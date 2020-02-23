Description 
===========

The **update center** allows you to update all
Jeedom features, including core software,
plugins, widgets, etc.. Other extension management functions
are available (delete, reinstall, check, etc.)

The Update Center page 
================================

It is accessible from the menu **Settings → System → Update Center
day** and consist of 3 tabs and a top part.

Functions at the top of the page. 
---------------------------------

At the top of the page, independent of the tab, are the control buttons. 
Jeedom periodically connects to the Market to see if there are any updates
are available (the date of the last check is indicated at the top
on the left of the page). If you want to do a manual check,
you can press the button &quot;Check for updates&quot;.

The button **Update** allows to update the set of
Jeedom. Once you click on it, we get these different
options :
-   **Pre-update** : Allows you to update the update script before
    applications of new updates.

-   **Save before** : Back up Jeedom before
    perform the update.

-   **Update plugins** : Allows you to include plugins in the
    update.

-   **Update the core** : Allows you to include the Jeedom kernel in
    the update.

-   **Forced mode** : Update in forced mode, i.e.
    that, even if there is an error, Jeedom continues and will not restore
    the backup. (This mode disables saving!)

-   **Update to reapply** : Allows you to reapply a bet
    up to date. (NB : Not all updates can be reapplied.)

> **Important**
>
> Before an update, by default, Jeedom will make a backup. In
> if there is a problem when applying an update, Jeedom will
> automatically restore the backup made just before. This principle
> is only valid for Jeedom updates and not plugins.

> **Tip**
>
> You can force an update of Jeedom, even if it does not
> don&#39;t offer any.

Core and Plugins tabs and the Others tab
------------------------------------------

These two similar tabs, consist of a table :

-   **Core and Plugins** : Contains the basic Jeedom software (core) and the
    list of installed plugins.

-   **Other** : Contains widgets, scripts, etc..

You will find the following information : \* **Status** : OK or NOK.
Allows to know the current state of the plugin. \* **Last name** : You there
find the source of the element, the type of element and its name. \*
**Version** : Indicates the specific version of the item. \* **options** :
Check this box if you do not want this item to be updated.
day during the general update (Button **Update**).

> **Tip**
>
> For each table, the first line allows the following filter
> the name of the elements present.

On each line, you can use the following functions to
every element :

-   **reinstate** : Force resettlement.

-   **Remove** : Allows you to uninstall it.

-   **Check** : Query source for updates to find out if
    a new update is available.

-   **Update** : Allows you to update the element (if it has
    an update).

-   **Changelog** : Access the list of changes in the
    update.

> **Important**
>
> If the changelog is empty but you still have an update
> update means that the documentation has been updated.
> There is therefore no need to ask the developer for
> changes, since there are not necessarily any. (it is often a bet
> translation, documentation)

> **Tip**
>
> Note that &quot;core : jeedom &quot;means&quot; updating the software
> Jeedom base &quot;.

Logs tab
-----------

Tab to which you are automatically switched to when installing
update, it allows you to follow everything that happens during the update
up to date with core, like plugins.


Command line update 
================================

It is possible to update Jeedom directly in SSH.
Once connected, this is the command to perform :

    sudo php /var/www/html/install/update.php

The possible parameters are :

-   **`mode`** : `force`, pour lancer une update en mode forcé (ne
    ignores errors).

-   **`version`** : followed by the version number, to reapply the
    changes since this version.

Here is an example of syntax to do a forced update in
reapplying changes since 3.2.14 :

    sudo php / var / www / html / install / update.php mode = force version = 3.2.14

Attention, after an update on command line, it is necessary
reapply rights on Jeedom folder :

    chown -R www-data:www-data / var / www / html
