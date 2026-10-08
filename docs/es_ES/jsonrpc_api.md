Aquí tienes la documentación sobre los métodos de la API.

En primer lugar, estas son las especificaciones (JSON RPC 2.0):
<http://www.jsonrpc.org/specification>

El acceso a la API se realiza a través de la URL: *URL\_JEEDOM*/core/api/jeeApi.php

A continuación se muestra un ejemplo de configuración de un objeto JSON que se puede utilizar en el cuerpo de una solicitud realizada por un agente HTTP:
``` json
{
    "jsonrpc": "2.0",
    "id": "007",
    "method": "event::changes",
    "params": {
        "apikey": "{{apikey}}",
        "datetime": "0"
    }
}
```

Varios
======

ping
----

Devuelve «pong», permite comprobar la comunicación con Jeedom

versión
-------

Muestra la versión de Jeedom

fecha y hora
--------

Convierte la fecha y hora de Jeedom en microsegundos

Configuración de la API
==========

config::byKey
-------------

Devuelve un valor de configuración.

Parámetros JSON:

-   cadena clave: clave del valor de configuración que se debe devolver

-   plugin de cadena: (opcional), plugin del valor de configuración

-   cadena por defecto: (opcional), valor que se debe devolver si la clave no existe

config::save
------------

Guarda un valor de configuración

Parámetros JSON:

-   valor de cadena: valor que se va a registrar

-   cadena clave: clave del valor de configuración que se va a guardar

-   plugin de cadena: (opcional), plugin del valor de configuración que se va a guardar

API de eventos JSON
==============

evento::cambios
--------------

Devuelve la lista de cambios desde la fecha y hora pasada como parámetro (debe estar en microsegundos). En la respuesta también aparecerá la fecha y hora actual de Jeedom (que se puede reutilizar para la siguiente consulta).

Parámetros JSON:

-   int datetime

Complemento API JSON
===============

plugin::listPlugin
------------------

Muestra la lista de todos los complementos

Parámetros JSON:

-   int activateOnly = 0 (solo devuelve la lista de complementos activados)

-   int orderByCategory = 0 (devuelve la lista de complementos ordenados por categoría)

Objeto JSON de la API
==============

jeeObject::all
-----------

Devuelve la lista de todos los objetos

jeeObject::full
------------

Devuelve la lista de todos los objetos, con todos los dispositivos de cada objeto y, para cada dispositivo, todos sus comandos, así como el estado de estos (para los comandos de tipo «info»).

jeeObject::fullById
----------------

Devuelve un objeto con todos sus dispositivos y, para cada dispositivo, todos sus comandos, así como el estado de los mismos (para los comandos de tipo «info»).

Parámetros JSON:

-   int id

jeeObject::byId
------------

Devuelve el objeto especificado

Parámetros:

-   int id

jeeObject::fullById
----------------

Devuelve un objeto, sus dispositivos y, para cada dispositivo, todos sus comandos, así como sus estados (para los comandos de tipo «info»).

jeeObject::save
------------

Devuelve el objeto especificado

Parámetros:

-   int id (vacío si se trata de una creación)

-   nombre de la cadena

-   int padre\_id = null

-   int intVisible = 0

-   posición interna

-   configuración de la matriz

-   pantalla de matriz

Resumen de la API JSON
================

resumen::global
---------------

Devuelve el resumen global correspondiente a la clave pasada como parámetro

Parámetros:

-   cadena clave: (opcional), clave del resumen deseado; si se deja en blanco, Jeedom te enviará el resumen para todas las claves

resumen::porId
-------------

Devuelve el resumen del objeto con el ID

Parámetros:

-   int id: identificador del objeto

-   cadena clave: (opcional), clave del resumen deseado; si se deja en blanco, Jeedom te enviará el resumen para todas las claves

API JSON de EqLogic
================

eqLogic::all
------------

Devuelve la lista de todos los dispositivos

eqLogic::fullById
-----------------

Devuelve un dispositivo y sus comandos, así como el estado de estos (para los comandos de tipo «info»).

Parámetros:

-   int id

eqLogic::byId
-------------

Devuelve el equipo especificado

