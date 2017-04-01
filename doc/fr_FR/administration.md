C’est ici que se trouvent la plupart des paramètres de configuration. Bien que nombreux, ils sont pré-configurés par défaut.

La page est accessible par Administration → Configuration :

![](../images/administration.png)

> **Important**
>
> Si vous n'êtes pas en mode expert certains paramètres peuvent ne pas être visibles.

Configuration générale
======================

![](../images/administration1.png)

Dans cet onglet on retrouve des informations générales sur Jeedom :

-   **Nom de votre Jeedom** : peut être réutilisé dans les scénarios ou permettre d’identifier un backup

-   **Clef API** : votre clef API, à utiliser pour toute requête vers l’API Jeedom

-   **Clef API Pro** : utilisée pour communiquer avec le système de gestion de parc

-   **Système** : type de votre hardware

-   **Clef d’installation** : clef hardware de votre Jeedom sur le market. Si votre Jeedom n’apparaît pas dans la liste de vos Jeedom sur le market il est conseillé de cliquer sur ce bouton

-   **Langue** : Langue de votre Jeedom

-   **Durée de vie des sessions (heure)** : durée de vie des sessions PHP, il est conseillé de ne pas y toucher

-   **Date et heure** : votre fuseau horaire

-   **Serveur de temps optionnel** : permet d’ajouter un serveur de temps optionnel (à réserver aux experts)

-   **Ignorer la vérification de l’heure** : indique à Jeedom de ne pas vérifier si l’heure est cohérente. Utile par exemple si vous ne connectez pas Jeedom à Internet et qu’il n’a pas de pile RTC

-   **Mode** : Mode de votre Jeedom maître ou esclave, attention lors du passage en esclave : tous vos équipements, scénarios, designs… seront supprimés.

Composants Jeedom
=================

![](../images/administration12.png)

Cette partie permet d’activer/désactiver certains composants du core jeedom :

-   **Droits avancés** : permet de désactiver la gestion des droits avancés (si vous ne vous en servez pas cela peut rendre Jeedom plus réactif)

-   **Sécurité** : permet d’activer ou non la sécurité anti-piratage de Jeedom (brute force sur le mot de passe).

Base de données
===============

![](../images/administration2.png)

-   **Accès à l’interface d’administration** : permet d’accèder à une interface d’administration de la base, les informations à renseigner sont en dessous

-   **Machine (hostname)** : localisation de la base

-   **Utilisateur** : nom d’utilisateur utilisé par Jeedom

-   **Mot de passe** : mot de passe d’accès à la base utilisée par Jeedom

Configuration réseau
====================

![](../images/administration3.png)

Partie très importante dans Jeedom, il faut absolument la configurer correctement sinon beaucoup de plugins risquent d'être non fonctionnels.

-   **Accès interne** : information pour joindre Jeedom à partir d’un équipement sur le même réseau

    -   **Protocole** : le protocole à utiliser, souvent HTTP

    -   **Adresse URL ou IP** : IP de Jeedom, à renseigner

    -   **Complément (exemple : /jeedom)** : le fragment d’URL complémentaire (type /jeedom). Pour savoir si vous avez besoin de définir une valeur ici, regardez si quand vous vous connectez à Jeedom vous devez renseigner /jeedom après l’IP

    -   **Port** : le port, en général 80. Attention changer le port ici ne change pas le port réel de Jeedom qui restera le même

    -   **Statut** : indique si la configuration réseau interne est correcte

-   **Accès externe** : information pour joindre Jeedom de l’extérieur. À ne remplir que si vous n’utilisez pas le DNS Jeedom

    -   **Protocole** : protocole utilisé pour l’accès extérieur

    -   **Adresse URL ou IP** : adresse ou IP externe si fixe

    -   **Complément (exemple : /jeedom)** : le fragment d’URL complémentaire (type /jeedom). Pour savoir si vous avez besoin de définir une valeur ici, regardez si quand vous vous connectez à Jeedom vous devez renseigner /jeedom après l’IP

-   **Gestion avancée** : peut ne pas apparaitre en fonction de la compatibilité avec votre matériel

    -   **Désactiver la gestion du réseau par Jeedom** : indique à Jeedom de ne pas monitorer le réseau (à activer si Jeedom n’est connecté à aucun réseau)

-   **Proxy market** : permet un accès distant à votre Jeedom sans avoir besoin d’un DNS, d’une IP fixe ou d’ouvrir les ports de votre box

    -   **Utiliser les DNS Jeedom** : active les DNS Jeedom (attention cela nécessite au moins un service pack)

    -   **Statut DNS** : statut du DNS HTTP

    -   **Gestion** : permet d’arrêter et relancer le service DNS

> **Tip**
>
> Si vous êtes en HTTPS le port est le 443 (par défaut) et en HTTP le port est par défaut le 80

> **Important**
>
> Cette partie est juste là pour expliquer à Jeedom son environnement : une modification du port ou de l’IP ici ne changera pas le port ou l’IP de Jeedom. Pour cela il faut se connecter en SSH et éditer le fichier /etc/network/interfaces pour l’IP et les fichiers etc/nginx/sites-available/default et etc/nginx/sites-available/default\_ssl (pour le HTTPS). En cas de mauvaise manipulation de votre Jeedom, l'équipe Jeedom ne pourra être tenue pour responsable et pourra refuser toute demande de support.

