# Changelog Jeedom V4.4

# 4.4.11

- Possibility of making table columns resizable (only the list of variables for the moment, it will be extended to other tables if necessary) [LINK](https://github.com/jeedom/core/issues/2499)
- Added an alert if jeedom disk space is too low (the check is done once a day) [LINK](https://github.com/jeedom/core/issues/2438)
- Added a button to the order configuration window at the value calculation field to fetch an order [LINK](https://github.com/jeedom/core/issues/2776)
- Ability to hide certain menus for limited users [LINK](https://github.com/jeedom/core/issues/2651)
- The graphs update automatically when new values arrive [LINK](https://github.com/jeedom/core/issues/2749)
- Jeedom automatically adds the height of the image when creating widgets to avoid overlapping issues on mobile [LINK](https://github.com/jeedom/core/issues/2539)
- Redesign of the cloud backup part [LINK](https://github.com/jeedom/core/issues/2765)
- [DEV] Implementation of a queuing system for action execution [LINK](https://github.com/jeedom/core/issues/2489)
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
- Improved cron system to avoid some launch failures [LINK](https://github.com/jeedom/core/commit/533d6d4d508ffe5815f7ba6355ec45497df73313)
- Added greater than or equal and less than or equal conditions to the scenario condition wizard [LINK](https://github.com/jeedom/core/issues/2810)

# 4.4.10

- Moved event management which is used to update the in-memory database interface [LINK](https://github.com/jeedom/core/pull/2757)
- Added a filter on many actions in scenarios [LINK](https://github.com/jeedom/core/pull/2753), [LINK](https://github.com/jeedom/core/pull/2742), [LINK](https://github.com/jeedom/core/pull/2759), [LINK](https://github.com/jeedom/core/pull/2743), [LINK](https://github.com/jeedom/core/pull/2755)
- The price of the plugin is hidden if you have already purchased it [LINK](https://github.com/jeedom/core/pull/2746)
- On the login page possibility to display or name the password [LINK](https://github.com/jeedom/core/pull/2740)
- Fixed a bug when leaving a page without saving [LINK](https://github.com/jeedom/core/pull/2745)
- Creation (in beta) of a new cache system [LINK](https://github.com/jeedom/core/pull/2758) :
  - File : system identical to that of before but taken internally to avoid dependencies on a third party lib. The most efficient but saved every 30 minutes
  - Mysql : using a base cache table. The least efficient but saved in real time (no data loss possible)
  - Redis : reserved for experts, relies on redis to manage the cache (requires you to install redis yourself and the php-redis dependencies)
- Fixed a bug on equipment alerts when deleting the alerted order [LINK](https://github.com/jeedom/core/issues/2775)
- Possibility in the advanced configuration of a piece of equipment to hide it when displaying several objects on the dashboard [LINK](https://github.com/jeedom/core/issues/2553)
- Fixed a bug when displaying the timeline folder in the advanced configuration of a command [LINK](https://github.com/jeedom/core/issues/2791)
- Redesign of Jeedom's fail2ban system so that it consumes less resources [LINK](https://github.com/jeedom/core/issues/2684)
- Fixed a bug on archiving and purging histories [LINK](https://github.com/jeedom/core/issues/2793)
- Improved patch for gpg bug on python dependencies [LINK](https://github.com/jeedom/core/pull/2798)
- Fixed a problem when changing the time following the overhaul of cron management [LINK](https://github.com/jeedom/core/issues/2794)
- Fixed a bug on the home automation summary page when searching for an order by id [LINK](https://github.com/jeedom/core/issues/2795)
- Added database size to health page [LINK](https://github.com/jeedom/core/commit/65fe37bb11a2e9f389669d935669abc33f54495c)
- Jeedom now lists all the branches and tags of the github repository to allow you to test functionalities in advance or to revert to a previous version of the core (be careful this is very risky) [LINK](https://github.com/jeedom/core/issues/2500)
- Improvement of subtypes of commands supported on generic types [LINK](https://github.com/jeedom/core/pull/2797)
- Fixed a bug in the display of scenarios and comments when you want to hide them [LINK](https://github.com/jeedom/core/pull/2790)

>**IMPORTANT**
>
> Any change of cache engine results in a reset of it so you then have to wait for the modules to send back the information to find everything

# 4.4.9

- Improved display of the list of scenarios when acting on scenarios (addition of groups) [LINK](https://github.com/jeedom/core/pull/2729)
- When copying equipment, if the widget had a graphic in the background, it is correctly transformed [LINK](https://github.com/jeedom/core/issues/2540)
- Adding tags #sunrise# and #sunset# in the scenarios to have the sunrise and sunset times [LINK](https://github.com/jeedom/core/pull/2725)
- A plugin can now add fields in the advanced configuration of all jeedom equipment [LINK](https://github.com/jeedom/core/issues/2711)
- Improvement of the crons management system [LINK](https://github.com/jeedom/core/issues/2719)
- Fixed a bug that could cause all information on the update page to be lost [LINK](https://github.com/jeedom/core/issues/2718)
- Deleting fail2ban service status from Jeedom health [LINK](https://github.com/jeedom/core/issues/2721)
- Fixed a problem with purging history [LINK](https://github.com/jeedom/core/issues/2723)
- Removal of widget caching (the display gain is not interesting and this causes a lot of problems) [LINK](https://github.com/jeedom/core/issues/2726)
- Fixed a bug on the design editing options (grid and magnetization) when changing a design with a link [LINK](https://github.com/jeedom/core/issues/2728)
- Fixed a javascript bug on buttons in modals [LINK](https://github.com/jeedom/core/pull/2734)
- Fixed a bug on the number of messages when deleting all messages [LINK](https://github.com/jeedom/core/issues/2735)
- Excluding the venv directory from backups [LINK](https://github.com/jeedom/core/pull/2736)
- Fixed a bug on the advanced configuration page of a command where the field to choose the timeline folder did not appear [LINK](https://github.com/jeedom/core/issues/2547)
- Fixed a bug in the command selection window following saving equipment [LINK](https://github.com/jeedom/core/issues/2773)

# 4.4.8.1

- Fixed a bug on scenarios with multiple programming [LINK](https://github.com/jeedom/core/issues/2698)

# 4.4.8

- Added advanced options to disallow certain api methods or only allow certain ones [LINK](https://github.com/jeedom/core/issues/2707)
- Improvement of the log configuration window [LINK](https://github.com/jeedom/core/issues/2687)
- Improvement of debug traces [LINK](https://github.com/jeedom/core/pull/2654)
- Deletion of warning message [LINK](https://github.com/jeedom/core/pull/2657)
- Fixed a problem on the logs page on small screens where the buttons were not visible [LINK](https://github.com/jeedom/core/issues/2671). Another improvement is planned later to have the buttons better placed [LINK](https://github.com/jeedom/core/issues/2672)
- Improved select management [LINK](https://github.com/jeedom/core/pull/2675)
- Increased field size for the sleep value in scenarios [LINK](https://github.com/jeedom/core/pull/2682)
- Fixed an issue with the order of messages in the message center [LINK](https://github.com/jeedom/core/issues/2686)
- Optimization of plugin loading [LINK](https://github.com/jeedom/core/issues/2689)
- Increase in the size of scrollbars on certain pages [LINK](https://github.com/jeedom/core/pull/2691)
- Improved jeedom restart page [LINK](https://github.com/jeedom/core/pull/2685)
- Fixed a problem with nodejs dependencies during restores [LINK](https://github.com/jeedom/core/issues/2621). Note : this will reduce the size of the backups
- Fixed a bug in the database update system [LINK](https://github.com/jeedom/core/issues/2693)
- Fixed a missing index issue for interactions [LINK](https://github.com/jeedom/core/issues/2694)
- Improved sick script.php to better detect database problems [LINK](https://github.com/jeedom/core/pull/2677)
- Best management of python2 on the health page [LINK](https://github.com/jeedom/core/pull/2674)
- Improvement of the scenario selection field (with search) in scenario actions [LINK](https://github.com/jeedom/core/pull/2688)
- Improved cron management in preparation for php8 [LINK](https://github.com/jeedom/core/issues/2698)
- Improved the daemons shutdown management function by port number [LINK](https://github.com/jeedom/core/pull/2697)
- Fixed a problem on certain browsers with filters [LINK](https://github.com/jeedom/core/issues/2699)
- Improved curl support [LINK](https://github.com/jeedom/core/pull/2702)
- Fixed a bug on composer dependency management [LINK](https://github.com/jeedom/core/pull/2703)
- Improvement of the cache management system [LINK](https://github.com/jeedom/core/pull/2706)
- Better management of user rights on API calls [LINK](https://github.com/jeedom/core/pull/2695)
- Fix warning [LINK](https://github.com/jeedom/core/pull/2701)
- Fixed a problem with installing python dependencies [LINK](https://github.com/jeedom/core/pull/2700/files)

# 4.4.7

- Possibility of choosing a maximum frequency of recorded data (1min/5min/10min) from the advanced configuration of the command [LINK](https://github.com/jeedom/core/issues/2610)
- Memorizing options on grids when editing designs [LINK](https://github.com/jeedom/core/issues/2545)
- Memorization of the menu state (expanded or not) when displaying histories [LINK](https://github.com/jeedom/core/issues/2538)
- Management of python dependencies in venv (to be compatible with debian 12) [LINK](https://github.com/jeedom/core/pull/2566). Note : the support is that on the core side it will then need updates on the plugin side for it to work
- Display of the size taken by the plugins (from Plugin -> Plugin management -> desired plugin) [LINK](https://github.com/jeedom/core/issues/2642)
- Fixed a bug on the discovery of mobile widget parameters [LINK](https://github.com/jeedom/core/issues/2615)
- Added fail2ban service status [LINK](https://github.com/jeedom/core/pull/2620)
- Improved display of the health page [LINK](https://github.com/jeedom/core/pull/2619)
- Fixed a problem with nodejs dependencies during restores [LINK](https://github.com/jeedom/core/issues/2621). Note : this will increase the size of the backups
- Fixed a bug on designs [LINK](https://github.com/jeedom/core/issues/2634)
- Fixed the display of selects on the replacement page [LINK](https://github.com/jeedom/core/pull/2639)
- Fixed a bug on the progression of dependencies (zwavejs plugin in particular) [LINK](https://github.com/jeedom/core/issues/2644)
- Fixed a width issue on the list widget in design mode [LINK](https://github.com/jeedom/core/issues/2647)
- Improved widget display [LINK](https://github.com/jeedom/core/pull/2631)
- Updated the documentation on the replace tool [LINK](https://github.com/jeedom/core/pull/2638)
- Fixed an inconsistency issue with the minimum values of report sizes [LINK](https://github.com/jeedom/core/issues/2449)
- Fixed a bug on the database check where an index could still be missing [LINK](https://github.com/jeedom/core/issues/2655)
- Fixed a bug on the links graph [LINK](https://github.com/jeedom/core/issues/2659)
- Removal of the service worker in mobile (no longer used) [LINK](https://github.com/jeedom/core/issues/2660)
- Fixed a bug that could affect the limitation of the number of events in the timeline [LINK](https://github.com/jeedom/core/issues/2663)
- Fixed a bug in the display of tooltips on designs [LINK](https://github.com/jeedom/core/pull/2667)
- Improved PDO trace management with php8 [LINK](https://github.com/jeedom/core/pull/2661)

# 4.4.6

- Fixed a bug when updating widget background graphics [LINK](https://github.com/jeedom/core/issues/2594)
- Fixed a bug on the gauge widget [LINK](https://github.com/jeedom/core/pull/2582)
- Ability to enter dates manually on the date pickers [LINK](https://github.com/jeedom/core/pull/2593)
- Fixed a bug when changing designs (order update functions were not removed) [LINK](https://github.com/jeedom/core/pull/2588)
- Bugfix [LINK](https://github.com/jeedom/core/pull/2592)
- Fixed sorting by date on the updates page [LINK](https://github.com/jeedom/core/pull/2595)
- Fixed a bug when copying limited user rights [LINK](https://github.com/jeedom/core/issues/2612)
- Fixed a bug on table widgets with style and attributes [LINK](https://github.com/jeedom/core/issues/2609)
- Fixed a bug on docker that could cause database corruption [LINK](https://github.com/jeedom/core/pull/2611)

## 4.4.5

- Bug fixes
- Documentation update
- Fixed a bug in php8 when installing and/or updating plugins
- Fixed a bug on the dashboard where in rare cases equipment could move or change size on its own

## 4.4.4

- Added sample code to the documentation [jeedom customization](https://doc.jeedom.com/en_US/core/4.4/custom) (to consult for those wanting to push personalization)
- Fixed a bug in the date selection window for history comparison
- Fixed a bug on the dashboard when moving commands was not immediately reflected on the widget
- Fixed various bugs (display and text)
- Fixed a bug on the update page that indicated an update was in progress when it wasn't

## 4.4.3

- Fix 401 error when opening a design with a non-admin user
- Various bug fixes (on widgets in particular)
- Removing minimums on widget steps

## 4.4.2

- Automatic management of internal access address after starting, updating or restoring Jeedom *(optionnel)*.
- Added info/string color widget. [[PR #2422](https://github.com/jeedom/core/pull/2422)]

## 4.4.1

- PHP 8 support.
- Checking the minimum core version required before installing or updating a plugin.
- Adding a button **Assistance** on the plugin configuration page *(Automatic creation of a help request on the forum)*.

>**IMPORTANT**
>
>Even if they are not necessarily visible at first glance, version 4.4 from Jeedom brings major changes with an interface that has been completely rewritten for complete control and above all an unrivaled gain in navigation fluidity. The management of PHP dependencies has also been revised in order to be able to keep them up to date automatically. Even if the Jeedom team and the beta testers have done a lot of testing, there are as many versions of jeedom as there are jeedom... It is therefore not possible to guarantee proper functioning in 100% of the However, in case of problem you can [open a topic on the forum with the label `v4_4`](https://community.jeedom.com/) or contact support from your market profile *(provided you have a service pack or higher)*.

### 4.4 : Prerequisites

- Debian 11 "Bullseye" *(very highly recommended, Jeedom remains functional in previous version)*
- Php 7.4

### 4.4 : News / Improvements

- **Historical** : History modal and History page allow to use buttons *Week, Month, Year* to dynamically reload a larger history.
- **Image selection window** : Added buttons and a context menu to send images and create, rename or delete a folder.
- **Icon selection window** : Ability to add a `path` parameter when calling `jeedomUtils.chooseIcon` by a plugin to display only its icons.
- **Dashboard** : Ability to display objects on multiple columns *(Settings → System → Configuration / Interface)*.
- **Dashboard** : Edit Mode tile editing window allows commands to be renamed.
- **Dashboard** : In table layout, possibility to insert HTML attributes *(colspan/rowspan in particular)* for each cell.
- **Equipment** : Ability to disable the widget templates of plugins that use them to return to the Jeedom default display *(device configuration window)*.
- **Equipment** : Equipment made inactive automatically disappears from all pages. Reactivated equipment reappears on the dashboard if the parent object is already present.
- **Equipment** : Equipment made invisible automatically disappears from the dashboard. The re-displayed equipment reappears on the dashboard if the parent object is already present.
- **Analysis > Equipment / Equipment on alert** : Devices that go into alert automatically appear and those that come out of an alert automatically disappear.
- **Message center** : Core messages on anomaly now inform an action, for example a link to open the offending scenario, or equipment, plugin configuration, etc.
- **Object** : Deleting or creating a summary updates the overall summary and subject.
- **Tools > Replace** : This tool now offers a mode *To copy*, allowing to copy the configurations of equipment and commands, without replacing them in the scenarios and others.
- **Timeline** : The Timeline now loads the first 35 events. The following events are loaded by scrolling at the bottom of the page.
- **Administration** : Possibility to differentiate actions on error or on command alert.
- **Administration** : Ability to set default command widgets.
- **Dashboard** : Ability to reorder equipment according to their use from the object configuration page.
- **Theme** : Possibility of choosing the theme directly from the address *(by adding ``&theme=Dark`` Where ``&theme=Light``)*.
- **Theme** : Removal of the theme **Core2019 Legacy**.
- **Report** : Possibility to choose the theme during a report on a jeedom page.
- **Jeedom menu** : A delay of 0.25s was introduced on opening submenus.
- **System Administration** : Ability to add custom shell commands in the left menu *(via a `/data/systemCustomCmd.json` file)*.

### 4.4 : Autre

- **Core** : Start of development in pure js, without jQuery. See [doc dev](https://doc.jeedom.com/en_US/dev/core4.4).
- **Core** : More detailed listing of USB devices.
- **Core** : A contextual menu has been added in different places at the level of the checkboxes to select them all, none, or invert the selection *(see [Doc dev](https://doc.jeedom.com/en_US/dev/core4.4))*.
- **Lib** : Update Highchart v9.3.2 to v10.3.2 (The module *solid gauge* no longer imported).
- **Orders** :  Added an option *(alpha)* to not execute an action if the equipment is already in the expected state.

### 4.4 : Remarques

> **Dashboard**
>
> On the **Dashboard** and the **Views**, Core v4.4 now automatically resizes tiles to build a seamless grid. The units (smallest height and smallest width of a tile) of this grid are defined in **Settings → System → Configuration / Interface** by values *Pas:Height (min 60px)* and *Pas:Width (min 80px)*. The value *Margin* defining the space between the tiles.
> The tiles adapt to the dimensions of the grid and can be done once, twice etc. these values in height or width. It will certainly be necessary to pass [Dashboard edit mode](https://doc.jeedom.com/en_US/core/4.4/dashboard#Mode%20%C3%A9dition) to fine-tune the size of some tiles after the update.

> **Widgets**
>
> Core widgets have been rewritten in pure js/css. You will have to edit the Dashboard *(Edit then button ⁝ on the tiles)* and use the option *Line wrap after* on certain commands to find the same visual aspect.
> All Core widgets now support displaying *time*, by adding an optional parameter *time* / *duration* Where *date*.

> **Dialog boxes**
>
> All dialog boxes (bootstrap, bootbox, jQuery UI) have been migrated to an internal Core lib (jeeDialog) specially developed. Resizable dialogs now have a button to switch to *fullscreen*.

# Changelog Jeedom V4.3

## 4.3.15

- Prohibition of the translation of Jeedom by browsers (avoids market.repo type errors.php not found).
- Optimization of the replacement function.

## 4.3.14

- Reduced load on DNS.

## 4.3.13

- Bugfix on **Tools / Replace**.

## 4.3.12

- Optimization on the histories.
- Bugfix Summary on mobile.
- Mobile shutter widget bugfix.
- Bugfix tile curves with binary info.

## 4.3.11

- Authorization of a free answer in *ask* if you put * in the possible answers field.
- **Analysis / History** : Bugfix on history comparison (bug introduced in 4.3.10).
- **Synthesis** : L'*Action from Synthesis* of an object is now supported on the mobile version.
- Correction of histories when using aggregation function.
- Fixed a bug on the installation of a plugin by another plugin (Ex : mqtt2 installed by zwavejs).
- Fixed a bug on the history where the value 0 could overwrite the previous value.

## 4.3.10

- **Analysis / History** : Fixed bugs on history deletion.
- Fixed value display in command configuration window.
- Added replacement tool information and control.

## 4.3.9

- Improved tile editing.
- Improved visibility of Dark and Light themed checkboxes.
- Fixed history stacking.
- Optimization of time change management (thanks @jpty).
- Bug fixes and improvements.

## 4.3.8

- Bugfix.
- Improved ask security when using the generateAskResponseLink function by plugins : use of a unique token (no more sending of the core api key) and locking of the response only among the possible choices.
- Fixed a bug preventing the installation of jeedom.
- Fixed a bug on influxdb.

## 4.3.7

- Bug fixes (impacting a future plugin under development).
- Fixed display bugs on some widgets based on unit.
- Added description **source** for message actions (see [Doc dev](https://doc.jeedom.com/en_US/dev/core4.3)).

## 4.3.6

- Removed unit conversion for seconds (s)).
- Removal of the OS update menu for Jeedom boxes (OS updates are managed by Jeedom SAS).
- Fixed a bug on the history configuration modal.
- Adding an action *changeTheme* for scenarios, value actions, pre/post exec actions : It allows to change the theme of the interface immediately, in dark, light or the other (toggle).

## 4.3.5

- Bugfix.

## 4.3.4

- Fixed an issue with background images.
- Fixed a bug with the default number widget.
- Fixed inclusion error with some plugins (*nuts* for example).

## 4.3.3

- Improved nodejs/npm version checking.

## 4.3.2

- Fixed a problem displaying the status of an info command in the advanced configuration of the command if the value is 0.

## 4.3.1

### 4.3 : Prerequisites

- Debian 10 Buster
- PHP 7.3

### 4.3 : News / Improvements

- **Tools / Scenarios** : Modal for ctrl+click editing in editable fields of blocks/actions.
- **Tools / Scenarios** : Addition of a contextual menu on a scenario to make active/inactive, change group, change parent object.
- **Tools / Objects** : Added a contextual menu on an object to manage visibility, change parent object, and move.
- **Tools / Replace** : New tool for replacing equipment and commands.
- **Analysis / Timeline** : Added a search field to filter the display.
- **Users** : Added a button to copy the rights of a limited user to another.
- **Report** : Ability to report on Jeedom health.
- **Report** : Ability to report on alerted equipment.
- **Update** : Ability to see from Jeedom the OS / PIP2 / PIP3 / NodeJS packages that can be updated and launch the update (beware risky function and in beta).
- **Alert command** : Added an option to receive a message in case of end of alert.
- **Plugins** : Possibility to disable the installation of dependencies by plugin.
- **Optimization** : jeeFrontEnd{}, jeephp2js{}, minor bugfixes and optimizations.

### 4.3 : WebApp

- Notes integration.
- Possibility to display the tiles only on a column (setting in the configuration of jeedom interface tab).

### 4.3 : Autre

- **Lib** : Update Font Awesome 5.13.1 to 5.15.4.

### 4.3 : Notes

- For users who use menus in their designs in the form :

``<a onClick="planHeader_id=15; displayPlan();"><li class="monmenu"><div class="imagette"><img src="theme1/images/new/home.png" height=30px></div></br></li></a>``

You must now use:

``<a onClick="jeephp2js.planHeader_id=15; jeeFrontEnd.plan.displayPlan();"><li class="monmenu"><div class="imagette"><img src="theme1/images/new/home.png" height=30px></div></br></li></a>``

see [Doc dev](https://doc.jeedom.com/en_US/dev/core4.3).

Blog post [here](https://blog.jeedom.com/6739-jeedom-4-3/)

# Changelog Jeedom V4.2

## 4.2.21

- Fixed a bug on summaries.

## 4.2.20

- Added a system to correct pip packages during a bad installation.

## 4.2.19

- Added version management for python packages (allows to correct the problem with the zigbee plugin).

## 4.2.18

- Nodejs update.

## 4.2.17

- Bugfix Core : Limited user access to designs and views.
- Bugfix UI : Display of A blocks in Chrome.
- Bugfix : Link to documentation when the plugin is in beta.

## 4.2.16

- Bugfix Core : Scenario : Merge pasted items in some cases.
- Bugfix Core : Archive creation with file editor.
- Bugfix : Increased delay for contacting the monitoring service (allows to lighten the load on cloud servers).

## 4.2.15

- Bugfix UI : Scenario : Adding the action *genericType* in the selection mode.
- Bugfix Core : Fixed lag on calculated histories.
- Bugfix : Installation of zigbee plugin dependencies.

## 4.2.14

- Bugfix UI : Research removed by activating the raw log option.
- Bugfix UI : Unable to download empty log.
- Bugfix UI : Cmd.action.slider.value widget

- Bugfix Core : Size of background images in relation to the size of the design.
- Bugfix Core : Fixed an issue with api keys still disabled.

## 4.2.13

- Bugfix UI : Option *Hide on desktop* summaries.
- Bugfix UI : Historiques: Respect scales when zooming.

- Bugfix Core : Fixed a backup size issue with the Atlas plugin.

- Improvement : Creation of api keys by default inactive (if the creation request does not come from the plugin).
- Improvement : Added backup size on backup management page.

## 4.2.12

- Bugfix UI : Displaying an action's folder on the Timeline.

- Bugfix Core : Display of the API key of each plugin on the configuration page.
- Bugfix Core : Add option *Hour* on a chart in Design.
- Bugfix Core : Tile curve with negative value.
- Bugfix Core : 403 error on reboot.

- Improvement : Display of the trigger value in the scenario log.

## 4.2.11

- Bugfix UI : Position on the home automation summary of newly created objects.
- Bugfix UI : 3D Design display issues.

- Bugfix Core : New undefined summary properties.
- Bugfix Core : Update value on click on the range of widgets *Slider*.
- Bugfix Core : Editing empty file (0b).
- Bugfix Core : Concerns of detecting the real IP of the client through the Jeedom DNS. A restart of the box is recommended following the update for this to activate.

## 4.2.9

- Bugfix UI : Widget fix *numeric default* (cmdName too long).
- Bugfix UI : Passing css variables *--url-iconsDark* and *--url-iconsLight* in absolute (Bug Safari MacOS).
- Bugfix UI : Position of notifications in *top center*.

- Bugfix Core : Default step for widgets *Slider* at 1.
- Bugfix Core : Page update indicates *In progress* on *END UPDATE ERROR* (log update).
- Bugfix Core : Modification of value of a history.
- Bugfix Core : Fixed issues with installing python dependencies.

- Improvement : New options on Design charts for scale and Y axis grouping.

- Core : Lib update *elFinder* 2.1.59 -> 2.1.60

## 4.2.8

- Bugfix UI : Home automation summary, clear deletion history.
- Bugfix UI : Option *Do not display anymore* on the modal *first-user*.
- Bugfix UI : Curve in background tiles on a View.
- Bugfix UI : Histories, scale of axes when de-zoomed.
- Bugfix UI : Histories, Stacking on Views.
- Bugfix UI : User name display when deleting.
- Bugfix UI : Options for displaying numbers without *icon if null*.

- Bugfix Core : Check Apache mod_alias.

- Improvement : Option in configuration to authorize dates in the future on the histories.

## 4.2.0

### 4.2 : Prerequisites

- Debian 10 Buster
- PHP 7.3

### 4.2 : News / Improvements

- **Synthesis** : Possibility of configuring objects to go to a *design* or a *seen* since the synthesis.
- **Dashboard** : The device configuration window (edit mode) now allows you to configure mobile widgets and generic types.
- **Widgets** : Internationalization of third-party Widgets (user code). see [Doc dev](https://doc.jeedom.com/en_US/dev/core4.2).
- **Analysis / History** : Possibility to compare a history over a given period.
- **Analysis / History** : Display of multiple axes in Y. Option for each axis to have its own scale, grouped by unit or not.
- **Analysis / History** : Possibility to hide the Y axes. Contextual menu on the legends with display only, axis masking, curve color change.
- **Analysis / History** : Saved history calculations are now displayed above the list of commands, in the same way as these.
- **Analysis / Equipment** : Orphan orders now display their name and date of deletion if still in the deletion history, as well as a link to the affected scenario or equipment.
- **Analysis / Logs** : Log line numbering. Possibility to display the raw log.
- **Logs** : Coloration of logs according to certain events. Possibility to display the raw log.
- **Summaries** : Possibility to define a different icon when the summary is null (no shutters open, no light on, etc).
- **Summaries** : Possibility to never display the number to the right of the icon, or only if it is positive.
- **Summaries** : The change of summary parameter in configuration and on objects is now visible, without waiting for a change in summary value.
- **Summaries** : It is now possible to configure [actions on summaries](/en_US/concept/summary#Actions on résumés) (ctrl + click on a summary) thanks to the virtual ones.
- **Report** : Preview PDF files.
- **Types of equipment** : [New page](/en_US/core/4.2/types) **Tools → Equipment types** allowing generic types to be assigned to devices and commands, with support for types dedicated to installed plugins (see [Doc dev](https://doc.jeedom.com/en_US/dev/core4.2)).
- **Selection of illustrations** : New global window for the choice of illustrations *(icons, images, backgrounds)*.
- **Table display** : Addition of a button to the right of the search on the pages *Objects* *Scenarios* *Interactions* *Widgets* and *Plugins* to switch to table mode. This is stored by a cookie or in **Settings → System → Configuration / Interface, Options**. The plugins can use this new function of the Core. see [Doc dev](https://doc.jeedom.com/en_US/dev/core4.2).
- **Equipment configuration** : Possibility of configuring a history curve at the bottom of the tile of a device.
- **Ordered** : Possibility of making a calculation on a command action of type slider before execution of the command.
- **Plugins / Management** : Display of the plugin category, and a link to directly open its page without going through the Plugins menu.
- **Scenario** : Code fallback function (*folding code*) in the *Code Blocks*. Ctrl + Y and Ctrl + I shortcuts.
- **Scenario** : Copy / paste and undo / redo bugfix (complete rewrite).
- **Scenario** : Adding calculation functions ``averageTemporal(commande,période)`` & ``averageTemporalBetween(commande,start,end)`` allowing to obtain the average weighted by the duration over the period.
- **Scenario** : Added support for Generic Types in scenarios.
  - Trigger : ``#genericType(LIGHT_STATE,#[Salon]#)# > 0``
  - IF ``genericType(LIGHT_STATE,#[Salon]#) > 0``
  - Stock ``genericType``
- **Objects** : Plugins can now request specific parameters specific to objects.
- **Users** : Plugins can now request specific parameters specific to users.
- **Users** : Ability to manage the profiles of different Jeedom users from the user management page.
- **Users** : Ability to hide objects / view / design / 3d design for limited users.
- **Updates Center** : Update Center now displays the date of the last update.
- **Adding the user performing an action** : Addition in the command execution options of the id and user name launching the action (visible in the log event for example)
- **Documentation and changelog plugin beta** : Documentation and changelog management for plugins in beta. Attention, in beta the changelog is not dated.
- **General** : Integration of the JeeXplorer plugin in the Core. Now used for Widget Code, and advanced customization.
- **Configuration** : New option in configuration / interface to not color the title banner of the equipment.
- **Configuration** : Possibility to configure wallpapers on the Dashboard, Analysis, Tools pages and their opacity according to the theme.
- **Configuration**: Adding Jeedom DNS based on Wireguard instead of Openvpn (Administration / networks). Faster, and more stable, but still in testing. Please note that this is currently not Jeedom Smart compatible.
- **Configuration** : OSDB settings: Addition of a tool for mass editing of equipment, commands, objects, scenarios.
- **Configuration** : OSDB settings: Adding a dynamic SQL query constructor.
- **Configuration**: Ability to disable cloud monitoring (Administration / Updates / Market).
- **jeeCLI** : Addition of ``jeeCli.php`` in the core / php folder of Jeedom to manage some command line functions.
- *Big improvements to the interface in terms of performance / responsiveness. jeedomUtils{}, jeedomUI{}, main menu rewritten in pure css, removal of initRowWorflow(), code simplification, css fixes for small screens, etc.*

### 4.2 : Core Widgets

- Widgets settings for the Mobile version are now accessible from the equipment configuration window in Dashboard Edit mode.
- The optional parameters available on widgets are now displayed for each widget, either in the command configuration or from the Dashboard edit mode.
- Many Core Widgets now accept optional color settings. (horizontal and vertical slider, gauge, compass, rain, shutter, templates slider, etc.).
- Core Widgets with display of a *time* now support an optional parameter **time : date** to display a relative date (Yesterday at 4:48 p.m., Last Monday at 2:00 p.m., etc).
- Cursor (action) type Widgets now accept an optional parameter *steps* to define the change step at the cursor.
- The Widget **action.slider.value** is now available in desktop, with an optional parameter *noslider*, which makes it a *input* simple.
- The Widget **info.numeric.default** (*Gauge*) has been redone in pure css, and integrated in mobile. They are therefore now identical in desktop and mobile.

### 4.2 : Cloud backup

We have added a confirmation of the cloud backup password to prevent entry errors (as a reminder the user is the only one to know this password, in case of forgetting, Jeedom can neither recover it nor access the backups. user's cloud).

>**IMPORTANT**
>
> Following the update, you MUST go to Settings → System → Configuration Update / Market tab and enter the cloud backup password confirmation so that it can be done.

### 4.2 : Security

- In order to significantly increase the security of the Jeedom solution, the file access system has changed. Before certain files were prohibited from certain locations. From v4.2, files are explicitly allowed by type and location.
- Change at the API level, previously "tolerant" if you arrived with the Core key indicating plugin XXXXX. This is no longer the case, you must arrive with the key corresponding to the plugin.
- In http API you could indicate a plugin name in type, this is no longer possible. The type corresponding to the type of the request (scenario, eqLogic, cmd, etc.) must correspond to the plugin. For example for the virtual plugin you had ``type=virtual`` in the url it is now necessary to replace by ``plugin=virtual&type=event``.
- Reinforcement of sessions : Change to sha256 with 64 characters in strict mode.

The Jeedom team is well aware that these changes can have an impact and be embarrassing for you, but we cannot compromise on safety.
The plugins must respect the recommendations on the tree structure of folders and files : [Doctor](https://doc.jeedom.com/en_US/dev/plugin_template).

[Blog: Jeedom 4 introduction.2 : Security](https://blog.jeedom.com/6165-introduction-jeedom-4-2-la-securite/)

# Changelog Jeedom V4.1

## 4.1.28

- Harmonization of widget templates for action / default commands

## 4.1.27

- Correction of a security vulnerability thanks @Maxime Rinaudo and @Antoine Cervoise from Synacktiv (>)

## 4.1.26

- Fixed an apt dependency installation problem on Smart due to the change of certificate at let's encrypt.

## 4.1.25

- Fixed apt dependency installation issue.

## 4.1.24

- Revision of the command configuration option **Managing the repetition of values** who becomes **Repeat identical values (Yes|Non)**. [See the blog article for more details](https://blog.jeedom.com/5414-nouvelle-gestion-de-la-repetition-des-valeurs/)

## 4.1.23

- Fixed bugs on history archiving
- Fixed a cache issue that could disappear during a reboot
- Fixed a bug on the management of repetitions of binary commands : in certain cases if the equipment sends twice 1 or 0 in a row, only the first ascent was taken into account. Please note that this bug correction can lead to an overload of the CPU. It is therefore necessary to update the plugins too (Philips Hue in particular) for other cases (multiple scenario triggering, whereas this was not the case before the update). question on the repetition of values (advanced configuration of the command) and change it to "never repeat" to find the way it was before.

## 4.1.22

- Addition of a system allowing Jeedom SAS to communicate messages to all Jeedom
- Switching Jeedom DNS to high availability mode

## 4.1.20

- Bugfix horizontal scroll on designs.
- Bugfix scroll on the plugins equipment pages.
- Bugfix of the color settings on the view / design links on a Design.
- Bugfix and optimization of the Timeline.
- Three-fingered review of mobile designs now limited to administrator profiles.

## 4.1.19

- Bugfix deletion of zone on a View.
- Bugfix js error that can appear on old browsers.
- Bugfix cmd.info.numeric.default.html if command not visible.
- Bugfix login page.

## 4.1.18

- Historical Bugfix on Designs.
- Bugfix searches in Analysis / History.
- Bugfix search on a variable, link to a device.
- Bugfix of colored summaries on synthesis.
- Bugfix on scenario comments with json.
- Bugfix on summary updates on Dashboard mode previews.
- Bugfix of elements *image* on a design.
- Added grouping options by time for charts on views.
- Conservation of the Synthesis context when clicking on the summaries.
- Centering of Synthesis images.

## 4.1.0

### 4.1 : Prerequisites

- Debian 10 Buster

### 4.1 : News / Improvements

- **Synthesis** : New page **Home → Synthesis** offering a global visual summary of the parts, with quick access to summaries.
- **Research** : Add of a search engine in **Tools → Search**.
- **Dashboard** : Edit mode now inserting the moved tile.
- **Dashboard** : Edit mode: the equipment refresh icons are replaced by an icon allowing access to their configuration, thanks to a new simplified modal.
- **Dashboard** : We can now click on the *time* time actions widgets to open the history window of the linked info command.
- **Dashboard** : The size of a new equipment&#39;s tile adapts to its content.
- **Dashboard** : Add (back!) A button to filter the displayed items by category.
- **Dashboard** : Ctrl Click on an info opens the history window with all the historicized commands of the equipment visible on the tile. Ctrl Click on a legend to display only this one, Alt Click to display them all.
- **Dashboard** : Redesign of the display of the object tree (arrow to the left of the search).
- **Dashboard** : Ability to blur background images (Configuration -> Interface).
- **Tools / Widgets** : Function *Apply on* shows the linked commands checked, unchecking one will apply the default core widget to this command.
- **Widgets** : Adding a core widget *sliderVertical*.
- **Widgets** : Adding a core widget *binarySwitch*.
- **Update Center** : Updates are checked automatically when opening this page and update check is older than 120mins.
- **Update Center** : The progress bar is now on the tab *Core and plugins*, and the log open by default on the tab *Information*.
- **Update Center** : If you open another browser during an update, the progress bar and the log indicate it.
- **Update Center** : If the update finishes correctly, display of a window asking to reload the page.
- **Core updates** : Implementation of a system for cleaning up old unused Core files.
- **Scenario** : Adding a search engine (to the left of the Run button).
- **Scenario** : Addition of the age function (gives the age of the value of the order).
- **Scenario** : *stateChanges()* now accept the period *today* (midnight to now), *yesterday* and *day* (for 1 day).
- **Scenario** : Functions *statistics (), average (), max (), min (), trend (), duration()* : Bugfix over the period *yesterday*, and accept now *day* (for 1 day).
- **Scenario** : Possibility to deactivate the automatic quote system (Settings → System → Configuration : Equipements).
- **Scenario** : Viewing a *warning* if no trigger is configured.
- **Scenario** : Bugfix of *select* on block copy / paste.
- **Scenario** : Copy / paste of block between different scenarios.
- **Scenario** : The undo / redo functions are now available as buttons (next to the block creation button).
- **Scenario** :  addition of "Historical export" (exportHistory)
- **Scenario variables window** : Alphabetical sort at opening.
- **Scenario variables window** : The scenarios used by the variables are now clickable, with opening of the search on the variable.
- **Analysis / History** : Ctrl Click on a legend to display only this history, Alt Click to display them all.
- **Analysis / History** : The options *grouping, type, variation, staircase* are active only with a single displayed curve.
- **Analysis / History** : We can now use the option *Area* with the option *Stairs*.
- **Analysis / Logs** : New monospace type font for logs.
- **Seen** : Possibility to put scenarios.
- **Seen** : Edit mode now inserting the moved tile.
- **Seen** : Edit mode: the equipment refresh icons are replaced by an icon allowing access to their configuration, thanks to a new simplified modal.
- **Seen** : The display order is now independent of that on the Dashboard.
- **Timeline** : Separation of History and Timeline pages.
- **Timeline** : Integration of the Timeline in DB for reliability reasons.
- **Timeline** : Management of multiple timelines.
- **Timeline** : Complete graphic redesign of the timeline (Desktop / Mobile).
- **Global Summary** : Summary view, support for summaries from a different object or with an empty root object (Desktop and WebApp).
- **Tools / Objects** : New tab *Summary by equipment*.
- **Domotic overview** : Plugin equipments deactivated and their controls no longer have the icons on the right (equipment configuration and advanced configuration).
- **Domotic overview** : Ability to search on equipment categories.
- **Domotic overview** : Possibility to move several pieces of equipment from one object to another.
- **Domotic overview** : Possibility to select all the equipment of an object.
- **Task engine** : On the tab *Daemon*, disabled plugins no longer appear.
- **Report** : The use of *chrome* if available.
- **Report** : Possibility to export timelines.
- **Configuration** : Tab *Information* is now in the tab *General*.
- **Configuration** : Tab *Orders* is now in the tab *Equipment*.
- **Advanced equipment configuration window** : Dynamic change of table configuration.
- **Equipment** : New Category *Opening*.
- **Equipment** : Possibility of inverting cursor type commands (info and action)
- **Equipment** : Possibility to add class css to a tile (see widget documentation).
- **About window** : Addition of links to Changelog and FAQ.
- Widgets / Objects / Scenarios / Interactions / Plugins Pages :
  - Ctrl Clic / Clic Center on a Widget, Object, Scenarios, Interaction, plugin equipment : Opens in a new tab.
  - Ctrl Clic / Clic Center also available in their context menus (on the tabs).
- New ModalDisplay page :
  - Analysis menu : Ctrl Click / Click Center on *Real time* : Open the window in a new tab, in full screen.
  - Tools menu : Ctrl Click / Click Center on *Ratings*, *Expression tester*, *Variables*, *Research* : Open the window in a new tab, in full screen.
- Code Block, File Editor, Advanced Customization : Dark theme adaptation.
- Improved image selection window.

### 4.1 : WebApp

- Integration of the new Synthesis page.
- Scenarios page, a click on the scenario title displays its log.
- We can now select / copy part of a log.
- On the search in a log, addition of an x button to cancel the search.
- Persistence of the theme toggle (8h).
- On a design, a click with three fingers returns to the home page.
- Display of scenarios by group.
- New monospace type font for logs.
- Many bug-fix (UI, portrait / landscape iOS, etc.).

### 4.1 : Autres

- **Documentation** : Adaptations in line with v4 and v4.1.
- **Documentation** : New page *Keyboard / mouse shortcuts* including a summary of all shortcuts in Jeedom. Accessible from the Dashboard doc or the FAQ.
- **Lib** : Update HighStock v7.1.2 to v8.2.0.
- **Lib** : Update jQuery v3.4.1 to v3.5.1.
- **Lib** : Update Font Awesome 5.9.0 to 5.13.1.
- **APIs** :  addition of an option to prohibit an api key of a plugin from executing core methods (general)
- Securing Ajax requests.
- Securing API calls.
- Bug fixes.
- Numerous desktop / mobile performance optimizations.

### 4.1 : Changements

- Function **scenario-> getHumanName()** of the php scenario class no longer returns *[object] [group] [name]* but *[group] [object] [name]*.
- Function **scenario-> byString()** must now be called with the structure *[group] [object] [name]*.
- Functions **network-> getInterfaceIp () network-> getInterfaceMac () network-> getInterfaces()** have been replaced by **network-> getInterfacesInfo()**

# Changelog Jeedom V4.0

## 4.0.62

- New buster + kernel migration for smart and Pro v2
- Verification of the OS version during important Jeedom updates

## 4.0.61

- Fixed a problem when applying a scenario template
- Addition of an option to disable SSL verification during communication with the market (not recommended but useful in certain specific network configuration)
- Fixed an issue with archiving history if the smoothing mode was forever
- Bug fixes
- Correction of the trigger () command in scenarios so that it returns the name of the trigger (without the #) instead of the value, for the value you must use triggerValue()

## 4.0.60

- Removal of new DNS system in eu.jeedom.link following too many operators who prohibit permanent http2 flows

## 4.0.59

- Fixed bugs on time widgets
- Increase in the number of bad passwords before banning (avoids problems with the webapp when rotating API keys)

## 4.0.57

- Reinforcement of cookie security
- Using chromium (if installed) for reports
- Fixed a problem with calculating state time on widgets if the jeedom time zone is not the same as that of the browser
- Bugfix

## 4.0.55

- The new dns (\*. Eu.jeedom.link) becomes the primary DNS (the old DNS still works)

## 4.0.54

- Start of the update to the new documentation site

## 4.0.53

- Bug fix.

## 4.0.52

- Bug correction (update to do absolutely if you are in 4.0.51).

## 4.0.51

- Bugfix.
- Optimization of the future DNS system.

## 4.0.49

- Possibility of choosing the Jeedom TTS engine and possibility of having plugins that offers a new TTS engine.
- Improved webview support in the mobile application.
- Bugfix.
- Doc update.

## 4.0.47

- Improved expression tester.
- Update of the repository on smart.
- Bugfix.

## 4.0.44

- Improved translations.
- Bugfix.
- Improved cloud backup restore.
- Cloud restoration now only retrieves the local backup, leaving the choice to download or restore it.

## 4.0.43

- Improved translations.
- Fixed bugs on scenario templates.

## 4.0.0

### 4.0 : Prerequisites

- Debian 9 Stretch

### 4.0 : News / Improvements

- Complete theme redesign (Core 2019 Light / Dark / Legacy).
- Possibility to change the theme automatically depending on the time.
- In mobile, the theme may change depending on the brightness (Requires to activate *generic extra sensor* in chrome, chrome page://flags).<br/><br/>
- Improvement and reorganization of the main menu.
- Plugins menu : The list of categories and plugins is now sorted alphabetically.
- Tools menu : Add a button to access the expression tester.
- Tools menu : Adding a button to access the variables.<br/><br/>
- Search fields now support accents.
- The search fields (Dashboard, scenarios, objects, widgets, interactions, plugins) are now active when the page is opened, allowing you to type a search directly.
- Added an X button on the search fields to cancel the search.
- During a search, the key *escape* cancel search.
- Dashboard : In edit mode, the search control and its buttons are disabled and become fixed.
- Dashboard : In edit mode, a click of a button *expand* to the right of objects resizes the tiles of the object to the height of the highest. Ctrl + click reduces them to the height of the lowest.
- Dashboard : Command execution on a tile is now signaled by the button *refresh*. If there is none on the tile, it will appear during execution.
- Dashboard : The tiles indicate an info command (historized, which will open the History window) or action on hover.
- Dashboard : The history window now allows you to open this history in Analysis / History.
- Dashboard : The history window keeps its position / dimensions when reopening another history.
- Command Configuration Window: Ctrl + click on "Save" closes the window after.
- Equipment configuration window: Ctrl + click on "Save" closes the window after.
- Add usage information when deleting a device.
- Objects : Added option to use custom colors.
- Objects : Addition of a contextual menu on the tabs (quick object change).
- Interactions : Addition of a contextual menu on the tabs (quick change of interaction).
- Plugins : Addition of a contextual menu on the tabs (quick change of equipment).
- Plugins : On the Plugins management page, an orange point indicates the plugins in non-stable version.
- Table improvements with filter and sort option.
- Possibility to assign an icon to an interaction.
- Each Jeedom page now has a title in the interface language (browser tab).
- Prevention of auto-filling on 'Access code' fields'.
- Function management *Previous page / Next page* from the browser.<br/><br/>
- Widgets : Redesign of the widget system (Tools / Widgets menu).
- Widgets : Possibility to replace a widget by another on all the commands using it.
- Widgets : Possibility of assigning a widgets to several commands.
- Widgets : Adding a horizontal numeric info widget.
- Widgets : Adding a vertical numeric info widget.
- Widgets : Addition of a numeric compass / wind info widget (thanks @thanaus).
- Widgets : Added a numeric rain info widget (thanks @thanaus)
- Widgets : Display of the info / action shutter widget proportional to the value.<br/><br/>
- Configuration : Improvement and reorganization of tabs.
- Configuration : Addition of many *tooltips* (aide).
- Configuration : Adding a search engine.
- Configuration : Added a button to empty the cache of widgets (Cache tab).
- Configuration : Added an option to disable the cache of widgets (Cache tab).
- Configuration : Possibility of vertically centering the content of the tiles (Interface tab).
- Configuration : Added a parameter for the global purge of logs (Orders tab).
- Configuration : Change of #message# at #subject# in Configuration / Logs / Messages to avoid duplicating the message.
- Configuration : Possibility in the summaries to add an exclusion of orders that have not been updated for more than XX minutes (example for the calculation of temperature averages if a sensor has not reported anything for more than 30min it will be excluded from the calculation)<br/><br/>
- Scenario : The colorization of blocks is no longer random, but by block type.
- Scenario : Possibility by doing a Ctrl + click on the button *execution* save it, launch it, and display the log (if the log level is not on *None*).
- Scenario : Confirmation of block deletion. Ctrl + click to avoid confirmation.
- Scenario : Addition of a search function in Code blocks. To research : Ctrl + F then Enter, Next result : Ctrl + G, Previous result : Ctrl+Shift+G
- Scenario : Possibility of condensing the blocks.
- Scenario : The 'Add block' action switches to the Scenario tab if necessary.
- Scenario : New block copy / paste functions. Ctrl + click to cut / replace.
- Scenario : A new block is no longer added at the end of the scenario, but after the block where you were before clicking, determined by the last field you clicked.
- Scenario : Setting up an Undo / Redo system (Ctrl + Shift + Z / Ctrl + Shift + Y).
- Scenario : Remove scenario sharing.
- Scenario : Improvement of the scenario template management window.<br/><br/>
- Analysis / Equipment : Addition of a search engine (Batteries tab, search on names and parents).
- Analysis / Equipment : The calendar / equipment day zone can now be clicked to directly access the battery change (s).
- Analysis / Equipment : Adding a search field.<br/><br/>
- Update Center : Warning on the 'Core and plugins' and / or 'Others' tab if an update is available. Switch to 'Others' if necessary.
- Update Center : differentiation by version (stable, beta, ...).
- Update Center : addition of a progress bar during the update.<br/><br/>
- Domotic overview : The history of deletions is now available in a tab (Summary - History).
- Domotic overview : Complete redesign, possibility of ordering objects, equipment, orders.
- Domotic overview : Adding equipment and order IDs, to display and search.
- Domotic overview : CSV export of parent object, id, equipment and their id, command.
- Domotic overview : Possibility of making visible or not one or more commands.<br/><br/>
- Design : Possibility to specify the order (position) of the *Designs* and *3d designs* (Edit, Configure Design).
- Design : Addition of a custom CSS field on the elements of the *design*.
- Design : Moved the display options in Design of the advanced configuration, in the display parameters from the *Design*. This in order to simplify the interface, and to allow to have different parameters by *Design*.
- Design : Moving and resizing components on *Design* takes their size into account, with or without magnetization.<br/><br/>
- Addition of a mass configuration system (used on the Equipment page to configure Communications Alerts on them)

### 4.0 : Autres

- **Lib** : Update jquery 3.4.1
- **Lib** : Update CodeMiror 5.46.0
- **Lib** : Update tablesorter 2.31.1
- General lightening (css / inline styles, refactoring, etc.) and performance improvements.
- Addition of global compatibility of Jeedom DNS with a 4G internet connection.
- Numerous bug fixes.
- Security fixes.

### 4.0 : Changements

- Remove Font Awesome 4 to keep only Font Awesome 5.
- The widget plugin is not compatible with this version of Jeedom and will no longer be supported (because the features have been taken internally on the core). More information [here](https://www.Jeedom.com/blog/4368-les-widgets-en-v4).

>**IMPORTANT**
>
> If after the update you have an error on the Dashboard, try to restart your box so that it takes the new additions of components into account.
