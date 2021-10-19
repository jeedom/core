# Gerätearten
**Werkzeuge → Gerätetypen**

Die Sensoren und Aktoren in Jeedom werden von Plugins verwaltet, die Geräte mit Befehlen erstellen *Die Info* (Sensor) oder *Handlung* (Antrieb). Dadurch ist es dann möglich, Aktionen basierend auf der Änderung bestimmter Sensoren auszulösen, wie z.B. das Einschalten eines Lichts bei Bewegungserkennung. Aber der Jeedom Core und Plugins wie *Handy, Mobiltelefon*, *Homebridge*, *Google-Smarthome*, *Alexa* etc., weiß nicht was das für ein Gerät ist : Eine Steckdose, ein Licht, ein Rollladen usw.

Um dieses Problem zu lösen, insbesondere bei Sprachassistenten (*Mach das Zimmerlicht an*), Core stellte die . vor **Generische Typen**, von diesen Plugins verwendet.

Dies ermöglicht die Identifizierung eines Gerätes durch *Das Licht des Zimmers* zum Beispiel.

Auch generische Typen sind in Szenarien integriert. So können Sie ein Szenario auslösen, wenn in einem Raum eine Lampe angeht, eine Bewegung im Haus erkannt wird, alle Lichter ausgeschaltet oder alle Rollläden mit einer einzigen Aktion geschlossen werden usw. Wenn Sie eine Ausrüstung hinzufügen, müssen Sie außerdem nur diese Typen angeben, es ist nicht erforderlich, solche Szenarien zu bearbeiten.

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


## Liste der generischen Kerntypen

| **Andere (id: Other)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TIMER | Statustimer (Nicht von der mobilen Anwendung verwaltet) | Die Info | numeric
| TIMER_STATE | Timer-Status (Pause oder nicht) (Nicht von der mobilen Anwendung verwaltet) | Die Info | binär, numerisch
| SET_TIMER | Timer (Nicht von der mobilen Anwendung verwaltet) | Handlung | slider
| TIMER_PAUSE | Pausentimer (Nicht von der mobilen Anwendung verwaltet) | Handlung | other
| TIMER_RESUME | Timer-Resume (Nicht von der mobilen Anwendung verwaltet) | Handlung | other

| **Batterie (id: Battery)** | | | |
|:--------|:----------------|:--------:|:---------:|
| BATTERIE | Akku (Nicht von der mobilen Anwendung verwaltet) | Die Info | numeric
| BATTERY_CHARGING | Aufladen des Akkus (wird nicht von der mobilen Anwendung verwaltet) | Die Info | binary

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
| VERBRAUCH | Stromverbrauch (Nicht von der mobilen Anwendung verwaltet) | Die Info | numeric
| STROMSPANNUNG | Spannung (Nicht von der mobilen Anwendung verwaltet) | Die Info | numeric
| NEUSTART | Neustart (Nicht von Application Mobile verwaltet) | Handlung | other

| **Umgebung (id: Environment)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TEMPERATUR | Temperatur | Die Info | numeric
| LUFTQUALITÄT | Luftqualität | Die Info | numeric
| HELLIGKEIT | Helligkeit | Die Info | numeric
| GEGENWART | Gegenwart | Die Info | binary
| RAUCH | Rauchmelder | Die Info | binary
| FEUCHTIGKEIT | Feuchtigkeit | Die Info | numeric
| UV | UV (Nicht von Application Mobile verwaltet) | Die Info | numeric
| CO2 | CO2 (ppm) (Nicht von der mobilen Anwendung verwaltet) | Die Info | numeric
| CO | CO (ppm) (Nicht von der mobilen Anwendung verwaltet) | Die Info | numeric
| LÄRM | Ton (dB) (Nicht von der mobilen Anwendung verwaltet) | Die Info | numeric
| DRUCK | Druck (wird nicht von der mobilen Anwendung verwaltet) | Die Info | numeric
| WASSERLECK | Wasserleck (Nicht von der mobilen Anwendung verwaltet) | Die Info |
| FILTER_CLEAN_STATE | Filterstatus (Nicht von Application Mobile verwaltet) | Die Info | binary

