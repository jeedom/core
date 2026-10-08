# Verwaltung von Plugins
**Plugins → Plugin-Verwaltung**

Auf dieser Seite können Sie auf die Einstellungen der Plugins zugreifen.
Sie können Plugins auch verwalten, d. h.: sie herunterladen, aktualisieren und aktivieren, …​

Dort findet man also die Liste der Plugins in alphabetischer Reihenfolge sowie einen Link zum Market.
- Deaktivierte Plugins sind ausgegraut.
- Plugins, die nicht in der *stabile* Version vorliegen, sind mit einem orangefarbenen Punkt vor ihrem Namen gekennzeichnet.

Wenn Sie auf ein Plugin klicken, gelangen Sie zu dessen Konfiguration. Oben sehen Sie den Namen des Plugins, dann in Klammern dessen Namen in Jeedom (ID) und schließlich den Typ der installierten Version (stabil, Beta).

> **Wichtig**
>
> Wenn Sie ein Plugin herunterladen, ist dieses standardmäßig deaktiviert. Sie müssen es daher selbst aktivieren.

## Verwaltung

Hier sehen Sie drei Schaltflächen:

- **Market synchronisieren**: Wenn Sie ein Plugin über einen Webbrowser in Ihrem Market-Konto (außerhalb von Jeedom) installieren, können Sie eine Synchronisierung erzwingen, um es zu installieren.
- **Market**: Öffnet den Jeedom Market, um ein Plugin auszuwählen und auf Ihrem Jeedom zu installieren.
- **Plugins**: Hier können Sie ein Plugin aus einer Github-, Samba- oder einer anderen Quelle installieren.

### Market synchronisieren

