# Changelog Jeedom V4.5

# 4.5

- Possibilité de rendre les colonnes des tableaux redimmensionnables (seulement la liste des variables pour le moment ça sera étendu à d'autres tables si besoin) [LIEN](https://github.com/jeedom/core/issues/2499)
- Ajout d'une alerte si l'espace disque de jeedom est trop faible (la vérification se fait une fois par jour) [LIEN](https://github.com/jeedom/core/issues/2438)
- Ajout d'un bouton sur la fenêtre de configuration d'une commande au niveau du champ de calcul de valeur pour aller chercher une commande [LIEN](https://github.com/jeedom/core/issues/2776)
- Possibilité de masquer certains menus pour les utilisateurs aux droits limités [LIEN](https://github.com/jeedom/core/issues/2651)
- Les graphiques se mettent à jour automatiquement lors de l'arrivée de nouvelles valeurs [LIEN](https://github.com/jeedom/core/issues/2749)
- Jeedom ajoute automatiquement la hauteur de l'image lors de la création des widgets pour eviter les soucis de chevauchement en mobile [LIEN](https://github.com/jeedom/core/issues/2539)
- Refonte de la partie backup cloud [LIEN](https://github.com/jeedom/core/issues/2765)
- **DEV** Mise en place d'un systeme de queue pour l'éxecution d'actions [LIEN](https://github.com/jeedom/core/issues/2489)
- Les tags des scénarios sont maintenant propres à l'instance du scénario (si vous avez deux lancements de scénarios très proches, les tags du dernier n'écrasent plus le premier) [LIEN](https://github.com/jeedom/core/issues/2763)
- Changement sur la partie trigger des scénarios : [LIEN](https://github.com/jeedom/core/issues/2414)
  - ``triggerId()`` est maintenant deprecated et sera retiré dans les futurs mises à jour du core
  - ``trigger()`` est maintenant deprecated et sera retiré dans les futurs mises à jour du core
  - ``triggerValue()`` est maintenant deprecated et sera retiré dans les futurs mises à jour du core
  - ``#trigger#`` : Peut être :
    - ``api`` si le lancement a été déclenché par l'API,
    - ``TYPEcmd`` si le lancement a été déclenché par une commande, avec TYPE remplacé par l'id du plugin (ex virtualCmd),
    - ``schedule`` s'il a été lancé par une programmation,
    - ``user`` s'il a été lancé manuellement,
    - ``start`` pour un lancement au démarrage de Jeedom.
  - ``#trigger_id#`` : Si c'est une commande qui a déclenché le scénario alors ce tag prend la valeur de l'id de la commande qui l'a déclenché
  - ``#trigger_name#`` : Si c'est une commande qui a déclenché le scénario alors ce tag prend la valeur du nom de la commande (sous forme [objet][equipement][commande])
  - ``#trigger_value#`` : Si c'est une commande qui a déclenché le scénario alors ce tag prend la valeur de la commande ayant déclenché le scénario
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
- Correction d'un bug sur la valeur du select du widget par defaut [LIEN](https://github.com/jeedom/core/pull/2813)
- Correction d'un bug si une commande dépasse son min ou son max la valeur passait à 0 (au lieu du min/max) [LIEN](https://github.com/jeedom/core/issues/2819)
- Correction d'un bug d'affichage du menu réglage dans certaines langues [LIEN](https://github.com/jeedom/core/issues/2821)
- Possibilité dans les déclencheurs programmés des scénarios d'utiliser des calculs/commandes/tags/formules donnant en résultat l'heure de lancement sous la forme Gi (heures sans zero initial et minutes, exemple pour 09h15 => 915 ou pour 23h40 => 2340) [LIEN](https://github.com/jeedom/core/pull/2808)
- Possibilité de mettre une image personnalisée pour les équipements dans les plugins (si le plugin le supporte), pour cela il suffit de mettre l'image dans `data/img` sous la forme `eqLogic#id#.png` avec #id# l'id de l'équipement (vous pouvez le trouver dans la configuration avancée de l'équipement) [LIEN](https://github.com/jeedom/core/pull/2802)
- Ajout du nom de l'utilisateur qui a lancé le scénario dans le tag ``#trigger_value#`` [LIEN](https://github.com/jeedom/core/pull/2382)
- Correction d'une erreur qui pouvait arriver en quittant le dashboard avant la fin du chargement de celui-ci [LIEN](https://github.com/jeedom/core/pull/2827)
- Correction d'un bug sur la page de remplacement lors d'un filtre sur les objets [LIEN](https://github.com/jeedom/core/issues/2833)
- Amélioration de l'ouverture du changelog du core sous ios (plus dans une popup) [LIEN](https://github.com/jeedom/core/issues/2835)
- Amélioration de la fenetre de création avancées de widget [LIEN](https://github.com/jeedom/core/pull/2836)
- Amélioration de la fenetre de configuration avancée des commandes [LIEN](https://github.com/jeedom/core/pull/2837)
- Correction d'un bug sur la création de widget [LIEN](https://github.com/jeedom/core/pull/2838)
- Correction d'un bug sur la page scénario et la fenetre d'ajout d'action qui pouvait ne plus fonctionner [LIEN](https://github.com/jeedom/core/issues/2839)
- Correction d'un bug qui pouvait changer l'ordre des commandes lors de l'édition du dashboard [LIEN](https://github.com/jeedom/core/issues/2841)
- Correction d'un erreur javascript sur les historiques [LIEN](https://github.com/jeedom/core/issues/2840)
- Ajout d'une sécurité sur l'encodage json en ajax pour eviter les erreurs du à des caractères invalide [LIEN](https://github.com/jeedom/core/commit/0784cbf9e409cfc50dd9c3d085c329c7eaba7042)
- Si une commande d'un équipement est de type generique "Batterie" et à pour unité % alors le core va automatiquement affecté le niveau de batterie de l'équipement à la valeur de la commande [LIEN](https://github.com/jeedom/core/issues/2842)

>**IMPORTANT**
>
> Dû au changement de moteur de cache sur cette mise à jour, tout le cache sera perdu, aucune inquiétude c'est du cache il va se reconstituer de lui même. Le cache contient entre autre les valeurs des commandes qui se remettront à jour automatiquement lorsque les modules remonteront leur valeur. A noter que si vous avez des virtuels à valeur fixe (ce qui n'est pas bien si ça ne change pas alors il faut utiliser les variables) alors il vous faudra resauvegarder ceux-ci pour récuperer la valeur.
