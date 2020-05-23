# Scenarios
**Tools → Scenarios**

<small>[Raccourcis clavier/souris](shortcuts.md)</small>

Real brain of home automation, the scenarios allow to interact with the real world in a way *smart*.

## Gestion

You will find there the list of scenarios of your Jeedom, as well as functionalities to manage them at best :

- **Add** : Create a scenario. The procedure is described in the next chapter.
- **Disable scenarios** : Disables all scenarios. Rarely used and knowingly, since no scenario will run anymore.
- **Overview** : Allows you to have an overview of all scenarios. You can change the values **active**, **Jeedom**, **multi launch**, **synchronous mode**, **Log** and **Timeline** (these parameters are described in the following chapter). You can also access the logs for each scenario and start them individually.

## My scenarios

In this section you will find the **list of scenarios** that you created. They are classified according to their **Group**, possibly defined for each of them. Each scenario is displayed with its **last name** and his **parent object**. The **grayed out scenarios** are the ones that are disabled.

> **Tip**
>
> You can open a scenario by doing :
> - Click on one of them.
> - Ctrl Clic or Clic Center to open it in a new browser tab.

You have a search engine to filter the display of scenarios. The Escape key cancels the search.
To the right of the search field, three buttons found in several places in Jeedom:
- The cross to cancel the search.
- The open folder to unfold all the panels and display all the scenarios.
- The closed folder to fold all the panels.

Once on the configuration of a scenario, you have a contextual menu with the Right Click on the tabs of the scenario. You can also use a Ctrl Click or Click Center to directly open another scenario in a new browser tab.

## Creation | Editing a scenario

After clicking on **Add**, you must choose the name of your scenario. You are then redirected to the page of its general parameters.
Before that, at the top of the page, there are some useful functions to manage this scenario :

- **Id** : Next to the word **General**, this is the scenario identifier.
- **status** : *Stopped* or *Running*, it indicates the current state of the scenario.
- **Previous / next state** : Cancel / redo an action.
- **Add a block** : Allows you to add a block of the desired type to the scenario (see below).
- **Log** : Displays the scenario logs.
- **Duplicate** : Copy the scenario to create a new one with another name.
- **Connections** : Allows you to view the graph of the elements related to the scenario.
- **Text editing** : Displays a window allowing to edit the scenario in the form of text / json. Don&#39;t forget to save.
- **Export** : Allows you to obtain a pure text version of the scenario.
- **Template** : Allows you to access the templates and apply one to the scenario from the market. (explained at the bottom of the page).
- **Research** : Unfolds a search field to search in the scenario. This search unfolds the collapsed blocks if necessary and folds them back after the search.
- **Perform** : Allows you to launch the scenario manually (regardless of the triggers). Save beforehand to take into account the modifications.
- **Remove** : Delete scenario.
- **Save** : Save the changes made.

> **TIPS**
>
> Two tools will also be invaluable to you in setting up scenarios :
    > - The variables, visible in **Tools → Variables**
    > - The expression tester, accessible by **Tools → Expression tester**
>
> A **Ctrl Click on the execute button** allows you to directly save, execute and display the scenario log (if the log level is not None).

## General tab

In the tab **General**, we find the main parameters of the scenario :

- **Scenario name** : The name of your scenario.
- **Name to display** : The name used for its display. Optional, if not completed, the name of the scenario is used.
- **Group** : Allows you to organize the scenarios, by classifying them in groups (visible on the scenarios page and in their contextual menus).
- **Active** : Activate the scenario. If not active, it will not be executed by Jeedom, regardless of the trigger mode.
- **Jeedom** : Used to make the scenario visible (Dashboard).
- **Parent object** : Assignment to a parent object. It will then be visible or not according to this parent.
- **Timeout in seconds (0 = unlimited)** : The maximum execution time allowed for this scenario. Beyond this time, the execution of the scenario is interrupted.
- **Multi launch** : Check this box if you want the scenario to be able to be launched several times at the same time.
>**IMPORTANT**
>
>The multi-launch works by the second, that is to say that if you have 2 launches in the same second without the box checked there will still be 2 launches of the scenario (when it should not). Similarly during several launches in the same second it is possible that some launches lose the tags. Conclusion you MUST ABSOLUTELY avoid multiple launches in the same seconds.
- **Synchronous mode** : Start the scenario in the current thread instead of a dedicated thread. Increases the speed at which the scenario is launched, but can make the system unstable.
- **Log** : The type of log desired for the scenario. You can cut the log of the scenario or on the contrary make it appear in Analysis → Real time.
- **Timeline** : Keep a follow-up of the scenario in the timeline (see History doc).
- **Icon** : Allows you to choose an icon for the scenario instead of the standard icon.
- **Description** : Allows you to write a small text to describe your scenario.
- **Scenario mode** : The scenario can be programmed, triggered or both. You will then have the choice to indicate the trigger (s) (15 triggers maximum) and the programming (s)).

