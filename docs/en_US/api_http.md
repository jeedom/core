# HTTP API

Jeedom provIDes developers and users with a complete API so that they can control Jeedom from any connected object.

Two APIs are available : a developer-oriented JSON RPC 2 pilot.0 and another via URL and HTTP request.

This API is very easy to use by simple HTTP requests via URL.

> **NOTE**
>
> For all this documentation, \#IP \ _JEEDOM \# corresponds to your Jeedom access url. This is (unless you are connected to your local network) the internet address that you use to access Jeedom from outsIDe.

> **NOTE**
>
> For all this documentation, \#API \ _KEY \# corresponds to your API key, specific to your installation. To find it, go to the "General" menu → "Configuration" → "General" tab".

## Scenario

Voici l'URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=scenario&ID=\#ID\#&Action=\#ACTION\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=scenario&ID=#ID#&Action=#ACTION#)

- **ID** : matches your scenario ID. The ID can be found on the relevant scenario page, in "Tools" → "Scenarios", once the scenario has been selected, next to the name of the "General" tab". Another way to find it : in "Tools" → "Scenarios", click on "Overview".
- **Action** : corresponds to the Action you want to apply. Available commands are : "start "," stop "," deactivate "and" activate "to start, stop, deactivate or activate the scenario, respectively.
- **tags** \[optional\] : if the Action is &quot;start&quot;, you can pass tags to the scenario (see the documentation on the scenarios) in the form tags = toto% 3D1% 20tata% 3D2 (note that% 20 corresponds to a space and% 3D to =).

##  Info / Action command

Voici l'URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=cmd&ID=\#ID\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=cmd&ID=#ID#)

- **ID** : corresponds to the ID of what you want to control or from which you want to receive information.

The easiest way to get this URL is to go to the page **Analysis → Home automation summary**, to search for the order then to open its advanced configuration (the "gear" icon) and there, you will see a URL which already contains everything you need depending on the type and subtype of the order.

> **NOTE**
>
> It is possible for the \#ID \# field to place several commands at once. To do this, you must pass an array in json (ex% 5B12,58,23% 5D, note that \ [and \] must be encoded, hence the% 5B and% 5D). Jeedom&#39;s return will be a json.

> **NOTE**
>
> Les paramètres doivent être encodés pour les url, Vous pouvez utiliser un outil, [ici](https://meyerweb.com/eric/tools/dencoder/).

## InterAction

Voici l'URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=interact&query=\#QUERY\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=interact&query=#QUERY#)

- **query** : question to ask Jeedom.
- **utf8** \[optional\] : tells Jeedom whether to encode query in utf8 before trying to answer.
- **emptyReply** \[optional\] : 0 for Jeedom to respond even if he dID not understand, 1 otherwise.
- **profile** \[optional\] : username of the person initiating the interAction.
- **reply \ _cmd** \[optional\] : Order ID to use to respond to the request.

## Message

Voici l'URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=Message&category=\#CATEGORY\#&Message=\#MESSAGE\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=Message&category=#CATEGORY#&Message=#MESSAGE#)

- **category** : Message category to add to Message center.
- **Message** : Message in question, be careful to think about encoding the Message (space becomes% 20, =% 3D…). Vous pouvez utiliser un outil, [ici](https://meyerweb.com/eric/tools/dencoder/).

## Object

Voici l'URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=object](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=object)

Returns in json the list of all Jeedom objects.

## Equipment

Voici l'URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=eqLogic&object \ _ID=\#OBJECT\_ID\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=eqLogic&object_ID=#OBJECT_ID#)

- **object \ _ID** : ID of the object whose equipment we want to recover.

## Ordered

Voici l'URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=command&eqLogic \ _ID=\#EQLOGIC\_ID\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=command&eqLogic_ID=#EQLOGIC_ID#)

- **eqLogic \ _ID** : ID of the equipment from which orders are to be retrieved.

## Full Data

Voici l'URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=fullData](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=fullData)

Returns all objects, equipment, commands (and their value if they are info) in json.

## Variable

Voici l'URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=variable&name=\#NAME\#&value=](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=variable&name=#NAME#&value=)*VALUE*

- **name** : name of the variable whose value is wanted (reading the value).
- **value** \[optional\] : if &quot;value&quot; is specified then the variable will take this value (writing a value).
