Esta página le permite configurar la visualización de todo su sistema domótico de
manera más personal. Esto lleva tiempo pero su único límite es
su imaginación.

Es accesible a través de Inicio → Diseño

> **Consejo**
>
> Puede ir directamente a un diseño a través del submenú.

> **Importante**
>
> Todas las acciones se realizan con un clic derecho en esta página, atención
> al hacer bien el diseño. Por lo tanto, al crear, es necesario de
> hacer en el centro de la página (para asegurarse de estar en el diseño).

Dans le menu (clic droit donc), nous retrouvons les
actions suivantes :

-   **Designs** : Affiche la liste de vos designs et permet d’y accéder

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

-   **Ajouter zone** : Permet d’ajouter une zone transparente cliquable
    qui pourra exécuter une série d’actions lors d’un clic (en fonction
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

-   **Créer un design** : permet d’ajouter un nouveau design

-   **Duplicar design**: Duplica el diseño actual

-   **Configurar el diseño**: Acceso a la configuración del diseño

-   **Sauvegarder** : permet de sauvegarder le design (attention, il y a
    acciones se guardan automáticamente)

> **Important**
>
> La configuration des éléments du design se fait par un clic sur
> ceux-ci.

Configuración del diseño
=======================

Encontrará aquí:

-   **General**

    -   **Nombre**: El nombre de su diseño

    -   **Fondo transparente**: Hace que el fondo sea transparente. Si la casilla
        case est cochée, la couleur de fond n’est pas utilisée

    -   **Color de fondo**: Color de fondo del diseño (blanco
        por defecto)

    -   **Code** : Code d’accès à votre design (si vide, aucun code
        ningún codigo)

    -   **Icône** : Une icône pour celui-ci (apparaît dans le menu de
        elección de diseño)

    -   **Imagen**

        -   **Enviar**: Agrega una imagen de fondo al diseño

        -   **Eliminar imagen**: Borrar la imagen

-   **Tamaños**

    -   **Tamaño (An x Al)**: Le permite configurar el tamaño de su diseño
        (marco gris en modo de edición)

Configuración general de elementos
===================================

> **Note**
>
> En fonction du type de l’élément, les options peuvent changer.

> **Note**
>
> L’élément sélectionné apparaît en surbrillance rouge (au lieu de vert
> pour tous les autres).

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

Permet de verrouiller l’élément pour qu’il ne soit plus déplaçable ou
redimensionnable.

Gráfico
=========

Paramètres d’affichage 
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
> ou si il écrase un composant Jeedom planter complètement le design et
> il ne restera plus qu’à le supprimer directement en base de données

Escenario
========

Paramètres d’affichage 
---------------------

Aucun paramètre spécifique d’affichage

Link 
====

Paramètres d’affichage 
---------------------

-   **Nombre** : Nombre del enlace (texto mostrado)

-   **Link** :  Enlace al diseño o a la vista en cuestión

-   **Color de fondo** : Permite cambiar el color de fondo o el color de fondo de la imagen.
    no se olvide de cambiar "Predeterminado" a NO

-   **Color del texto** : permite cambiar el color de los iconos y
    textos (tenga cuidado de pasar Default a No)

-   **Redondear las esquinas (no olvides poner %, ej 50%)** :
    para redondear las esquinas, no olvides poner el %

-   **Borde (atención a la sintaxis CSS, ej : sólido 1px negro)** : permite
    para agregar un borde, tenga cuidado de que la sintaxis sea HTML

-   **Tamaño de la fuente (ej 50%, es necesario poner el signo %)** :
    permite cambiar el tamaño de la fuente

-   **Alineación del texto** : Le permite elegir la alineación del texto.
    texto (izquierda/derecha/centro)

-   **Negrita** : pone el texto en negrita

Dispositivo 
==========

Ajustes de pantalla
---------------------

No hay parámetros de visualización específicos

Configuración avanzada
---------------------

Affiche la fenêtre de configuration avancée de l’équipement (voir
documentation Résumé domotique ("display"))

Commando 
========

Paramètres d’affichage 
---------------------

Aucun paramètre spécifique d’affichage

Configuración avanzada
---------------------

Affiche la fenêtre de configuration avancée de la commande (voir
documentation Résumé domotique ("display"))

Imagen/Cámara 
============

Paramètres d’affichage 
---------------------

-   **Afficher** : définit ce que vous voulez afficher, image fixe ou
    Flujo de una cámara

-   **Imagen** : permite enviar la imagen en cuestión (si tiene
    seleccionada una imagen)

-   **Cámara** : cámara a mostrar (si ha elegido una cámara)

Zona 
====

Paramètres d’affichage 
---------------------

-   **Type de zone** : C’est ici que vous choisissez le type de la zone :
    Macro individual, macro Binario o widget flotante

### Macro Individual

Dans ce mode là, un clic sur la zone exécute une ou plusieurs actions.

Aquí sólo tienes que indicar la lista de acciones a realizar al hacer clic en
la zona

### Macro binario

Dans ce mode, Jeedom va exécuter la ou les actions On ou Off en
fonction de l’état de la commande que vous donnez. Ex : si la commande
vaut 0 alors Jeedom exécutera la ou les actions On sinon il exécutera
la ou les actions Off

-   **Information binaire** : Commande donnant l’état à vérifier pour
    décider de l’action à faire (On ou Off)

Il vous suffit en dessous de mettre les actions à faire pour le On et
pour le Off

### Widget flotante

Dans ce mode, lors du survol ou du clic dans la zone Jeedom, vous
afficherez le widget en question

-   **Equipement** : widget à afficher lors du survol ou du clic

-   **Afficher au survol** : si coché, affiche le widget au survol

-   **Afficher sur un clic** : si coché, alors le widget est affiché au
    clic

-   **Posición** : le permite elegir la ubicación en la que se muestra el
    widget (por defecto abajo a la derecha)

Resumén 
======

-   **Lien** : Permet d’indiquer le résumé à afficher (Général pour le
    global de lo contrario indicar el objeto)

-   **Color de fondo** : Permite cambiar el color de fondo o el color de la
    o transparencia, no olvide de cambiar "Predeterminado" a NO

-   **Color del texto** : permite cambiar el color de los iconos y
    textos (recuerda cambiar Predeterminado on No)

-   **Redondear las esquinas (no olvides poner %, ej 50%)** :
    para redondear las esquinas, no olvides poner el %

-   **Borde (atención a la sintaxis CSS, ej : sólido 1px negro)** : permite
    para agregar un borde, tenga cuidado de que la sintaxis sea HTML

-   **Tamaño de la fuente (ex 50%, es necesario poner el signo %)** :
    permite cambiar el tamaño de la fuente

-   **Negrita**: Pone el texto en negrita


FAQ 
======

>**Je n'arrive plus à éditer mon design**
>
>Si vous avez mis un widget ou une image qui prend quasiment la totalité du design, il faut bien cliquer en dehors du widget ou de l'image pour avoir accès au menu par clic droit.

>**Supprimer un design qui ne marche plus**
>
>Dans la partie administration puis OS/DB, faire "select * from planHeader", récuperer l'id du design en question et faire un "delete from planHeader where id=#TODO#" et "delete from plan where planHeader_id=#todo#" en remplaçant bien #TODO# par l'id du design trouvé précedemment.
