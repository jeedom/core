Esta página le permite configurar la visualización de todo su sistema domótico de
manera más personal. Esto lleva tiempo pero su único límite es
su imaginación.

Es accesible a través de Inicio→ Dashboard

> **Consejo**
>
> Puede ir directamente a un diseño a través del submenú.

> **Importante**
>
> Todas las acciones se realizan con un clic derecho en esta página, atención
> al hacer bien el diseño. Por lo tanto, al crear, es necesario de
> hacer en el centro de la página (para asegurarse de estar en el diseño).

En el menú encontramos (clic derecho por lo tanto) la ópciones
siguientes :

-   **Diseños** : Muestra una lista de sus diseños y accede a ellos

-   **Edición** : Cambiar al modo de edición

-   **Pantalla completa** : Le permite utilizar toda la página de navegación, que
    eliminará el menú de Jeedom de la parte superior

-   **Agregar gráfico** : Le permite agregar un gráfico

-   **Añadir texto/html** : Añadir texto o código
    html/javascript

-   **Agregar escenario** : Le permite agregar un escenario

-   **Añadir enlace**

    -   **A una vista** : Añadir un enlace a una vista

    -   **A un diseño** : Permite añadir un enlace a otro diseño
        diseño

-   **Agregar dispositivo** : Le permite agregar dispositivo

-   **Agregar comando** : Le permite agregar un comando

-   **Agregar imagen/cámara** : Le permite agregar una imagen o video
    de una cámara

-   **Ajouter zone** : Permet d’ajouter une zone transparante cliquable
    que será capaz de ejecutar una serie de acciones al hacer clic (según
    o no del status de otra comando)

-   **Agregar resumen** : Agrega información a un resumen de objeto o
    general

-   **Visualizar**

    -   **Aucune** : N’affiche aucune grille

    -   **10x10** : Affiche une grille de 10 par 10

    -   **15x15** : Affiche une grille de 15 par 15

    -   **20x20** : Affiche une grille de 20 par 20

    -   **Aimanter les éléments** : Ajoute une aimantation entre les
        éléments pour permettre de les coller plus facilement

    -   **Aimanter à la grille** : Ajoute une aimantation des éléments à
        la grille (attention : en fonction du zoom de l’élément cette
        fonctionalité peut plus ou moins fonctionner)

    -   **Masquer la surbrillance des éléments** : Masque la
        surbrillance autour des éléments

-   **Supprimer le design** : supprime le design

-   **Créer un design** : permet d’ajouter un design

-   **Dupliquer le design** : duplique le design courant

-   **Configurer le design** : accès à la configuration du design

-   **Sauvegarder** : permet de sauvegarder le design (attention il y a
    aussi des sauvegardes automatiques lors de certaines actions)

> **Important**
>
> La configuration des éléments du désign se fait par un clic sur
> ceux-ci.

Configuration du design 
=======================

On retrouve ici :

-   **Général**

    -   **Nom** : Le nom de votre design

    -   **Fond transparent** : rend le fond transparent. Attention si la
        case est coché la couleur de fond n’est pas utilisée

    -   **Couleur de fond** : couleur de fond du design (blanc
        par défaut)

    -   **Code** : Code d’accès à votre design (si vide aucun code
        n’est demandé)

    -   **Icône** : Une icône pour celui-ci (apparait dans le menu de
        choix de design)

    -   **Image**

        -   **Envoyer** : permet d’ajouter une image de fond au design

        -   **Supprimer l’image** : permet de supprimer l’image

-   **Tailles**

    -   **Taille (LxH)** : Permet de fixer la taille de votre design
        (cadre gris en mode édition)

Configuration générale des éléments 
===================================

> **Note**
>
> En fonction du type de l’élément, les options peuvent changer.

> **Note**
>
> L’élément sélectionné apparait en surbrillance rouge (au lieu de vert
> pour tous les autres).

Paramètre d’affichage 
---------------------

-   **Profondeur** : permet de choisir le niveau de la profondeur

-   **Position X (%)** :

-   **Position Y (%)** :

-   **Largeur (px)** :

-   **Hauteur (px)** :

Supprimer 
---------

Permet de supprimer l’élément

Dupliquer 
---------

Permet de dupliquer l’élément

Verrouiller 
-----------

Permet de verrouiller l’élément pour qu’il ne soit plus déplaçable ou
redimmensionnable.

Graphique 
=========

Paramètre d’affichage 
---------------------

-   **Période** : permet de choisir la période d’affichage

-   **Afficher la légende** : affiche la légende

-   **Afficher le navigateur** : affiche le navigateur (deuxième graph
    plus léger en dessous du premier)

-   **Afficher le sélecteur de période** : affiche le sélecteur de
    période en haut à gauche

-   **Afficher la barre de défilement** : affiche la barre de défilement

-   **Fond transparent** : rend le fond transparent

-   **Bordure** : permet d’ajouter une bordure, attention la syntaxe est
    HTML (attention, il faut utiliser une syntaxe CSS, par exemple :
    solid 1px black)

Configuration avancée 
---------------------

Permet de choisir les commandes à grapher

Text/html 
=========

-   **Icone** : Icone à afficher devant

-   **Couleur de fond** : permet de changer la couleur de fond ou de le
    mettre transparent, ne pas oublier de passer "Défaut" sur NON

-   **Couleur du texte** : permet de changer la couleur des icônes et
    des textes (attention à bien passer Défaut sur Non)

