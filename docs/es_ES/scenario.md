# Escenarios
**Herramientas → Escenarios**

<small>[Raccorcis clavier/soris](shortcuts.md)</small>

Cerebro real de la automatización del hogar, los escenarios permiten interactuar con el mundo real de una manera inteligente **.

## Ladministración

Lallí encontrará la lista de escenarios de su Jeedom, así como las funcionalidades para administrarlos en el mejor de los casos. :

- **Lañadir** : Crea un escenario. El procedimiento se describe en el siguiente capítulo..
- **Deshabilitar escenarios** : Deshabilita todos los escenarios.. Raramente utilizado y a sabiendas, ya que ningún escenario se ejecutará más.
- **Resumen** : El permite tener una visión general de todos los escenarios.. Puedes cambiar los valores **bienes**, **visible**, **lanzamiento múltiple**, **modo síncrono**, **Registro** y **Línea de tiempo** (estos parámyros se describen en el siguiente capítulo). También puede acceder a los registros para cada escenario e iniciarlos individualmente.

## Mis escenarios

En esta sección encontrarás el **lista de escenarios** que creaste. Se clasifican según su **Grupo**, posiblemente definido para cada uno de ellos. Cada escenario se muestra con su **apellido** y su **objyo padre**. La **escenarios atenuados** son los que están deshabilitados.

> **Punta**
>
> Puede abrir un escenario haciendo :
> - Haga clic en uno de ellos..
> - Ctrl Clic o Clic Center para abrirlo en una nueva pestaña del navegador.

Tiene un motor de búsqueda para filtrar la visualización de escenarios.. La tecla Escape cancela la búsqueda..
La la derecha del campo de búsqueda, se encuentran tres botones en varios lugares de Jeedom:
- La cruz para cancelar la búsqueda..
- La carpya abierta para desplegar todos los paneles y mostrar todos los escenarios..
- La carpya cerrada para doblar todos los paneles.

Unaa vez en la configuración de un escenario, tiene un menú contextual con clic derecho en las pestañas del escenario. También puede usar Ctrl Click o Click Center para abrir directamente otro escenario en una nueva pestaña del navegador.

## Creación | Editar un escenario

Después de hacer clic en **Lañadir**, debes elegir el apellidobre de tu escenario. Luego se lo redirige a la página de sus parámyros generales.
Lantes de eso, en la parte superior de la página, hay algunas funciones útiles para administrar este escenario :

- **Identificación** : Lal lado de la palabra **General**, este es el identificador de escenario.
- **estatus** : *Dyenido * o * En progreso *, indica el estado actual del escenario.
- **Lagregar un bloque** : El permite agregar un bloque del tipo deseado al escenario (ver más abajo).
- **Registro** : Muestra los registros del escenario..
- **Duplicado** : Copie el escenario para crear uno nuevo con otro apellidobre.
- **Vínculos** : El permite ver el gráfico de los elementos relacionados con el escenario..
- **Edición de texto** : Muestra una ventana que permite editar el escenario en forma de texto / json. No olvides guardar.
- **Exportación** : El permite obtener una versión de texto puro del escenario.
- **Plantilla** : El permite acceder a las plantillas y aplicar una al mercado desde el mercado.. (explicado al final de la página).
- **Buscar** : Despliega un campo de búsqueda para buscar en el escenario. Esta búsqueda despliega los bloques colapsados si es necesario y los pliega nuevamente después de la búsqueda.
- **Realizar** : El permite iniciar el escenario manualmente (independientemente de los desencadenantes). Guardar de antemano para tener en cuenta las modificaciones..
- **Remove** : Eliminar escenario.
- **Guardar** : Guardar los cambios realizados.

> **Consejos**
>
> Dos herramientas también serán invaluables para usted en la configuración de escenarios :
    > - Las variables, visibles en **Herramientas → Variables**
    > - El probador de expresiones, accesible por **Herramientas → Probador de expresiones**
>
> Una **Ctrl Haga clic en el botón ejecutar** le permite guardar, ejecutar y mostrar directamente el registro del escenario (si el nivel de registro no es Ninguno).

### Pestaña General

En la pestaña **General**, encontramos los principales parámyros del escenario :

- **Nombre del escenario** : El apellidobre de tu escenario.
- **Nombre para mostrar** : El apellidobre utilizado para su visualización.. Opcional, si no se complya, se usa el apellidobre del escenario.
- **Grupo** : El permite organizar los escenarios, clasificándolos en grupos (visibles en la página de escenarios y en sus menús contextuales).
- **Bienes** : Lactiva el escenario. Si no está activo, Jeedom no lo ejecutará, independientemente del modo de disparo.
- **Visible** : El permite hacer visible el escenario (Panel de control).
- **Objyo padre** : Lasignación a un objyo padre. Entonces será visible o no según este padre.
- **Tiempo de espera en segundos (0 = ilimitado)** : El tiempo máximo de ejecución permitido para este escenario. Más allá de este tiempo, la ejecución del escenario se interrumpe..
- **Lanzamiento múltiple** : Marque esta casilla si desea que el escenario se pueda iniciar varias veces al mismo tiempo.
- **Modo síncrono** : Inicie el escenario en el hilo actual en lugar de un hilo dedicado. Laumenta la velocidad a la que se inicia el escenario, pero puede hacer que el sistema sea inestable.
- **Registro** : El tipo de registro deseado para el escenario. Puede cortar el registro del escenario o, por el contrario, hacer que aparezca en Lanálisis → Tiempo real.
- **Línea de tiempo** : Mantenga un seguimiento del escenario en la línea de tiempo (vea el documento de Historia).
- **Icono** : El permite elegir un icono para el escenario en lugar del icono estándar.
- **Descripción** : El permite escribir un pequeño texto para describir su escenario.
- **Modo de escenario** : El escenario puede ser programado, activado o ambos. Luego tendrá la opción de indicar los activadores (máximo de 15 activadores) y la (s) programación (s).

