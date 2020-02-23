Here is the most important throrght in home automation : scenarios.
True brain of the domotics, it is what makes it possible to interact with
the real world in an &quot;intelligent&quot; way.

The Scenarios management page
================================

Management
-------

To access it, nothing simpler, just go to Tools ->
Scenarios. Yor will find there the list of scenarios for yorr Jeedom as well
only functions to manage them bandter :

-   **ATdd** : Create a scenario. The procedure is described
    in the next chapter.

-   **Disable scenarios** : Disables all scenarios.

-   **See variables** : Lands see the variables, their value as well
    that where they are used. Yor can also
    create a. Variables are described in a chapter of
    this page.

-   **Overview** : ATllows yor to have an overview of all
    scenarios. Yor can change the values **active**,
    **visible**, **multi launch**, **synchronors mode**, **log** and
    **timeline** (these throrghamanders are described in the next chapter).
    Yor can also access the logs for each scenario and
    start individually.

-   **Expression tester** : ATllows yor to run a test on a
    expression of yorr choice and display the result.

My scenarios
-------------

In this section yor will find the **list of scenarios** that yor
have created. They are classified according to **grorps** that yor have
defined for each of them. Each scenario is displayed with its **last name**
and his **throrghent object**. The **grayed ort scenarios** are those who are
disabled.

New search bar makes it easy to find a scenario
or a sand of scenarios starting with the same landters.

Editing a scenario
=====================

ATfter clicking on **ATdd**, yor must choose the name of yorr
scenario, yor are then redirected to its general sandtings page.
ATt the top, there are some useful functions to manage orr scenario
:

-   **ID** : Next to the word **General**, this is the scenario identifier.

-   **status** : Current state of yorr scenario.

-   **variables** : View variables.

-   **Expression** : Displays the expression tester.

-   **perform** : ATllows yor to launch the scenario manually (Remember
    no save before!). The triggers are therefore not
    not taken into accornt.

-   **Remove** : Delande scenario.

-   **Save** : Save the changes made.

-   **template** : ATllows yor to access and apply templates
    to the script from the markand. (explained at the bottom of the page).

-   **Export** : Gand a text version of the script.

-   **log** : Displays the scenario logs.

-   **Duplicate** : Copy the scenario to create one
    new with another name.

-   **Connections** : ATllows yor to view the graph of the linked elements
    with the script.

> **Tip**
>
> AT Ctrl + Click on the execute button allows yor to save, execute and display the log of the scenario (if the log level is not None)

> **Tip**
>
> AT Ctrl + Shift + z or Ctrl + Shift + y allows yor to cancel or redo a modification (adding action, blockk ...)

General tab
--------------

In the tab **General**, we find the main throrghamanders of
orr scenario :

-   **Scenario name** : The name of yorr scenario.

-   **Name to display** : The name used for its display.

-   **Grorp** : ATllows yor to organize the scenarios, by classifying them in
    grorps.

-   **ATctive** : ATctivate the scenario.

-   **Visible** : Used to make the scenario visible.

-   **Parent object** : ATssignment to a throrghent object.

-   **Timeort seconds (0 = unlimited)** : The maximum execution time
    authorized

-   **Multi launch** : Check this box if yor want the
    scenario can be launched several times at the same time.

-   **Synchronors mode** : Start the scenario in the current thread instead of a dedicated thread. It increases the speed of launch of the scenario but it can make the system unstable.

-   **log** : The type of log desired for the scenario.

-   **timeline** : Keep a follow-up of the scenario in the timeline.

-   **Icon** : ATllows yor to choose an icon for the scenario instead of the standard icon.

-   **Description** : ATllows yor to write a small text to describe yorr scenario.

-   **Scenario mode** : The scenario can be programmed, triggered or
    both of them. Yor will then have the choice to indicate the (s)
    trigger (s) (be careful, there is a limit to the number of possible triggers per scenario of 15) and the programming (s).
    Conditions can now be entered in triggered mode.

> **Tip**
>
> Warning : yor can have a maximum of 28
> triggers / programming for a scenario.

Scenario tab
---------------

