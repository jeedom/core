#!/bin/sh

########################################################################
#
# Jeedom installer: shell script to deploy Jeedom and its dependencies
#
########################################################################

############################ Translations ##############################
# MUST be embedded, otherwise the network installation of Jeedom
# won't work!


install_msg_en() {
    msg_installer_welcome="*      Welcome to the Jeedom installer/updater        *"
    msg_usage1="Usage: $0 [<webserver_name>]"
    msg_usage2="            webserver_name can be 'apache' or 'nginx_ssl' or 'nginx' (default)"
    msg_manual_install_nodejs_ARM="*          Manual installation of nodeJS for ARM       *"
    msg_manual_install_nodejs_RPI="*     Manual installation of nodeJS for Raspberry      *"
    msg_nginx_config="*                  NGINX configuration                 *"
    msg_apache_config="*                  APACHE configuration                *"
    msg_question_install_jeedom="Are you sure you want to install Jeedom?"
    msg_warning_install_jeedom="Warning: this will overwrite the default ${ws_upname} configuration if it exists!"
    msg_warning_overwrite_jeedom="Warning: your existing Jeedom installation will be overwritten!"
    msg_yes="yes"
    msg_no="no"
    msg_yesno="yes/no: "
    msg_cancel_install="Canceling the installation"
    msg_answer_yesno="Answer yes or no"
    msg_install_deps="*               Dependencies installation              *"
    msg_passwd_mysql="What password do you have just typed (MySQL root password)?"
    msg_confirm_passwd_mysql="Do you confirm that the password is:"
    msg_bad_passwd_mysql="The MySQL password provided is invalid!"
    msg_setup_dirs_and_privs="*      Creating directories and setting up rights      *"
    msg_copy_jeedom_files="*                Copying files of Jeedom               *"
    msg_unable_to_download_file="Unable to download the file"
    msg_config_db="*               Configuring the database               *"
    msg_install_jeedom="*                 Installing de Jeedom                 *"
    msg_update_jeedom="*                  Updating de Jeedom                  *"
    msg_setup_cron="*                   Setting up cron                    *"
    msg_setup_nodejs_service="*               Setting up nodeJS service              *"
    msg_startup_nodejs_service="*             Starting the nodeJS service              *"
    msg_post_install_actions="*             Post-installation actions                *"
    msg_post_update_actions="*               Post-update actions                    *"
    msg_install_complete="*                Installation complete                 *"
    msg_update_complete="*                   Update complete                    *"
    msg_or="or"
    msg_login_info1="You can log in to Jeedom by going on:"
    msg_login_info2="Your credentials are:"
    msg_optimize_webserver_cache="*      Checking for webserver cache optimization       *"
    msg_php_version="PHP version found : "
    msg_php_already_optimized="PHP is already optimized, using : "
    msg_optimize_webserver_cache_apc="Installing APC cache optimization"
    msg_optimize_webserver_cache_opcache="Installing Zend OpCache cache optimization"
    msg_uptodate="is already installed and up-to-date"
    msg_needinstallupdate="needs to be installed or to be updated"
    msg_ask_install_nginx_ssl="Do you want to install SSL self sign certificat"
    msg_nginx_ssl_config="*                 NGINX SSL configuration               *"
}


