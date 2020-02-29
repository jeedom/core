Es informiert über alle Jeedom-Anwendungsaufgaben, die auf dem ausgeführt werden
Server. Dieses Menü ist wissentlich oder am zu verwenden
Technischen Support anfordern.

> **wichtig**
>
> Im Falle eines Missbrauchs auf dieser Seite jede Anfrage für
> Unterstützung kann Ihnen verweigert werden.

Um darauf zuzugreifen, gehen Sie zu **Administration → Task Engine**
:

# cron

Oben rechts haben Sie :

-   **cron-System deaktivieren** : eine Taste zum Deaktivieren oder
    Aktivieren Sie alle Aufgaben erneut (wenn Sie alle deaktivieren, mehr
    nichts wird auf deinem Jeedom funktionieren)

-   **cool** : un bouton pour rafraîchir le tableau des tâches

-   **hinzufügen** : un bouton pour ajouter une tâche cron

-   **Rekord** : un bouton pour enregistrer vos modifications.

En-dessous, vous avez le tableau de toutes les tâches existantes
(attention, certaines tâches peuvent lancer des sous-tâches, il est donc
vivement recommandé de ne jamais modifier d'informations sur cette
page). Dans ce tableau, on retrouve :

-   **\#** : ID de la tâche, peut être utile pour faire le lien entre un
    processus qui tourne et ce qu'il fait vraiment

-   **Aktion** : un bouton pour lancer ou arrêter la tâche en fonction
    de son statut et un bouton pour voir le cron dans le détail (tel que stocké en base)

-   **Aktiva** : indique si la tâche est active (peut être lancée
    par Jeedom) ou non

-   **PID** : indique le process ID actuel

-   **Dämon** : si cette case est à "oui" alors la tâche doit toujours
    être en cours. A côté, vous retrouvez la fréquence du démon, il est
    conseillé de ne jamais toucher cette valeur et surtout de ne jamais
    la diminuer

-   **Unique** : si c'est à "oui" alors la tâche se lancera une fois
    puis se supprimera

-   **Classe** : classe PHP appelée pour exécuter la tâche (peut
    leer sein)

-   **Fonction** : fonction PHP appelée dans la classe appelée (ou non
    si la classe est vide)

-   **Programmation** : la programmation de la tâche au format CRON

-   **Timeout** : durée maximale de fonctionnement de la tâche. Wenn die
    tâche est un démon alors elle sera automatiquement arrêtée et
    redémarrée à la fin du timeout

-   **Letzter Start** : date de dernier lancement de la tâche

-   **Dernière durée** : dernière durée pour accomplir la tâche (un
    démon sera toujours à 0s, il ne faut pas s'inquiéter d'autres tâches
    peuvent être à 0s)

-   **Status** : état actuel de la tâche (pour rappel, une tâche démon
    est toujours à "run")

-   **Suppression** : permet de supprimer la tâche


# Listener

Les listeners sont juste visibles en lecture et permettent de voir les fonctions appelées sur un évenement (mise à jour d'une commande...)
