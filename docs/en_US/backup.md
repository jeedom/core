Jeedom has the possibility to be saved and restored from or from
from different locations.

D'actualité 
=============

Accessible from **Administration → Backups**, this page allows the
backup management.

You will find, on the left, the parameters and action buttons. On the
right, this is the real-time status of the current action (backup
or restoration), if you have launched one.

**Backups** 
---------------

-   **Backups** : Allows you to start a backup manually and
    immediately (useful if you want to make a critical change.
    This will allow you to go back). You also have a
    button to start a backup without sending the archive to the
    cloud (requires subscription see below). Sending a
    cloud backup may take a while. This option
    thus avoids an excessive loss of time.

-   **Backup location** : Indicates the folder in which
    Jeedom copies backups. It is recommended not to
    change him. If you are on a relative path, its origin is
    where Jeedom is installed.

-   **Number of day (s) of storage of backups** : Number of
    backup days to keep. Once this period has passed, the
    backups will be deleted. Be careful not to put a number
    days too high, otherwise your file system may
    to be saturated.

-   **Maximum total size of backups (MB)** : Allows to limit
    the place taken by all of the backups in the folder
    backup. If this value is exceeded, Jeedom will delete the
    oldest backups until falling below the
    maximum size. It will however keep at least one backup.

**Local backups** 
-----------------------

-   **Available backups** : List of available backups.

-   **Restore backup** : Starts restoring the backup
    selected above.

-   **Delete backup** : Delete selected backup
    above, only in the local folder.

-   **Send a backup** : Allows you to send to the
    save an archive on the computer that you
    currently using (allows for example to restore an archive
    previously recovered on a new Jeedom or reinstallation).

-   **Download backup** : Lets download to your
    computer the backup archive selected above.

**Market backups** 
----------------------

-   **Sending backups** : Instructs Jeedom to send the
    backups on the Market cloud, beware you must have
    got the subscription.

-   **Send a backup** : Allows you to send a
    backup archive located on your computer.

-   **Available backups** : List of backups
    cloud available.

-   **Restore backup** : Launches the restoration of a
    cloud backup.

**Samba backups** 
---------------------

-   **Sending backups** : Instructs Jeedom to send the
    backups on the samba share configured here
    Administration → D'actualité → Updates tab.

-   **Available backups** : List of backups
    samba available.

-   **Restore backup** : Starts restoring the backup
    samba selected above.

> **Important**
>
>  !!! 


What is saved ? 
==============================

During a backup, Jeedom will backup all of its files and the
database. This therefore contains all of your configuration
(equipment, orders, history, scenarios, design, etc.).

In terms of protocols, only the Z-Wave (OpenZwave) is a bit
different because it is not possible to save the inclusions.
These are directly included in the controller, so you have to
keep the same controller to find its Zwave modules.

> **NOTE**
>
> The system on which Jeedom is installed is not backed up. Yes
> you have modified parameters of this system (notably via SSH),
> it&#39;s up to you to find a way to recover them in case of problems.

Cloud backup 
================

Cloud backup allows Jeedom to send your backups
directly on the Market. This allows you to restore them easily
and be sure not to lose them. The Market keeps the last 6
backups. To subscribe just go to your page
**profile** on the Market, then in the tab **my backups**. You
can, from this page, retrieve a backup or buy a
subscription (for 1, 3, 6 or 12 months).

> **Tip**
>
> You can customize the name of the backup files from
> of the tab **My jeedoms**, avoiding however the characters
> exotic.

Frequency of automatic backups 
======================================

Jeedom performs an automatic backup every day at the same
hour. It is possible to modify this, from the &quot;Engine
tasks &quot;(the task is named **Jeedom backup**), but it&#39;s not
recommended. Indeed, it is calculated in relation to the load of the
Market.
