# FAQ
**Einstellungen → Version : FAQ**

### Jeedom benötigt ein Abonnement ?
Nein, Jeedom ist vollständig nutzbar, ohne dass ein Abonnement erforderlich ist. Es werden jedoch Dienste für Backups oder Anrufe / SMS angeboten, die jedoch wirklich optional sind.

### Verwendet Jeedom externe Server zum Ausführen? ?
Nein, Jeedom verwendet keine Cloud-Infrastruktur". Alles wird lokal erledigt und Sie benötigen unsere Server nicht, damit Ihre Installation funktioniert. Nur Dienste wie Market, Online Backup oder Jeedom DNS erfordern die Verwendung unserer Server.

### Gibt es eine dedizierte mobile App ?
Jeedom verfügt über eine mobile Version, die für Mobilgeräte und Tablets geeignet ist. Es gibt auch eine native App für Android und iOS.

### Was sind die Anmeldeinformationen, um sich beim ersten Mal anzumelden? ?
Wenn Sie sich zum ersten Mal bei Jeedom anmelden (und auch danach, wenn Sie sie nicht geändert haben), lauten der Standardbenutzername und das Standardkennwort admin / admin. Bei der ersten Verbindung wird dringend empfohlen, diese Kennungen zu ändern, um die Sicherheit zu erhöhen.

