Cette page permet de configurer l'affichage de toute votre domotique de
manière très fine. Celle-ci demande du temps mais sa seule limite est
votre imagination.

Elle est accessible par Acceuil → Design

> **punta**
>
> Il est possible d'aller directement sur un diseño grâce au sous-menu.

> **importante**
>
> Toutes les actions se font par un clic droit sur cette page, attention
> à bien le faire dans le diseño. Lors de la création, il faut donc le
> faire au milieu de la page (pour être sûr d'être sur le diseño).

Dans le menu (clic droit donc), nous retrouvons les
actions suivantes :

-   **Designs** : Affiche la liste de vos diseños et permet d'y accéder

-   **Edition** : Permet de passer en mode édition

-   **Plein ecran** : Permet d'utiliser toute la page Internet, ce qui
    enlèvera le menu de Jeedom du haut

-   **Ajouter graphique** : Permet d'ajouter un graphique

-   **Ajouter texte/html** : Permet d'ajouter du texte ou du code
    html/javascript

-   **Ajouter scénario** : Permet d'ajouter un scénario

-   **Ajouter lien**

    -   **Vers une vue** : Permet d'ajouter un lien vers une vue

    -   **Vers un diseño** : Agregar un enlace a otro
        diseño

-   **Agregar equipo** : Agrega equipo

-   **Añadir pedido** : Agregar un pedido

-   **Añadir imagen / cámara** : Le permite agregar una imagen o transmisión
    de una camara

-   **Agregar área** : Agregar un área transparente en la que se pueda hacer clic
    quién puede ejecutar una serie de acciones con un clic (dependiendo
    o no el estado de otro pedido)

-   **Agregar resumen** : Agrega información de un resumen de objeto o
    general

-   **Viendo**

    -   **no** : No muestra ninguna cuadrícula

    -   **10x10** : Muestra una cuadrícula de 10 por 10

    -   **15x15** : Muestra una cuadrícula de 15 por 15

    -   **20x20** : Muestra una cuadrícula de 20 por 20

    -   **Magnetizar los elementos** : Añadir una magnetización entre
        elementos para que sea más fácil pegarlos

    -   **Imán en la rejilla** : Agregue una magnetización de los elementos a
        la cuadrícula (atención : dependiendo del zoom del artículo esto
        la funcionalidad puede funcionar más o menos)

    -   **Ocultar elemento resaltado** : Ocultarlo
        resaltar alrededor de elementos

-   **Eliminar diseño** : eliminar diseño

-   **Crea un diseño** : permite agregar un nuevo diseño

-   **Diseño duplicado** : duplicar el diseño actual

-   **Configura el diseño** : acceso a la configuración de diseño

-   **Guardar** : permite guardar el diseño (atención, hay
    también copias de seguridad automáticas durante ciertas acciones)

> **importante**
>
> La configuración de los elementos de diseño se realiza haciendo clic en
> estos.

Configuración de diseño 
=======================

Encontrado aquí :

-   **general**

    -   **apellido** : El nombre de su diseño

    -   **Fondo transparente** : hace que el fondo sea transparente. Tenga cuidado si el
        la casilla está marcada, no se utiliza el color de fondo

    -   **Color de fondo** : diseño de color de fondo (blanco
        por defecto)

    -   **código** : Código de acceso a su diseño (si está vacío, sin código
        no es solicitado)

    -   **icono** : Un icono para ello (aparece en el menú
        elección de diseño)

    -   **imagen**

        -   **enviar a** : permite agregar una imagen de fondo al diseño

        -   **Eliminar imagen** : borrar imagen

-   **tamaños**

    -   **Tamaño (WxH)** : Le permite fijar el tamaño de su diseño.
        (marco gris en modo edición)

Configuración general de elementos. 
===================================

> **nota**
>
> Dependiendo del tipo de elemento, las opciones pueden cambiar.

> **nota**
>
> El elemento seleccionado se resalta en rojo (en lugar de verde
> para todos los demás).

Ajuste de la pantalla 
---------------------

-   **profundidad** : permite elegir el nivel de profundidad

-   **Posición X (%)** :

-   **Posición Y (%)** :

-   **Ancho (px)** :

-   **Altura (px)** :

remove 
---------

Eliminar elemento

duplicado 
---------

Le permite duplicar el elemento.

cerradura 
-----------

Bloquea el elemento para que ya no sea móvil o
de tamaño variable.

gráfico 
=========

Configuraciones de pantalla 
---------------------

-   **período** : le permite elegir el período de visualización

-   **Mostrar subtítulo** : mostrar leyenda

-   **Mostrar navegador** : mostrar el navegador (segundo gráfico
    más claro debajo del primero)

-   **Afficher le sélecteur de période** : affiche le sélecteur de
    période en haut à gauche

-   **Afficher la barre de défilement** : affiche la barre de défilement

-   **Fondo transparente** : hace que el fondo sea transparente

-   **Bordure** : permet d'ajouter une bordure, attention la syntaxe est
    HTML (attention, il faut utiliser une syntaxe CSS, par exemple :
    solid 1px black)

Configuration avancée 
---------------------

Permet de choisir les commandes à grapher

Text/html 
=========

-   **icono** : icono à afficher devant

-   **Color de fondo** : permet de changer la couleur de fond ou de le
    mettre transparent, ne pas oublier de passer "Défaut" sur NON

-   **Couleur du texte** : permet de changer la couleur des icônes et
    des textes (attention à bien passer Défaut sur Non)

-   **Arrondir les angles** : permet d'arrondir les angles (ne pas
    oublier de mettre %, ex 50%)

