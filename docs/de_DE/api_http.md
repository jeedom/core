# HTTP-API

Jeedom bietet Entwicklern und Benutzern eine vollständige API, mit der sie Jeedom von jedem verbundenen Objekt aus steuern können.

Es stehen zwei APIs zur Verfügung : ein entwicklerorientierter JSON RPC 2-Pilot.0 und eine andere über URL und HTTP-Anfrage.

Diese API ist sehr einfach durch einfache HTTP-Anfragen über URL zu verwenden.

> **Notiz**
>
> Für all diese Dokumentationen entspricht \ #IP \ _JEEDOM \ # Ihrer Jeedom-Zugriffs-URL. Dies ist (sofern Sie nicht mit Ihrem lokalen Netzwerk verbunden sind) die Internetadresse, mit der Sie von außen auf Jeedom zugreifen.

> **Notiz**
>
> In dieser gesamten Dokumentation entspricht \ #API \ _KEY \ # Ihrem API-Schlüssel, der für Ihre Installation spezifisch ist. Um es zu finden, gehen Sie zum Menü "Allgemein" → "Konfiguration" → Registerkarte "Allgemein"".

## Szenario

Hier ist die URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=scenario&Identifikation=\#ID\#&Aktion=\#ACTION\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=scenario&Identifikation=#ID#&Aktion=#ACTION#)

- **Identifikation** : entspricht Ihrer Szenario-ID. Die ID finden Sie auf der entsprechenden Szenarioseite unter "Extras" → "Szenarien" nach Auswahl des Szenarios neben dem Namen der Registerkarte "Allgemein"". Ein anderer Weg, um es zu finden : Klicken Sie unter "Extras" → "Szenarien" auf "Übersicht"".
- **Aktion** : entspricht der Aktion, die Sie anwenden möchten. Verfügbare Befehle sind : "Start "," Stopp "," Deaktivieren "und" Aktivieren ", um das Szenario zu starten, zu stoppen, zu deaktivieren oder zu aktivieren.
- **Tags** \ [Optional \] : Wenn die Aktion &quot;Start&quot; ist, können Sie Tags an das Szenario übergeben (siehe Dokumentation zu den Szenarien) in der Form Tags = toto% 3D1% 20tata% 3D2 (beachten Sie, dass% 20 einem Leerzeichen und% 3D entspricht =).

##  Info / Aktionsbefehl

