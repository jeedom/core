Le sous-menu Gestion des plugins permet de manipuler les plugins, à
savoir : les télécharger, les mettre à jour et les activer,etc.​

Gestion des plugins 
===================

Vous pouvez accéder à la page plugins à partir de Plugins → Gestion des
plugins. Une fois que l’on clique dessus, on retrouve la liste des
plugins dans l’ordre alphabétique et un lien vers le market. Les plugins
désactivés sont grisés.

> **Tip**
>
> Comme à beaucoup d’endroits sur Jeedom, mettre la souris tout à gauche
> permet de faire apparaître un menu d’accès rapide (vous pouvez à
> partir de votre profil le laisser toujours visible). Ici, le menu
> permet d’avoir la liste des plugins triés par catégories.

En cliquant sur un plugin, vous accédez à sa configuration. En haut, vous
retrouvez le nom du plugin, puis entre parenthèses, son nom dans Jeedom
(ID) et enfin, le type de version installée (stable, beta).

> **Important**
>
> Lors du téléchargement d’un plugin, celui-ci est désactivé par défaut.
> Il faut donc que vous l’activiez par vous-même.

En haut à droite, quelques boutons :

-   **Documentation** : Permet d’accéder directement à la page de
    documentation du plugin

-   **Changelog** : permet de voir le changelog du plugin si il existe

-   **Envoyer sur le Market** : permet d’envoyer le plugin sur le Market
    (uniquement disponible si vous en êtes l’auteur)

-   **Détails** : permet de retrouver la page du plugin sur le market

-   **Supprimer** : Supprime le plugin de votre Jeedom. Attention, cela
    supprime également définitivement tous les équipements de ce plugin

En dessous à gauche, on retrouve une zone état avec :

-   **Statut** : permet de voir le statut du plugin (actif / inactif)

-   **Version** : la version du plugin installée

-   **Action** : Permet d’activer ou désactiver le plugin

-   **Version Jeedom** : Version de Jeedom minimum requise
    pour le fonctionnement du plugin

-   **Licence** : Indique la licence du plugin qui sera généralement
    AGPL

A droite, on retrouve la zone Log et surveillance qui permet de définir 

-   le niveau de logs spécifique au plugin (on retrouve cette même possibilité dans
Administation → Configuration sur l’onglet logs, en bas de page)

-   de voir les logs du plugin

-   Heartbeat : toutes les 5 mins, Jeedom regarde si au moins un équipement du plugin a communiqué dans les X dernières minutes (si vous voulez désactiver la fonctionnalité, il suffit de mettre 0)

-   Redémarrer démon : si le Heartbeat tombe en erreur alors Jeedom va redémarrer le démon

Si le plugin possède des dépendances et/ou un démon, ces zones
supplémentaires s’affichent sous les zones citées ci-dessus.

Dépendances :

-   **Nom** : généralement sera local

-   **Statut** : vous dira si les dépendances sont OK ou KO

-   **Installation** : permettra d’installer ou réinstaller les
    dépendances (si vous ne le faites pas manuellement et qu’elles sont
    KO, Jeedom s’en chargera de lui-même au bout d’un moment)

-   **Dernière installation** : date de la dernière installation des
    dépendances

Démon :

-   **Nom** : généralement sera local

-   **Statut** : vous dira si le démon est OK ou KO

-   **Configuration** : sera OK si tous les critères pour que le démon
    tourne sont réunis ou donnera la cause du blocage

-   **(Re)Démarrer** : permet de lancer ou relancer le démon

-   **Arrêter** : permet d’arrêter le démon (Uniquement dans le cas où
    la gestion automatique est désactivée)

-   **Gestion automatique** : permet d’activer ou désactiver la gestion
    automatique (ce qui permet à Jeedom de gérer lui même le démon et le
    relancer si besoin. Sauf contre indication, il est conseillé de
    laisser la gestion automatique active)

-   **Dernier lancement** : date du dernier lancement du démon

> **Tip**
>
> Certains plugins ont une partie configuration. Si tel est le cas, elle
> apparaîtra sous les zones dépendances et démon décrites ci-dessus.
> Dans ce cas, il faut se référer à la documentation du plugin en
> question pour savoir comment le configurer.

En dessous, on retrouve une zone fonctionnalités. Celle-ci permet de voir
si le plugin utilise une des fonctions core Jeedom tel que :

-   **Interact** : des interactions spécifiques

