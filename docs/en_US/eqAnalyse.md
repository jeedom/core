The Equipment Analysis page allows you to see a lot of info
relating to equipment centrally :

-   the state of your batteries

-   modules on alert

-   defined actions

-   defined alerts

-   orphan orders

The Batteries tab 
==================

You can see on this tab the list of your modules on battery,
their remaining level (the color of the tile depends on this level), the
type and number of batteries to be inserted in the module, the type of
module as well as the date on which the battery level information
has been updated. You can also see if a specific threshold has been
workbench for the particular module (represented by a hand)

> **Tip**
>
> The alert / warning thresholds on the battery levels are
> globally configurable in the Jeedom configuration
> (Administration → Equipment tab), or by equipment on the page
> advanced configuration of these in the alerts tab.

Modules on alert tab 
==========================

On this tab you will see in real time the modules in alert. The
alerts can be of different types :

-   Timeout (configured in the defined alerts tab)

-   battery in warning or in danger

-   command in warning or danger (configurable in the parameters
    advanced commands)

Other types of alerts may be found here.
Each alert will be represented by the color of the tile (the level
alert) and a logo at the top left (the type of alert)

> **Tip**
>
> Here will be displayed all the modules in alert even those configured in
> "Not visible". It is however interesting to note that if the module
> is &quot;visible&quot; the alert will also be visible on the dashboard (in
> the object concerned)

The Defined Actions tab 
=========================

This tab allows you to view the actions defined directly on a
ordered. Indeed, we can put on different orders and it
can be difficult to remember all. This tab is there for that
and synthesizes several things :

-   actions on state (found in advanced parameters
    info commands and used to perform one or more
    actions on the value of an order - immediately or after
    a delay)

-   action confirmations (configurable in the same place on a
    command info and allowing to request a confirmation for
    perform an action)

-   confirmations with code (same as above but with
    entering a code)

-   pre and post actions (always configurable in the same place on
    an action command and allowing to execute one or more others
    actions before or after the action in question)

> **Tip**
>
> The table allows you to see very textually the actions
> defined. Other types of defined actions may be added.

The Defined Alerts tab 
=========================

This tab allows you to see all the defined alerts, you
find in a table the following info if they exist :

-   communication delay alerts

-   specific battery thresholds defined on a device

-   the various danger alerts and warning commands

The Orphan Orders tab 
=============================

This tab allows you to see at a glance if you have any
orphan commands used through Jeedom. An order
orphan is a command used somewhere but which no longer exists.
We will find here all of these commands, such as for example :

-   orphan commands used in the body of a scenario

-   those used to trigger a scenario

And used in many other places like (not exhaustive) :

-   the interactions

-   jeedom configurations

-   in pre or post action of an order

-   in action on order status

-   in some plugins

> **Tip**
>
> The table provides a very textual view of the commands
> orphan. Its purpose is to be able to quickly identify all the
> &quot;orphan&quot; commands through all Jeedom and plugins. It is
> some areas may not be analyzed, the table will be
> be more and more exhaustive over time.
