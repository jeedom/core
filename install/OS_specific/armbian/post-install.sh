#!/bin/sh
rm /etc/update-motd.d/10-header
rm /etc/update-motd.d/99-point-to-faq
wget https://raw.githubusercontent.com/jeedom/core/beta/install/OS_specific/armbian/10-header -O /etc/update-motd.d/10-header
chmod +x /etc/update-motd.d/10-header