# Moteur de tâches
**Réglages → Système → Moteur de tâches**

Cette page informe de toutes les tâches applicatives Jeedom qui tournent sur le serveur.
Cette page est à utiliser en connaissance de cause ou à la demande du support technique.

> **Important**
>
> En cas de mauvaise manipulation sur cette page, toute demande de support peut vous être refusée.

## Onglet Cron

En haut, à droite, vous avez :

- **Désactiver le système cron** : un bouton pour désactiver ou réactiver toutes les tâches (si vous les désactivez toutes, plus rien ne sera fonctionnel sur votre Jeedom).
- **Rafraîchir** : Rafraîchit la table des tâches.
- **Ajouter** : Permet d'ajouter une tâche cron manuellement.
- **Sauvegarder** : Sauvegarde vos modifications.

En-dessous, vous avez le tableau de toutes les tâches existantes (attention, certaines tâches peuvent lancer des sous-tâches, il est donc vivement recommandé de ne jamais modifier d’informations sur cette page).

Dans ce tableau, on retrouve :

- **\#** : ID de la tâche, utile pour faire le lien entre un processus qui tourne et ce qu’il fait vraiment.
- **Actif** : Indique si la tâche est active (peut être lancée par Jeedom) ou non.
- **PID** : Indique le process ID actuel.
- **Démon** : Si cette case est à "oui" alors la tâche doit toujours être en cours. A côté, vous retrouvez la fréquence du démon, il est conseillé de ne jamais modifier cette valeur et surtout de ne jamais la diminuer.
- **Unique** : Si c’est à "oui" alors la tâche se lancera une fois puis se supprimera.
- **Classe** : Classe PHP appelée pour exécuter la tâche (peut être vide).
- **Fonction** : Fonction PHP appelée dans la classe appelée (ou non si la classe est vide).
- **Programmation** : Programmation de la tâche au format CRON.
- **Timeout** : Durée maximale de fonctionnement de la tâche. Si la tâche est un démon alors elle sera automatiquement arrêtée et redémarrée à la fin du timeout.
- **Dernier lancement** : Date de dernier lancement de la tâche.
- **Dernière durée** : Dernière durée d’exécution de la tâche (un démon sera toujours à 0s, il ne faut pas s’inquiéter d’autres tâches peuvent être à 0s).
- **Statut** : État actuel de la tâche (pour rappel, une tâche démon est toujours à "run").

- **Action** :
    - **Détails** : Voir le cron dans le détail (tel que stocké en base).
    - **Démarrer / Arrêter** : Lancer ou arrêter la tâche (en fonction de son statut).
    - **Suppression** : Permet de supprimer la tâche.


## Onglet Listener

Les listeners sont juste visibles en lecture et permettent de voir les fonctions appelées sur un évènement (mise à jour d'une commande...).

## Onglet Démon

Tableau de résumé des démons avec leur état, la date de dernier lancement ainsi que la possibilité de
- Démarrer / Redémarrer un démon.
- Arrêter un démon si la gestion automatique est désactiver.
- Activer / désactiver la gestion automatique d'un démon.

> Tip
> Les démons des plugins désactivés n’apparaissent pas sur cette page.