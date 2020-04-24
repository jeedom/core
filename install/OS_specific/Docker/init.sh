#!/bin/bash
echo 'Start init'

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

echo 'Start atd'
service atd restart

if [ $(which mysqld | wc -l) -ne 0 ]; then
	echo 'Starting mysql'
	chown -R mysql:mysql /var/lib/mysql /var/run/mysqld
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

echo 'All init complete'
chmod 777 /dev/tty*
chmod 777 -R /tmp
chmod 755 -R /var/www/html
chown -R www-data:www-data /var/www/html

echo 'Start apache2'
service apache2 start

cron -f
