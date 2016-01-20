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

echo -n "[$(date +%d-%m-%Y\ %H:%M:%S)] Check ${ROSE}filesystem space${NORMAL}..."
USERSPACE=$(df -h . | awk '/[0-9]/{print $(NF-1)}' | sed 's/\%//g')
if [ ${USERSPACE} -gt 95 ]; then 
	echo '${ROUGE}NOK${NORMAL}'
else
	echo "${VERT}OK${NORMAL}"
fi


echo -n "[$(date +%d-%m-%Y\ %H:%M:%S)] Check ${ROSE}mysql${NORMAL}..."
sudo service mysql status >> /dev/null 2>&1
if [ $? -ne 0 ]; then
	echo -n "${JAUNE}NOK, try to restart...${NORMAL}"
	sudo service mysql start 
	sudo service mysql status >> /dev/null 2>&1
	if [ $? -ne 0 ]; then
		echo "${ROUGE}[$(date +%d-%m-%Y\ %H:%M:%S)] Can not start it${NORMAL}"
		exit 1
	fi	
else
	echo "${VERT}OK${NORMAL}"
fi


echo -n "[$(date +%d-%m-%Y\ %H:%M:%S)] Check ${ROSE}apache2${NORMAL}..."
sudo service apache2 status >> /dev/null 2>&1
if [ $? -eq 0 ]; then
	echo -n "${JAUNE}NOK, try to restart...${NORMAL}"
	sudo service apache2 stop 
	sudo update-rc.d apache2 remove
	sudo service apache2 status >> /dev/null 2>&1
	if [ $? -eq 0 ]; then
		echo "${ROUGE}[$(date +%d-%m-%Y\ %H:%M:%S)] Can not stop it${NORMAL}"
		exit 1
	fi	
else
	echo "${VERT}OK${NORMAL}"
fi


echo -n "[$(date +%d-%m-%Y\ %H:%M:%S)] Check ${ROSE}nginx${NORMAL}..."
sudo service nginx status >> /dev/null 2>&1
if [ $? -ne 0 ]; then
	echo -n "${JAUNE}NOK, try to restart...${NORMAL}"
	sudo service nginx start 
	sudo service nginx status >> /dev/null 2>&1
	if [ $? -ne 0 ]; then
		echo "${ROUGE}[$(date +%d-%m-%Y\ %H:%M:%S)] Can not start it${NORMAL}"
		exit 1
	fi	
else
	echo "${VERT}OK${NORMAL}"
fi


echo -n "[$(date +%d-%m-%Y\ %H:%M:%S)] Check ${ROSE}php5-fpm${NORMAL}..."
sudo service php5-fpm status >> /dev/null 2>&1
if [ $? -ne 0 ]; then
	echo -n "${JAUNE}NOK, try to restart...${NORMAL}"
	sudo service php5-fpm start 
	sudo service php5-fpm status >> /dev/null 2>&1
	if [ $? -ne 0 ]; then
		echo "${ROUGE}[$(date +%d-%m-%Y\ %H:%M:%S)] Can not start it${NORMAL}"
		exit 1
	fi	
else
	echo "${VERT}OK${NORMAL}"
fi

echo -n "[$(date +%d-%m-%Y\ %H:%M:%S)] Check ${ROSE}cron${NORMAL}..."
sudo service cron status >> /dev/null 2>&1
if [ $? -ne 0 ]; then
	echo -n "${JAUNE}NOK, try to restart...${NORMAL}"
	sudo service cron start 
	sudo service cron status >> /dev/null 2>&1
	if [ $? -ne 0 ]; then
		echo "${ROUGE}[$(date +%d-%m-%Y\ %H:%M:%S)] Can not start it${NORMAL}"
		exit 1
	fi	
else
	echo "${VERT}OK${NORMAL}"
fi

echo -n "[$(date +%d-%m-%Y\ %H:%M:%S)] Check ${ROSE}cron jeedom${NORMAL}..."
if [ $(crontab -l | grep jeedom | wc -l) -lt 1 ]; then
	echo '${ROUGE}NOK${NORMAL}'
else
	echo "${VERT}OK${NORMAL}"
fi


echo -n "[$(date +%d-%m-%Y\ %H:%M:%S)] Check ${ROSE}right${NORMAL}..."
sudo chown -R www-data:www-data /usr/share/nginx/www/jeedom
sudo chmod 775 -R /usr/share/nginx/www/jeedom
echo "${VERT}OK${NORMAL}"


echo -n "[$(date +%d-%m-%Y\ %H:%M:%S)] Check access to ${ROSE}market${NORMAL}..."
sudo ping -c 2 market.jeedom.fr >> /dev/null 2>&1
if [ $? -ne 0 ]; then
	echo "${ROUGE}NOK${NORMAL}"
else
	echo "${VERT}OK${NORMAL}"
fi
