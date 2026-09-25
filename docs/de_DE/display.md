# Zusammenfassung: Hausautomation
**Analyse → Zusammenfassung zur Hausautomation**

Auf dieser Seite können Sie die verschiedenen auf Ihrem Jeedom konfigurierten Elemente auf einer einzigen Seite zusammenfassen. Außerdem bietet sie Zugriff auf Funktionen zur Organisation von Geräten und Befehlen, auf deren erweiterte Konfiguration sowie auf Möglichkeiten zur Anpassung der Anzeige.

{% include lightbox.html src="../images/doc-display_01.jpg" data="Display" title="Display" imgstyle="width:450px;display: block;margin: 0 auto;" %}

## Informationen

Oben auf der Seite finden Sie:
- **Anzahl der Objekte**: Gesamtzahl der in unserem Jeedom konfigurierten Objekte, einschließlich inaktiver Elemente.
- **Anzahl der Geräte**: Das Gleiche gilt für die Geräte.
- **Anzahl der Bestellungen**: Das Gleiche gilt für die Bestellungen.
- **Inaktiv**: Aktivieren Sie dieses Kontrollkästchen, wenn Sie möchten, dass inaktive Elemente auf dieser Seite korrekt angezeigt werden.
- **Suchen**: Ermöglicht die Suche nach einem bestimmten Element. Dabei kann es sich um den Namen eines Geräts, eines Befehls oder um den Namen des Plugins handeln, mit dem das Gerät erstellt wurde.
- **CSV-Export**: Ermöglicht den Export aller Objekte, Geräte und deren Befehle in eine CSV-Datei.

Außerdem gibt es einen Reiter **Verlauf**, auf dem der Verlauf der gelöschten Befehle, Geräte, Objekte, Ansichten, Designs, 3D-Designs, Szenarien und Benutzer angezeigt wird.

## Objektrahmen

Darunter befindet sich ein Rahmen pro Objekt. In jedem Rahmen ist die Liste der Geräte aufgeführt, die diesem Objekt zugeordnet sind.
Der erste Rahmen **Keine** steht für Geräte, denen kein übergeordnetes Gerät zugewiesen ist.

Für jedes Objekt stehen neben seiner Bezeichnung zwei Schaltflächen zur Verfügung.
- Mit dem ersten Link wird die Konfigurationsseite des Objekts in einem neuen Tab geöffnet.
- Der zweite enthält einige Informationen zum Objekt,

> **Tipp**
>
> Die Hintergrundfarbe der Objekt-Rahmen hängt von der in den Objekt-Einstellungen gewählten Farbe ab.

> **Tipp**
>
> Per Drag & Drop können Sie die Reihenfolge der Objekte oder Geräte ändern oder diese sogar einem anderen Objekt zuweisen. Die Anzeige des Dashboards wird anhand der auf dieser Seite festgelegten Reihenfolge berechnet.

## Die Geräte

Jedes Gerät verfügt über:

- Ein **Kontrollkästchen** zum Auswählen der Geräte (Sie können mehrere auswählen). Wenn mindestens ein Gerät ausgewählt ist, werden oben links Aktionsschaltflächen angezeigt, mit denen Sie die ausgewählten Geräte **löschen**, **sichtbar**/**unsichtbar** machen oder **aktivieren**/**deaktivieren** können.
- Die **ID** des Geräts.
- Der **Typ** des Geräts: Kennung des Plugins, zu dem es gehört.
- Der **Name** des Geräts.
- **Inaktiv** (kleines Kreuz): Bedeutet, dass das Gerät inaktiv ist (wenn das Kreuz nicht angezeigt wird, ist das Gerät aktiv).
- **Unsichtbar** (durchgestrichenes Auge): Bedeutet, dass das Gerät unsichtbar ist (wenn das Symbol nicht angezeigt wird, ist das Gerät sichtbar).

Wenn das Plugin für das Gerät deaktiviert ist, werden die beiden Symbole auf der rechten Seite nicht angezeigt:
- **Externer Link** (Quadrat mit Pfeil): Ermöglicht das Öffnen der Konfigurationsseite des Geräts in einem neuen Tab.
- **Erweiterte Konfiguration** (Zahnrad): Öffnet das Fenster für die erweiterte Konfiguration des Geräts.

> Wenn Sie auf die Zeile mit dem Namen des Geräts klicken, werden alle Befehle für dieses Gerät angezeigt. Wenn Sie dann auf einen Befehl klicken, gelangen Sie zum Konfigurationsfenster für diesen Befehl.

