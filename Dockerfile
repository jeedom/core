FROM debian:10

MAINTAINER info@jeedom.com

COPY install/install.sh /tmp/
RUN sh /tmp/install.sh

COPY install/OS_specific/Docker/init.sh /root/
CMD ["sh", "/root/init.sh"]
