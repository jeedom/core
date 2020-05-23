# Domotic overview
**Analysis → Home automation summary**

This page allows you to gather on a single page the different elements configured on your Jeedom. It also gives access to functions for organizing equipment and controls, to their advanced configuration and to display configuration possibilities.

## Informations

On the top of the page, we find :
- **Number of objects** : Total number of objects configured in our Jeedom, including inactive elements.
- **Number of equipments** : Same for the equipment.
- **Number of orders** : Same for orders.
- **Inactive** : Check this box if you want inactive items to be displayed on this page.
- **Search** : Search for a particular item. It can be the name of a device, an order or the name of the plugin by which the device was created.
- **CSV export** : Allows you to export all objects, equipment and their commands to a CSV file.

You also have a tab **Historical**, displaying the history of orders, equipment, objects, views, design, 3d design, scenarios and deleted users.

## Object frames

Below there is one frame per object. In each frame, we find the list of equipment that have this object as parent.
The first frame **No** represents devices that have no assigned parent.

For each object, next to its label, two buttons are available.
- The first is used to open the object configuration page in a new tab.
- The second provides some information on the object,

> **Tip**
>
> The background color of the object frames depends on the color chosen in the configuration of the object.

> **Tip**
>
> With a click and drop on the objects or equipment, you can change their order or even assign them to another object. It is from the order established on this page that the display of the Dashboard is calculated.

## The equipments

On each equipment we find :

- A **check box** to select the equipment (you can select several). If at least one device is selected, you have action buttons that appear in the upper left to **remove**, return **Jeedom**/**Invisible**,  **active**/**Inactive** selected equipment.
- L'**Id** equipment.
- The **Type** equipment : Identifier of the plugin to which it belongs.
- The **last name** equipment.
- **Inactive** (small cross) : Means that the equipment is inactive (if it is not there, the equipment is active).
- **Invisible** (crossed eye) : Means that the equipment is invisible (if it is not there, the equipment is visible).

If the equipment plugin is disabled, the two icons on the right do not appear:
- **External link** (square with arrow) : Allows to open in a new tab the equipment configuration page.
- **Advanced configuration** (toothed wheel) : opens the advanced equipment configuration window.

> By clicking on the line containing the name of the equipment, you will display all the commands for this equipment. By clicking on an order, you will access the order configuration window.

## Advanced equipment configuration

> **Tip**
>
> It is possible to access (if the plugin supports it) directly to this window from the equipment configuration page by clicking on the advanced configuration button

The window of **Advanced equipment configuration** allows to modify it. First, at the top right, some buttons available :

- **Information** : displays the raw properties of the equipment.
- **Connections** : Allows to display the links of the equipment with the objects, commands, scenarios, variables, interactions… in graphic form (in this one, a double click on an element will take you to its configuration).
- **Log** : displays the events of the equipment in question.
- **Save** : Save the modifications made on the equipment.
- **Remove** : Remove equipment.

### Information tab

The tab **Information** contains general information about the equipment and its controls :