install_msg_fr() {
    msg_installer_welcome="*Bienvenue dans l'assistant d'intallation/mise à jour de Jeedom*"
    msg_usage1="Utilisation: $0 [<nom_du_webserver>]"
    msg_usage2="             nom_du_webserver peut être 'apache' ou 'nginx_ssl' ou 'nginx' (par défaut)"
    msg_manual_install_nodejs_ARM="*        Installation manuelle de nodeJS pour ARM       *"
    msg_manual_install_nodejs_RPI="*     Installation manuelle de nodeJS pour Raspberry    *"
    msg_nginx_config="*                Configuration de NGINX                *"
    msg_apache_config="*                Configuration de APACHE               *"
    msg_question_install_jeedom="Etes-vous sûr de vouloir installer Jeedom ?"
    msg_warning_install_jeedom="Attention : ceci écrasera la configuration par défaut de ${ws_upname} si elle existe !"
    msg_warning_overwrite_jeedom="Attention : votre installation existante de Jeedom va être écrasée !"
    msg_yes="oui"
    msg_no="non"
    msg_yesno="oui / non : "
    msg_cancel_install="Annulation de l'installation"
    msg_answer_yesno="Répondez oui ou non"
    msg_install_deps="*             Installation des dépendances             *"
    msg_passwd_mysql="Quel mot de passe venez vous de taper (mot de passe root de la MySql) ?"
    msg_confirm_passwd_mysql="Confirmez vous que le mot de passe est :"
    msg_bad_passwd_mysql="Le mot de passe MySQL fourni est invalide !"
    msg_setup_dirs_and_privs="* Création des répertoires et mise en place des droits *"
    msg_copy_jeedom_files="*             Copie des fichiers de Jeedom             *"
    msg_unable_to_download_file="Impossible de télécharger le fichier"
    msg_config_db="*          Configuration de la base de données         *"
    msg_install_jeedom="*                Installation de Jeedom                *"
    msg_update_jeedom="*                Mise à jour de Jeedom                 *"
    msg_setup_cron="*                Mise en place du cron                 *"
    msg_setup_nodejs_service="*            Mise en place du service nodeJS           *"
    msg_startup_nodejs_service="*             Démarrage du service nodeJS              *"
    msg_post_install_actions="*             Action post installation                 *"
    msg_post_update_actions="*              Action post mise à jour                 *"
    msg_install_complete="*                Installation terminée                 *"
    msg_update_complete="*                Mise à jour terminée                  *"
    msg_or="ou"
    msg_login_info1="Vous pouvez vous connecter sur Jeedom en allant sur :"
    msg_login_info2="Vos identifiants sont :"
    msg_optimize_webserver_cache="*       Vérification de l'optimisation de cache        *"
    msg_php_version="PHP version trouvé : "
    msg_php_already_optimized="PHP est déjà optimisé, utilisation de : "
    msg_optimize_webserver_cache_apc="Installation de l'optimisation de cache APC"
    msg_optimize_webserver_cache_opcache="Installation de l'optimisation de cache Zend OpCache"
    msg_uptodate="est déjà installé et à jour"
    msg_needinstallupdate="nécessite une installation ou une mise à jour"
    msg_ask_install_nginx_ssl="Voules vous mettre en place un certification SSL auto signé"
    msg_nginx_ssl_config="*                 NGINX SSL configuration               *"
}


install_msg_de() {
    msg_installer_welcome="*      Willkommen beim Jeedom Installer / Updater        *"
    msg_usage1="Einsatz: $0 [<Name_des_Webservers>]"
    msg_usage2="            Webserver_Name kann "Apache" oder "nginx_ssl" oder "nginx" (Standard) sein"
    msg_manual_install_nodejs_ARM="*          Manuelle Installation von nodejs für ARM      *"
    msg_manual_install_nodejs_RPI="*     Manuelle Installation von nodejs für Raspberry     *"
    msg_nginx_config="*                  NGINX Konfiguration                 *"
    msg_apache_config="*                  APACHE Konfiguration                *"
    msg_question_install_jeedom="Sind Sie sicher, dass Sie Jeedom installieren wollen?"
    msg_warning_install_jeedom="Warnung: Diese überschreibt die Standard Konfiguration ${ws_upname}, falls vorhanden!"
    msg_warning_overwrite_jeedom="Warnung: Ihr vorhandene Jeedom Installation wird überschrieben!"
    msg_yes="ja"
    msg_no="nein"
    msg_yesno="ja/nein: "
    msg_cancel_install="Abbruch der Installation"
    msg_answer_yesno="Antwort ja oder nein"
    msg_install_deps="*               Installations Abhängigkeiten            *"
    msg_passwd_mysql="Welches Kennwort haben Sie gerade eingegeben (Root von MySQL)? "
    msg_confirm_passwd_mysql="Bestätigen sie, dass das Passwort:"
    msg_bad_passwd_mysql="Das MySQL-Passwort ist ungültig!"
    msg_setup_dirs_and_privs="*      Das Erzeugen von Verzeichnissen und die Aufstellung von Rechten       *"
    msg_copy_jeedom_files="*                Das Kopieren von Jeedom-Dateien              *"
    msg_unable_to_download_file="Unmöglich die Datei herrunter zu laden"
    msg_config_db="*               Konfiguration der Datenbank               *"
    msg_install_jeedom="*                Installieren von Jeedom                *"
    msg_update_jeedom="*                  Aktualisieren von Jeedom                  *"
    msg_setup_cron="*                    Cron einrichten                   *"
    msg_setup_nodejs_service="*               Einrichten von nodejs Dienst              *"
    msg_startup_nodejs_service="*             Starten von nodejs Dienst              *"
    msg_post_install_actions="*             Aktionen nach der Installation                *"
    msg_post_update_actions="*               Aktionen nach Update                    *"
    msg_install_complete="*                Installation abgeschlossen                *"
    msg_update_complete="*                   Aktualisierung abgeschlossen                    *"
    msg_or="or"
    msg_login_info1="Sie können sich in Jeedom einzloggen, indem Sie auf:"
    msg_login_info2="Ihre Anmeldedaten sind:"
    msg_optimize_webserver_cache="*      Überprüfen auf Webserver-Caching-Optimierung       *"
    msg_php_version="PHP Version gefunden: "
    msg_php_already_optimized="PHP wird bereits optimiert, mit:  "
    msg_optimize_webserver_cache_apc="Installation von APC-Cache-Optimierung"
    msg_optimize_webserver_cache_opcache="Installation von OpCache-Cache-Optimierung"
    msg_uptodate="ist bereits installiert und auf dem neuesten Stand"
    msg_needinstallupdate="Muss installiert oder aktualisiert werden!"
    msg_ask_install_nginx_ssl="Möchten Sie SSL installieren, selbst signiertes Zertifikat"
    msg_nginx_ssl_config="*                 NGINX SSL-Konfiguration               *"
}

