= Scénario Voici la partie la plus importante dans la domotique : les
scénarios. Véritable cerveau de la domotique, c’est ce qui permet
d’interagir avec le monde réel de manière "intelligente". \*
&gt; \*\*
&gt; : La gestion globale des scénarios. \*\*
&gt; : La liste des scénarios créés. \*
&gt; \*\*
&gt; : Les paramètres généraux d'un scénario. \*\*
&gt; : La page de confection d'un scénario, avec la présentation des
différents blocs. \*
&gt; \*\*
&gt; : Des déclencheurs propres à Jeedom. \*\*
&gt; : Ce qui permet de comparer des valeurs. \*\*
&gt; : Les tags inclus dans Jeedom. \*\*
&gt; : Les fonctions de calcul de valeurs. \*\*
&gt; : Les opérations mathématiques disponibles. \*
&gt; \*
&gt; == \[\[gestions\]\]La page de gestion des Scénarios ===
\[\[gestion\]\]Gestion Pour y accéder rien de plus simple, il suffit
d'aller sur Outils -&gt; Scénarios. Vous y trouverez la liste des
scénarios de votre Jeedom ainsi que des fonctions pour les gérer au
mieux : \* \*Ajouter\* : Permet de créer un scénario. La procédure est
décrite dans le chapitre suivant. \* \*Désactiver scénarios\* : Permet
de désactiver tous les scénarios. \* \*Voir variables\* : Permet de voir
les variables, leur valeur ainsi que l'endroit où elle sont utilisées.
Vous pouvez également y en créer une. Les variables sont décrites dans
un chapitre de cette page. \* \*Vue d'ensemble\* : Permet d'avoir une
vue d'ensemble de tous les scénarios. Vous pouvez changer les valeurs
\*actif\*, \*visible\*, \*multi lancement\*, \*mode synchrone\*, \*Log\*
et \*Timeline\* (ces paramètres sont décrits dans le chapitre suivant).
Vous pouvez également accéder aux logs de chaque scénario et les
démarrer individuellement. \* \*Testeur d'expression\* : Permet
d'executer un test sur une expression de votre choix et d'en afficher le
résultat. === \[\[messcenarios\]\]Mes scénarios Vous trouverez dans
cette partie la \*liste des scénarios\* que vous avez créés. Ils sont
classés suivant les \*groupes\* que vous avez définis pour chacun d'eux.
Chaque scénario est affiché avec son \*nom\* et son \*objet parent\*.
Les \*scénarios grisés\* sont ceux qui sont désactivés. Comme dans de
nombreuses pages de Jeedom, mettre la souris à gauche de l'écran permet
de faire apparaître un menu d'accès rapide (à partir de votre profil,
vous pouvez le laisser toujours visible). Vous pourrez alors
\*chercher\* votre scénario, mais aussi en \*ajouter\* un par ce menu.
== \[\[edition\]\]Edition d'un scénario Après avoir cliqué sur
\*Ajouter\*, vous devez choisir le nom de votre scénario et vous êtes
redirigés vers la page de ses paramètres généraux. En haut, on retrouve
quelques fonctions utiles pour gérer notre scénario : \* \*ID\* : A côté
du mot \*Général\*, c'est l'identifiant du scénario. \* \*statut\* :
Etat actuel de votre scénario. \* \*variables\* : Permet d'afficher les
variables. \* \*Expression\* : Permet d'afficher le testeur
d'expression. \* \*Exécuter\* : Permet de lancer le scénario
manuellement (N'oubliez pas de sauvegarder au préalable !). Les
déclencheurs ne sont donc pas pris en compte. \* \*Supprimer\* : Permet
de supprimer le scénario. \* \*Sauvegarder\* : Permet de sauvegarder les
changements effectués. \* \*Template\* : Permet d'accéder aux templates
et d'en appliquer un au scénario depuis le market. (expliqué en bas de
page). \* \*Exporter\* : Permet d'obtenir une version texte du scénario.
\* \*Log\* : Permet d'afficher les logs du scénario. \* \*Dupliquer\* :
Permet de copier le scénario pour en créer un nouveau avec un autre nom.
\* \*Liens\* : Permet de visualiser le graphique des éléments en lien
avec le scénario. === \[\[ongletgeneral\]\]Onglet Général Dans l'onglet
\*Général\*, on retrouve les paramètres principaux de notre scénario :
\* \*Nom du scénario\* : Le nom de votre scénario. \* \*Nom à afficher\*
: Le nom utilisé pour son affichage. \* \*Groupe\* : Permet d'organiser
les scénarios, en les classant dans des groupes. \* \*Actif\* : Permet
d'activer le scénario. \* \*Visible\* : Permet de rendre visible le
scénario. \* \*Objet parent\* : Affectation à un objet parent. \*
\*Timeout secondes (0 = illimité)\* : La durée d’exécution maximale
autorisée \* \*Multi lancement\* : Cochez cette case si vous souhaitez
que le scénario puisse être lancé plusieurs fois en même temps. \*
\*Mode synchrone\* : Attention, cela peut rendre le système instable. \*
\*Log\* : Le type de log souhaité pour le scénario. \* \*Suivre dans la
timeline\* : Permet de garder un suivi du scénario dans la timeline. \*
\*Description\* : Permet d'écrire un petit texte pour décrire votre
scénario. \* \*Mode du scénario\* : Le scénario peut être programmé,
déclenché ou les deux à la fois. Vous aurez ensuite le choix d'indiquer
le(s) déclencheur(s) et la/les programmation(s). \[TIP\] Attention :
vous pouvez avoir au maximum 28 déclencheurs/programmations pour un
scénario. === \[\[ongletscenario\]\]Onglet Scénario C'est ici que vous
allez construire votre scénario. Il faut commencer par \*ajouter un
bloc\*, avec le bouton situé à droite. Une fois un bloc créé, vous
pourrez y ajouter un autre \*bloc\* ou une \*action\*. ==== Les blocs
Voici les différents types de blocs disponibles : \* \*Si/Alors/Sinon\*
: Permet de réaliser des actions sous condition(s). \* \*Action\* :
Permet de lancer des actions simples sans aucune condition. \*
\*Boucle\* : Permet de réaliser des actions de manière répétitive de 1
jusqu’à un nombre défini (ou même la valeur d’un capteur, ou un nombre
aléatoire...). \* \*Dans\* : Permet de lancer une action dans X
minute(s) (0 est une valeur possible). La particularité est que les
actions sont lancées en arrière-plan, elles ne bloquent donc pas la
suite du scénario. C'est donc un bloc non bloquant. \* \*A\* : Permet de
dire à Jeedom de lancer les actions du bloc à une heure donnée (sous la
forme hhmm). Ce bloc est non bloquant. Ex : 0030 pour 00h30, ou 0146
pour 1h46 et 1050 pour 10h50. \* \*Code\* : Permet d’écrire directement
en code PHP (demande certaines connaissances et peut être risqué mais
permet de n'avoir aucune contrainte). \* \*Commentaire\* : Permet
d'ajouter des commentaires à son scénario. Chacun de ces blocs a ces
options pour mieux les manipuler : \* La case à cocher, à gauche, permet
de désactiver complètement le bloc sans pour autant le supprimer. \* La
double-flèche verticale, à gauche, permet de déplacer tout le bloc par
glisser/déposer. \* Le bouton, tout à droite, permet de supprimer le
bloc entier. ===== Blocs Si/Alors/Sinon , Boucle, Dans et A \[NOTE\] Sur
les blocs de type Si/Alors/Sinon, des flèches circulaires situées à
gauche du champ de condition permettent d'activer ou non la répétition
des actions si l'évaluation de la condition donne le même résultat que
la précedente évaluation. Pour les conditions, Jeedom essaye de faire en
sorte qu’on puisse les écrire le plus possible en langage naturel tout
en restant souple. trois boutons sont disponibles sur la droite de ce
type de bloc pour sélectionner un élément à tester : \* \*Rechercher une
commande\* : Permet de chercher une commande dans toutes celles
disponibles dans Jeedom. Une fois la commande trouvée, Jeedom ouvre une
fenêtre pour vous demander quel test vous souhaitez effectuer sur
celle-ci. Si vous choisissez de \*Ne rien mettre\*, Jeedom ajoutera la
commande sans comparaison. Vous pouvez également choisir \*et\* ou
\*ou\* devant \*Ensuite\* pour enchaîner des tests sur différents
équipements. \* \*Rechercher un scénario\* : Permet de chercher un
scénario à tester. \* \*Rechercher un équipement\* : Idem pour un
équipement. \[TIP\] Il existe une liste de tags permettant d’avoir accès
à des variables issues du scénario ou d’un autre, ou bien à l’heure, la
date, un nombre aléatoire,…. Voir plus loin les chapitres sur les
commandes et les tags. Une fois la condition renseignée, vous devez
utiliser le bouton "ajouter", à gauche, afin d'ajouter un nouveau
\*bloc\* ou une \*action\* dans le bloc actuel. ===== Bloc Code
\[IMPORTANT\] Attention les tags ne sont pas disponibles dans un bloc de
type code. Commandes (capteurs et actionneurs):: \*
\`\*cmd::byString(\$string)\*;\` : Retourne l'objet commande
correspondant. \*\* \`\$string\` : Lien vers la commande voulue :
\`\\\#\[objet\]\[equipement\]\[commande\]\#\` (ex :
\`\\\#\[Appartement\]\[Alarme\]\[Actif\]\#\`) \*
\`\*cmd::byId(\$id)\*;\` : Retourne l'objet commande correspondant. \*\*
\`\$id\` : ID de la commande voulue \* \`\*\$cmd-&gt;execCmd(\$options =
null)\*;\` : Exécute la commande et retourne le résultat. \*\*
\`\$options\` : Options pour l'exécution de la commande (peut être
spécifique au plugin), option de base (sous-type de la commande) :
\*\*\* \`message\` : \`\$option = array('title' =&gt; 'titre du message
, 'message' =&gt; 'Mon message');\` \*\*\* \`color\` : \`\$option =
array('color' =&gt; 'couleur en hexadécimal');\` \*\*\* \`slider\` :
\`\$option = array('slider' =&gt; 'valeur voulue de 0 à 100');\` Log::
\* \`\*log::add('filename','level','message')\*;\` \*\* \`filename\` :
Nom du fichier de log. \*\* \`level\` : \`\[debug\]\`, \`\[info\]\`,
\`\[error\]\`, \`\[event\]\`. \*\* \`message\` : Message à écrire dans
les logs. Scénario:: \* \`\*\$scenario\\-&gt;getName()\*;\` : Retourne
le nom du scénario courant. \* \`\*\$scenario\\-&gt;getGroup()\*;\` :
Retourne le groupe du scénario. \*
\`\*\$scenario\\-&gt;getIsActive()\*;\` : Retourne l'état du scénario.
\* \`\*\$scenario\\-&gt;setIsActive(\$active)\*;\` : Permet d'activer ou
non le scénario. \*\* \`\$active\` : 1 actif , 0 non actif. \*
\`\*\$scenario\\-&gt;setOnGoing(\$onGoing)\*;\` : Permet de dire si le
scénario est en cours ou non. \*\* \`\$onGoing\` =&gt; 1 en cours , 0
arrêté. \* \`\*\$scenario\\-&gt;save()\*;\` : Sauvegarde les
modifications. \* \`\*\$scenario\\-&gt;setData(\$key, \$value)\*;\` :
Sauvegarde une donnée (variable). \*\* \`\$key\` : clé de la valeur (int
ou string). \*\* \`\$value\` : valeur à stocker (int, string, array ou
object). \* \`\*\$scenario\\-&gt;getData(\$key)\*;\` : Récupère une
donnée (variable). \*\* \`\$key\` =&gt; clé de la valeur (int ou
string). \* \`\*\$scenario\\-&gt;removeData(\$key)\*;\` : Supprime une
donnée. \* \`\*\$scenario\\-&gt;setLog(\$message)\*;\` : Ecris un
message dans le log du scénario. \*
\`\*\$scenario\\-&gt;persistLog()\*;\` : Force l'écriture du log (sinon
il est écrit seulement à la fin du scénario). Attention ceci peut un peu
ralentir le scénario. ==== Les Actions Les actions ajoutées dans les
blocs ont plusieurs options. Dans l'ordre : \* Une case \*parallèle\*
pour que cette commande se lance en parallèle des autres commandes
également sélectionnées. \* Une case \*activée\* pour que cette commande
soit bien prise en compte dans le scénario. \* Une \*double-flèche
verticale\* pour déplacer l'action. Il suffit de le glisser/déposer à
partir de là. \* Un bouton pour supprimer l'action. \* Un bouton pour
les actions spécifiques, avec à chaque fois la description de cette
action. \* Un bouton pour rechercher une commande d'action. \[TIP\]
Suivant la commande sélectionnée, on peut voir apparaître différents
champs supplémentaires s'afficher. == \[\[substitutions\]\]Les
substitutions possibles === \[\[declencheurs\]\]Les déclencheurs Il
existe des déclencheurs spécifiques (autre que ceux fournis par les
commandes) : \* \`\*\\\#start\#\*\` : déclenché au (re)démarrage de
Jeedom, \* \`\*\\\#begin\_backup\#\*\` : événement envoyé au début d'une
sauvegarde. \* \`\*\\\#end\_backup\#\*\` : événement envoyé à la fin
d'une sauvegarde. \* \`\*\\\#begin\_update\#\*\` : événement envoyé au
début d'une mise à jour. \* \`\*\\\#end\_update\#\*\` : événement envoyé
à la fin d'une mise à jour. \* \`\*\\\#begin\_restore\#\*\` : événement
envoyé au début d'une restauration. \* \`\*\\\#end\_restore\#\*\` :
événement envoyé à la fin d'une restauration. Vous pouvez aussi
déclencher un scénario quand une variable est mise à jour en mettant :
\`\*\\\#variable(nom\_variable)\#\*\` ou en utilisant l'API HTTP décrite
https://github.com/jeedom/core/blob/master/doc/fr\_FR/api\_http.asciidoc\[ici\].
=== \[\[operateurs\]\]Opérateurs de comparaison et liens entre les
conditions Vous pouvez utiliser n'importe lequel des symboles suivant
pour les comparaisons dans les conditions : \* \`==\` : égal à, \*
\`&gt;\` : strictement supérieur à, \* \`&gt;=\` : supérieur ou égal à,
\* \`
humide/"\` ), \* \`not ( ... matches ...)\` : ne contient pas (ex :
\`not(\#\[Salle de bain\]\[Hydrometrie\]\[etat\]\# matches
"/humide/")\`), Vous pouvez combiner n'importe quelle comparaison avec
les opérateurs suivants : \* \`&\` / \`ET\` / \`ET\` / \`AND\` / \`AND\`
: ET, \* \`||\` / \`OU\` / \`OU\` / \`OR\` / \`OR\` : OU, \* \`|\^\` /
\`XOR\` / \`XOR\` : OU EXCLUSIF. === \[\[TAGS\]\]LES TAGS UN TAG EST
REMPLACÉ LORS DE L'EXÉCUTION DU SCÉNARIO PAR SA VALEUR. VOUS POUVEZ
UTILISER LES TAGS SUIVANTS : \[TIP\] POUR AVOIR LES ZÉROS INTIAUX À
L'AFFICHAGE, IL FAUT UTILIER LA FONCTION DATE(). VOIR
HTTP://PHP.NET/MANUAL/FR/FUNCTION.DATE.PHP\[ICI\]. \*
\`\*\\\#SECONDE\#\*\` : SECONDE COURANTE (SANS LES ZÉROS INITIAUX, EX :
6 POUR 08:07:06), \* \`\*\\\#HEURE\#\*\` : HEURE COURANTE AU FORMAT 24H
(SANS LES ZÉROS INITIAUX, EX : 8 POUR 08:07:06 OU 17 POUR 17:15), \*
\`\*\\\#HEURE12\#\*\` : HEURE COURANTE AU FORMAT 12H (SANS LES ZÉROS
INITIAUX, EX : 8 POUR 08:07:06), \* \`\*\\\#MINUTE\#\*\` : MINUTE
COURANTE (SANS LES ZÉROS INITIAUX, EX : 7 POUR 08:07:06), \*
\`\*\\\#JOUR\#\*\` : JOUR COURANT (SANS LES ZÉROS INITIAUX, EX : 6 POUR
06/07/2017), \* \`\*\\\#MOIS\#\*\` : MOIS COURANT (SANS LES ZÉROS
INITIAUX, EX : 7 POUR 06/07/2017), \* \`\*\\\#ANNEE\#\*\` : ANNÉE
COURANTE, \* \`\*\\\#TIME\#\*\` : HEURE ET MINUTE COURANTE (EX : 1715
POUR 17H15), \* \`\*\\\#TIMESTAMP\#\*\` : NOMBRE DE SECONDES DEPUIS LE
1ER JANVIER 1970, \* \`\*\\\#DATE\#\*\` : JOUR ET MOIS. ATTENTION, LE
PREMIER NOMBRE EST LE MOIS. (EX : 1215 POUR LE 15 DÉCEMBRE), \*
\`\*\\\#SEMAINE\#\*\` : NUMÉRO DE LA SEMAINE (EX : 51), \*
\`\*\\\#SJOUR\#\*\` : NOM DU JOUR DE LA SEMAINE (EX : SAMEDI), \*
\`\*\\\#NJOUR\#\*\` : NUMÉRO DU JOUR DE 0 (DIMANCHE) À 6 (SAMEDI), \*
\`\*\\\#SMOIS\#\*\` : NOM DU MOIS (EX : JANVIER), \* \`\*\\\#IP\#\*\` :
IP INTERNE DE JEEDOM, \* \`\*\\\#HOSTNAME\#\*\` : NOM DE LA MACHINE
JEEDOM, \* \`\*\\\#TRIGGER\#\*\` : NOM DE LA COMMANDE QUI A DÉCLENCHÉ LE
SCÉNARIO. VOUS AVEZ AUSSI LES TAGS SUIVANTS EN PLUS SI VOTRE SCÉNARIO A
ÉTÉ DÉCLENCHÉ PAR UNE INTERACTION : \* \`\*\\\#QUERY\#\*\` : INTERACTION
AYANT DÉCLENCHÉ LE SCÉNARIO, \* \`\*\\\#PROFIL\#\*\` : PROFIL DE
L'UTILISATEUR AYANT DÉCLENCHÉ LE SCÉNARIO (PEUT ÊTRE VIDE).
\[IMPORTANT\] LORSQU'UN SCÉNARIO EST DÉCLENCHÉ PAR UNE INTERACTION,
CELUI-CI EST FORCÉMENT EXÉCUTÉ EN MODE RAPIDE. === \[\[CALCULS\]\]LES
FONCTIONS DE CALCUL PLUSIEURS FONCTIONS SONT DISPONIBLES POUR LES
ÉQUIPEMENTS : \* \`\*AVERAGE\*(COMMANDE,PÉRIODE)\` ET
\`\*AVERAGEBETWEEN\*(COMMANDE,START,END)\` : DONNENT LA MOYENNE DE LA
COMMANDE SUR LA PÉRIODE (\`PERIOD=\[MONTH,DAY,HOUR,MIN\]\` OU
HTTP://PHP.NET/MANUAL/FR/DATETIME.FORMATS.RELATIVE.PHP\[EXPRESSION
PHP\]) OU ENTRE LES 2 BORNES DEMANDÉES (SOUS LA FORME \`Y-M-D H:I:S\` OU
HTTP://PHP.NET/MANUAL/FR/DATETIME.FORMATS.RELATIVE.PHP\[EXPRESSION
PHP\]) : \* \`\*MIN\*(COMMANDE,PÉRIODE)\` ET
\`\*MINBETWEEN\*(COMMANDE,START,END)\` : DONNENT LE MINIMUM DE LA
COMMANDE SUR LA PÉRIODE (\`PERIOD=\[MONTH,DAY,HOUR,MIN\]\` OU
HTTP://PHP.NET/MANUAL/FR/DATETIME.FORMATS.RELATIVE.PHP\[EXPRESSION
PHP\]) OU ENTRE LES 2 BORNES DEMANDÉES (SOUS LA FORME \`Y-M-D H:I:S\` OU
HTTP://PHP.NET/MANUAL/FR/DATETIME.FORMATS.RELATIVE.PHP\[EXPRESSION
PHP\]) : \* \`\*MAX\*(COMMANDE,PÉRIODE)\` ET
\`\*MAXBETWEEN\*(COMMANDE,START,END)\` : DONNENT LE MAXIMUM DE LA
COMMANDE SUR LA PÉRIODE (\`PERIOD=\[MONTH,DAY,HOUR,MIN\]\` OU
HTTP://PHP.NET/MANUAL/FR/DATETIME.FORMATS.RELATIVE.PHP\[EXPRESSION
PHP\]) OU ENTRE LES 2 BORNES DEMANDÉES (SOUS LA FORME Y-M-D H:I:S OU
HTTP://PHP.NET/MANUAL/FR/DATETIME.FORMATS.RELATIVE.PHP\[EXPRESSION
PHP\]) : \* \`\*DURATION\*(COMMANDE, VALEUR, PÉRIODE)\` ET
\`\*DURATIONBETWEEN\*(COMMANDE,VALEUR,START,END)\` : DONNENT LA DURÉE EN
MINUTES PENDANT LAQUELLE L'ÉQUIPEMENT AVAIT LA VALEUR CHOISIE SUR LA
PÉRIODE (\`PERIOD=\[MONTH,DAY,HOUR,MIN\]\` OU
HTTP://PHP.NET/MANUAL/FR/DATETIME.FORMATS.RELATIVE.PHP\[EXPRESSION
PHP\]) OU ENTRE LES 2 BORNES DEMANDÉES (SOUS LA FORME \`Y-M-D H:I:S\` OU
HTTP://PHP.NET/MANUAL/FR/DATETIME.FORMATS.RELATIVE.PHP\[EXPRESSION
PHP\]) : \* \`\*STATISTICS\*(COMMANDE,CALCUL,PÉRIODE)\` ET
\`\*STATISTICSBETWEEN\*(COMMANDE,CALCUL,START,END)\` : DONNENT LE
RÉSULTAT DE DIFFÉRENTS CALCULS STATISTIQUES (\`SUM\`, \`COUNT\`,
\`STD\`, \`VARIANCE\`, \`AVG\`, \`MIN\`, \`MAX\`) SUR LA PÉRIODE
(\`PERIOD=\[MONTH,DAY,HOUR,MIN\]\` OU
HTTP://PHP.NET/MANUAL/FR/DATETIME.FORMATS.RELATIVE.PHP\[EXPRESSION
PHP\]) OU ENTRE LES 2 BORNES DEMANDÉES (SOUS LA FORME \`Y-M-D H:I:S\` OU
HTTP://PHP.NET/MANUAL/FR/DATETIME.FORMATS.RELATIVE.PHP\[EXPRESSION
PHP\]) : \* \`\*TENDANCE\*(COMMANDE,PÉRIODE,SEUIL)\` : DONNE LA TENDANCE
DE LA COMMANDE SUR LA PÉRIODE (\`PERIOD=\[MONTH,DAY,HOUR,MIN\]\` OU
HTTP://PHP.NET/MANUAL/FR/DATETIME.FORMATS.RELATIVE.PHP\[EXPRESSION
PHP\]) : \* \`\*STATEDURATION\*(COMMANDE,\[VALEUR\])\` : DONNE LA DURÉE
EN SECONDES DEPUIS LE DERNIER CHANGEMENT DE VALEUR. RETOURNE -1 SI AUCUN
HISTORIQUE N'EXISTE OU SI LA VALEUR N'EXISTE PAS DANS L'HISTORIQUE.
RETURN -2 SI LA COMMANDE N'EST PAS HISTORISÉE : \*
\`\*LASTCHANGESTATEDURATION\*(COMMANDE,VALEUR)\` : DONNE LA DURÉE EN
SECONDES DEPUIS LE DERNIER CHANGEMENT D'ÉTAT À LA VALEUR PASSÉE EN
PARAMÈTRE. ATTENTION, LA VALEUR DE L'ÉQUIPEMENT DOIT ÊTRE HISTORISÉE. \*
\`\*LASTSTATEDURATION\*(COMMANDE,VALEUR)\` : DONNE LA DURÉE EN SECONDES
PENDANT LAQUELLE L'ÉQUIPEMENT A DERNIÈREMENT EU LA VALEUR CHOISIE.
ATTENTION, LA VALEUR DE L'ÉQUIPEMENT DOIT ÊTRE HISTORISÉE. \*
\`\*STATECHANGES\*(COMMANDE,\[VALEUR\], PÉRIODE)\` ET
\`\*STATECHANGESBETWEEN\*(COMMANDE, \[VALEUR\], START, END)\` : DONNENT
LE NOMBRE DE CHANGEMENTS D'ÉTAT (VERS UNE CERTAINE VALEUR SI INDIQUÉE,
OU AU TOTAL SINON) SUR LA PÉRIODE (\`PERIOD=\[MONTH,DAY,HOUR,MIN\]\` OU
HTTP://PHP.NET/MANUAL/FR/DATETIME.FORMATS.RELATIVE.PHP\[EXPRESSION
PHP\]) OU ENTRE LES 2 BORNES DEMANDÉES (SOUS LA FORME \`Y-M-D H:I:S\` OU
HTTP://PHP.NET/MANUAL/FR/DATETIME.FORMATS.RELATIVE.PHP\[EXPRESSION
PHP\]) : \* \`\*LASTBETWEEN\*(COMMANDE,START,END)\` : DONNE LA DERNIÈRE
VALEUR ENREGISTRÉE POUR L'ÉQUIPEMENT ENTRE LES 2 BORNES DEMANDÉES (SOUS
LA FORME \`Y-M-D H:I:S\` OU
HTTP://PHP.NET/MANUAL/FR/DATETIME.FORMATS.RELATIVE.PHP\[EXPRESSION
PHP\]) : \* \`\*VARIABLE\*(MAVARIABLE,VALEUR PAR DÉFAUT)\` : RÉCUPÈRE LA
VALEUR D'UNE VARIABLE OU DE LA VALEUR SOUHAITÉE PAR DÉFAUT : \*
\`\*SCENARIO\*(SCENARIO)\` : RENVOIE LE STATUT DU SCÉNARIO. 1 EN COURS,
0 SI ARRÊTÉ ET -1 SI DÉSACTIVÉ, -2 SI LE SCÉNARIO N'EXISTE PAS ET -3 SI
L'ÉTAT N'EST PAS COHÉRENT. \* \`\*LASTSCENARIOEXECUTION\*(SCENARIO)\` :
DONNE LA DURÉE EN SECONDES DEPUIS LE DERNIER LANCEMENT DU SCÉNARIO : \*
\`\*COLLECTDATE\*(CMD,\[FORMAT\])\` : RENVOIE LA DATE DE LA DERNIÈRE
DONNÉE POUR LA COMMANDE DONNÉE EN PARAMÈTRE, LE 2ÈME PARAMÈTRE OPTIONEL
PERMET DE SPÉCIFIER LE FORMAT DE RETOUR (DÉTAILS
HTTP://PHP.NET/MANUAL/FR/FUNCTION.DATE.PHP\[ICI\]). UN RETOUR DE -1
SIGNIFIE QUE LA COMMANDE EST INTROUVABLE ET -2 QUE LA COMMANDE N'EST PAS
DE TYPE INFO : \* \`\*VALUEDATE\*(CMD,\[FORMAT\])\` : RENVOIE LA DATE DE
LA DERNIÈRE DONNÉE POUR LA COMMANDE DONNÉE EN PARAMÈTRE, LE 2ÈME
PARAMÈTRE OPTIONEL PERMET DE SPÉCIFIER LE FORMAT DE RETOUR (DÉTAILS
HTTP://PHP.NET/MANUAL/FR/FUNCTION.DATE.PHP\[ICI\]). UN RETOUR DE -1
SIGNIFIE QUE LA COMMANDE EST INTROUVABLE ET -2 QUE LA COMMANDE N'EST PAS
DE TYPE INFO : \* \`\*EQENABLE\*(EQUIPEMENT)\` : RENVOIE L'ÉTAT DE
L'ÉQUIPEMENT. -2 SI L’ÉQUIPEMENT EST INTROUVABLE, 1 SI L’ÉQUIPEMENT EST
ACTIF ET 0 S’IL EST INACTIF \* \`\*TAG\*(MONTAG,\[DEFAUT\])\` : PERMET
DE RÉCUPERER LA VALEUR D'UN TAG OU LA VALEUR PAR DÉFAUT SI IL N'EXISTE
PAS : \* \`\*NAME\*(TYPE,COMMANDE)\` : PERMET DE RÉCUPERER LE NOM DE LA
COMMANDE, DE L'ÉQUIPEMENT OU DE L'OBJET. \`TYPE\` VAUT SOIT \`CMD\`,
\`EQLOGIC\` OU \`OBJECT\` : LES PÉRIODES ET INTERVALLES DE CES FONCTIONS
PEUVENT ÉGALEMENT S'UTILISER AVEC
HTTP://PHP.NET/MANUAL/FR/DATETIME.FORMATS.RELATIVE.PHP\[DES EXPRESSIONS
PHP\] COMME PAR EXEMPLE : \* \`NOW\` : MAINTENANT \* \`TODAY\` : 00:00
AUJOURD'HUI (PERMET PAR EXEMPLE D'OBTENIR DES RÉSULTATS DE LA JOURNÉE SI
ENTRE 'TODAY' ET 'NOW') \* \`LAST MONDAY\` : LUNDI DERNIER À 00:00 \*
\`5 DAYS AGO\` : IL Y A 5 JOURS \* \`YESTERDAY NOON\` : HIER MIDI \*
ETC. VOICI DES EXEMPLES PRATIQUES POUR COMPRENDRE LES VALEURS RETOURNÉES
PAR CES DIFFÉRENTES FONCTIONS : \[OPTIONS="HEADER",WIDTH="100%"\]
|====================== | PRISE AYANT POUR VALEURS : | 000 (PENDANT 10
MINUTES) 11 (PENDANT 1 HEURE) 000 (PENDANT 10 MINUTES) |
\`AVERAGE(PRISE,PÉRIODE)\` | RENVOIE LA MOYENNE DES 0 ET 1 (PEUT ÊTRE
INFLUENCÉE PAR LE POLLING) | \`AVERAGEBETWEEN(\#\[SALLE DE
BAIN\]\[HYDROMETRIE\]\[HUMIDITÉ\]\#,2015-01-01 00:00:00,2015-01-15
00:00:00)\` | RENVOIE LA MOYENNE DE LA COMMANDE ENTRE LE 1 JANVIER 2015
ET LE 15 JANVIER 2015 | \`MIN(PRISE,PÉRIODE)\` | RENVOIE 0 : LA PRISE A
BIEN ÉTÉ ÉTEINTE DANS LA PÉRIODE | \`MINBETWEEN(\#\[SALLE DE
BAIN\]\[HYDROMETRIE\]\[HUMIDITÉ\]\#,2015-01-01 00:00:00,2015-01-15
00:00:00)\` | RENVOIE LE MINIMUM DE LA COMMANDE ENTRE LE 1 JANVIER 2015
ET LE 15 JANVIER 2015 | \`MAX(PRISE,PÉRIODE)\` | RENVOIE 1 : LA PRISE A
BIEN ÉTÉ ALLUMÉE DANS LA PÉRIODE | \`MAXBETWEEN(\#\[SALLE DE
BAIN\]\[HYDROMETRIE\]\[HUMIDITÉ\]\#,2015-01-01 00:00:00,2015-01-15
00:00:00)\` | RENVOIE LE MAXIMUM DE LA COMMANDE ENTRE LE 1 JANVIER 2015
ET LE 15 JANVIER 2015 | \`DURATION(PRISE,1,PÉRIODE)\` | RENVOIE 60 : LA
PRISE ÉTAIT ALLUMÉE (À 1) PENDANT 60 MINUTES DANS LA PÉRIODE |
\`DURATIONBETWEEN(\#\[SALON\]\[PRISE\]\[ETAT\]\#,0,LAST MONDAY,NOW)\` |
RENVOIE LA DURÉE EN MINUTES PENDANT LAQUELLE LA PRISE ÉTAIT ÉTEINTE
DEPUIS LUNDI DERNIER. | \`STATISTICS(PRISE,COUNT,PÉRIODE)\` | RENVOIE 8
: IL Y A EU 8 REMONTÉES D'ÉTAT DANS LA PÉRIODE |
\`TENDANCE(PRISE,PÉRIODE,0.1)\` | RENVOIE -1 : TENDANCE À LA BAISSE |
\`STATEDURATION(PRISE)\` | RENVOIE 600 : LA PRISE EST DANS SON ÉTAT
ACTUEL DEPUIS 600 SECONDES (10 MINUTES) | \`STATEDURATION(PRISE,0)\` |
RENVOIE 600 : LA PRISE EST ÉTEINTE (À 0) DEPUIS 600 SECONDES (10
MINUTES) | \`STATEDURATION(PRISE,1)\` | RENVOIE UNE VALEUR COMPRISE
ENTRE 0 ET STATEDURATION(PRISE) (SELON VOTRE POLLING) : LA PRISE N'EST
PAS DANS CET ÉTAT | \`LASTCHANGESTATEDURATION(PRISE,0)\` | RENVOIE 600 :
LA PRISE S'EST ÉTEINTE (PASSAGE À 0) POUR LA DERNIÈRE FOIS IL Y A 600
SECONDES (10 MINUTES) | \`LASTCHANGESTATEDURATION(PRISE,1)\` | RENVOIE
4200 : LA PRISE S'EST ALLUMÉE (PASSAGE À 1) POUR LA DERNIÈRE FOIS IL Y A
4200 SECONDES (1H10) | \`LASTSTATEDURATION(PRISE,0)\` | RENVOIE 600 : LA
PRISE EST ÉTEINTE DEPUIS 600 SECONDES (10 MINUTES) |
\`LASTSTATEDURATION(PRISE,1)\` | RENVOIE 3600 : LA PRISE A ÉTÉ ALLUMÉE
POUR LA DERNIÈRE FOIS PENDANT 3600 SECONDES (1H) |
\`STATECHANGES(PRISE,PÉRIODE)\` | RENVOIE 3 : LA PRISE A CHANGÉ 3 FOIS
D'ÉTAT PENDANT LA PÉRIODE | \`STATECHANGES(PRISE,0,PÉRIODE)\` | RENVOIE
2 : LA PRISE S'EST ÉTEINTE (PASSAGE À 0) DEUX FOIS PENDANT LA PÉRIODE |
\`STATECHANGES(PRISE,1,PÉRIODE)\` | RENVOIE 1 : LA PRISE S'EST ALLUMÉE
(PASSAGE À 1) UNE FOIS PENDANT LA PÉRIODE | \`LASTBETWEEN(\#\[SALLE DE
BAIN\]\[HYDROMETRIE\]\[HUMIDITÉ\]\#,YESTERDAY,TODAY)\` | RENVOIE LA
DERNIÈRE TEMPÉRATURE ENREGISTRÉE HIER. | \`VARIABLE(PLOP,10)\` | RENVOIE
LA VALEUR DE LA VARIABLE PLOP OU 10 SI ELLE EST VIDE OU N’EXISTE PAS |
\`SCENARIO(\#\[SALLE DE BAIN\]\[LUMIÈRE\]\[AUTO\]\#)\` | RENVOIE 1 EN
COURS, 0 SI ARRETÉ ET -1 SI DESACTIVÉ, -2 SI LE SCÉNARIO N’EXISTE PAS ET
-3 SI L’ÉTAT N’EST PAS COHÉRENT | \`LASTSCENARIOEXECUTION(\#\[SALLE DE
BAIN\]\[LUMIÈRE\]\[AUTO\]\#)\` | RENVOIE 300 SI LE SCÉNARIO S’EST LANCÉ
POUR LA DERNIÈRE FOIS IL Y A 5 MIN | \`COLLECTDATE(\#\[SALLE DE
BAIN\]\[HYDROMETRIE\]\[HUMIDITÉ\]\#)\` | RENVOIE 2015-01-01 17:45:12 |
\`VALUEDATE(\#\[SALLE DE BAIN\]\[HYDROMETRIE\]\[HUMIDITÉ\]\#)\` |
RENVOIE 2015-01-01 17:50:12 | \`EQENABLE(\#\[AUCUN\]\[BASILIQUE\]\#)\` |
RENVOIE -2 SI L’ÉQUIPEMENT EST INTROUVABLE, 1 SI L’ÉQUIPEMENT EST ACTIF
ET 0 S’IL EST INACTIF | \`TAG(MONTAG,TOTO)\` | RENVOIE LA VALEUR DE
"MONTAG" SI IL EXISTE SINON RENVOIE LA VALEUR "TOTO" |
\`NAME(EQLOGIC,\[SALLE DE BAIN\]\[HYDROMETRIE\]\[HUMIDITÉ\])\` | RENVOIE
HYDROMETRIE |====================== === \[\[MATHS\]\]LES FONCTIONS
MATHÉMATIQUES UNE BOÎTE À OUTILS DE FONCTIONS GÉNÉRIQUES PEUT ÉGALEMENT
SERVIR À EFFECTUER DES CONVERSIONS OU DES CALCULS : \*
\`\*RAND\*(1,10)\` : DONNE UN NOMBRE ALÉATOIRE DE 1 À 10. \*
\`\*RANDTEXT\*(TEXTE1texte2;texte.....)\` : Permet de retourner un des
textes aléatoirement (séparer les texte par un \`;\` ). Il n'y a pas de
limite dans le nombre de texte. \* \`\*randomColor\*(min,max)\` : Donne
une couleur aléatoire compris entre 2 bornes ( 0 =&gt; rouge, 50 =&gt;
vert, 100 =&gt; bleu). \* \`\*trigger\*(commande)\` : Permet de
connaître le déclencheur du scénario ou de savoir si c'est bien la
commande passée en paramètre qui a déclenché le scénario. \*
\`\*triggerValue\*(commande)\` : Permet de connaître la valeur du
déclencheur du scénario. \* \`\*round\*(valeur,\[decimal\])\` : Donne un
arrondi au-dessus, \`\[decimal\]\` nombre de décimales après la virgule.
\* \`\*odd\*(valeur)\` : Permet de savoir si un nombre est impair ou
non. Renvoie 1 si impair 0 sinon. \*
\`\*median\*(commande1,commande2....commandeN)\` : Renvoie la médiane
des valeurs. \* \`\*time\_op\*(time,value)\` : Permet de faire des
opérations sur le temps, avec \`time=temps\` (ex : 1530) et
\`value=valeur\` à ajouter ou à soustraire en minutes. \*
\`\*formatTime\*(time)\` : Permet de formater le retour d'une chaine
\`\\\#time\#\`. \* \`\*floor\*(time/60)\` : Permet de convertir des
secondes en minutes, ou des minutes en heures (\`floor(time/3600)\` pour
des secondes en heures) Et les exemples pratiques :
\[options="header",width="100%"\] |====================== | Exemple de
fonction | Résultat retourné | \`randText(il fait
\\\#\[salon\]\[oeil\]\[température\]\#;La température est de
\\\#\[salon\]\[oeil\]\[température\]\#;Actuellement on a
\\\#\[salon\]\[oeil\]\[température\]\#)\` | la fonction retournera un de
ces textes aléatoirement à chaque exécution. | \`randomColor(40,60)\` |
Retourne une couleur aléatoire proche du vert. | \`trigger(\\\#\[Salle
de bain\]\[Hydrometrie\]\[Humidité\]\#)\` | 1 si c'est bien \\\#\[Salle
de bain\]\[Hydrometrie\]\[Humidité\]\# qui a déclenché le scénario sinon
0 | \`triggerValue(\\\#\[Salle de bain\]\[Hydrometrie\]\[Humidité\]\#)\`
| 80 si l'hydrométrie de \\\#\[Salle de
bain\]\[Hydrometrie\]\[Humidité\]\# est de 80 %. | \`round(\\\#\[Salle
de bain\]\[Hydrometrie\]\[Humidité\]\# / 10)\` | Renvoie 9 si le
pourcentage d'humidité et 85 | \`odd(3)\` | Renvoie 1 |
\`median(15,25,20)\` | Renvoie 20 | \`time\_op(\\\#time\#, -90)\` | s'il
est 16h50, renvoie : 1650 - 0130 = 1520 | \`formatTime(1650)\` | Renvoie
16h50 | \`floor(130/60)\` | Renvoie 2 (minutes si 130s, ou heures si
130m) |====================== == \[\[commandes\]\]Les commandes
spécifiques En plus des commandes domotiques vous avez accès aux actions
suivantes : \* \*Pause\* : Pause de x seconde(s). \* \*variable\* :
Création/modification d'une variable ou de la valeur d'une variable. \*
\*Scénario\* : Permet de contrôler des scénarios. La partie tags permet
d'envoyer des tags au scénario, ex : \`montag=2\` (attention il ne faut
utiliser que des lettre de a à z. Pas de majuscule, pas d'accent et pas
de caractères spéciaux). On récupere le tag dans le scénario cible avec
la fonction \`tag(montag)\`. \* \*stop\* : Arrête le scénario. \*
\*Attendre\* : Attend jusqu'à ce que la condition soit valide (maximum
2h), le timeout est en seconde(s). \* \*Aller au design\* : Change le
design affiché sur tous les navigateurs par le design demandé. \*
\*Ajouter un log\* : Permet de rajouter un message dans les logs. \*
\*Créer un message\* : Permet d'ajouter un message dans le centre de
message. \* \*Activer/Désactiver Masquer/afficher un équipement\* :
Permet de modifier les propriétés d'un équipement visible/invisible,
actif/inactif. \* \*Faire une demande\* : Permet d'indiquer à Jeedom
qu'il faut poser une question à l'utilisateur. La réponse est stockée
dans une variable, il suffit ensuite de tester sa valeur. Pour le moment
seuls les plugins sms et slack sont compatibles. Attention, cette
fonction est bloquante. Tant qu'il n'y a pas de réponse ou que le
timeout n'est pas atteint le scénario attend. \* \*Arrêter Jeedom\* :
Demande à Jeedom de s'éteindre. \* \*Retourner un texte/une donnée\* :
Retourne un texte ou une valeur pour une interaction par exemple. \*
\*Icône\* : Permet de changer l'icône de représentation du scénario. \*
\*Alerte\* : Permet d'afficher un petit message d'alerte sur tous les
navigateurs qui ont une page Jeedom ouverte. Vous pouvez, en plus,
choisir 4 niveaux d'alerte. \* \*Pop-up\* : Permet d'afficher un pop-up
qui doit absolument être validé sur tous les navigateurs qui ont une
page jeedom ouverte. \* \*Rapport\* : Permet d'exporter une vue au
format (PDF,PNG, JPEG ou SVG) et de l'envoyer par le biais d'une
commande de type message. Attention si votre accès Internet est en HTTPS
non-signé, cette fonctionalité ne marchera pas. Il faut du HTTP ou HTTPS
signé. \* \*Supprimer bloc DANS/A programmé\* : Permet de supprimer la
programmation de tous les blocs DANS et A du scénario. ==
\[\[template\]\]Template de scénario Cette fonctionalité permet de
transformer un scénario en template pour par exemple l'appliquer sur un
autre Jeedom ou le partager sur le Market. C'est aussi à partir de là
que vous pouvez récupérer un scénario du Market.
image::../images/scenario15.JPG\[\] Vous verrez alors cette fenêtre :
image::../images/scenario16.JPG\[\] A partir de celle-ci que vous avez
la possibilité : \* D'envoyer un template à Jeedom (fichier JSON
préalablement récupéré), \* De consulter la liste des scénarios
disponibles sur le Market, \* De créer un template à partir du scénario
courant (n'oubliez pas de donner un nom), \* De Consulter les templates
actuellement présent sur votre Jeedom. Par un clic sur un template vous
obtenez : image::../images/scenario17.JPG\[\] En haut, vous pouvez : \*
\*Partager\* : partager le template sur le Market, \* \*Supprimer\* :
supprimer le template, \* \*Télécharger\* : récupérer le template sous
forme de fichier JSON pour le renvoyer sur un autre Jeedom par exemple.
En-dessous, vous avez la partie pour appliquer votre template au
scénario courant. Etant donné que d'un Jeedom à l'autre ou d'une
installation à une autre les commandes peuvent être différentes, Jeedom
vous demande la correspondance des commandes entre celles présentes lors
de la création du template et celles présentes chez vous. Il vous suffit
de remplir la correspondance des commandes puis de faire appliquer.