Hier ist die URL = [http://\#IP\_JEEDOM\#/jeedom/core/api/jeeApi.php?apikey=\#APIKEY\#&type=cmd&Identifikation=\#ID\#](http://#IP_JEEDOM#/jeedom/core/api/jeeApi.php?apikey=#APIKEY#&type=cmd&Identifikation=#ID#)

- **Identifikation** : entspricht der ID dessen, was Sie steuern möchten oder von dem Sie Informationen erhalten möchten.

Der einfachste Weg, um diese URL zu erhalten, ist das Aufrufen der Seite **Analyse → Zusammenfassung der Hausautomation**, Um nach der Bestellung zu suchen und dann die erweiterte Konfiguration (das "Zahnrad" -Symbol) zu öffnen, sehen Sie dort eine URL, die je nach Typ und Subtyp der Bestellung bereits alles enthält, was Sie benötigen.

> **Notiz**
>
> Es ist möglich, dass das Feld \ #ID \ # mehrere Befehle gleichzeitig platziert. Dazu müssen Sie ein Array in json übergeben (z. B.% 5B12,58,23% 5D, beachten Sie, dass \ [und \] codiert werden müssen, daher% 5B und% 5D).. Jeedoms Rückkehr wird ein Json sein.

> **Notiz**
>
> Die Parameter müssen für die URL codiert sein. Sie können ein Tool [hier] (https) verwenden://meyerweb.com/eric/tools/dencoder/).

## Interaktion

Hier ist die URL = [http://\#IP\_JEEDOM\#/jeedom/core/api/jeeApi.php?apikey=\#APIKEY\#&type=interact&Abfrage=\#QUERY\#](http://#IP_JEEDOM#/jeedom/core/api/jeeApi.php?apikey=#APIKEY#&type=interact&Abfrage=#QUERY#)

- **Abfrage** : Frage an Jeedom zu stellen.
- **utf8** \ [Optional \] : teilt Jeedom mit, ob die Abfrage in utf8 codiert werden soll, bevor versucht wird zu antworten.
- **emptyReply** \ [Optional \] : 0 für Jeedom, um zu antworten, auch wenn er es nicht verstanden hat, 1 sonst.
- **Profil** \ [Optional \] : BenutzerName der Person, die die Interaktion initiiert.
- **antworten \ _cmd** \ [Optional \] : Bestellnummer zur Beantwortung der Anfrage.

## Nachricht

Hier ist die URL = [http://\#IP\_JEEDOM\#/jeedom/core/api/jeeApi.php?apikey=\#APIKEY\#&type=Nachricht&Kategorie=\#CATEGORY\#&Nachricht=\#MESSAGE\#](http://#IP_JEEDOM#/jeedom/core/api/jeeApi.php?apikey=#APIKEY#&type=Nachricht&Kategorie=#CATEGORY#&Nachricht=#MESSAGE#)

- **Kategorie** : Nachrichtenkategorie, die dem Nachrichtenzentrum hinzugefügt werden soll.
- **Nachricht** : Denken Sie bei der betreffenden Nachricht sorgfältig über die Codierung der Nachricht nach (Leerzeichen werden zu% 20, =% 3D…).. Sie können ein Tool verwenden, [hier] (https://meyerweb.com/eric/tools/dencoder/).

## Objekt

Hier ist die URL = [http://\#IP\_JEEDOM\#/jeedom/core/api/jeeApi.php?apikey=\#APIKEY\#&type=object](http://#IP_JEEDOM#/jeedom/core/api/jeeApi.php?apikey=#APIKEY#&type=object)

Gibt in json die Liste aller Jeedom-Objekte zurück.

## Ausrüstung

Hier ist die URL = [http://\#IP\_JEEDOM\#/jeedom/core/api/jeeApi.php?apikey=\#APIKEY\#&type=eqLogic&Objekt \ _Identifikation=\#OBJECT\_ID\#](http://#IP_JEEDOM#/jeedom/core/api/jeeApi.php?apikey=#APIKEY#&type=eqLogic&object_Identifikation=#OBJECT_ID#)

- **Objekt \ _Identifikation** : ID des Objekts, dessen Ausrüstung wir wiederherstellen möchten.

## bestellen

Hier ist die URL = [http://\#IP\_JEEDOM\#/jeedom/core/api/jeeApi.php?apikey=\#APIKEY\#&type=command&eqLogic \ _Identifikation=\#EQLOGIC\_ID\#](http://#IP_JEEDOM#/jeedom/core/api/jeeApi.php?apikey=#APIKEY#&type=command&eqLogic_Identifikation=#EQLOGIC_ID#)

- **eqLogic \ _Identifikation** : ID der Ausrüstung, von der Bestellungen abgerufen werden sollen.

## Vollständige Daten

Hier ist die URL = [http://\#IP\_JEEDOM\#/jeedom/core/api/jeeApi.php?apikey=\#APIKEY\#&type=fullData](http://#IP_JEEDOM#/jeedom/core/api/jeeApi.php?apikey=#APIKEY#&type=fullData)

Gibt alle Objekte, Geräte, Befehle (und deren Wert, wenn es sich um Informationen handelt) in json zurück.

## Variable

Hier ist die URL = [http://\#IP\_JEEDOM\#/jeedom/core/api/jeeApi.php?apikey=\#APIKEY\#&type=variable&Name=\#NAME\#&Wert=](http://#IP_JEEDOM#/jeedom/core/api/jeeApi.php?apikey=#APIKEY#&type=variable&Name=#NAME#&Wert=)*VALUE*

- **Name** : Name der Variablen, deren Wert gewünscht wird (Lesen des Werts).
- **Wert** \ [Optional \] : Wenn &quot;Wert&quot; angegeben ist, nimmt die Variable diesen Wert an (Schreiben eines Wertes)..
