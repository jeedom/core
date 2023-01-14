FROM debian:11

MAINTAINER info@jeedom.com

ARG WEBSERVER_HOME=/var/www/html
ENV WEBSERVER_HOME=${WEBSERVER_HOME}
ARG VERSION=V4-stable
ENV VERSION=${VERSION}

WORKDIR ${WEBSERVER_HOME}
VOLUME ${WEBSERVER_HOME}
VOLUME /var/lib/mysql

COPY install/install.sh /tmp/
RUN sh /tmp/install.sh -v ${VERSION} -w ${WEBSERVER_HOME}

EXPOSE 80
COPY install/OS_specific/Docker/init.sh /root/
CMD ["sh", "/root/init.sh"]
