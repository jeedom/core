# Historique
**Analyse → Geschichte**

Wichtiger Teil in der Software : der Historisierungsteil, eine wahre Erinnerung daran. In Jeedom ist es möglich, jeden Informationstypbefehl (binär oder digital) zu protokollieren). Auf diese Weise können Sie beispielsweise eine Temperatur-, Verbrauchs- oder Türöffnungskurve usw. protokollieren

### Principe

Hier wird das Prinzip der Historisierung von Jeedom beschrieben. Sie müssen dies nur verstehen, wenn Sie Probleme mit der Historisierung haben oder die Historisierungseinstellungen ändern möchten. Die Standardeinstellungen sind in den meisten Fällen in Ordnung.

### Archivage

Durch die Datenarchivierung kann Jeedom die im Speicher gespeicherte Datenmenge reduzieren. Dies ermöglicht es, nicht zu viel Platz zu beanspruchen und das System nicht zu verlangsamen. Wenn Sie alle Messungen beibehalten, werden umso mehr Punkte angezeigt, und daher kann die Zeit zum Rendern eines Diagramms erheblich verlängert werden. Wenn zu viele Punkte vorhanden sind, kann dies sogar zum Absturz der Diagrammanzeige führen.

Die Archivierung beginnt nachts und komprimiert die tagsüber wiederhergestellten Daten. Standardmäßig ruft Jeedom alle älteren Daten von 2 Stunden ab und erstellt 1-Stunden-Pakete daraus (entweder ein Durchschnitt, ein Minimum oder ein Maximum, abhängig von den Einstellungen). Hier haben wir also zwei Parameter, einen für die Paketgröße und einen anderen, um zu wissen, wann dies zu tun ist (standardmäßig sind dies 1-Stunden-Pakete mit Daten, die älter als 2 Stunden sind).

> **Spitze**
>
> Wenn Sie gut gefolgt sind, sollten Sie nur in den letzten 2 Stunden eine hohe Präzision haben. Wenn ich mich jedoch um 17 Uhr verbinde, habe ich eine Präzision für die letzten 17 Stunden. Warum ? Um unnötigen Ressourcenverbrauch zu vermeiden, findet die Archivierungsaufgabe nur einmal am Tag abends statt.

> **Wichtig**
>
> Dieses Archivierungsprinzip gilt natürlich nur für digitale Befehle. Bei Befehlen vom Typ Binär behält Jeedom nur die Daten der Zustandsänderung bei.

### Anzeigen eines Diagramms

Es gibt verschiedene Möglichkeiten, auf den Verlauf zuzugreifen :

- Durch Klicken auf den gewünschten Befehl in einem Widget,
- Gehen Sie zur Verlaufsseite, auf der Sie verschiedene Kurven überlagern und Stile (Fläche, Kurve, Balken) kombinieren können),
- Auf dem Handy, während Sie auf dem betreffenden Widget gedrückt bleiben,
- Indem Sie einen Grafikbereich in eine Ansicht einfügen (siehe unten)).

## Registerkarte &quot;Verlauf&quot;

Wenn Sie auf der Verlaufsseite ein Diagramm anzeigen, haben Sie Zugriff auf mehrere Anzeigeoptionen :

Wir finden oben rechts den Anzeigezeitraum (hier in der letzten Woche, weil ich standardmäßig nur eine Woche haben möchte - siehe 2 Absätze oben), dann kommen die Parameter der Kurve (diese Parameter werden beibehalten von einem Display zum anderen, sodass Sie sie nur einmal konfigurieren müssen).

- **Treppe** : Zeigt die Kurve als Treppe oder kontinuierliche Anzeige an.
- **Veränderung** : Zeigt die Wertdifferenz zum vorherigen Punkt an.
- **Linie** : Zeigt das Diagramm als Linien an.
- **Bereich** : Zeigt das Diagramm als Fläche an.
- **Spalte**\.* : Zeigt das Diagramm als Balken an.

> **Spitze**
>
> Wenn Sie mehrere Kurven gleichzeitig anzeigen:
> - Klicken Sie auf eine Legende unter dem Diagramm, um diese Kurve anzuzeigen / auszublenden.
> - Strg Klicken Sie auf eine Legende, um nur diese anzuzeigen.
> - Alt Klicken Sie auf eine Legende, um alle anzuzeigen.


### Grafik zu Ansichten und Designs

Sie können die Diagramme auch in den Ansichten anzeigen (wir sehen hier die Konfigurationsoptionen und nicht, wie es geht, dafür müssen Sie zur Dokumentation der Ansichten oder Entwürfe in Funktion gehen). Hier sind die Optionen :

