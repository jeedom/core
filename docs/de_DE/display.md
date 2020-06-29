Beschreibung 
===========

Auf dieser Seite können Sie auf einer Seite die verschiedenen zusammenfassen
Elemente auf seinem Jeedom konfiguriert. Es gibt auch Zugang zu
Funktionen der Organisation von Geräten und Kontrollen, an deren
erweiterte Konfiguration sowie Konfigurationsmöglichkeiten
d'affichage.

Diese Seite ist zugänglich von **Extras → Zusammenfassung der Hausautomation**.

Der obere Rand der Seite 
------------------

Oben auf der Seite finden wir : \.* **Anzahl der Objekte** : Nombre
Gesamtzahl der in unserem Jeedom konfigurierten Objekte, wobei die Elemente gezählt werden
Inaktiv. \* **Anzahl der Ausrüstungen** : Das Gleiche gilt für die Ausrüstung. \*
**Anzahl der Bestellungen** : Das Gleiche gilt für Bestellungen. \* **Inaktiv** :
Aktivieren Sie dieses Kontrollkästchen, wenn die inaktiven Elemente korrekt sein sollen
auf dieser Seite angezeigt. \* **Suche** : Suche nach a
bestimmtes Element. Es kann der Name eines Geräts sein, eine Bestellung
oder der Name des Plugins, mit dem das Gerät erstellt wurde.

Sie haben auch eine Schaltfläche &quot;Verlauf von Löschungen&quot;, mit der Sie den Verlauf von Bestellungen, Geräten, Objekten, Ansichten, Designs, 3D-Designs, Szenarien und gelöschten Benutzern anzeigen können.

Objektrahmen 
----------------

Darunter befindet sich ein Frame pro Objekt. In jedem Frame finden wir
die Liste der Geräte (in blau), die dieses Objekt als übergeordnetes Objekt haben. die
erster Frame **Keine** stellt Geräte dar, die keine haben
betroffene Eltern. Für jedes Objekt neben seiner Beschriftung drei Schaltflächen
sind verfügbar. Von links nach rechts :

-   Die erste wird verwendet, um die Objektkonfigurationsseite in a zu öffnen
    neue Registerkarte,

-   Die zweite enthält einige Informationen zum Objekt,

-   Mit dem letzten können Sie die Liste der Geräte ein- oder ausblenden
    ihm zugeschrieben.

> **Spitze**
>
> Die Hintergrundfarbe der Objektrahmen hängt von der in ausgewählten Farbe ab
> Objektkonfiguration.

> **Spitze**
>
> Durch Klicken / Ablegen auf das Gerät können Sie dessen ändern
> bestellen oder sogar einem anderen Objekt zuordnen. Es ist aus der Bestellung
> Auf dieser Seite wurde festgelegt, dass die Dashboard-Anzeige berechnet wird.

Die Ausrüstungen 
---------------

Auf jeder Ausrüstung finden wir :

-   Ein **Kontrollkästchen** um die Ausrüstung auszuwählen (Sie können
    Wählen Sie mehrere). Wenn mindestens ein Gerät ausgewählt ist
    Sie haben Aktionsschaltflächen, die oben links angezeigt werden
    für **Entfernen**, make **sichtbar**/.**unsichtbar**,
    **Aktiva**/.**Inaktiv** ausgewählte Ausrüstung.

-   Die **Name** Ausrüstung.

-   Die **Typ** Ausrüstung : Kennung des Plugins, zu dem
    es gehört.

-   **Inaktiv** (kleines Kreuz) : Bedeutet, dass das Gerät inaktiv ist
    (Ist dies nicht der Fall, ist das Gerät aktiv).

-   **Unsichtbar** (gekreuztes Auge) : Bedeutet, dass das Gerät unsichtbar ist
    (Ist dies nicht der Fall, ist das Gerät sichtbar).

-   **Externer Link** (Quadrat mit Pfeil) : Lass uns in a öffnen
    Neue Registerkarte der Gerätekonfigurationsseite.

