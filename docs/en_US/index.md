# Unifi Protect plugin

# Description

Plugin to connect Jeedom to Unifi Protect

>**IMPORTANT**
>
>There is no official API for Unifi protect the plugin can therefore stop working overnight following an update of Unifi protect. Under no circumstances can Jeedom be held responsible and / or have the duty to correct

# Plugin configuration

After installing the plugin, you just need to activate it. Some parameters must be entered in the plugin configuration :

-   **Unifi protect controller** : You have to put the path to your Unifi controller (just the IP in most cases)
-   **Unifi protect user** : Indicate here a local user name (admin) 
-   **Unifi protect password** : Enter the user's password here
-   **Refresh rate** : Frequency of information requests to the controller (the lower it is, the more resources it will consume on it, beware of those on UDM-Pro)
-   **Do not collect events** : Do not recover the events from the cameras (allows to consume less resources but you lose the detection of movement / person / car /...)
-   **Find Unifi protect equipment** : Starts synchronization with Unifi Protect

>**IMPORTANT**
>
>If you have the camera plugin installed the Unifi Protect plugin will automatically create the cameras in the camera plugin 

# Information feedback

## Controleur

- Etat
- Uptime
- Last seen
- SSH Active (SSH connection possible on the controller)
- Code error
- CPU usage
- CPU temperature (If possible)
- Memory usage
- Using tmpfs
- Disk usage

## Camera 

- Connected
- Etat
- Last seen
- Recording (is the camera recording)
- Last event
- Last event date
- Last event score (if the event is a smart event)