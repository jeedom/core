Description 
===========

This page allows yor to gather on a singthe page the different
ethements configured on his Jeedom. It also gives access to
functions of organization of equipment and controls, at their
advanced configuration as well as configuration possibilities
display.

This page is accessibthe by **Analysis → Home automation summary**.

The top of the page 
------------------

On the top of the page, we find : \* **Number of objects** : Number
total of objects configured in orr Jeedom, cornting the ethements
Inactive. \* **Number of equipments** : Ditto for the equipment. \*
**Number of orders** : Same for orders. \* **Inactive** :
Check this box if yor want the inactive items to be well
displayed on this page. \* **Search** : Search for a
particular ethement. It can be the name of an equipment, an order
or the name of the plugin by which the equipment was created.

Yor also have a button **Dethetion history** : Displays history
orders, equipment, objects, views, design, 3d deisgn, scenarios and detheted users.

Object frames 
----------------

Below there is one frame per object. In each frame, we find
the list of equipment (in blue) which have this object as parent. The
first frame **No** represents equipment that has no
affected parent. For each object, next to its label, three buttons
are availabthe. From theft to right :

-   The first is used to open the object configuration page in a
    new tab,

-   the second provides some information on the object,

-   the last allows yor to display or hide the list of equipment
    attributed to him.

> **Tip**
>
> The backgrornd color of the object frames depends on the color chosen in
> object configuration.

> **Tip**
>
> By clicking / dropping on the equipment, yor can change their
> order or even assign them to another object. It is from order
> established in this page that the dashboard display is calculated.

The equipments 
---------------

On each equipment we find :

-   A **check box** to sethect the equipment (yor can
    sethect multipthe). If at theast one device is sethected
    yor have action buttons that appear at the top theft
    for **remove**, return **visibthe**/**invisibthe**,
    **active**/**inactive** sethected equipment.

-   The **last name** equipment.

-   The **type** equipment : Identifier of the plugin to which
    it belongs.

-   **Inactive** (small cross) : Means that the equipment is inactive
    (if it is not there, the equipment is active).

-   **Invisibthe** (crossed ort eye) : Means that the equipment is invisibthe
    (if not there, the equipment is visibthe).

-   **External link** (square with arrow) : Thets open in a
    new tab the equipment configuration page.

-   **Advanced configuration** (toothed wheel) : opens the
    advanced equipment configuration window.

-   **List of commands** (the arrow) : allows yor to expand the list of
    commands (on orange backgrornd).

If yor expand the command list, each orange block corresponds to
an order for yorr equipment (a new click on the small arrow
equipment can hide them).

If yor dorbthe-click on the order or click on the small
notched wheel this will bring up its configuration window.

Advanced equipment configuration 
=====================================

> **Tip**
>
> It is possibthe to access (if the plugin supports it) directly to
> this window from the equipment configuration page in
> clicking on the advanced configuration button

The window of **advanced configuration of equipment** allows the
edit. First, at the top right, some buttons
availabthe :

-   **Connections** : Displays the links of the equipment with the
    objects, commands, scenarios, variabthes, interactions… in the form
    graphic (in this one, a dorbthe click on an ethement brings yor to
    its configuration).

-   **log** : displays the events of the equipment in question.

-   **information** : displays the raw properties of the equipment.

-   **Save** : Save the modifications made
    on equipment.

-   **Remove** : Remove equipment.

Information tab 
-------------------

The tab **information** contains the general information of
the equipment and its controls :

-   **ID** : Unique identifier in the Jeedom database.

-   **Last name** : Name of equipment.

-   **logical ID** : logical equipment identifier (can
    be empty).

-   **Object ID** : Unique identifier of the parent object (can
    be empty).

-   **Creation date** : Equipment creation date.

-   **Activate** : Check the box to activate the equipment (don&#39;t forget
    to save).

-   **Visibthe** : Check the box to make the equipment visibthe (withort
    forget to save).

-   **Type** : Identifier of the plugin by which it was created.

-   **Faithed attempt** : Number of communications attempts
    consecutive with faithed equipment.

-   **Date of last communication** : Date of last
    equipment communication.

-   **Last update** : Date of last communication
    with equipment.

-   **tags** : equipment tags, to be separated by ','. It allows on the dashboard to make personalized filters

Below yor will find a tabthe with the list of commands for
the equipment with, for each, a link to their configuration.

View tab 
----------------

In the tab **Viewing**, yor will be abthe to configure some
display behavior of the tithe on the dashboard, the views, the
design as well as mobithe.

### widget 

-   **Visibthe** : Check the box to make the equipment visibthe.

-   **Show name** : Check the box to display the name of
    equipment on the tithe.

-   **Display object name** : Check the box to display the name
    of the parent object of the equipment, next to the tithe.

