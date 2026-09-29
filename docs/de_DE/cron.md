# Aufgaben-Engine
**Einstellungen → System → Aufgaben-Engine**

Auf dieser Seite finden Sie Informationen zu allen Jeedom-Anwendungsprozessen, die auf dem Server laufen.
Diese Seite sollte nur in voller Kenntnis der Sachlage oder auf Anfrage des technischen Supports genutzt werden.

> **Wichtig**
>
> Bei unsachgemäßer Nutzung dieser Seite kann es vorkommen, dass Supportanfragen abgelehnt werden.

## Registerkarte „Cron“

Oben rechts finden Sie:

- **Cron-System deaktivieren**: Eine Schaltfläche zum Deaktivieren oder Reaktivieren aller Aufgaben (wenn Sie alle deaktivieren, funktioniert auf Ihrem Jeedom nichts mehr).
- **Aktualisieren**: Aktualisiert die Aufgabetabelle.
- **Hinzufügen**: Ermöglicht das manuelle Hinzufügen einer Cron-Aufgabe.
- **Speichern**: Speichert Ihre Änderungen.

Unten finden Sie eine Übersicht über alle vorhandenen Aufgaben (Achtung: Einige Aufgaben können Unteraufgaben auslösen; es wird daher dringend empfohlen, die Informationen auf dieser Seite niemals zu ändern).

In dieser Tabelle finden sich:

- **\#**: ID der Aufgabe, nützlich, um einen laufenden Prozess mit seiner tatsächlichen Funktion in Verbindung zu bringen.
- **Aktiv**: Gibt an, ob die Aufgabe aktiv ist (kann von Jeedom gestartet werden) oder nicht.
- **PID**: Zeigt die aktuelle Prozess-ID an.
- **Daemon**: Wenn dieses Kontrollkästchen auf „Ja“ gesetzt ist, muss die Aufgabe immer ausgeführt werden. Daneben finden Sie die Ausführungshäufigkeit des Daemons. Es wird empfohlen, diesen Wert niemals zu ändern und vor allem niemals zu verringern.
- **Einmalig**: Wenn „Ja“ ausgewählt wird, wird die Aufgabe einmal ausgeführt und anschließend gelöscht.
- **Klasse**: PHP-Klasse, die zum Ausführen der Aufgabe aufgerufen wird (kann leer sein).
- **Funktion**: PHP-Funktion, die in der aufgerufenen Klasse aufgerufen wird (oder nicht, wenn die Klasse leer ist).
- **Programmierung**: Programmierung der Aufgabe im CRON-Format.
- **Timeout**: Maximale Laufzeit der Aufgabe. Handelt es sich bei der Aufgabe um einen Daemon, wird dieser nach Ablauf des Timeouts automatisch beendet und neu gestartet.
- **Letzter Start**: Datum des letzten Starts der Aufgabe.
- **Letzte Dauer**: Letzte Ausführungsdauer der Aufgabe (bei einem Daemon beträgt diese immer 0 s; machen Sie sich keine Sorgen, wenn andere Aufgaben ebenfalls 0 s anzeigen).
- **Status**: Aktueller Status der Aufgabe (zur Erinnerung: Eine Daemon-Aufgabe hat immer den Status „run“).

- **Aktion**:
    - **Details**: Den Cron-Eintrag im Detail anzeigen (wie in der Datenbank gespeichert).
    - **Starten / Beenden**: Die Aufgabe starten oder beenden (je nach Status).
    - **Löschen**: Ermöglicht das Löschen der Aufgabe.


## Registerkarte „Listener“

Listener sind nur im Lesezugriff sichtbar und ermöglichen es, die bei einem Ereignis aufgerufenen Funktionen (Aktualisierung eines Befehls...) einzusehen.

## Registerkarte „Daemon“

Übersicht über alle Daemons mit ihrem Status, dem Datum des letzten Starts sowie der Möglichkeit, Folgendes zu tun:
- Einen Daemon starten / neu starten.
- Einen Daemon beenden, wenn die automatische Verwaltung deaktiviert ist.
- Automatische Verwaltung eines Daemons aktivieren/deaktivieren.

> Tipp
> Die Dämonen deaktivierter Plugins werden auf dieser Seite nicht angezeigt.