Parámetros:

-   int id

eqLogic::byType
---------------

Devuelve todos los dispositivos que pertenecen al tipo (plugin) especificado

Parámetros:

-   tipo de cadena

eqLogic::byObjectId
-------------------

Devuelve todos los dispositivos que pertenecen al objeto especificado

Parámetros:

-   int object_id

eqLogic::porTipoEID
--------------------

Devuelve una tabla de equipos en función de los parámetros.

El resultado tendrá el siguiente formato: array('eqType1' ⇒ array( 'id' ⇒…​, 'cmds' ⇒
array(…​.)), 'eqType2' ⇒ array( 'id' ⇒ …​, 'cmds' ⇒ array(…​.))…​., id1 ⇒
array( 'id'⇒…​,'cmds' ⇒ array(…​.)),id2 ⇒ array( 'id'⇒…​,'cmds' ⇒
array(…​.))..)

Parámetros:

-   cadena\[\] eqType = tabla de los tipos de equipos deseados

-   int\[\] id = tabla de los ID de los dispositivos personalizados deseados

eqLogic::save
-------------

Muestra los dispositivos registrados/creados

Parámetros:

-   int id (vacío si se trata de una creación)

-   cadena eqType\_name (tipo de dispositivo: script, virtual…​)

-   nombre de la cadena

-   string logicalId = ''

-   int object_id = null

-   int eqReal\_id = null

-   int intVisible = 0

-   int isEnable = 0

-   configuración de la matriz

-   tiempo de espera de la interfaz

-   categoría de matriz

API JSON Cmd
============

cmd::all
--------

Devuelve la lista de todos los comandos

cmd::byId
---------

Devuelve el comando especificado

Parámetros:

-   int id

cmd::byEqLogicId
----------------

Devuelve todos los comandos pertenecientes al equipo especificado

Parámetros:

-   int eqLogic\_id

cmd::execCmd
------------

Ejecuta el comando especificado

Parámetros:

-   int id: identificador de un comando o matriz de identificadores si quieres ejecutar varios comandos a la vez

-   \[options\] Lista de opciones del comando (depende del tipo y del subtipo del comando)

cmd::getEstadística
-------------------

Devuelve las estadísticas sobre el pedido (solo funciona con pedidos de tipo «info» y historizados)

Parámetros:

-   int id

-   cadena startTime: fecha de inicio del cálculo de las estadísticas

-   cadena «endTime»: fecha de finalización del cálculo de las estadísticas

cmd::getTendencia
----------------

Invierte la tendencia de la orden (solo funciona con órdenes de tipo «info» e historizadas)

Parámetros:

-   int id

-   cadena startTime: fecha de inicio del cálculo de la tendencia

-   cadena «endTime»: fecha de finalización del cálculo de la tendencia

cmd::getHistory
---------------

Devuelve el historial del comando (solo funciona con comandos de tipo «info» y los que se han registrado en el historial)

Parámetros:

-   int id

-   cadena startTime: fecha de inicio del historial

-   cadena «endTime»: fecha de finalización del historial

cmd::save
---------

Devuelve el objeto especificado

Parámetros:

-   int id (vacío si se trata de una creación)

-   nombre de la cadena

-   cadena «logicalId»

-   cadena eqType

-   orden de las cadenas

-   tipo de cadena

-   subtipo de cadena

-   int eqLogic\_id

-   int isHistorized = 0

-   cadena unite = ''

-   configuración de la matriz

-   plantilla de matriz

-   pantalla matricial

-   matriz html

-   int value = null

-   int intVisible = 1

-   alerta de matriz

cmd::event
-------------------

Permite enviar un valor a un comando

Parámetros:

-   int id

-   valor de cadena: valor

-   cadena de fecha y hora: (opcional) fecha y hora del valor

Escenario de la API JSON
=================

escenario::todos
-------------

Devuelve la lista de todos los escenarios

scenario::byId
--------------

Devuelve el escenario especificado

Parámetros:

-   int id

escenario::exportar
----------------

Devuelve la exportación del escenario, así como el *nombre descriptivo* del escenario

Parámetros:

-   int id

escenario::importar
----------------

