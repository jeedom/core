# Design
**Accueil → Design**

Cette page permet de configurer l’affichage de toute votre domotique de manière très fine.
Celle-ci demande du temps mais sa seule limite est votre imagination.

> **Tip**
>
> Il est possible d’aller directement sur un design grâce au sous-menu.

> **Important**
>
> Toutes les actions se font par un clic droit sur cette page, attention à bien le faire dans le design. Lors de la création, il faut donc le faire au milieu de la page (pour être sûr d’être sur le design).

Dans le menu (clic droit), nous retrouvons les actions suivantes :

- **Designs** : Affiche la liste de vos designs et permet d’y accéder.
- **Edition** : Permet de passer en mode édition.
- **Plein écran** : Permet d’utiliser toute la page Internet, ce qui enlèvera le menu de Jeedom du haut.
- **Ajouter graphique** : Permet d’ajouter un graphique.
- **Ajouter texte/html** : Permet d’ajouter du texte ou du code html/JavaScript.
- **Ajouter scénario** : Permet d’ajouter un scénario.
- **Ajouter lien**
    - **Vers une vue** : Permet d’ajouter un lien vers une vue.
    - **Vers un design** : Permet d’ajouter un lien vers un autre design.
- **Ajouter équipement** : Permet d’ajouter un équipement.
- **Ajouter commande** : Permet d’ajouter une commande.
- **Ajouter image/caméra** : Permet d’ajouter une image ou le flux d’une caméra.
- **Ajouter zone** : Permet d’ajouter une zone transparente cliquable qui pourra exécuter une série d’actions lors d’un clic (en fonction ou non de l’état d’une autre commande).
- **Ajouter résumé** : Ajoute les informations d’un résumé d’objet ou général.
- **Affichage**
    - **Aucune** : N’affiche aucune grille.
    - **10x10** : Affiche une grille de 10 par 10.
    - **15x15** : Affiche une grille de 15 par 15.
    - **20x20** : Affiche une grille de 20 par 20.
    - **Aimanter les éléments** : Ajoute une aimantation entre les éléments pour permettre de les coller plus facilement.
    - **Aimanter à la grille** : Ajoute une aimantation des éléments à la grille (attention : en fonction du zoom de l’élément cette fonctionnalité peut plus ou moins fonctionner).
    - **Masquer la surbrillance des éléments** : Masque la surbrillance autour des éléments.
- **Supprimer le design** : Supprime le design.
- **Créer un design** : Permet d’ajouter un nouveau design.
- **Dupliquer le design** : Duplique le design courant.
- **Configurer le design** : Accès à la configuration du design.
- **Sauvegarder** : Permet de sauvegarder le design (attention, il y a aussi des sauvegardes automatiques lors de certaines actions).

> **Important**
>
> La configuration des éléments du design se fait par un clic sur ceux-ci.

## Configuration du design

On retrouve ici :

- **Général**
    - **Nom** : Le nom de votre design.
    - **Position** : La position du design dans le menu. Permet d'ordonner les designs.
    - **Fond transparent** : Rend le fond transparent. Attention si la case est cochée, la couleur de fond n’est pas utilisée.
    - **Couleur de fond** : Couleur de fond du design.
    - **Code d'accès* : Code d’accès à votre design (si vide, aucun code n’est demandé).
    - **Icône** : Une icône pour celui-ci (apparaît dans le menu de choix de design).
    - **Image**
        - **Envoyer** : Permet d’ajouter une image de fond au design.
        - **Supprimer l’image** : Permet de supprimer l’image.
- **Tailles**
    - **Taille (LxH)** : Permet de fixer la taille en pixels de votre design.

## Configuration générale des éléments

> **Note**
>
> En fonction du type de l’élément, les options peuvent changer.

### Paramètres d’affichage communs

- **Profondeur** : Permet de choisir le niveau de la profondeur
- **Position X (%)** : Coordonnée horizontale de l'élément.
- **Position Y (%)** : Coordonnée verticale de l'élément.
- **Largeur (px)** : Largeur de l'élément en pixels.
- **Hauteur (px)** : Hauteur de l'élément en pixels.

