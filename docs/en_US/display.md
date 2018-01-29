Description
===========

This page allows to gather on a single page the different
elements configured on his Jeedom. It also gives access to
functions of organization of equipment and controls, to their
advanced configuration as well as configuration possibilities
display.

This page is accessible by **Tools → Home Automation Summary**.

The top of the page
------------------

On the top of the page, we find: \ * **Number of items**: Number
total of objects configured in our Jeedom, counting the elements
Inactive. \ * **Number of equipment**: Same for equipment. \ *
**Number of orders**: Ditto for orders. \ * **Inactive**:
Check this box if you want inactive items to be
posted on this page. \ * **Search**: Search for a
particular element. It can be the name of a piece of equipment, a command
or the name of the plugin by which the device was created.

Object frames
----------------

Below is a frame by object. In each frame, we find
the list of devices (in blue) that are related to this object. The
first frame **None** represents equipment that does not have
assigned parent. For each object, next to its label, three buttons
are available. From left to right :

-   The first one is used to open the configuration page of the object in a
    new tab,

-   the second brings some information about the object,

-   the last one allows to show or hide the list of equipment
    which are attributed to him.

> **Tip**
>
> The background color of the object frames depends on the color chosen in
> the configuration of the object.

> **Tip**
>
> By clicking / depositing on the equipment, you can change their
> order or even assign them to another object. It's from the order
> established in this page that the display of the dashboard is calculated.

The equipment
---------------

On each equipment we find:

-   A **checkbox** to select the equipment (you can
    select several). If at least one equipment is selected
    you have action buttons that appear at the top left
    for **delete**, make **visible** / **invisible**,
    **active** / **inactive** the selected equipment.

-   The **name** of the equipment.

-   The **type** of equipment: Plugin ID to which
    it belongs.

-   **Inactive** (small cross): Means that the equipment is inactive
    (If it is not there, the equipment is active).

-   **Invisible** (barred eye): Means that the equipment is invisible
    (if it is not there, the equipment is visible).

-   **External link** (square with an arrow): Opens in a
    new tab the equipment configuration page.

-   **Advanced configuration** (notched wheel): opens the
    advanced configuration window of the equipment.

-   **List of Orders** (the arrow): allows to unfold the list of
    orders (on orange background).

If you unfold the list of commands, each orange block corresponds to
a command of your equipment (a new click on the small arrow
equipment can hide them).

If you double-click on the command or click on the small
notched wheel this will bring up its configuration window.

Advanced equipment configuration
=====================================

> **Tip**
>
> It is possible to access (if the plugin supports it) directly to
> this window from the configuration page of the equipment in
> clicking on the advanced configuration button

The **advanced configuration of a device** window allows the
edit. First, at the top right, some buttons
available:

-   **Links**: Displays the links of the equipment with the
    objects, commands, scenarios, variables, interactions ... in form
    graph (in this one, a double click on an element will take you to
    its configuration).

-   **Log**: Displays the events of the equipment in question.

-   **Information**: Displays the raw properties of the equipment.

-   **Save**: Saves changes made
    on the equipment.

-   **Delete**: Deletes the equipment.

Information tab
-------------------

The **Information** tab contains the general information of
the equipment and its controls:

-   **ID**: Unique identifier in the Jeedom database.

-   **Name**: Name of the equipment.

-   **Logical ID**: logical identifier of the equipment (can
    to be empty).

-   **Object ID**: Unique identifier of the parent object (can
    to be empty).

-   **Creation date**: Date of creation of the equipment.

-   **Enable**: Check the box to enable the equipment (not to mention
    save).

-   **Visible**: Check the box to make the equipment visible (without
    forget to save).

-   **Type**: Identifier of the plugin by which it was created.

-   **Failed attempt**: Number of communications attempts
    consecutive with the equipment that failed.

-   **Date of last communication**: Date of last
    communication of the equipment.

-   **Last update**: Last communication date
    with the equipment.

Below you will find a table with the list of orders for
equipment with, for each, a link to their configuration.

Display tab
----------------

In the **Display** tab, you will be able to configure some
display behaviors of the tile on the dashboard, the views, the
design as well as mobile.

### widget

-   **Visible**: Check the box to make the equipment visible.

-   **Show Name**: Check the box to display the name of
    the equipment on the tile.

-   **Show the name of the object**: Check the box to display the name
    the parent object of the equipment, next to the tile.

-   **Background Color**: Check the box to keep the background color
    default (depending on the **category** of your equipment, see
    **Administration → Settings → Colors**). If you uncheck this
    box, you can choose another color. You will also be able to
    tick a new box **Transparent** to make the
    transparent background.

