# Widgets

A widget is the graphical representation of a command on the dashboard or in the mobile version. The Jeedom Core automatically assigns a widget based on the command’s type *(Info or Action)* and subtype *(Binary, Numeric, Other, Slider, etc.)*. You can select a different one from those available via the command’s advanced settings, under the “Display” tab → “**Widget**”.

## Default widgets

Here are the widgets built into the Jeedom Core, their uses, and their customization settings.

### Commands

Most widgets offer **optional settings** that allow you to customize their appearance without creating a custom widget: color, scale, behavior, etc. These settings are configured in the command’s advanced settings, under the “Display” tab → “**Optional Widget Settings**” section, in the form of name/value pairs. The available settings vary depending on the selected widget and are listed below.

The ** setting`time`** (`duration`/`date`) is common to all widgets *(except HygroThermograph)* and displays, respectively, the elapsed time or the date of the last value change.

#### Info / Binary

| Widget | Description | Optional settings |
|--------|-------------|----------------------|
| Shutter | Visual representation of a shutter with position shown as a percentage | `color` |
| Alert | Green checkmark (ON) / Red alert (OFF) | |
| Port | Green (ON) when port is closed / red (OFF) when port is open | |
| Flood | Green water drop with a line through it (ON) / blue water drop (OFF) | |
| Heat | Red flame (ON) / cross (OFF) | |
| Icon | Green checkmark (ON) / red X (OFF) | |
| Light | Light bulb on (yellow) / light bulb off | |
| Line | Green checkmark (ON) / red X (OFF), inline display with name | |
| Lock | Lock closed (ON) / red open lock (OFF) | |
| Presence | Green checkmark (ON) / red motion icon (OFF) | |
| Outlet | Outlet icon (ON) / cross (OFF) | |
| Window | Green (ON) when closed / red (OFF) when open | |

#### Info / Digital

| Widget | Description | Optional settings |
|--------|-------------|----------------------|
| Badge | Value displayed in a colored badge | `color`, `fontcolor` |
| Compass | Compass showing direction in degrees | `needle_color`, `ns_color`, `oe_color`, `scale` |
| Gauge | Arc-shaped gauge | `color` |
| Horizontal | Horizontal progress bar | `color` |
| Hygrothermograph | Combined temperature and humidity display *(multi-function widget, without commands `time`)* | `scale` |
| Light | Lightbulb icon (on/off depending on the value) with value and unit | |
| Line | Name, value, and unit displayed on a single line (compact format) | |
| Rain | Water level or precipitation | `color`, `scale`, `showRange`, `animate` |
| Shutter | Shutter with position slider (in %) | `color`, `invert` |
| Tile | Name displayed as a title above the value and unit | |
| Vertical | Vertical progress bar | `color` |
| HeatPiloteWire | 4-state control wire: comfort, frost protection, eco, off | |
| HeatPiloteWireQubino | Qubino control wire: 6 settings—comfort, eco, frost protection, off | |

#### Info / Other

| Widget | Description | Optional settings |
|--------|-------------|----------------------|
| Badge | Text value in a colored badge | `color`, `fontcolor` |
| ButtonImage | Button that opens a modal window displaying the image whose URL is the value of the command | |
| Color | Displays the color corresponding to a hexadecimal code | `showValue` |
| Line | Name and text value displayed on a single line (compact format) | |
| Multiline | Multi-line text value with scrolling | `maxHeight`, `minHeight`, `backgroundColor` |
| Tile | Name displayed as a title above the text value | |

#### Action / Color

| Widget | Description | Optional settings |
|--------|-------------|----------------------|
| Default | Full color picker (color wheel + hex value) | |
| Picker | Simplified Color Picker | |

#### Action / Default