########################## Helper functions ############################


setup_i18n() {
    lang=${LANG:=en_US}
    case ${lang} in
        [Ff][Rr]*)
            install_msg_fr
        ;;
        [Ee][Nn]*|*)
            install_msg_en
        ;;
        [De][De]*|*)
            install_msg_de
        ;;
    esac
}


usage_help() {
    echo "${msg_usage1}"
    echo "${msg_usage2}"
    exit 1
}


configure_php() {
    [ -z "`getent group dialout | grep www-data`" ] && adduser www-data dialout
    GPIO_GROUP="`cat /etc/group | grep -e 'gpio'`"
    if [ -z "${JEEDOM_CRON}" ] ; then
        [ -z "`getent group gpio | grep www-data`" ] && adduser www-data gpio
    fi
    sed -i 's/max_execution_time = 30/max_execution_time = 300/g' /etc/php5/fpm/php.ini
    sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 1G/g' /etc/php5/fpm/php.ini
    sed -i 's/post_max_size = 8M/post_max_size = 1G/g' /etc/php5/fpm/php.ini
}


install_nodejs() {
    # Check if nodeJS v0.10.25 is installed,
    # otherwise, try to install it from various sources (official,
    # backport, jeedom.fr)

    check_nodejs_version
    [ $? -eq 1 ] && return

    # If running wheezy, try wheezy-backport
    if [ -n "`grep wheezy /etc/apt/sources.list`" ] ; then
        if [ -z "`grep wheezy-backports /etc/apt/sources.list`" ] ; then
            # apply wheezy-backport patch
            echo "deb http://http.debian.net/debian wheezy-backports main" >> /etc/apt/sources.list

            # Add wheezy-backport keyring
            gpg --keyserver pgpkeys.mit.edu --recv 8B48AD6246925553
            gpg --export --armor 8B48AD6246925553 > missingkey.gpg
            apt-key add missingkey.gpg
            rm -f missingkey.gpg
        fi
            # otherwise, Jessie is good ; other-otherwise ?

            apt-get update

        # Install nodeJS
        apt-get -t wheezy-backports -y install nodejs libev4 libv8-3.8.9.20
    else
        # else, simply try to install
        apt-get -y install nodejs
    fi

    # Seems buggy on Raspbian (throw 'Illegal instruction')
    check_nodejs_version
    [ $? -eq 1 ] && return
    
    # Fallback, if APT method failed
    if [ $? -ne 0 ] ; then
        ARM=$(uname -a | grep arm | wc -l)
        if [ $( cat /etc/os-release | grep raspbian | wc -l) -gt 0 ] ; then
            echo "********************************************************"
            echo "${msg_manual_install_nodejs_RPI}"
            echo "********************************************************"
            wget --no-check-certificate http://jeedom.fr/ressources/nodejs/node-raspberry.bin
            rm -rf /usr/local/bin/node
            rm -rf /usr/bin/nodejs
            mv node-raspberry.bin /usr/local/bin/node
            ln -s /usr/local/bin/node /usr/bin/nodejs
            chmod +x /usr/local/bin/node
        elif [ ${ARM} -ne 0 ] ; then
            echo "********************************************************"
            echo "${msg_manual_install_nodejs_ARM}"
            echo "********************************************************"
            wget --no-check-certificate http://jeedom.fr/ressources/nodejs/node-v0.10.21-cubie.tar.xz
            tar xJvf node-v0.10.21-cubie.tar.xz -C /usr/local --strip-components 1
            if [ ! -f '/usr/bin/nodejs' ] && [ -f '/usr/local/bin/node' ] ; then
                ln -s /usr/local/bin/node /usr/bin/nodejs
            fi
            rm -rf node-v0.10.21-cubie.tar.xz
        fi
    fi
    
    # Remove wheezy-backports
    if [ -n "`grep wheezy-backports /etc/apt/sources.list`" ] ; then
        cat /etc/apt/sources.list | sed 's/deb http:\/\/http.debian.net\/debian wheezy-backports main//' > sources.list
        mv -f sources.list /etc/apt/sources.list
    fi
}


