FROM debian:11

MAINTAINER info@jeedom.com

RUN apt-get update
RUN apt-get install -y --no-install-recommends software-properties-common python3 sudo bash ca-certificates iproute2 python3-apt

ADD https://raw.githubusercontent.com/gdraheim/docker-systemctl-replacement/master/files/docker/systemctl3.py /usr/bin/systemctl
RUN chmod +x /usr/bin/systemctl

COPY install/install.sh /tmp/
RUN sh /tmp/install.sh

COPY install/OS_specific/Docker/init.sh /root/
CMD ["sh", "/root/init.sh"]
