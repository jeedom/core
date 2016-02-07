#!/bin/sh
# Check for root priviledges
if [ $(id -u) != 0 ] ; then
    echo "Super-user (root) privileges are required to install Jeedom"
    echo "Please run 'sudo $0' or log in as root, and rerun $0"
    exit 1
fi
WEBSERVER_HOME=$(pwd)
apt-get update
apt-get -y install ca-certificates unzip curl sudo ntp
apt-get -y install apache2 php5 libapache2-mod-php5 php5-cli php5-common php5-curl php5-fpm php5-json php5-mysql php5-gd 

JEEDOM_CRON="`crontab -l | grep -e 'jeeCron.php'`"
if [ -z "${JEEDOM_CRON}" ] ; then
    croncmd="su --shell=/bin/bash - www-data -c '/usr/bin/php ${WEBSERVER_HOME}/core/php/jeeCron.php' >> /dev/null 2>&1"
    cronjob="* * * * * $croncmd"
    ( crontab -l | grep -v "$croncmd" ; echo "$cronjob" ) | crontab -
fi
echo "www-data ALL=(ALL) NOPASSWD: ALL" | (EDITOR="tee -a" visudo)

if [ -z $1 -a "$1" = "travis" ]; then
    MYSQL_JEEDOM_USER=jeedom
    MYSQL_JEEDOM_DBNAME=jeedom
    MYSQL_JEEDOM_PASSWORD=jeedom
    MYSQL_HOST=localhost
    MYSQL_PORT=3306
    echo "mysql-server mysql-server/root_password password root" | debconf-set-selections
    echo "mysql-server mysql-server/root_password_again password root" | debconf-set-selections
    apt-get -y install mysql-client mysql-common mysql-server
    mysqladmin -u root password root
    echo "DROP USER '${MYSQL_JEEDOM_USER}'@'%';" | mysql -uroot -proot
    echo "CREATE USER '${MYSQL_JEEDOM_USER}'@'%' IDENTIFIED BY '${MYSQL_JEEDOM_PASSWORD}';" | mysql -uroot -proot
    echo "DROP DATABASE IF EXISTS ${MYSQL_JEEDOM_DBNAME};" | mysql -uroot -proot
    echo "CREATE DATABASE ${MYSQL_JEEDOM_DBNAME};" | mysql -uroot -proot
    echo "GRANT ALL PRIVILEGES ON ${MYSQL_JEEDOM_DBNAME}.* TO '${MYSQL_JEEDOM_USER}'@'%';" | mysql -uroot -proot
    cp core/config/common.config.sample.php ${WEBSERVER_HOME}/core/config/common.config.php
    sed -i "s/#PASSWORD#/${bdd_password}/g" ${WEBSERVER_HOME}/core/config/common.config.php 
    sed -i "s/#DBNAME#/${MYSQL_JEEDOM_DBNAME}/g" ${WEBSERVER_HOME}/core/config/common.config.php 
    sed -i "s/#USERNAME#/${MYSQL_JEEDOM_USER}/g" ${WEBSERVER_HOME}/core/config/common.config.php 
    sed -i "s/#PORT#/${MYSQL_PORT}/g" ${WEBSERVER_HOME}/core/config/common.config.php 
    sed -i "s/#HOST#/${MYSQL_HOST}/g" ${WEBSERVER_HOME}/core/config/common.config.php 
else
    apt-get -y install mysql-client mysql-common mysql-server
fi



wget https://raw.githubusercontent.com/jeedom/core/stable/install/apache_security -O /etc/apache2/conf-available/security.conf
systemctl restart apache2
apt-get autoremove

chmod 775 -R ${WEBSERVER_HOME}
chown -R www-data:www-data ${WEBSERVER_HOME}
cd ${WEBSERVER_HOME}

