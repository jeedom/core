# Utilisateurs
**Einstellungen → System → Benutzer**

Auf dieser Seite können Sie die Liste der Benutzer definieren, die berechtigt sind, eine Verbindung zu Jeedom herzustellen, sowie deren Administratorrechte.

Auf der Seite haben Sie drei Schaltflächen :

- Benutzer hinzufügen.
- Speichern.
- Öffnen Sie den Support-Zugriff.

## Liste der Benutzer

- **Benutzername** : Benutzer-ID.
- **Aktiva** : Ermöglicht das Deaktivieren des Kontos, ohne es zu löschen.
- **Lokal** : Ermöglicht die Verbindung des Benutzers nur, wenn er sich im lokalen Netzwerk von Jeedom befindet.
- **Profil** : Ermöglicht die Auswahl des Benutzerprofils :
    - **Verwalter** : Der Benutzer erhält alle Rechte (Bearbeitung / Beratung) an Jeedom.
    - **Benutzer** : Der Benutzer kann Dashboard, Ansichten, Designs usw. anzeigen. und auf Geräte / Kontrollen einwirken. Er hat jedoch weder Zugriff auf die Konfiguration der Steuerungen / Geräte noch auf die Konfiguration von Jeedom.
    - **Begrenzter Benutzer** : Der Benutzer sieht nur das autorisierte Gerät (konfigurierbar mit der Schaltfläche "Rechte"").
- **API-Schlüssel** : Persönlicher API-Schlüssel des Benutzers.
- **Doppelte Authentifizierung** : Gibt an, ob die doppelte Authentifizierung aktiv ist (OK) oder nicht (NOK)).
- **Datum der letzten Verbindung** : Datum der letzten Benutzeranmeldung. Bitte beachten Sie, dass dies das tatsächliche Verbindungsdatum ist. Wenn Sie also Ihren Computer speichern, wird das Verbindungsdatum nicht bei jeder Rückkehr aktualisiert.
- **Rechte** : Benutzerrechte ändern.
- **Passwort** : Ermöglicht das Ändern des Benutzerpassworts.
- **Entfernen** : Benutzer löschen.
- **API-Schlüssel neu generieren** : Regeneriert den API-Schlüssel des Benutzers.
- **Rechte verwalten** : Ermöglicht die Feinverwaltung von Benutzerrechten (beachten Sie, dass sich das Profil in "eingeschränkter Benutzer" befinden muss)").

## Rechteverwaltung

Wenn Sie auf &quot;Rechte&quot; klicken, wird ein Fenster angezeigt, in dem Sie die Benutzerrechte genau verwalten können. Auf der ersten Registerkarte werden die verschiedenen Geräte angezeigt. Der zweite zeigt die Szenarien.

> **Wichtig**
>
> Das Profil muss eingeschränkt sein, da sonst keine hier aufgeführten Einschränkungen berücksichtigt werden.

Sie erhalten eine Tabelle, in der für jedes Gerät und jedes Szenario die Rechte des Benutzers definiert werden können :
- **Keine** : Der Benutzer sieht die Ausrüstung / das Szenario nicht.
- **Visualisierung** : Der Benutzer sieht die Ausrüstung / das Szenario, kann jedoch nicht darauf reagieren.
- **Visualisierung und Ausführung** : Der Benutzer sieht die Ausrüstung / das Szenario und kann darauf reagieren (Anzünden einer Lampe, Starten des Szenarios usw.)).

## Aktive Sitzungen))

Zeigt die auf Ihrem Jeedom aktiven Browsersitzungen mit Benutzerinformationen, deren IP und seit wann an. Sie können den Benutzer über die Schaltfläche abmelden **Trennen**.

## Registrierte Geräte)

Listen Sie die Peripheriegeräte (Computer, Handys usw.) auf, deren Authentifizierung auf Ihrem Jeedom aufgezeichnet wurde.
Sie können sehen, welcher Benutzer, seine IP, wann und die Registrierung für dieses Gerät löschen.

> **Notiz**
>
> Der gleiche Benutzer hat möglicherweise verschiedene Geräte registriert. Zum Beispiel sein Desktop-Computer, Laptop, Handy usw.







