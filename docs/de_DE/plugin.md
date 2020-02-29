Im Untermenü Plugins-Verwaltung können Sie Plugins bearbeiten, außer
wissen : Laden Sie sie herunter, aktualisieren und aktivieren Sie sie usw.

Plugins-Verwaltung 
===================

Sie können auf die Plugins-Seite über Plugins → Verwalten zugreifen
Plug-In. Sobald wir darauf klicken, finden wir die Liste von
Plugins in alphabetischer Reihenfolge und ein Link zum Markt. Plugins
deaktiviert sind ausgegraut.

> **Spitze**
>
> Setzen Sie die Maus wie an vielen Stellen auf Jeedom ganz links
> ruft ein Schnellzugriffsmenü auf (Sie können
> von deinem Profil immer sichtbar lassen). Hier das Menü
> Ermöglicht die Sortierung der Liste der Plugins nach Kategorien.

Durch Klicken auf ein Plugin greifen Sie auf dessen Konfiguration zu. Oben du
Suchen Sie den Namen des Plugins und dann in Klammern den Namen in Jeedom
(ID) und schließlich die Art der installierten Version (Stable, Beta).

> **wichtig**
>
> Beim Herunterladen eines Plugins ist es standardmäßig deaktiviert.
> Sie müssen es also selbst aktivieren.

Oben rechts einige Schaltflächen :

-   **Dokumentation** : Ermöglicht den direkten Zugriff auf die Seite von
    Plugin-Dokumentation

-   **Changelog** : ermöglicht das Anzeigen des Änderungsprotokolls des Plugins, falls vorhanden

-   **Auf den Markt schicken** : ermöglicht das Senden des Plugins auf dem Markt
    (nur verfügbar, wenn Sie der Autor sind)

-   **Details** : ermöglicht es, die Plugin-Seite auf dem Markt zu finden

-   **Entfernen** : Entfernen Sie das Plugin aus Ihrem Jeedom. Sei vorsichtig, das
    entfernt auch dauerhaft alle Geräte von diesem Plugin

Unten links befindet sich ein Statusbereich mit :

-   **Status** : ermöglicht die Anzeige des Status des Plugins (aktiv / inaktiv)

-   **Version** : die Version des installierten Plugins

-   **Aktion** : Ermöglicht das Aktivieren oder Deaktivieren des Plugins

-   **Jeedom Version** : Minimale Jeedom-Version erforderlich
    für den Betrieb des Plugins

-   **Lizenz** : Gibt die Lizenz des Plugins an, die im Allgemeinen sein wird
    AGPL

Rechts finden wir die Protokoll- und Überwachungszone, die definiert werden kann 

-   die Ebene der für das Plugin spezifischen Protokolle (wir finden die gleiche Möglichkeit in
Administration → Konfiguration auf der Registerkarte Protokolle unten auf der Seite)

-   Siehe die Plugin-Protokolle

-   Herzschlag : Alle Freiheit überprüft alle 5 Minuten, ob in den letzten X Minuten mindestens ein Plugin-Gerät kommuniziert hat (wenn Sie die Funktionalität deaktivieren möchten, geben Sie einfach 0 ein).

-   Starten Sie den Dämon neu : Wenn der Herzschlag schief geht, startet Jeedom den Daemon neu

Wenn das Plugin Abhängigkeiten und / oder einen Daemon hat, diese Bereiche
Weitere werden unter den oben genannten Bereichen angezeigt.

Nebengebäude :

-   **Name** : in der Regel wird lokal sein

-   **Status** : wird Ihnen sagen, ob die Abhängigkeiten OK oder KO sind

-   **Installation** : wird installiert oder neu installiert
    Abhängigkeiten (wenn Sie es nicht manuell tun und sie sind
    KO, Jeedom wird nach einer Weile für sich selbst sorgen)

-   **Letzte Installation** : Datum der letzten Installation von
    Nebengebäude

Dämon :

-   **Name** : in der Regel wird lokal sein

-   **Status** : wird dir sagen, ob der Dämon in Ordnung oder KO ist

-   **Konfiguration** : wird in Ordnung sein, wenn alle Kriterien für den Dämon
    Wendungen sind erfüllt oder geben Anlass zur Blockierung

-   **(Re) Anfang** : Ermöglicht das Starten oder Neustarten des Dämons

-   **Anschlag** : erlaubt den Dämon aufzuhalten (nur für den Fall
    automatische Verwaltung ist deaktiviert)

-   **Automatische Verwaltung** : ermöglicht das Aktivieren oder Deaktivieren der Verwaltung
    automatisch (wodurch Jeedom den Daemon und den verwalten kann
    bei Bedarf wiederbeleben. Sofern nicht anders angegeben, ist dies ratsam
    automatische Verwaltung aktiv lassen)

-   **Letzter Start** : Datum des letzten Starts des Dämons

> **Spitze**
>
> Einige Plugins haben einen Konfigurationsteil. Wenn ja, es
> wird unter den oben beschriebenen Abhängigkeiten und Dämonzonen angezeigt.
> In diesem Fall lesen Sie die Plugin-Dokumentation in
> Frage, wie man es konfiguriert.

