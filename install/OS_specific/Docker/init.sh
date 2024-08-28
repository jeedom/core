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
}

db_creds(){
  cp ${WEBSERVER_HOME}/core/config/common.config.sample.php ${WEBSERVER_HOME}/core/config/common.config.php
  sed -i "s/#PASSWORD#/${DB_PASSWORD}/g" ${WEBSERVER_HOME}/core/config/common.config.php
  sed -i "s/#DBNAME#/${DB_NAME:-jeedom}/g" ${WEBSERVER_HOME}/core/config/common.config.php
  sed -i "s/#USERNAME#/${DB_USERNAME:-jeedom}/g" ${WEBSERVER_HOME}/core/config/common.config.php
  sed -i "s/#PORT#/${DB_PORT:-3306}/g" ${WEBSERVER_HOME}/core/config/common.config.php
  sed -i "s/#HOST#/${DB_HOST:-localhost}/g" ${WEBSERVER_HOME}/core/config/common.config.php
}

#Main
# $WEBSERVER_HOME and $VERSION env variables comes from Dockerfile
set +e
dpkg -l mariabd-server 2>/dev/null
status=$?
ISMARIADBSERVER=$(( 1 - ${status} ))

#define php db conf
db_creds

#check if database is populated
isDB=$(mysql -u${DB_USERNAME} -p${DB_PASSWORD} -h ${DB_HOST} -P${DB_PORT} -BNe "show databases;" | grep -c ${DB_NAME})

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

    DB_PASSWORD=$(openssl rand -base64 32 | tr -d /=+ | cut -c 15)
    echo "DROP USER IF EXISTS 'jeedom'@'%';" | mysql
    echo "CREATE USER 'jeedom'@'%' IDENTIFIED BY '${DB_PASSWORD}';" | mysql
    echo "DROP DATABASE IF EXISTS jeedom;" | mysql
    echo "CREATE DATABASE jeedom;" | mysql
    echo "GRANT ALL PRIVILEGES ON jeedom.* TO 'jeedom'@'localhost';" | mysql
  fi

  echo "************************
start JEEDOM PHP script installation
************************"

	php "${WEBSERVER_HOME}/install/install.php" mode=force
	# remove the flag file after the first successfull installation
	rm "${WEBSERVER_HOME}/initialisation"
fi

#docker specific: create DB/user if not local
# should not be used, as mysql/maria docker image can create user and database at first start.
if [[ 0 -eq ${isDB} ]] && [[ localhost != ${DB_HOST} ]]; then
  echo "la bdd n'est pas locale et le schema n'a pas ete trouvé. Arret du conteneur"
  docker_stop
else
  #populate database
  echo "Ajout des tables dans le schema ${DB_NAME}"
  php "${WEBSERVER_HOME}/install/install.php" mode=force
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