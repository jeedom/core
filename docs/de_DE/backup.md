# Sicherungen
**Einstellungen → System → Backups**

Jeedom bietet die Möglichkeit, von oder von verschiedenen Orten aus gerettet und wiederhergestellt zu werden.
Diese Seite ermöglicht die Verwaltung von Backups und Wiederherstellungen.


Links finden Sie die Parameter und Aktionsschaltflächen. Auf der rechten Seite sehen Sie den Echtzeitstatus der laufenden Aktion (Sicherung oder Wiederherstellung), falls Sie eine gestartet haben.

## Sicherungen

- **Sicherungen** : Ermöglicht das manuelle und sofortige Starten einer Sicherung (nützlich, wenn Sie eine kritische Änderung vornehmen möchten. Dies ermöglicht es Ihnen, zurück zu gehen). Sie haben auch eine Schaltfläche zum Starten eines Backups, ohne das Archiv an die Cloud zu senden (erfordert ein Abonnement, siehe unten). Das Senden eines Backups an die Cloud kann eine Weile dauern. Diese Option vermeidet daher übermäßigen Zeitverlust.

- **Sicherungsspeicherort** : Gibt den Ordner an, in den Jeedom die Sicherungen kopiert. Es wird empfohlen, es nicht zu ändern. Wenn Sie sich auf einem relativen Pfad befinden, ist der Ursprung dort, wo Jeedom installiert ist.

- **Anzahl der Tage, an denen Backups gespeichert werden** : Anzahl der zu speichernden Sicherungstage. Nach Ablauf dieser Frist werden die Sicherungen gelöscht. Achten Sie darauf, nicht zu viele Tage einzuplanen, da sonst Ihr Dateisystem möglicherweise überlastet ist.

- **Maximale Gesamtgröße der Sicherungen (MB)** : Begrenzt den Speicherplatz aller Sicherungen im Sicherungsordner. Wenn dieser Wert überschritten wird, löscht Jeedom die ältesten Sicherungen, bis die maximale Größe unterschritten wird. Es wird jedoch mindestens eine Sicherung gespeichert.

## Sicherungen Locales

- **Sicherungen disponibles** : Liste der verfügbaren Backups.

- **Backup wiederherstellen** : Startet die Wiederherstellung der oben ausgewählten Sicherung.

- **Backup löschen** : Löschen Sie die oben ausgewählte Sicherung nur im lokalen Ordner.

- **Senden Sie ein Backup** : Ermöglicht das Senden eines Archivs auf dem Computer, den Sie gerade verwenden, an den Sicherungsordner (z. B. um ein Archiv wiederherzustellen, das zuvor auf einem neuen Jeedom wiederhergestellt wurde, oder um eine Neuinstallation durchzuführen).

- **Backup herunterladen** : Laden Sie das Archiv der oben ausgewählten Sicherung auf Ihren Computer herunter.

## Sicherungen Market

- **Senden von Backups** : Bitten Sie Jeedom, Backups an die Market Cloud zu senden. Beachten Sie, dass Sie sich angemeldet haben müssen.

- **Senden Sie ein Backup** : Senden Sie ein Sicherungsarchiv auf Ihrem Computer an die Cloud.

- **Sicherungen disponibles** : Liste der verfügbaren Cloud-Backups.

- **Backup wiederherstellen** : Startet die Wiederherstellung einer Cloud-Sicherung.

## Sicherungen Samba

- **Senden von Backups** : Weist Jeedom an, die Backups an die hier konfigurierte Samba-Freigabe zu senden. Einstellungen → System → Konfiguration : Updates.

- **Sicherungen disponibles** : Liste der verfügbaren Samba-Backups.

- **Backup wiederherstellen** : Startet die Wiederherstellung des oben ausgewählten Samba-Backups.

> **Wichtig**
>
> Jeedom-Backups müssen unbedingt nur für ihn in einem Ordner gespeichert werden ! Es wird alles, was kein Jeedom-Backup ist, aus dem Ordner gelöscht.


# Was ist gespeichert ?

Während einer Sicherung sichert Jeedom alle seine Dateien und die Datenbank. Diese enthält daher Ihre gesamte Konfiguration (Ausrüstung, Steuerelemente, Verlauf, Szenarien, Design usw.).

Auf Protokollebene unterscheidet sich nur die Z-Wave (OpenZwave) ein wenig, da die Einschlüsse nicht gespeichert werden können. Diese sind direkt im Controller enthalten, daher müssen Sie denselben Controller behalten, um seine Zwave-Module zu finden.

> **Notiz**
>
> Das System, auf dem Jeedom installiert ist, wird nicht gesichert. Wenn Sie die Einstellungen für dieses System geändert haben (auch über SSH), müssen Sie einen Weg finden, diese bei Problemen wiederherzustellen.

# Cloud-Backup

Mit Cloud-Backups kann Jeedom Ihre Backups direkt an den Markt senden. Auf diese Weise können Sie sie einfach wiederherstellen und sicherstellen, dass Sie sie nicht verlieren. Der Markt behält die letzten 6 Backups. Um sich anzumelden, gehen Sie einfach auf Ihre Seite **Profil** auf dem Markt, dann in der Registerkarte **meine Backups**. Auf dieser Seite können Sie ein Backup abrufen oder ein Abonnement kaufen (für 1, 3, 6 oder 12 Monate).

> **Spitze**
>
> Sie können den Namen der Sicherungsdateien auf der Registerkarte anpassen **Meine Jeedoms**, Vermeiden Sie jedoch die exotischen Charaktere.

# Häufigkeit der automatischen Sicherungen

Jeedom führt jeden Tag zur gleichen Zeit eine automatische Sicherung durch. Es ist möglich, es über die &quot;Task-Engine&quot; zu ändern (die Task wird benannt **Jeedom Backup**), wird aber nicht empfohlen. In der Tat wird es in Bezug auf die Belastung des Marktes berechnet.
