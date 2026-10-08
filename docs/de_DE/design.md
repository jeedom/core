# Design
**Startseite → Design**

Auf dieser Seite können Sie die Anzeige Ihrer gesamten Hausautomation sehr detailliert konfigurieren.
Das erfordert zwar Zeit, aber die einzige Grenze ist Ihre Vorstellungskraft.

> **Tipp**
>
> Über das Untermenü kann man direkt zu einem Design springen.

> **Wichtig**
>
> Alle Aktionen werden durch einen Rechtsklick auf diese Seite ausgeführt. Achten Sie darauf, dass Sie dies innerhalb des Layouts tun. Bei der Erstellung sollten Sie den Rechtsklick daher in der Mitte der Seite ausführen (um sicherzustellen, dass Sie sich im Layout befinden).

Im Menü (Rechtsklick) finden wir folgende Aktionen:

- **Designs**: Zeigt die Liste Ihrer Designs an und ermöglicht den Zugriff darauf.
- **Bearbeiten**: Wechselt in den Bearbeitungsmodus.
- **Vollbild**: Ermöglicht die Nutzung der gesamten Webseite, wodurch das Jeedom-Menü oben ausgeblendet wird.
- **Diagramm hinzufügen**: Ermöglicht das Hinzufügen eines Diagramms.
- **Text/HTML hinzufügen**: Ermöglicht das Hinzufügen von Text oder HTML-/JavaScript-Code.
- **Szenario hinzufügen**: Ermöglicht das Hinzufügen eines Szenarios.
- **Link hinzufügen**
    - **Zu einer Ansicht**: Ermöglicht das Hinzufügen eines Links zu einer Ansicht.
    - **Zu einem Design**: Ermöglicht es, einen Link zu einem anderen Design hinzuzufügen.
- **Gerät hinzufügen**: Ermöglicht das Hinzufügen eines Geräts.
- **Befehl hinzufügen**: Ermöglicht das Hinzufügen eines Befehls.
- **Bild/Kamera hinzufügen**: Ermöglicht das Hinzufügen eines Bildes oder eines Kamerastreams.
- **Zone hinzufügen**: Ermöglicht das Hinzufügen einer transparenten, anklickbaren Zone, die bei einem Klick eine Reihe von Aktionen ausführen kann (abhängig oder unabhängig vom Status eines anderen Befehls).
- **Zusammenfassung hinzufügen**: Fügt die Informationen einer Objekt- oder allgemeinen Zusammenfassung hinzu.
- **Anzeige**
    - **Keine**: Es wird kein Raster angezeigt.
    - **10x10**: Zeigt ein Raster mit 10 x 10 Feldern an.
    - **15x15**: Zeigt ein Raster mit 15 x 15 Feldern an.
    - **20x20**: Zeigt ein Raster mit 20 x 20 Feldern an.
    - **Elemente magnetisieren**: Fügt eine Magnetisierung zwischen den Elementen hinzu, damit sie sich leichter zusammenkleben lassen.
    - **An Raster ausrichten**: Richtet die Elemente magnetisch am Raster aus (Achtung: Je nach Zoomstufe des Elements kann diese Funktion mehr oder weniger gut funktionieren).
    - **Hervorhebung der Elemente ausblenden**: Blendet die Hervorhebung um die Elemente herum aus.
- **Design löschen**: Löscht das Design.
- **Design erstellen**: Hiermit können Sie ein neues Design hinzufügen.
- **Design duplizieren**: Dupliziert das aktuelle Design.
- **Design konfigurieren**: Zugriff auf die Design-Konfiguration.
- **Speichern**: Ermöglicht das Speichern des Designs (Achtung: Bei bestimmten Aktionen erfolgt auch eine automatische Speicherung).

> **Wichtig**
>
> Die Konfiguration der Designelemente erfolgt durch einen Klick darauf.

## Design-Konfiguration

Hier finden Sie:

- **Allgemeines**
    - **Name**: Der Name Ihres Designs.
    - **Position**: Die Position des Designs im Menü. Ermöglicht die Sortierung der Designs.
    - **Transparenter Hintergrund**: Macht den Hintergrund transparent. Achtung: Wenn das Kontrollkästchen aktiviert ist, wird die Hintergrundfarbe nicht verwendet.
    - **Hintergrundfarbe**: Hintergrundfarbe des Designs.
    - **Zugangscode**: Zugangscode für Ihr Design (wenn leer, wird kein Code abgefragt).
    - **Symbol**: Ein Symbol für diese Option (wird im Menü zur Designauswahl angezeigt).
    - **Bild**
        - **Senden**: Ermöglicht das Hinzufügen eines Hintergrundbildes zum Design.
        - **Bild löschen**: Hiermit können Sie das Bild löschen.
- **Größen**
    - **Größe (BxH)**: Hier können Sie die Größe Ihres Designs in Pixeln festlegen.

## Allgemeine Konfiguration der Komponenten

> **Hinweis**
>
> Je nach Art des Elements können sich die Optionen ändern.

### Allgemeine Anzeigeeinstellungen

- **Tiefe**: Hier können Sie die Tiefe einstellen
- **Position X (%)**: Horizontale Koordinate des Elements.
- **Y-Position (%)**: Vertikale Koordinate des Elements.
- **Breite (px)**: Breite des Elements in Pixeln.
- **Höhe (px)**: Höhe des Elements in Pixeln.

### Löschen

Ermöglicht das Löschen des Elements

### Duplizieren

Ermöglicht das Duplizieren des Elements

### Sperren

Ermöglicht es, das Element zu sperren, sodass es nicht mehr verschoben oder in der Größe verändert werden kann.

## Grafik

### Spezifische Anzeigeeinstellungen

- **Zeitraum**: Hier können Sie den anzuzeigenden Zeitraum auswählen
- **Legende anzeigen**: Zeigt die Legende an.
- **Browser anzeigen**: Zeigt den Browser an (zweites, helleres Diagramm unterhalb des ersten).
- **Zeitraumauswahl anzeigen**: Zeigt die Zeitraumauswahl oben links an.
- **Bildlaufleiste anzeigen**: Zeigt die Bildlaufleiste an.
- **Transparenter Hintergrund**: Macht den Hintergrund transparent.
- **Rahmen**: Ermöglicht das Hinzufügen eines Rahmens. Bitte beachten Sie, dass die Syntax HTML ist (Achtung: Es muss eine CSS-Syntax verwendet werden, zum Beispiel: solid 1px black).

### Erweiterte Einstellungen

Ermöglicht die Auswahl der Befehle, die grafisch dargestellt werden sollen.

## Text/html

### Spezifische Anzeigeeinstellungen

- **Symbol**: Symbol, das vor dem Namen des Designs angezeigt wird.
- **Hintergrundfarbe**: Hiermit können Sie die Hintergrundfarbe ändern oder den Hintergrund transparent machen. Vergessen Sie nicht, die Option „Standard“ auf „NEIN“ zu setzen.
- **Textfarbe**: Hiermit können Sie die Farbe der Symbole und Texte ändern (achten Sie darauf, „Standard“ auf „Nein“ zu setzen).
- **Ecken abrunden**: Ermöglicht das Abrunden von Ecken (vergessen Sie nicht, einen Prozentsatz anzugeben, z. B. 50 %).
- **Rahmen**: Ermöglicht das Hinzufügen eines Rahmens. Achtung: Die Syntax ist HTML (es muss eine CSS-Syntax verwendet werden, zum Beispiel: solid 1px black).
- **Schriftgröße**: Hiermit können Sie die Schriftgröße ändern (z. B. 50 % – das Prozentzeichen % muss unbedingt angegeben werden).
- **Textausrichtung**: Hier können Sie die Textausrichtung (links/rechts/zentriert) auswählen.
- **Fett**: macht den Text fett.
- **Text**: HTML-Code, der im Element enthalten sein soll.

