# Arten von Geräten
**Extras → Gerätetypen**

Sensoren und Aktoren werden in Jeedom über Plugins verwaltet, die Geräte mit den Befehlen *Info* (Sensor) oder *Action* (Aktor) erstellen. So lassen sich dann je nach Änderung bestimmter Sensoren Aktionen auslösen, beispielsweise das Einschalten eines Lichts bei Bewegungserkennung. Der Jeedom-Core sowie Plugins wie *Mobile*, *Homebridge*, *Google Smarthome*, *Alexa Smarthome* usw. wissen jedoch nicht, um welche Geräte es sich handelt: eine Steckdose, eine Lampe, ein Rollladen usw.

Um dieses Problem zu beheben, insbesondere bei Sprachassistenten (*Schalte das Licht im Wohnzimmer ein*), hat der Core vor einigen Jahren die **generischen Typen** eingeführt, die von diesen Plugins verwendet werden.

Auf diese Weise lässt sich ein Gerät beispielsweise anhand von *„Das Licht im Raum“* identifizieren.

Meistens werden die generischen Typen bei der Konfiguration Ihres Moduls automatisch zugewiesen (z. B. bei der Einbindung in Z-Wave). Es kann jedoch vorkommen, dass Sie sie neu konfigurieren müssen. Die Einstellung dieser generischen Typen kann direkt in bestimmten Plugins oder über einen Befehl unter *Erweiterte Konfiguration* des jeweiligen Plugins vorgenommen werden.

Auf dieser Seite können Sie diese generischen Typen direkter und einfacher konfigurieren; außerdem wird eine automatische Zuordnung vorgeschlagen, sobald die Geräte korrekt zugeordnet wurden.

![Arten von Geräten](../images/coreGenerics.gif)

## Art der Anlage

Auf dieser Seite werden die Geräte nach Gerätetyp sortiert: Steckdose, Beleuchtung, Rollladen, Thermostat, Kamera usw. Zu Beginn werden die meisten Ihrer Geräte unter **Geräte ohne Typ** eingeordnet. Um ihnen einen Typ zuzuweisen, können Sie sie entweder in einen anderen Typ verschieben oder mit einem Rechtsklick auf das Gerät direkt dorthin verschieben. Der Gerätetyp an sich ist nicht wirklich nützlich, da die Art der Befehle wichtiger ist. So kann ein Gerät ohne Typ sein oder einem Typ zugeordnet sein, der nicht unbedingt seinen Befehlen entspricht. Sie können natürlich verschiedene Befehlstypen innerhalb eines Geräts mischen. Derzeit dient dies eher der Übersicht und einer logischen Organisation, die möglicherweise in zukünftigen Versionen eine Rolle spielen wird.

> **Tipp**
>
> - Wenn Sie ein Gerät in den Bereich **Geräte ohne Typ** verschieben, schlägt Jeedom Ihnen vor, die generischen Typen für dessen Befehle zu löschen.
> - Sie können mehrere Geräte gleichzeitig verschieben, indem Sie die Kontrollkästchen links neben den jeweiligen Geräten aktivieren.

## Steuerungsart

Sobald ein Gerät dem richtigen *Typ* zugeordnet wurde, gelangen Sie durch Anklicken zu einer Liste seiner Befehle, die je nachdem, ob es sich um eine *Info* (blau) oder eine *Aktion* (orange) handelt, unterschiedlich eingefärbt sind.

Wenn Sie mit der rechten Maustaste auf einen Befehl klicken, können Sie ihm einen generischen Typ zuweisen, der den Spezifikationen dieses Befehls entspricht (Typ „Info/Aktion“, Untertyp „Numerisch“, „Binär“ usw.).

> **Tipp**
>
> - Im Kontextmenü der Befehle wird der Gerätetyp fett dargestellt, es ist jedoch trotzdem möglich, jedem Gerätetyp einen beliebigen generischen Typ zuzuweisen.

An jedem Gerät befinden sich zwei Tasten:

