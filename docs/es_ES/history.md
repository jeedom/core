# Historique
**Análisis → Historia**

Parte importante en el software : la parte de la historización, un verdadero recuerdo de ella. En Jeedom es posible registrar cualquier comando de tipo de información (binario o digital). Esto le permitirá, por ejemplo, registrar una temperatura, consumo o curva de apertura de puerta, etc

### Principe

Aquí se describe el principio de historización de Jeedom. Solo necesita comprender esto si tiene problemas de historización o si desea cambiar la configuración de historización. La configuración predeterminada está bien en la mayoría de los casos.

### Archivage

El archivo de datos le permite a Jeedom reducir la cantidad de datos almacenados en la memoria. Esto permite no utilizar demasiado espacio y no ralentiza el sistema. De hecho, si mantiene todas las mediciones, esto aumenta la cantidad de puntos para mostrar y, por lo tanto, puede alargar considerablemente los tiempos para representar un gráfico. Si hay demasiados puntos, incluso puede hacer que la pantalla del gráfico se bloquee.

El archivado es una tarea que comienza de noche y compacta los datos recuperados durante el día. De forma predeterminada, Jeedom recupera todos los datos anteriores de 2 horas y crea paquetes de 1 hora (ya sea un promedio, un mínimo o un máximo dependiendo de la configuración). Así que aquí tenemos dos parámetros, uno para el tamaño del paquete y otro para saber cuándo hacerlo (por defecto, estos son paquetes de 1 hora con datos que tienen más de 2 horas de antigüedad).

> **Punta**
>
> Si ha seguido bien, debe tener una alta precisión solo en las últimas 2 horas. Sin embargo, cuando me conecto a las 5 p.m., tengo precisión en las últimas 17 horas. Por qué ? De hecho, para evitar consumir recursos innecesariamente, la tarea de archivo se lleva a cabo solo una vez al día, por la noche.

> **Importante**
>
> Por supuesto, este principio de archivo solo se aplica a los comandos de tipo digital; en comandos de tipo binario, Jeedom mantiene solo las fechas de cambio de estado.

### Ver un gráfico

Hay varias formas de acceder al historial :

- Haciendo clic en el comando deseado en un widget,
- Al ir a la página del historial que permite superponer diferentes curvas y combinar estilos (área, curva, barra)),
- En el dispositivo móvil mientras permanece presionado en el widget en cuestión,
- Al poner un área de gráfico en una vista (ver abajo).

## Pestaña Historial

Si muestra un gráfico junto a la página del historial, tiene acceso a varias opciones de visualización :

Encontramos en la parte superior derecha el período de visualización (aquí en la última semana porque, por defecto, quiero que sea solo una semana, ver 2 párrafos anteriores), luego vienen los parámetros de la curva (estos parámetros se mantienen de una pantalla a otra, por lo que solo tiene que configurarlas una vez).

- **Escalera** : Muestra la curva como una escalera o una pantalla continua.
- **Cambio** : Muestra la diferencia de valor del punto anterior.
- **Línea** : Muestra el gráfico como líneas.
- **área** : Muestra el gráfico como un área.
- **Columna**\* : Muestra el gráfico como barras.

> **Punta**
>
> Si visualiza varias curvas al mismo tiempo:
> - Haga clic en una leyenda debajo del gráfico para mostrar / ocultar esta curva.
> - Ctrl Haga clic en una leyenda le permite mostrar solo esta.
> - Alt Click en una leyenda le permite mostrarlos todos.


### Gráfico sobre vistas y diseños

También puede mostrar los gráficos en las vistas (veremos aquí las opciones de configuración y no cómo hacerlo, para eso debe ir a la documentación de las vistas o diseños en función). Estas son las opciones :

Una vez que se activan los datos, puede elegir :
- **Color** : El color de la curva.
- **Tipo** : El tipo de gráfico (área, línea o columna).
- **Escala** : Como puede colocar varias curvas (datos) en el mismo gráfico, es posible distinguir las escalas (derecha o izquierda).
- **Escalera** : Muestra la curva como una escalera o una pantalla continua.
- **Montón** : Apila los valores de las curvas (ver abajo el resultado).
- **Cambio** : Muestra la diferencia de valor del punto anterior.

### Opción en la página del historial

La página del historial da acceso a algunas opciones adicionales

#### Historia calculada

Le permite mostrar una curva de acuerdo con un cálculo en varios comandos (puede hacer casi todo, + - / \* valor absoluto ... consulte la documentación de PHP para ciertas funciones).
Ex :
abs(*\ [Jardín \] \ [Higrometría \] \ [Temperatura \]* - *\ [Espacio vital \] \ [Higrometría \] \ [Temperatura \]*)

También tiene acceso a una gestión de fórmulas de cálculo que le permite guardarlas para volver a mostrarlas más fácilmente.

> **Punta**
>
> Simplemente haga clic en el nombre del objeto para desplegarlo y muestre los comandos históricos que se pueden mostrar.

#### Historial de pedidos

Frente a cada dato que se puede mostrar, encontrará dos íconos :

- **Cubo de basura** : Le permite eliminar los datos grabados; Al hacer clic, Jeedom pregunta si eliminar los datos antes de una fecha determinada o todos los datos.
- **Flecha** : Permite tener una exportación CSV de datos históricos.

### Eliminación de valor inconsistente

A veces puede tener valores inconsistentes en los gráficos. Esto a menudo se debe a una preocupación por interpretar el valor. Es posible eliminar o cambiar el valor del punto en cuestión, haciendo clic directamente en el gráfico; Además, puede ajustar el mínimo y el máximo permitido para evitar problemas futuros.

## Pestaña Línea de tiempo

La línea de tiempo muestra ciertos eventos en su domótica en forma cronológica.

Para verlos, primero debe activar el seguimiento en la línea de tiempo de los comandos o escenarios deseados, luego ocurren estos eventos.

- **Guión** : Ya sea directamente en la página del escenario o en la página de resumen del escenario para hacerlo de forma masiva".
- **Orden** : Ya sea en la configuración avanzada del comando o en la configuración del historial para hacerlo en "masa".

La linea de tiempo *Primario* siempre contiene todos los eventos. Sin embargo, puede filtrar la línea de tiempo por *Carpetas*. En cada lugar donde active la línea de tiempo, tendrá un campo para ingresar el nombre de una carpeta, existente o no.
Luego puede filtrar la línea de tiempo por esta carpeta seleccionándola a la izquierda del botón *Refrescar*.

> **Nota**
>
> Si ya no usa una carpeta, aparecerá en la lista mientras existan eventos vinculados a esta carpeta. Desaparecerá de la lista por sí mismo.

> **Punta**
>
> Tiene acceso a las ventanas de resumen del escenario o de configuración del historial directamente desde la página de la línea de tiempo.

Una vez que haya activado el seguimiento en la línea de tiempo de los comandos y escenarios que desee, podrá verlos aparecer en la línea de tiempo.

> **Importante**
>
> Debe esperar nuevos eventos después de activar el seguimiento en la línea de tiempo antes de verlos aparecer.

### Affichage

La línea de tiempo muestra una tabla de eventos grabados en tres columnas:

- La fecha y hora del evento,
- El tipo de evento: Un comando de información o acción, o un escenario, con el complemento de comando para comandos.
- El nombre del objeto principal, el nombre y, según el tipo, el estado o el desencadenante.

- Un evento de tipo de comando muestra un icono a la derecha para abrir la configuración del comando.
- Un evento de tipo de escenario muestra dos iconos a la derecha para ir al escenario o abrir el registro del escenario.

