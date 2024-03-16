# Changelog Jeedom V4.4

>**IMPORTANT**
>
>Même si elles ne sont pas forcément visibles au premier abord, la version 4.4 de Jeedom apporte des modifications majeures avec une interface qui a été complètement réécrite pour une maitrise complète et surtout un gain de fluidité de navigation inégalé. La gestion des dépendances PHP à également été revue afin de pouvoir les maintenir à jour automatiquement. Même si l'équipe Jeedom et les beta testeurs ont fait énormément de tests, il y a autant de version de jeedom qu'il y a de jeedom... Il n'est donc pas possible de garantir un bon fonctionnement dans 100% des cas cependant en cas de souci vous pouvez [ouvrir un sujet sur le forum avec l'étiquette `v4_4`](https://community.jeedom.com/) ou contacter le support depuis votre profil market *(sous condition d'être détenteur d'un service pack ou supérieur)*.

## 4.4.2

- Gestion automatique de l'adresse d'accès interne après le démarrage, la mise à jour ou la restauration de Jeedom *(optionnel)*.
- Ajout du widget info/string color. [[PR #2422](https://github.com/jeedom/core/pull/2422)]

## 4.4.1

- Prise en charge de PHP 8.
- Vérification de la version minimale du core requise avant installation ou mise à jour d'un plugin.
- Ajout d'un bouton **Assistance** sur la page de configuration des plugins *(Création automatique d'une demande d'aide sur le forum)*.

### 4.4 : Pré-requis

- Debian 11 "Bullseye"
- Php 7.4

### 4.4 : Nouveautés / Améliorations

- **Historique** : La modale d'historique et la page Historique permettent d'utiliser les boutons *Week, Month, Year* pour recharger dynamiquement un historique plus large.
- **Fenêtre de sélection d'image** : Ajout d'un menu contextuel permettant d'envoyer des images et de créer, renommer ou supprimer un dossier.
- **Fenêtre de sélection d'icône** : Possibilité d'ajouter un paramètre `path` lors de l'appel à `jeedomUtils.chooseIcon` par un plugin pour afficher uniquement ses icônes.
- **Dashboard** : Possibilité d'afficher plusieurs objets côte à côte *(Réglages → Système → Configuration / Interface)*.
- **Dashboard** : La fenêtre d'édition des tuiles en Mode Edition permet de renommer les commandes.
- **Dashboard** : En disposition tableau, possibilité d'insérer des attributs HTML *(colspan/rowspan notamment)* pour chaque cellule.
- **Equipements** : Possibilité de désactiver les templates de widget des plugins qui en utilisent pour revenir à l'affichage par défaut Jeedom *(fenêtre de configuration de l'équipement)*.
- **Equipements** : Les équipements rendus inactifs disparaissent automatiquement de toutes les pages. Les équipements réactivés réapparaissent sur le dashboard si l'objet parent est déjà présent.
- **Equipements** : Les équipements rendus non visibles disparaissent automatiquement du dashboard. Les équipements ré-affichés réapparaissent sur le dashboard si l'objet parent est déjà présent.
- **Analyse > Equipements / Equipements en alerte** : Les équipements qui passent en alerte apparaissent automatiquement et ceux qui sortent d'une alerte disparaissent automatiquement.
- **Centre de message** : Les messages du Core sur anomalie renseignent maintenant une action, par exemple un lien pour ouvrir le scénario incriminé, ou l'équipement, la configuration du plugin, etc.
- **Objet** : La suppression ou la création d'un résumé entraîne l'update du résumé global et de l'objet.
- **Outils > Remplacer** : Cet outil propose maintenant un mode *Copier*, permettant de copier les configurations d'équipements et de commandes, sans les remplacer dans les scénarios et autres.
- **Timeline** : La Timeline charge maintenant les 35 premiers événements. Les événements suivant sont chargés au scroll en bas de page.
- **Administration** : Possibilité de différencier les actions sur erreur ou sur alerte de commande.
- **Administration** : Possibilité de paramétrer les widgets par défaut des commandes.
- **Dashboard** : possibilité sur la page de configuration des objets de demander à Jeedom de réordonner les équipements en fonction de leur utilisation.
- **Thème** : Possibilité de choisir le thème directement depuis l'url *(en ajoutant ``&theme=Dark`` ou ``&theme=Light``)*.
- **Thème** : Suppression du thème **Core2019 Legacy**.
- **Rapport** : Possibilité de choisir le thème lors d'un rapport sur une page Jeedom.
- **Menu Jeedom** : Un délai de 0.25s a été introduit sur l'ouverture des sous-menus.
- **Administration Système** : Possibilité d'ajouter des commandes shell personnalisées dans le menu de gauche *(via un fichier `/data/systemCustomCmd.json`)*.


### 4.4 : Autre

- **Core** : Début du développement en pure js, sans jQuery. Voir [doc dev](https://doc.jeedom.com/fr_FR/dev/core4.4).
- **Core** : Listing plus détaillé des périphériques USB.
- **Core** : Un menu contextuel a été ajouté à différents endroits au niveau des cases à cocher pour les sélectionner toutes, aucunes, ou inverser la sélection *(voir [Doc dev](https://doc.jeedom.com/fr_FR/dev/core4.4))*.
- **Lib** : Update Highchart v9.3.2 vers v10.3.2 (Le module *solid-gauge* n'est plus importé).
- **Commandes** :  Ajout d'une option *(alpha)* pour ne pas exécuter une action si l'équipement est déjà dans l'état attendu.

### 4.4 : Remarques

> **Dashboard**
>
> Sur le **Dashboard** et les **Vues**, le Core v4.4 redimensionne maintenant automatiquement les tuiles pour construire une grille homogène. Les unités (plus petite hauteur et plus petit largeur d'une tuile) de cette grille sont définies dans **Réglages → Système → Configuration / Interface** par les valeurs *Pas vertical (mini 100)* et *Pas horizontal (mini 110)*. La valeur *Marge* définissant l'espace entre les tuiles.
> Les tuiles s'adaptent aux dimensions de la grille et peuvent faire une fois, deux fois etc. ces valeurs en hauteur ou largeur. Il faudra certainement passer en [mode Edition du Dashboard](https://doc.jeedom.com/fr_FR/core/4.4/dashboard#Mode%20%C3%A9dition) pour affiner la taille de certaines tuiles après la mise à jour.


> **Widgets**
>
> Les widgets Core ont été réécrit en pure js / css. Il faudra éditer le Dashboard *(Edition puis bouton ⁝ sur les tuiles)* et utiliser l'option *Retour à la ligne après* sur certaines commandes pour retrouver le même aspect visuel.
> Tous les widgets Core supportent maintenant l'affichage des *time*, en ajoutant un paramètre optionnel *time* / *duration* ou *date*.

> **Boites de dialogue**
>
> Toutes les boites de dialogue (bootstrap, bootbox, jQuery UI) ont été migré sur une lib interne du Core (jeeDialog) développée spécialement. Les boites de dialogue redimensionnable ont maintenant un bouton pour passer en *fullscreen*.


# Changelog Jeedom V4.3

## 4.3.15

- Interdiction de la traduction de Jeedom par les navigateurs (évite les erreurs type marché.repo.php non trouvé).
- Optimisation de la fonction de remplacement.

## 4.3.14

- Réduction de la charge sur les DNS.

## 4.3.13

- Bugfix sur **Outils / Remplacer**.

## 4.3.12

- Optimisation sur les historiques.
- Bugfix Synthèse en mobile.
- Bugfix widget shutter en mobile.
- Bugfix des courbes de tuile avec info binaire.

## 4.3.11

- Autorisation d'une réponse libre dans *ask* si vous mettez * dans le champs des réponses possibles.
- **Analyse / Historique** : Bugfix sur la comparaison d'historique (bug introduit en 4.3.10).
- **Synthèse** : L'*Action depuis la synthèse* d'un objet est maintenant supportée sur la version mobile.
- Correction des historiques lors d'utilisation de fonction d’agrégation.
- Correction d'un bug sur l'installation d'un plugin par un autre plugin (Ex : mqtt2 installé par zwavejs).
- Correction d'un bug sur les historique ou la valeur 0 pouvait écraser la valeur précédente.

## 4.3.10

- **Analyse / Historique** : Correction de bugs sur la suppression d'historique.
- Correction de l'affichage de la valeur dans la fenêtre de configuration d'une commande.
- Ajout d'informations et de contrôle sur l'outil de remplacement.

## 4.3.9

- Amélioration de l'édition des tuiles.
- Amélioration de la visibilité des checkboxs sur les thème Dark et Light.
- Correction de l'empilement des historiques.
- Optimisation de la gestion du changement d'heure (merci @jpty).
- Correction de bugs et améliorations.

## 4.3.8

- Correction de bugs.
- Amélioration de la sécurité des ask lors de l'utilisation de la fonction generateAskResponseLink par les plugins : utilisation d'un token unique (plus d'envoi de la clef api du core) et verrouillage de la réponse uniquement parmi les choix possible.
- Correction d'un bug empêchant l'installation de Jeedom.
- Correction d'un bug sur InfluxDB.


## 4.3.7

- Correction de bugs (impactant un futur plugin en cours de développement).
- Correction de bugs d'affichage sur certains widgets en fonction de l'unité.
- Ajout de la description **source** pour les actions messages (voir [Doc dev](https://doc.jeedom.com/fr_FR/dev/core4.3)).

## 4.3.6

- Suppression de la conversion des unités pour les secondes (s).
- Suppression du menu de mise à jour OS pour les box Jeedom (les mises à jour OS sont gérées par Jeedom SAS).
- Correction d'un bug sur la modale de configuration des historiques.
- Ajout d'une action *changeTheme* pour les scénarios, actions sur valeur, actions pre/post exec : Elle permet de changer le thème de l'interface immédiatement, en dark, light ou l'autre (toggle).

## 4.3.5

- Correction de bugs.

## 4.3.4

- Correction d'un soucis sur les images de fond.
- Correction d'un bug avec le widget numérique par défaut.
- Correction d'une erreur d'inclusion avec certains plugins (*nut* par exemple).

## 4.3.3

- Amélioration de la vérification de la version de nodejs/npm.

## 4.3.2

- Correction d'un soucis d'affichage de l'état d'une commande info dans la configuration avancé de la commande si la valeur est 0.

## 4.3.1

### 4.3 : Pré-requis

- Debian 10 Buster
- Php 7.3

### 4.3 : Nouveautés / Améliorations

- **Outils / Scénarios** : Modale d'édition au ctrl+click dans les champs éditables des blocs/actions.
- **Outils / Scénarios** : Ajout d'un menu contextuel sur un scénario pour rendre actif/inactif, changer de groupe, changer d'objet parent.
- **Outils / Objets** : Ajout d'un menu contextuel sur un objet pour gérer la visibilité, changer d'objet parent, et déplacer.
- **Outils / Remplacer** : Nouvel outil de remplacement d'équipements et commandes.
- **Analyse / Timeline** : Ajout d'un champ de recherche pour filtrer l'affichage.
- **Utilisateurs** : Ajout d'un bouton pour copier les droits d'un utilisateur limité vers un autre.
- **Rapport** : Possibilité de faire des rapports sur la santé de Jeedom.
- **Rapport** : Possibilité de faire des rapports sur les équipements en alerte.
- **Mise à jour** : Possibilité de voir depuis Jeedom les packages OS/PIP2/PIP3/NodeJS qui peuvent etre mise à jour et de lancer la mise à jour (attention fonction risquée et en beta).
- **Commande alerte** : Ajout d'une option pour recevoir un message en cas de fin d'alerte.
- **Plugins** : Possibilité de désactiver l'installation des dépendances par plugin.
- **Optimisation** : jeeFrontEnd{}, jeephp2js{}, corrections de bugs mineures et optimisations.

### 4.3 : WebApp

- Intégration des Notes.
- Possibilité d'afficher les tuiles que sur une colonne (réglage dans la configuration de jeedom onglet interface).

### 4.3 : Autre

- **Lib** : Update Font Awesome 5.13.1 vers 5.15.4.

### 4.3 : Notes

- Pour les utilisateurs qui utilisent des menus dans leurs designs sous la forme :

``<a onClick="planHeader_id=15; displayPlan();"><li class="monmenu"><div class="imagette"><img src="theme1/images/new/home.png" height=30px></div></br></li></a>``

Il faut maintenant utiliser:

``<a onClick="jeephp2js.planHeader_id=15; jeeFrontEnd.plan.displayPlan();"><li class="monmenu"><div class="imagette"><img src="theme1/images/new/home.png" height=30px></div></br></li></a>``

cf [Doc dev](https://doc.jeedom.com/fr_FR/dev/core4.3).

Article du blog [ici](https://blog.jeedom.com/6739-jeedom-4-3/)

# Changelog Jeedom V4.2

## 4.2.21

- Correction d'un bug sur les résumés.

## 4.2.20

- Ajout d'un ssyteme de correction des packages pip lors d'une mauvaise installation.

## 4.2.19

- Ajout de la gestion des version pour les packages python (permet de corriger le soucis avec le plugin zigbee).

## 4.2.18

- Mise à jour de nodejs.

## 4.2.17

- Bugfix Core : Accès utilisateur limité aux designs et vues.
- Bugfix UI : Affichage des blocs A sous Chrome.
- Bugfix : Lien vers la documentation lorsque le plugin est en beta.

## 4.2.16

- Bugfix Core : Scénario : Fusion des éléments collés dans certains cas.
- Bugfix Core : Création d'archive avec l'éditeur de fichiers.
- Bugfix : Augmentation du délai pour le contact du service de monitoring (permet d'alléger la charge sur les serveurs cloud).

## 4.2.15

- Bugfix UI : Scénario : Ajout de l'action *genericType* dans la modale de sélection.
- Bugfix Core : Correction du décalage sur les historiques calculés.
- Bugfix : Installation des dépendances du plugin zigbee.

## 4.2.14

- Bugfix UI : Recherche supprimée en activant l'option log brut.
- Bugfix UI : Téléchargement de log vide impossible.
- Bugfix UI : Widget cmd.action.slider.value

- Bugfix Core : Taille des images de fond en rapport avec la taille du design.
- Bugfix Core : Correction d'un soucis sur les clefs api toujours en désactivées.

## 4.2.13

- Bugfix UI : Option *Masquer en desktop* des résumés.
- Bugfix UI : Historiques: Respect des échelles lors du zoom.

- Bugfix Core : Correction d'un soucis de taille de backup avec le plugin Atlas.

- Amélioration : Création des clef api par defaut en inactif (si la demande de création ne vient pas du plugin).
- Amélioration : Ajout de la taille des sauvegardes sur la page de gestion des sauvegardes.

## 4.2.12

- Bugfix UI : Affichage du dossier d'une action sur la Timeline.

- Bugfix Core : Affichage de la clé API de chaque plugin en page de configuration.
- Bugfix Core : Ajout option *Heure* sur un graphique en Design.
- Bugfix Core : Courbe de tuile avec valeur négative.
- Bugfix Core : Erreur 403 au reboot.

- Amélioration : Affichage de la valeur du déclencheur dans le log de scénario.

## 4.2.11

- Bugfix UI : Position sur le résumé domotique des objets nouvellement crées.
- Bugfix UI : Soucis d'affichage des Design 3D.

- Bugfix Core : Nouvelles propriétés de résumés non définie.
- Bugfix Core : Update de valeur au clic sur le range des widgets *Slider*.
- Bugfix Core : Edition de fichier vide (0b).
- Bugfix Core : Soucis de détection de l'IP réelle du client à travers les DNS Jeedom. Un redémarrage de la box est recommandé suite à la mise à jour pour que cela s'active.

## 4.2.9

- Bugfix UI : Correction widget *numeric default* (cmdName trop long).
- Bugfix UI : Passage des variables css *--url-iconsDark* et *--url-iconsLight* en absolue (Bug Safari MacOS).
- Bugfix UI : Position des notifications en *top center*.

- Bugfix Core : Step par défaut des widgets *Slider* à 1.
- Bugfix Core : Page update indique *en cours* sur *END UPDATE ERROR* (log update).
- Bugfix Core : Modification de valeur d'un historique.
- Bugfix Core : Correction de soucis sur l'installation des dépendances python.

- Amélioration : Nouvelles options sur les graphiques en Design pour échelle et groupement des axes Y.

- Core : Mise à jour de la lib *elFinder* 2.1.59 -> 2.1.60

## 4.2.8

- Bugfix UI : Résumé domotique, vider l'historique de suppression.
- Bugfix UI : Option *Ne plus afficher* sur la modale *first user*.
- Bugfix UI : Courbe en fond de tuiles sur une Vue.
- Bugfix UI : Historiques, échelle des axes au de-zoom.
- Bugfix UI : Historiques, empilement sur les Vues.
- Bugfix UI : Affichage du nom de l'utilisateur lors de la suppression.
- Bugfix UI : Options d'affichage des nombres sans *icône si nul*.

- Bugfix Core : Check Apache mod_alias.

- Amélioration : Option en configuration pour autoriser les dates dans le futur sur les historiques.

## 4.2.0

### 4.2 : Pré-requis

- Debian 10 Buster
- Php 7.3

### 4.2 : Nouveautés / Améliorations

- **Synthèse** : Possibilité de paramétrage des objets pour aller vers un *design* ou une *vue* depuis la synthèse.
- **Dashboard** : La fenêtre de configuration d'un équipement (mode édition) permet maintenant de configurer les widgets mobile et les types génériques.
- **Widgets** : Internationalisation des Widgets tiers (code utilisateur). voir [Doc dev](https://doc.jeedom.com/fr_FR/dev/core4.2).
- **Analyse / Historique** : Possibilité de comparer un historique sur une période donnée.
- **Analyse / Historique** : Affichage des axes multiples en Y. Option pour que chaque axe ait sa propre échelle, groupés par unité ou pas.
- **Analyse / Historique** : Possibilité de masquer les axes Y. Menu contextuel sur les légendes avec affichage seul, masquage d'axe, changement de couleur de courbe.
- **Analyse / Historique** : Les calculs d'historiques enregistrés sont maintenant affichés au dessus de la liste des commandes, de la même façon que celles-ci.
- **Analyse / Equipements** : Les commandes orphelines affichent maintenant leur nom et date de suppression si encore dans l'historique de suppression, ainsi qu'un lien vers le scénario ou l'équipement concerné.
- **Analyse / Logs** : Numérotation des lignes des logs. Possibilité d'afficher le log brut.
- **Logs** : Coloration des logs en fonction de certains événements. Possibilité d'afficher le log brut.
- **Résumés** : Possibilité de définir une icône différente quand le résumé est nul (aucun volets ouvert, aucune lumière allumée, etc).
- **Résumés** : Possibilité de ne jamais afficher le numéro à droite de l'icône, ou seulement s'il est positif.
- **Résumés** : Le changement de paramètre de résumé en configuration et sur les objets est maintenant visible, sans attendre un changement de valeur du résumé.
- **Résumés** : Il est maintenant possible de configurer des [actions sur les résumés](/fr_FR/concept/summary#Actions sur résumés) (ctrl + clic sur un résumé) grâce aux virtuels.
- **Rapport** : Prévisualisation des fichiers PDF.
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
	- Déclencheur : ``#genericType(LIGHT_STATE,#[Salon]#)# > 0``
	- IF ``genericType(LIGHT_STATE,#[Salon]#) > 0``
	- Action ``genericType``
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
- **Configuration**: Possibilité de désactiver le monitoring cloud (Administration / Mises à jour / Market).
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

L'équipe Jeedom a bien conscience que ces changements peuvent avoir un impact et être gênant pour vous mais nous ne pouvons transiger sur la sécurité.
Les plugins doivent respecter les recommandations sur l'arborescence des dossiers et fichiers : [Doc](https://doc.jeedom.com/fr_FR/dev/plugin_template).

[Blog: Introduction Jeedom 4.2 : la sécurité](https://blog.jeedom.com/6165-introduction-jeedom-4-2-la-securite/)

# Changelog Jeedom V4.1

## 4.1.28

- Harmonisation des templates de widgets pour commandes action/défaut

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

### 4.1 : Nouveautés / Améliorations

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