configure_nginx() {
    echo "********************************************************"
    echo "${msg_nginx_config}"
    echo "********************************************************"
    for i in apache2 apache mongoose ; do
        if [ -f "/etc/init.d/${i}" ] ; then
            service ${i} stop
            update-rc.d -f ${i} remove
        fi
    done
    service nginx stop
    JEEDOM_ROOT="`cat /etc/nginx/sites-available/default | grep -e 'root /usr/share/nginx/www/jeedom;'`"
    if [ -f '/etc/nginx/sites-available/defaults' ] ; then
        rm /etc/nginx/sites-available/default
    fi
    cp install/nginx_default /etc/nginx/sites-available/default
    if [ ! -z "${JEEDOM_ROOT}" ] ; then
        sed -i 's%root /usr/share/nginx/www;%root /usr/share/nginx/www/jeedom;%g' /etc/nginx/sites-available/default
    fi
    if [ ! -f '/etc/nginx/sites-available/jeedom_dynamic_rule' ] ; then
        cp install/nginx_jeedom_dynamic_rules /etc/nginx/sites-available/jeedom_dynamic_rule
    fi
    chmod 777 /etc/nginx/sites-available/jeedom_dynamic_rule
    if [ ! -f '/etc/nginx/sites-enabled/default' ] ; then
        ln -s /etc/nginx/sites-available/default /etc/nginx/sites-enabled/default
    fi
    service nginx restart
    configure_php
    update-rc.d nginx defaults

    JEEDOM_CRON="`crontab -l | grep -e 'jeeCron.php'`"

    if [ -z "${JEEDOM_CRON}" ] ; then
        croncmd="su --shell=/bin/bash - www-data -c '/usr/bin/php /usr/share/nginx/www/jeedom/core/php/jeeCron.php' >> /dev/null 2>&1"
        cronjob="* * * * * $croncmd"
        ( crontab -l | grep -v "$croncmd" ; echo "$cronjob" ) | crontab -
    fi
    for i in apache2 apache mongoose ; do
        if [ -f "/etc/init.d/${i}" ] ; then
            service ${i} stop
            update-rc.d -f ${i} remove
        fi
    done
    if [ ! -f '/etc/nginx/sites-enabled/default_ssl' ] ; then
        configure_nginx_ssl         
    fi          
}


