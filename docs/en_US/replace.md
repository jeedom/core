# Remplacer
**Tools → Replace**

This tool makes it possible to quickly replace equipment and controls, for example in the case of a change of plugin, or of a module.

Like the replacement options on the advanced configuration of a command, it replaces the commands in the scenarios and other, but also allows to transfer the properties of the equipment and the commands.

## Filtres

You can display only certain equipment for more readability, filtering by object or by plugin.

> In the case of a replacement of equipment by equipment from another plugin, select the two plugins.

## Options

> **Remark**
>
> If none of these options is checked, the replacement amounts to using the function *Replace this command with the command* in advanced configuration.

- **Copy configuration from source devices** :
For each equipment, will be copied from the source to the target:
	* The parent object
	* The property *visible*
	* The property *asset*
	* Order (Dashboard)
	* The width and height (Tile Dashboard)
	* Tile Curve Parameters
	* Optional parameters
	* The table display configuration
	* the Generic Type
	* The categories
	* Comments and tags
	* The property *timeout*
	* The configuration *autorefresh*

- **Hide source devices** : Allows to make the source equipment invisible once replaced by the target equipment.
- **Copy source command history** : Copy source command history to target command.
- **Copy configuration from source commands** :
For each command, will be copied from source to target:
	* The property *visible*
	* Order (Dashboard)
	* L'historisation
	* The Dashboard and Mobile widgets used
	* The Generic Type
	* Optional parameters
	* The configurations *jeedomPreExecCmd* and *jeedomPostExecCmd* (action)
	* Value Action configurations (info)
	* Icon
	* The activation and the directory in *Timeline*
	* The configurations of *calculation* and *round*
	* The repeat value configuration


## Remplacements

The button **Filter** At the top right allows you to display all the equipment, following the filters by object and by plugin.

For each equipment :

- Check the box at the beginning of the line to activate its replacement.
- Select on the right the equipment by which it will be replaced.
- Click on its name to see its commands, and indicate which commands replace them. When choosing a device, the tool pre-fills these choices if it finds a command of the same type and same name on the target device.


> **Remark**
>
> When you indicate a target device on a source device, this target device is disabled in the list.
