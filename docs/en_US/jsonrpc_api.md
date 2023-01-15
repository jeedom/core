Here is documentation on API methods.

First here are the specifications (JSON RPC 2.0) :
<http://www.jsonrpc.org/specification>

Access to the API is via the url : **

Here is an example of configuring a Json object that can be used in the body of a request made by an HTTP agent:
`` json
{
    "jsonrpc": "2.0",
    "id": "007",
    "method": "event::changes",
    "params": {
        "apikey": "{{apikey}}",
        "datetime": "0"
    }
}
`` ''

Divers
======

ping
----

Return pong, test communication with Jeedom

version
-------

Returns the version of Jeedom

datetime
--------

Returns the Jeedom datetime in microseconds

Config API
==========

config::byKey
-------------

Returns a configuration value.

Json settings :

-    : configuration value key to return

-    : (optional), configuration value plugin

-    : (optional), value to return if the key does not exist

config::save
------------

Saves a configuration value

Json settings :

-    : value to record

-    : configuration value key to save

-    : (optional), plugin of the configuration value to save

JSON Event API
==============

event::changes
--------------

Returns the list of changes since the datetime passed in parameter (must be in microseconds). You will also have in the response the current datetime of Jeedom (to be reused for the following query)

Json settings :

-   

JSON Plugin API
===============

plugin::listPlugin
------------------

Returns the list of all plugins

Json settings :

-   int activateOnly = 0 (only returns the list of activated plugins)

-   int orderByCaterogy = 0 (returns the list of plugins sorted by category)

Object JSON API
==============

jeeObject::all
-----------

Returns the list of all objects

jeeObject::full
------------

Returns the list of all objects, with for each object all its equipment and for each equipment all its commands as well as their states (for commands of type info)

jeeObject::fullById
----------------

Returns an object with all its equipment and for each equipment all its commands as well as their states (for commands of type info)

Json settings :

-   

jeeObject::byId
------------

Returns the specified object

Settings:

-   

jeeObject::fullById
----------------

Returns an object, its equipment and for each equipment all its commands as well as the cell states (for info type commands)

jeeObject::save
------------

Returns the specified object

Settings:

-   int id (empty if it is a creation)

-   string name

-   

-   int isVisible = 0

-   int position

-   array configuration

-   array display

JSON Summary API
================

summary::global
---------------

Returns the global summary for the key passed in parameter

Settings:

-    : (optional), key of the desired summary, if empty then Jeedom returns the summary for all the keys

summary::byId
-------------

Returns the summary for the object id

Settings:

-    : object id

-    : (optional), key of the desired summary, if empty then Jeedom returns the summary for all the keys

JSON EqLogic API
================

eqLogic::all
------------

Returns the list of all equipment

eqLogic::fullById
-----------------

Returns a device and its commands as well as their states (for info type commands)

Settings:

-   

eqLogic::byId
-------------

Returns the specified equipment

Settings:

-   

eqLogic::byType
---------------

Returns all equipment belonging to the specified type (plugin)

Settings:

-   thong type

eqLogic::byObjectId
-------------------

Returns all equipment belonging to the specified object

Settings:

-   

eqLogic::byTypeAndId
--------------------

Returns an equipment table according to the parameters.

The return will be of the form array (&#39;eqType1&#39; ⇒array (&#39;id&#39;⇒…,&#39; cmds&#39; ⇒
array (….)), &#39;eqType2&#39; ⇒array (&#39;id&#39;⇒…,&#39; cmds&#39; ⇒ array (….))….,
array (&#39;id&#39;⇒…,&#39; cmds &#39;⇒ array (….)), id2 ⇒ array (&#39; id&#39;⇒…, &#39;cmds&#39; ⇒
array(…​.))..)

Settings:

-   string \ [\] eqType = table of the types of equipment required

-   int \ [\] id = table of desired custom equipment IDs

eqLogic::save
-------------

Returns the registered / created equipment

Settings:

-   int id (empty if it is a creation)

-   string eqType\_name (type of script, virtual equipment…)

-   string name

-   string logicalId = ''

-   int object\_id = null

-   int eqReal\_id = null

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

cmd::byId
---------

Returns the specified command

Settings:

-   

cmd::byEqLogicId
----------------

Returns all commands belonging to the specified equipment

Settings:

-   

cmd::execCmd
------------

Execute the specified command

Settings:

-    : command id or id array if you want to execute multiple commands at once

-   \ [options \] List of command options (depends on the type and subtype of the command)

cmd::getStatistique
-------------------

Returns the statistics on the order (only works on info and historical orders)

Settings:

-   

-    : start date of statistics calculation

-    : end date of statistics calculation

cmd::getTendance
----------------

Returns the trend on the order (only works on info and historical orders)

Settings:

-   

-    : trend calculation start date

-    : trend calculation end date

cmd::getHistory
---------------

Returns the order history (only works on info and historical orders)

Settings:

-   

-    : history start date

-    : history end date

cmd::save
---------

Returns the specified object

Settings:

-   int id (empty if it is a creation)

-   string name

-   string logicalId

-   string eqType

-   thong order

-   thong type

-   

-   

-   

-   string unit = ''

-   array config

-   

-   

-   

-   intvalue=null

-   

-   array-alert

cmd::event
-------------------

Allows you to send a value to a command

Settings:

-   

-    : valeur

-   datetime string : (optional) datetime value

JSON Scenario API
=================

scenario::all
-------------

Returns the list of all scenarios

scenario::byId
--------------

Returns the specified scenario

Settings:

-   

scenario::export
----------------

Returns the export of the scenario as well as the *human name* from the script

