This is where most of the configuration parameters are located.
Although many, they are pre-configured by default.

The page is accessible by **Administration → Configuration**.

General 
=======

In this tab we find general information about Jeedom :

-   **Name of your Jeedom** : Identify your Jeedom,
    especially in the market. It can be reused in scenarios
    or identify a backup.

-   **System** : Type of hardware on which the system is installed where
    your Jeedom is spinning.

-   **Installation key** : Hardware key of your Jeedom on
    the market. If your Jeedom does not appear in the list of your
    Jeedom on the market, it is advisable to click on the button
    **Reset**.

-   **Language** : Language used in your Jeedom.

-   **Generate translations** : Generate translations,
    be careful, this can slow down your system. Most useful option
    for developers.

-   **Lifetime of sessions (hour)** : lifetime of sessions
    PHP, it is not recommended to touch this parameter.

-   **Date and hour** : Choose your time zone. You can
    click on **Force time synchronization** to restore
    a bad time displayed at the top right.

-   **Optional time server** : Indicates which time server should
    be used if you click **Force synchronization of
    time**. (to be reserved for experts)

-   **Skip time check** : tells Jeedom not to
    check if the time is consistent between itself and the system on
    which it turns. May be useful, for example, if you are not connecting
    no Jeedom to the Internet and that it has no PSTN battery on the
    equipment used.

API 
===

Here is the list of the different API keys available in
your Jeedom. Core has two API keys :

-   a general : as much as possible, avoid using it,

-   and another for professionals : used for management
    of park. It can be empty.

-   Then you will find one API key per plugin that needs it.

For each plugin API key, as well as for the HTTP, JsonRPC and APIs
TTS, you can define their scope :

-   **Disabled** : API key cannot be used,

-   **White IP** : only a list of IPs is authorized (see
    Administration → Configuration → Networks),

-   **Localhost** : only requests from the system on which is
    installed Jeedom are allowed,

-   **Activated** : no restrictions, any system with access
    your Jeedom will be able to access this API.

&gt;\_OS / DB 
===========

Two parts reserved for experts are present in this tab.

> **IMPORTANT**
>
> WARNING : If you modify Jeedom with one of these two solutions,
> support may refuse to help you.

-   **&gt;\_System** : Allows access to an interface
    system administration. It&#39;s a kind of shell console in
    which you can run the most useful commands, including
    to get information about the system.

-   **Database** : Allows access to the database
    from Jeedom. You can then launch commands in the field
    from the top. Two parameters are displayed below for information :

    -   **User** : Username used by Jeedom in
        the database,

    -   **Password** : database access password
        used by Jeedom.

Security 
========

LDAP 
----

-   **Enable LDAP authentication** : enable authentication to
    through an AD (LDAP)

-   **Host** : server hosting the AD

-   **Field** : domain of your AD

-   **DN base** : DN base of your AD

-   **Username** : username for Jeedom to
    connect to AD

-   **Password** : password for Jeedom to connect to AD

-   **User search fields** : search fields of
    user login. Usually uid for LDAP, samaccountname for
    Windows AD

-   **Filter (optional)** : filter on the AD (for managing
    groups for example)

-   **Allow REMOTE\_USER** : Activate REMOTE\_USER (used in SSO
    For example)

Log in 
---------

-   **Number of failures tolerated** : sets the number of attempts
    allowed before banning the IP

-   **Maximum time between failures (in seconds)** : maximum time
    so that 2 attempts are considered successive

-   **Duration of banishment (in seconds), -1 for infinity** : time to
    IP ban

-   **IP "white"** : list of IPs that can never be banned

-   **Remove banned IPs** : Clear the list of IPs
    currently banned

The list of banned IPs is at the bottom of this page. You will find
IP, ban date and ban end date
scheduled.

Networks 
=======

It is absolutely necessary to correctly configure this important part of
Jeedom, otherwise many plugins may not work. he
is possible to access Jeedom in two different ways : L'**access
interne** (from the same local network as Jeedom) and l'**access
externe** (from another network, especially from the Internet).

> **IMPORTANT**
>
> This part is just there to explain to Jeedom its environment :
> changing the port or IP in this tab will not change the
> Jeedom port or IP actually. To do this, you must log in
> SSH and edit the / etc / network / interfaces file for the IP and
> etc / apache2 / sites-available / default files and
> etc / apache2 / sites-available / default\_ssl (for HTTPS).However, in
> If your Jeedom is mishandled, the Jeedom team will not
> may be held responsible and may refuse any request for
> Support.

