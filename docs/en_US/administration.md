This is where most of the configuration parameters are.
Although many, they are pre-configured by default.

The page is accessible by **Administration → Configuration**.

General
=======

In this tab we find general information about Jeedom:

-   **Name of your Jeedom**: Identifies your Jeedom,
    especially in the market. It can be reused in scenarios
    or to identify a backup.

-   **System**: Type of hardware on which the system is installed where
    your Jeedom turns.

-   **Installation Key**: Material Key of your Jeedom on
    the market. If your Jeedom does not appear in the list of your
    Jeedom on the market, it is advisable to click on the button
    **Reset**.

-   **Language**: Language used in your Jeedom.

-   **Generate translations**: Generates translations,
    be careful, this can slow down your system. Most useful option
    for developers.

-   **Duration of sessions (hour)**: duration of sessions
    PHP, it is not recommended to touch this parameter.

-   **Date & Time**: Choose your time zone. You can
    click **Force time synchronization** to restore
    a bad time displayed at the top right.

-   **Optional Time Server**: Indicates which time server should
    to be used if you click ** Force synchronization of
    time**. (to be reserved for experts)

-   **Ignore time verification**: tells Jeedom not to
    check if the time is consistent between himself and the system on
    which he turns. May be useful, for example, if you do not connect
    not Jeedom to the Internet and that he does not have a PSTN stack on the
    equipment used.

API
===

Here you will find the list of different API keys available in
your Jeedom. Basic, the core has two API keys:

-   a general: as much as possible, avoid using it,

-   and another for professionals: used for management
    of park. It can be empty.

-   Then you will find an API key per plugin in need.

For each API plugin key, as well as for HTTP APIs, JsonRPC and
TTS, you can define their scope:

-   **Disabled**: API key can not be used,

-   **White IP**: Only a list of IPs is allowed (see
    Administration → Settings → Networks)

-   **Localhost**: only queries coming from the system on which is
    installed Jeedom are allowed,

-   **Enabled**: No restrictions, any system with access
    your Jeedom will be able to access this API.

&gt; \ _OS / DB
===========

Two parts reserved for the experts are present in this tab.

> **Important**
>
> WARNING: If you modify Jeedom with one of these two solutions,
> the support can refuse to help you.

-   **&gt; \ _System**: Provides access to an interface
    system administration. It's a kind of shell console in
    which you can launch the most useful orders, especially
    to get information about the system.

-   **Database**: Provides access to the database
    from Jeedom. You can then launch commands in the field
    from the top. Two parameters are displayed below for information:

    -   **User**: Name of the user used by Jeedom in
        the database,

    -   **Password**: password to access the database
        used by Jeedom.

security
========

LDAP
----

-   **Enable LDAP Authentication**: Enable Authentication to
    through an AD (LDAP)

-   **Host**: server hosting the AD

-   **Domain**: Domain of your AD

-   **Base DN**: base DN of your AD

-   **Username**: Username for Jeedom to
    connects to the AD

-   **Password**: Password for Jeedom to connect to AD

-   **User search fields**: search fields from
    user login. In general uid for LDAP, samaccountname for
    Windows AD

-   **Filter (optional)**: filter on the AD (for the management of
    groups for example)

-   **Allow REMOTE \ _USER**: Activates the REMOTE \ _USER (used in SSO
    for example)

Log in
---------

-   **Number of failures tolerated**: defines the number of attempts
    successive authorized before banning the IP

-   **Maximum time between failures (in seconds)**: maximum time
    for 2 attempts to be considered successive

-   **Duration of the ban (in seconds), -1 for infinity**: time of
    IP ban

-   **"white" IP**: list of IPs that can never be banned

-   **Remove banned IPs**: Used to clear IP list
    currently banned

The list of banned IPs is at the bottom of this page. You will find
the IP, the ban date, and the end date of the ban
programmed.

networks
=======

It is imperative to correctly configure this important part of
Jeedom, otherwise a lot of plugins might not work. he
is possible to access Jeedom in two different ways: ** Access
internal **(from the same local network as Jeedom) and** access
external ** (from another network especially from the Internet).

> **Important**
>
> This part is just there to explain to Jeedom his environment:
> a change to the port or IP in this tab will not change the
> port or the IP of Jeedom actually. For this you have to connect in
> SSH and edit the file / etc / network / interfaces for the IP and the
> files etc / apache2 / sites-available / default and
> etc / apache2 / sites-available / default \ _ssl (for HTTPS) .However, in
> case of mishandling your Jeedom, the Jeedom team does not
> may be held responsible and may refuse any request for
> support.

