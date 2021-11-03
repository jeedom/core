# Gerätearten
**Werkzeuge → Gerätetypen**

Die Sensoren und Aktoren in Jeedom werden von Plugins verwaltet, die Geräte mit Befehlen erstellen *Die Info* (Sensor) oder *Handlung* (Antrieb). Dadurch ist es dann möglich, Aktionen basierend auf der Änderung bestimmter Sensoren auszulösen, wie z.B. das Einschalten eines Lichts bei Bewegungserkennung. Aber der Jeedom Core und Plugins wie *Handy, Mobiltelefon*, *Homebridge*, *Google-Smarthome*, *Alexa-Smarthome* etc., weiß nicht was das für ein Gerät ist : Eine Steckdose, ein Licht, ein Rollladen usw.

Um dieses Problem zu lösen, insbesondere bei Sprachassistenten (*Mach das Zimmerlicht an*), Core stellte die . vor **Generische Typen**, von diesen Plugins verwendet.

Dies ermöglicht die Identifizierung eines Gerätes durch *Das Licht des Zimmers* zum Beispiel.

Meistens werden die generischen Typen automatisch bei der Konfiguration Ihres Moduls gesetzt (z.B. Aufnahme in Z-Wave). Es kann jedoch vorkommen, dass Sie sie neu konfigurieren müssen. Die Konfiguration dieser generischen Typen kann direkt in bestimmten Plugins oder per Befehl in . erfolgen *Erweiterte Konfiguration* davon.

Diese Seite ermöglicht eine direktere und einfachere Konfiguration dieser Generic Types und bietet sogar eine automatische Zuweisung nach korrekter Zuweisung der Geräte.

![Gerätearten](./images/coreGenerics.gif)

## Ausstattungsart

Diese Seite bietet Lagerung nach Gerätetyp : Steckdose, Licht, Shutter, Thermostat, Kamera usw. Zunächst werden die meisten Ihrer Geräte klassifiziert in **Geräte ohne Typ**. Um ihnen einen Typ zuzuweisen, können Sie sie entweder auf einen anderen Typ verschieben oder mit der rechten Maustaste auf das Gerät klicken, um es direkt zu verschieben. Der Ausrüstungstyp ist an sich nicht wirklich nützlich, am wichtigsten sind die Auftragstypen. Sie können also eine Ausrüstung ohne Typ haben oder einen Typ, der nicht unbedingt seinen Befehlen entspricht. Sie können natürlich verschiedene Steuerungstypen innerhalb derselben Ausrüstung mischen. Im Moment ist es eher ein Speicher, eine logische Organisation, die vielleicht in zukünftigen Versionen dienen wird.

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

## Generische Typen und Szenarien

In v4.2 hat der Core die generischen Typen in die Szenarien integriert. So können Sie ein Szenario auslösen, wenn in einem Raum eine Lampe angeht, eine Bewegung im Haus erkannt wird, alle Lichter ausgeschaltet oder alle Rollläden mit einer einzigen Aktion geschlossen werden usw. Wenn Sie eine Ausrüstung hinzufügen, müssen Sie außerdem nur die richtigen Typen in ihren Bestellungen angeben, solche Szenarien müssen nicht bearbeitet werden.

#### Abzug

Sie können ein Szenario über Sensoren auslösen. Wenn Sie beispielsweise Bewegungsmelder im Haus haben, können Sie ein Alarmszenario erstellen, bei dem jeder Melder auslöst : ``#[Salon][Move Salon][Presence]# == 1`, `#[Cuisine][Move Cuisine][Presence]# == 1` usw.. In einem solchen Szenario benötigen Sie daher alle Ihre Bewegungsmelder, und wenn Sie einen hinzufügen, müssen Sie ihn zu den Auslösern hinzufügen. Logik.

Generische Typen ermöglichen die Verwendung eines einzigen Triggers : ``#genericType(PRESENCE)# == 1`. Hier wird kein Objekt angezeigt, so dass die kleinste Bewegung im ganzen Haus das Szenario auslöst. Wenn Sie einen neuen Melder im Haus hinzufügen, müssen Sie das Szenario (die Szenarien) nicht bearbeiten).

