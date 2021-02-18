# Résumé Domotique
**Analyse → Résumé domotique**

Cette page permet de rassembler sur une seule page les différents éléments configurés sur son Jeedom. Elle donne également l’accès à des fonctions d’organisation des équipements et des commandes, à leur configuration avancée ainsi qu’à des possibilités de configuration d’affichage.

## Informations

Sur le haut de la page, on retrouve :
- **Nombre d’objets** : Nombre total d’objets configurés dans notre Jeedom, en comptant les éléments inactifs.
- **Nombre d’équipements** : Idem pour les équipements.
- **Nombre de commandes** : Idem pour les commandes.
- **Inactif** : Cochez cette case si vous voulez que les éléments inactifs soient bien affichés sur cette page.
- **Rechercher** : Permet de rechercher un élément particulier. Ce peut être le nom d’un équipement, d’une commande ou le nom du plugin par lequel a été créé l’équipement.
- **Export CSV** : Permet d'exporter tous les objets, équipements et leurs commandes dans un fichier CSV.

Vous avez aussi un onglet **Historique**, affichant l'historique des commandes, équipements, objets, vues, design, design 3d, scénarios et utilisateurs supprimés.

## Les cadres objet

En dessous on retrouve un cadre par objet. Dans chaque cadre, on trouve la liste des équipements  qui ont pour parent cet objet.
Le premier cadre **Aucun** représente les équipements qui n’ont pas de parent affecté.

Pour chaque objet, à côté de son libellé, deux boutons sont disponibles.
- Le premier sert à ouvrir la page de configuration de l’objet dans un nouvel onglet.
- Le deuxième apporte quelques informations sur l’objet,

> **Tip**
>
> La couleur de fond des cadres objets dépend de la couleur choisie dans la configuration de l’objet.

> **Tip**
>
> Avec un cliquer-déposer sur les objets ou équipements, vous pouvez changer leur ordre ou même les affecter à un autre objet. C’est à partir de l’ordre établi dans cette page que l’affichage du Dashboard est calculé.

## Les équipements

Sur chaque équipement on retrouve :

- Une **case à cocher** pour sélectionner l’équipement (vous pouvez en sélectionner plusieurs). Si au moins un équipement est sélectionné, vous avez des boutons d’action qui apparaissent en haut à gauche  pour **supprimer**, rendre **visible**/**invisible**,  **actif**/**inactif** les équipements sélectionnés.
- L'**id** de l'équipement.
- Le **type** d’équipement : Identifiant du plugin auquel il appartient.
- Le **nom** de l’équipement.
- **Inactif** (petite croix) : Signifie que l’équipement est inactif (si elle n’y est pas, l’équipement est actif).
- **Invisible** (œil barré) : Signifie que l’équipement est invisible (s’il n’y est pas, l’équipement est visible).

Si le plugin de l'équipement est désactivé, les deux icône à droite n'apparaissent pas:
- **Lien externe** (carré avec une flèche) : Permet d’ouvrir dans un nouvel onglet la page de configuration de l’équipement.
- **Configuration avancée** (roue crantée) : permet d’ouvrir la fenêtre de configuration avancée de l’équipement.

> En cliquant sur la ligne contenant le nom de l'équipement, vous afficherez toutes les commandes de cet équipement. En cliquant alors sur une commande, vous accéderez à la fenêtre de configuration de la commande.

## Configuration avancée d’un équipement

> **Tip**
>
> Il est possible d’accéder (si le plugin le supporte) directement à cette fenêtre à partir de la page de configuration de l’équipement en cliquant sur le bouton configuration avancée

La fenêtre de **configuration avancée d’un équipement** permet de la modifier. En premier lieu, en haut à droite, quelques boutons disponibles :

- **Informations** : affiche les propriétés brutes de l’équipement.
- **Liens** : Permet d’afficher les liens de l’équipement avec les objets, commandes, scénarios, variables, interactions…​ sous forme graphique (dans celui-ci, un double clic sur un élément vous amène à sa configuration).
- **Log** : affiche les évènements de l’équipement en question.
- **Sauvegarder** : Sauvegarde les modifications faites sur l’équipement.
- **Supprimer** : Supprime l’équipement.

