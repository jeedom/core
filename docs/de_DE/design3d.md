# 3D-Design
**Startseite → Design3D**

Auf dieser Seite können Sie eine 3D-Ansicht Ihres Zuhauses erstellen, die je nach Status der verschiedenen Daten Ihres Hausautomationssystems reagieren kann.


> **Tipp**
>
> Über das Untermenü kann man direkt zu einem 3D-Entwurf wechseln.

## Import des 3D-Modells

> **WICHTIG**
>
> Sie können Ihr 3D-Modell nicht direkt in Jeedom erstellen, sondern müssen dafür eine Drittanbieter-Software verwenden. Wir empfehlen das sehr gute SweetHome3d (http://www.sweethome3d.com/fr/).

Sobald Sie Ihr 3D-Modell erstellt haben, müssen Sie es im OBJ-Format exportieren. Wenn Sie SweetHome3D verwenden, erfolgt dies über das Menü „3D-Ansicht“ und dann „Als OBJ exportieren“. Anschließend müssen Sie alle erzeugten Dateien in eine ZIP-Datei packen (aufgrund der Texturen kann es viele Dateien geben).

> **WICHTIG**
>
> Die Dateien müssen sich im Stammverzeichnis der ZIP-Datei befinden und dürfen nicht in einem Unterordner liegen.

> **ACHTUNG**
>
> Ein 3D-Modell ist ziemlich groß (es kann mehrere hundert MB umfassen). Je größer es ist, desto länger dauert das Rendern in Jeedom.

Sobald Sie Ihr 3D-Modell exportiert haben, müssen Sie in Jeedom ein neues 3D-Design erstellen. Wechseln Sie dazu in den Bearbeitungsmodus, indem Sie auf den kleinen Bleistift rechts klicken, klicken Sie anschließend auf das „+“, geben Sie diesem neuen 3D-Design einen Namen und bestätigen Sie die Eingabe.

Jeedom wechselt automatisch zum neuen 3D-Design. Sie müssen erneut in den Bearbeitungsmodus wechseln und auf die kleinen Zahnräder klicken.

Auf diesem Bildschirm haben Sie folgende Möglichkeiten:

- Den Namen Ihres Designs ändern
- Zugangscode hinzufügen
- Ein Symbol auswählen
- 3D-Modell importieren

Klicken Sie auf die Schaltfläche „Senden“ unter „3D-Modell“ und wählen Sie Ihre ZIP-Datei aus.

> **ACHTUNG**
>
> Jeedom erlaubt den Import einer Datei mit einer maximalen Größe von 150 MB!

> **ACHTUNG**
>
> Es muss sich zwingend um eine ZIP-Datei handeln.

> **Tipp**
>
> Sobald der Import der Datei abgeschlossen ist (dies kann je nach Dateigröße eine Weile dauern), müssen Sie die Seite aktualisieren, um das Ergebnis zu sehen (F5).


## Konfiguration der Komponenten

> **WICHTIG**
>
> Die Konfiguration kann nur im Bearbeitungsmodus vorgenommen werden.

Um ein Element im 3D-Design zu konfigurieren, doppelklicken Sie auf das Element, das Sie konfigurieren möchten. Daraufhin wird ein Fenster angezeigt, in dem Sie folgende Möglichkeiten haben:

- Geben Sie einen Linktyp an (derzeit ist nur „Ausstattung“ verfügbar)
- Geben Sie den Link zum betreffenden Element ein. Derzeit können Sie hier nur einen Link zu einem Gerät einfügen. Dadurch wird beim Anklicken des Elements das Gerät angezeigt.
- Spezifikation festlegen: Hier gibt es mehrere Optionen, die wir gleich im Folgenden betrachten werden. Damit lässt sich der Gerätetyp und somit die Anzeige der Informationen festlegen.

### Beleuchtung

- Status: Der Befehl zum Lichtstatus kann ein binärer Wert (0 oder 1), ein numerischer Wert (von 0 bis 100 %) oder eine Farbe sein
- Leistung: Nennleistung der Glühbirne (Achtung: Diese Angabe entspricht möglicherweise nicht der tatsächlichen Leistung)

### Text

- Text: Anzuzeigender Text (Sie können hier Befehle eingeben; der Text wird bei einer Änderung automatisch aktualisiert)
- Textgröße
- Textfarbe
- Transparenz des Textes: von 0 (unsichtbar) bis 1 (sichtbar)
- Hintergrundfarbe
- Hintergrundtransparenz: von 0 (unsichtbar) bis 1 (sichtbar)
- Farbe des Rahmens
- Transparenz des Rahmens: von 0 (unsichtbar) bis 1 (sichtbar)
- Abstand oberhalb des Objekts: Hiermit kann der Abstand des Textes zum Element festgelegt werden

### Tür/Fenster

#### Tür/Fenster

- Status: Status der Tür/des Fensters, 1 = geschlossen und 0 = geöffnet
- Rotation
	- Aktivieren: Aktiviert die Drehung der Tür/des Fensters beim Öffnen
	- Öffnen: Am besten probieren Sie es aus, damit es zu Ihrer Tür/Ihrem Fenster passt
- Übersetzung
	- Aktivieren: Aktiviert die Verschiebung beim Öffnen (Typ Schiebefenster/-tür)
	- Richtung: Richtung, in die sich die Tür/das Fenster bewegen soll (Sie haben die Optionen „oben“, „unten“, „rechts“ und „links“)
	- Wiederholen: Standardmäßig bewegt sich die Tür/das Fenster um das Einfache ihrer/seiner Abmessung in die angegebene Richtung, Sie können diesen Wert jedoch erhöhen
- Ausblenden, wenn die Tür/das Fenster geöffnet ist
	- Aktivieren: Blendet das Element aus, wenn die Tür/das Fenster geöffnet ist
- Farbe
	- Farbe bei geöffnetem Zustand: Wenn dieses Kontrollkästchen aktiviert ist, wird diese Farbe angezeigt, wenn die Tür/das Fenster geöffnet ist
	- Farbe bei geschlossenem Zustand: Wenn dieses Kontrollkästchen aktiviert ist, nimmt das Element diese Farbe an, wenn die Tür/das Fenster geschlossen ist

#### Rollladen

- Status: Status des Rollladens, 0 = offen, andere Werte = geschlossen
- Ausblenden, wenn der Rollladen geöffnet ist
	- Aktivieren: Blendet das Element aus, wenn der Reiter geöffnet ist
- Farbe
	- Farbe bei geschlossenem Fenster: Wenn dieses Kontrollkästchen aktiviert ist, nimmt das Element diese Farbe an, wenn das Fenster geschlossen ist

### Bedingte Farbe

Wenn die Bedingung erfüllt ist, wird das Element in der ausgewählten Farbe angezeigt. Sie können beliebig viele Farben und Bedingungen festlegen.

> **Tipp**
>
> Die Bedingungen werden der Reihe nach ausgewertet; die erste, die wahr ist, wird berücksichtigt, die folgenden werden daher nicht ausgewertet.
