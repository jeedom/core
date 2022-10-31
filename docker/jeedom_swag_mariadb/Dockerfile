FROM debian:10 as sources
# Prefer make a docker step to prepare sources:
# If we download, the context can be this directory, and we chmod/chown and squash core-V4 directory here.
# If we use local sources, the context must be ../..
#    To swith between those two mode imply path change only in this docker stage

# ENV VERSION=V4-stable
# ADD https://github.com/jeedom/core/archive/${VERSION}.tar.gz /ZIP/
# RUN mkdir -p /UNZIP \
#  && tar -xf /ZIP/* -C /UNZIP/ \
#  && mv /UNZIP/core-* /SRC \
#  && chmod -R ug+rw,o+r /SRC \
#  && chown -R www-data:www-data /SRC

RUN apt update && apt install -y git

COPY . /SRC
# Generate if not already exists auto_patch/patch.git.diff
RUN /SRC/auto_patch/jeedom_docker_patch.sh /SRC \
 && rm -rf /SRC/.git*

RUN mkdir /SCRIPTS \
 && cd /SRC/docker/jeedom_swag_mariadb \
 && cp init.sh nginx_jeedom.conf logrotate.conf patch_zigbee_zhaquirks.py /SCRIPTS/ \
 && rm -rf /SRC/docker \
 && chmod -R ug+rw,o+r /SRC \
 && chown -R www-data:www-data /SRC


FROM debian:10 as repo
# With this docker stage, apt install is made only if something have changed from repositories

RUN apt-get update \
 && apt-get -y install software-properties-common \
 && add-apt-repository non-free

# Force cache misss : http://sec-zone.fr:8899/ return a now().timesstamp ... (random.org don't work any more for this ...)
ADD "http://sec-zone.fr:8899/" skipcache
RUN apt-get update


FROM debian:10

MAINTAINER info@jeedom.com

# Copy result of "apt-get update" from repo step, if repo have no change, we hit cache!
COPY --from=repo /etc/apt /etc/apt/
COPY --from=repo /var/lib/apt /var/lib/apt/
COPY --from=repo /var/cache/apt /var/cache/apt/

RUN apt-get -y install \
        at              \
        chromium        \
        cron            \
        curl            \
        espeak          \
        ffmpeg          \
        gettext         \
        git             \
        less            \
        libcurl3-gnutls \
        logrotate       \
        mariadb-client  \
        net-tools       \
        nginx           \
        nmap            \
        php-curl        \
        php-fpm         \
        php-gd          \
        php-imap        \
        php-json        \
        php-ldap        \
        php-mbstring    \
        php-mysql       \
        php-opcache     \
        php-soap        \
        php-xml         \
        php-xmlrpc      \
        php-zip         \
        python3         \
        python3-pip     \
        rsync           \
        rustc           \
        smbclient       \
        sudo            \
        tar             \
        unzip           \
        usbutils        \
        vim             \
        wget



# Configure php
RUN sed -i \
        -e 's/max_execution_time = 30/max_execution_time = 600/g'  \
        -e 's/upload_max_filesize = 2M/upload_max_filesize = 1G/g' \
        -e 's/post_max_size = 8M/post_max_size = 1G/g'             \
        -e 's/expose_php = On/expose_php = Off/g'                  \
        -e 's/;?opcache.enable=.*/opcache.enable=1/g'              \
        -e 's/;?opcache.enable_cli=.*/opcache.enable_cli=1/g'      \
        -e 's/memory_limit = 128M/memory_limit = 512M/g'           \
            $(find /etc/ -name php.ini -type f)

# Configure php-fpm
RUN sed -i 's,^listen = .*$,listen = 0.0.0.0:9000,' /etc/php/7.3/fpm/pool.d/www.conf

# Configure logroate (and arm it by cron every hour)
COPY --from=sources /SCRIPTS/logrotate.conf /etc/logrotate.d/jeedom
RUN echo "0 * * * * root logrotate /etc/logrotate.conf > /dev/null" > /etc/cron.d/logrotate

# Configure "internal" nginx (for loop back calls, not front)
COPY --from=sources /SCRIPTS/nginx_jeedom.conf /etc/nginx/sites-enabled/default

# Configure www-data user
RUN usermod -a -G dialout,tty www-data \
 && echo "www-data ALL=(ALL) NOPASSWD: ALL" >> /etc/sudoers.d/www_data && chmod 440 /etc/sudoers.d/www_data

# Import jeedom sources (in html_ref, source will be move to html by init.sh \
# because /var/www/html is designed to be a volume
COPY --from=sources /SRC/ "/var/www/html_ref/"
RUN mkdir -p -m 775 /tmp/jeedom && chown -R www-data:www-data /tmp/jeedom

# Set main jeedom cron
RUN echo "* * * * * www-data /usr/bin/php /var/www/html/core/php/jeeCron.php > /dev/null" > /etc/cron.d/jeedom

# Cron for zigbee zhaquirks patch checker
COPY --from=sources /SCRIPTS/patch_zigbee_zhaquirks.py /root/
RUN echo "*/5 * * * * root /root/patch_zigbee_zhaquirks.py > /dev/null" > /etc/cron.d/patch_zigbee_zhaquirks


# Set default command
COPY --from=sources /SCRIPTS/init.sh /root/
CMD ["bash", "/root/init.sh"]