### Onglet Informations

L’onglet **Informations** contient les informations générales de l’équipement ainsi que ses commandes :

- **ID** : Identifiant unique dans la base de données de Jeedom.
- **Nom** : Nom de l’équipement.
- **ID logique** : Identifiant logique de l’équipement (peut être vide).
- **ID de l’objet** : Identifiant unique de l’objet parent (peut être vide).
- **Date de création** : Date de création de l’équipement.
- **Activer** : Cochez la case pour activer l’équipement (sans oublier de sauvegarder).
- **Visible** : Cochez la case pour rendre visible l’équipement (sans oublier de sauvegarder).
- **Type** : Identifiant du plugin par lequel il a été créé.
- **Tentative échouée** : Nombre de tentatives de communications consécutives avec l’équipement qui ont échoué.
- **Date de dernière communication** : Date de la dernière communication de l’équipement.
- **Dernière mise à jour** : Date de dernière communication avec l’équipement.
- **Tags** : tags de l'équipement, à séparer par des ','. Il permet sur le Dashboard de faire des filtres personnalisés

En dessous vous retrouvez un tableau avec la liste des commandes de l’équipement avec, pour chacune, un lien vers leur configuration.

### Onglet Affichage

Dans l’onglet **Affichage**, vous allez pouvoir configurer certains comportements d’affichage de la tuile sur le Dashboard ou en mobile.

#### Widget

-  **Visible** : Cochez la case pour rendre visible l’équipement.
- **Afficher le nom** : Cochez la case pour afficher le nom de l’équipement sur la tuile.
- **Afficher le nom de l’objet** : Cochez la case pour afficher le nom de l’objet parent de l’équipement, à côté de la tuile.

### Paramètres optionnels sur la tuile

En-dessous, on retrouve des paramètres optionnels d’affichage que l’on peut appliquer à l’équipement. Ces paramètres sont composés d’un nom et d’une valeur. Il suffit de cliquer sur **Ajouter** pour en appliquer un
nouveau. Pour les équipements, seule la valeur **style** est pour le moment utilisée, elle permet d’insérer du code CSS sur l’équipement en question.

> **Tip**
>
> N’oubliez pas de sauvegarder après toute modification.

### Onglet Disposition

Cette partie vous permet de choisir entre la disposition standard des commandes (côte à côte dans le widget), ou en mode tableau. Il n’y a rien à régler en mode par défaut. Voici les options disponibles en mode
**Tableau** :
- **Nombre de lignes**
- **Nombre de colonnes**
- **Centrer dans les cases** : Cochez la case pour centrer les commandes dans les cases.
- **Style générale des cases (CSS)** : Permet de définir le style général en code CSS.
- **Style du tableau (CSS)** : Permet de définir le style du tableau uniquement.

En dessous pour chaque case, la **configuration détaillée** vous permet
ceci :
- **Texte de la case** : Ajouter un texte en plus de la commande (ou tout seul, si il n’y a pas de commande dans la case).
- **Style de la case (CSS)** : Modifier le style CSS spécifique de la case (attention celui-ci écrase et remplace le CSS général des cases).

> **Tip**
>
> Dans une case du tableau, si vous voulez mettre 2 commandes l’une en dessous de l’autre, il ne faut pas oublier de rajouter un retour à la ligne après la première dans la **configuration avancée** de celle-ci.

### Onglet Alertes

Cet onglet permet d’avoir les informations sur la batterie de l’équipement et de définir des alertes par rapport à celle-ci. Voici les types d’informations que l’on peut trouver :

- **Type de pile**,
- **Dernière remontée de l’information**,
- **Niveau restant**, (si bien sûr votre équipement fonctionne sur pile).

Dessous, vous pourrez aussi définir les seuils spécifiques d’alerte de batterie pour cet équipement. Si vous laissez les cases vides, ceux sont les seuils par défaut qui seront appliqués.

On peut également gérer le timeout, en minutes, de l’équipement. Par exemple, 30 indique à jeedom que si l’équipement n’a pas communiqué depuis 30 minutes, alors il faut le mettre en alerte.

> **Tip**
>
> Les paramètres globaux sont dans **Réglages→Système→Configuration : Logs** ou **Equipements**

