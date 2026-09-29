# Update-Center
**Einstellungen → System → Update-Center**


Über das **Update-Center** können Sie alle Funktionen von Jeedom aktualisieren, einschließlich der Kernsoftware (Core) und ihrer Plugins.
Es stehen weitere Funktionen zur Verwaltung von Erweiterungen zur Verfügung (löschen, neu installieren, überprüfen usw.).


## Funktionen dieser Seite

Oben auf der Seite, unabhängig von der Registerkarte, befinden sich die Schaltflächen.

Jeedom verbindet sich regelmäßig mit dem Market, um zu prüfen, ob Updates verfügbar sind. Das Datum der letzten Überprüfung wird oben links auf der Seite angezeigt.

Wenn beim Öffnen der Seite seit der letzten Überprüfung mehr als zwei Stunden vergangen sind, führt Jeedom automatisch eine erneute Überprüfung durch.
Sie können auch die Schaltfläche **Nach Updates suchen** verwenden, um dies manuell durchzuführen.
Wenn Sie eine manuelle Überprüfung durchführen möchten, können Sie auf die Schaltfläche „Nach Updates suchen“ klicken.

Die Schaltfläche **Speichern** sollten Sie verwenden, wenn Sie die Optionen in der Tabelle unten ändern, um festzulegen, dass bestimmte Plugins bei Bedarf nicht aktualisiert werden sollen.

## Core aktualisieren

Mit der Schaltfläche **Aktualisieren** können Sie den Core, die Plugins oder beides aktualisieren.
Sobald Sie darauf geklickt haben, werden folgende Optionen angezeigt:
- **Vorab-Update**: Ermöglicht die Aktualisierung des Update-Skripts, bevor die neuen Updates angewendet werden. Wird in der Regel auf Anfrage des Supports verwendet.
- **Vorher sichern**: Führt vor dem Update eine Sicherung von Jeedom durch. Die Sicherung erfolgt ausschließlich lokal (weder über Market noch über Samba).
- **Plugins aktualisieren**: Ermöglicht es, die Plugins in das Update einzubeziehen.
- **Core aktualisieren**: Ermöglicht es, den Jeedom-Kern (den Core) in das Update einzubeziehen.

- **Erzwungener Modus**: Führt die Aktualisierung im erzwungenen Modus durch, d. h., selbst wenn ein Fehler auftritt, fährt Jeedom fort und führt keine Wiederherstellung des Backups durch. (In diesem Modus wird das Backup deaktiviert!).
- **Update erneut anwenden**: Ermöglicht es, ein Update erneut anzuwenden. (Hinweis: Nicht alle Updates können erneut angewendet werden.)

> **Wichtig**
>
> Vor einem Update erstellt Jeedom standardmäßig eine Sicherungskopie. Sollte bei der Durchführung eines Updates ein Problem auftreten, stellt Jeedom automatisch die Wiederherstellung des Zustands der unmittelbar zuvor erstellten Sicherungskopie wieder her. Dies gilt jedoch nur für Jeedom-Updates und nicht für Updates von Plugins.

> **Tipp**
>
> Sie können ein Update von Jeedom erzwingen, auch wenn kein Update angeboten wird.

## Registerkarten „Core“ und „Plugins“

Die Tabelle enthält die Versionen des Core und der installierten Plugins.

Die Plugins sind mit einem Symbol neben ihrem Namen gekennzeichnet, das die jeweilige Version angibt: grün für *stable*, orange für *beta* oder andere Versionen.

- **Status**: OK oder NOK.
- **Name**: Name und Herkunft des Plugins
- **Version**: Gibt die genaue Version des Core oder des Plugins an.
- **Optionen**: Aktivieren Sie dieses Kontrollkästchen, wenn Sie nicht möchten, dass dieses Plugin bei der globalen Aktualisierung (Schaltfläche **Aktualisieren**) aktualisiert wird.

In jeder Zeile stehen Ihnen folgende Funktionen zur Verfügung:

- **Neu installieren**: Erzwingt eine Neuinstallation.
- **Löschen**: Hiermit können Sie die App deinstallieren.
- **Prüfen**: Fragt die Update-Quelle ab, ob ein neues Update verfügbar ist.
- **Aktualisieren**: Ermöglicht es, das Element zu aktualisieren (sofern ein Update verfügbar ist).
- **Changelog**: Hier können Sie die Liste der Änderungen des Updates einsehen.

> **Wichtig**
>
> Wenn das Changelog leer ist, Sie aber dennoch ein Update erhalten haben, bedeutet dies, dass die Dokumentation aktualisiert wurde. Es ist daher nicht notwendig, den Entwickler nach den Änderungen zu fragen, da es nicht unbedingt welche gibt. (Oft handelt es sich um eine Aktualisierung der Übersetzung oder der Dokumentation.)
> Der Entwickler des Plugins kann in bestimmten Fällen auch einfache Fehlerbehebungen vornehmen, für die nicht unbedingt eine Aktualisierung des Changelogs erforderlich ist.

> **Tipp**
>
> Wenn Sie ein Update starten, erscheint oberhalb der Tabelle ein Fortschrittsbalken. Führen Sie während des Updates keine weiteren Vorgänge durch.

## Registerkarte „Betriebssystem/Paket“

> **WICHTIG**
>
> Dieser Reiter ist ausschließlich für fortgeschrittene Benutzer bestimmt. Der kleinste Fehler hier kann Ihr Jeedom ZERSTÖREN (ohne die Möglichkeit, den Support in Anspruch zu nehmen).

Auf dieser Registerkarte können Sie verfügbare Updates für das Betriebssystem (apt) und Python-Pakete (pip2 und pip3) einsehen sowie die Pakete aktualisieren, für die dies erforderlich ist.

## Registerkarte „Informationen“

Während oder nach der Aktualisierung können Sie auf dieser Registerkarte das Protokoll dieser Aktualisierung in Echtzeit einsehen.

> **Hinweis**
>
> Dieses Protokoll endet normalerweise mit *[END UPDATE SUCCESS]*. Es kann zwar vorkommen, dass dieses Protokoll einige Fehlerzeilen enthält, doch sofern nach dem Update kein tatsächliches Problem auftritt, ist es nicht immer notwendig, sich diesbezüglich an den Support zu wenden.

## Update über die Befehlszeile

Es ist möglich, Jeedom direkt über SSH zu aktualisieren.
Sobald die Verbindung hergestellt ist, führen Sie folgenden Befehl aus:

```sudo php /var/www/html/install/update.php```

Les paramètres possibles sont :

- **mode** : `force`, pour lancer une mise à jour en mode forcé (ne tient pas compte des erreurs).
- **version** : Suivi du numéro de version, pour ré-appliquer les changements depuis cette version.

Voici un exemple de syntaxe pour faire une mise à jour forcée en ré-appliquant les changements depuis la 4.0.04 :

```sudo php  /var/www/html/install/update.php mode=force version=4.0.04```

Achtung: Nach einem Update über die Befehlszeile müssen die Berechtigungen für den Jeedom-Ordner erneut zugewiesen werden:

```sudo chown -R www-data:www-data /var/www/html```
