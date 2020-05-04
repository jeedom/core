# Vues
**Accueil → Vue**

Les vues permettent de créer un affichage personnalisé.
Ce n’est pas aussi puissant que les designs mais cela permet en quelques minutes d’avoir un affichage plus personnalisé.

> **Tip**
>
> Vous pouvez dans votre profil choisir la vue par défaut lors du clic sur le menu des vues.

## Principe

On peut mettre aussi bien des widgets, des graphiques (qui peuvent être composés de plusieurs données) ou des zones tableau (qui contiennent les widgets des commandes).

Sur cette page, on retrouve un bouton en haut à gauche pour montrer ou masquer la liste des vues ainsi que le bouton pour en ajouter une (Jeedom vous demandera son nom et vous enverra sur la page d’édition) :

> **Tip**
>
> Vous pouvez dans votre profil modifier cette option pour que la liste des vues soit visible par défaut.

## Ajout/Edition d’une vue

Le principe est assez simple : une vue est composée de zones (on peut en mettre autant que l’on veut). Chaque zone est de type graphique, widget ou tableau, en fonction du type vous pourrez mettre des widgets d’équipement, de commande ou des graphiques dans celle-ci.

> **Tip**
>
> Il est possible de déplacer l’ordre des zones par glisser/déposer.

- Sur la gauche de la page on retrouve la liste des vues ainsi qu’un bouton d’ajout.
- Un bouton en haut à droite vous permet d’éditer la vue courante.
- Au centre vous avez un bouton pour renommer une vue, un bouton d’ajout de zone, un bouton pour voir le résultat, un bouton pour sauvegarder et un bouton pour supprimer la vue.

Après avoir cliqué sur le bouton d’ajout de zone, Jeedom vous demandera son nom et son type.
Sur chaque zone vous avez les options générales suivantes :

- **Largeur** : Définit la largeur de la zone (en mode desktop seulement).
- **Editer** : Permet de changer le nom de la zone.
- **Supprimer** : Permet de supprimer la zone.

### Zone de type widget

Une zone de type widget permet d’ajouter des widgets :

- **Ajouter widget** : Permet d’ajouter/modifier des widgets à afficher dans la zone.

> **Tip**
>
> Vous pouvez supprimer un widget directement en cliquant sur la poubelle devant celui-ci.

> **Tip**
>
> Il est possible de modifier l’ordre des widgets dans la zone par glisser/déposer.

Une fois le bouton d’ajout de widget pressé, vous obtenez une fenêtre qui vous demandera le widget à ajouter

### Zone de type graphique

Une zone de type graphique permet d’ajouter des graphiques à votre vue, elle possède les options suivantes :

- **Période** : Permet de choisir la période d’affichage des graphiques (30 min, 1 jour, 1 semaine, 1 mois, 1 année ou tout).
- **Ajouter courbe** : Permet d’ajouter/modifier des graphiques.

Quand vous pressez le bouton "Ajouter courbe" Jeedom affiche la liste des commandes historisées et vous pouvez choisir celle(s) à ajouter, une fois cela fait vous avez accès aux options suivantes :

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

- **texte** : juste du texte à écrire.
- **html** : n’importe quel code html (javascript possible mais fortement déconseillé).
- **widget de commande** : le bouton à droite vous permet de choisir la commande à afficher (attention cela affiche le widget de la commande).


