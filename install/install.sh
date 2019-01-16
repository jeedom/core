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
    echo "Les droits de super-utilisateur (root) sont requis pour installer Jeedom"
    echo "Veuillez lancer 'sudo $0' ou connectez-vous en tant que root, puis relancez $0"
    exit 1
fi

apt_install() {
  apt-get -y install "$@"
  if [ $? -ne 0 ]; then
	echo "${ROUGE}Ne peut installer $@ - Annulation${NORMAL}"
	exit 1
  fi
}

mysql_sql() {
  echo "$@" | mysql -uroot -p${MYSQL_ROOT_PASSWD}
  if [ $? -ne 0 ]; then
    echo "C${ROUGE}Ne peut exécuter $@ dans MySQL - Annulation${NORMAL}"
    exit 1
  fi
}

step_1_upgrade() {
	echo "---------------------------------------------------------------------"
	echo "${JAUNE}Commence l'étape 1 de la révision${NORMAL}"

	apt-get update
	apt-get -f install
	apt-get -y dist-upgrade
	echo "${VERT}étape 1 de la révision réussie${NORMAL}"
}

step_2_mainpackage() {
	echo "---------------------------------------------------------------------"
	echo "${JAUNE}Commence l'étape 2 paquet principal${NORMAL}"
	apt_install ntp ca-certificates unzip curl sudo cron
	apt-get -y install locate tar telnet wget logrotate fail2ban dos2unix ntpdate htop iotop vim iftop smbclient
	apt-get -y install git python python-pip
	apt-get -y install software-properties-common
	apt-get -y install libexpat1 ssl-cert
	apt-get -y install apt-transport-https
	apt-get -y install xvfb cutycapt xauth
	apt-get -y install duplicity
	add-apt-repository non-free
	apt-get update
	apt-get -y install libav-tools
	apt-get -y install libsox-fmt-mp3 sox libttspico-utils
	apt-get -y install espeak
	apt-get -y install mbrola
	apt-get -y remove brltty
	echo "${VERT}étape 2 paquet principal réussie${NORMAL}"
}

step_3_database() {
	echo "---------------------------------------------------------------------"
	echo "${JAUNE}Commence l'étape 3 base de données${NORMAL}"
	echo "mysql-server mysql-server/root_password password ${MYSQL_ROOT_PASSWD}" | debconf-set-selections
	echo "mysql-server mysql-server/root_password_again password ${MYSQL_ROOT_PASSWD}" | debconf-set-selections
	apt_install mysql-client mysql-common mysql-server

	mysqladmin -u root password ${MYSQL_ROOT_PASSWD}

	systemctl status mysql > /dev/null 2>&1
	if [ $? -ne 0 ]; then
    	service mysql status
		if [ $? -ne 0 ]; then
    		systemctl start mysql > /dev/null 2>&1
    		if [ $? -ne 0 ]; then
				service mysql start > /dev/null 2>&1
			fi
  		fi
  	fi
  	systemctl status mysql > /dev/null 2>&1
	if [ $? -ne 0 ]; then
    	service mysql status
		if [ $? -ne 0 ]; then
    		echo "${ROUGE}Ne peut lancer mysql - Annulation${NORMAL}"
    		exit 1
  		fi
  	fi
	echo "${VERT}étape 3 base de données réussie${NORMAL}"
}

step_4_apache() {
	echo "---------------------------------------------------------------------"
	echo "${JAUNE}Commence l'étape 4 apache${NORMAL}"
	apt_install apache2 apache2-utils libexpat1 ssl-cert
	echo "${VERT}étape 4 apache réussie${NORMAL}"
}

step_5_php() {
	echo "---------------------------------------------------------------------"
	echo "${JAUNE}Commence l'étape 5 php${NORMAL}"
	apt-get -y install php7.0 php7.0-curl php7.0-gd php7.0-imap php7.0-json php7.0-mcrypt php7.0-mysql php7.0-xml php7.0-opcache php7.0-soap php7.0-xmlrpc libapache2-mod-php7.0 php7.0-common php7.0-dev php7.0-zip php7.0-ssh2 php7.0-mbstring
	if [ $? -ne 0 ]; then
		apt_install libapache2-mod-php5 php5 php5-common php5-curl php5-dev php5-gd php5-json php5-memcached php5-mysqlnd php5-cli php5-ssh2 php5-redis php5-mbstring
		apt_install php5-ldap
	else
		apt-get -y install php7.0-ldap
	fi
	echo "${VERT}étape 5 php réussie${NORMAL}"
}

