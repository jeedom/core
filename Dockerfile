FROM debian:latest

MAINTAINER info@jeedom.com

ENV SHELL_ROOT_PASSWORD Mjeedom96
ENV APACHE_PORT 80
ENV SSH_PORT 22
ENV MODE_HOST 0

RUN apt-get update && apt-get install -y wget openssh-server supervisor

RUN echo "root:${SHELL_ROOT_PASSWORD}" | chpasswd && \
  sed -i 's/PermitRootLogin without-password/PermitRootLogin yes/' /etc/ssh/sshd_config && \
  sed -i 's@session\s*required\s*pam_loginuid.so@session optional pam_loginuid.so@g' /etc/pam.d/sshd

RUN mkdir -p /var/run/sshd /var/log/supervisor
RUN rm /etc/motd
ADD install/motd /etc/motd
RUN rm /root/.bashrc
ADD install/bashrc /root/.bashrc
ADD install/OS_specific/Docker/supervisord.conf /etc/supervisor/conf.d/supervisord.conf

ADD install/install.sh /root/install_docker.sh
RUN chmod +x /root/install_docker.sh
RUN /root/install_docker.sh -s 1;exit 0
RUN /root/install_docker.sh -s 2;exit 0
RUN /root/install_docker.sh -s 4;exit 0
RUN /root/install_docker.sh -s 5;exit 0
RUN /root/install_docker.sh -s 8;exit 0
RUN /root/install_docker.sh -s 11;exit 0
RUN systemctl disable apache2;exit 0
RUN systemctl disable sshd;exit 0

ADD install/OS_specific/Docker/init.sh /root/init.sh
RUN chmod +x /root/init.sh
CMD ["/root/init.sh"]