configure_nginx_ssl() {
    echo "********************************************************"
    echo "${msg_nginx_ssl_config}"
    echo "********************************************************"
    openssl genrsa -out jeedom.key 2048
    openssl req \
    -new \
    -subj "/C=FR/ST=France/L=Paris/O=jeedom/OU=JE/CN=jeedom" \
    -key jeedom.key \
    -out jeedom.csr
    openssl x509 -req -days 9999 -in jeedom.csr -signkey jeedom.key -out jeedom.crt
    mkdir /etc/nginx/certs
    cp jeedom.key /etc/nginx/certs
    cp jeedom.crt /etc/nginx/certs
    rm jeedom.key jeedom.crt

    JEEDOM_ROOT="`cat /etc/nginx/sites-available/default | grep -e 'root /usr/share/nginx/www/jeedom;'`"
    cp ${webserver_home}/jeedom/install/nginx_default_ssl /etc/nginx/sites-available/default_ssl
    if [ ! -f '/etc/nginx/sites-enabled/default_ssl' ] ; then
        ln -s /etc/nginx/sites-available/default_ssl /etc/nginx/sites-enabled/default_ssl
    fi
    if [ ! -z "${JEEDOM_ROOT}" ] ; then
        sed -i 's%root /usr/share/nginx/www;%root /usr/share/nginx/www/jeedom;%g' /etc/nginx/sites-available/default_ssl
    fi
    for i in apache2 apache mongoose ; do
        if [ -f "/etc/init.d/${i}" ] ; then
            service ${i} stop
            update-rc.d -f ${i} remove
        fi
    done
    service nginx reload
    update-rc.d nginx
}


configure_apache() {
    echo "********************************************************"
    echo "${msg_apache_config}"
    echo "********************************************************"
    cp install/apache_default /etc/apache2/sites-available/000-default.conf
    if [ ! -f '/etc/apache2/sites-enabled/000-default.conf' ] ; then
        a2ensite 000-default.conf
    fi
    service apache2 restart

    croncmd="su --shell=/bin/bash - www-data -c 'nice -n 19 /usr/bin/php /var/www/jeedom/core/php/jeeCron.php' >> /dev/null 2>&1"
    cronjob="* * * * * $croncmd"
    ( crontab -l | grep -v "$croncmd" ; echo "$cronjob" ) | crontab -

    configure_php
}


is_version_greater_or_equal() {
    # Compare two "X.Y.Z" formated versions
    # Return 0 if $1 is lesser than $2
    # Return 1 if $1 is greater than or equal to $2

    newCMP="$1"
    [ -z "$1" ] && newCMP="0.0.0"

    # only compare the 2 first digits
    for i in 1 2 3 ; do
        REF="`echo $2 | cut -d. -f$i`"
        CMP="`echo ${newCMP} | cut -d. -f$i`"
        if [ $CMP -lt $REF ] ; then
            # not greater or equal
            return 0
            break
        fi
    done
    # greater or equal
    return 1
}


check_nodejs_version() {
    # Check if nodeJS v0.10.25 (or higher) is installed.
    # Return 1 of true, 0 (or else) otherwise

    NODEJS_VERSION="`nodejs -v 2>/dev/null  | sed 's/["v]//g'`"
    is_version_greater_or_equal "${NODEJS_VERSION}" "0.10.25"
    RETVAL=$?
    case ${RETVAL} in
        1)
            # Already installed and up to date
            echo "===> nodeJS ${msg_uptodate}"
        ;;
        0)
            # continue...
            echo "===> nodeJS ${msg_needinstallupdate}"
        ;;
    esac

    return ${RETVAL}
}


optimize_webserver_cache_apc() {
    # php < 5.5 => APC
    echo "${msg_optimize_webserver_cache_apc}"
    apt-get install -y php-apc php-pear php5-dev build-essential libpcre3-dev
    pear config-set php_ini /etc/php5/fpm/php_ini
    pear config-set php_ini /etc/php5/cli/php_ini
    pecl config-set php_ini /etc/php5/fpm/php_ini
    pecl config-set php_ini /etc/php5/cli/php_ini
    # Force pecl unattended mode
    yes '' | pecl install -fs apc
    echo 'apc.enable_cli = 1' >> /etc/php5/cli/conf.d/20-apc.ini
}


