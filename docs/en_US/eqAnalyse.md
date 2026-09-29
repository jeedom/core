# Equipment Analysis
**Analysis → Equipment**

The Equipment Analysis page allows you to view a wide range of information about your equipment in one place:

- Battery Status
- Alert Modules
- Defined Actions
- Defined alerts
- Orphaned commands

## Battery tab


On this tab, you can view a list of your battery-powered modules, their remaining battery level (the tile color indicates this level), the type and number of batteries required for each module, the module type, and the date the battery level information was last updated. You can also see if a specific threshold has been set for that particular module (indicated by a hand icon).

> **Tip**
>
> Battery level alert thresholds can be configured globally in the Jeedom settings (Settings → Systems → Configuration: Devices), or per device on the device’s advanced configuration page under the Alerts tab.

## "Modules on Alert" tab

On this tab, you'll see which modules are triggering alerts in real time. Alerts can be of various types:

- Timeout (configured in the "Defined Alerts" tab).
- Battery warning or low battery.
- Warning or danger command (configurable in the advanced command settings).

Other types of alerts may be added here in the future.
Each alert will be indicated by the tile’s color (the alert level) and a logo in the upper-left corner (the alert type).

> **Tip**
>
> All modules with alerts will be displayed here, even those configured as "hidden." It is worth noting, however, that if the module is set to "visible," the alert will also be visible on the dashboard (in the relevant object).

## "Defined Actions" tab

This tab lets you view the actions assigned directly to a command. Since you can assign actions to different commands, it can be hard to remember them all. That’s what this tab is for—it provides an overview of several things:

- Status-based actions (found in the advanced settings of the info commands, which allow you to perform one or more actions on the value of a command—either immediately or after a delay).
- Action confirmations (configurable in the same location via an "Info" command, allowing you to request confirmation before executing an action).
- Confirmations with a code (same as above, but requiring the entry of a code).
- Pre- and post-actions (which can always be configured in the same place within an action command and allow you to execute one or more other actions before or after the action in question).

> **Tip**
>
> The table provides a very clear overview of the defined actions. Other types of defined actions can be added.

## "Defined Alerts" tab

This tab lets you view all the alerts that have been set up. In the table, you’ll find the following information, if available:

- Alerts regarding communication delays.
- The specific battery thresholds set on a device.
- The various danger and warning alerts for commands.

## "Orphaned Commands" tab

This tab lets you see at a glance if you have any orphaned commands being used throughout Jeedom. An orphaned command is one that is being used somewhere but no longer exists. You’ll find all such commands listed here, such as:

- Orphaned commands used within the body of a scenario.
- Those used to trigger a scenario.

And used in many other places, such as (non-exhaustive list):
- Interactions.
- Jeedom configurations.
- As a pre- or post-action for a command.
- Action based on the status of a command.
- In certain plugins.

If the ID of the orphaned command is still present in the deletion history (viewable under Analysis / Home Automation Summary), its former name and deletion date will be displayed.

> **Tip**
>
> The table provides a very clear overview of orphaned commands. Its purpose is to quickly identify all “orphaned” commands throughout Jeedom and its plugins. Some areas may not yet have been analyzed, but the table will become increasingly comprehensive over time.
