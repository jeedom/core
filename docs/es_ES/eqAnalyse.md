# Análisis de equipos
**Análisis → Equipos**

La página «Análisis de equipos» te permite consultar de forma centralizada gran cantidad de información relativa a los equipos:

- El estado de tus pilas
- Módulos de alertas
- Las acciones definidas
- Las alertas configuradas
- Los comandos huérfanos

## Pestaña «Baterías»


En esta pestaña puedes ver la lista de tus módulos alimentados por batería, su nivel restante (el color de la ficha depende de este nivel), el tipo y el número de pilas que hay que poner en el módulo, el tipo de módulo, así como la fecha en la que se actualizó la información sobre el nivel de batería. También puedes ver si se ha establecido un umbral específico para ese módulo en concreto (representado por una mano).

> **Consejo**
>
> Los umbrales de alerta sobre el nivel de las baterías se pueden configurar de forma global en la configuración de Jeedom (Ajustes → Sistemas → Configuración: Equipos), o por equipo en su página de configuración avanzada, en la pestaña «Alertas».

## Pestaña «Módulos en alerta»

En esta pestaña podrás ver en tiempo real los módulos que están en estado de alerta. Las alertas pueden ser de diferentes tipos:

- Tiempo de espera (configurado en la pestaña «Alertas definidas»).
- La batería está en modo de aviso o en peligro.
- Comando de advertencia o peligro (configurable en los parámetros avanzados de los comandos).

Es posible que aquí se incluyan otros tipos de alertas.
Cada alerta se representará mediante el color del mosaico (el nivel de alerta) y un logotipo en la esquina superior izquierda (el tipo de alerta).

> **Consejo**
>
> Aquí se mostrarán todos los módulos en estado de alerta, incluso aquellos configurados como «no visibles». No obstante, cabe señalar que, si el módulo está en estado «visible», la alerta también se mostrará en el panel de control (en el objeto correspondiente).

## Pestaña «Acciones definidas»

Esta pestaña permite visualizar las acciones definidas directamente en un comando. De hecho, se pueden asignar a diferentes comandos y puede resultar difícil recordarlas todas. Esta pestaña sirve precisamente para eso y resume varios aspectos:

- Las acciones basadas en el estado (que se encuentran en los parámetros avanzados de los controles de información y que permiten realizar una o varias acciones sobre el valor de un control, ya sea de forma inmediata o tras un tiempo de espera).
- Las confirmaciones de acciones (configurables en el mismo lugar mediante un comando «info» y que permiten solicitar una confirmación para ejecutar una acción).
- Confirmaciones con código (lo mismo que antes, pero introduciendo un código).
- Las acciones previas y posteriores (que siempre se pueden configurar en el mismo lugar, en un comando de acción, y que permiten ejecutar una o varias acciones más antes o después de la acción en cuestión).

> **Consejo**
>
> La tabla permite ver de forma muy clara las acciones definidas. Se podrán añadir otros tipos de acciones definidas.

## Pestaña «Alertas definidas»

Esta pestaña permite ver todas las alertas configuradas; en ella encontrarás, en una tabla, la siguiente información, si está disponible:

- Alertas sobre retrasos en la comunicación.
- Los umbrales específicos de batería definidos en un equipo.
- Las diferentes alertas de peligro y avisos de los controles.

## Pestaña «Comandos huérfanos»

Esta pestaña te permite ver de un vistazo si tienes comandos huérfanos que se utilizan en Jeedom. Un comando huérfano es un comando que se utiliza en algún sitio pero que ya no existe. Aquí encontrarás todos estos comandos, como por ejemplo:

- Los comandos huérfanos utilizados en el cuerpo de un escenario.
- Las que se utilizan como desencadenantes de un escenario.

Y se utilizan en muchos otros ámbitos, como (entre otros):
- Las interacciones.
- Las configuraciones de Jeedom.
- Como acción previa o posterior a un comando.
- Acción en función del estado de un pedido.
- En algunos complementos.

Si el ID del comando huérfano sigue estando presente en el historial de eliminaciones (que se puede consultar en Análisis / Resumen de domótica), se mostrarán su nombre anterior y su fecha de eliminación.

> **Consejo**
>
> La tabla permite ver de forma muy clara los comandos huérfanos. Su objetivo es poder identificar rápidamente todos los comandos «huérfanos» en todo Jeedom y en los complementos. Es posible que algunas áreas no se hayan analizado aún, pero la tabla irá siendo cada vez más completa con el tiempo.
