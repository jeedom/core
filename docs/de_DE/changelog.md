# Changelog Jeedom V4.1

## 4.1.0

### Voraussetzungen

- Debian 10 Buster

### Neuigkeiten / Verbesserungen

- **Synthese** : Neue Seite hinzufügen **Home → Zusammenfassung** Bietet eine globale visuelle Zusammenfassung der Teile mit schnellem Zugriff auf Zusammenfassungen.
- **Suche** : Hinzufügen einer Suchmaschine in **Extras → Suchen**.
- **Armaturenbrett** : Im Bearbeitungsmodus wird jetzt die verschobene Kachel eingefügt.
- **Armaturenbrett** : Bearbeitungsmodus: Die Geräteaktualisierungssymbole werden durch ein Symbol ersetzt, das dank eines neuen vereinfachten Modals den Zugriff auf ihre Konfiguration ermöglicht.
- **Armaturenbrett** : Wir können jetzt auf die klicken *Zeit* Zeitaktions-Widgets zum Öffnen des Verlaufsfensters des Befehls "Verknüpfte Informationen".
- **Armaturenbrett** : Die Größe der Kachel eines neuen Geräts passt sich dem Inhalt an.
- **Armaturenbrett** : Hinzufügen (zurück!) Eine Schaltfläche zum Filtern der angezeigten Elemente nach Kategorie.
- **Armaturenbrett** : Strg Klicken Sie auf eine Info, um das Verlaufsfenster mit allen historisierten Befehlen der auf der Kachel sichtbaren Ausrüstung zu öffnen. Strg Klicken Sie auf eine Legende, um nur diese anzuzeigen. Alt Klicken Sie, um alle anzuzeigen.
- **Armaturenbrett** : Neugestaltung der Anzeige des Objektbaums (Pfeil links neben der Suche).
- **Armaturenbrett** : Möglichkeit, Hintergrundbilder zu verwischen (Konfiguration -> Benutzeroberfläche)).
- **Tools / Widgets** : Die Funktion *Bewerben Sie sich am* Zeigt die aktivierten verknüpften Befehle an. Wenn Sie diese Option deaktivieren, wird das Standard-Kern-Widget auf diesen Befehl angewendet.
- **Widgets** : Hinzufügen eines Kern-Widgets *sliderVertical*.
- **Widgets** : Hinzufügen eines Kern-Widgets *binarySwitch*.
- **Update Center** : Aktualisierungen werden beim Öffnen der Seite automatisch überprüft, wenn sie 120 Minuten älter ist.
- **Update Center** : Der Fortschrittsbalken befindet sich jetzt auf der Registerkarte *Core und Plugins*, und das Protokoll wird standardmäßig auf der Registerkarte geöffnet *Information*.
- **Update Center** : Wenn Sie während eines Updates einen anderen Browser öffnen, wird dies in der Fortschrittsanzeige und im Protokoll angezeigt.
- **Update Center** : Wenn das Update korrekt abgeschlossen wurde, wird ein Fenster angezeigt, in dem Sie aufgefordert werden, die Seite neu zu laden.
- **Kernupdates** : Implementierung eines Systems zum Bereinigen alter nicht verwendeter Core-Dateien.
- **Szenario** : Hinzufügen einer Suchmaschine (links von der Schaltfläche Ausführen)).
- **Szenario** : Hinzufügung der Altersfunktion (gibt das Alter des Wertes der Bestellung an).
- **Szenario** : *stateChanges()* Akzeptieren Sie jetzt den Zeitraum *Heute* (Mitternacht bis jetzt), *gestern* und *Tag* (für 1 Tag).
- **Szenario** : Funktionen *Statistik (), Durchschnitt (), Max (), Min (), Trend (), Dauer()* : Bugfix über den Zeitraum *gestern*, und jetzt akzeptieren *Tag* (für 1 Tag).
- **Szenario** : Möglichkeit, das automatische Angebotssystem zu deaktivieren (Einstellungen → System → Konfiguration : Equipements).
- **Szenario** : Anzeigen a *Warnung* wenn kein Trigger konfiguriert ist.
- **Szenario** : Bugfix von *wählen* auf Block kopieren / einfügen.
- **Szenario** : Kopieren / Einfügen eines Blocks zwischen verschiedenen Szenarien.
- **Szenario** : Die Funktionen zum Rückgängigmachen / Wiederherstellen sind jetzt als Schaltflächen verfügbar (neben der Schaltfläche zum Erstellen von Blöcken)).
- **Szenario** :  Hinzufügung von "Historischer Export" (exportHistory)
- **Fenster &quot;Szenariovariablen&quot;** : Alphabetische Sortierung beim Öffnen.
- **Fenster &quot;Szenariovariablen&quot;** : Die von den Variablen verwendeten Szenarien können jetzt angeklickt werden, wobei die Suche für die Variable geöffnet wird.
- **Analyse / Geschichte** : Strg Klicken Sie auf eine Legende, um nur diesen Verlauf anzuzeigen. Alt Klicken Sie, um alle anzuzeigen.
- **Analyse / Geschichte** : Die Optionen *Gruppierung, Typ, Variation, Treppe* sind nur mit einer einzigen angezeigten Kurve aktiv.
- **Analyse / Geschichte** : Wir können jetzt die Option verwenden *Fläche* mit der Option *Treppe*.
- **Analyse / Protokolle** : Neue Monospace-Schriftart für Protokolle.
- **Ansicht** : Möglichkeit, Szenarien zu setzen.
- **Ansicht** : Im Bearbeitungsmodus wird jetzt die verschobene Kachel eingefügt.
- **Ansicht** : Bearbeitungsmodus: Die Geräteaktualisierungssymbole werden durch ein Symbol ersetzt, das dank eines neuen vereinfachten Modals den Zugriff auf ihre Konfiguration ermöglicht.
- **Ansicht** : Die Anzeigereihenfolge ist jetzt unabhängig von der im Dashboard.
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
- **Konfiguration** : Die Registerkarte *Information* ist jetzt in der Registerkarte *Allgemein*.
- **Konfiguration** : Die Registerkarte *Befehle* ist jetzt in der Registerkarte *Geräte*.
- **Fenster zur erweiterten Gerätekonfiguration** : Dynamische Änderung der Schalttafelkonfiguration.
- **Geräte** : Neue Kategorie *Öffnen*.
- **Geräte** : Möglichkeit des Invertierens von Cursortypbefehlen (Info und Aktion))
- **Geräte** : Möglichkeit, einer Kachel Klassen-CSS hinzuzufügen (siehe Widget-Dokumentation).
- **Über Fenster** : Hinzufügen von Verknüpfungen zu Changelog und FAQ.
- Widgets / Objekte / Szenarien / Interaktionen / Plugins Seiten :
	- Strg Clic / Clic Center auf einem Widget, Objekt, Szenarien, Interaktion, Plugin-Ausrüstung : Wird in einem neuen Tab geöffnet.
	- Ctrl Clic / Clic Center ist auch in den Kontextmenüs (auf den Registerkarten) verfügbar).
