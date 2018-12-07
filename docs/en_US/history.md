Important part in a software: the historization part, true
memory of it. It is possible in Jeedom to historize anywhere
which information type command (binary or digital). this will
Thus, for example, it will be possible to record a temperature curve,
consumption or the openings of a door ...

Principle
========

Here is described the principle of historization of Jeedom. It is not
necessary to understand that if you encounter any worries
of history or that you want to change the settings of
historicization. The default settings are suitable for most
case.

archiving
---------

Data archiving allows Jeedom to reduce the amount of data
kept in memory. This makes it possible not to use too much space and
not to slow down the system. Indeed, if you keep all
measures, this makes all the more points to display and so can
considerably lengthen the time to render a graph. In case
Too many points, it can even crash
the graph display.

Archiving is a task that starts at night and compacts
data recovered in the day. By default Jeedom recovers all
data older than 2h and in fact 1h packets (ie one
average, a minimum or maximum depending on the settings). So we have
here 2 parameters, one for packet size and another for knowing
from when to do it (for default callback these are packets
1 hour with data that have more than 2 hours of seniority).

> **Tip**
>
> If you have followed correctly you should have a high precision on the
> Last 2 hours only. Yet when I connect at 17h,
> I have a precision on the last 17 hours. Why ? In fact,
> to avoid consuming resources unnecessarily, the task that makes
> Archiving only takes place once a day, in the evening.

> **Important**
>
> Of course, this archiving principle only applies to
> digital type; on the binary type commands, Jeedom only keeps
> the dates of change of state.

Display a chart
========================

There are several ways to access the history:

-   by putting a graph area in a view (see below),

-   by clicking on the desired command in a widget,

-   by going to the historical page which allows to superpose
    different curves and combine styles (area, curve, bar)

-   in mobile while staying pressed on the widget in question

If you are viewing a graphic through the history page or by clicking
the widget, you have access to several display options:

We find in the upper right the display period (here on the last
week because, by default I want it to be only a week - see
2 paragraphs above), then come the parameters of the curve
(These settings are kept from one display to another, so you do not have
than configure them once).

-   **Staircase**: Displays the curve in the form of a
    staircase or continuous display.

-   **Variation**: Displays the difference in value from the
    previous point.

-   **Line**: Displays the chart as lines.

-   **Area**: Displays the chart as an area.

-   **Column** \ *: Displays the graph as bars.

Graphics on views and designs
=====================================

You can also display the graphs on the views (we'll see here
the configuration options and not how to do it, for that you have to
return views or designs based on the document). here is
the options :

Once a data is activated, you can choose:

-   **Color**: the color of the curve.

-   **Type**: The type of chart (area, row or column).

-   **Scale**: since you can put several curves (data)
    on the same graph, it is possible to distinguish the scales
    (right or left).

-   **Staircase**: Displays the curve in the form of a
    staircase or continuous display

-   **Stack**: allows you to stack the values ​​of the curves (see
    below for the result).

-   **Variation**: Displays the difference in value from the
    previous point.

Option on the history page
===============================

The history page gives you access to some additional options

Calculated history
------------------

Displays a curve based on a multiple calculation
command (you can do everything, + - / \ * absolute value ... see
PHP documentation for some function). Ex:
abs (* \ [Garden \] \ [Hygrometry \] \ [Temperature \] * - * \ [Space of
Life \] \ [Humidity \] \ [Temperature \] *)

You also have access to a calculation formulas management that allows you
save them for easier viewing

> **Tip**
>
> Just click on the name of the object to unfold it;
> appear the historized commands that can be graphed.

Order History
----------------------

Before each data that can be graphed, you will find two icons:

-   **Trash**: Deletes the recorded data; then
    of click, Jeedom asks whether to delete the data before a
    certain date or all the data.

-   **Arrow**: allows to have a CSV export of the historized data.

Invalid value deletion
=================================

Sometimes you may have inconsistent values ​​about
graphics. This is often due to a concern for the interpretation of the
value. It is possible to delete or change the value of the point in
question, by clicking on it directly on the graph; of
Plus, you can set the minimum and maximum allowed so
to avoid future problems.

timeline
========

The timeline shows some events of your home automation form
chronological.

To see them, you must first activate the tracking on the timeline of
desired commands or scenarios:

-   **Scenario**: either directly on the scenario page, or on the
    Scenarios summary page to do it in "mass"

-   **Command**: either in the advanced configuration of the command,
    in the configuration of the history to do it in "mass"

> **Tip**
>
> You have access to the summary windows of the scenarios or the
> history configuration directly from the page of
> timeline.

Once you have enabled tracking in the timeline of commands and
scenarios, you can see them appear on the timeline.

> **Important**
>
> You have to wait for new events after activating the follow-up
> on the timeline before seeing them appear.

The cards on the timeline display:

-   **Action command**: in red background, an icon on the right allows you
    to display the advanced configuration window of the command

-   **Info command**: in blue background, an icon on the right allows you
    to display the advanced configuration window of the command

-   **Scenario**: in gray background, you have 2 icons: one to display
    the log of the scenario and one to go on the scenario


