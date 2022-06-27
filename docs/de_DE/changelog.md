# Änderungsprotokoll Jeedom V4.3

## 

### 4.3 : Voraussetzungen

- 
- PHP7.3

### 4.3 : Neuigkeiten / Verbesserungen

- **Werkzeuge / Szenarien** : Hinzufügen eines Kontextmenüs zu einem Szenario zum Aktivieren/Deaktivieren, Ändern der Gruppe, Ändern des übergeordneten Objekts.
- **Werkzeuge / Objekte** : Ein Kontextmenü für ein Objekt wurde hinzugefügt, um die Sichtbarkeit zu verwalten, das übergeordnete Objekt zu ändern und es zu verschieben.
- **Werkzeuge / Ersetzen** : Neues Tool zum Ersetzen von Ausrüstung und Befehlen.
- **Analyse / Zeitleiste** : Ein Suchfeld hinzugefügt, um die Anzeige zu filtern.
- **** : Möglichkeit, über die Gesundheit von Jeedom zu berichten.
- **** : Möglichkeit, über alarmierte Geräte zu berichten.
- **Aktualisieren** : Möglichkeit, von Jeedom die OS / PIP2 / PIP3 / NodeJS-Pakete zu sehen, die aktualisiert werden können, und das Update zu starten (Vorsicht, riskante Funktion und in Beta).
- **Alarmbefehl** : Option hinzugefügt, um eine Nachricht im Falle des Endes des Alarms zu erhalten.

- **** : jeeFrontEnd{}, jeephp2js{}, kleinere Bugfixes und Optimierungen.

### 4.3 : WebApp

- Notes-Integration.
- Möglichkeit, die Kacheln nur auf einer Spalte anzuzeigen (Einstellung in der Konfiguration der Registerkarte Jeedom-Schnittstelle)

### 4.3 : Autre

- **** : Aktualisieren Sie Font Awesome 5.13.1 bis 5.15.4.


# Änderungsprotokoll Jeedom V4.2

## 

- Bugfix-Benutzeroberfläche :  *Auf dem Desktop verstecken* Zusammenfassungen.
- Bugfix-Benutzeroberfläche : Historiques: Beachten Sie beim Zoomen die Maßstäbe.

- Bugfix-Kern : Ein Problem mit der Backup-Größe mit dem Atlas-Plugin wurde behoben.

- Verbesserung : Erstellung von API-Schlüsseln standardmäßig inaktiv (wenn die Erstellungsanfrage nicht vom Plugin kommt).
- Verbesserung : Sicherungsgröße auf der Sicherungsverwaltungsseite hinzugefügt.

## 

- Bugfix-Benutzeroberfläche : Anzeigen des Ordners einer Aktion auf der Timeline.

- Bugfix-Kern : Anzeige des API-Schlüssels jedes Plugins auf der Konfigurationsseite.
- Bugfix-Kern : Option hinzufügen ** auf einem Diagramm in Design.
- Bugfix-Kern : Kachelkurve mit negativem Wert.
- Bugfix-Kern : 403 Fehler beim Neustart.

- Verbesserung : Anzeige des Auslösewerts im Szenarioprotokoll.

## 

- Bugfix-Benutzeroberfläche : Position auf der Home-Automation-Zusammenfassung neu erstellter Objekte.
- Bugfix-Benutzeroberfläche : Probleme mit der 3D-Design-Anzeige.

- Bugfix-Kern : Neue nicht definierte Zusammenfassungseigenschaften.
- Bugfix-Kern : Aktualisieren Sie den Wert beim Klicken auf den Bereich der Widgets **.
- Bugfix-Kern : Bearbeiten einer leeren Datei (0b).
- Bugfix-Kern : Bedenken hinsichtlich der Erkennung der echten IP des Clients durch das Jeedom-DNS. Zur Aktivierung empfiehlt sich nach dem Update ein Neustart der Box.

## 

- Bugfix-Benutzeroberfläche : Widget-Fix *numerische Vorgabe* (cmdName zu lang).
- Bugfix-Benutzeroberfläche : Übergeben von CSS-Variablen *--url-iconsDark* und *--url-iconsLight* absolut (Bug Safari MacOS).
- Bugfix-Benutzeroberfläche : Position der Benachrichtigungen in *oben in der Mitte*.

- Bugfix-Kern : Standardschritt für Widgets ** um 1.
- Bugfix-Kern : Seitenaktualisierung zeigt an *In Bearbeitung*  *ENDE UPDATE-FEHLER* (Protokollaktualisierung).
- Bugfix-Kern : Änderung des Wertes einer Geschichte.
- Bugfix-Kern : Probleme beim Installieren von Python-Abhängigkeiten behoben.

- Verbesserung : Neue Optionen in Design-Diagrammen für Skalierung und Gruppierung der Y-Achse.

-  : Lib-Update ** 

## 

