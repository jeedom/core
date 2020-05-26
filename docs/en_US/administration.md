# Configuration
**Settings → System → Configuration**

This is where most of the configuration parameters are found.
Although many, a majority of parameters are configured by default.


## General tab

In this tab we find general information about Jeedom :

- **Name of your Jeedom** : Identify your Jeedom, especially in the Market. It can be reused in scenarios or to identify a backup.
- **Language** : Language used in your Jeedom.
- **System** : Type of hardware on which the system where your Jeedom runs is installed.
- **Generate translations** : Generate translations, beware, this can slow down your system. Option especially useful for developers.
- **Date and hour** : Choose your time zone. You can click on **Force time synchronization** to restore the wrong time displayed at the top right.
- **Optional time server** : Indicates which time server should be used if you click on **Force time synchronization** (to be reserved for experts).
- **Skip time check** : tells Jeedom not to check if the time is consistent between itself and the system it is running on. May be useful for example, if you do not connect Jeedom to the Internet and it does not have a PSTN battery on the equipment used.
- **System** : Indicates the type of hardware on which Jeedom is installed.
- **Installation key** : Hardware key of your Jeedom on the Market. If your Jeedom does not appear in the list of your Jeedom on the Market, it is advisable to click on the button **Reset**.
- **Last known date** : Date recorded by Jeedom, used after a restart for systems without a PSTN battery.

## Interface tab

In this tab you will find the display customization parameters.

### Themes

- **Light and dark desktop** : Lets you choose a light and dark theme for the Desktop.
- **Light and dark mobile** : same as above for the Mobile version.
- **Clear theme from / to** : Allows you to define a time period during which the previously chosen clear theme will be used. However, check the option **Toggle theme based on time**.
- **Brightness sensor**   : Mobile interface only, requires activating *generic extra sensor* in chrome, chrome page://flags.
- **Hide background images** : Allows you to hide the background images found in the scenarios, objects, interactions pages, etc.

### Tuiles

- **Tiles Not horizontal** : Constrains the width of the tiles every x pixels.
- **Tiles Not vertical** : Constrains the height of the tiles every x pixels.
- **Margin tiles** : Vertical and horizontal space between tiles, in pixels.

### Personnalisation

- **Activate** : Activate the use of the options below.
- **Transparency** : Displays Dashboard tiles and some content with transparency. 1 : totally opaque, 0 : totally transparent.
- **Round** : Displays interface elements with rounded angles. 0 : no rounding, 1 : maximum rounding.
- **Disable shadows** : Disables shadows from tiles on the dashboard, menus, and certain interface elements.



## Networks tab

It is absolutely necessary to correctly configure this important part of Jeedom otherwise a lot of plugins may not work. There are two ways to access Jeedom : L'**Internal access** (from the same local network as Jeedom) and l'**External access** (from another network, especially from the Internet).

> **IMPORTANT**
>
> This part is just there to explain to Jeedom its environment :
> changing the port or IP in this tab will not change Jeedom&#39;s port or IP actually. For that you have to connect in SSH and edit the file / etc / network / interfaces for IP and the files etc / apache2 / sites-available / default and etc / apache2 / sites-available / default\_ssl (for HTTPS).
> However, in the event of improper handling of your Jeedom, the Jeedom team cannot be held responsible and may refuse any request for support.

- **Internal access** : information for joining Jeedom from equipment on the same network as Jeedom (LAN)
    - **OK / NOK** : indicates if the internal network configuration is correct.
    - **Protocol** : the protocol to use, often HTTP.
    - **URL or IP address** : Jeedom IP to enter.
    - **Harbor** : the port of the Jeedom web interface, generally 80.
        Please note changing the port here does not change the actual Jeedom port which will remain the same.
    - **Complement** : the fragment of additional URL (example : / jeedom) to access Jeedom.

- **External access** : information to reach Jeedom from outside the local network. To be completed only if you are not using Jeedom DNS.
    - **OK / NOK** : indicates whether the external network configuration is correct.
    - **Protocol** : protocol used for outdoor access.
    - **URL or IP address** : External IP, if fixed. Otherwise, give the URL pointing to the external IP address of your network.
    - **Complement** : the fragment of additional URL (example : / jeedom) to access Jeedom.

- **Proxy for market** : proxy activation.
    - Check the enable proxy box.
    - **Proxy address** : Enter the proxy address,
    - **Proxy port** : Enter the proxy port,
    - **Login** : Enter the proxy login,
    - **Password** : Enter the password.

