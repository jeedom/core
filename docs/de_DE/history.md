# Geschichte
**Analyse → Verlauf**

Ein wichtiger Bestandteil einer Software ist die Protokollierung, die das eigentliche Gedächtnis der Software darstellt. In Jeedom ist es möglich, jeden Befehl vom Typ „Information“ (binär oder numerisch) zu protokollieren. So können Sie beispielsweise eine Temperaturkurve, den Verbrauch oder das Öffnen einer Tür usw. protokollieren.

![Geschichte](../images/history.gif)

### Prinzip der Protokollierung

### Archivierung

Durch die Datenarchivierung kann Jeedom die Menge der im Speicher gehaltenen Daten reduzieren. Dadurch wird verhindert, dass zu viel Speicherplatz belegt wird und das System verlangsamt wird. Wenn Sie nämlich alle Messwerte speichern, müssen entsprechend mehr Datenpunkte angezeigt werden, was die Ladezeit der Grafik erheblich verlängern kann. Bei einer zu großen Anzahl von Datenpunkten kann dies sogar dazu führen, dass die Anzeige des Diagramms abstürzt.

Die Archivierung ist ein Vorgang, der nachts gestartet wird und die tagsüber erfassten Daten komprimiert. Standardmäßig erfasst Jeedom alle Daten, die älter als 2 Stunden sind, und fasst sie zu 1-Stunden-Paketen zusammen (je nach Einstellung entweder als Durchschnittswert, Mindestwert oder Höchstwert). Es gibt also zwei Parameter: einen für die Größe der Pakete und einen weiteren, der festlegt, ab wann diese erstellt werden sollen (zur Erinnerung: Standardmäßig handelt es sich um 1-Stunden-Pakete mit Daten, die älter als 2 Stunden sind).

> **Tipp**
>
> Wenn Sie alles richtig verstanden haben, sollten Sie nur für die letzten zwei Stunden eine hohe Genauigkeit haben. Wenn ich mich jedoch um 17 Uhr einlogge, habe ich eine Genauigkeit für die letzten 17 Stunden. Warum? Um unnötigen Ressourcenverbrauch zu vermeiden, wird der Archivierungsvorgang tatsächlich nur einmal täglich, und zwar abends, durchgeführt.

> **Wichtig**
>
> Natürlich gilt dieses Archivierungsprinzip nur für digitale Befehle. Bei binären Befehlen speichert Jeedom lediglich die Zeitpunkte der Zustandsänderungen.

### Anzeige eines Diagramms

Es gibt mehrere Möglichkeiten, auf den Verlauf zuzugreifen:

- Wenn Sie in einem Widget auf die gewünschte Schaltfläche klicken,
- Wenn Sie die Seite „Verlauf“ aufrufen, können Sie verschiedene Kurven übereinanderlegen und die Darstellungsarten (Fläche, Kurve, Balken) kombinieren,
- Auf dem Smartphone durch langes Drücken auf das betreffende Widget,
- Wenn man ein Grafikfeld in eine Ansicht einfügt (siehe unten),
- Durch Einfügen eines Diagramms in ein Design.

Seit Core v4.2 ist es auch möglich, eine Kurve im Hintergrund einer Gerätekachel anzuzeigen.

## Geschichte

Wenn Sie über die Verlaufsseite ein Diagramm anzeigen, stehen Ihnen oberhalb des Diagramms verschiedene Anzeigeoptionen zur Verfügung:

- **Zeitraum**: Der Anzeigzeitraum, der die historischen Daten zwischen diesen beiden Datumsangaben umfasst. Standardmäßig richtet sich dieser nach der Einstellung *Standardzeitraum für die Grafikanzeige* unter *Einstellungen → System → Konfiguration / Geräte*.
- **Gruppierung**: Bietet verschiedene Gruppierungsoptionen an (Stundensumme usw.).
- **Anzeigetyp**: Anzeige als *Linie*, *Fläche* oder *Balken*. Die Option wird im Steuerelement gespeichert und über das Dashboard verwendet.
- **Abweichung**: Zeigt die Wertdifferenz zum vorherigen Messpunkt an. Diese Option wird auf dem Steuergerät gespeichert und über das Dashboard genutzt.
- **Treppenförmig**: Ermöglicht die Darstellung der Kurve in Form einer Treppe oder als durchgehende Anzeige. Diese Option wird auf dem Bedienelement gespeichert und über das Dashboard aufgerufen.
- **Vergleichen**: Ermöglicht den Vergleich der Kurve zwischen verschiedenen Zeiträumen.

