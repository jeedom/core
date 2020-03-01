Aquí hay documentación sobre métodos API. 

Primero, aquí están las especificaciones (JSON RPC 2.0) :
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

config::Guardar
------------

Guarda un valor de configuración

Configuraciones :

-   valor de cadena : valor para grabar

-   clave de cuerda : clave de valor de configuración para guardar

-   complemento de cadena : (opcional), complemento del valor de configuración para guardar

API de eventoos JSON
==============

evento::intercambio
--------------

Devuelve la listaa de cambios desde la fecha y hora pasada en el parámetro (debe estar en microsegundos). También tendrá en la respuesta la fecha y hora actual de Jeedom (que se reutilizará para la próxima consulta)

Configuraciones :

-   int fecha y hora

API de complementos JSON
===============

Plugin::listaPlugin
------------------

Devuelve la listaa de todos los complementos

Configuraciones :

-   int enableOnly = 0 (solo devuelve la listaa de complementos activados)

-   int orderByCaterogy = 0 (devuelve la listaa de complementos ordenados por categoría)

API JSON de objetos
==============

jeeObject::todos
-----------

Devuelve la listaa de todos los objetos.

jeeObject::completo
------------

Devuelve la listaa de todos los objetos, para cada objeto todo su equipo y para cada equipo todos sus comandos, así como sus estados (para comandos de tipo de información)

jeeObject::completoById
----------------

Devuelve un objeto con todo su equipo y para cada equipo todos sus comandos y sus estados (para comandos de tipo de información)

Configuraciones :

-   int id

jeeObject::BYID
------------

Devuelve el objeto especificado

Configuraciones:

-   int id

jeeObject::completoById
----------------

Devuelve un objeto, su equipo y para cada equipo todos sus comandos, así como los estados de las celdas (para comandos de tipo de información)

jeeObject::Guardar
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

Devuelve el resumen total de la clave pasada en el parámetro

Configuraciones:

-   clave de cuerda : (opcional), clave del resumen deseado, si está vacío, Jeedom le envía el resumen de todas las claves

resumen::BYID
-------------

Devuelve el resumen de la identificación del objeto.

Configuraciones:

-   int id : ID de objeto

-   clave de cuerda : (opcional), clave del resumen deseado, si está vacío, Jeedom le envía el resumen de todas las claves

API JSON EqLogic
================

eqLogic::todos
------------

Devuelve la listaa de todos los equipos.

eqLogic::completoById
-----------------

Devuelve el equipo y sus comandos, así como sus estados (para comandos de tipo de información)

Configuraciones:

-   int id

eqLogic::BYID
-------------

Devuelve el equipo especificado.

Configuraciones:

-   int id

eqLogic::byType
---------------

Devuelve todos los equipos que pertenecen al tipo especificado (complemento)

Configuraciones:

-   tipo de cadena

eqLogic::byObjectId
-------------------

Devuelve todo el equipo que pertenece al objeto especificado.

Configuraciones:

-   int objeto \ _id

eqLogic::byTypeAndId
--------------------

Devuelve una tabla de equipos según los parámetros.. 

