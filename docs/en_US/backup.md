Jeedom has the ability to be backed up and restored from or from
from different locations.

Configuration
=============

Accessible from **Administration → Backup**, this page allows
backup management.

On the left there are the parameters and the action buttons. On the
right is the real-time status of the current action (backup
or restore), if you have started one.

**Backup**
---------------

-   **Backups**: Allows you to start a backup manually and
    immediately (useful if you want to make a critical change.
    This will allow you to go back). You also have a
    button to launch a backup without sending the archive on the
    cloud (requires a subscription see below). Sending a
    Cloud backup can take a long time. This option
    thus avoids a loss of time too important.

-   **Location of Backups**: Indicates the folder in which
    Jeedom copies the backups. It is recommended not to
    change him. If you are on a relative path, its origin is
    the place where Jeedom is installed.

-   **Number of day (s) of storage of backups**: Number of
    backup days to keep. Once this time has elapsed,
    backups will be deleted. Be careful not to put a number
    of days too high, otherwise your file system may
    to be saturated.

-   **Maximum Total Size of Backups (MB)**: Allows you to limit
    the place taken by all the backups in the folder
    backup. If this value is exceeded, Jeedom will remove the
    older backups to fall below the
    maximum size. It will however keep at least one backup.

**Local Backups**
-----------------------

-   **Available Backups**: List of available backups.

-   **Restore Backup**: Starts Backup Restore
    selected above.

-   **Delete Backup**: Deletes the selected backup
    above, only in the local folder.

-   **Send backup**: Allows to send in the folder of
    backups an archive located on the computer that one
    currently using (allows for example to restore an archive
    previously recovered on a new Jeedom or reinstallation).

-   **Download Backup**: Allows you to download to your
    computer the archive of the backup selected above.

**Market Backups**
----------------------

-   **Send backups**: Tell Jeedom to send them
    backups on the Market's cloud, be careful you have to
    took the subscription.

-   **Send backup**: Allows to send to the cloud a
    backup archive located on your computer.

-   **Available Backups**: Backups List
    cloud available.

-   **Restore Backup**: Starts the restore of a
    cloud backup.

**Samba backups**
---------------------

-   **Send backups**: Tell Jeedom to send them
    backups on the samba share configured here
    Administration → Configuration → Updates Tab.

-   **Available Backups**: Backups List
    samba available.

-   **Restore Backup**: Starts Backup Restore
    samba selected above.

> **Tip**
>
> Depending on what will be activated, in the page
> Administration → Configuration → Updates tab, you can see
> more or fewer sections.

> **Tip**
>
> When reinstalling Jeedom and taking the subscription of
> backup to the cloud of the market, you must fill in your account
> Market on your new Jeedom (Administration → Configuration → Tab
> Updates) then come here to start the restoration.

> **Tip**
>
> It is possible, in case of problems, to make an online backup of
> command: `sudo php / usr / share / nginx / www / jeedom / install / backup.php`
> or `sudo php / var / www / html / install / backup.php` according to your system.

> **Tip**
>
> It is also possible to restore an online backup of
> commands (by default, Jeedom restores the most recent backup
> in the backup directory):
> `sudo php / usr / share / nginx / www / jeedom / install / restore.php` or
> `sudo php / var / www / html / install / restore.php`.

What is backed up?
==============================

During a backup, Jeedom will back up all of its files and the
database. This contains all your configuration
(equipment, controls, history, scenarios, design, etc.).

At the protocol level, only the Z-Wave (OpenZwave) is a bit
different because it is not possible to save the inclusions.
These are directly included in the controller, so you have to
keep the same controller to find its Zwave modules.

> **Note**
>
> The system on which Jeedom is installed is not backed up. Yes
> you have modified parameters of this system (notably via SSH),
> It's up to you to find a way to recover them in case of trouble.

Cloud backup
================

Cloud backup allows Jeedom to send your backups
directly on the Market. This allows you to restore them easily
and to be sure not to lose them. The Market keeps the last 6
backups. To subscribe just go to your page
**profile** on the Market, then, in the tab **my backups**. You
can, from this page, recover a backup or buy a
subscription (for 1, 3, 6 or 12 months).

> **Tip**
>
> You can customize the name of the backup files from
> of the tab **My Jeedoms**, while avoiding the characters
> exotic.

Frequency of automatic backups
======================================

Jeedom performs an automatic backup every day at the same
hour. It is possible to modify this one, from the "Engine of
tasks "(the task is named **Jeedom backup**), but it's not
recommended. Indeed, it is calculated in relation to the burden of
Market.
