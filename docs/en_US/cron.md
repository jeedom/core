# Task engine
**Settings → System → Task engine**

This page informs about all Jeedom application tasks running on the server.
This page is to be used knowingly or at the request of technical support.

> **IMPORTANT**
>
> In case of mishandling on this page, any request for support may be refused.

## Cron tab

At the top right, you have :

- **Disable cron system** : a button to deactivate or reactivate all tasks (if you deactivate them all, nothing will be functional on your Jeedom).
- **Refresh** : Refreshes the task table.
- **Add** : Add a cron job manually.
- **Save** : Save your changes.

Below, you have the table of all existing tasks (attention, some tasks can launch subtasks, so it is strongly recommended never to modify information on this page).

In this table, we find :

- **\#** : Task ID, useful for linking a running process to what it really does.
- **Active** : Indicates if the task is active (can be launched by Jeedom) or not.
- **PID** : Indicates the current process ID.
- **Devil** : If this box is &quot;yes&quot; then the task must always be in progress. Beside, you find the frequency of the daemon, it is advised never to modify this value and especially never to decrease it.
- **Unique** : If it is &quot;yes&quot; then the task will launch once and then delete itself.
- **Class** : PHP class called to execute the task (can be empty).
- **Function** : PHP function called in the called class (or not if the class is empty).
- **Programming** : Programming of the task in CRON format.
- **Timeout** : Maximum task run time. If the task is a daemon then it will be automatically stopped and restarted at the end of the timeout.
- **Last launch** : Date of last task launch.
- **Last duration** : Last execution time of the task (a daemon will always be at 0s, don't worry about other tasks can be at 0s).
- **Status** : Current status of the task (as a reminder, a daemon task is always "run"").

- **ACTION** :
    - **Details** : See the cron in detail (as stored in base).
    - **Start / Stop** : Start or stop the task (depending on its status).
    - **Suppression** : Delete task.


## Listener tab

The listeners are just visible in reading and allow you to see the functions called on an event (update of a command...).

## Demon tab

Summary table of the demons with their state, the date of last launch as well as the possibility of
- Start / Restart a daemon.
- Stop a daemon if automatic management is deactivated.
- Enable / disable automatic management of a daemon.

> Tip
> Demons of disabled plugins do not appear on this page.