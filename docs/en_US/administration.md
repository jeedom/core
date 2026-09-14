# Setup
**Settings → System → Configuration**

Most of the configuration settings can be found on this page.
Although there are many settings, most of them are configured by default.


## General Tab

This tab contains general information about Jeedom:

- **Your Jeedom Name**: Identifies your Jeedom, particularly in the Market. It can be reused in scenarios or used to identify a backup.
- **Language**: The language used in your Jeedom.
- **System**: The type of hardware on which the system running your Jeedom is installed.
- **Date and Time**: Select your time zone. You can click **Force Time Synchronization** to correct an incorrect time displayed in the upper-right corner.
- **Optional time server**: Specifies which time server should be used if you click **Force time synchronization** (for experts only).
- **Skip time verification**: Instructs Jeedom not to verify whether the time is consistent between itself and the system on which it is running. This may be useful, for example, if you do not connect Jeedom to the Internet and the hardware being used does not have an RTC battery.
- **System**: Indicates the type of hardware on which Jeedom is installed.
- **Installation Key**: Your Jeedom’s hardware key on the Market. If your Jeedom does not appear in the list of Jeedoms on the Market, we recommend clicking the **Reset** button.
- **Last known date**: Date recorded by Jeedom, used after a reboot for systems without an RTC battery.

Below are several settings that centralize information that can be used by the plugins, eliminating the need to enter it in each plugin.

- Coordinates: Latitude, Longitude, and Altitude of your home or site.
- Address: Mailing address of your home or site.
- Miscellaneous: Floor area and number of occupants in your home/premises.

## Interface tab

In this tab, you'll find the settings for customizing the display.

### Topics

- **Light and Dark Desktop**: Allows you to choose between a light and a dark theme for the desktop.
- **Mobile (light and dark)**: Same as above for the mobile version.
- **Light Theme From / To**: Allows you to set a time range during which the previously selected light theme will be used. However, you must check the **Switch theme based on the time** option.
- **Light Sensor**: Available only on the mobile interface; requires enabling *generic extra sensor* in Chrome on the chrome://flags page.

### Tiles

- **Horizontal**: Forces the tile width every x pixels.
- **Not vertical**: Forces the tile height every x pixels.
- **Margin**: Vertical and horizontal space between tiles, in pixels.
- **Vertical Tile Alignment**: Vertically centers the content of the tiles.
- **Colored widget icons**: Widget icons change color based on their status. Can be customized per scenario using *setColoredIcon* ("Icon Coloring").
- **Colored Categories**: Tile titles are colored based on the category.
- **Mobile: One Column by Default**: Full-width tile display on mobile


### Background images