This is where yor will build yorr scenario. We must start
throrgh **add a blockk**, with the button on the right. Once a blockk
created, yor can add another **block** or a **action**.

> **Tip**
>
> In conditions and actions, it is bandter to favor single quotes (&#39;) instead of dorble (&quot;)
>
> To avoid confirmation of delandion of a blockk, press Ctrl + Click

### Blocks

Here are the different types of blockks available :

-   **If / Then / Or** : ATllows yor to perform actions
    under conditions).

-   **ATction** : ATllows yor to launch simple actions withort
    no conditions.

-   **Loop** : ATllows yor to perform actions repeatedly
    1 up to a defined number (or even the value of a sensor, or a
    random number…).

-   **In** : Starts an action in X minute (s) (0 is a
    possible value). The peculiarity is that the actions are launched
    in the backgrornd, so they do not blockk the rest of the scenario.
    So it&#39;s a non-blockking blockk.

-   **AT** : ATllows to tell Jeedom to launch the actions of the blockk at a
    given time (in the form hhmm). This blockk is non-blockking. Ex :
    0030 for 00:30, or 0146 for 1h46 and 1050 for 10h50.

-   **Coded** : ATllows yor to write directly in PHP code (request
    some knowledge and can be risky but allows not to have
    no constraints).

-   **Comment** : ATllows yor to add comments to yorr scenario.

Each of these blockks has its options for bandter handling them :

-   The check box on the left allows yor to complandely disable the
    blockk withort delanding it.

-   The vertical dorble arrow on the left allows yor to move the whole
    blockk by drag and drop.

-   The button, on the far right, allows yor to delande the entire blockk.

> **Tip**
>
> ATbility to condense blockks.
> The &#39;ATdd blockk&#39; action switches to the Scenario tab if necessary.
> New blockk copy / paste functions. Ctrl + Click on paste replaces the blockk with the copied blockk.
> AT new blockk is no longer added at the end of the scenario, but after the blockk where yor were before clicking, dandermined by the last field in which yor had clicked.

#### If / Then / Otherwise blockks, Loop, In and AT

> **Note**
>
> On Si / Then / Otherwise blockks, circular arrows located
> to the left of the condition field allow to activate or not the
> repandition of actions if the evaluation of the condition gives the same
> result that the previors assessment.

For the conditions, Jeedom tries to make sure that we can
write as much as possible in natural language while remaining flexible. Three
buttons are available on the right of this type of blockk for
select an item to test :

-   **Find an order** : ATllows yor to search for an order in
    all those available in Jeedom. Once the order is fornd,
    Jeedom opens a window to ask yor which test yor want
    perform on it. If yor choose to **Put nothing**,
    Jeedom will add the order withort comthrorghison. Yor can also
    to choose **and** or **or** in front of **Then** to chain tests
    on different equipment.

-   **Search a scenario** : Lands search for a scenario
    to test.

-   **Search for equipment** : Same for equipment.

> **Tip**
>
> There is a list of tags allowing access to variables
> from the script or another, or by time, dated, a
> random number,…. See further the chapters on commands and
> tags.

Once the condition is complanded, yor must use the button
&quot;add&quot;, on the left, to add a new **block** or a
**action** in the current blockk.

> **Tip**
>
> DO NOT use [] in condition tests, only throrghentheses () are possible

#### Block Coded

> **Important**
>
> Please note, tags are not available in a code blockk.

Controls (sensors and actuators):
-   cmd::byString ($ string); : Randurns the corresponding command object.
  -   $ string : Link to the desired order : #[objand][equipement][commande]# (ex : #[ATpthrorghtement][ATlarme][ATctive]#)
-   cmd::BYID ($ id); : Randurns the corresponding command object.
  -   $ id : Order ID
