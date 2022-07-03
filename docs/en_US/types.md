# Types of equipment
**Tools → Equipment types**

The sensors and actuators in Jeedom are managed by plugins, which create equipment with commands *Information* (sensor) or *Stock* (actuator). This then makes it possible to trigger actions based on the change of certain sensors, such as turning on a light on motion detection. But the Jeedom Core, and plugins like **, **, *Google Smart Home*, *Alexa Smart Home* etc., do not know what this equipment is : A socket, a light, a shutter, etc.

To overcome this problem, especially with voice assistants (*Turn on the room light*), Core introduced the **Generic Types**, used by these plugins.

This makes it possible to identify a piece of equipment by *The light of the room* for example.

Most of the time the generic types are set automatically when configuring your module (inclusion in Z-wave for example). But there may be times when you need to reconfigure them. The configuration of these Generic Types can be done directly in certain plugins, or by command in *Advanced configuration* of it.

This page allows these Generic Types to be configured in a more direct and simpler way, and even offers automatic assignment once the devices have been correctly assigned.

![Types of equipment](./images/coreGenerics.gif)

## Equipment type

This page offers storage by type of equipment : Socket, Light, Shutter, Thermostat, Camera, etc. Initially, most of your equipment will be classified in **Equipment without type**. To assign them a type, you can either move them to another type, or right click on the equipment to move it directly. The Equipment Type is not really useful in itself, the most important being the Order Types. You can thus have an Equipment without a Type, or a Type that does not necessarily correspond to its commands. You can of course mix control types within the same equipment. For now, it is more of a storage, a logical organization, which will serve perhaps in future versions.

> ****
>
> - When you move equipment in the game **Equipment without type**, Jeedom suggests that you remove Generic Types from its orders.
> - You can move several pieces of equipment at once by checking the checkboxes to the left of them.

## Command type

Once an item of equipment is positioned in the correct **, by clicking on it you access the list of its orders, colored differently if it is a *Information* (Blue) or a *Stock* (Orange).

By right-clicking on an order, you can then assign it a Generic Type corresponding to the specifications of this order (Info / Action type, Numeric, Binary subtype, etc).

> ****
>
> - The contextual menu of commands displays the type of equipment in bold, but still allows to assign any Generic Type of any type of equipment.

On each device, you have two buttons :

- **Auto Types** : This function opens a window offering you the appropriate Generic Types according to the type of equipment, the specifics of the order, and its name. You can then adjust the proposals and uncheck the application for certain commands before accepting or not. This function is compatible with selection by checkboxes.

- **** : This function removes Generic Types from all equipment commands.

> ****
>
> No changes are made before saving, with the button at the top right of the page.

## Generic types and scenarios

In v4.2, the Core has integrated the generic types in the scenarios. You can thus trigger a scenario if a lamp comes on in a room, if movement is detected in the house, turn off all the lights or close all the shutters with a single action, etc. In addition, if you add an equipment, you only have to indicate the correct types on its orders, it will not be necessary to edit such scenarios.

#### Trigger

You can trigger a scenario from sensors. For example, if you have motion detectors in the house, you can create an alarm scenario with each detector triggering : ``#][Move Salon][Presence]# == 1`, `#[Cuisine][Move Cuisine][Presence]# == 1`, etc.. In such a scenario, you will therefore need all your motion detectors, and if you add one, you will have to add it to the triggers. Logic.

Thanks to generic types, you will be able to use a single trigger : ``#genericType(PRESENCE)# == 1`. Here, no object is indicated, so the slightest movement throughout the house will trigger the scenario. If you add a new detector in the house, no need to edit the scenario (s)).

Here, a trigger on the switching on of a light in the Living room : ``#genericType(,#]#)# > 

#### Expression

If, in a scenario, you want to know if a light is on in the Living Room, you can do :

IF `#][Lumiere Canapé][]# == 1 OR #][Lumiere Salon][]# == 1 OR #][Lumiere Angle][]# == 1`

