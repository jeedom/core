# Backups
**Settings → System → Backups**

Jeedom allows you to back up and perform Restoration of data from or to various locations.
This page allows you to manage backups and restorations.


On the left, you'll find the settings and action buttons. On the right is the real-time status of the current action (backup or Restoration), if you've started one.

## Backups

- **Backups**: Allows you to initiate a backup manually and immediately (useful if you want to make a critical change—this will let you revert to a previous state). There’s also a button to start a backup without uploading the archive to the cloud (requires a subscription; see below). Uploading a backup to the cloud can take some time. This option helps you avoid wasting too much time.

- **Backup Location**: Specifies the folder where Jeedom copies the backups. It is recommended that you do not change this. If you use a relative path, the base directory is where Jeedom is installed.

- **Number of days to retain backups**: The number of days for which backups should be retained. Once this period has elapsed, the backups will be deleted. Be careful not to set this number too high, or your file system may become full.

- **Maximum total backup size (MB)**: Allows you to limit the amount of space taken up by all backups in the backup folder. If this value is exceeded, Jeedom will delete the oldest backups until the total size falls below the maximum limit. However, it will retain at least one backup.

## Local Backups

- **Available Backups**: List of available backups.

- **Restore Backup**: Starts the Restoration of the backup selected above.

- **Delete backup**: Deletes the backup selected above, only from the local folder.

- **Send a Backup**: Allows you to send an archive located on the computer you are currently using to the backups folder (for example, to perform Restoration of an archive previously downloaded to a new Jeedom or after a reinstallation).

- **Download Backup**: Allows you to download the backup archive selected above to your computer.

## Market Backups

- **Send Backups**: Tells Jeedom to send backups to the Market cloud; please note that you must have a subscription.

- **Send a Backup**: Allows you to send a backup archive stored on your computer to the cloud.

- **Available Backups**: List of available cloud backups.

- **Restore Backup**: Starts the restoration of a cloud backup.

## Samba Backups

- **Send Backups**: Instructs Jeedom to send backups to the Samba share configured here: Settings → System → Configuration: Updates.

- **Available Backups**: List of available Samba backups.

- **Restore Backup**: Starts the Restoration of the Samba backup selected above.

> **IMPORTANT**
>
> Jeedom backups must be saved exclusively in a folder designated for that purpose! It will delete everything from the folder that is not a Jeedom backup.


# What is backed up?

During a backup, Jeedom will back up all its files and the database. This includes your entire configuration (devices, commands, history, scenarios, design, etc.).

In terms of protocols, only Z-Wave (OpenZwave) is slightly different because it is not possible to back up device pairings. These pairings are stored directly in the controller, so you must keep the same controller to retrieve your Z-Wave modules.

> **Note**
>
> The system on which Jeedom is installed is not backed up. If you have modified any settings on this system (particularly via SSH), it is up to you to find a way to recover them in case of problems. Similarly, the dependencies are not backed up either, so you will need to reinstall them after a Restoration.

# Cloud backup

Cloud backup allows Jeedom to send your backups directly to the Market. This lets you easily perform Restoration and ensures you won’t lose them. The Market stores the last 6 backups. To subscribe, simply go to your **profile** page on the Market, then to the **my backups** tab. From this page, you can retrieve a backup or purchase a subscription (for 1, 3, 6, or 12 months).

> **Tip**
>
> You can customize the names of backup files from the **My Jeedoms** tab, but be sure to avoid using special characters.

# Frequency of automatic backups

Jeedom performs an automatic backup every day at the same time. You can change this setting using the "Task Engine" (the task is named **Jeedom backup**), but this is not recommended. This is because the time is calculated based on Market load.

# FAQ

>**I can't perform Restoration on the backup I downloaded from Safari**
>
>By default, Safari unpacks tar.gz files (into tar), which means the backup can no longer be used by Jeedom; you must repack it (gzip) into a tar.gz file.
