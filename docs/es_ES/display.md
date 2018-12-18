descripción
===========

Esta página reúne en una sola página los diferentes
elementos configurados en su Jeedom. También da acceso a
funciones de organización de los equipos y controles, su
configuración avanzada, así como opciones de configuración
pantalla.

Esta página es accesible desde Herramientas → ** ** Resumen de automatización.

el Top
------------------

En la parte superior, encontramos: \ * ** ** número de objetos: Número
objetos totales configurados en nuestra Jeedom, contando los elementos
Inactivo. \ * ** ** Número de dispositivos: Lo mismo para el equipo. \ *
**** Número de órdenes: Lo mismo para los pedidos. \ *** ** No:
Marque esta casilla si desea que los elementos inactivos están bien
que aparece en esta página. \ * ** ** Buscar: Buscar una
tema en particular. Este puede ser el nombre de un dispositivo, un control
o el nombre del plugin mediante el cual se creó el equipo.

También dispone de un botón "Borrar Historico" que le permitirá visualizar el historial de las órdenes, equipos, objetos, vista, diseño, diseño, diseño 3d, escenario y usuario borrados.

Marcos de objetos 
----------------

Abajo hay un marco por objeto. En cada cuadro, encontramos
la lista del dispositivo (en azul) que es padre de este objeto. El
primer cuadro **Ninguno** representa un equipo que no tiene
padre asociado. Para cada objeto, junto a su etiqueta, tres botones
están disponibles. De izquierda a derecha:

-   El primero se utiliza para abrir la página de configuración del objeto en una
    nueva pestaña,

-   el segundo proporciona información sobre el objeto,

-   el último permite mostrar u ocultar la lista de dispositivos
    que le son atribuidos. 

> **Tip**
>
> El color de fondo de los marcos de los objetos depende del color elegido en
>la configuración del objeto.

> **Tip**
>
>Haciendo clic y soltando sobre el dispositivo, usted puede cambiarlos
>o incluso asignarlos a otro objeto. Es de la orden
>establecida en esta página, como se calcula la visualización del Dashborad.

Los dipositivos 
---------------

En cada dispositivo que encontramos :

-   Una **casilla de verificación** para seleccionar el dispositivo (puede
    seleccionar varios). Si se selecciona al menos un equipo
    tienes botones de acción que aparecen en la parte superior izquierda
    para **eliminar**, hacer **visible**/**invisible**,
    **activo**/**inactivo** el dispositivo seleccionado.

-   El **nombre** del dispositivo

-   Le **type** d’équipement : Identifiant du plugin auquel
    pertenece.

-   **Inactivo** (pequeña cruz) : Significa que el equipo está inactivo.
    (si no aparece, el dispositivo está activo).

-   **Invisible** (ojo cruzado): Significa que el dispositivo es invisible.
    (si no aparece, el dispositivo es visible).

-   **Enlace externo** (cuadrado con una flecha): Le permite abrir en una
    nueva pestaña la página de configuración del dispositivo.

-   **Configuración avanzada** (rueda dentada): permite abrir la
    ventana de configuración avanzada del dispositivo.

-   **Lista de comandos** (flecha): permite desplegar la lista de
    comandos (sobre fondo naranja).

Si despliega la lista de comandos, cada bloque naranja corresponde a
un comando para su dispositivo (un nuevo clic en la flecha pequeña
del dispositivo permite enmascararlas).

Si hace doble clic sobre el comando o en el pequeña
rueda dentada se abrirá la ventana de configuración.

Configuración avanzada de dispositivos
 =====================================

> **Tip**
>
>Es posible acceder (si el plugin lo soporta) directamente a
>esta ventana desde la página de configuración del dispositivo en
>Haga clic en el botón de configuración avanzada

La fenêtre de **configuration avancée d’un équipement** permet de la
modifier. En premier lieu, en haut à droite, quelques boutons
disponibles :

-   **Liens** : Permet d’afficher les liens de l’équipement avec les
    objets, commandes, scénarios, variables, interactions…​ sous forme
    graphique (dans celui-ci, un double clic sur un élement vous amène à
    sa configuration).

-   **Log** : affiche les évènements de l’équipement en question.

-   **Informations** : affiche les propriétés brutes de l’équipement.

-   **Enregistrer** : Enregistre les modifications faites
    sur l’équipement.

-   **Supprimer** : Supprime l’équipement.

Onglet Informations 
-------------------

L’onglet **Informations** contient les informations générales de
l’équipement ainsi que ses commandes :

-   **ID** : Identifiant unique dans la base de données de Jeedom.

-   **Nom** : Nom de l’équipement.

-   **ID logique** : Identifiant logique de l’équipement (peut
    être vide).

