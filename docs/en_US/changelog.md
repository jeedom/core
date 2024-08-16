# Changelog Jeedom V4.5

# 4.5

- Possibility of making table columns resizable (only the list of variables for the moment, it will be extended to other tables if necessary) [LINK](https://github.com/jeedom/core/issues/2499)
- Added an alert if jeedom disk space is too low (the check is done once a day) [LINK](https://github.com/jeedom/core/issues/2438)
- Added a button to the order configuration window at the value calculation field to fetch an order [LINK](https://github.com/jeedom/core/issues/2776)
- Ability to hide certain menus for limited users [LINK](https://github.com/jeedom/core/issues/2651)
- The graphs update automatically when new values arrive [LINK](https://github.com/jeedom/core/issues/2749)
- Jeedom automatically adds the height of the image when creating widgets to avoid overlapping issues on mobile [LINK](https://github.com/jeedom/core/issues/2539)
- Redesign of the cloud backup part [LINK](https://github.com/jeedom/core/issues/2765)
- **DEV** Setting up a queue system for action execution [LINK](https://github.com/jeedom/core/issues/2489)
- The scenario tags are now specific to the scenario instance (if you have two scenarios launched very close together, the tags of the latter no longer overwrite the first) [LINK](https://github.com/jeedom/core/issues/2763)
- Change to the trigger part of the scenarios : [LINK](https://github.com/jeedom/core/issues/2414)
  - ``triggerId()`` is now deprecated and will be removed in future core updates
  - ``trigger()`` is now deprecated and will be removed in future core updates
  - ``triggerValue()`` is now deprecated and will be removed in future core updates
  - ``#trigger#`` : Maybe :
    - ``api`` if the launch was triggered by the API,
    - ``TYPEcmd`` if the launch was triggered by a command, with TYPE replaced the plugin id (ex virtualCmd),
    - ``schedule`` if it was launched by programming,
    - ``user`` if it was started manually,
    - ``start`` for a launch at Jeedom startup.
  - ``#trigger_id#`` : If it is a command which triggered the scenario then this tag has the value of the id of the command which triggered it
  - ``#trigger_name#`` : If it is a command which triggered the scenario then this tag has the value of the name of the command (in the form [object][equipment][command])
  - ``#trigger_value#`` : If it is a command which triggered the scenario then this tag has the value of the command which triggered the scenario
- Improved plugin management on github (no more dependencies on a third-party library) [LINK](https://github.com/jeedom/core/issues/2567)
- Removing the old cache system. [LINK](https://github.com/jeedom/core/pull/2799)
- Possibility of deleting the IN and A blocks while waiting for another scenario [LINK](https://github.com/jeedom/core/pull/2379)
- Fixed a bug in Safari on filters with accents [LINK](https://github.com/jeedom/core/pull/2754)
- Fixed a bug on the generation of generic type information in scenarios [LINK](https://github.com/jeedom/core/pull/2806)
- Added confirmation when opening support access from the user management page [LINK](https://github.com/jeedom/core/pull/2809)
- Added greater than or equal and less than or equal conditions to the scenario condition wizard [LINK](https://github.com/jeedom/core/issues/2810)
- Ability to exclude orders from dead order analysis [LINK](https://github.com/jeedom/core/issues/2812)
- Fixed a bug on the numbering of the number of rows of tables [LINK](https://github.com/jeedom/core/commit/0e9e44492e29f7d0842b2c9b3df39d0d98957c83)
- Added openstreetmap.org in external domains allowed by default [LINK](https://github.com/jeedom/core/commit/2d62c64f0bd1958372844f6859ef691f88852422)
- Automatically update apache security file when updating core [LINK](https://github.com/jeedom/core/issues/2815)
- Fixed a warning on views [LINK](https://github.com/jeedom/core/pull/2816)
- Fixed bug on default widget select value [LINK](https://github.com/jeedom/core/pull/2813)
- Fixed a bug if a command exceeded its min or max the value would go to 0 (instead of min/max) [LINK](https://github.com/jeedom/core/issues/2819)
- Fixed a bug where the settings menu was not displayed in some languages [LINK](https://github.com/jeedom/core/issues/2821)
- Possibility in the programmed triggers of the scenarios to use calculations/commands/tags/formulas giving as a result the launch time in the form Gi (hour without initial zero and minute, example for 09:15 => 915 or for 23:40 => 2340) [LINK](https://github.com/jeedom/core/pull/2808)
- Possibility to put a custom image for equipment in plugins (if the plugin supports it), for this just put the image in `data/img` in the form `eqLogic#id#.png` with #id# the equipment id (you can find it in the advanced equipment configuration) [LINK](https://github.com/jeedom/core/pull/2802)
- Added the name of the user who launched the scenario in the tag ``#trigger_value#`` [LINK](https://github.com/jeedom/core/pull/2382)
- Fixed an error that could occur if you exited the dashboard before it finished loading [LINK](https://github.com/jeedom/core/pull/2827)

>**IMPORTANT**
>
> Due to the change of cache engine on this update, all cache will be lost, don't worry it's cache it will rebuild itself. The cache contains, among other things, the values of the commands which will be automatically updated when the modules update their value. Note that if you have virtuals with fixed values (which is not good if it does not change so you have to use variables) then you will have to resave them to recover the value.