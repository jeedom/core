# Vues
**Inicio → Ver**

Las vistas le permiten crear vistas personalizadas.
No es tan potente como los diseños, pero permite en pocos minutos tener una pantalla más personalizada que el Tablero, con equipos de diferentes objetos, gráficos o controles.

> **Propina**
>
> Puede elegir la vista predeterminada en su perfil al hacer clic en el menú de vista.

## Principe

También puede colocar mosaicos de equipo, gráficos (que pueden estar compuestos de varios datos) o zonas de tabla (que contienen los widgets de los comandos).

En una vista, encontramos :

- Un botón en la parte superior izquierda para mostrar u ocultar la lista de Vistas, así como el botón para agregar uno.
- El lápiz a la derecha para editar el orden y el tamaño del equipo, de la misma manera que el Tablero.
- Un botón *Edición completada* permitiendo editar las zonas y elementos de la Vista.

> **Propina**
>
> Puede, en su perfil, modificar esta opción para que la lista de Vistas sea visible por defecto.

## Agregar / Editar una vista

El principio es bastante simple : una vista está compuesta de áreas. Cada zona es de tipo *gráfico*, *widget* o *tablero*. Dependiendo de este tipo, puede agregarle gráficos, equipos o comandos.

- A la izquierda de la página encontramos la lista de Vistas, así como un botón de creación.
- Un botón en la parte superior derecha le permite editar la Vista actual (Configuración).
- Un botón para agregar una zona. Luego se le preguntará el nombre y el tipo de zona.
- Un botón *Ver el resultado*, para salir del modo de edición completo y mostrar esta Vista.
- Un botón que permite guardar esta Vista.
- Un botón que permite eliminar esta Vista.

> **Propina**
>
> Puede mover el orden de las zonas arrastrando y soltando.

En cada zona tienes las siguientes opciones generales :

- **Anchura** : Define el ancho del área (solo en modo escritorio). 1 para el ancho de 1/12 del navegador, 12 para el ancho total.
- Un botón que permite agregar un elemento a esta zona, dependiendo del tipo de zona (ver abajo).
- **Editar** : Le permite cambiar el nombre de la zona.
- **Eliminar** : Eliminar la zona.

### Zona de tipo de equipo

Una zona de tipo de equipo le permite agregar equipo :

- **Agregar dispositivo** : Le permite agregar / modificar equipos para mostrar en el área.

> **Propina**
>
> Puede eliminar un elemento del equipo directamente haciendo clic en el bote de basura a la izquierda.

> **Propina**
>
> Es posible cambiar el orden de los mosaicos en el área arrastrando y soltando.


### Área de tipo gráfico

Un área de tipo gráfico le permite agregar gráficos a su vista, tiene las siguientes opciones :

- **Período** : Le permite elegir el período de visualización de los gráficos (30 min, 1 día, 1 semana, 1 mes, 1 año o todos).
- **Agregar curva** : Agregar / editar gráficos.

Cuando presionas el botón **Agregar curva**, Jeedom muestra la lista de pedidos históricos y puede elegir el que desea agregar. Una vez hecho esto, tiene acceso a las siguientes opciones :

- **Bote de basura** : Eliminar comando del gráfico.
- **Apellido** : Nombre del comando para dibujar.
- **Color** : Color de la curva.
- **Tipo** : Tipo de curva.
- **Grupo** : Permite la agrupación de datos (tipo máximo por día).
- **Escalera** : Escala (derecha o izquierda) de la curva.
- **Escalera** : Muestra la curva de la escalera.
- **Apilar** : Apila la curva con las curvas de otro tipo.
- **Variación** : Solo dibuja variaciones con el valor anterior.

> **Propina**
>
> Puede cambiar el orden de los gráficos en el área arrastrando y soltando.

### Área de tipo de matriz

Aqui tienes los botones :

- **Agregar columna** : Agregar una columna a la tabla.
- **Agregar línea** : Agregar una fila a la tabla.

> **Nota**
>
> Es posible reorganizar las filas arrastrando y soltando, pero no las columnas.

Una vez que haya agregado sus filas / columnas, puede agregar información en los cuadros :

- Un texto.
- Código HTML (javascript posible pero desaconsejado)).
- El widget de una orden : El botón de la derecha le permite elegir el comando para mostrar.
