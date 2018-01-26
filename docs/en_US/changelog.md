Changelog
=========

3.2.0
=====

-   Ability to prohibit an order in interactions
    automatic

-   Improved automatic interactions

-   Fixed bug on synonymous management of interactions

-   Adding a user search field for LDAP / AD connections
    (allows to make Jeedom compatible AD)

-   Spelling corrections (thanks to dab0u for his huge work)

-   JEED-290: You can not connect with IDs anymore
    Default (admin / admin) remote, only LAN is allowed

-   JEED-186: We can now choose the background color in the
    designs

-   For block A, possibility to set an hour between 00:01 and 00:59
    by simply putting the minutes (ex 30 for 00:30)

-   Adding active sessions and devices registered on the
    user's profile page and the management page of the
    users

-   JEED-284: the permanent connection now depends on a key
    unique user and peripheral (and not only user)

-   JEED-283: adding a rescue mode to jeedom by adding & rescue = 1
    in the url

-   JEED-8: adding the name of the scenario to the page title when
    edition

-   Optimizing organizational changes (widget size,
    position of the equipment, position of the controls) on the dashboard
    and the views. Attention now the changes are only
    saved only when leaving the edit mode.

-   JEED-18: Adding logs when opening a ticket to support

-   JEED-181: adding a name command in scenarios to have
    the name of the order or equipment or object

-   JEED-15: Add battery and alert on the webapp

-   Fix bugs for moving design objects in Firefox

-   JEED-19: During an update it is now possible to
    update the update script before updating

-   JEED-125: Adding a link to the reset documentation
    password

-   JEED-2: Improved time management during a reboot

-   JEED-77: Adding variable management in the http API

-   JEED-78: Added the tag function for scenarios. Attention he
    must in scenarios using tags pass of * montag *
    to tag (montag)

-   JEED-124: Correcting the management of timeouts of scenarios

-   Bugfix

-   Ability to disable an interaction

-   Adding a file editor (reserved for
    experienced users)

-   Added Generic Types "Light State" (Binary), "Light
    Color Temperature "(Info)," Color Temperature Light "(Action)

-   Ability to make mandatory words in an interaction

3.1.7
=====

-   Bug fixes (especially on the logs and
    statistical functions)

-   Improvement of the system of updates with a page of notes of
    version (which you must check yourself before each update).
    day !!!!

-   Fixed a bug that recovered logs during restores

3.1
===

-   Bugfix

-   Global optimization of Jeedom (on the loading of classes of
    plugins, time almost divided by 3)

-   Debian Support 9

-   Onepage mode (page change without reloading the entire page, just
    the part that changes)

-   Added an option to hide objects on the dashboard but which
    always have them in the list

-   Double-click on a node on the link graph (except for
    variables) brings to its configuration page

-   Possibility to put the text on the left / right / center on the
    designs for text / view / design elements

-   Adding object summaries to the dashboard (list of objects
    to the left)

-   Added "prevites-me-si" interactions

-   Review of the homepage of the scenarios

-   Adding a command history for SQL or system commands
    in the Jeedom interface

-   Possibility to have the order history graphs in
    webapp (by long press on the command)

-   Adding the progress of the update of the webapp

-   Resume in case of error updating the webapp

-   Deleting "simple" scenarios (redundant with the configuration
    advanced orders)

-   Added hatching on graphs to distinguish days

-   Recasting of the interactions page

-   Redesign of the profiles page

-   Recast of the administration page

-   Adding a "health" on objects

-   Fixed bug on the battery level of equipment

-   Added method in the core for dead order management
    (must then be implemented in the plugin)

-   Possibility to save text commands

-   On the history page you can now make the graph
    a calculation

-   Add Calculation Formula Management for History

-   Update of all documentation:

    -   All docs have been reviewed

    -   Deleting images to make it easier to update and
        multilingual

-   More choices on setting zone sizes in
    views

-   Ability to choose the text color of the object summary

-   Added a remove \ _inat action in scenarios that allow
    to cancel all the programming of the IN / A blocks

-   Possibility in the designs for widgets to flyover to choose
    the position of the widget

-   Added reply \ _cmd parameter on interactions to specify
    the id of the command to use to answer

-   Add a timeline on the history page (attention must be
    activated on every command and / or scenario you want
    see appear)

-   Ability to clear timeline events

-   Ability to empty banned IPs

-   Correction / improvement of user accounts management

    -   Ability to delete basic admin account

    -   Prevention of the transition to normal of the last administrator

    -   Added security to avoid deleting the account with
        which one is connected

