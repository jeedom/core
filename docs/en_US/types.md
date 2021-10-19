# Types of equipment
**Tools → Equipment types**

The sensors and actuators in Jeedom are managed by plugins, which create equipment with commands *Info* (sensor) or *Action* (actuator). This then makes it possible to trigger actions based on the change of certain sensors, such as turning on a light on motion detection. But the Jeedom Core, and plugins like *Mobile*, *Homebridge*, *Google Smarthome*, *Alexa* etc., do not know what this equipment is : A socket, a light, a shutter, etc.

To overcome this problem, especially with voice assistants (*Turn on the room light*), Core introduced the **Generic Types**, used by these plugins.

This makes it possible to identify a piece of equipment by *The light of the room* for example.

Generic Types are also integrated into scenarios. You can thus trigger a scenario if a lamp comes on in a room, if movement is detected in the house, turn off all the lights or close all the shutters with a single action, etc. In addition, if you add an equipment, you only have to indicate these types, it will not be necessary to edit such scenarios.

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


## List of Generic Core Types

| **Other (id: Other)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TIMER | State timer (Not managed by Mobile Application) | Info | numeric
| TIMER_STATE | Timer Status (pause or not) (Not managed by Mobile Application) | Info | binary, numeric
| SET_TIMER | Timer (Not managed by Mobile Application) | Action | slider
| TIMER_PAUSE | Pause timer (Not managed by Mobile Application) | Action | other
| TIMER_RESUME | Timer resume (Not managed by Mobile Application) | Action | other

| **Battery (id: Battery)** | | | |
|:--------|:----------------|:--------:|:---------:|
| BATTERY | Battery (Not managed by Mobile Application) | Info | numeric
| BATTERY_CHARGING | Battery charging (Not managed by Mobile Application) | Info | binary

| **Camera (id: Camera)** | | | |
|:--------|:----------------|:--------:|:---------:|
| CAMERA_URL | Camera url | Info | string
| CAMERA_RECORD_STATE | Camera recording status | Info | binary
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
| HEATING_STATE | Pilot wire heating Status | Info | binary
| HEATING_ON | Pilot wire heating ON button | Action | other
| HEATING_OFF | Pilot wire heating OFF button | Action | other
| HEATING_OTHER | Heating pilot wire Button | Action | other

| **Electricity (id: Electricity)** | | | |
|:--------|:----------------|:--------:|:---------:|
| POWER | Electric power | Info | numeric
| CONSUMPTION | Electricity consumption (Not managed by Mobile Application) | Info | numeric
| VOLTAGE | Voltage (Not managed by Mobile Application) | Info | numeric
| REBOOT | Restart (Not managed by Application Mobile) | Action | other

| **Environment (id: Environment)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TEMPERATURE | Temperature | Info | numeric
| AIR_QUALITY | Air quality | Info | numeric
| BRIGHTNESS | Brightness | Info | numeric
| PRESENCE | Presence | Info | binary
| SMOKE | Smoke detection | Info | binary
| HUMIDITY | Humidity | Info | numeric
| UV | UV (Not managed by Application Mobile) | Info | numeric
| CO2 | CO2 (ppm) (Not managed by Mobile Application) | Info | numeric
| CO | CO (ppm) (Not managed by Mobile Application) | Info | numeric
| NOISE | Sound (dB) (Not managed by Mobile Application) | Info | numeric
| PRESSURE | Pressure (Not managed by Mobile Application) | Info | numeric
| WATER_LEAK | Water leak (Not managed by Mobile Application) | Info |
| FILTER_CLEAN_STATE | Filter status (Not managed by Application Mobile) | Info | binary

| **Generic (id: Generic)** | | | |
|:--------|:----------------|:--------:|:---------:|
| DEPTH | Depth | Info | numeric
| DISTANCE | Distance | Info | numeric
| BUTTON | Button | Info | binary, numeric
| GENERIC_INFO |  Generic | Info |
| GENERIC_ACTION |  Generic | Action | other

