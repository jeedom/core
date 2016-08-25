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
	echo "max_execution_time = 600" > /usr/local/etc/php/php.ini
	echo "upload_max_filesize = 1G" >> /usr/local/etc/php/php.ini
	echo "post_max_size = 1G" >> /usr/local/etc/php/php.ini
	/root/install.sh -h 1 -s 1 >> /var/www/html/index.html
	/root/install.sh -h 1 -s 2 >> /var/www/html/index.html
	/root/install.sh -h 1 -s 6 >> /var/www/html/index.html
	/root/install.sh -h 1 -s 7 >> /var/www/html/index.html
	/root/install.sh -h 1 -s 10 >> /var/www/html/index.html
	rm -rf /var/www/html/index.html
	
fi

echo 'All init complete'
chmod 777 /dev/tty*
chmod 755 -R /var/www/html
chown -R www-data:www-data /var/www/html

echo 'Launch cron'
service cron start

echo 'Launch apache2'
service apache2 start

/usr/bin/supervisord