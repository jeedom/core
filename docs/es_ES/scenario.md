# Escenarios
**Herramientas → Escenarios**

<small>[Raccourcis clavier/ /souris](shortcuts.md)</ /small>

Cerebro real de la automatización del hogar, los escenarios permiten interactuar con el mundo real de una manera *inteligente*.

## Gestion

Allí encontrará la lista de escenarios de su Jeedom, así como las funcionalidades para administrarlos en el mejor de los casos :

- **Añadir** : Crea un escenario. El procedimiento se describe en el siguiente capítulo.
- **Deshabilitar escenarios** : Deshabilita todos los escenarios. Raramente utilizado y a sabiendas, ya que ningún escenario se ejecutará más.
- **Resumen** : Le permite tener una visión general de todos los escenarios. Puedes cambiar los valores **activo**, **visible**, **lanzamiento múltiple**, **modo síncrono**, **Iniciar sesión** y **Cronología** (estos parámetros se describen en el siguiente capítulo). También puede acceder a los registros para cada escenario e iniciarlos individualmente.

## Mis escenarios

Encontrarás en esta parte **lista de escenarios** que creaste. Se clasifican según su **grupo**, posiblemente definido para cada uno de ellos. Cada escenario se muestra con su **apellido** y su **objeto padre**. Los **escenarios atenuados** son los que están deshabilitados.

> **Propina**
>
> Puede abrir un escenario haciendo :
> - Haga clic en uno de ellos.
> - Ctrl Clic o Clic Center para abrirlo en una nueva pestaña del navegador.

Tiene un motor de búsqueda para filtrar la visualización de escenarios. La tecla Escape cancela la búsqueda.
A la derecha del campo de búsqueda, se encuentran tres botones en varios lugares de Jeedom :
- La cruz para cancelar la búsqueda.
- La carpeta abierta para desplegar todos los paneles y mostrar todos los escenarios.
- El respaldo cerrado para plegar todos los paneles.

Una vez en la configuración de un escenario, tiene un menú contextual con clic derecho en las pestañas del escenario. También puede usar Ctrl Click o Click Center para abrir directamente otro escenario en una nueva pestaña del navegador.

## Creación | Editar un escenario

Después de hacer clic en **Añadir**, debes elegir el nombre de tu escenario. Luego se lo redirige a la página de sus parámetros generales.
Antes de eso, en la parte superior de la página, hay algunas funciones útiles para administrar este escenario :

- **CARNÉ DE IDENTIDAD** : Al lado de la palabra **General**, este es el identificador de escenario.
- **Estado** : *Detenido* o *En curso*, indica el estado actual del escenario.
- **Estado anterior / siguiente** : Cancelar / rehacer una acción.
- **Agregar bloque** : Le permite agregar un bloque del tipo deseado al escenario (ver más abajo).
- **Iniciar sesión** : Muestra los registros del escenario.
- **Duplicar** : Copie el escenario para crear uno nuevo con otro nombre.
- **Vínculos** : Le permite ver el gráfico de los elementos relacionados con el escenario.
- **Edición de texto** : Muestra una ventana que permite editar el escenario en forma de texto / json. No olvides guardar.
- **Exportar** : Le permite obtener una versión de texto puro del escenario.
- **Modelo** : Le permite acceder a las plantillas y aplicar una al escenario del Mercado (explicado en la parte inferior de la página).
- **Investigación** : Despliega un campo de búsqueda para buscar en el escenario. Esta búsqueda despliega los bloques colapsados si es necesario y los pliega después de la búsqueda.
- **Realizar** : Le permite iniciar el escenario manualmente (independientemente de los desencadenantes). Guardar de antemano para tener en cuenta las modificaciones.
- **Eliminar** : Eliminar escenario.
- **Salvar** : Guardar los cambios realizados.

> **Consejos**
>
> Dos herramientas también serán invaluables para usted en la configuración de escenarios :
    > - Las variables, visibles en **Herramientas → Variables**
    > - El probador de expresiones, accesible por **Herramientas → Probador de expresiones**
>
> UN **Ctrl Haga clic en el botón ejecutar** le permite guardar, ejecutar y mostrar directamente el registro del escenario (si el nivel de registro no es Ninguno).

## Pestaña General

En la pestaña **General**, encontramos los principales parámetros del escenario :

- **Nombre del escenario** : El nombre de tu escenario.
- **Nombre para mostrar** : El nombre utilizado para su visualización. Opcional, si no se completa, se usa el nombre del escenario.
- **Grupo** : Le permite organizar los escenarios, clasificándolos en grupos (visibles en la página de escenarios y en sus menús contextuales).
- **Activo** : Activa el escenario. Si no está activo, Jeedom no lo ejecutará, independientemente del modo de disparo.
- **Visible** : Se utiliza para hacer visible el escenario (Panel de control).
- **Objeto padre** : Asignación a un objeto padre. Entonces será visible o no según este padre.
- **Tiempo de espera en segundos (0 = ilimitado)** : El tiempo máximo de ejecución permitido para este escenario. Pasado este tiempo, se interrumpe la ejecución del escenario.
- **Lanzamiento múltiple** : Marque esta casilla si desea que el escenario se pueda iniciar varias veces al mismo tiempo.
>**IMPORTANTE**
>
>El lanzamiento múltiple funciona por segundo, es decir que si tienes 2 lanzamientos en el mismo segundo sin marcar la casilla, aún habrá 2 lanzamientos del escenario (aunque no debería). Asimismo, durante varios lanzamientos en el mismo segundo, algunos lanzamientos pueden perder las etiquetas. Conclusión es ABSOLUTAMENTE necesario evitar múltiples lanzamientos en el mismo segundo.
- **Modo síncrono** : Inicie el escenario en el hilo actual en lugar de un hilo dedicado. Aumenta la velocidad a la que se inicia el escenario, pero puede hacer que el sistema sea inestable.
- **Iniciar sesión** : El tipo de registro deseado para el escenario. Puede cortar los registros del escenario o por el contrario mostrarlo en Análisis → Tiempo real.
- **Cronología** : Mantenga un seguimiento del escenario en la línea de tiempo (consulte el documento Historial).
- **Icono** : Le permite elegir un icono para el escenario en lugar del icono estándar.
- **Descripción** : Le permite escribir un pequeño texto para describir su escenario.
- **Modo de escenario** : El escenario puede ser programado, activado o ambos. Luego tendrá la opción de indicar los activadores (máximo de 15 activadores) y la (s) programación (s)).

