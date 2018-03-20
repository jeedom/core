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

-   **Mettre à jour les plugins** : Permet d’inclure les plugins dans la
    mise à jour.

-   **Mettre à jour le core** : Permet d’inclure le noyau de Jeedom dans
    la mise à jour.

-   **Mode forcé** : Effectue la mise à jour en mode forcé, c’est-à-dire
    que, même s’il y a une erreur, Jeedom continue et ne restaurera pas
    la sauvegarde.

-   **Mise à jour à réappliquer** : Permet de réappliquer une mise
    à jour. (NB : Toutes les mises à jour ne peuvent être réappliquées.)

> **Important**
>
> Avant une mise à jour, par défaut, Jeedom va faire une sauvegarde. En
> cas de souci lors de l’application d’une mise à jour, Jeedom va
> automatiquement restaurer la sauvegarde faite juste avant. Ce principe
> n’est valable que pour les mises à jour de Jeedom et non des plugins.

> **Tip**
>
> Vous pouvez forcer une mise à jour de Jeedom, même si celui-ci ne vous
> en propose pas.

Le tableau des mises à jour 
---------------------------

Le tableau se compose de deux onglets :

-   **Core et Plugins** : Contient le logiciel de base de Jeedom et la
    liste des plugins installés.

-   **Autre** : Contient les widgets, les scripts, etc.

Vous y trouverez les informations suivants : \* **Statut** : OK ou NOK.
Permet de connaître l’état actuel du plugin. \* **Nom** : Vous y
trouverez la provenance de l’élément, le type d’élément et son nom. \*
**Version**: Indique la version précise de l’élément. \***Options** :
Cochez cette case si vous ne souhaitez pas que cet élément soit mis à
jour lors de la mise à jour générale (Bouton **Mettre à jour**).

> **Tip**
>
> Pour chaque tableau, la première ligne permet de filter suivant
> l’état, le nom ou la version des éléments présents.

Sur chaque ligne, vous pouvez utiliser les fonctions suivants pour
chaque élément :

-   **Réinstaller** : Force la réinstallation.

-   **Supprimer** : Permet de le désinstaller.

-   **Vérifier** : Interroge la source des mises à jour pour savoir si
    une nouvelle mise à jour est disponible.

-   **Mettre à jour** : Permet de mettre à jour l’élément (si celui-ci a
    une mise à jour).

-   **Changelog** : Permet d’accéder à la liste des changements de la
    mise à jour.

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
