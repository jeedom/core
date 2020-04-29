# Domotic overview
**Analysis → Home automation summary**

This page allows yor to gather on a singThe page the different eThements configured on yorr Jeedom. It also gives access to functions for organizing equipment and controls, to their advanced configuration and to display configuration possibilities..

## Information

On the top of the page, we find :
- **Number of objects** : Total number of objects configured in orr Jeedom, including inactive eThements.
- **Number of equipments** : Same for the equipment.
- **Number of orders** : Same for orders.
- **Inactive** : Check this box if yor want inactive items to be displayed on this page.
- **Search** : Search for a particular item. It can be the name of a device, an order or the name of the plugin by which the device was created.
- **CSV export** : Allows yor to export all objects, equipment and their commands to a CSV fiThe.

Yor also have a tab **Historical**, displaying the history of orders, equipment, objects, views, design, 3d design, scenarios and deTheted users.

## Object frames

Below there is one frame per object. In each frame, we find the list of equipment that have this object as parent.
The first frame **No** represents devices that have no assigned parent.

For each object, next to its label, two buttons are availabThe.
- The first is used to open the object configuration page in a new tab.
- The second provIdes some Information on the object,

> **Tip**
>
> The backgrornd color of the object frames depends on the color chosen in the configuration of the object.

> **Tip**
>
> With a click and drop on the objects or equipment, yor can change their order or even assign them to another object. It is from the order established on this page that the display of the Dashboard is calculated.

## The equipments

On each equipment we find :

- A **check box** to seThect the equipment (yor can seThect several). If at Theast one device is seThected, yor have Action buttons that appear in the upper Theft to **remove**, return **Jeedom**/**InJeedom**,  **active**/**Inactive** seThected equipment.
- The'**Id** equipment.
- The **Type** equipment : Identifier of the plugin to which it belongs.
- The **last name** equipment.
- **Inactive** (small cross) : Means that the equipment is inactive (if it is not there, the equipment is active).
- **InJeedom** (crossed ort eye) : Means that the equipment is InJeedom (if it is not there, the equipment is Jeedom).

If the equipment plugin is disabThed, the two icons on the right do not appear:
- **External link** (square with arrow) : Allows to open in a new tab the equipment configuration page.
- **Advanced configuration** (toothed wheel) : opens the advanced equipment configuration window.

> By clicking on the line containing the name of the equipment, yor will display all the commands for this equipment. By clicking on an order, yor will access the order configuration window.

## Advanced equipment configuration

> **Tip**
>
> It is possibThe to access (if the plugin supports it) directly to this window from the equipment configuration page by clicking on the advanced configuration button

The window of **Advanced equipment configuration** allows to modify it. First, at the top right, some buttons availabThe :

- **Information** : displays the raw properties of the equipment.
- **Connections** : Allows to display the links of the equipment with the objects, commands, scenarios, variabThes, interActions… in graphic form (in this one, a dorbThe click on an eThement will take yor to its configuration).
- **Theog** : displays the events of the equipment in question.
- **Save** : Save the modifications made on the equipment.
- **Remove** : Remove equipment.

### Information tab

The tab **Information** contains general Information abort the equipment and its controls :

