parte importante en el software: la parte real de archivado
la memoria del mismo. Es posible historizar cualquier Jeedom
lo que la información de tipo de comando (binaria o digital). esta voluntad
de este modo permitir, por ejemplo, historizar una curva de temperatura de
el consumo o la apertura de una puerta ...

principio
========

Aquí se describe el principio de archivado de Jeedom. es
tienen que entender que si usted está teniendo problemas
para archivar o quiere cambiar los ajustes
historización. Los ajustes por defecto son adecuadas en la mayoría
caso.

archivado
---------

El archivo de datos permite Jeedom reducir la cantidad de datos
almacenado. Esto permite no utilizar demasiado espacio y
no ralentizar el sistema. De hecho, si se mantiene todo el
medidas, se hace aún más puntos a ser visualizados y por lo tanto puede
alargar significativamente el tiempo para hacer un gráfico. En caso
de demasiados puntos, incluso puede bloquearse
visualización de gráficos.

El archivo es una tarea que se lanza a la noche y compacta
Los datos recogidos en el día. Por defecto recupera todos Jeedom
el 2h de datos más antiguo y hace 1h paquete (una
medio, mínimo o máximo dependiendo de la configuración). Por lo tanto,
Aquí dos ajustes, uno para el tamaño del paquete y la otra es saber
desde la hora de hacer (de aviso predeterminado para este son paquetes
1 hora con datos que tiene más de 2 horas de edad).

> **Tip**
>
> Si ha seguido usted debe tener una alta precisión en
> Última 2 horas. Sin embargo, cuando me conecto a 17h,
> Tengo una aclaración sobre las últimas 17 horas. Por qué ? De hecho,
> Para evitar el consumo de recursos innecesariamente, la tarea hecha
> Archivado lleva a cabo sólo una vez al día, por la tarde.

> **Importante**
>
> Por supuesto, este principio archivado sólo se aplica a las órdenes
> Tipo numérico; los comandos binarios, no enlatados Jeedom
> Que los cambios de estado.

Viendo el gráfico
========================

Hay varias maneras de acceder a la historia:

-   poner un área del gráfico en una vista en planta (véase más adelante),

-   haciendo clic en el comando deseado en un widget,

-   por ir a la página de la historia que puede superponer
    diferentes curvas y combinan estilos (área, curva, bar)

-   Móviles restantes soportado en el widget en cuestión

Si ve un gráfico de histórica o haciendo clic
el widget, usted tiene acceso a múltiples opciones de visualización:

Nos encontramos en la parte superior derecha de la duración de visualización (aquí la última
semana porque, por defecto quiero que sea sólo una semana - véase
Dos párrafos anteriores), seguido de los parámetros de la curva
(Estos valores se mantienen de una pantalla a la otra, por lo que hacen
tiene el conjunto una vez).

-   Escalera ** **: muestra la curva como una
    escalera o visualización continua.

-   ** ** Cambio: muestra la diferencia en valor en comparación con
    punto anterior.

-   ** ** Línea: muestra el gráfico en filas.

-   **zona**: muestra la forma gráfica de un área.

-   Columna ** ** \ *: muestra el gráfico de barras.

Gráfico de vistas y diseños
=====================================

También puede ver los gráficos en las vistas (veremos aquí
opciones de configuración y no la forma de hacerlo, por lo que debemos
Ir a las imágenes o diseños en documention). aquí está
opciones:

Una vez activados los datos, se puede elegir:

-   ** ** color: el color de la curva.

-   **Tipo**: el tipo de gráfico (área, fila o columna).

-   ** ** Escala: porque se puede poner varias curvas (datos)
    en el mismo gráfico, es posible distinguir las escalas
    (Derecha o izquierda).

-   Escalera ** **: muestra la curva como una
    escalera o visualización continua

-   ** ** Pila: puede apilar los valores de las curvas (véase
    a continuación para los resultados).

-   ** ** Cambio: muestra la diferencia en valor en comparación con
    punto anterior.

Opción en la página Historial
===============================

La página de historial proporciona acceso a algunas opciones adicionales

historia calculada
------------------

Le permite visualizar una curva de acuerdo con un cálculo en varias
comando (que puede bastante listo para hacer todo, + - / \ * absoluta ... Ver
PHP documentación para determinada función). por ejemplo:
abs (* \ [Jardín \] \ [humedad \] \ [Temperatura \] * - * \ [Espacio
La vida \] \ [Humedad \] \ [Temperatura \] *)

También tiene acceso a una fórmulas de gestión que le permite
guardarlos para el remostrado más fácil

> **Tip**
>
> Basta con hacer clic en el nombre del objeto a desarrollarse;
> Aparecen los comandos que pueden ser historizar graphées.

historial de pedidos
----------------------

Delante de cada dato puede ser graphée, se encuentran dos iconos:

-   ** ** Papelera: Borra los datos grabados; entonces
    el clic Jeedom pregunta si desea eliminar los datos antes de una
    fecha determinada o todos los datos.

-   Flecha ** ** permite a un CSV exportación de datos históricos.

Eliminación de valor inconsistente
=================================

A veces puede ser que usted tiene valores inconsistentes en
gráficos. Esto es a menudo debido a la preocupación por la interpretación de la
valor. Puede eliminar o cambiar el valor del punto
cuestión haciendo clic en él directamente en el gráfico; de
Además, se puede establecer el mínimo y el máximo permitido para
para evitar problemas futuros.

línea de tiempo
========

La línea de tiempo muestra algunos eventos en su casa como la automatización
cronológico.

Para verlos, debe habilitar el seguimiento en la línea de tiempo de la primera
controles o escenarios apropiados:

-   ** ** Escenario: ya sea directamente en la página de la escritura, o en el
    Resumen de una página de escenarios para que la "masa"

-   ** ** Orden ya sea en la configuración de control avanzada,
    ya sea en la historia de configuración para que sea a la "masa"

> **Tip**
>
> Puede acceder a los escenarios ventanas sumarias o
> Historia de la configuración directamente desde la página
> Línea de tiempo.

Una vez que haya participado en la línea de tiempo de los comandos y
escenarios apropiados, verá aparecer en la línea de tiempo.

> **Importante**
>
> Los nuevos eventos después de la activación de la monitorización debe esperar
> En la línea de tiempo antes de ver el espectáculo.

Las tarjetas de los espectáculos de línea de tiempo:

-   ** ** cuota de comandos: fondo rojo, un icono de la derecha que permite
    mostrar la ventana de configuración extendida del control

-   ** ** Info orden: fondo azul, un icono de la derecha que permite
    mostrar la ventana de configuración extendida del control

-   ** ** Escenario: fondo gris, tiene 2 iconos: uno para la pantalla
    el registro de la escritura y para ir en el guión