- **Automatische Typenauswahl**: Diese Funktion öffnet ein Fenster, in dem Ihnen anhand des Gerätetyps, der Besonderheiten des Befehls und seines Namens geeignete generische Typen vorgeschlagen werden. Sie können die Vorschläge dann anpassen und die Zuordnung bestimmter Befehle zu einer Anwendung deaktivieren, bevor Sie die Auswahl bestätigen oder ablehnen. Diese Funktion ist mit der Auswahl über Kontrollkästchen kompatibel.

- **Typen zurücksetzen**: Diese Funktion löscht die generischen Typen aus allen Befehlen der Geräte.

> **Achtung**
>
> Änderungen werden erst nach dem Speichern über die Schaltfläche oben rechts auf der Seite übernommen.

## Allgemeine Typen und Szenarien

In Version 4.2 hat der Core generische Typen in die Szenarien integriert. So können Sie ein Szenario auslösen, wenn in einem Raum ein Licht angeht, wenn im Haus eine Bewegung erkannt wird, alle Lichter ausschalten oder alle Rollläden mit einer einzigen Aktion schließen usw. Wenn Sie zudem ein Gerät hinzufügen, müssen Sie lediglich die richtigen Typen in dessen Befehlen angeben; es ist nicht notwendig, solche Szenarien anzupassen.

#### Auslöser

Sie können ein Szenario über Sensoren auslösen. Wenn Sie beispielsweise Bewegungsmelder im Haus haben, können Sie ein Alarmszenario erstellen, bei dem jeder Bewegungsmelder als Auslöser dient: `#[Salon][Move Salon][Presence]# == 1`, `#[Cuisine][Move Cuisine][Presence]# == 1`usw. In einem solchen Szenario benötigen Sie also alle Ihre Bewegungsmelder, und wenn Sie einen weiteren hinzufügen, müssen Sie diesen zu den Auslösern hinzufügen. Das ist logisch.

Dank generischer Typen können Sie einen einzigen Auslöser verwenden: `#genericType(PRESENCE)# == 1`. Hier ist kein Objekt angegeben, daher löst jede noch so kleine Bewegung im gesamten Haus das Szenario aus. Wenn Sie einen neuen Sensor im Haus hinzufügen, müssen Sie die Szenarien nicht anpassen.

Hier ein Auslöser zum Einschalten einer Lampe im Wohnzimmer: `#genericType(LIGHT_STATE,#[Salon]#)# > 0`

#### Ausdruck

Wenn Sie in einem Szenario wissen möchten, ob im Wohnzimmer das Licht an ist, können Sie Folgendes tun:

WENN `#[Salon][Lumiere Canapé][Etat]# == 1 OU #[Salon][Lumiere Salon][Etat]# == 1 OU #[Salon][Lumiere Angle][Etat]# == 1`

Oder einfacher gesagt: IF `genericType(LIGHT_STATE,#[Salon]#) > 0` d. h. wenn eine oder mehrere Lampen im Wohnzimmer eingeschaltet sind.

Wenn Sie morgen eine weitere Lampe in Ihrem Wohnzimmer hinzufügen, müssen Sie Ihre Szenarien nicht anpassen!


#### Aktion

Wenn Sie alle Lampen im Wohnzimmer einschalten möchten, können Sie für jede Lampe eine Aktion erstellen:

```
#[Salon][Lumiere Canapé][On]#
#[Salon][Lumiere Salon][On]#
#[Salon][Lumiere Angle][On]#
```

Oder einfacher gesagt: eine Aktion erstellen `genericType` mit `LIGHT_ON` in `Salon`. Wenn Sie morgen eine Lampe in Ihrem Wohnzimmer hinzufügen, müssen Sie Ihre Szenarien nicht anpassen!


## Liste der generischen Typen des Core

> **Tipp**
>
> - Sie finden diese Liste direkt in Jeedom auf dieser Seite über die Schaltfläche **Liste** oben rechts.

