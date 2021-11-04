# Design
**Home → Design**

This page allows you to configure the display of all your home automation in a very fine way.
It takes time but its only limit is your imagination.

> **Tip**
>
> It is possible to go directly to a design thanks to the submenu.

> **Important**
>
> All actions are done by right clicking on this page, be careful to do it well in the design. When creating, you must do it in the middle of the page (to be sure of being on the design).

In the menu (right click), we find the following actions :

- **Designs** : Displays the list of your designs and allows you to access them.
- **Editing** : Switch to edit mode.
- **Full screen** : Allows you to use the entire web page, which will remove the Jeedom menu from the top.
- **Add graphic** : Add a graphic.
- **Add text / html** : Allows you to add text or html / JavaScript code.
- **Add scenario** : Add a scenario.
- **Add link**
    - **Towards a view** : Add a link to a view.
    - **Towards a design** : Add a link to another design.
- **Add equipment** : Adds equipment.
- **Add command** : Add a command.
- **Add image / camera** : Allows you to add a picture or a stream from a camera.
- **Add area** : Allows to add a clickable transparent zone which will be able to execute a series of actions during a click (depending or not on the status of another command).
- **Add summary** : Adds information from an object or general summary.
- **Display**
    - **Any** : Does not display any grid.
    - **10x10** : Displays a 10 by 10 grid.
    - **15x15** : Displays a grid of 15 by 15.
    - **20x20** : Displays a 20 by 20 grid.
    - **Magnetize the elements** : Adds magnetization between the elements to make it easier to stick them.
    - **Magnet on the grid** : Add a magnetization of the elements to the grid (attention : depending on the zoom of the element this functionality can more or less work).
    - **Hide item highlighting** : Hide highlighting around items.
- **Delete design** : Remove design.
- **Create a design** : Allows you to add a new design.
- **Duplicate design** : Duplicates current design.
- **Configure the design** : Access to the configuration of the design.
- **Save** : Save the design (note, there are also automatic backups during certain actions).

> **Important**
>
> The configuration of the design elements is done by a click on them.

## Design configuration

Found here :

- **General**
    - **Last name** : The name of your design.
    - **Position** : The position of the design in the menu. Allows you to order the designs.
    - **Transparent background** : Makes the background transparent. Attention if the box is checked, the background color is not used.
    - **Background color** : Design background color.
    - **Access code** : Access code to your design (if empty, no code is required).
    - **Icon** : An icon for it (appears in the design choice menu).
    - **Picture**
        - **To send** : Allows you to add a background image to the design.
        - **Delete image** : Delete image.
- **Sizes**
    - **Size (WxH)** : Allows you to set the size in pixels of your design.

## General configuration of elements

> **Note**
>
> Depending on the type of item, options may change.

### Common display settings

- **Depth** : Allows you to choose the depth level
- **Position X (%)** : Horizontal coordinate of the element.
- **Position Y (%)** : Vertical coordinate of the element.
- **Width (px)** : Element width in pixels.
- **Height (px)** : Element height in pixels.

### Supprimer

Remove item

### Dupliquer

Allows you to duplicate the element

### Verrouiller

Allows you to lock the element so that it is no longer movable or resizable.

## Graphique

### Specific display settings

- **Period** : Allows you to choose the display period
- **Show caption** : Displays the legend.
- **Show browser** : Displays the browser (second lighter graph below the first).
- **Show period selector** : Displays the period selector at the top left.
- **Show scroll bar** : Displays the scroll bar.
- **Transparent background** : Makes the background transparent.
- **Border** : Allows you to add a border, be careful the syntax is HTML (be careful, you must use CSS syntax, for example : solid 1px black).

### Advanced configuration

Allows you to choose the commands to grapher.

## Text / html

### Specific display settings

- **Icon** : Icon displayed in front of the Design name.
- **Background color** : allows you to change the background color or make it transparent, do not forget to change &quot;Default&quot; to NO.
- **Text color** : allows you to change the color of icons and texts (be careful to set Default to No)..
- **Smooth it out** : allows to round the angles (do not forget to put%, ex 50%).
- **Border** : allows you to add a border, beware the syntax is HTML (you must use CSS syntax, for example : solid 1px black).
- **Font size** : allows you to change the font size (ex 50%, you must put the% sign).
- **Text alignment** : allows you to choose the alignment of the text (left / right / centered).
- **Fat** : bold text.
- **Text** : Text in HTML code that will be in the element.