Or more simply : IF `genericType (LIGHT_STATE,#]#) > 0` or if one or more light (s) are on in the Living room.

If tomorrow you add a light in your living room, no need to retouch your scenarios !


#### Action

If you want to turn on all the lights in the Living Room, you can create a light action:

`` ``
#][Lumiere Canapé][]#
#][Lumiere Salon][]#
#][Lumiere Angle][]#
`` ``

Or more simply, create a `genericType` action with` LIGHT_ON` in `Salon`. If tomorrow you add a light in your living room, no need to retouch your scenarios !


## List of Generic Core Types

> ****
>
> - You can find this list directly in Jeedom, on this same page, with the button **** top right.

| **Other (id: Other)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | State timer | Information | numeric
|  | Timer Status (pause or not) | Information | 
|  |  | Stock | slider
|  | Pause timer | Stock | other
|  | Timer resume | Stock | other

| **Battery (id: Battery)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  |  | Information | numeric
|  | Battery charging | Information | binary

| **Camera (id: Camera)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Camera url | Information | string
|  | Camera recording status | Information | binary
|  | Camera movement up | Stock | other
|  | Camera movement down | Stock | other
|  | Camera movement to the left | Stock | other
|  | Camera movement to the right | Stock | other
|  | Zoom camera forward | Stock | other
|  | Zoom camera back | Stock | other
|  | Stop camera | Stock | other
|  | Camera preset | Stock | other
|  | Camera recording | Stock |
|  | Snapshot camera | Stock |

| **Heating (id: Heating)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Pilot wire heating Status | Information | binary
|  | Pilot wire heating ON button | Stock | other
|  | Pilot wire heating OFF button | Stock | other
|  | Heating pilot wire Button | Stock | other

| **Electricity (id: Electricity)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Electric power | Information | numeric
|  | Power consumption | Information | numeric
|  |  | Information | numeric
|  | Restart | Stock | other

| **Environment (id: Environment)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | TEMPERATURE | Information | numeric
|  | Air quality | Information | numeric
|  |  | Information | numeric
|  | PRESENCE | Information | binary
|  | Smoke detection | Information | binary
|  |  | Information | numeric
|  |  | Information | numeric
|  | ) | Information | numeric
|  | ) | Information | numeric
|  | Sound (dB) | Information | numeric
|  |  | Information | numeric
|  | Water leak | Information |
|  | Filter state | Information | binary

| **: Generic)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  |  | Information | numeric
|  |  | Information | numeric
|  |  | Information | 
|  |  Generic | Information |
|  |  Generic | Stock | other

| **Light (id: Light)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Light State | Information | 
|  | Light Brightness | Information | numeric
|  | Light Color | Information | string
|  | Light State (Binary) | Information | binary
|  | Light Temperature Color | Information | numeric
|  | Toggle light | Stock | other
|  | Light Button On | Stock | other
|  | Light Button Off | Stock | other
|  | Slider light | Stock | slider
|  | Light Color | Stock | color
|  | Light Mode | Stock | other
|  | Light Temperature Color | Stock |

| **Fashion (id: Mode)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Status Mode | Information | string
|  | Change Mode | Stock | other

| **Multimedia (id: Multimedia)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  |  | Information | numeric
|  |  | Information | string
|  |  | Information | string
|  |  | Information | string
|  |  | Information | string
|  |  | Information | string
|  |  | Information | 
|  |  | Information | binary
|  |  | Stock | slider
|  |  | Stock | other slider
|  |  | Stock | other
|  |  | Stock | other
|  |  | Stock | other
|  |  | Stock | other
|  | Previous | Stock | other
|  |  | Stock | other
|  |  | Stock | other
|  |  | Stock | other
|  | No Mute | Stock | other

