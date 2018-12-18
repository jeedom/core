Beschreibung 
===========

Das **Update Center** ermöglicht es Ihnen, alle Funktionen von Jeedom zu
aktualisieren, einschließlich Kernsoftware, Plugins, Widgets usw.. Weitere
Funktionen zur Verwaltung von Erweiterungen sind verfügbar (Löschen,
Neuinstallieren, Prüfen usw.).

Die Update Center Seite
================================

Sie ist über das Menü **Administration → Update Center**
erreichbar.

Sie finden auf der linken Seite alle Funktionen von Jeedom und auf der rechten Seite den **Informations**-Teil, in dem angezeigt wird, was passiert wenn ein ein Update gestartet wird.

Die Funktionen oben auf der Seite.
---------------------------------

In oberem Teil der Tabelle befinden sich die Schaltflächen. Jeedom
verbindet sich regelmäßig mit dem Markt um zu sehen, ob Updates
verfügbar sind (das Datum der letzten Prüfung wird am oberen Rand auf der
linken Seite der Tabelle angezeigt). Wenn Sie eine manuelle Überprüfung
durchführen möchten, klicken Sie die Schaltfläche "Nach Updates suchen" an.

Die Schaltfläche **Updaten** wird verwendet, um das Jeedom Paket zu aktualisieren. Sobald Sie darauf klicken, erhalten Sie diese verschiedenen Optionen :

-   **Vorher speichern** : Führt vor dem Update eine Jeedom-Sicherung 
    durch.

-   **Plugins aktualisieren** : Ermöglicht Plugins im Update zu
    integrieren.

-   **Kern aktualisieren** : Ermöglicht Ihnen, den Jeedom-Kernel in das
    Update aufzunehmen.

-   **Zwangsbetrieb** : Führt das Update im erzwungenen Modus durch, das
    heißt, selbst wenn ein Fehler auftritt, fährt Jeedom fort und wird das Backup
    nicht wiederherstellen.

-   **Update wieder anwenden** : Ermöglicht es Ihnen, ein Update erneut zu
    installieren. (Hinweis : Nicht alle Updates können erneut angewendet werden.)

> **Wichtig**
>
>Vor der Aktualisierung wird Jeedom standardmäßig eine Sicherungskopie
> erstellen. Bei Problemen beim Anwenden eines Updates wird Jeedom
> automatisch die zuvor erstellte Sicherung wiederherstellen. Dieses Prinzip
> gilt natürlich nur für Jeedom Updates, nicht für Plugins.

> **Tipp**
>
> Sie können ein Update von Jeedom erzwingen, auch wenn es Ihnen nicht
> angeboten wird.

Die Tabelle der Aktualisierungen
---------------------------

Der Tabelle besteht aus zwei Registerkarten :

-   **Kern und Plugins** : Enthält die Basissoftware von Jeedom und 
    die Liste der installierten Plugins.

-   **Andere** : Enthält Widgets, Skripte usw.

Hier finden Sie folgende Informationen: * **Status** : OK oder NOK. Zeigt den
aktuellen Status des Plugins an. * **Name** : Sie sehen die Quelle des
Elements, den Typ des Elements und seinen Namen. * **Version** : Gibt die
genaue Version des Elements an. * **Optionen** : Aktivieren Sie dieses
Kontrollkästchen, wenn das Element während des allgemeinen Updates
nicht aktualisiert werden soll (Schaltfläche **Updaten**).

> **Tipp**
>
> Für jede Tabelle können Sie in der ersten Zeile nach dem Status, den
> Namen oder der Version des vorhandenen Elemente filtern.

In jeder Zeile können Sie  für jedes Element die folgenden Aktionen vdurchführen :

-   **Neu installieren** : Erzwingt eine Neuinstallation.

-   **Löschen** : Ermöglicht das Elemente zu löschen.

-   **Prüfen** : Überprüft die Update-Quelle, um herauszufinden, 
    ob ein neues Update verfügbar ist.

-   **Update** : Ermöglicht das Element zu aktualisieren (wenn ein 
    Update verfügbar ist).

-   **Änderungsprotokoll** : Bietet Zugriff auf die Liste der Änderungen der 
    Updates.

> **Wichtig**
>
> Wenn das Änderungsprotokoll leer ist, aber Sie noch ein Update haben,
> bedeutet dies, dass die Dokumentation aktualisiert wurde. Es ist daher
> nicht notwendig, vom Entwickler die Veränderungen zu verlangen, da es
> nicht unbedingt notwendig ist. (Dies ist oft ein Update der Dokumentations > Übersetzung.)

> **Tipp**
>
> Beachten Sie, dass "core : jeedom" "Aktualisierung der Jeedom
> Kernsoftware" bedeutet.

Update in der Kommandozeile
================================

Es ist möglich, Jeedom direkt in SSH zu aktualisieren. Sobald die Verbindung
hergestellt ist, folgt der folgende Befehl :

    sudo php /var/www/html/install/update.php

Die möglichen Parameter sind :

-   **`mode`** : `force`, um ein Update im erzwungenen Modus zu starten (be
    rücksichtigt keine Fehler).

-   **`version`** : gefolgt von der Versionsnummer, um Änderungen von 
    dieser Version erneut anzuwenden.

Im Folgenden finden Sie eine Beispielsyntax für eine erzwungene
Aktualisierung durch erneutes Anwenden des Updates von Version 1.188.0 :

    sudo php  /var/www/html/install/update.php mode=force version=1.188.0

Achtung nach einem Update in der Komandozeile müssen Sie die Rechte an
den Jeedom Ordner erneut anwenden :

    chown -R www-data:www-data /var/www/html
