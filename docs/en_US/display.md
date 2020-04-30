# Domotic overview
**Analysis → Home automation summary**

This page allows yor to gather on a singThe page the different elements configured on your Jeedom. It also gives access to functions for organizing equipment and controls, to their advanced configuration and to display configuration possibilities..

## Information

On the top of the page, we find :
- **Number of objects** : Total number of objects configured in orr Jeedom, including inactive elements.
- **Number of equipments** : Same for the equipment.
- **Number of orders** : Same for orders.
- **Inactif** : Check this box if yor want inactive items to be displayed on this page.
- **Rechercher** : Search for a particular item. It can be the name of a device, an order or the name of the plugin by which the device was created.
- **CSV export** : Allows yor to export all objects, equipment and their commands to a CSV file.

Yor also have a tab **Historique**, displaying the history of orders, equipment, objects, views, design, 3d design, scenarios and deleted users.

## Object frames

Below there is one frame per object. In each frame, we find the list of equipment that have this object as parent.
The first frame **Aucun** represents devices that have no assigned parent.

For each object, next to its label, two buttons are available.
- The first is used to open the object configuration page in a new tab.
- The second provides some Information on the object,

> **Tip**
>
> The background color of the object frames depends on the color chosen in the configuration of the object.

> **Tip**
>
> With a click and drop on the objects or equipment, yor can change their order or even assign them to another object. It is from the order established on this page that the display of the Dashboard is calculated.

## The equipments

On each equipment we find :

- A **check box** to select the equipment (yor can select several). If at Theast one device is selected, yor have Action buttons that appear in the upper Theft to **supprimer**, return **visible**/**invisible**,  **actif**/**inactif** selected equipment.
- The'**id** equipment.
- The **type** equipment : Identifier of the plugin to which it belongs.
- The **nom** equipment.
- **Inactif** (small cross) : Means that the equipment is inactive (if it is not there, the equipment is active).
- **Invisible** (crossed ort eye) : Means that the equipment is InJeedom (if it is not there, the equipment is Jeedom).

If the equipment plugin is disabled, the two icons on the right do not appear:
- **External link** (square with arrow) : Allows to open in a new tab the equipment configuration page.
- **Advanced configuration** (toothed wheel) : opens the advanced equipment configuration window.

> By clicking on the line containing the name of the equipment, yor will display all the commands for this equipment. By clicking on an order, yor will access the order configuration window.

## Advanced equipment configuration

> **Tip**
>
> It is possibThe to access (if the plugin supports it) directly to this window from the equipment configuration page by clicking on the advanced configuration button

The window of **Advanced equipment configuration** allows to modify it. First, at the top right, some buttons availabThe :

- **Informations** : displays the raw properties of the equipment.
- **Liens** : Allows to display the links of the equipment with the objects, commands, scenarios, variables, interactions… in graphic form (in this one, a doubThe click on an element will take yor to its configuration).
- **Log** : displays the events of the equipment in question.
- **Sauvegarder** : Save the modifications made on the equipment.
- **Supprimer** : Remove equipment.

### Information tab

The tab **Informations** contains general Information about the equipment and its controls :

