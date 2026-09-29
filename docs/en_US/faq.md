# FAQ
**Settings → Version: FAQ**

### Does Jeedom require a subscription?
No, Jeedom is fully functional without the need for any subscription whatsoever. However, there are services available for backups or calls/text messages, but these are truly optional.

### Does Jeedom use external servers to operate?
No, Jeedom does not use a “cloud” type of infrastructure. Everything is handled locally, and you do not need our servers for your system to work. Only services such as the Market, online backup, or Jeedom DNS require the use of our servers.

### Is there a dedicated mobile app?
Jeedom has a mobile version optimized for use on smartphones and tablets. There is also a native app for Android and iOS.

### What are the login credentials for my first login?
When you log in to Jeedom for the first time (and even afterward if you haven’t changed them), the default username and password are admin/admin. Upon your first login, we strongly recommend that you change these credentials for added security.

### I can no longer connect to my Jeedom
Starting with Jeedom 3.2, it is no longer possible to log in remotely using admin/admin for obvious security reasons. The admin/admin credentials now work only locally. Please note that if you access the system via DNS—even locally—you will automatically be identified as a remote user. Another point: by default, only IP addresses in the 192.168.*.* range or 127.0.0.1 are recognized as local. This can be configured in the Jeedom administration panel under “Security” and then “Whitelist IPs.” If you still can’t log in after trying this, you’ll need to use the password reset procedure; see [here](https://doc.jeedom.com/howto/en_US/reset.password).

### I don't see all my devices on the Dashboard
This is often because the devices are assigned to an object that is not a child or the object itself of the first object selected on the left in the tree (you can configure this in your profile).

### Does the Jeedom interface have shortcuts?
Yes, the list of keyboard and mouse shortcuts is [here](shortcuts.md).

### Can I reorder the commands for a device?
Yes, it's possible—just drag and drop the commands for your object onto its configuration.

### Can I customize the style of the widgets?
For each command, you can choose how it is displayed using different Core widgets, or create your own using Tools → Widgets.

### Can the same device be added multiple times to a layout?
No, that's not possible, but you can duplicate this one using the virtual plugin.

### How do I correct an incorrect data entry in the history?
Simply click on the relevant point on the command history graph. If you leave the field blank, the value will be deleted.

### How long does a backup take?
There is no standard duration; it depends on the system and the amount of data to be backed up, but it may take more than 5 minutes—this is normal.

### Where are the Jeedom backups located?
They are in the /var/www/html/backup folder

### Can Jeedom be set up to use HTTPS?
Yes: Either you have a Power package or higher, in which case you
Just use the [Jeedom DNS](https://doc.jeedom.com/howto/en_US/mise_en_place_dns_jeedom). Either you use a DNS and know how to set up a valid certificate—in which case it's a standard certificate installation.

### How do I connect via SSH?
Here's a [documentation](https://www.alsacreations.com/tuto/lire/612-Premiere-connexion-SSH.html), "On Windows: PuTTY" section. The "hostname" is your Jeedom's IP address, and the login credentials are:

- Username: "root", password: "Mjeedom96"
- Username: "jeedom", password: "Mjeedom96"
- Or whatever you installed if you're a DIYer

Please note that when you type the password, nothing will appear on the screen—this is normal.

### How can we overhaul intellectual property rights?
In SSH, run:

``` {.bash}
sudo su -
chmod -R 775 /var/www/html
chown -R www-data:www-data /var/www/html
```

### How do I update Jeedom via SSH?
In SSH, run:

``` {.bash}
sudo su -
php /var/www/html/install/update.php
chmod -R 775 /var/www/html
chown -R www-data:www-data /var/www/html
```

### Is the web app compatible with Symbian?
The web app requires a smartphone that supports HTML5 and CSS3. Unfortunately, it is not compatible with Symbian.

### On which platforms can Jeedom run?
For Jeedom to work, you need a Linux platform with root privileges or a Docker-type system. It therefore does not work on a pure Android platform.

### I can't update a certain plugin. "Failed to download the file. Please try again later (file size less than 100 bytes)..."?
This could be due to several reasons; you need to:

- Check to make sure your Jeedom is still connected to the market (in the Jeedom administration page, under the "Updates" section, there is a test button).
- Verify that the Market account has actually purchased the plugin in question.
- Make sure you have enough space on Jeedom (the health page will show you).
- Make sure your version of Jeedom is compatible with the plugin.

### I have a blank page
You need to connect to Jeedom via SSH and run the self-diagnostic script:
``` {.bash}
sudo chmod +x /var/www/html/health.sh;sudo /var/www/html/health.sh
```
If there's a problem, the script will try to fix it. If it can't, it will let you know.

You can also check the log file at /var/www/html/log/http.error. Very often, this file indicates the problem.

### I'm having a problem with my database username
You need to reset these:

``` {.bash}
bdd_password=$(cat /dev/urandom | tr -cd 'a-f0-9' | head -c 15)
echo "DROP USER 'jeedom'@'localhost'" | mysql -uroot -p
echo "CREATE USER 'jeedom'@'localhost' IDENTIFIED BY '${bdd_password}';" | mysql -uroot -p
echo "GRANT ALL PRIVILEGES ON jeedom.* TO 'jeedom'@'localhost';" | mysql -uroot -p
cd /var/www/html
sudo cp core/config/common.config.sample.php core/config/common.config.php
sudo sed -i -e "s/#PASSWORD#/${bdd_password}/g" core/config/common.config.php
sudo chown www-data:www-data core/config/common.config.php
```

### I have \{\{…​\}\} everywhere
The most common cause is using a beta version of a plugin with a stable version of Jeedom, or vice versa. To get the details of the error, check the http.error log (in /var/www/html/log).

### When I send a command, a wheel spins continuously
Once again, this is often due to a plugin being in beta while Jeedom is in stable. To view the error, press F12 and then go to the console.

### I can no longer access Jeedom, either through the web interface or via the SSH console
This error is not caused by Jeedom, but by a problem with the system.
If the problem persists after a reinstallation, we recommend contacting customer service to check for a hardware issue. Here is the [documentation](https://doc.jeedom.com/installation/en_US/recovery) for the Smart

### My scenario won't stop/doesn't stop
It's a good idea to check the commands executed by the scenario; often, the issue stems from a command that doesn't complete.

### I'm experiencing instability or 504 errors
Check to see if your file system is corrupted; via SSH, the command is: ```sudo dmesg | grep error```.

### I'm getting the following error: SQLSTATE\[HY000\] \[2002\] Can’t connect to local MySQL server through socket '/var/run/mysqld/mysqld.sock'
This is because MySQL has stopped; this is not normal. Common causes include:

- Insufficient disk space on the file system (can be checked by running the "df -h" command via SSH)
- File corruption issue, which often occurs after an improper shutdown of Jeedom (power outage)
- Memory issues: The system is running out of memory and terminates the process that is using the most memory (often the database). You can check this in the OS administration panel by running `dmesg`; you should see a process terminated by "oom." If this is the case, you need to reduce Jeedom's memory usage by disabling plugins.

Unfortunately, there aren’t many solutions if this is the second scenario; the best course of action is to retrieve a backup (located in /var/www/html/backup by default), reinstall Jeedom, and perform the Restoration. You can also check why MySQL won’t start from an SSH console:
``` {.bash}
sudo su -
service mysql stop
mysqld --verbose
```
Or check the log: /var/log/mysql/error.log

### The "Power Off" and "Restart" buttons aren't working
This is normal for a DIY setup. In SSH, run the `visudo` command and add the following at the end of the file: `www-data ALL=(ALL)`
NOPASSWD: ALL.

``` {.bash}
sudo service apache2 restart
```

### I can't see some of the plugins in the Market
This kind of issue occurs if your Jeedom isn't compatible with the plugin. Usually, updating Jeedom resolves the problem.

### I have a device that's timed out, but I don't see it on the Dashboard
Alerts are ranked by priority, from least critical to most critical: timeout, battery warning, battery danger, warning alert, danger alert

### My Jeedom keeps displaying "Starting up" even after an hour?
If you're a DIY enthusiast running Debian 9 or later, check to see if there has been an Apache update that brought back the `privateTmp` directory (visible by running `ls /tmp` and check if there is a "private\*Apache" folder. If so, do the following:
```
mkdir /etc/systemd/system/apache2.service.d
echo "[Service]" > /etc/systemd/system/apache2.service.d/privatetmp.conf
echo "PrivateTmp=no" >> /etc/systemd/system/apache2.service.d/privatetmp.conf
```

### I'm having a problem with the time on my history records
Try clearing Chrome's cache; the history display is based on the browser's time.

### I'm getting the error "Network issue detected, restarting the network."
Jeedom cannot find or ping the gateway. This usually happens if the ADSL router restarts (especially Livebox models) and Jeedom has not restarted or restarted faster than the router. As a safety measure, it will notify you that it has detected an issue and will restart the network connection process. You can disable this mechanism by going to Jeedom’s settings and turning off Jeedom’s network management.

### I'm getting the message "Failure while backing up the database. Check that mysqldump is present."
This means that Jeedom is unable to back up the database, which may indicate a problem with database or filesystem corruption. Unfortunately, there’s no magic command to fix this. The best approach is to run a backup and analyze the backup log. Known issues include:

- A table in the database is corrupted => This is a bad sign. You'll need to see if you can fix it, and if that doesn't work, restore from the last good backup (if you're using an SD card, now is a good time to replace it).
- Not enough space on the filesystem => check the health page; it may indicate this

### I'm getting errors of the type "Class 'eqLogic' not found"; some files seem to be missing, or I'm getting a blank page
That's a pretty serious error; the simplest solution is to
```
mkdir -p /root/tmp/
cd /root/tmp
wget https://github.com/jeedom/core/archive/master.zip
unzip master.zip
cp -R /root/tmp/core-master/* /var/www/html
rm -rf /root/tmp/core-master
```

### I'm getting an error in scenario_execution: MYSQL_ATTR_INIT_COMMAND
In the Jeedom administration interface, under OS/DB, then in the system console, do the following:
```
yes | sudo apt install -y php-mysql php-curl php-gd php-imap php-xml php-opcache php-soap php-xmlrpc php-common php-dev php-zip php-ssh2 php-mbstring php-ldap
```

### I can't install the dependencies for a plugin; I'm getting an error of the type: "E: dpkg was interrupted. You need to run 'sudo dpkg --configure -a' to fix the problem." or "E: Could not get lock /var/lib/dpkg/lock"

You'll need:

- Restart Jeedom
- Go to its settings (click the gear icon in the top right corner, then select "Configuration" in v3 or "Settings" -> "System" -> "Configuration" in v4)
- Go to the OS/DB tab
- Launch System Administration
- Click on "dpkg configure"
- wait 10 minutes
- Restart the dependencies of the plugin that is causing the block

### I'm getting this error when installing a plugin's dependencies: "from pip._internal import main"

In the Jeedom system console or via SSH, you need to do the following:

````
sudo easy_install pip
sudo easy_install3 pip
````

Then restart the dependencies


### Since version 4.2, I can no longer display iframes

Core 4.2 significantly enhances Jeedom's security. If you truly (knowingly) need to revert to an unsecured version of your Jeedom:
Go to **Settings -> System -> Configuration**, then to **OS/DB**, launch the system administration console, and click **Unsecured Apache**. It is recommended that you restart Jeedom after making this change.

### Since version 4.2, some plugins no longer work, and I'm getting 403 errors in the browser console (F12 key).

This is due to Apache security measures that require plugin developers to place the correct files in the correct directories to limit Jeedom’s attack surface. This security configuration is set up in the .htaccess file (which is overwritten with every Core update). You can create a .htaccess_custom file with your own rules; if it exists, it will be used instead of the Core’s .htaccess file.
