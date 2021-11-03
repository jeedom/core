# HTTP-API

Jeedom bietet Entwicklern und Benutzern eine vollständige API, mit der sie Jeedom von jedem verbundenen Objekt aus steuern können.

Es stehen zwei APIs zur Verfügung : ein entwicklerorientierter JSON RPC 2-Pilot.0 und eine andere über URL und HTTP-Anfrage.

Diese API ist sehr einfach durch einfache HTTP-Anfragen über URL zu verwenden.

> **Hinweis**
>
> Für die gesamte Dokumentation gilt \#IP\_JEEDOM\# entspricht Ihrer Jeedom-Zugriffs-URL. Dies ist (sofern Sie nicht mit Ihrem lokalen Netzwerk verbunden sind) die Internetadresse, mit der Sie von außen auf Jeedom zugreifen.

> **Hinweis**
>
> Für die gesamte Dokumentation gilt \#API\_KEY\# entspricht Ihrem API-Schlüssel, der für Ihre Installation spezifisch ist. Um es zu finden, gehen Sie zum Menü "Allgemein" → "Konfiguration" → Registerkarte "Allgemein"".

## Szenario

VoHier l'URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = Szenario & id = \#ID\#&action=\#ACTION\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = Szenario & ID=#ID#&action=#ACTION#)

- **Ich würde** : entspricht Ihrer Szenario-ID. Die ID finden Sie auf der entsprechenden Szenarioseite unter "Extras" → "Szenarien" nach Auswahl des Szenarios neben dem Namen der Registerkarte "Allgemein"". Ein anderer Weg, um es zu finden : Klicken Sie unter "Extras" → "Szenarien" auf "Übersicht"".
- **Aktion** : entspricht der Aktion, die Sie anwenden möchten. Verfügbare Befehle sind : "start "," stop "," deaktivieren "und" aktivieren "um das Szenario zu starten, zu stoppen, zu deaktivieren oder zu aktivieren.
- **Stichworte** \ [Optional \] : Wenn die Aktion "Start" ist, können Sie Tags an das Szenario übergeben (siehe Dokumentation zu den Szenarien) in der Form tags = toto% 3D1% 20tata% 3D2 (beachten Sie, dass% 20 einem Leerzeichen und% 3D entspricht = ).

##  Info / Aktionsbefehl

VoHier l'URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = cmd & id = \#ID\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = cmd & id=#ID#)

- **Ich würde** : entspricht der ID dessen, was Sie steuern möchten oder von dem Sie Informationen erhalten möchten.

Der einfachste Weg, um diese URL zu erhalten, ist das Aufrufen der Seite **Analyse → Zusammenfassung der Hausautomation**, Um nach der Bestellung zu suchen und dann die erweiterte Konfiguration (das "Zahnrad" -Symbol) zu öffnen, sehen Sie dort eine URL, die je nach Typ und Subtyp der Bestellung bereits alles enthält, was Sie benötigen.

> **Hinweis**
>
> Es ist möglich für das Feld \#ID\# mehrere Bestellungen gleichzeitig aufgeben. Dazu müssen Sie ein Array in json übergeben (ex% 5B12,58,23% 5D, beachten Sie, dass \ [und \] codiert werden müssen, daher% 5B und% 5D). Jeedoms Rückkehr wird ein Json sein.

> **Hinweis**
>
> Parameter müssen für URLs codiert werden, Sie können ein Tool verwenden, [Hier](https://meyerweb.com/eric/tools/dencoder/).

## Interaction

VoHier l'URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = interagiere & query = \#QUERY\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = interagieren & abfragen=#QUERY#)

- **Abfrage** : Frage an Jeedom zu stellen.
- **utf8** \ [Optional \] : teilt Jeedom mit, ob die Abfrage in utf8 codiert werden soll, bevor versucht wird zu antworten.
- **leer Antworten** \ [Optional \] : 0 für Jeedom, um zu antworten, auch wenn er es nicht verstanden hat, 1 sonst.
- **Profil** \ [Optional \] : Benutzername der Person, die die Interaktion initiiert.
- **antworten\_cmd** \ [Optional \] : Bestellnummer zur Beantwortung der Anfrage.

## Message

VoHier l'URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = message & category = \#CATEGORY\#&message=\#MESSAGE\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = message & category=#CATEGORY#&message=#MESSAGE#)

- **Kategorie** : Nachrichtenkategorie, die dem Nachrichtenzentrum hinzugefügt werden soll.
- **Botschaft** : Denken Sie bei der fraglichen Nachricht sorgfältig über die Codierung der Nachricht nach (Leerzeichen wird zu% 20, =% 3D…)). Sie können ein Werkzeug verwenden, [Hier](https://meyerweb.com/eric/tools/dencoder/).

## Objet

VoHier l'URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = Objekt](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = Objekt)

Gibt in json die Liste aller Jeedom-Objekte zurück.

## Equipement

VoHier l'URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = eqLogic & object\_id = \#OBJECT\_ID\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = eqLogic & object_id=#OBJECT_ID#)

- **Objekt\_id** : ID des Objekts, dessen Ausrüstung wir wiederherstellen möchten.

## Commande

VoHier l'URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = command & eqLogic\_id = \#EQLOGIC\_ID\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = command & eqLogic_id=#EQLOGIC_ID#)

- **eqLogic\_id** : ID der Ausrüstung, von der Bestellungen abgerufen werden sollen.

## Vollständige Daten

VoHier l'URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = fullData](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = fullData)

Gibt alle Objekte, Geräte, Befehle (und deren Wert, wenn es sich um Informationen handelt) in json zurück.

## Variable

VoHier l'URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#& type = variable & name = \#NAME\#&value=](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#& type = Variable & Name=#NAME#&value=)*WERT*

- **Name** : Name der Variablen, deren Wert gewünscht wird (Lesen des Werts).
- **WERT** \ [Optional \] : Wenn "Wert" angegeben ist, nimmt die Variable diesen Wert an (Schreiben eines Werts)).
