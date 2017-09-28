# Jeedom by Loïc #

Website (English): [https://jeedom.com/site/en/](https://jeedom.com/site/en/)

Website (French):  [https://jeedom.com/site/](https://jeedom.com/site/)

# Installation #

## Pre-requis
- mysql installé (en local ou sur une machine distante)
- un serveur web d'installé (apache ou nginx)
- php (5.6 minimum) installé avec les extensions : curl, json, gd et mysql
- ntp et crontab installés
- curl, unzip et sudo installés

TIPS : pour nginx vous trouverez un exemple de la configuration web nécessaire dans install/nginx_default.

### Création de la BDD jeedom

Il vous faut créer une base de données Jeedom sur mysql (en utf8_general_ci).

### Téléchargement des fichiers

Téléchargez les sources Jeedom : https://github.com/jeedom/core/archive/stable.zip, décompressez les dans un répertoire de votre serveur web.

### Configuration et installation

Allez (avec votre navigateur) sur `install/setup.php`.

Remplissez les informations, validez et attendez la fin de l'installation. Les identifiants par défaut sont admin/admin.