- **Display background images**: Display the background images found on the scenarios, objects, interactions, and other pages.
- **Blur object backgrounds**: Automatically blurs the backgrounds of objects and rooms.
- **Dashboard Image**: Background image for the Dashboard pages (depending on the object's options).
- **Image Analysis**: Background image for the pages in the Analysis menu.
- **Tools Image**: Background image for the pages in the Tools menu.
- **Light Theme Opacity**: Opacity of background images in the Light theme. Adjust based on the brightness of the background images for better readability.
- **Dark Theme Opacity**: Opacity of background images in Dark Theme. Adjust based on the brightness of the background images for better readability.

### Options

- **Table Mode**: Displays the pages in the Tools menu and the supported plugins in table format.
- **Notification Position**: The position on the page where notifications appear.
- **Notification Duration**: How long notifications remain on screen, in seconds. Set to 0 to prevent them from being hidden automatically.

### Customization

- **Enable**: Enables the options listed below.
- **Transparency**: Displays Dashboard tiles and certain content with transparency. 1: completely opaque, 0: completely transparent.
- **Rounded**: Displays interface elements with rounded corners. 0: no rounding, 1: maximum rounding.
- **Turn off shadows**: Turns off the shadows on tiles on the Dashboard, in menus, and on certain interface elements.



## Networks tab

It is essential to configure this important part of Jeedom correctly; otherwise, many plugins may not work. There are two ways to access Jeedom: **internal access** (from the same local network as Jeedom) and **external access** (from another network, such as the Internet).

> **Important**
>
> This section is just here to tell Jeedom about its environment:
> Changing the port or IP address in this tab will not actually change Jeedom’s port or IP address. To do so, you must connect via SSH and edit the /etc/network/interfaces file for the IP address, and the etc/apache2/sites-available/default and etc/apache2/sites-available/default_ssl files (for HTTPS).
> However, if you misuse your Jeedom, the Jeedom team cannot be held liable and may refuse any support requests.

- **Internal Access**: Information on how to connect to Jeedom from a device on the same network as Jeedom (LAN)
    - **OK/NOK**: Indicates whether the internal network configuration is correct.
    - **Protocol**: the protocol to use, often HTTP.
    - **URL or IP address**: Enter Jeedom's IP address.
    - **Port**: the port for the Jeedom web interface, typically 80.
Please note: Changing the port here does not change the actual Jeedom port, which will remain the same.
    - **Supplement**: the additional URL segment (example: /Jeedom) used to access Jeedom.

- **External Access**: Information on how to access Jeedom from outside the local network. Fill this out only if you are not using Jeedom DNS.
    - **OK/NOK**: Indicates whether the external network configuration is correct.
    - **Protocol**: protocol used for external access.
    - **URL or IP Address**: External IP address, if it is static. Otherwise, provide the URL that points to your network’s external IP address.
    - **Supplement**: the additional URL segment (example: /Jeedom) used to access Jeedom.

- **Proxy for Market**: Proxy enabled.
    - Check the "Enable proxy" box.
    - **Proxy Address**: Enter the proxy address,
    - **Proxy Port**: Enter the proxy port,
    - **Login**: Enter the proxy login,
    - **Password**: Enter the password.

> **Tip**
>
> If you're using HTTPS, the port is 443 (by default), and if you're using HTTP, the port is 80 (by default). To use HTTPS from outside the network, a Let's Encrypt plugin is now available on the marketplace.

> **Tip**
>
> To determine whether you need to enter a value in the **complement** field, check—when you log in to Jeedom in your web browser—whether you need to add /Jeedom (or something else) after the IP address.

- **Advanced Settings**: This section may not appear, depending on your hardware's compatibility.
Here you will find a list of your network interfaces. You can tell Jeedom not to monitor the network by clicking **Disable Jeedom network management** (check this box if Jeedom is not connected to any network). You can also specify the local IP range in the format 192.168.1.* (to be used only in Docker-type installations).
- **Proxy Market**: Allows remote access to your Jeedom without needing a DNS, a static IP address, or to open ports on your Internet router.
    - **Use Jeedom DNS**: Makes Jeedom DNS active (note: this requires at least one service pack).
    - **DNS Status**: HTTP DNS status.
    - **Management**: Allows you to stop and restart the Jeedom DNS service.

> **Important**
>
> If you're having trouble getting the Jeedom DNS to work, check the firewall and parental control settings on your Internet router (on a Livebox, for example, the firewall should be set to medium).
- **Session timeout (hours)**: PHP session timeout; it is not recommended to change this setting.

## Logs tab

### Timeline

- **Maximum number of events**: Sets the maximum number of events to display in the timeline.
- **Delete All Events**: Clears the timeline of all recorded events.

### Posts

- **Add a message for each error in the logs**: If a plugin or Jeedom writes an error message to a log, Jeedom automatically adds a message to the message center (so at least you can be sure you won’t miss it).
- **Message Action**: Allows you to perform an action when a message is added to the message center. You have 2 tags for these actions:
        - #subject#: message in question.
        - #plugin#: the plugin that triggered the message.

### Alerts

- **Add a message on every timeout**: Adds a message to the message center if a device times out.
- **Timeout Command**: A **message**-type command to use if a device has timed out.
- **Add a message for each battery at the "Warning" level**: Adds a message to the message center if a device's battery level is at the **"Warning"** level.
- **Battery Level at "Warning"**: A **message**-type command to use if a device's battery level is at **"warning"**.
- **Add a message for each Low Battery**: Adds a message to the message center if a device's battery level is **low**.
- **Battery Level in Danger**: A **message**-type command to be used if a device's battery level is in **danger**.
- **Add a message to each Warning**: Adds a message to the message center if a command triggers a **warning** alert.
- **Warning Command**: A **message**-type command to be used if a command triggers a **warning** alert.
- **Add a message to each Danger event**: Adds a message to the message center if a command triggers a **danger** alert.
- **Danger Command**: A **message**-type command to be used if a command triggers a **danger** alert.

### Logs

- **Log engine**: Allows you to change the log engine so that logs are sent to, for example, the syslog(d) daemon.
- **Log format**: The log format to use (Note: This does not affect daemon logs).
- **Maximum number of lines in a log file**: Sets the maximum number of lines in a log file. It is recommended that you do not change this value, as setting it too high could fill up the file system and/or prevent Jeedom from displaying the log.
- **Default log level**: When you select "Default" for a log level in Jeedom, that level will be used.

Below is a table that allows you to fine-tune the log level for Jeedom's core components as well as for plugins.

## "Summaries" tab

[See the documentation on summaries.](https://doc.jeedom.com/concept/en_US/summary)

## "Equipment" tab

### Equipment

- **Number of failures before the device is disabled**: The number of communication failures with the device before it is disabled (you will receive a message if this happens).
- **Battery Thresholds**: Allows you to manage global alert thresholds for batteries.

Many commands can be logged. In Analysis→History, you can view graphs showing their usage. This tab allows you to set global parameters for command logging.

### Command History

- **Display widget statistics**: Allows you to display statistics on widgets. The widget must be compatible, which is the case for most of them. The command must also be of the numeric type.
- **Calculation period for min, max, and average (in hours)**: The period used to calculate statistics (24 hours by default). You cannot set a period of less than one hour.
- **Trend calculation period (in hours)**: The period used to calculate trends (default is 2 hours). You cannot set a period shorter than one hour.
- **Time Before Archiving (in hours)**: Specifies the time before Jeedom archives data (24 hours by default). This means that historical data must be older than 24 hours to be archived (as a reminder, archiving will either calculate an average, or take the maximum or minimum value of the data over a period corresponding to the packet size).
- **Archive by packet size (in hours)**: This setting specifies the packet size (1 hour by default). This means, for example, that Jeedom will take 1-hour periods, calculate the average, and store the new calculated value while deleting the averaged values.
- **Downward Trend Threshold**: This value indicates the threshold at which Jeedom determines that the trend is downward. It must be negative (default: -0.1).
- **Upper trend calculation threshold**: The same applies to upward trends.
- **Default graph display period**: The period used by default when you want to view the history of a command. The shorter the period, the faster Jeedom will display the requested graph.

> **Note**
>
> The first setting, **Show statistics on widgets**, is available but disabled by default because it significantly increases the dashboard’s loading time. If you enable this option, by default, Jeedom uses data from the last 24 hours to calculate these statistics.
> The trend calculation method is based on the least squares method (see [here](https://fr.wikipedia.org/wiki/M%C3%A9thode_des_moindres_carr%C3%A9s) (for details).

### Push

- **Global push URL**: Allows you to add a URL to be called when a command is updated. You can use the following tags:
**\#value\#** for the command value, **\#cmd_name\#** for the command name,
**\#cmd\_id\#** for the command’s unique identifier,
**\#humanname\#** for the full name of the command (e.g., \#\[Bathroom\]\[Hydrometry\]\[Humidity\]\#),
**\#eq_name\#** for the device name

## Reports tab

Allows you to configure report generation and management

- **Wait time after page generation (in ms)**: The wait time after the report loads to take the "snapshot"; adjust this if your report is incomplete, for example.
- **Delete older reports (days)**: Sets the number of days before a report is deleted (reports take up some space, so be careful not to set the retention period too long).

## Links tab

Allows you to configure link diagrams. These links let you view, in diagram form, the relationships between objects, devices, and other items.

- **Depth for Scenarios**: Allows you to set, when viewing a scenario’s link graph, the maximum number of items to display (the more items there are, the longer it will take to generate the graph and the harder it will be to read).
- **Depth for objects**: Same as for objects.
- **Depth for equipment**: Same applies to equipment.
- **Depth for commands**: Same applies to the commands.
- **Depth for variables**: Same applies to variables.
- **Prerender setting**: Allows you to adjust the layout of the graph.
- **Render settings**: Same as above.

## Interactions Tab

This tab allows you to set global settings for the interactions found under Tools→Interactions.

> **Tip**
>
> To enable interaction logging, go to the Settings tab → System → Configuration: Logs, then check **Debug** in the list at the bottom. Note: The logs will then be very verbose!

### General

Here are three settings:

- **Sensitivity**: There are 4 levels of match (Sensitivity ranges from 1 (exact match) to 99) for
    -   1 word: the match level for single-word interactions.
    -   2 words: the match level for two-word interactions.
    -   3 words: the matching level for three-word interactions.
    -   + 3 words: the match level for interactions with more than three words.
- **Do not respond if the interaction is not understood**: By default, Jeedom responds with "I didn't understand" if no interaction matches. You can disable this behavior so that Jeedom does not respond at all. Check the box to disable the response.
- **General exclusion regex for interactions**: allows you to define a regex that, if it matches an interaction, will automatically remove that phrase from the generated text (for experts only). For more information, see the explanations in the **Exclusion Regex** section of the interactions documentation.

### Automatic, Context-Aware Interaction & Alerts

-   **Automatic interactions** allow Jeedom to attempt to understand an interaction request even if none has been defined. It will then search for the name of an object, device, and/or command to try to respond as best as possible.

-   **Contextual interactions** allow you to chain together multiple requests without having to repeat everything, for example:
    - *Jeedom maintaining context:*
        - *You*: What's the temperature in the bedroom?
        - *Jeedom*: Temperature 25.2 °C
        - *You*: What about the living room?
        - *Jeedom*: Temperature 27.2 °C
    - *Asking two questions in one:*
        - *You*: What's the temperature in the bedroom and the living room?
        - *Jeedom*: Temperature 23.6 °C, Temperature 27.2 °C
-   **Notify Me**-type interactions allow you to ask Jeedom to notify you if a command exceeds, falls below, or equals a certain value.
    - *You*: Let me know if the temperature in the living room goes above 25°C?
    - *Jeedom*: OK (*As soon as the temperature in the living room exceeds 25°C, Jeedom will notify you—just once*)

> **Note**
>
> By default, Jeedom will respond via the same channel you used to ask it to notify you. If it cannot find one, it will use the default command specified in this tab: **Default Response Command**.

Here are the various options available:

- **Enable automatic interactions**: Check this box to enable automatic interactions.
- **Enable Contextual Responses**: Check this box to enable contextual interactions.
- **Prioritize contextual response if the sentence begins with**: If the sentence begins with the word you enter here, Jeedom will prioritize a contextual response (you can enter multiple words by separating them with **;**).
- **Split an interaction into 2 if it contains**: The same applies to splitting an interaction that contains multiple questions. Here, you specify the words that separate the different questions.
- **Enable "Notify Me" interactions**: Check this box to enable **Notify Me** interactions of the type **Notify Me**.
- **"Notify Me" response if the sentence begins with**: If the sentence begins with this word or these words, Jeedom will attempt to trigger an **"Notify Me"** type interaction (you can include multiple words by separating them with **;**).
- **Default command**: Default command for a **Notify Me** type of interaction (used, for example, if you scheduled the alert via the mobile app)
- **Synonyms for objects**: List of synonyms for objects (e.g., ground floor|basement|lower level; bathroom|bathroom).
- **Synonyms for equipment**: List of synonyms for equipment.
- **Synonyms for Commands**: List of synonyms for commands.
- **Synonyms for summaries**: List of synonyms for summaries.
- **Synonym for "set slider command to maximum"**: Synonym for setting a slider command to its maximum value (e.g., "open" to open the bedroom shutter ⇒ bedroom shutter at 100%).
- **Synonym for "set slider command to minimum"**: A synonym for setting a slider command to its minimum value (e.g., move the slider all the way to the right to close the bedroom shutter ⇒ bedroom shutter at 0%).

## Security tab

### LDAP

- **Enable LDAP Authentication**: Enables authentication through an Active Directory (LDAP) server.
- **Host**: server hosting the AD.
- **Domain**: Your AD domain.
- **Base DN**: the base DN of your Active Directory.
- **Username**: the username Jeedom uses to connect to the AD.
- **Password**: the password Jeedom uses to connect to the AD.
- **User search fields**: Search fields for the user login. Typically, `uid` for LDAP and `SamAccountName` for Windows AD.
- **Administrator filter (optional)**: administrator filter in Active Directory (for managing groups, for example)
- **User filter (optional)**: user filter on the AD (for managing groups, for example)
- **Restricted Users Filter (optional)**: filters restricted users on the AD (for managing groups, for example)
- **Allow REMOTE\_USER**: Makes REMOTE\_USER active (used for SSO, for example).

### Log In

- **Number of allowed failures**: specifies the number of consecutive attempts allowed before the IP address is blocked
- **Maximum time between failures (in seconds)**: the maximum time interval between two attempts for them to be considered consecutive
- **Ban duration (in seconds), -1 for infinite**: IP ban duration
- **"White" IP**: list of IP addresses that can never be banned
- **Delete banned IPs**: Clears the list of currently banned IPs

The list of banned IP addresses is at the bottom of this page. There you will find the IP address, the date the ban was imposed, and the scheduled end date of the ban.

## Update/Market tab

### Jeedom Update

- **Update Source**: Select the update source for the Jeedom core.
- **Core version**: Core version to download.
- **Automatically check for updates**: Specifies whether to automatically check for new updates (note: to avoid overloading the Market, the check time may change).

### Repositories

Repositories are storage (and service) spaces where you can move backups, retrieve plugins, retrieve the Jeedom core, etc.

### File

A repository used to enable the delivery of plugins via files.

#### GitHub

Repository used to connect Jeedom to GitHub.

- **Token**: Token for access to the private repository.
- **User or organization for the Jeedom core repository**: The name of the user or organization on GitHub for the core.
- **Jeedom Core Repository Name**: Name of the repository for the core.
- **Branch for the Jeedom core**: Branch of the repository for the core.

#### Market

This repository is used to connect Jeedom to the Market; we strongly recommend using this repository. Please note: any support request may be denied if you use a repository other than this one.

- **Address**: Market address. (https://market.jeedom.com).
- **Username**: Your username on the Market.
- **Password**: Your Market password.
- **[Cloud Backup] Name**: Name of your cloud backup (note: this must be unique for each Jeedom instance, otherwise they may overwrite each other).
- **[Cloud Backup] Password**: Cloud backup password. IMPORTANT: You must not lose this password under any circumstances; there is no way to recover it. Without it, you will no longer be able to perform Restoration on your Jeedom.
- **[Cloud Backup] Full Backup Frequency**: Frequency of full cloud backups. A full backup takes longer than an incremental backup (which only sends the changes). It is recommended to perform one per month.

#### Samba

A repository that automatically sends a Jeedom backup to a Samba share (e.g., Synology NAS).

- **\[Backup\] IP**: IP address of the Samba server.
- **\[Backup\] User**: Username for logging in (anonymous logins are not allowed). The user must have both read AND write permissions on the destination directory.
- **\[Backup\] Password**: User password (note: special characters are not allowed).
- **\[Backup\] Share**: Path to the share (be sure to stop at the share level).
- **\[Backup\] Path**: Path within the share (must be relative); this path must exist.

> **Note**
>
> If the path to your Samba backup folder is:
> \\\\192.168.0.1\\Backups\\Home Automation\\Jeedom So IP = 192.168.0.1, Share = //192.168.0.1/Backups, Path = Home Automation/Jeedom

> **Note**
>
> When you validate the Samba share, as described above, a new backup option appears in the Settings→System→Backups section of Jeedom. When you enable it, Jeedom will automatically send the backup during the next scheduled backup. You can test this by performing a manual backup.

> **Important**
>
> You may need to install the smbclient package for the repository to work.

> **Important**
>
> The Samba protocol has several versions; the security of version 1 is compromised, and on some NAS devices, you can force the client to use version 2 or 3 to connect. So if you get an error saying *protocol negotiation failed: NT_STATUS_INVALID_NETWORK_RESPONSE*, there’s a good chance that this restriction is in place on the NAS side. In that case, you’ll need to edit the /etc/samba/smb.conf file on your Jeedom’s OS and add these two lines:
> client max protocol = SMB3
> client min protocol = SMB2
> The smbclient on the Jeedom side will then use v2 or v3, and if you set SMB3 to "2," it will use SMB3 only. It’s up to you to adjust this based on any restrictions on the NAS or other Samba server.

> **Important**
>
> Jeedom must be the only program allowed to write to this folder, and it must be empty by default (that is, before configuration and sending the first backup, the folder must not contain any files or folders).

#### URL

- **Jeedom Core URL**
- **Jeedom Core Version URL**

## Cache tab

Allows you to monitor and control the Jeedom cache:

- **Cache Engine**: Choosing a cache engine for Jeedom:
  - File system: Cache information is stored in /tmp/jeedom/cache (in RAM) in file mode, using a third-party library. It will soon be replaced by File (beta).
  - File (beta): Stores cache information in /tmp/jeedom/cache (in RAM) in file mode. This is the most efficient option but is backed up every 30 minutes.
  - MySQL (beta): Use of a cache table in the database. Least efficient but saved in real time (no data loss possible)
  - Redis (beta): For experts only; relies on Redis to manage the cache (so you'll need to install Redis and the php-redis dependencies yourself)
- **Clear the cache**: Forces the removal of objects that are no longer needed. Jeedom does this automatically every night.
- **Clear all cached data**: Completely clears the cache.
Warning: This may result in data loss!
- **Long polling pause time**: The frequency at which Jeedom checks for pending events for clients (web interface, mobile app, etc.). The shorter this time, the faster the interface will update; however, this uses more resources and can therefore slow down Jeedom.

>**IMPORTANT**
>
> Any change to the cache engine causes it to reset, so you must wait for the modules to resend the information to restore everything.

## API tab

Here you'll find a list of the various API keys available in your Jeedom. By default, the core has two API keys:

- General rule: Avoid using it whenever possible,
- and another one for professionals: used for fleet management. It may be empty.
- You'll then find an API key for each plugin as needed.

For each plugin API key, as well as for the HTTP, JSON-RPC, and TTS APIs, you can define their scope:

- **Disabled**: The API key cannot be used,
- **White-listed IPs**: Only a list of IP addresses is allowed (see Settings→System→Configuration: Security),
- **Localhost**: Only requests originating from the system on which Jeedom is installed are allowed,
- **Enabled**: No restrictions; any system with access to your Jeedom will be able to access this API.

For each plugin API key, you can disable the core (general) methods to limit them to their own built-in method only (note that some plugins, such as Mobile or Jeelink, absolutely require the core methods).

## Tab &gt;\_OS/DB

> **Important**
>
> This tab is for experts only.
> If you modify Jeedom using either of these two solutions, support may refuse to assist you.

### System Checks

- **General Check**: Allows you to run a consistency test on Jeedom.
- **Restore Permissions**: Restores the correct permissions on the Jeedom Core directories and files.
- **System Package Check**: Allows you to run a check on the installed packages.
- **Database Check**: Allows you to run a check on the Jeedom database and correct any errors if necessary.
- **Database cleanup**: Runs a database check and removes any unused entries.


### System Tools

- **File Editor**: Allows you to access various operating system files and edit, delete, or create them.
- **System Administration**: Provides access to a system administration interface. It is a type of shell console where you can run the most useful commands, particularly to retrieve system information.
- **Bulk Editor**: A tool for bulk editing devices, commands, objects, and scenarios.
- **Database Administration**: Allows you to access the Jeedom database. You can then enter commands in the top field.
- **Username / Password**: The username and password used by Jeedom to access the database.
