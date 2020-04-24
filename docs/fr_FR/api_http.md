Jeedom met à disposition des développeurs et des utilisateurs une API
complète afin de pouvoir piloter Jeedom depuis n’importe quel objet
connecté.

Deux API sont disponibles : une orientée développeur qui se pilote en
JSON RPC 2.0 et une autre via URL et requête HTTP.

Cette API s’utilise très facilement par de simples requêtes HTTP via
URL.

> **Note**
>
> Pour toute cette documentation, \#IP\_JEEDOM\# correspond à votre url
> d’accès à Jeedom. Il s’agit (sauf si vous êtes connecté à votre réseau
> local) de l’adresse internet que vous utilisez pour accéder à Jeedom
> depuis l’extérieur.

> **Note**
>
> Pour toute cette documentation, \#API\_KEY\# correspond à votre clé
> API, propre à votre installation. Pour la trouver, il faut aller dans
> le menu "Général" → "Configuration" → onglet "Général".

Scénario 
========

Voici l’URL =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=scenario&id=\#ID\#&action=\#ACTION\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=scenario&id=#ID#&action=#ACTION#)

-   **id** : correspond à l’id de votre scénario. L’ID se trouve sur la
    page du scénario concerné, dans "outils" → "Scénarios", une fois le
    scénario sélectionné, à côté du nom de l’onglet "Général". Autre
    moyen de le retrouver : dans "Outils" → "Scénarios", cliquez sur
    "Vue d’ensemble".

-   **action** : correspond à l’action que vous voulez appliquer. Les
    commandes disponibles sont : "start", "stop", "désactiver" et
    "activer" pour respectivement démarrer, arrêter, désactiver ou
    activer le scénario.

-   **tags** \[optionnel\] : si l’action est "start", vous pouvez passer
    des tags au scénario (voir la documentation sur les scénarii) sous
    la forme tags=toto%3D1%20tata%3D2 (à noter que %20 correspond à un
    espace et %3D à = )

Info/Action commande 
====================

Voici l’URL =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=cmd&id=\#ID\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=cmd&id=#ID#)

-   **id** : correspond à l’id de ce que vous voulez piloter ou duquel
    vous souhaitez recevoir des informations

Le plus simple pour avoir cette URL est d’aller sur la page Outils →
Résumé domotique, de chercher la commande puis d’ouvrir sa configuration
avancée (l’icône "engrenage") et là, vous allez voir une URL qui contient
déjà tout ce qu’il faut en fonction du type et du sous-type de la
commande.

> **Note**
>
> Il est possible pour le champs \#ID\# de passer plusieurs commandes
> d’un coup. Pour cela, il faut passer un tableau en json (ex
> %5B12,58,23%5D, à noter que \[ et \] doivent être encodés d’où les %5B
> et %5D). Le retour de Jeedom sera un json

> **Note**
>
> Les paramètres doivent être encodés pour les url, Vous pouvez utiliser
> un outil, [ici](https://meyerweb.com/eric/tools/dencoder/)

Interaction 
===========

Voici l’URL =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=interact&query=\#QUERY\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=interact&query=#QUERY#)

-   **query** : question à poser à Jeedom

-   **utf8** \[optionnel\] : indique à Jeedom si il faut encoder query
    en utf8 avant de chercher à répondre

-   **emptyReply** \[optionnel\] : 0 pour que Jeedom réponde même si il
    n’a pas compris, 1 sinon

-   **profile** \[optionnel\] : nom d’utilisateur de la personne
    déclenchant l’interaction

-   **reply\_cmd** \[optionnel\] : ID de la commande à utiliser pour
    répondre à la demande

Message 
=======

Voici l’URL =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=message&category=\#CATEGORY\#&message=\#MESSAGE\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=message&category=#CATEGORY#&message=#MESSAGE#)

-   **category** : catégorie du message à ajouter au centre de message

-   **message** : message en question, attention à bien penser à encoder
    le message (espace devient %20, = %3D…​). Vous pouvez utiliser un
    outil, [ici](https://meyerweb.com/eric/tools/dencoder/)

Objet 
=====

Voici l’URL =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=object](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=object)

Renvoie en json la liste de tous les objets de Jeedom

Equipement 
==========

Voici l’URL =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=eqLogic&object\_id=\#OBJECT\_ID\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=eqLogic&object_id=#OBJECT_ID#)

-   **object\_id** : ID de l’objet dont on veut récupérer les
    équipements

Commande 
========

Voici l’URL =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=command&eqLogic\_id=\#EQLOGIC\_ID\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=command&eqLogic_id=#EQLOGIC_ID#)

-   **eqLogic\_id** : ID de l’équipement dont on veut récupérer les
    commandes

Full Data 
=========

Voici l’URL =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=fullData](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=fullData)

Renvoie tous les objets, équipements, commandes (et leur valeur si ce
sont des infos) en json

Variable 
========

Voici l’URL =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=variable&name=\#NAME\#&value=](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=variable&name=#NAME#&value=)*VALUE*

-   **name** : nom de la variable dont on veut la valeur (lecture de
    la valeur)

-   **value** \[optionnel\] : si "value" est précisé alors la variable
    prendra cette valeur (écriture d’une valeur)


