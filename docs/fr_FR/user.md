# Utilisateurs
**Réglages → Système → Utilisateurs**

Cette page permet de définir la liste des utilisateurs autorisés à se connecter à Jeedom, ainsi que leurs droits administrateur.

Sur la page vous avez trois boutons :

- Ajouter un utilisateur.
- Sauvegarder.
- Ouvrir un accès au support.

## Liste des utilisateurs

- **Nom d’utilisateur** : Identifiant de l’utilisateur.
- **Actif** : Permet de désactiver le compte sans le supprimer.
- **Local** : Autorise la connexion de l’utilisateur uniquement s’il est sur le réseau local de Jeedom.
- **Profil** : Permet de choisir le profil de l’utilisateur :
    - **Administrateur** : L’utilisateur obtient tous les droits (édition / consultation) sur Jeedom.
    - **Utilisateur** : L’utilisateur peut voir le Dashboard, les vues, les designs, etc. et agir sur les équipements/commandes. En revanche, il n’aura pas accès à la configuration des commandes/équipements ni à la configuration de Jeedom.
    - **Utilisateur limité** : L’utilisateur ne voit que les équipements autorisés (configurable avec le bouton "Droits").
- **Clef API** : Clef API personnelle de l’utilisateur.
- **Double authentification** : Indique si la double authentification est active (OK) ou non (NOK).
- **Date de dernière connexion** : Date de la dernière connexion de l’utilisateur. Attention, ici c’est la date de connexion réelle, ainsi si vous enregistrez votre ordinateur, la date de connexion n’est pas mise à jour à chaque fois que vous y retournez.
- **Droits** : Permet de modifier les droits de l'utilisateur.
- **Mot de passe** : Permet de changer le mot de passe de l’utilisateur.
- **Supprimer** : Permet de supprimer l’utilisateur.
- **Régénérer clef API** : Régénère la clef API de l’utilisateur.
- **Gérer les droits** : Permet de gérer finement les droits de l’utilisateur (attention le profil doit être en "utilisateur limité").

## Gestion des droits

Lors du clic sur "Droits" une fenêtre apparaît et vous permet de gérer finement les droits de l’utilisateur. Le premier onglet affiche les différents équipements. Le deuxième présente les scénarios.

> **Important**
>
> Le profil doit être en limité sinon aucune restriction mise ici ne sera prise en compte.

Vous obtenez un tableau qui permet, pour chaque équipement et chaque scénario, de définir les droits de l’utilisateur :
- **Aucun** : l’utilisateur ne voit pas l’équipement/scénario.
- **Visualisation** : l’utilisateur voit l’équipement/scénario mais ne peut pas agir dessus.
- **Visualisation et exécution** : l’utilisateur voit l’équipement/scénario et peut agir dessus (allumer une lampe, lancer le scénario, etc).

## Session(s) active(s)

Affiche les sessions de navigateur active sur votre Jeedom, avec les informations utilisateur, son IP et depuis quand. Vous pouvez déconnecter l'utilisateur à l'aide du bouton **Déconnecter**.

## Périphérique(s) enregistré(s)

Liste les périphériques (ordinateurs, mobiles etc) qui on enregistré leur authentification sur votre Jeedom.
Vous pouvez voir quel utilisateur, son IP, à quelle date, et supprimer l'enregistrement de ce périphérique.

> **Note**
>
> Un même utilisateur peut avoir enregistré différents périphériques. Par exemple, son ordinateur de bureau, son ordinateur portable, son mobile, etc.







