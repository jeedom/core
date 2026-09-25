# Logs
**Analysis → Logs**

Logs are files that track what is happening in your home automation system. In most cases, logs are used only for debugging and troubleshooting by the support team.

> **Tip**
>
> When the page opens, the first available log is displayed.

The Logs page is pretty simple:
On the left is a list of available logs, with a search field to filter by log name.
Top right: 5 buttons:

- **Search**: Allows you to filter the display of the current log.
- **Pause/Resume**: Allows you to pause or resume the real-time update of the current log.
- **Download**: Allows you to download the current log.
- **Clear**: Clears the current log.
- **Delete**: Deletes the current log. If Jeedom needs it, it will automatically recreate it.
- **Delete all logs**: Deletes all existing logs.

> **Tip**
>
> Please note that the http.error log cannot be deleted. It is essential; if you delete it (via the command line, for example), it will not be recreated automatically—you must restart the system.

## Real-time

The "Event" log is a bit unusual. First, for it to work, it must be set to the "info" or "debug" level; second, it records all events and actions occurring in the home automation system. To access it, go either to the log page or to Analysis → Real-Time.

Once you click on it, a window opens that updates in real time and displays all the events in your home automation system.

In the top right corner, you'll find a search field (which only works if you're not paused) and a pause button (useful for copying and pasting, for example).