-   **ID de l’objet** : Identifiant unique de l’objet parent (peut
    être vide).

-   **Date de création** : Date de création de l’équipement.

-   **Activer** : Cochez la case pour activer l’équipement (sans oublier
    de sauvegarder).

-   **Visible** : Cochez la case pour rendre visible l’équipement (sans
    oublier de sauvegarder).

-   **Type** : Identifiant du plugin par lequel il a été créé.

-   **Tentative échouée** : Nombre de tentatives de communications
    consécutives avec l’équipement qui ont échoué.

-   **Date de dernière communication** : Date de la dernière
    communication de l’équipement.

-   **Dernière mise à jour** : Date de dernière communication
    avec l’équipement.

-   **Tags** : tags de l'équipement, à séparer par des ','. Il permet sur le dashboard de faire des filtre personalisés

En dessous vous retrouvez un tableau avec la liste des commandes de
l’équipement avec, pour chacune, un lien vers leur configuration.

Onglet Affichage 
----------------

Dans l’onglet **Affichage**, vous allez pouvoir configurer certains
comportements d’affichage de la tuile sur le dashboard, les vues, le
design ainsi qu’en mobile.

### Widget 

-   **Visible** : Cochez la case pour rendre visible l’équipement.

-   **Afficher le nom** : Cochez la case pour afficher le nom de
    l’équipement sur la tuile.

-   **Afficher le nom de l’objet** : Cochez la case pour afficher le nom
    de l’objet parent de l’équipement, à côté de la tuile.

-   **Couleur de fond** : Cochez la case pour garder la couleur de fond
    par défaut (suivant la **catégorie** de votre équipement, voir
    **Administration→Configuration→Couleurs**). Si vous décochez cette
    case, vous pourrez choisir une autre couleur. Vous pourrez également
    cocher une nouvelle case **Transparent** pour rendre le
    fond transparent.

-   **Opacité** : Opacité de la couleur de fond de la tuile.

-   **Couleur du texte** : Cochez la case pour garder la couleur du
    texte par défaut.

-   **Bordures** : Cochez la case pour garder la bordure par défaut.
    Sinon, il faut mettre du code CSS, propriété `border` (ex :
    `3px blue dashed` pour une bordure pointillée de 3px en bleu).

-   **Arrondi des bordures** (en px) : Cochez la case pour garder
    l’arrondi par défaut. Sinon, il faut mettre du code CSS, propriété
    `border-radius` (ex : `10px`)

### Paramètres optionnels sur la tuile 

En-dessous, on retrouve des paramètres optionnels d’affichage que l’on
peut appliquer à l’équipement. Ces paramètres sont composés d’un nom et
d’une valeur. Il suffit de cliquer sur **Ajouter** pour en appliquer un
nouveau. Pour les équipements, seule la valeur **style** est pour le
moment utilisée, elle permet d’insérer du code CSS sur l’équipement en
question.

> **Tip**
>
> N’oubliez pas de sauvegarder après toute modification.

Onglet Disposition 
------------------

Cette partie vous permet de choisir entre la disposition standard des
commandes (côte à côte dans le widget), ou en mode tableau. Il n’y a
rien à régler en mode par défaut. Voici les options disponibles en mode
**Tableau** :

-   **Nombre de lignes**

-   **Nombre de colonnes**

-   **Centrer dans les cases** : Cochez la case pour centrer les
    commandes dans les cases.

-   **Style générale des cases (CSS)** : Permet de définir le style
    général en code CSS.

-   **Style du tableau (CSS)** : Permet de définir le style du
    tableau uniquement.

En dessous pour chaque case, la **configuration détaillée** vous permet
ceci :

-   **Texte de la case** : Ajouter un texte en plus de la commande (ou
    tout seul, si il n’y a pas de commande dans la case).

-   **Style de la case (CSS)** : Modifier le style CSS spécifique de la
    case (attention celui-ci écrase et remplace le CSS général
    des cases).

> **Tip**
>
> Dans une case du tableau, si vous voulez mettre 2 commandes l’une en
> dessous de l’autre, il ne faut pas oublier de rajouter un retour à la
> ligne après la première dans la **configuration avancée** de celle-ci.

Onglet Alertes 
--------------

Cet onglet permet d’avoir les informations sur la batterie de
l’équipement et de définir des alertes par rapport à celle-ci. Voici les
types d’informations que l’on peut trouver :

-   **Type de pile**,

-   **Dernière remontée de l’information**,

-   **Niveau restant**, (si bien sûr votre équipement fonctionne
    sur pile).

Dessous, vous pourrez aussi définir les seuils spécifiques d’alerte de
batterie pour cet équipement. Si vous laissez les cases vides, ceux sont
les seuils par défaut qui seront appliqués.

