ARG DEBIAN=bookworm-slim
FROM debian:${DEBIAN}
ARG DEBIAN

# ARG to build Docker images ... ENV to run Docker container
ARG WEBSERVER_HOME=/var/www/html
ENV WEBSERVER_HOME=${WEBSERVER_HOME}
ARG VERSION=master
ENV VERSION=${VERSION}
ARG DATABASE=1
ENV APACHE_HTTP_PORT=80
ENV APACHE_HTTPS_PORT=443
ENV DATABASE=${DATABASE}
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
RUN apt update -y 
RUN apt -o Dpkg::Options::="--force-confdef" -y install software-properties-common \
  ntp ca-certificates unzip curl sudo cron locate tar telnet wget logrotate dos2unix ntpdate htop \
  iotop vim iftop smbclient git python3 python3-pip libexpat1 ssl-cert \
  apt-transport-https xvfb cutycapt xauth at mariadb-client espeak net-tools nmap ffmpeg usbutils \
  gettext libcurl3-gnutls chromium librsync-dev ssl-cert iputils-ping \
  apache2 apache2-utils libexpat1 ssl-cert \
  php libapache2-mod-php php-json php-mysql php-curl php-gd php-imap php-xml php-opcache php-soap php-xmlrpc \
  php-common php-dev php-zip php-ssh2 php-mbstring php-ldap php-yaml php-snmp && apt -y remove brltty

COPY install/install.sh /tmp/
RUN sh /tmp/install.sh -s 1 -v ${VERSION} -w ${WEBSERVER_HOME} -d ${DATABASE} -i docker
RUN sh /tmp/install.sh -s 2 -v ${VERSION} -w ${WEBSERVER_HOME} -d ${DATABASE} -i docker
RUN sh /tmp/install.sh -s 3 -v ${VERSION} -w ${WEBSERVER_HOME} -d ${DATABASE} -i docker
RUN sh /tmp/install.sh -s 4 -v ${VERSION} -w ${WEBSERVER_HOME} -d ${DATABASE} -i docker
RUN sh /tmp/install.sh -s 5 -v ${VERSION} -w ${WEBSERVER_HOME} -d ${DATABASE} -i docker
COPY . ${WEBSERVER_HOME}
RUN sh /tmp/install.sh -s 7 -v ${VERSION} -w ${WEBSERVER_HOME} -d ${DATABASE} -i docker
RUN sh /tmp/install.sh -s 8 -v ${VERSION} -w ${WEBSERVER_HOME} -d ${DATABASE} -i docker
RUN sh /tmp/install.sh -s 9 -v ${VERSION} -w ${WEBSERVER_HOME} -d ${DATABASE} -i docker
RUN sh /tmp/install.sh -s 10 -v ${VERSION} -w ${WEBSERVER_HOME} -d ${DATABASE} -i docker
RUN sh /tmp/install.sh -s 11 -v ${VERSION} -w ${WEBSERVER_HOME} -d ${DATABASE} -i docker
RUN apt-get clean && rm -rf /var/lib/apt/lists/* 
RUN echo >${WEBSERVER_HOME}/initialisation

WORKDIR ${WEBSERVER_HOME}
EXPOSE 80
EXPOSE 443
COPY --chown=root:root --chmod=550 install/OS_specific/Docker/init.sh /root/
COPY --chown=root:root --chmod=550 install/bashrc /root/.bashrc
CMD ["bash", "/root/init.sh"]
