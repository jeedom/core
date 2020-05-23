Viewing 
=========

The Logs menu allows you to follow what is happening on your home automation. In the
most cases the logs will only be used for debugging and
solve problems by the support team.

To access it, go to Administration → Logs :

The Logs page is quite simple, at the top left a drop-down list
allowing the choice of the log to watch, at the top right you have 5
buttons :

-   **Search** : allows to filter the log poster

-   **Pause / Resume** : allows you to pause / resume the setting
    real time log update

-   **Download** : allows to download the current log,

-   **Empty** : allows to empty the current log,

-   **Remove** : delete the current log, if Jeedom has any
    need it will recreate it automatically,

-   **Delete all logs** : delete all logs present.

> **Tip**
>
> Note that the http log.error cannot be deleted. It is essential
> ! if you delete it (on the command line for example) it will not
> not recreate itself, you have to restart the system.

The event log" 
==============

The &quot;Event&quot; log is a bit special. First of all so that it
works, it must be in info or debug level, then this one
lists all the events or actions happening on home automation.
To access it, you must either go to the log page or in Analysis
→ Real time

Once you have clicked on it, you will get a window
updates in real time and displays all the events of your
domotique.

At the top right you have a search field (only works if you
are not paused) and a button to pause (useful for
copy / paste for example).
