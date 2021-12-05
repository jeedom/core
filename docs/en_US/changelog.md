# Changelog Jeedom V4.2

## 4.2.0

### 4.2 : Prerequisites

- Debian 10 Buster
- Php 7.3

### 4.2 : News / Improvements

- **Synthesis** : Possibility of configuring objects to go to a *design* or a *view* since the synthesis.
- **Dashboard** : The device configuration window (edit mode) now allows you to configure mobile widgets and generic types.
- **Widgets** : Internationalization of third-party Widgets (user code). see [Doc dev](https://doc.jeedom.com/en_US/dev/core4.2).
- **Analysis / History** : Possibility to compare a history over a given period.
- **Analysis / History** : Display of multiple Y axes with their own scale.
- **Analysis / Equipment** : Orphan orders now display their name and date of deletion if still in the deletion history, as well as a link to the affected scenario or equipment.
- **Analysis / Logs** : Log line numbering. Possibility to display the raw log.
- **Logs** : Coloration of logs according to certain events. Possibility to display the raw log.
- **Summaries** : Possibility to define a different icon when the summary is null (no shutters open, no light on, etc).
- **Summaries** : Possibility to never display the number to the right of the icon, or only if it is positive.
- **Summaries** : The change of summary parameter in configuration and on objects is now visible, without waiting for a change in summary value.
- **Summaries** : It is now possible to configure [actions on summaries](/en_US/concept/summary#Actions sur résumés) (ctrl + click on a summary) thanks to the virtual ones.
- **Types of equipment** : [New page](/en_US/core/4.2/types) **Tools → Equipment types** allowing generic types to be assigned to devices and commands, with support for types dedicated to installed plugins (see [Doc dev](https://doc.jeedom.com/en_US/dev/core4.2)).
- **Selection of illustrations** : New global window for the choice of illustrations *(icons, images, backgrounds)*.
- **Table display** : Addition of a button to the right of the search on the pages *Objects* *Scenarios* *Interactions* *Widgets* and *Plugins* to switch to table mode. This is stored by a cookie or in **Settings → System → Configuration / Interface, Options**. The plugins can use this new function of the Core. see [Doc dev](https://doc.jeedom.com/en_US/dev/core4.2).
- **Equipment configuration** : Possibility of configuring a history curve at the bottom of the tile of a device.
- **Ordered** : Possibility of making a calculation on a command action of type slider before execution of the command.
- **Plugins / Management** : Display of the plugin category, and a link to directly open its page without going through the Plugins menu.
- **Scenario** : Code fallback function (*code folding*) in the *Code Blocks*. Ctrl + Y and Ctrl + I shortcuts.
- **Scenario** : Copy / paste and undo / redo bugfix (complete rewrite).
- **Scenario** : Adding calculation functions ````averageTemporal(commande,période)```` & ````averageTemporalBetween(commande,start,end)```` allowing to obtain the average weighted by the duration over the period.
- **Scenario** : Added support for Generic Types in scenarios.
	- Trigger : ``#genericType(LIGHT_STATE,#[Salon]#)# > 0`
	- IF `genericType (LIGHT_STATE,#[Salon]#) > 0`
	- `GenericType` action
- **Objects** : Plugins can now request specific parameters specific to objects.
- **Users** : Plugins can now request specific parameters specific to users.
- **Users** : Ability to manage the profiles of different Jeedom users from the user management page.
- **Users** : Ability to hide objects / view / design / 3d design for limited users.
- **Updates Center** : Update Center now displays the date of the last update.
- **Adding the user performing an action** : Addition in the command execution options of the id and user name launching the action (visible in the log event for example)
- **Documentation and changelog plugin beta** : Documentation and changelog management for plugins in beta. Attention, in beta the changelog is not dated.
- **Main** : Integration of the JeeXplorer plugin in the Core. Now used for Widget Code, and advanced customization.
- **Configuration** : New option in configuration / interface to not color the title banner of the equipment.
- **Configuration** : Possibility to configure wallpapers on the Dashboard, Analysis, Tools pages and their opacity according to the theme.
- **Configuration**: Adding Jeedom DNS based on Wireguard instead of Openvpn (Administration / networks). Faster, and more stable, but still in testing. Please note that this is currently not Jeedom Smart compatible.
- **Configuration** : OSDB settings: Addition of a tool for mass editing of equipment, commands, objects, scenarios.
- **Configuration** : OSDB settings: Adding a dynamic SQL query constructor.
- **Configuration**: Possibility to deactivate cloud monitoring (Administration / Updates / Market).
- **jeeCLI** : Addition of ````jeeCli.php```` in the core / php folder of Jeedom to manage some command line functions.
- *Big improvements to the interface in terms of performance / responsiveness. jeedomUtils {}, jeedomUI {}, main menu rewritten in pure css, removal of initRowWorflow (), simplification of the code, css fixes for small screens, etc.*

### 4.2 : Core Widgets

- Widgets settings for the Mobile version are now accessible from the equipment configuration window in Dashboard Edit mode.
- The optional parameters available on widgets are now displayed for each widget, either in the command configuration or from the Dashboard edit mode.
- Many Core Widgets now accept optional color settings. (horizontal and vertical slider, gauge, compass, rain, shutter, templates slider, etc.).
- Core Widgets with display of a *time* now support an optional parameter **time : dated** to display a relative date (Yesterday at 4:48 p.m., Last Monday at 2:00 p.m., etc).
- Cursor (action) type Widgets now accept an optional parameter *step* to define the change step at the cursor.
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
- In http API you could indicate a plugin name in type, this is no longer possible. The type corresponding to the type of the request (scenario, eqLogic, cmd, etc.) must correspond to the plugin. For example for the virtual plugin you had ````type=virtual```` in the url it is now necessary to replace by ````plugin=virtual&type=event````.
- Reinforcement of sessions : Change to sha256 with 64 characters in strict mode.
- The "stay connected" cookie (3 months max) is now "one shot", renewed with each use.

The Jeedom team is well aware that these changes can have an impact and be embarrassing for you, but we cannot compromise on safety.
The plugins must respect the recommendations on the tree structure of folders and files : [Doc](https://doc.jeedom.com/en_US/dev/plugin_template).

# Changelog Jeedom V4.1

## 4.1.27

- Correction of a security breach thank you @Maxime Rinaudo and @Antoine Cervoise from Synacktiv (www.synacktiv.com)

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
- Bugfix of elements *picture* on a design.
- Added grouping options by time for charts on views.
- Conservation of the Synthesis context when clicking on the summaries.
- Centering of Synthesis images.

## 4.1.0

### 4.1 : Prerequisites

- Debian 10 Buster

### 4.1 News / Improvements

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
- **Analysis / History** : We can now use the option *Area* with the option *Staircase*.
- **Analysis / Logs** : New monospace type font for logs.
- **View** : Possibility to put scenarios.
- **View** : Edit mode now inserting the moved tile.
- **View** : Edit mode: the equipment refresh icons are replaced by an icon allowing access to their configuration, thanks to a new simplified modal.
- **View** : The display order is now independent of that on the Dashboard.
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
- **Report** : The use of *chromium* if available.
- **Report** : Possibility to export timelines.
- **Configuration** : The tab *Information* is now in the tab *Main*.
- **Configuration** : The tab *Orders* is now in the tab *Equipment*.
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
	- Tools menu : Ctrl Click / Click Center on *Notes*, *Expression tester*, *Variables*, *Research* : Open the window in a new tab, in full screen.
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
- **API** :  addition of an option to prohibit an api key of a plugin from executing core methods (general)
- Securing Ajax requests.
- Securing API calls.
- Bug fixes.
- Numerous desktop / mobile performance optimizations.

### 4.1 : Changements
- Function **scenario-> getHumanName()** of the php scenario class no longer returns *[object] [group] [name]* But *[group] [object] [name]*.
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

- Removal of the new DNS system in eu.jeedom.link following too many operators which forbid permanent http2 flows

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
- Scenario : Possibility by doing a Ctrl + click on the button *execution* save it, launch it, and display the log (if the log level is not on *No*).
- Scenario : Confirmation of block deletion. Ctrl + click to avoid confirmation.
- Scenario : Addition of a search function in Code blocks. Search : Ctrl + F then Enter, Next result : Ctrl + G, Previous result : Ctrl + Shift + G
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
