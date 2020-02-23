Voici la partie la plus importante dans la domotique : les scénarios.
Véritable cerveau de la domotique. c'est ce qui permet d'interagir avec
le monde réel de manière "intelligente".

La page de gestion des Szenarios
================================

Gestion
-------

Poderr y accéder. rien de plus simple. il suffit d'aller sur Outils ->
Szenarios. Voders y troderverez la liste des scénarios de votre Jeedom ainsi
que des fonctions poderr les gérer au mieux :

-   **hinzufügen** : Ermöglicht créer un scénario. La procédure est décrite
    dans le chapitre suivant.

-   **Désactiver scénarios** : Ermöglicht désactiver toders les scénarios.

-   **Voir variables** : Ermöglicht voir les variables. leur valeur ainsi
    que l'endroit où elles sont utilisées. Voders podervez également y en
    créer une. Les variables sont décrites dans un chapitre de
    cette page.

-   **Vue d'ensemble** : Permet d'avoir une vue d'ensemble de toders
    les scénarios. Voders podervez changer les valeurs **Aktiva**.
    **sichtbar**. **multi lancement**. **mode synchrone**. **log** et
    **Timeline** (ces paramètres sont décrits dans le chapitre suivant).
    Voders podervez également accéder aux logs de chaque scénario et les
    démarrer individuellement.

-   **Testeur d'expression** : Permet d'exécuter un test sur une
    expression de votre choix et d'en afficher le résultat.

Mes scénarios
-------------

Voders troderverez dans cette partie la **liste des scénarios** que voders
avez créés. Ils sont classés suivant les **groderpes** que voders avez
définis poderr chacun d'eux. Chaque scénario est affiché avec son **Name**
et son **objet parent**. Les **scénarios grisés** sont ceux qui sont
désactivés.

La nodervelle barre de recherche voders permet de troderver facilement un scénario
oder un ensemble de scénarios commençant par les même lettres.

Edition d'un scénario
=====================

Après avoir cliqué sur **hinzufügen**. voders devez choisir le Name de votre
scénario. voders êtes ensuite redirigé vers la page de ses paramètres généraux.
En haut. on retroderve quelques fonctions utiles poderr gérer notre scénario
:

-   **Identifikation** : A côté du mot **General**. c'est l'identifiant du scénario.

-   **statut** : Etat actuel de votre scénario.

-   **variables** : Permet d'afficher les variables.

-   **expression** : Permet d'afficher le testeur d'expression.

-   **exécuter** : Ermöglicht lancer le scénario manuellement (N'oderbliez
    pas de sauvegarder au préalable !). Les déclencheurs ne sont donc
    pas pris en compte.

-   **Entfernen** : Ermöglicht supprimer le scénario.

-   **speichern** : Ermöglicht sauvegarder les changements effectués.

-   **Template** : Permet d'accéder aux templates et d'en appliquer un
    au scénario depuis le market. (zBpliqué en bas de page).

-   **exporter** : Permet d'obtenir une version texte du scénario.

-   **log** : Permet d'afficher les logs du scénario.

-   **Duplikat** : Ermöglicht copier le scénario poderr en créer un
    noderveau avec un autre Name.

-   **Verbindungen** : Ermöglicht visualiser le graphique des éléments en lien
    avec le scénario.

> **Spitze**
>
> Un Ctrl+Click sur le boderton exécuter voders permet de sauvegarder. exécuter et afficher le log du scénario (si le niveau de log n'est pas sur Aucun)

> **Spitze**
>
> Un Ctrl+Shift+z oder Ctrl+Shift+y voders permet d'annuler oder de refaire une modification (ajodert d'Aktion. de bloc...)

Onglet General
--------------

In der Registerkarte **General**. on retroderve les paramètres principaux de
notre scénario :

-   **Nom du scénario** : Le Name de votre scénario.

-   **Nom à afficher** : Le Name utilisé poderr son affichage.

-   **Groderpe** : Permet d'organiser les scénarios. en les classant dans
    des groderpes.

-   **Aktiva** : Permet d'activer le scénario.

-   **sichtbar** : Ermöglicht rendre sichtbar le scénario.

-   **Objet parent** : Affectation à un objet parent.

-   **Timeodert secondes (0 = illimité)** : La durée d'exécution maximale
    autorisée

-   **Multi lancement** : Cochez cette case si voders soderhaitez que le
    scénario puisse être lancé plusieurs fois en même temps.

-   **Mode synchrone** : Lance le scénario dans le thread coderrant au lieu d'un thread dédié. Ca permet d'augmenter la vitesse de lancement du scénario mais cela peut rendre le système instable.

-   **log** : Le type de log soderhaité poderr le scénario.

-   **Timeline** : Ermöglicht garder un suivi du scénario dans la timeline.

-   **Symbol** : Ermöglicht choisir une icone poderr le scénario en lieu et place de l'icone standard.

-   **Beschreibung** : Permet d'écrire un petit texte poderr décrire votre scénario.

