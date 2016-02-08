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

echo "mysql-server mysql-server/root_password password root" | debconf-set-selections
echo "mysql-server mysql-server/root_password_again password root" | debconf-set-selections
apt-get -y install mysql-client mysql-common mysql-server
mysqladmin -u root password root
echo "DROP USER 'jeedom'@'%';" | mysql -uroot -proot
echo "CREATE USER 'jeedom'@'%' IDENTIFIED BY 'jeedom';" | mysql -uroot -proot
echo "DROP DATABASE IF EXISTS jeedom;" | mysql -uroot -proot
echo "CREATE DATABASE jeedom;" | mysql -uroot -proot
echo "GRANT ALL PRIVILEGES ON jeedom.* TO 'jeedom'@'%';" | mysql -uroot -proot
cp core/config/common.config.sample.php ${WEBSERVER_HOME}/core/config/common.config.php
sed -i "s/#PASSWORD#/jeedom/g" ${WEBSERVER_HOME}/core/config/common.config.php 
sed -i "s/#DBNAME#/jeedom/g" ${WEBSERVER_HOME}/core/config/common.config.php 
sed -i "s/#USERNAME#/jeedom/g" ${WEBSERVER_HOME}/core/config/common.config.php 
sed -i "s/#PORT#/3306/g" ${WEBSERVER_HOME}/core/config/common.config.php 
sed -i "s/#HOST#/localhost/g" ${WEBSERVER_HOME}/core/config/common.config.php 

wget https://raw.githubusercontent.com/jeedom/core/stable/install/apache_security -O /etc/apache2/conf-available/security.conf
systemctl restart apache2
apt-get -y autoremove

chmod 775 -R ${WEBSERVER_HOME}
chown -R www-data:www-data ${WEBSERVER_HOME}
cd ${WEBSERVER_HOME}