-   Possibility in advanced configuration of equipment to put
    the layout of the commands in the widgets in table mode in
    choosing for each order the box or put it

-   Possibility of reorganizing the equipment widgets since the
    dashboard (in edit mode right click on the widget)

-   Changing the step of the widgets (from 40 \ * 80 to 10 \ * 10). Attention this
    will impact the layout on your dashboard / view / design

-   Ability to give a size from 1 to 12 to objects on the
    dashboard

-   Possibility of independently launching the actions of the scenarios (and
    plugin type mode / alarm if compatible) in parallel with others

-   Ability to add an access code to a design

-   Addition of a watchdog independent of Jeedom to check the status of
    MySql and Apache

3.0.11
======

-   Bug fixes on "ask" requests in timeout

3.0.10
======

-   Bug fixes on the interaction configuration interface

3.0
===

-   Deleting the slave mode

-   Ability to trigger a scenario on a change of one
    variable

-   Variable updates now trigger the update
    commands from a virtual device (you need the latest version
    of the plugin)

-   Ability to have an icon on the info type commands

-   Possibility on the commands to display the name and the icon

-   Added an "alert" action on the scenarios: message at the top in
    jeedom

-   Adding a "popup" action on the scenarios: message to validate

-   The widgets of the commands can now have a method
    update which avoids an ajax call to Jeedom

-   Scenario widgets are now updated without ajax call
    to have the widget

-   The global summary and parts are now updated without appeal
    ajax

-   A click on an element of a home automation summary will take you to a view
    detailed of it

-   You can now put in the summaries type orders
    text

-   Slider bootstrap change in slider (bug fix
    double slider event)

-   Automatic saving of the views when clicking on the "see the
    result"

-   Possibility to have local docs

-   Third-party developers can add their own system of
    ticket management

-   Redesign of user rights configuration (everything is on the
    user management page)

-   Updated libs: jquery (in 3.0), jquery mobile, hightstock
    and sorter table, make-awesome

-   Big improvement of the designs:

    -   All actions are now accessible from a
        right click

    -   Ability to add a single command

    -   Ability to add an image or a video stream

    -   Ability to add zones (clickable location):

        -   Macro-type zone: launches a series of actions during a
            click on it

        -   Binary type zone: launches a series of actions during a
            click on it depending on the status of an order

        -   Widget type zone: displays a widget by clicking or by hovering
            of the area

    -   General optimization of the code

    -   Ability to display a grid and choose its
        size (10x10,15x15 or 30x30)

    -   Ability to activate a magnetization of widgets on the grid

    -   Ability to activate a magnetization of widgets between them

    -   Some types of widgets can now be duplicated

    -   Ability to lock an item

-   Plugins can now use an API key that is
    clean

-   Adding automatic interactions, Jeedom will try to understand
    the sentence, execute the action and answer

-   Adding mobile version daemon management

-   Adding mobile cron management

-   Addition of some health information in mobile version

-   Add on the battery page modules on alert

-   Objects without widget are automatically hidden on the dashboard

-   Adding a button in the advanced configuration of a
    equipment / order to see the events of
    thereof / the latter

-   The triggers of a scenario can now be
    terms

-   A double click on the line of an order (on the page
    configuration) now opens the advanced configuration of
    this one

-   Possibility to prohibit certain values ​​for an order (in the
    advanced configuration of it)

-   Adding configuration fields on automatic status feedback
    (eg return to 0 after 4min) in the advanced configuration of a
    order

-   Added a valueDate function in the scenarios (see
    documentation of scenarios)

-   Possibility in scenarios to change the value of an order
    with the action "event"

-   Added a comment field on the advanced configuration of a
    equipment

-   Addition of an alert system on orders with 2 levels:
    alert and danger. The configuration is in the configuration
    advanced commands (info-only, of course). You can
    see the modules on alert on the Analysis → Equipment page. You
    can configure actions on alert on the page of
    general configuration of Jeedom

-   Added a "table" area on the views that can display one or
    several columns per box. The boxes also support the HTML code

-   Jeedom can now run without root rights (experimental).
    Attention because without the root rights you will have to manually launch
    scripts for plugin dependencies

-   Optimization of calculation of expressions (calculation of tags only
    if present in the expression)

-   Add in the function API to access the summary (global
    and object)

-   Ability to restrict access of each API key according to
    IP

-   Possibility on the history to group by hour or
    year

-   The timeout on the wait command can now be a calculation

