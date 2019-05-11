La page Analyse d’équipements accessible depuis le menu Analyse → Equipements vous permet de voir de nombreuses infos
relatives aux équipements de manière centralisée :

-   el estado de la batería

-   las alertas módulos

-   las acciones definidas

-   alertas definidas

-   órdenes huérfanos

La pestaña Baterías
====================

Se puede ver en esta pestaña lista de sus módulos de batería
su nivel restante (el color de la baldosa depende de ese nivel), la
tipo y cantidad de baterías para poner en el módulo, el tipo de
módulo y la información de fecha de la carga de la batería
se ha actualizado. También puede ver si un determinado umbral era
establecido para el módulo en particular (representado por una mano)

> **Tip**
>
> Los umbrales de alerta / advertencia en los niveles de la batería
> A nivel mundial configurable en el Jeedom configuración
> (Pestaña Administración → Instalaciones), o el equipo en la página
> Configuración avanzada del mismo en la pestaña de alertas.

La pestaña Módulos en alerta
==========================

En esta pestaña se verá módulos de alerta en tiempo real. la
Las alertas pueden ser de diferentes tipos:

-   tiempo de espera (configurado en la pestaña de alertas definido)

-   aviso de batería o en peligro de extinción

-   advertencia o peligro de comandos (configurable en los ajustes
    comandos avanzados)

Otros tipos de alertas finalmente va a terminar aquí.
Cada alerta estará representada por el color de la baldosa (el nivel
Alerta) y un logotipo en la parte superior izquierda (tipo de aviso)

> **Tip**
>
> A continuación, se publicarán todos alerta incluso los módulos configurados
> "No visible". Sin embargo, es interesante observar que si el módulo
> ¿Es "visible" alerta también será visible en el salpicadero (en
> El objeto en cuestión)

La ficha Acciones definido
=========================

Este registro le permite ver las acciones definidas directamente en una
mando. De hecho, se puede poner en diferentes órdenes y
puede ser difícil recordar todo. Esta ficha es sólo eso
y sintetiza varias cosas:

-   acciones sobre el estado (que se encuentra en la configuración avanzada
    información y comandos para realizar uno o más
    acciones sobre el valor de una orden - de forma inmediata o después de
    un retraso)

-   confirmaciones de patrimonio (configurables en el mismo lugar en una
    controlar la información y para pedir la confirmación
    realizar una acción)

-   confirmaciones con código (igual que antes, pero con
    introducción de un código)

-   la acción pre y post (configurable siempre en el mismo lugar de
    comando de acción y para la realización de uno o varios de los demás
    acciones antes o después de la acción en cuestión)

> **Tip**
>
> En la tabla le permite ver las acciones tanto de texto
> Definido. Se pueden añadir otros tipos de acciones definidas.

La ficha Alertas definido
=========================

Esta ficha le permite ver todas las alertas definidas, se quiere
encontrará una tabla con la siguiente información si está disponible:

-   el retraso en la comunicación de alertas

-   umbrales específicos de baterías en el equipo

-   varias órdenes de alertas de peligro y advertencias

El huérfano ficha Comandos
=============================

Esta ficha le permite ver de un vistazo si usted tiene
comandos huérfanos utilizan a través de Jeedom. Un pedido
Huérfano es un comando utilizado en alguna parte, pero que ya no existe.
Aquí encontramos todos estos comandos, tales como:

-   huérfanos comandos utilizados en el cuerpo de un escenario

-   los utilizados en un escenario de gatillo

Y se utiliza en muchos otros lugares como (no exhaustiva):

-   interacciones

-   jeedom configuración

-   en la acción pre o post de un control

-   acción sobre el estado del pedido

-   en algunos plugins

> **Tip**
>
> En la tabla le permite ver los comandos de texto tanto
> Huérfano. Su objetivo es identificar rápidamente todos
> órdenes "huérfanos" a través de toda Jeedom y plugins. El se
> Algunas áreas no pueden ser analizados, la tabla será
> Ser más amplia en el tiempo.
