# Historique
**Analyse → Historique**

Partie importante dans un logiciel : la partie historisation, véritable mémoire de celui-ci. Il est possible dans Jeedom d’historiser n’importe quelle commande de type information (binaire ou numérique). Cela vous permettra donc par exemple d’historiser une courbe de température, de consommation ou les ouvertures d’une porte, etc.​

### Principe

Ici est décrit le principe d’historisation de Jeedom. Il n’est nécessaire de le comprendre que si vous rencontrez des soucis d’historisation ou que vous voulez modifier les réglages de l’historisation. Les réglages par défaut conviennent dans la plupart des cas.

### Archivage

L’archivage de données permet à Jeedom de réduire la quantité de données conservées en mémoire. Cela permet de ne pas utiliser trop de place et de ne pas ralentir le système. En effet, si vous conservez toutes les mesures, cela fait d’autant plus de points à afficher et donc cela peut considérablement allonger les temps pour rendre un graphique. En cas d’un nombre trop important de points, cela peut même faire planter l’affichage du graphique.

L’archivage est une tâche qui se lance dans la nuit et compacte les données récupérées dans la journée. Par défaut Jeedom récupère toutes les données plus vieilles de 2h et en fait des paquets de 1h (soit une moyenne, un minimum ou un maximum en fonction des réglages). On a donc ici deux paramètres, un pour la taille des paquets et un autre pour savoir à partir de quand en faire (pour rappel par défaut ce sont des paquets de 1h avec des données qui ont plus de 2h d’ancienneté).

> **Tip**
>
> Si vous avez bien suivi vous devriez avoir une haute précision sur les 2 dernières heures seulement. Pourtant quand je me connecte à 17h, j’ai une précision sur les 17 dernières heures. Pourquoi ? En fait, pour éviter de consommer des ressources inutilement, la tâche qui fait l’archivage ne se déroule qu’une fois par jour, le soir.

> **Important**
>
> Bien sûr, ce principe d’archivage ne s’applique qu’aux commandes de type numérique ; sur les commandes de type binaire, Jeedom ne conserve que les dates de changement d’état.

### Affichage d’un graphique

Il existe plusieurs moyens d’accéder à l’historique :

- En cliquant sur la commande voulue dans un widget,
- En allant dans la page historique qui permet de superposer différentes courbes et de combiner les styles (aire, courbe, barre),
- En mobile en restant appuyé sur le widget en question,
- En mettant une zone graphe dans une vue (voir plus bas).

## Onglet Historique

Si vous affichez un graphique par la page historique, vous avez accès à plusieurs options d’affichage :

On retrouve en haut à droite la période d’affichage (ici sur la dernière semaine car, par défaut je veux que ça soit seulement une semaine - voir 2 paragraphes au-dessus), ensuite viennent les paramètres de la courbe (ces paramètres sont gardés d’un affichage à l’autre ; vous n’avez donc qu’à les configurer une seule fois).

- **Escalier** : Permet d’afficher la courbe sous la forme d’un escalier ou d’un affichage continu.
- **Variation** : Affiche la différence de valeur par rapport au point précédent.
- **Ligne** : Affiche le graphique sous forme de lignes.
- **Aire** : Affiche le graphique sous forme d’une aire.
- **Colonne**\* : Affiche le graphique sous forme de barres.

> **Tip**
>
> Si vous affichez plusieurs courbes en même temps:
> - Un clic sur une légende sous le graphique permet d'afficher / masquer cette courbe.
> - Ctrl Clic sur une légende vous permet de n'afficher que celle-ci.
> - Alt Clic sur une légende vous permet de les afficher toutes.


### Graphique sur les vues et les designs

Vous pouvez aussi afficher les graphiques sur les vues (nous verrons ici les options de configuration et non comment faire, pour cela il faut se rendre sur la documentation des vues ou des designs en fonction). Voici les options :

