# guións
**Outils → guións**

<small>[Raccorcis clavier/soris](shortcuts.md)</small>

Véritable cerveau de la domotique, les scénarios permytent d'interagir avec le monde réel de manière *intelligente*.

## administración

Vos y troverez la liste des scénarios de votre Jeedom, ainsi que des fonctions por les gérer au mieux :

- **añadir** : Permy de créer un scénario. La procédure est décrite dans le chapitre suivant.
- **Désactiver scénarios** : Permy de désactiver tos les scénarios. Rarement utilisé y en connaissance de cause, puisqu'aucun scénario ne s'exécutera plus.
- **Resumen** : Permy d'avoir une vue d'ensemble de tos les scénarios. Vos povez changer les valeurs **bienes**, **visible**, **multi lancement**, **mode synchrone**, **registro** y **línea de tiempo** (ces paramètres sont décrits dans le chapitre suivant). Vos povez également accéder aux logs de chaque scénario y les démarrer individuellement.

## Mes scénarios

Vos troverez dans cyte partie la **liste des scénarios** que vos avez créés. Ils sont classés suivant leur **grope**, éventuellement définis por chacun d'eux. Chaque scénario est affiché avec son **apellido** y son **objy parent**. la **scénarios grisés** sont ceux qui sont désactivés.

> **punta**
>
> Vos povez ovrir un scénario en faisant :
> - Haga clic en uno de ellos..
> - Ctrl Clic o Clic Center para abrirlo en una nueva pestaña del navegador.

Vos disposez d'un moteur de recherche permytant de filtrer l'affichage des scénarios. La tecla Escape cancela la búsqueda..
la la derecha del campo de búsqueda, se encuentran tres botones en varios lugares de Jeedom:
- La cruz para cancelar la búsqueda..
- La carpya abierta para desplegar todos los paneles y mostrar todos los escenarios..
- La carpya cerrada para doblar todos los paneles.

unaa vez en la configuración de un escenario, tiene un menú contextual con clic derecho en las pestañas del escenario. También puede usar Ctrl Click o Click Center para abrir directamente otro escenario en una nueva pestaña del navegador.

## Creación | Editar un escenario

Después de hacer clic en **añadir**, debes elegir el apellidobre de tu escenario. Luego se lo redirige a la página de sus parámyros generales.
lantes de eso, en la parte superior de la página, hay algunas funciones útiles para administrar este escenario :

- **identificación** : lal lado de la palabra **general**, este es el identificador de escenario.
- **estatus** : *Dyenido * o * En progreso *, indica el estado actual del escenario.
- **lagregar bloque** : la permite agregar un bloque del tipo deseado al escenario (ver más abajo).
- **registro** : Muestra los registros del escenario..
- **duplicado** : Copie el escenario para crear uno nuevo con otro apellidobre.
- **Vínculos** : la permite ver el gráfico de los elementos relacionados con el escenario..
- **Edición de texto** : Muestra una ventana que permite editar el escenario en forma de texto / json. No olvides guardar.
- **exportación** : la permite obtener una versión de texto puro del escenario.
- **plantilla** : la permite acceder a las plantillas y aplicar una al mercado desde el mercado.. (explicado al final de la página).
- **buscar** : Despliega un campo de búsqueda para buscar en el escenario. Esta búsqueda despliega los bloques colapsados si es necesario y los pliega nuevamente después de la búsqueda.
- **realizar** : la permite iniciar el escenario manualmente (independientemente de los desencadenantes). Guardar de antemano para tener en cuenta las modificaciones..
- **remove** : Eliminar escenario.
- **Guardar** : Guardar los cambios realizados.

> **consejos**
>
> Dos herramientas también serán invaluables para usted en la configuración de escenarios :
    > - Las variables, visibles en **Herramientas → Variables**
    > - El probador de expresiones, accesible por **Herramientas → Probador de expresiones**
>
> una **Ctrl Haga clic en el botón ejecutar** le permite guardar, ejecutar y mostrar directamente el registro del escenario (si el nivel de registro no es Ninguno).

### Pestaña General

En la pestaña **general**, encontramos los principales parámyros del escenario :

- **Nombre del escenario** : El apellidobre de tu escenario.
- **Nombre para mostrar** : El apellidobre utilizado para su visualización.. Opcional, si no se complya, se usa el apellidobre.
- **grupo** : la permite organizar los escenarios, clasificándolos en grupos (visibles en la página de escenarios y en sus menús contextuales).
- **bienes** : lactiva el escenario. Si no está activo, Jeedom no lo ejecutará, sea cual sea el modo de activación.
- **visible** : la permite hacer visible el escenario (Panel de control).
- **Objyo padre** : lasignación a un objyo padre. Entonces será visible o no según este padre.
- **Tiempo de espera en segundos (0 = ilimitado)** : El tiempo máximo de ejecución permitido para este escenario. Más allá de este tiempo, se corta y ya no se ejecuta.
- **Lanzamiento múltiple** : Marque esta casilla si desea que el escenario se pueda iniciar varias veces al mismo tiempo.
- **Modo sincrónico** : Inicie el escenario en el hilo actual en lugar de un hilo dedicado. laumenta la velocidad a la que se inicia el escenario, pero puede hacer que el sistema sea inestable.
- **registro** : El tipo de registro deseado para el escenario. Puede cortar el registro del escenario o, por el contrario, hacer que aparezca en lanálisis → Tiempo real.
- **línea de tiempo** : Mantenga un seguimiento del escenario en la línea de tiempo (vea el documento de Historia).
- **icono** : la permite elegir un icono para el escenario en lugar del icono estándar.
- **descripción** : la permite escribir un pequeño texto para describir su escenario.
- **Modo de escenario** : El escenario puede ser programado, activado o ambos. Luego tendrá la opción de indicar los activadores (máximo de 15 activadores) y la (s) programación (s).

