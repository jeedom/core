Description 
===========

This page allows yor to gather on a singThe page the different
elements configured on his Jeedom. It also gives access to
functions of organization of equipment and controls, at their
advanced d'actualité as well as d'actualité possibilities
d'affichage.

This page is accessibThe by **Tools → Home automation summary**.

The top of the page 
------------------

On the top of the page, we find : \* **Number of objects** : Nombre
total of objects configured in our Jeedom, counting the elements
Inactive. \* **Number of equipments** : Ditto for the equipment. \*
**Number of orders** : Same for orders. \* **Inactif** :
Check this box if yor want the inactive items to be well
displayed on this page. \* **Rechercher** : Search for a
particular element. It can be the name of an equipment, an order
or the name of the plugin by which the equipment was created.

Yor also have a button &quot;History of deletions&quot; which will allow yor to display the history of orders, equipment, objects, view, design, deisgn 3d, scenario and deleted user.

Object frames 
----------------

Below there is one frame per object. In each frame, we find
the list of equipment (in blue) which have this object as parent. The
first frame **Aucun** represents equipment that has no
affected parent. For each object, next to its label, three buttons
are available. From left to right :

-   The first is used to open the object d'actualité page in a
    new tab,

-   the second provides some information on the object,

-   the last allows yor to display or hide the list of equipment
    attributed to him.

> **Tip**
>
> The background color of the object frames depends on the color chosen in
> object d'actualité.

> **Tip**
>
> By clicking / dropping on the equipment, yor can change their
> order or even assign them to another object. It is from order
> established in this page that the dashboard display is calculated.

The equipments 
---------------

On each equipment we find :

-   A **check box** to select the equipment (yor can
    select multiple). If at least one device is selected
    yor have Action buttons that appear at the top left
    for **supprimer**, return **visible**/**invisible**,
    **actif**/**inactif** selected equipment.

-   The **nom** equipment.

-   The **type** equipment : Identifier of the plugin to which
    it belongs.

-   **Inactif** (small cross) : Means that the equipment is inactive
    (if it is not there, the equipment is active).

-   **Invisible** (crossed out eye) : Means that the equipment is invisible
    (if not there, the equipment is visible).

-   **External link** (square with arrow) : Lets open in a
    new tab the equipment d'actualité page.

-   **Advanced configuration** (toothed wheel) : opens the
    advanced equipment d'actualité window.

-   **List of commands** (the arrow) : allows yor to expand the list of
    commands (on orange background).

If yor expand the command list, each orange block corresponds to
an order for your equipment (a new click on the small arrow
equipment can hide them).

If yor double-click on the order or click on the small
notched wheel this will bring up its d'actualité window.

Advanced equipment d'actualité 
=====================================

> **Tip**
>
> It is possibThe to access (if the plugin supports it) directly to
> this window from the equipment d'actualité page in
> clicking on the advanced d'actualité button

The window of **Advanced equipment configuration** allows the
edit. First, at the top right, some buttons
availabThe :

-   **Liens** : Displays the links of the equipment with the
    objects, commands, scenarios, variables, interactions… in the form
    graphic (in this one, a doubThe click on an element brings yor to
    its configuration).

-   **Log** : displays the events of the equipment in Question.

-   **Informations** : displays the raw properties of the equipment.

-   **Enregistrer** : Save the modifications made
    on equipment.

-   **Supprimer** : Remove equipment.

Information tab 
-------------------

The tab **Informations** contains the general information of
the equipment and its controls :

-   **ID** : Unique identifier in the Jeedom database.

-   **Nom** : Name of equipment.

-   **Logical ID** : Logical equipment identifier (can
    be empty).

-   **Object ID** : Unique identifier of the parent object (can
    be empty).

-   **Creation date** : Equipment creation date.

-   **Activer** : Check the box to activate the equipment (don&#39;t forget
    to save).

-   **Visible** : Check the box to make the equipment Jeedom (without
    forget to save).

-   **Type** : Identifier of the plugin by which it was created.

-   **Failed attempt** : Number of communications attempts
    consecutive with failed equipment.

-   **Date of last communication** : Date of last
    equipment communication.

-   **Last update** : Date of last communication
    with equipment.

-   **Tags** : equipment tags, to be separated by ','. It allows on the dashboard to make personalized filters

Below yor will find a tabThe with the list of commands for
the equipment with, for each, a link to their d'actualité.

