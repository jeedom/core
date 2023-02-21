#!/bin/sh

echo -n "[$(date +%d-%m-%Y\ %H:%M:%S)] Checking file system space..."
USERSPACE=$(df -h . | awk '/[0-9]/{print $(NF-1)}' | sed 's/\%//g')
if [ ${USERSPACE} -gt 95 ]; then
	echo 'NOK'
else
	echo "OK"
fi


echo -n "[$(date +%d-%m-%Y\ %H:%M:%S)] Checking MySQL..."
sudo service mysql status >> /dev/null 2>&1
if [ $? -ne 0 ]; then
	echo -n "NOK, essaie de le redémarrer..."
	sudo service mysql start
	sudo service mysql status >> /dev/null 2>&1
	if [ $? -ne 0 ]; then
		echo "[$(date +%d-%m-%Y\ %H:%M:%S)] Unable to start it"
		exit 1
	fi
else
	echo "OK"
fi

echo -n "[$(date +%d-%m-%Y\ %H:%M:%S)] Checking system cron engine..."
sudo service cron status >> /dev/null 2>&1
if [ $? -ne 0 ]; then
	echo -n "NOK, Trying to restart it..."
	sudo service cron start
	sudo service cron status >> /dev/null 2>&1
	if [ $? -ne 0 ]; then
		echo "[$(date +%d-%m-%Y\ %H:%M:%S)] Unable to start it"
		exit 1
	fi
else
	echo "OK"
fi

echo -n "[$(date +%d-%m-%Y\ %H:%M:%S)] Checking Jeedom cron..."
if [ $(crontab -l | grep jeeCron | wc -l) -lt 1 ]; then
	if [ ! -f /etc/cron.d/jeedom ]; then
		echo 'NOK'
	else
		echo "OK"
	fi
else
	echo "OK"
fi
DIR="$( cd "$( dirname "$0" )" && pwd )"
echo -n "[$(date +%d-%m-%Y\ %H:%M:%S)] Checking rights..."
sudo chown -R www-data:www-data ${DIR}/*
sudo chmod -R 775 ${DIR}/*
sudo chown -R www-data:www-data /tmp/jeedom/cache
sudo chmod -R 775 /tmp/jeedom/cache
sudo chown -R www-data:www-data /var/www
sudo chmod -R 775 /var/www
	echo "OK"