step_6_jeedom_download() {
	echo "---------------------------------------------------------------------"
	echo "${JAUNE}Commence l'étape 6 téléchargement de jeedom${NORMAL}"
	wget https://github.com/jeedom/core/archive/${VERSION}.zip -O /tmp/jeedom.zip
	if [ $? -ne 0 ]; then
		echo "${JAUNE}Ne peut télécharger Jeedom depuis github. Utilisez la version de déploiement si elle existe${NORMAL}"
		if [ -f /root/jeedom.zip ]; then
			cp /root/jeedom.zip /tmp/jeedom.zip
		fi
	fi
	if [ ! /tmp/jeedom.zip ]; then
		echo "${ROUGE}Ne peut trouver l'archive jeedom.zip - Annulation${NORMAL}"
    	exit 1
	fi
	mkdir -p ${WEBSERVER_HOME}
	find ${WEBSERVER_HOME} ! -name 'index.html' -type f -exec rm -rf {} +
	rm -rf /root/core-*
	unzip -q /tmp/jeedom.zip -d /root/
	if [ $? -ne 0 ]; then
    	echo "${ROUGE}Ne peut décompresser l'archive - Annulation${NORMAL}"
    	exit 1
  	fi
	cp -R /root/core-*/* ${WEBSERVER_HOME}
	cp -R /root/core-*/.[^.]* ${WEBSERVER_HOME}
	rm -rf /root/core-* > /dev/null 2>&1
	rm /tmp/jeedom.zip
	echo "${VERT}étape 6 téléchargement de jeedom réussie${NORMAL}"
}

