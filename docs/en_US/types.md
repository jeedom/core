# Types of equipment
**Tools → Equipment types**

The sensors and actuators in Jeedom are managed by plugins, which create equipment with commands *Info* (sensor) or *Action* (actuator). This then makes it possible to trigger actions based on the change of certain sensors, such as turning on a light on motion detection. But the Jeedom Core, and plugins like *Mobile*, *Homebridge*, *Google Smarthome*, *Alexa* etc., do not know what this equipment is : A socket, a light, a shutter, etc.

To overcome this problem, especially with voice assistants (*Turn on the room light*), Core introduced the **Generic Types**, used by these plugins.

The configuration of these Generic Types can be done directly in certain plugins, or by command in *Advanced configuration* of it.

This page allows these Generic Types to be configured directly, in a more direct and simpler way, and even offers automatic assignment once the devices have been correctly assigned.

![Types of equipment](./images/coreGenerics.gif)

## Equipment type

This page offers storage by type of equipment : Socket, Light, Shutter, Thermostat, Camera, etc. Initially, most of your equipment will be classified in **Equipment without type**. To assign them a type, you can either move them to another type, or right click on the equipment to move it directly.

> **Tip**
>
> - When you move equipment in the game **Equipment without type**, Jeedom suggests that you remove Generic Types from its orders.
> - You can move several pieces of equipment at once by checking the checkboxes to the left of them.

## Command type

Once an item of equipment is positioned in the correct *Type*, by clicking on it you access the list of its orders, colored differently if it is a *Info* (Blue) or a *Action* (Orange).

By right-clicking on an order, you can then assign it a Generic Type corresponding to the specifications of this order (Info / Action type, Numeric, Binary subtype, etc).

> **Tip**
>
> - The contextual menu of commands displays the type of equipment in bold, but still allows to assign any Generic Type of any type of equipment.

On each device, you have two buttons :

- **Auto Types** : This function opens a window offering you the appropriate Generic Types according to the type of equipment, the specifics of the order, and its name. You can then adjust the proposals and uncheck the application for certain commands before accepting or not. This function is compatible with selection by checkboxes.

- **Reset types** : This function removes Generic Types from all equipment commands.

> **Warning**
>
> No changes are made before saving, with the button at the top right of the page.