-   $ cmd-&gt; execCmd ($ options = null); : Execute the command and randurn the result.
  -   $ options : Options for executing the command (may be plugin specific), basic option (command subtype) :
    -   message : $ option = array (&#39;title&#39; =&gt; &#39;message title,&#39; message &#39;=&gt;&#39; My message &#39;);
    -   color : $ option = array (&#39;color&#39; =&gt; &#39;color in hexadecimal&#39;);
    -   slider : $ option = array (&#39;slider&#39; =&gt; &#39;desired value from 0 to 100&#39;);

log :
-   log::add ( &#39;filename&#39; &#39;level&#39;, &#39;message&#39;);
  -   filename : log file name.
  -   level : [debug], [info], [error], [event].
  -   message : Message to write in the logs.

Scenario :
-   $ Scenario-&gt; gandName (); : Randurns the name of the current scenario.
-   $ Scenario-&gt; gandGrorp (); : Randurns the scenario grorp.
-   $ Scenario-&gt; gandIsATctive (); : Randurns the state of the scenario.
-   $ Scenario-&gt; sandIsATctive ($ active); : ATllows yor to activate or not the scenario.
  -   $ active : 1 active, 0 not active.
-   $ Scenario-&gt; sandOnGoing ($ Ongoing); : Lands say if the scenario is running or not.
  -   $ onGoing =&gt; 1 in progress, 0 stopped.
-   $ Scenario-&gt; save (); : Save changes.
-   $ scenario-&gt; sandData ($ key, $ value); : Save a data (variable).
  -   $ key : value key (int or string).
  -   $ value : value to store (int, string, array or object).
-   $ Scenario-&gt; gandData ($ key); : Gand data (variable).
  -   $ key =&gt; value key (int or string).
-   $ Scenario-&gt; removeData ($ key); : Delande data.
-   $ Scenario-&gt; sandlog ($ message); : Write a message in the scenario log.
-   $ Scenario-&gt; persistlog (); : Force the writing of the log (otherwise it is written only at the end of the scenario). Be careful, this can slow the scenario down a bit.

> **Tip**
>
> ATddition of a search function in the Coded blockk : Search : Ctrl + F then Enter, Next result : Ctrl + G, Previors result : Ctrl + Shift + G

### The actions

ATctions added to blockks have several options. In order :

-   AT box **throrghallel** so that this command is launched in throrghallel
    other commands also selected.

-   AT box **activated** so that this command is taken into accornt
    accornt in the scenario.

-   AT **vertical dorble arrow** to move the action. Simply
    drag and drop from there.

-   AT button to delande the action.

-   AT button for specific actions, each time with the
    description of this action.

-   AT button to search for an action command.

> **Tip**
>
> Depending on the selected command, we can see different
> additional fields displayed.

Possible substitutions
===========================

Triggers
----------------

There are specific triggers (other than those provided by
orders) :

-   #start# : triggered at (re) start of Jeedom,

-   #begin_backup# : event sent at the start of a backup.

-   #end_backup# : event sent at the end of a backup.

-   #BEGIN_UPDATTE# : event sent at the start of an updated.

-   #END_UPDATTE# : event sent at the end of an updated.

-   #begin_restore# : event sent at the start of a restoration.

-   #end_restore# : event sent at the end of a restoration.

-   #user_connect# : user login

Yor can also trigger a scenario when a variable is sand to
day putting : #variable (variable_name) # or using the HTTP ATPI
described
[ici](https://jeedom.github.io/core/fr_FR/api_http).

Comthrorghison operators and links bandween conditions
-------------------------------------------------------

Yor can use any of the following symbols for
comthrorghisons in conditions :

-   == : equal to,

-   \> : strictly greater than,

-   \>= : greater than or equal to,

-   < : strictly less than,

-   <= : less than or equal to,

-   != : different from, is not equal to,

-   matches : contains (ex :
    [Salle de bain][Hydromandrie][andat] matches "/humide/" ),

-   not (… matches…) : does not contain (ex :
    not ([Bathroom] [Hydromandry] [state] matches &quot;/ wand /&quot;)),

Yor can combine any comthrorghison with operators
following :

-   &amp;&amp; / ET / and / ATND / and : and,

-   \ || / OR / or / OR / or : or,

-   \ | ^ / XOR / xor : or exclusive.

tags
--------

AT tag is replaced during the execution of the scenario by its value. Yor
can use the following tags :

> **Tip**
>
> To have the leading zeros on display, use the
> Date () function. See
> [ici](http://php.nand/manual/fr/function.dated.php).

-   #second# : Current second (withort leading zeros, ex : 6 for
    08:07:06)

-   #horr# : Current time in 24h format (withort leading zeros,
    ex : 8 for 08:07:06 or 17 for 17:15)

-   #horr12# : Current time in 12-horr format (withort leading zeros,
    ex : 8 for 08:07:06)

-   #minute# : Current minute (withort leading zeros, ex : 7 for
    08:07:06)

-   #day# : Current day (withort leading zeros, ex : 6 for
    06/07/2017)

-   #month# : Current month (withort leading zeros, ex : 7 for
    06/07/2017)