-   **Erweiterte Konfiguration** (Zahnrad) : öffnet die
    Fenster zur erweiterten Gerätekonfiguration.

-   **Liste der Befehle** (der Pfeil) : ermöglicht es Ihnen, die Liste von zu erweitern
    Befehle (auf orangefarbenem Hintergrund).

Wenn Sie die Befehlsliste erweitern, entspricht jeder orangefarbene Block
eine Bestellung für Ihre Ausrüstung (ein neuer Klick auf den kleinen Pfeil
Ausrüstung kann sie verstecken).

Wenn Sie auf die Bestellung doppelklicken oder auf die kleine klicken
gekerbtes Rad Dadurch wird das Konfigurationsfenster geöffnet.

Erweiterte Gerätekonfiguration 
=====================================

> **Spitze**
>
> Es ist möglich, direkt auf (wenn das Plugin dies unterstützt) zuzugreifen
> dieses Fenster von der Gerätekonfigurationsseite in
> Klicken Sie auf die Schaltfläche Erweiterte Konfiguration

Das Fenster von **Erweiterte Gerätekonfiguration** ermöglicht die
ändern. Zuerst oben rechts einige Schaltflächen
verfügbar :

-   **Verbindungen** : Zeigt die Verknüpfungen des Geräts mit dem an
    Objekte, Befehle, Szenarien, Variablen, Interaktionen ... im Formular
    Grafik (in diesem Fall bringt Sie ein Doppelklick auf ein Element zu
    seine Konfiguration).

-   **Log** : Zeigt die Ereignisse des betreffenden Geräts an.

-   **Informationen** : Zeigt die Roheigenschaften des Geräts an.

-   **Rekord** : Speichern Sie die vorgenommenen Änderungen
    auf Ausrüstung.

-   **Entfernen** : Ausrüstung entfernen.

Registerkarte Informationen 
-------------------

Die Registerkarte **Informationen** enthält die allgemeinen Informationen von
die Ausrüstung und ihre Kontrollen :

-   **Identifikation** : Eindeutige Kennung in der Jeedom-Datenbank.

-   **Name** : Name der Ausrüstung.

-   **Logische ID** : Kennung der logischen Ausrüstung (can
    leer sein).

-   **Objekt-ID** : Eindeutige Kennung des übergeordneten Objekts (can
    leer sein).

-   **Erstellungsdatum** : Erstellungsdatum der Ausrüstung.

-   **Activate** : Aktivieren Sie das Kontrollkästchen, um das Gerät zu aktivieren (nicht vergessen
    speichern).

-   **Sichtbar** : Aktivieren Sie das Kontrollkästchen, um das Gerät sichtbar zu machen (ohne
    vergiss zu speichern).

-   **Typ** : Kennung des Plugins, mit dem es erstellt wurde.

-   **Versuch fehlgeschlagen** : Anzahl der Kommunikationsversuche
    konsekutiv mit ausgefallener Ausrüstung.

-   **Datum der letzten Mitteilung** : Datum des letzten
    Gerätekommunikation.

-   **Letztes Update** : Datum der letzten Mitteilung
    mit Ausrüstung.

-   **Stichworte** : Geräteetiketten, die durch zu trennen sind ','. Auf dem Dashboard können personalisierte Filter erstellt werden

Unten finden Sie eine Tabelle mit der Liste der Befehle für
die Ausrüstung mit jeweils einem Link zu ihrer Konfiguration.

Registerkarte &quot;Ansicht&quot; 
----------------

In der Registerkarte **Anzeigen**, Sie können einige konfigurieren
Anzeigeverhalten der Kachel im Dashboard, die Ansichten, die
Design sowie mobil.

### Widget 

-   **Sichtbar** : Aktivieren Sie das Kontrollkästchen, um das Gerät sichtbar zu machen.

-   **Name anzeigen** : Aktivieren Sie das Kontrollkästchen, um den Namen von anzuzeigen
    Ausrüstung auf der Fliese.

