# Scénarios
**Outils → Scénarios**

<small>[Raccourcis clavier/souris](shortcuts.md)</small>

Véritable cerveau de la domotique, les scénarios permettent d’interagir avec le monde réel de manière *intelligente*.

## Gestion

Vous y trouverez la liste des scénarios de votre Jeedom, ainsi que des fonctionnalités pour les gérer au mieux :

- **Ajouter** : Permet de créer un scénario. La procédure est décrite dans le chapitre suivant.
- **Désactiver scénarios** : Permet de désactiver tous les scénarios. Rarement utilisé et en connaissance de cause, puisqu'aucun scénario ne s’exécutera plus.
- **Vue d’ensemble** : Permet d’avoir une vue d’ensemble de tous les scénarios. Vous pouvez changer les valeurs **actif**, **visible**, **multi lancement**, **mode synchrone**, **Log** et **Timeline** (ces paramètres sont décrits dans le chapitre suivant). Vous pouvez également accéder aux logs de chaque scénario et les démarrer individuellement.

## Mes scénarios

Vous trouverez dans cette partie-là **liste des scénarios** que vous avez créés. Ils sont classés suivant leur **groupe**, éventuellement définis pour chacun d’eux. Chaque scénario est affiché avec son **nom** et son **objet parent**. Les **scénarios grisés** sont ceux qui sont désactivés.

> **Tip**
>
> Vous pouvez ouvrir un scénario en faisant :
> - Clic sur l'un d'entre eux.
> - Ctrl Clic ou Clic Centre pour l'ouvrir dans un nouvel onglet du navigateur.

Vous disposez d'un moteur de recherche permettant de filtrer l'affichage des scénarios. La touche Echap annule la recherche.
A droite du champ de recherche, trois boutons que l'on retrouve à plusieurs endroits de Jeedom :
- La croix pour annuler la recherche.
- Le dossier ouvert pour déplier tous les panneaux et afficher tous les scénarios.
- Le dossier fermé pour replier tous les panneaux.

Une fois sur la configuration d'un scénario, vous disposez d'un menu contextuel au Clic Droit sur les onglets du scénario. Vous pouvez également utiliser un Ctrl Clic ou Clic Centre pour ouvrir directement un autre scénario dans un nouvel onglet du navigateur.

## Création | Édition d’un scénario

Après avoir cliqué sur **Ajouter**, vous devez choisir le nom de votre scénario. Vous êtes ensuite redirigé vers la page de ses paramètres généraux.
Avant cela, en haut de page, on retrouve certaines fonctions utiles pour gérer ce scénario :

- **ID** : A côté du mot **Général**, c’est l’identifiant du scénario.
- **Statut** : *Arrêté* ou *En cours*, il indique l'état actuel du scénario.
- **Etat précédent / suivant** : Permet d'annuler / refaire une action.
- **Ajouter un bloc** : Permet d'ajouter un bloc du type souhaité au scénario (voir plus bas).
- **Log** : Permet d’afficher les logs du scénario.
- **Dupliquer** : Permet de copier le scénario pour en créer un nouveau avec un autre nom.
- **Liens** : Permet de visualiser le graphique des éléments en lien avec le scénario.
- **Edition texte** : Affiche une fenêtre permettant d'éditer le scénario sous forme de texte/json. Ne pas oublier de sauvegarder.
- **Exporter** : Permet d’obtenir une version texte pur du scénario.
- **Template** : Permet d’accéder aux templates et d’en appliquer un au scénario depuis le Market (expliqué en bas de page).
- **Recherche** : Déplie un champ de recherche pour rechercher dans le scénario. Cette recherche déplie les blocs collapsés si nécessaire et les replie après la recherche.
- **Exécuter** : Permet de lancer le scénario manuellement (indépendamment des déclencheurs). Sauvegarder au préalable pour prendre en compte les modifications.
- **Supprimer** : Supprimer le scénario.
- **Sauvegarder** : Sauvegarder les changements effectués.

> **Tips**
>
> Deux outils vous seront également précieux dans la mise en place de scénarios :
    > - Les variables, visibles dans **Outils → Variables**
    > - Le testeur d'expressions, accessible par **Outils → Testeur expression**
>
> Un **Ctrl Clic sur le bouton Exécuter** vous permet directement de sauvegarder, exécuter et afficher le log du scénario (si le niveau de log n'est pas sur Aucun).

## Onglet Général

Dans l’onglet **Général**, on retrouve les paramètres principaux du scénario :

- **Nom du scénario** : Le nom de votre scénario.
- **Nom à afficher** : Le nom utilisé pour son affichage. Facultatif, s'il n'est pas renseigné, c'est le nom du scénario qui est utilisé.
- **Groupe** : Permet d’organiser les scénarios, en les classant dans des groupes (visibles sur la page des scénarios et dans leurs menus contextuels).
- **Actif** : Permet d’activer le scénario. Si non actif, il ne sera pas exécuté par Jeedom, quel que soit le mode de déclenchement.
- **Visible** : Permet de rendre visible le scénario (Dashboard).
- **Objet parent** : Affectation à un objet parent. Il sera alors visible ou non selon ce parent.
- **Timeout en secondes (0 = illimité)** : La durée d’exécution maximale autorisée pour ce scénario. Au-delà de ce temps, l'exécution du scénario est interrompue.
- **Multi lancement** : Cochez cette case si vous souhaitez que le scénario puisse être lancé plusieurs fois en même temps.
>**IMPORTANT**
>
>Le multi lancement marche à la seconde, c'est à dire que si vous avez 2 lancements dans la même seconde sans la case cochée il y aura quand même 2 lancements du scénario (alors qu'il ne devrait pas). De même lors de plusieurs lancements dans la même seconde il se peut que certains lancements perdent les tags. Conclusion il faut ABSOLUMENT éviter de multiples lancements dans la même seconde.
- **Mode synchrone** : Lance le scénario dans le thread courant au lieu d'un thread dédié. Permet d'augmenter la vitesse de lancement du scénario, mais peut rendre le système instable.
- **Log** : Le type de log souhaité pour le scénario. Vous pouvez couper les logs du scénario ou au contraire le faire apparaître dans Analyse → Temps réel.
- **Timeline** : Permet de garder un suivi du scénario dans la timeline (voir doc Historique).
- **Icône** : Permet de choisir une icône pour le scénario en lieu et place de l’icône standard.
- **Description** : Permet d’écrire un petit texte pour décrire votre scénario.
- **Mode du scénario** : Le scénario peut être programmé, déclenché ou les deux à la fois. Vous aurez ensuite le choix d’indiquer le(s) déclencheur(s) (15 déclencheurs maximum) et la/les programmation(s).

