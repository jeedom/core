Hier finden Sie eine Dokumentation zu API-Methoden. Zuerst ist hier
die Spezifikationen (JSON RPC 2.0) :
<http://www.jsonrpc.org/specification>

Der Zugriff auf die API erfolgt über die URL : *URL\_JEEDOM*/core/api/jeeApi.php

Divers
======

ping
----

Pong zurückgeben, Kommunikation mit Jeedom testen

version
-------

Gibt die Version von Jeedom zurück

datetime
--------

Gibt die Jeedom-Datumszeit in Mikrosekunden zurück

Konfigurations-API
==========

config::byKey
-------------

Gibt einen Konfigurationswert zurück.

Einstellungen :

-   String-Schlüssel : Konfigurationswertschlüssel, der zurückgegeben werden soll

-   String Plugin : (optional), Konfigurationswert-Plugin

-   Zeichenfolge Standard : (optional), Wert, der zurückgegeben werden soll, wenn der Schlüssel nicht vorhanden ist
    pas

config::save
------------

Speichert einen Konfigurationswert

Einstellungen :

-   Zeichenfolgenwert : Wert aufzuzeichnen

-   String-Schlüssel : Konfigurationswertschlüssel zum Speichern

-   String Plugin : (optional), Konfigurationswert Plugin zu
    enregistrer

JSON-Ereignis-API
==============

event::changes
--------------

Gibt die Liste der Änderungen seit der im Parameter übergebenen Datum / Uhrzeit zurück
(muss in Mikrosekunden sein). Sie haben auch in der Antwort die
Jeedom's aktuelle Datumszeit (wird für die nächste Abfrage wiederverwendet)

Einstellungen :

-   int datetime

JSON Plugin API
===============

plugin::listPlugin
------------------

Gibt die Liste aller Plugins zurück

Einstellungen :

-   int activOnly = 0 (gibt nur die Liste der aktivierten Plugins zurück)

-   int orderByCaterogy = 0 (gibt die Liste der sortierten Plugins zurück
    nach Kategorie)

Objekt-JSON-API
==============

object::all
-----------

Gibt die Liste aller Objekte zurück

object::full
------------

Gibt die Liste aller Objekte zurück, wobei für jedes Objekt alle seine
Ausrüstung und für jede Ausrüstung alle ihre Befehle sowie
Zustände von diesen (für Befehle vom Typ Info)

object::fullById
----------------

Gibt ein Objekt mit seiner gesamten Ausrüstung und für jede Ausrüstung zurück
alle seine Befehle sowie ihre Zustände (z
Info-Typ-Befehle)

Einstellungen :

-   int id

object::byId
------------

Gibt das angegebene Objekt zurück

Einstellungen:

-   int id

object::fullById
----------------

Gibt ein Objekt, seine Ausrüstung und für jede Ausrüstung alle seine zurück
Befehle sowie die Zellenzustände (für Typbefehle
info)

object::save
------------

Gibt das angegebene Objekt zurück

Einstellungen:

-   int id (leer, wenn es sich um eine Kreation handelt)

-   Zeichenfolgenname

-   int Vater\_id = null

-   int isVisible = 0

-   int position

-   Array-Konfiguration

-   Array-Anzeige

JSON-Zusammenfassungs-API
================

summary::global
---------------

Gibt die globale Zusammenfassung für den im Parameter übergebenen Schlüssel zurück

Einstellungen:

-   String-Schlüssel : (optional), Schlüssel der gewünschten Zusammenfassung, wenn leer, dann Jeedom
    sendet Ihnen die Zusammenfassung aller Schlüssel

summary::byId
-------------

Gibt die Zusammenfassung für die Objekt-ID zurück

Einstellungen:

-   int id : Objekt-ID

-   String-Schlüssel : (optional), Schlüssel der gewünschten Zusammenfassung, wenn leer, dann Jeedom
    sendet Ihnen die Zusammenfassung aller Schlüssel