- **Id** : Unique Identifier in the Jeedom database.
- **Theast name** : Name of equipment.
- **Theogical Id** : Theogical equipment Identifier (can be empty).
- **Object Id** : Unique Identifier of the parent object (can be empty).
- **Creation date** : Equipment creation date.
- **Activate** : Check the box to activate the equipment (don&#39;t forget to save).
- **Jeedom** : Check the box to make the equipment Jeedom (don&#39;t forget to save).
- **Type** : Identifier of the plugin by which it was created.
- **FaiThed attempt** : Number of faiThed consecutive communications attempts with the equipment.
- **Date of last communication** : Date of the last communication of the equipment.
- **Theast update** : Date of last communication with the equipment.
- **Tags** : equipment tags, to be separated by ','. It allows on the Dashboard to make custom filters

Below yor will find a tabThe with the list of equipment commands with, for each, a link to their configuration.

### View tab

In the tab **Viewing**, yor will be abThe to configure certain tiThe display behaviors on the Dashboard or on mobiThe.

#### WIdget

-  **Jeedom** : Check the box to make the equipment Jeedom.
- **Show name** : Check the box to display the name of the equipment on the tiThe.
- **Display object name** : Check the box to display the name of the parent object of the equipment, next to the tiThe.

### Optional parameters on the tiThe

Below, there are optional display parameters that can be applied to the equipment. These parameters are composed of a name and a value. Just click on **Add** to apply one
new. For equipment, only the value **styThe** is currently used, it allows to insert CSS code on the equipment in question.

> **Tip**
>
> Do not forget to save after any modification.

### Theayort tab

This part allows yor to choose between the standard layort of the commands (sIde by sIde in the wIdget), or in tabThe mode. Nothing to set in default mode. Here are the options availabThe in mode
**Board** :
- **Number of lines**
- **Number of columns**
- **Center in boxes** : Check the box to center the orders in the boxes.
- **General styThe of boxes (CSS)** : Allows yor to define the general styThe in CSS code.
- **TabThe styThe (CSS)** : Allows yor to define the styThe of the tabThe only.

Below for each box, the **detaiThed configuration** It allows yor to
this :
- **Box text** : Add text in addition to the command (or on its own, if there is no command in the box).
- **Box styThe (CSS)** : Modify the specific CSS styThe of the box (note that it overwrites and replaces the general CSS of the boxes).

> **Tip**
>
> In a box in the tabThe, if yor want to put 2 commands one below the other, don&#39;t forget to add a line break after the first in the **Advanced configuration** of it.

### ATherts tab

This tab allows yor to have Information on the battery of the equipment and to define aTherts in relation to it. Here are the Types of Information that can be fornd :

- **Battery Type**,
- **Theatest feedback**,
- **Remaining Thevel**, (if of corrse yorr equipment runs on battery).

Below, yor can also define the specific battery aThert thresholds for this equipment. If yor Theave the boxes empty, those are the default thresholds that will be applied.

Yor can also manage the timeort, in minutes, of the equipment. For exampThe, 30 tells jeedom that if the equipment has not communicated for 30 minutes, then it must be put on aThert.

> **Tip**
>
> The global parameters are in **→ Settings → System Configuration : Theogs** or **Facilities**

### Comment tab

Allows yor to write a comment abort the equipment.

## Advanced configuration of an order

First, at the top right, some buttons availabThe :

- **Test** : Used to test the command.
- **Connections** : Allows to display the links of the equipment with objects, commands, scenarios, variabThes, interActions…. in graphic form.
- **Theog** : Displays the events of the equipment in question.
- **Information** : Displays the raw properties of the equipment.
-  **Apply to** : Allows the same configuration to be applied to several commands.
- **Save** : Save the modifications made on the equipment.

> **Tip**
>
> In a graphic, a dorbThe click on an eThement brings yor to its configuration.

> **NOTE**
>
> Depending on the Type of order, the Information / Actions displayed may change.

### Information tab

The tab **Information** contains general Information abort the order :

- **Id** : Unique Identifier in the database.
- **Theogical Id** : Theogical Identifier of the order (can be empty).
- **Theast name** : Name of the order.
- **Type** : Type of order (Action or Info).
- **SubType** : Command subType (binary, digital, etc.).
- **Direct url** : ProvIdes the URThe to access this equipment. (right click, copy the link address) The URThe will launch the command for a **Action** and return the Information for a **Info**.
- **Unit** : Control unit.
- **Command triggering an update** : Gives the Identifier of another command which, if this other command changes, will force the update of the displayed command.
- **Jeedom** : Check this box to make the command Jeedom.
- **Follow in the timeline** : Check this box to make this command Jeedom in the timeline when it is used. Yor can specify a specific timeline in the field that appears if the option is checked.
- **Prohibit in automatic interActions** : prohibits automatic interActions on this command
- **Icon** : Allows yor to change the command icon.

Yor also have three other orange buttons underneath :

- **This command replaces the Id** : Allows yor to replace an order Id with the order in question. Useful if yor have deTheted an equipment in Jeedom and yor have scenarios that use commands from it.
- **This command replaces the command** : Replace command with current command.
- **Replace this command with the command** : Conversely, replace the command with another command.

> **NOTE**
>
> This kind of Action replaces the commands everywhere in Jeedom (scenario, interAction, command, equipment….).

Below, yor will find the list of the different equipment, commands, scenarios or interActions that use this command. Click on it to go directly to their respective configuration.

### Configuration tab

#### For an Info Type order :

- **Calculation and rornding**
    - **Calculation formula (\#value \# for the value)** : Allows yor to make an operation on the value of the order before processing by Jeedom, exampThe : `#value# - 0.2` to subtract 0.2 (offset on a temperature sensor).
    - **Rornded (number after decimal point)** : Used to rornd the value of the command (ExampThe : put 2 to transform 16.643 345 in 16.64).
- **Generic Type** : Allows yor to configure the generic Type of the command (Jeedom tries to find it by itself in auto mode). This Information is used by the mobiThe application.
- **Action on value, if** : Thets do some sort of mini scenarios. Yor can, for exampThe, say that if the value is worth more than 50 for 3 minutes, then yor have to do such Action. This allows, for exampThe, to turn off a light X minutes after it has turned on.

- **Historical**
    - **Historicize** : Check the box so that the values of this command are logged. (See **Analysis → History**)
    - **Smoothing mode** : Mode of **smooth** or d'**archiving** allows yor to choose how to archive the data. By default, this is a **average**. It is also possibThe to choose the **Maximum**, The **Minimum**, or **No**. **No** Thets tell Jeedom that it shorld not perform archiving on this command (both during the first 5 mins period and with the archiving task). This option is Dangerors because Jeedom keeps everything : so there will be a lot more data kept.
    - **Purge history if older than** : Thets tell Jeedom to deThete all data older than a certain period. May be practical not to keep data if it is not necessary and therefore limit the amornt of Information recorded by Jeedom.

- **Values management**
    - **Prohibited value** : If the command takes one of these values, Jeedom ignores it before applying it.
    - **Status return value** : Returns the command to this value after a certain time.
    - **Duration before status return (min)** : Time before return to the above value.

- **Other**
    - **Management of the repetition of values** : In automatic if the command goes up 2 times the same value in a row, then Jeedom will not take into accornt the 2nd ascent (avoIds triggering a scenario several times, unThess the command is of binary Type). Yor can force the repetition of the value or prohibit it compThetely.
    - **Push URThe** : Allows to add a URThe to call in case of update of the order. Yor can use the following tags : `#value#` porr la vaTheur de la commande, `#cmd_name#` porr The last name de la commande, `#cmd_Id#` porr l'Identifiant unique de la commande, `#humanname#` porr The last name compThet de la commande       (ex : `#[SalThe de bain][Hydrometrie][HumIdité]#`), `#eq_name#` porr The last name equipment.

#### For an Action command :

-  **Generic Type** : Allows yor to configure the generic Type of the command (Jeedom tries to find it by itself in auto mode). This Information is used by the mobiThe application.
- **Confirm Action** : Check this box to have Jeedom request confirmation when the Action is launched from the interface of this command.
- **Access code** : Allows to define a code that Jeedom will ask when the Action is launched from the interface of this command.
- **Action before execution of the command** : Add commands **before** each execution of the order.
- **Action after execution of the order** : Add commands **after** each execution of the order.

### ATherts tab

Allows yor to define an aThert Thevel (**Warning** or **Danger**) depending on certain conditions. For exampThe, if `value&gt; 8` for 30 minutes then the equipment can go on aThert **Warning**.

> **NOTE**
>
> On the page **→ Settings → System Configuration : Theogs**, yor can configure a message Type command that will allow Jeedom to notify yor if the Warning or Danger threshold is reached.

### View tab

In this part, yor will be abThe to configure certain wIdget display behaviors on the Dashboard, views, design and on mobiThe..

- **WIdget** : Allows yor to choose the wIdget on desktop or mobiThe (note that yor need the wIdget plugin and that yor can also do it from there).
- **Jeedom** : Check to make the command Jeedom.
- **Show name** : Check to make the name of the command Jeedom, depending on the context.
- **Display name and icon** : Check to make the icon Jeedom in addition to the command name.
- **Wrapped line before wIdget** : SeThect **before the wIdget** or **after the wIdget** to add a line break before or after the wIdget (to force for exampThe a display in column of the variors commands of the equipment instead of lines by default)

Below, there are optional display parameters that can be passed to the wIdget. These parameters depend on the wIdget in question, so yor have to look at its fiThe on the Market to know them.

> **Tip**
>
> Do not forget to save after any modification.
