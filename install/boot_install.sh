#!/bin/sh

if [ ! -f /var/www/html/core/config/common.config.php ]; then
	cd /root
	wget https://raw.githubusercontent.com/jeedom/core/stable/install/install.sh
	/root/install.sh
	if [ $? -ne 0 ]; then
		/root/install.sh -s 5
		/root/install.sh -s 6
		/root/install.sh -s 7
	fi
fi