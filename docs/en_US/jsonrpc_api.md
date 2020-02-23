Voici une documentation sur les méthodes de l'API. Tout d'abord voici
les spécifications (JSON RPC 2.0) :
<http://www.jsonrpc.org/specification>

L'accès à l'API se fait par l'url : *URL\_JEEDOM*/core/api/jeeApi.php

Divers
======

ping
----

Retourne pong, permet de tester la communication avec Jeedom

version
-------

Retourne la version de Jeedom

datetime
--------

Retourne le datetime de Jeedom en microsecondes

API config
==========

config::byKey
-------------

Retourne une value de configuration.

Paramètres :

-   string key : configuration value key to return

-   string plugin : (optional), configuration value plugin

-   string default : (optional), value to return if the key does not exist
    not

config::save
------------

Saves a configuration value

Paramètres :

-   string value : value to record

-   string key : configuration value key to save

-   string plugin : (optional), plugin of the configuration value to
    save

JSON Event API
==============

event::exchange
--------------

Returns the list of exchange since the datetime notsed in parameter
(must be in microseconds). You will also have in the answer the
Jeedom&#39;s current datetime (to be reused for the next query)

Paramètres :

-   int datetime

JSON Plugin API
===============

plugin::listPlugin
------------------

Returns the list of all plugins

Paramètres :

-   int activateOnly = 0 (only returns the list of activated plugins)

-   int orderByCaterogy = 0 (returns the list of sorted plugins
    by category)

Object JSON API
==============

jeeObject::all
-----------

Returns the list of all objects

jeeObject::full
------------

Returns the list of all objects, with for each object all its
equipment and for each equipment all its commands as well as
states of these (for info type commands)

jeeObject::fullById
----------------

Returns an object with all its equipment and for each equipment
all its commands as well as their states (for
info type commands)

Paramètres :

-   int id

jeeObject::BYID
------------

Returns the specified object

Paramètres:

-   int id

jeeObject::fullById
----------------

Returns an object, its equipment and for each equipment all its
commands as well as the cell states (for type commands
info)

jeeObject::save
------------

Returns the specified object

Paramètres:

-   int id (empty if it is a creation)

-   string name

-   int father \ _id = null

-   int isVisible = 0

-   int position

-   array configuration

-   array display

JSON Summary API
================

summary::global
---------------

Return the global summary for the key notsed in parameter

Paramètres:

-   string key : (optional), key of the desired summary, if empty then Jeedom
    sends you the summary for all the keys

summary::BYID
-------------

Returns the summary for the object id

Paramètres:

-   int id : object id

-   string key : (optional), key of the desired summary, if empty then Jeedom
    sends you the summary for all the keys

JSON EqLogic API
================

eqLogic::all
------------

Returns the list of all equipment

eqLogic::fullById
-----------------

Returns equipment and its commands as well as their states
(for info type orders)

Paramètres:

-   int id

eqLogic::BYID
-------------

Returns the specified equipment

Paramètres:

-   int id

eqLogic::byType
---------------

Returns all equipment belonging to the specified type (plugin)

Paramètres:

-   string type

eqLogic::byObjectId
-------------------

Returns all equipment belonging to the specified object

Paramètres:

-   int object \ _id

eqLogic::byTypeAndId
--------------------

Returns an equipment table according to the parameters. The return
will be of the form array (&#39;eqType1&#39; ⇒array (&#39;id&#39;⇒…,&#39; cmds&#39; ⇒
array (….)), &#39;eqType2&#39; ⇒array (&#39;id&#39;⇒…,&#39; cmds&#39; ⇒ array (….))…., id1 ⇒
array (&#39;id&#39;⇒…,&#39; cmds &#39;⇒ array (….)), id2 ⇒ array (&#39; id&#39;⇒…, &#39;cmds&#39; ⇒
Array (....)) ..)

Paramètres:

-   string \ [\] eqType = table of the types of equipment required

-   int \ [\] id = table of desired custom equipment IDs

eqLogic::save
-------------

Returns the registered / created equipment

Paramètres:

-   int id (empty if it is a creation)

-   string eqType \ _name (type of script, virtual equipment, etc.)

-   string name

-   string logicalId = &#39;&#39;

-   int object \ _id = null

