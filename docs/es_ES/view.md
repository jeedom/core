# Vues
**Inicio → Ver**

Las vistas le permiten crear una vista personalizada.
No es tan potente como los diseños, pero permite en unos minutos tener una pantalla más personalizada.

> **Punta**
>
> Puede elegir la vista predeterminada en su perfil al hacer clic en el menú de vista.

## Principe

También podemos poner widgets, gráficos (que pueden estar compuestos de varios datos) o zonas de tabla (que contienen los widgets de comandos).

En esta página, hay un botón en la parte superior izquierda para mostrar u ocultar la lista de vistas, así como el botón para agregar una (Jeedom le preguntará su nombre y lo enviará a la página de edición)) :

> **Punta**
>
> Puede modificar esta opción en su perfil para que la lista de vistas sea visible por defecto.

## Agregar / Editar una vista

El principio es bastante simple : una vista se compone de zonas (puede poner tantas como desee). Cada zona es de tipo gráfico, de widget o de tabla, dependiendo del tipo en el que puede colocar equipos, controles o widgets gráficos.

> **Punta**
>
> Puede mover el orden de las zonas arrastrando y soltando.

- A la izquierda de la página encontramos la lista de vistas y un botón para agregar.
- Un botón en la parte superior derecha le permite editar la vista actual.
- En el centro, tiene un botón para cambiar el nombre de una vista, un botón para agregar un área, un botón para ver el resultado, un botón para guardar y un botón para eliminar la vista.

Después de hacer clic en el botón Agregar zona, Jeedom le preguntará su nombre y tipo.
En cada zona tienes las siguientes opciones generales :

- **Ancho** : Define el ancho del área (solo en modo escritorio).
- **Editar** : Permite cambiar el nombre de la zona.
- **Remove** : Permite eliminar la zona.

### Área de tipo de widget

Un área de tipo de widget le permite agregar widgets :

- **Agregar widget** : Agregar / editar widgets para mostrar en el área.

> **Punta**
>
> Puede eliminar un widget directamente haciendo clic en la papelera que se encuentra frente a él.

> **Punta**
>
> Puede cambiar el orden de los widgets en el área arrastrando y soltando.

Una vez que se presiona el botón Agregar widget, aparece una ventana que le pedirá que agregue el widget

### Área de tipo gráfico

Un área de tipo gráfico le permite agregar gráficos a su vista, tiene las siguientes opciones :

- **Período** : Le permite elegir el período de visualización de los gráficos (30 min, 1 día, 1 semana, 1 mes, 1 año o todos).
- **Agregar curva** : Permite agregar / modificar gráficos.

Cuando presiona el botón &quot;Agregar curva&quot;, Jeedom muestra la lista de comandos históricos y puede elegir los que desea agregar, una vez hecho esto tiene acceso a las siguientes opciones :

- **Cubo de basura** : Eliminar comando del gráfico.
- **Apellido** : Nombre del comando para dibujar.
- **Color** : Color de la curva.
- **Tipo** : Tipo de curva.
- **Grupo** : Permite la agrupación de datos (tipo máximo por día).
- **Escala** : escala (derecha o izquierda) de la curva.
- **Escalera** : Muestra la curva escalonada.
- **Montón** : Apilar la curva con las curvas de otro tipo.
- **Cambio** : Solo dibuja variaciones con el valor anterior.

> **Punta**
>
> Puede cambiar el orden de los gráficos en el área arrastrando y soltando.

### Área de tipo de matriz

Aqui tienes los botones :

- **Agregar columna** : Agregar una columna a la tabla.
- **Agregar línea** : Agregar una fila a la mesa.

> **Nota**
>
> Es posible reorganizar las filas arrastrando y soltando, pero no las columnas.

Una vez que haya agregado sus filas / columnas, puede agregar información en los cuadros :

- **texto** : solo texto para escribir.
- **HTML** : cualquier código html (JavaScript posible pero desaconsejado)).
- **widget de comando** : el botón de la derecha le permite elegir el comando para mostrar (tenga en cuenta que esto muestra el widget del comando).


