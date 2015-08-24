#!/bin/sh

echo '[START UPDATE]'
echo "*************Fix previous update (if needed)*************"
sudo dpkg --configure -a
echo "*************Update repository*************"
sudo apt-get -y update
echo "*************Upgrade*************"
sudo DEBIAN_FRONTEND=noninteractive apt-get -q -y -o "Dpkg::Options::=--force-confdef" -o "Dpkg::Options::=--force-confold" dist-upgrade
echo "*************Cleaning*************"
sudo apt-get -y autoremove
echo "*************Restart cron system*************"
sudo service cron restart
echo '[END UPDATE SUCCESS]'