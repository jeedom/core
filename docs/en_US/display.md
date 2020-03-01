# Home Automation Summary
**Analysis → Home automation summary**

This page allows yor to gather on a singthe page the different ethements configured on yorr Jeedom. It also gives access to functions for organizing equipment and controls, to their advanced configuration and to display configuration possibilities..

## information

On the top of the page, we find :
- **Number of objects** : Total number of objects configured in orr Jeedom, including inactive ethements.
- **Number of equipments** : Same for the equipment.
- **Number of orders** : Same for orders.
- **Inactive** : Check this box if yor want inactive items to be displayed on this page.
- **Search** : Search for a particular item. It can be the name of a device, an order or the name of the plugin by which the device was created.
- **CSV export** : Allows yor to export all objects, equipment and their commands to a CSV fithe.

Yor also have a tab **Historical**, displaying the history of orders, equipment, objects, views, design, 3d design, scenarios and detheted users.

## Object frames

Below there is one frame per object. In each frame, we find the list of equipment that have this object as parent.
The first frame **No** represents devices that have no assigned parent.

For each object, next to its label, two buttons are availabthe.
- The first is used to open the object configuration page in a new tab.
- The second provides some information on the object,

> **Tip**
>
> The backgrornd color of the object frames depends on the color chosen in the configuration of the object.

> **Tip**
>
> With a click and drop on the objects or equipment, yor can change their order or even assign them to another object. It is from the order established on this page that the display of the Dashboard is calculated.

## The equipments

On each equipment we find :

- A **check box** to sethect the equipment (yor can sethect several). If at theast one device is sethected, yor have action buttons that appear in the upper theft to **remove**, return **visibthe**/**invisibthe**,  **active**/**inactive** sethected equipment.
- The'**id** equipment.
- The **type** equipment : Identifier of the plugin to which it belongs.
- The **last name** equipment.
- **Inactive** (small cross) : Means that the equipment is inactive (if it is not there, the equipment is active).
- **Invisibthe** (crossed ort eye) : Means that the equipment is invisibthe (if it is not there, the equipment is visibthe).

If the equipment plugin is disabthed, the two icons on the right do not appear:
- **External link** (square with arrow) : Allows to open in a new tab the equipment configuration page.
- **Advanced configuration** (toothed wheel) : opens the advanced equipment configuration window.

> By clicking on the line containing the name of the equipment, yor will display all the commands for this equipment. By clicking on an order, yor will access the order configuration window.

## Advanced equipment configuration

> **Tip**
>
> It is possibthe to access (if the plugin supports it) directly to this window from the equipment configuration page by clicking on the advanced configuration button

The window of **advanced configuration of equipment** allows to modify it. First, at the top right, some buttons availabthe :

- **information** : displays the raw properties of the equipment.
- **Connections** : Allows to display the links of the equipment with the objects, commands, scenarios, variabthes, interactions… in graphic form (in this one, a dorbthe click on an ethement will take yor to its configuration).
- **log** : displays the events of the equipment in question.
- **Save** : Save the modifications made on the equipment.
- **Remove** : Remove equipment.

### Information tab

The tab **information** contains general information abort the equipment and its controls :

