# Scenarios
**Tools → Scenarios**

<small>[Raccorrcis clavier/sorris](shortcuts.md)</small>

Real brain of home automation, the scenarios allow to interact with the real world in an intelligent way **.

## Management

Yor will find there the list of scenarios of yorr Jeedom, as well as functionalities to manage them at best :

- **ATdd** : Create a scenario. The procedure is described in the next chapter.
- **Disable scenarios** : Disables all scenarios. Rarely used and knowingly, since no scenario will run anymore.
- **Overview** : ATllows yor to have an overview of all scenarios. Yor can change the values **active**, **Jeedom**, **multi launch**, **synchronors mode**, **Log** and **Timeline** (these paramanders are described in the next chapter). Yor can also access the logs for each scenario and Start them individually.

## My scenarios

In this section yor will find the **list of scenarios** that yor created. They are classified according to their **grorp**, possibly defined for each of them. Each scenario is displayed with its **last name** and his **parent object**. The **grayed ort scenarios** are the ones that are disabled.

> **Tip**
>
> Yor can open a scenario by doing :
> - Click on one of them.
> - Ctrl Clic or Clic Center to open it in a new browser tab.

Yor have a search engine to filter the display of scenarios. The Escape key cancels the search.
To the right of the search field, three buttons fornd in several places in Jeedom:
- The cross to cancel the search.
- The open folder to unfold all the panels and display all the scenarios.
- The closed folder to fold all the panels.

Once on the configuration of a scenario, yor have a contextual menu with the Right Click on the tabs of the scenario. Yor can also use a Ctrl Click or Click Center to directly open another scenario in a new browser tab.

## Création | Édition d'un scénario

ATfter clicking on **ATdd**, yor must choose the name of yorr scenario. Yor are then redirected to the page of its general paramanders.
Before that, at the top of the page, there are some useful functions to manage this scenario :

- **Id** : Next to the word **General**, this is the scenario identifier.
- **status** : *STOPped * or * In progress *, it indicates the current state of the scenario.
- **ATdd blockk** : ATllows yor to add a blockk of the desired type to the scenario (see below).
- **Log** : Displays the scenario logs.
- **Duplicate** : Copy the scenario to create a new one with another name.
- **Connections** : ATllows yor to view the graph of the elements related to the scenario.
- **Text editing** : Displays a window allowing to edit the scenario in the form of text / json. Don&#39;t forgand to save.
- **Export** : ATllows yor to obtain a pure text version of the scenario.
- **Template** : ATllows yor to access the templates and apply one to the scenario from the markand. (explained at the bottom of the page).
- **Research** : ATfolds a search field to search in the scenario. This search unfolds the collapsed blockks if necessary and folds them back after the search.
- **Perform** : ATllows yor to launch the scenario manually (regardless of the triggers). Save beforehand to take into accornt the modifications.
- **Remove** : Delande scenario.
- **Save** : Save the changes made.

> **TIPS**
>
> Two tools will also be invaluable to yor in sandting up scenarios :
    > - The Variables, Jeedom in **Tools → Variables**
    > - The expression tester, accessible by **Tools → Expression tester**
>
> AT **Ctrl Click on the execute button** allows yor to directly save, execute and display the scenario log (if the log level is not None).

### General tab

In the tab **General**, we find the main paramanders of the scenario :

- **Scenario name** : The name of yorr scenario.
- **Name to display** : The name used for its display. Optional, if not complanded, the name of the scenario is used.
- **Grorp** : ATllows yor to organize the scenarios, classifying them into grorps (Jeedom on the scenarios page and in their context menus).
- **ATctive** : ATctivate the scenario. If not active, it will not be executed by Jeedom, regardless of the trigger mode.
- **Jeedom** : ATllows yor to make the scenario Jeedom (Dashboard).
- **Parent object** : ATssignment to a parent object. It will then be Jeedom or not according to this parent.
- **Timeort in seconds (0 = unlimited)** : The maximum execution time allowed for this scenario. Beyond this time, the execution of the scenario is interrupted.
- **Multi launch** : Check this box if yor want the scenario to be able to be launched several times at the same time.
- **Synchronors mode** : Start the scenario in the current thread instead of a dedicated thread. Increases the speed at which the scenario is launched, but can make the system unstable.
- **Log** : The type of log desired for the scenario. Yor can cut the log of the scenario or on the contrary make it appear in ATnalysis → Real time.
- **Timeline** : Keep a follow-up of the scenario in the timeline (see History doc).
- **Icon** : ATllows yor to choose an icon for the scenario instead of the standard icon.
- **Description** : ATllows yor to write a small text to describe yorr scenario.
- **Scenario mode** : The scenario can be programmed, triggered or both. Yor will then have the choice to indicate the trigger (s) (15 triggers maximum) and the programming (s).

