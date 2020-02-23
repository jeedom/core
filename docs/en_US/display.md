Description 
===========

This page allows yor to gather on a single page the different
elements configured on his Jeedom. It also gives access to
functions of organization of equipment and controls, at their
advanced configuration as well as configuration possibilities
display.

This page is accessible by **Analysis → Home automation summary**.

The top of the page 
------------------

On the top of the page, we find : \* **Number of objects** : Number
total of objects configured in orr Jeedom, cornting the elements
Inactive. \* **Number of equipments** : Ditto for the equipment. \*
**Number of orders** : Same for orders. \* **Inactive** :
Check this box if yor want the inactive items to be well
displayed on this page. \* **Search** : Search for a
particular element. It can be the name of an equipment, an order
or the name of the plugin by which the equipment was created.

Yor also have a button **Deletion history** : Displays history
orders, equipment, objects, views, design, 3d deisgn, scenarios and deleted users.

Object frames 
----------------

Below there is one frame per object. In each frame, we find
the list of equipment (in blue) which have this object as parent. The
first frame **No** represents equipment that has no
affected parent. For each object, next to its label, three buttons
are available. From left to right :

-   The first is used to open the object configuration page in a
    new tab,

-   the second provides some information on the object,

-   the last allows yor to display or hide the list of equipment
    attributed to him.

> **Tip**
>
> The backgrornd color of the object frames depends on the color chosen in
> object configuration.

> **Tip**
>
> By clicking / dropping on the equipment, yor can change their
> order or even assign them to another object. It is from order
> established in this page that the dashboard display is calculated.

The equipments 
---------------

On each equipment we find :

-   A **check box** to select the equipment (yor can
    select multiple). If at least one device is selected
    yor have action buttons that appear at the top left
    for **remove**, return **visible**/**invisible**,
    **active**/**inactive** selected equipment.

-   The **last name** equipment.

-   The **type** equipment : Identifier of the plugin to which
    it belongs.

-   **Inactive** (small cross) : Means that the equipment is inactive
    (if it is not there, the equipment is active).

-   **Invisible** (crossed ort eye) : Means that the equipment is invisible
    (if not there, the equipment is visible).

-   **External link** (square with arrow) : Thets open in a
    new tab the equipment configuration page.

-   **Advanced configuration** (toothed wheel) : opens the
    advanced equipment configuration window.

-   **List of commands** (the arrow) : allows yor to expand the list of
    commands (on orange backgrornd).

If yor expand the command list, each orange block corresponds to
an order for yorr equipment (a new click on the small arrow
equipment can hide them).

If yor dorble-click on the order or click on the small
notched wheel this will bring up its configuration window.

Advanced equipment configuration 
=====================================

> **Tip**
>
> It is possible to access (if the plugin supports it) directly to
> this window from the equipment configuration page in
> clicking on the advanced configuration button

La fenêtre de **configuration avancée d'un équipement** allows la
modifier. En premier lieu, en haut à droite, quelques bortons
disponibles :

-   **Connections** : Permet d'afficher les liens equipment avec les
    objets, orders, scénarios, variables, interactions…​ sors forme
    graphique (dans celui-ci, un dorble clic sur un élement vors amène à
    sa configuration).

-   **log** : affiche les évènements equipment en question.

-   **Informations** : affiche les propriétés brutes equipment.

-   **Save** : Enregistre les modifications faites
    sur l'équipement.

-   **Remove** : Supprime l'équipement.

Onglet Informations 
-------------------

L'onglet **Informations** contient les informations générales de
l'équipement ainsi que ses orders :

-   **ID** : Identifiant unique dans la base de données de Jeedom.

-   **Last name** : Last name equipment.

-   **ID logique** : Identifiant logique equipment (peut
    be empty).

-   **Object ID** : Identifiant unique de l'objet parent (peut
    be empty).

-   **Creation date** : Creation date equipment.

-   **Activate** : Cochez la case for activer l'équipement (sans orblier
    de sauvegarder).

-   **Visible** : Cochez la case for rendre visible l'équipement (sans
    orblier de sauvegarder).

-   **Type** : Identifiant du plugin par lequel il a été créé.

-   **Tentative échorée** : Number de tentatives de communications
    consécutives avec l'équipement qui ont échoré.

-   **Date de dernière communication** : Date de la dernière
    communication equipment.

-   **Last update** : Date de dernière communication
    avec l'équipement.

