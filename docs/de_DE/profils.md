# Vorlieben
**Einstellungen → Einstellungen**

Auf der Seite &quot;Einstellungen&quot; können Sie bestimmte benutzerspezifische Jeedom-Verhaltensweisen konfigurieren.

## Registerkarte &quot;Einstellungen&quot;

### Interface

Definiert bestimmte Schnittstellenverhalten

- **Standardseite** : Seite, die standardmäßig angezeigt wird, wenn eine Verbindung zum Desktop oder Handy hergestellt wird.
- **Standardobjekt** : Objekt, das standardmäßig bei der Ankunft im Dashboard / Handy angezeigt wird.

- **Standardansicht** : Ansicht, die standardmäßig bei der Ankunft im Dashboard / Handy angezeigt wird.
- **Klappen Sie das Ansichtsfenster auf** : Wird verwendet, um das Ansichtsmenü (links) standardmäßig in den Ansichten sichtbar zu machen.

- **Standarddesign** : Design, das standardmäßig bei der Ankunft im Dashboard / Handy angezeigt wird.
- **Vollbild-Design** : Standardanzeige im Vollbildmodus bei Ankunft bei Designs.

- **Standard-3D-Design** : 3D-Design wird standardmäßig angezeigt, wenn Sie auf dem Dashboard / Handy ankommen.
- **Vollbild-3D-Design** : Standardanzeige im Vollbildmodus bei Ankunft in 3D-Designs.

### Notifications

- **Benutzerbenachrichtigungsbefehl** : Standardbefehl, um sich Ihnen anzuschließen (Nachrichtentyp Befehl).

## Registerkarte &quot;Sicherheit&quot;

- **2-stufige Authentifizierung** : Ermöglicht die Konfiguration der Authentifizierung in zwei Schritten (zur Erinnerung: Es handelt sich um einen Code, der sich alle X Sekunden ändert und in einer mobilen Anwendung angezeigt wird *Google Authentifikator*). Beachten Sie, dass eine doppelte Authentifizierung nur für externe Verbindungen angefordert wird. Bei lokalen Verbindungen wird der Code daher nicht angefordert.

  **Wichtig** Wenn Sie während der Konfiguration der doppelten Authentifizierung einen Fehler haben, müssen Sie überprüfen, ob Jeedom (siehe auf der Gesundheitsseite) und Ihr Telefon gleichzeitig in Ordnung sind (1 Minute Unterschied reicht aus, damit es nicht funktioniert).

- **Passwort** : Ermöglicht das Ändern Ihres Passworts (vergessen Sie nicht, es unten erneut einzugeben).

- **Benutzer-Hash** : Ihr Benutzer-API-Schlüssel.

### Aktive Sitzungen

Hier haben Sie die Liste Ihrer aktuell verbundenen Sitzungen, ihre ID, ihre IP sowie das Datum der letzten Kommunikation. Durch Klicken auf &quot;Trennen&quot; wird der Benutzer getrennt. Seien Sie vorsichtig, wenn es sich auf einem registrierten Gerät befindet. Dadurch wird auch die Registrierung gelöscht.

### Registrierte Geräte

Hier finden Sie die Liste aller registrierten Geräte (die ohne Authentifizierung eine Verbindung herstellen) zu Ihrem Jeedom sowie das Datum der letzten Verwendung.
Hier können Sie die Registrierung eines Geräts löschen. Achtung, es trennt es nicht, sondern verhindert nur die automatische Wiederverbindung.
