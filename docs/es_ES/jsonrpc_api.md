Aquí hay documentación sobre métodos API. Primero aquí está
las especificaciones (JSON RPC 2.0) :
<http://www.jsonrpc.org/specification>

El acceso a la API es a través de la url : *URL \ _JEEDOM * / core / api / jeeApi.php

Diverso
======

de ping
----

Regrese pong, pruebe la comunicación con Jeedom

versión
-------

Devuelve la versión de Jeedom

fecha y hora
--------

Devuelve la fecha y hora de Jeedom en microsegundos

API de configuración
==========

config::byKey
-------------

Devuelve un valor de configuración.

configuraciones :

-   clave de cuerda : clave de valor de configuración para devolver

-   complemento de cadena : (opcional), complemento de valor de configuración

-   cadena por defecto : (opcional), valor a devolver si la clave no existe
    no

config::Guardar
------------

Guarda un valor de configuración

configuraciones :

-   valor de cadena : valor para grabar

-   clave de cuerda : clave de valor de configuración para guardar

-   complemento de cadena : (opcional), complemento del valor de configuración para
    registro

API de eventoos JSON
==============

evento::intercambio
--------------

Devuelve la listaa de cambios desde la fecha y hora noada en el parámetro
(debe estar en microsegundos). También tendrás en la respuesta el
Fecha y hora actual de Jeedom (se reutilizará para la próxima consulta)

configuraciones :

-   int fecha y hora

API de complementos JSON
===============

plugin::listaPlugin
------------------

Devuelve la listaa de todos los complementos

configuraciones :

-   int enableOnly = 0 (solo devuelve la listaa de complementos activados)

-   int orderByCaterogy = 0 (devuelve la listaa de complementos ordenados
    por categoría)

API JSON de objetos
==============

jeeObject::todos
-----------

Devuelve la listaa de todos los objetos.

jeeObject::completo
------------

Devuelve la listaa de todos los objetos, con para cada objeto todos sus
equipo y para cada equipo todos sus comandos, así como
estados de estos (para comandos de tipo de información)

jeeObject::completoById
----------------

Devuelve un objeto con todo su equipo y para cada equipo.
todos sus comandos, así como sus estados (para
comandos de tipo de información)

configuraciones :

-   int id

jeeObject::BYID
------------

Devuelve el objeto especificado

configuraciones:

-   int id

jeeObject::completoById
----------------

Devuelve un objeto, su equipo y para cada equipo todos sus
comandos, así como los estados de las celdas (para comandos de tipo
info)

jeeObject::Guardar
------------

Devuelve el objeto especificado

configuraciones:

-   Identificación del int (vacía si es una creación)

-   nombre de cadena

-   int father \ _id = null

-   int isVisible = 0

-   posición int

-   configuración de matriz

-   panttodosa de matriz

API de resumen JSON
================

resumen::total
---------------

Devuelve el resumen total de la clave noada en el parámetro

configuraciones:

-   clave de cuerda : (opcional), clave del resumen deseado, si está vacío, entonces Jeedom
    te envía el resumen de todas las claves

resumen::BYID
-------------

Devuelve el resumen de la identificación del objeto.

configuraciones:

-   int id : ID de objeto

-   clave de cuerda : (opcional), clave del resumen deseado, si está vacío, entonces Jeedom
    te envía el resumen de todas las claves

API JSON EqLogic
================

eqLogic::todos
------------

Devuelve la listaa de todos los equipos.

eqLogic::completoById
-----------------

Devuelve el equipo y sus comandos, así como sus estados.
(para pedidos de tipo de información)

configuraciones:

-   int id

eqLogic::BYID
-------------

Devuelve el equipo especificado.

configuraciones:

-   int id

eqLogic::byType
---------------

Devuelve todos los equipos que pertenecen al tipo especificado (complemento)

configuraciones:

-   tipo de cadena

eqLogic::byObjectId
-------------------

Devuelve todo el equipo que pertenece al objeto especificado.

