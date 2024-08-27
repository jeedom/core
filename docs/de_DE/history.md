# Historique
**Analyse → Geschichte**

Wichtiger Teil in der Software : der Historisierungsteil, eine wahre Erinnerung daran. In Jeedom ist es möglich, jeden Informationstypbefehl (binär oder digital) zu protokollieren). So können Sie beispielsweise einen Temperaturverlauf, Verbräuche, Türöffnungen etc. protokollieren.

![Historisch](./images/history.gif)

### Prinzip der Historisierung

### Archivage

Die Datenarchivierung ermöglicht es Jeedom, die im Speicher gehaltene Datenmenge zu reduzieren. Dies ermöglicht es, nicht zu viel Platz zu beanspruchen und das System nicht zu verlangsamen. Wenn Sie alle Messungen beibehalten, werden umso mehr Punkte angezeigt, und daher kann die Zeit zum Rendern eines Diagramms erheblich verlängert werden. Wenn zu viele Punkte vorhanden sind, kann dies sogar zum Absturz der Diagrammanzeige führen.

Die Archivierung beginnt nachts und komprimiert die tagsüber wiederhergestellten Daten. Standardmäßig ruft Jeedom alle älteren Daten von 2 Stunden ab und erstellt 1-Stunden-Pakete daraus (entweder ein Durchschnitt, ein Minimum oder ein Maximum, abhängig von den Einstellungen). Hier haben wir also zwei Parameter, einen für die Paketgröße und einen anderen, um zu wissen, wann dies zu tun ist (standardmäßig sind dies 1-Stunden-Pakete mit Daten, die älter als 2 Stunden sind).

> **Tipp**
>
> Wenn Sie gut gefolgt sind, sollten Sie nur in den letzten 2 Stunden eine hohe Präzision haben. Wenn ich mich jedoch um 17 Uhr verbinde, habe ich eine Präzision für die letzten 17 Stunden. Wofür ? Um unnötigen Ressourcenverbrauch zu vermeiden, findet die Archivierungsaufgabe nur einmal am Tag abends statt.

> **Wichtig**
>
> Dieses Archivierungsprinzip gilt natürlich nur für digitale Bestellungen. Bei binären Bestellungen speichert Jeedom nur die Daten der Zustandsänderung.

### Anzeigen eines Diagramms

Es gibt verschiedene Möglichkeiten, auf den Verlauf zuzugreifen :

- Durch Klicken auf den gewünschten Befehl in einem Widget,
- Auf der Verlaufsseite können Sie verschiedene Kurven überlagern und Stile kombinieren (Fläche, Kurve, Balken)),
- Auf dem Handy, während Sie auf dem betreffenden Widget gedrückt bleiben,
- Durch Einfügen eines Diagrammbereichs in eine Ansicht (siehe unten),
- Durch Einfügen eines Graphen in ein Design.

Ab Core v4.2 ist es auch möglich, eine Kurve am unteren Rand der Kachel eines Geräts anzuzeigen.

## Historique

Wenn Sie ein Diagramm über die Verlaufsseite anzeigen, haben Sie über dem Diagramm Zugriff auf mehrere Anzeigeoptionen :

- **Zeit** : Der Anzeigezeitraum einschließlich historischer Daten zwischen diesen beiden Daten. Standardmäßig abhängig von der Einstellung *Standard Anzeigezeitraum der Grafiken* In *Einstellungen → System → Konfiguration / Ausstattung*.
- **Gruppe** : Bietet verschiedene Gruppierungsoptionen (Summe pro Stunde usw.).).
- **Anzeigetyp** : Anzeige in *Linie*, *Bereich*, Oder *Stange*. Option in der Bestellung gespeichert und über das Dashboard verwendet.
- **Variation** : Zeigt die Wertdifferenz zum vorherigen Punkt an. Option in der Bestellung gespeichert und über das Dashboard verwendet.
- **Treppe** : Zeigt die Kurve als Treppe oder kontinuierliche Anzeige an. Option in der Bestellung gespeichert und über das Dashboard verwendet.
- **Vergleichen** : Vergleichen Sie die Kurve zwischen verschiedenen Perioden.

