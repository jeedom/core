Im Untermenü Plugins-Verwaltung können Sie Plugins bearbeiten, außer
wissen : Laden Sie sie herunter, aktualisieren und aktivieren Sie sie usw

Plugins-Verwaltung 
===================

Sie können auf die Plugins-Seite über Plugins → Verwalten zugreifen
Plug-In. Sobald wir darauf klicken, finden wir die Liste von
Plugins in alphabetischer Reihenfolge und ein Link zum Markt. Plugins
deaktiviert sind ausgegraut.

> **Spitze**
>
> Setzen Sie die Maus wie an vielen Stellen auf Jeedom ganz links
> ruft ein Schnellzugriffsmenü auf (Sie können
> Lassen Sie es in Ihrem Profil immer sichtbar). Hier das Menü
> Ermöglicht die Sortierung der Liste der Plugins nach Kategorien.

Durch Klicken auf ein Plugin greifen Sie auf dessen Konfiguration zu. Oben du
Suchen Sie den Namen des Plugins und dann in Klammern den Namen in Jeedom
(ID) und schließlich die Art der installierten Version (stabil, Beta).

> **Wichtig**
>
> Beim Herunterladen eines Plugins ist es standardmäßig deaktiviert.
> Sie müssen es also selbst aktivieren.

Oben rechts einige Schaltflächen :

-   **Dokumentation** : Ermöglicht den direkten Zugriff auf die Seite von
    Plugin-Dokumentation

-   **Änderungsprotokoll** : ermöglicht das Anzeigen des Änderungsprotokolls des Plugins, falls vorhanden

-   **Auf den Markt schicken** : ermöglicht das Senden des Plugins auf dem Markt
    (Nur verfügbar, wenn Sie der Autor sind)

-   **Details** : ermöglicht es, die Plugin-Seite auf dem Markt zu finden

-   **Entfernen** : Entfernen Sie das Plugin aus Ihrem Jeedom. Sei vorsichtig, das
    entfernt auch dauerhaft alle Geräte von diesem Plugin

Unten links befindet sich ein Statusbereich mit :

-   **Status** : Hier können Sie den Status des Plugins anzeigen (aktiv / inaktiv)

-   **Version** : die Version des installierten Plugins

-   **Aktion** : Ermöglicht das Aktivieren oder Deaktivieren des Plugins

-   **Jeedom Version** : Minimale Jeedom-Version erforderlich
    für den Betrieb des Plugins

-   **Lizenz** : Gibt die Lizenz des Plugins an, die im Allgemeinen sein wird
    AGPL

Rechts finden wir die Protokoll- und Überwachungszone, die definiert werden kann 

-   die Ebene der für das Plugin spezifischen Protokolle (wir finden die gleiche Möglichkeit in
Administration → Konfiguration auf der Registerkarte Protokolle unten auf der Seite)

-   Siehe die Plugin-Protokolle

-   Herzschlag : Alle Freiheit überprüft alle 5 Minuten, ob in den letzten X Minuten mindestens ein Plugin-Gerät kommuniziert hat (wenn Sie die Funktionalität deaktivieren möchten, geben Sie einfach 0 ein)

-   Starten Sie den Dämon neu : Wenn der Herzschlag schief geht, startet Jeedom den Daemon neu

Wenn das Plugin Abhängigkeiten und / oder einen Daemon hat, diese Bereiche
Weitere werden unter den oben genannten Bereichen angezeigt.

Nebengebäude :

-   **Name** : in der Regel wird lokal sein

-   **Status** : wird Ihnen sagen, ob die Abhängigkeiten OK oder KO sind

-   **Installation** : wird installiert oder neu installiert
    Abhängigkeiten (wenn Sie es nicht manuell tun und sie sind
    KO, Jeedom wird nach einer Weile auf sich selbst aufpassen)

-   **Letzte Installation** : Datum der letzten Installation von
    Nebengebäude

Dämon :

-   **Name** : in der Regel wird lokal sein

-   **Status** : wird dir sagen, ob der Dämon in Ordnung oder KO ist

-   **Konfiguration** : wird in Ordnung sein, wenn alle Kriterien für den Dämon
    Wendungen sind erfüllt oder geben Anlass zur Blockierung