> **Wichtig**
>
> Wenn Sie HTML-Code (insbesondere JavaScript) einfügen, sollten Sie diesen unbedingt vorher sorgfältig überprüfen, da ein Fehler darin oder das Überschreiben einer Jeedom-Komponente das Design komplett zum Absturz bringen kann und es dann nur noch die Möglichkeit gibt, den Code direkt in der Datenbank zu löschen.

## Szenario

*Keine spezifischen Anzeigeeinstellungen*

## Link

### Spezifische Anzeigeeinstellungen

- **Name**: Name des Links (angezeigter Text).
- **Link**: Link zum jeweiligen Design oder zur jeweiligen Ansicht.
- **Hintergrundfarbe**: Hiermit können Sie die Hintergrundfarbe ändern oder den Hintergrund transparent machen. Vergessen Sie nicht, die Option „Standard“ auf „NEIN“ zu setzen.
- **Textfarbe**: Hiermit können Sie die Farbe der Symbole und Texte ändern (achten Sie darauf, „Standard“ auf „Nein“ zu setzen).
- **Ecken abrunden (% nicht vergessen, z. B. 50 %)**: Ermöglicht das Abrunden der Ecken. Vergessen Sie nicht, den Prozentsatz anzugeben.
- **Rahmen (Achtung: CSS-Syntax, z. B.: solid 1px black)**: Ermöglicht das Hinzufügen eines Rahmens. Achtung: Die Syntax ist HTML.
- **Schriftgröße (z. B. 50 %, das Prozentzeichen % muss unbedingt angegeben werden)**: Hiermit können Sie die Schriftgröße ändern.
- **Textausrichtung**: Hier können Sie die Ausrichtung des Textes auswählen (links/rechts/zentriert).
- **Fett**: Stellt den Text fett dar.

## Ausstattung

### Spezifische Anzeigeeinstellungen

- **Objektname anzeigen**: Aktivieren Sie dieses Kontrollkästchen, um den Namen des übergeordneten Objekts des Geräts anzuzeigen.
- **Namen ausblenden**: Aktivieren Sie dieses Kontrollkästchen, um den Namen des Geräts auszublenden.
- **Hintergrundfarbe**: Hier können Sie eine benutzerdefinierte Hintergrundfarbe auswählen, das Gerät mit transparentem Hintergrund anzeigen lassen oder die Standardfarbe verwenden.
- **Textfarbe**: Hier können Sie eine benutzerdefinierte Hintergrundfarbe auswählen oder die Standardfarbe verwenden.
- **Abrundung**: Pixelwert für die Abrundung der Ecken der Geräte-Kachel.
- **Rahmen**: CSS-Definition für einen Rahmen der Geräte-Kachel. Beispiel: 1px solid black.
- **Deckkraft**: Deckkraft der Geräte-Kachel, zwischen 0 und 1. Achtung: Es muss eine Hintergrundfarbe festgelegt werden.
- **Benutzerdefiniertes CSS**: CSS-Regeln, die auf das Gerät angewendet werden sollen.
- **Benutzerdefiniertes CSS anwenden auf**: CSS-Selektor, auf den das benutzerdefinierte CSS angewendet werden soll.

### Steuerungen

Über die Liste der am Gerät vorhandenen Steuerelemente können Sie für jedes Steuerelement Folgendes tun:
- Den Namen des Befehls ausblenden.
- Befehl ausblenden.
- Die Schaltfläche mit transparentem Hintergrund anzeigen.

### Erweiterte Einstellungen

Zeigt das Fenster für die erweiterte Konfiguration des Geräts an (siehe Dokumentation **Zusammenfassung zur Hausautomation**).

## Bestellung

*Keine spezifischen Anzeigeeinstellungen*

### Erweiterte Einstellungen

Zeigt das Fenster für die erweiterte Konfiguration des Geräts an (siehe Dokumentation **Zusammenfassung zur Hausautomation**).

## Bild/Kamera

### Spezifische Anzeigeeinstellungen

- **Anzeigen**: Legt fest, was angezeigt werden soll: ein Standbild oder ein Kamerabild.
- **Bild**: Ermöglicht das Senden des ausgewählten Bildes (sofern Sie ein Bild ausgewählt haben).
- **Kamera**: Anzuzeigende Kamera (sofern Sie eine Kamera ausgewählt haben).