Sobald eine Daten aktiviert sind, können Sie auswählen :
- **Farbe** : Die Farbe der Kurve.
- **Typ** : Der Diagrammtyp (Bereich, Linie oder Spalte)).
- **Maßstab** : Da Sie mehrere Kurven (Daten) in ein Diagramm einfügen können, können Sie die Skalen (rechts oder links) unterscheiden).
- **Treppe** : Zeigt die Kurve als Treppe oder kontinuierliche Anzeige an.
- **Stapel** : Stapeln Sie die Werte der Kurven (siehe unten für das Ergebnis).
- **Veränderung** : Zeigt die Wertdifferenz zum vorherigen Punkt an.

### Option auf der Verlaufsseite

Auf der Verlaufsseite können Sie auf einige zusätzliche Optionen zugreifen

#### Berechnete Geschichte

Ermöglicht die Anzeige einer Kurve gemäß einer Berechnung mit mehreren Befehlen (Sie können fast alles tun, + - / \* absoluter Wert… für bestimmte Funktionen siehe PHP-Dokumentation).
Ex :
abs(*\ [Garten \] \ [Hygrometrie \] \ [Temperatur \]* - *\ [Wohnraum \] \ [Hygrometrie \] \ [Temperatur \]*)

Sie haben auch Zugriff auf eine Verwaltung von Berechnungsformeln, mit der Sie diese zur einfacheren erneuten Anzeige speichern können.

> **Spitze**
>
> Klicken Sie einfach auf den Namen des Objekts, um es zu entfalten, und rufen Sie die historischen Befehle auf, die angezeigt werden können.

#### Bestellhistorie

Vor allen Daten, die angezeigt werden können, finden Sie zwei Symbole :

- **Mülleimer** : Ermöglicht das Löschen der aufgezeichneten Daten. Beim Klicken fragt Jeedom, ob die Daten vor einem bestimmten Datum oder alle Daten gelöscht werden sollen.
- **Pfeil** : Ermöglicht einen CSV-Export historischer Daten.

### Inkonsistente Wertentfernung

Manchmal haben Sie inkonsistente Werte in den Diagrammen. Dies ist häufig auf Bedenken hinsichtlich der Interpretation des Werts zurückzuführen. Sie können den Wert des betreffenden Punkts löschen oder ändern, indem Sie direkt in der Grafik darauf klicken. Darüber hinaus können Sie das zulässige Minimum und Maximum anpassen, um zukünftige Probleme zu vermeiden.

## Registerkarte &quot;Zeitleiste&quot;

In der Zeitleiste werden bestimmte Ereignisse in Ihrer Hausautomation in chronologischer Form angezeigt.

Um sie anzuzeigen, müssen Sie zuerst die Verfolgung der gewünschten Befehle oder Szenarien auf der Zeitachse aktivieren. Anschließend treten diese Ereignisse auf.

- **Szenario** : Entweder direkt auf der Szenarioseite oder auf der Szenarioübersichtsseite, um dies in großen Mengen zu tun".
- **Bestellen** : Entweder in der erweiterten Konfiguration des Befehls oder in der Konfiguration des Verlaufs, um dies in "Masse" zu tun".

Die Zeitleiste *Primär* enthält immer alle Ereignisse. Sie können die Zeitleiste jedoch nach filtern *Rekord*. An jeder Stelle, an der Sie die Zeitleiste aktivieren, haben Sie ein Feld, in das Sie den Namen eines Ordners eingeben können, ob vorhanden oder nicht.
Sie können die Zeitleiste dann nach diesem Ordner filtern, indem Sie sie links neben der Schaltfläche auswählen *Aktualisieren*.

> **Notiz**
>
> Wenn Sie keinen Ordner mehr verwenden, wird dieser in der Liste angezeigt, solange Ereignisse vorhanden sind, die mit diesem Ordner verknüpft sind. Es wird von selbst aus der Liste verschwinden.

> **Spitze**
>
> Sie haben Zugriff auf die Fenster &quot;Szenarioübersicht&quot; oder &quot;Verlaufskonfiguration&quot; direkt über die Timeline-Seite.

Sobald Sie die Verfolgung in der Zeitleiste der gewünschten Befehle und Szenarien aktiviert haben, können Sie sie in der Zeitleiste anzeigen.

> **Wichtig**
>
> Sie müssen auf neue Ereignisse warten, nachdem Sie das Tracking auf der Timeline aktiviert haben, bevor sie angezeigt werden.

### Affichage

In der Zeitleiste wird eine Tabelle mit aufgezeichneten Ereignissen in drei Spalten angezeigt:

- Datum und Uhrzeit der Veranstaltung,
- Die Art des Ereignisses: Ein Info- oder Aktionsbefehl oder ein Szenario mit dem Befehls-Plugin für Befehle.
- Der Name des übergeordneten Objekts, der Name und je nach Typ, Status oder Auslöser.

- Ein Befehlstypereignis zeigt rechts ein Symbol zum Öffnen der Befehlskonfiguration an.
- Bei einem Ereignis vom Typ Szenario werden rechts zwei Symbole angezeigt, mit denen Sie zum Szenario wechseln oder das Szenarioprotokoll öffnen können.

