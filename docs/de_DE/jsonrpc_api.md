Voici une documentation sur les méthodes de l'API. Tout d'abord voici
les spécifications (JSON RPC 2.0) :
<http://www.jsonrpc.org/specification>

L'accès à l'API se fait par l'url : *URL\_JEEDOM*/core/api/jeeApi.php

Divers
======

ping
----

Retourne pong, permet de tester la communication avec Jeedom

Version
-------

Retourne la Version de Jeedom

datetime
--------

Retourne le datetime de Jeedom en microsecondes

API config
==========

config::byKey
-------------

Retourne une valeur de configuration.

Paramètres :

-   string key : clef de la valeur de configuration à retourner

-   string Plugin : (optionnel), Plugin de la valeur de configuration

-   string default : (optionnel), valeur à retourner si la clef n'existe
    pas

config::save
------------

Enregistre une valeur de configuration

Paramètres :

-   string value : valeur à enregistrer

-   string key : clef de la valeur de configuration à enregistrer

-   string Plugin : (optionnel), Plugin de la valeur de configuration à
    enregistrer

API JSON Event
==============

event::changes
--------------

Retourne la Listee des changements depuis le datetime passé en paramètre
(doit être en microsecondes). Vous aurez aussi dans la réponse le
datetime courant de Jeedom (à réutiliser pour l'interrogation suivante)

Paramètres :

-   int datetime

API JSON Plugin
===============

Plugin::ListePlugin
------------------

Retourne la Listee de tous les Plugins

Paramètres :

-   int activateOnly = 0 (ne retourne que la Listee des Plugins activés)

-   int orderByCaterogy = 0 (retourne la Listee des Plugins triés
    par catégorie)

API JSON Objet
==============

jeeObject::all
-----------

Retourne la Listee de tous les objets

jeeObject::full
------------

Retourne la Listee de tous les objets, avec pour chaque objet tous ses
équipements et pour chaque équipement toutes ses commandes ainsi que les
états de celles-ci (pour les commandes de type info)

jeeObject::fullById
----------------

Retourne un objet avec tous ses équipements et pour chaque équipement
toutes ses commandes ainsi que les états de celles-ci (pour les
commandes de type info)

Paramètres :

-   int id

jeeObject::byId
------------

Retourne l'objet spécifié

Paramètres:

-   int id

jeeObject::fullById
----------------

Retourne un objet, ses équipements et pour chaque équipement toutes ses
commandes ainsi que les états de cellse-ci (pour les commandes de type
info)

jeeObject::save
------------

Retourne l'objet spécifié

Paramètres:

-   int id (vide si c'est une création)

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

Retour le résumé global pour la clef passée en paramètre

Paramètres:

-   string key : (optionnel), clef du résumé voulu, si vide alors Jeedom
    vous renvoi le résumé pour toute les clefs

summary::byId
-------------

Retourne le résumé pour l'objet id

Paramètres:

-   int id : id de l'objet

-   string key : (optionnel), clef du résumé voulu, si vide alors Jeedom
    vous renvoi le résumé pour toute les clefs

API JSON EqLogic
================

eqLogic::all
------------

Retourne la Listee de tous les équipements

eqLogic::fullById
-----------------

Retourne un équipement et ses commandes ainsi que les états de celles-ci
(pour les commandes de type info)

Paramètres:

-   int id

eqLogic::byId
-------------

Retourne l'équipement spécifié

Paramètres:

-   int id

eqLogic::byType
---------------

Retourne tous les équipements appartenant au type (Plugin) spécifié

Paramètres:

-   string type

eqLogic::byObjectId
-------------------

Retourne tous les équipements appartenant à l'objet spécifié

Paramètres:

-   int object\_id

eqLogic::byTypeAndId
--------------------

Renvoi un tableau d'équipement en fonction des paramètres. Le retour
sera de la forme array('eqType1' ⇒array( 'id'⇒…​,'cmds' ⇒
array(…​.)),'eqType2' ⇒array( 'id'⇒…​,'cmds' ⇒ array(…​.))…​.,id1 ⇒
array( 'id'⇒…​,'cmds' ⇒ array(…​.)),id2 ⇒ array( 'id'⇒…​,'cmds' ⇒
array(…​.))..)

Paramètres:

-   string\[\] eqType = tableau des types d'équipements voulus

-   int\[\] id = tableau des ID d'équipements personnalisés voulus

eqLogic::save
-------------

Retourne l'équipement enregistré/créé

Paramètres:

-   int id (vide si c'est une création)

-   string eqType\_name (type de l'équipement script, virtuel…​)

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

Retourne la Listee de toutes les commandes

cmd::byId
---------

Retourne la commande spécifiée

Paramètres:

-   int id

cmd::byEqLogicId
----------------

Retourne toutes les commandes appartenant à l'équipement spécifié

Paramètres:

-   int eqLogic\_id

cmd::execCmd
------------

Exécute la commande spécifiée

Paramètres:

-   int id : id d'une commande ou tableau d'id si vous voulez executer
    plusieurs commande d'un coup

-   \[options\] Liste des options de la commande (dépend du type et du
    sous-type de la commande)

cmd::getStatistique
-------------------

Retourne les statistiques sur la commande (ne marche que sur les
commandes de type info et historisées)

Paramètres:

-   int id

-   String-StartTime : date de début de calcul des statistiques

-   string endTime : date de fin de calcul des statistiques

cmd::getTendance
----------------

Retourne la tendance sur la commande (ne marche que sur les commandes de
type info et historisées)

Paramètres:

-   int id

-   String-StartTime : date de début de calcul de la tendance

-   string endTime : date de fin de calcul de la tendance

cmd::getHistory
---------------

Retourne l'historique de la commande (ne marche que sur les commandes de
type info et historisées)

Paramètres:

-   int id

-   String-StartTime : date de début de l'historique

-   string endTime : date de fin de l'historique

cmd::save
---------

Retourne l'objet spécifié

Paramètres:

-   int id (vide si c'est une création)

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

Retourne la Listee de tous les scénarios

scenario::byId
--------------

Retourne le scénario spécifié

Paramètres:

-   int id

scenario::export
----------------

Retourne l'export du scénario ainsi que le nom humain du scénario

Paramètres:

-   int id

scenario::import
----------------

Permet d'importer un scénario.

Paramètres:

-   int id : ID des Szenarios, in das importiert werden soll (leer, wenn erstellt)

-   Zeichenfolge humanName : menschlicher Name des Szenarios (leer bei Erstellung)

-   Array-Import : Szenario (aus dem Feld Exportszenario::Export)

scenario::Change
---------------------

Ändert den Status des angegebenen Szenarios.

Paramètres:

-   int id

-   Zeichenfolgenstatus: \ [Run, Stop, aktivieren, deaktivieren \]

JSON-Protokoll-API
============

log::get
--------

Ermöglicht das Wiederherstellen eines Protokolls

Paramètres:

-   Zeichenfolgenprotokoll : Name des wiederherzustellenden Protokolls

-   String-Start : Zeilennummer, auf der mit dem Lesen begonnen werden soll

-   Zeichenfolge nbLine : Anzahl der wiederherzustellenden Zeilen

log::hinzufügen
--------

Ermöglicht das Schreiben in ein Protokoll

Paramètres:

-   Zeichenfolgenprotokoll : Name des wiederherzustellenden Protokolls

-   string type : Protokolltyp (Debug, Info, Warnung, Fehler)

-   Zeichenfolge Nachricht : SMS zu schreiben

-   Zeichenfolge logische ID : logische ID der generierten Nachricht


log::Liste
---------

Holen Sie sich die Jeedom-ProtokollListee

Paramètres:

-   String-Filter : (optional) Filter nach dem Namen der wiederherzustellenden Protokolle

log::leer
----------

Leeren Sie ein Protokoll

Paramètres:

-   Zeichenfolgenprotokoll : Name des zu leeren Protokolls

log::Entfernen
-----------

Ermöglicht das Löschen eines Protokolls

Paramètres:

-   Zeichenfolgenprotokoll : Protokollname zum Löschen

JSON-Datenspeicher-API (Variable)
=============================

Datenspeicher::byTypeLinkIdKey
--------------------------

Ruft den Wert einer im Datenspeicher gespeicherten Variablen ab

Paramètres:

-   string type : Art des gespeicherten Werts (für Szenarien
    es ist Szenario)

-   id linkId : -1 für das globale (Wert für die Standardszenarien,
    oder die Szenario-ID)

-   string key : Wertname

Datenspeicher::save
---------------

Speichert den Wert einer Variablen im Datenspeicher

Paramètres:

-   string type : Art des gespeicherten Werts (für Szenarien
    es ist Szenario)

-   id linkId : -1 für das globale (Wert für die Standardszenarien,
    oder die Szenario-ID)

-   string key : Wertname

-   gemischter Wert : valeur à enregistrer

JSON-Nachrichten-API
================

Nachricht::all
------------

Gibt die Liste aller Nachrichten zurück

Nachricht::hinzufügen
--------

Ermöglicht das Schreiben in ein Protokoll

Paramètres:

-   string type : Protokolltyp (Debug, Info, Warnung, Fehler)

-   Zeichenfolge Nachricht : Nachricht

-   String-Aktion : Aktion

-   Zeichenfolge logische ID : logicalId

Nachricht::EntfernenAll
------------------

Löschen Sie alle Nachrichten

JSON-Interaktions-API
====================

interact::tryToReply
--------------------

Versuchen Sie, eine Anforderung mit einer Interaktion abzugleichen, und führen Sie sie aus
Aktion und reagiert entsprechend

Paramètres:

-   Abfrage (Anforderungsphrase)

-   int reply \ _cmd = NULL : ID des Befehls, mit dem geantwortet werden soll,
    Wenn nicht angegeben, sendet Jeedom Ihnen die Antwort im JSON

interactQuery::all
------------------

Gibt die vollständige Liste aller Interaktionen zurück

JSON-System-API
===============

jeedom::halt
------------

Stoppen Sie Jeedom

jeedom::rebooten
--------------

Starten Sie Jeedom neu

jeedom::ISOK
------------

Lässt Sie wissen, ob der globale Zustand von Jeedom in Ordnung ist

jeedom::Aktualisierung
--------------

Starten wir ein Jeedom-Update

jeedom::Sicherungskopie
--------------

Ermöglicht das Starten eines Backups von Jeedom

jeedom::getUsbMapping
---------------------

Liste der USB-Anschlüsse und Namen der daran angeschlossenen USB-Sticks

JSON-Plugin-API
===============

Plugin::installieren
---------------

Installation / Update eines bestimmten Plugins

Paramètres:

-   int Plugin \ _id (optional) : Plugin ID
-   Zeichenfolge logische ID (optional) : Plugin Name (logischer Name)

Plugin::Entfernen
--------------

Löschen eines bestimmten Plugins

Paramètres:

-   int Plugin \ _id (optional) : Plugin ID
-   Zeichenfolge logische ID (optional) : Plugin Name (logischer Name)

Plugin::dependancyInfo
----------------------

Gibt Informationen zum Status von Plugin-Abhängigkeiten zurück

Paramètres:

-   int Plugin \ _id (optional) : Plugin ID
-   Zeichenfolge logische ID (optional) : Plugin Name (logischer Name)

Plugin::dependancyInstall
-------------------------

Erzwingen Sie die Installation von Plugin-Abhängigkeiten

Paramètres:

-   int Plugin \ _id (optional) : Plugin ID
-   Zeichenfolge logische ID (optional) : Plugin Name (logischer Name)

Plugin::deamonInfo
------------------

Gibt Informationen zum Status des Plugin-Daemons zurück

Paramètres:

-   int Plugin \ _id (optional) : Plugin ID
-   Zeichenfolge logische ID (optional) : Plugin Name (logischer Name)

Plugin::deamonStart
-------------------

Zwinge den Dämon zu starten

Paramètres:

-   int Plugin \ _id (optional) : Plugin ID
-   Zeichenfolge logische ID (optional) : Plugin Name (logischer Name)

Plugin::deamonStop
------------------

Dämonenstopp erzwingen

Paramètres:

-   int Plugin \ _id (optional) : Plugin ID
-   Zeichenfolge logische ID (optional) : Plugin Name (logischer Name)

Plugin::deamonChangeAutoMode
----------------------------

Ändern Sie den Verwaltungsmodus des Dämons

Paramètres:

-   int Plugin \ _id (optional) : Plugin ID
-   Zeichenfolge logische ID (optional) : Plugin Name (logischer Name)
-   int mode : 1 pour automatique, 0 pour manuel

API JSON Aktualisierung
===============

Aktualisierung::all
-----------

Retour la Listee de tous les composants installierenés, leur Version et les
informations associées

Aktualisierung::checkUpdate
-------------------

Permet de vérifier les mises à jour

Aktualisierung::Aktualisierung
--------------

Permet de mettre à jour Jeedom et tous les Plugins

Aktualisierung::doUpdate
--------------

Paramètres:

-   int Plugin \ _id (optional) : Plugin ID
-   Zeichenfolge logische ID (optional) : Plugin Name (logischer Name)

API JSON network
================

network::restartDns
-------------------

Force le (re)démarrage du DNS Jeedom

network::stopDns
----------------

Force l'arret du DNS Jeedom

network::dnsRun
---------------

Renvoi le status du DNS Jeedom

API JSON Exemples
=================

Voici un exemple d'utilisation de l'API. Pour l'exemple ci-dessous
j'utilise [cette class
php](https://github.com/jeedom/core/blob/stable/core/class/jsonrpcClient.class.php)
qui permet de simplifier l'utilisation de l'api.

Récupération de la Listee des objets :

``` {.php}
$jsonrpc = new jsonrpcClient('#URL_JEEDOM#/core/api/jeeApi.php', #API_KEY#);
if($jsonrpc->sendRequest('jeeObject::all', array())){
    print_r($jsonrpc->getResult());
}else{
    echo $jsonrpc->getError();
}
```

Exécution d'une commande (avec comme option un titre et un Nachricht)

``` {.php}
$jsonrpc = new jsonrpcClient('#URL_JEEDOM#/core/api/jeeApi.php', #API_KEY#);
if($jsonrpc->sendRequest('cmd::execCmd', array('id' => #cmd_id#, 'options' => array('title' => 'Coucou', 'Nachricht' => 'Ca marche')))){
    echo 'OK';
}else{
    echo $jsonrpc->getError();
}
```

L'API est bien sur utilisable avec d'autres langages (simplement un post
sur une page) 