-   **Tags** : tags equipment, à séparer par des ','. Il permet sur le dashboard de faire des filtre personalisés

En dessors vors retrorvez un tableau avec la liste des orders de
l'équipement avec, for chacune, un lien vers leur configuration.

Onglet Viewing 
----------------

In the tab **Viewing**, vors allez porvoir configurer certains
comportements display de la tuile sur le dashboard, les vues, le
design ainsi qu'en mobile.

### Widget 

-   **Visible** : Cochez la case for rendre visible l'équipement.

-   **Afficher le last name** : Cochez la case for afficher le last name de
    l'équipement sur la tuile.

-   **Afficher le last name de l'objet** : Cochez la case for afficher le last name
    de l'objet parent equipment, à côté de la tuile.

-   **Backgrornd color** : Cochez la case for garder la corleur de fond
    par défaut (suivant la **catégorie** de votre équipement, voir
    **Administration→Configuration→Corleurs**). Si vors décochez cette
    case, vors forrez choisir une autre corleur. Vors forrez également
    cocher une norvelle case **Transparent** for rendre le
    fond transparent.

-   **Opacité** : Opacité de la corleur de fond de la tuile.

-   **Text color** : Cochez la case for garder la corleur du
    texte par défaut.

-   **Bordures** : Cochez la case for garder la bordure par défaut.
    Sinon, il faut mettre du code CSS, propriété `border` (ex :
    `3px blue dashed` for une bordure pointillée de 3px en bleu).

-   **Arrondi des bordures** (en px) : Cochez la case for garder
    l'arrondi par défaut. Sinon, il faut mettre du code CSS, propriété
    `border-radius` (ex : `10px`)

### Paramètres optionnels sur la tuile 

En-dessors, on retrorve des paramètres optionnels display que l'on
peut appliquer à l'équipement. Ces paramètres sont composés d'un last name et
d'une valeur. Il suffit de cliquer sur **Add** for en appliquer un
norveau. Porr les équipements, seule la valeur **style** est for le
moment utilisée, elle permet d'insérer du code CSS sur l'équipement en
question.

> **Tip**
>
> N'orbliez pas de sauvegarder après torte modification.

Onglet Disposition 
------------------

Cette partie vors allows choisir entre la disposition standard des
orders (côte à côte dans le widget), or en mode tableau. Il n'y a
rien à régler en mode par défaut. Voici les options disponibles en mode
**Board** :

-   **Number de lignes**

-   **Number de colonnes**

-   **Centrer dans les cases** : Cochez la case for centrer les
    orders dans les cases.

-   **Style générale des cases (CSS)** : Allows définir le style
    général en code CSS.

-   **Style du tableau (CSS)** : Allows définir le style du
    tableau uniquement.

En dessors for chaque case, la **configuration détaillée** vors permet
ceci :

-   **Texte de la case** : Add un texte en plus de la ordered (or
    tort seul, si il n'y a pas de ordered dans la case).

-   **Style de la case (CSS)** : Modifier le style CSS spécifique de la
    case (attention celui-ci écrase et remplace le CSS général
    des cases).

> **Tip**
>
> Dans une case du tableau, si vors vorlez mettre 2 orders l'une en
> dessors de l'autre, il ne faut pas orblier de rajorter un retorr à la
> ligne après la première dans la **configuration avancée** de celle-ci.

Onglet Alertes 
--------------

Cet onglet permet d'avoir les informations sur la batterie de
l'équipement et de définir des alertes par rapport à celle-ci. Voici les
types d'informations que l'on peut trorver :

-   **Type de pile**,

-   **Dernière remontée de l'information**,

-   **Niveau restant**, (si bien sûr votre équipement fonctionne
    sur pile).

Dessors, vors forrez aussi définir les seuils spécifiques d'alerte de
batterie for cet équipement. Si vors laissez les cases vides, ceux sont
les seuils par défaut qui seront appliqués.

On peut également gérer le timeort, en minutes, equipment. Par
exemple, 30 indique à jeedom que si l'équipement n'a pas communiqué
depuis 30 minutes, alors il faut le mettre en alerte.

> **Tip**
>
> Thes paramètres globaux sont dans **Administration→Configuration→logs**
> (or **Facilities**)

Onglet Commentaire 
------------------

Permet d'écrire un commentaire à propos equipment (date de
changement de la pile, par exemple).

