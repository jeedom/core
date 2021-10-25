# Dashboard
**Inicio → Tablero**

<small>[Raccourcis clavier/souris](shortcuts.md)</small>

El tablero es una de las páginas principales de Jeedom, muestra un informe de toda su domótica.
Este informe (a diferencia de las vistas y los diseños) es autogenerado por Jeedom e incluye todos los objetos visibles y sus equipos.

![Tablero](../images/doc-dashboard-legends.png)

- 1 : Menú principal de Jeedom.
- 2 : Resumen global [Documentación abstracta.](/es_ES/concept/summary).
- 3 : Tiempo del navegador, acceso directo a la línea de tiempo.
- 4 : Botón para acceder a la documentación de la página actual.
- 5 : Nombre de su Jeedom, acceso directo a la configuración.
- 6 : Modo de edición (reordenar / redimensionar mosaicos).
- 7 : Filtrar por categorias.
- 8 : Objeto : Icono, nombre y resumen, y su equipo.
- 9 : Equipo de azulejos.
- 10 : Solicitar widget.

> **Propina**
>
> El orden de visualización de los objetos en el Tablero es el visible en **Análisis → Resumen de domótica**. Puede modificar este orden en esta página arrastrando y soltando.

Para que el equipo aparezca en el Tablero, debe :
- Estar activo.
- Ser visible.
- Tener como objeto principal un objeto visible en el Tablero.

Cuando el equipo aparece por primera vez en el Tablero, Jeedom intenta dimensionar correctamente su mosaico para mostrar todos los comandos y sus widgets.
Para mantener un tablero equilibrado, puede cambiar al modo Editar con el lápiz en la parte superior derecha de la barra de búsqueda, para cambiar el tamaño y / o reordenar los mosaicos del equipo.

Al mover el mouse sobre un pedido, aparece un marcador de color en la parte inferior izquierda del mosaico:
- Azul para un pedido de información. Si está registrado, un clic en él abre la ventana de registro.
- Naranja para un comando de acción. Un clic activará la acción.

Además, puede hacer clic en el título del mosaico (el nombre del equipo) para abrir directamente la página de configuración de este equipo.

> **Propina**
>
> Es posible ir directamente a un solo objeto en su domótica, a través del menú **Inicio → Panel de control → Nombre del objeto**.
> Esto le permite tener solo el equipo que le interesa y cargar la página más rápido.

- Tiene en la parte superior izquierda un pequeño icono para mostrar el árbol de objetos al pasar el mouse.
- Un campo de búsqueda le permite buscar equipos por nombre, categoría, complemento, etiqueta, etc.
- El icono a la derecha del campo de búsqueda se utiliza para filtrar los equipos mostrados según su categoría. Un clic central permite seleccionar rápidamente una sola categoría.
- En el extremo derecho, un botón le permite cambiar al modo de edición, modificar el orden de los mosaicos (hacer clic y soltar en el widget) o cambiar su tamaño. También puede reorganizar el orden de los pedidos en un mosaico.

- Al hacer clic en el resumen de un objeto, filtra para mostrar solo el equipo relacionado con este objeto y que está relacionado con este resumen de objeto.

- Un clic en un pedido de tipo de información muestra el historial del pedido (si es histórico).
- Un comando Ctrl + clic en un tipo de información muestra el historial de todos los comandos (históricos) para este mosaico.
- Un clic en la información *hora* de un comando de acción muestra el historial del comando (si está historizado).


## Modo de edición

En modo edición (*el lápiz en la esquina superior derecha*), puede cambiar el tamaño de los mosaicos y su disposición en el Tablero.

los íconos de actualización del equipo se reemplazan por un ícono que permite acceder a su configuración. Este icono abre una ventana de edición con los parámetros de visualización del equipo y sus controles.

![Modo de edición](./images/EditDashboardModal.gif)

En cada objeto, a la derecha de su nombre y resumen, dos iconos le permiten alinear la altura de todos los mosaicos del objeto en el más alto o más bajo.

## Barra de menú Jeedom

> **Propina**
>
> - Haga clic en el reloj (barra de menú) : Abre la línea de tiempo.
> - Haga clic en el nombre de Jeedom (barra de menú) : Abre Configuración → Sistema → Configuración.
> - Haga clic en ? (Barra de menú) : Abrir ayuda en la página actual.
> - Escapar en un campo de investigación : Borrar el campo y cancelar esta búsqueda.
