# Changelog Jeedom V4.2

## 4.2.0

### 4.2 : Pré-requis

- Debian 10 Buster
- Php 7.3

### 4.2 : Nouveautés / Améliorations

- **Synthèse** : Possibilité de paramétrage des objets pour aller vers un *design* ou une *vue* depuis la synthèse.
- **Dashboard** : La fenêtre de configuration d'un équipement (mode édition) permet maintenant de configurer les widgets mobile et les types génériques.
- **Widgets** : Internationalisation des Widgets tiers (code utilisateur). voir [Doc dev](https://doc.jeedom.com/fr_FR/dev/core4.2).
- **Analyse / Historique** : Possibilité de comparer un historique sur une période donnée.
- **Analyse / Historique** : Affichage des axes multiples en Y avec leur propre échelle.
- **Analyse / Equipements** : Les commandes orphelines affichent maintenant leur nom et date de suppression si encore dans l'historique de suppression, ainsi qu'un lien vers le scénario ou l'équipement concerné.
- **Analyse / Logs** : Numérotation des lignes des logs. Possibilité d'afficher le log brut.
- **Logs** : Coloration des logs en fonction de certains événements. Possibilité d'afficher le log brut.
- **Résumés** : Possibilité de définir une icône différente quand le résumé est nul (aucun volets ouvert, aucune lumière allumée, etc).
- **Résumés** : Possibilité de ne jamais afficher le numéro à droite de l'icône, ou seulement s'il est positif.
- **Résumés** : Le changement de paramètre de résumé en configuration et sur les objets est maintenant visible, sans attendre un changement de valeur du résumé.
- **Résumés** : Il est maintenant possible de configurer des [actions sur les résumés](/fr_FR/concept/summary#Actions sur résumés) (ctrl + clic sur un résumé) grâce aux virtuels.
- **Types d'équipement** : [Nouvelle page](/fr_FR/core/4.2/types) **Outils → Types d'équipement** permettant d'attribuer des types génériques aux équipements et commandes, avec support des types dédiés aux plugins installés (voir [Doc dev](https://doc.jeedom.com/fr_FR/dev/core4.2)).
- **Sélection d'illustrations** : Nouvelle fenêtre globale pour le choix des illustrations *(icônes, images, fonds)*.
- **Affichage en tableau** : Ajout d'un bouton à droite de la recherche sur les pages *Objets* *Scénarios* *Interactions* *Widgets* et *Plugins* pour basculer en mode tableau. Celui-ci est conservé par un cookie ou dans **Réglages → Système → Configuration / Interface, Options**. Les plugins peuvent faire appel à cette nouvelle fonction du Core. voir [Doc dev](https://doc.jeedom.com/fr_FR/dev/core4.2).
- **Configuration Equipement** : Possibilité de paramétrer une courbe d'historique en fond de tuile d'un équipement.
- **Commande** : Possibilité de faire un calcul sur une commande action de type slider avant exécution de la commande.
- **Plugins / Gestion** : Affichage de la catégorie du plugin, et d'un lien pour ouvrir directement la page de celui-ci sans passer par le menu Plugins.
- **Scénario** : Fonction de repli de code (*code folding*) dans les *Blocs Code*. Raccourcis Ctrl+Y et Ctrl+I.
- **Scénario** : Bugfix des copier / coller et undo / redo (réécriture complète).
- **Scénario** : Ajout des fonctions de calcul ``averageTemporal(commande,période)`` & ``averageTemporalBetween(commande,start,end)`` permettant d'obtenir la moyenne pondérée par la durée sur la période.
- **Scénario** : Ajout du support des Types Génériques dans les scénarios.
	- Déclencheur : `#genericType(LIGHT_STATE,#[Salon]#)# > 0`
	- IF `genericType(LIGHT_STATE,#[Salon]#) > 0`
	- Action `genericType`
- **Objets** : Les plugins peuvent maintenant demander des paramètres spécifique propres aux objets.
- **Utilisateurs** : Les plugins peuvent maintenant demander des paramètres spécifique propres aux utilisateurs.
- **Utilisateurs** : Possibilité de gérer les profils des différents utilisateurs Jeedom depuis la page de gestion des utilisateurs.
- **Utilisateurs** : Possibilité de masquer des objets/vue/design/design 3d pour les utilisateurs limités.
- **Centre de Mises à jour** : Le centre de mises à jour affiche désormais la date de dernière mise à jour.
- **Ajout de l'utilisateur exécutant une action** : Ajout dans les options d’exécution de la commande de l'id et du nom d'utilisateur lançant l'action (visible dans le log event par exemple)
- **Documentation et changelog plugin beta** : Gestion de la documentation et du changelog pour les plugins en beta. Attention, en beta le changelog n'est pas daté.
- **General** : Intégration du plugin JeeXplorer dans le Core. Utilisé maintenant pour les Widget Code, et la personnalisation avancée.
- **Configuration** : Nouvelle option en configuration / interface pour ne pas colorer le bandeau de titre des équipements.
- **Configuration** : Possibilité de paramétrer des fonds d'écran sur les pages Dashboard, Analyse, Outils et leur opacité en fonction du thème.
- **Configuration**: Ajout DNS Jeedom basé sur Wireguard au lieu d'Openvpn (Administration / réseaux). Plus rapide, et plus stable, mais encore en test. Attention ce n'est pour le moment pas compatible Jeedom Smart.
- **Configuration** : Réglages OSDB: Ajout d'un outil d'édition en masse d'équipements, commandes, objets, scénarios.
- **Configuration** : Réglages OSDB: Ajout d'un constructeur dynamique de requête SQL.
- **Configuration**: Possibilité de désactiver le monitoring cloud (Administration / Mises à jour/Market).
- **jeeCLI** : Ajout de ``jeeCli.php`` dans le dossier core/php de Jeedom pour gérer certaines fonctions en ligne de commande.
- *Grosses améliorations de l'interface en terme de performances / réactivité. jeedomUtils{}, jeedomUI{}, menu principal réecrit en css pur, suppression d'initRowWorflow(), simplication du code, corrections css pour les petits écrans, etc.*

### 4.2 : Widgets Core

- Les paramètres de Widgets pour la version Mobile sont maintenant accessible depuis la fenêtre de configuration de l'équipement en mode Édition du Dashboard.
- Les paramètres optionnels disponibles sur les widgets sont maintenant affichés pour chaque widget, que ce soit dans la configuration de la commande ou depuis le mode Édition du Dashboard.
- De nombreux Widgets Core acceptent maintenant des paramètres optionnels de couleur. (slider horizontal et vertical, jauge, compass, rain, shutter, templates slider, etc.).
- Les Widgets Core avec affichage d'un *time* supportent maintenant un paramètre optionnel **time : date** pour afficher une date relative (Hier à 16h48, Lundi dernier à 14h00, etc).
- Les Widgets de type curseur (action) acceptent maintenant un paramètre optionnel *step* pour définir le pas de changement au curseur.
- Le Widget **action.slider.value** est maintenant disponible en desktop, avec un paramètre optionnel *noslider*, ce qui en fait un *input* simple.
- Le Widget **info.numeric.default** (*Gauge*) a été refait en pur css, et intégré en mobile. Ils sont donc maintenant identiques en desktop et mobile.

### 4.2 : Backup cloud

Nous avons ajouté une confirmation du mot de passe de backup cloud pour prévenir les erreurs de saisie (pour rappel l'utilisateur est le seul à connaître ce mot de passe, en cas d'oubli, Jeedom ne peut ni le récupérer ni accéder aux backup cloud de l'utilisateur).

>**IMPORTANT**
>
> Suite à la mise à jour, il faudra obligatoirement aller dans Réglages → Système → Configuration onglet Mise à jour/Market et entrer la confirmation de mot de passe de backup cloud pour que celui-ci puisse se faire.

### 4.2 : Sécurité

- Afin d'augmenter significativement la sécurité de la solution Jeedom, le système d'accès aux fichiers a changé. Avant certains fichiers étaient interdits depuis certains emplacements. A partir de la v4.2, les fichiers sont explicitement autorisés par type et par emplacement.
- Changement au niveau de l'api, auparavant "tolérante" si vous arriviez avec la clef du Core en indiquant plugin XXXXX. Ce n'est plus le cas, vous devez arriver avec la clef correspondante au plugin.
- En api http vous pouviez indiquer un nom de plugin en type, ce n'est plus possible. Le type correspondant au type de la demande (scenario, eqLogic, cmd, etc.) doit correspondre au plugin. Par exemple pour le plugin virtuel vous aviez ``type=virtual`` dans l'url il faut maintenant remplacer par ``plugin=virtual&type=event``.
- Renforcement des sessions : Passage en sha256 avec 64 caractères en mode strict.
- Le cookie de "rester connecté" (3 mois max) est maintenant "one shot", renouvelé à chaque utilisation.

L'équipe Jeedom a bien conscience que ces changements peuvent avoir un impact et être gênant pour vous mais nous ne pouvons transiger sur la sécurité.
Les plugins doivent respecter les recommandations sur l'arborescence des dossiers et fichiers : [Doc](https://doc.jeedom.com/fr_FR/dev/plugin_template).

# Changelog Jeedom V4.1

## 4.1.27

- Correction d'une faille de sécurité merci @Maxime Rinaudo et @Antoine Cervoise de Synacktiv (www.synacktiv.com)

## 4.1.26

- Correction d'un soucis d'installation de dépendance apt sur Smart dû au changement de certificat chez let's encrypt.

## 4.1.25

- Correction d'un soucis d'installation de dépendance apt.

## 4.1.24

- Révision de l'option de configuration des commandes **Gestion de la répétition des valeurs** qui devient **Répéter les valeurs identiques (Oui|Non)**. [Voir l'article du blog pour + de détails](https://blog.jeedom.com/5414-nouvelle-gestion-de-la-repetition-des-valeurs/)

## 4.1.23

- Correction de bugs sur l'archivage de l'historique
- Correction d'un souci de cache qui pouvait disparaitre lors d'un reboot
- Correction d'un bug sur la gestion des répétitions des commandes binaires : dans certain cas si l'équipement envoi deux fois 1 ou 0 d'affilé, seule la première remontée était prise en compte. Attention cette correction de bug peut entraîner une surcharge de la CPU. Il faut donc bien mettre à jour les plugins aussi (Philips Hue notamment) pour d'autre cas (déclenchement multiple de scénario, alors que ce n'était pas le cas avant la mise à jour) bien regarder la configuration de la commande binaire en question sur la répétition des valeurs (configuration avancée de la commande) et la passer en "jamais répéter" pour retrouver le fonctionnement d'avant.

## 4.1.22

- Ajout d'un système permettant à Jeedom SAS de communiquer des messages à tous les Jeedom
- Passage du DNS Jeedom en mode haute disponibilité

## 4.1.20

- Bugfix scroll horizontal sur les designs.
- Bugfix scroll sur les pages équipements de plugins.
- Bugfix des paramétrages de couleurs sur les liens vue/design sur un Design.
- Bugfix et optimisation de la Timeline.
- Retour à trois doigts sur les designs en mobile maintenant limité aux profils administrateur.

## 4.1.19

- Bugfix suppression de zone sur une Vue.
- Bugfix erreur js pouvant apparaitre sur d'anciens navigateurs.
- Bugfix cmd.info.numeric.default.html si commande non visible.
- Bugfix page de connexion.

## 4.1.18

- Bugfix historiques sur les Designs.
- Bugfix recherche dans Analyse / Historique.
- Bugfix recherche sur une variable, lien vers un équipement.
- Bugfix des résumés colorés sur la synthèse.
- Bugfix sur les commentaires de scénario avec json.
- Bugfix sur les updates de résumé sur les aperçus mode Dashboard.
- Bugfix des éléments *image* sur un design.
- Ajout des options de groupement par heure pour les graphiques sur les vues.
- Conservation du contexte de la Synthèse au clic sur les résumés.
- Centrage des images de la Synthèse.

## 4.1.0

### 4.1 : Pré-requis

- Debian 10 Buster

### 4.1 Nouveautés / Améliorations

- **Synthèse** : Ajout d'une nouvelle page **Accueil → Synthèse** proposant une synthèse visuelle globale des pièces, avec accès rapide aux résumés.
- **Recherche** : Ajout d'un moteur de recherche dans **Outils → Recherche**.
- **Dashboard** : Mode Édition maintenant en insertion de la tuile déplacée.
- **Dashboard** : Mode édition: les icônes refresh des équipements sont remplacées par une icône permettant d'accéder à leur configuration, grâce a une nouvelle modale simplifiée.
- **Dashboard** : On peut maintenant cliquer sur les *time* des widgets actions time pour ouvrir la fenêtre d'historique de la commande info liée.
- **Dashboard** : La taille de la tuile d'un nouvel équipement s'adapte à son contenu.
- **Dashboard** : Ajout (retour !) d'un bouton pour filtrer les éléments affichés par catégorie.
- **Dashboard** : Ctrl Clic sur une info ouvre la fenêtre d'historique avec toutes les commandes historisées de l'équipement visible sur la tuile. Ctrl Clic sur une légende pour afficher seulement celle-ci, Alt Clic pour les afficher toutes.
- **Dashboard** : Refonte de l'affichage de l'arbre des objets (flèche à gauche de la recherche).
- **Dashboard** : Possibilité de flouter les arrières plan des images de fond (Configuration -> Interface).
- **Outils / Widgets** : La fonction *Appliquer sur* montre les commandes liées cochées, en décocher une appliquera le widget core par défaut sur cette commande.
- **Widgets** : Ajout d'un widget core *sliderVertical*.
- **Widgets** : Ajout d'un widget core *binarySwitch*.
- **Centre de mise à jour** : La vérification des mises à jour se fait automatiquement à l'ouverture de la page si plus ancienne de 120 mins.
- **Centre de mise à jour** : La barre de progression est maintenant sur l'onglet *Core et plugins*, et le log ouvert par défaut sur l'onglet *Informations*.
- **Centre de mise à jour** : Si vous ouvrez un autre navigateur pendant une update, la barre de progression et le log le signalent.
- **Centre de mise à jour** : Si l'update se finit correctement, affichage d'une fenêtre invitant à recharger la page.
- **Mises à jour du Core** : Mise en place d'un système de nettoyage des anciens fichiers non utilisés du Core.
- **Scénario** : Ajout d'un moteur de recherche (à gauche du bouton Exécuter).
- **Scénario** : Ajout de la fonction age (donne l'âge de la valeur de la commande).
- **Scénario** : *stateChanges()* accepte maintenant la période *today* (de minuit à maintenant), *yesterday* et *day* (pour 1 day).
- **Scénario** : Fonctions *statistics(), average(), max(), min(), tendance(), duration()* : Bugfix sur la période *yesterday*, et accepte maintenant *day* (pour 1 day).
- **Scénario** : Possibilité de désactiver le système de quote automatique (Réglages → Système → Configuration : Equipements).
- **Scénario** : Affichage d'un *warning* si aucun déclencheur n'est configuré.
- **Scénario** : Bugfix des *select* sur les copier/coller de bloc.
- **Scénario** : Copier/coller de bloc entre différents scénarios.
- **Scénario** : Les fonctions undo/redo sont maintenant disponible sous forme de boutons (à coté du bouton de création de bloc).
- **Scénario** :  ajout de "Export historique" (exportHistory)
- **Fenêtre des variables de scénarios** : Tri alphabétique à l'ouverture.
- **Fenêtre des variables de scénarios** : Les scénarios utilisés par les variables sont maintenant clickable, avec ouverture de la recherche sur la variable.
- **Analyse / Historique** : Ctrl Clic sur une légende pour afficher seulement cet historique, Alt Clic pour les afficher tous.
- **Analyse / Historique** : Les options *groupement, type, variation, escalier* sont actives seulement avec une seule courbe affichée.
- **Analyse / Historique** : On peut maintenant utiliser l'option *Aire* avec l'option *Escalier*.
- **Analyse / Logs** : Nouvelle police de type monospace pour les logs.
- **Vue** : Possibilité de mettre des scénarios.
- **Vue** : Mode Édition maintenant en insertion de la tuile déplacée.
- **Vue** : Mode édition: les icônes refresh des équipements sont remplacées par une icône permettant d'accéder à leur configuration, grâce a une nouvelle modale simplifiée.
- **Vue** : L'ordre d'affichage est maintenant indépendant de celui sur le Dashboard.
- **Timeline** : Séparation des pages Historique et Timeline.
- **Timeline** : Intégration de la Timeline en DB pour des raisons de fiabilité.
- **Timeline** : Gestion de Timelines multiples.
- **Timeline** : Refonte graphique complète de la timeline (Desktop / Mobile).
- **Résumé global** : Affichage par résumé, support des résumés depuis un objet différent ou avec un objet racine vide (Desktop et WebApp).
- **Outils / Objets** : Nouvel onglet *Résumé par équipements*.
- **Résumé domotique** : Les équipements de plugins désactivés et leurs commandes n'ont plus les icônes de droite (configuration de l'équipement et configuration avancée).
- **Résumé domotique** : Possibilité de chercher sur les catégories d'équipements.
- **Résumé domotique** : Possibilité de déplacer plusieurs équipements d'un objet dans un autre.
- **Résumé domotique** : Possibilité de sélectionner tous les équipements d'un objet.
- **Moteur de tâches** : Sur l'onglet *Démon*, les plugins désactivés n’apparaissent plus.
- **Rapport** : Utilisation de *chromium* si disponible.
- **Rapport** : Possibilité d'exporter les timelines.
- **Configuration** : L'onglet *Informations* est maintenant dans l'onglet *Général*.
- **Configuration** : L'onglet *Commandes* est maintenant dans l'onglet *Equipements*.
- **Fenêtre de configuration avancée d'équipement** : Changement dynamique de la configuration tableau.
- **Equipements** : Nouvelle catégorie *Ouvrant*.
- **Equipements** : Possibilité d'inverser les commande de type curseur (info et action)
- **Equipements** : Possibilité d'ajouter des class css à une tuile (voir documentation widget).
- **Fenêtre A propos** : Ajout de raccourcis vers le Changelog et la FAQ.
- Pages Widgets / Objets / Scénarios / Interactions / Plugins :
	- Ctrl Clic / Clic Centre sur un Widget, Objet, Scénarios, Interaction, équipement de plugin : Ouvre dans un nouvel onglet.
	- Ctrl Clic / Clic Centre également disponible dans leurs menus contextuels (sur les onglets).
- Nouvelle page ModalDisplay :
	- Menu Analyse : Ctrl Clic / Clic Centre sur *Temps réel* : Ouvre la fenêtre dans un nouvel onglet, en pleine page.
	- Menu Outils : Ctrl Clic / Clic Centre sur *Notes*, *Testeur expression*, *Variables*, *Recherche* : Ouvre la fenêtre dans un nouvel onglet, en pleine page.
- Bloc code, Éditeur de fichier, Personnalisation avancée : Adaptation thème Dark.
- Amélioration de la fenêtre de sélection d'image.

### 4.1 : WebApp
- Intégration de la nouvelle page Synthèse.
- Page scénarios, un clic sur le titre du scénario affiche le log de celui-ci.
- On peut maintenant sélectionner / copier une partie d'un log.
- Sur la recherche dans un log, ajout d'un bouton x pour annuler la recherche.
- Persistance de la bascule de thème (8h).
- Sur un design, un click avec trois doigts permet de revenir à l'accueil.
- Affichage des scénarios par groupe.
- Nouvelle police de type monospace pour les logs.
- Nombreux bug-fix (UI, portrait/landscape iOS, etc.).

### 4.1 : Autres
- **Documentation** : Adaptations en adéquation avec la v4 et v4.1.
- **Documentation** : Nouvelle page *Raccourcis clavier / souris* comprenant un récapitulatif de tous les raccourcis dans Jeedom. Accessible depuis la doc du Dashboard ou la FAQ.
- **Lib** : Update HighStock v7.1.2 vers v8.2.0.
- **Lib** : Update jQuery v3.4.1 vers v3.5.1.
- **Lib** : Update Font Awesome 5.9.0 vers 5.13.1.
- **API** :  ajout d'une option pour interdire une clef api d'un plugin d'executer des methodes core (général)
- Sécurisation des requêtes Ajax.
- Sécurisation des appels API.
- Corrections de bugs.
- Nombreuses optimisations de performance desktop / mobile.

### 4.1 : Changements
- La fonction **scenario->getHumanName()** de la class php scenario ne renvoit plus *[object][group][name]* mais *[group][object][name]*.
- La fonction **scenario->byString()** doit maintenant être appellée avec la structure *[group][object][name]*.
- Les fonctions **network->getInterfaceIp() network->getInterfaceMac() network->getInterfaces()** ont été remplacées par **network->getInterfacesInfo()**


# Changelog Jeedom V4.0

## 4.0.62

- Nouvelle migration buster + kernel pour la smart et la Pro v2
- Verification version OS lors de mise à jour importantes de Jeedom


## 4.0.61

- Correction d'un soucis lors de l'application d'un template de scénario
- Ajout d'une option permettant de désactiver la vérification SSL lors de la communication avec le market (non recommandé mais utile dans certaine configuration réseaux spécifique)
- Correction d'un soucis sur l'archivage des historique si le mode de lissage était à jamais
- Corrections de bugs
- Correction de la commande trigger() dans les scénarios pour qu'elle renvoi le nom du declencheur (sans les #) au lieu de la valeur, pour la valeur il faut utilise triggerValue()

## 4.0.60

- Supression du nouveau systeme de DNS en eu.jeedom.link suite à un trop grand nombre d'opérateur qui interdisent les flux http2 permanant

## 4.0.59

- Correction de bugs sur les widgets time
- Augmentation du nombre de mauvais mot de passe avant bannissement (évite les soucis avec la webapp lors de la rotation des clefs api)

## 4.0.57

- Renforcement de la securité des cookies
- Utilisation de chromium (si il est installé) pour les rapports
- Correction d'un soucis de calcul de temps d'état sur les widgets si le fuseau horaire de jeedom n'est pas le meme que celui du navigateur
- Correction de bugs

## 4.0.55

- Le nouveau dns (\*.eu.jeedom.link) devient le DNS primaire (l'ancien DNS marche toujours)

## 4.0.54

- Début de la mise à jour vers le nouveau site de documentation

## 4.0.53

- Correction de bug.

## 4.0.52

- Correction de bug (mise à jour à faire absolument si vous êtes en 4.0.51).

## 4.0.51

- Correction de bugs.
- Optimisation du futur système de DNS.

## 4.0.49

- Possibilité de choisir le moteur TTS de jeedom et possibilité d'avoir des plugins qui propose un nouveau moteur TTS.
- Amélioration du support de la webview dans l'application mobile.
- Correction de bugs.
- Mise à jour de la doc.

## 4.0.47

- Amélioration du testeur d'expression.
- Mise à jour du repository sur smart.
- Correction de bugs.

## 4.0.44

- Amélioration des traductions.
- Correction de bugs.
- Amélioration de la restauration de backup cloud.
- La restauration cloud ne rapatrie plus maintenant que le backup en local, laissant le choix de le télécharger ou de le restaurer.

## 4.0.43

- Amélioration des traductions.
- Correction de bugs sur les templates de scénario.

## 4.0.0

### 4.0 : Pré-requis

- Debian 9 Stretch

### 4.0 : Nouveautés / Améliorations

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
- Ajout d'un système de configuration en masse (utilisé sur la page Equipement pour configurer l'Alertes Communications sur ceux-ci)

### 4.0 : Autres

- **Lib** : Update jquery 3.4.1
- **Lib** : Update CodeMiror 5.46.0
- **Lib** : Update tablesorter 2.31.1
- Allègement général (css / inline styles, refactoring, etc.) et améliorations des performances.
- Ajout de la compatibilité global du DNS Jeedom avec une connexion internet 4G.
- Nombreuses corrections de bugs.
- Corrections de sécurité.

### 4.0 : Changements

- Suppression de Font Awesome 4 pour ne conserver que Font Awesome 5.
- Le plugin widget n'est pas compatible avec cette version de Jeedom et ne sera plus supporté (car les fonctionnalités ont été reprise en interne sur le core). Plus d'informations [ici](https://www.Jeedom.com/blog/4368-les-widgets-en-v4).

>**IMPORTANT**
>
> Si après la mise à jour vous avez une erreur sur le Dashboard essayez de redémarrer votre box pour qu'elle prenne bien les nouveaux ajout de composants en compte.
