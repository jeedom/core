# Objetos
**Herramientas → Objetos**

Los **objetos** permiten definir la estructura de tu sistema de domótica.

Todos los dispositivos que crees deben pertenecer a un objeto, lo que facilita su localización. En este caso, se dice que el objeto es el **padre** del dispositivo.

Para que puedas personalizarlo a tu gusto, puedes nombrar estos objetos como quieras. Normalmente, se suelen definir las diferentes partes de la casa, como los nombres de las habitaciones (de hecho, esta es la configuración recomendada).

![Objetos](../images/object_intro.gif)

## Gestión

Tienes dos opciones:
- **Añadir**: Permite crear un nuevo objeto.
- **Visión general**: Permite visualizar la lista de objetos creados, así como su configuración.

## Visión general

La vista general te permite visualizar todos los objetos de Jeedom, así como su configuración:

- **ID**: ID del objeto.
- **Objeto**: Nombre del objeto.
- **Padre**: Nombre del objeto padre.
- **Visible**: Visibilidad del objeto.
- **Oculto**: Indica si el objeto está oculto en el panel de control.
- **Resumen definido**: Indica el número de comandos por resumen. Lo que aparece en azul se tiene en cuenta en el resumen global.
- **Resumen del panel de control oculto**: Muestra los resúmenes ocultos en el panel de control.
- **Resumen oculto en el móvil**: Indica los resúmenes ocultos en el móvil.

## Mis objetos

Una vez que hayas creado un objeto, aparecerá en esta sección.

> **Consejo**
>
> Puedes abrir un objeto haciendo lo siguiente:
> - Haz clic en uno de ellos.
> - Ctrl + clic o clic con el botón central para abrirlo en una nueva pestaña del navegador.

Dispone de un motor de búsqueda que le permite filtrar los objetos que se muestran. La tecla Esc cancela la búsqueda.
A la derecha del campo de búsqueda, hay tres botones que aparecen en varios lugares de Jeedom:

- La cruz para cancelar la búsqueda.
- La carpeta está abierta para desplegar todos los paneles y mostrar todos los objetos.
- La carpeta cerrada para plegar todos los paneles.

Una vez en la configuración de un objeto, al hacer clic con el botón derecho del ratón sobre las pestañas del objeto, aparecerá un menú contextual. También puedes utilizar Ctrl + clic o el botón central del ratón para abrir directamente otro objeto en una nueva pestaña del navegador.

## Pestaña «Objeto»

Al hacer clic en un objeto, accederás a su página de configuración. Independientemente de los cambios que realices, no olvides guardar las modificaciones.

A continuación se detallan las diferentes características para configurar un objeto:

#### Parámetros:

- **Nombre del objeto**: El nombre de tu objeto.
- **Objeto padre**: Indica el objeto padre del objeto actual, lo que permite definir una jerarquía entre los objetos. Por ejemplo: el salón tiene como objeto padre el piso. Un objeto solo puede tener un objeto padre, pero varios objetos pueden tener el mismo objeto padre.
- **Visible**: Marca esta casilla para que este objeto sea visible.
- **Ocultar en el panel de control**: Marca esta casilla para ocultar el objeto en el panel de control. No obstante, se mantiene en la lista, lo que permite mostrarlo, pero solo de forma explícita.
- **Ocultar en el resumen**: Marca esta casilla para ocultar el objeto en el resumen. No obstante, se mantiene en la lista, lo que permite mostrarlo, pero solo de forma explícita.
- **Acción desde el resumen**: Aquí puedes indicar una vista o un diseño al que ir cuando hagas clic en el objeto desde el resumen. *Por defecto: Panel de control*.

#### Visualización:

- **Icono**: Te permite elegir un icono para tu objeto.
- **Colores personalizados**: Activa la aplicación de los dos parámetros de colores personalizados que aparecen a continuación.
- **Color de la etiqueta**: Permite elegir el color del objeto y de los dispositivos asociados a él.
- **Color del texto de la etiqueta**: permite elegir el color del texto del objeto. Este texto aparecerá sobre el **color de la etiqueta**. Elige un color que haga que el texto sea legible.
- **Solo en el resumen**: Permite añadir una imagen al resumen sin que se utilice como imagen de fondo, especialmente en la página *Dashboard* de este objeto.
- **Imagen**: Tienes la opción de subir una imagen o eliminarla. Si está en formato JPEG, esta imagen será la imagen de fondo del objeto cuando lo visualices en el panel de control. También se utilizará como miniatura de la estancia en el resumen.

> **Consejo**
>
> Puedes modificar el orden en que se muestran los objetos en el panel de control, a través del Resumen de domótica (Análisis -> Resumen de domótica); selecciona el objeto con el ratón y arrástralo y suéltalo para asignarle una nueva posición.

> **Consejo**
>
> Puedes ver un gráfico que muestra todos los elementos de Jeedom vinculados a este objeto haciendo clic en el botón **Enlaces**, situado en la parte superior derecha.

> **Consejo**
>
> Cuando se crea un dispositivo y no se ha definido ningún dispositivo principal, tendrá como dispositivo principal: **Ninguno**.

## Pestañas de resumen

[Consulta la documentación sobre los resúmenes.](https://doc.jeedom.com/concept/es_ES/summary)