-   **Internal Access**: Information to join Jeedom from a
    equipment on the same network as Jeedom (LAN)

    -   **OK / NOK**: indicates if the internal network configuration is
        correct

    -   **Protocol**: the protocol to use, often HTTP

    -   **URL or IP**: Jeedom IP to fill in

    -   **Port**: The port of the Jeedom web interface, usually 80.
        Warning changing the port here does not change the actual port of
        Jeedom that will remain the same

    -   **Complement**: the complementary URL fragment (example
        : / jeedom) to access Jeedom.

-   **External Access**: Information to join Jeedom from the outside
    of the local network. Only fill in if you do not use DNS
    Jeedom

    -   **OK / NOK**: indicates if the external network configuration is
        correct

    -   **Protocol**: protocol used for external access

    -   **URL or IP address**: External IP, if it is fixed. If not,
        give the URL pointing to the external IP address of your network.

    -   **Complement**: the complementary URL fragment (example
        : / jeedom) to access Jeedom.

> **Tip**
>
> If you are in HTTPS the port is 443 (default) and in HTTP the
> port is 80 (default). To use HTTPS from the outside,
> a letsencrypt plugin is now available on the market.

> **Tip**
>
> To find out if you need to set a value in the field
> **add-on**, look, when you log in to Jeedom in
> your Internet browser, if you need to add / jeedom (or other
> thing) after the IP.

-   **Advanced Management**: This part may not appear, in
    function of compatibility with your material. You will find
    the list of your network interfaces. You can tell Jeedom
    not to monitor the network by clicking ** disable the
    network management by Jeedom ** (check if Jeedom is connected to
    no network)

-   **Proxy market**: allows remote access to your Jeedom without having
    need a DNS, a fixed IP or open the ports of your box
    Internet

    -   **Use DNS Jeedom**: activates DNS Jeedom (attention
        this requires at least one service pack)

    -   **DNS status**: HTTP DNS status

    -   **Management**: allows to stop and restart the DNS service

> **Important**
>
> If you can not get the DNS Jeedom working, look at the
> configure the firewall and the parental filter of your Internet box
> (on livebox it is necessary for example the average firewall).

Colors
========

The colorization of the widgets is done according to the category to
which equipment belongs. Among the categories we find the
Heating, Security, Energy, light, Automatism, Multimedia, Other ...

For each category, we can differentiate the colors of the version
desktop and mobile version. We can then change:

-   the background color of the widgets,

-   the color of the command when the widget is of gradual type (for
    example lights, shutters, temperatures).

By clicking on the color a window opens, allowing to choose its
color. The cross next to the color returns to the parameter
by default.

At the top of the page, you can also configure the transparency of
widgets globally (this will be the default value.
possible then to modify this widget value by widget). Not to
put no transparency, leave 1.0.

> **Tip**
>
> Do not forget to save after any modification.

Orders
=========

Many commands can be historized. So, in
Analysis → History, you get graphs representing their
use. This tab allows you to set global parameters to
the historization of orders.

Historical
----------

-   **View widget statistics**: View
    widget statistics. The widget must be
    compatible, which is the case for most. It is also necessary that the
    command is of numeric type.

-   **Calculation period for min, max, average (in hours)**: Period
    statistics calculation (24h by default). It is not possible
    to put less than an hour.

-   **Calculation period for the trend (in hours)**: Period of
    Trend calculation (2h by default). It is not possible to
    put less than an hour.

-   **Delay before archiving (in hours)**: Indicates the delay before
    Jeedom does not archive any data (24h by default). That is, the
    historical data must be more than 24 hours to be archived
    (as a reminder, archiving will either average or take the maximum
    or the minimum of the data over a period that corresponds to the
    packet size).

-   **Archive by package of (in hours)**: This setting gives
    precisely the size of the packets (1h by default). It means by
    example that Jeedom will take periods of 1h, average and
    store the new calculated value by deleting the
    averaged values.

-   **Low Trend Calculation Threshold**: This value indicates the
    value from which Jeedom indicates that the trend is to
    the decline. It must be negative (default -0.1).

-   **High Trend Calculation Threshold**: Same for upside.

-   **Default Graph Display Period**: Period that is
    used by default when you want to display the history
    of an order. The shorter the period, the faster Jeedom will be
    to display the requested graph.

