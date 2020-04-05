
Changelog
=========


=====

- 


=====

- Automatic rotation of the API key of admin users every 3 months. I can deactivate it (but it is not recommended) in the user management. Please note this update launches a rotation of API keys for admins users.
- Ability to enter global information for your home in the Jeedom administration (geographic position, altitude ...) to avoid having to re-enter it in plugins when creating equipment.
- Updating the repository on smart
- 
- 

3.3.39
=====

- Changed Variable name $ key to $ key2 in class event
- Cleaning the plugin / widgand / scenario sending code to the markand (saves several seconds on displaying plugins)
- Correction of a warning on the lastBandween function
- Bandter consideration of plugin widgands
- Optimization of the health calculation on the swap

>**Important**
>
>This update fixes a concern that may prevent any history recording as of January 1, 2020, it is more than highly recommended

3.3.38
=====

- Addition of global compatibility of Jeedom DNS with a 4G internand connection. (Important if you use Jeedom DNS is that you have a 4g connection, you must check in the configure Jeedom dns the corresponding box).
- Spelling corrections.
- Security fix

3.3.37
=====

- Bugfix

3.3.36
=====

- Addition of rounding on the number of days since the last battery change
- Bugfix

3.3.35
=====

- Bugfix
- Possibility to install plugins directly from the markand

3.3.34
=====

- Fixed a bug that could prevent the battery status from going back up
- Correction of a bug on tags in interactions
- The "timeout" status (non communication) of the equipment now has priority over the "warning" or "danger" status"
- Bug fix on cloud backups

3.3.33
=====

- Bugfix

3.3.32
=====

- Bugfix
- Mobile support for sliders on designs
- SMART : optimization of swap management

3.3.31
=====

- Bugfix

3.3.30
=====

- Correction of a bug on the display of user sessions
- Documentation update
- Removal of updating graphics in real time, following numerous bugs reported
- Correction of a bug which could prevent the display of certain logs
- Correction of a bug on the monitoring service
- Correction of a bug on the &quot;Equipment analysis&quot; page, the battery update date is now correct 
- Improvement of the remove_inat action in scenarios

3.3.29
=====

- Correction of the disappearance of the date of the last update check
- Fixed a bug that could block cloud backups
- Correction of a bug on the calculation of the use of the Variables if they are in the form : Variable (foo, myvalue)


3.3.28
=====

- Fixed an infinite wheel bug on the updates page
- Various corrections and optimizations

3.3.27
=====

- Correction of a bug on the translation of the days in French
- Improved stability (auto restart of the MySql service and watchdog to check the time on startup)
- Bugfix
- Disabling actions on orders when editing designs, views or Dashboards

3.3.26
=====

- Bugfix
- Correction of a bug on the multi-launch of scenario
- Correction of a bug on the alerts on the value of the orders

3.3.25
=====

- Bugfix
- Switching the timeline to table mode (due to errors in the independent Jeedom lib)
- Addition of classes for color supports in the mode plugin


3.3.24
=====

-   Correction of a bug on the display of the number of updates
-	Removed editing of HTML code from the advanced configuration of commands due to too many bugs
-	Bug fixes
-	Improvement of the icon selection window
-	Automatic update of the battery change date if the battery is more than 90% and 10% higher than the previous value
-	Addition of button on the administration to resand the rights and launch a Jeedom verification (right, cron, database ...)
-	Removal of advanced visibility choices for equipment on Dashboard / view / design / mobile. Now if you want to see or not the equipment on Dashboard / mobile just check or not the general visibility box. For views and design just put or not the equipment on it

3.3.22
=====

- Bug fixes
- Improved order replacement (in views, plan and plan3d)
- Fixed a bug that could prevent opening certain plugin equipment (alarm or virtual type)

3.3.21
=====

- Fixed a bug where the time display could exceed 24h
- Correction of a bug on the update of design summaries
- Correction of a bug on the management of the levels of alerts on certain widgands during the update of the value
- Fixed display of disabled equipment on some plugins
- Correction of a bug when indicating battery change at Jeedom
- Improved display of logs when updating Jeedom
- Bug fix during the Variable update (which did not always launch the scenarios or did not trigger an update of the commands in all cases)
- Fixed a bug on Cloud backups, or duplicity was not installing correctly
- Improvement of internal TTS in Jeedom
- Improvement of the cron syntax checking system


