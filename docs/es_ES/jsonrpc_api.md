Aquí está la documentación sobre los métodos API. Lo primero de todo aquí
Las especificaciones (JSON RPC 2.0) :
<http://www.jsonrpc.org/specification>

El acceso a la API es a través de la URL: URL * \ * _JEEDOM / core / api / jeeApi.php

Diverso
======

de ping
----

Devuelve pong, para probar la comunicación con Jeedom

version
-------

Devuelve la versión de Jeedom

datetime 
--------

Devuelve la fecha y hora Jeedom en microsegundos

config API
==========

Config :: byKey
-------------

Devuelve un valor de configuración.

Parámetros :

-   clave de cadena: el valor de configuración de tecla para volver

-   cadena Plugin (opcional), el valor de configuración del plugin

-   string predeterminado: (opcional) Valor de retorno si no existe la clave
    no

Config :: save
------------

Recibe un valor de configuración

Configuraciones :

-   valor de cadena: Valor que debe registrarse

-   cadena de clave: clave para el valor de configuración para grabar

-   cadena Plugin (opcional), el valor de configuración del plugin
    registro

JSON API Evento
==============

event::changes 
--------------

Devuelve lista de cambios desde la fecha y hora como un parámetro
(Debe estar en microsegundos). También responderá en el
fecha y hora actuales Jeedom (reutilización para la consulta siguiente)

Parámetros:

-   int datetime

JSON API plug-in
===============

Plugin :: listPlugin
------------------

Devuelve una lista de todos los plugins

Parámetros:

-   activateOnly int = 0 (sólo devuelve la lista de plug-ins habilitados)

-   orderByCaterogy int = 0 (devuelve la lista de plugins ordenados
    por categoría)

JSON API de objetos
==============

object::all 
-----------

Devuelve una lista de todos los objetos

object::full 
------------

Devuelve una lista de todos los objetos, con cada objeto de todo
instalaciones y equipos para cada uno de todos los mandos y la
declaraciones de éstos (por comandos de información de tipo)

:: fullById objeto
----------------

Devuelve un objeto con todas sus instalaciones y equipos para cada
todos los mandos y las declaraciones de éstos (por
comandos de información de tipo)

Parámetros:

-   int id

object::byId 
------------

Devuelve el objeto especificado

Parámetros:

-   int id

object::fullById 
----------------

Devuelve un objeto, instalaciones y equipos para cada todo
órdenes y las declaraciones de cellse que (para los comandos de tipo
info)

object::save 
------------

Devuelve el objeto especificado

Parámetros:

-   id int (en blanco si se trata de una creación)

-   string name

-   int father\_id = null

-   int isVisible = 0

-   int position

-   array configuration

-   array display

JSON API Resumen
================

summary::global 
---------------

Respaldar el resumen general de parámetro clave pasado

Parámetros:

-   string key : clave (opcional) del resumen deseada, si está vacío entonces Jeedom
    hace referencia a la síntesis de todas las llaves

summary::byId 
-------------

Devuelve resumen para el identificador de objeto

Parámetros:

-   int id : id de objeto

-   string key : (opcional), clave para el resumen deseado, si está vacío, entonces Jeedom
    enviará el resumen de todas las claves

JSON API EqLogic
================

eqLogic::all 
------------

Devuelve una lista de todos los equipos

eqLogic :: fullById
-----------------

Devuelve un equipo y sus controles y las declaraciones de éstos
(Para los comandos de información de tipo)

eqLogic :: BYID
-------------

Devuelve el equipo especificado

Parámetros:

-   int id

eqLogic :: byType
---------------

Volver todo el equipo que pertenece al tipo (plugin) especificado

Parámetros:

-   string type

eqLogic :: byObjectId
-------------------

Devolver todo el material que pertenece al objeto especificado

Parámetros:

-   int objeto \ _id

eqLogic :: byTypeAndId
--------------------

