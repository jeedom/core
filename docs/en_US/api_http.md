Jeedom provides developers and users with an API
complete to be able to drive Jeedom from any object
logged.

Two APIs are available: a developer-oriented pilot
JSON RPC 2.0 and another via URL and HTTP request.

This API is very easily used by simple HTTP requests via
URL.

> **Note**
>
> For all this documentation, \ #IP \ _JEEDOM \ # corresponds to your url
> access to Jeedom. This is (unless you are connected to your network
> local) of the internet address you use to access Jeedom
> from the outside.

> **Note**
>
> For all this documentation, \ #API \ _KEY \ # corresponds to your key
> API, specific to your installation. To find it, you have to go to
> the "General" menu → "Configuration" → "General" tab.

Scenario
========

Here is the URL =
[Http: // \ #IP \ _JEEDOM \ # / core / api / jeeApi.php apikey = \ #APIKEY \ # & type = scenario & id = \ #ID \ # & action = \ #ACTION \ #?] (Http: // # # IP_JEEDOM / core / api / jeeApi.php? apikey aPIKEY = # # & type = scenario & id = # ID # & action = ACTION # #)

-   **id**: corresponds to the id of your scenario. The ID is on the
    page of the scenario concerned, in "tools" → "Scenarios", once the
    selected scenario, next to the name of the "General" tab. Other
    way to find it: in "Tools" → "Scenarios", click on
    "Overview".

-   **action**: corresponds to the action you want to apply. The
    available commands are: "start", "stop", "disable" and
    "activate" to respectively start, stop, disable or
    activate the scenario.

-   **tags** \ [optional]: if the action is "start", you can pass
    tags to the scenario (see the documentation on the scenarios) under
    the form tags = foo% 3D1%20tata% 3D2 (note that% 20 corresponds to a
    space and% 3D to =)

Info / Action command
====================

Here is the URL =
[Http: // \ #IP \ _JEEDOM \ # / jeedom / core / api / jeeApi.php apikey = \ #APIKEY \ # & type = cmd & id = \ #ID \ #?] (Http: // # # IP_JEEDOM / jeedom /core/api/jeeApi.php?apikey=#APIKEY#&type=cmd&id=#ID#)

-   **id**: is the id of what you want to drive or which
    you want to receive information

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

Here is the URL =
[Http: // \ #IP \ _JEEDOM \ # / jeedom / core / api / jeeApi.php apikey = \ #APIKEY \ # & type = interact & query = \ #QUERY \ #?] (Http: // # # IP_JEEDOM / jeedom /core/api/jeeApi.php?apikey=#APIKEY#&type=interact&query=#QUERY#)

-   **query**: question to ask to Jeedom

-   **utf8** \ [optional]: tells Jeedom whether to encode query
    in utf8 before trying to answer

-   **emptyReply** \ [optional]: 0 for Jeedom to answer even if he
    did not understand, 1 otherwise

-   **profile** \ [optional]: username of the person
    triggering the interaction

-   **reply \ _cmd** \ [optional]: ID of the command to use for
    respond to the request

Message
=======

Here is the URL =
[Http: // \ #IP \ _JEEDOM \ # / jeedom / core / api / jeeApi.php apikey = \ #APIKEY \ # & type = & category = Message \ #category \ # & message = \ #message \ #?] (Http: //#IP_JEEDOM#/jeedom/core/api/jeeApi.php?apikey=#APIKEY#&type=message&category=#CATEGORY#&message=#MESSAGE#)

-   **category**: category of the message to add to the message center

-   **message**: message in question, be careful to think about encoding
    the message (space becomes% 20, =% 3D ...). You can use a
    tool, [here] (https://meyerweb.com/eric/tools/dencoder/)

Object
=====

Here is the URL =
[Http: // \ #IP \ _JEEDOM \ # / jeedom / core / api / jeeApi.php apikey = \ #APIKEY \ # & type = object?] (Http: // # # IP_JEEDOM / jeedom / core / api / jeeApi .php? apikey aPIKEY = # # & type = object)

Returns in json the list of all Jeedom objects

Equipment
==========

Here is the URL =
[Http: // \ #IP \ _JEEDOM \ # / jeedom / core / api / jeeApi.php apikey = \ #APIKEY \ # & type = & eqLogic object \ _id = \ #OBJECT \ _ID \ #?] (Http: // # IP_JEEDOM # / jeedom / core / api / jeeApi.php? apikey aPIKEY = # # & type = eqLogic & object_id = # # oBJECT_ID)

-   **object \ _id**: ID of the object we want to recover
    amenities

order
========

Here is the URL =
[Http: // \ #IP \ _JEEDOM \ # / jeedom / core / api / jeeApi.php apikey = \ #APIKEY \ # & type = command & eqLogic \ _id = \ #EQLOGIC \ _ID \ #?] (Http: // # IP_JEEDOM # / jeedom / core / api / jeeApi.php? apikey aPIKEY = # # & type = command & eqLogic_id = # # EQLOGIC_ID)

-   **eqLogic \ _id**: ID of the equipment we want to recover
    orders

Full Data
=========

Here is the URL =
[Http: // \ #IP \ _JEEDOM \ # / jeedom / core / api / jeeApi.php apikey = \ #APIKEY \ # & type = fullData?] (Http: // # # IP_JEEDOM / jeedom / core / api / jeeApi .php? apikey aPIKEY = # # & type = fullData)

Returns all objects, devices, commands (and their value if this
are infos) in json

Variable
========

Here is the URL =
[Http: // \ #IP \ _JEEDOM \ # / jeedom / core / api / jeeApi.php apikey = \ #APIKEY \ # & type = & variable name = \ #NAME \ # & value =] (http: // # # IP_JEEDOM /jeedom/core/api/jeeApi.php?apikey=#APIKEY#&type=variable&name=#NAME#&value=)*VALUE*

-   **name**: name of the variable whose value we want (read from
    the value)

-   **value** \ [optional]: if "value" is specified then the variable
    will take this value (writing a value)