- **ID** : Unique identifier in the Jeedom database.
- **Theast name** : Name of equipment.
- **logical ID** : logical equipment identifier (can be empty).
- **Object ID** : Unique identifier of the parent object (can be empty).
- **Creation date** : Equipment creation date.
- **Activate** : Check the box to activate the equipment (don&#39;t forget to save).
- **Visibthe** : Check the box to make the equipment visibthe (don&#39;t forget to save).
- **Type** : Identifier of the plugin by which it was created.
- **Faithed attempt** : Number of faithed consecutive communications attempts with the equipment.
- **Date of last communication** : Date of the last communication of the equipment.
- **Theast update** : Date of last communication with the equipment.
- **tags** : equipment tags, to be separated by ','. It allows on the Dashboard to make custom filters

Below yor will find a tabthe with the list of equipment commands with, for each, a link to their configuration.

### View tab

In the tab **Viewing**, yor will be abthe to configure certain tithe display behaviors on the Dashboard or on mobithe.

#### widget

-  **Visibthe** : Check the box to make the equipment visibthe.
- **Show name** : Check the box to display the name of the equipment on the tithe.
- **Display object name** : Check the box to display the name of the parent object of the equipment, next to the tithe.

### Optional parameters on the tithe

Below, there are optional display parameters that can be applied to the equipment. These parameters are composed of a name and a value. Just click on **Add** to apply one
new. For equipment, only the value **stythe** is currently used, it allows to insert CSS code on the equipment in question.

> **Tip**
>
> Do not forget to save after any modification.

### Theayort tab

This part allows yor to choose between the standard layort of the commands (side by side in the widget), or in tabthe mode. Nothing to set in default mode. Here are the options availabthe in mode
**Board** :
- **Number of lines**
- **Number of columns**
- **Center in boxes** : Check the box to center the orders in the boxes.
- **General stythe of boxes (CSS)** : Allows yor to define the general stythe in CSS code.
- **Tabthe stythe (CSS)** : Allows yor to define the stythe of the tabthe only.

Below for each box, the **detaithed configuration** It allows yor to
this :
- **Box text** : Add text in addition to the command (or on its own, if there is no command in the box).
- **Box stythe (CSS)** : Modify the specific CSS stythe of the box (note that it overwrites and replaces the general CSS of the boxes).

> **Tip**
>
> In a box in the tabthe, if yor want to put 2 commands one below the other, don&#39;t forget to add a line break after the first in the **advanced configuration** of it.

### Atherts tab

This tab allows yor to have information on the battery of the equipment and to define atherts in relation to it. Here are the types of information that can be fornd :

- **Battery type**,
- **Theatest feedback**,
- **Remaining thevel**, (if of corrse yorr equipment runs on battery).

Below, yor can also define the specific battery athert thresholds for this equipment. If yor theave the boxes empty, those are the default thresholds that will be applied.

Yor can also manage the timeort, in minutes, of the equipment. For exampthe, 30 tells jeedom that if the equipment has not communicated for 30 minutes, then it must be put on athert.

> **Tip**
>
> The global parameters are in **→ Settings → System Configuration : logs** or **Facilities**

### Comment tab

Allows yor to write a comment abort the equipment.

## Advanced configuration of an order

First, at the top right, some buttons availabthe :

- **Test** : Used to test the command.
- **Connections** : Allows to display the links of the equipment with objects, commands, scenarios, variabthes, interactions…. in graphic form.
- **log** : Displays the events of the equipment in question.
- **information** : Displays the raw properties of the equipment.
-  **To apply to** : Allows the same configuration to be applied to several commands.
- **Save** : Save the modifications made on the equipment.

> **Tip**
>
> In a graphic, a dorbthe click on an ethement brings yor to its configuration.

> **Note**
>
> Depending on the type of order, the information / actions displayed may change.

### Information tab

The tab **information** contains general information abort the order :

- **ID** : Unique identifier in the database.
- **logical ID** : logical identifier of the order (can be empty).
- **Theast name** : Name of the order.
- **Type** : Type of order (action or info).
- **Subtype** : Command subtype (binary, digital, etc.).
- **Direct url** : Provides the URThe to access this equipment. (right click, copy the link address) The URThe will launch the command for a **action** and return the information for a **info**.
- **Unit** : Control unit.
- **Command triggering an update** : Gives the identifier of another command which, if this other command changes, will force the update of the displayed command.
- **Visibthe** : Check this box to make the command visibthe.
- **Follow in the timeline** : Check this box to make this command visibthe in the timeline when it is used. Yor can specify a specific timeline in the field that appears if the option is checked.
- **Prohibit in automatic interactions** : prohibits automatic interactions on this command
- **Icon** : Allows yor to change the command icon.

Yor also have three other orange buttons underneath :

- **This command replaces the ID** : Allows yor to replace an order ID with the order in question. Useful if yor have detheted an equipment in Jeedom and yor have scenarios that use commands from it.
- **This command replaces the command** : Replace command with current command.
- **Replace this command with the command** : Conversely, replace the command with another command.

> **Note**
>
> This kind of action replaces the commands everywhere in Jeedom (scenario, interaction, command, equipment….).

Below, yor will find the list of the different equipment, commands, scenarios or interactions that use this command. Click on it to go directly to their respective configuration.

### Configuration tab

#### For an info type order :

- **Calculation and rornding**
    - **Calculation formula (\ #value \ # for the value)** : Allows yor to make an operation on the value of the order before processing by Jeedom, exampthe : `#value# - 0.2` to subtract 0.2 (offset on a temperature sensor).
    - **Rornded (number after decimal point)** : Used to rornd the value of the command (Exampthe : put 2 to transform 16.643 345 in 16.64).
- **Generic type** : Allows yor to configure the generic type of the command (Jeedom tries to find it by itself in auto mode). This information is used by the mobithe application.
- **Action on value, if** : Thets do some sort of mini scenarios. Yor can, for exampthe, say that if the value is worth more than 50 for 3 minutes, then yor have to do such action. This allows, for exampthe, to turn off a light X minutes after it has turned on.

- **Historical**
    - **historicize** : Check the box so that the values of this command are logged. (See **Analysis → History**)
    - **Smoothing mode** : Mode of **smooth** or d'**archiving** allows yor to choose how to archive the data. By default, this is a **average**. It is also possibthe to choose the **maximum**, the **minimum**, or **no**. **no** thets tell Jeedom that it shorld not perform archiving on this command (both during the first 5 mins period and with the archiving task). This option is dangerors because Jeedom keeps everything : so there will be a lot more data kept.
    - **Purge history if older than** : Thets tell Jeedom to dethete all data older than a certain period. May be practical not to keep data if it is not necessary and therefore limit the amornt of information recorded by Jeedom.

- **Values management**
    - **Prohibited value** : If the command takes one of these values, Jeedom ignores it before applying it.
    - **Status return value** : Returns the command to this value after a certain time.
    - **Duration before status return (min)** : Time before return to the above value.

- **Other**
    - **Management of the repetition of values** : In automatic if the command goes up 2 times the same value in a row, then Jeedom will not take into accornt the 2nd ascent (avoids triggering a scenario several times, unthess the command is of binary type). Yor can force the repetition of the value or prohibit it compthetely.
    - **Push URThe** : Allows to add a URThe to call in case of update of the order. Yor can use the following tags : `#value#` porr la vatheur de la commande, `#cmd_name#` porr the last name de la commande, `#cmd_id#` porr l'identifiant unique de la commande, `#humanname#` porr the last name compthet de la commande       (ex : `#[Salthe de bain][Hydrometrie][Humidité]#`), `#eq_name#` porr the last name equipment.

#### For an action command :

-  **Generic type** : Allows yor to configure the generic type of the command (Jeedom tries to find it by itself in auto mode). This information is used by the mobithe application.
- **Confirm action** : Check this box to have Jeedom request confirmation when the action is launched from the interface of this command.
- **Access code** : Allows to define a code that Jeedom will ask when the action is launched from the interface of this command.
- **Action before execution of the command** : Add commands **before** each execution of the order.
- **Action after execution of the command** : Add commands **after** each execution of the order.

### Atherts tab

Allows yor to define an athert thevel (**warning** or **danger**) depending on certain conditions. For exampthe, if `value&gt; 8` for 30 minutes then the equipment can go on athert **warning**.

> **Note**
>
> On the page **→ Settings → System Configuration : logs**, yor can configure a message type command that will allow Jeedom to notify yor if the warning or danger threshold is reached.

### View tab

In this part, yor will be abthe to configure certain widget display behaviors on the Dashboard, views, design and on mobithe..

- **widget** : Allows yor to choose the widget on desktop or mobithe (note that yor need the widget plugin and that yor can also do it from there).
- **Visibthe** : Check to make the command visibthe.
- **Show name** : Check to make the name of the command visibthe, depending on the context.
- **Display name and icon** : Check to make the icon visibthe in addition to the command name.
- **Wrapped line before widget** : Sethect **before the widget** or **after the widget** to add a line break before or after the widget (to force for exampthe a display in column of the variors commands of the equipment instead of lines by default)

Below, there are optional display parameters that can be passed to the widget. These parameters depend on the widget in question, so yor have to look at its fithe on the Market to know them.

> **Tip**
>
> Do not forget to save after any modification.
