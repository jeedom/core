# Types of equipment
**Tools → Equipment types**

The sensors and actuators in Jeedom are managed by plugins, which create equipment with commands *Information* (sensor) or *Action* (actuator). This then makes it possible to trigger actions based on the change of certain sensors, such as turning on a light on motion detection. But the Jeedom Core, and plugins like *Mobile*, *Homebridge*, *Google Smart Home*, *Alexa Smart Home* etc., do not know what this equipment is : A socket, a light, a shutter, etc.

To overcome this problem, especially with voice assistants (*Turn on the room light*), Core introduced the **Generic Types**, used by these plugins.

This makes it possible to identify a piece of equipment by *The light of the room* for example.

Most of the time the generic types are set automatically when configuring your module (inclusion in Z-wave for example). But there may be times when you need to reconfigure them. The configuration of these Generic Types can be done directly in certain plugins, or by command in *Advanced configuration* of it.

This page allows these Generic Types to be configured in a more direct and simpler way, and even offers automatic assignment once the devices have been correctly assigned.

![Types of equipment](./images/coreGenerics.gif)

## Equipment type

This page offers storage by type of equipment : Socket, Light, Shutter, Thermostat, Camera, etc. Initially, most of your equipment will be classified in **Equipment without type**. To assign them a type, you can either move them to another type, or right click on the equipment to move it directly. The Equipment Type is not really useful in itself, the most important being the Order Types. You can thus have an Equipment without a Type, or a Type that does not necessarily correspond to its commands. You can of course mix control types within the same equipment. For now, it is more of a storage, a logical organization, which will serve perhaps in future versions.

> **Tip**
>
> - When you move equipment in the game **Equipment without type**, Jeedom suggests that you remove Generic Types from its orders.
> - You can move several pieces of equipment at once by checking the checkboxes to the left of them.

## Command type

Once an item of equipment is positioned in the correct *Type*, by clicking on it you access the list of its orders, colored differently if it is a *Information* (Blue) or a *Action* (Orange).

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

## Generic types and scenarios

In v4.2, the Core has integrated the generic types in the scenarios. You can thus trigger a scenario if a lamp comes on in a room, if movement is detected in the house, turn off all the lights or close all the shutters with a single action, etc. In addition, if you add an equipment, you only have to indicate the correct types on its orders, it will not be necessary to edit such scenarios.

#### Trigger

You can trigger a scenario from sensors. For example, if you have motion detectors in the house, you can create an alarm scenario with each detector triggering : ``#[Salon][Move Salon][Presence]# == 1`, `#[Cuisine][Move Cuisine][Presence]# == 1`, etc.. In such a scenario, you will therefore need all your motion detectors, and if you add one, you will have to add it to the triggers. Logic.

Thanks to generic types, you will be able to use a single trigger : ``#genericType(PRESENCE)# == 1`. Here, no object is indicated, so the slightest movement throughout the house will trigger the scenario. If you add a new detector in the house, no need to edit the scenario (s)).

Here, a trigger on the switching on of a light in the Living room : ``#genericType(LIGHT_STATE,#[Salon]#)# > 0`

#### Expression

If, in a scenario, you want to know if a light is on in the Living Room, you can do :

IF `#[Salon][Lumiere Canapé][State]# == 1 OR #[Salon][Lumiere Salon][State]# == 1 OR #[Salon][Lumiere Angle][State]# == 1`

Or more simply : IF `genericType (LIGHT_STATE,#[Salon]#) > 0` or if one or more light (s) are on in the Living room.

If tomorrow you add a light in your living room, no need to retouch your scenarios !


#### Action

If you want to turn on all the lights in the Living Room, you can create a light action:

`` ``
#[Salon][Lumiere Canapé][We]#
#[Salon][Lumiere Salon][We]#
#[Salon][Lumiere Angle][We]#
`` ``

Or more simply, create a `genericType` action with` LIGHT_ON` in `Salon`. If tomorrow you add a light in your living room, no need to retouch your scenarios !


## List of Generic Core Types

> **Tip**
>
> - You can find this list directly in Jeedom, on this same page, with the button **Listing** top right.