> **Note**
>
> The first parameter **Show widget statistics** is
> possible but disabled by default because it significantly extends the
> dashboard display time. If you enable this option, by
> default, Jeedom relies on data from the last 24 hours for
> calculate these statistics. The trend calculation method is based
> on the least squares calculation (see
> [here] (https://fr.wikipedia.org/wiki/M%C3%A9thode_des_moindres_carr%C3%A9s)
> for the detail).

push
----

**Global push URL**: allows to add a URL to call in case of
update an order. You can use the following tags:
**\ # value \ #** for the value of the command, **\ # cmd \ _name \ #** for the
the name of the command, **\ # cmd \ _id \ #** for the unique identifier of the
command, **\ # humanname \ #** for the full name of the command (ex:
\ # \ [Bathroom \] \ [Hydrometry \] \ [Humidity \] \ #)

Hidden
=====

Monitor and act on the Jeedom cache:

-   **Statistics**: Number of objects currently cached

-   **Clean the cache**: Force the deletion of objects that are not
    more useful. Jeedom does it automatically every night.

-   **Empty all cached data**: Completely blanks the cache.
    Be careful, this can make you lose data!

-   **Break time for long polling**: Frequency at which
    Jeedom checks if there are pending events for customers
    (web interface, mobile application ...). The shorter this time, the more
    the interface will update quickly, in counter part this
    uses more resources and can slow down Jeedom.

interactions
============

This tab allows you to set global parameters for
interactions that you will find in Tools → Interactions.

> **Tip**
>
> To activate the interaction log, go to the tab
> Administration → Configuration → Logs, then check **Debug** in the list
> from the bottom. Warning: the logs will be very verbose!

General
-------

Here you have three parameters:

-   **Sensitivity**: There are 4 levels of matching (Sensitivity
    goes from 1 (corresponds exactly to 99)

    -   for 1 word: the level of correspondence for interactions at
        only one word

    -   2 words: the level of correspondence for the interactions to
        two words

    -   3 words: the level of correspondence for the interactions to
        three words

    -   + of 3 words: the level of correspondence for interactions
        more than three words

-   **Do not answer if the interaction is not understood**: default
    Jeedom answers "I did not understand" if no interaction
    does not match. It is possible to disable this operation for
    that Jeedom does not answer anything. Check the box to disable
    the answer.

-   **General exclusion regex for interactions**: allows to
    define a regexp which, if it corresponds to an interaction,
    will automatically delete this phrase from the generation (reserved
    to experts). For more information see explanations in the
    chapter **Exclusion Regexp** of the documentation on
    the interactions.

Automatic, contextual & warning interaction
-------------------------------------------------- ---

-   The **automatic interactions** allow Jeedom to try to
    understand an interaction request even if there is none
    defined. He will then look for a name of object and / or equipment
    and / or command to try to answer the best.

-   **contextual interactions** let you do it
    several requests without repeating everything, for example:

    -   * Jeedom keeping the context: *

        -   * You *: How much is it in the room?

        -   * Jeedom *: Temperature 25.2 ° C

        -   * You *: and in the living room?

        -   * Jeedom *: Temperature 27.2 ° C

    -   * Ask two questions in one: *

        -   * You *: How much is it in the bedroom and in the living room?

        -   * Jeedom *: Temperature 23.6 ° C, Temperature 27.2 ° C

-   **Please warn me** interactions allow you to ask
    Jeedom to warn you if an order goes up / down or is worth a
    certain value.

    -   * You *: Do you warn me if the living room temperature exceeds 25 ° C?

    -   * Jeedom *: OK (* As soon as the living room temperature exceeds 25 ° C,
        Jeedom will tell you, only once *)

> **Note**
>
> By default, Jeedom will respond to you by the same channel as you
> used to ask him to warn you. If he does not find
> no, it will then use the default command specified in this
> tab: **Default return command**.

Here are the different options available:

-   **Enable automatic interactions**: Check to enable
    automatic interactions.

-   **Enable Contextual Responses**: Check to enable
    contextual interactions.

-   **Priority contextual response if the sentence starts with**: If
    the sentence starts with the word you fill in here, Jeedom is going
    then prioritize a contextual response (you can put
    several words by separating them with **; **).

-   **Cut an interaction in 2 if it contains**: Same thing for
    the division of an interaction containing several questions. You
    give here the words that separate the different questions.

-   **Enable "Notify me" interactions**: Check to enable
    **Please warn me** type interactions.

-   **"Please warn me" answer if the sentence begins with**: If the
    sentence begins with this / these word (s) then Jeedom will seek to make a
    **Please let me know** interaction (you can put several
    words by separating them with **; **).

-   **Default Return Command**: Default Return Command
    for an **Please warn me** interaction (used, in particular,
    if you have programmed the alert via the mobile interface)

-   **Synonym for objects**: List of synonyms for objects
    (ex: ground floor | basement | downstairs | downstairs; bathroom | bathroom).

-   **Synonym for Equipment**: Synonyms List for
    the equipment.

-   **Synonym for commands**: List of synonyms for
    the orders.

-   **Synonym for summary**: Synonym list for abstracts.

-   **Synonym maximum slider command**: Synonym for putting a
    maximum slider command (ex opens to open the pane of
    the room ⇒ 100% room shutter).

-   **Synonym minimum slider command**: Synonym for putting a
    slider type control at least (ex closes to close the shutter of
    the room ⇒ room shutter at 0%).

Colors
--------

This part allows to define the colors that Jeedom will associate with
words red / blue / black ... To add a color:

-   Click on the **+** button, on the right,

-   Give a name to your color,

-   Choose the associated color by clicking on the box on the right.

Reports
========

Configure the generation and management of reports

-   **Timeout after page generation (in ms)**: Timeout
    wait after loading the report to make the "photo", to
    change if your report is incomplete for example.

-   **Clean up older reports by (days)**: Sets the
    number of days before deleting a report (the reports take
    a little space so be careful not to put too much
    conservation).

Connections
=====

Allows you to configure the link graphs. These links allow
see graphically the relationships between objects,
equipment, objects, etc.

-   **Depth for Scenarios**: Allows you to set, when
    the display of a link graph of a scenario, the number
    maximum elements to display (plus there are elements plus the
    graph will be slow to generate and more difficult to read).

-   **Depth for objects**: Same for objects.

-   **Depth for equipment**: Same for equipment.

-   **Depth for orders**: Same for orders.

-   **Depth for variables**: Same for variables.

-   **Parameter of prerender**: Allows to act on the layout
    of the graph.

-   **Render parameter**: Idem.

summaries
=======

Adds summaries of objects. This information is displayed
at the top, right, in the Jeedom menu bar, or next to
objects :

-   **Key**: Key summary, not to touch.

-   **Name**: Name of the summary.

-   **Calculation**: Method of calculation, can be of type:

    -   **Sum**: sum of the different values,

    -   **Average**: averages the values,

    -   **Text**: Text displays the value (especially for those
        type string).

-   **Icon**: Summary icon.

-   **Unit**: Summary Unit.

-   **Counting method**: If you count a binary data then
    you have to set this value to binary, example if you count the
    number of lamps lit but you just have the value of the
    inverter (0 to 100), then you have to put binary, like that Jeedom
    considered that if the value is greater than 1, then the lamp
    is on.

-   **Show if value equals 0**: Check this box to display the
    value, even when it is worth 0.

-   **Link to virtual**: Starts the creation of virtual commands
    having for value those of the summary.

-   **Delete summary**: The last button, on the far right, allows
    to delete the summary of the line.

logs
====

timeline
--------

-   **Maximum number of events**: Sets the maximum number to
    show in the timeline.

-   **Delete all events**: Lets you empty the timeline of
    all of his recorded events.

posts
--------

-   **Add a message to every error in the logs**: if a plugin
    or Jeedom writes an error message in a log, Jeedom adds
    automatically a message in the message center (at least
    you are sure not to miss it).

-   **User Information Command**: Allows you to select a
    or several commands (to be separated by **&&**) of type
    **message** that will be used when issuing
    new messages.

Notifications
-------

-   **Add a message to each Timeout**: Adds a message in the
    message center if a device falls **timeout**.

-   **Command on Timeout**: Command of type **message** to use
    if equipment is **timeout**.

-   **Add a message to each Battery in Warning**: Adds a
    message in the message center if a device has its level of
    battery **warning**.

-   **Command on Battery in Warning**: Command of type **message**
    to use if a device at its battery level **warning**.

-   **Add a message to each Battery in Danger**: Adds a
    message in the message center if a device at its level of
    battery in **danger**.

-   **Dangerous Battery Control**: **message** command at
    use if equipment at its battery level in **danger**.

-   **Add a message to each Warning**: Adds a message in the
    message center if a command goes into alert **warning**.

-   **Command on Warning**: Command of type **message** to use
    if a command goes into alert **warning**.

-   **Add a message to each Danger**: Adds a message in the
    message center if a command goes into alert **danger**.

-   **Command on Danger**: Command of type **message** to be used if
    a command goes on alert **danger**.

log
---

-   **Log Engine**: Allows you to change the log engine for, by
    example, send them to a syslog daemon (d).

-   **Log format**: Log format to use (Attention: it
    does not affect the logs of the daemons).

-   **Maximum number of lines in a log file**: Sets the
    maximum number of lines in a log file. It is recommended
    not to touch this value because too much value could be
    fill the file system and / or make Jeedom unable
    to display the log.

-   **Default log level**: When you select "Default",
    for the level of a log in Jeedom, this is the one that will be
    then used.

Below you will find a table allowing to manage finely the
log level of the essential elements of Jeedom as well as that of
plugins.

Facilities
===========

-   **Number of failures before disabling the equipment**: Number
    communication failures with equipment before deactivating
    this one (a message will warn you if this happens).

-   **Stack thresholds**: Manage global alert thresholds
    on the batteries.

Update and files
=======================

Update of Jeedom
---------------------

-   **Source of Update**: Choose the update source from
    Jeedom's core.

-   **Core version**: Core version to recover.

-   **Automatically check for updates**: Indicates if
    you have to search automatically if there are new updates
    (be careful to avoid overloading the market, the time of
    verification may change).

Deposits
----------

Deposits are storage (and service) spaces for
move backups, recover plugins, recover the core of
Jeedom, etc.

### File

Deposit used to activate the sending of plugins by files.

### Github

Deposit used to link Jeedom to Github.

-   **Token**: Token for private depot access.

-   **User or repository organization for Jeedom Core**: Name
    the user or organization on github for the core.

-   **Name of the deposit for the core Jeedom**: Name of the deposit for the core.

-   **Branch for Jeedom core**: Branch of the deposit for the core.

### Market

Deposit used to connect Jeedom to the market, it is strongly recommended
to use this deposit. Attention: any support request can be
refused if you use another deposit than this one.

-   **Address**: Market Address.

-   **Username**: Your username on the Market.

-   **Password**: Your password for the Market.

### Samba

Deposit to automatically send a Jeedom backup on
a Samba share (ex: NAS Synology).

-   **\ [Backup \] IP**: IP of the Samba server.

-   **\ [Backup \] User**: Username for login
    (anonymous connections are not possible). It must be necessary
    that the user has the rights to read AND write to the
    destination directory.

-   **\ [Backup \] Password**: Password of the user.

-   **\ [Backup \] Sharing**: Share path (be careful
    stop at the sharing level).

-   **\ [Backup \] Path**: Path in the share (to put in
    relative), this one must exist.

> **Note**
>
> If the path to your samba backup folder is:
> \\\\192.168.0.1\\Backup\\Domotic\\Jeedom Then IP = 192.168.0.1
>, Share = //192.168.0.1/Sauvegardes, Path = Domotics/Jeedom

> **Note**
>
> When validating the Samba share, as described previously,
> a new backup form appears in the screen
> Administration → Jeedom backups. By activating it, Jeedom will proceed
> to its automatic upload during the next backup. A test is
> possible by performing a manual backup.

> **Important**
>
> You may need to install the smbclient package to make it
> works.

> **Important**
>
> The Samba protocol has several versions, the v1 is compromised from a security point of view
> and on some NAS you can force the customer to use the v2
> or the v3 to connect. So if you have a protocol negotiation error
> failed: NT_STATUS_INVAID_NETWORK_RESPONSE it is most probably because restriction are enforced on the NAS
> You must then edit on the OS of your Jeedom
> the /etc/samba/smb.conf file and add these two lines:
> max protocol client = SMB3
> client min protocol = SMB2
> The smbclient used by Jeedom will then use v2 or v3 and by putting SMB3 on the 2 line then only
> SMB3 will be used. It's up to you to adapt according to the restrictions on NAS side or other Samba server

> **Important**
>
> Jeedom must be the only one to write in this folder and it must be empty
> by default (that is, before configuring and sending the
> first backup, the folder must not contain any files or
> folder).

### URLs

-   **Jeedom Core URL**

-   **Jeedom core version URL**