-   **Objektnamen anzeigen** : Aktivieren Sie das Kontrollkästchen, um den Namen anzuzeigen
    des übergeordneten Objekts der Ausrüstung neben der Kachel.

-   **Hintergrundfarbe** : Aktivieren Sie das Kontrollkästchen, um die Hintergrundfarbe beizubehalten
    standardmäßig (abhängig von der **Kategorie** Ihrer Ausrüstung, siehe
    **Administration → Einstellungen → Farben**). Wenn Sie dies deaktivieren
    Feld können Sie eine andere Farbe wählen. Sie können auch
    Aktivieren Sie ein neues Kontrollkästchen **Transparent** das machen
    transparenter Hintergrund.

-   **Opazität** : Deckkraft der Hintergrundfarbe der Kachel.

-   **Textfarbe** : Aktivieren Sie das Kontrollkästchen, um die Farbe des zu behalten
    Standardtext.

-   **Grenzen** : Aktivieren Sie das Kontrollkästchen, um den Standardrahmen beizubehalten.
    Andernfalls müssen Sie den CSS-Code eingeben, Eigenschaft `border` (z :
    `3px blau gestrichelt` für einen gepunkteten Rand von 3px in blau).

-   **Abgerundete Kanten** (in px) : Aktivieren Sie das Kontrollkästchen, um es zu behalten
    die Standardrundung. Andernfalls müssen Sie den CSS-Code und die Eigenschaft eingeben
    "Randradius" (z : ``10px``)

### Optionale Parameter auf der Kachel 

Unten finden wir optionale Anzeigeparameter, die wir
kann für Geräte gelten. Diese Parameter bestehen aus einem Namen und
wert. Klicken Sie einfach auf **Hinzufügen** einen anwenden
wieder. Für Geräte nur den Wert **Stil** ist für die
Moment verwendet, ermöglicht es das Einfügen von CSS-Code auf dem Gerät in
question.

> **Spitze**
>
> Vergessen Sie nicht, nach jeder Änderung zu speichern.

Registerkarte Layout 
------------------

In diesem Teil können Sie zwischen der Standardanordnung von wählen
Befehle (nebeneinander im Widget) oder im Tabellenmodus. Es gibt
Im Standardmodus ist nichts einzustellen. Hier sind die im Modus verfügbaren Optionen
**Tabelle** :

-   **Anzahl der Zeilen**

-   **Anzahl der Spalten**

-   **In Kisten zentrieren** : Aktivieren Sie das Kontrollkästchen, um das zu zentrieren
    Befehle in den Feldern.

-   **Allgemeiner Boxstil (CSS))** : Ermöglicht das Definieren des Stils
    Allgemein im CSS-Code.

-   **Tabellenstil (CSS)** : Hier können Sie den Stil des definieren
    nur Tisch.

Unten für jede Box die **detaillierte Konfiguration** erlaubt dir
diese :

-   **Boxtext** : Fügen Sie zusätzlich zum Befehl Text hinzu (oder
    ganz alleine, wenn keine Bestellung in der Box ist).

-   **Fallstil (CSS)** : Ändern Sie den spezifischen CSS-Stil des
    box (Vorsicht, dies überschreibt und ersetzt das allgemeine CSS
    Boxen).

> **Spitze**
>
> Wenn Sie in einem Feld in der Tabelle zwei Befehle eingeben möchten, geben Sie einen ein
> Vergessen Sie nicht, unter dem anderen eine Rückgabe hinzuzufügen
> Linie nach der Premiere in der **Erweiterte Konfiguration** davon.

Registerkarte &quot;Warnungen&quot; 
--------------

Diese Registerkarte enthält Informationen zur Batterie von
die Ausrüstung und definieren Warnungen in Bezug darauf. Hier sind die
Arten von Informationen, die gefunden werden können :

-   **Batterietyp**,

-   **Neuestes Feedback**,

-   **Verbleibendes Niveau**, (wenn natürlich deine Ausrüstung funktioniert
    batteriebetrieben).