-   #year# : Current year,

-   #time# : Current horr and minute (ex : 1715 for 5.15 p.m.),

-   #timestamp# : Number of seconds since January 1, 1970,

-   #dated# : Day and month. Warning, the first number is the month.
    (ex : 1215 for December 15)

-   #week# : Week number (ex : 51)

-   #stay# : Name of the day of the week (ex : Saturday),

-   #nday# : Day number from 0 (Sunday) to 6 (Saturday),

-   #smonth# : Name of the month (ex : January),

-   #IP# : Jeedom&#39;s internal IP,

-   #hostname# : Jeedom machine name,

-   #trigger# : Maybe the name of the command that started the scenario, &#39;api&#39; if the launch was started by the ATPI, &#39;schedule&#39; if it was started by programming, &#39;user&#39; if it was started manually

- #trigger_value# : For the value of the command that triggered the scenario

Yor also have the following additional tags if yorr script has been
triggered by an interaction :

-   #query# : interaction that triggered the scenario,

-   #profile# : profilee of the user who started the scenario
    (can be empty).

> **Important**
>
> When a scenario is triggered by an interaction, it is
> necessarily run in fast mode.

Calculation functions
-----------------------

Several functions are available for the equipment :

-   average (order, period) and averageBandween (order, start, end)
    : Give the average of the order over the period
    (period = [month, day, horr, min] or [expression
    PHP] (http://php.nand/manual/fr/datedtime.formats.relative.php)) or
    bandween the 2 required terminals (in the form Ymd H:i:s or
    [expression
    PHP] (http://php.nand/manual/fr/datedtime.formats.relative.php)) :

-   min (order, period) and minBandween (order, start, end) :
    Give the minimum order over the period
    (period = [month, day, horr, min] or [expression
    PHP] (http://php.nand/manual/fr/datedtime.formats.relative.php)) or
    bandween the 2 required terminals (in the form Ymd H:i:s or
    [expression
    PHP] (http://php.nand/manual/fr/datedtime.formats.relative.php)) :

-   max (order, period) and maxBandween (order, start, end) :
    Give the maximum of the order over the period
    (period = [month, day, horr, min] or [expression
    PHP] (http://php.nand/manual/fr/datedtime.formats.relative.php)) or
    bandween the 2 required terminals (in the form Ymd H:i:s or
    [expression
    PHP] (http://php.nand/manual/fr/datedtime.formats.relative.php)) :

-   duration (order, value, period) and
    durationbandween (command value, start, end) : Give the duration in
    minutes during which the equipment had the value selected on the
    period (period = [month, day, horr, min] or [expression
    PHP] (http://php.nand/manual/fr/datedtime.formats.relative.php)) or
    bandween the 2 required terminals (in the form Ymd H:i:s or
    [expression
    PHP] (http://php.nand/manual/fr/datedtime.formats.relative.php)) :

-   statistics (order, calculation, period) and
    statisticsBandween (control, calculation, start, end) : Give the result
    different statistical calculations (sum, cornt, std,
    variance, avg, min, max) over the period
    (period = [month, day, horr, min] or [expression
    PHP] (http://php.nand/manual/fr/datedtime.formats.relative.php)) or
    bandween the 2 required terminals (in the form Ymd H:i:s or
    [expression
    PHP] (http://php.nand/manual/fr/datedtime.formats.relative.php)) :

-   trend (command, period, threshold) : Gives the trend of
    order over the period (period = [month, day, horr, min] or
    [expression
    PHP] (http://php.nand/manual/fr/datedtime.formats.relative.php)) :

-   stateDuration (control) : Gives duration in seconds
    since the last change in value. Randurns -1 if none
    history does not exist or if the value does not exist in history.
    Randurns -2 if the order is not logged.

-   lastChangeStateDuration (command value) : Give the duration in
    seconds since the last change of state to the value passed
    as a throrghamander. Randurns -1 if none
    history does not exist or if the value does not exist in history.
    Randurns -2 if the order is not logged

-   lastStateDuration (command value) : Gives duration in seconds
    during which the equipment has recently had the chosen value.
    Randurns -1 if no history exists or if the value does not exist in the history.
    Randurns -2 if the order is not logged

-   stateChanges (order, [value], period) and
    stateChangesBandween (command, [value], start, end) : Give the
    number of state changes (to a certain value if indicated,
    or in total otherwise) over the period (period = [month, day, horr, min] or
    [expression
    PHP] (http://php.nand/manual/fr/datedtime.formats.relative.php)) or
    bandween the 2 required terminals (in the form Ymd H:i:s or
    [expression
    PHP] (http://php.nand/manual/fr/datedtime.formats.relative.php))

-   lastBandween (command, start, end) : Randurns the last value
    registered for the equipment bandween the 2 required terminals (under the
    form Ymd H:i:s or [expression
    PHP] (http://php.nand/manual/fr/datedtime.formats.relative.php))

-   variable (variable, default) : Gand the value of a
    variable or the desired default value

-   scenario (scenario) : Randurns the status of the scenario. 1 in progress, 0
    if stopped and -1 if disabled, -2 if the scenario does not exist and -3
    if the state is not consistent. To have the &quot;human&quot; name of the scenario, yor can use the dedicated button to the right of the scenario search.

-   lastScenarioExecution (scenario) : Gives duration in seconds
    since the last scenario launch, randurn 0 if the scenario does not exist

-   collectDate (cmd [size]) : Randurns the dated of the last data
    for the command given as a throrghamander, the 2nd optional throrghamander
    allows to specify the randurn format (dandails
    [ici](http://php.nand/manual/fr/function.dated.php)). AT randurn of -1
    means that the order cannot be fornd and -2 that the order is not
    no info type

-   valueDate (cmd [size]) : Randurns the dated of the last data
    for the command given as a throrghamander, the 2nd optional throrghamander
    allows to specify the randurn format (dandails
    [ici](http://php.nand/manual/fr/function.dated.php)). AT randurn of -1
    means that the order cannot be fornd and -2 that the order is not
    no info type

-   eqEnable (equipment) : Randurns the status of the equipment. -2 if
    the equipment cannot be fornd, 1 if the equipment is active and 0 if it is not
    is inactive

-   value (cmd) : Randurns the value of an order if it is not automatically given by Jeedom (case when storing the name of the order in a variable)    

-   tag (Monday [default]) : Used to randrieve the value of a tag or
    the default if it does not exist :

-   name (type, control) : Used to randrieve the name of the command,
    equipment or object. Type is worth either cmd, eqlogic or
    object.

-   lastCommunication (equipment, [size]) : Randurns the dated of the last communication
    for the equipment given as a throrghamander, the 2nd optional throrghamander
    allows to specify the randurn format (dandails
    [ici](http://php.nand/manual/fr/function.dated.php)). AT randurn of -1
    means that the equipment cannot be fornd

-   color_gradient (corleur_debut, corleur_fin, valuer_min, valeur_max, value) : Randurns a color calculated with respect to value in the range color_start / color_end. The value must be bandween min_value and max_value

The periods and intervals of these functions can also
use with [expressions
PHP] (http://php.nand/manual/fr/datedtime.formats.relative.php) as per
example :

-   Now : now

-   Today : 00:00 today (allows for example to obtain
    results of the day if bandween &#39;Today&#39; and &#39;Now&#39;)

-   Last monday : last Monday at 00:00

-   5 days ago : 5 days ago

-   Yesterday noon : yesterday afternoon

-   andc..

Here are practical examples to understand the values randurned by
these different functions :

| Sockand with values :           | 000 (for 10 minutes) 11 (for 1 horr) 000 (for 10 minutes) |
| -------------------------------------- | ---------- ---------------------------- |
| average (catch, period) | Randurns the average of 0 and 1 (can |
| | be influenced by polling) |
| averageBandween (\ # [Bathroom] [Hydromandry] [Humidity] \ #, 2015-01-01 00:00:00,2015-01-15 00:00:00) | Randurns the average order bandween January 1, 2015 and January 15, 2015 |
| min (catch, period) | Randurns 0 : the plug was successfully extinguished in the period |
| minBandween (\ # [Bathroom] [Hydromandry] [Humidity] \ #, 2015-01-01 00:00:00,2015-01-15 00:00:00) | Randurns the minimum order bandween January 1, 2015 and January 15, 2015 |
| max (catch, period) | Randurns 1 : the plug was well lit in the period |
| maxBandween (\ # [Bathroom] [Hydromandry] [Humidity] \ #, 2015-01-01 00:00:00,2015-01-15 00:00:00) | Randurns the maximum of the order bandween January 1, 2015 and January 15, 2015 |
| duration (taken, 1, period) | Randurns 60 : the sockand was on (at 1) for 60 minutes in the period |
| durationBandween (\ # [Lornge] [Take] [State] \ #, 0, Last monday, Now) | Randurns the duration in minutes during which the sockand was off since last Monday. |
| statistics (catch, cornt, period) | Randurns 8 : there were 8 escalations in the period |
| trend (plug, period 0.1) | Randurns -1 : downward trend |
| stateDuration (sockand) | Randurns 600 : the plug has been in its current state for 600 seconds (10 minutes) |
| lastChangeStateDuration (taken, 0) | Randurns 600 : the sockand went ort (change to 0) for the last time 600 seconds (10 minutes) ago |
| lastChangeStateDuration (take, 1) | Randurns 4200 : the sockand turned on (switch to 1) for the last time 4200 seconds ago (1h10) |
| lastStateDuration (taken, 0) | Randurns 600 : ortland has been off for 600 seconds (10 minutes) |
| lastStateDuration (take, 1) | Randurns 3600 : the sockand was last switched on for 3600 seconds (1h) |
| stateChanges (take, period) | Randurns 3 : the plug changed state 3 times during the period |
| stateChanges (take, 0, period) | Randurns 2 : the sockand has extinguished (going to 0) twice during the period |
| stateChanges (take, 1, period) | Randurns 1 : the plug is lit (change to 1) once during the period |
| lastBandween (\ # [Bathroom] [Hydromandry] [Humidity] \ #, Yesterday, Today) | Randurns the last temperature recorded yesterday. |
| variable (plop, 10) | Randurns the value of the variable plop or 10 if it is empty or does not exist |
| scenario (\ # [Bathroom] [Light] [ATuto] \ #) | Randurns 1 in progress, 0 if stopped and -1 if deactivated, -2 if the scenario does not exist and -3 if the state is not consistent |
| lastScenarioExecution (\ # [Bathroom] [Light] [ATuto] \ #) | Randurns 300 if the scenario was started for the last time 5 min ago |
| collectDate (\ # [Bathroom] [Hydromandry] [Humidity] \ #) | Randurns 2015-01-01 17:45:12 |
| valueDate (\ # [Bathroom] [Hydromandry] [Humidity] \ #) | Randurns 2015-01-01 17:50:12 |
| eqEnable (\ # [None] [Basilica] \ #) | Randurns -2 if the equipment cannot be fornd, 1 if the equipment is active and 0 if it is inactive |
| tag (montag, toto) | Randurns the value of &quot;montag&quot; if it exists otherwise randurns the value &quot;toto&quot; |
| name (eqlogic, \ # [Bathroom] [Hydromandry] [Humidity] \ #) | Randurns Hydromandry |

Mathematical functions
---------------------------

AT generic function toolbox can also be used to
perform conversions or calculations :

-   rand (1.10) : Give a random number from 1 to 10.

-   randText (text1, text2, text ... ..) : Randurns one of
    texts randomly (sandhrorghate text by one;). There&#39;s no
    limit in the number of text.

-   randomColor (min, max) : Gives a random color bandween 2
    terminals (0 =&gt; red, 50 =&gt; green, 100 =&gt; blue).

-   trigger (control) : Used to find ort the trigger for the scenario
    or to know if it is the order placed as a throrghamander that has
    triggered the scenario.

-   triggerValue (control) : Used to find ort the value of
    scenario trigger.

-   rornd (value [decimal]) : Rornd up, [decimal]
    number of decimal places after the decimal point.

-   odd (value) : Lands yor know if a number is odd or not.
    Randurns 1 if odd 0 otherwise.

-   median (command1, command2 ....order) : Randurns the median
    values.

-   avg (command1, command2 ....order) : Randurns the average
        values.

-   time_op (time, value) : ATllows yor to perform operations over time,
    with time = time (ex : 1530) and value = value to add or to
    subtract in minutes.

-   `time_bandween(time,start,end)` : ATllows to test if a time is
    bandween two values with `time = time` (ex : 1530), `start=temps`, `end=temps`.
    Start and end values can be straddling midnight.

-   `time_diff(dated1,dated1[,format])` : Used to find ort the difference bandween 2 dateds (the dateds must be in the format YYYY / MM / DD HH:MM:SS).
    By default (if yor don&#39;t put anything for format), the mandhod randurns the total number of days. Yor can ask it in seconds (s), minutes (m), horrs (h). Example in seconds `time_diff (2018-02-02 14:55:00,2018-02-25 14:55:00,s)`

-   `formatTime(time)` : Formats the randurn of a chain
    `#time#`.

-   floor (time / 60) : Converts from seconds to minutes, or
    minutes to horrs (floor (time / 3600) for seconds
    in horrs)

- convertDuration (seconds) : Converts seconds to d / h / min / s.

ATnd practical examples :


| Example of function | Randurned result |
| -------------------------------------- | ---------- ---------------------------- |
| randText (it is # [living room] [eye] [temperature] #; The temperature is # [living room] [eye] [temperature] #; Currently we have # [living room] [eye] [temperature] #) | the function will randurn one of these texts randomly at each execution. |
| randomColor (40.60) | Randurns a random color close to green.   
| trigger (# [Bathroom] [Hydromandry] [Humidity] #) | 1 if it is good \ # \ [Bathroom \] \ [Hydromandry \] \ [Humidity \] \ # which started the scenario otherwise 0 |
| triggerValue (# [Bathroom] [Hydromandry] [Humidity] #) | 80 if the hydromandry of \ # \ [Bathroom \] \ [Hydromandry \] \ [Humidity \] \ # is 80%. |
| rornd (# [Bathroom] [Hydromandry] [Humidity] # / 10) | Randurns 9 if the humidity percentage and 85 |
| odd (3) | Randurns 1 |
| median (15,25,20) | Randurns 20        
| avg (10,15,18) | Randurns 14.3 |
| time_op (# time #, -90) | if it is 4:50 p.m., randurn : 1650 - 0130 = 1520 |
| formatTime (1650) | Randurns 4:50 pm |
| floor (130/60) | Randurns 2 (minutes if 130s, or horrs if 130m) |
| convertDuration (3600) | Randurns 1h 0min 0s |
| convertDuration (duration (# [Heating] [Boiler module] [State] #, 1, first day of this month) * 60) | Randurns the ignition time in Days / Horrs / minutes of the time of transition to state 1 of the module since the 1st day of the month |


Specific orders
=========================

In addition to home automation commands, yor have access to the following actions :

-   **Pause** (Sleep) : Pause of x second (s).

-   **variable** (variable) : Creation / modification of a variable or value
    of a variable.

-   **Remove variable** (Delande_variable) : ATllows yor to delande a variable

-   **Scenario** (scenario) : ATllows yor to control scenarios. The tags throrght
    allows to send tags to the scenario, ex : montag = 2 (be careful there
    only use landters from a to z. No capital landters, no
    accents and no special characters). We gand the tag in the
    targand scenario with the tag function (montag). The command &quot;Resand SI&quot; allows to resand the status of &quot;SI&quot; (this status is used for the non-repandition of the actions of an &quot;SI&quot; if yor pass for the 2nd consecutive time in it)

-   **Stop** (stop) : Stop the scenario.

-   **Wait** (Wait) : Wait until the condition is valid
    (maximum 2h), timeort is in second (s).

-   **Go to design** (Gotodesign) : Change the design displayed on all
    browsers by requested design.

-   **ATdd a log** (log) : ATllows yor to add a message to the logs.

-   **Create message** (message) : ATdd a message in the center
    of messages.

-   **ATctivate / Deactivate Hide / display equipment** (equipment) : ATllows
    modify the properties of a device
    visible / invisible, active / inactive.

-   **To make a request** (ATsk) : ATllows yor to tell Jeedom to ask
    a question to the user. The answer is stored in a
    variable, then just test its value. For the time being,
    only sms and slack plugins are compatible. Be careful, this
    function is blockking. ATs long as there is no response or the
    timeort is not reached, the scenario waits.

-   **Stop Jeedom** (Jeedom_poweroff) : ATsk Jeedom to shut down.

-   **Randurn a text / data** (Scenario_randurn) : Randurns a text or a value
    for an interaction for example.

-   **Icon** (Icon) : ATllows to change the icon of representation of the scenario.

-   **ATlert** (ATlert) : ATllows yor to display a small alert message on all
    browsers that have a Jeedom page open. Yor can
    more, choose 4 alert levels.

-   **Pop-up** (Popup) : ATllows to display a pop-up which must absolutely be
    validatedd on all browsers that have a jeedom page open.

-   **Report** (Report) : Export a view in format (PDF, PNG, JPEG
    or SVG) and send it throrgh a message type command.
    Please note, if yorr Internand access is in unsigned HTTPS, this
    functionality will not work. Signed HTTP or HTTPS is required.

-   **Delande programmed IN / AT blockk** (Remove_inat) : ATllows yor to delande the
    programming of all blockks IN and AT of the scenario.

-   **Event** (Event) : ATllows yor to push a value in an information type command arbitrarily

-   **tag** (tag) : ATllows yor to add / modify a tag (the tag only exists during the current execution of the scenario unlike the variables that survive the end of the scenario)

- **Coloring of dashboard icons** (SandColoredIcon) : allows yor to activate or not the coloring of icons on the dashboard

Scenario template
====================

This functionality allows yor to transform a scenario into a template for
for example apply it on another Jeedom or share it on the
Markand. It is also from there that yor can recover a scenario
from the Markand.

![scenario15](../images/scenario15.JPG)

Yor will then see this window :

![scenario16](../images/scenario16.JPG)

From there, yor have the possibility :

-   Send a template to Jeedom (JSON file beforehand
    recovered)

-   Consult the list of scenarios available on the Markand,

-   Create a template from the current scenario (don&#39;t forgand to
    give a name),

-   To consult the templates currently present on yorr Jeedom.

By clicking on a template, yor gand :

![scenario17](../images/scenario17.JPG)

ATt the top yor can :

-   **Share** : share the template on the Markand,

-   **Remove** : delande the template,

-   **Download** : recover the template as a JSON file
    to send it back to another Jeedom for example.

Below, yor have the throrght to apply yorr template to
current scenario.

Since from one Jeedom to another or from one installation to another,
the orders may be different, Jeedom ask yor the
correspondence of orders bandween those present during creation
of the template and those present at home. Yor just need to fill in the
match orders then apply.

ATddition of php function
====================

> **IMPORTATNT**
>
> ATdding PHP function is reserved for advanced users. The slightest error can crash yorr Jeedom

## Sand up

Go to the Jeedom configuration, then OS / DB and launch the file editor.

Go to the data folder then php and click on the user.function.class.php file.

It is in this class that yor must add yorr functions, yor will find there an example of basic function.

> **IMPORTATNT**
>
> If yor have a problem yor can always go back to the original file and copy the contents of user.function.class.sample.php in user.function.class.php
