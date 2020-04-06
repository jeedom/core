Änderungsprotokoll
=========


=====

- 


=====

- Verbesserung des Expressionstesters
- Aktualisieren des Repositorys auf smart
- Fehlerbehebungen

4.0.44
=====

- Verbesserte Übersetzungen (ja nochmal).
- Fehlerbehebungen.
- Verbesserung der Wiederherstellung der Backup-Cloud.
- Durch die Cloud-Wiederherstellung wird nur die lokale Sicherung zurückgeführt, sodass Sie die Wahl haben, sie herunterzuladen oder wiederherzustellen.

4.0.43
=====

- Verbesserte Übersetzungen.
- Fehlerbehebungen bei Szenariovorlagen.

4.0.0
=====
- Komplette Neugestaltung der Themen (Core 2019 Light / Dark / Legacy).
- Möglichkeit, das Thema automatisch nach Zeit zu ändern.
- In Mobilgeräten kann sich das Thema je nach Helligkeit ändern (Erfordert die Aktivierung des * generischen zusätzlichen Sensors * in Chrom, Chromseite:// flags). <br/><br/>
- Verbesserung und Neuorganisation des Hauptmenüs.
- Plugins-Menü : Die Liste der Kategorien und Plugins ist jetzt alphabetisch sortiert.
- Menü Extras : Hinzufügen einer Schaltfläche für den Zugriff auf den Ausdruckstester.
- Menü Extras : Hinzufügen einer Schaltfläche für den Zugriff auf die Variablen. <br/><br/>
- Suchfelder unterstützen jetzt Akzente.
- Die Suchfelder (Armaturenbrett, Szenarien, Objekte, Widgets, Interaktionen, Plugins) sind jetzt beim Öffnen der Seite aktiv, sodass Sie eine Suche direkt eingeben können.
- Fügen Sie den Suchfeldern eine X-Schaltfläche hinzu, um die Suche abzubrechen.
- Während einer Suche bricht die Taste * Escape * die Suche ab.
- Armaturenbrett : Im Bearbeitungsmodus sind das Suchfeld und seine Schaltflächen deaktiviert und werden behoben.
- Armaturenbrett : Im Bearbeitungsmodus werden durch Klicken auf eine Schaltfläche * Erweitern * rechts neben den Objekten die Kacheln des Objekts auf die höchste Höhe geändert. Strg + Klick reduziert sie auf die niedrigste Höhe.
- Armaturenbrett : Die Auftragsausführung auf einer Kachel wird jetzt durch die Schaltfläche * Aktualisieren angezeigt*. Wenn sich keine auf der Kachel befindet, wird sie während der Ausführung angezeigt.
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
- Funktionsverwaltung * Vorherige Seite / Nächste Seite * des Browsers. <br/><br/>
- Widgets : Neugestaltung des Widget-Systems (Menü Extras / Widgets).
- Widgets : Möglichkeit, ein Widget bei allen Befehlen, die es verwenden, durch ein anderes zu ersetzen.
- Widgets : Möglichkeit, mehreren Befehlen ein Widget zuzuweisen.
- Widgets : Fügen Sie ein horizontales numerisches Info-Widget hinzu.
- Widgets : Hinzufügen eines vertikalen Info-Widgets.
- Widgets : Hinzufügen eines Info-numerischen Kompass- / Wind-Widgets (danke @thanaus).
- Widgets : Hinzufügen eines numerischen Regen-Widgets (danke @thanaus)
- Widgets : Anzeige des Info- / Action-Shutter-Widgets proportional zum Wert. <br/><br/>
- Konfiguration : Verbesserung und Reorganisation von Registerkarten.
- Konfiguration : Viele * Tooltips * hinzugefügt (Hilfe).
- Konfiguration : Hinzufügen einer Suchmaschine.
- Konfiguration : Hinzufügen einer Schaltfläche zum Leeren des Widget-Cache (Registerkarte Cache).
- Konfiguration : Option zum Deaktivieren des Widget-Cache hinzugefügt (Registerkarte Cache).
- Konfiguration : Möglichkeit, den Inhalt der Kacheln vertikal zu zentrieren (Registerkarte &quot;Schnittstelle&quot;).
- Konfiguration : Hinzufügen eines Parameters zum globalen Löschen der Historien (Tab-Befehle).
- Konfiguration : Wechseln Sie in Konfiguration / Protokolle / Nachrichten von # Nachricht # zu # Betreff #, um Doppelarbeit der Nachricht zu vermeiden.
- Konfiguration : Möglichkeit in den Zusammenfassungen, einen Ausschluss von Aufträgen hinzuzufügen, die nicht länger als XX Minuten aktualisiert wurden (Beispiel für die Berechnung der Temperaturdurchschnitte, wenn ein Sensor länger als 30 Minuten nichts angehoben hat, wird er von der Berechnung ausgeschlossen ) <br/><br/>
- Szenario : Die Färbung der Blöcke ist nicht mehr zufällig, sondern nach Blocktyp.
- Szenario : Möglichkeit durch Strg + Klicken auf die Schaltfläche * Ausführung *, um es zu speichern, zu starten und das Protokoll anzuzeigen (wenn die Protokollebene nicht aktiviert ist * Keine *).
- Szenario : Löschbestätigung blockieren. Strg + Klicken, um eine Bestätigung zu vermeiden.
- Szenario : Hinzufügen einer Suchfunktion in den Codeblöcken. Suche : Strg + F dann Enter, Nächstes Ergebnis : Strg + G, Vorheriges Ergebnis : Strg + Umschalt + G.
- Szenario : Fähigkeit, Blöcke zu verdichten.
- Szenario : Die Aktion &quot;Block hinzufügen&quot; wechselt bei Bedarf zur Registerkarte &quot;Szenario&quot;.
- Szenario : Neue Funktionen zum Kopieren / Einfügen von Blöcken. Strg + Klicken zum Ausschneiden / Ersetzen.
- Szenario : Ein neuer Block wird am Ende des Szenarios nicht mehr hinzugefügt, sondern nach dem Block, in dem Sie sich vor dem Klicken befanden. Dies wird durch das letzte Feld bestimmt, in das Sie geklickt haben.
- Szenario : Implementierung eines Rückgängig- / Wiederherstellungssystems (Strg + Umschalt + Z / Strg + Umschalt + Y).
- Szenario : Szenariofreigabe löschen.
- Szenario : Verbesserung des Verwaltungsfensters für Szenariovorlagen. <br/><br/>
- Analyse / Ausrüstung : Hinzufügen einer Suchmaschine (Registerkarte Batterien, Suche nach Namen und Eltern).
- Analyse / Ausrüstung : Die Kalender- / Tageszone des Geräts kann jetzt angeklickt werden, um direkt auf den Batteriewechsel zuzugreifen..
- Analyse / Ausrüstung : Hinzufügen eines Suchfeldes. <br/><br/>
- Update Center : Warnung auf der Registerkarte &quot;Core and Plugins&quot; und / oder &quot;Others&quot;, wenn ein Update verfügbar ist. Wechseln Sie bei Bedarf zu &quot;Andere&quot;.
- Update Center : Differenzierung nach Version (stabil, Beta, ...).
- Update Center : Hinzufügen eines Fortschrittsbalkens während des Updates. <br/><br/>
- Zusammenfassung der Hausautomation : Der Löschverlauf ist jetzt auf einer Registerkarte verfügbar (Zusammenfassung - Verlauf).
- Zusammenfassung der Hausautomation : Komplette Überholung, Möglichkeit der Bestellung von Gegenständen, Ausrüstung, Bestellungen.
- Zusammenfassung der Hausautomation : Hinzufügen von Ausrüstungs- und Bestell-IDs in Anzeige und Suche.
- Zusammenfassung der Hausautomation : CSV-Export des übergeordneten Objekts, der ID, der Ausrüstung und ihrer ID, des Befehls.
- Zusammenfassung der Hausautomation : Möglichkeit, eine oder mehrere Bestellungen sichtbar zu machen oder nicht. <br/><br/>
- Design : Möglichkeit, die Reihenfolge (Position) von * Designs * und * 3D-Designs * anzugeben (Bearbeiten, Design konfigurieren).
- Design : Hinzufügen eines benutzerdefinierten CSS-Felds zu den Elementen von * design*.
- Design : Verschiebung der Anzeigeoptionen im Design der erweiterten Konfiguration in den Anzeigeeinstellungen von * Design*. Dies dient zur Vereinfachung der Benutzeroberfläche und ermöglicht die Verwendung unterschiedlicher Parameter durch * Design*.
- Design : Das Verschieben und Ändern der Größe von Komponenten in * Design * berücksichtigt deren Größe mit oder ohne Magnetisierung. <br/><br/>
- Allgemeine Reduzierung (CSS / Inline-Stile, Refactoring usw.) und Leistungsverbesserungen.
- Entfernen Sie Font Awesome 4, um nur Font Awesome 5 beizubehalten.
- Libs Update : jquery 3.4.1, CodeMiror 5.46.0, Tablesorter 2.31.1.
- Zahlreiche Fehlerbehebungen.
- Hinzufügen eines Massenkonfigurationssystems (wird auf der Seite Ausrüstung verwendet, um Kommunikationswarnungen darauf zu konfigurieren)

>**WICHTIG**
>
>Wenn Sie nach dem Update einen Fehler im Armaturenbrett haben, versuchen Sie, Ihre Box neu zu starten, damit die neuen Ergänzungen von Komponenten berücksichtigt werden

>**WICHTIG**
>
>Das Widget-Plugin ist nicht mit dieser Version von Jeedom kompatibel und wird nicht mehr unterstützt (da die Funktionen intern im Kern übernommen wurden).. Weitere Informationen [hier] (https://www.jeedom.com/blog/4368-les-widgets-en-v4)
