# Analyse équipements
**Analyse → Equipements**

La page Analyse d’équipements vous permet de voir de nombreuses infos relatives aux équipements de manière centralisée :

- L’état de vos piles
- Les modules en alertes
- Les actions définies
- Les alertes définies
- Les commandes orphelines

## Onglet Batteries


Vous pouvez voir sur cet onglet la liste de vos modules sur batterie, leur niveau restant (la couleur de la tuile dépend de ce niveau), le type et le nombre de piles qu’il faut mettre dans le module, le type du module ainsi que la date à laquelle l’information du niveau de batterie a été mise à jour. Vous pouvez aussi voir si un seuil spécifique a été établi pour le module en particulier (représenté par une main)

> **Tip**
>
> Les seuils d’alerte/warning sur les niveaux des batteries sont configurables de manière globale dans la configuration de Jeedom (Réglages→Systèmes→Configuration : Equipements), ou par équipement sur la page de configuration avancée de ceux-ci dans l’onglet alertes.

## Onglet Modules en alerte

Sur cet onglet vous verrez en temps réel les modules en alerte. Les alertes peuvent être de différents types :

- Timeout (configuré dans l’onglet alertes définies).
- Batterie en warning ou en danger.
- Commande en warning ou danger (paramétrable dans les paramètres avancées des commandes).

D’autres types d’alertes pourront éventuellement se retrouver ici.
Chaque alerte sera représentée par la couleur de la tuile (le niveau d’alerte) et un logo en haut à gauche (le type d’alerte).

> **Tip**
>
> Ici seront affichés tous les modules en alerte même ceux configurés en "non visible". Il est toutefois intéressant de noter que si le module est en "visible" l’alerte sera aussi visible sur le dashboard (dans l’objet concerné).

## Onglet Actions définies

Cet onglet permet de visualiser les actions définies directement sur une commande. En effet, on peut en mettre sur différentes commandes et il peut être difficile de se rappeler de toutes. Cet onglet est là pour ça et synthétise plusieurs choses :

- Les actions sur état (que l’on retrouve dans les paramètres avancées des commandes infos et permettant de réaliser une ou plusieurs actions sur la valeur d’une commande - de manière immédiate ou après un délai).
- Les confirmations d’actions (configurable au même endroit sur une commande info et permettant de demander une confirmation pour exécuter une action).
- Les confirmations avec code (même chose que précédemment mais avec la saisie d’un code).
- Les actions pre et post (configurables toujours au même endroit sur une commande action et permettant d’exécuter une ou plusieurs autres actions avant ou après l’action en question).

> **Tip**
>
> Le tableau permet de voir de manière très textuelle les actions définies. D’autres types d’actions définies pourront être rajoutées.

## Onglet Alertes définies

Cet onglet permet de voir l’ensemble des alertes définies, vous y retrouverez dans un tableau les infos suivantes si elles existent :

- Les alertes sur délai de communication.
- Les seuils spécifiques de batterie définis sur un équipement.
- Les différentes alertes danger et warning des commandes.

## Onglet Commandes orphelines

Cet onglet vous permet de voir en un coup d’œil si vous avez des commandes orphelines utilisées au travers de Jeedom. Une commande orpheline est une commande utilisée quelque part mais qui n’existe plus. On retrouvera ici l’ensemble de ces commandes, comme par exemple :

- Les commandes orphelines utilisée dans le corps d’un scenario.
- celles utilisées en déclencheur d’un scénario.

Et utilisées à plein d’autres endroits comme (non exhaustif) :
- Les interactions.
- Les configurations de Jeedom.
- En pre ou post action d’une commande.
- En action sur état d’une commande.
- Dans certains plugins.

> **Tip**
>
> Le tableau permet de voir de manière très textuelle les commandes orphelines. Son but est de pouvoir identifier rapidement toutes les commandes "orphelines" au travers de tout Jeedom et des plugins. Il se peut que certaines zones ne soient pas analysées, le tableau se verra être de plus en plus exhaustif avec le temps.
