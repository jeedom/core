FROM debian:stretch

MAINTAINER info@jeedom.com

COPY install/install.sh /root/
RUN sh /root/install.sh -s 1
RUN sh /root/install.sh -s 2
RUN sh /root/install.sh -s 4
RUN sh /root/install.sh -s 5
RUN sh /root/install.sh -s 8
RUN sh /root/install.sh -s 11

RUN apt-get install -y supervisor
COPY install/OS_specific/Docker/supervisord.conf /etc/supervisor/conf.d/

COPY install/OS_specific/Docker/init.sh /root/
RUN chmod +x /root/init.sh
CMD ["/root/init.sh"]
