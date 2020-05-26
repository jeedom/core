Aquí hay documentación sobre métodos API. 

Primero, aquí están las especificaciones (JSON RPC 2.0) :
<http://www.jsonrpc.org/specification>

El acceso a la API es a través de la url : *URL\_JEEDOM*/core/api/jeeApi.php

Divers
======

ping
----

Regrese pong, pruebe la comunicación con Jeedom

version
-------

Devuelve la versión de Jeedom

datetime
--------

Devuelve la fecha y hora de Jeedom en microsegundos

API de configuración
==========

config::byKey
-------------

Devuelve un valor de configuración.

Configuraciones :

-   clave de cuerda : clave de valor de configuración para devolver

-   complemento de cadena : (opcional), complemento de valor de configuración

-   cadena por defecto : (opcional), valor a devolver si la clave no existe

config::save
------------

Guarda un valor de configuración

Configuraciones :

-   valor de cadena : valor para grabar

-   clave de cuerda : clave de valor de configuración para guardar

-   complemento de cadena : (opcional), complemento del valor de configuración para guardar

API de eventos JSON
==============

event::changes
--------------

Devuelve la lista de cambios desde la fecha y hora pasada en el parámetro (debe estar en microsegundos). También tendrá en la respuesta la fecha y hora actual de Jeedom (que se reutilizará para la siguiente consulta)

Configuraciones :

-   int datetime

API de complementos JSON
===============

plugin::listPlugin
------------------

Devuelve la lista de todos los complementos

Configuraciones :

-   int enableOnly = 0 (solo devuelve la lista de complementos activados)

-   int orderByCaterogy = 0 (devuelve la lista de complementos ordenados por categoría)

API JSON de objetos
==============

jeeObject::all
-----------

Devuelve la lista de todos los objetos

jeeObject::full
------------

Devuelve la lista de todos los objetos, con cada objeto todo su equipo y para cada equipo todos sus comandos, así como sus estados (para comandos de tipo info)

jeeObject::fullById
----------------

Devuelve un objeto con todo su equipo y para cada equipo todos sus comandos, así como sus estados (para comandos de tipo info)

Configuraciones :

-   int id

jeeObject::byId
------------

Devuelve el objeto especificado

Configuraciones:

-   int id

jeeObject::fullById
----------------

Devuelve un objeto, su equipo y para cada equipo todos sus comandos, así como los estados de las celdas (para comandos de tipo de información)

jeeObject::save
------------

Devuelve el objeto especificado

Configuraciones:

-   Identificación del int (vacía si es una creación)

-   nombre de cadena

-   int father\_id = null

-   int isVisible = 0

-   posición int

-   configuración de matriz

-   pantalla de matriz

API de resumen JSON
================

summary::global
---------------

Devuelve el resumen global de la clave pasada en el parámetro

Configuraciones:

-   clave de cuerda : (opcional), clave del resumen deseado, si está vacío, entonces Jeedom devuelve el resumen de todas las claves

summary::byId
-------------

Devuelve el resumen de la identificación del objeto

Configuraciones:

-   int id : ID de objeto

-   clave de cuerda : (opcional), clave del resumen deseado, si está vacío, entonces Jeedom devuelve el resumen de todas las claves

API JSON EqLogic
================

eqLogic::all
------------

Devuelve la lista de todos los equipos

eqLogic::fullById
-----------------

Devuelve un dispositivo y sus comandos, así como sus estados (para comandos de tipo de información)

Configuraciones:

-   int id

eqLogic::byId
-------------

Devuelve el equipo especificado

Configuraciones:

-   int id

eqLogic::byType
---------------

Devuelve todos los equipos que pertenecen al tipo especificado (complemento)

Configuraciones:

-   tipo de cadena

eqLogic::byObjectId
-------------------

Devuelve todo el equipo que pertenece al objeto especificado

Configuraciones:

-   int objeto\_id

eqLogic::byTypeAndId
--------------------

Devuelve una tabla de equipos según los parámetros. 

El retorno será de la matriz de forma (&#39;eqType1&#39; ⇒array (&#39;id&#39;⇒ ...,&#39; cmds &#39;⇒
array (....)), &#39;eqType2&#39; ⇒array (&#39;id&#39;⇒ ...,&#39; cmds &#39;⇒ array (....)).,id1 ⇒
array (&#39;id&#39;⇒ ...,&#39; cmds &#39;⇒ array (....)), id2 ⇒ array (&#39; id&#39;⇒ ..., &#39;cmds&#39; ⇒
array(…​.))..)

Configuraciones:

-   string \ [\] eqType = tabla de los tipos de equipos requeridos

-   int \ [\] id = tabla de ID de equipos personalizados deseados

eqLogic::save
-------------

Devuelve el equipo registrado / creado

Configuraciones:

-   Identificación del int (vacía si es una creación)

-   string eqType\_name (tipo de script, equipo virtual)

-   nombre de cadena

-   string logicalId = ''

-   int objeto\_id = nulo

-   int eqReal\_id = nulo

-   int isVisible = 0

