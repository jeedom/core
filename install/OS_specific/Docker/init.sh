#!/bin/bash
echo 'Start init'

if [ -z ${ROOT_PASSWORD} ]; then
	echo "Use default password : Mjeedom96"
	echo "root:Mjeedom96" | chpasswd
else
	echo "root:${ROOT_PASSWORD}" | chpasswd
fi

if [ -f /var/www/html/core/config/common.config.php ]; then
	echo 'Jeedom is already install'
else
	echo 'Start jeedom installation'
	rm -rf /root/install.sh
	wget https://raw.githubusercontent.com/jeedom/core/stable/install/install.sh -O /root/install.sh
	chmod +x /root/install.sh
	/root/install.sh -s 6
fi

echo 'All init complete'
chmod 777 /dev/tty*
chmod 777 -R /tmp
chmod 755 -R /var/www/html
chown -R www-data:www-data /var/www/html

echo 'Launch cron'
service cron start

echo 'Launch apache2'
service apache2 start

/usr/bin/supervisord
