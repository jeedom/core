# Ansichten
**Startseite → Ansicht**

Mit „Ansichten“ lassen sich individuelle Darstellungen erstellen.
Es ist zwar nicht so leistungsstark wie die Designs, ermöglicht es aber, in wenigen Minuten eine individuellere Ansicht als das Dashboard zu erstellen, mit verschiedenen Objekten, Grafiken oder Steuerelementen.

{% include lightbox.html src="../images/doc-view_01.jpg" data="View" title="View" imgstyle="width:450px;display: block;margin: 0 auto;" %}

> **Tipp**
>
> Sie können in Ihrem Profil die Standardansicht auswählen, wenn Sie auf das Menü „Ansichten“ klicken.

## Prinzip

Man kann sowohl Geräte-Kacheln, Grafiken (die aus mehreren Daten bestehen können) als auch Tabellenbereiche (die die Steuerungs-Widgets enthalten) einfügen.

In einer Ansicht findet man:

- Eine Schaltfläche oben links zum Ein- oder Ausblenden der Liste der Ansichten sowie eine Schaltfläche zum Hinzufügen einer neuen Ansicht.
- Mit dem Stift auf der rechten Seite können Sie die Reihenfolge und Größe der Geräte bearbeiten, genau wie im Dashboard.
- Eine Schaltfläche „*Vollständige Bearbeitung*“, mit der die Bereiche und Elemente der Ansicht bearbeitet werden können.

> **Tipp**
>
> Sie können diese Option in Ihrem Profil ändern, damit die Liste der Ansichten standardmäßig angezeigt wird.

## Ansicht hinzufügen/bearbeiten

Das Prinzip ist recht einfach: Eine Ansicht besteht aus Bereichen. Jeder Bereich ist vom Typ *Grafik*, *Widget* oder *Tabelle*. Je nach Typ können Sie Grafiken, Geräte oder Steuerelemente hinzufügen.

- Auf der linken Seite der Seite finden Sie die Liste der Ansichten sowie eine Schaltfläche zum Erstellen.
- Über eine Schaltfläche oben rechts können Sie die aktuelle Ansicht (Konfiguration) bearbeiten.
- Eine Schaltfläche zum Hinzufügen einer Zone. Sie werden dann aufgefordert, den Namen und den Typ der Zone anzugeben.
- Eine Schaltfläche „Ergebnis anzeigen“, mit der Sie den Vollbearbeitungsmodus verlassen und diese Ansicht anzeigen können.
- Eine Schaltfläche zum Speichern dieser Ansicht.
- Eine Schaltfläche zum Löschen dieser Ansicht.

> **Tipp**
>
> Die Reihenfolge der Bereiche lässt sich per Drag & Drop ändern.

In jedem Bereich stehen Ihnen die folgenden allgemeinen Optionen zur Verfügung:

- **Breite**: Legt die Breite des Bereichs fest (nur im Desktop-Modus). 1 entspricht einer Breite von 1/12 des Browsers, 12 der gesamten Breite.
- Eine Schaltfläche, mit der je nach Zonentyp (siehe unten) ein Element zu dieser Zone hinzugefügt werden kann.
- **Bearbeiten**: Ermöglicht es, den Namen des Bereichs zu ändern.
- **Löschen**: Ermöglicht das Löschen des Bereichs.

### Ausstattungsart

In einem Bereich vom Typ „Geräte“ können Geräte hinzugefügt werden:

- **Gerät hinzufügen**: Hiermit können Sie Geräte hinzufügen oder bearbeiten, die in diesem Bereich angezeigt werden sollen.

> **Tipp**
>
> Sie können ein Gerät direkt löschen, indem Sie auf den Papierkorb links davon klicken.

> **Tipp**
>
> Die Reihenfolge der Kacheln im Bereich kann per Drag & Drop geändert werden.


### Grafikbereich

Über einen Grafikbereich können Sie Ihrer Ansicht Diagramme hinzufügen. Dieser verfügt über folgende Optionen:

- **Zeitraum**: Hier können Sie den Zeitraum auswählen, für den die Diagramme angezeigt werden sollen (30 Min., 1 Tag, 1 Woche, 1 Monat, 1 Jahr oder „Alle“).
- **Kurve hinzufügen**: Ermöglicht das Hinzufügen bzw. Bearbeiten von Diagrammen.

Wenn Sie auf die Schaltfläche **Kurve hinzufügen** klicken, zeigt Jeedom die Liste der protokollierten Befehle an, aus der Sie den gewünschten Befehl auswählen können. Anschließend stehen Ihnen folgende Optionen zur Verfügung:

- **Papierkorb**: Löscht den Befehl aus der Grafik.
- **Name**: Name des zu zeichnenden Befehls.
- **Farbe**: Farbe der Kurve.
- **Typ**: Art der Kurve.
- **Gruppierung**: Ermöglicht die Gruppierung der Daten (z. B. Höchstwert pro Tag).
- **Skala**: Skala (rechts oder links) der Kurve.
- **Treppe**: Zeigt die Treppenkurve an.
- **Stapeln**: Stapelt die Kurve mit den anderen Kurven desselben Typs.
- **Veränderung**: Zeichnet nur die Veränderungen gegenüber dem vorherigen Wert.

{% include lightbox.html src="../images/doc-view_02.jpg" data="View" title="Pie Graph" imgstyle="width:450px;display: block;margin: 0 auto;" %}

> **Tipp**
>
> Die Reihenfolge der Diagramme im Bereich kann per Drag & Drop geändert werden.

### Tabellenfeld

Hier finden Sie die Schaltflächen:

- **Spalte hinzufügen**: Ermöglicht das Hinzufügen einer Spalte zur Tabelle.
- **Zeile hinzufügen**: Ermöglicht das Hinzufügen einer Zeile zur Tabelle.

> **Hinweis**
>
> Die Zeilen lassen sich per Drag & Drop neu anordnen, die Spalten jedoch nicht.

Sobald Sie Ihre Zeilen/Spalten hinzugefügt haben, können Sie Informationen in die Felder eingeben:

- Ein Text.
- HTML-Code (JavaScript ist möglich, wird jedoch dringend abgeraten).
- Das Widget eines Befehls: Über die Schaltfläche rechts können Sie den anzuzeigenden Befehl auswählen.
