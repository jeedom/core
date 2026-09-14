# Objekte
**Werkzeuge → Objekte**

Mit den **Objekten** können Sie die Struktur Ihres Hausautomationssystems festlegen.

Alle Geräte, die Sie anlegen, müssen zu einem Objekt gehören und sind so leichter zu finden. Man sagt dann, dass das Objekt der **Übergeordnete** des Geräts ist.

Um Ihnen freie Hand bei der individuellen Anpassung zu lassen, können Sie diese Objekte nach Belieben benennen. In der Regel werden hier die verschiedenen Bereiche Ihres Hauses definiert, beispielsweise die Namen der Räume (dies ist übrigens die empfohlene Konfiguration).

![Objekte](../images/object_intro.gif)

## Verwaltung

Sie haben zwei Möglichkeiten:
- **Hinzufügen**: Ermöglicht das Erstellen eines neuen Objekts.
- **Übersicht**: Hier werden die Liste der angelegten Objekte sowie deren Konfiguration angezeigt.

## Übersicht

In der Übersicht können Sie alle Objekte in Jeedom sowie deren Konfiguration einsehen:

- **ID**: ID des Objekts.
- **Objekt**: Name des Objekts.
- **Übergeordnetes Objekt**: Name des übergeordneten Objekts.
- **Sichtbar**: Sichtbarkeit des Objekts.
- **Ausgeblendet**: Gibt an, ob das Objekt auf dem Dashboard ausgeblendet ist.
- **Zusammenfassung definiert**: Gibt die Anzahl der Befehle pro Zusammenfassung an. Die blau markierten Elemente werden in der Gesamtzusammenfassung berücksichtigt.
- **Ausgeblendete Dashboard-Übersichten**: Zeigt die auf dem Dashboard ausgeblendeten Übersichten an.
- **Auf dem Handy ausgeblendete Zusammenfassung**: Zeigt die auf dem Handy ausgeblendeten Zusammenfassungen an.

## Meine Objekte

Sobald Sie ein Objekt erstellt haben, wird es in diesem Bereich angezeigt.

> **Tipp**
>
> Sie können ein Objekt wie folgt öffnen:
> - Klicken Sie auf eines davon.
> - Strg-Klick oder mittlerer Mausklick, um die Seite in einem neuen Browser-Tab zu öffnen.

Ihnen steht eine Suchfunktion zur Verfügung, mit der Sie die Anzeige der Objekte filtern können. Mit der Esc-Taste brechen Sie die Suche ab.
Rechts neben dem Suchfeld befinden sich drei Schaltflächen, die an mehreren Stellen in Jeedom zu finden sind:

- Das Kreuz zum Abbrechen der Suche.
- Der Ordner ist geöffnet, um alle Registerkarten zu entfalten und alle Objekte anzuzeigen.
- Der Ordner ist geschlossen, um alle Paneele einzuklappen.

Sobald Sie sich in der Konfiguration eines Objekts befinden, steht Ihnen ein Kontextmenü zur Verfügung, das Sie durch einen Rechtsklick auf die Registerkarten des Objekts aufrufen können. Sie können auch Strg+Klick oder den mittleren Mausknopf verwenden, um ein anderes Objekt direkt in einer neuen Registerkarte des Browsers zu öffnen.

## Registerkarte „Objekt“

Wenn Sie auf ein Objekt klicken, gelangen Sie zu dessen Konfigurationsseite. Vergessen Sie nicht, Ihre Änderungen zu speichern, unabhängig davon, welche Änderungen Sie vorgenommen haben.

Hier sind also die verschiedenen Eigenschaften zur Konfiguration eines Objekts:

#### Einstellungen:

- **Objektname**: Der Name Ihres Objekts.
- **Übergeordnetes Objekt**: Gibt das übergeordnete Objekt des aktuellen Objekts an. Auf diese Weise lässt sich eine Hierarchie zwischen den Objekten definieren. Beispiel: Das Wohnzimmer ist dem Apartment untergeordnet. Ein Objekt kann nur ein einziges übergeordnetes Objekt haben, aber mehrere Objekte können dasselbe übergeordnete Objekt haben.
- **Sichtbar**: Aktivieren Sie dieses Kontrollkästchen, um dieses Objekt sichtbar zu machen.
- **Auf dem Dashboard ausblenden**: Aktivieren Sie dieses Kontrollkästchen, um das Objekt auf dem Dashboard auszublenden. Es bleibt dennoch in der Liste erhalten, sodass es angezeigt werden kann, jedoch nur auf ausdrücklichen Wunsch.
- **In der Übersicht ausblenden**: Aktivieren Sie dieses Kontrollkästchen, um das Objekt in der Übersicht auszublenden. Es bleibt jedoch in der Liste erhalten, sodass es angezeigt werden kann, allerdings nur auf ausdrücklichen Wunsch.
- **Aktion aus der Übersicht**: Hier können Sie eine Ansicht oder ein Design angeben, zu dem Sie weitergeleitet werden möchten, wenn Sie in der Übersicht auf das Objekt klicken. *Standard: Dashboard*.

#### Anzeige:

- **Symbol**: Hier können Sie ein Symbol für Ihr Objekt auswählen.
- **Benutzerdefinierte Farben**: Aktiviert die Berücksichtigung der beiden unten aufgeführten Parameter für benutzerdefinierte Farben.
- **Farbe des Tags**: Hier können Sie die Farbe des Objekts und der damit verbundenen Geräte auswählen.
- **Farbe des Tag-Textes**: Hier können Sie die Farbe des Textes des Objekts auswählen. Dieser Text wird über der **Tag-Farbe** angezeigt. Wählen Sie eine Farbe aus, damit der Text gut lesbar ist.
- **Nur auf der Übersicht**: Ermöglicht es, ein Bild für die Übersicht einzufügen, ohne dass es als Hintergrundbild verwendet wird, insbesondere auf der *Dashboard*-Seite dieses Objekts.
- **Bild**: Sie haben die Möglichkeit, ein Bild hochzuladen oder es zu löschen. Dieses Bild im JPEG-Format dient als Hintergrundbild des Objekts, wenn Sie es auf dem Dashboard anzeigen. Es wird außerdem als Miniaturansicht des Raums in der Übersicht verwendet.

> **Tipp**
>
> Sie können die Reihenfolge der Objekte im Dashboard über die Hausautomationsübersicht (Analyse -> Hausautomationsübersicht) ändern. Wählen Sie Ihr Objekt mit der Maus aus und verschieben Sie es per Drag & Drop an eine neue Position.

> **Tipp**
>
> Wenn Sie oben rechts auf die Schaltfläche **Verknüpfungen** klicken, wird eine Grafik angezeigt, die alle mit diesem Objekt verknüpften Jeedom-Elemente darstellt.

> **Tipp**
>
> Wenn ein Gerät angelegt wird und kein übergeordnetes Gerät definiert wurde, wird als übergeordnetes Gerät **„Keines“** angegeben.

## Registerkarten „Zusammenfassungen“

[Siehe Dokumentation zu den Zusammenfassungen.](https://doc.jeedom.com/concept/de_DE/summary)