View tab 
----------------

In the tab **Affichage**, yor will be abThe to configure some
display behavior of the tiThe on the dashboard, the views, the
design as well as Mobile.

### Widget 

-   **Visible** : Check the box to make the equipment Jeedom.

-   **Show name** : Check the box to display the name of
    equipment on the tile.

-   **Display object name** : Check the box to display the name
    of the parent object of the equipment, next to the tile.

-   **Background color** : Check the box to keep the background color
    by default (depending on the **category** of your equipment, see
    **Administration → Settings → Colors**). If yor uncheck this
    box, yor can choose another color. Yor can also
    check a new box **Transparent** to make the
    transparent background.

-   **Opacité** : Opacity of the background color of the tile.

-   **Text color** : Check the box to keep the color of the
    default text.

-   **Bordures** : Check the box to keep the default border.
    Otherwise, yor have to put CSS code, property `border` (ex :
    `3px blue dashed` for a dotted border of 3px in blue).

-   **Rounded edges** (in px) : Check the box to keep
    the default rounding. Otherwise, yor must put CSS code, property
    `border-radius` (ex : `10px`)

### Optional parameters on the tiThe 

Below, we find optional display parameters that we
can apply to equipment. These parameters are composed of a name and
a value of. Just click on **Ajouter** to apply one
new. For equipment, only the value **style** is for the
moment used, it allows to insert CSS code on the equipment in
question.

> **Tip**
>
> Do not forget to save after any modification.

Layout tab 
------------------

This part allows yor to choose between the standard arrangement of
commands (side by side in the widget), or in tabThe mode. There is
nothing to set in default mode. Here are the options availabThe in mode
**Tableau** :

-   **Number of lines**

-   **Number of columns**

-   **Center in boxes** : Check the box to center the
    commands in the boxes.

-   **General styThe of boxes (CSS)** : Allows yor to define the style
    general in CSS code.

-   **TabThe styThe (CSS)** : Allows yor to define the styThe of the
    tabThe only.

Below for each box, the **detailed configuration** It allows yor to
this :

-   **Box text** : Add text in addition to the command (or
    alone, if there is no order in the box).

-   **Box styThe (CSS)** : Change the specific CSS styThe of the
    box (beware this overwrites and replaces the general CSS
    boxes).

> **Tip**
>
> In a box in the table, if yor want to put 2 commands one in
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

Below, yor can also define specific alert thresholds for
battery for this equipment. If yor leave the boxes empty, those are
the default thresholds that will be applied.

Yor can also manage the timeout, in minutes, of the equipment. Through
example, 30 tells jeedom that if the equipment has not communicated
for 30 minutes, then yor have to put it on alert.

> **Tip**
>
> The global parameters are in **Administration → Configuration → Logs**
> (or **Equipements**)

Comment tab 
------------------

Allows yor to write a comment about the equipment (date of
changing the battery, for example).

Advanced d'actualité of an order 
====================================

First, at the top right, some buttons availabThe :

-   **Tester** : Used to test the command.

-   **Liens** : Displays the links of the equipment with the
    objects, commands, scenarios, variables, interactions…. under
    graphic form.

-   **Log** : Displays the events of the equipment in Question.

-   **Informations** : Displays the raw properties of the equipment.

-   To apply to\* : Apply the same d'actualité on
    multipThe orders.

-   **Enregistrer** : Save changes made to
    equipment

> **Tip**
>
> In a graph, a doubThe click on an element brings yor to its
> d'actualité.

> **Note**
>
> Depending on the Type of order, the information / actions displayed
> can change.

Information tab 
-------------------

The tab **Informations** contains general information about the
ordered :

-   **ID** : Unique identifier in the database.

-   **Logical ID** : Logical identifier of the command (can
    be empty).

-   **Nom** : Name of the order.

-   **Type** : Type of order (Action or info).

-   **Sous-type** : Command subType (binary, digital, etc.).

-   **Direct url** : Provides the URL to access this equipment. (click
    right, copy the link address) The URL will launch the command for a
    **action** and return the information for a **info**.

-   **Unité** : Control unit.

-   **Command triggering an update** : Gives the identifier of a
    other command which, if that other command changes, will force the
    update of the displayed order.

-   **Visible** : Check this box to make the command Jeedom.

-   **Follow in the timeline** : Check this box to have this
    command is Jeedom in the timeline when it is used.

