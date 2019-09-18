This is where we will be able to define the list of users
allowed to connect to Jeedom, but also their rights
director

Accessible par Réglages → Système → Utilisateurs.

En haut à droite vous avez un bouton pour ajouter un utilisateur, un
pour sauvegarder et un bouton pour ouvrir un accès au support.

Below you have a table:

-   **Username**: User ID

-   **Active**: disable the account

-   **Local only**: allows user login
    only if it is on Jeedom's local network

-   **Profiles**: allows to choose the profile of the user:

    -   **Administrator**: gets all rights to Jeedom

    -   **User**: can see the dashboard, the views, the
        design, etc. and act on the equipment / orders. On the other hand,
        it will not have access to the configuration of commands / equipment
        nor to Jeedom's configuration.

    -   **Limited user**: the user only sees the
        authorized equipment (configurable with the "Manage
        rights")

-   **API Key**: User's Own API Key

-   **Double Authentication**: indicates whether dual authentication
    is active (OK) or not (NOK)

-   **Date of last connection**: date of the last connection of
    the user to Jeedom. Attention, here is the date of connection
    real, so if you save your computer, the date of
    login is not updated each time you return.

-   **Change password**: Change the password of
    the user

-   **Delete**: delete the user

-   **Régénérer clef API** : régénère la clef API de l’utilisateur

-   **Manage rights**: allows to manage finely the rights of
    l’utilisateur (attention le profil doit être en
    "limited user")

Rights management
==================

When clicking on "Manage rights" a window appears and allows you
to finely manage the rights of the user. The first tab displays
the different equipment. The second presents the scenarios.

> **Important**
>
> The profile must be limited or no restrictions placed here
> will be taken into account

You get a table that allows for each equipment and each
scenario, define the rights of the user:

-   **None**: the user does not see the equipment / scenario

-   **Visualization**: the user sees the equipment / scenario but does not
    can not act on it

-   **Visualization and execution**: the user sees
    the equipment / scenario and can act on it (lighting a lamp, throwing
    the scenario, etc.)