> **Tip**
>
> En mode déclenché, des conditions peuvent à présent être saisies. Par ex : ``#[Garage][Open Garage][Ouverture]# == 1``
> Attention : vous pouvez avoir au maximum 28 déclencheurs/programmations pour un scénario.

> **Tip Mode programmé**
>
> Le mode programmé utilise la syntaxe **Cron**. Vous pourrez par exemple exécuter un scénario toutes les 20 minutes avec  ``*/20 * * * *``, ou à 5h du matin pour régler une multitude de choses pour la journée avec ``0 5 * * *``. Le ? à droite d'une programmation vous permet de régler celle-ci sans être un spécialiste de la syntaxe Cron.

## Onglet Scénario

C’est ici que vous allez construire votre scénario. Après la création du scénario, son contenu est vide, il ne fera donc ... rien. Il faut commencer par **ajouter un bloc**, avec le bouton situé à droite. Une fois un bloc créé, vous pourrez y ajouter un autre **bloc** ou une **action**.

Pour plus de praticité et ne pas avoir à constamment réordonner les blocs dans le scénario, l'ajout d'un bloc se fait après le champ dans lequel se situe le curseur de la souris.
*Par exemple, si vous avez une dizaine de blocs, et que vous cliquez dans la condition SI du premier bloc, le bloc ajouté le sera après ce bloc, au même niveau. Si aucun champ n'est actif, il sera ajouté à la fin du scénario.*

> **Tip**
>
> Dans les conditions et actions, il vaut mieux privilégier les guillemets simples (') au lieu des doubles (").

> **Tip**
>
> Un Ctrl Shift Z ou Ctrl Shift Y vous permet d'**annuler** ou de **refaire** une modification (ajout d'action, de bloc...).

## Les blocs

Voici les différents types de blocs disponibles :

- **Si/Alors/Sinon** : Permet de réaliser des actions sous condition (si ceci, alors cela).
- **Action** : Permet de lancer des actions simples sans aucune condition.
- **Boucle** : Permet de réaliser des actions de manière répétitive de 1 jusqu’à un nombre défini (ou même la valeur d’un capteur, ou un nombre aléatoire…​).
- **Dans** : Permet de lancer une action dans X minute(s) (0 est une valeur possible). La particularité est que les actions sont lancées en arrière-plan, elles ne bloquent donc pas la suite du scénario. C’est donc un bloc non bloquant.
- **A** : Permet de dire à Jeedom de lancer les actions du bloc à une heure donnée (sous la forme hhmm). Ce bloc est non bloquant. Ex : 0030 pour 00h30, ou 0146 pour 1h46 et 1050 pour 10h50.
- **Code** : Permet d’écrire directement en code PHP (demande certaines connaissances et peut être risqué mais permet de n’avoir aucune contrainte).
- **Commentaire** : Permet d’ajouter des commentaires à son scénario.

Chaque bloc a ses options pour mieux les manipuler :

- Sur la gauche :
    - La flèche bidirectionnelle permet de déplacer un bloc ou une action pour les réordonner dans le scénario.
    - L’œil permet de réduire un bloc (*collapse*) pour réduire son impact visuel. Ctrl Clic sur l’œil les réduit ou les affiche tous.
    - La case à cocher permet de désactiver complètement le bloc sans pour autant le supprimer. Il ne sera donc pas exécuté.

- Sur la droite :
    - L’icône Copier permet de copier le bloc pour en faire une copie ailleurs. Ctrl Clic sur l’icône coupe le bloc (copie puis suppression).
    - L’icône Coller permet de coller une copie du bloc précédemment copié après le bloc sur lequel vous utilisez cette fonction.  Ctrl Clic sur l’icône remplace le bloc par le bloc copié.
    - L'icône - permet de supprimer le bloc, avec une demande de confirmation. Ctrl Clic supprime le bloc sans confirmation.

### Blocs Si/Alors/Sinon | Boucle | Dans | A

Pour les conditions, Jeedom essaye de faire en sorte qu’on puisse les écrire le plus possible en langage naturel tout en restant souple.
> Il ne faut SURTOUT PAS utiliser des [ ] dans les tests de condition, seules les parenthèses () sont possibles.

Trois boutons sont disponibles sur la droite de ce type de bloc pour sélectionner un élément à tester :

- **Rechercher une commande** : Permet de chercher une commande dans toutes celles disponibles dans Jeedom. Une fois la commande trouvée, Jeedom ouvre une fenêtre pour vous demander quel test vous souhaitez effectuer sur celle-ci. Si vous choisissez de **Ne rien mettre**, Jeedom ajoutera la commande sans comparaison. Vous pouvez également choisir **et** ou **ou** devant **Ensuite** pour enchaîner des tests sur différents équipements.
- **Rechercher un scénario** : Permet de chercher un scénario à tester.
- **Rechercher un équipement** : Idem pour un équipement.

