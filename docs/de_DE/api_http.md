Jeedom bietet Entwicklern und Anwendern eine umfassende API, um
Jeedom von jedem angeschlossenen Objekt zu steuern.

Zwei API´s sind verfügbar : Eines ist auf Programmierer ausgerichtet,
gesteuert mit JSON RPC 2.0 und ein anderes via URL und der HTTP Abfrage. 

Diese API ist einfach, mit einfachen HTTP-Anfragen über eine URL zu verwenden.

> **Note**
>
>Für die ganze Dokumentation entspricht IP_JEEDOM Ihrer URL für den
> Zugriff auf Jeedom. Dies ist (sofern Sie nicht mit Ihrem lokalen Netzwerk
> verbunden sind) die Internetadresse, die Sie verwenden, um von außen auf
> Jeedom zuzugreifen.

> **Note**
>
>Für die ganze Dokumentation entspricht API_KEY Ihrem API-Schlüssel, der
> für Ihre Installation spezifisch ist. Um ihn zu finden, gehen Sie zum Menü
> "Allgemein" → "Konfiguration" → Registerkarte "Allgemein".

Szenario 
========

Hier ist die URL =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=scenario&id=\#ID\#&action=\#ACTION\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=scenario&id=#ID#&action=#ACTION#)

-   **id** : Entspricht der ID Ihres Szenarios. Die ID befindet sich auf der
    entsprechenden Szenarioseite unter "Werkzeuge" → "Szenarien", sobald das 
    Szenario ausgewählt wurde, neben dem Namen der Registerkarte "Allgemein".
    Eine andere Möglichkeit, sie zu finden ist : Klicken Sie in "Werkzeuge" → 
    "Szenarien" auf "Übersicht".

-   **action** : Ist die Aktion, die Sie anwenden möchten. Die verfügbaren 
    Befehle sind : "Start", "Stopp", "Deaktivieren" und "Aktivieren", 
    um das Szenario zu starten, zu stoppen, zu deaktivieren oder 
    zu aktivieren.

-   **tags** [optional] : Wenn die Aktion "Start" ist, können Sie Tags an das 
    Szenario übergeben (siehe Dokumentation zu den Szenarien) in dem
    Format tags=toto%3D1%20tata%3D2 (beachten Sie, dass %20 einem 
    Leerzeichen und %3D einem = entspricht)

Info/Aktion Befehl
====================

