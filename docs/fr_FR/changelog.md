
Changelog
=========

4.1.0
=====
- Dashboard : Mode Édition maintenant en insertion de la tuile déplacée.
- Dashboard : On peut maintenant cliquer sur les *time* des widgets actions time pour ouvrir la fenêtre d'historique de la commande info liée.
- Dashboard : La taille de la tuile d'un nouvel équipement s'adapte à son contenu.
- Aperçu : Ajout d'une nouvelle page **Accueil → Aperçu** proposant un aperçu visuel global des pièces.
- Centre de mise à jour : La vérification des mises à jour se fait automatiquement à l'ouverture de la page si plus ancienne de 120mins.
- Centre de mise à jour : La barre de progression est maintenant sur l'onglet *Core et plugins*, et le log ouvert par défaut sur l'onglet *Informations*.
- Mises à jour du Core : Mise en place d'un système de nettoyage des anciens fichiers non utilisés du Core.
- Pages Widgets / Objets / Scénarios / Interactions / Plugins :
	- Ctrl Clic / Clic Centre sur un Widget, Objet, Scénarios, Interaction, équipement de plugin : Ouvre dans un nouvel onglet.
	- Ctrl Clic / Clic Centre également disponible dans leurs menus contextuels (sur les onglets).
- Nouvelle page ModalDisplay:
	- Menu Analyse : Ctrl Clic / Clic Centre sur *Temps réel* : Ouvre la fenêtre dans un nouvel onglet, en pleine page.
	- Menu Outils : Ctrl Clic / Clic Centre sur *Notes*, *Testeur expression*, *Variables* : Ouvre la fenêtre dans un nouvel onglet, en pleine page.
- Scénario : Ajout d'un moteur de recherche (à gauche du bouton Exécuter).
- Scénario : Ajout de la fonction age (donne l'âge de la valeur de la commande).
- Scénario : *stateChanges()* accepte maintenant la période *today* (de minuit à maintenant), *yesterday* et *day* (pour 1 day).
- Scénario : Fonctions *statistics(), average(), max(), min(), tendance(), duration()* : Bugfix sur la période *yesterday*, et accepte maintenant *day* (pour 1 day).
- Scénario : Possibilité de désactiver le système de quote automatique (Réglages → Système → Configuration : Commandes).
- Analyse / Historique : Ctrl Clic sur une légende pour afficher seulement cet historique, Alt Clic pour les afficher tous.
- Analyse / Historique : Les options *groupement, type, variation, escalier* sont actives seulement avec une seule courbe affichée.
- Analyse / Historique : On peut maintenant utiliser l'option *Aire* avec l'option *Escalier*.
- Dashboard : Ctrl Clic sur une info ouvre la fenêtre d'historique avec toutes les commandes historisées de l'équipement visible sur la tuile. Ctrl Clic sur une légende pour afficher seulement celle-ci, Alt Clic pour les afficher toutes.
- Vue : possibilité de mettre des scénarios.
- Intégration de la Timeline en DB pour des raisons de fiabilité.
- Gestion de Timelines multiples.
- Résumé domotique : Les équipements de plugins désactivés et leurs commandes n'ont plus les icônes de droite (configuration de l'équipement et configuration avancée).
- Moteur de tâches : Sur l'onglet *Démon*, les plugins désactivés n’apparaissent plus.
- Fenêtre A propos : Ajout de raccourcis vers le Changelog et la FAQ.
- Documentation : Adaptations en adéquation avec la v4 et v4.1.
- Documentation : Nouvelle page *Raccourcis clavier / souris* comprenant un récapitulatif de tous les raccourcis dans Jeedom. Accessible depuis la doc du Dashboard ou la FAQ.
- Corrections de bugs et optimisations.


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
- Ajout d'un système de configuration en masse (utilisé sur la page Equipement pour configurer l’Alerte Communication sur ceux-ci)

>**IMPORTANT**
>
>Si après la mise à jour vous avez une erreur sur le Dashboard essayez de redémarrer votre box pour qu'elle prenne bien les nouveaux ajout de composants en compte.

