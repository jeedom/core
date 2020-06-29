Es informiert über alle Jeedom-Anwendungsaufgaben, die auf dem ausgeführt werden
Server. Dieses Menü ist wissentlich oder am zu verwenden
Technischen Support anfordern.

> **Wichtig**
>
> Im Falle eines Missbrauchs auf dieser Seite jede Anfrage für
> Unterstützung kann Ihnen verweigert werden.

Um darauf zuzugreifen, gehen Sie zu **Administration → Task Engine**
:

# Cron

Oben rechts haben Sie :

-   **Cron-System deaktivieren** : eine Taste zum Deaktivieren oder
    Aktivieren Sie alle Aufgaben erneut (wenn Sie alle deaktivieren, mehr
    Nichts wird an deinem Jeedom funktionieren)

-   **Cool** : eine Schaltfläche zum Aktualisieren der Aufgabentabelle

-   **Hinzufügen** : eine Schaltfläche zum Hinzufügen eines Cron-Jobs

-   **Rekord** : eine Schaltfläche zum Speichern Ihrer Änderungen.

Unten finden Sie die Tabelle aller vorhandenen Aufgaben
(Seien Sie vorsichtig, einige Aufgaben können Unteraufgaben starten
Es wird dringend empfohlen, die Informationen hierzu niemals zu ändern
Seite). In dieser Tabelle finden wir :

-   **\.#** : Aufgaben-ID, kann zum Verknüpfen von a hilfreich sein
    Prozess, der läuft und was er wirklich tut

-   **Aktion** : eine Schaltfläche zum Starten oder Stoppen der Funktion
    seinen Status und eine Schaltfläche zum detaillierten Anzeigen des Cron (wie in der Datenbank gespeichert))

-   **Aktiva** : Gibt an, ob die Aufgabe aktiv ist (kann gestartet werden
    von Jeedom) oder nicht

-   **PID** : gibt die aktuelle Prozess-ID an

-   **Dämon** : Wenn dieses Feld "Ja" ist, muss die Aufgabe immer
    in Bearbeitung sein. Als nächstes finden Sie die Frequenz des Dämons, es ist
    Es wird empfohlen, diesen Wert niemals zu berühren und insbesondere niemals
    verringern Sie es

-   **Single** : Wenn es "Ja" ist, wird die Aufgabe einmal gestartet
    dann wird gelöscht

-   **Klasse** : PHP-Klasse aufgerufen, um die Aufgabe auszuführen (can
    leer sein)

-   **Funktion** : PHP-Funktion, die in der aufgerufenen Klasse aufgerufen wird (oder nicht)
    wenn die Klasse leer ist)

-   **Programmierung** : Programmieren der Aufgabe im CRON-Format

-   **Zeitüberschreitung** : maximale Laufzeit der Aufgabe. Wenn die
    Aufgabe ist ein Dämon, dann wird es automatisch gestoppt und
    am Ende des Timeouts neu gestartet

-   **Letzter Start** : Datum des letzten Taskstarts

-   **Letzte Dauer** : letztes Mal, um die Aufgabe abzuschließen (a
    Dämon wird immer bei 0 sein, also mach dir keine Sorgen über andere Aufgaben
    kann bei 0s sein)

-   **Status** : aktueller Status der Aufgabe (zur Erinnerung eine Daemon-Aufgabe
    ist noch bei "run")

-   **Unterdrückung** : Aufgabe löschen


# Listener

Die Listener sind nur beim Lesen sichtbar und ermöglichen es Ihnen, die bei einem Ereignis aufgerufenen Funktionen anzuzeigen (Aktualisierung eines Befehls)...)
