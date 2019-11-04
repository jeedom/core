Das ist eine Dokumentation der API-Methoden. Zuallererst, sind hier die
(JSON RPC 2.0) Spezifizierungen :
<http://www.jsonrpc.org/specification>

Der Zugriff auf die API erfolgt über die URL : *URL\_JEEDOM*/core/api/jeeApi.php

Divers
======

ping
----

Gib Ping zurück, um die Kommunikation mit Jeedom zu testen

version
-------

Gibt die Jeedom Version zurück

datetime
--------

Gibt das Jeedom-Datum in Mikrosekunden zurück

API config
==========

config::byKey
-------------

Gibt einen Konfigurationswert zurück.

Parameter :

-   string key : Schlüssel des Konfigurationswerts, der zurückgegeben werden soll

-   string plugin : (optional), Plugin Konfigurationswert

-   string default : (optional), Rückgabewert, wenn der Schlüssel nicht
    existiert

config::save
------------

Speichert einen Konfigurationswert

Parameter :

-   string value : Wert zum Speichern

-   string key : Schlüssel, des zu speichernden Konfigurationswertes

-   string plugin : Plugin, des zu speichernden 
    Konfigurationswertes

API JSON Event
==============

event::changes
--------------

Gibt eine Liste der Änderungen aus, die als Parameter in datetime übergeben wurde, (muss in Mikrosekunden sein). Sie werden in der Antwort auch das aktuelle Jeedom Datum zurück bekommen (um für die nächste Abfrage wiederverwendet zu werden).

Parameter :

-   int datetime

API JSON Plugin
===============

plugin::listPlugin
------------------

Gibt die Liste aller Plugins zurück

Parameter :

-   int activateOnly = 0 (liefert nur die Liste der aktivierten Plugins)

-   int orderByCaterogy = 0 (liefert die Liste der Plugins nach 
    Kategorie geordnet)

API JSON Objet
==============

jeeObject::all
-----------

Gibt die Liste aller Objekte zurück 

jeeObject::full
------------

Liefert eine Liste aller Objekte, mit allen Geräten für jedes Objekt und alle
Befehle von jedem Gerät, sowie die Zustände von diesem
(für Befehle des Typs Info)

jeeObject::fullById
----------------

Gibt ein Objekt mit allen seinen Geräten und für jedes Gerät alle seine
Befehle sowie deren Zustände zurück (für Befehle des Typs Info)

Parameter :

-   int id

jeeObject::byId
------------

Gibt das angegebenen Objekt zurück

Parameter :

-   int id

jeeObject::fullById
----------------

Gibt ein Objekt, seine Ausrüstung und für jedes Gerät alle seine Befehle
sowie die Zustände dieses Objekts zurück (für Befehle des Typs Info)

jeeObject::save
------------

Gibt das angegebenen Objekt zurück

Parameter :

-   int id (leer, wenn es geschaffen wurde)

-   string name

-   int father\_id = null

-   int isVisible = 0

-   int position

-   array configuration

-   array display

API JSON Summary
================

summary::global
---------------

Gibt eine gesamt Zusammenfassung der Parameter des übergebenen Schlüssels zurück

Parameter :

-   string key : (optional), Schlüssel der gewünschten Zusammenfassung, wenn leer, dann gibt Jeedom
    die Zusammenfassung für alle Schlüssel zurück

summary::byId
-------------

Gibt die Zusammenfassung für das ID-Objekt zurück

Parameter :

-   int id : ID vom Objekt

-   string key : (optional), Schlüssel der gewünschten Zusammenfassung, wenn leer dann Jeedom
    die Zusammenfassung für alle Schlüssel zurück

API JSON EqLogic
================

eqLogic::all
------------

Gibt die Liste aller Geräte zurück

eqLogic::fullById
-----------------

Gibt ein Gerät sowie dessen Befehle und die Zustände zurück
(für Befehle des Typs Info)

Parameter :

-   int id

eqLogic::byId
-------------

Liefert das angegebene Gerät zurück

Parameter :

-   int id

eqLogic::byType
---------------

Gibt alle Geräte zurück, die zum angegebenen Typ (Plugin) gehören

Parameter :

-   string type

eqLogic::byObjectId
-------------------

Gibt alle zum angegebenen Objekt gehörenden Geräte zurück

Parameter :

-   int object\_id

eqLogic::byTypeAndId
--------------------

Giebt eine Tabelle von Geräten gemäß den Parametern zurück. Die Rückgabe erfolgt in Form von Arrays ('eqType1' ⇒array( 'id'⇒…​,'cmds' ⇒
array(…​.)),'eqType2' ⇒array( 'id'⇒…​,'cmds' ⇒ array(…​.))…​.,id1 ⇒
array( 'id'⇒…​,'cmds' ⇒ array(…​.)),id2 ⇒ array( 'id'⇒…​,'cmds' ⇒
array(…​.))..)

Parameter :

-   string\[\] eqType = Tabelle der gewünschten Gerätetypen

-   int\[\] id = benutzerdefinierte Tabelle der gewünschten Geräte-IDs

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
