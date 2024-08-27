# Gerätearten
**Werkzeuge → Gerätetypen**

Die Sensoren und Aktoren in Jeedom werden von Plugins verwaltet, die Geräte mit Befehlen erstellen *Information* (Sensor) oder *Aktion* (Antrieb). Dadurch ist es dann möglich, Aktionen basierend auf der Änderung bestimmter Sensoren auszulösen, wie z.B. das Einschalten eines Lichts bei Bewegungserkennung. Aber der Jeedom Core und Plugins wie *Mobile*, *Homebridge*, *Google-Smarthome*, *Alexa-Smarthome* etc., weiß nicht was das für ein Gerät ist : Eine Steckdose, ein Licht, ein Rollladen usw.

Um dieses Problem zu lösen, insbesondere bei Sprachassistenten (*Mach das Zimmerlicht an*), Core stellte die . vor **Generische Typen**, von diesen Plugins verwendet.

Dies ermöglicht die Identifizierung eines Gerätes durch *Das Licht des Zimmers* zum Beispiel.

Meistens werden die generischen Typen automatisch bei der Konfiguration Ihres Moduls gesetzt (z.B. Aufnahme in Z-Wave). Es kann jedoch vorkommen, dass Sie sie neu konfigurieren müssen. Die Konfiguration dieser generischen Typen kann direkt in bestimmten Plugins oder per Befehl in . erfolgen *Erweiterte Konfiguration* davon.

Diese Seite ermöglicht eine direktere und einfachere Konfiguration dieser Generic Types und bietet sogar eine automatische Zuweisung nach korrekter Zuweisung der Geräte.

![Gerätearten](./images/coreGenerics.gif)

## Ausstattungsart

Diese Seite bietet Lagerung nach Gerätetyp : Steckdose, Licht, Shutter, Thermostat, Kamera usw. Zunächst werden die meisten Ihrer Geräte klassifiziert in **Geräte ohne Typ**. Um ihnen einen Typ zuzuweisen, können Sie sie entweder auf einen anderen Typ verschieben oder mit der rechten Maustaste auf das Gerät klicken, um es direkt zu verschieben. Der Ausrüstungstyp ist an sich nicht wirklich nützlich, am wichtigsten sind die Auftragstypen. Sie können also eine Ausrüstung ohne Typ haben oder einen Typ, der nicht unbedingt seinen Befehlen entspricht. Sie können natürlich verschiedene Steuerungstypen innerhalb derselben Ausrüstung mischen. Im Moment ist es eher ein Speicher, eine logische Organisation, die vielleicht in zukünftigen Versionen dienen wird.

> **Tipp**
>
> - Wenn du Ausrüstung im Spiel bewegst **Geräte ohne Typ**, Jeedom schlägt vor, generische Typen aus seinen Bestellungen zu entfernen.
> - Sie können mehrere Ausrüstungsgegenstände gleichzeitig bewegen, indem Sie die Kontrollkästchen links davon aktivieren.

## Befehlstyp

Sobald ein Ausrüstungsgegenstand richtig positioniert ist *Art*, wenn Sie darauf klicken, gelangen Sie zur Liste der Bestellungen, die anders gefärbt sind, wenn es a . ist *Information* (Blau) oder a *Aktion* (Orange).

Durch Rechtsklick auf einen Auftrag können Sie ihm dann einen generischen Typ entsprechend den Spezifikationen dieses Auftrags zuweisen (Info-/Aktionstyp, Numerisch, Binärer Untertyp usw).

> **Tipp**
>
> - Das Kontextmenü der Befehle zeigt den Gerätetyp in Fettdruck an, ermöglicht aber dennoch die Zuweisung eines beliebigen generischen Typs eines beliebigen Gerätetyps.

Auf jedem Gerät haben Sie zwei Tasten :

- **Autotypen** : Diese Funktion öffnet ein Fenster, das Ihnen die passenden generischen Typen entsprechend der Art der Ausrüstung, den Besonderheiten des Auftrags und seines Namens anbietet. Sie können dann die Vorschläge anpassen und die Anwendung für bestimmte Befehle deaktivieren, bevor Sie sie akzeptieren oder nicht. Diese Funktion ist kompatibel mit der Auswahl durch Checkboxen.

- **Reset-Typen** : Diese Funktion entfernt generische Typen aus allen Ausrüstungsbefehlen.

> **Aufmerksamkeit**
>
> Vor dem Speichern werden keine Änderungen vorgenommen, mit der Schaltfläche oben rechts auf der Seite.

