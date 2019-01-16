#!/bin/sh

echo -n "[$(date +%d-%m-%Y\ %H:%M:%S)] Vérifie l'espace du système de fichiers..."
USERSPACE=$(df -h . | awk '/[0-9]/{print $(NF-1)}' | sed 's/\%//g')
if [ ${USERSPACE} -gt 95 ]; then
	echo 'NOK'
else
	echo "OK"
fi


echo -n "[$(date +%d-%m-%Y\ %H:%M:%S)] Vérifie mysql..."
sudo service mysql status >> /dev/null 2>&1
if [ $? -ne 0 ]; then
	echo -n "NOK, essaie de le redémarrer..."
	sudo service mysql start
	sudo service mysql status >> /dev/null 2>&1
	if [ $? -ne 0 ]; then
		echo "[$(date +%d-%m-%Y\ %H:%M:%S)] Impossible de le démarrer"
		exit 1
	fi
else
	echo "OK"
fi

echo -n "[$(date +%d-%m-%Y\ %H:%M:%S)] Vérifie cron..."
sudo service cron status >> /dev/null 2>&1
if [ $? -ne 0 ]; then
	echo -n "NOK, essaie de le redémarrer..."
	sudo service cron start
	sudo service cron status >> /dev/null 2>&1
	if [ $? -ne 0 ]; then
		echo "[$(date +%d-%m-%Y\ %H:%M:%S)] Impossible de le démarrer"
		exit 1
	fi
else
	echo "OK"
fi

echo -n "[$(date +%d-%m-%Y\ %H:%M:%S)] Vérifie le cron de jeedom..."
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
echo -n "[$(date +%d-%m-%Y\ %H:%M:%S)] Vérifie les droits..."
#sudo chown -R www-data:www-data ${DIR}/*
#sudo chmod -R 775 ${DIR}/*
#sudo chown -R www-data:www-data /tmp/jeedom/cache
#sudo chmod -R 775 /tmp/jeedom/cache
#sudo chown -R www-data:www-data /var/www
#sudo chmod -R 775 /var/www
	echo "OK"