-   **Opacity**: Opacity of the background color of the tile.

-   **Text Color**: Check the box to keep the color of the
    default text.

-   **Borders**: Check the box to keep the default border.
    Otherwise, you have to put CSS code, property `border` (ex:
    `3px blue dashed` for a dashed border of 3px in blue).

-   **Round Border** (in px): Check the box to keep
    the rounding by default. Otherwise, you have to put CSS code, property
    `border-radius` (eg` 10px`)

### Optional parameters on the tile

Below, we find optional display parameters that we
can apply to the equipment. These parameters are composed of a name and
a value of. Just click on **Add** to apply one
new. For equipment, only the **style** value is for the
used, it allows to insert CSS code on the equipment in
question.

> **Tip**
>
> Do not forget to save after any modification.

Layout tab
------------------

This part allows you to choose between the standard layout of
commands (side by side in the widget), or in array mode. There is
nothing to set in default mode. Here are the options available in
**Board** :

-   **Number of lines**

-   **Number of columns**

-   **Center in boxes**: Check the box to center the
    commands in the boxes.

-   **General style of the boxes (CSS)**: Allows to define the style
    general in CSS code.

-   **Table Style (CSS)**: Allows you to set the style of the
    table only.

Below for each box, **detailed configuration** allows you
this:

-   **Text of the box**: Add text in addition to the order (or
    all alone, if there is no command in the box).

-   **Case Style (CSS)**: Change the specific CSS style of the
    case (watch this one overwrites and replaces the general CSS
    boxes).

> **Tip**
>
> In a box of the table, if you want to put 2 commands one in
> below the other, we must not forget to add a return to the
> line after the first in the **advanced configuration** of it.

Alerts tab
--------------

This tab allows you to have information about the battery of
equipment and set alerts against it. here are the
types of information that can be found:

-   **Battery type**,

-   **Last feedback**,

-   **Remaining level**, (if of course your equipment works
    on battery).

Below, you will also be able to define the specific alert thresholds for
battery for this equipment. If you leave the boxes blank, those are
the default thresholds that will be applied.

We can also manage the timeout, in minutes, of the equipment. By
example, 30 tells jeedom that if the equipment did not communicate
for 30 minutes, then it must be put on alert.

> **Tip**
>
> The global settings are in **Administration → Configuration → Logs**
> (or **Equipment**)

Comment tab
------------------

Allows you to write a comment about the equipment (date of
change of the battery, for example).

Advanced configuration of a command
====================================

First, at the top right, some buttons available:

-   **Test**: Test the command.

-   **Links**: Displays the links of the equipment with the
    objects, commands, scenarios, variables, interactions .... under
    graphic form.

-   **Log**: Displays the events of the equipment in question.

-   **Information**: Displays the raw properties of the equipment.

-   Apply to \ *: Apply the same configuration on
    several orders.

-   **Save**: Saves changes made on
    equipment

> **Tip**
>
> In a graph, a double click on an element brings you to its
> configuration.

> **Note**
>
> Depending on the type of order, the information / actions displayed
> can change.

Information tab
-------------------

The **Information** tab contains general information about the
command:

-   **ID**: Unique identifier in the database.

-   **Logical ID**: logical identifier of the command (can
    to be empty).

-   **Name**: Name of the order.

-   **Type**: Type of command (action or info).

-   **Subtype**: Subtype of the command (binary, numeric ...).

-   **Direct URL**: Provides the URL to access this device. (click
    right, copy the link address) The URL will launch the command for a
    **action** and will return the information for an **info**.

-   **Unit**: Unit of the order.

-   **Command triggering an update**: Gives the identifier of a
    another command that, if this other command changes, will force the
    update of the command displayed.

-   **Visible**: Check this box to make the command visible.

-   **Follow in timeline**: Check this box for this
    command is visible in the timeline when it is used.

-   **Prohibit in automatic interactions**: forbids
    automatic interactions on this command

-   **Icon**: Change the icon of the command.

You also have three other orange buttons underneath:

-   **This command replaces ID**: Allows you to replace an ID of
    command by the command in question. Useful if you have deleted a
    equipment in Jeedom and that you have scenarios that use
    orders from it.

-   **This command replaces the command**: Replaces a command by
    the current order.

-   **Replace this command with the command**: The reverse, replaces
    the order by another order.

> **Note**
>
> This kind of action replaces orders everywhere in Jeedom
> (scenario, interaction, order, equipment ....)