| **Light (id: Light)** | | | |
|:--------|:----------------|:--------:|:---------:|
| LIGHT_STATE | Light State | Info | binary, numeric
| LIGHT_BRIGHTNESS | Light Brightness (Not managed by Mobile Application) | Info | numeric
| LIGHT_COLOR | Light Color | Info | string
| LIGHT_STATE_BOOL | Light State (Binary) (Not managed by Mobile Application) | Info | binary
| LIGHT_COLOR_TEMP | Light Temperature Color (Not managed by Mobile Application) | Info | numeric
| LIGHT_TOGGLE | Toggle light | Action | other
| LIGHT_ON | Light Button On | Action | other
| LIGHT_OFF | Light Button Off | Action | other
| LIGHT_SLIDER | Slider light | Action | slider
| LIGHT_SET_COLOR | Light Color | Action | color
| LIGHT_MODE | Light Mode | Action | other
| LIGHT_SET_COLOR_TEMP | Light Temperature Color (Not managed by Mobile Application) | Action |

| **Mode (id: Mode)** | | | |
|:--------|:----------------|:--------:|:---------:|
| MODE_STATE | Status Mode | Info | string
| MODE_SET_STATE | Change Mode | Action | other

| **Multimedia (id: Multimedia)** | | | |
|:--------|:----------------|:--------:|:---------:|
| VOLUME | Volume | Info | numeric
| MEDIA_STATUS | Status (Not managed by Application Mobile) | Info | string
| MEDIA_ALBUM | Album (Not managed by Application Mobile) | Info | string
| MEDIA_ARTIST | Artist (Not managed by Application Mobile) | Info | string
| MEDIA_TITLE | Title (Not managed by Application Mobile) | Info | string
| MEDIA_POWER | Power (Not managed by Application Mobile) | Info | string
| CHANNEL | Chain | Info | numeric, string
| MEDIA_STATE | Status (Not managed by Application Mobile) | Info | binary
| SET_VOLUME | Volume | Action | slider
| SET_CHANNEL | Chain | Action | other slider
| MEDIA_PAUSE | Pause | Action | other
| MEDIA_RESUME | Reading | Action | other
| MEDIA_STOP | Stop | Action | other
| MEDIA_NEXT | Following | Action | other
| MEDIA_PREVIOUS | Previous | Action | other
| MEDIA_ON | On (Not managed by Application Mobile) | Action | other
| MEDIA_OFF | Off (Not managed by Mobile Application) | Action | other
| MEDIA_MUTE | Mute (Not managed by Application Mobile) | Action | other
| MEDIA_UNMUTE | No Mute (Not managed by Mobile Application) | Action | other

