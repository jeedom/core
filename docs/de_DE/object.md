# Objets
**Werkzeuge → Objekte**

Die **Objekte** Mit dieser Option können Sie die Baumstruktur Ihrer Hausautomation definieren.

Alle von Ihnen erstellten Geräte müssen zu einem Objekt gehören und sind daher leichter zu identifizieren. Wir sagen dann, dass das Objekt das ist **relativ** Ausrüstung.

Um der Personalisierung freie Wahl zu lassen, können Sie diese Objekte nach Ihren Wünschen benennen. Normalerweise definieren wir die verschiedenen Teile seines Hauses, wie den Namen der Räume (dies ist auch die empfohlene Konfiguration).

![Objekte](./images/object_intro.gif)

## Gestion

Sie haben zwei Möglichkeiten :
- **Hinzufügen** : Erstellen Sie ein neues Objekt.
- **Übersicht** : Zeigt die Liste der erstellten Objekte und deren Konfiguration an.

## Meine Objekte

Sobald Sie ein Objekt erstellt haben, wird es in diesem Teil angezeigt.

> **Spitze**
>
> Sie können ein Objekt öffnen, indem Sie dies tun :
> - Klicken Sie auf eine davon.
> - Strg Clic oder Clic Center, um es in einem neuen Browser-Tab zu öffnen.

Sie haben eine Suchmaschine, um die Anzeige von Objekten zu filtern. Die Escape-Taste bricht die Suche ab.
Rechts neben dem Suchfeld befinden sich drei Schaltflächen, die an mehreren Stellen in Jeedom gefunden wurden:

- Das Kreuz, um die Suche abzubrechen.
- Der geöffnete Ordner zum Entfalten aller Bedienfelder und Anzeigen aller Objekte.
- Der geschlossene Ordner zum Falten aller Panels.

Sobald Sie ein Objekt konfiguriert haben, haben Sie ein Kontextmenü mit der rechten Maustaste auf die Registerkarten des Objekts. Sie können auch einen Strg-Klick oder einen Mittel-Klick verwenden, um ein anderes Objekt direkt in einer neuen Browser-Registerkarte zu öffnen.

## Registerkarte &quot;Objekt&quot;

Durch Klicken auf ein Objekt gelangen Sie auf dessen Konfigurationsseite. Vergessen Sie nicht, Ihre Änderungen zu speichern, unabhängig davon, welche Änderungen Sie vornehmen.

Hier sind die verschiedenen Merkmale zum Konfigurieren eines Objekts :

- **Objektname** : Der Name Ihres Objekts.
- **Vater** : Gibt das übergeordnete Objekt des aktuellen Objekts an. Dadurch kann eine Hierarchie zwischen den Objekten definiert werden. Zum Beispiel : Das Wohnzimmer ist mit der Wohnung verbunden. Ein Objekt kann nur ein übergeordnetes Objekt haben, aber mehrere Objekte können dasselbe übergeordnete Objekt haben.
- **Sichtbar** : Aktivieren Sie dieses Kontrollkästchen, um dieses Objekt sichtbar zu machen.
- **Im Dashboard ausblenden** : Aktivieren Sie dieses Kontrollkästchen, um das Objekt im Dashboard auszublenden. Es wird weiterhin in der Liste gespeichert, sodass es angezeigt werden kann, jedoch nur explizit.
- **Zusammenfassung ausblenden'** : Aktivieren Sie dieses Kontrollkästchen, um das Objekt in der Zusammenfassung auszublenden'. Es wird weiterhin in der Liste gespeichert, sodass es angezeigt werden kann, jedoch nur explizit.
- **Symbol** : Ermöglicht die Auswahl eines Symbols für Ihr Objekt.
- **Benutzerdefinierte Farben** : Aktiviert die Berücksichtigung der beiden optionalen Farbparameter.
- **Tag Farbe** : Ermöglicht die Auswahl der Farbe des Objekts und der daran angeschlossenen Ausrüstung.
- **Tag-Textfarbe** : Hier können Sie die Farbe des Textes des Objekts auswählen. Dieser Text wird über dem **Tag Farbe**. Sie wählen eine Farbe, um den Text lesbar zu machen.
- **Bild** : Sie haben die Möglichkeit, ein Bild hochzuladen oder zu löschen. Im JPEG-Format ist dieses Bild das Hintergrundbild des Objekts, wenn Sie es im Dashboard anzeigen. Es wird auch für das Vorschaubild des Stücks über die Synthese verwendet.
- **Nur bei Synthese** : Ermöglicht das Einfügen eines Bildes für die Synthese, ohne dass es als Hintergrundbild verwendet wird.

