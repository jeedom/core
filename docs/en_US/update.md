Description 
===========

The **update center** allows you to update all
Jeedom features, including core software,
plugins, widgets, etc. Other extension management functions
are available (delete, reinstall, check, etc)

The Update Center page 
================================

It is accessible from the menu **Administration → Updating center
jour**.

You will find, on the left, all the functionalities of
Jeedom and on the right part **Information** who describes what he
happened, when you launched an update.

Functions at the top of the page. 
---------------------------------

At the top of the table are the control buttons. Jeedom se
periodically connect with the Market to see if any updates
are available (the date of the last check is indicated at the top
left of the table). If you want to perform a manual check,
you can press the button "Check for updates".

The button **Update** allows to update the set of
Jeedom. Once you click on it, we get these different
Jeedoms Sources :

-   **Save before** : Back up Jeedom before
    perform the update.

-   **Update plugins** : Allows you to include plugins in the
    update.

-   **Update the core** : Allows you to include the Jeedom kernel in
    the update.

-   **Forced mode** : Update in forced mode, i.e
    that, even if there is an error, Jeedom continues and will not restore
    the backup.

-   **Update to reapply** : Allows you to reapply a bet
    up to date. (NB : All updates cannot be reapplied.)

> **IMPORTANT**
>
> Before an update, by default, Jeedom will make a backup. In
> if there is a problem when applying an update, Jeedom will
> automatically restore the backup made just before. This principle
> is only valid for Jeedom updates and not plugins.

> **Tip**
>
> You can force an update of Jeedom, even if it does not
> don&#39;t offer any.

The update table 
---------------------------

The table consists of two tabs :

-   **Core and Plugins** : Contains basic Jeedom software and
    list of installed plugins.

-   **Other** : Contains widgets, scripts, etc.

You will find the following information : \* **Status** : OK or NOK.
Allows you to know the current state of the plugin. \* **Last name** : You there
find the source of the element, the type of element and its name. \*
**Version** : Indicates the specific version of the item. \* **Jeedoms Sources** :
Check this box if you do not want this item to be updated
day during the general update (Button **Update**).

> **Tip**
>
> For each table, the first line allows the following filter
> the state, name or version of the elements present.

On each line, you can use the following functions to
every element :

-   **Reinstate** : Force resettlement.

-   **Remove** : Allows you to uninstall it.

-   **Check** : Query source for updates to find out if
    a new update is available.

-   **Update** : Allows you to update the element (if it has
    an update).

-   **Changelog** : Access the list of changes in the
    update.

> **IMPORTANT**
>
> If the changelog is empty but you still have an update
> update means that the documentation has been updated.
> There is therefore no need to ask the developer for
> changes, since there are not necessarily any. (it is often a bet
> of the translation of the documentation)

> **Tip**
>
> Note that &quot;core : jeedom &quot;means&quot; updating the software
> Jeedom base".

Command line update 
================================

It is possible to update Jeedom directly in SSH.
Once connected, this is the command to perform :

    sudo php /var/www/html/install/update.php

The possible parameters are :

-   **`fashion`** : `force`, to launch an update in forced mode (do not
    ignore errors).

-   **`version`** : followed by the version number, to reapply the
    changes since this version.

Here is an example of syntax to do a forced update in
reapplying changes since 1.188.0 :

    sudo php / var / www / html / install / update.php mode = force version = 1.188.0

Attention, after an update on command line, it is necessary
reapply rights on Jeedom folder :

    chown -R www-data:www-data / var / www / html
