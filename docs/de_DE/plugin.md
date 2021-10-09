# Plugins-Verwaltung
**Plugins → Plugins-Verwaltung**

Diese Seite bietet Zugriff auf Plugin-Konfigurationen.
Sie können nämlich auch die Plugins manipulieren : herunterladen, aktualisieren und aktivieren,…

Es gibt daher eine Liste von Plugins in alphabetischer Reihenfolge und einen Link zum Markt.
- Deaktivierte Plugins sind ausgegraut.
- Plugins, die nicht in der Version sind *stabil* Wir haben einen orangefarbenen Punkt vor ihrem Namen.

Durch Klicken auf ein Plugin greifen Sie auf dessen Konfiguration zu. Oben finden Sie den Namen des Plugins, dann in Klammern den Namen in Jeedom (ID) und schließlich den Typ der installierten Version (Stable, Beta)).

> **Wichtig**
>
> Beim Herunterladen eines Plugins ist es standardmäßig deaktiviert. Sie müssen es also selbst aktivieren.

## Gestion

Hier haben Sie drei Tasten :

- **Markt synchronisieren** : Wenn Sie ein Plugin über einen Webbrowser in Ihrem Market-Konto installieren (außer Jeedom), können Sie eine Synchronisierung erzwingen, um es zu installieren.
- **Markt** : Öffnen Sie den Jeedom Market, wählen Sie ein Plugin aus und installieren Sie es auf Ihrem Jeedom.
- **Plugins** : Sie können hier ein Plugin von einer Github, Samba-Quelle installieren, ...

### Markt synchronisieren

Gehen Sie in einem Browser zu [Markt](https://market.jeedom.com).
Melden Sie sich bei Ihrem Konto an.
Klicken Sie auf ein Plugin und wählen Sie *Stabil installieren* oder *Installieren Sie die Beta* (wenn Ihr Marktkonto dies zulässt).

Wenn Ihr Marktkonto in Ihrem Jeedom korrekt konfiguriert ist (Konfiguration → Updates / Markt → Registerkarte Markt), können Sie auf klicken *Markt synchronisieren* oder warten Sie, bis es sich von selbst niedergelassen hat.

### Market

Um ein neues Plugin zu installieren, klicken Sie einfach auf die Schaltfläche "Market" (und Jeedom ist mit dem Internet verbunden). Nach einer kurzen Ladezeit erhalten Sie die Seite.

> **Trinkgeld**
>
> Sie müssen Ihre Marktkontoinformationen in der Administration eingegeben haben (Konfiguration → Updates / Markt → Registerkarte Markt), um die Plugins zu finden, die Sie beispielsweise bereits gekauft haben.

Oben im Fenster befinden sich Filter :
- **Free / Pay** : zeigt nur kostenlos oder kostenpflichtig an.
- **Amtlicher / Empfohlen** : Zeigt nur offizielle oder empfohlene Plugins an.
- **Dropdown-Menü Kategorie** : zeigt nur bestimmte Kategorien von Plugins an.
- **Suche** : Ermöglicht die Suche nach einem Plugin (im Namen oder in der Beschreibung)).
- **Benutzername** : Zeigt den Benutzernamen für die Verbindung zum Markt sowie den Verbindungsstatus an.

> **Trinkgeld**
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

### Plugins

Sie können Jeedom ein Plugin aus einer Datei oder einem Github-Repository hinzufügen. Dazu müssen Sie in der Jeedom-Konfiguration die entsprechende Funktion im Abschnitt "Updates / Market" aktivieren".

Achtung, beim Hinzufügen durch eine Zip-Datei muss der Name der Zip mit der ID des Plugins übereinstimmen und beim Öffnen der ZIP muss ein Plugin\_info-Ordner vorhanden sein.



## Meine Plugins

Durch Klicken auf das Symbol eines Plugins öffnen Sie dessen Konfigurationsseite.

> **Trinkgeld**
>
> Sie können bei gedrückter Strg-Taste oder Klick-Mitte die Konfiguration in einer neuen Browser-Registerkarte öffnen.

### Oben rechts einige Schaltflächen :

- **Dokumentation** : Ermöglicht den direkten Zugriff auf die Plugin-Dokumentationsseite.
- **Änderungsprotokoll** : Sehen wir uns das Plugin-Änderungsprotokoll an, falls vorhanden.
- **Details** : Ermöglicht es Ihnen, die Plugin-Seite auf dem Markt zu finden.
- **Entfernen** : Entfernen Sie das Plugin aus Ihrem Jeedom. Bitte beachten Sie, dass dadurch auch alle Geräte dauerhaft aus diesem Plugin entfernt werden.

### Unten links befindet sich ein Bereich **Zustand** mit :

