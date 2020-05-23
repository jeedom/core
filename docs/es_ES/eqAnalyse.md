La página de Análisis de equipo le permite ver mucha información
en relación con el equipo centralmente :

-   el estado de tus baterías

-   módulos en alerta

-   acciones definidas

-   alertas definidas

-   órdenes huérfanas

La pestaña Baterías 
==================

Puede ver en esta pestaña la lista de sus módulos con batería,
su nivel restante (el color del mosaico depende de este nivel), el
tipo y número de baterías que se insertarán en el módulo, el tipo de
módulo, así como la fecha en que la información del nivel de batería
ha sido actualizado. También puede ver si se ha establecido un umbral específico
banco de trabajo para el módulo particular (representado por una mano)

> **Punta**
>
> Los umbrales de alerta / advertencia en los niveles de batería son
> configurable globalmente en la configuración de Jeedom
> (Administración → pestaña Equipo), o por equipo en la página
> configuración avanzada de estos en la pestaña de alertas.

Módulos en la pestaña de alerta 
==========================

En esta pestaña verá en tiempo real los módulos en alerta. la
las alertas pueden ser de diferentes tipos :

-   Tiempo de espera (configurado en la pestaña de alertas definidas)

-   batería en advertencia o en peligro

-   comando en advertencia o peligro (configurable en los parámetros
    comandos avanzados)

Otros tipos de alertas se pueden encontrar aquí.
Cada alerta estará representada por el color del mosaico (el nivel
alerta) y un logotipo en la parte superior izquierda (el tipo de alerta)

> **Punta**
>
> Aquí se mostrarán todos los módulos en alerta, incluso aquellos configurados en
> "no visible". Sin embargo, es interesante notar que si el módulo
> está &quot;visible&quot;, la alerta también estará visible en el panel de control (en
> el objeto en cuestión)

La pestaña Acciones definidas 
=========================

Esta pestaña le permite ver las acciones definidas directamente en un
mando. De hecho, podemos hacer diferentes pedidos y
puede ser difícil de recordar todo. Esta pestaña está ahí para eso
y sintetiza varias cosas :

-   acciones en estado (encontradas en parámetros avanzados
    comandos de información y se utilizan para realizar uno o más
    acciones sobre el valor de un pedido, inmediatamente o después
    un retraso)

-   confirmaciones de acciones (configurables en el mismo lugar en un
    información del comando y permitir solicitar una confirmación para
    realizar una acción)

-   confirmaciones con código (igual que el anterior pero con
    ingresando un código)

-   acciones pre y post (siempre configurables en el mismo lugar en
    un comando de acción y que permite ejecutar uno o más otros
    acciones antes o después de la acción en cuestión)

> **Punta**
>
> La tabla le permite ver muy textualmente las acciones
> definido. Se pueden agregar otros tipos de acciones definidas.

La pestaña Alertas definidas 
=========================

Esta pestaña le permite ver todas las alertas definidas, usted
encuentra en una tabla la siguiente información si existe :

-   alertas de retraso de comunicación

-   umbrales de batería específicos definidos en un dispositivo

-   las diversas alertas de peligro y comandos de advertencia

La pestaña Orphan Orders 
=============================

Esta pestaña le permite ver de un vistazo si tiene alguna
comandos huérfanos utilizados a través de Jeedom. Un pedido
huérfano es un comando utilizado en algún lugar pero que ya no existe.
Encontraremos aquí todos estos comandos, como por ejemplo :

-   comandos huérfanos utilizados en el cuerpo de un escenario

-   los utilizados para desencadenar un escenario

Y se usa en muchos otros lugares como (no exhaustivo) :

-   interacciones

-   configuraciones de libertad

-   en pre o post acción de una orden

-   en acción sobre el estado del pedido

-   en algunos complementos

> **Punta**
>
> La tabla proporciona una vista muy textual de los comandos
> huérfano. Su propósito es poder identificar rápidamente todos los
> comandos &quot;huérfanos&quot; a través de todos los complementos y libertad. El se
> algunas áreas pueden no ser analizadas, la tabla será
> ser cada vez más exhaustivo con el tiempo.
