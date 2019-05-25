Hier ist die Seite, wo man die Liste der Benutzer definieren kann, die befugt
sind, sich in Jeedom einzuloggen und auch ihre Administratorrechte
definieren kann.

Accessible par Réglages → Système → Utilisateurs.

Oben rechts haben Sie eine Schaltfläche zum Hinzufügen eines Benutzers,
eine zum Speichern und eine Schaltfläche zum Öffnen eines Support-Zugriffs.

Darunter haben Sie eine Tabelle :

-   **Benutzer** : der Anmeldename des Benutzers

-   **Aktiv** : zum Konto deaktivieren

-   **Lokal** : erlaubt die Benutzeranmeldung nur, wenn sie sich
    im lokalen Netzwerk von Jeedom befinden

-   **Profil** : erlaubt ein Profil für den Benutzers auszuwählen :

    -   **Administrator** : erhält alle Rechte für Jeedom

    -   **Benutzer** : Kann das Armaturenbrett, die Ansichten, das Design usw. 
        sehen und auf die Geräte/Befehle reagieren. Es besteht jedoch
        keinen Zugriff auf die Konfiguration der Befehle/Geräte oder 
        die Jeedom Konfiguration.

    -   **Eingeschränkter Benutzer** : Der Benutzer sieht nur die 
        genehmigten Geräte (konfigurierbar mit der Schaltfläche 
        "Rechte")

-   **API-Schlüssel** : der persönlichen API-Schlüssel des Benutzers

-   **Doppelte Authentifizierung** : gibt an, ob die doppelte Authentifizierung 
    aktiv ist (OK) oder nicht (NOK)

-   **Letzte Anmeldung** : Datum der letzten Anmeldung des Benutzers
    bei Jeedom. Seien Sie vorsichtig, hier ist das tatsächliche 
    Anmeldedatum. Wenn Sie Ihren Computer registrieren, wird
    das Anmeldedatum bei jeder Rückkehr nicht aktualisiert.

-   **Passwort** : erlaubt das Passwort des Benutzers 
    zu ändern

-   **Löschen** : um den Benutzer zu löschen

-   **Régénérer clef API** : régénère la clef API de l’utilisateur

-   **Rechte** : erlaubt, die Rechte des Benutzers genau zu
    l’utilisateur (attention le profil doit être en
    "Eingeschränkter Benutzer" sein)

Rechteverwaltung
==================

Wenn Sie auf "Rechte verwalten" klicken, erscheint ein Fenster, in dem Sie
die Rechte des Benutzers einstellen können. Die erste Registerkarte zeigt
die verschiedenen Geräte an. Die zweite enthält die Szenarien.

> **Wichtig**
>
> Das Profil muss eingeschränkt sein, ansonsten werden hier keine
> Einschränkungen berücksichtigt

Sie erhalten eine Tabelle, die es ermöglicht, für jedes Gerät und jedes
Szenario die Rechte des Benutzers festzulegen :

-   **Keine** : Der Benutzer sieht das Gerät/Szenario nicht.

-   **Visualisierung** : Der Benutzer sieht das Gerät/Szenario, kann sie 
    jedoch nicht verwenden

-   **Visualisierung und Ausführung** : Der Benutzer sieht das
    Gerät/Szenario und kann sie verwenden (eine Lampe anschalten, Szenarien 
    starten, etc.)


