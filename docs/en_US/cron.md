It informs about all Jeedom application tasks that run on the
server. This menu is to be used knowingly, or at the
request technical support.

> **Important**
>
> In case of mishandling on this page, any request for
> support may be refused.

To access it, go to **Administration → Task Engine**
:

At the top, on the right, you have:

-   **Disable the cron system**: a button to disable or
    reactivate all tasks (if you disable them all, more
    nothing will be functional on your Jeedom)

-   **Refresh**: a button to refresh the task board

-   **Add**: a button to add a cron job

-   **Save**: a button to save your changes.

Below you have the table of all the existing tasks
(be careful some tasks can launch subtasks, so it is
strongly recommended never to change information about this
page). In this table we find:

-   **\ #**: ID of the task, can be useful to make the link between a
    process that turns and what it really does

-   **Action**: a button to start or stop the task based
    of its status

-   **Active**: indicates if the task is active (can be started
    by Jeedom) or not

-   **PID**: indicates the current process ID

-   **Demon**: if this box is "yes" then the task must always
    to be in lessons. Beside you find the frequency of the demon, it is
    advised never to touch this value and especially never
    decrease it

-   **Unique**: if it's "yes" then the task will run once
    then will be deleted

-   **Class**: PHP class called to execute the task (can
    to be empty)

-   **Function**: PHP function called in the called class (or not
    if the class is empty)

-   **Programming**: programming the task in CRON format

-   **Timeout**: maximum duration of operation of the task. If the
    task is a daemon so it will automatically be stopped and
    restarted at the end of the timeout

-   **Last launch**: last task launch date

-   **Last duration**: last time to complete the task (a
    demon will always be at 0s, do not worry about other tasks
    can be at 0s)

-   **Status**: current status of the task (to recall a daemon task
    is still at "run")

-   **Deletion**: delete the task