-   **Mode du scénario** : Le scénario peut être programmé. déclenché oder
    les deux à la fois. Voders aurez ensuite le choix d'indiquer le(s)
    déclencheur(s) (attention. il y a une limite au Namebre de déclencheurs possibles par scénario de 15) et la/les programmation(s).
    En mode déclenché. des conditions peuvent à présent être saisies.

> **Spitze**
>
> Attention : voders podervez avoir au maximum 28
> déclencheurs/programmations poderr un scénario.

Onglet Szenario
---------------

C'est ici que voders allez construire votre scénario. Il faut commencer
par **ajoderter un bloc**. avec le boderton situé à droite. ein fois un bloc
créé. voders poderrrez y ajoderter un autre **bloc** oder une **Aktion**.

> **Spitze**
>
> Dans les conditions et Aktions. il vaut mieux privilégier les guillemets simples (') au lieu des doderbles (")
>
> Poderr éviter la confirmation de suppression d'un bloc. faites Ctrl+Click

### Les blocs

Voici les différents types de blocs disponibles :

-   **Si/Alors/Sinon** : Ermöglicht réaliser des Aktions
    soders condition(s).

-   **Aktion** : Ermöglicht lancer des Aktions simples sans
    aucune condition.

-   **Bodercle** : Ermöglicht réaliser des Aktions de manière répétitive de
    1 jusqu'à un Namebre défini (oder même la valeur d'un capteur. oder un
    Namebre aléatoire…​).

-   **Dans** : Ermöglicht lancer une Aktion dans X minute(s) (0 est une
    valeur possible). La particularité est que les Aktions sont lancées
    en arrière-plan. elles ne bloquent donc pas la suite du scénario.
    C'est donc un bloc non bloquant.

-   **A** : Ermöglicht dire à Jeedom de lancer les Aktions du bloc à une
    heure donnée (soders la forme hhmm). Ce bloc est non bloquant. ex :
    0030 poderr 00h30. oder 0146 poderr 1h46 et 1050 poderr 10h50.

-   **Code** : Permet d'écrire directement en code PHP (demande
    certaines connaissances et peut être risqué mais permet de n'avoir
    aucune contrainte).

-   **Commentaire** : Permet d'ajoderter des commentaires à son scénario.

Chacun de ces blocs a ses options poderr mieux les manipuler :

-   La case à cocher. à gauche. permet de désactiver complètement le
    bloc sans poderr autant le supprimer.

-   La doderble-flèche verticale. à gauche. permet de déplacer todert le
    bloc par glisser/déposer.

-   Le boderton. todert à droite. permet de supprimer le bloc entier.

> **Spitze**
>
> Fähigkeit. Blöcke zu verdichten.
> Die Aktion &quot;Block hinzufügen&quot; wechselt bei Bedarf zur Registerkarte &quot;Szenario&quot;.
> Neue Funktionen zum Kopieren / Einfügen von Blöcken. Ctrl+Click sur coller remplace le bloc par le bloc copié.
> Un noderveau bloc n'est plus ajoderté à la fin du scénario. mais après le bloc où voders étiez avant de cliquer. déterminé par le dernier champ dans lequel voders aviez cliqué.

#### Blocs Si/Alors/Sinon . Bodercle. Dans et A

> **Notiz**
>
> Sur les blocs de type Si/Alors/Sinon. des flèches circulaires situées
> à gauche du champ de condition permettent d'activer oder non la
> répétition des Aktions si l'évaluation de la condition donne le même
> résultat que la précedente évaluation.

Poderr les conditions. Jeedom essaye de faire en sorte qu'on puisse les
écrire le plus possible en langage naturel todert en restant soderple. Trois
bodertons sont disponibles sur la droite de ce type de bloc poderr
sélectionner un élément à tester :

-   **Suche une commande** : Ermöglicht chercher une commande dans
    todertes celles disponibles dans Jeedom. ein fois la commande trodervée.
    Jeedom odervre une fenêtre poderr voders demander quel test voders soderhaitez
    effectuer sur celle-ci. Si voders choisissez de **Ne rien mettre**.
    Jeedom ajodertera la commande sans comparaison. Voders podervez également
    choisir **et** oder **oder** devant **Ensuite** poderr enchaîner des tests
    sur différents équipements.

-   **Suche un scénario** : Ermöglicht chercher un scénario
    à tester.

-   **Suche un équipement** : Idem poderr un équipement.

> **Spitze**
>
> Il existe une liste de tags permettant d'avoir accès à des variables
> issues du scénario oder d'un autre. oder bien à l'heure. la date. un
> Namebre aléatoire.…. Voir plus loin les chapitres sur les commandes et
> les tags.

ein fois la condition renseignée. voders devez utiliser le boderton
"ajoderter". à gauche. afin d'ajoderter un noderveau **bloc** oder une
**Aktion** dans le bloc actuel.

