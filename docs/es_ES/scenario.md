# Escenarios

**Herramientas → Escenarios**

<small>[Atajos de teclado y ratón](shortcuts.md)</small>

Los escenarios, auténticos cerebros de la domótica, permiten interactuar con el mundo real de forma *inteligente*.

## Gestión

Aquí encontrarás la lista de escenarios de tu Jeedom, así como funciones para gestionarlos de la mejor manera posible:

- **Añadir**: Permite crear un escenario. El procedimiento se describe en el siguiente capítulo.
- **Desactivar escenarios**: Permite desactivar todos los escenarios. Se utiliza en contadas ocasiones y con conocimiento de causa, ya que ningún escenario se ejecutará a partir de ese momento.
- **Visión general**: Permite obtener una visión general de todos los escenarios. Puedes modificar los valores **activo**, **visible**, **ejecución múltiple**, **modo síncrono**, **Registro** y **Línea de tiempo** (estos parámetros se describen en el siguiente capítulo). También puede acceder a los registros de cada escenario e iniciarlos de forma individual.

## Mis escenarios

En esta sección encontrarás la **lista de escenarios** que has creado. Están ordenados según su **grupo**, que puede estar definido para cada uno de ellos. Cada escenario se muestra con su **nombre** y su **objeto principal**. Los **escenarios que aparecen en gris** son los que están desactivados.

> **Consejo**
>
> Puedes abrir un escenario haciendo lo siguiente:
>
> - Haz clic en uno de ellos.
> - Ctrl + clic o clic con el botón central para abrirlo en una nueva pestaña del navegador.

Dispone de un buscador que le permite filtrar los escenarios que se muestran. La tecla Esc cancela la búsqueda.
A la derecha del campo de búsqueda, hay tres botones que aparecen en varios lugares de Jeedom:

- La cruz para cancelar la búsqueda.
- La carpeta abierta para desplegar todos los paneles y mostrar todos los escenarios.
- La carpeta cerrada para plegar todos los paneles.

Una vez en la configuración de un escenario, al hacer clic con el botón derecho del ratón sobre las pestañas del escenario, aparecerá un menú contextual. También puedes utilizar Ctrl + clic o el botón central del ratón para abrir directamente otro escenario en una nueva pestaña del navegador.

## Creación | Edición de un escenario

Después de hacer clic en **Añadir**, debes elegir el nombre de tu escenario. A continuación, se te redirigirá a la página de sus ajustes generales.
Antes de eso, en la parte superior de la página, encontramos algunas funciones útiles para gestionar este escenario:

- **ID**: Junto a la palabra **General**, es el identificador del escenario.
- **Estado**: *Detenido* o *En curso*, indica el estado actual del escenario.
- **Estado anterior / siguiente**: Permite deshacer / rehacer una acción.
- **Añadir un bloque**: Permite añadir un bloque del tipo deseado al escenario (véase más abajo).
- **Registro**: Permite visualizar los registros del escenario.
- **Duplicar**: Permite copiar el escenario para crear uno nuevo con otro nombre.
- **Enlaces**: Permite visualizar el gráfico de los elementos relacionados con el escenario.
- **Edición de texto**: Muestra una ventana que permite editar el escenario en formato de texto o JSON. No olvides guardar los cambios.
- **Exportar**: Permite obtener una versión en texto sin formato del escenario.
- **Plantilla**: Permite acceder a las plantillas y aplicar una al escenario desde el Market (se explica al final de la página).
- **Búsqueda**: Abre un campo de búsqueda para buscar en el escenario. Esta búsqueda despliega los bloques contraídos si es necesario y los vuelve a contraer tras la búsqueda.
- **Ejecutar**: Permite iniciar el escenario manualmente (independientemente de los activadores). Guarda primero para que se apliquen los cambios.
- **Eliminar**: Eliminar el escenario.
- **Guardar**: Guardar los cambios realizados.

> **Consejos**
>
> También hay dos herramientas que te resultarán muy útiles a la hora de configurar escenarios:
    > - Las variables, visibles en **Herramientas → Variables**
    > - El comprobador de expresiones, al que se accede desde **Herramientas → Comprobador de expresiones**
>
> Al **hacer clic con el botón derecho del ratón en el botón «Ejecutar»**, podrás guardar, ejecutar y visualizar el registro del escenario directamente (siempre que el nivel de registro no esté configurado en «Ninguno»).

## Pestaña «General»

En la pestaña **General** se encuentran los parámetros principales del escenario:

- **Nombre del escenario**: El nombre de tu escenario.
- **Nombre que se mostrará**: El nombre que se utilizará para su visualización. Opcional; si no se introduce, se utilizará el nombre del escenario.
- **Grupo**: Permite organizar los escenarios clasificándolos en grupos (visibles en la página de escenarios y en sus menús contextuales).
- **Activo**: Permite activar el escenario. Si no está activo, Jeedom no lo ejecutará, independientemente del modo de activación.
- **Visible**: Permite que el escenario sea visible (panel de control).
- **Objeto principal**: Asignación a un objeto principal. Dependiendo de dicho objeto principal, será visible o no.
- **Tiempo de espera en segundos (0 = ilimitado)**: El tiempo máximo de ejecución permitido para este escenario. Transcurrido este tiempo, se interrumpe la ejecución del escenario.
- **Inicio múltiple**: Marca esta casilla si deseas que el escenario se pueda iniciar varias veces al mismo tiempo.

>**IMPORTANTE**
>
>La función de ejecución múltiple funciona por segundos, es decir, si se producen dos ejecuciones en el mismo segundo sin tener marcada la casilla, se ejecutarán de todos modos dos veces el escenario (cuando no debería ser así). Del mismo modo, cuando se producen varias ejecuciones en el mismo segundo, es posible que algunas de ellas pierdan las etiquetas. En conclusión, hay que evitar A TODA COSTA las ejecuciones múltiples en el mismo segundo.

