Jeedom benötigt ein Abonnement ? 
=====================================

Nein, Jeedom ist voll nutzbar, ohne dass es eines braucht
Abonnement überhaupt. Es werden jedoch Dienstleistungen für angeboten
Backups oder Anrufe / SMS, die aber tatsächlich bleiben
optionnels.

Verwendet Jeedom externe Server zum Ausführen? ? 
==============================================================

Nein, Jeedom verwendet keine Cloud-Infrastruktur". Alles ist erledigt in
lokal und Sie brauchen unsere Server nicht für Ihre
Installation funktioniert. Nur Dienstleistungen wie der Markt, der
Online-Backup oder Jeedom DNS erfordern die Verwendung unserer
serveurs.

Können wir Ausrüstungsbestellungen nachbestellen? ? 
==================================================

Ja, es ist möglich, ziehen Sie einfach Ihre Bestellungen per Drag &amp; Drop
Objekt auf seine Konfiguration.

Können wir den Stil der Widgets bearbeiten? ? 
=====================================

Ja, es ist möglich, entweder über das Widget-Plugin oder über
Verwenden Sie die Seite Allgemein → Anzeige

Können wir die gleiche Ausrüstung mehr als einmal auf ein Design setzen? ? 
================================================================

Nein, es ist nicht möglich, aber Sie können es dank der duplizieren
virtuelles Plugin.

So ändern Sie falsche historische Daten ? 
====================================================

Es reicht aus, auf einer historischen Kurve der Reihenfolge auf das zu klicken
Punkt in Frage. Wenn Sie das Feld leer lassen, wird der Wert angezeigt
wird gelöscht.

Wie lange dauert ein Backup? ? 
======================================

Es gibt keine Standarddauer, dies hängt vom System und der Lautstärke von ab
Die zu sichernden Daten können jedoch länger als 5 Minuten dauern
normal.

Gibt es eine dedizierte mobile App ? 
========================================

Jeedom hat eine mobile Version, die für den Einsatz auf Mobilgeräten geeignet ist
Tablette. Es gibt auch eine native App für Android und iOS.

Was sind die Anmeldeinformationen, um sich beim ersten Mal anzumelden? ? 
================================================================

Wenn Sie sich zum ersten Mal bei Jeedom anmelden (und auch danach, wenn Sie dies nicht tun)
nicht geändert), Standardbenutzername und Passwort
sind admin / admin. Bei der ersten Verbindung sind Sie stark
Es wird empfohlen, diese Kennungen für mehr Sicherheit zu ändern.

Können wir Jeedom in https setzen? ? 
================================

