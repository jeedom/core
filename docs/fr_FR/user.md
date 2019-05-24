C’est ici que l’on va pouvoir définir la liste des utilisateurs
autorisés à se connecter à Jeedom, mais également leurs droits
d’administrateur

Accessible par Réglages → Système → Utilisateurs.

En haut à droite vous avez un bouton pour ajouter un utilisateur, un
pour sauvegarder et un bouton pour ouvrir un accès au support.

En dessous vous avez un tableau :

-   **Nom d’utilisateur** : l’identifiant de l’utilisateur

-   **Actif** : permet de désactiver le compte

-   **Local seulement** : autorise la connexion de l’utilisateur
    uniquement s’il est sur le réseau local de Jeedom

-   **Profils** : permet de choisir le profil de l’utilisateur :

    -   **Administrateur** : obtient tous les droits sur Jeedom

    -   **Utilisateur** : peut voir le dashboard, les vues, les
        design, etc. et agir sur les équipements/commandes. En revanche,
        il n’aura pas accès à la configuration des commandes/équipements
        ni à la configuration de Jeedom.

    -   **Utilisateur limité** : l’utilisateur ne voit que les
        équipements autorisés (configurable avec le bouton "Gérer
        les droits")

-   **Clef API** : clef API personnelle de l’utilisateur

-   **Double authentification** : indique si la double authentification
    est active (OK) ou non (NOK)

-   **Date de dernière connexion** : date de la dernière connexion de
    l’utilisateur à Jeedom. Attention, ici c’est la date de connexion
    réelle, ainsi si vous enregistrez votre ordinateur, la date de
    connexion n’est pas mise à jour à chaque fois que vous y retournez.

-   **Changer le mot de passe** : permet de changer le mot de passe de
    l’utilisateur

-   **Supprimer** : permet de supprimer l’utilisateur

-   **Régénérer clef API** : régénère la clef API de l’utilisateur

-   **Gérer les droits** : permet de gérer finement les droits de
    l’utilisateur (attention le profil doit être en
    "utilisateur limité")

Gestion des droits 
==================

Lors du clic sur "Gérer les droits" une fenêtre apparait et vous permet
de gérer finement les droits de l’utilisateur. Le premier onglet affiche
les différents équipements. Le deuxième présente les scénarios.

> **Important**
>
> Le profil doit être en limité sinon aucune restriction mise ici ne
> sera prise en compte

Vous obtenez un tableau qui permet, pour chaque équipement et chaque
scénario, de définir les droits de l’utilisateur :

-   **Aucun** : l’utilisateur ne voit pas l’équipement/scénario

-   **Visualisation** : l’utilisateur voit l’équipement/scénario mais ne
    peut pas agir dessus

-   **Visualisation et exécution** : l’utilisateur voit
    l’équipement/scénario et peut agir dessus (allumer une lampe, lancer
    le scénario, etc.)


