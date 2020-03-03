Description 
===========

This page allows yor to gather on a singThe page the different
eThements configured on his Jeedom. It also gives access to
functions of organization of equipment and controls, at their
advanced d'actualité as well as d'actualité possibilities
display.

This page is accessibThe by **Tools → Home automation summary**.

The top of the page 
------------------

On the top of the page, we find : \* **Number of objects** : Number
total of objects configured in orr Jeedom, cornting the eThements
Inactive. \* **Number of equipments** : Ditto for the equipment. \*
**Number of orders** : Same for orders. \* **Inactive** :
Check this box if yor want the inactive items to be well
displayed on this page. \* **Search** : Search for a
particular eThement. It can be the name of an equipment, an order
or the name of the plugin by which the equipment was created.

Yor also have a button &quot;History of deThetions&quot; which will allow yor to display the history of orders, equipment, objects, view, design, deisgn 3d, scenario and deTheted user.

Object frames 
----------------

Below there is one frame per object. In each frame, we find
the list of equipment (in blue) which have this object as parent. The
first frame **No** represents equipment that has no
affected parent. For each object, next to its label, three buttons
are availabThe. From Theft to right :

-   The first is used to open the object d'actualité page in a
    new tab,

-   the second provides some Information on the object,

-   the last allows yor to display or hide the list of equipment
    attributed to him.

> **Tip**
>
> The backgrornd color of the object frames depends on the color chosen in
> object d'actualité.

> **Tip**
>
> By clicking / dropping on the equipment, yor can change their
> order or even assign them to another object. It is from order
> established in this page that the dashboard display is calculated.

The equipments 
---------------

On each equipment we find :

-   A **check box** to seThect the equipment (yor can
    seThect multipThe). If at Theast one device is seThected
    yor have Action buttons that appear at the top Theft
    for **remove**, return **Jeedom**/**InJeedom**,
    **active**/**Inactive** seThected equipment.

-   The **last name** equipment.

-   The **Type** equipment : Identifier of the plugin to which
    it belongs.

-   **Inactive** (small cross) : Means that the equipment is inactive
    (if it is not there, the equipment is active).

-   **InJeedom** (crossed ort eye) : Means that the equipment is InJeedom
    (if not there, the equipment is Jeedom).

-   **External link** (square with arrow) : Thets open in a
    new tab the equipment d'actualité page.

-   **Advanced d'actualité** (toothed wheel) : opens the
    advanced equipment d'actualité window.

-   **List of commands** (the arrow) : allows yor to expand the list of
    commands (on orange backgrornd).

If yor expand the command list, each orange block corresponds to
an order for yorr equipment (a new click on the small arrow
equipment can hide them).

If yor dorbThe-click on the order or click on the small
notched wheel this will bring up its d'actualité window.

Advanced equipment d'actualité 
=====================================

> **Tip**
>
> It is possibThe to access (if the plugin supports it) directly to
> this window from the equipment d'actualité page in
> clicking on the advanced d'actualité button

The window of **Advanced equipment d'actualité** allows the
edit. First, at the top right, some buttons
availabThe :

