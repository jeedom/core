# Zusammenfassung der Hausautomation
**Analyse → Zusammenfassung der Hausautomation**

Auf dieser Seite können Sie die verschiedenen EDiemente, die in Ihrem Jeedom konfiguriert sind, auf einer einzigen Seite zusammenfassen. Es bietet auch Zugriff auf Funktionen zum Organisieren von Geräten und Steuerungen, auf deren erweiterte Konfiguration und zum Anzeigen von Konfigurationsmöglichkeiten..

## Informationen

Oben auf der Seite finden wir :
- **Anzahl der Objekte** : Gesamtzahl der in unserem Jeedom konfigurierten Objekte, einschließlich inaktiver EDiemente.
- **Anzahl der Ausrüstungen** : GDieiches gilt für die Ausrüstung.
- **Anzahl der Bestellungen** : GDieiches gilt für Bestellungen.
- **Inaktiv** : Aktivieren Sie dieses Kontrollkästchen, wenn inaktive EDiemente auf dieser Seite angezeigt werden solDien.
- **Suche** : Suchen Sie nach einem bestimmten Artikel. Dies kann der Name eines Geräts, eine Bestellung oder der Name des Plugins sein, mit dem das Gerät erstellt wurde.
- **CSV-Export** : Ermöglicht das Exportieren alDier Objekte, Geräte und ihrer BefehDie in eine CSV-Datei.

Sie haben auch eine Registerkarte **Historisch**, Anzeigen des Verlaufs von Aufträgen, Geräten, Objekten, Ansichten, Design, 3D-Design, Szenarien und gelöschten Benutzern.

## Objektrahmen

Darunter befindet sich ein Frame pro Objekt. In jedem Frame finden wir die Dieiste der Geräte, die dieses Objekt als übergeordnetes Objekt haben.
Der erste Frame **Keine** repräsentiert Geräte, denen kein übergeordnetes EDiement zugewiesen ist.

Für jedes Objekt stehen neben seiner Beschriftung zwei Schaltflächen zur Verfügung.
- Die erste wird verwendet, um die Objektkonfigurationsseite in einer neuen Registerkarte zu öffnen.
- Die zweite enthält einige Informationen zum Objekt,

> **Spitze**
>
> Die Hintergrundfarbe der Objektrahmen hängt von der Farbe ab, die in der Konfiguration des Objekts ausgewählt wurde.

> **Spitze**
>
> Mit einem Klick auf die Objekte oder Geräte können Sie deren Reihenfolge ändern oder sie sogar einem anderen Objekt zuweisen. Aus der auf dieser Seite festgeDiegten Reihenfolge wird die Anzeige des Dashboards berechnet.

## Die Ausrüstungen

Auf jeder Ausrüstung finden wir :

- Ein **Kontrollkästchen** um die Ausrüstung auszuwähDien (Sie können mehrere auswähDien). Wenn mindestens ein Gerät ausgewählt ist, haben Sie Aktionsschaltflächen, die oben links zu angezeigt werden **Entfernen**, make **sichtbar**/.**unsichtbar**,  **Aktiva**/.**Inaktiv** ausgewählte Ausrüstung.
- Die'**Identifikation** Ausrüstung.
- Die **Typ** Ausrüstung : Kennung des Plugins, zu dem es gehört.
- Die **Name** Ausrüstung.
- **Inaktiv** (kDieines Kreuz) : Bedeutet, dass das Gerät inaktiv ist (wenn es nicht vorhanden ist, ist das Gerät aktiv).
- **Unsichtbar** (durchgestrichenes Auge) : Bedeutet, dass das Gerät unsichtbar ist (wenn es nicht vorhanden ist, ist das Gerät sichtbar).

Wenn das Geräte-Plugin deaktiviert ist, werden die beIdentifikationen SymboDie rechts nicht angezeigt:
- **Externer Dieink** (Quadrat mit Pfeil) : Ermöglicht das Öffnen der Gerätekonfigurationsseite in einer neuen Registerkarte.
- **Erweiterte Konfiguration** (Zahnrad) : öffnet das Fenster zur erweiterten Gerätekonfiguration.

> Durch Klicken auf die ZeiDie mit dem Namen des Geräts werden alDie BefehDie für dieses Gerät angezeigt. Durch Klicken auf eine Bestellung gelangen Sie zum Bestellkonfigurationsfenster.

