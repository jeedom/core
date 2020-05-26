# 3D Design
**Startseite → Design3D**

Auf dieser Seite können Sie eine 3D-Ansicht Ihres Hauses erstellen, die abhängig vom Status der verschiedenen Informationen in Ihrer Hausautomation reagieren kann.


> **Spitze**
>
> Dank des Untermenüs ist es möglich, direkt zu einem 3D-Design zu wechseln.

## 3D-Modell importieren

> **WICHTIG**
>
> Sie können Ihr 3D-Modell nicht direkt in Jeedom erstellen. Dies muss mit Software von Drittanbietern erfolgen. Nous recommandons le très bon SweetHome3d (http://www.sweethome3d.com/fr/).

Sobald Ihr 3D-Modell erstellt wurde, muss es im OBJ-Format exportiert werden. Wenn Sie SweetHome3d verwenden, erfolgt dies über das Menü "3D-Ansicht" und dann über "In OBJ-Format exportieren"". Nehmen Sie dann alle generierten Dateien und fügen Sie sie in eine Zip-Datei ein (aufgrund der Texturen können viele Dateien vorhanden sein).

> **WICHTIG**
>
> Die Dateien müssen sich im Stammverzeichnis der Zip-Datei befinden und dürfen sich nicht in einem Unterordner befinden.

> **VORSICHT**
>
> Ein 3D-Modell ist ziemlich beeindruckend (dies kann mehrere hundert Mo darstellen). Je größer es ist, desto länger ist die Renderzeit in Jeedom.

Nachdem Ihr 3D-Modell exportiert wurde, müssen Sie in Jeedom ein neues 3D-Design erstellen. Dazu müssen Sie in den Bearbeitungsmodus wechseln, indem Sie auf den kleinen Stift rechts klicken, dann auf das + klicken, diesem neuen 3D-Design einen Namen geben und dann validieren.

Jeedom wechselt automatisch zum neuen 3D-Design. Sie müssen in den Bearbeitungsmodus zurückkehren und auf die kleinen gekerbten Räder klicken.

Sie können von diesem Bildschirm aus :

- Ändern Sie den Namen Ihres Designs
- Fügen Sie einen Zugangscode hinzu
- Wählen Sie ein Symbol
- Importieren Sie Ihr 3D-Modell

Klicken Sie auf der Ebene &quot;3D-Modell&quot; auf die Schaltfläche &quot;Senden&quot; und wählen Sie Ihre Zip-Datei aus

> **VORSICHT**
>
> Jeedom autorisiert den Import einer Datei mit maximal 150 Monaten !

> **VORSICHT**
>
> Sie müssen eine Zip-Datei haben.

> **Spitze**
>
> Nachdem die Datei importiert wurde (sie kann je nach Größe der Datei sehr lang sein), müssen Sie die Seite aktualisieren, um das Ergebnis anzuzeigen (F5).


## Konfiguration von Elementen

> **WICHTIG**
>
> Die Konfiguration kann nur im Bearbeitungsmodus erfolgen.

Doppelklicken Sie auf das Element, das Sie konfigurieren möchten, um ein Element im 3D-Design zu konfigurieren. Dies zeigt ein Fenster an, in dem Sie können :

- Geben Sie einen Verbindungstyp an (derzeit ist nur Ausrüstung vorhanden)
- Geben Sie den Link zu dem betreffenden Element ein. Hier können Sie momentan nur einen Link zu einem Gerät setzen. Dies ermöglicht es beim Klicken auf den Gegenstand, die Ausrüstung aufzurufen
- Spezifität definieren: Es gibt einige, die wir gleich danach sehen werden. Dies ermöglicht es, den Gerätetyp und damit die Anzeige von Informationen festzulegen

### Licht

- Status : Die Lichtstatussteuerung kann binär (0 oder 1), digital (0 bis 100%) oder farbig sein
- Macht : Lampenleistung (bitte beachten Sie, dass dies möglicherweise nicht die Realität widerspiegelt))

### Texte

- Text : anzuzeigender Text (Sie können dort Befehle eingeben, der Text wird bei Änderung automatisch aktualisiert)
- Textgröße
- Textfarbe
- Texttransparenz : von 0 (unsichtbar) bis 1 (sichtbar))
- Hintergrundfarbe
- Hintergrundtransparenz : von 0 (unsichtbar) bis 1 (sichtbar))
- Randfarbe
- Grenztransparenz : von 0 (unsichtbar) bis 1 (sichtbar))
- Platz über dem Objekt : Ermöglicht die Angabe des Abstands des Texts zum Element

### Tür / Fenster

#### Tür / Fenster

- Zustand : Tür- / Fensterstatus, 1 geschlossen und 0 offen
- Rotation
	- Activate : Aktiviert die Drehung der Tür / des Fensters beim Öffnen
	- Öffnung : Am besten testen Sie, ob es zu Ihrer Tür / Ihrem Fenster passt
- Translation
	- Activate : aktiviert die Übersetzung beim Öffnen (Schiebetür- / Fenstertyp))
	- Bedeutung : Richtung, in die sich die Tür / das Fenster bewegen soll (Sie haben oben / unten / rechts / links)
	- Wiederholung : Standardmäßig bewegt sich die Tür / das Fenster einmal in der angegebenen Richtung, aber Sie können diesen Wert erhöhen
- Verstecken, wenn die Tür / das Fenster geöffnet ist
	- Activate : Blendet das Element aus, wenn die Tür / das Fenster geöffnet ist
- Couleur
	- Farbe öffnen : Wenn Sie das Kontrollkästchen aktivieren, nimmt das Element diese Farbe an, wenn die Tür / das Fenster geöffnet ist
	- Farbe geschlossen : Wenn Sie das Kontrollkästchen aktivieren, nimmt das Element diese Farbe an, wenn die Tür / das Fenster geschlossen ist

#### Volet

- Zustand : Verschlussstatus, 0 offen anderer Wert geschlossen
- Ausblenden, wenn der Verschluss geöffnet ist
	- Activate : Blenden Sie das Element aus, wenn der Verschluss geöffnet ist
- Couleur
	- Farbe geschlossen : Wenn diese Option aktiviert ist, nimmt das Element diese Farbe an, wenn der Verschluss geschlossen ist

### Bedingte Farbe

Wenn die Bedingung gültig ist, können Sie dem Element die ausgewählte Farbe geben. Sie können so viele Farben / Bedingungen festlegen, wie Sie möchten.

> **Spitze**
>
> Die Bedingungen werden in der Reihenfolge ausgewertet, die erste, die wahr ist, wird genommen, die folgenden werden daher nicht bewertet