3.3.20
=====

- Correction of a bug on the scenarios or they could remain blocked at &quot;in progress&quot; while they are deactivated
- Fixed an issue with launching an unplanned scenario
- Time zone bug fix

3.3.19
=====
- Bug fixes (especially during the update)


3.3.18
=====
- Bugfix

3.3.17
=====

- Correction of an error on samba backups

3.3.16
=====

-   Ability to delande a Variable.
-   Addition of a 3D display (banda)
-   Redesign of the cloud backup system (incremental and encrypted backup).
-   Adding an integrated note taking system (in Analysis -&gt; Note).
-   Addition of the notion of tag on equipment (can be found in the advanced configuration of equipment).
-   Addition of a history system on the delandion of orders, equipment, objects, view, design, 3d design, scenario and user.
-   Addition of the Jeedom_reboot action to launch a restart of Jeedom.
-   Add option in the cron generation window.
-   A message is now added if an invalid expression is found when executing a scenario.
-   Adding a command in the scenarios : value (order) allows to have the value of an order if it is not automatically given by Jeedom (case when storing the name of the order in a Variable).
-   Addition of a button to refresh the messages of the message center.
-   Add in the configuration of action on value of a command a button to search for an internal action (scenario, pause ...).
-   Addition of an action “Resand to zero of the IS” on the scenarios
-   Ability to add images in the background on the views
-   Ability to add background images on objects
-   Update information available is now hidden from non-admin users
-   Improved support for () in calculating expressions
-   Possibility to edit the scenarios in text / json mode
-   Addition on the health page of a free space check for the Jeedom tmp
-   Ability to add options in reports
-   Addition of a heartbeat by plugin and automatic restart of daemon in case of problems
-   Addition of listeners on the task engine page
-   Optimizations
-   Possibility to consult the logs in mobile version (wepapp)
-   Addition of an action tag in the scenarios (see documentation)
-   Possibility to have a full screen view by adding &quot;&amp; fullscreen = 1&quot; in the url
-   Addition of lastCommunication in the scenarios (to have the last communication date of an equipment)
-   Real-time update of graphs (simple, not calculated or timelines)
-   Ability to delande an element from the design configuration
-   Possibility of having a report on the battery level (equipment report)
-   Scenario widgands are now displayed by default on the Dashboard
-   Change the pitch of the widgands by horizontal 25 to 40, vertical 5 to 20 and margin 1 to 4 (you can resand the old values in the configuration of Jeedom, widgand tab)
-   Possibility to put an icon on the scenarios
-   Addition of daemon management on the task engine
-   Addition of the color_gradient function in the scenarios

3.2.16
=====

- Correction of a bug during the dependency installation of certain plugins on smart

3.2.15
=====

- Correction of a bug when saving equipment

3.2.14
=====

- Preparation to avoid an error when switching to 3.3.X
- Correction of a problem when requesting support for third-party plugins

3.2.12
=====

- Bugfix
- Optimizations

3.2.11
=====

- Bugfix.

3.2.10
=====

- Bugfix.
- Improved synchronization with the markand.
- Improvement of the update process in particular in the copying of files which now checks the size of the copied file.
- Bug fixes on the stateDuration, lastStateDuration and lastChangeStateDuration functions (thanks @kiboost).
- Optimization of the link graph calculation and the use of Variables.
- Improvement of the cron task dandails window which now displays the scenario as well as the action to be taken for doIn tasks (thanks @kiboost).

3.2.9
=====

- Bugfix
- Correction of a bug on the icons of the file editor and on the expression tester
- Bug fixes on listenners
- Addition of an alert if a plugin blocks crons
- Correction of a bug in the cloud monitoring system if the agent version is less than 3.X.X

3.2.8
=====

- Bugfix
- Addition of an option in the Jeedom administration to specify the local ip range (useful in docker type installations)
- Correction of a bug on the calculation of the use of Variables
- Addition of an indicator on the health page giving the number of processes killed by lack of memory (overall indicates that the Jeedom is too loaded)
- Improved file editor

