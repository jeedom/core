# Diseño
**Inicio → Diseño**

Esta página te permite configurar la visualización de todo tu sistema de domótica con gran precisión.
Esto requiere tiempo, pero su único límite es tu imaginación.

> **Consejo**
>
> Se puede acceder directamente a un diseño a través del submenú.

> **Importante**
>
> Todas las acciones se realizan haciendo clic con el botón derecho en esta página; asegúrate de hacerlo sobre el diseño. Por lo tanto, al crearla, debes hacerlo en el centro de la página (para asegurarte de que estás sobre el diseño).

En el menú (clic con el botón derecho), encontramos las siguientes acciones:

- **Diseños**: Muestra la lista de tus diseños y te permite acceder a ellos.
- **Edición**: Permite pasar al modo de edición.
- **Pantalla completa**: permite utilizar toda la página web, lo que ocultará el menú de Jeedom de la parte superior.
- **Añadir gráfico**: Permite añadir un gráfico.
- **Añadir texto/HTML**: Permite añadir texto o código HTML/JavaScript.
- **Añadir escenario**: Permite añadir un escenario.
- **Añadir enlace**
    - **Hacia una vista**: Permite añadir un enlace a una vista.
    - **Hacia un diseño**: Permite añadir un enlace a otro diseño.
- **Añadir dispositivo**: Permite añadir un dispositivo.
- **Añadir comando**: Permite añadir un comando.
- **Añadir imagen/cámara**: Permite añadir una imagen o la señal de una cámara.
- **Añadir zona**: Permite añadir una zona transparente en la que se puede hacer clic y que ejecutará una serie de acciones al hacer clic (dependiendo o no del estado de otro comando).
- **Añadir resumen**: Añade la información de un resumen de objeto o general.
- **Pantalla**
    - **Ninguna**: No muestra ninguna tabla.
    - **10x10**: Muestra una cuadrícula de 10 por 10.
    - **15x15**: Muestra una cuadrícula de 15 por 15.
    - **20x20**: Muestra una cuadrícula de 20 por 20.
    - **Magnetizar los elementos**: Añade magnetismo entre los elementos para que se puedan pegar más fácilmente.
    - **Ajustar a la cuadrícula**: Añade una alineación magnética de los elementos a la cuadrícula (atención: dependiendo del nivel de zoom del elemento, esta función puede funcionar mejor o peor).
    - **Ocultar el resaltado de los elementos**: Oculta el resaltado que rodea a los elementos.
- **Eliminar el diseño**: Elimina el diseño.
- **Crear un diseño**: Permite añadir un nuevo diseño.
- **Duplicar el diseño**: Duplica el diseño actual.
- **Configurar el diseño**: Acceso a la configuración del diseño.
- **Guardar**: Permite guardar el diseño (atención: también se realizan copias de seguridad automáticas al realizar determinadas acciones).

> **Importante**
>
> Para configurar los elementos del diseño, basta con hacer clic sobre ellos.

## Configuración del diseño

Aquí encontrarás:

- **General**
    - **Nombre**: El nombre de tu diseño.
    - **Posición**: La posición del diseño en el menú. Permite ordenar los diseños.
    - **Fondo transparente**: Hace que el fondo sea transparente. Atención: si se marca esta casilla, no se utiliza el color de fondo.
    - **Color de fondo**: Color de fondo del diseño.
    - **Código de acceso**: Código de acceso a tu diseño (si está en blanco, no se solicita ningún código).
    - **Icono**: Un icono para este (aparece en el menú de selección de diseño).
    - **Imagen**
        - **Enviar**: Permite añadir una imagen de fondo al diseño.
        - **Eliminar la imagen**: Permite eliminar la imagen.
- **Tamaños**
    - **Tamaño (An x Al)**: permite establecer el tamaño en píxeles de tu diseño.

## Configuración general de los elementos

> **Nota**
>
> Las opciones pueden variar en función del tipo de elemento.

### Parámetros de visualización comunes

- **Profundidad**: Permite seleccionar el nivel de profundidad
- **Posición X (%)**: coordenada horizontal del elemento.
- **Posición Y (%)**: coordenada vertical del elemento.
- **Ancho (px)**: Ancho del elemento en píxeles.
- **Altura (px)**: Altura del elemento en píxeles.

