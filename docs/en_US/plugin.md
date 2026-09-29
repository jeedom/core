# Plugin Management
**Plugins → Plugin Management**

This page provides access to the plugin settings.
You can also manage plugins—namely, download, update, and activate them, …​

Here you'll find a list of plugins in alphabetical order and a link to the marketplace.
- Disabled plugins are grayed out.
- Plugins that are not in the *stable* version have an orange dot next to their name.

Clicking on a plugin takes you to its configuration. At the top, you’ll see the plugin’s name, followed by its Jeedom name (ID) in parentheses, and finally, the version type (stable, beta).

> **Important**
>
> When you download a plugin, it is disabled by default. You'll need to enable it yourself.

## Management

Here are three buttons:

- **Synchronize Market**: If you install a plugin from a web browser on your Market account (outside of Jeedom), you can force a synchronization to install it.
- **Market**: Opens the Jeedom Market, where you can select a plugin and install it on your Jeedom.
- **Plugins**: Here you can install a plugin from a GitHub, Samba, or other source.

### Synchronize Market

Using a web browser, go to the [Market](https://market.jeedom.com).
Log in to your account.
Click on a plugin, then choose *Install stable* or *Install beta* (if your Market account allows it).

If your Market account is properly configured on your Jeedom (Configuration→Updates/Market→Market tab), you can click *Synchronize Market* or wait for it to install automatically.

### Market

To install a new plugin, simply click the "Market" button (and make sure Jeedom is connected to the Internet). After a short loading time, the page will appear.

> **Tip**
>
> You must have entered your Market account information in the admin panel (Configuration → Updates/Market → Market tab) in order to find the plugins you have already purchased, for example.

At the top of the window, you'll find filters:
- **Free/Paid**: displays only free or paid options.
- **Official/Recommended**: Displays only official or recommended plugins.
- **Category drop-down menu**: allows you to display only certain categories of plugins.
- **Search**: allows you to search for a plugin (by name or description).
- **Username**: Displays the username used to log in to the Market, as well as the connection status.

> **Tip**
>
> The small cross icon allows you to reset the filter in question

Once you’ve found the plugin you’re looking for, simply click on it to view its details page. This page provides a wealth of information about the plugin, including:

- Whether it is official/recommended or obsolete (it is true that you should definitely avoid installing obsolete plugins).
- 4 actions:
    - **Install stable**: installs the plugin in its stable version.
    - **Install beta**: allows you to install the beta version of the plugin (for beta testers only).
    - **Install Pro**: allows you to install the Pro version (rarely used).
    - **Uninstall**: If the plugin is currently installed, this button allows you to uninstall it.

Below, you’ll find a description of the plugin, compatibility information (if Jeedom detects an incompatibility, it will notify you), reviews of the plugin (you can rate it here), and additional information (the author, the person who made the last update, a link to the documentation, and the number of downloads). On the right, you’ll find a “Changelog” button that lets you view the full history of changes, and a “Documentation” button that links to the plugin’s documentation. Next, you’ll see the available languages and various details about the release date of the latest stable version.

> **Important**
>
> It is truly not recommended to install a beta plugin on a non-beta Jeedom; doing so can result in many operational issues.

> **Important**
>
> Some plugins are paid; in this case, the plugin’s page will offer you the option to purchase it. Once you’ve done that, you’ll need to wait about ten minutes (for the payment to be processed), then return to the plugin’s page to install it as usual.

### Plugins

You can add a plugin to Jeedom from a file or from a GitHub repository. To do this, you must enable the appropriate feature in the "Updates/Market" section of the Jeedom configuration.

Please note: When adding a plugin via a ZIP file, the ZIP file name must match the plugin ID, and a folder named `plugin\_info` must be present inside the ZIP file.

## My plugins

Clicking on a plugin's icon opens its configuration page.

> **Tip**
>
> You can Ctrl-click or middle-click to open its settings in a new browser tab.

### In the upper right corner, there are a few buttons:

- **Details**: Takes you to the plugin's page on the marketplace.
- **Documentation**: Provides direct access to the plugin's documentation page.
- **Changelog**: Allows you to view the plugin's Changelog, if available.
- **Support**: Allows you to automatically create a support request on the forum.
- **Delete**: Deletes the plugin from your Jeedom. Please note that this will also permanently delete all devices associated with this plugin.

### At the bottom left, there is a **status** section that includes:

- **Status**: Allows you to view the plugin's status (active/inactive).
- **Category**: The plugin's category, indicating which submenu it can be found in.
- **Author**: The plugin's author, link to the marketplace, and other plugins by this author.
- **License**: Specifies the plugin's license, which is typically AGPL.

- **Action**: Enables or disables the plugin. The **Open** button takes you directly to the plugin's page.
- **Version**: The version of the plugin that is installed.
- **Prerequisites**: Specifies the minimum Jeedom version required for the plugin.


### On the right is the **Log and Monitoring** section, which allows you to configure:

- The plugin-specific log level (this same option is available in Administration → Configuration on the Logs tab, at the bottom of the page).
- View the plugin logs.
- Heartbeat: Every 5 minutes, Jeedom checks to see if at least one device from the plugin has communicated in the last X minutes (if you want to disable this feature, simply set the value to 0).
- Restart daemon: If the heartbeat fails, Jeedom will restart the daemon.

If the plugin has dependencies and/or a daemon, these additional fields appear below the fields listed above.

### Dependencies:

- **Name**: Will generally be local.
- **Status**: Dependency status, OK or NOK.
- **Installation**: Allows you to install or reinstall dependencies (if you don’t do this manually and they are missing, Jeedom will take care of it on its own after a while).
- **Last installation**: Date of the last installation of the dependencies.

### Demon:

- **Name**: Will generally be local.
- **Status**: Daemon status, OK or NOK.
- **Configuration**: Returns "OK" if all the criteria for the daemon to run are met, or specifies the cause of the failure.
- **(Re)Start**: Starts or restarts the daemon.
- **Stop**: Stops the daemon (only if automatic management is disabled).
- **Automatic Management**: Allows you to enable or disable automatic management (which lets Jeedom manage the daemon itself and restart it if necessary. Unless otherwise specified, it is recommended to keep automatic management active).
- **Last launch**: Date of the daemon's last launch.

> **Tip**
>
> Some plugins have a configuration section. If so, it will appear below the dependencies and daemon sections described above.
> In this case, refer to the documentation for the plugin in question to learn how to configure it.

### Below that is a "Features" section. This section lets you see if the plugin uses any of Jeedom's core features, such as:

- **Interact**: Specific interactions.
- **Cron**: A cron job every minute.
- **Cron5**: A cron job every 5 minutes.
- **Cron10**: A cron job every 10 minutes.
- **Cron15**: A cron job every 15 minutes.
- **Cron30**: A cron job every 30 minutes.
- **CronHourly**: A cron job every hour.
- **CronDaily**: A daily cron job.
- **deadcmd**: A cron job for dead commands.
- **health**: A cron job for health.

> **Tip**
>
> If the plugin uses one of these functions, you can specifically prevent it from doing so by unchecking the "Enable" box next to it.

### Panel

There is a "Panel" section that allows you to enable or disable the panel display on the dashboard or on mobile devices, if the plugin offers this feature.