| **Sonstiges (id: Other)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TIMER | Timer-Status | Info | numerisch
| TIMER_STATE | Timer-Status (pausiert oder nicht) | Info | binär, numerisch
| SET_TIMER | Timer | Aktion | Schieberegler
| TIMER_PAUSE | Pausen-Timer | Aktion | Sonstiges
| TIMER_RESUME | Timer fortsetzen | Aktion | Sonstiges

| **Batterie (id: Battery)** | | | |
|:--------|:----------------|:--------:|:---------:|
| BATTERY | Batterie | Info | numerisch
| BATTERY_CHARGING | Batterie wird geladen | Info | binary

| **Kamera (ID: Camera)** | | | |
|:--------|:----------------|:--------:|:---------:|
| CAMERA_URL | Kamera-URL | Info | string
| CAMERA_RECORD_STATE | Aufnahmestatus der Kamera | Info | binary
| CAMERA_UP | Kamera nach oben bewegen | Aktion | Sonstiges
| CAMERA_DOWN | Kamera nach unten bewegen | Aktion | Sonstiges
| CAMERA_LEFT | Kamerabewegung nach links | Aktion | Sonstiges
| CAMERA_RIGHT | Kamerabewegung nach rechts | Aktion | Sonstiges
| CAMERA_ZOOM | Kamera nach vorne zoomen | Aktion | Sonstiges
| CAMERA_DEZOOM | Kamera zurückzoomen | Aktion | Sonstiges
| CAMERA_STOP | Kamera stoppen | Aktion | Sonstiges
| CAMERA_PRESET | Kamera-Voreinstellung | Aktion | Sonstiges
| CAMERA_RECORD | Kameraaufzeichnung | Aktion |
| CAMERA_TAKE | Kamera-Schnappschuss | Aktion |

| **Heizung (id: Heating)** | | | |
|:--------|:----------------|:--------:|:---------:|
| HEATING_STATE | Status der Heizung (Pilotdraht) | Info | binary
| HEATING_ON | Heizung (Pilotleitung) – Taste „ON“ | Aktion | Sonstiges
| HEATING_OFF | Heizung (Pilotleitung) – Schalter „OFF“ | Aktion | Sonstiges
| HEATING_OTHER | Pilotdraht-Heizung – Schalter | Aktion | Sonstiges

| **Elektrizität (id: Electricity)** | | | |
|:--------|:----------------|:--------:|:---------:|
| POWER | Elektrische Leistung | Info | numeric
| VERBRAUCH | Stromverbrauch | Info | numeric
| SPANNUNG | Spannung | Info | numerisch
| REBOOT | Neustart | Aktion | Sonstiges

| **Umwelt (id: Environment)** | | | |
|:--------|:----------------|:--------:|:---------:|
| TEMPERATUR | Temperatur | Info | numerisch
| AIR_QUALITY | Luftqualität | Info | numeric
| BRIGHTNESS | Helligkeit | Info | numerisch
| PRESENCE | Präsenz | Info | binary
| SMOKE | Rauchmelder | Info | binary
| LUFTFEUCHTIGKEIT | Luftfeuchtigkeit | Info | numerisch
| UV | UV | Info | numerisch
| CO₂ | CO₂ (ppm) | Info | numerisch
| CO | CO (ppm) | Info | numerisch
| GERÄUSCH | Geräuschpegel (dB) | Info | numerisch
| DRUCK | Druck | Info | numerisch
| WATER_LEAK | Wasserleck | Info |
| FILTER_CLEAN_STATE | Filterstatus | Info | binary

| **Generic (id: Generic)** | | | |
|:--------|:----------------|:--------:|:---------:|
| DEPTH | Tiefe | Info | numerisch
| ENTFERNUNG | Entfernung | Info | numerisch
| BUTTON | Schaltfläche | Info | binär, numerisch
| GENERIC_INFO |  Allgemein | Info |
| GENERIC_ACTION |  Allgemein | Aktion | Sonstiges