> **Tipp**
>
> Um Handhabungsfehler zu vermeiden, sind diese in den Befehlen gespeicherten Optionen nur aktiv, wenn eine einzelne Kurve angezeigt wird.
> 
Im oberen Teil, wo die Kurven angezeigt werden, gibt es auch mehrere Optionen :

Auf der Linken:

- **Zoom** : Ein Shortcut-Bereich, mit dem Sie den horizontalen Zoom auf die gewünschte Dauer einstellen können, wenn die Daten geladen sind.

Auf der rechten Seite:

- **Sichtbare vertikale Achsen** : Ermöglicht das Ausblenden oder Anzeigen aller vertikalen Achsen.
- **Skalierung der vertikalen Achse** : Ermöglicht Ihnen, die Skalierung jeder vertikalen Achse unabhängig von den anderen zu aktivieren oder nicht.
- **Gruppierung der vertikalen Achsen nach Einheiten** : Ermöglicht die Gruppierung der Skalierung von Kurven und vertikalen Achsen nach ihrer Einheit. Alle Kurven mit der gleichen Einheit haben den gleichen Maßstab.
- **Deckkraft der Kurven unter der Maus** : Hiermit können Sie die Hervorhebung der Kurve deaktivieren, wenn ein Wert am Mauszeiger angezeigt wird. Zum Beispiel, wenn zwei Kurven nicht gleichzeitig ihre Werte haben.

Unter den Kurven können Sie auch das Kontextmenü jeder Legende verwenden, um eine Kurve zu isolieren, ihre Achse anzuzeigen / auszublenden, ihre Farbe zu ändern, ...

### Grafik zu Ansichten und Designs

Sie können die Diagramme auch in den Ansichten anzeigen (wir sehen hier die Konfigurationsoptionen und nicht, wie es geht, dafür müssen Sie zur Dokumentation der Ansichten oder Entwürfe in Funktion gehen). Hier sind die Optionen :

Sobald eine Daten aktiviert sind, können Sie auswählen :
- **Farbe** : Die Farbe der Kurve.
- **Art** : Der Diagrammtyp (Bereich, Linie oder Spalte)).
- **Leiter** : Da Sie mehrere Kurven (Daten) in ein Diagramm einfügen können, können Sie die Skalen (rechts oder links) unterscheiden).
- **Treppe** : Zeigt die Kurve als Treppe oder kontinuierliche Anzeige an.
- **Stapel** : Stapeln Sie die Werte der Kurven (siehe unten für das Ergebnis).
- **Variation** : Zeigt die Wertdifferenz zum vorherigen Punkt an.

### Option auf der Verlaufsseite

Auf der Verlaufsseite können Sie auf einige zusätzliche Optionen zugreifen

#### Berechnete Geschichte

Ermöglicht die Anzeige einer Kurve gemäß einer Berechnung mit mehreren Befehlen (Sie können so ziemlich alles tun, + - / \* absoluter Wert ... siehe PHP-Dokumentation für einige Funktionen). Beispielsweise :

`abs(*\ [Garten \] \ [Hygrometrie \] \ [Temperatur \]* - *\ [Wohnraum \] \ [Hygrometrie \] \ [Temperatur \]*)`

Sie haben auch Zugriff auf eine Verwaltung von Berechnungsformeln, die es Ihnen ermöglicht, diese zu speichern, um sie einfacher wieder anzuzeigen.

> **Tipp**
>
> Wenn Sie Berechnungen gespeichert haben, stehen diese links in . zur Verfügung **Meine Berechnungen**.

#### Bestellhistorie

Vor allen Daten, die angezeigt werden können, finden Sie zwei Symbole :

- **Mülleimer** : Ermöglicht das Löschen der aufgezeichneten Daten. Beim Klicken fragt Jeedom, ob die Daten vor einem bestimmten Datum oder alle Daten gelöscht werden sollen.
- **Pfeil** : Aktiviert den CSV-Export historischer Daten.

### Inkonsistente Wertentfernung

Manchmal haben Sie inkonsistente Werte in den Diagrammen. Dies ist häufig auf Bedenken hinsichtlich der Interpretation des Werts zurückzuführen. Sie können den Wert des betreffenden Punkts löschen oder ändern, indem Sie direkt in der Grafik darauf klicken. Darüber hinaus können Sie das zulässige Minimum und Maximum anpassen, um zukünftige Probleme zu vermeiden.


