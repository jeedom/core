Aquí está la parte más importante de la automatización del hogar : los escenarios.
Verdadero cerebro de la domótica, es lo que hace posible interactuar con
el mundo real de una "manera inteligente".

La página de gestión de escenarios
================================

Gestion
-------

Para acceder, nada más simple, solo ve a Herramientas ->
Escenarios. Encontrará allí la lista de escenarios para su Jeedom también
solo funciones para gestionarlos mejor :

-   **Añadir** : Crea un escenario. Se describe el procedimiento
    en el siguiente capitulo.

-   **Deshabilitar escenarios** : Deshabilita todos los escenarios.

-   **Ver variables** : Veamos las variables, su valor también
    que el lugar donde se usan. Tambien puedes
    crear un. Las variables se describen en un capítulo de
    esta página.

-   **Resumen** : Le permite tener una visión general de todos
    los escenarios. Puedes cambiar los valores **bienes**,
    **visible**, **lanzamiento múltiple**, **modo síncrono**, **Registro** et
    **Línea de tiempo** (estos parámetros se describen en el siguiente capítulo).
    También puede acceder a los registros para cada escenario y
    comenzar individualmente.

-   **Probador de expresión** : Le permite ejecutar una prueba en un
    expresión de su elección y mostrar el resultado.

Mis escenarios
-------------

En esta sección encontrarás el **lista de escenarios** que usted
he creado. Se clasifican de acuerdo a **grupos** que tienes
definido para cada uno de ellos. Cada escenario se muestra con su **apellido**
y su **objeto padre**. La **escenarios atenuados** son los que son
discapacitado.

Como en muchas páginas de Jeedom, coloque el mouse a la izquierda de
la pantalla muestra un menú de acceso rápido (desde
tu perfil, siempre puedes dejarlo visible). Usted puede
entonces **buscar** su escenario, pero también en **Añadir** uno por esto
menu.

Editar un escenario
=====================

Después de hacer clic en **Añadir**, debes elegir el nombre de tu
escenario y se le redirige a su página de configuración general.
En la parte superior, hay algunas funciones útiles para administrar nuestro escenario
:

-   **Identificación** : Al lado de la palabra **General**, este es el identificador de escenario.

-   **estatus** : Estado actual de su escenario.

-   **las variables** : Ver variables.

-   **Expresión** : Muestra el probador de expresiones.

-   **Realizar** : Le permite iniciar el escenario manualmente (recuerde
    sin guardar de antemano !). Los desencadenantes por lo tanto no son
    no tomado en cuenta.

-   **Remove** : Eliminar escenario.

-   **Guardar** : Guardar los cambios realizados.

-   **Plantilla** : Le permite acceder y aplicar plantillas
    al guión del mercado. (explicado al final de la página).

-   **Exportación** : Obtenga una versión de texto del guión.

-   **Registro** : Muestra los registros del escenario.

-   **Duplicado** : Copie el escenario para crear uno
    nuevo con otro nombre.

-   **Vínculos** : Le permite ver el gráfico de los elementos vinculados
    con el guión.

Pestaña General
--------------

En la pestaña **General**, encontramos los principales parámetros de
nuestro escenario :

-   **Nombre del escenario** : El nombre de tu escenario.

-   **Nombre para mostrar** : El nombre utilizado para su visualización.

-   **Grupo** : Le permite organizar los escenarios, clasificándolos en
    grupos.

-   **Bienes** : Activa el escenario.

-   **Visible** : Se usa para hacer visible el escenario.

-   **Objeto padre** : Asignación a un objeto padre.

-   **Segundos de tiempo de espera (0 = ilimitado)** : El tiempo máximo de ejecución
    autorizado

-   **Lanzamiento múltiple** : Marque esta casilla si desea
    el escenario se puede iniciar varias veces al mismo tiempo.
>**Importante**
>
>El lanzamiento múltiple funciona por segundo, es decir que si tiene 2 lanzamientos en el mismo segundo sin marcar la casilla, todavía habrá 2 lanzamientos del escenario (cuando no debería). Del mismo modo, durante varios lanzamientos en el mismo segundo, es posible que ciertos lanzamientos pierdan las etiquetas. Conclusión DEBE ABSOLUTAMENTE evitar múltiples lanzamientos en los mismos segundos.
-   **Modo síncrono** : Inicie el escenario en el hilo actual en lugar de un hilo dedicado. Aumenta la velocidad de lanzamiento del escenario pero puede hacer que el sistema sea inestable.

