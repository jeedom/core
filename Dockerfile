ARG DEBIAN=bullseye
FROM debian:${DEBIAN}
ARG DEBIAN

# ARG to build Docker images ... ENV to run Docker container
ARG WEBSERVER_HOME=/var/www/html
ENV WEBSERVER_HOME=${WEBSERVER_HOME}
ARG VERSION=V4-stable
ENV VERSION=${VERSION}

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
RUN sh /tmp/install.sh -v ${VERSION} -w ${WEBSERVER_HOME}

EXPOSE 80
COPY install/OS_specific/Docker/init.sh /root/
CMD ["sh", "/root/init.sh"]