## Generische Typen und Szenarien

In v4.2 hat der Core die generischen Typen in die Szenarien integriert. So können Sie ein Szenario auslösen, wenn in einem Raum eine Lampe angeht, eine Bewegung im Haus erkannt wird, alle Lichter ausgeschaltet oder alle Rollläden mit einer einzigen Aktion geschlossen werden usw. Wenn Sie eine Ausrüstung hinzufügen, müssen Sie außerdem nur die richtigen Typen in ihren Bestellungen angeben, solche Szenarien müssen nicht bearbeitet werden.

#### Abzug

Sie können ein Szenario über Sensoren auslösen. Wenn Sie beispielsweise Bewegungsmelder im Haus haben, können Sie ein Alarmszenario erstellen, bei dem jeder Melder auslöst : `#[Wohnzimmer][Move Salon][Presence]# == 1`,`#[Cuisine][Move Cuisine][Presence]# == 1` usw.. In einem solchen Szenario benötigen Sie daher alle Ihre Bewegungsmelder, und wenn Sie einen hinzufügen, müssen Sie ihn zu den Auslösern hinzufügen. Logik.

Generische Typen ermöglichen die Verwendung eines einzigen Triggers : `#genericType(PRESENCE)# == 1`. Hier wird kein Objekt angezeigt, so dass die kleinste Bewegung im ganzen Haus das Szenario auslöst. Wenn Sie einen neuen Melder im Haus hinzufügen, müssen Sie das Szenario (die Szenarien) nicht bearbeiten).

Hier ein Auslöser beim Einschalten eines Lichts im Wohnzimmer : `#genericType(LIGHT_STATE,#[Wohnzimmer]#)# > 0`

#### Expression

Wenn Sie in einem Szenario wissen möchten, ob im Wohnzimmer Licht brennt, können Sie dies tun :

WENN `#[Wohnzimmer][Lumiere Canapé][Zustand]# == 1 ODER #[Wohnzimmer][Lumiere Salon][Zustand]# == 1 ODER #[Wohnzimmer][Lumiere Angle][Zustand]# == 1`

Oder einfacher : IF `genericType (LIGHT_STATE .),#[Wohnzimmer]#) > 0` oder wenn ein oder mehrere Licht(e) im Wohnzimmer leuchten.

Wenn Sie morgen ein Licht in Ihrem Wohnzimmer hinzufügen, müssen Sie Ihre Szenarien nicht retuschieren !


#### Action

Wenn Sie alle Lichter im Wohnzimmer einschalten möchten, können Sie eine Lichtaktion erstellen:

`` ``
#[Wohnzimmer][Lumiere Canapé][Wir]#
#[Wohnzimmer][Lumiere Salon][Wir]#
#[Wohnzimmer][Lumiere Angle][Wir]#
`` ``

Oder einfacher, erstellen Sie eine `genericType`-Aktion mit `LIGHT_ON` in `Salon`. Wenn Sie morgen ein Licht in Ihrem Wohnzimmer hinzufügen, müssen Sie Ihre Szenarien nicht retuschieren !


## Liste der generischen Kerntypen

> **Tipp**
>
> - Sie finden diese Liste direkt in Jeedom, auf derselben Seite, mit dem Button **Liste** oben rechts.

| **Andere (id: Other)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TIMER | Status-Timer | Information | numeric
| TIMER_STATUS | Timer-Status (Pause oder nicht)) | Information | binär, numerisch
| SET_TIMER | Timer | Aktion | slider
| TIMER_PAUSE | Pause-Timer | Aktion | other
| TIMER_RESUME | Timer fortsetzen | Aktion | other

| **Batterie (id: Battery)** | | | |
|:--------|:----------------|:--------:|:---------:|
| BATTERIE | Batterie | Information | numeric
| BATTERIE_LADEN | Aufladen des Akkus | Information | binary

| **Kamera (id: Camera)** | | | |
|:--------|:----------------|:--------:|:---------:|
| KAMERA_URL | Kamera-URL | Information | string
| CAMERA_RECORD_STATE | Aufnahmestatus der Kamera | Information | binary
| KAMERA_AUF | Kamerabewegung nach oben | Aktion | other
| KAMERA RUNTER | Kamerabewegung nach unten | Aktion | other
| KAMERA_LEFT | Kamerabewegung nach links | Aktion | other
| KAMERA_RECHTS | Kamerabewegung nach rechts | Aktion | other
| KAMERA_ZOOM | Kamera nach vorne zoomen | Aktion | other
| KAMERA_DEZOOM | Kamerarückseite zoomen | Aktion | other
| KAMERA_STOP | Kamera stoppen | Aktion | other
| KAMERA_PRESET | Kameravoreinstellung | Aktion | other
| KAMERA_AUFNAHME | Kameraaufnahme | Aktion |
| KAMERA_AUFNAHME | Schnappschuss-Kamera | Aktion |

