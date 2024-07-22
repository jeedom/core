ARG DEBIAN=bullseye
FROM debian:${DEBIAN}
ARG DEBIAN

# ARG to build Docker images ... ENV to run Docker container
ARG WEBSERVER_HOME=/var/www/html
ENV WEBSERVER_HOME=${WEBSERVER_HOME}
ARG VERSION=master
ENV VERSION=${VERSION}
ARG DATABASE=1
ENV DATABASE=${DATABASE}

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

COPY install/install.sh /tmp/
# install step by step : step_1_upgrade
RUN sh /tmp/install.sh -s 1 -v ${VERSION} -w ${WEBSERVER_HOME} -d ${DATABASE} -i docker
# step_2_mainpackage
RUN sh /tmp/install.sh -s 2 -v ${VERSION} -w ${WEBSERVER_HOME} -d ${DATABASE} -i docker
# step_3_database
RUN sh /tmp/install.sh -s 3 -v ${VERSION} -w ${WEBSERVER_HOME} -d ${DATABASE} -i docker
# step_4_apache
RUN sh /tmp/install.sh -s 4 -v ${VERSION} -w ${WEBSERVER_HOME} -d ${DATABASE} -i docker
# step_5_php
RUN sh /tmp/install.sh -s 5 -v ${VERSION} -w ${WEBSERVER_HOME} -d ${DATABASE} -i docker
# step 6 : copy jeedom source files
COPY . ${WEBSERVER_HOME}
# step_7_jeedom_customization_mariadb
RUN sh /tmp/install.sh -s 7 -v ${VERSION} -w ${WEBSERVER_HOME} -d ${DATABASE} -i docker
# step_8_jeedom_customization
RUN sh /tmp/install.sh -s 8 -v ${VERSION} -w ${WEBSERVER_HOME} -d ${DATABASE} -i docker
# step_9_jeedom_configuration
RUN sh /tmp/install.sh -s 9 -v ${VERSION} -w ${WEBSERVER_HOME} -d ${DATABASE} -i docker
# step_10_jeedom_installation
RUN sh /tmp/install.sh -s 10 -v ${VERSION} -w ${WEBSERVER_HOME} -d ${DATABASE} -i docker
# step_11_jeedom_post
RUN sh /tmp/install.sh -s 11 -v ${VERSION} -w ${WEBSERVER_HOME} -d ${DATABASE} -i docker

# cleanup
RUN apt-get clean && \
    rm -rf /var/lib/apt/lists/*

# this file is a flag to trigger init.sh initialisation
RUN touch initialisation

EXPOSE 80
COPY install/OS_specific/Docker/init.sh /root/
CMD ["bash", "/root/init.sh"]