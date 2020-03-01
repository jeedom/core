The Plugins management submenu allows you to manipulate plugins, except
know : download, update and activate them, etc.

Plugins management 
===================

You can access the plugins page from Plugins → Manage
plugins. Once we click on it, we find the list of
plugins in alphabetical order and a link to the market. Plugins
disabled are grayed out.

> **Tip**
>
> As in many places on Jeedom, put the mouse on the far left
> brings up a quick access menu (you can
> from your profile always leave it visible). Here, the menu
> allows to have the list of plugins sorted by categories.

By clicking on a plugin, you access its configuration. Upstairs you
find the name of the plugin, then in brackets, its name in Jeedom
(ID) and finally, the type of version installed (stable, beta).

> **IMPORTANT**
>
> When downloading a plugin, it is disabled by default.
> So you have to activate it by yourself.

At the top right, some buttons :

-   **Documentation** : Allows direct access to the page of
    plugin documentation

-   **Changelog** : allows to see the changelog of the plugin if it exists

-   **Send to the Market** : allows to send the plugin on the Market
    (only available if you are the author)

-   **Details** : allows to find the plugin page on the market

-   **Remove** : Remove the plugin from your Jeedom. Be careful, this
    also permanently removes all equipment from this plugin

Below left, there is a status area with :

-   **Status** : allows to see the status of the plugin (active / inactive)

-   **Version** : the version of the plugin installed

-   **ACTION** : Allows you to enable or disable the plugin

-   **Jeedom version** : Minimum Jeedom version required
    for the operation of the plugin

-   **Licence** : Indicates the license of the plugin which will generally be
    AGPL

On the right, we find the Log and surveillance zone which allows to define 

-   the level of logs specific to the plugin (we find this same possibility in
Administration → D'actualité on the logs tab, at the bottom of the page)

-   see the plugin logs

-   Heartbeat : every 5 mins, Jeedom checks if at least one plugin device has communicated in the last X minutes (if you want to deactivate the functionality, just put 0)

-   Restart demon : if the Heartbeat goes wrong then Jeedom will restart the daemon

If the plugin has dependencies and / or a daemon, these areas
additional are displayed under the areas mentioned above.

Dependencies :

-   **Last name** : generally will be local

-   **Status** : will tell you if the dependencies are OK or KO

-   **Setup** : will install or reinstall
    dependencies (if you don&#39;t do it manually and they are
    KO, Jeedom will take care of itself after a while)

-   **Last installation** : date of last installation of
    Dependencies

Devil :

-   **Last name** : generally will be local

-   **Status** : will tell you if the demon is OK or KO

-   **D'actualité** : will be OK if all the criteria for the demon
    turns are met or will give cause for blocking

-   **(To restart** : allows to launch or relaunch the demon

-   **Stop** : allows to stop the demon (Only in case
    automatic management is disabled)

-   **Automatic management** : allows to activate or deactivate the management
    automatic (which allows Jeedom to manage the daemon and the
    revive if necessary. Unless otherwise indicated, it is advisable to
    leave automatic management active)

-   **Last launch** : date of the last launch of the demon

> **Tip**
>
> Some plugins have a configuration part. If so, it
> will appear under the dependencies and daemon zones described above.
> In this case, refer to the plugin documentation in
> question for how to configure it.

Below, there is a functionality area. This allows you to see
if the plugin uses one of the Jeedom core functions such as :

-   **Interact** : specific interactions

-   **Cron** : one cron per minute

-   **Cron5** : one cron every 5 minutes

-   **Cron15** : one cron every 15 minutes

-   **Cron30** : one cron every 30 minutes

-   **CronHourly** : one cron every hour

-   **CronDaily** : a daily cron

> **Tip**
>
> If the plugin uses one of these functions, you can specifically
> forbid him to do so by unchecking the &quot;activate&quot; box which will be
> present next.

Finally, we can find a Panel section which will activate or
deactivate the display of the panel on the dashboard or in mobile if the
plugin offers one.

Plugin installation 
========================

To install a new plugin, just click on the button
"Market "(and that Jeedom is connected to the Internet). After a short time of
loading you will get the page.

> **Tip**
>
> You must have entered your Market account information in
> administration (D'actualité → Updates → Market tab) in order to
> find the plugins you have already purchased for example.

At the top of the window you have filters :

-   **Free / Pay** : displays only free or
    the paying ones.

-   **Official / Recommended** : displays only plugins
    officials or advisers

-   **Installed / Not installed** : displays only plugins
    installed or not installed

-   **Category drop-down menu** : displays only
    certain plugin categories

-   **Search** : allows you to search for a plugin (in the name or
    description of it)

-   **Username** : displays the username used for the
    connection to the Market and the status of the connection

> **Tip**
>
> The small cross resets the filter concerned

Once you have found the plugin you want, just click on
this one to bring up his card. This sheet gives you a lot
information about the plugin, including :

-   If it is official / recommended or if it is obsolete (you really need to
    avoid installing obsolete plugins)

-   4 actions :

    -   **Install stable** : allows to install the plugin in its
        stable version

    -   **Install beta** : allows to install the plugin in its
        beta version (only for beta testers)

    -   **Install pro** : allows to install the pro version (very
        little used)

    -   **Remove** : if the plugin is currently installed, this
        button to delete it

Below, you will find the description of the plugin, the compatibility
(if Jeedom detects an incompatibility, it will notify you), the reviews
on the plugin (you can rate it here) and information
complementary (the author, the person who made the last update
day, link to doc, number of downloads). On the right
you will find a &quot;Changelog&quot; button which allows you to have everything
change history, a &quot;Documentation&quot; button which returns
to the plugin documentation. Then you have the language available
and the various information on the date of the last stable version.

> **IMPORTANT**
>
> It is really not recommended to put a beta plugin on a
> Jeedom non beta, many operating problems can
> result.

> **IMPORTANT**
>
> Some plugins are chargeable, in this case the plugin sheet will
> will offer to buy it. Once this is done, wait for a
> ten minutes (payment validation time), then return
> on the plugin sheet to install it normally.

> **Tip**
>
> You can also add a plugin to Jeedom from a file or
> from a Github repository. This requires, in the configuration of
> Jeedom, activate the appropriate function in the &quot;Updates and
> files". It will then be possible, by placing the mouse completely
> left, and bringing up the plugin page menu, click
> on "Add from another source". You can then choose the
> source "File". Attention, in the case of the addition by a file
> zip, the zip name must be the same as the plugin ID and from
> opening the ZIP a plugin \ _info folder must be present.
