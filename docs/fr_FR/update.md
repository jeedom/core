# Centre de Mise à jour
**Réglages → Système → Centre de Mise à jour**


Le **centre de mise à jour** permet de mettre à jour toutes les fonctionnalités de Jeedom, incluant le logiciel de base (core) et ses plugins.
D’autres fonctions de gestion des extensions sont disponibles (supprimer, réinstaller, vérifier, etc.).


## Fonctions de la page

En haut de la page, indépendant de l'onglet, se trouvent les boutons de commande.

Jeedom se connecte périodiquement au Market pour voir si des mises à jour sont disponibles. La date de dernière vérification est indiquée en haut à gauche de la page.

A l'ouverture de la page, si cette vérification date de plus de deux heures, Jeedom refait automatiquement une vérification.
Vous pouvez également utiliser le bouton **Vérifier les mises à jour** Pour le faire manuellement.
Si vous voulez réaliser une vérification manuelle, vous pouvez appuyer sur le bouton "Vérifier les mises à jour".

Le bouton **Sauvegarder** est à utiliser quand vous changez les options dans le tableau plus bas, pour spécifier de ne pas mettre à jour certains plugins si nécessaire.

## Mettre à jour le Core

Le bouton **Mettre à jour** permet de mettre à jour le Core, les plugins, ou les deux.
Une fois que vous avez cliqué dessus, on obtient ces différentes options :
- **Pré-update** : Permet de mettre à jour le script de mise à jour avant l'application des nouvelles mises à jour. Généralement utilisé sur demande du support.
- **Sauvegarder avant** : Effectue une sauvegarde de Jeedom avant d’effectuer la mise à jour.
- **Mettre à jour les plugins** : Permet d’inclure les plugins dans la mise à jour.
- **Mettre à jour le core** : Permet d’inclure le noyau de Jeedom (le Core) dans la mise à jour.

- **Mode forcé** : Effectue la mise à jour en mode forcé, c’est-à-dire que, même s’il y a une erreur, Jeedom continuera et ne restaurera pas la sauvegarde. (Ce mode désactive la sauvegarde !).
- **Mise à jour à réappliquer** : Permet de ré-appliquer une mise à jour. (NB : Toutes les mises à jour ne peuvent pas être ré-appliquées.)

> **Important**
>
> Avant une mise à jour, par défaut, Jeedom va faire une sauvegarde. En cas de souci lors de l’application d’une mise à jour, Jeedom va automatiquement restaurer la sauvegarde faite juste avant. Ce principe n’est valable que pour les mises à jour de Jeedom et non la mise à jour des plugins.

> **Tip**
>
> Vous pouvez forcer une mise à jour de Jeedom, même si celui-ci ne vous en propose pas.

## Onglets Core et Plugins

Le tableau contient les versions du Core et des plugins installés.

Les plugins disposent d'un badge à coté de leur nom, spécifiant leur version, de couleur verte en *stable*, ou orange en *beta* ou autre.

- **Statut** : OK ou NOK.
- **Nom** : Nom et provenance du plugin
- **Version** : Indique la version précise du Core ou du plugin.
- **Options** : Cochez cette case si vous ne souhaitez pas que ce plugin soit mis à jour lors de la mise à jour globale (Bouton **Mettre à jour**).

Sur chaque ligne, vous pouvez utiliser les fonctions suivantes:

- **Réinstaller** : Force la réinstallation.
- **Supprimer** : Permet de le désinstaller.
- **Vérifier** : Interroge la source des mises à jour pour savoir si une nouvelle mise à jour est disponible.
- **Mettre à jour** : Permet de mettre à jour l’élément (si celui-ci a une mise à jour).
- **Changelog** : Permet d’accéder à la liste des changements de la mise à jour.

> **Important**
>
> Si le changelog est vide mais que vous avez tout de même une mise à jour, cela signifie que c’est la documentation qui a été mise à jour. Il n’est donc pas nécessaire de demander au développeur les changements, vu qu’il n’y en a pas forcément. (c’est souvent une mise à jour de la traduction, de la documentation).
> Le développeur du plugin peut aussi dans certains cas faire des bugfix simples, ne nécessitant pas forcément de mettre à jour le changelog.

> **Tip**
>
> Quand vous lancez une mise à jour, une barre de progression apparaît au dessus du tableau. Évitez d'autres manipulations pendant la mise à jour.

## Onglet Informations

En cours de mise à jour ou après celle-ci, cet onglet permet de lire en temps réel le log de cette mise à jour.

> **Note**
>
> Ce log finit normalement par *[END UPDATE SUCCESS]*. Il peut y avoir certaines lignes d'erreur dans ce type de log, toutefois, sauf problème réel après mise à jour, il n'est pas toujours nécessaire de contacter le support pour cela.

## Mise à jour en ligne de commande

Il est possible de faire une mise à jour de Jeedom directement en SSH.
Une fois connecté, voilà la commande à effectuer :

```sudo php /var/www/html/install/update.php```

Les paramètres possibles sont :

- **mode** : `force`, pour lancer une mise à jour en mode forcé (ne tient pas compte des erreurs).
- **version** : Suivi du numéro de version, pour ré-appliquer les changements depuis cette version.

Voici un exemple de syntaxe pour faire une mise à jour forcée en ré-appliquant les changements depuis la 4.0.04 :

```sudo php  /var/www/html/install/update.php mode=force version=4.0.04```

Attention, après une mise à jour en ligne de commande, il faut ré-appliquer les droits sur le dossier Jeedom :

```sudo chown -R www-data:www-data /var/www/html```
