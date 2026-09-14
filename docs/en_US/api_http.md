# HTTP API

Jeedom provides developers and users with a comprehensive API that allows them to control Jeedom from any connected object.

Two APIs are available: one designed for developers that uses JSON-RPC 2.0, and another that uses URLs and HTTP requests.

This API is very easy to use via simple HTTP requests using a URL.

> **Note**
>
> Throughout this documentation, \#IP\_JEEDOM\# refers to your Jeedom access URL. This is (unless you’re connected to your local network) the web address you use to access Jeedom from outside your network.

> **Note**
>
> Throughout this documentation, \#API\_KEY\# refers to your API key, which is specific to your installation. To find it, go to the "General" menu → "Configuration" → "General" tab.

> **Note**
>
> For POST requests, each query parameter can be sent in the request body in form-data or x-www-form-urlencoded format.
> Query parameters and the body content can be used together, but it's important to note that query parameters take precedence over the body content.

## Scenario

Here is the URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=scenario&id=\#ID\#&action=\#ACTION\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=scenario&id=#ID#&action=#ACTION#)

- **id**: corresponds to your scenario’s ID. The ID can be found on the page for the relevant scenario, under “Tools” → “Scenarios.” Once you’ve selected the scenario, it appears next to the “General” tab. Another way to find it is to go to “Tools” → “Scenarios” and click “Overview.”
- **action**: corresponds to the action you want to perform. The available commands are: "start," "stop," "disable," and "enable" to start, stop, disable, or enable the scenario, respectively.
- **tags** \[optional\]: if the action is "start," you can pass tags to the scenario (see the documentation on scenarios) in the format tags=toto%3D1%20tata%3D2 (note that %20 corresponds to a space and %3D to =).

> **Note**
>
> Don't try to use 'php://input' to pass data to your script; that's what tags are for.

##  Info/Command Action

Here is the URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=cmd&id=\#ID\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=cmd&id=#ID#)

- **id**: corresponds to the ID of the device you want to control or from which you want to receive information.

The easiest way to get this URL is to go to the **Analysis → Home Automation Summary** page, search for the command, then open its advanced settings (the "gear" icon). There, you’ll see a URL that already contains everything you need based on the command’s type and subtype.

> **Note**
>
> The \#ID\# field can be used to send multiple commands at once. To do this, you must pass an array in JSON format (e.g., %5B12,58,23%5D; note that \[ and \] must be encoded, hence %5B and %5D). Jeedom will return a JSON response.

> **Note**
>
> Parameters must be encoded for URLs. You can use a tool, [here](https://meyerweb.com/eric/tools/dencoder/).

## Interaction

Here is the URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=interact&query=\#QUERY\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=interact&query=#QUERY#)

- **query**: a question to ask Jeedom.
- **utf8** \[optional\]: tells Jeedom whether to encode the query in UTF-8 before attempting to respond.
- **emptyReply** \[optional\]: 0 to have Jeedom respond even if it didn't understand, 1 otherwise.
- **profile** \[optional\]: username of the person triggering the interaction.
- **reply\_cmd** \[optional\]: ID of the command to use to respond to the request.

## Message

Here is the URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=message&category=\#CATEGORY\#&message=\#MESSAGE\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=message&category=#CATEGORY#&message=#MESSAGE#)

- **category**: Category of the message to be added to the message center.
- **message**: the message in question; be sure to properly encode the message (spaces become %20, = becomes %3D, etc.). You can use a tool, [here](https://meyerweb.com/eric/tools/dencoder/).

## Object

Here is the URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=object](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=object)

Returns a list of all Jeedom objects in JSON format.

## Equipment

Here is the URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=eqLogic&object\_id=\#OBJECT\_ID\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=eqLogic&object_id=#OBJECT_ID#)

- **object\_id**: The ID of the object for which you want to retrieve the devices.

## Command

Here is the URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=command&eqLogic\_id=\#EQLOGIC\_ID\#](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=command&eqLogic_id=#EQLOGIC_ID#)

- **eqLogic\_id**: The ID of the device from which you want to retrieve commands.

## Full Data

Here is the URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=fullData](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=fullData)

Returns all objects, devices, and commands (and their values, if applicable) in JSON format.

## Variable

Here is the URL = [http://\#IP\_JEEDOM\#/core/api/jeeApi.php?apikey=\#APIKEY\#&type=variable&name=\#NAME\#&value=](http://#IP_JEEDOM#/core/api/jeeApi.php?apikey=#APIKEY#&type=variable&name=#NAME#&value=)*VALUE*

- **name**: the name of the variable whose value you want to retrieve (read the value).
- **value** \[optional\]: If "value" is specified, the variable will take on that value (writing a value).