- **Modo síncrono**: Ejecuta el escenario en el hilo actual en lugar de en un hilo dedicado. Permite aumentar la velocidad de ejecución del escenario, pero puede provocar inestabilidad en el sistema. Ten mucho cuidado de no ejecutar en modo síncrono escenarios complejos o que incluyan pausas (sleep) o wait, ya que esto provoca un comportamiento inestable de Jeedom y no estará cubierto por el servicio de asistencia técnica.
- **Registro**: el tipo de registro deseado para el escenario. Puedes desactivar los registros del escenario o, por el contrario, hacer que aparezcan en Análisis → Tiempo real.
- **Línea de tiempo**: Permite realizar un seguimiento del escenario en la línea de tiempo (véase la documentación «Historial»).
- **Icono**: Permite elegir un icono para el escenario en lugar del icono estándar.
- **Descripción**: Permite escribir un breve texto para describir tu escenario.
- **Modo del escenario**: El escenario se puede programar, activar o ambas cosas a la vez. A continuación, podrás indicar el o los activadores (15 como máximo) y la o las programaciones.

> **Consejo**
>
> En el modo activado, ahora se pueden introducir condiciones. Por ejemplo: ``#[Garage][Open Garage][Ouverture]# == 1``
> Atención: puedes tener un máximo de 28 activadores/programaciones por escenario.

> **Modo «Tip» programado**
>
> El modo programado utiliza la sintaxis **Cron**. Por ejemplo, podrás ejecutar un escenario cada 20 minutos con  `*/20 * * * *`, o a las 5 de la mañana para organizar un montón de cosas para el día con ``0 5 * * *``. El símbolo «?» situado a la derecha de una programación te permite configurarla sin necesidad de ser un experto en la sintaxis de Cron. También es posible indicar una hora de inicio en el formato `Gi` (hora sin el cero inicial y los minutos, ejemplo para `09h15` => `915` o para `23h40` => `2340`). Esta hora puede ser el resultado de un cálculo (utilizando un comando o una etiqueta), por ejemplo: `#sunset# + 10` para un lanzamiento 10 minutos después de la puesta del sol. Ten en cuenta que, para un lanzamiento 1 h 30 min después de la puesta del sol, hay que poner `#sunset# + 130`. Ten en cuenta que, si utilizas una sintaxis distinta a la de cron, Jeedom no podrá indicarte las fechas de las ejecuciones anteriores o posteriores.

## Pestaña «Escenario»

Aquí es donde vas a crear tu escenario. Una vez creado el escenario, su contenido estará vacío, por lo que no hará... nada. Debes empezar por **añadir un bloque**, utilizando el botón situado a la derecha. Una vez creado un bloque, podrás añadirle otro **bloque** o una **acción**.

Para mayor comodidad y para no tener que estar reordenando constantemente los bloques en el escenario, al añadir un bloque, este se inserta después del campo en el que se encuentra el cursor del ratón.
*Por ejemplo, si tienes unos diez bloques y haces clic en la condición «SI» del primer bloque, el bloque añadido se colocará después de ese bloque, en el mismo nivel. Si no hay ningún campo activo, se añadirá al final del escenario.*

