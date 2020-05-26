# Objets
**Herramientas → Objetos**

La **objetos** le permite definir la estructura de árbol de su domótica.

Todo el equipo que cree debe pertenecer a un objeto y, por lo tanto, es más fácil de identificar. Luego decimos que el objeto es el **pariente** equipo.

Para dar libre elección a la personalización, puede nombrar estos objetos como desee. Por lo general, definiremos las diferentes partes de su casa, como el nombre de las habitaciones (esta también es la configuración recomendada).

## Gestion

Tienes dos opciones :
- **Añadir** : Crea un nuevo objeto.
- **Resumen** : Muestra la lista de objetos creados y su configuración.

## Mis objetos

Una vez que haya creado un objeto, aparecerá en esta parte.

> **Punta**
>
> Puede abrir un objeto haciendo :
> - Haga clic en uno de ellos.
> - Ctrl Clic o Clic Center para abrirlo en una nueva pestaña del navegador.

Tiene un motor de búsqueda para filtrar la visualización de objetos. La tecla Escape cancela la búsqueda.
A la derecha del campo de búsqueda, se encuentran tres botones en varios lugares de Jeedom:

- La cruz para cancelar la búsqueda.
- La carpeta abierta para desplegar todos los paneles y mostrar todos los objetos.
- La carpeta cerrada para doblar todos los paneles.

Una vez en la configuración de un objeto, tiene un menú contextual con el botón derecho en las pestañas del objeto. También puede usar Ctrl Click o Center Click para abrir directamente otro objeto en una nueva pestaña del navegador.

## Pestaña Objeto

Al hacer clic en un objeto, accede a su página de configuración. Independientemente de los cambios que realice, no olvide guardar sus cambios.

Aquí están las diferentes características para configurar un objeto :

- **Nombre del objeto** : El nombre de tu objeto.
- **Padre** : Indica el padre del objeto actual, esto permite definir una jerarquía entre los objetos. Por ejemplo : El salón está relacionado con el apartamento. Un objeto puede tener solo un padre pero varios objetos pueden tener el mismo padre.
- **Visible** : Marque esta casilla para hacer visible este objeto.
- **Esconderse en el tablero** : Marque esta casilla para ocultar el objeto en el Tablero. Todavía se mantiene en la lista, lo que permite que se muestre, pero solo explícitamente.
- **Ocultar en resumen'** : Marque esta casilla para ocultar el objeto en el resumen'. Todavía se mantiene en la lista, lo que permite que se muestre, pero solo explícitamente.
- **Icono** : Le permite elegir un icono para su objeto.
- **Colores personalizados** : Activa la consideración de los dos parámetros de color opcionales.
- **Etiqueta de color** : Le permite elegir el color del objeto y el equipo adjunto.
- **Color del texto de la etiqueta** : Le permite elegir el color del texto del objeto. Este texto estará sobre el **Etiqueta de color**. Eliges un color para que el texto sea legible.
- **Imagen** : Tienes la opción de cargar una imagen o eliminarla. En formato jpeg, esta imagen será la imagen de fondo del objeto cuando lo muestre en el Tablero.

> **Punta**
>
> Puede cambiar el orden de visualización de los objetos en el tablero. En la descripción general, seleccione su objeto con el mouse arrastrando y soltando para darle un nuevo lugar.

> **Punta**
>
> Puede ver un gráfico que representa todos los elementos de Jeedom unidos a este objeto haciendo clic en el botón **Vínculos**, arriba a la derecha.

> **Punta**
>
> Cuando se crea un dispositivo y no se ha definido ningún padre, tendrá como padre : **No**.

## Pestaña Resumen

Los resúmenes son información global, asignada a un objeto, que se muestra en particular en el Tablero junto a su nombre.

### Tablero de anuncios

Las columnas representan los resúmenes asignados al objeto actual. Se te proponen tres líneas :

- **Subir en el resumen global** : Marque la casilla si desea que el resumen se muestre en la barra de menú de Jeedom.
- **Ocultar en el escritorio** : Marque la casilla si no desea que el resumen aparezca junto al nombre del objeto en el Tablero.
- **Ocultar en el móvil** : Marque la casilla si no desea que aparezca el resumen cuando lo vea desde un dispositivo móvil.

### Commandes

Cada pestaña representa un tipo de resumen definido en la configuración de Jeedom. Haga clic en **Agregar un pedido** para que se tenga en cuenta en el resumen. Tiene la opción de seleccionar el comando de cualquier equipo Jeedom, incluso si no tiene este objeto como padre.

> **Punta**
>
> Si desea agregar un tipo de resumen o configurar el método de cálculo del resultado, la unidad, el icono y el nombre de un resumen, debe ir a la configuración general de Jeedom : **Preferencias → Sistema → Configuración : Pestaña Resúmenes**.

## Resumen

La descripción general le permite ver todos los objetos en Jeedom, así como su configuración :

- **Identificación** : ID de objeto.
- **Objeto** : Nombre del objeto.
- **Padre** : Nombre del objeto padre.
- **Visible** : Visibilidad del objeto.
- **Enmascarado** : Indica si el objeto está oculto en el tablero.
- **Resumen definido** : Indica el número de pedidos por resumen. Lo que está en azul se tiene en cuenta en el resumen global.
- **Resumen de panel oculto** : Indica resúmenes ocultos en el Tablero.
- **Resumen móvil oculto** : Mostrar resúmenes ocultos en dispositivos móviles.
