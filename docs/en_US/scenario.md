# Scenarios

**Tools → Scenarios**

<small>[Keyboard/Mouse Shortcuts](shortcuts.md)</small>

As the true "brain" of home automation, scenarios allow you to interact with the real world in an *intelligent* way.

## Management

Here you'll find a list of your Jeedom scenarios, along with features to help you manage them effectively:

- **Add**: Allows you to create a scenario. The procedure is described in the next chapter.
- **Disable Scenarios**: Disables all scenarios. This option is rarely used and should be used with caution, as no scenarios will run anymore.
- **Overview**: Provides an overview of all scenarios. You can change the values for **active**, **visible**, **multi-launch**, **synchronous mode**, **Log**, and **Timeline** (these settings are described in the next chapter). You can also access the logs for each scenario and run them individually.

## My Scenarios

In this section, you’ll find a **list of scenarios** that you’ve created. They’re sorted by their **group**, if one has been defined for each scenario. Each scenario is displayed with its **name** and **parent object**. The **grayed-out scenarios** are the ones that are disabled.

> **Tip**
>
> You can open a scenario by doing the following:
>
> - Click on one of them.
> - Ctrl-click or middle-click to open it in a new browser tab.

You have a search engine that allows you to filter the display of scenarios. Pressing the Esc key cancels the search.
To the right of the search field, there are three buttons that appear in several places throughout Jeedom:

- The cross icon to cancel the search.
- The folder is open to expand all panels and display all scenarios.
- The folder closes to fold up all the panels.

Once you're in the scenario configuration screen, right-clicking on the scenario tabs will bring up a context menu. You can also use Ctrl+click or the middle mouse button to open another scenario directly in a new browser tab.

## Create | Edit a scenario

After clicking **Add**, you must choose a name for your scenario. You will then be redirected to the page with its general settings.
Before that, at the top of the page, you’ll find some useful features for managing this scenario:

- **ID**: Next to the word **General**, this is the scenario's ID.
- **Status**: *Stopped* or *In Progress*; this indicates the current status of the scenario.
- **Previous/Next**: Allows you to undo or redo an action.
- **Add a block**: Allows you to add a block of the desired type to the scenario (see below).
- **Log**: Displays the scenario logs.
- **Duplicate**: Allows you to copy the scenario to create a new one with a different name.
- **Links**: Allows you to view the graph of elements related to the scenario.
- **Text Editor**: Displays a window that allows you to edit the scenario in text/JSON format. Don't forget to save.
- **Export**: Allows you to obtain a plain text version of the scenario.
- **Template**: Allows you to access templates and apply one to the scenario from the Market (explained at the bottom of the page).
- **Search**: Opens a search field to search within the scenario. This search expands collapsed blocks if necessary and collapses them again after the search is complete.
- **Run**: Allows you to start the scenario manually (regardless of triggers). Save first to apply any changes.
- **Delete**: Delete the scenario.
- **Save**: Save the changes you've made.

> **Tips**
>
> Two tools will also be invaluable to you when setting up scenarios:
    > - Variables, visible in **Tools → Variables**
    > - The expression tester, accessible via **Tools → Expression Tester**
>
> **Clicking the Run button while holding down the Ctrl key** allows you to save, run, and view the scenario log directly (provided the log level is not set to None).

## General Tab

In the **General** tab, you'll find the main settings for the scenario:

- **Scenario Name**: The name of your scenario.
- **Display Name**: The name used for display purposes. Optional; if left blank, the name of the scenario is used.
- **Group**: Allows you to organize scenarios by categorizing them into groups (visible on the scenarios page and in their context menus).
- **Active**: Enables the scenario. If not active, it will not be executed by Jeedom, regardless of the trigger mode.
- **Visible**: Makes the scenario visible (Dashboard).
- **Parent object**: Assignment to a parent object. It will then be visible or hidden depending on that parent.
- **Timeout in seconds (0 = unlimited)**: The maximum allowed execution time for this scenario. After this time has elapsed, the scenario's execution is terminated.
- **Multiple launches**: Check this box if you want the scenario to be able to be launched multiple times simultaneously.

>**IMPORTANT**
>
>Multiple launches occur within the same second; in other words, if you have two launches in the same second without the checkbox selected, the scenario will still run twice (even though it shouldn’t). Similarly, when there are multiple launches in the same second, some launches may lose their tags. In conclusion, you MUST avoid multiple launches in the same second.

