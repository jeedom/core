#!/bin/bash
echo 'Start init'

if ! [ -f /.dockerinit ]; then
	touch /.dockerinit
	chmod 755 /.dockerinit
fi

if [ -z ${ROOT_PASSWORD} ]; then
	ROOT_PASSWORD=$(cat /dev/urandom | tr -cd 'a-f0-9' | head -c 20)
	echo "Use generate password : ${ROOT_PASSWORD}"
fi

echo "root:${ROOT_PASSWORD}" | chpasswd

if [ ! -z ${APACHE_PORT} ]; then
	echo 'Change apache listen port to : '${APACHE_PORT}
	echo "Listen ${APACHE_PORT}" > /etc/apache2/ports.conf
	sed -i -E "s/\<VirtualHost \*:(.*)\>/VirtualHost \*:${APACHE_PORT}/" /etc/apache2/sites-enabled/000-default.conf
else
	echo "Listen 80" > /etc/apache2/ports.conf
	sed -i -E "s/\<VirtualHost \*:(.*)\>/VirtualHost \*:80/" /etc/apache2/sites-enabled/000-default.conf
fi

if [ ! -z ${SSH_PORT} ]; then
	echo 'Change SSH listen port to : '${SSH_PORT}
	sed -i '/Port /d' /etc/ssh/sshd_config
	echo "Port ${SSH_PORT}" >> /etc/ssh/sshd_config
else
	sed  -i '/Port /d' /etc/ssh/sshd_config
	echo "Port 22" >> /etc/ssh/sshd_config
fi

if [ ! -z ${MODE_HOST} ] && [ ${MODE_HOST} -eq 1 ]; then
	echo 'Update /etc/hosts for host mode'
	echo "127.0.0.1 localhost jeedom" > /etc/hosts
fi

if [ -f /var/www/html/core/config/common.config.php ]; then
	echo 'Jeedom is already install'
else
	echo 'Start jeedom installation'
	rm -rf /root/install.sh
	wget https://raw.githubusercontent.com/jeedom/core/alpha/install/install.sh -O /root/install.sh
	chmod +x /root/install.sh
	/root/install.sh -s 6
	if [ $(which mysqld | wc -l) -ne 0 ]; then
		chown -R mysql:mysql /var/lib/mysql
		mysql_install_db --user=mysql --basedir=/usr/ --ldata=/var/lib/mysql/
		service mysql restart
		MYSQL_JEEDOM_PASSWD=$(cat /dev/urandom | tr -cd 'a-f0-9' | head -c 15)
		echo "DROP USER 'jeedom'@'localhost';" | mysql > /dev/null 2>&1
		echo  "CREATE USER 'jeedom'@'localhost' IDENTIFIED BY '${MYSQL_JEEDOM_PASSWD}';" | mysql
		echo  "DROP DATABASE IF EXISTS jeedom;" | mysql
		echo  "CREATE DATABASE jeedom;" | mysql
		echo  "GRANT ALL PRIVILEGES ON jeedom.* TO 'jeedom'@'localhost';" | mysql
		cp /var/www/html/core/config/common.config.sample.php /var/www/html/core/config/common.config.php
		sed -i "s/#PASSWORD#/${MYSQL_JEEDOM_PASSWD}/g" /var/www/html/core/config/common.config.php
		sed -i "s/#DBNAME#/jeedom/g" /var/www/html/core/config/common.config.php
		sed -i "s/#USERNAME#/jeedom/g" /var/www/html/core/config/common.config.php
		sed -i "s/#PORT#/3306/g" /var/www/html/core/config/common.config.php
		sed -i "s/#HOST#/localhost/g" /var/www/html/core/config/common.config.php
		/root/install.sh -s 10
		/root/install.sh -s 11
	fi
fi

echo 'All init complete'
chmod 777 /dev/tty*
chmod 777 -R /tmp
chmod 755 -R /var/www/html
chown -R www-data:www-data /var/www/html

echo 'Start sshd'
service ssh restart

echo 'Start atd'
service atd restart

if [ $(which mysqld | wc -l) -ne 0 ]; then
	echo 'Starting mysql'
	service mysql restart
fi

if ! [ -f /.jeedom_backup_restore ]; then
	if [ ! -z "${RESTOREBACKUP}" ] && [ "${RESTOREBACKUP}" != 'NO' ]; then
		echo 'Need restore backup '${RESTOREBACKUP}
		wget ${RESTOREBACKUP} -O /tmp/backup.tar.gz
		php /var/www/html/install/restore.php backup=/tmp/backup.tar.gz
		rm /tmp/backup.tar.gz
		touch /.jeedom_backup_restore
		if [ ! -z "${UPDATEJEEDOM}" ] && [ "${UPDATEJEEDOM}" != 'NO' ]; then
			echo 'Need update jeedom'
			php /var/www/html/install/update.php
		fi
	fi
fi



echo 'Start apache2'
service apache2 restart

/usr/bin/supervisord
