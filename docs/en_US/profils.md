# Preferences
**Settings → Preferences**

The Preferences page allows you to configure certain user-specific Jeedom behaviors.

## Preferences tab

### Interface

Defines certain interface behaviors

- **Default page** : Page to display by default when connecting to desktop or mobile.
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

  **Important** if during the configuration of the double authentication you have an error, it is necessary to check that Jeedom (see on the health page) and your phone are well at the same time (1 min of difference is enough for it not to work).

- **Password** : Allows you to change your password (do not forget to retype it below).

- **User hash** : Your user API key.

### Active sessions

Here you have the list of your currently connected sessions, their ID, their IP as well as the date of last communication. By clicking on &quot;Disconnect&quot; this will disconnect the user. Be careful if it is on a registered device, this will also delete the registration.

### Registered devices

Here you find the list of all registered devices (which connect without authentication) to your Jeedom as well as the date of last use.
Here you can delete the registration of a device. Attention it does not disconnect it but will just prevent its automatic reconnection.
