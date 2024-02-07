#!/bin/sh
GREEN="\\033[1;32m"
NORMAL="\\033[0;39m"
RED="\\033[1;31m"
YELLOW="\\033[1;33m"

if [ $(id -u) != 0 ] ; then
  echo "Superuser rights (root) are required to install Jeedom"
  echo "Please run 'sudo $0' or login as root and then rerun $0"
  exit 1
fi

apt_install() {
  apt-get -o Dpkg::Options::="--force-confdef" -y install "$@"
  if [ $? -ne 0 ]; then
    echo "${RED}Cannot install $@ - Cancelling${NORMAL}"
    exit 1
  fi
}

mariadb_sql() {
  echo "$@" | mariadb -uroot
  if [ $? -ne 0 ]; then
    echo "${RED}Cannot execute $@ in MySQL - Cancelling${NORMAL}"
    exit 1
  fi
}

service_action(){
  if [ "${INSTALLATION_TYPE}" = "pigen" ];then
    service $2 $1
    return $?
  else
    if [ "$1" = "status" ];then
      systemctl is-active --quiet $2
    else
      systemctl $1 $2
    fi
    if [ $? -ne 0 ]; then
      service $2 $1
      return $?
    fi
    return 0
  fi
}

version() { 
  echo "$@" | awk -F. '{ printf("%d%03d%03d%03d\n", $1,$2,$3,$4); }'; 
}

step_1_upgrade() {
  echo "---------------------------------------------------------------------"
  echo "${YELLOW}Starting step 1 - install${NORMAL}"
  
  apt-get update
  apt-get -f install
  apt-get -y dist-upgrade
  echo "${GREEN} Step 1 - Install done ${NORMAL}"
}

step_2_mainpackage() {
  echo "---------------------------------------------------------------------"
  echo "${YELLOW}Starting step 2 - packages${NORMAL}"
  apt-get -y install software-properties-common
  apt-get update
  apt_install ntp ca-certificates unzip curl sudo cron
  apt-get -o Dpkg::Options::="--force-confdef" -y install locate tar telnet wget logrotate fail2ban dos2unix ntpdate htop iotop vim iftop smbclient
  apt-get -y install usermod
  apt-get -y install visudo
  apt-get -y install git python python-pip
  apt-get -y install python3 python3-pip
  apt-get -y install libexpat1 ssl-cert
  apt-get -y install apt-transport-https
  apt-get -y install xvfb cutycapt xauth
  apt-get -y install at
  apt-get -y install mariadb-client
  apt-get -y install libav-tools
  apt-get -y install espeak
  apt-get -y install mbrola
  apt-get -y install net-tools
  apt-get -y install nmap
  apt-get -y install ffmpeg
  apt-get -y install usbutils
  apt-get -y install gettext
  apt-get -y install libcurl3-gnutls
  apt-get -y install chromium
  apt-get -y install librsync-dev
  apt-get -y install ssl-cert
  apt-get -y remove brltty
  echo "${GREEN}step 2 - packages done${NORMAL}"
}

step_3_database() {
  echo "---------------------------------------------------------------------"
  echo "${YELLOW}Starting step 3 - databse${NORMAL}"
  apt_install mariadb-client mariadb-common mariadb-server
  
  service_action status mariadb
  if [ $? -ne 0 ]; then
    service_action start mariadb
    service_action start mysql
  fi
  service_action status mariadb
  if [ $? -ne 0 ]; then
    service_action status mysql
    if [ $? -ne 0 ]; then
      echo "${RED}Cannot start mariadb - Cancelling${NORMAL}"
      exit 1
    fi
  fi
  
  echo "${GREEN}Step 3 - database done${NORMAL}"
}

step_4_apache() {
  echo "---------------------------------------------------------------------"
  echo "${YELLOW}Starting step 4 - apache${NORMAL}"
  apt_install apache2 apache2-utils libexpat1 ssl-cert
  echo "${GREEN}Step 4 - apache done${NORMAL}"
}

step_5_php() {
  echo "---------------------------------------------------------------------"
  echo "${YELLOW}Starting step 5 - php${NORMAL}"
  apt_install php libapache2-mod-php php-json php-mysql
  apt install -y php-curl
  apt install -y php-gd
  apt install -y php-imap
  apt install -y php-xml
  apt install -y php-opcache
  apt install -y php-soap
  apt install -y php-xmlrpc
  apt install -y php-common
  apt install -y php-dev
  apt install -y php-zip
  apt install -y php-ssh2
  apt install -y php-mbstring
  apt install -y php-ldap
  apt install -y php-yaml
  apt install -y php-snmp
  echo "${GREEN}Step 5 - php done${NORMAL}"
}

