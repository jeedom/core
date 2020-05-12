# Changelog Jeedom V4.1

## 4.1.0

- Synthese : Neue Seite hinzufügen **Home → Zusammenfassung** bietet eine globale visuelle Synthese der Teile.
- Suche : Hinzufügen einer Suchmaschine in **Extras → Suchen**.
- Armaturenbrett : Im Bearbeitungsmodus wird jetzt die verschobene Kachel eingefügt.
- Armaturenbrett : Wir können jetzt auf die klicken *Zeit* Zeitaktions-Widgets zum Öffnen des Verlaufsfensters des Befehls "Verknüpfte Informationen".
- Armaturenbrett : Die Größe der Kachel eines neuen Geräts passt sich dem Inhalt an.
- Armaturenbrett : Hinzufügen einer Schaltfläche zum Filtern der angezeigten Elemente nach Kategorie.
- Armaturenbrett : Strg Klicken Sie auf eine Info, um das Verlaufsfenster mit allen historisierten Befehlen der auf der Kachel sichtbaren Ausrüstung zu öffnen. Strg Klicken Sie auf eine Legende, um nur diese anzuzeigen. Alt Klicken Sie, um alle anzuzeigen.
- Armaturenbrett : Möglichkeit, Hintergrundbilder zu verwischen (Konfiguration -> Benutzeroberfläche).
- Tools / Widgets : Die Funktion *Bewerben Sie sich am* Zeigt die aktivierten verknüpften Befehle an. Wenn Sie diese Option deaktivieren, wird das Standard-Kern-Widget auf diesen Befehl angewendet.
- Widgets : Möglichkeit, einem Widget Klassen-CSS hinzuzufügen (siehe Widget-Dokumentation).
- Widgets : Hinzufügen eines Kern-Widgets *sliderVertical*.
- Update Center : Aktualisierungen werden beim Öffnen der Seite automatisch überprüft, wenn sie 120 Minuten älter ist.
- Update Center : Der Fortschrittsbalken befindet sich jetzt auf der Registerkarte *Core und Plugins*, und das Protokoll wird standardmäßig auf der Registerkarte geöffnet *Information*.
- Update Center : Wenn Sie während eines Updates einen anderen Browser öffnen, wird dies in der Fortschrittsanzeige und im Protokoll angezeigt.
- Update Center : Wenn das Update korrekt abgeschlossen wurde, wird ein Fenster angezeigt, in dem Sie aufgefordert werden, die Seite neu zu laden.
- Kernupdates : Implementierung eines Systems zum Bereinigen alter nicht verwendeter Core-Dateien.
- Widgets / Objekte / Szenarien / Interaktionen / Plugins Seiten :
	- Strg Clic / Clic Center auf einem Widget, Objekt, Szenarien, Interaktion, Plugin-Ausrüstung : Wird in einem neuen Tab geöffnet.
	- Ctrl Clic / Clic Center ist auch in den Kontextmenüs verfügbar (auf den Registerkarten).
- Neue ModalDisplay-Seite:
	- Analysemenü : Strg Klicken / Klicken Sie auf Mitte *Echtzeit* : Öffnen Sie das Fenster in einer neuen Registerkarte im Vollbildmodus.
	- Menü Extras : Strg Klicken / Klicken Sie auf Mitte *Hinweis*, *Expressionstester*, *Variablen*, *Suche* : Öffnen Sie das Fenster in einer neuen Registerkarte im Vollbildmodus.
