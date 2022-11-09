FROM debian:11

MAINTAINER info@jeedom.com

RUN apt-get update
RUN apt install dbus-user-session

COPY install/install.sh /tmp/
RUN sh /tmp/install.sh

COPY install/OS_specific/Docker/init.sh /root/
CMD ["sh", "/root/init.sh"]
