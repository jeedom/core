#!/bin/sh
rm /etc/systemd/system/multi-user.target.wants/apache2.service
cp /lib/systemd/system/apache2.service /etc/systemd/system/multi-user.target.wants/
sed -i 's/PrivateTmp=true/PrivateTmp=false/g' /etc/systemd/system/multi-user.target.wants/apache2.service > /dev/null 2>&1
sed -i 's/PrivateTmp=true/PrivateTmp=false/g' /lib/systemd/system/apache2.service > /dev/null 2>&1
systemctl daemon-reload