| **Beleuchtung (id: Light)** | | | |
|:--------|:----------------|:--------:|:---------:|
| LIGHT_STATE | Lichtstatus | Info | binär, numerisch
| LIGHT_BRIGHTNESS | Lichthelligkeit | Info | numerisch
| LIGHT_COLOR | Lichtfarbe | Info | string
| LIGHT_STATE_BOOL | Lichtstatus (binär) | Info | binary
| LIGHT_COLOR_TEMP | Lichtfarbtemperatur | Info | numerisch
| LIGHT_TOGGLE | Licht-Schalter | Aktion | Sonstiges
| LIGHT_ON | Licht-Ein-Taste | Aktion | Sonstiges
| LIGHT_OFF | Licht-Aus-Taste | Aktion | Sonstiges
| LIGHT_SLIDER | Licht-Schieberegler | Aktion | Schieberegler
| LIGHT_SET_COLOR | Lichtfarbe | Aktion | Farbe
| LIGHT_MODE | Lichtmodus | Aktion | Sonstiges
| LIGHT_SET_COLOR_TEMP | Lichtfarbtemperatur | Aktion |

| **Modus (id: Modus)** | | | |
|:--------|:----------------|:--------:|:---------:|
| MODE_STATE | Modusstatus | Info | string
| MODE_SET_STATE | Modus ändern | Aktion | Sonstiges

| **Multimedia (id: Multimedia)** | | | |
|:--------|:----------------|:--------:|:---------:|
| LAUTSTÄRKE | Lautstärke | Info | numerisch
| MEDIA_STATUS | Status | Info | Zeichenfolge
| MEDIA_ALBUM | Album | Info | string
| MEDIA_ARTIST | Künstler | Info | string
| MEDIA_TITLE | Titel | Info | string
| MEDIA_POWER | Strom | Info | string
| CHANNEL | Kanal | Info | Zahl, Zeichenfolge
| MEDIA_STATE | Status | Info | binary
| SET_VOLUME | Lautstärke | Aktion | Schieberegler
| SET_CHANNEL | Kanal | Aktion | Sonstiges, Schieberegler
| MEDIA_PAUSE | Pause | Aktion | Sonstiges
| MEDIA_RESUME | Wiedergabe | Aktion | Sonstiges
| MEDIA_STOP | Stopp | Aktion | Sonstiges
| MEDIA_NEXT | Weiter | Aktion | Sonstiges
| MEDIA_PREVIOUS | Zurück | Aktion | Sonstiges
| MEDIA_ON | Ein | Aktion | Sonstiges
| MEDIA_OFF | Aus | Aktion | Sonstiges
| MEDIA_MUTE | Stumm | Aktion | Sonstiges
| MEDIA_UNMUTE | Stummschaltung aufheben | Aktion | Sonstiges