-   **Arrondir les angles** : permet d’arrondir les angles (ne pas
    oublier de mettre %, ex 50%)

-   **Bordure** : permet d’ajouter une bordure, attention la syntaxe est
    HTML (il faut utiliser une syntaxe CSS, par exemple : solid
    1px black)

-   **Taille de la police** : permet de modifier la taille de la police
    (ex 50%, il faut bien mettre le signe %)

-   **Alignement du texte** : permet de choisir l’alignement du
    texte (gauche/droit/centré)

-   **Gras** : met le texte en gras

-   **Texte** : Texte au code HTML qui sera dans l’élément

> **Important**
>
> Si vous mettez du code HTML (en particulier du Javascript), attention
> à bien le vérifier avant car vous pouvez si il y a une erreur dedans
> ou si il écrase un composant Jeedom planter completement le design et
> il ne restera plus qu’a le supprimer directement base de données

Scénario 
========

Paramètre d’affichage 
---------------------

Aucun paramètre spécifique d’affichage

Lien 
====

Paramètre d’affichage 
---------------------

-   **Nom** : Nom du lien (texte affiché)

-   **Lien** : Lien vers le design ou la vue en question

-   **Couleur de fond** : permet de changer la couleur de fond ou de le
    mettre transparent, ne pas oublier de passer "Défaut" sur NON

-   **Couleur du texte** : permet de changer la couleur des icônes et
    des textes (attention à bien passer Défaut sur Non)

-   **Arrondir les angles (ne pas oublier de mettre %, ex 50%)** :
    permet d’arrondir les angles, ne pas oublier de mettre le %

-   **Bordure (attention syntaxe CSS, ex : solid 1px black)** : permet
    d’ajouter une bordure, attention la syntaxe est HTML

-   **Taille de la police (ex 50%, il faut bien mettre le signe %)** :
    permet de modifier la taille de la police

-   **Alignement du texte** : permet de choisir l’alignement du
    texte (gauche/droit/centré)

-   **Gras** : met le texte en gras

Equipement 
==========

Paramètre d’affichage 
---------------------

Aucun paramètre spécifique d’affichage

Configuration avancée 
---------------------

Affiche la fenetre de configuration avancé de l’équipement (voir
documentation Résumé domotique ("display"))

Commande 
========

Paramètre d’affichage 
---------------------

Aucun paramètre spécifique d’affichage

Configuration avancée 
---------------------

Affiche la fenetre de configuration avancé de la commande (voir
documentation Résumé domotique ("display"))

Image/Caméra 
============

Paramètre d’affichage 
---------------------

-   **Afficher** : défini ceux que vous voulez afficher, image fixe ou
    flux d’une caméra

-   **Image** : permet d’envoyer l’image en question (si vous avez
    choisi une image)

-   **Caméra** : caméra à afficher (si vous avez choisi caméra)

Zone 
====

Paramètre d’affichage 
---------------------

-   **Type de zone** : C’est ici que vous choissez le type de la zone :
    Macro simple, Macro Binaire ou Widget au survol

### Macro simple 

Dans ce mode la un clic sur la zone execute une ou plusieurs action.

Il vous suffit ici d’indiquer la liste des actions à faire lors du clic
sur la zone

### Macro binaire 

Dans ce mode Jeedom va executer la ou les action(s) On ou Off en
fonction de l’état de la commande que vous donnez. Ex si la commande
vaut 0 alors Jeedom executera la ou les action(s) On sinon il executera
la ou les action(s) off

-   **Information binaire** : Commande donnant l’état à verifier pour
    decider de l’action à faire (on ou off)

Il vous suffit en dessous de mettre les actions à faire pour le on et
pour le off

### Widget au survol 

Dans ce mode lors du survole ou du clic dans la zone jeedom vous
affichera le widget en question

-   **Equipement** : widget à afficher lors du survole ou du clic

-   **Afficher au survol** : si coché affiche le widget au survol

-   **Afficher sur un clic** : si coché alors le widget est affiché au
    clic

-   **Position** : permet de choisir l’emplacement d’apparition du
    widget (par defaut bas droite)

Résumé 
======

-   **Lien** : Permet d’indiqué le résumé à afficher (Général pour le
    globale sinon indiquer l’objet)

-   **Couleur de fond** : permet de changer la couleur de fond ou de le
    mettre transparent, ne pas oublier de passer "Défaut" sur NON

-   **Couleur du texte** : permet de changer la couleur des icônes et
    des textes (attention à bien passer Défaut sur Non)

-   **Arrondir les angles (ne pas oublier de mettre %, ex 50%)** :
    permet d’arrondir les angles, ne pas oublier de mettre le %

-   **Bordure (attention syntaxe CSS, ex : solid 1px black)** : permet
    d’ajouter une bordure, attention la syntaxe est HTML

-   **Taille de la police (ex 50%, il faut bien mettre le signe %)** :
    permet de modifier la taille de la police

-   **Gras** : met le texte en gras

FAQ 
======

>**Je n'arrive plus à éditer mon design**
>
>Si vous avais mis un widget ou une image qui prend quasiment la totalité du design il faut bien cliquer en dehors du widget ou de l'image pour avoir accès au menu par clic droit.

>**Supprimer un design qui ne marche plus**
>
>Dans la partie administration puis OS/DB faire "select * from planHeader" recuperer l'id du design en question et faire un "delete from planHeader where id=#TODO#" et "delete from plan where planHeader_id=#todo#" en replacant bien #TODO# par l'id du design trouvé précedement.