> **Tip**
>
> If you are in HTTPS the port is 443 (default) and in HTTP the port is 80 (default). To use HTTPS from outside, a letsencrypt plugin is now available on the market.

> **Tip**
>
> To find out if you need to set a value in the field **Complement**, look, when you log into Jeedom in your internet browser, if you need to add / Jeedom (or whatever) after the IP.

- **Advanced management** : This part may not appear, depending on the compatibility with your hardware.
    You will find there the list of your network interfaces. You can tell Jeedom not to monitor the network by clicking on **disable Jeedom network management** (check if Jeedom is not connected to any network). You can also specify the local ip range in the form 192.168.1.* (to be used only in docker type installations).
- **Proxy market** : allows remote access to your Jeedom without the need for a DNS, a fixed IP or to open the ports of your Internet box.
    - **Using Jeedom DNS** : activates Jeedom DNS (note that this requires at least one service pack).
    - **DNS status** : DNS HTTP status.
    - **Management** : allows to stop and restart the Jeedom DNS service.

> **IMPORTANT**
>
> If you cannot get Jeedom DNS to work, look at the configuration of the firewall and parental filter of your Internet box (on livebox you need for example the firewall at medium level).
- **Lifetime of sessions (hour)** : lifetime of PHP sessions, it is not recommended to touch this parameter.

## Logs tab

### Timeline

- **Maximum number of events** : Defines the maximum number of events to display in the timeline.
- **Delete all events** : Empty the timeline of all its recorded events.

### Messages

- **Add a message to each error in the logs** : if a plugin or Jeedom writes an error message in a log, Jeedom automatically adds a message in the message center (at least you are sure not to miss it).
- **Action on message** : Allows you to take an action when adding a message to the message center. You have 2 tags for these actions :
        - #subject# : message in question.
        - #plugin# : plugin that triggered the message.

### Alertes

- **Add a message to each Timeout** : Add a message in the message center if a device falls in **timeout**.
- **Timeout order** : Type command **Message** to be used if an equipment is in **timeout**.
- **Add a message to each Battery in Warning** : Add a message in the message center if a device has its battery level in **Warning**.
- **Battery command in Warning** : Type command **Message** to be used if equipment at its battery level **Warning**.
- **Add a message to each Battery in Danger** : Add a message in the message center if a device has its battery level in **Danger**.
- **Command on Battery in Danger** : Type command **Message** to be used if equipment at its battery level **Danger**.
- **Add a message to each Warning** : Add a message in the message center if an order goes on alert **Warning**.
- **Command on Warning** : Type command **Message** to use if an order goes on alert **Warning**.
- **Add a message to each Danger** : Add a message in the message center if an order goes on alert **Danger**.
- **Command on Danger** : Type command **Message** to use if an order goes on alert **Danger**.

### Logs