| **Weather (id: Weather)** | | | |
|:--------|:----------------|:--------:|:---------:|
| WEATHER_TEMPERATURE | Weather Temperature (Not managed by Mobile Application) | Info | numeric
| WEATHER_TEMPERATURE_MAX_2 | Weather condition d + 1 max d + 2 (Not managed by Mobile Application) | Info | numeric
| WIND_SPEED | Wind (speed) (Not managed by Mobile Application) | Info | numeric
| RAIN_TOTAL | Rain (accumulation) (Not managed by Application Mobile) | Info | numeric
| RAIN_CURRENT | Rain (mm / h) (Not managed by Mobile Application) | Info | numeric
| WEATHER_CONDITION_ID_4 | Weather condition (id) d + 4 (Not managed by Mobile Application) | Info | numeric
| WEATHER_CONDITION_4 | Weather condition d + 4 (Not managed by Mobile Application) | Info | string
| WEATHER_TEMPERATURE_MAX_4 | Weather Max temperature d + 4 (Not managed by Mobile Application) | Info | numeric
| WEATHER_TEMPERATURE_MIN_4 | Weather Temperature min d + 4 (Not managed by Mobile Application) | Info | numeric
| WEATHER_CONDITION_ID_3 | Weather condition (id) d + 3 (Not managed by Mobile Application) | Info | numeric
| WEATHER_CONDITION_3 | Weather condition d + 3 (Not managed by Mobile Application) | Info | string
| WEATHER_TEMPERATURE_MAX_3 | Weather Max temperature d + 3 (Not managed by Mobile Application) | Info | numeric
| WEATHER_TEMPERATURE_MIN_3 | Weather Temperature min d + 3 (Not managed by Mobile Application) | Info | numeric
| WEATHER_CONDITION_ID_2 | Weather condition (id) d + 2 (Not managed by Mobile Application) | Info | numeric
| WEATHER_CONDITION_2 | Weather condition d + 2 (Not managed by Mobile Application) | Info | string
| WEATHER_TEMPERATURE_MIN_2 | Weather Temperature min d + 2 (Not managed by Mobile Application) | Info | numeric
| WEATHER_HUMIDITY | Weather Humidity (Not managed by Mobile Application) | Info | numeric
| WEATHER_CONDITION_ID_1 | Weather condition (id) d + 1 (Not managed by Mobile Application) | Info | numeric
| WEATHER_CONDITION_1 | Weather condition d + 1 (Not managed by Mobile Application) | Info | string
| WEATHER_TEMPERATURE_MAX_1 | Weather Max temperature d + 1 (Not managed by Mobile Application) | Info | numeric
| WEATHER_TEMPERATURE_MIN_1 | Weather Temperature min d + 1 (Not managed by Mobile Application) | Info | numeric
| WEATHER_CONDITION_ID | Weather condition (id) (Not managed by Mobile Application) | Info | numeric
| WEATHER_CONDITION | Weather condition (Not managed by Mobile Application) | Info | string
| WEATHER_TEMPERATURE_MAX | Weather Max temperature (Not managed by Mobile Application) | Info | numeric
| WEATHER_TEMPERATURE_MIN | Weather Temperature min (Not managed by Mobile Application) | Info | numeric
| WEATHER_SUNRISE | Sunset weather (Not managed by Mobile Application) | Info | numeric
| WEATHER_SUNSET | Sunrise weather (Not managed by Mobile Application) | Info | numeric
| WEATHER_WIND_DIRECTION | Wind direction weather forecast (Not managed by Mobile Application) | Info | numeric
| WEATHER_WIND_SPEED | Wind speed weather forecast (Not managed by Mobile Application) | Info | numeric
| WEATHER_PRESSURE | Weather Pressure (Not managed by Mobile Application) | Info | numeric
| WIND_DIRECTION | Wind (direction) (Not managed by Application Mobile) | Info | numeric

| **Opening (id: Opening)** | | | |
|:--------|:----------------|:--------:|:---------:|
| LOCK_STATE | State lock | Info | binary
| BARRIER_STATE | Portal (opening) State | Info | binary
| GARAGE_STATE | Garage (opening) State | Info | binary
| OPENING | Door | Info | binary
| OPENING_WINDOW | Window | Info | binary
| LOCK_OPEN | Lock Button Open | Action | other
| LOCK_CLOSE | Lock Button Close | Action | other
| GB_OPEN | Gate or garage opening button | Action | other
| GB_CLOSE | Gate or garage closing button | Action | other
| GB_TOGGLE | Gate or garage button toggle | Action | other

| **Socket (id: Outlet)** | | | |
|:--------|:----------------|:--------:|:---------:|
| ENERGY_STATE | State socket | Info | numeric, binary
| ENERGY_ON | On Button Socket | Action | other
| ENERGY_OFF | Socket Button Off | Action | other
| ENERGY_SLIDER | Slider socket | Action |

