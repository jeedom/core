# HTTP API

Jeedom provides developers and users with a complete API so that they can control Jeedom from any connected object.

Two APIs are available : a developer-oriented JSON RPC 2 pilot.0 and another via URL and HTTP request.

This API is very easy to use by simple HTTP requests via URL.

> ****
>
> For all of this documentation, \#IP\_JEEDOM\# corresponds to your Jeedom access url. This is (unless you are connected to your local network) the internet address that you use to access Jeedom from outside.

> ****
>
> For all of this documentation, \#API\_KEY\# corresponds to your API key, specific to your installation. To find it, go to the "General" menu → "Configuration" → "General" tab".

## Scenario

Vo l'URL = [http://\#IP\_JEEDOM\#?apikey=\#APIKEY\#& type = scenario & id = \#ID\#&action=\#ACTION\#](http://#IP_JEEDOM#?apikey=#APIKEY#& type = scenario & id=#ID#&action=#ACTION#)

- **** : matches your scenario id. The ID can be found on the relevant scenario page, in "Tools" → "Scenarios", once the scenario has been selected, next to the name of the "General" tab". Another way to find it : in "Tools" → "Scenarios", click on "Overview".
- **** : corresponds to the action you want to apply. Available commands are : "start ", " stop ", " disable "and "enable" to respectively start, stop, disable or enable the scenario.
- **** \[optional\] : if the action is "start", you can pass tags to the scenario (see the documentation on the scenarios) in the form tags = toto% 3D1% 20tata% 3D2 (note that% 20 corresponds to a space and% 3D to = ).

##  Info / Action command

Vo l'URL = [http://\#IP\_JEEDOM\#?apikey=\#APIKEY\#& type = cmd & id = \#ID\#](http://#IP_JEEDOM#?apikey=#APIKEY#& type = cmd & id=#ID#)

- **** : corresponds to the id of what you want to control or from which you want to receive information.

The easiest way to get this URL is to go to the page **Analysis → Home automation summary**, to search for the command then to open its advanced configuration (the "gear" icon) and there, you will see a URL which already contains everything you need depending on the type and subtype of the command.

> ****
>
> It is possible for the field \#ID\# place multiple commands at once. To do this, you must pass an array in json (ex% 5B12,58,23% 5D, note that \ [and \] must be encoded, hence the% 5B and% 5D). Jeedom&#39;s return will be a json.

> ****
>
> Parameters must be encoded for urls, You can use a tool, [](https://meyerweb.com/eric/tools/dencoder/).

## Interaction

Vo l'URL = [http://\#IP\_JEEDOM\#?apikey=\#APIKEY\#& type = interact & query = \#QUERY\#](http://#IP_JEEDOM#?apikey=#APIKEY#& type = interact & query=#QUERY#)

- **** : question to ask Jeedom.
- **** \[optional\] : tells Jeedom whether to encode query in utf8 before trying to answer.
- **** \[optional\] : 0 for Jeedom to respond even if he did not understand, 1 otherwise.
- **** \[optional\] : username of the person initiating the interaction.
- **** \[optional\] : Command ID to use to respond to the request.

## Message

Vo l'URL = [http://\#IP\_JEEDOM\#?apikey=\#APIKEY\#& type = message & category = \#CATEGORY\#&message=\#MESSAGE\#](http://#IP_JEEDOM#?apikey=#APIKEY#& type = message & category=#CATEGORY#&message=#MESSAGE#)

- **** : message category to add to message center.
- **** : message in question, be careful to think about encoding the message (space becomes% 20, =% 3D…). You can use a tool, [](https://meyerweb.com/eric/tools/dencoder/).

## Objet

Vo l'URL = [http://\#IP\_JEEDOM\#?apikey=\#APIKEY\#& type = object](http://#IP_JEEDOM#?apikey=#APIKEY#& type = object)

Returns in json the list of all Jeedom objects.

## Equipement

Vo l'URL = [http://\#IP\_JEEDOM\#?apikey=\#APIKEY\#& type = eqLogic & object\_id = \#OBJECT\_ID\#](http://#IP_JEEDOM#?apikey=#APIKEY#& type = eqLogic & object_id=#OBJECT_ID#)

- **** : ID of the object whose equipment we want to recover.

## Commande

Vo l'URL = [http://\#IP\_JEEDOM\#?apikey=\#APIKEY\#& type = command & eqLogic\_id = \#EQLOGIC\_ID\#](http://#IP_JEEDOM#?apikey=#APIKEY#& type = command & eqLogic_id=#EQLOGIC_ID#)

- **** : ID of the equipment from which command are to be retrieved.

## 

Vo l'URL = [http://\#IP\_JEEDOM\#?apikey=\#APIKEY\#& type = fullData](http://#IP_JEEDOM#?apikey=#APIKEY#& type = fullData)

Returns all objects, equipment, commands (and their value if they are info) in json.

## Variable

Vo l'URL = [http://\#IP\_JEEDOM\#?apikey=\#APIKEY\#& type = variable & name = \#NAME\#&value=](http://#IP_JEEDOM#?apikey=#APIKEY#& type = variable & name=#NAME#&value=)**

- **** : name of the variable whose value is desired (reading the value).
- **** \[optional\] : if "value" is specified then the variable will take this value (writing a value).
