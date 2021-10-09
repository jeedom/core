# Gerätearten
**Werkzeuge → Gerätetypen**

Die Sensoren und Aktoren in Jeedom werden von Plugins verwaltet, die Geräte mit Befehlen erstellen *Die Info* (Sensor) oder *Handlung* (Antrieb). Dadurch ist es dann möglich, Aktionen basierend auf der Änderung bestimmter Sensoren auszulösen, wie z.B. das Einschalten eines Lichts bei Bewegungserkennung. Aber der Jeedom Core und Plugins wie *Handy, Mobiltelefon*, *Homebridge*, *Google-Smarthome*, *Alexa* etc., weiß nicht was das für ein Gerät ist : Eine Steckdose, ein Licht, ein Rollladen usw.

Um dieses Problem zu lösen, insbesondere bei Sprachassistenten (*Mach das Zimmerlicht an*), Cote stellte die . vor **Generische Typen**, von diesen Plugins verwendet.

Die Konfiguration dieser generischen Typen kann direkt in bestimmten Plugins oder per Befehl in . erfolgen *Erweiterte Konfiguration* davon.

Auf dieser Seite können diese Generic Types direkt, direkter und einfacher konfiguriert werden und bietet sogar eine automatische Zuordnung nach korrekter Zuordnung der Geräte.

![Gerätearten](./images/coreGenerics.gif)

## Ausstattungsart

Diese Seite bietet Lagerung nach Gerätetyp : Steckdose, Licht, Shutter, Thermostat, Kamera usw. Zunächst werden die meisten Ihrer Geräte klassifiziert in **Geräte ohne Typ**. Um ihnen einen Typ zuzuweisen, können Sie sie entweder auf einen anderen Typ verschieben oder mit der rechten Maustaste auf das Gerät klicken, um es direkt zu verschieben.

> **Spitze**
>
> - Wenn du Ausrüstung im Spiel bewegst **Geräte ohne Typ**, Jeedom schlägt vor, generische Typen aus seinen Bestellungen zu entfernen.
> - Sie können mehrere Ausrüstungsgegenstände gleichzeitig bewegen, indem Sie die Kontrollkästchen links davon aktivieren.

## Befehlstyp

Sobald ein Ausrüstungsgegenstand richtig positioniert ist *Typ*, wenn Sie darauf klicken, gelangen Sie zur Liste der Bestellungen, die anders gefärbt sind, wenn es sich um a . handelt *Die Info* (Blau) oder a *Handlung* (Orange).

Durch Rechtsklick auf einen Auftrag können Sie ihm dann einen generischen Typ entsprechend den Spezifikationen dieses Auftrags zuweisen (Info-/Aktionstyp, Numerisch, Binärer Untertyp usw).

> **Spitze**
>
> - Das Kontextmenü der Befehle zeigt den Gerätetyp in Fettdruck an, ermöglicht aber dennoch die Zuweisung eines beliebigen generischen Typs eines beliebigen Gerätetyps.

Auf jedem Gerät haben Sie zwei Tasten :

- **Autotypen** : Diese Funktion öffnet ein Fenster, das Ihnen die passenden generischen Typen entsprechend der Art der Ausrüstung, den Besonderheiten des Auftrags und seines Namens anbietet. Sie können dann die Vorschläge anpassen und die Anwendung für bestimmte Befehle deaktivieren, bevor Sie sie akzeptieren oder nicht. Diese Funktion ist kompatibel mit der Auswahl durch Checkboxen.

- **Reset-Typen** : Diese Funktion entfernt generische Typen aus allen Ausrüstungsbefehlen.

> **Warnung**
>
> Vor dem Speichern werden keine Änderungen vorgenommen, mit der Schaltfläche oben rechts auf der Seite.