Permite importar un escenario.

Parámetros:

-   int id: ID del escenario al que se va a importar (vacío si se trata de una creación)

-   cadena humanName: *nombre humano* del escenario (vacío si se está creando)

-   importación de array: escenario (procedente del campo «export» de «scenario::export»)

scenario::changeState
---------------------

Cambia el estado del escenario especificado.

Parámetros:

-   int id

-   cadena de estado: \[run,stop,enable,disable\]

Registro JSON de la API
============

log::get
--------

Permite recuperar un registro

Parámetros:

-   cadena de registro: nombre del registro que se va a recuperar

-   string start: número de línea en la que comenzar la lectura

-   cadena nbLine: número de líneas que se van a recuperar

log::add
--------

Permite escribir en un registro

Parámetros:

-   cadena de registro: nombre del registro que se va a recuperar

-   tipo de cadena: tipo de registro (debug, info, warning, error)

-   cadena de mensaje: mensaje de texto que hay que escribir

-   cadena logicalId: logicalId del mensaje generado


log::list
---------

Permite recuperar la lista de registros de Jeedom

Parámetros:

-   cadena de filtro: (opcional) filtro por el nombre de los registros que se quieren recuperar

log::empty
----------

Permite vaciar un registro

Parámetros:

-   cadena de registro: nombre del registro que se va a vaciar

log::remove
-----------

Permite eliminar un registro

Parámetros:

-   cadena de registro: nombre del registro que se va a eliminar

API del almacén de datos JSON (variable)
=============================

datastore::byTypeLinkIdKey
--------------------------

Recupera el valor de una variable almacenada en el almacén de datos

Parámetros:

-   tipo de cadena: tipo del valor almacenado (en el caso de los escenarios, es «scenario»)

-   id linkId: -1 para el global (valor para los escenarios por defecto, o el id del escenario)

-   cadena clave: nombre del valor

datastore::save
---------------

Guarda el valor de una variable en el almacén de datos

Parámetros:

-   tipo de cadena: tipo del valor almacenado (para los escenarios
(esto es un escenario)

-   id linkId: -1 para el global (valor predeterminado para los escenarios,
o el ID del escenario)

-   cadena clave: nombre del valor

-   valor mixto: valor que hay que registrar

Mensaje JSON de la API
================

mensaje::todos
------------

Muestra la lista de todos los mensajes

mensaje::añadir
--------

Permite escribir en un registro

Parámetros:

-   tipo de cadena: tipo de registro (debug, info, warning, error)

-   cadena de mensaje: mensaje

-   cadena de acción: acción

-   cadena logicalId: logicalId

mensaje::eliminarTodo
------------------

Eliminar todos los mensajes

Interacción con la API JSON
====================

interact::tryToReply
--------------------

Intenta asociar una solicitud con una interacción, ejecuta la acción y responde en consecuencia

Parámetros:

-   consulta (frase de búsqueda)

-   int reply_cmd = NULL: ID del comando que se va a utilizar para responder,
Si no se especifica, Jeedom te devuelve la respuesta en formato JSON

interactQuery::all
------------------

Muestra la lista completa de todas las interacciones

Sistema API JSON
===============

jeedom::halt
------------

Permite detener Jeedom

jeedom::reinicio
--------------

Permite reiniciar Jeedom

jeedom::isOk
------------

Permite saber si el estado general de Jeedom es correcto

jeedom::actualización
--------------

Permite iniciar una actualización de Jeedom

jeedom::copia de seguridad
--------------

Permite iniciar una copia de seguridad de Jeedom

jeedom::getUsbMapping
---------------------

Lista de puertos USB y nombres de las memorias USB conectadas a ellos

Complemento API JSON
===============

plugin::install
---------------

Instalación/Actualización de un complemento concreto

Parámetros:

-   int plugin\_id (opcional): identificador del complemento
-   cadena logicalId (opcional): nombre del complemento (nombre lógico)

complemento::eliminar
--------------

Eliminación de un complemento concreto

Parámetros:

-   int plugin\_id (opcional): identificador del complemento
-   cadena logicalId (opcional): nombre del complemento (nombre lógico)

plugin::dependancyInfo
----------------------

Devuelve información sobre el estado de las dependencias del complemento

Parámetros:

-   int plugin\_id (opcional): identificador del complemento
-   cadena logicalId (opcional): nombre del complemento (nombre lógico)

plugin::dependancyInstall
-------------------------

Fuerza la instalación de las dependencias del complemento

Parámetros:

-   int plugin\_id (opcional): identificador del complemento
-   cadena logicalId (opcional): nombre del complemento (nombre lógico)

plugin::deamonInfo
------------------

Muestra información sobre el estado del demonio del complemento

Parámetros:

-   int plugin\_id (opcional): identificador del complemento
-   cadena logicalId (opcional): nombre del complemento (nombre lógico)

plugin::deamonStart
-------------------

Forza el inicio del demonio

Parámetros:

-   int plugin\_id (opcional): identificador del complemento
-   cadena logicalId (opcional): nombre del complemento (nombre lógico)

complemento::deamonStop
------------------

Forza la detención del demonio

Parámetros:

-   int plugin\_id (opcional): identificador del complemento
-   cadena logicalId (opcional): nombre del complemento (nombre lógico)

plugin::deamonChangeAutoMode
----------------------------

Cambia el modo de gestión del demonio

Parámetros:

-   int plugin\_id (opcional): identificador del complemento
-   cadena logicalId (opcional): nombre del complemento (nombre lógico)
-   modo int: 1 para automático, 0 para manual

Actualización de la API JSON
===============

actualización::todo
-----------

Devuelve la lista de todos los componentes instalados, sus versiones y la información asociada

actualización::comprobarActualización
-------------------

Permite comprobar si hay actualizaciones

actualización::actualización
--------------

Permite actualizar Jeedom y todos los complementos

actualización::doUpdate
--------------

Parámetros:

-   int plugin\_id (opcional): identificador del complemento
-   cadena logicalId (opcional): nombre del complemento (nombre lógico)

Red API JSON
================

red::reiniciarDNS
-------------------

Forza el reinicio del DNS de Jeedom

red::stopDns
----------------

Forza el cierre del DNS de Jeedom

red::dnsRun
---------------

Cronología de la API JSON
===============

cronología::todo
-----------

Muestra todos los elementos de la línea de tiempo

línea de tiempo::lista de carpetas
-----------

Muestra todas las carpetas (categorías) de la línea de tiempo

línea de tiempo::por carpeta
-----------

Devuelve todos los elementos de la carpeta solicitada

Parámetros:

-   carpeta de cadenas: número de la carpeta

API JSON para usuarios
=================

usuario::todos
-------------

Devuelve la lista de todos los usuarios

usuario::guardar
---------------------

Crear o editar un usuario

Parámetros:

-   ID interno (si se trata de una edición)

-   cadena de inicio de sesión

-   contraseña de cadena

-   perfil de cadena: \[admin,user,restrict\]


Ejemplos de API JSON
=================

A continuación se muestra un ejemplo de uso de la API. Para el ejemplo que figura a continuación
utilizo [esta clase de PHP](https://github.com/jeedom/core/blob/master/core/class/jsonrpcClient.class.php)
que permite simplificar el uso de la API.

Recuperación de la lista de objetos:

``` {.php}
$jsonrpc = new jsonrpcClient('#URL_JEEDOM#/core/api/jeeApi.php', #API_KEY#);
if($jsonrpc->sendRequest('jeeObject::all', array())){
    print_r($jsonrpc->getResult());
}else{
    echo $jsonrpc->getError();
}
```

Ejecución de un comando (con un título y un mensaje opcionales)

``` {.php}
$jsonrpc = new jsonrpcClient('#URL_JEEDOM#/core/api/jeeApi.php', #API_KEY#);
if($jsonrpc->sendRequest('cmd::execCmd', array('id' => #cmd_id#, 'options' => array('title' => 'Coucou', 'message' => 'Ca marche')))){
    echo 'OK';
}else{
    echo $jsonrpc->getError();
}
```

Por supuesto, la API se puede utilizar con otros lenguajes (basta con publicar una entrada en una página).
