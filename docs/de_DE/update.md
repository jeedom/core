# Update Center
**Einstellungen → System → Update Center**


Die **Update Center** Mit dieser Funktion können Sie alle Funktionen von Jeedom aktualisieren, einschließlich der Kernsoftware und ihrer Plugins.
Andere Erweiterungsverwaltungsfunktionen sind verfügbar (Löschen, Neuinstallieren, Überprüfen usw.).


## Funktionen der Seite

Am oberen Rand der Seite befinden sich unabhängig von der Registerkarte die Steuerschaltflächen.

Jeedom stellt regelmäßig eine Verbindung zum Markt her, um festzustellen, ob Updates verfügbar sind. Das Datum der letzten Prüfung wird oben links auf der Seite angezeigt.

Wenn diese Überprüfung zu Beginn der Seite älter als zwei Stunden ist, wiederholt Jeedom automatisch eine Überprüfung.
Sie können auch die Schaltfläche verwenden **Suchen Sie nach Updates** Um es manuell zu tun.
Wenn Sie eine manuelle Überprüfung durchführen möchten, können Sie auf die Schaltfläche "Nach Updates suchen" klicken".

Die Schaltfläche **Sauvegarder** wird verwendet, wenn Sie die Optionen in der folgenden Tabelle ändern, um anzugeben, dass bestimmte Plugins bei Bedarf nicht aktualisiert werden sollen.

## Aktualisieren Sie den Kern

Die Schaltfläche **Update** Mit dieser Option können Sie den Core, die Plugins oder beides aktualisieren.
Sobald Sie darauf klicken, erhalten Sie diese verschiedenen Optionen :
- **Pre-Update** : Ermöglicht das Aktualisieren des Aktualisierungsskripts, bevor die neuen Aktualisierungen angewendet werden. Wird in der Regel auf Anfrage des Supports verwendet.
- **Vorher speichern** : Sichern Sie Jeedom vor dem Update.
- **Plugins aktualisieren** : Ermöglicht das Einfügen von Plugins in das Update.
- **Aktualisieren Sie den Kern** : Ermöglicht es Ihnen, den Jeedom-Kernel (den Kern) in das Update aufzunehmen.

- **Erzwungener Modus** : Führen Sie das Update im erzwungenen Modus durch, dh, dass Jeedom auch im Fehlerfall fortfährt und die Sicherung nicht wiederherstellt. (Dieser Modus deaktiviert das Speichern!).
- **Update zur erneuten Anwendung** : Ermöglicht das erneute Anwenden eines Updates. (NB : Nicht alle Updates können erneut angewendet werden.)

> **Important**
>
> Vor einem Update erstellt Jeedom standardmäßig ein Backup. Im Falle eines Problems beim Anwenden eines Updates stellt Jeedom das unmittelbar zuvor erstellte Backup automatisch wieder her. Dieses Prinzip gilt nur für Jeedom-Updates und nicht für Plugin-Updates.

> **Tip**
>
> Sie können ein Update von Jeedom erzwingen, auch wenn es keines bietet.

## Core- und Plugins-Registerkarten

Die Tabelle enthält die Versionen des Core und der installierten Plugins.

Die Plugins haben neben ihrem Namen ein Abzeichen, das ihre Version angibt, grün in * stabil * oder orange in * beta * oder anderen.

- **Statut** : OK oder NOK.
- **Nom** : Name und Herkunft des Plugins
- **Version** : Zeigt die genaue Version des Core oder Plugins an.
- **Options** : Aktivieren Sie dieses Kontrollkästchen, wenn dieses Plugin während des globalen Updates nicht aktualisiert werden soll (Schaltfläche) **Update**).

In jeder Zeile können Sie die folgenden Funktionen verwenden:

- **Wieder einstellen** : Neuansiedlung erzwingen.
- **Supprimer** : Ermöglicht die Deinstallation.
- **überprüfen** : Fragen Sie die Quelle nach Updates ab, um herauszufinden, ob ein neues Update verfügbar ist.
- **Update** : Ermöglicht das Aktualisieren des Elements (falls es ein Update enthält).
- **Changelog** : Ermöglicht den Zugriff auf die Liste der Änderungen im Update.

> **Important**
>
> Wenn das Änderungsprotokoll leer ist, Sie aber noch ein Update haben, bedeutet dies, dass die Dokumentation aktualisiert wurde. Es ist daher nicht erforderlich, den Entwickler um Änderungen zu bitten, da diese nicht unbedingt vorhanden sind. (Es ist oft eine Aktualisierung der Übersetzung, der Dokumentation).
> In einigen Fällen kann der Plugin-Entwickler auch einfache Bugfixes vornehmen, für die nicht unbedingt das Änderungsprotokoll aktualisiert werden muss.

> **Tip**
>
> Wenn Sie ein Update starten, wird über der Tabelle ein Fortschrittsbalken angezeigt. Vermeiden Sie andere Manipulationen während des Updates.

## Registerkarte Informationen

Während oder nach einem Update können Sie auf dieser Registerkarte das Protokoll dieses Updates in Echtzeit lesen..

> **Note**
>
> Dieses Protokoll endet normalerweise mit * [END UPDATE SUCCESS]*. Es kann einige Fehlerzeilen in dieser Art von Protokoll geben. Sofern nach dem Update kein echtes Problem auftritt, ist es jedoch nicht immer erforderlich, den Support zu kontaktieren..

## Befehlszeilenaktualisierung

Es ist möglich, Jeedom direkt in SSH zu aktualisieren.
Sobald die Verbindung hergestellt ist, ist dies der auszuführende Befehl :

``````sudo php /var/www/html/install/update.php``````

Die möglichen Parameter sind :

- **mode** : ``force``, um ein Update im erzwungenen Modus zu starten (ignoriert Fehler).
- **version** : Nachverfolgung der Versionsnummer, um Änderungen gegenüber dieser Version erneut anzuwenden.

Hier ist ein Beispiel für die Syntax, um ein erzwungenes Update durchzuführen, indem die Änderungen seit 4.0 erneut angewendet werden.04 :

``````sudo php  /var/www/html/install/update.php Modus=force Version=4.0.04``````

Achtung, nach einem Update in der Befehlszeile müssen die Rechte für den Jeedom-Ordner erneut angewendet werden :

``````sudo chown -R www-data:www-data /var/www/html``````