| **Other (id: Other)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TIMER | State timer | Information | numeric
| TIMER_STATE | Timer Status (pause or not) | Information | binary, numeric
| SET_TIMER | Timer | Action | slider
| TIMER_PAUSE | Pause timer | Action | other
| TIMER_RESUME | Timer resume | Action | other

| **Battery (id: Battery)** | | | |
|:--------|:----------------|:--------:|:---------:|
| BATTERY | Battery | Information | numeric
| BATTERY_CHARGING | Battery charging | Information | binary

| **Camera (id: Camera)** | | | |
|:--------|:----------------|:--------:|:---------:|
| CAMERA_URL | Camera url | Information | string
| CAMERA_RECORD_STATE | Camera recording status | Information | binary
| CAMERA_UP | Camera movement up | Action | other
| CAMERA_DOWN | Camera movement down | Action | other
| CAMERA_LEFT | Camera movement to the left | Action | other
| CAMERA_RIGHT | Camera movement to the right | Action | other
| CAMERA_ZOOM | Zoom camera forward | Action | other
| CAMERA_DEZOOM | Zoom camera back | Action | other
| CAMERA_STOP | Stop camera | Action | other
| CAMERA_PRESET | Camera preset | Action | other
| CAMERA_RECORD | Camera recording | Action |
| CAMERA_TAKE | Snapshot camera | Action |

| **Heating (id: Heating)** | | | |
|:--------|:----------------|:--------:|:---------:|
| HEATING_STATE | Pilot wire heating Status | Information | binary
| HEATING_ON | Pilot wire heating ON button | Action | other
| HEATING_OFF | Pilot wire heating OFF button | Action | other
| HEATING_OTHER | Heating pilot wire Button | Action | other

| **Electricity (id: Electricity)** | | | |
|:--------|:----------------|:--------:|:---------:|
| Power | Electric power | Information | numeric
| CONSUMPTION | Power consumption | Information | numeric
| VOLTAGE | Voltage | Information | numeric
| REBOOT | Restart | Action | other

| **Environment (id: Environment)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TEMPERATURE | TEMPERATURE | Information | numeric
| AIR_QUALITY | Air quality | Information | numeric
| BRIGHTNESS | Brightness | Information | numeric
| PRESENCE | PRESENCE | Information | binary
| SMOKE | Smoke detection | Information | binary
| HUMIDITY | Humidity | Information | numeric
| UV | UV | Information | numeric
| CO2 | CO2 (ppm) | Information | numeric
| CO | CO (ppm) | Information | numeric
| NOISE | Sound (dB) | Information | numeric
| PRESSURE | Pressure | Information | numeric
| WATER_LEAK | Water leak | Information |
| FILTER_CLEAN_STATE | Filter state | Information | binary

| **Generic (id: Generic)** | | | |
|:--------|:----------------|:--------:|:---------:|
| DEPTH | Depth | Information | numeric
| DISTANCE | DISTANCE | Information | numeric
| BUTTON | Button | Information | binary, numeric
| GENERIC_INFO |  Generic | Information |
| GENERIC_ACTION |  Generic | Action | other

| **Light (id: Light)** | | | |
|:--------|:----------------|:--------:|:---------:|
| LIGHT_STATE | Light State | Information | binary, numeric
| LIGHT_BRIGHTNESS | Light Brightness | Information | numeric
| LIGHT_COLOR | Light Color | Information | string
| LIGHT_STATE_BOOL | Light State (Binary) | Information | binary
| LIGHT_COLOR_TEMP | Light Temperature Color | Information | numeric
| LIGHT_TOGGLE | Toggle light | Action | other
| LIGHT_ON | Light Button On | Action | other
| LIGHT_OFF | Light Button Off | Action | other
| LIGHT_SLIDER | Slider light | Action | slider
| LIGHT_SET_COLOR | Light Color | Action | color
| LIGHT_MODE | Light Mode | Action | other
| LIGHT_SET_COLOR_TEMP | Light Temperature Color | Action |

| **Fashion (id: Mode)** | | | |
|:--------|:----------------|:--------:|:---------:|
| MODE_STATE | Status Mode | Information | string
| MODE_SET_STATE | Change Mode | Action | other