-   **Bordure** : permet d'ajouter une bordure, attention la syntaxe est
    HTML (il faut utiliser une syntaxe CSS, par exemple : solid
    1px black)

-   **Taille de la police** : permet de modifier la taille de la police
    (ex 50%, il faut bien mettre le signe %)

-   **Alignement du texte** : permet de choisir l'alignement du
    texte (gauche/droit/centré)

-   **Gras** : met le texte en gras

-   **texto** : texto au code HTML qui sera dans l'élément

> **importante**
>
> Si vous mettez du code HTML (en particulier du Javascript), attention
> à bien le vérifier avant car vous pouvez si il y a une erreur dedans
> ou si il écrase un composant Jeedom planter complètement le diseño et
> il ne restera plus qu'à le supprimer directement en base de données

guión 
========

Configuraciones de pantalla 
---------------------

Aucun paramètre spécifique d'affichage

Lien 
====

Configuraciones de pantalla 
---------------------

-   **apellido** : apellido du lien (texte affiché)

-   **Lien** : Lien vers le diseño ou la vue en question

-   **Color de fondo** : permet de changer la couleur de fond ou de le
    mettre transparent, ne pas oublier de passer "Défaut" sur NON

-   **Couleur du texte** : permet de changer la couleur des icônes et
    des textes (attention à bien passer Défaut sur Non)

-   **Arrondir les angles (ne pas oublier de mettre %, ex 50%)** :
    permet d'arrondir les angles, ne pas oublier de mettre le %

-   **Bordure (attention syntaxe CSS, ex : solid 1px black)** : permet
    d'ajouter une bordure, attention la syntaxe est HTML

-   **Taille de la police (ex 50%, il faut bien mettre le signe %)** :
    permet de modifier la taille de la police

-   **Alignement du texte** : permet de choisir l'alignement du
    texte (gauche/droit/centré)

-   **Gras** : met le texte en gras

equipo 
==========

Configuraciones de pantalla 
---------------------

Aucun paramètre spécifique d'affichage

Configuration avancée 
---------------------

Affiche la fenêtre de configuration avancée de l'équipement (voir
documentation Résumé domotique ("display"))

orden 
========

Configuraciones de pantalla 
---------------------

Aucun paramètre spécifique d'affichage

Configuration avancée 
---------------------

Affiche la fenêtre de configuration avancée de la commande (voir
documentation Résumé domotique ("display"))

imagen/Caméra 
============

Configuraciones de pantalla 
---------------------

-   **Afficher** : définit ce que vous voulez afficher, image fixe ou
    flux de una camara

-   **imagen** : permet d'envoyer l'image en question (si vous avez
    choisi une image)

-   **Caméra** : caméra à afficher (si vous avez choisi caméra)

Zone 
====

Configuraciones de pantalla 
---------------------

-   **Type de zone** : C'est ici que vous choisissez le type de la zone :
    Macro simple, Macro Binaire ou Widget au survol

### Macro simple 

Dans ce mode là, un clic sur la zone exécute une ou plusieurs actions.

Il vous suffit ici d'indiquer la liste des actions à faire lors du clic
sur la zone

### Macro binaire 

Dans ce mode, Jeedom va exécuter la ou les actions On ou Off en
fonction de l'état de la commande que vous donnez. Ex : si la commande
vaut 0 alors Jeedom exécutera la ou les actions On sinon il exécutera
la ou les actions Off

-   **Information binaire** : orden donnant l'état à vérifier pour
    décider de l'action à faire (On ou Off)

Il vous suffit en dessous de mettre les actions à faire pour le On et
pour le Off

### Widget au survol 

Dans ce mode, lors du survol ou du clic dans la zone Jeedom, vous
afficherez le widget en question

-   **equipo** : widget à afficher lors du survol ou du clic

-   **Afficher au survol** : si coché, affiche le widget au survol

-   **Afficher sur un clic** : si coché, alors le widget est affiché au
    clic

-   **Position** : permet de choisir l'emplacement d'apparition du
    widget (par défaut bas droite)

Résumé 
======

-   **Lien** : Permet d'indiquer le résumé à afficher (general pour le
    global sinon indiquer l'objet)

-   **Color de fondo** : permet de changer la couleur de fond ou de le
    mettre transparent, ne pas oublier de passer "Défaut" sur NON

-   **Couleur du texte** : permet de changer la couleur des icônes et
    des textes (attention à bien passer Défaut sur Non)

-   **Arrondir les angles (ne pas oublier de mettre %, ex 50%)** :
    permet d'arrondir les angles, ne pas oublier de mettre le %

-   **Bordure (attention syntaxe CSS, ex : solid 1px black)** : permet
    d'ajouter une bordure, attention la syntaxe est HTML

-   **Taille de la police (ex 50%, il faut bien mettre le signe %)** :
    permet de modifier la taille de la police

-   **Gras** : met le texte en gras


FAQ 
======

>**Je n'arrive plus à éditer mon diseño**
>
>Si vous avez mis un widget ou une image qui prend quasiment la totalité du diseño, il faut bien cliquer en dehors du widget ou de l'image pour avoir accès au menu par clic droit.

>**remove un diseño qui ne marche plus**
>
>Dans la partie administration puis OS/DB, faire "select * from planHeader", récuperer l'id du diseño en question et faire un "delete from planHeader where id=#TODO#" et "delete from plan where planHeader_id=#todo#" en remplaçant bien #TODO# par l'id du diseño trouvé précedemment.