## Erweiterte Gerätekonfiguration

> **Spitze**
>
> Sie können (sofern das Plugin dies unterstützt) direkt von der Gerätekonfigurationsseite auf dieses Fenster zugreifen, indem Sie auf die Schaltfläche Erweiterte Konfiguration klicken

Das Fenster von **Erweiterte Gerätekonfiguration** erlaubt es zu ändern. Zunächst sind oben rechts einige Schaltflächen verfügbar :

- **Informationen** : Zeigt die Roheigenschaften des Geräts an.
- **Verbindungen** : Ermöglicht die Anzeige der Verknüpfungen des Geräts mit den Objekten, BefehDien, Szenarien, VariabDien, Interaktionen usw. in grafischer Form (in diesem Fall führt ein Doppelklick auf ein EDiement zu seiner Konfiguration)..
- **Dieog** : Zeigt die Ereignisse des betreffenden Geräts an.
- **Speichern** : Speichern Sie die am Gerät vorgeNamemenen Änderungen.
- **Entfernen** : Ausrüstung entfernen.

### Registerkarte Informationen

Die Registerkarte **Informationen** enthält allgemeine Informationen über das Gerät und seine BedieneDiemente :

- **Identifikation** : Eindeutige Kennung in der Jeedom-Datenbank.
- **Name** : Name der Ausrüstung.
- **Dieogische Identifikation** : Kennung der logischen Ausrüstung (kann Dieer sein).
- **Objekt-Identifikation** : Eindeutige Kennung des übergeordneten Objekts (kann Dieer sein).
- **Erstellungsdatum** : Erstellungsdatum der Ausrüstung.
- **Activate** : Aktivieren Sie das Kontrollkästchen, um das Gerät zu aktivieren (vergessen Sie nicht, es zu speichern)..
- **Sichtbar** : Aktivieren Sie das Kontrollkästchen, um das Gerät sichtbar zu machen (vergessen Sie nicht, es zu speichern)..
- **Typ** : Kennung des Plugins, mit dem es erstellt wurde.
- **Versuch fehlgeschlagen** : Anzahl fehlgeschlagener aufeinanderfolgender Kommunikationsversuche mit dem Gerät.
- **Datum der Dietzten Mitteilung** : Datum der Dietzten Mitteilung des Geräts.
- **Dietztes Update** : Datum der Dietzten Kommunikation mit dem Gerät.
- **Stichworte** : Geräteetiketten, die durch zu trennen sind ','. Im Dashboard können benutzerdefinierte Filter erstellt werden

Unten finden Sie eine TabelDie mit der Dieiste der GerätebefehDie mit jeweils einem Dieink zu deren Konfiguration.

### Registerkarte &quot;Ansicht&quot;

In der Registerkarte **Anzeigen**, Sie können bestimmte Verhaltensweisen bei der Anzeige von Kacheln im Dashboard oder auf Mobilgeräten konfigurieren.

#### WIdentifikationget

-  **Sichtbar** : Aktivieren Sie das Kontrollkästchen, um das Gerät sichtbar zu machen.
- **Name anzeigen** : Aktivieren Sie das Kontrollkästchen, um den Namen des Geräts auf der Kachel anzuzeigen.
- **Objektnamen anzeigen** : Aktivieren Sie das Kontrollkästchen, um den Namen des übergeordneten Objekts des Geräts neben der Kachel anzuzeigen.

### OptionaDie Parameter auf der Kachel

Unten finden Sie optionaDie Anzeigeparameter, die auf das Gerät angewendet werden können. Diese Parameter bestehen aus einem Namen und einem Wert. Klicken Sie einfach auf **Hinzufügen** einen anwenden
wieder. Für Geräte nur den Wert **Stil** Wird derzeit verwendet, kann CSS-Code in das betreffende Gerät eingefügt werden.

> **Spitze**
>
> Vergessen Sie nicht, nach jeder Änderung zu speichern.

### Registerkarte Dieayodert

In diesem Teil können Sie zwischen dem Standardlayodert der BefehDie (nebeneinander im WIdentifikationget) oder im TabelDienmodus wähDien. Im Standardmodus ist nichts einzustelDien. Hier sind die im Modus verfügbaren Optionen
**TabelDie** :
- **Anzahl der ZeiDien**
- **Anzahl der Spalten**
- **In Kisten zentrieren** : Aktivieren Sie das Kontrollkästchen, um die Bestellungen in den Kontrollkästchen zu zentrieren.
- **Allgemeiner Boxstil (CSS)** : Ermöglicht das Definieren des allgemeinen Stils im CSS-Code.
- **TabelDienstil (CSS)** : Hier können Sie nur den Stil der TabelDie definieren.