- **Synchronous mode**: Runs the scenario in the current thread instead of a dedicated thread. This speeds up scenario execution but may cause the system to become unstable. Be sure not to run complex scenarios or those containing sleep or wait commands in synchronous mode, as this causes Jeedom to behave erratically and will not be covered by support.
- **Log**: The type of log you want for the scenario. You can disable logging for the scenario or, conversely, have it appear in Analysis → Real-Time.
- **Timeline**: Allows you to track the scenario in the timeline (see the History documentation).
- **Icon**: Allows you to choose an icon for the scenario instead of the default icon.
- **Description**: Allows you to write a short description of your scenario.
- **Scenario Mode**: The scenario can be scheduled, triggered, or both. You can then choose to specify the trigger(s) (up to 15 triggers) and the schedule(s).

> **Tip**
>
> In triggered mode, conditions can now be entered. For example: ``#[Garage][Open Garage][Ouverture]# == 1``
> Note: You can have a maximum of 28 triggers/schedules for a scenario.

> **Scheduled Tip Mode**
>
> The scheduled mode uses **Cron** syntax. For example, you can run a scenario every 20 minutes with  `*/20 * * * *`, or at 5 a.m. to set up a variety of things for the day with ``0 5 * * *``. The ? to the right of a schedule allows you to configure it without needing to be an expert in Cron syntax. You can also specify a start time in the format `Gi` (time without leading zeros and minutes, example for `09h15` => `915` or for `23h40` => `2340`). This time can be the result of a calculation (using a command or a tag), for example: `#sunset# + 10` for a launch 10 minutes after sunset. Note that for a launch 1 hour and 30 minutes after sunset, you must set `#sunset# + 130`. Please note that when using a syntax other than cron, Jeedom will not be able to provide you with the dates of previous or subsequent runs.

## "Scenario" tab

This is where you’ll build your scenario. After creating the scenario, its content is empty, so it won’t do... anything. You’ll need to start by **adding a block** using the button on the right. Once a block has been created, you can add another **block** or an **action** to it.

For added convenience and to avoid having to constantly rearrange blocks in the scenario, a block is added after the field where the mouse cursor is located.
*For example, if you have about ten blocks, and you click in the IF condition of the first block, the new block will be added after that block, at the same level. If no field is active, it will be added at the end of the scenario.*

