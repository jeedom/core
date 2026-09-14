# Vistas
**Inicio → Vista**

Las vistas permiten crear pantallas personalizadas.
No es tan potente como los diseños, pero permite, en tan solo unos minutos, conseguir una visualización más personalizada que el Dashboard, con diferentes objetos, gráficos o controles.

{% include lightbox.html src="images/doc-view_01.jpg" data="View" title="View" imgstyle="width:450px;display: block;margin: 0 auto;" %}

> **Consejo**
>
> En tu perfil puedes elegir la vista predeterminada al hacer clic en el menú de vistas.

## Principio

Se pueden colocar tanto mosaicos de equipos como gráficos (que pueden estar compuestos por varios datos) o áreas de tabla (que contienen los widgets de los controles).

En una vista, encontramos:

- Un botón en la parte superior izquierda para mostrar u ocultar la lista de vistas, así como el botón para añadir una nueva.
- El lápiz de la derecha sirve para editar el orden y el tamaño de los dispositivos, igual que en el Dashboard.
- Un botón *Edición completa* que permite editar las zonas y los elementos de la vista.

> **Consejo**
>
> En tu perfil, puedes modificar esta opción para que la lista de vistas aparezca por defecto.

## Añadir/Editar una vista

El principio es bastante sencillo: una vista se compone de zonas. Cada zona puede ser de tipo *gráfico*, *widget* o *tabla*. En función de este tipo, podrás añadir gráficos, dispositivos o controles a la misma.

- A la izquierda de la página se encuentra la lista de vistas, así como un botón para crearlas.
- Un botón situado en la parte superior derecha te permite editar la vista actual (Configuración).
- Un botón que permite añadir una zona. A continuación, se le pedirá el nombre y el tipo de zona.
- Un botón «Ver el resultado», que permite salir del modo de edición completa y mostrar esta vista.
- Un botón que permite guardar esta vista.
- Un botón que permite eliminar esta vista.

> **Consejo**
>
> Es posible cambiar el orden de las zonas arrastrándolas y soltándolas.

En cada zona dispones de las siguientes opciones generales:

- **Ancho**: Define el ancho del área (solo en modo escritorio). 1 corresponde a un ancho de 1/12 del navegador; 12, al ancho total.
- Un botón que permite añadir un elemento a esta zona, en función del tipo de zona (véase más abajo).
- **Editar**: Permite cambiar el nombre de la zona.
- **Eliminar**: Permite eliminar la zona.

### Zona de tipo «equipamiento»

Una zona de tipo «equipos» permite añadir equipos:

- **Añadir dispositivo**: Permite añadir o modificar los dispositivos que se mostrarán en la zona.

> **Consejo**
>
> Puedes eliminar un dispositivo directamente haciendo clic en la papelera situada a la izquierda del mismo.

> **Consejo**
>
> Es posible cambiar el orden de los mosaicos en el área arrastrándolos y soltándolos.


### Área de tipo gráfico

Un área de tipo gráfico permite añadir gráficos a tu vista; cuenta con las siguientes opciones:

- **Período**: Permite seleccionar el período de visualización de los gráficos (30 minutos, 1 día, 1 semana, 1 mes, 1 año o todo).
- **Añadir curva**: Permite añadir o modificar gráficos.

Al pulsar el botón **Añadir curva**, Jeedom muestra la lista de comandos registrados y puedes elegir cuál quieres añadir. Una vez hecho esto, tendrás acceso a las siguientes opciones:

- **Papelera**: Elimina el comando del gráfico.
- **Nombre**: Nombre del comando que se va a dibujar.
- **Color**: Color de la curva.
- **Tipo**: Tipo de curva.
- **Agrupación**: Permite agrupar los datos (por ejemplo, el máximo diario).
- **Escala**: Escala (derecha o izquierda) de la curva.
- **Escalera**: Muestra la curva en forma de escalera.
- **Apilar**: Apila la curva con las demás curvas del mismo tipo.
- **Variación**: Solo dibuja las variaciones con respecto al valor anterior.

{% include lightbox.html src="images/doc-view_02.jpg" data="View" title="Pie Graph" imgstyle="width:450px;display: block;margin: 0 auto;" %}

> **Consejo**
>
> Es posible cambiar el orden de los gráficos en el área mediante la función de arrastrar y soltar.

### Área de tipo tabla

Aquí tienes los botones:

- **Añadir columna**: permite añadir una columna a la tabla.
- **Añadir fila**: Permite añadir una fila a la tabla.

> **Nota**
>
> Es posible reorganizar las filas arrastrando y soltando, pero no las columnas.

Una vez que hayas añadido tus filas y columnas, puedes introducir información en las casillas:

- Un texto.
- Código HTML (se admite JavaScript, aunque se desaconseja encarecidamente).
- El widget de un comando: el botón de la derecha te permite elegir el comando que quieres mostrar.
