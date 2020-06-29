Parte importante en el software : la parte de historización, real
recuerdo de ello. En Jeedom es posible historizar cualquier
qué comando de tipo de información (binario o digital). Que tu
por lo tanto, permitirá, por ejemplo, historizar una curva de temperatura,
consumo o apertura de puertas

Principio 
========

Aquí se describe el principio de historización de Jeedom. No es
necesario entender que si tiene alguna inquietud
o desea cambiar la configuración de
historización. La configuración predeterminada es adecuada para la mayoría
cas.

Archivado 
---------

El archivo de datos le permite a Jeedom reducir la cantidad de datos
guardado en la memoria. Esto permite no usar demasiado espacio y
no ralentizar el sistema. De hecho, si mantiene todos los
medidas, esto hace más puntos para mostrar y por lo tanto puede
alargar drásticamente los tiempos para hacer un gráfico. En caso
demasiados puntos, incluso puede bloquearse
visualización gráfica.

Archivar es una tarea que comienza de noche y compacta
datos recuperados durante el día. Por defecto, Jeedom recupera todo
Datos 2h más antiguos y paquetes de 1h (uno
promedio, mínimo o máximo según la configuración). Entonces tenemos
Aquí 2 parámetros, uno para el tamaño del paquete y otro para conocer
cuándo hacerlo (por defecto, estos son paquetes
1 hora con datos que tienen más de 2 horas de antigüedad).

> **Punta**
>
> Si ha seguido bien, debe tener una alta precisión en el
> Últimas 2 horas solamente. Sin embargo, cuando inicio sesión a las 5 p.m,
> Tengo una aclaración sobre las últimas 17 horas. Por qué ? De hecho,
> para evitar consumir recursos innecesariamente, la tarea que hace
> el archivo se realiza solo una vez al día, por la tarde.

> **Importante**
>
> Por supuesto, este principio de archivo solo se aplica a pedidos de
> tipo digital; en comandos de tipo binario, Jeedom no mantiene
> que las fechas de cambio de estado.

Ver un gráfico 
========================

Hay varias formas de acceder al historial :

-   Al poner un área de gráfico en una vista (ver abajo),

-   haciendo clic en el comando deseado en un widget,

-   yendo a la página del historial que permite superponer
    diferentes curvas y estilos combinados (área, curva, barra)

-   en el dispositivo móvil mientras permanece presionado en el widget en cuestión

Si muestra un gráfico junto a la página histórica o haciendo clic en
el widget, tiene acceso a varias opciones de visualización :

Encontramos en la parte superior derecha el período de visualización (aquí en el último
semana porque por defecto quiero que sea solo una semana - ver
2 párrafos anteriores), luego vienen los parámetros de la curva
(estos parámetros se guardan de una pantalla a otra; entonces no lo haces
que configurarlos una vez).

-   **Escalera** : muestra la curva como un
    escalera o pantalla continua.

-   **Cambio** : muestra la diferencia en valor de
    punto anterior.

-   **Línea** : muestra el gráfico como líneas.

-   **área** : muestra el gráfico como un área.

-   **Columna**\* : muestra el gráfico como barras.

Gráfico sobre vistas y diseños 
=====================================

También puede mostrar los gráficos en las vistas (veremos aquí
las opciones de configuración y no cómo hacerlo, para eso tienes que
renderizar vistas o diseños basados en la documentación). aquí está
las opciones :

Una vez que se activan los datos, puede elegir :

-   **Color** : el color de la curva.

-   **Tipo** : El tipo de gráfico (área, línea o columna).

-   **Escala** : ya que puedes poner varias curvas (datos)
    en el mismo gráfico, es posible distinguir las escalas
    (derecha o izquierda).

-   **Escalera** : muestra la curva como un
    escalera o pantalla continua

-   **Montón** : permite apilar los valores de las curvas (ver en
    a continuación para el resultado).

-   **Cambio** : muestra la diferencia en valor de
    punto anterior.

Opción en la página del historial 
===============================

La página del historial da acceso a algunas opciones adicionales

Historia calculada 
------------------

Se usa para mostrar una curva basada en un cálculo en varios
comando (puedes hacer casi todo, + - / \* valor absoluto ... ver
Documentación PHP para ciertas funciones). Ex :
abs(*\ [Jardín \] \ [Higrometría \] \ [Temperatura \]* - *\ [Espacio de
vida \] \ [Higrometría \] \ [Temperatura \]*)

También tiene acceso a una gestión de fórmulas de cálculo que le permite
guárdelos para verlos más fácilmente

> **Punta**
>
> Simplemente haga clic en el nombre del objeto para desplegarlo;
> aparecen los comandos históricos que se pueden graficar.

Historial de pedidos 
----------------------

Frente a cada dato que se puede graficar, encontrará dos íconos :

-   **Cubo de basura** : permite eliminar los datos grabados; entonces
    del clic, Jeedom pregunta si es necesario eliminar los datos antes de un
    cierta fecha o todos los datos.

-   **Flecha** : permite tener una exportación CSV de datos históricos.

Eliminación de valor inconsistente 
=================================

A veces puede tener valores inconsistentes en el
gráficos. Esto a menudo se debe a una preocupación con la interpretación de la
valor. Es posible eliminar o cambiar el valor del punto por
pregunta, haciendo clic directamente en el gráfico; de
más, puede establecer el mínimo y el máximo permitidos para
evitar problemas futuros.

Línea de tiempo 
========

La línea de tiempo muestra ciertos eventos en su domótica en el formulario
chronologique.

Para verlos, primero debe activar el seguimiento en la línea de tiempo de
comandos o escenarios deseados :

-   **Guión** : ya sea directamente en la página del escenario o en el
    página de resumen del escenario para hacerlo en "masa"

-   **Orden** : ya sea en la configuración avanzada del comando,
    ya sea en la configuración de la historia para hacerlo en "masa"

> **Punta**
>
> Tiene acceso a las ventanas de resumen de los escenarios o del
> configuración del historial directamente desde la página
> Línea de tiempo.

Una vez que haya habilitado el seguimiento en la línea de tiempo del pedido y
escenarios deseados, puede verlos aparecer en la línea de tiempo.

> **Importante**
>
> Debe esperar nuevos eventos después de activar el seguimiento
> en la línea de tiempo antes de verlos aparecer.

Las tarjetas en la línea de tiempo muestran :

-   **Comando de acción** : en fondo rojo, un ícono a la derecha le permite
    muestra la ventana de configuración avanzada del comando

-   **Comando de información** : en fondo azul, un icono a la derecha le permite
    muestra la ventana de configuración avanzada del comando

-   **Guión** : en fondo gris, tienes 2 iconos : uno para mostrar
    el registro del escenario y uno para ir al escenario