> **Tip**
>
> Conditions can now be entered in triggered mode. Eg : ``#[Garage][Open Garage][Ouverture]# == 1``
> Warning : you can have a maximum of 28 triggers / programming for a scenario.

> **Tip mode programmed**
>
> Scheduled mode uses syntax **Cron**. For example, you can run a scenario every 20 minutes with  ``*/20 * * * *``, or at 5 a.m. to settle a multitude of things for the day with ``0 5 * * *``. The ? to the right of a program allows you to set it without being a specialist in Cron syntax.

## Scenario tab

This is where you will build your scenario. After creating the scenario, its content is empty, so it will do ... nothing. You have to start with **add a block**, with the button on the right. Once a block has been created, you can add another **block** or a **Action**.

For more convenience and not having to constantly reorder the blocks in the scenario, adding a block is done after the field in which the mouse cursor is located.
*For example, if you have ten blocks, and you click in the IF condition of the first block, the added block will be added after block, at the same level. If no field is active, it will be added at the end of the scenario.*

> **Tip**
>
> In conditions and actions, it is better to favor single quotes (') instead of double (").

> **Tip**
>
> A Ctrl Shift Z or Ctrl Shift Y allows you to'**Cancel** or redo a modification (adding action, block...).

## Blocks

Here are the different types of blocks available :

- **If / Then / Or** : Allows you to perform actions under condition (if this, then that).
- **Action** : Allows you to launch simple actions without any conditions.
- **Loop** : Allows actions to be performed repeatedly from 1 to a defined number (or even the value of a sensor, or a random number…).
- **In** : Allows to launch an action in X minute (s) (0 is a possible value). The peculiarity is that the actions are launched in the background, so they do not block the rest of the scenario. So it&#39;s a non-blocking block.
- **AT** : Allows to tell Jeedom to launch the actions of the block at a given time (in the form hhmm). This block is non-blocking. Ex : 0030 for 00:30, or 0146 for 1h46 and 1050 for 10h50.
- **Coded** : Allows you to write directly in PHP code (requires certain knowledge and can be risky but allows you to have no constraints).
- **Comment** : Allows you to add comments to your scenario.

Each block has its options to better handle them :

- On the left :
    - The bidirectional arrow allows you to move a block or an action to reorder them in the scenario.
    - The eye reduces a block (*collapse*) to reduce its visual impact. Ctrl Click on the eye reduce them or display them all.
    - The check box allows you to completely deactivate the block without deleting it. It will therefore not be executed.

- On the right :
    - The Copy icon allows you to copy the block to make a copy elsewhere. Ctrl Click on the icon cuts the block (copy then delete).
    - The Paste icon allows you to paste a copy of the block previously copied after the block on which you use this function.  Ctrl Click on the icon replaces the block with the copied block.
    - The icon - allows you to delete the block, with a confirmation request. Ctrl Click deletes the block without confirmation.

### If / Then / Otherwise blocks | Loop | In | A

For the conditions, Jeedom tries to make it possible to write them as much as possible in natural language while remaining flexible.
> DO NOT use [] in condition tests, only parentheses () are possible.

Three buttons are available on the right of this type of block to select an item to test :

- **Find an order** : Allows you to search for an order in all those available in Jeedom. Once the order is found, Jeedom opens a window to ask you what test you want to perform on it. If you choose to **Put nothing**, Jeedom will add the order without comparison. You can also choose **and** or **or** in front of **Then** to chain tests on different equipment.
- **Search a scenario** : Allows you to search for a scenario to test.
- **Search for equipment** : Same for equipment.

> **NOTE**
>
> On blocks of type If / Then / Otherwise, circular arrows to the left of the condition field allow to activate or not the repetition of actions if the evaluation of the condition gives the same result as during the previous evaluation.

> **Tip**
>
> There is a list of tags allowing access to variables from the scenario or another, or by the time, the date, a random number,… See below the chapters on commands and tags.

Once the condition is completed, you must use the &quot;add&quot; button on the left to add a new **block** or a **Action** in the current block.


### Block Code

The Code block allows you to execute php code. It is therefore very powerful but requires a good knowledge of the php language.

#### Access to controls (sensors and actuators)

-  ``cmd::byString($string);`` : Returns the corresponding command object.
    -   ``$string``: Link to the desired order : ``#[objet][Equipment][commande]#`` (Ex : ``#[Appartement][Alarme][Active]#``)
-  ``cmd::byId($id);`` : Returns the corresponding command object.
    -  ``$id`` : Order ID.
-  ``$cmd->execCmd($options = null);`` : Execute the command and return the result.
    - ``$options`` : Options for the execution of the command (can be specific to the plugin). Basic options (command subtype) :
        -  ``message`` : ``$option = array('title' => 'titre du Message , 'message' => 'Mon message');``
        -  ``color`` : ``$option = array('color' => 'couleur en hexadécimal');``
        -  ``slider`` : ``$option = array('slider' => 'valeur voulue de 0 à 100');``

#### Access to log

-  ``log::add('filename','level','message');``
    - ``filename`` : Log file name.
    - ``level`` : [debug], [info], [error], [event].
    - ``message`` : Message to write in the logs.

#### Access to scenario

- ``$scenario->getName();`` : Returns the name of the current scenario.
- ``$scenario->getGroup();`` : Returns the scenario group.
- ``$scenario->getIsActive();`` : Returns the state of the scenario.
- ``$scenario->setIsActive($active);`` : Allows you to activate or not the scenario.
    - ``$active`` : 1 active, 0 not active.
- ``$scenario->setOnGoing($onGoing);`` : Lets say if the scenario is running or not.
    - ``$onGoing => 1`` : 1 in progress, 0 stopped.
- ``$scenario->save();`` : Save changes.
- ``$scenario->setData($key, $value);`` : Save a data (variable).
    - ``$key`` : value key (int or string).
    - ``$value`` : value to store (``int``, ``string``, ``array`` or ``object``).
- ``$scenario->getData($key);`` : Get data (variable).
    - ``$key => 1`` : value key (int or string).
- ``$scenario->removeData($key);`` : Delete data.
- ``$scenario->setLog($message);`` : Write a message in the scenario log.
- ``$scenario->persistLog();`` : Force the writing of the log (otherwise it is written only at the end of the scenario). Be careful, this can slow the scenario down a bit.

> **Tip**
>
> Addition of a search function in the Code block : Search : Ctrl + F then Enter, Next result : Ctrl + G, Previous result : Ctrl + Shift + G

[Scenarios : Little codes with friends](https://kiboost.github.io/jeedom_docs/jeedomV4Tips/CodesScenario/)

### Comment block

Comment block acts differently when it is hidden. Its buttons on the left disappear as well as the title of the block, and reappear on hover. Similarly, the first line of the comment is displayed in bold type.
This allows this block to be used as a purely visual separation within the scenario.

### The actions

Actions added to blocks have several options :

- A box **activated** so that this command is taken into account in the scenario.
- A box **parallel** so that this command is launched in parallel (at the same time) with the other commands also selected.
- A **vertical double arrow** to move the action. Just drag and drop from there.
- A button for **Remove** the action.
- A button for specific actions, with each time the description (on hover) of this action.
- A button to search for an action command.

> **Tip**
>
> Depending on the selected command, you can see different additional fields displayed.

## Possible substitutions

### Triggers

There are specific triggers (other than those provided by commands) :

- ``#start#`` : Triggered at (re) start of Jeedom.
- ``#begin_backup#`` : event sent at the start of a backup.
- ``#end_backup#`` : event sent at the end of a backup.
- ``#begin_update#`` : event sent at the start of an update.
- ``#end_update#`` : event sent at the end of an update.
- ``#begin_restore#`` : event sent at the start of a restoration.
- ``#end_restore#`` : event sent at the end of a restoration.
- ``#user_connect#`` : User login

You can also trigger a scenario when a variable is updated by putting : #variable(nom_variable)# or using the HTTP API described [here](https://doc.jeedom.com/en_US/core/4.1/api_http).

### Comparison operators and links between conditions

You can use any of the following symbols for comparisons in conditions :

- ``==`` : Equal to.
- ``>`` : Strictly greater than.
- ``>=`` : Greater than or equal to.
- ``<`` : Strictly less than.
- ``<=`` : Less than or equal to.
- ``!=`` : Different from, is not equal to.
- ``matches`` : Contains. Ex : ``[Salle de bain][Hydrometrie][etat] matches "/humide/"``.
- ``not ( …​ matches …​)`` : Does not contain. Ex :  ``not([Salle de bain][Hydrometrie][etat] matches "/humide/")``.

You can combine any comparison with the following operators :

- ``&&`` / ``ET`` / ``et`` / ``AND`` / ``and`` : et,
- ``||`` / ``OU`` / ``ou`` / ``OR`` / ``or`` : ou,
- ``^`` / ``XOR`` / ``xor`` : or exclusive.

### Tags

A tag is replaced during the execution of the scenario by its value. You can use the following tags :

> **Tip**
>
> To have the leading zeros displayed, use the Date () function. See [here](http://php.net/manual/fr/function.date.php).

- ``#seconde#`` : Current second (without leading zeros, ex : 6 for 08:07:06).
- ``#hour#`` : Current time in 24h format (without leading zeros). Ex : 8 for 08:07:06 or 17 for 17:15.
- ``#hour12#`` : Current time in 12-hour format (without leading zeros). Ex : 8 for 08:07:06.
- ``#minute#`` : Current minute (without leading zeros). Ex : 7 for 08:07:06.
- ``#day#`` : Current day (without leading zeros). Ex : 6 for 06/07/2017.
- ``#month#`` : Current month (without leading zeros). Ex : 7 for 06/07/2017.
- ``#year#`` : Current year.
- ``#time#`` : Current hour and minute. Ex : 1715 for 5.15 p.m.
- ``#timestamp#`` : Number of seconds since January 1, 1970.
- ``#date#`` : Day and month. Attention, the first number is the month. Ex : 1215 for December 15.
- ``#week#`` : Week number.
- ``#sday#`` : Name of day of week. Ex : Saturday.
- ``#nday#`` : Day number from 0 (Sunday) to 6 (Saturday).
- ``#smonth#`` : Name of the month. Ex : January.
- ``#IP#`` : Jeedom&#39;s internal IP.
- ``#hostname#`` : Jeedom machine name.
- ``#trigger#`` (deprecated, better to use ``trigger()``) : Maybe the name of the command that started the scenario :
    - ``api`` if the launch was triggered by the API,
    - ``schedule`` if it was started by programming,
    - ``user`` if it was started manually,
    - ``start`` for a launch at startup of Jeedom.
- ``#trigger_value#`` (deprecated, better to use triggerValue()) : For the value of the command that triggered the scenario

You also have the following additional tags if your scenario was triggered by an interaction :

- #query# : Interaction that triggered the scenario.
- #profil# : Profile of the user who started the scenario (can be empty).

> **IMPORTANT**
>
> When a scenario is triggered by an interaction, it is necessarily executed in fast mode. So in the interaction thread and not in a separate thread.

### Calculation functions

Several functions are available for the equipment :

- ``average(commande,période)`` and ``averageBetween(commande,start,end)`` : Give the average of the order over the period (period=[month, day, hour, min] or [PHP expression](http://php.net/manual/fr/datetime.formats.relative.php)) or between the 2 required terminals (in the form Ymd H:i:s or [PHP expression](http://php.net/manual/fr/datetime.formats.relative.php)).

- ``min(commande,période)`` and ``minBetween(commande,start,end)`` : Give the minimum order over the period (period=[month, day, hour, min] or [PHP expression](http://php.net/manual/fr/datetime.formats.relative.php)) or between the 2 required terminals (in the form Ymd H:i:s or [PHP expression](http://php.net/manual/fr/datetime.formats.relative.php)).

- ``max(commande,période)`` and ``maxBetween(commande,start,end)`` : Give the maximum of the order over the period (period=[month, day, hour, min] or [PHP expression](http://php.net/manual/fr/datetime.formats.relative.php)) or between the 2 required terminals (in the form Ymd H:i:s or [PHP expression](http://php.net/manual/fr/datetime.formats.relative.php)).

- ``duration(commande, valeur, période)`` and ``durationbetween(commande,valeur,start,end)`` : Give the duration in minutes during which the equipment had the chosen value over the period (period=[month, day, hour, min] or [PHP expression](http://php.net/manual/fr/datetime.formats.relative.php)) or between the 2 required terminals (in the form Ymd H:i:s or [PHP expression](http://php.net/manual/fr/datetime.formats.relative.php)).

- ``statistics(commande,calcul,période)`` and ``statisticsBetween(commande,calcul,start,end)`` : Give the result of different statistical calculations (sum, count, std, variance, avg, min, max) over the period (period=[month, day, hour, min] or [PHP expression](http://php.net/manual/fr/datetime.formats.relative.php)) or between the 2 required terminals (in the form Ymd H:i:s or [PHP expression](http://php.net/manual/fr/datetime.formats.relative.php)).

- ``tendance(commande,période,seuil)`` : Gives the trend of the order over the period (period=[month, day, hour, min] or [PHP expression](http://php.net/manual/fr/datetime.formats.relative.php)).

- ``stateDuration(commande)`` : Gives the duration in seconds since the last change in value.
    -1 : No history exists or value does not exist in history.
    -2 : The order is not logged.

- ``lastChangeStateDuration(commande,valeur)`` : Gives the duration in seconds since the last change of state to the value passed in parameter.
    -1 : No history exists or value does not exist in history.
    -2 The order is not logged

- ``lastStateDuration(commande,valeur)`` : Gives the duration in seconds during which the equipment has last had the chosen value.
    -1 : No history exists or value does not exist in history.
    -2 : The order is not logged.

- ``age(commande)`` : Gives the age in seconds of the value of the command (``collecDate``)
    -1 : The command does not exist or it is not of type info.

- ``stateChanges(commande,[valeur], période)`` and ``stateChangesBetween(commande, [valeur], start, end)`` : Give the number of state changes (towards a certain value if indicated, or in total if not) over the period (period=[month, day, hour, min] or [PHP expression](http://php.net/manual/fr/datetime.formats.relative.php)) or between the 2 required terminals (in the form Ymd H:i:s or [PHP expression](http://php.net/manual/fr/datetime.formats.relative.php)).

- ``lastBetween(commande,start,end)`` : Gives the last value recorded for the equipment between the 2 requested terminals (in the form Ymd H:i:s or [PHP expression](http://php.net/manual/fr/datetime.formats.relative.php)).

- ``variable(mavariable,valeur par défaut)`` : Retrieves the value of a variable or the desired value by default.

- ``scenario(scenario)`` : Returns the status of the scenario.
    1 : Running,
    0 : Stopped,
    -1 : Disabled,
    -2 : The scenario does not exist,
    -3 : State is not consistent.
    To have the &quot;human&quot; name of the scenario, you can use the dedicated button to the right of the scenario search.

- ``lastScenarioExecution(scenario)`` : Gives the duration in seconds since the last launch of the scenario.
    0 : The scenario does not exist

- ``collectDate(cmd,[format])`` : Returns the date of the last data for the command given in parameter, the 2nd optional parameter allows to specify the return format (details [here](http://php.net/manual/fr/function.date.php)).
    -1 : The command could not be found,
    -2 : The command is not of type info.

- ``valueDate(cmd,[format])`` : Returns the date of the last data for the command given in parameter, the 2nd optional parameter allows to specify the return format (details [here](http://php.net/manual/fr/function.date.php)).
    -1 : The command could not be found,
    -2 : The command is not of type info.

- ``eqEnable(equipement)`` : Returns the status of the equipment.
    -2 : The equipment cannot be found,
    1 : Equipment is active,
    0 : The equipment is inactive.

- ``value(cmd)`` : Returns the value of an order if it is not automatically given by Jeedom (case when storing the name of the order in a variable)

- ``tag(montag,[defaut])`` : Used to retrieve the value of a tag or the default value if it does not exist.

- ``name(type,commande)`` : Used to retrieve the name of the order, equipment or object. Type : cmd, eqLogic or object.

- ``lastCommunication(equipment,[format])`` : Returns the date of the last communication for the equipment given as a parameter, the 2nd optional parameter allows you to specify the return format (details [here](http://php.net/manual/fr/function.date.php)). A return of -1 means that the equipment cannot be found.

- ``color_gradient(couleur_debut,couleur_fin,valuer_min,valeur_max,valeur)`` : Returns a color calculated with respect to value in the range color_start / color_end. The value must be between min_value and max_value.

The periods and intervals of these functions can also be used with [PHP expressions](http://php.net/manual/fr/datetime.formats.relative.php) For example :

- ``Now`` : now.
- ``Today`` : 00:00 today (allows for example to obtain results for the day if between ``Today`` and ``Now``).
- ``Last Monday`` : last Monday at 00:00.
- ``5 days ago`` : 5 days ago.
- ``Yesterday noon`` : yesterday afternoon.
- Etc..

Here are practical examples to understand the values returned by these different functions :

| Socket with values :           | 000 (for 10 minutes) 11 (for 1 hour) 000 (for 10 minutes)    |
|--------------------------------------|--------------------------------------|
| ``average(prise,période)``             | Returns the average of 0 and 1 (can  |
|                                      | be influenced by polling)      |
| ``averageBetween(#[Salle de bain][Hydrometrie][Humidité]#,2015-01-01 00:00:00,2015-01-15 00:00:00)`` | Returns the average order between January 1, 2015 and January 15, 2015                       |
| ``min(prise,période)``                 | Returns 0 : the plug was extinguished during the period              |
| ``minBetween(#[Salle de bain][Hydrometrie][Humidité]#,2015-01-01 00:00:00,2015-01-15 00:00:00)`` | Returns the minimum order between January 1, 2015 and January 15, 2015                       |
| ``max(prise,période)``                 | Returns 1 : the plug was well lit in the period              |
| ``maxBetween(#[Salle de bain][Hydrometrie][Humidité]#,2015-01-01 00:00:00,2015-01-15 00:00:00)`` | Returns the maximum of the order between January 1, 2015 and January 15, 2015                       |
| ``duration(prise,1,période)``          | Returns 60 : the plug was on (at 1) for 60 minutes in the period                              |
| ``durationBetween(#[Salon][Prise][Etat]#,0,Last Monday,Now)``   | Returns the duration in minutes during which the socket was off since last Monday.                |
| ``statistics(prise,count,période)``    | Returns 8 : there were 8 escalations in the period               |
| ``tendance(prise,période,0.1)``        | Returns -1 : downward trend    |
| ``stateDuration(prise)``               | Returns 600 : the plug has been in its current state for 600 seconds (10 minutes)                             |
| ``lastChangeStateDuration(prise,0)``   | Returns 600 : the socket went out (change to 0) for the last time 600 seconds ago (10 minutes)     |
| ``lastChangeStateDuration(prise,1)``   | Returns 4200 : the socket turned on (switch to 1) for the last time 4200 seconds ago (1h10)                               |
| ``lastStateDuration(prise,0)``         | Returns 600 : the socket has been off for 600 seconds (10 minutes)     |
| ``lastStateDuration(prise,1)``         | Returns 3600 : the socket was last switched on for 3600 seconds (1h)           |
| ``stateChanges(prise,période)``        | Returns 3 : the plug changed state 3 times during the period            |
| ``stateChanges(prise,0,période)``      | Returns 2 : the socket has extinguished (going to 0) twice during the period                              |
| ``stateChanges(prise,1,période)``      | Returns 1 : the plug is lit (change to 1) once during the period                              |
| ``lastBetween(#[Salle de bain][Hydrometrie][Humidité]#,Yesterday,Today)`` | Returns the last temperature recorded yesterday.                    |
| ``variable(plop,10)``                  | Returns the value of the variable plop or 10 if it is empty or does not exist                         |
| ``scenario(#[Salle de bain][Lumière][Auto]#)`` | Returns 1 in progress, 0 if stopped and -1 if deactivated, -2 if the scenario does not exist and -3 if the state is not consistent                         |
| ``lastScenarioExecution(#[Salle de bain][Lumière][Auto]#)``   | Returns 300 if the scenario was started for the last time 5 min ago                                  |
| ``collectDate(#[Salle de bain][Hydrometrie][Humidité]#)``     | Returns 2015-01-01 17:45:12          |
| ``valueDate(#[Salle de bain][Hydrometrie][Humidité]#)`` | Returns 2015-01-01 17:50:12          |
| ``eqEnable(#[Aucun][Basilique]#)``       | Returns -2 if the equipment is not found, 1 if the equipment is active and 0 if it is inactive          |
| ``tag(montag,toto)``                   | Returns the value of "montag" if it exists otherwise returns the value "toto"                               |
| ``name(eqLogic,#[Salle de bain][Hydrometrie][Humidité]#)``     | Returns Hydrometry                  |


### Mathematical functions

A generic function toolbox can also be used to perform conversions or calculations :

- ``rand(1,10)`` : Give a random number from 1 to 10.
- ``randText(texte1;texte2;texte…​..)`` : Allows you to return one of the texts randomly (separate the texts by one; ). There is no limit in the number of texts.
- ``randomColor(min,max)`` : Gives a random color between 2 bounds (0 => red, 50 => green, 100 => blue).
- ``trigger(commande)`` : Enables you to find out the trigger for the scenario or to know whether it is the command passed as a parameter that triggered the scenario.
- ``triggerValue(commande)`` : Used to find out the value of the scenario trigger.
- ``round(valeur,[decimal])`` : Rounds above, [decimal] number of decimal places after the decimal point.
- ``odd(valeur)`` : Lets you know if a number is odd or not. Returns 1 if odd 0 otherwise.
- ``median(commande1,commande2…​.commandeN)`` : Returns the median of the values.
- ``avg(commande1,commande2…​.commandeN)`` : Returns the average of the values.
- ``time_op(time,value)`` : Allows you to perform operations on time, with time = time (ex : 1530) and value = value to add or subtract in minutes.
- ``time_between(time,start,end)`` : Used to test if a time is between two values with ``time=temps`` (Ex : 1530), ``start=temps``, ``end=temps``. Start and end values can be straddling midnight.
- ``time_diff(date1,date2[,format, round])`` : Used to find out the difference between two dates (the dates must be in the format YYYY / MM / DD HH:MM:SS). By default, the method returns the difference in day (s). You can ask it in seconds (s), minutes (m), hours (h). Example in seconds ``time_diff(2019-02-02 14:55:00,2019-02-25 14:55:00,s)``. The difference is returned in absolute, unless you specify ``f`` (``sf``, ``mf``, ``hf``, ``df``). You can also use ``dhms`` who will not return example ``7j 2h 5min 46s``. The optional round parameter rounded to x digits after the decimal point (2 by default). Ex: ``time_diff(2020-02-21 20:55:28,2020-02-28 23:01:14,df, 4)``.
- ``formatTime(time)`` : Formats the return of a chain ``#time#``.
- ``floor(time/60)`` : Convert seconds to minutes, or minutes to hours (``floor(time/3600)`` for seconds to hours).
- ``convertDuration(secondes)`` : Converts seconds to d / h / min / s.

And practical examples :


| Example of function                  | Returned result                    |
|--------------------------------------|--------------------------------------|
| ``randText(il fait #[salon][oeil][température]#;La température est de #[salon][oeil][température]#;Actuellement on a #[salon][oeil][température]#)`` | the function will return one of these texts randomly at each execution.                           |
| ``randomColor(40,60)``                 | Returns a random color close to green.
| ``trigger(#[Salle de bain][Hydrometrie][Humidité]#)``   | 1 if it's good ``#[Salle de bain][Hydrometrie][Humidité]#`` who started the scenario otherwise 0  |
| ``triggerValue(#[Salle de bain][Hydrometrie][Humidité]#)`` | 80 if the hydrometry of ``#[Salle de bain][Hydrometrie][Humidité]#`` is 80%.                         |
| ``round(#[Salle de bain][Hydrometrie][Humidité]# / 10)`` | Returns 9 if the humidity percentage and 85                     |
| ``odd(3)``                             | Returns 1                            |
| ``median(15,25,20)``                   | Returns 20
| ``avg(10,15,18)``                      | Returns 14.3                     |
| ``time_op(#time#, -90)``               | if it is 4:50 p.m., return : 1 650 - 1 130 = 1520                          |
| ``formatTime(1650)``                   | Returns 4:50 p.m                        |
| ``floor(130/60)``                     | Returns 2 (minutes if 130s, or hours if 130m)                      |
| ``convertDuration(3600)``             | Returns 1h 0min 0s                      |
| ``convertDuration(duration(#[Chauffage][Module chaudière][Etat]#,1, first day of this month)*60)`` | Returns the ignition time in Days / Hours / minutes of the time of transition to state 1 of the module since the 1st day of the month |


### Specific orders

In addition to home automation commands, you have access to the following actions :

- **Pause** (sleep) : Pause of x second (s).
- **Variable** (variable) : Creation / modification of a variable or the value of a variable.
- **Remove variable** (delete_variable) : Allows you to delete a variable.
- **Scenario** (scenario) : Allows you to control scenarios. The tags part allows you to send tags to the scenario, ex : montag = 2 (be careful, only use letters from a to z. No capital letters, no accents and no special characters). We recover the tag in the target scenario with the tag function (montag). The command "Reset to SI" allows to reset the status of "SI" (this status is used for the non-repetition of the actions of an "SI" if you pass for the 2nd consecutive time in it)
- **STOP** (stop) : Stop the scenario.
- **Wait** (wait) : Wait until the condition is valid (maximum 2h), the timeout is in seconds (s).
- **Go to design** (gotodesign) : Change the design displayed on all browsers by the requested design.
- **Add a log** (log) : Allows you to add a message to the logs.
- **Create message** (message) : Add a message to the message center.
- **Activate / Deactivate Hide / display equipment** (equipement) : Allows you to modify the properties of visible / invisible, active / inactive equipment.
- **To make a request** (ask) : Allows to indicate to Jeedom that it is necessary to ask a question to the user. The answer is stored in a variable, then you just have to test its value.
    For the moment, only sms, slack, telegram and snips plugins are compatible, as well as the mobile application.
    Attention, this function is blocking. As long as there is no response or the timeout is not reached, the scenario waits.
- **Stop Jeedom** (jeedom_poweroff) : Ask Jeedom to shut down.
- **Return a text / data** (scenario_return) : Returns a text or a value for an interaction for example.
- **Icon** (icon) : Allows to change the icon of representation of the scenario.
- **Alert** (alert) : Displays a small alert message on all browsers that have a Jeedom page open. You can, in addition, choose 4 alert levels.
- **Pop-up** (popup) : Allows to display a pop-up which must absolutely be validated on all browsers which have a jeedom page open.
- **Report** (report) : Allows you to export a view in format (PDF, PNG, JPEG or SVG) and send it using a message-type command. Please note, if your Internet access is in unsigned HTTPS, this functionality will not work. Signed HTTP or HTTPS is required.
- **Delete programmed IN / A block** (remove_inat) : Allows you to delete the programming of all IN and AT blocks of the scenario.
- **Event** (event) : Allows you to push a value in an information type command arbitrarily.
- **Tag** (tag) : Allows you to add / modify a tag (the tag only exists during the current execution of the scenario unlike the variables that survive the end of the scenario).
- **Coloring of dashboard icons** (setColoredIcon) : allows you to activate or not the coloring of icons on the dashboard.

### Scenario template

This functionality allows you to transform a scenario into a template to, for example, apply it to another Jeedom.

By clicking on the button **template** at the top of the page, you open the template management window.

From there, you have the possibility :

- Send a template to Jeedom (JSON file previously retrieved).
- Consult the list of scenarios available on the Market.
- Create a template from the current scenario (don't forget to give a name).
- To consult the templates currently present on your Jeedom.

By clicking on a template, you can :

- **Share** : Share the template on the Market.
- **Remove** : Delete template.
- **Download** : Get the template as a JSON file to send it to another Jeedom for example.

Below, you have the part to apply your template to the current scenario.

Given that from one Jeedom to another or from one installation to another, the commands can be different, Jeedom asks you for the correspondence of the commands between those present during the creation of the template and those present at home. You just have to fill in the correspondence of the orders then to apply.

## Addition of php function

> **IMPORTANT**
>
> Adding PHP function is reserved for advanced users. The slightest error can be fatal for your Jeedom.

### Set up

Go to the Jeedom configuration, then OS / DB and launch the file editor.

Go to the data folder then php and click on the user.function.class.php file.

It is in this *Class* that you can add your functions, there you will find an example of a basic function.

> **IMPORTANT**
>
> If you have a concern, you can always revert to the original file by copying the contents of ``user.function.class.sample.php`` In ``user.function.class.php``
