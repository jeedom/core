Jeedom hat die Möglichkeit, von oder von gerettet und wiederhergestellt zu werden
von verschiedenen Orten.

Konfiguration 
=============

Zugänglich von **Administration → Backups**, Diese Seite ermöglicht die
Backup-Management.

Links finden Sie die Parameter und Aktionsschaltflächen. Auf dem
Richtig, dies ist der Echtzeitstatus der aktuellen Aktion (Sicherung)
oder Wiederherstellung), wenn Sie eine gestartet haben.

**Sicherungen** 
---------------

-   **Sicherungen** : Ermöglicht das manuelle Starten einer Sicherung und
    sofort (nützlich, wenn Sie eine kritische Änderung vornehmen möchten.
    Auf diese Weise können Sie zurückkehren). Sie haben auch eine
    Schaltfläche, um eine Sicherung zu starten, ohne das Archiv an das zu senden
    Cloud (erfordert Abonnement siehe unten). Senden eines
    Cloud-Backup kann eine Weile dauern. Diese Option
    So wird ein übermäßiger Zeitverlust vermieden.

-   **Sicherungsspeicherort** : Gibt den Ordner an, in dem
    Jeedom kopiert Backups. Es wird empfohlen, dies nicht zu tun
    ändere es. Wenn Sie sich auf einem relativen Pfad befinden, ist sein Ursprung
    wo Jeedom installiert ist.

-   **Anzahl der Tage, an denen Backups gespeichert werden** : Anzahl der
    Backup-Tage zu halten. Nach Ablauf dieser Frist wird die
    Backups werden gelöscht. Achten Sie darauf, keine Nummer einzugeben
    Tage zu hoch, sonst kann Ihr Dateisystem
    gesättigt sein.

-   **Maximale Gesamtgröße der Sicherungen (MB)** : Ermöglicht das Begrenzen
    Der Platz, den alle Sicherungen im Ordner einnehmen
    Backup. Wenn dieser Wert überschritten wird, löscht Jeedom das
    älteste Backups bis unter die
    maximale Größe. Es wird jedoch mindestens eine Sicherung gespeichert.

**Lokale Backups** 
-----------------------

-   **Verfügbare Backups** : Liste der verfügbaren Backups.

-   **Backup wiederherstellen** : Startet die Wiederherstellung der Sicherung
    oben ausgewählt.

-   **Backup löschen** : Ausgewählte Sicherung löschen
    oben nur im lokalen Ordner.

-   **Senden Sie ein Backup** : Ermöglicht das Senden an die
    Speichern Sie ein Archiv auf dem Computer, den Sie
    Wird derzeit verwendet (ermöglicht beispielsweise das Wiederherstellen eines Archivs
    zuvor auf einem neuen Jeedom oder Neuinstallation wiederhergestellt).

-   **Backup herunterladen** : Lass uns auf deine herunterladen
    Computer das oben ausgewählte Backup-Archiv.

**Marktsicherungen** 
----------------------

-   **Senden von Backups** : Weist Jeedom an, das zu senden
    Backups in der Market Cloud, Vorsicht, Sie müssen haben
    habe das Abonnement bekommen.

-   **Senden Sie ein Backup** : Ermöglicht das Senden eines
    Sicherungsarchiv auf Ihrem Computer.

-   **Verfügbare Backups** : Liste der Backups
    Cloud verfügbar.

-   **Backup wiederherstellen** : Startet die Wiederherstellung von a
    Cloud-Backup.

**Samba-Backups** 
---------------------

-   **Senden von Backups** : Weist Jeedom an, das zu senden
    Backups auf der hier konfigurierten Samba-Freigabe
    Administration → Konfiguration → Registerkarte Updates.

-   **Verfügbare Backups** : Liste der Backups
    Samba verfügbar.

-   **Backup wiederherstellen** : Startet die Wiederherstellung der Sicherung
    Samba oben ausgewählt.

> **Wichtig**
>
> Jeedom-Backups dürfen nur für ihn in einen Ordner fallen !!! Es wird alles, was kein Jeedom-Backup ist, aus dem Ordner gelöscht


Was ist gespeichert ? 
==============================

Während einer Sicherung sichert Jeedom alle seine Dateien und die
Datenbank. Dies enthält daher Ihre gesamte Konfiguration
(Ausrüstung, Steuerung, Verlauf, Szenarien, Design usw.).

In Bezug auf Protokolle ist nur die Z-Wave (OpenZwave) ein bisschen
anders, weil es nicht möglich ist, die Einschlüsse zu speichern.
Diese sind direkt im Controller enthalten, also müssen Sie
Behalten Sie den gleichen Controller bei, um die Zwave-Module zu finden.

> **Notiz**
>
> Das System, auf dem Jeedom installiert ist, wird nicht gesichert. wenn
> Sie haben die Parameter dieses Systems geändert (insbesondere über SSH),
> Es liegt an Ihnen, einen Weg zu finden, um sie bei Problemen wiederherzustellen.

Cloud-Backup 
================

Mit Cloud-Backups kann Jeedom Ihre Backups senden
direkt auf dem Markt. Auf diese Weise können Sie sie einfach wiederherstellen
und seien Sie sicher, sie nicht zu verlieren. Der Markt behält die letzten 6
Backups. Um sich anzumelden, gehen Sie einfach auf Ihre Seite
**Profil** auf dem Markt, dann in der Registerkarte **meine Backups**. Vous
kann auf dieser Seite ein Backup abrufen oder ein kaufen
Abonnement (für 1, 3, 6 oder 12 Monate).

> **Spitze**
>
> Sie können den Namen der Sicherungsdateien von anpassen
> der Registerkarte **Meine Jeedoms**, Vermeiden Sie jedoch die Zeichen
> exotisch.

Häufigkeit der automatischen Sicherungen 
======================================

Jeedom führt jeden Tag zur gleichen Zeit eine automatische Sicherung durch
Stunde. Es ist möglich, dies über die &quot;Engine&quot; zu ändern
Aufgaben &quot;(die Aufgabe heißt **Jeedom Backup**), aber es ist nicht
empfohlen. In der Tat wird es in Bezug auf die Last der berechnet
Market.
