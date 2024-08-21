# Änderungsprotokoll Jeedom V4.5

# 4.5

- Möglichkeit, Tabellenspalten in der Größe veränderbar zu machen (im Moment nur die Liste der Variablen, dies wird bei Bedarf auf andere Tabellen ausgeweitet)) [LINK](https://github.com/jeedom/core/issues/2499)
- Es wurde eine Warnung hinzugefügt, wenn der Jeedom-Speicherplatz zu gering ist (die Überprüfung erfolgt einmal täglich)) [LINK](https://github.com/jeedom/core/issues/2438)
- Dem Bestellkonfigurationsfenster im Wertberechnungsfeld wurde eine Schaltfläche zum Abrufen einer Bestellung hinzugefügt [LINK](https://github.com/jeedom/core/issues/2776)
- Möglichkeit, bestimmte Menüs für Benutzer mit eingeschränkten Rechten auszublenden [LINK](https://github.com/jeedom/core/issues/2651)
- Die Diagramme werden automatisch aktualisiert, wenn neue Werte eintreffen [LINK](https://github.com/jeedom/core/issues/2749)
- Jeedom fügt beim Erstellen von Widgets automatisch die Höhe des Bildes hinzu, um Überlappungsprobleme auf Mobilgeräten zu vermeiden [LINK](https://github.com/jeedom/core/issues/2539)
- Neugestaltung des Cloud-Backup-Teils [LINK](https://github.com/jeedom/core/issues/2765)
- **ENTW** Einrichten eines Warteschlangensystems für die Aktionsausführung [LINK](https://github.com/jeedom/core/issues/2489)
- Szenario-Tags sind jetzt spezifisch für die Szenario-Instanz (wenn Sie zwei sehr nahe beieinander liegende Szenario-Starts haben, überschreiben die Tags des letzteren nicht mehr das erste)) [LINK](https://github.com/jeedom/core/issues/2763)
- Wechseln Sie zum Auslöserteil der Szenarien : [LINK](https://github.com/jeedom/core/issues/2414)
  - ``triggerId()`` ist jetzt veraltet und wird in zukünftigen Kernaktualisierungen entfernt
  - ``trigger()`` ist jetzt veraltet und wird in zukünftigen Kernaktualisierungen entfernt
  - ``triggerValue()`` ist jetzt veraltet und wird in zukünftigen Kernaktualisierungen entfernt
  - ``#trigger#`` : Vielleicht :
    - ``api`` wenn der Start durch die API ausgelöst wurde,
    - ``TYPEcmd`` Wenn der Start durch einen Befehl ausgelöst wurde, wird TYPE durch die Plugin-ID ersetzt (z. B. virtualCmd),
    - ``schedule`` wenn es durch Programmierung gestartet wurde,
    - ``user`` wenn es manuell gestartet wurde,
    - ``start`` für einen Start beim Jeedom-Startup.
  - ``#trigger_id#`` : Wenn es sich um einen Befehl handelt, der das Szenario ausgelöst hat, hat dieses Tag den Wert der ID des Befehls, der es ausgelöst hat
  - ``#trigger_name#`` : Wenn es sich um einen Befehl handelt, der das Szenario ausgelöst hat, hat dieses Tag den Wert des Namens des Befehls (in der Form [Objekt][Ausrüstung][Befehl])
  - ``#trigger_value#`` : Wenn es sich um einen Befehl handelt, der das Szenario ausgelöst hat, hat dieses Tag den Wert des Befehls, der das Szenario ausgelöst hat
- Verbesserte Plugin-Verwaltung auf Github (keine Abhängigkeiten mehr von einer Drittanbieter-Bibliothek)) [LINK](https://github.com/jeedom/core/issues/2567)
- Entfernen des alten Cache-Systems. [LINK](https://github.com/jeedom/core/pull/2799)
- Möglichkeit, die Blöcke IN und A zu löschen, während auf ein anderes Szenario gewartet wird [LINK](https://github.com/jeedom/core/pull/2379)
- Ein Fehler in Safari bei Filtern mit Akzenten wurde behoben [LINK](https://github.com/jeedom/core/pull/2754)
- Ein Fehler bei der Generierung generischer Typinformationen in Szenarios wurde behoben [LINK](https://github.com/jeedom/core/pull/2806)
- Bestätigung hinzugefügt, wenn der Support-Zugriff über die Benutzerverwaltungsseite geöffnet wird [LINK](https://github.com/jeedom/core/pull/2809)
- Verbesserung des Cron-Systems, um einige Startfehler zu vermeiden [LINK](https://github.com/jeedom/core/commit/533d6d4d508ffe5815f7ba6355ec45497df73313)
- Hinzufügen von Bedingungsszenarien „größer oder gleich“ und „kleiner als oder gleich“ zum Bedingungsassistenten [LINK](https://github.com/jeedom/core/issues/2810)
- Möglichkeit, Aufträge von der Dead-Order-Analyse auszuschließen [LINK](https://github.com/jeedom/core/issues/2812)
- Ein Fehler bei der Nummerierung der Zeilenanzahl in Tabellen wurde behoben [LINK](https://github.com/jeedom/core/commit/0e9e44492e29f7d0842b2c9b3df39d0d98957c83)
- Openstreetmap hinzugefügt.org in externen Domänen standardmäßig erlaubt [LINK](https://github.com/jeedom/core/commit/2d62c64f0bd1958372844f6859ef691f88852422)
- Automatische Aktualisierung der Apache-Sicherheitsdatei beim Aktualisieren des Kerns [LINK](https://github.com/jeedom/core/issues/2815)
- Eine Warnung zu Ansichten wurde behoben [LINK](https://github.com/jeedom/core/pull/2816)
- Ein Fehler beim Standard-Widget-Auswahlwert wurde behoben [LINK](https://github.com/jeedom/core/pull/2813)
- Es wurde ein Fehler behoben, bei dem der Wert auf 0 (anstelle von Min/Max) geändert wurde, wenn ein Befehl seine Mindest- oder Höchstgrenze überschritt) [LINK](https://github.com/jeedom/core/issues/2819)
- Ein Fehler in der Anzeige des Einstellungsmenüs in bestimmten Sprachen wurde behoben [LINK](https://github.com/jeedom/core/issues/2821)
- Möglichkeit in den programmierten Szenario-Triggern, Berechnungen/Befehle/Tags/Formeln zu verwenden, die zu einer Startzeit in der Form Gi (Stunde ohne Anfangsnull und Minute) führen, Beispiel für 9:15 Uhr => 915 oder für 23:40 Uhr => 2340) [LINK](https://github.com/jeedom/core/pull/2808)
- Möglichkeit, in den Plugins ein personalisiertes Bild für die Ausrüstung einzufügen (sofern das Plugin dies unterstützt), dazu einfach das Bild in „data/img“ in der Form „eqLogic“ einfügen#id#.png` mit #id# die Geräte-ID (Sie finden sie in der erweiterten Konfiguration des Geräts)) [LINK](https://github.com/jeedom/core/pull/2802)
- Hinzufügen des Namens des Benutzers, der das Szenario gestartet hat, im Tag ``#trigger_value#`` [LINK](https://github.com/jeedom/core/pull/2382)
- Es wurde ein Fehler behoben, der auftreten konnte, wenn Sie das Dashboard verlassen, bevor es vollständig geladen wurde [LINK](https://github.com/jeedom/core/pull/2827)
- Ein Fehler auf der Ersetzungsseite beim Filtern nach Objekten wurde behoben [LINK](https://github.com/jeedom/core/issues/2833)
- Verbessertes Öffnen des Core-Changelogs unter iOS (nicht mehr in einem Popup)) [LINK](https://github.com/jeedom/core/issues/2835)
- Verbesserung des erweiterten Widget-Erstellungsfensters [LINK](https://github.com/jeedom/core/pull/2836)
- Das erweiterte Befehlskonfigurationsfenster wurde verbessert [LINK](https://github.com/jeedom/core/pull/2837)
- Ein Fehler bei der Widget-Erstellung wurde behoben [LINK](https://github.com/jeedom/core/pull/2838)
- Es wurde ein Fehler auf der Szenarioseite und im Fenster zum Hinzufügen von Aktionen behoben, der nicht mehr funktionieren konnte [LINK](https://github.com/jeedom/core/issues/2839)
- Es wurde ein Fehler behoben, der die Reihenfolge der Befehle beim Bearbeiten des Dashboards ändern konnte [LINK](https://github.com/jeedom/core/issues/2841)
- Ein Javascript-Fehler in Protokollen wurde behoben [LINK](https://github.com/jeedom/core/issues/2840)
- Hinzufügen von Sicherheit zur JSON-Codierung in Ajax, um Fehler aufgrund ungültiger Zeichen zu vermeiden [LINK](https://github.com/jeedom/core/commit/0784cbf9e409cfc50dd9c3d085c329c7eaba7042)
- Wenn ein Gerätebefehl vom generischen Typ „Batterie“ ist und die Einheit „%“ hat, ordnet der Kern automatisch den Batteriestand des Geräts dem Wert des Befehls zu [LINK](https://github.com/jeedom/core/issues/2842)
- Verbesserung von Texten und Korrektur von Fehlern [LINK](https://github.com/jeedom/core/pull/2834)
- Bei der Installation von NPM-Abhängigkeiten wird der Cache vorher bereinigt [LINK](https://github.com/jeedom/core/commit/1a151208e0a66b88ea61dca8d112d20bb045c8d9)

>**WICHTIG**
>
> Aufgrund der Änderung der Cache-Engine bei diesem Update geht der gesamte Cache verloren. Keine Sorge, der Cache wird sich selbst neu aufbauen. Der Cache enthält unter anderem die Werte der Befehle, die automatisch aktualisiert werden, wenn die Module ihren Wert erhöhen. Beachten Sie, dass Sie virtuelle Variablen mit einem festen Wert (was nicht gut ist, wenn er sich nicht ändert und dann Variablen verwenden muss) erneut speichern müssen, um den Wert wiederherzustellen.
