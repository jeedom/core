# Replace

## Why use such a tool?

![1](../images/replace1.png)

Starting with version 4.3.2, Jeedom offers a new tool <kbd>Replace</kbd> which, in the event of a problem or the need to replace physical or virtual equipment (such as a temperature sensor, a motion sensor, a volume control, a water level sensor, etc.), will ensure that all commands, information, advanced settings, and history for that equipment are transferred to the new equipment.<br>
It will also replace the ID of the old device with the new one in all scenarios, designs, virtual environments, etc., that referenced it.

In fact, if the old device is removed, the reference to its original ID number will be permanently deleted. You will then need to recreate all the commands and reintegrate them into all designs, widgets, etc., for the new module—even if it is exactly the same type as the original, or even the same module but with a different ID number.<br>
Also, before any device is removed, Jeedom will warn you of the consequences of this removal in an alert window:

![2](../images/replace2.png)

Here, removing this vibration sensor will cause:

- Removing the displays defined in the "Zone Alarms" design,
- The removal of information regarding vibration, battery level, and the date of the last communication, including historical data,
- Removing the device from the “Intruder Detection Alarm” scenario.

And once this equipment is permanently decommissioned, it will be replaced in all these entities with its former ID number, or a blank field in place of its original name:

![3](../images/replace3.png)
<br><br>

## Steps to Take Before Using This Tool

Even though the tool <kbd>Replace</kbd> will prompt you to create a backup as a precaution beforehand; it is strongly recommended that you do so before beginning this replacement procedure.<br>
Keep in mind that this tool is indeed true in terms of its power, as it will make replacements at every level—including those you hadn’t thought of or simply forgot about. Furthermore, there is no *undo* function to cancel or reverse changes.<br><br>

The next step will be to rename the old equipment. To do this, simply change its name by adding the suffix '**_old**', for example.

![4](../images/replace4.png)
<br>

Don't forget to save.
<br>

Next, you must add the new device if it is a physical device, or create the new virtual device, following the standard procedure specific to each plugin.
This device will be given its final name, and its parent object and category will be defined before it is activated.
<br>
This gives us two devices:

- Old equipment, which may no longer physically exist but is still listed in all Jeedom structures along with its history,
- And the new equipment, to which we’ll need to transfer the historical data and set it up to replace the old one.
<br>

![5](../images/replace5.png)
<br><br>

## Using the tool <kbd>Replace</kbd>

Open the tool <kbd>Replace</kbd>, in the menu <kbd>Tools</kbd>.

![6](../images/replace6.png)
<br>

In the *Object* field, select the parent object(s).

![7](../images/replace7.png)
<br>

In the options, select the desired mode (*Replace* or *Copy*) from the drop-down list, and, as needed, the following options (all of which are unchecked by default), or at least:

- Copy the configuration of the source device,
- Copy the configuration of the source command.
<br>

![8](../images/replace8.png)
<br>

Then click on <kbd>Filter</kbd>

![9](../images/replace9.png)
<br>

In the *Replacements* field, all entities related to the parent object appear:

![10](../images/replace10.png)
<br>

Check the source device (renamed to '**_old**'), i.e., the one from which you want to copy commands, information, history, etc.
Here, the source equipment will be: [Guest Room][Room_Temp_old] (767 | z2m).<br>
Click on the line to display the various associated fields.

![11](../images/replace11.png)
<br>

In the *Target* section on the right, scroll through the list and select the new device that will replace it—in our example, [Guest Room][Room Temp].

![12](../images/replace12.png)
<br>

In the drop-down lists that appear on the right, information is displayed on a blue background, and actions on an orange background (below is another example of a light fixture that includes both actions and information).

![13](../images/replace13.png)
<br>

And if there is a direct match (specifically, the same name), the various settings will be configured automatically.

![14](../images/replace14.png)
<br>

Here, everything is automatically recognized.
Otherwise, the field will be empty, and you will need to manually select the corresponding information or action from the drop-down list, if applicable.

![15](../images/replace15.png)
<br>

Click on <kbd>Replace</kbd>,

![16](../images/replace16.png)
<br>

Confirm the replacement, making sure you've created a backup beforehand (please note: you cannot undo this action!).

![17](../images/replace17.png)
<br>

In fact, the tool will prompt you to do so at this stage. However, if you exit this function to perform the backup at this point, you will also lose all the settings you have already configured, which is why it’s best to perform this backup right at the start of the process.<br><br>

After placing the command, after a brief wait, an alert pop-up will appear indicating that the process was successful.<br><br>

## Checks

Make sure that the new equipment has been properly incorporated into the designs, scenarios, widgets, virtual environments, plug-ins, etc., along with its configuration (layout, display, widget assignments, etc.) and (if applicable) the associated history.

![18](../images/replace18.png)
<br>

To ensure that no additional problems have arisen as a result of this replacement, you can use the orphan command detection feature.
Go to <kbd>Analysis</kbd>, <kbd>Equipment</kbd>, click the *Orphaned Commands* tab.

![19](../images/replace19.png)
<br>

![20](../images/replace20.png)
<br>

If everything went well, there should be no lines in this report.
 
![21](../images/replace21.png)
<br>

Otherwise, you'll need to analyze each line individually for every identified issue in order to resolve it.

![22](../images/replace22.png)
<br>

But if orphaned commands are not processed by the tool <kbd>Replace</kbd>, it is still possible to perform replacements using this feature <kbd>This command replaces the ID</kbd> which can be found here in the command configuration window:

![23](../images/replace23.png)
<br><br>

## Finalization

If everything is correct, the old device (Room_Temperature_old in the example) can then be permanently deleted. No references should appear in the warning pop-up during deletion, except for commands specific to that device.

![24](../images/replace24.png)
<br>

Here, this device is now identified only by the object it belongs to and its own commands, which is to be expected. We can therefore remove it without hesitation.<br><br>

## Conclusion

This tool is convenient, but it is just as dangerous if misused due to its multi-layered implications.<br>
Also, keep these fundamentals in mind:

- Always make a backup as a precaution, even before using the tool <kbd>Replace</kbd>,
- Once this command has been executed, it cannot be canceled or undone,
- And finally, it is strongly recommended that you at least familiarize yourself with how to use this tool.