Une fois une donnée activée, vous pouvez choisir :
- **Couleur** : La couleur de la courbe.
- **Type** : Le type de graphique (aire, ligne ou colonne).
- **Echelle** : Vu que vous pouvez mettre plusieurs courbes (données) sur le même graphique, il est possible de distinguer les échelles (droite ou gauche).
- **Escalier** : Permet d’afficher la courbe sous la forme d’un escalier ou d’un affichage continu.
- **Empiler** : Permet d’empiler les valeurs des courbes (voir en dessous pour le résultat).
- **Variation** : Affiche la différence de valeur par rapport au point précédent.

### Option sur la page d’historique

La page d’historique donne accès à quelques options supplémentaires

#### Historique calculé

Permet d’afficher une courbe en fonction d’un calcul sur plusieurs commande (vous pouvez à peu prêt tout faire, +-/\* valeur absolue…​ voir documentation PHP pour certaines fonctions).
Ex :
abs(*\[Jardin\]\[Hygrometrie\]\[Température\]* - *\[Espace de vie\]\[Hygrométrie\]\[Température\]*)

Vous avez aussi accès à un gestion de formules de calcul qui vous permet de les sauvegarder pour les ré-afficher plus facilement.

> **Tip**
>
> Il suffit de cliquer sur le nom de l’objet pour le déplier, et faire apparaître les commandes historisées qui peuvent être affichées.

#### Historique de commande

Devant chaque donnée pouvant être affichée, vous retrouvez deux icônes :

- **Poubelle** : Permet de supprimer les données enregistrées ; lors du clic, Jeedom demande s’il faut supprimer les données avant une certaine date ou toutes les données.
- **Flèche** : Permet d’avoir un export CSV des données historisées.

### Suppression de valeur incohérente

Parfois, il se peut que vous ayez des valeurs incohérentes sur les graphiques. Cela est souvent dû à un souci d’interprétation de la valeur. Il est possible de supprimer ou changer la valeur du point en question, en cliquant sur celui-ci directement sur le graphique ; de plus, vous pouvez régler le minimum et le maximum autorisés afin d’éviter des problèmes futurs.

## Onglet Timeline

La timeline affiche certains événements de votre domotique sous forme chronologique.

Pour les voir, il vous faut d’abord activer le suivi sur la timeline des commandes ou scénarios voulus, puis que ces évènements se produisent.

- **Scenario** : Soit directement sur la page de scénario, soit sur la page de résumé des scénarios pour le faire en "masse".
- **Commande** : Soit dans la configuration avancée de la commande, soit dans la configuration de l’historique pour le faire en "masse".

La timeline *Principal* contient toujours l'ensemble des évènements. Toutefois, vous pouvez filtrer la timeline par *dossier*. A chaque endroit où vous activerez la timeline, vous disposerez d'un champ pour entrer le nom d'un dossier, existant ou non.
Vous pourrez alors filtrer la timeline par ce dossier en le sélectionnant à gauche du bouton *Rafraichir*.

> **Note**
>
> Si vous n'utilisez plus un dossier, il apparaitra dans la liste tant que des évènements liés a ce dossier existent. Il disparaitra tout seul de la liste ensuite.

> **Tip**
>
> Vous avez accès aux fenêtres de résumé des scénarios ou de la configuration de l’historique directement à partir de la page de timeline.

Une fois que vous avez activé le suivi dans la timeline des commandes et scénarios voulus, vous pourrez voir apparaître ceux-ci sur la timeline.

> **Important**
>
> Il faut attendre de nouveaux événements après avoir activé le suivi sur la timeline avant de les voir apparaître.

### Affichage

La timeline affiche un tableau des évènements enregistrés sur trois colonnes:

- La date et l'heure de l'évènement,
- Le type d'évènement: Une commande info ou action, ou un scénario, avec pour les commandes le plugin de la commande.
- Le nom de l'objet parent, le nom, et suivant le type, l'état ou le déclencheur.

- Un évènement de type commande affiche une icône sur la droite pour ouvrir la configuration de la commande.
- Un évènement de type scénario affiche deux icônes sur la droite pour se rendre sur le scénario, ou ouvrir le log du scénario.