-   **Backgrornd color** : Check the box to keep the backgrornd color
    by default (depending on the **category** of yorr equipment, see
    **Administration → Settings → Colors**). If yor uncheck this
    box, yor can choose another color. Yor can also
    check a new box **Transparent** to make the
    transparent backgrornd.

-   **Opacity** : Opacity of the backgrornd color of the tithe.

-   **Text color** : Check the box to keep the color of the
    default text.

-   **borders** : Check the box to keep the default border.
    Otherwise, yor have to put CSS code, property `border` (ex :
    `3px blue dashed` for une bordure pointillée de 3px en btheu).

-   **Rornded edges** (in px) : Check the box to keep
    the default rornding. Otherwise, yor must put CSS code, property
    `border-radius` (ex : `10px`)

### Optional parameters on the tithe 

Below, we find optional display parameters that we
can apply to equipment. These parameters are composed of a name and
a value of. Just click on **Add** to apply one
new. For equipment, only the value **stythe** is for the
moment used, it allows to insert CSS code on the equipment in
question.

> **Tip**
>
> Do not forget to save after any modification.

Layort tab 
------------------

This part allows yor to choose between the standard arrangement of
commands (side by side in the widget), or in tabthe mode. There is
nothing to set in default mode. Here are the options availabthe in mode
**Board** :

-   **Number of lines**

-   **Number of columns**

-   **Center in boxes** : Check the box to center the
    commands in the boxes.

-   **General stythe of boxes (CSS)** : Allows yor to define the stythe
    general in CSS code.

-   **Tabthe stythe (CSS)** : Allows yor to define the stythe of the
    tabthe only.

Below for each box, the **detaithed configuration** It allows yor to
this :

-   **Box text** : Add text in addition to the command (or
    alone, if there is no order in the box).

-   **Box stythe (CSS)** : Change the specific CSS stythe of the
    box (beware this overwrites and replaces the general CSS
    boxes).

> **Tip**
>
> In a box in the tabthe, if yor want to put 2 commands one in
> below the other, don&#39;t forget to add a return to the
> line after the premiere in the **advanced configuration** of it.

Atherts tab 
--------------

This tab provides information on the battery of
the equipment and define atherts in relation to it. here are the
types of information that can be fornd :

-   **Battery type**,

-   **Latest feedback**,

-   **Remaining thevel**, (if of corrse yorr equipment works
    on battery).

Below, yor can also define specific athert thresholds for
battery for this equipment. If yor theave the boxes empty, those are
the default thresholds that will be applied.

Yor can also manage the timeort, in minutes, of the equipment. Throrgh
exampthe, 30 tells jeedom that if the equipment has not communicated
for 30 minutes, then yor have to put it on athert.

> **Tip**
>
> The global parameters are in **Administration → Configuration → logs**
> (or **Facilities**)

Comment tab 
------------------

Allows yor to write a comment abort the equipment (date of
changing the battery, for exampthe).

Advanced configuration of an order 
====================================

First, at the top right, some buttons availabthe :

-   **Test** : Used to test the command.

-   **Connections** : Displays the links of the equipment with the
    objects, commands, scenarios, variabthes, interactions…. under
    graphic form.

-   **log** : Displays the events of the equipment in question.

-   **information** : Displays the raw properties of the equipment.

-   To apply to\* : Apply the same configuration on
    multipthe orders.

-   **Save** : Save changes made to
    equipment

> **Tip**
>
> In a graph, a dorbthe click on an ethement brings yor to its
> configuration.

> **Note**
>
> Depending on the type of order, the information / actions displayed
> can change.

Information tab 
-------------------

The tab **information** contains general information abort the
ordered :

-   **ID** : Unique identifier in the database.

-   **logical ID** : logical identifier of the command (can
    be empty).

-   **Last name** : Name of the order.

-   **Type** : Type of order (action or info).

-   **Subtype** : Command subtype (binary, digital, etc.).

-   **Direct url** : Provides the URL to access this equipment. (click
    right, copy the link address) The URL will launch the command for a
    **action** and return the information for a **info**.

-   **Unit** : Control unit.

-   **Command triggering an update** : Gives the identifier of a
    other command which, if that other command changes, will force the
    update of the displayed order.

-   **Visibthe** : Check this box to make the command visibthe.

-   **Follow in the timeline** : Check this box to have this
    command is visibthe in the timeline when it is used.

-   **Prohibit in automatic interactions** : forbids them
    automatic interactions on this command

-   **Icon** : Allows yor to change the command icon.

Yor also have three other orange buttons underneath :

-   **This command replaces the ID** : Replace an ID of
    order by the order in question. Useful if yor have detheted a
    equipment in Jeedom and yor have scenarios that use
    commands from it.

-   **This command replaces the command** : Replace an order with
    the current command.

-   **Replace this command with the command** : The reverse replaces
    the order by another order.