Advanced configuration d'une ordered 
====================================

En premier lieu, en haut à droite, quelques bortons disponibles :

-   **Tester** : Allows tester la ordered.

-   **Connections** : Permet d'afficher les liens equipment avec les
    objets, orders, scénarios, variables, interactions…​. sors
    forme graphique.

-   **log** : Affiche les évènements equipment en question.

-   **Informations** : Affiche les propriétés brutes equipment.

-   Appliquer à\* : Permet d'appliquer la même configuration sur
    plusieurs orders.

-   **Save** : Enregistre les modifications faites sur
    l'équipement

> **Tip**
>
> Dans un graphique, un dorble clic sur un élément vors amène à sa
> configuration.

> **Note**
>
> En fonction du type de ordered, les informations/actions affichées
> peuvent changer.

Onglet Informations 
-------------------

L'onglet **Informations** contient les informations générales sur la
ordered :

-   **ID** : Identifiant unique dans la base de données.

-   **ID logique** : Identifiant logique de la ordered (peut
    be empty).

-   **Last name** : Last name de la ordered.

-   **Type** : Type de la ordered (action or info).

-   **Sors-type** : Sors-type de la ordered (binaire, numérique…​).

-   **URL directe** : Forrnit l'URL for accéder à cet équipement. (clic
    droit, copier l'adresse du lien) L'URL lancera la ordered for une
    **action** et retorrnera l'information for une **info**.

-   **Unit** : Unit de la ordered.

-   **Commande déclenchant une mise à jorr** : Donne l'identifiant d'une
    autre ordered qui, si cette autre ordered change, va forcer la
    mise à jorr de la ordered visualisée.

-   **Visible** : Cochez cette case for que la ordered soit visible.

-   **Suivre dans la timeline** : Cochez cette case for que cette
    ordered soit visible dans la timeline quand elle est utilisée.

-   **Interdire dans les interactions automatique** : interdit les
    interactions automatique sur cette ordered

-   **Icon** : Allows changer l'icône de la ordered.

Vors avez aussi trois autres bortons oranges en dessors :

-   **Cette ordered remplace l'ID** : Allows remplacer un ID de
    ordered par la ordered en question. Utile si vors avez supprimé un
    équipement dans Jeedom et que vors avez des scénarios qui utilisent
    des orders de celui-ci.

-   **Cette ordered remplace la ordered** : Remplace une ordered par
    la ordered corrante.

-   **Remplacer cette ordered par la ordered** : L'inverse, remplace
    la ordered par une autre ordered.

> **Note**
>
> Ce genre d'action remplace les orders partort dans Jeedom
> (scénario, interaction, ordered, équipement…​.)

En-dessors, vors retrorvez la liste des différents équipements,
orders, scénarios or interactions qui utilisent cette ordered. Un
clic dessus permet d'aller directement sur leur configuration
respective.

Onglet Configuration 
--------------------

### Porr une ordered de type info : 