-   **Registro** : El tipo de registro deseado para el escenario.

-   **Sigue en la línea de tiempo** : Realiza un seguimiento del escenario
    en la linea de tiempo.

-   **Descripción** : Le permite escribir un pequeño texto para describir
    tu escenario.

-   **Modo de escenario** : El escenario puede ser programado, activado o
    ambos al mismo tiempo. Luego tendrá la opción de indicar el (s))
    disparador (es) (tenga cuidado, hay un límite para el número de disparadores posibles por escenario de 15) y la programación (s)).

> **Punta**
>
> Atención : puedes tener un máximo de 28
> disparadores / programación para un escenario.

Pestaña Escenario
---------------

Aquí es donde construirás tu escenario. Tenemos que empezar
por **agregar un bloque**, con el botón de la derecha. Una vez un bloque
creado, puedes agregar otro **bloque** o un **acción**.

> **Punta**
>
> En condiciones y acciones, es mejor favorecer comillas simples (') en lugar de dobles (")

### Bloques

Aquí están los diferentes tipos de bloques disponibles :

-   **If / Then / O** : Le permite realizar acciones
    bajo condiciones).

-   **Acción** : Le permite lanzar acciones simples sin
    sin condiciones.

-   **Bucle** : Le permite realizar acciones repetidamente
    1 hasta un número definido (o incluso el valor de un sensor, o un
    número aleatorio…).

-   **Dentro** : Inicia una acción en X minuto (s) (0 es un
    valor posible). La peculiaridad es que las acciones se lanzan
    en segundo plano, para que no bloqueen el resto del escenario.
    Entonces es un bloque sin bloqueo.

-   **La** : Permite decirle a Jeedom que inicie las acciones del bloque en un
    tiempo dado (en la forma hhmm). Este bloque no es bloqueante. Ex :
    0030 para 00:30, o 0146 para 1h46 y 1050 para 10h50.

-   **Código** : Le permite escribir directamente en código PHP (solicitud
    cierto conocimiento y puede ser arriesgado pero permite no tener
    sin restricciones).

-   **Comentario** : Le permite agregar comentarios a su escenario.

Cada uno de estos bloques tiene sus opciones para manejarlos mejor :

-   La casilla de verificación a la izquierda le permite deshabilitar completamente
    bloquear sin eliminarlo.

-   La flecha doble vertical a la izquierda le permite mover todo
    bloque por arrastrar y soltar.

-   El botón, en el extremo derecho, le permite eliminar todo el bloque.

#### Si / Entonces / De lo contrario bloquea, Bucle, In y A

> **Nota**
>
> En bloques Si / Entonces / De lo contrario, flechas circulares ubicadas
> a la izquierda del campo de condición permite activar o no el
> repetición de acciones si la evaluación de la condición da el mismo
> resultado que la evaluación anterior.

Para las condiciones, Jeedom trata de asegurarse de que podamos
escribir tanto como sea posible en lenguaje natural sin dejar de ser flexible. Tres
los botones están disponibles a la derecha de este tipo de bloque para
seleccione un elemento para probar :

-   **Encuentra un pedido** : Le permite buscar un pedido en
    todos los disponibles en Jeedom. Una vez que se encuentra el pedido,
    Jeedom abre una ventana para preguntarte qué prueba quieres
    actuar en ello. Si eliges **No poner nada**,
    Jeedom agregará el pedido sin comparación. Usted puede también
    elegir **y** o **o** delante **Entonces** para encadenar pruebas
    en diferentes equipos.

-   **Buscar un escenario** : Permite buscar un escenario
    para probar.

-   **Busca equipo** : Lo mismo para el equipo.

> **Punta**
>
> Hay una lista de etiquetas que permiten el acceso a las variables
> del guión u otro, o por hora, fecha, un
> número aleatorio,. Vea más adelante los capítulos sobre comandos y
> etiquetas.

Una vez que se complete la condición, debe usar el botón
"agregar ", izquierda, para agregar un nuevo **bloque** o un
**acción** en el bloque actual.

> **Punta**
>
> NO DEBE utilizar [] en pruebas de condición, solo son posibles paréntesis ()

#### Código de bloque

> **Importante**
>
> Tenga en cuenta que las etiquetas no están disponibles en un bloque de código.

Controles (sensores y actuadores):
-   cmd::byString ($ cadena); : Devuelve el objeto de comando correspondiente.
  -   $string : Enlace al pedido deseado : #[objet][Dispositivos][commande]# (ex : #[Appartement][Alarme][Bienes]#)