Hier ein Auslöser beim Einschalten eines Lichts im Wohnzimmer : ``#genericType(LIGHT_STATE,#[Salon]#)# > 0`

#### Expression

Wenn Sie in einem Szenario wissen möchten, ob im Wohnzimmer Licht brennt, können Sie dies tun :

WENN `#[Salon][Lumiere Canapé][Zustand]# == 1 ODER #[Salon][Lumiere Salon][Zustand]# == 1 ODER #[Salon][Lumiere Angle][Zustand]# == 1`

Oder einfacher : IF `genericType (LIGHT_STATE .),#[Salon]#) > 0` oder wenn ein oder mehrere Licht(e) im Wohnzimmer leuchten.

Wenn Sie morgen ein Licht in Ihrem Wohnzimmer hinzufügen, müssen Sie Ihre Szenarien nicht retuschieren !


#### Action

Wenn Sie alle Lichter im Wohnzimmer einschalten möchten, können Sie eine Lichtaktion erstellen:

`` ``
#[Salon][Lumiere Canapé][Wir]#
#[Salon][Lumiere Salon][Wir]#
#[Salon][Lumiere Angle][Wir]#
`` ``

Oder einfacher, erstellen Sie eine `genericType`-Aktion mit `LIGHT_ON` in `Salon`. Wenn Sie morgen ein Licht in Ihrem Wohnzimmer hinzufügen, müssen Sie Ihre Szenarien nicht retuschieren !


## Liste der generischen Kerntypen

> **Spitze**
>
> - Sie finden diese Liste direkt in Jeedom, auf derselben Seite, mit dem Button **Auflistung** oben rechts.

| **Andere (id: Other)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TIMER | Status-Timer | Die Info | numeric
| TIMER_STATE | Timer-Status (Pause oder nicht)) | Die Info | binär, numerisch
| SET_TIMER | Timer | Handlung | slider
| TIMER_PAUSE | Pause-Timer | Handlung | other
| TIMER_RESUME | Timer fortsetzen | Handlung | other

| **Batterie (id: Battery)** | | | |
|:--------|:----------------|:--------:|:---------:|
| BATTERIE | Schlagzeug | Die Info | numeric
| BATTERY_CHARGING | Aufladen des Akkus | Die Info | binary

| **Kamera (id: Camera)** | | | |
|:--------|:----------------|:--------:|:---------:|
| KAMERA_URL | Kamera-URL | Die Info | string
| CAMERA_RECORD_STATE | Aufnahmestatus der Kamera | Die Info | binary
| CAMERA_UP | Kamerabewegung nach oben | Handlung | other
| KAMERA RUNTER | Kamerabewegung nach unten | Handlung | other
| KAMERA_LEFT | Kamerabewegung nach links | Handlung | other
| CAMERA_RIGHT | Kamerabewegung nach rechts | Handlung | other
| KAMERA_ZOOM | Kamera nach vorne zoomen | Handlung | other
| CAMERA_DEZOOM | Kamerarückseite zoomen | Handlung | other
| KAMERA_STOP | Kamera stoppen | Handlung | other
| CAMERA_PRESET | Kameravoreinstellung | Handlung | other
| CAMERA_RECORD | Kameraaufnahme | Handlung |
| CAMERA_TAKE | Schnappschuss-Kamera | Handlung |

| **Heizung (id: Heating)** | | | |
|:--------|:----------------|:--------:|:---------:|
| HEATING_STATE | Status der Pilotdrahtheizung | Die Info | binary
| AUFHEIZEN | Taste Steuerdrahtheizung EIN | Handlung | other
| HEIZUNG_AUS | Taste Pilotdrahtheizung AUS | Handlung | other
| HEATING_OTHER | Heizsteuerdraht Taste | Handlung | other

| **Strom (id: Electricity)** | | | |
|:--------|:----------------|:--------:|:---------:|
| ENERGIE | Elektrische Energie | Die Info | numeric
| VERBRAUCH | Energieverbrauch | Die Info | numeric
| STROMSPANNUNG | Stromspannung | Die Info | numeric
| NEUSTART | Neu starten | Handlung | other