| **Multimedia (id: Multimedia)** | | | |
|:--------|:----------------|:--------:|:---------:|
| Volume | Volume | Information | numeric
| MEDIA_STATUS | Status | Information | string
| MEDIA_ALBUM | Album | Information | string
| MEDIA_ARTIST | Artist | Information | string
| MEDIA_TITLE | Title | Information | string
| MEDIA_POWER | Power | Information | string
| CHANNEL | Chain | Information | numeric, string
| MEDIA_STATE | State | Information | binary
| SET_VOLUME | Volume | Action | slider
| SET_CHANNEL | Chain | Action | other slider
| MEDIA_PAUSE | Pause | Action | other
| MEDIA_RESUME | Reading | Action | other
| MEDIA_STOP | Stop | Action | other
| MEDIA_NEXT | Following | Action | other
| MEDIA_PREVIOUS | Previous | Action | other
| MEDIA_ON | We | Action | other
| MEDIA_OFF | Off | Action | other
| MEDIA_MUTE | Mute | Action | other
| MEDIA_UNMUTE | No Mute | Action | other

| **Weather (id: Weather)** | | | |
|:--------|:----------------|:--------:|:---------:|
| WEATHER_TEMPERATURE | Weather Temperature | Information | numeric
| WEATHER_TEMPERATURE_MAX_2 | Weather condition d + 1 max d + 2 | Information | numeric
| WIND_SPEED | Wind (speed) | Information | numeric
| RAIN_TOTAL | Rain (accumulation) | Information | numeric
| RAIN_CURRENT | Rain (mm / h) | Information | numeric
| WEATHER_CONDITION_ID_4 | Weather condition (id) d + 4 | Information | numeric
| WEATHER_CONDITION_4 | Weather condition d + 4 | Information | string
| WEATHER_TEMPERATURE_MAX_4 | Weather Max temperature d + 4 | Information | numeric
| WEATHER_TEMPERATURE_MIN_4 | Weather Temperature min d + 4 | Information | numeric
| WEATHER_CONDITION_ID_3 | Weather condition (id) d + 3 | Information | numeric
| WEATHER_CONDITION_3 | Weather condition d + 3 | Information | string
| WEATHER_TEMPERATURE_MAX_3 | Weather Max temperature d + 3 | Information | numeric
| WEATHER_TEMPERATURE_MIN_3 | Weather Temperature min d + 3 | Information | numeric
| WEATHER_CONDITION_ID_2 | Weather condition (id) d + 2 | Information | numeric
| WEATHER_CONDITION_2 | Weather condition d + 2 | Information | string
| WEATHER_TEMPERATURE_MIN_2 | Weather Temperature min d + 2 | Information | numeric
| WEATHER_HUMIDITY | Weather Humidity | Information | numeric
| WEATHER_CONDITION_ID_1 | Weather condition (id) d + 1 | Information | numeric
| WEATHER_CONDITION_1 | Weather condition d + 1 | Information | string
| WEATHER_TEMPERATURE_MAX_1 | Weather Max temperature d + 1 | Information | numeric
| WEATHER_TEMPERATURE_MIN_1 | Weather Temperature min d + 1 | Information | numeric
| WEATHER_CONDITION_ID | Weather condition (id) | Information | numeric
| WEATHER_CONDITION | Weather condition | Information | string
| WEATHER_TEMPERATURE_MAX | Weather Max temperature | Information | numeric
| WEATHER_TEMPERATURE_MIN | Weather Temperature min | Information | numeric
| WEATHER_SUNRISE | Sunset weather | Information | numeric
| WEATHER_SUNSET | Sunrise weather | Information | numeric
| WEATHER_WIND_DIRECTION | Wind direction weather | Information | numeric
| WEATHER_WIND_SPEED | Wind speed weather | Information | numeric
| WEATHER_PRESSURE | Weather Pressure | Information | numeric
| WIND_DIRECTION | Wind (direction) | Information | numeric

