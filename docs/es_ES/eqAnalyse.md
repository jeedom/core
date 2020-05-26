# Análisis de equipos
**Análisis → Equipo**

La página de Análisis de equipo le permite ver mucha información relacionada con el equipo de forma centralizada :

- El estado de tus baterías
- Módulos en alerta
- Acciones definidas
- Alertas definidas
- órdenes huérfanas

## Pestaña Baterías


Puede ver en esta pestaña la lista de sus módulos de batería, su nivel restante (el color del mosaico depende de este nivel), el tipo y la cantidad de baterías que deben colocarse en el módulo, el tipo de módulo también que la fecha en que se actualizó la información del nivel de batería. También puede ver si se ha establecido un umbral específico para el módulo en particular (representado por una mano)

> **Punta**
>
> Los umbrales de alerta / advertencia en los niveles de batería se pueden configurar globalmente en la configuración de Jeedom (Configuración → Sistemas → Configuración : Equipo), o por equipo en la página de configuración avanzada de estos en la pestaña de alertas.

## Módulos en la pestaña de alerta

En esta pestaña verá en tiempo real los módulos en alerta. Las alertas pueden ser de diferentes tipos :

- Tiempo de espera (configurado en la pestaña de alertas definidas).
- Batería en advertencia o en peligro.
- Comando de advertencia o peligro (configurable en parámetros de comando avanzados).

Otros tipos de alertas se pueden encontrar aquí.
Cada alerta estará representada por el color del mosaico (el nivel de alerta) y un logotipo en la parte superior izquierda (el tipo de alerta).

> **Punta**
>
> Aquí se mostrarán todos los módulos en alerta, incluso aquellos configurados en "no visible". Sin embargo, es interesante observar que si el módulo está "visible", la alerta también estará visible en el tablero (en el objeto en cuestión)).

## Pestaña Acciones definidas

Esta pestaña le permite ver las acciones definidas directamente en un pedido. De hecho, podemos poner diferentes comandos y puede ser difícil recordar todos. Esta pestaña está ahí para eso y sintetiza varias cosas :

- Acciones en estado (se encuentran en los parámetros avanzados de los comandos de información y permiten realizar una o más acciones sobre el valor de un pedido, inmediatamente o después de un retraso).
- Confirmaciones de acciones (configurables en el mismo lugar en un comando de información y permitiendo solicitar una confirmación para ejecutar una acción).
- Confirmaciones con código (igual que el anterior pero con la introducción de un código).
- Acciones previas y posteriores (siempre configurables en el mismo lugar en un comando de acción y permitiendo ejecutar una o más acciones antes o después de la acción en cuestión).

> **Punta**
>
> La tabla proporciona una vista muy textual de las acciones definidas. Se pueden agregar otros tipos de acciones definidas.

## Pestaña Alertas definidas

Esta pestaña le permite ver todas las alertas definidas, encontrará en una tabla la siguiente información si existen :

- Alertas de retraso de comunicación.
- Umbrales de batería específicos definidos en un dispositivo.
- Las diversas alertas de peligro y comandos de advertencia.

## Pestaña Orphan Orders

Esta pestaña le permite ver de un vistazo si ha utilizado comandos huérfanos a través de Jeedom. Un comando huérfano es un comando utilizado en algún lugar pero que ya no existe. Encontraremos aquí todos estos comandos, como por ejemplo :

- Comandos huérfanos utilizados en el cuerpo de un escenario.
- los utilizados para desencadenar un escenario.

Y se usa en muchos otros lugares como (no exhaustivo) :
- Interacciones.
- Configuraciones de libertad.
- En pre o post acción de una orden.
- En acción sobre el estado del pedido.
- En algunos complementos.

> **Punta**
>
> La tabla proporciona una vista muy textual de los comandos huérfanos. Su objetivo es poder identificar rápidamente todos los pedidos &quot;huérfanos&quot; a través de todos los complementos y Jeedom. Puede ser que algunas áreas no se analicen, la tabla será más y más exhaustiva con el tiempo.
