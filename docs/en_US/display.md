Description 
===========

This page allows you to gather on a single page the different
elements configured on his Jeedom. It also gives access to
functions of organization of equipment and controls, at their
advanced configuration as well as configuration possibilities
d'affichage.

This page is accessible by **Tools → Home automation summary**.

The top of the page 
------------------

On the top of the page, we find : \* **Number of objects** : Nombre
total of objects configured in our Jeedom, counting the elements
Inactive. \* **Number of equipments** : Ditto for the equipment. \*
**Number of orders** : Ditto for orders. \* **Inactive** :
Check this box if you want the inactive items to be well
displayed on this page. \* **Search** : Search for a
particular element. It can be the name of an equipment, an order
or the name of the plugin by which the equipment was created.

You also have a button &quot;History of deletions&quot; which will allow you to display the history of orders, equipment, objects, view, design, deisgn 3d, scenario and deleted user.

Object frames 
----------------

Below there is one frame per object. In each frame, we find
the list of equipment (in blue) which have this object as parent. The
first frame **No** represents equipment that has no
affected parent. For each object, next to its label, three buttons
are available. From left to right :

-   The first is used to open the object configuration page in a
    new tab,

-   the second provides some information on the object,

-   the last allows you to display or hide the list of equipment
    attributed to him.

> **Tip**
>
> The background color of the object frames depends on the color chosen in
> object configuration.

> **Tip**
>
> By clicking / dropping on the equipment, you can change their
> order or even assign them to another object. It is from order
> established in this page that the dashboard display is calculated.

The equipments 
---------------

On each equipment we find :

-   A **check box** to select the equipment (you can
    select multiple). If at least one device is selected
    you have action buttons that appear at the top left
    for **remove**, return **Jeedom**/**Invisible**,
    **active**/**Inactive** selected equipment.

-   The **last name** equipment.

-   The **Type** equipment : Identifier of the plugin to which
    it belongs.

-   **Inactive** (small cross) : Means that the equipment is inactive
    (if it is not there, the equipment is active).

-   **Invisible** (crossed eye) : Means that the equipment is invisible
    (if it is not there, the equipment is visible).

-   **External link** (square with arrow) : Lets open in a
    new tab the equipment configuration page.

-   **Advanced configuration** (toothed wheel) : opens the
    advanced equipment configuration window.

-   **List of commands** (the arrow) : allows you to expand the list of
    commands (on orange background).

If you expand the command list, each orange block corresponds to
an order for your equipment (a new click on the small arrow
equipment can hide them).

If you double-click on the order or click on the small
notched wheel this will bring up its configuration window.

Advanced equipment configuration 
=====================================

> **Tip**
>
> It is possible to access (if the plugin supports it) directly to
> this window from the equipment configuration page in
> clicking on the advanced configuration button

The window of **Advanced equipment configuration** allows the
edit. First, at the top right, some buttons
available :

-   **Connections** : Displays the links of the equipment with the
    objects, commands, scenarios, variables, interactions… in the form
    graphic (in this one, a double click on an element brings you to
    its configuration).

-   **Log** : displays the events of the equipment in question.

-   **Information** : displays the raw properties of the equipment.

-   **Save** : Save the modifications made
    on equipment.

-   **Remove** : Remove equipment.

Information tab 
-------------------

The tab **Information** contains the general information of
the equipment and its controls :

-   **Id** : Unique identifier in the Jeedom database.

-   **Last name** : Name of equipment.

-   **Logical ID** : Logical equipment identifier (can
    to be empty).

-   **Object ID** : Unique identifier of the parent object (can
    to be empty).

-   **Creation date** : Equipment creation date.

-   **Activate** : Check the box to activate the equipment (don&#39;t forget
    to save).

-   **Jeedom** : Check the box to make the equipment visible (without
    forget to save).

-   **Type** : Identifier of the plugin by which it was created.

-   **Failed attempt** : Number of communications attempts
    consecutive with failed equipment.

-   **Date of last communication** : Date of last
    equipment communication.

-   **Last update** : Date of last communication
    with equipment.

-   **Tags** : equipment tags, to be separated by ','. It allows on the dashboard to make personalized filters

Below you will find a table with the list of commands for
the equipment with, for each, a link to their configuration.

View tab 
----------------

In the tab **Viewing**, you will be able to configure some
display behavior of the tile on the dashboard, the views, the
design as well as mobile.

### Widget 

-   **Jeedom** : Check the box to make the equipment visible.

-   **Show name** : Check the box to display the name of
    equipment on the tile.

-   **Display object name** : Check the box to display the name
    of the parent object of the equipment, next to the tile.

