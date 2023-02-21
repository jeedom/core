# Änderungsprotokoll Jeedom V4.4


### 4.4 : Voraussetzungen

- Debian 10 Buster
- PHP7.3

### 4.4 : Neuigkeiten / Verbesserungen

- **Historisch** : Verlaufsmodal und Verlaufsseite ermöglichen die Verwendung von Schaltflächen *Woche, Monat, Jahr* um einen größeren Verlauf dynamisch neu zu laden.
- **Bildauswahlfenster** : Kontextmenü zum Senden von Bildern und Erstellen, Umbenennen oder Löschen eines Ordners hinzugefügt.
- **Symbolauswahlfenster** : Möglichkeit zum Hinzufügen eines „Pfad“-Parameters beim Aufrufen von „jeedomUtils.chooseIcon` durch ein Plugin, um nur seine Symbole anzuzeigen.
- **Armaturenbrett** : Möglichkeit, mehrere Objekte nebeneinander anzuzeigen *(Einstellungen → System → Konfiguration / Schnittstelle)*.
- **Armaturenbrett** : Das Kachel-Bearbeitungsfenster des Bearbeitungsmodus ermöglicht das Umbenennen von Befehlen.
- **Armaturenbrett** : Im Tabellenlayout Möglichkeit zum Einfügen von HTML-Attributen *(insbesondere colspan/rowspan)* für jede Zelle.
- **Ausrüstung** : Möglichkeit, die Widget-Vorlagen von Plugins zu deaktivieren, die sie verwenden, um zur Jeedom-Standardanzeige zurückzukehren *(Gerätekonfigurationsfenster)*.
- **Ausrüstung** : Inaktiv gemachte Ausrüstung verschwindet automatisch von allen Seiten. Reaktivierte Ausrüstung erscheint wieder auf dem Dashboard, wenn das übergeordnete Objekt bereits vorhanden ist.
- **Ausrüstung** : Unsichtbar gemachte Geräte verschwinden automatisch vom Dashboard. Die neu angezeigte Ausrüstung erscheint erneut auf dem Dashboard, wenn das übergeordnete Objekt bereits vorhanden ist.
- **Analyse > Ausrüstung / Ausrüstung in Alarmbereitschaft** : Geräte, die in einen Alarmzustand versetzt werden, werden automatisch angezeigt, und Geräte, die einen Alarmzustand verlassen haben, verschwinden automatisch.
- **Nachrichtenzentrum** : Kernmeldungen zu Anomalien informieren jetzt über eine Aktion, z. B. einen Link zum Öffnen des anstößigen Szenarios, oder Ausrüstung, Plugin-Konfiguration usw.
- **Objekt** : Das Löschen oder Erstellen einer Zusammenfassung führt zur Aktualisierung der globalen Zusammenfassung und des Betreffs.
- **Extras > Ersetzen** : Dieses Tool bietet nun einen Modus *Kopieren*, ermöglicht das Kopieren der Konfigurationen von Geräten und Befehlen, ohne sie in den Szenarien und anderen zu ersetzen.
- **Zeitleiste** : Die Timeline lädt jetzt die ersten 35 Ereignisse. Die folgenden Ereignisse werden beim Scrollen unten auf der Seite geladen.
- **Verwaltung** : Möglichkeit, Aktionen bei Fehler oder Befehlsalarm zu unterscheiden.
- **Verwaltung** : Möglichkeit, Standardbefehls-Widgets festzulegen.
- **Armaturenbrett** : Möglichkeit auf der Objektkonfigurationsseite, jeedom zu bitten, die Ausrüstung entsprechend ihrer Verwendung neu zu ordnen.
- **Thema** : Möglichkeit, das Thema direkt aus der URL auszuwählen (durch Hinzufügen von &theme=Dark oder &theme=Light).
- **Bericht** : Möglichkeit, das Thema während eines Berichts auf einer Jeedom-Seite zu wählen.
- **Jeedom-Menü** : Eine Verzögerung von 0.25s wurde beim Öffnen von Untermenüs eingeführt.

### 4.4 : Autre

- **Kern** : Beginn der Entwicklung in reinem js, ohne jQuery. Sehen [Doc-Entwickler](https://doc.jeedom.com/de_DE/dev/core4.4).
- **Kern** : Detailliertere Auflistung von USB-Geräten.
- **Kern** : Ein Kontextmenü wurde an verschiedenen Stellen auf der Ebene der Kontrollkästchen hinzugefügt, um alle oder keine auszuwählen oder die Auswahl umzukehren *(siehe [Doc-Entwickler](https://doc.jeedom.com/de_DE/dev/core4.4))*.
- **Lib** : Aktualisieren Sie Highchart v9.3.2 bis v10.3.2 (Das Modul *solide Lehre* nicht mehr importiert).

### 4.4 : Remarques

> **Armaturenbrett**
>
> Auf der **Armaturenbrett** und die **Ansichten**, Kern v4.4 ändert jetzt automatisch die Größe von Kacheln, um ein nahtloses Raster zu erstellen. Die Einheiten (kleinste Höhe und kleinste Breite einer Kachel) dieses Rasters sind in definiert **Einstellungen → System → Konfiguration / Schnittstelle** nach Werten *Vertikaler Abstand (mindestens 100)* und *Horizontaler Abstand (mindestens 110)*. Der Wert *Rand* Definieren des Raums zwischen den Kacheln.
> Die Fliesen passen sich den Abmessungen des Rasters an und können einmal, zweimal usw. diese Werte in Höhe oder Breite. Es wird sicherlich notwendig sein, zu bestehen [Dashboard-Bearbeitungsmodus](https://doc.jeedom.com/de_DE/core/4.4/dashboard#Mode%20%C3%A9dition) um die Größe einiger Kacheln nach dem Update zu optimieren.


> **Widgets**
>
> Kern-Widgets wurden in reinem js/css neu geschrieben. Sie müssen das Dashboard bearbeiten *(Bearbeiten Sie dann die Schaltfläche ⁝ auf den Kacheln)* und nutzen Sie die Möglichkeit *Zeilenumbruch danach* bei bestimmten Befehlen, um denselben visuellen Aspekt zu finden.
> Alle Core-Widgets unterstützen jetzt die Anzeige *Zeit*, durch Hinzufügen eines optionalen Parameters *Zeit* / *Dauer* Wo *Datum*.

> **Dialogboxen**
>
> Alle Dialogboxen (Bootstrap, Bootbox, jQuery UI) wurden auf eine eigens entwickelte interne Core lib (jeeDialog) migriert. In der Größe anpassbare Dialoge haben jetzt eine Schaltfläche zum Wechseln *ganzer Bildschirm*.


# Änderungsprotokoll Jeedom V4.3

## 4.3.15

- Verbot der Übersetzung von Jeedom durch Browser (vermeidet market.repo-Typfehler.php nicht gefunden).
- Optimierung der Ersetzungsfunktion.

## 4.3.14

- Reduzierte Last auf DNS.

## 4.3.13

- Bugfix an **Werkzeuge / Ersetzen**.

## 4.3.12

- Optimierung der Historien.
- Bugfix-Zusammenfassung auf Mobilgeräten.
- Bugfix für Mobile Shutter-Widget.
- Kachelkurven mit binären Informationen behoben.

## 4.3.11

- Autorisierung einer kostenlosen Antwort in *Fragen* wenn Sie * in das Antwortfeld eingeben.
- **Analyse / Geschichte** : Bugfix beim Historienvergleich (Bug eingeführt in 4.3.10).
- **Synthese** : L'*Aktion aus der Synthese* eines Objekts wird jetzt auf der mobilen Version unterstützt.
- Korrektur von Historien bei Verwendung der Aggregationsfunktion.
- Fehler bei der Installation eines Plugins durch ein anderes Plugin behoben (Bsp : mqtt2 von zwavejs installiert).
- Es wurde ein Fehler im Verlauf behoben, bei dem der Wert 0 den vorherigen Wert überschreiben konnte.

## 4.3.10

- **Analyse / Geschichte** : Fehler beim Löschen des Verlaufs behoben.
- Festwertanzeige im Befehlskonfigurationsfenster.
- Ersatzwerkzeuginformationen und -steuerung hinzugefügt.

## 4.3.9

- Verbesserte Kachelbearbeitung.
- Verbesserte Sichtbarkeit von dunklen und hellen Kontrollkästchen.
- Verlaufsstapelung behoben.
- Optimierung des Zeitumstellungsmanagements (danke @jpty).
- Fehlerbehebungen und Verbesserungen.

## 4.3.8

- Bug-Fix.
- Verbesserte Ask-Sicherheit bei Verwendung der generateAskResponseLink-Funktion durch Plugins : Verwendung eines eindeutigen Tokens (kein Senden des Kern-API-Schlüssels mehr) und Sperren der Antwort nur unter den möglichen Optionen.
- Es wurde ein Fehler behoben, der die Installation von jeedom verhinderte.
- Fehler in influxdb behoben.


## 4.3.7

- Fehlerbehebungen (die sich auf ein zukünftiges Plugin in der Entwicklung auswirken).
- Anzeigefehler bei einigen Widgets basierend auf Einheiten behoben.
- Beschreibung hinzugefügt **Quelle** für Nachrichtenaktionen (vgl [Doc-Entwickler](https://doc.jeedom.com/de_DE/dev/core4.3)).

## 4.3.6

- Einheitenumrechnung für Sekunden (s) entfernt).
- Entfernung des Betriebssystem-Update-Menüs für Jeedom-Boxen (Betriebssystem-Updates werden von Jeedom SAS verwaltet).
- Fehler im Verlaufskonfigurationsmodus behoben.
- Hinzufügen einer Aktion *Thema ändern* für Szenarien, Wertaktionen, Pre-/Post-Exec-Aktionen : Es ermöglicht, das Thema der Benutzeroberfläche sofort zu ändern, in dunkel, hell oder das andere (toggle).

## 4.3.5

- Bug-Fix.

## 4.3.4

- Ein Problem mit Hintergrundbildern wurde behoben.
- Ein Fehler mit dem Standardnummern-Widget wurde behoben.
- Einschlussfehler bei einigen Plugins behoben (*Nüsse* zum Beispiel).

## 4.3.3

- Versionsprüfung von nodejs/npm verbessert.

## 4.3.2

- Es wurde ein Problem behoben, bei dem der Status eines Info-Befehls in der erweiterten Konfiguration des Befehls angezeigt wurde, wenn der Wert 0 ist.

## 4.3.1

### 4.3 : Voraussetzungen

- Debian 10 Buster
- PHP7.3

### 4.3 : Neuigkeiten / Verbesserungen

- **Werkzeuge / Szenarien** : Modal für Strg+Klick-Bearbeitung in bearbeitbaren Feldern von Blöcken/Aktionen.
- **Werkzeuge / Szenarien** : Hinzufügen eines Kontextmenüs zu einem Szenario zum Aktivieren/Deaktivieren, Ändern der Gruppe, Ändern des übergeordneten Objekts.
- **Werkzeuge / Objekte** : Ein Kontextmenü für ein Objekt wurde hinzugefügt, um die Sichtbarkeit zu verwalten, das übergeordnete Objekt zu ändern und es zu verschieben.
- **Werkzeuge / Ersetzen** : Neues Tool zum Ersetzen von Ausrüstung und Befehlen.
- **Analyse / Zeitleiste** : Ein Suchfeld hinzugefügt, um die Anzeige zu filtern.
- **Benutzer** : Eine Schaltfläche hinzugefügt, um die Rechte eines eingeschränkten Benutzers auf einen anderen zu kopieren.
- **Bericht** : Möglichkeit, über die Gesundheit von Jeedom zu berichten.
- **Bericht** : Möglichkeit, über alarmierte Geräte zu berichten.
- **Aktualisieren** : Möglichkeit, von Jeedom die OS / PIP2 / PIP3 / NodeJS-Pakete zu sehen, die aktualisiert werden können, und das Update zu starten (Vorsicht, riskante Funktion und in Beta).
- **Alarmbefehl** : Option hinzugefügt, um eine Nachricht im Falle des Endes des Alarms zu erhalten.
- **Plugins** : Möglichkeit, die Installation von Abhängigkeiten per Plugin zu deaktivieren.
- **Optimierung** : jeeFrontEnd{}, jeephp2js{}, kleinere Bugfixes und Optimierungen.

### 4.3 : WebApp

- Notes-Integration.
- Möglichkeit, die Kacheln nur auf einer Spalte anzuzeigen (Einstellung in der Konfiguration der Registerkarte Jeedom-Schnittstelle).

### 4.3 : Autre

- **Lib** : Aktualisieren Sie Font Awesome 5.13.1 bis 5.15.4.

### 4.3 : Notes

- Für Benutzer, die Menüs in ihren Designs im Formular verwenden :

``<a onClick="planHeader_id=15; displayPlan();"><li class="monmenu"><div class="imagette"><img src="theme1/images/new/home.png" height=30px></div></br></li></a>``

Sie müssen jetzt verwenden:

``<a onClick="jeephp2js.planHeader_id=15; jeeFrontEnd.plan.displayPlan();"><li class="monmenu"><div class="imagette"><img src="theme1/images/new/home.png" height=30px></div></br></li></a>``

sehen [Doc-Entwickler](https://doc.jeedom.com/de_DE/dev/core4.3).

Blogeintrag [hier](https://blog.jeedom.com/6739-jeedom-4-3/)

# Änderungsprotokoll Jeedom V4.2

## 4.2.21

- Fehler in Zusammenfassungen behoben.

## 4.2.20

- Ein System hinzugefügt, um Pip-Pakete während einer fehlerhaften Installation zu korrigieren.

## 4.2.19

- Versionsverwaltung für Python-Pakete hinzugefügt (ermöglicht die Behebung des Problems mit dem Zigbee-Plugin).

## 4.2.18

- Nodejs-Update.

## 4.2.17

- Bugfix-Kern : Eingeschränkter Benutzerzugriff auf Designs und Ansichten.
- Bugfix-Benutzeroberfläche : Anzeige von A-Blöcken in Chrome.
- Fehlerbehebung : Link zur Dokumentation, wenn sich das Plugin in der Beta-Phase befindet.

## 4.2.16

- Bugfix-Kern : Szenario : Eingefügte Elemente in einigen Fällen zusammenführen.
- Bugfix-Kern : Archiverstellung mit Dateieditor.
- Fehlerbehebung : Erhöhte Verzögerung für die Kontaktaufnahme mit dem Überwachungsdienst (ermöglicht die Belastung von Cloud-Servern zu verringern).

## 4.2.15

- Bugfix-Benutzeroberfläche : Szenario : Hinzufügen der Aktion *generischerTyp* im Auswahlmodus.
- Bugfix-Kern : Verzögerung bei berechneten Historien behoben.
- Fehlerbehebung : Installation von Zigbee-Plugin-Abhängigkeiten.

## 4.2.14

- Bugfix-Benutzeroberfläche : Recherche durch Aktivieren der Raw-Log-Option entfernt.
- Bugfix-Benutzeroberfläche : Leeres Protokoll kann nicht heruntergeladen werden.
- Bugfix-Benutzeroberfläche : Cmd.action.slider.value-Widget

- Bugfix-Kern : Größe der Hintergrundbilder im Verhältnis zur Größe des Designs.
- Bugfix-Kern : Ein Problem mit weiterhin deaktivierten API-Schlüsseln wurde behoben.

## 4.2.13

- Bugfix-Benutzeroberfläche : Möglichkeit *Auf dem Desktop verstecken* Zusammenfassungen.
- Bugfix-Benutzeroberfläche : Historiques: Beachten Sie beim Zoomen die Maßstäbe.

- Bugfix-Kern : Ein Problem mit der Backup-Größe mit dem Atlas-Plugin wurde behoben.

- Verbesserung : Erstellung von API-Schlüsseln standardmäßig inaktiv (wenn die Erstellungsanfrage nicht vom Plugin kommt).
- Verbesserung : Sicherungsgröße auf der Sicherungsverwaltungsseite hinzugefügt.

## 4.2.12

- Bugfix-Benutzeroberfläche : Anzeigen des Ordners einer Aktion auf der Timeline.

- Bugfix-Kern : Anzeige des API-Schlüssels jedes Plugins auf der Konfigurationsseite.
- Bugfix-Kern : Option hinzufügen *Stunde* auf einem Diagramm in Design.
- Bugfix-Kern : Kachelkurve mit negativem Wert.
- Bugfix-Kern : 403 Fehler beim Neustart.

- Verbesserung : Anzeige des Auslösewerts im Szenarioprotokoll.

## 4.2.11

- Bugfix-Benutzeroberfläche : Position auf der Home-Automation-Zusammenfassung neu erstellter Objekte.
- Bugfix-Benutzeroberfläche : Probleme mit der 3D-Design-Anzeige.

- Bugfix-Kern : Neue nicht definierte Zusammenfassungseigenschaften.
- Bugfix-Kern : Aktualisieren Sie den Wert beim Klicken auf den Bereich der Widgets *Schieberegler*.
- Bugfix-Kern : Bearbeiten einer leeren Datei (0b).
- Bugfix-Kern : Bedenken hinsichtlich der Erkennung der echten IP des Clients durch das Jeedom-DNS. Zur Aktivierung empfiehlt sich nach dem Update ein Neustart der Box.

## 4.2.9

- Bugfix-Benutzeroberfläche : Widget-Fix *numerische Vorgabe* (cmdName zu lang).
- Bugfix-Benutzeroberfläche : Übergeben von CSS-Variablen *--url-iconsDark* und *--url-iconsLight* absolut (Bug Safari MacOS).
- Bugfix-Benutzeroberfläche : Position der Benachrichtigungen in *oben in der Mitte*.

- Bugfix-Kern : Standardschritt für Widgets *Schieberegler* um 1.
- Bugfix-Kern : Seitenaktualisierung zeigt an *In Bearbeitung* an *ENDE UPDATE-FEHLER* (Protokollaktualisierung).
- Bugfix-Kern : Änderung des Wertes einer Geschichte.
- Bugfix-Kern : Probleme beim Installieren von Python-Abhängigkeiten behoben.

- Verbesserung : Neue Optionen in Design-Diagrammen für Skalierung und Gruppierung der Y-Achse.

- Kern : Lib-Update *elFinder* 2.1.59 -> 2.1.60

## 4.2.8

- Bugfix-Benutzeroberfläche : Zusammenfassung der Hausautomation, Löschen des Löschverlaufs.
- Bugfix-Benutzeroberfläche : Möglichkeit *Nicht mehr anzeigen* auf dem Modal *Erstbenutzer*.
- Bugfix-Benutzeroberfläche : Kurve in Hintergrundkacheln in einer Ansicht.
- Bugfix-Benutzeroberfläche : Historien, Achsenskalierung beim Entzoomen.
- Bugfix-Benutzeroberfläche : Historien, Ansichten stapeln.
- Bugfix-Benutzeroberfläche : Anzeige des Benutzernamens beim Löschen.
- Bugfix-Benutzeroberfläche : Optionen zum Anzeigen von Zahlen ohne *Symbol, wenn null*.

- Bugfix-Kern : Überprüfen Sie Apache mod_alias.

- Verbesserung : Option in der Konfiguration, um Daten in der Zukunft in den Historien zu autorisieren.

## 4.2.0

### 4.2 : Voraussetzungen

- Debian 10 Buster
- PHP7.3

### 4.2 : Neuigkeiten / Verbesserungen

- **Synthese** : Möglichkeit zum Konfigurieren von Objekten für a *Entwurf* oder ein *gesehen* seit der Synthese.
- **Armaturenbrett** : Im Gerätekonfigurationsfenster (Bearbeitungsmodus) können Sie jetzt mobile Widgets und generische Typen konfigurieren.
- **Widgets** : Internationalisierung von Widgets von Drittanbietern (Benutzercode). sehen [Doc-Entwickler](https://doc.jeedom.com/de_DE/dev/core4.2).
- **Analyse / Geschichte** : Möglichkeit, eine Historie über einen bestimmten Zeitraum zu vergleichen.
- **Analyse / Geschichte** : Anzeige mehrerer Achsen in Y. Option für jede Achse eine eigene Skala zu haben, gruppiert nach Einheiten oder nicht.
- **Analyse / Geschichte** : Möglichkeit zum Ausblenden der Y-Achsen Kontextmenü auf den Legenden nur mit Anzeige, Achsenmaskierung, Kurvenfarbänderung.
- **Analyse / Geschichte** : Gespeicherte Verlaufsberechnungen werden jetzt wie diese über der Liste der Befehle angezeigt.
- **Analyse / Ausrüstung** : Verwaiste Bestellungen zeigen jetzt ihren Namen und das Löschdatum an, falls sie sich noch im Löschverlauf befinden, sowie einen Link zu dem betroffenen Szenario oder der betroffenen Ausrüstung.
- **Analyse / Protokolle** : Protokollzeilennummerierung. Möglichkeit, das Rohprotokoll anzuzeigen.
- **Protokolle** : Färbung von Protokollen nach bestimmten Ereignissen. Möglichkeit, das Rohprotokoll anzuzeigen.
- **Zusammenfassungen** : Möglichkeit, ein anderes Symbol zu definieren, wenn die Zusammenfassung null ist (keine Fensterläden geöffnet, kein Licht an usw.)).
- **Zusammenfassungen** : Es ist möglich, die Zahl rechts neben dem Symbol niemals oder nur dann anzuzeigen, wenn sie positiv ist.
- **Zusammenfassungen** : Die Änderung des Zusammenfassungsparameters in der Konfiguration und für Objekte ist jetzt sichtbar, ohne auf eine Änderung des Zusammenfassungswerts zu warten.
- **Zusammenfassungen** : Konfiguration ist jetzt möglich [Aktionen zu Zusammenfassungen](/de_DE/concept/summary#Actions an résumés) (Strg + Klick auf eine Zusammenfassung) dank der virtuellen.
- **Bericht** : Vorschau von PDF-Dateien.
- **Gerätearten** : [Neue Seite](/de_DE/core/4.2/types) **Werkzeuge → Gerätetypen** Ermöglicht die Zuweisung von generischen Typen zu Geräten und Befehlen mit Unterstützung für Typen, die für installierte Plugins bestimmt sind (siehe [Doc-Entwickler](https://doc.jeedom.com/de_DE/dev/core4.2)).
- **Auswahl der Abbildungen** : Neues globales Fenster für die Auswahl der Abbildungen *(Symbole, Bilder, Hintergründe)*.
- **Tabellenanzeige** : Hinzufügen einer Schaltfläche rechts von der Suche auf den Seiten *Objekte* *Szenarien* *Interaktionen* *Widgets* und *Plugins* in den Tabellenmodus wechseln. Dies wird durch ein Cookie oder in gespeichert **Einstellungen → System → Konfiguration / Schnittstelle, Optionen**. Die Plugins können diese neue Funktion des Core nutzen. sehen [Doc-Entwickler](https://doc.jeedom.com/de_DE/dev/core4.2).
- **Gerätekonfiguration** : Möglichkeit, eine Verlaufskurve am unteren Rand der Kachel eines Geräts zu konfigurieren.
- **Bestellt** : Möglichkeit, vor Ausführung des Befehls eine Berechnung für eine Befehlsaktion vom Typ Schieberegler durchzuführen.
- **Plugins / Management** : Anzeige der Plugin-Kategorie und ein Link zum direkten Öffnen der Seite, ohne das Plugins-Menü aufzurufen.
- **Szenario** : Code-Fallback-Funktion (*Code falten*) in dem *Codeblöcke*. Tastenkombinationen Strg + Y und Strg + I.
- **Szenario** : Bugfix kopieren / einfügen und rückgängig machen / wiederholen (vollständiges Umschreiben)).
- **Szenario** : Berechnungsfunktionen hinzufügen ``averageTemporal(commande,période)`` & ``averageTemporalBetween(commande,start,end)`` Damit kann der nach der Duration über den Zeitraum gewichtete Durchschnitt ermittelt werden.
- **Szenario** : Unterstützung für generische Typen in Szenarien hinzugefügt.
	- Abzug : ``#genericType(LIGHT_STATE,#[Salon]#)# > 0``
	- WENN ``genericType(LIGHT_STATE,#[Salon]#) > 0``
	- Aktie ``genericType``
- **Objekte** : Plugins können jetzt bestimmte objektspezifische Parameter anfordern.
- **Benutzer** : Plugins können jetzt bestimmte benutzerspezifische Parameter anfordern.
- **Benutzer** : Möglichkeit zum Verwalten der Profile verschiedener Jeedom-Benutzer auf der Benutzerverwaltungsseite.
- **Benutzer** : Möglichkeit zum Ausblenden von Objekten / Ansicht / Design / 3D-Design für eingeschränkte Benutzer.
- **Updates Center** : Das Update Center zeigt jetzt das Datum des letzten Updates an.
- **Hinzufügen des Benutzers, der eine Aktion ausführt** : Hinzufügen in den Befehlsausführungsoptionen der ID und des Benutzernamens, die die Aktion starten (z. B. sichtbar im Protokollereignis)
- **Dokumentation und Changelog-Plugin Beta** : Dokumentation und Changelog-Management für Plugins in der Beta. Achtung, in der Beta ist das Changelog nicht datiert.
- **Allgemein** : Integration des JeeXplorer-Plugins in den Core. Wird jetzt für Widget-Code und erweiterte Anpassung verwendet.
- **Aufbau** : Neue Option in Konfiguration / Benutzeroberfläche, um das Titelbanner des Geräts nicht einzufärben.
- **Aufbau** : Möglichkeit, Hintergrundbilder auf den Seiten Dashboard, Analyse, Tools und deren Deckkraft entsprechend dem Thema zu konfigurieren.
- **Aufbau**: Jeedom DNS basierend auf Wireguard anstelle von Openvpn hinzufügen (Administration / Netzwerke). Schneller und stabiler, aber noch im Test. Bitte beachten Sie, dass dies derzeit nicht mit Jeedom Smart kompatibel ist.
- **Aufbau** : OSDB-Einstellungen: Hinzufügen eines Tools zur Massenbearbeitung von Geräten, Befehlen, Objekten, Szenarien.
- **Aufbau** : OSDB-Einstellungen: Hinzufügen eines dynamischen SQL-Abfragekonstruktors.
- **Aufbau**: Möglichkeit zum Deaktivieren der Cloud-Überwachung (Administration / Updates / Market).
- **jeeCLI** : Zugabe von ``jeeCli.php`` im core / php-Ordner von Jeedom, um einige Kommandozeilenfunktionen zu verwalten.
- *Große Verbesserungen der Benutzeroberfläche in Bezug auf Leistung / Reaktionsfähigkeit. jeedomUtils {}, jeedomUI {}, Hauptmenü in reinem CSS umgeschrieben, Entfernung von initRowWorflow(), Vereinfachung des Codes, CSS-Fixes für kleine Bildschirme usw.*

### 4.2 : Kern-Widgets

- Auf die Widgets-Einstellungen für die Mobile-Version kann jetzt über das Gerätekonfigurationsfenster im Dashboard-Bearbeitungsmodus zugegriffen werden.
- Die optionalen Parameter, die für Widgets verfügbar sind, werden jetzt für jedes Widget entweder in der Befehlskonfiguration oder im Dashboard-Bearbeitungsmodus angezeigt.
- Viele Core Widgets akzeptieren jetzt optionale Farbeinstellungen. (horizontaler und vertikaler Schieberegler, Messgerät, Kompass, Regen, Verschluss, Schieberegler für Vorlagen usw.).
- Kern-Widgets mit Anzeige von a *Zeit* unterstützen jetzt einen optionalen Parameter **Zeit : Datum** um ein relatives Datum anzuzeigen (Gestern um 16:48 Uhr, Letzter Montag um 14:00 Uhr usw.)).
- Widgets vom Typ Cursor (Aktion) akzeptieren jetzt einen optionalen Parameter *Schritte* um den Änderungsschritt am Cursor zu definieren.
- Das Widget **action.slider.value** ist jetzt auf dem Desktop mit einem optionalen Parameter verfügbar *kein Gleiter*, was macht es ein *Eingang* einfach.
- Das Widget **info.numeric.default** (*Messgerät*) wurde in reinem CSS überarbeitet und in mobile integriert. Sie sind daher jetzt in Desktop und Mobile identisch.

### 4.2 : Cloud-Backup

Wir haben eine Bestätigung des Cloud-Backup-Passworts hinzugefügt, um Eingabefehler zu vermeiden (zur Erinnerung, der Benutzer ist der einzige, der dieses Passwort kennt, falls es vergessen wird, kann Jeedom es weder wiederherstellen noch auf die Backups zugreifen. Cloud des Benutzers).

>**WICHTIG**
>
> Nach dem Update MÜSSEN Sie zu Einstellungen → System → Konfigurationsupdate / Markt gehen und die Bestätigung des Cloud-Sicherungskennworts eingeben, damit dies durchgeführt werden kann.

### 4.2 : Sicherheit

- Um die Sicherheit der Jeedom-Lösung deutlich zu erhöhen, wurde das Dateizugriffssystem geändert. Bevor bestimmte Dateien an bestimmten Orten verboten wurden. Ab v4.2, Dateien sind explizit nach Typ und Speicherort erlaubt.
- Änderung auf API-Ebene, zuvor "tolerant", wenn Sie mit dem Core-Key-Anzeige-Plugin angekommen sind XXXXX. Dies ist nicht mehr der Fall, Sie müssen mit dem dem Plugin entsprechenden Schlüssel anreisen.
- In der http-API könnten Sie einen Plugin-Namen im Typ angeben, dies ist nicht mehr möglich. Der dem Typ der Anfrage entsprechende Typ (szenario, eqLogic, cmd usw.) muss dem Plugin entsprechen. Zum Beispiel für das virtuelle Plugin, das Sie hatten ``type=virtual`` in der URL muss jetzt ersetzt werden durch ``plugin=virtual&type=event``.
- Verstärkung der Sitzungen : Wechseln Sie zu sha256 mit 64 Zeichen im strikten Modus.

Das Jeedom-Team ist sich bewusst, dass diese Änderungen Auswirkungen haben und für Sie peinlich sein können, aber wir können keine Kompromisse bei der Sicherheit eingehen.
Die Plugins müssen die Empfehlungen zur Baumstruktur von Ordnern und Dateien respektieren : [Arzt](https://doc.jeedom.com/de_DE/dev/plugin_template).

[Blog: Jeedom 4 Einführung.2 : Sicherheit](https://blog.jeedom.com/6165-introduction-jeedom-4-2-la-securite/)

# Änderungsprotokoll Jeedom V4.1

## 4.1.28

- Harmonisierung von Widget-Vorlagen für Aktions-/Standardbefehle

## 4.1.27

- Korrektur einer Sicherheitsverletzung danke @Maxime Rinaudo und @Antoine Cervoise von Synacktiv (www.synacktiv.com)

## 4.1.26

- Es wurde ein Problem bei der Installation von apt-Abhängigkeiten auf Smart aufgrund der Änderung des Zertifikats bei let's encrypt behoben.

## 4.1.25

- Problem mit der Installation von apt-Abhängigkeiten behoben.

## 4.1.24

- Überarbeitung der Befehlskonfigurationsoption **Verwalten der Wiederholung von Werten** wer wird **Identische Werte wiederholen (Ja|Non)**. [Weitere Informationen finden Sie im Blog-Artikel](https://blog.jeedom.com/5414-nouvelle-gestion-de-la-repetition-des-valeurs/)

## 4.1.23

- Fehler bei der Verlaufsarchivierung behoben
- Cache-Problem behoben, das während eines Neustarts verschwinden konnte
- Fehler bei der Verwaltung von Wiederholungen binärer Befehle behoben Fixed : in bestimmten Fällen, wenn das Gerät zweimal 1 oder 0 hintereinander sendet, wurde nur die Erstbegehung berücksichtigt. Bitte beachten Sie, dass diese Fehlerkorrektur zu einer Überlastung der CPU führen kann. Daher ist es notwendig, auch die Plugins (insbesondere Philipps Hue) für andere Fälle zu aktualisieren (mehrfache Szenarioauslösung, was vor dem Update nicht der Fall war), Frage zur Wiederholung von Werten (erweiterte Konfiguration des Befehls) und ändern Sie es in "Nie wiederholen", um den vorherigen Vorgang zu finden.

## 4.1.22

- Hinzufügen eines Systems, das es Jeedom SAS ermöglicht, Nachrichten an alle Jeedom . zu übermitteln
- Umschalten von Jeedom DNS in den Hochverfügbarkeitsmodus

## 4.1.20

- Bugfix horizontale Schriftrolle auf Designs.
- Bugfix-Bildlauf auf den Ausrüstungsseiten der Plugins.
- Bugfix der Farbeinstellungen in den Ansichts- / Design-Links eines Designs.
- Bugfix und Optimierung der Timeline.
- Die Überprüfung von mobilen Designs mit drei Fingern ist jetzt auf Administratorprofile beschränkt.

## 4.1.19

- Bugfix Löschung der Zone in einer Ansicht.
- Bugfix js Fehler, der in alten Browsern auftreten kann.
- Fehlerbehebung cmd.info.numeric.default.HTML, wenn Befehl nicht sichtbar ist.
- Bugfix-Anmeldeseite.

## 4.1.18

- Historischer Bugfix für Designs.
- Bugfix-Suche in Analyse / Verlauf.
- Bugfix-Suche nach einer Variablen, Link zu einem Gerät.
- Bugfix von farbigen Zusammenfassungen zur Synthese.
- Bugfix für Szenariokommentare mit json.
- Bugfix bei Zusammenfassungsaktualisierungen in der Vorschau des Dashboard-Modus.
- Bugfix von Elementen *Bild* auf ein Design.
- Gruppierungsoptionen nach Zeit für Diagramme in Ansichten hinzugefügt.
- Beibehaltung des Synthesekontexts beim Klicken auf die Zusammenfassungen.
- Zentrierung von Synthesebildern.

## 4.1.0

### 4.1 : Voraussetzungen

- Debian 10 Buster

### 4.1 : Neuigkeiten / Verbesserungen

- **Synthese** : Neue Seite hinzufügen **Home → Zusammenfassung** Bietet eine globale visuelle Zusammenfassung der Teile mit schnellem Zugriff auf Zusammenfassungen.
- **Forschung** : Hinzufügen einer Suchmaschine in **Extras → Suchen**.
- **Armaturenbrett** : Im Bearbeitungsmodus wird jetzt die verschobene Kachel eingefügt.
- **Armaturenbrett** : Bearbeitungsmodus: Die Geräteaktualisierungssymbole werden durch ein Symbol ersetzt, das dank eines neuen vereinfachten Modals den Zugriff auf ihre Konfiguration ermöglicht.
- **Armaturenbrett** : Wir können jetzt auf die klicken *Zeit* Zeitaktions-Widgets zum Öffnen des Verlaufsfensters des Befehls "Verknüpfte Informationen".
- **Armaturenbrett** : Die Größe der Kachel eines neuen Geräts passt sich dem Inhalt an.
- **Armaturenbrett** : Hinzufügen (zurück!) Eine Schaltfläche zum Filtern der angezeigten Elemente nach Kategorie.
- **Armaturenbrett** : Strg Klicken Sie auf eine Info, um das Verlaufsfenster mit allen historisierten Befehlen der auf der Kachel sichtbaren Ausrüstung zu öffnen. Strg Klicken Sie auf eine Legende, um nur diese anzuzeigen. Alt Klicken Sie, um alle anzuzeigen.
- **Armaturenbrett** : Neugestaltung der Anzeige des Objektbaums (Pfeil links neben der Suche).
- **Armaturenbrett** : Möglichkeit, Hintergrundbilder zu verwischen (Konfiguration -> Benutzeroberfläche)).
- **Tools / Widgets** : Die Funktion *Bewerben Sie sich am* Zeigt die aktivierten verknüpften Befehle an. Wenn Sie diese Option deaktivieren, wird das Standard-Kern-Widget auf diesen Befehl angewendet.
- **Widgets** : Hinzufügen eines Kern-Widgets *Schieberegler Vertikal*.
- **Widgets** : Hinzufügen eines Kern-Widgets *binärSchalter*.
- **Update Center** : Aktualisierungen werden beim Öffnen der Seite automatisch überprüft, wenn sie 120 Minuten älter ist.
- **Update Center** : Der Fortschrittsbalken befindet sich jetzt auf der Registerkarte *Core und Plugins*, und das Protokoll wird standardmäßig auf der Registerkarte geöffnet *Information*.
- **Update Center** : Wenn Sie während eines Updates einen anderen Browser öffnen, wird dies in der Fortschrittsanzeige und im Protokoll angezeigt.
- **Update Center** : Wenn das Update korrekt abgeschlossen wurde, wird ein Fenster angezeigt, in dem Sie aufgefordert werden, die Seite neu zu laden.
- **Kernupdates** : Implementierung eines Systems zum Bereinigen alter nicht verwendeter Core-Dateien.
- **Szenario** : Hinzufügen einer Suchmaschine (links von der Schaltfläche Ausführen)).
- **Szenario** : Hinzufügung der Altersfunktion (gibt das Alter des Wertes der Bestellung an).
- **Szenario** : *stateChanges()* Akzeptieren Sie jetzt den Zeitraum *heute* (Mitternacht bis jetzt), *gestern* und *Tag* (für 1 Tag).
- **Szenario** : Funktionen *Statistik (), Durchschnitt (), Max (), Min (), Trend (), Dauer()* : Bugfix über den Zeitraum *gestern*, und jetzt akzeptieren *Tag* (für 1 Tag).
- **Szenario** : Möglichkeit, das automatische Angebotssystem zu deaktivieren (Einstellungen → System → Konfiguration : Equipements).
- **Szenario** : Anzeigen a *Warnung* wenn kein Trigger konfiguriert ist.
- **Szenario** : Bugfix von *auswählen* auf Block kopieren / einfügen.
- **Szenario** : Kopieren / Einfügen eines Blocks zwischen verschiedenen Szenarien.
- **Szenario** : Die Funktionen zum Rückgängigmachen / Wiederherstellen sind jetzt als Schaltflächen verfügbar (neben der Schaltfläche zum Erstellen von Blöcken)).
- **Szenario** :  Hinzufügung von "Historischer Export" (exportHistory)
- **Fenster &quot;Szenariovariablen&quot;** : Alphabetische Sortierung beim Öffnen.
- **Fenster &quot;Szenariovariablen&quot;** : Die von den Variablen verwendeten Szenarien können jetzt angeklickt werden, wobei die Suche für die Variable geöffnet wird.
- **Analyse / Geschichte** : Strg Klicken Sie auf eine Legende, um nur diesen Verlauf anzuzeigen. Alt Klicken Sie, um alle anzuzeigen.
- **Analyse / Geschichte** : Die Optionen *Gruppierung, Typ, Variation, Treppe* sind nur mit einer einzigen angezeigten Kurve aktiv.
- **Analyse / Geschichte** : Wir können jetzt die Option verwenden *Bereich* mit der Option *Treppe*.
- **Analyse / Protokolle** : Neue Monospace-Schriftart für Protokolle.
- **Gesehen** : Möglichkeit, Szenarien zu setzen.
- **Gesehen** : Im Bearbeitungsmodus wird jetzt die verschobene Kachel eingefügt.
- **Gesehen** : Bearbeitungsmodus: Die Geräteaktualisierungssymbole werden durch ein Symbol ersetzt, das dank eines neuen vereinfachten Modals den Zugriff auf ihre Konfiguration ermöglicht.
- **Gesehen** : Die Anzeigereihenfolge ist jetzt unabhängig von der im Dashboard.
- **Zeitleiste** : Trennung von Verlaufs- und Zeitleisten-Seiten.
- **Zeitleiste** : Integration der Timeline in die DB aus Zuverlässigkeitsgründen.
- **Zeitleiste** : Verwaltung mehrerer Zeitleisten.
- **Zeitleiste** : Komplette grafische Neugestaltung der Timeline (Desktop / Mobile).
- **Globale Zusammenfassung** : Zusammenfassungsansicht, Unterstützung für Zusammenfassungen von einem anderen Objekt oder mit einem leeren Stammobjekt (Desktop und WebApp)).
- **Werkzeuge / Objekte** : Neue Registerkarte *Zusammenfassung nach Ausrüstung*.
- **Zusammenfassung der Hausautomation** : Plugin-Geräte sind deaktiviert und ihre Steuerelemente haben nicht mehr die Symbole auf der rechten Seite (Gerätekonfiguration und erweiterte Konfiguration)).
- **Zusammenfassung der Hausautomation** : Möglichkeit zur Suche nach Gerätekategorien.
- **Zusammenfassung der Hausautomation** : Möglichkeit, mehrere Geräte von einem Objekt zum anderen zu bewegen.
- **Zusammenfassung der Hausautomation** : Möglichkeit, alle Geräte eines Objekts auszuwählen.
- **Task-Engine** : Auf der Registerkarte *Dämon*, deaktivierte Plugins werden nicht mehr angezeigt.
- **Bericht** : Die Verwendung von *Chrom* wenn verfügbar.
- **Bericht** : Möglichkeit zum Exportieren von Zeitleisten.
- **Aufbau** : Tab *Information* ist jetzt in der Registerkarte *Allgemein*.
- **Aufbau** : Tab *Aufträge* ist jetzt in der Registerkarte *Ausrüstung*.
- **Fenster zur erweiterten Gerätekonfiguration** : Dynamische Änderung der Schalttafelkonfiguration.
- **Ausrüstung** : Neue Kategorie *Öffnung*.
- **Ausrüstung** : Möglichkeit des Invertierens von Cursortypbefehlen (Info und Aktion))
- **Ausrüstung** : Möglichkeit, einer Kachel Klassen-CSS hinzuzufügen (siehe Widget-Dokumentation).
- **Über Fenster** : Hinzufügen von Verknüpfungen zu Changelog und FAQ.
- Widgets / Objekte / Szenarien / Interaktionen / Plugins Seiten :
	- Strg Clic / Clic Center auf einem Widget, Objekt, Szenarien, Interaktion, Plugin-Ausrüstung : Wird in einem neuen Tab geöffnet.
	- Ctrl Clic / Clic Center ist auch in den Kontextmenüs (auf den Registerkarten) verfügbar).
- Neue ModalDisplay-Seite :
	- Analysemenü : Strg Klicken / Klicken Sie auf Mitte *Echtzeit* : Öffnen Sie das Fenster in einer neuen Registerkarte im Vollbildmodus.
	- Menü Extras : Strg Klicken / Klicken Sie auf Mitte *Bewertungen*, *Expressionstester*, *Variablen*, *Forschung* : Öffnen Sie das Fenster in einer neuen Registerkarte im Vollbildmodus.
- Codeblock, Datei-Editor, Erweiterte Anpassung : Dunkle Themenanpassung.
- Verbessertes Bildauswahlfenster.

### 4.1 : WebApp
- Integration der neuen Übersichtsseite.
- Auf der Seite &quot;Szenarien&quot; wird durch Klicken auf den Szenariotitel das Protokoll angezeigt.
- Wir können jetzt einen Teil eines Protokolls auswählen / kopieren.
- Fügen Sie bei der Suche in einem Protokoll eine x-Schaltfläche hinzu, um die Suche abzubrechen.
- Persistenz des Themenumschalters (8h).
- Bei einem Design kehrt ein Klick mit drei Fingern zur Startseite zurück.
- Anzeige von Szenarien nach Gruppe.
- Neue Monospace-Schriftart für Protokolle.
- Viele Fehlerbehebungen (Benutzeroberfläche, Hoch- / Querformat iOS usw.).).

### 4.1 : Autres
- **Dokumentation** : Anpassungen gemäß v4 und v4.1.
- **Dokumentation** : Neue Seite *Tastatur- / Mausverknüpfungen* einschließlich einer Zusammenfassung aller Verknüpfungen in Jeedom. Zugriff über das Dashboard-Dokument oder die FAQ.
- **Lib** : Aktualisieren Sie HighStock v7.1.2 bis v8.2.0.
- **Lib** : Aktualisieren Sie jQuery v3.4.1 bis v3.5.1.
- **Lib** : Aktualisieren Sie Font Awesome 5.9.0 bis 5.13.1.
- **APIs** :  Hinzufügung einer Option, um zu verhindern, dass ein API-Schlüssel eines Plugins Kernmethoden ausführt (allgemein))
- Sichern von Ajax-Anfragen.
- API-Aufrufe sichern.
- Fehlerbehebungen.
- Zahlreiche Leistungsoptimierungen für Desktop / Mobile.

### 4.1 : Changements
- Die Funktion **Szenario-> getHumanName()** der PHP-Szenario-Klasse wird nicht mehr zurückgegeben *[Objekt] [Gruppe] [Name]* aber *[Gruppe] [Objekt] [Name]*.
- Die Funktion **Szenario-> byString()** muss nun mit der Struktur aufgerufen werden *[Gruppe] [Objekt] [Name]*.
- Funktionen **network-> getInterfaceIp () network-> getInterfaceMac () network-> getInterfaces()** wurden ersetzt durch **network-> getInterfacesInfo()**


# Änderungsprotokoll Jeedom V4.0

## 4.0.62

- Neue Buster + Kernel-Migration für Smart und Pro v2
- Überprüfung der Betriebssystemversion bei wichtigen Jeedom-Updates


## 4.0.61

- Ein Problem beim Anwenden einer Szenariovorlage wurde behoben
- Hinzufügen einer Option zum Deaktivieren der SSL-Überprüfung während der Kommunikation mit dem Markt (nicht empfohlen, aber in bestimmten Netzwerkkonfigurationen nützlich)
- Es wurde ein Problem mit dem Archivierungsverlauf behoben, wenn der Glättungsmodus für immer war
- Fehlerbehebungen
- Korrektur des Befehls trigger () in Szenarien, sodass der Name des Triggers (ohne das #) anstelle des Werts für den Wert zurückgegeben wird, den Sie triggerValue verwenden müssen()

## 4.0.60

- Entfernung des neuen DNS-Systems in eu.jeedom.Link folgt zu vielen Betreibern, die permanente http2-Flows verbieten

## 4.0.59

- Fehler bei Zeit-Widgets behoben
- Erhöhen Sie die Anzahl der fehlerhaften Kennwörter vor dem Sperren (vermeidet Probleme mit der Webanwendung beim Drehen von API-Schlüsseln)

## 4.0.57

- Verstärkung der Cookie-Sicherheit
- Verwendung von Chrom (falls installiert) für Berichte
- Es wurde ein Problem mit der Berechnung der Statuszeit für Widgets behoben, wenn die Zeitzone jeedom nicht mit der des Browsers übereinstimmt
- Bug-Fix

## 4.0.55

- Die neuen DNS (\*. Eu.jeedom.Link) wird zum primären DNS (der alte DNS funktioniert immer noch)

## 4.0.54

- Start des Updates für die neue Dokumentationssite

## 4.0.53

- Bug-Fix.

## 4.0.52

- Fehlerkorrektur (Update unbedingt durchführen, wenn Sie in 4.0.51 sind).

## 4.0.51

- Bug-Fix.
- Optimierung des zukünftigen DNS-Systems.

## 4.0.49

- Möglichkeit, den Jeedom TTS-Motor zu wählen und Plugins zu haben, die einen neuen TTS-Motor bieten.
- Verbesserte Webview-Unterstützung in der mobilen Anwendung.
- Bug-Fix.
- Doc Update.

## 4.0.47

- Verbesserter Expressionstester.
- Update des Repositorys auf smart.
- Bug-Fix.

## 4.0.44

- Verbesserte Übersetzungen.
- Bug-Fix.
- Verbesserte Wiederherstellung von Cloud-Backups.
- Bei der Cloud-Wiederherstellung wird nur noch das lokale Backup abgerufen, sodass Sie es herunterladen oder wiederherstellen können.

## 4.0.43

- Verbesserte Übersetzungen.
- Fehler in Szenariovorlagen behoben.

## 4.0.0

### 4.0 : Voraussetzungen

- Debian 9 Stretch

### 4.0 : Neuigkeiten / Verbesserungen

- Komplette Neugestaltung des Themas (Core 2019 Light / Dark / Legacy)).
- Möglichkeit, das Thema je nach Zeit automatisch zu ändern.
- In Mobilgeräten kann sich das Thema je nach Helligkeit ändern (Aktivierung erforderlich) *generischer zusätzlicher Sensor* in Chrom, Chrom Seite://flags).<br/><br/>
- Verbesserung und Neuorganisation des Hauptmenüs.
- Plugins-Menü : Die Liste der Kategorien und Plugins ist jetzt alphabetisch sortiert.
- Menü Extras : Fügen Sie eine Schaltfläche hinzu, um auf den Ausdruckstester zuzugreifen.
- Menü Extras : Hinzufügen einer Schaltfläche für den Zugriff auf die Variablen.<br/><br/>
- Suchfelder unterstützen jetzt Akzente.
- Die Suchfelder (Dashboard, Szenarien, Objekte, Widgets, Interaktionen, Plugins) sind jetzt beim Öffnen der Seite aktiv, sodass Sie eine Suche direkt eingeben können.
- In den Suchfeldern wurde eine X-Schaltfläche hinzugefügt, um die Suche abzubrechen.
- Während einer Suche wird der Schlüssel *Flucht* Suche abbrechen.
- Armaturenbrett : Im Bearbeitungsmodus sind das Suchsteuerelement und seine Schaltflächen deaktiviert und werden behoben.
- Armaturenbrett : Im Bearbeitungsmodus ein Klick auf eine Schaltfläche *erweitern* rechts von Objekten wird die Größe der Kacheln des Objekts auf die höchste Höhe geändert. Strg + Klick reduziert sie auf die niedrigste Höhe.
- Armaturenbrett : Die Befehlsausführung auf einer Kachel wird jetzt durch die Schaltfläche signalisiert *Aktualisierung*. Wenn sich keine auf der Kachel befindet, wird sie während der Ausführung angezeigt.
- Armaturenbrett : Die Kacheln zeigen einen Infobefehl (historisiert, wodurch das Verlaufsfenster geöffnet wird) oder eine Aktion beim Schweben an.
- Armaturenbrett : Im Verlaufsfenster können Sie diesen Verlauf jetzt in Analyse / Verlauf öffnen.
- Armaturenbrett : Das Verlaufsfenster behält seine Position / Abmessungen bei, wenn ein anderer Verlauf erneut geöffnet wird.
- Befehlskonfigurationsfenster: Strg + Klick auf "Speichern" schließt das Fenster danach.
- Gerätekonfigurationsfenster: Strg + Klick auf "Speichern" schließt das Fenster danach.
- Fügen Sie beim Löschen eines Geräts Nutzungsinformationen hinzu.
- Objekte : Option zur Verwendung benutzerdefinierter Farben hinzugefügt.
- Objekte : Hinzufügen eines Kontextmenüs auf den Registerkarten (schneller Objektwechsel).
- Interaktionen : Hinzufügen eines Kontextmenüs auf den Registerkarten (schnelle Änderung der Interaktion).
- Plugins : Hinzufügen eines Kontextmenüs auf den Registerkarten (schneller Wechsel der Ausrüstung).
- Plugins : Auf der Plugins-Verwaltungsseite zeigt ein orangefarbener Punkt die Plugins in einer nicht stabilen Version an.
- Tabellenverbesserungen mit Filter- und Sortieroption.
- Möglichkeit, einer Interaktion ein Symbol zuzuweisen.
- Jede Jeedom-Seite hat jetzt einen Titel in der Sprache der Benutzeroberfläche (Registerkarte Browser)).
- Verhinderung des automatischen Ausfüllens von 'Zugangscode'-Feldern'.
- Funktionsmanagement *Vorherige Seite / Nächste Seite* vom Browser.<br/><br/>
- Widgets : Neugestaltung des Widget-Systems (Menü Extras / Widgets).
- Widgets : Möglichkeit, ein Widget bei allen Befehlen, die es verwenden, durch ein anderes zu ersetzen.
- Widgets : Möglichkeit, mehreren Befehlen Widgets zuzuweisen.
- Widgets : Hinzufügen eines horizontalen numerischen Info-Widgets.
- Widgets : Hinzufügen eines vertikalen numerischen Info-Widgets.
- Widgets : Hinzufügen eines Widgets für numerische Kompass- / Windinformationen (danke @thanaus).
- Widgets : Ein numerisches Regeninfo-Widget wurde hinzugefügt (danke @thanaus)
- Widgets : Anzeige des Info- / Action-Shutter-Widgets proportional zum Wert.<br/><br/>
- Aufbau : Verbesserung und Reorganisation von Registerkarten.
- Aufbau : Hinzufügung von vielen *Kurzinfos* (aide).
- Aufbau : Hinzufügen einer Suchmaschine.
- Aufbau : Es wurde eine Schaltfläche hinzugefügt, um den Cache der Widgets zu leeren (Registerkarte Cache)).
- Aufbau : Es wurde eine Option hinzugefügt, um den Cache von Widgets zu deaktivieren (Registerkarte Cache)).
- Aufbau : Möglichkeit der vertikalen Zentrierung des Inhalts der Kacheln (Registerkarte Schnittstelle)).
- Aufbau : Es wurde ein Parameter für die globale Bereinigung von Protokollen hinzugefügt (Registerkarte "Bestellungen")).
- Aufbau : Änderung von #message# beim #subject# in Konfiguration / Protokolle / Nachrichten, um das Duplizieren der Nachricht zu vermeiden.
- Aufbau : Möglichkeit in den Zusammenfassungen, einen Ausschluss von Aufträgen hinzuzufügen, die nicht länger als XX Minuten aktualisiert wurden (Beispiel für die Berechnung von Temperaturdurchschnitten, wenn ein Sensor länger als 30 Minuten nichts gemeldet hat, wird er von der Berechnung ausgeschlossen)<br/><br/>
- Szenario : Die Färbung von Blöcken erfolgt nicht mehr zufällig, sondern nach Blocktyp.
- Szenario : Möglichkeit durch Strg + Klick auf die Schaltfläche *Ausführung* Speichern Sie es, starten Sie es und zeigen Sie das Protokoll an (wenn die Protokollebene nicht aktiviert ist *Keiner*).
- Szenario : Bestätigung der Blocklöschung. Strg + Klicken, um eine Bestätigung zu vermeiden.
- Szenario : Hinzufügen einer Suchfunktion in Codeblöcken. Forschen : Strg + F dann Enter, Nächstes Ergebnis : Strg + G, Vorheriges Ergebnis : Strg + Umschalt + G
- Szenario : Möglichkeit der Verdichtung der Blöcke.
- Szenario : Die Aktion "Block hinzufügen" wechselt bei Bedarf zur Registerkarte "Szenario".
- Szenario : Neue Funktionen zum Kopieren / Einfügen von Blöcken. Strg + Klicken zum Ausschneiden / Ersetzen.
- Szenario : Ein neuer Block wird am Ende des Szenarios nicht mehr hinzugefügt, sondern nach dem Block, in dem Sie sich vor dem Klicken befanden, bestimmt durch das zuletzt angeklickte Feld.
- Szenario : Einrichten eines Rückgängig- / Wiederherstellungssystems (Strg + Umschalt + Z / Strg + Umschalt + Y)).
- Szenario : Entfernen Sie die Szenariofreigabe.
- Szenario : Verbesserung des Verwaltungsfensters für Szenariovorlagen.<br/><br/>
- Analyse / Ausrüstung : Hinzufügen einer Suchmaschine (Registerkarte Batterien, Suche nach Namen und Eltern).
- Analyse / Ausrüstung : Auf die Tageszone für Kalender / Ausrüstung kann jetzt geklickt werden, um direkt auf die Batteriewechsel zuzugreifen).
- Analyse / Ausrüstung : Suchfeld hinzufügen.<br/><br/>
- Update Center : Warnung auf der Registerkarte "Core and Plugins" und / oder "Others", wenn ein Update verfügbar ist. Wechseln Sie bei Bedarf zu "Andere".
- Update Center : Differenzierung nach Version (stabil, Beta, ...).
- Update Center : Hinzufügen eines Fortschrittsbalkens während des Updates.<br/><br/>
- Zusammenfassung der Hausautomation : Der Verlauf der Löschungen ist jetzt auf einer Registerkarte verfügbar (Zusammenfassung - Verlauf).
- Zusammenfassung der Hausautomation : Komplette Neugestaltung, Möglichkeit der Bestellung von Objekten, Ausrüstung, Bestellungen.
- Zusammenfassung der Hausautomation : Hinzufügen von Geräten und Bestell-IDs zum Anzeigen und Suchen.
- Zusammenfassung der Hausautomation : CSV-Export des übergeordneten Objekts, der ID, der Ausrüstung und ihrer ID, des Befehls.
- Zusammenfassung der Hausautomation : Möglichkeit, einen oder mehrere Befehle sichtbar zu machen oder nicht.<br/><br/>
- Entwurf : Möglichkeit zur Angabe der Reihenfolge (Position) des *Entwürfe* und *3D-Designs* (Bearbeiten, Design konfigurieren).
- Entwurf : Hinzufügen eines benutzerdefinierten CSS-Felds zu den Elementen des *Entwurf*.
- Entwurf : Die Anzeigeoptionen im Design der erweiterten Konfiguration wurden in den Anzeigeparametern aus dem verschoben *Entwurf*. Dies, um die Schnittstelle zu vereinfachen und verschiedene Parameter durch zu ermöglichen *Entwurf*.
- Entwurf : Verschieben und Ändern der Größe von Komponenten *Entwurf* berücksichtigt ihre Größe mit oder ohne Magnetisierung.<br/><br/>
- Hinzufügen eines Massenkonfigurationssystems (wird auf der Seite Ausrüstung verwendet, um Kommunikationswarnungen darauf zu konfigurieren)

### 4.0 : Autres

- **Lib** : Aktualisieren Sie jquery 3.4.1
- **Lib** : Aktualisieren Sie CodeMiror 5.46.0
- **Lib** : Tablesorter aktualisieren 2.31.1
- Allgemeine Aufhellung (CSS / Inline-Stile, Refactoring usw.) und Leistungsverbesserungen.
- Hinzufügung der globalen Kompatibilität von Jeedom DNS mit einer 4G-Internetverbindung.
- Zahlreiche Fehlerbehebungen.
- Sicherheitskorrekturen.

### 4.0 : Changements

- Entfernen Sie Font Awesome 4, um nur Font Awesome 5 beizubehalten.
- Das Widget-Plugin ist nicht mit dieser Version von Jeedom kompatibel und wird nicht mehr unterstützt (da die Funktionen intern im Kern übernommen wurden). Mehr Informationen [hier](https://www.Jeedom.com/blog/4368-les-widgets-en-v4).

>**WICHTIG**
>
> Wenn nach dem Update ein Fehler im Dashboard auftritt, versuchen Sie, Ihre Box neu zu starten, damit die neuen Ergänzungen von Komponenten berücksichtigt werden.