Im Folgenden können Sie auch bestimmte Alarmschwellenwerte für definieren
Batterie für dieses Gerät. Wenn Sie die Kästchen leer lassen, sind dies
Die Standardschwellenwerte, die angewendet werden.

Sie können das Zeitlimit der Ausrüstung auch in Minuten verwalten. durch
Beispiel 30 sagt jeedom, dass, wenn das Gerät nicht kommuniziert hat
für 30 Minuten, dann müssen Sie es in Alarmbereitschaft versetzen.

> **Spitze**
>
> Die globalen Parameter sind in **Administration → Konfiguration → Protokolle**
> (oder **Einrichtungen**)

Registerkarte &quot;Kommentar&quot; 
------------------

Ermöglicht das Schreiben eines Kommentars zur Ausrüstung (Datum von
zum Beispiel die Batterie wechseln).

Erweiterte Konfiguration einer Bestellung 
====================================

Zunächst sind oben rechts einige Schaltflächen verfügbar :

-   **Test** : Wird zum Testen des Befehls verwendet.

-   **Verbindungen** : Zeigt die Verknüpfungen des Geräts mit dem an
    Objekte, Befehle, Szenarien, Variablen, Interaktionen…. unter
    grafische Form.

-   **Log** : Zeigt die Ereignisse des betreffenden Geräts an.

-   **Informationen** : Zeigt die Roheigenschaften des Geräts an.

-   Bewerben Sie sich bei \* : Wenden Sie dieselbe Konfiguration auf an
    Mehrfachbestellungen.

-   **Rekord** : Speichern Sie die vorgenommenen Änderungen an
    Ausrüstung

> **Spitze**
>
> In einem Diagramm bringt Sie ein Doppelklick auf ein Element zu seinem
> Konfiguration.

> **Notiz**
>
> Abhängig von der Art der Bestellung werden die Informationen / Aktionen angezeigt
> kann sich ändern.

Registerkarte Informationen 
-------------------

Die Registerkarte **Informationen** enthält allgemeine Informationen über die
bestellen :

-   **Identifikation** : Eindeutiger Bezeichner in der Datenbank.

-   **Logische ID** : Logische Kennung des Befehls (can
    leer sein).

-   **Name** : Name der Bestellung.

-   **Typ** : Art des Befehls (Aktion oder Info).

-   **Unterart** : Befehlssubtyp (binär, digital usw.)).

-   **Direkte URL** : Gibt die URL für den Zugriff auf dieses Gerät an. (klicken
    rechts, kopiere die Linkadresse) Die URL startet den Befehl für a
    **Aktion** und geben Sie die Informationen für a zurück **Info**.

-   **Einheit** : Steuereinheit.

-   **Befehl, der ein Update auslöst** : Gibt die Kennung von a an
    anderer Befehl, der, wenn sich dieser andere Befehl ändert, das erzwingt
    Aktualisierung der angezeigten Bestellung.

-   **Sichtbar** : Aktivieren Sie dieses Kontrollkästchen, um den Befehl sichtbar zu machen.

-   **Folgen Sie der Zeitleiste** : Aktivieren Sie dieses Kontrollkästchen, um dies zu haben
    Befehl ist in der Timeline sichtbar, wenn er verwendet wird.

-   **In automatischen Interaktionen verbieten** : verbietet ihnen
    automatische Interaktionen mit diesem Befehl

-   **Symbol** : Ermöglicht das Ändern des Befehlssymbols.

Sie haben auch drei andere orangefarbene Knöpfe darunter :

-   **Dieser Befehl ersetzt die ID** : Ersetzen Sie eine ID von
    Bestellung nach Bestellung. Nützlich, wenn Sie a gelöscht haben
    Ausrüstung in Jeedom und Sie haben Szenarien, die verwenden
    Befehle davon.

-   **Dieser Befehl ersetzt den Befehl** : Ersetzen Sie eine Bestellung durch
    der aktuelle Befehl.

