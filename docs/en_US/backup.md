# Sauvegardes
**Settings → System → Backups**

Jeedom offers the possibility of being saved and restored from or from different locations.
This page allows the management of backups, restores.


You will find, on the left, the parameters and action buttons. On the right is the real-time status of the action in progress (backup or restore), if you have launched one.

## Sauvegardes

- **Backups** : Allows you to start a backup manually and immediately (useful if you want to make a critical change. This will allow you to go back). You also have a button to launch a backup without sending the archive to the cloud (requires a subscription see below). Sending a backup to the cloud can take a while. This option therefore avoids excessive loss of time.

- **Backup location** : Indicates the folder in which Jeedom copies the backups. It is recommended not to change it. If you are on a relative path, its origin is where Jeedom is installed.

- **Number of day (s) of storage of backups** : Number of backup days to keep. Once this period has passed, the backups will be deleted. Be careful not to put too many days, otherwise your file system may be saturated.

- **Maximum total size of backups (MB)** : Limits the space taken by all backups in the backup folder. If this value is exceeded, Jeedom will delete the oldest backups until it drops below the maximum size. It will however keep at least one backup.

## Local backups

- **Available backups** : List of available backups.

- **Restore backup** : Launches the restoration of the backup selected above.

- **Delete backup** : Delete the backup selected above, only in the local folder.

- **Send a backup** : Allows you to send an archive located on the computer you are currently using to the backups folder (for example, restoring an archive previously recovered on a new Jeedom or reinstalling).

- **Download backup** : Download the archive of the backup selected above to your computer.

## Market backups

- **Sending backups** : Tell Jeedom to send backups to the Market cloud, please note that you must have subscribed.

- **Send a backup** : Send a backup archive located on your computer to the cloud.

- **Available backups** : List of available cloud backups.

- **Restore backup** : Starts restoring a cloud backup.

## Samba backups

- **Sending backups** : Tells Jeedom to send the backups to the samba share configured here Settings → System → Configuration : Updates.

- **Available backups** : List of available samba backups.

- **Restore backup** : Starts restoring the samba backup selected above.

> **Important**
>
> Jeedom backups must absolutely fall into a folder only for him !!! It will delete everything that is not a jeedom backup from the folder


# What is saved ?

During a backup, Jeedom will backup all of its files and the database. This therefore contains all of your configuration (equipment, controls, history, scenarios, design, etc.).

At the protocol level, only the Z-Wave (OpenZwave) is a little different because it is not possible to save the inclusions. These are directly included in the controller, so you must keep the same controller to find its Zwave modules.

> **NOTE**
>
> The system on which Jeedom is installed is not backed up. If you have changed settings for this system (including via SSH), it&#39;s up to you to find a way to recover them if you have any concerns.

# Cloud backup

Cloud backup allows Jeedom to send your backups directly to the Market. This allows you to restore them easily and be sure not to lose them. The Market keeps the last 6 backups. To subscribe just go to your page **profile** on the Market, then in the tab **my backups**. You can, from this page, retrieve a backup or buy a subscription (for 1, 3, 6 or 12 months).

> **Tip**
>
> You can customize the name of the backup files from the tab **My jeedoms**, avoiding however the exotic characters.

# Frequency of automatic backups

Jeedom performs an automatic backup every day at the same time. It is possible to modify it, from the &quot;Task engine&quot; (the task is named **Jeedom backup**), but it is not recommended. Indeed, it is calculated in relation to the load of the Market.
