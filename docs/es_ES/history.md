# Historique
**Análisis → Historia**

Parte importante en el software : la parte de la historización, un verdadero recuerdo de ella. En Jeedom es posible registrar cualquier comando de tipo de información (binario o digital). Esto te permitirá, por ejemplo, registrar una curva de temperatura, consumos, aperturas de una puerta, etc.

![Histórico](./images/history.gif)

### Principio de historización

### Archivage

El archivo de datos permite a Jeedom reducir la cantidad de datos almacenados en la memoria. Esto permite no utilizar demasiado espacio y no ralentiza el sistema. De hecho, si mantiene todas las mediciones, esto aumenta la cantidad de puntos para mostrar y, por lo tanto, puede alargar considerablemente los tiempos para representar un gráfico. Si hay demasiados puntos, incluso puede hacer que la pantalla del gráfico se bloquee.

El archivado es una tarea que comienza de noche y compacta los datos recuperados durante el día. De forma predeterminada, Jeedom recupera todos los datos anteriores de 2 horas y crea paquetes de 1 hora (ya sea un promedio, un mínimo o un máximo dependiendo de la configuración). Así que aquí tenemos dos parámetros, uno para el tamaño del paquete y otro para saber cuándo hacerlo (por defecto, estos son paquetes de 1 hora con datos que tienen más de 2 horas de antigüedad).

> **Consejo**
>
> Si ha seguido bien, debe tener una alta precisión solo en las últimas 2 horas. Sin embargo, cuando me conecto a las 5 p.m., tengo precisión en las últimas 17 horas. Para qué ? De hecho, para evitar consumir recursos innecesariamente, la tarea de archivo se lleva a cabo solo una vez al día, por la noche.

> **Importante**
>
> Por supuesto, este principio de archivo solo se aplica a los pedidos digitales. En órdenes de tipo binario, Jeedom solo mantiene las fechas de cambio de estado.

### Ver un gráfico

Hay varias formas de acceder al historial :

- Al hacer clic en el comando deseado en un widget,
- Al ir a la página del historial, que le permite superponer diferentes curvas y combinar estilos (área, curva, barra),
- En el dispositivo móvil mientras permanece presionado en el widget en cuestión,
- Poniendo un área de gráfico en una vista (ver más abajo),
- Insertando un gráfico en un diseño.

Desde Core v4.2 también es posible mostrar una curva en la parte inferior del mosaico de un dispositivo.

## Historique

Si muestra un gráfico a través de la página del historial, tiene acceso a varias opciones de visualización, arriba del gráfico :

- **Período** : El período de visualización, incluidos los datos históricos entre estas dos fechas. Por defecto, dependiendo de la configuración *Período de visualización de los gráficos por defecto* En *Configuración → Sistema → Configuración / Equipo*.
- **Grupo** : Ofrece varias opciones de agrupación (Suma por hora, etc.).
- **Tipo de visualización** : Mostrar en *Línea*, *Área*, O *Vara*. Opción guardada en el pedido y utilizada desde el Tablero.
- **Variación** : Muestra la diferencia de valor del punto anterior. Opción guardada en el pedido y utilizada desde el Tablero.
- **Escaleras** : Muestra la curva como una escalera o una pantalla continua. Opción guardada en el pedido y utilizada desde el Tablero.
- **Comparar** : Compara la curva entre diferentes períodos.

> **Consejo**
>
> Para evitar cualquier error de manejo, estas opciones guardadas en los comandos solo están activas cuando se muestra una sola curva.
> 
En la parte superior donde se muestran las curvas, también hay varias opciones :

A la izquierda:

- **Zoom** : Un área de acceso directo que le permite ajustar el zoom horizontal a la duración deseada, si los datos están cargados.

Sobre la derecha:

- **Ejes verticales visibles** : Le permite ocultar o mostrar todos los ejes verticales.
- **Escala de eje vertical** : Permite activar o no el escalado de cada eje vertical independientemente de los demás.
- **Agrupación de ejes verticales por unidades** : Permite agrupar la escala de curvas y ejes verticales según su unidad. Todas las curvas con la misma unidad tendrán la misma escala.
- **Opacidad de las curvas debajo del mouse** : Le permite desactivar el resaltado de la curva cuando se muestra un valor en el cursor del mouse. Por ejemplo, cuando dos curvas no tienen sus valores al mismo tiempo.

Debajo de las curvas, también puede usar el menú contextual en cada leyenda para aislar una curva, mostrar / ocultar su eje, cambiar su color, ...

### Gráfico sobre vistas y diseños

También puede mostrar los gráficos en las vistas (veremos aquí las opciones de configuración y no cómo hacerlo, para eso debe ir a la documentación de las vistas o diseños en función). Estas son las opciones :

Una vez que se activan los datos, puede elegir :
- **Color** : El color de la curva.
- **Amable** : El tipo de gráfico (área, línea o columna).
- **Escalera** : Como puede colocar varias curvas (datos) en el mismo gráfico, es posible distinguir las escalas (derecha o izquierda).
- **Escaleras** : Muestra la curva como una escalera o una pantalla continua.
- **Pila** : Apila los valores de las curvas (ver abajo el resultado).
- **Variación** : Muestra la diferencia de valor del punto anterior.

### Opción en la página del historial

La página del historial da acceso a algunas opciones adicionales

#### Historia calculada

Permite mostrar una curva de acuerdo con un cálculo en varios comandos (puede hacer prácticamente cualquier cosa, + - / \* valor absoluto ... consulte la documentación de PHP para conocer algunas funciones). Por ejemplo :

`abs(*\ [Jardín \] \ [Higrometría \] \ [Temperatura \]* - *\ [Espacio vital \] \ [Higrometría \] \ [Temperatura \]*)`

También tiene acceso a una gestión de fórmulas de cálculo que le permite guardarlas para volver a mostrarlas más fácilmente.

> **Consejo**
>
> Cuando haya guardado los cálculos, estos estarán disponibles a la izquierda en **Mis calculos**.

#### Historial de pedidos

Frente a cada dato que se puede mostrar, encontrará dos íconos :

- **Cubo de la basura** : Le permite eliminar los datos grabados; Al hacer clic, Jeedom pregunta si eliminar los datos antes de una fecha determinada o todos los datos.
- **Flecha** : Permite la exportación CSV de datos históricos.

### Eliminación de valor inconsistente

A veces puede tener valores inconsistentes en los gráficos. Esto a menudo se debe a una preocupación por interpretar el valor. Es posible eliminar o cambiar el valor del punto en cuestión, haciendo clic directamente en el gráfico; Además, puede ajustar el mínimo y el máximo permitido para evitar problemas futuros.


