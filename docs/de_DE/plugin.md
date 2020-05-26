# Plugins-Verwaltung
Plugins → Plugins-Verwaltung

Diese Seite bietet Zugriff auf Plugin-Konfigurationen.
Sie können nämlich auch die Plugins manipulieren : herunterladen, aktualisieren und aktivieren,…

Es gibt daher eine Liste von Plugins in alphabetischer Reihenfolge und einen Link zum Markt.
- Deaktivierte Plugins sind ausgegraut.
- Plugins, die nicht in der Version sind *Stabil* Wir haben einen orangefarbenen Punkt vor ihrem Namen.

Durch Klicken auf ein Plugin greifen Sie auf dessen Konfiguration zu. Oben finden Sie den Namen des Plugins, dann in Klammern den Namen in Jeedom (ID) und schließlich den Typ der installierten Version (Stable, Beta)).

> **Wichtig**
>
> Beim Herunterladen eines Plugins ist es standardmäßig deaktiviert. Sie müssen es also selbst aktivieren.

> **Spitze**
>
> Sie können bei gedrückter Strg-Taste oder Klick-Mitte die Konfiguration in einer neuen Browser-Registerkarte öffnen.

## Plugin Konfiguration

### Oben rechts einige Schaltflächen :

- **Dokumentation** : Ermöglicht den direkten Zugriff auf die Plugin-Dokumentationsseite.
- **Änderungsprotokoll** : Ermöglicht das Anzeigen des Änderungsprotokolls des Plugins, falls vorhanden.
- **Details** : Ermöglicht es, die Plugin-Seite auf dem Markt zu finden.
- **Entfernen** : Entfernen Sie das Plugin aus Ihrem Jeedom. Bitte beachten Sie, dass dadurch auch alle Geräte dauerhaft aus diesem Plugin entfernt werden.

### Unten links befindet sich ein Bereich **Zustand** mit :

- **Status** : Hier können Sie den Status des Plugins anzeigen (aktiv / inaktiv).
- **Version** : Die Version des installierten Plugins.
- **Autor** : Plugin Autor.
- **Aktion** : Ermöglicht das Aktivieren oder Deaktivieren des Plugins.
- **Jeedom Version** : Gibt die für das Plugin erforderliche Jeedom-Mindestversion an.
- **Lizenz** : Gibt die Lizenz des Plugins an, bei dem es sich im Allgemeinen um AGPL handelt.

### Rechts finden wir die Gegend **Protokoll und Überwachung** was erlaubt zu definieren :

- Die Ebene der für das Plugin spezifischen Protokolle (dieselbe Möglichkeit finden Sie unter Administration → Konfiguration auf der Registerkarte Protokolle unten auf der Seite).
- Plugin-Protokolle anzeigen.
- Herzschlag : Alle 5 Minuten überprüft Jeedom, ob in den letzten X Minuten mindestens ein Plugin-Gerät kommuniziert hat (wenn Sie die Funktionalität deaktivieren möchten, geben Sie einfach 0 ein).
- Starten Sie den Dämon neu : Wenn der Hertbeat schief geht, startet Jeedom den Daemon neu.

Wenn das Plugin Abhängigkeiten und / oder einen Dämon hat, werden diese zusätzlichen Bereiche unter den oben genannten Bereichen angezeigt.

### Nebengebäude :

- **Name** : In der Regel wird lokal sein.
- **Status** : Abhängigkeitsstatus, OK oder NOK.
- **Installation** : Ermöglicht das Installieren oder Neuinstallieren von Abhängigkeiten (wenn Sie dies nicht manuell tun und diese NOK sind, kümmert sich Jeedom nach einer Weile um sich selbst).
- **Letzte Installation** : Datum der letzten Abhängigkeitsinstallation.

### Dämon :

