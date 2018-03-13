Erfordert Jeedom ein Abonnement ?
=====================================

Nein, Jeedom ist voll nutzbar, ohne dass irgendwelches Abonnements
benötigt wird. Allerdings gibt es Dienstleistungsangebot
für Backups oder Anruf/SMS, aber diese sind Tatsächlich 
optional.

Benutzt Jeedom einen externen Server, um zu funktionieren ?
==============================================================

Nein, Jeedom verwendet keine solche "Cloud" Infrastruktur. Alles wird vor
Ort durchgeführt, sodas sie unseren Server nicht brauchen, damit Ihre
Installation funktioniert. Nur Dienstleistungen wie der Markt,
Online-Backup oder Jeedom-DNS erfordern den Einsatz von unserem
Server.

Können die Befehle der Geräte neu angeordnet werden ?
==================================================

Ja, es ist möglich, einfach mit Drag/Drop die Befehle Ihres Objekts in seiner
Konfiguration verschieben.

Können wir den Widget Stil bearbeiten ? 
=====================================

Ja, es ist möglich, entweder über das Widget-Plugin, oder mit Hilfe der 
Seite Allgemein → Anzeige

Können wir mehrmals die gleichen Geräte auf einem Design darstellen ?
================================================================

Das ist nicht möglich, aber Sie können diese Geräte vervielfältigen, dank des
virtuellen Plugins.

Wie kann man eine fehlerhafte Chronik ändern ? 
====================================================

Es genügt auf einer Kurve der entsprechenden Chronik, auf den fraglichen
Punkt zu klicken. Wenn Sie das Feld leer lassen, dann wird der Wert
gelöscht.

Wie lange dauert eine Sicherung? 
======================================

Es gibt keine Standard-Dauer, es hängt vom System und der Menge der zu
sichernden Daten ab, aber es kann mehr als 5 Minuten dauern, das ist
normal.

Gibt es eine spezielle Mobile Anwendung ? 
========================================

Jeedom hat eine mobile Version für den Einsatz auf Handy und Tablet. Es
gibt auch eine native App für Android und iOS.

Was sind die Anmeldeinformationen für die erste Anmeldung ? 
================================================================

Wenn Sie sich zum ersten Mal bei Jeedom anmelden (und selbst wenn Sie
sie nicht geändert haben), lautet der Standardbenutzername und das
Passwort admin/admin. Bei der ersten Anmeldung wird dringend empfohlen,
diese Zugangsdaten für mehr Sicherheit zu ändern.

Kann Jeedom https ? 
================================

Ja: * Haben Sie ein Power- oder Plus-Paket, in diesem Fall verwenden Sie
einfach die Jeedom-DNS. * Oder Sie haben DNS und Sie haben ein gültiges
Zertifikat eingerichtet, in diesem Fall ist es eine standard-Installation eines
Zertifikats.

Wie sind die Rechte zu überarbeiten? 
====================================

In SSH eingeben :

``` {.bash}
sudo su -
chmod -R 775 /var/www/html
chown -R www-data:www-data /var/www/html
```

Wo sind die Jeedom Backups ? 
==========================================

Sie sind im Verzeichnis /var/www/html/backup

Wie aktualisiert man Jeedom in SSH ? 
=====================================

In SSH eingeben :

``` {.bash}
sudo su -
php /var/www/html/install/update.php
chmod -R 775 /var/www/html
chown -R www-data:www-data /var/www/html
```

Ist die Webapp mit Symbian kompatibel ? 
=======================================

Die Webapp erfordert ein Smartphone, das HTML5 und CSS3 unterstützt. Sie
ist daher leider nicht mit Symbian kompatibel.

Auf welchen Plattformen kann Jeedom arbeiten ? 
====================================================

Damit Jeedom funktioniert, ist eine Linux Plattform mit root Rechten
notwendig oder ein typisches Docker System. Es funktioniert nicht auf einer
reinen Android-Plattform.

Ich kann einige Plugins nicht aktualisieren "Fehler beim Herunterladen der Datei. Bitte versuchen Sie es später erneut (Größe kleiner als 100 Byte) ... " ?
====================================================

Dies kann auf mehrere Dinge zurückgeführt werden, es ist notwendig :

- Überprüfen Sie, dass Ihr Jeedom immer noch mit dem Markt verbunden ist (auf der Jeedom Administration Page, im Abschnitt Update haben Sie eine Test-Schaltfläche)
- Überprüfen Sie das auf dem Markt-Konto das betreffende Plugin richtig gekauft wurde
- Stellen Sie sicher, dass Sie Platz auf Jeedom haben ( auf der Gesundheitsseite wird es ihnen angezeigt)
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