-   **Cron** : un cron à la minute

-   **Cron5** : un cron toutes les 5 minutes

-   **Cron15** : un cron toutes les 15 minutes

-   **Cron30** : un cron toutes les 30 minutes

-   **CronHourly** : un cron toutes les heures

-   **CronDaily** : un cron journalier

> **Tip**
>
> Si le plugin utilise une de ces fonctions, vous pourrez spécifiquement
> lui interdire de le faire en décochant la case "activer" qui sera
> présente à côté.

Enfin, on peut retrouver une section Panel qui permettra d’activer ou
désactiver l’affichage du panel sur le dashboard ou en mobile si le
plugin en propose un.

Installation d’un plugin 
========================

Pour installer un nouveau plugin, il suffit de cliquer sur le bouton
"Market" (et que Jeedom soit relié à Internet). Après un petit temps de
chargement, vous obtiendrez la page.

> **Tip**
>
> Vous devez avoir saisi les informations de votre compte du Market dans
> l’administration (Configuration→Mises à jour→Onglet market) afin de
> retrouver les plugins que vous avez déjà achetés par exemple.

En haut de la fenêtre, vous avez des filtres :

-   **Gratuit/Payant** : permet d’afficher uniquement les gratuits ou
    les payants.

-   **Officiel/Conseillé** : permet d’afficher uniquement les plugins
    officiels ou les conseillés

-   **Installé/Non installé** : permet d’afficher uniquement les plugins
    installés ou non installés

-   **Menu déroulant Catégorie** : permet d’afficher uniquement
    certaines catégories de plugins

-   **Rechercher** : permet de rechercher un plugin (dans le nom ou la
    description de celui-ci)

-   **Nom d’utilisateur** : affiche le nom d’utilisateur utilisé pour la
    connexion au Market ainsi que le statut de la connexion

> **Tip**
>
> La petite croix permet de réinitialiser le filtre concerné

Une fois que vous avez trouvé le plugin voulu, il suffit de cliquer sur
celui-ci pour faire apparaître sa fiche. Cette fiche vous donne beaucoup
d’informations sur le plugin, notamment :

-   S’il est officiel/recommandé ou s’il est obsolète (il faut vraiment
    éviter d’installer des plugins obsolètes)

-   4 actions :

    -   **Installer stable** : permet d’installer le plugin dans sa
        version stable

    -   **Installer beta** : permet d’installer le plugin dans sa
        version beta (seulement pour les betatesteurs)

    -   **Installer pro** : permet d’installer la version pro (très
        peu utilisé)

    -   **Supprimer** : si le plugin est actuellement installé, ce
        bouton permet de le supprimer

En dessous, vous retrouvez la description du plugin, la compatibilité
(si Jeedom détecte une incompatibilité, il vous le signalera), les avis
sur le plugin (vous pouvez ici le noter) et des informations
complémentaires (l’auteur, la personne ayant fait la dernière mise à
jour, un lien vers la doc, le nombre de téléchargements). Sur la droite
vous retrouvez un bouton "Changelog" qui vous permet d’avoir tout
l’historique des modifications, un bouton "Documentation" qui renvoie
vers la documentation du plugin. Ensuite vous avez la langue disponible
et les diverses informations sur la date de la dernière version stable.

> **Important**
>
> Il n’est vraiment pas recommandé de mettre un plugin beta sur un
> Jeedom non beta, beaucoup de soucis de fonctionnement peuvent en
> résulter.

> **Important**
>
> Certains plugins sont payants, dans ce cas la fiche du plugin vous
> proposera de l’acheter. Une fois cela fait, il faut attendre une
> dizaine de minutes (temps de validation du paiement), puis retourner
> sur la fiche du plugin pour l’installer normalement.

> **Tip**
>
> Vous pouvez aussi ajouter un plugin à Jeedom à partir d’un fichier ou
> depuis un dépôt Github. Pour cela, il faut, dans la configuration de
> Jeedom, activer la fonction adéquate dans la partie "Mises à jour et
> fichiers". Il sera ensuite possible, en mettant la souris tout à
> gauche, et en faisant apparaître le menu de la page plugin, de cliquer
> sur "Ajout depuis une autre source". Vous pourrez ensuite choisir la
> source "Fichier". Attention, dans le cas de l’ajout par un fichier
> zip, le nom du zip doit être le même que l’ID du plugin et dès
> l’ouverture du ZIP un dossier plugin\_info doit être présent.
