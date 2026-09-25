# Einstellungen
**Einstellungen → Voreinstellungen**

Auf der Seite „Einstellungen“ können Sie bestimmte benutzerspezifische Funktionen von Jeedom konfigurieren.

## Registerkarte „Einstellungen“

### Benutzeroberfläche

Legt bestimmte Verhaltensweisen der Benutzeroberfläche fest

- **Standardseite**: Die Seite, die standardmäßig angezeigt wird, wenn man sich über einen Desktop-Computer oder ein Mobilgerät anmeldet.
- **Standardobjekt**: Objekt, das standardmäßig angezeigt wird, wenn man das Dashboard bzw. die mobile App aufruft.

- **Standardansicht**: Die Ansicht, die standardmäßig angezeigt wird, wenn man das Dashboard bzw. die mobile App aufruft.
- **Ansichtsleiste einblenden**: Damit wird das Ansichtsmenü (links) standardmäßig in den Ansichten angezeigt.

- **Standarddesign**: Das Design, das standardmäßig angezeigt wird, wenn man das Dashboard aufruft / auf dem Handy.
- **Vollbild-Design**: Standardmäßig wird beim Aufrufen der Designs eine Vollbildansicht angezeigt.

- **Standard-3D-Design**: Das 3D-Design, das standardmäßig angezeigt wird, wenn man das Dashboard bzw. die mobile App aufruft.
- **3D-Design im Vollbildmodus**: Standardmäßig wird beim Aufrufen der 3D-Designs der Vollbildmodus angezeigt.

### Benachrichtigungen

- **Benachrichtigungsbefehl**: Standardbefehl, um Sie zu erreichen (Befehl vom Typ „Nachricht“).

## Registerkarte „Sicherheit“

- **Zwei-Faktor-Authentifizierung**: Hiermit lässt sich die Zwei-Faktor-Authentifizierung einrichten (zur Erinnerung: Dabei handelt es sich um einen Code, der sich alle X Sekunden ändert und in einer mobilen App wie *Google Authenticator* angezeigt wird). Bitte beachten Sie, dass die Zwei-Faktor-Authentifizierung nur bei externen Verbindungen erforderlich ist. Bei lokalen Verbindungen wird der Code daher nicht abgefragt.

**Wichtig**: Wenn bei der Einrichtung der Zwei-Faktor-Authentifizierung ein Fehler auftritt, überprüfen Sie bitte, ob Jeedom (siehe Seite „Status“) und Ihr Smartphone auf die gleiche Uhrzeit eingestellt sind (schon eine Abweichung von einer Minute reicht aus, damit es nicht funktioniert).

- **Passwort**: Hier können Sie Ihr Passwort ändern (vergessen Sie nicht, es unten noch einmal einzugeben).

- **Benutzer-Hash**: Ihr Benutzer-API-Schlüssel.

### Aktive Sitzungen

Hier finden Sie eine Liste Ihrer derzeit angemeldeten Sitzungen mit deren ID, IP-Adresse sowie dem Datum der letzten Kommunikation. Wenn Sie auf „Abmelden“ klicken, wird der Benutzer abgemeldet. Achtung: Befindet sich der Benutzer auf einem registrierten Gerät, wird dadurch auch die Registrierung gelöscht.

### Registrierte Geräte

Hier finden Sie eine Liste aller bei Ihrem Jeedom registrierten Geräte (die sich ohne Authentifizierung verbinden) sowie das Datum der letzten Nutzung.
Hier können Sie die Registrierung eines Geräts löschen. Bitte beachten Sie, dass das Gerät dadurch nicht getrennt wird, sondern lediglich die automatische Wiederverbindung verhindert wird.
