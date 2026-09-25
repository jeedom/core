Hier finden Sie eine Dokumentation zu den API-Methoden.

Zunächst einmal hier die Spezifikationen (JSON-RPC 2.0):
<http://www.jsonrpc.org/specification>

Der Zugriff auf die API erfolgt über die URL: *URL\_JEEDOM*/core/api/jeeApi.php

Hier ist ein Beispiel für die Konfiguration eines JSON-Objekts, das im Hauptteil einer von einem HTTP-Agenten gesendeten Anfrage verwendet werden kann:
``` json
{
    "jsonrpc": "2.0",
    "id": "007",
    "method": "event::changes",
    "params": {
        "apikey": "{{apikey}}",
        "datetime": "0"
    }
}
```

Verschiedenes
======

Ping
----

Gibt „pong“ zurück, ermöglicht das Testen der Kommunikation mit Jeedom

Version
-------

Gibt die Version von Jeedom zurück

Datum und Uhrzeit
--------

Gibt das Jeedom-Datum und die Uhrzeit in Mikrosekunden zurück

API-Konfiguration
==========

config::byKey
-------------

Gibt einen Konfigurationswert zurück.

JSON-Parameter:

-   string key: Schlüssel des zurückzugebenden Konfigurationswerts

-   String-Plugin: (optional), Plugin für den Konfigurationswert

-   Standard-String: (optional), Wert, der zurückgegeben werden soll, wenn der Schlüssel nicht existiert

config::save
------------

Speichert einen Konfigurationswert

JSON-Parameter:

-   String-Wert: zu speichernder Wert

-   String-Schlüssel: Schlüssel des zu speichernden Konfigurationswerts

-   String-Plugin: (optional), Plugin für den zu speichernden Konfigurationswert

JSON-Ereignis-API
==============

Ereignis::Änderungen
--------------

Gibt die Liste der Änderungen seit dem als Parameter übergebenen Datums- und Zeitstempel zurück (muss in Mikrosekunden angegeben werden). In der Antwort erhalten Sie außerdem den aktuellen Datums- und Zeitstempel von Jeedom (zur Wiederverwendung für die nächste Abfrage).

JSON-Parameter:

-   int datetime

JSON-API-Plugin
===============

Plugin::listPlugin
------------------

Gibt eine Liste aller Plugins zurück

JSON-Parameter:

-   int activateOnly = 0 (gibt nur die Liste der aktivierten Plugins zurück)

-   int orderByCategory = 0 (gibt die Liste der Plugins nach Kategorie sortiert zurück)

JSON-Objekt-API
==============

jeeObject::all
-----------

Gibt eine Liste aller Objekte zurück

jeeObject::full
------------

Gibt eine Liste aller Objekte zurück, wobei für jedes Objekt alle seine Geräte und für jedes Gerät alle seine Befehle sowie deren Status (bei Befehlen vom Typ „Info“) aufgeführt werden

jeeObject::fullById
----------------

Gibt ein Objekt mit all seinen Geräten zurück, und für jedes Gerät alle seine Befehle sowie deren Status (für Befehle vom Typ „info“)

JSON-Parameter:

-   int id

jeeObject::byId
------------

Gibt das angegebene Objekt zurück

Einstellungen:

-   int id

jeeObject::fullById
----------------

Gibt ein Objekt, dessen Geräte und für jedes Gerät alle Befehle sowie deren Status zurück (für Befehle vom Typ „info“)

jeeObject::save
------------

Gibt das angegebene Objekt zurück

Einstellungen:

-   int id (leer, wenn es sich um eine Neuanlage handelt)

-   String-Name

-   int father_id = null

-   int isVisible = 0

-   int position

-   Array-Konfiguration

-   Array-Anzeige

JSON-API-Übersicht
================

Zusammenfassung::global
---------------

Gibt die Gesamtzusammenfassung für den als Parameter übergebenen Schlüssel zurück

Einstellungen:

-   string key: (optional), Schlüssel der gewünschten Zusammenfassung; wenn leer, gibt Jeedom die Zusammenfassung für alle Schlüssel zurück

Zusammenfassung::nach ID
-------------

Gibt die Zusammenfassung für das Objekt mit der ID zurück

Einstellungen:

-   int id: ID des Objekts

-   string key: (optional), Schlüssel der gewünschten Zusammenfassung; wenn leer, gibt Jeedom die Zusammenfassung für alle Schlüssel zurück

EqLogic-JSON-API
================

eqLogic::all
------------

Gibt die Liste aller Geräte zurück

eqLogic::fullById
-----------------

