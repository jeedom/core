# Gerätearten
**Werkzeuge → Gerätetypen**

Die Sensoren und Aktoren in Jeedom werden von Plugins verwaltet, die Geräte mit Befehlen erstellen ** (Sensor) oder ** (Antrieb). Dadurch ist es dann möglich, Aktionen basierend auf der Änderung bestimmter Sensoren auszulösen, wie z.B. das Einschalten eines Lichts bei Bewegungserkennung. Aber der Jeedom Core und Plugins wie **, **, *Google-Smarthome*, *Alexa-Smarthome* etc., weiß nicht was das für ein Gerät ist : Eine Steckdose, ein Licht, ein Rollladen usw.

Um dieses Problem zu lösen, insbesondere bei Sprachassistenten (*Mach das Zimmerlicht an*), Core stellte die . vor **Generische Typen**, von diesen Plugins verwendet.

Dies ermöglicht die Identifizierung eines Gerätes durch *Das Licht des Zimmers* zum Beispiel.

Meistens werden die generischen Typen automatisch bei der Konfiguration Ihres Moduls gesetzt (z.B. Aufnahme in Z-Wave). Es kann jedoch vorkommen, dass Sie sie neu konfigurieren müssen. Die Konfiguration dieser generischen Typen kann direkt in bestimmten Plugins oder per Befehl in . erfolgen *Erweiterte Konfiguration* davon.

Diese Seite ermöglicht eine direktere und einfachere Konfiguration dieser Generic Types und bietet sogar eine automatische Zuweisung nach korrekter Zuweisung der Geräte.

![Gerätearten](./images/coreGenerics.gif)

## Ausstattungsart

Diese Seite bietet Lagerung nach Gerätetyp : Steckdose, Licht, Shutter, Thermostat, Kamera usw. Zunächst werden die meisten Ihrer Geräte klassifiziert in **Geräte ohne Typ**. Um ihnen einen Typ zuzuweisen, können Sie sie entweder auf einen anderen Typ verschieben oder mit der rechten Maustaste auf das Gerät klicken, um es direkt zu verschieben. Der Ausrüstungstyp ist an sich nicht wirklich nützlich, am wichtigsten sind die Auftragstypen. Sie können also eine Ausrüstung ohne Typ haben oder einen Typ, der nicht unbedingt seinen Befehlen entspricht. Sie können natürlich verschiedene Steuerungstypen innerhalb derselben Ausrüstung mischen. Im Moment ist es eher ein Speicher, eine logische Organisation, die vielleicht in zukünftigen Versionen dienen wird.

> ****
>
> - Wenn du Ausrüstung im Spiel bewegst **Geräte ohne Typ**, Jeedom schlägt vor, generische Typen aus seinen Bestellungen zu entfernen.
> - Sie können mehrere Ausrüstungsgegenstände gleichzeitig bewegen, indem Sie die Kontrollkästchen links davon aktivieren.

## Befehlstyp

Sobald ein Ausrüstungsgegenstand richtig positioniert ist **, wenn Sie darauf klicken, gelangen Sie zur Liste der Bestellungen, die anders gefärbt sind, wenn es a . ist ** (Blau) oder a ** (Orange).

Durch Rechtsklick auf einen Auftrag können Sie ihm dann einen generischen Typ entsprechend den Spezifikationen dieses Auftrags zuweisen (Info-/Aktionstyp, Numerisch, Binärer Untertyp usw).

> ****
>
> - Das Kontextmenü der Befehle zeigt den Gerätetyp in Fettdruck an, ermöglicht aber dennoch die Zuweisung eines beliebigen generischen Typs eines beliebigen Gerätetyps.

Auf jedem Gerät haben Sie zwei Tasten :

- **Autotypen** : Diese Funktion öffnet ein Fenster, das Ihnen die passenden generischen Typen entsprechend der Art der Ausrüstung, den Besonderheiten des Auftrags und seines Namens anbietet. Sie können dann die Vorschläge anpassen und die Anwendung für bestimmte Befehle deaktivieren, bevor Sie sie akzeptieren oder nicht. Diese Funktion ist kompatibel mit der Auswahl durch Checkboxen.

- **Reset-Typen** : Diese Funktion entfernt generische Typen aus allen Ausrüstungsbefehlen.

> ****
>
> Vor dem Speichern werden keine Änderungen vorgenommen, mit der Schaltfläche oben rechts auf der Seite.

## Generische Typen und Szenarien

