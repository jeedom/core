Here is a documentation on API methods. First of all here
the specifications (JSON RPC 2.0):
<Http://www.jsonrpc.org/specification>

Access to the API is via the URL: * URL \ _JEEDOM * / core / api / jeeApi.php

Divers
======

ping
----

Return pong, to test the communication with Jeedom

version
-------

Returns the version of Jeedom

datetime
--------

Returns the Jeedom datetime in microseconds

API config
==========

config::byKey
-------------

Returns a configuration value.

Settings :

-   string key: key of the configuration value to return

-   string plugin: (optional), configuration value plugin

-   string default: (optional), value to return if the key does not exist
    not

config::save
------------

Saves a configuration value

Settings :

-   string value: value to save

-   string key: key of the configuration value to be saved

-   string plugin: (optional), plugin of the configuration value to
    record

API JSON Event
==============

event::changes
--------------

Returns the list of changes since the datetime passed as parameter
(must be in microseconds) You will also have in the answer the
Jeedom's current datetime (to reuse for next query)

Settings :

-   int datetime

API JSON Plugin
===============

plugin::listPlugin
------------------

Returns the list of all plugins

Settings :

-   int activateOnly = 0 (only returns the list of activated plugins)

-   int orderByCaterogy = 0 (returns the list of sorted plugins
    by category)

API JSON Objet
==============

jeeObject::all
-----------

Returns the list of all objects

jeeObject::full
------------

Returns the list of all objects, with for each object all its
equipment and for each equipment all its controls as well as the
states of these (for info commands)

jeeObject::fullById
----------------

Returns an object with all its equipment and for each device
all its orders as well as the states thereof (for
info type commands)

Settings :

-   int id

jeeObject::byId
------------

Returns the specified object

Settings:

-   int id

jeeObject::fullById
----------------

Returns an object, its equipment and for each equipment all its
orders and cell status (for type orders).
info)

jeeObject::save
------------

Returns the specified object

Settings:

