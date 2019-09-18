Il informe de toutes les tâches applicatives Jeedom qui tournent sur le
serveur. Ce menu est à utiliser en connaissance de cause ou à la
demande du support technique.

> **Important**
>
> In case of mishandling on this page, any request for
> support may be refused.

Pour y accéder, il faut aller dans **Réglages → Système → Moteur de tâches**
:

# Cron

En haut, à droite, vous avez :

-   **Désactiver le système cron** : un bouton pour désactiver ou
    réactiver toutes les tâches (si vous les désactivez toutes, plus
    rien ne sera fonctionnel sur votre Jeedom)

-   **Rafraîchir** : un bouton pour rafraîchir le tableau des tâches

-   **Ajouter** : un bouton pour ajouter une tâche cron

-   **Enregistrer** : un bouton pour enregistrer vos modifications.

En-dessous, vous avez le tableau de toutes les tâches existantes
(attention, certaines tâches peuvent lancer des sous-tâches, il est donc
vivement recommandé de ne jamais modifier d’informations sur cette
page). Dans ce tableau, on retrouve :

-   **\#** : ID de la tâche, peut être utile pour faire le lien entre un
    processus qui tourne et ce qu’il fait vraiment

-   **Action** : un bouton pour lancer ou arrêter la tâche en fonction
    de son statut et un bouton pour voir le cron dans le détail (tel que stocké en base)

-   **Actif** : indique si la tâche est active (peut être lancée
    par Jeedom) ou non

-   **PID** : indique le process ID actuel

-   **Démon** : si cette case est à "oui" alors la tâche doit toujours
    être en cours. A côté, vous retrouvez la fréquence du démon, il est
    conseillé de ne jamais toucher cette valeur et surtout de ne jamais
    la diminuer

-   **Unique** : si c’est à "oui" alors la tâche se lancera une fois
    puis se supprimera

-   **Classe** : classe PHP appelée pour exécuter la tâche (peut
    être vide)

-   **Fonction** : fonction PHP appelée dans la classe appelée (ou non
    si la classe est vide)

-   **Programmation** : la programmation de la tâche au format CRON

-   **Timeout** : durée maximale de fonctionnement de la tâche. Si la
    tâche est un démon alors elle sera automatiquement arrêtée et
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
