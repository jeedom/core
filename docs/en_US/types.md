# Types of equipment
**Tools → Equipment types**

The sensors and actuators in Jeedom are managed by plugins, which create equipment with commands ** (sensor) or ** (actuator). This then makes it possible to trigger actions based on the change of certain sensors, such as turning on a light on motion detection. But the Jeedom Core, and plugins like **, **, *Google Smart Home*, *Alexa Smart Home* etc., do not know what this equipment is : A socket, a light, a shutter, etc.

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

Once an item of equipment is positioned in the correct **, by clicking on it you access the list of its orders, colored differently if it is a ** (Blue) or a ** (Orange).

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

You can trigger a scenario from sensors. For example, if you have motion detectors in the house, you can create an alarm scenario with each detector triggering : ``#[Living room][Move Salon][Presence]# == #[Cuisine][Move Cuisine][Presence]# == 1`, etc.. In such a scenario, you will therefore need all your motion detectors, and if you add one, you will have to add it to the triggers. Logic.

Thanks to generic types, you will be able to use a single trigger : ``#genericType(PRESENCE)# == . Here, no object is indicated, so the slightest movement throughout the house will trigger the scenario. If you add a new detector in the house, no need to edit the scenario (s)).

Here, a trigger on the switching on of a light in the Living room : ``#genericType(,#[Living room]#)# > 

#### Expression

If, in a scenario, you want to know if a light is on in the Living Room, you can do :

IF `#[Living room][Lumiere Canapé][]# == 1 OR #[Living room][Lumiere Salon][]# == 1 OR #[Living room][Lumiere Angle][]# == 

Or more simply : IF `genericType (LIGHT_STATE,#[Living room]#) > 0` or if one or more light (s) are on in the Living room.

If tomorrow you add a light in your living room, no need to retouch your scenarios !


#### Action

If you want to turn on all the lights in the Living Room, you can create a light action:

`` ``
#[Living room][Lumiere Canapé][]#
#[Living room][Lumiere Salon][]#
#[Living room][Lumiere Angle][]#
`` ``

Or more simply, create a `genericType` action with` LIGHT_ON` in `Salon`. If tomorrow you add a light in your living room, no need to retouch your scenarios !


## List of Generic Core Types

> ****
>
> - You can find this list directly in Jeedom, on this same page, with the button **** top right.

| **Other (id: Other)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | State timer |  | numeric
|  | Timer Status (pause or not) |  | 
|  |  |  | slider
|  | Pause timer |  | other
|  | Timer resume |  | other

| **Battery (id: Battery)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  |  |  | numeric
|  | Battery charging |  | binary

| **Camera (id: Camera)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Camera url |  | string
|  | Camera recording status |  | binary
|  | Camera movement up |  | other
|  | Camera movement down |  | other
|  | Camera movement to the left |  | other
|  | Camera movement to the right |  | other
|  | Zoom camera forward |  | other
|  | Zoom camera back |  | other
|  | Stop camera |  | other
|  | Camera preset |  | other
|  | Camera recording |  |
|  | Snapshot camera |  |

| **Heating (id: Heating)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Pilot wire heating Status |  | binary
|  | Pilot wire heating ON button |  | other
|  | Pilot wire heating OFF button |  | other
|  | Heating pilot wire Button |  | other

| **Electricity (id: Electricity)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Electric power |  | numeric
|  | Power consumption |  | numeric
|  |  |  | numeric
|  | Restart |  | other

| **Environment (id: Environment)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | TEMPERATURE |  | numeric
|  | Air quality |  | numeric
|  |  |  | numeric
|  | PRESENCE |  | binary
|  | Smoke detection |  | binary
|  |  |  | numeric
|  |  |  | numeric
|  | ) |  | numeric
|  | ) |  | numeric
|  | Sound (dB) |  | numeric
|  |  |  | numeric
|  | Water leak |  |
|  | Filter state |  | binary

| **: Generic)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  |  |  | numeric
|  |  |  | numeric
|  |  |  | 
|  |  Generic |  |
|  |  Generic |  | other

| **Light (id: Light)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Light State |  | 
|  | Light Brightness |  | numeric
|  | Light Color |  | string
|  | Light State (Binary) |  | binary
|  | Light Temperature Color |  | numeric
|  | Toggle light |  | other
|  | Light Button On |  | other
|  | Light Button Off |  | other
|  | Slider light |  | slider
|  | Light Color |  | color
|  | Light Mode |  | other
|  | Light Temperature Color |  |

| **Fashion (id: Mode)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Status Mode |  | string
|  | Change Mode |  | other