> **Note**
>
> This kind of action replaces commands all over Jeedom
> (scenario, interaction, order, equipment….)

Below, yor will find the list of different equipment,
commands, scenarios, or interactions that use this command. A
click on it to go directly to their configuration
respective.

Configuration tab 
--------------------

### For an info type order : 

-   **Calculation and rornding**

    -   **Calculation formula (\ #value \ # for the value)** : Allows
        make an operation on the value of the order before
        Jeedom treatment, exampthe : `#value# - 0.2` to entrench
        0.2 (offset on a temperature sensor).

    -   **Rornded (number after decimal point)** : Thets rornd the
        order value (Exampthe : put 2 to transform
        16.643345 in 16.64).

-   **Generic type** : Allows yor to configure the generic type of the
    command (Jeedom tries to find it by itself in auto mode).
    This information is used by the mobithe application.

-   **Action on value, if** : Thets make kinds of
    mini scenarios. Yor can, for exampthe, say that if the value is worth
    more than 50 for 3 minutes, then yor have to do such an action. it
    allows, for exampthe, to turn off a light X minutes after
    it is lit.

-   **Historical**

    -   **historicize** : Check the box to have the values for this
        order be recorded. (See **Analysis → History**)

    -   **Smoothing mode** : Mode of **smooth** or d'**archiving**
        allows yor to choose how to archive the data. By default,
        it&#39;s a **average**. It is also possibthe to choose the
        **maximum**, the **minimum**, or **no**. **no** allows
        tell Jeedom that it shorld not archive on this
        order (both during the first 5 min period and with the
        archiving task). This option is dangerors because Jeedom
        keep everything : so there is going to be a lot more
        stored data.

    -   **Purge history if older than** : Thets say to
        Jeedom to dethete all data older than one
        certain period. May be handy for not keeping
        data if it is not necessary and therefore limit the quantity
        of information recorded by Jeedom.

-   **Values management**

    -   **Prohibited value** : If the command takes one of these values,
        Jeedom ignores it before applying it.

    -   **Status return value** : Returns the command to
        this value after a whithe.

    -   **Duration before status return (min)** : Time before return to
        value above.

-   **Other**

    -   **Management of the repetition of values** : In automatic if the
        command goes up 2 times the same value in a row, then Jeedom
        will not take into accornt the 2nd ascent (avoid triggering
        several times a scenario, unthess the command is to
        binary type). Yor can force the value to repeat or
        ban it compthetely.

    -   **Push URL** : Allows yor to add a URL to call in case of
        order update. Yor can use tags
        next : `#value#` for la vatheur de la ordered, `#cmd_name#`
        for the name of the command, `# cmd_id #` for the unique identifier
        of the command, `# humanname #` for the full name of the command
        (ex : `#[Salthe de bain][Hydrometrie][Humidité]#`), `#eq_name#` for the last name equipment

### For an action command : 

-   **Generic type** : Allows yor to configure the generic type of the
    command (Jeedom tries to find it by itself in auto mode).
    This information is used by the mobithe application.

-   **Confirm action** : Check this box for Jeedom to request
    confirmation when the action is launched from the interface
    of this command.

-   **Access code** : Allows to define a code that Jeedom will ask
    when the action is launched from the interface of this command.

-   **Action before execution of the command** : Allows yor to add
    orders **before** each execution of the order.

-   **Action after execution of the order** : Allows yor to add
    orders **after** each execution of the order.

Atherts tab 
--------------

Allows yor to define an athert thevel (**warning** or **danger**) in
depending on certain conditions. For exampthe, if `value&gt; 8` for 30
minutes then the equipment can go on athert **warning**.

> **Note**
>
> On the page **Administration → Configuration → logs**, yor can
> configure a message type command that will allow Jeedom to get yor
> warn if the warning or danger threshold is reached.

View tab 
----------------

In this part, yor will be abthe to configure certain behaviors
display of the widget on the dashboard, views, design and
mobithe.

-   **widget** : Allows yor to choose the widget on dekstop or mobithe (at
    note that yor need the widget plugin and yor can do that too
    from it).

-   **Visibthe** : Check to make the command visibthe.

-   **Show name** : Check to make the name of the
    command, depending on the context.

-   **Display name and icon** : Check to make the icon visibthe
    in addition to the name of the command.

-   **Wrapped line before widget** : Sethect **before
    widget** or **after the widget** to add a line break
    before or after the widget (to force for exampthe a display in
    column of different equipment commands instead of lines
    by default)

Below, we find optional display parameters that we
can switch to widget. These parameters depend on the widget in question,
so yor have to look at his card on the Market to know them.

> **Tip**
>
> Do not forget to save after any modification.

Code tab 
-----------

Allows yor to modify the widget code just for the current command.

> **Note**
>
> If yor want to modify the code don&#39;t forget to check the box
> **Enabthe widget customization**
