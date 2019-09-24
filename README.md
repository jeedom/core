<img align="right" src="https://www.jeedom.com/site/logo.png" width="100">

# Jeedom - La domotique innovante #
<p align="center">
<a href="https://www.jeedom.com/site">Site</a>  -
<a href="https://www.jeedom.com/blog">Blog</a>  -
<a href="https://www.jeedom.com/forum">Forum</a>  -
<a href="https://www.jeedom.com/market">Market</a>  -
<a href="https://www.jeedom.com/doc">Doc</a>
</p>

# Installation #

## Pre-requis
- MySQL installé (en local ou sur une machine distante).
- Un serveur web installé (apache ou nginx).
- php (5.6 minimum) installé avec les extensions : curl, json, gd, zip et mysql.
- ntp et crontab installés.
- curl, unzip et sudo installés.

---
*TIPS : pour nginx vous trouverez un exemple de la configuration web nécessaire dans install/nginx_default.*

---

### Création de la BDD jeedom

Il vous faut créer une base de données Jeedom sur MySQL (en utf8_general_ci).

### Téléchargement des fichiers

Téléchargez les sources Jeedom : https://github.com/jeedom/core/archive/stable.zip.
Décompressez-les dans un répertoire de votre serveur web.

### Configuration et installation

Allez avec un navigateur sur `install/setup.php`.

Remplissez les informations, validez et attendez la fin de l'installation.
Les identifiants par défaut sont Admin/admin.

---