- Szenario : Hinzufügen einer Suchmaschine (links von der Schaltfläche Ausführen).
- Szenario : Hinzufügung der Altersfunktion (gibt das Alter des Wertes der Bestellung an).
- Szenario : *stateChanges ()* Akzeptieren Sie jetzt den Zeitraum *Heute* (von Mitternacht bis jetzt), *gestern* und *Tag* (für 1 Tag).
- Szenario : Funktionen *Statistik (), Durchschnitt (), Max (), Min (), Trend (), Dauer ()* : Bugfix über den Zeitraum *gestern*, und jetzt akzeptieren *Tag* (für 1 Tag).
- Szenario : Möglichkeit, das automatische Angebotssystem zu deaktivieren (Einstellungen → System → Konfiguration : Befehle).
- Szenario : Anzeigen a *Warnung* wenn kein Trigger konfiguriert ist.
- Szenario : Bugfix von Select auf Copy / Paste Block.
- Szenario : Kopieren / Einfügen eines Blocks zwischen verschiedenen Szenarien.
- Szenario : Die Funktionen zum Rückgängigmachen / Wiederherstellen sind jetzt in Form von Schaltflächen verfügbar (neben der Schaltfläche zum Erstellen von Blöcken).
- Fenster &quot;Szenariovariablen&quot; : alphabetische Sortierung beim Öffnen.
- Analyse / Geschichte : Strg Klicken Sie auf eine Legende, um nur diesen Verlauf anzuzeigen. Alt Klicken Sie, um alle anzuzeigen.
- Analyse / Geschichte : Die Optionen *Gruppierung, Typ, Variation, Treppe* sind nur mit einer einzigen angezeigten Kurve aktiv.
- Analyse / Geschichte : Wir können jetzt die Option verwenden *Fläche* mit der Option *Treppe*.
- Ansicht : Möglichkeit, Szenarien zu setzen.
- Chronik : Integration der Timeline in die DB aus Zuverlässigkeitsgründen.
- Chronik : Verwaltung mehrerer Zeitleisten.
- Chronik : Überarbeitung der Timeline-Grafik.
- Zusammenfassung der Hausautomation : Plugin-Geräte sind deaktiviert und ihre Steuerelemente haben nicht mehr die Symbole auf der rechten Seite (Gerätekonfiguration und erweiterte Konfiguration).
- Zusammenfassung der Hausautomation : Möglichkeit zur Suche nach Gerätekategorien.
- Zusammenfassung der Hausautomation : Möglichkeit, mehrere Geräte von einem Objekt zum anderen zu bewegen.
- Zusammenfassung der Hausautomation : Möglichkeit, alle Geräte eines Objekts auszuwählen.
- Task-Engine : Auf der Registerkarte *Dämon*, deaktivierte Plugins werden nicht mehr angezeigt.
- Konfiguration : Die Registerkarte *Information* ist jetzt in der Registerkarte *Allgemein*.
- Konfiguration : Die Registerkarte *Befehle* ist jetzt in der Registerkarte *Geräte*.
- Fenster zur erweiterten Gerätekonfiguration : Dynamische Änderung der Schalttafelkonfiguration.
- Geräte : Neue Kategorie *Öffnen*.
- Über Fenster : Hinzufügen von Verknüpfungen zu Changelog und FAQ.<br/><br/>
- WebApp : Integration der neuen Übersichtsseite.
- WebApp : Auf der Seite &quot;Szenarien&quot; wird durch Klicken auf den Szenariotitel das Protokoll angezeigt.
- WebApp : Wir können jetzt einen Teil eines Protokolls auswählen / kopieren.
- WebApp : Fügen Sie bei der Suche in einem Protokoll eine x-Schaltfläche hinzu, um die Suche abzubrechen.
- WebApp : Persistenz des Themas umschalten (8h).
- WebApp : Bei einem Design kehrt ein Klick mit drei Fingern zur Startseite zurück.
- WebApp : Anzeige von Szenarien nach Gruppe.
- WebApp : Viele Fehlerbehebungen (Benutzeroberfläche, Hoch- / Querformat iOS usw.).<br/><br/>
- Dokumentation : Anpassungen gemäß v4 und v4.1.
- Dokumentation : Neue Seite *Tastatur- / Mausverknüpfungen* einschließlich einer Zusammenfassung aller Verknüpfungen in Jeedom. Zugriff über das Dashboard-Dokument oder die FAQ.
- Fehlerbehebungen und Optimierungen.
- Lib : Aktualisieren Sie HighStock v7.1.2 bis v8.1.0.
- Lib : Aktualisieren Sie jQuery v3.4.1 bis v3.5.1.
- Lib : Update Font Awesome 5.9.0 bis 5.13.0.


