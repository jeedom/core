# 3D Design
**Home → Design3D**

This page lets you create a 3D view of your home that can respond based on the status of various data points in your home automation system.


> **Tip**
>
> You can go directly to a 3D design using the submenu.

## Importing the 3D model

> **IMPORTANT**
>
> You cannot create your 3D model directly in Jeedom; you must do so using third-party software. We recommend the excellent SweetHome3D (http://www.sweethome3d.com/fr/).

Once you’ve created your 3D model, you’ll need to export it in OBJ format. If you’re using SweetHome3D, you can do this by going to the “3D View” menu and selecting “Export to OBJ.” Next, gather all the generated files and place them in a ZIP file (there may be a lot of files due to the textures).

> **IMPORTANT**
>
> The files must be in the root directory of the ZIP file, not in a subfolder.

> **WARNING**
>
> A 3D model can be quite large (it may be several hundred MB). The larger it is, the longer it will take to render in Jeedom.

Once you’ve exported your 3D model, you’ll need to create a new 3D design in Jeedom. To do this, switch to edit mode by clicking the small pencil icon on the right, then click the + button, give the new 3D design a name, and confirm.

Jeedom will automatically switch to the new 3D design; you’ll need to go back to edit mode and click on the little cogwheel icons.

From this screen, you can:

- Rename your design
- Add an access code
- Choose an icon
- Import Your 3D Model

Click the "Send" button under "3D Model" and select your ZIP file

> **WARNING**
>
> Jeedom allows you to import a file up to 150 MB in size!

> **WARNING**
>
> A ZIP file is required.

> **Tip**
>
> Once the file has been imported (this may take a while, depending on the file size), you'll need to refresh the page to see the result (F5).


## Configuring Components

> **IMPORTANT**
>
> Configuration can only be performed in edit mode.

To configure an element in the 3D design, double-click the element you want to configure. This will open a window where you can:

- Specify a link type (currently, only “Equipment” is available)
- Enter the link to the item in question. For now, you can only enter a link to a device here. This allows the device to appear when you click on the item.
- Define the specific type: there are several options, which we’ll look at next; this allows you to specify the type of device and, consequently, the information displayed

### Lighting

- Status: The light status command can be binary (0 or 1), numerical (0 to 100%), or color-based
- Power: bulb wattage (note that this may not reflect actual conditions)

### Text

- Text: text to display (you can include commands here; the text will automatically update when the command changes)
- Text size
- Text color
- Text transparency: from 0 (invisible) to 1 (visible)
- Background color
- Background transparency: from 0 (invisible) to 1 (visible)
- Border color
- Border transparency: from 0 (invisible) to 1 (visible)
- Spacing above the object: specifies the spacing of the text relative to the element

### Door/Window

#### Door/Window

- Status: Port/Window status; 1 = closed, 0 = open
- Rotation
	- Enable: Enables the door/window to rotate when opened
	- Opening: It's best to test it to make sure it fits your door or window
- Translation
	- Enable: Enables translation when opening (Sliding Door/Window type)
	- Direction: the direction in which the port/window should move (you have up/down/right/left)
	- Repeat: By default, the port/window moves a distance equal to its size in the specified direction, but you can increase this value
- Hide when the Door/Window is open
	- Enable: Hides the item if the port/window is open
- Color
	- Open color: If checked, the item will take on this color when the port or window is open
	- Closed color: If checked, the item will take on this color when the port/window is closed

#### Shutter

- Status: shutter status; 0 = open, other values = closed
- Hide when the shutter is open
	- Enable: Hides the element if the shutter is open
- Color
	- Closed color: If checked, the item will take on this color when the shutter is closed

### Conditional color

If the condition is true, this sets the element to the selected color. You can add as many colors and conditions as you like.

> **Tip**
>
> The conditions are evaluated in order; the first one that is true will be selected, so the subsequent ones will not be evaluated
