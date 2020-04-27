# Sauvegardes
**Réglages → Système → Sauvegardes**

Jeedom offre la possibilité d’être sauvegardé et restauré depuis ou à partir de différents emplacements.
Cette page permet la gestion des sauvegardes, restaurations.


Vous y trouvez, à gauche, les paramètres et les boutons d’action. Sur la droite, c’est le statut en temps réel de l’action en cours (sauvegarde ou restauration), si vous en avez lancé une.

## Sauvegardes

- **Sauvegardes** : Permet de lancer une sauvegarde manuellement et immédiatement (utile si vous voulez faire un changement critique. Cela vous permettra de revenir en arrière). Vous avez aussi un bouton pour lancer une sauvegarde sans envoyer l’archive sur le cloud (nécessite un abonnement voir plus bas). L’envoi d’une sauvegarde sur le cloud peut prendre un certain temps. Cette option permet donc d’éviter une perte de temps trop importante.

- **Emplacement des sauvegardes** : Indique le dossier dans lequel Jeedom copie les sauvegardes. Il est recommandé de ne pas le changer. Si vous êtes en chemin relatif, son origine est l’endroit où Jeedom est installé.

- **Nombre de jour(s) de mémorisation des sauvegardes** : Nombre de jours de sauvegarde à garder. Une fois ce délai passé, les sauvegardes seront supprimées. Attention de ne pas mettre un nombre de jours trop élevé, sinon votre système de fichiers peut être saturé.

- **Taille totale maximale des sauvegardes (Mo)** : Permet de limiter la place prise par l’ensemble des sauvegardes dans le dossier de sauvegarde. Si cette valeur est dépassée, Jeedom va supprimer les sauvegardes les plus vieilles jusqu’à retomber en-dessous de la taille maximale. Il gardera cependant au moins une sauvegarde.

## Sauvegardes Locales

- **Sauvegardes disponibles** : Liste des sauvegardes disponibles.

- **Restaurer la sauvegarde** : Lance la restauration de la sauvegarde sélectionnée au-dessus.

- **Supprimer la sauvegarde** : Supprime la sauvegarde sélectionnée au-dessus, uniquement dans le dossier local.

- **Envoyer une sauvegarde** : Permet d’envoyer dans le dossier des sauvegardes une archive se trouvant sur l’ordinateur que l’on utilise actuellement (permet par exemple de restaurer une archive précédemment récupérée sur un nouveau Jeedom ou une réinstallation).

- **Télécharger la sauvegarde** : Permet de télécharger sur votre ordinateur l’archive de la sauvegarde sélectionnée au-dessus.

## Sauvegardes Market

- **Envoi des sauvegardes** : Indique à Jeedom d’envoyer les sauvegardes sur le cloud du Market, attention il faut avoir pris l’abonnement.

- **Envoyer une sauvegarde** : Permet d’envoyer sur le cloud une archive de sauvegarde située sur votre ordinateur.

- **Sauvegardes disponibles** : Liste des sauvegardes cloud disponibles.

- **Restaurer la sauvegarde** : Lance la restauration d’une sauvegarde cloud.

## Sauvegardes Samba

- **Envoi des sauvegardes** : Indique à Jeedom d’envoyer les sauvegardes sur le partage samba configuré ici Réglages → Système→Configuration : Mises à jour.

- **Sauvegardes disponibles** : Liste des sauvegardes samba disponibles.

- **Restaurer la sauvegarde** : Lance la restauration de la sauvegarde samba sélectionnée au-dessus.

> **IMPORTANT**
>
> Les backups de jeedom doivent absolument tomber dans un dossier que pour lui !!! Il supprimera tous ce qui n'est pas un backup jeedom du dossier


# Qu’est-ce qui est sauvegardé ?

Lors d’une sauvegarde, Jeedom va sauvegarder tous ses fichiers et la base de données. Cela contient donc toute votre configuration (équipements, commandes, historiques, scénarios, design, etc.).

Au niveau des protocoles, seul le Z-Wave (OpenZwave) est un peu différent car il n’est pas possible de sauvegarder les inclusions. Celles-ci sont directement incluses dans le contrôleur, il faut donc garder le même contrôleur pour retrouver ses modules Zwave.

> **Note**
>
> Le système sur lequel est installé Jeedom n’est pas sauvegardé. Si vous avez modifié des paramètres de ce système (notamment via SSH), c’est à vous de trouver un moyen de les récupérer en cas de soucis.

# Sauvegarde cloud

La sauvegarde dans le cloud permet à Jeedom d’envoyer vos sauvegardes directement sur le Market. Cela vous permet de les restaurer facilement et d’être sûr de ne pas les perdre. Le Market conserve les 6 dernières sauvegardes. Pour vous abonner il suffit d’aller sur votre page **profil** sur le Market, puis, dans l’onglet **mes backups**. Vous pouvez, à partir de cette page, récupérer une sauvegarde ou acheter un abonnement (pour 1, 3, 6 ou 12 mois).

> **Tip**
>
> Vous pouvez personnaliser le nom des fichiers de sauvegarde à partir de l’onglet **Mes Jeedoms**, en évitant toutefois les caractères exotiques.

# Fréquence des sauvegardes automatiques

Jeedom effectue une sauvegarde automatique tous les jours à la même heure. Il est possible de modifier celle-ci, à partir du "Moteur de tâches" (la tâche est nommée **Jeedom backup**), mais ce n’est pas recommandé. En effet, elle est calculée par rapport à la charge du Market.
