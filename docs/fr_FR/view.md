# Vues
**Accueil → Vue**

Les Vues permettent de créer des affichages personnalisés.
Ce n’est pas aussi puissant que les designs mais cela permet en quelques minutes d’avoir un affichage plus personnalisé que le Dashboard, avec des équipements d'objets différents, des graphiques, ou des commandes.

> **Tip**
>
> Vous pouvez dans votre profil choisir la Vue par défaut lors du clic sur le menu des Vues.

## Principe

On peut mettre aussi bien des tuiles d'équipements, des graphiques (qui peuvent être composés de plusieurs données) ou des zones tableau (qui contiennent les widgets des commandes).

Sur une Vue, on retrouve :

- Un bouton en haut à gauche pour montrer ou masquer la liste des Vues, ainsi que le bouton pour en ajouter une.
- Le crayon sur la droite pour éditer l'ordre et la taille des équipements, de la même manière que le Dashboard.
- Un bouton *Edition complète* permettant d'éditer les zones et éléments de la Vue.

> **Tip**
>
> Vous pouvez, dans votre profil, modifier cette option pour que la liste des Vues soit visible par défaut.

## Ajout/Edition d’une Vue

Le principe est assez simple : une Vue est composée de zones. Chaque zone est de type *graphique*, *widget* ou *tableau*. En fonction de ce type, vous pourrez ajouter des graphiques, des équipements, ou des commandes celle-ci.

- Sur la gauche de la page on retrouve la liste des Vues ainsi qu’un bouton de création.
- Un bouton en haut à droite vous permet d’éditer la Vue courante (Configuration).
- Un bouton permettant d'ajouter une zone. Les nom et type de zone vous seront alors demandés.
- Un bouton *Voir le résultat*, permettant de sortir du mode d'édition complète et d'afficher cette Vue.
- Un bouton permettant de sauvegarder cette Vue.
- Un bouton permettant de supprimer cette Vue.

> **Tip**
>
> Il est possible de déplacer l’ordre des zones par glisser/déposer.

Sur chaque zone vous avez les options générales suivantes :

- **Largeur** : Définit la largeur de la zone (en mode desktop seulement). 1 pour une l'argeur d'1/12 du navigateur, 12 pour la largeur totale.
- Un bouton permettant d'ajouter un élément à cette zone, dépendant du type de zone (voir plus bas).
- **Editer** : Permet de changer le nom de la zone.
- **Supprimer** : Permet de supprimer la zone.

### Zone de type equipement

Une zone de type equipement permet d’ajouter des équipements :

- **Ajouter equipement** : Permet d’ajouter/modifier des équipements à afficher dans la zone.

> **Tip**
>
> Vous pouvez supprimer un equipement directement en cliquant sur la poubelle à gauche de celui-ci.

> **Tip**
>
> Il est possible de modifier l’ordre des tuiles dans la zone par glisser/déposer.


### Zone de type graphique

Une zone de type graphique permet d’ajouter des graphiques à votre Vue, elle possède les options suivantes :

- **Période** : Permet de choisir la période d’affichage des graphiques (30 min, 1 jour, 1 semaine, 1 mois, 1 année ou tout).
- **Ajouter courbe** : Permet d’ajouter/modifier des graphiques.

Quand vous pressez le bouton **Ajouter courbe**, Jeedom affiche la liste des commandes historisées et vous pouvez choisir celle à ajouter. Une fois cela fait vous avez accès aux options suivantes :

- **Poubelle** : Supprime la commande du graphique.
- **Nom** : Nom de la commande à dessiner.
- **Couleur** : Couleur de la courbe.
- **Type** : Type de la courbe.
- **Groupement** : Permet de grouper les données (type maximum par jour).
- **Echelle** : Échelle (droite ou gauche) de la courbe.
- **Escalier** : Affiche la courbe en escalier.
- **Empiler** : Empile la courbe avec les autres courbes de type.
- **Variation** : Dessine seulement les variations avec la valeur précédente.

> **Tip**
>
> Il est possible de modifier l’ordre des graphiques dans la zone par glisser/déposer.

### Zone de type tableau

Vous avez ici les boutons :

- **Ajouter colonne** : Permet d’ajouter une colonne au tableau.
- **Ajouter ligne** : Permet d’ajouter une ligne au tableau.

> **Note**
>
> Il est possible de réorganiser les lignes par glisser/déposer mais pas les colonnes.

Une fois que vous avez ajouté vos lignes/colonnes vous pouvez ajouter des informations dans les cases :

- Un texte.
- Du code html (javascript possible mais fortement déconseillé).
- Le Widget d'une commande : Le bouton à droite vous permet de choisir la commande à afficher.