-   int id (empty if it's a creation)

-   string name

-   int father \ _id = null

-   int isVisible = 0

-   int position

-   array configuration

-   display array

API JSON Summary
================

summary::global
---------------

Return the global summary for the key passed in parameter

Settings:

-   string key: (optional), key of the desired summary, if empty then Jeedom
    you return the summary for all the keys

summary::byId
-------------

Return the summary for the id object

Settings:

-   int id: id of the object

-   string key: (optional), key of the desired summary, if empty then Jeedom
    you return the summary for all the keys

API JSON EqLogic
================

eqLogic::all
------------

Returns a list of all equipment

eqLogic::fullById
-----------------

Returns a device and its commands as well as the states of these
(for info orders)

Paramètres:

-   int id

eqLogic::byId
-------------

Retourne l’équipement spécifié

Paramètres:

-   int id

eqLogic::byType
---------------

Retourne tous les équipements appartenant au type (plugin) spécifié

Paramètres:

-   string type

eqLogic::byObjectId
-------------------

Retourne tous les équipements appartenant à l’objet spécifié

Paramètres:

-   int object\_id

eqLogic::byTypeAndId
--------------------

Renvoi un tableau d’équipement en fonction des paramètres. Le retour
sera de la forme array('eqType1' ⇒array( 'id'⇒…​,'cmds' ⇒
array(…​.)),'eqType2' ⇒array( 'id'⇒…​,'cmds' ⇒ array(…​.))…​.,id1 ⇒
array( 'id'⇒…​,'cmds' ⇒ array(…​.)),id2 ⇒ array( 'id'⇒…​,'cmds' ⇒
array(…​.))..)

Paramètres:

-   string\[\] eqType = tableau des types d’équipements voulus

-   int\[\] id = tableau des ID d’équipements personnalisés voulus

eqLogic::save
-------------

Retourne l’équipement enregistré/créé

Paramètres:

-   int id (vide si c’est une création)

-   string eqType\_name (type de l’équipement script, virtuel…​)

-   string name

-   string logicalId = ''

-   int object\_id = null

-   int eqReal\_id = null

-   int isVisible = 0

-   int isEnable = 0

-   array configuration

-   int timeout

-   array category

API JSON Cmd
============

cmd::all
--------

Retourne la liste de toutes les commandes

cmd::byId
---------

Retourne la commande spécifiée

Paramètres:

-   int id

cmd::byEqLogicId
----------------

Retourne toutes les commandes appartenant à l’équipement spécifié

Paramètres:

-   int eqLogic\_id

cmd::execCmd
------------

Exécute la commande spécifiée

Paramètres:

-   int id : id d’une commande ou tableau d’id si vous voulez executer
    plusieurs commande d’un coup

-   \[options\] Liste des options de la commande (dépend du type et du
    sous-type de la commande)

cmd::getStatistique
-------------------

Retourne les statistiques sur la commande (ne marche que sur les
commandes de type info et historisées)

Paramètres:

-   int id

-   string startTime : date de début de calcul des statistiques

-   string endTime : date de fin de calcul des statistiques

cmd::getTendance
----------------

Retourne la tendance sur la commande (ne marche que sur les commandes de
type info et historisées)

Paramètres:

-   int id

-   string startTime : date de début de calcul de la tendance

-   string endTime : date de fin de calcul de la tendance

cmd::getHistory
---------------

Retourne l’historique de la commande (ne marche que sur les commandes de
type info et historisées)

Paramètres:

-   int id

-   string startTime : date de début de l’historique

-   string endTime : date de fin de l’historique

cmd::save
---------

Retourne l’objet spécifié

Paramètres:

-   int id (vide si c’est une création)

-   string name

-   string logicalId

-   string eqType

-   string order

-   string type

-   string subType

-   int eqLogic\_id

-   int isHistorized = 0

-   string unite = ''

-   array configuration

-   array template

-   array display

-   array html

-   int value = null

-   int isVisible = 1

-   array alert

cmd::event
-------------------

Permet d'envoyer une valeur à une commande

Paramètres:

-   int id

-   string value : valeur

-   string datetime : (optionnel) datetime de la valeur

API JSON Scenario
=================

scenario::all
-------------

Retourne la liste de tous les scénarios

scenario::byId
--------------

Retourne le scénario spécifié

Paramètres:

-   int id

scenario::export
----------------

Retourne l’export du scénario ainsi que le nom humain du scénario

Paramètres:

-   int id

scenario::import
----------------

Permet d’importer un scénario.

Paramètres:

-   int id : id du scénario dans lequel importer (vide si création)

-   string humanName : nom humain du scénario (vide si création)

-   array import : scénario (issue du champs export de scenario::export)

scenario::changeState
---------------------

Change l’état du scénario spécifié.

Paramètres:

-   int id

-   string state : \[run,stop,enable,disable\]

API JSON Log
============

log::get
--------

Permet de récuperer un log

Paramètres:

-   string log : nom du log à recuperer

-   string start : numéro de ligne sur laquelle commencer la lecture

-   string nbLine : nombre de ligne à recuperer

log::add
--------

Permet d'écrire dans un log

Paramètres:

-   string log : nom du log à recuperer

-   string type : type de log (debug, info, warning, error)

-   string message : message text à écrire

-   string logicalId : logicalId du message généré


log::list
---------

Permet de récuperer la list des logs de Jeedom

Paramètres:

-   string filtre : (optionnel) filtre sur le nom des logs à recuperer

log::empty
----------

Permet de vider un log

Paramètres:

-   string log : nom du log à vider

log::remove
-----------

Permet de supprimer un log

Paramètres:

-   string log : nom du log a supprimer

API JSON datastore (variable)
=============================

datastore::byTypeLinkIdKey
--------------------------

Récupère la valeur d’une variable stockée dans le datastore

Paramètres:

-   string type : type de la valeur stockée (pour les scénarios
    c’est scenario)

-   id linkId : -1 pour le global (valeur pour les scénarios par défaut,
    ou l’id du scénario)

-   string key : nom de la valeur

datastore::save
---------------

Enregistre la valeur d’une variable dans le datastore

Paramètres:

-   string type : type de la valeur stockée (pour les scénarios
    c’est scenario)

-   id linkId : -1 pour le global (valeur pour les scénarios par défaut,
    ou l’id du scénario)

-   string key : nom de la valeur

-   mixte value : valeur à enregistrer

API JSON Message
================

message::all
------------

Retourne la liste de tous les messages

message::add
--------

Permet d'écrire dans un log

Paramètres:

-   string type : type de log (debug, info, warning, error)

-   string message : message

-   string action : action

-   string logicalId : logicalId

message::removeAll
------------------

Supprime tous les messages

API JSON Interaction
====================

interact::tryToReply
--------------------

Essaie de faire correspondre une demande avec une interaction, exécute
l’action et répond en conséquence

Paramètres:

-   query (phrase de la demande)

-   int reply\_cmd = NULL : ID de la commande à utiliser pour répondre,
    si non préciser alors Jeedom vous renvoi la réponse dans le json

interactQuery::all
------------------

Renvoi la liste complete de toute les interactions

API JSON System
===============

jeedom::halt
------------

Permet d’arrêter Jeedom

jeedom::reboot
--------------

Permet de redémarrer Jeedom

jeedom::isOk
------------

Permet de savoir si l’état global de Jeedom est OK

jeedom::update
--------------

Permet de lancer un update de Jeedom

jeedom::backup
--------------

Permet de lancer un backup de Jeedom

jeedom::getUsbMapping
---------------------

Liste des ports USB et des noms de clef USB branché dessus

API JSON plugin
===============

plugin::install
---------------

Installation/Mise à jour d’un plugin donné

Paramètres:

-   int plugin\_id (optionnel) : id du plugin
-   string logicalId (optionnel) : nom du plugin (nom logique)

plugin::remove
--------------

Suppression d’un plugin donné

Paramètres:

-   int plugin\_id (optionnel) : id du plugin
-   string logicalId (optionnel) : nom du plugin (nom logique)

plugin::dependancyInfo
----------------------

Renvoi les informations sur le status des dépendances du plugins

Paramètres:

-   int plugin\_id (optionnel) : id du plugin
-   string logicalId (optionnel) : nom du plugin (nom logique)

plugin::dependancyInstall
-------------------------

Force l’installation des dépendances du plugin

Paramètres:

-   int plugin\_id (optionnel) : id du plugin
-   string logicalId (optionnel) : nom du plugin (nom logique)

plugin::deamonInfo
------------------

Renvoi les informations sur le status du démon du plugin

Paramètres:

-   int plugin\_id (optionnel) : id du plugin
-   string logicalId (optionnel) : nom du plugin (nom logique)

plugin::deamonStart
-------------------

Force le démarrage du démon

Paramètres:

-   int plugin\_id (optionnel) : id du plugin
-   string logicalId (optionnel) : nom du plugin (nom logique)

plugin::deamonStop
------------------

Force l’arret du démon

Paramètres:

-   int plugin\_id (optionnel) : id du plugin
-   string logicalId (optionnel) : nom du plugin (nom logique)

plugin::deamonChangeAutoMode
----------------------------

Change le mode de gestion du démon

Paramètres:

-   int plugin\_id (optionnel) : id du plugin
-   string logicalId (optionnel) : nom du plugin (nom logique)
-   int mode : 1 pour automatique, 0 pour manuel

API JSON update
===============

update::all
-----------

Retour la liste de tous les composants installés, leur version et les
informations associées

update::checkUpdate
-------------------

Permet de vérifier les mises à jour

update::update
--------------

Permet de mettre à jour Jeedom et tous les plugins

update::doUpdate
--------------

Paramètres:

-   int plugin\_id (optionnel) : id du plugin
-   string logicalId (optionnel) : nom du plugin (nom logique)

API JSON network
================

network::restartDns
-------------------

Force le (re)démarrage du DNS Jeedom

network::stopDns
----------------

Force l’arret du DNS Jeedom

network::dnsRun
---------------

Renvoi le status du DNS Jeedom

API JSON Exemples
=================

Voici un exemple d’utilisation de l’API. Pour l’exemple ci-dessous
j’utilise [cette class
php](https://github.com/jeedom/core/blob/stable/core/class/jsonrpcClient.class.php)
qui permet de simplifier l’utilisation de l’api.

Récupération de la liste des objets :

``` {.php}
$jsonrpc = new jsonrpcClient('#URL_JEEDOM#/core/api/jeeApi.php', #API_KEY#);
if($jsonrpc->sendRequest('jeeObject::all', array())){
    print_r($jsonrpc->getResult());
}else{
    echo $jsonrpc->getError();
}
```

Exécution d’une commande (avec comme option un titre et un message)

``` {.php}
$jsonrpc = new jsonrpcClient('#URL_JEEDOM#/core/api/jeeApi.php', #API_KEY#);
if($jsonrpc->sendRequest('cmd::execCmd', array('id' => #cmd_id#, 'options' => array('title' => 'Coucou', 'message' => 'Ca marche')))){
    echo 'OK';
}else{
    echo $jsonrpc->getError();
}
```

L’API est bien sur utilisable avec d’autres langages (simplement un post
sur une page) 
