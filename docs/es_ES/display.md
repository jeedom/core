Description 
===========

Cette page permite rassembler sur une seule page les différents
éléments configurés sur son Jeedom. Elle donne également l'accès à des
fonctions d'organisation des équipements et des comandos, à leur
configuration avancée ainsi qu'à des possibilités de configuration
d'affichage.

Cette page est accessible par **Analyse → Résumé domotique**.

Le haut de la page 
------------------

Sur le haut de la page, on retrouve : \* **número d'objets** : número
total d'objets configurés dans notre Jeedom, en comptant les éléments
inactifs. \* **número d'équipements** : Idem pour les équipements. \*
**número de comandos** : Lo mismo para pedidos. \* **Inactif** :
Cochez cette case si vous voulez que les éléments inactifs soient bien
affichés sur cette page. \* **Buscar** : Permet de rechercher un
élément particulier. Ce peut être le nom d'un équipement, d'une orden
ou le nom du plugin par lequel a été créé l'équipement.

Vous avez aussi un bouton **histórico des suppressions** : Affiche l'historique
des comandos, équipements, objets, vues, design, deisgn 3d, scénarios et utilisateurs supprimés.

Les cadres objet 
----------------

En dessous on retrouve un cadre par objet. Dans chaque cadre, on trouve
la liste des équipements (en bleu) qui ont pour parent cet objet. Le
premier cadre **Aucun** représente les équipements qui n'ont pas de
parent affecté. Pour chaque objet, à côté de son libellé, trois boutons
sont disponibles. De gauche à droite :

-   Le premier sert à ouvrir la page de configuration de l'objet dans un
    nouvel onglet,

-   le deuxième apporte quelques informations sur l'objet,

-   le dernier permet d'afficher ou de masquer la liste des équipements
    qui lui sont attribués.

> **punta**
>
> La couleur de fond des cadres objets dépend de la couleur choisie dans
> la configuration de l'objet.

> **punta**
>
> En cliquant/déposant sur les équipements, vous pouvez changer leur
> ordre ou même les affecter à un autre objet. C'est à partir de l'ordre
> établi dans cette page que l'affichage du dashboard est calculé.

Les équipements 
---------------

Sur chaque équipement on retrouve :

-   Une **case à cocher** pour sélectionner l'équipement (vous pouvez en
    sélectionner plusieurs). Si au moins un équipement est sélectionné
    vous avez des boutons d'acción qui apparaissent en haut à gauche
    pour **supprimer**, rendre **visible**/**invisible**,
    **actif**/**inactif** les équipements sélectionnés.

-   Le **nom** de l'équipement.

-   Le **type** d'équipement : Identifiant du plugin auquel
    il appartient.

-   **Inactif** (petite croix) : Signifie que l'équipement est inactif
    (si elle n'y est pas, l'équipement est actif).

-   **Invisible** (oeil barré) : Signifie que l'équipement est invisible
    (s'il n'y est pas, l'équipement est visible).

-   **Lien externe** (carré avec une flèche) : Permet d'ouvrir dans un
    nouvel onglet la page de configuration de l'équipement.

-   **Configuration avancée** (roue crantée) : permet d'ouvrir la
    fenêtre de configuration avancée de l'équipement.

-   **Liste des comandos** (la flèche) : permite déplier la liste des
    comandos (sur fond orange).

Si vous dépliez la liste des comandos, chaque bloc orange correspond à
une orden de votre équipement (un nouveau clic sur la petite flèche
de l'équipement permite les masquer).

Si vous double-cliquez sur la orden ou que vous cliquez sur la petite
roue crantée cela fera apparaitre sa fenêtre de configuration.

Configuration avancée d'un équipement 
=====================================

> **punta**
>
> Il est possible d'accéder (si le plugin le supporte) directement à
> cette fenêtre à partir de la page de configuration de l'équipement en
> cliquant sur le bouton configuration avancée

La fenêtre de **configuration avancée d'un équipement** permite la
modifier. En premier lieu, en haut à droite, quelques boutons
disponibles :

-   **Vínculos** : Permet d'afficher les liens de l'équipement avec les
    objets, comandos, scénarios, variables, interaccións…​ sous forme
    graphique (dans celui-ci, un double clic sur un élement vous amène à
    sa configuration).

-   **Log** : affiche les évènements de l'équipement en question.

-   **Informations** : affiche les propriétés brutes de l'équipement.

-   **Enregistrer** : Enregistre les modifications faites
    sur l'équipement.

-   **remove** : Supprime l'équipement.

Onglet Informations 
-------------------

L'onglet **Informations** contient les informations générales de
l'équipement ainsi que ses comandos :

-   **ID** : Identifiant unique dans la base de données de Jeedom.

-   **apellido** : apellido de l'équipement.

-   **ID logique** : Identifiant logique de l'équipement (peut
    être vide).

-   **ID de l'objet** : Identifiant unique de l'objet parent (peut
    être vide).

-   **Date de création** : Date de création de l'équipement.

-   **Activer** : Cochez la case pour activer l'équipement (sans oublier
    de sauvegarder).

