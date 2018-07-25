Es informiert über alle auf dem Server laufenden Jeedom-Anwendungsaufgaben. Dieses Menü ist wissentlich oder auf Anforderung des technischen Supports zu verwenden.

> **Wichtig**
>
> Bei unsachgemäßer Handhabung auf dieser Seite kann ihnen jede
> Support-Anfrage verweigert werden.

Um dorthin zu gelangen muss man auf **Einstellungen → Task Engine**
gehen :

Oben rechts haben Sie :

-   **Cron-System deaktivieren** : eine Schaltfläche zum Deaktivieren oder 
    Reaktivieren aller Aufgaben (wenn Sie sie alle deaktivieren, wird 
    nichts auf Ihrem Jeedom funktionieren)

-   **Aktualisieren** : eine Schaltfläche zum Aktualisieren der Task-Tabelle

-   **Hinzufügen** : eine Schaltfläche zum Hinzufügen eines Cron-Jobs

-   **Speichern** : eine Schaltfläche zum Speichern Ihrer Änderungen.

Im Folgenden finden Sie die Tabelle aller vorhandenen Aufgaben
(Achtung, einige Tasks können Unter-Tasks starten, es wird daher dringend empfohlen, dass Sie niemals Informationen auf dieser Seite 
ändern). Diese Tabelle enthält :

-   **\#** : die Task ID, kann nützlich sein, um die Verbindung zwischen einem
    laufenden Prozess herzustellen und was er wirklich macht

-   **Aktion** : eine Schaltfläche zum Starten oder Stoppen eines Tasks 
    de son statut et un bouton pour voir le cron dans le details (tel que stocké en base)

-   **Aktiv** : zeigt an ob der Task aktiv ist (kann durch Jeedom
    gestartet werden) oder nicht

-   **PID** : zeigt die aktuelle Prozess-ID

-   **Daemon** : wenn dieses Feld "Ja" ist, sollte die Aufgabe immer
    laufen. Daneben finden Sie die Frequenz des Dämons wieder, es wird 
    empfohlen, diesen Wert niemals zu ändern und vor allem, ihn niemals
    zu verringern

-   **Unique** : Wenn es "Ja" ist, wird der Task einmal ausgeführt und 
    dann selbst gelöscht

-   **Klasse** : PHP Klasse aufgerufen, um den Task auszuführen (kann
    leer sein)

-   **Funktion** : genannt PHP-Funktion in der genannten Klasse (oder auch
    nicht, wenn die Klasse leer ist)

-   **Programmierung** : die Task Programmierung im CRON-Format

-   **Zeitüberschreitung** : maximale Dauer des Funktionierens des Tasks. Wenn
     der Task ein Daemon ist, wird er am Ende der Zeitüberschreitung
    automatisch gestoppt und neu gestartet

-   **Letzter Start** : Zeit des letzten Task Starts   

-   **Letzte Dauer** : letzte Dauer, um die Aufgabe zu erfüllen (ein Dämon wird immer bei 0s sein,
    Dämon wird immer 0s haben, man darf sich nicht über andere Tasks 
    sorgen machen, sie können 0s haben)

-   **Status** : aktueller Status des Tasks (zur Erinnerung, ein Dämons Task
    ist immer auf "run")

-   **Löschen** : löscht den Task