- Neue ModalDisplay-Seite :
	- Analysemenü : Strg Klicken / Klicken Sie auf Mitte *Echtzeit* : Öffnen Sie das Fenster in einer neuen Registerkarte im Vollbildmodus.
	- Menü Extras : Strg Klicken / Klicken Sie auf Mitte *Hinweis*, *Expressionstester*, *Variablen*, *Suche* : Öffnen Sie das Fenster in einer neuen Registerkarte im Vollbildmodus.
- Codeblock, Datei-Editor, Erweiterte Anpassung : Dunkle Themenanpassung.

### WebApp
- Integration der neuen Übersichtsseite.
- Auf der Seite &quot;Szenarien&quot; wird durch Klicken auf den Szenariotitel das Protokoll angezeigt.
- Wir können jetzt einen Teil eines Protokolls auswählen / kopieren.
- Fügen Sie bei der Suche in einem Protokoll eine x-Schaltfläche hinzu, um die Suche abzubrechen.
- Persistenz des Themenumschalters (8h).
- Bei einem Design kehrt ein Klick mit drei Fingern zur Startseite zurück.
- Anzeige von Szenarien nach Gruppe.
- Neue Monospace-Schriftart für Protokolle.
- Viele Fehlerbehebungen (Benutzeroberfläche, Hoch- / Querformat iOS usw.).).

### Autres
- **Dokumentation** : Anpassungen gemäß v4 und v4.1.
- **Dokumentation** : Neue Seite *Tastatur- / Mausverknüpfungen* einschließlich einer Zusammenfassung aller Verknüpfungen in Jeedom. Zugriff über das Dashboard-Dokument oder die FAQ.
- **Lib** : Aktualisieren Sie HighStock v7.1.2 bis v8.2.0.
- **Lib** : Aktualisieren Sie jQuery v3.4.1 bis v3.5.1.
- **Lib** : Update Font Awesome 5.9.0 bis 5.13.1.
- **API** :  Hinzufügung einer Option, um zu verhindern, dass ein API-Schlüssel eines Plugins Kernmethoden ausführt (allgemein))
- Sichern von Ajax-Anfragen.
- API-Aufrufe sichern.
- Fehlerbehebungen.
- Zahlreiche Leistungsoptimierungen für Desktop / Mobile.

