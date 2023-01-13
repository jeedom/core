 # Remplacer
**Extras → Ersetzen**

Dieses Tool ermöglicht den schnellen Austausch von Geräten und Befehlen, beispielsweise bei einem Plugin- oder Modulwechsel.

Wie die Ersetzungsoptionen in der erweiterten Konfiguration eines Befehls ersetzt er die Befehle in den Szenarien und anderen, ermöglicht aber auch die Übertragung der Eigenschaften der Ausrüstung und der Befehle.

## Filtres

Sie können zur besseren Lesbarkeit nur bestimmte Geräte anzeigen, nach Objekt oder nach Plugin filtern.

> Bei einem Gerätetausch durch ein anderes Plugin wählen Sie die beiden Plugins aus.

## Options

> ****
>
> Wenn keine dieser Optionen aktiviert ist, läuft die Ersetzung auf die Verwendung der Funktion hinaus *Ersetzen Sie diesen Befehl durch den Befehl* in erweiterter Konfiguration.

- **Konfiguration vom Quellgerät kopieren** :
Für jedes Gerät wird von der Quelle zum Ziel kopiert (nicht erschöpfende Liste) :
	* Das übergeordnete Objekt,
	* Die Kategorien,
	* Eigenschaften **  **,
	* Kommentare und Tags,
	* Bestellung (Dashboard),
	* Breite und Höhe (Kachel-Dashboard),
	* Kachelkurveneinstellungen,
	* Optionale Parameter,
	* Die Konfiguration der Tabellenanzeige,
	* der generische Typ,
	* Die Eigenschaft **
	* Die Konfiguration **,
	* Batterie- und Kommunikationswarnungen,

Das Quellgerät wird auch durch das Zielgerät auf dem ersetzt **** und die ****.


*Diese Ausrüstung wird auch durch die Zielausrüstung auf Designs und Ansichten ersetzt.*

- **Quellgerät ausblenden** : Ermöglicht es, das Quellgerät unsichtbar zu machen, nachdem es durch das Zielgerät ersetzt wurde.

- **Kopieren Sie die Konfiguration aus dem Quellbefehl** :
Für jede Bestellung wird von der Quelle zum Ziel kopiert (nicht erschöpfende Liste) :
	* Die Eigenschaft **,
	* Bestellung (Dashboard),
	* L'historisation,
	* Die verwendeten Dashboard- und Mobile-Widgets,
	* Der generische Typ,
	* Optionale Parameter,
	* Die Konfigurationen **  *jeedomPostExecCmd* (Aktion),
	* Value Action Konfigurationen (info),
	* Symbol,
	* Die Aktivierung und das Verzeichnis in **,
	* Die Konfigurationen von **  **,
	* Die influxDB-Konfiguration,
	* Die Wiederholungswertkonfiguration,
	* Warnungen,

- **Befehlshistorie des Ziels löschen** : Löscht Zielbefehlsverlaufsdaten.

- **Quellbefehlshistorie kopieren** : Kopieren Sie den Quellbefehlsverlauf in den Zielbefehl.



## Remplacements

Die Taste **** Oben rechts können Sie alle Geräte anzeigen, indem Sie den Filtern nach Objekt und Plugin folgen.

Für jedes Gerät :

- Aktivieren Sie das Kontrollkästchen am Anfang der Zeile, um den Ersatz zu aktivieren.
- Wählen Sie rechts das Gerät aus, durch das es ersetzt werden soll.
- Klicken Sie auf seinen Namen, um seine Befehle anzuzeigen, und geben Sie an, welche Befehle sie ersetzen. Bei der Auswahl eines Geräts füllt das Tool diese Auswahlmöglichkeiten vorab aus, wenn es auf dem Zielgerät einen Befehl desselben Typs und desselben Namens findet.


> ****
>
> Wenn Sie auf einem Quellgerät ein Zielgerät angeben, wird dieses Zielgerät in der Liste deaktiviert.