- **Name** : In der Regel wird lokal sein.
- **Status** : Daemon-Status, OK oder NOK.
- **Konfiguration** : OK, wenn alle Kriterien für die Ausführung des Dämons erfüllt sind oder die Ursache für die Blockierung angegeben ist.
- **(Neustarten** : Ermöglicht das Starten oder Neustarten des Dämons.
- **Anschlag** : Wird zum Stoppen des Dämons verwendet (nur in dem Fall, in dem die automatische Verwaltung deaktiviert ist).
- **Automatische Verwaltung** : Aktiviert oder deaktiviert die automatische Verwaltung (wodurch Jeedom den Dämon selbst verwalten und bei Bedarf neu starten kann. Sofern nicht anders angegeben, ist es ratsam, die automatische Verwaltung aktiv zu lassen).
- **Letzter Start** : Datum des letzten Starts des Dämons.

> **Spitze**
>
> Einige Plugins haben einen Konfigurationsteil. Wenn dies der Fall ist, wird es unter den oben beschriebenen Abhängigkeits- und Dämonzonen angezeigt.
> In diesem Fall finden Sie Informationen zur Konfiguration des betreffenden Plugins in der Dokumentation.

### Unten befindet sich ein Funktionsbereich. Auf diese Weise können Sie feststellen, ob das Plugin eine der Jeedom-Kernfunktionen verwendet, z :

- **Interact** : Spezifische Wechselwirkungen.
- **Cron** : Ein Cron pro Minute.
- **Cron5** : Ein Cron alle 5 Minuten.
- **Cron10** : Ein Cron alle 10 Minuten.
- **Cron15** : Alle 15 Minuten ein Cron.
- **Cron30** : Alle 30 Minuten ein Cron.
- **CronHourly** : Eine Cron pro Stunde.
- **CronDaily** : Ein täglicher cron.
- **deadcmd** : Ein Cron für tote Kommandeure.
- **Gesundheit** : Eine alte Gesundheit.

> **Spitze**
>
> Wenn das Plugin eine dieser Funktionen verwendet, können Sie dies ausdrücklich verhindern, indem Sie das Kontrollkästchen &quot;Aktivieren&quot; deaktivieren, das daneben angezeigt wird.

### Panel

Wir finden einen Panel-Bereich, der die Anzeige des Panels auf dem Dashboard oder auf dem Handy aktiviert oder deaktiviert, wenn das Plugin eines anbietet.

## Plugin Installation

Um ein neues Plugin zu installieren, klicken Sie einfach auf die Schaltfläche "Market" (und Jeedom ist mit dem Internet verbunden). Nach einer kurzen Ladezeit erhalten Sie die Seite.

> **Spitze**
>
> Sie müssen Ihre Marktkontoinformationen in der Administration eingegeben haben (Konfiguration → Updates → Registerkarte Markt), um beispielsweise die bereits gekauften Plugins zu finden.

Oben im Fenster befinden sich Filter :
- **Free / Pay** : zeigt nur kostenlos oder kostenpflichtig an.
- **Amtlicher / Empfohlen** : Zeigt nur offizielle oder empfohlene Plugins an.
- **Dropdown-Menü Kategorie** : zeigt nur bestimmte Kategorien von Plugins an.
- **Suche** : Ermöglicht die Suche nach einem Plugin (im Namen oder in der Beschreibung)).
- **Benutzername** : Zeigt den Benutzernamen für die Verbindung zum Markt sowie den Verbindungsstatus an.

> **Spitze**
>
> Das kleine Kreuz setzt den betreffenden Filter zurück

Wenn Sie das gewünschte Plugin gefunden haben, klicken Sie einfach darauf, um die Datei aufzurufen. Dieses Blatt enthält insbesondere viele Informationen zum Plugin :

- Wenn es offiziell / empfohlen ist oder veraltet ist (Sie sollten auf jeden Fall vermeiden, veraltete Plugins zu installieren).
- 4 Aktionen :
    - **Stabil installieren** : ermöglicht die Installation des Plugins in seiner stabilen Version.
    - **Installieren Sie die Beta** : ermöglicht die Installation des Plugins in seiner Beta-Version (nur für Betatester).
    - **Installieren Sie pro** : ermöglicht die Installation der Pro-Version (sehr wenig verwendet).
    - **Entfernen** : Wenn das Plugin derzeit installiert ist, können Sie es mit dieser Schaltfläche entfernen.

Nachfolgend finden Sie die Beschreibung des Plugins, die Kompatibilität (wenn Jeedom eine Inkompatibilität feststellt, werden Sie benachrichtigt), die Meinungen zum Plugin (Sie können es hier notieren) und zusätzliche Informationen (der Autor, die Person, die es erstellt hat) das neueste Update, ein Link zum Dokument, die Anzahl der Downloads). Auf der rechten Seite finden Sie eine Schaltfläche &quot;Changelog&quot;, mit der Sie den gesamten Änderungsverlauf abrufen können. Eine Schaltfläche &quot;Dokumentation&quot;, die auf die Dokumentation des Plugins verweist. Dann haben Sie die verfügbare Sprache und die verschiedenen Informationen zum Datum der letzten stabilen Version.

> **Wichtig**
>
> Es wird wirklich nicht empfohlen, ein Beta-Plugin auf ein Nicht-Beta-Jeedom zu setzen, da dies zu vielen Betriebsproblemen führen kann.

> **Wichtig**
>
> Einige Plugins sind kostenpflichtig. In diesem Fall bietet Ihnen das Plugin den Kauf an. Sobald Sie fertig sind, müssen Sie ungefähr zehn Minuten warten (Zahlungsüberprüfungszeit) und dann zur Plugin-Datei zurückkehren, um sie normal zu installieren.

> **Spitze**
>
> Sie können Jeedom auch ein Plugin aus einer Datei oder einem Github-Repository hinzufügen. Aktivieren Sie dazu in der Jeedom-Konfiguration die entsprechende Funktion im Abschnitt "Updates und Dateien"". Wenn Sie dann die Maus ganz links halten und das Plugin-Seitenmenü anzeigen, können Sie auf "Aus einer anderen Quelle hinzufügen" klicken". Sie können dann die Quelle "Datei" auswählen". Achtung, beim Hinzufügen durch eine Zip-Datei muss der Name der Zip mit der ID des Plugins übereinstimmen und beim Öffnen der ZIP muss ein Plugin\_info-Ordner vorhanden sein.