-   **Internal access** : information for joining Jeedom from a
    same network equipment as Jeedom (LAN)

    -   **OK / NOK** : indicates whether the internal network configuration is
        correcte

    -   **Protocol** : the protocol to use, often HTTP

    -   **URL or IP address** : Jeedom IP to enter

    -   **Harbor** : the port of the Jeedom web interface, generally 80.
        Please note changing the port here does not change the actual port of
        Jeedom which will remain the same

    -   **Complement** : the fragment of additional URL (example
        : / jeedom) to access Jeedom.

-   **External access** : information to reach Jeedom from outside
    local network. To be completed only if you are not using DNS
    Jeedom

    -   **OK / NOK** : indicates whether the external network configuration is
        correcte

    -   **Protocol** : protocol used for outdoor access

    -   **URL or IP address** : External IP, if it is fixed. If not,
        give the URL pointing to the external IP address of your network.

    -   **Complement** : the fragment of additional URL (example
        : / jeedom) to access Jeedom.

> **Tip**
>
> If you are in HTTPS the port is 443 (by default) and in HTTP the
> port is 80 (default). To use HTTPS from outside,
> a letsencrypt plugin is now available on the market.

> **Tip**
>
> To find out if you need to set a value in the field
> **Complement**, look, when you log into Jeedom in
> your Internet browser, if you need to add / jeedom (or other
> thing) after the IP.

-   **Advanced management** : This part may not appear, in
    depending on compatibility with your hardware. You will find
    the list of your network interfaces. You can tell Jeedom
    not to monitor the network by clicking on **deactivate the
    network management by Jeedom** (check if Jeedom is not connected to
    no network). You can also specify the local ip range in the form 192.168.1.* (to be used only in docker type installations)

-   **Proxy market** : allows remote access to your Jeedom without having
    need a DNS, a fixed IP or to open the ports of your box
    Internet

    -   **Using Jeedom DNS** : activates Jeedom DNS (attention
        this requires at least one service pack)

    -   **DNS status** : DNS HTTP status

    -   **Management** : allows to stop and restart the DNS service

> **IMPORTANT**
>
> If you can&#39;t get Jeedom DNS to work, check the
> configuration of the firewall and parental filter of your Internet box
> (on livebox you need for example the firewall in medium).

Colors 
========

The colorization of widgets is performed according to the category to
which equipment belongs. Among the categories we find the
heating, Security, Energy, light, Automation, Multimedia, Other…

For each category, we can differentiate the colors of the version
desktop and mobile version. We can then change :

-   the background color of the widgets,

-   the color of the command when the widget is of the gradual type (for
    example lights, shutters, temperatures).

By clicking on the color a window opens, allowing you to choose your
color. The cross next to the color returns to the parameter
by default.

At the top of the page, you can also configure the transparency of
widgets globally (this will be the default. It is
then possible to modify this value widget by widget). Not to
put no transparency, leave 1.0 .

> **Tip**
>
> Do not forget to save after any modification.

Orders 
=========

Many orders can be logged. So in
Analysis → History, you get graphs representing their
use. This tab allows you to set global parameters for
order history.

Historical 
----------

-   **View widget statistics** : Displays
    widget statistics. The widget must be
    compatible, which is the case for most. It is also necessary that the
    command either digital.

-   **Calculation period for min, max, average (in hours)** : Period
    statistics calculation (24h by default). It is not possible
    to put less than an hour.

-   **Calculation period for the trend (in hours)** : Period of
    trend calculation (2h by default). It is not possible to
    put less than an hour.

-   **Delay before archiving (in hours)** : Indicates the delay before
    Jeedom does not archive a data (24h by default). That is to say, the
    historical data must have more than 24 hours to be archived
    (as a reminder, archiving will either average or take the maximum
    or the minimum of the data over a period which corresponds to the
    packet size).

-   **Archive by package from (in hours)** : This parameter gives
    precisely the size of the packets (1 hour by default). It means by
    example that Jeedom will take periods of 1 hour, average and
    store the new calculated value by deleting the
    averaged values.

-   **Low trend calculation threshold** : This value indicates the
    value from which Jeedom indicates that the trend is towards
    downward. It must be negative (default -0.1).

-   **High trend calculation threshold** : Same thing for the rise.

-   **Default graphics display period** : Period which is
    used by default when you want to display the history
    of an order. The shorter the period, the faster Jeedom will be
    to display the requested graph.