3.2.7
=====

- Bugfix
- Docs update
- Ability to use tags in the terms of the "A" and "IN blocks"
- Bug correction of markand categories for widgands / scripts / scenarios...

3.2.6
=====

- Bugfix
- Docs update
- Standardization of the names of certain orders in the scenarios
- Performance optimization

3.2.5
=====

- Bugfix
- Reactivation of interactions (inactive because of the update)

3.2.4
=====

- Bugfix
- Correction of a bug on certain modal in Spanish
- Correction of a calculation error on time_diff
- Preparation for the future alerting system

3.2.3
=====

-   Bugfix on min / max functions....
-   Improved export of graphics and display in table mode

3.2.2
=====

-   Removal of the old widgand update system (deprecated since version 3.0). Attention if your widgand does not use the new system there is a risk of malfunction (duplication of it in this case). Example widgand [here] (https://github.com/Jeedom/core/tree/banda/core/template/Dashboard)
-   Possibility to display the graphs in table form or to export these in csv or xls

-   Users can now add their own php function for scenarios. See documentation of scenarios for implementation

-   JEED-417 : addition of a time_diff function in the scenarios

-   Addition of a configurable delay before response on interactions (allows to wait for the status feedback to take place for example)

-   JEED-365 : Removal of &quot;User information command&quot; to be replaced by actions on message. Allows you to launch several different commands, to launch a scenario ... Attention if you had a &quot;User information command&quot; it must be reconfigured.

-   Add an option to easily open an access for the support (on the user page and when opening a tickand)

-   Correction of a rights bug following a restore of a backup

-   Updating translations

-   Library update (jquery and highcharts)

-   Possibility to prohibit an order in interactions
    automatic

-   Improved automatic interactions

-   Bug correction on the synonym management of interactions

-   Addition of a user search field for LDAP / AD connections
    (makes Jeedom AD compatible)

-   Spelling corrections (thanks to dab0u for his enormous work)

-   JEED-290 : We can no longer connect with identifiers by
    default (admin / admin) remotely, only the local nandwork is authorized

-   JEED-186 : We can now choose the background color in the
    designs

-   For block A, possibility of sandting an hour bandween 12:01 a.m. and 12:59 a.m.
    by simply putting the minutes (ex 30 for 00:30)

-   Adding active sessions and devices registered on the
    user profile page and management page
    users

-   JEED-284 : permanent connection now depends on a key
    single user and device (rather than user)

-   JEED-283 : adding a * rescue * mode to Jeedom by adding &amp; rescue = 1
    in the url

-   JEED-8 : addition of the name of the scenario on the title of the page during
    edition

-   Optimization of organizational changes (size of widgands,
    position of equipment, position of controls) on the Dashboard
    and the views. Attention now the modifications are not
    saved only when exiting edit mode.

-   JEED-18 : Adding logs when opening a tickand to support

-   JEED-181 : addition of a name command in the scenarios to have
    the name of the order or equipment or object

-   JEED-15 : Add battery and alert on webapp

-   Correction of bugs for moving design objects in Firefox

-   JEED-19 : During an update it is now possible to
    update the update script before updating

-   JEED-125 : added link to resand documentation
    password

-   JEED-2 : Improved time management during a restart

-   JEED-77 : Addition of Variable management in the http API

-   JEED-78 : addition of the tag function for scenarios. Be careful there
    must in the scenarios using the tags pass from \ #montag \#
    to tag (montag)

-   JEED-124 : Correct the management of scenario timeouts

-   Bugfix

-   Ability to deactivate an interaction

-   Adding a file editor (reserved for
    experienced users)

-   Addition of generics Types &quot;State Light&quot; (Binary), &quot;Light
    Color Temperature &quot;(Info),&quot; Light Color Temperature &quot;(Action)

-   Ability to make words mandatory in an interaction

3.1.7
=====

-   Bug fixes (especially on logs and
    statistical functions)

-   Improvement of the update system with a page of notes
    version (which you must check yourself before each update
    day !!!!)

-   Correction of a bug which recovered the logs during restores

3.1
===

-   Bugfix

-   Global optimization of Jeedom (on loading classes of
    plugins, time almost divided by 3)

-   Debian 9 support

-   Onepage mode (page change without reloading the whole page, just
    the part that changes)

-   Add an option to hide objects on the Dashboard but which
    lands always have them in the list

-   Double-click on a node on the link graph (except for
    Variables) brings on its configuration page

-   Ability to put the text left / right / center on the
    designs for text / view / design elements

-   Adding object summaries on the Dashboard (list of objects
    to the left)

-   Add interactions of type "notify me-if"

-   Scenario home page review

-   Add command history for SQL or system commands
    in the Jeedom interface

-   Possibility to have graphs of order histories in
    webapp (by long press on the command)

-   Addition of the progress of the webapp update

-   Recovery in case of webapp update error

-   Elimination of &quot;simple&quot; scenarios (redundant with the configuration
    advanced orders)

-   Add hatching on graphs to distinguish days

-   Redesign of the interactions page

-   Profile page redesign

-   Redesign of the administration page

-   Adding a &quot;health&quot; on objects

-   Bug fix on the battery level of the equipment

-   Addition of mandhod in the core for the management of dead commands
    (must then be implemented in the plugin)

-   Possibility to log text commands

-   On the history page you can now make the graph
    of a calculation

-   Adding a calculation formula management for histories

-   Updating of all documentation :

    -   All the documents have been revised

    -   Delandion of images to facilitate updating and
        multilingual

-   More choices possible on the zone size sandtings in the
    views

-   Possibility to choose the color of the text of the object summary

-   Addition of a remove \ _inat action in the scenarios allowing
    cancel all programming of the DANS / A blocks

-   Ability in designs for widgands on hover to choose
    widgand position

-   Adding a reply \ _cmd paramander on interactions to specify
    the id of the command to use to respond

-   Adding a timeline on the history page (attention must be
    activated on each command and / or scenario you want
    see appear)

-   Possibility to empty the timeline events

-   Possibility to empty the banned IPs

-   Correction / improvement of user account management

    -   Ability to delande basic admin account

    -   Preventing the last administrator from going back to normal

    -   Added security to prevent account delandion with
        which one is connected

-   Possibility in the advanced configuration of equipment to put
    the layout of the commands in the widgands in table mode in
    choosing for each order the box or put it

-   Ability to rearrange equipment widgands from
    Dashboard (in edit mode right click on the widgand)

-   Change the pitch of widgands (from 40 \ * 80 to 10 \ * 10). Be careful
    will impact the layout on your Dashboard / view / design

-   Possibility to give a size from 1 to 12 to objects on the
    Dashboard

-   Ability to independently launch scenario actions (and
    plugin type mode / alarm if compatible) in parallel with the others

-   Possibility to add an access code to a design

-   Addition of a Jeedom independent watchdog to check the status of
    MySql and Apache

3.0.11
======

-   Fixed bugs on &quot;ask&quot; timeout requests

3.0.10
======

-   Bug correction on the interface for configuring interactions

3.0
===

-   Suppression of slave mode

-   Ability to trigger a scenario on a change of
    Variable

-   Variable updates now trigger the update
    virtual equipment orders (you need the latest version
    plugin)

-   Possibility to have an icon on info type commands

-   Ability on commands to display the name and icon

-   Addition of an &quot;alert&quot; action on scenarios : message up in
    Jeedom

-   Addition of a &quot;popup&quot; action on scenarios : message to validate

-   Command widgands can now have a mandhod
    update which avoids an ajax call to Jeedom

-   Scenario widgands are now updated without ajax calls
    to gand the widgand

-   The global summary and parts are now updated without appeal
    ajax

-   A click on an element of a home automation summary brings you to a view
    dandailed of it

-   You can now put in type summaries
    text

-   Change of bootstraps slider to slider (bug fix
    double slider event)

-   Automatic saving of views when clicking on the button &quot;see the
    result"

-   Possibility to have the docs locally

-   Third-party developers can add their own system of
    tickand management

-   Redesign of user rights configuration (everything is on the
    user management page)

-   Libs update : jquery (in 3.0), jquery mobile, hightstock
    and table sorter, font-awesome

-   Big improvement in designs:

    -   All actions are now accessible from a
        right click

    -   Possibility to add a single order

    -   Ability to add an image or video stream

    -   Ability to add zones (clickable location) :

        -   Macro type area : launches a series of actions during a
            click on it

        -   Binary type area : launches a series of actions during a
            click on it depending on the status of an order

        -   Widgand type area : displays a widgand on click or hover
            of the area

    -   General code optimization

    -   Possibility to display a grid and choose its
        size (10x10,15x15 or 30x30)

    -   Possibility to activate a magnandization of the widgands on the grid

    -   Possibility to activate a magnandization of the widgands bandween them

    -   Certain types of widgands can now be duplicated

    -   Ability to lock an item

-   Plugins can now use their API key
    clean

-   Adding automatic interactions, Jeedom will try to understand
    the sentence, execute the action and respond

-   Added management of demons in mobile version

-   Addition of cron management in mobile version

-   Addition of certain health information in mobile version

-   Adding modules on alert to the battery page

-   Objects without a widgand are automatically hidden on the Dashboard

-   Addition of a button in the advanced configuration of a
    equipment / of a command to see the events of
    thereof / the latter

-   The triggers for a scenario can now be
    terms

-   Double click on the command line (on the page
    configuration) now opens the advanced configuration of
    this one

-   Possibility to prohibit certain values for an order (in the
    advanced configuration)

-   Addition of configuration fields on automatic status feedback
    (eg randurn to 0 after 4 min) in the advanced configuration of a
    ordered

-   Adding a valueDate function in the scenarios (see
    scenario documentation)

-   Possibility in scenarios to modify the value of an order
    with the action "event"

-   Addition of a comment field on the advanced configuration of a
    equipment

-   Addition of an alert system on orders with 2 levels :
    alert and danger. The configuration is in the configuration
    advanced commands (info type only of course). You can
    see the modules in alert on the Analysis → Equipment page. You
    can configure the actions on alert on the page of
    general configuration of Jeedom

-   Addition of a &quot;table&quot; area on the views which allows to display one or
    multiple columns per box. The boxes also support HTML code

-   Jeedom can now run without root rights (experimental).
    Be careful because without root rights you will have to manually launch
    scripts for plugin dependencies

-   Optimization of expression calculations (calculation of tags only
    if present in expression)

-   Addition in the function API to access the summary (global
    and object)

-   Ability to restrict access to each API key based on
    IP

-   Possibility on the history to make groupings by hour or
    year

-   The timeout on the wait command can now be a calculation

-   Correction of a bug if there are &quot;in the paramanders of an action

-   Switch to sha512 for password hash (sha1
    being compromised)

-   Fixed a bug in the cache management that made it grow
    indefinitely

-   Correction of access to the doc of third-party plugins if they have not
    no local doc

-   Interactions can take into account the notion of context (in
    depending on the previous request as well as that before)

-   Possibility to weight words according to their size for
    understanding analysis

-   Plugins can now add interactions

-   Interactions can now randurn files in addition to
    the answer

-   Possibility to see on the plugins configuration page the
    functionality of these (interact, cron…) and deactivate them
    unitary

-   Automatic interactions can randurn values from
    summaries

-   Ability to define synonyms for objects, equipment,
    commands and summaries that will be used in responses
    contextual and summaries

-   Jeedom knows how to manage several related interactions (contextually)
    in one. They must be separated by a keyword (by default and).
    Example : "How much is it in the bedroom and in the living room? "Or
    "Turn on the kitchen and bedroom light."

-   The status of the scenarios on the edit page is now sand to
    dynamically day

-   Possibility to export a view in PDF, PNG, SVG or JPEG with the
    &quot;report&quot; command in a scenario

-   Possibility to export a design in PDF, PNG, SVG or JPEG with the
    &quot;report&quot; command in a scenario

-   Possibility to export a panel of a plugin in PDF, PNG, SVG or JPEG
    with the command &quot;report&quot; in a scenario

-   Adding a report management page (to re-download or
    delande them)

-   Correction of a bug on the date of the last escalation of an event
    for some plugins (alarm)

-   Fixed display bug with Chrome 55

-   Optimization of the backup (on a RPi2 the time is divided by 2)

-   Optimization of catering

-   Optimization of the update process

-   Standardization of the tmp Jeedom, now everything is in / tmp / Jeedom

-   Possibility of having a graph of the different links of a scenario,
    equipment, object, command or Variable

-   Ability to adjust the depth of link graphics by
    function of the original object

-   Possibility of having real-time scenario logs (slows down
    execution of scenarios)

-   Ability to pass tags when launching a scenario

-   Optimization of the loading of scenarios and pages using
    actions with option (configuration type of alarm plugin or mode)

2.4.6
=====

-   Improvement of the management of the repandition of the values of
    orders

2.4.5
=====

-   Bugfix

-   Optimized update checking

2.4
---

-   General optimization

    -   Grouping of SQL queries

    -   Delande unnecessary requests

    -   Pid caching, status and last launch of scenarios

    -   Pid caching, status and last launch of crons

    -   In 99% of the cases more request for writing on the base in
        nominal operation (therefore except Jeedom configuration,
        modifications, installation, update…)

-   Suppression of fail2ban (because easily bypassed by sending a
    false ip address), this speeds up Jeedom

-   Addition in the interactions of an option without category so that
    we can generate interactions on equipment without
    category

-   Addition in the scenarios of a button of choice of equipment on the
    slider commands

-   Bootstrap update in 2.3.7

-   Addition of the notion of home automation summary (allows to know of a
    single shot the number of lights in ON, the doors open, the
    shutters, windows, power, motion dandections…).
    All this is configured on the object management page

-   Adding pre and post orders to an order. Allows to trigger
    all the time an action before or after another action. Can also
    allow synchronization of equipment for, for example, that 2
    lights always come on togandher with the same intensity.

-   Listenner optimization

-   Add modal to display raw information (attribute of
    the object in base) of an equipment or an order

-   Ability to copy the history of one order to another
    ordered

-   Ability to replace an order with another in all Jeedom
    (even if the order to be replaced no longer exists)

2.3
---

-   Correction of filters on the markand

-   Correction of checkboxes on the page for editing views (on a
    graphics area)

-   Correction of checkbox history, visible and reverse in the
    control panel

-   Correction of a problem with the translation of javascripts

-   Adding a plugin category : communicating object

-   Adding GENERIC \ _TYPE

-   Removal of new and top filters on the course of plugins
    from the markand

-   Renaming the default category on the course of the plugins of the
    markand in "Top and new"

-   Correction of free and paid filters on the course of plugins
    from the markand

-   Correction of a bug which could lead to a duplication of the curves
    on the history page

-   Correction of a bug on the timeout value of scenarios

-   fixed a bug on the display of widgands in views which
    took the Dashboard version

-   Correction of a bug on the designs which could use the
    configuration of Dashboard widgands instead of designs

-   Correction of backup / restore bugs if the name of the Jeedom
    contains special characters

-   Optimization of the organization of the generic type list

-   Improved display of advanced configuration of
    amenities

-   Correction of the backup access interface from

-   Saving the configuration during the markand test

-   Preparation for the removal of bootstrapswtich in plugins

-   Correction of a bug on the type of widgand requested for designs
    (Dashboard instead of dplan)

-   bug fix on the event handler

-   random switching of the backup at night (bandween 2h10 and 3h59) for
    avoid markand overload concerns

-   Fix widgand markand

-   Correction of a bug on markand access (timeout)

-   Correction of a bug on the opening of tickands

-   Fixed a blank page bug during the update if the
    / tmp is too small (be careful the correction takes effect at
    update n + 1)

-   Addition of a * Jeedom \ _name * tag in the scenarios (gives the name
    Jeedom)

-   Bugfix

-   Move all temporary files to / tmp

-   Improved sending of plugins (automatic dos2unix on
    files \ *. sh)

-   Redesign of the log page

-   Addition of a darksobre theme for mobile

-   Ability for developers to add options
    widgand configuration on specific widgands (sonos type,
    koubachi and other)

-   Optimization of logs (thanks @ kwizer15)

-   Ability to choose log format

-   Various optimization of the code (thanks @ kwizer15)

-   Passage in module of the connection with the markand (will allow to have
    a Jeedom without any link to the markand)

-   Addition of a &quot;repo&quot; (connection module type connection with
    the markand) file (allows sending a zip containing the plugin)

-   Addition of a github &quot;repo&quot; (allows to use github as source of
    plugin, with update management system)

-   Addition of a URL &quot;repo&quot; (allows to use URL as source of plugin)

-   Addition of a Samba &quot;repo&quot; (usable to push backups on a
    samba server and recover plugins)

-   Addition of an FTP &quot;repo&quot; (usable to push backups on a
    FTP server and recover plugins)

-   Addition for certain &quot;repo&quot; of the possibility of recovering the core of
    Jeedom

-   Adding automatic code tests (thanks @ kwizer15)

-   Ability to show / hide plugin panels on mobile and
    or desktop (beware now by default the panels are hidden)

-   Ability to disable plugin updates (as well as
    the cheking process)

-   Ability to force versification of plugin updates

-   Slight redesign of the update center

-   Possibility to deactivate the automatic update check
    day

-   Fixed a bug that resand all data to 0 following a
    restart

-   Possibility to configure the log level of a plugin directly
    on the configuration page of it

-   Possibility to consult the logs of a plugin directly on the
    configuration page of it

-   Suppression of the debug start of demons, maintaining the level
    of daemon logs is the same as that of the plugin

-   Lib third party cleaning

-   Suppression of responsive voice (function said in the scenarios which
    worked less and less well)

-   Fixed several security vulnerabilities

-   Addition of a synchronous mode on the scenarios (formerly
    fast mode)

-   Possibility to manually enter the position of the widgands in% on
    the designs

-   Redesign of the plugins configuration page

-   Ability to configure the transparency of widgands

-   Added Jeedom \ _poweroff action in scenarios to stop
    Jeedom

-   Randurn of the action scenario \ _randurn to randurn to a
    interaction (or other) from a scenario

-   Going through long polling for updating the interface in time
    real

-   Correction of a bug during multiple widgand refresh

-   Optimization of the update of command and equipment widgands

-   Addition of a tag * begin \ _backup *, * end \ _backup *, * begin \ _update*,
    *end \ _update *, * begin \ _restore *, * end \ _restore * in scenarios

2.2
---

-   Bugfix

-   Simplification of access to plugin configurations from
    the health page

-   Addition of an icon indicating if the daemon is started in debug or not

-   Addition of a global history configuration page
    (accessible from the history page)

-   Docker bug fix

-   Ability to allow a user to connect only to
    from a station on the local nandwork

-   Redesign of the widgands configuration (be careful
    surely resume the configuration of some widgands)

-   Reinforcement of error handling on widgands

-   Ability to reorder views

-   Theme management overhaul

2.1
---

-   Jeedom cache system redesign (use of
    hidden doctrine). This allows for example to connect Jeedom to a
    redis or memcached server. By default Jeedom uses a system of
    files (and no longer the MySQL DB which allows you to download a
    bit), it is in / tmp so it is recommended if you
    have more than 512 MB of RAM to mount the / tmp in tmpfs (in RAM for
    faster and less wear and tear on the SD card, I
    recommend a size of 64MB). Be careful when restarting
    Jeedom the cache is emptied so you have to wait for the
    reporting of all information

-   Redesign of the log system (use of monolog) which allows
    integration with logging systems (syslog (d) type)

-   Optimization of Dashboard loading

-   Fixed many warning

-   Possibility during an API call to a scenario to pass tags
    in the url

-   Apache support

-   Docker optimization with official docker support

-   Optimization for synology

-   Support + optimization for php7

-   Jeedom menu redesign

-   Delande all nandwork management part : wifi, fixed ip…
    (will surely come back as a plugin). ATTENTION this is not the
    Jeedom master / slave mode which is delanded

-   Removed battery indication on widgands

-   Addition of a page which summarizes the status of all equipment on
    drums

-   Redesign of Jeedom DNS, use of openvpn (and therefore of
    openvpn plugin)

-   Update all libs

-   Interaction : addition of a parsing system (allows
    remove interactions with large type syntax errors «
    le chambre »)

-   Suppression of the interface update by nodejs (change to
    pulling every second on the event list)

-   Possibility for third-party applications to request via the API
    events

-   Refonte du système « d'action sur valeur » avec possibilité de faire
    several actions and also the addition of all possible actions
    in the scenarios (be careful it may take all
    reconfigure following the update)

-   Ability to deactivate a block in a scenario

-   Addition for developers of a tooltips help system. It is necessary
    sur un label mandtre la classe « help » and mandtre un attribut
    data-help with the desired help message. This allows Jeedom
    automatically add an icon at the end of your label « ? » and
    on hover to display the help text

-   Change in the core update process, we no longer ask
    the archive at the Markand but now at Github now

-   Addition of a centralized system for installing dependencies on
    plugins

-   Redesign of the plugins management page

-   Adding mac addresses of the different interfaces

-   Added double authentication connection

-   Hash connection removal (for security reasons)

-   Adding an OS administration system

-   Addition of standard Jeedom widgands

-   Adding a banda system to find Jeedom&#39;s IP on the nandwork
    (you have to connect Jeedom on the nandwork, then go to the markand and
    cliquer sur « Mes Jeedoms » dans votre profil)

-   Addition to the scenarios page of an expression tester

-   Review of the scenario sharing system

2.0
---

-   Jeedom cache system redesign (use of
    hidden doctrine). This allows for example to connect Jeedom to a
    redis or memcached server. By default Jeedom uses a system of
    files (and no longer the MySQL DB which allows you to download a
    bit), it is in / tmp so it is recommended if you
    have more than 512 MB of RAM to mount the / tmp in tmpfs (in RAM for
    faster and less wear and tear on the SD card, I
    recommend a size of 64MB). Be careful when restarting
    Jeedom the cache is emptied so you have to wait for the
    reporting of all information

-   Redesign of the log system (use of monolog) which allows
    integration with logging systems (syslog (d) type)

-   Optimization of Dashboard loading

-   Fixed many warning

-   Possibility during an API call to a scenario to pass tags
    in the url

-   Apache support

-   Docker optimization with official docker support

-   Optimization for synology

-   Support + optimization for php7

-   Jeedom menu redesign

-   Delande all nandwork management part : wifi, fixed ip…
    (will surely come back as a plugin). ATTENTION this is not the
    Jeedom master / slave mode which is delanded

-   Removed battery indication on widgands

-   Addition of a page which summarizes the status of all equipment on
    drums

-   Redesign of Jeedom DNS, use of openvpn (and therefore of
    openvpn plugin)

-   Update all libs

-   Interaction : addition of a parsing system (allows
    remove interactions with large type syntax errors «
    le chambre »)

-   Suppression of the interface update by nodejs (change to
    pulling every second on the event list)

-   Possibility for third-party applications to request via the API
    events

-   Refonte du système « d'action sur valeur » avec possibilité de faire
    several actions and also the addition of all possible actions
    in the scenarios (be careful it may take all
    reconfigure following the update)

-   Ability to deactivate a block in a scenario

-   Addition for developers of a tooltips help system. It is necessary
    sur un label mandtre la classe « help » and mandtre un attribut
    data-help with the desired help message. This allows Jeedom
    automatically add an icon at the end of your label « ? » and
    on hover to display the help text

-   Change in the core update process, we no longer ask
    the archive at the Markand but now at Github now

-   Addition of a centralized system for installing dependencies on
    plugins

-   Redesign of the plugins management page

-   Adding mac addresses of the different interfaces

-   Added double authentication connection

-   Hash connection removal (for security reasons)

-   Adding an OS administration system

-   Addition of standard Jeedom widgands

-   Adding a banda system to find Jeedom&#39;s IP on the nandwork
    (you have to connect Jeedom on the nandwork, then go to the markand and
    cliquer sur « Mes Jeedoms » dans votre profil)

-   Addition to the scenarios page of an expression tester

-   Review of the scenario sharing system
