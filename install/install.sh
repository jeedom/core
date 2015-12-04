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
    msg_usage2="            webserver_name can be 'nginx' (default)"
    msg_nginx_config="*                  NGINX configuration                 *"
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
    msg_post_install_actions="*             Post-installation actions                *"
    msg_post_update_actions="*               Post-update actions                    *"
    msg_install_complete="*                Installation complete                 *"
    msg_update_complete="*                   Update complete                    *"
    msg_or="or"
    msg_login_info1="You can log in to Jeedom by going on:"
    msg_login_info2="Your credentials are:"
    msg_php_version="PHP version found : "
    msg_php_already_optimized="PHP is already optimized, using : "
    msg_uptodate="is already installed and up-to-date"
    msg_needinstallupdate="needs to be installed or to be updated"
    msg_nginx_ssl_config="*                 NGINX SSL configuration               *"
}


install_msg_fr() {
    msg_installer_welcome="*Bienvenue dans l'assistant d'intallation/mise à jour de Jeedom*"
    msg_usage1="Utilisation: $0 [<nom_du_webserver>]"
    msg_usage2="             nom_du_webserver peut être 'nginx' (par défaut)"
    msg_nginx_config="*                Configuration de NGINX                *"
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
    msg_post_install_actions="*             Action post installation                 *"
    msg_post_update_actions="*              Action post mise à jour                 *"
    msg_install_complete="*                Installation terminée                 *"
    msg_update_complete="*                Mise à jour terminée                  *"
    msg_or="ou"
    msg_login_info1="Vous pouvez vous connecter sur Jeedom en allant sur :"
    msg_login_info2="Vos identifiants sont :"
    msg_php_version="PHP version trouvé : "
    msg_php_already_optimized="PHP est déjà optimisé, utilisation de : "
    msg_uptodate="est déjà installé et à jour"
    msg_needinstallupdate="nécessite une installation ou une mise à jour"
    msg_ask_install_nginx_ssl="Voules vous mettre en place un certification SSL auto signé"
    msg_nginx_ssl_config="*                 NGINX SSL configuration               *"
}


install_msg_de() {
    msg_installer_welcome="*      Willkommen beim Jeedom Installer / Updater        *"
    msg_usage1="Einsatz: $0 [<Name_des_Webservers>]"
    msg_usage2="            Webserver_Name kann 'nginx' (Standard) sein"
    msg_nginx_config="*                  NGINX Konfiguration                 *"
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
    msg_post_install_actions="*             Aktionen nach der Installation                *"
    msg_post_update_actions="*               Aktionen nach Update                    *"
    msg_install_complete="*                Installation abgeschlossen                *"
    msg_update_complete="*                   Aktualisierung abgeschlossen                    *"
    msg_or="or"
    msg_login_info1="Sie können sich in Jeedom einzloggen, indem Sie auf:"
    msg_login_info2="Ihre Anmeldedaten sind:"
    msg_php_version="PHP Version gefunden: "
    msg_php_already_optimized="PHP wird bereits optimiert, mit:  "
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
    if [ -z "${GPIO_GROUP}" ] ; then
        [ -z "`getent group gpio | grep www-data`" ] && adduser www-data gpio
    fi
    sed -i 's/max_execution_time = 30/max_execution_time = 300/g' /etc/php5/fpm/php.ini
    sed -i 's/upload_max_filesize = 2M/upload_max_filesize = 1G/g' /etc/php5/fpm/php.ini
    sed -i 's/post_max_size = 8M/post_max_size = 1G/g' /etc/php5/fpm/php.ini
    sed -i 's/expose_php = On/expose_php = Off/g' /etc/php5/fpm/php.ini
}