| **Heizung (id: Heating)** | | | |
|:--------|:----------------|:--------:|:---------:|
| HEIZZUSTAND | Status der Pilotdrahtheizung | Information | binary
| AUFHEIZEN | Taste Steuerdrahtheizung EIN | Aktion | other
| HEIZUNG_AUS | Taste Pilotdrahtheizung AUS | Aktion | other
| HEIZUNG_OTHER | Heizsteuerdraht Taste | Aktion | other

| **Strom (id: Electricity)** | | | |
|:--------|:----------------|:--------:|:---------:|
| Leistung | Elektrische Energie | Information | numeric
| VERBRAUCH | Energieverbrauch | Information | numeric
| STROMSPANNUNG | Spannung | Information | numeric
| NEUSTART | Neu starten | Aktion | other

| **Umgebung (id: Environment)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TEMPERATUR | TEMPERATUR | Information | numeric
| LUFTQUALITÄT | Luftqualität | Information | numeric
| HELLIGKEIT | Helligkeit | Information | numeric
| GEGENWART | GEGENWART | Information | binary
| RAUCH | Rauchmelder | Information | binary
| LUFTFEUCHTIGKEIT | Luftfeuchtigkeit | Information | numeric
| UV | UV | Information | numeric
| CO2 | CO2 (ppm) | Information | numeric
| CO | CO (ppm) | Information | numeric
| LÄRM | Ton (dB) | Information | numeric
| DRUCK | Druck | Information | numeric
| WASSERLECK | Wasserleck | Information |
| FILTER_CLEAN_STATUS | Filterstatus | Information | binary

| **Generisch (id: Generic)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TIEFE | Tiefe | Information | numeric
| DISTANZ | DISTANZ | Information | numeric
| TASTE | Taste | Information | binär, numerisch
| ALLGEMEINE_INFO |  Generisch | Information |
| GENERIC_ACTION |  Generisch | Aktion | other

| **Licht (id: Light)** | | | |
|:--------|:----------------|:--------:|:---------:|
| LIGHT_STATE | Lichtzustand | Information | binär, numerisch
| LICHT_HELLIGKEIT | Lichthelligkeit | Information | numeric
| HELLE FARBE | Helle Farbe | Information | string
| LIGHT_STATE_BOOL | Lichtzustand (Binär) | Information | binary
| LIGHT_COLOR_TEMP | Lichttemperatur Farbe | Information | numeric
| LIGHT_TOGGLE | Licht umschalten | Aktion | other
| LICHT AN | Lichttaste Ein | Aktion | other
| LICHT AUS | Lichttaste aus | Aktion | other
| LIGHT_SLIDER | Lichtschieber | Aktion | slider
| LIGHT_SET_COLOR | Helle Farbe | Aktion | color
| LIGHT_MODE | Lichtmodus | Aktion | other
| LIGHT_SET_COLOR_TEMP | Lichttemperatur Farbe | Aktion |

| **Modus (id: Mode)** | | | |
|:--------|:----------------|:--------:|:---------:|
| MODE_STATUS | Statusmodus | Information | string
| MODE_SET_STATE | Modus ändern | Aktion | other

| **Multimedia (id: Multimedia)** | | | |
|:--------|:----------------|:--------:|:---------:|
| Volumen | Volumen | Information | numeric
| MEDIEN_STATUS | Status | Information | string
| MEDIA_ALBUM | Album | Information | string
| MEDIEN_KÜNSTLER | Künstler | Information | string
| MEDIEN_TITEL | Titel | Information | string
| MEDIEN_MACHT | Leistung | Information | string
| KANAL | Kette | Information | numerisch, Zeichenfolge
| MEDIEN_STATUS | Zustand | Information | binary
| SET_VOLUME | Volumen | Aktion | slider
| SET_CHANNEL | Kette | Aktion | anderer Schieberegler
| MEDIEN_PAUSE | Brechen | Aktion | other
| MEDIA_RESUME | Lektüre | Aktion | other
| MEDIEN_STOP | Stoppen | Aktion | other
| MEDIEN_NÄCHSTES | Nachfolgend | Aktion | other
| MEDIEN_VORHER | Vorherige | Aktion | other
| MEDIEN_EIN | Wir | Aktion | other
| MEDIEN_AUS | Aus | Aktion | other
| MEDIEN_STUMM | Stumm | Aktion | other
| MEDIA_UNMUTE | Keine Stummschaltung | Aktion | other

