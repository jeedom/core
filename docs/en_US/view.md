# Views
**Home → View**

Views allow you to create custom displays.
It's not as powerful as the designs, but it lets you create a more personalized display than the Dashboard in just a few minutes, featuring various objects, graphs, or commands.

{% include lightbox.html src="../images/doc-view_01.jpg" data="View" title="View" imgstyle="width:450px;display: block;margin: 0 auto;" %}

> **Tip**
>
> In your profile, you can select the default view when you click the Views menu.

## Principle

You can add equipment tiles, charts (which can consist of multiple data points), or table areas (which contain command widgets).

A View includes:

- A button in the upper-left corner to show or hide the list of Views, as well as a button to add a new one.
- Use the pencil icon on the right to edit the order and size of the devices, just as you would on the Dashboard.
- A *Full Edit* button that allows you to edit the zones and elements in the View.

> **Tip**
>
> In your profile, you can change this setting so that the list of Views is visible by default.

## Add/Edit a View

The concept is fairly simple: a View consists of zones. Each zone is either a *graph*, *widget*, or *table*. Depending on the type, you can add graphs, devices, or commands to it.

- On the left side of the page, you'll find the list of Views as well as a create button.
- A button in the upper-right corner lets you edit the Current View (Configuration).
- A button to add a zone. You will then be prompted to enter the zone name and type.
- A *View Result* button that allows you to exit full-edit mode and display this view.
- A button to save this View.
- A button to delete this View.

> **Tip**
>
> You can reorder the zones using drag-and-drop.

For each zone, you have the following general options:

- **Width**: Sets the width of the zone (in desktop mode only). 1 for a width of 1/12 of the browser, 12 for the full width.
- A button that allows you to add an item to this zone, depending on the zone type (see below).
- **Edit**: Allows you to change the name of the zone.
- **Delete**: Deletes the zone.

### Equipment Type Zone

A "Type Equipment" zone allows you to add equipment:

- **Add Device**: Allows you to add or edit devices to be displayed in the area.

> **Tip**
>
> You can delete a device directly by clicking the trash can icon to the left of it.

> **Tip**
>
> You can change the order of the tiles in the area by dragging and dropping them.


### Graphical type area

A chart type lets you add charts to your View; it has the following options:

- **Time Period**: Allows you to select the time period for the graphs (30 min, 1 day, 1 week, 1 month, 1 year, or all).
- **Add Curve**: Allows you to add or edit graphs.

When you press the **Add Curve** button, Jeedom displays a list of historical commands, and you can choose which one to add. Once you’ve done that, you’ll have access to the following options:

- **Trash Can**: Removes the command from the graph.
- **Name**: Name of the command to be drawn.
- **Color**: Color of the curve.
- **Type**: Curve type.
- **Grouping**: Allows you to group data (such as the maximum type per day).
- **Scale**: Scale (right or left) of the curve.
- **Staircase**: Displays the staircase curve.
- **Stack**: Stacks the curve with other curves of the same type.
- **Variation**: Plots only the variations relative to the previous value.

{% include lightbox.html src="../images/doc-view_02.jpg" data="View" title="Pie Graph" imgstyle="width:450px;display: block;margin: 0 auto;" %}

> **Tip**
>
> You can change the order of the charts in the area by dragging and dropping them.

### Table-type zone

Here are the buttons:

- **Add Column**: Allows you to add a column to the table.
- **Add Row**: Allows you to add a row to the table.

> **Note**
>
> You can rearrange the rows using drag-and-drop, but not the columns.

Once you've added your rows and columns, you can enter information in the cells:

- A text.
- HTML code (JavaScript is possible but strongly discouraged).
- The Control Widget: The button on the right lets you choose the command to display.