-   int isEnable = 0

-   configuración de matriz

-   int timeout

-   categoría de matriz

JSON Cmd API
============

cmd::all
--------

Devuelve la lista de todos los comandos

cmd::byId
---------

Devuelve el comando especificado

Configuraciones:

-   int id

cmd::byEqLogicId
----------------

Devuelve todos los pedidos que pertenecen al equipo especificado

Configuraciones:

-   int eqLogic\_id

cmd::execCmd
------------

Ejecuta el comando especificado

Configuraciones:

-   int id : ID de comando o matriz de ID si desea ejecutar múltiples comandos a la vez
    
-   \ [opciones \] Lista de opciones de comando (depende del tipo y subtipo del comando)

cmd::getStatistique
-------------------

Devuelve las estadísticas del pedido (solo funciona con información y pedidos históricos)

Configuraciones:

-   int id

-   string startTime : fecha de inicio del cálculo de estadísticas

-   string endTime : fecha de finalización del cálculo de estadísticas

cmd::getTendance
----------------

Devuelve la tendencia en el pedido (solo funciona en pedidos de información e históricos)

Configuraciones:

-   int id

-   string startTime : fecha de inicio del cálculo de tendencia

-   string endTime : fecha de finalización del cálculo de tendencia

cmd::getHistory
---------------

Devuelve el historial de pedidos (solo funciona en pedidos de información e históricos)

Configuraciones:

-   int id

-   string startTime : fecha de inicio de la historia

-   string endTime : fecha de finalización de la historia

cmd::save
---------

Devuelve el objeto especificado

Configuraciones:

-   Identificación del int (vacía si es una creación)

-   nombre de cadena

-   string logicalId

-   string eqType

-   orden de cuerda

-   tipo de cadena

-   subtipo de cadena

-   int eqLogic\_id

-   int isHistorized = 0

-   unidad de cuerda = ''

-   configuración de matriz

-   plantilla de matriz

-   pantalla de matriz

-   matriz html

-   valor int = nulo

-   int isVisible = 1

-   alerta de matriz

cmd::event
-------------------

Le permite enviar un valor a un pedido

Configuraciones:

-   int id

-   valor de cadena : valeur

-   cadena de fecha y hora : (opcional) valor de fecha y hora

API de escenario JSON
=================

scenario::all
-------------

Devuelve la lista de todos los escenarios

scenario::byId
--------------

Devuelve el escenario especificado

Configuraciones:

-   int id

scenario::export
----------------

Devuelve la exportación del escenario, así como la *nombre humano* del guión

Configuraciones:

-   int id

scenario::import
----------------

Le permite importar un escenario.

Configuraciones:

-   int id : ID del escenario en el que importar (vacío si la creación)

-   string humanName : *nombre humano* del escenario (vacío si creación)

-   importación de matriz : escenario (del campo escenario de exportación::export)

scenario::changeState
---------------------

Cambia el estado del escenario especificado.

Configuraciones:

-   int id

-   estado de cadena: \ [Ejecutar, detener, habilitar, deshabilitar \]

API de registro JSON
============

log::get
--------

Le permite recuperar un registro

Configuraciones:

-   registro de cadena : nombre del registro para recuperar 

-   inicio de cadena : número de línea en el que comenzar a leer

-   string nbLine : cantidad de líneas para recuperar 

log::add
--------

Permite escribir en un registro

Configuraciones:

-   registro de cadena : nombre del registro para recuperar 

-   tipo de cadena : tipo de registro (depuración, información, advertencia, error)

-   mensaje de cadena : mensaje de texto para escribir

-   string logicalId : Id. lógico del mensaje generado


log::list
---------

Obtenga la lista de registros de Jeedom

Configuraciones:

-   filtro de cadena : (opcional) filtrar en el nombre de los registros para recuperar 

log::empty
----------

Vaciar un registro

Configuraciones:

-   registro de cadena : nombre del registro para vaciar

log::remove
-----------

Le permite eliminar un registro

Configuraciones:

-   registro de cadena : nombre de registro para eliminar

API de almacén de datos JSON (variable)
=============================

datastore::byTypeLinkIdKey
--------------------------

Obtener el valor de una variable almacenada en el almacén de datos

Configuraciones:

-   tipo de cadena : tipo de valor almacenado (para escenarios es escenario)
    
-   id linkId : -1 para el global (valor para los escenarios predeterminados o el id del escenario)
    
-   clave de cuerda : nombre del valor

datastore::save
---------------

Almacena el valor de una variable en el almacén de datos

Configuraciones:

-   tipo de cadena : tipo de valor almacenado (para escenarios
    es escenario)

-   id linkId : -1 para global (valor para escenarios predeterminados,
    o el id del escenario)

-   clave de cuerda : nombre del valor

-   valor mixto : valor para grabar

API de mensajes JSON
================

message::all
------------

Devuelve la lista de todos los mensajes

message::add
--------

Permite escribir en un registro

Configuraciones:

-   tipo de cadena : tipo de registro (depuración, información, advertencia, error)

