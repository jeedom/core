Auf dieser Seite können Sie alle Berichte anzeigen, die durch die Berichtsaktion generiert wurden (siehe Szenariodokumentation)..

# Was ist ein Bericht? ?

Ein Bericht ist ein Screenshot der Jeedom-Benutzeroberfläche zu einem Zeitpunkt T (die Erfassung wird n angepasst, dass die Menüleiste und andere unnötige Elemente für diese Art der Verwendung nicht übernommen werden)..

Sie können dies für Ansichten, Design und Bedienfeldseite tun....

Es wird von einem Skript mit dem Befehl report ausgelöst. Sie können festlegen, dass dieser Bericht mit einem Nachrichtenbefehl (E-Mail, Telegramm usw.) an Sie gesendet wird.

# Verwendung

Die Verwendung ist sehr einfach, Sie wählen aus, wenn Sie sehen möchten :

-	Berichte anzeigen
-	Degins Berichte
-	Plugin-Panel-Berichte
- Geräteberichte (für eine Zusammenfassung der Batterie für jedes Modul)

Anschließend wählen Sie den Namen des betreffenden Berichts aus und sehen alle Daten der Berichte im Speicher

> **Wichtig**
>
> Das automatische Löschen erfolgt standardmäßig für Berichte, die länger als 30 Tage dauern (Sie können diesen Zeitraum in der Konfiguration von Jeedom konfigurieren).

Sobald der Bericht ausgewählt ist, können Sie ihn sehen, erneut herunterladen oder löschen.

Sie können auch alle Sicherungen eines bestimmten Berichts löschen

# Faq

> **Wenn Sie einen Detailfehler haben : Cutycapt: Fehler beim Laden von gemeinsam genutzten Bibliotheken: libEGL.n: freigegebene Objektdatei kann nicht geöffnet werden: Keine nlche Datei oder Verzeichnis**
>
> Dies ist in ssh oder in Administration -> Konfiguration -> OS / DB -> System -> Administration erforderlich :
>sudo ln -s /usr/lib/aarch64-linux-gnu/libGLESv2.n.2 /usr/lib/aarch64-linux-gnu/libGLESv2.n
>sudo ln -s /usr/lib/aarch64-linux-gnu/libEGL.n.1 /usr/lib/aarch64-linux-gnu/libEGL.n