### Supprimer

Permet de supprimer l’élément

### Dupliquer

Permet de dupliquer l’élément

### Verrouiller

Permet de verrouiller l’élément pour qu’il ne soit plus déplaçable ou redimensionnable.

## Graphique

### Paramètres d’affichage spécifiques

- **Période** : Permet de choisir la période d’affichage
- **Afficher la légende** : Affiche la légende.
- **Afficher le navigateur** : Affiche le navigateur (deuxième graph plus léger en dessous du premier).
- **Afficher le sélecteur de période** : Affiche le sélecteur de période en haut à gauche.
- **Afficher la barre de défilement** : Affiche la barre de défilement.
- **Fond transparent** : Rend le fond transparent.
- **Bordure** : Permet d’ajouter une bordure, attention la syntaxe est HTML (attention, il faut utiliser une syntaxe CSS, par exemple : solid 1px black).

### Configuration avancée

Permet de choisir les commandes à grapher.

## Text/html

### Paramètres d’affichage spécifiques

- **Icône** : Icône affichée devant le nom du Design.
- **Couleur de fond** : permet de changer la couleur de fond ou de le mettre transparent, ne pas oublier de passer "Défaut" sur NON.
- **Couleur du texte** : permet de changer la couleur des icônes et des textes (attention à bien passer Défaut sur Non)..
- **Arrondir les angles** : permet d’arrondir les angles (ne pas oublier de mettre %, ex 50%).
- **Bordure** : permet d’ajouter une bordure, attention la syntaxe est HTML (il faut utiliser une syntaxe CSS, par exemple : solid 1px black).
- **Taille de la police** : permet de modifier la taille de la police (ex 50%, il faut bien mettre le signe %).
- **Alignement du texte** : permet de choisir l’alignement du texte (gauche/droit/centré).
- **Gras** : met le texte en gras.
- **Texte** : Texte au code HTML qui sera dans l’élément.

> **Important**
>
> Si vous mettez du code HTML (en particulier du Javascript), attention à bien le vérifier avant car vous pouvez si il y a une erreur dedans ou si il écrase un composant Jeedom planter complètement le design et il ne restera plus qu’à le supprimer directement en base de données.

## Scénario

*Aucun paramètre spécifique d’affichage*

## Lien

### Paramètres d’affichage spécifiques

- **Nom** : Nom du lien (texte affiché).
- **Lien** : Lien vers le design ou la vue en question.
- **Couleur de fond** : Permet de changer la couleur de fond ou de le mettre transparent, ne pas oublier de passer "Défaut" sur NON.
- **Couleur du texte** : Permet de changer la couleur des icônes et des textes (attention à bien passer Défaut sur Non).
- **Arrondir les angles (ne pas oublier de mettre %, ex 50%)** : Permet d’arrondir les angles, ne pas oublier de mettre le %.
- **Bordure (attention syntaxe CSS, ex : solid 1px black)** : Permet d’ajouter une bordure, attention la syntaxe est HTML.
- **Taille de la police (ex 50%, il faut bien mettre le signe %)** : Permet de modifier la taille de la police.
- **Alignement du texte** : Permet de choisir l’alignement du texte (gauche/droit/centré).
- **Gras** : Met le texte en gras.

## Equipement

### Paramètres d’affichage spécifiques

- **Afficher le nom de l'objet** : Cocher pour afficher le nom de l'objet parent de l'équipement.
- **Masquer le nom** : Cocher pour masquer le nom de l'équipement.
- **Couleur de fond** : Permet de choisir une couleur de fond personnalisée, ou d'afficher l'équipement avec un fond transparent, ou d'utiliser la couleur par défaut.
- **Couleur du texte** : Permet de choisir une couleur de fond personnalisée, ou d'utiliser la couleur par défaut.
- **Arrondis** : Valeur en pixels de l'arrondis des angles de la tuile de l'équipement.
- **Bordure** : Définition CSS d'une bordure de la tuile de l'équipement. Ex : 1px solid black.
- **Opacité** : Opacité de la tuile de l'équipement, entre 0 et 1. Attention : une couleur de fond doit être définie.
- **CSS personnalisé** : Règles CSS à appliquer sur l'équipement.
- **Appliquer le css personnalisé sur** : Sélecteur CSS sur lequel appliquer le CSS personnalisé.