> **Note**
>
> Sur les blocs de type Si/Alors/Sinon, des flèches circulaires situées à gauche du champ de condition permettent d’activer ou non la répétition des actions si l’évaluation de la condition donne le même résultat que lors de la précédente évaluation.
> SI expression != 0 est équivalent à SI expression et SI expression == 0 est équivalent à SI not expression

> **Tip**
>
> Il existe une liste de tags permettant d’avoir accès à des variables issues du scénario ou d’un autre, ou bien à l’heure, la date, un nombre aléatoire, … Voir plus loin les chapitres sur les commandes et les tags.

Une fois la condition renseignée, vous devez utiliser le bouton "ajouter", à gauche, afin d’ajouter un nouveau **bloc** ou une **action** dans le bloc actuel.


### Bloc Code

Le bloc Code permet d’exécuter du code PHP. Il est donc très puissant mais nécessite une bonne connaissance du langage PHP.

#### Accès aux commandes (capteurs et actionneurs)

-  ``cmd::byString($string);`` : Retourne l’objet commande correspondant.
    -   ``$string``: Lien vers la commande voulue : ``#[objet][equipement][commande]#`` (ex : ``#[Appartement][Alarme][Actif]#``)
-  ``cmd::byId($id);`` : Retourne l’objet commande correspondant.
    -  ``$id`` : ID de la commande voulue.