| **Umgebung (id: Environment)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TEMPERATUR | TEMPERATUR | Die Info | numeric
| LUFTQUALITÄT | Luftqualität | Die Info | numeric
| HELLIGKEIT | Helligkeit | Die Info | numeric
| GEGENWART | GEGENWART | Die Info | binary
| RAUCH | Rauchmelder | Die Info | binary
| FEUCHTIGKEIT | Feuchtigkeit | Die Info | numeric
| UV | UV | Die Info | numeric
| CO2 | CO2 (ppm) | Die Info | numeric
| CO | CO (ppm) | Die Info | numeric
| LÄRM | Ton (dB) | Die Info | numeric
| DRUCK | Druck | Die Info | numeric
| WASSERLECK | Wasserleck | Die Info |
| FILTER_CLEAN_STATE | Filterstatus | Die Info | binary

| **Generisch (id: Generic)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TIEFE | Tiefe | Die Info | numeric
| DISTANZ | DISTANZ | Die Info | numeric
| TASTE | Taste | Die Info | binär, numerisch
| GENERIC_INFO |  Generisch | Die Info |
| GENERIC_ACTION |  Generisch | Handlung | other

| **Licht (id: Light)** | | | |
|:--------|:----------------|:--------:|:---------:|
| LIGHT_STATE | Lichtzustand | Die Info | binär, numerisch
| LICHT_HELLIGKEIT | Lichthelligkeit | Die Info | numeric
| HELLE FARBE | Helle Farbe | Die Info | string
| LIGHT_STATE_BOOL | Lichtzustand (Binär) | Die Info | binary
| LIGHT_COLOR_TEMP | Lichttemperatur Farbe | Die Info | numeric
| LIGHT_TOGGLE | Licht umschalten | Handlung | other
| LICHT AN | Lichttaste Ein | Handlung | other
| LICHT AUS | Lichttaste aus | Handlung | other
| LIGHT_SLIDER | Lichtschieber | Handlung | slider
| LIGHT_SET_COLOR | Helle Farbe | Handlung | color
| LIGHT_MODE | Lichtmodus | Handlung | other
| LIGHT_SET_COLOR_TEMP | Lichttemperatur Farbe | Handlung |

| **Modus (id: Mode)** | | | |
|:--------|:----------------|:--------:|:---------:|
| MODE_STATE | Statusmodus | Die Info | string
| MODE_SET_STATE | Modus ändern | Handlung | other

| **Multimedia (id: Multimedia)** | | | |
|:--------|:----------------|:--------:|:---------:|
| Volumen | Volumen | Die Info | numeric
| MEDIA_STATUS | Status | Die Info | string
| MEDIA_ALBUM | Album | Die Info | string
| MEDIA_ARTIST | Künstler | Die Info | string
| MEDIA_TITLE | Titel | Die Info | string
| MEDIA_POWER | ENERGIE | Die Info | string
| KANAL | Kette | Die Info | numerisch, Zeichenfolge
| MEDIA_STATE | Zustand | Die Info | binary
| SET_VOLUME | Volumen | Handlung | slider
| SET_CHANNEL | Kette | Handlung | anderer Schieberegler
| MEDIA_PAUSE | Pause | Handlung | other
| MEDIA_RESUME | Lektüre | Handlung | other
| MEDIA_STOP | Halt | Handlung | other
| MEDIA_NEXT | Folge | Handlung | other
| MEDIA_PREVIOUS | Vorherige | Handlung | other
| MEDIA_ON | Wir | Handlung | other
| MEDIA_OFF | Aus | Handlung | other
| MEDIA_MUTE | Stumm | Handlung | other
| MEDIA_UNMUTE | Keine Stummschaltung | Handlung | other