Gibt ein Gerät und dessen Befehle sowie deren Status zurück (für Befehle vom Typ „info“)

Einstellungen:

-   int id

eqLogic::byId
-------------

Gibt die angegebene Ausrüstung zurück

Einstellungen:

-   int id

eqLogic::byType
---------------

Gibt alle Geräte zurück, die dem angegebenen Typ (Plugin) angehören

Einstellungen:

-   Zeichenkette

eqLogic::byObjectId
-------------------

Gibt alle Geräte zurück, die zum angegebenen Objekt gehören

Einstellungen:

-   int object_id

eqLogic::byTypeAndId
--------------------

Gibt ein Array mit Geräten basierend auf den Parametern zurück.

Die Rückgabe erfolgt in der Form array('eqType1' ⇒ array( 'id' ⇒ …​, 'cmds' ⇒
array(…​.)),'eqType2' ⇒array( 'id'⇒…​,'cmds' ⇒ array(…​.))…​.,id1 ⇒
array( 'id' ⇒ …​, 'cmds' ⇒ array(…​.)), id2 ⇒ array( 'id' ⇒ …​, 'cmds' ⇒
array(…​.))..)

Einstellungen:

-   string\[\] eqType = Array der gewünschten Gerätetypen

-   int\[\] id = Array der gewünschten benutzerdefinierten Geräte-IDs

eqLogic::save
-------------

Gibt die registrierten/angelegten Geräte zurück

Einstellungen:

-   int id (leer, wenn es sich um eine Neuanlage handelt)

-   string eqType\_name (Gerätetyp: Skript, virtuell…​)

-   String-Name

-   string logicalId = ''

-   int object_id = null

-   int eqReal\_id = null

-   int isVisible = 0

-   int int isEnable = 0

-   Array-Konfiguration

-   int timeout

-   Array-Kategorie

JSON-Befehls-API
============

cmd::all
--------

Gibt eine Liste aller Befehle zurück

cmd::byId
---------

Gibt den angegebenen Befehl zurück

Einstellungen:

-   int id

cmd::byEqLogicId
----------------

Gibt alle Befehle zurück, die zu dem angegebenen Gerät gehören

Einstellungen:

-   int eqLogic\_id

cmd::execCmd
------------

Führt den angegebenen Befehl aus

Einstellungen:

-   int id: ID eines Befehls oder Array von IDs, wenn Sie mehrere Befehle gleichzeitig ausführen möchten

-   \[Optionen\] Liste der Befehlsoptionen (abhängig vom Befehlstyp und -untertyp)

cmd::getStatistik
-------------------

Gibt Statistiken zum Befehl zurück (funktioniert nur bei Befehlen vom Typ „info“ und bei protokollierten Befehlen)

Einstellungen:

-   int id

-   String „startTime“: Startdatum für die Berechnung der Statistiken

-   String „endTime“: Enddatum für die Berechnung der Statistiken

cmd::getTrend
----------------

Gibt den Trend des Befehls zurück (funktioniert nur bei Befehlen vom Typ „info“ und bei protokollierten Befehlen)

Einstellungen:

-   int id

-   String „startTime“: Startdatum für die Trendberechnung

-   String „endTime“: Enddatum der Trendberechnung

cmd::getHistory
---------------

Gibt den Bestellverlauf zurück (funktioniert nur bei Bestellungen vom Typ „Info“ und solchen, die im Verlauf gespeichert sind)

Einstellungen:

-   int id

-   String „startTime“: Startdatum des Verlaufs

-   String „endTime“: Enddatum des Verlaufs

cmd::save
---------

Gibt das angegebene Objekt zurück

Einstellungen:

-   int id (leer, wenn es sich um eine Neuanlage handelt)

-   String-Name

-   Zeichenkette „logicalId“

-   string eqType

-   Reihenfolge der Zeichenfolge

-   Zeichenkette

-   String-Subtyp

-   int eqLogic\_id

-   int isHistorized = 0

-   string unite = ''

-   Array-Konfiguration

-   Array-Vorlage

-   Array-Anzeige

-   HTML-Array

-   int value = null

-   int isVisible = 1

-   Array-Alarm

cmd::event
-------------------

Ermöglicht das Senden eines Werts an einen Befehl

Einstellungen:

-   int id

-   String-Wert: Wert

-   String „datetime“: (optional) Datums- und Uhrzeitangabe des Werts

JSON-API-Szenario
=================

Szenario::alle
-------------

Gibt die Liste aller Szenarien zurück

Szenario::nach_ID
--------------

Gibt das angegebene Szenario zurück