## Erweiterte Konfiguration eines Geräts

> **Tipp**
>
> (Sofern das Plugin dies unterstützt) kann direkt von der Konfigurationsseite des Geräts aus auf dieses Fenster zugegriffen werden, indem Sie auf die Schaltfläche „Erweiterte Konfiguration“ klicken.

Im Fenster **„Erweiterte Konfiguration eines Geräts“** können Sie die Einstellungen ändern. Zunächst stehen oben rechts einige Schaltflächen zur Verfügung:

- **Informationen**: Zeigt die Rohdaten des Geräts an.
- **Links**: Hier werden die Verknüpfungen des Geräts mit Objekten, Befehlen, Szenarien, Variablen, Interaktionen usw. grafisch dargestellt (in dieser Ansicht gelangen Sie durch einen Doppelklick auf ein Element zu dessen Konfiguration).
- **Protokoll**: Zeigt die Ereignisse des betreffenden Geräts an.
- **Speichern**: Speichert die an dem Gerät vorgenommenen Änderungen.
- **Löschen**: Löscht das Gerät.

### Registerkarte „Informationen“

Die Registerkarte **Informationen** enthält allgemeine Informationen zum Gerät sowie dessen Bedienelemente:

- **ID**: Eindeutige Kennung in der Jeedom-Datenbank.
- **Name**: Name des Geräts.
- **Logische ID**: Logische Kennung des Geräts (kann leer sein).
- **Objekt-ID**: Eindeutige Kennung des übergeordneten Objekts (kann leer sein).
- **Erstellungsdatum**: Datum, an dem das Gerät erstellt wurde.
- **Aktivieren**: Setzen Sie ein Häkchen in das Kontrollkästchen, um das Gerät zu aktivieren (vergessen Sie nicht, die Änderungen zu speichern).
- **Sichtbar**: Aktivieren Sie das Kontrollkästchen, um das Gerät sichtbar zu machen (vergessen Sie nicht, die Änderungen zu speichern).
- **Typ**: Kennung des Plugins, mit dem es erstellt wurde.
- **Fehlgeschlagener Versuch**: Anzahl der aufeinanderfolgenden Kommunikationsversuche mit dem Gerät, die fehlgeschlagen sind.
- **Datum der letzten Kommunikation**: Datum der letzten Kommunikation des Geräts.
- **Letzte Aktualisierung**: Datum der letzten Kommunikation mit dem Gerät.
- **Tags**: Tags der Geräte, durch „,“ getrennt. Damit lassen sich auf dem Dashboard benutzerdefinierte Filter erstellen.

Nachstehend finden Sie eine Tabelle mit einer Liste der Befehle für die Geräte, jeweils mit einem Link zu deren Konfiguration.

### Registerkarte „Ansicht“

Auf der Registerkarte **Anzeige** können Sie bestimmte Anzeigeeinstellungen für die Kachel auf dem Dashboard oder auf Mobilgeräten konfigurieren.

#### Widget

-  **Sichtbar**: Aktivieren Sie das Kontrollkästchen, um das Gerät sichtbar zu machen.
- **Name anzeigen**: Aktivieren Sie das Kontrollkästchen, um den Namen des Geräts auf der Kachel anzuzeigen.
- **Objektnamen anzeigen**: Aktivieren Sie das Kontrollkästchen, um den Namen des übergeordneten Objekts des Geräts neben der Kachel anzuzeigen.

### Optionale Einstellungen auf der Kachel

Darunter befinden sich optionale Anzeigeeinstellungen, die auf das Gerät angewendet werden können. Diese Einstellungen bestehen aus einem Namen und einem Wert. Klicken Sie einfach auf **Hinzufügen**, um eine Einstellung anzuwenden.
Neu. Bei den Geräten wird derzeit nur der Wert **style** verwendet; damit lässt sich CSS-Code in das jeweilige Gerät einfügen.

> **Tipp**
>
> Vergessen Sie nicht, nach jeder Änderung zu speichern.

### Registerkarte „Anordnung“