- **Id** : Unique identifier in the Jeedom database.
- **Last name** : Name of equipment.
- **Logical ID** : Logical identifier of the equipment (can be empty).
- **Object ID** : Unique identifier of the parent object (can be empty).
- **Creation date** : Equipment creation date.
- **Activate** : Check the box to activate the equipment (don't forget to save).
- **Jeedom** : Check the box to make the equipment visible (don't forget to save).
- **Type** : Identifier of the plugin by which it was created.
- **Failed attempt** : Number of failed consecutive communications attempts with the equipment.
- **Date of last communication** : Date of the last communication of the equipment.
- **Last update** : Date of last communication with the equipment.
- **Tags** : equipment tags, to be separated by ','. It allows on the Dashboard to make custom filters

Below you will find a table with the list of equipment commands with, for each, a link to their configuration.

### View tab

In the tab **Viewing**, you will be able to configure certain tile display behaviors on the Dashboard or on mobile.

#### Widget

-  **Jeedom** : Check the box to make the equipment visible.
- **Show name** : Check the box to display the name of the equipment on the tile.
- **Display object name** : Check the box to display the name of the parent object of the equipment, next to the tile.

### Optional parameters on the tile

Below, there are optional display parameters that can be applied to the equipment. These parameters are composed of a name and a value. Just click on **Add** to apply one
new. For equipment, only the value **style** is currently used, it allows to insert CSS code on the equipment in question.

> **Tip**
>
> Do not forget to save after any modification.

### Layout tab

This part allows you to choose between the standard layout of the commands (side by side in the widget), or in table mode. Nothing to set in default mode. Here are the options available in mode
**Board** :
- **Number of lines**
- **Number of columns**
- **Center in boxes** : Check the box to center the orders in the boxes.
- **General style of boxes (CSS)** : Allows you to define the general style in CSS code.
- **Table style (CSS)** : Allows you to define the style of the table only.

Below for each box, the **detailed configuration** It allows you to
this :
- **Box text** : Add text in addition to the command (or all by itself, if there is no command in the box).
- **Case style (CSS)** : Modify the specific CSS style of the box (note that it overwrites and replaces the general CSS of boxes).

> **Tip**
>
> In a box in the table, if you want to put 2 commands one below the other, don&#39;t forget to add a line break after the first in the **Advanced configuration** of it.

### Alerts tab

This tab allows you to have information on the battery of the equipment and to define alerts in relation to it. Here are the types of information that can be found :

- **Battery type**,
- **Latest feedback**,
- **Remaining level**, (if of course your equipment runs on battery).

Below, you can also define the specific battery alert thresholds for this equipment. If you leave the boxes empty, those are the default thresholds that will be applied.

You can also manage the timeout, in minutes, of the equipment. For example, 30 tells jeedom that if the equipment has not communicated for 30 minutes, then it must be put on alert.

> **Tip**
>
> The global parameters are in **→ Settings → System Configuration : Logs** or **Facilities**

### Comment tab

Allows you to write a comment about the equipment.

## Advanced configuration of an order

First, at the top right, some buttons available :

- **Test** : Used to test the command.
- **Connections** : Allows to display the links of the equipment with objects, commands, scenarios, variables, interactions…. in graphic form.
- **Log** : Displays the events of the equipment in question.
- **Information** : Displays the raw properties of the equipment.
-  **Apply to** : Allows the same configuration to be applied to several commands.
- **Save** : Save the modifications made on the equipment.

> **Tip**
>
> In a graphic, a double click on an element brings you to its configuration.

> **NOTE**
>
> Depending on the type of order, the information / actions displayed may change.

### Information tab

The tab **Information** contains general information about the order :

- **Id** : Unique identifier in the database.
- **Logical ID** : Logical identifier of the command (can be empty).
- **Last name** : Name of the order.
- **Type** : Type of command (action or info).
- **Subtype** : Command subtype (binary, digital, etc.)).
- **Direct url** : Provides the URL to access this equipment. (right click, copy the link address) The URL will launch the command for a **Action** and return the information for a **Info**.
- **Unit** : Control unit.
- **Command triggering an update** : Gives the identifier of another command which, if this other command changes, will force the update of the displayed command.
- **Jeedom** : Check this box to make the command visible.
- **Follow in the timeline** : Check this box to make this command visible in the timeline when it is used. You can specify a specific timeline in the field that appears if the option is checked.
- **Prohibit in automatic interactions** : prohibits automatic interactions on this command
- **Icon** : Allows you to change the command icon.

You also have three other orange buttons underneath :

- **This command replaces the ID** : Allows you to replace an order ID with the order in question. Useful if you have deleted an equipment in Jeedom and you have scenarios that use commands from it.
- **This command replaces the command** : Replace command with current command.
- **Replace this command with the command** : Conversely, replace the command with another command.

> **NOTE**
>
> This kind of action replaces commands everywhere in Jeedom (scenario, interaction, command, equipment….).

Below, you will find the list of the different equipment, commands, scenarios or interactions that use this command. Click on it to go directly to their respective configuration.

### Configuration tab

#### For an info type order :

- **Calculation and rounding**
    - **Algorithm (\#value\# for value)** : Allows you to make an operation on the value of the order before processing by Jeedom, example : ``#value# - 0.2` to subtract 0.2 (offset on a temperature sensor).
    - **Rounded (number after decimal point)** : Used to round the value of the command (Example : put 2 to transform 16.643,345 in 16.64).
- **Generic type** : Allows you to configure the generic type of the command (Jeedom tries to find it by itself in auto mode). This information is used by the mobile application.
- **Action on value, if** : Lets do some sort of mini scenarios. You can, for example, say that if the value is worth more than 50 for 3 minutes, then you have to do such action. This allows, for example, to turn off a light X minutes after it has turned on.

- **Historical**
    - **Historicize** : Check the box to have the values of this command logged. (See **Analysis → History**)
    - **Smoothing mode** : Mode of **smooth** or d'**archiving** allows you to choose how to archive the data. By default, this is a **average**. It is also possible to choose the **Maximum**, The **Minimum**, or **No**. **No** lets tell Jeedom that it should not perform archiving on this command (both during the first 5 mins period and with the archiving task). This option is dangerous because Jeedom keeps everything : so there will be a lot more data kept.
    - **Purge history if older than** : Lets tell Jeedom to delete all data older than a certain period. May be practical not to keep data if it is not necessary and therefore limit the amount of information recorded by Jeedom.

- **Values management**
    - **Prohibited value** : If the command takes one of these values, Jeedom ignores it before applying it.
    - **Status return value** : Returns the command to this value after a certain time.
    - **Duration before return of status (min)** : Time before return to the above value.

- **Other**
    - **Management of the repetition of values** : In automatic if the command goes up 2 times the same value in a row, then Jeedom will not take into account the 2nd ascent (avoids triggering a scenario several times, unless the command is of binary type). You can force the repetition of the value or prohibit it completely.
    - **Push URL** : Allows to add a URL to call in case of update of the order. You can use the following tags : ``#value#`for the value of the order,`#cmd_name#`for the name of the command,`#cmd_id#`for the unique identifier of the order,`#humanname#`for the full name of the command (ex : ``#[SalThe de bain][Hydrometrie][Humidité]#`),`#eq_name#`for the name of the equipment.

#### For an action command :

-  **Generic type** : Allows you to configure the generic type of the command (Jeedom tries to find it by itself in auto mode). This information is used by the mobile application.
- **Confirm action** : Check this box to have Jeedom request confirmation when the action is launched from the interface of this command.
- **Access code** : Allows to define a code that Jeedom will ask when the action is launched from the interface of this command.
- **Action before execution of the command** : Add commands **before** each execution of the order.
- **Action after execution of the order** : Add commands **after** each execution of the order.

### Alerts tab

Allows you to define an alert level (**Warning** or **Danger**) depending on certain conditions. For example, if `value&gt; 8` for 30 minutes then the equipment can go on alert **Warning**.

> **NOTE**
>
> On the page **→ Settings → System Configuration : Logs**, you can configure a message type command that will allow Jeedom to notify you if the warning or danger threshold is reached.

### View tab

In this part, you will be able to configure certain widget display behaviors on the Dashboard, views, design and on mobile.

- **Widget** : Allows you to choose the widget on desktop or mobile (note that you need the widget plugin and that you can also do it from it).
- **Jeedom** : Check to make the command visible.
- **Show name** : Check to make the name of the command visible, depending on the context.
- **Display name and icon** : Check to make the icon visible in addition to the command name.
- **Wrapped line before widget** : Select **before the widget** or **after the widget** to add a line break before or after the widget (to force for example a column display of the different commands of the equipment instead of default lines)

Below, there are optional display parameters that can be passed to the widget. These parameters depend on the widget in question, so you have to look at its file on the Market to know them.

> **Tip**
>
> Do not forget to save after any modification.