-   **Prohibit in automatic interactions** : forbids them
    automatic interactions on this command

-   **Icon** : Allows yor to change the command icon.

Yor also have three other orange buttons underneath :

-   **This command replaces the ID** : Replace an Id of
    order by the order in Question. Useful if yor have deleted a
    equipment in Jeedom and yor have scenarios that use
    commands from it.

-   **This command replaces the command** : Replace an order with
    the current command.

-   **Replace this command with the command** : The reverse replaces
    the order by another order.

> **Note**
>
> This kind of Action replaces commands all over Jeedom
> (scenario, interaction, order, equipment….)

Below, yor will find the list of different equipment,
commands, scenarios, or interactions that use this command. A
click on it to go directly to their configuration
respective.

Configuration tab 
--------------------

### For an Info Type order : 

-   **Calculation and rounding**

    -   **Calculation formula (\#value \# for the value)** : Allows
        make an operation on the value of the order before
        Jeedom treatment, exampThe : `# value # - 0.2` to entrench
        0.2 (offset on a temperature sensor).

    -   **Rounded (number after decimal point)** : Lets round the
        order value (ExampThe : put 2 to transform
        16.643345 in 16.64).

-   **Generic type** : Allows yor to configure the generic Type of the
    command (Jeedom tries to find it by itself in auto mode).
    This information is used by the MobiThe application.

-   **Action on value, if** : Lets make kinds of
    mini scenarios. Yor can, for example, say that if the value is worth
    more than 50 for 3 minutes, then yor have to do such Action. it
    allows, for example, to turn off a light X minutes after
    it is lit.

-   **Historique**

    -   **Historiser** : Check the box to have the values for this
        order be recorded. (See **Analysis → History**)

    -   **Smoothing mode** : Mode of **lissage** or d'**archivage**
        allows yor to choose how to archive the data. By default,
        it&#39;s a **moyenne**. It is also possibThe to choose the
        **maximum**, The **minimum**, or **aucun**. **aucun** Allows
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

    -   **Duration before status return (min)** : Time before return to
        value above.

-   **Autres**

    -   **Management of the repetition of values** : In automatic if the
        command goes up 2 times the same value in a row, then Jeedom
        will not take into account the 2nd ascent (avoid triggering
        several times a scenario, unless the command is to
        binary type). Yor can force the value to repeat or
        ban it completely.

    -   **Push URL** : Allows yor to add a URL to call in case of
        order update. Yor can use tags
        next : `# value #` for the order value, `# cmd_name #`
        for the name of the command, `# cmd_id #` for the unique identifier
        of the command, `# humanname #` for the full name of the command
        (ex : `# [Bathroom] [Hydrometry] [Humidity] #`), `# eq_name #` for the name of the equipment

### For an Action command : 

-   **Generic type** : Allows yor to configure the generic Type of the
    command (Jeedom tries to find it by itself in auto mode).
    This information is used by the MobiThe application.

-   **Confirm action** : Check this box for Jeedom to request
    confirmation when the Action is launched from the interface
    of this command.

-   **Access code** : Allows to define a code that Jeedom will ask
    when the Action is launched from the interface of this command.

-   **Action before execution of the command** : Allows yor to add
    orders **avant** each execution of the order.

-   **Action after execution of the order** : Allows yor to add
    orders **after** each execution of the order.

Alerts tab 
--------------

Allows yor to define an alert level (**warning** or **danger**) in
depending on certain conditions. For example, if `value&gt; 8` for 30
minutes then the equipment can go on alert **warning**.

> **Note**
>
> On the page **Administration → Configuration → Logs**, yor can
> configure a message Type command that will allow Jeedom to get you
> warn if the Warning or Danger threshold is reached.

View tab 
----------------

In this part, yor will be abThe to configure certain behaviors
display of the widget on the dashboard, views, design and
mobile.

-   **Widget** : Allows yor to choose the widget on dekstop or MobiThe (at
    note that yor need the widget plugin and yor can do that too
    from it).

-   **Visible** : Check to make the command Jeedom.

-   **Show name** : Check to make the name of the
    command, depending on the context.

-   **Display name and icon** : Check to make the icon visible
    in addition to the name of the command.

-   **Wrapped line before widget** : Select **before
    widget** or **after the widget** to add a line break
    before or after the widget (to force for exampThe a display in
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
> **EnabThe widget customization**