### Onglet Commentaire

Permet d’écrire un commentaire à propos de l’équipement.

## Configuration avancée d’une commande

En premier lieu, en haut à droite, quelques boutons disponibles :

- **Tester** : Permet de tester la commande.
- **Liens** : Permet d’afficher les liens de l’équipement avec les objets, commandes, scénarios, variables, interactions…​. sous forme graphique.
- **Log** : Affiche les évènements de l’équipement en question.
- **Informations** : Affiche les propriétés brutes de l’équipement.
-  **Appliquer à** : Permet d’appliquer la même configuration sur plusieurs commandes.
- **Sauvegarder** : Sauvegarde les modifications faites sur l’équipement.

> **Tip**
>
> Dans un graphique, un double clic sur un élément vous amène à sa configuration.

> **Note**
>
> En fonction du type de commande, les informations/actions affichées peuvent changer.

### Onglet Informations

L’onglet **Informations** contient les informations générales sur la commande :

- **ID** : Identifiant unique dans la base de données.
- **ID logique** : Identifiant logique de la commande (peut être vide).
- **Nom** : Nom de la commande.
- **Type** : Type de la commande (action ou info).
- **Sous-type** : Sous-type de la commande (binaire, numérique…​).
- **URL directe** : Fournit l’URL pour accéder à cet équipement. (clic droit, copier l’adresse du lien) L’URL lancera la commande pour une **action** et retournera l’information pour une **info**.
- **Unité** : Unité de la commande.
- **Commande déclenchant une mise à jour** : Donne l’identifiant d’une  autre commande qui, si cette autre commande change, va forcer la mise à jour de la commande visualisée.
- **Visible** : Cochez cette case pour que la commande soit visible.
- **Suivre dans la timeline** : Cochez cette case pour que cette commande soit visible dans la timeline quand elle est utilisée. Vous pouvez préciser une timeline en particulier dans le champ qui s'affiche si l'option est cochée.
- **Interdire dans les interactions automatique** : interdit les interactions automatique sur cette commande
- **Icône** : Permet de changer l’icône de la commande.

Vous avez aussi trois autres boutons oranges en dessous :

- **Cette commande remplace l’ID** : Permet de remplacer un ID de commande par la commande en question. Utile si vous avez supprimé un équipement dans Jeedom et que vous avez des scénarios qui utilisent des commandes de celui-ci.
- **Cette commande remplace la commande** : Remplace une commande par la commande courante.
- **Remplacer cette commande par la commande** : L’inverse, remplace la commande par une autre commande.

> **Note**
>
> Ce genre d’action remplace les commandes partout dans Jeedom (scénario, interaction, commande, équipement…​.).

En-dessous, vous retrouvez la liste des différents équipements, commandes, scénarios ou interactions qui utilisent cette commande. Un clic dessus permet d’aller directement sur leur configuration respective.

### Onglet Configuration

#### Pour une commande de type info :