### Ich kann mich nicht mehr mit meinem Jeedom verbinden
Seit Jeedom 3.2 Aus offensichtlichen Sicherheitsgründen ist es nicht mehr möglich, eine Remoteverbindung mit admin / admin herzustellen. Admin / Admin-Anmeldeinformationen funktionieren nur lokal. Achtung, wenn Sie den DNS auch lokal durchlaufen, werden Sie notwendigerweise als remote identifiziert. Anderer Standardpunkt nur IP auf 192.168.*.* oder 127.0.0.1 werden als lokal anerkannt. Es wird in der Administration des Jeedom-Sicherheitsteils dann IP "weiß konfiguriert". Wenn Sie trotz allem immer noch keine Verbindung herstellen können, müssen Sie das Verfahren zum Zurücksetzen des Kennworts verwenden (siehe) [Hier](https://doc.jeedom.com/de_DE/howto/reset.password).

### Ich sehe nicht alle meine Geräte im Dashboard
Dies liegt häufig daran, dass die Ausrüstung einem Objekt zugeordnet ist, das nicht der Sohn oder das Objekt selbst des ersten links im Baum ausgewählten Objekts ist (Sie können dieses in Ihrem Profil konfigurieren).

### Die Jeedom-Schnittstelle verfügt über Verknüpfungen ?
Ja, die Liste der Tastatur- / Mausverknüpfungen lautet [Hier](shortcuts.md).

### Können wir Ausrüstungsbestellungen nachbestellen? ?
Ja, es ist möglich, ziehen Sie einfach die Befehle Ihres Objekts in die Konfiguration und legen Sie sie dort ab.

### Können wir den Stil der Widgets bearbeiten? ?
Für jeden Befehl können Sie die Anzeige zwischen verschiedenen Core-Widgets auswählen oder eine mit Extras → Widgets erstellen.

### Können wir die gleiche Ausrüstung mehr als einmal auf ein Design setzen? ?
Nein, es ist nicht möglich, aber Sie können es dank des virtuellen Plugins duplizieren.

### So ändern Sie falsche historische Daten ?
Es reicht aus, auf einer historischen Kurve der Reihenfolge auf den betreffenden Punkt zu klicken. Wenn Sie das Feld leer lassen, wird der Wert gelöscht.

### Wie lange dauert ein Backup? ?
Es gibt keine Standarddauer, dies hängt vom System und dem zu sichernden Datenvolumen ab. Dies kann jedoch länger als 5 Minuten dauern. Dies ist normal.

### Wo sind Jeedom&#39;s Backups? ?
Sie befinden sich im Ordner / var / www / html / backup

### Können wir Jeedom in https setzen? ?
Ja : Entweder Sie haben ein Netzteil oder mehr, in diesem Fall Sie
benutze einfach die [Jeedom DNS](https://jeedom.github.io/documentation/howto/de_DE/mise_en_place_dns_jeedom). Entweder mit einem DNS und Sie wissen, wie man ein gültiges Zertifikat einrichtet. In diesem Fall handelt es sich um eine Standardinstallation eines Zertifikats.

### So verbinden Sie sich in SSH ?
Hier ist ein [Dokumentation](https://www.alsacreations.com/tuto/lire/612-Premiere-connexion-SSH.html), "Windows : Putty". Der &quot;Hostname&quot; ist die IP Ihres Jeedom, die Bezeichner sind :

- Nutzername : "root ", Passwort : "Mjeedom96"
- Nutzername : "jeedom ", Passwort : "Mjeedom96"
- Oder was Sie in die Installation einfügen, wenn Sie in DIY sind

Beachten Sie, dass beim Schreiben des Passworts nichts auf dem Bildschirm angezeigt wird. Dies ist normal.

### So setzen Sie Rechte zurück ?
In SSH tun :

`` `{.bash}
sudo su -
chmod -R 775 / var / www / html
chown -R www-Daten:www-data / var / www / html
`` ''

### So aktualisieren Sie Jeedom in SSH ?
In SSH tun :

`` `{.bash}
sudo su -
php /var/www/html/install/update.php
chmod -R 775 / var / www / html
chown -R www-Daten:www-data / var / www / html
`` ''

### Ist das Webapp-kompatible Symbian ?
Für die Webanwendung ist ein Smartphone erforderlich, das HTML5 und CSS3 unterstützt. Es ist daher leider nicht Symbian-kompatibel.

### Auf welchen Plattformen kann Jeedom laufen? ?
Damit Jeedom funktioniert, benötigen Sie eine Linux-Plattform mit Root-Rechten oder ein Docker-System. Es funktioniert daher nicht auf einer reinen Android-Plattform.

### Ich kann ein bestimmtes Plugin nicht aktualisieren. &quot;Fehler beim Herunterladen der Datei. Bitte versuchen Sie es später erneut (Größe weniger als 100 Byte))..." ?
Dies kann auf verschiedene Dinge zurückzuführen sein: :

- Überprüfen Sie, ob Ihr Jeedom noch mit dem Markt verbunden ist (auf der Jeedom-Verwaltungsseite, aktualisierter Teil, haben Sie eine Testschaltfläche).
- Überprüfen Sie, ob das Marktkonto das betreffende Plugin gekauft hat.
- Überprüfen Sie, ob auf Jeedom Platz ist (die Gesundheitsseite informiert Sie darüber).
- Überprüfen Sie, ob Ihre Version von Jeedom mit dem Plugin kompatibel ist.

### Ich habe eine leere Seite
Es ist erforderlich, in SSH eine Verbindung zu Jeedom herzustellen und das Selbstdiagnoseskript zu starten :
`` `{.bash}
sudo chmod + x / var / www / html / health.sh; sudo /var/www/html/health.sh
`` ''
Wenn es ein Problem gibt, versucht das Skript, es zu beheben. Wenn es nicht kann, wird es Ihnen sagen.

Sie können sich auch das Protokoll /var/www/html/log/http.error ansehen. Sehr oft deutet dies auf die Besorgnis hin.

### Ich habe ein BDD-Identifizierungsproblem
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

### Ich habe überall \ {\ {… \} \}
Die häufigste Ursache ist die Verwendung eines Plugins in der Beta-Version und Jeedom in Stable oder umgekehrt. Um die Details des Fehlers zu erhalten, müssen Sie sich das http-Protokoll ansehen.Fehler (in / var / www / html / log).

### Bei der Bestellung habe ich ein Rad, das sich dreht, ohne anzuhalten
Auch dies ist oft auf ein Plugin in der Beta zurückzuführen, während Jeedom stabil ist. Um den Fehler zu sehen, müssen Sie F12 und dann die Konsole ausführen.

### Ich habe keinen Zugriff mehr auf Jeedom, weder über die Weboberfläche noch über SSH in der Konsole
Dieser Fehler ist nicht auf Jeedom zurückzuführen, sondern auf ein Problem mit dem System.
Wenn dies nach einer Neuinstallation weiterhin der Fall ist, sollten Sie sich beim Kundendienst nach Hardwareproblemen erkundigen. Hier ist die [Dokumentation](https://doc.jeedom.com/de_DE/installation/smart) für Smart

### Mein Szenario hört nicht mehr auf
Es ist ratsam, sich die vom Szenario ausgeführten Befehle anzusehen. Oft stammt sie von einem Befehl, der nicht endet.

### Ich habe Instabilitäten oder Fehler 504
Überprüfen Sie, ob Ihr Dateisystem nicht beschädigt ist. In SSH lautet der Befehl : `` ''sudo dmesg | grep error`` ''.

### Ich habe den folgenden Fehler : SQLSTATE \ [HY000 \] \ [2002 \] Über Socket &#39;/var/run/mysqld/mysqld.sock kann keine Verbindung zum lokalen MySQL-Server hergestellt werden'
Dies liegt an MySQL, das gestoppt wurde. Es ist nicht normal, die häufigsten Fälle sind :

- Platzmangel im Dateisystem (kann überprüft werden, indem der Befehl "df -h" in SSH ausgeführt wird)
- Problem mit der Beschädigung von Dateien, das häufig nach einem unsicheren Herunterfahren von Jeedom (Stromausfall) auftritt)
- Speicherprobleme, das System verfügt nicht über Speicher und beendet den aufwendigsten Prozess (häufig die Datenbank)). Dies kann in der OS-Administration gesehen werden, dann sollten Sie einen Kill von "oom" sehen". Wenn dies der Fall ist, reduzieren Sie den Verbrauch von Jeedom, indem Sie Plugins deaktivieren.

Leider gibt es nicht viel Lösung, wenn es sich um den zweiten Fall handelt. Das Beste ist, ein Backup wiederherzustellen (standardmäßig in / var / www / html / backup verfügbar), Jeedom neu zu installieren und das Backup wiederherzustellen. Sie können auch sehen, warum MySQL nicht von einer SSH-Konsole booten möchte :
`` `{.bash}
sudo su -
MySQL Stop Service
mysqld --verbose
`` ''
Oder konsultieren Sie das Protokoll : /var/log/mysql/error.log

### Die Schaltflächen Herunterfahren / Neustart funktionieren nicht
Bei einer DIY-Installation ist das normal. In SSH müssen Sie den Befehl visudo ausführen und am Ende der Datei hinzufügen : www-data ALL = (ALL)
NOPASSWD: ALLE.

`` `{.bash}
sudo service apache2 neu starten
`` ''

### Ich sehe keine Plugins vom Markt
Dies ist der Fall, wenn Ihr Jeedom nicht mit dem Plugin kompatibel ist. Im Allgemeinen wird das Problem durch ein Jeedom-Update behoben.

### Ich habe Timeout-Ausrüstung, sehe sie aber nicht im Dashboard
Warnungen werden nach Priorität geordnet, von der am wenigsten wichtigen bis zur wichtigsten : Zeitüberschreitung, Batteriewarnung, Batteriegefahr, Warnmeldung, Gefahrenwarnung

### Mein Jeedom zeigt auch nach 1 Stunde permanent &quot;Start&quot; an ?
Wenn Sie in DIY und unter Debian 9 oder höher sind, überprüfen Sie, ob es kein Update von Apache und damit die Rückgabe von privateTmp gegeben hat (sichtbar durch Ausführen von `ls / tmp`) und prüfen Sie, ob dies der Fall ist ein privater \* Apache-Ordner). Wenn dies der Fall ist, ist dies erforderlich :
`` ''
mkdir /etc/systemd/system/apache2.service.d
echo &quot;[Service]&quot;&gt; /etc/systemd/system/apache2.service.d/privatetmp.conf
echo &quot;PrivateTmp = no&quot; &gt;&gt; /etc/systemd/system/apache2.service.d/privatetmp.conf
`` ''

### Ich habe ein zeitliches Problem mit meiner Geschichte
Versuchen Sie, den Chrome-Cache zu löschen. Die Anzeige der Historien wird relativ zur Browserzeit berechnet.

### Ich habe den Fehler "Netzwerkprobleme erkannt, Netzwerkneustart"
Jeedom kann das Gateway nicht finden oder nicht anpingen. Im Allgemeinen passiert es, wenn die ADSL-Box neu gestartet wird (insbesondere Liveboxen) und Jeedom nicht neu gestartet wurde oder schneller als die Box neu gestartet wurde. Aus Sicherheitsgründen teilt er Ihnen mit, dass er ein Problem gefunden hat, und startet den Netzwerkverbindungsprozess neu. Sie können diesen Mechanismus deaktivieren, indem Sie zur Jeedom-Konfiguration wechseln und die Netzwerkverwaltung von Jeedom deaktivieren.

### Ich erhalte die Meldung &quot;Fehler beim Sichern der Datenbank. Überprüfen Sie, ob mysqldump vorhanden ist."
Dies bedeutet, dass Jeedom die Datenbank nicht sichern kann, was auf ein Problem mit der Beschädigung der Datenbank und des Dateisystems hinweisen kann. Es gibt leider keinen Wunderbefehl zu korrigieren. Am besten starten Sie ein Backup und analysieren das Protokoll. In bekannten Fällen von Bedenken haben wir:

- Eine beschädigte Basistabelle => Es gibt einen schlechten Start, den wir sehen müssen, um zu versuchen, ihn zu reparieren. Wenn er nicht mit der letzten guten Sicherung beginnt (wenn Sie sich auf einer SD-Karte befinden, ist es der richtige Zeitpunkt, ihn zu ändern)
- Nicht genügend Speicherplatz im Dateisystem =&gt; Schauen Sie sich die Gesundheitsseite an, die Sie darüber informieren können

### Ich habe Fehler vom Typ &quot;Klasse &#39;eqLogic&#39; nicht gefunden&quot;, Dateien scheinen zu fehlen oder ich habe eine leere Seite
Es ist ein ziemlich schwerwiegender Fehler, der am einfachsten zu machen ist
`` ''
mkdir -p / root / tmp /
cd / root / tmp
wget https://github.com/jeedom/core/archive/master.zip
entpacke master.zip
cp -R / root / tmp / core-master / * / var / www / html
rm -rf / root / tmp / core-master
`` ''

### Ich habe den Fehler in Szenario_Ausführung MYSQL_ATTR_INIT_COMMAND
Bei der Verwaltung des Jeedom-Teils OS / DB muss dann in der Systemkonsole vorgenommen werden :
`` ''
Ja | sudo apt install -y php-mysql php-curl php-gd php-imap php-xml php-opcache php-seife php-xmlrpc php-common php-dev php-zip php-ssh2 php-mbstring php-ldap
`` ''

### Ich kann die Plugin-Abhängigkeiten nicht installieren. Ich habe einen Fehler des Typs : "E: dpkg wurde eingestellt. Il est nécessaire d'utiliser « sudo dpkg --configure -a » pour corriger le problème." ou "E: Lock / var / lib / dpkg / lock konnte nicht abgerufen werden"

Du musst :

- Jeedom neu starten
- Gehen Sie zur Verwaltung (Schaltfläche mit gekerbtem Rad oben rechts, dann Konfiguration in Version 3 oder Setup -> System -> Konfiguration in Version 4))
- Wechseln Sie zur Registerkarte OS / DB
- Starten Sie die Systemadministration
- Klicken Sie auf Dpkg konfigurieren
- Warten Sie 10 Minuten
- Starten Sie die Abhängigkeiten der blockierenden Plugins neu

### Ich habe diesen Fehler bei der Installation von Plugin-Abhängigkeiten : "von pip._internal import main"

Es ist notwendig in der Systemkonsole von Jeedom oder in ssh zu machen

`` ''``
sudo easy_install pip
sudo easy_install3 pip
`` ''``

Starten Sie dann die Abhängigkeiten neu


### Ab dem 4.2, ich kann iframe nicht mehr anzeigen

Kern 4.2 erhöht die Sicherheit von Jeedom erheblich. Wenn Sie wirklich (wissentlich) zu einer unsicheren Version Ihres Jeedoms zurückkehren müssen :
Gehe zu **Einstellungen -> System -> Konfiguration** dann in **Betriebssystem / DB**, Starten Sie die Systemverwaltungskonsole und klicken Sie auf **Apache nicht sicher**. Ein Neustart von Jeedom wird nach dieser Änderung empfohlen.

### Ab dem 4.2, einige Plugins funktionieren nicht mehr und in der Browserkonsole (F12-Taste) habe ich 403 Fehler

Dies liegt an der Sicherheit von Apache, die erfordert, dass Plugin-Entwickler die richtigen Dateien in den richtigen Verzeichnissen ablegen, um die Angriffsfläche von Jeedom einzuschränken. Diese Sicherheit erfolgt in der Datei .htaccess (überschrieben jedes Mal, wenn der Kern aktualisiert wird). Sie können eine Datei erstellen .htaccess_custom mit Ihren eigenen Regeln, die, falls vorhanden, anstelle der Datei verwendet werden .htaccess von Core.