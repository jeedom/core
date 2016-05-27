#!/bin/sh
VERT="\\033[1;32m"
NORMAL="\\033[0;39m"
ROUGE="\\033[1;31m"
ROSE="\\033[1;35m"
BLEU="\\033[1;34m"
BLANC="\\033[0;02m"
BLANCLAIR="\\033[1;08m"
JAUNE="\\033[1;33m"
CYAN="\\033[1;36m"

if [ $(id -u) != 0 ] ; then
    echo "Super-user (root) privileges are required to install Jeedom"
    echo "Please run 'sudo $0' or log in as root, and rerun $0"
    exit 1
fi

apt_install() {
  apt-get -y install "$@"
  if [ $? -ne 0 ]; then
    echo "${ROUGE}Could not install $@ - abort${NORMAL}"
    exit 1
  fi
}

mysql_sql() {
  echo "$@" | mysql -uroot -proot
  if [ $? -ne 0 ]; then
    echo "C${ROUGE}ould not execute $@ into mysql - abort${NORMAL}"
    exit 1
  fi
}

step_1_upgrade() {
	echo "---------------------------------------------------------------------"
	echo "${JAUNE}Start step_1_upgrade${NORMAL}"
	apt-get update
	apt-get -y dist-upgrade
	echo "${VERT}step_1_upgrade success${NORMAL}"
}

step_2_mainpackage() {
	echo "---------------------------------------------------------------------"
	echo "${JAUNE}Start step_2_mainpackage${NORMAL}"
	apt_install ntp ca-certificates unzip curl sudo
	echo "${VERT}step_2_mainpackage success${NORMAL}"
}

step_3_mysql() {
	echo "---------------------------------------------------------------------"
	echo "${JAUNE}Start step_3_mysql${NORMAL}"
	echo "mysql-server mysql-server/root_password password root" | debconf-set-selections
	echo "mysql-server mysql-server/root_password_again password root" | debconf-set-selections
	apt_install mysql-client mysql-common mysql-server
	service mysql start
	if [ $? -ne 0 ]; then
    	echo "${ROUGE}Could not start database - abort${NORMAL}"
    	exit 1
  	fi
	mysqladmin -u root password root
	if [ $? -ne 0 ]; then
    	echo "${ROUGE}Could not connect to database - abort${NORMAL}"
    	exit 1
  	fi
	echo "${VERT}step_3_mysql success${NORMAL}"
}

step_4_apache() {
	echo "---------------------------------------------------------------------"
	echo "${JAUNE}Start step_4_apache${NORMAL}"
	apt_install apache2 apache2-mpm-prefork apache2-utils libexpat1 ssl-cert
	apt_install	libapache2-mod-php5 php5 php5-common php5-curl php5-dev php5-gd php-pear php5-json php5-memcached php5-mysql php5-cli
	echo "${VERT}step_4_apache success${NORMAL}"
}

