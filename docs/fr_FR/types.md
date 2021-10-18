# Types d'équipement
**Outils → Types d'équipement**

Les capteurs et actionneurs dans Jeedom sont gérés par des plugins, qui créent des équipements avec des commandes *Info* (capteur) ou *Action* (actionneur). Ce qui permet ensuite de déclencher des actions en fonctions du changement de certains capteurs, comme allumer une lumière sur une détection de mouvement. Mais le Core de Jeedom, et des plugins comme *Mobile*, *Homebridge*, *Google Smarthome*, *Alexa* etc., ne savent pas ce que sont ces équipements : Une prise, une lumière, un volet, etc.

Pour palier à ce problème, notamment avec les assistants vocaux (*Allume la lumière de la salle*), le Core a introduit il y a quelques années les **Types Génériques**, utilisés par ces plugins.

Cela permet ainsi d'identifier un équipement par *La lumière de la salle* par exemple.

Les Types Génériques sont également intégrés dans les scénarios. Vous pouvez ainsi déclencher un scénario si une lampe s'allume dans une pièce, si un mouvement est détecté dans la maison, éteindre toutes les lumières ou fermer tous les volets avec une seule action, etc. De plus, si vous ajoutez un équipement, vous n'avez qu'à indiquer ces types, il ne sera pas nécessaire de retoucher de tels scénarios.

Le paramétrage des ces Types Génériques peut se faire directement dans certains plugins, ou par commande dans *Configuration avancée* de celle-ci.

Cette page permet de paramétrer ces Types Génériques directement, de manière plus directe et plus simple, et propose même une assignation automatique une fois les équipements assignés correctement.

![Types d'équipement](./images/coreGenerics.gif)

## Type d'équipement

Cette page propose un rangement par type d'équipement : Prise, Lumière, Volet, Thermostat, Camera, etc. Au départ, la plupart de vos équipements seront classés dans **Equipements sans type**. Pour leur assigner un type, vous pouvez soit les déplacer dans un autre type, soit faire un clic droit sur l'équipement pour le déplacer directement.

> **Tip**
>
> - Quand vous déplacer un équipement dans la partie **Equipements sans type**, Jeedom vous propose de supprimer les Types Génériques sur ses commandes.
> - Vous pouvez déplacer plusieurs équipements d'un coup en cochant les cases à cocher à gauche de ceux-ci.

## Type de commande

Une fois un équipement positionné dans le bon *Type*, en cliquant dessus vous accédez à la liste de ses commandes, colorées différemment si c'est une *Info* (Bleue) ou une *Action* (Orange).

Au clic droit sur une commande, vous pouvez alors lui attribuer un Type Générique correspond aux spécifiées de cette commande (type Info/Action, sous-type Numérique, Binaire, etc).

> **Tip**
>
> - Le menu contextuel des commandes affiche le type de l'équipement en caractères gras, mais permet tout de même d'attribuer n'importe quel Type Générique de n'importe quel type d'équipement.

Sur chaque équipement, vous avez deux boutons :

- **Types Auto** : Cette fonction ouvre une fenêtre vous proposant les Types Génériques appropriés en fonction du type de l'équipement, des spécificités de la commande, et de son nom. Vous pouvez alors ajuster les propositions et décocher l'application à certaines commandes avant d'accepter ou pas. Cette fonction est compatible avec la sélection par les cases à cocher.

- **Reset types** : Cette fonction supprime les Types Génériques de toutes les commandes de l'équipement.

> **Attention**
>
> Aucun changement n'est effectué avant de sauvegarder, avec le bouton en haut à droite de la page.