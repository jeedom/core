# Changelog Jeedom V4.2

## 4.2.0

### Pré-requis

- Debian 10 Buster
- Php 7.3

### Nouveautés / Améliorations

- **Synthèse** : Possibilité de paramétrage des objets pour aller vers un *design* ou une *vue* depuis la synthèse.
- **Analyse / Historique** : Possibilité de comparer un historique sur une période donnée.
- **Analyse / Equipements** : Les commandes orphelines affichent maintenant leur nom et date de suppression si encore dans l'historique de suppression, ainsi qu'un lien vers le scénario ou l'équipement concerné.
- **Analyse / Logs** : Numérotation des lignes des logs. Possibilité d'afficher le log brut.
- **Logs** : Coloration des logs en fonction de certains événements. Possibilité d'afficher le log brut.
- **Résumés** : Possibilité de définir une icône différente quand le résumé est nul (aucun volets ouvert, aucune lumière allumée, etc).
- **Résumés** : Possibilité de ne jamais afficher le numéro à droite de l'icône, ou seulement s'il est positif.
- **Résumés** : Le changement de paramètre de résumé en configuration et sur les objets est maintenant visible, sans attendre un changement de valeur du résumé.
- **Affichage en tableau** : Ajout d'un bouton à droite de la recherche sur les pages *Objets* *Scénarios* *Interactions* *Widgets* et *Plugins* pour basculer en mode tableau. Celui-ci est conservé par un cookie ou dans **Réglages → Système → Configuration / Interface**. Les plugins peuvent faire appel à cette nouvelle fonction du Core.
- **Blocs Code** : (Editeur de fichier, Scénarios, personnalisation avancée) Fonction de repli de code (*code folding*). Raccourcis Ctrl+Y et Ctrl+I.
- **Plugins / Gestion** : Affichage de la catégorie du plugin, et d'un lien pour ouvrir directement la page de celui-ci sans passer par le menu Plugins.
- **Scénario** : Bugfix des copier / coller et undo / redo (réécriture complète).

### Widgets Core

- Les paramètres optionnels disponibles sur les widgets sont maintenant affichés pour chaque widget, que ce soit dans la configuration de la commande ou depuis le mode Edition du Dashboard.
- De nombreux Widgets Core acceptent maintenant des paramètres optionnels de couleur. (slider horizontal et vertical, jauge, compass, rain, shutter, templates slider, etc.).
- Les Widgets Core avec affichage d'un *time* supportent maintenant un paramètre optionnel **time : date** pour afficher une date relative (Hier à 16h48, Lundi dernier à 14h00, etc).

### Déprécié

- Suppression de la lib jwerty pour du vanillaJS (gestion des raccourcis clavier). Conservée en v4.2 pour les plugins susceptibles de l'utiliser, elle sera supprimée en v4.3.

### En cours de test / développement

#### Coloration des logs et traduction

- `\core\configjeedom.config.php` : $JEEDOM_SCLOG_TEXT reprend les valeurs colorées pour la traduction. Vérifiez les retours de log manquant et les incorporer si nécessaire.
- Utilisé dans les class php `cmd` `scenario` `scenarioElement` `scenarioExpression` `scenarioSubElement`
- Utilisé dans les appels ajax `scenario` et `log`

#### Aide sur les template widget dashboard

- Système de traduction qui actuellement ne scanne pas core/template/dashboard et /mobile :
- > Scanner core/template/dashboard et tout mettre dans le i18n.json avec le path "core\/template\/widgets.html"



[Changelog v4.1](/fr_FR/core/4.1/changelog)