Rufen Sie über einen Browser die Seite [Markt](https://market.jeedom.com).
Melden Sie sich bei Ihrem Konto an.
Klicken Sie auf ein Plugin und wählen Sie dann *Stabile Version installieren* oder *Beta-Version installieren* (sofern Ihr Market-Konto dies zulässt).

Wenn Ihr Market-Konto in Ihrem Jeedom korrekt eingerichtet ist (Einstellungen → Updates/Market → Registerkarte „Market“), können Sie auf *Market synchronisieren* klicken oder warten, bis die Installation automatisch erfolgt.

### Markt

Um ein neues Plugin zu installieren, klicken Sie einfach auf die Schaltfläche „Market“ (vorausgesetzt, Jeedom ist mit dem Internet verbunden). Nach einer kurzen Ladezeit wird die Seite angezeigt.

> **Tipp**
>
> Sie müssen Ihre Market-Kontodaten im Admin-Bereich eingegeben haben (Konfiguration → Updates/Market → Registerkarte „Market“), um beispielsweise die Plugins zu finden, die Sie bereits gekauft haben.

Oben im Fenster finden Sie Filter:
- **Kostenlos/Kostenpflichtig**: Hiermit können Sie wählen, ob nur kostenlose oder nur kostenpflichtige Angebote angezeigt werden sollen.
- **Offiziell/Empfohlen**: Hiermit werden nur offizielle oder empfohlene Plugins angezeigt.
- **Dropdown-Menü „Kategorie“**: Ermöglicht es, nur bestimmte Plugin-Kategorien anzuzeigen.
- **Suchen**: Ermöglicht die Suche nach einem Plugin (im Namen oder in der Beschreibung).
- **Benutzername**: Zeigt den Benutzernamen an, der für die Anmeldung beim Market verwendet wird, sowie den Verbindungsstatus.

> **Tipp**
>
> Mit dem kleinen Kreuz können Sie den betreffenden Filter zurücksetzen.

Sobald Sie das gewünschte Plugin gefunden haben, klicken Sie einfach darauf, um die Detailansicht aufzurufen. Diese Detailansicht enthält zahlreiche Informationen zum Plugin, darunter:

- Ob es offiziell ist/empfohlen wird oder veraltet ist (die Installation veralteter Plugins sollte unbedingt vermieden werden).
- 4 Schritte:
    - **Stabile Version installieren**: Ermöglicht die Installation der stabilen Version des Plugins.
    - **Beta installieren**: Ermöglicht die Installation der Beta-Version des Plugins (nur für Betatester).
    - **Pro-Version installieren**: Ermöglicht die Installation der Pro-Version (wird sehr selten verwendet).
    - **Entfernen**: Wenn das Plugin derzeit installiert ist, können Sie es über diese Schaltfläche entfernen.

Darunter finden Sie die Beschreibung des Plugins, Angaben zur Kompatibilität (falls Jeedom eine Inkompatibilität feststellt, wird Sie das System darauf hinweisen), Bewertungen des Plugins (hier können Sie es bewerten) sowie weitere Informationen (Autor, Person, die das letzte Update durchgeführt hat, Link zur Dokumentation, Anzahl der Downloads). Auf der rechten Seite finden Sie eine Schaltfläche „Changelog“, über die Sie den gesamten Änderungsverlauf einsehen können, sowie eine Schaltfläche „Dokumentation“, die zur Dokumentation des Plugins führt. Anschließend werden die verfügbare Sprache und verschiedene Informationen zum Datum der letzten stabilen Version angezeigt.

> **Wichtig**
>
> Es ist wirklich nicht empfehlenswert, ein Beta-Plugin auf einem Jeedom-System zu installieren, das nicht in der Beta-Phase ist, da dies zu zahlreichen Funktionsstörungen führen kann.

> **Wichtig**
>
> Einige Plugins sind kostenpflichtig. In diesem Fall wird Ihnen auf der Plugin-Seite angeboten, das Plugin zu kaufen. Sobald Sie den Kauf abgeschlossen haben, müssen Sie etwa zehn Minuten warten (Zeit für die Zahlungsbestätigung) und dann zur Plugin-Seite zurückkehren, um es wie gewohnt zu installieren.

### Plugins

Sie können ein Plugin zu Jeedom hinzufügen, entweder aus einer Datei oder aus einem GitHub-Repository. Dazu müssen Sie in der Jeedom-Konfiguration im Bereich „Updates/Market“ die entsprechende Funktion aktivieren.

Bitte beachten Sie: Wenn Sie eine ZIP-Datei hinzufügen, muss der Name der ZIP-Datei mit der ID des Plugins übereinstimmen, und beim Öffnen der ZIP-Datei muss ein Ordner „plugin\_info“ vorhanden sein.

## Meine Plugins

Wenn Sie auf das Symbol eines Plugins klicken, öffnen Sie dessen Konfigurationsseite.

> **Tipp**
>
> Sie können mit Strg+Klick oder einem Klick mit der mittleren Maustaste die Konfiguration in einem neuen Browser-Tab öffnen.

### Oben rechts befinden sich einige Schaltflächen:

- **Details**: Hier gelangen Sie zur Seite des Plugins im Market.
- **Dokumentation**: Ermöglicht den direkten Zugriff auf die Dokumentationsseite des Plugins.
- **Changelog**: Zeigt das Changelog des Plugins an, sofern vorhanden.
- **Support**: Ermöglicht es, automatisch eine Supportanfrage im Forum zu erstellen.
- **Löschen**: Löscht das Plugin aus Ihrem Jeedom. Achtung: Dadurch werden auch alle Geräte dieses Plugins endgültig gelöscht.

### Unten links befindet sich ein Bereich „**Status**“ mit:

- **Status**: Hier können Sie den Status des Plugins einsehen (aktiv / inaktiv).
- **Kategorie**: Die Kategorie des Plugins, die angibt, in welchem Untermenü es zu finden ist.
- **Autor**: Der Autor des Plugins, Link zum Marktplatz und zu den Plugins dieses Autors.
- **Lizenz**: Gibt die Lizenz des Plugins an, bei der es sich in der Regel um die AGPL handelt.

- **Aktion**: Hiermit können Sie das Plugin aktivieren oder deaktivieren. Über die Schaltfläche **Öffnen** gelangen Sie direkt zur Seite des Plugins.
- **Version**: Die installierte Version des Plugins.
- **Voraussetzungen**: Gibt die für das Plugin erforderliche Mindestversion von Jeedom an.


### Auf der rechten Seite befindet sich der Bereich **Protokollierung und Überwachung**, in dem Folgendes festgelegt werden kann:

- Die plugin-spezifische Protokollstufe (diese Option finden Sie auch unter „Verwaltung“ → „Konfiguration“ auf der Registerkarte „Protokolle“ am Ende der Seite).
- Die Protokolle des Plugins anzeigen.
- Heartbeat: Alle 5 Minuten prüft Jeedom, ob mindestens ein Gerät des Plugins in den letzten X Minuten Daten übertragen hat (wenn Sie diese Funktion deaktivieren möchten, geben Sie einfach 0 ein).
- Daemon neu starten: Wenn der Heartbeat fehlschlägt, startet Jeedom den Daemon neu.

Wenn das Plugin Abhängigkeiten und/oder einen Daemon hat, werden diese zusätzlichen Felder unter den oben genannten Feldern angezeigt.

### Abhängigkeiten:

- **Name**: In der Regel lokal.
- **Status**: Status der Abhängigkeiten, OK oder NOK.
- **Installation**: Ermöglicht die Installation oder Neuinstallation von Abhängigkeiten (falls Sie dies nicht manuell tun und diese fehlerhaft sind, übernimmt Jeedom dies nach einer Weile automatisch).
- **Letzte Installation**: Datum der letzten Installation der Abhängigkeiten.

### Dämon:

- **Name**: In der Regel lokal.
- **Status**: Status des Daemons, OK oder NOK.
- **Konfiguration**: OK, wenn alle Voraussetzungen für den Betrieb des Daemons erfüllt sind, oder gibt den Grund für die Blockierung an.
- **(Neu)Starten**: Ermöglicht das Starten oder Neustarten des Daemons.
- **Beenden**: Ermöglicht das Beenden des Daemons (nur wenn die automatische Verwaltung deaktiviert ist).
- **Automatische Verwaltung**: Hiermit können Sie die automatische Verwaltung aktivieren oder deaktivieren (dadurch kann Jeedom den Daemon selbst verwalten und bei Bedarf neu starten. Sofern nicht anders angegeben, wird empfohlen, die automatische Verwaltung aktiviert zu lassen).
- **Letzter Start**: Datum des letzten Starts des Daemons.

> **Tipp**
>
> Einige Plugins verfügen über einen Konfigurationsbereich. Ist dies der Fall, wird dieser unter den oben beschriebenen Bereichen „Abhängigkeiten“ und „Daemon“ angezeigt.
> In diesem Fall sollten Sie in der Dokumentation des jeweiligen Plugins nachlesen, wie es konfiguriert wird.

### Darunter befindet sich ein Bereich mit den Funktionen. Hier kann man sehen, ob das Plugin eine der Jeedom-Kernfunktionen nutzt, wie zum Beispiel:

- **Interact**: Spezifische Interaktionen.
- **Cron**: Ein Cron-Job pro Minute.
- **Cron5**: Ein Cron-Job alle 5 Minuten.
- **Cron10**: Ein Cron-Job alle 10 Minuten.
- **Cron15**: Ein Cron-Job alle 15 Minuten.
- **Cron30**: Ein Cron-Job alle 30 Minuten.
- **CronHourly**: Ein Cron-Job jede Stunde.
- **CronDaily**: Ein täglicher Cron-Job.
- **deadcmd**: Ein Cron-Job für inaktive Befehle.
- **health**: Ein Cron-Job für „health“.

> **Tipp**
>
> Wenn das Plugin eine dieser Funktionen nutzt, können Sie dies gezielt unterbinden, indem Sie das nebenstehende Kontrollkästchen „Aktivieren“ deaktivieren.

### Bedienfeld

Es gibt einen Abschnitt „Panel“, über den Sie die Anzeige des Panels auf dem Dashboard oder auf Mobilgeräten aktivieren oder deaktivieren können, sofern das Plugin diese Funktion bietet.
