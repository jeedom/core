#!/bin/sh
rm /etc/update-motd.d/10-header
rm /etc/update-motd.d/99-point-to-faq
rm /etc/update-motd.d/30-sysinfo
rm /etc/update-motd.d/40-updates
wget https://raw.githubusercontent.com/jeedom/core/beta/install/OS_specific/armbian/10-header -O /etc/update-motd.d/10-header
chmod +x /etc/update-motd.d/10-header
sed -i '/# The named pipe \/dev\/xconsole/,$d' /etc/rsyslog.conf
systemctl restart rsyslog.service