-   **Background color** : Check the box to keep the background color
    by default (depending on the **category** of your equipment, see
    **Administration → Settings → Colors**). If you uncheck this
    box, you can choose another color. You can also
    check a new box **Transparent** to make the
    transparent background.

-   **Opacity** : Opacity of the background color of the tile.

-   **Text color** : Check the box to keep the color of the
    default text.

-   **Borders** : Check the box to keep the default border.
    Otherwise, you have to put CSS code, property `border` (ex :
    `3px blue dashed` for a dotted border of 3px in blue).

-   **Rounded edges** (in px) : Check the box to keep
    the default rounding. Otherwise, you must put CSS code, property
    `border-radius` (ex : ``10px``)

### Optional parameters on the tile 

Below, we find optional display parameters that we
can apply to equipment. These parameters are composed of a name and
a value of. Just click on **Add** to apply one
new. For equipment, only the value **style** is for the
moment used, it allows to insert CSS code on the equipment in
question.

> **Tip**
>
> Do not forget to save after any modification.

Layout tab 
------------------

This part allows you to choose between the standard arrangement of
commands (side by side in the widget), or in table mode. There is
nothing to set in default mode. Here are the options available in mode
**Board** :

-   **Number of lines**

-   **Number of columns**

-   **Center in boxes** : Check the box to center the
    commands in the boxes.

-   **General style of boxes (CSS)** : Allows you to define the style
    general in CSS code.

-   **Table style (CSS)** : Allows you to define the style of the
    table only.

Below for each box, the **detailed configuration** It allows you to
this :

-   **Box text** : Add text in addition to the command (or
    all alone, if there is no order in the box).

-   **Case style (CSS)** : Change the specific CSS style of the
    box (beware this overwrites and replaces the general CSS
    boxes).

> **Tip**
>
> In a box in the table, if you want to put 2 commands one in
> below the other, don&#39;t forget to add a return to the
> line after the premiere in the **Advanced configuration** of it.

Alerts tab 
--------------

This tab provides information on the battery of
the equipment and define alerts in relation to it. Here are the
types of information that can be found :

-   **Battery type**,

-   **Latest feedback**,

-   **Remaining level**, (if of course your equipment works
    on battery).

Below, you can also define specific alert thresholds for
battery for this equipment. If you leave the boxes empty, those are
the default thresholds that will be applied.

You can also manage the timeout, in minutes, of the equipment. Through
example, 30 tells jeedom that if the equipment has not communicated
for 30 minutes, then you have to put it on alert.

> **Tip**
>
> The global parameters are in **Administration → Configuration → Logs**
> (or **Facilities**)

Comment tab 
------------------

Allows you to write a comment about the equipment (date of
changing the battery, for example).

Advanced configuration of an order 
====================================

First, at the top right, some buttons available :

-   **Test** : Used to test the command.

-   **Connections** : Displays the links of the equipment with the
    objects, commands, scenarios, variables, interactions…. under
    graphic form.

-   **Log** : Displays the events of the equipment in question.

-   **Information** : Displays the raw properties of the equipment.

-   To apply to\* : Apply the same configuration on
    multiple orders.

-   **Save** : Save changes made to
    equipment

> **Tip**
>
> In a graph, a double click on an element brings you to its
> d'actualité.

> **NOTE**
>
> Depending on the type of order, the information / actions displayed
> can change.

Information tab 
-------------------

The tab **Information** contains general information about the
ordered :

-   **Id** : Unique identifier in the database.

-   **Logical ID** : Logical identifier of the command (can
    to be empty).

-   **Last name** : Name of the order.

-   **Type** : Type of command (action or info).

-   **Subtype** : Command subtype (binary, digital, etc.)).

-   **Direct url** : Provides the URL to access this equipment. (click
    right, copy the link address) The URL will launch the command for a
    **Action** and return the information for a **Info**.

-   **Unit** : Control unit.

-   **Command triggering an update** : Gives the identifier of a
    other command which, if that other command changes, will force the
    update of the displayed order.

-   **Jeedom** : Check this box to make the command visible.

-   **Follow in the timeline** : Check this box to have this
    command is visible in the timeline when it is used.

-   **Prohibit in automatic interactions** : forbids them
    automatic interactions on this command

-   **Icon** : Allows you to change the command icon.

You also have three other orange buttons underneath :

-   **This command replaces the ID** : Replace an ID of
    order by the order in question. Useful if you have deleted a
    equipment in Jeedom and you have scenarios that use
    commands from it.

-   **This command replaces the command** : Replace an order with
    the current command.

-   **Replace this command with the command** : The reverse replaces
    the order by another order.

