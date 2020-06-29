
Changelog Jeedom V4
=========

4.0.59
=====

- Correction de bugs sur les widgets time
- Augmentation du nombre de mauvais mot de passe avant bannissement (évite les soucis avec la webapp lors de la rotation des clefs api)

4.0.57
=====

- Renforcement de la securité des cookies
- Utilisation de chromium (si il est installé) pour les rapports
- Correction d'un soucis de calcul de temps d'état sur les widgets si le fuseau horaire de jeedom n'est pas le meme que celui du navigateur
- Correction de bugs

4.0.55
=======

- Le nouveau dns (\*.eu.jeedom.link) devient le DNS primaire (l'ancien DNS marche toujours)

4.0.54
=====

- Début de la mise à jour vers le nouveau site de documentation

4.0.53
=====

- Correction de bug.

4.0.52
=====

- Correction de bug (mise à jour à faire absolument si vous êtes en 4.0.51).

4.0.51
=====

- Correction de bugs.
- Optimisation du futur système de DNS.

4.0.49
=====

- Possibilité de choisir le moteur TTS de jeedom et possibilité d'avoir des plugins qui propose un nouveau moteur TTS.
- Amélioration du support de la webview dans l'application mobile.
- Correction de bugs.
- Mise à jour de la doc.

4.0.47
=====

- Amélioration du testeur d'expression.
- Mise à jour du repository sur smart.
- Correction de bugs.

4.0.44
=====

- Amélioration des traductions.
- Correction de bugs.
- Amélioration de la restauration de backup cloud.
- La restauration cloud ne rapatrie plus maintenant que le backup en local, laissant le choix de le télécharger ou de le restaurer.

4.0.43
=====

- Amélioration des traductions.
- Correction de bugs sur les templates de scénario.

4.0.0
=====
- Refonte complète des thèmes (Core 2019 Light / Dark / Legacy).
- Possibilité de changer de thème automatiquement en fonction de l'heure.
- En mobile, le thème peut changer en fonction de la luminosité (Nécessite d'activer *generic extra sensor* dans chrome, page chrome://flags).<br/><br/>
- Amélioration et réorganisation du menu principal.
- Menu Plugins : La liste des catégories et des plugins est maintenant triée alphabétiquement.
- Menu Outils : Ajout d'un bouton pour avoir accès au testeur d'expression.
- Menu Outils : Ajout d'un bouton pour avoir accès aux variables.<br/><br/>
- Les champs de recherche supportent maintenant les accents.
- Les champs de recherche (Dashboard, scénarios, objets, widgets, interactions, plugins) sont maintenant actifs à l'ouverture de la page, permettant de taper directement une recherche.
- Ajout d'un bouton X sur les champs de recherche pour annuler la recherche.
- Lors d'une recherche, la touche *echap* annule la recherche.
- Dashboard : En mode édition, le champ recherche et ses boutons sont désactivés et deviennent fixe.
- Dashboard : En mode édition, un clic sur un bouton *expand* à droite des objets redimensionne les tuiles de l'objet à la hauteur de la plus haute. Ctrl+clic les réduit à la hauteur de la moins haute.
- Dashboard : L’exécution de commande sur une tuile est maintenant signalée par le bouton *refresh*. Si il n'y en a pas sur la tuile, il apparaîtra le temps de l’exécution.
- Dashboard : Les tuiles indiquent une commande info (historisée, qui ouvrira la fenêtre Historique) ou action au survol.
- Dashboard : La fenêtre d'historique permet maintenant d'ouvrir cet historique dans Analyse/Historique.
- Dashboard : La fenêtre d'historique conserve ses position/dimensions à la réouverture d'un autre historique.
- Fenêtre Configuration de commande: Ctrl+clic sur "Enregistrer" ferme la fenêtre après.
- Fenêtre Configuration de l'équipement: Ctrl+clic sur "Enregistrer" ferme la fenêtre après.
- Ajout d'informations d'utilisation lors de la suppression d'un équipement.
- Objets : Ajout d'une option pour utiliser des couleurs personnalisées.
- Objets : Ajout d'un menu contextuel sur les onglets (changement rapide d'objet).
- Interactions : Ajout d'un menu contextuel sur les onglets (changement rapide d'interaction).
- Plugins : Ajout d'un menu contextuel sur les onglets (changement rapide d'équipement).
- Plugins : Sur la page Gestion des plugins, un point orange signale les plugins en version non Stable.
- Améliorations des tables avec option de filtre et tri.
- Possibilité d'attribuer une icône à une interaction.
- Chaque page de Jeedom a maintenant un titre dans la langue de l'interface (tab du navigateur).
- Prévention de l'auto remplissage sur les champs 'Code d'accès'.
- Gestion des fonctions *Page précédente / Page suivante* du navigateur.<br/><br/>
- Widgets : Refonte du système de widgets (menu Outils / Widgets).
- Widgets : Possibilité de remplacer un widget par un autre sur toutes les commandes l'utilisant.
- Widgets : Possibilité d'affecter un widgets à plusieurs commandes.
- Widgets : Ajout d'un widget info numeric horizontal.
- Widgets : Ajout d'un widget info numeric vertical.
- Widgets : Ajout d'un widget info numeric compass/wind (merci @thanaus).
- Widgets : Ajout d'un widget info numeric rain (merci @thanaus)
- Widgets : Affichage du widget info/action shutter proportionnel à la valeur.<br/><br/>
- Configuration : Amélioration et réorganisation des onglets.
- Configuration : Ajout de nombreux *tooltips* (aide).
- Configuration : Ajout d'un moteur de recherche.
- Configuration : Ajout d'un bouton pour vider le cache des widgets (onglet Cache).
- Configuration : Ajout d'une option pour désactiver le cache des widgets (onglet Cache).
- Configuration : Possibilité de centrer verticalement le contenu des tuiles (onglet Interface).
- Configuration : Ajout d'un paramètre pour la purge globale des historiques (onglet Commandes).
- Configuration : Changement de #message# à #subject# dans Configuration/Logs/Messages pour éviter la duplication du message.
- Configuration : Possibilité dans les résumés d'ajouter une exclusion des commandes n'ayant pas étés mises à jour depuis plus de XX minutes (exemple pour le calcul des moyennes de température si un capteur n'a rien remonté depuis plus de 30min il sera exclus du calcul)<br/><br/>
- Scénario : La colorisation des blocs n'est plus aléatoire, mais par type de bloc.
- Scénario : Possibilité en faisant un Ctrl + clic sur le bouton *exécution* de le sauvegarder, le lancer, et afficher le log (si le niveau de log n'est pas sur *Aucun*).
- Scénario : Confirmation de suppression d'un bloc. Ctrl + clic pour éviter la confirmation.
- Scénario : Ajout d'une fonction recherche dans les bloc Code. Rechercher : Ctrl + F puis Enter, Résultat suivant : Ctrl + G, Résultat précédent : Ctrl + Shift + G
- Scénario : Possibilité de condenser les blocs.
- Scénario : L'action 'Ajouter bloc' bascule sur l'onglet Scénario si nécessaire.
- Scénario : Nouvelles fonctions copier/coller de bloc. Ctrl+clic pour couper/remplacer.
- Scénario : Un nouveau bloc n'est plus ajouté à la fin du scénario, mais après le bloc où vous étiez avant de cliquer, déterminé par le dernier champ dans lequel vous avez cliqué.
- Scénario : Mise en place d'un système d'Undo/Redo (Ctrl+Shift+Z / Ctrl+Shift+Y).
- Scénario : Suppression du partage de scénario.
- Scénario : Amélioration de la fenêtre de gestion des templates de scénario.<br/><br/>
- Analyse / Equipements : Ajout d'un moteur de recherche (onglet Batteries, recherche sur les noms et parents).
- Analyse / Equipements : La zone calendrier/jours de l'équipement est maintenant cliquable pour accéder directement au changement de pile(s).
- Analyse / Equipements : Ajout d'un champ de recherche.<br/><br/>
- Centre de mise à jour : Warning sur l'onglet 'Core et plugins' et/ou 'Autres' si une update est disponible. Bascule sur 'Autres' si nécessaire.
- Centre de mise à jour : différentiation par version (stable, beta, ...).
- Centre de mise à jour : ajout d'une barre de progression pendant l'update.<br/><br/>
- Résumé domotique : L'historique des suppressions est maintenant disponible dans un onglet (Résumé - Historique).
- Résumé domotique : Refonte complète, possibilité d'ordonner les objets, équipements, commandes.
- Résumé domotique : Ajout des IDs d'équipement et de commande, à l'affichage et dans la recherche.
- Résumé domotique : Export CSV des objet parent,id,équipement et de leurs id,commande.
- Résumé domotique : Possibilité de rendre visible ou non une ou des commandes.<br/><br/>
- Design : Possibilité de spécifier l'ordre (position) des *Designs* et *Designs 3D* (Edition, Configurer le Design).
- Design : Ajout d'un champs CSS personnalisé sur les éléments du *design*.
- Design : Déplacement des options d'affichages en Design de la configuration avancée, dans les paramètres d'affichage depuis le *Design*. Ceci afin de simplifier l'interface, et de permettre d'avoir des paramètres différents par *Design*.
- Design : Le déplacement et le redimensionnement des composants sur les *Design* tient compte de leur taille, avec ou sans aimantation.<br/><br/>
- Allègement général (css / inline styles, refactoring, etc.) et améliorations des performances.
- Suppression de Font Awesome 4 pour ne conserver que Font Awesome 5.
- Mise à jour des libs : jquery 3.4.1 , CodeMiror 5.46.0, tablesorter 2.31.1.
- Nombreuses corrections de bugs.
- Ajout d'un système de configuration en masse (utilisé sur la page Equipement pour configurer l'Alertes Communications sur ceux-ci)
- Ajout de la compatibilité global du DNS Jeedom avec une connexion internet 4G.
- Correction de sécurité

>**IMPORTANT**
>
>Si après la mise à jour vous avez une erreur sur le Dashboard essayez de redémarrer votre box pour qu'elle prenne bien les nouveaux ajout de composants en compte.

>**IMPORTANT**
>
>Le plugin widget n'est pas compatible avec cette version de Jeedom et ne sera plus supporté (car les fonctionnalités ont été reprise en interne sur le core). Plus d'informations [ici](https://www.Jeedom.com/blog/4368-les-widgets-en-v4).

