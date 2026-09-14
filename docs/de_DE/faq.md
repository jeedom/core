# Häufig gestellte Fragen
**Einstellungen → Version: FAQ**

### Ist für Jeedom ein Abonnement erforderlich?
Nein, Jeedom ist vollständig nutzbar, ohne dass ein Abonnement erforderlich ist. Es werden zwar Dienste für Backups oder Anrufe/SMS angeboten, diese sind jedoch rein optional.

### Verwendet Jeedom externe Server für den Betrieb?
Nein, Jeedom nutzt keine „Cloud“-Infrastruktur. Alles läuft lokal ab, und Sie benötigen unsere Server nicht, damit Ihre Anlage funktioniert. Lediglich Dienste wie der Market, die Online-Sicherung oder das Jeedom-DNS erfordern die Nutzung unserer Server.

### Gibt es eine spezielle App dafür?
Jeedom verfügt über eine mobile Version, die für die Nutzung auf Smartphones und Tablets optimiert ist. Außerdem gibt es eine native App für Android und iOS.

### Wie lauten die Anmeldedaten für die erstmalige Anmeldung?
Bei Ihrer ersten Anmeldung bei Jeedom (und auch danach, sofern Sie diese nicht geändert haben) lauten der Benutzername und das Passwort standardmäßig „admin/admin“. Es wird dringend empfohlen, diese Anmeldedaten bei der ersten Anmeldung aus Sicherheitsgründen zu ändern.