-   Fixed a bug if there are "in the parameters of an action

-   Switch to sha512 for hash passwords (sha1
    being compromised)

-   Fixed a bug in cache management that made him fatter
    indefinitely

-   Fixed access to 3rd party plugins doc if they did not
    no doc locally

-   Interactions can take into account the notion of context (in
    according to the previous request as well as the previous one)

-   Ability to weight words by size for
    understanding analysis

-   Plugins can now add interactions

-   Interactions can now return files in addition to
    the answer

-   Ability to see on the plugins configuration page the
    features of these (interact, cron ...) and disable them
    unitary

-   Automatic interactions can return the values ​​of
    summaries

-   Ability to define synonymous for objects, equipment,
    orders and summaries that will be used in the answers
    Contextual and summary

-   Jeedom knows how to manage several related interactions (contextually)
    in one. They must be separated by a keyword (default and).
    Example: "How much is in the bedroom and living room?" or
    "Turn on the light of the kitchen and the bedroom."

-   The status of the scenarios on the edit page is now set to
    dynamically

-   Ability to export a view in PDF, PNG, SVG or JPEG with the
    report command in a scenario

-   Ability to export a design in PDF, PNG, SVG or JPEG with the
    report command in a scenario

-   Ability to export a panel of a plugin in PDF, PNG, SVG or JPEG
    with the "report" command in a scenario

-   Adding a report management page (to re-download or
    delete them)

-   Fixed a bug on the last date of an event
    for some plugins (alarm)

-   Fixed a display bug with Chrome 55

-   Backup optimization (on a RPi2 the time is divided by 2)

-   Optimization of the restoration

-   Optimization of the updating process

-   Uniformization of the jeedom tmp, now everything is in / tmp / jeedom

-   Possibility to have a graph of the different links of a scenario,
    equipment, object, order or variable

-   Ability to adjust the depth of the link graphics in
    function of the original object

-   Possibility to have logs of scenarios in real time (slows down
    the execution of the scenarios)

-   Ability to pass tags when launching a scenario

-   Optimizing the loading of scenarios and pages using
    actions with option (type configuration of the alarm plugin or mode)

2.4.6
=====

-   Improvement of the management of the repetition of the values ​​of
    orders

2.4.5
=====

-   Bugfix

-   Optimizing update checking

2.4
---

-   General optimization

    -   Grouping of SQL queries

    -   Deleting unnecessary queries

    -   Pid caching, state and last scenario launch

    -   Pid caching, state and last launch of crons

    -   In 99% of cases no more write requests on the base in
        nominal operation (thus excluding Jeedom configuration,
        changes, installation, update ...)

-   Deleting the fail2ban (because easily bypassing by sending a
    false IP address), this speeds up Jeedom

-   Add in the interactions of an option without category for that
    we can generate interactions on equipment without
    category

-   Addition in the scenarios of a button of choice of equipment on the
    slider commands

-   Update of bootstrap in 2.3.7

-   Addition of the concept of home automation summary (allows to know of a
    only once the number of lights ON, the doors open, the
    shutters, windows, power, motion detections ...).
    All of this is configured on the object management page

-   Added pre and post command on a command. Trigger
    all the time an action before or after another action. Can also
    to synchronize equipment for, for example, 2
    lights always light up together with the same intensity.

-   Optimizing listenner

-   Added modal to display raw information (attribute of
    the object in base) of an equipment or an order

-   Ability to copy the history of one order to another
    order

-   Ability to replace one order with another in any Jeedom
    (even if the order to be replaced no longer exists)

2.3
---

-   Correction of filters on the market

-   Fixed checkboxes on the edit page of the views (on a
    graphic area)

-   Fixed checkboxes to be historized, visible and reversed in the
    command board

-   Fixed a problem with the translation of javascripts

-   Adding a plugin category: object communicating

-   Adding GENERIC \ _TYPE

-   Remove new and top filters on the plugins path
    of the market

-   Renaming the default category on the course of the plugins of the
    market in "Top and new"

-   Fixed free and paid filters on the plugins path
    of the market

-   Fixed a bug that could lead to duplicate curves
    on the history page

-   Fixed a bug on the timeout value of scenarios

-   fixed a bug on the display of widgets in the views that
    was taking the dashboard version

-   Fixed a bug on designs that could use the
    set up dashboard widgets instead of designs

-   Fixed backup / restore bugs if the name of the jeedom
    contains special characters

-   Optimization of the organization of the generic type list

-   Improved display of advanced configuration of
    amenities

