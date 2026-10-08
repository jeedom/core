# Users
**Settings → System → Users**

This page allows you to define the list of users authorized to log in to Jeedom, as well as their administrator privileges.

On this page, you'll see three buttons:

- Add a user.
- Save.
- Submit a support request.

## List of Users

- **Username**: The user's login ID.
- **Active**: Allows you to deactivate the account without deleting it.
- **Local**: Allows the user to connect only if they are on Jeedom's local network.
- **Profile**: Allows you to select the user's profile:
    - **Administrator**: The user is granted full access (edit/view) to Jeedom.
    - **User**: The user can view the Dashboard, views, designs, etc., and control devices and commands. However, they will not have access to the configuration of commands or devices, nor to Jeedom’s configuration.
    - **Limited User**: The user can only see authorized devices (configurable using the "Permissions" button).
- **API Key**: The user's personal API key.
- **Two-factor authentication**: Indicates whether two-factor authentication is active (OK) or disabled (NOK).
- **Last Login Date**: The date of the user's last login. Please note that this is the actual login date; therefore, if you save your computer, the login date is not updated every time you return to it.
- **Permissions**: Allows you to modify user permissions.
- **Password**: Allows you to change the user's password.
- **Delete**: Deletes the user.
- **Regenerate API Key**: Regenerates the user's API key.
- **Manage Permissions**: Allows you to finely control user permissions (note: the profile must be set to "limited user").

## Access Control

When you click "Permissions," a window appears that allows you to fine-tune user permissions. The first tab displays the various devices. The second tab shows the scenarios.

> **Important**
>
> The profile must be set to "limited"; otherwise, any restrictions specified here will be ignored.

You’ll see a table that allows you to define user permissions for each device and each scenario:
- **None**: The user cannot see the device or scenario.
- **Visualization**: The user can see the device or scenario but cannot control it.
- **Visualization and Execution**: The user sees the device or scenario and can interact with it (turn on a light, start the scenario, etc.).

## Active session(s)

Displays the active browser sessions on your Jeedom, along with user information, the user's IP address, and the time the session began. You can log the user out using the **Log Out** button.

## Registered device(s)

Lists the devices (computers, mobile devices, etc.) that have registered their authentication on your Jeedom.
You can see which user, their IP address, and the date, and delete the record for that device.

> **Note**
>
> A single user may have registered multiple devices. For example, their desktop computer, laptop, cell phone, etc.