El retorno será de la matriz de forma (&#39;eqType1&#39; ⇒array (&#39;id&#39;⇒ ...,&#39; cmds &#39;⇒
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

-   int id : ID de comando o matriz de ID si desea ejecutar múltiples comandos a la vez
    
-   \ [opciones \] Lista de opciones de comando (depende del tipo y subtipo del comando)

cmd::getStatistique
-------------------

Estadísticas de devoluciones en el pedido (solo funciona con información y pedidos históricos)

Configuraciones:

-   int id

-   inicio de cadenaTime : fecha de inicio del cálculo de estadísticas

-   string endTime : fecha de finalización del cálculo de estadísticas

cmd::getTendance
----------------

Devuelve la tendencia en el pedido (solo funciona con información y pedidos históricos)

Configuraciones:

-   int id

-   inicio de cadenaTime : fecha de inicio del cálculo de tendencia

-   string endTime : fecha de finalización del cálculo de tendencia

cmd::getHistory
---------------

Devuelve el historial de pedidos (solo funciona con información y pedidos históricos)

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

Devuelve la exportaciónación del eguión, así como el * nombre humano * del eguión

Configuraciones:

-   int id

guión::importación
----------------

Le permite importaciónar un eguión.

Configuraciones:

-   int id : ID del eguión en el que importaciónar (vacío si se crea)

-   string humanName : *nombre humano * del eguión (vacío si creación)

-   importaciónación de matriz : eguión (del campo eguión de exportaciónación::exportaciónación)

guión::ChangeState
---------------------

Cambia el estado del eguión especificado..

Configuraciones:

-   int id

-   estado de cadena: \ [Ejecutar, detener, habilitar, deshabilitar \]

API de registro JSON
============

registro::get
--------

Le permite recuperar un registro

Configuraciones:

-   registro de cadena : nombre del registro a recuperar 

-   inicio de cadena : número de línea en el que comenzar a leer

-   string nbLine : cantidad de líneas para recuperar 

registro::añadir
--------

Permite escribir en un registro

Configuraciones:

-   registro de cadena : nombre del registro a recuperar 

-   tipo de cadena : tipo de registro (depuración, información, advertencia, error)

-   mensaje de cadena : mensaje de texto para escribir

-   registro de cadenaicalId : Id. lógico del mensaje generado


registro::lista
---------

Obtenga la listaa de registros de Jeedom

Configuraciones:

-   filtro de cadena : (opcional) filtre el nombre de los registros para recuperar 

registro::vaciar
----------

Vaciar un registro

Configuraciones:

-   registro de cadena : nombre del registro para vaciar

registro::remove
-----------

Le permite eliminar un registro

Configuraciones:

-   registro de cadena : nombre de registro para eliminar

API de almacén de datos JSON (variable)
=============================

almacén de datos::byTypeLinkIdKey
--------------------------

Obtener el valor de una variable almacenada en el almacén de datos

Configuraciones:

-   tipo de cadena : tipo de valor almacenado (para eguións es eguión)
    
-   id linkId : -1 para el total (valor para los eguións predeterminados o el id del eguión)
    
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

mensaje::añadir
--------

Permite escribir en un registro

Configuraciones:

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

Intente hacer coincidir una solicitud con una interacción, ejecute la acción y responda en consecuencia

Configuraciones:

-   consulta (frase de solicitud)

-   int reply \ _cmd = NULL : ID de comando para usar para responder,
    si no especifica, entonces Jeedom le devuelve la respuesta en el json

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

Jeedom::getUsbMapde ping
---------------------

Lista de puertos USB y nombres de llaves USB conectadas

API de complementos JSON
===============

Plugin::instalar
---------------

Instalación / Actualización de un complemento dado

Configuraciones:

-   int Plugin \ _id (opcional) : ID del complemento
-   registro de cadenaicalId (opcional) : nombre del complemento (nombre lógico)

Plugin::remove
--------------

Eliminación de un complemento dado

Configuraciones:

-   int Plugin \ _id (opcional) : ID del complemento
-   registro de cadenaicalId (opcional) : nombre del complemento (nombre lógico)

Plugin::dependancyInfo
----------------------

Devuelve información sobre el estado de dependencia del complemento

Configuraciones:

-   int Plugin \ _id (opcional) : ID del complemento
-   registro de cadenaicalId (opcional) : nombre del complemento (nombre lógico)

Plugin::dependancyInsttodos
-------------------------

Forzar la instalación de dependencias de complementos

Configuraciones:

-   int Plugin \ _id (opcional) : ID del complemento
-   registro de cadenaicalId (opcional) : nombre del complemento (nombre lógico)

Plugin::deamonInfo
------------------

Devuelve información sobre el estado del demonio del complemento.

Configuraciones:

-   int Plugin \ _id (opcional) : ID del complemento
-   registro de cadenaicalId (opcional) : nombre del complemento (nombre lógico)

Plugin::deamonStart
-------------------

Forzar al demonio a comenzar

Configuraciones:

-   int Plugin \ _id (opcional) : ID del complemento
-   registro de cadenaicalId (opcional) : nombre del complemento (nombre lógico)

Plugin::deamonStop
------------------

Fuerza demonio parada

Configuraciones:

-   int Plugin \ _id (opcional) : ID del complemento
-   registro de cadenaicalId (opcional) : nombre del complemento (nombre lógico)

Plugin::deamonChangeAutoMode
----------------------------

Cambiar el modo de gestión del demonio

Configuraciones:

-   int Plugin \ _id (opcional) : ID del complemento
-   registro de cadenaicalId (opcional) : nombre del complemento (nombre lógico)
-   modo int : 1 para automático, 0 para manual

API de actualización de JSON
===============

actualización::todos
-----------

Volver a la listaa de todos los componentes instalados, sus versiónes y la información asociada.

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
Yo uso [esta clase de PHP] (https://github.com/Jeedom/core/blob/stable/core/class/jsonrpcClient.class.php)
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

Por supuesto, la API se puede usar con otros idiomas (solo una publicación en una página)
