Esta página le permite configurar la visualización de todo su sistema domótico de
manera más personal. Esto lleva tiempo pero su único límite es
su imaginación.

Elle est accessible par Acceuil → Design

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

-   **Agregar zona** : Le permite agregar un área transparente seleccionable
    que será capaz de ejecutar una serie de acciones al hacer clic (según
    o no del status de otra comando)

-   **Agregar resumen** : Agrega información a un resumen de objeto o
    general

-   **Visualizar**

    -   **Ninguno**: No muestra ninguna grilla

    -   **10x10** : Muestra una cuadrícula de 10 por 10

    -   **15x15**: Muestra una cuadrícula de 15 por 15

    -   **20x20**: Muestra una cuadrícula de 20 por 20

    -   **Magnetizar elementos**: Agrega magnetización entre 
        elementos para que sea más fácil colocarlos

    -   **Imán en la cuadrícula**: Agrega una magnetización de los elementos a
        la cuadrícula (atención: dependiendo del zoom del elemento, esta
        característica puede funcionar mejor o peor)

    -   **Ocultar resaltados**: Ocultar el
        resaltado alrededor de los elementos

-   **Eliminar diseño**: Elimina el diseño

-   **Crear un diseño**: Agregar un diseño

-   **Duplicar design**: Duplica el diseño actual

-   **Configurar el diseño**: Acceso a la configuración del diseño

-   **Guardar**: Guarde el diseño (tenga en cuenta que algunas
    acciones se guardan automáticamente)

> **Importante**
>
> La configuración de los elementos de diseño se realiza haciendo clic en
> estos.

Configuración del diseño
=======================

Encontrará aquí:

-   **General**

    -   **Nombre**: El nombre de su diseño

    -   **Fondo transparente**: Hace que el fondo sea transparente. Si la casilla
        de verificación está marcada, el color de fondo no se utilizará

    -   **Color de fondo**: Color de fondo del diseño (blanco
        por defecto)

    -   **Código**: Código de acceso a su diseño (si está vacío, no se pedirá
        ningún codigo)

    -   **Icono**: Icono del diseño (aparece en el menú de
        elección de diseño)

    -   **Imagen**

        -   **Enviar**: Agrega una imagen de fondo al diseño

        -   **Eliminar imagen**: Borrar la imagen

-   **Tamaños**

    -   **Tamaño (An x Al)**: Le permite configurar el tamaño de su diseño
        (marco gris en modo de edición)

Configuración general de elementos
===================================

> **Nota**
>
> Según el tipo de artículo, las opciones pueden cambiar.

> **Nota**
>
> El elemento seleccionado se resalta en rojo (todos los demás
> en verde).

Configuración de pantalla
---------------------

-   **Profundidad**: Permite elegir el nivel de profundidad

-   **Posición X (%)**:

-   **Posición Y (%)**:

-   **Ancho (px)**:

-   **Alto (px)**:

Eliminar
---------

Eliminar el elemento

Duplicar
---------

Duplicar el elemento

Bloquear
-----------

Bloquea el elemento para que ya no se pueda mover o
redimensionar.

Gráfico
=========

Configuración de muestra
---------------------

-   **Período**: Le permite elegir el período de muestra

-   **Mostrar leyenda**: Muestra la leyenda

-   **Mostrar navegador**: Muestra el navegador (segundo gráfico
    más ligero debajo del primero)

-   **Mostrar selector de período**: Muestra el
    período en la esquina superior izquierda

-   **Mostrar barra de desplazamiento**: Muestra barra de desplazamiento

-   **Fondo transparente**: Hace que el fondo sea transparente

-   **Borde**: Permite agregar un borde, tenga cuidado, la sintaxis es
    HTML (debe usar una sintaxis CSS, por ejemplo:
    solid 1px black)

Configuración avanzada
---------------------

Le permite elegir los comandos a mostrar

Texto / html
=========

-   **Icono**: Icono para mostrar en frente

-   **Color de fondo**: Le permite cambiar el color de fondo o
    dejarlo transparente, no olvide dejar "Predeterminado" en NO

-   **Color del texto**: Cambie el color de los iconos y
    los textos (debe dejar el valor predeterminado en No)

-   **Redondeo de esquinas**: Redondear las esquinas (no
    olvide poner %, ej: 50%)

-   **Borde**: Permite agregar un borde, tenga cuidado, la sintaxis es
    HTML (debe usar una sintaxis CSS, por ejemplo: solid
    1px black)

-   **Tamaño de fuente**: Le permite cambiar el tamaño de la fuente
    (es necesario poner el signo %, ej: 50%)

-   **Alineación de texto**: Permite elegir la alineación del
    texto (izquierda / derecha / centrado)

-   **Negrita**: Pone el texto en negrita

-   **Texto**: Texto en código HTML que estará en el elemento

> **Important**
>
> Si vous mettez du code HTML (en particulier du Javascript), attention
> à bien le vérifier avant car vous pouvez si il y a une erreur dedans
> ou si il écrase un composant Jeedom planter completement le design et
> il ne restera plus qu’a le supprimer directement base de données

Escenario
========

Configuración de pantalla
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