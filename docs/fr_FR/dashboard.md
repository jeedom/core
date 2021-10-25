# Dashboard
**Accueil → Dashboard**

<small>[Raccourcis clavier/souris](shortcuts.md)</small>

Le dashboard est une des pages principales de Jeedom, il affiche un compte-rendu de toute votre domotique.
Ce compte-rendu (contrairement aux vues et designs) est auto-généré par Jeedom, et comprend l'ensemble des objets visibles et leurs équipements.

![Dashboard](../images/doc-dashboard-legends.png)

- 1 : Menu principal de Jeedom.
- 2 : Résumé global [Documentation sur les résumés.](/fr_FR/concept/summary).
- 3 : Heure du navigateur, raccourci vers la Timeline.
- 4 : Bouton pour accéder à la documentation de la page en cours.
- 5 : Nom de votre Jeedom, raccourci vers la configuration.
- 6 : Mode édition (Réordonner / redimensionner les tuiles).
- 7 : Filtre par catégories.
- 8 : Objet : Icône, nom et résumé, et ses équipements.
- 9 : Tuile d'un équipement.
- 10 : Widget d'une commande.

> **Tip**
>
> L'ordre d'affichage des objets sur le Dashboard est celui visible dans **Analyse → Résumé domotique**. Vous pouvez sur cette page modifier cet ordre par glisser - déposer.

Pour qu'un équipement apparaisse sur le Dashboard, il doit :
- Être actif.
- Être visible.
- Avoir comme objet parent un objet visible sur le Dashboard.

A la première apparition de l'équipement sur le Dashboard, Jeedom essaye de dimensionner correctement sa tuile pour afficher l'ensemble des commandes et leurs widgets.
Afin de conserver un Dashboard équilibré, vous pouvez passer en mode Édition avec le crayon en haut à droite de la barre de recherche, afin de redimensionner et/ou réordonner les tuiles des équipements.

En passant la souris sur une commande, un repère de couleur apparaît en bas à gauche de la tuile:
- Bleu pour une commande info. Si elle est historisée, un clic dessus ouvre la fenêtre d'historique.
- Orange pour une commande action. Un clic déclenchera donc l'action.

De plus, vous pouvez cliquer sur le titre de la tuile (le nom de l'équipement) pour ouvrir directement la page de configuration de cet équipement.

> **Tip**
>
> Il est possible d’aller directement sur un seul objet de votre domotique, par le menu **Accueil → Dashboard → Nom de l'objet**.
> Cela permet de n’avoir que les équipements qui vous intéressent, et de charger la page plus rapidement.

- Vous avez en haut à gauche une petite icône pour afficher l’arbre des objets au survol.
- Un champ de recherche permet de rechercher un équipement par son nom, sa catégorie, son plugin, un tag, etc.
- L'icône à droite du champ de recherche permet de filtrer les équipements affichés en fonction de leur catégorie. Un clic centre permet de rapidement sélectionner une seule catégorie.
- Tout à droite, un bouton permet de passer en mode édition, pour modifier l’ordre des tuiles (cliquer - déposer sur le widget), ou les redimensionner. Vous pouvez également réorganiser l’ordre des commandes dans une tuile.

- En cliquant sur un résumé d’objet, vous filtrez pour n’afficher que les équipements ayant pour parent cet objet et qui concernent ce résumé d’objet.

- Un clic sur une commande de type information permet d’afficher l’historique de la commande (si elle est historisée).
- Un Ctrl+Clic sur une commande de type information permet d’afficher l’historique de toutes les commandes (historisées) de cette tuile.
- Un clic sur l'information *time* d'une commande action permet d’afficher l’historique de la commande (si elle est historisée).


## Mode édition

En mode édition (*le crayon tout en haut à droite*), vous pouvez changer la taille des tuiles et leur disposition sur le Dashboard.

les icônes refresh des équipements sont remplacées par une icône permettant d'accéder à leur configuration. Cette icône ouvre une fenêtre d'édition comportant les paramètres d'affichage de l'équipement et de ses commandes.

![Mode édition](./images/EditDashboardModal.gif)

Sur chaque objet, à droite de son nom et résumé, deux icônes permettent d'aligner la hauteur de toutes les tuiles de l'objet sur la plus haute ou la moins haute.

## Barre de menu de Jeedom

> **Tip**
>
> - Clic sur l’horloge (barre de menu) : Ouvre la Timeline.
> - Clic sur le nom du Jeedom (barre de menu) : Ouvre Réglages → Système → Configuration.
> - Clic sur le ? (barre de menu) : Ouvre l’aide sur la page en cours.
> - Echap sur un champ de recherche : Vide le champ et annule cette recherche.
