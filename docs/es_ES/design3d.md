Esta página le permite crear una vista 3D de su hogar que puede reaccionar dependiendo del estado de la información variada en su domótica.

Es accesible por Inicio → Panel de control

> **Punta**
>
> Es posible ir directamente a un diseño 3D gracias al submenú.

# Importando el modelo 3D

> **IMPORTANTE**
>
> No puede crear su modelo 3D directamente en Jeedom, debe hacerlo con un software de terceros. Nous recommandons le très bon SweetHome3d (http://www.sweethome3d.com/fr/).

Una vez que se ha creado su modelo 3D, debe exportarse en formato OBJ. Si usa SweetHome3d, esto se hace desde el menú "Vista 3D" y luego "Exportar a formato OBJ". Luego tome todos los archivos generados y póngalos en un archivo zip (puede haber muchos archivos debido a las texturas).

> **IMPORTANTE**
>
> Los archivos deben estar en la raíz del zip no está en una subcarpeta

> **ATENCIÓN**
>
> Un modelo 3D es bastante impresionante (esto puede representar varios cientos de MB). Cuanto más grande es, mayor es el tiempo de representación en Jeedom.

Una vez que su modelo 3D ha sido exportado, debe crear un nuevo diseño 3D en Jeedom. Para eso, debe ingresar al modo de edición haciendo clic en el lápiz pequeño a la derecha, luego haga clic en +, asigne un nombre a este nuevo diseño 3D y luego valide.

Jeedom cambiará automáticamente al nuevo diseño 3D, debe volver al modo de edición y hacer clic en las pequeñas ruedas con muescas.

Puedes desde esta pantalla :

- Cambia el nombre de tu diseño
- Agregar un código de acceso
- Elige un ícono
- Importa tu modelo 3D

Haga clic en el botón &quot;enviar&quot; en el nivel &quot;Modelo 3D&quot; y seleccione su archivo zip

> **ATENCIÓN**
>
> Jeedom autoriza la importación de un archivo de 150mo como máximo !

> **ATENCIÓN**
>
> Debes tener un archivo zip

> **Punta**
>
> Una vez que se haya importado el archivo (puede ser bastante largo dependiendo del tamaño del archivo), debe actualizar la página para ver el resultado (F5)


# Configuracion de elementos

> **IMPORTANTE**
>
> La configuración solo se puede hacer en modo edición

Para configurar un elemento en el diseño 3D, haga doble clic en el elemento que desea configurar. Esto abrirá una ventana donde puedes :

- Indique un tipo de enlace (actualmente solo existe el equipo)
- El enlace al artículo en cuestión. Aquí solo puede poner un enlace a un dispositivo por el momento. Esto permite al hacer clic en el elemento para que aparezca el equipo
- La especificidad, hay varios que veremos justo después, esto permite especificar el tipo de equipo y, por lo tanto, la visualización de información

## Luz

- Estatus : El control del estado de la luz puede ser binario (0 o 1), digital (0 a 100%) o color
- Potencia : potencia de la bombilla (tenga en cuenta que esto puede no reflejar la realidad))

## Texte

- Texto : texto para mostrar (puede poner comandos allí, el texto se actualizará automáticamente al cambiarlo)
- Tamaño del texto
- Color del texto
- Transparencia de texto : de 0 (invisible) a 1 (visible)
- Color de fondo
- Transparencia de fondo : de 0 (invisible) a 1 (visible)
- Color del borde
- Transparencia de la frontera : de 0 (invisible) a 1 (visible)
- Espacio sobre el objeto : permite indicar el espaciado del texto en comparación con el elemento

## Puerta / ventana

### Puerta / ventana

- Estado : Estado de puerta / ventana, 1 cerrado y 0 abierto
- Rotation
	- Activar : activa la rotación de la puerta / ventana al abrir
	- Apertura : lo mejor es probar para que coincida con su puerta / ventana
- Translation
	- Activar : activa la traducción al abrir (puerta corredera / tipo de ventana))
	- Significado : dirección en la que debe moverse la puerta / ventana (tiene arriba / abajo / derecha / izquierda)
	- Repetición : de forma predeterminada, la Puerta / Ventana se mueve una vez que su dimensión en la dirección dada, pero puede aumentar este valor
- Ocultar cuando la puerta / ventana está abierta
	- Activar : Oculta el elemento si la puerta / ventana está abierta
- Couleur
	- Color abierto : si está marcado, el elemento tomará este color si la puerta / ventana está abierta
	- Color cerrado : si está marcado, el elemento tomará este color si la puerta / ventana está cerrada

### Volet

- Estado : estado del obturador, 0 abierto otro valor cerrado
- Ocultar cuando el obturador está abierto
	- Activar : ocultar el elemento si el obturador está abierto
- Couleur
	- Color cerrado : si está marcado, el elemento tomará este color si el obturador está cerrado

## Color condicional

Permite dar el color elegido al elemento si la condición es válida. Puedes poner tantos colores / condiciones como quieras.

> **Punta**
>
> Las condiciones se evalúan en orden, se tomará la primera que sea verdadera, por lo tanto, las siguientes no se evaluarán