> **Punta**
>
> Las condiciones ahora se pueden ingresar en modo activado. Por ejemplo : `#[Garage][Open Garage][Ouverture]# == 1`
> Latención : puede tener un máximo de 28 disparadores / programación para un escenario.

> **Modo de punta programado**
>
> El modo programado usa sintaxis **Cron**. Vos porrez par exemple exécuté un scénario totes les 20 minutos avec  `*/20 * * * * `, o à 5h du matin por régler une multitude de choses por la jornée avec `0 5 * * *`. El ? a la derecha de un programa le permite configurarlo sin ser un especialista en sintaxis de Cron.

### Pestaña Escenario

Laquí es donde construirás tu escenario. Después de crear el escenario, su contenido está vacío, por lo que hará ... nada. Tienes que empezar con **agregar un bloque**, con el botón de la derecha. Unaa vez que se ha creado un bloque, puede agregar otro **bloque** o un **acción**.

Para mayor comodidad y no tener que reordenar constantemente los bloques en el escenario, se agrega un bloque después del campo en el que se encuentra el cursor del mose.
*Por ejemplo, si tiene diez bloques y hace clic en la condición IF del primer bloque, el bloque agregado se agregará después del bloque, en el mismo nivel. Si no hay ningún campo activo, se agregará al final del escenario.*

> **Punta**
>
> En condiciones y acciones, es mejor favorecer comillas simples (&#39;) en lugar de dobles (&quot;).

> **Punta**
>
> Una Ctrl Shift Z o Ctrl Shift Y le permite'**Cancelar** o rehacer una modificación (adición de acción, bloqueo ...).

### Bloques

Laquí están los diferentes tipos de bloques disponibles. :

- **If / Then / O** : El permite realizar acciones condicionales (si esto, entonces eso).
- **Lacción** : El permite lanzar acciones simples sin ninguna condición..
- **Bucle** : Permite que las acciones se realicen repyidamente desde 1 hasta un número definido (o incluso el valor de un sensor, o un número aleatorio, yc.).
- **Dentro** : Permite iniciar una acción en X minuto (s) (0 es un valor posible). La peculiaridad es que las acciones se inician en segundo plano, por lo que no bloquean el resto del escenario.. Entonces es un bloque sin bloqueo.
- **La** : Permite decirle a Jeedom que inicie las acciones del bloque en un momento dado (en la forma hhmm). Este bloque no es bloqueante. Ex : 0030 para 00:30, o 0146 para 1h46 y 1050 para 10h50.
- **Código** : El permite escribir directamente en código PHP (requiere cierto conocimiento y puede ser arriesgado, pero le permite no tener restricciones).
- **Comentario** : El permite agregar comentarios a su escenario.

Cada bloque tiene sus opciones para manejarlos mejor :

- La la izquierda :
    - La flecha bidireccional le permite mover un bloque o una acción para reordenarlos en el escenario.
    - El ojo reduce un bloqueo (* colapso *) para reducir su impacto visual. Ctrl Haga clic en el ojo para reducirlos o mostrarlos todos.
    - La casilla de verificación le permite desactivar complyamente el bloque sin eliminarlo. Por lo tanto, no se ejecutará.

- Sobre la derecha :
    - El icono Copiar le permite copiar el bloque para hacer una copia en otro lugar. Ctrl clic en el icono corta el bloque (copia y eliminación).
    - El icono Pegar le permite pegar una copia del bloque copiado previamente después del bloque en el que utiliza esta función..  Ctrl Click en el icono reemplaza el bloque con el bloque copiado.
    - El icono: le permite eliminar el bloque con una solicitud de confirmación.. Ctrl Click elimina el bloque sin confirmación.

#### Si / Entonces / De lo contrario bloquea | Bucle | Dentro | La

Para las condiciones, Jeedom trata de hacer posible escribirlas tanto como sea posible en lenguaje natural sin dejar de ser flexible..
> NO use [] en pruebas de condición, solo son posibles paréntesis ().

Hay tres botones disponibles a la derecha de este tipo de bloque para seleccionar un elemento para probar :

- **Encuentra un pedido** : El permite buscar un pedido en todos los disponibles en Jeedom. Unaa vez que se encuentra el pedido, Jeedom abre una ventana para preguntarle qué prueba desea realizar.. Si eliges **No poner nada**, Jeedom agregará el pedido sin comparación. También puedes elegir **y** o **o** delante **Entonces** para encadenar pruebas en diferentes equipos.
- **Buscar un escenario** : El permite buscar un escenario para probar.
- **Busca equipo** : Lo mismo para el equipo..

> **Nota**
>
> En bloques de tipo Si / Entonces / De lo contrario, las flechas circulares a la izquierda del campo de condición permiten activar o no la repyición de acciones si la evaluación de la condición da el mismo resultado que durante la evaluación previa.

> **Punta**
>
> Hay una lista de yiquyas que permiten el acceso a las variables desde el escenario u otro, o por la hora, la fecha, un número aleatorio, ... Vea a continuación los capítulos sobre comandos y yiquyas.

Unaa vez que se complya la condición, debe usar el botón &quot;Lagregar&quot; a la izquierda para agregar un nuevo **bloque** o un **acción** en el bloque actual.


#### Código de bloque

El bloque de código le permite ejecutar código php. Por lo tanto, es muy potente pero requiere un buen conocimiento del lenguaje php..

##### Lacceso a controles (sensores y actuadores):
-  `cmd::byString($string);` : Devuelve el objyo de comando correspondiente.
    -   `$string`: Enlace al pedido deseado : `#[objy][equipement][commande]#` (ex : `#[Lappartement][Lalarme][Bienes]#`)
-  `cmd::byId($id);` : Devuelve el objyo de comando correspondiente.
    -  `$id` : Identificación de pedido.
-  `$cmd->execCmd($options = null);` : Ejecute el comando y devuelva el resultado..
    - `$options` : Opciones para la ejecución de la orden (puede ser un complemento específico). Opciones básicas (subtipo de comando) :
        -  mensaje : `$option = array('title' => 'titre du mensaje , 'mensaje' => 'Mon mensaje');`
        -  color : `$option = array('color' => 'coleur en hexadécimal');`
        -  deslizador : `$option = array('deslizador' => 'valeur volue de 0 à 100');`

##### Lacceso al registro :
-  `log::add('apellidobre de archivo','nivel','mensaje');`
    - apellidobre de archivo : Nombre del archivo de registro.
    - nivel : ].
    - mensaje : Mensaje para escribir en los registros.

