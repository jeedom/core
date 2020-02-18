# Rapport
**Analyse → Rapport**

Cette page permet de voir tous les rapports qui ont été générés par l'action report (voir documentation des scénarios).

## Principe

Un rapport est une capture d'écran de l'interface de Jeedom à un instant t.

> **Note**
>
> Cette capture est adaptée pour ne pas prendre la barre de menu et autres éléments inutiles sur ce type d'utilisation.

Vous pouvez le faire sur des vues, designs, pages de panel....

La génération se déclenche à partir d'un scénario avec la commande report.
Vous pouvez choisir de vous faire envoyer ce rapport à l'aide d'une commande message (mail, télégram, etc).

## Utilisation

Son utilisation est très simple. Sélectionnez sur la gauche si vous voulez voir :

- Les rapports des vues.
- Les rapports des designs.
- Les rapports des panels de plugins.
- Les rapports sur les équipements (pour avoir un résumé de la batterie de chaque module).

Ensuite, sélectionnez le nom du rapport en question. Vous verrez alors toutes les dates des rapports disponibles.

> **Important**
>
> Une suppression automatique est faite par défaut pour les rapports vieux de plus de 30 jours. Vous pouvez configurer ce délai dans la configuration de Jeedom.

Une fois le rapport sélectionné, vous pouvez le voir apparaître, le télécharger ou le supprimer.

Vous pouvez aussi supprimer toutes les sauvegardes d'un rapport donné

## FAQ

> Si vous avez une erreur du type Détails :
> *cutycapt: error while loading shared libraries: libEGL.so: cannot open shared object file: No such file or directory*
> Il faut en ssh ou dans Réglages → Système → Configuration : OS/DB / Système administration faire :
> ```sudo ln -s /usr/lib/aarch64-linux-gnu/libGLESv2.so.2 /usr/lib/aarch64-linux-gnu/libGLESv2.so```
> ```sudo ln -s /usr/lib/aarch64-linux-gnu/libEGL.so.1 /usr/lib/aarch64-linux-gnu/libEGL.so```
