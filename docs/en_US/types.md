# Types of Equipment
**Tools → Equipment Types**

Sensors and actuators in Jeedom are managed by plugins, which create devices with *Info* (sensor) or *Action* (actuator) commands. This then allows you to trigger actions based on changes in certain sensors, such as turning on a light when motion is detected. However, the Jeedom Core, as well as plugins such as *Mobile*, *Homebridge*, *Google Smart Home*, *Alexa Smart Home*, etc., do not recognize what these devices are: a power outlet, a light, a roller shutter, etc.

To address this issue, particularly with voice assistants (*Turn on the living room light*), the Core introduced **Generic Types** a few years ago, which are used by these plugins.

This makes it possible to identify a device by *the light in the room*, for example.

Most of the time, generic types are set automatically when you configure your module (e.g., when adding it via Z-Wave). However, you may occasionally need to reconfigure them. You can configure these generic types directly in certain plugins, or via a command in the plugin’s *Advanced Configuration* menu.

This page allows you to configure these Generic Types in a more direct and straightforward way, and even offers automatic assignment once the devices have been assigned correctly.

![Types of Equipment](../images/coreGenerics.gif)

## Type of equipment

This page organizes devices by type: Outlet, Light, Shutter, Thermostat, Camera, etc. Initially, most of your devices will be classified under **Devices without a type**. To assign them a type, you can either move them to another category or right-click on the device to move it directly. The “Device Type” isn’t particularly useful on its own; what matters most is the “Command Type.” This means you can have a device with no type, or one whose type doesn’t necessarily match its commands. You can, of course, mix command types within a single device. For now, this is more of a way to organize things logically, which may prove useful in future versions.

> **Tip**
>
> - When you move a device to the **Devices without a type** section, Jeedom prompts you to remove the generic types from its commands.
> - You can select multiple devices at once by checking the checkboxes to the left of them.

## Command Type

Once a device is assigned to the correct *type*, clicking on it will take you to a list of its commands, which are color-coded to indicate whether they are *Info* (blue) or *action* (orange).

When you right-click on a command, you can assign it a Generic Type that matches the specifications of that command (Info/Action type, Numeric or Binary subtype, etc.).

> **Tip**
>
> - The command context menu displays the device type in bold, but still allows you to assign any Generic Type to any device type.

Each device has two buttons:

- **Auto Types**: This feature opens a window that suggests appropriate Generic Types based on the device type, command specifics, and device name. You can then refine the suggestions and deselect certain commands before accepting or rejecting them. This feature is compatible with selection via checkboxes.

- **Reset Types**: This function removes Generic Types from all device commands.

> **Warning**
>
> No changes are made until you save them using the button in the upper-right corner of the page.

## Generic Types and Scenarios

In v4.2, the Core has integrated generic types into scenarios. This allows you to trigger a scenario if a light turns on in a room, if motion is detected in the house, turn off all the lights, or close all the shutters with a single action, and so on. Furthermore, if you add a device, you simply need to specify the correct types for its commands; there will be no need to modify such scenarios.

#### Trigger

You can trigger a scenario using sensors. For example, if you have motion detectors in your home, you can create an alarm scenario with each detector as a trigger: `#[Salon][Move Salon][Presence]# == 1`, `#[Cuisine][Move Cuisine][Presence]# == 1`, etc. In a scenario like this, you’ll need all your motion detectors, and if you add one, you’ll have to add it to the triggers. Makes sense.

With generic types, you can use a single trigger: `#genericType(PRESENCE)# == 1`. Here, no specific object is specified, so even the slightest movement anywhere in the house will trigger the scenario. If you add a new sensor to the house, there’s no need to edit the scenario(s).

Here is a trigger to turn on a light in the living room: `#genericType(LIGHT_STATE,#[Salon]#)# > 0`

#### Expression

If you want to check, within a scenario, whether a light is on in the living room, you can do the following:

IF `#[Salon][Lumiere Canapé][Etat]# == 1 OU #[Salon][Lumiere Salon][Etat]# == 1 OU #[Salon][Lumiere Angle][Etat]# == 1`

Or, more simply: IF `genericType(LIGHT_STATE,#[Salon]#) > 0` that is, if one or more lights are on in the living room.

If you add a light to your living room tomorrow, there’s no need to edit your scenarios!


#### Action

If you want to turn on all the lights in the living room, you can create an action for each light:

```
#[Salon][Lumiere Canapé][On]#
#[Salon][Lumiere Salon][On]#
#[Salon][Lumiere Angle][On]#
```

Or, more simply, create an action `genericType` with `LIGHT_ON` in `Salon`. If you add a light to your living room tomorrow, there’s no need to edit your scenarios!


## List of Core Generic Types

> **Tip**
>
> - You can find this list directly in Jeedom, on this same page, by clicking the **List** button in the top right corner.

| **Other (id: Other)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TIMER | Timer Status | Info | numeric
| TIMER_STATE | Timer status (paused or not) | Info | binary, numeric
| SET_TIMER | Timer | Action | slider
| TIMER_PAUSE | Pause timer | Action | other
| TIMER_RESUME | Resume Timer | Action | other

| **Battery (id: Battery)** | | | |
|:--------|:----------------|:--------:|:---------:|
| BATTERY | Battery | Info | numeric
| BATTERY_CHARGING | Battery charging | Info | binary

| **Camera (id: Camera)** | | | |
|:--------|:----------------|:--------:|:---------:|
| CAMERA_URL | Camera URL | Info | string
| CAMERA_RECORD_STATE | Camera recording status | Info | binary
| CAMERA_UP | Move camera up | Action | other
| CAMERA_DOWN | Camera moves down | Action | other
| CAMERA_LEFT | Camera moves to the left | Action | other
| CAMERA_RIGHT | Camera moves to the right | Action | other
| CAMERA_ZOOM | Zoom camera forward | Action | other
| CAMERA_DEZOOM | Zoom out | Action | other
| CAMERA_STOP | Stop camera | Action | other
| CAMERA_PRESET | Camera Preset | Action | other
| CAMERA_RECORD | Camera recording | Action |
| CAMERA_TAKE | Camera snapshot | Action |

| **Heating (id: Heating)** | | | |
|:--------|:----------------|:--------:|:---------:|
| HEATING_STATE | Pilot-wire heating status | Info | binary
| HEATING_ON | Pilot-wire heating ON button | Action | other
| HEATING_OFF | Pilot-wire heating OFF button | Action | other
| HEATING_OTHER | Pilot-wire heating Button | Action | other

| **Electricity (id: Electricity)** | | | |
|:--------|:----------------|:--------:|:---------:|
| POWER | Electrical Power | Info | numeric
| CONSUMPTION | Electricity Consumption | Info | numeric
| VOLTAGE | Voltage | Info | numeric
| REBOOT | Restart | Action | other

| **Environment (id: Environment)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TEMPERATURE | Temperature | Info | numeric
| AIR_QUALITY | Air Quality | Info | numeric
| BRIGHTNESS | Brightness | Info | numeric
| PRESENCE | Presence | Info | binary
| SMOKE | Smoke Detection | Info | binary
| HUMIDITY | Humidity | Info | numeric
| UV | UV | Info | numeric
| CO2 | CO2 (ppm) | Info | numeric
| CO | CO (ppm) | Info | numeric
| NOISE | Sound (dB) | Info | numeric
| PRESSURE | Pressure | Info | numeric
| WATER_LEAK | Water Leak | Info |
| FILTER_CLEAN_STATE | Filter status | Info | binary

| **Generic (id: Generic)** | | | |
|:--------|:----------------|:--------:|:---------:|
| DEPTH | Depth | Info | numeric
| DISTANCE | Distance | Info | numeric
| BUTTON | Button | Info | binary, numeric
| GENERIC_INFO |  Generic | Info |
| GENERIC_ACTION |  Generic | Action | other

| **Light (id: Light)** | | | |
|:--------|:----------------|:--------:|:---------:|
| LIGHT_STATE | Light Status | Info | binary, numeric
| LIGHT_BRIGHTNESS | Light Brightness | Info | numeric
| LIGHT_COLOR | Light Color | Info | string
| LIGHT_STATE_BOOL | Light Status (Binary) | Info | binary
| LIGHT_COLOR_TEMP | Light Color Temperature | Info | numeric
| LIGHT_TOGGLE | Light Toggle | Action | other
| LIGHT_ON | Light On Button | Action | other
| LIGHT_OFF | Light Off Button | Action | other
| LIGHT_SLIDER | Light Slider | Action | slider
| LIGHT_SET_COLOR | Light Color | Action | color
| LIGHT_MODE | Light Mode | Action | other
| LIGHT_SET_COLOR_TEMP | Light Color Temperature | Action |