On peut également gérer le timeout, en minutes, de l’équipement. Par
exemple, 30 indique à jeedom que si l’équipement n’a pas communiqué
depuis 30 minutes, alors il faut le mettre en alerte.

> **Tip**
>
> Les paramètres globaux sont dans **Administration→Configuration→Logs**
> (ou **Equipements**)

Onglet Commentaire 
------------------

Permet d’écrire un commentaire à propos de l’équipement (date de
changement de la pile, par exemple).

Configuration avancée d’une commande 
====================================

En premier lieu, en haut à droite, quelques boutons disponibles :

-   **Tester** : Permet de tester la commande.

-   **Liens** : Permet d’afficher les liens de l’équipement avec les
    objets, commandes, scénarios, variables, interactions…​. sous
    forme graphique.

-   **Log** : Affiche les évènements de l’équipement en question.

-   **Informations** : Affiche les propriétés brutes de l’équipement.

-   Appliquer à\* : Permet d’appliquer la même configuration sur
    plusieurs commandes.

-   **Enregistrer** : Enregistre les modifications faites sur
    l’équipement

> **Tip**
>
> Dans un graphique, un double clic sur un élément vous amène à sa
> configuration.

> **Note**
>
> En fonction du type de commande, les informations/actions affichées
> peuvent changer.

Onglet Informations 
-------------------

L’onglet **Informations** contient les informations générales sur la
commande :

-   **ID** : Identifiant unique dans la base de données.

-   **ID logique** : Identifiant logique de la commande (peut
    être vide).

-   **Nom** : Nom de la commande.

-   **Type** : Type de la commande (action ou info).

-   **Sous-type** : Sous-type de la commande (binaire, numérique…​).

-   **URL directe** : Fournit l’URL pour accéder à cet équipement. (clic
    droit, copier l’adresse du lien) L’URL lancera la commande pour une
    **action**et retournera l’information pour une**info**.

-   **Unité** : Unité de la commande.

-   **Commande déclenchant une mise à jour** : Donne l’identifiant d’une
    autre commande qui, si cette autre commande change, va forcer la
    mise à jour de la commande visualisée.

-   **Visible** : Cochez cette case pour que la commande soit visible.

-   **Suivre dans la timeline** : Cochez cette case pour que cette
    commande soit visible dans la timeline quand elle est utilisée.

-   **Interdire dans les interactions automatique** : interdit les
    interactions automatique sur cette commande

-   **Icône** : Permet de changer l’icône de la commande.

Vous avez aussi trois autres boutons oranges en dessous :

-   **Cette commande remplace l’ID** : Permet de remplacer un ID de
    commande par la commande en question. Utile si vous avez supprimé un
    équipement dans Jeedom et que vous avez des scénarios qui utilisent
    des commandes de celui-ci.

-   **Cette commande remplace la commande** : Remplace une commande par
    la commande courante.

-   **Remplacer cette commande par la commande** : L’inverse, remplace
    la commande par une autre commande.

> **Note**
>
> Ce genre d’action remplace les commandes partout dans Jeedom
> (scénario, interaction, commande, équipement…​.)

En-dessous, vous retrouvez la liste des différents équipements,
commandes, scénarios ou interactions qui utilisent cette commande. Un
clic dessus permet d’aller directement sur leur configuration
respective.

Onglet Configuration 
--------------------

### Pour une commande de type info : 