| **Opening (id: Opening)** | | | |
|:--------|:----------------|:--------:|:---------:|
| LOCK_STATE | State lock | Information | binary
| BARRIER_STATE | Portal (opening) State | Information | binary
| GARAGE_STATE | Garage (opening) State | Information | binary
| OPENING | Carries | Information | binary
| OPENING_WINDOW | Window | Information | binary
| LOCK_OPEN | Lock Button Open | Action | other
| LOCK_CLOSE | Lock Button Close | Action | other
| GB_OPEN | Gate or garage opening button | Action | other
| GB_CLOSE | Gate or garage closing button | Action | other
| GB_TOGGLE | Gate or garage button toggle | Action | other

| **Socket (id: Outlet)** | | | |
|:--------|:----------------|:--------:|:---------:|
| ENERGY_STATE | State socket | Information | numeric, binary
| ENERGY_ON | On Button Socket | Action | other
| ENERGY_OFF | Socket Button Off | Action | other
| ENERGY_SLIDER | Slider socket | Action |

| **Robot (id: Robot)** | | | |
|:--------|:----------------|:--------:|:---------:|
| DOCK_STATE | State base | Information | binary
| DOCK | Back to base | Action | other

| **Security (id: Security)** | | | |
|:--------|:----------------|:--------:|:---------:|
| SIREN_STATE | Mermaid State | Information | binary
| ALARM_STATE | Alarm Status | Information | binary, string
| ALARM_MODE | Alarm Mode | Information | string
| ALARM_ENABLE_STATE | Alarm Status activated | Information | binary
| FLOOD | Flood | Information | binary
| SABOTAGE | SABOTAGE | Information | binary
| SHOCK | Shock | Information | binary, numeric
| SIREN_OFF | Siren Button Off | Action | other
| SIREN_ON | Siren Button On | Action | other
| ALARM_ARMED | Armed alarm | Action | other
| ALARM_RELEASED | Alarm released | Action | other
| ALARM_SET_MODE | Alarm Mode | Action | other

| **Thermostat (id: Thermostat)** | | | |
|:--------|:----------------|:--------:|:---------:|
| THERMOSTAT_STATE | Thermostat Status (BINARY) (for Plugin Thermostat only) | Information |
| THERMOSTAT_TEMPERATURE | Room temperature thermostat | Information | numeric
| THERMOSTAT_SETPOINT | Setpoint thermostat | Information | numeric
| THERMOSTAT_MODE | Thermostat Mode (for Plugin Thermostat only) | Information | string
| THERMOSTAT_LOCK | Locking Thermostat (for Plugin Thermostat only) | Information | binary
| THERMOSTAT_TEMPERATURE_OUTDOOR | Outdoor Temperature Thermostat (for Plugin Thermostat only) | Information | numeric
| THERMOSTAT_STATE_NAME | Thermostat Status (HUMAN) (for Plugin Thermostat only) | Information | string
| THERMOSTAT_HUMIDITY | Room humidity thermostat | Information | numeric
| HUMIDITY_SETPOINT | Set humidity | Information | slider
| THERMOSTAT_SET_SETPOINT | Setpoint thermostat | Action | slider
| THERMOSTAT_SET_MODE | Thermostat Mode (for Plugin Thermostat only) | Action | other
| THERMOSTAT_SET_LOCK | Locking Thermostat (for Plugin Thermostat only) | Action | other
| THERMOSTAT_SET_UNLOCK | Unlock Thermostat (for Plugin Thermostat only) | Action | other
| HUMIDITY_SET_SETPOINT | Set humidity | Action | slider

| **Fan (id: Fan)** | | | |
|:--------|:----------------|:--------:|:---------:|
| FAN_SPEED_STATE | Fan speed Status | Information | numeric
| ROTATION_STATE | State Rotation | Information | numeric
| FAN_SPEED | Fan speed | Action | slider
| ROTATION | ROTATION | Action | slider

| **Pane (id: Shutter)** | | | |
|:--------|:----------------|:--------:|:---------:|
| FLAP_STATE | Status pane | Information | binary, numeric
| FLAP_BSO_STATE | BSO Status pane | Information | binary, numeric
| FLAP_UP | Pane Up Button | Action | other
| FLAP_DOWN | Pane Down Button | Action | other
| FLAP_STOP | Stop button shutter | Action |
| FLAP_SLIDER | Slider Button Pane | Action | slider
| FLAP_BSO_UP | BSO pane Up button | Action | other
| FLAP_BSO_DOWN | BSO pane Down button | Action | other