In diesem Bereich können Sie zwischen der Standardanordnung der Steuerelemente (nebeneinander im Widget) und dem Tabellenmodus wählen. Im Standardmodus müssen keine Einstellungen vorgenommen werden. Hier sind die im Tabellenmodus verfügbaren Optionen:
**Tabelle**:
- **Anzahl der Leitungen**
- **Anzahl der Spalten**
- **In Feldern zentrieren**: Aktivieren Sie das Kontrollkästchen, um die Steuerelemente in den Feldern zu zentrieren.
- **Allgemeiner Stil der Felder (CSS)**: Hiermit lässt sich der allgemeine Stil mithilfe von CSS-Code festlegen.
- **Tabellenstil (CSS)**: Hiermit lässt sich ausschließlich der Stil der Tabelle festlegen.

Unter jedem Feld finden Sie die **detaillierte Konfiguration**, mit der Sie
Folgendes:
- **Text im Feld**: Fügen Sie zusätzlich zur Befehlseingabe einen Text hinzu (oder nur diesen Text, falls das Feld keine Befehlseingabe enthält).
- **Stil des Feldes (CSS)**: Ändern Sie den spezifischen CSS-Stil des Feldes (Achtung: Dieser überschreibt und ersetzt das allgemeine CSS der Felder).

> **Tipp**
>
> Wenn Sie in einem Feld der Tabelle zwei Befehle untereinander anordnen möchten, dürfen Sie nicht vergessen, in der **erweiterten Konfiguration** des jeweiligen Feldes nach dem ersten Befehl einen Zeilenumbruch einzufügen.

### Registerkarte „Benachrichtigungen“

Auf dieser Registerkarte können Sie Informationen zur Batterie des Geräts abrufen und entsprechende Warnmeldungen festlegen. Folgende Informationen stehen zur Verfügung:

- **Batterietyp**,
- **Neueste Informationen**,
- **Verbleibende Batterieleistung** (sofern Ihre Geräte natürlich mit Batterien betrieben werden).

Im folgenden Abschnitt können Sie außerdem spezifische Schwellenwerte für die Warnmeldungen der Batterie dieses Geräts festlegen. Wenn Sie die Felder leer lassen, werden die Standardschwellenwerte angewendet.

Man kann auch das Timeout des Geräts in Minuten festlegen. Die Angabe „30“ weist Jeedom beispielsweise an, dass das Gerät in den Alarmzustand versetzt werden soll, wenn es seit 30 Minuten keine Daten übermittelt hat.

> **Tipp**
>
> Die allgemeinen Einstellungen finden Sie unter **Einstellungen → System → Konfiguration: Protokolle** oder **Geräte**

### Registerkarte „Kommentar“

Hier können Sie einen Kommentar zu dem Gerät hinterlassen.

## Erweiterte Konfiguration eines Befehls

Zunächst einmal stehen oben rechts einige Schaltflächen zur Verfügung:

- **Testen**: Ermöglicht das Testen des Befehls.
- **Verbindungen**: Ermöglicht die grafische Darstellung der Verbindungen des Geräts zu Objekten, Befehlen, Szenarien, Variablen, Interaktionen usw.
- **Protokoll**: Zeigt die Ereignisse des betreffenden Geräts an.
- **Informationen**: Zeigt die Rohdaten des Geräts an.
-  **Anwenden auf**: Ermöglicht es, dieselbe Konfiguration auf mehrere Befehle anzuwenden.
- **Speichern**: Speichert die an dem Gerät vorgenommenen Änderungen.

> **Tipp**
>
> Wenn Sie in einer Grafik doppelt auf ein Element klicken, gelangen Sie zu dessen Konfiguration.

> **Hinweis**
>
> Je nach Art des Befehls können sich die angezeigten Informationen bzw. Aktionen ändern.

### Registerkarte „Informationen“

Die Registerkarte **Informationen** enthält allgemeine Informationen zum Auftrag:

- **ID**: Eindeutige Kennung in der Datenbank.
- **Logische ID**: Logische Kennung des Befehls (kann leer sein).
- **Name**: Name des Befehls.
- **Typ**: Art des Befehls (Aktion oder Information).
- **Untertyp**: Untertyp des Befehls (binär, numerisch …​).
- **Direkte URL**: Stellt die URL für den Zugriff auf dieses Gerät bereit. (Rechtsklick, Link-Adresse kopieren) Die URL löst den Befehl für eine **Aktion** aus und gibt die Informationen für eine **Abfrage** zurück.
- **Einheit**: Einheit der Bestellung.
- **Befehl, der eine Aktualisierung auslöst**: Gibt die Kennung eines anderen Befehls an, der, sobald sich dieser andere Befehl ändert, die Aktualisierung des angezeigten Befehls erzwingt.
- **Sichtbar**: Aktivieren Sie dieses Kontrollkästchen, damit der Befehl sichtbar ist.
- **In der Zeitleiste anzeigen**: Aktivieren Sie dieses Kontrollkästchen, damit dieser Befehl in der Zeitleiste sichtbar ist, wenn er verwendet wird. Sie können eine bestimmte Zeitleiste im Feld angeben, das angezeigt wird, wenn die Option aktiviert ist.
- **Automatische Interaktionen verbieten**: Verbietet automatische Interaktionen für diesen Befehl
- **Symbol**: Ermöglicht es, das Symbol des Befehls zu ändern.

