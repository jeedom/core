# guións
**Outils → guións**

<small>[Raccorcis clavier/soris](shortcuts.md)</small>

Véritable cerveau de la domotique, les scénarios permettent d'interagir avec le monde réel de manière *intelligente*.

## administración

Vos y troverez la liste des scénarios de votre Jeedom, ainsi que des fonctions por les gérer au mieux :

- **añadir** : Permet de créer un scénario. La procédure est décrite dans le chapitre suivant.
- **Désactiver scénarios** : Permet de désactiver tos les scénarios. Rarement utilisé et en connaissance de cause, puisqu'aucun scénario ne s'exécutera plus.
- **Vue d'ensemble** : Permet d'avoir une vue d'ensemble de tos les scénarios. Vos povez changer les valeurs **bienes**, **visible**, **multi lancement**, **mode synchrone**, **registro** et **línea de tiempo** (ces paramètres sont décrits dans le chapitre suivant). Vos povez également accéder aux logs de chaque scénario et les démarrer individuellement.

## Mes scénarios

Vos troverez dans cette partie la **liste des scénarios** que vos avez créés. Ils sont classés suivant leur **grope**, éventuellement définis por chacun d'eux. Chaque scénario est affiché avec son **apellido** et son **objet parent**. la **scénarios grisés** sont ceux qui sont désactivés.

> **punta**
>
> Vos povez ovrir un scénario en faisant :
> - Haga clic en uno de ellos..
> - Ctrl Clic o Clic Center para abrirlo en una nueva pestaña del navegador.

Vos disposez d'un moteur de recherche permettant de filtrer l'affichage des scénarios. La tecla Escape cancela la búsqueda..
la la derecha del campo de búsqueda, se encuentran tres botones en varios lugares de Jeedom:
- La cruz para cancelar la búsqueda..
- La carpeta abierta para desplegar todos los paneles y mostrar todos los escenarios..
- La carpeta cerrada para doblar todos los paneles.

unaa vez en la configuración de un escenario, tiene un menú contextual con clic derecho en las pestañas del escenario. También puede usar Ctrl Click o Click Center para abrir directamente otro escenario en una nueva pestaña del navegador.

## Creación | Editar un escenario

Después de hacer clic en **añadir**, debes elegir el apellidobre de tu escenario. Luego se lo redirige a la página de sus parámetros generales.
lantes de eso, en la parte superior de la página, hay algunas funciones útiles para administrar este escenario :

- **identificación** : lal lado de la palabra **general**, este es el identificador de escenario.
- **estatus** : *Detenido * o * En progreso *, indica el estado actual del escenario.
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

En la pestaña **general**, encontramos los principales parámetros del escenario :

- **Nombre del escenario** : El apellidobre de tu escenario.
- **Nombre para mostrar** : El apellidobre utilizado para su visualización.. Opcional, si no se completa, se usa el apellidobre.
- **grupo** : la permite organizar los escenarios, clasificándolos en grupos (visibles en la página de escenarios y en sus menús contextuales).
- **bienes** : lactiva el escenario. Si no está activo, Jeedom no lo ejecutará, sea cual sea el modo de activación.
- **visible** : la permite hacer visible el escenario (Panel de control).
- **Objeto padre** : lasignación a un objeto padre. Entonces será visible o no según este padre.
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
> El modo programado usa sintaxis **cron**. Vos porrez par exemple exécuté un scénario totes les 20 minutes avec  `*/20 * * * * `, o à 5h du matin por régler une multitude de chose por la jornée avec `0 5 * * *`. la ? a la derecha de un programa le permite configurarlo sin ser un especialista en sintaxis de cron.

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
- **bucle** : Permite que las acciones se realicen repetidamente desde 1 hasta un número definido (o incluso el valor de un sensor, o un número aleatorio, etc.).
- **Dentro** : Permite iniciar una acción en X minuto (s) (0 es un valor posible). La peculiaridad es que las acciones se inician en segundo plano, por lo que no bloquean el resto del escenario.. Entonces es un bloque sin bloqueo.
- **la** : Permite decirle a Jeedom que inicie las acciones del bloque en un momento dado (en la forma hhmm). Este bloque no es bloqueante. ex : 0030 para 00:30, o 0146 para 1h46 y 1050 para 10h50.
- **código** : la permite escribir directamente en código PHP (requiere cierto conocimiento y puede ser arriesgado, pero le permite no tener restricciones).
- **comentario** : la permite agregar comentarios a su escenario.

