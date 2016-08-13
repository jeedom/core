#!/bin/sh
systemctl mask serial-getty@ttyAMA0.service
systemctl stop serial-getty@ttyAMA0.service
systemctl mask serial-getty@ttymxc0.service
systemctl stop serial-getty@ttymxc0.service
systemctl stop serial-getty@ttyS0.service
systemctl mask serial-getty@ttyS0.service
echo jeedom > /etc/hostname
(echo "Mjeedom96";echo "Mjeedom96";) | passwd root
rm /etc/motd
wget https://raw.githubusercontent.com/jeedom/core/stable/install/motd -O /etc/motd
rm -rf /root/.bashrc
wget https://raw.githubusercontent.com/jeedom/core/stable/install/bashrc -O /root/.bashrc