-   **Calcul et arrondi**

    -   **Formule de calcul (\#value\# pour la valeur)** : Permet de
        faire une opération sur la valeur de la commande avant le
        traitement par Jeedom, exemple : `#value# - 0.2` pour retrancher
        0.2 (offset sur un capteur de température).

    -   **Arrondi (chiffre après la virgule)** : Permet d’arrondir la
        valeur de la commande (Exemple : mettre 2 pour tranformer
        16.643345 en 16.64).

-   **Type générique** : Permet de configurer le type générique de la
    commande (Jeedom essaie de le trouver par lui-même en mode auto).
    Cette information est utilisée par l’application mobile.

-   **Action sur la valeur, si** : Permet de faire des sortes de
    mini scénarios. Vous pouvez, par exemple, dire que si la valeur vaut
    plus de 50 pendant 3 minutes, alors il faut faire telle action. Cela
    permet, par exemple, d’éteindre une lumière X minutes après que
    celle-ci se soit allumée.

-   **Historique**

    -   **Historiser** : Cochez la case pour que les valeurs de cette
        commande soient historisées. (Voir **Analyse→Historique**)

    -   **Mode de lissage**: Mode de**lissage** ou d'**archivage**
        permet de choisir la manière d’archiver la donnée. Par défaut,
        c’est une **moyenne**. Il est aussi possible de choisir le
        **maximum**, le **minimum**, ou **aucun**. **aucun** permet de
        dire à Jeedom qu’il ne doit pas réaliser d’archivage sur cette
        commande (aussi bien sur la première période des 5 mn qu’avec la
        tâche d’archivage). Cette option est dangereuse car Jeedom
        conserve tout : il va donc y avoir beaucoup plus de
        données conservées.

    -   **Purger l’historique si plus vieux de** : Permet de dire à
        Jeedom de supprimer toutes les données plus vieilles qu’une
        certaine période. Peut être pratique pour ne pas conserver de
        données si ça n’est pas nécessaire et donc limiter la quantité
        d’informations enregistrées par Jeedom.

-   **Gestion des valeurs**

    -   **Valeur interdite** : Si la commande prend une de ces valeurs,
        Jeedom l’ignore avant de l’appliquer.

    -   **Valeur retour d’état** : Permet de faire revenir la commande à
        cette valeur après un certain temps.

    -   **Durée avant retour d’état (min)** : Temps avant le retour à la
        valeur ci-dessus.

-   **Autres**

    -   **Gestion de la répétition des valeurs** : En automatique si la
        commande remonte 2 fois la même valeur d’affilée, alors Jeedom
        ne prendra pas en compte la 2eme remontée (évite de déclencher
        plusieurs fois un scénario, sauf si la commande est de
        type binaire). Vous pouvez forcer la répétition de la valeur ou
        l’interdire complètement.

    -   **Push URL** : Permet de rajouter une URL à appeler en cas de
        mise à jour de la commande. Vous pouvez utiliser les tags
        suivant : `#value#` pour la valeur de la commande, `#cmd_name#`
        pour le nom de la commande, `#cmd_id#` pour l’identifiant unique
        de la commande, `#humanname#` pour le nom complet de la commande
        (ex : `#[Salle de bain][Hydrometrie][Humidité]#`), `#eq_name#` pour le nom de l'équipement

### Pour une commande action : 

-   **Type générique** : Permet de configurer le type générique de la
    commande (Jeedom essaie de le trouver par lui-même en mode auto).
    Cette information est utilisée par l’application mobile.

-   **Confirmer l’action** : Cochez cette case pour que Jeedom demande
    une confirmation quand l’action est lancée à partir de l’interface
    de cette commande.

-   **Code d’accès** : Permet de définir un code que Jeedom demandera
    quand l’action est lancée à partir de l’interface de cette commande.

-   **Action avant exécution de la commande** : Permet d’ajouter des
    commandes **avant** chaque exécution de la commande.

-   **Action après execution de la commande** : Permet d’ajouter des
    commandes **après** chaque exécution de la commande.

Onglet Alertes 
--------------

Permet de définir un niveau d’alerte (**warning**ou**danger**) en
fonction de certaines conditions. Par exemple, si `value > 8` pendant 30
minutes alors l’équipement peut passer en alerte **warning**.

> **Note**
>
> Sur la page **Administration→Configuration→Logs**, vous pouvez
> configurer une commande de type message qui permettra à Jeedom de vous
> prévenir si on atteint le seuil warning ou danger.

Onglet Affichage 
----------------

Dans cettre partie, vous allez pouvoir configurer certains comportements
d’affichage du widget sur le dashboard, les vues, le design et en
mobile.

-   **Widget** : Permet de choisir le widget sur dekstop ou mobile (à
    noter qu’il faut le plugin widget et que vous pouvez le faire aussi
    à partir de celui-ci).

-   **Visible** : Cochez pour rendre visible la commande.

-   **Afficher le nom** : Cochez pour rendre visible le nom de la
    commande, en fonction du contexte.

-   **Afficher le nom et l’icône** : Cochez pour rendre visible l’icône
    en plus du nom de la commande.

-   **Retour à la ligne forcé avant le widget**: Cochez**avant le
    widget**ou**après le widget** pour ajouter un retour à la ligne
    avant ou après le widget (pour forcer par exemple un affichage en
    colonne des différentes commandes de l’équipement au lieu de lignes
    par défaut)

En-dessous, on retrouve des paramètres optionnels d’affichage que l’on
peut passer au widget. Ces paramètres dépendent du widget en question,
il faut donc regarder sa fiche sur le Market pour les connaître.

> **Tip**
>
> N’oubliez pas de sauvegarder après toute modification.

Onglet Code 
-----------

Permet de modifier le code du widget juste pour la commande courante.

> **Note**
>
> Si vous voulez modifier le code n’oubliez pas de cocher la case
> **Activer la personnalisation du widget**
