# Dashboard
**Inicio → Tablero**

<small>[Raccourcis clavier/souris](shortcuts.md)</small>

El tablero de instrumentos es una de las páginas principales de Jeedom, muestra un informe de toda la automatización de su hogar.
Este informe (a diferencia de las vistas y los diseños) es autogenerado por Jeedom e incluye todos los objetos visibles y sus equipos.

> **Punta**
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

> **Punta**
>
> Es posible ir directamente a un solo objeto en su domótica, a través del menú **Inicio → Panel de control → Nombre del objeto**.
> Esto le permite tener solo el equipo que le interesa y cargar la página más rápido.

- Tienes en la parte superior izquierda un pequeño icono para mostrar / ocultar el árbol de objetos.
- El segundo icono a la izquierda permite mostrar solo la información de los resúmenes de los objetos.
- En el medio, un campo de búsqueda le permite buscar equipos por nombre, categoría, complemento, etiqueta, etc.
- A la derecha, un botón le permite cambiar al modo de edición, modificar el orden de los mosaicos (hacer clic - soltar en el widget) o cambiar su tamaño. También puede reorganizar el orden de los pedidos en un mosaico,
- Al hacer clic en el resumen de un objeto, filtra para mostrar solo el equipo relacionado con este objeto y que está relacionado con este resumen de objeto.
- Un clic en un pedido de tipo de información muestra el historial del pedido (si es histórico).

> **Punta**
>
> Es posible, desde su perfil, configurar Jeedom para que el árbol de objetos y / o los escenarios sean visibles de forma predeterminada cuando llegue al Tablero.

> **Punta**
>
> En dispositivos móviles, al presionar un comando de tipo de información se muestra un menú que le ofrece mostrar el historial del pedido o poner una alerta en él para que Jeedom le avise (una vez) tan pronto como sea posible. que el valor pasa un cierto umbral.


## Modo de edición

En modo edición (*el lápiz en la esquina superior derecha*), puede cambiar el tamaño de los mosaicos y su disposición en el Tablero.

También puede editar el diseño interno de los controles en el mosaico :

- O bien reorganícelos arrastrando y soltando.
- Ya sea haciendo clic derecho en el widget. Entonces accedes :
    - **Configuración avanzada** : da acceso a la configuración avanzada del comando.
    - **Estándar** : diseño predeterminado, todo es automático con solo la posibilidad de reorganizar el orden de los pedidos.
    - **Mesa** : permite poner los comandos en una tabla : Las columnas y filas se agregan y eliminan haciendo clic derecho, luego simplemente mueva los comandos en los cuadros deseados. Puedes poner múltiples pedidos por caja
    - **Agregar columna** : agregar una columna a la tabla (accesible solo si está en el diseño de la tabla)
    - **Agregar línea** : agregar una fila a la tabla (accesible solo si está en el diseño de la tabla)
    - **Eliminar columna** : eliminar una columna de la tabla (accesible solo si está en el diseño de la tabla)
    - **Eliminar linea** : eliminar una fila en la tabla (accesible solo si está en el diseño de la tabla)

A la derecha de cada objeto, un icono le permite :

- Clic : Todas las fichas de este objeto adoptarán una altura igual a la ficha más alta.

## Barra de menú Jeedom

> **Punta**
>
> - Haga clic en el reloj (barra de menú) : Abre la línea de tiempo.
> - Haga clic en el nombre de Jeedom (barra de menú) : Abre Configuración → Sistema → Configuración.
> - Haga clic en ? (Barra de menú) : Abre la ayuda en la página actual.
> - Escapar en un campo de investigación : Borrar el campo y cancelar esta búsqueda .