| **Generisch (id: Generic)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TIEFE | Tiefe | Die Info | numeric
| DISTANZ | Distanz | Die Info | numeric
| TASTE | Taste | Die Info | binär, numerisch
| GENERIC_INFO |  Generisch | Die Info |
| GENERIC_ACTION |  Generisch | Handlung | other

| **Licht (id: Light)** | | | |
|:--------|:----------------|:--------:|:---------:|
| LIGHT_STATE | Lichtzustand | Die Info | binär, numerisch
| LICHT_HELLIGKEIT | Lichthelligkeit (Nicht von der mobilen Anwendung verwaltet) | Die Info | numeric
| HELLE FARBE | Helle Farbe | Die Info | string
| LIGHT_STATE_BOOL | Lichtstatus (binär) (Nicht von der mobilen Anwendung verwaltet) | Die Info | binary
| LIGHT_COLOR_TEMP | Lichttemperatur Farbe (Nicht von der mobilen Anwendung verwaltet) | Die Info | numeric
| LIGHT_TOGGLE | Licht umschalten | Handlung | other
| LICHT AN | Lichttaste Ein | Handlung | other
| LICHT AUS | Lichttaste aus | Handlung | other
| LIGHT_SLIDER | Lichtschieber | Handlung | slider
| LIGHT_SET_COLOR | Helle Farbe | Handlung | color
| LIGHT_MODE | Lichtmodus | Handlung | other
| LIGHT_SET_COLOR_TEMP | Lichttemperatur Farbe (Nicht von der mobilen Anwendung verwaltet) | Handlung |

| **Modus (id: Mode)** | | | |
|:--------|:----------------|:--------:|:---------:|
| MODE_STATE | Statusmodus | Die Info | string
| MODE_SET_STATE | Modus ändern | Handlung | other

| **Multimedia (id: Multimedia)** | | | |
|:--------|:----------------|:--------:|:---------:|
| VOLUMEN | Volumen | Die Info | numeric
| MEDIA_STATUS | Status (Nicht von Application Mobile verwaltet) | Die Info | string
| MEDIA_ALBUM | Album (Nicht von Application Mobile verwaltet) | Die Info | string
| MEDIA_ARTIST | Künstler (Nicht von Application Mobile verwaltet) | Die Info | string
| MEDIA_TITLE | Titel (Nicht von Application Mobile verwaltet) | Die Info | string
| MEDIA_POWER | Leistung (Nicht von Application Mobile verwaltet) | Die Info | string
| KANAL | Kette | Die Info | numerisch, Zeichenfolge
| MEDIA_STATE | Status (Nicht von Application Mobile verwaltet) | Die Info | binary
| SET_VOLUME | Volumen | Handlung | slider
| SET_CHANNEL | Kette | Handlung | anderer Schieberegler
| MEDIA_PAUSE | Pause | Handlung | other
| MEDIA_RESUME | Lektüre | Handlung | other
| MEDIA_STOP | Halt | Handlung | other
| MEDIA_NEXT | Folge | Handlung | other
| MEDIA_PREVIOUS | Vorherige | Handlung | other
| MEDIA_ON | Ein (Nicht von Application Mobile verwaltet) | Handlung | other
| MEDIA_OFF | Aus (Nicht von der mobilen Anwendung verwaltet) | Handlung | other
| MEDIA_MUTE | Stumm (nicht von Application Mobile verwaltet) | Handlung | other
| MEDIA_UNMUTE | Keine Stummschaltung (Nicht von der mobilen Anwendung verwaltet) | Handlung | other

