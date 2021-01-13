# Plugins management
**Plugins → Plugins management**

This page provides access to plugin configurations.
You can also manipulate the plugins, namely : download, update and activate them,…

There is therefore a list of plugins in alphabetical order and a link to the market.
- Disabled plugins are grayed out.
- Plugins that are not in version *stable* we have an orange dot in front of their name.

By clicking on a plugin, you access its configuration. At the top, you find the name of the plugin, then in parentheses, its name in Jeedom (ID) and finally, the type of installed version (stable, beta).

> **Important**
>
> When downloading a plugin, it is disabled by default. So you have to activate it by yourself.

## Gestion

Here you have three buttons :

- **Synchronize Market** : If you install a plugin from a web browser on your Market account (apart from Jeedom), you can force a synchronization to install it.
- **Market** : Open the Jeedom Market, to select a plugin and install it on your Jeedom.
- **Plugins** : You can install a plugin here from a Github, Samba source, ...

### Synchronize Market

From a browser, go to the [Market](https://market.jeedom.com).
Sign into your account.
Click on a plugin, then choose *Install stable* or *Install beta* (if your Market account allows it).

If your Market account is correctly configured on your Jeedom (Configuration → Updates / Market → Market tab), you can click on *Synchronize Market* or wait for it to settle down on its own.

### Market

To install a new plugin, just click on the "Market" button (and Jeedom is connected to the Internet). After a short loading time, you will get the page.

> **Tip**
>
> You must have entered your Market account information in the administration (Configuration → Updates / Market → Market tab) in order to find the plugins that you have already purchased for example.

At the top of the window you have filters :
- **Free / Pay** : displays only free or paid.
- **Official / Recommended** : displays only official or recommended plugins.
- **Category drop-down menu** : displays only certain categories of plugins.
- **Search** : allows you to search for a plugin (in the name or description of it).
- **Username** : displays the user name used to connect to the Market as well as the connection status.

> **Tip**
>
> The small cross resets the filter concerned

Once you have found the plugin you want, just click on it to bring up its file. This sheet gives you a lot of information on the plugin, in particular :

- If it is official / recommended or if it is obsolete (you should definitely avoid installing obsolete plugins).
- 4 actions :
    - **Install stable** : allows to install the plugin in its stable version.
    - **Install beta** : allows to install the plugin in its beta version (only for betatesters).
    - **Install pro** : allows to install the pro version (very little used).
    - **Remove** : if the plugin is currently installed, this button allows you to remove it.

Below, you will find the description of the plugin, the compatibility (if Jeedom detects an incompatibility, it will notify you), the opinions on the plugin (you can note it here) and additional information (the author, the person who made the latest update, a link to the doc, the number of downloads). On the right you find a &quot;Changelog&quot; button which allows you to have all the history of modifications, a &quot;Documentation&quot; button which refers to the documentation of the plugin. Then you have the available language and the various information on the date of the last stable version.

> **Important**
>
> It is really not recommended to put a beta plugin on a non beta Jeedom, a lot of operational problems can result.

> **Important**
>
> Some plugins are chargeable, in this case the plugin will offer you to buy it. Once done, you have to wait about ten minutes (payment validation time), then return to the plugin file to install it normally.

### Plugins

You can add a plugin to Jeedom from a file or from a Github repository. To do this, you have to activate the appropriate function in the Jeedom configuration in the "Updates / Market" section".

Attention, in the case of adding by a zip file, the name of the zip must be the same as the ID of the plugin and upon opening the ZIP a plugin\_info folder must be present.



## My plugins

By clicking on the icon of a plugin, you open its configuration page.

> **Tip**
>
> You can Ctrl-Click or Click Center to open its configuration in a new browser tab.

### At the top right, some buttons :

- **Documentation** : Allows direct access to the plugin documentation page.
- **Changelog** : Lets see the plugin changelog if it exists.
- **Details** : Allows you to find the plugin page on the market.
- **Remove** : Remove the plugin from your Jeedom. Please note, this also permanently removes all equipment from this plugin.

### Below left, there is an area **state** with :

- **Status** : Allows you to see the status of the plugin (active / inactive).
- **Category** : The category of the plugin, indicating in which sub-menu to find it.
- **Author** : The author of the plugin, link to the market and the plugins of this author.
- **Licence** : Indicates the license of the plugin which will generally be AGPL.

- **Action** : Allows you to enable or disable the plugin. The button **To open** Allows you to go directly to the plugin page.
- **Version** : The version of the plugin installed.
- **Prerequisites** : Indicates the minimum Jeedom version required for the plugin.


### On the right, we find the area **Log and monitoring** which allows to define :

- The level of logs specific to the plugin (we find this same possibility in Administration → Configuration on the logs tab, at the bottom of the page).
- View plugin logs.
- Heartbeat : Every 5 mins, Jeedom checks if at least one plugin device has communicated in the last X minutes (if you want to deactivate the functionality, just put 0).
- Restart demon : If the hertbeat goes wrong then Jeedom will restart the daemon.

If the plugin has dependencies and / or a daemon, these additional areas are displayed below the areas mentioned above.

### Dependencies :

- **Last name** : Generally will be local.
- **Status** : Dependency status, OK or NOK.
- **Installation** : Allows to install or reinstall dependencies (if you do not do it manually and they are NOK, Jeedom will take care of itself after a while).
- **Last installation** : Date of last dependency installation.

### Devil :

- **Last name** : Generally will be local.
- **Status** : Daemon status, OK or NOK.
- **Configuration** : OK if all the criteria for the demon to run are met, or gives the cause of the blocking.
- **(To restart** : Allows you to launch or restart the demon.
- **Stop** : Used to stop the daemon (Only in the case where automatic management is disabled).
- **Automatic management** : Enables or disables automatic management (which allows Jeedom to manage the daemon itself and restart it if necessary. Unless otherwise indicated, it is advisable to leave automatic management active).
- **Last launch** : Date of last launch of the daemon.

> **Tip**
>
> Some plugins have a configuration part. If this is the case, it will appear under the dependency and daemon zones described above.
> In this case, refer to the documentation of the plugin in question to know how to configure it.

### Below, there is a functionality area. This allows you to see if the plugin uses one of the Jeedom core functions such as :

- **Interact** : Specific interactions.
- **Cron** : One cron a minute.
- **Cron5** : One cron every 5 minutes.
- **Cron10** : One cron every 10 minutes.
- **Cron15** : One cron every 15 minutes.
- **Cron30** : One cron every 30 minutes.
- **CronHourly** : One cron every hour.
- **CronDaily** : A daily cron.
- **deadcmd** : A cron for dead commanders.
- **health** : A cron health.

> **Tip**
>
> If the plugin uses one of these functions, you can specifically prohibit it from doing so by unchecking the &quot;activate&quot; box which will be present next to it.

### Panel

We can find a Panel section which will enable or disable the display of the panel on the dashboard or mobile if the plugin offers one.


