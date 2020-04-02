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

Configuraciones :

-   clave de cuerda : clave de valor de configuración para devolver

-   complemento de cadena : (opcional), complemento de valor de configuración

-   cadena por defecto : (opcional), valor a devolver si la clave no existe
    no

config::Guardar
------------

Guarda un valor de configuración

Configuraciones :

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

Configuraciones :

-   int fecha y hora

API de complementos JSON
===============

Plugin::listaPlugin
------------------

Devuelve la listaa de todos los complementos

Configuraciones :

-   int enableOnly = 0 (solo devuelve la listaa de complementos activados)

-   int orderByCaterogy = 0 (devuelve la listaa de complementos ordenados
    por categoría)

API JSON de objetos
==============

objeto::todos
-----------

Devuelve la listaa de todos los objetos.

objeto::completo
------------

Devuelve la listaa de todos los objetos, con para cada objeto todos sus
equipo y para cada equipo todos sus comandos, así como
estados de estos (para comandos de tipo de información)

objeto::completoById
----------------

Devuelve un objeto con todo su equipo y para cada equipo.
todos sus comandos, así como sus estados (para
comandos de tipo de información)

Configuraciones :

-   int id

objeto::BYID
------------

Devuelve el objeto especificado

Configuraciones:

-   int id

objeto::completoById
----------------

Devuelve un objeto, su equipo y para cada equipo todos sus
comandos, así como los estados de las celdas (para comandos de tipo
info)

objeto::Guardar
------------

Devuelve el objeto especificado

Configuraciones:

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

Configuraciones:

-   clave de cuerda : (opcional), clave del resumen deseado, si está vacío, entonces Jeedom
    te envía el resumen de todas las claves

resumen::BYID
-------------

Devuelve el resumen de la identificación del objeto.

Configuraciones:

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

Configuraciones:

-   int id

eqLogic::BYID
-------------

Devuelve el equipo especificado.

Configuraciones:

-   int id

eqLogic::por tipo
---------------

Devuelve todos los equipos que pertenecen al tipo especificado (complemento)

Configuraciones:

-   tipo de cadena

eqLogic::porObjectId
-------------------

Devuelve todo el equipo que pertenece al objeto especificado.

Configuraciones:

-   int objeto \ _id

eqLogic::por tipoAndId
--------------------

Devuelve una tabla de equipos según los parámetros.. El regreso
será de la matriz de forma (&#39;eqType1&#39; ⇒array (&#39;id&#39;⇒ ...,&#39; cmds &#39;⇒
array (....)), &#39;eqType2&#39; ⇒array (&#39;id&#39;⇒ ...,&#39; cmds &#39;⇒ array (....)) ....,id1 ⇒
array (&#39;id&#39;⇒ ...,&#39; cmds &#39;⇒ array (....)), id2 ⇒ array (&#39; id&#39;⇒ ..., &#39;cmds&#39; ⇒
Array (....)) ..)

Configuraciones:

-   string \ [\] eqType = tabla de los tipos de equipos requeridos

-   int \ [\] id = tabla de ID de equipos personalizados deseados

eqLogic::Guardar
-------------

Devuelve el equipo registrado / creado

Configuraciones:

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

Configuraciones:

-   int id

cmd::byEqLogicId
----------------

Devuelve todos los pedidos que pertenecen al equipo especificado.

Configuraciones:

-   int eqLogic \ _id

cmd::ExecCmd
------------

Ejecuta el comando especificado

Configuraciones:

-   int id : ID de un comando o matriz de ID si desea ejecutar
    múltiples pedidos a la vez

-   \ [opciones \] Lista de opciones de comando (depende del tipo y
    subtipo de comando)

cmd::obtenerStatistics
-------------------

Devuelve estadísticas sobre el pedido (solo funciona en
información y comandos históricos)

Configuraciones:

-   int id

-   inicio de cadenaTime : fecha de inicio del cálculo de estadísticas

-   string endTime : fecha de finalización del cálculo de estadísticas

cmd::obtenerTendance
----------------

Devuelve la tendencia en el comando (solo funciona en los comandos de
información y tipo histórico)

Configuraciones:

-   int id

-   inicio de cadenaTime : fecha de inicio del cálculo de tendencia

-   string endTime : fecha de finalización del cálculo de tendencia

cmd::obtenerHistory
---------------

Devuelve el historial de comandos (solo funciona en los comandos de
información y tipo histórico)

Configuraciones:

-   int id

-   inicio de cadenaTime : fecha de inicio de la historia

-   string endTime : fecha de finalización de la historia

cmd::Guardar
---------

Devuelve el objeto especificado

Configuraciones:

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

Configuraciones:

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

Configuraciones:

-   int id

guión::exportación
----------------

Devuelve la exportaciónación del eguión, así como el nombre humano del eguión.

Configuraciones:

-   int id

guión::importación
----------------

Le permite importaciónar un eguión.

Configuraciones:

-   int id : ID del eguión en el que importaciónar (vacío si se crea)

-   string humanName : nombre humano del eguión (vacío si creación)

-   importaciónación de matriz : eguión (del campo eguión de exportaciónación::exportaciónación)

guión::ChangeState
---------------------

Cambia el estado del eguión especificado..

Configuraciones:

-   int id

-   estado de cadena: \ [Ejecutar, detener, habilitar, deshabilitar \]