| **Wetter (id: Weather)** | | | |
|:--------|:----------------|:--------:|:---------:|
| WEATHER_TEMPERATURE | Wetter Temperatur | Die Info | numeric
| WEATHER_TEMPERATURE_MAX_2 | Wetterbedingung d + 1 max d + 2 | Die Info | numeric
| WINDGESCHWINDIGKEIT | Windgeschwindigkeit) | Die Info | numeric
| REGEN_TOTAL | Regen (Akkumulation)) | Die Info | numeric
| REGEN_AKTUELL | Regen (mm / h) | Die Info | numeric
| WEATHER_CONDITION_ID_4 | Wetterbedingung (id) d + 4 | Die Info | numeric
| WEATHER_CONDITION_4 | Wetterbedingungen d + 4 | Die Info | string
| WEATHER_TEMPERATURE_MAX_4 | Wetter Max Temperatur d + 4 | Die Info | numeric
| WEATHER_TEMPERATURE_MIN_4 | Wettertemperatur min d + 4 | Die Info | numeric
| WEATHER_CONDITION_ID_3 | Wetterbedingung (id) d + 3 | Die Info | numeric
| WEATHER_CONDITION_3 | Wetterbedingung d + 3 | Die Info | string
| WEATHER_TEMPERATURE_MAX_3 | Wetter Max Temperatur d + 3 | Die Info | numeric
| WEATHER_TEMPERATURE_MIN_3 | Wetter Temperatur min d + 3 | Die Info | numeric
| WEATHER_CONDITION_ID_2 | Wetterbedingung (id) d + 2 | Die Info | numeric
| WEATHER_CONDITION_2 | Wetterbedingungen d + 2 | Die Info | string
| WEATHER_TEMPERATURE_MIN_2 | Wetter Temperatur min d + 2 | Die Info | numeric
| WEATHER_HUMIDITY | Wetter Luftfeuchtigkeit | Die Info | numeric
| WEATHER_CONDITION_ID_1 | Wetterbedingung (id) d + 1 | Die Info | numeric
| WEATHER_CONDITION_1 | Wetterbedingung d + 1 | Die Info | string
| WEATHER_TEMPERATURE_MAX_1 | Wetter Max Temperatur d + 1 | Die Info | numeric
| WEATHER_TEMPERATURE_MIN_1 | Wetter Temperatur min d + 1 | Die Info | numeric
| WEATHER_CONDITION_ID | Wetterbedingungen (id) | Die Info | numeric
| WETTERLAGE | Wetterlage | Die Info | string
| WEATHER_TEMPERATURE_MAX | Wetter Höchsttemperatur | Die Info | numeric
| WEATHER_TEMPERATURE_MIN | Wetter Temperatur min | Die Info | numeric
| WEATHER_SUNRISE | Sonnenuntergang Wetter | Die Info | numeric
| WEATHER_SUNSET | Sonnenaufgang Wetter | Die Info | numeric
| WEATHER_WIND_DIRECTION | Windrichtung Wetter | Die Info | numeric
| WEATHER_WIND_SPEED | Wetter mit Windgeschwindigkeit | Die Info | numeric
| WEATHER_PRESSURE | Wetterdruck | Die Info | numeric
| WINDRICHTUNG | Windrichtung) | Die Info | numeric

| **Eröffnung (id: Opening)** | | | |
|:--------|:----------------|:--------:|:---------:|
| LOCK_STATE | Zustandssperre | Die Info | binary
| BARRIER_STATE | Portal (Öffnen) Status | Die Info | binary
| GARAGE_STATE | Garage (Eröffnung) Zustand | Die Info | binary
| ÖFFNUNG | Tür | Die Info | binary
| OPENING_WINDOW | Fenster | Die Info | binary
| LOCK_OPEN | Sperrtaste öffnen | Handlung | other
| LOCK_CLOSE | Sperrtaste Schließen | Handlung | other
| GB_OPEN | Tor- oder Garagenöffnungstaste | Handlung | other
| GB_CLOSE | Tor- oder Garagenschließtaster | Handlung | other
| GB_TOGGLE | Tor- oder Garagentaste umschalten | Handlung | other

| **Buchse (id: Outlet)** | | | |
|:--------|:----------------|:--------:|:---------:|
| ENERGIE_STATE | Zustandssteckdose | Die Info | numerisch, binär
| ENERGIE_EIN | Auf Knopfleiste | Handlung | other
| ENERGIE_AUS | Steckdosentaste Aus | Handlung | other
| ENERGY_SLIDER | Schieberbuchse | Handlung |

