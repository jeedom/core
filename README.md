![Travis ci](https://travis-ci.org/jeedom/core.svg)

# Jeedom by Loïc Gevrey #

Website (English): [https://jeedom.com/site/en/](https://jeedom.com/site/en/)

Website (French):  [https://jeedom.com/site/](https://jeedom.com/site/)

(Readme en français [ci-dessous](#french).)

# Install #

## Pre-requis
- mysql d'installé (en local ou sur une machine distance)
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

Et allez (avec votre navigateur) sur install/setup.php

Remplissez les informations, validez et attendez la fin de l'installation. Les identifiants par défaut sont admin/admin
