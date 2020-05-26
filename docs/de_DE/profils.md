# Vorlieben
**Einstellungen → Einstellungen**

Auf der Seite &quot;Einstellungen&quot; können Sie bestimmte benutzerspezifische Jeedom-Verhaltensweisen konfigurieren.

## Registerkarte &quot;Einstellungen&quot;

### Interface

Definiert bestimmte Schnittstellenverhalten

- **Objektbedienfeld im Dashboard** : Zeigt das Objektfenster (links) im Dashboard an, ohne auf die entsprechende Schaltfläche klicken zu müssen.
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

  **Wichtig** Wenn Sie während der Konfiguration der doppelten Authentifizierung einen Fehler haben, müssen Sie überprüfen, ob Jeedom (siehe auf der Gesundheitsseite) und Ihr Telefon gleichzeitig vorhanden sind (1 Minute Unterschied reicht aus, damit es nicht funktioniert).

- **Passwort** : Ermöglicht das Ändern Ihres Passworts (vergessen Sie nicht, es unten erneut einzugeben).

- **Benutzer-Hash** : Ihr Benutzer-API-Schlüssel.

-   **Menüs anzeigen** : Sagen Sie Jeedom, dass er das Panel anzeigen soll
    links, wenn es existiert, als Erinnerung ist dieses Panel
    verfügbar auf den meisten Plugin-Seiten sowie
    Seite mit Szenarien, Interaktionen, Objekten….

-   **Standardseite** : Standardseite, die angezeigt wird, wenn
    Desktop / Mobile-Verbindung

-   **Standardobjekt auf dem Armaturenbrett** : Standardanzeigeobjekt
    bei der Ankunft auf dem Armaturenbrett / Handy

-   **Standardansicht** : Ansicht, die standardmäßig angezeigt wird, wenn Sie am ankommen
    das Dashboard / Handy

-   **Standarddesign** : Design, das standardmäßig angezeigt wird, wenn
    die Ankunft auf dem Armaturenbrett / Handy

    -   **Vollbild** : Standardanzeige im Vollbildmodus, wenn
        die Ankunft auf den Entwürfen

-   **Armaturenbrett**

    -   **Klappen Sie das Szenario-Bedienfeld auf** : ermöglicht sichtbar zu machen
        Standardmäßig das Szenario-Menü (rechts) im Dashboard

    -   **Das Objekt-Panel anzeigen** : ermöglicht sichtbar zu machen durch
        Standardmäßig das Objektmenü (links) im Dashboard

-   **Ansicht**

    -   **Klappen Sie das Ansichtsfenster auf** : ermöglicht sichtbar zu machen durch
        Standardansichtsmenü (links) für Ansichten

Sicherheit
--------

-   **2-stufige Authentifizierung** : ermöglicht zu konfigurieren
    2-Schritt-Authentifizierung (zur Erinnerung, dies ist ein sich ändernder Code
    wird alle X Sekunden in einer mobilen Anwendung angezeigt, geben Sie ein
    Google Authentificator oder Microsoft Authenticator). Beachten Sie, dass eine doppelte Authentifizierung nur für externe Verbindungen angefordert wird. Für eine lokale Verbindung wird der Code daher nicht angefordert. Wichtig, wenn Sie während der Konfiguration der doppelten Authentifizierung eine Fehlerprüfung haben, dass jeedom (siehe auf der Gesundheitsseite) und Ihr Telefon gleichzeitig vorhanden sind (1 Minute Unterschied reicht aus, damit es nicht funktioniert)

-   **Passwort** : ermöglicht es Ihnen, Ihr Passwort zu ändern (nicht
    Vergessen Sie, es unten erneut einzugeben)

-   **Benutzer-Hash** : Ihr Benutzer-API-Schlüssel


### Aktive Sitzungen

Hier haben Sie die Liste Ihrer aktuell verbundenen Sitzungen, ihre ID, ihre IP sowie das Datum der letzten Kommunikation. Durch Klicken auf &quot;Trennen&quot; wird der Benutzer getrennt. Achtung, wenn es sich auf einem registrierten Gerät befindet, wird dadurch auch die Aufzeichnung gelöscht.

### Registriertes Gerät

Hier finden Sie die Liste aller registrierten Geräte (die ohne Authentifizierung eine Verbindung herstellen) zu Ihrem Jeedom sowie das Datum der letzten Verwendung.
Hier können Sie die Registrierung eines Geräts löschen. Achtung, es trennt es nicht, sondern verhindert nur die automatische Wiederverbindung.