step_6_jeedom_download() {
  echo "---------------------------------------------------------------------"
  echo "${YELLOW}Starting step 6 - download Jeedom${NORMAL}"
  wget https://codeload.github.com/jeedom/core/zip/refs/heads/${VERSION} -O /tmp/jeedom.zip
  
  if [ $? -ne 0 ]; then
    echo "${YELLOW}Cannot download Jeedom from Github. Use deployment version if exist.${NORMAL}"
    if [ -f /root/jeedom.zip ]; then
      cp /root/jeedom.zip /tmp/jeedom.zip
    fi
  fi
  if [ ! /tmp/jeedom.zip ]; then
    echo "${RED}Cannot get jeedom.zip archive - Cancelling${NORMAL}"
    exit 1
  fi
  mkdir -p ${WEBSERVER_HOME}
  find ${WEBSERVER_HOME} ! -name 'index.html' -type f -exec rm -rf {} +
  rm -rf /root/core-*
  unzip -q /tmp/jeedom.zip -d /root/
  if [ $? -ne 0 ]; then
    echo "${RED}Cannot unpack archive - Cancelling${NORMAL}"
    exit 1
  fi
  cp -R /root/core-*/* ${WEBSERVER_HOME}
  cp -R /root/core-*/.[^.]* ${WEBSERVER_HOME}
  rm -rf /root/core-* > /dev/null 2>&1
  rm /tmp/jeedom.zip
  echo "${GREEN}Step 6 - download Jeedom done${NORMAL}"
}

step_7_jeedom_customization_mariadb() {
  echo "---------------------------------------------------------------------"
  echo "${YELLOW}Starting step 7 - mariadb customization${NORMAL}"
  
  mkdir -p /lib/systemd/system/mariadb.service.d
  echo '[Service]' > /lib/systemd/system/mariadb.service.d/override.conf
  echo 'Restart=always' >> /lib/systemd/system/mariadb.service.d/override.conf
  echo 'RestartSec=10' >> /lib/systemd/system/mariadb.service.d/override.conf
  systemctl daemon-reload
  
  service_action stop mariadb > /dev/null 2>&1
  if [ $? -ne 0 ]; then
    service_action status mariadb
    service_action stop mysql > /dev/null 2>&1
    if [ $? -ne 0 ]; then
      service_action status mysql
      echo "${RED}Cannot stop mariadb - Canceling${NORMAL}"
      exit 1
    fi
  fi

  #rm /var/lib/mysql/ib_logfile* /var/lib/mysql/ibdata* &> /dev/null
  
  if [ -d /etc/mysql/conf.d ]; then
    touch /etc/mysql/conf.d/jeedom_my.cnf
    echo "[mysqld]" > /etc/mysql/conf.d/jeedom_my.cnf
    echo "skip-name-resolve" >> /etc/mysql/conf.d/jeedom_my.cnf
    echo "key_buffer_size = 16M" >> /etc/mysql/conf.d/jeedom_my.cnf
    echo "thread_cache_size = 16" >> /etc/mysql/conf.d/jeedom_my.cnf
    echo "tmp_table_size = 48M" >> /etc/mysql/conf.d/jeedom_my.cnf
    echo "max_heap_table_size = 48M" >> /etc/mysql/conf.d/jeedom_my.cnf
    echo "query_cache_type =1" >> /etc/mysql/conf.d/jeedom_my.cnf
    echo "query_cache_size = 32M" >> /etc/mysql/conf.d/jeedom_my.cnf
    echo "query_cache_limit = 2M" >> /etc/mysql/conf.d/jeedom_my.cnf
    echo "query_cache_min_res_unit=3K" >> /etc/mysql/conf.d/jeedom_my.cnf
    echo "innodb_flush_method = O_DIRECT" >> /etc/mysql/conf.d/jeedom_my.cnf
    echo "innodb_flush_log_at_trx_commit = 2" >> /etc/mysql/conf.d/jeedom_my.cnf
    echo "innodb_log_file_size = 32M" >> /etc/mysql/conf.d/jeedom_my.cnf
    echo "innodb_large_prefix = on" >> /etc/mysql/conf.d/jeedom_my.cnf
    echo "connect_timeout = 600" >> /etc/mysql/conf.d/jeedom_my.cnf
    echo "wait_timeout = 600" >> /etc/mysql/conf.d/jeedom_my.cnf
    echo "interactive_timeout = 600" >> /etc/mysql/conf.d/jeedom_my.cnf
   # echo "default-storage-engine=myisam" >> /etc/mysql/conf.d/jeedom_my.cnf
  fi
  
  service_action start mariadb > /dev/null 2>&1
  if [ $? -ne 0 ]; then
    service_action status mariadb
    service_action start mysql > /dev/null 2>&1
    if [ $? -ne 0 ]; then
      service_action status mysql
      echo "${RED}Cannot start mariadb - Cancelling${NORMAL}"
      exit 1
    fi
  fi
  
  echo "${GREEN}Step 7 - mariadb customization done${NORMAL}"
}