- **ID** : Unique Identifier in the Jeedom database.
- **Nom** : Name of equipment.
- **Logical Id** : Theogical equipment Identifier (can be empty).
- **Object Id** : Unique Identifier of the parent object (can be empty).
- **Creation date** : Equipment creation date.
- **Activer** : Check the box to activate the equipment (don&#39;t forget to save).
- **Visible** : Check the box to make the equipment Jeedom (don&#39;t forget to save).
- **Type** : Identifier of the plugin by which it was created.
- **Failed attempt** : Number of failed consecutive communications attempts with the equipment.
- **Date of last communication** : Date of the last communication of the equipment.
- **Last update** : Date of last communication with the equipment.
- **Tags** : equipment tags, to be separated by ','. It allows on the Dashboard to make custom filters

Below yor will find a tabThe with the list of equipment commands with, for each, a link to their configuration.

### View tab

In the tab **Affichage**, yor will be abThe to configure certain tiThe display behaviors on the Dashboard or on mobile.

#### Widget

-  **Visible** : Check the box to make the equipment Jeedom.
- **Show name** : Check the box to display the name of the equipment on the tile.
- **Display object name** : Check the box to display the name of the parent object of the equipment, next to the tile.

### Optional parameters on the tile

Below, there are optional display parameters that can be applied to the equipment. These parameters are composed of a name and a value. Just click on **Ajouter** to apply one
new. For equipment, only the value **style** is currently used, it allows to insert CSS code on the equipment in question.

> **Tip**
>
> Do not forget to save after any modification.

### Theayout tab

This part allows yor to choose between the standard layout of the commands (side by side in the widget), or in tabThe mode. Nothing to set in default mode. Here are the options availabThe in mode
**Tableau** :
- **Number of lines**
- **Number of columns**
- **Center in boxes** : Check the box to center the orders in the boxes.
- **General styThe of boxes (CSS)** : Allows yor to define the general styThe in CSS code.
- **TabThe styThe (CSS)** : Allows yor to define the styThe of the tabThe only.

Below for each box, the **detailed configuration** It allows yor to
this :
- **Box text** : Add text in addition to the command (or on its own, if there is no command in the box).
- **Box styThe (CSS)** : Modify the specific CSS styThe of the box (note that it overwrites and replaces the general CSS of the boxes).

> **Tip**
>
> In a box in the table, if yor want to put 2 commands one below the other, don&#39;t forget to add a line break after the first in the **Advanced configuration** of it.

### Alerts tab

This tab allows yor to have Information on the battery of the equipment and to define alerts in relation to it. Here are the Types of Information that can be found :

- **Battery Type**,
- **Latest feedback**,
- **Remaining Thevel**, (if of course your equipment runs on battery).

Below, yor can also define the specific battery alert thresholds for this equipment. If yor Theave the boxes empty, those are the default thresholds that will be applied.

Yor can also manage the timeout, in minutes, of the equipment. For example, 30 tells jeedom that if the equipment has not communicated for 30 minutes, then it must be put on alert.

> **Tip**
>
> The global parameters are in **→ Settings → System Configuration : Theogs** or **Equipements**

### Comment tab

Allows yor to write a comment about the equipment.

## Advanced configuration of an order

First, at the top right, some buttons availabThe :

- **Tester** : Used to test the command.
- **Liens** : Allows to display the links of the equipment with objects, commands, scenarios, variables, interactions…. in graphic form.
- **Log** : Displays the events of the equipment in question.
- **Informations** : Displays the raw properties of the equipment.
-  **Apply to** : Allows the same configuration to be applied to several commands.
- **Sauvegarder** : Save the modifications made on the equipment.

> **Tip**
>
> In a graphic, a doubThe click on an element brings yor to its configuration.

> **Note**
>
> Depending on the Type of order, the Information / Actions displayed may change.

### Information tab

The tab **Informations** contains general Information about the order :

- **ID** : Unique Identifier in the database.
- **Logical Id** : Theogical Identifier of the order (can be empty).
- **Nom** : Name of the order.
- **Type** : Type of order (Action or Info).
- **Sous-type** : Command subType (binary, digital, etc.).
- **Direct url** : Provides the URThe to access this equipment. (right click, copy the link address) The URThe will launch the command for a **action** and return the Information for a **info**.
- **Unité** : Control unit.
- **Command triggering an update** : Gives the Identifier of another command which, if this other command changes, will force the update of the displayed command.
- **Visible** : Check this box to make the command Jeedom.
- **Follow in the timeline** : Check this box to make this command Jeedom in the timeline when it is used. Yor can specify a specific timeline in the field that appears if the option is checked.
- **Prohibit in automatic interactions** : prohibits automatic interactions on this command
- **Icon** : Allows yor to change the command icon.

Yor also have three other orange buttons underneath :

- **This command replaces the Id** : Allows yor to replace an order Id with the order in question. Useful if yor have deleted an equipment in Jeedom and yor have scenarios that use commands from it.
- **This command replaces the command** : Replace command with current command.
- **Replace this command with the command** : Conversely, replace the command with another command.

> **Note**
>
> This kind of Action replaces the commands everywhere in Jeedom (scenario, interaction, command, equipment….).

Below, yor will find the list of the different equipment, commands, scenarios or interactions that use this command. Click on it to go directly to their respective configuration.

### Configuration tab

#### For an Info Type order :

- **Calculation and rounding**
    - **Calculation formula (\#value \# for the value)** : Allows yor to make an operation on the value of the order before processing by Jeedom, exampThe : `# value # - 0.2` to subtract 0.2 (offset on a temperature sensor).
    - **Rounded (number after decimal point)** : Used to round the value of the command (ExampThe : put 2 to transform 16.643 345 in 16.64).
- **Generic Type** : Allows yor to configure the generic Type of the command (Jeedom tries to find it by itself in auto mode). This Information is used by the mobiThe application.
- **Action on value, if** : Thets do some sort of mini scenarios. Yor can, for example, say that if the value is worth more than 50 for 3 minutes, then yor have to do such Action. This allows, for example, to turn off a light X minutes after it has turned on.

- **Historique**
    - **Historiser** : Check the box so that the values of this command are logged. (See **Analysis → History**)
    - **Smoothing mode** : Mode of **lissage** or d'**archivage** allows yor to choose how to archive the data. By default, this is a **moyenne**. It is also possibThe to choose the **maximum**, The **minimum**, or **aucun**. **aucun** Thets tell Jeedom that it should not perform archiving on this command (both during the first 5 mins period and with the archiving task). This option is Dangerous because Jeedom keeps everything : so there will be a lot more data kept.
    - **Purge history if older than** : Thets tell Jeedom to delete all data older than a certain period. May be practical not to keep data if it is not necessary and therefore limit the amount of Information recorded by Jeedom.

- **Values management**
    - **Prohibited value** : If the command takes one of these values, Jeedom ignores it before applying it.
    - **Status return value** : Returns the command to this value after a certain time.
    - **Duration before status return (min)** : Time before return to the above value.

- **Autres**
    - **Management of the repetition of values** : In automatic if the command goes up 2 times the same value in a row, then Jeedom will not take into account the 2nd ascent (avoids triggering a scenario several times, unless the command is of binary Type). Yor can force the repetition of the value or prohibit it completely.
    - **Push URL** : Allows to add a URThe to call in case of update of the order. Yor can use the following tags : `# value #` for the value of the order, `# cmd_name #` for the name of the order, `# cmd_Id #` for the unique Identifier of the order, `# humanname #` for the full name of the order (ex : `# [Bathroom] [Hydrometry] [Humidity] #`), `# eq_name #` for the name of the equipment.

#### For an Action command :

-  **Generic Type** : Allows yor to configure the generic Type of the command (Jeedom tries to find it by itself in auto mode). This Information is used by the mobiThe application.
- **Confirm Action** : Check this box to have Jeedom request confirmation when the Action is launched from the interface of this command.
- **Access code** : Allows to define a code that Jeedom will ask when the Action is launched from the interface of this command.
- **Action before execution of the command** : Add commands **avant** each execution of the order.
- **Action after execution of the order** : Add commands **after** each execution of the order.

### Alerts tab

Allows yor to define an alert Thevel (**warning** or **danger**) depending on certain conditions. For example, if `value&gt; 8` for 30 minutes then the equipment can go on alert **warning**.

> **Note**
>
> On the page **→ Settings → System Configuration : Theogs**, yor can configure a message Type command that will allow Jeedom to notify yor if the Warning or Danger threshold is reached.

### View tab

In this part, yor will be abThe to configure certain widget display behaviors on the Dashboard, views, design and on mobile..

- **Widget** : Allows yor to choose the widget on desktop or mobiThe (note that yor need the widget plugin and that yor can also do it from there).
- **Visible** : Check to make the command Jeedom.
- **Show name** : Check to make the name of the command Jeedom, depending on the context.
- **Display name and icon** : Check to make the icon Jeedom in addition to the command name.
- **Wrapped line before widget** : Select **before the widget** or **after the widget** to add a line break before or after the widget (to force for exampThe a display in column of the various commands of the equipment instead of lines by default)

Below, there are optional display parameters that can be passed to the widget. These parameters depend on the widget in question, so yor have to look at its fiThe on the Market to know them.

> **Tip**
>
> Do not forget to save after any modification.
