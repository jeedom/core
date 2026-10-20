# syntax=docker/dockerfile:1
FROM node:22-trixie-slim AS node
FROM composer:2 AS composer
FROM debian:trixie-slim

LABEL org.opencontainers.image.title="Jeedom Core" \
      org.opencontainers.image.source="https://github.com/jeedom/core" \
      org.opencontainers.image.description="Environnement Jeedom avec MariaDB intégrée et données persistantes"

ENV TZ=Europe/Paris \
    DEBIAN_FRONTEND=noninteractive \
    COMPOSER_ALLOW_SUPERUSER=1

# Les scripts Debian ne doivent lancer aucun service pendant la construction.
# MBROLA est fourni par le composant Debian officiel contrib.
RUN printf '#!/bin/sh\nexit 101\n' > /usr/sbin/policy-rc.d \
 && chmod 755 /usr/sbin/policy-rc.d \
 && sed -i 's/^Components: main$/Components: main contrib/' /etc/apt/sources.list.d/debian.sources \
 && apt-get update \
 && apt-get install -y --no-install-recommends \
      apache2 apache2-utils apt-transport-https apt-utils at build-essential ca-certificates \
      chromium chrony cron curl cutycapt dos2unix espeak espeak-ng ffmpeg gettext git gnupg htop iftop iotop \
      iputils-ping libcurl3t64-gnutls libcurl4t64 libexpat1 librsync-dev plocate logrotate lsb-release \
      mariadb-client mariadb-client-compat mariadb-common mariadb-server mbrola net-tools \
      nmap ntpsec-ntpdate passwd php php-cli php-common php-curl php-dev php-gd php-json \
      php-ldap php-mbstring php-mysql php-opcache php-snmp php-soap php-ssh2 \
      php-xml php-xmlrpc php-yaml php-zip libapache2-mod-php python3 python3-pip \
      python3-venv smbclient ssl-cert sudo supervisor tar telnet tzdata unzip usbutils \
      vim wget xauth xvfb \
 && rm -rf /var/lib/apt/lists/* /var/lib/mysql/* \
 && a2enmod headers remoteip \
 && a2dismod status \
 && usermod -a -G dialout,tty www-data \
 && printf 'www-data ALL=(ALL) NOPASSWD: ALL\n' > /etc/sudoers.d/jeedom \
 && chmod 440 /etc/sudoers.d/jeedom

COPY --from=node /usr/local/bin/node /usr/local/bin/node
COPY --from=node /usr/local/lib/node_modules /usr/local/lib/node_modules
COPY --from=composer /usr/bin/composer /usr/local/bin/composer
RUN ln -s /usr/local/bin/node /usr/local/bin/nodejs \
 && ln -s ../lib/node_modules/npm/bin/npm-cli.js /usr/local/bin/npm \
 && ln -s ../lib/node_modules/npm/bin/npx-cli.js /usr/local/bin/npx

COPY --chown=www-data:www-data . /usr/src/jeedom/
WORKDIR /usr/src/jeedom
RUN composer install --no-dev --no-interaction --no-plugins --no-scripts --prefer-dist --optimize-autoloader \
 && cp install/apache_security /etc/apache2/conf-available/security.conf \
 && cp install/apache_remoteip /etc/apache2/conf-available/remoteip.conf \
 && a2enconf remoteip \
 && chown -R www-data:www-data /usr/src/jeedom \
 && find /usr/src/jeedom -type d -exec chmod 755 {} + \
 && find /usr/src/jeedom -type f -name '*.sh' -exec chmod 755 {} + \
 && rm -rf /root/.cache/composer /root/.composer/cache

# Le checkout Semaphore hérite d'UMask=0077 ; ces droits ne sont pas ceux de l'image.
COPY --chmod=u=rwX,go=rX install/OS_specific/Docker/rootfs/ /
COPY install/OS_specific/Docker/source.json /usr/share/jeedom-image/source.json
COPY --chmod=644 install/OS_specific/Docker/check-packages.py /usr/local/lib/jeedom/check-packages.py
RUN chmod 755 /usr/local/bin/jeedom-* /usr/local/bin/systemctl /usr/local/sbin/service \
 && printf '* * * * * www-data /usr/bin/php /var/www/html/core/php/jeeCron.php >> /var/www/html/log/cron_execution 2>&1\n* * * * * www-data /usr/bin/touch /run/jeedom/cron-heartbeat\n' > /etc/cron.d/jeedom \
 && chmod 644 /etc/cron.d/jeedom \
 && mkdir -p /var/www/html/log \
 && apache2ctl configtest \
 && php -l /usr/local/lib/jeedom/database.php \
 && runuser -u www-data -- /usr/bin/python3 /usr/local/lib/jeedom/check-packages.py \
 && runuser -u mysql -- /usr/bin/getent passwd root > /dev/null \
 && runuser -u www-data -- /usr/bin/php -r 'require "/usr/src/jeedom/vendor/autoload.php";' \
 && find /var/www/html -mindepth 1 -delete

WORKDIR /var/www/html
EXPOSE 80
STOPSIGNAL SIGTERM
HEALTHCHECK --interval=30s --timeout=10s --start-period=180s --retries=3 \
  CMD ["/usr/local/bin/jeedom-healthcheck"]
ENTRYPOINT ["/usr/local/bin/jeedom-init"]
CMD ["run"]