Ja : Entweder Sie haben ein Netzteil oder mehr, in diesem Fall Sie
benutze einfach die [DNS Jeedom](https://doc.jeedom.com/de_DE/howto/mise_en_place_dns_jeedom). Entweder mit einem DNS und Sie wissen, wie man ein gültiges Zertifikat einrichtet. In diesem Fall handelt es sich um eine Standardinstallation eines Zertifikats.

So verbinden Sie sich in SSH ?
=============================

Hier ist ein [Dokumentation](https://www.alsacreations.com/tuto/lire/612-Premiere-connexion-SSH.html), "Windows : Putty". Der &quot;Hostname&quot; ist die IP Ihres Jeedom, die Bezeichner sind :

- Benutzername : "root ", Passwort : "Mjeedom96"
- Benutzername : "jeedom ", Passwort : "Mjeedom96"
- Oder was Sie in die Installation einfügen, wenn Sie in DIY sind

Beachten Sie, dass beim Schreiben des Passworts nichts auf dem Bildschirm angezeigt wird. Dies ist normal.

So setzen Sie Rechte zurück ? 
====================================

In SSH tun :

`` `{.bash}
sudo su -
chmod -R 775 / var / www / html
chown -R www-Daten:www-data / var / www / html
`` ''

Wo sind Jeedom&#39;s Backups? ? 
==========================================

Sie befinden sich im Ordner / var / www / html / backup

So aktualisieren Sie Jeedom in SSH ? 
=====================================

In SSH tun :

`` `{.bash}
sudo su -
php /var/www/html/install/update.php
chmod -R 775 / var / www / html
chown -R www-Daten:www-data / var / www / html
`` ''

Ist das Webapp-kompatible Symbian ? 
=======================================

Für die Webanwendung ist ein Smartphone erforderlich, das HTML5 und CSS3 unterstützt. Es ist daher leider nicht Symbian-kompatibel.

Auf welchen Plattformen kann Jeedom laufen? ? 
====================================================

Damit Jeedom funktioniert, benötigen Sie eine Linux-Plattform mit den Rechten
root oder ein Docker-System. Es funktioniert daher nicht auf einem
reine Android-Plattform.

Ich kann ein bestimmtes Plugin nicht aktualisieren. &quot;Fehler beim Herunterladen der Datei. Bitte versuchen Sie es später erneut (Größe weniger als 100 Byte))..." ? 
====================================================

Dies kann auf verschiedene Dinge zurückzuführen sein, es ist notwendig : 

- Überprüfen Sie, ob Ihr Jeedom noch mit dem Markt verbunden ist (auf der Jeedom-Verwaltungsseite, aktualisierter Teil, haben Sie eine Testschaltfläche)
- Überprüfen Sie, ob das Marktkonto das betreffende Plugin gekauft hat
- Überprüfen Sie, ob auf Jeedom Platz ist (die Gesundheitsseite informiert Sie darüber)
- Überprüfen Sie, ob Ihre Version von Jeedom mit dem Plugin kompatibel ist
- Überprüfen Sie, ob Ihr Jeedom noch korrekt mit dem Markt verbunden ist (Klicken Sie in der Jeedom-Konfiguration auf die Registerkarte Aktualisieren)

Ich habe eine leere Seite 
=====================

Sie müssen in SSH eine Verbindung zu Jeedom herstellen und das Skript starten
Selbstdiagnose :

`` `{.bash}
sudo chmod + x / var / www / html / health.sh; sudo /var/www/html/health.sh
`` ''

Wenn es ein Problem gibt, versucht das Skript, es zu beheben. Wenn er nicht kann
Nein, es wird dir sagen.

Sie können sich auch das Protokoll /var/www/html/log/http.error ansehen. sehr
oft deutet dies auf Besorgnis hin.

Ich habe ein BDD-Identifizierungsproblem 
==================================

Diese müssen zurückgesetzt werden :

`` `{.bash}
bdd_password = $ (cat / dev / urandom | tr-cd &#39;a-f0-9' | Kopf -c 15)
echo "DROP USER &#39;jeedom&#39; @ &#39;localhost'" | mysql -uroot -p
echo "CREATE USER &#39;jeedom&#39; @ &#39;localhost&#39; IDENTIFIED BY &#39;$ {bdd_password}&#39;;" | mysql -uroot -p
echo &quot;GEWÄHRLEISTUNG ALLER PRIVILEGIEN FÜR jeedom.* TO &#39;jeedom&#39; @ &#39;localhost&#39;;" | mysql -uroot -p
cd / usr / share / nginx / www / jeedom
sudo cp core / config / common.config.sample.PHP Core / Config / Common.config.php
sudo sed -i -e "s /#PASSWORD#/ $ {bdd_password} / g "core / config / common.config.php
sudo chown www-data:www-data core / config / common.config.php
`` ''

Ich habe überall \ {\ {… \} \} 
=======================

Die häufigste Ursache ist die Verwendung eines Beta-Plugins
und Jeedom im Stall oder umgekehrt. Um das Detail des Fehlers zu haben, ist es
muss auf das http-Protokoll schauen.Fehler (in / var / www / html / log).

Bei der Bestellung habe ich ein Rad, das sich dreht, ohne anzuhalten 
===========================================================

Auch dies ist oft auf ein Beta-Plugin während Jeedom zurückzuführen
ist im Stall. Um den Fehler zu sehen, müssen Sie F12 und dann die Konsole ausführen.

Ich habe keinen Zugriff mehr auf Jeedom, weder über die Weboberfläche noch über SSH in der Konsole 
=========================================================================

Dieser Fehler ist nicht auf Jeedom zurückzuführen, sondern auf ein Problem mit dem System.
Wenn es nach einer Neuinstallation weiterhin besteht, ist es ratsam,
Informationen zum Hardware-Problem erhalten Sie beim Kundendienst. Hier ist die [Dokumentation](https://doc.jeedom.com/de_DE/installation/smart) für Smart

Mein Szenario hört nicht mehr auf 
=================================

Es ist ratsam, sich die vom Szenario ausgeführten Befehle anzusehen,
oft kommt dies von einer Bestellung, die nicht endet.

Ich habe Instabilitäten oder Fehler 504 
========================================

Überprüfen Sie, ob Ihr Dateisystem nicht beschädigt ist
Befehl ist : "sudo dmesg | grep Fehler" .

Ich sehe nicht alle meine Geräte auf dem Armaturenbrett 
====================================================

Dies liegt häufig daran, dass das Gerät einem Objekt zugeordnet ist
Dies ist nicht das untergeordnete Element oder das Objekt selbst des ersten ausgewählten Objekts
links im Baum (Sie können es in Ihrem Profil konfigurieren).

Ich habe den folgenden Fehler : SQLSTATE \ [HY000 \] \ [2002 \] Über Socket &#39;/var/run/mysqld/mysqld.sock kann keine Verbindung zum lokalen MySQL-Server hergestellt werden' 
====================================================================================================================================

Dies liegt an MySQL, das gestoppt hat, es ist nicht normal, die Fälle
Ströme sind :

-   Platzmangel im Dateisystem (kann von überprüft werden
    Führen Sie den Befehl "df -h" in SSH aus)

-   Problem mit der Beschädigung von Dateien, das häufig aufgrund von
    Jeedom's nicht sauberes Herunterfahren (Stromausfall)

- 	Speicherprobleme, das System verfügt nicht über Speicher und beendet den aufwendigsten Prozess (häufig die Datenbank)). Dies kann in der OS-Administration gesehen werden, dann sollten Sie einen Kill von "oom" sehen". Wenn dies der Fall ist, reduzieren Sie den Verbrauch von Jeedom, indem Sie Plugins deaktivieren.

Leider gibt es nicht viel Lösung, wenn es die zweite ist
In diesem Fall ist es am besten, ein Backup wiederherzustellen (verfügbar in
/ var / www / html / backup standardmäßig), installieren Sie Jeedom und neu
um das Backup wiederherzustellen. Sie können auch sehen, warum MySQL nicht ist
Ich möchte nicht von einer SSH-Konsole booten :

`` `{.bash}
sudo su -
MySQL Stop Service
mysqld --verbose
`` ''

Oder konsultieren Sie das Protokoll : /var/log/mysql/error.log

Die Schaltflächen Herunterfahren / Neustart funktionieren nicht 
===================================================

Bei einer DIY-Installation ist das normal. In SSH müssen Sie bestellen
visudo und am Ende der Datei müssen Sie hinzufügen : www-data ALL = (ALL)
NOPASSWD: Alle.

`` `{.bash}
sudo service apache2 neu starten
`` ''

Ich sehe keine Plugins vom Markt 
=========================================

Diese Art von Fall tritt auf, wenn Ihr Jeedom nicht mit dem kompatibel ist
Plugin. Im Allgemeinen behebt ein Jeedom-Update das Problem.

Ich habe Timeout-Ausrüstung, sehe sie aber nicht auf dem Armaturenbrett
=========================================

Die Warnungen werden nach Priorität klassifiziert, von der am wenigsten wichtigen bis zur wichtigsten : Zeitüberschreitung, Batteriewarnung, Batteriegefahr, Warnmeldung, Gefahrenwarnung

Mein Jeedom zeigt auch nach 1 Stunde permanent &quot;Start&quot; an ? 
=====================================

Wenn Sie in DIY und unter Debian 9 oder höher sind, überprüfen Sie, ob es kein Update von Apache und damit die Rückgabe von privateTmp gegeben hat (sichtbar durch Ausführen von `ls / tmp`) und prüfen Sie, ob dies der Fall ist ein privater \* Apache-Ordner). Wenn das der Fall ist, müssen Sie tun :

`` '' 
mkdir /etc/systemd/system/apache2.service.d
echo &quot;[Service]&quot;&gt; /etc/systemd/system/apache2.service.d/privatetmp.conf
echo &quot;PrivateTmp = no&quot; &gt;&gt; /etc/systemd/system/apache2.service.d/privatetmp.conf
`` '' 

Ich habe ein zeitliches Problem mit meiner Geschichte
=========================================

Versuchen Sie, den Chrome-Cache zu leeren. Die Verlaufsanzeige wird relativ zur Browserzeit berechnet.

Ich habe den Fehler "Netzwerkprobleme erkannt, Netzwerkneustart"
=========================================

Jeedom kann das Gateway nicht finden oder anpingen. Im Allgemeinen passiert es, wenn die ADSL-Box neu gestartet wird (insbesondere Liveboxen) und Jeeodm nicht neu gestartet wurde oder schneller als die Box neu gestartet wurde. Aus Sicherheitsgründen teilt er Ihnen mit, dass er ein Problem gefunden hat, und startet den Netzwerkverbindungsprozess neu. Sie können diesen Mechanismus deaktivieren, indem Sie zur Jeedom-Konfiguration wechseln und die Netzwerkverwaltung von Jeedom deaktivieren.

Ich erhalte die Meldung &quot;Fehler beim Sichern der Datenbank. Überprüfen Sie, ob mysqldump vorhanden ist."
=========================================
Dies bedeutet, dass Jeedom die Datenbank nicht sichern kann, was auf ein Problem mit der Beschädigung der Datenbank und des Dateisystems hinweisen kann. Es gibt leider keinen Wunderbefehl zu korrigieren. Am besten starten Sie ein Backup und analysieren das Protokoll. In bekannten Fällen von Bedenken haben wir

- Eine beschädigte Basistabelle => Sie ist schlecht gestartet. Sie müssen versuchen, sie zu reparieren. Wenn sie nicht mit der letzten guten Sicherung beginnt (wenn Sie sich auf SD Guard befinden, ist es der richtige Zeitpunkt, sie zu ändern)
- Nicht genügend Speicherplatz im Dateisystem =&gt; Schauen Sie sich die Gesundheitsseite an, die Sie darüber informieren können


Ich kann mich nicht mehr mit meinem Jeedom verbinden
=========================================
Seit Jeedom 3.2 Aus offensichtlichen Sicherheitsgründen ist es nicht mehr möglich, eine Remoteverbindung mit admin / admin herzustellen. Die Admin / Admin-IDs funktionieren nur lokal. Achtung, wenn Sie den DNS auch lokal durchlaufen, werden Sie notwendigerweise als remote identifiziert. Anderer Standardpunkt nur IP auf 192.168.*.* oder 127.0.0.1 werden als lokal anerkannt. Es wird in der Administration des Jeedom-Sicherheitsteils dann IP "weiß konfiguriert". Wenn Sie trotz alledem immer noch keine Verbindung herstellen können, müssen Sie das Verfahren zum Zurücksetzen des Passworts verwenden (siehe Tutorials / Vorgehensweise))

