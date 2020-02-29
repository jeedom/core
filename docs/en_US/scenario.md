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
    that the place where they are used. Yor can also
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

ATs in many Jeedom pages, put the morse to the left of
the screen displays a quick access menu (from
yorr profilee, yor can always leave it visible). Yor will be able
so **search** yorr scenario, but also in **add** one by this
menu.

Editing a scenario
=====================

ATfter clicking on **ATdd**, yor must choose the name of yorr
scenario and yor are redirected to its general sandtings page.
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

-   **Follow in the timeline** : Keeps track of the scenario
    in the timeline.

-   **Description** : ATllows yor to write a small text to describe
    yorr scenario.

-   **Scenario mode** : The scenario can be programmed, triggered or
    both of them. Yor will then have the choice to indicate the (s)
    trigger (s) (be careful, there is a limit to the number of possible triggers per scenario of 15) and the programming (s).

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
> Yor MUST NOT use [] in condition tests, only throrghentheses () are possible

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

Tags
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
    PHP] (http://php.nand/manual/fr/datedtime.formats.relative.php)) :

-   lastBandween (command, start, end) : Randurns the last value
    registered for the equipment bandween the 2 required terminals (under the
    form Ymd H:i:s or [expression
    PHP] (http://php.nand/manual/fr/datedtime.formats.relative.php)) :

-   variable (variable, default) : Gand the value of a
    variable or the desired default value :

-   scenario (scenario) : Randurns the status of the scenario. 1 in progress, 0
    if stopped and -1 if disabled, -2 if the scenario does not exist and -3
    if the state is not consistent. To have the &quot;human&quot; name of the scenario, yor can use the dedicated button to the right of the scenario search.

-   lastScenarioExecution (scenario) : Gives duration in seconds
    since the last scenario launch :

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

-   lastCommunication(equipment,[format]) : Renvoie la dated de la dernière communication
    porr l'équipement donnée as a throrghamander, le 2ème throrghamètre optionnel
    allows to specify the randurn format (dandails
    [ici](http://php.nand/manual/fr/function.dated.php)). AT randurn of -1
    signifie que l'équipment est introrvable

-   color_gradient(corleur_debut,corleur_fin,valuer_min,valeur_max,valeur) : Renvoi une corleur calculé throrgh rapport à valeur dans l'intervalle corleur_debut/corleur_fin. La valeur doit andre comprise entre valeur_min and valeur_max

The périodes and intervalles de ces fonctions peuvent également
s'utiliser avec [des expressions
PHP] (http://php.nand/manual/fr/datedtime.formats.relative.php) comme throrgh
exemple :

-   Now : maintenant

-   Today : 00:00 audayd'hui (permand throrgh exemple d'obtenir des
    résultats de la daynée si entre 'Today' and 'Now')

-   Last Monday : lundi dernier à 00:00

-   5 days ago : il y a 5 days

-   Yesterday noon : hier midi

-   Etc.

Voici des exemples pratiques porr comprendre les valeurs randorrnées throrgh
ces différentes fonctions :

| Prise ayant porr valeurs :           | 000 (pendant 10 minutes) 11 (pendant 1 horr) 000 (pendant 10 minutes)    |
|--------------------------------------|--------------------------------------|
| average(prise,période)             | Renvoie la moyenne des 0 and 1 (peut  |
|                                      | être influencée throrgh le polling)      |
| averageBandween(\#[Salle de bain][Hydromandrie][Humidité]\#,2015-01-01 00:00:00,2015-01-15 00:00:00) | Renvoie la moyenne de la commande entre le 1 janvier 2015 and le 15 janvier 2015                         |
| min(prise,période)                 | Renvoie 0 : la prise a bien été éteinte dans la période              |
| minBandween(\#[Salle de bain][Hydromandrie][Humidité]\#,2015-01-01 00:00:00,2015-01-15 00:00:00) | Renvoie le minimum de la commande entre le 1 janvier 2015 and le 15 janvier 2015                         |
| max(prise,période)                 | Renvoie 1 : la prise a bien été allumée dans la période              |
| maxBandween(\#[Salle de bain][Hydromandrie][Humidité]\#,2015-01-01 00:00:00,2015-01-15 00:00:00) | Renvoie le maximum de la commande entre le 1 janvier 2015 and le 15 janvier 2015                         |
| duration(prise,1,période)          | Renvoie 60 : la prise était allumée (à 1) pendant 60 minutes dans la période                              |
| durationBandween(\#[Salon][Prise][Etat]\#,0,Last Monday,Now)   | Renvoie la durée en minutes pendant laquelle la prise était éteinte depuis lundi dernier.                |
| statistics(prise,cornt,période)    | Renvoie 8 : il y a eu 8 remontées d'état dans la période               |
| tendance(prise,période,0.1)        | Renvoie -1 : tendance à la baisse    |
| stateDuration(prise)               | Renvoie 600 : la prise est dans son état actuel depuis 600 seconds (10 minutes)                             |
| lastChangeStateDuration(prise,0)   | Renvoie 600 : la prise s'est éteinte (passage à 0) porr la dernière fois il y a 600 seconds (10 minutes)     |
| lastChangeStateDuration(prise,1)   | Renvoie 4200 : la prise s'est allumée (passage à 1) porr la dernière fois il y a 4200 seconds (1h10)                               |
| lastStateDuration(prise,0)         | Renvoie 600 : la prise est éteinte depuis 600 seconds (10 minutes)     |
| lastStateDuration(prise,1)         | Renvoie 3600 : la prise a été allumée porr la dernière fois pendant 3600 seconds (1h)           |
| stateChanges(prise,période)        | Renvoie 3 : la prise a changé 3 fois d'état pendant la période            |
| stateChanges(prise,0,période)      | Renvoie 2 : la prise s'est éteinte (passage à 0) deux fois pendant la période                              |
| stateChanges(prise,1,période)      | Renvoie 1 : la prise s'est allumée (passage à 1) une fois pendant la  période                              |
| lastBandween(\#[Salle de bain][Hydromandrie][Humidité]\#,Yesterday,Today) | Renvoie la dernière température enregistrée hier.                    |
| variable(plop,10)                  | Renvoie la valeur de la variable plop or 10 si elle est vide or n'existe pas                         |
| scenario(\#[Salle de bain][Lumière][ATuto]\#) | Renvoie 1 in progress, 0 si arrandé and -1 si desactivé, -2 if le scénario n'existe pas and -3 if the state is not consistent                         |
| lastScenarioExecution(\#[Salle de bain][Lumière][ATuto]\#)   | Renvoie 300 si le scénario s'est lancé porr la dernière fois il y a 5 min                                  |
| collectDate(\#[Salle de bain][Hydromandrie][Humidité]\#)     | Renvoie 2015-01-01 17:45:12          |
| valueDate(\#[Salle de bain][Hydromandrie][Humidité]\#) | Renvoie 2015-01-01 17:50:12          |
| eqEnable(\#[ATucun][Basilique]\#)       | Renvoie -2 if the equipment cannot be fornd, 1 if the equipment is active and 0 if it is not is inactive          |
| tag(montag,toto)                   | Renvoie la valeur de "montag" si il existe sinon renvoie la valeur "toto"                               |
| name(eqlogic,\#[Salle de bain][Hydromandrie][Humidité]\#)     | Renvoie Hydromandrie                  |

The fonctions mathématiques
---------------------------

AT boîte à ortils de fonctions génériques peut également servir à
effectuer des conversions or des calculs :

-   rand(1,10) : Donne un last namebre aléatoire de 1 à 10.

-   randText(texte1;texte2;texte…​..) : ATllows randorrner un des
    textes aléatoirement (séthrorgher les texte throrgh un ; ). Il n'y a pas
    de limite dans le last namebre de texte.

-   randomColor(min,max) : Donne une corleur aléatoire compris entre 2
    bornes ( 0 => rorge, 50 => vert, 100 => bleu).

-   trigger(commande) : ATllows connaître le déclencheur du scénario
    or de savoir si c'est bien la commande passée as a throrghamander qui a
    déclenché le scénario.

-   triggerValue(commande) : ATllows connaître la valeur du
    déclencheur du scénario.

-   rornd(valeur,[decimal]) : Donne un arrondi au-dessus, [decimal]
    last namebre de décimales après la virgule.

-   odd(valeur) : ATllows savoir si un last namebre est impair or non.
    Renvoie 1 si impair 0 sinon.

-   median(commande1,commande2…​.commandeN) : Renvoie la médiane
    des valeurs.

-   time_op(time,value) : ATllows faire des opérations sur le temps,
    avec time=temps (ex : 1530) and value=valeur à add or à
    sorstraire en minutes.

-   `time_bandween(time,start,end)` : ATllows tester si un temps est
    entre deux valeurs avec `time=temps` (ex : 1530), `start=temps`, `end=temps`.
    The valeurs start and end peuvent être à cheval sur minuit.

-   `time_diff(dated1,dated1[,format])` : ATllows connaître la différence entre 2 dateds (les dateds doivent être au format ATATATAT/MM/JJ HH:MM:SS).
    Par défaut (si vors ne mandtez rien porr format), la méthode randorrne le last namebre total de days. Vors porvez lui demander en seconds (s), minutes (m), horrs (h). Exemple en seconds `time_diff(2018-02-02 14:55:00,2018-02-25 14:55:00,s)`

-   `formatTime(time)` : ATllows formater le randorr d'une chaine
    `#time#`.

-   floor(time/60) : ATllows convertir des seconds en minutes, or
    des minutes en horrs (floor(time/3600) porr des seconds
    en horrs)

Et les exemples pratiques :


| Exemple de fonction                  | Résultat randorrné                    |
|--------------------------------------|--------------------------------------|
| randText(il fait #[salon][oeil][température]#;La température est de #[salon][oeil][température]#;ATctuellement on a #[salon][oeil][température]#) | la fonction randorrnera un de ces textes aléatoirement à chaque exécution.                           |
| randomColor(40,60)                 | Randorrne une corleur aléatoire  proche du vert.   
| trigger(#[Salle de bain][Hydromandrie][Humidité]#)   | 1 si c'est bien \#\[Salle de bain\]\[Hydromandrie\]\[Humidité\]\# qui a déclenché le scénario sinon 0  |
| triggerValue(#[Salle de bain][Hydromandrie][Humidité]#) | 80 si l'hydrométrie de \#\[Salle de bain\]\[Hydromandrie\]\[Humidité\]\# est de 80 %.                         |
| rornd(#[Salle de bain][Hydromandrie][Humidité]# / 10) | Renvoie 9 si le porrcentage d'humidité and 85                     |
| odd(3)                             | Renvoie 1                            |
| median(15,25,20)                   | Renvoie 20                           |
| time_op(#time#, -90)               | s'il est 16h50, renvoie : 1650 - 0130 = 1520                          |
| formatTime(1650)                   | Renvoie 16h50                        |
| floor(130/60)                      | Renvoie 2 (minutes si 130s, or horrs si 130m)                      |

The commandes spécifiques
=========================

En plus des commandes domotiques, vors avez accès aux actions suivantes :

-   **Pause** (sleep) : Pause de x second(s).

-   **variable** (variable) : Création/modification d'une variable or de la valeur
    d'une variable.

-   **Remove variable** (delande_variable) : ATllows supprimer une variable

-   **Scenario** (scenario) : ATllows contrôler des scénarios. La throrghtie tags
    permand d'envoyer des tags au scénario, ex : montag=2 (attention il
    ne faut utiliser que des landtre de a à z. Pas de majuscules, pas
    d'accents and pas de caractères spéciaux). On récupère le tag dans le
    scénario cible avec la fonction tag(montag). La commande "Remise à zéro des SI" permand de remandtre à zéro le status des "SI" (ce status est utilisé porr la non répétition des actions d'un "SI" si on passe porr la 2ème fois consécutive dedans)

-   **Stop** (stop) : ATrrête le scénario.

-   **ATttendre** (wait) : ATttend jusqu'à ce que la condition soit valide
    (maximum 2h), le timeort est en second(s).

-   **ATller au design** (gotodesign) : Change le design affiché sur tors les
    navigateurs throrgh le design demandé.

-   **ATdd un log** (log) : ATllows radd un message dans les logs.

-   **Créer un message** (message) : Permand d'add un message dans le centre
    de messages.

-   **ATctiver/Désactiver Masquer/afficher un équipement** (equipement) : ATllows
    modifier les propriétés d'un équipement
    visible/invisible, active/inactive.

-   **Faire une demande** (ask) : Permand d'indiquer à Jeedom qu'il faut poser
    une question à l'utilisateur. La réponse est stockée dans une
    variable, il suffit ensuite de tester sa valeur. Porr le moment,
    seuls les plugins sms and slack sont compatibles. Warning, candte
    fonction est bloquante. Tant qu'il n'y a pas de réponse or que le
    timeort n'est pas atteint, le scénario attend.

-   **ATrrêter Jeedom** (jeedom_poweroff) : Demande à Jeedom de s'éteindre.

-   **Redémarrer Jeedom** (jeedom_reboot) : Demande à Jeedom de redémarrer.

-   **Randorrner un texte/une donnée** (scenario_randurn) : Randorrne un texte or a valeur
    porr une interaction throrgh exemple.

-   **Icon** (icon) : ATllows changer l'icône de représentation du scénario.

-   **ATlerte** (alert) : Permand d'afficher un pandit message d'alerte sur tors
    les navigateurs qui ont une page Jeedom orverte. Vors porvez, en
    plus, to choose 4 niveaux d'alerte.

-   **Pop-up** (popup) : Permand d'afficher un pop-up qui doit absolument être
    validé sur tors les navigateurs qui ont une page jeedom orverte.

-   **Rapport** (report) : Permand d'exporter une vue au format (PDF,PNG, JPEG
    or SVG) and de l'envoyer throrgh le biais d'une commande de type message.
    Warning, si votre accès Internand est en HTTPS non-signé, candte
    fonctionalité ne marchera pas. Il faut du HTTP or HTTPS signé.

-   **Remove block DATNS/AT programmé** (remove_inat) : ATllows supprimer la
    programmation de tors les blocks DATNS and AT du scénario.

-   **Evènement** (event) : ATllows porsser une valeur dans une commande de type information de manière arbitraire

-   **Tag** (tag) : Permand d'add/modifier un tag (le tag n'existe que pendant l'exécution en corrs du scénario à la difference des variables qui survivent à la fin du scénario)

template de scénario
====================

Candte fonctionalité permand de transformer un scénario en template porr
throrgh exemple l'appliquer sur un autre Jeedom or le throrghtager sur le
Markand. C'est aussi à throrghtir de là that yor porvez récupérer un scénario
du Markand.

![scenario15](../images/scenario15.JPG)

Vors verrez so candte fenêtre :

![scenario16](../images/scenario16.JPG)

AT throrghtir de celle-ci, vors avez la possibilité :

-   D'envoyer un template à Jeedom (fichier JSON préalablement
    récupéré),

-   De consulter la list of scenarios disponibles sur le Markand,

-   De créer un template à throrghtir du scénario corrant (n'orbliez pas de
    donner un last name),

-   De consulter les templates actuellement présents sur votre Jeedom.

Par un clic sur un template, vors obtenez :

![scenario17](../images/scenario17.JPG)

En haut, vors porvez :

-   **Partager** : throrghtager le template sur le Markand,

-   **Remove** : supprimer le template,

-   **Download** : récupérer le template sors forme de fichier JSON
    porr le renvoyer sur un autre Jeedom throrgh exemple.

En-dessors, vors avez la throrghtie porr appliquer votre template au
scénario corrant.

Etant donné que d'un Jeedom à l'autre or d'une installation à une autre,
les commandes peuvent être différentes, Jeedom vors demande la
correspondance des commandes entre celles présentes lors de la création
du template and celles présentes chez vors. Il vors suffit de remplir la
correspondance des commandes puis de faire appliquer.

ATjort de fonction php
====================

> **IMPORTATNT**
>
> L'ajort de fonction PHP est reservé aux utilisateurs avancés. La moindre erreur peut faire planter votre Jeedom

## Mise en place

ATller dans la configuration de Jeedom, puis OS/DB and lancer l'éditeur de fichier.

ATllez dans le dossier data puis php and cliquez sur le fichier user.function.class.php.

C'est dans candte class that yor devez add vos fonctions, vors y trorverez un exemple de fonction basique.

> **IMPORTATNT**
>
> Si vors avez un sorcis vors porvez tordays revenir au fichier d'origine en copier le contenu de user.function.class.sample.php dans  user.function.class.php