| **Wetter (id: Weather)** | | | |
|:--------|:----------------|:--------:|:---------:|
| WEATHER_TEMPERATURE | Wetter Temperatur | Info | numeric
| WEATHER_TEMPERATURE_MAX_2 | Wetterbedingungen Tag +1, max. Tag +2 | Info | numerisch
| WIND_SPEED | Wind (Geschwindigkeit) | Info | numerisch
| RAIN_TOTAL | Niederschlag (Gesamtmenge) | Info | numerisch
| RAIN_CURRENT | Niederschlag (mm/h) | Info | numerisch
| WEATHER_CONDITION_ID_4 | Wetterbedingung (ID) Tag +4 | Info | numerisch
| WEATHER_CONDITION_4 | Wetterbedingungen am Tag +4 | Info | string
| WEATHER_TEMPERATURE_MAX_4 | Wetter – Höchsttemperatur Tag 4 | Info | numerisch
| WEATHER_TEMPERATURE_MIN_4 | Wetter – Tiefsttemperatur am Tag +4 | Info | numerisch
| WEATHER_CONDITION_ID_3 | Wetterbedingung (ID) Tag 3 | Info | numerisch
| WEATHER_CONDITION_3 | Wetterbedingungen am Tag +3 | Info | string
| WEATHER_TEMPERATURE_MAX_3 | Wetter – Höchsttemperatur am Tag +3 | Info | numerisch
| WEATHER_TEMPERATURE_MIN_3 | Wetter – Tiefsttemperatur am Tag +3 | Info | numerisch
| WEATHER_CONDITION_ID_2 | Wetterbedingung (ID) Tag+2 | Info | numerisch
| WEATHER_CONDITION_2 | Wetterbedingungen am Tag +2 | Info | string
| WEATHER_TEMPERATURE_MIN_2 | Wetter – Tiefsttemperatur am Tag +2 | Info | numerisch
| WEATHER_HUMIDITY | Wetter – Luftfeuchtigkeit | Info | numerisch
| WEATHER_CONDITION_ID_1 | Wetterbedingung (ID) Tag+1 | Info | numerisch
| WEATHER_CONDITION_1 | Wetterbedingungen am Tag j+1 | Info | string
| WEATHER_TEMPERATURE_MAX_1 | Wetter – Höchsttemperatur am Tag +1 | Info | numerisch
| WEATHER_TEMPERATURE_MIN_1 | Wetter – Tiefsttemperatur am Tag +1 | Info | numerisch
| WEATHER_CONDITION_ID | Wetterbedingung (ID) | Info | numerisch
| WEATHER_CONDITION | Wetterbedingungen | Info | string
| WEATHER_TEMPERATURE_MAX | Wetter – Höchsttemperatur | Info | numerisch
| WEATHER_TEMPERATURE_MIN | Wetter – Tiefsttemperatur | Info | numerisch
| WEATHER_SUNRISE | Wetter bei Sonnenuntergang | Info | numerisch
| WEATHER_SUNSET | Wetter Sonnenaufgang | Info | numeric
| WEATHER_WIND_DIRECTION | Wetter: Windrichtung | Info | numerisch
| WEATHER_WIND_SPEED | Wetter – Windgeschwindigkeit | Info | numerisch
| WEATHER_PRESSURE | Wetter – Luftdruck | Info | numerisch
| WIND_DIRECTION | Wind (Richtung) | Info | numerisch

| **Öffnung (id: Opening)** | | | |
|:--------|:----------------|:--------:|:---------:|
| LOCK_STATE | Schlossstatus | Info | binary
| BARRIER_STATE | Tor (öffnend) Status | Info | binary
| GARAGE_STATE | Garagentor (öffnend) Status | Info | binary
| OPENING | Tür | Info | binary
| OPENING_WINDOW | Fenster | Info | binary
| LOCK_OPEN | Öffnen-Knopf | Aktion | Sonstiges
| LOCK_CLOSE | Schließen-Taste | Aktion | Sonstiges
| GB_OPEN | Tür oder Garagentor – Öffnungstaste | Aktion | Sonstiges
| GB_CLOSE | Tor oder Garagentor – Schließen-Taste | Aktion | Sonstiges
| GB_TOGGLE | Tor oder Garage – Umschaltknopf | Aktion | Sonstiges

| **Steckdose (id: Outlet)** | | | |
|:--------|:----------------|:--------:|:---------:|
| ENERGY_STATE | Steckdosenstatus | Info | numerisch, binär
| ENERGY_ON | Steckdose mit Ein-/Aus-Taste | Aktion | Sonstiges
| ENERGY_OFF | Steckdose „Aus“-Taste | Aktion | Sonstiges
| ENERGY_SLIDER | Slider-Steckdose | Aktion |

| **Roboter (id: Roboter)** | | | |
|:--------|:----------------|:--------:|:---------:|
| DOCK_STATE | Basisstatus | Info | binary
| DOCK | Zurück zur Basisstation | Aktion | Sonstiges

