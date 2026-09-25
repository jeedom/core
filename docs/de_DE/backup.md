# Sicherungen
**Einstellungen → System → Backups**

Jeedom bietet die Möglichkeit, Daten an verschiedenen Orten zu sichern und von dort die Wiederherstellung durchzuführen.
Auf dieser Seite können Sie Backups und Wiederherstellungen verwalten.


Auf der linken Seite finden Sie die Einstellungen und Aktionsschaltflächen. Auf der rechten Seite wird der Echtzeitstatus der laufenden Aktion (Sicherung oder Wiederherstellung) angezeigt, sofern Sie eine solche gestartet haben.

## Sicherungen

- **Sicherungen**: Ermöglicht es Ihnen, eine Sicherung manuell und sofort zu starten (nützlich, wenn Sie eine wichtige Änderung vornehmen möchten. So können Sie den Vorgang rückgängig machen). Es gibt auch eine Schaltfläche, mit der Sie eine Sicherung starten können, ohne das Archiv in die Cloud zu übertragen (erfordert ein Abonnement, siehe weiter unten). Das Hochladen eines Backups in die Cloud kann einige Zeit in Anspruch nehmen. Mit dieser Option lässt sich daher ein zu großer Zeitverlust vermeiden.

- **Speicherort der Sicherungen**: Gibt den Ordner an, in den Jeedom die Sicherungen kopiert. Es wird empfohlen, diesen nicht zu ändern. Bei einem relativen Pfad ist der Ausgangspunkt der Ort, an dem Jeedom installiert ist.

- **Anzahl der Tage, an denen Backups gespeichert werden**: Anzahl der Tage, für die Backups aufbewahrt werden sollen. Nach Ablauf dieser Frist werden die Backups gelöscht. Achten Sie darauf, keine zu hohe Anzahl an Tagen festzulegen, da sonst Ihr Dateisystem überfüllt werden könnte.

- **Maximale Gesamtgröße der Sicherungen (MB)**: Hiermit können Sie den Speicherplatz begrenzen, den alle Sicherungen im Ordner für Sicherungen einnehmen. Wird dieser Wert überschritten, löscht Jeedom die ältesten Sicherungen, bis die maximale Größe wieder unterschritten wird. Es wird jedoch mindestens eine Sicherung beibehalten.

## Lokale Backups

- **Verfügbare Backups**: Liste der verfügbaren Backups.

- **Sicherung wiederherstellen**: Startet die Wiederherstellung der oben ausgewählten Sicherung.

- **Sicherung löschen**: Löscht die oben ausgewählte Sicherung, jedoch nur im lokalen Ordner.

- **Sicherung senden**: Ermöglicht es, ein Archiv, das sich auf dem aktuell verwendeten Computer befindet, in den Ordner für Sicherungen zu senden (damit lässt sich beispielsweise eine zuvor durchgeführte Wiederherstellung eines Archives auf einem neuen Jeedom oder nach einer Neuinstallation durchführen).

- **Sicherung herunterladen**: Ermöglicht es Ihnen, das Archiv der oben ausgewählten Sicherung auf Ihren Computer herunterzuladen.

## Market-Backups

- **Sicherungskopien senden**: Weist Jeedom an, die Sicherungskopien in die Cloud des Market zu senden. Bitte beachten Sie, dass hierfür ein Abonnement erforderlich ist.

- **Sicherung senden**: Ermöglicht es Ihnen, ein auf Ihrem Computer gespeichertes Sicherungsarchiv in die Cloud zu übertragen.

- **Verfügbare Backups**: Liste der verfügbaren Cloud-Backups.

- **Sicherung wiederherstellen**: Startet die Wiederherstellung einer Cloud-Sicherung.

## Samba-Backups

- **Sicherungskopien senden**: Weist Jeedom an, die Sicherungskopien an den hier unter „Einstellungen → System → Konfiguration: Updates“ konfigurierten Samba-Ordner zu senden.

- **Verfügbare Backups**: Liste der verfügbaren Samba-Backups.

- **Sicherung wiederherstellen**: Startet die Wiederherstellung der oben ausgewählten Samba-Sicherung.

> **WICHTIG**
>
> Die Jeedom-Backups müssen unbedingt in einem eigens dafür vorgesehenen Ordner gespeichert werden! Alles, was kein Jeedom-Backup ist, wird aus diesem Ordner gelöscht.


# Was wird gesichert?

Bei einer Sicherung speichert Jeedom alle seine Dateien und die Datenbank. Diese enthalten somit Ihre gesamte Konfiguration (Geräte, Befehle, Verlaufsdaten, Szenarien, Design usw.).

Was die Protokolle betrifft, unterscheidet sich lediglich Z-Wave (OpenZwave) ein wenig, da es nicht möglich ist, die Einbindungen zu speichern. Diese werden direkt in den Controller integriert, daher muss man denselben Controller behalten, um seine Z-Wave-Module wiederzufinden.

> **Hinweis**
>
> Das System, auf dem Jeedom installiert ist, wird nicht gesichert. Wenn Sie Einstellungen an diesem System geändert haben (insbesondere über SSH), müssen Sie selbst einen Weg finden, diese im Falle von Problemen wiederherzustellen. Auch die Abhängigkeiten werden nicht gesichert, sodass Sie diese nach einer Wiederherstellung neu installieren müssen.

# Cloud-Backup

Dank der Cloud-Sicherung kann Jeedom Ihre Backups direkt an den Market senden. So können Sie diese ganz einfach wiederherstellen und haben die Gewissheit, dass sie nicht verloren gehen. Der Market speichert die letzten 6 Backups. Um ein Abonnement abzuschließen, gehen Sie einfach auf Ihre **Profil**-Seite im Market und dann auf den Reiter **Meine Backups**. Von dieser Seite aus können Sie ein Backup abrufen oder ein Abonnement (für 1, 3, 6 oder 12 Monate) erwerben.

> **Tipp**
>
> Sie können die Namen der Sicherungsdateien auf der Registerkarte **Meine Jeedoms** individuell anpassen, sollten dabei jedoch auf Sonderzeichen verzichten.

# Häufigkeit der automatischen Datensicherungen

Jeedom führt täglich zur gleichen Uhrzeit eine automatische Sicherung durch. Es ist möglich, diese über die „Aufgaben-Engine“ zu ändern (die Aufgabe heißt **Jeedom backup**), dies wird jedoch nicht empfohlen. Denn der Zeitpunkt wird anhand der Auslastung des Market berechnet.

# Häufig gestellte Fragen

>**Ich kann mein Backup, das ich aus Safari wiederhergestellt habe, nicht wiederherstellen**
>
>Standardmäßig entpackt Safari die tar.gz-Dateien (in tar), wodurch die Sicherungsdatei für Jeedom nicht mehr nutzbar ist. Sie muss daher erneut (gzip) als tar.gz komprimiert werden.