| **Wetter (id: Weather)** | | | |
|:--------|:----------------|:--------:|:---------:|
| WEATHER_TEMPERATURE | Wettertemperatur (Nicht von der mobilen Anwendung verwaltet) | Die Info | numeric
| WEATHER_TEMPERATURE_MAX_2 | Wetterbedingungen d + 1 max d + 2 (Nicht von der mobilen Anwendung verwaltet) | Die Info | numeric
| WINDGESCHWINDIGKEIT | Wind (Geschwindigkeit) (Nicht von der mobilen Anwendung verwaltet) | Die Info | numeric
| REGEN_TOTAL | Regen (Ansammlung) (Nicht von Application Mobile verwaltet) | Die Info | numeric
| REGEN_AKTUELL | Regen (mm / h) (Nicht von der mobilen Anwendung verwaltet) | Die Info | numeric
| WEATHER_CONDITION_ID_4 | Wetterbedingung (id) d + 4 (Nicht von der mobilen Anwendung verwaltet) | Die Info | numeric
| WEATHER_CONDITION_4 | Wetterbedingung d + 4 (Nicht von der mobilen Anwendung verwaltet) | Die Info | string
| WEATHER_TEMPERATURE_MAX_4 | Wetter Max. Temperatur d + 4 (Nicht von der mobilen Anwendung verwaltet) | Die Info | numeric
| WEATHER_TEMPERATURE_MIN_4 | Wettertemperatur min d + 4 (Nicht von der mobilen Anwendung verwaltet) | Die Info | numeric
| WEATHER_CONDITION_ID_3 | Wetterbedingung (id) d + 3 (Nicht von der mobilen Anwendung verwaltet) | Die Info | numeric
| WEATHER_CONDITION_3 | Wetterbedingung d + 3 (Nicht von der mobilen Anwendung verwaltet) | Die Info | string
| WEATHER_TEMPERATURE_MAX_3 | Wetter Max. Temperatur d + 3 (Nicht von der mobilen Anwendung verwaltet) | Die Info | numeric
| WEATHER_TEMPERATURE_MIN_3 | Wettertemperatur min d + 3 (Nicht von der mobilen Anwendung verwaltet) | Die Info | numeric
| WEATHER_CONDITION_ID_2 | Wetterbedingung (id) d + 2 (Nicht von der mobilen Anwendung verwaltet) | Die Info | numeric
| WEATHER_CONDITION_2 | Wetterbedingung d + 2 (Nicht von der mobilen Anwendung verwaltet) | Die Info | string
| WEATHER_TEMPERATURE_MIN_2 | Wettertemperatur min d + 2 (Nicht von der mobilen Anwendung verwaltet) | Die Info | numeric
| WEATHER_HUMIDITY | Wetterfeuchtigkeit (Nicht von der mobilen Anwendung verwaltet) | Die Info | numeric
| WEATHER_CONDITION_ID_1 | Wetterbedingung (id) d + 1 (Nicht von der mobilen Anwendung verwaltet) | Die Info | numeric
| WEATHER_CONDITION_1 | Wetterbedingung d + 1 (Nicht von der mobilen Anwendung verwaltet) | Die Info | string
| WEATHER_TEMPERATURE_MAX_1 | Wetter Max. Temperatur d + 1 (Nicht von der mobilen Anwendung verwaltet) | Die Info | numeric
| WEATHER_TEMPERATURE_MIN_1 | Wettertemperatur min d + 1 (Nicht von der mobilen Anwendung verwaltet) | Die Info | numeric
| WEATHER_CONDITION_ID | Wetterbedingung (ID) (Nicht von der mobilen Anwendung verwaltet) | Die Info | numeric
| WETTERLAGE | Wetterbedingungen (Nicht von der mobilen Anwendung verwaltet) | Die Info | string
| WEATHER_TEMPERATURE_MAX | Wetter Max. Temperatur (Nicht von der mobilen Anwendung verwaltet) | Die Info | numeric
| WEATHER_TEMPERATURE_MIN | Wettertemperatur min (Nicht von der mobilen Anwendung verwaltet) | Die Info | numeric
| WEATHER_SUNRISE | Sonnenuntergangswetter (Nicht von der mobilen Anwendung verwaltet) | Die Info | numeric
| WEATHER_SUNSET | Sonnenaufgangswetter (Nicht von der mobilen Anwendung verwaltet) | Die Info | numeric
| WEATHER_WIND_DIRECTION | Wettervorhersage für die Windrichtung (Nicht von der mobilen Anwendung verwaltet) | Die Info | numeric
| WEATHER_WIND_SPEED | Windgeschwindigkeits-Wettervorhersage (Nicht von der mobilen Anwendung verwaltet) | Die Info | numeric
| WEATHER_PRESSURE | Wetterdruck (Nicht von der mobilen Anwendung verwaltet) | Die Info | numeric
| WINDRICHTUNG | Wind (Richtung) (Nicht von Application Mobile verwaltet) | Die Info | numeric

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
| DOCK_STATE | State Base (Nicht von der mobilen Anwendung verwaltet) | Die Info | binary
| DOCK | Zurück zur Basis (Nicht von der mobilen Anwendung verwaltet) | Handlung | other