Hier ist die URL =
[http://\#IP\_JEEDOM\#/jeedom/core/api/jeeApi.php?apikey=\#APIKEY\#&type=cmd&id=\#ID\#](http://#IP_JEEDOM#/jeedom/core/api/jeeApi.php?apikey=#APIKEY#&type=cmd&id=#ID#)

-   **id** : Entspricht der ID, was Sie steuern oder von dem Sie 
    Informationen erhalten möchten

Der einfachste Weg, um diese URL zu erhalten, ist auf die Seite Werkzeuge →
Übersicht Haus-Automatisierung zu gehen, suchen Sie nach dem Befehl und
dann öffnen sie seine erweiterte Konfiguration (das "Zahnrad"-Symbol). Dort sehen Sie eine URL, die basierend auf dem Typ und Subtyp des Befehls
bereits alles enthält, was Sie benötigen.

> **Notiz**
>
> Es ist möglich, dass dem Feld \#ID# mehrere Befehle gleichzeitig
> übergeben werden. Dazu muss ein Array in json übergeben werden (zB.
> %5B12,58,23%5D, beachten Sie, dass [und] von dem aus codiert werden
> muß %5B und %5D). Die Rückgabe von Jeedom wird in JSON efolgen.

> **Notiz**
>
> Die Parameter müssen für die URL kodiert werden, Sie können dieses
> Werkzeug benutzen, [hier](https://meyerweb.com/eric/tools/dencoder/)

Interaktion
===========

Hier ist die URL =
[http://\#IP\_JEEDOM\#/jeedom/core/api/jeeApi.php?apikey=\#APIKEY\#&type=interact&query=\#QUERY\#](http://#IP_JEEDOM#/jeedom/core/api/jeeApi.php?apikey=#APIKEY#&type=interact&query=#QUERY#)

-   **query** :  zu stellende Fragen an Jeedom

-   **utf8** [optional] : Sagt Jeedom, ob die Abfrage in utf8 codiert werden soll, 
    bevor versucht wird zu antworten

-   **emptyReply** [optional] : 0 zum antworten von Jeedom, auch wenn es nicht 
    verstanden hat, sonst 1

-   **profile** [optional] : Benutzername der Person, die die
    Interaktion auslöst

-   **reply_cmd** [optional] : ID des Befehls, der verwendet werden soll, 
    um auf die Anfrage zu antworten

Nachricht 
=======

Hier ist die URL =
[http://\#IP\_JEEDOM\#/jeedom/core/api/jeeApi.php?apikey=\#APIKEY\#&type=message&category=\#CATEGORY\#&message=\#MESSAGE\#](http://#IP_JEEDOM#/jeedom/core/api/jeeApi.php?apikey=#APIKEY#&type=message&category=#CATEGORY#&message=#MESSAGE#)

-   **category** : Kategorie der Nachricht, die in der Nachrichten mitte hinzugefügt werden soll

-   **message** : betreffende Nachricht, achten Sie darauf, die Nachricht zu 
    verschlüsseln (Leerzeichen ist %20, = %3D ...). Sie können dieses Werkzeug 
    verwenden, [hier](https://meyerweb.com/eric/tools/dencoder/)

Objekt
=====

InhaltHier ist die URL =
[http://\#IP\_JEEDOM\#/jeedom/core/api/jeeApi.php?apikey=\#APIKEY\#&type=object](http://#IP_JEEDOM#/jeedom/core/api/jeeApi.php?apikey=#APIKEY#&type=object)

Gibt die Liste aller Jeedom-Objekte in json zurück.

Geräte
==========

Hier ist die URL =
[http://\#IP\_JEEDOM\#/jeedom/core/api/jeeApi.php?apikey=\#APIKEY\#&type=eqLogic&object\_id=\#OBJECT\_ID\#](http://#IP_JEEDOM#/jeedom/core/api/jeeApi.php?apikey=#APIKEY#&type=eqLogic&object_id=#OBJECT_ID#)

-   **object_id** : ID des Objekts, dessen Gerät wir wiederherstellen
    wollen

Befehle
========

Hier ist die URL =
[http://\#IP\_JEEDOM\#/jeedom/core/api/jeeApi.php?apikey=\#APIKEY\#&type=command&eqLogic\_id=\#EQLOGIC\_ID\#](http://#IP_JEEDOM#/jeedom/core/api/jeeApi.php?apikey=#APIKEY#&type=command&eqLogic_id=#EQLOGIC_ID#)

-   **eqLogic\_id** :  ID der Geräte, deren Befehl man wiederbekommen 
    will

Vollständige Daten
=========

Hier ist die URL =
[http://\#IP\_JEEDOM\#/jeedom/core/api/jeeApi.php?apikey=\#APIKEY\#&type=fullData](http://#IP_JEEDOM#/jeedom/core/api/jeeApi.php?apikey=#APIKEY#&type=fullData)

Gibt alle Objekte, Geräte, Befehle (und deren Wert, wenn es sich um Informationen handelt) in json zurück.

Variable
========

Hier ist die URL =
[http://\#IP\_JEEDOM\#/jeedom/core/api/jeeApi.php?apikey=\#APIKEY\#&type=variable&name=\#NAME\#&value=](http://#IP_JEEDOM#/jeedom/core/api/jeeApi.php?apikey=#APIKEY#&type=variable&name=#NAME#&value=)*VALUE*

-   **name** : Name der Variablen, deren Wert man haben will (Lesen 
    des Wertes)

-   **value** [optional] : Wenn "value" angegeben wird, wird die Variable diesen 
    Wert annehmen (einen Wert schreiben)


