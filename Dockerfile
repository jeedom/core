FROM php:7-apache

MAINTAINER info@jeedom.com

ENV SHELL_ROOT_PASSWORD Mjeedom96

RUN apt-get update && apt-get install -y \
wget \
libssh2-php \
ntp \
unzip \
curl \
openssh-server \
supervisor \
cron \
usb-modeswitch \
python-serial \
nodejs \
npm \
tar \
libmcrypt-dev \
libcurl4-gnutls-dev \
libfreetype6-dev \
libjpeg62-turbo-dev \
libpng12-dev \
libxml2-dev \
sudo \
htop \
net-tools \
python \
ca-certificates \
vim \
git \
g++ \
locate \
mysql-client \
telnet \
man \
usbutils \
libtinyxml-dev \
libjsoncpp-dev \
snmp \
libsnmp-dev

####################################################################PHP7 EXTENSION#######################################################################################

RUN docker-php-ext-install json
RUN docker-php-ext-install mcrypt
RUN docker-php-ext-install curl
RUN docker-php-ext-install opcache
RUN docker-php-ext-install pdo
RUN docker-php-ext-install pdo_mysql
RUN docker-php-ext-install posix
RUN docker-php-ext-install simplexml
RUN docker-php-ext-install sockets
RUN docker-php-ext-install zip
RUN docker-php-ext-install iconv
RUN docker-php-ext-install mbstring
RUN docker-php-ext-install mysqli
RUN docker-php-ext-install soap
RUN docker-php-ext-install snmp
RUN docker-php-ext-configure gd --with-freetype-dir=/usr/include/ --with-jpeg-dir=/usr/include/ \
&& docker-php-ext-install gd

RUN rm /usr/bin/php
RUN ln -s /usr/local/bin/php /usr/bin/php

####################################################################SYSTEM#######################################################################################

RUN echo "root:${SHELL_ROOT_PASSWORD}" | chpasswd && \
  sed -i 's/PermitRootLogin without-password/PermitRootLogin yes/' /etc/ssh/sshd_config && \
  sed -i 's@session\s*required\s*pam_loginuid.so@session optional pam_loginuid.so@g' /etc/pam.d/sshd

RUN mkdir -p /var/run/sshd /var/log/supervisor
ADD install/OS\ specific/Docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

ADD install/OS\ specific/Docker/init.sh /root/init.sh
RUN chmod +x /root/init.sh
CMD ["/root/init.sh"]

EXPOSE 22 80 162 1886 4025 17100 10000 

#17100 : zibasdom
#10000 : orvibo
#1886 : MQTT
#162 : SNMP
#4025 : DSC