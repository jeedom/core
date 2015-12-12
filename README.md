![Travis ci](https://travis-ci.org/jeedom/core.svg)

# Jeedom by Loïc Gevrey #

Website (English): [https://jeedom.fr/site/en/](https://jeedom.fr/site/en/)

Website (French):  [https://jeedom.fr/site/](https://jeedom.fr/site/)

(Readme en français [ci-dessous](#french).)

# Install #


## Installation automatique

ATTENTION : tout est fait automatiquement, mysql et nginx ne doivent surtout pas etre installé sur le système

```bash
sudo su -
apt-get update
apt-get dist-upgrade
apt-get install wget unzip
cd /var/www/
wget https://github.com/jeedom/core/archive/stable.zip -O jeedom.zip
unzip jeedom.zip
cd jeedom
install/install.sh
```

## Installation manuel

### Pre-requis
- mysql d'installé
- un serveur web d'installé (apache ou nginx)
- php (5.6 minimum) d'installé avec les extensions : curl, json et mysql
- ntp et crontab d'installé
- curl, unzip et sudo d'installés

TIPS : pour nginx vous trouverez un exemple de la configuration web necessaire dans install/nginx_default

### Création de la BDD jeedom

Il vous faut creer une base de données jeedom sur mysql (en utf8_general_ci)

### Téléchargement des fichiers

Téléchargez les sources jeedom : https://github.com/jeedom/core/archive/stable.zip, décompressé les dans un repertoire de votre serveur web

### Configuration et installation

Renommer le fichier core/config/common.config.sample.php en core/config/common.config.php puis editez le pour configurer l'accès au serveur de BDD

Lancer l'installation : 

```bash
php install/install.php
```

### Ajout des droits sudo

Pour fonctionner Jeedom a besoin des droits sudo : 

```bash
echo "www-data ALL=(ALL) NOPASSWD: ALL" | (EDITOR="tee -a" visudo)
```

ATTENTION : si votre serveur web n'utilise pas www-data il faut adapter la ligne de commande

### Ajout du cron

Dans la crontab ajouter la ligne suivante : 
```bash
su --shell=/bin/bash - www-data -c '/usr/bin/php #REP_JEEDOM#/core/php/jeeCron.php' >> /dev/null 2>&1"
```

ATTENTION : si votre serveur web n'utilise pas www-data il faut adapter la ligne de commande

ATTENTION : à bien adapter #REP_JEEDOM# en fonction de la ou est installé jeedom

### Configuration PHP

Il est recommandé d'autoriser un temps d'éxecution de 300 secondes pour php (max_execution_time) et d'autoriser des upload de 1G (upload_max_filesize et post_max_size)