| **Weather (id: Weather)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Weather Temperature | Information | numeric
|  | Weather condition d + 1 max d + 2 | Information | numeric
|  | Wind (speed) | Information | numeric
|  | Rain (accumulation) | Information | numeric
|  | Rain (mm / h) | Information | numeric
|  | Weather condition (id) d + 4 | Information | numeric
|  | Weather condition d + 4 | Information | string
|  | Weather Max temperature d + 4 | Information | numeric
|  | Weather Temperature min d + 4 | Information | numeric
|  | Weather condition (id) d + 3 | Information | numeric
|  | Weather condition d + 3 | Information | string
|  | Weather Max temperature d + 3 | Information | numeric
|  | Weather Temperature min d + 3 | Information | numeric
|  | Weather condition (id) d + 2 | Information | numeric
|  | Weather condition d + 2 | Information | string
|  | Weather Temperature min d + 2 | Information | numeric
|  | Weather Humidity | Information | numeric
|  | Weather condition (id) d + 1 | Information | numeric
|  | Weather condition d + 1 | Information | string
|  | Weather Max temperature d + 1 | Information | numeric
|  | Weather Temperature min d + 1 | Information | numeric
|  | Weather condition (id) | Information | numeric
|  | Weather condition | Information | string
| X | Weather Max temperature | Information | numeric
|  | Weather Temperature min | Information | numeric
|  | Sunset weather | Information | numeric
|  | Sunrise weather | Information | numeric
|  | Wind direction weather | Information | numeric
|  | Wind speed weather | Information | numeric
|  | Weather Pressure | Information | numeric
|  | Wind (direction) | Information | numeric

| **Opening (id: Opening)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | State lock | Information | binary
|  | Portal (opening) State | Information | binary
|  | Garage (opening) State | Information | binary
|  |  | Information | binary
|  | Window | Information | binary
|  | Lock Button Open | Stock | other
|  | Lock Button Close | Stock | other
|  | Gate or garage opening button | Stock | other
|  | Gate or garage closing button | Stock | other
|  | Gate or garage button toggle | Stock | other

| **Socket (id: Outlet)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | State socket | Information | 
|  | On Button Socket | Stock | other
|  | Socket Button Off | Stock | other
|  | Slider socket | Stock |

| **: Robot)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | State base | Information | binary
|  | Back to base | Stock | other

| **Security (id: Security)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Mermaid State | Information | binary
|  | Alarm Status | Information | 
|  | Alarm Mode | Information | string
|  | Alarm Status activated | Information | binary
|  |  | Information | binary
|  |  | Information | binary
|  |  | Information | 
|  | Siren Button Off | Stock | other
|  | Siren Button On | Stock | other
|  | Armed alarm | Stock | other
|  | Alarm released | Stock | other
|  | Alarm Mode | Stock | other

| **: Thermostat)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Thermostat Status (BINARY) (for Plugin Thermostat only) | Information |
|  | Room temperature thermostat | Information | numeric
|  | Setpoint thermostat | Information | numeric
|  | Thermostat Mode (for Plugin Thermostat only) | Information | string
|  | Locking Thermostat (for Plugin Thermostat only) | Information | binary
|  | Outdoor Temperature Thermostat (for Plugin Thermostat only) | Information | numeric
|  | Thermostat Status (HUMAN) (for Plugin Thermostat only) | Information | string
|  | Room humidity thermostat | Information | numeric
|  | Set humidity | Information | slider
|  | Setpoint thermostat | Stock | slider
|  | Thermostat Mode (for Plugin Thermostat only) | Stock | other
|  | Locking Thermostat (for Plugin Thermostat only) | Stock | other
|  | Unlock Thermostat (for Plugin Thermostat only) | Stock | other
|  | Set humidity | Stock | slider

| **Fan (id: Fan)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Fan speed Status | Information | numeric
|  | State Rotation | Information | numeric
|  | Fan speed | Stock | slider
|  |  | Stock | slider

| **Pane (id: Shutter)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Status pane | Information | 
|  | BSO Status pane | Information | 
|  | Pane Up Button | Stock | other
|  | Pane Down Button | Stock | other
|  | Stop button shutter | Stock |
|  | Slider Button Pane | Stock | slider
|  | BSO pane Up button | Stock | other
|  | BSO pane Down button | Stock | other
