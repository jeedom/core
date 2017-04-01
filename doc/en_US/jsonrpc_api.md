Here is a documentation (which will change) on API’s methods. First of all, here are the specifications (JSON RPC 2.0) : <http://www.jsonrpc.org/specification>

L’accès à l’API se fait par l’url : URL\_JEEDOM/core/api/jeeApi.php

Various
=======

ping
----

Retourne pong, permet de tester la communication avec Jeedom

version
-------

Retourne la version de Jeedom

datetime
--------

Retourne le datetime de Jeedom en microsecondes

API JSON Event
==============

event::changes
--------------

Retourne la liste des changements depuis le datetime passé en paramètre (doit être en microsecondes). Vous aurez aussi dans la réponse le datetime courant de Jeedom (à réutiliser pour l’interrogation suivante)

Settings :

-   int datetime

API JSON Plugin
===============

plugin::listPlugin
------------------

Retourne la liste de tous les plugins

Settings :

-   int activateOnly = 0 (ne retourne que la liste des plugins activés)

-   int orderByCaterogy = 0 (retourne la liste des plugins triés par catégorie)

API JSON Object
===============

object::all
-----------

Retourne la liste de tous les objets

object::full
------------

Retourne la liste de tous les objets, avec pour chaque objet tous ses équipements et pour chaque équipement toutes ses commandes ainsi que les états de celles-ci (pour les commandes de type info)

object::byId
------------

Retourne l’objet spécifié

Settings:

-   int id

object::fullById
----------------

Retourne un objet, ses équipements et pour chaque équipement toutes ses commandes ainsi que les états de cellse-ci (pour les commandes de type info)

API JSON Summary
================

summary::global
---------------

Retour le résumé global pour la clef passée en paramètre

Settings:

-   string key : (optionnel), clef du résumé voulu, si vide alors Jeedom vous renvoi le résumé pour toute les clefs

summary::byId
-------------

Retourne le résumé pour l’objet id

Settings:

-   int id : id de l’objet

-   string key : (optionnel), clef du résumé voulu, si vide alors Jeedom vous renvoi le résumé pour toute les clefs

API JSON EqLogic
================

eqLogic::all
------------

Retourne la liste de tous les équipements

eqLogic::fullById
-----------------

Retourne un équipement et ses commandes ainsi que les états de celles-ci (pour les commandes de type info)

eqLogic::byId
-------------

Retourne l'équipement spécifié

Settings:

-   int id

eqLogic::byType
---------------

Retourne tous les équipements appartenant au type (plugin) spécifié

Settings:

-   string type

eqLogic::byObjectId
-------------------

Retourne tous les équipements appartenant à l’objet spécifié

Settings:

-   int object\_id

eqLogic::byTypeAndId
--------------------

Renvoi un tableau d'équipement en fonction des paramètres. Le retour sera de la forme array(*eqType1* ⇒array( *id*⇒…,*cmds* ⇒ array(….)),*eqType2* ⇒array( *id*⇒…,*cmds* ⇒ array(….))….,id1 ⇒ array( *id*⇒…,*cmds* ⇒ array(….)),id2 ⇒ array( *id*⇒…,*cmds* ⇒ array(….))..)

Settings:

-   string[] eqType = equipment types wanted

-   int[] id = tableau des ID d'équipements personnalisés voulus

eqLogic::save
-------------

Retourne l'équipement enregistré/créé

Settings:

-   int id (empty if it’s a creation)

