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

> **Importante**
>
> Si pones código HTML (especialmente Javascript), ten cuidado
> Compruébelo de antemano porque puede haber errores en él.l
> o si se sobrescribe un componente de Jeedom bloqueará completamente el diseño y
> sólo podrá borrarla directamente de la base de datos

Escenario
========

Configuración de pantalla
---------------------

No hay parámetros de visualización específicos

Link 
====

Parámetro de visualización 
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

Parámetro de visualización 
---------------------

No hay parámetros de visualización específicos

Configuración avanzada
---------------------

Muestra la ventana de configuración avanzada del equipo (véase
documentación Resumen domótico ("display")

Commando 
========

Parámetro de visualización 
---------------------

No hay parámetros de visualización específicos

Configuración avanzada
---------------------

Muestra la ventana de configuración avanzada del comando (véase
documentación Resumen domótico ("display")

Imagen/Cámara 
============

Parámetro de visualización 
---------------------

-   **Visualización** : define lo que desea visualizar, imagen fija o
    Flujo de una cámara

-   **Imagen** : permite enviar la imagen en cuestión (si tiene
    seleccionada una imagen)

-   **Cámara** : cámara a mostrar (si ha elegido una cámara)

Zona 
====

Parámetro de visualización 
---------------------

-   **Tipo de zona** : Aquí es donde usted elige el tipo de zona :
    Macro individual, macro Binario o widget flotante

### Macro Individual

En este modo, un clic en el área realiza una o más acciones.

Aquí sólo tienes que indicar la lista de acciones a realizar al hacer clic en
la zona

### Macro binario

En este modo Jeedom ejecutará la o las acción(es) ON u Off en
función del estado del comando que haya elegido. Ej si el comando
es 0 entonces Jeedom ejecutará la o las acción(es)  On, Si no ejecutará
la o las acción(es) off

-   **Información binaria** : Comando que da el estado para comprobar
    decidir qué acción tomar (on u off)

Sólo tienes que poner las acciones a realizar a continuación para el on y
para el off

### Widget flotante

En este modo, cuando se desplaza el cursor o hace clic en el área de jeedom, usted
verá el widget en cuestión

-   **Dispositivo** : widget para mostrar cuando se desplaza o hace clic

-   **Mostrar flotante** : si esta opción está seleccionada, se muestra

-   **Mostrar con un clic** : si esta opción está seleccionada, el widget se mostrará con un
    clic

-   **Posición** : le permite elegir la ubicación en la que se muestra el
    widget (por defecto abajo a la derecha)

Resumén 
======

-   **Enlace** : Se usa para indicar el resumen a mostrar (General para los
    globales si no indica el objeto)

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

>**Ya no puedo editar mi diseño**
>
>Si has puesto un widget o una imagen que ocupe casi la totalidad del diseño debes pulsar bien fuera del widget o de la imagen para acceder al menú con el botón derecho del ratón.

>**Borrar un Diseño que ya no funciona**
>
>En la parte de administración en de OS/DB hacer "select * from planHeader" obtener el id del diseño en cuestión y hacer un "delete from planHeader where id=#TODO#" y "delete from plan where planHeader_id=#todo#" reemplazando correctamente #TODO# por el id del diseño encontrado previamente.