| **Sicherheit (id: Security)** | | | |
|:--------|:----------------|:--------:|:---------:|
| SIREN_STATE | Sirenenstatus | Info | binary
| ALARM_STATE | Alarmstatus | Info | Binär, Zeichenfolge
| ALARM_MODE | Alarmmodus | Info | string
| ALARM_ENABLE_STATE | Alarmstatus aktiviert | Info | binär
| FLOOD | Überschwemmung | Info | binary
| SABOTAGE | Sabotage | Info | binary
| SHOCK | Schock | Info | binär, numerisch
| SIREN_OFF | Sirene-Aus-Taste | Aktion | Sonstiges
| SIREN_ON | Sirene-Taste „Ein“ | Aktion | Sonstiges
| ALARM_ARMED | Alarm aktiviert | Aktion | Sonstiges
| ALARM_RELEASED | Alarm freigegeben | Aktion | Sonstiges
| ALARM_SET_MODE | Alarmmodus | Aktion | Sonstiges

| **Thermostat (id: Thermostat)** | | | |
|:--------|:----------------|:--------:|:---------:|
| THERMOSTAT_STATE | Thermostat-Status (BINÄR) (nur für das Thermostat-Plugin) | Info |
| THERMOSTAT_TEMPERATURE | Thermostat Raumtemperatur | Info | numerisch
| THERMOSTAT_SETPOINT | Sollwert des Thermostats | Info | numerisch
| THERMOSTAT_MODE | Thermostat-Modus (nur für das Thermostat-Plugin) | Info | string
| THERMOSTAT_LOCK | Thermostat-Sperre (nur für das Thermostat-Plugin) | Info | binary
| THERMOSTAT_TEMPERATURE_OUTDOOR | Thermostat Außentemperatur (nur für das Thermostat-Plugin) | Info | numerisch
| THERMOSTAT_STATE_NAME | Thermostat-Zustand (MENSCH) (nur für das Thermostat-Plugin) | Info | string
| THERMOSTAT_HUMIDITY | Raumfeuchtethermostat | Info | numerisch
| HUMIDITY_SETPOINT | Sollwert für die Luftfeuchtigkeit | Info | Schieberegler
| THERMOSTAT_SET_SETPOINT | Thermostat-Sollwert | Aktion | Schieberegler
| THERMOSTAT_SET_MODE | Thermostat-Modus (nur für das Thermostat-Plugin) | Aktion | Sonstiges
| THERMOSTAT_SET_LOCK | Thermostat-Sperre (nur für das Thermostat-Plugin) | Aktion | Sonstiges
| THERMOSTAT_SET_UNLOCK | Thermostat entsperren (nur für das Thermostat-Plugin) | Aktion | Sonstiges
| HUMIDITY_SET_SETPOINT | Sollwert Luftfeuchtigkeit | Aktion | Schieberegler

| **Ventilator (id: Fan)** | | | |
|:--------|:----------------|:--------:|:---------:|
| FAN_SPEED_STATE | Lüftergeschwindigkeit – Status | Info | numerisch
| ROTATION_STATE | Rotationsstatus | Info | numerisch
| FAN_SPEED | Lüftergeschwindigkeit | Aktion | Schieberegler
| DREHUNG | Drehung | Aktion | Schieberegler

| **Fensterladen (id: Shutter)** | | | |
|:--------|:----------------|:--------:|:---------:|
| FLAP_STATE | Rollladenstatus | Info | binär, numerisch
| FLAP_BSO_STATE | BSO-Ladenstatus | Info | binär, numerisch
| FLAP_UP | Rollladen-Schaltfläche „Hochfahren“ | Aktion | Sonstiges
| FLAP_DOWN | Rollladen-Schaltfläche „Herunterfahren“ | Aktion | Sonstiges
| FLAP_STOP | Rollladen-Stopp-Taste | Aktion |
| FLAP_SLIDER | Schieberegler-Schaltfläche | Aktion | Schieberegler
| FLAP_BSO_UP | BSO-Rollladen, Schaltfläche „Hochfahren“ | Aktion | Sonstiges
| FLAP_BSO_DOWN | BSO-Rollladen, Schaltfläche „Herunterfahren“ | Aktion | Sonstiges