Einstellungen:

-   int id

Szenario::Export
----------------

Gibt den Export des Szenarios sowie den *leserfreundlichen Namen* des Szenarios zurück

Einstellungen:

-   int id

Szenario::Import
----------------

Ermöglicht das Importieren eines Szenarios.

Einstellungen:

-   int id: ID des Szenarios, in das importiert werden soll (leer bei Erstellung)

-   String „humanName“: *menschlicher Name* des Szenarios (leer bei Erstellung)

-   Array-Import: Szenario (aus dem Feld „export“ von „scenario::export“)

Szenario::Zustandsänderung
---------------------

Ändert den Status des angegebenen Szenarios.

Einstellungen:

-   int id

-   String-Zustand: \[run,stop,enable,disable\]

JSON-API-Protokoll
============

log::get
--------

Ermöglicht das Abrufen eines Protokolls

Einstellungen:

-   String „log“: Name des abzurufenden Protokolls

-   string start: Zeilennummer, ab der das Einlesen beginnen soll

-   string nbLine: Anzahl der abzurufenden Zeilen

log::add
--------

Ermöglicht das Schreiben in ein Protokoll

Einstellungen:

-   String „log“: Name des abzurufenden Protokolls

-   string type: Protokolltyp (debug, info, warning, error)

-   String-Meldung: einzugebende Textmeldung

-   String „logicalId“: logische ID der generierten Nachricht


log::list
---------

Ermöglicht das Abrufen der Liste der Jeedom-Protokolle

Einstellungen:

-   Filterzeichenfolge: (optional) Filter nach dem Namen der abzurufenden Protokolle

log::empty
----------

Ermöglicht das Löschen eines Protokolls

Einstellungen:

-   String „log“: Name des zu leeren Protokolls

log::remove
-----------

Ermöglicht das Löschen eines Protokolls

Einstellungen:

-   String „log“: Name des zu löschenden Logs

JSON-Datenspeicher-API (variabel)
=============================

datastore::byTypeLinkIdKey
--------------------------

Ruft den Wert einer im Datenspeicher abgelegten Variablen ab

Einstellungen:

-   string type: Typ des gespeicherten Werts (bei Szenarien ist dies „scenario“)

-   Link-ID: -1 für die globale ID (Standardwert für Szenarien oder die ID des Szenarios)

-   String-Schlüssel: Name des Werts

datastore::save
---------------

Speichert den Wert einer Variablen im Datenspeicher

Einstellungen:

-   String-Typ: Typ des gespeicherten Werts (für Szenarien
(das ist ein Szenario)

-   id linkId: -1 für den globalen Bereich (Standardwert für Szenarien,
oder die ID des Szenarios)

-   String-Schlüssel: Name des Werts

-   gemischter Wert: zu erfassender Wert

JSON-API-Nachricht
================

message::all
------------

Gibt eine Liste aller Nachrichten zurück

message::add
--------

Ermöglicht das Schreiben in ein Protokoll

Einstellungen:

-   string type: Protokolltyp (debug, info, warning, error)

-   String-Meldung: Meldung

-   String „action“: action

-   Zeichenkette „logicalId“: logicalId

message::removeAll
------------------

Alle Nachrichten löschen

JSON-API-Interaktion
====================

interact::tryToReply
--------------------

Versuche, eine Anfrage einer Interaktion zuzuordnen, führe die Aktion aus und antworte entsprechend

Einstellungen:

-   Suchanfrage (Suchbegriff)

-   int reply_cmd = NULL: ID des Befehls, der zur Antwort verwendet werden soll,
Wenn nicht angegeben, sendet Jeedom die Antwort im JSON-Format zurück.

interactQuery::all
------------------

Zeigt die vollständige Liste aller Interaktionen an

JSON-System-API
===============

jeedom::halt
------------

Ermöglicht das Beenden von Jeedom

jeedom::Neustart
--------------

Ermöglicht einen Neustart von Jeedom

jeedom::isOk
------------

Zeigt an, ob der Gesamtstatus von Jeedom in Ordnung ist

jeedom::update
--------------

Ermöglicht das Starten eines Jeedom-Updates

jeedom::backup
--------------

Ermöglicht das Starten einer Jeedom-Sicherung

jeedom::getUsbMapping
---------------------

Liste der USB-Anschlüsse und der Namen der daran angeschlossenen USB-Sticks

JSON-API-Plugin
===============

Plugin::Installieren
---------------

Installation/Aktualisierung eines bestimmten Plugins

Einstellungen:

-   int plugin_id (optional): ID des Plugins
-   string logicalId (optional): Name des Plugins (logischer Name)

Plugin::Entfernen
--------------

Ein bestimmtes Plugin entfernen

Einstellungen:

-   int plugin_id (optional): ID des Plugins
-   string logicalId (optional): Name des Plugins (logischer Name)

Plugin::dependancyInfo
----------------------

Gibt Informationen zum Status der Abhängigkeiten des Plugins zurück

Einstellungen:

-   int plugin_id (optional): ID des Plugins
-   string logicalId (optional): Name des Plugins (logischer Name)

Plugin::Abhängigkeiten installieren
-------------------------

Erzwingt die Installation der Abhängigkeiten des Plugins

Einstellungen:

-   int plugin_id (optional): ID des Plugins
-   string logicalId (optional): Name des Plugins (logischer Name)

Plugin::DaemonInfo
------------------

Gibt Informationen zum Status des Plugin-Daemons zurück

Einstellungen:

-   int plugin_id (optional): ID des Plugins
-   string logicalId (optional): Name des Plugins (logischer Name)

Plugin::DaemonStart
-------------------

Erzwingt den Start des Daemons

Einstellungen:

-   int plugin_id (optional): ID des Plugins
-   string logicalId (optional): Name des Plugins (logischer Name)

Plugin::daemonStop
------------------

Den Daemon zwangsweise beenden

Einstellungen:

-   int plugin_id (optional): ID des Plugins
-   string logicalId (optional): Name des Plugins (logischer Name)

Plugin::daemonChangeAutoMode
----------------------------

Ändert den Betriebsmodus des Daemons

Einstellungen:

-   int plugin_id (optional): ID des Plugins
-   string logicalId (optional): Name des Plugins (logischer Name)
-   int mode: 1 für Automatik, 0 für manuell

JSON-API-Aktualisierung
===============

update::all
-----------

Gibt eine Liste aller installierten Komponenten, deren Versionen und zugehörige Informationen zurück

update::checkUpdate
-------------------

Ermöglicht die Überprüfung auf Updates

Update::Update
--------------

Ermöglicht die Aktualisierung von Jeedom und allen Plugins

update::doUpdate
--------------

Einstellungen:

-   int plugin_id (optional): ID des Plugins
-   string logicalId (optional): Name des Plugins (logischer Name)

JSON-API-Netzwerk
================

network::restartDns
-------------------

Erzwingt einen (Neu-)Start des Jeedom-DNS

Netzwerk::stopDns
----------------

DNS von Jeedom zwangsweise beenden

network::dnsRun
---------------

JSON-API-Zeitleiste
===============

Zeitleiste::alle
-----------

Gibt alle Elemente der Zeitleiste zurück

Zeitleiste::Ordnerliste
-----------

Zeigt alle Ordner (Kategorien) der Zeitleiste an

Zeitleiste::nach Ordner
-----------

Gibt alle Elemente des angeforderten Ordners zurück

Einstellungen:

-   String „folder“: Name des Ordners

JSON-Benutzer-API
=================

Benutzer::Alle
-------------

Gibt die Liste aller Benutzer zurück

Benutzer::Speichern
---------------------

Benutzer anlegen oder bearbeiten

Einstellungen:

-   int id (falls bearbeitet)

-   Anmelde-String

-   Passwortzeichenfolge

-   String-Profil: \[admin,user,restrict\]


JSON-API-Beispiele
=================

Hier ist ein Beispiel für die Verwendung der API. Für das folgende Beispiel
Ich nutze [diese PHP-Klasse](https://github.com/jeedom/core/blob/master/core/class/jsonrpcClient.class.php)
die die Nutzung der API vereinfacht.

Abrufen der Objektliste:

``` {.php}
$jsonrpc = new jsonrpcClient('#URL_JEEDOM#/core/api/jeeApi.php', #API_KEY#);
if($jsonrpc->sendRequest('jeeObject::all', array())){
    print_r($jsonrpc->getResult());
}else{
    echo $jsonrpc->getError();
}
```

Ausführung eines Befehls (optional mit Titel und Meldung)

``` {.php}
$jsonrpc = new jsonrpcClient('#URL_JEEDOM#/core/api/jeeApi.php', #API_KEY#);
if($jsonrpc->sendRequest('cmd::execCmd', array('id' => #cmd_id#, 'options' => array('title' => 'Coucou', 'message' => 'Ca marche')))){
    echo 'OK';
}else{
    echo $jsonrpc->getError();
}
```

Die API lässt sich natürlich auch mit anderen Sprachen nutzen (einfach ein Beitrag auf einer Seite)
