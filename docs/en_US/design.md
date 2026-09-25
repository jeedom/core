# Design
**Home → Design**

This page allows you to configure the display of your entire home automation system in great detail.
This takes time, but the only limit is your imagination.

> **Tip**
>
> You can go directly to a design using the submenu.

> **Important**
>
> All actions are performed by right-clicking on this page; be sure to do so within the design area. When creating the page, you must therefore right-click in the middle of the page (to ensure you are within the design area).

In the menu (right-click), you'll find the following actions:

- **Designs**: Displays a list of your designs and lets you access them.
- **Edit**: Switches to edit mode.
- **Full Screen**: Allows you to use the entire web page, which will remove the Jeedom menu at the top.
- **Add Chart**: Allows you to add a chart.
- **Add text/HTML**: Allows you to add text or HTML/JavaScript code.
- **Add Scenario**: Allows you to add a scenario.
- **Add link**
    - **To a view**: Allows you to add a link to a view.
    - **To a design**: Allows you to add a link to another design.
- **Add Device**: Allows you to add a device.
- **Add Command**: Allows you to add a command.
- **Add Image/Camera**: Allows you to add an image or a camera feed.
- **Add Zone**: Allows you to add a clickable transparent zone that can execute a series of actions when clicked (depending or not on the state of another command).
- **Add summary**: Adds information from an object summary or general summary.
- **Display**
    - **None**: Does not display any schedule.
    - **10x10**: Displays a 10-by-10 grid.
    - **15x15**: Displays a 15-by-15 grid.
    - **20x20**: Displays a 20-by-20 grid.
    - **Magnetize the pieces**: Add magnetism between the pieces to make them stick together more easily.
    - **Snap to Grid**: Snaps elements to the grid (note: depending on the element’s zoom level, this feature may work to a greater or lesser extent).
    - **Hide element highlights**: Hides the highlights around elements.
- **Remove the design**: Removes the design.
- **Create a Design**: Allows you to add a new design.
- **Duplicate Design**: Duplicates the current design.
- **Configure the design**: Access the design configuration.
- **Save**: Allows you to save the design (note: automatic saves also occur during certain actions).

> **Important**
>
> To configure the design elements, simply click on them.

## Design Configuration

Here you will find:

- **General**
    - **Name**: The name of your design.
    - **Position**: The design's position in the menu. Allows you to reorder the designs.
    - **Transparent Background**: Makes the background transparent. Note that if this box is checked, the background color is not used.
    - **Background Color**: The background color of the design.
    - **Access code**: Access code for your design (if left blank, no code is required).
    - **Icon**: An icon for this one (appears in the design selection menu).
    - **Image**
        - **Upload**: Allows you to add a background image to the design.
        - **Delete image**: Deletes the image.
- **Sizes**
    - **Size (WxH)**: Sets the size of your design in pixels.

## General Configuration of Components

> **Note**
>
> Options may vary depending on the type of component.

### Common Display Settings

- **Depth**: Allows you to select the depth level
- **Position X (%)**: The element's horizontal coordinate.
- **Y Position (%)**: The element's vertical coordinate.
- **Width (px)**: Width of the element in pixels.
- **Height (px)**: The height of the element in pixels.

### Delete

Allows you to delete the item

### Duplicate

Allows you to duplicate the item

### Lock

Locks the element so that it can no longer be moved or resized.

## Graph

### Specific display settings

- **Period**: Allows you to select the display period
- **Show Caption**: Displays the caption.
- **Show Navigator**: Displays the navigator (the second, lighter-colored graph below the first one).
- **Show the period selector**: Displays the period selector in the upper-left corner.
- **Show the scroll bar**: Displays the scroll bar.
- **Transparent Background**: Makes the background transparent.
- **Border**: Allows you to add a border; note that the syntax is HTML (note: you must use CSS syntax, for example: solid 1px black).

### Advanced Settings

Allows you to select the commands to plot.

## Text/html

### Specific display settings

