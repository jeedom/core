# Historia
**Análisis → Historial**

Una parte importante de un software es el historial, que constituye su verdadera memoria. En Jeedom es posible registrar en el historial cualquier comando de tipo informativo (binario o numérico). Esto te permitirá, por ejemplo, registrar una curva de temperatura, el consumo, las aperturas de una puerta, etc.

![Historia](../images/history.gif)

### Principio de historización

### Archivo

El archivado de datos permite a Jeedom reducir la cantidad de datos almacenados en la memoria. De este modo, se evita ocupar demasiado espacio y ralentizar el sistema. De hecho, si se guardan todas las mediciones, hay más puntos que mostrar y, por lo tanto, el tiempo de generación de un gráfico puede alargarse considerablemente. Si el número de puntos es demasiado elevado, incluso puede provocar que el gráfico deje de mostrarse.

El archivado es una tarea que se inicia por la noche y comprime los datos recopilados durante el día. Por defecto, Jeedom recopila todos los datos con más de 2 horas de antigüedad y los agrupa en paquetes de 1 hora (ya sea una media, un mínimo o un máximo, según la configuración). Por lo tanto, aquí tenemos dos parámetros: uno para el tamaño de los paquetes y otro para determinar a partir de cuándo crearlos (como recordatorio, por defecto se trata de paquetes de 1 hora con datos de más de 2 horas de antigüedad).

> **Consejo**
>
> Si has seguido bien las instrucciones, deberías tener una alta precisión solo en las últimas dos horas. Sin embargo, cuando me conecto a las 17:00, tengo una precisión de las últimas 17 horas. ¿Por qué? De hecho, para evitar consumir recursos innecesariamente, la tarea de archivado solo se lleva a cabo una vez al día, por la tarde.

> **Importante**
>
> Por supuesto, este principio de archivo solo se aplica a los comandos de tipo numérico. En el caso de los comandos de tipo binario, Jeedom solo guarda las fechas en las que se produce el cambio de estado.

### Visualización de un gráfico

Hay varias formas de acceder al historial:

- Al hacer clic en el comando deseado de un widget,
- Al acceder a la página de historial, que permite superponer diferentes curvas y combinar estilos (área, curva, barra),
- En el móvil, mantén pulsado el widget en cuestión,
- Al incluir un gráfico de zona en una vista (véase más abajo),
- Al insertar un gráfico en un diseño.

Desde la versión v4.2 de Core, también es posible mostrar una curva en el fondo de la ficha de un dispositivo.

## Historia

Si visualizas un gráfico desde la página de historial, tendrás acceso a varias opciones de visualización, situadas encima del gráfico:

- **Período**: El período de visualización, que incluye los datos históricos comprendidos entre estas dos fechas. Por defecto, según el parámetro *Período de visualización de gráficos por defecto* en *Ajustes → Sistema → Configuración / Equipos*.
- **Agrupación**: Ofrece varias opciones de agrupación (suma por hora, etc.).
- **Tipo de visualización**: Visualización en *línea*, *área* o *barra*. Opción guardada en el control y utilizada desde el panel de control.
- **Variación**: Muestra la diferencia de valor respecto al punto anterior. Opción guardada en el control y utilizada desde el panel de control.
- **Escalera**: Permite mostrar la curva en forma de escalera o de gráfica continua. Opción guardada en el control y utilizable desde el panel de control.
- **Comparar**: Permite comparar la curva entre diferentes periodos.

> **Consejo**
>
> Para evitar cualquier error de manejo, estas opciones guardadas en los controles solo se activan cuando se muestra una única curva.
>
En la parte superior, donde se muestran las curvas, también hay varias opciones:

A la izquierda:

- **Zoom**: Un área de accesos directos que permite ajustar el zoom horizontal al tiempo deseado, siempre que los datos estén cargados.

A la derecha:

- **Ejes verticales visibles**: Permite ocultar o mostrar todos los ejes verticales.
- **Escala de los ejes verticales**: Permite activar o desactivar el escalado de cada eje vertical de forma independiente respecto a los demás.
- **Agrupación de ejes verticales por unidades**: permite agrupar la escala de las curvas y los ejes verticales en función de su unidad. Todas las curvas de la misma unidad tendrán la misma escala.
- **Opacidad de las curvas al pasar el ratón**: Permite desactivar el resaltado de la curva cuando se muestra un valor al pasar el cursor del ratón. Por ejemplo, cuando dos curvas no tienen sus valores en los mismos momentos.

Debajo de las curvas, también puedes utilizar el menú contextual de cada leyenda para aislar una curva, mostrar u ocultar su eje, cambiar su color, etc.

### Gráfico sobre vistas y diseños

También puedes mostrar los gráficos en las vistas (aquí veremos las opciones de configuración y no cómo hacerlo; para ello, debes consultar la documentación sobre vistas o diseños, según corresponda). Estas son las opciones:

Una vez activada una opción, puedes elegir:
- **Color**: El color de la curva.
- **Tipo**: El tipo de gráfico (de área, de líneas o de columnas).
- **Escala**: Dado que se pueden representar varias curvas (datos) en el mismo gráfico, es posible distinguir entre las escalas (derecha o izquierda).
- **Escalera**: Permite visualizar la curva en forma de escalera o de gráfico continuo.
- **Apilar**: Permite apilar los valores de las curvas (véase más abajo el resultado).
- **Variación**: Muestra la diferencia de valor con respecto al punto anterior.

### Opción en la página de historial

La página de historial ofrece acceso a algunas opciones adicionales

#### Historial calculado

Permite mostrar una curva basada en un cálculo sobre varios comandos (se puede hacer prácticamente de todo: +, -, /, *, valor absoluto… Consulta la documentación de PHP para algunas funciones). Por ejemplo:

`abs(*\[Jardin\]\[Hygrometrie\]\[Température\]* - *\[Espace de vie\]\[Hygrométrie\]\[Température\]*)`

También tienes acceso a una función de gestión de fórmulas de cálculo que te permite guardarlas para volver a visualizarlas más fácilmente.

> **Consejo**
>
> Cuando tienes cálculos guardados, estos aparecen a la izquierda, en **Mis cálculos**.

#### Historial de pedidos

- Delante de cada dato que se puede visualizar, encontrarás un icono de **papelera** que te permite eliminar los datos guardados; al hacer clic en él, Jeedom te preguntará si quieres eliminar los datos anteriores a una fecha determinada o todos los datos.
- En **Configuración**, a la derecha de cada dato, encontrarás un icono de **flecha** que te permite exportar los datos históricos a un archivo CSV.

### Eliminación de valores incoherentes

En ocasiones, es posible que aparezcan valores incoherentes en los gráficos. Esto suele deberse a un problema de interpretación del valor. Es posible eliminar o modificar el valor del punto en cuestión haciendo clic directamente sobre él en el gráfico; además, puedes ajustar los valores mínimo y máximo permitidos para evitar problemas en el futuro.


