It informs about all Jeedom application tasks that run on the
server. This menu is to be used knowingly, or at the
request technical support.

> **Important**
>
> In case of mishandling on this page, any request for
> support may be refused.

Pour y accéder, il faut aller dans **Réglages → Système → Moteur de tâches**
:

# Cron

On top right you can :

-   **Désactiver le système cron** : un bouton pour désactiver ou
    reactivate all tasks (if you disable them all, more
    nothing will be functional on your Jeedom)

-   **Rafraîchir** : un bouton pour rafraîchir le tableau des tâches

-   **Add**: a button to add a cron job

-   **Save**: a button to save your changes.

Below you have the table of all the existing tasks
(be careful some tasks can launch subtasks, so it is 
strongly recommended never to change information about this
 page). In this table we find:

-   **\ #**: ID of the task, can be useful to make the link between a
    process that turns and what it really does

-   **Action**: a button to start or stop the task based
    de son statut et un bouton pour voir le cron dans le détail (tel que stocké en base)

-   **Active**: indicates if the task is active (can be started
    by Jeedom) or not

-   **PID**: indicates the current process ID

-   **Demon**: if this box is "yes" then the task must always
    to be in progress. Beside you find the frequency of the demon, it is
    advised to never touch this value and especially never
    decrease it

-   **Unique**: if it's "yes" then the task will run once
    then will be deleted

-   **Class**: PHP class called to execute the task (can
    be empty)

-   **Function**: PHP function called in the called class (or not
    if the class is empty)

-   **Programming**: programming the task in CRON format

-   **Timeout**: maximum duration of operation of the task. If the
    task is a daemon so it will automatically be stopped and
    redémarrée à la fin du timeout

-   **Dernier lancement** : date de dernier lancement de la tâche

-   **Dernière durée** : dernière durée pour accomplir la tâche (un
    démon sera toujours à 0s, il ne faut pas s’inquiéter d’autres tâches
    peuvent être à 0s)

-   **Statut** : état actuel de la tâche (pour rappel, une tâche démon
    est toujours à "run")

-   **Suppression** : permet de supprimer la tâche


# Listener

Les listeners sont juste visibles en lecture et permettent de voir les fonctions appelées sur un évènement (mise à jour d'une commande...)

# Démons

Tableau de résumé des démons avec leur état, la date de dernier lancement ainsi que la possibilité de les arrêter ou les redémarrer.
