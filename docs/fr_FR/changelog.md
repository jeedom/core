# Changelog Jeedom V4.5

# 4.5

- Possibilité de rendre les colonnes des tableaux redimensionnables (seulement la liste des variables pour le moment ça sera étendu à d'autres tables si besoin) [LIEN](https://github.com/jeedom/core/issues/2499)
- Ajout d'une alerte si l'espace disque de jeedom est trop faible (la vérification se fait une fois par jour) [LIEN](https://github.com/jeedom/core/issues/2438)
- Ajout d'un bouton sur la fenêtre de configuration d'une commande au niveau du champ de calcul de valeur pour aller chercher une commande [LIEN](https://github.com/jeedom/core/issues/2776)
- Possibilité de masquer certains menus pour les utilisateurs aux droits limités [LIEN](https://github.com/jeedom/core/issues/2651)
- Les graphiques se mettent à jour automatiquement lors de l'arrivée de nouvelles valeurs [LIEN](https://github.com/jeedom/core/issues/2749)
- Jeedom ajoute automatiquement la hauteur de l'image lors de la création des widgets pour éviter les soucis de chevauchement en mobile [LIEN](https://github.com/jeedom/core/issues/2539)
- Refonte de la partie backup cloud [LIEN](https://github.com/jeedom/core/issues/2765)
- **DEV** Mise en place d'un système de queue pour l'exécution d'actions [LIEN](https://github.com/jeedom/core/issues/2489)
- Les tags des scénarios sont maintenant propres à l'instance du scénario (si vous avez deux lancements de scénarios très proches, les tags du dernier n'écrasent plus le premier) [LIEN](https://github.com/jeedom/core/issues/2763)
- Changement sur la partie trigger des scénarios : [LIEN](https://github.com/jeedom/core/issues/2414)
  - ``triggerId()`` est maintenant deprecated et sera retiré dans les futures mises à jour du core
  - ``trigger()`` est maintenant deprecated et sera retiré dans les futures mises à jour du core
  - ``triggerValue()`` est maintenant deprecated et sera retiré dans les futures mises à jour du core
  - ``#trigger#`` : Peut être :
    - ``api`` si le lancement a été déclenché par l'API,
    - ``TYPEcmd`` si le lancement a été déclenché par une commande, avec TYPE remplacé par l'id du plugin (ex virtualCmd),
    - ``schedule`` s'il a été lancé par une programmation,
    - ``user`` s'il a été lancé manuellement,
    - ``start`` pour un lancement au démarrage de Jeedom.
  - ``#trigger_id#`` : Si c'est une commande qui a déclenché le scénario alors ce tag prend la valeur de l'id de la commande qui l'a déclenché
  - ``#trigger_name#`` : Si c'est une commande qui a déclenché le scénario alors ce tag prend la valeur du nom de la commande (sous forme [objet][équipement][commande])
  - ``#trigger_value#`` : Si c'est une commande qui a déclenché le scénario alors ce tag prend la valeur de la commande ayant déclenché le scénario
  - ``#trigger_message#`` : Message indiquant l'origine du lancement du scénario
- Amélioration de la gestion des plugins sur github (plus de dépendances à une librairie tierce) [LIEN](https://github.com/jeedom/core/issues/2567)
- Suppression de l'ancien système de cache. [LIEN](https://github.com/jeedom/core/pull/2799)
- Possibilité de suppression les blocs DANS et A en attente d'un autre scénario [LIEN](https://github.com/jeedom/core/pull/2379)
- Correction d'un bug dans Safari sur les filtres avec accents [LIEN](https://github.com/jeedom/core/pull/2754)
- Correction d'un bug sur la génération des informations *generic type* dans les scénarios [LIEN](https://github.com/jeedom/core/pull/2806)
- Ajout d'une confirmation lors de l'ouverture de l'accès support depuis la page de gestion des utilisateurs [LIEN](https://github.com/jeedom/core/pull/2809)
- Amélioration du système de cron pour éviter quelques ratés de lancement [LIEN](https://github.com/jeedom/core/commit/533d6d4d508ffe5815f7ba6355ec45497df73313)
- Ajout dans l'assistant de condition des scénarios des conditions *supérieur ou égal* et *inférieur ou égal* [LIEN](https://github.com/jeedom/core/issues/2810)
- Possibilité d'exclure des commandes de l'analyse des commandes mortes [LIEN](https://github.com/jeedom/core/issues/2812)
- Correction d'un bug sur la numérotation du nombre de lignes des tableaux [LIEN](https://github.com/jeedom/core/commit/0e9e44492e29f7d0842b2c9b3df39d0d98957c83)
- Ajout d'openstreetmap.org dans les domaines externes autorisés par défaut [LIEN](https://github.com/jeedom/core/commit/2d62c64f0bd1958372844f6859ef691f88852422)
- Mise à jour automatique du fichier de sécurité apache lors de la mise à jour du core [LIEN](https://github.com/jeedom/core/issues/2815)
- Correction d'un warning sur les vues [LIEN](https://github.com/jeedom/core/pull/2816)
- Correction d'un bug sur la valeur du select du widget par défaut [LIEN](https://github.com/jeedom/core/pull/2813)
- Correction d'un bug si une commande dépasse son min ou son max la valeur passait à 0 (au lieu du min/max) [LIEN](https://github.com/jeedom/core/issues/2819)
- Correction d'un bug d'affichage du menu réglage dans certaines langues [LIEN](https://github.com/jeedom/core/issues/2821)
- Possibilité dans les déclencheurs programmés des scénarios d'utiliser des calculs/commandes/tags/formules donnant en résultat l'heure de lancement sous la forme Gi (heures sans zero initial et minutes, exemple pour 09h15 => 915 ou pour 23h40 => 2340) [LIEN](https://github.com/jeedom/core/pull/2808)
- Possibilité de mettre une image personnalisée pour les équipements dans les plugins (si le plugin le supporte), cela se fait dans la configuration avancée de l'équipement [LIEN](https://github.com/jeedom/core/pull/2802) [LIEN](https://github.com/jeedom/core/pull/2852)
- Ajout du nom de l'utilisateur qui a lancé le scénario dans le tag ``#trigger_value#`` [LIEN](https://github.com/jeedom/core/pull/2382)
- Correction d'une erreur qui pouvait arriver en quittant le dashboard avant la fin du chargement de celui-ci [LIEN](https://github.com/jeedom/core/pull/2827)
- Correction d'un bug sur la page de remplacement lors d'un filtre sur les objets [LIEN](https://github.com/jeedom/core/issues/2833)
- Amélioration de l'ouverture du changelog du core sous iOS (plus dans une popup) [LIEN](https://github.com/jeedom/core/issues/2835)
- Amélioration de la fenêtre de création avancée de widget [LIEN](https://github.com/jeedom/core/pull/2836)
- Amélioration de la fenêtre de configuration avancée des commandes [LIEN](https://github.com/jeedom/core/pull/2837)
- Correction d'un bug sur la création de widget [LIEN](https://github.com/jeedom/core/pull/2838)
- Correction d'un bug sur la page scénario et la fenêtre d'ajout d'actions qui pouvait ne plus fonctionner [LIEN](https://github.com/jeedom/core/issues/2839)
- Correction d'un bug qui pouvait changer l'ordre des commandes lors de l'édition du dashboard [LIEN](https://github.com/jeedom/core/issues/2841)
- Correction d'un erreur javascript sur les historiques [LIEN](https://github.com/jeedom/core/issues/2840)
- Ajout d'une sécurité sur l'encodage json en ajax pour éviter les erreurs dues à des caractères invalides [LIEN](https://github.com/jeedom/core/commit/0784cbf9e409cfc50dd9c3d085c329c7eaba7042)
- Si une commande d'un équipement est de type générique "Batterie" et a pour unité "%" alors le core va automatiquement affecter le niveau de batterie de l'équipement à la valeur de la commande [LIEN](https://github.com/jeedom/core/issues/2842)
- Amélioration des textes et correction de fautes [LIEN](https://github.com/jeedom/core/pull/2834)
- Lors de l'installation de dépendances npm le cache est nettoyé avant [LIEN](https://github.com/jeedom/core/commit/1a151208e0a66b88ea61dca8d112d20bb045c8d9)
- Correction d'un bug sur les plan 3d pouvant bloquer complètement la configuration [LIEN](https://github.com/jeedom/core/pull/2849)
- Correction d'un bug sur la fenêtre d'affichage des historiques [LIEN](https://github.com/jeedom/core/pull/2850)
- Possibilité de choisir le port d'écoute d'Apache en mode docker [LIEN](https://github.com/jeedom/core/pull/2847)
- Correction d'un warning lors d'une sauvegarde sur la table event [LIEN](https://github.com/jeedom/core/issues/2851)
- Ajout d'un nom d'affichage (display name) pour les objets [LIEN](https://github.com/jeedom/core/issues/2484)
- Ajout d'un bouton pour supprimer les historiques et évenements de la timeline dans le futur [LIEN](https://github.com/jeedom/core/issues/2415)
- Correction d'un soucis sur les commandes de type select dans les designs [LIEN](https://github.com/jeedom/core/issues/2853)
- Possibilité d'indiquer qu'un équipement n'a pas de batterie (en cas de mauvaise remontée) [LIEN](https://github.com/jeedom/core/issues/2855)
- Refonte de l'écriture dans les logs, suppression de la bibliothèque monolog (attention l'option d'envoi des logs dans syslog n'est plus disponible pour le moment, si la demande est forte nous verrons pour la remettre) [LIEN](https://github.com/jeedom/core/pull/2805)
- Passage de nodejs 18 à nodejs 20 [LIEN](https://github.com/jeedom/core/pull/2846)
- Meilleure gestion du niveau de log des sous log des plugins [LIEN](https://github.com/jeedom/core/issues/2860)
- Suppression du dossier vendor (utilisation de composer de manière normale), permet de réduire la taille du core [LIEN](https://github.com/jeedom/core/commit/3aa99c503b6b1903e6a07b346ceb4d03ca3c0c42)
- Les paramètres spécifiques des widgets peuvent maintent être traduits [LIEN](https://github.com/jeedom/core/pull/2862)
- Correction d'un bug sous mac sur les designs lors d'un clic droit [LIEN](https://github.com/jeedom/core/issues/2863)
- Amélioration du systeme de lancement des scénarios programmées [LIEN](https://github.com/jeedom/core/issues/2875)

>**IMPORTANT**
>
> Dû au changement de moteur de cache sur cette mise à jour, tout le cache sera perdu, aucune inquiétude c'est du cache il va se reconstituer de lui-même. Le cache contient entre-autre les valeurs des commandes qui se remettront à jour automatiquement lorsque les modules remonteront leur valeur. A noter que si vous avez des virtuels à valeur fixe (ce qui n'est pas bien si ça ne change pas alors il faut utiliser les variables) alors il vous faudra resauvegarder ceux-ci pour récupérer la valeur.

>**IMPORTANT**
>
> Dû à la refonte des logs et la réinternalisation de bibliothèques, lors de la mise à jour vous pouvez avoir une erreur type ``PHP Fatal error`` (rien de grave) il suffit de relancer la mise à jour.