step_7_jeedom_customization() {
	echo "---------------------------------------------------------------------"
	echo "${JAUNE}Commence l'étape 7 personnalisation de jeedom${NORMAL}"
	cp ${WEBSERVER_HOME}/install/apache_security /etc/apache2/conf-available/security.conf
	rm /etc/apache2/conf-enabled/security.conf > /dev/null 2>&1
	ln -s /etc/apache2/conf-available/security.conf /etc/apache2/conf-enabled/

	cp ${WEBSERVER_HOME}/install/apache_default /etc/apache2/sites-available/000-default.conf
	rm /etc/apache2/sites-enabled/000-default.conf > /dev/null 2>&1
	ln -s /etc/apache2/sites-available/000-default.conf /etc/apache2/sites-enabled/

	rm /etc/apache2/conf-available/other-vhosts-access-log.conf > /dev/null 2>&1
	rm /etc/apache2/conf-enabled/other-vhosts-access-log.conf > /dev/null 2>&1

	mkdir /etc/systemd/system/apache2.service.d
	echo "[Service]" > /etc/systemd/system/apache2.service.d/privatetmp.conf
	echo "PrivateTmp=no" >> /etc/systemd/system/apache2.service.d/privatetmp.conf

	systemctl daemon-reload

	for file in $(find / -iname php.ini -type f); do
		echo "Update php file ${file}"
		sed -i 's/max_execution_time = 30/max_execution_time = 600/g' ${file} > /dev/null 2>&1
	    sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 1G/g' ${file} > /dev/null 2>&1
	    sed -i 's/post_max_size = 8M/post_max_size = 1G/g' ${file} > /dev/null 2>&1
	    sed -i 's/expose_php = On/expose_php = Off/g' ${file} > /dev/null 2>&1
	    sed -i 's/;opcache.enable=0/opcache.enable=1/g' ${file} > /dev/null 2>&1
	    sed -i 's/opcache.enable=0/opcache.enable=1/g' ${file} > /dev/null 2>&1
	    sed -i 's/;opcache.enable_cli=0/opcache.enable_cli=1/g' ${file} > /dev/null 2>&1
	    sed -i 's/opcache.enable_cli=0/opcache.enable_cli=1/g' ${file} > /dev/null 2>&1
	    sed -i 's/memory_limit = 128M/memory_limit = 256M/g' ${file} > /dev/null 2>&1
	done

	a2dismod status
	systemctl restart apache2 > /dev/null 2>&1
	if [ $? -ne 0 ]; then
		service apache2 restart
		if [ $? -ne 0 ]; then
    		echo "${ROUGE}Ne peut redémarrer apache - Annulation${NORMAL}"
    		exit 1
  		fi
  	fi

  	systemctl stop mysql > /dev/null 2>&1
	if [ $? -ne 0 ]; then
		service mysql stop
		if [ $? -ne 0 ]; then
    		echo "${ROUGE}Ne peut arrêter mysql - Annulation${NORMAL}"
    		exit 1
  		fi
  	fi

    rm /var/lib/mysql/ib_logfile*

    if [ -d /etc/mysql/conf.d ]; then
    	touch /etc/mysql/conf.d/jeedom_my.cnf
    	echo "[mysqld]" >> /etc/mysql/conf.d/jeedom_my.cnf
    	echo "skip-name-resolve" >> /etc/mysql/conf.d/jeedom_my.cnf
    	echo "key_buffer_size = 16M" >> /etc/mysql/conf.d/jeedom_my.cnf
		echo "thread_cache_size = 16" >> /etc/mysql/conf.d/jeedom_my.cnf
		echo "tmp_table_size = 48M" >> /etc/mysql/conf.d/jeedom_my.cnf
		echo "max_heap_table_size = 48M" >> /etc/mysql/conf.d/jeedom_my.cnf
		echo "query_cache_type =1" >> /etc/mysql/conf.d/jeedom_my.cnf
		echo "query_cache_size = 32M" >> /etc/mysql/conf.d/jeedom_my.cnf
		echo "query_cache_limit = 2M" >> /etc/mysql/conf.d/jeedom_my.cnf
		echo "query_cache_min_res_unit=3K" >> /etc/mysql/conf.d/jeedom_my.cnf
		echo "innodb_flush_method = O_DIRECT" >> /etc/mysql/conf.d/jeedom_my.cnf
		echo "innodb_flush_log_at_trx_commit = 2" >> /etc/mysql/conf.d/jeedom_my.cnf
		echo "innodb_log_file_size = 32M" >> /etc/mysql/conf.d/jeedom_my.cnf
		echo "innodb_large_prefix = on" >> /etc/mysql/conf.d/jeedom_my.cnf
    fi

  	systemctl start mysql > /dev/null 2>&1
	if [ $? -ne 0 ]; then
		service mysql start
		if [ $? -ne 0 ]; then
    		echo "${ROUGE}Ne peut lancer mysql - Annulation${NORMAL}"
    		exit 1
  		fi
  	fi

	echo "${VERT}étape 7 personnalisation de jeedom réussie${NORMAL}"
}

step_8_jeedom_configuration() {
	echo "---------------------------------------------------------------------"
	echo "${JAUNE}commence l'étape 8 configuration de jeedom${NORMAL}"
	echo "DROP USER 'jeedom'@'localhost';" | mysql -uroot -p${MYSQL_ROOT_PASSWD} > /dev/null 2>&1
	mysql_sql "CREATE USER 'jeedom'@'localhost' IDENTIFIED BY '${MYSQL_JEEDOM_PASSWD}';"
	mysql_sql "DROP DATABASE IF EXISTS jeedom;"
	mysql_sql "CREATE DATABASE jeedom;"
	mysql_sql "GRANT ALL PRIVILEGES ON jeedom.* TO 'jeedom'@'localhost';"
	cp ${WEBSERVER_HOME}/core/config/common.config.sample.php ${WEBSERVER_HOME}/core/config/common.config.php
	sed -i "s/#PASSWORD#/${MYSQL_JEEDOM_PASSWD}/g" ${WEBSERVER_HOME}/core/config/common.config.php
	sed -i "s/#DBNAME#/jeedom/g" ${WEBSERVER_HOME}/core/config/common.config.php
	sed -i "s/#USERNAME#/jeedom/g" ${WEBSERVER_HOME}/core/config/common.config.php
	sed -i "s/#PORT#/3306/g" ${WEBSERVER_HOME}/core/config/common.config.php
	sed -i "s/#HOST#/localhost/g" ${WEBSERVER_HOME}/core/config/common.config.php
	#chmod 775 -R ${WEBSERVER_HOME}
	#chown -R www-data:www-data ${WEBSERVER_HOME}
	echo "${VERT}étape 8 configuration de jeedom réussie${NORMAL}"
}

