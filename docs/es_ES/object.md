# Objets
**Herramientas → Objetos**

EL **Objetos** le permite definir la estructura de árbol de su domótica.

Todo el equipo que cree debe pertenecer a un objeto y, por lo tanto, es más fácil de identificar. Luego decimos que el objeto es el **padre** equipo.

Para dar libre elección a la personalización, puede nombrar estos objetos como desee. Por lo general, definiremos las diferentes partes de su casa, como el nombre de las habitaciones (esta también es la configuración recomendada).

![Objetos](./images/object_intro.gif)

## Gestion

Tienes dos opciones :
- **Agregar** : Crea un nuevo objeto.
- **Resumen** : Muestra la lista de objetos creados y su configuración.

## Resumen

La descripción general le permite ver todos los objetos en Jeedom, así como su configuración :

- **IDENTIFICACIÓN** : ID de objeto.
- **Objeto** : Nombre del objeto.
- **Padre** : Nombre del objeto padre.
- **Visible** : Visibilidad del objeto.
- **Mascarilla** : Indica si el objeto está oculto en el Tablero.
- **Resumen definido** : Indica el número de pedidos por resumen. Lo que está en azul se tiene en cuenta en el resumen global.
- **Resumen de panel oculto** : Indica resúmenes ocultos en el Tablero.
- **Resumen móvil oculto** : Mostrar resúmenes ocultos en dispositivos móviles.

## Mis objetos

Una vez que haya creado un objeto, aparecerá en esta parte.

> **Consejo**
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

#### Configuraciones :

- **Nombre del objeto** : El nombre de tu objeto.
- **Objeto padre** : Indica el padre del objeto actual, esto permite definir una jerarquía entre los objetos. Por ejemplo : El salón está relacionado con el apartamento. Un objeto puede tener solo un padre pero varios objetos pueden tener el mismo padre.
- **Visible** : Marque esta casilla para hacer visible este objeto.
- **Ocultar en el tablero** : Marque esta casilla para ocultar el objeto en el Tablero. Todavía se mantiene en la lista, lo que permite que se muestre, pero solo explícitamente.
- **Ocultar en resumen** : Marque esta casilla para ocultar el objeto en el resumen'. Todavía se mantiene en la lista, lo que permite que se muestre, pero solo explícitamente.
- **Acción de síntesis** : Aquí puede indicar una vista o un diseño al que ir cuando haga clic en el objeto del Resumen. *Por defecto : Panel*.

#### Mostrar :

- **Icono** : Le permite elegir un icono para su objeto.
- **Colores personalizados** : Activa el tener en cuenta los dos parámetros de color personalizados a continuación.
- **Etiqueta de color** : Le permite elegir el color del objeto y el equipo adjunto.
- **Color del texto de la etiqueta** : Le permite elegir el color del texto del objeto. Este texto estará sobre el **Etiqueta de color**. Eliges un color para que el texto sea legible.
- **Solo en síntesis** : Le permite poner una imagen para la síntesis sin que se utilice como imagen de fondo, especialmente en la página *Panel* de este objeto.
- **Imagen** : Tienes la opción de cargar una imagen o eliminarla. En formato jpeg, esta imagen será la imagen de fondo del objeto cuando lo muestre en el Tablero. También se usará para la miniatura de la pieza en la Síntesis.

> **Consejo**
>
> Puede cambiar el orden de visualización de los objetos en el Panel, a través del Resumen de automatización del hogar (herramientas -> resumen de automatización del hogar), seleccione su objeto con el mouse arrastrando/soltando para darle un nuevo lugar.

> **Consejo**
>
> Puede ver un gráfico que representa todos los elementos de Jeedom unidos a este objeto haciendo clic en el botón **Campo de golf**, arriba a la derecha.

> **Consejo**
>
> Cuando se crea un dispositivo y no se ha definido ningún padre, tendrá como padre : **Ninguno**.

## Pestañas de resumen

[Ver documentación de resúmenes.](/es_ES/concept/summary)