> **Tipp**
>
> Um Bedienungsfehler zu vermeiden, sind diese in den Befehlen gespeicherten Optionen nur aktiv, wenn eine einzige Kurve angezeigt wird.
>
Im oberen Bereich, in dem die Kurven angezeigt werden, gibt es ebenfalls mehrere Optionen:

Links:

- **Zoom**: Ein Bereich mit Schaltflächen, mit denen der horizontale Zoom auf die gewünschte Dauer eingestellt werden kann, sofern die Daten geladen sind.

Rechts:

- **Vertikale Achsen anzeigen**: Hiermit können Sie alle vertikalen Achsen ausblenden oder anzeigen.
- **Skalierung der vertikalen Achsen**: Hiermit können Sie festlegen, ob jede vertikale Achse unabhängig von den anderen skaliert werden soll oder nicht.
- **Gruppierung der vertikalen Achsen nach Einheiten**: Ermöglicht die Gruppierung der Skalierung der Kurven und vertikalen Achsen nach ihren Einheiten. Alle Kurven mit derselben Einheit erhalten dieselbe Skalierung.
- **Deckkraft der Kurven unter dem Mauszeiger**: Hiermit können Sie die Hervorhebung der Kurve deaktivieren, wenn ein Wert am Mauszeiger angezeigt wird. Zum Beispiel, wenn zwei Kurven nicht zu denselben Zeitpunkten Werte aufweisen.

Unter den Kurven können Sie auch das Kontextmenü der jeweiligen Legende verwenden, um eine Kurve hervorzuheben, ihre Achse ein- oder auszublenden, ihre Farbe zu ändern usw.

### Grafik zu Ansichten und Designs

Sie können die Diagramme auch in den Ansichten anzeigen (wir befassen uns hier mit den Konfigurationsoptionen und nicht mit der Vorgehensweise; dazu müssen Sie die Dokumentation zu den jeweiligen Ansichten oder Designs konsultieren). Hier sind die Optionen:

Sobald eine Funktion aktiviert ist, haben Sie folgende Möglichkeiten:
- **Farbe**: Die Farbe der Kurve.
- **Typ**: Der Diagrammtyp (Flächen-, Linien- oder Säulendiagramm).
- **Skala**: Da Sie mehrere Kurven (Daten) in ein und demselben Diagramm darstellen können, ist es möglich, zwischen der rechten und der linken Skala zu unterscheiden.
- **Treppenförmig**: Ermöglicht die Darstellung der Kurve in Form einer Treppe oder als durchgehende Anzeige.
- **Stapeln**: Ermöglicht das Stapeln der Kurvenwerte (das Ergebnis siehe unten).
- **Abweichung**: Zeigt die Wertdifferenz zum vorherigen Messpunkt an.

### Option auf der Seite „Verlauf“

Über die Seite „Verlauf“ haben Sie Zugriff auf einige zusätzliche Optionen

#### Berechnete Historie

Ermöglicht die Darstellung einer Kurve basierend auf einer Berechnung über mehrere Befehle hinweg (Sie können so gut wie alles tun: +, -, /, *, Absolutwert … siehe PHP-Dokumentation für bestimmte Funktionen). Zum Beispiel:

`abs(*\[Jardin\]\[Hygrometrie\]\[Température\]* - *\[Espace de vie\]\[Hygrométrie\]\[Température\]*)`

Außerdem haben Sie Zugriff auf eine Verwaltung von Berechnungsformeln, mit der Sie diese speichern können, um sie später leichter wieder aufzurufen.

> **Tipp**
>
> Wenn Sie Berechnungen gespeichert haben, finden Sie diese links unter **Meine Berechnungen**.

#### Bestellhistorie

- Vor jeder anzeigbaren Datenzeile befindet sich ein **Papierkorb**-Symbol, mit dem Sie die gespeicherten Daten löschen können; wenn Sie darauf klicken, fragt Jeedom, ob Daten vor einem bestimmten Datum oder alle Daten gelöscht werden sollen.
- Unter **Konfiguration** finden Sie rechts neben jedem Datensatz ein **Pfeil**-Symbol, über das Sie einen CSV-Export der historischen Daten erstellen können.

### Entfernung inkonsistenter Werte

Manchmal kann es vorkommen, dass Sie inkonsistente Werte in den Diagrammen sehen. Dies ist oft auf ein Problem bei der Interpretation des Werts zurückzuführen. Sie können den Wert des betreffenden Datenpunkts löschen oder ändern, indem Sie direkt im Diagramm darauf klicken; außerdem können Sie den zulässigen Minimal- und Maximalwert festlegen, um zukünftige Probleme zu vermeiden.