> **Propina**
>
> Las condiciones ahora se pueden ingresar en modo activado. Por ejemplo : ``#[Garage][Open Garage][Ouverture]# == 1``
> Atención : puede tener un máximo de 28 disparadores / programación para un escenario.

> **Modo de punta programado**
>
> El modo programado usa sintaxis **Cron**. Por ejemplo, puede ejecutar un escenario cada 20 minutos con  ``*/ /20 * * * *``, o a las 5 a.m. para arreglar una multitud de cosas para el día con ``0 5 * * *``. Los ? a la derecha de un programa le permite configurarlo sin ser un especialista en sintaxis de Cron.

## Pestaña Escenario

Aquí es donde construirás tu escenario. Después de crear el escenario, su contenido está vacío, por lo que hará ... nada. Tienes que empezar con **Agregar bloque**, con el botón de la derecha. Una vez que se ha creado un bloque, puede agregar otro **bloquear** o un **acción**.

Para mayor comodidad y no tener que reordenar constantemente los bloques en el escenario, se agrega un bloque después del campo en el que se encuentra el cursor del mouse.
*Por ejemplo, si tiene diez bloques y hace clic en la condición SI del primer bloque, el bloque agregado se agregará después de este bloque, en el mismo nivel. Si no hay ningún campo activo, se agregará al final del escenario.*

