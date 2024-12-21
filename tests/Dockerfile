ARG DEBIAN=bookworm-slim

FROM debian:${DEBIAN}

ARG WEBSERVER_HOME=/var/www/html
ENV WEBSERVER_HOME=${WEBSERVER_HOME}
ARG VERSION=alpha
ARG DATABASE=1
ARG MARIADB_NAME=jeedom
ENV MARIADB_NAME=${MARIADB_NAME}
WORKDIR ${WEBSERVER_HOME}

COPY --chown=root:root --chmod=550 install/install.sh /install.sh
RUN /install.sh -v ${VERSION} -w ${WEBSERVER_HOME} -d ${DATABASE}

# créer le script d'init
COPY --chown=root:root --chmod=550 <<EOF /root/init.sh
#!/bin/bash
# démarrer mariadb
service mariadb start
# démarrer atd
service atd start
# démarrer apache2
service apache2 start
# démarrer cron
service cron start
# attendre à l'infini
while :; do echo "$((COUNT++)) DAYS"; sleep 86400; done
EOF

# définir le script d'init comme commande par défaut
CMD ["/root/init.sh"]
