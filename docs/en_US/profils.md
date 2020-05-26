# Preferences
**Settings → Preferences**

The Preferences page allows you to configure certain user-specific Jeedom behaviors.

## Preferences tab

### Interface

Defines certain interface behaviors

- **Objects panel on the Dashboard** : Displays the objects panel (on the left) on the Dashboard, without having to click on the dedicated button.
- **Default page** : Page to display by default to display when connecting to desktop or mobile.
- **Default object** : Object to display by default upon arrival on the Dashboard / mobile.

- **Default view** : View to display by default upon arrival on the Dashboard / mobile.
- **Unfold the view panel** : Used to make the view menu (left) visible on the views by default.

- **Default design** : Design to display by default upon arrival on the Dashboard / mobile.
- **Full Screen Design** : Default display in full screen upon arrival on designs.

- **Default 3D design** : 3D design to display by default when arriving on the Dashboard / mobile.
- **Full screen 3D design** : Default display in full screen upon arrival on 3D designs.

### Notifications

- **User notification command** : Default command to join you (message type command).

## Security tab

- **2-step authentication** : allows to configure authentication in 2 steps (as a reminder, it is a code changing every X seconds which is displayed on a mobile application, type *google authentificator*). Note that double authentication will only be requested for external connections. For local connections, the code will therefore not be requested.

  **Important** if during the configuration of the double authentication you have an error, you must check that Jeedom (see on the health page) and your phone are at the same time (1 min difference is enough for it not to work).

- **Password** : Allows you to change your password (do not forget to retype it below).

- **User hash** : Your user API key.

-   **View the menus** : tell Jeedom to display the panel
    left, when it exists, as a reminder this panel is
    available on most plugin pages, as well as
    page of scenarios, interactions, objects….

-   **Default page** : default page to display when
    desktop / mobile connection

-   **Default object on the dashboard** : default display object
    upon arrival on the dashboard / mobile

-   **Default view** : view to display by default when arriving on
    the dashboard / mobile

-   **Default design** : design to display by default when
    the arrival on the dashboard / mobile

    -   **Full screen** : default display in full screen when
        the arrival on the designs

-   **Dashboard**

    -   **Unfold the scenario panel** : allows to make visible
        by default the scenario menu (on the right) on the dashboard

    -   **Open the objects pane** : allows to make visible by
        default the objects menu (on the left) on the dashboard

-   **View**

    -   **Unfold the view panel** : allows to make visible by
        default views menu (left) on views

Security
--------

-   **2-step authentication** : allows to configure
    2-step authentication (as a reminder, this is a changing code
    displayed every X seconds on a mobile application, type
    google authentificator or Microsoft Authenticator). Note that double authentication will only be requested for external connections. For local connection the code will therefore not be requested. Important if during the configuration of double authentication you have an error check that jeedom (see on the health page) and your phone are at the same time (1 min difference is enough for it to not work)

-   **Password** : allows you to change your password (do not
    forget to retype it below)

-   **User hash** : your user API key


### Active sessions

Here you have the list of your currently connected sessions, their ID, their IP as well as the date of last communication. By clicking on &quot;Disconnect&quot; this will disconnect the user. Attention if it is on a registered device this will also delete the recording.

### Registered device

Here you find the list of all registered devices (which connect without authentication) to your Jeedom as well as the date of last use.
Here you can delete the registration of a device. Attention it does not disconnect it but will just prevent its automatic reconnection.