> **Spitze**
>
> Sie können die Anzeigereihenfolge von Objekten im Dashboard ändern. Wählen Sie in der Übersicht (oder in der Zusammenfassung der Hausautomation) Ihr Objekt mit der Maus per Drag & Drop aus, um ihm einen neuen Platz zu geben.

> **Spitze**
>
> Sie können eine Grafik sehen, die alle Elemente von Jeedom darstellt, die an dieses Objekt angehängt sind, indem Sie auf die Schaltfläche klicken **Verbindungen**, oben rechts.

> **Spitze**
>
> Wenn ein Gerät erstellt wird und kein übergeordnetes Gerät definiert wurde, wird es als übergeordnetes Element verwendet : **Keine**.

## Registerkarte &quot;Zusammenfassung&quot;

Zusammenfassungen sind globale Informationen, die einem Objekt zugewiesen sind und insbesondere im Dashboard neben seinem Namen angezeigt werden.

### Schwarzes Brett

Die Spalten stellen die Zusammenfassungen dar, die dem aktuellen Objekt zugewiesen sind. Ihnen werden drei Zeilen vorgeschlagen :

- **Gehen Sie in die globale Zusammenfassung** : Aktivieren Sie das Kontrollkästchen, wenn die Zusammenfassung in der Jeedom-Menüleiste angezeigt werden soll.
- **Auf dem Desktop ausblenden** : Aktivieren Sie das Kontrollkästchen, wenn die Zusammenfassung nicht neben dem Objektnamen im Dashboard angezeigt werden soll.
- **Auf dem Handy verstecken** : Aktivieren Sie das Kontrollkästchen, wenn die Zusammenfassung nicht angezeigt werden soll, wenn Sie sie von einem Mobiltelefon aus anzeigen.

### Commandes

Jede Registerkarte stellt eine Art Zusammenfassung dar, die in der Konfiguration von Jeedom definiert ist. Klicken Sie auf **Bestellung hinzufügen** so dass es in der Zusammenfassung berücksichtigt wird. Sie haben die Wahl, den Befehl eines Jeedom-Geräts auszuwählen, auch wenn dieses Objekt nicht als übergeordnetes Objekt vorhanden ist.

> **Spitze**
>
> Wenn Sie eine Art von Zusammenfassung hinzufügen oder die Methode zur Berechnung des Ergebnisses, der Einheit, des Symbols und des Namens einer Zusammenfassung konfigurieren möchten, müssen Sie zur allgemeinen Konfiguration von Jeedom wechseln : **Einstellungen → System → Konfiguration : Registerkarte &quot;Zusammenfassungen&quot;**.

## Übersicht

In der Übersicht können Sie alle Objekte in Jeedom sowie deren Konfiguration anzeigen :

- **Identifikation** : Objekt-ID.
- **Objekt** : Objektname.
- **Vater** : Name des übergeordneten Objekts.
- **Sichtbar** : Objektsichtbarkeit.
- **Maskiert** : Gibt an, ob das Objekt im Dashboard ausgeblendet ist.
- **Zusammenfassung definiert** : Gibt die Anzahl der Bestellungen pro Zusammenfassung an. Was blau ist, wird in der globalen Zusammenfassung berücksichtigt.
- **Versteckte Dashboard-Zusammenfassung** : Zeigt versteckte Zusammenfassungen im Dashboard an.
- **Versteckte mobile Zusammenfassung** : Versteckte Zusammenfassungen auf dem Handy anzeigen.