step_8_jeedom_customization() {
  echo "---------------------------------------------------------------------"
  echo "${YELLOW}Starting step 8 - Jeedom customization${NORMAL}"
  cp ${WEBSERVER_HOME}/install/apache_security /etc/apache2/conf-available/security.conf
  sed -i -e "s%WEBSERVER_HOME%${WEBSERVER_HOME}%g" /etc/apache2/conf-available/security.conf

  cp ${WEBSERVER_HOME}/install/apache_remoteip /etc/apache2/conf-available/remoteip.conf
  sed -i -e "s%WEBSERVER_HOME%${WEBSERVER_HOME}%g" /etc/apache2/conf-available/remoteip.conf
  
  rm /etc/apache2/conf-enabled/security.conf > /dev/null 2>&1
  ln -s /etc/apache2/conf-available/security.conf /etc/apache2/conf-enabled/
  ln -s /etc/apache2/conf-available/remoteip.conf /etc/apache2/conf-enabled/
  
  cp ${WEBSERVER_HOME}/install/apache_default /etc/apache2/sites-available/000-default.conf
  rm /etc/apache2/sites-enabled/000-default.conf > /dev/null 2>&1
  ln -s /etc/apache2/sites-available/000-default.conf /etc/apache2/sites-enabled/
  
  rm /etc/apache2/conf-available/other-vhosts-access-log.conf > /dev/null 2>&1
  rm /etc/apache2/conf-enabled/other-vhosts-access-log.conf > /dev/null 2>&1

  echo '' > /etc/apache2/mods-available/alias.conf
  
  mkdir /etc/systemd/system/apache2.service.d
  echo "[Service]" > /etc/systemd/system/apache2.service.d/override.conf
  echo "PrivateTmp=no" >> /etc/systemd/system/apache2.service.d/override.conf
  echo "Restart=always" >> /etc/systemd/system/apache2.service.d/override.conf
  echo "RestartSec=10" >> /etc/systemd/system/apache2.service.d/override.conf
  
  systemctl daemon-reload
  
  for file in $(find /etc/ -iname php.ini -type f); do
    echo "Update php file ${file}"
    sed -i 's/max_execution_time = 30/max_execution_time = 600/g' ${file} > /dev/null 2>&1
    sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 1G/g' ${file} > /dev/null 2>&1
    sed -i 's/post_max_size = 8M/post_max_size = 1G/g' ${file} > /dev/null 2>&1
    sed -i 's/expose_php = On/expose_php = Off/g' ${file} > /dev/null 2>&1
    sed -i 's/;opcache.enable=0/opcache.enable=1/g' ${file} > /dev/null 2>&1
    sed -i 's/opcache.enable=0/opcache.enable=1/g' ${file} > /dev/null 2>&1
    sed -i 's/;opcache.enable_cli=0/opcache.enable_cli=1/g' ${file} > /dev/null 2>&1
    sed -i 's/opcache.enable_cli=0/opcache.enable_cli=1/g' ${file} > /dev/null 2>&1
    sed -i 's/memory_limit = 128M/memory_limit = 512M/g' ${file} > /dev/null 2>&1
  done
  
  a2dismod status
  a2enmod headers
  a2enmod remoteip

  sed -i -e "s%\${APACHE_LOG_DIR}/error.log%${WEBSERVER_HOME}/log/http.error%g" /etc/apache2/apache2.conf
  
  service_action restart apache2 > /dev/null 2>&1
  
  echo "vm.swappiness = 10" >>  /etc/sysctl.conf
  sysctl vm.swappiness=10
  
  cp ${WEBSERVER_HOME}/install/fail2ban.jeedom.conf /etc/fail2ban/jail.d/jeedom.conf
  
  mkdir -p /lib/systemd/system/fail2ban.service.d
  echo '[Service]' > /lib/systemd/system/fail2ban.service.d/override.conf
  echo 'Restart=always' >> /lib/systemd/system/fail2ban.service.d/override.conf
  echo 'RestartSec=10' >> /lib/systemd/system/fail2ban.service.d/override.conf
  systemctl daemon-reload
  service_action restart fail2ban > /dev/null 2>&1
  
  echo "${GREEN}Step 8 - Jeedom customization done${NORMAL}"
}