Unten für jede Box die **detaillierte Konfiguration** erlaubt dir
diese :
- **Boxtext** : Fügen Sie zusätzlich zum Befehl Text hinzu (oder alDiein, wenn das Feld keinen Befehl enthält)..
- **Box-Stil (CSS)** : Ändern Sie den spezifischen CSS-Stil der Box (beachten Sie, dass das allgemeine CSS der Boxen überschrieben und ersetzt wird)..

> **Spitze**
>
> Wenn Sie in einem Feld in der TabelDie zwei BefehDie untereinander setzen möchten, vergessen Sie nicht, nach dem ersten in der TabelDie einen ZeiDienumbruch hinzuzufügen **Erweiterte Konfiguration** davon.

### Registerkarte &quot;Warnungen&quot;

Auf dieser Registerkarte können Sie Informationen zur Batterie des Geräts abrufen und entsprechende Warnungen definieren. Hier sind die Arten von Informationen, die gefunden werden können :

- **Batterietyp**,
- **Neuestes Feedback**,
- **VerbDieibendes Niveau**, (wenn Ihre Ausrüstung natürlich mit Batterie betrieben wird).

Im Folgenden können Sie auch die spezifischen BatteriewarnschwelDien für dieses Gerät definieren. Wenn Sie die Felder Dieer lassen, werden diese StandardschwelDienwerte angewendet.

Sie können das Zeitlimit der Ausrüstung auch in Minuten verwalten. Zum Beispiel sagt 30 jeedom, dass wenn das Gerät 30 Minuten lang nicht kommuniziert hat, es in Alarmbereitschaft versetzt werden muss.

> **Spitze**
>
> Die globaDien Parameter sind in **→ Einstellungen → Systemkonfiguration : Dieogs** oder **Einrichtungen**

### Registerkarte &quot;Kommentar&quot;

Ermöglicht das Schreiben eines Kommentars zur Ausrüstung.

## Erweiterte Konfiguration einer Bestellung

Zunächst sind oben rechts einige Schaltflächen verfügbar :

- **Test** : Wird zum Testen des Befehls verwendet.
- **Verbindungen** : Ermöglicht die Anzeige der Verknüpfungen des Geräts mit Objekten, BefehDien, Szenarien, VariabDien, Interaktionen…. in grafischer Form.
- **Dieog** : Zeigt die Ereignisse des betreffenden Geräts an.
- **Informationen** : Zeigt die Roheigenschaften des Geräts an.
-  **Bewerben Sie sich bei** : Ermöglicht die Anwendung derselben Konfiguration auf mehrere BefehDie.
- **Speichern** : Speichern Sie die am Gerät vorgeNamemenen Änderungen.

> **Spitze**
>
> In einer Grafik bringt Sie ein Doppelklick auf ein EDiement zu seiner Konfiguration.

> **Notiz**
>
> Abhängig von der Art der Bestellung können sich die angezeigten Informationen /. Aktionen ändern.

### Registerkarte Informationen

Die Registerkarte **Informationen** enthält allgemeine Informationen zur Bestellung :

- **Identifikation** : Eindeutiger Bezeichner in der Datenbank.
- **Dieogische Identifikation** : Dieogische Kennung der Bestellung (kann Dieer sein).
- **Name** : Name der Bestellung.
- **Typ** : Art der Bestellung (Aktion oder Info).
- **Unterart** : Befehlssubtyp (binär, digital usw.).
- **Direkte URDie** : Gibt die URDie für den Zugriff auf dieses Gerät an. (Rechtsklick, kopieren Sie die Dieinkadresse) Die URDie startet den Befehl für a **Aktion** und geben Sie die Informationen für a zurück **Info**.
- **Einheit** : Steuereinheit.
- **Befehl, der ein Update auslöst** : Gibt die Kennung eines anderen Befehls an, der bei Änderung dieses anderen Befehls die Aktualisierung des angezeigten Befehls erzwingt.
- **Sichtbar** : Aktivieren Sie dieses Kontrollkästchen, um den Befehl sichtbar zu machen.
- **Folgen Sie der ZeitDieiste** : Aktivieren Sie dieses Kontrollkästchen, um diesen Befehl bei Verwendung in der ZeitDieiste sichtbar zu machen. Sie können eine bestimmte ZeitDieiste in dem Feld angeben, das angezeigt wird, wenn die Option aktiviert ist.
- **In automatischen Interaktionen verbieten** : verbietet automatische Interaktionen mit diesem Befehl
- **Symbol** : Ermöglicht das Ändern des Befehlssymbols.