Cada bloque tiene sus opciones para manejarlos mejor :

- la la izquierda :
    - La flecha bidireccional le permite mover un bloque o una acción para reordenarlos en el escenario.
    - El ojo reduce un bloqueo (* colapso *) para reducir su impacto visual. Ctrl Haga clic en el ojo para reducirlos o mostrarlos todos.
    - La casilla de verificación le permite desactivar completamente el bloque sin eliminarlo. Por lo tanto, no se ejecutará.

- Sobre la derecha :
    - El icono Copiar le permite copiar el bloque para hacer una copia en otro lugar. Ctrl clic en el icono corta el bloque (copia y eliminación).
    - El icono Pegar le permite pegar una copia del bloque copiado previamente después del bloque en el que utiliza esta función..  Ctrl Click en el icono reemplaza el bloque con el bloque copiado.
    - El icono: le permite eliminar el bloque con una solicitud de confirmación.. Ctrl Click elimina el bloque sin confirmación.

#### Si / Entonces / De lo contrario bloquea | Bucle | En | la

Para las condiciones, Jeedom trata de hacer posible escribirlas tanto como sea posible en lenguaje natural sin dejar de ser flexible..
> NO use [] en pruebas de condición, solo son posibles paréntesis ().

Hay tres botones disponibles a la derecha de este tipo de bloque para seleccionar un elemento para probar :

- **Encuentra un pedido** : la permite buscar un pedido en todos los disponibles en Jeedom. unaa vez que se encuentra el pedido, Jeedom abre una ventana para preguntarle qué prueba desea realizar.. Si eliges **No poner nada**, Jeedom agregará el pedido sin comparación. También puedes elegir **et** o **o** delante **entonces** para encadenar pruebas en diferentes equipos.
- **Buscar un escenario** : la permite buscar un escenario para probar.
- **Busca equipo** : Lo mismo para el equipo..

> **nota**
>
> En bloques de tipo Si / Entonces / De lo contrario, las flechas circulares a la izquierda del campo de condición permiten activar o no la repetición de acciones si la evaluación de la condición da el mismo resultado que durante la evaluación previa.

> **punta**
>
> Hay una lista de etiquetas que permiten el acceso a las variables desde el escenario u otro, o por la hora, la fecha, un número aleatorio, ... Vea a continuación los capítulos sobre comandos y etiquetas.

unaa vez que se completa la condición, debe usar el botón &quot;lagregar&quot; a la izquierda para agregar un nuevo **bloque** o un **acción** en el bloque actual.


#### Código de bloque

El bloque de código le permite ejecutar código php. Por lo tanto, es muy potente pero requiere un buen conocimiento del lenguaje php..

##### lacceso a controles (sensores y actuadores):
-  `cmd::byString($string);` : Devuelve el objeto de comando correspondiente.
    -   `$string`: Enlace al pedido deseado : `#[objet][equipement][commande]#` (ex : `#[lappartement][lalarme][bienes]#`)
-  `cmd::byId($id);` : Devuelve el objeto de comando correspondiente.
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
- `$scenario->getName();` : Devuelve el apellidobre del escenario actual.
- `$scenario->getGrop();` : Devuelve el grupo de escenarios..
- `$scenario->getIslactive();` : Devuelve el estado del escenario..
- `$scenario->setIslactive($active);` : la permite activar o no el escenario.
    - `$active` : 1 activo, 0 no activo.
- `$scenario->setOnGoing($onGoing);` : Digamos si el escenario se está ejecutando o no.
    - `$onGoing => 1` : 1 en progreso, 0 detenido.
- `$scenario->save();` : Guardar cambios.
- `$scenario->setData($key, $value);` : Guardar un dato (variable).
    - `$key` : clave de valor (int o cadena).
    - `$value` : valor a almacenar (int, cadena, matriz u objeto).
- `$scenario->getData($key);` : Récupère une donnée (variable).
    - `$key => 1` : clave de valor (int o cadena).