### 4.0.53

- Fehlerbehebung.

### 4.0.52

- Fehlerkorrektur (Update muss unbedingt durchgeführt werden, wenn Sie sich in 4.0.51 befinden).

### 4.0.51

- Fehlerbehebungen.
- Optimierung des zukünftigen DNS-Systems.

### 4.0.49

- Möglichkeit, den Jeedom TTS-Motor zu wählen und Plugins zu haben, die einen neuen TTS-Motor bieten.
- Verbesserte Unterstützung für Webview in der mobilen Anwendung.
- Fehlerbehebungen.
- Aktualisieren des Dokuments.

### 4.0.47

- Verbesserung des Expressionstesters.
- Aktualisieren des Repositorys auf smart.
- Fehlerbehebungen.

### 4.0.44

- Verbesserte Übersetzungen.
- Fehlerbehebungen.
- Verbesserte Wiederherstellung von Cloud-Backups.
- Die Cloud-Wiederherstellung repatriiert nur noch das lokale Backup und lässt die Wahl, es herunterzuladen oder wiederherzustellen.

### 4.0.43

- Verbesserte Übersetzungen.
- Fehlerbehebungen bei Szenariovorlagen.

## 4.0.0
- Komplette Neugestaltung der Themen (Core 2019 Light / Dark / Legacy).
- Möglichkeit, das Thema automatisch nach Zeit zu ändern.
- In Mobilgeräten kann sich das Thema je nach Helligkeit ändern (Aktivierung erforderlich) *generischer zusätzlicher Sensor* in Chrom, Chromseite://flags).<br/><br/>
- Verbesserung und Neuorganisation des Hauptmenüs.
- Plugins-Menü : Die Liste der Kategorien und Plugins ist jetzt alphabetisch sortiert.
- Menü Extras : Hinzufügen einer Schaltfläche für den Zugriff auf den Ausdruckstester.
- Menü Extras : Hinzufügen einer Schaltfläche für den Zugriff auf die Variablen.<br/><br/>
- Suchfelder unterstützen jetzt Akzente.
- Die Suchfelder (Dashboard, Szenarien, Objekte, Widgets, Interaktionen, Plugins) sind jetzt beim Öffnen der Seite aktiv, sodass Sie eine Suche direkt eingeben können.
- Fügen Sie den Suchfeldern eine X-Schaltfläche hinzu, um die Suche abzubrechen.
- Während einer Suche wird der Schlüssel *Flucht* Suche abbrechen.
- Armaturenbrett : Im Bearbeitungsmodus sind das Suchfeld und seine Schaltflächen deaktiviert und werden behoben.
- Armaturenbrett : Klicken Sie im Bearbeitungsmodus auf eine Schaltfläche *erweitern* Rechts neben den Objekten werden die Kacheln des Objekts auf die höchste Höhe angepasst. Strg + Klick reduziert sie auf die niedrigste Höhe.
- Armaturenbrett : Die Befehlsausführung auf einer Kachel wird nun durch die Schaltfläche signalisiert *Aktualisieren*. Wenn sich keine auf der Kachel befindet, wird sie während der Ausführung angezeigt.
- Armaturenbrett : Die Kacheln zeigen einen Info-Befehl (Verlauf, der das Verlaufsfenster öffnet) oder eine Aktion beim Schweben an.
- Armaturenbrett : Im Verlaufsfenster können Sie diesen Verlauf jetzt in Analyse / Verlauf öffnen.
- Armaturenbrett : Das Verlaufsfenster behält seine Position / Dimensionen bei, wenn ein anderer Verlauf erneut geöffnet wird.
- Befehlskonfigurationsfenster: Strg + Klick auf &quot;Speichern&quot; schließt das Fenster danach.
- Fenster Gerätekonfiguration: Strg + Klick auf &quot;Speichern&quot; schließt das Fenster danach.
- Hinzufügen von Nutzungsinformationen beim Löschen von Geräten.
- Objekte : Option zur Verwendung benutzerdefinierter Farben hinzugefügt.
- Objekte : Kontextmenü auf Registerkarten hinzufügen (schnelle Objektänderung).
- Wechselwirkungen : Kontextmenü auf Registerkarten hinzufügen (schnelle Änderung der Interaktion).
- Plugins : Kontextmenü auf Registerkarten hinzufügen (schneller Gerätewechsel).
- Plugins : Auf der Plugins-Verwaltungsseite zeigt ein orangefarbener Punkt nicht stabile Plugins an.
- Tabellenverbesserungen mit Filter- und Sortieroption.
- Möglichkeit, einer Interaktion ein Symbol zuzuweisen.
- Jede Jeedom-Seite hat jetzt einen Titel in der Sprache der Benutzeroberfläche (Registerkarte Browser).
- Verhinderung des automatischen Ausfüllens des Zugangscodes von Feldern'.
- Funktionsverwaltung *Vorherige Seite / Nächste Seite* Browser.<br/><br/>
- Widgets : Neugestaltung des Widget-Systems (Menü Extras / Widgets).
- Widgets : Möglichkeit, ein Widget bei allen Befehlen, die es verwenden, durch ein anderes zu ersetzen.
- Widgets : Möglichkeit, mehreren Befehlen ein Widget zuzuweisen.
- Widgets : Fügen Sie ein horizontales numerisches Info-Widget hinzu.
- Widgets : Hinzufügen eines vertikalen Info-Widgets.
- Widgets : Hinzufügen eines Info-numerischen Kompass- / Wind-Widgets (danke @thanaus).
- Widgets : Hinzufügen eines numerischen Regen-Widgets (danke @thanaus)
- Widgets : Anzeige des Info- / Action-Shutter-Widgets proportional zum Wert.<br/><br/>
- Konfiguration : Verbesserung und Reorganisation von Registerkarten.
- Konfiguration : Viele hinzufügen *Tooltips* (Hilfe).
- Konfiguration : Hinzufügen einer Suchmaschine.
- Konfiguration : Hinzufügen einer Schaltfläche zum Leeren des Widget-Cache (Registerkarte Cache).
- Konfiguration : Option zum Deaktivieren des Widget-Cache hinzugefügt (Registerkarte Cache).
- Konfiguration : Möglichkeit, den Inhalt der Kacheln vertikal zu zentrieren (Registerkarte &quot;Schnittstelle&quot;).
- Konfiguration : Hinzufügen eines Parameters zum globalen Löschen der Historien (Tab-Befehle).
- Konfiguration : Änderung von #message# Bis #subject# in Konfiguration / Protokolle / Nachrichten, um Doppelarbeit der Nachricht zu vermeiden.
- Konfiguration : Möglichkeit in den Zusammenfassungen, einen Ausschluss von Aufträgen hinzuzufügen, die nicht länger als XX Minuten aktualisiert wurden (Beispiel für die Berechnung der Temperaturdurchschnitte, wenn ein Sensor länger als 30 Minuten nichts angehoben hat, wird er von der Berechnung ausgeschlossen )<br/><br/>
- Szenario : Die Färbung der Blöcke ist nicht mehr zufällig, sondern nach Blocktyp.
- Szenario : Möglichkeit durch Strg + Klick auf die Schaltfläche *Ausführung* Speichern Sie es, starten Sie es und zeigen Sie das Protokoll an (wenn die Protokollebene nicht aktiviert ist *Ohne*).
- Szenario : Löschbestätigung blockieren. Strg + Klicken, um eine Bestätigung zu vermeiden.
- Szenario : Hinzufügen einer Suchfunktion in den Codeblöcken. Suche : Strg + F dann Enter, Nächstes Ergebnis : Strg + G, Vorheriges Ergebnis : Strg + Umschalt + G
- Szenario : Fähigkeit, Blöcke zu verdichten.
- Szenario : Die Aktion &quot;Block hinzufügen&quot; wechselt bei Bedarf zur Registerkarte &quot;Szenario&quot;.
- Szenario : Neue Funktionen zum Kopieren / Einfügen von Blöcken. Strg + Klicken zum Ausschneiden / Ersetzen.
- Szenario : Ein neuer Block wird am Ende des Szenarios nicht mehr hinzugefügt, sondern nach dem Block, in dem Sie sich vor dem Klicken befanden. Dies wird durch das letzte Feld bestimmt, in das Sie geklickt haben.
- Szenario : Implementierung eines Rückgängig- / Wiederherstellungssystems (Strg + Umschalt + Z / Strg + Umschalt + Y).
- Szenario : Szenariofreigabe löschen.
- Szenario : Verbesserung des Verwaltungsfensters für Szenariovorlagen.<br/><br/>
- Analyse / Ausrüstung : Hinzufügen einer Suchmaschine (Registerkarte Batterien, Suche nach Namen und Eltern).
- Analyse / Ausrüstung : Die Kalender- / Tageszone des Geräts kann jetzt angeklickt werden, um direkt auf den Batteriewechsel zuzugreifen.
- Analyse / Ausrüstung : Hinzufügen eines Suchfeldes.<br/><br/>
- Update Center : Warnung auf der Registerkarte &quot;Core and Plugins&quot; und / oder &quot;Others&quot;, wenn ein Update verfügbar ist. Wechseln Sie bei Bedarf zu &quot;Andere&quot;.
- Update Center : Differenzierung nach Version (stabil, Beta, ...).
- Update Center : Hinzufügen eines Fortschrittsbalkens während des Updates.<br/><br/>
- Zusammenfassung der Hausautomation : Der Löschverlauf ist jetzt auf einer Registerkarte verfügbar (Zusammenfassung - Verlauf).
- Zusammenfassung der Hausautomation : Komplette Überholung, Möglichkeit der Bestellung von Gegenständen, Ausrüstung, Bestellungen.
- Zusammenfassung der Hausautomation : Hinzufügen von Ausrüstungs- und Bestell-IDs in Anzeige und Suche.
- Zusammenfassung der Hausautomation : CSV-Export des übergeordneten Objekts, der ID, der Ausrüstung und ihrer ID, des Befehls.
- Zusammenfassung der Hausautomation : Möglichkeit, eine oder mehrere Bestellungen sichtbar zu machen oder nicht.<br/><br/>
- Design : Möglichkeit, die Reihenfolge (Position) von anzugeben *Design* und *Design 3D* (Bearbeiten, Design konfigurieren).
- Design : Hinzufügen eines benutzerdefinierten CSS-Felds zu den Elementen des *Design*.
- Design : Verschiebung der Anzeigeoptionen im Design der erweiterten Konfiguration, in den Anzeigeparametern aus dem *Design*. Dies, um die Schnittstelle zu vereinfachen und um zu ermöglichen, unterschiedliche Parameter durch zu haben *Design*.
- Design : Verschieben und Ändern der Größe von Komponenten *Design* berücksichtigt ihre Größe mit oder ohne Magnetisierung.<br/><br/>
- Allgemeine Reduzierung (CSS / Inline-Stile, Refactoring usw.) und Leistungsverbesserungen.
- Entfernen Sie Font Awesome 4, um nur Font Awesome 5 beizubehalten.
- Libs Update : jquery 3.4.1, CodeMiror 5.46.0, Tablesorter 2.31.1.
- Zahlreiche Fehlerbehebungen.
- Hinzufügen eines Massenkonfigurationssystems (wird auf der Seite Ausrüstung verwendet, um den Kommunikationsalarm für diese zu konfigurieren)

>**WICHTIG**
>
>Wenn Sie nach dem Update einen Fehler im Dashboard haben, versuchen Sie, Ihre Box neu zu starten, damit die neuen Ergänzungen von Komponenten berücksichtigt werden.

>**WICHTIG**
>
>Das Widget-Plugin ist nicht mit dieser Version von Jeedom kompatibel und wird nicht mehr unterstützt (da die Funktionen intern im Kern übernommen wurden). Weitere Informationen [hier](https://www.Jeedom.com/blog/4368-les-widgets-en-v4).