JSON EqLogic API
================

eqLogic::all
------------

Gibt die Liste aller Geräte zurück

eqLogic::fullById
-----------------

Gibt Geräte und ihre Befehle sowie deren Status zurück
(für Info-Befehle)

Einstellungen:

-   int id

eqLogic::byId
-------------

Gibt das angegebene Gerät zurück

Einstellungen:

-   int id

eqLogic::byType
---------------

Gibt alle Geräte zurück, die zum angegebenen Typ gehören (Plugin)

Einstellungen:

-   Zeichenfolgentyp

eqLogic::byObjectId
-------------------

Gibt alle Geräte zurück, die zum angegebenen Objekt gehören

Einstellungen:

-   int object\_id

eqLogic::byTypeAndId
--------------------

Gibt eine Gerätetabelle gemäß den Parametern zurück. Die Rückkehr
wird vom Formulararray sein (&#39;eqType1&#39; ⇒array (&#39;id&#39;⇒…,&#39; cmds &#39;⇒
Array (….)), &#39;eqType2&#39; ⇒array (&#39;id&#39;⇒…,&#39; cmds &#39;⇒ Array (….))….,id1 ⇒
Array (&#39;id&#39;⇒…,&#39; cmds &#39;⇒ Array (….)), id2 ⇒ Array (&#39; id&#39;⇒…, &#39;cmds&#39; ⇒
array(…​.))..)

Einstellungen:

-   string \ [\] eqType = Tabelle der erforderlichen Gerätetypen

-   int \ [\] id = Tabelle der gewünschten benutzerdefinierten Geräte-IDs

eqLogic::save
-------------

Gibt das registrierte / erstellte Gerät zurück

Einstellungen:

-   int id (leer, wenn es sich um eine Kreation handelt)

-   Zeichenfolge eqType\_name (Skripttyp, virtuelle Ausrüstung…)

-   Zeichenfolgenname

-   Zeichenfolge logische ID = ''

-   int object\_id = null

-   int eqReal\_id = null

-   int isVisible = 0

-   int isEnable = 0

-   Array-Konfiguration

-   int timeout

-   Array-Kategorie

JSON Cmd API
============

cmd::all
--------

Gibt die Liste aller Befehle zurück

cmd::byId
---------

Gibt den angegebenen Befehl zurück

Einstellungen:

-   int id

cmd::byEqLogicId
----------------

Gibt alle Bestellungen zurück, die zum angegebenen Gerät gehören

Einstellungen:

-   int eqLogic\_id

cmd::execCmd
------------

Führen Sie den angegebenen Befehl aus

Einstellungen:

-   int id : ID eines Befehls oder ID-Arrays, wenn Sie ausführen möchten
    mehrere Bestellungen gleichzeitig

-   \ [Optionen \] Liste der Befehlsoptionen (abhängig von Typ und
    Befehlssubtyp)

cmd::getStatistique
-------------------

Gibt Statistiken zur Bestellung zurück (funktioniert nur bei
Infos und historische Befehle)

Einstellungen:

-   int id

-   Zeichenfolge startTime : Startdatum der Statistikberechnung

-   Zeichenfolge endTime : Enddatum der Statistikberechnung

cmd::getTendance
----------------

Gibt den Trend für den Befehl zurück (funktioniert nur für die Befehle von
Info und historischer Typ)

Einstellungen:

-   int id

-   Zeichenfolge startTime : Startdatum der Trendberechnung

-   Zeichenfolge endTime : Enddatum der Trendberechnung

cmd::getHistory
---------------

Gibt den Befehlsverlauf zurück (funktioniert nur mit den Befehlen von
Info und historischer Typ)

Einstellungen:

-   int id

-   Zeichenfolge startTime : Startdatum der Geschichte

-   Zeichenfolge endTime : Enddatum der Geschichte

cmd::save
---------

