#!/bin/sh

echo '[START UPDATE]'
echo "*************Fix previous update (if needed)*************"
sudo dpkg --configure -a
if [ $? -ne 0 ]; then
	echo "Error on fix previous update"
	echo '[END UPDATE ERROR]'
	exit 1
fi
echo "*************Update repository*************"
sudo apt-get -y update
echo "*************Upgrade*************"
sudo DEBIAN_FRONTEND=noninteractive apt-get -q -y -o "Dpkg::Options::=--force-confdef" -o "Dpkg::Options::=--force-confold" dist-upgrade
if [ $? -ne 0 ]; then
	echo "Error on upgrade"
	echo '[END UPDATE ERROR]'
	exit 1
fi
echo "*************Cleaning*************"
sudo apt-get -y autoremove
if [ $? -ne 0 ]; then
	echo "Error on cleaning"
	echo '[END UPDATE ERROR]'
	exit 1
fi
echo "*************Restart cron system*************"
sudo service cron restart
if [ $? -ne 0 ]; then
	echo "Error on restart cron system"
	echo '[END UPDATE ERROR]'
	exit 1
fi

echo "*************Disable Apache2*************"
sudo update-rc.d -f apache2 remove
if [ $? -ne 0 ]; then
	echo "Error on disable apache"
	echo '[END UPDATE ERROR]'
	exit 1
fi

echo '[END UPDATE SUCCESS]'