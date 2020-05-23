This is where we will be able to define the list of users
allowed to connect to Jeedom, but also their rights
d'administrateur

Accessible by Administration → Users.

At the top right you have a button to add a user, a
to save and a button to open a support access.

Below you have a table :

-   **Username** : user id

-   **Active** : allows to deactivate the account

-   **Local only** : authorize user login
    only if it is on the local Jeedom network

-   **Profiles** : allows to choose the user profile :

    -   **Administrator** : gets all rights on Jeedom

    -   **User** : can see the dashboard, views,
        design, etc. and act on equipment / controls. On the other hand,
        he will not have access to the configuration of controls / equipment
        nor to the configuration of Jeedom.

    -   **Limited user** : the user only sees the
        authorized equipment (configurable with the &quot;Manage&quot; button
        rights")

-   **API key** : user&#39;s personal API key

-   **Double authentication** : indicates whether double authentication
    is active (OK) or not (NOK)

-   **Date of last connection** : date of the last connection of
    the user at Jeedom. Please note, this is the connection date
    actual, so if you save your computer, the date of
    connection is not updated every time you return to it.

-   **To change the password** : allows to change the password from
    l'utilisateur

-   **Remove** : delete user

-   **Regenerate API key** : regenerates the API key of the user

-   **Manage rights** : allows to finely manage the rights of
    the user (attention the profiles must be in
    "Limited user")

Rights management 
==================

When clicking on &quot;Manage rights&quot; a window appears allowing you
finely manage user rights. The first tab displays
the different equipment. The second presents the scenarios.

> **IMPORTANT**
>
> The profile must be limited otherwise no restrictions placed here
> will be taken into account

You get a table which allows, for each equipment and each
scenario, define user rights :

-   **No** : the user does not see the equipment / scenario

-   **Visualization** : the user sees the equipment / scenario but does not
    can&#39;t act on it

-   **Visualization and execution** : the user sees
    the equipment / scenario and can act on it (light a lamp, throw
    the script, etc.)