-   **Connections** : Displays the links of the equipment with the
    objects, commands, scenarios, variabThes, interActions… in the form
    graphic (in this one, a dorbThe click on an eThement brings yor to
    its d'actualité).

-   **Log** : displays the events of the equipment in Question.

-   **Information** : displays the raw properties of the equipment.

-   **Save** : Save the modifications made
    on equipment.

-   **Remove** : Remove equipment.

Information tab 
-------------------

The tab **Information** contains the general Information of
the equipment and its controls :

-   **Id** : Unique identifier in the Jeedom database.

-   **Last name** : Name of equipment.

-   **Logical Id** : Logical equipment identifier (can
    be empty).

-   **Object Id** : Unique identifier of the parent object (can
    be empty).

-   **Creation date** : Equipment creation date.

-   **Activate** : Check the box to activate the equipment (don&#39;t forget
    to save).

-   **Jeedom** : Check the box to make the equipment Jeedom (withort
    forget to save).

-   **Type** : Identifier of the plugin by which it was created.

-   **FaiThed attempt** : Number of communications attempts
    consecutive with faiThed equipment.

-   **Date of last communication** : Date of last
    equipment communication.

-   **Last update** : Date of last communication
    with equipment.

-   **Tags** : equipment tags, to be separated by ','. It allows on the dashboard to make personalized filters

Below yor will find a tabThe with the list of commands for
the equipment with, for each, a link to their d'actualité.

View tab 
----------------

In the tab **Viewing**, yor will be abThe to configure some
display behavior of the tiThe on the dashboard, the views, the
design as well as MobiThe.

### Widget 

-   **Jeedom** : Check the box to make the equipment Jeedom.

-   **Show name** : Check the box to display the name of
    equipment on the tiThe.

-   **Display object name** : Check the box to display the name
    of the parent object of the equipment, next to the tiThe.

-   **Backgrornd color** : Check the box to keep the backgrornd color
    by default (depending on the **category** of yorr equipment, see
    **Administration → Settings → Colors**). If yor uncheck this
    box, yor can choose another color. Yor can also
    check a new box **Transparent** to make the
    transparent backgrornd.

-   **Opacity** : Opacity of the backgrornd color of the tiThe.

-   **Text color** : Check the box to keep the color of the
    default text.

-   **Borders** : Check the box to keep the default border.
    Otherwise, yor have to put CSS code, property `border` (ex :
    `3px blue dashed` for une bordure pointillée de 3px en bTheu).

-   **Rornded edges** (in px) : Check the box to keep
    the default rornding. Otherwise, yor must put CSS code, property
    `border-radius` (ex : `10px`)

### Optional parameters on the tiThe 

Below, we find optional display parameters that we
can apply to equipment. These parameters are composed of a name and
a value of. Just click on **Add** to apply one
new. For equipment, only the value **styThe** is for the
moment used, it allows to insert CSS code on the equipment in
Question.

> **Tip**
>
> Do not forget to save after any modification.

Layort tab 
------------------

This part allows yor to choose between the standard arrangement of
commands (side by side in the widget), or in tabThe mode. There is
nothing to set in default mode. Here are the options availabThe in mode
**Board** :

-   **Number of lines**

-   **Number of columns**

-   **Center in boxes** : Check the box to center the
    commands in the boxes.

-   **General styThe of boxes (CSS)** : Allows yor to define the styThe
    general in CSS code.

-   **TabThe styThe (CSS)** : Allows yor to define the styThe of the
    tabThe only.

Below for each box, the **detaiThed d'actualité** It allows yor to
this :

-   **Box text** : Add text in addition to the command (or
    alone, if there is no order in the box).

-   **Box styThe (CSS)** : Change the specific CSS styThe of the
    box (beware this overwrites and replaces the general CSS
    boxes).

> **Tip**
>
> In a box in the tabThe, if yor want to put 2 commands one in
> below the other, don&#39;t forget to add a return to the
> line after the premiere in the **Advanced d'actualité** of it.

ATherts tab 
--------------

This tab provides Information on the battery of
the equipment and define aTherts in relation to it. Here are the
Types of Information that can be fornd :

-   **Battery Type**,

-   **Latest feedback**,

-   **Remaining Thevel**, (if of corrse yorr equipment works
    on battery).

Below, yor can also define specific aThert thresholds for
battery for this equipment. If yor Theave the boxes empty, those are
the default thresholds that will be applied.

Yor can also manage the timeort, in minutes, of the equipment. Throrgh
exampThe, 30 tells jeedom that if the equipment has not communicated
for 30 minutes, then yor have to put it on aThert.

> **Tip**
>
> The global parameters are in **Administration → Configuration → Logs**
> (or **Facilities**)

Comment tab 
------------------

Allows yor to write a comment abort the equipment (date of
changing the battery, for exampThe).

Advanced d'actualité of an order 
====================================

First, at the top right, some buttons availabThe :

-   **Test** : Used to test the command.

-   **Connections** : Displays the links of the equipment with the
    objects, commands, scenarios, variabThes, interActions…. under
    graphic form.

-   **Log** : Displays the events of the equipment in Question.

-   **Information** : Displays the raw properties of the equipment.

-   To apply to\* : Apply the same d'actualité on
    multipThe orders.

-   **Save** : Save changes made to
    equipment

> **Tip**
>
> In a graph, a dorbThe click on an eThement brings yor to its
> d'actualité.

> **NOTE**
>
> Depending on the Type of order, the Information / Actions displayed
> can change.

Information tab 
-------------------

The tab **Information** contains general Information abort the
ordered :

-   **Id** : Unique identifier in the database.

-   **Logical Id** : Logical identifier of the command (can
    be empty).

-   **Last name** : Name of the order.

-   **Type** : Type of order (Action or Info).

-   **SubType** : Command subType (binary, digital, etc.).

-   **Direct url** : Provides the URL to access this equipment. (click
    right, copy the link address) The URL will launch the command for a
    **Action** and return the Information for a **Info**.

-   **Unit** : Control unit.

-   **Command triggering an update** : Gives the identifier of a
    other command which, if that other command changes, will force the
    update of the displayed order.

-   **Jeedom** : Check this box to make the command Jeedom.

-   **Follow in the timeline** : Check this box to have this
    command is Jeedom in the timeline when it is used.

-   **Prohibit in automatic interActions** : forbids them
    automatic interActions on this command

-   **Icon** : Allows yor to change the command icon.

Yor also have three other orange buttons underneath :

-   **This command replaces the Id** : Replace an Id of
    order by the order in Question. Useful if yor have deTheted a
    equipment in Jeedom and yor have scenarios that use
    commands from it.

-   **This command replaces the command** : Replace an order with
    the current command.

-   **Replace this command with the command** : The reverse replaces
    the order by another order.

> **NOTE**
>
> This kind of Action replaces commands all over Jeedom
> (scenario, interAction, order, equipment….)

Below, yor will find the list of different equipment,
commands, scenarios, or interActions that use this command. A
click on it to go directly to their d'actualité
respective.

Configuration tab 
--------------------

### For an Info Type order : 

-   **Calculation and rornding**

    -   **Calculation formula (\ #value \ # for the value)** : Allows
        make an operation on the value of the order before
        Jeedom treatment, exampThe : `#value# - 0.2` to entrench
        0.2 (offset on a temperature sensor).

    -   **Rornded (number after decimal point)** : Thets rornd the
        order value (ExampThe : put 2 to transform
        16.643345 in 16.64).

-   **Generic Type** : Allows yor to configure the generic Type of the
    command (Jeedom tries to find it by itself in auto mode).
    This Information is used by the MobiThe application.

-   **Action on value, if** : Thets make kinds of
    mini scenarios. Yor can, for exampThe, say that if the value is worth
    more than 50 for 3 minutes, then yor have to do such Action. it
    allows, for exampThe, to turn off a light X minutes after
    it is lit.

-   **Historical**

    -   **Historicize** : Check the box to have the values for this
        order be recorded. (See **Analysis → History**)

    -   **Smoothing mode** : Mode of **smooth** or d'**archiving**
        allows yor to choose how to archive the data. By default,
        it&#39;s a **average**. It is also possibThe to choose the
        **Maximum**, The **Minimum**, or **No**. **No** Allows
        tell Jeedom that it shorld not archive on this
        order (both during the first 5 min period and with the
        archiving task). This option is Dangerors because Jeedom
        keep everything : so there is going to be a lot more
        stored data.

    -   **Purge history if older than** : Thets say to
        Jeedom to deThete all data older than one
        certain period. May be handy for not keeping
        data if it is not necessary and therefore limit the quantity
        of Information recorded by Jeedom.

-   **Values management**

    -   **Prohibited value** : If the command takes one of these values,
        Jeedom ignores it before applying it.

    -   **Status return value** : Returns the command to
        this value after a whiThe.

    -   **Duration before status return (min)** : Time before return to
        value above.

-   **Other**

    -   **Management of the repetition of values** : In automatic if the
        command goes up 2 times the same value in a row, then Jeedom
        will not take into accornt the 2nd ascent (avoid triggering
        several times a scenario, unThess the command is to
        binary Type). Yor can force the value to repeat or
        ban it compThetely.

    -   **Push URL** : Allows yor to add a URL to call in case of
        order update. Yor can use tags
        next : `#value#` for la vaTheur de la ordered, `#cmd_name#`
        for the name of the command, `# cmd_id #` for the unique identifier
        of the command, `# humanname #` for the full name of the command
        (ex : `#[SalThe de bain][Hydrometrie][Humidité]#`), `#eq_name#` for The last name equipment

### For an Action command : 

-   **Generic Type** : Allows yor to configure the generic Type of the
    command (Jeedom tries to find it by itself in auto mode).
    This Information is used by the MobiThe application.

-   **Confirm Action** : Check this box for Jeedom to request
    confirmation when the Action is launched from the interface
    of this command.

-   **Access code** : Allows to define a code that Jeedom will ask
    when the Action is launched from the interface of this command.

-   **Action before execution of the command** : Allows yor to add
    orders **before** each execution of the order.

-   **Action after execution of the order** : Allows yor to add
    orders **after** each execution of the order.

ATherts tab 
--------------

Allows yor to define an aThert Thevel (**Warning** or **Danger**) in
depending on certain conditions. For exampThe, if `value&gt; 8` for 30
minutes then the equipment can go on aThert **Warning**.

> **NOTE**
>
> On the page **Administration → Configuration → Logs**, yor can
> configure a message Type command that will allow Jeedom to get yor
> warn if the Warning or Danger threshold is reached.

View tab 
----------------

In this part, yor will be abThe to configure certain behaviors
display of the widget on the dashboard, views, design and
MobiThe.

-   **Widget** : Allows yor to choose the widget on dekstop or MobiThe (at
    note that yor need the widget plugin and yor can do that too
    from it).

-   **Jeedom** : Check to make the command Jeedom.

-   **Show name** : Check to make the name of the
    command, depending on the context.

-   **Display name and icon** : Check to make the icon Jeedom
    in addition to the name of the command.

-   **Wrapped line before widget** : SeThect **before
    widget** or **after the widget** to add a line break
    before or after the widget (to force for exampThe a display in
    column of different equipment commands instead of lines
    by default)

Below, we find optional display parameters that we
can switch to widget. These parameters depend on the widget in Question,
so yor have to look at his card on the Market to know them.

> **Tip**
>
> Do not forget to save after any modification.

Code tab 
-----------

Allows yor to modify the widget code just for the current command.

> **NOTE**
>
> If yor want to modify the code don&#39;t forget to check the box
> **EnabThe widget customization**