> **Propina**
>
> En condiciones y acciones, es mejor favorecer comillas simples (') en lugar de dobles (").

> **Propina**
>
> Un Ctrl Shift Z o Ctrl Shift Y le permite'**anular** o de **rehacer** una modificación (adición de acción, bloqueo...).

## Bloques

Aquí están los diferentes tipos de bloques disponibles :

- **If / Then / O** : Permite que las acciones se lleven a cabo bajo condiciones (si esto, entonces eso).
- **Acción** : Le permite lanzar acciones simples sin ninguna condición.
- **Lazo** : Permite que las acciones se realicen repetidamente desde 1 hasta un número definido (o incluso el valor de un sensor, o un número aleatorio).
- **Dentro** : Permite iniciar una acción en X minuto (s) (0 es un valor posible). La peculiaridad es que las acciones se inician en segundo plano, por lo que no bloquean el resto del escenario. Entonces es un bloque sin bloqueo.
- **A** : Permite decirle a Jeedom que inicie las acciones del bloque en un momento dado (en la forma hhmm). Este bloque no es bloqueante. Ex : 0030 para 00:30, o 0146 para 1h46 y 1050 para 10h50.
- **Codificado** : Le permite escribir directamente en código PHP (requiere cierto conocimiento y puede ser arriesgado, pero le permite no tener restricciones).
- **Comentario** : Le permite agregar comentarios a su escenario.

Cada bloque tiene sus opciones para manejarlos mejor :

- A la izquierda :
    - La flecha bidireccional le permite mover un bloque o una acción para reordenarlos en el escenario.
    - El ojo reduce un bloqueo (*colapso*) para reducir su impacto visual. Ctrl Clic en el ojo los reduce o los muestra todos.
    - La casilla de verificación le permite desactivar completamente el bloque sin eliminarlo. Por lo tanto, no se ejecutará.

- Sobre la derecha :
    - El icono Copiar le permite copiar el bloque para hacer una copia en otro lugar. Ctrl clic en el icono corta el bloque (copiar y luego eliminar).
    - El icono Pegar le permite pegar una copia del bloque copiado previamente después del bloque en el que utiliza esta función.  Ctrl Click en el icono reemplaza el bloque con el bloque copiado.
    - El icono: le permite eliminar el bloque con una solicitud de confirmación. Ctrl Click elimina el bloque sin confirmación.

### Si / Entonces / De lo contrario bloquea | Lazo | Dentro | A

Para las condiciones, Jeedom trata de hacer posible escribirlas tanto como sea posible en lenguaje natural sin dejar de ser flexible.
> NO use [] en pruebas de condición, solo son posibles paréntesis ().

Hay tres botones disponibles a la derecha de este tipo de bloque para seleccionar un elemento para probar :

- **Encuentra un pedido** : Le permite buscar un pedido en todos los disponibles en Jeedom. Una vez que se encuentra el pedido, Jeedom abre una ventana para preguntarle qué prueba desea realizar. Si eliges **No poner nada**, Jeedom agregará el pedido sin comparación. También puedes elegir **y** o **o** delante **Luego** para encadenar pruebas en diferentes equipos.
- **Buscar un escenario** : Le permite buscar un escenario para probar.
- **Busca equipo** : Lo mismo para el equipo.

> **Nota**
>
> En bloques de tipo Si / Entonces / De lo contrario, las flechas circulares a la izquierda del campo de condición permiten activar o no la repetición de acciones si la evaluación de la condición da el mismo resultado que durante la evaluación previa.
> SI expresión != 0 es equivalente a SI expresión y SI expresión == 0 es equivalente a SI no expresión

> **Propina**
>
> Hay una lista de etiquetas que permiten el acceso a las variables desde el escenario u otro, o por la hora, la fecha, un número aleatorio, ... Vea a continuación los capítulos sobre comandos y etiquetas.

Una vez que se completa la condición, debe usar el botón &quot;Agregar&quot; a la izquierda para agregar un nuevo **bloquear** o un **acción** en el bloque actual.


### Código de bloque

El bloque de código le permite ejecutar código php. Por lo tanto, es muy potente pero requiere un buen conocimiento del lenguaje php.

#### Acceso a controles (sensores y actuadores)

-  ``cmd::byString($string);`` : Devuelve el objeto de comando correspondiente.
    -   ``$string``: Enlace al pedido deseado : ``#[objet][equipo][commande]#`` (Ex : ``#[Appartement][Alarme][Activo]#``)
-  ``cmd::byId($id);`` : Devuelve el objeto de comando correspondiente.
    -  ``$id`` : ID de pedido.
-  ``$cmd->execCmd($options = null);`` : Ejecute el comando y devuelva el resultado.
    - ``$options`` : Opciones para la ejecución del comando (puede ser específico del complemento). Opciones básicas (subtipo de comando) :
        -  ``message`` : ``$option = array('title' => 'titre du mensaje , 'message' => 'Mon message');``
        -  ``color`` : ``$option = array('color' => 'couleur en hexadécimal');``
        -  ``slider`` : ``$option = array('slider' => 'valeur voulue de 0 à 100');``

#### Acceso a registros

-  ``log::add('filename','level','message');``
    - ``filename`` : Nombre del archivo de registro.
    - ``level`` : [depuración], [información], [error], [evento].
    - ``message`` : Mensaje para escribir en los registros.

#### Acceso a escenarios

- ``$scenario->getName();`` : Devuelve el nombre del escenario actual.
- ``$scenario->getGroup();`` : Devuelve el grupo de escenarios.
- ``$scenario->getIsActive();`` : Devuelve el estado del escenario.
- ``$scenario->setIsActive($active);`` : Le permite activar o no el escenario.
    - ``$active`` : 1 activo, 0 no activo.
- ``$scenario->running();`` : Se usa para averiguar si el escenario se está ejecutando o no (verdadero / falso).
- ``$scenario->save();`` : Guardar cambios.
- ``$scenario->setData($key, $value);`` : Guardar un dato (variable).
    - ``$key`` : clave de valor (int o string).
    - ``$value`` : valor para almacenar (``int``, ``string``, ``array`` o ``object``).
- ``$scenario->getData($key);`` : Obtener datos (variable).
    - ``$key => 1`` : clave de valor (int o string).
- ``$scenario->removeData($key);`` : Eliminar datos.
- ``$scenario->setLog($message);`` : Escribe un mensaje en el registro del script.
- ``$scenario->persistLog();`` : Forzar la escritura del registro (de lo contrario, se escribe solo al final del escenario). Tenga cuidado, esto puede retrasar un poco el escenario.

> **Propina**
>
> Adición de una función de búsqueda en el bloque de Código : Buscar : Ctrl + F luego Enter, Siguiente resultado : Ctrl + G, resultado anterior : Ctrl + Mayús + G

[Escenarios : Pequeños códigos con amigos](https:/ // /kiboost.github.io/ /jeedom_docs/ /jeedomV4Tips/ /CodesScenario/ /)

### Bloque de comentarios

El bloque de comentarios actúa de manera diferente cuando está oculto. Sus botones a la izquierda desaparecen, así como el título del bloque, y reaparecen al pasar el cursor. Del mismo modo, la primera línea del comentario se muestra en negrita.
Esto permite que este bloque se utilice como una separación puramente visual dentro del escenario.

### Las acciones

Las acciones agregadas a los bloques tienen varias opciones :

- Una cabaña **activado** para que este comando se tenga en cuenta en el escenario.
- Una cabaña **paralelas** para que este comando se inicie en paralelo (al mismo tiempo) con los otros comandos también seleccionados.
- UN **flecha doble vertical** para mover la acción. Solo arrastra y suelta desde allí.
- Un botón para **Eliminar** la acción.
- Un botón para acciones específicas, con cada vez la descripción (al pasar el mouse) de esta acción.
- Un botón para buscar un comando de acción.

> **Propina**
>
> Dependiendo del comando seleccionado, se pueden mostrar diferentes campos adicionales.

## Posibles sustituciones

### Disparadores

Hay desencadenantes específicos (distintos de los proporcionados por los comandos) :

- ``#start#`` : Activado al (re) inicio de Jeedom.
- ``#begin_backup#`` : Evento enviado al inicio de una copia de seguridad.
- ``#end_backup#`` : Evento enviado al final de una copia de seguridad.
- ``#begin_update#`` : Evento enviado al inicio de una actualización.
- ``#end_update#`` : Evento enviado al final de una actualización.
- ``#begin_restore#`` : Evento enviado al inicio de una restauración.
- ``#end_restore#`` : Evento enviado al final de una restauración.
- ``#user_connect#`` : Inicio de sesión de usuario
- ``#variable(nom_variable)#`` : Cambiar el valor de la variable name_name.
- ``#genericType (GENÉRICO, #[Object]#)#`` : Cambio de un comando de información de tipo GENÉRICO genérico, en el objeto Objeto.

También puede activar un escenario utilizando la API HTTP descrita [aquí](https:/ // /doc.jeedom.com/es_ES/core/ /4.1/ /api_http).

### Operadores de comparación y enlaces entre condiciones

Puede utilizar cualquiera de los siguientes símbolos para realizar comparaciones en condiciones :

- ``==`` : Igual a.
- ``>`` : Estrictamente mayor que.
- ``>=`` : Mayor o igual que.
- ``<`` : Estrictamente menor que.
- ``<=`` : Menor o igual que.
- ``!=`` : Diferente de, no es igual a.
- ``matches`` : Contiene. Ex : ``[Salle de bain][Hydrometrie][etat] matches "/ /humide/ /"``.
- ``not ( …​ matches …​)`` : No contiene. Ex :  ``not([Salle de bain][Hydrometrie][etat] matches "/ /humide/ /")``.

Puedes combinar cualquier comparación con los siguientes operadores :

- ``YY`` / / ``ET`` / / ``et`` / / ``AND`` / / ``and`` : et,
- ``||`` / / ``OU`` / / ``ou`` / / ``OR`` / / ``or`` : ou,
- ``^`` / / ``XOR`` / / ``xor`` : o exclusivo.

### Etiquetas

Una etiqueta se reemplaza durante la ejecución del escenario por su valor. Puedes usar las siguientes etiquetas :

> **Propina**
>
> Para mostrar los ceros iniciales, use la función Fecha (). Ver [aquí](http:/ // /php.net/ /manual/ /fr/ /function.date.php).

- ``#seconde#`` : Segundo actual (sin ceros a la izquierda, ej : 6 para 08:07:06).
- ``#hour#`` : Hora actual en formato de 24 h (sin ceros a la izquierda)). Ex : 8 para 08:07:06 o 17 para 17:15.
- ``#hour12#`` : Hora actual en formato de 12 horas (sin ceros a la izquierda)). Ex : 8 para 08:07:06.
- ``#minute#`` : Minuto actual (sin ceros a la izquierda). Ex : 7 para 08:07:06.
- ``#day#`` : Día actual (sin ceros a la izquierda)). Ex : 6 para 06/07/2017.
- ``#month#`` : Mes actual (sin ceros a la izquierda). Ex : 7 para 06/07/2017.
- ``#year#`` : Año actual.
- ``#time#`` : Hora y minuto actual. Ex : 1715 para las 5.15 p.m.
- ``#timestamp#`` : Número de segundos desde el 1 de enero de 1970.
- ``#date#`` : Día y mes. Atención, el primer número es el mes. Ex : 1215 para el 15 de diciembre.
- ``#week#`` : Número de semana.
- ``#sday#`` : Nombre del día de la semana. Ex : Sábado.
- ``#nday#`` : Número de día de 0 (domingo) a 6 (sábado)).
- ``#smonth#`` : Nombre del mes. Ex : Enero.
- ``#IP#`` : IP interna de Jeedom.
- ``#hostname#`` : Nombre de la máquina Jeedom.
- ``#trigger#`` (en desuso, mejor usar ``trigger()``) : Quizás el nombre del comando que inició el escenario :
    - ``api`` si el lanzamiento fue activado por la API,
    - ``schedule`` si se inició programando,
    - ``user`` si se inició manualmente,
    - ``start`` para un lanzamiento al inicio de Jeedom.
- ``#trigger_value#`` (en desuso, mejor usar triggerValue()) : Por el valor del comando que activó el escenario

También tiene las siguientes etiquetas adicionales si su escenario fue desencadenado por una interacción :

- #query# : Interacción que desencadenó el escenario.
- #profil# : Perfil del usuario que inició el escenario (puede estar vacío).

> **IMPORTANTE**
>
> Cuando una interacción desencadena un escenario, se ejecuta necesariamente en modo rápido. Entonces, en el hilo de interacción y no en un hilo separado.

### Funciones de cálculo

Hay varias funciones disponibles para el equipo :

- ``average(commande,période)`` Y ``averageBetween(commande,start,end)`` : Indique el promedio del pedido durante el período (período=[mes, día, hora, min] o [expresión PHP](http:/ // /php.net/ /manual/ /fr/ /datetime.formats.relative.php)) o entre los 2 terminales solicitados (en el formulario ``Y-m-d H:i:s`` o [Expresión PHP](http:/ // /php.net/ /manual/ /fr/ /datetime.formats.relative.php)).

- ``averageTemporal(commande,période)`` Y ``averageTemporalBetween(commande,start,end)`` : Indique el promedio de los valores del pedido ponderado por su duración de existencia durante el período (período=[mes, día, hora, min] o [expresión PHP](http:/ // /php.net/ /manual/ /fr/ /datetime.formats.relative.php)) o entre los 2 terminales solicitados (en el formulario ``Y-m-d H:i:s`` o [Expresión PHP](http:/ // /php.net/ /manual/ /fr/ /datetime.formats.relative.php)).

- ``min(commande,période)`` Y ``minBetween(commande,start,end)`` : Dar el pedido mínimo durante el período (período=[mes, día, hora, min] o [expresión PHP](http:/ // /php.net/ /manual/ /fr/ /datetime.formats.relative.php)) o entre los 2 terminales solicitados (en el formulario ``Y-m-d H:i:s`` o [Expresión PHP](http:/ // /php.net/ /manual/ /fr/ /datetime.formats.relative.php)).

- ``max(commande,période)`` Y ``maxBetween(commande,start,end)`` : Dar el máximo del pedido durante el período (período=[mes, día, hora, min] o [expresión PHP](http:/ // /php.net/ /manual/ /fr/ /datetime.formats.relative.php)) o entre los 2 terminales solicitados (en el formulario ``Y-m-d H:i:s`` o [Expresión PHP](http:/ // /php.net/ /manual/ /fr/ /datetime.formats.relative.php)).

- ``duration(commande, valeur, période)`` Y ``durationbetween(commande,valeur,start,end)`` : Indique la duración en minutos durante los cuales el equipo tuvo el valor elegido durante el período (período=[mes, día, hora, min] o [expresión PHP](http:/ // /php.net/ /manual/ /fr/ /datetime.formats.relative.php)) o entre los 2 terminales solicitados (en el formulario ``Y-m-d H:i:s`` o [Expresión PHP](http:/ // /php.net/ /manual/ /fr/ /datetime.formats.relative.php)).

- ``statistics(commande,calcul,période)`` Y ``statisticsBetween(commande,calcul,start,end)`` : Proporcione el resultado de diferentes cálculos estadísticos (suma, recuento, estándar, varianza, promedio, mínimo, máximo) durante el período (período=[mes, día, hora, min] o [expresión PHP](http:/ // /php.net/ /manual/ /fr/ /datetime.formats.relative.php)) o entre los 2 terminales solicitados (en el formulario ``Y-m-d H:i:s`` o [Expresión PHP](http:/ // /php.net/ /manual/ /fr/ /datetime.formats.relative.php)).

- ``tendance(commande,période,seuil)`` : Da la tendencia del pedido durante el período (período=[mes, día, hora, min] o [expresión PHP](http:/ // /php.net/ /manual/ /fr/ /datetime.formats.relative.php)).

- ``stateDuration(commande)`` : Da la duración en segundos desde el último cambio de valor.
    -1 : No existe historial o el valor no existe en el historial.
    -2 : El pedido no está registrado.

- ``lastChangeStateDuration(commande,valeur)`` : Da la duración en segundos desde el último cambio de estado al valor pasado en el parámetro.
    -1 : No existe historial o el valor no existe en el historial.
    -2 El pedido no está registrado

- ``lastStateDuration(commande,valeur)`` : Da la duración en segundos durante los cuales el equipo ha tenido el último valor elegido.
    -1 : No existe historial o el valor no existe en el historial.
    -2 : El pedido no está registrado.

- ``age(commande)`` : Da la edad en segundos del valor del comando (``collecDate``)
    -1 : El comando no existe o no es de tipo información.

- ``stateChanges(commande,[valeur], période)`` Y ``stateChangesBetween(commande, [valeur], start, end)`` : Da el número de cambios de estado (hacia un cierto valor si se indica, o si no se indica en comparación con su valor actual) durante el período (período=[mes, día, hora, min] o [expresión PHP](http:/ // /php.net/ /manual/ /fr/ /datetime.formats.relative.php)) o entre los 2 terminales solicitados (en el formulario ``Y-m-d H:i:s`` o [Expresión PHP](http:/ // /php.net/ /manual/ /fr/ /datetime.formats.relative.php)).

- ``lastBetween(commande,start,end)`` : Da el último valor registrado para el dispositivo entre los 2 terminales solicitados (en el formulario ``Y-m-d H:i:s`` o [Expresión PHP](http:/ // /php.net/ /manual/ /fr/ /datetime.formats.relative.php)).

- ``variable(mavariable,valeur par défaut)`` : Recupera el valor de una variable o el valor deseado por defecto.

- ``genericType (GENÉRICO, #[Object]#)`` : Recupera la suma de la información GENÉRICA del tipo genérico en el objeto Objeto.

- ``scenario(scenario)`` : Devuelve el estado del escenario.
    1 : En curso,
    0 : Detenido,
    -1 : Discapacitado,
    -2 : El escenario no existe,
    -3 : El estado no es consistente.
    Para tener el nombre &quot;humano&quot; del escenario, puede usar el botón dedicado a la derecha de la búsqueda del escenario.

- ``lastScenarioExecution(scenario)`` : Da la duración en segundos desde el último lanzamiento del escenario.
    0 : El escenario no existe

- ``collectDate(cmd,[format])`` : Devuelve la fecha de la última recopilación de datos para el comando colocado como parámetro, el segundo parámetro opcional se usa para especificar el formato de retorno (detalles [aquí](http:/ // /php.net/ /manual/ /fr/ /function.date.php)).
    -1 : No se pudo encontrar el comando,
    -2 : El comando no es de tipo info.

- ``valueDate(cmd,[format])`` : Devuelve la fecha del último valor conocido para el comando colocado como parámetro, el segundo parámetro opcional se usa para especificar el formato de retorno (detalles [aquí](http:/ // /php.net/ /manual/ /fr/ /function.date.php)).
    -1 : No se pudo encontrar el comando,
    -2 : El comando no es de tipo info.

- ``eqEnable(equipement)`` : Devuelve el estado del equipo.
    -2 : No se puede encontrar el equipo,
    1 : El equipo esta activo,
    0 : El equipo esta inactivo.

- ``value(cmd)`` : Devuelve el valor de un pedido si Jeedom no lo proporciona automáticamente (caso al almacenar el nombre del pedido en una variable)

- ``tag(montag,[defaut])`` : Se usa para recuperar el valor de una etiqueta o el valor predeterminado si no existe.

- ``name(type,commande)`` : Se utiliza para recuperar el nombre del pedido, equipo u objeto. Tipo : cmd, eqLogic u objeto.

- ``lastCommunication(equipment,[format])`` : Devuelve la fecha de la última comunicación para el dispositivo dada como parámetro, el segundo parámetro opcional se usa para especificar el formato de retorno (detalles [aquí](http:/ // /php.net/ /manual/ /fr/ /function.date.php)). Un retorno de -1 significa que no se puede encontrar el equipo.

- ``color_gradient(couleur_debut,couleur_fin,valuer_min,valeur_max,valeur)`` : Devuelve un color calculado en relación con un valor en el intervalo color_start / color_end. El valor debe estar entre min_value y max_value.

Los períodos e intervalos de estas funciones también se pueden usar con [Expresiones PHP](http:/ // /php.net/ /manual/ /fr/ /datetime.formats.relative.php) como por ejemplo :

- ``Now`` : Ahora.
- ``Today`` : 00:00 hoy (permite, por ejemplo, obtener resultados para el día si entre ``Today`` y ``Now``).
- ``Last Monday`` : el lunes pasado a las 00:00.
- ``5 days ago`` : Hace 5 dias.
- ``Yesterday noon`` : ayer mediodia.
- Etc..

Aquí hay ejemplos prácticos para comprender los valores devueltos por estas diferentes funciones :

| Zócalo con valores :           | 000 (por 10 minutos) 11 (por 1 hora) 000 (por 10 minutos)    |
|--------------------------------------|--------------------------------------|
| ``average(prise,période)``             | Devuelve el promedio de 0 y 1 (puede  |
|                                      | ser influenciado por las encuestas)      |
| ``averageBetween(#[Salle de bain][Hydrometrie][Humidité]#,2015-01-01 00:00:00,2015-01-15 00:00:00)`` | Devuelve el pedido promedio entre el 1 de enero de 2015 y el 15 de enero de 2015                       |
| ``min(prise,période)``                 | Devuelve 0 : el tapón se apagó durante el período              |
| ``minBetween(#[Salle de bain][Hydrometrie][Humidité]#,2015-01-01 00:00:00,2015-01-15 00:00:00)`` | Devuelve el pedido mínimo entre el 1 de enero de 2015 y el 15 de enero de 2015                       |
| ``max(prise,période)``                 | Devuelve 1 : el enchufe estaba bien iluminado en el período              |
| ``maxBetween(#[Salle de bain][Hydrometrie][Humidité]#,2015-01-01 00:00:00,2015-01-15 00:00:00)`` | Devuelve el máximo del pedido entre el 1 de enero de 2015 y el 15 de enero de 2015                       |
| ``duration(prise,1,période)``          | Devuelve 60 : el enchufe estuvo encendido (a 1) durante 60 minutos en el período                              |
| ``durationBetween(#[Salon][Prise][Etat]#,0,Last Monday,Now)``   | Devuelve la duración en minutos durante la cual el socket estuvo apagado desde el lunes pasado.                |
| ``statistics(prise,count,période)``    | Devuelve 8 : hubo 8 escaladas en el período               |
| ``tendance(prise,période,0.1)``        | Devuelve -1 : tendencia a la baja    |
| ``stateDuration(prise)``               | Devuelve 600 : el enchufe ha estado en su estado actual durante 600 segundos (10 minutos)                             |
| ``lastChangeStateDuration(prise,0)``   | Devuelve 600 : el zócalo se apagó (cambie a 0) por última vez hace 600 segundos (10 minutos)     |
| ``lastChangeStateDuration(prise,1)``   | Devuelve 4200 : el zócalo se encendió (cambie a 1) por última vez hace 4200 segundos (1h10)                               |
| ``lastStateDuration(prise,0)``         | Devuelve 600 : el enchufe ha estado apagado por 600 segundos (10 minutos)     |
| ``lastStateDuration(prise,1)``         | Devuelve 3600 : el zócalo se encendió por última vez durante 3600 segundos (1 hora)           |
| ``stateChanges(prise,période)``        | Devuelve 3 : el conector ha cambiado de estado 3 veces durante el período (si el comando de información es de tipo binario)            |
| ``stateChanges(prise,0,période)``      | Devuelve 2 : el zócalo se ha apagado (yendo a 0) dos veces durante el período                              |
| ``stateChanges(prise,1,période)``      | Devuelve 1 : el enchufe se enciende (cambie a 1) una vez durante el período                              |
| ``lastBetween(#[Salle de bain][Hydrometrie][Température]#,Yesterday,Today)`` | Devuelve la última temperatura registrada ayer.                    |
| ``variable(plop,10)``                  | Devuelve el valor de la variable plop o 10 si está vacía o no existe                         |
| ``scenario(#[Salle de bain][Lumière][Auto]#)`` | Devuelve 1 en progreso, 0 si se detiene y -1 si está desactivado, -2 si el escenario no existe y -3 si el estado no es consistente                         |
| ``lastScenarioExecution(#[Salle de bain][Lumière][Auto]#)``   | Devuelve 300 si el escenario se inició por última vez hace 5 minutos                                  |
| ``collectDate(#[Salle de bain][Hydrometrie][Humidité]#)``     | Devuelve 2021-02-14 17:50:12          |
| ``valueDate(#[Salle de bain][Hydrometrie][Humidité]#)`` | Devuelve 2021-02-14 17:45:12          |
| ``eqEnable(#[Aucun][Basilique]#)``       | Devuelve -2 si no se encuentra el equipo, 1 si el equipo está activo y 0 si está inactivo          |
| ``tag(montag,toto)``                   | Devuelve el valor de "montag" si existe, de lo contrario devuelve el valor "toto"                               |
| ``name(eqLogic,#[Salle de bain][Hydrometrie][Humidité]#)``     | Hidrometría de devoluciones                  |


### Funciones matematicas

También se puede usar una caja de herramientas de funciones genéricas para realizar conversiones o cálculos :

- ``rand(1,10)`` : Dar un número aleatorio del 1 al 10.
- ``randText(texte1;texte2;texte…​..)`` : Le permite devolver uno de los textos al azar (separe los textos por uno; ). No hay límite en el número de textos.
- ``randomColor(min,max)`` : Da un color aleatorio entre 2 límites (0 => rojo, 50 => verde, 100 => azul).
- ``trigger(commande)`` : Le permite descubrir el desencadenante del escenario o saber si es el comando pasado como parámetro el que desencadenó el escenario.
- ``triggerValue()`` : Se usa para averiguar el valor del desencadenante del escenario.
- ``round(valeur,[decimal])`` : Redondea arriba, número [decimal] de lugares decimales después del punto decimal.
- ``odd(valeur)`` : Le permite saber si un número es impar o no. Devuelve 1 si es impar 0 de lo contrario.
- ``median(commande1,commande2…​.commandeN)`` : Devuelve la mediana de los valores.
- ``avg(commande1,commande2…​.commandeN)`` : Devuelve el promedio de los valores.
- ``time_op(time,value)`` : Le permite realizar operaciones a tiempo, con tiempo = tiempo (ej : 1530) y valor = valor para sumar o restar en minutos.
- ``time_between(time,start,end)`` : Se usa para probar si un tiempo está entre dos valores con ``time=temps`` (Ex : 1530), ``start=temps``, ``end=temps``. Los valores iniciales y finales pueden estar a caballo entre la medianoche.
- ``time_diff(date1,date2[,format, round])`` : Se usa para descubrir la diferencia entre dos fechas (las fechas deben estar en el formato AAAA / MM / DD HH:MM:SS). Por defecto, el método devuelve la diferencia en día (s). Puedes preguntarlo en segundos (s), minutos (m), horas (h). Ejemplo en segundos ``time_diff(2019-02-02 14:55:00,2019-02-25 14:55:00,s)``. La diferencia se devuelve en absoluto, a menos que especifique ``f`` (``sf``, ``mf``, ``hf``, ``df``). También puedes usar ``dhms`` quien no devolverá ejemplo ``7j 2h 5min 46s``. El parámetro de redondeo opcional se redondea a x decimales (2 por defecto). Ex: ``time_diff(2020-02-21 20:55:28,2020-02-28 23:01:14,df, 4)``.
- ``formatTime(time)`` : Formatea el retorno de una cadena ``#time#``.
- ``floor(time/ /60)`` : Convierte segundos a minutos o minutos a horas (``floor(time/ /3600)`` por segundos a horas).
- ``convertDuration(secondes)`` : Convierte segundos a d / h / min / s.

Y ejemplos prácticos :


| Ejemplo de funcion                  | Resultado devuelto                    |
|--------------------------------------|--------------------------------------|
| ``randText(il fait #[salon][oeil][température]#;La température est de #[salon][oeil][température]#;Actuellement on a #[salon][oeil][température]#)`` | la función devolverá uno de estos textos al azar en cada ejecución.                           |
| ``randomColor(40,60)``                 | Devuelve un color aleatorio cercano al verde.
| ``trigger(#[Salle de bain][Hydrometrie][Humidité]#)``   | 1 si es bueno ``#[Salle de bain][Hydrometrie][Humidité]#`` quien inició el escenario de lo contrario 0  |
| ``triggerValue()`` | 80 si la hidrometría de ``#[Salle de bain][Hydrometrie][Humidité]#`` es 80% y eso es ``#[Salle de bain][Hydrometrie][Humidité]#`` quien desencadenó el escenario. Si el escenario no fue activado por un comando, devuelve "falso".                         |
| ``round(#[Salle de bain][Hydrometrie][Humidité]# / / 10)`` | Devuelve 9 si el porcentaje de humedad y 85                     |
| ``odd(3)``                             | Devuelve 1                            |
| ``median(15,25,20)``                   | Devuelve 20
| ``avg(10,15,18)``                      | Devuelve 14.3                     |
| ``time_op(#time#, -90)``               | si son las 4:50 p.m., regrese : 1 650-1 130 = 1520                          |
| ``formatTime(1650)``                   | Devuelve 4:50 p.m                        |
| ``floor(130/ /60)``                     | Devuelve 2 (minutos si 130 s, u horas si 130 m)                      |
| ``convertDuration(3600)``             | Devuelve 1h 0min 0s                      |
| ``convertDuration(duration(#[Chauffage][Module chaudière][Etat]#,1, first day of this month)*60)`` | Devuelve el tiempo de encendido en días / horas / minutos del tiempo de transición al estado 1 del módulo desde el primer día del mes |


### Pedidos específicos

Además de los comandos de automatización del hogar, tiene acceso a las siguientes acciones :

- **Pausa** (sleep) : Pausa de x segundo (s).
- **variable** (variable) : Creación / modificación de una variable o el valor de una variable.
- **Eliminar variable** (delete_variable) : Le permite eliminar una variable.
- **genericType (GENÉRICO, #[Object]#)** : Modificación de un comando de información (evento) o acción (execCmd) por tipo genérico, en un objeto. Por ejemplo, apague todas las luces de la sala de estar.
- **Guión** (scenario) : Te permite controlar escenarios. La parte de etiquetas le permite enviar etiquetas al escenario, ej : montag = 2 (ten cuidado, solo usa letras de la a a la z. Sin mayúsculas, sin acentos y sin caracteres especiales). Recuperamos la etiqueta en el escenario de destino con la función de etiqueta (montag).
    - Comenzar : Inicie el escenario en un hilo diferente. El escenario iniciado se ejecuta independientemente del escenario de llamada.
    - Iniciar (sincronizar) : Inicia el escenario llamado y pausa el escenario de llamada, mientras que el escenario llamado ha terminado de ejecutarse.
    - Parar : Detener el escenario.
    - Activar : Activar un escenario discapacitado.
    - Desactivar : Desactivar escenario. Ya no se inicia independientemente de los desencadenantes.
    - Restablecimiento de los IS : Se utiliza para restablecer el estado de **SI**. Este estado se utiliza para la no repetición de las acciones de un **SI**, si la evaluación de la condición da el mismo resultado que la evaluación anterior.
- **Detener** (stop) : Detener el escenario.
- **Esperar** (wait) : Espere hasta que la condición sea válida (máximo 2 h), el tiempo de espera es en segundos (s).
- **Ir al diseño** (gotodesign) : Cambie el diseño que se muestra en todos los navegadores por el diseño solicitado.
- **Agregar un registro** (log) : Le permite agregar un mensaje a los registros.
- **Crear mensaje** (message) : Agregar un mensaje al centro de mensajes.
- **Activar / Desactivar Ocultar / mostrar equipo** (equipement) : Le permite modificar las propiedades de los equipos visibles / invisibles, activos / inactivos.
- **Hacer una solicitud** (ask) : Permite indicar a Jeedom que es necesario hacerle una pregunta al usuario. La respuesta se almacena en una variable, entonces solo tiene que probar su valor.
    Por el momento, solo los complementos sms, slack, telegram y snips son compatibles, así como la aplicación móvil.
    Atención, esta función está bloqueando. Mientras no haya respuesta o no se alcance el tiempo de espera, el escenario espera.
- **Stop Jeedom** (jeedom_poweroff) : Pídale a Jeedom que cierre.
- **Devolver un texto / datos** (vuelta_escenario) : Devuelve un texto o un valor para una interacción, por ejemplo.
- **Icono** (icon) : Permite cambiar el ícono de representación del escenario.
- **Alerta** (alert) : Muestra un pequeño mensaje de alerta en todos los navegadores que tienen abierta una página de Jeedom. Además, puedes elegir 4 niveles de alerta.
- **Surgir** (popup) : Permite mostrar una ventana emergente que debe validarse absolutamente en todos los navegadores que tienen una página abierta.
- **Relación** (report) : Le permite exportar una vista en formato (PDF, PNG, JPEG o SVG) y enviarla utilizando un comando de tipo mensaje. Tenga en cuenta que si su acceso a Internet está en HTTPS sin firmar, esta funcionalidad no funcionará. Se requiere HTTP o HTTPS firmado.
- **Eliminar bloque IN / A programado** (remove_inat) : Le permite eliminar la programación de todos los bloques IN y A del escenario.
- **Evento** (event) : Le permite insertar un valor en un comando de tipo de información arbitrariamente.
- **Etiqueta** (tag) : Le permite agregar / modificar una etiqueta (la etiqueta solo existe durante la ejecución actual del escenario a diferencia de las variables que sobreviven al final del escenario).
- **Coloración de los iconos del tablero** (setColoredIcon) : permite activar o no la coloración de iconos en el tablero.
- **Exportación histórica** (exportHistory) : permite exportar el historial en csv de un pedido en forma de archivo (envío por correo por ejemplo). Puede poner varios comandos (separados por &&). La selección del período se realiza en el formulario :
  - "-1 mes "=> -1 mes
  - "-1 día medianoche "=> -1 día a medianoche
  - "ahora "=> ahora
  - "lunes de esta semana medianoche "=> Lunes de esta semana a medianoche
  - "último domingo 23:59 "=> domingo anterior a las 11:59 p.m
  - "último día del mes anterior 23:59 "=> último día del mes anterior a las 11:59 p.m
  - "primer día de enero de este año medianoche "=> primer día de enero a medianoche
  - ...

### Plantilla de escenario

Esta funcionalidad le permite transformar un escenario en una plantilla para, por ejemplo, aplicarlo a otro Jeedom.

Haciendo clic en el botón **Modelo** en la parte superior de la página, abre la ventana de administración de plantillas.

A partir de ahí, tienes la posibilidad :

- Enviar una plantilla a Jeedom (archivo JSON recuperado previamente).
- Consulte la lista de escenarios disponibles en el mercado.
- Cree una plantilla a partir del escenario actual (no olvide dar un nombre).
- Para consultar las plantillas actualmente presentes en su Jeedom.

Al hacer clic en una plantilla, puede :

- **Compartir, repartir** : Comparta la plantilla en el mercado.
- **Eliminar** : Eliminar plantilla.
- **Descargar** : Obtenga la plantilla como un archivo JSON para enviarla a otro Jeedom, por ejemplo.

A continuación, tiene la parte para aplicar su plantilla al escenario actual.

Dado que de un Jeedom a otro o de una instalación a otra, los comandos pueden ser diferentes, Jeedom le solicita la correspondencia de los comandos entre los presentes durante la creación de la plantilla y los presentes en el hogar. Solo tiene que completar la correspondencia de las órdenes y luego aplicar.

## Adición de la función php

> **IMPORTANTE**
>
> Agregar funciones PHP está reservado para usuarios avanzados. El más mínimo error puede ser fatal para su Jeedom.

### Configurar

Vaya a la configuración de Jeedom, luego OS / DB e inicie el editor de archivos.

Vaya a la carpeta de datos, luego php y haga clic en el archivo user.function.class.php.

Es en esto *clase* que puedes agregar tus funciones, allí encontrarás un ejemplo de una función básica.

> **IMPORTANTE**
>
> Si tiene alguna inquietud, siempre puede volver al archivo original copiando el contenido de ``user.function.class.sample.php`` Dentro ``user.function.class.php``