## Bereich

### Spezifische Anzeigeeinstellungen

- **Zonentyp**: Hier wählen Sie den Zonentyp aus: Einfaches Makro, Binäres Makro oder Widget bei Mauszeigerüberfahrt.

### Einfaches Makro

In diesem Modus führt ein Klick auf das Feld eine oder mehrere Aktionen aus. Sie müssen hier lediglich die Liste der Aktionen angeben, die beim Klicken auf das Feld ausgeführt werden sollen.

### Binäres Makro

In diesem Modus führt Jeedom die Aktion(en) „Ein“ oder „Aus“ entsprechend dem von Ihnen angegebenen Befehlsstatus aus. Beispiel: Wenn der Befehlswert 0 ist, führt Jeedom die Aktion(en) „Ein“ aus; andernfalls führt es die Aktion(en) „Aus“ aus.

- **Binäre Information**: Befehl, der den zu überprüfenden Zustand angibt, um über die auszuführende Aktion zu entscheiden (Ein oder Aus).

Geben Sie unten einfach die Aktionen ein, die beim Einschalten und beim Ausschalten ausgeführt werden sollen.

### Widget beim Überfahren mit der Maus

In diesem Modus wird das entsprechende Widget angezeigt, wenn Sie mit der Maus über den Jeedom-Bereich fahren oder darauf klicken.

- **Ausstattung**: Widget, das beim Darüberfahren mit der Maus oder beim Klicken angezeigt wird.
- **Beim Überfahren anzeigen**: Wenn diese Option aktiviert ist, wird das Widget beim Überfahren angezeigt.
- **Bei Klick anzeigen**: Wenn dieses Kontrollkästchen aktiviert ist, wird das Widget beim Klicken angezeigt.
- **Position**: Hier können Sie den Ort auswählen, an dem das Widget angezeigt werden soll (standardmäßig unten rechts).

## Zusammenfassung

### Spezifische Anzeigeeinstellungen

- **Link**: Hier können Sie angeben, welche Zusammenfassung angezeigt werden soll (allgemein für den Gesamtüberblick, andernfalls geben Sie das Objekt an).
- **Hintergrundfarbe**: Hiermit können Sie die Hintergrundfarbe ändern oder den Hintergrund transparent machen. Vergessen Sie nicht, die Option „Standard“ auf „NEIN“ zu setzen.
- **Textfarbe**: Hiermit können Sie die Farbe der Symbole und Texte ändern (achten Sie darauf, „Standard“ auf „Nein“ zu setzen).
- **Ecken abrunden (% nicht vergessen, z. B. 50 %)**: Ermöglicht das Abrunden der Ecken. Vergessen Sie nicht, den Prozentsatz anzugeben.
- **Rahmen (Achtung: CSS-Syntax, z. B.: solid 1px black)**: Ermöglicht das Hinzufügen eines Rahmens. Achtung: Die Syntax ist HTML.
- **Schriftgröße (z. B. 50 %, das Prozentzeichen % muss unbedingt angegeben werden)**: Hiermit können Sie die Schriftgröße ändern.
- **Fett**: Stellt den Text fett dar.

## Häufig gestellte Fragen

>**Ich kann mein Design nicht mehr bearbeiten**
>Wenn Sie ein Widget oder ein Bild eingefügt haben, das fast die gesamte Fläche einnimmt, müssen Sie außerhalb des Widgets oder des Bildes klicken, um das Kontextmenü per Rechtsklick aufzurufen.

>**Ein Design löschen, das nicht mehr funktioniert**
>Gehen Sie im Administrationsbereich unter „OS/DB“ den Befehl „select * from planHeader“ aus, notieren Sie sich die ID des betreffenden Designs und führen Sie anschließend die Befehle „delete from planHeader where id=#TODO#“ sowie „delete from plan where planHeader_id=#todo#“ aus, wobei Sie #TODO# durch die zuvor ermittelte ID des Designs ersetzen müssen.
