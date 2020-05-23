# Vues
**Home → View**

Views allow you to create a custom view.
It is not as powerful as the designs but it allows in a few minutes to have a more personalized display.

> **Tip**
>
> You can choose the default view in your profile when clicking on the view menu.

## Principe

We can also put widgets, graphs (which can be composed of several data) or table zones (which contain the widgets of commands).

On this page, there is a button at the top left to show or hide the list of views as well as the button to add one (Jeedom will ask for its name and send you to the edit page) :

> **Tip**
>
> You can modify this option in your profile so that the list of views is visible by default.

## Adding / Editing a view

The principle is quite simple : a view is made up of zones (you can put as many as you want). Each zone is of graphic, widget or table type, depending on the type you can put equipment, control or graphic widgets in it.

> **Tip**
>
> You can move the order of the zones by dragging and dropping.

- On the left of the page we find the list of views and an add button.
- A button at the top right allows you to edit the current view.
- In the center you have a button to rename a view, a button to add an area, a button to see the result, a button to save and a button to delete the view.

After clicking on the add zone button, Jeedom will ask for its name and type.
On each zone you have the following general options :

- **Width** : Defines the width of the area (in desktop mode only).
- **Edit** : Allows to change the name of the zone.
- **Remove** : Allows to delete the zone.

### Widget type area

A widget type area allows you to add widgets :

- **Add widget** : Add / edit widgets to display in the area.

> **Tip**
>
> You can delete a widget directly by clicking on the trash can in front of it.

> **Tip**
>
> You can change the order of widgets in the area by dragging and dropping.

Once the add widget button is pressed, you get a window that will ask you for the widget to add

### Graphic type area

A graphics type area allows you to add graphics to your view, it has the following options :

- **Period** : Allows you to choose the display period of the graphics (30 min, 1 day, 1 week, 1 month, 1 year or all).
- **Add curve** : Allows to add / modify graphics.

When you press the &quot;Add curve&quot; button Jeedom displays the list of historical commands and you can choose the one (s) to add, once done you have access to the following options :

- **Trash can** : Remove command from chart.
- **Last name** : Name of the command to draw.
- **Color** : Color of the curve.
- **Type** : Curve type.
- **Group** : Allows grouping of data (maximum type per day).
- **Ladder** : scale (right or left) of the curve.
- **Staircase** : Displays the stepped curve.
- **Stack** : Stack the curve with the other type curves.
- **Variation** : Only draw variations with the previous value.

> **Tip**
>
> You can change the order of the graphics in the area by dragging and dropping.

### Array type area

Here you have the buttons :

- **Add column** : Add a column to the table.
- **Add line** : Add a row to the table.

> **NOTE**
>
> It is possible to reorganize the rows by dragging and dropping but not the columns.

Once you have added your rows / columns you can add information in the boxes :

- **text** : just text to write.
- **l'opération à mener** : any html code (javascript possible but strongly discouraged).
- **command widget** : the button on the right allows you to choose the command to display (note that this displays the widget of the command).