### Eliminar

Permite eliminar el elemento

### Duplicar

Permite duplicar el elemento

### Cerrar con llave

Permite bloquear el elemento para que ya no se pueda mover ni cambiar su tamaño.

## Gráfico

### Parámetros de visualización específicos

- **Período**: Permite seleccionar el período que se va a mostrar
- **Mostrar leyenda**: Muestra la leyenda.
- **Mostrar el navegador**: Muestra el navegador (el segundo gráfico, más claro, situado debajo del primero).
- **Mostrar el selector de periodo**: Muestra el selector de periodo en la parte superior izquierda.
- **Mostrar la barra de desplazamiento**: Muestra la barra de desplazamiento.
- **Fondo transparente**: Hace que el fondo sea transparente.
- **Borde**: Permite añadir un borde; ten en cuenta que la sintaxis es HTML (atención: hay que utilizar sintaxis CSS, por ejemplo: solid 1px black).

### Configuración avanzada

Permite seleccionar los comandos que se van a representar gráficamente.

## Texto/html

### Parámetros de visualización específicos

- **Icono**: Icono que aparece delante del nombre del diseño.
- **Color de fondo**: permite cambiar el color de fondo o hacerlo transparente; no olvides desactivar la opción «Predeterminado».
- **Color del texto**: permite cambiar el color de los iconos y los textos (asegúrate de cambiar la opción «Predeterminado» a «No»).
- **Redondear las esquinas**: permite redondear las esquinas (no olvides indicar el porcentaje, p. ej., 50 %).
- **Borde**: permite añadir un borde; ten en cuenta que la sintaxis es HTML (hay que utilizar sintaxis CSS, por ejemplo: solid 1px black).
- **Tamaño de la fuente**: permite modificar el tamaño de la fuente (por ejemplo, 50 %; hay que incluir el signo %).
- **Alineación del texto**: permite elegir la alineación del texto (izquierda/derecha/centrada).
- **Negrita**: pone el texto en negrita.
- **Texto**: Texto en código HTML que aparecerá en el elemento.

> **Importante**
>
> Si introduces código HTML (especialmente JavaScript), asegúrate de revisarlo bien antes, ya que, si contiene algún error o sobrescribe un componente de Jeedom, puedes estropear por completo el diseño y no te quedará más remedio que eliminarlo directamente de la base de datos.

## Guion

*Sin ajustes específicos de visualización*

## Enlace

### Parámetros de visualización específicos

- **Nombre**: Nombre del enlace (texto que se muestra).
- **Enlace**: Enlace al diseño o a la vista en cuestión.
- **Color de fondo**: Permite cambiar el color de fondo o hacerlo transparente; no olvides desactivar la opción «Predeterminado».
- **Color del texto**: Permite cambiar el color de los iconos y los textos (asegúrate de cambiar la opción «Predeterminado» a «No»).
- **Redondear las esquinas (no olvides indicar el %, p. ej., 50 %)**: Permite redondear las esquinas; no olvides indicar el %.
- **Borde (atención a la sintaxis CSS, p. ej.: solid 1px black)**: Permite añadir un borde; ten en cuenta que la sintaxis es HTML.
- **Tamaño de la fuente (p. ej., 50 %, hay que poner el signo %)**: Permite modificar el tamaño de la fuente.
- **Alineación del texto**: Permite elegir la alineación del texto (izquierda/derecha/centrada).
- **Negrita**: Pone el texto en negrita.

## Equipamiento

### Parámetros de visualización específicos

- **Mostrar el nombre del objeto**: Marca esta casilla para mostrar el nombre del objeto principal del equipo.
- **Ocultar el nombre**: Marca esta casilla para ocultar el nombre del dispositivo.
- **Color de fondo**: Permite elegir un color de fondo personalizado, mostrar el dispositivo con un fondo transparente o utilizar el color predeterminado.
- **Color del texto**: Permite elegir un color de fondo personalizado o utilizar el color predeterminado.
- **Redondeo**: Valor en píxeles del redondeo de las esquinas del mosaico del equipo.
- **Borde**: Definición CSS del borde de la ficha del equipo. Ej.: 1px sólido negro.
- **Opacidad**: Opacidad del mosaico del equipo, entre 0 y 1. Atención: es necesario definir un color de fondo.
- **CSS personalizado**: Reglas CSS que se deben aplicar al equipo.
- **Aplicar el CSS personalizado a**: Selector CSS al que se aplicará el CSS personalizado.

