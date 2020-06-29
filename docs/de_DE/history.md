Wichtiger Teil in der Software : der Historisierungsteil, real
Erinnerung daran. In Jeedom ist es möglich, jeden zu historisieren
welcher Informationstyp Befehl (binär oder digital). Das du
ermöglicht es daher beispielsweise, eine Temperaturkurve zu historisieren,
Verbrauch oder Türöffnungen…

Prinzip 
========

Hier wird das Prinzip der Historisierung von Jeedom beschrieben. Es ist nicht
notwendig, um das zu verstehen, wenn Sie irgendwelche Bedenken haben
oder möchten die Einstellungen für ändern
Historisierung. Die Standardeinstellungen sind für die meisten geeignet
cas.

Archivierung 
---------

Durch die Datenarchivierung kann Jeedom die Datenmenge reduzieren
in Erinnerung behalten. Dies ermöglicht es, nicht zu viel Platz zu verbrauchen und
das System nicht zu verlangsamen. In der Tat, wenn Sie alle behalten
Maßnahmen, dies macht umso mehr Punkte anzuzeigen und kann daher
verlängern Sie die Zeiten dramatisch, um ein Diagramm zu erstellen. Für den Fall
zu viele Punkte, es kann sogar abstürzen
Grafikanzeige.

Die Archivierung ist eine Aufgabe, die nachts beginnt und komprimiert
Daten während des Tages wiederhergestellt. Standardmäßig stellt Jeedom alle wieder her
2h ältere Daten und macht 1h Pakete (eins
Durchschnitt, Minimum oder Maximum je nach Einstellung). Also haben wir
hier 2 Parameter, einer für die Paketgröße und einer für das Wissen
wann es zu tun ist (standardmäßig sind dies Pakete
1 Stunde mit Daten, die mehr als 2 Stunden Dienstalter haben).

> **Spitze**
>
> Wenn Sie gut gefolgt sind, sollten Sie eine hohe Präzision auf dem haben
> Nur die letzten 2 Stunden. Doch wenn ich mich um 17 Uhr anmelde,
> Ich habe eine Klarstellung zu den letzten 17 Stunden. Warum ? In der Tat,
> um unnötigen Ressourcenverbrauch zu vermeiden, ist die Aufgabe, die macht
> Die Archivierung erfolgt nur einmal am Tag, abends.

> **Wichtig**
>
> Dieses Archivierungsprinzip gilt natürlich nur für Bestellungen von
> digitaler Typ; Bei Befehlen vom Typ Binär behält Jeedom nicht bei
> dass die Daten der Zustandsänderung.

Anzeigen eines Diagramms 
========================

Es gibt verschiedene Möglichkeiten, auf den Verlauf zuzugreifen :

-   Indem Sie einen Grafikbereich in eine Ansicht einfügen (siehe unten)),

-   durch Klicken auf den gewünschten Befehl in einem Widget,

-   indem Sie zur Verlaufsseite gehen, die das Überlagern ermöglicht
    verschiedene Kurven und kombinieren Stile (Fläche, Kurve, Balken)

-   auf dem Handy, während Sie auf dem betreffenden Widget gedrückt bleiben

Wenn Sie ein Diagramm auf der Verlaufsseite oder durch Klicken auf anzeigen
Über das Widget haben Sie Zugriff auf mehrere Anzeigeoptionen :

Oben rechts finden wir den Anzeigezeitraum (hier am letzten
Woche, weil ich standardmäßig nur eine Woche haben möchte - siehe
2 Absätze oben), dann kommen die Parameter der Kurve
(Diese Parameter werden von einer Anzeige zur anderen gehalten. also tust du es nicht
als sie einmal konfigurieren).

-   **Treppe** : zeigt die Kurve als an
    Treppe oder kontinuierliche Anzeige.

-   **Veränderung** : zeigt die Wertdifferenz von an
    vorheriger Punkt.

-   **Linie** : zeigt das Diagramm als Linien an.

-   **Bereich** : Zeigt das Diagramm als Fläche an.

-   **Spalte**\.* : Zeigt das Diagramm als Balken an.

Grafik zu Ansichten und Designs 
=====================================

Sie können die Grafiken auch in den Ansichten anzeigen (wir werden hier sehen
die Konfigurationsoptionen und nicht wie es geht, dafür muss man
Ansichten oder Designs basierend auf der Dokumentation rendern). hier ist
die Optionen :

