Description
===========

The **update center** allows you to update all
features of Jeedom, including core software,
plugins, widgets, etc. Other extensions management features
are available (delete, reinstall, check, etc.)

The Update Center page
================================

It can be accessed through the menu ** Administration → Upgrade Center
day**.

On the left, you will find all the features of
Jeedom and on the right a part **Information** that describes what he
happened, when you started an update.

The functions at the top of the page.
---------------------------------

At the top of the table are the command buttons. Jeedom
periodically connects with the Market to see if any updates
are available (the date of last verification is indicated at the top
to the left of the table). If you want to do a manual check,
you can press the "Check Updates" button.

The **Update** button allows you to update the set of
Jeedom. Once you click on it, you get these different
options:

-   **Save before**: Make a Jeedom backup before
    to update.

-   **Update plugins**: Allows to include plugins in the
    update.

-   **Update Core**: Allows you to include the Jeedom kernel in
    the update.

-   **Forced Mode**: Performs the update in forced mode, that is to say
    that, even if there is an error, Jeedom continues and will not restore
    the backup.

-   **Update Reapply**: Reapply a bet
    up to date. (NB: Not all updates can be reapplied.)

> **Important**
>
> Before an update, by default, Jeedom will make a backup. In
> case of concern when applying an update, Jeedom will
> automatically restore the backup made just before. This principle
> is only valid for Jeedom updates and not plugins.

> **Tip**
>
> You can force an update of Jeedom, even if it does not
> do not propose it.

The table of updates
---------------------------

The table consists of two tabs:

-   **Core and Plugins**: Contains Jeedom's core software and the
    list of installed plugins.

-   **Other**: Contains widgets, scripts, etc.

Here you will find the following information: \ * **Status**: OK or NOK.
Lets know the current state of the plugin. \ * **Name**: You are there
find the source of the element, the type of element and its name. \ *
**Version**: Indicates the precise version of the element. \ * **Options**:
Check this box if you do not want this item to be set
day during the general update (Button **Update**).

> **Tip**
>
> For each table, the first line allows filter following
> the state, name or version of the elements present.

On each line, you can use the following functions for
each element:

-   **Reinstall**: Force reinstallation.

-   **Delete**: Allows you to uninstall it.

-   **Check**: Queries the source for updates to see if
    a new update is available.

-   **Update**: Allows you to update the item (if it has
    an update).

-   **Changelog**: Allows access to the list of changes of the
    update.

> **Important**
>
> If the changelog is empty but you still have an update
> day, this means that the documentation has been updated.
> It is therefore not necessary to ask the developer for
> changes, as there are not necessarily. (it's often a bet
> up to date of the translation of the documentation)

> **Tip**
>
> Note that "core: jeedom" means "the update of the software of
> Jeedom base ".

Update command line
================================

It is possible to update Jeedom directly in SSH.
Once connected, here is the command to perform:

    sudo php /var/www/html/install/update.php

The possible parameters are:

-   **`mode`**:` force`, to start an update in forced mode (only
    ignore errors).

-   **`version`**: followed by the version number, to reapply the
    changes since this release.

Here is an example of syntax to make a forced update in
Reapply changes since 1.188.0:

  sudo php /var/www/html/install/update.php mode=force version=1.188.0

Attention, after an update command line, it is necessary
Reapply rights to the Jeedom folder:

    chown -R www-data: www-data / var / www / html
