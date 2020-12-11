# Changelog Jeedom V4.2

## 4.2.0

- **Synthèse** : Possibilité de paramétrage des objets pour aller vers un *design* ou une *vue* depuis la synthèse.
- **Analyse / Historique** : Possibilité de comparer un historique sur une période donnée.
- **Analyse / Equipements** : Les commandes orphelines affichent maintenant leur nom et date de suppression si encore dans l'historique de suppression.
- **Analyse / Logs** : Numérotation des lignes des logs. Possibilité d'afficher le log brut.
- **Logs** : Coloration des logs en fonction de certains événements. Possibilité d'afficher le log brut.
- **Résumés** : Possibilité de définir une icône différente quand le résumé est nul (aucun volets ouvert, aucune lumière allumée, etc).
- **Résumés** : Possibilité de ne jamais afficher le numéro à droite de l'icône, ou seulement s'il est positif.
- **Résumés** : Le changement de paramètre de résumé en configuration et sur les objets est maintenant visible, sans attendre un changement de valeur du résumé.
- **Affichage en tableau** : Ajout d'un bouton à droite de la recherche sur les pages *Objets* *Scénarios* *Interactions* *Widgets* et *Plugins* pour basculer en mode tableau. Celui-ci est conservé par un cookie sur chaque page ou dans **Réglages → Système → Configuration / Interface**. Les plugins peuvent faire appel à cette nouvelle fonction du Core.
- **Blocs Code** : (Editeur de fichier, Scénarios, personnalisation avancée) Fonction de repli de code (*code folding*). Raccourcis Ctrl+Y et Ctrl+I.
- **Plugins / Gestion** : Affichage de la catégorie du plugin, et d'un lien pour ouvrir directement la page de celui-ci sans passer par le menu Plugins.
- **Scénario** : Bugfix des copier / coller et undo / redo (réécriture complète).
<br/><br/>
#### Deprecated
- Suppression de la lib jwerty pour du vanillaJS (gestion des raccourcis clavier). Conservée en v4.2 pour les plugins susceptibles de l'utiliser.



[Changelog v4.1](/fr_FR/core/4.1/changelog)
