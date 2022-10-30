#!/bin/bash

set -e

WEBSERVER_HOME=/var/www/html

rsync -rc "${WEBSERVER_HOME}_ref/" "${WEBSERVER_HOME}"
rm -rf "${WEBSERVER_HOME}_ref"

# Force cache reconstruction (to force recheck of plugins dependencies)
rm -f "${WEBSERVER_HOME}/cache.tar.gz"

sed \
    -e "s/#HOST#/${MYSQL_HOSTNAME}/g"                            \
    -e "s/#PORT#/${MYSQL_PORT}/g"                                \
    -e "s/#DBNAME#/${MYSQL_DBNAME}/g"                            \
    -e "s/#USERNAME#/${MYSQL_USERNAME}/g"                        \
    -e "s/#PASSWORD#/${MYSQL_PASSWORD}/g"                        \
        "${WEBSERVER_HOME}/core/config/common.config.sample.php" \
        > "${WEBSERVER_HOME}/core/config/common.config.php"


if [[ -e /dev/gpiomem ]]; then
    # We presume hare we are under a board with gpio (raspberrypi or something else)
    # and this image run with --device /dev/gpiomem :  We probably need this following package
    python3 -m pip install RPi.GPIO
    ln -sfr /dev/gpiomem /dev/mem
    group=$(stat -c '%G' /dev/gpiomem)
    if [[ ${group} == UNKNOWN ]]; then
        group=gpio
        groupadd -g "$(stat -c '%g' /dev/gpiomem)" ${group}
    fi
    usermod -a -G ${group} www-data
fi


php "${WEBSERVER_HOME}/install/install.php" mode=force
php "${WEBSERVER_HOME}/sick.php"


sed -i '1,/REMOVE ALL PREVIOUS LINES/ d' "$0"

service nginx start
service atd start
service php7.3-fpm start

exec cron -f