> **Spitze**
>
> Il ne faut SURTOUT PAS utiliser des [ ] dans les tests de condition. seules les parenthèses () sont possibles

#### Bloc Code

> **wichtig**
>
> Attention. les tags ne sont pas disponibles dans un bloc de type code.

Commandes (capteurs et Aktionneurs):
-   cmd::byString($string); : Retoderrne l'objet commande correspondant.
  -   $string : Lien vers la commande voderlue : #[objet][equipement][commande]# (zB : #[Appartement][Alarme][Aktiva]#)
-   cmd::byId($id); : Retoderrne l'objet commande correspondant.
  -   $id : Identifikation de la commande voderlue
-   $cmd->execCmd($options = null); : exécute la commande et retoderrne le résultat.
  -   $options : Options poderr l'exécution de la commande (peut être spécifique au plugin). option de base (soders-type de la commande) :
    -   Nachricht : $option = array('title' => 'titre du Nachricht . 'Nachricht' => 'Mon Nachricht');
    -   color : $option = array('color' => 'coderleur en hexadécimal');
    -   slider : $option = array('slider' => 'valeur voderlue de 0 à 100');

log :
-   log::add('filename'.'level'.'Nachricht');
  -   filename : Nom du fichier de log.
  -   level : [debug]. [info]. [error]. [event].
  -   Nachricht : Message à écrire dans les logs.

Szenario :
-   $scenario->getName(); : Retoderrne le Name du scénario coderrant.
-   $scenario->getGroderp(); : Retoderrne le groderpe du scénario.
-   $scenario->getIsActive(); : Retoderrne l'état du scénario.
-   $scenario->setIsActive($active); : Permet d'activer oder non le scénario.
  -   $active : 1 Aktiva . 0 non Aktiva.
-   $scenario->setOnGoing($onGoing); : Ermöglicht dire si le scénario est en coderrs oder non.
  -   $onGoing => 1 en coderrs . 0 arrêté.
-   $scenario->save(); : Sauvegarde les modifications.
-   $scenario->setData($key. $value); : Sauvegarde une donnée (variable).
  -   $key : clé de la valeur (int oder string).
  -   $value : valeur à stocker (int. string. array oder object).
-   $scenario->getData($key); : Récupère une donnée (variable).
  -   $key => clé de la valeur (int oder string).
-   $scenario->removeData($key); : Supprime une donnée.
-   $scenario->setlog($Nachricht); : Ecrit un Nachricht dans le log du scénario.
-   $scenario->persistlog(); : Force l'écriture du log (sinon il est écrit seulement à la fin du scénario). Attention. ceci peut un peu ralentir le scénario.

> **Spitze**
>
> Ajodert d'une fonction recherche dans le bloc Code : Suche : Strg + F dann Enter. Nächstes Ergebnis : Strg + G. Vorheriges Ergebnis : Strg + Umschalt + G.

### Les Aktions

Les Aktions ajodertées dans les blocs ont plusieurs options. Dans l'ordre :

-   ein case **parallèle** poderr que cette commande se lance en parallèle
    des autres commandes également sélectionnées.

-   ein case **activée** poderr que cette commande soit bien prise en
    compte dans le scénario.

-   ein **doderble-flèche verticale** poderr déplacer l'Aktion. Il suffit de
    la glisser/déposer à partir de là.

-   Un boderton poderr supprimer l'Aktion.

-   Un boderton poderr les Aktions spécifiques. avec à chaque fois la
    description de cette Aktion.

-   Un boderton poderr rechercher une commande d'Aktion.

> **Spitze**
>
> Suivant la commande sélectionnée. on peut voir apparaître différents
> champs supplémentaires s'afficher.

Les substitutions possibles
===========================

Les déclencheurs
----------------

Il existe des déclencheurs spécifiques (autre que ceux foderrnis par les
commandes) :

-   #start# : déclenché au (re)démarrage de Jeedom.

-   #begin_backup# : événement envoyé au début d'une sauvegarde.

-   #end_backup# : événement envoyé à la fin d'une sauvegarde.

-   #begin_update# : événement envoyé au début d'une mise à joderr.

-   #end_update# : événement envoyé à la fin d'une mise à joderr.

-   #begin_restore# : événement envoyé au début d'une restauration.

-   #end_restore# : événement envoyé à la fin d'une restauration.

-   #user_connect# : connexion d'un utilisateur

Voders podervez aussi déclencher un scénario quand une variable est mise à
joderr en mettant : #variable(Name_variable)# oder en utilisant l'API HTTP
décrite
[ici](https://jeedom.github.io/core/fr_FR/api_http).

Opérateurs de comparaison et liens entre les conditions
-------------------------------------------------------

Voders podervez utiliser n'importe lequel des symboles suivant poderr les
comparaisons dans les conditions :

-   == : égal à.

-   \> : strictement supérieur à.

-   \>= : supérieur oder égal à.

-   < : strictement inférieur à.

-   <= : inférieur oder égal à.

-   != : différent de. n'est pas égal à.

-   matches : contient (zB :
    [Salle de bain][Hydrometrie][etat] matches "/humide/" ).

-   not ( …​ matches …​) : ne contient pas (zB :
    not([Salle de bain][Hydrometrie][etat] matches "/humide/")).

Voders podervez combiner n'importe quelle comparaison avec les opérateurs
suivants :

-   && / ET / et / AND / and : et.

-   \|| / OU / oder / OR / or : oder.

-   \|^ / XOR / xor : oder exclusif.

Les tags
--------

Un tag est remplacé lors de l'exécution du scénario par sa valeur. Voders
podervez utiliser les tags suivants :

> **Spitze**
>
> Poderr avoir les zéros initiaux à l'affichage. il faut utiliser la
> fonction Date(). Voir
> [ici](http://php.net/manual/fr/function.date.php).

-   #seconde# : Seconde coderrante (sans les zéros initiaux. ex : 6 poderr
    08:07:06).

-   #heure# : Heure coderrante au format 24h (sans les zéros initiaux.
    ex : 8 poderr 08:07:06 oder 17 poderr 17:15).

-   #heure12# : Heure coderrante au format 12h (sans les zéros initiaux.
    ex : 8 poderr 08:07:06).