In v4.2 hat der Core die generischen Typen in die Szenarien integriert. So können Sie ein Szenario auslösen, wenn in einem Raum eine Lampe angeht, eine Bewegung im Haus erkannt wird, alle Lichter ausgeschaltet oder alle Rollläden mit einer einzigen Aktion geschlossen werden usw. Wenn Sie eine Ausrüstung hinzufügen, müssen Sie außerdem nur die richtigen Typen in ihren Bestellungen angeben, solche Szenarien müssen nicht bearbeitet werden.

#### Abzug

Sie können ein Szenario über Sensoren auslösen. Wenn Sie beispielsweise Bewegungsmelder im Haus haben, können Sie ein Alarmszenario erstellen, bei dem jeder Melder auslöst : ``#[Wohnzimmer][Move Salon][Presence]# == 1`,`#[Cuisine][Move Cuisine][Presence]# == 1` usw.. In einem solchen Szenario benötigen Sie daher alle Ihre Bewegungsmelder, und wenn Sie einen hinzufügen, müssen Sie ihn zu den Auslösern hinzufügen. Logik.

Generische Typen ermöglichen die Verwendung eines einzigen Triggers : ``#genericType(PRESENCE)# == 1`. Hier wird kein Objekt angezeigt, so dass die kleinste Bewegung im ganzen Haus das Szenario auslöst. Wenn Sie einen neuen Melder im Haus hinzufügen, müssen Sie das Szenario (die Szenarien) nicht bearbeiten).

Hier ein Auslöser beim Einschalten eines Lichts im Wohnzimmer : ``#genericType(,#[Wohnzimmer]#)# > 

#### Expression

Wenn Sie in einem Szenario wissen möchten, ob im Wohnzimmer Licht brennt, können Sie dies tun :

WENN `#[Wohnzimmer][Lumiere Canapé][]# == 1 ODER #[Wohnzimmer][Lumiere Salon][]# == 1 ODER #[Wohnzimmer][Lumiere Angle][]# == 1`

Oder einfacher : IF `genericType (LIGHT_STATE .),#[Wohnzimmer]#) > 0` oder wenn ein oder mehrere Licht(e) im Wohnzimmer leuchten.

Wenn Sie morgen ein Licht in Ihrem Wohnzimmer hinzufügen, müssen Sie Ihre Szenarien nicht retuschieren !


#### Action

Wenn Sie alle Lichter im Wohnzimmer einschalten möchten, können Sie eine Lichtaktion erstellen:

`` ``
#[Wohnzimmer][Lumiere Canapé][]#
#[Wohnzimmer][Lumiere Salon][]#
#[Wohnzimmer][Lumiere Angle][]#
`` ``

Oder einfacher, erstellen Sie eine `genericType`-Aktion mit `LIGHT_ON` in `Salon`. Wenn Sie morgen ein Licht in Ihrem Wohnzimmer hinzufügen, müssen Sie Ihre Szenarien nicht retuschieren !


## Liste der generischen Kerntypen

> ****
>
> - Sie finden diese Liste direkt in Jeedom, auf derselben Seite, mit dem Button **** oben rechts.

| **Andere (id: Other)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Status-Timer |  | numeric
| TIMER_STATUS | Timer-Status (Pause oder nicht)) |  | binär, numerisch
|  |  |  | slider
|  | Pause-Timer |  | other
|  | Timer fortsetzen |  | other

| **: Battery)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  |  |  | numeric
| BATTERIE_LADEN | Aufladen des Akkus |  | binary

| **Kamera (id: Camera)** | | | |
|:--------|:----------------|:--------:|:---------:|
| KAMERA_URL | Kamera-URL |  | string
|  | Aufnahmestatus der Kamera |  | binary
| KAMERA_AUF | Kamerabewegung nach oben |  | other
| KAMERA RUNTER | Kamerabewegung nach unten |  | other
| KAMERA_LEFT | Kamerabewegung nach links |  | other
| KAMERA_RECHTS | Kamerabewegung nach rechts |  | other
| KAMERA_ZOOM | Kamera nach vorne zoomen |  | other
| KAMERA_DEZOOM | Kamerarückseite zoomen |  | other
| KAMERA_STOP | Kamera stoppen |  | other
|  | Kameravoreinstellung |  | other
| KAMERA_AUFNAHME | Kameraaufnahme |  |
| KAMERA_AUFNAHME | Schnappschuss-Kamera |  |