| **Roboter (id: Robot)** | | | |
|:--------|:----------------|:--------:|:---------:|
| DOCK_STATE | Staatliche Basis | Die Info | binary
| DOCK | Zurück zur basis | Handlung | other

| **Sicherheit (id: Security)** | | | |
|:--------|:----------------|:--------:|:---------:|
| SIREN_STATE | Meerjungfrauenstaat | Die Info | binary
| ALARM_STATE | Alarmstatus | Die Info | binär, string
| ALARM_MODE | Alarmmodus | Die Info | string
| ALARM_ENABLE_STATE | Alarmstatus aktiviert | Die Info | binary
| FLUT | Flut | Die Info | binary
| SABOTAGE | SABOTAGE | Die Info | binary
| SCHOCK | Schock | Die Info | binär, numerisch
| SIREN_OFF | Sirenentaste aus | Handlung | other
| SIREN_ON | Sirenenknopf Ein | Handlung | other
| ALARM_ARMED | Bewaffneter Alarm | Handlung | other
| ALARM_RELEASED | Alarm ausgelöst | Handlung | other
| ALARM_SET_MODE | Alarmmodus | Handlung | other

| **Thermostat (id: Thermostat)** | | | |
|:--------|:----------------|:--------:|:---------:|
| THERMOSTAT_STATE | Thermostatstatus (BINÄR) (nur für Plugin-Thermostat) | Die Info |
| THERMOSTAT_TEMPERATURE | Raumtemperaturregler | Die Info | numeric
| THERMOSTAT_SETPOINT | Sollwertthermostat | Die Info | numeric
| THERMOSTAT_MODE | Thermostat-Modus (nur für Plugin-Thermostat)) | Die Info | string
| THERMOSTAT_LOCK | Sperrthermostat (nur für Plugin-Thermostat)) | Die Info | binary
| THERMOSTAT_TEMPERATURE_OUTDOOR | Außentemperatur-Thermostat (nur für Plugin-Thermostat)) | Die Info | numeric
| THERMOSTAT_STATE_NAME | Thermostatstatus (MENSCH) (nur für Plugin-Thermostat) | Die Info | string
| THERMOSTAT_HUMIDITY | Raumfeuchtigkeitsthermostat | Die Info | numeric
| HUMIDITY_SETPOINT | Luftfeuchtigkeit einstellen | Die Info | slider
| THERMOSTAT_SET_SETPOINT | Sollwertthermostat | Handlung | slider
| THERMOSTAT_SET_MODE | Thermostat-Modus (nur für Plugin-Thermostat)) | Handlung | other
| THERMOSTAT_SET_LOCK | Sperrthermostat (nur für Plugin-Thermostat)) | Handlung | other
| THERMOSTAT_SET_UNLOCK | Thermostat entsperren (nur für Plugin-Thermostat)) | Handlung | other
| HUMIDITY_SET_SETPOINT | Luftfeuchtigkeit einstellen | Handlung | slider

| **Lüfter (id: Fan)** | | | |
|:--------|:----------------|:--------:|:---------:|
| FAN_SPEED_STATE | Lüftergeschwindigkeit Status | Die Info | numeric
| ROTATION_STATE | Zustandsrotation | Die Info | numeric
| LÜFTERGESCHWINDIGKEIT | Lüftergeschwindigkeit | Handlung | slider
| DREHUNG | DREHUNG | Handlung | slider

| **Bereich (id: Shutter)** | | | |
|:--------|:----------------|:--------:|:---------:|
| FLAP_STATE | Statusbereich | Die Info | binär, numerisch
| FLAP_BSO_STATE | Bereich BSO-Status | Die Info | binär, numerisch
| FLAP_UP | Schaltfläche „Fenster nach oben“ | Handlung | other
| FLAP_DOWN | Schaltfläche „Fenster nach unten“ | Handlung | other
| FLAP_STOP | Stopptaste Auslöser | Handlung |
| FLAP_SLIDER | Bereich mit Schieberegler | Handlung | slider
| FLAP_BSO_UP | Schaltfläche BSO-Fenster nach oben | Handlung | other
| FLAP_BSO_DOWN | Schaltfläche BSO-Fenster nach unten | Handlung | other