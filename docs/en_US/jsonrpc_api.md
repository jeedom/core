Here is the documentation on the API methods.

First, here are the specifications (JSON-RPC 2.0):
<http://www.jsonrpc.org/specification>

The API can be accessed via the following URL: *URL\_JEEDOM*/core/api/jeeApi.php

Here is an example of how to configure a JSON object for use in the body of a request made by an HTTP agent:
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

Miscellaneous
======

ping
----

Return Pong, allows you to test communication with Jeedom

version
-------

Returns the Jeedom version

datetime
--------

Converts Jeedom's datetime to microseconds

API configuration
==========

config::byKey
-------------

Returns a configuration value.

JSON settings:

-   string key: key of the configuration value to be returned

-   string plugin: (optional), plugin for the configuration value

-   default string: (optional), value to return if the key does not exist

config::save
------------

Saves a configuration value

JSON settings:

-   string value: value to be stored

-   string key: key for the configuration value to be saved

-   string plugin: (optional), plugin for the configuration value to be saved

JSON Event API
==============

event::changes
--------------

Returns a list of changes since the datetime passed as a parameter (must be in microseconds). The response will also include Jeedom's current datetime (to be reused for the next query)

JSON settings:

-   int datetime

JSON API Plugin
===============

plugin::listPlugin
------------------

Returns a list of all plugins

JSON settings:

-   int activateOnly = 0 (returns only the list of activated plugins)

-   int orderByCategory = 0 (returns the list of plugins sorted by category)

JSON Object API
==============

jeeObject::all
-----------

Returns a list of all objects

jeeObject::full
------------

Returns a list of all objects, with all devices for each object, and for each device, all commands as well as their statuses (for info-type commands)

jeeObject::fullById
----------------

Returns an object containing all of its devices, and for each device, all of its commands as well as their statuses (for info-type commands)

JSON settings:

-   int id

jeeObject::byId
------------

Returns the specified object

Settings:

-   int id

jeeObject::fullById
----------------

Returns an object, its devices, and—for each device—all of its commands along with their statuses (for info-type commands)

jeeObject::save
------------

Returns the specified object

Settings:

-   int id (empty if it's a new entry)

-   string name

-   int father_id = null

-   int isVisible = 0

-   int position

-   array configuration

-   array display

JSON API Summary
================

summary::global
---------------

Returns the overall summary for the key passed as a parameter

Settings:

-   string key: (optional), key for the desired summary; if left blank, Jeedom returns the summary for all keys

summary::byId
-------------

Returns the summary for the object ID

Settings:

-   int id: object ID

-   string key: (optional), key for the desired summary; if left blank, Jeedom returns the summary for all keys

EqLogic JSON API
================

eqLogic::all
------------

Returns a list of all devices

eqLogic::fullById
-----------------

Returns a device and its commands, along with the status of those commands (for info-type commands)

Settings:

-   int id

eqLogic::byId
-------------

Returns the specified device

Settings:

-   int id

eqLogic::byType
---------------

Returns all devices of the specified type (plugin)

Settings:

-   string type type

eqLogic::byObjectId
-------------------

Returns all devices belonging to the specified object

Settings:

-   int object_id

eqLogic::byTypeAndId
--------------------

Returns an array of devices based on the parameters.

The return value will be in the form of an array('eqType1' ⇒ array( 'id' ⇒ …​, 'cmds' ⇒
array(…​.)), 'eqType2' ⇒ array( 'id' ⇒ …​, 'cmds' ⇒ array(…​.))…​., id1 ⇒
array( 'id' ⇒ …​, 'cmds' ⇒ array(…​.)), id2 ⇒ array( 'id' ⇒ …​, 'cmds' ⇒
array(…​.))..)

Settings:

-   string\[\] eqType = array of desired device types

-   int\[\] id = array of desired custom device IDs

eqLogic::save
-------------

Returns the registered/created device

Settings:

-   int id (empty if it's a new entry)

-   string eqType\_name (device type: script, virtual, etc.)

-   string name

-   string logicalId = ''

-   int object_id = null

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

Returns a list of all commands

cmd::byId
---------

Returns the specified command

Settings:

-   int id

cmd::byEqLogicId
----------------

Returns all commands belonging to the specified device

Settings:

-   int eqLogic_id

cmd::execCmd
------------

Executes the specified command

Settings:

-   int id: ID of a command or an array of IDs if you want to execute multiple commands at once

-   \[options\] List of command options (depends on the command type and subtype)

cmd::getStatistics
-------------------

Returns statistics about the command (works only for info-type and logged commands)

Settings:

-   int id

-   string startTime: start date for calculating statistics

-   string endTime: end date for calculating statistics

cmd::getTrend
----------------

Reverses the trend on the command (works only on commands of the "info" and "historical" types)

Settings:

-   int id

-   string startTime: start date for trend calculation

-   string endTime: end date for trend calculation

cmd::getHistory
---------------

Returns the command history (works only for "info" type commands and those that have been logged)

Settings:

-   int id

-   string startTime: start date of the history

-   string endTime: end date of the history

cmd::save
---------

Returns the specified object

Settings:

-   int id (empty if it's a new entry)

-   string name

-   string logicalId

-   string eqType

-   string order

-   string type type

-   string subType

-   int eqLogic_id

-   int isHistorized = 0

-   string unite = ''

-   array configuration

-   array template

-   array display

-   HTML array

-   int value = null

-   int isVisible = 1

-   array alert

cmd::event
-------------------

Allows you to send a value to a command

Settings:

-   int id

-   string value: value

-   datetime string: (optional) the value's datetime

JSON API Scenario
=================

scenario::all
-------------

Returns a list of all scenarios

scenario::byId
--------------

Returns the specified scenario

Settings:

-   int id

scenario::export
----------------

Returns the scenario export and the *human-readable name* of the scenario

Settings:

-   int id

scenario::import
----------------

Allows you to import a scenario.

Settings:

-   int id: ID of the scenario to import into (empty if creating)

-   string humanName: *human name* of the scenario (empty when created)

-   array import: scenario (from the "export" field of scenario::export)

scenario::changeState
---------------------

Changes the status of the specified scenario.

Settings:

-   int id

-   string state: [run, stop, enable, disable]

JSON API Log
============

log::get
--------

Allows you to retrieve a log

Settings:

-   log string: name of the log to retrieve

-   string start: line number to start reading from

-   string nbLine: number of lines to retrieve

log::add
--------

Allows you to write to a log

Settings:

-   log string: name of the log to retrieve

-   string type: log type (debug, info, warning, error)

-   string message: text message to be written

-   string logicalId: logicalId of the generated message


log::list
---------

Retrieves the list of Jeedom logs

Settings:

-   filter string: (optional) filter based on the names of the logs to retrieve

log::empty
----------

Allows you to clear a log

Settings:

-   string log: name of the log to be cleared

log::remove
-----------

Allows you to delete a log

Settings:

-   log string: name of the log to be deleted

JSON Datastore API (variable)
=============================

datastore::byTypeLinkIdKey
--------------------------

Retrieves the value of a variable stored in the datastore

Settings:

-   string type: type of the stored value (for scenarios, it is "scenario")

-   linkId: -1 for global (default value for scenarios, or the scenario ID)

-   string key: value name

datastore::save
---------------

Saves the value of a variable to the datastore

Settings:

-   string type: type of the stored value (for scenarios
(this is a scenario)

-   id linkId: -1 for the global setting (default value for scenarios,
or the scenario ID)

-   string key: value name

-   mixed value: value to be recorded

JSON API Message
================

message::all
------------

Returns a list of all messages

message::add
--------

Allows you to write to a log

Settings:

-   string type: log type (debug, info, warning, error)

-   message string: message

-   string action: action

-   string logicalId: logicalId

message::removeAll
------------------

Delete all messages

JSON API Interaction
====================

interact::tryToReply
--------------------

Try to match a request with an interaction, perform the action, and respond accordingly

Settings:

-   query (search phrase)

-   int reply_cmd = NULL: ID of the command to use for the response,
If not specified, Jeedom returns the response in JSON

interactQuery::all
------------------

Returns the complete list of all interactions

JSON API System
===============

jeedom::halt
------------

Allows you to stop Jeedom

jeedom::reboot
--------------

Allows you to restart Jeedom

jeedom::isOk
------------

Lets you know if Jeedom's overall status is OK

jeedom::update
--------------

Allows you to run a Jeedom update

jeedom::backup
--------------

Allows you to start a Jeedom backup

jeedom::getUsbMapping
---------------------

List of USB ports and the names of the USB flash drives connected to them

JSON API plugin
===============

plugin::install
---------------

Installing/Updating a Specific Plugin

Settings:

-   int plugin_id (optional): plugin ID
-   string logicalId (optional): plugin name (logical name)

plugin::remove
--------------

Removing a Specific Plugin

Settings:

-   int plugin_id (optional): plugin ID
-   string logicalId (optional): plugin name (logical name)

plugin::dependancyInfo
----------------------

Returns information about the status of the plugin's dependencies

Settings:

-   int plugin_id (optional): plugin ID
-   string logicalId (optional): plugin name (logical name)

plugin::dependancyInstall
-------------------------

Force the installation of the plugin's dependencies

Settings:

-   int plugin_id (optional): plugin ID
-   string logicalId (optional): plugin name (logical name)

plugin::daemonInfo
------------------

Returns information about the plugin daemon's status

Settings:

-   int plugin_id (optional): plugin ID
-   string logicalId (optional): plugin name (logical name)

plugin::daemonStart
-------------------

Force the daemon to start

Settings:

-   int plugin_id (optional): plugin ID
-   string logicalId (optional): plugin name (logical name)

plugin::daemonStop
------------------

Force the daemon to stop

Settings:

-   int plugin_id (optional): plugin ID
-   string logicalId (optional): plugin name (logical name)

plugin::daemonChangeAutoMode
----------------------------

Change the daemon's management mode

Settings:

-   int plugin_id (optional): plugin ID
-   string logicalId (optional): plugin name (logical name)
-   int mode: 1 for automatic, 0 for manual

JSON API update
===============

update::all
-----------

Returns a list of all installed components, their versions, and related information

update::checkUpdate
-------------------

Allows you to check for updates

update::update
--------------

Allows you to update Jeedom and all plugins

update::doUpdate
--------------

Settings:

-   int plugin_id (optional): plugin ID
-   string logicalId (optional): plugin name (logical name)

JSON API network
================

network::restartDns
-------------------

Force a (re)start of the Jeedom DNS

network::stopDns
----------------

Force the Jeedom DNS to stop

network::dnsRun
---------------

JSON API timeline
===============

timeline::all
-----------

Returns all items on the timeline

timeline::listFolder
-----------

Returns all folders (categories) from the timeline

timeline::byFolder
-----------

Returns all items in the specified folder

Settings:

-   string folder: folder name

JSON User API
=================

user::all
-------------

Returns a list of all users

user::save
---------------------

Create or edit a user

Settings:

-   int id (if edited)

-   login string

-   password string

-   string profile: \[admin,user,restrict\]


JSON API Examples
=================

Here is an example of how to use the API. For the example below
I use [this PHP class](https://github.com/jeedom/core/blob/master/core/class/jsonrpcClient.class.php)
which simplifies the use of the API.

Retrieving the list of objects:

``` {.php}
$jsonrpc = new jsonrpcClient('#URL_JEEDOM#/core/api/jeeApi.php', #API_KEY#);
if($jsonrpc->sendRequest('jeeObject::all', array())){
    print_r($jsonrpc->getResult());
}else{
    echo $jsonrpc->getError();
}
```

Executing a command (with optional title and message)

``` {.php}
$jsonrpc = new jsonrpcClient('#URL_JEEDOM#/core/api/jeeApi.php', #API_KEY#);
if($jsonrpc->sendRequest('cmd::execCmd', array('id' => #cmd_id#, 'options' => array('title' => 'Coucou', 'message' => 'Ca marche')))){
    echo 'OK';
}else{
    echo $jsonrpc->getError();
}
```

The API can, of course, be used with other languages (simply by posting to a page)
