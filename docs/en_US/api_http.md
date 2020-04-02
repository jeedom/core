Jeedom provIDes developers and users with an API
complete so you can control Jeedom from any object
connected.

Two APIs are available : a developer oriented pilot
JSON RPC 2.0 and another via URLs and HTTP request.

This API is very easily used by simple HTTP requests via
URLs.

> **NOTE**
>
> For all this documentation, \ #IP \ _JEEDOM \ # corresponds to your url
> access to Jeedom. This is (unless you are connected to your network
> local) of the internet address you use to access Jeedom
> from the outsIDe.

> **NOTE**
>
> For all this documentation, \ #API \ _KEY \ # corresponds to your key
> API, specific to your installation. To find it, you have to go to
> the "General" menu → "Configuration" → "General" tab".

Scenario 
========

Here is the URLs =
[http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=scenario&ID=\#ID\#&Action=\#ACTION\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=scenario&ID=#ID#&Action=#ACTION#)

-   **ID** : matches your scenario ID. The ID is on the
    page of the scenario concerned, in &quot;tools&quot; → &quot;Scenarios&quot;, once the
    selected scenario, next to the name of the &quot;General&quot; tab. Other
    way to find it : in &quot;Tools&quot; → &quot;Scenarios&quot;, click on
    "Overview".

-   **Action** : corresponds to the Action you want to apply. The
    available orders are : "start "," stop "," deactivate "and
    "activate "to start, stop, deactivate or
    activate the scenario.

-   **tags** \[optional\] : if the Action is &quot;start&quot;, you can skip
    tags to the scenario (see documentation on scenarios) under
    the form tags = toto% 3D1% 20tata% 3D2 (note that% 20 corresponds to a
    space and% 3D to =)

Info / Action command 
====================

Here is the URLs =
[http://\#IP\_JEEDOM\#/jeedom/core/api/jeeApi.php?apikey=\#APIKEY\#&type=cmd&ID=\#ID\#](http://#IP_JEEDOM#/jeedom/core/api/jeeApi.php?apikey=#APIKEY#&type=cmd&ID=#ID#)

-   **ID** : corresponds to the ID of what you want to drive or from which
    you wish to receive information

The easiest way to get this URLs is to go to the Tools page →
Home automation summary, to search for the command then to open its configuration
advanced (the &quot;gear&quot; icon) and there you will see a URLs that contains
already all you need depending on the type and subtype of the
ordered.

> **NOTE**
>
> It is possible for the \ #ID \ # field to place multiple commands
> at once. To do this, you must pass an array in json (ex
> % 5B12,58,23% 5D, note that \ [and \] must be encoded, hence the% 5B
> and% 5D). Jeedom&#39;s return will be a json

> **NOTE**
>
> Parameters must be encoded for url, You can use
> a tool, [here] (https://meyerweb.com/eric/tools/dencoder/)

InterAction 
===========

Here is the URLs =
[http://\#IP\_JEEDOM\#/jeedom/core/api/jeeApi.php?apikey=\#APIKEY\#&type=interact&query=\#QUERY\#](http://#IP_JEEDOM#/jeedom/core/api/jeeApi.php?apikey=#APIKEY#&type=interact&query=#QUERY#)

-   **query** : question to ask Jeedom

-   **utf8** \[optional\] : tells Jeedom whether to encode query
    in utf8 before trying to answer

-   **emptyReply** \[optional\] : 0 for Jeedom to respond even if it
    dID not understand, 1 otherwise

-   **profile** \[optional\] : person&#39;s username
    triggering interAction

-   **reply \ _cmd** \[optional\] : Command ID to use for
    respond to the request

Message 
=======

Here is the URLs =
[http://\#IP\_JEEDOM\#/jeedom/core/api/jeeApi.php?apikey=\#APIKEY\#&type=Message&category=\#CATEGORY\#&Message=\#MESSAGE\#](http://#IP_JEEDOM#/jeedom/core/api/jeeApi.php?apikey=#APIKEY#&type=Message&category=#CATEGORY#&Message=#MESSAGE#)

-   **category** : Message category to add to Message center

-   **Message** : Message in question, be careful to think about encoding
    the Message (space becomes% 20, =% 3D…). You can use a
    tool, [here] (https://meyerweb.com/eric/tools/dencoder/)

Object 
=====

Here is the URLs =
[http://\#IP\_JEEDOM\#/jeedom/core/api/jeeApi.php?apikey=\#APIKEY\#&type=object](http://#IP_JEEDOM#/jeedom/core/api/jeeApi.php?apikey=#APIKEY#&type=object)

Returns in json the list of all Jeedom objects

Equipment 
==========

Here is the URLs =
[http://\#IP\_JEEDOM\#/jeedom/core/api/jeeApi.php?apikey=\#APIKEY\#&type=eqLogic&object \ _ID=\#OBJECT\_ID\#](http://#IP_JEEDOM#/jeedom/core/api/jeeApi.php?apikey=#APIKEY#&type=eqLogic&object_ID=#OBJECT_ID#)

-   **object \ _ID** : ID of the object from which we want to retrieve
    amenities

Ordered 
========

Here is the URLs =
[http://\#IP\_JEEDOM\#/jeedom/core/api/jeeApi.php?apikey=\#APIKEY\#&type=command&eqLogic \ _ID=\#EQLOGIC\_ID\#](http://#IP_JEEDOM#/jeedom/core/api/jeeApi.php?apikey=#APIKEY#&type=command&eqLogic_ID=#EQLOGIC_ID#)

-   **eqLogic \ _ID** : ID of the equipment from which we want to recover
    orders

Full Data 
=========

Here is the URLs =
[http://\#IP\_JEEDOM\#/jeedom/core/api/jeeApi.php?apikey=\#APIKEY\#&type=fullData](http://#IP_JEEDOM#/jeedom/core/api/jeeApi.php?apikey=#APIKEY#&type=fullData)

Returns all objects, equipment, commands (and their value if this
are infos) in json

Variable 
========

Here is the URLs =
[http://\#IP\_JEEDOM\#/jeedom/core/api/jeeApi.php?apikey=\#APIKEY\#&type=variable&name=\#NAME\#&value=](http://#IP_JEEDOM#/jeedom/core/api/jeeApi.php?apikey=#APIKEY#&type=variable&name=#NAME#&value=)*VALUE*

-   **name** : name of the variable whose value is wanted (reading of
    the value)

-   **value** \[optional\] : if &quot;value&quot; is specified then the variable
    will take this value (writing a value)