Gibt das angegebene Objekt zurück

Einstellungen:

-   int id (leer, wenn es sich um eine Kreation handelt)

-   Zeichenfolgenname

-   Zeichenfolge logische ID

-   Zeichenfolge eqType

-   Zeichenfolgenreihenfolge

-   Zeichenfolgentyp

-   String-Subtyp

-   int eqLogic\_id

-   int isHistorized = 0

-   String-Einheit = ''

-   Array-Konfiguration

-   Array-Vorlage

-   Array-Anzeige

-   Array HTML

-   int value = null

-   int isVisible = 1

-   Array-Alarm

cmd::event
-------------------

Ermöglicht das Senden eines Werts an eine Bestellung

Einstellungen:

-   int id

-   Zeichenfolgenwert : valeur

-   Zeichenfolge datetime : (optional) Datum / Uhrzeit-Wert

JSON-Szenario-API
=================

scenario::all
-------------

Gibt die Liste aller Szenarien zurück

scenario::byId
--------------

Gibt das angegebene Szenario zurück

Einstellungen:

-   int id

scenario::export
----------------

Gibt den Export des Szenarios sowie den menschlichen Namen des Szenarios zurück

Einstellungen:

-   int id

scenario::import
----------------

Ermöglicht das Importieren eines Szenarios.

Einstellungen:

-   int id : ID des zu importierenden Szenarios (leer bei Erstellung)

-   Zeichenfolge humanName : menschlicher Name des Szenarios (leer bei Erstellung)

-   Array-Import : Szenario (aus dem Feld Exportszenario::export)

scenario::changeState
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

log::list
---------

Holen Sie sich die Jeedom-Protokollliste

Einstellungen:

-   String-Filter : (optional) Filtern Sie nach dem Namen der abzurufenden Protokolle

log::empty
----------

Leeren Sie ein Protokoll

Einstellungen:

-   Zeichenfolgenprotokoll : Name des zu leeren Protokolls

log::remove
-----------

Ermöglicht das Löschen eines Protokolls

Einstellungen:

-   Zeichenfolgenprotokoll : Protokollname zum Löschen

JSON-Datenspeicher-API (Variable)
=============================

datastore::byTypeLinkIdKey
--------------------------

Ruft den Wert einer im Datenspeicher gespeicherten Variablen ab

Einstellungen:

-   Zeichenfolgentyp : Art des gespeicherten Werts (für Szenarien
    Es ist ein Szenario)

-   id linkId : -1 für global (Wert für Standardszenarien,
    oder die Szenario-ID)

-   String-Schlüssel : Wertname

datastore::save
---------------

Speichert den Wert einer Variablen im Datenspeicher

Einstellungen:

-   Zeichenfolgentyp : Art des gespeicherten Werts (für Szenarien
    Es ist ein Szenario)

-   id linkId : -1 für global (Wert für Standardszenarien,
    oder die Szenario-ID)

-   String-Schlüssel : Wertname

-   gemischter Wert : Wert aufzuzeichnen

JSON-Nachrichten-API
================

message::all
------------

Gibt die Liste aller Nachrichten zurück

message::removeAll
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

-   int reply\_cmd = NULL : Befehls-ID, die zum Antworten verwendet werden soll,
    Wenn nicht angegeben, sendet Jeedom Ihnen die Antwort im JSON

interactQuery::all
------------------

Gibt die vollständige Liste aller Interaktionen zurück

JSON-System-API
===============

jeedom::halt
------------

Stoppen Sie Jeedom

jeedom::reboot
--------------

Starten Sie Jeedom neu

jeedom::isOk
------------

Lässt Sie wissen, ob der globale Zustand von Jeedom in Ordnung ist

jeedom::update
--------------

Starten wir ein Jeedom-Update

jeedom::backup
--------------

Ermöglicht das Starten eines Backups von Jeedom

jeedom::getUsbMapping
---------------------