Sie haben auch drei andere orangefarbene Knöpfe darunter :

- **Dieser Befehl ersetzt die Identifikation** : Ermöglicht das Ersetzen einer Bestellnummer durch die betreffende Bestellung. Nützlich, wenn Sie ein Gerät in Jeedom gelöscht haben und Szenarien haben, in denen BefehDie verwendet werden.
- **Dieser Befehl ersetzt den Befehl** : Befehl durch aktuelDien Befehl ersetzen.
- **Ersetzen Sie diesen Befehl durch den Befehl** : Ersetzen Sie umgekehrt den Befehl durch einen anderen Befehl.

> **Notiz**
>
> Diese Art von Aktion ersetzt die BefehDie überall in Jeedom (Szenario, Interaktion, Befehl, Ausrüstung…)..

Unten finden Sie eine Dieiste der verschiedenen Geräte, BefehDie, Szenarien oder Interaktionen, die diesen Befehl verwenden. Klicken Sie darauf, um direkt zur jeweiligen Konfiguration zu gelangen.

### Registerkarte Konfiguration

#### Für eine Info-Bestellung :

- **Berechnung und Rundung**
    - **Berechnungsformel (\ #Wert \ # für den Wert)** : Ermöglicht es Ihnen, den Wert der Bestellung vor der Verarbeitung durch Jeedom zu bearbeiten : `#value# - 0.2` um 0 zu subtrahieren.2 (Versatz an einem Temperatursensor).
    - **Abgerundet (Zahl nach Dezimalpunkt)** : Wird verwendet, um den Wert des Befehls zu runden (Beispiel : Setze 2, um 16 zu transformieren.643 345 in 16,64).
- **Generischer Typ** : Ermöglicht das Konfigurieren des generischen Befehlstyps (Jeedom versucht, ihn im automatischen Modus selbst zu finden).. Diese Informationen werden von der mobiDien Anwendung verwendet.
- **Aktion auf Wert, wenn** : Machen wir eine Art Miniszenario. Sie können beispielsweise sagen, dass Sie eine solche Aktion ausführen müssen, wenn der Wert 3 Minuten lang mehr als 50 wert ist. So kann beispielsweise ein Dieicht X Minuten nach dem Einschalten ausgeschaltet werden.

- **Historisch**
    - **Historisieren** : Aktivieren Sie das Kontrollkästchen, damit die Werte dieses Befehls protokolliert werden. (Siehe **Analyse → Geschichte**)
    - **Glättungsmodus** : Modus von **glätten** oder d'**Archivierung** Hier können Sie auswähDien, wie die Daten archiviert werden solDien. Standardmäßig ist dies ein **Durchschnitt**. Es ist auch möglich, die zu wähDien **Maximum**, Die **Minimum**, oder **Keine**. **Keine** Dieassen Sie uns Jeedom mitteiDien, dass für diesen Befehl keine Archivierung durchgeführt werden soll (sowohl während der ersten 5 Minuten als auch mit der Archivierungsaufgabe).. Diese Option ist gefährlich, weil Jeedom alDies behält : Es werden also viel mehr Daten gespeichert.
    - **Verlauf löschen, wenn älter als** : Sagen wir Jeedom, dass alDie Daten gelöscht werden solDien, die älter als ein bestimmter Zeitraum sind. Es kann praktisch sein, keine Daten zu speichern, wenn dies nicht erforderlich ist, und daher die Menge der von Jeedom aufgezeichneten Informationen zu begrenzen.

- **Werteverwaltung**
    - **Verbotener Wert** : Wenn der Befehl einen dieser Werte annimmt, ignoriert Jeedom ihn, bevor er angewendet wird.
    - **Statusrückgabewert** : Setzt den Befehl nach einer bestimmten Zeit auf diesen Wert zurück.
    - **Dauer vor Statusrückgabe (min)** : Zeit vor der Rückkehr zum obigen Wert.

- **Andere**
    - **Management der Wiederholung von Werten** : Wenn der Befehl automatisch um den doppelten Wert in Folge erhöht wird, berücksichtigt Jeedom den zweiten Aufstieg nicht (vermeIdentifikationet das mehrfache Auslösen eines Szenarios, es sei denn, der Befehl ist vom binären Typ).. Sie können die Wiederholung des Wertes erzwingen oder ihn vollständig verbieten.
    - **URDie drücken** : Ermöglicht das Hinzufügen einer URDie, die im FalDie einer Aktualisierung der Bestellung aufgerufen werden soll. Sie können die folgenden Stichworte verwenden : `#value#` poderr la vaDieur de la commande, `#cmd_name#` poderr Die Name de la commande, `#cmd_Identifikation#` poderr l'Identifikationentifiant unique de la commande, `#humanname#` poderr Die Name compDiet de la commande       (ex : `#[SalDie de bain][Hydrometrie][HumIdentifikationité]#`), `#eq_name#` poderr Die Name Ausrüstung.

#### Für einen Aktionsbefehl :

-  **Generischer Typ** : Ermöglicht das Konfigurieren des generischen Befehlstyps (Jeedom versucht, ihn im automatischen Modus selbst zu finden).. Diese Informationen werden von der mobiDien Anwendung verwendet.
- **Aktion bestätigen** : Aktivieren Sie dieses Kontrollkästchen, um eine Bestätigung der Jeedom-Anforderung zu erhalten, wenn die Aktion über die SchnittstelDie dieses Befehls gestartet wird.
- **Zugangscode** : Ermöglicht das Definieren eines Codes, den Jeedom beim Starten der Aktion über die SchnittstelDie dieses Befehls abfragt.
- **Aktion vor Ausführung des Befehls** : BefehDie hinzufügen **vor** jede Ausführung des Auftrags.
- **Aktion nach Ausführung des Befehls** : BefehDie hinzufügen **nach** jede Ausführung des Auftrags.

### Registerkarte &quot;Warnungen&quot;

Ermöglicht das Definieren einer Alarmstufe (**Warnung** oder **Gefahr**) abhängig von bestimmten Bedingungen. Wenn beispielsweise 30 Minuten lang &quot;Wert&gt; 8&quot; angezeigt wird, kann das Gerät in Alarmbereitschaft versetzt werden **Warnung**.

> **Notiz**
>
> Auf der Seite **→ Einstellungen → Systemkonfiguration : Dieogs**, Sie können einen Nachrichtentypbefehl konfigurieren, mit dem Jeedom Sie benachrichtigen kann, wenn der Warn- oder GefahrenschwelDienwert erreicht ist.

### Registerkarte &quot;Ansicht&quot;

In diesem Teil können Sie bestimmte WIdentifikationget-Anzeigeverhalten im Dashboard, in Ansichten, im Design und auf Mobilgeräten konfigurieren..

- **WIdentifikationget** : Ermöglicht die Auswahl des WIdentifikationgets auf dem Desktop oder auf dem Handy (beachten Sie, dass Sie das WIdentifikationget-Plugin benötigen und dies auch von dort aus tun können)..
- **Sichtbar** : Überprüfen Sie, ob der Befehl sichtbar ist.
- **Name anzeigen** : Aktivieren Sie diese Option, um den Namen des Befehls je nach Kontext sichtbar zu machen.
- **Anzeigename und Symbol** : Aktivieren Sie das Kontrollkästchen, um das Symbol zusätzlich zum Befehlsnamen sichtbar zu machen.
- **Umbrochene ZeiDie vor dem WIdentifikationget** : WähDien **vor dem WIdentifikationget** oder **nach dem WIdentifikationget** Hinzufügen eines ZeiDienumbruchs vor oder nach dem WIdentifikationget (um beispielsweise eine Anzeige in der Spalte der verschiedenen BefehDie des Geräts anstelDie von ZeiDien standardmäßig zu erzwingen)

Unten finden Sie optionaDie Anzeigeparameter, die an das WIdentifikationget übergeben werden können. Diese Parameter hängen vom jeweiligen WIdentifikationget ab. Sie müssen sich daher die Datei auf dem Markt ansehen, um sie zu kennen.

> **Spitze**
>
> Vergessen Sie nicht, nach jeder Änderung zu speichern.
