Cette page permet de voir tous les rapports qui ont été générés par l'action report (voir documentation des scénarios).

# Qu'est qu'un rapport ?

Un rapport est une capture d'écran de l'interface de Jeedom à un instant T (la capture est adaptée pour ne pas prendre la barre de menu et autre élément inutile sur ce type d'utilisation).

Vous pouvez le faire sur des vues, design, page de panel....

Elle se déclenche à partir d'un scénario avec la commande report, vous pouvez choisir de vous faire envoyer ce rapport à l'aide d'une commande message (mail, télégram, etc.)

# Utilisation

Son utilisation est très simple vous sélectionnez si vous voulez voir :

-	les rapports des vues
-	les rapports des degins
-	les rapports des panels des plugins
- les rapports sur les équipements (pour avoir un résumé de la batterie de chaque module)

Ensuite vous sélectionnez le nom du rapport en question puis vous allez voir toutes les dates des rapports en mémoire

> **Important**
>
> Une suppression automatique est faite par défaut pour les rapports de plus de 30 jours (vous pouvez configurer ce délai dans la configuration de Jeedom)

Une fois le rapport sélectionné vous pouvez le voir apparaitre, le retélécharger ou le supprimer.

Vous pouvez aussi supprimer toutes les sauvegardes d'un rapport donné

# FAQ

> **Si vous avez une erreur du type Détails : cutycapt: error while loading shared libraries: libEGL.so: cannot open shared object file: No such file or directory**
>
> Il faut en ssh ou dans Administration -> Configuration -> OS/DB -> Systeme -> Administration faire :
>sudo ln -s /usr/lib/aarch64-linux-gnu/libGLESv2.so.2 /usr/lib/aarch64-linux-gnu/libGLESv2.so
>sudo ln -s /usr/lib/aarch64-linux-gnu/libEGL.so.1 /usr/lib/aarch64-linux-gnu/libEGL.so