-   Correction of the access interface to the backup since

-   Backup of the configuration during the market test

-   Preparing to remove bootstrapswtich in plugins

-   Fixed a bug on the type of widget requested for designs
    (dashboard instead of dplan)

-   bug fix on the event handler

-   random backup of the backup at night (between 2:10 and 3:59) for
    avoid the worries of overloading the market

-   Correction of the widget market

-   Fixed a bug on market access (timeout)

-   Fixed a bug on opening tickets

-   Fixed a blank page bug when updating if the
    / tmp is too small (attention correction takes effect at
    the update n + 1)

-   Added a * jeedom \ _name * tag in the scenarios (gives the name
    of the jeedom)

-   Bugfix

-   Moving all temporary files in / tmp

-   Improved sending plugins (automatic dos2unix on the
    \ *. sh files)

-   Recast of the log page

-   Adding a mobile darksobre theme

-   Possibility for developers to add options for
    widget configuration on specific widgets (type sonos,
    koubachi and other)

-   Log optimization (thanks @ kwizer15)

-   Possibility of choosing the format of the logs

-   Various optimization of the code (thanks @ kwizer15)

-   Passage in module of the connection with the market (will allow to have
    a jeedom without any link to the market)

-   Addition of a "repo" (connection module type the connection with
    the market) file (allows to send a zip containing the plugin)

-   Added a "repo" github (allows to use github as source of
    plugin, with update management system)

-   Added a "repo" URL (allows to use URL as a plugin source)

-   Added a "repo" Samba (usable to push backups on a
    samba server and get some plugins)

-   Added FTP "repo" (used to push backups on a
    FTP server and recover plugins)

-   Added for some "repo" the possibility of recovering the core of
    jeedom

-   Added automatic code tests (thanks @ kwizer15)

-   Ability to show / hide plugins panels on mobile and
    or desktop (beware now by default the panels are hidden)

-   Ability to disable updates to a plugin (as well as
    the cheking process)

-   Ability to force check for updates to a plugin

-   Slightly redesigned update center

-   Ability to disable automatic check of updates
    day

-   Fixed a bug that reset all data to 0 after a
    restart

-   Ability to configure the log level of a plugin directly
    on the configuration page of it

-   Ability to view the logs of a plugin directly on the
    configuration page of it

-   Removed Debug debugging daemons, now level
    log of the daemon is the same as the plugin

-   Third Party Cleanup

-   Deleting responsive voice (function says in scenarios that
    walked less and less well)

-   Fixed several security vulnerabilities

-   Addition of a synchronous mode on the scenarios (formerly
    fast mode)

-   Ability to manually enter the position of widgets in% on
    design

-   Redesign of the plugins configuration page

-   Ability to configure widget transparency

-   Adding the jeedom \ _poweroff action in the scenarios to stop
    jeedom

-   Back from the action scenario \ _return to make a return to a
    interaction (or other) from a scenario

-   Pass in long polling for updating the interface in time
    real

-   Fixed a bug during multiple widget refresh

-   Optimizing the update of orders and equipment widgets

-   Added tag * begin \ _backup *, * end \ _backup *, * begin \ _update *,
    * end \ _update *, * begin \ _restore *, * end \ _restore * in scenarios

2.2
---

-   Bugfix

-   Simplified access to plug-in configurations from
    the health page

-   Added an icon indicating whether the daemon is debugged or not

-   Adding an overall configuration history page
    (accessible from the history page)

-   Bug fixes for docker

-   Ability to allow a user to sign in only to
    from a station on the local network

-   Redesigned configuration of widgets (be careful it will take
    surely resume the configuration of some widgets)

-   Enhanced error handling on widgets

-   Ability to reorder views

-   Overhaul of theme management

2.1
---

-   Overhaul of the Jeedom cache system (use of
    doctrine cache). This allows for example to connect Jeedom to a
    server redis or memcached. By default Jeedom uses a system of
    files (and no longer MySQL database which allows to unload it
    little), this one is in / tmp so it is advisable if you
    have more than 512 MB of RAM to mount the / tmp in tmpfs (in RAM for
    more speed and less wear and tear on the SD card, I
    recommend a size of 64mo). Be careful when restarting
    Jeedom the cache is emptied so you have to wait to get the
    feedback of all the infos

-   Redesign of the log system (use of monolog) which allows a
    integration with log systems (syslog type (d))

-   Optimizing dashboard loading

-   Fixed many warnings

-   Possibility during a call api to a scenario to pass tags
    in the url