-   **Visible** : Cochez la case pour rendre visible l'équipement (sans
    oublier de sauvegarder).

-   **Type** : Identifiant du plugin par lequel il a été créé.

-   **Tentative échouée** : número de tentatives de communications
    consécutives avec l'équipement qui ont échoué.

-   **Date de dernière communication** : Date de la dernière
    communication de l'équipement.

-   **Dernière mise à jour** : Date de dernière communication
    avec l'équipement.

-   **Tags** : tags de l'équipement, à séparer par des ','. Il permet sur le dashboard de faire des filtre personalisés

En dessous vous retrouvez un tableau avec la liste des comandos de
l'équipement avec, pour chacune, un lien vers leur configuration.

Onglet Viendo 
----------------

Dans l'onglet **Viendo**, vous allez pouvoir configurer certains
comportements d'affichage de la tuile sur le dashboard, les vues, le
design ainsi qu'en mobile.

### Widget 

-   **Visible** : Cochez la case pour rendre visible l'équipement.

-   **Afficher le nom** : Cochez la case pour afficher le nom de
    l'équipement sur la tuile.

-   **Afficher le nom de l'objet** : Cochez la case pour afficher le nom
    de l'objet parent de l'équipement, à côté de la tuile.

-   **Color de fondo** : Cochez la case pour garder la couleur de fond
    par défaut (suivant la **catégorie** de votre équipement, voir
    **Administration→Configuration→Couleurs**). Si vous décochez cette
    case, vous pourrez choisir une autre couleur. Vous pourrez également
    cocher une nouvelle case **Transparent** pour rendre le
    fond transparent.

-   **Opacité** : Opacité de la couleur de fond de la tuile.

-   **Couleur du texte** : Cochez la case pour garder la couleur du
    texte par défaut.

-   **Bordures** : Cochez la case pour garder la bordure par défaut.
    Sinon, il faut mettre du code CSS, propriété `border` (por ejemplo, :
    `3px blue dashed` pour une bordure pointillée de 3px en bleu).

-   **Arrondi des bordures** (en px) : Cochez la case pour garder
    l'arrondi par défaut. Sinon, il faut mettre du code CSS, propriété
    `border-radius` (por ejemplo, : `10px`)

### Paramètres optionnels sur la tuile 

En-dessous, on retrouve des paramètres optionnels d'affichage que l'on
peut appliquer à l'équipement. Ces paramètres sont composés d'un nom et
d'une valeur. Il suffit de cliquer sur **Ajouter** pour en appliquer un
nouveau. Pour les équipements, seule la valeur **style** est pour le
moment utilisée, elle permet d'insérer du code CSS sur l'équipement en
question.

> **punta**
>
> N'oubliez pas de sauvegarder après toute modification.

Onglet Disposition 
------------------

Cette partie vous permite choisir entre la disposition standard des
comandos (côte à côte dans le widget), ou en mode tableau. Il n'y a
rien à régler en mode par défaut. Voici les options disponibles en mode
**Tableau** :

-   **número de lignes**

-   **número de colonnes**

-   **Centrer dans les cases** : Cochez la case pour centrer les
    comandos dans les cases.

-   **Style générale des cases (CSS)** : Permet de définir le style
    général en code CSS.

-   **Style du tableau (CSS)** : Permet de définir le style du
    tableau uniquement.

En dessous pour chaque case, la **configuration détaillée** vous permet
ceci :

