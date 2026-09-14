# Summary: Home Automation
**Analysis → Home Automation Summary**

This page brings together all the different elements configured on your Jeedom on a single page. It also provides access to features for organizing devices and commands, advanced configuration options, and display customization options.

{% include lightbox.html src="images/doc-display_01.jpg" data="Display" title="Display" imgstyle="width:450px;display: block;margin: 0 auto;" %}

## Information

At the top of the page, you'll find:
- **Number of objects**: Total number of objects configured in our Jeedom, including inactive ones.
- **Number of devices**: Same as for devices.
- **Number of commands**: Same as for commands.
- **Inactive**: Check this box if you want inactive items to be displayed on this page.
- **Search**: Allows you to search for a specific item. This could be the name of a device, a command, or the name of the plugin used to create the device.
- **Export CSV**: Allows you to export all objects, devices, and their commands to a CSV file.

There is also a **History** tab, which displays the history of deleted commands, devices, objects, views, designs, 3D designs, scenarios, and users.

## Object frameworks

Below, there is one frame per object. Each frame contains a list of devices that are associated with that object.
The first box, **None**, represents devices that do not have a parent assigned to them.

For each object, there are two buttons next to its label.
- The first one opens the object's configuration page in a new tab.
- The second provides some information about the object,

> **Tip**
>
> The background color of object frames depends on the color selected in the object's settings.

> **Tip**
>
> By dragging and dropping objects or devices, you can change their order or even assign them to another object. The Dashboard display is calculated based on the order established on this page.

## Equipment

Each device features:

- A **checkbox** to select devices (you can select multiple devices). If at least one device is selected, action buttons appear in the upper-left corner to **delete**, make the selected devices **visible**/**invisible**, or set them to **active**/**inactive**.
- The **id** of the device.
- The **type** of device: The identifier of the plugin to which it belongs.
- The **name** of the device.
- **Inactive** (small cross): Indicates that the device is inactive (if the cross is not present, the device is active).
- **Invisible** (crossed-out eye): Indicates that the device is invisible (if this symbol is not present, the device is visible).

If the device plugin is disabled, the two icons on the right will not appear:
- **External link** (square with an arrow): Opens the device's configuration page in a new tab.
- **Advanced Settings** (gear icon): Opens the device's advanced settings window.

> Clicking on the line containing the device name will display all the commands for that device. Clicking on a command will then open the command configuration window.

## Advanced Configuration of a Device

> **Tip**
>
> If the plugin supports it, you can access this window directly from the device's configuration page by clicking the "Advanced Configuration" button.

The **advanced device settings** window allows you to modify the device's settings. First, in the upper-right corner, there are a few buttons available:

- **Information**: Displays the raw properties of the device.
- **Links**: Displays the device’s links to objects, commands, scenarios, variables, interactions, and more in a graphical format (in this view, double-clicking an item takes you to its configuration).
- **Log**: Displays events related to the device in question.
- **Save**: Saves the changes made to the device.
- **Delete**: Deletes the device.

### "Information" tab

The **Information** tab contains general information about the device as well as its commands:

