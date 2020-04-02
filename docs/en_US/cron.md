It informs of all Jeedom application tasks that run on the
server. This menu is to be used knowingly, or at the
request technical support.

> **IMPORTANT**
>
> In case of mishandling on this page, any request for
> support may be denied you.

To access it, go to **Administration → Task engine**
:

# Cron

At the top right, you have :

-   **Disable cron system** : a button to deactivate or
    re-enable all tasks (if you disable them all, more
    nothing will work on your Jeedom)

-   **Refresh** : a button to refresh the task table

-   **Add** : a button to add a cron job

-   **Save** : a button to save your changes.

Below you have the table of all existing tasks
(be careful, some tasks can launch subtasks, so it is
strongly recommended never to modify information on this
page). In this table, we find :

-   **\#** : Task ID, can be useful for linking a
    process that is running and what it really does

-   **ACTION** : a button to start or stop the task in function
    its status and a button to see the cron in detail (as stored in the database)

-   **Active** : indicates if the task is active (can be launched
    by Jeedom) or not

-   **PID** : indicates the current process ID

-   **Devil** : if this box is &quot;yes&quot; then the task must always
    to be in lessons. Next, you find the frequency of the demon, it is
    advised never to touch this value and especially never
    decrease it

-   **Unique** : if it is &quot;yes&quot; then the task will launch once
    then will delete

-   **Class** : PHP class called to execute the task (can
    be empty)

-   **Function** : PHP function called in the called class (or not
    if the class is empty)

-   **Programming** : programming the task in CRON format

-   **Timeout** : maximum task run time. If the
    task is a demon then it will be automatically stopped and
    restarted at the end of the timeout

-   **Last launch** : date of last task launch

-   **Last duration** : last time to complete the task (a
    demon will always be at 0s, so don&#39;t worry about other tasks
    can be 0s)

-   **Status** : current status of the task (as a reminder, a daemon task
    is still &quot;run&quot;)

-   **Suppression** : delete task


# Listener

The listeners are just visible in reading and allow you to see the functions called on an event (update of an order ...)