>**IMPORTANT**
>
>Le plugin widget n'est pas compatible avec cette version de Jeedom et ne sera plus supporté (car les fonctionnalités ont été reprise en interne sur le core). Plus d'informations [ici](https://www.Jeedom.com/blog/4368-les-widgets-en-v4).

3.3.37
=====

- Correction de bugs.

3.3.36
=====

- Ajout d'un arrondi sur le nombre de jours depuis le dernier changement de pile.
- Correction de bugs

3.3.35
=====

- Correction de bugs.
- Possibilité d'installer les plugins directement depuis le market en ligne.

3.3.34
=====

- Correction d'un bug pouvant empêcher la remonter de l'état des batteries.
- Correction d'un bug sur les tags dans les interactions.
- Le status "timeout" (non communication) des équipements est maintenant prioritaire sur le statut en warning ou danger.
- Correction de bug sur les backups cloud.


3.3.33
=====

- Correction de bugs.

3.3.32
=====

- Correction de bugs
- Support en mobile des sliders sur les designs.
- SMART : optimisation de la gestion du swap.

3.3.31
=====

- Correction de bugs.

3.3.30
=====

- Correction d'un bug sur l'affichage des sessions utilisateur.
- Mise à jour de la documentation.
- Suppression de la mise à jour des graphiques en temps réel, suite aux nombreux bugs remontés.
- Correction d'un bug pouvant empêcher l'affichage de certains logs.
- Correction d'un bug sur le service de monitoring.
- Correction d'un bug sur la page "Analyse équipement", la date de mise à jour de la batterie est maintenant correcte.
- Amélioration de l'action *remove_inat* dans les scénarios.

3.3.29
=====

- Correction de la disparition de la date de dernière vérification des mises à jour.
- Correction d'un bug pouvant bloquer les backups cloud.
- Correction d'un bugs sur le calcul d'utilisation des variable si c'est de la forme variable(toto,mavaleur).

3.3.28
=====

- Correction d'un bug de roue infini sur la page des mises à jour.
- Corrections et optimisations diverses.

3.3.27
=====

- Correction d'un bug sur la traduction des jours en français.
- Amélioration de la stabilité (redémarrage auto du service MySql et watchdog de vérification de l'heure au démarrage).
- Correction de bugs.
- Désactivation des actions sur les commandes lors de l'édition des designs, vue ou Dashboard.

3.3.26
=====

- Correction de bugs.
- Correction d'un bug sur le multi-lancement de scénario.
- Correction d'un bug sur les alertes sur la valeur des commandes.

3.3.25
=====
- Correction de bug.
- Passage de la timeline en mode tableau (du à des erreurs dans la lib indépendante de Jeedom).
- Ajout des classes pour les supports de la couleur dans le plugin mode.

3.3.24
=====
-   Correction d'un bug sur l'affichage du nombre de mise à jour.
-	Suppression de l'édition du code html depuis la configuration avancée des commandes dû à de trop nombreux bugs.
-	Corrections de bugs.
-	Amélioration de la fenêtre de choix des icônes
-	Mise à jour automatique de la date de changement de batterie si la batterie est à plus de 90% et supérieure de 10% à la valeur précédente.
-	Ajout de bouton sur l'administration pour remettre à plat les droits et lancer une vérification de Jeedom (droit, cron, base de données...).
-	Suppression des choix de visibilité avancé des équipements sur Dashboard/vue/design/mobile. Maintenant si vous voulez voir ou pas l’équipement sur Dashboard/mobile il suffit de cocher ou pas la case de visibilité général. Pour les vues et design il suffit de mettre ou pas l'équipement dessus.

3.3.22
=====
- Corrections de bugs.
- Amélioration du remplacement des commandes (dans les vues, plan et plan3d).
- Correction d'un bug pouvant empêcher d'ouvrir certains équipements de plugins (type alarme ou virtuel).

3.3.21
=====
- Correction d'un bug où l'affichage de l'heure pouvait dépasser 24h.
- Correction d'un bug sur la mise à jour des résumés en design.
- Correction d'un bug sur la gestion des niveaux d'alertes sur certains widgets lors de la mise à jour de la valeur.
- Correction de l'affichage des équipements désactivés sur certains plugins.
- Correction d'un bug lors de l'indication de changement de pile à Jeedom.
- Amélioration de l'affichage des logs lors de la mise à jour de Jeedom.
- Correction de bug lors de la mise à jour de variable (qui ne lançait pas toujours les scénarios ou ne déclenchait pas une mise à jour des commandes dans tous les cas).
- Correction d'un bug sur les backups Cloud, ou duplicity ne s'installait pas correctement.
- Amélioration du TTS interne à Jeedom.
- Amélioration du système de vérification de syntaxe cron.


3.3.20
=====
- Correction d'un bug sur les scénarios ou ceux-ci pouvaient rester bloqués à "en cours" alors qu'ils sont désactivés.
- Correction d'un souci de lancement de scénario non planifié.
- Correction de bug lié au fuseau horaire.

3.3.19
=====
- Correction de bugs (en particulier lors de l'update).


3.3.18
=====
- Correction de bugs.

3.3.17
=====
- Correction d'une erreur sur les backups samba.

3.3.16
=====
- Possibilité de supprimer une variable.
- Ajout d'un affichage 3D (beta).
- Refonte du système de backup cloud (backup incrémental et chiffré).
- Ajout d'un système de prise de note intégré (dans Analyse -> Note).
- Ajout de la notion de tag sur les équipements (se trouve dans la configuration avancée de l'équipement).
- Ajout d'un système d'historique sur la suppression des commandes, équipements, objets, vue, design, design 3d, scénario et utilisateur.
- Ajout de l'action Jeedom_reboot pour lancer un redémarrage de Jeedom.
- Ajout d'option dans la fenêtre de génération de cron.
- Un message est maintenant ajouté si une expression invalide est trouvée lors de l’exécution d'un scénario.
- Ajout d'une commande dans les scénarios : value(commande) permet d'avoir la valeur d'une commande si elle n'est pas donnée automatiquement par Jeedom (cas lors du stockage du nom de la commande dans une variable).
- Ajout d'un bouton pour rafraichir les messages du centre message.
- Ajout dans la configuration d'action sur valeur d'une commande un bouton pour chercher une action interne (scénario, pause...).
- Ajout d'un action "Remise à zero des SI" sur les scénarios.
- Possibilité d'ajouter des images en fond sur les vues.
- Possibilité d'ajouter des images en fond sur les objets.
- L'information de mise à jour disponible est maintenant masquée aux utilisateurs non admin.
- Amélioration du support des () dans le calcul d'expressions.
- Possibilité d'éditer les scénarios en mode text/json.
- Ajout sur la page santé d'une vérification de l'espace libre pour le tmp Jeedom.
- Possibilité d'ajouter des options dans les rapports.
- Ajout d'un heartbeat par plugin et de redémarrage automatique de démon en cas de soucis.
- Ajout des listeners sur la page de moteur de tâche.
- Optimisations.
- Possibilité de consulter les logs en version mobile (wepapp).
- Ajout d'une action tag dans les scénarios (voir documentation).
- Possibilité d'avoir une vue en pleine écran en ajoutant "&fullscreen=1" dans l'url.
- Ajout de lastCommunication dans les scénarios (pour avoir la date de dernière communication d'un équipement).
- Mise à jour en temps réel des graphiques (simple, pas ceux calculé ou les timelines).
- Possibilité de supprimer un élément à partir de la configuration du design.
- Possibilité d'avoir un rapport sur le niveau de batterie (rapport équipement).
- Les widgets scénarios sont maintenant affiché par défaut sur le Dashboard.
- Changement du pas des widgets par horizontal 25 à 40, vertical 5 à 20 et marge 1 à 4 (vous pouvez remettre les anciennes valeurs dans la configuration de Jeedom, onglet widget).
- Possibilité de mettre une icône sur les scénarios.
- Affichage des widgets mobile en une seule colonne.
- Ajout de la gestion des démons sur le moteur de tâches.
- Ajout de la fonction color_gradient dans les scénarios.

3.2.16
=====
- Correction d'un bug lors de l'installation des dépendances de certains plugins sur Smart.

3.2.15
=====
- Correction d'un bug lors de la sauvegarde d'un équipement.

3.2.14
=====
- Préparation pour éviter une erreur lors du passage en 3.3.X.
- Correction d'un soucis lors d'une demande de support pour les plugins tierces.

3.2.12
=====
- Correction de bugs.
- Optimisations.

3.2.11
=====
- Correction de bugs.

3.2.10
=====
- Correction de bugs.
- Amélioration de la synchronisation avec le Market.
- Amélioration du processus d'update en particulier au niveau de la copie des fichiers qui vérifie maintenant la taille du fichier copié.
- Correction de bugs sur les fonctions stateDuration, lastStateDuration et lastChangeStateDuration (merci @kiboost).
- Optimisation du calcul du graphique de liens et de l'utilisation des variables.
- Amélioration de la fenêtre de détails des taches cron qui affiche maintenant pour les taches doIn le scénario ainsi que l'action qui sera faite (merci @kiboost).

3.2.9
=====
- Correction de bugs.
- Correction d'un bug sur les icônes de l'éditeur de fichier et sur le testeur d'expression.
- Correction de bugs sur les listenners.
- Ajout d'une alerte si un plugin bloque les crons.
- Correction d'un bug dans le système de monitoring cloud si la version de l'agent est inférieure à 3.X.X.

3.2.8
=====
- Correction de bugs.
- Ajout d'une option dans l'administration de Jeedom pour préciser la plage d'ip local (utile dans les installations type docker).
- Correction d'un bug sur le calcul d'utilisation des variables.
- Ajout d'un indicateur sur la page santé donnant le nombre de processus tué par manque de mémoire (indique globalement que le Jeedom est trop chargé).
- Amélioration de l'éditeur de fichier.

3.2.7
=====
- Correction de bugs.
- Mise à jour de la docs.
- Possibilité d'utiliser les tags dans les conditions des blocs "A" et "DANS".
- Correction du bugs des catégories market pour les widgets/scripts/scénarios...

3.2.6
=====
- Correction de bugs.
- Mise à jour de la docs.
- Uniformisation des noms de certaine commande dans les scénarios.
- Optimisation des performances.

3.2.5
=====
- Correction de bugs.
- Réactivation des interactions (inactive a cause de la mise à jour).

3.2.4
=====
- Correction de bugs.
- Correction d'un bugs sur certaine modale en Espagnol.
- Correction d'une erreur de calcul sur time_diff.
- Préparation pour le futur système d'alerting.

3.2.3
=====
- Bugfix sur les fonctions min/max.
- Amélioration de l'export des graphiques et de l'affichage en mode table.

3.2.2
=====
- Suppression de l'ancien système de mise à jour des widget (déprécié depuis la version 3.0). Attention si votre widget n'utilise pas le nouveau système il y a des risques de dysfonctionnement (.dédoublement de celui-ci en l’occurrence). Exemple de widget [ici](https://github.com/Jeedom/core/tree/beta/core/template/Dashboard)
- Possibilité d'afficher les graphiques sous forme de tableau ou d'exporter ceux-ci en csv ou xls.
- Les utilisateurs peuvent maintenant ajouter leur propre fonction php pour les scénarios. Voir documentation des scénarios pour la mise en place.
- JEED-417 : ajout d'une fonction time_diff dans les scénarios.
- Ajout d'un délai configurable avant réponse sur les interactions (permet d'attendre que le retour d'état se fasse par exemple).
- JEED-365 : Suppression de "Commande d’information utilisateur" pour être remplacé par des actions sur message. Permet de lancer plusieurs commande différentes, de lancer un scénario... Attention .si vous aviez une "Commande d’information utilisateur" il faut la reconfigurer.
- Ajout d'une option permettant d'ouvrir facilement un accès pour le support (sur la page utilisateur et lors de l'ouverture d'un ticket).
- Correction d'un bug de droits suite à une restauration d'un backup.
- Mise à jour des traductions.
- Mise à jour des librairies (jquery et highcharts).
- Possibilité d’interdire une commande dans les interactions automatique.
- Amélioration des interactions automatique.
- Correction de bug sur la gestion des synonyme des interactions.
- Ajout d’un champs recherche utilisateur pour les connexion LDAP/AD (permet de rendre Jeedom compatible AD).
- Corrections d’orthographe (merci à dab0u pour son énorme travail).
- JEED-290 : On ne peut plus se connecter avec les identifiants par défaut (admin/admin) à distance, seul le réseau local est autorisé.
- JEED-186 : On peut maintenant choisir la couleur de fond dans les designs.
- Pour le bloc A, possibilité de mettre une heure entre 00h01 et 00h59 en mettant simplement les minutes (ex 30 pour 00h30).
- Ajout des sessions actives et des périphériques enregistrés sur la page de profil de l’utilisateur et la page de gestion des utilisateurs.
- JEED-284 : la connexion permanente dépend maintenant d’une clef unique utilisateur et périphérique (et non plus que utilisateur).
- JEED-283 : ajout d’un mode *rescue* à Jeedom en rajoutant &rescue=1 dans l’url.
- JEED-8 : ajout du nom du scénario sur le titre de la page lors de l’édition.
- Optimisation des modifications d’organisation (taille des widgets, position des équipements, position des commandes) sur le Dashboard et les vue. Attention maintenant les modifications ne sont .sauvegardées que lorsque l’on quitte le mode édition.
- JEED-18 : Ajout des logs lors de l’ouverture d’un ticket au support.
- JEED-181 : ajout d’une commande name dans les scénarios pour avoir le nom de la commande ou de l’équipement ou de l’objet.
- JEED-15 : Ajout des batterie et alerte sur la webapp.
- Correction du bugs de déplacement des objets du design sous Firefox.
- JEED-19 : Lors d’une mise à jour il est maintenant possible de mettre à jour le script d’update avant la mise à jour.
- JEED-125 : ajout d’un lien vers la documentation de réinitialisation de mot de passe.
- JEED-2 : Amélioration de la gestion de l’heure lors d’un redémarrage.
- JEED-77 : Ajout de la gestion des variables dans l’API http.
- JEED-78 : ajout de la fonction tag pour les scénarios. Attention il faut dans les scénarios utilisant les tags passer de \#montag\# à tag(montag).
- JEED-124 : Corriger la gestion des timeouts des scénarios.
- Correction de bugs.
- Possibilité de désactiver une interaction.
- Ajout d’un éditeur de fichiers (réservé aux utilisateurs expérimentés).
- Ajout des génériques Types "Lumière Etat" (Binaire), "Lumière Température Couleur" (Info), "Lumière Température Couleur" (Action).
- Possibilité de rendre des mots obligatoires dans une interaction.

3.1.7
=====
- Correction de bugs (en particulier sur les historiques et fonctions statistiques).
- Amélioration du système de mises à jour avec une page de notes de version (que vous devez vérifier vous même avant chaque mise à jour.).
- Correction d’un bug qui récupérait les logs lors des restaurations.

3.1
===
- Correction de bugs.
- Optimisation globale de Jeedom (sur le chargement des classes de plugins, temps presque divisé par 3).
- Support de Debian 9.
- Mode onepage (changement de page sans recharger toute la page, juste la partie qui change).
- Ajout d’une option pour masquer les objets sur le Dashboard mais qui permet de toujours les avoir dans la liste.
- Un double-clic sur un nœud sur le graphique de lien (sauf pour les variables) amène sur sa page de configuration.
- Possibilité de mettre le texte à gauche/droit/au centre sur les designs pour les éléments de type texte/vue/design.
- Ajout des résumés d’objets sur le Dashboard (liste des objets à gauche).
- Ajout des interactions de type "previens-moi-si".
- Revue de la page d’accueil des scénarios.
- Ajout d’un historique de commandes pour les commandes SQL ou système dans l’interface de Jeedom.
- Possibilité d’avoir les graphiques d’historiques des commandes en webapp (par appui long sur la commande).
- Ajout de l’avancement de l’update de la webapp.
- Reprise en cas d’erreur de mise à jour de la webapp.
- Suppression des scénarios "simples" (redondant avec la configuration avancée des commandes).
- Ajout de hachure sur les graphs pour distinguer les jours.
- Refonte de la page des interactions.
- Refonte de la page profils.
- Refonte de la page d’administration.
- Ajout d’une "santé" sur les objets.
- Correction de bug sur le niveau de batterie des équipements.
- Ajout de méthode dans le core pour la gestion des commandes mortes (doit être ensuite implémentée dans le plugin).
- Possibilité d’historiser des commandes de type texte.
- Sur la page historique vous pouvez maintenant faire le graphique d’un calcul.
- Ajout d’une gestion de formule de calcul pour les historiques.
- Remise à jour de toute la documentation :.
    - Toute les docs ont été revues.
    - Suppression des images pour faciliter la mise à jour et le multilingue.
- Plus de choix possibles sur les réglage des tailles de zone dans les vues.
- Possibilité de choisir la couleur du texte du résumé d’objet.
- Ajout d’une action remove\_inat dans les scénarios permettant d’annuler toutes les programmations des bloc DANS/A.
- Possibilité dans les designs pour les widgets au survol de choisir la position du widget.
- Ajout d’un paramètre reply\_cmd sur les interactions pour spécifier l’id de la commande à utiliser pour répondre.
- Ajout d’une timeline sur la page historique (attention doit être activée sur chaque commande et/ou scénario que vous voulez voir apparaitre).
- Possibilité de vider les évènements de la timeline.
- Possibilité de vider les IPs bannies.
- Correction/amélioration de la gestion des comptes utilisateurs.
    - Possibilité de supprimer le compte admin de base.
    - Prévention du passage en normal du dernier administrateur.
    - Ajout d’une sécurité pour éviter la suppression du compte avec lequel on est connecté.
- Possibilité dans la configuration avancé des équipements de mettre la disposition des commandes dans le widgets en mode table en choisissant pour chaque commande la case ou la mettre.
- Possibilité de réorganiser les widgets des équipements depuis le Dashboard (en mode édition clic droit sur le widget).
- Changement du pas des widgets (de 40\*80 à 10\*10). Attention cela va impacter la disposition sur votre Dashboard/vue/design.
- Possibilité de donner une taille de 1 à 12 aux objets sur le Dashboard.
- Possibilité de lancer indépendamment les actions des scénarios (et plugin type mode/alarm si compatible) en parallèle des autres.
- Possibilité d’ajouter un code d’accès à un design.
- Ajout d’un watchdog indépendant de Jeedom pour vérifier le status de MySql et Apache.

3.0.11
======
- Correction de bugs sur les demandes "ask" en timeout.

3.0.10
======
- Correction de bugs sur l’interface de configuration des interactions.

3.0
===
- Suppression du mode esclave.
- Possibilité de déclencher un scénario sur un changement d’une variable.
- Les mises à jour de variables déclenchent maintenant la mise à jour des commandes d’un équipement virtuel (il faut la dernière version du plugin).
- Possibilité d’avoir une icône sur les commandes de type info.
- Possibilité sur les commandes d’afficher le nom et l’icône.
- Ajout d’une action "alert" sur les scénarios : message en haut dans Jeedom.
- Ajout d’une action "popup" sur les scénarios : message à valider.
- Les widgets des commandes peuvent maintenant avoir une méthode d’update ce qui évite un appel ajax à Jeedom.
- Les widgets des scénarios sont maintenant mis à jour sans appel ajax pour avoir le widget.
- Le résumé global et des pièces sont maintenant mis à jour sans appel ajax.
- Un clic sur un élément d’un résumé domotique vous amène sur une vue détaillée de celui-ci.
- Vous pouvez maintenant mettre dans les résumés des commandes de type texte.
- Changement des bootstraps slider en slider (correction du bug du double événement des sliders).
- Sauvegarde automatique des vues lors du clic sur le bouton "voir le résultat".
- Possibilité d’avoir les docs en local.
- Les développeurs tiers peuvent ajouter leur propre système de gestion de tickets.
- Refonte de la configuration des droits utilisateurs (tout est sur la page de gestion des utilisateurs).
- Mise à jour des libs : jquery (en 3.0) , jquery mobile, hightstock et table sorter, font-awesome.
- Grosse amélioration des designs:.
    - Toutes les actions sont maintenant accessibles à partir d’un clic droit.
    - Possibilité d’ajouter une commande seule.
    - Possibilité d’ajouter une image ou un flux vidéo.
    - Possibilité d’ajouter des zones (emplacement cliquable) :.
        - Zone de type macro : lance une série d’actions lors d’un clic dessus.
        - Zone de type binaire : lance une série d’actions lors d’un clic dessus en fonction de l’état d’une commande.
        - Zone de type widget : affiche un widget au clic ou au survol de la zone.
    - Optimisation générale du code.
    - Possibilité de faire apparaître une grille et de choisir sa taille (10x10,15x15 ou 30x30).
    - Possibilité d’activer une aimantation des widgets sur la grille.
    - Possibilité d’activer une aimantation des widgets entre eux.
    - Certains types de widgets peuvent maintenant être dupliqués.
    - Possibilité de verrouiller un élément.
- Les plugins peuvent maintenant utiliser une clef api qui leur est propre.
- Ajout d’interactions automatiques, Jeedom va essayer de comprendre la phrase, d’exécuter l’action et de répondre.
- Ajout de la gestion des démons en version mobile.
- Ajout de la gestion des crons en version mobile.
- Ajout de certaines informations de santé en version mobile.
- Ajout sur la page batterie des modules en alerte.
- Les objets sans widget sont automatiquement masqués sur le Dashboard.
- Ajout d’un bouton dans la configuration avancée d’un équipement/d’une commande pour voir les événements de celui-ci/celle-ci.
- Les déclencheurs d’un scénario peuvent maintenant être des conditions.
- Un double clic sur la ligne d’une commande (sur la page de configuration) ouvre maintenant la configuration avancée de celle-ci.
- Possibilité d’interdire certaines valeurs pour une commande (dans la configuration avancée de celle-ci).
- Ajout des champs de configuration sur le retour d’état automatique (ex revenir à 0 au bout de 4min) dans la configuration avancée d’une commande.
- Ajout d’une fonction valueDate dans les scénarios (voir documentation des scénarios).
- Possibilité dans les scénarios de modifier la valeur d’une commande avec l’action "event".
- Ajout d’un champ commentaire sur la configuration avancée d’un équipement.
- Ajout d’un système d’alerte sur les commandes avec 2 niveaux : alerte et danger. La configuration se trouve dans la configuration avancée des commandes (de type info seulement bien sûr). Vous .pouvez voir les modules en alerte sur la page Analyse → Equipements. Vous pouvez configurer les actions sur alerte sur la page de configuration générale de Jeedom
- Ajout d’une zone "tableau" sur les vues qui permet d’afficher une ou plusieurs colonnes par case. Les cases supportent aussi le code HTML.
- Jeedom peut maintenant tourner sans les droits root (expérimental). Attention car sans les droits root vous devrez manuellement lancer les scripts pour les dépendances des plugins.
- Optimisation du calcul des expressions (calcul des tags uniquement si présents dans l’expression).
- Ajout dans l’API de fonction pour avoir accès au résumé (global et d’objet).
- Possibilité de restreindre l’accès de chaque clef api en fonction de l’IP.
- Possibilité sur l’historique de faire des regroupements par heure ou année.
- Le timeout sur la commande wait peut maintenant être un calcul.
- Correction d’un bug s’il y a des " dans les paramètres d’une action.
- Passage au sha512 pour le hash des mots de passe (le sha1 étant compromis).
- Correction d’un bug dans la gestion du cache qui le faisait grossir indéfiniment.
- Correction de l’accès à la doc des plugins tiers si ceux-ci n’ont pas de doc en local.
- Les interactions peuvent prendre en compte la notion de contexte (en fonction de la demande précédente ainsi que celle d’avant).
- Possibilité de pondérer les mots en fonction de leur taille pour l’analyse de la compréhension.
- Les plugins peuvent maintenant ajouter des interactions.
- Les interactions peuvent maintenant renvoyer des fichiers en plus de la réponse.
- Possibilité de voir sur la page de configuration des plugins les fonctionnalités de ceux-ci (interact, cron…​) et de les désactiver unitairement.
- Les interactions automatiques peuvent renvoyer les valeurs des résumés.
- Possibilité de définir des synonymes pour les objets, équipements, commandes et résumés qui seront utilisés dans les réponses contextuelles et résumés.
- Jeedom sait gérer plusieurs interactions liées (contextuellement) en une. Elles doivent être séparées par un mot clef (par défaut et). Exemple : "Combien fait-il dans la chambre et dans le salon .?" ou "Allume la lumière de la cuisine et de la chambre."
- Le statut des scénarios sur la page d’édition est maintenant mis à jour dynamiquement.
- Possibilité d’exporter une vue en PDF, PNG, SVG ou JPEG avec la commande "report" dans un scénario.
- Possibilité d’exporter un design en PDF, PNG, SVG ou JPEG avec la commande "report" dans un scénario.
- Possibilité d’exporter un panel d’un plugin en PDF, PNG, SVG ou JPEG avec la commande "report" dans un scénario.
- Ajout d’une page de gestion de rapport (pour les re-télécharger ou les supprimer).
- Correction d’un bug sur la date de dernière remontée d’un événement pour certains plugins (alarme).
- Correction d’un bug d’affichage avec Chrome 55.
- Optimisation du backup (sur un RPi2 le temps est divisé par 2).
- Optimisation de la restauration.
- Optimisation du processus de mise à jour.
- Uniformisation du tmp Jeedom, maintenant tout est dans /tmp/Jeedom.
- Possibilité d’avoir un graph des différentes liaisons d’un scénario, équipement, objet, commande ou variable.
- Possibilité de régler la profondeur des graphiques de lien en fonction de l’objet d’origine.
- Possibilité d’avoir les logs des scénarios en temps réel (ralentit l’exécution des scénarios).
- Possibilité de passer des tags lors du lancement d’un scénario.
- Optimisation du chargement des scénarios et pages utilisant des actions avec option (type configuration du plugin alarme ou mode).

2.4.6
=====
- Amélioration de la gestion de la répétition des valeurs des commandes.

2.4.5
=====
- Correction de bugs.
- Optimisation de la vérification des mises à jour.

2.4
=====
- Optimisation générale.
    - Regroupement de requêtes SQL.
    - Suppression de requêtes inutiles.
    - Passage en cache du pid, état et dernier lancement des scénarios.
    - Passage en cache du pid, état et dernier lancement des crons.
    - Dans 99% des cas plus de requête d’écriture sur la base en fonctionnement nominal (donc hors configuration de Jeedom, modifications, installation, mise à jour…​).
- Suppression du fail2ban (car facilement contournable en envoyant une fausse adresse ip), cela permet d’accélérer Jeedom.
- Ajout dans les interactions d’une option sans catégorie pour que l’on puisse générer des interactions sur des équipements sans catégorie.
- Ajout dans les scénarios d’un bouton de choix d’équipement sur les commandes de type slider.
- Mise à jour de bootstrap en 2.3.7.
- Ajout de la notion de résumé domotique (permet de connaitre d’un seul coup le nombre de lumières à ON, les porte ouvertes, les volets, les fenêtres, la puissance, les détections de mouvement…​). .Tout cela se configure sur la page de gestion des objets
- Ajout de pre et post commande sur une commande. Permet de déclencher tout le temps une action avant ou après une autre action. Peut aussi permettre de synchroniser des équipements pour, par .exemple, que 2 lumières s’allument toujours ensemble avec la même intensité.
- Optimisation des listenner.
- Ajout de modal pour afficher les informations brutes (attribut de l’objet en base) d’un équipement ou d’une commande.
- Possibilité de copier l’historique d’une commande sur une autre commande.
- Possibilité de remplacer une commande par une autre dans tout Jeedom (même si la commande à remplacer n’existe plus).

2.3
=====
- Correction des filtres sur le market.
- Correction des checkbox sur la page d’édition des vues (sur une zone graphique).
- Correction des checkbox historiser, visible et inverser dans le tableau des commandes.
- Correction d’un soucis sur la traduction des javascripts.
- Ajout d’une catégorie de plugin : objet communiquant.
- Ajout de GENERIC\_TYPE.
- Suppression des filtres nouveau et top sur le parcours des plugins du market.
- Renommage de la catégorie par défaut sur le parcours des plugins du market en "Top et nouveauté".
- Correction des filtres gratuit et payant sur le parcours des plugins du market.
- Correction d’un bug qui pouvait amener à une duplication des courbes sur la page d’historique.
- Correction d’un bug sur la valeur de timeout des scénarios.
- correction d’un bug sur l’affichage des widgets dans les vues qui prenait la version Dashboard.
- Correction d’un bug sur les designs qui pouvait utiliser la configuration des widgets du Dashboard au lieu des designs.
- Correction de bugs de la sauvegarde/restauration si le nom du Jeedom contient des caractères spéciaux.
- Optimisation de l’organisation de la liste des generic type.
- Amélioration de l’affichage de la configuration avancée des équipements.
- Correction de l’interface d’accès au backup depuis.
- Sauvegarde de la configuration lors du test du market.
- Préparation à la suppression des bootstrapswtich dans les plugins.
- Correction d’un bug sur le type de widget demandé pour les designs (Dashboard au lieu de dplan).
- correction de bug sur le gestionnaire d’événements.
- passage en aléatoire du backup la nuit (entre 2h10 et 3h59) pour éviter les soucis de surcharge du market.
- Correction du market de widget.
- Correction d’un bug sur l’accès au market (timeout).
- Correction d’un bug sur l’ouverture des tickets.
- Correction d’un bug de page blanche lors de la mise à jour si le /tmp est trop petit (attention la correction prend effet à l’update n+1).
- Ajout d’un tag *Jeedom\_name* dans les scénarios (donne le nom du Jeedom).
- Correction de bugs.
- Déplacement de tous les fichiers temporaire dans /tmp.
- Amélioration de l’envoi des plugins (dos2unix automatique sur les fichiers \*.sh).
- Refonte de la page de log.
- Ajout d’un thème darksobre pour mobile.
- Possibilité pour les développeurs d’ajouter des options de configuration des widget sur les widgets spécifique (type sonos, koubachi et autre).
- Optimisation des logs (merci @kwizer15).
- Possibilité de choisir le format des logs.
- Optimisation diverse du code (merci @kwizer15).
- Passage en module de la connexion avec le market (permettra d’avoir un Jeedom sans aucun lien au market).
- Ajout d’un "repo" (module de connexion type la connexion avec le market) fichier (permet d’envoi un zip contenant le plugin).
- Ajout d’un "repo" github (permet d’utiliser github comme source de plugin, avec système de gestion de mise à jour).
- Ajout d’un "repo" URL (permet d’utiliser URL comme source de plugin).
- Ajout d’un "repo" Samba (utilisable pour pousser des backups sur un serveur samba et récupérer des plugins).
- Ajout d’un "repo" FTP (utilisable pour pousser des backups sur un serveur FTP et récupérer des plugins).
- Ajout pour certain "repo" de la possibilité de récupérer le core de Jeedom.
- Ajout de tests automatique du code (merci @kwizer15).
- Possibilité d’afficher/masquer les panels des plugins sur mobile et ou desktop (attention maintenant par défaut les panels sont masqués).
- Possibilité de désactiver les mises à jour d’un plugin (ainsi que la vérification).
- Possibilité de forcé la versification des mises à jour d’un plugin.
- Légère refonte du centre de mise à jour.
- Possibilité de désactiver la vérification automatique des mises à jour.
- Correction d’un bug qui remettait toute les données à 0 suite à un redémarrage.
- Possibilité de configurer le niveau de log d’un plugin directement sur la page de configuration de celui-ci.
- Possibilité de consulter les logs d’un plugin directement sur la page de configuration de celui-ci.
- Suppression du démarrage en debug des démons, maintenant le niveau de logs du démon est le même que celui du plugin.
- Nettoyage de lib tierce.
- Suppression de responsive voice (fonction dit dans les scénarios qui marchait de moins en moins bien).
- Correction de plusieurs faille de sécurité.
- Ajout d’un mode synchrone sur les scénarios (anciennement mode rapide).
- Possibilité de rentrer manuellement la position des widgets en % sur les design.
- Refonte de la page de configuration des plugins.
- Possibilité de configurer la transparence des widgets.
- Ajout de l’action Jeedom\_poweroff dans les scénarios pour arrêter Jeedom.
- Retour de l’action scenario\_return pour faire un retour à une interaction (ou autre) à partir d’un scénario.
- Passage en long polling pour la mise à jour de l’interface en temps réel.
- Correction d’un bug lors de refresh multiple de widget.
- Optimisation de la mise à jour des widgets commandes et équipements.
- Ajout d’un tag *begin\_backup*, *end\_backup*, *begin\_update*, *end\_update*, *begin\_restore*, *end\_restore* dans les scénarios.

2.2
=====
- Correction de bugs.
- Simplification de l’accès aux configurations des plugins à partir de la page santé.
- Ajout d’une icône indiquant si le démon est démarré en debug ou non.
- Ajout d’une page de configuration globale des historiques (accessible à partir de la page historique).
- Correction de bugs pour docker.
- Possibilité d’autoriser un utilisateur à se connecter uniquement à partir d’un poste sur le réseau local.
- Refonte de la configuration des widgets (attention il faudra sûrement reprendre la configuration de certains widgets).
- Renforcement de la gestion des erreurs sur les widgets.
- Possibilité de réordonner les vues.
- Refonte de la gestion des thèmes.

2.1
=====
- Refonte du système de cache de Jeedom (utilisation de doctrine cache). Cela permet par exemple de connecter Jeedom à un serveur redis ou memcached. Par défaut Jeedom utilise un système de fichiers (et non plus la BDD MySQL ce qui permet de la décharger un peu), celui-ci se trouve dans /tmp il est donc conseillé si vous avez plus de 512 Mo de RAM de monter le /tmp en tmpfs (en RAM pour plus de rapidité et une diminution de l’usure de la carte SD, je recommande une taille de 64mo). Attention lors du redémarrage de Jeedom le cache est vidé il faut donc attendre pour avoir la remontée de toutes les infos.
- Refonte du système de log (utilisation de monolog) qui permet une.
intégration à des systèmes de logs (type syslog(d))
- Optimisation du chargement du Dashboard.
- Correction de nombreux warning.
- Possibilité lors d’un appel api à un scénario de passer des tags dans l’url.
- Support d’apache.
- Optimisation pour docker avec support officiel de docker.
- Optimisation pour les synology.
- Support + optimisation pour php7.
- Refonte des menus Jeedom.
- Suppression de toute la partie gestion réseau : wifi, ip fixe… (reviendra sûrement sous forme de plugin). ATTENTION ce n’est pas le mode maître/esclave de Jeedom qui est supprimé.
- Suppression de l’indication de batterie sur les widgets.
- Ajout d’une page qui résume le statut de tous les équipements sur batterie.
- Refonte du DNS Jeedom, utilisation d’openvpn (et donc du plugin openvpn).
- Mise à jour de toutes les libs.
- Interaction : ajout d’un système d’analyse syntaxique (permet de supprimer les interactions avec de grosses erreurs de syntaxe type « le chambre »).
- Suppression de la mise à jour de l’interface par nodejs (passage en pulling toutes les secondes sur la liste des événements).
- Possibilité pour les applications tierces de demander par l’api les événements.
- Refonte du système « d’action sur valeur » avec possibilité de faire plusieurs actions et aussi l’ajout de toutes les actions possibles dans les scénarios (attention il faudra peut-être toutes les .reconfigurer suite à la mise à jour)
- Possibilité de désactiver un bloc dans un scénario.
- Ajout pour les développeurs d’un système d’aide tooltips. Il faut sur un label mettre la classe « help » et mettre un attribut data-help avec le message d’aide souhaité. Cela permet à Jeedom d’ajouter automatiquement à la fin de votre label une icône « ? » et au survol d’afficher le texte d’aide.
- Changement du processus de mise à jour du core, on ne demande plus l’archive au Market mais directement à Github maintenant.
- Ajout d’un système centralisé d’installation des dépendances sur les plugins.
- Refonte de la page de gestion des plugins.
- Ajout des adresses mac des différentes interfaces.
- Ajout de la connexion en double authentification.
- Suppression de la connexion par hash (pour des raisons de sécurité).
- Ajout d’un système d’administration OS.
- Ajout de widgets standards Jeedom.
- Ajout d’un système en beta pour trouver l’IP de Jeedom sur le réseau (il faut connecter Jeedom sur le réseau, puis aller sur le market et cliquer sur « Mes Jeedoms » dans votre profil).
- Ajout sur la page des scénarios d’un testeur d’expression.
- Revue du système de partage de scénario.

2.0
=====
- Refonte du système de cache de Jeedom (utilisation de doctrine cache). Cela permet par exemple de connecter Jeedom à un serveur redis ou memcached. Par défaut Jeedom utilise un système de fichiers (et non plus la BDD MySQL ce qui permet de la décharger un peu), celui-ci se trouve dans /tmp il est donc conseillé si vous avez plus de 512 Mo de RAM de monter le /tmp en tmpfs (en RAM pour plus de rapidité et une diminution de l’usure de la carte SD, je recommande une taille de 64mo). Attention lors du redémarrage de Jeedom le cache est vidé il faut donc attendre pour avoir la remontée de toutes les infos.
- Refonte du système de log (utilisation de monolog) qui permet une intégration à des systèmes de logs (type syslog(d)).
- Optimisation du chargement du Dashboard.
- Correction de nombreux warning.
- Possibilité lors d’un appel api à un scénario de passer des tags dans l’url.
- Support d’apache.
- Optimisation pour docker avec support officiel de docker.
- Optimisation pour les synology.
- Support + optimisation pour php7.
- Refonte des menus Jeedom.
- Suppression de toute la partie gestion réseau : wifi, ip fixe… (reviendra sûrement sous forme de plugin). ATTENTION ce n’est pas le mode maître/esclave de Jeedom qui est supprimé.
- Suppression de l’indication de batterie sur les widgets.
- Ajout d’une page qui résume le statut de tous les équipements sur batterie.
- Refonte du DNS Jeedom, utilisation d’openvpn (et donc du plugin openvpn).
- Mise à jour de toutes les libs.
- Interaction : ajout d’un système d’analyse syntaxique (permet de supprimer les interactions avec de grosses erreurs de syntaxe type « le chambre »).
- Suppression de la mise à jour de l’interface par nodejs (passage en pulling toutes les secondes sur la liste des événements).
- Possibilité pour les applications tierces de demander par l’api les événements.
- Refonte du système « d’action sur valeur » avec possibilité de faire plusieurs actions et aussi l’ajout de toutes les actions possibles dans les scénarios (attention il faudra peut-être toutes les .reconfigurer suite à la mise à jour)
- Possibilité de désactiver un bloc dans un scénario.
- Ajout pour les développeurs d’un système d’aide tooltips. Il faut sur un label mettre la classe « help » et mettre un attribut data-help avec le message d’aide souhaité. Cela permet à Jeedom .d’ajouter automatiquement à la fin de votre label une icône « ? » et au survol d’afficher le texte d’aide
- Changement du processus de mise à jour du core, on ne demande plus l’archive au Market mais directement à Github maintenant.
- Ajout d’un système centralisé d’installation des dépendances sur les plugins.
- Refonte de la page de gestion des plugins.
- Ajout des adresses mac des différentes interfaces.
- Ajout de la connexion en double authentification.
- Suppression de la connexion par hash (pour des raisons de sécurité).
- Ajout d’un système d’administration OS.
- Ajout de widgets standards Jeedom.
- Ajout d’un système en beta pour trouver l’IP de Jeedom sur le réseau (il faut connecter Jeedom sur le réseau, puis aller sur le market et cliquer sur « Mes Jeedoms » dans votre profil).
- Ajout sur la page des scénarios d’un testeur d’expression.
- Revue du système de partage de scénario.
