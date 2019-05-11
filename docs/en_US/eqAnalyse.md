La page Analyse d’équipements accessible depuis le menu Analyse → Equipements vous permet de voir de nombreuses infos
relatives aux équipements de manière centralisée :

-   the state of your batteries

-   the modules in alerts

-   defined actions

-   defined alerts

-   Orphaned orders

The Batteries tab
==================

You can see on this tab the list of your battery modules,
their remaining level (the color of the tile depends on this level), the
type and number of batteries that must be put in the module, the type of
module as well as the date at which the battery level information
has been updated. You can also see if a specific threshold has been
established for the particular module (represented by a hand)

> **Tip**
>
> The warning / warning thresholds on the battery levels are
> configurable globally in the Jeedom configuration
> (Administration → Equipment tab), or by equipment on the page
> advanced configuration of these in the alerts tab.

The Modules Alert tab
==========================

On this tab you will see in real time the modules on alert. The
alerts can be of different types:

-   timeout (configured in the defined alerts tab)

-   battery warning or in danger

-   command in warning or danger (parameterizable in the parameters
    advanced orders)

Other types of alerts may eventually be found here.
Each alert will be represented by the color of the tile (the level
alert) and a logo at the top left (the alert type)

> **Tip**
>
> Here will be displayed all modules on alert even those configured in
> "not visible". It is however interesting to note that if the module
> is in "visible" the alert will also be visible on the dashboard (in
> the object concerned)

The Defined Actions tab
=========================

This tab allows you to view the actions defined directly on a
command. Indeed, we can put on different orders and it
can be difficult to remember all. This tab is there for that
and synthesizes several things:

-   actions on state (found in the advanced parameters
    info commands and allowing to realize one or more
    actions on the value of an order - immediately or after
    a delay)

-   share confirmations (configurable in the same place on a
    order info and to request a confirmation for
    perform an action)

-   confirmations with code (same as before but with
    entering a code)

-   pre and post actions (always configurable in the same place on
    an action command and allowing to execute one or more others
    actions before or after the action in question)

> **Tip**
>
> The table allows to see in a very textual way the actions
> defined. Other types of actions defined can be added.

The Alerts tab
=========================

This tab allows you to see all the defined alerts, you can
find in a table the following information if they exist:

-   communication delay alerts

-   specific battery thresholds set on a device

-   the various hazard and warning alerts

The Orphan Orders tab
=============================

This tab allows you to see at a glance if you have any
orphan commands used through Jeedom. An order
orphan is a command used somewhere but no longer exists.
We will find here all these commands, as for example:

-   Orphaned commands used in the body of a scenario

-   those used as a trigger for a scenario

And used in many other places like (not exhaustive):

-   the interactions

-   jeedom's configurations

-   in pre or post action of an order

-   in action on order status

-   in some plugins

> **Tip**
>
> The table allows to see in a very textual way the commands
> orphans. Its purpose is to be able to quickly identify all
> "orphan" commands through all Jeedom and plugins. He is
> may some areas not be analyzed, the table will be
> be more and more exhaustive with time.
