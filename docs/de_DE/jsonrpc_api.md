Hier finden Sie eine Dokumentation zu API-Methoden. Zuerst ist hier
die Spezifikationen (JSON RPC 2.0) :
<http://www.jsonrpc.org/specification>

Der Zugriff auf die API erfolgt über die URL : *URL \ _JEEDOM * / core / api / jeeApi.php

verschiedene
======

Klingeln
----

Pong zurückgeben, Kommunikation mit Jeedom testen

Version
-------

Gibt die Version von Jeedom zurück

Datetime
--------

Gibt die Jeedom-Datumszeit in Mikrosekunden zurück

Konfigurations-API
==========

Config::byKey
-------------

Gibt einen Konfigurationswert zurück.

Einstellungen :

-   String-Schlüssel : Konfigurationswertschlüssel, der zurückgegeben werden soll

-   String Plugin : (optional), Konfigurationswert-Plugin

-   Zeichenfolge Standard : (optional), Wert, der zurückgegeben werden soll, wenn der Schlüssel nicht vorhanden ist
    nicht

Config::speichern
------------

Speichert einen Konfigurationswert

Einstellungen :

-   Zeichenfolgenwert : Wert aufzuzeichnen

-   String-Schlüssel : Konfigurationswertschlüssel zum Speichern

-   String Plugin : (optional), Plugin des Konfigurationswertes zu
    Rekord

JSON-Ereignis-API
==============

Ereignis::Austausch
--------------

Gibt die Liste der Änderungen seit der im Parameter übergebenen Datum / Uhrzeit zurück
(muss in Mikrosekunden sein). Sie haben auch in der Antwort die
Jeedom&#39;s aktuelle Datumszeit (zur Wiederverwendung für die nächste Abfrage)

Einstellungen :

-   int Datetime

API JSON Plugin
===============

Plugin::ListePlugin
------------------

Retourne la Listee de tous les Plugins

Einstellungen :

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

Einstellungen :

-   int id

jeeObject::byId
------------

Retourne l'objet spécifié

Einstellungen:

-   int id

jeeObject::fullById
----------------

Retourne un objet, ses équipements et pour chaque équipement toutes ses
commandes ainsi que les états de cellse-ci (pour les commandes de type
info)

jeeObject::speichern
------------

Retourne l'objet spécifié

Einstellungen:

-   int id (vide si c'est une création)

-   string name

-   int father\_id = null

-   int isVisible = 0

-   int position

-   array Configuration

-   array display

API JSON Summary
================

summary::global
---------------

Retour le résumé global pour la clef nichtsée en paramètre

Einstellungen:

-   String-Schlüssel : (optionnel), clef du résumé voulu, si vide alors Jeedom
    vous renvoi le résumé pour toute les clefs

summary::byId
-------------

Retourne le résumé pour l'objet id

Einstellungen:

-   int id : id de l'objet

-   String-Schlüssel : (optionnel), clef du résumé voulu, si vide alors Jeedom
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

Einstellungen:

-   int id

eqLogic::byId
-------------

Retourne l'équipement spécifié

Einstellungen:

-   int id

eqLogic::byType
---------------

Retourne tous les équipements appartenant au type (Plugin) spécifié

Einstellungen:

-   string type

eqLogic::byObjectId
-------------------

Retourne tous les équipements appartenant à l'objet spécifié

Einstellungen:

-   int object\_id

eqLogic::byTypeAndId
--------------------

Renvoi un tableau d'équipement en fonction des paramètres. Le retour
sera de la forme array('eqType1' ⇒array( 'id'⇒…​,'cmds' ⇒
array(…​.)),'eqType2' ⇒array( 'id'⇒…​,'cmds' ⇒ array(…​.))…​.,id1 ⇒
array( 'id'⇒…​,'cmds' ⇒ array(…​.)),id2 ⇒ array( 'id'⇒…​,'cmds' ⇒
array(…​.))..)

Einstellungen:

-   string\[\] eqType = tableau des types d'équipements voulus

-   int\[\] id = tableau des ID d'équipements personnalisés voulus

eqLogic::speichern
-------------

Retourne l'équipement enregistré/créé

Einstellungen:

-   int id (vide si c'est une création)

-   string eqType\_name (type de l'équipement script, virtuel…​)

-   string name

-   string logicalId = ''

-   int object\_id = null

-   int eqReal\_id = null

-   int isVisible = 0

-   int isEnable = 0

-   array Configuration

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

Einstellungen:

-   int id

cmd::byEqLogicId
----------------

Retourne toutes les commandes appartenant à l'équipement spécifié

Einstellungen:

-   int eqLogic\_id

cmd::execCmd
------------

Exécute la commande spécifiée

Einstellungen:

-   int id : id d'une commande ou tableau d'id si vous voulez executer
    plusieurs commande d'un coup

-   \[options\] Liste des options de la commande (dépend du type et du
    sous-type de la commande)

cmd::getStatistique
-------------------

Retourne les statistiques sur la commande (ne marche que sur les
commandes de type info et historisées)

Einstellungen:

-   int id

-   String-StartTime : date de début de calcul des statistiques

-   string endTime : date de fin de calcul des statistiques

cmd::getTendance
----------------

Retourne la tendance sur la commande (ne marche que sur les commandes de
type info et historisées)

Einstellungen:

-   int id

-   String-StartTime : date de début de calcul de la tendance

-   string endTime : date de fin de calcul de la tendance

cmd::getHistory
---------------

Retourne l'historique de la commande (ne marche que sur les commandes de
type info et historisées)

Einstellungen:

-   int id

-   String-StartTime : date de début de l'historique

-   string endTime : date de fin de l'historique

cmd::speichern
---------

Retourne l'objet spécifié

Einstellungen:

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

-   array Configuration

-   array template

-   array display

-   array html

-   int value = null

-   int isVisible = 1

-   array alert

cmd::Ereignis
-------------------

Permet d'envoyer une valeur à une commande

Einstellungen:

-   int id

-   Zeichenfolgenwert : valeur

-   string Datetime : (optionnel) Datetime de la valeur

API JSON Scenario
=================

scenario::all
-------------

Retourne la Listee de tous les scénarios

scenario::byId
--------------

Retourne le scénario spécifié

Einstellungen:

-   int id

scenario::export
----------------

Retourne l'export du scénario ainsi que le nom humain du scénario

Einstellungen:

-   int id

scenario::import
----------------

Permet d'importer un scénario.

Einstellungen:

-   int id : ID des Szenarios, in das importiert werden soll (leer, wenn erstellt)

-   Zeichenfolge humanName : menschlicher Name des Szenarios (leer bei Erstellung)

-   Array-Import : Szenario (aus dem Feld Exportszenario::Export)

scenario::Change
---------------------

Ändert den Status des angegebenen Szenarios.

Einstellungen:

-   int id

-   Zeichenfolgenstatus: \ [Run, Stop, aktivieren, deaktivieren \]

JSON-Protokoll-API
============

log::get
--------

Ermöglicht das Wiederherstellen eines Protokolls

Einstellungen:

-   Zeichenfolgenprotokoll : Name des wiederherzustellenden Protokolls

-   String-Start : Zeilennummer, auf der mit dem Lesen begonnen werden soll

-   Zeichenfolge nbLine : Anzahl der wiederherzustellenden Zeilen

log::hinzufügen
--------

Ermöglicht das Schreiben in ein Protokoll

Einstellungen:

-   Zeichenfolgenprotokoll : Name des wiederherzustellenden Protokolls

-   string type : Protokolltyp (Debug, Info, Warnung, Fehler)

-   Zeichenfolge Nachricht : SMS zu schreiben

-   Zeichenfolge logische ID : logische ID der generierten Nachricht


log::Liste
---------

Holen Sie sich die Jeedom-ProtokollListee

Einstellungen:

-   String-Filter : (optional) Filter nach dem Namen der wiederherzustellenden Protokolle

log::leer
----------

Leeren Sie ein Protokoll

Einstellungen:

-   Zeichenfolgenprotokoll : Name des zu leeren Protokolls

log::Entfernen
-----------

Ermöglicht das Löschen eines Protokolls

Einstellungen:

-   Zeichenfolgenprotokoll : Protokollname zum Löschen

JSON-Datenspeicher-API (Variable)
=============================

Datenspeicher::byTypeLinkIdKey
--------------------------

Ruft den Wert einer im Datenspeicher gespeicherten Variablen ab

Einstellungen:

-   string type : Art des gespeicherten Werts (für Szenarien
    es ist Szenario)

-   id linkId : -1 für das globale (Wert für die Standardszenarien,
    oder die Szenario-ID)

-   String-Schlüssel : Wertname

Datenspeicher::speichern
---------------

Speichert den Wert einer Variablen im Datenspeicher

Einstellungen:

-   string type : Art des gespeicherten Werts (für Szenarien
    es ist Szenario)

-   id linkId : -1 für das globale (Wert für die Standardszenarien,
    oder die Szenario-ID)

-   String-Schlüssel : Wertname

-   gemischter Wert : Wert aufzuzeichnen

JSON-Nachrichten-API
================

Nachricht::all
------------

Gibt die Liste aller Nachrichten zurück

Nachricht::hinzufügen
--------

Ermöglicht das Schreiben in ein Protokoll

Einstellungen:

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

Einstellungen:

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

jeedom::getUsbMapKlingeln
---------------------

Liste der USB-Anschlüsse und Namen der daran angeschlossenen USB-Sticks

JSON-Plugin-API
===============

Plugin::installieren
---------------

Installation / Update eines bestimmten Plugins

Einstellungen:

-   int Plugin \ _id (optional) : Plugin ID
-   Zeichenfolge logische ID (optional) : Plugin Name (logischer Name)

Plugin::Entfernen
--------------

Löschen eines bestimmten Plugins

Einstellungen:

-   int Plugin \ _id (optional) : Plugin ID
-   Zeichenfolge logische ID (optional) : Plugin Name (logischer Name)

Plugin::dependancyInfo
----------------------

Gibt Informationen zum Status von Plugin-Abhängigkeiten zurück

Einstellungen:

-   int Plugin \ _id (optional) : Plugin ID
-   Zeichenfolge logische ID (optional) : Plugin Name (logischer Name)

Plugin::dependancyInstall
-------------------------

Erzwingen Sie die Installation von Plugin-Abhängigkeiten

Einstellungen:

-   int Plugin \ _id (optional) : Plugin ID
-   Zeichenfolge logische ID (optional) : Plugin Name (logischer Name)

Plugin::deamonInfo
------------------

Gibt Informationen zum Status des Plugin-Daemons zurück

Einstellungen:

-   int Plugin \ _id (optional) : Plugin ID
-   Zeichenfolge logische ID (optional) : Plugin Name (logischer Name)

Plugin::deamonStart
-------------------

Zwinge den Dämon zu starten

Einstellungen:

-   int Plugin \ _id (optional) : Plugin ID
-   Zeichenfolge logische ID (optional) : Plugin Name (logischer Name)

Plugin::deamonStop
------------------

Dämonenstopp erzwingen

Einstellungen:

-   int Plugin \ _id (optional) : Plugin ID
-   Zeichenfolge logische ID (optional) : Plugin Name (logischer Name)

Plugin::deamonChangeAutoMode
----------------------------

Ändern Sie den Verwaltungsmodus des Dämons

Einstellungen:

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

Einstellungen:

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