| **Heizung (id: Heating)** | | | |
|:--------|:----------------|:--------:|:---------:|
| HEIZZUSTAND | Status der Pilotdrahtheizung |  | binary
| AUFHEIZEN | Taste Steuerdrahtheizung EIN |  | other
| HEIZUNG_AUS | Taste Pilotdrahtheizung AUS |  | other
| HEIZUNG_OTHER | Heizsteuerdraht Taste |  | other

| **Strom (id: Electricity)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Elektrische Energie |  | numeric
|  | Energieverbrauch |  | numeric
|  |  |  | numeric
|  | Neu starten |  | other

| **Umgebung (id: Environment)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | TEMPERATUR |  | numeric
| LUFTQUALITÄT | Luftqualität |  | numeric
|  |  |  | numeric
|  | GEGENWART |  | binary
|  | Rauchmelder |  | binary
|  |  |  | numeric
|  |  |  | numeric
|  | ) |  | numeric
|  | ) |  | numeric
|  | Ton (dB) |  | numeric
|  |  |  | numeric
| WASSERLECK | Wasserleck |  |
| FILTER_CLEAN_STATUS | Filterstatus |  | binary

| **Generisch (id: Generic)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  |  |  | numeric
|  |  |  | numeric
|  |  |  | binär, numerisch
| ALLGEMEINE_INFO |  Generisch |  |
|  |  Generisch |  | other

| **Licht (id: Light)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Lichtzustand |  | binär, numerisch
| LICHT_HELLIGKEIT | Lichthelligkeit |  | numeric
| HELLE FARBE | Helle Farbe |  | string
|  | Lichtzustand (Binär) |  | binary
|  | Lichttemperatur Farbe |  | numeric
|  | Licht umschalten |  | other
| LICHT AN | Lichttaste Ein |  | other
| LICHT AUS | Lichttaste aus |  | other
|  | Lichtschieber |  | slider
|  | Helle Farbe |  | color
|  | Lichtmodus |  | other
|  | Lichttemperatur Farbe |  |

| **Modus (id: Mode)** | | | |
|:--------|:----------------|:--------:|:---------:|
| MODE_STATUS | Statusmodus |  | string
|  | Modus ändern |  | other

| **Multimedia (id: Multimedia)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  |  |  | numeric
| MEDIEN_STATUS |  |  | string
|  |  |  | string
| MEDIEN_KÜNSTLER |  |  | string
| MEDIEN_TITEL |  |  | string
| MEDIEN_MACHT |  |  | string
|  |  |  | numerisch, Zeichenfolge
| MEDIEN_STATUS |  |  | binary
|  |  |  | slider
|  |  |  | anderer Schieberegler
| MEDIEN_PAUSE |  |  | other
|  |  |  | other
| MEDIEN_STOP |  |  | other
| MEDIEN_NÄCHSTES |  |  | other
| MEDIEN_VORHER | Vorherige |  | other
| MEDIEN_EIN |  |  | other
| MEDIEN_AUS |  |  | other
| MEDIEN_STUMM |  |  | other
|  | Keine Stummschaltung |  | other

| **Wetter (id: Weather)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Wetter Temperatur |  | numeric
|  | Wetterbedingung d + 1 max d + 2 |  | numeric
| WINDGESCHWINDIGKEIT | Windgeschwindigkeit) |  | numeric
| REGEN_TOTAL | Regen (Akkumulation)) |  | numeric
| REGEN_AKTUELL | Regen (mm / h) |  | numeric
|  | Wetterbedingung (id) d + 4 |  | numeric
|  | Wetterbedingungen d + 4 |  | string
|  | Wetter Max Temperatur d + 4 |  | numeric
|  | Wettertemperatur min d + 4 |  | numeric
|  | Wetterbedingung (id) d + 3 |  | numeric
|  | Wetterbedingung d + 3 |  | string
|  | Wetter Max Temperatur d + 3 |  | numeric
|  | Wetter Temperatur min d + 3 |  | numeric
|  | Wetterbedingung (id) d + 2 |  | numeric
|  | Wetterbedingungen d + 2 |  | string
|  | Wetter Temperatur min d + 2 |  | numeric
| WETTER_FEUCHTE | Wetter Luftfeuchtigkeit |  | numeric
|  | Wetterbedingung (id) d + 1 |  | numeric
|  | Wetterbedingung d + 1 |  | string
|  | Wetter Max Temperatur d + 1 |  | numeric
|  | Wetter Temperatur min d + 1 |  | numeric
|  | Wetterbedingungen (id) |  | numeric
| WETTERLAGE | Wetterlage |  | string
| X | Wetter Höchsttemperatur |  | numeric
|  | Wetter Temperatur min |  | numeric
| WEATHER_SONNENAUFGANG | Sonnenuntergang Wetter |  | numeric
|  | Sonnenaufgang Wetter |  | numeric
|  | Windrichtung Wetter |  | numeric
|  | Wetter mit Windgeschwindigkeit |  | numeric
| WETTER_DRUCK | Wetterdruck |  | numeric
| WINDRICHTUNG | Windrichtung) |  | numeric

