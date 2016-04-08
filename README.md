![Travis ci](https://travis-ci.org/jeedom/core.svg)

# Jeedom by Loïc Gevrey #

Website (English): [https://jeedom.com/site/en/](https://jeedom.com/site/en/)

Website (French):  [https://jeedom.com/site/](https://jeedom.com/site/)

# Installation #

## Pre-requis
- mysql installé (en local ou sur une machine distance)
- un serveur web d'installé (apache ou nginx)
- php (5.6 minimum) installé avec les extensions : curl, json, gd et mysql
- ntp et crontab installés
- curl, unzip et sudo installés

TIPS : pour nginx vous trouverez un exemple de la configuration web necessaire dans install/nginx_default.

### Création de la BDD jeedom

Il vous faut créer une base de données jeedom sur mysql (en utf8_general_ci).

### Téléchargement des fichiers

Téléchargez les sources jeedom : https://github.com/jeedom/core/archive/stable.zip, décompressez les dans un repertoire de votre serveur web.

### Configuration et installation

Allez (avec votre navigateur) sur `install/setup.php`.

Remplissez les informations, validez et attendez la fin de l'installation. Les identifiants par défaut sont admin/admin.
