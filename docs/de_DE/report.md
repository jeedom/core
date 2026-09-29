# Bericht
**Analyse → Bericht**

Auf dieser Seite können Sie alle Berichte einsehen, die durch die Aktion „report“ erstellt wurden (siehe Dokumentation zu den Szenarien).

## Prinzip

Ein Bericht ist ein Screenshot der Jeedom-Benutzeroberfläche zu einem bestimmten Zeitpunkt.

> **Hinweis**
>
> Dieser Screenshot ist so angepasst, dass die Menüleiste und andere für diese Art der Nutzung unnötige Elemente nicht mit aufgenommen werden.

Sie können dies in Ansichten, Designs, Dashboard-Seiten usw. tun....

Die Generierung wird über ein Szenario mit dem Befehl „report“ ausgelöst.
Sie können wählen, ob Sie diesen Bericht per Nachricht (E-Mail, Telegramm usw.) erhalten möchten.

## Anwendung

Die Bedienung ist ganz einfach. Wählen Sie links aus, was Sie sehen möchten:

- Die Berichte der Ansichten.
- Berichte zu den Designs.
- Berichte der Plugin-Panels.
- Geräteberichte (um einen Überblick über den Status der Batterie jedes Moduls zu erhalten).

Wählen Sie anschließend den Namen des gewünschten Berichts aus. Daraufhin werden Ihnen alle verfügbaren Berichtsdaten angezeigt.

> **Wichtig**
>
> Berichte, die älter als 30 Tage sind, werden standardmäßig automatisch gelöscht. Sie können diesen Zeitraum in den Jeedom-Einstellungen anpassen.

Sobald Sie den Bericht ausgewählt haben, können Sie ihn anzeigen, herunterladen oder löschen.

Sie können auch alle Sicherungen eines bestimmten Berichts löschen

## Häufig gestellte Fragen

> Wenn Sie eine Fehlermeldung vom Typ „Details“ erhalten:
> *cutycapt: Fehler beim Laden von gemeinsam genutzten Bibliotheken: libEGL.so: Die gemeinsam genutzte Objektdatei kann nicht geöffnet werden: Keine solche Datei oder kein solches Verzeichnis*
> Führen Sie folgende Schritte per SSH oder unter „Einstellungen“ → „System“ → „Konfiguration“: OS/DB / Systemverwaltung durch:
> ```sudo ln -s /usr/lib/aarch64-linux-gnu/libGLESv2.so.2 /usr/lib/aarch64-linux-gnu/libGLESv2.so```
> ```sudo ln -s /usr/lib/aarch64-linux-gnu/libEGL.so.1 /usr/lib/aarch64-linux-gnu/libEGL.so```
