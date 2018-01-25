I have a blank page
=====================

You have to connect in SSH to Jeedom and start the script
self-diagnosis:

`` `{.bash}
sudo chmod + x /var/www/html/health.sh;sudo /var/www/html/health.sh
`` `

If there is a problem, the script will try to fix it. If he does not
no, he will tell you.

You can also look at the log /var/www/html/http.error. Very
often, this one indicates the worry.

I have a BDD ID problem
==================================

You have to reset these:

`` `{.bash}
bdd_password = $ (cat / dev / urandom | tr -cd 'a-f0-9' | head -c 15)
echo "DROP USER 'jeedom' @ 'localhost'" | mysql -uroot -p
echo "CREATE USER" jeedom '@' localhost 'IDENTIFIED BY' $ {bdd_password} '; " | mysql -uroot -p
echo "GRANT ALL PRIVILEGES ON jeedom. * TO 'jeedom' @ 'localhost';" | mysql -uroot -p
cd / usr / share / nginx / www / jeedom
sudo core / config / common.config.sample.php core / config / common.config.php
sudo sed -i -e "s / # PASSWORD # / $ {bdd_password} / g" core / config / common.config.php
sudo chown www -data: www-data core / config / common.config.php
`` `

I have {{...}} everywhere
=======================

The most common cause is the use of a beta plugin
and Jeedom in stable, or vice versa. To have the detail of the error, he
look at the log http.error (in / var / www / html / log).

At the time of an order I have a wheel which turns without stopping
================================================== =========

Again this is often due to a beta plugin while Jeedom
is in stable. To see the error, you have to do F12 then console.

I no longer have access to Jeedom, neither through the web interface nor in console by SSH
================================================== =======================

This error is not due to Jeedom, but to a problem with the system.
If this persists following a reinstallation, it is advisable to
see with the SAV for a hardware problem.

My scenario does not stop / no
=================================

It is advisable to look at the commands executed by the scenario,
often it comes from a command that does not end.

I have instabilities or errors 504
========================================

Check if your filesystem is not corrupted, in SSH the
command is: "sudo dmesg | grep error".

I do not see all my equipment on the dashboard
================================================== ==

Often this is because the equipment is assigned to an object
which is not the son or the object itself of the first object selected to
left in the tree (you can configure it in your profile).

I have the following error: SQLSTATE \ [HY000 \] \ [2002 \] Can not connect to local MySQL server through socket '/var/run/mysqld/mysqld.sock'
================================================== ================================================== ================================

This is due to MySQL that stopped, it's not normal, the cases
currents are:

-   Lack of space on the file system (can be checked in
    doing the command "df -h", in SSH)

-   Problem of file corruption (s), which often happens following
    a non-clean stop of Jeedom (power failure)

Unfortunately, there is not a lot of solution if it's the second
case, the best is to recover a backup (available in
/ usr / share / nginx / www / jeedom / backup by default), to reinstall Jeedom and
to restore the backup. You can also look at why MySQL does not
do not want to boot from an SSH console:

`` `{.bash}
sudo su -
mysql stop service
mysqld --verbose
`` `

Or check the log: /var/log/mysql/error.log

Shutdown / Restart buttons do not work
================================================== =

On a DIY installation it's normal. In SSH, you have to order
visudo and at the end of the file you have to add: www-data ALL = (ALL)
NOPASSWD: ALL.

`` `{.bash}
sudo service apache2 restart
`` `

I do not see some plugins in the Market
=========================================

This kind of case happens if your Jeedom is not compatible with the
plugin. In general, an update of jeedom corrects the problem.
