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

> **Tip**
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

> **Tip**
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

> **Important**
>
> Si le changelog est vide mais que vous avez tout de même une mise à
> jour, cela signifie que c’est la documentation qui a été mise à jour.
> Il n’est donc pas nécessaire de demander au développeur les
> changements, vu qu’il n’y en a pas forcément. (c’est souvent une mise
> à jour de la traduction de la documentation)

> **Tip**
>
> A noter que "core : jeedom" signifie "la mise à jour du logiciel de
> base Jeedom".

Mise à jour en ligne de commande 
================================

Il est possible de faire une mise à jour de Jeedom directement en SSH.
Une fois connecté, voilà la commande à effectuer :

    sudo php /var/www/html/install/update.php

Les paramètres possibles sont :

-   **`mode`** : `force`, pour lancer une mise à jour en mode forcé (ne
    tient pas compte des erreurs).

-   **`version`** : suivi du numéro de version, pour réappliquer les
    changements depuis cette version.

Voici un exemple de syntaxe pour faire une mise à jour forcée en
réappliquant les changements depuis la 1.188.0 :

    sudo php  /var/www/html/install/update.php mode=force version=1.188.0

Attention, après une mise à jour en ligne de commande, il faut
réappliquer les droits sur le dossier Jeedom :

    chown -R www-data:www-data /var/www/html