> **NOTE**
>
> The first parameter **View widget statistics** est
> possible but disabled by default because it significantly lengthens the
> dashboard display time. If you activate this option, for example
> default, Jeedom relies on data from the past 24 hours to
> calculate these statistics. The trend calculation method is based
> on least squares calculation (see
> [here](https://fr.wikipedia.org/wiki/M%C3%A9thode_des_moindres_carr%C3%A9s)
> for the detail).

Push 
----

**Global push URL** : allows to add a URL to call in case of
order update. You can use the following tags :
**\#value\#** for the order value, **\#cmd\_name\#** for the
command name, **\#cmd\_id\#** for the unique identifier of the
commande, **\#humanname\#** for the full name of the order (ex :
\#\[Salle de bain\]\[Hydrometrie\]\[Humidité\]\#), ``#eq_name#`for the name of the equipment

Hidden 
=====

Allows monitoring and acting on the Jeedom cache :

-   **Statistics** : Number of objects currently cached

-   **Clean the cover** : Force the deletion of objects that are not
    more useful. Jeedom does this automatically every night.

-   **Clear all cached data** : Empty the cover completely.
    Please note that this may cause data loss !

-   **Pause time for long polling** : How often
    Jeedom checks if there are any pending events for customers
    (web interface, mobile application…). The shorter this time, the more
    the interface will update quickly, in return this
    uses more resources and can therefore slow Jeedom.

Interactions 
============

This tab allows you to set global parameters concerning
interactions which you will find in Tools → Interactions.

> **Tip**
>
> To activate the interaction log, go to the tab
> Administration → Configuration → Logs, then tick **Debug** in the list
> bottom. WARNING : the logs will then be very verbose !

General 
-------

Here you have three parameters :

-   **Sensitivity** : there are 4 levels of correspondence (Sensitivity
    ranges from 1 (exactly matches) to 99)

    -   for 1 word : the level of correspondence for interactions at
        only one word

    -   2 words : the level of correspondence for interactions at
        two words

    -   3 words : the level of correspondence for interactions at
        three words

    -   more than 3 words : the level of correspondence for interactions
        more than three words

-   **Do not respond if interaction is not understood** : by default
    Jeedom responds &quot;I didn&#39;t understand&quot; if no interaction
    does not match. It is possible to deactivate this function for
    that Jeedom doesn&#39;t answer anything. Check the box to deactivate
    the answer.

-   **General exclusion regex for interactions** : allows
    define a regexp which, if it corresponds to an interaction,
    will automatically delete this sentence from the generation (reserved
    to experts). For more information see the explanations in the
    chapter **Regexp exclusion** documentation on
    the interactions.

Automatic interaction, contextual &amp; warning 
-----------------------------------------------------

-   The **automatic interactions** allow Jeedom to attempt to
    understand an interaction request even if there is none
    of defined. He will then look for an object and / or equipment name
    and / or order to try to respond as best as possible.

-   The **contextual interactions** allow you to chain
    multiple requests without repeating everything, for example :

    -   *Jeedom keeping the context :*

        -   *You* : How much is he in the room ?

        -   *Jeedom* : Temperature 25.2 ° C

        -   *You* : and in the living room ?

        -   *Jeedom* : Temperature 27.2 ° C

    -   *Ask two questions in one :*

        -   *You* : What is it like in the bedroom and in the living room ?

        -   *Jeedom* : Temperature 23.6 ° C, Temperature 27.2 ° C

-   Type interactions **Warn me** let&#39;s ask
    Jeedom to notify you if an order exceeds / descends or is worth a
    certain value.

    -   *You* : Notify me if the living room temperature exceeds 25 ° C ?

    -   *Jeedom* : OK (* As soon as the living room temperature exceeds 25 ° C,
        Jeedom will tell you, only once*)

> **NOTE**
>
> By default Jeedom will answer you by the same channel as the one you
> used to ask him to notify you. If he doesn&#39;t find one
> not, it will then use the default command specified in this
> tab : **Default return command**.

Here are the different options available :

-   **Enable automatic interactions** : Check to activate
    automatic interactions.

-   **Enable contextual responses** : Check to activate
    contextual interactions.

-   **Priority contextual response if the sentence begins with** : Si
    the sentence begins with the word you enter here, Jeedom will
    then prioritize a contextual response (you can put
    several words separated by **;** ).

-   **Cut an interaction in half if it contains** : Same thing for
    the breakdown of an interaction containing several questions. You
    give here the words that separate the different questions.

-   **Activate "Notify Me" interactions"** : Check to activate
    Type interactions **Warn me**.

-   **&quot;Tell me&quot; response if the sentence starts with** : If the
    sentence starts with this word (s) then Jeedom will try to make a
    type interaction **Warn me** (you can put multiple
    words separated by **;** ).

-   **Default return command** : Default return command
    for a type interaction **Warn me** (used, in particular,
    if you have programmed the alert via the mobile interface)

-   **Synonym for objects** : List of synonyms for objects
    (Ex : rdc|ground floor|basement|low; sdb|Bathroom).

-   **Synonym for equipment** : Synonyms list for
    equipment.

-   **Synonym for orders** : Synonyms list for
    the orders.

-   **Synonym for abstracts** : List of synonyms for summaries.

-   **Synonym for maximum slider command** : Synonym for putting a
    maximum slider type command (ex opens to opens the shutter
    the room ⇒ 100% room shutter).

-   **Synonym for minimum slider command** : Synonym for putting a
    slider type command at minimum (ex closes to close the shutter
    the room ⇒ room component at 0%).

Colors 
--------

This part allows you to define the colors that Jeedom will associate with
words red / blue / black… To add a color :

-   Click on the button **+**, to the right,

-   Give your color a name,

-   Choose the associated color by clicking on the box on the right.

Reports 
========

Configure the generation and management of reports

-   **Timeout after page generation (in ms)** : Time limit
    waiting after loading the report to take the &quot;photo&quot;, at
    change if your report is incomplete for example.

-   **Clean up older reports from (days)** : Defines the
    number of days before deleting a report (reports take
    a little space so be careful not to put too much
    conservation).

Connections 
=====

Configure link graphics. These links allow you to
see, in the form of a graph, the relationships between objects,
equipment, objects, etc.

-   **Depth for scenarios** : Used to define, when
    displaying a graph of links of a scenario, the number
    maximum number of elements to display (the more elements the greater the
    graphic will be slow to generate and the more difficult it will be to read).

-   **Depth for objects** : Same for objects.

-   **Depth for equipment** : Same for the equipment.

-   **Depth for controls** : Same for orders.

-   **Depth for variables** : Same for variables.

-   **Parameter of prerender** : Lets act on the layout
    of the graph.

-   **Render parameter** : Same.

Summaries 
=======

Add object summaries. This information is displayed
at the very top, on the right, in the Jeedom menu bar, or next to the
objects :

-   **Key** : Key to the summary, especially not to touch.

-   **Last name** : Abstract name.

-   **Calculation** : Calculation method, can be of type :

    -   **Sum** : sum the different values,

    -   **Average** : averages values,

    -   **Text** : display the value verbatim (especially for those
        of string type).

-   **Icon** : Summary icon.

-   **Unit** : Summary unit.

-   **Counting method** : If you count binary data then
    you have to set this value to binary, example if you count the
    number of lights on but you just have the value of
    dimmer (0 to 100), then you have to put binary, like this Jeedom
    consider that if the value is greater than 1, then the lamp
    is on.

-   **Show if value is 0** : Check this box to display the
    value, even when it is 0.

-   **Link to a virtual** : Start creating virtual orders
    having for value those of the summary.

-   **Delete summary** : The last button, on the far right, allows
    to delete the summary from the line.

Logs 
====

Timeline 
--------

-   **Maximum number of events** : Sets the maximum number to
    show in timeline.

-   **Delete all events** : Empty the timeline of
    all his recorded events.

Posts 
--------

-   **Add a message to each error in the logs** : if a plugin
    or Jeedom writes an error message in a log, Jeedom adds
    automatically a message in the message center (at least
    you are sure not to miss it).

-   **Action on message** : Allows you to take an action when adding a message to the message center. You have 2 tags for these actions : 
        - #message# : message in question
        - #plugin# : plugin that triggered the message

Notifications 
-------

-   **Add a message to each Timeout** : Add a message in the
    message center if equipment falls in **timeout**.

-   **Timeout order** : Type command **Message** use
    if an equipment is in **timeout**.

-   **Add a message to each Battery in Warning** : Add a
    message in the message center if a device has its level of
    battery in **Warning**.

-   **Battery command in Warning** : Type command **Message**
    to be used if equipment at its battery level **Warning**.

-   **Add a message to each Battery in Danger** : Add a
    message in the message center if a device has its level of
    battery in **Danger**.

-   **Command on Battery in Danger** : Type command **Message** at
    use if equipment at its battery level **Danger**.

-   **Add a message to each Warning** : Add a message in the
    message center if an order goes on alert **Warning**.

-   **Command on Warning** : Type command **Message** use
    if an order goes on alert **Warning**.

-   **Add a message to each Danger** : Add a message in the
    message center if an order goes on alert **Danger**.

-   **Command on Danger** : Type command **Message** to use if
    an order goes on alert **Danger**.

Log 
---

-   **Log engine** : Allows you to change the log engine for, for
    example, send them to a syslog demon (d).

-   **Log format** : Log format to use (Caution : ça
    does not affect daemon logs).

-   **Maximum number of lines in a log file** : Defines the
    maximum number of lines in a log file. It is recommended
    not to touch this value, because too large a value could
    fill the file system and / or render Jeedom incapable
    to display the log.

-   **Default log level** : When you select "Default",
    for the level of a log in Jeedom, this is the one that will be
    then used.

Below you will find a table for finely managing the
log level of the essential elements of Jeedom as well as that of
plugins.

Facilities 
===========

-   **Number of failures before deactivation of the equipment** : Nombre
    communication failure with the equipment before deactivation of
    this one (a message will warn you if this happens).

-   **Battery thresholds** : Allows you to manage global alert thresholds
    on the batteries.

Update and files 
=======================

Jeedom update 
---------------------

-   **Update source** : Choose the source for updating the
    Jeedom core.

-   **Core version** : Core version to recover.

-   **Automatically check for updates** : Indicate if
    you have to search automatically if there are new updates
    (be careful to avoid overloading the market, the time of
    verification may change).

Deposits 
----------

The depots are storage (and service) spaces to be able to
move backups, recover plugins, recover core
Jeedom, etc.

### File 

Deposit used to activate the sending of plugins by files.

### Github 

Deposit used to connect Jeedom to Github.

-   **Token** : Token for access to private deposit.

-   **Jeedom core repository user or organization** : Nom
    the user or the organization on github for the core.

-   **Repository name for the Jeedom core** : Repository name for core.

-   **Jeedom core industry** : Core repository branch.

### Market 

Deposit used to connect Jeedom to the market, it is highly recommended
to use this repository. WARNING : any support request may be
refused if you use a different deposit than this one.

-   **Address** : Market address.

-   **Username** : Your Username on the Market.

-   **Password** : Your Market password.

-   **[Backup cloud] Name** : Name of your cloud backup (attention must be unique for each Jeedom at risk of it crashing between them)

-   **[Backup cloud] Password** : Cloud backup password. IMPORTANT you must not lose it, there is no way to recover it. Without it you will not be able to restore your Jeedom

-   **[Backup cloud] Frequency full backup** : Frequency of full cloud backup. A full backup is longer than an incremental one (which only sends the differences). It is recommended to do 1 per month

### Samba 

Deposit to automatically send a Jeedom backup to
a Samba share (ex : NAS Synology).

-   **\ [Backup \] IP** : Samba server IP.

-   **\ [Backup \] User** : Username for login
    (anonymous connections are not possible). There must be
    that the user has read and write rights on the
    destination directory.

-   **\ [Backup \] Password** : User password.

-   **\ [Backup \] Sharing** : Way of sharing (be careful
    stop at the sharing level).

-   **\ [Backup \] Path** : Path in sharing (to set
    relative), it must exist.

> **NOTE**
>
> If the path to your samba backup folder is :
> \\\\ 192.168.0.1 \\ Backups \\ Home automation \\ Jeedom Then IP = 192.168.0.1
> , Sharing = //192.168.0.1 / Backups, Path = Home automation / Jeedom

> **NOTE**
>
> When validating the Samba share, as described above,
> a new form of backup appears in the section
> Administration → Jeedom backups. By activating it, Jeedom will proceed
> when it is automatically sent at the next backup. A test is
> possible by performing a manual backup.

> **IMPORTANT**
>
> You may need to install the smbclient package for the
> deposit works.

> **IMPORTANT**
>
> The Samba protocol has several versions, the v1 is compromised level 
> security and on some NAS you can force the client to use v2
> or v3 to connect. So if you have a protocol negotiation error
> failed: NT_STATUS_INVAID_NETWORK_RESPONSE there is a good chance that listed NAS
> the restriction be in place. You must then modify on the OS of your Jeedom
> the / etc / samba / smb file.conf and add these two lines to it :
> client max protocol = SMB3
> client min protocol = SMB2
> The Jeedom side smbclient will then use v2 where v3 and by putting SMB3 in both only
> SMB3. So it&#39;s up to you to adapt according to restrictions on the NAS or other Samba server

> **IMPORTANT**
>
> Jeedom should be the only one to write to this folder and it should be empty
> by default (i.e. before configuring and sending the
> first backup, the folder must not contain any file or
> dossier).

### URLs 

-   **Jeedom core URL**

-   **Jeedom core version URL**


