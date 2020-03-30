FROM debian:stretch

MAINTAINER info@jeedom.com

ENV MODE_HOST 0

RUN apt-get update && apt-get install -y wget supervisor

RUN mkdir -p /var/log/supervisor
ADD install/OS_specific/Docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

ADD install/install.sh /root/install_docker.sh
RUN chmod +x /root/install_docker.sh
RUN /root/install_docker.sh -s 1;exit 0
RUN /root/install_docker.sh -s 2;exit 0
RUN /root/install_docker.sh -s 4;exit 0
RUN /root/install_docker.sh -s 5;exit 0
RUN /root/install_docker.sh -s 8;exit 0
RUN /root/install_docker.sh -s 11;exit 0

ADD install/OS_specific/Docker/init.sh /root/init.sh
RUN chmod +x /root/init.sh
CMD ["/root/init.sh"]