optimize_webserver_cache_opcache() {
    # php >= 5.5 => OPcache
    echo "${msg_optimize_webserver_cache_opcache}"
    apt-get install -y php-pear php5-dev build-essential
    # Force pecl unattended mode
    yes '' | pecl install -fs zendopcache-7.0.3

    # Enable cache for FPM and CLI
    for i in fpm cli ; do
        echo "zend_extension=opcache.so" >> /etc/php5/${i}/php.ini
        echo "opcache.memory_consumption=256"  >> /etc/php5/${i}/php.ini
        echo "opcache.interned_strings_buffer=8"  >> /etc/php5/${i}/php.ini
        echo "opcache.max_accelerated_files=4000"  >> /etc/php5/${i}/php.ini
        echo "opcache.revalidate_freq=1"  >> /etc/php5/${i}/php.ini
        echo "opcache.fast_shutdown=1"  >> /etc/php5/${i}/php.ini
        echo "opcache.enable_cli=1"  >> /etc/php5/${i}/php.ini
        echo "opcache.enable=1"  >> /etc/php5/${i}/php.ini
    done
}


optimize_webserver_cache() {
    # Check the version of PHP, and if already optimized
    # Otherwise, install cache optimization according to PHP version

    echo "${msg_php_version}${PHP_VERSION}"
    # Check if PHP is already optimized or not (empty string)
    if [ -n "${PHP_OPTIMIZATION}" ] ; then
        echo "${msg_php_already_optimized}${PHP_OPTIMIZATION}"
        return
    fi

    is_version_greater_or_equal "${PHP_VERSION}" "5.5.0"
    case $? in
        0)
            optimize_webserver_cache_apc
        ;;
        1)
            optimize_webserver_cache_opcache
        ;;
    esac
}


install_dependency() {
    apt-get update && \
    apt-get -y install \
        build-essential \
        curl \
        libarchive-dev \
        libav-tools \
        libjsoncpp-dev \
        libpcre3-dev \
        libssh2-php \
        libtinyxml-dev \
        libxml2 \
        make \
        miniupnpc \
        mysql-client \
        mysql-common \
        mysql-server \
        mysql-server-core-5.5 \
        npm \
        ntp \
        php5-cli \
        php5-common \
        php5-curl \
        php5-dev \
        php5-fpm \
        php5-json \
        php5-mysql \
        php-pear \
        python-serial \
        systemd \
        unzip \
        usb-modeswitch

    apt-get -y install \
        ffmpeg

    apt-get -y install \
        avconv \
        libudev1

    pecl install oauth
    if [ $? -eq 0 ] ; then
        for i in fpm cli ; do
            PHP_OAUTH="`cat /etc/php5/${i}/php.ini | grep -e 'oauth.so'`"
            if [ -z "${PHP_OAUTH}" ] ; then
                echo "extension=oauth.so" >> /etc/php5/${i}/php.ini
            fi
        done
    fi

    install_nodejs

    apt-get autoremove
}


install_dependency_nginx() {
    apt-get install -y nginx-common nginx-full
}


install_dependency_apache() {
    # Packages dependencies
    apt-get install -y apache2 libapache2-mod-php5 autoconf make subversion
    svn checkout http://svn.apache.org/repos/asf/httpd/httpd/tags/2.2.22/ httpd-2.2.22
    wget --no-check-certificate http://cafarelli.fr/gentoo/apache-2.2.24-wstunnel.patch
    cd httpd-2.2.22
    patch -p1 < ../apache-2.2.24-wstunnel.patch
    svn co http://svn.apache.org/repos/asf/apr/apr/branches/1.4.x srclib/apr
    svn co http://svn.apache.org/repos/asf/apr/apr-util/branches/1.3.x srclib/apr-util
    ./buildconf
    ./configure --enable-proxy=shared --enable-proxy_wstunnel=shared
    make
    cp modules/proxy/.libs/mod_proxy{_wstunnel,}.so /usr/lib/apache2/modules/
    chmod 644 /usr/lib/apache2/modules/mod_proxy{_wstunnel,}.so
    echo "# Depends: proxy\nLoadModule proxy_wstunnel_module /usr/lib/apache2/modules/mod_proxy_wstunnel.so" | tee -a /etc/apache2/mods-available/proxy_wstunnel.load
    a2enmod proxy_wstunnel
    a2enmod proxy_http
    a2enmod proxy
    service apache2 restart
}


##################### Main (script entry point) ########################