step_9_jeedom_installation() {
	echo "---------------------------------------------------------------------"
	echo "${JAUNE}Commence l'étape 9 installation de jeedom${NORMAL}"
	mkdir -p /tmp/jeedom
	#chmod 777 -R /tmp/jeedom
	#chown www-data:www-data -R /tmp/jeedom
	php ${WEBSERVER_HOME}/install/install.php mode=force
	if [ $? -ne 0 ]; then
    	echo "${ROUGE}Ne peut installer jeedom - Annulation${NORMAL}"
    	exit 1
  	fi
	echo "${VERT}étape 9 installation de jeedom réussie${NORMAL}"
}

step_10_jeedom_post() {
	echo "---------------------------------------------------------------------"
	echo "${JAUNE}Commence l'étape 10 post jeedom${NORMAL}"
	if [ $(crontab -l | grep jeedom | wc -l) -ne 0 ];then
		(echo crontab -l | grep -v "jeedom") | crontab -

  	fi
	if [ ! -f /etc/cron.d/jeedom ]; then
		echo "* * * * * www-data /usr/bin/php ${WEBSERVER_HOME}/core/php/jeeCron.php >> /dev/null" > /etc/cron.d/jeedom
		if [ $? -ne 0 ]; then
	    	echo "${ROUGE}Ne peut installer le cron de jeedom - Annulation${NORMAL}"
	    	exit 1
	  	fi
	fi
	if [ ! -f /etc/cron.d/jeedom_watchdog ]; then
		echo "*/5 * * * * root /usr/bin/php ${WEBSERVER_HOME}/core/php/watchdog.php >> /dev/null" > /etc/cron.d/jeedom_watchdog
		if [ $? -ne 0 ]; then
	    	echo "${ROUGE}Ne peut installer le cron de jeedom - Annulation${NORMAL}"
	    	exit 1
	  	fi
	fi
  	usermod -a -G dialout,tty www-data
	if [ $(grep "www-data ALL=(ALL) NOPASSWD: ALL" /etc/sudoers | wc -l) -eq 0 ];then
		echo "www-data ALL=(ALL) NOPASSWD: ALL" | (EDITOR="tee -a" visudo)
		if [ $? -ne 0 ]; then
    		echo "${ROUGE}Ne peut permettre à jeedom d'utiliser sudo - Annulation${NORMAL}"
    		exit 1
  		fi
  	fi
  	if [ $(cat /proc/meminfo | grep MemTotal | awk '{ print $2 }') -gt 600000 ]; then
  		if [ $(cat /etc/fstab | grep /tmp/jeedom | grep tmpfs | wc -l) -eq 0 ];then
  			echo 'tmpfs        /tmp/jeedom            tmpfs  defaults,size=128M                                       0 0' >>  /etc/fstab
  		fi
  	fi
	echo "${VERT}étape 10 post jeedom réussie${NORMAL}"
}

step_11_jeedom_check() {
	echo "---------------------------------------------------------------------"
	echo "${JAUNE}Commence l'étape 11 vérification de jeedom${NORMAL}"
	php ${WEBSERVER_HOME}/sick.php
	#chmod 777 -R /tmp/jeedom
	#chown www-data:www-data -R /tmp/jeedom
	echo "${VERT}étape 11 vérification de jeedom réussie${NORMAL}"
}