Liste der USB-Anschlüsse und Namen der daran angeschlossenen USB-Sticks

JSON Plugin API
===============

plugin::install
---------------

Installation / Update eines bestimmten Plugins

Einstellungen:

-   String-Plugin\_id : Plugin-Name (logischer Name)

plugin::remove
--------------

Löschen eines bestimmten Plugins

Einstellungen:

-   String-Plugin\_id : Plugin-Name (logischer Name)

plugin::dependancyInfo
----------------------

Gibt Informationen zum Status von Plugin-Abhängigkeiten zurück

Einstellungen:

-   String-Plugin\_id : Plugin-Name (logischer Name)

plugin::dependancyInstall
-------------------------

Erzwingen Sie die Installation von Plugin-Abhängigkeiten

Einstellungen:

-   String-Plugin\_id : Plugin-Name (logischer Name)

plugin::deamonInfo
------------------

Gibt Informationen zum Status des Plugin-Daemons zurück

Einstellungen:

-   String-Plugin\_id : Plugin-Name (logischer Name)

plugin::deamonStart
-------------------

Zwinge den Dämon zu starten

Einstellungen:

-   String-Plugin\_id : Plugin-Name (logischer Name)

plugin::deamonStop
------------------

Dämonenstopp erzwingen

Einstellungen:

-   String-Plugin\_id : Plugin-Name (logischer Name)

plugin::deamonChangeAutoMode
----------------------------

Ändern Sie den Verwaltungsmodus des Dämons

Einstellungen:

-   String-Plugin\_id : Plugin-Name (logischer Name)

-   int-Modus : 1 für automatisch, 0 für manuell

JSON-Update-API
===============

update::all
-----------

Geben Sie die Liste aller installierten Komponenten, deren Version und die zurück
verwandte Informationen

update::checkUpdate
-------------------

Ermöglicht die Suche nach Updates

update::update
--------------

Ermöglicht das Aktualisieren von Jeedom und allen Plugins

update::doUpdate
--------------

Einstellungen:

-   int plugin\_id (optional) : Plugin ID
-   Zeichenfolge logische ID (optional) : Plugin-Name (logischer Name)

JSON-Netzwerk-API
================

network::restartDns
-------------------

Erzwingen Sie den (Neustart) des Jeedom DNS

network::stopDns
----------------

Erzwingt das Anhalten des DNS Jeedom

network::dnsRun
---------------

Gibt den Jeedom DNS-Status zurück

JSON-API-Beispiele
=================

Hier ist ein Beispiel für die Verwendung der API. Für das folgende Beispiel
Ich benutze [diese Klasse
php](https://github.com/jeedom/core/blob/release/core/class/jsonrpcClient.class.php)
Dies vereinfacht die Verwendung der API.

Abrufen der Objektliste :

`` `{.php}
$jsonrpc = new jsonrpcClient('#URL_JEEDOM#/core/api/jeeApi.php', #API_KEY#);
if ($ jsonrpc-&gt; sendrequest ( ‚Objekt::all ', Array())){
    print_r ($ jsonrpc-&gt; getResult ());
}else{
    echo $ jsonrpc-&gt; getError ();
}
`` ''

Ausführung eines Auftrags (mit der Option eines Titels und einer Nachricht)

`` `{.php}
$jsonrpc = new jsonrpcClient('#URL_JEEDOM#/core/api/jeeApi.php', #API_KEY#);
if ($ jsonrpc-&gt; sendrequest ( ‚cmd::execCmd ', array (' id' => #cmd_id#, 'options '=> array (' title '=>' Cuckoo ',' message '=>' Es funktioniert')))){
    Echo &#39;OK&#39;;
}else{
    echo $ jsonrpc-&gt; getError ();
}
`` ''

Die API kann natürlich auch in anderen Sprachen verwendet werden (einfach ein Beitrag
auf einer Seite)
