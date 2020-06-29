Hier können wir die Liste der Benutzer definieren
darf sich mit Jeedom verbinden, aber auch mit ihren Rechten
d'administrateur

Zugänglich für Administration → Benutzer.

Oben rechts haben Sie eine Schaltfläche zum Hinzufügen eines Benutzers, a
zum Speichern und eine Schaltfläche zum Öffnen eines Support-Zugriffs.

Unten haben Sie eine Tabelle :

-   **Benutzername** : Benutzer-ID

-   **Aktiva** : ermöglicht das Deaktivieren des Kontos

-   **Nur lokal** : Benutzeranmeldung autorisieren
    Nur wenn es sich im lokalen Jeedom-Netzwerk befindet

-   **Profile** : ermöglicht die Auswahl des Benutzerprofils :

    -   **Verwalter** : bekommt alle Rechte an Jeedom

    -   **Benutzer** : kann das Dashboard sehen, Ansichten,
        Design usw. und auf Geräte / Kontrollen einwirken. Allerdings,
        Er hat keinen Zugriff auf die Konfiguration der Steuerungen / Geräte
        noch auf die Konfiguration von Jeedom.

    -   **Begrenzter Benutzer** : Der Benutzer sieht nur die
        autorisierte Geräte (konfigurierbar mit der Schaltfläche &quot;Verwalten&quot;
        Rechte")

-   **API-Schlüssel** : persönlicher API-Schlüssel des Benutzers

-   **Doppelte Authentifizierung** : Gibt an, ob eine doppelte Authentifizierung vorliegt
    ist aktiv (OK) oder nicht (NOK)

-   **Datum der letzten Verbindung** : Datum der letzten Verbindung von
    der Benutzer bei Jeedom. Bitte beachten Sie, dass dies das Verbindungsdatum ist
    Ist also, wenn Sie Ihren Computer speichern, das Datum von
    Die Verbindung wird nicht jedes Mal aktualisiert, wenn Sie zu ihr zurückkehren.

-   **Passwort ändern** : ermöglicht das Ändern des Passworts von
    l'utilisateur

-   **Entfernen** : Benutzer löschen

-   **API-Schlüssel neu generieren** : regeneriert den API-Schlüssel des Benutzers

-   **Rechte verwalten** : ermöglicht es, die Rechte von fein zu verwalten
    der Benutzer (Aufmerksamkeit, in der sich die Profile befinden müssen
    "Begrenzter Benutzer")

Rechteverwaltung 
==================

Wenn Sie auf &quot;Rechte verwalten&quot; klicken, wird ein Fenster angezeigt, in dem Sie klicken können
Benutzerrechte fein verwalten. Die erste Registerkarte wird angezeigt
die verschiedenen Geräte. Der zweite zeigt die Szenarien.

> **Wichtig**
>
> Das Profil muss eingeschränkt werden, sonst werden hier keine Einschränkungen gesetzt
> wird berücksichtigt

Sie erhalten eine Tabelle, die für jede Ausrüstung und jede erlaubt
Szenario definieren Benutzerrechte :

-   **Keine** : Der Benutzer sieht die Ausrüstung / das Szenario nicht

-   **Visualisierung** : Der Benutzer sieht die Ausrüstung / das Szenario, jedoch nicht
    kann nicht darauf reagieren

-   **Visualisierung und Ausführung** : der Benutzer sieht
    die Ausrüstung / das Szenario und kann darauf einwirken (eine Lampe anzünden, werfen
    das Skript usw.)