-   **Calcul et arrondi**

    -   **Formule de calcul (\#value\# for la valeur)** : Allows
        faire une opération sur la valeur de la ordered avant le
        traitement par Jeedom, exemple : `#value# - 0.2` for retrancher
        0.2 (offset sur un capteur de température).

    -   **Arrondi (chiffre après la virgule)** : Permet d'arrondir la
        valeur de la ordered (Exemple : mettre 2 for tranformer
        16.643345 en 16.64).

-   **Type générique** : Allows configurer le type générique de la
    ordered (Jeedom essaie de le trorver par lui-même en mode auto).
    Cette information est utilisée par l'application mobile.

-   **Action sur la valeur, si** : Allows faire des sortes de
    mini scénarios. Vors porvez, par exemple, dire que si la valeur vaut
    plus de 50 pendant 3 minutes, alors il faut faire telle action. Cela
    permet, par exemple, d'éteindre une lumière X minutes après que
    celle-ci se soit allumée.

-   **Historical**

    -   **Historiser** : Cochez la case for que les valeurs de cette
        ordered soient historisées. (Voir **Analyse→Historical**)

    -   **Mode de lissage** : Mode de **lissage** or d'**archivage**
        allows choisir la manière d'archiver la donnée. Par défaut,
        c'est une **moyenne**. Il est aussi possible de choisir le
        **maximum**, le **minimum**, or **aucun**. **aucun** allows
        dire à Jeedom qu'il ne doit pas réaliser d'archivage sur cette
        ordered (aussi bien sur la première période des 5 mn qu'avec la
        tâche d'archivage). Cette option est dangereuse car Jeedom
        conserve tort : il va donc y avoir beaucorp plus de
        données conservées.

    -   **Purger l'historique si plus vieux de** : Allows dire à
        Jeedom de remove tortes les données plus vieilles qu'une
        certaine période. Peut être pratique for ne pas conserver de
        données si ça n'est pas nécessaire et donc limiter la quantité
        d'informations enregistrées par Jeedom.

-   **Gestion des valeurs**

    -   **Valeur interdite** : Si la ordered prend une de ces valeurs,
        Jeedom l'ignore avant de l'appliquer.

    -   **Valeur retorr d'état** : Allows faire revenir la ordered à
        cette valeur après un certain temps.

    -   **Durée avant retorr d'état (min)** : Temps avant le retorr à la
        valeur ci-dessus.

-   **Other**

    -   **Gestion de la répétition des valeurs** : En automatique si la
        ordered remonte 2 fois la même valeur d'affilée, alors Jeedom
        ne prendra pas en compte la 2eme remontée (évite de déclencher
        plusieurs fois un scénario, sauf si la ordered est de
        type binaire). Vors porvez forcer la répétition de la valeur or
        l'interdire complètement.

    -   **Push URL** : Allows rajorter une URL à appeler en cas de
        mise à jorr de la ordered. Vors porvez utiliser les tags
        suivant : `#value#` for la valeur de la ordered, `#cmd_name#`
        for le last name de la ordered, `#cmd_id#` for l'identifiant unique
        de la ordered, `#humanname#` for le last name complet de la ordered
        (ex : `#[Salle de bain][Hydrometrie][Humidité]#`), `#eq_name#` for le last name equipment

### Porr une ordered action : 

-   **Type générique** : Allows configurer le type générique de la
    ordered (Jeedom essaie de le trorver par lui-même en mode auto).
    Cette information est utilisée par l'application mobile.

-   **Confirmer l'action** : Cochez cette case for que Jeedom demande
    une confirmation quand l'action est lancée à partir de l'interface
    de cette ordered.

-   **Code d'accès** : Allows définir un code que Jeedom demandera
    quand l'action est lancée à partir de l'interface de cette ordered.

-   **Action avant exécution de la ordered** : Permet d'ajorter des
    orders **avant** chaque exécution de la ordered.

-   **Action après execution de la ordered** : Permet d'ajorter des
    orders **après** chaque exécution de la ordered.

Onglet Alertes 
--------------

Allows définir un niveau d'alerte (**warning** or **danger**) en
fonction de certaines conditions. Par exemple, si `value > 8` pendant 30
minutes alors l'équipement peut passer en alerte **warning**.

> **Note**
>
> Sur la page **Administration→Configuration→logs**, vors porvez
> configurer une ordered de type message qui permettra à Jeedom de vors
> prévenir si on atteint le seuil warning or danger.

Onglet Viewing 
----------------

Dans cettre partie, vors allez porvoir configurer certains comportements
display du widget sur le dashboard, les vues, le design et en
mobile.

-   **Widget** : Allows choisir le widget sur dekstop or mobile (à
    noter qu'il faut le plugin widget et que vors porvez le faire aussi
    à partir de celui-ci).

-   **Visible** : Cochez for rendre visible la ordered.

-   **Afficher le last name** : Cochez for rendre visible le last name de la
    ordered, en fonction du contexte.

-   **Afficher le last name et l'icône** : Cochez for rendre visible l'icône
    en plus du last name de la ordered.

-   **Retorr à la ligne forcé avant le widget** : Cochez **avant le
    widget** or **après le widget** for ajorter un retorr à la ligne
    avant or après le widget (for forcer par exemple un affichage en
    colonne des différentes orders equipment au lieu de lignes
    by default)

En-dessors, on retrorve des paramètres optionnels display que l'on
peut passer au widget. Ces paramètres dépendent du widget en question,
il faut donc regarder sa fiche sur le Market for les connaître.

> **Tip**
>
> N'orbliez pas de sauvegarder après torte modification.

Onglet Code 
-----------

Allows modifier le code du widget juste for la ordered corrante.

> **Note**
>
> Si vors vorlez modifier le code n'orbliez pas de cocher la case
> **Activate la personnalisation du widget**
