#!/bin/sh

echo -n "[$(date +%d-%m-%Y\ %H:%M:%S)] Check filesystem space..."
USERSPACE=$(df -h . | awk '/[0-9]/{print $(NF-1)}' | sed 's/\%//g')
if [ ${USERSPACE} -gt 95 ]; then 
	echo 'NOK'
else
	echo "OK"
fi


echo -n "[$(date +%d-%m-%Y\ %H:%M:%S)] Check mysql..."
sudo service mysql status >> /dev/null 2>&1
if [ $? -ne 0 ]; then
	echo -n "NOK, try to restart..."
	sudo service mysql start 
	sudo service mysql status >> /dev/null 2>&1
	if [ $? -ne 0 ]; then
		echo "[$(date +%d-%m-%Y\ %H:%M:%S)] Can not start it"
		exit 1
	fi	
else
	echo "OK"
fi

echo -n "[$(date +%d-%m-%Y\ %H:%M:%S)] Check cron..."
sudo service cron status >> /dev/null 2>&1
if [ $? -ne 0 ]; then
	echo -n "NOK, try to restart..."
	sudo service cron start 
	sudo service cron status >> /dev/null 2>&1
	if [ $? -ne 0 ]; then
		echo "[$(date +%d-%m-%Y\ %H:%M:%S)] Can not start it"
		exit 1
	fi	
else
	echo "OK"
fi

echo -n "[$(date +%d-%m-%Y\ %H:%M:%S)] Check cron jeedom..."
if [ $(crontab -l | grep jeeCron | wc -l) -lt 1 ]; then
	echo 'NOK'
else
	echo "OK"
fi


echo -n "[$(date +%d-%m-%Y\ %H:%M:%S)] Check right..."
sudo chown -R www-data:www-data /usr/share/nginx/www/jeedom
sudo chmod 775 -R /usr/share/nginx/www/jeedom
echo "OK"


echo -n "[$(date +%d-%m-%Y\ %H:%M:%S)] Check access to market..."
sudo ping -c 2 market.jeedom.fr >> /dev/null 2>&1
if [ $? -ne 0 ]; then
	echo "NOK"
else
	echo "OK"
fi
