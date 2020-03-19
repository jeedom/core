
Changelog
=========

4.1.0
=====
- Dashboard : Edit mode now inserting the moved tile.
- Dashboard : We can now click on the * time * of the time actions widgets to open the history window of the linked info command..
- Dashboard : The size of a new equipment&#39;s tile adapts to its content.
- Synthesis : Adding a new page **Home → Summary** offering a global visual synthesis of the parts.
- Update Center : Updates are checked automatically when the page is opened if it is 120 mins older.
- Update Center : The progress bar is now on the * Core and plugins * tab, and the log open by default on the * Information tab*.
- Update Center : If you open another browser during an update, the progress bar and the log indicate it.
- Update Center : If the update finishes correctly, display of a window asking to reload the page.
- Core updates : Implementation of a system for cleaning up old unused Core files.
- Widget / Objects / Scenarios / Interactions / Plugins Pages :
	- Ctrl Clic / Clic Center on a Widget, Object, Scenarios, Interaction, plugin equipment : Opens in a new tab.
	- Ctrl Clic / Clic Center also available in their context menus (on the tabs).
- New ModalDisplay page:
	- Analysis menu : Ctrl Click / Click Center on * Real time* : Open the window in a new tab, in full screen.
	- Tools menu : Ctrl Click / Click Center on * Notes *, * Expression tester *, * Variables* : Open the window in a new tab, in full screen.
