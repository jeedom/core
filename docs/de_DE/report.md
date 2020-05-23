Auf dieser Seite können Sie alle Berichte anzeigen, die von der Berichtsaktion generiert wurden (siehe Szenariodokumentation).

# Was ist ein Bericht? ?

Ein Bericht ist ein Screenshot der Jeedom-Oberfläche zu einem Zeitpunkt T (die Erfassung wird so angepasst, dass die Menüleiste und andere unnötige Elemente für diese Art der Verwendung nicht berücksichtigt werden).

Sie können dies für Ansichten, Design und Bedienfeldseite tun....

Es wird von einem Skript mit dem Befehl report ausgelöst. Sie können festlegen, dass dieser Bericht mit einem Nachrichtenbefehl (E-Mail, Telegramm) an Sie gesendet wird....)

# Utilisation

Die Verwendung ist sehr einfach, Sie wählen aus, wenn Sie sehen möchten :

-	Berichte anzeigen
-	Degins Berichte
-	Plugin-Panel-Berichte
- Geräteberichte (für eine Zusammenfassung der Batterie jedes Moduls)

Anschließend wählen Sie den Namen des betreffenden Berichts aus und sehen alle Daten der Berichte im Speicher

> **Wichtig**
>
> Das automatische Löschen erfolgt standardmäßig für Berichte, die länger als 30 Tage dauern (Sie können diesen Zeitraum in der Konfiguration von Jeedom konfigurieren)

Sobald der Bericht ausgewählt ist, können Sie ihn sehen, erneut herunterladen oder löschen.

Sie können auch alle Sicherungen eines bestimmten Berichts löschen

# FAQ

> **Wenn Sie einen Detailfehler haben : cutycapt: Fehler beim Laden von gemeinsam genutzten Bibliotheken: libEGL.so: freigegebene Objektdatei kann nicht geöffnet werden: Keine solche Datei oder Verzeichnis**
>
> Dies ist in ssh oder in Administration -> Konfiguration -> OS / DB -> System -> Administration erforderlich :
>sudo ln -s /usr/lib/aarch64-linux-gnu/libGLESv2.so.2 /usr/lib/aarch64-linux-gnu/libGLESv2.so
>sudo ln -s /usr/lib/aarch64-linux-gnu/libEGL.so.1 /usr/lib/aarch64-linux-gnu/libEGL.so
