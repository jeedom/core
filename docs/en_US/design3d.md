This page allows you to create a 3D view of your home that can react depending on the state of the various information in your home automation.

It is accessible by Home → Dashboard

> **Tip**
>
> It is possible to go directly to a 3D design thanks to the submenu.

# Importing the 3D model

> **Important**
>
> You cannot create your 3D model directly in Jeedom, it must be done using third-party software. We recommend the very good SweetHome3d (http://www.sweethome3d.com/fr/).

Once your 3D model has been created, it must be exported in OBJ format. If you use SweetHome3d this is done from the "3D View" menu then "Export to OBJ format". Then take all the generated files and put them in a zip file (there may be a lot of files due to the textures).

> **Important**
>
> Files must be at the root of the zip is not in a subfolder

> **WARNING**
>
> A 3D model is quite impressive (this can represent several hundred MB). The bigger it is the longer the rendering time in Jeedom.

Once your 3D model has been exported, you must create a new 3D design in Jeedom. For that you have to go into edit mode by clicking on the small pencil on the right, then click on the +, give a name to this new 3D design then validate.

Jeedom will automatically switch to the new 3D design, you must return to edit mode and click on the small notched wheels.

You can from this screen :

- Change the name of your design
- Add an access code
- Choose an icon
- Import your 3D model

Click on the &quot;send&quot; button at the &quot;3D Model&quot; level and select your zip file

> **WARNING**
>
> Jeedom authorizes the import of a file of 150mo maximum !

> **WARNING**
>
> You must have a zip file

> **Tip**
>
> Once the file has been imported (it can be quite long depending on the size of the file), you need to refresh the page to see the result (F5)


# Configuration of elements

> **Important**
>
> Configuration can only be done in edit mode

To configure an element on the 3D design, double click on the element you want to configure. This will bring up a window where you can :

- Indicate a type of link (currently only Equipment exists)
- The link to the item in question. Here you can only put a link to a device for the moment. This allows when clicking on the item to bring up the equipment
- The specificity, there there are several that we will see right after, this allows to specify the type of equipment and therefore the display of information

## Light

- Status : Light status control can be binary (0 or 1), digital (0 to 100%) or color
- Power : bulb power (please note this may not reflect reality)

## Text

- Text : text to display (you can put commands there, the text will be automatically updated on change of it)
- Text size
- Text color
- Text transparency : from 0 (invisible) to 1 (visible)
- Background color
- Background transparency : from 0 (invisible) to 1 (visible)
- Border color
- Border transparency : from 0 (invisible) to 1 (visible)
- Space above the object : allows to indicate the spacing of the text compared to the element

## Door / Window

### Door / Window

- State : Door / Window status, 1 closed and 0 open
- Rotation
	- Activate : activates the rotation of the Door / Window when opening
	- Opening : the best is to test so that it matches your Door / Window
- Translation
	- Activate : activates translation when opening (sliding door / window type)
	- Meaning : direction in which the Door / Window should move (you have up / down / right / left)
	- Repeat : by default the Door / Window moves once its dimension in the given direction but you can increase this value
- Hide when the Door / Window is open
	- Activate : Hides the element if the Door / Window is open
- Color
	- Open color : if checked the element will take on this color if the Door / Window is open
	- Color closed : if checked the element will take on this color if the Door / Window is closed

### Shutter

- State : shutter status, 0 open other value closed
- Hide when the shutter is open
	- Activate : hide the element if the shutter is open
- Color
	- Color closed : if checked the element will take on this color if the shutter is closed

## Conditional color

Allows to give the chosen color to the element if the condition is valid. You can put as many colors / conditions as you want.

> **Tip**
>
> The conditions are evaluated in order, the first one which is true will be taken, the following ones will therefore not be evaluated