> **Important**
>
> If you put HTML code (in particular Javascript), be careful to check it before because you can if there is an error in it or if it overwrites a Jeedom component completely crash the design and it will only have to delete it directly into the database.

## Scenario

*No specific display settings*

## Lien

### Specific display settings

- **Last name** : Name of the link (displayed text).
- **Link** : Link to the design or view in question.
- **Background color** : Allows you to change the background color or make it transparent, do not forget to change &quot;Default&quot; to NO.
- **Text color** : Allows you to change the color of icons and texts (be careful to set Default to No).
- **Round off the angles (don't forget to put%, ex 50%)** : Allows to round the angles, do not forget to put the%.
- **Border (attention CSS syntax, ex : solid 1px black)** : Allows you to add a border, beware the syntax is HTML.
- **Font size (ex 50%, you must put the% sign)** : Allows you to change the font size.
- **Text alignment** : Allows you to choose the alignment of the text (left / right / centered).
- **Fat** : Bold text.

## Equipement

### Specific display settings

- **Display object name** : Check to display the name of the parent object of the device.
- **Hide name** : Check to hide the name of the equipment.
- **Background color** : Allows you to choose a custom background color, or to display the equipment with a transparent background, or to use the default color.
- **Text color** : Lets you choose a custom background color, or use the default color.
- **Rounding** : Value in pixels of the rounding of the angles of the equipment tile.
- **Border** : CSS definition of an equipment tile border. Ex : 1px solid black.
- **Opacity** : Opacity of the equipment tile, between 0 and 1. Warning : a background color must be defined.
- **Custom CSS** : CSS rules to apply on the equipment.
- **Apply custom css on** : CSS selector on which to apply custom CSS.

### Commandes

The list of commands present on the equipment allows you, for each command, to:
- Hide command name.
- Hide command.
- Display the order with a transparent background.

### Advanced configuration

Displays the advanced equipment configuration window (see documentation **Home automation summary**).

## Commande

*No specific display settings*

### Advanced configuration

Displays the advanced equipment configuration window (see documentation **Home automation summary**).

## Picture / Camera

### Specific display settings

- **Pin up** : Defines what you want to display, still image or stream from a camera.
- **Picture** : Send the image in question (if you have chosen an image).
- **Camera** : Camera to display (if you chose camera).

## Zone

### Specific display settings

- **Type of area** : This is where you choose the type of area : Simple macro, Binary macro or Widget on hover.

### Single macro

In this mode, a click on the zone performs one or more actions. Here you just need to indicate the list of actions to do when clicking on the area.

### Binary macro

In this mode, Jeedom will execute the On or Off action (s) depending on the status of the command you indicate. Ex : if the command is worth 0 then Jeedom will execute the On action (s) otherwise it will execute the Off action (s)

- **Binary information** : Command giving the status to check to decide what action to do (On or Off).

You just have to put the actions to do for the On and for the Off.

### Hover widget

In this mode, when hovering or clicking in the Jeedom area, you will display the widget in question.

- **Equipment** : Widget to display when hovering or clicking.
- **Show on flyover** : If checked, displays the widget on hover.
- **View on one click** : If checked, then the widget is displayed on click.
- **Position** : Allows you to choose where the widget will appear (by default bottom right).

## Summary

### Specific display settings

- **Link** : Allows you to indicate the summary to display (General for the global otherwise indicate the subject).
- **Background color** : Allows you to change the background color or make it transparent, do not forget to change &quot;Default&quot; to NO.
- **Text color** : Allows you to change the color of icons and texts (be careful to set Default to No).
- **Round off the angles (don't forget to put%, ex 50%)** : Allows to round the angles, do not forget to put the%.
- **Border (attention CSS syntax, ex : solid 1px black)** : Allows you to add a border, beware the syntax is HTML.
- **Font size (ex 50%, you must put the% sign)** : Allows you to change the font size.
- **Fat** : Bold text.

## FAQ

>**I can no longer edit my design**
>If you have put a widget or an image that takes almost the entire design, you must click outside the widget or image to access the menu by right-clicking.

>**Delete a design that no longer works**
>In the administration part then OS / DB, make "select * from planHeader", recover the id of the design in question and make a "delete from planHeader where id=#TODO#" and "delete from plan where planHeader_id=#todo#" replacing well #TODO# by the design id previously found.
