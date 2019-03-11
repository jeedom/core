Partie importante dans un logiciel : la partie historisation, véritable
mémoire de celui-ci. Il est possible dans Jeedom d’historiser n’importe
quelle commande de type information (binaire ou numérique). Cela vous
permettra donc par exemple d’historiser une courbe de température, de
consommation ou les ouvertures d’une porte, etc.​

Principe 
========

Ici est décrit le principe d’historisation de Jeedom. Il n’est
nécessaire de le comprendre que si vous rencontrez des soucis
d’historisation ou que vous voulez modifier les réglages de
l’historisation. Les réglages par défaut conviennent dans la plupart des
cas.

Archivage 
---------

L’archivage de données permet à Jeedom de réduire la quantité de données
conservées en mémoire. Cela permet de ne pas utiliser trop de place et
de ne pas ralentir le système. En effet, si vous conservez toutes les
mesures, cela fait d’autant plus de points à afficher et donc peut
considérablement allonger les temps pour rendre un graphique. En cas
d’un nombre trop important de points, cela peut même faire planter
l’affichage du graphique.

L’archivage est une tâche qui se lance dans la nuit et compacte les
données récupérées dans la journée. Par défaut Jeedom récupère toutes
les données plus vieilles de 2h et en fait des paquets de 1h (soit une
moyenne, un minimum ou un maximum en fonction des réglages). On a donc
ici 2 paramètres, un pour la taille des paquets et un autre pour savoir
à partir de quand en faire (pour rappel par défaut ce sont des paquets
de 1h avec des données qui ont plus de 2h d’ancienneté).

> **Tip**
>
> Si vous avez bien suivi vous devriez avoir une haute précision sur les
> 2 dernières heures seulement. Pourtant quand je me connecte à 17h,
> j’ai une précision sur les 17 dernières heures. Pourquoi ? En fait,
> pour éviter de consommer des ressources inutilement, la tâche qui fait
> l’archivage ne se déroule qu’une fois par jour, le soir.

> **Important**
>
> Bien sûr, ce principe d’archivage ne s’applique qu’aux commandes de
> type numérique ; sur les commandes de type binaire, Jeedom ne conserve
> que les dates de changement d’état.

Affichage d’un graphique 
========================

Il existe plusieurs moyens d’accéder à l’historique :

-   en mettant une zone graphe dans une vue (voir plus bas),

-   en cliquant sur la commande voulue dans un widget,

-   en allant dans la page historique qui permet de superposer
    différentes courbes et de combiner les styles (aire, courbe, barre)

-   en mobile en restant appuyé sur le widget en question

Si vous affichez un graphique par la page historique ou en cliquant sur
le widget, vous avez accès à plusieurs options d’affichage :

On retrouve en haut à droite la période d’affichage (ici sur la dernière
semaine car, par défaut je veux que ça soit seulement une semaine - voir
2 paragraphes au-dessus), ensuite viennent les paramètres de la courbe
(ces paramètres sont gardés d’un affichage à l’autre ; vous n’avez donc
qu’à les configurer une seule fois).

-   **Escalier** : permet d’afficher la courbe sous la forme d’un
    escalier ou d’un affichage continu.

-   **Variation** : affiche la différence de valeur par rapport au
    point précédent.

-   **Ligne** : affiche le graphique sous forme de lignes.

-   **Aire** : affiche le graphique sous forme d’une aire.

-   **Colonne**\* : affiche le graphique sous forme de barres.

Graphique sur les vues et les designs 
=====================================

Vous pouvez aussi afficher les graphiques sur les vues (nous verrons ici
les options de configuration et non comment faire, pour cela il faut se
rendre sur la documention des vues ou des designs en fonction). Voici
les options :

Une fois une donnée activée, vous pouvez choisir :

-   **Couleur** : la couleur de la courbe.

-   **Type** : le type de graphique (aire, ligne ou colonne).

-   **Echelle** : vu que vous pouvez mettre plusieurs courbes (données)
    sur le même graphique, il est possible de distinguer les échelles
    (droite ou gauche).

-   **Escalier** : permet d’afficher la courbe sous la forme d’un
    escalier ou d’un affichage continu

-   **Empiler** : permet d’empiler les valeurs des courbes (voir en
    dessous pour le résultat).

-   **Variation** : affiche la différence de valeur par rapport au
    point précédent.

Option sur la page d’historique 
===============================

La page d’historique donne accès à quelques options supplémentaires

Historique calculé 
------------------

Permet d’afficher une courbe en fonction d’un calcul sur plusieurs
commande (vous pouvez à peu prêt tout faire, +-/\* valeur absolue…​ voir
documentation PHP pour certaines fonctions). Ex :
abs(*\[Jardin\]\[Hygrometrie\]\[Température\]* - *\[Espace de
vie\]\[Hygrométrie\]\[Température\]*)

Vous avez aussi accès à un gestion de formules de calcul qui vous permet
de les sauvegarder pour les réafficher plus facilement

> **Tip**
>
> Il suffit de cliquer sur le nom de l’objet pour le déplier ;
> apparaissent les commandes historisées qui peuvent être graphées.

Historique de commande 
----------------------

Devant chaque donnée pouvant être graphée, vous retrouvez deux icônes :

-   **Poubelle** : permet de supprimer les données enregistrées ; lors
    du clic, Jeedom demande s’il faut supprimer les données avant une
    certaine date ou toutes les données.

-   **Flèche** : permet d’avoir un export CSV des données historisées.

Suppression de valeur incohérente 
=================================

Parfois, il se peut que vous ayez des valeurs incohérentes sur les
graphiques. Cela est souvent dû à un souci d’interprétation de la
valeur. Il est possible de supprimer ou changer la valeur du point en
question, en cliquant sur celui-ci directement sur le graphique ; de
plus, vous pouvez régler le minimum et le maximum autorisés afin
d’éviter des problèmes futurs.

Timeline 
========

La timeline affiche certains événements de votre domotique sous forme
chronologique.

Pour les voir, il vous faut d’abord activer le suivi sur la timeline des
commandes ou scénarios voulus :

-   **Scenario** : soit directement sur la page de scénario, soit sur la
    page de résumé des scénarios pour le faire en "masse"

-   **Commande** : soit dans la configuration avancée de la commande,
    soit dans la configuration de l’historique pour le faire en "masse"

> **Tip**
>
> Vous avez accès aux fenêtres de résumé des scénarios ou de la
> configuration de l’historique directement à partir de la page de
> timeline.

Une fois que vous avez activé le suivi dans la timeline des commandes et
scénarios voulus, vous pourrez voir apparaître ceux-ci sur la timeline.

> **Important**
>
> Il faut attendre de nouveaux événements après avoir activé le suivi
> sur la timeline avant de les voir apparaître.

Les cartes sur la timeline affichent :

-   **Commande action** : en fond rouge, une icône à droite vous permet
    d’afficher la fenêtre de configuration avancée de la commande

-   **Commande info** : en fond bleu, une icône à droite vous permet
    d’afficher la fenêtre de configuration avancée de la commande

-   **Scénario** : en fond gris, vous avez 2 icônes : une pour afficher
    le log du scénario et une pour aller sur le scénario