-   **(Neustarten** : Ermöglicht das Starten oder Neustarten des Dämons

-   **Anschlag** : erlaubt den Dämon aufzuhalten (nur für den Fall
    Die automatische Verwaltung ist deaktiviert)

-   **Automatische Verwaltung** : ermöglicht das Aktivieren oder Deaktivieren der Verwaltung
    automatisch (wodurch Jeedom den Daemon und den verwalten kann
    bei Bedarf wiederbeleben. Sofern nicht anders angegeben, ist dies ratsam
    Lassen Sie die automatische Verwaltung aktiv)

-   **Letzter Start** : Datum des letzten Starts des Dämons

> **Spitze**
>
> Einige Plugins haben einen Konfigurationsteil. Wenn ja, es
> wird unter den oben beschriebenen Abhängigkeiten und Dämonzonen angezeigt.
> In diesem Fall lesen Sie die Plugin-Dokumentation in
> Frage, wie man es konfiguriert.

Unten befindet sich ein Funktionsbereich. Dies ermöglicht es Ihnen zu sehen
wenn das Plugin eine der Jeedom-Kernfunktionen verwendet, wie z :

-   **Interact** : spezifische Wechselwirkungen

-   **Cron** : ein Cron pro Minute

-   **Cron5** : ein Cron alle 5 Minuten

-   **Cron15** : alle 15 Minuten ein Cron

-   **Cron30** : alle 30 Minuten ein Cron

-   **CronHourly** : eine Cron pro Stunde

-   **CronDaily** : ein täglicher cron

> **Spitze**
>
> Wenn das Plugin eine dieser Funktionen verwendet, können Sie dies speziell tun
> Verbieten Sie ihm dies, indem Sie das Kontrollkästchen &quot;Aktivieren&quot; deaktivieren
> als nächstes anwesend.

Schließlich finden wir einen Panel-Bereich, der oder aktiviert
Deaktivieren Sie die Anzeige des Panels im Dashboard oder auf dem Handy, wenn die
Plugin bietet eine.

Plugin Installation 
========================

Um ein neues Plugin zu installieren, klicken Sie einfach auf die Schaltfläche
"Markt "(und dass Jeedom mit dem Internet verbunden ist). Nach kurzer Zeit von
Beim Laden erhalten Sie die Seite.

> **Spitze**
>
> Sie müssen Ihre Marktkontoinformationen in eingegeben haben
> Administration (Konfiguration → Updates → Registerkarte Markt), um
> Finden Sie zum Beispiel die Plugins, die Sie bereits gekauft haben.

Oben im Fenster befinden sich Filter :

-   **Free / Pay** : zeigt nur frei oder an
    die zahlenden.

-   **Amtlicher / Empfohlen** : zeigt nur Plugins an
    Beamte oder Berater

-   **Installiert / Nicht installiert** : zeigt nur Plugins an
    installiert oder nicht installiert

-   **Dropdown-Menü Kategorie** : wird nur angezeigt
    bestimmte Plugin-Kategorien

-   **Suche** : ermöglicht es Ihnen, nach einem Plugin zu suchen (im Namen oder
    Beschreibung davon)

-   **Benutzername** : Zeigt den Benutzernamen an, der für das verwendet wird
    Verbindung zum Markt und den Status der Verbindung

> **Spitze**
>
> Das kleine Kreuz setzt den betreffenden Filter zurück

Wenn Sie das gewünschte Plugin gefunden haben, klicken Sie einfach auf
dieser, um seine Karte aufzurufen. Dieses Blatt gibt Ihnen viel
Informationen über das Plugin, einschließlich :

-   Wenn es offiziell / empfohlen ist oder wenn es veraltet ist (müssen Sie wirklich
    Vermeiden Sie die Installation veralteter Plugins)

-   4 Aktionen :

    -   **Stabil installieren** : ermöglicht die Installation des Plugins in seinem
        stabile Version

    -   **Installieren Sie die Beta** : ermöglicht die Installation des Plugins in seinem
        Beta-Version (nur für Beta-Tester)

    -   **Installieren Sie pro** : ermöglicht die Installation der Pro-Version (sehr
        wenig benutzt)

    -   **Entfernen** : Wenn das Plugin derzeit installiert ist, ist dies
        Schaltfläche, um es zu löschen

Nachfolgend finden Sie die Beschreibung des Plugins, die Kompatibilität
(Wenn Jeedom eine Inkompatibilität feststellt, werden Sie darüber informiert
auf dem Plugin (Sie können es hier bewerten) und Informationen
komplementär (der Autor, die Person, die das letzte Update vorgenommen hat
Tag, Link zum Dokument, Anzahl der Downloads). Rechts
Sie finden einen "Changelog" -Button, mit dem Sie alles haben können
Änderungsverlauf, eine Schaltfläche "Dokumentation", die zurückkehrt
zur Plugin-Dokumentation. Dann haben Sie die Sprache zur Verfügung
und die verschiedenen Informationen zum Datum der letzten stabilen Version.

> **Wichtig**
>
> Es wird wirklich nicht empfohlen, ein Beta-Plugin auf ein zu setzen
> Jeedom nicht Beta, viele Betriebsprobleme können
> Ergebnis.

> **Wichtig**
>
> Einige Plugins sind kostenpflichtig, in diesem Fall wird das Plugin-Blatt
> wird anbieten, es zu kaufen. Sobald dies erledigt ist, warten Sie auf a
> zehn Minuten (Zahlungsvalidierungszeit), dann zurück
> auf dem Plugin Sheet, um es normal zu installieren.

> **Spitze**
>
> Sie können Jeedom auch ein Plugin aus einer Datei oder hinzufügen
> aus einem Github-Repository. Dies erfordert in der Konfiguration von
> Jeedom, aktiviere die entsprechende Funktion in den "Updates und
> fichiers". Dies ist dann möglich, indem Sie die Maus vollständig platzieren
> Klicken Sie links und rufen Sie das Plugin-Seitenmenü auf
> auf "Aus einer anderen Quelle hinzufügen". Sie können dann die auswählen
> Quelle "Datei". Achtung, im Falle der Hinzufügung durch eine Datei
> zip, der zip-Name muss mit der Plugin-ID und von übereinstimmen
> Beim Öffnen der ZIP-Datei muss ein Plugin\_info-Ordner vorhanden sein.