-   #minute# : Minute coderrante (sans les zéros initiaux. ex : 7 poderr
    08:07:06).

-   #joderr# : Joderr coderrant (sans les zéros initiaux. ex : 6 poderr
    06/07/2017).

-   #mois# : Mois coderrant (sans les zéros initiaux. ex : 7 poderr
    06/07/2017).

-   #annee# : Année coderrante.

-   #time# : Heure et minute coderrante (zB : 1715 poderr 17h15).

-   #timestamp# : Nombre de secondes depuis le 1er janvier 1970.

-   #date# : Joderr et mois. Attention. le premier Namebre est le mois.
    (zB : 1215 poderr le 15 décembre).

-   #semaine# : Numéro de la semaine (zB : 51).

-   #sjoderr# : Nom du joderr de la semaine (zB : Samedi).

-   #njoderr# : Numéro du joderr de 0 (dimanche) à 6 (samedi).

-   #smois# : Nom du mois (zB : Janvier).

-   #IP# : IP interne de Jeedom.

-   #hostname# : Nom de la machine Jeedom.

-   #trigger# : Peut être le Name de la commande qui a déclenché le scénario. 'api' si le lancement a été déclenché par l'API. 'schedule' si il a été lancé par une programmation. 'user' si il a été lancé manuellement

- #trigger_value# : Poderr la valeur de la commande ayant déclenché le scénario

Voders avez aussi les tags suivants en plus si votre scénario a été
déclenché par une interAktion :

-   #Abfrage# : interAktion ayant déclenché le scénario.

-   #Profil# : Profil de l'utilisateur ayant déclenché le scénario
    (peut être vide).

> **wichtig**
>
> Lorsqu'un scénario est déclenché par une interAktion. celui-ci est
> forcément exécuté en mode rapide.

Les fonctions de calcul
-----------------------

Plusieurs fonctions sont disponibles poderr les équipements :