> **Tip**
>
> In conditions and actions, it's best to use single quotes (') instead of double quotes (").

> **Tip**
>
> Pressing Ctrl+Shift+Z or Ctrl+Shift+Y allows you to **undo** or **redo** a change (adding an action, a block, etc.).

## Blocks

Here are the different types of blocks available:

- **If/Then/Else**: Allows you to perform actions based on conditions (if this, then that).
- **Action**: Allows you to trigger simple actions without any conditions.
- **Loop**: Allows you to perform actions repeatedly, from 1 up to a specified number, a random number, a sensor value, etc. *(maximum duration: 1 hour)*
- **In**: Allows you to trigger an action in X minute(s) (0 is a valid value). The key feature is that actions are triggered in the background, so they do not block the rest of the scenario. It is therefore a non-blocking block.
- **A**: Tells Jeedom to trigger the block’s actions at a specific time (in the format hhmm). This block is non-blocking. For example: 0030 for 12:30 a.m., 0146 for 1:46 a.m., and 1050 for 10:50 a.m.
- **As long as**: Allows you to perform actions as long as a condition is true. *(maximum duration: 1 hour)*
- **Code**: Allows you to write directly in PHP code (requires some knowledge and can be risky, but offers complete freedom).
- **Comment**: Allows you to add comments to your scenario.

Each block has its own options to make it easier to use:

- On the left:
  - The two-way arrow lets you move a block or an action to reorder them in the scenario.
  - The eye icon lets you collapse a block (*collapse*) to reduce its visual impact. Ctrl-click the eye icon to collapse or expand all blocks.
  - Checking this box completely disables the block without deleting it. It will therefore not be executed.

- On the right:
  - The Copy icon lets you copy the block to create a copy elsewhere. Ctrl-click the icon to cut the block (copy then delete).
  - The Paste icon allows you to paste a copy of the previously copied block after the block on which you are using this function.  Ctrl-clicking the icon replaces the block with the copied block.
  - The icon—allows you to delete the block, with a confirmation prompt. Ctrl-clicking deletes the block without confirmation.

### If/Then/Else Blocks | Loop | In | A

When it comes to conditions, Jeedom tries to make it possible to write them in natural language as much as possible while remaining flexible.
> You MUST NOT use [ ] in conditional tests; only parentheses () are allowed.

There are three buttons on the right side of this type of block for selecting an item to test:

- **Search for a command**: Allows you to search for a command among all those available in Jeedom. Once the command is found, Jeedom opens a window asking you which test you want to perform on it. If you choose **Leave blank**, Jeedom will add the command without performing a comparison. You can also choose **and** or **or** before **Next** to chain together tests on different devices.
- **Search for a scenario**: Allows you to search for a scenario to test.
- **Search for a device**: Same as for a device.

> **Note**
>
> On "If/Then/Else" blocks of type Si/Alors/Sinon, circular arrows located to the left of the condition field allow you to enable or disable the repetition of actions if the condition evaluation yields the same result as the previous evaluation.
> IF expression != 0 is equivalent to IF expression, and IF expression == 0 is equivalent to IF NOT expression

> **Tip**
>
> There is a list of tags that provide access to variables from the current or another scenario, as well as the time, date, a random number, and more. See the sections on commands and tags below for more information.

Once you've entered the condition, click the "Add" button on the left to add a new **block** or an **action** to the current block.

### Code Block

The Code block allows you to execute PHP code. It is therefore very powerful but requires a solid understanding of the PHP language.

#### Access to commands (sensors and actuators)

- ``cmd::byString($string);`` : Returns the corresponding command object.
  - ``$string``: Link to the desired command: ``#[objet][equipement][commande]#`` (e.g., ``#[Appartement][Alarme][Actif]#``)
- ``cmd::byId($id);`` : Returns the corresponding command object.
  - ``$id`` : ID of the desired command.
- ``$cmd->execCmd($options = null);`` : Executes the command and returns the result.
  - ``$options`` : Options for executing the command (may be specific to the plugin). Basic options (subtype of the command):
    - ``message`` : ``$option = array('title' => 'titre du message , 'message' => 'Mon message');``
    - ``color`` : ``$option = array('color' => 'couleur en hexadécimal');``
    - ``slider`` : ``$option = array('slider' => 'valeur voulue de 0 à 100');``

#### Access to logs

- ``log::add('filename','level','message');``
  - ``filename`` : Name of the log file.
  - ``level`` : [debug], [info], [error], [event].
  - ``message`` : Message to be written to the logs.

#### Access to Scenarios

- ``$scenario->getName();`` : Returns the name of the current scenario.
- ``$scenario->getGroup();`` : Returns the scenario group.
- ``$scenario->getIsActive();`` : Returns the status of the scenario.
- ``$scenario->setIsActive($active);`` : Allows you to enable or disable the scenario.
  - ``$active`` : 1 active, 0 inactive.
- ``$scenario->running();`` : Indicates whether the scenario is currently running or not (true / false).
- ``$scenario->save();`` : Saves changes.
- ``$scenario->setData($key, $value);`` : Saves a piece of data (variable).
  - ``$key`` : value key (int or string).
  - ``$value`` : value to store (``int``, ``string``, ``array`` or ``object``).
- ``$scenario->getData($key);`` : Retrieves a piece of data (variable).
  - ``$key => 1`` : value key (int or string).
- ``$scenario->removeData($key);`` : Deletes a piece of data.
- ``$scenario->setLog($message);`` : Writes a message to the scenario log.
- ``$scenario->persistLog();`` : Forces the log to be written (otherwise, it is written only at the end of the scenario). Note that this may slow down the scenario slightly.

> **Tip**
>
> Added a search function to the Code block: Search: Ctrl + F, then Enter; Next result: Ctrl + G; Previous result: Ctrl + Shift + G

[Scenarios: Little codes among friends](https://kiboost.github.io/jeedom_docs/jeedomV4Tips/CodesScenario/)

### Comment Block

The Comment block behaves differently when it is hidden. Its buttons on the left and the block title disappear, and reappear when you hover over them. Similarly, the first line of the comment is displayed in bold.
This allows you to use this block as a purely visual separator within the scenario.

### Actions

Actions added to blocks have several options:

- A **checked** box to ensure that this command is properly included in the scenario.
- A **parallel** checkbox so that this command runs in parallel (at the same time) with the other selected commands.
- A **vertical double arrow** to move the action. Just drag and drop it from there.
- A button to **delete** the action.
- A button for specific actions, with a description of each action (when you hover over it).
- A button to search for an action command.

> **Tip**
>
> Depending on the selected command, various additional fields may appear on the screen.

## Possible substitutions

### Triggers

There are specific triggers (other than those provided by the commands):

- ``#start#`` : Triggered when Jeedom (re)starts.
- ``#begin_backup#`` : Event sent at the start of a backup.
- ``#end_backup#`` : Event sent at the end of a backup.
- ``#begin_update#`` : Event sent at the start of an update.
- ``#end_update#`` : Event sent at the end of an update.
- ``#begin_restore#`` : Event sent at the start of a Restoration.
- ``#end_restore#`` : Event sent at the end of a Restoration.
- ``#user_connect#`` : User login, the tag `#trigger_value#` contains the user's name.
- ``#variable(nom_variable)#`` : Change in the value of the variable `variable_name`.
- ``#genericType(GENERIC, #[Object]#)#`` : Changing a GENERIC-type info command in the Object object.
- ``#new_eqLogic#`` : An event triggered when a new device is created; the tags include id (ID of the created device), name (name of the created device), and eqType (type/plugin of the created device)

You can also trigger a scenario using the HTTP API described [here](api_http.md).

### Comparison Operators and Links Between Conditions

You can use any of the following symbols for comparisons in conditions:

- ``==`` : Equals.
- ``>`` : Strictly greater than.
- ``>=`` : Greater than or equal to.
- ``<`` : Strictly less than.
- ``<=`` : Less than or equal to.
- ``!=`` : Different from, not equal to.
- ``matches`` : Contains. Ex: ``[Salle de bain][Hydrometrie][etat] matches "/humide/"``.
- ``not(…​ matches …​)`` : Does not contain. Ex:  ``not([Salle de bain][Hydrometrie][etat] matches "/humide/")``.

You can combine any comparison with the following operators:

Whether you're comparing different devices or the same one, you must always specify the device.
``[Salle de bain][Hydrometrie][température] >= 18 && [Salle de bain][Hydrometrie][température] <= 22``

- ``&&`` : and. **Warning**, the use of  : ``ET`` / ``et`` / ``AND`` / ``and`` is not recommended; in some cases it may work, but with certain PHP functions it will not work.
- ``||`` : or. **Warning**: the use of  : ``OU`` / ``ou`` / ``OR`` / ``or`` is not recommended; in some cases it may work, but with certain PHP functions it will not work.
- ``xor``  : or exclusive. **Warning**, the use of  : ``XOR`` / ``^`` is not recommended; in some cases it may work, but with certain PHP functions it will not work.

### Tags

When a scenario is executed, a tag is replaced with its value. You can use the following tags:

> **Tip**
>
> To display leading zeros, use the Date() function. See [here](https://www.php.net/manual/fr/datetime.format.php).

- ``#seconde#`` : Current second (without leading zeros, e.g., 6 for 08:07:06).
- ``#hour#`` : Current time in 24-hour format (without leading zeros). Ex: 8 for 08:07:06 or 17 for 17:15.
- ``#hour12#`` : Current time in 12-hour format (without leading zeros). Example: 8 for 08:07:06.
- ``#minute#`` : Current minute (without leading zeros). Ex: 7 for 08:07:06.
- ``#day#`` : Current day (without leading zeros). Ex: 6 for 07/06/2017.
- ``#month#`` : Current month (without leading zeros). Ex: 7 for 06/07/2017.
- ``#year#`` : Current year.
- ``#time#`` : Current hour and minute. Example: 1715 for 5:15 p.m.
- ``#timestamp#`` : Number of seconds since January 1, 1970.
- ``#date#`` : Day and month. Note that the first number is the month. Example: 1215 for December 15.
- ``#week#`` : This Week's Issue.
- ``#sday#`` : Name of the day of the week. Ex: Saturday.
- ``#nday#`` : Day of the week from 0 (Sunday) to 6 (Saturday).
- ``#smonth#`` : Name of the month. Ex: January.
- ``#IP#`` : Jeedom's internal IP address.
- ``#hostname#`` : Name of the Jeedom device.
- ``#jeedomName#`` : Jeedom name.
- ``#trigger#`` : Perhaps:
  - ``api`` if the launch was triggered by the API,
  - ``TYPEcmd`` if the launch was triggered by a command, with type replaced by the plugin ID (e.g., virtualCmd),
  - ``schedule`` if it was triggered by a scheduled event,
  - ``user`` if it was started manually,
  - ``start`` to be launched when Jeedom starts up.
- ``#trigger_id#`` : If a command triggered the scenario, this tag contains the ID of the command that triggered it. Example: ``#trigger_id# == 19``
- ``#trigger_name#`` : If a command triggered the scenario, this tag contains the name of the command (in the format [object][device][command]). Example: ``#trigger_name# == '[cuisine][lumiere][etat]'``
- ``#trigger_value#`` : If a command triggered the scenario, this tag holds the value of the command that triggered the scenario. Tip: If you want the current value of the command that triggered the scenario (rather than its value at the time of triggering), you can use: ``##trigger_id##`` (double #)
- ``#latitude#`` : Retrieves the latitude information specified in the Jeedom configuration
- ``#longitude#`` : Retrieves the longitude information specified in the Jeedom configuration
- ``#altitude#`` : Retrieves the altitude information entered in the Jeedom configuration
- ``#sunrise#`` : Allows you to retrieve the sunrise time, provided that the latitude and longitude are specified in the Jeedom configuration
- ``#sunset#`` : Retrieves the sunset time provided that the latitude and longitude are specified in the Jeedom configuration

You also have the following additional tags if your scenario was triggered by an interaction:

- #query#: Interaction that triggered the scenario.
- #profile#: Profile of the user who triggered the scenario (may be empty).

> **Important**
>
> When a scenario is triggered by an interaction, it is always executed in fast mode. This means it runs in the interaction’s thread rather than in a separate thread.

### Calculation functions

Several functions are available for the devices:

- ``average(commande,période)`` & ``averageBetween(commande,start,end)`` : Returns the average of the command over the period (period=[month, day, hour, min] or [PHP expression](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)) or between the two specified terminals (in the form of ``Y-m-d H:i:s`` or [PHP expression](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)).

- ``averageTemporal(commande,période)`` & ``averageTemporalBetween(commande,start,end)`` : Returns the average of the command values, weighted by their duration over the specified period (period=[month,day,hour,min] or [PHP expression](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)) or between the two specified terminals (in the form of ``Y-m-d H:i:s`` or [PHP expression](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)).

- ``min(commande,période)`` & ``minBetween(commande,start,end)`` : Returns the minimum command value for the specified period (period=[month, day, hour, min] or [PHP expression](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)) or between the two specified terminals (in the form of ``Y-m-d H:i:s`` or [PHP expression](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)).

- ``max(commande,période)`` & ``maxBetween(commande,start,end)`` : Provide the maximum command over the period (period=[month, day, hour, min] or [PHP expression](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)) or between the two specified terminals (in the form of ``Y-m-d H:i:s`` or [PHP expression](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)).

- ``duration(commande, valeur, période)`` & ``durationbetween(commande,valeur,start,end)`` : Returns the duration in minutes during which the device maintained the selected value over the specified period (period=[month, day, hour, min] or [PHP expression](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)) or between the two specified terminals (in the form of ``Y-m-d H:i:s`` or [PHP expression](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)).

- ``statistics(commande,calcul,période)`` & ``statisticsBetween(commande,calcul,start,end)`` : Returns the results of various statistical calculations (sum, count, std, variance, avg, min, max) for the specified time period (period=[month, day, hour, min] or [PHP expression](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)) or between the two specified terminals (in the form of ``Y-m-d H:i:s`` or [PHP expression](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)).

- ``tendance(commande,période,seuil)`` : Shows the trend of the command over the specified time period (period=[month, day, hour, min] or [PHP expression](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)).

- ``stateDuration(commande)`` : Returns the time in seconds since the last value change.
-1: No history exists, or the value is not present in the history.
-2: The command is not logged.

- ``lastChangeStateDuration(commande,valeur)`` : Returns the number of seconds since the last state change for the value passed as a parameter.
-1: No history exists, or the value is not present in the history.
-2 The command is not logged

- ``lastStateDuration(commande,valeur)`` : Shows the duration in seconds during which the device last had the selected value.
-1: No history exists, or the value is not present in the history.
-2: The command is not logged.

- ``age(commande)`` : Returns the age of the command value in seconds (``collecDate``)
-1: The command does not exist or is not of the info type.

- ``stateChanges(commande,[valeur], période)`` & ``stateChangesBetween(commande, [valeur], start, end)`` : Returns the number of state changes (to a specific value if specified, or relative to the current value if not specified) over the period (period=[month, day, hour, min] or [PHP expression](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)) or between the two specified terminals (in the form of ``Y-m-d H:i:s`` or [PHP expression](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)).

- ``lastBetween(commande,start,end)`` : Returns the last recorded value for the device between the two requested terminals (in the form ``Y-m-d H:i:s`` or [PHP expression](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)).

- ``variable(mavariable,valeur par défaut)`` : Retrieves the value of a variable or the desired default value.

- ``genericType(GENERIC, #[Object]#)`` : Retrieves the sum of the GENERIC-type data in the Object object.

- ``scenario(scenario)`` : Returns the status of the scenario.
1: In progress,
0: Stopped,
-1: Disabled,
-2: The scenario does not exist,
-3: The state is inconsistent.
To get the "human-readable" name of the scenario, you can use the dedicated button to the right of the scenario search.

- ``lastScenarioExecution(scenario)`` : Returns the time in seconds since the scenario was last run.
0: The scenario does not exist

- ``collectDate(cmd,[format])`` : Returns the date of the last data collection for the command specified as a parameter; the optional second parameter allows you to specify the return format (details [here](https://www.php.net/manual/fr/datetime.format.php)).
-1: The command cannot be found,
-2: The command does not have the info type.

- ``valueDate(cmd,[format])`` : Returns the date of the last known value for the command specified as a parameter; the optional second parameter allows you to specify the return format (details [here](https://www.php.net/manual/fr/datetime.format.php)).
-1: The command cannot be found,
-2: The command does not have the info type.

- ``eqEnable(equipement)`` : Returns the status of the device.
-2: The device cannot be found,
1: The device is active,
0: The device is inactive.

- ``value(cmd)`` : Returns the value of a command if it is not automatically provided by Jeedom (e.g., when storing the command name in a variable)

- ``tag(montag,[defaut])`` : Retrieves the value of a tag or the default value if the tag does not exist.

- ``name(type,commande)`` : Retrieves the name of the command, device, or object. Type: cmd, eqLogic, or object.

- ``lastCommunication(equipment,[format])`` : Returns the date of the last data update for the device specified as a parameter; the optional second parameter allows you to specify the return format (details [here](https://www.php.net/manual/fr/datetime.format.php)). A return value of -1 means the device cannot be found. The date of the last update is calculated based on the "information" type command and the date the data was collected.

- ``color_gradient(couleur_debut,couleur_fin,valuer_min,valeur_max,valeur)`` : Returns a color calculated based on a value within the color_start/color_end range. The value must be between value_min and value_max.

The time periods and intervals for these functions can also be used with [PHP expressions](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative) such as:

- ``Now`` : now.
- ``Today`` : 00:00 today (allows you, for example, to get results for the day if between ``Today`` and ``Now``).
- ``Last Monday`` : Last Monday at 12:00 a.m.
- ``5 days ago`` : 5 days ago.
- ``Yesterday noon`` : yesterday at noon.
- Etc.

Here are some practical examples to help you understand the values returned by these different functions:

| Outlet settings: | 000 (for 10 minutes) 11 (for 1 hour) 000 (for 10 minutes)    |
|--------------------------------------|--------------------------------------|
| ``average(prise,période)``             | Returns the average of the 0s and 1s (may  |
| | be influenced by polling) |
| ``averageBetween(#[Salle de bain][Hydrometrie][Humidité]#,2015-01-01 00:00:00,2015-01-15 00:00:00)`` | Returns the average command amount between January 1, 2015, and January 15, 2015 |
| ``min(prise,période)``                 | Returns 0: the outlet was successfully turned off during the period |
| ``minBetween(#[Salle de bain][Hydrometrie][Humidité]#,2015-01-01 00:00:00,2015-01-15 00:00:00)`` | Shows the minimum command amount between January 1, 2015, and January 15, 2015 |
| ``max(prise,période)``                 | Returns 1: The outlet was turned on during the period |
| ``maxBetween(#[Salle de bain][Hydrometrie][Humidité]#,2015-01-01 00:00:00,2015-01-15 00:00:00)`` | Shows the highest command amount between January 1, 2015, and January 15, 2015 |
| ``duration(prise,1,période)``          | Returns 60: The outlet was on (set to 1) for 60 minutes during the period |
| ``durationBetween(#[Salon][Prise][Etat]#,0,Last Monday,Now)``   | Returns the number of minutes the outlet has been off since last Monday. |
| ``statistics(prise,count,période)``    | Returns 8: There were 8 status updates during the period |
| ``tendance(prise,période,0.1)``        | Returns -1: downward trend    |
| ``stateDuration(prise)``               | Returns 600: The outlet has been in its current state for 600 seconds (10 minutes) |
| ``lastChangeStateDuration(prise,0)``   | Returns 600: The outlet turned off (switched to 0) for the last time 600 seconds (10 minutes) ago     |
| ``lastChangeStateDuration(prise,1)``   | Returns 4200: the outlet turned on (state changed to 1) for the last time 4200 seconds ago (1 hour and 10 minutes) |
| ``lastStateDuration(prise,0)``         | Returns 600: The outlet has been off for 600 seconds (10 minutes)     |
| ``lastStateDuration(prise,1)``         | Returns 3600: The outlet was last turned on 3600 seconds (1 hour) ago |
| ``stateChanges(prise,période)``        | Returns 3: the outlet changed state 3 times during the period (if the info command is of type binary) |
| ``stateChanges(prise,0,période)``      | Count 2: The outlet turned off (change to 0) twice during the period |
| ``stateChanges(prise,1,période)``      | Return 1: The outlet turned on (changed to 1) once during the  period |
| ``lastBetween(#[Salle de bain][Hydrometrie][Température]#,Yesterday,Today)`` | Returns the last temperature recorded yesterday. |
| ``variable(plop,10)``                  | Returns the value of the variable plop or 10 if it is empty or does not exist |
| ``scenario(#[Salle de bain][Lumière][Auto]#)`` | Returns 1 if running, 0 if stopped, -1 if disabled, -2 if the scenario does not exist, and -3 if the state is inconsistent |
| ``lastScenarioExecution(#[Salle de bain][Lumière][Auto]#)``   | Returns 300 if the scenario was last run 5 minutes ago |
| ``collectDate(#[Salle de bain][Hydrometrie][Humidité]#)``     | Posted February 14, 2021, 5:50:12 PM |
| ``valueDate(#[Salle de bain][Hydrometrie][Humidité]#)`` | Posted on February 14, 2021, at 5:45:12 PM |
| ``eqEnable(#[Aucun][Basilique]#)``       | Returns -2 if the device cannot be found, 1 if the device is active, and 0 if it is inactive |
| ``tag(montag,toto)``                   | Returns the value "montag" if it exists; otherwise, returns the value "toto" |
| ``name(eqLogic,#[Salle de bain][Hydrometrie][Humidité]#)``     | Back to Hydrometry |

### Mathematical Functions

A toolkit of generic functions can also be used to perform conversions or calculations:

- ``rand(1,10)`` : Generates a random number between 1 and 10.
- ``randText(texte1;texte2;texte…​..)`` : Returns one of the texts at random (separate the texts with a semicolon ;). There is no limit to the number of texts.
- ``randomColor(min,max)`` : Generates a random color within a range (0 = red, 50 = green, 100 = blue).
- ``trigger(commande)`` : Allows you to identify the trigger for the scenario or to verify whether the command passed as a parameter actually triggered the scenario. **=> Deprecated; it is better to use the #trigger# tag**
- ``triggerValue()`` : Allows you to see the value of the scenario's trigger. **=> Deprecated; it's better to use the #trigger_value# tag**
- ``round(valeur,[decimal])`` : Rounds up, [decimal] number of decimal places.
- ``odd(valeur)`` : Determines whether a number is odd or even. Returns 1 if odd, 0 otherwise.
- ``median(commande1,commande2…​.commandeN)`` : Returns the median of the values.
- ``avg(commande1,commande2…​.commandeN)`` : Returns the average of the values.
- ``time_op(time,value)`` : Allows you to perform operations on time, using `time=time` (e.g., 1530) and `value=value` to add or subtract in minutes.
- ``time_between(time,start,end)`` : Allows you to check whether a time falls between two values using ``time=temps`` (e.g., 1530), ``start=temps``, ``end=temps``. The start and end values may span midnight.
- ``time_diff(date1,date2[,format, round])`` : Calculates the difference between two dates (dates must be in the YYYY/MM/DD HH:MM:SS format). By default, the method returns the difference in days. You can specify the unit as seconds (s), minutes (m), or hours (h). Example in seconds ``time_diff(2019-02-02 14:55:00,2019-02-25 14:55:00,s)``. The difference is returned as an absolute value, unless you specify ``f`` (``sf``, ``mf``, ``hf``, ``df``). You can also use ``dhms`` that will not return, for example ``7j 2h 5min 46s``. The optional "round" parameter rounds to x decimal places (2 by default). Example: ``time_diff(2020-02-21 20:55:28,2020-02-28 23:01:14,df, 4)``.
- ``formatTime(time)`` : Formats the return value of a channel ``#time#``.
- ``floor(time/60)`` : Converts seconds to minutes, or minutes to hours (``floor(time/3600)`` (seconds to hours).
- ``convertDuration(secondes)`` : Converts seconds to days, hours, minutes, and seconds.

And here are some practical examples:

| Function example | Returned result |
|--------------------------------------|--------------------------------------|
| ``randText(il fait #[salon][oeil][température]#;La température est de #[salon][oeil][température]#;Actuellement on a #[salon][oeil][température]#)`` | The function will return one of these texts at random each time it is executed. |
| ``randomColor(40,60)``                 | Returns a random color  close to green. |
| ``round(#[Salle de bain][Hydrometrie][Humidité]# / 10)`` | Returns 9 if the humidity percentage is 85 |
| ``odd(3)``                             | Returns 1 |
| ``median(15,25,20)``                   | Returns 20
| ``avg(10,15,18)``                      | Returns 14.3 |
| ``time_op(#time#, -90)``               | if it is 4:50 p.m., returns: 1650 - 0130 = 1520 |
| ``formatTime(1650)``                   | Posted at 4:50 p.m. |
| ``floor(130/60)``                     | Returns 2 (minutes if 130s, or hours if 130m) |
| ``convertDuration(3600)``             | Returns 1h 0min 0s |
| ``convertDuration(duration(#[Chauffage][Module chaudière][Etat]#,1, first day of this month)*60)`` | Returns the time in days/hours/minutes that the module has been in state 1 since the first day of the month |

### Various Features

- ``sun(elevation)`` : Displays the sun's elevation in degrees (note: you must have entered your geographic coordinates in the Jeedom configuration)
- ``sun(azimuth)`` : Displays the sun's azimuth in degrees (note: you must have entered your geographic coordinates in the Jeedom configuration)

### Specific Commands

In addition to home automation commands, you have access to the following actions:

- **Pause** (sleep): Pause for x seconds. *(maximum duration: 1 hour)*
- **variable** (variable): Create or modify a variable or a variable's value.
- **Delete variable** (delete_variable): Allows you to delete a variable.
- **genericType(GENERIC, #[Object]#)**: Modifies an info command (event) or action (execCmd) by Generic Type within an object. For example, turn off all the lights in the living room.
- **Scenario**: Allows you to control scenarios. The "tags" section lets you send tags to the scenario, e.g., montag=2 (note: use only letters from a to z; no uppercase letters, no accents, and no special characters). You can retrieve the tag in the target scenario using the tag(montag) function.
  - Start: Starts the scenario in a separate thread. The started scenario runs independently of the calling scenario.
  - Start (Sync): Starts the called scenario and pauses the calling scenario until the called scenario has finished running.
  - Stop: Stops the scenario.
  - Activate: Activates a deactivated scenario.
  - Disable: Disables the scenario. It will no longer run, regardless of the triggers.
  - Reset IS: Resets the status of the **IS**. This status is used to prevent an **IS** action from repeating if the condition evaluation yields the same result as the previous evaluation.
- **Stop** (stop): Stops the scenario.
- **Wait**: Waits until the condition is met; the timeout is in seconds. *(maximum duration: 1 hour)*
- **Go to Design** (gotodesign): Changes the design displayed in all browsers to the requested design.
- **Add a log** (log): Allows you to add a message to the logs.
- **Create a message** (message): Allows you to add a message to the message center.
- **Enable/Disable Hide/Show a Device** (device): Allows you to change a device's properties to make it visible or invisible, active or inactive.
- **Make a request** (ask): Tells Jeedom to ask the user a question. The answer is stored in a variable; you can then simply check its value.
Currently, only the SMS, Slack, Telegram, and Snips plugins are compatible, as well as the mobile app.
Note: This function is blocking. Until a response is received or the timeout is reached, the scenario waits. Note: To allow for a free-form response, include * in the list of possible responses.
- **Shut Down Jeedom** (jeedom_poweroff): Instructs Jeedom to shut down.
- **Return Text/Data** (scenario_return): Returns text or a value for an interaction, for example.
- **Icon**: Allows you to change the icon representing the scenario.
- **Alert**: Displays a small alert message in all browsers that have a Jeedom page open. You can also choose from 4 alert levels.
- **Pop-up** (popup): Displays a pop-up that must be confirmed on all browsers that have a Jeedom page open.
- **Report**: Allows you to export a view in PDF, PNG, JPEG, or SVG format and send it via a message-type command. Please note that if your Internet connection uses unsigned HTTPS, this feature will not work. You must use HTTP or signed HTTPS. The "delay" is in milliseconds (ms).
- **Delete IN/A blocks from a program** (remove_inat): Deletes the programming for all IN and A blocks from a scenario.
- **Event**: Allows you to push a value into an information-type command at will.
- **Tag** (tag): Allows you to add or modify a tag (unlike variables, which persist after the scenario ends, a tag exists only during the current execution of the scenario).
- **Dashboard Icon Colors** (setColoredIcon): Enables or disables colored icons on the dashboard.
- **Change Theme** (changetheme): Allows you to switch the current interface theme to Dark or Light.
- **History Export** (exportHistory): allows you to export the history of a command as a CSV file (to be sent via email, for example). You can include multiple commands (separated by &&). The time period is specified as follows:
  - "-1 month" => -1 month
  - "-1 day at midnight" => -1 day at midnight
  - "now" => now
  - "monday this week midnight" => Monday of this week at midnight
  - "last Sunday at 11:59 p.m." => the previous Sunday at 11:59 p.m.
  - "last day of previous month 23:59" => the last day of the previous month at 11:59 p.m.
  - "midnight on the first day of January this year" => midnight on the first day of January
  - ...

### Scenario template

This feature allows you to convert a scenario into a template so that you can, for example, apply it to another Jeedom instance.

Clicking the **template** button at the top of the page opens the template management window.

From here, you can:

- Send a template to Jeedom (JSON file retrieved earlier).
- View the list of available scenarios on the Market.
- Create a template based on the current scenario (don't forget to give it a name).
- View the templates currently available on your Jeedom.

By clicking on a template, you can:

- **Share**: Share the template on the Market.
- **Delete**: Delete the template.
- **Download**: Download the template as a JSON file to upload it to another Jeedom, for example.

Below, you'll find the section where you can apply your template to the current scenario.

Since commands may vary from one Jeedom system to another or from one setup to another, Jeedom asks you to map the commands used when creating the template to the ones in your system. Simply fill in the command mappings and then apply the changes.

## Adding a PHP function

> **IMPORTANT**
>
> Adding PHP functions is for advanced users only. Even the slightest error can be fatal for your Jeedom.

### Setup

Go to Jeedom's configuration, then OS/DB, and launch the file editor.

Go to the "data" folder, then to "PHP," and click on the file "user.function.class.php."

This is the *class* where you can add your functions; you'll find an example of a basic function there.

> **IMPORTANT**
>
> If you encounter a problem, you can always revert to the original file by copying the contents of ``user.function.class.sample.php`` in ``user.function.class.php``
