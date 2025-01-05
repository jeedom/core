# Changelog Jeedom V4.5

# 4.5

- Ability to make table columns resizable (only the list of variables for the moment, this will be extended to other tables if necessary)) [LINK](https://github.com/jeedom/core/issues/2499)
- Added an alert if jeedom disk space is too low (the check is done once a day) [LINK](https://github.com/jeedom/core/issues/2438)
- Added a button to the order configuration window at the value calculation field to fetch an order [LINK](https://github.com/jeedom/core/issues/2776)
- Ability to hide certain menus for users with limited rights [LINK](https://github.com/jeedom/core/issues/2651)
- Charts update automatically when new values arrive [LINK](https://github.com/jeedom/core/issues/2749)
- Jeedom automatically adds the height of the image when creating widgets to avoid overlapping issues on mobile [LINK](https://github.com/jeedom/core/issues/2539)
- Redesign of the cloud backup part [LINK](https://github.com/jeedom/core/issues/2765)
- **DEV** Setting up a queue system for executing actions [LINK](https://github.com/jeedom/core/issues/2489)
- Scenario tags are now specific to the scenario instance (if you have two scenario launches very close together, the tags of the last one no longer overwrite the first one)) [LINK](https://github.com/jeedom/core/issues/2763)
- Change to the trigger part of the scenarios : [LINK](https://github.com/jeedom/core/issues/2414)
  - ``triggerId()`` is now deprecated and will be removed in future core updates. If you have ``triggerId() == 587`` it must be replaced by ``#trigger_id# == 587``
  - ``triggerValue()`` is now deprecated and will be removed in future core updates. If you have ``triggerValue() == 10`` it must be replaced by ``#trigger_value# == 10``
  - ``#trigger#`` : Maybe :
    - ``api`` if the launch was triggered by the API,
    - ``TYPEcmd`` if the launch was triggered by a command, with TYPE replaced by the plugin id (eg virtualCmd),
    - ``schedule`` if it was launched by programming,
    - ``user`` if it was started manually,
    - ``start`` for a launch at Jeedom startup.
  - ``#trigger_id#`` : If it is a command that triggered the scenario then this tag takes the value of the id of the command that triggered it
  - ``#trigger_name#`` : If it is a command that triggered the scenario then this tag takes the value of the name of the command (in the form [object][equipment][command])
  - ``#trigger_value#`` : If it is a command that triggered the scenario then this tag takes the value of the command that triggered the scenario
  - ``#trigger_message#`` : Message indicating the origin of the scenario launch
- Improved plugin management on github (no more dependencies on a third-party library) [LINK](https://github.com/jeedom/core/issues/2567)
- Removing the old cache system. [LINK](https://github.com/jeedom/core/pull/2799)
- Possibility of deleting the IN and A blocks pending another scenario [LINK](https://github.com/jeedom/core/pull/2379)
- Fixed a bug in Safari on filters with accents [LINK](https://github.com/jeedom/core/pull/2754)
- Fixed bug on information generation *generic type* in the scenarios [LINK](https://github.com/jeedom/core/pull/2806)
- Added confirmation when opening support access from the user management page [LINK](https://github.com/jeedom/core/pull/2809)
- Improved cron system to avoid some launch glitches [LINK](https://github.com/jeedom/core/commit/533d6d4d508ffe5815f7ba6355ec45497df73313)
- Added in the condition wizard of the scenarios of the conditions *greater than or equal to* And *less than or equal to* [LINK](https://github.com/jeedom/core/issues/2810)
- Ability to exclude orders from dead order analysis [LINK](https://github.com/jeedom/core/issues/2812)
- Fixed a bug on numbering of the number of rows of tables [LINK](https://github.com/jeedom/core/commit/0e9e44492e29f7d0842b2c9b3df39d0d98957c83)
- Added openstreetmap.org in external domains allowed by default [LINK](https://github.com/jeedom/core/commit/2d62c64f0bd1958372844f6859ef691f88852422)
- Automatically update apache security file when updating core [LINK](https://github.com/jeedom/core/issues/2815)
- Fixed a warning on views [LINK](https://github.com/jeedom/core/pull/2816)
- Fixed bug on default widget select value [LINK](https://github.com/jeedom/core/pull/2813)
- Fixed a bug if a command exceeded its min or max the value would go to 0 (instead of min/max) [LINK](https://github.com/jeedom/core/issues/2819)
- Fixed a bug with the settings menu displaying in some languages [LINK](https://github.com/jeedom/core/issues/2821)
- Possibility in the programmed triggers of the scenarios to use calculations/commands/tags/formulas giving as a result the launch time in the form Gi (hours without initial zero and minutes, example for 09:15 => 915 or for 23:40 => 2340) [LINK](https://github.com/jeedom/core/pull/2808)
- Ability to put a custom image for equipment in plugins (if the plugin supports it), this is done in the advanced configuration of the equipment [LINK](https://github.com/jeedom/core/pull/2802) [LINK](https://github.com/jeedom/core/pull/2852)
- Added the name of the user who launched the scenario in the tag ``#trigger_value#`` [LINK](https://github.com/jeedom/core/pull/2382)
- Fixed an error that could occur when exiting the dashboard before it had finished loading [LINK](https://github.com/jeedom/core/pull/2827)
- Fixed a bug on the replacement page when filtering on objects [LINK](https://github.com/jeedom/core/issues/2833)
- Improved opening of the core changelog under ios (no longer in a popup)) [LINK](https://github.com/jeedom/core/issues/2835)
- Improved advanced widget creation window [LINK](https://github.com/jeedom/core/pull/2836)
- Improved advanced command configuration window [LINK](https://github.com/jeedom/core/pull/2837)
- Fixed bug on widget creation [LINK](https://github.com/jeedom/core/pull/2838)
- Fixed a bug on the scenario page and the add actions window that could no longer work [LINK](https://github.com/jeedom/core/issues/2839)
- Fixed a bug that could change the order of commands when editing the dashboard [LINK](https://github.com/jeedom/core/issues/2841)
- Fixed a javascript error on histories [LINK](https://github.com/jeedom/core/issues/2840)
- Added security on json encoding in ajax to avoid errors due to invalid characters [LINK](https://github.com/jeedom/core/commit/0784cbf9e409cfc50dd9c3d085c329c7eaba7042)
- If a command of a device is of generic type "Battery" and has unit "%" then the core will automatically assign the battery level of the device to the value of the command [LINK](https://github.com/jeedom/core/issues/2842)
- Improving texts and correcting mistakes [LINK](https://github.com/jeedom/core/pull/2834)
- When installing npm dependencies the cache is cleaned before [LINK](https://github.com/jeedom/core/commit/1a151208e0a66b88ea61dca8d112d20bb045c8d9)
- Fixed a bug on 3D plans that could completely block the configuration [LINK](https://github.com/jeedom/core/pull/2849)
- Fixed a bug on the history display window [LINK](https://github.com/jeedom/core/pull/2850)
- Ability to choose Apache listening port in docker mode [LINK](https://github.com/jeedom/core/pull/2847)
- Fixed a warning when saving to the event table [LINK](https://github.com/jeedom/core/issues/2851)
- Added display name for objects [LINK](https://github.com/jeedom/core/issues/2484)
- Added a button to delete timeline history and events in the future [LINK](https://github.com/jeedom/core/issues/2415)
- Fixed an issue with select type commands in designs [LINK](https://github.com/jeedom/core/issues/2853)
- Possibility to indicate that a piece of equipment has no battery (in case of poor feedback)) [LINK](https://github.com/jeedom/core/issues/2855)
- Rework of writing in logs, removal of the monolog library (note that the option to send logs in syslog is no longer available at the moment, if demand is high we will see about putting it back) [LINK](https://github.com/jeedom/core/pull/2805)
- Better management of plugin sub-log log level [LINK](https://github.com/jeedom/core/issues/2860)
- Removed vendor folder (using composer normally), reduces core size [LINK](https://github.com/jeedom/core/commit/3aa99c503b6b1903e6a07b346ceb4d03ca3c0c42)
- Widget specific settings can now be translated [LINK](https://github.com/jeedom/core/pull/2862)
- Fixed a bug on mac on designs when right clicking [LINK](https://github.com/jeedom/core/issues/2863)
- Improved the system for launching programmed scenarios [LINK](https://github.com/jeedom/core/issues/2875)
- Improved exception error messages if log level is debug [LINK](https://github.com/jeedom/core/issues/2886)
- On the replacement page, it is possible to request the deactivation of the equipment [LINK](https://github.com/jeedom/core/issues/2893)
- When updating, the backup before now takes into account plugin exclusions [LINK](https://github.com/jeedom/core/commit/22aa19b85028b0de6f7d3028ae0424d4f238f7df)
- If a plugin is in beta but does not have a beta changelog then the update center will link to the stable changelog [LINK](https://github.com/jeedom/core/commit/2af7b0a4d8680f68810cf9d07c657c51fe9e40bd)
- Added alert if debian version is 10 or lower [LINK](https://github.com/jeedom/core/issues/2912)
- Fix DB update error on cache table [LINK](https://github.com/jeedom/core/commit/a21f3498195f0003c5ead7cd5e8589f1c77c1c06)
- If the equipment page has an img_device element (img type tag) then the core can itself configure the url to display the correct equipment image (using the one from the presentation page of all the plugin's equipment) [LINK](https://github.com/jeedom/core/commit/07708ba4cbed982af968919dac3e406707867417)
- Fixed bug with time_diff function [LINK](https://github.com/jeedom/core/issues/2915)
- Bug fix on composer dependency management [LINK](https://github.com/jeedom/core/issues/2920)
- In case of empty command Jeedom forces the return according to the subtype of the command (therefore 0 for numeric and binary) [LINK](https://github.com/jeedom/core/commit/442d47246373e4f52b1dde7d1c7fdc9f67ea143e)
- Support for custom events of type #pluginClass::*# Or #pluginClass::customId# [LINK](https://github.com/jeedom/core/pull/2964)
- Added an update button on the "core" line in addition to the one on the top right [LINK](https://github.com/jeedom/core/pull/2974)
- Fixed "package" screen at python2 packages level [LINK](https://github.com/jeedom/core/pull/2973)
- Fixed a bug when updating equipment on the battery page [LINK](https://github.com/jeedom/core/pull/3008)

>**IMPORTANT**
>
> Due to the rework of logs and the re-internalization of libraries, when updating you may have an error type ``PHP Fatal error`` (nothing serious) just restart the update.

>**IMPORTANT**
>
> Restoring a backup 4.4 may in some cases end up with errors in the web interface. Rien de grave cela peut facilement se corriger il suffit de faire : `cd /tmp;wgAnd https://github.com/jeedom/core/archive/refs/tags/4.4.19.zip;unzip 4.4.19.zip;cd core-4.4.19;cp -rf * /var/www/html/;rm -rf /tmp/master.zip;rm -rf /tmp/core-4.4.19;`. You can launch this command from the jeedom rescue interface (add `&rescue=1` in the url), or directly in ssh.