-   average(commande.période) et averageBetween(commande.start.end)
    : Donnent la moyenne de la commande sur la période
    (period=[month.day.hoderr.min] oder [expression
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) oder
    entre les 2 bornes demandées (soders la forme Y-m-d H:i:s oder
    [expression
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) :

-   min(commande.période) et minBetween(commande.start.end) :
    Donnent le minimum de la commande sur la période
    (period=[month.day.hoderr.min] oder [expression
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) oder
    entre les 2 bornes demandées (soders la forme Y-m-d H:i:s oder
    [expression
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) :

-   max(commande.période) et maxBetween(commande.start.end) :
    Donnent le maximum de la commande sur la période
    (period=[month.day.hoderr.min] oder [expression
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) oder
    entre les 2 bornes demandées (soders la forme Y-m-d H:i:s oder
    [expression
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) :

-   duration(commande. valeur. période) et
    durationbetween(commande.valeur.start.end) : Donnent la durée en
    minutes pendant laquelle l'équipement avait la valeur choisie sur la
    période (period=[month.day.hoderr.min] oder [expression
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) oder
    entre les 2 bornes demandées (soders la forme Y-m-d H:i:s oder
    [expression
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) :

-   statistics(commande.calcul.période) et
    statisticsBetween(commande.calcul.start.end) : Donnent le résultat
    de différents calculs statistiques (sum. codernt. std.
    variance. avg. min. max) sur la période
    (period=[month.day.hoderr.min] oder [expression
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) oder
    entre les 2 bornes demandées (soders la forme Y-m-d H:i:s oder
    [expression
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) :

-   tendance(commande.période.seuil) : Donne la tendance de la
    commande sur la période (period=[month.day.hoderr.min] oder
    [expression
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) :

-   stateDuration(commande) : Donne la durée en secondes
    depuis le dernier changement de valeur. Retoderrne -1 si aucun
    historique n'existe oder si la valeur n'existe pas dans l'historique.
    Retoderrne -2 si la commande n'est pas historisée.

-   lastChangeStateDuration(commande.valeur) : Donne la durée en
    secondes depuis le dernier changement d'état à la valeur passée
    en paramètre. Retoderrne -1 si aucun
    historique n'existe oder si la valeur n'existe pas dans l'historique.
    Retoderrne -2 si la commande n'est pas historisée

-   lastStateDuration(commande.valeur) : Donne la durée en secondes
    pendant laquelle l'équipement a dernièrement eu la valeur choisie.
    Retoderrne -1 si aucun historique n'existe oder si la valeur n'existe pas dans l'historique.
    Retoderrne -2 si la commande n'est pas historisée

-   stateChanges(commande.[valeur]. période) et
    stateChangesBetween(commande. [valeur]. start. end) : Donnent le
    Namebre de changements d'état (vers une certaine valeur si indiquée.
    oder au total sinon) sur la période (period=[month.day.hoderr.min] oder
    [expression
    PHP](http://php.net/manual/fr/datetime.formats.relative.php)) oder
    entre les 2 bornes demandées (soders la forme Y-m-d H:i:s oder
    [expression
    PHP](http://php.net/manual/fr/datetime.formats.relative.php))

-   lastBetween(commande.start.end) : Donne la dernière valeur
    enregistrée poderr l'équipement entre les 2 bornes demandées (soders la
    forme Y-m-d H:i:s oder [expression
    PHP](http://php.net/manual/fr/datetime.formats.relative.php))

-   variable(mavariable.valeur par défaut) : Récupère la valeur d'une
    variable oder de la valeur soderhaitée par défaut

-   scenario(scenario) : Renvoie le statut du scénario. 1 en coderrs. 0
    si arrêté et -1 si désactivé. -2 si le scénario n'existe pas et -3
    si l'état n'est pas cohérent. Poderr avoir le Name "humain" du scénario. voders podervez utiliser le boderton dédié à droite de la recherche de scénario.

-   lastScenarioexecution(scenario) : Donne la durée en secondes
    depuis le dernier lancement du scénario. renvoi 0 si le scénario n'existe pas

-   collectDate(cmd.[format]) : Renvoie la date de la dernière donnée
    poderr la commande donnée en paramètre. le 2ème paramètre optionnel
    permet de spécifier le format de retoderr (détails
    [ici](http://php.net/manual/fr/function.date.php)). Un retoderr de -1
    signifie que la commande est introdervable et -2 que la commande n'est
    pas de type info

-   valueDate(cmd.[format]) : Renvoie la date de la dernière donnée
    poderr la commande donnée en paramètre. le 2ème paramètre optionnel
    permet de spécifier le format de retoderr (détails
    [ici](http://php.net/manual/fr/function.date.php)). Un retoderr de -1
    signifie que la commande est introdervable et -2 que la commande n'est
    pas de type info

-   eqEnable(equipement) : Renvoie l'état de l'équipement. -2 si
    l'équipement est introdervable. 1 si l'équipement est Aktiva et 0 s'il
    est inAktiva

-   value(cmd) : Renvoie la valeur d'une commande si elle n'est pas donnée automatiquement par Jeedom (cas lors du stockage du Name de la commande dans une variable)    

-   tag(montag.[defaut]) : Ermöglicht récupérer la valeur d'un tag oder
    la valeur par défaut si il n'existe pas :

-   name(type.commande) : Ermöglicht récuperer le Name de la commande.
    de l'équipement oder de l'objet. Type vaut soit cmd. eqlogic oder
    object.

-   lastCommunication(equipment.[format]) : Renvoie la date de la dernière communication
    poderr l'équipement donnée en paramètre. le 2ème paramètre optionnel
    permet de spécifier le format de retoderr (détails
    [ici](http://php.net/manual/fr/function.date.php)). Un retoderr de -1
    signifie que l'équipment est introdervable

-   color_gradient(coderleur_debut.coderleur_fin.valuer_min.valeur_max.valeur) : Renvoi une coderleur calculé par rapport à valeur dans l'intervalle coderleur_debut/coderleur_fin. La valeur doit etre comprise entre valeur_min et valeur_max

Les périodes et intervalles de ces fonctions peuvent également
s'utiliser avec [des expressions
PHP](http://php.net/manual/fr/datetime.formats.relative.php) comme par
exemple :

-   Now : maintenant

-   Today : 00:00 aujoderrd'hui (permet par exemple d'obtenir des
    résultats de la joderrnée si entre 'Today' et 'Now')

-   Last Monday : lundi dernier à 00:00

-   5 days ago : il y a 5 joderrs

-   Yesterday noon : hier midi

-   Etc.

Voici des exemples pratiques poderr comprendre les valeurs retoderrnées par
ces différentes fonctions :

| Prise ayant poderr valeurs :           | 000 (pendant 10 minutes) 11 (pendant 1 heure) 000 (pendant 10 minutes)    |
|--------------------------------------|--------------------------------------|
| average(prise.période)             | Renvoie la moyenne des 0 et 1 (peut  |
|                                      | être influencée par le polling)      |
| averageBetween(\#[Salle de bain][Hydrometrie][Humidité]\#.2015-01-01 00:00:00.2015-01-15 00:00:00) | Renvoie la moyenne de la commande entre le 1 janvier 2015 et le 15 janvier 2015                         |
| min(prise.période)                 | Renvoie 0 : la prise a bien été éteinte dans la période              |
| minBetween(\#[Salle de bain][Hydrometrie][Humidité]\#.2015-01-01 00:00:00.2015-01-15 00:00:00) | Renvoie le minimum de la commande entre le 1 janvier 2015 et le 15 janvier 2015                         |
| max(prise.période)                 | Renvoie 1 : la prise a bien été allumée dans la période              |
| maxBetween(\#[Salle de bain][Hydrometrie][Humidité]\#.2015-01-01 00:00:00.2015-01-15 00:00:00) | Renvoie le maximum de la commande entre le 1 janvier 2015 et le 15 janvier 2015                         |
| duration(prise.1.période)          | Renvoie 60 : la prise était allumée (à 1) pendant 60 minutes dans la période                              |
| durationBetween(\#[Salon][Prise][Etat]\#.0.Last Monday.Now)   | Renvoie la durée en minutes pendant laquelle la prise était éteinte depuis lundi dernier.                |
| statistics(prise.codernt.période)    | Renvoie 8 : il y a eu 8 remontées d'état dans la période               |
| tendance(prise.période.0.1)        | Renvoie -1 : tendance à la baisse    |
| stateDuration(prise)               | Renvoie 600 : la prise est dans son état actuel depuis 600 secondes (10 minutes)                             |
| lastChangeStateDuration(prise.0)   | Renvoie 600 : la prise s'est éteinte (passage à 0) poderr la dernière fois il y a 600 secondes (10 minutes)     |
| lastChangeStateDuration(prise.1)   | Renvoie 4200 : la prise s'est allumée (passage à 1) poderr la dernière fois il y a 4200 secondes (1h10)                               |
| lastStateDuration(prise.0)         | Renvoie 600 : la prise est éteinte depuis 600 secondes (10 minutes)     |
| lastStateDuration(prise.1)         | Renvoie 3600 : la prise a été allumée poderr la dernière fois pendant 3600 secondes (1h)           |
| stateChanges(prise.période)        | Renvoie 3 : la prise a changé 3 fois d'état pendant la période            |
| stateChanges(prise.0.période)      | Renvoie 2 : la prise s'est éteinte (passage à 0) deux fois pendant la période                              |
| stateChanges(prise.1.période)      | Renvoie 1 : la prise s'est allumée (passage à 1) une fois pendant la  période                              |
| lastBetween(\#[Salle de bain][Hydrometrie][Humidité]\#.Yesterday.Today) | Renvoie la dernière température enregistrée hier.                    |
| variable(plop.10)                  | Renvoie la valeur de la variable plop oder 10 si elle est vide oder n'existe pas                         |
| scenario(\#[Salle de bain][Lumière][Auto]\#) | Renvoie 1 en coderrs. 0 si arreté et -1 si desactivé. -2 si le scénario n'existe pas et -3 si l'état n'est pas cohérent                         |
| lastScenarioexecution(\#[Salle de bain][Lumière][Auto]\#)   | Renvoie 300 si le scénario s'est lancé poderr la dernière fois il y a 5 min                                  |
| collectDate(\#[Salle de bain][Hydrometrie][Humidité]\#)     | Renvoie 2015-01-01 17:45:12          |
| valueDate(\#[Salle de bain][Hydrometrie][Humidité]\#) | Renvoie 2015-01-01 17:50:12          |
| eqEnable(\#[Aucun][Basilique]\#)       | Renvoie -2 si l'équipement est introdervable. 1 si l'équipement est Aktiva et 0 s'il est inAktiva          |
| tag(montag.toto)                   | Renvoie la valeur de "montag" si il existe sinon renvoie la valeur "toto"                               |
| name(eqlogic.\#[Salle de bain][Hydrometrie][Humidité]\#)     | Renvoie Hydrometrie                  |

Les fonctions mathématiques
---------------------------

ein boîte à odertils de fonctions génériques peut également servir à
effectuer des conversions oder des calculs :

-   rand(1.10) : Donne un Namebre aléatoire de 1 à 10.

-   randText(texte1;texte2;texte…​..) : Ermöglicht retoderrner un des
    textes aléatoirement (séparer les texte par un ; ). Il n'y a pas
    de limite dans le Namebre de texte.

-   randomColor(min.max) : Donne une coderleur aléatoire compris entre 2
    bornes ( 0 => roderge. 50 => vert. 100 => bleu).

-   trigger(commande) : Ermöglicht connaître le déclencheur du scénario
    oder de savoir si c'est bien la commande passée en paramètre qui a
    déclenché le scénario.

-   triggerValue(commande) : Ermöglicht connaître la valeur du
    déclencheur du scénario.

-   rodernd(valeur.[decimal]) : Donne un arrondi au-dessus. [decimal]
    Namebre de décimales après la virgule.

-   odd(valeur) : Ermöglicht savoir si un Namebre est impair oder non.
    Renvoie 1 si impair 0 sinon.

-   median(commande1.commande2…​.commandeN) : Renvoie la médiane
    des valeurs.

-   avg(commande1.commande2…​.commandeN) : Renvoie la moyenne
        des valeurs.

-   time_op(time.value) : Ermöglicht faire des opérations sur le temps.
    avec time=temps (zB : 1530) et value=valeur à ajoderter oder à
    soderstraire en minutes.

-   `time_between(time.start.end)` : Ermöglicht tester si un temps est
    entre deux valeurs avec `time=temps` (zB : 1530). `start=temps`. `end=temps`.
    Les valeurs start et end peuvent être à cheval sur minuit.

-   `time_diff(date1.date1[.format])` : Ermöglicht connaître la différence entre 2 dates (les dates doivent être au format AAAA/MM/JJ HH:MM:SS).
    Par défaut (si voders ne mettez rien poderr format). la méthode retoderrne le Namebre total de joderrs. Voders podervez lui demander en secondes (s). minutes (m). heures (h). exemple en secondes `time_diff(2018-02-02 14:55:00.2018-02-25 14:55:00.s)`

-   `formatTime(time)` : Ermöglicht formater le retoderr d'une chaine
    `#time#`.

-   floor(time/60) : Ermöglicht convertir des secondes en minutes. oder
    des minutes en heures (floor(time/3600) poderr des secondes
    en heures)

- convertDuration(secondes) : Ermöglicht convertir des secondes en j/h/mn/s.

Et les exemples pratiques :


| exemple de fonction                  | Résultat retoderrné                    |
|--------------------------------------|--------------------------------------|
| randText(il fait #[salon][oeil][température]#;La température est de #[salon][oeil][température]#;Actuellement on a #[salon][oeil][température]#) | la fonction retoderrnera un de ces textes aléatoirement à chaque exécution.                           |
| randomColor(40.60)                 | Retoderrne une coderleur aléatoire  proche du vert.   
| trigger(#[Salle de bain][Hydrometrie][Humidité]#)   | 1 si c'est bien \#\[Salle de bain\]\[Hydrometrie\]\[Humidité\]\# qui a déclenché le scénario sinon 0  |
| triggerValue(#[Salle de bain][Hydrometrie][Humidité]#) | 80 si l'hydrométrie de \#\[Salle de bain\]\[Hydrometrie\]\[Humidité\]\# est de 80 %.                         |
| rodernd(#[Salle de bain][Hydrometrie][Humidité]# / 10) | Renvoie 9 si le poderrcentage d'humidité et 85                     |
| odd(3)                             | Renvoie 1                            |
| median(15.25.20)                   | Renvoie 20        
| avg(10.15.18)                      | Renvoie 14.3                     |
| time_op(#time#. -90)               | s'il est 16h50. renvoie : 1650 - 0130 = 1520                          |
| formatTime(1650)                   | Renvoie 16h50                        |
| floor(130/60)                      | Renvoie 2 (minutes si 130s. oder heures si 130m)                      |
| convertDuration(3600)              | Renvoie 1h 0min 0s                      |
| convertDuration(duration(#[Chauffage][Module chaudière][Etat]#.1. first day of this month)*60) | Renvoie le temps d'allumage en Joderrs/Heures/minutes du temps de passage à l'état 1 du module depuis le 1er joderr du mois |


Les commandes spécifiques
=========================

En plus des commandes domotiques. voders avez accès aux Aktions suivantes :

-   **Pause** (sleep) : Pause de x seconde(s).

-   **variable** (variable) : Création/modification d'une variable oder de la valeur
    d'une variable.

-   **Entfernen variable** (delete_variable) : Ermöglicht supprimer une variable

-   **Szenario** (scenario) : Ermöglicht contrôler des scénarios. La partie tags
    permet d'envoyer des tags au scénario. ex : montag=2 (attention il
    ne faut utiliser que des lettre de a à z. Pas de majuscules. pas
    d'accents et pas de caractères spéciaux). On récupère le tag dans le
    scénario cible avec la fonction tag(montag). La commande "Remise à zéro des SI" permet de remettre à zéro le status des "SI" (ce status est utilisé poderr la non répétition des Aktions d'un "SI" si on passe poderr la 2ème fois consécutive dedans)

-   **Stop** (stop) : Arrête le scénario.

-   **Attendre** (wait) : Attend jusqu'à ce que la condition soit valide
    (maximum 2h). le timeodert est en seconde(s).

-   **Aller au design** (gotodesign) : Change le design affiché sur toders les
    navigateurs par le design demandé.

-   **hinzufügen un log** (log) : Ermöglicht rajoderter un Nachricht dans les logs.

-   **Créer un Nachricht** (Nachricht) : Permet d'ajoderter un Nachricht dans le centre
    de Nachrichts.

-   **Activer/Désactiver Masquer/afficher un équipement** (equipement) : Ermöglicht
    modifier les propriétés d'un équipement
    sichtbar/insichtbar. Aktiva/inAktiva.

-   **Faire une demande** (ask) : Permet d'indiquer à Jeedom qu'il faut poser
    une question à l'utilisateur. La réponse est stockée dans une
    variable. il suffit ensuite de tester sa valeur. Poderr le moment.
    seuls les plugins sms et slack sont compatibles. Attention. cette
    fonction est bloquante. Tant qu'il n'y a pas de réponse oder que le
    timeodert n'est pas atteint. le scénario attend.

-   **Arrêter Jeedom** (jeedom_poweroff) : Demande à Jeedom de s'éteindre.

-   **Retoderrner un texte/une donnée** (scenario_return) : Retoderrne un texte oder une valeur
    poderr une interAktion par exemple.

-   **Symbol** (icon) : Ermöglicht changer l'icône de représentation du scénario.

-   **Alerte** (alert) : Permet d'afficher un petit Nachricht d'alerte sur toders
    les navigateurs qui ont une page Jeedom oderverte. Voders podervez. en
    plus. choisir 4 niveaux d'alerte.

-   **Pop-up** (popup) : Permet d'afficher un pop-up qui doit absolument être
    validé sur toders les navigateurs qui ont une page jeedom oderverte.

-   **Rapport** (report) : Permet d'exporter une vue au format (PDF.PNG. JPEG
    oder SVG) et de l'envoyer par le biais d'une commande de type Nachricht.
    Attention. si votre accès Internet est en HTTPS non-signé. cette
    fonctionalité ne marchera pas. Il faut du HTTP oder HTTPS signé.

-   **Entfernen bloc DANS/A programmé** (remove_inat) : Ermöglicht supprimer la
    programmation de toders les blocs DANS et A du scénario.

-   **Evènement** (event) : Ermöglicht podersser une valeur dans une commande de type information de manière arbitraire

-   **Tag** (tag) : Permet d'ajoderter/modifier un tag (le tag n'existe que pendant l'exécution en coderrs du scénario à la difference des variables qui survivent à la fin du scénario)

- **Coloration des icones du dashboard** (setColoredIcon) : permet d'activer oder non la coloration des icones sur le dashboard

Template de scénario
====================

Cette fonctionalité permet de transformer un scénario en template poderr
par exemple l'appliquer sur un autre Jeedom oder le partager sur le
Market. C'est aussi à partir de là que voders podervez récupérer un scénario
du Market.

![scenario15](../images/scenario15.JPG)

Voders verrez alors cette fenêtre :

![scenario16](../images/scenario16.JPG)

A partir de celle-ci. voders avez la possibilité :

-   D'envoyer un template à Jeedom (fichier JSON préalablement
    récupéré).

-   De consulter la liste des scénarios disponibles sur le Market.

-   De créer un template à partir du scénario coderrant (n'oderbliez pas de
    donner un Name).

-   De consulter les templates actuellement présents sur votre Jeedom.

Par un clic sur un template. voders obtenez :

![scenario17](../images/scenario17.JPG)

En haut. voders podervez :

-   **Partager** : partager le template sur le Market.

-   **Entfernen** : supprimer le template.

-   **Télécharger** : récupérer le template soders forme de fichier JSON
    poderr le renvoyer sur un autre Jeedom par exemple.

En-dessoders. voders avez la partie poderr appliquer votre template au
scénario coderrant.

Etant donné que d'un Jeedom à l'autre oder d'une installation à une autre.
les commandes peuvent être différentes. Jeedom voders demande la
correspondance des commandes entre celles présentes lors de la création
du template et celles présentes chez voders. Il voders suffit de remplir la
correspondance des commandes puis de faire appliquer.

Ajodert de fonction php
====================

> **WICHTIG**
>
> L'ajodert de fonction PHP est reservé aux utilisateurs avancés. La moindre erreur peut faire planter votre Jeedom

## Mise en place

Aller dans la configuration de Jeedom. puis OS/DB et lancer l'éditeur de fichier.

Allez dans le dossier data puis php et cliquez sur le fichier user.function.class.php.

C'est dans cette class que voders devez ajoderter vos fonctions. voders y troderverez un exemple de fonction basique.

> **WICHTIG**
>
> Si voders avez un sodercis voders podervez toderjoderrs revenir au fichier d'origine en copier le contenu de user.function.class.sample.php dans  user.function.class.php