webserver=${1-nginx}
ws_upname="$(echo ${webserver} | tr 'a-z' 'A-Z')"

# Select the right language, among available ones
setup_i18n

echo "********************************************************"
echo "${msg_installer_welcome}"
echo "********************************************************"

# Check for root priviledges
if [ $(id -u) != 0 ] ; then
    echo "Super-user (root) privileges are required to install Jeedom"
    echo "Please run 'sudo $0' or log in as root, and rerun $0"
    exit 1
fi

# Check that the provided ${webserver} is supported [nginx,apache]
case ${webserver} in
    nginx)
        # Configuration
        webserver_home="/usr/share/nginx/www"
        croncmd="su --shell=/bin/bash - www-data -c 'nice -n 19 /usr/bin/php /usr/share/nginx/www/jeedom/core/php/jeeCron.php' >> /dev/null 2>&1"
    ;;
    apache)
        # Configuration
        webserver_home="/var/www"
        croncmd="su --shell=/bin/bash - www-data -c 'nice -n 19 /usr/bin/php /var/www/jeedom/core/php/jeeCron.php' >> /dev/null 2>&1"
    ;;
    nginx_ssl)
        # Configuration
        webserver_home="/usr/share/nginx/www"
        configure_nginx_ssl
        exit 1
    ;;
    update_nginx)
        # Configuration
        webserver_home="/usr/share/nginx/www"
        echo "********************************************************"
        echo "${msg_install_deps}"
        echo "********************************************************"
        install_dependency
        install_dependency_nginx

        cd $webserver_home/jeedom
        configure_nginx

        echo "********************************************************"
        echo "${msg_setup_nodejs_service}"
        echo "********************************************************"
        cp jeedom /etc/init.d/
        chmod +x /etc/init.d/jeedom
        update-rc.d jeedom defaults

        echo "********************************************************"
        echo "${msg_startup_nodejs_service}"
        echo "********************************************************"
        service jeedom restart
        echo '[END UPDATE SUCCESS]'
        service php5-fpm restart
        exit 1
    ;;
    *)
        usage_help
        exit 1
    ;;
esac

echo "${msg_question_install_jeedom}"
echo "${msg_warning_install_jeedom}"
[ -d "${webserver_home}/jeedom/" ] && echo "${msg_warning_overwrite_jeedom}"
while true ; do
    echo -n "${msg_yesno}"
    read ANSWER < /dev/tty
    case $ANSWER in
        ${msg_yes})
            break
        ;;
        ${msg_no})
            echo "${msg_cancel_install}"
            exit 1
        ;;
    esac
    echo "${msg_answer_yesno}"
done

echo "********************************************************"
echo "${msg_install_deps}"
echo "********************************************************"

install_dependency
if [ "${webserver}" = "nginx" ] ; then 
    # Packages dependencies
    install_dependency_nginx
fi

if [ "${webserver}" = "apache" ] ; then 
    install_dependency_apache
fi

echo "${msg_passwd_mysql}"
while true ; do
    read MySQL_root < /dev/tty
    echo "${msg_confirm_passwd_mysql} ${MySQL_root}"
    while true ; do
        echo -n "${msg_yesno}"
        read ANSWER < /dev/tty
        case $ANSWER in
            ${msg_yes})
                break
            ;;
            ${msg_no})
                break
            ;;
        esac
        echo "${msg_answer_yesno}"
    done    
    if [ "${ANSWER}" = "${msg_yes}" ] ; then
        # Test access immediately
        # to ensure that the provided password is valid
        CMD="`echo "show databases;" | mysql -uroot -p${MySQL_root}`"
        if [ $? -eq 0 ] ; then
            # good password
            break
        else
            echo "${msg_bad_passwd_mysql}"
            echo "${msg_passwd_mysql}"
            continue
        fi
    fi
done

echo "********************************************************"
echo "${msg_setup_dirs_and_privs}"
echo "********************************************************"

mkdir -p "${webserver_home}"
cd "${webserver_home}"
chown www-data:www-data -R "${webserver_home}"

echo "********************************************************"
echo "${msg_copy_jeedom_files}"
echo "********************************************************"
if [ -d "jeedom" ] ; then
    rm -rf jeedom