-   string eqType\_name (type de l'équipement script, virtuel…)

-   string \$name

-   string \$logicalId = \<nowiki\>''\</nowiki\>

-   int \$object\_id = null

-   int \$eqReal\_id = null;

-   int \$isVisible = 0;

-   int \$isEnable = 0;

-   array \$configuration;

-   int \$timeout;

-   array \$category;

API JSON Cmd
============

cmd::all
--------

Retourne la liste de toutes les commandes

cmd::byId
---------

Retourne la commande spécifiée

Settings:

-   int id

cmd::byEqLogicId
----------------

Retourne toutes les commandes appartenant à l'équipement spécifié

Settings:

-   int eqLogic\_id

cmd::execCmd
------------

Exécute la commande spécifiée

Settings:

-   int id

-   [options] Liste des options de la commande (dépend du type et du sous-type de la commande)

cmd::getStatistique
-------------------

Retourne les statistiques sur la commande (ne marche que sur les commandes de type info et historisées)

Settings:

-   int id

-   string startTime : date de début de calcul des statistiques

-   string endTime : date de fin de calcul des statistiques

cmd::getTendance
----------------

Retourne la tendance sur la commande (ne marche que sur les commandes de type info et historisées)

Settings:

-   int id

-   string startTime : date de début de calcul de la tendance

-   string endTime : date de fin de calcul de la tendance

cmd::getHistory
---------------

Retourne l’historique de la commande (ne marche que sur les commandes de type info et historisées)

Settings:

-   int id

-   string startTime : date de début de l’historique

-   string endTime : date de fin de l’historique

API JSON Scenario
=================

scenario::all
-------------

Retourne la liste de tous les scénarios

scenario::byId
--------------

Retourne le scénario spécifié

Settings:

-   int id

scenario::changeState
---------------------

Change l'état du scénario spécifié.

Settings:

-   int id

-   string state : [run,stop,enable,disable]

API JSON datastore (variable)
=============================

datastore::byTypeLinkIdKey
--------------------------

Récupère la valeur d’une variable stockée dans le datastore

Settings:

-   string type : type de la valeur stockée (pour les scénarios c’est scenario)

-   id linkId : -1 pour le global (valeur pour les scénarios par défaut, ou l’id du scénario)

-   string key : name of the value

datastore::save
---------------

Enregistre la valeur d’une variable dans le datastore

Settings:

-   string type : type de la valeur stockée (pour les scénarios c’est scenario)

-   id linkId : -1 pour le global (valeur pour les scénarios par défaut, ou l’id du scénario)

-   string key : name of the value

-   mixte value : valeur à enregistrer

API JSON Message
================

message::all
------------

Retourne la liste de tous les messages

message::removeAll
------------------

Cancel all messages

API JSON Interaction
====================

interact::tryToReply
--------------------

Essaie de faire correspondre une demande avec une interaction, exécute l’action et répond en conséquence

Settings:

-   query (phrase de la demande)

API JSON System
===============

jeedom::halt
------------

Permet d’arrêter Jeedom

jeedom::reboot
--------------

Permet de redémarrer Jeedom

API JSON plugin
===============

plugin::install
---------------

Installation/Mise à jour d’un plugin donné

Settings:

-   string plugin\_id : nom du plugin (nom logique)

plugin::remove
--------------

Suppression d’un plugin donné

Settings:

-   string plugin\_id : nom du plugin (nom logique)

API JSON update
===============

update::all
-----------

Retour la liste de tous les composants installés, leur version et les informations associées

update::checkUpdate
-------------------

Permet de vérifier les mises à jour

update::update
--------------

Permet de mettre à jour Jeedom et tous les plugins

API JSON Examples
=================

Voici un exemple d’utilisation de l’API. Pour l’exemple ci-dessous j’utilise [cette class php](https://github.com/jeedom/core/blob/stable/core/class/jsonrpcClient.class.php) qui permet de simplifier l’utilisation de l’api.

Récupération de la liste des objets :

    $jsonrpc = new jsonrpcClient('#URL_JEEDOM#/core/api/jeeApi.php', #API_KEY#);
    if($jsonrpc->sendRequest('object::all', array())){
        print_r($jsonrpc->getResult());
    }else{
        echo $jsonrpc->getError();
    }

Exécution d’une commande (avec comme option un titre et un message)

    $jsonrpc = new jsonrpcClient('#URL_JEEDOM#/core/api/jeeApi.php', #API_KEY#);
    if($jsonrpc->sendRequest('cmd::execCmd', array('id' => #cmd_id#, 'options' => array('title' => 'Coucou', 'message' => 'Ca marche')))){
        echo 'OK';
    }else{
        echo $jsonrpc->getError();
    }

L’API est bien sur utilisable avec d’autres langages (simplement un post sur une page) 

