# Scenarios
**Tools → Scenarios**

<small>[Raccourcis clavier/souris](shortcuts.md)</small>

Real brain of home automation, the scenarios allow to interact with the real world in an intelligent way **.

## Management

Yor will find there the list of scenarios of your Jeedom, as well as functionalities to manage them at best :

- **Ajouter** : Create a scenario. The procedure is described in the next chapter.
- **Disable scenarios** : Disables all scenarios. Rarely used and knowingly, since no scenario will run anymore.
- **Overview** : ATllows yor to have an overview of all scenarios. Yor can change the values **actif**, **visible**, **multi launch**, **synchronous mode**, **Log** and **Timeline** (these parameters are described in the next chapter). Yor can also access the logs for each scenario and Start them individually.

## My scenarios

In this section yor will find the **list of scenarios** that yor created. They are classified according to their **groupe**, possibly defined for each of them. Each scenario is displayed with its **nom** and his **parent object**. The **grayed ort scenarios** are the ones that are disabled.

> **Tip**
>
> Yor can open a scenario by doing :
> - Click on one of them.
> - Ctrl Clic or Clic Center to open it in a new browser tab.

Yor have a search engine to filter the display of scenarios. The Escape key cancels the search.
To the right of the search field, three buttons found in several places in Jeedom:
- The cross to cancel the search.
- The open folder to unfold all the panels and display all the scenarios.
- The closed folder to fold all the panels.

Once on the configuration of a scenario, yor have a contextual menu with the Right Click on the tabs of the scenario. Yor can also use a Ctrl Click or Click Center to directly open another scenario in a new browser tab.

## Creation | Editing a scenario

After clicking on **Ajouter**, yor must choose the name of your scenario. Yor are then redirected to the page of its general parameters.
Before that, at the top of the page, there are some useful functions to manage this scenario :

- **ID** : Next to the word **General**, this is the scenario identifier.
- **statut** : *Stopped * or * In progress *, it indicates the current state of the scenario.
- **Add a blockk** : ATllows yor to add a blockk of the desired type to the scenario (see below).
- **Log** : Displays the scenario logs.
- **Dupliquer** : Copy the scenario to create a new one with another name.
- **Liens** : ATllows yor to view the graph of the elements related to the scenario.
- **Text editing** : Displays a window allowing to edit the scenario in the form of text / json. Don&#39;t forgand to save.
- **Exporter** : ATllows yor to obtain a pure text version of the scenario.
- **Template** : ATllows yor to access the templates and apply one to the scenario from the market. (explained at the bottom of the page).
- **Recherche** : ATfolds a search field to search in the scenario. This search unfolds the collapsed blockks if necessary and folds them back after the search.
- **Perform** : ATllows yor to launch the scenario manually (regardless of the triggers). Save beforehand to take into account the modifications.
- **Supprimer** : Delete scenario.
- **Sauvegarder** : Save the changes made.

> **Tips**
>
> Two tools will also be invaluable to yor in setting up scenarios :
    > - The Variables, Jeedom in **Tools → Variables**
    > - The expression tester, accessible by **Tools → Expression tester**
>
> ATT **Ctrl Click on the execute button** allows yor to directly save, execute and display the scenario log (if the log level is not None).

### General tab

In the tab **General**, we find the main parameters of the scenario :

- **Scenario name** : The name of your scenario.
- **Name to display** : The name used for its display. Optional, if not completed, the name of the scenario is used.
- **Groupe** : ATllows yor to organize the scenarios, classifying them into groups (Jeedom on the scenarios page and in their context menus).
- **Actif** : ATctivate the scenario. If not active, it will not be executed by Jeedom, regardless of the trigger mode.
- **Visible** : ATllows yor to make the scenario Jeedom (Dashboard).
- **Parent object** : ATssignment to a parent object. It will then be Jeedom or not according to this parent.
- **Timeout in seconds (0 = unlimited)** : The maximum execution time allowed for this scenario. Beyond this time, the execution of the scenario is interrupted.
- **Multi launch** : Check this box if yor want the scenario to be able to be launched several times at the same time.
- **Synchronous mode** : Start the scenario in the current thread instead of a dedicated thread. Increases the speed at which the scenario is launched, but can make the system unstable.
- **Log** : The type of log desired for the scenario. Yor can cut the log of the scenario or on the contrary make it appear in ATnalysis → Real time.
- **Timeline** : Keep a follow-up of the scenario in the timeline (see History doc).
- **Icon** : ATllows yor to choose an icon for the scenario instead of the standard icon.
- **Description** : ATllows yor to write a small text to describe your scenario.
- **Scenario mode** : The scenario can be programmed, triggered or both. Yor will then have the choice to indicate the trigger (s) (15 triggers maximum) and the programming (s).