| **Sicherheit (id: Security)** | | | |
|:--------|:----------------|:--------:|:---------:|
| SIREN_STATE | Meerjungfrauenstaat | Die Info | binary
| ALARM_STATE | Alarmstatus (Nicht von der mobilen Anwendung verwaltet) | Die Info | binär, string
| ALARM_MODE | Alarmmodus (Nicht von der mobilen Anwendung verwaltet) | Die Info | string
| ALARM_ENABLE_STATE | Alarmstatus aktiviert (Nicht von der mobilen Anwendung verwaltet) | Die Info | binary
| FLUT | Flut | Die Info | binary
| SABOTAGE | Sabotage | Die Info | binary
| SCHOCK | Schock | Die Info | binär, numerisch
| SIREN_OFF | Sirenentaste aus | Handlung | other
| SIREN_ON | Sirenenknopf Ein | Handlung | other
| ALARM_ARMED | Scharfalarm (Nicht von der mobilen Anwendung verwaltet) | Handlung | other
| ALARM_RELEASED | Alarm ausgelöst (Nicht von der mobilen Anwendung verwaltet) | Handlung | other
| ALARM_SET_MODE | Alarmmodus (Nicht von der mobilen Anwendung verwaltet) | Handlung | other

| **Thermostat (id: Thermostat)** | | | |
|:--------|:----------------|:--------:|:---------:|
| THERMOSTAT_STATE | Thermostatstatus (BINÄR) (nur für Plugin-Thermostat) | Die Info |
| THERMOSTAT_TEMPERATURE | Raumtemperaturregler | Die Info | numeric
| THERMOSTAT_SETPOINT | Sollwertthermostat | Die Info | numeric
| THERMOSTAT_MODE | Thermostat-Modus (nur für Plugin-Thermostat)) | Die Info | string
| THERMOSTAT_LOCK | Sperrthermostat (nur für Plugin-Thermostat)) | Die Info | binary
| THERMOSTAT_TEMPERATURE_OUTDOOR | Außentemperatur-Thermostat (nur für Plugin-Thermostat)) | Die Info | numeric
| THERMOSTAT_STATE_NAME | Thermostatstatus (MENSCH) (nur für Plugin-Thermostat) | Die Info | string
| THERMOSTAT_HUMIDITY | Raumfeuchtigkeitsthermostat (wird nicht von der mobilen Anwendung verwaltet) | Die Info | numeric
| HUMIDITY_SETPOINT | Feuchtigkeit einstellen (Nicht von der mobilen Anwendung verwaltet) | Die Info | slider
| THERMOSTAT_SET_SETPOINT | Sollwertthermostat | Handlung | slider
| THERMOSTAT_SET_MODE | Thermostat-Modus (nur für Plugin-Thermostat)) | Handlung | other
| THERMOSTAT_SET_LOCK | Sperrthermostat (nur für Plugin-Thermostat)) | Handlung | other
| THERMOSTAT_SET_UNLOCK | Thermostat entsperren (nur für Plugin-Thermostat)) | Handlung | other
| HUMIDITY_SET_SETPOINT | Feuchtigkeit einstellen (Nicht von der mobilen Anwendung verwaltet) | Handlung | slider

| **Lüfter (id: Fan)** | | | |
|:--------|:----------------|:--------:|:---------:|
| FAN_SPEED_STATE | Lüftergeschwindigkeitsstatus (Nicht von der mobilen Anwendung verwaltet) | Die Info | numeric
| ROTATION_STATE | Statusrotation (nicht von der mobilen Anwendung verwaltet) | Die Info | numeric
| LÜFTERGESCHWINDIGKEIT | Lüftergeschwindigkeit (wird nicht von der mobilen Anwendung verwaltet) | Handlung | slider
| DREHUNG | Rotation (Nicht von Application Mobile verwaltet) | Handlung | slider

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