-   Apache support

-   Docker optimization with official docker support

-   Optimization for synology

-   Support + optimization for php7

-   Redesign of Jeedom menus

-   Deleting all the network management part: wifi, fixed ip ...
    (will surely come back as a plugin). ATTENTION this is not the
    jeedom master / slave mode that is deleted

-   Deleting the battery indication on widgets

-   Added a page that summarizes the status of all equipment on
    drums

-   Redesign of the DNS Jeedom, use of openvpn (and therefore the
    openvpn plugin)

-   Update all the libs

-   Interaction: adding a parsing system (allows to
    remove interactions with large syntax errors like "
    the bedroom ")

-   Removal of the update of the interface by nodejs (change to
    pulling every second on the list of events)

-   Possibility for third-party applications to request by API
    events

-   Overhaul of the "action on value" system with the possibility of making
    several actions and also adding all possible actions
    in the scenarios (be careful it may take all
    reconfigure following the update)

-   Ability to disable a block in a scenario

-   Adding for developers a tooltips help system. It is necessary
    on a label put the class "help" and put an attribute
    data-help with the desired help message. This allows Jeedom
    to add automatically at the end of your label an icon "? And
    on flyover to display help text

-   Change the process of updating the core, we do not ask anymore
    the archive at the Market but directly to Github now

-   Addition of a centralized system for installing dependencies on
    plugins

-   Redesign of the plugins management page

-   Adding mac addresses of different interfaces

-   Adding the dual authentication connection

-   Deleting the hash connection (for security reasons)

-   Adding an OS administration system

-   Adding standard Jeedom widgets

-   Adding a beta system to find the Jeedom IP on the network
    (You have to connect Jeedom on the network, then go to the market and
    click on "My Jeedoms" in your profile)

-   Add on the page of the scenarios of an expression tester

-   Review of the scenario sharing system

2.0
---

-   Overhaul of the Jeedom cache system (use of
    doctrine cache). This allows for example to connect Jeedom to a
    server redis or memcached. By default Jeedom uses a system of
    files (and no longer MySQL database which allows to unload it
    little), this one is in / tmp so it is advisable if you
    have more than 512 MB of RAM to mount the / tmp in tmpfs (in RAM for
    more speed and less wear and tear on the SD card, I
    recommend a size of 64mo). Be careful when restarting
    Jeedom the cache is emptied so you have to wait to get the
    feedback of all the infos

-   Redesign of the log system (use of monolog) which allows a
    integration with log systems (syslog type (d))

-   Optimizing dashboard loading

-   Fixed many warnings

-   Possibility during a call api to a scenario to pass tags
    in the url

-   Apache support

-   Docker optimization with official docker support

-   Optimization for synology

-   Support + optimization for php7

-   Redesign of Jeedom menus

-   Deleting all the network management part: wifi, fixed ip ...
    (will surely come back as a plugin). ATTENTION this is not the
    jeedom master / slave mode that is deleted

-   Deleting the battery indication on widgets

-   Added a page that summarizes the status of all equipment on
    drums

-   Redesign of the DNS Jeedom, use of openvpn (and therefore the
    openvpn plugin)

-   Update all the libs

-   Interaction: adding a parsing system (allows to
    remove interactions with large syntax errors like "
    the bedroom ")

-   Removal of the update of the interface by nodejs (change to
    pulling every second on the list of events)

-   Possibility for third-party applications to request by API
    events

-   Overhaul of the "action on value" system with the possibility of making
    several actions and also adding all possible actions
    in the scenarios (be careful it may take all
    reconfigure following the update)

-   Ability to disable a block in a scenario

-   Adding for developers a tooltips help system. It is necessary
    on a label put the class "help" and put an attribute
    data-help with the desired help message. This allows Jeedom
    to add automatically at the end of your label an icon "? And
    on flyover to display help text

-   Change the process of updating the core, we do not ask anymore
    the archive at the Market but directly to Github now

-   Addition of a centralized system for installing dependencies on
    plugins

-   Redesign of the plugins management page

-   Adding mac addresses of different interfaces

-   Adding the dual authentication connection

-   Deleting the hash connection (for security reasons)

-   Adding an OS administration system

-   Adding standard Jeedom widgets

-   Adding a beta system to find the Jeedom IP on the network
    (You have to connect Jeedom on the network, then go to the market and
    click on "My Jeedoms" in your profile)

-   Add on the page of the scenarios of an expression tester

-   Review of the scenario sharing system