| **Wetter (id: Weather)** | | | |
|:--------|:----------------|:--------:|:---------:|
| WEATHER_TEMPERATURE | Wetter Temperatur | Information | numeric
| WEATHER_TEMPERATURE_MAX_2 | Wetterbedingung d + 1 max d + 2 | Information | numeric
| WINDGESCHWINDIGKEIT | Windgeschwindigkeit) | Information | numeric
| REGEN_TOTAL | Regen (Akkumulation)) | Information | numeric
| REGEN_AKTUELL | Regen (mm / h) | Information | numeric
| WEATHER_CONDITION_ID_4 | Wetterbedingung (id) d + 4 | Information | numeric
| WEATHER_CONDITION_4 | Wetterbedingungen d + 4 | Information | string
| WEATHER_TEMPERATURE_MAX_4 | Wetter Max Temperatur d + 4 | Information | numeric
| WEATHER_TEMPERATURE_MIN_4 | Wettertemperatur min d + 4 | Information | numeric
| WEATHER_CONDITION_ID_3 | Wetterbedingung (id) d + 3 | Information | numeric
| WEATHER_CONDITION_3 | Wetterbedingung d + 3 | Information | string
| WEATHER_TEMPERATURE_MAX_3 | Wetter Max Temperatur d + 3 | Information | numeric
| WEATHER_TEMPERATURE_MIN_3 | Wetter Temperatur min d + 3 | Information | numeric
| WEATHER_CONDITION_ID_2 | Wetterbedingung (id) d + 2 | Information | numeric
| WEATHER_CONDITION_2 | Wetterbedingungen d + 2 | Information | string
| WEATHER_TEMPERATURE_MIN_2 | Wetter Temperatur min d + 2 | Information | numeric
| WETTER_FEUCHTE | Wetter Luftfeuchtigkeit | Information | numeric
| WEATHER_CONDITION_ID_1 | Wetterbedingung (id) d + 1 | Information | numeric
| WEATHER_CONDITION_1 | Wetterbedingung d + 1 | Information | string
| WEATHER_TEMPERATURE_MAX_1 | Wetter Max Temperatur d + 1 | Information | numeric
| WEATHER_TEMPERATURE_MIN_1 | Wetter Temperatur min d + 1 | Information | numeric
| WEATHER_CONDITION_ID | Wetterbedingungen (id) | Information | numeric
| WETTERLAGE | Wetterlage | Information | string
| WEATHER_TEMPERATURE_MAX | Wetter Höchsttemperatur | Information | numeric
| WEATHER_TEMPERATURE_MIN | Wetter Temperatur min | Information | numeric
| WEATHER_SONNENAUFGANG | Sonnenuntergang Wetter | Information | numeric
| WETTER_SONNENUNTERGANG | Sonnenaufgang Wetter | Information | numeric
| WETTER_WIND_RICHTUNG | Windrichtung Wetter | Information | numeric
| WEATHER_WIND_SPEED | Wetter mit Windgeschwindigkeit | Information | numeric
| WETTER_DRUCK | Wetterdruck | Information | numeric
| WINDRICHTUNG | Windrichtung) | Information | numeric

| **Eröffnung (id: Opening)** | | | |
|:--------|:----------------|:--------:|:---------:|
| LOCK_STATE | Zustandssperre | Information | binary
| BARRIER_STATE | Portal (Öffnen) Status | Information | binary
| GARAGE_STAAT | Garage (Eröffnung) Zustand | Information | binary
| ÖFFNUNG | Tür | Information | binary
| OPENING_WINDOW | Fenster | Information | binary
| LOCK_OPEN | Sperrtaste öffnen | Aktion | other
| LOCK_CLOSE | Sperrtaste Schließen | Aktion | other
| GB_OPEN | Tor- oder Garagenöffnungstaste | Aktion | other
| GB_CLOSE | Tor- oder Garagenschließtaster | Aktion | other
| GB_TOGGLE | Tor- oder Garagentaste umschalten | Aktion | other