-  ``$cmd->execCmd($options = null);`` : Exécute la commande et retourne le résultat.
    - ``$options`` : Options pour l’exécution de la commande (peut être spécifique au plugin). Options de base (sous-type de la commande) :
        -  ``message`` : ``$option = array('title' => 'titre du message , 'message' => 'Mon message');``
        -  ``color`` : ``$option = array('color' => 'couleur en hexadécimal');``
        -  ``slider`` : ``$option = array('slider' => 'valeur voulue de 0 à 100');``

#### Accès aux logs

-  ``log::add('filename','level','message');``
    - ``filename`` : Nom du fichier de log.
    - ``level`` : [debug], [info], [error], [event].
    - ``message`` : Message à écrire dans les logs.

#### Accès aux scénarios

- ``$scenario->getName();`` : Retourne le nom du scénario courant.
- ``$scenario->getGroup();`` : Retourne le groupe du scénario.
- ``$scenario->getIsActive();`` : Retourne l’état du scénario.
- ``$scenario->setIsActive($active);`` : Permet d’activer ou non le scénario.
    - ``$active`` : 1 actif , 0 non actif.
- ``$scenario->running();`` : Permet de savoir si le scénario est en cours d'exécution ou non (true / false).
- ``$scenario->save();`` : Sauvegarde les modifications.
- ``$scenario->setData($key, $value);`` : Sauvegarde une donnée (variable).
    - ``$key`` : clé de la valeur (int ou string).
    - ``$value`` : valeur à stocker (``int``, ``string``, ``array`` ou ``object``).
- ``$scenario->getData($key);`` : Récupère une donnée (variable).
    - ``$key => 1`` : clé de la valeur (int ou string).
- ``$scenario->removeData($key);`` : Supprime une donnée.
- ``$scenario->setLog($message);`` : Écrit un message dans le log du scénario.
- ``$scenario->persistLog();`` : Force l’écriture du log (sinon il est écrit seulement à la fin du scénario). Attention, ceci peut un peu ralentir le scénario.

> **Tip**
>
> Ajout d'une fonction recherche dans le bloc Code : Rechercher : Ctrl + F puis Enter, Résultat suivant : Ctrl + G, Résultat précédent : Ctrl + Shift + G

[Scénarios : Petits codes entre amis](https://kiboost.github.io/jeedom_docs/jeedomV4Tips/CodesScenario/)

### Bloc Commentaire

Le Bloc commentaire agît différemment quand il est masqué. Ses boutons sur la gauche disparaissent ainsi que le titre du bloc, et réapparaissent au survol. De même, la première ligne du commentaire est affichée en caractères gras.
Ceci permet d'utiliser ce bloc comme séparation purement visuelle au sein du scénario.

### Les Actions

Les actions ajoutées dans les blocs ont plusieurs options :

- Une case **activée** pour que cette commande soit bien prise en compte dans le scénario.
- Une case **parallèle** pour que cette commande se lance en parallèle (en même temps) des autres commandes également sélectionnées.
- Une **double-flèche verticale** pour déplacer l’action. Il suffit de la glisser/déposer à partir de là.
- Un bouton pour **supprimer** l’action.
- Un bouton pour les actions spécifiques, avec à chaque fois la description (au survol) de cette action.
- Un bouton pour rechercher une commande d’action.

> **Tip**
>
> Suivant la commande sélectionnée, on peut voir s'afficher différents champs supplémentaires.

## Les substitutions possibles

### Les déclencheurs

Il existe des déclencheurs spécifiques (autre que ceux fournis par les commandes) :

- ``#start#`` : Déclenché au (re)démarrage de Jeedom.
- ``#begin_backup#`` : Événement envoyé au début d’une sauvegarde.
- ``#end_backup#`` : Événement envoyé à la fin d’une sauvegarde.
- ``#begin_update#`` : Événement envoyé au début d’une mise à jour.
- ``#end_update#`` : Événement envoyé à la fin d’une mise à jour.
- ``#begin_restore#`` : Événement envoyé au début d’une restauration.
- ``#end_restore#`` : Événement envoyé à la fin d’une restauration.
- ``#user_connect#`` : Connexion d'un utilisateur
- ``#variable(nom_variable)#`` : Changement de valeur de la variable nom_variable.
- ``#genericType(GENERIC, #[Object]#)#`` : Changement d'une commande info de Type Generic GENERIC, dans l'objet Object.

Vous pouvez aussi déclencher un scénario en utilisant l’API HTTP décrite [ici](https://doc.jeedom.com/fr_FR/core/4.1/api_http).

### Opérateurs de comparaison et liens entre les conditions

Vous pouvez utiliser n’importe lequel des symboles suivants pour les comparaisons dans les conditions :

- ``==`` : Egal à.
- ``>`` : Strictement supérieur à.
- ``>=`` : Supérieur ou égal à.
- ``<`` : Strictement inférieur à.
- ``<=`` : Inférieur ou égal à.
- ``!=`` : Différent de, n’est pas égal à.
- ``matches`` : Contient. Ex : ``[Salle de bain][Hydrometrie][etat] matches "/humide/"``.
- ``not ( …​ matches …​)`` : Ne contient pas. Ex :  ``not([Salle de bain][Hydrometrie][etat] matches "/humide/")``.

Vous pouvez combiner n’importe quelle comparaison avec les opérateurs suivants :

- ``&&`` / ``ET`` / ``et`` / ``AND`` / ``and`` : et,
- ``||`` / ``OU`` / ``ou`` / ``OR`` / ``or`` : ou,
- ``^`` / ``XOR`` / ``xor`` : ou exclusif.

### Les tags

Un tag est remplacé lors de l’exécution du scénario par sa valeur. Vous pouvez utiliser les tags suivants :

> **Tip**
>
> Pour avoir les zéros initiaux à l’affichage, il faut utiliser la fonction Date(). Voir [ici](http://php.net/manual/fr/function.date.php).

- ``#seconde#`` : Seconde courante (sans les zéros initiaux, ex : 6 pour 08:07:06).
- ``#hour#`` : Heure courante au format 24h (sans les zéros initiaux). Ex : 8 pour 08:07:06 ou 17 pour 17:15.
- ``#hour12#`` : Heure courante au format 12h (sans les zéros initiaux). Ex : 8 pour 08:07:06.
- ``#minute#`` : Minute courante (sans les zéros initiaux). Ex : 7 pour 08:07:06.
- ``#day#`` : Jour courant (sans les zéros initiaux). Ex : 6 pour 06/07/2017.
- ``#month#`` : Mois courant (sans les zéros initiaux). Ex : 7 pour 06/07/2017.
- ``#year#`` : Année courante.
- ``#time#`` : Heure et minute courantes. Ex : 1715 pour 17h15.
- ``#timestamp#`` : Nombre de secondes depuis le 1er janvier 1970.
- ``#date#`` : Jour et mois. Attention, le premier nombre est le mois. Ex : 1215 pour le 15 décembre.
- ``#week#`` : Numéro de la semaine.
- ``#sday#`` : Nom du jour de la semaine. Ex : Samedi.
- ``#nday#`` : Numéro du jour de 0 (dimanche) à 6 (samedi).
- ``#smonth#`` : Nom du mois. Ex : Janvier.
- ``#IP#`` : IP interne de Jeedom.
- ``#hostname#`` : Nom de la machine Jeedom.
- ``#trigger#`` (déprecié, mieux vaut utiliser ``trigger()``) : Peut être le nom de la commande qui a déclenché le scénario :
    - ``api`` si le lancement a été déclenché par l'API,
    - ``schedule`` s'il a été lancé par une programmation,
    - ``user`` s'il a été lancé manuellement,
    - ``start`` pour un lancement au démarrage de Jeedom.
- ``#trigger_value#`` (déprecié, mieux vaut utiliser triggerValue()) : Pour la valeur de la commande ayant déclenché le scénario

Vous avez aussi les tags suivants en plus si votre scénario a été déclenché par une interaction :

- #query# : Interaction ayant déclenché le scénario.
- #profil# : Profil de l’utilisateur ayant déclenché le scénario (peut être vide).

> **Important**
>
> Lorsqu’un scénario est déclenché par une interaction, celui-ci est forcément exécuté en mode rapide. Donc dans le thread de l'interaction et non dans un thread séparé.

### Les fonctions de calcul

Plusieurs fonctions sont disponibles pour les équipements :

- ``average(commande,période)`` & ``averageBetween(commande,start,end)`` : Donnent la moyenne de la commande sur la période (period=[month,day,hour,min] ou [expression PHP](http://php.net/manual/fr/datetime.formats.relative.php)) ou entre les 2 bornes demandées (sous la forme ``Y-m-d H:i:s`` ou [expression PHP](http://php.net/manual/fr/datetime.formats.relative.php)).

- ``averageTemporal(commande,période)`` & ``averageTemporalBetween(commande,start,end)`` : Donnent la moyenne des valeurs de la commande pondérée par leur durée d'existence sur la période (period=[month,day,hour,min] ou [expression PHP](http://php.net/manual/fr/datetime.formats.relative.php)) ou entre les 2 bornes demandées (sous la forme ``Y-m-d H:i:s`` ou [expression PHP](http://php.net/manual/fr/datetime.formats.relative.php)).

- ``min(commande,période)`` & ``minBetween(commande,start,end)`` : Donnent le minimum de la commande sur la période (period=[month,day,hour,min] ou [expression PHP](http://php.net/manual/fr/datetime.formats.relative.php)) ou entre les 2 bornes demandées (sous la forme ``Y-m-d H:i:s`` ou [expression PHP](http://php.net/manual/fr/datetime.formats.relative.php)).

- ``max(commande,période)`` & ``maxBetween(commande,start,end)`` : Donnent le maximum de la commande sur la période (period=[month,day,hour,min] ou [expression PHP](http://php.net/manual/fr/datetime.formats.relative.php)) ou entre les 2 bornes demandées (sous la forme ``Y-m-d H:i:s`` ou [expression PHP](http://php.net/manual/fr/datetime.formats.relative.php)).

- ``duration(commande, valeur, période)`` & ``durationbetween(commande,valeur,start,end)`` : Donnent la durée en minutes pendant laquelle l’équipement avait la valeur choisie sur la période (period=[month,day,hour,min] ou [expression PHP](http://php.net/manual/fr/datetime.formats.relative.php)) ou entre les 2 bornes demandées (sous la forme ``Y-m-d H:i:s`` ou [expression PHP](http://php.net/manual/fr/datetime.formats.relative.php)).

- ``statistics(commande,calcul,période)`` & ``statisticsBetween(commande,calcul,start,end)`` : Donnent le résultat de différents calculs statistiques (sum, count, std, variance, avg, min, max) sur la période (period=[month,day,hour,min] ou [expression PHP](http://php.net/manual/fr/datetime.formats.relative.php)) ou entre les 2 bornes demandées (sous la forme ``Y-m-d H:i:s`` ou [expression PHP](http://php.net/manual/fr/datetime.formats.relative.php)).

- ``tendance(commande,période,seuil)`` : Donne la tendance de la commande sur la période (period=[month,day,hour,min] ou [expression PHP](http://php.net/manual/fr/datetime.formats.relative.php)).

- ``stateDuration(commande)`` : Donne la durée en secondes depuis le dernier changement de valeur.
    -1 : Aucun historique n’existe ou la valeur n’existe pas dans l'historique.
    -2 : La commande n’est pas historisée.

- ``lastChangeStateDuration(commande,valeur)`` : Donne la durée en secondes depuis le dernier changement d’état à la valeur passée en paramètre.
    -1 : Aucun historique n’existe ou la valeur n’existe pas dans l'historique.
    -2 La commande n’est pas historisée

- ``lastStateDuration(commande,valeur)`` : Donne la durée en secondes pendant laquelle l’équipement a dernièrement eu la valeur choisie.
    -1 : Aucun historique n’existe ou la valeur n’existe pas dans l'historique.
    -2 : La commande n’est pas historisée.

- ``age(commande)`` : Donne l'âge en secondes de la valeur de la commande (``collecDate``)
    -1 : La commande n’existe pas ou elle n'est pas de type info.

- ``stateChanges(commande,[valeur], période)`` & ``stateChangesBetween(commande, [valeur], start, end)`` : Donnent le nombre de changements d’état (vers une certaine valeur si indiquée, ou si non indiqué par rapport à sa valeur actuelle) sur la période (period=[month,day,hour,min] ou [expression PHP](http://php.net/manual/fr/datetime.formats.relative.php)) ou entre les 2 bornes demandées (sous la forme ``Y-m-d H:i:s`` ou [expression PHP](http://php.net/manual/fr/datetime.formats.relative.php)).

- ``lastBetween(commande,start,end)`` : Donne la dernière valeur enregistrée pour l’équipement entre les 2 bornes demandées (sous la forme ``Y-m-d H:i:s`` ou [expression PHP](http://php.net/manual/fr/datetime.formats.relative.php)).

- ``variable(mavariable,valeur par défaut)`` : Récupère la valeur d’une variable ou de la valeur souhaitée par défaut.

- ``genericType(GENERIC, #[Object]#)`` : Récupère la somme des infos de Type Générique GENERIC dans l'objet Object.

- ``scenario(scenario)`` : Renvoie le statut du scénario.
    1 : En cours,
    0 : Arrêté,
    -1 : Désactivé,
    -2 : Le scénario n’existe pas,
    -3 : L’état n’est pas cohérent.
    Pour avoir le nom "humain" du scénario, vous pouvez utiliser le bouton dédié à droite de la recherche de scénario.

- ``lastScenarioExecution(scenario)`` : Donne la durée en secondes depuis le dernier lancement du scénario.
    0 : Le scénario n'existe pas

- ``collectDate(cmd,[format])`` : Renvoie la date de la dernière collecte de données pour la commande placée en paramètre, le 2ème paramètre optionnel permet de spécifier le format de retour (détails [ici](http://php.net/manual/fr/function.date.php)).
    -1 : La commande est introuvable,
    -2 : La commande n’est pas de type info.

- ``valueDate(cmd,[format])`` : Renvoie la date de la dernière valeur connue pour la commande placée en paramètre, le 2ème paramètre optionnel permet de spécifier le format de retour (détails [ici](http://php.net/manual/fr/function.date.php)).
    -1 : La commande est introuvable,
    -2 : La commande n’est pas de type info.

- ``eqEnable(equipement)`` : Renvoie l’état de l’équipement.
    -2 : L’équipement est introuvable,
    1 : L’équipement est actif,
    0 : L’équipement est inactif.

- ``value(cmd)`` : Renvoie la valeur d'une commande si elle n'est pas donnée automatiquement par Jeedom (cas lors du stockage du nom de la commande dans une variable)

- ``tag(montag,[defaut])`` : Permet de récupérer la valeur d’un tag ou la valeur par défaut si il n’existe pas.

- ``name(type,commande)`` : Permet de récupérer le nom de la commande, de l’équipement ou de l’objet. Type : cmd, eqLogic ou object.

- ``lastCommunication(equipment,[format])`` : Renvoie la date de la dernière communication pour l'équipement donné en paramètre, le 2ème paramètre optionnel permet de spécifier le format de retour (détails [ici](http://php.net/manual/fr/function.date.php)). Un retour de -1 signifie que l’équipement est introuvable.

- ``color_gradient(couleur_debut,couleur_fin,valuer_min,valeur_max,valeur)`` : Renvoie une couleur calculée par rapport à une valeur dans l'intervalle couleur_debut/couleur_fin. La valeur doit être comprise entre valeur_min et valeur_max.

Les périodes et intervalles de ces fonctions peuvent également s'utiliser avec [des expressions PHP](http://php.net/manual/fr/datetime.formats.relative.php) comme par exemple :

- ``Now`` : maintenant.
- ``Today`` : 00:00 aujourd’hui (permet par exemple d’obtenir des résultats de la journée si entre ``Today`` et ``Now``).
- ``Last Monday`` : lundi dernier à 00:00.
- ``5 days ago`` : il y a 5 jours.
- ``Yesterday noon`` : hier midi.
- Etc.

Voici des exemples pratiques pour comprendre les valeurs retournées par ces différentes fonctions :

| Prise ayant pour valeurs :           | 000 (pendant 10 minutes) 11 (pendant 1 heure) 000 (pendant 10 minutes)    |
|--------------------------------------|--------------------------------------|
| ``average(prise,période)``             | Renvoie la moyenne des 0 et 1 (peut  |
|                                      | être influencée par le polling)      |
| ``averageBetween(#[Salle de bain][Hydrometrie][Humidité]#,2015-01-01 00:00:00,2015-01-15 00:00:00)`` | Renvoie la moyenne de la commande entre le 1er janvier 2015 et le 15 janvier 2015                       |
| ``min(prise,période)``                 | Renvoie 0 : la prise a bien été éteinte dans la période              |
| ``minBetween(#[Salle de bain][Hydrometrie][Humidité]#,2015-01-01 00:00:00,2015-01-15 00:00:00)`` | Renvoie le minimum de la commande entre le 1er janvier 2015 et le 15 janvier 2015                       |
| ``max(prise,période)``                 | Renvoie 1 : la prise a bien été allumée dans la période              |
| ``maxBetween(#[Salle de bain][Hydrometrie][Humidité]#,2015-01-01 00:00:00,2015-01-15 00:00:00)`` | Renvoie le maximum de la commande entre le 1er janvier 2015 et le 15 janvier 2015                       |
| ``duration(prise,1,période)``          | Renvoie 60 : la prise était allumée (à 1) pendant 60 minutes dans la période                              |
| ``durationBetween(#[Salon][Prise][Etat]#,0,Last Monday,Now)``   | Renvoie la durée en minutes pendant laquelle la prise était éteinte depuis lundi dernier.                |
| ``statistics(prise,count,période)``    | Renvoie 8 : il y a eu 8 remontées d’état dans la période               |
| ``tendance(prise,période,0.1)``        | Renvoie -1 : tendance à la baisse    |
| ``stateDuration(prise)``               | Renvoie 600 : la prise est dans son état actuel depuis 600 secondes (10 minutes)                             |
| ``lastChangeStateDuration(prise,0)``   | Renvoie 600 : la prise s’est éteinte (passage à 0) pour la dernière fois il y a 600 secondes (10 minutes)     |
| ``lastChangeStateDuration(prise,1)``   | Renvoie 4200 : la prise s’est allumée (passage à 1) pour la dernière fois il y a 4200 secondes (1h10)                               |
| ``lastStateDuration(prise,0)``         | Renvoie 600 : la prise est éteinte depuis 600 secondes (10 minutes)     |
| ``lastStateDuration(prise,1)``         | Renvoie 3600 : la prise a été allumée pour la dernière fois pendant 3600 secondes (1h)           |
| ``stateChanges(prise,période)``        | Renvoie 3 : la prise a changé 3 fois d’état pendant la période (si la commande info est de type binaire)            |
| ``stateChanges(prise,0,période)``      | Renvoie 2 : la prise s’est éteinte (passage à 0) deux fois pendant la période                              |
| ``stateChanges(prise,1,période)``      | Renvoie 1 : la prise s’est allumée (passage à 1) une fois pendant la  période                              |
| ``lastBetween(#[Salle de bain][Hydrometrie][Température]#,Yesterday,Today)`` | Renvoie la dernière température enregistrée hier.                    |
| ``variable(plop,10)``                  | Renvoie la valeur de la variable plop ou 10 si elle est vide ou n’existe pas                         |
| ``scenario(#[Salle de bain][Lumière][Auto]#)`` | Renvoie 1 en cours, 0 si arreté et -1 si désactivé, -2 si le scénario n’existe pas et -3 si l’état n’est pas cohérent                         |
| ``lastScenarioExecution(#[Salle de bain][Lumière][Auto]#)``   | Renvoie 300 si le scénario s’est lancé pour la dernière fois il y a 5 min                                  |
| ``collectDate(#[Salle de bain][Hydrometrie][Humidité]#)``     | Renvoie 2021-02-14 17:50:12          |
| ``valueDate(#[Salle de bain][Hydrometrie][Humidité]#)`` | Renvoie 2021-02-14 17:45:12          |
| ``eqEnable(#[Aucun][Basilique]#)``       | Renvoie -2 si l’équipement est introuvable, 1 si l’équipement est actif et 0 s’il est inactif          |
| ``tag(montag,toto)``                   | Renvoie la valeur de "montag" si il existe sinon renvoie la valeur "toto"                               |
| ``name(eqLogic,#[Salle de bain][Hydrometrie][Humidité]#)``     | Renvoie Hydrometrie                  |


### Les fonctions mathématiques

Une boîte à outils de fonctions génériques peut également servir à effectuer des conversions ou des calculs :

- ``rand(1,10)`` : Donne un nombre aléatoire de 1 à 10.
- ``randText(texte1;texte2;texte…​..)`` : Permet de retourner un des textes aléatoirement (séparer les texte par un ; ). Il n’y a pas de limite dans le nombre de texte.
- ``randomColor(min,max)`` : Donne une couleur aléatoire comprise entre 2 bornes ( 0 => rouge, 50 => vert, 100 => bleu).
- ``trigger(commande)`` : Permet de connaître le déclencheur du scénario ou de savoir si c’est bien la commande passée en paramètre qui a déclenché le scénario.
- ``triggerValue()`` : Permet de connaître la valeur du déclencheur du scénario.
- ``round(valeur,[decimal])`` : Donne un arrondi au-dessus, [decimal] nombre de décimales après la virgule.
- ``odd(valeur)`` : Permet de savoir si un nombre est impair ou non. Renvoie 1 si impair 0 sinon.
- ``median(commande1,commande2…​.commandeN)`` : Renvoie la médiane des valeurs.
- ``avg(commande1,commande2…​.commandeN)`` : Renvoie la moyenne des valeurs.
- ``time_op(time,value)`` : Permet de faire des opérations sur le temps, avec time=temps (ex : 1530) et value=valeur à ajouter ou à soustraire en minutes.
- ``time_between(time,start,end)`` : Permet de tester si un temps est entre deux valeurs avec ``time=temps`` (ex : 1530), ``start=temps``, ``end=temps``. Les valeurs start et end peuvent être à cheval sur minuit.
- ``time_diff(date1,date2[,format, round])`` : Permet de connaître la différence entre deux dates (les dates doivent être au format AAAA/MM/JJ HH:MM:SS). Par défaut, la méthode retourne la différence en jour(s). Vous pouvez lui demander en secondes (s), minutes (m), heures (h). Exemple en secondes ``time_diff(2019-02-02 14:55:00,2019-02-25 14:55:00,s)``. La différence est retournée en absolu, sauf si vous spécifiez ``f`` (``sf``, ``mf``, ``hf``, ``df``). Vous pouvez aussi utiliser ``dhms`` qui ne retournera pas exemple ``7j 2h 5min 46s``. Le paramètre round, optionnel, arrondit à x chiffres après la virgule (2 par défaut). Ex: ``time_diff(2020-02-21 20:55:28,2020-02-28 23:01:14,df, 4)``.
- ``formatTime(time)`` : Permet de formater le retour d’une chaine ``#time#``.
- ``floor(time/60)`` : Permet de convertir des secondes en minutes, ou des minutes en heures (``floor(time/3600)`` pour des secondes en heures).
- ``convertDuration(secondes)`` : Permet de convertir des secondes en j/h/mn/s.

Et les exemples pratiques :


| Exemple de fonction                  | Résultat retourné                    |
|--------------------------------------|--------------------------------------|
| ``randText(il fait #[salon][oeil][température]#;La température est de #[salon][oeil][température]#;Actuellement on a #[salon][oeil][température]#)`` | la fonction retournera un de ces textes aléatoirement à chaque exécution.                           |
| ``randomColor(40,60)``                 | Retourne une couleur aléatoire  proche du vert.
| ``trigger(#[Salle de bain][Hydrometrie][Humidité]#)``   | 1 si c’est bien ``#[Salle de bain][Hydrometrie][Humidité]#`` qui a déclenché le scénario sinon 0  |
| ``triggerValue()`` | 80 si l’hydrométrie de ``#[Salle de bain][Hydrometrie][Humidité]#`` est de 80 % et que c'est ``#[Salle de bain][Hydrometrie][Humidité]#`` qui a déclenché le scénario. Si le scénario n'a pas été déclenché par une commande, retourne `faux`.                         |
| ``round(#[Salle de bain][Hydrometrie][Humidité]# / 10)`` | Renvoie 9 si le pourcentage d’humidité et 85                     |
| ``odd(3)``                             | Renvoie 1                            |
| ``median(15,25,20)``                   | Renvoie 20
| ``avg(10,15,18)``                      | Renvoie 14.3                     |
| ``time_op(#time#, -90)``               | s’il est 16h50, renvoie : 1650 - 0130 = 1520                          |
| ``formatTime(1650)``                   | Renvoie 16h50                        |
| ``floor(130/60)``                     | Renvoie 2 (minutes si 130s, ou heures si 130m)                      |
| ``convertDuration(3600)``             | Renvoie 1h 0min 0s                      |
| ``convertDuration(duration(#[Chauffage][Module chaudière][Etat]#,1, first day of this month)*60)`` | Renvoie le temps d'allumage en Jours/Heures/minutes du temps de passage à l'état 1 du module depuis le 1er jour du mois |


### Les commandes spécifiques

En plus des commandes domotiques, vous avez accès aux actions suivantes :

- **Pause** (sleep) : Pause de x seconde(s).
- **variable** (variable) : Création/modification d’une variable ou de la valeur d’une variable.
- **Supprimer variable** (delete_variable) : Permet de supprimer une variable.
- **genericType(GENERIC, #[Object]#)** : Modification d'une commande info (event) ou action (execCmd) par Type Générique, dans un objet. Par exemple, éteindre toutes les lumières dans le Salon.
- **Scénario** (scenario) : Permet de contrôler des scénarios. La partie tags permet d’envoyer des tags au scénario, ex : montag=2 (attention il ne faut utiliser que des lettre de a à z. Pas de majuscules, pas d’accents et pas de caractères spéciaux). On récupère le tag dans le scénario cible avec la fonction tag(montag).
    - Démarrer : Démarre le scenario dans un thread diffèrent. Le scenario démarré s’exécute indépendamment du scénario appelant.
    - Démarrer (Sync) : Démarre le scénario appelé et met en pause le scénario appelant, le temps que le scénario appelé ait fini de s’exécuter.
    - Arrêter : Arrête le scenario.
    - Activer : Active un scénario désactivé.
    - Désactiver : Désactive le scénario. Il ne se lance plus quelque soit les déclencheurs.
    - Remise à zéro des SI : Permet de remettre à zéro le statut des **SI**. Ce statut est utilisé pour la non répétition des actions d’un **SI**, si l’évaluation de la condition donne le même résultat que la précédente évaluation.
- **Stop** (stop) : Arrête le scénario.
- **Attendre** (wait) : Attend jusqu’à ce que la condition soit valide (maximum 2h), le timeout est en seconde(s).
- **Aller au design** (gotodesign) : Change le design affiché sur tous les navigateurs par le design demandé.
- **Ajouter un log** (log) : Permet de rajouter un message dans les logs.
- **Créer un message** (message) : Permet d’ajouter un message dans le centre de messages.
- **Activer/Désactiver Masquer/afficher un équipement** (equipement) : Permet de modifier les propriétés d’un équipement visible/invisible, actif/inactif.
- **Faire une demande** (ask) : Permet d’indiquer à Jeedom qu’il faut poser une question à l’utilisateur. La réponse est stockée dans une variable, il suffit ensuite de tester sa valeur.
    Pour le moment, seuls les plugins sms, slack, telegram et snips sont compatibles, ainsi que l'application mobile.
    Attention, cette fonction est bloquante. Tant qu’il n’y a pas de réponse ou que le timeout n’est pas atteint, le scénario attend.
- **Arrêter Jeedom** (jeedom_poweroff) : Demande à Jeedom de s’éteindre.
- **Retourner un texte/une donnée** (scenario_return) : Retourne un texte ou une valeur pour une interaction par exemple.
- **Icône** (icon) : Permet de changer l’icône de représentation du scénario.
- **Alerte** (alert) : Permet d’afficher un petit message d’alerte sur tous les navigateurs qui ont une page Jeedom ouverte. Vous pouvez, en plus, choisir 4 niveaux d’alerte.
- **Pop-up** (popup) : Permet d’afficher un pop-up qui doit absolument être validé sur tous les navigateurs qui ont une page jeedom ouverte.
- **Rapport** (report) : Permet d’exporter une vue au format (PDF,PNG, JPEG ou SVG) et de l’envoyer par le biais d’une commande de type message. Attention, si votre accès Internet est en HTTPS non-signé, cette fonctionnalité ne fonctionnera pas. Il faut du HTTP ou HTTPS signé.
- **Supprimer bloc DANS/A programmé** (remove_inat) : Permet de supprimer la programmation de tous les blocs DANS et A du scénario.
- **Evènement** (event) : Permet de pousser une valeur dans une commande de type information de manière arbitraire.
- **Tag** (tag) : Permet d'ajouter/modifier un tag (le tag n'existe que pendant l'exécution en cours du scénario à la différence des variables qui survivent à la fin du scénario).
- **Coloration des icônes du dashboard** (setColoredIcon) : permet d'activer ou non la coloration des icônes sur le Dashboard.
- **Export historique** (exportHistory) : permet d'exporter l'historique en csv d'une commande sous forme d'un fichier (envoi par mail par exemple). Vous pouvez mettre plusieurs commandes (séparées par des &&). La selection de la période se fait sous la forme :
  - "-1 month" => -1 mois
  - "-1 day midnight" => -1 jour à minuit
  - "now" => maintenant
  - "monday this week midnight" => lundi de cette semaine à minuit
  - "last sunday 23:59" => dimanche précédent à 23h59
  - "last day of previous month 23:59" => dernier jour du mois précédent à 23h59
  - "first day of january this year midnight" => premier jour de janvier à minuit
  - ...

### Template de scénario

Cette fonctionnalité permet de transformer un scénario en template pour par exemple l’appliquer sur un autre Jeedom.

En cliquant sur le bouton **template** en haut de page, vous ouvrez la fenêtre de gestion des templates.

A partir de celle-ci, vous avez la possibilité :

- D’envoyer un template à Jeedom (fichier JSON préalablement récupéré).
- De consulter la liste des scénarios disponibles sur le Market.
- De créer un template à partir du scénario courant (n’oubliez pas de donner un nom).
- De consulter les templates actuellement présents sur votre Jeedom.

En cliquant sur un template, vous pourrez :

- **Partager** : Partager le template sur le Market.
- **Supprimer** : Supprimer le template.
- **Télécharger** : Récupérer le template sous forme de fichier JSON pour le renvoyer sur un autre Jeedom par exemple.

En-dessous, vous avez la partie pour appliquer votre template au scénario courant.

Etant donné que d’un Jeedom à l’autre ou d’une installation à une autre, les commandes peuvent être différentes, Jeedom vous demande la correspondance des commandes entre celles présentes lors de la création du template et celles présentes chez vous. Il vous suffit de remplir la correspondance des commandes puis de faire appliquer.

## Ajout de fonction PHP

> **IMPORTANT**
>
> L'ajout de fonction PHP est réservé aux utilisateurs avancés. La moindre erreur peut être fatale pour votre Jeedom.

### Mise en place

Aller dans la configuration de Jeedom, puis OS/DB et lancer l'éditeur de fichier.

Allez dans le dossier data puis PHP et cliquez sur le fichier user.function.class.php.

C'est dans cette *classe* que vous pouvez ajouter vos fonctions, vous y trouverez un exemple de fonction basique.

> **IMPORTANT**
>
> Si vous avez un souci, vous pouvez toujours revenir au fichier d'origine en copiant le contenu de ``user.function.class.sample.php`` dans ``user.function.class.php``