### Controles

La lista de controles disponibles en el equipo te permite, para cada uno de ellos:
- Ocultar el nombre del comando.
- Ocultar el comando.
- Mostrar el comando con un fondo transparente.

### Configuración avanzada

Muestra la ventana de configuración avanzada del equipo (véase la documentación **Resumen de domótica**).

## Pedido

*Sin ajustes específicos de visualización*

### Configuración avanzada

Muestra la ventana de configuración avanzada del equipo (véase la documentación **Resumen de domótica**).

## Imagen/Cámara

### Parámetros de visualización específicos

- **Mostrar**: Define lo que quieres mostrar, ya sea una imagen fija o la señal en directo de una cámara.
- **Imagen**: Permite enviar la imagen en cuestión (si has seleccionado una imagen).
- **Cámara**: Cámara que se va a mostrar (si has seleccionado una cámara).

## Zona

### Parámetros de visualización específicos

- **Tipo de zona**: Aquí es donde se elige el tipo de zona: macro simple, macro binaria o widget al pasar el cursor por encima.

### Macro sencilla

En este modo, al hacer clic en el área se ejecutan una o varias acciones. Solo tienes que indicar aquí la lista de acciones que deben realizarse al hacer clic en el área.

### Macro binaria

En este modo, Jeedom ejecutará la acción o acciones «On» o «Off» en función del estado del comando que indiques. Por ejemplo: si el comando es 0, Jeedom ejecutará la acción o acciones «On»; en caso contrario, ejecutará la acción o acciones «Off».

- **Información binaria**: Comando que indica el estado que hay que comprobar para decidir qué acción realizar (encendido o apagado).

Solo tienes que indicar a continuación las acciones que se deben realizar para encender y para apagar.

### Widget al pasar el cursor por encima

En este modo, al pasar el cursor por encima o hacer clic en la zona de Jeedom, se mostrará el widget en cuestión.

- **Equipamiento**: Widget que se muestra al pasar el cursor por encima o al hacer clic.
- **Mostrar al pasar el cursor**: Si se marca, se muestra el widget al pasar el cursor.
- **Mostrar al hacer clic**: si está marcado, el widget se muestra al hacer clic.
- **Posición**: Permite elegir la ubicación en la que aparecerá el widget (por defecto, abajo a la derecha).

## Resumen

### Parámetros de visualización específicos

- **Enlace**: Permite indicar el resumen que se mostrará (General para el conjunto; de lo contrario, indicar el objeto).
- **Color de fondo**: Permite cambiar el color de fondo o hacerlo transparente; no olvides desactivar la opción «Predeterminado».
- **Color del texto**: Permite cambiar el color de los iconos y los textos (asegúrate de cambiar la opción «Predeterminado» a «No»).
- **Redondear las esquinas (no olvides indicar el %, p. ej., 50 %)**: Permite redondear las esquinas; no olvides indicar el %.
- **Borde (atención a la sintaxis CSS, p. ej.: solid 1px black)**: Permite añadir un borde; ten en cuenta que la sintaxis es HTML.
- **Tamaño de la fuente (p. ej., 50 %, hay que poner el signo %)**: Permite modificar el tamaño de la fuente.
- **Negrita**: Pone el texto en negrita.

## Preguntas frecuentes

>**Ya no puedo editar mi diseño**
>Si has colocado un widget o una imagen que ocupa prácticamente todo el diseño, tienes que hacer clic fuera del widget o de la imagen para acceder al menú con el botón derecho del ratón.

>**Eliminar un diseño que ya no funciona**
>En la sección de administración, en «OS/DB», ejecuta «select * from planHeader», obtén el ID del diseño en cuestión y ejecuta «delete from planHeader where id=#TODO#» y «delete from plan where planHeader_id=#todo#», sustituyendo #TODO# por el ID del diseño que has encontrado anteriormente.
