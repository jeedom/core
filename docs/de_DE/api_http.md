# HTTP-API

Jeedom stellt Entwicklern und Nutzern eine umfassende API zur Verfügung, um Jeedom von jedem vernetzten Objekt aus steuern zu können.

Es stehen zwei APIs zur Verfügung: eine entwicklerorientierte API, die über JSON-RPC 2.0 gesteuert wird, und eine weitere über URL und HTTP-Anfragen.

Diese API lässt sich ganz einfach über einfache HTTP-Anfragen per URL nutzen.

> **Hinweis**
>
> In dieser gesamten Dokumentation entspricht \#IP\_JEEDOM\# Ihrer URL für den Zugriff auf Jeedom. Dabei handelt es sich (sofern Sie nicht mit Ihrem lokalen Netzwerk verbunden sind) um die Internetadresse, über die Sie von außen auf Jeedom zugreifen.

> **Hinweis**
>
> In dieser gesamten Dokumentation steht \#API\_KEY\# für Ihren API-Schlüssel, der für Ihre Installation spezifisch ist. Um ihn zu finden, gehen Sie im Menü auf „Allgemein“ → „Konfiguration“ → Registerkarte „Allgemein“.

> **Hinweis**
>
> Bei POST-Anfragen kann jeder Abfrageparameter im Request-Body im Format „form-data“ oder „x-www-form-urlencoded“ übermittelt werden.
> Abfrageparameter und der Inhalt des Body-Teils können gemeinsam verwendet werden, wobei zu beachten ist, dass Abfrageparameter Vorrang vor dem Inhalt des Body-Teils haben.

## Szenario

Hier ist die URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=scenario&id=\#ID\#&action=\#ACTION\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=scenario&id=#ID#&action=#ACTION#)

- **id**: Entspricht der ID Ihres Szenarios. Die ID finden Sie auf der Seite des betreffenden Szenarios unter „Extras“ → „Szenarien“, nachdem Sie das Szenario ausgewählt haben, neben dem Namen der Registerkarte „Allgemein“. Eine weitere Möglichkeit, sie zu finden: Klicken Sie unter „Extras“ → „Szenarien“ auf „Übersicht“.
- **Aktion**: Bezeichnet die Aktion, die Sie ausführen möchten. Die verfügbaren Befehle sind: „start“, „stop“, „disable“ und „enable“, um das Szenario jeweils zu starten, zu stoppen, zu deaktivieren oder zu aktivieren.
- **Tags** \[optional\]: Wenn die Aktion „start“ lautet, können Sie dem Szenario Tags übergeben (siehe Dokumentation zu Szenarien) in der Form tags=toto%3D1%20tata%3D2 (beachten Sie, dass %20 einem Leerzeichen und %3D dem Zeichen = entspricht).

> **Hinweis**
>
> Versuchen Sie nicht, „php://input“ zu verwenden, um Daten an Ihr Skript zu übergeben – dafür sind die Tags da.

##  Info/Bestellvorgang

Hier ist die URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=cmd&id=\#ID\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=cmd&id=#ID#)

- **id**: Entspricht der ID des Geräts, das Sie steuern möchten oder von dem Sie Informationen erhalten möchten.

Am einfachsten erhalten Sie diese URL, indem Sie auf die Seite **Analyse → Hausautomationsübersicht** gehen, den Befehl suchen und dann dessen erweiterte Einstellungen (das „Zahnrad“-Symbol) öffnen. Dort sehen Sie eine URL, die je nach Typ und Untertyp des Befehls bereits alle erforderlichen Angaben enthält.

> **Hinweis**
>
> Im Feld \#ID\# können mehrere Befehle auf einmal übermittelt werden. Dazu muss ein Array im JSON-Format übergeben werden (z. B. %5B12,58,23%5D; dabei ist zu beachten, dass \[ und \] kodiert werden müssen, daher %5B und %5D). Die Rückmeldung von Jeedom erfolgt im JSON-Format.

> **Hinweis**
>
> Die Parameter müssen für die URLs kodiert werden. Sie können dazu ein Tool verwenden, [hier](https://meyerweb.com/eric/tools/dencoder/).

## Interaktion

Hier ist die URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=interact&query=\#QUERY\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=interact&query=#QUERY#)

- **query**: Frage, die an Jeedom gestellt werden soll.
- **utf8** \[optional\]: Teilt Jeedom mit, ob die Abfrage vor der Beantwortung in UTF-8 kodiert werden soll.
- **emptyReply** \[optional\]: 0, damit Jeedom auch dann antwortet, wenn es den Befehl nicht verstanden hat; andernfalls 1.
- **Profil** \[optional\]: Benutzername der Person, die die Interaktion auslöst.
- **reply\_cmd** \[optional\]: ID des Befehls, der zur Beantwortung der Anfrage verwendet werden soll.

## Nachricht

Hier ist die URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=message&category=\#CATEGORY\#&message=\#MESSAGE\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=message&category=#CATEGORY#&message=#MESSAGE#)

- **Kategorie**: Kategorie der Nachricht, die dem Nachrichtencenter hinzugefügt werden soll.
- **Nachricht**: Die betreffende Nachricht. Achten Sie darauf, die Nachricht korrekt zu kodieren (Leerzeichen wird zu %20, = zu %3D…). Sie können ein Tool verwenden, [hier](https://meyerweb.com/eric/tools/dencoder/).

## Objekt

Hier ist die URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=object](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=object)

Gibt die Liste aller Jeedom-Objekte im JSON-Format zurück.

## Ausstattung

Hier ist die URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=eqLogic&object\_id=\#OBJECT\_ID\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=eqLogic&object_id=#OBJECT_ID#)

- **object\_id**: ID der Objekte, deren Geräte abgerufen werden sollen.

## Bestellung

Hier ist die URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=command&eqLogic\_id=\#EQLOGIC\_ID\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=command&eqLogic_id=#EQLOGIC_ID#)

- **eqLogic\_id**: ID des Geräts, von dem die Befehle abgerufen werden sollen.

## Vollständige Daten

Hier ist die URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=fullData](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=fullData)

Gibt alle Objekte, Geräte und Befehle (sowie deren Werte, sofern es sich um Informationen handelt) im JSON-Format zurück.

## Variable

Hier ist die URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=variable&name=\#NAME\#&value=](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=variable&name=#NAME#&value=)*VALUE*

- **name**: Name der Variablen, deren Wert abgerufen werden soll (Auslesen des Werts).
- **value** \[optional\]: Wenn „value“ angegeben wird, nimmt die Variable diesen Wert an (Schreiben eines Werts).
