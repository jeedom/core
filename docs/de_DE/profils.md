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

- **Zwei-Faktor-Authentifizierung**: Ermöglicht die Einrichtung der Zwei-Faktor-Authentifizierung. Ein temporärer Bestätigungscode wird von einer Authentifizierungs-App auf Ihrem Mobilgerät generiert. Die Zwei-Faktor-Authentifizierung wird nur bei externen Verbindungen verlangt; für lokale Verbindungen ist sie nicht erforderlich.

**Wichtig:** Sollte bei der Konfiguration ein Fehler auftreten, überprüfen Sie bitte, ob die Uhrzeit von Jeedom und die Ihres Smartphones synchronisiert sind. Eine Abweichung von nur einer Minute kann dazu führen, dass der Code nicht bestätigt wird.

- **Passwort**: Hier können Sie Ihr Passwort ändern. Geben Sie es bitte auch in das Bestätigungsfeld ein.

- **Benutzer-Hash**: Ihr Benutzer-API-Schlüssel.

### Aktive Sitzungen

Hier finden Sie eine Liste Ihrer derzeit angemeldeten Sitzungen mit deren ID, IP-Adresse sowie dem Datum der letzten Kommunikation. Wenn Sie auf „Abmelden“ klicken, wird der Benutzer abgemeldet. Achtung: Befindet sich der Benutzer auf einem registrierten Gerät, wird dadurch auch die Registrierung gelöscht.

### Registrierte Geräte

Hier finden Sie eine Liste aller bei Ihrem Jeedom registrierten Geräte (die sich ohne Authentifizierung verbinden) sowie das Datum der letzten Nutzung.
Hier können Sie die Registrierung eines Geräts löschen. Bitte beachten Sie, dass das Gerät dadurch nicht getrennt wird, sondern lediglich die automatische Wiederverbindung verhindert wird.
