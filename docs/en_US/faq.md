Does Jeedom require a subscription?
=====================================

No, Jeedom is fully usable without any need of
subscription whatever. However, there are services offered for
backups or calls / SMS but that actually stay
optional.

Does Jeedom use external servers to work?
================================================== ============

No, Jeedom does not use cloud infrastructure. Everything is done in
local and you do not need our servers for your
installation works. Only services like the Market, the
online backup or the Jeedom DNS require the use of our
servers.

Can we reorder orders for equipment?
==================================================

Yes it is possible, just drag and drop the commands of your
object on its configuration.

Can we edit the style of the widgets?
=====================================

Yes it is possible, either through the widget plugin, or in
using the General → Display page

Can we put the same equipment several times on a design?
================================================== ==============

No it is not possible, but you can duplicate it thanks to
virtual plugin.

How to change an erroneous data in the history?
================================================== ==

It suffices, on a historical curve of the order, to click on the
point in question. If you leave the field blank, then the value
will be deleted.

How long does a backup take?
======================================

There is no standard duration, it depends on the system and the volume of
data to back up, but it can take more than 5 minutes, that's
normal.

Is there a dedicated mobile app?
========================================

Jeedom has a mobile version suitable for use on mobile and
Tablet. There is also a native app for Android and iOS.

What are the credentials to login the first time?
================================================== ==============

When you first connect to Jeedom (and even if you do not
have not changed), the default username and password
are admin / admin. At the first connection, it is strongly
recommended to modify these identifiers for more security.

Can we put Jeedom in https?
================================

Yes: \ * Either you have a power pack or more, in which case you
Just use the Jeedom DNS. \ * Either have a DNS and you know
set up a valid certificate, in this case it is an installation
standard of a certificate.

How to flatten rights?
====================================

In SSH do:

`` `{.bash}
sudo su -
chmod -R 775 / var / www / html
chown -R www-data: www-data / var / www / html
`` `

Where are the Jeedom backups?
==========================================

They are in the folder / var / www / html / backup

How to update Jeedom in SSH?
=====================================

In SSH do:

`` `{.bash}
sudo su -
php /var/www/html/install/update.php
chmod -R 775 / var / www / html
chown -R www-data: www-data / var / www / html
`` `

Is Webapp compatible with Symbian?
=======================================

The webapp requires a smartphone that supports HTML5 and CSS3. She
is therefore not compatible with Symbian.

On which platforms can Jeedom work?
================================================== ==

For Jeedom to work, you need a linux platform with rights
root or docker type system. So it does not work on a
pure android platform.
