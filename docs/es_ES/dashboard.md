# Panel de control
**Inicio → Panel de control**

<small>[Atajos de teclado y ratón](shortcuts.md)</small>

El panel de control es una de las páginas principales de Jeedom; muestra un resumen de todo tu sistema domótico.
Este informe (a diferencia de las vistas y los diseños) lo genera automáticamente Jeedom e incluye todos los objetos visibles y sus dispositivos.

{% include lightbox.html src="../images/doc-dashboard-legends.png" data="Dashboard" title="Dashboard" imgstyle="width:450px;display: block;margin: 0 auto;" %}

- 1: Menú principal de Jeedom.
- 2: Resumen general [Documentación sobre los resúmenes.](https://doc.jeedom.com/concept/es_ES/summary).
- 3: Hora del navegador, acceso directo a la línea de tiempo.
- 4: Botón para acceder a la documentación de la página actual.
- 5: Nombre de tu Jeedom, acceso directo a la configuración.
- 6: Modo de edición (Reordenar / cambiar el tamaño de los mosaicos).
- 7: Filtrar por categorías.
- 8: Objeto: Icono, nombre y resumen, y sus equipos.
- 9: Ficha de un equipo.
- 10: Widget de un comando.

> **Consejo**
>
> El orden en el que se muestran los objetos en el panel de control es el mismo que el que aparece en **Análisis → Resumen de domótica**. En esta página puedes modificar el orden arrastrando y soltando los objetos.

Para que un dispositivo aparezca en el panel de control, debe:
- Mantenerte activo.
- Ser visible.
- Tener como objeto padre un objeto visible en el panel de control.

La primera vez que el dispositivo aparece en el Dashboard, Jeedom intenta ajustar correctamente el tamaño de su mosaico para mostrar todos los controles y sus widgets.
Para mantener un panel de control equilibrado, puedes pasar al modo de edición haciendo clic en el lápiz situado en la esquina superior derecha de la barra de búsqueda, con el fin de cambiar el tamaño y/o reordenar los mosaicos de los dispositivos.

Al pasar el ratón por encima de un comando, aparece un marcador de color en la parte inferior izquierda del mosaico:
- Azul para solicitar información sobre un comando. Si está registrado en el historial, al hacer clic sobre él se abre la ventana del historial.
- Naranja para un comando de acción. Un clic activará la acción.

Además, puedes hacer clic en el título del mosaico (el nombre del dispositivo) para abrir directamente la página de configuración de dicho dispositivo.

> **Consejo**
>
> Puedes acceder directamente a un objeto concreto de tu sistema de domótica a través del menú **Inicio → Panel de control → Nombre del objeto**.
> Esto permite que solo se muestren los dispositivos que te interesan y que la página se cargue más rápido.

- En la esquina superior izquierda hay un pequeño icono que permite mostrar el árbol de objetos al pasar el cursor por encima.
- Un campo de búsqueda permite buscar un dispositivo por su nombre, categoría, plugin, etiqueta, etc.
- El icono situado a la derecha del campo de búsqueda permite filtrar los dispositivos que se muestran según su categoría. Al hacer clic en el centro, se puede seleccionar rápidamente una sola categoría.
- En el extremo derecho, hay un botón que permite pasar al modo de edición para modificar el orden de los mosaicos (hacer clic y arrastrar sobre el widget) o cambiar su tamaño. También puedes reorganizar el orden de los comandos dentro de un mosaico.

- Al hacer clic en un resumen de objeto, se filtra la lista para mostrar únicamente los equipos que tienen ese objeto como padre y que están relacionados con dicho resumen de objeto.

- Al hacer clic en un comando de tipo «información», se muestra el historial del comando (si está registrado).
- Al pulsar Ctrl+clic en un comando de tipo «información», se muestra el historial de todos los comandos (registrados) de ese mosaico.
- Al hacer clic en la información *time* de un comando de acción, se muestra el historial del comando (si se ha registrado).

## Modo de edición

En el modo de edición (*el lápiz situado en la esquina superior derecha*), puedes cambiar el tamaño de los mosaicos y su disposición en el panel de control.

Los iconos de actualización de los dispositivos se sustituyen por un icono que permite acceder a su configuración. Este icono abre una ventana de edición que incluye los parámetros de visualización del dispositivo y sus controles.

![Modo de edición](../images/EditDashboardModal.gif)

En cada objeto, a la derecha de su nombre y resumen, hay dos iconos que permiten ajustar la altura de todas las fichas del objeto a la más alta o a la más baja.

## Barra de menú de Jeedom

> **Consejo**
>
> - Haz clic en el reloj (barra de menú): abre la línea de tiempo.
> - Haz clic en el nombre de Jeedom (barra de menú): se abre Ajustes → Sistema → Configuración.
> - Haz clic en el signo «?» (barra de menú): abre la ayuda de la página actual.
> - Tecla Esc en un campo de búsqueda: borra el contenido del campo y cancela la búsqueda.
