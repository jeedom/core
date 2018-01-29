The plugin management sub-menu allows you to manipulate plugins,
know how to download, update and activate them, ...

Plugins management
===================

You can access the plugins page from Plugins → Manage
plugins. Once we click on it, we find the list of
plugins in alphabetical order and a link to the market. Plugins
disabled are grayed out.

> **Tip**
>
> As in many places on Jeedom, put the mouse on the far left
> allows you to display a quick access menu (you can
> from your profile always leave it visible). Here the menu
> allows to have the list of plugins sorted by categories.

By clicking on a plugin you access its configuration. At the top you
find the name of the plugin, then parentheses its name in Jeedom
(ID) and finally the type of version installed (stable, beta).

> **Important**
>
> When downloading a plugin this one is deactivated by default.
> So you have to activate it on your own.

At the top right, some buttons:

-   **Documentation**: Allows direct access to the page of
    plugin documentation

-   **Changelog**: allows to see the changelog of the plugin if it exists

-   **Send on the Market**: allows to send the plugin on the Market
    (only available if you are the author)

-   **Details**: allows to find the plugin page on the market

-   **Delete**: Removes the plugin from your Jeedom. Attention this
    also permanently removes all the equipment from this plugin

Below left is a state area with:

-   **Status**: allows to see the status of the plugin (active / inactive)

-   **Version**: the version of the installed plugin

-   **Author**: the author of the plugin

-   **Action**: Enable or disable the plugin

-   **Jeedom version**: Indicates the minimum Jeedom version required
    for the plugin

-   **License**: Indicates the license of the plugin that will usually be
    AGPL

On the right we find the Log zone which allows to define the log level
specific to the plugin (we find this same possibility in
Administation → Configuration on the logs tab, at the bottom of the page).

If the plugin has dependencies and / or a daemon these zones
additional items are displayed under the areas listed above.

Dependencies:

-   **Name**: usually will be local

-   **Status**: will tell you if the dependencies are OK or KO

-   **Installation**: will install or reinstall the
    dependencies (if you do not do it manually and they are
    KO, Jeedom will do it himself after a while)

-   **Last installation**: date of last installation of
    outbuildings

Demon:

-   **Name**: usually will be local

-   **Status**: will tell you if the daemon is OK or KO

-   **Configuration**: will be OK if all the criteria for the daemon
    turn together or give the cause of blocking

-   **(Re) Start**: start or restart the daemon

-   **Stop**: allows to stop the demon (Only in case
    automatic management is disabled)

-   **Automatic management**: allows to activate or deactivate the management
    automatically (which allows Jeedom to manage the daemon and the
    restart if necessary, unless against indication it is advisable to
    leave automatic management active)

-   **Last launch**: date of last demon launch

> **Tip**
>
> Some plugins have a configuration part. If so, she
> will appear under the dependencies and demon areas described above.
> In this case, it is necessary to refer to the documentation of the plugin in
> question to know how to configure it.

Below we find an area features. This one allows to see
if the plugin uses a Jeedom core function such as:

-   **Interact**: specific interactions

-   **Cron**: a cron per minute

-   **Cron5**: one cron every 5 minutes

-   **Cron15**: every 15 minutes

-   **Cron30**: every 30 minutes

-   **CronHourly**: every hour

-   **CronDaily**: every day

> **Tip**
>
> If the plugin uses one of these functions. You will specifically
> forbid him to do so by unchecking the box "activate" which will be
> present next.

Finally we can find a Panel section that will allow to activate or
disable panel display on the dashboard or mobile if the
plugin offers one.

Installing a plugin
========================

To install a new plugin just click on the button
"Market" (and that Jeedom is connected to the Internet). After a short time
loading you will get the page.

> **Tip**
>
> You must have entered the information of your account of the market in
> administration (Configuration → Updates → Market tab) in order to
> find the plugins you have already bought for example.

At the top of the window you have filters:

-   **Free / Paid**: allows to display only the free or
    the paying ones.

-   **Official / Advisor**: only show plugins
    officials or advisers

-   **Installed / Uninstalled**: only show plugins
    installed or not installed

-   **Category** drop-down menu: displays only
    some categories of plugins

-   **Search**: allows to search for a plugin (in the name or the
    description of it)

-   **Username**: Displays the username used for the
    connection to the Market as well as the status of the connection

> **Tip**
>
> The small cross resets the filter concerned

Once you have found the plugin you want, just click on
this one to make appear his file. This card gives you a lot
information about the plugin, including:

-   If it is official / recommended or is it obsolete (you really need
    avoid installing obsolete plugins)

-   4 actions:

    -   **Install stable**: allows to install the plugin in its
        stable version

    -   **Install beta**: allows to install the plugin in its
        beta version (only for betatestors)

    -   **Install pro**: allows to install the pro version (very
        little used)

    -   **Delete**: If the plugin is currently installed, this
        button allows to delete it

Below, you will find the description of the plugin, the compatibility
(if Jeedom detects an incompatibility, it will signal it to you), the opinions
on the plugin (here you can note it) and information
(the author, the person who made the last
day, a link to the doc, the number of downloads). On the right
you find a button "Changelog" that allows you to have everything
change history, a "Documentation" button that returns
to the plugin documentation. Then you have the language available
and the various information on the date of the latest stable release.

> **Important**
>
> It is not really recommended to put a beta plugin on a
> Jeedom non beta, a lot of worries of operation can in
> result.

> **Important**
>
> Some plugins are paying, in this case the plugin plug you
> will offer to buy it, once done it is necessary to wait for a
> ten minutes (payment validation time), then return
> on the plugin plug to install it normally.

> **Tip**
>
> You can also add a plugin to Jeedom from a file or
> from a Github repository. For this, it is necessary, in the configuration of
> Jeedom, activate the appropriate function in the section "Updates and
> files. "It will then be possible, by putting the mouse all to
> left, and by displaying the menu of the plugin page, click
> on "Add from another source". You can then choose the
> source "File". Attention, in the case of adding by a file
> zip, the name of the zip must be the same as the plugin ID and as soon as
> Opening the ZIP a plugin \ _info folder must be present.
