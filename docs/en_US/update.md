# Update Center
**Settings → System → Update Center**


The **update center** allows you to update all Jeedom features, including the core software and its plugins.
Other extension management features are available (delete, reinstall, check, etc.).


## Page Features

At the top of the page, regardless of the tab, are the command buttons.

Jeedom periodically checks the Market to see if any updates are available. The date of the last check is displayed in the upper-left corner of the page.

When the page opens, if the last check was more than two hours ago, Jeedom automatically performs another check.
You can also use the **Check for Updates** button to do this manually.
If you want to check for updates manually, you can click the "Check for Updates" button.

Use the **Save** button when you change the options in the table below to specify that certain plugins should not be updated if necessary.

## Update the Core

The **Update** button lets you update the Core, the plugins, or both.
Once you click on it, you'll see these different options:
- **Pre-update**: Allows you to update the update script before applying new updates. Typically used at the request of support.
- **Back up first**: Back up Jeedom before performing the update. The backup is performed locally only (not via Market or Samba).
- **Update plugins**: Includes plugins in the update.
- **Update the Core**: Includes the Jeedom kernel (the Core) in the update.

- **Force Mode**: Performs the update in force mode, meaning that even if an error occurs, Jeedom will continue and will not restore the backup. (This mode disables the backup!).
- **Reapply Update**: Allows you to reapply an update. (Note: Not all updates can be reapplied.)

> **Important**
>
> Before an update, Jeedom will create a backup by default. If a problem occurs while applying an update, Jeedom will automatically perform restoration of the backup created just before the update. This applies only to Jeedom updates, not to plugin updates.

> **Tip**
>
> You can force a Jeedom update, even if it doesn't prompt you to do so.

## Core and Plugins tabs

The table lists the versions of the Core and the installed plugins.

Plugins have a badge next to their name that indicates their version; the badge is green for *stable* or orange for *beta* or other versions.

- **Status**: OK or NOK.
- **Name**: Name and source of the plugin
- **Version**: Indicates the specific version of the Core or plugin.
- **Options**: Check this box if you do not want this plugin to be updated during a global update (the **Update** button).

On each line, you can use the following functions:

- **Reinstall**: Forces a reinstallation.
- **Uninstall**: Allows you to uninstall it.
- **Check**: Queries the update source to see if a new update is available.
- **Update**: Updates the item (if an update is available).
- **Changelog**: Allows you to view the list of changes in the update.

> **Important**
>
> If the Changelog is empty but you still have an update, it means that the documentation has been updated. Therefore, there is no need to ask the developer about the changes, since there may not necessarily be any. (This is often an update to the translation or documentation.)
> In some cases, the plugin developer may also make simple bug fixes that do not necessarily require an update to the Changelog.

> **Tip**
>
> When you start an update, a progress bar appears above the table. Avoid performing any other actions while the update is in progress.

## OS/Package tab

> **IMPORTANT**
>
> This tab is intended for advanced users only—any action here could BRICK your Jeedom (with no option to contact support)

This tab allows you to view available updates for the operating system (apt) and Python packages (pip2 and pip3), as well as update any packages that require updates.

## "Information" tab

During or after an update, this tab allows you to view the update log in real time.

> **Note**
>
> This log normally ends with *[END UPDATE SUCCESS]*. There may be some error lines in this type of log; however, unless there is an actual problem after the update, it is not always necessary to contact support about this.

## Command-line update

You can update Jeedom directly via SSH.
Once you're logged in, here's the command to enter:

```sudo php /var/www/html/install/update.php```

Les paramètres possibles sont :

- **mode** : `force`, pour lancer une mise à jour en mode forcé (ne tient pas compte des erreurs).
- **version** : Suivi du numéro de version, pour ré-appliquer les changements depuis cette version.

Voici un exemple de syntaxe pour faire une mise à jour forcée en ré-appliquant les changements depuis la 4.0.04 :

```sudo php  /var/www/html/install/update.php mode=force version=4.0.04```

Please note: After an update via the command line, you must reapply permissions to the Jeedom folder:

```sudo chown -R www-data:www-data /var/www/html```