| **Robot (id: Robot)** | | | |
|:--------|:----------------|:--------:|:---------:|
| DOCK_STATE | State Base (Not managed by Mobile Application) | Info | binary
| DOCK | Return to base (Not managed by Mobile Application) | Action | other

| **Security (id: Security)** | | | |
|:--------|:----------------|:--------:|:---------:|
| SIREN_STATE | Mermaid State | Info | binary
| ALARM_STATE | Alarm Status (Not managed by Mobile Application) | Info | binary, string
| ALARM_MODE | Alarm mode (Not managed by Mobile Application) | Info | string
| ALARM_ENABLE_STATE | Alarm Status activated (Not managed by Mobile Application) | Info | binary
| FLOOD | Flood | Info | binary
| SABOTAGE | Sabotage | Info | binary
| SHOCK | Shock | Info | binary, numeric
| SIREN_OFF | Siren Button Off | Action | other
| SIREN_ON | Siren Button On | Action | other
| ALARM_ARMED | Armed alarm (Not managed by Mobile Application) | Action | other
| ALARM_RELEASED | Alarm released (Not managed by Mobile Application) | Action | other
| ALARM_SET_MODE | Alarm Mode (Not managed by Mobile Application) | Action | other

| **Thermostat (id: Thermostat)** | | | |
|:--------|:----------------|:--------:|:---------:|
| THERMOSTAT_STATE | Thermostat Status (BINARY) (for Plugin Thermostat only) | Info |
| THERMOSTAT_TEMPERATURE | Room temperature thermostat | Info | numeric
| THERMOSTAT_SETPOINT | Setpoint thermostat | Info | numeric
| THERMOSTAT_MODE | Thermostat Mode (for Plugin Thermostat only) | Info | string
| THERMOSTAT_LOCK | Locking Thermostat (for Plugin Thermostat only) | Info | binary
| THERMOSTAT_TEMPERATURE_OUTDOOR | Outdoor Temperature Thermostat (for Plugin Thermostat only) | Info | numeric
| THERMOSTAT_STATE_NAME | Thermostat Status (HUMAN) (for Plugin Thermostat only) | Info | string
| THERMOSTAT_HUMIDITY | Room humidity thermostat (Not managed by Mobile Application) | Info | numeric
| HUMIDITY_SETPOINT | Set humidity (Not managed by Mobile Application) | Info | slider
| THERMOSTAT_SET_SETPOINT | Setpoint thermostat | Action | slider
| THERMOSTAT_SET_MODE | Thermostat Mode (for Plugin Thermostat only) | Action | other
| THERMOSTAT_SET_LOCK | Locking Thermostat (for Plugin Thermostat only) | Action | other
| THERMOSTAT_SET_UNLOCK | Unlock Thermostat (for Plugin Thermostat only) | Action | other
| HUMIDITY_SET_SETPOINT | Set humidity (Not managed by Mobile Application) | Action | slider

| **Fan (id: Fan)** | | | |
|:--------|:----------------|:--------:|:---------:|
| FAN_SPEED_STATE | Fan speed Status (Not managed by Mobile Application) | Info | numeric
| ROTATION_STATE | State Rotation (Not managed by Mobile Application) | Info | numeric
| FAN_SPEED | Fan speed (Not managed by Mobile Application) | Action | slider
| ROTATION | Rotation (Not managed by Application Mobile) | Action | slider

| **Pane (id: Shutter)** | | | |
|:--------|:----------------|:--------:|:---------:|
| FLAP_STATE | Status pane | Info | binary, numeric
| FLAP_BSO_STATE | BSO Status pane | Info | binary, numeric
| FLAP_UP | Pane Up Button | Action | other
| FLAP_DOWN | Pane Down Button | Action | other
| FLAP_STOP | Stop button shutter | Action |
| FLAP_SLIDER | Slider Button Pane | Action | slider
| FLAP_BSO_UP | BSO pane Up button | Action | other
| FLAP_BSO_DOWN | BSO pane Down button | Action | other