| Widget | Description | Optional settings |
|--------|-------------|----------------------|
| Alert | Bell icon indicating the status of the associated command (red = active, green with a line through it = inactive) | |
| BinaryDefault | Icon indicating the status of the associated command (green checkmark = active, red X = inactive) | |
| BinarySwitch | ON/OFF toggle switch | `color`, `color_switch` |
| BtnAlert | Button with a bell icon indicating the status of the associated command | |
| Button | Simple action button | |
| Circle | Solid circle (ON) / empty circle (OFF) | |
| Fan | Fan (ON) / cross (OFF) | |
| Garage | Closed garage (green) / Open garage (red) | |
| Light | Light bulb on (yellow) / light bulb off | |
| Lock | Lock closed (ON) / orange lock open (OFF) | |
| Outlet | Outlet icon (ON) / cross (OFF) | |
| Sprinkle | Blue sprinkler (ON) / cross (OFF) | |
| Toggle | Switch: yellow (ON) / off (OFF) | |
| ToggleLine | Yellow on/off switch, inline display | |

#### Action / Slider

| Widget | Description | Optional settings |
|--------|-------------|----------------------|
| Button | Slider with + and − buttons for precise adjustment | `step`, `width` |
| Shutter | Specialized slider for adjusting a shutter's position | `color`, `step`, `invert` |
| Slider | Horizontal slider | `color`, `step` |
| SliderVertical | Vertical Slider | `color`, `step` |
| Value | Direct numeric input field | `color`, `step`, `noslider`, `width` |
| Light | Light bulb on (yellow) / light bulb off | |

#### Action / List

| Widget | Description | Optional settings |
|--------|-------------|----------------------|
| Select | Drop-down list of predefined values | |

#### Action / Message

| Widget | Description | Optional settings |
|--------|-------------|----------------------|
| Input | Form for sending a message with a subject and body | `title`, `title_placeholder`, `title_possibility_list`, `title_disable`, `message_placeholder`, `message`, `message_disable` |

### Equipment

Devices (or tiles) have certain configuration settings that can be accessed via the device's advanced settings, under the "Display" tab → "**Optional tile settings**":

- **dashboard_class/mobile_class**: allows you to add a class to the device. For example `col2` for mobile devices will double the width of the widget.

## Custom widgets

The Widgets page, accessible via the **Tools → Widgets** menu, allows you to add custom widgets in addition to those available by default in Jeedom.

There are two types of custom widgets:

- *Core* widgets based on a template. These widgets are managed by the Jeedom Core and are therefore maintained by the development team. Their compatibility with future updates to Jeedom is guaranteed.
- *Third-party* widgets based on user-provided code. Unlike Core widgets, the Jeedom development team has no control over the code embedded in these widgets, so their compatibility with future updates is not guaranteed. These widgets must therefore be maintained by the user.

### Management

![Widgets](../images/widgets.png)

You have four options:
- **Add**: Allows you to add a *Core* widget.
- **Import**: Allows you to import a widget from a previously exported JSON file.
- **Code**: Opens the *Third-Party* widgets editing page.
- **Replace**: Opens a window that allows you to replace one widget with another on all devices using it.

### My widgets

In this section, you'll find all the widgets you've created, organized by type.

![My Widgets](../images/widgets1.png)

>**INFORMATION**
>
>You can open a widget by doing the following:
>- `Clic` on one of them.
>- `Ctrl+Clic` or `Clic+Centre` to open it in a new browser tab.

The search engine allows you to filter the display of widgets based on various criteria (name, type, subtype, etc.). The button `Echap` Cancel search.

![Search for Widgets](../images/widgets2.png)

To the right of the search field are three buttons that appear in several places in Jeedom:

- **The cross** to cancel the search.
- **The open folder** to expand all panels and display the widgets.
- **The folder is closed** to collapse all panels and hide the widgets.

Once on a widget's configuration page, a context menu is available to the `Clic Droit` on the widget tabs. You can also use a `Ctrl+Clic` or `Clic+Centre` to open another widget directly in a new browser tab.

### Creating a widget

Once you're on the **Tools → Widgets** page, click the "**Add**" button and give your new widget a name.

Next:
- You choose whether it applies to an action command of type **Action** or an info command of type **Info**.
- Based on your previous selection, you will need to **select the subtype** of the command.
- Finally, **the template** from among those that will be available based on your previous selections.
- Once you've selected a template, Jeedom displays its configuration options below.

### Templates

#### Defining a template