-   **Texte de la case** : Ajouter un texte en plus de la orden (ou
    tout seul, si il n'y a pas de orden dans la case).

-   **Style de la case (CSS)** : Modifier le style CSS spécifique de la
    case (attention celui-ci écrase et remplace le CSS général
    des cases).

> **punta**
>
> Dans une case du tableau, si vous voulez mettre 2 comandos l'une en
> dessous de l'autre, il ne faut pas oublier de rajouter un retour à la
> ligne après la première dans la **configuration avancée** de celle-ci.

Onglet Alertes 
--------------

Cet onglet permet d'avoir les informations sur la batterie de
l'équipement et de définir des alertes par rapport à celle-ci. Voici les
types d'informations que l'on peut trouver :

-   **Type de pile**,

-   **Dernière remontée de l'information**,

-   **Niveau restant**, (si bien sûr votre équipement fonctionne
    sur pile).

Dessous, vous pourrez aussi définir les seuils spécifiques d'alerte de
batterie pour cet équipement. Si vous laissez les cases vides, ceux sont
les seuils par défaut qui seront appliqués.

On peut également gérer le timeout, en minutes, de l'équipement. Par
exemple, 30 indique à jeedom que si l'équipement n'a pas communiqué
depuis 30 minutes, alors il faut le mettre en alerte.

> **punta**
>
> Les paramètres globaux sont dans **Administration→Configuration→Logs**
> (ou **comodidades**)

Onglet Commentaire 
------------------

Permet d'écrire un commentaire à propos de l'équipement (date de
changement de la pile, par exemple).

Configuration avancée d'une orden 
====================================

En premier lieu, en haut à droite, quelques boutons disponibles :

-   **Tester** : Permet de tester la orden.

-   **Vínculos** : Permet d'afficher les liens de l'équipement avec les
    objets, comandos, scénarios, variables, interaccións…​. sous
    forme graphique.

-   **Log** : Affiche les évènements de l'équipement en question.

-   **Informations** : Affiche les propriétés brutes de l'équipement.

-   Appliquer à\* : Permet d'appliquer la même configuration sur
    plusieurs comandos.

-   **Enregistrer** : Enregistre les modifications faites sur
    l'équipement

> **punta**
>
> Dans un graphique, un double clic sur un élément vous amène à sa
> configuration.

> **nota**
>
> En fonction du type de orden, les informations/accións affichées
> peuvent changer.

Onglet Informations 
-------------------

L'onglet **Informations** contient les informations générales sur la
orden :

-   **ID** : Identifiant unique dans la base de données.

-   **ID logique** : Identifiant logique de la orden (peut
    être vide).

-   **apellido** : apellido de la orden.

-   **Type** : Type de la orden (acción ou info).

-   **Sous-type** : Sous-type de la orden (binaire, numérique…​).

-   **URL directe** : Fournit l'URL pour accéder à cet équipement. (clic
    droit, copier l'adresse du lien) L'URL lancera la orden pour une
    **acción** et retournera l'information pour une **info**.

-   **unidad** : unidad de la orden.

-   **Commande déclenchant une mise à jour** : Donne l'identifiant d'une
    autre orden qui, si cette autre orden change, va forcer la
    mise à jour de la orden visualisée.

-   **Visible** : Cochez cette case pour que la orden soit visible.

-   **Suivre dans la timeline** : Cochez cette case pour que cette
    orden soit visible dans la timeline quand elle est utilisée.

-   **Interdire dans les interaccións automatique** : interdit les
    interaccións automatique sur cette orden

-   **icono** : Permet de changer l'icône de la orden.

Vous avez aussi trois autres boutons oranges en dessous :

-   **Cette orden remplace l'ID** : Permet de remplacer un ID de
    orden par la orden en question. Utile si vous avez supprimé un
    équipement dans Jeedom et que vous avez des scénarios qui utilisent
    des comandos de celui-ci.

-   **Cette orden remplace la orden** : Remplace une orden par
    la orden courante.

-   **Remplacer cette orden par la orden** : L'inverse, remplace
    la orden par une autre orden.

> **nota**
>
> Ce genre d'acción remplace les comandos partout dans Jeedom
> (scénario, interacción, orden, équipement…​.)

En-dessous, vous retrouvez la liste des différents équipements,
comandos, scénarios ou interaccións qui utilisent cette orden. Un
clic dessus permet d'aller directement sur leur configuration
respective.

Onglet Configuration 
--------------------

### Pour une orden de type info : 

-   **Calcul et arrondi**

    -   **Formule de calcul (\#value\# pour la valeur)** : Permet de
        faire une opération sur la valeur de la orden avant le
        traitement par Jeedom, exemple : `#value# - 0.2` pour retrancher
        0.2 (offset sur un capteur de température).

    -   **Arrondi (chiffre après la virgule)** : Permet d'arrondir la
        valeur de la orden (Exemple : mettre 2 pour tranformer
        16.643345 en 16.64).

-   **Type générique** : Permet de configurer le type générique de la
    orden (Jeedom essaie de le trouver par lui-même en mode auto).
    Cette information est utilisée par l'application mobile.

-   **Action sur la valeur, si** : Permet de faire des sortes de
    mini scénarios. Vous pouvez, par exemple, dire que si la valeur vaut
    plus de 50 pendant 3 minutes, alors il faut faire telle acción. Cela
    permet, par exemple, d'éteindre une lumière X minutes après que
    celle-ci se soit allumée.

-   **histórico**

    -   **Historiser** : Cochez la case pour que les valeurs de cette
        orden soient historisées. (Voir **Analyse→histórico**)

    -   **Mode de lissage** : Mode de **lissage** ou d'**archivage**
        permite choisir la manière d'archiver la donnée. Par défaut,
        c'est une **moyenne**. Il est aussi possible de choisir le
        **maximum**, le **minimum**, ou **aucun**. **aucun** permite
        dire à Jeedom qu'il ne doit pas réaliser d'archivage sur cette
        orden (aussi bien sur la première période des 5 mn qu'avec la
        tâche d'archivage). Cette option est peligroeuse car Jeedom
        conserve tout : il va donc y avoir beaucoup plus de
        données conservées.

    -   **Purger l'historique si plus vieux de** : Permet de dire à
        Jeedom de supprimer toutes les données plus vieilles qu'une
        certaine période. Peut être pratique pour ne pas conserver de
        données si ça n'est pas nécessaire et donc limiter la quantité
        d'informations enregistrées par Jeedom.

-   **Gestion des valeurs**

    -   **Valeur interdite** : Si la orden prend une de ces valeurs,
        Jeedom l'ignore avant de l'appliquer.

    -   **Valeur retour d'état** : Permet de faire revenir la orden à
        cette valeur après un certain temps.

    -   **Durée avant retour d'état (min)** : Temps avant le retour à la
        valeur ci-dessus.

-   **Autres**

    -   **Gestion de la répétition des valeurs** : En automatique si la
        orden remonte 2 fois la même valeur d'affilée, alors Jeedom
        ne prendra pas en compte la 2eme remontée (évite de déclencher
        plusieurs fois un scénario, sauf si la orden est de
        type binaire). Vous pouvez forcer la répétition de la valeur ou
        l'interdire complètement.

    -   **Push URL** : Permet de rajouter une URL à appeler en cas de
        mise à jour de la orden. Vous pouvez utiliser les tags
        suivant : `#value#` pour la valeur de la orden, `#cmd_name#`
        pour le nom de la orden, `#cmd_id#` pour l'identifiant unique
        de la orden, `#humanname#` pour le nom complet de la orden
        (por ejemplo, : `#[Salle de bain][Hydrometrie][Humidité]#`), `#eq_name#` pour le nom de l'équipement

### Pour une orden acción : 

-   **Type générique** : Permet de configurer le type générique de la
    orden (Jeedom essaie de le trouver par lui-même en mode auto).
    Cette information est utilisée par l'application mobile.

-   **Confirmer l'acción** : Cochez cette case pour que Jeedom demande
    une confirmation quand l'acción est lancée à partir de l'interface
    de cette orden.

-   **Code d'accès** : Permet de définir un code que Jeedom demandera
    quand l'acción est lancée à partir de l'interface de cette orden.

-   **Action avant exécution de la orden** : Permet d'ajouter des
    comandos **avant** chaque exécution de la orden.

-   **Action après execution de la orden** : Permet d'ajouter des
    comandos **après** chaque exécution de la orden.

Onglet Alertes 
--------------

Permet de définir un niveau d'alerte (**advertencia** ou **peligro**) en
fonction de certaines conditions. Par exemple, si `value > 8` pendant 30
minutes alors l'équipement peut passer en alerte **advertencia**.

> **nota**
>
> Sur la page **Administration→Configuration→Logs**, vous pouvez
> configurer une orden de type message qui permettra à Jeedom de vous
> prévenir si on atteint le seuil advertencia ou peligro.

Onglet Viendo 
----------------

Dans cettre partie, vous allez pouvoir configurer certains comportements
d'affichage du widget sur le dashboard, les vues, le design et en
mobile.

-   **Widget** : Permet de choisir le widget sur dekstop ou mobile (à
    noter qu'il faut le plugin widget et que vous pouvez le faire aussi
    à partir de celui-ci).

-   **Visible** : Cochez pour rendre visible la orden.

-   **Afficher le nom** : Cochez pour rendre visible le nom de la
    orden, en fonction du contexte.

-   **Afficher le nom et l'icône** : Cochez pour rendre visible l'icône
    en plus du nom de la orden.

-   **Retour à la ligne forcé avant le widget** : Cochez **avant le
    widget** ou **après le widget** pour ajouter un retour à la ligne
    avant ou après le widget (pour forcer par exemple un affichage en
    colonne des différentes comandos de l'équipement au lieu de lignes
    por defecto)

En-dessous, on retrouve des paramètres optionnels d'affichage que l'on
peut passer au widget. Ces paramètres dépendent du widget en question,
il faut donc regarder sa fiche sur le Market pour les connaître.

> **punta**
>
> N'oubliez pas de sauvegarder après toute modification.

Onglet Code 
-----------

Permet de modifier le code du widget juste pour la orden courante.

> **nota**
>
> Si vous voulez modifier le code n'oubliez pas de cocher la case
> **Activer la personnalisation du widget**