fi
wget --no-check-certificate -O jeedom.zip https://market.jeedom.fr/jeedom/stable/jeedom.zip
if [  $? -ne 0 ] ; then
    wget --no-check-certificate -O jeedom.zip https://market.jeedom.fr/jeedom/stable/jeedom.zip
    if [  $? -ne 0 ] ; then
        echo "${msg_unable_to_download_file}"
        exit 0
    fi
fi
unzip jeedom.zip -d jeedom
mkdir "${webserver_home}"/jeedom/tmp
chmod 775 -R "${webserver_home}"
chown -R www-data:www-data "${webserver_home}"
rm -rf jeedom.zip
cd jeedom

echo "********************************************************"
echo "${msg_config_db}"
echo "********************************************************"
bdd_password=$(cat /dev/urandom | tr -cd 'a-f0-9' | head -c 15)
echo "DROP USER 'jeedom'@'localhost'" | mysql -uroot -p${MySQL_root}
echo "CREATE USER 'jeedom'@'localhost' IDENTIFIED BY '${bdd_password}';" | mysql -uroot -p${MySQL_root}
echo "DROP DATABASE IF EXISTS jeedom;" | mysql -uroot -p${MySQL_root}
echo "CREATE DATABASE jeedom;" | mysql -uroot -p${MySQL_root}
echo "GRANT ALL PRIVILEGES ON jeedom.* TO 'jeedom'@'localhost';" | mysql -uroot -p${MySQL_root}

echo "********************************************************"
echo "${msg_install_jeedom}"
echo "********************************************************"
cp core/config/common.config.sample.php core/config/common.config.php
sed -i -e "s/#PASSWORD#/${bdd_password}/g" core/config/common.config.php 
chown www-data:www-data core/config/common.config.php
php install/install.php mode=force

echo "********************************************************"
echo "${msg_setup_cron}"
echo "********************************************************"

case ${webserver} in
    nginx)
        configure_nginx
    ;;
    apache)
        configure_apache
    ;;
esac

echo "********************************************************"
echo "${msg_optimize_webserver_cache}"
echo "********************************************************"
# Get the currently installed php version
PHP_VERSION="`php -v | awk '/PHP [0-9].[0-9].[0-9].*/{ print $2 }' | cut -d'-' -f1`"
PHP_OPTIMIZATION="`php -v | grep -e 'OPcache' -o -e 'APC'`"
# Workaround APC detection: APC is not exposed in 'php -v'
if [ -z "${PHP_OPTIMIZATION}" ] ; then
    APC_INI="/etc/php5/cli/conf.d/20-apc.ini"
    if [ -f /etc/php5/cli/conf.d/20-apc.ini ] ; then
        PHP_OPTIMIZATION="APC"
    fi
fi
optimize_webserver_cache

echo "********************************************************"
echo "${msg_setup_nodejs_service}"
echo "********************************************************"
cp install/jeedom /etc/init.d/
chmod +x /etc/init.d/jeedom
update-rc.d jeedom defaults

if [ -d /etc/systemd/system ] ; then
    cp install/jeedom.service /etc/systemd/system
    systemctl enable jeedom
fi

if [ "${webserver}" = "apache" ] ; then 
    sed -i 's%PATH_TO_JEEDOM="/usr/share/nginx/www/jeedom"%PATH_TO_JEEDOM="/var/www/jeedom"%g' /etc/init.d/jeedom
    if [ -d /etc/systemd/system ] ; then
        sed -i 's%/usr/share/nginx/www/jeedom%/var/www/jeedom%g' /etc/systemd/system/jeedom.service
    fi
fi
service jeedom start

echo "********************************************************"
echo "${msg_post_install_actions}"
echo "********************************************************"
cp install/motd /etc
chown root:root /etc/motd
chmod 644 /etc/motd
service php5-fpm restart

echo "********************************************************"
echo "${msg_install_complete}"
echo "********************************************************"
IP=$(ifconfig eth0 | grep 'inet addr:' | cut -d: -f2 | awk '{print $1}')
HOST=$(hostname -f)
echo "${msg_login_info1}"
echo "\n\t\thttp://$IP/jeedom ${msg_or} http://$HOST/jeedom\n"
echo "${msg_login_info2} admin/admin"
