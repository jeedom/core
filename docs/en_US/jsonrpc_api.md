Here is documentation on API methods. First here is
the specifications (JSON RPC 2.0) :
<http://www.jsonrpc.org/specification>

Access to the API is via the url : *URL \ _JEEDOM * / core / api / jeeApi.php

Various
======

ping
----

Return pong, test communication with Jeedom

Version
-------

Returns the Version of Jeedom

datetime
--------

Returns the Jeedom datetime in microseconds

Config API
==========

config::byKey
-------------

Returns a configuration value.

Settings :

-   string key : configuration value key to return

-   string Plugin : (optional), configuration value Plugin

-   string default : (optional), value to return if the key does not exist
    not

config::save
------------

Saves a configuration value

Settings :

-   string value : value to record

-   string key : configuration value key to save

-   string Plugin : (optional), Plugin of the configuration value to
    save

JSON Event API
==============

Event::exchange
--------------

Returns the list of exchange since the datetime notsed in parameter
(must be in microseconds). You will also have in the answer the
Jeedom&#39;s current datetime (to be reused for the next query)

Settings :

-   int datetime

JSON Plugin API
===============

Plugin::listPlugin
------------------

Returns the list of ALL Plugins

Settings :

-   int activateOnly = 0 (only returns the list of activated Plugins)

-   int orderByCaterogy = 0 (returns the list of sorted Plugins
    by category)

Object JSON API
==============

object::ALL
-----------

Returns the list of ALL objects

object::full
------------

Returns the list of ALL objects, with for each object ALL its
equipment and for each equipment ALL its commands as well as
states of these (for info type commands)

object::fullById
----------------

Returns an object with ALL its equipment and for each equipment
ALL its commands as well as their states (for
info type commands)

Settings :

-   int id

object::BYID
------------

Returns the specified object

Settings:

-   int id

object::fullById
----------------

Returns an object, its equipment and for each equipment ALL its
commands as well as the cell states (for type commands
info)

object::save
------------

Returns the specified object

Settings:

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

Settings:

-   string key : (optional), key of the desired summary, if empty then Jeedom
    sends you the summary for ALL the keys

summary::BYID
-------------

Returns the summary for the object id

Settings:

-   int id : object id

-   string key : (optional), key of the desired summary, if empty then Jeedom
    sends you the summary for ALL the keys

JSON EqLogic API
================

eqLogic::ALL
------------

Returns the list of ALL equipment

eqLogic::fullById
-----------------

Returns equipment and its commands as well as their states
(for info type orders)

Settings:

-   int id

eqLogic::BYID
-------------

Returns the specified equipment

Settings:

-   int id

eqLogic::byType
---------------

Returns ALL equipment belonging to the specified type (Plugin)

Settings:

-   string type

eqLogic::byObjectId
-------------------

Returns ALL equipment belonging to the specified object

Settings:

-   int object \ _id

eqLogic::byTypeAndId
--------------------

Returns an equipment table according to the parameters. The return
will be of the form array (&#39;eqType1&#39; ⇒array (&#39;id&#39;⇒…,&#39; cmds&#39; ⇒
array (….)), &#39;eqType2&#39; ⇒array (&#39;id&#39;⇒…,&#39; cmds&#39; ⇒ array (….))….,id1 ⇒
array (&#39;id&#39;⇒…,&#39; cmds &#39;⇒ array (….)), id2 ⇒ array (&#39; id&#39;⇒…, &#39;cmds&#39; ⇒
Array (....)) ..)

Settings:

-   string \ [\] eqType = table of the types of equipment required

-   int \ [\] id = table of desired custom equipment IDs

eqLogic::save
-------------

Returns the registered / created equipment

Settings:

-   int id (empty if it is a creation)

-   string eqType \ _name (type of script, virtual equipment, etc.)

-   string name

-   string logicalId = ''

-   int object \ _id = null

-   int eqReal \ _id = null

-   int isVisible = 0

-   int isEnable = 0

-   array configuration

-   int timeout

-   array category

JSON Cmd API
============

cmd::ALL
--------

Returns the list of ALL commands

cmd::BYID
---------

Returns the specified command

Settings:

-   int id

cmd::byEqLogicId
----------------

Returns ALL orders belonging to the specified equipment

Settings:

-   int eqLogic \ _id

cmd::ExecCmd
------------

Execute the specified command

Settings:

-   int id : id of a command or id array if you want to execute
    multiple orders at once

-   \ [options \] List of command options (depends on type and
    command subtype)

cmd::getStatistics
-------------------

Returns statistics on the order (only works on
info and historical commands)

Settings:

-   int id

-   string startTime : start date of statistics calculation

-   string endTime : end date of statistics calculation

cmd::getTendance
----------------

Returns the trend on the command (only works on the commands of
info and historical type)

Settings:

-   int id

-   string startTime : trend calculation start date

-   string endTime : trend calculation end date

cmd::getHistory
---------------

Returns the command history (only works on the commands of
info and historical type)

Settings:

-   int id

-   string startTime : history start date

-   string endTime : history end date

cmd::save
---------

Returns the specified object

Settings:

-   int id (empty if it is a creation)

-   string name

-   string logicalId

-   string eqType

-   string order

-   string type

-   string subType

-   int eqLogic \ _id

-   int isHistorized = 0

-   string unit = ''

-   array configuration

-   array template

-   array display

-   array html

-   int value = null

-   int isVisible = 1

-   array alert

cmd::Event
-------------------

Allows you to send a value to an order

Settings:

-   int id

-   string value : value

-   string datetime : (optional) value datetime