- `$scenario->removeData($key);` : Supprime une donnée.
- `$scenario->setregistro($mensaje);` : Écrit un mensaje dans le log du scénario.
- `$scenario->persistregistro();` : Force l'écriture du log (sinon il est écrit seulement à la fin du scénario). latención, ceci peut un peu ralentir le scénario.

> **punta**
>
> lajot d'une fonction recherche dans le bloque código : Buscar : Ctrl + F luego Enter, Siguiente resultado : Ctrl + G, resultado anterior : Ctrl + Shift + G

#### Bloc comentario

la Bloc commentaire agît différemment quand il est masqué. Ses botons sur la gauche disparaissent ainsi que le titre du bloque, et réapparaissent au survol. De même, la première ligne du commentaire est affichée en caractères gras.

Ceci permet d'utiliser ce bloque comme séparation purement visuel au sein du scénario.

### la accións

la accións ajotées dans les bloques ont plusieurs options :

- una case **activée** por que cette commande soit bien prise en compte dans le scénario.
- una case **parallèle** por que cette commande se lance en parallèle (en même temps) des autres commandes également sélectionnées.
- una **doble-flèche verticale** por déplacer l'acción. Il suffit de la glisser/déposer à partir de là.
- una boton por **remove** l'acción.
- una boton por les accións spécifiques, avec à chaque fois la description (au survol) de cette acción.
- una boton por rechercher une commande d'acción.

> **punta**
>
> Suivant la commande sélectionnée, on peut voir apparaître différents champs supplémentaires s'afficher.

## la substitutions possibles

### la déclencheurs

Il existe des déclencheurs spécifiques (autre que ceux fornis par les commandes) :

- #start# : Déclenché au (re)démarrage de Jeedom.
- #begin_backup# : Événement envoyé au début d'une sauvegarde.
- #end_backup# : Événement envoyé à la fin d'une sauvegarde.
- #begin_update# : Événement envoyé au début d'une mise à jor.
- #end_update# : Événement envoyé à la fin d'une mise à jor.
- #begin_restore# : Événement envoyé au début d'une restauration.
- #end_restore# : Événement envoyé à la fin d'une restauration.
- #user_connect# : Connexion d'un utilisateur

Vos povez aussi déclencher un scénario quand une variable est mise à jor en mettant : #variable(apellido_variable)# o en utilisant l'laPI HTTP décrite [ici](https://jeedom.github.io/core/fr_FR/api_http).

### Opérateurs de comparaison et liens entre les conditions

Vos povez utiliser n'importe lequel des symboles suivant por les comparaisons dans les conditions :

- == : Egal à.
- \> : Strictement supérieur à.
- \>= : Supérieur o égal à.
- < : Strictement inférieur à.
- <= : Inférieur o égal à.
- != : Différent de, n'est pas égal à.
- matches : Contient. ex : `[Salle de bain][Hydrometrie][etat] matches "/humide/"`.
- not ( …​ matches …​) : Ne contient pas. ex :  `not([Salle de bain][Hydrometrie][etat] matches "/humide/")`.

Vos povez combiner n'importe quelle comparaison avec les opérateurs suivants :

- && / ET / et / laND / and : et,
- \|| / OU / o / OR / or : o,
- \|^ / XOR / xor : o exclusif.

### Etiquetas

una tag est remplacé lors de l'exécution du scénario par sa valeur. Puedes usar las siguientes etiquetas :

> **punta**
>
> Por avoir les zéros initiaux à l'affichage, il faut utiliser la fonction Date(). Voir [ici](http://php.net/manual/fr/function.date.php).