### Changements
- Die Funktion **Szenario-> getHumanName()** der PHP-Szenario-Klasse wird nicht mehr zurückgegeben *[Objekt] [Gruppe] [Name]* Aber *[Gruppe] [Objekt] [Name]*.
- Die Funktion **Szenario-> byString()** muss nun mit der Struktur aufgerufen werden *[Gruppe] [Objekt] [Name]*.
- Funktionen **network-> getInterfaceIp () network-> getInterfaceMac () network-> getInterfaces()** wurden ersetzt durch **network-> getInterfacesInfo()**


# Changelog Jeedom V4.0

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
- Erhöhen Sie die Anzahl der fehlerhaften Kennwörter vor dem Verbannen (vermeidet Probleme mit der Webanwendung beim Drehen von API-Schlüsseln)

## 4.0.57

- Verstärkung der Cookie-Sicherheit
- Verwendung von Chrom (falls installiert) für Berichte
- Es wurde ein Problem beim Berechnen der Statuszeit für Widgets behoben, wenn die Zeitzone jeedom nicht mit der des Browsers übereinstimmt
- Fehlerbehebungen

## 4.0.55

- Die neuen DNS (\*. Eu.jeedom.Link) wird zum primären DNS (der alte DNS funktioniert immer noch)

## 4.0.54

- Start des Updates für die neue Dokumentationssite

## 4.0.53

- Fehlerbehebung.

## 4.0.52

- Fehlerkorrektur (Update unbedingt durchführen, wenn Sie in 4.0.51 sind).

## 4.0.51

- Fehlerbehebungen.
- Optimierung des zukünftigen DNS-Systems.

## 4.0.49

- Möglichkeit, den Jeedom TTS-Motor zu wählen und Plugins zu haben, die einen neuen TTS-Motor bieten.
- Verbesserte Webview-Unterstützung in der mobilen Anwendung.
- Fehlerbehebungen.
- Aktualisieren des Dokuments.

## 4.0.47

- Verbesserter Expressionstester.
- Aktualisieren des Repositorys auf smart.
- Fehlerbehebungen.

## 4.0.44

- Verbesserte Übersetzungen.
- Fehlerbehebungen.
- Verbesserte Wiederherstellung von Cloud-Backups.
- Bei der Cloud-Wiederherstellung wird nur noch das lokale Backup abgerufen, sodass Sie es herunterladen oder wiederherstellen können.

## 4.0.43

- Verbesserte Übersetzungen.
- Fehler in Szenariovorlagen behoben.

