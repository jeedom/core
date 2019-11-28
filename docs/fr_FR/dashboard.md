# Dashboard
**Accueil → Dashboard**

<small>[Raccourcis clavier/souris](shortcuts.md)</small>

Une des pages principales de Jeedom affiche un compte-rendu de toute votre domotique.
Ce compte-rendu (contrairement aux vues et designs) est auto-généré par Jeedom, et comprend l'ensemble des objets visibles et leurs équipements.

> **Tip**
>
> L'ordre d'affichage des objets sur le Dashboard est celui visible dans **Analyse → Résumé domotique**. Vous pouvez sur cette page modifier cet ordre par glissé - déposé.

Pour qu'un équipement apparaisse sur le Dashboard, il doit :
- Être actif.
- Être visible.
- Avoir comme objet parent un objet visible sur le Dashboard.

A la première apparition de l'équipement sur le Dashboard, Jeedom essaye de dimensionner correctement sa tuile pour afficher l'ensemble des commandes et leurs widgets.
Afin de conserver un Dashboard équilibré, vous pouvez passer en mode Édition avec le crayon en haut à droite de la barre de recherche, afin de redimensionner et/ou réordonner les tuiles des équipements.

En passant la souris sur une commande, un repère de couleur appairait en bas à gauche de la tuile:
- Bleu pour une commande info. Si elle est historisée, un clic dessus ouvre la fenêtre d'historique.
- Orange pour une commande action. Un clic déclenchera donc l'action.

De plus, vous pouvez cliquer sur le titre de la tuile (le nom de l'équipement) pour ouvrir directement la page de configuration de cet équipement.

> **Tip**
>
> Il est possible d’aller directement sur un seul objet de votre domotique, par le menu **Accueil → Dashboard → Nom de l'objet**.
> Cela permet de n’avoir que les équipements qui vous intéressent, et de charger la page plus rapidement.

- Vous avez en haut à gauche une petite icône pour faire afficher/masquer l’arbre des objets.
- La deuxième icône à gauche permet d'afficher seulement les informations des résumés des objets.
- Au milieu, un champ de recherche permet de rechercher un équipement par son nom, sa catégorie, son plugin, un tag, etc.
- A droite, un bouton permet de passer en mode édition, pour modifier l’ordre des tuiles (cliqué - déposé sur le widget), ou les redimensionner. Vous pouvez également dans une tuile, réorganiser l’ordre des commandes.
- En cliquant sur un résumé d’objet, vous filtrez pour n’afficher que les équipements ayant pour parent cet objet et qui concernent ce résumé d’objet.
- Un clic sur une commande de type information permet d’afficher l’historique de la commande (si elle est historisée).

> **Tip**
>
> Il est possible, à partir de votre profil, de configurer Jeedom pour que l’arbre des objets et/ou les scénarios soient visibles par défaut lorsque vous arrivez sur le Dashboard.

> **Tip**
>
> En mobile, un appui sur une commande de type info permet d’afficher un menu vous proposant soit d’afficher l’historique de la commande, ou de mettre une alerte sur celle-ci pour que Jeedom vous prévienne (une seule fois) dès que la valeur passe un certain seuil.


## Mode édition

En mode édition (*le crayon tout en haut à droite*), vous pouvez changer la taille des tuiles et leur disposition sur le Dashboard.

Vous pouvez aussi éditer la disposition interne des commandes sur la tuile :

- Soit les réorganiser par glissé - déposé.
- Soit en faisant un clic droit sur le widget. Vous accédez alors à :
    - **Configuration avancée** : permet d’accéder à la configuration avancée de la commande.
    - **Standard** : disposition par défaut, tout est en automatique avec juste la possibilité de réorganiser l’ordre des commandes.
    - **Tableau** : permet de mettre les commandes dans un tableau : les colonnes et les lignes s’ajoutent et se suppriment par clic droit, ensuite il suffit de déplacer les commandes dans les cases voulues. Vous pouvez mettre plusieurs commandes par case
    - **Ajouter colonne** : ajoute une colonne au tableau (accessible uniquement si vous êtes en disposition tableau)
    - **Ajouter ligne** : ajoute une ligne au tableau (accessible uniquement si vous êtes en disposition tableau)
    - **Supprimer colonne** : supprime une colonne au tableau (accessible uniquement si vous êtes en disposition tableau)
    - **Supprimer ligne** : supprime une ligne au tableau (accessible uniquement si vous êtes en disposition tableau)

A droite de chaque objet, une icône permet de :

- Clic : Toutes les tuiles de cet objet adopterons une hauteur égale à la tuile la plus haute.
- Ctrl Clic : Toutes les tuiles de cet objet adopterons une hauteur égale à la tuile la moins haute.

## Barre de menu de Jeedom

> **Tip**
>
> - Clic sur l’horloge (barre de menu) : Ouvre la Timeline.
> - Clic sur le nom du Jeedom (barre de menu) : Ouvre Réglages → Système → Configuration.
> - Clic sur le ? (barre de menu) : Ouvre l’aide sur le page en cours.
> - Echap sur un champ de recherche : Vide le champ de recherche et annule celle-ci.
