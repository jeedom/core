Beschreibung 
===========

Die **Update Center** ermöglicht es Ihnen, alle zu aktualisieren
Jeedom-Funktionen, einschließlich Kernsoftware,
Plugins, Widgets usw.. Andere Erweiterungsverwaltungsfunktionen
verfügbar sind (löschen, neu installieren, prüfen usw.)

Die Update Center-Seite 
================================

Es ist über das Menü zugänglich **Einstellungen → System → Update Center
Tag** und bestehen aus 3 Laschen und einem Oberteil.

Funktionen oben auf der Seite. 
---------------------------------

Am oberen Rand der Seite befinden sich unabhängig von der Registerkarte die Steuerschaltflächen. 
Jeedom stellt regelmäßig eine Verbindung zum Markt her, um festzustellen, ob Aktualisierungen vorliegen
verfügbar sind (das Datum der letzten Prüfung ist oben angegeben
auf der linken Seite). Wenn Sie eine manuelle Überprüfung durchführen möchten,
Sie können die Taste "Nach Updates suchen" drücken".

Die Schaltfläche **Update** ermöglicht das Aktualisieren des Satzes von
Jeedom. Sobald Sie darauf klicken, erhalten wir diese unterschiedlich
Optionen :
-   **Pre-Update** : Ermöglicht das Aktualisieren des Aktualisierungsskripts
    Anwendungen neuer Updates.

-   **Vorher speichern** : Sichern Sie Jeedom vor
    Führen Sie das Update durch.

-   **Plugins aktualisieren** : Ermöglicht das Einfügen von Plugins in die
    Update.

-   **Aktualisieren Sie den Kern** : Ermöglicht das Einfügen des Jeedom-Kernels in
    das Update.

-   **Erzwungener Modus** : Update im erzwungenen Modus, d.h.
    Selbst wenn ein Fehler auftritt, fährt Jeedom fort und wird nicht wiederhergestellt
    das Backup. (Dieser Modus deaktiviert das Speichern!)

-   **Update zur erneuten Anwendung** : Ermöglicht es Ihnen, eine Wette erneut anzuwenden
    auf dem neuesten Stand. (NB : Nicht alle Updates können erneut angewendet werden.)

> **Wichtig**
>
> Vor einem Update erstellt Jeedom standardmäßig ein Backup. in
> Wenn beim Anwenden eines Updates ein Problem auftritt, wird Jeedom dies tun
> Stellen Sie das zuvor erstellte Backup automatisch wieder her. Dieses Prinzip
> gilt nur für Jeedom-Updates und nicht für Plugins.

> **Spitze**
>
> Sie können ein Update von Jeedom erzwingen, auch wenn dies nicht der Fall ist
> biete keine an.

Registerkarten &quot;Core&quot; und &quot;Plugins&quot; sowie die Registerkarte &quot;Andere&quot;
------------------------------------------

Diese beiden ähnlichen Registerkarten bestehen aus einer Tabelle :

-   **Core und Plugins** : Enthält die grundlegende Jeedom-Software (Kern) und die
    Liste der installierten Plugins.

-   **Andere** : Enthält Widgets, Skripte usw..

Sie finden folgende Informationen : \.* **Status** : OK oder NOK.
Ermöglicht den aktuellen Status des Plugins. \.* **Name** : Du da
Suchen Sie die Quelle des Elements, den Elementtyp und seinen Namen. \.*
**Version** : Gibt die spezifische Version des Elements an. \.* **Optionen** :
Aktivieren Sie dieses Kontrollkästchen, wenn dieses Element nicht aktualisiert werden soll.
Tag während des allgemeinen Updates (Button **Update**).

> **Spitze**
>
> In der ersten Zeile ist für jede Tabelle der folgende Filter zulässig
> der Name der vorhandenen Elemente.

In jeder Zeile können Sie die folgenden Funktionen verwenden
jedes Element :

-   **Wieder einstellen** : Neuansiedlung erzwingen.

-   **Entfernen** : Ermöglicht die Deinstallation.

-   **überprüfen** : Fragen Sie die Quelle nach Updates ab, um herauszufinden, ob
    Ein neues Update ist verfügbar.

-   **Update** : Ermöglicht das Aktualisieren des Elements (falls vorhanden)
    ein Update).

-   **Änderungsprotokoll** : Greifen Sie auf die Liste der Änderungen in der zu
    Update.

> **Wichtig**
>
> Wenn das Änderungsprotokoll leer ist, Sie aber noch ein Update haben
> Update bedeutet, dass die Dokumentation aktualisiert wurde.
> Es besteht daher keine Notwendigkeit, den Entwickler danach zu fragen
> Änderungen, da es nicht unbedingt welche gibt. (Es ist oft eine Wette
> Übersetzung, Dokumentation)

> **Spitze**
>
> Beachten Sie, dass &quot;Kern : jeedom &quot;bedeutet&quot; das Aktualisieren der Software
> Jeedom Basis".

Registerkarte &quot;Protokolle&quot;
-----------

Registerkarte, zu der Sie bei der Installation automatisch wechseln
Update ermöglicht es Ihnen, alles zu verfolgen, was während des Updates passiert
aktuell mit Kern, wie Plugins.


Befehlszeilenaktualisierung 
================================

Es ist möglich, Jeedom direkt in SSH zu aktualisieren.
Sobald die Verbindung hergestellt ist, ist dies der auszuführende Befehl :

    sudo php /var/www/html/install/update.php

Die möglichen Parameter sind :

-   **`mode`** : `force`, pour lancer une Update en mode forcé (ne
    ignoriert Fehler).

-   **`version`** : gefolgt von der Versionsnummer, um die erneut anzuwenden
    Änderungen seit dieser Version.

Hier ist ein Beispiel für die Syntax, mit der ein erzwungenes Update durchgeführt werden soll
Änderungen seit 3.2 erneut anwenden.14 :

    sudo php / var / www / html / install / update.PHP-Modus = Force-Version = 3.2.14

Achtung, nach einem Update in der Kommandozeile ist es notwendig
Wenden Sie die Rechte erneut auf den Jeedom-Ordner an :

    chown -R www-Daten:www-data / var / www / html
