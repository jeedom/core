# FAQ
**Settings → Version : Faq**

### Jeedom does it require a subscription ?
No, Jeedom is fully usable without any need for any subscription. However, there are services offered for backups or calls / SMS, but which are really optional.

### Does Jeedom use outside servers to run ?
No, Jeedom does not use "Cloud" type infrastructure". Everything is done locally and you do not need our servers for your installation to work. Only services like Market, online backup or Jeedom DNS require the use of our servers.

### Is there a dedicated mobile app ?
Jeedom has a mobile version suitable for use on mobile and tablet. There is also a native app for Android and iOS.

### What are the credentials to log in the first time ?
When you log in to Jeedom for the first time (and even afterwards if you haven&#39;t changed them), the default username and password are admin / admin. At the first connection, you are strongly recommended to modify these identifiers for more security.

### I can no longer connect to my Jeedom
Since Jeedom 3.2 it is no longer possible to connect with admin / admin remotely for obvious security reasons. Admin / admin credentials only work locally. Attention if you go through the DNS even locally you are necessarily identified as remote. Other default point only IP on 192.168.*.* or 127.0.0.1 are recognized as local. It is configured in the administration of Jeedom security part then IP "white". If, despite everything, you still cannot connect, you must use the password reset procedure, see [here](https://doc.jeedom.com/en_US/howto/reset.password).

### I do not see all my equipment on the Dashboard
Often this is due to the fact that the equipment is assigned to an object which is not the son or the object itself of the first object selected on the left in the tree (you can configure this one in your profile).

### Jeedom interface has shortcuts ?
Yes, the list of keyboard / mouse shortcuts is [here](shortcuts.md).

### Can we reorder equipment commands ?
Yes it is possible, just drag and drop the commands of your object on its configuration.

### Can we edit the style of the widgets ?
For each command you can choose its display between different Core widgets, or create one with Tools → Widgets.

### Can we put the same equipment more than once on a design ?
No it&#39;s not possible, but you can duplicate it thanks to the virtual plugin.

### How to change wrong historical data ?
It is enough, on a historical curve of the command, to click on the point in question. If you leave the field blank, then the value will be deleted.

### How long does a backup take ?
There is no standard duration, it depends on the system and the volume of data to be backed up, but it can take more than 5 minutes, this is normal.

### Where are Jeedom&#39;s backups ?
They are in the / var / www / html / backup folder

### Can we put Jeedom in https ?
Yes : Either you have a power pack or more, in this case you
just use the [Jeedom DNS](https://jeedom.github.io/documentation/howto/en_US/mise_en_place_dns_jeedom). Either with a DNS and you know how to set up a valid certificate, in this case it is a standard installation of a certificate.

### How to connect in SSH ?
Here's one [documentation](https://www.alsacreations.com/tuto/lire/612-Premiere-connexion-SSH.html), "Windows : Putty". The &quot;hostname&quot; being the ip of your Jeedom, the identifiers being :

- Username : "root ", password : "Mjeedom96"
- Username : "jeedom ", password : "Mjeedom96"
- Or what you put in the installation if you are in DIY

Note that when you write the password you will not see anything written on the screen, this is normal.

### How to reset rights ?
In SSH do :

`` `{.bash}
sudo su -
chmod -R 775 / var / www / html
chown -R www-data:www-data / var / www / html
`` ''

### How to update Jeedom in SSH ?
In SSH do :

`` `{.bash}
sudo su -
php /var/www/html/install/update.php
chmod -R 775 / var / www / html
chown -R www-data:www-data / var / www / html
`` ''

### Is the Webapp compatible Symbian ?
The webapp requires a smartphone supporting HTML5 and CSS3. It is therefore unfortunately not Symbian compatible.

### What platforms can Jeedom run on ?
For Jeedom to work, you need a linux platform with root rights or a docker type system. It therefore does not work on a pure android platform.

### I cannot update certain plugin &quot;Failed to download the file. Please try again later (size less than 100 bytes))..." ?
This can be due to several things, :

- Check that your Jeedom is still connected to the market (in the Jeedom administration page, updated part you have a test button).
- Check that the market account has purchased the plugin in question.
- Check that you have space on Jeedom (the health page will tell you).
- Check that your version of Jeedom is compatible with the plugin.

### I have a blank page
It is necessary to connect in SSH to Jeedom and launch the self-diagnostic script :
`` `{.bash}
sudo chmod + x / var / www / html / health.sh; sudo /var/www/html/health.sh
`` ''
If there is a problem, the script will try to correct it. If it can&#39;t, it will tell you.

You can also look at the log /var/www/html/log/http.error. Very often, this indicates the concern.

### I have a BDD identifier problem
These must be reset :

`` `{.bash}
bdd_password = $ (cat / dev / urandom | tr -cd &#39;a-f0-9' | head -c 15)
echo "DROP USER &#39;jeedom&#39; @ &#39;localhost'" | mysql -uroot -p
echo "CREATE USER &#39;jeedom&#39; @ &#39;localhost&#39; IDENTIFIED BY &#39;$ {bdd_password}&#39;;" | mysql -uroot -p
echo &quot;GRANT ALL PRIVILEGES ON jeedom.* TO &#39;jeedom&#39; @ &#39;localhost&#39;;" | mysql -uroot -p
cd / usr / share / nginx / www / jeedom
sudo cp core / config / common.config.sample.php core / config / common.config.php
sudo sed -i -e "s /#PASSWORD#/ $ {bdd_password} / g "core / config / common.config.php
sudo chown www-data:www-data core / config / common.config.php
`` ''

### I have \ {\ {… \} \} everywhere
The most frequent cause is the use of a plugin in beta version and Jeedom in stable, or the reverse. To get the details of the error, you have to look at the http log.error (in / var / www / html / log).

### On command I have a wheel that turns without stopping
Again this is often due to a plugin in beta while Jeedom is in stable. To see the error, you must do F12 then console.

### I no longer have access to Jeedom, neither through the web interface nor in console via SSH
This error is not due to Jeedom, but to a problem with the system.
If this persists following a reinstallation, it is advisable to check with the after-sales service for hardware concerns. Here is [documentation](https://doc.jeedom.com/en_US/installation/smart) for Smart

### My scenario does not stop any more
It is advisable to look at the commands executed by the scenario, often it comes from a command that does not end.

### I have instabilities or errors 504
Check if your file system is not corrupt, in SSH the command is : `` ''sudo dmesg | grep error`` ''.

### I have the following error : SQLSTATE \ [HY000 \] \ [2002 \] Can&#39;t connect to local MySQL server through socket &#39;/var/run/mysqld/mysqld.sock'
This is due to MySQL which stopped, it is not normal, the common cases are :

- Lack of space on the file system (can be checked by making the command "df -h", in SSH)
- File (s) corruption problem, which often happens after an unsafe shutdown of Jeedom (power failure)
- Memory worries, the system lacks memory and kills the most consuming process (often the database). This can be seen in the OS administration then dmesg, you should see a kill by "oom". If this is the case, reduce Jeedom&#39;s consumption by deactivating plugins.

Unfortunately, there is not much solution if it is the second case, the best being to recover a backup (available in / var / www / html / backup by default), reinstall Jeedom and restore the backup. You can also see why MySQL doesn&#39;t want to boot from an SSH console :
`` `{.bash}
sudo su -
mysql stop service
mysqld --verbose
`` ''
Or consult the log : /var/log/mysql/error.log

### The Shutdown / Restart buttons do not work
On a DIY installation it&#39;s normal. In SSH, you must make the visudo command and at the end of the file you must add : www-data ALL = (ALL)
NOPASSWD: ALL.

`` `{.bash}
sudo service apache2 restart
`` ''

### I don&#39;t see some plugins from the Market
This kind of case happens if your Jeedom is not compatible with the plugin. In general, a Jeedom update fixes the problem.

### I have timeout equipment but I don&#39;t see it on the Dashboard
Alerts are ranked by priority, from the least important to the most important : timeout, battery warning, battery danger, warning alert, danger alert

### My Jeedom permanently displays &quot;Starting up&quot; even after 1 hour ?
If you are in DIY and under Debian 9 or more, check that there has not been an update of Apache and therefore the return of privateTmp (visible by doing `ls / tmp` and see if there is a private \* Apache folder). If that&#39;s the case, you have to do :
`` ''
mkdir /etc/systemd/system/apache2.service.d
echo &quot;[Service]&quot;&gt; /etc/systemd/system/apache2.service.d/privatetmp.conf
echo &quot;PrivateTmp = no&quot; &gt;&gt; /etc/systemd/system/apache2.service.d/privatetmp.conf
`` ''

### I have a time concern on my history
Try to clear the chrome cache, the display of histories is calculated relative to the browser time.

### I have the error "Network issues detected, network restart"
Jeedom can&#39;t find or can&#39;t ping the gateway. In general it happens if the adsl box restarts (in particular liveboxes) and Jeedom has not restarted or has restarted faster than the box. For security he tells you that he has found a problem and relaunches the network connection process. You can deactivate this mechanism by going to the Jeedom configuration and by deactivating the network management by Jeedom.

### I get the message &quot;Failed to back up the database. Check that mysqldump is present."
It means that Jeedom cannot back up the database which can suggest a problem with database and filesystem corruption. There is unfortunately no miracle command to correct. The best is to launch a backup and analyze the log of it. In known cases of concerns we have:

- a corrupt base table => there is a bad start we must see to try to repair and if it does not start from the last good backup (if you are on SD card it is the right time to change it)
- not enough space on the filesystem =&gt; look at the health page this can tell you

### I have errors of type &quot;Class &#39;eqLogic&#39; not found&quot;, files seem to be missing or I have a blank page
It is a fairly serious error, the simplest is to make
`` ''
mkdir -p / root / tmp /
cd / root / tmp
wget https://github.com/jeedom/core/archive/master.zip
unzip master.zip
cp -R / root / tmp / core-master / * / var / www / html
rm -rf / root / tmp / core-master
`` ''

### I have the error in scenario_execution MYSQL_ATTR_INIT_COMMAND
In the administration of Jeedom part OS / DB then in the system console it is necessary to make :
`` ''
yes | sudo apt install -y php-mysql php-curl php-gd php-imap php-xml php-opcache php-soap php-xmlrpc php-common php-dev php-zip php-ssh2 php-mbstring php-ldap
`` ''

### I cannot install the plugin dependencies I have an error of the type : "E: dpkg has been discontinued. Il est nécessaire d'utiliser « sudo dpkg --configure -a » pour corriger le problème." ou "E: Could not get lock / var / lib / dpkg / lock"

It is necessary :

- restart Jeedom
- go to the administration of it (notched wheel button at the top right then configuration in v3 or Setup -> System -> Configuration in v4)
- go to the OS / DB tab
- launch system administration
- click on Dpkg configure
- wait 10min
- relaunch the dependencies of the blocking plugins

### I have this error when installing plugin dependencies : "from pip._internal import main"

It is necessary in the system console of Jeedom or in ssh to make

`` ''``
sudo easy_install pip
sudo easy_install3 pip
`` ''``

Then relaunch the dependencies


### From the 4.2, I can no longer display iframe

Core 4.2 greatly enhances Jeedom's security. If you really (knowingly) need to revert to an insecure version of your Jeedom :
Go to **Settings -> System -> Configuration** then in **OS / DB**, launch the system administration console and click **Apache not secure**. A restart of Jeedom is recommended following this change.

### From the 4.2, some plugins no longer work and in the browser console (F12 key) I have 403 errors

This is due to the security of Apache which requires plugin developers to put the right files in the right directories to limit Jeedom's attack surface. This security is done in the file .htaccess (overwritten each time the core is updated). You can make a file .htaccess_custom with your own rules which if it exists will be used instead of the file .htaccess from Core.