| **Mode (id: Mode)** | | | |
|:--------|:----------------|:--------:|:---------:|
| MODE_STATE | Mode State | Info | string
| MODE_SET_STATE | Change Mode | Action | other

| **Multimedia (id: Multimedia)** | | | |
|:--------|:----------------|:--------:|:---------:|
| VOLUME | Volume | Info | numeric
| MEDIA_STATUS | Status | Info | string
| MEDIA_ALBUM | Album | Info | string
| MEDIA_ARTIST | Artist | Info | string
| MEDIA_TITLE | Title | Info | string
| MEDIA_POWER | Power | Info | string
| CHANNEL | Channel | Info | numeric, string
| MEDIA_STATE | Status | Info | binary
| SET_VOLUME | Volume | Action | slider
| SET_CHANNEL | Channel | Action | other, slider
| MEDIA_PAUSE | Pause | Action | other
| MEDIA_RESUME | Play | Action | other
| MEDIA_STOP | Stop | Action | other
| MEDIA_NEXT | Next | Action | other
| MEDIA_PREVIOUS | Previous | Action | other
| MEDIA_ON | On | Action | other
| MEDIA_OFF | Off | Action | other
| MEDIA_MUTE | Mute | Action | other
| MEDIA_UNMUTE | Unmute | Action | other

| **Weather (id: Weather)** | | | |
|:--------|:----------------|:--------:|:---------:|
| WEATHER_TEMPERATURE | Weather Temperature | Info | numeric
| WEATHER_TEMPERATURE_MAX_2 | Weather conditions for day +1, max day +2 | Info | numeric
| WIND_SPEED | Wind (speed) | Info | numeric
| RAIN_TOTAL | Rain (accumulation) | Info | numeric
| RAIN_CURRENT | Rain (mm/h) | Info | numeric
| WEATHER_CONDITION_ID_4 | Weather condition (ID) D+4 | Info | numeric
| WEATHER_CONDITION_4 | Weather conditions for day 4 | Info | string
| WEATHER_TEMPERATURE_MAX_4 | Weather: High Temperature in 4 Days | Info | numeric
| WEATHER_TEMPERATURE_MIN_4 | Weather: Lowest Temperature on Day +4 | Info | numeric
| WEATHER_CONDITION_ID_3 | Weather condition (ID) day+3 | Info | numeric
| WEATHER_CONDITION_3 | Weather conditions for day +3 | Info | string
| WEATHER_TEMPERATURE_MAX_3 | Weather: Highest Temperature in 3 Days | Info | numeric
| WEATHER_TEMPERATURE_MIN_3 | Weather: Minimum Temperature in 3 Days | Info | numeric
| WEATHER_CONDITION_ID_2 | Weather condition (ID) day+2 | Info | numeric
| WEATHER_CONDITION_2 | Weather conditions for day +2 | Info | string
| WEATHER_TEMPERATURE_MIN_2 | Weather: Minimum Temperature Day +2 | Info | numeric
| WEATHER_HUMIDITY | Weather Humidity | Info | numeric
| WEATHER_CONDITION_ID_1 | Weather condition (ID) for day +1 | Info | numeric
| WEATHER_CONDITION_1 | Weather conditions for day +1 | Info | string
| WEATHER_TEMPERATURE_MAX_1 | Weather: High Temperature for Day +1 | Info | numeric
| WEATHER_TEMPERATURE_MIN_1 | Weather: Lowest Temperature on Day +1 | Info | numeric
| WEATHER_CONDITION_ID | Weather condition (ID) | Info | numeric
| WEATHER_CONDITION | Weather Condition | Info | string
| WEATHER_TEMPERATURE_MAX | Weather: High | Info | numeric
| WEATHER_TEMPERATURE_MIN | Weather: Minimum Temperature | Info | numeric
| WEATHER_SUNRISE | Sunset Weather | Info | numeric
| WEATHER_SUNSET | Weather & Sunrise | Info | numeric
| WEATHER_WIND_DIRECTION | Weather: Wind Direction | Info | numeric
| WEATHER_WIND_SPEED | Weather: Wind Speed | Info | numeric
| WEATHER_PRESSURE | Weather Pressure | Info | numeric
| WIND_DIRECTION | Wind (direction) | Info | numeric