-   cmd::BYID ($ id); : Devuelve el objeto de comando correspondiente.
  -   $id : ID de pedido
-   $cmd->execCmd($options = null); : Ejecute el comando y devuelva el resultado.
  -   $options : Opciones para la ejecución del comando (puede ser específico del complemento), opción básica (subtipo de comando) :
    -   mensaje : $option = array('title' => 'titre du mensaje , 'message' => 'Mon message');
    -   color : $option = array('color' => 'couleur en hexadécimal');
    -   deslizador : $option = array('slider' => 'valeur voulue de 0 à 100');

Registro :
-   log::add ( &#39;nombre&#39; &#39;nivel&#39;, &#39;mensaje&#39;);
  -   nombre de archivo : Nombre del archivo de registro.
  -   nivel : [depuración], [información], [error], [evento].
  -   mensaje : Mensaje para escribir en los registros.

Guión :
-   $scenario->getName(); : Devuelve el nombre del escenario actual.
-   $scenario->getGroup(); : Devuelve el grupo de escenarios.
-   $scenario->getIsActive(); : Devuelve el estado del escenario.
-   $scenario->setIsActive($active); : Le permite activar o no el escenario.
  -   $active : 1 activo, 0 no activo.
-   $scenario->setOnGoing($onGoing); : Digamos si el escenario se está ejecutando o no.
  -   $onGoing => 1 en cours , 0 arrêté.
-   $scenario->save(); : Guardar cambios.
-   $scenario->setData($key, $value); : Guardar un dato (variable).
  -   $key : clave de valor (int o string).
  -   $value : valor para almacenar (int, string, array u object).
-   $scenario->getData($key); : Obtener datos (variable).
  -   $key => clave de valor (int o string).
-   $scenario->removeData($key); : Eliminar datos.
-   $scenario->setLog($message); : Escribir un mensaje en el registro del escenario.
-   $scenario->persistLog(); : Forzar la escritura del registro (de lo contrario, se escribe solo al final del escenario). Tenga cuidado, esto puede retrasar un poco el escenario.

### Las acciones

Las acciones agregadas a los bloques tienen varias opciones. En el orden :

-   Una cabaña **paralelas** para que este comando se inicie en paralelo
    otros comandos también seleccionados.

-   Una cabaña **activado** para que este comando se tenga en cuenta
    cuenta en el escenario.

-   Una **flecha doble vertical** para mover la acción. Basta con
    arrastrar y soltar desde allí.

-   Un botón para eliminar la acción.

-   Un botón para acciones específicas, cada vez con el
    descripción de esta acción.

-   Un botón para buscar un comando de acción.

> **Punta**
>
> Dependiendo del comando seleccionado, podemos ver diferentes
> campos adicionales mostrados.

Posibles sustituciones
===========================

Disparadores
----------------

Hay desencadenantes específicos (distintos de los proporcionados por
commandes) :

-   #start# : activado al (re) inicio de Jeedom,

-   #begin_backup# : evento enviado al inicio de una copia de seguridad.

-   #end_backup# : evento enviado al final de una copia de seguridad.

-   #begin_update# : evento enviado al inicio de una actualización.

-   #end_update# : evento enviado al final de una actualización.

-   #begin_restore# : evento enviado al inicio de una restauración.

-   #end_restore# : evento enviado al final de una restauración.

-   #user_connect# : inicio de sesión de usuario

También puede desencadenar un escenario cuando una variable se establece en
día poniendo : #variable(nom_variable)# o usando la API HTTP
descrito
[aquí](https://jeedom.github.io/core/es_ES/api_http).

Operadores de comparación y enlaces entre condiciones
-------------------------------------------------------

Puede usar cualquiera de los siguientes símbolos para
comparaciones en condiciones :

-   == : igual a,

-   \> : estrictamente mayor que,

-   \>= : mayor o igual que,

-   < : estrictamente menor que,

-   <= : menor o igual que,

-   != : diferente de, no es igual a,

-   cerillas : contiene (ex :
    [Baño] [Hidrometría] [condición] coincide "/ mojado /" ),

-   no (... coincide) : no contiene (ex :
    no ([Baño] [Hidrometría] [condición] coincide "/ mojado /")),

Puedes combinar cualquier comparación con operadores
siguiente :

-   &amp;&amp; / ET / y / AND / y : et,

-   \|| / OR / o / OR / o : ou,

-   \|^ / XOR / xor : o exclusivo.

Etiquetas
--------

Una etiqueta se reemplaza durante la ejecución del escenario por su valor. Vosotras
puede usar las siguientes etiquetas :

> **Punta**
>
> Para mostrar los ceros a la izquierda, use el
> Función de fecha (). Ver
> [aquí](http://php.net/manual/fr/function.date.php).

-   #seconde# : Segundo actual (sin ceros a la izquierda, ej : 6 para
    08:07:06),

-   #heure# : Hora actual en formato de 24 h (sin ceros a la izquierda),
    ex : 8 para 08:07:06 o 17 para 17:15),

-   #heure12# : Hora actual en formato de 12 horas (sin ceros a la izquierda),
    ex : 8 para 08:07:06),

-   #minute# : Minuto actual (sin ceros a la izquierda, ej : 7 para
    08:07:06),

