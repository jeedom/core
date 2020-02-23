# logs
**Analyse → logs**

Les logs sont des fichiers de journaux, permettant de suivre ce qui se passe sur sa domotique. Dans la plupart des cas les logs ne serviront qu'à effectuer du debuggage et à résoudre des problèmes par l'équipe de support.

> **Spitze**
>
> A l'ouverture de la page, le premier log disponible est affiché.

La page de logs est assez simple :
Sur la gauche, une liste des logs disponible, avec un champ de recherche pour filtrer le nom des logs.
En haut à droite 5 boutons :

- **Suche** : Permet de filtrer l'affichage du log actuel.
- **Pause/Reprendre** : Permet de mettre en pause/reprendre la mise à jour en temps réel du log actuel.
- **Download** : Permet de télécharger le log actuel.
- **Vider** : Permet de vider le log actuel.
- **Entfernen** : Permet de supprimer le log actuel. Si Jeedom en a besoin il le recréera automatiquement.
- **Entfernen tous les logs** : Supprime tous les logs présents.

> **Spitze**
>
> A noter que le log http.error ne peut être supprimé. Il est essentiel si vous le supprimez (en ligne de commande par exemple) celui-ci ne se recréera pas tout seul, il faut redémarrer le système.

## Echtzeit

Das &quot;Ereignis&quot; -Protokoll ist etwas Besonderes. Damit es funktioniert, muss es sich zunächst auf Info- oder Debug-Ebene befinden und anschließend alle Ereignisse oder Aktionen auflisten, die bei der Heimautomation auftreten. Um darauf zuzugreifen, müssen Sie entweder zur Protokollseite oder unter Analyse → Echtzeit gehen.

Sobald Sie darauf klicken, erhalten Sie ein Fenster, das in Echtzeit aktualisiert wird und Ihnen alle Ereignisse Ihrer Hausautomation anzeigt.

Oben rechts haben Sie ein Suchfeld (funktioniert nur, wenn Sie nicht pausieren) und eine Schaltfläche zum Pausieren (nützlich zum Kopieren / Einfügen zum Beispiel)..