##### Lacceso al escenario :
- `$scenario->gyName();` : Devuelve el apellidobre del escenario actual.
- `$scenario->gyGrop();` : Devuelve el grupo de escenarios..
- `$scenario->gyIsLactive();` : Devuelve el estado del escenario..
- `$scenario->syIsLactive($active);` : El permite activar o no el escenario.
    - `$active` : 1 activo, 0 no activo.
- `$scenario->syOnGoing($onGoing);` : Digamos si el escenario se está ejecutando o no.
    - `$onGoing => 1` : 1 en progreso, 0 dyenido.
- `$scenario->save();` : Guardar cambios.
- `$scenario->syData($key, $value);` : Guardar un dato (Variable).
    - `$key` : clave de valor (int o cadena).
    - `$value` : valor a almacenar (int, cadena, matriz u objyo).
- `$scenario->gyData($key);` : Obtener datos (Variable).
    - `$key => 1` : clave de valor (int o cadena).
- `$scenario->removeData($key);` : Eliminar datos.
- `$scenario->syRegistro($mensaje);` : Escribir un mensaje en el registro del escenario.
- `$scenario->persistRegistro();` : Forzar la escritura del registro (de lo contrario, se escribe solo al final del escenario). Tenga cuidado, esto puede ryrasar un poco el escenario.

> **Punta**
>
> Ladición de una función de búsqueda en el bloque de Código : Buscar : Ctrl + F luego Enter, Siguiente resultado : Ctrl + G, resultado anterior : Ctrl + Shift + G

#### Bloque de comentarios

El bloque de comentarios actúa de manera diferente cuando está oculto. Sus botones a la izquierda desaparecen, así como el título del bloque, y reaparecen al pasar el cursor. Del mismo modo, la primera línea del comentario se muestra en negrita..
Esto permite que este bloque se use como una separación puramente visual dentro del escenario..

### Las acciones

Las acciones agregadas a los bloques tienen varias opciones :

- Unaa cabaña **activado** para que este comando se tenga en cuenta en el escenario.
- Unaa cabaña **paralelas** para que este comando se inicie en paralelo (al mismo tiempo) con los otros comandos también seleccionados.
- Unaa **flecha doble vertical** para mover la acción. Solo arrastra y suelta desde allí.
- Una botón para **Remove** la acción.
- Una botón para acciones específicas, con cada vez la descripción (al pasar el mose) de esta acción.
- Una botón para buscar un comando de acción.

> **Punta**
>
> Dependiendo del comando seleccionado, puede ver diferentes campos adicionales que se muestran.

## Posibles sustituciones

### Disparadores

Hay desencadenantes específicos (distintos de los proporcionados por los comandos) :

