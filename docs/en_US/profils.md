The Profile page allows you to configure certain behaviors of
Jeedom specific to the user: homepage, themes of the
desktop version, mobile version, graphics ... It allows
also to change your password.

You find it at the top right by clicking on the icon man
then Profile (followed by your login).

themes
======

The themes panel allows you to adjust interface settings:

-   **Desktop**: themes to use in desktop mode, be careful only the
    default theme is officially supported by Jeedom

-   **Mobile color**: allows to choose the color of the interface
    (here everything is supported)

-   **Desktop Graphic**: Set the default theme for
    graphics in desktop mode

-   **Mobile Chart**: Set the default theme for
    mobile graphics

-   **Opacity with Dashboard widgets**: gives opacity
    (between 0 and 1) widgets on the dashboard

-   **Opacity with Design widgets**: gives opacity
    (between 0 and 1) widgets on the designs

-   **Opacity with View widgets**: allows to give the opacity (between
    0 and 1) widgets on views

-   **Opacity with Mobile widgets**: gives opacity
    (between 0 and 1) mobile widgets

Interface
---------

Allows you to define certain interface behaviors:

-   **General**

    -   **Show menus**: tells Jeedom to display the panel
        left, when it exists, as a reminder this panel is
        available on most pages of plugins, as well as the
        page of scenarios, interactions, objects ....

-   **Default Page**: Default page to display when
    desktop / mobile connection

-   **Default object on the dashboard**: object to display by default
    when arriving on the dashboard / mobile

-   **Default view**: view to display by default when arriving on
    the dashboard / mobile

-   **Default Design**: Design to display by default when
    the arrival on the dashboard / mobile

    -   **Full Screen**: default full screen view when
        the arrival on the designs

-   ** ** Dashboard

    -   **Unfold the Scenarios Panel**: make visible
        default the scenario menu (right) on the dashboard

    -   **Unfold objects panel**: allows to make visible by
        default the objects menu (left) on the dashboard

-   **View**

    -   **Unfold view panel**: make visible by
        default view menu (left) on views

security
--------

-   **2-step authentication**: allows you to configure
    2-step authentication (as a reminder, it's a changing code
    every X seconds that appears on a mobile app, type
    google authentificator). A noter que la double authentification ne sera demandée que pour les connexions externe. Pour les connexion local le code ne sera donc pas demandé.

-   **Password**: allows you to change your password (do not
    forget to retype it below)

-   **User Hash**: Your User API Key

### Active sessions

Here you have the list of your currently connected sessions, their ID,
their IP and the date of last communication. Clicking on
"Disconnect" this will disconnect the user. Be careful if he is on
a registered device will delete the registration.

### Recorded device

Here you will find the list of all devices registered (which is
connects without authentication) to your Jeedom as well as the date of
last use. Here you can delete the registration of a
peripheral. Be careful this does not disconnect it but will just prevent
its automatic reconnection.

Notifications
-------------

-   **User Notification Command**: Default Command for
    reach you (message type command)