-   #jour# : Día actual (sin ceros a la izquierda, ej : 6 para
    07/06/2017),

-   #mois# : Mes actual (sin ceros a la izquierda, ej : 7 para
    07/06/2017),

-   #annee# : Año actual,

-   #time# : Hora y minuto actual (ex : 1715 para las 5.15 p.m),

-   #timestamp# : Número de segundos desde el 1 de enero de 1970,

-   #date# : Día y mes. Atención, el primer número es el mes.
    (ex : 1215 para el 15 de diciembre),

-   #semaine# : Número de semana (ex : 51),

-   #sjour# : Nombre del día de la semana (ex : Samedi),

-   #njour# : Número de día de 0 (domingo) a 6 (sábado)),

-   #smois# : Nombre del mes (ex : Janvier),

-   #IP# : IP interna de Jeedom,

-   #hostname# : Nombre de la máquina Jeedom,

-   #trigger# : Tal vez el nombre del comando que inició el escenario, &#39;api&#39; si la API inició el lanzamiento, &#39;horario&#39; si se inició mediante programación, &#39;usuario&#39; si se inició manualmente

También tiene las siguientes etiquetas adicionales si su script ha sido
desencadenado por una interacción :

-   #query# : interacción que desencadenó el escenario,

-   #profil# : perfil del usuario que inició el escenario
    (puede estar vacío).

> **Importante**
>
> Cuando un escenario se desencadena por una interacción, es
> necesariamente se ejecuta en modo rápido.

Funciones de cálculo
-----------------------

Hay varias funciones disponibles para el equipo :