configuraciones:

-   int objeto \ _id

eqLogic::byTypeAndId
--------------------

Devuelve una tabla de equipos según los parámetros.. El regreso
será de la matriz de forma (&#39;eqType1&#39; ⇒array (&#39;id&#39;⇒ ...,&#39; cmds &#39;⇒
array (....)), &#39;eqType2&#39; ⇒array (&#39;id&#39;⇒ ...,&#39; cmds &#39;⇒ array (....)) ....,id1 ⇒
array (&#39;id&#39;⇒ ...,&#39; cmds &#39;⇒ array (....)), id2 ⇒ array (&#39; id&#39;⇒ ..., &#39;cmds&#39; ⇒
Array (....)) ..)

configuraciones:

-   string \ [\] eqType = tabla de los tipos de equipos requeridos

-   int \ [\] id = tabla de ID de equipos personalizados deseados

eqLogic::Guardar
-------------

Devuelve el equipo registrado / creado

configuraciones:

-   Identificación del int (vacía si es una creación)

-   string eqType \ _name (tipo de script, equipo virtual, etc.)

-   nombre de cadena

-   registro de cadenaicalId = ''

-   int objeto \ _id = nulo

-   int eqReal \ _id = nulo

-   int isVisible = 0

-   int isEnable = 0

-   configuración de matriz

-   int timeout

-   categoría de matriz

JSON Cmd API
============

cmd::todos
--------

Devuelve la listaa de todos los comandos.

cmd::BYID
---------

Devuelve el comando especificado

configuraciones:

-   int id

cmd::byEqLogicId
----------------

Devuelve todos los pedidos que pertenecen al equipo especificado.

configuraciones:

-   int eqLogic \ _id

cmd::ExecCmd
------------

Ejecuta el comando especificado

configuraciones:

-   int id : ID de un comando o matriz de ID si desea ejecutar
    múltiples pedidos a la vez

-   \ [opciones \] Lista de opciones de comando (depende del tipo y
    subtipo de comando)

cmd::getStatistique
-------------------

Devuelve estadísticas sobre el pedido (solo funciona en
información y comandos históricos)

configuraciones:

-   int id

-   inicio de cadenaTime : fecha de inicio del cálculo de estadísticas

-   string endTime : fecha de finalización del cálculo de estadísticas

cmd::getTendance
----------------

Devuelve la tendencia en el comando (solo funciona en los comandos de
información y tipo histórico)

configuraciones:

-   int id

-   inicio de cadenaTime : fecha de inicio del cálculo de tendencia

-   string endTime : fecha de finalización del cálculo de tendencia

cmd::getHistory
---------------

Devuelve el historial de comandos (solo funciona en los comandos de
información y tipo histórico)

configuraciones:

-   int id

-   inicio de cadenaTime : fecha de inicio de la historia

-   string endTime : fecha de finalización de la historia

cmd::Guardar
---------

Devuelve el objeto especificado

configuraciones:

-   Identificación del int (vacía si es una creación)

-   nombre de cadena

-   registro de cadenaicalId

-   string eqType

-   orden de cuerda

-   tipo de cadena

-   subtipo de cadena

-   int eqLogic \ _id

-   int isHistorized = 0

-   unidad de cuerda = ''

-   configuración de matriz

-   plantilla de matriz

-   panttodosa de matriz

-   matriz html

-   valor int = nulo

-   int isVisible = 1

-   alerta de matriz

cmd::evento
-------------------

Le permite enviar un valor a un pedido.

configuraciones:

-   int id

-   valor de cadena : valor

-   cadena de fecha y hora : (opcional) valor fecha y hora

API de eguión JSON
=================

guión::todos
-------------

Devuelve la listaa de todos los eguións.

guión::BYID
--------------

Devuelve el eguión especificado

configuraciones:

-   int id

guión::exportación
----------------

Devuelve la exportaciónación del eguión, así como el nombre humano del eguión.

configuraciones:

-   int id

guión::importación
----------------

