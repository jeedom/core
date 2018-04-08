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

Je ne peux mettre à jour certain plugin "Echec lors du téléchargement du fichier. Veuillez réessayer plus tard (taille inférieure à 100 octets)..." ? 
====================================================

Cela peut etre du à plusieurs chose, il faut : 

- Vérifier que votre Jeedom est toujours connecté au market (dans la page d'administration de jeedom, partie mise à jour vous avez un bouton de test)
- Vérifier que le compte market à bien acheté le plugin en question
- Vérifier que vous avez bien de la place sur Jeedom (la page santé vous l'indiquera)
- Vérifier que votre version de Jeedom est bien compatible avec le plugin

J’ai une page blanche 
=====================

Il faut se connecter en SSH à Jeedom et lancer le script
d’auto-diagnostic :

``` {.bash}
sudo chmod +x /var/www/html/health.sh;sudo /var/www/html/health.sh
```

S’il y a un souci, le script essaiera de le corriger. S’il n’y arrive
pas, il vous l’indiquera.

Vous pouvez aussi regarder le log /var/www/html/log/http.error. Très
souvent, celui-ci indique le souci.

J’ai un problème d’identifiant BDD 
==================================

Il faut réinitialiser ceux-ci :

``` {.bash}
bdd_password=$(cat /dev/urandom | tr -cd 'a-f0-9' | head -c 15)
echo "DROP USER 'jeedom'@'localhost'" | mysql -uroot -p
echo "CREATE USER 'jeedom'@'localhost' IDENTIFIED BY '${bdd_password}';" | mysql -uroot -p
echo "GRANT ALL PRIVILEGES ON jeedom.* TO 'jeedom'@'localhost';" | mysql -uroot -p
cd /usr/share/nginx/www/jeedom
sudo cp core/config/common.config.sample.php core/config/common.config.php
sudo sed -i -e "s/#PASSWORD#/${bdd_password}/g" core/config/common.config.php
sudo chown www-data:www-data core/config/common.config.php
```

J’ai des {{…​}} partout 
=======================

La cause la plus fréquente est l’utilisation d’un plugin en version beta
et Jeedom en stable, ou l’inverse. Pour avoir le détail de l’erreur, il
faut regarder le log http.error (dans /var/www/html/log).

Lors d’une commande j’ai une roue qui tourne sans s’arrêter 
===========================================================

Encore une fois cela est souvent dû à un plugin en beta alors que Jeedom
est en stable. Pour voir l’erreur, il faut faire F12 puis console.

Je n’ai plus accès à Jeedom, ni par l’interface web ni en console par SSH 
=========================================================================

Cette erreur n’est pas due à Jeedom, mais à un problème avec le système.
Si celui-ci persiste suite à une réinstallation, il est conseillé de
voir avec le SAV pour un souci hardware.

Mon scénario ne s’arrête plus/pas 
=================================

Il est conseillé de regarder les commandes exécutées par le scénario,
souvent cela vient d’une commande qui ne se termine pas.

J’ai des instabilités ou des erreurs 504 
========================================

Vérifiez si votre système de fichiers n’est pas corrompu, en SSH la
commande est : "sudo dmesg | grep error" .

Je ne vois pas tous mes équipements sur le dashboard 
====================================================

Souvent cela est dû au fait que les équipements sont affectés à un objet
qui n’est pas le fils ou l’objet lui-même du premier objet sélectionné à
gauche dans l’arbre (vous pouvez configurer celui-ci dans votre profil).

J’ai l’erreur suivante : SQLSTATE\[HY000\] \[2002\] Can’t connect to local MySQL server through socket '/var/run/mysqld/mysqld.sock' 
====================================================================================================================================

Cela est dû à MySQL qui s’est arrêté, ce n’est pas normal, les cas
courants sont :

-   Manque de place sur le système de fichiers (peut être vérifié en
    faisant la commande "df -h", en SSH)

-   Problème de corruption de fichier(s), ce qui arrive souvent suite à
    un arrêt non propre de Jeedom (coupure de courant)

Malheureusement, il n’y a pas beaucoup de solution si c’est le deuxième
cas, le mieux étant de récupérer une sauvegarde (disponible dans
/var/www/html/backup par défaut), de réinstaller Jeedom et
de restaurer la sauvegarde. Vous pouvez aussi regarder pourquoi MySQL ne
veut pas démarrer depuis une console SSH :

``` {.bash}
sudo su -
service mysql stop
mysqld --verbose
```

Ou consulter le log : /var/log/mysql/error.log

Les boutons Eteindre/Redémarrer ne fonctionnent pas 
===================================================

Sur une installation DIY c’est normal. En SSH, il faut faire la commande
visudo et à la fin du fichier vous devez ajouter : www-data ALL=(ALL)
NOPASSWD: ALL.

``` {.bash}
sudo service apache2 restart
```

Je ne vois pas certains plugins du Market 
=========================================

Ce genre de cas arrive si votre Jeedom n’est pas compatible avec le
plugin. En général, une mise à jour de jeedom corrige le souci.

J'ai un équipement en timeout mais je ne le vois pas sur le dashboard
=========================================

Les alerte sont classé par priorité, de la moins importante à la plus importante : timeout, batterie warning, batterie danger, alerte warning, alerte danger

Mon Jeedom affiche en permanance "En cours de démarrage" même après 1h ? 
=====================================

Si vous etes en DIY et sous Debian 9 ou plus, vérifiez qu'il n'y a pas eu une mise à jour d'Apache et donc le retour du privateTmp (visible en faisant `ls /tmp` et voir si il y a un dossier private\*Apache). Si c'est le cas il faut faire :

``` 
mkdir /etc/systemd/system/apache2.service.d
echo "[Service]" > /etc/systemd/system/apache2.service.d/privatetmp.conf
echo "PrivateTmp=no" >> /etc/systemd/system/apache2.service.d/privatetmp.conf
```