-   promedio (orden, período) y promedio entre (orden, inicio, fin))
    : Proporcione el promedio del pedido durante el período
    (período = [mes, día, hora, min] o [expresión
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) ou
    entre las 2 terminales requeridas (en la forma Ymd H:i:s o
    [expresión
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) :

-   min (orden, período) y minBetween (orden, inicio, fin) :
    Dar el pedido mínimo durante el período
    (período = [mes, día, hora, min] o [expresión
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) ou
    entre las 2 terminales requeridas (en la forma Ymd H:i:s o
    [expresión
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) :

-   max (orden, período) y maxBetween (orden, inicio, fin) :
    Dar el máximo del pedido durante el período
    (período = [mes, día, hora, min] o [expresión
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) ou
    entre las 2 terminales requeridas (en la forma Ymd H:i:s o
    [expresión
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) :

-   duración (orden, valor, período) y
    duración entre (comando, valor, inicio, fin) : Dar la duración en
    minutos durante los cuales el equipo tenía el valor seleccionado en el
    período (período = [mes, día, hora, min] o [expresión
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) ou
    entre las 2 terminales requeridas (en la forma Ymd H:i:s o
    [expresión
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) :

-   estadísticas (orden, cálculo, período) y
    estadísticas entre (comando, cálculo, inicio, fin) : Dar el resultado
    diferentes cálculos estadísticos (suma, recuento, estándar),
    varianza, promedio, min, max) durante el período
    (período = [mes, día, hora, min] o [expresión
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) ou
    entre las 2 terminales requeridas (en la forma Ymd H:i:s o
    [expresión
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) :

-   tendencia (orden, período, umbral) : Da la tendencia de
    orden durante el período (período = [mes, día, hora, min] o
    [expresión
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) :

-   stateDuration (comando) : Da duración en segundos
    desde el último cambio de valor. Devuelve -1 si ninguno
    el historial no existe o si el valor no existe en el historial.
    Devuelve -2 si el pedido no está registrado.

-   lastChangeStateDuration (comando, valor) : Dar la duración en
    segundos desde el último cambio de estado al valor pasado
    como un parámetro. Devuelve -1 si ninguno
    el historial no existe o si el valor no existe en el historial.
    Devuelve -2 si el pedido no está registrado

-   lastStateDuration (comando, valor) : Da duración en segundos
    durante el cual el equipo ha tenido recientemente el valor elegido.
    Devuelve -1 si no existe un historial o si el valor no existe en el historial.
    Devuelve -2 si el pedido no está registrado

-   stateChanges (orden, [valor], punto) y
    stateChangesBetween (comando, [valor], inicio, fin) : Dar el
    cantidad de cambios de estado (a un cierto valor si se indica,
    o en total de lo contrario) durante el período (período = [mes, día, hora, min] o
    [expresión
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) ou
    entre las 2 terminales requeridas (en la forma Ymd H:i:s o
    [expresión
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) :

-   lastBetween (comando, inicio, fin) : Devuelve el último valor
    registrado para el equipo entre los 2 terminales requeridos (bajo el
    forma Ymd H:i:s o [expresión
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) :

-   variable (variable, valor predeterminado) : Obtenga el valor de un
    variable o el valor predeterminado deseado :

-   escenario (escenario) : Devuelve el estado del escenario. 1 en progreso, 0
    si se detiene y -1 si está deshabilitado, -2 si el escenario no existe y -3
    si el estado no es consistente. Para tener el nombre &quot;humano&quot; del escenario, puede usar el botón dedicado a la derecha de la búsqueda del escenario.

-   lastScenarioExecution (escenario) : Da duración en segundos
    desde el lanzamiento del último escenario :

-   collectDate (cmd, [formato]) : Devuelve la fecha de los últimos datos
    para el comando dado como parámetro, el segundo parámetro opcional
    permite especificar el formato de retorno (detalles
    [aquí](http://php.net/manual/fr/function.date.php)). Un retorno de -1
    significa que no se puede encontrar el pedido y -2 que el pedido no es
    sin tipo de información

-   valueDate (cmd, [formato]) : Devuelve la fecha de los últimos datos
    para el comando dado como parámetro, el segundo parámetro opcional
    permite especificar el formato de retorno (detalles
    [aquí](http://php.net/manual/fr/function.date.php)). Un retorno de -1
    significa que no se puede encontrar el pedido y -2 que el pedido no es
    sin tipo de información

-   eqEnable (equipo) : Devuelve el estado del equipo. -2 si
    no se puede encontrar el equipo, 1 si el equipo está activo y 0 si no lo está
    está inactivo

-   valor (cmd) : Devuelve el valor de un pedido si Jeedom no lo proporciona automáticamente (caso al almacenar el nombre del pedido en una variable)    

-   etiqueta (montag, [predeterminado]) : Se utiliza para recuperar el valor de una etiqueta o
    el valor predeterminado si no existe :

-   nombre (tipo, comando) : Se usa para recuperar el nombre del comando,
    equipo u objeto. El tipo vale cmd, eqLogic o
    objeto.

-   lastCommunication (equipo, [formato]) : Devuelve la fecha de la última comunicación
    para el equipo dado como parámetro, el segundo parámetro opcional
    permite especificar el formato de retorno (detalles
    [aquí](http://php.net/manual/fr/function.date.php)). Un retorno de -1
    significa que no se puede encontrar el equipo

-   color_gradient (start_colour, end_colour, min_value, max_value, value) : Devuelve un color calculado con respecto al valor en el rango color_start / color_end. El valor debe estar entre min_value y max_value

Los períodos e intervalos de estas funciones también pueden
utilizar con [expresiones
PHP](http://php.net/manual/fr/datetime.formats.relative.php) comme par
ejemplo :

-   Ahora : maintenant

-   Hoy : 00:00 hoy (permite por ejemplo obtener
    resultados del día si entre 'Hoy' y 'Ahora')

-   Lunes pasado : el lunes pasado a las 00:00

-   Hace 5 dias : Hace 5 dias

-   Ayer mediodia : ayer mediodia

-   Etcétera.

Aquí hay ejemplos prácticos para comprender los valores devueltos por
estas diferentes funciones :

| Zócalo con valores :           | 000 (por 10 minutos) 11 (por 1 hora) 000 (por 10 minutos)    |
|--------------------------------------|--------------------------------------|
| promedio (captura, período)             | Devuelve el promedio de 0 y 1 (puede  |
|                                      | ser influenciado por las encuestas)      |
| averageBetween(\#[Salle de bain][Hydrometrie][Humidité]\#,2015-01-01 00:00:00,2015-01-15 00:00:00) | Devuelve el pedido promedio entre el 1 de enero de 2015 y el 15 de enero de 2015                         |
| min (captura, período)                 | Devuelve 0 : el tapón se apagó durante el período              |
| minBetween(\#[Salle de bain][Hydrometrie][Humidité]\#,2015-01-01 00:00:00,2015-01-15 00:00:00) | Devuelve el pedido mínimo entre el 1 de enero de 2015 y el 15 de enero de 2015                         |
| max (captura, período)                 | Devuelve 1 : el enchufe estaba bien iluminado en el período              |
| maxBetween(\#[Salle de bain][Hydrometrie][Humidité]\#,2015-01-01 00:00:00,2015-01-15 00:00:00) | Devuelve el máximo del pedido entre el 1 de enero de 2015 y el 15 de enero de 2015                         |
| duración (tomado, 1, período)          | Devuelve 60 : el enchufe estuvo encendido (a 1) durante 60 minutos en el período                              |
| durationBetween(\#[Salon][Prise][Etat]\#,0, el lunes pasado, ahora)   | Devuelve la duración en minutos durante la cual el socket estuvo apagado desde el lunes pasado.                |
| estadísticas (captura, conteo, período)    | Devuelve 8 : hubo 8 escaladas en el período               |
| tendencia (enchufe, período 0.1)        | Devuelve -1 : tendencia a la baja    |
| stateDuration (tomado)               | Devuelve 600 : el enchufe ha estado en su estado actual durante 600 segundos (10 minutos)                             |
| lastChangeStateDuration (tomado, 0)   | Devuelve 600 : el zócalo se apagó (cambie a 0) por última vez hace 600 segundos (10 minutos)     |
| lastChangeStateDuration (toma, 1)   | Devuelve 4200 : el zócalo se encendió (cambie a 1) por última vez hace 4200 segundos (1h10)                               |
| lastStateDuration (tomado, 0)         | Devuelve 600 : el enchufe ha estado apagado por 600 segundos (10 minutos)     |
| lastStateDuration (tomado, 1)         | Devuelve 3600 : el zócalo se encendió por última vez durante 3600 segundos (1 hora)           |
| StateChanges (tomado, período)        | Devuelve 3 : el enchufe cambió de estado 3 veces durante el período            |
| stateChanges (take, 0, punto)      | Devuelve 2 : el zócalo se ha apagado (yendo a 0) dos veces durante el período                              |
| cambios de estado (toma, 1, período)      | Devuelve 1 : el enchufe se enciende (cambie a 1) una vez durante el período                              |
| lastBetween(\#[Salle de bain][Hydrometrie][Humidité]\#,Ayer hoy) | Devuelve la última temperatura registrada ayer.                    |
| variable (plop, 10)                  | Devuelve el valor de la variable plop o 10 si está vacía o no existe                         |
| scenario(\#[Salle de bain][Lumière][Auto]\#) | Devuelve 1 en progreso, 0 si se detiene y -1 si está desactivado, -2 si el escenario no existe y -3 si el estado no es consistente                         |
| lastScenarioExecution(\#[Salle de bain][Lumière][Auto]\#)   | Devuelve 300 si el escenario se inició por última vez hace 5 minutos                                  |
| collectDate(\#[Salle de bain][Hydrometrie][Humidité]\#)     | Devoluciones 2015-01-01 17:45:12          |
| valueDate(\#[Salle de bain][Hydrometrie][Humidité]\#) | Devoluciones 2015-01-01 17:50:12          |
| eqEnable(\#[Aucun][Basilique]\#)       | Devuelve -2 si no se encuentra el equipo, 1 si el equipo está activo y 0 si está inactivo          |
| etiqueta (montag, toto)                   | Devuelve el valor de "montag" si existe, de lo contrario devuelve el valor "toto"                               |
| nombre (eqLogic, \#[Salle de bain][Hydrometrie][Humidité]\#)     | Hidrometría de devoluciones                  |

Funciones matematicas
---------------------------

Una caja de herramientas de funciones genéricas también se puede utilizar para
realizar conversiones o cálculos :

-   rand(1,10) : Dar un número aleatorio del 1 al 10.

-   randText (texto1; texto2; texto..) : Devuelve uno de
    textos al azar (separe los textos por uno; ). No hay
    límite en el número de texto.

-   randomColor (min, max) : Da un color aleatorio entre 2
    terminales (0 => rojo, 50 => verde, 100 => azul).

-   disparador (comando) : Se usa para descubrir el desencadenante del escenario
    o para saber si es el pedido realizado como parámetro el que tiene
    desencadenó el escenario.

-   triggerValue (comando) : Se usa para averiguar el valor de
    desencadenante de escenario.

-   round (valor, [decimal]) : Redondo arriba, [decimal]
    número de decimales después del punto decimal.

-   impar (valor) : Le permite saber si un número es impar o no.
    Devuelve 1 si es impar 0 de lo contrario.

-   mediana (comando1, comando2.commandeN) : Devuelve la mediana
    valores.

-   time_op (tiempo, valor) : Le permite realizar operaciones a tiempo,
    con tiempo = tiempo (ex : 1530) y valor = valor para agregar o para
    restar en minutos.

-   `time_between (hora, inicio, fin)` : Permite probar si es un momento
    entre dos valores con `time = time` (ex : 1530), `inicio = tiempo`,` fin = tiempo`.
    Los valores iniciales y finales pueden estar a caballo entre la medianoche.

-   `time_diff (fecha1, fecha1 [, formato])` : Se usa para descubrir la diferencia entre 2 fechas (las fechas deben estar en el formato AAAA / MM / DD HH:MM:SS).
    Por defecto (si no pone nada para el formato), el método devuelve el número total de días. Puedes preguntarlo en segundos (s), minutos (m), horas (h). Ejemplo en segundos `time_diff (2018-02-02 14:55:00,2018-02-25 14:55:00,s)``

-   `formatTime (hora)` : Formatea el retorno de una cadena
    ``#time#``.

-   piso (tiempo / 60) : Convierte de segundos a minutos, o
    minutos a horas (piso (tiempo / 3600) por segundos
    En horas)

Y ejemplos prácticos :


| Ejemplo de funcion                  | Resultado devuelto                    |
|--------------------------------------|--------------------------------------|
| randText (lo hace #[salon][oeil][température]#; La temperatura es #[salon][oeil][température]#; Actualmente tenemos #[salon][oeil][température]#) | la función devolverá uno de estos textos al azar en cada ejecución.                           |
| randomColor(40,60)                 | Devuelve un color aleatorio cercano al verde.   
| trigger(#[Salle de bain][Hydrometrie][Humidité]#)   | 1 si eso es bueno \#\[Salle de bain\]\[Hydrometrie\]\[Humidité\]\# quien inició el escenario de lo contrario 0  |
| triggerValue(#[Salle de bain][Hydrometrie][Humidité]#) | 80 si la hidrometría de \#\[Salle de bain\]\[Hydrometrie\]\[Humidité\]\# es 80%.                         |
| round(#[Salle de bain][Hydrometrie][Humidité]# / 10) | Devuelve 9 si el porcentaje de humedad y 85                     |
| odd(3)                             | Devuelve 1                            |
| median(15,25,20)                   | Devuelve 20                           |
| time_op(#time#, -90)               | si son las 4:50 p.m., regrese : 1 650-1 130 = 1520                          |
| formatTime(1650)                   | Devuelve 4:50 p.m                        |
| floor(130/60)                      | Devuelve 2 (minutos si 130 s, u horas si 130 m)                      |

Pedidos específicos
=========================

Además de los comandos de automatización del hogar, tiene acceso a las siguientes acciones :

-   **Pausa** (sleep) : Pausa de x segundo (s).

-   **variable** (variable) : Creación / modificación de una variable o valor
    de una variable.

-   **Eliminar variable** (delete_variable) : Le permite eliminar una variable

-   **Guión** (scenario) : Te permite controlar escenarios. La parte de las etiquetas
    permite enviar etiquetas al escenario, ej : montag = 2 (ten cuidado allí
    solo use letras de la a a la z. No letras mayúsculas, no
    acentos y sin caracteres especiales). Obtenemos la etiqueta en el
    escenario objetivo con la función de etiqueta (montag). El comando "Restablecer a SI" permite restablecer el estado de "SI" (este estado se utiliza para la no repetición de las acciones de un "SI" si pasa por segunda vez consecutiva en él)

-   **Detener** (stop) : Detener el escenario.

-   **Esperar** (wait) : Espere hasta que la condición sea válida
    (máximo 2 h), el tiempo de espera es en segundos (s).

-   **Ir al diseño** (gotodesign) : Cambiar el diseño que se muestra en todos
    navegadores por diseño solicitado.

-   **Agregar un registro** (log) : Le permite agregar un mensaje a los registros.

-   **Crear mensaje** (message) : Agrega un mensaje en el centro
    de mensajes.

-   **Activar / Desactivar Ocultar / mostrar equipo** (equipement) : Permite
    modificar las propiedades de un dispositivo
    visible / invisible, activo / inactivo.

-   **Hacer una solicitud** (ask) : Le permite decirle a Jeedom que pregunte
    una pregunta al usuario. La respuesta se almacena en un
    variable, luego simplemente pruebe su valor. Por el momento,
    solo sms y complementos de holgura son compatibles. Ten cuidado, esto
    la función está bloqueando. Mientras no haya respuesta o el
    no se alcanza el tiempo de espera, el escenario espera.

-   **Stop Jeedom** (jeedom_poweroff) : Pídale a Jeedom que cierre.

-   **Reiniciar Jeedom** (jeedom_reboot) : Pedirle a Jeedom que reinicie.

-   **Devolver un texto / datos** (vuelta_escenario) : Devuelve un texto o un valor
    para una interacción por ejemplo.

-   **Icono** (icon) : Permite cambiar el ícono de representación del escenario.

-   **Advertencia** (alert) : Le permite mostrar un pequeño mensaje de alerta en todos
    navegadores que tienen abierta una página de Jeedom. Usted puede
    más, elige 4 niveles de alerta.

-   **Pop-up** (popup) : Permite mostrar una ventana emergente que debe ser absolutamente
    validado en todos los navegadores que tienen una página abierta de libertad.

-   **Relación** (report) : Exportar una vista en formato (PDF, PNG, JPEG
    o SVG) y enviarlo a través de un comando de tipo de mensaje.
    Tenga en cuenta que si su acceso a Internet está en HTTPS sin firmar, esto
    la funcionalidad no funcionará. Se requiere HTTP o HTTPS firmado.

-   **Eliminar bloque IN / A programado** (remove_inat) : Le permite eliminar el
    programación de todos los bloques IN y A del escenario.

-   **Evento** (event) : Le permite insertar un valor en un comando de tipo de información arbitrariamente

-   **Etiqueta** (tag) : Le permite agregar / modificar una etiqueta (la etiqueta solo existe durante la ejecución actual del escenario a diferencia de las variables que sobreviven al final del escenario)

Plantilla de escenario
====================

Esta funcionalidad le permite transformar un escenario en una plantilla para
por ejemplo, aplícalo en otro Jeedom o compártelo en el
Mercado. También es a partir de ahí que puedes recuperar un escenario
del mercado.

![scenario15](../images/scenario15.JPG)

Entonces verás esta ventana :

![scenario16](../images/scenario16.JPG)

A partir de ahí, tienes la posibilidad :

-   Enviar una plantilla a Jeedom (archivo JSON de antemano
    recuperado),

-   Consulte la lista de escenarios disponibles en el mercado,

-   Crear una plantilla a partir del escenario actual (no olvides
    Dame un nombre),

-   Para consultar las plantillas actualmente presentes en su Jeedom.

Al hacer clic en una plantilla, obtienes :

![scenario17](../images/scenario17.JPG)

En la cima puedes :

-   **Compartir, repartir** : compartir la plantilla en el mercado,

-   **Remove** : eliminar plantilla,

-   **Descargar** : recuperar la plantilla como un archivo JSON
    para enviarlo de vuelta a otro Jeedom por ejemplo.

A continuación, tiene la parte para aplicar su plantilla a
escenario actual.

Desde un Jeedom a otro o de una instalación a otra,
los pedidos pueden ser diferentes, Jeedom te pregunta el
correspondencia de órdenes entre los presentes durante la creación
de la plantilla y los presentes en casa. Solo necesita completar el
las órdenes de partido se aplican.

Adición de la función php
====================

> **Importante**
>
> Agregar funciones PHP está reservado para usuarios avanzados. El más mínimo error puede bloquear tu Jeedom

## Configurar

Vaya a la configuración de Jeedom, luego OS / DB e inicie el editor de archivos.

Vaya a la carpeta de datos, luego php y haga clic en el archivo user.function.class.php.

Es en esta clase que debe agregar sus funciones, encontrará allí un ejemplo de función básica.

> **Importante**
>
> Si tiene un problema, siempre puede volver al archivo original y copiar el contenido de user.function.class.sample.php en user.function.class.php