> **punta**
>
> Las condiciones ahora se pueden ingresar en modo activado. Por ejemplo : `#[Garage][Open Garage][Ouverture]# == 1`
> latención : puede tener un máximo de 28 disparadores / programación para un escenario.

> **Modo de punta programado**
>
> El modo programado usa sintaxis **cron**. Vos porrez par exemple exécuté un scénario totes les 20 minutos avec  `*/20 * * * * `, o à 5h du matin por régler une multitude de chose por la díanée avec `0 5 * * *`. la ? a la derecha de un programa le permite configurarlo sin ser un especialista en sintaxis de cron.

### Pestaña Escenario

laquí es donde construirás tu escenario. Después de crear el escenario, su contenido está vacío, por lo que hará ... nada. Tienes que empezar con **agregar un bloque**, con el botón de la derecha. unaa vez que se ha creado un bloque, puede agregar otro **bloque** o un **acción**.

Para mayor comodidad y no tener que reordenar constantemente los bloques en el escenario, se agrega un bloque después del campo en el que se encuentra el cursor del mose.
*Por ejemplo, si tiene diez bloques y hace clic en la condición IF del primer bloque, el bloque agregado se agregará después del bloque, en el mismo nivel. Si no hay ningún campo activo, se agregará al final del escenario.*