| **Eröffnung (id: Opening)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Zustandssperre |  | binary
|  | Portal (Öffnen) Status |  | binary
| GARAGE_STAAT | Garage (Eröffnung) Zustand |  | binary
|  |  |  | binary
|  | Fenster |  | binary
|  | Sperrtaste öffnen |  | other
|  | Sperrtaste Schließen |  | other
|  | Tor- oder Garagenöffnungstaste |  | other
|  | Tor- oder Garagenschließtaster |  | other
|  | Tor- oder Garagentaste umschalten |  | other

| **Buchse (id: Outlet)** | | | |
|:--------|:----------------|:--------:|:---------:|
| ENERGIE_STATE | Zustandssteckdose |  | numerisch, binär
| ENERGIE_EIN | Auf Knopfleiste |  | other
| ENERGIE_AUS | Steckdosentaste Aus |  | other
|  | Schieberbuchse |  |

| **Roboter (id: Robot)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Staatliche Basis |  | binary
|  | Zurück zur basis |  | other

| **Sicherheit (id: Security)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Meerjungfrauenstaat |  | binary
| ALARM_ZUSTAND | Alarmstatus |  | binär, string
| ALARM_MODUS | Alarmmodus |  | string
|  | Alarmstatus aktiviert |  | binary
|  |  |  | binary
|  |  |  | binary
|  |  |  | binär, numerisch
| SIRENE_AUS | Sirenentaste aus |  | other
| SIRENE_EIN | Sirenenknopf Ein |  | other
| ALARM_SCHARF | Bewaffneter Alarm |  | other
| ALARM_FREIGEGEBEN | Alarm ausgelöst |  | other
|  | Alarmmodus |  | other

| **: Thermostat)** | | | |
|:--------|:----------------|:--------:|:---------:|
| THERMOSTAT_ZUSTAND | Thermostatstatus (BINÄR) (nur für Plugin-Thermostat) |  |
| THERMOSTAT_TEMPERATUR | Raumtemperaturregler |  | numeric
|  | Sollwertthermostat |  | numeric
| THERMOSTAT_MODUS | Thermostat-Modus (nur für Plugin-Thermostat)) |  | string
| THERMOSTAT_SPERRE | Sperrthermostat (nur für Plugin-Thermostat)) |  | binary
| THERMOSTAT_TEMPERATUR_AUSSEN | Außentemperatur-Thermostat (nur für Plugin-Thermostat)) |  | numeric
|  | Thermostatstatus (MENSCH) (nur für Plugin-Thermostat) |  | string
| THERMOSTAT_FEUCHTE | Raumfeuchtigkeitsthermostat |  | numeric
|  | Luftfeuchtigkeit einstellen |  | slider
|  | Sollwertthermostat |  | slider
|  | Thermostat-Modus (nur für Plugin-Thermostat)) |  | other
|  | Sperrthermostat (nur für Plugin-Thermostat)) |  | other
|  | Thermostat entsperren (nur für Plugin-Thermostat)) |  | other
|  | Luftfeuchtigkeit einstellen |  | slider

| **Lüfter (id: Fan)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Lüftergeschwindigkeit Status |  | numeric
| ROTATION_STATUS | Zustandsrotation |  | numeric
| LÜFTERGESCHWINDIGKEIT | Lüftergeschwindigkeit |  | slider
|  |  |  | slider

| **Bereich (id: Shutter)** | | | |
|:--------|:----------------|:--------:|:---------:|
|  | Statusbereich |  | binär, numerisch
|  | Bereich BSO-Status |  | binär, numerisch
|  | Schaltfläche „Fenster nach oben“ |  | other
|  | Schaltfläche „Fenster nach unten“ |  | other
|  | Stopptaste Auslöser |  |
|  | Bereich mit Schieberegler |  | slider
|  | Schaltfläche BSO-Fenster nach oben |  | other
|  | Schaltfläche BSO-Fenster nach unten |  | other
