Logs 
====

The Logs menu allows you to follow what is happening on your home. In the
In most cases the logs will only be used to debug and
solve problems by the support team.

Pour y accéder il faut aller dans Analyse → Logs :

The Logs page is quite simple, in the top left a drop-down list
allowing the choice of log to look, in the top right you have 5
buttons:

-   **Search**: allows you to filter the log poster

-   **Pause / Resume**: Pause / resume the update
    real time logs

-   **Download**: allows you to download the current log,

-   **Empty**: allows to empty the current log,

-   **Delete**: deletes the current log, if Jeedom has
    need it will recreate it automatically,

-   **Delete all logs**: delete all the logs present.

> **Tip**
>
> Note that the http.error log can not be deleted. It is essential
>! if you delete it (in command line for example) this one does not
> will not recreate itself, it is necessary to restart the system.

Temps réel 
==============

The log "Event" is a bit special. First of all for him
it works, it must be in level info or debug, then this one
list all the events or actions that happen on home automation.
To access it, either go to the log page or in Analysis
→ Real time

Once you click on it, you get a window that
update in real time and show you all the events of your
Automation.

At the top right you have a search field (works only if you
are not paused) and a button to pause (useful for making
copy / paste for example).