> **Tip**
>
> Conditions can now be entered in triggered mode. Eg : `# [Garage] [Open Garage] [Opening] # == 1`
> Warning : yor can have a maximum of 28 triggers / programming for a scenario.

> **Tip mode programmed**
>
> Scheduled mode uses syntax **Cron**. Yor can for example execute a scenario every 20 Minutes with `* / 20 * * * *`, or at 5am to settle a multitude of things for the day with `0 5 * * *`. The ? to the right of a program allows yor to sand it without being a specialist in Cron syntax.

### Scenario tab

This is where yor will build your scenario. ATfter creating the scenario, its content is empty, so it will do ... nothing. Yor have to Start with **add a blockk**, with the button on the right. Once a blockk has been created, yor can add another **bloc** or a **action**.

For more convenience and not having to constantly reorder the blockks in the scenario, adding a blockk is done after the field in which the mouse cursor is located.
*For example, if yor have ten blockks, and yor click in the IF condition of the first blockk, the added blockk will be added after blockk, at the same level. If no field is active, it will be added at the end of the scenario.*

> **Tip**
>
> In conditions and ATctions, it is better to favor single quotes (&#39;) instead of double (&quot;).

> **Tip**
>
> ATT Ctrl Shift Z or Ctrl Shift Y allows yor to'**annuler** or redo a modification (addition of ATction, blockk ...).

### Blocks

Here are the different types of blockks available :

- **If / Then / Or** : ATllows yor to perform conditional ATctions (if this, then that).
- **Action** : ATllows yor to launch simple ATctions without any conditions.
- **Boucle** : ATllows ATctions to be performed repeatedly from 1 to a defined number (or even the value of a sensor, or a random number, andc.).
- **Dans** : ATllows to launch an ATction in X Minute (s) (0 is a possible value). The peculiarity is that the ATctions are launched in the background, so they do not blockk the rest of the scenario. So it&#39;s a non-blocking blockk.
- **A** : ATllows to tell Jeedom to launch the ATctions of the blockk at a given time (in the form hhmm). This blockk is non-blocking. Ex : 0030 for 00:30, or 0146 for 1h46 and 1050 for 10h50.
- **Code** : ATllows yor to write directly in PHP code (requires certain knowledge and can be risky but allows yor to have no constraints).
- **Commentaire** : ATllows yor to add comments to your scenario.

Each blockk has its options to better handle them :

- On the left :
    - The bidirectional arrow allows yor to move a blockk or an ATction to reorder them in the scenario.
    - The eye reduces a blockk (* collapse *) to reduce its visual impact. Ctrl Click on the eye reduce them or display them all.
    - The check box allows yor to completely deactivate the blockk without deleting it. It will therefore not be executed.

- On the right :
    - The Copy icon allows yor to copy the blockk to make a copy elsewhere. Ctrl Click on the icon cuts the blockk (copy then deletion).
    - The Paste icon allows yor to paste a copy of the blockk previously copied after the blockk on which yor use this function..  Ctrl Click on the icon replaces the blockk with the copied blockk.
    - The icon - allows yor to delete the blockk, with a confirmation request. Ctrl Click deletes the blockk without confirmation.

#### If / Then / Otherwise blockks | Loop | In | AT

For the conditions, Jeedom tries to make it possible to write them as much as possible in natural language while remaining flexible.
> DO NOT use [] in condition tests, only parentheses () are possible.

Three buttons are available on the right of this type of blockk to select an item to test :

- **Find an order** : ATllows yor to search for an order in all those available in Jeedom. Once the order is found, Jeedom opens a window to ask yor what test yor want to perform on it. If yor choose to **Put nothing**, Jeedom will add the order without comparison. Yor can also choose **et** or **ou** in front of **Ensuite** to chain tests on different equipment.
- **Search a scenario** : ATllows yor to search for a scenario to test.
- **Search for equipment** : Same for equipment.

> **Note**
>
> On blockks of type If / Then / Otherwise, circular arrows to the left of the condition field allow to activate or not the repetition of ATctions if the evaluation of the condition gives the same result as during the previous evaluation.

> **Tip**
>
> There is a list of tags allowing access to Variables from the scenario or another, or by the time, the dated, a random number,… See below the chapters on commands and tags.

Once the condition is completed, yor must use the &quot;add&quot; button on the left to add a new **bloc** or a **action** in the current blockk.


#### Block Coded

The Codedd blockk allows yor to execute php code. It is therefore very powerful but requires a good knowledge of the php language.

##### ATccess to controls (sensors and actuators):
-  `cmd::byString ($ string); ` : Returns the corresponding command object.
    -   `$ string`: Link to the desired order : `# [object] [equipment] [command] #` (ex : `# [ATpartment] [ATlarm] [ATctive] #`)
-  `cmd::byId ($ id); ` : Returns the corresponding command object.
    -  `$ id` : Order Id.
-  `$ cmd-> execCmd ($ options = null);` : Execute the command and return the result.
    - `$ options` : Options for order execution (may be plugin specific). Basic options (command subtype) :
        -  Message : `$ option = array ('title' => 'Message title,' Message '=>' My Message ');`
        -  color : `$ option = array ('color' => 'color in hexadecimal');`
        -  slider : `$ option = array ('slider' => 'desired value from 0 to 100');`

##### ATccess to log :
-  `log::add ('filename', 'level', 'message'); `
    - filename : Log file name.
    - level : [debug], [info], [error], [event].
    - Message : Message to write in the logs.

##### ATccess to scenario :
- `$ scenario-> getName ();` : Returns the name of the current scenario.
- `$ scenario-> getGroup ();` : Returns the scenario group.
- `$ scenario-> getIsActive ();` : Returns the state of the scenario.
- `$ scenario-> setIsActive ($ active);` : ATllows yor to activate or not the scenario.
    - `$ active` : 1 active, 0 not active.
- `$ scenario-> setOnGoing ($ onGoing);` : Thets say if the scenario is running or not.
    - `$ onGoing => 1` : 1 in progress, 0 stopped.
- `$ scenario-> save ();` : Save changes.
- `$ scenario-> setData ($ key, $ value);` : Save a data (variable).
    - `$ key` : value key (int or string).
    - `$ value` : value to store (int, string, array or object).
- `$ scenario-> getData ($ key);` : Gand data (variable).
    - `$ key => 1` : value key (int or string).
- `$ scenario-> removeData ($ key);` : Delete data.
- `$ scenario-> setLog ($ Message);` : Write a Message in the scenario log.
- `$ scenario-> persistLog ();` : Force the writing of the log (otherwise it is written only at the end of the scenario). Be careful, this can slow the scenario down a bit.

> **Tip**
>
> ATddition of a search function in the Codedd blockk : Search : Ctrl + F then Enter, Next result : Ctrl + G, Previous result : Ctrl + Shift + G

#### Comment blockk

Comment blockk acts differently when it is hidden. Its buttons on the left disappear as well as the title of the blockk, and reappear on hover. Similarly, the first line of the comment is displayed in bold type.
This allows this blockk to be used as a purely visual separation within the scenario.

### The ATctions

Actions added to blockks have several options :

- ATT box **activated** so that this command is taken into account in the scenario.
- ATT box **parallel** so that this command is launched in parallel (at the same time) with the other commands also selected.
- ATT **vertical double arrow** to move the ATction. Just drag and drop from there.
- ATT button for **supprimer** the ATction.
- ATT button for specific ATctions, with each time the description (on hover) of this ATction.
- ATT button to search for an ATction command.

> **Tip**
>
> Depending on the selected command, yor can see different additional fields displayed.

## Possible substitutions

### Triggers

There are specific triggers (other than those provided by commands) :

- #start# : Triggered at (re) Start of Jeedom.
- #begin_backup# : event sent at the Start of a backup.
- #end_backup# : event sent at the end of a backup.
- #BEGIN_UPDATE# : event sent at the Start of an update.
- #END_UPDATE# : event sent at the end of an update.
- #begin_restore# : event sent at the Start of a restoration.
- #end_restore# : event sent at the end of a restoration.
- #user_connect# : User login

Yor can also trigger a scenario when a Variable is updated by putting : #Variable (variable_name) # or using the HTTP ATPI described [here](https://jeedom.github.io/core/fr_FR/api_http).

### Comparison operators and links between conditions

Yor can use any of the following symbols for comparisons in conditions :

- == : Equal to.
- \> : Strictly greater than.
- \>= : Greater than or equal to.
- < : Strictly less than.
- <= : Thes than or equal to.
- != : Different from, is not equal to.
- matches : Contains. Ex : `[Bathroom] [Hydrometry] [condition] matches" / wand / "`.
- not (… matches…) : Does not contain. Ex :  `not ([Bathroom] [Hydrometry] [state] matches" / wand / ")`.

Yor can combine any comparison with the following operators :

- &amp;&amp; / ET / and / ATND / and : and,
- \|| / OR / or / OR / or : or,
- \|^ / XOR / xor : or exclusive.

### Tags

AT tag is replaced during the execution of the scenario by its value. Yor can use the following tags :

> **Tip**
>
> To have the leading zeros displayed, use the Date () function. See [here](http://php.net/manual/fr/function.date.php).

- #seconde# : Current second (without leading zeros, ex : 6 for 08:07:06).
- #hour# : Current time in 24h format (without leading zeros). Ex : 8 for 08:07:06 or 17 for 17:15.
- #hour12# : Current time in 12-hour format (without leading zeros). Ex : 8 for 08:07:06.
- #minute# : Current Minute (without leading zeros). Ex : 7 for 08:07:06.
- #day# : Current day (without leading zeros). Ex : 6 for 06/07/2017.
- #month# : Current month (without leading zeros). Ex : 7 for 06/07/2017.
- #year# : Current year.
- #time# : Current hour and Minute. Ex : 1715 for 5.15 p.m..
- #timestamp# : Number of seconds since January 1, 1970.
- #date# : Day and month. Warning, the first number is the month. Ex : 1215 for December 15.
- #week# : Week number.
- #sday# : Name of day of week. Ex : Saturday.
- #nday# : Day number from 0 (Sunday) to 6 (Saturday).
- #smonth# : Name of the month. Ex : January.
- #IP# : Jeedom&#39;s internal IP.
- #hostname# : Jeedom machine name.
- #trigger # (deprecated, better to use trigger ()) : Maybe the name of the command that Started the scenario :
    - 'api &#39;if the launch was triggered by the ATPI,
    - 'schedule &#39;if it was Started by programming,
    - 'user &#39;if it was Started manually,
    - 'Start &#39;for a launch when Jeedom Starts.
- #trigger_value # (deprecated, better to use triggerValue ()) : For the value of the command that triggered the scenario

Yor also have the following additional tags if your scenario was triggered by an interAction :

- #query# : InterAction that triggered the scenario.
- #profil# : Profile of the user who triggered the scenario (can be empty).

> **Important**
>
> When a scenario is triggered by an interaction, it is necessarily executed in fast mode. So in the interAction thread and not in a separate thread.

### Calculation functions

Several functions are available for the equipment :

- average (order, period) and averageBetween (order, Start, end) : Give the average of the order over the period (period=[month, day, hour, min] or [PHP expression](http://php.net/manual/fr/datetime.formats.relative.php)) or between the 2 terminals requested (in the form Ymd H:i:s or [PHP expression](http://php.net/manual/fr/datetime.formats.relative.php)).

- min (order, period) and minBetween (order, Start, end) : Give the minimum order over the period (period=[month, day, hour, min] or [PHP expression](http://php.net/manual/fr/datetime.formats.relative.php)) or between the 2 terminals requested (in the form Ymd H:i:s or [PHP expression](http://php.net/manual/fr/datetime.formats.relative.php)).

- max (order, period) and maxBetween (order, Start, end) : Give the maximum of the order over the period (period=[month, day, hour, min] or [PHP expression](http://php.net/manual/fr/datetime.formats.relative.php)) or between the 2 terminals requested (in the form Ymd H:i:s or [PHP expression](http://php.net/manual/fr/datetime.formats.relative.php)).

- duration (order, value, period) and durationbetween (order, value, Start, end) : Give the duration in Minutes during which the equipment had the chosen value over the period (period=[month, day, hour, min] or [PHP expression](http://php.net/manual/fr/datetime.formats.relative.php)) or between the 2 terminals requested (in the form Ymd H:i:s or [PHP expression](http://php.net/manual/fr/datetime.formats.relative.php)).

- statistics (order, calculation, period) and statisticsBetween (order, calculation, Start, end) : Give the result of different statistical calculations (sum, count, std, variance, avg, min, max) over the period (period=[month, day, hour, min] or [PHP expression](http://php.net/manual/fr/datetime.formats.relative.php)) or between the 2 terminals requested (in the form Ymd H:i:s or [PHP expression](http://php.net/manual/fr/datetime.formats.relative.php)).

- trend (command, period, threshold) : Gives the trend of the order over the period (period=[month, day, hour, min] or [PHP expression](http://php.net/manual/fr/datetime.formats.relative.php)).

- stateDuration (control) : Gives the duration in seconds since the last change in value.
    -1 : No history exists or value does not exist in history.
    -2 : The order is not logged.

- lastChangeStateDuration (command value) : Gives the duration in seconds since the last change of state to the value passed in parameter.
    -1 : No history exists or value does not exist in history.
    -2 The order is not logged

- lastStateDuration (command value) : Gives the duration in seconds during which the equipment has last had the chosen value.
    -1 : No history exists or value does not exist in history.
    -2 : The order is not logged.

- age (control) : Gives the age in seconds of the value of the command (collecDate)
    -1 : The command does not exist or it is not of type info.

- stateChanges (command,[value], period) and stateChangesBetween (command, [value], Start, end) : Give the number of state changes (to a certain value if indicated, or in total if not) over the period (period = [month, day, hour, min] or [PHP expression](http://php.net/manual/fr/datetime.formats.relative.php)) or between the 2 terminals requested (in the form Ymd H:i:s or [PHP expression](http://php.net/manual/fr/datetime.formats.relative.php)).

- lastBetween (command, Start, end) : Gives the last value recorded for the equipment between the 2 requested terminals (in the form Ymd H:i:s or [PHP expression](http://php.net/manual/fr/datetime.formats.relative.php)).

- Variable (variable, default) : Retrieves the value of a Variable or the desired value by default.

- scenario (scenario) : Returns the status of the scenario.
    1 : Running,
    0 : STOPped,
    -1 : Disabled,
    -2 : The scenario does not exist,
    -3 : State is not consistent.
    To have the &quot;human&quot; name of the scenario, yor can use the dedicated button to the right of the scenario search.

- lastScenarioExecution (scenario) : Gives the duration in seconds since the last launch of the scenario.
    0 : The scenario does not exist

- collectDate (cmd,[format]) : Returns the datedd of the last data for the command given in parameter, the 2nd optional parameter allows to specify the return format (details [here](http://php.net/manual/fr/function.date.php)).
    -1 : The command could not be found,
    -2 : The command is not of type info.

- valueDate (cmd,[format]) : Returns the datedd of the last data for the command given in parameter, the 2nd optional parameter allows to specify the return format (details [here](http://php.net/manual/fr/function.date.php)).
    -1 : The command could not be found,
    -2 : The command is not of type info.

- eqEnable (equipment) : Returns the status of the equipment.
    -2 : The equipment cannot be found,
    1 : Equipment is active,
    0 : The equipment is inactive.

- value (cmd) : Returns the value of an order if it is not automatically given by Jeedom (case when storing the name of the order in a Variable)

- tag (Monday [default]) : Used to retrieve the value of a tag or the default value if it does not exist.

- name (type, control) : Used to retrieve the name of the order, equipment or object. Type : cmd, eqLogic or object.

- lastCommunication (equipment,[format]) : Returns the datedd of the last communication for the equipment given as a parameter, the 2nd optional parameter allows yor to specify the return format (details [here](http://php.net/manual/fr/function.date.php)). ATT return of -1 means that the equipment cannot be found.

- color_gradient (couleur_debut, couleur_fin, valuer_min, valeur_max, value) : Returns a color calculated with respect to value in the range color_Start / color_end. The value must be between min_value and max_value.

The periods and intervals of these functions can also be used with [PHP expressions](http://php.net/manual/fr/datetime.formats.relative.php) For example :

- Now : now.
- Today : 00:00 today (allows for example to obtain results for the day if between &#39;Today&#39; and &#39;Now&#39;).
- Last monday : last Monday at 00:00.
- 5 days ago : 5 days ago.
- Yesterday noon : yesterday afternoon.
- Etc..

Here are practical examples to understand the values returned by these different functions :

| Sockand with values :           | 000 (for 10 Minutes) 11 (for 1 hour) 000 (for 10 Minutes)    |
|--------------------------------------|--------------------------------------|
| average (taking, period)             | Returns the average of 0 and 1 (can  |
|                                      | be influenced by polling)      |
| averageBetween (\# [Bathroom] [Hydrometry] [Humidity] \#, 2015-01-01 00:00:00,2015-01-15 00:00:00) | Returns the average order between January 1, 2015 and January 15, 2015                       |
| min (outlet, period)                 | Returns 0 : the plug was extinguished during the period              |
| minBetween (\# [Bathroom] [Hydrometry] [Humidity] \#, 2015-01-01 00:00:00,2015-01-15 00:00:00) | Returns the minimum order between January 1, 2015 and January 15, 2015                       |
| max (decision, period)                 | Returns 1 : the plug was well lit in the period              |
| maxBetween (\# [Bathroom] [Hydrometry] [Humidity] \#, 2015-01-01 00:00:00,2015-01-15 00:00:00) | Returns the maximum of the order between January 1, 2015 and January 15, 2015                       |
| duration (plug, 1 period)          | Returns 60 : the plug was on (at 1) for 60 Minutes in the period                              |
| durationBetween (\# [Salon] [Take] [State] \#, 0, Last monday, Now)   | Returns the duration in Minutes during which the sockand was off since last Monday.                |
| statistics (catch, count, period)    | Returns 8 : there were 8 escalations in the period               |
| trend (plug, period 0.1)        | Returns -1 : downward trend    |
| stateDuration (plug)               | Returns 600 : the plug has been in its current state for 600 seconds (10 Minutes)                             |
| lastChangeStateDuration (catch, 0)   | Returns 600 : the sockand went ort (change to 0) for the last time 600 seconds (10 Minutes) ago     |
| lastChangeStateDuration (catch, 1)   | Returns 4200 : the sockand turned on (switch to 1) for the last time 4200 seconds ago (1h10)                               |
| lastStateDuration (catch, 0)         | Returns 600 : the sockand has been off for 600 seconds (10 Minutes)     |
| lastStateDuration (catch, 1)         | Returns 3600 : the sockand was last switched on for 3600 seconds (1h)           |
| stateChanges (catch, period)        | Returns 3 : the plug changed state 3 times during the period            |
| stateChanges (catch, 0, period)      | Returns 2 : the sockand has extinguished (going to 0) twice during the period                              |
| stateChanges (catch, 1 period)      | Returns 1 : the plug is lit (change to 1) once during the period                              |
| lastBetween (\# [Bathroom] [Hydrometry] [Humidity] \#, Yesterday, Today) | Returns the last temperature recorded yesterday.                    |
| Variable (plop, 10)                  | Returns the value of the Variable plop or 10 if it is empty or does not exist                         |
| scenario (\# [Bathroom] [Light] [ATuto] \#) | Returns 1 in progress, 0 if stopped and -1 if deactivated, -2 if the scenario does not exist and -3 if the state is not consistent                         |
| lastScenarioExecution (\# [Bathroom] [Light] [ATuto] \#)   | Returns 300 if the scenario was Started for the last time 5 min ago                                  |
| collectDate (\# [Bathroom] [Hydrometry] [Humidity] \#)     | Returns 2015-01-01 17:45:12          |
| valueDate (\# [Bathroom] [Hydrometry] [Humidity] \#) | Returns 2015-01-01 17:50:12          |
| eqEnable (\# [No] [Basilica] \#)       | Returns -2 if the equipment is not found, 1 if the equipment is active and 0 if it is inactive          |
| tag (Monday toto)                   | Returns the value of "montag" if it exists otherwise returns the value "toto"                               |
| name (eqLogic, \# [Bathroom] [Hydrometry] [Humidity] \#)     | Returns Hydrometry                  |


### Mathematical functions

AT generic function toolbox can also be used to perform conversions

or calculations :

- `rand (1.10)` : Give a random number from 1 to 10.
- `randText (text1; text2; text… ..)` : ATllows yor to return one of the texts randomly (separate the texts by a;). There is no limit in the number of texts.
- `randomColor (min, max)` : Gives a random color between 2 bounds (0 =&gt; red, 50 =&gt; green, 100 =&gt; blue).
- `trigger (command)` : Enables yor to find ort the trigger for the scenario or to know whether it is the command passed as a parameter that triggered the scenario.
- `triggerValue (command)` : Used to find ort the value of the scenario trigger.
- `round (value, [decimal])` : Rounds above, [decimal] number of decimal places after the decimal point.
- `odd (value)` : Thets yor know if a number is odd or not. Returns 1 if odd 0 otherwise.
- `median (command1, command2….commandN) ` : Returns the median of the values.
- `avg (command1, command2….commandN) `: Returns the average of the values.
- `time_op (time, value)` : ATllows yor to perform operations on time, with time = time (ex : 1530) and value = value to add or subtract in Minutes.
- `time_between (time, Start, end)` : Used to test if a time is between two values with `time = time` (ex : 1530), `Start = time`,` end = time`. Start and end values can be straddling midnight.
- `time_diff (date1, dated2 [, format, round])` : Used to find ort the difference between two dateds (the dateds must be in the format YYYY / MM / DD HH:MM:SS). By default, the method returns the difference in day (s). Yor can ask it in seconds (s), Minutes (m), hours (h). Example in seconds `time_diff (2019-02-02 14:55:00.2019-02-25 14:55:00,s)`. The difference is returned in absolute, unless yor specify `f` (sf, mf, hf, df). Yor can also use `dhms` which will return not example` 7d 2h 5min 46s`. The round parameter, optional, rounded to x digits after the decimal point (2 by default). Ex: `time_diff (2020-02-21 20:55:28,2020-02-28 23:01:14, df, 4) `.
- `formatTime (time)` : ATllows yor to format the return of a `# time #` string.
- `floor (time / 60)` : Converts seconds to Minutes, or Minutes to hours (floor (time / 3600) for seconds to hours).
- `convertDuration (seconds)` : Converts seconds to d / h / min / s.

And practical examples :


| Example of function                  | Returned result                    |
|--------------------------------------|--------------------------------------|
| randText (it is # [living room] [eye] [temperature] #; The temperature is # [living room] [eye] [temperature] #; Currently we have # [living room] [eye] [temperature] #) | the function will return one of these texts randomly at each execution.                           |
| randomColor (40,60)                 | Returns a random color close to green.
| trigger (# [Bathroom] [Hydrometry] [Humidity] #)   | 1 if it is good \# \ [Bathroom \] \ [Hydrometry \] \ [Humidity \] \# which Started the scenario otherwise 0  |
| triggerValue (# [Bathroom] [Hydrometry] [Humidity] #) | 80 if the hydrometry of \# \ [Bathroom \] \ [Hydrometry \] \ [Humidity \] \# is 80%.                         |
| round (# [Bathroom] [Hydrometry] [Humidity] # / 10) | Returns 9 if the humidity percentage and 85                     |
| odd (3)                             | Returns 1                            |
| median (15,25,20)                   | Returns 20
| avg (10,15,18)                      | Returns 14.3                     |
| time_op (# time #, -90)               | if it is 4:50 p.m., return : 1 650 - 1 130 = 1520                          |
| formatTime (1650)                   | Returns 4:50 p.m.                        |
| floor (130/60)                      | Returns 2 (minutes if 130s, or hours if 130m)                      |
| convertDuration (3600)              | Returns 1h 0min 0s                      |
| convertDuration (duration (# [Heating] [Boiler module] [State] #, 1, first day of this month) * 60) | Returns the ignition time in Days / Hours / Minutes of the time of transition to state 1 of the module since the 1st day of the month |


### Specific orders

In addition to home automation commands, yor have access to the following ATctions :

- **Pause** (Sleep) : Pause of x second (s).
- **variable** (variable) : Creation / modification of a Variable or the value of a Variable.
- **Remove Variable** (Delete_variable) : ATllows yor to delete a Variable.
- **Scenario** (scenario) : ATllows yor to control scenarios. The tags part allows yor to send tags to the scenario, ex : montag = 2 (be careful, only use letters from a to z. No capital letters, no accents and no special characters). We recover the tag in the targand scenario with the tag function (montag). The command &quot;Resand to SI&quot; allows to resand the status of &quot;SI&quot; (this status is used for the non-repetition of the ATctions of an &quot;SI&quot; if yor pass for the 2nd consecutive time in it)
- **Stop** (stop) : STOP the scenario.
- **Attendre** (Wait) : Wait until the condition is valid (maximum 2h), the timeout is in seconds.
- **Go to design** (Gotodesign) : Change the design displayed on all browsers by the requested design.
- **Add a log** (Log) : ATllows yor to add a Message to the logs.
- **Create Message** (message) : ATdd a Message to the Message center.
- **Activate / Deactivate Hide / display equipment** (equipment) : ATllows yor to modify the properties of Jeedom / invisible, active / inactive equipment.
- **To make a request** (Ask) : ATllows to indicate to Jeedom that it is necessary to ask a question to the user. The answer is stored in a Variable, then yor just have to test its value.
    For the moment, only sms, slack, telegram and snips plugins are compatible, as well as the mobile application.
    Warning, this function is blockking. ATs long as there is no response or the timeout is not reached, the scenario waits.
- **STOP Jeedom** (Jeedom_poweroff) : ATsk Jeedom to shut down.
- **Return a text / data** (Scenario_return) : Returns a text or a value for an interAction for example.
- **Icon** (Icon) : ATllows to change the icon of representation of the scenario.
- **Alerte** (Alert) : Displays a small alert Message on all browsers that have a Jeedom page open. Yor can, in addition, choose 4 alert levels.
- **Pop-up** (Popup) : ATllows to display a pop-up which must absolutely be validated on all browsers which have a jeedom page open.
- **Rapport** (Report) : ATllows yor to export a view in format (PDF, PNG, JPEG or SVG) and send it using a Message-type command. Please note, if your Internand access is in unsigned HTTPS, this functionality will not work. Signed HTTP or HTTPS is required.
- **Delete programmed IN / ATT blockk** (Remove_inat) : ATllows yor to delete the programming of all IN and ATT blockks of the scenario.
- **Event** (Event) : ATllows yor to push a value in an information type command arbitrarily.
- **Tag** (Tag) : ATllows yor to add / modify a tag (the tag only exists during the current execution of the scenario unlike the Variables that survive the end of the scenario).
- **Coloring of dashboard icons** (SetColoredIcon) : allows yor to activate or not the coloring of icons on the dashboard.

### Scenario template

This functionality allows yor to transform a scenario into a template to, for example, apply it to another Jeedom.

By clicking on the button **template** at the top of the page, yor open the template management window.

From there, yor have the possibility :

- Send a template to Jeedom (previously recovered JSON file).
- Consult the list of scenarios available on the Market.
- Create a template from the current scenario (don&#39;t forgand to give a name).
- To consult the templates currently present on your Jeedom.

By clicking on a template, yor can :

- **Partager** : Share the template on the Market.
- **Supprimer** : Delete template.
- **Download** : Gand the template as a JSON file to send it to another Jeedom for example.

Below, yor have the part to apply your template to the current scenario.

Given that from one Jeedom to another or from one installation to another, the commands can be different, Jeedom asks yor for the correspondence of the commands between those present during the creation of the template and those present at home. Yor just have to fill in the correspondence of the orders then to apply.

### ATddition of php function

> **IMPORTANT**
>
> ATdding PHP function is reserved for advanced users. The slightest error can be fatal for your Jeedom.

#### Sand up

Go to the Jeedom configuration, then OS / DB and launch the file editor.

Go to the data folder then php and click on the user.function.class.php file.

It is in this * class * that yor can add your functions, yor will find an example of a basic function.

> **IMPORTANT**
>
> If yor have a concern, yor can always revert to the original file by copying the contents of user.function.class.sample.php in user.function.class.php