step_9_jeedom_configuration() {
  echo "---------------------------------------------------------------------"
  echo "${YELLOW}Starting step 9 - Jeedom configuration${NORMAL}"
  echo "DROP USER 'jeedom'@'localhost';" | mariadb -uroot > /dev/null 2>&1
  mariadb_sql "CREATE USER 'jeedom'@'localhost' IDENTIFIED BY '${MARIADB_JEEDOM_PASSWD}';"
  mariadb_sql "DROP DATABASE IF EXISTS jeedom;"
  mariadb_sql "CREATE DATABASE jeedom;"
  mariadb_sql "GRANT ALL PRIVILEGES ON jeedom.* TO 'jeedom'@'localhost';"
  cp ${WEBSERVER_HOME}/core/config/common.config.sample.php ${WEBSERVER_HOME}/core/config/common.config.php
  sed -i "s/#PASSWORD#/${MARIADB_JEEDOM_PASSWD}/g" ${WEBSERVER_HOME}/core/config/common.config.php
  sed -i "s/#DBNAME#/jeedom/g" ${WEBSERVER_HOME}/core/config/common.config.php
  sed -i "s/#USERNAME#/jeedom/g" ${WEBSERVER_HOME}/core/config/common.config.php
  sed -i "s/#PORT#/3306/g" ${WEBSERVER_HOME}/core/config/common.config.php
  sed -i "s/#HOST#/localhost/g" ${WEBSERVER_HOME}/core/config/common.config.php
  chmod 775 -R ${WEBSERVER_HOME}
  chown -R www-data:www-data ${WEBSERVER_HOME}
  echo "${GREEN}Step 9 - Jeedom configuration done${NORMAL}"
}

step_10_jeedom_installation() {
  echo "---------------------------------------------------------------------"
  echo "${YELLOW}Starting step 10 - Jeedom install${NORMAL}"
  chmod +x ${WEBSERVER_HOME}/resources/install_composer.sh
  ${WEBSERVER_HOME}/resources/install_composer.sh
  PHP_VERSION=$(php -r "echo PHP_VERSION;")
  if [ $(version $PHP_VERSION) -ge $(version "8.0.0") ]; then
    echo "PHP version highter than 8.0.0, need to reinstall composer dependancy"
    rm -rf ${WEBSERVER_HOME}/vendor
    rm -rf ${WEBSERVER_HOME}/composer.lock
    export COMPOSER_ALLOW_SUPERUSER=1
    cd ${WEBSERVER_HOME}
    composer install --no-ansi --no-dev --no-interaction --no-plugins --no-progress --no-scripts --optimize-autoloader
  fi
  mkdir -p /tmp/jeedom
  chmod 777 -R /tmp/jeedom
  chown www-data:www-data -R /tmp/jeedom
  php ${WEBSERVER_HOME}/install/install.php mode=force
  if [ $? -ne 0 ]; then
    echo "${RED}Cannot install Jeedom - Cancelling${NORMAL}"
    exit 1
  fi
  echo "${GREEN}Step 10 - Jeedom install done${NORMAL}"
}

step_11_jeedom_post() {
  echo "---------------------------------------------------------------------"
  echo "${YELLOW}Starting step 11 - Jeedom post-install${NORMAL}"
  if [ $(crontab -l | grep jeedom | wc -l) -ne 0 ];then
    (echo crontab -l | grep -v "jeedom") | crontab -
    
  fi
  if [ ! -f /etc/cron.d/jeedom ]; then
    echo "* * * * * www-data /usr/bin/php ${WEBSERVER_HOME}/core/php/jeeCron.php >> /dev/null" > /etc/cron.d/jeedom
    if [ $? -ne 0 ]; then
      echo "${RED}Cannot install Jeedom cron - Canceling${NORMAL}"
      exit 1
    fi
  fi
  if [ ! -f /etc/cron.d/jeedom_watchdog ]; then
    echo "*/5 * * * * root /usr/bin/php ${WEBSERVER_HOME}/core/php/watchdog.php >> /dev/null" > /etc/cron.d/jeedom_watchdog
    if [ $? -ne 0 ]; then
      echo "${RED}Cannot install Jeedom cron - Canceling${NORMAL}"
      exit 1
    fi
  fi
  usermod -a -G dialout,tty www-data
  if [ $(grep "www-data ALL=(ALL) NOPASSWD: ALL" /etc/sudoers | wc -l) -eq 0 ];then
    echo "www-data ALL=(ALL) NOPASSWD: ALL" | (EDITOR="tee -a" visudo)
    if [ $? -ne 0 ]; then
      echo "${RED}Cannot allow Sudo for Jeedom - Cancelling${NORMAL}"
      exit 1
    fi
  fi
  if [ $(cat /proc/meminfo | grep MemTotal | awk '{ print $2 }') -gt 600000 ]; then
    if [ $(cat /etc/fstab | grep /tmp/jeedom | grep tmpfs | wc -l) -eq 0 ];then
      echo 'tmpfs        /tmp/jeedom            tmpfs  defaults,size=256M                                       0 0' >>  /etc/fstab
    fi
  fi
  chmod +x ${WEBSERVER_HOME}/resources/install_nodejs.sh
  ${WEBSERVER_HOME}/resources/install_nodejs.sh
  echo "${GREEN}Step 11 - Jeedom post-install done${NORMAL}"
}

