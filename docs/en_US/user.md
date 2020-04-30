# Users
**Settings → System → Users**

This page allows you to define the list of users authorized to connect to Jeedom, as well as their administrator rights..

On the page you have three buttons :

- Add user.
- Save.
- Open access to support.

## Users list

- **Username** : User ID.
- **Actif** : Allows you to deactivate the account without deleting it.
- **Local** : Allows the connection of the user only if he is on the local network of Jeedom.
- **Profil** : Allows to choose the user profile :
    - **Administrateur** : The user obtains all rights (editing / consultation) on Jeedom.
    - **Utilisateur** : User can see Dashboard, views, designs, etc.. and act on equipment / controls. However, he will not have access to the configuration of the controls / equipment nor to the configuration of Jeedom.
    - **Limited user** : The user only sees the authorized equipment (configurable with the &quot;Rights&quot; button).
- **API key** : User&#39;s personal API key.
- **Double authentication** : Indicates whether double authentication is active (OK) or not (NOK).
- **Date of last connection** : Date of last user login. Please note, this is the actual connection date, so if you save your computer, the connection date is not updated each time you return.
- **Droits** : Modify user rights.
- **Password** : Allows you to change the user&#39;s password.
- **Supprimer** : Delete user.
- **Regenerate API key** : Regenerates the user&#39;s API key.
- **Manage rights** : Allows you to manage user rights finely (note that the profile must be &quot;limited user&quot;).

## Rights management

When clicking on &quot;Rights&quot; a window appears allowing you to manage user rights finely. The first tab displays the different equipment. The second presents the scenarios.

> **Important**
>
> The profile must be limited otherwise no restrictions put here will be taken into account.

You get a table which allows, for each device and each scenario, to define the rights of the user. :
- **Aucun** : the user does not see the equipment / scenario.
- **Visualisation** : the user sees the equipment / scenario but cannot act on it.
- **Visualization and execution** : the user sees the equipment / scenario and can act on it (light a lamp, launch the scenario, etc.).

## Active session (s)

Displays the browser sessions active on your Jeedom, with user information, its IP and since when. You can log out the user using the button **Log-out**.

## Registered device(s)

List the peripherals (computers, mobiles etc) which have recorded their authentication on your Jeedom.
You can see which user, their IP, when, and delete the registration for this device.

> **Note**
>
> The same user may have registered different devices. For example, his desktop computer, laptop, mobile, etc..