Reenvío de un conjunto de equipos en función de la configuración. El regreso
será de la matriz de forma (⇒array 'eqType1' ( 'id'⇒ ...' ⇒ cmds
Array (....)) ⇒array 'eqType2' ( 'id'⇒ ...' cmds ⇒ matriz (....)) ...., ⇒ id1
array ( 'id'⇒ ...' 'cmds ⇒ matriz (....)) ⇒ matriz ID2 (' id'⇒ ... ⇒ '' cmds
Array (....)) ..)

Parámetros:

-   tipos de cadena \ [\] = array eqType de servicios e instalaciones

-   int \ [\] id = personalización ID tabla deseada

eqLogic :: save
-------------

Devolver el material grabado / creado

Configuraciones:

-   id int (en blanco si se trata de una creación)

-   eqType cadena \ _name (tipo de equipo de guión, virtuales ...)

-   nombre de la cadena

-   logicalId cadena = ''

-   int objeto \ _id = null

-   int eqReal \ _id = null

-   int = 0 isVisible

-   int = 0 isEnable

-   configuración de la matriz

-   int tiempo de espera

-   Categoría gama

JSON API Cmd
============

:: todas cmd
--------

Devuelve una lista de todos los comandos

cmd :: BYID
---------

Devuelve el comando especificado

Configuraciones:

-   int id

cmd :: byEqLogicId
----------------

Devolver todos los comandos que pertenecen al equipo especificado

Configuraciones:

-   eqLogic int \ _id

cmd :: ExecCmd
------------

Ejecuta el comando especificado

Configuraciones:

-   int id: Identificación de un comando o una imagen de identificación si se desea ejecutar
    más control de repente

-   \ [\ Opciones] lista de opciones de comandos (en función del tipo y
    ordenamiento subtipo)

cmd :: getStatistique
-------------------

Devuelve las estadísticas de orden (no estoy seguro del mercado
comandos de información de tipo y historizar)

Configuraciones:

-   int id

-   cadena horaInicio: fecha de inicio para el cálculo de las estadísticas

-   fecha de finalización para el cálculo de las estadísticas: cadena endTime

cmd :: getTendance
----------------

Devuelve la tendencia en el control (sólo funciona en los pedidos
Escribir información y historizar)

Configuraciones:

-   int id

-   cadena horaInicio: fecha de inicio para el cálculo de la tendencia

-   cadena endTime: fecha de finalización para el cálculo de la tendencia

cmd :: getHistory
---------------

Devuelve la historia de la orden (sólo funciona en órdenes
Escribir información y historizar)

Configuraciones:

-   int id

-   cadena horaInicio: fecha de inicio de la historia

-   cadena endTime: fecha de fin de la historia

cmd :: save
---------

Devuelve el objeto especificado

Configuraciones:

-   id int (en blanco si se trata de una creación)

-   nombre de la cadena

-   logicalId cuerdas

-   eqType cuerdas

-   orden de las cuerdas

-   tipo de cadena

-   subType cuerdas

-   eqLogic int \ _id

-   int = 0 isHistorized

-   unidad de la serie = ''

-   configuración de la matriz

-   array plantilla

-   gama de visualización

-   array html

-   valor int = null

-   int isVisible = 1

-   alerta gama

Escenario JSON API
=================

:: todas escenario
-------------

Devuelve una lista de todos los escenarios

:: escenario BYID
--------------

Devuelve el escenario especificado

Configuraciones:

-   int id

:: escenario de exportación
----------------

Devuelve el escenario de exportación y el nombre humano de la secuencia de comandos

Configuraciones:

-   int id

:: escenario de importación
----------------

Le permite importar un escenario.

Configuraciones:

-   int id: Identificación del escenario en que la importación (vacío si la creación)

-   humanName cadena: nombre del escenario humano (vacío si se crea)

-   gama de importación: escenario (de la exportación Campos escenario :: exportación)

:: escenario ChangeState
---------------------

Cambiar el estado del escenario especificado.

Configuraciones:

-   int id

-   Estado cadena: \ [Ejecutar, detener, activar, desactivar \]

JSON API de registro
============

log :: get
--------

Se utiliza para recuperar un registro

Configuraciones:

-   registro de cadena: nombre de registro para recuperar

-   inicio frase: número de línea en la que para empezar a leer

-   cadena nbLine: número de línea para recuperar

log :: lista
---------

Se utiliza para recuperar la lista de registro Jeedom

Configuraciones:

-   filtro de cadena (opcional) de filtro en el nombre de los registros para recuperar

de registro vacío ::
----------

Permite registro vacío

Configuraciones:

-   registro de cadena: Nombre del registro de vacío

log :: remove
-----------

eliminar registro

Configuraciones:

-   registro de cadena: nombre del registro se ha eliminado

almacén de datos API JSON (variable)
=============================

:: byTypeLinkIdKey almacén de datos
--------------------------

Obtener el valor de una variable almacenada en el almacén de datos

Configuraciones:

-   Tipo de cadena: tipo del valor almacenado (por escenarios
    este escenario)

-   linkID id: -1 para el global (valor para escenarios de incumplimiento
    o el id del escenario)

-   clave de cadena: nombre del valor

almacén de datos :: save
---------------

Almacena el valor de una variable en el almacén de datos

Configuraciones:

-   Tipo de cadena: tipo del valor almacenado (por escenarios
    este escenario)

-   linkID id: -1 para el global (valor para escenarios de incumplimiento
    o el id del escenario)

-   clave de cadena: nombre del valor

-   valor mixta: Valor que debe registrarse

Mensaje API JSON
================

Mensaje :: todas
------------

Devuelve una lista de todos los mensajes

Mensaje :: removeAll
------------------

Eliminar todos los mensajes

Interacción JSON API
====================

Interactuar :: tryToReply
--------------------

Intenta hacer coincidir la demanda con la interacción, corre
acción y responde en consecuencia

Configuraciones:

-   consulta (frase de la solicitud)

-   responder int \ _cmd = NULL: identificador del control que se utilizará para satisfacer,
    si no, entonces especifique Jeedom que devolver la respuesta en el JSON

:: todas interactQuery
------------------

Envío de la lista completa de todas las interacciones

Sistema API JSON
===============

jeedom :: cese
------------

detiene Jeedom

jeedom :: reinicio
--------------

reinicia Jeedom

jeedom :: Isok
------------

Vamos a averiguar si el estado general de Jeedom está bien

jeedom :: update
--------------

Inicia una actualización de Jeedom

:: jeedom copia de seguridad
--------------

Inicia una copia de seguridad Jeedom

jeedom :: getUsbMapping
---------------------

Lista de puertos USB y los nombres clave USB conectados anteriores

JSON API plug-in
===============

:: plug-in instalado
---------------

Instalar / Actualización de un plugin dada

Configuraciones:

-   Plugin de cadena \ _id: nombre del plugin (nombre lógico)

Plugin :: remove
--------------

La eliminación de un complemento específico

Configuraciones:

-   Plugin de cadena \ _id: nombre del plugin (nombre lógico)

Plugin :: dependancyInfo
----------------------

La información de referencia sobre el estado de las dependencias de plugins

Configuraciones:

-   Plugin de cadena \ _id: nombre del plugin (nombre lógico)

Plugin :: dependancyInstall
-------------------------

Forzar la instalación de dependencias de plugins

Configuraciones:

-   Plugin de cadena \ _id: nombre del plugin (nombre lógico)

Plugin :: deamonInfo
------------------

Información de referencia sobre el estado complemento demonio

Configuraciones:

-   Plugin de cadena \ _id: nombre del plugin (nombre lógico)

Plugin :: deamonStart
-------------------

Forzar el inicio daemon

Configuraciones:

-   Plugin de cadena \ _id: nombre del plugin (nombre lógico)

Plugin :: deamonStop
------------------

Forzar la parada del diablo

Configuraciones:

-   Plugin de cadena \ _id: nombre del plugin (nombre lógico)

Plugin :: deamonChangeAutoMode
----------------------------

Cambia el modo de administración de demonio

Configuraciones:

-   Plugin de cadena \ _id: nombre del plugin (nombre lógico)

-   int modo: 1 para automático, 0 para el manual

Actualización de la API JSON
===============

:: actualización de todos
-----------

Devolver la lista de todos los componentes instalados, y su versión
información asociada

:: actualización checkUpdate
-------------------

Le permite comprobar si hay actualizaciones

:: update actualización
--------------

Deja la actualización Jeedom y todos los plugins

Red API JSON
================

:: red restartDns
-------------------

Fuerza (re) iniciar el Jeedom DNS

:: red stopDns
----------------

Forzar la detención de la Jeedom DNS

:: red dnsRun
---------------

Devuelve el estado de la Jeedom DNS

JSON Ejemplos API
=================

He aquí un ejemplo del uso de la API. Para el ejemplo siguiente
Yo uso [esta clase
php] (https://github.com/jeedom/core/blob/stable/core/class/jsonrpcClient.class.php)
que simplifica el uso de la API.

Recuperar la lista de los objetos:

``` {.php}
$jsonrpc = new jsonrpcClient('#URL_JEEDOM#/core/api/jeeApi.php', #API_KEY#);
if($jsonrpc->sendRequest('object::all', array())){
    print_r($jsonrpc->getResult());
}else{
    echo $jsonrpc->getError();
}
```

Ejecución de una instrucción (con el título de la opción y el mensaje)

``` {.php}
$jsonrpc = new jsonrpcClient('#URL_JEEDOM#/core/api/jeeApi.php', #API_KEY#);
if($jsonrpc->sendRequest('cmd::execCmd', array('id' => #cmd_id#, 'options' => array('title' => 'Coucou', 'message' => 'Ca marche')))){
    echo 'OK';
}else{
    echo $jsonrpc->getError();
}
```

El API es capaz de ser utilizado con otros idiomas (Just Post
en una sola página)
