#!/usr/bin/env bash

#Variables
VERT="\\033[1;32m"
NORMAL="\\033[0;39m"
ROUGE="\\033[1;31m"
ROSE="\\033[1;35m"
BLEU="\\033[1;34m"
BLANC="\\033[0;02m"
BLANCLAIR="\\033[1;08m"
JAUNE="\\033[1;33m"
CYAN="\\033[1;36m"

FILE_STOP="/root/stop_requested"
if [[ -e "${FILE_STOP}" ]]; then rm ${FILE_STOP}; fi

# flag to fail fast on errors
set -e
# enable debug if required.
[[ ${DEBUG:-0} -eq 1 ]] && set -x ||true


#Functions
docker_stop() {
  echo "${JAUNE}Stopping Jeedom container${NORMAL}"
  echo "${VERT}Killing CRON${NORMAL}"
  killall cron
  echo "${VERT}Stopping Apache gracefully${NORMAL}"
  service apache2 stop
  if [[ 1 -eq ${ISMARIABDBINSTALLED} ]]; then
     echo "${VERT}Stopping Database gracefully${NORMAL}"
     service mariadb stop
  fi
  echo "${VERT}Stopping ATD gracefully${NORMAL}"
  service atd stop
  echo "${ROUGE}Requesting stop on init.sh${NORMAL}"
  touch ${FILE_STOP}
  exit 0
}

setTimeZone() {
  [[ ${TZ} == $(</etc/timezone) ]] && return
  echo "Setting timezone to ${TZ}"
  ln -fs /usr/share/zoneinfo/${TZ} /etc/localtime
  dpkg-reconfigure -fnoninteractive tzdata
}

set_root_password(){
  #set root password
  if [[ -z ${ROOT_PASSWD} ]]; then
    ROOT_PASSWD=$(openssl rand -base64 32 | tr -d /=+ | cut -c1-15)
    echo "Use generate password : ${ROOT_PASSWD}"
  fi
  echo "root:${ROOT_PASSWD}" | chpasswd
}

apache_setup() {
  mkdir -p /var/log/apache2/
  #define ports, activate ssl
  if [[ 3 -ne $(grep -cP "(${APACHE_HTTP_PORT}|${APACHE_HTTPS_PORT})" /etc/apache2/ports.conf) ]]; then
    echo "Ports update for apache2: ${APACHE_HTTP_PORT}, ${APACHE_HTTPS_PORT}"
    echo "Listen ${APACHE_HTTP_PORT}

<IfModule ssl_module>
	Listen ${APACHE_HTTPS_PORT:-443}
</IfModule>

<IfModule mod_gnutls.c>
	Listen ${APACHE_HTTPS_PORT:-443}
</IfModule>" >/etc/apache2/ports.conf
    sed -i -E "s/\<VirtualHost \*:(.*)\>/VirtualHost \*:${APACHE_HTTP_PORT}/" /etc/apache2/sites-available/000-default.conf
    sed -i -E "s/\<VirtualHost \*:(.*)\>/VirtualHost \*:${APACHE_HTTPS_PORT}/" /etc/apache2/sites-available/default-ssl.conf
  fi

  sed -i 's#/var/log/apache2#/var/www/html/log/#' /etc/apache2/envvars
  sed -i 's#/var/log/apache2#/var/www/html/log#' /etc/logrotate.d/apache2

  [[ $(a2query -m ssl | grep -c "^ssl") -eq 0 ]] && a2enmod ssl || true
  [[ $(a2query -s default-ssl | grep -c "^default-ssl") -eq 0 ]] && a2ensite default-ssl
  [[ $(a2query -s 000-default | grep -c "^000-default") -eq 0 ]] && a2ensite 000-default
}

db_creds(){
  cp ${WEBSERVER_HOME}/core/config/common.config.sample.php ${WEBSERVER_HOME}/core/config/common.config.php
  sed -i "s/#PASSWORD#/${DB_PASSWORD}/g" ${WEBSERVER_HOME}/core/config/common.config.php
  sed -i "s/#DBNAME#/${DB_NAME:-jeedom}/g" ${WEBSERVER_HOME}/core/config/common.config.php
  sed -i "s/#USERNAME#/${DB_USERNAME:-jeedom}/g" ${WEBSERVER_HOME}/core/config/common.config.php
  sed -i "s/#PORT#/${DB_PORT:-3306}/g" ${WEBSERVER_HOME}/core/config/common.config.php
  sed -i "s/#HOST#/${DB_HOST:-localhost}/g" ${WEBSERVER_HOME}/core/config/common.config.php
}

save_db_decrypt_key() {
  # check if env jeedom encryption key is defined
  if [[ -n ${JEEDOM_ENCRYPTION_KEY} ]]; then
    #write jeedom encryption key if different
    if [[ ! -e /var/www/html/data/jeedom_encryption.key ]] || [[ "$(cat /var/www/html/data/jeedom_encryption.key)" != "${JEEDOM_ENCRYPTION_KEY}" ]]; then
      echo "Writing jeedom encryption key as defined in env"
      echo "${JEEDOM_ENCRYPTION_KEY}" >${WEBSERVER_HOME}/data/jeedom_encryption.key
    fi
  fi
}