### Ich kann mich nicht mehr bei meinem Jeedom anmelden
Seit Jeedom 3.2 ist es aus offensichtlichen Sicherheitsgründen nicht mehr möglich, sich aus der Ferne mit „admin/admin“ anzumelden. Die Anmeldedaten „admin/admin“ funktionieren nur noch lokal. Achtung: Wenn Sie den DNS nutzen, werden Sie selbst lokal zwangsläufig als Fernzugriff identifiziert. Ein weiterer Punkt: Standardmäßig werden nur IP-Adressen im Bereich 192.168.*.* oder 127.0.0.1 als lokal erkannt. Dies lässt sich in der Jeedom-Verwaltung unter „Sicherheit“ und dann „Whitelist“ konfigurieren. Wenn Sie sich trotz alledem immer noch nicht anmelden können, müssen Sie das Verfahren zum Zurücksetzen des Passworts anwenden, siehe [hier](https://doc.jeedom.com/howto/de_DE/reset.password).

### Ich sehe nicht alle meine Geräte auf dem Dashboard
Häufig liegt dies daran, dass die Geräte einem Objekt zugeordnet sind, das kein Unterelement oder das Objekt selbst des ersten links im Baum ausgewählten Objekts ist (Sie können dies in Ihrem Profil konfigurieren).

### Gibt es in der Jeedom-Benutzeroberfläche Schnellzugriffe?
Ja, die Liste der Tastatur- und Maus-Tastenkombinationen lautet [hier](shortcuts.md).

### Kann man die Befehle für ein Gerät neu anordnen?
Ja, das ist möglich. Sie müssen lediglich die Befehle Ihres Objekts per Drag & Drop in dessen Konfiguration ziehen.

### Kann man das Design der Widgets bearbeiten?
Für jeden Befehl können Sie die Darstellung aus verschiedenen Widgets des Core auswählen oder unter „Extras“ → „Widgets“ eigene erstellen.

### Kann man dasselbe Gerät mehrmals in einem Entwurf platzieren?
Nein, das ist nicht möglich, aber Sie können dieses Gerät mithilfe des virtuellen Plugins duplizieren.

### Wie kann man einen fehlerhaften Eintrag im Verlauf ändern?
Klicken Sie einfach in einem historischen Verlauf der Bestellung auf den entsprechenden Punkt. Wenn Sie das Feld leer lassen, wird der Wert gelöscht.

### Wie lange dauert eine Datensicherung?
Es gibt keine Standarddauer; diese hängt vom System und der zu sichernden Datenmenge ab, kann jedoch mehr als 5 Minuten betragen – das ist normal.

### Wo befinden sich die Backups von Jeedom?
Sie befinden sich im Ordner /var/www/html/backup

### Kann man Jeedom über HTTPS betreiben?
Ja: Entweder haben Sie ein Power-Paket oder ein höheres Paket; in diesem Fall
Man muss nur das [Jeedom-DNS](https://doc.jeedom.com/howto/de_DE/mise_en_place_dns_jeedom). Entweder mit einem DNS, und Sie wissen, wie man ein gültiges Zertifikat einrichtet – in diesem Fall handelt es sich um eine Standardinstallation eines Zertifikats.

### Wie verbindet man sich über SSH?
Hier ist eine [Dokumentation](https://www.alsacreations.com/tuto/lire/612-Premiere-connexion-SSH.html), Abschnitt „Unter Windows: PuTTY“. Der „Hostname“ ist die IP-Adresse Ihres Jeedom, die Anmeldedaten lauten:

- Benutzername: „root“, Passwort: „Mjeedom96“
- Benutzername: „jeedom“, Passwort: „Mjeedom96“
- Oder was Sie bei der Installation eingebaut haben, wenn Sie selbst Hand anlegen

Bitte beachten Sie, dass beim Eingeben des Passworts auf dem Bildschirm nichts angezeigt wird – das ist normal.

### Wie lassen sich die Rechte neu regeln?
Führen Sie in SSH Folgendes aus:

``` {.bash}
sudo su -
chmod -R 775 /var/www/html
chown -R www-data:www-data /var/www/html
```

### Wie aktualisiert man Jeedom über SSH?
Führen Sie in SSH Folgendes aus:

``` {.bash}
sudo su -
php /var/www/html/install/update.php
chmod -R 775 /var/www/html
chown -R www-data:www-data /var/www/html
```

### Ist die Web-App mit Symbian kompatibel?
Für die Web-App ist ein Smartphone erforderlich, das HTML5 und CSS3 unterstützt. Sie ist daher leider nicht mit Symbian kompatibel.

### Auf welchen Plattformen läuft Jeedom?
Damit Jeedom funktioniert, ist eine Linux-Plattform mit Root-Rechten oder ein Docker-ähnliches System erforderlich. Auf einer reinen Android-Plattform läuft es daher nicht.

### Ich kann ein bestimmtes Plugin nicht aktualisieren: „Fehler beim Herunterladen der Datei. Bitte versuchen Sie es später erneut (Größe unter 100 Byte) ...“?
Das kann verschiedene Ursachen haben. Man muss:

- Stellen Sie sicher, dass Ihr Jeedom weiterhin mit dem Market verbunden ist (auf der Jeedom-Verwaltungsseite finden Sie im Bereich „Updates“ eine Testschaltfläche).
- Bitte überprüfen Sie, ob das Market-Konto das betreffende Plugin tatsächlich gekauft hat.
- Stellen Sie sicher, dass Sie genügend Speicherplatz auf Jeedom haben (dies wird auf der Statusseite angezeigt).
- Stellen Sie sicher, dass Ihre Jeedom-Version mit dem Plugin kompatibel ist.

### Ich habe eine leere Seite
Man muss sich per SSH bei Jeedom anmelden und das Selbstdiagnose-Skript starten:
``` {.bash}
sudo chmod +x /var/www/html/health.sh;sudo /var/www/html/health.sh
```
Sollte ein Problem auftreten, versucht das Skript, dieses zu beheben. Gelingt dies nicht, wird Ihnen dies angezeigt.

Sie können auch das Protokoll unter /var/www/html/log/http.error einsehen. Sehr oft gibt dieses den Fehler an.

### Ich habe ein Problem mit meiner Datenbank-ID
Diese müssen zurückgesetzt werden:

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

### Ich habe überall \{\{…​\}\}
Die häufigste Ursache ist die Verwendung eines Plugins in der Beta-Version und Jeedom in der stabilen Version oder umgekehrt. Um Details zum Fehler zu erhalten, muss man sich das Log „http.error“ (in /var/www/html/log) ansehen.

### Bei einem Befehl dreht sich ein Rad ununterbrochen
Auch hier liegt das oft an einem Plugin in der Beta-Phase, während Jeedom sich in der stabilen Version befindet. Um den Fehler anzuzeigen, drücken Sie F12 und wählen Sie dann „Konsole“.

### Ich habe keinen Zugriff mehr auf Jeedom, weder über die Weboberfläche noch über die Konsole per SSH
Dieser Fehler ist nicht auf Jeedom zurückzuführen, sondern auf ein Problem mit dem System.
Sollte das Problem nach einer Neuinstallation weiterhin bestehen, empfehlen wir, sich wegen eines Hardwarefehlers an den Kundendienst zu wenden. Hier ist die [Dokumentation](https://doc.jeedom.com/installation/de_DE/recovery) für den Smart

### Mein Szenario hört nicht mehr auf/nicht
Es empfiehlt sich, die vom Szenario ausgeführten Befehle zu überprüfen; oft liegt das Problem bei einem Befehl, der nicht abgeschlossen wird.

### Ich habe Verbindungsstörungen oder 504-Fehler
Überprüfen Sie, ob Ihr Dateisystem beschädigt ist. Über SSH lautet der Befehl: ```sudo dmesg | grep error```.

### Ich erhalte folgende Fehlermeldung: SQLSTATE\[HY000\] \[2002\] Es kann keine Verbindung zum lokalen MySQL-Server über den Socket '/var/run/mysqld/mysqld.sock' hergestellt werden.
Dies liegt daran, dass MySQL abgestürzt ist. Das ist nicht normal. Häufige Ursachen sind:

- Nicht genügend Speicherplatz im Dateisystem (kann mit dem Befehl „df -h“ über SSH überprüft werden)
- Problem mit beschädigten Dateien, was häufig nach einem nicht ordnungsgemäßen Herunterfahren von Jeedom (Stromausfall) auftritt
- Speicherprobleme: Dem System fehlt Speicherplatz, weshalb der Prozess mit dem höchsten Speicherverbrauch (oft die Datenbank) beendet wird. Dies lässt sich in der Systemverwaltung des Betriebssystems unter „dmesg“ erkennen – dort sollte ein „kill“ durch „oom“ angezeigt werden. Ist dies der Fall, muss der Speicherverbrauch von Jeedom durch Deaktivieren von Plugins reduziert werden.

Leider gibt es im zweiten Fall nicht viele Lösungen. Am besten ist es, ein Backup zu sichern (standardmäßig unter /var/www/html/backup verfügbar), Jeedom neu zu installieren und die Wiederherstellung des Backups durchzuführen. Sie können auch über eine SSH-Konsole überprüfen, warum MySQL nicht starten will:
``` {.bash}
sudo su -
service mysql stop
mysqld --verbose
```
Oder das Protokoll einsehen: /var/log/mysql/error.log

### Die Schaltflächen „Ausschalten“ und „Neustart“ funktionieren nicht
Bei einer DIY-Installation ist das normal. Unter SSH müssen Sie den Befehl „visudo“ ausführen und am Ende der Datei Folgendes hinzufügen: www-data ALL=(ALL)
NOPASSWD: ALL.

``` {.bash}
sudo service apache2 restart
```

### Ich kann bestimmte Plugins aus dem Market nicht finden
So etwas passiert, wenn Ihr Jeedom nicht mit dem Plugin kompatibel ist. In der Regel lässt sich das Problem durch ein Jeedom-Update beheben.

### Ein Gerät hat ein Timeout, wird aber nicht im Dashboard angezeigt
Die Warnmeldungen sind nach Priorität geordnet, von der geringsten bis zur höchsten: Timeout, Batterie-Warnung, Batterie-Gefahr, Warnmeldung, Gefahrenmeldung

### Mein Jeedom zeigt auch nach einer Stunde immer noch „Wird gestartet“ an?
Wenn Sie selbst installieren und Debian 9 oder höher verwenden, überprüfen Sie, ob es ein Apache-Update gab und somit die Wiederherstellung von „privateTmp“ (zu sehen durch den Befehl `ls /tmp` und prüfen, ob ein Ordner „private\*Apache“ vorhanden ist. Ist dies der Fall, muss Folgendes durchgeführt werden:
```
mkdir /etc/systemd/system/apache2.service.d
echo "[Service]" > /etc/systemd/system/apache2.service.d/privatetmp.conf
echo "PrivateTmp=no" >> /etc/systemd/system/apache2.service.d/privatetmp.conf
```

### Ich habe ein Problem mit der Uhrzeit in meinen Verlaufsdaten
Versuchen Sie, den Cache von Chrome zu leeren, da die Anzeige des Verlaufs auf der Uhrzeit des Browsers basiert.

### Ich erhalte die Fehlermeldung „Netzwerkproblem erkannt, Netzwerk wird neu gestartet“.
Jeedom kann das Gateway nicht finden oder es nicht anpingen. Dies tritt in der Regel auf, wenn sich die ADSL-Box neu startet (insbesondere bei Liveboxen) und Jeedom entweder nicht neu gestartet wurde oder schneller neu gestartet wurde als die Box. Aus Sicherheitsgründen meldet Jeedom daher, dass ein Problem aufgetreten ist, und startet den Netzwerkverbindungsprozess neu. Sie können diesen Mechanismus deaktivieren, indem Sie in den Jeedom-Einstellungen die Netzwerkverwaltung durch Jeedom deaktivieren.

### Ich erhalte die Meldung „Fehler beim Sichern der Datenbank. Überprüfen Sie, ob mysqldump vorhanden ist.“
Das bedeutet, dass Jeedom die Datenbank nicht sichern kann, was auf ein Problem mit einer beschädigten Datenbank oder einem beschädigten Dateisystem hindeuten könnte. Leider gibt es keinen Wundermittel-Befehl, um das Problem zu beheben. Am besten ist es, eine Sicherung zu starten und das entsprechende Protokoll zu analysieren. Zu den bekannten Problemfällen gehören:

- Eine Tabelle der Datenbank ist beschädigt => Das sieht nicht gut aus. Man muss versuchen, sie zu reparieren, und wenn das nicht funktioniert, vom letzten funktionierenden Backup ausgehen (wenn Sie eine SD-Karte verwenden, ist jetzt der richtige Zeitpunkt, diese zu wechseln).
- Nicht genügend Speicherplatz auf dem Dateisystem => Sehen Sie sich die Statusseite an, dort finden Sie möglicherweise entsprechende Hinweise

### Ich erhalte Fehlermeldungen wie „Class 'eqLogic' not found“, es scheinen Dateien zu fehlen oder ich erhalte eine leere Seite.
Das ist ein ziemlich schwerwiegender Fehler. Am einfachsten ist es, Folgendes zu tun:
```
mkdir -p /root/tmp/
cd /root/tmp
wget https://github.com/jeedom/core/archive/master.zip
unzip master.zip
cp -R /root/tmp/core-master/* /var/www/html
rm -rf /root/tmp/core-master
```

### Ich erhalte den Fehler „MYSQL_ATTR_INIT_COMMAND“ in „scenario_execution“
In der Jeedom-Verwaltung unter „OS/DB“ und anschließend in der Systemkonsole müssen Sie Folgendes tun:
```
yes | sudo apt install -y php-mysql php-curl php-gd php-imap php-xml php-opcache php-soap php-xmlrpc php-common php-dev php-zip php-ssh2 php-mbstring php-ldap
```

### Ich schaffe es nicht, die Abhängigkeiten eines Plugins zu installieren. Ich erhalte eine Fehlermeldung wie: „E: dpkg wurde unterbrochen. Verwenden Sie ‚sudo dpkg --configure -a‘, um das Problem zu beheben.“ oder „E: Could not get lock /var/lib/dpkg/lock“

Man braucht:

- Jeedom neu starten
- Rufen Sie die Verwaltung des Geräts auf (Zahnrad-Symbol oben rechts, dann „Konfiguration“ in Version 3 oder „Einstellungen“ -> „System“ -> „Konfiguration“ in Version 4)
- Gehen Sie zur Registerkarte „OS/DB“
- Systemverwaltung starten
- Klicken Sie auf „dpkg configure“
- 10 Minuten warten
- die Abhängigkeiten des Plugins, das den Prozess blockiert, neu starten

### Bei der Installation der Abhängigkeiten eines Plugins erhalte ich folgende Fehlermeldung: „from pip._internal import main“

In der Jeedom-Systemkonsole oder per SSH muss Folgendes ausgeführt werden:

````
sudo easy_install pip
sudo easy_install3 pip
````

Anschließend die Abhängigkeiten neu starten


### Seit Version 4.2 kann ich keine iframes mehr anzeigen

Der Core 4.2 erhöht die Sicherheit von Jeedom erheblich. Sollten Sie (in voller Kenntnis der Sachlage) wirklich auf eine unsichere Version Ihres Jeedom zurückgreifen müssen:
Gehen Sie zu **Einstellungen -> System -> Konfiguration** und dann zu **OS/DB**, starten Sie die Systemverwaltungskonsole und klicken Sie auf **Unsicheres Apache**. Nach dieser Änderung wird ein Neustart von Jeedom empfohlen.

### Seit Version 4.2 funktionieren einige Plugins nicht mehr, und in der Browserkonsole (Taste F12) werden mir 403-Fehler angezeigt.

Dies ist auf die Sicherheitsmaßnahmen für Apache zurückzuführen, die Plugin-Entwickler dazu verpflichten, die richtigen Dateien in die richtigen Verzeichnisse zu legen, um die Angriffsfläche von Jeedom zu verringern. Diese Sicherheitsmaßnahmen werden in der Datei .htaccess vorgenommen (die bei jedem Update des Core überschrieben wird). Sie können eine Datei .htaccess_custom mit Ihren eigenen Regeln erstellen, die, sofern vorhanden, anstelle der .htaccess-Datei des Core verwendet wird.