- Bugfix-Benutzeroberfläche : Zusammenfassung der Hausautomation, Löschen des Löschverlaufs.
- Bugfix-Benutzeroberfläche :  *Nicht mehr anzeigen* auf dem Modal *Erstbenutzer*.
- Bugfix-Benutzeroberfläche : Kurve in Hintergrundkacheln in einer Ansicht.
- Bugfix-Benutzeroberfläche : Historien, Achsenskalierung beim Entzoomen.
- Bugfix-Benutzeroberfläche : Historien, Ansichten stapeln.
- Bugfix-Benutzeroberfläche : Anzeige des Benutzernamens beim Löschen.
- Bugfix-Benutzeroberfläche : Optionen zum Anzeigen von Zahlen ohne *Symbol, wenn null*.

- Bugfix-Kern : Überprüfen Sie Apache mod_alias.

- Verbesserung : Option in der Konfiguration, um Daten in der Zukunft in den Historien zu autorisieren.

## 

### 4.2 : Voraussetzungen

- 
- PHP7.3

### 4.2 : Neuigkeiten / Verbesserungen

- **Synthese** : Möglichkeit zum Konfigurieren von Objekten für a ** oder ein ** seit der Synthese.
- **** : Im Gerätekonfigurationsfenster (Bearbeitungsmodus) können Sie jetzt mobile Widgets und generische Typen konfigurieren.
- **** : Internationalisierung von Widgets von Drittanbietern (Benutzercode). sehen [Doc-Entwickler](https://doc.jeedom.com/de_DE/dev/core4.2).
- **Analyse / Geschichte** : Möglichkeit, eine Historie über einen bestimmten Zeitraum zu vergleichen.
- **Analyse / Geschichte** : Anzeige mehrerer Achsen in Y. Option für jede Achse eine eigene Skala zu haben, gruppiert nach Einheiten oder nicht.
- **Analyse / Geschichte** : Möglichkeit zum Ausblenden der Y-Achsen Kontextmenü auf den Legenden nur mit Anzeige, Achsenmaskierung, Kurvenfarbänderung.
- **Analyse / Geschichte** : Gespeicherte Verlaufsberechnungen werden jetzt wie diese über der Liste der Befehle angezeigt.
- **Analyse / Ausrüstung** : Verwaiste Bestellungen zeigen jetzt ihren Namen und das Löschdatum an, falls sie sich noch im Löschverlauf befinden, sowie einen Link zu dem betroffenen Szenario oder der betroffenen Ausrüstung.
- **Analyse / Protokolle** : Protokollzeilennummerierung. Möglichkeit, das Rohprotokoll anzuzeigen.
- **** : Färbung von Protokollen nach bestimmten Ereignissen. Möglichkeit, das Rohprotokoll anzuzeigen.
- **Zusammenfassungen** : Möglichkeit, ein anderes Symbol zu definieren, wenn die Zusammenfassung null ist (keine Fensterläden geöffnet, kein Licht an usw.)).
- **Zusammenfassungen** : Es ist möglich, die Zahl rechts neben dem Symbol niemals oder nur dann anzuzeigen, wenn sie positiv ist.
- **Zusammenfassungen** : Die Änderung des Zusammenfassungsparameters in der Konfiguration und für Objekte ist jetzt sichtbar, ohne auf eine Änderung des Zusammenfassungswerts zu warten.
- **Zusammenfassungen** : Konfiguration ist jetzt möglich [Aktionen zu Zusammenfassungen](/de_DE/concept/summary#Actions  résumés) (Strg + Klick auf eine Zusammenfassung) dank der virtuellen.
- **** : Vorschau von PDF-Dateien.
- **Gerätearten** : [Neue Seite](/de_DE/core/4.2/types) **Werkzeuge → Gerätetypen** Ermöglicht die Zuweisung von generischen Typen zu Geräten und Befehlen mit Unterstützung für Typen, die für installierte Plugins bestimmt sind (siehe [Doc-Entwickler](https://doc.jeedom.com/de_DE/dev/core4.2)).
- **Auswahl der Abbildungen** : Neues globales Fenster für die Auswahl der Abbildungen *(Symbole, Bilder, Hintergründe)*.
- **Tabellenanzeige** : Hinzufügen einer Schaltfläche rechts von der Suche auf den Seiten ** *Szenarien* ** ** und ** in den Tabellenmodus wechseln. Dies wird durch ein Cookie oder in gespeichert **Einstellungen → System → Konfiguration / Schnittstelle, Optionen**. Die Plugins können diese neue Funktion des Core nutzen. sehen [Doc-Entwickler](https://doc.jeedom.com/de_DE/dev/core4.2).
- **Gerätekonfiguration** : Möglichkeit, eine Verlaufskurve am unteren Rand der Kachel eines Geräts zu konfigurieren.
- **** : Möglichkeit, vor Ausführung des Befehls eine Berechnung für eine Befehlsaktion vom Typ Schieberegler durchzuführen.
- **Plugins / Management** : Anzeige der Plugin-Kategorie und ein Link zum direkten Öffnen der Seite, ohne das Plugins-Menü aufzurufen.
- **Szenario** : Code-Fallback-Funktion (*Code falten*) in dem *Codeblöcke*. Tastenkombinationen Strg + Y und Strg + I.
- **Szenario** : Bugfix kopieren / einfügen und rückgängig machen / wiederholen (vollständiges Umschreiben)).
- **Szenario** : Berechnungsfunktionen hinzufügen ````averageTemporal(commande,période)````  ````averageTemporalBetween(commande,start,end)```` Damit kann der nach der Duration über den Zeitraum gewichtete Durchschnitt ermittelt werden.
- **Szenario** : Unterstützung für generische Typen in Szenarien hinzugefügt.
	- Abzug : ``#genericType(LIGHT_STATE,#[Wohnzimmer]#)# > 
	- IF `genericType (LIGHT_STATE .),#[Wohnzimmer]#) > 
	- `GenericType`-Aktion
- **** : Plugins können jetzt bestimmte objektspezifische Parameter anfordern.
- **** : Plugins können jetzt bestimmte benutzerspezifische Parameter anfordern.
- **** : Möglichkeit zum Verwalten der Profile verschiedener Jeedom-Benutzer auf der Benutzerverwaltungsseite.
- **** : Möglichkeit zum Ausblenden von Objekten / Ansicht / Design / 3D-Design für eingeschränkte Benutzer.
- **Updates Center** : Das Update Center zeigt jetzt das Datum des letzten Updates an.
- **Hinzufügen des Benutzers, der eine Aktion ausführt** : Hinzufügen in den Befehlsausführungsoptionen der ID und des Benutzernamens, die die Aktion starten (z. B. sichtbar im Protokollereignis)
- **Dokumentation und Changelog-Plugin Beta** : Dokumentation und Changelog-Management für Plugins in der Beta. Achtung, in der Beta ist das Changelog nicht datiert.
- **** : Integration des JeeXplorer-Plugins in den Core. Wird jetzt für Widget-Code und erweiterte Anpassung verwendet.
- **Aufbau** : Neue Option in Konfiguration / Benutzeroberfläche, um das Titelbanner des Geräts nicht einzufärben.
- **Aufbau** : Möglichkeit, Hintergrundbilder auf den Seiten Dashboard, Analyse, Tools und deren Deckkraft entsprechend dem Thema zu konfigurieren.
- **Aufbau**: Jeedom DNS basierend auf Wireguard anstelle von Openvpn hinzufügen (Administration / Netzwerke). Schneller und stabiler, aber noch im Test. Bitte beachten Sie, dass dies derzeit nicht mit Jeedom Smart kompatibel ist.
- **Aufbau** : OSDB-Einstellungen: Hinzufügen eines Tools zur Massenbearbeitung von Geräten, Befehlen, Objekten, Szenarien.
- **Aufbau** : OSDB-Einstellungen: Hinzufügen eines dynamischen SQL-Abfragekonstruktors.
- **Aufbau**: Möglichkeit zum Deaktivieren der Cloud-Überwachung (Administration / Updates / Market).
- **** : Zugabe von ````jeeCli.php```` im core / php-Ordner von Jeedom, um einige Kommandozeilenfunktionen zu verwalten.
- *Große Verbesserungen der Benutzeroberfläche in Bezug auf Leistung / Reaktionsfähigkeit. jeedomUtils {}, jeedomUI {}, Hauptmenü in reinem CSS umgeschrieben, Entfernung von initRowWorflow(), Vereinfachung des Codes, CSS-Fixes für kleine Bildschirme usw.*

### 4.2 : Kern-Widgets

- Auf die Widgets-Einstellungen für die Mobile-Version kann jetzt über das Gerätekonfigurationsfenster im Dashboard-Bearbeitungsmodus zugegriffen werden.
- Die optionalen Parameter, die für Widgets verfügbar sind, werden jetzt für jedes Widget entweder in der Befehlskonfiguration oder im Dashboard-Bearbeitungsmodus angezeigt.
- Viele Core Widgets akzeptieren jetzt optionale Farbeinstellungen. (horizontaler und vertikaler Schieberegler, Messgerät, Kompass, Regen, Verschluss, Schieberegler für Vorlagen usw.).
- Kern-Widgets mit Anzeige von a ** unterstützen jetzt einen optionalen Parameter ** : ** um ein relatives Datum anzuzeigen (Gestern um 16:48 Uhr, Letzter Montag um 14:00 Uhr usw.)).
- Widgets vom Typ Cursor (Aktion) akzeptieren jetzt einen optionalen Parameter ** um den Änderungsschritt am Cursor zu definieren.
- Das Widget **** ist jetzt auf dem Desktop mit einem optionalen Parameter verfügbar **, was macht es ein ** .
- Das Widget **** (**) wurde in reinem CSS überarbeitet und in mobile integriert. Sie sind daher jetzt in Desktop und Mobile identisch.

### 4.2 : Cloud-Backup

Wir haben eine Bestätigung des Cloud-Backup-Passworts hinzugefügt, um Eingabefehler zu vermeiden (zur Erinnerung, der Benutzer ist der einzige, der dieses Passwort kennt, falls es vergessen wird, kann Jeedom es weder wiederherstellen noch auf die Backups zugreifen. Cloud des Benutzers).

>****
>
> Nach dem Update MÜSSEN Sie zu Einstellungen → System → Konfigurationsupdate / Markt gehen und die Bestätigung des Cloud-Sicherungskennworts eingeben, damit dies durchgeführt werden kann.

### 4.2 : Sicherheit

- Um die Sicherheit der Jeedom-Lösung deutlich zu erhöhen, wurde das Dateizugriffssystem geändert. Bevor bestimmte Dateien an bestimmten Orten verboten wurden. Ab v4.2, Dateien sind explizit nach Typ und Speicherort erlaubt.
- Änderung auf API-Ebene, zuvor "tolerant", wenn Sie mit dem Core-Key-Anzeige-Plugin angekommen sind XXXXX. Dies ist nicht mehr der Fall, Sie müssen mit dem dem Plugin entsprechenden Schlüssel anreisen.
- In der http-API könnten Sie einen Plugin-Namen im Typ angeben, dies ist nicht mehr möglich. Der dem Typ der Anfrage entsprechende Typ (szenario, eqLogic, cmd usw.) muss dem Plugin entsprechen. Zum Beispiel für das virtuelle Plugin, das Sie hatten ````type=virtual```` in der URL muss jetzt ersetzt werden durch ````plugin=virtualtype=event````.
- Verstärkung der Sitzungen : Wechseln Sie zu sha256 mit 64 Zeichen im strikten Modus.

Das Jeedom-Team ist sich bewusst, dass diese Änderungen Auswirkungen haben und für Sie peinlich sein können, aber wir können keine Kompromisse bei der Sicherheit eingehen.
Die Plugins müssen die Empfehlungen zur Baumstruktur von Ordnern und Dateien respektieren : [](https://doc.jeedom.com/de_DE/dev/plugin_template).

[Blog: Jeedom 4 Einführung.2 : Sicherheit](https://blog.jeedom.com/6165-introduction-jeedom-4-2-la-securite/)

# Änderungsprotokoll Jeedom V4.1

## 

- Harmonisierung von Widget-Vorlagen für Aktions-/Standardbefehle

## 

- Korrektur einer Sicherheitsverletzung danke @Maxime Rinaudo und @Antoine Cervoise von Synacktiv (www.synacktiv.com)

## 

- Es wurde ein Problem bei der Installation von apt-Abhängigkeiten auf Smart aufgrund der Änderung des Zertifikats bei let's encrypt behoben.

## 

- Problem mit der Installation von apt-Abhängigkeiten behoben.

## 

- Überarbeitung der Befehlskonfigurationsoption **Verwalten der Wiederholung von Werten** wer wird **Identische Werte wiederholen (Ja|Non)**. [Weitere Informationen finden Sie im Blog-Artikel](https://blog.jeedom.com/5414-nouvelle-gestion-de-la-repetition-des-valeurs/)

## 

- Fehler bei der Verlaufsarchivierung behoben
- Cache-Problem behoben, das während eines Neustarts verschwinden konnte
- Fehler bei der Verwaltung von Wiederholungen binärer Befehle behoben Fixed : in bestimmten Fällen, wenn das Gerät zweimal 1 oder 0 hintereinander sendet, wurde nur die Erstbegehung berücksichtigt. Bitte beachten Sie, dass diese Fehlerkorrektur zu einer Überlastung der CPU führen kann. Daher ist es notwendig, auch die Plugins (insbesondere Philipps Hue) für andere Fälle zu aktualisieren (mehrfache Szenarioauslösung, was vor dem Update nicht der Fall war), Frage zur Wiederholung von Werten (erweiterte Konfiguration des Befehls) und ändern Sie es in "Nie wiederholen", um den vorherigen Vorgang zu finden.

## 

- Hinzufügen eines Systems, das es Jeedom SAS ermöglicht, Nachrichten an alle Jeedom . zu übermitteln
- Umschalten von Jeedom DNS in den Hochverfügbarkeitsmodus

## 

- Bugfix horizontale Schriftrolle auf Designs.
- Bugfix-Bildlauf auf den Ausrüstungsseiten der Plugins.
- Bugfix der Farbeinstellungen in den Ansichts- / Design-Links eines Designs.
- Bugfix und Optimierung der Timeline.
- Die Überprüfung von mobilen Designs mit drei Fingern ist jetzt auf Administratorprofile beschränkt.

## 

- Bugfix Löschung der Zone in einer Ansicht.
- Bugfix js Fehler, der in alten Browsern auftreten kann.
- Fehlerbehebung cmd.info.numeric.default.HTML, wenn Befehl nicht sichtbar ist.
- Bugfix-Anmeldeseite.

## 

- Historischer Bugfix für Designs.
- Bugfix-Suche in Analyse / Verlauf.
- Bugfix-Suche nach einer Variablen, Link zu einem Gerät.
- Bugfix von farbigen Zusammenfassungen zur Synthese.
- Bugfix für Szenariokommentare mit json.
- Bugfix bei Zusammenfassungsaktualisierungen in der Vorschau des Dashboard-Modus.
- Bugfix von Elementen ** auf ein Design.
- Gruppierungsoptionen nach Zeit für Diagramme in Ansichten hinzugefügt.
- Beibehaltung des Synthesekontexts beim Klicken auf die Zusammenfassungen.
- Zentrierung von Synthesebildern.

## 

### 4.1 : Voraussetzungen

- 

### 4.1 : Neuigkeiten / Verbesserungen

- **Synthese** : Neue Seite hinzufügen **Home → Zusammenfassung** Bietet eine globale visuelle Zusammenfassung der Teile mit schnellem Zugriff auf Zusammenfassungen.
- **** : Hinzufügen einer Suchmaschine in **Extras → Suchen**.
- **** : Im Bearbeitungsmodus wird jetzt die verschobene Kachel eingefügt.
- **** : Bearbeitungsmodus: Die Geräteaktualisierungssymbole werden durch ein Symbol ersetzt, das dank eines neuen vereinfachten Modals den Zugriff auf ihre Konfiguration ermöglicht.
- **** : Wir können jetzt auf die klicken ** Zeitaktions-Widgets zum Öffnen des Verlaufsfensters des Befehls "Verknüpfte Informationen".
- **** : Die Größe der Kachel eines neuen Geräts passt sich dem Inhalt an.
- **** : Hinzufügen (zurück!) Eine Schaltfläche zum Filtern der angezeigten Elemente nach Kategorie.
- **** : Strg Klicken Sie auf eine Info, um das Verlaufsfenster mit allen historisierten Befehlen der auf der Kachel sichtbaren Ausrüstung zu öffnen. Strg Klicken Sie auf eine Legende, um nur diese anzuzeigen. Alt Klicken Sie, um alle anzuzeigen.
- **** : Neugestaltung der Anzeige des Objektbaums (Pfeil links neben der Suche).
- **** : Möglichkeit, Hintergrundbilder zu verwischen (Konfiguration -> Benutzeroberfläche)).
- **Tools / Widgets** : Die Funktion *Bewerben Sie sich am* Zeigt die aktivierten verknüpften Befehle an. Wenn Sie diese Option deaktivieren, wird das Standard-Kern-Widget auf diesen Befehl angewendet.
- **** : Hinzufügen eines Kern-Widgets **.
- **** : Hinzufügen eines Kern-Widgets **.
- **Update Center** : Aktualisierungen werden beim Öffnen der Seite automatisch überprüft, wenn sie 120 Minuten älter ist.
- **Update Center** : Der Fortschrittsbalken befindet sich jetzt auf der Registerkarte *Core und Plugins*, und das Protokoll wird standardmäßig auf der Registerkarte geöffnet **.
- **Update Center** : Wenn Sie während eines Updates einen anderen Browser öffnen, wird dies in der Fortschrittsanzeige und im Protokoll angezeigt.
- **Update Center** : Wenn das Update korrekt abgeschlossen wurde, wird ein Fenster angezeigt, in dem Sie aufgefordert werden, die Seite neu zu laden.
- **Kernupdates** : Implementierung eines Systems zum Bereinigen alter nicht verwendeter Core-Dateien.
- **Szenario** : Hinzufügen einer Suchmaschine (links von der Schaltfläche Ausführen)).
- **Szenario** : Hinzufügung der Altersfunktion (gibt das Alter des Wertes der Bestellung an).
- **Szenario** : *stateChanges()* Akzeptieren Sie jetzt den Zeitraum ** (Mitternacht bis jetzt), ** und ** (für 1 Tag).
- **Szenario** :  *Statistik (), Durchschnitt (), Max (), Min (), Trend (), Dauer()* : Bugfix über den Zeitraum **, und jetzt akzeptieren ** (für 1 Tag).
- **Szenario** : Möglichkeit, das automatische Angebotssystem zu deaktivieren (Einstellungen → System → Konfiguration : Equipements).
- **Szenario** : Anzeigen a ** wenn kein Trigger konfiguriert ist.
- **Szenario** : Bugfix von ** auf Block kopieren / einfügen.
- **Szenario** : Kopieren / Einfügen eines Blocks zwischen verschiedenen Szenarien.
- **Szenario** : Die Funktionen zum Rückgängigmachen / Wiederherstellen sind jetzt als Schaltflächen verfügbar (neben der Schaltfläche zum Erstellen von Blöcken)).
- **Szenario** :  Hinzufügung von "Historischer Export" (exportHistory)
- **Fenster &quot;Szenariovariablen&quot;** : Alphabetische Sortierung beim Öffnen.
- **Fenster &quot;Szenariovariablen&quot;** : Die von den Variablen verwendeten Szenarien können jetzt angeklickt werden, wobei die Suche für die Variable geöffnet wird.
- **Analyse / Geschichte** : Strg Klicken Sie auf eine Legende, um nur diesen Verlauf anzuzeigen. Alt Klicken Sie, um alle anzuzeigen.
- **Analyse / Geschichte** : Die Optionen *Gruppierung, Typ, Variation, Treppe* sind nur mit einer einzigen angezeigten Kurve aktiv.
- **Analyse / Geschichte** : Wir können jetzt die Option verwenden ** mit der Option **.
- **Analyse / Protokolle** : Neue Monospace-Schriftart für Protokolle.
- **** : Möglichkeit, Szenarien zu setzen.
- **** : Im Bearbeitungsmodus wird jetzt die verschobene Kachel eingefügt.
- **** : Bearbeitungsmodus: Die Geräteaktualisierungssymbole werden durch ein Symbol ersetzt, das dank eines neuen vereinfachten Modals den Zugriff auf ihre Konfiguration ermöglicht.
- **** : Die Anzeigereihenfolge ist jetzt unabhängig von der im Dashboard.
- **** : Trennung von Verlaufs- und Zeitleisten-Seiten.
- **** : Integration der Timeline in die DB aus Zuverlässigkeitsgründen.
- **** : Verwaltung mehrerer Zeitleisten.
- **** : Komplette grafische Neugestaltung der Timeline (Desktop / Mobile).
- **Globale Zusammenfassung** : Zusammenfassungsansicht, Unterstützung für Zusammenfassungen von einem anderen Objekt oder mit einem leeren Stammobjekt (Desktop und WebApp)).
- **Werkzeuge / Objekte** : Neue Registerkarte *Zusammenfassung nach Ausrüstung*.
- **Zusammenfassung der Hausautomation** : Plugin-Geräte sind deaktiviert und ihre Steuerelemente haben nicht mehr die Symbole auf der rechten Seite (Gerätekonfiguration und erweiterte Konfiguration)).
- **Zusammenfassung der Hausautomation** : Möglichkeit zur Suche nach Gerätekategorien.
- **Zusammenfassung der Hausautomation** : Möglichkeit, mehrere Geräte von einem Objekt zum anderen zu bewegen.
- **Zusammenfassung der Hausautomation** : Möglichkeit, alle Geräte eines Objekts auszuwählen.
- **Task-Engine** : Auf der Registerkarte *Dämon*, deaktivierte Plugins werden nicht mehr angezeigt.
- **** : Die Verwendung von ** wenn verfügbar.
- **** : Möglichkeit zum Exportieren von Zeitleisten.
- **Aufbau** :  ** ist jetzt in der Registerkarte *Allgemein*.
- **Aufbau** :  ** ist jetzt in der Registerkarte **.
- **Fenster zur erweiterten Gerätekonfiguration** : Dynamische Änderung der Schalttafelkonfiguration.
- **** : Neue Kategorie **.
- **** : Möglichkeit des Invertierens von Cursortypbefehlen (Info und Aktion))
- **** : Möglichkeit, einer Kachel Klassen-CSS hinzuzufügen (siehe Widget-Dokumentation).
- **Über Fenster** : Hinzufügen von Verknüpfungen zu Changelog und FAQ.
- Widgets / Objekte / Szenarien / Interaktionen / Plugins Seiten :
	- Strg Clic / Clic Center auf einem Widget, Objekt, Szenarien, Interaktion, Plugin-Ausrüstung : Wird in einem neuen Tab geöffnet.
	- Ctrl Clic / Clic Center ist auch in den Kontextmenüs (auf den Registerkarten) verfügbar).
- Neue ModalDisplay-Seite :
	- Analysemenü : Strg Klicken / Klicken Sie auf Mitte *Echtzeit* : Öffnen Sie das Fenster in einer neuen Registerkarte im Vollbildmodus.
	- Menü Extras : Strg Klicken / Klicken Sie auf Mitte **, *Expressionstester*, **, ** : Öffnen Sie das Fenster in einer neuen Registerkarte im Vollbildmodus.
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
- **** : Anpassungen gemäß v4 und v4.1.
- **** : Neue Seite *Tastatur- / Mausverknüpfungen* einschließlich einer Zusammenfassung aller Verknüpfungen in Jeedom. Zugriff über das Dashboard-Dokument oder die FAQ.
- **** : Aktualisieren Sie HighStock v7.1.2 bis v8.2.0.
- **** : Aktualisieren Sie jQuery v3.4.1 bis v3.5.1.
- **** : Aktualisieren Sie Font Awesome 5.9.0 bis 5.13.1.
- **** :  Hinzufügung einer Option, um zu verhindern, dass ein API-Schlüssel eines Plugins Kernmethoden ausführt (allgemein))
- Sichern von Ajax-Anfragen.
- API-Aufrufe sichern.
- Fehlerbehebungen.
- Zahlreiche Leistungsoptimierungen für Desktop / Mobile.

### 4.1 : Changements
- Die Funktion **Szenario-> getHumanName()** der PHP-Szenario-Klasse wird nicht mehr zurückgegeben *[Objekt] [Gruppe] [Name]*  *[Gruppe] [Objekt] [Name]*.
- Die Funktion **Szenario-> byString()** muss nun mit der Struktur aufgerufen werden *[Gruppe] [Objekt] [Name]*.
- Funktionen **network-> getInterfaceIp () network-> getInterfaceMac () network-> getInterfaces()** wurden ersetzt durch **network-> getInterfacesInfo()**


# Änderungsprotokoll Jeedom V4.0

## 

- Neue Buster + Kernel-Migration für Smart und Pro v2
- Überprüfung der Betriebssystemversion bei wichtigen Jeedom-Updates


## 

- Ein Problem beim Anwenden einer Szenariovorlage wurde behoben
- Hinzufügen einer Option zum Deaktivieren der SSL-Überprüfung während der Kommunikation mit dem Markt (nicht empfohlen, aber in bestimmten Netzwerkkonfigurationen nützlich)
- Es wurde ein Problem mit dem Archivierungsverlauf behoben, wenn der Glättungsmodus für immer war
- Fehlerbehebungen
- Korrektur des Befehls trigger () in Szenarien, sodass der Name des Triggers (ohne das #) anstelle des Werts für den Wert zurückgegeben wird, den Sie triggerValue verwenden müssen()

## 

- Entfernung des neuen DNS-Systems in eu.jeedom.Link folgt zu vielen Betreibern, die permanente http2-Flows verbieten

## 

- Fehler bei Zeit-Widgets behoben
- Erhöhen Sie die Anzahl der fehlerhaften Kennwörter vor dem Sperren (vermeidet Probleme mit der Webanwendung beim Drehen von API-Schlüsseln)

## 

- Verstärkung der Cookie-Sicherheit
- Verwendung von Chrom (falls installiert) für Berichte
- Es wurde ein Problem mit der Berechnung der Statuszeit für Widgets behoben, wenn die Zeitzone jeedom nicht mit der des Browsers übereinstimmt
- Bug-Fix

## 

- Die neuen DNS (\*. Eu.jeedom.Link) wird zum primären DNS (der alte DNS funktioniert immer noch)

## 

- Start des Updates für die neue Dokumentationssite

## 

- Bug-Fix.

## 

- Fehlerkorrektur (Update unbedingt durchführen, wenn Sie in 4.0.51 sind).

## 

- Bug-Fix.
- Optimierung des zukünftigen DNS-Systems.

## 

- Möglichkeit, den Jeedom TTS-Motor zu wählen und Plugins zu haben, die einen neuen TTS-Motor bieten.
- Verbesserte Webview-Unterstützung in der mobilen Anwendung.
- Bug-Fix.
- Doc Update.

## 

- Verbesserter Expressionstester.
- Update des Repositorys auf smart.
- Bug-Fix.

## 

- Verbesserte Übersetzungen.
- Bug-Fix.
- Verbesserte Wiederherstellung von Cloud-Backups.
- Bei der Cloud-Wiederherstellung wird nur noch das lokale Backup abgerufen, sodass Sie es herunterladen oder wiederherstellen können.

## 

- Verbesserte Übersetzungen.
- Fehler in Szenariovorlagen behoben.

## 

### 4.0 : Voraussetzungen

- 

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
- Während einer Suche wird der Schlüssel ** Suche abbrechen.
-  : Im Bearbeitungsmodus sind das Suchsteuerelement und seine Schaltflächen deaktiviert und werden behoben.
-  : Im Bearbeitungsmodus ein Klick auf eine Schaltfläche ** rechts von Objekten wird die Größe der Kacheln des Objekts auf die höchste Höhe geändert. Strg + Klick reduziert sie auf die niedrigste Höhe.
-  : Die Befehlsausführung auf einer Kachel wird jetzt durch die Schaltfläche signalisiert **. Wenn sich keine auf der Kachel befindet, wird sie während der Ausführung angezeigt.
-  : Die Kacheln zeigen einen Infobefehl (historisiert, wodurch das Verlaufsfenster geöffnet wird) oder eine Aktion beim Schweben an.
-  : Im Verlaufsfenster können Sie diesen Verlauf jetzt in Analyse / Verlauf öffnen.
-  : Das Verlaufsfenster behält seine Position / Abmessungen bei, wenn ein anderer Verlauf erneut geöffnet wird.
- Befehlskonfigurationsfenster: Strg + Klick auf "Speichern" schließt das Fenster danach.
- Gerätekonfigurationsfenster: Strg + Klick auf "Speichern" schließt das Fenster danach.
- Fügen Sie beim Löschen eines Geräts Nutzungsinformationen hinzu.
-  : Option zur Verwendung benutzerdefinierter Farben hinzugefügt.
-  : Hinzufügen eines Kontextmenüs auf den Registerkarten (schneller Objektwechsel).
-  : Hinzufügen eines Kontextmenüs auf den Registerkarten (schnelle Änderung der Interaktion).
-  : Hinzufügen eines Kontextmenüs auf den Registerkarten (schneller Wechsel der Ausrüstung).
-  : Auf der Plugins-Verwaltungsseite zeigt ein orangefarbener Punkt die Plugins in einer nicht stabilen Version an.
- Tabellenverbesserungen mit Filter- und Sortieroption.
- Möglichkeit, einer Interaktion ein Symbol zuzuweisen.
- Jede Jeedom-Seite hat jetzt einen Titel in der Sprache der Benutzeroberfläche (Registerkarte Browser)).
- Verhinderung des automatischen Ausfüllens von 'Zugangscode'-Feldern'.
- Funktionsmanagement *Vorherige Seite / Nächste Seite* vom Browser.<br/><br/>
-  : Neugestaltung des Widget-Systems (Menü Extras / Widgets).
-  : Möglichkeit, ein Widget bei allen Befehlen, die es verwenden, durch ein anderes zu ersetzen.
-  : Möglichkeit, mehreren Befehlen Widgets zuzuweisen.
-  : Hinzufügen eines horizontalen numerischen Info-Widgets.
-  : Hinzufügen eines vertikalen numerischen Info-Widgets.
-  : Hinzufügen eines Widgets für numerische Kompass- / Windinformationen (danke @thanaus).
-  : Ein numerisches Regeninfo-Widget wurde hinzugefügt (danke @thanaus)
-  : Anzeige des Info- / Action-Shutter-Widgets proportional zum Wert.<br/><br/>
- Aufbau : Verbesserung und Reorganisation von Registerkarten.
- Aufbau : Hinzufügung von vielen ** (aide).
- Aufbau : Hinzufügen einer Suchmaschine.
- Aufbau : Es wurde eine Schaltfläche hinzugefügt, um den Cache der Widgets zu leeren (Registerkarte Cache)).
- Aufbau : Es wurde eine Option hinzugefügt, um den Cache von Widgets zu deaktivieren (Registerkarte Cache)).
- Aufbau : Möglichkeit der vertikalen Zentrierung des Inhalts der Kacheln (Registerkarte Schnittstelle)).
- Aufbau : Es wurde ein Parameter für die globale Bereinigung von Protokollen hinzugefügt (Registerkarte "Bestellungen")).
- Aufbau : Änderung von #message# beim #subject# in Konfiguration / Protokolle / Nachrichten, um das Duplizieren der Nachricht zu vermeiden.
- Aufbau : Möglichkeit in den Zusammenfassungen, einen Ausschluss von Aufträgen hinzuzufügen, die nicht länger als XX Minuten aktualisiert wurden (Beispiel für die Berechnung von Temperaturdurchschnitten, wenn ein Sensor länger als 30 Minuten nichts gemeldet hat, wird er von der Berechnung ausgeschlossen)<br/><br/>
- Szenario : Die Färbung von Blöcken erfolgt nicht mehr zufällig, sondern nach Blocktyp.
- Szenario : Möglichkeit durch Strg + Klick auf die Schaltfläche *Ausführung* Speichern Sie es, starten Sie es und zeigen Sie das Protokoll an (wenn die Protokollebene nicht aktiviert ist **).
- Szenario : Bestätigung der Blocklöschung. Strg + Klicken, um eine Bestätigung zu vermeiden.
- Szenario : Hinzufügen einer Suchfunktion in Codeblöcken.  : Strg + F dann Enter, Nächstes Ergebnis : Strg + G, Vorheriges Ergebnis : Strg + Umschalt + G
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
-  : Möglichkeit zur Angabe der Reihenfolge (Position) des ** und *3D-Designs* (Bearbeiten, Design konfigurieren).
-  : Hinzufügen eines benutzerdefinierten CSS-Felds zu den Elementen des **.
-  : Die Anzeigeoptionen im Design der erweiterten Konfiguration wurden in den Anzeigeparametern aus dem verschoben **. Dies, um die Schnittstelle zu vereinfachen und verschiedene Parameter durch zu ermöglichen **.
-  : Verschieben und Ändern der Größe von Komponenten ** berücksichtigt ihre Größe mit oder ohne Magnetisierung.<br/><br/>
- Hinzufügen eines Massenkonfigurationssystems (wird auf der Seite Ausrüstung verwendet, um Kommunikationswarnungen darauf zu konfigurieren)

### 4.0 : Autres

- **** : Aktualisieren Sie jquery 3.4.1
- **** : Aktualisieren Sie CodeMiror 5.46.0
- **** : Tablesorter aktualisieren 2.31.1
- Allgemeine Aufhellung (CSS / Inline-Stile, Refactoring usw.) und Leistungsverbesserungen.
- Hinzufügung der globalen Kompatibilität von Jeedom DNS mit einer 4G-Internetverbindung.
- Zahlreiche Fehlerbehebungen.
- Sicherheitskorrekturen.

### 4.0 : Changements

- Entfernen Sie Font Awesome 4, um nur Font Awesome 5 beizubehalten.
- Das Widget-Plugin ist nicht mit dieser Version von Jeedom kompatibel und wird nicht mehr unterstützt (da die Funktionen intern im Kern übernommen wurden). Mehr Informationen [](https://www.Jeedom.com/blog/4368-les-widgets-en-v4).

>****
>
> Wenn nach dem Update ein Fehler im Dashboard auftritt, versuchen Sie, Ihre Box neu zu starten, damit die neuen Ergänzungen von Komponenten berücksichtigt werden.