Below, you will find the list of different equipment,
commands, scenarios, or interactions that use this command. A
click on it to go directly to their configuration
respectively.

Configuration tab
--------------------

### For an order of type info:

-   **Calculation and rounding**

    -   **Calculation formula (\ #value \ # for the value)**: Allows you to
        do an operation on the value of the order before the
        processing by Jeedom, example: `# value # - 0.2` to subtract
        0.2 (offset on a temperature sensor).

    -   **Rounded (decimal place)**: Rounds up the
        value of the command (Example: put 2 for tranformer
        16.643345 in 16.64).

-   **Generic Type**: Allows you to configure the generic type of the
    command (Jeedom tries to find it by itself in auto mode).
    This information is used by the mobile application.

-   **Action on the value, if**: Allows to do sorts of
    mini scenarios. For example, you can say that if the value is
    more than 50 for 3 minutes, so you have to do that. it
    allows, for example, to turn off a light X minutes after
    it has lit up.

-   **Historical**

    -   **Historiser**: Check the box for the values ​​of this
        order are historized. (See **Analysis → History**)

    -   **Smoothing Mode**: **Smoothing Mode**or**archive**
        allows you to choose how to archive the data. By default,
        it's an **average**. It is also possible to choose the
        **maximum**, the **minimum**, or **none**. **none** allows
        tell Jeedom that he should not perform archiving on this
        command (both in the first 5 minute period and in the
        archive task). This option is dangerous because Jeedom
        keeps everything: so there will be a lot more
        retained data.

    -   **Purge history if older than**: Lets say to
        Jeedom to delete all the data older than a
        certain period. May be convenient for not keeping
        data if it is not necessary and therefore limit the amount
        of information recorded by Jeedom.

-   **Value Management**

    -   **Prohibited value**: If the command takes one of these values,
        Jeedom ignores it before applying it.

    -   **Return status**: Returns the command to
        this value after a while.

    -   **Duration before status return (min)**: Time before returning to the
        value above.

-   **Other**

    -   ** Management of the repetition of the values ​​**: In automatic if the
        command goes back twice the same value in a row, so Jeedom
        will not take into account the 2nd ascent (avoid triggering
        several times a scenario, unless the order is from
        binary type). You can force the repetition of the value or
        forbid it completely.

    -   **Push URL**: Allows you to add a URL to call in case of
        update the order. You can use tags
        next: `# value #` for the value of the command, `# cmd_name #`
        for the name of the command, `# cmd_id #` for the unique identifier
        the command, `# humanname #` for the full name of the command
        (ex: `# [Bathroom] [Hydrometry] [Humidity] #`)

### For an action command:

-   **Generic Type**: Allows you to configure the generic type of the
    command (Jeedom tries to find it by itself in auto mode).
    This information is used by the mobile application.

-   **Confirm Action**: Check this box for Jeedom to request
    a confirmation when the action is launched from the interface
    of this command.

-   **Access Code**: Allows you to define a code that Jeedom will ask for
    when the action is launched from the interface of this command.

-   **Action before executing the command**: Allows you to add
    commands **before** each execution of the command.

-   **Action after execution of the command**: Allows you to add
    orders **after** each execution of the order.

Alerts tab
--------------

Set an alert level (**warning**or**danger**) in
according to certain conditions. For example, if `value> 8` for 30
minutes then the equipment can go into alert **warning**.

> **Note**
>
> On the page **Administration → Configuration → Logs**, you can
> configure a message type command that will allow Jeedom to you
> warn if you reach the warning or danger threshold.

Display tab
----------------

In this part, you will be able to configure certain behaviors
of the widget on the dashboard, the views, the design and in
mobile.

-   **Widget**: Allows to choose the widget on dekstop or mobile (to
    note that you need the widget plugin and you can do it too
    from this one).

-   **Visible**: Check to make the command visible.

-   **Show name**: Check to make visible the name of the
    command, depending on the context.

-   **Show name and icon**: Check to make the icon visible
    in addition to the name of the order.

-   **Wrap line before widget**: Check ** before
    widget **or** after the widget ** to add a line break
    before or after the widget (to force for example a display in
    column of different orders of the equipment instead of lines
    by default)

Below, we find optional display parameters that we
can go to the widget. These settings depend on the widget in question,
so you have to look at his page on the Market to know them.

> **Tip**
>
> Do not forget to save after any modification.

Code tab
-----------

Modify the widget code just for the current command.

> **Note**
>
> If you want to modify the code do not forget to check the box
> **Enable widget customization**