## 4.0.0
- Komplette Neugestaltung des Themas (Core 2019 Light / Dark / Legacy)).
- Möglichkeit, das Thema je nach Zeit automatisch zu ändern.
- In Mobilgeräten kann sich das Thema je nach Helligkeit ändern (Aktivierung erforderlich) *generischer zusätzlicher Sensor* in Chrom, Chromseite://flags).<br/><br/>
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
- Armaturenbrett : Die Befehlsausführung auf einer Kachel wird jetzt durch die Schaltfläche signalisiert *Aktualisieren*. Wenn sich keine auf der Kachel befindet, wird sie während der Ausführung angezeigt.
- Armaturenbrett : Die Kacheln zeigen einen Infobefehl (historisiert, wodurch das Verlaufsfenster geöffnet wird) oder eine Aktion beim Schweben an.
- Armaturenbrett : Im Verlaufsfenster können Sie diesen Verlauf jetzt in Analyse / Verlauf öffnen.
- Armaturenbrett : Das Verlaufsfenster behält seine Position / Abmessungen bei, wenn ein anderer Verlauf erneut geöffnet wird.
- Befehlskonfigurationsfenster: Strg + Klick auf "Speichern" schließt das Fenster danach.
- Fenster Gerätekonfiguration: Strg + Klick auf "Speichern" schließt das Fenster danach.
- Fügen Sie beim Löschen eines Geräts Nutzungsinformationen hinzu.
- Objekte : Option zur Verwendung benutzerdefinierter Farben hinzugefügt.
- Objekte : Hinzufügen eines Kontextmenüs auf den Registerkarten (schneller Objektwechsel).
- Interaktion : Hinzufügen eines Kontextmenüs auf den Registerkarten (schnelle Änderung der Interaktion).
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
- Konfiguration : Verbesserung und Reorganisation von Registerkarten.
- Konfiguration : Hinzufügung von vielen *Tooltips* (aide).
- Konfiguration : Hinzufügen einer Suchmaschine.
- Konfiguration : Es wurde eine Schaltfläche hinzugefügt, um den Cache der Widgets zu leeren (Registerkarte Cache)).
- Konfiguration : Es wurde eine Option hinzugefügt, um den Cache von Widgets zu deaktivieren (Registerkarte Cache)).
- Konfiguration : Möglichkeit der vertikalen Zentrierung des Inhalts der Kacheln (Registerkarte Schnittstelle)).
- Konfiguration : Es wurde ein Parameter für die globale Bereinigung von Protokollen hinzugefügt (Registerkarte "Bestellungen")).
- Konfiguration : Änderung von #message# Bis #subject# in Konfiguration / Protokolle / Nachrichten, um das Duplizieren der Nachricht zu vermeiden.
- Konfiguration : Möglichkeit in den Zusammenfassungen, einen Ausschluss von Aufträgen hinzuzufügen, die nicht länger als XX Minuten aktualisiert wurden (Beispiel für die Berechnung von Temperaturdurchschnitten, wenn ein Sensor länger als 30 Minuten nichts gemeldet hat, wird er von der Berechnung ausgeschlossen)<br/><br/>
- Szenario : Die Färbung von Blöcken erfolgt nicht mehr zufällig, sondern nach Blocktyp.
- Szenario : Möglichkeit durch Strg + Klick auf die Schaltfläche *Ausführung* Speichern Sie es, starten Sie es und zeigen Sie das Protokoll an (wenn die Protokollebene nicht aktiviert ist *Ohne*).
- Szenario : Bestätigung der Blocklöschung. Strg + Klicken, um eine Bestätigung zu vermeiden.
- Szenario : Hinzufügen einer Suchfunktion in Codeblöcken. Suchen nach : Strg + F dann Enter, Nächstes Ergebnis : Strg + G, Vorheriges Ergebnis : Strg + Umschalt + G
- Szenario : Möglichkeit der Verdichtung der Blöcke.
- Szenario : Die Aktion "Block hinzufügen" wechselt bei Bedarf zur Registerkarte "Szenario".
- Szenario : Neue Funktionen zum Kopieren / Einfügen von Blöcken. Strg + Klicken zum Ausschneiden / Ersetzen.
- Szenario : Ein neuer Block wird nicht mehr am Ende der Zeitleiste hinzugefügt, sondern nach dem Block, in dem Sie sich vor dem Klicken befanden, bestimmt durch das zuletzt angeklickte Feld.
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
- Design : Möglichkeit zur Angabe der Reihenfolge (Position) des *Design* und *3D-Designs* (Bearbeiten, Design konfigurieren).
- Design : Hinzufügen eines benutzerdefinierten CSS-Felds zu den Elementen des *Design*.
- Design : Die Anzeigeoptionen im Design der erweiterten Konfiguration wurden in den Anzeigeparametern aus dem verschoben *Design*. Dies, um die Schnittstelle zu vereinfachen und verschiedene Parameter durch zu ermöglichen *Design*.
- Design : Verschieben und Ändern der Größe von Komponenten *Design* berücksichtigt ihre Größe mit oder ohne Magnetisierung.<br/><br/>
- Allgemeine Aufhellung (CSS / Inline-Stile, Refactoring usw.) und Leistungsverbesserungen.
- Entfernen Sie Font Awesome 4, um nur Font Awesome 5 beizubehalten.
- Libs Update : jquery 3.4.1, CodeMiror 5.46.0, Tablesorter 2.31.1.
- Zahlreiche Fehlerbehebungen.
- Hinzufügen eines Massenkonfigurationssystems (wird auf der Seite Ausrüstung verwendet, um Kommunikationswarnungen darauf zu konfigurieren)
- Hinzufügung der globalen Kompatibilität von Jeedom DNS mit einer 4G-Internetverbindung.
- Sicherheitsupdate

>**Wichtig**
>
>Wenn nach dem Update ein Fehler im Dashboard auftritt, versuchen Sie, Ihre Box neu zu starten, damit die neuen Ergänzungen von Komponenten berücksichtigt werden.

>**Wichtig**
>
>Das Widget-Plugin ist nicht mit dieser Version von Jeedom kompatibel und wird nicht mehr unterstützt (da die Funktionen intern im Kern übernommen wurden). Mehr Informationen [hier](https://www.Jeedom.com/blog/4368-les-widgets-en-v4).