#Main
# $WEBSERVER_HOME and $VERSION env variables comes from Dockerfile
set +e
dpkg -l mariadb-server 2>/dev/null
status=$?
ISMARIADBSERVER=$(( 1 - ${status} ))

#Get vars from secrets
for s in JEEDOM_ENCRYPTION_KEY DB_ROOT_PASSWD DB_PASSWORD ROOT_PASSWORD; do
  if [[ -f /run/secrets/${s} ]]; then
    echo "Reading ${s} from secrets"
    eval ${s}=$(cat /run/secrets/${s})
    [[ 1 -eq ${DEBUG} ]] && echo "${s}: ${!s}" || true
  fi
done

#define php db conf
db_creds

if [[ -f ${WEBSERVER_HOME}/initialisation ]]; then
  echo "************************
Start Jeedom initialisation !
************************"
  JEEDOM_INSTALL=0
  # mariadb server is installed
  if [[ 1 -eq ${ISMARIADBSERVER} ]]; then
    echo "************************
  Start mariadb service
  ************************"
    service mariadb start
    service mariadb status
    DB_PASSWORD=$(openssl rand -base64 32 | tr -d /=+)
    echo "DROP USER IF EXISTS 'jeedom'@'%';" | mysql
    echo "CREATE USER 'jeedom'@'%' IDENTIFIED BY '${DB_PASSWORD}';" | mysql
    echo "DROP DATABASE IF EXISTS jeedom;" | mysql
    echo "CREATE DATABASE jeedom;" | mysql
    echo "GRANT ALL PRIVILEGES ON jeedom.* TO 'jeedom'@'%';" | mysql
  fi
  echo "************************
start JEEDOM PHP script installation
************************"
	php "${WEBSERVER_HOME}/install/install.php" mode=force
	# remove the flag file after the first successfull installation
	rm "${WEBSERVER_HOME}/initialisation"
else
  isTables=$(mysql -u${DB_USERNAME} -p${DB_PASSWORD} -h ${DB_HOST} -P${DB_PORT} ${DB_NAME} -e "show tables;" | wc -l)
  if [[ ${isTables:-0} -eq 0 ]]; then
    php "${WEBSERVER_HOME}/install/install.php" mode=force
  fi
fi

#set admin password if needed
if [[ "${JEEDOM_INSTALL}" == 0 ]] && [[ ! -z "${ADMIN_PASSWORD}" ]]; then
	echo "Set admin password with env var"
	php "${WEBSERVER_HOME}/core/php/jeecli.php" user password admin "${ADMIN_PASSWORD}"
fi

#set timezone
setTimeZone
#setup apache port
apache_setup
#setup root passwd
set_root_password
#allow db secrets decode when using external db.
save_db_decrypt_key
#save db config fil
db_creds

service atd restart
service atd status

if [[ $(which mysqld | wc -l) -ne 0 ]]; then
	echo "Restarting mariadb service"
	chown -R mysql:mysql /var/lib/mysql /var/run/mysqld
	service mariadb restart
	if [ $? -ne 0 ]; then
		# That can lead to FATAL corruption of databases
		# rm /var/lib/mysql/ib_logfile*
		echo "${ROUGE}Starting Database FAILED${NORMAL}"
		exit 1
	fi
fi

if [[ "${JEEDOM_INSTALL}" == 0 ]] && [[ ! -z "${RESTOREBACKUP}" ]] && [[ "${RESTOREBACKUP}" != "NO" ]]; then
	echo "Need restore backup ${RESTOREBACKUP}"
	wget "${RESTOREBACKUP}" -O /tmp/backup.tar.gz
	php "${WEBSERVER_HOME}/install/restore.php backup=/tmp/backup.tar.gz"
	rm /tmp/backup.tar.gz
	if [[ ! -z "${UPDATEJEEDOM}" ]] && [[ "${UPDATEJEEDOM}" != "NO" ]]; then
		echo "Need update Jeedom"
		php "${WEBSERVER_HOME}/install/update.php"
	fi
fi

echo "All init complete"
chmod 777 /dev/tty*
chmod 755 -R "${WEBSERVER_HOME}"

#redirect logs to container stdout
if [[ ${LOGS_TO_STDOUT,,} =~ [yo] ]]; then
  echo "Send apache logs to stdout/err"
  [[ -f /var/log/apache2/access.log ]] && rm -Rf /var/log/apache2/* || true
  ln -sf /proc/1/fd/1 /var/www/html/log/access.log
  ln -sf /proc/1/fd/1 /var/www/html/log/error.log
  chown -R www-data:www-data /var/www/html/log/
else
  [[ -L /var/log/apache2/access.log ]] && rm -f /var/log/apache2/{access,error}.log && echo "Remove apache symlink to stdout/stderr" || echo
fi

service apache2 start
service apache2 status

echo "Start cron daemon"
cron

# step_12_jeedom_check
sh ${WEBSERVER_HOME}/install/install.sh -s 12 -v ${VERSION} -w ${WEBSERVER_HOME} -i docker

#TAKE CARE : the init.sh script is running under sh so trap only takes signal_number
echo "Add trap docker_stop"
trap "docker_stop $$ ;" 15

while [[ ! -e "${FILE_STOP}" ]]; do sleep 1; done
rm ${FILE_STOP}