distrib_1_spe(){
	if [ -f post-install.sh ]; then
		rm post-install.sh
	fi
	if [ -f /etc/armbian.txt ]; then
		cp ${WEBSERVER_HOME}/install/OS_specific/armbian/post-install.sh post-install.sh
	fi
	if [ -f /usr/bin/raspi-config ]; then
		cp ${WEBSERVER_HOME}/install/OS_specific/rpi/post-install.sh post-install.sh
	fi
	if [ -f post-install.sh ]; then
		#chmod +x post-install.sh
		./post-install.sh
		rm post-install.sh
	fi
}

STEP=0
VERSION=master
WEBSERVER_HOME=/var/www/html
HTML_OUTPUT=0
MYSQL_ROOT_PASSWD=$(cat /dev/urandom | tr -cd 'a-f0-9' | head -c 15)
MYSQL_JEEDOM_PASSWD=$(cat /dev/urandom | tr -cd 'a-f0-9' | head -c 15)

while getopts ":s:v:w:h:m:" opt; do
  case $opt in
    s) STEP="$OPTARG"
    ;;
    v) VERSION="$OPTARG"
    ;;
    w) WEBSERVER_HOME="$OPTARG"
    ;;
    h) HTML_OUTPUT=1
    ;;
    m) MYSQL_ROOT_PASSWD="$OPTARG"
    ;;
    \?) echo "${ROUGE}Invalid option -$OPTARG${NORMAL}" >&2
    ;;
  esac
done

if [ ${HTML_OUTPUT} -eq 1 ]; then
	VERT="</pre><span style='color:green;font-weight: bold;'>"
	NORMAL="</span><pre>"
	ROUGE="<span style='color:red;font-weight: bold;'>"
	ROSE="<span style='color:pink;font-weight: bold;'>"
	BLEU="<span style='color:blue;font-weight: bold;'>"
	BLANC="<span style='color:white;font-weight: bold;'>"
	BLANCLAIR="<span style='color:blue;font-weight: bold;'>"
	JAUNE="<span style='color:#FFBF00;font-weight: bold;'>"
	CYAN="<span style='color:blue;font-weight: bold;'>"
	echo "<script>"
	echo "setTimeout(function(){ window.scrollTo(0,document.body.scrollHeight); }, 100);"
	echo "setTimeout(function(){ window.scrollTo(0,document.body.scrollHeight); }, 300);"
	echo "setTimeout(function(){ location.reload(); }, 1000);"
	echo "</script>"
	echo "<pre>"
fi

echo "${JAUNE}Bienvenue dans l'installateur de Jeedom${NORMAL}"
echo "${JAUNE}Version d'installation de Jeedom : ${VERSION}${NORMAL}"
echo "${JAUNE}Dossier principal du serveur web : ${WEBSERVER_HOME}${NORMAL}"

case ${STEP} in
   0)
	echo "${JAUNE}Commence toutes les étapes de l'installation${NORMAL}"
	step_1_upgrade
	step_2_mainpackage
	step_3_database
	step_4_apache
	step_5_php
	step_6_jeedom_download
	step_7_jeedom_customization
	step_8_jeedom_configuration
	step_9_jeedom_installation
	step_10_jeedom_post
	step_11_jeedom_check
	distrib_1_spe
	echo "/!\ IMPORTANT /!\ Le mot de passe root MySQL est ${MYSQL_ROOT_PASSWD}"
	echo "Installation finie. Un redémarrage devrait être effectué"
	;;
   1) step_1_upgrade
	;;
   2) step_2_mainpackage
	;;
   3) step_3_database
	;;
   4) step_4_apache
	;;
   5) step_5_php
	;;
   6) step_6_jeedom_download
	;;
   7) step_7_jeedom_customization
	;;
   8) step_8_jeedom_configuration
	;;
   9) step_9_jeedom_installation
	;;
   10) step_10_jeedom_post
	;;
   11) step_11_jeedom_check
	;;
   *) echo "${ROUGE}Désolé, Je ne peux sélectionner une ${STEP} étape pour vous !${NORMAL}"
	;;
esac

rm -rf ${WEBSERVER_HOME}/index.html > /dev/null 2>&1

exit 0
