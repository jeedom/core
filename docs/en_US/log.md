# Logs
**Analysis → Logs**

Logs are log files, allowing you to follow what is happening on your home automation. In most cases the logs will only be used for debugging and solving problems by the support team.

> **Tip**
>
> When the page opens, the first available log is displayed.

The Logs page is quite simple :
On the left, a list of available logs, with a search field to filter the name of the logs.
Top right 5 buttons :

- **Search** : Allows you to filter the display of the current log.
- **Pause / Resume** : Pause / resume real-time update of the current log.
- **Download** : Allows to download the current log.
- **Empty** : Allows to empty the current log.
- **Remove** : Delete the current log. If Jeedom needs it it will recreate it automatically.
- **Delete all logs** : Delete all logs present.

> **Tip**
>
> Note that the http log.error cannot be deleted. It is essential if you delete it (on the command line for example) it will not recreate itself, you must restart the system.

## Real time

The &quot;Event&quot; log is a bit special. First of all for it to work, it has to be in info or debug level, then it lists all the events or actions that happen on home automation. To access it, you must either go to the log page or in Analysis → Real time.

Once you click on it, you get a window that updates in real time and shows you all the events of your home automation.

At the top right you have a search field (only works if you are not on pause) and a button to pause (useful for copying / pasting for example).