Unten befindet sich ein Funktionsbereich. Dies ermöglicht es Ihnen zu sehen
wenn das Plugin eine der Jeedom-Kernfunktionen verwendet, wie z :

-   **Interact** : spezifische Wechselwirkungen

-   **cron** : ein cron pro Minute

-   **cron5** : ein cron alle 5 Minuten

-   **cron15** : alle 15 Minuten ein cron

-   **cron30** : alle 30 Minuten ein cron

-   **cronHourly** : eine cron pro Stunde

-   **cronDaily** : ein täglicher cron

> **Spitze**
>
> Wenn das Plugin eine dieser Funktionen verwendet, können Sie dies speziell tun
> Verbieten Sie ihm dies, indem Sie das Kontrollkästchen &quot;Aktivieren&quot; deaktivieren
> als nächstes anwesend.

Schließlich finden wir einen Panel-Bereich, der oder aktiviert
Deaktivieren Sie die Anzeige des Panels im Dashboard oder auf dem Handy, wenn die
Plugin bietet eine.

Plugin Installation 
========================

Um ein neues Plugin zu installieren, klicken Sie einfach auf die Schaltfläche
&quot;Markt&quot; (und dass Jeedom mit dem Internet verbunden ist). Nach kurzer Zeit von
Beim Laden erhalten Sie die Seite.

> **Spitze**
>
> Vous devez avoir saisi les informations de votre compte du Market dans
> l'administration (Konfiguration→Mises à jour→Onglet market) afin de
> retrouver les plugins que vous avez déjà achetés par exemple.

En haut de la fenêtre, vous avez des filtres :

-   **Gratuit/Payant** : permet d'afficher uniquement les gratuits ou
    les payants.

-   **Officiel/Conseillé** : permet d'afficher uniquement les plugins
    officiels ou les conseillés

-   **Installé/Non installé** : permet d'afficher uniquement les plugins
    installés ou non installés

-   **Menu déroulant Catégorie** : permet d'afficher uniquement
    certaines catégories de plugins

-   **Suche** : permet de rechercher un plugin (dans le nom ou la
    description de celui-ci)

-   **Benutzername** : affiche le nom d'utilisateur utilisé pour la
    connexion au Market ainsi que le statut de la connexion

> **Spitze**
>
> La petite croix permet de réinitialiser le filtre concerné

Une fois que vous avez trouvé le plugin voulu, il suffit de cliquer sur
celui-ci pour faire apparaître sa fiche. Cette fiche vous donne beaucoup
d'informations sur le plugin, notamment :

-   S'il est officiel/recommandé ou s'il est obsolète (il faut vraiment
    éviter d'installer des plugins obsolètes)

-   4 actions :

    -   **Installer stable** : permet d'installer le plugin dans sa
        version stable

    -   **Installer beta** : permet d'installer le plugin dans sa
        version beta (seulement pour les betatesteurs)

    -   **Installer pro** : permet d'installer la version pro (très
        peu utilisé)

    -   **Entfernen** : si le plugin est actuellement installé, ce
        bouton permet de le supprimer

En dessous, vous retrouvez la description du plugin, la compatibilité
(si Jeedom détecte une incompatibilité, il vous le signalera), les avis
sur le plugin (vous pouvez ici le noter) et des informations
complémentaires (l'auteur, la personne ayant fait la dernière mise à
jour, un lien vers la doc, le nombre de téléchargements). Sur la droite
vous retrouvez un bouton "Changelog" qui vous permet d'avoir tout
l'historique des modifications, un bouton "Dokumentation" qui renvoie
vers la Plugin-Dokumentation. Ensuite vous avez la langue disponible
et les diverses informations sur la date de la dernière version stable.

> **wichtig**
>
> Il n'est vraiment pas recommandé de mettre un plugin beta sur un
> Jeedom non beta, beaucoup de soucis de fonctionnement peuvent en
> résulter.

> **wichtig**
>
> Certains plugins sont payants, dans ce cas la fiche du plugin vous
> proposera de l'acheter. Une fois cela fait, il faut attendre une
> dizaine de minutes (temps de validation du paiement), puis retourner
> sur la fiche du plugin pour l'installer normalement.

> **Spitze**
>
> Vous pouvez aussi ajouter un plugin à Jeedom à partir d'un fichier ou
> depuis un dépôt Github. Pour cela, il faut, dans la configuration de
> Jeedom, activer la fonction adéquate dans la partie "Mises à jour et
> fichiers". Il sera ensuite possible, en mettant la souris tout à
> gauche, et en faisant apparaître le menu de la page plugin, de cliquer
> sur "Ajout depuis une autre source". Vous pourrez ensuite choisir la
> source "Fichier". Attention, dans le cas de l'ajout par un fichier
> zip, le nom du zip doit être le même que l'ID du plugin et dès
> l'ouverture du ZIP un dossier plugin\_info doit être présent.