JSON Scenario API
=================

Scenario::ALL
-------------

Returns the list of ALL Scenarios

Scenario::BYID
--------------

Returns the specified Scenario

Settings:

-   int id

Scenario::export
----------------

Returns the export of the Scenario as well as the human name of the Scenario

Settings:

-   int id

Scenario::import
----------------

Allows you to import a Scenario.

Settings:

-   int id : id of the Scenario in which to import (empty if creation)

-   string humanName : human name of the Scenario (empty if creation)

-   array import : Scenario (from the export Scenario field::export)

Scenario::ChangeState
---------------------

Changes the state of the specified Scenario.

Settings:

-   int id

-   string state: \ [Run, stop, enable, disable \]

JSON Log API
============

log::get
--------

Allows you to recover a log

Settings:

-   string log : name of the log to recover

-   string start : line number on which to start reading

-   string nbLine : number of lines to recover

log::list
---------

Get the Jeedom logs list

Settings:

-   string filter : (optional) filter on the name of the logs to recover

log::empty
----------

Empty a log

Settings:

-   string log : name of the log to empty

log::remove
-----------

Allows you to delete a log

Settings:

-   string log : log name to delete

JSON datastore API (variable)
=============================

datastore::byTypeLinkIdKey
--------------------------

Get the value of a variable stored in the datastore

Settings:

-   string type : type of stored value (for Scenarios
    it&#39;s Scenario)

-   id linkId : -1 for global (value for default Scenarios,
    or the Scenario id)

-   string key : value name

datastore::save
---------------

Stores the value of a variable in the datastore

Settings:

-   string type : type of stored value (for Scenarios
    it&#39;s Scenario)

-   id linkId : -1 for global (value for default Scenarios,
    or the Scenario id)

-   string key : value name

-   mixed value : value to record

JSON Message API
================

Message::ALL
------------

Returns the list of ALL Messages

Message::removeAll
------------------

Delete ALL Messages

JSON Interaction API
====================

Interact::tryToReply
--------------------

Try to match a request with an Interaction, execute
action and responds accordingly

Settings:

-   query (request phrase)

-   int reply \ _cmd = NULL : Command ID to use to respond,
    if not specify then Jeedom sends you the answer in the json

InteractQuery::ALL
------------------

Returns the complete list of ALL Interactions

JSON System API
===============

Jeedom::halt
------------

Stop Jeedom

Jeedom::reboot
--------------

Restart Jeedom

Jeedom::ISOK
------------

Lets you know if the global state of Jeedom is OK

Jeedom::update
--------------

Lets launch a Jeedom update

Jeedom::Backup
--------------

Allows you to launch a Backup of Jeedom

Jeedom::getUsbMapping
---------------------

List of USB ports and names of USB key connected to it

JSON Plugin API
===============

Plugin::instALL
---------------

InstALLation / Update of a given Plugin

Settings:

-   string Plugin \ _id : Plugin name (logical name)

Plugin::remove
--------------

Deletion of a given Plugin

Settings:

-   string Plugin \ _id : Plugin name (logical name)

Plugin::dependancyInfo
----------------------

Returns information on the status of Plugin dependencies

Settings:

-   string Plugin \ _id : Plugin name (logical name)

Plugin::dependancyInstALL
-------------------------

Force instALLation of Plugin dependencies

Settings:

-   string Plugin \ _id : Plugin name (logical name)

Plugin::deamonInfo
------------------

Returns information about the status of the Plugin daemon

Settings:

-   string Plugin \ _id : Plugin name (logical name)

Plugin::deamonStart
-------------------

Force the demon to start

Settings:

-   string Plugin \ _id : Plugin name (logical name)

Plugin::deamonStop
------------------

Force demon stop

Settings:

-   string Plugin \ _id : Plugin name (logical name)

Plugin::deamonChangeAutoMode
----------------------------

Change the management mode of the daemon

Settings:

-   string Plugin \ _id : Plugin name (logical name)

-   int mode : 1 for automatic, 0 for manual

JSON update API
===============

update::ALL
-----------

Return the list of ALL instALLed components, their Version and the
related information

update::checkUpdate
-------------------

Allows you to check for updates

update::update
--------------

Allows you to update Jeedom and ALL Plugins

update::DoUpdate
--------------

Settings:

-   int Plugin \ _id (optional) : Plugin id
-   string logicalId (optional) : Plugin name (logical name)

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
php] (https://github.com/Jeedom/core/blob/stable/core/class/jsonrpcClient.class.php)
which simplifies the use of the API.

Retrieving the list of objects :

``` {.php}
$jsonrpc = new jsonrpcClient('#URL_JEEDOM#/core/api/jeeApi.php', #API_KEY#);
if ($ jsonrpc-&gt; sendRequest ( &#39;object::ALL &#39;, array ())){
    print_r ($ jsonrpc-&gt; getResult ());
}else{
    echo $ jsonrpc-&gt; getError ();
}
```

Execution of an order (with the option of a title and a Message)

``` {.php}
$jsonrpc = new jsonrpcClient('#URL_JEEDOM#/core/api/jeeApi.php', #API_KEY#);
if ($ jsonrpc-&gt; sendRequest ( &#39;cmd::ExecCmd &#39;, array (&#39; id &#39;=> # cmd_id #,&#39; options&#39; => array (&#39;title&#39; => &#39;Cuckoo&#39;, &#39;Message&#39; => &#39;It works&#39;)))){
    echo &#39;OK&#39;;
}else{
    echo $ jsonrpc-&gt; getError ();
}
```

The API is of course usable with other languages (simply a post
on a page)
