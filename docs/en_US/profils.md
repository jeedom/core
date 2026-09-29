# Preferences
**Settings → Preferences**

The Preferences page allows you to configure certain user-specific Jeedom behaviors.

## Preferences tab

### Interface

Defines certain behaviors of the interface

- **Default Page**: The page to display by default when logging in on a desktop or mobile device.
- **Default object**: The object to display by default when opening the Dashboard or mobile app.

- **Default View**: The view that appears by default when you open the Dashboard or mobile app.
- **Expand the Views panel**: Makes the Views menu (on the left) visible by default.

- **Default Design**: The design to display by default when users arrive at the Dashboard or on mobile.
- **Full-Screen Design**: Designs are displayed in full-screen mode by default when you land on them.

- **Default 3D Design**: The 3D design to display by default when opening the Dashboard or mobile app.
- **Full-Screen 3D Design**: Default full-screen display when viewing 3D designs.

### Notifications

- **User Notification Command**: Default command to contact you (message-type command).

## Security tab

- **Two-Step Authentication**: Allows you to set up two-step authentication (as a reminder, this is a code that changes every X seconds and is displayed on a mobile app, type *Google Authenticator*). Please note that two-step authentication will only be required for external logins. For local logins, the code will not be required.

**Important**: If you encounter an error while setting up two-factor authentication, make sure that Jeedom (check the "Health" page) and your phone are set to the same time (even a 1-minute difference is enough to prevent it from working).

- **Password**: Allows you to change your password (be sure to re-enter it below).

- **User hash**: Your user API key.

### Active sessions

Here is a list of your currently logged-in sessions, including their IDs, IP addresses, and the date of the last communication. Clicking "Log Out" will log the user out. Please note that if the user is logged in on a registered device, this will also delete the registration.

### Registered devices

Here you'll find a list of all devices registered (that connect without authentication) to your Jeedom, along with the date they were last used.
Here, you can remove a device from the system. Please note that this does not disconnect the device; it simply prevents it from reconnecting automatically.
