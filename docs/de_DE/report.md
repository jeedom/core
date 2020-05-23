# Bericht
**Analyse → Bericht**

).

## Prinzip

Ein Bericht ist ein Screenshot der Jeedom-Schnittstelle zu einem Zeitpunkt t.

> **Notiz**
>
> Diese Erfassung ist so angepasst, dass die Menüleiste und andere unnötige Elemente für diese Art der Verwendung nicht berücksichtigt werden.

Sie können dies für Ansichten, Designs und Bedienfeldseiten tun....

Die Generierung wird aus einem Szenario mit dem Befehl report ausgelöst.
).

## Verwendung

Die Verwendung ist sehr einfach. Wählen Sie links, wenn Sie sehen möchten :

- Berichte anzeigen.
- Entwurfsberichte.
- Plugin-Panel-Berichte.
- ).

Wählen Sie dann den Namen des betreffenden Berichts aus. Sie sehen dann alle Daten der verfügbaren Berichte.

> **Wichtig**
>
> Das automatische Löschen erfolgt standardmäßig für Berichte, die älter als 30 Tage sind. Sie können diese Verzögerung in der Konfiguration von Jeedom konfigurieren.

Sobald der Bericht ausgewählt ist, können Sie ihn anzeigen, herunterladen oder löschen.

Sie können auch alle Sicherungen eines bestimmten Berichts löschen

## Faq

> Wenn Sie einen Detailfehler haben :
> *Cutycapt: Fehler beim Laden von gemeinsam genutzten Bibliotheken: libEGL.so: freigegebene Objektdatei kann nicht geöffnet werden: Keine solche Datei oder Verzeichnis*
> In ssh oder unter Einstellungen → System → Konfiguration : OS / DB / Systemadministration tun :
> ``````sudo ln -s /usr/lib/aarch64-linux-gnu/libGLESv2.so.2 /usr/lib/aarch64-linux-gnu/libGLESv2.so``````
> ``````sudo ln -s /usr/lib/aarch64-linux-gnu/libEGL.so.1 /usr/lib/aarch64-linux-gnu/libEGL.so``````