step_5_jeedom_download() {
	echo "---------------------------------------------------------------------"
	echo "${JAUNE}Start step_5_jeedom_download${NORMAL}"
	wget https://github.com/jeedom/core/archive/stable.zip -O /tmp/jeedom.zip
	if [ $? -ne 0 ]; then
		echo "${JAUNE}Could not download jeedom from github, use preadd version if exist${NORMAL}"
		if [ -f /root/jeedom.zip ]; then
			cp /root/jeedom.zip /tmp/jeedom.zip
		fi
	fi
	if [ ! /tmp/jeedom.zip ]; then
		echo "${ROUGE}Could not retrieve jeedom.zip package - abort${NORMAL}"
    	exit 1
	fi
	mkdir -p /var/www/html
	rm /var/www/html/index.html 2>&1 >> /dev/null
	rm -rf /root/core-*
	unzip -q /tmp/jeedom.zip -d /root/
	if [ $? -ne 0 ]; then
    	echo "${ROUGE}Could not unzip archive - abort${NORMAL}"
    	exit 1
  	fi
	cp -R /root/core-*/* /var/www/html/
	rm -rf /root/core-*
	echo "${VERT}step_5_jeedom_download success${NORMAL}"
}

step_6_jeedom_customization() {
	echo "---------------------------------------------------------------------"
	echo "${JAUNE}Start step_6_jeedom_customization${NORMAL}"
	cp /var/www/html/install/apache_security /etc/apache2/conf-available/security.conf
	rm /etc/apache2/conf-enabled/security.conf
	ln -s /etc/apache2/conf-available/security.conf /etc/apache2/conf-enabled/
	rm /etc/apache2/conf-available/other-vhosts-access-log.conf
	rm /etc/apache2/conf-enabled/other-vhosts-access-log.conf
	systemctl restart apache2
	if [ $? -ne 0 ]; then
    	echo "${ROUGE}Could not restart apache - abort${NORMAL}"
    	exit 1
  	fi
	echo "${VERT}step_6_jeedom_customization success${NORMAL}"
}

step_7_jeedom_installation() {
	echo "---------------------------------------------------------------------"
	echo "${JAUNE}Start step_7_jeedom_installation${NORMAL}"
	mysql_sql "DROP USER 'jeedom'@'%';"
	mysql_sql "CREATE USER 'jeedom'@'%' IDENTIFIED BY 'jeedom';"
	mysql_sql "DROP DATABASE IF EXISTS jeedom;"
	mysql_sql "CREATE DATABASE jeedom;"
	mysql_sql "GRANT ALL PRIVILEGES ON jeedom.* TO 'jeedom'@'%';"
	cp /var/www/html/core/config/common.config.sample.php /var/www/html/core/config/common.config.php
	sed -i "s/#PASSWORD#/jeedom/g" /var/www/html/core/config/common.config.php 
	sed -i "s/#DBNAME#/jeedom/g" /var/www/html/core/config/common.config.php 
	sed -i "s/#USERNAME#/jeedom/g" /var/www/html/core/config/common.config.php 
	sed -i "s/#PORT#/3306/g" /var/www/html/core/config/common.config.php 
	sed -i "s/#HOST#/localhost/g" /var/www/html/core/config/common.config.php 
	chmod 775 -R /var/www/html
	chown -R www-data:www-data /var/www/html
	php /var/www/html/install/install.php mode=force
	if [ $? -ne 0 ]; then
    	echo "${ROUGE}Could not install jeedom - abort${NORMAL}"
    	exit 1
  	fi
	echo "${VERT}step_7_jeedom_installation success${NORMAL}"
}

step_8_jeedom_crontab() {
	echo "---------------------------------------------------------------------"
	echo "${JAUNE}Start step_8_jeedom_crontab${NORMAL}"
	echo "* * * * * su --shell=/bin/bash - www-data -c '/usr/bin/php /var/www/html/core/php/jeeCron.php' >> /dev/null" | crontab -
	if [ $? -ne 0 ]; then
    	echo "${ROUGE}Could not install jeedom cron - abort${NORMAL}"
    	exit 1
  	fi
	echo "${VERT}step_8_jeedom_crontab success${NORMAL}"
}

step_9_jeedom_sudo() {
	echo "---------------------------------------------------------------------"
	echo "${JAUNE}Start step_9_jeedom_sudo${NORMAL}"
	echo "www-data ALL=(ALL) NOPASSWD: ALL" | (EDITOR="tee -a" visudo)
	if [ $? -ne 0 ]; then
    	echo "${ROUGE}Could not install make jeedom sudo - abort${NORMAL}"
    	exit 1
  	fi
	echo "${VERT}step_9_jeedom_sudo success${NORMAL}"
}

echo "${JAUNE}Welcome to jeedom installer${NORMAL}"
STEP=0

while getopts ":s:" opt; do
  case $opt in
    s) STEP="$OPTARG"
    ;;
    \?) echo "${ROUGE}Invalid option -$OPTARG${NORMAL}" >&2
    ;;
  esac
done

case ${STEP} in
   0)
	echo "${JAUNE}Start of all install step${NORMAL}"
	step_1_upgrade
	step_2_mainpackage
	step_3_mysql
	step_4_apache
	step_5_jeedom_download
	step_6_jeedom_customization
	step_7_jeedom_installation
	step_8_jeedom_crontab
	step_9_jeedom_sudo
	;;
   1) step_1_upgrade
	;;
   2) step_2_mainpackage
	;;
   3) step_3_mysql
	;;
   4) step_4_apache
	;;
   5) step_5_jeedom_download
	;;
   6) step_6_jeedom_customization
	;;
   7) step_7_jeedom_installation
	;;
   8) step_8_jeedom_crontab
	;;
   9) step_9_jeedom_sudo
	;;
   *) echo "${ROUGE}Sorry, I can not get a ${STEP} step for you!${NORMAL}"
	;;
esac