step_12_jeedom_check() {
  echo "---------------------------------------------------------------------"
  echo "${YELLOW}Starting step 12 - Jeedom check${NORMAL}"
  php ${WEBSERVER_HOME}/sick.php
  chmod 777 -R /tmp/jeedom
  chown www-data:www-data -R /tmp/jeedom
  echo "${GREEN}Step 12 - Jeedom check done${NORMAL}"
}

distrib_1_spe(){
  if [ -f post-install.sh ]; then
    rm post-install.sh
  fi
  if [ -f /etc/armbian.txt ]; then
    cp ${WEBSERVER_HOME}/install/OS_specific/armbian/post-install.sh post-install.sh
  fi
  if [ -f /usr/bin/raspi-config ]; then
    cp ${WEBSERVER_HOME}/install/OS_specific/rpi/post-install.sh post-install.sh
  fi
  if [ -f post-install.sh ]; then
    chmod +x post-install.sh
    ./post-install.sh
    rm post-install.sh
  fi
}

STEP=0
VERSION=V4-stable
WEBSERVER_HOME=/var/www/html
MARIADB_JEEDOM_PASSWD=$(cat /dev/urandom | tr -cd 'a-f0-9' | head -c 15)
INSTALLATION_TYPE='standard'
DATABASE=1

while getopts ":s:v:w:m:i:d:" opt; do
  case $opt in
    s) STEP="$OPTARG"
    ;;
    v) VERSION="$OPTARG"
    ;;
    w) WEBSERVER_HOME="$OPTARG"
    ;;
    i) INSTALLATION_TYPE="$OPTARG"
    ;;
    d) DATABASE="$OPTARG"
    ;;
    \?) echo "${RED}Invalid option -$OPTARG${NORMAL}" >&2
    ;;
  esac
done

echo "${YELLOW}Welcome to Jeedom installer${NORMAL}"
echo "${YELLOW}Jeedom version : ${VERSION}${NORMAL}"
echo "${YELLOW}Web folder : ${WEBSERVER_HOME}${NORMAL}"
echo "${YELLOW}Installation type : ${INSTALLATION_TYPE}${NORMAL}"
if [ ${DATABASE} -ne 1 ]; then
  echo "${YELLOW}External database${NORMAL}"
fi

case ${STEP} in
  0)
  echo "${YELLOW}Starting installation ...${NORMAL}"
  step_1_upgrade
  step_2_mainpackage
  if [ ${DATABASE} -eq 1 ]; then
    step_3_database
  fi
  step_4_apache
  step_5_php
  step_6_jeedom_download
  if [ ${DATABASE} -eq 1 ]; then
    step_7_jeedom_customization_mariadb
  fi
  step_8_jeedom_customization
 

  if [ ${DATABASE} -eq 1 ]; then
    step_9_jeedom_configuration
    step_10_jeedom_installation
  fi
  step_11_jeedom_post
  step_12_jeedom_check
  distrib_1_spe
  echo "Installation done. Reboot required."
  ;;
  1) step_1_upgrade
  ;;
  2) step_2_mainpackage
  ;;
  3) step_3_database
  ;;
  4) step_4_apache
  ;;
  5) step_5_php
  ;;
  6) step_6_jeedom_download
  ;;
  7) step_7_jeedom_customization_mariadb
  ;;
  8) step_8_jeedom_customization
  ;;
  9) step_9_jeedom_configuration
  ;;
  10) step_10_jeedom_installation
  ;;
  11) step_11_jeedom_post
  ;;
  12) step_12_jeedom_check
  ;;
  *) echo "${RED}Sorry, cannot select step ${STEP}${NORMAL}"
  ;;
esac

rm -rf ${WEBSERVER_HOME}/index.html > /dev/null 2>&1

exit 0