-   int eqReal \ _id = null

-   int isVisible = 0

-   int isEnable = 0

-   array configuration

-   int timeout

-   array category

JSON Cmd API
============

cmd::all
--------

Returns the list of all commands

cmd::BYID
---------

Returns the specified command

Paramètres:

-   int id

cmd::byEqLogicId
----------------

Returns all orders belonging to the specified equipment

Paramètres:

-   int eqLogic \ _id

cmd::ExecCmd
------------

Execute the specified command

Paramètres:

-   int id : id of a command or id array if you want to execute
    multiple orders at once

-   \ [options \] List of command options (depends on type and
    command subtype)

cmd::getStatistique
-------------------

Returns statistics on the order (only works on
info and historical commands)

Paramètres:

-   int id

-   string startTime : start date of statistics calculation

-   string endTime : end date of statistics calculation

cmd::getTendance
----------------

Returns the trend on the command (only works on the commands of
info and historical type)

Paramètres:

-   int id

-   string startTime : trend calculation start date

-   string endTime : trend calculation end date

cmd::getHistory
---------------

Returns the command history (only works on the commands of
info and historical type)

Paramètres:

-   int id

-   string startTime : history start date

-   string endTime : history end date

cmd::save
---------

Returns the specified object

Paramètres:

-   int id (empty if it is a creation)

-   string name

-   string logicalId

-   string eqType

-   string order

-   string type

-   string subType

-   int eqLogic \ _id

-   int isHistorized = 0

-   string unite = &#39;&#39;

-   array configuration

-   array template

-   array display

-   array html

-   int value = null

-   int isVisible = 1

-   array alert

cmd::event
-------------------

Allows you to send a value to an order

Paramètres:

-   int id

-   string value : value

-   string datetime : (optional) value datetime

JSON Scenario API
=================

scenario::all
-------------

Returns the list of all scenarios

scenario::BYID
--------------

Returns the specified scenario

Paramètres:

-   int id

scenario::export
----------------

Returns the export of the scenario as well as the human name of the scenario

Paramètres:

-   int id

scenario::import
----------------

Allows you to import a scenario.

Paramètres:

-   int id : id of the scenario in which to import (empty if creation)

-   string humanName : human name of the scenario (empty if creation)

-   array import : scenario (from the export scenario field::export)

scenario::ChangeState
---------------------

Changes the state of the specified scenario.

Paramètres:

-   int id

-   string state: \ [Run, stop, enable, disable \]

JSON Log API
============

log::get
--------

Allows you to recover a log

Paramètres:

-   string log : name of the log to recover

-   string start : line number on which to start reading

-   string nbLine : number of lines to recover

log::add
--------

Allows to write in a log

Paramètres:

-   string log : name of the log to recover

-   string type : log type (debug, info, warning, error)

-   string message : text message to write

-   string logicalId : logicalId of the generated message


log::list
---------

Get the Jeedom logs list

Paramètres:

-   string filter : (optional) filter on the name of the logs to recover

log::empty
----------

Empty a log

Paramètres:

-   string log : name of the log to empty

log::remove
-----------

Allows you to delete a log

Paramètres:

-   string log : log name to delete

JSON datastore API (variable)
=============================

datastore::byTypeLinkIdKey
--------------------------

Get the value of a variable stored in the datastore

Paramètres:

-   string type : type of stored value (for scenarios
    it&#39;s scenario)

-   id linkId : -1 for the global (value for the default scenarios,
    or the scenario id)

-   string key : value name

datastore::save
---------------

Stores the value of a variable in the datastore

Paramètres:

-   string type : type of stored value (for scenarios
    it&#39;s scenario)

-   id linkId : -1 for the global (value for the default scenarios,
    or the scenario id)

-   string key : value name

-   mixed value : value to record

JSON Message API
================

message::all
------------

Returns the list of all messages

message::add
--------

Allows to write in a log

Paramètres:

-   string type : log type (debug, info, warning, error)

-   string message : message

-   string action : action

-   string logicalId : logicalId

message::removeAll
------------------

Delete all messages

JSON Interaction API
====================

interact::tryToReply
--------------------

Try to match a request with an interaction, execute
action and responds accordingly

Paramètres:

-   query (request phrase)

-   int reply \ _cmd = NULL : ID of the command to use to respond,
    if not specify then Jeedom sends you the answer in the json

interactQuery::all
------------------

Returns the complete list of all interactions

JSON System API
===============

jeedom::halt
------------

Stop Jeedom

jeedom::reboot
--------------

Restart Jeedom

jeedom::ISOK
------------

Lets you know if the global state of Jeedom is OK

jeedom::update
--------------

Lets launch a Jeedom update

jeedom::backup
--------------

Allows you to launch a backup of Jeedom

jeedom::getUsbMapping
---------------------

List of USB ports and names of USB key connected to it

JSON plugin API
===============

plugin::install
---------------

Installation / Update of a given plugin

Paramètres:

-   int plugin \ _id (optional) : plugin id
-   string logicalId (optional) : plugin name (logical name)

plugin::remove
--------------

Deletion of a given plugin

Paramètres:

-   int plugin \ _id (optional) : plugin id
-   string logicalId (optional) : plugin name (logical name)

plugin::dependancyInfo
----------------------

Returns information on the status of plugin dependencies

Paramètres:

-   int plugin \ _id (optional) : plugin id
-   string logicalId (optional) : plugin name (logical name)

plugin::dependancyInstall
-------------------------

Force installation of plugin dependencies

Paramètres:

-   int plugin \ _id (optional) : plugin id
-   string logicalId (optional) : plugin name (logical name)

plugin::deamonInfo
------------------

Returns information about the status of the plugin daemon

Paramètres:

-   int plugin \ _id (optional) : plugin id
-   string logicalId (optional) : plugin name (logical name)

plugin::deamonStart
-------------------

Force the demon to start

Paramètres:

-   int plugin \ _id (optional) : plugin id
-   string logicalId (optional) : plugin name (logical name)

plugin::deamonStop
------------------

Force demon stop

Paramètres:

-   int plugin \ _id (optional) : plugin id
-   string logicalId (optional) : plugin name (logical name)

plugin::deamonChangeAutoMode
----------------------------

Change the management mode of the daemon

Paramètres:

-   int plugin \ _id (optional) : plugin id
-   string logicalId (optional) : plugin name (logical name)
-   int mode : 1 for automatic, 0 for manual

JSON update API
===============

update::all
-----------

Return the list of all installed components, their version and the
related information

update::checkUpdate
-------------------

Allows you to check for updates

update::update
--------------

Allows you to update Jeedom and all plugins

update::DoUpdate
--------------

Paramètres:

-   int plugin \ _id (optional) : plugin id
-   string logicalId (optional) : plugin name (logical name)

JSON network API
================

network::restartDns
-------------------

Force the (re) start of the Jeedom DNS

network::stopDns
----------------

Forces the DNS Jeedom to stop

network::dnsRun
---------------

Return Jeedom DNS status

JSON API Examples
=================

Here is an example of using the API. For the example below
I use [this class
php] (https://github.com/jeedom/core/blob/stable/core/class/jsonrpcClient.class.php)
which simplifies the use of the API.

Retrieving the list of objects :

``` {.php}
$ jsonrpc = new jsonrpcClient (&#39;# URL_JEEDOM # / core / api / jeeApi.php &#39;, # API_KEY #);
if ($ jsonrpc-&gt; sendRequest ( &#39;jeeObject::all &#39;, array ())) {
    print_r ($ jsonrpc-&gt; getResult ());
} Else {
    echo $ jsonrpc-&gt; getError ();
}
```

Execution of an order (with the option of a title and a message)

``` {.php}
$ jsonrpc = new jsonrpcClient (&#39;# URL_JEEDOM # / core / api / jeeApi.php &#39;, # API_KEY #);
if ($ jsonrpc-&gt; sendRequest ( &#39;cmd::ExecCmd &#39;, array (&#39; id &#39;=&gt; # cmd_id #,&#39; options&#39; =&gt; array (&#39;title&#39; =&gt; &#39;Cuckoo&#39;, &#39;message&#39; =&gt; &#39;It works&#39;)))) {
    echo &#39;OK&#39;;
} Else {
    echo $ jsonrpc-&gt; getError ();
}
```

The API is of course usable with other languages (simply a post
on a page)