- **Calcul et arrondi**
    - **Formule de calcul (\#value\# pour la valeur)** : Permet de faire une opération sur la valeur de la commande avant le traitement par Jeedom, exemple : `#value# - 0.2` pour retrancher 0.2 (offset sur un capteur de température).
    - **Arrondi (chiffre après la virgule)** : Permet d’arrondir la valeur de la commande (Exemple : mettre 2 pour transformer 16.643345 en 16.64).
- **Type générique** : Permet de configurer le type générique de la commande (Jeedom essaie de le trouver par lui-même en mode auto). Cette information est utilisée par l’application mobile.
- **Action sur la valeur, si** : Permet de faire des sortes de mini scénarios. Vous pouvez, par exemple, dire que si la valeur vaut plus de 50 pendant 3 minutes, alors il faut faire telle action. Cela permet, par exemple, d’éteindre une lumière X minutes après que celle-ci se soit allumée.

- **Historique**
    - **Historiser** : Cochez la case pour que les valeurs de cette  commande soient historisées. (Voir **Analyse→Historique**)
    - **Mode de lissage** : Mode de **lissage** ou d'**archivage** permet de choisir la manière d’archiver la donnée. Par défaut, c’est une **moyenne**. Il est aussi possible de choisir le **maximum**, le **minimum**, ou **aucun**. **aucun** permet de dire à Jeedom qu’il ne doit pas réaliser d’archivage sur cette  commande (aussi bien sur la première période des 5 mins qu’avec la tâche d’archivage). Cette option est dangereuse car Jeedom        conserve tout : il va donc y avoir beaucoup plus de données conservées.
    - **Purger l’historique si plus vieux de** : Permet de dire à Jeedom de supprimer toutes les données plus vieilles qu’une certaine période. Peut être pratique pour ne pas conserver de données si ça n’est pas nécessaire et donc limiter la quantité d’informations enregistrées par Jeedom.

- **Gestion des valeurs**
    - **Valeur interdite** : Si la commande prend une de ces valeurs,  Jeedom l’ignore avant de l’appliquer.
    - **Valeur retour d’état** : Permet de faire revenir la commande à cette valeur après un certain temps.
    - **Durée avant retour d’état (min)** : Temps avant le retour à la valeur ci-dessus.

- **Autres**
    - **Gestion de la répétition des valeurs** : En automatique si la commande remonte 2 fois la même valeur d’affilée, alors Jeedom ne prendra pas en compte la 2eme remontée (évite de déclencher plusieurs fois un scénario, sauf si la commande est de type binaire). Vous pouvez forcer la répétition de la valeur ou l’interdire complètement.
    - **Push URL** : Permet de rajouter une URL à appeler en cas de mise à jour de la commande. Vous pouvez utiliser les tags suivant : `#value#` pour la valeur de la commande, `#cmd_name#` pour le nom de la commande, `#cmd_id#` pour l’identifiant unique de la commande, `#humanname#` pour le nom complet de la commande       (ex : `#[Salle de bain][Hydrometrie][Humidité]#`), `#eq_name#` pour le nom de l'équipement.

#### Pour une commande action :

-  **Type générique** : Permet de configurer le type générique de la commande (Jeedom essaie de le trouver par lui-même en mode auto). Cette information est utilisée par l’application mobile.
- **Confirmer l’action** : Cochez cette case pour que Jeedom demande une confirmation quand l’action est lancée à partir de l’interface de cette commande.
- **Code d’accès** : Permet de définir un code que Jeedom demandera quand l’action est lancée à partir de l’interface de cette commande.
- **Action avant exécution de la commande** : Permet d’ajouter des commandes **avant** chaque exécution de la commande.
- **Action après exécution de la commande** : Permet d’ajouter des commandes **après** chaque exécution de la commande.

### Onglet Alertes

Permet de définir un niveau d’alerte (**warning** ou **danger**) en fonction de certaines conditions. Par exemple, si `value > 8` pendant 30 minutes alors l’équipement peut passer en alerte **warning**.

> **Note**
>
> Sur la page **Réglages→Système→Configuration : Logs**, vous pouvez configurer une commande de type message qui permettra à Jeedom de vous prévenir si on atteint le seuil warning ou danger.

### Onglet Affichage

Dans cette partie, vous allez pouvoir configurer certains comportements d’affichage du widget sur le Dashboard, les vues, le design et en mobile.

- **Widget** : Permet de choisir le widget sur desktop ou mobile (à noter qu’il faut le plugin widget et que vous pouvez le faire aussi à partir de celui-ci).
- **Visible** : Cochez pour rendre visible la commande.
- **Afficher le nom** : Cochez pour rendre visible le nom de la commande, en fonction du contexte.
- **Afficher le nom et l’icône** : Cochez pour rendre visible l’icône en plus du nom de la commande.
- **Retour à la ligne forcé avant le widget** : Cochez **avant le  widget** ou **après le widget** pour ajouter un retour à la ligne avant ou après le widget (pour forcer par exemple un affichage en colonne des différentes commandes de l’équipement au lieu de lignes par défaut)

En-dessous, on retrouve des paramètres optionnels d’affichage que l’on peut passer au widget. Ces paramètres dépendent du widget en question, il faut donc regarder sa fiche sur le Market pour les connaître.

> **Tip**
>
> N’oubliez pas de sauvegarder après toute modification.