| **Opening (id: Opening)** | | | |
|:--------|:----------------|:--------:|:---------:|
| LOCK_STATE | Lock Status | Info | binary
| BARRIER_STATE | Gate (open) Status | Info | binary
| GARAGE_STATE | Garage (opening) Status | Info | binary
| OPENING | Port | Info | binary
| OPENING_WINDOW | Window | Info | binary
| LOCK_OPEN | Open Button Lock | Action | other
| LOCK_CLOSE | Lock Close Button | Action | other
| GB_OPEN | Gate or garage door open button | Action | other
| GB_CLOSE | Gate or garage door close button | Action | other
| GB_TOGGLE | Toggle switch for gate or garage | Action | other

| **Outlet (id: Outlet)** | | | |
|:--------|:----------------|:--------:|:---------:|
| ENERGY_STATE | Outlet Status | Info | numeric, binary
| ENERGY_ON | On Button Outlet | Action | other
| ENERGY_OFF | Off Button Outlet | Action | other
| ENERGY_SLIDER | Slider Outlet | Action |

| **Robot (id: Robot)** | | | |
|:--------|:----------------|:--------:|:---------:|
| DOCK_STATE | Base State | Info | binary
| DOCK | Return to base | Action | other

| **Security (id: Security)** | | | |
|:--------|:----------------|:--------:|:---------:|
| SIREN_STATE | Siren Status | Info | binary
| ALARM_STATE | Alarm Status | Info | binary, string
| ALARM_MODE | Alarm mode | Info | string
| ALARM_ENABLE_STATE | Alarm Enabled Status | Info | binary
| FLOOD | Flood | Info | binary
| SABOTAGE | Sabotage | Info | binary
| SHOCK | Shock | News | binary, numeric
| SIREN_OFF | Siren Off Button | Action | other
| SIREN_ON | Siren On Button | Action | other
| ALARM_ARMED | Alarm armed | Action | other
| ALARM_RELEASED | Alarm cleared | Action | other
| ALARM_SET_MODE | Alarm Mode | Action | other

| **Thermostat (id: Thermostat)** | | | |
|:--------|:----------------|:--------:|:---------:|
| THERMOSTAT_STATE | Thermostat Status (BINARY) (for Thermostat Plugin only) | Info |
| THERMOSTAT_TEMPERATURE | Thermostat Room Temperature | Info | numeric
| THERMOSTAT_SETPOINT | Thermostat setpoint | Info | numeric
| THERMOSTAT_MODE | Thermostat Mode (for the Thermostat plugin only) | Info | string
| THERMOSTAT_LOCK | Thermostat Lock (for the Thermostat plugin only) | Info | binary
| THERMOSTAT_TEMPERATURE_OUTDOOR | Outdoor Temperature (for Thermostat Plugin only) | Info | numeric
| THERMOSTAT_STATE_NAME | Thermostat State (HUMAN) (for Thermostat Plugin only) | Info | string
| THERMOSTAT_HUMIDITY | Room humidity thermostat | Info | numeric
| HUMIDITY_SETPOINT | Humidity Setpoint | Info | slider
| THERMOSTAT_SET_SETPOINT | Thermostat Setpoint | Action | slider
| THERMOSTAT_SET_MODE | Thermostat Mode (for the Thermostat Plugin only) | Action | other
| THERMOSTAT_SET_LOCK | Thermostat Lock (for Thermostat Plugin only) | Action | other
| THERMOSTAT_SET_UNLOCK | Thermostat Unlock (for Thermostat Plugin only) | Action | other
| HUMIDITY_SET_SETPOINT | Humidity Setpoint | Action | slider

| **Fan (id: Fan)** | | | |
|:--------|:----------------|:--------:|:---------:|
| FAN_SPEED_STATE | Fan Speed Status | Info | numeric
| ROTATION_STATE | Rotation Status | Info | numeric
| FAN_SPEED | Fan speed | Action | slider
| ROTATION | Rotation | Action | slider

| **Shutter (id: Shutter)** | | | |
|:--------|:----------------|:--------:|:---------:|
| FLAP_STATE | Shutter Status | Info | binary, numeric
| FLAP_BSO_STATE | BSO Shutter Status | Info | binary, numeric
| FLAP_UP | Shutter Raise Button | Action | other
| FLAP_DOWN | Shutter Lower Button | Action | other
| FLAP_STOP | Shutter Stop Button | Action |
| FLAP_SLIDER | Slider Button | Action | slider
| FLAP_BSO_UP | BSO Shutter "Up" Button | Action | other
| FLAP_BSO_DOWN | BSO Shutter Lower Button | Action | other
