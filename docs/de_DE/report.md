# Rapport
**Analyse → Bericht**

Auf dieser Seite können Sie alle Berichte anzeigen, die von der Berichtsaktion generiert wurden (siehe Szenariodokumentation).

## Principe

Ein Bericht ist ein Screenshot der Jeedom-Schnittstelle zu einem Zeitpunkt t.

> **Notiz**
>
> Diese Erfassung ist so angepasst, dass die Menüleiste und andere unnötige Elemente für diese Art der Verwendung nicht berücksichtigt werden.

Sie können dies für Ansichten, Designs und Bedienfeldseiten tun....

Die Generierung wird aus einem Szenario mit dem Befehl report ausgelöst.
Sie können diesen Bericht mit einem Nachrichtenbefehl (E-Mail, Telegramm usw.) an Sie senden lassen).

## Utilisation

Die Verwendung ist sehr einfach. Wählen Sie links, wenn Sie sehen möchten :

- Berichte anzeigen.
- Entwurfsberichte.
- Plugin-Panel-Berichte.
- Geräteberichte (für eine Zusammenfassung der Batterie jedes Moduls).

Wählen Sie dann den Namen des betreffenden Berichts aus. Sie sehen dann alle Daten der verfügbaren Berichte.

> **Wichtig**
>
> Das automatische Löschen erfolgt standardmäßig für Berichte, die älter als 30 Tage sind. Sie können diese Verzögerung in der Konfiguration von Jeedom konfigurieren.

Sobald der Bericht ausgewählt ist, können Sie ihn anzeigen, herunterladen oder löschen.

Sie können auch alle Sicherungen eines bestimmten Berichts löschen

## FAQ

> Wenn Sie einen Detailfehler haben :
> *cutycapt: Fehler beim Laden von gemeinsam genutzten Bibliotheken: libEGL.so: freigegebene Objektdatei kann nicht geöffnet werden: Keine solche Datei oder Verzeichnis*
> In ssh oder unter Einstellungen → System → Konfiguration : OS / DB / Systemadministration tun :
> ``````sudo ln -s /usr/lib/aarch64-linux-gnu/libGLESv2.so.2 /usr/lib/aarch64-linux-gnu/libGLESv2.so``````
> ``````sudo ln -s /usr/lib/aarch64-linux-gnu/libEGL.so.1 /usr/lib/aarch64-linux-gnu/libEGL.so``````
