
<img align="right" src="https://www.jeedom.com/site/logo.png" width="100">

# Jeedom - La domotique innovante
<p align="center">
<a href="https://www.jeedom.com/site">Site</a>  -
<a href="https://www.jeedom.com/blog">Blog</a>  -
<a href="https://community.jeedom.com">Community</a>  -
<a href="https://www.jeedom.com/market">Market</a>  -
<a href="https://www.jeedom.com/doc">Doc</a>
</p>

#### Jeedom permet de nombreuses possibilités dont :
-   Gérer la sécurité des biens et des personnes,
-   Automatiser le chauffage pour un meilleur confort et des économies d'énergie,
-   Visualiser et gérer la consommation énergétique, pour anticiper une dépense et réduire les consommations,
-   Communiquer par la voix, des SMS, des mails ou des applications mobiles,
-   Gérer tous les automatismes de la maison, volets, portail, lumières...
-   Gérer ses périphériques multimédia audio et vidéo, et ses objets connectés.

<p align="center">
<a href="https://www.jeedom.com/site/fr/box.html">Les solutions domotiques plug & play Jeedom</a>
</p>

## Installation

### Pré-requis
- MySQL installé (en local ou sur une machine distante).
- Un serveur web installé (apache ou nginx).
- php (5.6 minimum) installé avec les extensions : curl, json, gd, zip et mysql.
- ntp et crontab installés.
- curl, unzip et sudo installés.


> *TIPS : pour nginx vous trouverez un exemple de la configuration web nécessaire dans install/nginx_default.*


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