API de registro JSON
============

registro::obtener
--------

Le permite recuperar un registro

Configuraciones:

-   registro de cadena : nombre del registro para recuperar

-   inicio de cadena : número de línea en el que comenzar a leer

-   string nbLine : cantidad de líneas para recuperar

registro::lista
---------

Obtenga la listaa de registros de Jeedom

Configuraciones:

-   filtro de cadena : (opcional) filtra el nombre de los registros para recuperar

registro::vaciar
----------

Vaciar un registro

Configuraciones:

-   registro de cadena : nombre del registro para vaciar

registro::quitar
-----------

Le permite eliminar un registro

Configuraciones:

-   registro de cadena : nombre de registro para eliminar

API de almacén de datos JSON (variable)
=============================

almacén de datos::por tipoLinkIdKey
--------------------------

Obtener el valor de una variable almacenada en el almacén de datos

Configuraciones:

-   tipo de cadena : tipo de valor almacenado (para eguións
    es eguión)

-   id linkId : -1 para total (valor para eguións predeterminados,
    o el id del eguión)

-   clave de cuerda : nombre del valor

almacén de datos::Guardar
---------------

Almacena el valor de una variable en el almacén de datos

Configuraciones:

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

mensaje::quitarAll
------------------

Eliminar todos los mensajes

API de interacción JSON
====================

Interact::tryToReply
--------------------

Intenta hacer coincidir una solicitud con una interacción, ejecuta
acción y responde en consecuencia

Configuraciones:

-   consulta (frase de solicitud)

-   int reply \ _cmd = NULL : ID de comando para usar para responder,
    si no especifica, entonces Jeedom le envía la respuesta en el json

InteractQuery::todos
------------------

Devuelve la listaa completa de todas las interacciones.

API del sistema JSON
===============

Jeedom::alto
------------

Stop Jeedom

Jeedom::reiniciar
--------------

Reiniciar Jeedom

Jeedom::Isok
------------

Le permite saber si el estado total de Jeedom está bien

Jeedom::actualización
--------------

Vamos a lanzar una actualización de Jeedom

Jeedom::reserva
--------------

Le permite iniciar una copia de seguridad de Jeedom

Jeedom::obtenerUsbMapde ping
---------------------

Lista de puertos USB y nombres de llaves USB conectadas

API de complementos JSON
===============

Plugin::instalar
---------------

Instalación / Actualización de un complemento dado

Configuraciones:

-   complemento de cadena \ _id : nombre del complemento (nombre lógico)

Plugin::quitar
--------------

Eliminación de un complemento dado

Configuraciones:

-   complemento de cadena \ _id : nombre del complemento (nombre lógico)

Plugin::información de dependencia
----------------------

Devuelve información sobre el estado de las dependencias de complementos

Configuraciones:

-   complemento de cadena \ _id : nombre del complemento (nombre lógico)

Plugin::dependenciaInstalar
-------------------------

Forzar la instalación de dependencias de complementos

Configuraciones:

-   complemento de cadena \ _id : nombre del complemento (nombre lógico)

Plugin::deamonInfo
------------------

Devuelve información sobre el estado del demonio del complemento.

Configuraciones:

-   complemento de cadena \ _id : nombre del complemento (nombre lógico)

Plugin::deamonStart
-------------------

Forzar al demonio a comenzar

Configuraciones:

-   complemento de cadena \ _id : nombre del complemento (nombre lógico)

Plugin::deamonStop
------------------

Fuerza demonio parada

Configuraciones:

-   complemento de cadena \ _id : nombre del complemento (nombre lógico)

Plugin::deamonChangeAutoMode
----------------------------

Cambiar el modo de gestión del demonio

Configuraciones:

-   complemento de cadena \ _id : nombre del complemento (nombre lógico)

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

Configuraciones:

-   int Plugin \ _id (opcional) : ID del complemento
-   registro de cadenaicalId (opcional) : nombre del complemento (nombre lógico)

API de red JSON
================

red::reiniciarDns
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
php] (https://github.com/Jeedom/core/blob/stable/core/class/jsonrpcClient.class.php)
lo que simplifica el uso de la API.

Recuperando la listaa de objetos :

``` {.php}
$jsonrpc = new jsonrpcClient('#URL_JEEDOM#/core/api/jeeApi.php', #API_KEY#);
if ($ jsonrpc-&gt; sendRequest (objeto&#39;::todos &#39;, array ())){
    print_r ($ jsonrpc-&gt; obtenerResult ());
}otro{
    echo $ jsonrpc-&gt; obtenerError ();
}
```

Ejecución de una orden (con la opción de un título y un mensaje)

``` {.php}
$jsonrpc = new jsonrpcClient('#URL_JEEDOM#/core/api/jeeApi.php', #API_KEY#);
if ($ jsonrpc-&gt; sendRequest ( &#39;cmd::ExecCmd &#39;, array (&#39; id &#39;=> # cmd_id #,&#39; options &#39;=> array (&#39; title &#39;=>&#39; Cuckoo &#39;,&#39; mensaje &#39;=>&#39; Funciona &#39;)))){
    echo &#39;OK&#39;;
}otro{
    echo $ jsonrpc-&gt; obtenerError ();
}
```

La API, por supuesto, se puede usar con otros idiomas (simplemente una publicación
en una página)
