# Vues
**Home → View**

Views allow you to create custom views.
It is not as powerful as the designs but it allows in a few minutes to have a more personalized display than the Dashboard, with equipment of different objects, graphics, or controls.

> **Tip**
>
> You can choose the default view in your profile when clicking on the view menu.

## Principe

We can also put equipment tiles, graphs (which can be composed of several data) or table zones (which contain the widgets of the commands).

On a View, we find :

- A button at the top left to show or hide the list of Views, as well as the button to add one.
- The pencil on the right to edit the order and size of the equipment, in the same way as the Dashboard.
- A button *Full Edition* allowing to edit the zones and elements of the View.

> **Tip**
>
> You can, in your profile, modify this option so that the list of Views is visible by default.

## Adding / Editing a view

The principle is quite simple : a View is made up of areas. Each zone is of type *graphic*, *widget* or *board*. Depending on this type, you can add graphics, equipment, or commands to it.

- On the left of the page we find the list of Views as well as a creation button.
- A button at the top right allows you to edit the Current View (Configuration).
- A button to add a zone. You will then be asked for the name and type of zone.
- A button *Display the result*, to exit the complete editing mode and display this View.
- A button allowing to save this View.
- A button allowing to delete this View.

> **Tip**
>
> You can move the order of the zones by dragging and dropping.

On each zone you have the following general options :

- **Width** : Defines the width of the area (in desktop mode only). 1 for the width of 1/12 of the navigator, 12 for the total width.
- A button allowing to add an element to this zone, depending on the type of zone (see below).
- **Edit** : Allows you to change the name of the zone.
- **Remove** : Delete the zone.

### Equipment type zone

An equipment type zone allows you to add equipment :

- **Add equipment** : Allows you to add / modify equipment to display in the area.

> **Tip**
>
> You can delete an item of equipment directly by clicking on the trash can to the left of it.

> **Tip**
>
> It is possible to change the order of the tiles in the area by dragging and dropping.


### Graphic type area

A graphics type area allows you to add graphics to your view, it has the following options :

- **Period** : Allows you to choose the display period of the graphics (30 min, 1 day, 1 week, 1 month, 1 year or all).
- **Add curve** : Add / edit graphics.

When you press the button **Add curve**, Jeedom displays the list of historical commands and you can choose the one to add. Once done you have access to the following options :

- **Trash can** : Remove command from chart.
- **Last name** : Name of the command to draw.
- **Color** : Color of the curve.
- **Type** : Type of curve.
- **Group** : Allows grouping of data (maximum type per day).
- **Ladder** : Scale (right or left) of the curve.
- **Staircase** : Displays the staircase curve.
- **Stack** : Stacks the curve with the other type curves.
- **Variation** : Only draw variations with the previous value.

> **Tip**
>
> You can change the order of the graphics in the area by dragging and dropping.

### Array type area

Here you have the buttons :

- **Add column** : Add a column to the table.
- **Add line** : Add a row to the table.

> **Note**
>
> It is possible to reorganize the rows by dragging and dropping but not the columns.

Once you have added your rows / columns you can add information in the boxes :

- A text.
- HTML code (javascript possible but strongly discouraged).
- The Widget of an order : The button on the right allows you to choose the command to display.