- Scenario : Adding a search engine (to the left of the Run button).
- Scenario : Addition of the age function (gives the age of the value of the order).
- Scenario : *stateChanges () * now accepts the period * today * (from midnight to now), * yesterday * and * day * (for 1 day).
- Scenario : Functions * statistics (), average (), max (), min (), trend (), duration ()* : Bugfix for the period * yesterday *, and now accepts * day * (for 1 day).
- Scenario : Possibility to deactivate the automatic quote system (Settings → System → D'actualité : Orders).
- Scenario : Display of a * warning * if no trigger is configured.
- Scenario : bugfix of select on copy / paste block.
- Scenario : copy / paste of block between different scenarios.
- Scenario variables window : alphabetical sort at opening.
- Analysis / History : Ctrl Click on a legend to display only this history, Alt Click to display them all.
- Analysis / History : The options * grouping, type, variation, staircase * are active only with a single curve displayed.
- Analysis / History : We can now use the * Area * option with the * Staircase option*.
- Dashboard : Ctrl Click on an info opens the history window with all the historicized commands of the equipment visible on the tile. Ctrl Click on a legend to display only this one, Alt Click to display them all.
- View : possibility to put scenarios.
- Tools / Widget : The * Apply on * function shows the linked commands checked, unchecking one will apply the default core widget on this command.
- Integration of the Timeline in DB for reliability reasons.
- Management of multiple timelines.
- Home automation summary : Plugin equipments deactivated and their controls no longer have the icons on the right (equipment configuration and advanced configuration).
- Task engine : On the * Daemon * tab, deactivated plugins no longer appear.
- Advanced equipment configuration window : Dynamic change of switchboard configuration.
- About window : Addition of shortcuts to Changelog and FAQ.
- WebApp : Integration of the new Summary page.
- WebApp : Scenarios page, a click on the scenario title displays its log.
- WebApp : We can now select / copy part of a log.
- WebApp : On the search in a log, addition of an x button to cancel the search.
- WebApp : Persistence of the theme toggle (8h).
- WebApp : Many bug-fix (UI, portrait / landscape iOS, etc.).
- Documentation : Adaptations in line with v4 and v4.1.
- Documentation : New page * Keyboard / mouse shortcuts * including a summary of all shortcuts in Jeedom. Accessible from the Dashboard doc or the FAQ.
- Bug fixes and optimizations.
- Widget : possibilité d'ajouté des class css à un widget (voir documentation widget)

4.0.43
=====

- Improved translations.
- Bug fixes on scenario templates.

4.0.0
=====
- Complete redesign of themes (Core 2019 Light / Dark / Legacy).
- Possibility to change the theme automatically according to the time.
- In mobile, the theme can change depending on the brightness (Requires activating * generic extra sensor * in chrome, chrome page:// flags). <br/><br/>
- Improvement and reorganization of the main menu.
- Plugins menu : The list of categories and plugins is now sorted alphabetically.
- Tools menu : Addition of a button to access the expression tester.
- Tools menu : Addition of a button to access the variables. <br/><br/>
- Search fields now support accents.
- The search fields (Dashboard, scenarios, objects, widgets, interactions, plugins) are now active when the page opens, allowing you to type a search directly.
- Add an X button on the search fields to cancel the search.
- During a search, the * escape * key cancels the search.
- Dashboard : In edit mode, the search field and its buttons are disabled and become fixed.
- Dashboard : In edit mode, a click on an * expand * button to the right of the objects resizes the tiles of the object to the height of the highest. Ctrl + click reduces them to the height of the lowest.
- Dashboard : Order execution on a tile is now indicated by the * refresh button*. If there is none on the tile, it will appear during the execution.
- Dashboard : The tiles indicate an info command (history, which will open the History window) or action on hover.
- Dashboard : The history window now allows you to open this history in Analysis / History.
- Dashboard : History window retains its position / dimensions when another history reopens.
- Command D'actualité window: Ctrl + click on &quot;Save&quot; closes the window after.
- Equipment D'actualité window: Ctrl + click on &quot;Save&quot; closes the window after.
- Adding usage information when deleting equipment.
- Objects : Added option to use custom colors.
- Objects : Add context menu on tabs (quick object change).
- Interactions : Add context menu on tabs (quick interaction change).
- Plugins : Add context menu on tabs (quick change of equipment).
- Plugins : On the Plugins management page, an orange dot indicates non-stable plugins.
- Table improvements with filter and sort option.
- Ability to assign an icon to an interaction.
- Each Jeedom page now has a title in the interface language (browser tab).
- Prevention of auto-filling on fields&#39; Access code'.
- Management of functions * Previous page / Next page * of the browser. <br/><br/>
- Widget : Redesign of the widget system (Tools / Widget menu).
- Widget : Ability to replace a widget with another on all commands using it.
- Widget : Ability to assign a widget to multiple commands.
- Widget : Add horizontal info numeric widget.
- Widget : Adding an info numeric vertical widget.
- Widget : Addition of an info numeric compass / wind widget (thanks @thanaus).
- Widget : Adding an info numeric rain widget (thanks @thanaus)
- Widget : Display of the info / action shutter widget proportional to the value. <br/><br/>
- D'actualité : Improvement and reorganization of tabs.
- D'actualité : Added many * tooltips * (help).
- D'actualité : Adding a search engine.
- D'actualité : Adding a button to empty the widget cache (Cache tab).
- D'actualité : Added option to disable widget cache (Cache tab).
- D'actualité : Ability to center the content of the tiles vertically (Interface tab).
- D'actualité : Addition of a parameter for the global purging of the histories (Tab Commands).
- D'actualité : Change from # message # to # subject # in D'actualité / Logs / Messages to avoid duplication of the message.
- D'actualité : Possibility in the summaries to add an exclusion of the orders which have not been updated for more than XX minutes (example for the calculation of the temperature averages if a sensor has not raised anything for more than 30min it will be excluded from the calculation ) <br/><br/>
- Scenario : The colorization of the blocks is no longer random, but by type of block.
- Scenario : Possibility by Ctrl + click on the button * execution * to save it, launch it, and display the log (if the log level is not on * None *).
- Scenario : Block deletion confirmation. Ctrl + click to avoid confirmation.
- Scenario : Addition of a search function in the Code blocks. Search : Ctrl + F then Enter, Next result : Ctrl + G, Previous result : Ctrl + Shift + G
- Scenario : Ability to condense blocks.
- Scenario : The &#39;Add block&#39; action switches to the Scenario tab if necessary.
- Scenario : New block copy / paste functions. Ctrl + click to cut / replace.
- Scenario : A new block is no longer added at the end of the scenario, but after the block where you were before clicking, determined by the last field in which you clicked.
- Scenario : Implementation of an Undo / Redo system (Ctrl + Shift + Z / Ctrl + Shift + Y).
- Scenario : Delete scenario sharing.
- Scenario : Improvement of the scenario templates management window. <br/><br/>
- Analysis / Equipment : Addition of a search engine (Batteries tab, search on names and parents).
- Analysis / Equipment : The calendar / days area of the equipment is now clickable to directly access the change of battery (s).
- Analysis / Equipment : Addition of a search field. <br/><br/>
- Update Center : Warning on the &#39;Core and plugins&#39; and / or &#39;Others&#39; tab if an update is available. Switch to &#39;Others&#39; if necessary.
- Update Center : differentiation by version (stable, beta, ...).
- Update Center : addition of a progress bar during the update. <br/><br/>
- Home automation summary : The deletion history is now available in a tab (Summary - History).
- Home automation summary : Complete overhaul, possibility of ordering objects, equipment, orders.
- Home automation summary : Addition of equipment and order IDs, in display and in search.
- Home automation summary : CSV export of parent object, id, equipment and their id, command.
- Home automation summary : Possibility of making visible or not one or more orders. <br/><br/>
- Design : Ability to specify the order (position) of * Designs * and * 3D Designs * (Edit, Configure Design).
- Design : Addition of a custom CSS field on the elements of * design*.
- Design : Displacement of display options in Design of the advanced configuration, in the display settings from * Design*. This in order to simplify the interface, and allow to have different parameters by * Design*.
- Design : Moving and resizing components on * Design * takes their size into account, with or without magnetization. <br/><br/>
- General reduction (css / inline styles, refactoring, etc.) and performance improvements.
- Remove Font Awesome 4 to keep only Font Awesome 5.
- Libs update : jquery 3.4.1, CodeMiror 5.46.0, tablesorter 2.31.1.
- Numerous bug fixes.
- Adding a mass configuration system (used on the Equipment page to configure the Communication Alert on these)

>**Important**
>
>If after the update you have an error on the Dashboard try to restart your box so that it takes the new additions of components into account.

>**Important**
>
>The widget plugin is not compatible with this version of Jeedom and will no longer be supported (because the functions have been taken over internally on the core). More information [here] (https://www.jeedom.com/blog/4368-les-widgets-en-v4).

3.3.39
=====

- Changed variable name $ key to $ key2 in class event.
- Cleaning the plugin / widget / scenario sending code to the market (saves several seconds on displaying plugins).
- Correction of a warning on the lastBetween function.
- Better consideration of plugin widgets.
- Optimization of the health calculation on the swap.

>**Important**
>
>This update fixes a concern that may prevent any history recording as of January 1, 2020, it is more than highly recommended.

3.3.38
=====

- Added global compatibility of Jeedom DNS with 4G Internet connection. (Important : if you use Jeedom DNS is that you have a 4G connection, you must check in the configuration of Jeedom DNS the corresponding box).
- Spelling corrections.
- Security fix

3.3.37
=====

- Bugfix.

3.3.36
=====

- Addition of rounding on the number of days since the last battery change.
- Bugfix

3.3.35
=====

- Bugfix.
- Possibility to install plugins directly from the online market.

3.3.34
=====

- Fixed a bug that could prevent the batteries from going back up.
- Correction of a bug on tags in interactions.
- The &quot;timeout&quot; status (non communication) of the equipment now has priority over the warning or danger status.
- Bug fix on cloud backups.


3.3.33
=====

- Bugfix.

3.3.32
=====

- Bugfix
- Mobile support for sliders on designs.
- SMART : optimization of swap management.

3.3.31
=====

- Bugfix.

3.3.30
=====

- Correction of a bug on the display of user sessions.
- Documentation update.
- Removal of updating graphics in real time, following numerous bugs reported.
- Correction of a bug which could prevent the display of certain logs.
- Correction of a bug on the monitoring service.
- Correction of a bug on the &quot;Equipment analysis&quot; page, the battery update date is now correct.
- Improvement of the action * remove_inat * in the scenarios.

3.3.29
=====

- Correction of the disappearance of the date of last check for updates.
- Fixed a bug that could block cloud backups.
- Correction of a bug on the calculation of the use of the variables if it is of the variable form (toto, mavaleur).

3.3.28
=====

- Fixed an infinite wheel bug on the updates page.
- Various corrections and optimizations.

3.3.27
=====

- Correction of a bug on the translation of the days in French.
- Improved stability (auto restart of the MySql service and watchdog to check the time on startup).
- Bugfix.
- Disable actions on orders when editing designs, views or Dashboards.

3.3.26
=====

- Bugfix.
- Correction of a bug on the multi-launch of scenario.
- Correction of a bug on the alerts on the value of the orders.

3.3.25
=====
- Bug fix.
- Switching the timeline to table mode (due to errors in the independent Jeedom lib).
- Addition of classes for color supports in the mode plugin.

3.3.24
=====
-   Correction of a bug on the display of the update number.
-	Removed editing of html code from the advanced configuration of commands due to too many bugs.
-	Bug fixes.
-	Improvement of the icon selection window
-	Automatic update of the battery change date if the battery is more than 90% and 10% higher than the previous value.
-	Addition of button on the administration to reset the rights and launch a Jeedom verification (right, cron, database ...).
-	Removal of advanced visibility choices for equipment on Dashboard / view / design / mobile. Now if you want to see or not the equipment on Dashboard / mobile just check or not the general visibility box. For views and design just put or not the equipment on it.

3.3.22
=====
- Bug fixes.
- Improved order replacement (in views, plan and plan3d).
- Fixed a bug that could prevent opening certain plugin equipment (alarm or virtual type).

3.3.21
=====
- Fixed a bug where the time display could exceed 24h.
- Correction of a bug on the update of design summaries.
- Correction of a bug on the management of the levels of alerts on certain widgets during the update of the value.
- Fixed display of disabled equipment on some plugins.
- Correction of a bug when indicating battery change at Jeedom.
- Improved display of logs when updating Jeedom.
- Bug fix during the variable update (which did not always launch the scenarios or did not trigger an update of the commands in all cases).
- Fixed a bug on Cloud backups, or duplicity was not installing correctly.
- Improvement of internal TTS in Jeedom.
- Improvement of the cron syntax checking system.


3.3.20
=====
- Correction of a bug on the scenarios or they could remain blocked at &quot;in progress&quot; while they are deactivated.
- Fixed an issue with launching an unplanned scenario.
- Time zone bug fix.

3.3.19
=====
- Bug fixes (especially during the update).


3.3.18
=====
- Bugfix.

3.3.17
=====
- Correction of an error on samba backups.

3.3.16
=====
- Ability to delete a variable.
- Addition of a 3D display (beta).
- Redesign of the cloud backup system (incremental and encrypted backup).
- Adding an integrated note taking system (in Analysis -&gt; Note).
- Addition of the notion of tag on equipment (can be found in the advanced configuration of equipment).
- Addition of a history system on the deletion of orders, equipment, objects, view, design, 3d design, scenario and user.
- Addition of the Jeedom_reboot action to launch a restart of Jeedom.
- Add option in the cron generation window.
- A message is now added if an invalid expression is found when executing a scenario.
- Adding a command in the scenarios : value (order) allows to have the value of an order if it is not automatically given by Jeedom (case when storing the name of the order in a variable).
- Addition of a button to refresh the messages of the message center.
- Add in the configuration of action on value of a command a button to search for an internal action (scenario, pause ...).
- Addition of an action “Reset to zero of the IS” on the scenarios.
- Ability to add images in the background on the views.
- Ability to add background images on objects.
- Update information available is now hidden from non-admin users.
- Improved support for () in calculating expressions.
- Possibility to edit the scenarios in text / json mode.
- Addition on the health page of a free space check for the Jeedom tmp.
- Ability to add options in reports.
- Addition of a heartbeat by plugin and automatic restart of the daemon in case of problems.
- Addition of listeners on the task engine page.
- Optimizations.
- Possibility to consult the logs in mobile version (wepapp).
- Addition of an action tag in the scenarios (see documentation).
- Possibility to have a full screen view by adding &quot;&amp; fullscreen = 1&quot; in the url.
- Addition of lastCommunication in the scenarios (to have the last communication date of an equipment).
- Real-time update of graphs (simple, not calculated or timelines).
- Ability to delete an element from the design configuration.
- Possibility of having a report on the battery level (equipment report).
- Scenario widgets are now displayed by default on the Dashboard.
- Change the pitch of the widgets by horizontal 25 to 40, vertical 5 to 20 and margin 1 to 4 (you can reset the old values in the configuration of Jeedom, tab widget).
- Possibility to put an icon on the scenarios.
- Display of mobile widgets in a single column.
- Added management of daemons on the task engine.
- Addition of the color_gradient function in the scenarios.

3.2.16
=====
- Correction of a bug when installing dependencies of certain plugins on Smart.

3.2.15
=====
- Correction of a bug when saving equipment.

3.2.14
=====
- Preparation to avoid an error when switching to 3.3.X.
- Correction of a problem when requesting support for third-party plugins.

3.2.12
=====
- Bugfix.
- Optimizations.

3.2.11
=====
- Bugfix.

3.2.10
=====
- Bugfix.
- Improved synchronization with the Market.
- Improvement of the update process in particular in the copying of files which now checks the size of the copied file.
- Bug fixes on the stateDuration, lastStateDuration and lastChangeStateDuration functions (thanks @kiboost).
- Optimization of the link graph calculation and the use of variables.
- Improvement of the cron task details window which now displays the scenario as well as the action to be performed for doIn tasks (thanks @kiboost).

3.2.9
=====
- Bugfix.
- Correction of a bug on the icons of the file editor and on the expression tester.
- Bug fixes on listenners.
- Addition of an alert if a plugin blocks crons.
- Correction of a bug in the cloud monitoring system if the agent version is less than 3.X.X.

3.2.8
=====
- Bugfix.
- Addition of an option in the Jeedom administration to specify the local ip range (useful in docker type installations).
- Correction of a bug on the calculation of the use of variables.
- Addition of an indicator on the health page giving the number of processes killed by lack of memory (overall indicates that the Jeedom is too loaded).
- Improved file editor.

3.2.7
=====
- Bugfix.
- Docs update.
- Ability to use tags in the conditions of the "A" and "IN blocks".
- Bug correction of market categories for widgets / scripts / scenarios...

3.2.6
=====
- Bugfix.
- Docs update.
- Standardization of the names of certain orders in the scenarios.
- Performance optimization.

3.2.5
=====
- Bugfix.
- Reactivation of interactions (inactive because of the update).

3.2.4
=====
- Bugfix.
- Correction of a bug on certain modal in Spanish.
- Correction of a calculation error on time_diff.
- Preparation for the future alerting system.

3.2.3
=====
- Bugfix on min / max functions.
- Improved export of graphics and display in table mode.

3.2.2
=====
- Removal of the old widget update system (deprecated since version 3.0). Please note if your widget does not use the new system there is a risk of malfunction (.duplication of it in this case). Example widget [here] (https://github.com/jeedom/core/tree/beta/core/template/dashboard)
- Possibility to display the graphs in table form or to export these in csv or xls.
- Users can now add their own php function for scenarios. See documentation of scenarios for implementation.
- JEED-417 : addition of a time_diff function in the scenarios.
- Addition of a configurable delay before response on interactions (allows to wait for the status feedback to take place for example).
- JEED-365 : Removal of &quot;User information command&quot; to be replaced by actions on message. Allows you to launch several different commands, to launch a scenario ... Caution .if you had a &quot;User information command&quot; it must be reconfigured.
- Add an option to easily open an access for the support (on the user page and when opening a ticket).
- Correction of a rights bug following a restore of a backup.
- Updating translations.
- Library update (jquery and highcharts).
- Ability to prohibit an order in automatic interactions.
- Improved automatic interactions.
- Bug correction on the synonym management of interactions.
- Addition of a user search field for LDAP / AD connections (makes Jeedom AD compatible).
- Spelling corrections (thanks to dab0u for his enormous work).
- JEED-290 : We can no longer connect with the default identifiers (admin / admin) remotely, only the local network is authorized.
- JEED-186 : We can now choose the background color in the designs.
- For block A, possibility of putting an hour between 12:01 am and 12:59 am by simply putting the minutes (ex 30 for 12:30 am).
- Adding active sessions and registered devices to the user profile page and the user management page.
- JEED-284 : the permanent connection now depends on a unique user and peripheral key (and no longer that user).
- JEED-283 : adding a * rescue * mode to Jeedom by adding &amp; rescue = 1 in the url.
- JEED-8 : addition of the name of the scenario on the title of the page during the edition.
- Optimization of organizational changes (size of widgets, position of equipment, position of commands) on the Dashboard and views. Attention now the modifications are not .saved only when exiting edit mode.
- JEED-18 : Adding logs when opening a ticket to support.
- JEED-181 : addition of a name command in the scenarios to have the name of the command or of the equipment or object.
- JEED-15 : Add battery and alert on webapp.
- Correction of bugs for moving design objects in Firefox.
- JEED-19 : During an update it is now possible to update the update script before the update.
- JEED-125 : added link to password reset documentation.
- JEED-2 : Improved time management during a restart.
- JEED-77 : Addition of variable management in the http API.
- JEED-78 : addition of the tag function for scenarios. Please note that in scenarios using tags, switch from \ #montag \ # to tag (montag).
- JEED-124 : Correct the management of scenario timeouts.
- Bugfix.
- Ability to deactivate an interaction.
- Adding a file editor (reserved for experienced users).
- Addition of generics Types &quot;State Light&quot; (Binary), &quot;Light Color Temperature&quot; (Info), &quot;Light Color Temperature&quot; (Action).
- Ability to make words mandatory in an interaction.

3.1.7
=====
- Bug fixes (especially on histories and statistical functions).
- Improvement of the update system with a page of release notes (which you must check yourself before each update.).
- Correction of a bug which recovered the logs during restores.

3.1
===
- Bugfix.
- Global optimization of Jeedom (on loading plugin classes, time almost divided by 3).
- Debian 9 support.
- Onepage mode (page change without reloading the whole page, just the part that changes).
- Addition of an option to hide the objects on the Dashboard but which allows to always have them in the list.
- Double-clicking on a node on the link graph (except for variables) brings up its configuration page.
- Ability to put text left / right / center on designs for text / view / design elements.
- Adding object summaries on the Dashboard (list of objects on the left).
- Add interactions of type "notify me-if".
- Scenario home page review.
- Adding a command history for SQL or system commands in the Jeedom interface.
- Possibility to have the graphs of histories of orders in webapp (by long press on the order).
- Addition of the progress of the webapp update.
- Recovery in case of webapp update error.
- Elimination of &quot;simple&quot; scenarios (redundant with advanced configuration of commands).
- Add hatching on graphs to distinguish days.
- Redesign of the interactions page.
- Profile page redesign.
- Redesign of the administration page.
- Adding a &quot;health&quot; on objects.
- Bug fix on the battery level of the equipment.
- Addition of method in the core for the management of dead commands (must then be implemented in the plugin).
- Possibility to log text commands.
- On the history page you can now graph a calculation.
- Adding a calculation formula management for histories.
- Updating of all documentation :.
    - All the documents have been revised.
    - Deletion of images to facilitate updating and multilingual.
- More choices possible on setting the area sizes in the views.
- Possibility to choose the color of the text of the object summary.
- Addition of a remove \ _inat action in the scenarios allowing to cancel all the programming of the DANS / A blocks.
- Ability in designs for widgets on hover to choose the position of the widget.
- Adding a reply \ _cmd parameter on interactions to specify the id of the command to use to respond.
- Adding a timeline on the history page (attention must be activated on each command and / or scenario that you want to appear).
- Possibility to empty the timeline events.
- Possibility to empty the banned IPs.
- Correction / improvement of user account management.
    - Ability to delete basic admin account.
    - Preventing the last administrator from going back to normal.
    - Added security to prevent deletion of the account with which we are connected.
- Possibility in the advanced configuration of the equipment to put the orders in the widgets in table mode by choosing the box for each order or putting it.
- Possibility to rearrange equipment widgets from the Dashboard (in edit mode right click on the widget).
- Change the pitch of widgets (from 40 \ * 80 to 10 \ * 10). Please note this will impact the layout on your Dashboard / view / design.
- Possibility to give a size from 1 to 12 to objects on the Dashboard.
- Possibility to independently launch the actions of the scenarios (and plugin type mode / alarm if compatible) in parallel with the others.
- Possibility to add an access code to a design.
- Addition of a Jeedom independent watchdog to check the status of MySql and Apache.

3.0.11
======
- Fixed bugs on &quot;ask&quot; timeout requests.

3.0.10
======
- Bug correction on the interface for configuring interactions.

3.0
===
- Suppression of slave mode.
- Possibility of triggering a scenario on a change of a variable.
- Variable updates now trigger the update of virtual equipment commands (the latest version of the plugin is required).
- Possibility to have an icon on info type commands.
- Ability on commands to display the name and icon.
- Addition of an &quot;alert&quot; action on scenarios : message up in Jeedom.
- Addition of a &quot;popup&quot; action on scenarios : message to validate.
- Command widgets can now have an update method which avoids an ajax call to Jeedom.
- Scenario widgets are now updated without ajax calls to get the widget.
- The global summary and parts are now updated without ajax call.
- A click on an element of a home automation summary brings you to a detailed view of it.
- You can now put text type commands in the summaries.
- Change of bootstraps slider to slider (correction of the bug of the double sliders event).
- Automatic saving of views when clicking on the button "see the result".
- Possibility to have the docs locally.
- Third-party developers can add their own ticket management system.
- Redesign of user rights configuration (everything is on the user management page).
- Libs update : jquery (in 3.0), jquery mobile, hightstock and table sorter, font-awesome.
- Big improvement in designs:.
    - All actions are now accessible from a right click.
    - Possibility to add a single order.
    - Ability to add an image or video stream.
    - Ability to add zones (clickable location) :.
        - Macro type area : launch a series of actions when clicked.
        - Binary type area : launches a series of actions when clicked on depending on the status of an order.
        - Widget type area : displays a widget when clicking or hovering over the area.
    - General code optimization.
    - Possibility to display a grid and choose its size (10x10,15x15 or 30x30).
    - Possibility to activate a magnetization of the widgets on the grid.
    - Possibility to activate a magnetization of the widgets between them.
    - Certain types of widgets can now be duplicated.
    - Ability to lock an item.
- Plugins can now use their own API key.
- Adding automatic interactions, Jeedom will try to understand the sentence, execute the action and respond.
- Added management of demons in mobile version.
- Addition of cron management in mobile version.
- Addition of certain health information in mobile version.
- Adding modules on alert to the battery page.
- Objects without a widget are automatically hidden on the Dashboard.
- Addition of a button in the advanced configuration of a device / command to see its events.
- Scenario triggers can now be conditions.
- A double click on the command line (on the configuration page) now opens its advanced configuration.
- Possibility to prohibit certain values for an order (in its advanced configuration).
- Addition of configuration fields on automatic status feedback (eg return to 0 after 4 min) in the advanced configuration of a command.
- Adding a valueDate function in scenarios (see scenario documentation).
- Possibility in the scenarios to modify the value of an order with the action "event".
- Addition of a comment field on the advanced configuration of a device.
- Addition of an alert system on orders with 2 levels : alert and danger. The configuration is in the advanced configuration of commands (info type only of course). You .can see the modules in alert on the Analysis → Equipment page. You can configure the actions on alert on the general configuration page of Jeedom
- Addition of a &quot;table&quot; area on the views which allows one or more columns to be displayed per box. The boxes also support HTML code.
- Jeedom can now run without root rights (experimental). Be careful because without root rights you will have to manually run the scripts for plugin dependencies.
- Optimization of expression calculations (calculation of tags only if present in the expression).
- Addition in the function API to access the summary (global and object).
- Ability to restrict access to each API key based on IP.
- Possibility on the history to make groupings by hour or year.
- The timeout on the wait command can now be a calculation.
- Correction of a bug if there are &quot;in the parameters of an action.
- Switch to sha512 for password hash (sha1 being compromised).
- Fixed a bug in the cache management that made it grow indefinitely.
- Correction of the access to the doc of third-party plugins if they do not have a doc locally.
- The interactions can take into account the notion of context (depending on the previous request as well as that before).
- Possibility of weighting words according to their size for the analysis of comprehension.
- Plugins can now add interactions.
- Interactions can now return files in addition to the response.
- Possibility to see on the plugins configuration page the functionalities of these plugins (interact, cron…) and to deactivate them individually.
- Automatic interactions can return values from summaries.
- Ability to define synonyms for objects, equipment, commands and summaries that will be used in contextual responses and summaries.
- Jeedom knows how to manage several related interactions (contextually) in one. They must be separated by a keyword (by default and). Example : "How much is it in the bedroom and in the living room? "Or" Turn on the light in the kitchen and the bedroom."
- The status of the scenarios on the edit page is now dynamically updated.
- Possibility to export a view in PDF, PNG, SVG or JPEG with the &quot;report&quot; command in a scenario.
- Possibility to export a design in PDF, PNG, SVG or JPEG with the &quot;report&quot; command in a scenario.
- Possibility to export a panel of a plugin in PDF, PNG, SVG or JPEG with the &quot;report&quot; command in a scenario.
- Adding a report management page (to re-download or delete them).
- Correction of a bug on the date of the last reporting of an event for certain plugins (alarm).
- Fixed display bug with Chrome 55.
- Optimization of the backup (on a RPi2 the time is divided by 2).
- Optimization of catering.
- Optimization of the update process.
- Standardization of the tedom Jeedom, now everything is in / tmp / Jeedom.
- Possibility of having a graph of the different links of a scenario, equipment, object, command or variable.
- Possibility to adjust the depth of the link graphics according to the original object.
- Possibility of having real-time scenario logs (slows down scenario execution).
- Ability to pass tags when launching a scenario.
- Optimization of the loading of scenarios and pages using actions with option (type configuration of the alarm plugin or mode).

2.4.6
=====
- Improved handling of repeat order values.

2.4.5
=====
- Bugfix.
- Optimized update checking.

2.4
=====
- General optimization.
    - Grouping of SQL queries.
    - Delete unnecessary requests.
    - Pid caching, status and last launch of scenarios.
    - Pid caching, status and last launch of crons.
    - In 99% of the cases more write request on the base in nominal operation (therefore except configuration of Jeedom, modifications, installation, update…).
- Removal of fail2ban (because easily bypassed by sending a false ip address), this makes Jeedom faster.
- Addition in the interactions of an option without category so that one can generate interactions on equipment without category.
- Addition in the scenarios of a button of choice of equipment on the slider type commands.
- Bootstrap update in 2.3.7.
- Addition of the notion of home automation summary (allows you to know all at once the number of lights in ON, open doors, shutters, windows, power, motion detections…). .All this is configured on the object management page
- Adding pre and post orders to an order. Allows you to trigger an action before or after another action. Can also be used to synchronize equipment for, for example .example, that 2 lights always light up together with the same intensity.
- Listenner optimization.
- Addition of modal to display the raw information (attribute of the object in base) of an equipment or an order.
- Possibility to copy the history of an order to another order.
- Ability to replace an order with another in all Jeedom (even if the order to be replaced no longer exists).

2.3
=====
- Correction of filters on the market.
- Correction of checkboxes on the view editing page (on a graphic area).
- Correction of checkbox history, visible and reverse in the command table.
- Correction of a problem with the translation of javascripts.
- Adding a plugin category : communicating object.
- Adding GENERIC \ _TYPE.
- Removal of new and top filters on the plugins market path.
- Renaming the default category on the market plugins route to "Top and new".
- Correction of free and paid filters on the plugins market path.
- Correction of a bug which could lead to a duplication of the curves on the history page.
- Correction of a bug on the timeout value of scenarios.
- correction of a bug on the display of widgets in the views which took the Dashboard version.
- Correction of a bug on the designs which could use the configuration of the widgets of the Dashboard instead of the designs.
- Correction of backup / restore bugs if the Jeedom name contains special characters.
- Optimization of the organization of the generic type list.
- Improved display of advanced equipment configuration.
- Correction of the backup access interface from.
- Saving the configuration during the market test.
- Preparation for the removal of bootstrapswtich in plugins.
- Correction of a bug on the type of widget requested for designs (Dashboard instead of dplan).
- bug fix on the event handler.
- random switching of the backup at night (between 2h10 and 3h59) to avoid worries of market overload.
- Fix widget market.
- Correction of a bug on market access (timeout).
- Correction of a bug on the opening of tickets.
- Correction of a blank page bug during the update if the / tmp is too small (be careful the correction takes effect on update n + 1).
- Addition of a * Jeedom \ _name * tag in the scenarios (gives the name of the Jeedom).
- Bugfix.
- Move all temporary files to / tmp.
- Improved sending of plugins (automatic dos2unix on \ *. Sh files).
- Redesign of the log page.
- Addition of a darksobre theme for mobile.
- Ability for developers to add widget configuration options on specific widgets (sonos, koubachi and others).
- Optimization of logs (thanks @ kwizer15).
- Ability to choose log format.
- Various optimization of the code (thanks @ kwizer15).
- Passage in module of the connection with the market (will allow to have a Jeedom without any link to the market).
- Adding a &quot;repo&quot; (connection module such as connection with the market) file (allows sending a zip containing the plugin).
- Addition of a github &quot;repo&quot; (allows to use github as a plugin source, with update management system).
- Addition of a URL &quot;repo&quot; (allows to use URL as source of plugin).
- Addition of a Samba &quot;repo&quot; (usable to push backups on a samba server and recover plugins).
- Addition of an FTP &quot;repo&quot; (usable to push backups on an FTP server and recover plugins).
- Addition for certain &quot;repo&quot; of the possibility of recovering the core of Jeedom.
- Adding automatic code tests (thanks @ kwizer15).
- Possibility to show / hide plugin panels on mobile and or desktop (be careful now by default the panels are hidden).
- Ability to disable plugin updates (as well as verification).
- Ability to force versification of plugin updates.
- Slight redesign of the update center.
- Ability to disable automatic check for updates.
- Fixed a bug that reset all data to 0 after a reboot.
- Ability to configure the log level of a plugin directly on the configuration page of the plugin.
- Possibility to consult the logs of a plugin directly on the configuration page of this one.
- Suppression of the debug start of daemons, now the log level of the daemon is the same as that of the plugin.
- Lib third party cleaning.
- Suppression of responsive voice (function said in the scenarios which worked less and less well).
- Fixed several security vulnerabilities.
- Addition of a synchronous mode on the scenarios (formerly fast mode).
- Possibility to manually enter the position of the widgets in% on the designs.
- Redesign of the plugins configuration page.
- Ability to configure the transparency of widgets.
- Addition of Jeedom \ _poweroff action in scenarios to stop Jeedom.
- Return of the action scenario \ _return to return to an interaction (or other) from a scenario.
- Going through long polling for updating the interface in real time.
- Correction of a bug during multiple widget refresh.
- Optimization of the update of command and equipment widgets.
- Addition of a tag * begin \ _backup *, * end \ _backup *, * begin \ _update *, * end \ _update *, * begin \ _restore *, * end \ _restore * in the scenarios.

2.2
=====
- Bugfix.
- Simplification of access to plugin configurations from the health page.
- Addition of an icon indicating if the daemon is started in debug or not.
- Addition of a global history configuration page (accessible from the history page).
- Docker bug fix.
- Ability to authorize a user to connect only from a computer on the local network.
- Overhaul of the configuration of widgets (be careful, you will probably have to resume the configuration of some widgets).
- Reinforcement of error handling on widgets.
- Ability to reorder views.
- Theme management overhaul.

2.1
=====
- Jeedom cache system redesign (use of doctrine cache). This allows for example to connect Jeedom to a redis or memcached server. By default Jeedom uses a file system (and no longer the MySQL database which allows you to download it a little), it is in / tmp so it is recommended if you have more than 512 MB of RAM to mount the / tmp to tmpfs (in RAM for faster and less wear and tear on the SD card, I recommend a size of 64MB). Be careful when restarting Jeedom the cache is emptied so you have to wait for all the information to come back up.
- Redesign of the log system (use of monolog) which allows.
integration with logging systems (syslog (d) type)
- Dashboard loading optimization.
- Fixed many warning.
- Possibility during an API call to a scenario to pass tags in the url.
- Apache support.
- Docker optimization with official docker support.
- Optimization for synology.
- Support + optimization for php7.
- Jeedom menu redesign.
- Delete all network management part : wifi, fixed ip… (will surely come back as a plugin). PLEASE NOTE that Jeedom&#39;s master / slave mode is not deleted.
- Removed battery indication on widgets.
- Addition of a page which summarizes the status of all equipment on battery.
- Redesign of the Jeedom DNS, use of openvpn (and therefore of the openvpn plugin).
- Update all libs.
- Interaction : ajout d'un système d'analyse syntaxique (permet de supprimer les interactions avec de grosses erreurs de syntaxe type « le chambre »).
- Suppression of the interface update by nodejs (switch to pulling every second on the list of events).
- Possibility for third-party applications to request events via the API.
- Refonte du système « d'action sur valeur » avec possibilité de faire plusieurs actions et aussi l'ajout de toutes les actions possibles dans les scénarios (attention il faudra peut-être toutes les .reconfigure following the update)
- Ability to deactivate a block in a scenario.
- Addition for developers of a tooltips help system. Il faut sur un label mettre la classe « help » et mettre un attribut data-help avec le message d'aide souhaité. This allows Jeedom to automatically add an icon at the end of your label « ? » and on hover to display the help text.
- Change of the core update process, we no longer request the archive from the Market but directly from Github now.
- Addition of a centralized system for installing dependencies on plugins.
- Redesign of the plugins management page.
- Adding mac addresses of the different interfaces.
- Added double authentication connection.
- Hash connection removal (for security reasons).
- Adding an OS administration system.
- Addition of standard Jeedom widgets.
- Ajout d'un système en beta pour trouver l'IP de Jeedom sur le réseau (il faut connecter Jeedom sur le réseau, puis aller sur le market et cliquer sur « Mes Jeedoms » dans votre profil).
- Addition to the scenarios page of an expression tester.
- Review of the scenario sharing system.

2.0
=====
- Jeedom cache system redesign (use of doctrine cache). This allows for example to connect Jeedom to a redis or memcached server. By default Jeedom uses a file system (and no longer the MySQL database which allows you to download it a little), it is in / tmp so it is recommended if you have more than 512 MB of RAM to mount the / tmp to tmpfs (in RAM for faster and less wear and tear on the SD card, I recommend a size of 64MB). Be careful when restarting Jeedom the cache is emptied so you have to wait for all the information to come back up.
- Redesign of the log system (use of monolog) which allows integration into log systems (syslog type (d)).
- Dashboard loading optimization.
- Fixed many warning.
- Possibility during an API call to a scenario to pass tags in the url.
- Apache support.
- Docker optimization with official docker support.
- Optimization for synology.
- Support + optimization for php7.
- Jeedom menu redesign.
- Delete all network management part : wifi, fixed ip… (will surely come back as a plugin). PLEASE NOTE that Jeedom&#39;s master / slave mode is not deleted.
- Removed battery indication on widgets.
- Addition of a page which summarizes the status of all equipment on battery.
- Redesign of the Jeedom DNS, use of openvpn (and therefore of the openvpn plugin).
- Update all libs.
- Interaction : ajout d'un système d'analyse syntaxique (permet de supprimer les interactions avec de grosses erreurs de syntaxe type « le chambre »).
- Suppression of the interface update by nodejs (switch to pulling every second on the list of events).
- Possibility for third-party applications to request events via the API.
- Refonte du système « d'action sur valeur » avec possibilité de faire plusieurs actions et aussi l'ajout de toutes les actions possibles dans les scénarios (attention il faudra peut-être toutes les .reconfigure following the update)
- Ability to deactivate a block in a scenario.
- Addition for developers of a tooltips help system. Il faut sur un label mettre la classe « help » et mettre un attribut data-help avec le message d'aide souhaité. This allows Jeedom .automatically add an icon at the end of your label « ? » and on hover to display the help text
- Change of the core update process, we no longer request the archive from the Market but directly from Github now.
- Addition of a centralized system for installing dependencies on plugins.
- Redesign of the plugins management page.
- Adding mac addresses of the different interfaces.
- Added double authentication connection.
- Hash connection removal (for security reasons).
- Adding an OS administration system.
- Addition of standard Jeedom widgets.
- Ajout d'un système en beta pour trouver l'IP de Jeedom sur le réseau (il faut connecter Jeedom sur le réseau, puis aller sur le market et cliquer sur « Mes Jeedoms » dans votre profil).
- Addition to the scenarios page of an expression tester.
- Review of the scenario sharing system.
