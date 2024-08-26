ARG DEBIAN=bookworm-slim
FROM debian:${DEBIAN}
ARG DEBIAN

# ARG to build Docker images ... ENV to run Docker container
ARG WEBSERVER_HOME=/var/www/html
ENV WEBSERVER_HOME=${WEBSERVER_HOME}
ARG VERSION=master
ENV VERSION=${VERSION}
ARG DATABASE=0
ENV APACHE_HTTP_PORT=80
ENV APACHE_HTTPS_PORT=443
ENV DATABASE=${DATABASE}
ENV DB_PASSWORD=changeIt
ENV DB_USERNAME=jeedom
ENV DB_NAME=jeedom
ENV DB_PORT=3306
ENV DB_HOST=localhost
ENV TZ=America/Chicago
ENV DEBUG=0

# labels follows opencontainers convention
LABEL org.opencontainers.image.title='Jeedom'
LABEL org.opencontainers.image.authors='info@jeedom.com'
LABEL org.opencontainers.image.url='https://www.jeedom.com/'
LABEL org.opencontainers.image.documentation='https://doc.jeedom.com/'
LABEL org.opencontainers.image.source='https://github.com/jeedom/core'
LABEL org.opencontainers.image.vendor='Jeedom SAS'
LABEL org.opencontainers.image.licenses='GNU GENERAL PUBLIC LICENSE'
LABEL org.opencontainers.image.version='${VERSION}-${DEBIAN}'
LABEL org.opencontainers.image.description='Software for home automation'

WORKDIR ${WEBSERVER_HOME}
VOLUME ${WEBSERVER_HOME}
VOLUME /var/lib/mysql

#speed up build using docker cache
RUN apt update -y && apt -o Dpkg::Options::="--force-confdef" -y install software-properties-common \
  ntp ca-certificates unzip curl sudo cron locate tar telnet wget logrotate dos2unix ntpdate htop \
  iotop vim iftop smbclient git python3 python3-pip libexpat1 ssl-cert \
  apt-transport-https xvfb cutycapt xauth at mariadb-client espeak net-tools nmap ffmpeg usbutils \
  gettext libcurl3-gnutls chromium librsync-dev ssl-cert iputils-ping \
  # package step 4
  apache2 apache2-utils libexpat1 ssl-cert \
  # package step 5
  php libapache2-mod-php php-json php-mysql php-curl php-gd php-imap php-xml php-opcache php-soap php-xmlrpc \
  php-common php-dev php-zip php-ssh2 php-mbstring php-ldap php-yaml php-snmp && apt -y remove brltty

COPY install/install.sh /tmp/
# install step by step : step_1_upgrade
RUN sh /tmp/install.sh -s 1 -v ${VERSION} -w ${WEBSERVER_HOME} -d ${DATABASE} -i docker \
    # step_2_mainpackage
    sh /tmp/install.sh -s 2 -v ${VERSION} -w ${WEBSERVER_HOME} -d ${DATABASE} -i docker \
    # step_3_database
    sh /tmp/install.sh -s 3 -v ${VERSION} -w ${WEBSERVER_HOME} -d ${DATABASE} -i docker \
    # step_4_apache
    sh /tmp/install.sh -s 4 -v ${VERSION} -w ${WEBSERVER_HOME} -d ${DATABASE} -i docker \
    # step_5_php
    sh /tmp/install.sh -s 5 -v ${VERSION} -w ${WEBSERVER_HOME} -d ${DATABASE} -i docker
# step 6 : copy jeedom source files
COPY . ${WEBSERVER_HOME}
# step_7_jeedom_customization_mariadb
RUN sh /tmp/install.sh -s 7 -v ${VERSION} -w ${WEBSERVER_HOME} -d ${DATABASE} -i docker \
    # step_8_jeedom_customization
    sh /tmp/install.sh -s 8 -v ${VERSION} -w ${WEBSERVER_HOME} -d ${DATABASE} -i docker \
    # step_9_jeedom_configuration
    sh /tmp/install.sh -s 9 -v ${VERSION} -w ${WEBSERVER_HOME} -d ${DATABASE} -i docker \
    # step_10_jeedom_installation
    sh /tmp/install.sh -s 10 -v ${VERSION} -w ${WEBSERVER_HOME} -d ${DATABASE} -i docker \
    # step_11_jeedom_post
    sh /tmp/install.sh -s 11 -v ${VERSION} -w ${WEBSERVER_HOME} -d ${DATABASE} -i docker \
    # cleanup
    apt-get clean && rm -rf /var/lib/apt/lists/* \
    # this file is a flag to trigger init.sh initialisation
    && echo >${WEBSERVER_HOME}/initialisation

WORKDIR ${WEBSERVER_HOME}
EXPOSE 80
EXPOSE 443
COPY --chown=root:root --chmod=550 install/OS_specific/Docker/init.sh /root/
COPY --chown=root:root --chmod=550 install/bashrc /root/.bashrc
CMD ["bash", "/root/init.sh"]