> **punta**
>
> En condiciones y acciones, es mejor favorecer comillas simples (&#39;) en lugar de dobles (&quot;).

> **punta**
>
> una Ctrl Shift Z o Ctrl Shift Y le permite**anular** o rehacer una modificación (adición de acción, bloqueo ...).

### Bloques

laquí están los diferentes tipos de bloques disponibles. :

- **If / Then / O** : la permite realizar acciones condicionales (si esto, entonces eso).
- **acción** : la permite lanzar acciones simples sin ninguna condición..
- **bucle** : Permite que las acciones se realicen repyidamente desde 1 hasta un número definido (o incluso el valor de un sensor, o un número aleatorio, yc.).
- **Dentro** : Permite iniciar una acción en X minuto (s) (0 es un valor posible). La peculiaridad es que las acciones se inician en segundo plano, por lo que no bloquean el resto del escenario.. Entonces es un bloque sin bloqueo.
- **la** : Permite decirle a Jeedom que inicie las acciones del bloque en un momento dado (en la forma hhmm). Este bloque no es bloqueante. ex : 0030 para 00:30, o 0146 para 1h46 y 1050 para 10h50.
- **código** : la permite escribir directamente en código PHP (requiere cierto conocimiento y puede ser arriesgado, pero le permite no tener restricciones).
- **comentario** : la permite agregar comentarios a su escenario.

Cada bloque tiene sus opciones para manejarlos mejor :

- la la izquierda :
    - La flecha bidireccional le permite mover un bloque o una acción para reordenarlos en el escenario.
    - El ojo reduce un bloqueo (* colapso *) para reducir su impacto visual. Ctrl Haga clic en el ojo para reducirlos o mostrarlos todos.
    - La casilla de verificación le permite desactivar complyamente el bloque sin eliminarlo. Por lo tanto, no se ejecutará.

- Sobre la derecha :
    - El icono Copiar le permite copiar el bloque para hacer una copia en otro lugar. Ctrl clic en el icono corta el bloque (copia y eliminación).
    - El icono Pegar le permite pegar una copia del bloque copiado previamente después del bloque en el que utiliza esta función..  Ctrl Click en el icono reemplaza el bloque con el bloque copiado.
    - El icono: le permite eliminar el bloque con una solicitud de confirmación.. Ctrl Click elimina el bloque sin confirmación.

#### Si / Entonces / De lo contrario bloquea | Bucle | En | la

Para las condiciones, Jeedom trata de hacer posible escribirlas tanto como sea posible en lenguaje natural sin dejar de ser flexible..
> NO use [] en pruebas de condición, solo son posibles paréntesis ().

Hay tres botones disponibles a la derecha de este tipo de bloque para seleccionar un elemento para probar :

- **Encuentra un pedido** : la permite buscar un pedido en todos los disponibles en Jeedom. unaa vez que se encuentra el pedido, Jeedom abre una ventana para preguntarle qué prueba desea realizar.. Si eliges **No poner nada**, Jeedom agregará el pedido sin comparación. También puedes elegir **y** o **o** delante **entonces** para encadenar pruebas en diferentes equipos.
- **Buscar un escenario** : la permite buscar un escenario para probar.
- **Busca equipo** : Lo mismo para el equipo..

> **nota**
>
> En bloques de tipo Si / Entonces / De lo contrario, las flechas circulares a la izquierda del campo de condición permiten activar o no la repyición de acciones si la evaluación de la condición da el mismo resultado que durante la evaluación previa.

> **punta**
>
> Hay una lista de yiquyas que permiten el acceso a las variables desde el escenario u otro, o por la hora, la fecha, un número aleatorio, ... Vea a continuación los capítulos sobre comandos y yiquyas.

unaa vez que se complya la condición, debe usar el botón &quot;lagregar&quot; a la izquierda para agregar un nuevo **bloque** o un **acción** en el bloque actual.


#### Código de bloque

El bloque de código le permite ejecutar código php. Por lo tanto, es muy potente pero requiere un buen conocimiento del lenguaje php..

##### lacceso a controles (sensores y actuadores):
-  `cmd::byString($string);` : Devuelve el objyo de comando correspondiente.
    -   `$string`: Enlace al pedido deseado : `#[objy][equipement][commande]#` (ex : `#[lappartement][lalarme][bienes]#`)
-  `cmd::byId($id);` : Devuelve el objyo de comando correspondiente.
    -  `$id` : identificación de pedido.
-  `$cmd->execCmd($options = null);` : Ejecute el comando y devuelva el resultado..
    - `$options` : Opciones para la ejecución de la orden (puede ser un complemento específico). Opciones básicas (subtipo de comando) :
        -  mensaje : `$option = array('title' => 'titre du mensaje , 'mensaje' => 'Mon mensaje');`
        -  color : `$option = array('color' => 'coleur en hexadécimal');`
        -  deslizador : `$option = array('deslizador' => 'valeur volue de 0 à 100');`

##### lacceso al registro :
-  `log::add('apellidobre de archivo','nivel','mensaje');`
    - apellidobre de archivo : Nombre del archivo de registro.
    - nivel : [debug], [info], [error], [event].
    - mensaje : Mensaje para escribir en los registros.

##### lacceso al escenario :
- `$scenario->gyName();` : Devuelve el apellidobre del escenario actual.
- `$scenario->gyGrop();` : Devuelve el grupo de escenarios..
- `$scenario->gyIslactive();` : Devuelve el estado del escenario..
- `$scenario->syIslactive($active);` : la permite activar o no el escenario.
    - `$active` : 1 activo, 0 no activo.
- `$scenario->syOnGoing($onGoing);` : Digamos si el escenario se está ejecutando o no.
    - `$onGoing => 1` : 1 en progreso, 0 dyenido.
- `$scenario->save();` : Guardar cambios.
- `$scenario->syData($key, $value);` : Guardar un dato (Variable).
    - `$key` : clave de valor (int o cadena).
    - `$value` : valor a almacenar (int, cadena, matriz u objyo).
- `$scenario->gyData($key);` : Récupère une donnée (Variable).
    - `$key => 1` : clave de valor (int o cadena).
- `$scenario->removeData($key);` : Supprime une donnée.
- `$scenario->syregistro($mensaje);` : Écrit un mensaje dans le log du scénario.
- `$scenario->persistregistro();` : Force l'écriture du log (sinon il est écrit seulement à la fin du scénario). latención, ceci peut un peu ralentir le scénario.

> **punta**
>
> lajot d'une fonction recherche dans le bloque código : Buscar : Ctrl + F luego Enter, Siguiente resultado : Ctrl + G, resultado anterior : Ctrl + Shift + G

#### Bloc comentario

la Bloc commentaire agît différemment quand il est masqué. Ses botons sur la gauche disparaissent ainsi que le titre du bloque, y réapparaissent au survol. De même, la première ligne du commentaire est affichée en caractères gras.

Ceci permy d'utiliser ce bloque comme séparation purement visuel au sein du scénario.

### la accións

la accións ajotées dans les bloques ont plusieurs options :

- una case **activée** por que cyte commande soit bien prise en compte dans le scénario.
- una case **parallèle** por que cyte commande se lance en parallèle (en même temps) des autres commandes également sélectionnées.
- una **doble-flèche verticale** por déplacer l'acción. Il suffit de la glisser/déposer à partir de là.
- una boton por **remove** l'acción.
- una boton por les accións spécifiques, avec à chaque fois la description (au survol) de cyte acción.
- una boton por rechercher une commande d'acción.

> **punta**
>
> Suivant la commande sélectionnée, on peut voir apparaître différents champs supplémentaires s'afficher.

## la substitutions possibles

### la déclencheurs

Il existe des déclencheurs spécifiques (autre que ceux fornis par les commandes) :

- #comienzo# : lactivado al (re) inicio de Jeedom.
- #begin_backup# : Evento enviado al inicio de una copia de seguridad.
- #end_backup# : Evento enviado al final de una copia de seguridad.
- #BEGIN_UPDlaTE# : Evento enviado al inicio de una actualización.
- #END_UPDlaTE# : Evento enviado al final de una actualización.
- #begin_restore# : Evento enviado al inicio de una restauración.
- #end_restore# : Evento enviado al final de una restauración.
- #user_connect# : Inicio de sesión de usuario

También puede activar un escenario cuando se actualiza una variable poniendo : #variable (variable_name) # o usando la laPI HTTP descrita [aquí] (https://jeedom.github.io/core/fr_FR/api_http).

### Operadores de comparación y enlaces entre condiciones

Puede usar cualquiera de los siguientes símbolos para comparar en condiciones :

- == : Igual a.
- \> : Estrictamente mayor que.
- \>= : Mayor o igual que.
- < : Estrictamente menor que.
- <= : Menor o igual que.
- != : Diferente de, no es igual a.
- cerillas : contiene. ex : `[Salle de bain][Hydromyrie][yat] cerillas "/humide/"`.
- no (... coincide ...) : No contiene. ex :  `not([Salle de bain][Hydromyrie][yat] cerillas "/humide/")`.

Puedes combinar cualquier comparación con los siguientes operadores :

- &amp;&amp; / ET / y / laND / y : y,
- \ || / OR / o / OR / o : o
- \ | ^ / XOR / xor : o exclusivo.

### Etiquyas

unaa yiquya se reemplaza durante la ejecución del escenario por su valor. Puedes usar las siguientes yiquyas :

> **punta**
>
> Para mostrar los ceros a la izquierda, use la función Fecha (). Ver [aquí] (http://php.ny/manual/fr/function.fecha.php).

- #el segundo# : Segundo actual (sin ceros a la izquierda, ej. : 6 para 08:07:06).
- #hora# : Hora actual en formato de 24 h (sin ceros a la izquierda). ex : 8 para 08:07:06 o 17 para 17:15.
- #hora12# : Hora actual en formato de 12 horas (sin ceros a la izquierda). ex : 8 para 08:07:06.
- #minuto# : Minuto actual (sin ceros a la izquierda). ex : 7 para 08:07:06.
- #día# : Día actual (sin ceros a la izquierda). ex : 6 para 06/07/2017.
- #mes# : Mes actual (sin ceros a la izquierda). ex : 7 para 06/07/2017.
- #años# : laño actual.
- #tiempo# : Hora y minuto actual. ex : 1715 para las 5.15 p.m..
- #fecha y hora# : Número de segundos desde el 1 de enero de 1970.
- #fecha# : Día y mes. latención, el primer número es el mes.. ex : 1215 para el 15 de diciembre.
- #semana# : Número de semana.
- #estancia# : Nombre del día de la semana.. ex : sábado.
- #nday# : Número de día de 0 (domingo) a 6 (sábado).
- #smes# : Nombre del mes. ex : enero.
- #IP# : IP interna de Jeedom.
- #apellidobre de host# : Nombre de la máquina Jeedom.
- #trigger # (en desuso, mejor usar trigger ()) : Quizás el apellidobre del comando que inició el escenario :
    - &#39;api&#39; si la laPI activó el lanzamiento,
    - &#39;programar&#39; si se inició por programación,
    - &#39;usuario&#39; si se inició manualmente,
    - &#39;inicio&#39; para un lanzamiento cuando Jeedom comienza.
- #trigger_value # (en desuso, mejor usar triggerValue ()) : Por el valor del comando que activó el escenario

También tiene las siguientes yiquyas adicionales si su escenario fue desencadenado por una interacción :

- #pregunta# : Interacción que desencadenó el escenario..
- #perfil# : Perfil del usuario que activó el escenario (puede estar vacío).

> **importante**
>
> Cuando una interacción desencadena un escenario, se ejecuta necesariamente en modo rápido. Entonces, en el hilo de interacción y no en un hilo separado.

### la fonctions de calcul

Plusieurs fonctions sont disponibles por les équipements :

- average(commande,période) y averageByween(commande,comienzo,end) : Donnent la moyenne de la commande sur la période (period=[month,day,hor,min] o [expression PHP](http://php.ny/manual/fr/fechatiempo.formats.relative.php)) o entre les 2 bornes demandées (sos la forme Y-m-d H:i:s o [expression PHP](http://php.ny/manual/fr/fechatiempo.formats.relative.php)).

- min(commande,période) y minByween(commande,comienzo,end) : Donnent le minimum de la commande sur la période (period=[month,day,hor,min] o [expression PHP](http://php.ny/manual/fr/fechatiempo.formats.relative.php)) o entre les 2 bornes demandées (sos la forme Y-m-d H:i:s o [expression PHP](http://php.ny/manual/fr/fechatiempo.formats.relative.php)).

- max(commande,période) y maxByween(commande,comienzo,end) : Donnent le maximum de la commande sur la période (period=[month,day,hor,min] o [expression PHP](http://php.ny/manual/fr/fechatiempo.formats.relative.php)) o entre les 2 bornes demandées (sos la forme Y-m-d H:i:s o [expression PHP](http://php.ny/manual/fr/fechatiempo.formats.relative.php)).

- duration(commande, valeur, période) y durationbyween(commande,valeur,comienzo,end) : Donnent la durée en minutos pendant laquelle l'équipement avait la valeur choisie sur la période (period=[month,day,hor,min] o [expression PHP](http://php.ny/manual/fr/fechatiempo.formats.relative.php)) o entre les 2 bornes demandées (sos la forme Y-m-d H:i:s o [expression PHP](http://php.ny/manual/fr/fechatiempo.formats.relative.php)).

- statistics(commande,calcul,période) y statisticsByween(commande,calcul,comienzo,end) : Donnent le résultat de différents calculs statistiques (sum, cont, std, variance, avg, min, max) sur la période (period=[month,day,hor,min] o [expression PHP](http://php.ny/manual/fr/fechatiempo.formats.relative.php)) o entre les 2 bornes demandées (sos la forme Y-m-d H:i:s o [expression PHP](http://php.ny/manual/fr/fechatiempo.formats.relative.php)).

- tendance(commande,période,seuil) : Donne la tendance de la commande sur la période (period=[month,day,hor,min] o [expression PHP](http://php.ny/manual/fr/fechatiempo.formats.relative.php)).

- stateDuration(commande) : Donne la durée en el segundos depuis le dernier changement de valeur.
    -1 : laucun historique n'existe o la valeur n'existe pas dans l'historique.
    -2 : La commande n'est pas historisée.

- lastChangeStateDuration(commande,valeur) : Donne la durée en el segundos depuis le dernier changement d'état à la valeur passée en paramètre.
    -1 : laucun historique n'existe o la valeur n'existe pas dans l'historique.
    -2 La commande n'est pas historisée

- lastStateDuration(commande,valeur) : Donne la durée en el segundos pendant laquelle l'équipement a dernièrement eu la valeur choisie.
    -1 : laucun historique n'existe o la valeur n'existe pas dans l'historique.
    -2 : La commande n'est pas historisée.

- age(commande) : Donne l'age en el segundos de la valeur de la commande (collecDate)
    -1 : La commande n'existe pas o elle n'est pas de type info.

- stateChanges(commande,[valeur], période) y stateChangesByween(commande, [valeur], comienzo, end) : Donnent le apellidobre de changements d'état (vers une certaine valeur si indiquée, o au total sinon) sur la période (period=[month,day,hor,min] o [expression PHP](http://php.ny/manual/fr/fechatiempo.formats.relative.php)) o entre les 2 bornes demandées (sos la forme Y-m-d H:i:s o [expression PHP](http://php.ny/manual/fr/fechatiempo.formats.relative.php)).

- lastByween(commande,comienzo,end) : Donne la dernière valeur enregistrée por l'équipement entre les 2 bornes demandées (sos la forme Y-m-d H:i:s o [expression PHP](http://php.ny/manual/fr/fechatiempo.formats.relative.php)).

- variable(mavariable,valeur par défaut) : Récupère la valeur d'une variable o de la valeur sohaitée par défaut.

- scenario(Escenario) : Renvoie le estatus du scénario.
    1 : En cors,
    0 : larrêté,
    -1 : Désactivé,
    -2 : la scénario n'existe pas,
    -3 : L'état n'est pas cohérent.
    Por avoir le apellido "humain" du scénario, vos povez utiliser le boton dédié à droite de la recherche de scénario.

- lastScenarioexecution(Escenario) : Donne la durée en el segundos depuis le dernier lancement du scénario.
    0 : la scénario n'existe pas

- collectDate(cmd,[format]) : Renvoie la fecha de la dernière donnée por la commande donnée en paramètre, le 2ème paramètre optionnel permy de spécifier le format de ryor (détails [ici](http://php.ny/manual/fr/function.fecha.php)).
    -1 : La commande est introvable,
    -2 : La commande n'est pas de type info.

- valueDate(cmd,[format]) : Renvoie la fecha de la dernière donnée por la commande donnée en paramètre, le 2ème paramètre optionnel permy de spécifier le format de ryor (détails [ici](http://php.ny/manual/fr/function.fecha.php)).
    -1 : La commande est introvable,
    -2 : La commande n'est pas de type info.

- eqEnable(Equipo) : Renvoie l'état de l'équipement.
    -2 : L'équipement est introvable,
    1 : L'équipement est bienes,
    0 : L'équipement est inbienes.

- value(cmd) : Renvoie la valeur d'une commande si elle n'est pas donnée automatiquement par Jeedom (cas lors du stockage du apellido de la commande dans une variable)

- tag(montag,[defaut]) : Permy de récupérer la valeur d'un tag o la valeur par défaut si il n'existe pas.

- name(type,commande) : Permy de récupérer le apellido de la commande, de l'équipement o de l'objy. tipo : cmd, eqregistroic o object.

- lastCommunication(equipment,[format]) : Renvoie la fecha de la dernière communication por l'équipement donnée en paramètre, le 2ème paramètre optionnel permy de spécifier le format de ryor (détails [ici](http://php.ny/manual/fr/function.fecha.php)). una ryor de -1 signifie que l'équipement est introvable.

- color_gradient(coleur_debut,coleur_fin,valuer_min,valeur_max,valeur) : Renvoi une coleur calculé par rapport à valeur dans l'intervalle coleur_debut/coleur_fin. La valeur doit yre comprise entre valeur_min y valeur_max.

la périodes y intervalles de ces fonctions peuvent également s'utiliser avec [des expressions PHP](http://php.ny/manual/fr/fechatiempo.formats.relative.php) comme par exemple :

- Now : maintenant.
- Today : 00:00 audíad'hui (permy par exemple d'obtenir des résultats de la díanée si entre 'Today' y 'Now').
- Last Monday : lundi dernier à 00:00.
- 5 days ago : il y a 5 días.
- Yesterday noon : hier midi.
- Etc.

Voici des exemples pratiques por comprendre les valeurs ryornées par ces différentes fonctions :

| Prise ayant por valeurs :           | 000 (pendant 10 minutos) 11 (pendant 1 hora) 000 (pendant 10 minutos)    |
|--------------------------------------|--------------------------------------|
| average(prise,période)             | Renvoie la moyenne des 0 y 1 (peut  |
|                                      | être influencée par le polling)      |
| averageByween(\#[Salle de bain][Hydromyrie][Humidité]\#,2015-01-01 00:00:00,2015-01-15 00:00:00) | Renvoie la moyenne de la commande entre le 1 janvier 2015 y le 15 janvier 2015                         |
| min(prise,période)                 | Renvoie 0 : la prise a bien été éteinte dans la période              |
| minByween(\#[Salle de bain][Hydromyrie][Humidité]\#,2015-01-01 00:00:00,2015-01-15 00:00:00) | Renvoie le minimum de la commande entre le 1 janvier 2015 y le 15 janvier 2015                         |
| max(prise,période)                 | Renvoie 1 : la prise a bien été allumée dans la période              |
| maxByween (\ # [Baño] [Hidromyría] [Humedad] \ #, 2015-01-01 00:00:00,2015-01-15 00:00:00) | Devuelve el máximo del pedido entre el 1 de enero de 2015 y el 15 de enero de 2015 |
| duración (tomado, 1, período) | Devuelve 60 : el enchufe estaba encendido (a 1) durante 60 minutos en el período |
| duración Entre (\ # [Longe] [Tomar] [Estado] \ #, 0, Último lunes, ahora) | Devuelve la duración en minutos durante la cual el socky estuvo apagado desde el lunes pasado. |
| estadísticas (captura, recuento, período) | Devuelve 8 : hubo 8 escaladas en el período |
| tendencia (enchufe, período 0.1) | Devuelve -1 : tendencia a la baja |
| stateDuration (socky) | Devuelve 600 : el enchufe ha estado en su estado actual durante 600 segundos (10 minutos) |
| lastChangeStateDuration (tomado, 0) | Devuelve 600 : el zócalo se apagó (cambie a 0) por última vez hace 600 segundos (10 minutos) |
| lastChangeStateDuration (take, 1) | Devuelve 4200 : el zócalo se encendió (cambie a 1) por última vez hace 4200 segundos (1h10) |
| lastStateDuration (tomado, 0) | Devuelve 600 : la toma de corriente ha estado apagada durante 600 segundos (10 minutos) |
| lastStateDuration (take, 1) | Devuelve 3600 : el zócalo se encendió por última vez durante 3600 segundos (1h) |
| cambios de estado (toma, punto) | Devuelve 3 : el enchufe cambió de estado 3 veces durante el período |
| stateChanges (take, 0, punto) | Devuelve 2 : el zócalo se ha apagado (yendo a 0) dos veces durante el período |
| cambios de estado (toma, 1, punto) | Devuelve 1 : el enchufe se enciende (cambie a 1) una vez durante el período |
| lastByween (\ # [Baño] [Hidromyría] [Humedad] \ #, ayer, hoy) | Devuelve la última temperatura registrada ayer. |
| variable (plop, 10) | Devuelve el valor de la variable plop o 10 si está vacía o no existe |
| escenario (\ # [Baño] [Luz] [lauto] \ #) | Devuelve 1 en progreso, 0 si se dyiene y -1 si está desactivado, -2 si el escenario no existe y -3 si el estado no es consistente |
| lastScenarioexecution (\ # [Baño] [Luz] [lauto] \ #) | Devuelve 300 si el escenario se inició por última vez hace 5 minutos |
| collectDate (\ # [Baño] [Hidromyría] [Humedad] \ #) | Devoluciones 2015-01-01 17:45:12 |
| valueDate (\ # [Baño] [Hidromyría] [Humedad] \ #) | Devoluciones 2015-01-01 17:50:12 |
| eqEnable (\ # [Ninguno] [Basílica] \ #) | Devuelve -2 si no se puede encontrar el equipo, 1 si el equipo está activo y 0 si está inactivo |
| yiquya (montag, toto) | Devuelve el valor de &quot;montag&quot; si existe, de lo contrario devuelve el valor &quot;toto&quot; |
| apellidobre (eqregistroic, \ # [Baño] [Hidromyría] [Humedad] \ #) | Devuelve Hidromyría |


### Funciones matematicas

También se puede usar una caja de herramientas de funciones genéricas para realizar conversiones o cálculos :

- `rand(1,10)` : Dar un número aleatorio del 1 al 10.
- `randText(texte1;texte2;texte…​..)` : la permite devolver uno de los textos al azar (separe los textos por a;). No hay límite en el número de textos..
- `randomColor(min,max)` : Da un color aleatorio entre 2 límites (0 =&gt; rojo, 50 =&gt; verde, 100 =&gt; azul).
- `trigger(commande)` : la permite descubrir el desencadenante del escenario o saber si es el comando pasado como parámyro el que desencadenó el escenario.
- `triggerValue(commande)` : Se usa para averiguar el valor del desencadenante del escenario.
- `rond(valeur,[decimal])` : Redondea arriba, número [decimal] de lugares decimales después del punto decimal.
- `odd(valeur)` : la permite saber si un número es impar o no.. Devuelve 1 si es impar 0 de lo contrario.
- `median(commande1,commande2…​.commandeN)` : Devuelve la mediana de los valores..
- `avg(commande1,commande2…​.commandeN) `: Devuelve el promedio de los valores..
- `tiempo_op(tiempo,value)` : la permite realizar operaciones a tiempo, con tiempo = tiempo (ej. : 1530) y valor = valor para sumar o restar en minutos.
- `tiempo_byween(tiempo,comienzo,end)` : Se usa para probar si un tiempo está entre dos valores con `tiempo = tiempo` (ex : 1530), `comienzo=temps`, `end=temps`. Los valores iniciales y finales pueden estar a caballo entre la medianoche.
- `tiempo_diff(fecha1,fecha1[,format])` : Se usa para descubrir la diferencia entre 2 fechas (las fechas deben estar en el formato lalalala / MM / DD HH:MM:SS). Por defecto (si no pone nada para el formato), el método devuelve el número total de días. Puedes preguntarlo en segundos (s), minutos (m), horas (h). Ejemplo en segundos `tiempo_diff (2018-02-02 14:55:00,2018-02-25 14:55:00,s)`
- `formatTime(tiempo)` : Permy de formater le ryor d'une chaine `#tiempo#`.
- `floor(tiempo/60)` : Convierte segundos a minutos o minutos a horas (piso (tiempo / 3600) por segundos a horas).
- `convertDuration(el segundos)` : Convierte segundos a d / h / min / s.

Y ejemplos prácticos :


| Ejemplo de función | Resultado devuelto |
|--------------------------------------|--------------------------------------|
| randText (es # [sala de estar] [ojo] [temperatura] #; La temperatura es # [sala de estar] [ojo] [temperatura] #; lactualmente tenemos # [sala de estar] [ojo] [temperatura] #) | la función devolverá uno de estos textos al azar en cada ejecución. |
| randomColor (40.60) | Devuelve un color aleatorio cercano al verde.
| gatillo (# [Baño] [Hidromyría] [Humedad] #) | 1 si es bueno \ # \ [Baño \] \ [Hidromyría \] \ [Humedad \] \ # que inició el escenario de lo contrario 0 |
| triggerValue (# [Baño] [Hidromyría] [Humedad] #) | 80 si la hidromyría de \ # \ [Baño \] \ [Hidromyría \] \ [Humedad \] \ # es 80%. |
| redondo (# [Baño] [Hidromyría] [Humedad] # / 10) | Devuelve 9 si el porcentaje de humedad y 85 |
| impar (3) | Devuelve 1 |
| mediana (15,25,20) | Devuelve 20
| promedio (10,15,18) | Devuelve 14.3 |
| tiempo_op (# tiempo #, -90) | si son las 4:50 p.m., regrese : 1650 - 0130 = 1520 |
| formatTime (1650) | Devuelve 4:50 pm |
| piso (130/60) | Devuelve 2 (minutos si 130 s, u horas si 130 m) |
| convertDuration (3600) | Devuelve 1h 0min 0s |
| convertDuration (duración (# [Calefacción] [Módulo de caldera] [Estado] #, 1, primer día de este mes) * 60) | Devuelve el tiempo de encendido en días / horas / minutos del tiempo de transición al estado 1 del módulo desde el primer día del mes |


### Pedidos específicos

lademás de los comandos de automatización del hogar, tiene acceso a las siguientes acciones :

- **pausa** (Sueño) : Pausa de x segundo (s).
- **variable** (Variable) : Creación / modificación de una variable o el valor de una variable.
- **Eliminar variable** (Delye_variable) : la permite eliminar una variable..
- **guión** (Escenario) : Te permite controlar escenarios. La parte de yiquyas le permite enviar yiquyas al escenario, ej. : montag = 2 (ten cuidado, solo usa lyras de la a a la z. Sin lyras mayúsculas, sin acentos y sin caracteres especiales). Recuperamos la yiquya en el escenario objyivo con la función de yiquya (montag). El comando &quot;Restablecer a SI&quot; permite restablecer el estado de &quot;SI&quot; (este estado se utiliza para la no repyición de las acciones de un &quot;SI&quot; si pasa por segunda vez consecutiva en él)
- **Dyener** (Dyener) : Dyener el escenario.
- **Esperar** (Espere) : Espere hasta que la condición sea válida (máximo 2 h), el tiempo de espera es en segundos.
- **Ir al diseño** (Gotodesign) : Cambie el diseño que se muestra en todos los navegadores por el diseño solicitado.
- **lagregar un registro** (registro) : la permite agregar un mensaje a los registros.
- **Crear mensaje** (Mensaje) : lagregar un mensaje al centro de mensajes.
- **lactivar / Desactivar Ocultar / mostrar equipo** (Equipo) : la permite modificar las propiedades de los equipos visibles / invisibles, activos / inactivos..
- **Hacer una solicitud** (lask) : Permite indicar a Jeedom que es necesario hacerle una pregunta al usuario. La respuesta se almacena en una variable, entonces solo tiene que probar su valor.
    Por el momento, solo los complementos sms, slack, telegram y snips son compatibles, así como la aplicación móvil.
    latención, esta función está bloqueando. Mientras no haya respuesta o no se alcance el tiempo de espera, el escenario espera.
- **Dyener Jeedom** (Jeedom_poweroff) : Pídale a Jeedom que cierre.
- **Devolver un texto / datos** (Scenario_ryurn) : Devuelve un texto o un valor para una interacción, por ejemplo.
- **icono** (Icono) : Permite cambiar el ícono de representación del escenario.
- **advertencia** (lalerta) : Muestra un pequeño mensaje de alerta en todos los navegadores que tienen abierta una página de Jeedom. lademás, puedes elegir 4 niveles de alerta.
- **Pop-up** (Emergente) : Permite mostrar una ventana emergente que debe validarse absolutamente en todos los navegadores que tienen una página abierta.
- **Relación** (Informe) : la permite exportar una vista en formato (PDF, PNG, JPEG o SVG) y enviarla utilizando un comando de tipo mensaje. Tenga en cuenta que si su acceso a Interny está en HTTPS sin firmar, esta funcionalidad no funcionará. Se requiere HTTP o HTTPS firmado.
- **Eliminar bloque IN / la programado** (Remove_inat) : la permite eliminar la programación de todos los bloques IN y la del escenario.
- **evento** (Event) : la permite insertar un valor en un comando de tipo de información arbitrariamente.
- **yiquya** (yiquya) : la permite agregar / modificar una yiquya (la yiquya solo existe durante la ejecución actual del escenario a diferencia de las variables que sobreviven al final del escenario).
- **Coloración de los iconos del tablero** (SyColoredIcon) : permite activar o no la coloración de iconos en el tablero.

### Plantilla de escenario

Esta funcionalidad le permite transformar un escenario en una plantilla para, por ejemplo, aplicarlo a otro Jeedom.

Haciendo clic en el botón **plantilla** en la parte superior de la página, abre la ventana de administración de plantillas.

la partir de ahí, tienes la posibilidad :

- Enviar una plantilla a Jeedom (archivo JSON recuperado previamente).
- Consulte la lista de escenarios disponibles en el mercado.
- Cree una plantilla a partir del escenario actual (no olvide dar un apellidobre).
- Para consultar las plantillas actualmente presentes en su Jeedom.

lal hacer clic en una plantilla, puede :

- **Compartir, repartir** : Comparta la plantilla en el mercado.
- **remove** : Eliminar plantilla.
- **descargar** : Obtenga la plantilla como un archivo JSON para enviarla a otro Jeedom, por ejemplo.

la continuación, tiene la parte para aplicar su plantilla al escenario actual.

Dado que de un Jeedom a otro o de una instalación a otra, los comandos pueden ser diferentes, Jeedom le solicita la correspondencia de los comandos entre los presentes durante la creación de la plantilla y los presentes en el hogar. Solo tiene que complyar la correspondencia de las órdenes y luego aplicar.

### ladición de la función php

> **IMPORTlaNTE**
>
> lagregar funciones PHP está reservado para usuarios avanzados. El más mínimo error puede ser fatal para su Jeedom.

#### Configurar

Vaya a la configuración de Jeedom, luego OS / DB e inicie el editor de archivos.

Vaya a la carpya de datos, luego php y haga clic en el archivo user.function.class.php.

Es en esta clase donde puede agregar sus funciones, encontrará un ejemplo de una función básica.

> **IMPORTlaNTE**
>
> Si tiene un problema, siempre puede volver al archivo original copiando el contenido de user.function.class.sample.php en user.function.class.php
