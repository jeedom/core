# Dashboard
**Startseite → Dashboard**

<small>[Tastatur- und Maus-Shortcuts](shortcuts.md)</small>

Das Dashboard ist eine der Hauptseiten von Jeedom und zeigt einen Überblick über Ihr gesamtes Hausautomationssystem an.
Dieser Bericht (im Gegensatz zu Ansichten und Designs) wird automatisch von Jeedom erstellt und umfasst alle sichtbaren Objekte sowie deren Geräte.

{% include lightbox.html src="images/doc-dashboard-legends.png" data="Dashboard" title="Dashboard" imgstyle="width:450px;display: block;margin: 0 auto;" %}

- 1: Hauptmenü von Jeedom.
- 2: Zusammenfassung [Dokumentation zu den Zusammenfassungen.](https://doc.jeedom.com/concept/de_DE/summary).
- 3: Browser-Uhrzeit, Verknüpfung zur Zeitleiste.
- 4: Schaltfläche zum Aufrufen der Dokumentation zur aktuellen Seite.
- 5: Name Ihres Jeedom, Verknüpfung zur Konfiguration.
- 6: Bearbeitungsmodus (Kacheln neu anordnen / Größe ändern).
- 7: Nach Kategorien filtern.
- 8: Objekt: Symbol, Name und Zusammenfassung sowie die zugehörige Ausstattung.
- 9: Kachel eines Geräts.
- 10: Widget für eine Steuerung.

> **Tipp**
>
> Die Reihenfolge, in der die Objekte auf dem Dashboard angezeigt werden, entspricht der Reihenfolge unter **Analyse → Hausautomationsübersicht**. Auf dieser Seite können Sie die Reihenfolge per Drag & Drop ändern.

Damit ein Gerät auf dem Dashboard angezeigt wird, muss es:
- Aktiv sein.
- Sichtbar sein.
- Ein auf dem Dashboard sichtbares Objekt als übergeordnetes Objekt haben.

Wenn das Gerät zum ersten Mal auf dem Dashboard erscheint, versucht Jeedom, die Größe der Kachel so anzupassen, dass alle Befehle und deren Widgets angezeigt werden.
Um ein ausgewogenes Dashboard zu gewährleisten, können Sie über den Bleistift oben rechts in der Suchleiste in den Bearbeitungsmodus wechseln, um die Größe der Geräte-Kacheln anzupassen und/oder deren Reihenfolge zu ändern.

Wenn Sie mit der Maus über einen Befehl fahren, erscheint unten links auf der Kachel eine farbige Markierung:
- Blau für eine Info-Anfrage. Wenn diese protokolliert wurde, öffnet ein Klick darauf das Verlaufsfenster.
- Orange für eine Aktionsbefehlsschaltfläche. Ein Klick löst die Aktion aus.

Außerdem können Sie auf den Titel der Kachel (den Namen des Geräts) klicken, um direkt die Konfigurationsseite dieses Geräts zu öffnen.

> **Tipp**
>
> Sie können direkt zu einem einzelnen Objekt Ihres Hausautomationssystems navigieren, indem Sie im Menü **Startseite → Dashboard → Name des Objekts** auswählen.
> So werden nur die Geräte angezeigt, die Sie interessieren, und die Seite wird schneller geladen.

- Oben links befindet sich ein kleines Symbol, mit dem Sie beim Darüberfahren mit der Maus die Struktur der Objekte anzeigen können.
- Über ein Suchfeld können Sie ein Gerät anhand seines Namens, seiner Kategorie, seines Plugins, eines Tags usw. suchen.
- Mit dem Symbol rechts neben dem Suchfeld können Sie die angezeigten Geräte nach ihrer Kategorie filtern. Durch einen Klick in die Mitte können Sie schnell eine einzelne Kategorie auswählen.
- Ganz rechts befindet sich eine Schaltfläche, mit der Sie in den Bearbeitungsmodus wechseln können, um die Reihenfolge der Kacheln zu ändern (klicken und auf das Widget ziehen) oder ihre Größe anzupassen. Sie können auch die Reihenfolge der Befehle innerhalb einer Kachel neu anordnen.

- Wenn Sie auf eine Zusammenfassung eines Objekts klicken, filtern Sie die Anzeige so, dass nur die Geräte angezeigt werden, die diesem Objekt untergeordnet sind und die diese Zusammenfassung des Objekts betreffen.

- Durch einen Klick auf einen Befehl vom Typ „Information“ wird der Verlauf des Befehls angezeigt (sofern dieser protokolliert wurde).
- Durch Strg+Klick auf einen Befehl vom Typ „Information“ wird der Verlauf aller (protokollierten) Befehle dieser Kachel angezeigt.
- Durch einen Klick auf die Angabe *time* eines Befehls wird der Verlauf des Befehls angezeigt (sofern dieser protokolliert wurde).

## Bearbeitungsmodus

Im Bearbeitungsmodus (*der Bleistift ganz oben rechts*) können Sie die Größe der Kacheln und deren Anordnung auf dem Dashboard ändern.

Die Aktualisierungssymbole der Geräte werden durch ein Symbol ersetzt, über das Sie auf deren Konfiguration zugreifen können. Dieses Symbol öffnet ein Bearbeitungsfenster mit den Anzeigeeinstellungen für das Gerät und dessen Bedienelemente.

![Bearbeitungsmodus](../images/EditDashboardModal.gif)

Bei jedem Objekt befinden sich rechts neben dem Namen und der Zusammenfassung zwei Symbole, mit denen Sie die Höhe aller Kacheln des Objekts an die höchste oder die niedrigste anpassen können.

## Jeedom-Menüleiste

> **Tipp**
>
> - Klick auf die Uhr (Menüleiste): Öffnet die Zeitleiste.
> - Klicken Sie auf den Namen von Jeedom (Menüleiste): Öffnet „Einstellungen“ → „System“ → „Konfiguration“.
> - Klick auf das ? (Menüleiste): Öffnet die Hilfe zur aktuellen Seite.
> - Esc-Taste in einem Suchfeld: Leert das Feld und bricht die Suche ab.
