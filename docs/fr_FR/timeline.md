# Timeline
**Analyse → Timeline**

## Timeline

La page Timeline permet d'afficher chronologiquement des évènements, comme des changements de commandes *info*, des déclenchements de commandes *action*, et des éxécutions de scénarios.

Pour les voir, il vous faut d’abord activer le suivi sur la timeline des commandes ou scénarios voulus, puis que ces évènements se produisent.

- **Scenario** : Soit directement sur la page d'un scénario, soit sur *Vue d'ensemble* des scénarios.
- **Commande** : Soit dans la configuration avancée de la commande, soit dans la configuration de l’historique pour le faire en "masse".

![Timeline](images/timeline_intro.jpg)

La timeline *Principal* contient toujours l'ensemble des évènements. Toutefois, vous pouvez filtrer la timeline par *dossier*. A chaque endroit où vous activerez la timeline, vous disposerez d'un champ pour entrer le nom d'un dossier, existant ou non.
Vous pourrez alors filtrer la timeline par ce dossier en le sélectionnant à gauche du bouton *Rafraichir*.

> **Note**
>
> Si vous n'utilisez plus un dossier, il apparaitra dans la liste tant que des évènements liés à ce dossier existent. Il disparaitra tout seul de la liste ensuite.

Une fois que vous avez activé le suivi dans la timeline des commandes et scénarios voulus, vous pourrez voir apparaître ceux-ci sur la timeline.

> **Important**
>
> Il faut attendre de nouveaux événements après avoir activé le suivi sur la timeline avant de les voir apparaître.

## Affichage

La timeline affiche les évènements enregistrés, échelonnés jour par jour verticalement.

Pour chaque évènement, vous avez:

- La date et l'heure de l'évènement,
- Le type d'évènement: Une commande info ou action, ou un scénario, avec pour les commandes le plugin de la commande.
- Le nom de l'objet parent, le nom, et suivant le type, l'état ou le déclencheur.

- Un évènement de type commande affiche une icône sur la droite pour ouvrir la configuration de la commande.
- Un évènement de type scénario affiche deux icônes sur la droite pour se rendre sur le scénario, ou ouvrir le log du scénario.

En haut à droite, vous pouvez sélectionner un dossier de timeline. Celui-ci doit être créé avant et doit contenir des évènements.