Sobald eine Daten aktiviert sind, können Sie auswählen :

-   **Farbe** : die Farbe der Kurve.

-   **Typ** : Der Diagrammtyp (Bereich, Linie oder Spalte)).

-   **Maßstab** : da kann man mehrere kurven (daten) setzen)
    Im selben Diagramm ist es möglich, die Skalen zu unterscheiden
    (rechts oder links).

-   **Treppe** : zeigt die Kurve als an
    Treppe oder kontinuierliche Anzeige

-   **Stapel** : ermöglicht das Stapeln der Kurvenwerte (siehe in
    unten für das Ergebnis).

-   **Veränderung** : zeigt die Wertdifferenz von an
    vorheriger Punkt.

Option auf der Verlaufsseite 
===============================

Auf der Verlaufsseite können Sie auf einige zusätzliche Optionen zugreifen

Berechnete Geschichte 
------------------

Wird verwendet, um eine Kurve anzuzeigen, die auf einer Berechnung für mehrere basiert
Befehl (Sie können so ziemlich alles tun, + - / \* absoluter Wert ... siehe
PHP-Dokumentation für bestimmte Funktionen). Ex :
abs(*\ [Garten \] \ [Hygrometrie \] \ [Temperatur \]* - *\ [Raum von
Leben \] \ [Hygrometrie \] \ [Temperatur \]*)

Sie haben auch Zugriff auf eine Verwaltung von Berechnungsformeln, die es Ihnen ermöglicht
Speichern Sie sie zur einfacheren Anzeige

> **Spitze**
>
> Klicken Sie einfach auf den Namen des Objekts, um es zu entfalten
> erscheinen die historischen Befehle, die grafisch dargestellt werden können.

Bestellhistorie 
----------------------

Vor allen Daten, die grafisch dargestellt werden können, befinden sich zwei Symbole :

-   **Mülleimer** : ermöglicht das Löschen der aufgezeichneten Daten; dann
    Jeedom fragt, ob die Daten vor a gelöscht werden müssen
    bestimmtes Datum oder alle Daten.

-   **Pfeil** : Ermöglicht einen CSV-Export historischer Daten.

Inkonsistente Wertentfernung 
=================================

Manchmal haben Sie möglicherweise inkonsistente Werte auf dem
Grafiken. Dies ist häufig auf Bedenken hinsichtlich der Auslegung des
Wert. Es ist möglich, den Punktwert um zu löschen oder zu ändern
Frage, indem Sie direkt in der Grafik darauf klicken; von
Darüber hinaus können Sie das zulässige Minimum und Maximum festlegen
Vermeiden Sie zukünftige Probleme.

Zeitleiste 
========

In der Zeitleiste werden bestimmte Ereignisse in Ihrer Hausautomation im Formular angezeigt
chronologique.

Um sie zu sehen, müssen Sie zuerst das Tracking auf der Timeline von aktivieren
gewünschte Befehle oder Szenarien :

-   **Szenario** : entweder direkt auf der Szenarioseite oder auf der
    Szenario-Übersichtsseite, um dies in "Masse" zu tun"

-   **Bestellen** : entweder in der erweiterten Konfiguration des Befehls,
    entweder in der Konfiguration der Geschichte, um es in "Masse" zu tun"

> **Spitze**
>
> Sie haben Zugriff auf die Zusammenfassungsfenster der Szenarien oder der
> Konfiguration des Verlaufs direkt von der Seite
> Zeitleiste.

Sobald Sie die Nachverfolgung in der Bestellzeitleiste aktiviert haben und
gewünschten Szenarien können Sie sie auf der Timeline sehen.

> **Wichtig**
>
> Sie müssen auf neue Ereignisse warten, nachdem Sie das Tracking aktiviert haben
> auf der Timeline, bevor sie angezeigt werden.

Die Karten auf der Timeline werden angezeigt :

-   **Aktionsbefehl** : Im roten Hintergrund können Sie ein Symbol auf der rechten Seite anzeigen
    Zeigen Sie das erweiterte Konfigurationsfenster des Befehls an

-   **Info Befehl** : Im blauen Hintergrund können Sie ein Symbol auf der rechten Seite anzeigen
    Zeigen Sie das erweiterte Konfigurationsfenster des Befehls an

-   **Szenario** : In grauem Hintergrund haben Sie 2 Symbole : eine anzuzeigen
    das Szenario-Protokoll und eines, um zum Szenario zu gelangen


