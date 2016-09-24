#!/bin/sh
echo jeedom > /etc/hostname
apt-get update
apt-get -y autoremove
apt-get -y dist-upgrade
apt-get -f install
apt-get -y install locate tar unzip telnet wget logrotate dos2unix fail2ban
apt-get -y install apache2 apache2-utils libexpat1 ssl-cert
sed -i 's/PermitRootLogin without-password/PermitRootLogin yes/g' /etc/ssh/sshd_config
systemctl restart sshd
echo jeedom > /etc/hostname
(echo "Mjeedom96";echo "Mjeedom96";) | passwd root
rm -rf /root/.bashrc
wget https://raw.githubusercontent.com/jeedom/core/stable/install/bashrc -O /root/.bashrc
userdel pi
rm -rf /home/pi
rm /etc/motd
wget https://raw.githubusercontent.com/jeedom/core/stable/install/motd -O /etc/motd
systemctl mask serial-getty@ttyAMA0.service
systemctl stop serial-getty@ttyAMA0.service
systemctl mask serial-getty@ttymxc0.service
systemctl stop serial-getty@ttymxc0.service
systemctl stop serial-getty@ttyS0.service
systemctl mask serial-getty@ttyS0.service
(echo "@reboot root [[ -f /var/www/html/core/config/common.config.php ]] && exit 0;cd /root;echo '<script>setTimeout(function(){ location.reload(); }, 1000);</script>Start installation of jeedom in 30s, please wait.' > /var/www/html/index.html;sleep 30;rm -rf /root/install.sh;wget https://raw.githubusercontent.com/jeedom/core/stable/install/install.sh -O /root/install.sh;chmod +x /root/install.sh;PATH=/usr/local/sbin:/usr/local/bin:/sbin:/bin:/usr/sbin:/usr/bin;/root/install.sh -h 1 > /var/www/html/index.html 2>&1;"; crontab -l) | crontab -