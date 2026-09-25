# Task Engine
**Settings → System → Task Engine**

This page provides information about all Jeedom application tasks running on the server.
This page should be used with full knowledge of the facts or at the request of technical support.

> **Important**
>
> If you use this page incorrectly, any support requests you submit may be denied.

## Cron tab

At the top right, you’ll find:

- **Disable the cron system**: a button to disable or re-enable all tasks (if you disable them all, nothing will work on your Jeedom).
- **Refresh**: Refreshes the task table.
- **Add**: Allows you to manually add a cron job.
- **Save**: Saves your changes.

Below is a table listing all existing tasks (please note that some tasks may trigger subtasks, so it is strongly recommended that you never modify any information on this page).

This table includes:

- **\#**: Task ID, useful for linking a running process to what it’s actually doing.
- **Active**: Indicates whether the task is active (can be triggered by Jeedom) or not.
- **PID**: Displays the current process ID.
- **Daemon**: If this checkbox is set to "Yes," then the task must always be running. Next to it, you'll see the daemon's frequency; it is recommended that you never change this value and, above all, never decrease it.
- **Unique**: If set to "yes," the task will run once and then be deleted.
- **Class**: PHP class called to execute the task (may be empty).
- **Function**: A PHP function called within the called class (or not, if the class is empty).
- **Scheduling**: Schedule the task using the CRON format.
- **Timeout**: Maximum duration for which the task can run. If the task is a daemon, it will be automatically stopped and restarted when the timeout expires.
- **Last run**: The date the task was last run.
- **Last duration**: The last time the task ran (a daemon will always show 0s; don't worry if other tasks also show 0s).
- **Status**: Current status of the task (as a reminder, a daemon task is always set to "run").

- **Action**:
    - **Details**: View the cron job in detail (as stored in the database).
    - **Start / Stop**: Start or stop the task (depending on its status).
    - **Delete**: Deletes the task.


## Listener tab

Listeners are read-only and allow you to see the functions called in response to an event (such as a command update...).

## "Daemon" tab

Table listing all daemons along with their status, the date they were last started, and the ability to:
- Start / Restart a daemon.
- Stop a daemon if automatic management is disabled.
- Enable/disable automatic management of a daemon.

> Tip
> Plugins that have been disabled do not appear on this page.
