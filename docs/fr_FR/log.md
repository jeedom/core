# Logs
**Analyse → Logs**

Les logs sont des fichiers de journaux, permettant de suivre ce qui se passe sur sa domotique. Dans la plupart des cas les logs ne serviront qu’à effectuer du debuggage et à résoudre des problèmes par l’équipe de support.

> **Tip**
>
> A l'ouverture de la page, le premier log disponible est affiché.

La page de Logs est assez simple :
Sur la gauche, une liste des logs disponibles, avec un champ de recherche pour filtrer le nom des logs.
En haut à droite 5 boutons :

- **Rechercher** : Permet de filtrer l’affichage du log actuel.
- **Pause/Reprendre** : Permet de mettre en pause/reprendre la mise à jour en temps réel du log actuel.
- **Télécharger** : Permet de télécharger le log actuel.
- **Vider** : Permet de vider le log actuel.
- **Supprimer** : Permet de supprimer le log actuel. Si Jeedom en a besoin il le recréera automatiquement.
- **Supprimer tous les logs** : Supprime tous les logs présents.

> **Tip**
>
> A noter que le log http.error ne peut être supprimé. Il est essentiel si vous le supprimez (en ligne de commande par exemple) celui-ci ne se recréera pas tout seul, il faut redémarrer le système.

## Temps réel

Le log "Event" est un peu particulier. Tout d’abord pour qu’il fonctionne, il faut qu’il soit en niveau info ou debug, ensuite celui-ci recense tous les évènements ou actions qui se passent sur la domotique. Pour y accéder, il faut soit aller sur la page de log soit dans Analyse → Temps réel.

Une fois que vous avez cliqué dessus, vous obtenez une fenêtre qui se met à jour en temps réel et vous affiche tous les évènements de votre domotique.

En haut à droite vous avez un champ recherche (ne marche que si vous n’êtes pas en pause) et un bouton pour mettre en pause (utile pour faire un copier/coller par exemple).
