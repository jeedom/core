Jeedom bietet Entwicklern und Benutzern eine API
vollständig, damit Sie Jeedom von jedem Objekt aus steuern können
connecté.

Es stehen zwei APIs zur Verfügung : ein entwicklerorientierter Pilot
JSON RPC 2.0 und eine andere über URL und HTTP-Anfrage.

Diese API wird sehr einfach von einfachen HTTP-Anfragen über verwendet
URL.

> **Notiz**
>
> Für die gesamte Dokumentation gilt \#IP\_JEEDOM\# passt zu deiner URL
> Zugang zu Jeedom. Dies ist (es sei denn, Sie sind mit Ihrem Netzwerk verbunden
> lokal) der Internetadresse, mit der Sie auf Jeedom zugreifen
> von außen.

> **Notiz**
>
> Für die gesamte Dokumentation gilt \#API\_KEY\# passt zu Ihrem Schlüssel
> API, spezifisch für Ihre Installation. Um es zu finden, müssen Sie zu gehen
> das Menü "Allgemein" → Registerkarte "Konfiguration" → "Allgemein"".

Szenario 
========

Hier ist die URL =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = Szenario & id = \#ID\#&action=\#ACTION\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = Szenario & ID=#ID#&action=#ACTION#)

-   **Identifikation** : entspricht Ihrer Szenario-ID. Die ID befindet sich auf der
    Seite des betreffenden Szenarios, unter &quot;Tools&quot; → &quot;Szenarien&quot;, sobald die
    ausgewähltes Szenario neben dem Namen der Registerkarte &quot;Allgemein&quot;. andere
    Weg, um es zu finden : Klicken Sie unter &quot;Extras&quot; → &quot;Szenarien&quot; auf
    "Übersicht".

-   **Aktion** : entspricht der Aktion, die Sie anwenden möchten. die
    verfügbare Bestellungen sind : "start "," stop "," disable "und
    "aktivieren "um zu starten, zu stoppen, zu deaktivieren oder
    Aktivieren Sie das Szenario.

-   **Tags** \ [Optional \] : Wenn die Aktion &quot;Start&quot; ist, können Sie überspringen
    Tags zum Szenario (siehe Dokumentation zu Szenarien) unter
    Die Formular-Tags = toto% 3D1% 20tata% 3D2 (beachten Sie, dass% 20 a entspricht
    Raum und% 3D zu = )

Info / Aktionsbefehl 
====================

Hier ist die URL =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = cmd & id = \#ID\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = cmd & id=#ID#)

-   **Identifikation** : entspricht der ID dessen, was Sie fahren möchten oder von welchem
    Sie möchten Informationen erhalten

Der einfachste Weg, um diese URL zu erhalten, ist die Seite Extras →
Zusammenfassung der Hausautomation, um nach dem Befehl zu suchen und dann seine Konfiguration zu öffnen
Erweitert (das &quot;Zahnrad&quot; -Symbol) und dort sehen Sie eine URL, die enthält
schon alles was du brauchst je nach typ und subtyp des
commande.

> **Notiz**
>
> Es ist möglich für das Feld \#ID\# mehrere Bestellungen aufgeben
> plötzlich. Dazu müssen Sie ein Array in json übergeben (z
> % 5B12,58,23% 5D, beachten Sie, dass \ [und \] codiert werden müssen, daher% 5B
> und% 5D). Jeedoms Rückkehr wird ein Json sein

> **Notiz**
>
> Parameter müssen für die URL codiert sein, die Sie verwenden können
> ein Werkzeug, [hier](https://meyerweb.com/eric/tools/dencoder/)

Interaktion 
===========

Hier ist die URL =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = interagiere & query = \#QUERY\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = interagieren & abfragen=#QUERY#)

-   **Abfrage** : Frage an Jeedom zu stellen

-   **utf8** \ [Optional \] : teilt Jeedom mit, ob die Abfrage codiert werden soll
    in utf8 bevor Sie versuchen zu antworten

-   **leer Antworten** \ [Optional \] : 0 für Jeedom, um zu antworten, auch wenn es
    habe nicht verstanden, 1 sonst

-   **Profil** \ [Optional \] : Benutzername der Person
    Interaktion auslösen

-   **antworten\_cmd** \ [Optional \] : Befehls-ID, für die verwendet werden soll
    Nachfrage befriedigen

Nachricht 
=======

Hier ist die URL =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = message & category = \#CATEGORY\#&message=\#MESSAGE\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = message & category=#CATEGORY#&message=#MESSAGE#)

-   **Kategorie** : Nachrichtenkategorie, die dem Nachrichtenzentrum hinzugefügt werden soll

-   **Nachricht** : Nachricht in Frage, denken Sie sorgfältig über die Codierung
    die Nachricht (Leerzeichen wird% 20, =% 3D…). Sie können eine verwenden
    outil, [hier](https://meyerweb.com/eric/tools/dencoder/)

Objekt 
=====

Hier ist die URL =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = Objekt](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = Objekt)

Gibt in json die Liste aller Jeedom-Objekte zurück

Ausrüstung 
==========

Hier ist die URL =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = eqLogic & object\_id = \#OBJECT\_ID\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = eqLogic & object_id=#OBJECT_ID#)

-   **Objekt\_id** : ID des Objekts, von dem wir abrufen möchten
    équipements

Bestellen 
========

Hier ist die URL =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = command & eqLogic\_id = \#EQLOGIC\_ID\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = command & eqLogic_id=#EQLOGIC_ID#)

-   **eqLogic\_id** : ID der Ausrüstung, von der wir wiederherstellen möchten
    commandes

Vollständige Daten 
=========

Hier ist die URL =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = fullData](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = fullData)

Gibt alle Objekte, Geräte, Befehle (und deren Wert, falls dies der Fall ist) zurück
sind infos) in json

Variable 
========

Hier ist die URL =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = variable & name = \#NAME\#&value=](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = Variable & Name=#NAME#&value=)*Wert*

-   **Name** : Name der Variablen, deren Wert gewünscht wird (Lesen von
    der Wert)

-   **Wert** \ [Optional \] : Wenn &quot;Wert&quot; angegeben ist, dann die Variable
    wird diesen Wert annehmen (einen Wert schreiben)