> **Note**
>
> Vous pouvez voir [ici](http://blog.domadoo.fr/2014/10/15/acceder-depuis-lexterieur-jeedom-en-https) un tutoriel pour mettre en place un certificat HTTPS auto-signé.

> **Important**
>
> Si vous n’arrivez pas à faire fonctionner le DNS Jeedom, regardez la configuration du pare-feu et filtre parental de votre box (sur livebox il faut par exemple le pare-feu en moyen).

Configuration des couleurs
==========================

La colorisation des widgets est effectuée en fonction de la catégorie d’appartenance du widget qui est définie dans la configuration de chaque module (voir plugin Z-Wave, RFXCOM… etc). Parmi les catégories on retrouve le chauffage, les lumières, les automatismes etc…

Pour chaque catégorie, on pourra choisir une couleur différente entre la version desktop et la version mobile. Il y a également 2 types de couleurs, les couleurs de fond des widgets et les couleurs des commandes lorsque le widget est de type graduel, par exemple les lumières, les volets, les températures.

![](../images/display6.png)

En cliquant sur la couleur une fenêtre s’ouvre, permettant de choisir sa couleur.

![](../images/display7.png)

Vous pouvez aussi configurer ici la transparence des widgets de manière globale (qui sera la valeur par défaut, il est possible ensuite de modifier cette valeur widget par widget).

> **Tip**
>
> N’oubliez pas de sauvegarder après toute modification.

Configuration des commandes
===========================

![](../images/administration4.png)

-   **Historique** : voir [ici](https://jeedom.com/doc/documentation/core/fr_FR/doc-core-history.html#_configuration_général_de_l_historique)

-   **Push**

    -   **URL de push globale** : permet de rajouter une URL à appeler en cas de mise à jour d’une commande. Vous pouvez utiliser les tags suivant : \#value\# pour la valeur de la commande, \#cmd\_name\# pour le nom de la commande, \#cmd\_id\# pour l’identifiant unique de la commande, \#humanname\# pour le nom complet de la commande (ex : \#[Salle de bain][Hydrometrie][Humidité]\#)

Configuration des interactions
==============================

![](../images/administration5.png)

Voir [ici](https://jeedom.com/doc/documentation/core/fr_FR/doc-core-interact.html#_configuration_2)

Configuration des logs & messages
=================================

![](../images/administration7.png)

Voir [ici](https://jeedom.com/doc/documentation/core/fr_FR/doc-core-log.html#_configuration)

Configuration LDAP
==================

![](../images/administration8.png)

-   **Activer l’authentification LDAP** : active l’authentification à travers un AD (LDAP)

-   **Hôte** : serveur hébergeant l’AD

-   **Domaine** : domaine de votre AD

-   **Base DN** : base DN de votre AD

-   **Nom d’utilisateur** : nom d’utilisateur pour que Jeedom se connecte à l’AD

-   **Mot de passe** : mot de passe pour que Jeedom se connecte à l’AD

-   **Filtre (optionnel)** : filtre sur l’AD (pour la gestion des groupes par exemple)

-   **Autoriser REMOTE\_USER** : Active le REMOTE\_USER (utilisé en SSO par exemple)

Configuration des équipements
=============================

![](../images/administration9.png)

-   **Nombre d'échecs avant désactivation de l'équipement** : nombre d'échecs de communication avec l'équipement avant desactivation de celui-ci (un message vous préviendra si cela arrive)

-   **Seuils des piles** : permet de gérer les seuils d’alertes globaux sur les piles

Mise à jour et fichiers
=======================

![](../images/administration10.png)

-   Source de mise à jour :

-   Faire une sauvegarde avant la mise à jour

-   Vérifier automatiquement s’il y a des mises à jour

Les dépots
----------

Les dépôts sont des espaces de stockage (et de service) pour pouvoir mettre des backups, récupérer des plugins, récupérer le core de jeedom….

### Market

Dépôt servant à relier Jeedom au market, il est vivement conseillé d’utiliser ce dépôt. Attention toute demande de support pourra être refusée si vous utilisez un autre dépôt que celui-ci.

![](../images/administration17.png)

-   **Adresse** : adresse du Market

-   **Nom d’utilisateur** : votre nom d’utilisateur sur le Market

-   **Mot de passe** : votre mot de passe du Market

### Fichier

Dépôt servant à activer l’envoi de plugins par des fichiers.

![](../images/administration15.png)

### Github

Dépôt servant à relier Jeedom à Github.

![](../images/administration16.png)

-   **Token** : token pour l’accès au dépôt privé

-   **Utilisateur ou organisation du dépôt pour le core Jeedom**

-   **Nom du dépôt pour le core Jeedom**

-   **Branche pour le core Jeedom**

### Samba

Dépôt permettant d’envoyer automatiquement un backup de Jeedom sur un partage Samba (ex NAS Synology).

![](../images/administration18.png)

-   **[Backup] IP** : IP du serveur Samba

-   **[Backup] Utilisateur** : nom d’utilisateur pour la connexion (les connexions anonymes ne sont pas possibles)

-   **[Backup] Mot de passe** : mot de passe de l’utilisateur

-   **[Backup] Partage** : chemin du partage (attention à bien s’arrêter au niveau du partage)

-   **[Backup] Chemin** : chemin dans le partage (à mettre en relatif), celui-ci doit exister

> **Note**
>
> Si le chemin d’accès à votre dossier de sauvegarde samba est : \\\\192.168.0.1\\Sauvegardes\\Domotique\\Jeedom Alors IP = 192.168.0.1 , Partage = //192.168.0.1/Sauvegardes , Chemin = Domotique/Jeedom

> **Important**
>
> Il vous faudra peut-être installer le package smbclient pour que le dépôt fonctionne.

> **Important**
>
> Jeedom doit être le seul à écrire dans ce dossier et il doit être vide par défaut (c’est à dire avant la configuration et l’envoi du premier backup, le dossier ne doit contenir aucun fichier ou dossier).

### URL

![](../images/administration19.png)

-   **URL core Jeedom**

-   **URL version core Jeedom**


