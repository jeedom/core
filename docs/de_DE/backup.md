Jeedom kann von verschiedenen Standorten aus gesichert und
wiederhergestellt werden.

Konfiguration
=============

Erreichbar unter **Verwaltung → Sicherungen**, diese Seite ermöglicht die Verwaltung von Sicherungen.

Auf der linken Seite befinden sich die Parameter und die Aktions Schaltflächen. Auf der rechten Seite ist der Echtzeitstatus der aktuellen Aktion (Sicherung oder Wiederherstellung), wenn Sie etwas gestartet haben.

**Sicherungen** 
---------------

-   **Sicherungen** : Ermöglicht Ihnen, eine Sicherung manuell und sofort zu
    starten (nützlich, wenn Sie eine kritische Änderung vornehmen möchten, 
    damit Sie zurückgehen können). Sie haben auch eine Schaltfläche, um eine 
    Sicherung zu starten, ohne das Archiv an die Cloud zu senden
    (Abonnement erforderlich, siehe unten). Das Senden einer
    Sicherung in die Cloud kann eine Weile dauern. Diese Option
    ermöglicht es, einen zu großen Zeitverlust zu vermeiden.

-   **Sicherungsort** : legt den Ordner fest, in dem Jeedom die Backups
    speichert. Es wird empfohlen, ihn nicht zu ändern. Wenn Sie sich
    auf einem relativen Pfad befinden,
    ist das der Ursprung von Jeedom.

-   **Anzahl der Tag(e) zum Speichern der Sicherungskopie** : Anzahl der
    Tage, an denen die Sicherung aufbewahrt werden soll. Sobald diese Zeit
    verstrichen ist, werden die Backups gelöscht. Achten Sie darauf, dass Sie die
    Anzahl der Tage nicht zu hoch setzen, da Ihr Dateisystem sonst
    überlastet ist.

-   **Maximale Gesamtgröße der Backups (MB)** : Ermöglicht Ihnen, den
    Speicherplatz aller Backups im Backup-Ordner zu begrenzen. Wenn dieser
    Wert überschritten wird, löscht Jeedom die ältesten Backups, bis die
    maximale Größe unterschritten wird. Es wird jedoch mindestens
    eine Sicherung beibehalten.

**Lokale Sicherung**
-----------------------

-   **Verfügbare Sicherungen** : Liste der verfügbaren Sicherungen.

-   **Sicherung wiederherstellen** : Startet die Wiederherstellung des oben
    ausgewählten Backups.

-   **Sicherung löschen** : Löscht die oben ausgewählte Sicherung,
    nur im lokalen Ordner.

-   **Sicherungskopie senden** : Erlaubt das Senden einer Sicherungs Datei des
    Archivs, zu dem aktuellen Computer den man gerade benutzt
    (zum Beispiel, zum Wiederherstellen eines zuvor auf einem neuen
    Jeedom gefundenen Archivs oder einer Neuinstallation).

-   **Sicherungskopie herunterladen** : Ermöglicht Ihnen, das Archiv des
    oben ausgewählten Backups auf Ihren Computer herunterzuladen.

**Markt Sicherung**
----------------------

-   **Sicherungskopie senden** : Sagt Jeedom, dass Sicherungskopien in die
    Markt Cloud gesendet werden sollen, Achtung, Sie müssen
    das Abonnement annehmen.

-   **Ausgewählte lokale Backup senden** :  Sie können ein Cloud-Sicherungs-
    Archiv auf Ihrem Computer zu senden.

-   **Verfügbare Sicherungen** :  Liste der verfügbaren
    Cloud Sicherungen.

-   **Sicherung wiederherstellen** : Startet die Wiederherstellung einer
    Cloud Sicherung.

**Samba Sicherung**
---------------------

-   **Sicherungskopie senden** : Weist Jeedom an, dass die Sicherung an die
    hier Administration → Konfiguration → Registerkarte Updates
    konfigurierte Samba-Freigabe gesendet werden sollen.

-   **Verfügbare Sicherungen** :  Liste der verfügbaren
    Samba Sicherungen.

-   **Sicherung wiederherstellen** : Startet die Wiederherstellung der oben 
    ausgewählten Samba Sicherung.

> **Tip**
>
> Je nachdem, was aktiviert ist, können Sie auf
> Administration → Konfiguration → Registerkarte Updates, mehr oder
> weniger Bereiche sehen.

> **Tip**
>
> Bei der Neuinstallation von Jeedom und der Aufnahme des Backup
> Abonnements in die Cloud des Marktes, müssen Sie Ihr Market-Konto auf 
> Ihrem neuen Jeedom eingeben (Administration → Konfiguration →
> Registerkarte Updates) und dann hierher kommen, um die Wiederherstellung zu starten.

> **Tip**
>
> Bei Problemen können Sie in der Komandozeile eine Sicherungskopie
> erstellen : `sudo php /usr/share/nginx/www/jeedom/install/backup.php`
> oder `sudo php /var/www/html/install/backup.php` abhängig von Ihrem System.

> **Tip**
>
> Es ist auch möglich, eine Sicherung von der Komandozeile aus
> wiederherzustellen (standardmäßig stellt Jeedom die letzte Sicherung im
> Sicherungsverzeichnis wieder her) : `sudo php /usr/share/ngin
> /www/jeedom/install/restore.php` oder `sudo php /var/www/html/instal
> /restore.php` .

Was wird gespeichert ?
==============================

Lors d’une sauvegarde, Jeedom va sauvegarder tous ses fichiers et la
base de données. Cela contient donc toute votre configuration
(équipements, commandes, historiques, scénarios, design, etc.).

Au niveau des protocoles, seul le Z-Wave (OpenZwave) est un peu
différent car il n’est pas possible de sauvegarder les inclusions.
Celles-ci sont directement incluses dans le contrôleur, il faut donc
garder le même contrôleur pour retrouver ses modules Zwave.

> **Note**
>
> Le système sur lequel est installé Jeedom n’est pas sauvegardé. Si
> vous avez modifié des paramètres de ce système (notamment via SSH),
> c’est à vous de trouver un moyen de les récupérer en cas de soucis.

Sauvegarde cloud 
================

La sauvegarde dans le cloud permet à Jeedom d’envoyer vos sauvegardes
directement sur le Market. Cela vous permet de les restaurer facilement
et d’être sûr de ne pas les perdre. Le Market conserve les 6 dernières
sauvegardes. Pour vous abonner il suffit d’aller sur votre page
**profil**sur le Market, puis, dans l’onglet**mes backups**. Vous
pouvez, à partir de cette page, récupérer une sauvegarde ou acheter un
abonnement (pour 1, 3, 6 ou 12 mois).

> **Tip**
>
> Vous pouvez personnaliser le nom des fichiers de sauvegarde à partir
> de l’onglet **Mes Jeedoms**, en évitant toutefois les caractères
> exotiques.

Fréquence des sauvegardes automatiques 
======================================

Jeedom effectue une sauvegarde automatique tous les jours à la même
heure. Il est possible de modifier celle-ci, à partir du "Moteur de
tâches" (la tâche est nommée **Jeedom backup**), mais ce n’est pas
recommandé. En effet, elle est calculée par rapport à la charge du
Market.
