 # Remplacer
**Tools → Replace**

This tool makes it possible to quickly replace equipment and controls, for example in the case of a change of plugin, or of a module.

Like the replacement options on the advanced configuration of a command, it replaces the commands in the scenarios and other, but also allows to transfer the properties of the equipment and the commands.

## Filtres

You can display only certain equipment for more readability, filtering by object or by plugin.

> In the case of a replacement of equipment by equipment from another plugin, select the two plugins.

## Options

> ****
>
> If none of these options is checked, the replacement amounts to using the function *Replace this command with the command* in advanced configuration.

- **Copy configuration from source device** :
For each piece of equipment, will be copied from the source to the target (non-exhaustive list) :
	* The parent object,
	* The categories,
	* Properties **  **,
	* Comments and tags,
	* Order (Dashboard),
	* The Width and Height (Tile Dashboard),
	* Tile curve settings,
	* Optional parameters,
	* The table display configuration,
	* the Generic Type,
	* The property **
	* The configuration **,
	* Battery and communication alerts,

The source equipment will also be replaced by the target equipment on the **** and the ****.


*This equipment will also be replaced by the target equipment on Designs and Views.*

- **Hide source equipment** : Allows to make the source equipment invisible once replaced by the target equipment.

- **Copy configuration from source command** :
For each order, will be copied from the source to the target (non-exhaustive list) :
	* The property **,
	* Order (Dashboard),
	* L'historisation,
	* The Dashboard and Mobile widgets used,
	* The Generic Type,
	* Optional parameters,
	* The configurations **  *),
	* Value Action configurations (info),
	* Icon,
	* The activation and the directory in **,
	* The configurations of **  **,
	* The influxDB configuration,
	* The repeat value configuration,
	* Alerts,

- **Delete target command history** : Deletes target command history data.

- **Copy source command history** : Copy source command history to target command.



## Remplacements

The button **** At the top right allows you to display all the equipment, following the filters by object and by plugin.

For each equipment :

- Check the box at the beginning of the line to activate its replacement.
- Select on the right the equipment by which it will be replaced.
- Click on its name to see its commands, and indicate which commands replace them. When choosing a device, the tool pre-fills these choices if it finds a command of the same type and same name on the target device.


> ****
>
> When you indicate a target device on a source device, this target device is disabled in the list.