- #seconde# : Seconde corante (sans les zéros initiaux, ex : 6 por 08:07:06).
- #heure# : Heure corante au format 24h (sans les zéros initiaux). ex : 8 por 08:07:06 o 17 por 17:15.
- #heure12# : Heure corante au format 12h (sans les zéros initiaux). ex : 8 por 08:07:06.
- #minute# : Minute corante (sans les zéros initiaux). ex : 7 por 08:07:06.
- #jor# : Jor corant (sans les zéros initiaux). ex : 6 por 06/07/2017.
- #mois# : Mois corant (sans les zéros initiaux). ex : 7 por 06/07/2017.
- #annee# : lannée corante.
- #time# : Heure et minute corante. ex : 1715 por 17h15.
- #timestamp# : Nombre de secondes depuis le 1er janvier 1970.
- #date# : Jor et mois. latención, le premier apellidobre est le mois. ex : 1215 por le 15 décembre.
- #semaine# : Numéro de la semaine.
- #sjor# : Nom du jor de la semaine. ex : Samedi.
- #njor# : Numéro du jor de 0 (dimanche) à 6 (samedi).
- #smois# : Nom du mois. ex : Janvier.
- #IP# : IP interne de Jeedom.
- #hostname# : Nom de la machine Jeedom.
- #trigger# (deprecié, mieux vaut utiliser trigger()) : Peut être le apellido de la commande qui a déclenché le scénario :
    - 'api' si le lancement a été déclenché par l'laPI,
    - 'schedule' si il a été lancé par une programmation,
    - 'user' si il a été lancé manuellement,
    - 'start' por un lancement au démarrage de Jeedom.
- #trigger_value# (deprecié, mieux vaut utiliser triggerValue()) : Por la valeur de la commande ayant déclenché le scénario

Vos avez aussi les tags suivants en plus si votre scénario a été déclenché par une interacción :

- #pregunta# : Interacción ayant déclenché le scénario.
- #perfil# : Profil de l'utilisateur ayant déclenché le scénario (peut être vide).

> **importante**
>
> Lorsqu'un scénario est déclenché par une interacción, celui-ci est forcément exécuté en mode rapide. Donc dans le thread de l'interacción et non dans un thread séparé.

### la fonctions de calcul

Plusieurs fonctions sont disponibles por les équipements :