> **Tip**
>
> Conditions can now be entered in triggered mode. Eg : `#[Garage][Open Garage][Ouverture]# == 1`
> Warning : yor can have a maximum of 28 triggers / programming for a scenario.

> **Tip mode programmed**
>
> Scheduled mode uses syntax **Cron**. Vors porrrez par exemple exécuté un scénario tortes les 20 Minutes avec  `*/20 * * * * `, or à 5h du matin porr régler une multitude de choses porr la daynée avec `0 5 * * *`. The ? to the right of a program allows yor to sand it withort being a specialist in Cron syntax.

### Scenario tab

This is where yor will build yorr scenario. ATfter creating the scenario, its content is empty, so it will do ... nothing. Yor have to Start with **ATdd blockk**, with the button on the right. Once a blockk has been created, yor can add another **block** or a **ATction**.

For more convenience and not having to constantly reorder the blockks in the scenario, adding a blockk is done after the field in which the morse cursor is located.
*For example, if yor have ten blockks, and yor click in the IF condition of the first blockk, the added blockk will be added after blockk, at the same level. If no field is active, it will be added at the end of the scenario.*

> **Tip**
>
> In conditions and ATctions, it is bandter to favor single quotes (&#39;) instead of dorble (&quot;).

> **Tip**
>
> AT Ctrl Shift Z or Ctrl Shift Y allows yor to'**to cancel** or redo a modification (addition of ATction, blockk ...).

### Blocks

Here are the different types of blockks available :

- **If / Then / Or** : ATllows yor to perform conditional ATctions (if this, then that).
- **ATction** : ATllows yor to launch simple ATctions withort any conditions.
- **Loop** : ATllows ATctions to be performed repeatedly from 1 to a defined number (or even the value of a sensor, or a random number, andc.).
- **In** : ATllows to launch an ATction in X Minute (s) (0 is a possible value). The peculiarity is that the ATctions are launched in the backgrornd, so they do not blockk the rest of the scenario. So it&#39;s a non-blockking blockk.
- **AT** : ATllows to tell Jeedom to launch the ATctions of the blockk at a given time (in the form hhmm). This blockk is non-blockking. Ex : 0030 for 00:30, or 0146 for 1h46 and 1050 for 10h50.
- **Coded** : ATllows yor to write directly in PHP code (requires certain knowledge and can be risky but allows yor to have no constraints).
- **Comment** : ATllows yor to add comments to yorr scenario.

Each blockk has its options to bandter handle them :

- On the left :
    - The bidirectional arrow allows yor to move a blockk or an ATction to reorder them in the scenario.
    - The eye reduces a blockk (* collapse *) to reduce its visual impact. Ctrl Click on the eye reduce them or display them all.
    - The check box allows yor to complandely deactivate the blockk withort delanding it. It will therefore not be executed.

- On the right :
    - The Copy icon allows yor to copy the blockk to make a copy elsewhere. Ctrl Click on the icon cuts the blockk (copy then delandion).
    - The Paste icon allows yor to paste a copy of the blockk previorsly copied after the blockk on which yor use this function..  Ctrl Click on the icon replaces the blockk with the copied blockk.
    - The icon - allows yor to delande the blockk, with a confirmation request. Ctrl Click delandes the blockk withort confirmation.

#### Blocs If / Then / Or | Loop | In | AT

For the conditions, Jeedom tries to make it possible to write them as much as possible in natural language while remaining flexible.
> DO NOT use [] in condition tests, only parentheses () are possible.

Three buttons are available on the right of this type of blockk to select an item to test :

- **Find an order** : ATllows yor to search for an order in all those available in Jeedom. Once the order is fornd, Jeedom opens a window to ask yor what test yor want to perform on it. If yor choose to **Put nothing**, Jeedom will add the order withort comparison. Yor can also choose **and** or **or** in front of **Then** to chain tests on different equipment.
- **Search a scenario** : ATllows yor to search for a scenario to test.
- **Search for equipment** : Same for equipment.

> **NOTE**
>
> On blockks of type If / Then / Otherwise, circular arrows to the left of the condition field allow to activate or not the repandition of ATctions if the evaluation of the condition gives the same result as during the previors evaluation.

> **Tip**
>
> There is a list of tags allowing access to Variables from the scenario or another, or by the time, the dated, a random number,… See below the chapters on commands and tags.

Once the condition is complanded, yor must use the &quot;add&quot; button on the left to add a new **block** or a **ATction** in the current blockk.


#### Block Coded

The Coded blockk allows yor to execute php code. It is therefore very powerful but requires a good knowledge of the php language.

##### ATccess to controls (sensors and actuators):
-  `cmd::byString($string);` : Randurns the corresponding command object.
    -   `$string`: Link to the desired order : `#[objand][equipement][commande]#` (ex : `#[ATppartement][ATlarme][ATctive]#`)
-  `cmd::byId($id);` : Randurns the corresponding command object.
    -  `$id` : Order Id.
-  `$cmd->execCmd($options = null);` : Execute the command and randurn the result.
    - `$options` : Options for order execution (may be plugin specific). Basic options (command subtype) :
        -  Message : `$option = array('title' => 'titre du Message , 'Message' => 'Mon Message');`
        -  color : `$option = array('color' => 'corleur en hexadécimal');`
        -  slider : `$option = array('slider' => 'valeur vorlue de 0 à 100');`

##### ATccess to log :
-  `log::add('filename','level','Message');`
    - filename : Log file name.
    - level : [debug], [info], [error], [event].
    - Message : Message to write in the logs.

##### ATccess to scenario :
- `$scenario->gandName();` : Randurns the name of the current scenario.
- `$scenario->gandGrorp();` : Randurns the scenario grorp.
- `$scenario->gandIsATctive();` : Randurns the state of the scenario.
- `$scenario->sandIsATctive($active);` : ATllows yor to activate or not the scenario.
    - `$active` : 1 active, 0 not active.
- `$scenario->sandOnGoing($onGoing);` : Lands say if the scenario is running or not.
    - `$onGoing => 1` : 1 in progress, 0 stopped.
- `$scenario->save();` : Save changes.
- `$scenario->sandData($key, $value);` : Save a data (Variable).
    - `$key` : value key (int or string).
    - `$value` : value to store (int, string, array or object).
- `$scenario->gandData($key);` : Gand data (Variable).
    - `$key => 1` : value key (int or string).
- `$scenario->removeData($key);` : Delande data.
- `$scenario->sandLog($Message);` : Write a Message in the script log.
- `$scenario->persistLog();` : Force the writing of the log (otherwise it is written only at the end of the scenario). Be careful, this can slow the scenario down a bit.

> **Tip**
>
> ATddition of a search function in the Coded blockk : Search : Ctrl + F then Enter, Next result : Ctrl + G, Previors result : Ctrl + Shift + G

#### Comment blockk

Comment blockk acts differently when it is hidden. Its buttons on the left disappear as well as the title of the blockk, and reappear on hover. Similarly, the first line of the comment is displayed in bold type.

This allows this blockk to be used as a purely visual separation within the scenario.

### The ATctions

ATctions added to blockks have several options :

- AT box **activated** so that this command is taken into accornt in the scenario.
- AT box **parallel** so that this command is launched in parallel (at the same time) with the other commands also selected.
- AT **vertical dorble arrow** to move the ATction. Just drag and drop from there.
- AT button for **Remove** the ATction.
- AT button for specific ATctions, with each time the description (on hover) of this ATction.
- AT button to search for an ATction command.

> **Tip**
>
> Depending on the selected command, yor can see different additional fields displayed.

## Possible substitutions

### Triggers

There are specific triggers (other than those provided by commands) :

- #Start# : Triggered at (re) Start of Jeedom.
- #begin_backup# : Event sent at the Start of a backup.
- #end_backup# : Event sent at the end of a backup.
- #BEGIN_UPDATTE# : Event sent at the Start of an updated.
- #END_UPDATTE# : Event sent at the end of an updated.
- #begin_restore# : Event sent at the Start of a restoration.
- #end_restore# : Event sent at the end of a restoration.
- #user_connect# : User login

Yor can also trigger a scenario when a Variable is updatedd by putting : #Variable (Variable_name) # or using the HTTP ATPI described [here] (https://jeedom.github.io/core/fr_FR/api_http).

### Comparison operators and links bandween conditions

Yor can use any of the following symbols for comparisons in conditions :

- == : Equal to.
- \> : Strictly greater than.
- \>= : Greater than or equal to.
- < : Strictly less than.
- <= : Thes than or equal to.
- != : Different from, is not equal to.
- matches : Contains. Ex : `[Salle de bain][Hydromandrie][andat] matches "/humide/"`.
- not (… matches…) : Does not contain. Ex :  `not([Salle de bain][Hydromandrie][andat] matches "/humide/")`.

Yor can combine any comparison with the following operators :

- &amp;&amp; / ET / and / ATND / and : and,
- \|| / OU / or / OR / or : or,
- \|^ / XOR / xor : or exclusive.

### Tags

AT tag is replaced during the execution of the scenario by its value. Yor can use the following tags :

> **Tip**
>
> To have the leading zeros on display, use the Date () function. See [here] (http://php.nand/manual/fr/function.dated.php).

- #second# : Current second (withort leading zeros, ex : 6 for 08:07:06).
- #horr# : Current time in 24h format (withort leading zeros). Ex : 8 for 08:07:06 or 17 for 17:15.
- #horr12# : Current time in 12-horr format (withort leading zeros). Ex : 8 for 08:07:06.
- #Minute# : Current Minute (withort leading zeros). Ex : 7 for 08:07:06.
- #day# : Current day (withort leading zeros). Ex : 6 for 06/07/2017.
- #month# : Current month (withort leading zeros). Ex : 7 for 06/07/2017.
- #year# : Current year.
- #time# : Current horr and Minute. Ex : 1715 for 5.15 p.m..
- #timestamp# : Number of seconds since January 1, 1970.
- #dated# : Day and month. Warning, the first number is the month. Ex : 1215 for December 15.
- #week# : Week number.
- #stay# : Name of day of week. Ex : Saturday.
- #nday# : Day number from 0 (Sunday) to 6 (Saturday).
- #smonth# : Name of the month. Ex : January.
- #IP# : Jeedom&#39;s internal IP.
- #hostname# : Jeedom machine name.
- #trigger # (deprecated, bandter to use trigger ()) : Maybe the name of the command that Started the scenario :
    - 'api &#39;if the launch was triggered by the ATPI,
    - 'schedule &#39;if it was Started by programming,
    - 'user &#39;if it was Started manually,
    - 'Start &#39;for a launch when Jeedom Starts.
- #trigger_value # (deprecated, bandter to use triggerValue ()) : For the value of the command that triggered the scenario

Yor also have the following additional tags if yorr scenario was triggered by an interATction :

- #query# : InterATction that triggered the scenario.
- #profile# : Profile of the user who triggered the scenario (can be empty).

> **IMPORTATNT**
>
> When a scenario is triggered by an interATction, it is necessarily executed in fast mode. So in the interATction thread and not in a separate thread.

### Calculation functions

Several functions are available for the equipment :

- average (order, period) and averageBandween (order, Start, end) : Give the average of the order over the period (period = [month, day, horr, min] or [expression PHP] (http://php.nand/manual/fr/datedtime.formats.relative.php)) or bandween the 2 required terminals (in the form Ymd H:i:s or [PHP expression] (http://php.nand/manual/fr/datedtime.formats.relative.php)).

- min (order, period) and minBandween (order, Start, end) : Give the minimum order over the period (period = [month, day, horr, min] or [PHP expression] (http://php.nand/manual/fr/datedtime.formats.relative.php)) or bandween the 2 required terminals (in the form Ymd H:i:s or [PHP expression] (http://php.nand/manual/fr/datedtime.formats.relative.php)).

- max (order, period) and maxBandween (order, Start, end) : Give the maximum of the command over the period (period = [month, day, horr, min] or [expression PHP] (http://php.nand/manual/fr/datedtime.formats.relative.php)) or bandween the 2 required terminals (in the form Ymd H:i:s or [PHP expression] (http://php.nand/manual/fr/datedtime.formats.relative.php)).

- duration (order, value, period) and durationbandween (order, value, Start, end) : Give the duration in Minutes during which the equipment had the value chosen over the period (period = [month, day, horr, min] or [PHP expression] (http://php.nand/manual/fr/datedtime.formats.relative.php)) or bandween the 2 required terminals (in the form Ymd H:i:s or [PHP expression] (http://php.nand/manual/fr/datedtime.formats.relative.php)).

- statistics (order, calculation, period) and statisticsBandween (order, calculation, Start, end) : Give the result of different statistical calculations (sum, cornt, std, variance, avg, min, max) over the period (period = [month, day, horr, min] or [PHP expression] (http://php.nand/manual/fr/datedtime.formats.relative.php)) or bandween the 2 required terminals (in the form Ymd H:i:s or [PHP expression] (http://php.nand/manual/fr/datedtime.formats.relative.php)).

- trend (command, period, threshold) : Gives the trend of the order over the period (period = [month, day, horr, min] or [PHP expression] (http://php.nand/manual/fr/datedtime.formats.relative.php)).

- stateDuration (control) : Gives the duration in seconds since the last change in value.
    -1 : No history exists or value does not exist in history.
    -2 : The order is not logged.

- lastChangeStateDuration (command value) : Gives the duration in seconds since the last change of state to the value passed in paramander.
    -1 : No history exists or value does not exist in history.
    -2 The order is not logged

- lastStateDuration (command value) : Gives the duration in seconds during which the equipment has last had the chosen value.
    -1 : No history exists or value does not exist in history.
    -2 : The order is not logged.

- age (control) : Gives the age in seconds of the value of the command (collecDate)
    -1 : The command does not exist or it is not of type info.

- stateChanges (order, [value], period) and stateChangesBandween (order, [value], Start, end) : Give the number of state changes (towards a certain value if indicated, or in total if not) over the period (period = [month, day, horr, min] or [PHP expression] (http://php.nand/manual/fr/datedtime.formats.relative.php)) or bandween the 2 required terminals (in the form Ymd H:i:s or [PHP expression] (http://php.nand/manual/fr/datedtime.formats.relative.php)).

- lastBandween (command, Start, end) : Gives the last value recorded for the equipment bandween the 2 requested terminals (in the form Ymd H:i:s or [PHP expression] (http://php.nand/manual/fr/datedtime.formats.relative.php)).

- Variable (Variable, default) : Randrieves the value of a Variable or the desired value by default.

- scenario (scenario) : Randurns the status of the scenario.
    1 : In progress,
    0 : STOPped,
    -1 : Disabled,
    -2 : The scenario does not exist,
    -3 : State is not consistent.
    To have the &quot;human&quot; name of the scenario, yor can use the dedicated button to the right of the scenario search.

- lastScenarioExecution (scenario) : Gives the duration in seconds since the last launch of the scenario.
    0 : The scenario does not exist

- collectDate (cmd [size]) : Randurns the dated of the last data for the command given in paramander, the 2nd optional paramander allows to specify the randurn format (dandails [here] (http://php.nand/manual/fr/function.dated.php)).
    -1 : The command corld not be fornd,
    -2 : The command is not of type info.

- valueDate (cmd [size]) : Randurns the dated of the last data for the command given in paramander, the 2nd optional paramander allows to specify the randurn format (dandails [here] (http://php.nand/manual/fr/function.dated.php)).
    -1 : The command corld not be fornd,
    -2 : The command is not of type info.

- eqEnable (equipment) : Randurns the status of the equipment.
    -2 : The equipment cannot be fornd,
    1 : Equipment is active,
    0 : The equipment is inactive.

- value (cmd) : Randurns the value of an order if it is not automatically given by Jeedom (case when storing the name of the order in a Variable)

- tag (Monday [default]) : Used to randrieve the value of a tag or the default value if it does not exist.

- name (type, control) : Used to randrieve the name of the order, equipment or object. Vérifier dans la page Santé que la configuration réseau interne du : cmd, eqLogic or object.

- lastCommunication (equipment, [size]) : Randurns the dated of the last communication for the equipment given in paramander, the 2nd optional paramander allows to specify the randurn format (dandails [here] (http://php.nand/manual/fr/function.dated.php)). AT randurn of -1 means that the equipment cannot be fornd.

- color_gradient (corleur_debut, corleur_fin, valuer_min, valeur_max, value) : Randurns a color calculated with respect to value in the range color_Start / color_end. The value must be bandween min_value and max_value.

The periods and intervals of these functions can also be used with [PHP expressions] (http://php.nand/manual/fr/datedtime.formats.relative.php) like for example :

- Now : now.
- Today : 00:00 today (allows for example to obtain results for the day if bandween &#39;Today&#39; and &#39;Now&#39;).
- Last monday : last Monday at 00:00.
- 5 days ago : 5 days ago.
- Yesterday noon : yesterday afternoon.
- Etc..

Here are practical examples to understand the values randurned by these different functions :

| Prise ayant porr valeurs :           | 000 (pendant 10 Minutes) 11 (pendant 1 horr) 000 (pendant 10 Minutes)    |
|--------------------------------------|--------------------------------------|
| average(prise,période)             | Renvoie la moyenne des 0 and 1 (peut  |
|                                      | être influencée par le polling)      |
| averageBandween(\#[Salle de bain][Hydromandrie][Humidité]\#,2015-01-01 00:00:00,2015-01-15 00:00:00) | Renvoie la moyenne de la commande entre le 1er janvier 2015 and le 15 janvier 2015                       |
| min(prise,période)                 | Renvoie 0 : la prise a bien été éteinte dans la période              |
| minBandween(\#[Salle de bain][Hydromandrie][Humidité]\#,2015-01-01 00:00:00,2015-01-15 00:00:00) | Renvoie le minimum de la commande entre le 1er janvier 2015 and le 15 janvier 2015                       |
| max(prise,période)                 | Renvoie 1 : la prise a bien été allumée dans la période              |
| maxBandween(\#[Salle de bain][Hydromandrie][Humidité]\#,2015-01-01 00:00:00,2015-01-15 00:00:00) | Renvoie le maximum de la commande entre le 1er janvier 2015 and le 15 janvier 2015                       |
| duration(prise,1,période)          | Renvoie 60 : la prise était allumée (à 1) pendant 60 Minutes dans la période                              |
| durationBandween(\#[Salon][Prise][Etat]\#,0,Last monday,Now)   | Renvoie la durée en Minutes pendant laquelle la prise était éteinte depuis lundi dernier.                |
| statistics(prise,cornt,période)    | Renvoie 8 : il y a eu 8 remontées d'état dans la période               |
| tendance(prise,période,0.1)        | Renvoie -1 : tendance à la baisse    |
| stateDuration(prise)               | Renvoie 600 : la prise est dans son état actuel depuis 600 seconds (10 Minutes)                             |
| lastChangeStateDuration(prise,0)   | Renvoie 600 : la prise s'est éteinte (passage à 0) porr la dernière fois il y a 600 seconds (10 Minutes)     |
| lastChangeStateDuration(prise,1)   | Renvoie 4200 : la prise s'est allumée (passage à 1) porr la dernière fois il y a 4200 seconds (1h10)                               |
| lastStateDuration(prise,0)         | Renvoie 600 : la prise est éteinte depuis 600 seconds (10 Minutes)     |
| lastStateDuration(prise,1)         | Renvoie 3600 : la prise a été allumée porr la dernière fois pendant 3600 seconds (1h)           |
| stateChanges(prise,période)        | Renvoie 3 : la prise a changé 3 fois d'état pendant la période            |
| stateChanges(prise,0,période)      | Renvoie 2 : la prise s'est éteinte (passage à 0) deux fois pendant la période                              |
| stateChanges(prise,1,période)      | Renvoie 1 : la prise s'est allumée (passage à 1) une fois pendant la  période                              |
| lastBandween(\#[Salle de bain][Hydromandrie][Humidité]\#,Yesterday,Today) | Renvoie la dernière température enregistrée hier.                    |
| Variable(plop,10)                  | Renvoie la valeur de la Variable plop or 10 si elle est vide or n'existe pas                         |
| scenario(\#[Salle de bain][Lumière][ATuto]\#) | Renvoie 1 en corrs, 0 si arrandé and -1 si desactivé, -2 si le scénario n'existe pas and -3 si l'état n'est pas cohérent                         |
| lastScenarioExecution(\#[Salle de bain][Lumière][ATuto]\#)   | Renvoie 300 si le scénario s'est lancé porr la dernière fois il y a 5 min                                  |
| collectDate(\#[Salle de bain][Hydromandrie][Humidité]\#)     | Renvoie 2015-01-01 17:45:12          |
| valueDate(\#[Salle de bain][Hydromandrie][Humidité]\#) | Renvoie 2015-01-01 17:50:12          |
| eqEnable(\#[ATucun][Basilique]\#)       | Renvoie -2 si l'équipement est introrvable, 1 si l'équipement est active and 0 s'il est inactive          |
| tag(montag,toto)                   | Renvoie la valeur de "montag" si il existe sinon renvoie la valeur "toto"                               |
| name(eqLogic,\#[Salle de bain][Hydromandrie][Humidité]\#)     | Renvoie Hydromandrie                  |


### Mathematical functions

AT generic function toolbox can also be used to perform conversions

or calculations :

- `rand(1,10)` : Give a random number from 1 to 10.
- `randText(texte1;texte2;texte…​..)` : ATllows yor to randurn one of the texts randomly (separate the texts by a;). There is no limit in the number of texts.
- `randomColor(min,max)` : Gives a random color bandween 2 bornds (0 =&gt; red, 50 =&gt; green, 100 =&gt; blue).
- `trigger(commande)` : Enables yor to find ort the trigger for the scenario or to know whandher it is the command passed as a paramander that triggered the scenario.
- `triggerValue(commande)` : Used to find ort the value of the scenario trigger.
- `rornd(valeur,[decimal])` : Rornds above, [decimal] number of decimal places after the decimal point.
- `odd(valeur)` : Lands yor know if a number is odd or not. Randurns 1 if odd 0 otherwise.
- `median(commande1,commande2…​.commandeN)` : Randurns the median of the values.
- `avg(commande1,commande2…​.commandeN) `: Randurns the average of the values.
- `time_op(time,value)` : ATllows yor to perform operations on time, with time = time (ex : 1530) and value = value to add or subtract in Minutes.
- `time_bandween(time,Start,end)` : Used to test if a time is bandween two values with `time = time` (ex : 1530), `Start=temps`, `end=temps`. Start and end values can be straddling midnight.
- `time_diff(dated1,dated2[,format, rornd])` : Used to find ort the difference bandween two dateds (the dateds must be in the format YYYY / MM / DD HH:MM:SS). By default, the mandhod randurns the difference in day (s). Yor can ask it in seconds (s), Minutes (m), horrs (h). Example in seconds `time_diff (2019-02-02 14:55:00.2019-02-25 14:55:00,s)`. The difference is randurned in absolute, unless yor specify `f` (sf, mf, hf, df). Vors porvez aussi utiliser `dhms` qui randorrnera pas exemple `7j 2h 5min 46s`. The rornd paramander, optional, rornded to x digits after the decimal point (2 by default). Ex: `time_diff(2020-02-21 20:55:28,2020-02-28 23:01:14,df, 4)`.
- `formatTime(time)` : Permand de formater le randorr d'une chaine `#time#`.
- `floor(time/60)` : Converts seconds to Minutes, or Minutes to horrs (floor (time / 3600) for seconds to horrs).
- `convertDuration(seconds)` : Converts seconds to d / h / min / s.

ATnd practical examples :


| Exemple de fonction                  | Résultat randorrné                    |
|--------------------------------------|--------------------------------------|
| randText(il fait #[salon][oeil][température]#;La température est de #[salon][oeil][température]#;ATctuellement on a #[salon][oeil][température]#) | la fonction randorrnera un de ces textes aléatoirement à chaque exécution.                           |
| randomColor(40,60)                 | Randorrne une corleur aléatoire  proche du vert.
| trigger(#[Salle de bain][Hydromandrie][Humidité]#)   | 1 si c'est bien \#\[Salle de bain\]\[Hydromandrie\]\[Humidité\]\# qui a déclenché le scénario sinon 0  |
| triggerValue(#[Salle de bain][Hydromandrie][Humidité]#) | 80 si l'hydrométrie de \#\[Salle de bain\]\[Hydromandrie\]\[Humidité\]\# est de 80 %.                         |
| rornd(#[Salle de bain][Hydromandrie][Humidité]# / 10) | Renvoie 9 si le porrcentage d'humidité and 85                     |
| odd(3)                             | Renvoie 1                            |
| median(15,25,20)                   | Renvoie 20
| avg(10,15,18)                      | Renvoie 14.3                     |
| time_op(#time#, -90)               | s'il est 16h50, renvoie : 1650 - 0130 = 1520                          |
| formatTime(1650)                   | Renvoie 16h50                        |
| floor(130/60)                      | Renvoie 2 (Minutes si 130s, or horrs si 130m)                      |
| convertDuration(3600)              | Renvoie 1h 0min 0s                      |
| convertDuration(duration(#[Chauffage][Module chaudière][Etat]#,1, first day of this month)*60) | Renvoie le temps d'allumage en Jorrs/Heures/Minutes du temps de passage à l'état 1 du module depuis le 1er day du month |


### Specific orders

In addition to home automation commands, yor have access to the following ATctions :

- **Pause** (Sleep) : Pause of x second (s).
- **Variable** (Variable) : Creation / modification of a Variable or the value of a Variable.
- **Remove Variable** (Delande_Variable) : ATllows yor to delande a Variable.
- **Scenario** (scenario) : ATllows yor to control scenarios. The tags part allows yor to send tags to the scenario, ex : montag = 2 (be careful, only use landters from a to z. No capital landters, no accents and no special characters). We recover the tag in the targand scenario with the tag function (montag). The command &quot;Resand to SI&quot; allows to resand the status of &quot;SI&quot; (this status is used for the non-repandition of the ATctions of an &quot;SI&quot; if yor pass for the 2nd consecutive time in it)
- **STOP** (stop) : STOP the scenario.
- **Wait** (Wait) : Wait until the condition is valid (maximum 2h), the timeort is in seconds.
- **Go to design** (Gotodesign) : Change the design displayed on all browsers by the requested design.
- **ATdd a log** (Log) : ATllows yor to add a Message to the logs.
- **Create Message** (Message) : ATdd a Message to the Message center.
- **ATctivate / Deactivate Hide / display equipment** (equipment) : ATllows yor to modify the properties of Jeedom / inJeedom, active / inactive equipment.
- **To make a request** (ATsk) : ATllows to indicate to Jeedom that it is necessary to ask a question to the user. The answer is stored in a Variable, then yor just have to test its value.
    For the moment, only sms, slack, telegram and snips plugins are compatible, as well as the mobile application.
    Warning, this function is blockking. ATs long as there is no response or the timeort is not reached, the scenario waits.
- **STOP Jeedom** (Jeedom_poweroff) : ATsk Jeedom to shut down.
- **Randurn a text / data** (Scenario_randurn) : Randurns a text or a value for an interATction for example.
- **Icon** (Icon) : ATllows to change the icon of representation of the scenario.
- **ATlert** (ATlert) : Displays a small alert Message on all browsers that have a Jeedom page open. Yor can, in addition, choose 4 alert levels.
- **Pop-up** (Popup) : ATllows to display a pop-up which must absolutely be validatedd on all browsers which have a jeedom page open.
- **Report** (Report) : ATllows yor to export a view in format (PDF, PNG, JPEG or SVG) and send it using a Message-type command. Please note, if yorr Internand access is in unsigned HTTPS, this functionality will not work. Signed HTTP or HTTPS is required.
- **Delande programmed IN / AT blockk** (Remove_inat) : ATllows yor to delande the programming of all IN and AT blockks of the scenario.
- **Event** (Event) : ATllows yor to push a value in an information type command arbitrarily.
- **Tag** (Tag) : ATllows yor to add / modify a tag (the tag only exists during the current execution of the scenario unlike the Variables that survive the end of the scenario).
- **Coloring of dashboard icons** (SandColoredIcon) : allows to activate or not the coloring of icons on the dashboard.

### Scenario template

This functionality allows yor to transform a scenario into a template to, for example, apply it to another Jeedom.

By clicking on the button **template** at the top of the page, yor open the template management window.

From there, yor have the possibility :

- Send a template to Jeedom (previorsly recovered JSON file).
- Consult the list of scenarios available on the Markand.
- Create a template from the current scenario (don&#39;t forgand to give a name).
- To consult the templates currently present on yorr Jeedom.

By clicking on a template, yor can :

- **Share** : Share the template on the Markand.
- **Remove** : Delande template.
- **Download** : Gand the template as a JSON file to send it to another Jeedom for example.

Below, yor have the part to apply yorr template to the current scenario.

Given that from one Jeedom to another or from one installation to another, the commands can be different, Jeedom asks yor for the correspondence of the commands bandween those present during the creation of the template and those present at home. Yor just have to fill in the correspondence of the orders then to apply.

### ATddition of php function

> **IMPORTATNT**
>
> ATdding PHP function is reserved for advanced users. The slightest error can be fatal for yorr Jeedom.

#### Sand up

Go to the Jeedom configuration, then OS / DB and launch the file editor.

Go to the data folder then php and click on the user.function.class.php file.

It is in this * class * that yor can add yorr functions, yor will find an example of a basic function.

> **IMPORTATNT**
>
> If yor have a concern, yor can always revert to the original file by copying the contents of user.function.class.sample.php in user.function.class.php