Ich habe Fehler vom Typ &quot;Klasse &#39;eqLogic&#39; nicht gefunden&quot;, Dateien scheinen zu fehlen oder ich habe eine leere Seite
=========================================
Es ist ein ziemlich schwerwiegender Fehler, der am einfachsten zu machen ist 

`` '' 
mkdir -p / root / tmp /
cd / root / tmp
wget https://github.com/jeedom/core/archive/master.zip
entpacke master.zip
cp -R / root / tmp / core-master / * / var / www / html
rm -rf / root / tmp / core-master
`` ''

# Ich kann die Plugin-Abhängigkeiten nicht installieren. Ich habe einen Fehler des Typs : "E: dpkg wurde eingestellt. Il est nécessaire d'utiliser « sudo dpkg --configure -a » pour corriger le problème." ou "E: Lock / var / lib / dpkg / lock konnte nicht abgerufen werden"

Du musst :

- Jeedom neu starten
- Gehen Sie zur Verwaltung (Schaltfläche mit gekerbtem Rad oben rechts, dann Konfiguration in Version 3 oder Setup -> System -> Konfiguration in Version 4))
- Wechseln Sie zur Registerkarte OS / DB
- Starten Sie die Systemadministration
- Klicken Sie auf Dpkg konfigurieren
- Warten Sie 10 Minuten
- Starten Sie die Abhängigkeiten der blockierenden Plugins neu
