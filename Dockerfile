FROM debian:11

MAINTAINER info@jeedom.com

COPY install/install.sh /tmp/
RUN sh /tmp/install.sh -s 1
RUN sh /tmp/install.sh -s 2
RUN apt install -y mariadb-client mariadb-common mariadb-server
RUN sh /tmp/install.sh -s 4
RUN sh /tmp/install.sh -s 5
RUN sh /tmp/install.sh -s 6

RUN mkdir -p /lib/systemd/system/mariadb.service.d
RUN echo '[Service]' > /lib/systemd/system/mariadb.service.d/override.conf
RUN echo 'Restart=always' >> /lib/systemd/system/mariadb.service.d/override.conf
RUN echo 'RestartSec=10' >> /lib/systemd/system/mariadb.service.d/override.conf
RUN touch /etc/mysql/conf.d/jeedom_my.cnf
RUN echo "[mysqld]" >> /etc/mysql/conf.d/jeedom_my.cnf
RUN echo "skip-name-resolve" >> /etc/mysql/conf.d/jeedom_my.cnf
RUN echo "key_buffer_size = 16M" >> /etc/mysql/conf.d/jeedom_my.cnf
RUN echo "thread_cache_size = 16" >> /etc/mysql/conf.d/jeedom_my.cnf
RUN echo "tmp_table_size = 48M" >> /etc/mysql/conf.d/jeedom_my.cnf
RUN echo "max_heap_table_size = 48M" >> /etc/mysql/conf.d/jeedom_my.cnf
RUN echo "query_cache_type =1" >> /etc/mysql/conf.d/jeedom_my.cnf
RUN echo "query_cache_size = 32M" >> /etc/mysql/conf.d/jeedom_my.cnf
RUN echo "query_cache_limit = 2M" >> /etc/mysql/conf.d/jeedom_my.cnf
RUN echo "query_cache_min_res_unit=3K" >> /etc/mysql/conf.d/jeedom_my.cnf
RUN echo "innodb_flush_method = O_DIRECT" >> /etc/mysql/conf.d/jeedom_my.cnf
RUN echo "innodb_flush_log_at_trx_commit = 2" >> /etc/mysql/conf.d/jeedom_my.cnf
RUN echo "innodb_log_file_size = 32M" >> /etc/mysql/conf.d/jeedom_my.cnf
RUN echo "innodb_large_prefix = on" >> /etc/mysql/conf.d/jeedom_my.cnf

RUN sh /tmp/install.sh -s 8
RUN sh /tmp/install.sh -s 11
RUN sh /tmp/install.sh -s 12


COPY install/OS_specific/Docker/init.sh /root/
CMD ["sh", "/root/init.sh"]