- **Log engine** : Allows you to change the log engine to, for example, send them to a syslog daemon (d).
- **Log format** : Log format to use (Caution : it doesn't affect daemon logs).
- **Maximum number of lines in a log file** : Defines the maximum number of lines in a log file. It is recommended not to touch this value, as a too large value could fill the file system and / or make Jeedom unable to display the log.
- **Default log level** : When you select &quot;Default&quot;, for the level of a log in Jeedom, this will be used.

Below you will find a table for finely managing the log level of essential elements of Jeedom as well as that of plugins.

## Orders Tab

Many orders can be logged. Thus, in Analysis → History, you get graphs representing their use. This tab allows you to set global parameters for command logging.

### Historique

- **View widget statistics** : View statistics on widgets. The widget must be compatible, which is the case for most. The command must also be of numeric type.
- **Calculation period for min, max, average (in hours)** : Statistics calculation period (24h by default). It is not possible to take less than an hour.
- **Calculation period for the trend (in hours)** : Trend calculation period (2h by default). It is not possible to take less than an hour.
- **Delay before archiving (in hours)** : Indicates the delay before Jeedom archives data (24h by default). That is to say that the historical data must have more than 24 hours to be archived (as a reminder, archiving will either average, or take the maximum or minimum of the data over a period which corresponds to the size of the packets).
- **Archive by package from (in hours)** : This parameter gives the packet size (1 hour by default). This means for example that Jeedom will take periods of 1 hour, average and store the new calculated value by deleting the averaged values.
- **Low trend calculation threshold** : This value indicates the value from which Jeedom indicates that the trend is downward. It must be negative (default -0.1).
- **High trend calculation threshold** : Same thing for the rise.
- **Default graphics display period** : Period which is used by default when you want to display the history of an order. The shorter the period, the faster Jeedom will display the requested graph.

> **NOTE**
>
> The first parameter **View widget statistics** is possible but disabled by default because it significantly extends the display time of the dashboard. If you activate this option, by default, Jeedom relies on data from the past 24 hours to calculate these statistics.
> The trend calculation method is based on the least squares calculation (see [here](https://fr.wikipedia.org/wiki/M%C3%A9thode_des_moindres_carr%C3%A9s) for the detail).

### Push

- **Global push URL** : allows you to add a URL to call in the event of an order update. You can use the following tags :
**\#value\#** for the order value, **\#cmd\_name\#** for the name of the command,
**\#cmd\_id\#** for the unique identifier of the order,
**\#humanname\#** for the full name of the order (ex : \#\[Salle de bain\]\[Hydrometrie\]\[Humidité\]\#),
**\#eq_name\#** for the name of the equipment

## Summaries tab

Add object summaries. This information is displayed at the top right of the Jeedom menu bar, or next to objects :

- **Key** : Key to the summary, especially not to touch.
- **Last name** : Abstract name.
- **Calculation** : Calculation method, can be of type :
    - **Sum** : sum the different values,
    - **Average** : averages values,
    - **Text** : display the value verbatim (especially for those of type string).
- **Icon** : Summary icon.
- **Unit** : Summary unit.
- **Counting method** : If you count a binary data then you have to put this value in binary, example if you count the number of lights on but you just have the value of the dimmer (0 to 100), then you have to put binary, like that Jeedom considered that if the value is greater than 1, then the lamp is on.
- **Show if value is 0** : Check this box to display the value, even when it is 0.
- **Link to a virtual** : Launches the creation of virtual orders having for value those of the summary.
- **Delete summary** : The last button, on the far right, deletes the summary from the line.

## Equipment tab

- **Number of failures before deactivation of the equipment** : Number of communications failures with the equipment before deactivation of the equipment (a message will warn you if this happens).
- **Battery thresholds** : Allows you to manage the global alert thresholds on the stacks.

## Reports tab

Configure the generation and management of reports

- **Timeout after page generation (in ms)** : Waiting time after loading the report to take the &quot;photo&quot;, to change if your report is incomplete for example.
- **Clean up older reports from (days)** : Defines the number of days before deleting a report (the reports take up a little space so be careful not to put too much conservation).

## Links tab

Configure link graphics. These links allow you to see, in the form of a graph, the relationships between objects, equipment, objects, etc.

- **Depth for scenarios** : Allows to define, when displaying a graph of links of a scenario, the maximum number of elements to display (the more there are elements the slower the graph will be to generate and the more it will be difficult to read).
- **Depth for objects** : Same for objects.
- **Depth for equipment** : Same for the equipment.
- **Depth for controls** : Same for orders.
- **Depth for variables** : Same for variables.
- **Parameter of prerender** : Allows you to act on the layout of the graph.
- **Render parameter** : Same.

## Interactions tab

This tab allows you to set global parameters concerning the interactions that you will find in Tools → Interactions.

> **Tip**
>
> To activate the interaction log, go to the Settings → System → Configuration tab : Logs, then check **Debug** in the bottom list. Warning : the logs will then be very verbose !

### General

Here you have three parameters :

- **Sensitivity** : there are 4 levels of correspondence (The sensitivity goes from 1 (corresponds exactly) to 99)
    -   for 1 word : match level for single word interactions.
    -   2 words : the match level for two-word interactions.
    -   3 words : the match level for three-word interactions.
    -   more than 3 words : match level for interactions longer than three words.
- **Do not respond if interaction is not understood** : by default Jeedom responds &quot;I did not understand&quot; if no interaction corresponds. It is possible to deactivate this operation so that Jeedom does not respond. Check the box to disable the response.
- **General exclusion regex for interactions** : allows to define a regexp which, if it corresponds to an interaction, will automatically delete this sentence from the generation (reserved for experts). For more information see explanations in chapter **Regexp exclusion** documentation on interactions.

### Automatic interaction, contextual &amp; warning

-   The **automatic interactions** allow Jeedom to try to understand an interaction request even if there is none defined. He will then look for an object name and / or equipment and / or order to try to respond as best as possible.

-   The **contextual interactions** allow you to chain multiple requests without repeating everything, for example :
    - *Jeedom keeping the context :*
        - *You* : How much is he in the room ?
        - *Jeedom* : Temperature 25.2 ° C
        - *You* : and in the living room ?
        - *Jeedom* : Temperature 27.2 ° C
    - *Ask two questions in one :*
        - *You* : What is it like in the bedroom and in the living room ?
        - *Jeedom* : Temperature 23.6 ° C, Temperature 27.2 ° C
-   Type interactions **Warn me** allow to ask Jeedom to warn you if an order exceeds / falls or is worth a certain value.
    - *You* : Notify me if the living room temperature exceeds 25 ° C ?
    - *Jeedom* : Ok (*As soon as the living room temperature exceeds 25 ° C, Jeedom will tell you, once*)

> **NOTE**
>
> By default Jeedom will answer you by the same channel as the one you used to ask it to notify you. If it does not find one, it will then use the default command specified in this tab : **Default return command**.

Here are the different options available :

- **Enable automatic interactions** : Check to enable automatic interactions.
- **Enable contextual responses** : Check to enable contextual interactions.
- **Priority contextual response if the sentence begins with** : If the sentence begins with the word you enter here, Jeedom will then prioritize a contextual response (you can put several words separated by **;** ).
- **Cut an interaction in half if it contains** : Same thing for the division of an interaction containing several questions. Here you give the words that separate the different questions.
- **Activate "Notify Me" interactions"** : Check to enable type interactions **Warn me**.
- **&quot;Tell me&quot; response if the sentence starts with** : If the sentence begins with this word (s) then Jeedom will seek to make an interaction of the type **Warn me** (you can put several words separated by **;** ).
- **Default return command** : Default return command for type interaction **Warn me** (used, in particular, if you have programmed the alert via the mobile interface)
- **Synonym for objects** : List of synonyms for objects (ex : rdc|ground floor|basement|low; sdb|Bathroom).
- **Synonym for equipment** : List of synonyms for equipment.
- **Synonym for orders** : List of synonyms for commands.
- **Synonym for abstracts** : List of synonyms for summaries.
- **Synonym for maximum slider command** : Synonym for placing a slider type command to the maximum (ex opens to opens the bedroom shutter ⇒ bedroom shutter at 100%).
- **Synonym for minimum slider command** : Synonym for putting a slider type command to the minimum (ex closes to close the bedroom shutter ⇒ bedroom shutter at 0%).

## Security tab

### LDAP

- **Enable LDAP authentication** : enable authentication through an AD (LDAP).
- **Host** : server hosting the AD.
- **Field** : domain of your AD.
- **DN base** : DN base of your AD.
- **Username** : username for Jeedom to log into AD.
- **Password** : password for Jeedom to connect to AD.
- **User search fields** : user login search fields. Usually uid for LDAP, SamAccountName for Windows AD.
- **Administrators filter (optional)** : administrators filter on AD (for group management for example)
- **User filter (optional)** : user filter on the AD (for group management for example)
- **Limited user filter (optional)** : filter limited users on the AD (for group management for example)
- **Allow REMOTE\_USER** : Activate REMOTE\_USER (used in SSO for example).

### Connexion

- **Number of failures tolerated** : defines the number of successive attempts allowed before banning the IP
- **Maximum time between failures (in seconds)** : maximum time for 2 attempts to be considered successive
- **Duration of banishment (in seconds), -1 for infinity** : IP ban time
- **IP "white"** : list of IPs that can never be banned
- **Remove banned IPs** : Clear the list of currently banned IPs

The list of banned IPs is at the bottom of this page. You will find the IP, the ban date and the scheduled ban end date.

## Update / Market Tab

### Jeedom update

- **Update source** : Choose Jeedom core update source.
- **Core version** : Core version to recover.
- **Automatically check for updates** : Indicate whether to automatically check if there are new updates (be careful to avoid overloading the market, the verification time may change).

### Deposits

The repositories are storage (and service) spaces to be able to move backups, recover plugins, recover the core of Jeedom, etc.

### Fichier

Deposit used to activate the sending of plugins by files.

#### Github

Deposit used to connect Jeedom to Github.

- **Token** : Token for access to private deposit.
- **Jeedom core repository user or organization** : User or organization name on github for the core.
- **Repository name for the Jeedom core** : Repository name for core.
- **Jeedom core industry** : Core repository branch.

#### Market

Deposit used to connect Jeedom to the market, it is strongly advised to use this deposit. Warning : any request for support may be refused if you use a deposit other than this one.

- **Address** : Address du Market.(https://www.Jeedom.com/market).
- **Username** : Your Username on the Market.
- **Password** : Your Market password.
- **[Backup cloud] Name** : Name of your cloud backup (attention must be unique for each Jeedom at risk of it crashing between them).
- **[Backup cloud] Password** : Cloud backup password. IMPORTANT you must not lose it, there is no way to recover it. Without it you will no longer be able to restore your Jeedom.
- **[Backup cloud] Frequency full backup** : Frequency of full cloud backup. A full backup is longer than an incremental one (which only sends the differences). It is recommended to do 1 per month.

#### Samba

Deposit allowing to automatically send a backup of Jeedom on a Samba share (ex : NAS Synology).

- **\ [Backup \] IP** : Samba server IP.
- **\ [Backup \] User** : Username for connection (anonymous connections are not possible). The user must have read AND write rights on the destination directory.
- **\ [Backup \] Password** : User password.
- **\ [Backup \] Sharing** : Path to sharing (be careful to stop at the sharing level).
- **\ [Backup \] Path** : Path in the sharing (to put in relative), this must exist.

> **NOTE**
>
> If the path to your samba backup folder is :
> \\\\ 192.168.0.1 \\ Backups \\ Home automation \\ Jeedom Then IP = 192.168.0.1, Sharing = //192.168.0.1 / Backups, Path = Home automation / Jeedom

> **NOTE**
>
> When validating the Samba share, as described above, a new form of backup appears in the Settings → System → Backups section of Jeedom. By activating it, Jeedom will send it automatically during the next backup. A test is possible by performing a manual backup.

> **IMPORTANT**
>
> You may need to install the smbclient package for the repository to work.

> **IMPORTANT**
>
> The Samba protocol has several versions, the v1 is compromised in terms of security and on some NAS you can force the client to use v2 or v3 to connect. So if you have an error *protocol negotiation failed: NT_STATUS_INVAID_NETWORK_RESPONSE* there is a good chance that on the NAS side the restriction is in place. You must then modify the / etc / samba / smb file on your Jeedom OS.conf and add these two lines to it :
> client max protocol = SMB3
> client min protocol = SMB2
> The Jeedom side smbclient will then use v2 where v3 and by putting SMB3 to both only SMB3. So it&#39;s up to you to adapt according to restrictions on the NAS or other Samba server

> **IMPORTANT**
>
> Jeedom must be the only one to write in this folder and it must be empty by default (i.e. before the configuration and the sending of the first backup, the folder must not contain any file or folder).

#### URL

- **Jeedom core URL**
- **Jeedom core version URL**

## Cache tab

Allows monitoring and acting on the Jeedom cache :

- **Statistics** : Number of objects currently cached.
- **Clean the cover** : Force deletion of objects that are no longer useful. Jeedom does this automatically every night.
- **Clear all cached data** : Empty the cover completely.
    Please note that this may cause data loss !
- **Clear the widget cache** : Clear the cache dedicated to widgets.
- **Disable widget cache** : Check the box to disable the widget caches.
- **Pause time for long polling** : Frequency at which Jeedom checks if there are pending events for customers (web interface, mobile application, etc.)). The shorter this time, the faster the interface will update, however it uses more resources and can therefore slow Jeedom.

## API tab

Here you find the list of the different API keys available in your Jeedom. Core has two API keys :

-   a general : as much as possible, avoid using it,
-   and another for professionals : used for fleet management. It can be empty.
-   Then you will find one API key per plugin that needs it.

For each API plugin key, as well as for HTTP, JsonRPC and TTS APIs, you can define their scope :

- **Disabled** : API key cannot be used,
- **White IP** : only a list of IPs is authorized (see Settings → System → Configuration : Networks),
- **Localhost** : only requests from the system on which Jeedom is installed are allowed,
- **Activated** : no restrictions, any system with access to your Jeedom will be able to access this API.

## Onglet &gt;\_OS/DB

> **IMPORTANT**
>
> This tab is reserved for experts.
> If you modify Jeedom with one of these two solutions, the support may refuse to help you.

- **General** :
    - **General verification** : Lets launch Jeedom consistency test.
- **&gt;\_System** :
    - **Settings** : Provides access to a system administration interface. It is a kind of shell console in which you can launch the most useful commands, in particular to obtain information on the system.
    - Reinstatement of rights : Enables you to reapply the correct rights to the Jeedom Core directories and files.
- **File editor** : Allows access to various operating system files and to edit or delete or create them.
- **Database** :
    - **Settings** : Allows access to the Jeedom database. You can then launch commands in the top field.
    - **Verification** : Allows to launch a verification on the Jeedom database and to correct errors if necessary
    - **Cleaning** : Launches a database check and cleans up any unused entries.
    - **User** : Username used by Jeedom in the database,
    - **Password** : password to access the database used by Jeedom.
