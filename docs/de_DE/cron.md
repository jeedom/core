# Task-Engine
**Einstellungen → System → Task Engine**

Diese Seite informiert über alle Jeedom-Anwendungsaufgaben, die auf dem Server ausgeführt werden.
Diese Seite ist wissentlich oder auf Anfrage des technischen Supports zu verwenden.

> **Important**
>
> Im Falle eines Missbrauchs auf dieser Seite kann jede Anfrage nach Unterstützung abgelehnt werden.

## Cron-Registerkarte

Oben rechts haben Sie :

- **Cron-System deaktivieren** : eine Schaltfläche zum Deaktivieren oder Reaktivieren aller Aufgaben (wenn Sie alle deaktivieren, funktioniert auf Ihrem Jeedom nichts).
- **Cool** : Aktualisiert die Aufgabentabelle.
- **Ajouter** : Fügen Sie manuell einen Cron-Job hinzu.
- **Sauvegarder** : Speichern Sie Ihre Änderungen.

Unten finden Sie eine Tabelle aller vorhandenen Aufgaben (Achtung, einige Aufgaben können Unteraufgaben starten. Es wird daher dringend empfohlen, die Informationen auf dieser Seite niemals zu ändern.).

In dieser Tabelle finden wir :

- **\.#** : Aufgaben-ID, die nützlich ist, um einen laufenden Prozess mit dem zu verknüpfen, was er wirklich tut.
- **Actif** : Zeigt an, ob die Aufgabe aktiv ist (kann von Jeedom gestartet werden) oder nicht.
- **PID** : Zeigt die aktuelle Prozess-ID an.
- **Dämon** : Wenn dieses Feld &quot;Ja&quot; lautet, muss die Aufgabe immer ausgeführt werden. Außerdem finden Sie die Häufigkeit des Dämons. Es wird empfohlen, diesen Wert niemals zu ändern und insbesondere niemals zu verringern.
- **Unique** : Wenn es &quot;Ja&quot; ist, wird die Aufgabe einmal gestartet und löscht sich dann von selbst.
- **Classe** : PHP-Klasse aufgerufen, um die Aufgabe auszuführen (kann leer sein).
- **Fonction** : PHP-Funktion, die in der aufgerufenen Klasse aufgerufen wird (oder nicht, wenn die Klasse leer ist).
- **Programmation** : Programmierung der Aufgabe im CRON-Format.
- **Timeout** : Maximale Task-Laufzeit. Wenn es sich bei der Aufgabe um einen Dämon handelt, wird sie am Ende des Zeitlimits automatisch gestoppt und neu gestartet.
- **Letzter Start** : Datum des letzten Taskstarts.
- **Letzte Dauer** : Letzte Ausführungszeit der Aufgabe (ein Daemon wird immer bei 0 sein, keine Sorge, andere Aufgaben können bei 0 sein).
- **Statut** : Aktueller Status der Aufgabe (zur Erinnerung, eine Daemon-Aufgabe wird immer &quot;ausgeführt&quot;).

- **Action** :
    - **Details** : Sehen Sie sich den Cron im Detail an (wie in der Basis gespeichert).
    - **Start / Stopp** : Starten oder stoppen Sie die Aufgabe (abhängig von ihrem Status)..
    - **Suppression** : Aufgabe löschen.


## Registerkarte &quot;Listener&quot;

Die Listener sind nur beim Lesen sichtbar und ermöglichen es Ihnen, die bei einem Ereignis aufgerufenen Funktionen zu sehen (Aktualisierung eines Befehls ...).

## Registerkarte Dämon

Übersichtstabelle der Dämonen mit ihrem Zustand, dem Datum des letzten Starts sowie der Möglichkeit von
- Starten / Starten Sie einen Daemon neu.
- Stoppen Sie einen Daemon, wenn die automatische Verwaltung deaktiviert ist.
- Aktivieren / Deaktivieren der automatischen Verwaltung eines Daemons.

> Spitze
> Dämonen deaktivierter Plugins werden auf dieser Seite nicht angezeigt.