configure_nginx() {
    echo "********************************************************"
    echo "${msg_nginx_config}"
    echo "********************************************************"
    for i in apache2 apache ; do
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

install_dependency() {
    apt-get update
    apt-get -y install
    apt-get -y install build-essential
    apt-get -y install curl
    apt-get -y install libarchive-dev
    apt-get -y install libav-tools
    apt-get -y install libjsoncpp-dev
    apt-get -y install libpcre3-dev
    apt-get -y install libssh2-php
    apt-get -y install libtinyxml-dev
    apt-get -y install libxml2
    apt-get -y install make
    apt-get -y install miniupnpc
    apt-get -y install mysql-client
    apt-get -y install mysql-common
    apt-get -y install mysql-server
    apt-get -y install mysql-server-core-5.5
    apt-get -y install ntp
    apt-get -y install php5-cli
    apt-get -y install php5-common
    apt-get -y install php5-curl
    apt-get -y install php5-dev
    apt-get -y install php5-fpm
    apt-get -y install php5-json
    apt-get -y install php5-mysql
    apt-get -y install php5-ldap
    apt-get -y install php5-memcached
    apt-get -y install php5-redis
    apt-get -y install php-pear
    apt-get -y install python-serial
    apt-get -y install unzip
    apt-get -y install usb-modeswitch
    apt-get -y install ffmpeg
    apt-get -y install avconv
    apt-get -y install libudev1
    apt-get -y install ca-certificates
    apt-get -y install htop

    pecl install oauth
    if [ $? -eq 0 ] ; then
        for i in fpm cli ; do
            PHP_OAUTH="`cat /etc/php5/${i}/php.ini | grep -e 'oauth.so'`"
            if [ -z "${PHP_OAUTH}" ] ; then
                echo "extension=oauth.so" >> /etc/php5/${i}/php.ini
            fi
        done
    fi

    apt-get autoremove
}


install_dependency_nginx() {
    apt-get install -y nginx-common nginx-full
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

# Check that the provided ${webserver} is supported [nginx]
case ${webserver} in
    nginx)
        # Configuration
        webserver_home="/usr/share/nginx/www"
        croncmd="su --shell=/bin/bash - www-data -c 'nice -n 19 /usr/bin/php /usr/share/nginx/www/jeedom/core/php/jeeCron.php' >> /dev/null 2>&1"
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
install_dependency_nginx

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
        echo "show databases;" | mysql -uroot -p"${MySQL_root}"
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
BRANCH="stable"
if [ -z "$1" ]; then
    BRANCH="$1"
fi
wget --no-check-certificate -O jeedom.zip https://github.com/jeedom/core/archive/${BRANCH}.zip
if [  $? -ne 0 ] ; then
    wget --no-check-certificate -O jeedom.zip https://github.com/jeedom/core/archive/${BRANCH}.zip
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

MYSQL_HOST=localhost
MYSQL_PORT=3306
MYSQL_JEEDOM_USER=jeedom
MYSQL_JEEDOM_DBNAME=jeedom

echo "********************************************************"
echo "${msg_config_db}"
echo "********************************************************"
bdd_password=$(cat /dev/urandom | tr -cd 'a-f0-9' | head -c 15)
echo "DROP USER '${MYSQL_JEEDOM_USER}'@'%'" | mysql -uroot -p"${MySQL_root}"
echo "CREATE USER '${MYSQL_JEEDOM_USER}'@'%' IDENTIFIED BY '${bdd_password}';" | mysql -uroot -p"${MySQL_root}"
echo "DROP DATABASE IF EXISTS ${MYSQL_JEEDOM_DBNAME};" | mysql -uroot -p"${MySQL_root}"
echo "CREATE DATABASE ${MYSQL_JEEDOM_DBNAME};" | mysql -uroot -p"${MySQL_root}"
echo "GRANT ALL PRIVILEGES ON ${MYSQL_JEEDOM_DBNAME}.* TO '${MYSQL_JEEDOM_USER}'@'%';" | mysql -uroot -p"${MySQL_root}"

echo "********************************************************"
echo "${msg_install_jeedom}"
echo "********************************************************"
cp core/config/common.config.sample.php core/config/common.config.php
sed -i -e "s/#PASSWORD#/${bdd_password}/g" core/config/common.config.php 
sed -i -e "s/#DBNAME#/${MYSQL_JEEDOM_DBNAME}/g" core/config/common.config.php 
sed -i -e "s/#USERNAME#/${MYSQL_JEEDOM_USER}/g" core/config/common.config.php 
sed -i -e "s/#PORT#/${MYSQL_PORT}/g" core/config/common.config.php 
sed -i -e "s/#HOST#/${MYSQL_HOST}/g" core/config/common.config.php 
chown www-data:www-data core/config/common.config.php
php install/install.php mode=force

echo "********************************************************"
echo "${msg_setup_cron}"
echo "********************************************************"

case ${webserver} in
    nginx)
        configure_nginx
    ;;
esac

echo "********************************************************"
echo "${msg_post_install_actions}"
echo "********************************************************"
cp install/motd /etc
chown root:root /etc/motd
chmod 644 /etc/motd
chown -R www-data:www-data /usr/share/nginx/www/jeedom
chmod -R 775 /usr/share/nginx/www/jeedom
service php5-fpm restart

echo "********************************************************"
echo "${msg_install_complete}"
echo "********************************************************"
IP=$(ifconfig eth0 | grep 'inet addr:' | cut -d: -f2 | awk '{print $1}')
HOST=$(hostname -f)
echo "${msg_login_info1}"
echo "\n\t\thttp://$IP/jeedom ${msg_or} http://$HOST/jeedom\n"
echo "${msg_login_info2} admin/admin"