Le permite importaciónar un eguión.

configuraciones:

-   int id : ID del eguión en el que importaciónar (vacío si se crea)

-   string humanName : nombre humano del eguión (vacío si creación)

-   importaciónación de matriz : eguión (del campo eguión de exportaciónación::exportaciónación)

guión::ChangeState
---------------------

Cambia el estado del eguión especificado..

configuraciones:

-   int id

-   estado de cadena: \ [Ejecutar, detener, habilitar, deshabilitar \]

API de registro JSON
============

registro::get
--------

Le permite recuperar un registro

configuraciones:

-   registro de cadena : nombre del registro para recuperar

-   inicio de cadena : número de línea en el que comenzar a leer

-   string nbLine : cantidad de líneas para recuperar

registro::añadir
--------

Permite escribir en un registro

configuraciones:

-   registro de cadena : nombre del registro para recuperar

-   tipo de cadena : tipo de registro (depuración, información, advertencia, error)

-   mensaje de cadena : mensaje de texto para escribir

-   registro de cadenaicalId : Id. lógico del mensaje generado


registro::lista
---------

Obtenga la listaa de registros de Jeedom

configuraciones:

-   filtro de cadena : (opcional) filtra el nombre de los registros para recuperar

registro::vaciar
----------

Vaciar un registro

configuraciones:

-   registro de cadena : nombre del registro para vaciar

registro::remove
-----------

Le permite eliminar un registro

configuraciones:

-   registro de cadena : nombre de registro para eliminar

API de almacén de datos JSON (variable)
=============================

almacén de datos::byTypeLinkIdKey
--------------------------

Obtener el valor de una variable almacenada en el almacén de datos

configuraciones:

-   tipo de cadena : tipo de valor almacenado (para eguións
    es eguión)

-   id linkId : -1 para total (valor para eguións predeterminados,
    o el id del eguión)

-   clave de cuerda : nombre del valor

almacén de datos::Guardar
---------------

Almacena el valor de una variable en el almacén de datos

configuraciones:

-   tipo de cadena : tipo de valor almacenado (para eguións
    es eguión)

-   id linkId : -1 para total (valor para eguións predeterminados,
    o el id del eguión)

-   clave de cuerda : nombre del valor

-   valor mixto : valor para grabar

API de mensajes JSON
================

mensaje::todos
------------

Devuelve la listaa de todos los mensajes.

mensaje::añadir
--------

Permite escribir en un registro

configuraciones:

-   tipo de cadena : tipo de registro (depuración, información, advertencia, error)

-   mensaje de cadena : mensaje

-   acción de cuerda : acción

-   registro de cadenaicalId : registroicalId

mensaje::removeAll
------------------

Eliminar todos los mensajes

API de interacción JSON
====================

Interact::tryToReply
--------------------

Intenta hacer coincidir una solicitud con una interacción, ejecuta
acción y responde en consecuencia

configuraciones:

-   consulta (frase de solicitud)

-   int reply \ _cmd = NULL : ID de comando para usar para responder,
    si no especifica, entonces Jeedom le envía la respuesta en el json

InteractQuery::todos
------------------

Devuelve la listaa completa de todas las interacciones.

API del sistema JSON
===============

jeedom::alto
------------

Stop Jeedom

jeedom::reiniciar
--------------

Reiniciar Jeedom

jeedom::Isok
------------

Le permite saber si el estado total de Jeedom está bien

jeedom::actualización
--------------

Vamos a lanzar una actualización de Jeedom

jeedom::reserva
--------------

Le permite iniciar una copia de seguridad de Jeedom

jeedom::getUsbMapde ping
---------------------

Lista de puertos USB y nombres de llaves USB conectadas

API de complemento JSON
===============

plugin::instalar
---------------

Instalación / Actualización de un complemento dado

configuraciones:

-   int plugin \ _id (opcional) : ID del complemento
-   registro de cadenaicalId (opcional) : nombre del complemento (nombre lógico)

plugin::remove
--------------