Settings:

-   

scenario::import
----------------

Allows you to import a scenario.

Settings:

-    : id of the scenario in which to import (empty if creation)

-    : *human name* of the scenario (empty if creation)

-    : scenario (from the export scenario field::export)

scenario::changeState
---------------------

Changes the state of the specified scenario.

Settings:

-   

-   string state: \ [Run, stop, enable, disable \]

JSON Log API
============

log::get
--------

Allows you to retrieve a log

Settings:

-    : name of the log to retrieve

-    : line number on which to start reading

-    : number of lines to recover

log::add
--------

Allows to write in a log

Settings:

-    : name of the log to retrieve

-   thong type : log type (debug, info, warning, error)

-   message string : text message to write

-    : logicalId of the generated message


log::list
---------

Get the Jeedom logs list

Settings:

-   string filter : (optional) filter on the name of the logs to retrieve

log::empty
----------

Empty a log

Settings:

-    : name of the log to empty

log::remove
-----------

Allows you to delete a log

Settings:

-    : log name to delete

JSON datastore API (variable)
=============================

datastore::byTypeLinkIdKey
--------------------------

Get the value of a variable stored in the datastore

Settings:

-   thong type : type of stored value (for scenarios it is scenario)

-    : -1 for the global (value for the default scenarios, or the scenario id)

-    : value name

datastore::save
---------------

Stores the value of a variable in the datastore

Settings:

-   thong type : type of stored value (for scenarios
    it's scenario)

-    : -1 for global (value for default scenarios,
    or the scenario id)

-    : value name

-   mixed value : value to record

JSON Message API
================

message::all
------------

Returns the list of all messages

message::add
--------

Allows to write in a log

Settings:

-   thong type : log type (debug, info, warning, error)

-   message string : message

-   thong action : action

-    : logicalId

message::removeAll
------------------

Delete all messages

JSON Interaction API
====================

interact::tryToReply
--------------------

Try to match a request with an interaction, execute the action and respond accordingly

Settings:

-   query (request phrase)

-    : Command ID to use to respond,
    if not specify then Jeedom returns the answer to you in the json

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

jeedom::isOk
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

List of USB ports and names of USB keys connected to it

JSON Plugin API
===============

plugin::install
---------------

Installation / Update of a given plugin

Settings:

-   int plugin\_id (optional) : plugin id
-   string logicalId (optional) : plugin name (logical name)

plugin::remove
--------------

Deletion of a given plugin

Settings:

-   int plugin\_id (optional) : plugin id
-   string logicalId (optional) : plugin name (logical name)

plugin::dependancyInfo
----------------------

Returns information on the plugin dependency status

Settings:

-   int plugin\_id (optional) : plugin id
-   string logicalId (optional) : plugin name (logical name)

plugin::dependancyInstall
-------------------------

Force installation of plugin dependencies

Settings:

-   int plugin\_id (optional) : plugin id
-   string logicalId (optional) : plugin name (logical name)

plugin::deamonInfo
------------------

Returns information about the status of the plugin daemon

Settings:

-   int plugin\_id (optional) : plugin id
-   string logicalId (optional) : plugin name (logical name)

plugin::deamonStart
-------------------

Force the demon to start

Settings:

-   int plugin\_id (optional) : plugin id
-   string logicalId (optional) : plugin name (logical name)

plugin::deamonStop
------------------

Force demon stop

Settings:

-   int plugin\_id (optional) : plugin id
-   string logicalId (optional) : plugin name (logical name)

plugin::deamonChangeAutoMode
----------------------------

Change the management mode of the daemon

Settings:

-   int plugin\_id (optional) : plugin id
-   string logicalId (optional) : plugin name (logical name)
-    : 1 for automatic, 0 for manual

JSON update API
===============

update::all
-----------

Returns a list of all installed components, their versions and associated information

update::checkUpdate
-------------------

Allows you to check for updates

update::update
--------------

Allows you to update Jeedom and all plugins

update::doUpdate
--------------

Settings:

-   int plugin\_id (optional) : plugin id
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

JSON timeline API
===============

timeline::all
-----------

Returns all elements of the timeline

timeline::listFolder
-----------

Returns all the folders (category) of the timeline

timeline::byFolder
-----------

Returns all the elements of the requested folder

Settings:

-    : folder name

JSON User API
=================

user::all
-------------

Returns the list of all users

user::save
---------------------

Create or edit a user

Settings:

-   int id (if edition)

-   string login

-   string password

-   string profile: ]


JSON API Examples
=================

Here is an example of using the API. For the example below
 [this php class](https://github.com/jeedom/core/blob/release/core/class/jsonrpcClient.class.php)
which simplifies the use of the API.

Retrieving the list of objects :

`` `{.php}
$ = new jsonrpcClient('#URL_JEEDOM#', #API_KEY#);
if ($ jsonrpc-&gt; sendRequest ( &#39;jeeObject::all ', array())){
    print_r ($ jsonrpc-&gt; getResult ());
}else{
    echo $ jsonrpc-&gt; getError ();
}
`` ''

Execution of an order (with the option of a title and a message)

`` `{.php}
$ = new jsonrpcClient('#URL_JEEDOM#', #API_KEY#);
if ($ jsonrpc-&gt; sendRequest ( &#39;cmd::execCmd ', array (' id' => #cmd_id#, 'options '=> array (' title '=>' Cuckoo ',' message '=>' It works')))){
    echo &#39;OK&#39;;
}else{
    echo $ jsonrpc-&gt; getError ();
}
`` ''

The API is of course usable with other languages (just a post on a page)
