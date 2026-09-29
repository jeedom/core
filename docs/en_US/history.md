# History
**Analysis → History**

An important feature of any software is its logging function, which serves as its memory. In Jeedom, you can log any information-type command (binary or numerical). This allows you, for example, to log temperature trends, energy consumption, door openings, and more.

![History](../images/history.gif)

### Principle of Logging

### Archiving

Data archiving allows Jeedom to reduce the amount of data stored in memory. This helps avoid using too much space and prevents the system from slowing down. In fact, if you store all measurements, there are that many more data points to display, which can significantly increase the time it takes to render a graph. If there are too many data points, it can even cause the graph to crash.

Archiving is a task that runs overnight and compresses the data collected during the day. By default, Jeedom retrieves all data older than 2 hours and organizes it into 1-hour packets (either an average, a minimum, or a maximum, depending on the settings). So there are two settings here: one for the packet size and another to determine when to start creating them (as a reminder, by default these are 1-hour packets containing data that is more than 2 hours old).

> **Tip**
>
> If you’ve been following along, you should have high accuracy for only the last two hours. However, when I log in at 5:00 p.m., I see accuracy for the last 17 hours. Why is that? Actually, to avoid using resources unnecessarily, the task that handles archiving runs only once a day, in the evening.

> **Important**
>
> Of course, this logging principle applies only to digital commands. For binary commands, Jeedom stores only the dates when the state changed.

### Displaying a graph

There are several ways to access the history:

- By clicking on the desired command in a widget,
- By going to the history page, which allows you to overlay different charts and combine chart types (area, line, bar),
- On a mobile device, press and hold the widget in question,
- By placing a graph zone in a view (see below),
- By inserting a chart into a Design.

Starting with Core v4.2, it is also possible to display a graph in the background of a device tile.

## History

If you view a chart on the history page, you’ll see several display options above the chart:

- **Period**: The display period, including historical data between these two dates. By default, this is based on the *Default Chart Display Period* setting in *Settings → System → Configuration / Devices*.
- **Grouping**: Offers several grouping options (hourly total, etc.).
- **Display Type**: *Line*, *Area*, or *Bar* display. This option is saved on the command and used from the Dashboard.
- **Change**: Displays the difference in value compared to the previous point. This option is saved on the command and used from the Dashboard.
- **Staircase**: Allows you to display the curve as a staircase or a continuous display. This option is saved on the command and accessed from the Dashboard.
- **Compare**: Allows you to compare the curve across different time periods.

> **Tip**
>
> To prevent any user errors, these options saved in the commands are active only when a single curve is displayed.
>
In the upper section where the graphs are displayed, there are also several options:

On the left:

- **Zoom**: A shortcut area that allows you to set the horizontal zoom to the desired duration, provided the data has been loaded.

On the right:

- **Visible Vertical Axes**: Hides or shows all vertical axes.
- **Vertical Axis Scaling**: Allows you to enable or disable scaling for each vertical axis independently of the others.
- **Grouping Vertical Axes by Units**: Allows you to group the scale of curves and vertical axes based on their units. All curves with the same unit will have the same scale.
- **Curve opacity on mouseover**: Disables the highlighting of the curve when a value is displayed at the mouse cursor. For example, when two curves do not have values at the same points in time.

Below the graphs, you can also use the context menu on each legend to isolate a graph, show or hide its axis, change its color, and more.

### Chart on Views and Designs

You can also display graphs on views (here we’ll cover the configuration options rather than how to do it; for that, please refer to the documentation for views or designs, as appropriate). Here are the options:

Once a setting is enabled, you can choose:
- **Color**: The color of the curve.
- **Type**: The type of chart (area, line, or column).
- **Scale**: Since you can plot multiple curves (data points) on the same graph, you can choose between right- and left-hand scales.
- **Staircase**: Allows you to display the curve as a staircase or as a continuous line.
- **Stack**: Allows you to stack the values of the curves (see below for the result).
- **Change**: Displays the difference in value compared to the previous point.

### Option on the history page

The history page provides access to a few additional options

#### Calculated history

Allows you to display a graph based on a calculation involving multiple commands (you can do just about anything—+-/\* absolute value… see the PHP documentation for specific functions). For example:

`abs(*\[Jardin\]\[Hygrometrie\]\[Température\]* - *\[Espace de vie\]\[Hygrométrie\]\[Température\]*)`

You also have access to a formula management feature that allows you to save formulas so you can easily view them again.

> **Tip**
>
> When you have saved calculations, they are available on the left under **My Calculations**.

#### Command History

- Next to each piece of data that can be displayed, you’ll see a **Trash Can** icon that lets you delete the saved data; when you click it, Jeedom asks whether you want to delete data prior to a certain date or all data.
- In **Configuration**, to the right of each data point, you'll find an **Arrow** icon that lets you export historical data to a CSV file.

### Removal of Inconsistent Values

Sometimes, you may see inconsistent values on the graphs. This is often due to an issue with how the value is interpreted. You can delete or change the value of the data point in question by clicking directly on it on the graph; additionally, you can set the minimum and maximum allowed values to prevent future problems.


