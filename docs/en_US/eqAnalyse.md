# Equipment analysis
**Analysis → Equipment**

The Equipment Analysis page allows you to view a lot of information relating to equipment in a centralized way :

- The state of your batteries
- Modules on alert
- Defined actions
- Defined alerts
- Orphan orders

## Batteries tab


You can see on this tab the list of your battery modules, their remaining level (the color of the tile depends on this level), the type and number of batteries that must be put in the module, the type of module as well that the date the battery level information was updated. You can also see if a specific threshold has been set for the particular module (represented by a hand)

> **Tip**
>
> The alert / warning thresholds on the battery levels can be configured globally in the Jeedom configuration (Settings → Systems → Configuration : Equipment), or by equipment on the advanced configuration page of these in the alerts tab.

## Modules on alert tab

On this tab you will see in real time the modules in alert. The alerts can be of different types :

- Timeout (configured in the defined alerts tab).
- Battery in warning or in danger.
- Warning or danger command (configurable in advanced command parameters).

Other types of alerts may be found here.
Each alert will be represented by the color of the tile (the alert level) and a logo at the top left (the alert type).

> **Tip**
>
> Here will be displayed all the modules in alert even those configured in "not visible". It is however interesting to note that if the module is "visible" the alert will also be visible on the dashboard (in the object concerned).

## Defined Actions tab

This tab allows you to view the actions defined directly on an order. Indeed, we can put on different commands and it can be difficult to remember all. This tab is there for that and synthesizes several things :

- Actions on status (found in the advanced parameters of info commands and allowing one or more actions to be performed on the value of an order - immediately or after a delay).
- Confirmations of actions (configurable in the same place on an info command and allowing to request a confirmation to execute an action).
- Confirmations with code (same as above but with entering a code).
- Pre and post actions (always configurable in the same place on an action command and allowing to execute one or more other actions before or after the action in question).

> **Tip**
>
> The table provides a very textual view of the actions defined. Other types of defined actions may be added.

## Defined Alerts tab

This tab allows you to see all the defined alerts, you will find in a table the following info if they exist :

- Communication delay alerts.
- Specific battery thresholds defined on a device.
- The various danger alerts and warning commands.

## Orphan Orders tab

This tab allows you to see at a glance if you have orphaned commands used through Jeedom. An orphan command is a command used somewhere but which no longer exists. We will find here all of these commands, such as for example :

- Orphan commands used in the body of a scenario.
- those used to trigger a scenario.

And used in many other places like (not exhaustive) :
- The interactions.
- Jeedom configurations.
- In pre or post action of an order.
- In action on order status.
- In some plugins.

> **Tip**
>
> The table provides a very textual view of the orphan commands. Its goal is to be able to quickly identify all &quot;orphan&quot; orders through all Jeedom and plugins. It may be that some areas are not analyzed, the table will be more and more exhaustive over time.