- **Status** : Hier können Sie den Status des Plugins anzeigen (aktiv / inaktiv).
- **Kategorie** : Die Kategorie des Plugins, die angibt, in welchem Untermenü es sich befindet.
- **Autor** : Der Autor des Plugins, Link zum Markt und die Plugins dieses Autors.
- **Lizenz** : Gibt die Lizenz des Plugins an, bei dem es sich im Allgemeinen um AGPL handelt.

- **Aktion** : Ermöglicht das Aktivieren oder Deaktivieren des Plugins. Die Taste **Öffnen** Ermöglicht den direkten Zugriff auf die Plugin-Seite.
- **Ausführung** : Die Version des installierten Plugins.
- **Voraussetzungen** : Gibt die für das Plugin erforderliche Jeedom-Mindestversion an.


### Rechts finden wir die Gegend **Protokoll und Überwachung** was erlaubt zu definieren :

- Die Ebene der für das Plugin spezifischen Protokolle (dieselbe Möglichkeit finden Sie unter Administration → Konfiguration auf der Registerkarte Protokolle unten auf der Seite).
- Plugin-Protokolle anzeigen.
- Herzschlag : Alle 5 Minuten überprüft Jeedom, ob in den letzten X Minuten mindestens ein Plugin-Gerät kommuniziert hat (wenn Sie die Funktionalität deaktivieren möchten, geben Sie einfach 0 ein).
- Starten Sie den Dämon neu : Wenn der Hertbeat schief geht, startet Jeedom den Daemon neu.

Wenn das Plugin Abhängigkeiten und / oder einen Dämon hat, werden diese zusätzlichen Bereiche unter den oben genannten Bereichen angezeigt.

### Nebengebäude :

- **Familienname, Nachname** : Im Allgemeinen wird lokal sein.
- **Status** : Abhängigkeitsstatus, OK oder NOK.
- **Installation** : Ermöglicht das Installieren oder Neuinstallieren von Abhängigkeiten (wenn Sie dies nicht manuell tun und diese NOK sind, kümmert sich Jeedom nach einer Weile um sich selbst).
- **Letzte Installation** : Datum der letzten Abhängigkeitsinstallation.

### Dämon :

- **Familienname, Nachname** : Im Allgemeinen wird lokal sein.
- **Status** : Daemon-Status, OK oder NOK.
- **Aufbau** : OK, wenn alle Kriterien für die Ausführung des Dämons erfüllt sind oder die Ursache für die Blockierung angegeben ist.
- **(Neustarten** : Ermöglicht das Starten oder Neustarten des Dämons.
- **Anschlag** : Wird zum Stoppen des Dämons verwendet (nur in dem Fall, in dem die automatische Verwaltung deaktiviert ist).
- **Automatische Verwaltung** : Aktiviert oder deaktiviert die automatische Verwaltung (wodurch Jeedom den Dämon selbst verwalten und bei Bedarf neu starten kann. Sofern nicht anders angegeben, ist es ratsam, die automatische Verwaltung aktiv zu lassen).
- **Letzter Start** : Datum des letzten Starts des Daemons.

> **Trinkgeld**
>
> Einige Plugins haben einen Konfigurationsteil. Wenn dies der Fall ist, wird es unter den oben beschriebenen Abhängigkeits- und Dämonzonen angezeigt.
> In diesem Fall finden Sie Informationen zur Konfiguration des betreffenden Plugins in der Dokumentation.

### Unten befindet sich ein Funktionsbereich. Auf diese Weise können Sie feststellen, ob das Plugin eine der Jeedom-Kernfunktionen verwendet, z :

- **Interagieren** : Spezifische Wechselwirkungen.
- **Cron** : Ein Cron pro Minute.
- **Cron5** : Ein Cron alle 5 Minuten.
- **Cron10** : Ein Cron alle 10 Minuten.
- **Cron15** : Ein Cron alle 15 Minuten.
- **Cron30** : Ein Cron alle 30 Minuten.
- **CronHourly** : Ein Cron pro Stunde.
- **CronDaily** : Ein täglicher Cron.
- **deadcmd** : Ein Cron für tote Kommandeure.
- **Gesundheit** : Eine alte Gesundheit.

> **Trinkgeld**
>
> Wenn das Plugin eine dieser Funktionen verwendet, können Sie dies ausdrücklich verhindern, indem Sie das Kontrollkästchen &quot;Aktivieren&quot; deaktivieren, das daneben angezeigt wird.

### Panel

Wir finden einen Panel-Bereich, der die Anzeige des Panels auf dem Dashboard oder auf dem Handy aktiviert oder deaktiviert, wenn das Plugin eines anbietet.