- #comienzo# : Lactivado al (re) inicio de Jeedom.
- #begin_backup# : evento enviado al inicio de una copia de seguridad.
- #end_backup# : evento enviado al final de una copia de seguridad.
- #BEGIN_UPDLaTE# : evento enviado al inicio de una actualización.
- #END_UPDLaTE# : evento enviado al final de una actualización.
- #begin_restore# : evento enviado al inicio de una restauración.
- #restauración_final# : evento enviado al final de una restauración.
- #user_connect# : Inicio de sesión de usuario

Vos povez aussi déclencher un scénario quand une variable est mise à jor en mytant : #variable(apellido_variable)# o en utilisant l'LaPI HTTP décrite [ici](https://jeedom.github.io/core/fr_FR/api_http).

### Operadores de comparación y enlaces entre condiciones

Puede usar cualquiera de los siguientes símbolos para comparar en condiciones :

- == : Igual a.
- \> : Estrictamente mayor que.
- \>= : Mayor o igual que.
- < : Estrictamente menor que.
- <= : Menor o igual que.
- != : Diferente de, no es igual a.
- cerillas : Contiene. Ex : `[Salle de bain][Hydromyrie][yat] cerillas "/humide/"`.
- no (... coincide ...) : No contiene. Ex :  `not([Salle de bain][Hydromyrie][yat] cerillas "/humide/")`.

Puedes combinar cualquier comparación con los siguientes operadores :

- &amp;&amp; / ET / y / LaND / y : y,
- \|| / OR / o / OR / o : o,
- \|^ / XOR / xor : o exclusivo.

### Etiquyas

Unaa yiquya se reemplaza durante la ejecución del escenario por su valor. Puedes usar las siguientes yiquyas :

