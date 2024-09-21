#!/bin/bash

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

docker_stop(){
	echo "${JAUNE}Stopping Jeedom container${NORMAL}"
	echo "${VERT}Killing CRON${NORMAL}"
	killall cron
	echo "${VERT}Stopping Apache gracefully${NORMAL}"
	service apache2 stop
	echo "${VERT}Stopping Database gracefully${NORMAL}"
	service mariadb stop
	echo "${VERT}Stopping ATD gracefully${NORMAL}"
	service atd stop
	echo "${ROUGE}Requesting stop on init.sh${NORMAL}"
	touch "${FILE_STOP}"
	exit 0
}

# flag to fail fast on errors
set -e

# $WEBSERVER_HOME and $VERSION env variables comes from Dockerfile

if [[ -f "${WEBSERVER_HOME}/initialisation" ]]; then
    echo "************************
Start Jeedom initialisation !
************************"
	JEEDOM_INSTALL=0

    echo "************************
Start mariadb service
************************"

	service mariadb start
	service mariadb status

	DB_PASSWORD=$(cat /dev/urandom | tr -cd 'a-f0-9' | head -c 15)
	echo "DROP USER IF EXISTS '${DB_USERNAME:-jeedom}'@'${DB_HOST:-localhost}';" | mysql
	echo "CREATE USER '${DB_USERNAME:-jeedom}'@'${DB_HOST:-localhost}' IDENTIFIED BY '${DB_PASSWORD}';" | mysql
	echo "DROP DATABASE IF EXISTS ${DB_NAME:-jeedom};" | mysql
	echo "CREATE DATABASE ${DB_NAME:-jeedom};" | mysql
	echo "GRANT ALL PRIVILEGES ON ${DB_NAME:-jeedom}.* TO '${DB_USERNAME:-jeedom}'@'${DB_HOST:-localhost}';" | mysql

	cp "${WEBSERVER_HOME}/core/config/common.config.sample.php" "${WEBSERVER_HOME}/core/config/common.config.php"
	sed -i "s/#PASSWORD#/${DB_PASSWORD}/g" "${WEBSERVER_HOME}/core/config/common.config.php"
	sed -i "s/#DBNAME#/${DB_NAME:-jeedom}/g" "${WEBSERVER_HOME}/core/config/common.config.php"
	sed -i "s/#USERNAME#/${DB_USERNAME:-jeedom}/g" "${WEBSERVER_HOME}/core/config/common.config.php"
	sed -i "s/#PORT#/${DB_PORT:-3306}/g" "${WEBSERVER_HOME}/core/config/common.config.php"
	sed -i "s/#HOST#/${DB_HOST:-localhost}/g" "${WEBSERVER_HOME}/core/config/common.config.php"

    echo "************************
Start Jeedom PHP script installation
************************"

	php "${WEBSERVER_HOME}/install/install.php" mode=force
	# remove the flag file after the first successfull installation
	rm "${WEBSERVER_HOME}/initialisation"
fi

if [[ "${JEEDOM_INSTALL}" == 0 ]] && [[ ! -z "${ADMIN_PASSWORD}" ]]; then
	echo "Set admin password with env var"
	php "${WEBSERVER_HOME}/core/php/jeecli.php" user password admin "${ADMIN_PASSWORD}"
fi

echo "Start atd service"
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

echo "Start apache2 service"
if [[ "${APACHE_PORT}" != 80 ]]; then
  echo "Port update for apache2: ${APACHE_PORT}"
  echo "Listen ${APACHE_PORT}" > /etc/apache2/ports.conf
  sed -i -E "s/\<VirtualHost \*:(.*)\>/VirtualHost \*:${APACHE_PORT}/" /etc/apache2/sites-available/000-default.conf
fi
service apache2 start
service apache2 status

echo "Start cron daemon"
cron

# step_12_jeedom_check
sh /tmp/install.sh -s 12 -v "${VERSION}" -w "${WEBSERVER_HOME}" -i docker

#TAKE CARE : the init.sh script is running under sh so trap only takes signal_number
echo "Add trap docker_stop"
trap "docker_stop $$ ;" 15

while [[ ! -e "${FILE_STOP}" ]]; do sleep 1; done