> **Consejo**
>
> En las condiciones y acciones, es mejor utilizar comillas simples (') en lugar de comillas dobles (").

> **Consejo**
>
> Con Ctrl+Mayús+Z o Ctrl+Mayús+Y puedes **deshacer** o **rehacer** un cambio (añadir una acción, un bloque...).

## Los bloques

Estos son los diferentes tipos de bloques disponibles:

- **Si/Entonces/Si no**: Permite realizar acciones condicionadas (si esto, entonces aquello).
- **Acción**: Permite iniciar acciones sencillas sin ninguna condición.
- **Bucle**: Permite realizar acciones de forma repetitiva entre 1 y un número definido, un número aleatorio, el valor de un sensor, etc. *(duración máxima: 1 hora)*
- **En**: Permite iniciar una acción en X minutos (0 es un valor válido). La particularidad es que las acciones se ejecutan en segundo plano, por lo que no bloquean la continuación del escenario. Por lo tanto, se trata de un bloque no bloqueante.
- **A**: Permite indicar a Jeedom que ejecute las acciones del bloque a una hora determinada (en formato hhmm). Este bloque no es bloqueante. Ej.: 0030 para las 00:30, 0146 para la 1:46 y 1050 para las 10:50.
- **Mientras**: Permite realizar acciones mientras se cumpla una condición. *(duración máxima: 1 hora)*
- **Código**: Permite escribir directamente en código PHP (requiere ciertos conocimientos y puede suponer un riesgo, pero permite trabajar sin restricciones).
- **Comentario**: Permite añadir comentarios a un escenario.

Cada bloque tiene sus propias opciones para manejarlo mejor:

- A la izquierda:
  - La flecha bidireccional permite mover un bloque o una acción para reordenarlos en el escenario.
  - El icono del ojo permite contraer un bloque (*collapse*) para reducir su impacto visual. Al pulsar Ctrl + clic en el icono del ojo, se contraen o se muestran todos los bloques.
  - La casilla de selección permite desactivar por completo el bloque sin llegar a eliminarlo. Por lo tanto, no se ejecutará.

- A la derecha:
  - El icono «Copiar» permite copiar el bloque para crear una copia en otro lugar. Al hacer clic con la tecla Ctrl pulsada sobre el icono, se corta el bloque (se copia y luego se elimina).
  - El icono «Pegar» permite pegar una copia del bloque copiado anteriormente justo después del bloque en el que se utiliza esta función. Al hacer clic con la tecla Ctrl pulsada sobre el icono, el bloque se sustituye por el bloque copiado.
  - El icono permite eliminar el bloque, con una solicitud de confirmación. Al pulsar Ctrl + clic se elimina el bloque sin confirmación.

### Bloques «Si/Entonces/Si no» | Bucle | En | A

En cuanto a las condiciones, Jeedom intenta que se puedan redactar, en la medida de lo posible, en lenguaje natural, sin perder flexibilidad.
> Es MUY IMPORTANTE NO utilizar [ ] en las condiciones de las pruebas; solo se pueden utilizar paréntesis ().

A la derecha de este tipo de bloque hay tres botones disponibles para seleccionar un elemento que se vaya a probar:

- **Buscar un comando**: permite buscar un comando entre todos los disponibles en Jeedom. Una vez encontrado el comando, Jeedom abre una ventana para preguntarte qué prueba deseas realizar con él. Si eliges **No introducir nada**, Jeedom añadirá el comando sin realizar ninguna comparación. También puedes elegir **y** o **o** delante de **A continuación** para encadenar pruebas en diferentes dispositivos.
- **Buscar un escenario**: Permite buscar un escenario para probarlo.
- **Buscar un dispositivo**: Lo mismo que para un dispositivo.

> **Nota**
>
> En los bloques del tipo «Si/Entonces/Si no», unas flechas circulares situadas a la izquierda del campo de condición permiten activar o desactivar la repetición de las acciones si la evaluación de la condición da el mismo resultado que en la evaluación anterior.
> SI expresión != 0 es equivalente a SI expresión y SI expresión == 0 es equivalente a SI no expresión

> **Consejo**
>
> Existe una lista de etiquetas que permite acceder a variables procedentes de un escenario u otro, o bien a la hora, la fecha, un número aleatorio, etc. Consulta más adelante los capítulos sobre comandos y etiquetas.

Una vez introducida la condición, debes utilizar el botón «Añadir», situado a la izquierda, para añadir un nuevo **bloque** o una **acción** al bloque actual.

### Bloque de código

El bloque «Código» permite ejecutar código PHP. Por lo tanto, es muy potente, pero requiere un buen conocimiento del lenguaje PHP.

#### Acceso a los controles (sensores y actuadores)

- ``cmd::byString($string);`` : Devuelve el objeto de comando correspondiente.
  - ``$string``: Enlace al pedido deseado: ``#[objet][equipement][commande]#`` (p. ej.: ``#[Appartement][Alarme][Actif]#``)
- ``cmd::byId($id);`` : Devuelve el objeto de comando correspondiente.
  - ``$id`` : ID del comando deseado.
- ``$cmd->execCmd($options = null);`` : Ejecuta el comando y devuelve el resultado.
  - ``$options`` : Opciones para la ejecución del comando (pueden ser específicas del complemento). Opciones básicas (subtipo del comando):
    - ``message`` : ``$option = array('title' => 'titre du message , 'message' => 'Mon message');``
    - ``color`` : ``$option = array('color' => 'couleur en hexadécimal');``
    - ``slider`` : ``$option = array('slider' => 'valeur voulue de 0 à 100');``

#### Acceso a los registros

- ``log::add('filename','level','message');``
  - ``filename`` : Nombre del archivo de registro.
  - ``level`` : [depuración], [información], [error], [evento].
  - ``message`` : Mensaje que se debe escribir en los registros.

#### Acceso a los escenarios

- ``$scenario->getName();`` : Devuelve el nombre del escenario actual.
- ``$scenario->getGroup();`` : Devuelve el grupo del escenario.
- ``$scenario->getIsActive();`` : Devuelve el estado del escenario.
- ``$scenario->setIsActive($active);`` : Permite activar o desactivar el escenario.
  - ``$active`` : 1 activo, 0 inactivo.
- ``$scenario->running();`` : Permite saber si el escenario se está ejecutando o no (verdadero / falso).
- ``$scenario->save();`` : Guarda los cambios.
- ``$scenario->setData($key, $value);`` : Guarda un dato (variable).
  - ``$key`` : clave del valor (entero o cadena).
  - ``$value`` : valor que se va a almacenar (``int``, ``string``, ``array`` o ``object``).
- ``$scenario->getData($key);`` : Recupera un dato (variable).
  - ``$key => 1`` : clave del valor (entero o cadena).
- ``$scenario->removeData($key);`` : Elimina un dato.
- ``$scenario->setLog($message);`` : Escribe un mensaje en el registro del escenario.
- ``$scenario->persistLog();`` : Obliga a escribir el registro (de lo contrario, solo se escribe al final del escenario). Atención: esto puede ralentizar ligeramente el escenario.

> **Consejo**
>
> Se ha añadido una función de búsqueda en el bloque «Código»: Buscar: Ctrl + F y luego Intro; resultado siguiente: Ctrl + G; resultado anterior: Ctrl + Mayús + G

[Escenarios: Pequeños códigos entre amigos](https://kiboost.github.io/jeedom_docs/jeedomV4Tips/CodesScenario/)

### Bloque de comentarios

El bloque de comentarios se comporta de forma diferente cuando está oculto. Sus botones de la izquierda y el título del bloque desaparecen, y vuelven a aparecer al pasar el cursor por encima. Asimismo, la primera línea del comentario se muestra en negrita.
Esto permite utilizar este bloque como una separación meramente visual dentro del escenario.

### Las acciones

Las acciones añadidas en los bloques tienen varias opciones:

- Una casilla **marcada** para que este comando se tenga en cuenta en el escenario.
- Una casilla **«paralelo»** para que este comando se ejecute en paralelo (al mismo tiempo) que los demás comandos también seleccionados.
- Una **flecha doble vertical** para desplazar la acción. Basta con arrastrarla y soltarla desde ahí.
- Un botón para **eliminar** la acción.
- Un botón para acciones específicas, con la descripción de cada acción (al pasar el cursor por encima).
- Un botón para buscar un comando de acción.

> **Consejo**
>
> Dependiendo del comando seleccionado, pueden aparecer diferentes campos adicionales.

## Posibles sustituciones

### Los activadores

Existen activadores específicos (aparte de los que proporcionan los comandos):

- ``#start#`` : Se activa al (re)iniciar Jeedom.
- ``#begin_backup#`` : Evento enviado al inicio de una copia de seguridad.
- ``#end_backup#`` : Evento enviado al finalizar una copia de seguridad.
- ``#begin_update#`` : Evento enviado al inicio de una actualización.
- ``#end_update#`` : Evento enviado al finalizar una actualización.
- ``#begin_restore#`` : Evento enviado al inicio de una restauración.
- ``#end_restore#`` : Evento enviado al finalizar una restauración.
- ``#user_connect#`` : Inicio de sesión de un usuario, la etiqueta `#trigger_value#` contiene el nombre de usuario.
- ``#variable(nom_variable)#`` : Cambio en el valor de la variable «nombre_variable».
- ``#genericType(GENERIC, #[Object]#)#`` : Modificación de un comando de información de tipo «Generic» (GENERIC) en el objeto «Objecto».
- ``#new_eqLogic#`` : Evento enviado al crear un nuevo dispositivo; en las etiquetas encontrarás id (ID del dispositivo creado), name (nombre del dispositivo creado) y eqType (tipo o complemento del dispositivo creado).

También puedes activar un escenario utilizando la API HTTP descrita [aquí](api_http.md).

### Operadores de comparación y relaciones entre las condiciones

Puedes utilizar cualquiera de los siguientes símbolos para realizar comparaciones en las condiciones:

- ``==`` : Igual que.
- ``>`` : Estrictamente superior a.
- ``>=`` : Mayor o igual que.
- ``<`` : Estrictamente menor que.
- ``<=`` : Menor o igual que.
- ``!=`` : Diferente de, no es igual a.
- ``matches`` : Contiene. Ej.: ``[Salle de bain][Hydrometrie][etat] matches "/humide/"``.
- ``not(…​ matches …​)`` : No contiene. Ej.:  ``not([Salle de bain][Hydrometrie][etat] matches "/humide/")``.

Puedes combinar cualquier comparación con los siguientes operadores:

Tanto si comparas diferentes equipos como si comparas el mismo, siempre es necesario indicar de qué equipo se trata.
``[Salle de bain][Hydrometrie][température] >= 18 && [Salle de bain][Hydrometrie][température] <= 22``

- ``&&`` : y. **Atención**, el uso de  : ``ET`` / ``et`` / ``AND`` / ``and`` no es recomendable; en algunos casos puede funcionar, pero con ciertas funciones de PHP no funcionará.
- ``||`` : o. **Atención**, el uso de  : ``OU`` / ``ou`` / ``OR`` / ``or`` no es recomendable; en algunos casos puede funcionar, pero con ciertas funciones de PHP no funcionará.
- ``xor``  : o exclusivo. **Atención**, el uso de  : ``XOR`` / ``^`` no es recomendable; en algunos casos puede funcionar, pero con ciertas funciones de PHP no funcionará.

### Etiquetas

Al ejecutarse el escenario, una etiqueta se sustituye por su valor. Puedes utilizar las siguientes etiquetas:

> **Consejo**
>
> Para que aparezcan los ceros iniciales en la pantalla, hay que utilizar la función Date(). Véase [aquí](https://www.php.net/manual/fr/datetime.format.php).

- ``#seconde#`` : Segundos actuales (sin los ceros iniciales, p. ej., 6 para las 08:07:06).
- ``#hour#`` : Hora actual en formato de 24 horas (sin los ceros iniciales). Ej.: 8 para las 08:07:06 o 17 para las 17:15.
- ``#hour12#`` : Hora actual en formato de 12 horas (sin los ceros iniciales). Ej.: 8 para las 08:07:06.
- ``#minute#`` : Minuto actual (sin los ceros iniciales). Ej.: 7 para las 08:07:06.
- ``#day#`` : Día del mes (sin los ceros iniciales). Ej.: 6 para el 06/07/2017.
- ``#month#`` : Mes actual (sin los ceros iniciales). Ej.: 7 para el 06/07/2017.
- ``#year#`` : Año actual.
- ``#time#`` : Hora y minuto actuales. Ej.: 1715 para las 17:15.
- ``#timestamp#`` : Número de segundos transcurridos desde el 1 de enero de 1970.
- ``#date#`` : Día y mes. Atención: el primer número es el mes. Ej.: 1215 para el 15 de diciembre.
- ``#week#`` : Número de esta semana.
- ``#sday#`` : Nombre del día de la semana. Ej.: Sábado.
- ``#nday#`` : Número del día, del 0 (domingo) al 6 (sábado).
- ``#smonth#`` : Nombre del mes. Ej.: enero.
- ``#IP#`` : Dirección IP interna de Jeedom.
- ``#hostname#`` : Nombre del dispositivo Jeedom.
- ``#jeedomName#`` : Nombre del Jeedom.
- ``#trigger#`` : Podría ser:
  - ``api`` si la ejecución se ha iniciado mediante la API,
  - ``TYPEcmd`` si la ejecución se ha activado mediante un comando, con TYPE sustituido por el ID del complemento (por ejemplo, virtualCmd),
  - ``schedule`` si se ha iniciado mediante una programación,
  - ``user`` si se ha iniciado manualmente,
  - ``start`` para que se active al iniciar Jeedom.
- ``#trigger_id#`` : Si ha sido un comando el que ha activado el escenario, esta etiqueta tendrá como valor el ID del comando que lo ha activado. Ejemplo: ``#trigger_id# == 19``
- ``#trigger_name#`` : Si ha sido un comando el que ha activado el escenario, esta etiqueta tendrá como valor el nombre del comando (en el formato [objeto][equipo][comando]). Ejemplo: ``#trigger_name# == '[cuisine][lumiere][etat]'``
- ``#trigger_value#`` : Si ha sido un comando el que ha activado el escenario, esta etiqueta tendrá el valor del comando que lo ha activado. Consejo: si quieres obtener el valor actual del comando que ha activado el escenario (y no su valor en el momento de la activación), puedes utilizar: ``##trigger_id##`` (doble #)
- ``#latitude#`` : Permite recuperar la información de latitud introducida en la configuración de Jeedom
- ``#longitude#`` : Permite recuperar la información de longitud introducida en la configuración de Jeedom
- ``#altitude#`` : Permite recuperar la información sobre la altitud introducida en la configuración de Jeedom
- ``#sunrise#`` : Permite obtener la hora del amanecer, siempre que se hayan introducido la latitud y la longitud en la configuración de Jeedom
- ``#sunset#`` : Permite obtener la hora de la puesta de sol, siempre que se hayan introducido la latitud y la longitud en la configuración de Jeedom

Además, dispones de las siguientes etiquetas si tu escenario se ha activado mediante una interacción:

- #query#: Interacción que ha activado el escenario.
- #perfil#: Perfil del usuario que ha activado el escenario (puede estar vacío).

> **Importante**
>
> Cuando una interacción activa un escenario, este se ejecuta necesariamente en modo rápido. Es decir, en el hilo de la interacción y no en un hilo independiente.

### Las funciones de cálculo

Hay varias funciones disponibles para los dispositivos:

- ``average(commande,période)`` & ``averageBetween(commande,start,end)`` : Muestra la media del consumo durante el periodo (period=[mes, día, hora, min] o [expresión PHP](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)) o entre los dos terminales indicados (en forma de ``Y-m-d H:i:s`` o [expresión PHP](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)).

- ``averageTemporal(commande,période)`` & ``averageTemporalBetween(commande,start,end)`` : Muestran la media de los valores del comando, ponderada por su tiempo de existencia durante el periodo (period=[mes, día, hora, min] o [expresión PHP](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)) o entre los dos terminales indicados (en forma de ``Y-m-d H:i:s`` o [expresión PHP](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)).

- ``min(commande,période)`` & ``minBetween(commande,start,end)`` : Indica el mínimo de pedidos durante el periodo (period=[mes, día, hora, min] o [expresión PHP](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)) o entre los dos terminales indicados (en forma de ``Y-m-d H:i:s`` o [expresión PHP](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)).

- ``max(commande,période)`` & ``maxBetween(commande,start,end)`` : Indican el valor máximo del control durante el periodo (period=[mes, día, hora, min] o [expresión PHP](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)) o entre los dos terminales indicados (en forma de ``Y-m-d H:i:s`` o [expresión PHP](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)).

- ``duration(commande, valeur, période)`` & ``durationbetween(commande,valeur,start,end)`` : Indica el tiempo, en minutos, durante el cual el equipo mantuvo el valor seleccionado en el periodo (period=[month,day,hour,min] o [expresión PHP](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)) o entre los dos terminales indicados (en forma de ``Y-m-d H:i:s`` o [expresión PHP](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)).

- ``statistics(commande,calcul,période)`` & ``statisticsBetween(commande,calcul,start,end)`` : Proporcionan el resultado de diferentes cálculos estadísticos (suma, recuento, desviación estándar, varianza, media, mínimo, máximo) para el periodo (period=[mes, día, hora, minuto] o [expresión PHP](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)) o entre los dos terminales indicados (en forma de ``Y-m-d H:i:s`` o [expresión PHP](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)).

- ``tendance(commande,période,seuil)`` : Muestra la tendencia de la demanda durante el periodo (period=[mes, día, hora, min] o [expresión PHP](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)).

- ``stateDuration(commande)`` : Indica el tiempo transcurrido en segundos desde el último cambio de valor.
-1: No hay historial o el valor no figura en el historial.
-2: El comando no se ha registrado en el historial.

- ``lastChangeStateDuration(commande,valeur)`` : Indica el tiempo, en segundos, transcurrido desde el último cambio de estado del valor pasado como parámetro.
-1: No hay historial o el valor no figura en el historial.
-2 El comando no se registra en el historial

- ``lastStateDuration(commande,valeur)`` : Indica el tiempo, en segundos, durante el cual el equipo ha mantenido por última vez el valor seleccionado.
-1: No hay historial o el valor no figura en el historial.
-2: El comando no se ha registrado en el historial.

- ``age(commande)`` : Indica la antigüedad, en segundos, del valor del comando (``collecDate``)
-1: El comando no existe o no es de tipo «info».

- ``stateChanges(commande,[valeur], période)`` & ``stateChangesBetween(commande, [valeur], start, end)`` : Indica el número de cambios de estado (hacia un valor determinado, si se indica, o, si no se indica, en relación con su valor actual) durante el periodo (period=[month,day,hour,min] o [expresión PHP](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)) o entre los dos terminales indicados (en forma de ``Y-m-d H:i:s`` o [expresión PHP](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)).

- ``lastBetween(commande,start,end)`` : Proporciona el último valor registrado para el equipo entre los dos terminales solicitados (en formato ``Y-m-d H:i:s`` o [expresión PHP](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)).

- ``variable(mavariable,valeur par défaut)`` : Recupera el valor de una variable o el valor predeterminado deseado.

- ``genericType(GENERIC, #[Object]#)`` : Recupera la suma de los datos de tipo genérico GENERIC del objeto Objecto.

- ``scenario(scenario)`` : Devuelve el estado del escenario.
1: En curso,
0: Parado,
-1: Desactivado,
-2: El escenario no existe,
-3: El estado no es coherente.
Para obtener el nombre «en lenguaje natural» del escenario, puedes utilizar el botón específico situado a la derecha del buscador de escenarios.

- ``lastScenarioExecution(scenario)`` : Indica el tiempo transcurrido en segundos desde la última ejecución del escenario.
0: El escenario no existe

- ``collectDate(cmd,[format])`` : Devuelve la fecha de la última recopilación de datos para el comando indicado en el parámetro; el segundo parámetro, opcional, permite especificar el formato de la respuesta (detalles [aquí](https://www.php.net/manual/fr/datetime.format.php)).
-1: No se encuentra el comando,
-2: El comando no es de tipo «info».

- ``valueDate(cmd,[format])`` : Devuelve la fecha del último valor conocido para el comando indicado en el parámetro; el segundo parámetro, opcional, permite especificar el formato de retorno (detalles [aquí](https://www.php.net/manual/fr/datetime.format.php)).
-1: No se encuentra el comando,
-2: El comando no es de tipo «info».

- ``eqEnable(equipement)`` : Muestra el estado del equipo.
-2: No se encuentra el dispositivo,
1: El equipo está activo,
0: El equipo está inactivo.

- ``value(cmd)`` : Devuelve el valor de un comando si Jeedom no lo proporciona automáticamente (por ejemplo, al almacenar el nombre del comando en una variable)

- ``tag(montag,[defaut])`` : Permite recuperar el valor de una etiqueta o el valor por defecto si no existe.

- ``name(type,commande)`` : Permite recuperar el nombre del comando, del equipo o del objeto. Tipo: cmd, eqLogic u objeto.

- ``lastCommunication(equipment,[format])`` : Devuelve la fecha de la última transmisión de información del dispositivo indicado en el primer parámetro; el segundo parámetro, opcional, permite especificar el formato de respuesta (detalles [aquí](https://www.php.net/manual/fr/datetime.format.php)). Un valor de -1 significa que no se ha encontrado el equipo. La fecha de la última actualización se calcula en función de los comandos de tipo «información» y de su fecha de recogida.

- ``color_gradient(couleur_debut,couleur_fin,valuer_min,valeur_max,valeur)`` : Devuelve un color calculado en función de un valor comprendido en el intervalo color_inicio/color_fin. El valor debe estar comprendido entre valor_mín y valor_máx.

Los periodos e intervalos de estas funciones también se pueden utilizar con [expresiones PHP](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative) como, por ejemplo:

- ``Now`` : ahora.
- ``Today`` : 00:00 hoy (permite, por ejemplo, obtener los resultados del día si entre ``Today`` y ``Now``).
- ``Last Monday`` : el lunes pasado a las 00:00.
- ``5 days ago`` : hace 5 días.
- ``Yesterday noon`` : ayer al mediodía.
- Etc.

A continuación se muestran algunos ejemplos prácticos para comprender los valores que devuelven estas diferentes funciones:

| Toma con los siguientes valores: | 000 (durante 10 minutos) 11 (durante 1 hora) 000 (durante 10 minutos)    |
|--------------------------------------|--------------------------------------|
| ``average(prise,période)``             | Devuelve la media de los valores 0 y 1 (puede  |
| | verse afectada por el sondeo) |
| ``averageBetween(#[Salle de bain][Hydrometrie][Humidité]#,2015-01-01 00:00:00,2015-01-15 00:00:00)`` | Muestra el promedio de los pedidos realizados entre el 1 de enero de 2015 y el 15 de enero de 2015 |
| ``min(prise,période)``                 | Devuelve 0: el enchufe se ha apagado correctamente durante el periodo |
| ``minBetween(#[Salle de bain][Hydrometrie][Humidité]#,2015-01-01 00:00:00,2015-01-15 00:00:00)`` | Mostrar el pedido mínimo realizado entre el 1 de enero de 2015 y el 15 de enero de 2015 |
| ``max(prise,période)``                 | Resultado 1: el enchufe se ha encendido correctamente durante el periodo |
| ``maxBetween(#[Salle de bain][Hydrometrie][Humidité]#,2015-01-01 00:00:00,2015-01-15 00:00:00)`` | Devuelve el máximo de pedidos realizados entre el 1 de enero de 2015 y el 15 de enero de 2015 |
| ``duration(prise,1,période)``          | Código de error 60: la toma de corriente estuvo encendida (en 1) durante 60 minutos en el periodo |
| ``durationBetween(#[Salon][Prise][Etat]#,0,Last Monday,Now)``   | Muestra el tiempo, en minutos, que lleva apagado el enchufe desde el lunes pasado. |
| ``statistics(prise,count,période)``    | Resultado 8: se han registrado 8 notificaciones de estado durante el periodo |
| ``tendance(prise,période,0.1)``        | Devuelve -1: tendencia a la baja    |
| ``stateDuration(prise)``               | Devuelve 600: la toma lleva 600 segundos (10 minutos) en su estado actual |
| ``lastChangeStateDuration(prise,0)``   | Código de error 600: la toma se ha apagado (ha pasado a 0) por última vez hace 600 segundos (10 minutos)     |
| ``lastChangeStateDuration(prise,1)``   | Devuelve 4200: el enchufe se encendió (pasó a 1) por última vez hace 4200 segundos (1 h 10 min) |
| ``lastStateDuration(prise,0)``         | Código 600: la toma lleva apagada 600 segundos (10 minutos)     |
| ``lastStateDuration(prise,1)``         | Devuelve 3600: la toma se encendió por última vez hace 3600 segundos (1 h) |
| ``stateChanges(prise,période)``        | Resultado 3: el enchufe ha cambiado de estado 3 veces durante el periodo (si el comando «info» es de tipo binario) |
| ``stateChanges(prise,0,période)``      | Resultado 2: la toma se ha apagado (ha pasado a 0) dos veces durante el periodo |
| ``stateChanges(prise,1,période)``      | Salida 1: el enchufe se ha encendido (cambio a 1) una vez durante el  periodo |
| ``lastBetween(#[Salle de bain][Hydrometrie][Température]#,Yesterday,Today)`` | Muestra la última temperatura registrada ayer. |
| ``variable(plop,10)``                  | Devuelve el valor de la variable plop o 10 si está vacía o no existe |
| ``scenario(#[Salle de bain][Lumière][Auto]#)`` | Devuelve 1 si está en marcha, 0 si está parado, -1 si está desactivado, -2 si el escenario no existe y -3 si el estado no es coherente |
| ``lastScenarioExecution(#[Salle de bain][Lumière][Auto]#)``   | Devuelve 300 si el escenario se ejecutó por última vez hace 5 minutos |
| ``collectDate(#[Salle de bain][Hydrometrie][Humidité]#)``     | Publicado el 14 de febrero de 2021 a las 17:50:12 |
| ``valueDate(#[Salle de bain][Hydrometrie][Humidité]#)`` | Publicado el 14 de febrero de 2021 a las 17:45:12 |
| ``eqEnable(#[Aucun][Basilique]#)``       | Devuelve -2 si no se encuentra el dispositivo, 1 si el dispositivo está activo y 0 si está inactivo |
| ``tag(montag,toto)``                   | Devuelve el valor de «montag» si existe; en caso contrario, devuelve el valor «toto» |
| ``name(eqLogic,#[Salle de bain][Hydrometrie][Humidité]#)``     | Volver a Hidrometría |

### Las funciones matemáticas

Un conjunto de funciones genéricas también puede utilizarse para realizar conversiones o cálculos:

- ``rand(1,10)`` : Genera un número aleatorio entre 1 y 10.
- ``randText(texte1;texte2;texte…​..)`` : Permite devolver uno de los textos de forma aleatoria (separar los textos con un punto y coma ;). No hay límite en el número de textos.
- ``randomColor(min,max)`` : Asigna un color aleatorio comprendido entre dos valores límite (0 => rojo, 50 => verde, 100 => azul).
- ``trigger(commande)`` : Permite conocer el desencadenante del escenario o saber si fue realmente el comando pasado como parámetro el que lo activó. **=> Obsoleto; es mejor utilizar la etiqueta #trigger#**
- ``triggerValue()`` : Permite conocer el valor del desencadenante del escenario. **=> Obsoleto; es mejor utilizar la etiqueta #trigger_value#**
- ``round(valeur,[decimal])`` : Redondea al alza, [decimal] número de decimales después de la coma.
- ``odd(valeur)`` : Permite saber si un número es impar o no. Devuelve 1 si es impar y 0 en caso contrario.
- ``median(commande1,commande2…​.commandeN)`` : Devuelve la mediana de los valores.
- ``avg(commande1,commande2…​.commandeN)`` : Devuelve la media de los valores.
- ``time_op(time,value)`` : Permite realizar operaciones con la hora, utilizando «time=hora» (p. ej., 1530) y «value=valor» que se va a sumar o restar en minutos.
- ``time_between(time,start,end)`` : Permite comprobar si una hora está comprendida entre dos valores con ``time=temps`` (p. ej.: 1530), ``start=temps``, ``end=temps``. Los valores «start» y «end» pueden abarcar la medianoche.
- ``time_diff(date1,date2[,format, round])`` : Permite calcular la diferencia entre dos fechas (las fechas deben estar en el formato AAAA/MM/DD HH:MM:SS). Por defecto, el método devuelve la diferencia en días. Se le puede solicitar que la devuelva en segundos (s), minutos (m) u horas (h). Ejemplo en segundos ``time_diff(2019-02-02 14:55:00,2019-02-25 14:55:00,s)``. La diferencia se devuelve en valor absoluto, salvo que se especifique lo contrario ``f`` (``sf``, ``mf``, ``hf``, ``df``). También puedes utilizar ``dhms`` que no devolverá ningún resultado, por ejemplo ``7j 2h 5min 46s``. El parámetro «round», opcional, redondea a x decimales (2 por defecto). Ej.: ``time_diff(2020-02-21 20:55:28,2020-02-28 23:01:14,df, 4)``.
- ``formatTime(time)`` : Permite dar formato a la salida de un canal ``#time#``.
- ``floor(time/60)`` : Permite convertir segundos en minutos, o minutos en horas (``floor(time/3600)`` (de segundos a horas).
- ``convertDuration(secondes)`` : Permite convertir segundos en días/horas/minutos/segundos.

Y algunos ejemplos prácticos:

| Ejemplo de función | Resultado devuelto |
|--------------------------------------|--------------------------------------|
| ``randText(il fait #[salon][oeil][température]#;La température est de #[salon][oeil][température]#;Actuellement on a #[salon][oeil][température]#)`` | La función devolverá uno de estos textos al azar cada vez que se ejecute. |
| ``randomColor(40,60)``                 | Devuelve un color aleatorio  cercano al verde. |
| ``round(#[Salle de bain][Hydrometrie][Humidité]# / 10)`` | Devuelve 9 si el porcentaje de humedad es 85 |
| ``odd(3)``                             | Muestra 1 |
| ``median(15,25,20)``                   | Devuelve 20
| ``avg(10,15,18)``                      | Versión 14.3 |
| ``time_op(#time#, -90)``               | si son las 16:50, devuelve: 1650 - 0130 = 1520 |
| ``formatTime(1650)``                   | Publicado a las 16:50 |
| ``floor(130/60)``                     | Devuelve 2 (minutos si son 130 s, u horas si son 130 m) |
| ``convertDuration(3600)``             | Duración: 1 h 0 min 0 s |
| ``convertDuration(duration(#[Chauffage][Module chaudière][Etat]#,1, first day of this month)*60)`` | Devuelve el tiempo de encendido en días/horas/minutos desde que el módulo pasó al estado 1, contando desde el primer día del mes |

### Las diversas funciones

- ``sun(elevation)`` : Indica en grados la elevación del sol (atención: es necesario haber introducido tus coordenadas geográficas en la configuración de Jeedom)
- ``sun(azimuth)`` : Indica en grados el azimut del sol (atención: es necesario haber introducido tus coordenadas geográficas en la configuración de Jeedom)

### Los comandos específicos

Además de los controles de domótica, tienes acceso a las siguientes acciones:

- **Pausa** (sleep): Pausa de x segundos. *(duración máxima: 1 hora)*
- **variable** (variable): Creación o modificación de una variable o del valor de una variable.
- **Eliminar variable** (delete_variable): Permite eliminar una variable.
- **genericType(GENERIC, #[Objeto]#)**: Modificación de un comando de información (event) o de acción (execCmd) mediante el tipo genérico, en un objeto. Por ejemplo, apagar todas las luces del salón.
- **Escenario** (scenario): Permite controlar escenarios. La sección «etiquetas» permite enviar etiquetas al escenario, p. ej.: montag=2 (atención: solo se deben utilizar letras de la a a la z. No se permiten mayúsculas, acentos ni caracteres especiales). La etiqueta se recupera en el escenario de destino con la función tag(montag).
  - Iniciar: Inicia el escenario en un hilo diferente. El escenario iniciado se ejecuta independientemente del escenario que lo ha llamado.
  - Iniciar (Sincronizar): Inicia el escenario llamado y pone en pausa el escenario que lo ha llamado, hasta que el escenario llamado haya terminado de ejecutarse.
  - Detener: Detiene el escenario.
  - Activar: Activa un escenario desactivado.
  - Desactivar: Desactiva el escenario. Ya no se ejecutará independientemente de los desencadenantes.
  - Restablecimiento de los SI: Permite restablecer el estado de los **SI**. Este estado se utiliza para que no se repitan las acciones de un **SI**, si la evaluación de la condición da el mismo resultado que la evaluación anterior.
- **Stop** (stop): Detiene el escenario.
- **Esperar** (wait): Espera hasta que se cumpla la condición; el tiempo de espera se expresa en segundos. *(duración máxima: 1 hora)*
- **Ir al diseño** (gotodesign): Cambia el diseño que se muestra en todos los navegadores por el diseño solicitado.
- **Añadir un registro** (registro): Permite añadir un mensaje a los registros.
- **Crear un mensaje** (mensaje): Permite añadir un mensaje al centro de mensajes.
- **Activar/Desactivar Ocultar/Mostrar un dispositivo** (dispositivo): Permite modificar las propiedades de un dispositivo para que sea visible/invisible, activo/inactivo.
- **Realizar una solicitud** (ask): Permite indicar a Jeedom que debe plantear una pregunta al usuario. La respuesta se almacena en una variable; después, basta con comprobar su valor.
Por el momento, solo son compatibles los complementos de SMS, Slack, Telegram y Snips, así como la aplicación móvil.
Atención: esta función es bloqueante. Mientras no haya respuesta o no se alcance el tiempo de espera, el escenario permanecerá en espera. Nota: para una respuesta libre, introduce * en la lista de respuestas posibles.
- **Apagar Jeedom** (jeedom_poweroff): solicita a Jeedom que se apague.
- **Devolver un texto o un dato** (scenario_return): Devuelve un texto o un valor, por ejemplo, en una interacción.
- **Icono** (icon): Permite cambiar el icono que representa el escenario.
- **Alerta** (alert): Permite mostrar un pequeño mensaje de alerta en todos los navegadores que tengan abierta una página de Jeedom. Además, puedes elegir entre 4 niveles de alerta.
- **Ventana emergente** (popup): Permite mostrar una ventana emergente que debe validarse obligatoriamente en todos los navegadores que tengan abierta una página de Jeedom.
- **Informe** (report): Permite exportar una vista en formato (PDF, PNG, JPEG o SVG) y enviarla mediante un comando de tipo mensaje. Atención: si tu conexión a Internet es HTTPS sin firmar, esta función no funcionará. Se requiere HTTP o HTTPS firmado. El «tiempo de espera» se expresa en milisegundos (ms).
- **Eliminar bloques DANS/A programados** (remove_inat): Permite eliminar la programación de todos los bloques DANS y A de un escenario.
- **Evento** (event): Permite enviar un valor a un comando de tipo «información» de forma arbitraria.
- **Etiqueta** (tag): Permite añadir o modificar una etiqueta (la etiqueta solo existe mientras se está ejecutando el escenario, a diferencia de las variables, que persisten una vez finalizado el escenario).
- **Color de los iconos del panel de control** (setColoredIcon): Permite activar o desactivar el color de los iconos del panel de control.
- **Cambio de tema** (changetheme): Permite cambiar el tema actual de la interfaz a oscuro o claro.
- **Exportación del historial** (exportHistory): permite exportar el historial de un pedido en formato CSV como un archivo (por ejemplo, para enviarlo por correo electrónico). Puedes incluir varios pedidos (separados por &&). La selección del periodo se realiza de la siguiente forma:
  - «-1 mes» => -1 mes
  - «-1 día a medianoche» => -1 día a medianoche
  - «now» => ahora
  - «lunes de esta semana a medianoche» => lunes de esta semana a medianoche
  - «el domingo pasado a las 23:59» => el domingo anterior a las 23:59
  - «último día del mes anterior a las 23:59» => último día del mes anterior a las 23:59
  - «la medianoche del primer día de enero de este año» => el primer día de enero a medianoche
  - ...

### Plantilla de guion

Esta función permite convertir un escenario en una plantilla para, por ejemplo, aplicarla a otro Jeedom.

Al hacer clic en el botón **plantilla** situado en la parte superior de la página, se abre la ventana de gestión de plantillas.

Desde aquí, tienes la posibilidad de:

- Enviar una plantilla a Jeedom (archivo JSON obtenido previamente).
- Consulta la lista de escenarios disponibles en el Market.
- Crea una plantilla a partir del escenario actual (no olvides darle un nombre).
- Consulta las plantillas que hay actualmente en tu Jeedom.

Al hacer clic en una plantilla, podrás:

- **Compartir**: Comparte la plantilla en el Market.
- **Eliminar**: Eliminar la plantilla.
- **Descargar**: Descargar la plantilla en formato JSON para enviarla a otro Jeedom, por ejemplo.

A continuación, encontrarás la sección para aplicar tu plantilla al escenario actual.

Dado que los comandos pueden variar de un Jeedom a otro o de una instalación a otra, Jeedom te pide que establezcas la correspondencia entre los comandos presentes al crear la plantilla y los que tienes en tu instalación. Solo tienes que rellenar la correspondencia de los comandos y, a continuación, aplicar los cambios.

## Incorporación de una función PHP

> **IMPORTANTE**
>
> La incorporación de funciones PHP está reservada a usuarios avanzados. El más mínimo error puede resultar fatal para tu Jeedom.

### Instalación

Ve a la configuración de Jeedom, luego a OS/DB y abre el editor de archivos.

Ve a la carpeta «data», luego a «PHP» y haz clic en el archivo «user.function.class.php».

En esta *clase* puedes añadir tus funciones; allí encontrarás un ejemplo de función básica.

> **IMPORTANTE**
>
> Si tienes algún problema, siempre puedes volver al archivo original copiando el contenido de ``user.function.class.sample.php`` en ``user.function.class.php``
