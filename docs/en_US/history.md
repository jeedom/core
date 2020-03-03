IMPORTANT part in software : the historization part, real
memory of it. It is possible in Jeedom to historize any
which information type command (binary or digital). This will
will therefore allow for example to historicize a temperature curve,
consumption or door openings…

Principle 
========

Here is described the principle of historicization of Jeedom. It is not
necessary to understand that if you have any concerns
or want to change the settings for
historicization. The default settings are suitable for most
case.

Archiving 
---------

Data archiving allows Jeedom to reduce the amount of data
kept in memory. This allows not to use too much space and
not to slow down the system. Indeed, if you keep all the
measures, this makes all the more points to display and therefore can
dramatically lengthen times to make a graph. In casee
too many points, it can even crash
graph display.

Archiving is a task that starts at night and compacts
data recovered during the day. By default Jeedom recovers all
2h older data and makes 1h packets (one
average, minimum or maximum depending on the settings). So we have
here 2 parameters, one for packet size and another for knowing
when to do it (by default, these are packages
1 hour with data that has more than 2 hours of seniority).

> **Tip**
>
> If you have followed well you should have a high precision on the
> Last 2 hours only. Yet when I log in at 5 p.m.,
> I have a clarification on the last 17 hours. Why ? In fact,
> to avoid consuming resources unnecessarily, the task that makes
> archiving takes place only once a day, in the evening.

> **IMPORTANT**
>
> Of course, this archiving principle only applies to orders from
> digital type; on binary type commands, Jeedom does not keep
> that the dates of change of state.

Viewing a graph 
========================

There are several ways to access the history :

-   by putting a graph area in a view (see below),

-   by clicking on the desired command in a widget,

-   by going to the history page which allows to superimpose
    different curves and combine styles (area, curve, bar)

-   on mobile while remaining pressed on the widget in question

If you display a graph by the historical page or by clicking on
the widget, you have access to several display options :

We find at the top right the display period (here on the last
week because by default I want it to be only one week - see
2 paragraphs above), then come the parameters of the curve
(these parameters are kept from one display to another; you therefore
than configure them once).

-   **Staircasee** : displays the curve as a
    staircasee or continuous display.

-   **Variation** : displays the difference in value from
    previous point.

-   **Line** : displays the graph as lines.

-   **Area** : displays the graph as an area.

-   **Column**\* : displays the graph as bars.

Graphic on views and designs 
=====================================

You can also display the graphs on the views (we will see here
the configuration options and not how to do it, for that you have to
render views or designs based on the documentation). here is
the options :

Once a data is activated, you can choose :

-   **Color** : the color of the curve.

-   **Type** : the type of graph (area, line or column).

-   **Ladder** : since you can put several curves (data)
    on the same graph, it is possible to distinguish the scales
    (right or left).

-   **Staircasee** : displays the curve as a
    staircasee or continuous display

-   **Stack** : allows to stack the values of the curves (see in
    below for the result).

-   **Variation** : displays the difference in value from
    previous point.

Option on the history page 
===============================

The history page gives access to some additional options

Calculated history 
------------------

Used to display a curve based on a calculation on several
command (you can pretty much do everything, + - / \ * absolute value… see
PHP documentation for certain functions). Ex :
abs (* \ [Garden \] \ [Hygrometry \] \ [Temperature \] * - * \ [Space of
Life \] \ [Humidity \] \ [Temperature \] *)

You also have access to a management of calculation formulas which allows you
save them for easier viewing

> **Tip**
>
> Just click on the name of the object to unfold it;
> appear the historical commands which can be graphed.

Order history 
----------------------

In front of each data that can be graphed, you will find two icons :

-   **Trash can** : allows to delete the recorded data; then
    of the click, Jeedom asks if it is necessary to delete the data before a
    certain date or all data.

-   **Arrow** : allows to have a CSV export of historical data.

Inconsistent value removal 
=================================

Sometimes you may have inconsistent values on the
graphics. This is often due to a concern with the interpretation of the
value. It is possible to delete or change the point value by
question, by clicking on it directly on the graph; of
more, you can set the minimum and maximum allowed so
avoid future problems.

Timeline 
========

The timeline displays certain events in your home automation in the form
chronological.

To see them, you must first activate the tracking on the timeline of
desired commands or scenarios :

-   **Scenario** : either directly on the scenario page, or on the
    scenario summary page to do it in "mass"

-   **Ordered** : either in the advanced configuration of the command,
    either in the configuration of the history to do it in "mass"

> **Tip**
>
> You have access to the summary windows of the scenarios or of the
> configuration of the history directly from the page
> timeline.

Once you&#39;ve enabled tracking in the order timeline and
desired scenarios, you can see them appear on the timeline.

> **IMPORTANT**
>
> You have to wait for new events after activating tracking
> on the timeline before seeing them appear.

The cards on the timeline displays :

-   **Action command** : in red background, an icon on the right allows you
    display the advanced configuration window of the command

-   **Info command** : in blue background, an icon on the right allows you
    display the advanced configuration window of the command

-   **Scenario** : in gray background, you have 2 icons : one to display
    the scenario log and one to go to the scenario