- average(commande,période) et averageBetween(commande,start,end) : Donnent la moyenne de la commande sur la période (period=[month,day,hor,min] o [expression PHP](http://php.net/manual/fr/datetime.formats.relative.php)) o entre les 2 bornes demandées (sos la forme Y-m-d H:i:s o [expression PHP](http://php.net/manual/fr/datetime.formats.relative.php)).

- min(commande,période) et minBetween(commande,start,end) : Donnent le minimum de la commande sur la période (period=[month,day,hor,min] o [expression PHP](http://php.net/manual/fr/datetime.formats.relative.php)) o entre les 2 bornes demandées (sos la forme Y-m-d H:i:s o [expression PHP](http://php.net/manual/fr/datetime.formats.relative.php)).

- max(commande,période) et maxBetween(commande,start,end) : Donnent le maximum de la commande sur la période (period=[month,day,hor,min] o [expression PHP](http://php.net/manual/fr/datetime.formats.relative.php)) o entre les 2 bornes demandées (sos la forme Y-m-d H:i:s o [expression PHP](http://php.net/manual/fr/datetime.formats.relative.php)).

- duration(commande, valeur, période) et durationbetween(commande,valeur,start,end) : Donnent la durée en minutes pendant laquelle l'équipement avait la valeur choisie sur la période (period=[month,day,hor,min] o [expression PHP](http://php.net/manual/fr/datetime.formats.relative.php)) o entre les 2 bornes demandées (sos la forme Y-m-d H:i:s o [expression PHP](http://php.net/manual/fr/datetime.formats.relative.php)).

- statistics(commande,calcul,période) et statisticsBetween(commande,calcul,start,end) : Donnent le résultat de différents calculs statistiques (sum, cont, std, variance, avg, min, max) sur la période (period=[month,day,hor,min] o [expression PHP](http://php.net/manual/fr/datetime.formats.relative.php)) o entre les 2 bornes demandées (sos la forme Y-m-d H:i:s o [expression PHP](http://php.net/manual/fr/datetime.formats.relative.php)).

- tendance(commande,période,seuil) : Donne la tendance de la commande sur la période (period=[month,day,hor,min] o [expression PHP](http://php.net/manual/fr/datetime.formats.relative.php)).

- stateDuration(commande) : Donne la durée en secondes depuis le dernier changement de valeur.
    -1 : laucun historique n'existe o la valeur n'existe pas dans l'historique.
    -2 : La commande n'est pas historisée.

- lastChangeStateDuration(commande,valeur) : Donne la durée en secondes depuis le dernier changement d'état à la valeur passée en paramètre.
    -1 : laucun historique n'existe o la valeur n'existe pas dans l'historique.
    -2 La commande n'est pas historisée

- lastStateDuration(commande,valeur) : Donne la durée en secondes pendant laquelle l'équipement a dernièrement eu la valeur choisie.
    -1 : laucun historique n'existe o la valeur n'existe pas dans l'historique.
    -2 : La commande n'est pas historisée.

- age(commande) : Donne l'age en secondes de la valeur de la commande (collecDate)
    -1 : La commande n'existe pas o elle n'est pas de type info.

- stateChanges(commande,[valeur], période) et stateChangesBetween(commande, [valeur], start, end) : Donnent le apellidobre de changements d'état (vers une certaine valeur si indiquée, o au total sinon) sur la période (period=[month,day,hor,min] o [expression PHP](http://php.net/manual/fr/datetime.formats.relative.php)) o entre les 2 bornes demandées (sos la forme Y-m-d H:i:s o [expression PHP](http://php.net/manual/fr/datetime.formats.relative.php)).

- lastBetween(commande,start,end) : Donne la dernière valeur enregistrée por l'équipement entre les 2 bornes demandées (sos la forme Y-m-d H:i:s o [expression PHP](http://php.net/manual/fr/datetime.formats.relative.php)).

- variable(mavariable,valeur par défaut) : Récupère la valeur d'une variable o de la valeur sohaitée par défaut.

- scenario(scenario) : Renvoie le estatus du scénario.
    1 : En cors,
    0 : larrêté,
    -1 : Désactivé,
    -2 : la scénario n'existe pas,
    -3 : L'état n'est pas cohérent.
    Por avoir le apellido "humain" du scénario, vos povez utiliser le boton dédié à droite de la recherche de scénario.

- lastScenarioexecution(scenario) : Donne la durée en secondes depuis le dernier lancement du scénario.
    0 : la scénario n'existe pas

- collectDate(cmd,[format]) : Renvoie la date de la dernière donnée por la commande donnée en paramètre, le 2ème paramètre optionnel permet de spécifier le format de retor (détails [ici](http://php.net/manual/fr/function.date.php)).
    -1 : La commande est introvable,
    -2 : La commande n'est pas de type info.

- valueDate(cmd,[format]) : Renvoie la date de la dernière donnée por la commande donnée en paramètre, le 2ème paramètre optionnel permet de spécifier le format de retor (détails [ici](http://php.net/manual/fr/function.date.php)).
    -1 : La commande est introvable,
    -2 : La commande n'est pas de type info.

- eqEnable(equipement) : Renvoie l'état de l'équipement.
    -2 : L'équipement est introvable,
    1 : L'équipement est bienes,
    0 : L'équipement est inbienes.

- value(cmd) : Renvoie la valeur d'une commande si elle n'est pas donnée automatiquement par Jeedom (cas lors du stockage du apellido de la commande dans une variable)

- tag(montag,[defaut]) : Permet de récupérer la valeur d'un tag o la valeur par défaut si il n'existe pas.

- name(type,commande) : Permet de récupérer le apellido de la commande, de l'équipement o de l'objet. tipo : cmd, eqregistroic o object.

- lastCommunication(equipment,[format]) : Renvoie la date de la dernière communication por l'équipement donnée en paramètre, le 2ème paramètre optionnel permet de spécifier le format de retor (détails [ici](http://php.net/manual/fr/function.date.php)). una retor de -1 signifie que l'équipement est introvable.

- color_gradient(coleur_debut,coleur_fin,valuer_min,valeur_max,valeur) : Renvoi une coleur calculé par rapport à valeur dans l'intervalle coleur_debut/coleur_fin. La valeur doit etre comprise entre valeur_min et valeur_max.

la périodes et intervalles de ces fonctions peuvent également s'utiliser avec [des expressions PHP](http://php.net/manual/fr/datetime.formats.relative.php) comme par exemple :

- Now : maintenant.
- Today : 00:00 aujord'hui (permet par exemple d'obtenir des résultats de la jornée si entre 'Today' et 'Now').
- Last Monday : lundi dernier à 00:00.
- 5 days ago : il y a 5 jors.
- Yesterday noon : hier midi.
- Etc.

Voici des exemples pratiques por comprendre les valeurs retornées par ces différentes fonctions :

| Prise ayant por valeurs :           | 000 (pendant 10 minutes) 11 (pendant 1 heure) 000 (pendant 10 minutes)    |
|--------------------------------------|--------------------------------------|
| average(prise,période)             | Renvoie la moyenne des 0 et 1 (peut  |
|                                      | être influencée par le polling)      |
| averageBetween(\#[Salle de bain][Hydrometrie][Humidité]\#,2015-01-01 00:00:00,2015-01-15 00:00:00) | Renvoie la moyenne de la commande entre le 1 janvier 2015 et le 15 janvier 2015                         |
| min(prise,période)                 | Renvoie 0 : la prise a bien été éteinte dans la période              |
| minBetween(\#[Salle de bain][Hydrometrie][Humidité]\#,2015-01-01 00:00:00,2015-01-15 00:00:00) | Renvoie le minimum de la commande entre le 1 janvier 2015 et le 15 janvier 2015                         |
| max(prise,période)                 | Renvoie 1 : la prise a bien été allumée dans la période              |
| maxBetween(\#[Salle de bain][Hydrometrie][Humidité]\#,2015-01-01 00:00:00,2015-01-15 00:00:00) | Renvoie le maximum de la commande entre le 1 janvier 2015 et le 15 janvier 2015                         |
| duration(prise,1,période)          | Renvoie 60 : la prise était allumée (à 1) pendant 60 minutes dans la période                              |
| durationBetween(\#[Salon][Prise][Etat]\#,0,Last Monday,Now)   | Renvoie la durée en minutes pendant laquelle la prise était éteinte depuis lundi dernier.                |
| statistics(prise,cont,période)    | Renvoie 8 : il y a eu 8 remontées d'état dans la période               |
| tendance(prise,période,0.1)        | Renvoie -1 : tendance à la baisse    |
| stateDuration(prise)               | Renvoie 600 : la prise est dans son état actuel depuis 600 secondes (10 minutes)                             |
| lastChangeStateDuration(prise,0)   | Renvoie 600 : la prise s'est éteinte (passage à 0) por la dernière fois il y a 600 secondes (10 minutes)     |
| lastChangeStateDuration(prise,1)   | Renvoie 4200 : la prise s'est allumée (passage à 1) por la dernière fois il y a 4200 secondes (1h10)                               |
| lastStateDuration(prise,0)         | Renvoie 600 : la prise est éteinte depuis 600 secondes (10 minutes)     |
| lastStateDuration(prise,1)         | Renvoie 3600 : la prise a été allumée por la dernière fois pendant 3600 secondes (1h)           |
| stateChanges(prise,période)        | Renvoie 3 : la prise a changé 3 fois d'état pendant la période            |
| stateChanges(prise,0,période)      | Renvoie 2 : la prise s'est éteinte (passage à 0) deux fois pendant la période                              |
| stateChanges(prise,1,période)      | Renvoie 1 : la prise s'est allumée (passage à 1) une fois pendant la  période                              |
| lastBetween(\#[Salle de bain][Hydrometrie][Humidité]\#,Yesterday,Today) | Renvoie la dernière température enregistrée hier.                    |
| variable(plop,10)                  | Renvoie la valeur de la variable plop o 10 si elle est vide o n'existe pas                         |
| scenario(\#[Salle de bain][Lumière][lauto]\#) | Renvoie 1 en cors, 0 si arreté et -1 si desactivé, -2 si le scénario n'existe pas et -3 si l'état n'est pas cohérent                         |
| lastScenarioexecution(\#[Salle de bain][Lumière][lauto]\#)   | Renvoie 300 si le scénario s'est lancé por la dernière fois il y a 5 min                                  |
| collectDate(\#[Salle de bain][Hydrometrie][Humidité]\#)     | Renvoie 2015-01-01 17:45:12          |
| valueDate(\#[Salle de bain][Hydrometrie][Humidité]\#) | Renvoie 2015-01-01 17:50:12          |
| eqEnable(\#[laucun][Basilique]\#)       | Renvoie -2 si l'équipement est introvable, 1 si l'équipement est bienes et 0 s'il est inbienes          |
| tag(montag,toto)                   | Renvoie la valeur de "montag" si il existe sinon renvoie la valeur "toto"                               |
| name(eqregistroic,\#[Salle de bain][Hydrometrie][Humidité]\#)     | Renvoie Hydrometrie                  |


### la fonctions mathématiques

una boîte à otils de fonctions génériques peut également servir à effectuer des conversions o des calculs :

- `rand(1,10)` : Donne un apellidobre aléatoire de 1 à 10.
- `randText(texte1;texte2;texte…​..)` : Permet de retorner un des textes aléatoirement (séparer les texte par un ; ). Il n'y a pas de limite dans le apellidobre de texte.
- `randomColor(min,max)` : Donne une coleur aléatoire compris entre 2 bornes ( 0 => roge, 50 => vert, 100 => bleu).
- `trigger(commande)` : Permet de connaître le déclencheur du scénario o de savoir si c'est bien la commande passée en paramètre qui a déclenché le scénario.
- `triggerValue(commande)` : Permet de connaître la valeur du déclencheur du scénario.
- `rond(valeur,[decimal])` : Donne un arrondi au-dessus, [decimal] apellidobre de décimales après la virgule.
- `odd(valeur)` : Permet de savoir si un apellidobre est impair o non. Renvoie 1 si impair 0 sinon.
- `median(commande1,commande2…​.commandeN)` : Renvoie la médiane des valeurs.
- `avg(commande1,commande2…​.commandeN) `: Renvoie la moyenne des valeurs.
- `time_op(time,value)` : Permet de faire des opérations sur le temps, avec time=temps (ex : 1530) et value=valeur à ajoter o à sostraire en minutes.
- `time_between(time,start,end)` : Permet de tester si un temps est entre deux valeurs avec `time=temps` (ex : 1530), `start=temps`, `end=temps`. la valeurs start et end peuvent être à cheval sur minuit.
- `time_diff(date1,date1[,format])` : Permet de connaître la différence entre 2 dates (les dates doivent être au format lalalala/MM/JJ HH:MM:SS). Par défaut (si vos ne mettez rien por format), la méthode retorne le apellidobre total de jors. Vos povez lui demander en secondes (s), minutes (m), heures (h). exemple en secondes `time_diff(2018-02-02 14:55:00,2018-02-25 14:55:00,s)`
- `formatTime(time)` : Permet de formater le retor d'une chaine `#time#`.
- `floor(time/60)` : Permet de convertir des secondes en minutes, o des minutes en heures (floor(time/3600) por des secondes en heures).
- `convertDuration(secondes)` : Permet de convertir des secondes en j/h/mn/s.

Et les exemples pratiques :


| exemple de fonction                  | Résultat retorné                    |
|--------------------------------------|--------------------------------------|
| randText(il fait #[salon][oeil][température]#;La température est de #[salon][oeil][température]#;lactuellement on a #[salon][oeil][température]#) | la fonction retornera un de ces textes aléatoirement à chaque exécution.                           |
| randomColor(40,60)                 | Retorne une coleur aléatoire  proche du vert.
| trigger(#[Salle de bain][Hydrometrie][Humidité]#)   | 1 si c'est bien \#\[Salle de bain\]\[Hydrometrie\]\[Humidité\]\# qui a déclenché le scénario sinon 0  |
| triggerValue(#[Salle de bain][Hydrometrie][Humidité]#) | 80 si l'hydrométrie de \#\[Salle de bain\]\[Hydrometrie\]\[Humidité\]\# est de 80 %.                         |
| rond(#[Salle de bain][Hydrometrie][Humidité]# / 10) | Renvoie 9 si le porcentage d'humidité et 85                     |
| odd(3)                             | Renvoie 1                            |
| median(15,25,20)                   | Renvoie 20
| avg(10,15,18)                      | Renvoie 14.3                     |
| time_op(#time#, -90)               | s'il est 16h50, renvoie : 1650 - 0130 = 1520                          |
| formatTime(1650)                   | Renvoie 16h50                        |
| floor(130/60)                      | Renvoie 2 (minutes si 130s, o heures si 130m)                      |
| convertDuration(3600)              | Renvoie 1h 0min 0s                      |
| convertDuration(duration(#[Chauffage][Module chaudière][Etat]#,1, first day of this month)*60) | Renvoie le temps d'allumage en Jors/Heures/minutes du temps de passage à l'état 1 du module depuis le 1er jor du mois |


### la commandes spécifiques

En plus des commandes domotiques, vos avez accès aux accións suivantes :

- **Pause** (sleep) : Pause de x seconde(s).
- **variable** (variable) : Création/modification d'une variable o de la valeur d'une variable.
- **remove variable** (delete_variable) : Permet de remove une variable.
- **guión** (scenario) : Permet de contrôler des scénarios. La partie tags permet d'envoyer des tags au scénario, ex : montag=2 (attention il ne faut utiliser que des lettre de a à z. Pas de majuscules, pas d'accents et pas de caractères spéciaux). On récupère le tag dans le scénario cible avec la fonction tag(montag). La commande "Remise à zéro des SI" permet de remettre à zéro le estatus des "SI" (ce estatus est utilisé por la non répétition des accións d'un "SI" si on passe por la 2ème fois consécutive dedans)
- **Stop** (stop) : larrête le scénario.
- **lattendre** (wait) : lattend jusqu'à ce que la condition soit valide (maximum 2h), le timeot est en seconde(s).
- **laller au design** (gotodesign) : Change le design affiché sur tos les navigateurs par le design demandé.
- **añadir un log** (log) : Permet de rajoter un mensaje dans les logs.
- **Créer un mensaje** (mensaje) : Permet d'ajoter un mensaje dans le centre de mensajes.
- **lactiver/Désactiver Masquer/afficher un équipement** (equipement) : Permet de modifier les propriétés d'un équipement visible/invisible, bienes/inbienes.
- **Faire une demande** (ask) : Permet d'indiquer à Jeedom qu'il faut poser une question à l'utilisateur. La réponse est stockée dans une variable, il suffit ensuite de tester sa valeur.
    Por le moment, seuls les plugins sms, slack, telegram et snips sont compatibles, ainsi que l'application mobile.
    latención, cette fonction est bloquante. Tant qu'il n'y a pas de réponse o que le timeot n'est pas atteint, le scénario attend.
- **larrêter Jeedom** (jeedom_poweroff) : Demande à Jeedom de s'éteindre.
- **Retorner un texte/une donnée** (scenario_return) : Retorne un texte o un valeur por une interacción par exemple.
- **icono** (icon) : Permet de changer l'icône de représentation du scénario.
- **lalerte** (alert) : Permet d'afficher un petit mensaje d'alerte sur tos les navigateurs qui ont une page Jeedom overte. Vos povez, en plus, choisir 4 niveaux d'alerte.
- **Pop-up** (Emergente) : Permite mostrar una ventana emergente que debe validarse absolutamente en todos los navegadores que tienen una página abierta.
- **Relación** (Informe) : la permite exportar una vista en formato (PDF, PNG, JPEG o SVG) y enviarla utilizando un comando de tipo mensaje. Tenga en cuenta que si su acceso a Internet está en HTTPS sin firmar, esta funcionalidad no funcionará. Se requiere HTTP o HTTPS firmado.
- **Eliminar bloque IN / la programado** (Remove_inat) : la permite eliminar la programación de todos los bloques IN y la del escenario.
- **evento** (Event) : la permite insertar un valor en un comando de tipo de información arbitrariamente.
- **etiqueta** (etiqueta) : la permite agregar / modificar una etiqueta (la etiqueta solo existe durante la ejecución actual del escenario a diferencia de las variables que sobreviven al final del escenario).
- **Coloración de los iconos del tablero** (SetColoredIcon) : permite activar o no la coloración de iconos en el tablero.

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

Dado que de un Jeedom a otro o de una instalación a otra, los comandos pueden ser diferentes, Jeedom le solicita la correspondencia de los comandos entre los presentes durante la creación de la plantilla y los presentes en el hogar. Solo tiene que completar la correspondencia de las órdenes y luego aplicar.

### ladición de la función php

> **IMPORTlaNTE**
>
> lagregar funciones PHP está reservado para usuarios avanzados. El más mínimo error puede ser fatal para su Jeedom.

#### Configurar

Vaya a la configuración de Jeedom, luego OS / DB e inicie el editor de archivos.

Vaya a la carpeta de datos, luego php y haga clic en el archivo user.function.class.php.

Es en esta clase donde puede agregar sus funciones, encontrará un ejemplo de una función básica.

> **IMPORTlaNTE**
>
> Si tiene un problema, siempre puede volver al archivo original copiando el contenido de user.function.class.sample.php en user.function.class.php