| **Multimedia (id: Multimedia)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  |  |  | numeric
|  |  |  | string
|  |  |  | string
|  |  |  | string
|  |  |  | string
|  |  |  | string
|  |  |  | 
|  |  |  | binary
|  |  |  | slider
|  |  |  | other slider
|  |  |  | other
|  |  |  | other
|  |  |  | other
|  |  |  | other
|  | Previous |  | other
|  |  |  | other
|  |  |  | other
|  |  |  | other
|  | No Mute |  | other

| **Weather (id: Weather)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Weather Temperature |  | numeric
|  | Weather condition d + 1 max d + 2 |  | numeric
|  | Wind (speed) |  | numeric
|  | Rain (accumulation) |  | numeric
|  | Rain (mm / h) |  | numeric
|  | Weather condition (id) d + 4 |  | numeric
|  | Weather condition d + 4 |  | string
|  | Weather Max temperature d + 4 |  | numeric
|  | Weather Temperature min d + 4 |  | numeric
|  | Weather condition (id) d + 3 |  | numeric
|  | Weather condition d + 3 |  | string
|  | Weather Max temperature d + 3 |  | numeric
|  | Weather Temperature min d + 3 |  | numeric
|  | Weather condition (id) d + 2 |  | numeric
|  | Weather condition d + 2 |  | string
|  | Weather Temperature min d + 2 |  | numeric
|  | Weather Humidity |  | numeric
|  | Weather condition (id) d + 1 |  | numeric
|  | Weather condition d + 1 |  | string
|  | Weather Max temperature d + 1 |  | numeric
|  | Weather Temperature min d + 1 |  | numeric
|  | Weather condition (id) |  | numeric
|  | Weather condition |  | string
| X | Weather Max temperature |  | numeric
|  | Weather Temperature min |  | numeric
|  | Sunset weather |  | numeric
|  | Sunrise weather |  | numeric
|  | Wind direction weather |  | numeric
|  | Wind speed weather |  | numeric
|  | Weather Pressure |  | numeric
|  | Wind (direction) |  | numeric

| **Opening (id: Opening)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | State lock |  | binary
|  | Portal (opening) State |  | binary
|  | Garage (opening) State |  | binary
|  |  |  | binary
|  | Window |  | binary
|  | Lock Button Open |  | other
|  | Lock Button Close |  | other
|  | Gate or garage opening button |  | other
|  | Gate or garage closing button |  | other
|  | Gate or garage button toggle |  | other

| **Socket (id: Outlet)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | State socket |  | 
|  | On Button Socket |  | other
|  | Socket Button Off |  | other
|  | Slider socket |  |

| **: Robot)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | State base |  | binary
|  | Back to base |  | other

| **Security (id: Security)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Mermaid State |  | binary
|  | Alarm Status |  | 
|  | Alarm Mode |  | string
|  | Alarm Status activated |  | binary
|  |  |  | binary
|  |  |  | binary
|  |  |  | 
|  | Siren Button Off |  | other
|  | Siren Button On |  | other
|  | Armed alarm |  | other
|  | Alarm released |  | other
|  | Alarm Mode |  | other

| **: Thermostat)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Thermostat Status (BINARY) (for Plugin Thermostat only) |  |
|  | Room temperature thermostat |  | numeric
|  | Setpoint thermostat |  | numeric
|  | Thermostat Mode (for Plugin Thermostat only) |  | string
|  | Locking Thermostat (for Plugin Thermostat only) |  | binary
|  | Outdoor Temperature Thermostat (for Plugin Thermostat only) |  | numeric
|  | Thermostat Status (HUMAN) (for Plugin Thermostat only) |  | string
|  | Room humidity thermostat |  | numeric
|  | Set humidity |  | slider
|  | Setpoint thermostat |  | slider
|  | Thermostat Mode (for Plugin Thermostat only) |  | other
|  | Locking Thermostat (for Plugin Thermostat only) |  | other
|  | Unlock Thermostat (for Plugin Thermostat only) |  | other
|  | Set humidity |  | slider

| **Fan (id: Fan)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Fan speed Status |  | numeric
|  | State Rotation |  | numeric
|  | Fan speed |  | slider
|  |  |  | slider

| **Pane (id: Shutter)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Status pane |  | 
|  | BSO Status pane |  | 
|  | Pane Up Button |  | other
|  | Pane Down Button |  | other
|  | Stop button shutter |  |
|  | Slider Button Pane |  | slider
|  | BSO pane Up button |  | other
|  | BSO pane Down button |  | other