-   mensaje de cadena : message

-   acción de cuerda : action

-   string logicalId : logicalId

message::removeAll
------------------

Eliminar todos los mensajes

API de interacción JSON
====================

interact::tryToReply
--------------------

Intente hacer coincidir una solicitud con una interacción, ejecute la acción y responda en consecuencia

Configuraciones:

-   consulta (frase de solicitud)

-   int reply\_cmd = NULL : ID de comando para usar para responder,
    si no especifica, entonces Jeedom le devuelve la respuesta en el json

interactQuery::all
------------------

Devuelve la lista completa de todas las interacciones

API del sistema JSON
===============

jeedom::halt
------------

Stop Jeedom

jeedom::reboot
--------------

Reiniciar Jeedom

jeedom::isOk
------------

Le permite saber si el estado global de Jeedom está bien

jeedom::update
--------------

Vamos a lanzar una actualización de Jeedom

jeedom::backup
--------------

Le permite iniciar una copia de seguridad de Jeedom

jeedom::getUsbMapping
---------------------

Lista de puertos USB y nombres de llaves USB conectadas

API de complementos JSON
===============

plugin::install
---------------

Instalación / Actualización de un complemento dado

Configuraciones:

-   int plugin\_id (opcional) : ID del complemento
-   string logicalId (opcional) : nombre del complemento (nombre lógico)

plugin::remove
--------------

Eliminación de un complemento dado

Configuraciones:

-   int plugin\_id (opcional) : ID del complemento
-   string logicalId (opcional) : nombre del complemento (nombre lógico)

plugin::dependancyInfo
----------------------

Devuelve información sobre el estado de dependencia del complemento

Configuraciones:

-   int plugin\_id (opcional) : ID del complemento
-   string logicalId (opcional) : nombre del complemento (nombre lógico)

plugin::dependancyInstall
-------------------------

Forzar la instalación de dependencias de complementos

Configuraciones:

-   int plugin\_id (opcional) : ID del complemento
-   string logicalId (opcional) : nombre del complemento (nombre lógico)

plugin::deamonInfo
------------------

Devuelve información sobre el estado del demonio del complemento

Configuraciones:

-   int plugin\_id (opcional) : ID del complemento
-   string logicalId (opcional) : nombre del complemento (nombre lógico)

plugin::deamonStart
-------------------

Forzar al demonio a comenzar

Configuraciones:

-   int plugin\_id (opcional) : ID del complemento
-   string logicalId (opcional) : nombre del complemento (nombre lógico)

plugin::deamonStop
------------------

Fuerza demonio parada

Configuraciones:

-   int plugin\_id (opcional) : ID del complemento
-   string logicalId (opcional) : nombre del complemento (nombre lógico)

plugin::deamonChangeAutoMode
----------------------------

Cambiar el modo de gestión del demonio

Configuraciones:

-   int plugin\_id (opcional) : ID del complemento
-   string logicalId (opcional) : nombre del complemento (nombre lógico)
-   modo int : 1 para automático, 0 para manual

API de actualización de JSON
===============

update::all
-----------

Volver a la lista de todos los componentes instalados, sus versiones y la información asociada

update::checkUpdate
-------------------

Le permite buscar actualizaciones

update::update
--------------

Le permite actualizar Jeedom y todos los complementos

update::doUpdate
--------------

Configuraciones:

-   int plugin\_id (opcional) : ID del complemento
-   string logicalId (opcional) : nombre del complemento (nombre lógico)

API de red JSON
================

network::restartDns
-------------------

Forzar el (re) inicio del DNS de Jeedom

network::stopDns
----------------

Obliga al DNS Jeedom a detenerse

network::dnsRun
---------------

Devolver el estado DNS de Jeedom

Ejemplos de API JSON
=================

Aquí hay un ejemplo del uso de la API. Para el siguiente ejemplo
Yo uso [esta clase de php](https://github.com/jeedom/core/blob/release/core/class/jsonrpcClient.class.php)
lo que simplifica el uso de la API.

Recuperando la lista de objetos :

`` `{.php}
$jsonrpc = new jsonrpcClient('#URL_JEEDOM#/core/api/jeeApi.php', #API_KEY#);
if ($ jsonrpc-&gt; sendRequest ( &#39;jeeObject::todo ', matriz())){
    print_r ($ jsonrpc-&gt; getResult ());
}else{
    echo $ jsonrpc-&gt; getError ();
}
`` ''

Ejecución de una orden (con la opción de un título y un mensaje)

`` `{.php}
$jsonrpc = new jsonrpcClient('#URL_JEEDOM#/core/api/jeeApi.php', #API_KEY#);
if ($ jsonrpc-&gt; sendRequest ( &#39;cmd::execCmd ', array (' id' => #cmd_id#, 'opciones '=> array (' title '=>' Cuckoo ',' message '=>' Funciona')))){
    echo &#39;OK&#39;;
}else{
    echo $ jsonrpc-&gt; getError ();
}
`` ''

Por supuesto, la API se puede usar con otros idiomas (solo una publicación en una página)
