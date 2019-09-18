Jeedom offre la possibilité d’être sauvegardé et restauré depuis ou à partir
de différents emplacements.

Konfiguration
=============

Accessible depuis **Réglages → Système → Sauvegardes**, cette page permet la
gestion des sauvegardes, restaurations.

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
> Selon ce qui sera activé, dans la page
> Administration→Configuration→Onglet Mises à jour, vous pouvez voir
> plus ou moins de sections.

> **Tip**
>
> Lors d’une réinstallation de Jeedom et en ayant pris l’abonnement de
> sauvegarde vers le cloud du Market, vous devez renseigner votre compte
> Market sur votre nouveau Jeedom (Administration→Configuration→Onglet
> Mises à jour) puis venir ici pour lancer la restauration.

> **Tip**
>
> Il est possible, en cas de soucis, de faire une sauvegarde en ligne de
> commande : `sudo php /usr/share/nginx/www/jeedom/install/backup.php`
> ou `sudo php /var/www/html/install/backup.php` selon votre système.

> **Tip**
>
> Il est possible aussi de restaurer une sauvegarde en ligne de
> commandes (par défaut, Jeedom restaure la sauvegarde la plus récente
> présente dans le répertoire de sauvegarde) :
> `sudo php /usr/share/nginx/www/jeedom/install/restore.php` ou
> `sudo php /var/www/html/install/restore.php`.

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