### Commandes

La liste des commandes présentes sur l'équipement vous permet, pour chaque commande, de:
- Masquer le nom de la commande.
- Masquer la commande.
- Afficher la commande avec une fond transparent.

### Configuration avancée

Affiche la fenêtre de configuration avancée de l’équipement (voir documentation **Résumé domotique**).

## Commande

*Aucun paramètre spécifique d’affichage*

### Configuration avancée

Affiche la fenêtre de configuration avancée de l’équipement (voir documentation **Résumé domotique**).

## Image/Caméra

### Paramètres d’affichage spécifiques

- **Afficher** : Définit ce que vous voulez afficher, image fixe ou flux d’une caméra.
- **Image** : Permet d’envoyer l’image en question (si vous avez choisi une image).
- **Caméra** : Caméra à afficher (si vous avez choisi caméra).

## Zone

### Paramètres d’affichage spécifiques

- **Type de zone** : C’est ici que vous choisissez le type de la zone : Macro simple, Macro Binaire ou Widget au survol.

### Macro simple

Dans ce mode là, un clic sur la zone exécute une ou plusieurs actions. Il vous suffit ici d’indiquer la liste des actions à faire lors du clic sur la zone.

### Macro binaire

Dans ce mode, Jeedom va exécuter la ou les actions On ou Off en fonction de l’état de la commande que vous indiquez. Ex : si la commande vaut 0 alors Jeedom exécutera la ou les actions On sinon il exécutera la ou les actions Off

- **Information binaire** : Commande donnant l’état à vérifier pour décider de l’action à faire (On ou Off).

Il vous suffit en dessous de mettre les actions à faire pour le On et pour le Off.

### Widget au survol

Dans ce mode, lors du survol ou du clic dans la zone Jeedom, vous afficherez le widget en question.

- **Equipement** : Widget à afficher lors du survol ou du clic.
- **Afficher au survol** : Si coché, affiche le widget au survol.
- **Afficher sur un clic** : Si coché, alors le widget est affiché au clic.
- **Position** : Permet de choisir l’emplacement d’apparition du widget (par défaut bas droite).

## Résumé

### Paramètres d’affichage spécifiques

- **Lien** : Permet d’indiquer le résumé à afficher (Général pour le global sinon indiquer l’objet).
- **Couleur de fond** : Permet de changer la couleur de fond ou de le mettre transparent, ne pas oublier de passer "Défaut" sur NON.
- **Couleur du texte** : Permet de changer la couleur des icônes et des textes (attention à bien passer Défaut sur Non).
- **Arrondir les angles (ne pas oublier de mettre %, ex 50%)** : Permet d’arrondir les angles, ne pas oublier de mettre le %.
- **Bordure (attention syntaxe CSS, ex : solid 1px black)** : Permet d’ajouter une bordure, attention la syntaxe est HTML.
- **Taille de la police (ex 50%, il faut bien mettre le signe %)** : Permet de modifier la taille de la police.
- **Gras** : Met le texte en gras.

## FAQ

>**Je n'arrive plus à éditer mon design**
>Si vous avez mis un widget ou une image qui prend quasiment la totalité du design, il faut bien cliquer en dehors du widget ou de l'image pour avoir accès au menu par clic droit.

>**Supprimer un design qui ne marche plus**
>Dans la partie administration puis OS/DB, faire "select * from planHeader", récupérer l'id du design en question et faire un "delete from planHeader where id=#TODO#" et "delete from plan where planHeader_id=#todo#" en remplaçant bien #TODO# par l'id du design trouvé précédemment.