Simply put, it’s code (HTML/JS) built into the Core, parts of which can be configured by the user via the **Widgets** menu’s graphical interface. Based on this same foundation and taking into account the elements you enter in the template, the Core will generate unique widgets that match the display you want to achieve.

Depending on the type of widget, you can usually customize the icons, add images of your choice, and/or embed HTML code.

There are two types of templates:

- The "**simple**" ones: such as an icon/image for "**ON**" and an icon/image for "**OFF**".
- "**Multistates**": This allows you to define, for example, one image if the command value is "**XX**" and another if it is greater than "**YY**" or less than "**ZZ**". This also works for text values: one image if the value is "**toto**," another if it is "**plop**," and so on...

#### Replacement

This is what's called a simple template. Here, you just need to specify that "**ON**" corresponds to a certain icon or image *(using the "Choose" button)*, "**OFF**" to another icon or image, and so on...

The available replacement Core types are:

| Widget | Types/Subtypes |
|--------|-----------------|
| Icon Template | Info/Binary, Action/Default, Action/Slider |
| Iconline Template | Info/Binary, Action/Default |
| Image Template | Info/Binary, Action/Default, Action/Slider |

The **Time widget** field, if available, is equivalent to the setting `time: duration` (see [Commands](#Commandes)).

For templates that use images, you can set the widget width in pixels based on the device type (**Desktop width** & **Mobile width**). Different images can also be selected depending on the active Jeedom theme *(light or dark)*.

>**INFORMATION**
>
>Advanced users can include tags in the placeholder values and specify their values in the command's advanced settings.
>For example, if you enter a value for **Desktop Width** `#largeur_desktop#` (**be sure to include the** `#` **around**), then in the advanced settings for a command, under the Display tab → "**Optional widget settings**," add the parameter `largeur_desktop` (**excluding the** `#`) and set it to "**90**," this custom widget on that command will be 90 pixels wide. This allows you to adjust the widget's size for each command without having to create a specific widget each time.

#### Test

These are known as multistate templates *(multiple states)*. Instead of setting an image for "**ON**" and/or "**OFF**" as in the previous example, you will assign an icon based on whether a condition *(test)* is met. If the condition is true, the widget will display the icon or image in question.

The available multi-state Core widgets of type multistate are:

| Widget | Types/Subtypes |
|--------|-----------------|
| Multistate Template | Information/Digital, Information/Other |
| Multistateline Template | Info/Other |

As before, different images can be selected based on the active theme in Jeedom, and the **Time widget** checkbox displays the time elapsed since the last status change.

The tests are in the following format: `#value# == 1`, `#value#` will be automatically replaced by the current value of the command. You can also do, for example:

- `#value# > 1`
- `#value# >= 1 && #value# <= 5`
- `#value# == 'toto'`

>**IMPORTANT**
>
>It is essential to include apostrophes (**'**) around the text to be compared if the value is text *(info/other)*.

>**INFORMATION**
>
>You can display the command value in the widget by specifying `#value#` in the test HTML code. To display the unit, add `#unite#`.\
>For advanced users, it is also possible to use JavaScript functions such as `#value#.match("^plop")` to check if the text begins with `plop`.

## Widget code

### Tags

In code mode, you have access to various tags for commands. Here is a list (not necessarily exhaustive):

- **#name#**: command name
- **#valueName#**: name of the command value, and = #name# when it is an info-type command
- **#minValue#**: the minimum value the command can take (if the command is of the slider type)
- **#maxValue#**: the maximum value the command can take (if the command is of the slider type)
- **#hide_name#**: empty or "hidden" if the user has requested that the widget's name be hidden; to be placed directly within a class tag
- **#id#**: command ID
- **#state#**: command value; empty for an action-type command if it is not linked to a state command
- **#uid#**: unique identifier for this generation of the widget (if the same command appears multiple times—as is the case with designs—only this identifier is truly unique)
- **#valueDate#**: date of the command value
- **#collectDate#**: command pickup date
- **#alertLevel#**: alert level (none, warning, danger)
- **#hide_history#**: Whether or not the history (max, min, average, trend) should be hidden. As with #hide_name#, it can be empty or set to "hidden," and can therefore be used directly in a class. IMPORTANT: If this tag is not found on your widget, then the tags #minHistoryValue#, #averageHistoryValue#, #maxHistoryValue#, and #trend# will not be replaced by Jeedom.
- **#minHistoryValue#**: minimum value over the period (period defined by the user in the Jeedom configuration)
- **#averageHistoryValue#**: average value over the period (period defined by the user in the Jeedom configuration)
- **#maxHistoryValue#**: maximum value over the period (period defined by the user in the Jeedom configuration)
- **#trend#**: trend over the period (period defined by the user in Jeedom's configuration). Note that "trend" is directly a class for icons: fas fa-arrow-up, fas fa-arrow-down, or fas fa-minus

### Updating Values

When a new value is received, Jeedom checks the page to see if the command is there and checks jeedom.cmd.update to see if there is an update function. If so, it calls it with a single argument, which is an object in the following format:

```
{display_value: '#state#', valueDate: '#valueDate#', collectDate: '#collectDate#', alertLevel: '#alertLevel#'}
```

Here's a simple example of JavaScript code to put in your widget:

```
<script>
    jeedom.cmd.addUpdateFunction('#id#', function(_options) {
      if (is_object(cmd = document.querySelector('.cmd[data-cmd_id="#id#"]'))) {
        cmd.setAttribute('title', '{{Date de valeur}}: ' + _options.valueDate + '<br>{{Date de collecte}}: ' + _options.collectDate)
        cmd.querySelector('.value').innerHTML = _options.display_value
        cmd.querySelector('.unit').innerHTML = _options.unit
      }
    }
    jeedom.cmd.refreshValue([{ cmd_id: '#id#', value: '#value#', display_value: '#state#', valueDate: '#valueDate#', collectDate: '#collectDate#', alertLevel: '#alertLevel#', unit: '#unite#' }])
</script>
```

Here are two important points:

```
jeedom.cmd.addUpdateFunction('#id#', function(_options) {
  if (is_object(cmd = document.querySelector('.cmd[data-cmd_id="#id#"]'))) {
    cmd.setAttribute('title', '{{Date de valeur}}: ' + _options.valueDate + '<br>{{Date de collecte}}: ' + _options.collectDate)
    cmd.querySelector('.value').innerHTML = _options.display_value
    cmd.querySelector('.unit').innerHTML = _options.unit
  }
}
```
This function is called when the widget is updated. It then updates the HTML code of the widget_template.

```
jeedom.cmd.refreshValue([{ cmd_id: '#id#', value: '#value#', display_value: '#state#', valueDate: '#valueDate#', collectDate: '#collectDate#', alertLevel: '#alertLevel#', unit: '#unite#' }])
```
Calling this function initializes the widget.

### Examples

You'll find [here](https://github.com/Jeedom/core/tree/master/core/template) Examples of widgets (in the dashboard and mobile folders)

## ON/OFF icon toggle

Regarding widgets for switches *(on/off, turn on/off, open/close, etc.)*, it may be considered more visually appealing to display only an icon reflecting the status of the device being controlled.

This feature works with both default widgets and custom widgets.

To do this, you need to consider two prerequisites:

- The **2 action/default commands** must be linked to an **info/binary** command that will store the device's current status.

>**Example**
>![ToggleLink Widget](../images/widgets5.png)

>**Tip**
>Uncheck the *"Show"* box for the info/binary command that does not need to be displayed.

- In order for the Jeedom Core to be able to identify which command corresponds to which action, it is essential to follow the naming convention below for the **2 action/default commands**:
```
    'on':'on',
    'off':'off',
    'monter':'on',
    'descendre':'off',
    'ouvrir':'on',
    'ouvrirStop':'on',
    'ouvert':'on',
    'fermer':'off',
    'activer':'on',
    'desactiver':'off',
    'désactiver':'off',
    'lock':'on',
    'unlock':'off',
    'marche':'on',
    'arret':'off',
    'arrêt':'off',
    'stop':'off',
    'go':'on'
```

>**INFORMATION**
>
>As long as the standardized name remains readable, you can adapt the naming convention—for example, *open_shutter* or *close_shutter*, *on_2* and *off_2*, etc.