| **Buchse (id: Outlet)** | | | |
|:--------|:----------------|:--------:|:---------:|
| ENERGIE_STATE | Zustandssteckdose | Information | numerisch, binär
| ENERGIE_EIN | Auf Knopfleiste | Aktion | other
| ENERGIE_AUS | Steckdosentaste Aus | Aktion | other
| ENERGY_SLIDER | Schieberbuchse | Aktion |

| **Roboter (id: Robot)** | | | |
|:--------|:----------------|:--------:|:---------:|
| DOCK_STATE | Staatliche Basis | Information | binary
| DOCK | Zurück zur basis | Aktion | other

| **Sicherheit (id: Security)** | | | |
|:--------|:----------------|:--------:|:---------:|
| SIREN_STATE | Meerjungfrauenstaat | Information | binary
| ALARM_ZUSTAND | Alarmstatus | Information | binär, string
| ALARM_MODUS | Alarmmodus | Information | string
| ALARM_ENABLE_STATE | Alarmstatus aktiviert | Information | binary
| FLUT | Flut | Information | binary
| SABOTAGE | SABOTAGE | Information | binary
| SCHOCK | Schock | Information | binär, numerisch
| SIRENE_AUS | Sirenentaste aus | Aktion | other
| SIRENE_EIN | Sirenenknopf Ein | Aktion | other
| ALARM_SCHARF | Bewaffneter Alarm | Aktion | other
| ALARM_FREIGEGEBEN | Alarm ausgelöst | Aktion | other
| ALARM_SET_MODE | Alarmmodus | Aktion | other

| **Thermostat (id: Thermostat)** | | | |
|:--------|:----------------|:--------:|:---------:|
| THERMOSTAT_ZUSTAND | Thermostatstatus (BINÄR) (nur für Plugin-Thermostat) | Information |
| THERMOSTAT_TEMPERATUR | Raumtemperaturregler | Information | numeric
| THERMOSTAT_SETPOINT | Sollwertthermostat | Information | numeric
| THERMOSTAT_MODUS | Thermostat-Modus (nur für Plugin-Thermostat)) | Information | string
| THERMOSTAT_SPERRE | Sperrthermostat (nur für Plugin-Thermostat)) | Information | binary
| THERMOSTAT_TEMPERATUR_AUSSEN | Außentemperatur-Thermostat (nur für Plugin-Thermostat)) | Information | numeric
| THERMOSTAT_STATE_NAME | Thermostatstatus (MENSCH) (nur für Plugin-Thermostat) | Information | string
| THERMOSTAT_FEUCHTE | Raumfeuchtigkeitsthermostat | Information | numeric
| HUMIDITY_SETPOINT | Luftfeuchtigkeit einstellen | Information | slider
| THERMOSTAT_SET_SETPOINT | Sollwertthermostat | Aktion | slider
| THERMOSTAT_SET_MODE | Thermostat-Modus (nur für Plugin-Thermostat)) | Aktion | other
| THERMOSTAT_SET_LOCK | Sperrthermostat (nur für Plugin-Thermostat)) | Aktion | other
| THERMOSTAT_SET_UNLOCK | Thermostat entsperren (nur für Plugin-Thermostat)) | Aktion | other
| HUMIDITY_SET_SETPOINT | Luftfeuchtigkeit einstellen | Aktion | slider

| **Lüfter (id: Fan)** | | | |
|:--------|:----------------|:--------:|:---------:|
| FAN_SPEED_STATE | Lüftergeschwindigkeit Status | Information | numeric
| ROTATION_STATUS | Zustandsrotation | Information | numeric
| LÜFTERGESCHWINDIGKEIT | Lüftergeschwindigkeit | Aktion | slider
| DREHUNG | DREHUNG | Aktion | slider

| **Bereich (id: Shutter)** | | | |
|:--------|:----------------|:--------:|:---------:|
| FLAP_STATE | Statusbereich | Information | binär, numerisch
| FLAP_BSO_STATE | Bereich BSO-Status | Information | binär, numerisch
| FLAP_UP | Schaltfläche „Fenster nach oben“ | Aktion | other
| FLAP_DOWN | Schaltfläche „Fenster nach unten“ | Aktion | other
| FLAP_STOP | Stopptaste Auslöser | Aktion |
| FLAP_SLIDER | Bereich mit Schieberegler | Aktion | slider
| FLAP_BSO_UP | Schaltfläche BSO-Fenster nach oben | Aktion | other
| FLAP_BSO_DOWN | Schaltfläche BSO-Fenster nach unten | Aktion | other
