Here is a documentation on API methods. First of all here
the specifications (JSON RPC 2.0):
<Http://www.jsonrpc.org/specification>

Access to the API is via the URL: * URL \ _JEEDOM * / core / api / jeeApi.php

Various
======

ping
----

Return pong, to test the communication with Jeedom

version
-------

Returns the version of Jeedom

datetime
--------

Returns the Jeedom datetime in microseconds

Config API
==========

Config :: byKey
-------------

Returns a configuration value.

Settings :

-   string key: key of the configuration value to return

-   string plugin: (optional), configuration value plugin

-   string default: (optional), value to return if the key does not exist
    not

Config :: save
------------

Saves a configuration value

Settings :

-   string value: value to save

-   string key: key of the configuration value to be saved

-   string plugin: (optional), plugin of the configuration value to
    record

Event JSON API
==============

event :: changes
--------------

Returns the list of changes since the datetime passed as parameter
(must be in microseconds) You will also have in the answer the
Jeedom's current datetime (to reuse for next query)

Settings :

-   int datetime

API JSON Plugin
===============

plugin :: listPlugin
------------------

Returns the list of all plugins

Settings :

-   int activateOnly = 0 (only returns the list of activated plugins)

-   int orderByCaterogy = 0 (returns the list of sorted plugins
    by category)

Object JSON API
==============

object :: all
-----------

Returns the list of all objects

:: full object
------------

Returns the list of all objects, with for each object all its
equipment and for each equipment all its controls as well as the
states of these (for info commands)

:: object fullById
----------------

Returns an object with all its equipment and for each device
all its orders as well as the states thereof (for
info type commands)

Settings :

-   int id

:: object BYID
------------

Returns the specified object

Settings:

-   int id

:: object fullById
----------------

Returns an object, its equipment and for each equipment all its
orders and cell status (for type orders).
info)

object :: save
------------

Returns the specified object

Settings:

-   int id (empty if it's a creation)

-   string name

-   int father \ _id = null

-   int isVisible = 0

-   int position

-   array configuration

-   display array

JSON API Summary
================

summary :: Global
---------------

Return the global summary for the key passed in parameter

Settings:

-   string key: (optional), key of the desired summary, if empty then Jeedom
    you return the summary for all the keys

summary :: BYID
-------------

Return the summary for the id object

Settings:

-   int id: id of the object

-   string key: (optional), key of the desired summary, if empty then Jeedom
    you return the summary for all the keys

JSON EqLogic API
================

:: all eqLogic
------------

Returns a list of all equipment

eqLogic :: fullById
-----------------

Returns a device and its commands as well as the states of these
(for info orders)

eqLogic :: BYID
-------------

Returns the specified equipment

Settings:

-   int id

eqLogic :: byType
---------------

Returns all devices belonging to the specified type (plugin)

Settings:

-   string type

eqLogic :: byObjectId
-------------------

Returns all devices belonging to the specified object

Settings:

-   int object \ _id

eqLogic :: byTypeAndId
--------------------

Returns an array of equipment based on parameters. The return
will be of the form array ('eqType1' ⇒array ('id'⇒ ...,' cmds' ⇒
array (....)), 'eqType2' ⇒array ('id'⇒ ...,' cmds' ⇒ array (....)) ...., id1 ⇒
array ('id'⇒ ...,' cmds '⇒ array (....)), id2 ⇒ array (' id'⇒ ..., 'cmds' ⇒
Array (....)) ..)

Settings:

-   string \ [\] eqType = array of desired device types

-   int \ [\] id = array of desired custom device IDs

eqLogic :: save
-------------

Returns the registered / created equipment

Settings:

-   int id (empty if it's a creation)

-   string eqType \ _name (type of scripting device, virtual ...)

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

:: all cmd
--------

Returns a list of all orders

cmd :: BYID
---------

Returns the specified command

Settings:

-   int id

cmd :: byEqLogicId
----------------

Returns all orders belonging to the specified equipment

Settings:

-   int eqLogic \ _id

cmd :: ExecCmd
------------

Execute the specified command

Settings:

-   int id: id of a command or array of id if you want to execute
    several command at once

-   \ [options \] List of command options (depends on type and
    subtype of the command)

cmd :: getStatistique
-------------------

Returns the statistics on the order (only works on
info and historized commands)

Settings:

-   int id

-   string startTime: start date of calculation of statistics

-   string endTime: end date of calculation of statistics

cmd :: getTendance
----------------

Returns the trend on the command (works only on
type info and historized)

Settings:

-   int id

-   string startTime: start date of the trend calculation

-   string endTime: end date of trend calculation

cmd :: getHistory
---------------

Returns the command history (only works on the commands of
type info and historized)

Settings:

-   int id

-   string startTime: start date of the history

-   string endTime: end date of the history

cmd :: save
---------

Returns the specified object

Settings:

-   int id (empty if it's a creation)

-   string name

-   string logicalId

-   string eqType

-   string order

-   string type

-   subType string

-   int eqLogic \ _id

-   int isHistorized = 0

-   string unite = ''

-   array configuration

-   array template

-   array display

-   html array

-   int value = null

-   int isVisible = 1

-   array alert

JSON Scenario API
=================

:: all scenario
-------------

Return the list of all the scenarios

:: scenario BYID
--------------

Returns the specified scenario

Settings:

-   int id

:: export scenario
----------------

Returns the scenario export as well as the human name of the scenario

Settings:

-   int id

:: import scenario
----------------

Import a scenario.

Settings:

-   int id: id of the scenario in which to import (empty if creation)

-   string humanName: human name of the scenario (empty if creation)

-   array import: scenario (from the export field of scenario :: export)

:: scenario ChangeState
---------------------

Change the state of the specified scenario.

Settings:

-   int id

-   string state: \ [run, stop, enable, disable \]

JSON Log API
============

log :: get
--------

Retrieve a log

Settings:

-   string log: name of the log to retrieve

-   string start: line number on which to start playback

-   string nbLine: number of rows to retrieve

log :: list
---------

Retrieves the list of Jeedom logs

Settings:

-   string filter: (optional) filter on the name of the logs to retrieve

empty log ::
----------

Used to empty a log

Settings:

-   string log: name of the log to empty

log :: remove
-----------

Delete a log

Settings:

-   string log: name of the log to delete

JSON datastore API (variable)
=============================

:: datastore byTypeLinkIdKey
--------------------------

Retrieves the value of a variable stored in the datastore

Settings:

-   string type: type of stored value (for scenarios
    it's scenario)

-   id linkId: -1 for the global (value for the default scenarios,
    or the id of the scenario)

-   string key: name of the value

datastore :: save
---------------

Saves the value of a variable in the datastore

Settings:

-   string type: type of stored value (for scenarios
    it's scenario)

-   id linkId: -1 for the global (value for the default scenarios,
    or the id of the scenario)

-   string key: name of the value

-   mixed value: value to record

JSON API Message
================

Message :: all
------------

Returns the list of all messages

Message :: removeAll
------------------

Delete all messages

JSON Interaction API
====================

Interact :: tryToReply
--------------------

Trying to match a request with an interaction, performs
the action and responds accordingly

Settings:

-   query (sentence of the request)

-   int reply \ _cmd = NULL: ID of the command to use to reply,
    if not specify then Jeedom will send you the answer in the json

:: all interactQuery
------------------

Return the complete list of all interactions

JSON API System
===============

jeedom :: halt
------------

Stop Jeedom

jeedom :: reboot
--------------

Restart Jeedom

jeedom :: ISOK
------------

Lets you know if the global state of Jeedom is OK

jeedom :: update
--------------

Launch a Jeedom update

:: backup jeedom
--------------

Launch a backup of Jeedom

jeedom :: getUsbMapping
---------------------

List of USB ports and USB key names plugged in

JSON plugin API
===============

:: plugin installed
---------------

Installing / Updating a given plugin

Settings:

-   string plugin \ _id: plugin name (logical name)

plugin :: remove
--------------

Deleting a given plugin

Settings:

-   string plugin \ _id: plugin name (logical name)

plugin :: dependancyInfo
----------------------

Returns status information for plugin dependencies

Settings:

-   string plugin \ _id: plugin name (logical name)

plugin :: dependancyInstall
-------------------------

Force installation of plugin dependencies

Settings:

-   string plugin \ _id: plugin name (logical name)

plugin :: deamonInfo
------------------

Return the status information of the plugin daemon

Settings:

-   string plugin \ _id: plugin name (logical name)

plugin :: deamonStart
-------------------

Force the demon start

Settings:

-   string plugin \ _id: plugin name (logical name)

plugin :: deamonStop
------------------

Force the demon's stop

Settings:

-   string plugin \ _id: plugin name (logical name)

plugin :: deamonChangeAutoMode
----------------------------

Change the daemon management mode

Settings:

-   string plugin \ _id: plugin name (logical name)

-   int mode: 1 for automatic, 0 for manual

JSON API update
===============

:: update all
-----------

Back the list of all installed components, their version and the
related information

:: update checkUpdate
-------------------

Check for updates

:: update update
--------------

Let's update Jeedom and all plugins

JSON API network
================

network :: restartDns
-------------------

Force the restart of the Jeedom DNS

network :: stopDns
----------------

Force the stop of the DNS Jeedom

network :: dnsRun
---------------

Return Jeedom DNS Status

JSON API Examples
=================

Here is an example of using the API. For the example below
I use [this class
php] (https://github.com/jeedom/core/blob/stable/core/class/jsonrpcClient.class.php)
which simplifies the use of the API.

Retrieving the list of objects:

`` `{.php}
$ jsonrpc = new jsonrpcClient ('#JEEDOM_URL # / core / api / jeeApi.php', # API_KEY #);
if ($ jsonrpc-> sendRequest ('object :: all', array ())) {
    print_r ($ jsonrpc-> getResult ());
} Else {
    echo $ jsonrpc-> getError ();
}
`` `

Execution of a command (with the option of a title and a message)

`` `{.php}
$ jsonrpc = new jsonrpcClient ('#JEEDOM_URL # / core / api / jeeApi.php', # API_KEY #);
if ($ jsonrpc-> sendRequest ('cmd :: execCmd', array ('id' => # cmd_id #, 'options' => array (' title '=>' Cuckoo ',' message '=>' It works ')))) {
    echo 'OK';
} Else {
    echo $ jsonrpc-> getError ();
}
`` `

The API is of course usable with other languages ​​(just a post
on one page)