-   **Ersetzen Sie diesen Befehl durch den Befehl** : Das Gegenteil ersetzt
    die Bestellung durch eine andere Bestellung.

> **Notiz**
>
> Diese Art von Aktion ersetzt Befehle in ganz Jeedom
> (Szenario, Interaktion, Befehl, Ausrüstung….)

Nachfolgend finden Sie die Liste der verschiedenen Geräte,
Befehle, Szenarien oder Interaktionen, die diesen Befehl verwenden. ein
Klicken Sie darauf, um direkt zu ihrer Konfiguration zu gelangen
respective.

Registerkarte Konfiguration 
--------------------

### Für eine Info-Bestellung : 

-   **Berechnung und Rundung**

    -   **Berechnungsformel (\#value\.# für Wert)** : Ermöglicht
        Machen Sie eine Operation mit dem Wert der Bestellung vor
        Jeedom Behandlung, Beispiel : ``#value# - 0.2` zu verschanzen
        0,2 (Offset an einem Temperatursensor).

    -   **Abgerundet (Zahl nach Dezimalpunkt)** : Lass uns umrunden
        Bestellwert (Beispiel : setze 2 um zu transformieren
        16.643345 in 16.64).

-   **Generischer Typ** : Ermöglicht die Konfiguration des generischen Typs von
    Befehl (Jeedom versucht, es im Auto-Modus selbst zu finden).
    Diese Informationen werden von der mobilen Anwendung verwendet.

-   **Aktion auf Wert, wenn** : Lass uns Arten von machen
    Mini-Szenarien. Sie können zum Beispiel sagen, wenn der Wert wert ist
    mehr als 50 für 3 Minuten, dann müssen Sie eine solche Aktion ausführen. es
    ermöglicht beispielsweise das Ausschalten eines Lichts X Minuten später
    es ist beleuchtet.

-   **Historisch**

    -   **Historisieren** : Aktivieren Sie das Kontrollkästchen, um die Werte dafür zu erhalten
        Bestellung aufgezeichnet werden. (Siehe **Analyse → Geschichte**)

    -   **Glättungsmodus** : Modus von **glätten** oder d'**Archivierung**
        Hier können Sie auswählen, wie die Daten archiviert werden sollen. Standard,
        es ist ein **Durchschnitt**. Es ist auch möglich, die zu wählen
        **Maximum**, Die **Minimum**, oder **Keine**. **Keine** Ermöglicht
        Sag Jeedom, dass es nicht darauf archivieren soll
        Bestellung (sowohl während der ersten 5 Minuten als auch mit dem
        Archivierungsaufgabe). Diese Option ist gefährlich, weil Jeedom
        behalte alles : Es wird also noch viel mehr geben
        gespeicherte Daten.

    -   **Verlauf löschen, wenn älter als** : Sagen wir mal
        Jeedom, um alle Daten zu löschen, die älter als eins sind
        bestimmte Zeit. Kann nützlich sein, um nicht zu behalten
        Daten, wenn es nicht notwendig ist und daher die Menge begrenzen
        von Informationen von Jeedom aufgezeichnet.

-   **Werteverwaltung**

    -   **Verbotener Wert** : Wenn der Befehl einen dieser Werte annimmt,
        Jeedom ignoriert es, bevor er es anwendet.

    -   **Statusrückgabewert** : Gibt den Befehl an zurück
        dieser Wert nach einer Weile.

    -   **Dauer vor Rückkehr des Status (min)** : Zeit vor der Rückkehr zu
        Wert oben.

-   **Andere**

    -   **Management der Wiederholung von Werten** : In automatischer wenn die
        Befehl steigt 2 mal den gleichen Wert in einer Reihe, dann Jeedom
        wird den 2. Aufstieg nicht berücksichtigen (Auslösung vermeiden
        mehrmals ein Szenario, es sei denn, der Befehl lautet
        binärer Typ). Sie können die Wiederholung des Werts erzwingen oder
        verbiete es komplett.

    -   **URL drücken** : Ermöglicht das Hinzufügen einer URL, die im Falle von aufgerufen werden soll
        Bestellaktualisierung. Sie können Tags verwenden
        folgende : ``#value#"für den Wert der Bestellung"#cmd_name#``
        für den Namen des Befehls `#cmd_id#`für die eindeutige Kennung
        des Befehls, `#humanname#`für den vollständigen Namen des Befehls
        (ex : ``#[SalDie de bain][Hydrometrie][Humidité]#`),`#eq_name#`für den Namen des Geräts

### Für einen Aktionsbefehl : 

-   **Generischer Typ** : Ermöglicht die Konfiguration des generischen Typs von
    Befehl (Jeedom versucht, es im Auto-Modus selbst zu finden).
    Diese Informationen werden von der mobilen Anwendung verwendet.

-   **Aktion bestätigen** : Aktivieren Sie dieses Kontrollkästchen, damit Jeedom dies anfordert
    Bestätigung, wenn die Aktion über die Schnittstelle gestartet wird
    dieses Befehls.

-   **Zugangscode** : Ermöglicht das Definieren eines Codes, den Jeedom anfordert
    wenn die Aktion über die Schnittstelle dieses Befehls gestartet wird.

-   **Aktion vor Ausführung des Befehls** : Ermöglicht das Hinzufügen
    Befehle **vor** jede Ausführung des Auftrags.

-   **Aktion nach Ausführung des Auftrags** : Ermöglicht das Hinzufügen
    Befehle **nach** jede Ausführung des Auftrags.

Registerkarte &quot;Warnungen&quot; 
--------------

Ermöglicht das Definieren einer Alarmstufe (**Warnung** oder **Gefahr**) en
abhängig von bestimmten Bedingungen. Zum Beispiel, wenn &quot;Wert&gt; 8&quot; für 30
Minuten dann kann das Gerät in Alarmbereitschaft gehen **Warnung**.

> **Notiz**
>
> Auf der Seite **Administration → Konfiguration → Protokolle**, du kannst
> Konfigurieren Sie einen Befehl vom Typ Nachricht, mit dem Jeedom Sie abrufen kann
> warnen, wenn die Warn- oder Gefahrenschwelle erreicht ist.

Registerkarte &quot;Ansicht&quot; 
----------------

In diesem Teil können Sie bestimmte Verhaltensweisen konfigurieren
Anzeige des Widgets im Dashboard, Ansichten, Design und
mobile.

-   **Widget** : Ermöglicht die Auswahl des Widgets auf dekstop oder mobil (at
    Beachten Sie, dass Sie das Widget-Plugin benötigen und dies auch tun können
    davon).

-   **Sichtbar** : Überprüfen Sie, ob der Befehl sichtbar ist.

-   **Name anzeigen** : Überprüfen Sie den Namen des
    Befehl, je nach Kontext.

-   **Anzeigename und Symbol** : Aktivieren Sie das Kontrollkästchen, um das Symbol sichtbar zu machen
    zusätzlich zum Namen des Befehls.

-   **Umbrochene Zeile vor dem Widget** : Wählen **vorher
    Widget** oder **nach dem Widget** Zeilenumbruch hinzufügen
    vor oder nach dem Widget (um beispielsweise eine Anzeige in zu erzwingen
    Spalte mit verschiedenen Gerätebefehlen anstelle von Zeilen
    Standard)

Unten finden wir optionale Anzeigeparameter, die wir
kann zum Widget wechseln. Diese Parameter hängen vom jeweiligen Widget ab,
Sie müssen sich also seine Karte auf dem Markt ansehen, um sie zu kennen.

> **Spitze**
>
> Vergessen Sie nicht, nach jeder Änderung zu speichern.

Registerkarte Code 
-----------

Ermöglicht das Ändern des Widget-Codes nur für den aktuellen Befehl.

> **Notiz**
>
> Wenn Sie den Code ändern möchten, vergessen Sie nicht, das Kontrollkästchen zu aktivieren
> **Aktivieren Sie die Widget-Anpassung**