- **ID**: Unique identifier in the Jeedom database.
- **Name**: Name of the device.
- **Logical ID**: Logical identifier for the device (may be empty).
- **Object ID**: Unique identifier of the parent object (may be empty).
- **Creation Date**: The date the device was created.
- **Enable**: Check the box to enable the device (don't forget to save).
- **Visible**: Check the box to make the device visible (don't forget to save).
- **Type**: The ID of the plugin used to create it.
- **Failed attempt**: Number of consecutive failed communication attempts with the device.
- **Date of Last Communication**: The date of the device's last communication.
- **Last Update**: Date of the last communication with the device.
- **Tags**: device tags, separated by ','s. This allows you to create custom filters on the Dashboard

Below is a table listing the device commands, each with a link to its configuration.

### Display tab

In the **Display** tab, you can configure certain display settings for the tile on the Dashboard or on mobile devices.

#### Widget

-  **Visible**: Check the box to make the device visible.
- **Show Name**: Check the box to display the device name on the tile.
- **Show object name**: Check the box to display the name of the device's parent object next to the tile.

### Optional settings on the tile

Below that, you’ll find optional display settings that you can apply to the device. These settings consist of a name and a value. Simply click **Add** to apply one.
New. For devices, only the **style** value is currently used; it allows you to insert CSS code for the device in question.

> **Tip**
>
> Don't forget to save after making any changes.

### "Layout" tab

This section lets you choose between the standard layout for commands (side-by-side in the widget) or grid mode. There are no settings to adjust in default mode. Here are the options available in grid mode:
**Table**:
- **Number of lines**
- **Number of columns**
- **Center in boxes**: Check the box to center the commands within the boxes.
- **General Box Style (CSS)**: Allows you to define the general style using CSS code.
- **Table style (CSS)**: Allows you to define the table's style only.

Below each box, the **detailed configuration** allows you to
this:
- **Text in the box**: Add text in addition to the command (or on its own, if there is no command in the box).
- **Box Style (CSS)**: Modify the specific CSS style of the box (note that this overrides and replaces the general CSS for boxes).

> **Tip**
>
> If you want to place two commands one below the other in a table cell, don’t forget to add a line break after the first one in its **advanced settings**.

### Alerts tab

This tab allows you to view information about the device's battery and set up alerts related to it. Here are the types of information you can find:

- **Battery type**,
- **Latest update**,
- **Remaining battery level** (assuming, of course, that your device is battery-powered).

Below, you can also set specific battery alert thresholds for this device. If you leave the fields blank, the default thresholds will be applied.

You can also set the device’s timeout in minutes. For example, entering “30” tells Jeedom that if the device hasn’t communicated for 30 minutes, it should trigger an alert.

> **Tip**
>
> Global settings can be found under **Settings→System→Configuration: Logs** or **Devices**

### "Comments" tab

Allows you to write a comment about the device.

## Advanced Configuration of a Command

First, in the upper right corner, there are a few buttons available:

- **Test**: Allows you to test the command.
- **Links**: Displays the links between devices and objects, commands, scenarios, variables, interactions, etc., in graphical form.
- **Log**: Displays events for the device in question.
- **Information**: Displays the raw properties of the device.
-  **Apply to**: Allows you to apply the same configuration to multiple commands.
- **Save**: Saves the changes made to the device.

> **Tip**
>
> In a diagram, double-clicking an element takes you to its configuration.

> **Note**
>
> Depending on the type of command, the information or actions displayed may vary.

### "Information" tab

The **Information** tab contains general information about the command:

- **ID**: Unique identifier in the database.
- **Logical ID**: Logical identifier of the command (may be empty).
- **Name**: Name of the command.
- **Type**: Type of command (action or information).
- **Subtype**: Subtype of the command (binary, numeric, etc.).
- **Direct URL**: Provides the URL to access this device. (Right-click, copy link address.) The URL will trigger a command for an **action** and return information for an **info** query.
- **Unit**: Command unit.
- **Command that triggers an update**: Specifies the ID of another command; if that command changes, it will force an update of the displayed command.
- **Visible**: Check this box to make the command visible.
- **Show in Timeline**: Check this box to make this command visible in the timeline when it is used. You can specify a particular timeline in the field that appears when this option is checked.
- **Disable automatic interactions**: Disables automatic interactions for this command
- **Icon**: Allows you to change the command’s icon.

There are also three other orange buttons below:

- **This command replaces the ID**: Allows you to replace a command ID with the command itself. Useful if you have deleted a device in Jeedom and have scenarios that use commands from that device.
- **This command replaces the command**: Replaces a command with the current command.
- **Replace this command with the command**: The opposite—replaces the command with another command.

> **Note**
>
> This type of action replaces commands throughout Jeedom (scenarios, interactions, commands, devices, etc.).

Below, you'll find a list of the various devices, commands, scenarios, and interactions that use this command. Clicking on any of them will take you directly to their respective configuration.

### Configuration tab

#### For an "info" type command:

- **Calculation and Rounding**
    - **Calculation formula (\#value\# for the value)**: Allows you to perform an operation on the command value before it is processed by Jeedom. Example: `#value# - 0.2` to subtract 0.2 (offset on a temperature sensor).
    - **Rounding (decimal place)**: Allows you to round the command value (Example: enter 2 to round 16.643345 to 16.64).
- **Generic Type**: Allows you to configure the generic type of the command (Jeedom attempts to detect this in auto mode). This information is used by the mobile app.
- **Action based on value, if**: Allows you to create mini-scenarios. For example, you can specify that if the value remains above 50 for 3 minutes, then a certain action should be performed. This allows you, for example, to turn off a light X minutes after it turns on.

- **History**
    - **Log**: Check this box to log the values for this command. (See **Analysis→History**)
    - **Smoothing Mode**: The **smoothing** or **archiving** mode lets you choose how to archive the data. By default, it uses the **average**. You can also choose the **maximum**, the **minimum**, or **none**. **None** tells Jeedom not to archive data for this command (neither during the first 5-minute period nor via the archiving task). This option is risky because Jeedom retains everything; consequently, much more data will be stored.
    - **Clear history older than**: This allows you to tell Jeedom to delete all data older than a certain period. This can be useful for avoiding the storage of unnecessary data and thus limiting the amount of information recorded by Jeedom. Please note that the purge runs at night, so you must wait until the night is over before the purge takes effect.

- **Value Management**
    - **Prohibited value**: If the command takes one of these values, Jeedom ignores it before applying it.
    - **Status Return Value**: Sets the command to return to this value after a certain amount of time.
    - **Time to return to previous state (min)**: Time until the value returns to the one specified above.

- **Other**
    - **Value Repetition Management**: By default, if a command sends the same value twice in a row, Jeedom will ignore the second value (this prevents a scenario from being triggered multiple times, unless the command is of a binary type). You can force the value to be repeated or disable repetition entirely.
    - **Push URL**: Allows you to add a URL to be called when the command is updated. You can use the following tags: `#value#` for the command value, `#cmd_name#` for the command name, `#cmd_id#` for the command's unique ID, `#humanname#` for the full name of the command (e.g., `#[Salle de bain][Hydrometrie][Humidité]#`), `#eq_name#` for the device name.

#### For an action command:

-  **Generic Type**: Allows you to configure the generic type of the command (Jeedom attempts to detect this in auto mode). This information is used by the mobile app.
- **Confirm Action**: Check this box to have Jeedom prompt for confirmation when the action is triggered from this command’s interface.
- **Access Code**: Allows you to set a code that Jeedom will request when the action is triggered from this command’s interface.
- **Action before command execution**: Allows you to add commands **before** each time the command is executed.
- **Action after command execution**: Allows you to add commands **after** each time the command is executed.

### Alerts tab

Allows you to set an alert level (**warning** or **danger**) based on certain conditions. For example, if `value > 8` for 30 minutes, the system may trigger a **warning**.

> **Note**
>
> On the **Settings→System→Configuration: Logs** page, you can configure a message-type command that will allow Jeedom to notify you if the warning or danger threshold is reached.

### Display tab

In this section, you’ll be able to configure certain display settings for the widget on the Dashboard, as well as views, design, and mobile settings.

- **Widget**: Lets you choose the widget for desktop or mobile (note that you need the widget plugin, and you can also do this from within the plugin).
- **Visible**: Check this box to make the command visible.
- **Show Name**: Check this box to display the command name, depending on the context.
- **Show Name and Icon**: Check this box to display the icon in addition to the command name.
- **Force line break before the widget**: Check **before the widget** or **after the widget** to add a line break before or after the widget (for example, to force the device's various commands to be displayed in columns instead of the default lines)

Below that, you'll find optional display settings that you can apply to the widget. These settings depend on the specific widget, so you'll need to check its listing on the Market to find out what they are.

> **Tip**
>
> Don't forget to save after making any changes.