> **NOTE**
>
> This kind of action replaces commands all over Jeedom
> (scenario, interaction, command, equipment….)

Below, you will find the list of different equipment,
commands, scenarios, or interactions that use this command. A
click on it to go directly to their configuration
respective.

Configuration tab 
--------------------

### For an info type order : 

-   **Calculation and rounding**

    -   **Algorithm (\#value\# for value)** : Allows
        make an operation on the value of the order before
        Jeedom treatment, example : ``#value# - 0.2` to entrench
        0.2 (offset on a temperature sensor).

    -   **Rounded (number after decimal point)** : Lets round the
        order value (Example : put 2 to transform
        16.643345 in 16.64).

-   **Generic type** : Allows you to configure the generic type of the
    command (Jeedom tries to find it by itself in auto mode).
    This information is used by the mobile application.

-   **Action on value, if** : Lets make kinds of
    mini scenarios. You can, for example, say that if the value is worth
    more than 50 for 3 minutes, then you have to do such action. it
    allows, for example, to turn off a light X minutes after
    it is lit.

-   **Historical**

    -   **Historicize** : Check the box to have the values for this
        order be recorded. (See **Analysis → History**)

    -   **Smoothing mode** : Mode of **smooth** or d'**archiving**
        allows you to choose how to archive the data. By default,
        it&#39;s a **average**. It is also possible to choose the
        **Maximum**, The **Minimum**, or **No**. **No** Allows
        tell Jeedom that it should not archive on this
        order (both during the first 5 min period and with the
        archiving task). This option is dangerous because Jeedom
        keep everything : so there is going to be a lot more
        stored data.

    -   **Purge history if older than** : Lets say to
        Jeedom to delete all data older than one
        certain period. May be handy for not keeping
        data if it is not necessary and therefore limit the quantity
        of information recorded by Jeedom.

-   **Values management**

    -   **Prohibited value** : If the command takes one of these values,
        Jeedom ignores it before applying it.

    -   **Status return value** : Returns the command to
        this value after a while.

    -   **Duration before return of status (min)** : Time before return to
        value above.

-   **Other**

    -   **Management of the repetition of values** : In automatic if the
        command goes up 2 times the same value in a row, then Jeedom
        will not take into account the 2nd ascent (avoid triggering
        several times a scenario, unless the command is to
        binary type). You can force the value to repeat or
        ban it completely.

    -   **Push URL** : Allows you to add a URL to call in case of
        order update. You can use tags
        next : ``#value#`for the value of the order,`#cmd_name#``
        for the name of the command, `#cmd_id#`for the unique identifier
        of the command, `#humanname#`for the full name of the command
        (Ex : ``#[SalThe de bain][Hydrometrie][Humidité]#`),`#eq_name#`for the name of the equipment

### For an action command : 

-   **Generic type** : Allows you to configure the generic type of the
    command (Jeedom tries to find it by itself in auto mode).
    This information is used by the mobile application.

-   **Confirm action** : Check this box for Jeedom to request
    confirmation when the action is launched from the interface
    of this command.

-   **Access code** : Allows to define a code that Jeedom will ask
    when the action is launched from the interface of this command.

-   **Action before execution of the command** : Allows you to add
    orders **before** each execution of the order.

-   **Action after execution of the order** : Allows you to add
    orders **after** each execution of the order.

Alerts tab 
--------------

Allows you to define an alert level (**Warning** or **Danger**) en
depending on certain conditions. For example, if `value&gt; 8` for 30
minutes then the equipment can go on alert **Warning**.

> **NOTE**
>
> On the page **Administration → Configuration → Logs**, you can
> configure a message type command that will allow Jeedom to get you
> warn if the warning or danger threshold is reached.

View tab 
----------------

In this part, you will be able to configure certain behaviors
display of the widget on the dashboard, views, design and
mobile.

-   **Widget** : Allows you to choose the widget on dekstop or mobile (at
    note that you need the widget plugin and you can do that too
    from it).

-   **Jeedom** : Check to make the command visible.

-   **Show name** : Check to make the name of the
    command, depending on the context.

-   **Display name and icon** : Check to make the icon visible
    in addition to the name of the command.

-   **Wrapped line before widget** : Select **before
    widget** or **after the widget** to add a line break
    before or after the widget (to force for example a display in
    column of different equipment commands instead of lines
    By default)

Below, we find optional display parameters that we
can switch to widget. These parameters depend on the widget in question,
so you have to look at his card on the Market to know them.

> **Tip**
>
> Do not forget to save after any modification.

Code tab 
-----------

Allows you to modify the widget code just for the current command.

> **NOTE**
>
> If you want to modify the code don&#39;t forget to check the box
> **Enable widget customization**
