# Benutzer
**Einstellungen → System → Benutzer**

Auf dieser Seite können Sie die Liste der Benutzer festlegen, die sich bei Jeedom anmelden dürfen, sowie deren Administratorrechte.

Auf der Seite finden Sie drei Schaltflächen:

- Benutzer hinzufügen.
- Speichern.
- Supportanfrage erstellen.

## Benutzerliste

- **Benutzername**: Benutzer-ID.
- **Aktiv**: Ermöglicht es, das Konto zu deaktivieren, ohne es zu löschen.
- **Lokal**: Erlaubt die Anmeldung des Benutzers nur, wenn er sich im lokalen Netzwerk von Jeedom befindet.
- **Profil**: Hier können Sie das Benutzerprofil auswählen:
    - **Administrator**: Der Benutzer erhält alle Rechte (Bearbeiten/Anzeigen) in Jeedom.
    - **Benutzer**: Der Benutzer kann das Dashboard, die Ansichten, die Designs usw. einsehen und Geräte/Befehle steuern. Er hat jedoch keinen Zugriff auf die Konfiguration der Befehle/Geräte oder auf die Konfiguration von Jeedom.
    - **Eingeschränkter Benutzer**: Der Benutzer sieht nur die zugelassenen Geräte (konfigurierbar über die Schaltfläche „Rechte“).
- **API-Schlüssel**: Persönlicher API-Schlüssel des Nutzers.
- **Zwei-Faktor-Authentifizierung**: Gibt an, ob die Zwei-Faktor-Authentifizierung aktiviert ist (OK) oder nicht (NOK).
- **Datum der letzten Anmeldung**: Datum der letzten Anmeldung des Benutzers. Bitte beachten Sie, dass es sich hierbei um das tatsächliche Anmeldedatum handelt. Wenn Sie also Ihren Computer registrieren, wird das Anmeldedatum nicht bei jeder erneuten Anmeldung aktualisiert.
- **Berechtigungen**: Hier können Sie die Berechtigungen des Benutzers ändern.
- **Passwort**: Ermöglicht es, das Passwort des Benutzers zu ändern.
- **Löschen**: Ermöglicht das Löschen des Benutzers.
- **API-Schlüssel neu generieren**: Generiert den API-Schlüssel des Benutzers neu.
- **Berechtigungen verwalten**: Ermöglicht die detaillierte Verwaltung der Benutzerberechtigungen (Achtung: Das Profil muss auf „eingeschränkter Benutzer“ eingestellt sein).

## Rechteverwaltung

Wenn Sie auf „Rechte“ klicken, erscheint ein Fenster, in dem Sie die Benutzerrechte detailliert verwalten können. Auf der ersten Registerkarte werden die verschiedenen Geräte angezeigt. Auf der zweiten Registerkarte werden die Szenarien angezeigt.

> **Wichtig**
>
> Das Profil muss eingeschränkt sein, andernfalls werden die hier festgelegten Einschränkungen nicht berücksichtigt.

Sie erhalten eine Tabelle, in der Sie für jedes Gerät und jedes Szenario die Benutzerrechte festlegen können:
- **Keine**: Der Benutzer sieht das Gerät/Szenario nicht.
- **Visualisierung**: Der Benutzer sieht das Gerät/Szenario, kann jedoch keine Aktionen daran ausführen.
- **Anzeige und Ausführung**: Der Benutzer sieht die Geräte/Szenarien und kann diese steuern (eine Lampe einschalten, ein Szenario starten usw.).

## Aktive Sitzung(en)

Zeigt die aktiven Browsersitzungen auf Ihrem Jeedom an, einschließlich der Benutzerdaten, der IP-Adresse und der Dauer der Sitzung. Sie können den Benutzer über die Schaltfläche **Abmelden** abmelden.

## Registrierte Geräte

Listet die Geräte (Computer, Mobilgeräte usw.) auf, die sich bei Ihrem Jeedom angemeldet haben.
Sie können sehen, welcher Benutzer mit welcher IP-Adresse zu welchem Datum zugreift, und den Eintrag für dieses Gerät löschen.

> **Hinweis**
>
> Ein und derselbe Benutzer kann verschiedene Geräte registriert haben. Zum Beispiel seinen Desktop-Computer, seinen Laptop, sein Handy usw.