> **Punta**
>
> Para mostrar los ceros a la izquierda, use la función Fecha (). Voir [ici](http://php.ny/manual/fr/function.fecha.php).

- #el segundo# : Segundo actual (sin ceros a la izquierda, ej. : 6 para 08:07:06).
- #hora# : Hora actual en formato de 24 h (sin ceros a la izquierda). Ex : 8 para 08:07:06 o 17 para 17:15.
- #hora12# : Hora actual en formato de 12 horas (sin ceros a la izquierda). Ex : 8 para 08:07:06.
- #minuto# : Minuto actual (sin ceros a la izquierda). Ex : 7 para 08:07:06.
- #dia# : Día actual (sin ceros a la izquierda). Ex : 6 para 06/07/2017.
- #mes# : Mes actual (sin ceros a la izquierda). Ex : 7 para 06/07/2017.
- #año# : Laño actual.
- #tiempo# : Hora y minuto actual. Ex : 1715 para las 5.15 p.m..
- #fecha y hora# : Número de segundos desde el 1 de enero de 1970.
- #fecha# : Día y mes. Latención, el primer número es el mes.. Ex : 1215 para el 15 de diciembre.
- #semana# : Número de semana.
- #domingo# : Nombre del día de la semana.. Ex : Sábado.
- #ndia# : Número de día de 0 (domingo) a 6 (sábado).
- #smes# : Nombre del mes. Ex : Enero.
- #IP# : IP interna de Jeedom.
- #apellidobre de host# : Nombre de la máquina Jeedom.
- #trigger # (en desuso, mejor usar trigger ()) : Quizás el apellidobre del comando que inició el escenario :
    - 'api &#39;si la LaPI activó el lanzamiento,
    - 'programar &#39;si se inició programando,
    - 'usuario &#39;si se inició manualmente,
    - 'comenzar &#39;para un lanzamiento cuando comience Jeedom.
- #trigger_value # (en desuso, mejor usar triggerValue ()) : Por el valor del comando que activó el escenario

También tiene las siguientes yiquyas adicionales si su escenario fue desencadenado por una interacción :

- #pregunta# : Interacción que desencadenó el escenario.
- #perfil# : Perfil del usuario que activó el escenario (puede estar vacío).

> **Importante**
>
> Cuando una interacción desencadena un escenario, se ejecuta necesariamente en modo rápido. Entonces, en el hilo de interacción y no en un hilo separado.

### Funciones de cálculo

Hay varias funciones disponibles para el equipo. :

- average(commande,période) y averageByween(commande,comienzo,end) : Donnent la moyenne de la commande sur la période (period=[mes,dia,hora,min] o [expression PHP](http://php.ny/manual/fr/fechatiempo.formats.relative.php)) o entre les 2 bornes demandées (sos la forme Y-m-d H:i:s o [expression PHP](http://php.ny/manual/fr/fechatiempo.formats.relative.php)).

- min(commande,période) y minByween(commande,comienzo,end) : Donnent le minimum de la commande sur la période (period=[mes,dia,hora,min] o [expression PHP](http://php.ny/manual/fr/fechatiempo.formats.relative.php)) o entre les 2 bornes demandées (sos la forme Y-m-d H:i:s o [expression PHP](http://php.ny/manual/fr/fechatiempo.formats.relative.php)).

- max(commande,période) y maxByween(commande,comienzo,end) : Donnent le maximum de la commande sur la période (period=[mes,dia,hora,min] o [expression PHP](http://php.ny/manual/fr/fechatiempo.formats.relative.php)) o entre les 2 bornes demandées (sos la forme Y-m-d H:i:s o [expression PHP](http://php.ny/manual/fr/fechatiempo.formats.relative.php)).

- duration(commande, valeur, période) y durationbyween(commande,valeur,comienzo,end) : Donnent la durée en minutos pendant laquelle l'équipement avait la valeur choisie sur la période (period=[mes,dia,hora,min] o [expression PHP](http://php.ny/manual/fr/fechatiempo.formats.relative.php)) o entre les 2 bornes demandées (sos la forme Y-m-d H:i:s o [expression PHP](http://php.ny/manual/fr/fechatiempo.formats.relative.php)).

- statistics(commande,calcul,période) y statisticsByween(commande,calcul,comienzo,end) : Donnent le résultat de différents calculs statistiques (sum, cont, std, variance, avg, min, max) sur la période (period=[mes,dia,hora,min] o [expression PHP](http://php.ny/manual/fr/fechatiempo.formats.relative.php)) o entre les 2 bornes demandées (sos la forme Y-m-d H:i:s o [expression PHP](http://php.ny/manual/fr/fechatiempo.formats.relative.php)).

- tendance(commande,période,seuil) : Donne la tendance de la commande sur la période (period=[mes,dia,hora,min] o [expression PHP](http://php.ny/manual/fr/fechatiempo.formats.relative.php)).

- stateDuration (control) : Da la duración en segundos desde el último cambio de valor..
    -1 : No existe historial o el valor no existe en el historial.
    -2 : El pedido no está registrado.

- lastChangeStateDuration (valor de comandos) : Da la duración en segundos desde el último cambio de estado al valor pasado en el parámyro.
    -1 : No existe historial o el valor no existe en el historial.
    -2 El pedido no está registrado

- lastStateDuration (valor de comandos) : Da la duración en segundos durante los cuales el equipo ha tenido el último valor elegido.
    -1 : No existe historial o el valor no existe en el historial.
    -2 : El pedido no está registrado.

- edad (control) : Da la edad en segundos del valor del comando (collecDate)
    -1 : El comando no existe o no es de tipo información.

- stateChanges(commande,[valeur], période) y stateChangesByween(commande, [valeur], comienzo, end) : Donnent le apellidobre de changements d'état (vers une certaine valeur si indiquée, o au total sinon) sur la période (period=[mes,dia,hora,min] o [expression PHP](http://php.ny/manual/fr/fechatiempo.formats.relative.php)) o entre les 2 bornes demandées (sos la forme Y-m-d H:i:s o [expression PHP](http://php.ny/manual/fr/fechatiempo.formats.relative.php)).

- lastByween(commande,comienzo,end) : Donne la dernière valeur enregistrée por l'équipement entre les 2 bornes demandées (sos la forme Y-m-d H:i:s o [expression PHP](http://php.ny/manual/fr/fechatiempo.formats.relative.php)).

- variable (variable, predyerminada) : Recupera el valor de una variable o el valor deseado por defecto.

- escenario (escenario) : Devuelve el estado del escenario..
    1 : En curso,
    0 : Dyenido,
    -1 : Desactivado,
    -2 : El escenario no existe.,
    -3 : El estado no es consistente.
    Para tener el apellidobre &quot;humano&quot; del escenario, puede usar el botón dedicado a la derecha de la búsqueda del escenario.

- lastScenarioExecution (escenario) : Da la duración en segundos desde el último lanzamiento del escenario..
    0 : El escenario no existe.

- collectDate(cmd,[format]) : Renvoie la fecha de la dernière donnée por la commande donnée en paramètre, le 2ème paramètre optionnel permy de spécifier le format de ryor (détails [ici](http://php.ny/manual/fr/function.fecha.php)).
    -1 : No se pudo encontrar el comando.,
    -2 : El comando no es de tipo info.

- valueDate(cmd,[format]) : Renvoie la fecha de la dernière donnée por la commande donnée en paramètre, le 2ème paramètre optionnel permy de spécifier le format de ryor (détails [ici](http://php.ny/manual/fr/function.fecha.php)).
    -1 : No se pudo encontrar el comando.,
    -2 : El comando no es de tipo info.

- eqEnable (equipo) : Devuelve el estado del equipo..
    -2 : No se puede encontrar el equipo.,
    1 : El equipo esta activo,
    0 : El equipo esta inactivo.

- valor (cmd) : Devuelve el valor de un pedido si Jeedom no lo proporciona automáticamente (caso al almacenar el apellidobre del pedido en una variable)

- yiquya (de lunes [por defecto]) : Se usa para recuperar el valor de una yiquya o el valor predyerminado si no existe.

- apellidobre (tipo, control) : Se utiliza para recuperar el apellidobre del pedido, equipo u objyo.. Puntao : cmd, eqRegistroic u objyo.

- lastCommunication(equipment,[format]) : Renvoie la fecha de la dernière communication por l'équipement donnée en paramètre, le 2ème paramètre optionnel permy de spécifier le format de ryor (détails [ici](http://php.ny/manual/fr/function.fecha.php)). Una ryorno de -1 significa que no se puede encontrar el equipo.

- color_gradient (coleur_debut, coleur_fin, valuer_min, valeur_max, valor) : Devuelve un color calculado con respecto al valor en el rango color_comienzo / color_end. El valor debe estar entre min_value y max_value.

La périodes y intervalles de ces fonctions peuvent également s'utiliser avec [des expressions PHP](http://php.ny/manual/fr/fechatiempo.formats.relative.php) como por ejemplo :

- Lahora : Lahora.
- Hoy : 00:00 hoy (permite, por ejemplo, obtener resultados para el día si está entre &#39;Hoy&#39; y &#39;Lahora&#39;).
- Lunes pasado : el lunes pasado a las 00:00.
- Hace 5 dias : Hace 5 dias.
- Layer mediodia : ayer mediodia.
- Etcétera.

Laquí hay ejemplos prácticos para comprender los valores devueltos por estas diferentes funciones. :

| Zócalo con valores :           | 000 (por 10 minutos) 11 (por 1 hora) 000 (por 10 minutos)    |
|--------------------------------------|--------------------------------------|
| promedio (tomando, periodo)             | Devuelve el promedio de 0 y 1 (puede  |
|                                      | ser influenciado por las encuestas)      |
| promedio Entre (\# [Baño] [Hidromyría] [Humedad] \#, 2015-01-01 00:00:00,2015-01-15 00:00:00) | Devuelve el pedido promedio entre el 1 de enero de 2015 y el 15 de enero de 2015                       |
| min (salida, periodo)                 | Devuelve 0 : el tapón se apagó durante el período              |
| minByween (\# [Baño] [Hidromyría] [Humedad] \#, 2015-01-01 00:00:00,2015-01-15 00:00:00) | Devuelve el pedido mínimo entre el 1 de enero de 2015 y el 15 de enero de 2015                       |
| max (decisión, período)                 | Devuelve 1 : el enchufe estaba bien iluminado en el período              |
| maxByween (\# [Baño] [Hidromyría] [Humedad] \#, 2015-01-01 00:00:00,2015-01-15 00:00:00) | Devuelve el máximo del pedido entre el 1 de enero de 2015 y el 15 de enero de 2015                       |
| duración (enchufe, 1 período)          | Devuelve 60 : el enchufe estuvo encendido (a 1) durante 60 minutos en el período                              |
| duración Entre (\# [Salón] [Tomar] [Estado] \#, 0, Último lunes, ahora)   | Devuelve la duración en minutos durante la cual el socky estuvo apagado desde el lunes pasado.                |
| estadísticas (captura, recuento, y punto)    | Devuelve 8 : hubo 8 escaladas en el período               |
| tendencia (enchufe, período 0.1)        | Devuelve -1 : tendencia a la baja    |
| stateDuration (enchufe)               | Devuelve 600 : el enchufe ha estado en su estado actual durante 600 segundos (10 minutos)                             |
| lastChangeStateDuration (captura, 0)   | Devuelve 600 : el zócalo se apagó (cambie a 0) por última vez hace 600 segundos (10 minutos)     |
| lastChangeStateDuration (captura, 1)   | Devuelve 4200 : el zócalo se encendió (cambie a 1) por última vez hace 4200 segundos (1h10)                               |
| lastStateDuration (captura, 0)         | Devuelve 600 : el enchufe ha estado apagado por 600 segundos (10 minutos)     |
| lastStateDuration (captura, 1)         | Devuelve 3600 : el zócalo se encendió por última vez durante 3600 segundos (1 h)           |
| stateChanges (captura, y punto)        | Devuelve 3 : el enchufe cambió de estado 3 veces durante el período            |
| stateChanges (captura, 0, periodo)      | Devuelve 2 : el zócalo se ha apagado (yendo a 0) dos veces durante el período                              |
| stateChanges (captura, 1 período)      | Devuelve 1 : el enchufe se enciende (cambie a 1) una vez durante el período                              |
| lastByween (\# [Baño] [Hidromyría] [Humedad] \#, ayer, hoy) | Devuelve la última temperatura registrada ayer.                    |
| variable (plop, 10)                  | Devuelve el valor de la variable plop o 10 si está vacía o no existe                         |
| escenario (\# [Baño] [Luz] [Lauto] \#) | Devuelve 1 en progreso, 0 si se dyiene y -1 si está desactivado, -2 si el escenario no existe y -3 si el estado no es consistente                         |
| lastScenarioExecution (\# [Baño] [Luz] [Lauto] \#)   | Devuelve 300 si el escenario se inició por última vez hace 5 minutos                                  |
| collectDate (\# [Baño] [Hidromyría] [Humedad] \#)     | Devoluciones 2015-01-01 17:45:12          |
| valueDate (\# [Baño] [Hidromyría] [Humedad] \#) | Devoluciones 2015-01-01 17:50:12          |
| eqEnable (\# [n] [basílica] \#)       | Devuelve -2 si no se encuentra el equipo, 1 si el equipo está activo y 0 si está inactivo          |
| yiquya (de lunes toto)                   | Devuelve el valor de "montag" si existe, de lo contrario devuelve el valor "toto"                               |
| apellidobre (eqRegistroic, \# [Baño] [Hidromyría] [Humedad] \#)     | Hidromyría de devoluciones                  |


### Funciones matematicas

También se puede usar una caja de herramientas de funciones genéricas para realizar conversiones

o cálculos :

- `rand(1,10)` : Dar un número aleatorio del 1 al 10.
- `randText(texte1;texte2;texte…​..)` : El permite devolver uno de los textos al azar (separe los textos por a;). No hay límite en el número de textos..
- `randomColor(min,max)` : Da un color aleatorio entre 2 límites (0 =&gt; rojo, 50 =&gt; verde, 100 =&gt; azul).
- `trigger(commande)` : El permite descubrir el desencadenante del escenario o saber si es el comando pasado como parámyro el que desencadenó el escenario.
- `triggerValue(commande)` : Se usa para averiguar el valor del desencadenante del escenario.
- `rond(valeur,[decimal])` : Redondea arriba, número [decimal] de lugares decimales después del punto decimal.
- `odd(valeur)` : El permite saber si un número es impar o no.. Devuelve 1 si es impar 0 de lo contrario.
- `median(commande1,commande2…​.commandeN)` : Devuelve la mediana de los valores..
- `avg(commande1,commande2…​.commandeN) `: Devuelve el promedio de los valores..
- `tiempo_op(tiempo,value)` : El permite realizar operaciones a tiempo, con tiempo = tiempo (ej. : 1530) y valor = valor para sumar o restar en minutos.
- `tiempo_byween(tiempo,comienzo,end)` : Se usa para probar si un tiempo está entre dos valores con `tiempo = tiempo` (ex : 1530), `comienzo=temps`, `end=temps`. Los valores iniciales y finales pueden estar a caballo entre la medianoche.
- `tiempo_diff(fecha1,fecha2[,format, rond])` : Se usa para descubrir la diferencia entre dos fechas (las fechas deben estar en el formato LaLaLaLa / MM / DD HH:MM:SS). Por defecto, el método devuelve la diferencia en día (s). Puedes preguntarlo en segundos (s), minutos (m), horas (h). Ejemplo en segundos `tiempo_diff (2019-02-02 14:55:00.2019-02-25 14:55:00,s)`. La diferencia se devuelve en absoluto, a menos que especifique `f` (sf, mf, hf, df). Vos povez aussi utiliser `dhms` qui ryornera pas exemple `7j 2h 5min 46s`. El parámyro redondo, opcional, redondeado a x dígitos después del punto decimal (2 por defecto). Ex: `tiempo_diff(2020-02-21 20:55:28,2020-02-28 23:01:14,df, 4)`.
- `formatTime(tiempo)` : Permy de formater le ryor d'une chaine `#tiempo#`.
- `floor(tiempo/60)` : Convierte segundos a minutos o minutos a horas (piso (tiempo / 3600) por segundos a horas).
- `convertDuration(el segundos)` : Convierte segundos a d / h / min / s.

Y ejemplos prácticos :


| Ejemplo de funcion                  | Resultado devuelto                    |
|--------------------------------------|--------------------------------------|
| randText (es # [sala de estar] [ojo] [temperatura] #; La temperatura es # [sala de estar] [ojo] [temperatura] #; Lactualmente tenemos # [sala de estar] [ojo] [temperatura] #) | la función devolverá uno de estos textos al azar en cada ejecución.                           |
| randomColor (40,60)                 | Devuelve un color aleatorio cercano al verde.
| gatillo (# [Baño] [Hidromyría] [Humedad] #)   | 1 si es bueno \# \ [Baño \] \ [Hidromyría \] \ [Humedad \] \# que inició el escenario de lo contrario 0  |
| triggerValue (# [Baño] [Hidromyría] [Humedad] #) | 80 si la hidromyría de \# \ [Baño \] \ [Hidromyría \] \ [Humedad \] \# es 80%.                         |
| redondo (# [Baño] [Hidromyría] [Humedad] # / 10) | Devuelve 9 si el porcentaje de humedad y 85                     |
| impar (3)                             | Devuelve 1                            |
| mediana (15,25,20)                   | Devuelve 20
| avg (10,15,18)                      | Devuelve 14.3                     |
| tiempo_op (# tiempo #, -90)               | si son las 4:50 p.m., regrese : 1 650-1 130 = 1520                          |
| formatTime (1650)                   | Devuelve 4:50 p.m.                        |
| piso (130/60)                      | Devuelve 2 (minutos si 130 s, u horas si 130 m)                      |
| convertDuration (3600)              | Devuelve 1h 0min 0s                      |
| convertDuration (duración (# [Calefacción] [Módulo de caldera] [Estado] #, 1, primer día de este mes) * 60) | Devuelve el tiempo de encendido en días / horas / minutos del tiempo de transición al estado 1 del módulo desde el primer día del mes |


### Pedidos específicos

Lademás de los comandos de automatización del hogar, tiene acceso a las siguientes acciones :

- **Pausa** (Sueño) : Pausa de x segundo (s).
- **variable** (Variable) : Creación / modificación de una variable o el valor de una variable.
- **Eliminar variable** (Delye_variable) : El permite eliminar una variable..
- **Guión** (Escenario) : Te permite controlar escenarios. La parte de yiquyas le permite enviar yiquyas al escenario, ej. : montag = 2 (ten cuidado, solo usa lyras de la a a la z. Sin lyras mayúsculas, sin acentos y sin caracteres especiales). Recuperamos la yiquya en el escenario objyivo con la función de yiquya (montag). El comando &quot;Restablecer a SI&quot; permite restablecer el estado de &quot;SI&quot; (este estado se utiliza para la no repyición de las acciones de un &quot;SI&quot; si pasa por segunda vez consecutiva en él)
- **Dyener** (Dyener) : Dyener el escenario.
- **Esperar** (Espere) : Espere hasta que la condición sea válida (máximo 2 h), el tiempo de espera es en segundos.
- **Ir al diseño** (Gotodesign) : Cambie el diseño que se muestra en todos los navegadores por el diseño solicitado.
- **Lagregar un registro** (Registro) : El permite agregar un mensaje a los registros.
- **Crear mensaje** (Mensaje) : Lagregar un mensaje al centro de mensajes.
- **Lactivar / Desactivar Ocultar / mostrar equipo** (Equipo) : El permite modificar las propiedades de los equipos visibles / invisibles, activos / inactivos..
- **Hacer una solicitud** (Lask) : Permite indicar a Jeedom que es necesario hacerle una pregunta al usuario. La respuesta se almacena en una variable, entonces solo tiene que probar su valor.
    Por el momento, solo los complementos sms, slack, telegram y snips son compatibles, así como la aplicación móvil.
    Latención, esta función está bloqueando. Mientras no haya respuesta o no se alcance el tiempo de espera, el escenario espera.
- **Dyener Jeedom** (Jeedom_poweroff) : Pídale a Jeedom que cierre.
- **Devolver un texto / datos** (Scenario_ryurn) : Devuelve un texto o un valor para una interacción por ejemplo.
- **Icono** (Icono) : Permite cambiar el ícono de representación del escenario.
- **Ladvertencia** (Lalerta) : Muestra un pequeño mensaje de alerta en todos los navegadores que tienen abierta una página de Jeedom. Lademás, puedes elegir 4 niveles de alerta.
- **Pop-up** (Emergente) : Permite mostrar una ventana emergente que debe validarse absolutamente en todos los navegadores que tienen una página abierta.
- **Relación** (Informe) : El permite exportar una vista en formato (PDF, PNG, JPEG o SVG) y enviarla utilizando un comando de tipo mensaje. Tenga en cuenta que si su acceso a Interny está en HTTPS sin firmar, esta funcionalidad no funcionará. Se requiere HTTP o HTTPS firmado.
- **Eliminar bloque IN / La programado** (Remove_inat) : Eliminar la programación de todos los bloques y un escenario de.
- **Evento** (Event) : El permite insertar un valor en un comando de tipo de información arbitrariamente.
- **Etiquya** (Etiquya) : El permite agregar / modificar una yiquya (la yiquya solo existe durante la ejecución actual del escenario a diferencia de las variables que sobreviven al final del escenario).
- **Coloración de los iconos del tablero** (SyColoredIcon) : le permite activar o no el color de los íconos en el tablero.

### Plantilla de escenario

Esta funcionalidad le permite transformar un escenario en una plantilla para, por ejemplo, aplicarlo a otro Jeedom.

Haciendo clic en el botón **Plantilla** en la parte superior de la página, abre la ventana de administración de plantillas.

La partir de ahí, tienes la posibilidad :

- Enviar una plantilla a Jeedom (archivo JSON recuperado previamente).
- Consulte la lista de escenarios disponibles en el mercado.
- Cree una plantilla a partir del escenario actual (no olvide dar un apellidobre).
- Para consultar las plantillas actualmente presentes en su Jeedom.

Lal hacer clic en una plantilla, puede :

- **Compartir, repartir** : Compartir la plantilla en el mercado.
- **Remove** : Eliminar plantilla.
- **Descargar** : Obtenga la plantilla como un archivo JSON para enviarla a otro Jeedom, por ejemplo.

La continuación, tiene la parte para aplicar su plantilla al escenario actual.

Dado que de un Jeedom a otro o de una instalación a otra, los comandos pueden ser diferentes, Jeedom le solicita la correspondencia de los comandos entre los presentes durante la creación de la plantilla y los presentes en el hogar. Solo tiene que complyar la correspondencia de las órdenes y luego aplicar.

### Ladición de la función php

> **Importante**
>
> Lagregar funciones PHP está reservado para usuarios avanzados. El más mínimo error puede ser fatal para su Jeedom.

#### Configurar

Vaya a la configuración de Jeedom, luego OS / DB e inicie el editor de archivos.

Vaya a la carpya de datos, luego php y haga clic en el archivo user.function.class.php.

Es en esta * clase * donde puede agregar sus funciones, encontrará un ejemplo de una función básica.

> **Importante**
>
> Si tiene alguna inquiyud, siempre puede volver al archivo original copiando el contenido de user.function.class.sample.php en user.function.class.php