Darunter befinden sich außerdem drei weitere orangefarbene Tasten:

- **Dieser Befehl ersetzt die ID**: Ermöglicht es, eine Befehls-ID durch den entsprechenden Befehl zu ersetzen. Nützlich, wenn Sie ein Gerät in Jeedom gelöscht haben und Szenarien vorhanden sind, die Befehle dieses Geräts verwenden.
- **Dieser Befehl ersetzt den Befehl**: Ersetzt einen Befehl durch den aktuellen Befehl.
- **Diesen Befehl durch den Befehl ersetzen**: Das Gegenteil, ersetzt den Befehl durch einen anderen Befehl.

> **Hinweis**
>
> Diese Art von Aktion ersetzt Befehle überall in Jeedom (Szenario, Interaktion, Befehl, Gerät…​.).

Nachfolgend finden Sie eine Liste der verschiedenen Geräte, Befehle, Szenarien oder Interaktionen, die diesen Befehl verwenden. Mit einem Klick darauf gelangen Sie direkt zu deren jeweiligen Einstellungen.

### Registerkarte „Konfiguration“

#### Für eine Anfrage vom Typ „Info“:

- **Berechnung und Rundung**
    - **Berechnungsformel (\#value\# für den Wert)**: Ermöglicht die Durchführung einer Berechnung mit dem Wert des Befehls vor der Verarbeitung durch Jeedom, Beispiel: `#value# - 0.2` um 0,2 abzuziehen (Offset bei einem Temperatursensor).
    - **Rundung (Dezimalstelle)**: Ermöglicht die Rundung des Bestellwerts (Beispiel: Geben Sie „2“ ein, um 16,643345 auf 16,64 zu runden).
- **Generischer Typ**: Ermöglicht die Konfiguration des generischen Befehlstyps (Jeedom versucht, diesen im Auto-Modus selbst zu ermitteln). Diese Information wird von der mobilen App verwendet.
- **Aktion bei Wert, wenn**: Ermöglicht die Erstellung von sogenannten Miniszenarien. Sie können beispielsweise festlegen, dass eine bestimmte Aktion ausgeführt wird, wenn der Wert 3 Minuten lang über 50 liegt. So lässt sich beispielsweise ein Licht X Minuten nach dem Einschalten wieder ausschalten.

- **Geschichte**
    - **Protokollieren**: Aktivieren Sie das Kontrollkästchen, damit die Werte dieses Befehls protokolliert werden. (Siehe **Analyse→Protokoll**)
    - **Glättungsmodus**: Mit dem Modus **Glättung** oder **Archivierung** können Sie festlegen, wie die Daten archiviert werden sollen. Standardmäßig wird der **Durchschnitt** verwendet. Sie können auch **Maximum**, **Minimum** oder **keine** wählen. Mit **keine** wird Jeedom mitgeteilt, dass für diesen Befehl keine Archivierung erfolgen soll (sowohl im ersten 5-Minuten-Zeitraum als auch bei der Archivierungsaufgabe). Diese Option ist riskant, da Jeedom alles speichert: Es werden daher wesentlich mehr Daten gespeichert.
    - **Verlauf löschen, wenn älter als**: Damit weisen Sie Jeedom an, alle Daten zu löschen, die älter sind als ein bestimmter Zeitraum. Dies kann praktisch sein, um Daten nicht unnötig aufzubewahren und somit die Menge der von Jeedom gespeicherten Informationen zu begrenzen. Bitte beachten Sie, dass die Löschung nachts erfolgt; Sie müssen daher abwarten, bis die Nacht vorbei ist, bevor die Löschung tatsächlich wirksam wird.

- **Wertverwaltung**
    - **Unzulässiger Wert**: Wenn der Befehl einen dieser Werte annimmt, ignoriert Jeedom ihn, bevor er ausgeführt wird.
    - **Status-Rückgabewert**: Ermöglicht es, den Befehl nach einer bestimmten Zeit auf diesen Wert zurückzusetzen.
    - **Zeit bis zur Rückkehr zum Ausgangszustand (min)**: Zeit, bis der oben genannte Wert wieder erreicht ist.

- **Sonstiges**
    - **Verwaltung der Wertwiederholung**: Im Automatikmodus ignoriert Jeedom den zweiten Wert, wenn der Befehl zweimal hintereinander denselben Wert übermittelt (verhindert die mehrfache Auslösung eines Szenarios, es sei denn, es handelt sich um einen binären Befehl). Sie können die Wiederholung des Werts erzwingen oder vollständig unterbinden.
    - **Push-URL**: Ermöglicht das Hinzufügen einer URL, die bei einer Aktualisierung des Befehls aufgerufen werden soll. Sie können die folgenden Tags verwenden: `#value#` für den Bestellwert, `#cmd_name#` für den Namen des Befehls, `#cmd_id#` für die eindeutige Bestell-ID, `#humanname#` für den vollständigen Namen des Befehls (z. B.: `#[Salle de bain][Hydrometrie][Humidité]#`), `#eq_name#` als Name des Geräts.

#### Für einen Befehl:

-  **Generischer Typ**: Ermöglicht die Konfiguration des generischen Befehlstyps (Jeedom versucht, diesen im Auto-Modus selbst zu ermitteln). Diese Information wird von der mobilen App verwendet.
- **Aktion bestätigen**: Aktivieren Sie dieses Kontrollkästchen, damit Jeedom eine Bestätigung anfordert, wenn die Aktion über die Benutzeroberfläche dieses Befehls ausgelöst wird.
- **Zugangscode**: Ermöglicht die Festlegung eines Codes, den Jeedom abfragt, wenn die Aktion über die Benutzeroberfläche dieses Befehls ausgelöst wird.
- **Aktion vor der Ausführung des Befehls**: Ermöglicht es, **vor** jeder Ausführung des Befehls Befehle hinzuzufügen.
- **Aktion nach Ausführung des Befehls**: Ermöglicht das Hinzufügen von Befehlen **nach** jeder Ausführung des Befehls.

### Registerkarte „Benachrichtigungen“

Ermöglicht die Festlegung einer Alarmstufe (**Warnung** oder **Gefahr**) in Abhängigkeit von bestimmten Bedingungen. Zum Beispiel, wenn `value > 8` Wenn dies 30 Minuten lang andauert, kann das Gerät in den Alarmzustand **warning** wechseln.

> **Hinweis**
>
> Auf der Seite **Einstellungen → System → Konfiguration: Protokolle** können Sie einen Befehl vom Typ „Nachricht“ konfigurieren, der es Jeedom ermöglicht, Sie zu benachrichtigen, wenn der Warn- oder Gefahren-Schwellenwert erreicht wird.

### Registerkarte „Ansicht“

In diesem Abschnitt können Sie bestimmte Anzeigeeinstellungen des Widgets auf dem Dashboard, in den Ansichten, im Design und auf Mobilgeräten konfigurieren.

- **Widget**: Hier können Sie das Widget für den Desktop oder für Mobilgeräte auswählen (beachten Sie, dass Sie dafür das Widget-Plugin benötigen und die Auswahl auch über dieses Plugin vornehmen können).
- **Sichtbar**: Aktivieren Sie dieses Kontrollkästchen, um den Befehl sichtbar zu machen.
- **Namen anzeigen**: Aktivieren Sie diese Option, um den Namen des Befehls je nach Kontext anzuzeigen.
- **Name und Symbol anzeigen**: Aktivieren Sie dieses Kontrollkästchen, um zusätzlich zum Namen des Befehls das Symbol anzuzeigen.
- **Erzwungener Zeilenumbruch vor dem Widget**: Aktivieren Sie **vor dem Widget** oder **nach dem Widget**, um vor oder nach dem Widget einen Zeilenumbruch einzufügen (um beispielsweise eine Spaltenanzeige der verschiedenen Gerätebefehle anstelle der standardmäßigen Zeilenanzeige zu erzwingen).

Darunter befinden sich optionale Anzeigeeinstellungen, die an das Widget übergeben werden können. Diese Einstellungen hängen vom jeweiligen Widget ab; um sie zu erfahren, muss man daher dessen Beschreibung im Market nachlesen.

> **Tipp**
>
> Vergessen Sie nicht, nach jeder Änderung zu speichern.
