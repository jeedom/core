#!/bin/sh

if [ $(id -u) != 0 ] ; then
    echo "Super-user (root) privileges are required to install Jeedom"
    echo "Please run 'sudo $0' or log in as root, and rerun $0"
    exit 1
fi

apt_install() {
  apt-get -y install "$@"
  if [ $? -ne 0 ]; then
    echo "Could not install $@ - abort"
    exit 1
  fi
}

step_1_upgrade() {
	echo "---------------------------------------------------------------------"
	echo "Start " ${FUNCNAME[ 0 ]}
	apt-get update
	apt-get -y dist-upgrade
	echo  ${FUNCNAME[ 0 ]} " success"
}

step_2_mainpackage() {
	echo "---------------------------------------------------------------------"
	echo "Start " ${FUNCNAME[ 0 ]}
	apt_install ntp ca-certificates unzip curl sudo
	echo  ${FUNCNAME[ 0 ]} " success"
}

step_3_mysql() {
	echo "---------------------------------------------------------------------"
	echo "Start " ${FUNCNAME[ 0 ]}
	echo "mysql-server mysql-server/root_password password root" | debconf-set-selections
	echo "mysql-server mysql-server/root_password_again password root" | debconf-set-selections
	apt_install mysql-client mysql-common mysql-server
	mysqladmin -u root password root
	echo  ${FUNCNAME[ 0 ]} " success"
}

step_4_apache() {
	echo "---------------------------------------------------------------------"
	echo "Start " ${FUNCNAME[ 0 ]}
	apt_install apache2 apache2-mpm-prefork apache2-utils libexpat1 ssl-cert
	apt_install	libapache2-mod-php5 php5 php5-common php5-curl php5-dev php5-gd php-pear php5-json php5-memcached php5-mysql php5-cli
	echo  ${FUNCNAME[ 0 ]} " success"
}

step_5_jeedom_download() {
	echo "---------------------------------------------------------------------"
	echo "Start " ${FUNCNAME[ 0 ]}
	wget https://github.com/jeedom/core/archive/stable.zip -O /tmp/jeedom.zip
	if [ $? -ne 0 ]; then
		echo "Could not download jeedom from github, use preadd version if exist"
		if [ -f /root/jeedom.zip ]; then

		fi
	fi
	if [ ! -e /tmp/jeedom.zip ]; then
		echo "Could not retrieve jeedom.zip package - abort"
    	exit 1
	fi
	mkdir -p /var/www/html
	rm /var/www/html/index.html 2>&1 >> /dev/null
	rm -rf /root/core-*
	unzip -q /tmp/jeedom.zip -d /root/
	cp -R /root/core-*/* /var/www/html/
	rm -rf /root/core-*
	echo  ${FUNCNAME[ 0 ]} " success"
}

step_6_jeedom_customization() {
	echo "---------------------------------------------------------------------"
	echo "Start " ${FUNCNAME[ 0 ]}
	cp /var/www/html/install/apache_security /etc/apache2/conf-available/security.conf
	rm /etc/apache2/conf-enabled/security.conf
	ln -s /etc/apache2/conf-available/security.conf /etc/apache2/conf-enabled/
	rm /etc/apache2/conf-available/other-vhosts-access-log.conf
	rm /etc/apache2/conf-enabled/other-vhosts-access-log.conf
	systemctl restart apache2
	echo  ${FUNCNAME[ 0 ]} " success"
}

step_7_jeedom_installation() {
	echo "---------------------------------------------------------------------"
	echo "Start " ${FUNCNAME[ 0 ]}
	echo "DROP USER 'jeedom'@'%';" | mysql -uroot -proot
	echo "CREATE USER 'jeedom'@'%' IDENTIFIED BY 'jeedom';" | mysql -uroot -proot
	echo "DROP DATABASE IF EXISTS jeedom;" | mysql -uroot -proot
	echo "CREATE DATABASE jeedom;" | mysql -uroot -proot
	echo "GRANT ALL PRIVILEGES ON jeedom.* TO 'jeedom'@'%';" | mysql -uroot -proot
	cp /var/www/html/core/config/common.config.sample.php /var/www/html/core/config/common.config.php
	sed -i "s/#PASSWORD#/jeedom/g" /var/www/html/core/config/common.config.php 
	sed -i "s/#DBNAME#/jeedom/g" /var/www/html/core/config/common.config.php 
	sed -i "s/#USERNAME#/jeedom/g" /var/www/html/core/config/common.config.php 
	sed -i "s/#PORT#/3306/g" /var/www/html/core/config/common.config.php 
	sed -i "s/#HOST#/localhost/g" /var/www/html/core/config/common.config.php 
	chmod 775 -R /var/www/html
	chown -R www-data:www-data /var/www/html
	php /var/www/html/install/install.php mode=force
	echo  ${FUNCNAME[ 0 ]} " success"
}

step_8_jeedom_crontab() {
	echo "---------------------------------------------------------------------"
	echo "Start " ${FUNCNAME[ 0 ]}
	echo "* * * * * su --shell=/bin/bash - www-data -c '/usr/bin/php /var/www/html/core/php/jeeCron.php' >> /dev/null" | crontab -
	echo  ${FUNCNAME[ 0 ]} " success"
}

step_9_jeedom_sudo() {
	echo "---------------------------------------------------------------------"
	echo "Start " ${FUNCNAME[ 0 ]}
	echo "www-data ALL=(ALL) NOPASSWD: ALL" | (EDITOR="tee -a" visudo)
	echo  ${FUNCNAME[ 0 ]} " success"
}

echo "Welcome to jeedom installer"
STEP=0

while getopts ":s:" opt; do
  case $opt in
    s) STEP="$OPTARG"
    ;;
    \?) echo "Invalid option -$OPTARG" >&2
    ;;
  esac
done

if [ ${STEP} -eq -1 ]; then
	echo 'Launching complete install step'
	step_1_upgrade
	step_2_mainpackage
	step_3_mysql
	step_4_apache
	step_5_jeedom_download
	step_6_jeedom_customization
	step_7_jeedom_installation
	step_8_jeedom_crontab
	step_9_jeedom_sudo
else
fi

case ${STEP} in
   0)
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
   *) echo "Sorry, I can not get a ${STEP} step for you!"
	;;
esac