- **Icon**: Icon displayed next to the Design name.
- **Background Color**: Allows you to change the background color or make it transparent; don't forget to set "Default" to NO.
- **Text Color**: Allows you to change the color of icons and text (be sure to set "Default" to "No").
- **Round Corners**: Allows you to round corners (don't forget to include the percentage, e.g., 50%).
- **Border**: Allows you to add a border. Please note that the syntax is HTML (you must use CSS syntax, for example: solid 1px black).
- **Font size**: allows you to change the font size (e.g., 50%; be sure to include the % sign).
- **Text Alignment**: allows you to choose the text alignment (left/right/centered).
- **Bold**: makes the text bold.
- **Text**: HTML text that will appear within the element.

> **Important**
>
> If you add HTML code (especially JavaScript), be sure to check it carefully beforehand, because if there’s an error in it or if it overwrites a Jeedom component, it could completely break the layout, and your only option will be to delete it directly from the database.

## Scenario

*No specific display settings*

## Link

### Specific display settings

- **Name**: Link name (display text).
- **Link**: Link to the design or view in question.
- **Background Color**: Allows you to change the background color or set it to transparent; don't forget to set "Default" to NO.
- **Text Color**: Allows you to change the color of icons and text (be sure to set "Default" to "No").
- **Round the corners (don't forget to include the %, e.g., 50%)**: Allows you to round the corners; don't forget to include the %.
- **Border (note CSS syntax, e.g., solid 1px black)**: Allows you to add a border; note that the syntax is HTML.
- **Font size (e.g., 50%; be sure to include the % sign)**: Allows you to change the font size.
- **Text Alignment**: Allows you to choose the text alignment (left/right/center).
- **Bold**: Makes the text bold.

## Equipment

### Specific display settings

- **Show object name**: Check this box to display the name of the device's parent object.
- **Hide Name**: Check this box to hide the device name.
- **Background Color**: Lets you choose a custom background color, display the device with a transparent background, or use the default color.
- **Text Color**: Allows you to choose a custom background color or use the default color.
- **Rounded**: The pixel value for rounding the corners of the device tile.
- **Border**: CSS definition of a border for the device tile. Example: 1px solid black.
- **Opacity**: Opacity of the device tile, between 0 and 1. Note: A background color must be specified.
- **Custom CSS**: CSS rules to apply to the device.
- **Apply custom CSS to**: CSS selector to which the custom CSS should be applied.

### Commands

The list of commands available on the device allows you, for each command, to:
- Hide the command name.
- Hide the command.
- Display the command with a transparent background.

### Advanced Settings

Displays the device's advanced configuration window (see the **Home Automation Summary** documentation).

## Command

*No specific display settings*

### Advanced Settings

Displays the device's advanced configuration window (see the **Home Automation Summary** documentation).

## Image/Camera

### Specific display settings

- **Display**: Sets what you want to display—a still image or a live feed from a camera.
- **Image**: Allows you to send the image in question (if you have selected an image).
- **Camera**: Camera to display (if you selected a camera).

## Zone

### Specific display settings

- **Zone Type**: This is where you select the zone type: Simple Macro, Binary Macro, or Hover Widget.

### Simple macro

In this mode, clicking on the area triggers one or more actions. All you need to do here is specify the list of actions to be performed when the area is clicked.

### Binary macro

In this mode, Jeedom will execute the On or Off action(s) depending on the state of the command you specify. For example: if the command value is 0, Jeedom will execute the On action(s); otherwise, it will execute the Off action(s).

- **Binary information**: A command that specifies the status to be checked in order to determine the action to be taken (On or Off).

Simply enter the actions to be performed for "On" and "Off" below.

### Hover widget

In this mode, when you hover over or click within the Jeedom area, the widget in question will be displayed.

- **Equipment**: Widget to display when hovering over or clicking.
- **Show on hover**: If checked, displays the widget when the mouse hovers over it.
- **Show on click**: If checked, the widget is displayed when clicked.
- **Position**: Allows you to choose where the widget appears (default: bottom right).

## Summaries

### Specific display settings

- **Link**: Specifies which summary to display (select "General" for the overall view; otherwise, specify the object).
- **Background Color**: Allows you to change the background color or set it to transparent; don't forget to set "Default" to NO.
- **Text Color**: Allows you to change the color of icons and text (be sure to set "Default" to "No").
- **Round the corners (don't forget to include the %, e.g., 50%)**: Allows you to round the corners; don't forget to include the %.
- **Border (note CSS syntax, e.g., solid 1px black)**: Allows you to add a border; note that the syntax is HTML.
- **Font size (e.g., 50%; be sure to include the % sign)**: Allows you to change the font size.
- **Bold**: Makes the text bold.

## FAQ

>**I can't edit my design anymore**
>If you've added a widget or image that takes up almost the entire layout, you'll need to click outside the widget or image to access the right-click menu.

>**Delete a design that no longer works**
>In the Administration section, under OS/DB, run "select * from planHeader," retrieve the ID of the design in question, and then run "delete from planHeader where id=#TODO#" and "delete from plan where planHeader_id=#todo#," making sure to replace #TODO# with the design ID you found earlier.