Eliminación de un complemento dado

configuraciones:

-   int plugin \ _id (opcional) : ID del complemento
-   registro de cadenaicalId (opcional) : nombre del complemento (nombre lógico)

plugin::dependancyInfo
----------------------

Devuelve información sobre el estado de las dependencias de complementos

configuraciones:

-   int plugin \ _id (opcional) : ID del complemento
-   registro de cadenaicalId (opcional) : nombre del complemento (nombre lógico)

plugin::dependancyInsttodos
-------------------------

Forzar la instalación de dependencias de complementos

configuraciones:

-   int plugin \ _id (opcional) : ID del complemento
-   registro de cadenaicalId (opcional) : nombre del complemento (nombre lógico)

plugin::deamonInfo
------------------

Devuelve información sobre el estado del demonio del complemento.

configuraciones:

-   int plugin \ _id (opcional) : ID del complemento
-   registro de cadenaicalId (opcional) : nombre del complemento (nombre lógico)

plugin::deamonStart
-------------------

Forzar al demonio a comenzar

configuraciones:

-   int plugin \ _id (opcional) : ID del complemento
-   registro de cadenaicalId (opcional) : nombre del complemento (nombre lógico)

plugin::deamonStop
------------------

Fuerza demonio parada

configuraciones:

-   int plugin \ _id (opcional) : ID del complemento
-   registro de cadenaicalId (opcional) : nombre del complemento (nombre lógico)

plugin::deamonChangeAutoMode
----------------------------

Cambiar el modo de gestión del demonio

configuraciones:

-   int plugin \ _id (opcional) : ID del complemento
-   registro de cadenaicalId (opcional) : nombre del complemento (nombre lógico)
-   modo int : 1 para automático, 0 para manual

API de actualización de JSON
===============

actualización::todos
-----------

Devuelve la listaa de todos los componentes instalados, su versión y el
información relacionada

actualización::checkUpdate
-------------------

Le permite buscar actualizaciones

actualización::actualización
--------------

Le permite actualizar Jeedom y todos los complementos

actualización::DoUpdate
--------------

configuraciones:

-   int plugin \ _id (opcional) : ID del complemento
-   registro de cadenaicalId (opcional) : nombre del complemento (nombre lógico)

API de red JSON
================

red::restartDns
-------------------

Forzar el (re) inicio del DNS de Jeedom

red::stopDns
----------------

Obliga al DNS Jeedom a detenerse

red::dnsRun
---------------

Devolver el estado DNS de Jeedom

Ejemplos de API JSON
=================

Aquí hay un ejemplo del uso de la API. Para el siguiente ejemplo
Yo uso [esta clase
php] (https://github.com/jeedom/core/blob/stable/core/class/jsonrpcClient.class.php)
lo que simplifica el uso de la API.

Recuperando la listaa de objetos :

``` {.php}
$jsonrpc = new jsonrpcClient('#URL_JEEDOM#/core/api/jeeApi.php', #API_KEY#);
if ($ jsonrpc-&gt; sendRequest ( &#39;jeeObject::todos &#39;, array ())){
    print_r ($ jsonrpc-&gt; getResult ());
}otro{
    echo $ jsonrpc-&gt; getError ();
}
```

Ejecución de una orden (con la opción de un título y un mensaje)

``` {.php}
$jsonrpc = new jsonrpcClient('#URL_JEEDOM#/core/api/jeeApi.php', #API_KEY#);
if ($ jsonrpc-&gt; sendRequest ( &#39;cmd::ExecCmd &#39;, array (&#39; id &#39;=> # cmd_id #,&#39; options &#39;=> array (&#39; title &#39;=>&#39; Cuckoo &#39;,&#39; mensaje &#39;=>&#39; Funciona &#39;)))){
    echo &#39;OK&#39;;
}otro{
    echo $ jsonrpc-&gt; getError ();
}
```

La API, por supuesto, se puede usar con otros idiomas (simplemente una publicación
en una página)
