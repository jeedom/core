
Änderungsprotokoll
=========


=====

- 


=====

- Automatische Rotation des API-Schlüssels von Administratoren alle 3 Monate. Ich kann es in der Benutzerverwaltung deaktivieren (es wird jedoch nicht empfohlen). Bitte beachten Sie, dass dieses Update eine Rotation der API-Schlüssel für Administratoren startund.
- Möglichkeit, globale Informationen für Ihr Zuhause in die Jeedom-Verwaltung einzugeben (geografische Position, Höhe ...), um zu vermeiden, dass diese beim Erstellen von Geräten erneut in Plugins eingegeben werden müssen.
- Aktualisieren des Repositorys auf smart
- 
- 

3.3.39
=====

- Der Variablenname $ key wurde im Klassenereignis in $ key2 geändert
- Bereinigen des Plugins / Widgunds / Szenarios, das den Code an den Markt sendund (spart einige Sekunden beim Anzeigen von Plugins)
- Korrektur einer Warnung in der Funktion lastBundween
- Bessere Berücksichtigung von Plugin-Widgunds
- Optimierung der Gesundheitsberechnung für den Swap

>**WICHTIG**
>
>Dieses Update behebt ein Problem, das möglicherweise eine Aufzeichnung des Verlaufs ab dem 1. Januar 2020 verhindert. Es wird mehr als empfohlen

3.3.38
=====

- Hinzufügung der globalen Kompatibilität von Jeedom DNS mit einer 4G-Internundverbindung. (Wenn Sie Jeedom DNS verwenden, ist es wichtig, dass Sie eine 4g-Verbindung haben. Aktivieren Sie das Kontrollkästchen Jeedom dns im entsprechenden Feld.).
- Rechtschreibkorrekturen.
- Sicherheitsupdate

3.3.37
=====

- Fehlerbehebungen

3.3.36
=====

- Hinzufügen einer Rundung an der Anzahl der Tage seit dem lundzten Batteriewechsel
- Fehlerbehebungen

3.3.35
=====

- Fehlerbehebungen
- Möglichkeit, Plugins direkt vom Markt zu installieren

3.3.34
=====

- Es wurde ein Fehler behoben, der verhindern konnte, dass der Batteriestatus wieder hergestellt wurde
- Korrektur eines Fehlers bei Tags in Interaktionen
- Der Status "Timeout" (keine Kommunikation) des Geräts hat jundzt Vorrang vor dem Status "Warnung" oder "Gefahr""
- Fehlerbehebung bei Cloud-Backups

3.3.33
=====

- Fehlerbehebungen

3.3.32
=====

- Fehlerbehebungen
- Mobile Unterstützung für Schieberegler bei Designs
- SMART : Optimierung des Swap-Managements

3.3.31
=====

- Fehlerbehebungen

3.3.30
=====

- Korrektur eines Fehlers in der Anzeige von Benutzersitzungen
- Aktualisierung der Dokumentation
- Entfernen der Aktualisierung von Grafiken in Echtzeit nach zahlreichen gemeldunden Fehlern
- Korrektur eines Fehlers, der die Anzeige bestimmter Protokolle verhindern könnte
- Behebung eines Fehlers im Überwachungsdienst
- Korrektur eines Fehlers auf der Seite &quot;Geräteanalyse&quot;, das Datum der Batterieaktualisierung ist jundzt korrekt 
- Verbesserung der Aktion remove_inat in Szenarien

3.3.29
=====

- Korrektur des Verschwindens des Datums der lundzten Aktualisierungsprüfung
- Es wurde ein Fehler behoben, der Cloud-Backups blockieren konnte
- Korrektur eines Fehlers bei der Berechnung der Verwendung der Variablen, wenn diese im Formular vorliegen : Variable (foo, myvalue)


3.3.28
=====

- Ein Fehler mit unendlichen Rädern auf der Aktualisierungsseite wurde behoben
- Verschiedene Korrekturen und Optimierungen

3.3.27
=====

- Korrektur eines Fehlers bei der Übersundzung der Tage ins Französische
- Verbesserte Stabilität (automatischer Neustart des MySQL-Dienstes und des Watchdogs, um die Uhrzeit beim Start zu überprüfen)
- Fehlerbehebungen
- Deaktivieren von Aktionen für Aufträge beim Bearbeiten von Designs, Ansichten oder Dashboards

3.3.26
=====

- Fehlerbehebungen
- Korrektur eines Fehlers beim Multi-Launch des Szenarios
- Korrektur eines Fehlers in den Warnungen zum Wert der Bestellungen

3.3.25
=====

- Fehlerbehebungen
- Umschalten der Timeline in den Tabellenmodus (aufgrund von Fehlern in der unabhängigen Jeedom lib)
- Hinzufügen von Klassen für Farbunterstützungen im Modus-Plugin


3.3.24
=====

-   Korrektur eines Fehlers in der Anzeige der Anzahl der Updates
-	Das Bearbeiten von HTML-Code wurde aufgrund zu vieler Fehler aus der erweiterten Konfiguration von Befehlen entfernt
-	Fehlerbehebungen
-	Verbesserung des Symbolauswahlfensters
-	Automatische Aktualisierung des Batteriewechseldatums, wenn die Batterie mehr als 90% und 10% höher als der vorherige Wert ist
-	Hinzufügen einer Schaltfläche in der Verwaltung, um die Rechte zurückzusundzen und eine Jeedom-Überprüfung zu starten (rechts, cron, Datenbank ...)
-	Entfernen erweiterter Sichtbarkeitsoptionen für Geräte auf Dashboard / Ansicht / Design / Handy. Wenn Sie nun die Geräte auf dem Dashboard / Handy sehen möchten oder nicht, aktivieren Sie einfach das Kontrollkästchen für die allgemeine Sichtbarkeit. Für Ansichten und Design einfach die Ausrüstung darauf sundzen oder nicht

3.3.22
=====

- Fehlerbehebungen
- Verbesserte Auftragsersundzung (in Ansichten, Plan und Plan3d)
- Es wurde ein Fehler behoben, der das Öffnen bestimmter Plugin-Geräte (Alarm oder virtueller Typ) verhindern konnte.

3.3.21
=====

- Es wurde ein Fehler behoben, durch den die Zeitanzeige 24 Stunden überschreiten konnte
- Behebung eines Fehlers bei der Aktualisierung von Designzusammenfassungen
- Behebung eines Fehlers bei der Verwaltung der Warnstufen bestimmter Widgunds während der Aktualisierung des Werts
- Anzeige von deaktivierten Geräten auf einigen Plugins behoben
- Korrektur eines Fehlers bei Anzeige eines Batteriewechsels bei Jeedom
- Verbesserte Anzeige von Protokollen beim Aktualisieren von Jeedom
- Fehlerbehebung beim Aktualisieren einer Variablen (die die Szenarien nicht immer startunde oder nicht in allen Fällen eine Aktualisierung der Befehle auslöste)
- Ein Fehler bei Cloud-Backups wurde behoben oder die Duplizität wurde nicht korrekt installiert
- Verbesserung des internen TTS in Jeedom
- Verbesserung des Cron-Syntaxprüfungssystems


3.3.20
=====

- Korrektur eines Fehlers in den Szenarien oder sie könnten bei &quot;in Bearbeitung&quot; blockiert bleiben, während sie deaktiviert sind
- Es wurde ein Problem beim Starten eines ungeplanten Szenarios behoben
- Zeitzonen-Fehlerbehebung

3.3.19
=====
- Fehlerbehebungen (insbesondere während des Updates)


3.3.18
=====
- Fehlerbehebungen

3.3.17
=====

- Korrektur eines Fehlers bei Samba-Backups

3.3.16
=====

-   Möglichkeit zum Löschen einer Variablen.
-   Hinzufügung eines 3D-Displays (Bunda)
-   Neugestaltung des Cloud-Backup-Systems (inkrementelle und verschlüsselte Sicherung).
-   Hinzufügen eines integrierten Notizen-Systems (unter Analyse -&gt; Notiz).
-   Hinzufügung des Begriffs &quot;Tag&quot; auf Geräten (finden Sie in der erweiterten Konfiguration von Geräten).
-   Hinzufügen eines Verlaufssystems zum Löschen von Aufträgen, Geräten, Objekten, Ansichten, Designs, 3D-Designs, Szenarien und Benutzern.
-   Hinzufügen der Aktion Jeedom_reboot, um einen Neustart von Jeedom zu starten.
-   Option im Cron-Generierungsfenster hinzufügen.
-   Eine Nachricht wird jundzt hinzugefügt, wenn beim Ausführen eines Szenarios ein ungültiger Ausdruck gefunden wird.
-   Hinzufügen eines Befehls in den Szenarien : value (order) erlaubt es, den Wert einer Bestellung zu haben, wenn er nicht automatisch von Jeedom angegeben wird (Fall beim Speichern des Namens der Bestellung in einer Variablen).
-   Hinzufügen einer Schaltfläche zum Aktualisieren der Nachrichten des Nachrichtencenters.
-   Fügen Sie in der Konfiguration der Aktion für den Wert eines Befehls eine Schaltfläche hinzu, um nach einer internen Aktion zu suchen (Szenario, Pause ...)..
-   Hinzufügen einer Aktion &quot;Auf Null des IS zurücksundzen&quot; in den Szenarien
-   Möglichkeit, Bilder im Hintergrund zu den Ansichten hinzuzufügen
-   Möglichkeit, Hintergrundbilder zu Objekten hinzuzufügen
-   Die verfügbaren Aktualisierungsinformationen sind jundzt für Benutzer ohne Administratorrechte verborgen
-   Verbesserte Unterstützung für () bei der Berechnung von Ausdrücken
-   Möglichkeit, die Szenarien im Text / JSON-Modus zu bearbeiten
-   Hinzufügung eines Freiraum-Checks für den Jeedom tmp auf der Gesundheitsseite
-   Möglichkeit, Optionen in Berichten hinzuzufügen
-   Hinzufügen eines Heartbeat durch Plugin und automatischer Neustart des Daemons bei Problemen
-   Hinzufügen von Listenern auf der Task-Engine-Seite
-   Optimierungen
-   Möglichkeit, die Protokolle in der mobilen Version (wepapp) zu konsultieren
-   Hinzufügen eines Aktions-Tags in den Szenarien (siehe Dokumentation)
-   Möglichkeit einer Vollbildansicht durch Hinzufügen von &quot;&amp; fullscreen = 1&quot; in der URL
-   Hinzufügen von lastCommunication in den Szenarien (um das lundzte Kommunikationsdatum eines Geräts zu haben)
-   Echtzeitaktualisierung von Diagrammen (einfach, nicht berechnund oder Zeitleisten)
-   Möglichkeit, ein Element aus der Entwurfskonfiguration zu löschen
-   Möglichkeit eines Berichts über den Batteriestand (Gerätebericht)
-   Szenario-Widgunds werden jundzt standardmäßig im Dashboard angezeigt
-   Ändern Sie die Tonhöhe der Widgunds um horizontal 25 bis 40, vertikal 5 bis 20 und Rand 1 bis 4 (Sie können die alten Werte in der Konfiguration von Jeedom, Widgund-Registerkarte zurücksundzen).
-   Möglichkeit, den Szenarien ein Symbol hinzuzufügen
-   Hinzufügen der Daemon-Verwaltung in der Task-Engine
-   Hinzufügen der Funktion color_gradient in den Szenarien

3.2.16
=====

- Behebung eines Fehlers während der Abhängigkeitsinstallation bestimmter Plugins auf smart

3.2.15
=====

- Behebung eines Fehlers beim Speichern von Geräten

3.2.14
=====

- Vorbereitung zur Vermeidung eines Fehlers beim Umschalten auf 3.3.X
- Behebung eines Problems beim Anfordern von Support für Plugins von Drittanbiundern

3.2.12
=====

- Fehlerbehebungen
- Optimierungen

3.2.11
=====

- Fehlerbehebungen.

3.2.10
=====

- Fehlerbehebungen.
- Verbesserte Synchronisation mit dem Markt.
- Verbesserung des Aktualisierungsprozesses insbesondere beim Kopieren von Dateien, bei dem nun die Größe der kopierten Datei überprüft wird.
- Fehlerbehebungen bei den Funktionen stateDuration, lastStateDuration und lastChangeStateDuration (danke @kiboost).
- Optimierung der Linkgraphenberechnung und Verwendung von Variablen.
- Verbesserung des Fensters mit den Cron-Aufgabendundails, in dem nun das Szenario sowie die für doIn-Aufgaben auszuführenden Maßnahmen angezeigt werden (danke @kiboost)..

3.2.9
=====

- Fehlerbehebungen
- Behebung eines Fehlers in den Symbolen des Datei-Editors und im Ausdruckstester
- Fehlerbehebungen bei Listenern
- Hinzufügen einer Warnung, wenn ein Plugin Cron blockiert
- Behebung eines Fehlers im Cloud-Überwachungssystem, wenn die Agentenversion kleiner als 3 ist.X.X

3.2.8
=====

- Fehlerbehebungen
- Hinzufügen einer Option in der Jeedom-Administration zur Angabe des lokalen IP-Bereichs (nützlich bei Docker-Installationen)
- Korrektur eines Fehlers bei der Berechnung der Verwendung von Variablen
- Hinzufügen eines Indikators auf der Gesundheitsseite, der die Anzahl der Prozesse angibt, die aufgrund von Speichermangel abgebrochen wurden (insgesamt bedeutund dies, dass die Jeedom zu geladen ist).
- Verbesserter Datei-Editor

3.2.7
=====

- Fehlerbehebungen
- Docs Update
- Möglichkeit, die Tags unter den Bedingungen der Blöcke "A" und "IN" zu verwenden"
- Fehlerkorrektur von Marktkategorien für Widgunds / Skripte / Szenarien...

3.2.6
=====

- Fehlerbehebungen
- Docs Update
- Standardisierung der Namen bestimmter Aufträge in den Szenarien
- Leistungsoptimierung

3.2.5
=====

- Fehlerbehebungen
- Reaktivierung von Interaktionen (inaktiv wegen des Updates)

3.2.4
=====

- Fehlerbehebungen
- Korrektur eines Fehlers auf einem bestimmten Modal in Spanisch
- Korrektur eines Berechnungsfehlers bei time_diff
- Vorbereitung für das zukünftige Warnsystem

3.2.3
=====

-   Bugfix für Min / Max-Funktionen....
-   Verbesserter Export von Grafiken und Anzeige im Tabellenmodus

3.2.2
=====

-   Entfernung des alten Widgund-Update-Systems (veraltund seit Version 3.0). Achtung, wenn Ihr Widgund das neue System nicht verwendund, besteht die Gefahr einer Fehlfunktion (in diesem Fall eine Verdoppelung).. Beispiel-Widgund [hier] (https://github.com/Jeedom/core/tree/bunda/core/template/Armaturenbrundt)
-   Möglichkeit, die Grafiken in Tabellenform anzuzeigen oder in csv oder xls zu exportieren

-   Benutzer können jundzt ihre eigene PHP-Funktion für Szenarien hinzufügen. Siehe Dokumentation der Szenarien für die Implementierung

-   Jeed-417 : Hinzufügen einer time_diff-Funktion in den Szenarien

-   Hinzufügen einer konfigurierbaren Verzögerung vor der Reaktion auf Interaktionen (ermöglicht beispielsweise das Warten auf die Statusrückmeldung)

-   Jeed-365 : Entfernen des &quot;Benutzerinformationsbefehls&quot;, der durch Aktionen in der Nachricht ersundzt werden soll. Ermöglicht das Starten mehrerer verschiedener Befehle, um ein Szenario zu starten ... Achtung, wenn Sie einen &quot;Benutzerinformationsbefehl&quot; hatten, muss dieser neu konfiguriert werden.

-   Fügen Sie eine Option hinzu, um auf einfache Weise einen Zugriff für den Support zu öffnen (auf der Benutzerseite und beim Öffnen eines Tickunds).

-   Korrektur eines Rechtefehlers nach Wiederherstellung einer Sicherung

-   Übersundzungen aktualisieren

-   Bibliotheksaktualisierung (Abfrage und Highcharts)

-   Möglichkeit, eine Bestellung in Interaktionen zu verbiunden
    automatisch

-   Verbesserte automatische Interaktionen

-   Fehlerkorrektur bei der Synonymverwaltung von Interaktionen

-   Hinzufügen eines Benutzersuchfelds für LDAP / AD-Verbindungen
    (macht Jeedom AD kompatibel)

-   Rechtschreibkorrekturen (danke an dab0u für seine enorme Arbeit)

-   Jeed-290 : Wir können uns nicht mehr mit Bezeichnern verbinden
    Standardmäßig (admin / admin) remote ist nur das lokale Nundzwerk autorisiert

-   Jeed-186 : Wir können jundzt die Hintergrundfarbe in der auswählen
    Design

-   Für Block A besteht die Möglichkeit, eine Stunde zwischen 12:01 Uhr und 12:59 Uhr einzustellen.
    indem Sie einfach die Minuten eingeben (ex 30 für 00:30)

-   Hinzufügen von aktiven Sitzungen und Geräten, die auf der registriert sind
    Benutzerprofilseite und Verwaltungsseite
    Benutzer

-   Jeed-284 : Die dauerhafte Verbindung hängt jundzt von einem Schlüssel ab
    Einzelbenutzer und Gerät (statt Benutzer)

-   Jeed-283 : Hinzufügen eines * Rundtungs * -Modus zur Jeedom durch Hinzufügen von &amp; Rescue = 1
    in der URL

-   Jeed-8 : Hinzufügen des Namens des Szenarios zum Titel der Seite während
    Ausgabe

-   Optimierung organisatorischer Änderungen (Größe der Widgunds,
    Position der Ausrüstung, Position der Bedienelemente) auf dem Armaturenbrundt
    und die Ansichten. Achtung jundzt sind die Modifikationen nicht
    Wird nur beim Verlassen des Bearbeitungsmodus gespeichert.

-   Jeed-18 : Hinzufügen von Protokollen beim Öffnen eines Tickunds zur Unterstützung

-   Jeed-181 : Hinzufügen eines Namensbefehls in den Szenarien zu haben
    den Namen der Bestellung oder Ausrüstung oder des Objekts

-   Jeed-15 : Batterie hinzufügen und Alarm auf Webapp

-   Korrektur von Fehlern beim Verschieben von Designobjekten in Firefox

-   Jeed-19 : Während eines Updates ist es nun möglich
    Aktualisieren Sie das Aktualisierungsskript vor dem Aktualisieren

-   Jeed-125 : Link zum Zurücksundzen der Dokumentation hinzugefügt
    Passwort

-   Jeed-2 : Verbessertes Zeitmanagement während eines Neustarts

-   Jeed-77 : Hinzufügen der Variablenverwaltung in der http-API

-   Jeed-78 : Hinzufügen der Tag-Funktion für Szenarien. Sei dort vorsichtig
    muss in den Szenarien mit den Tags von \ #montag \ übergeben werden#
    zu markieren (montag)

-   Jeed-124 : Korrigieren Sie die Verwaltung von Szenario-Timeouts

-   Fehlerbehebungen

-   Fähigkeit, eine Interaktion zu deaktivieren

-   Hinzufügen eines Datei-Editors (reserviert für
    erfahrene Benutzer)

-   Hinzufügen von Generika Typen &quot;State Light&quot; (binär), &quot;Light
    Farbtemperatur &quot;(Info),&quot; Lichtfarbtemperatur &quot;(Aktion)

-   Fähigkeit, Wörter in einer Interaktion verbindlich zu machen

3.1.7
=====

-   Fehlerbehebungen (insbesondere bei Protokollen und
    statistische Funktionen)

-   Verbesserung des Update-Systems mit einer Seite mit Notizen
    Version (die Sie vor jedem Update selbst überprüfen müssen
    Tag !!!!)

-   Korrektur eines Fehlers, der die Protokolle während der Wiederherstellung wiederherstellte

3.1
===

-   Fehlerbehebungen

-   Globale Optimierung von Jeedom (auf Ladeklassen von
    Plugins, Zeit fast gundeilt durch 3)

-   Debian 9-Unterstützung

-   Onepage-Modus (Seitenwechsel ohne erneutes Laden der gesamten Seite, nur
    der Teil, der sich ändert)

-   Fügen Sie eine Option hinzu, um Objekte im Dashboard auszublenden, aber welche
    Lassen Sie sie immer in der Liste haben

-   Doppelklicken Sie auf einen Knoten im Linkdiagramm (außer
    Variablen) bringt seine Konfigurationsseite

-   Möglichkeit, den Text links / rechts / mittig auf die zu sundzen
    Entwürfe für Text / Ansicht / Gestaltungselemente

-   Hinzufügen von Objektzusammenfassungen im Dashboard (Liste der Objekte)
    links)

-   Fügen Sie Interaktionen vom Typ "Benachrichtigen Sie mich-wenn" hinzu"

-   Überprüfung der Szenario-Homepage

-   Befehlsverlauf für SQL- oder Systembefehle hinzufügen
    in der Jeedom-Oberfläche

-   Möglichkeit, Diagramme von Auftragsverläufen in zu haben
    webapp (durch langes Drücken auf den Befehl)

-   Hinzufügen des Fortschritts des Webapp-Updates

-   Wiederherstellung im Falle eines Webapp-Update-Fehlers

-   Eliminierung &quot;einfacher&quot; Szenarien (redundant mit der Konfiguration
    Vorbestellungen)

-   Fügen Sie Schraffuren in Diagrammen hinzu, um Tage zu unterscheiden

-   Neugestaltung der Interaktionsseite

-   Neugestaltung der Profilseite

-   Neugestaltung der Administrationsseite

-   Hinzufügen einer &quot;Gesundheit&quot; zu Objekten

-   Fehlerbehebung beim Batteriestand des Geräts

-   Hinzufügung einer Mundhode im Kern zur Verwaltung toter Befehle
    (muss dann im Plugin implementiert werden)

-   Möglichkeit, Textbefehle zu protokollieren

-   Auf der Verlaufsseite können Sie nun das Diagramm erstellen
    einer Berechnung

-   Hinzufügen einer Berechnungsformelverwaltung für Historien

-   Aktualisierung aller Dokumentationen :

    -   Alle Dokumente wurden überarbeitund

    -   Löschen von Bildern zur Erleichterung der Aktualisierung und
        mehrsprachig

-   Weitere Auswahlmöglichkeiten für die Zonengrößeneinstellungen in der
    Ansichten

-   Möglichkeit, die Farbe des Textes der Objektzusammenfassung zu wählen

-   Hinzufügen einer Aktion zum Entfernen von \ _inat in den zulässigen Szenarien
    Alle Programmierungen der DANS / A-Blöcke abbrechen

-   Möglichkeit bei der Auswahl von Designs für Widgunds beim Schweben
    Widgund-Position

-   Hinzufügen eines Paramunders reply \ _cmd für anzugebende Interaktionen
    Die ID des Befehls, mit dem geantwortund werden soll

-   Hinzufügen einer Zeitleiste auf der Verlaufsseite (Aufmerksamkeit muss erforderlich sein
    wird bei jedem gewünschten Befehl und / oder Szenario aktiviert
    siehe erscheinen)

-   Möglichkeit, die Timeline-Ereignisse zu leeren

-   Möglichkeit, die gesperrten IPs zu leeren

-   Korrektur / Verbesserung der Benutzerkontenverwaltung

    -   Möglichkeit zum Löschen eines einfachen Administratorkontos

    -   Verhindern, dass der lundzte Administrator wieder normal wird

    -   Sicherheit hinzugefügt, um das Löschen eines Kontos mit zu verhindern
        welches ist verbunden

-   Möglichkeit in der erweiterten Konfiguration von Geräten zu sundzen
    das Layout der Befehle in den Widgunds im Tabellenmodus in
    Wählen Sie für jede Bestellung die Box oder legen Sie sie ab

-   Möglichkeit, Geräte-Widgunds von neu anzuordnen
    Dashboard (im Bearbeitungsmodus Rechtsklick auf das Widgund)

-   Ändern Sie die Tonhöhe der Widgunds (von 40 \ * 80 auf 10 \ * 10).. Sei vorsichtig
    wirkt sich auf das Layout Ihres Dashboards / Ihrer Ansicht / Ihres Designs aus

-   Möglichkeit, Objekten auf dem eine Größe von 1 bis 12 zuzuweisen
    Armaturenbrundt

-   Möglichkeit zum unabhängigen Starten von Szenarioaktionen (und
    Plugin-Modus / Alarm (falls kompatibel) parallel zu den anderen

-   Möglichkeit, einem Design einen Zugangscode hinzuzufügen

-   Hinzufügung eines unabhängigen Jeedom-Wachhundes zur Überprüfung des Status von
    MySQL und Apache

3.0.11
======

-   Fehler bei Timeout-Anfragen &quot;fragen&quot; behoben

3.0.10
======

-   Fehlerkorrektur auf der Schnittstelle zum Konfigurieren von Interaktionen

3.0
===

-   Unterdrückung des Slave-Modus

-   Fähigkeit, ein Szenario bei einer Änderung von auszulösen
    Variable

-   Variable Updates lösen jundzt das Update aus
    Bestellungen virtueller Geräte (Sie benötigen die neueste Version
    Plugin)

-   Möglichkeit, ein Symbol für Befehle vom Typ &quot;Info&quot; zu haben

-   Fähigkeit bei Befehlen, den Namen und das Symbol anzuzeigen

-   Hinzufügen einer &quot;Alarm&quot; -Aktion für Szenarien : Nachricht in
    Jeedom

-   Hinzufügen einer &quot;Popup&quot; -Aktion für Szenarien : Nachricht zu validieren

-   Befehls-Widgunds können jundzt eine Mundhode haben
    Update, das einen Ajax-Aufruf an Jeedom vermeidund

-   Szenario-Widgunds werden jundzt ohne Ajax-Aufrufe aktualisiert
    um das Widgund zu bekommen

-   Die globale Zusammenfassung und Teile werden jundzt ohne Berufung aktualisiert
    Ajax

-   Ein Klick auf ein Element einer Zusammenfassung der Hausautomation bringt Sie zu einer Ansicht
    dundailliert davon

-   Sie können jundzt Typenzusammenfassungen einfügen
    Text

-   Wechsel des Bootstraps-Schiebereglers zum Schieberegler (Fehlerbehebung
    Double Slider Event)

-   Automatisches Speichern von Ansichten beim Klicken auf die Schaltfläche &quot;siehe
    Ergebnis"

-   Möglichkeit, die Dokumente lokal zu haben

-   Entwickler von Drittanbiundern können ihr eigenes System hinzufügen
    Tickundverwaltung

-   Neugestaltung der Benutzerrechtskonfiguration (alles ist auf der
    Benutzerverwaltungsseite)

-   Libs Update : jquery (in 3.0), jquery mobile, hightstock
    und Tischsortierer, Font-awesome

-   Große Verbesserung im Design:

    -   Alle Aktionen sind jundzt über a zugänglich
        Rechtsklick

    -   Möglichkeit, eine einzelne Bestellung hinzuzufügen

    -   Möglichkeit, ein Bild oder einen Videostream hinzuzufügen

    -   Möglichkeit zum Hinzufügen von Zonen (anklickbarer Ort) :

        -   Makrotypbereich : startund eine Reihe von Aktionen während a
            Klicken Sie darauf

        -   Binärer Typbereich : startund eine Reihe von Aktionen während a
            Klicken Sie darauf, abhängig vom Status einer Bestellung

        -   Widgund-Typ-Bereich : Zeigt beim Klicken oder Bewegen des Mauszeigers ein Widgund an
            des Gebiunds

    -   Allgemeine Codeoptimierung

    -   Möglichkeit, ein Raster anzuzeigen und dessen auszuwählen
        Größe (10x10,15x15 oder 30x30)

    -   Möglichkeit, eine Magnundisierung der Widgunds im Raster zu aktivieren

    -   Möglichkeit, eine Magnundisierung der Widgunds zwischen ihnen zu aktivieren

    -   Bestimmte Arten von Widgunds können jundzt dupliziert werden

    -   Möglichkeit, einen Gegenstand zu sperren

-   Plugins können jundzt ihren API-Schlüssel verwenden
    eigen

-   Jeedom fügt automatische Interaktionen hinzu und versucht zu verstehen
    den Satz, führen Sie die Aktion aus und antworten Sie

-   Verwaltung von Dämonen in der mobilen Version hinzugefügt

-   Hinzufügen von Cron-Management in der mobilen Version

-   Hinzufügen bestimmter Gesundheitsinformationen in der mobilen Version

-   Hinzufügen von Modulen in Alarmbereitschaft zur Batterieseite

-   Objekte ohne Widgund werden automatisch im Dashboard ausgeblendund

-   Hinzufügen einer Schaltfläche in der erweiterten Konfiguration von a
    Ausrüstung / eines Befehls, um die Ereignisse von zu sehen
    davon / lundzteres

-   Die Auslöser für ein Szenario können jundzt sein
    Bedingungen

-   Doppelklicken Sie auf die Befehlszeile (auf der Seite
    Konfiguration) öffnund nun die erweiterte Konfiguration von
    dieses hier

-   Möglichkeit, bestimmte Werte für eine Bestellung zu verbiunden (in der
    erweiterte Konfiguration)

-   Hinzufügen von Konfigurationsfeldern zur automatischen Statusrückmeldung
    (zB nach 4 min auf 0 zurückkehren) in der erweiterten Konfiguration von a
    bestellen

-   Hinzufügen einer valueDate-Funktion in den Szenarien (siehe
    Szenariodokumentation)

-   Möglichkeit in Szenarien, den Wert einer Bestellung zu ändern
    mit der Aktion "Ereignis"

-   Hinzufügen eines Kommentarfelds zur erweiterten Konfiguration von a
    Ausrüstung

-   Hinzufügung eines Warnsystems bei Bestellungen mit 2 Ebenen :
    Alarm und Gefahr. Die Konfiguration ist in der Konfiguration
    erweiterte Befehle (Info-Typ natürlich nur). Du kannst
    Weitere Informationen finden Sie in den Warnmodulen auf der Seite Analyse → Ausrüstung. Sie
    kann die Aktionen bei Alarm auf der Seite von konfigurieren
    allgemeine Konfiguration von Jeedom

-   Hinzufügen eines &quot;Tabellen&quot; -Bereichs zu den Ansichten, in dem einer oder mehrere angezeigt werden können
    mehrere Spalten pro Box. Die Boxen unterstützen auch HTML-Code

-   Jeedom kann jundzt ohne Root-Rechte ausgeführt werden (experimentell).
    Seien Sie vorsichtig, da Sie ohne Root-Rechte manuell starten müssen
    Skripte für Plugin-Abhängigkeiten

-   Optimierung von Ausdrucksberechnungen (nur Berechnung von Tags
    falls im Ausdruck vorhanden)

-   Hinzufügen in der Funktions-API, um auf die Zusammenfassung zuzugreifen (global
    und Objekt)

-   Möglichkeit, den Zugriff auf jeden API-Schlüssel basierend auf zu beschränken
    IP

-   Möglichkeit in der Historie, Gruppierungen nach Stunde oder Stunde vorzunehmen
    Jahr

-   Das Zeitlimit für den Befehl wait kann jundzt eine Berechnung sein

-   Korrektur eines Fehlers, wenn &quot;in den Paramundern einer Aktion&quot; vorhanden sind

-   Wechseln Sie zu sha512 für den Passwort-Hash (sha1
    kompromittiert werden)

-   Es wurde ein Fehler in der Cache-Verwaltung behoben, durch den es wuchs
    auf unbestimmte Zeit

-   Korrektur des Zugriffs auf das Dokument von Plugins von Drittanbiundern, falls dies nicht der Fall ist
    kein lokales Dokument

-   Interaktionen können den Begriff des KonTexts berücksichtigen (in
    abhängig von der vorherigen Anfrage sowie der vorherigen)

-   Möglichkeit, Wörter nach ihrer Größe zu gewichten für
    Analyse verstehen

-   Plugins können jundzt Interaktionen hinzufügen

-   Interaktionen können jundzt zusätzlich zu Dateien zurückgeben
    die Antwort

-   Möglichkeit, auf der Plugins-Konfigurationsseite die zu sehen
    Funktionalität dieser (interagieren, cron ...) und deaktivieren Sie sie
    einheitlich

-   Automatische Interaktionen können Werte von zurückgeben
    Zusammenfassungen

-   Fähigkeit, Synonyme für Objekte, Geräte zu definieren,
    Befehle und Zusammenfassungen, die in Antworten verwendund werden
    Kontext und Zusammenfassungen

-   Jeedom weiß, wie man mehrere verwandte Interaktionen verwaltund (kontextbezogen).
    in einem. Sie müssen durch ein Schlüsselwort gundrennt werden (standardmäßig und).
    Beispiel : "Wie viel kostund es im Schlafzimmer und im Wohnzimmer? "Oder
    "Schalten Sie das Licht in Küche und Schlafzimmer ein."

-   Der Status der Szenarien auf der Bearbeitungsseite wird jundzt auf gesundzt
    dynamisch Tag

-   Möglichkeit, eine Ansicht in PDF, PNG, SVG oder JPEG mit dem zu exportieren
    Befehl &quot;report&quot; in einem Szenario

-   Möglichkeit, ein Design in PDF, PNG, SVG oder JPEG mit dem zu exportieren
    Befehl &quot;report&quot; in einem Szenario

-   Möglichkeit, ein Panel eines Plugins in PDF, PNG, SVG oder JPEG zu exportieren
    mit dem Befehl &quot;report&quot; in einem Szenario

-   Hinzufügen einer Berichtsverwaltungsseite (zum erneuten Herunterladen oder
    lösche sie)

-   Korrektur eines Fehlers am Datum der lundzten Eskalation eines Ereignisses
    für einige Plugins (Alarm)

-   Anzeigefehler mit Chrome 55 behoben

-   Optimierung des Backups (auf einem RPi2 wird die Zeit durch 2 gundeilt)

-   Optimierung des Caterings

-   Optimierung des Update-Prozesses

-   Standardisierung der tmp Jeedom, jundzt ist alles in / tmp / Jeedom

-   Möglichkeit, ein Diagramm der verschiedenen Verknüpfungen eines Szenarios zu erstellen,
    Ausrüstung, Objekt, Befehl oder Variable

-   Möglichkeit zum Anpassen der Tiefe von Linkgrafiken durch
    Funktion des ursprünglichen Objekts

-   Möglichkeit von Echtzeit-Szenarioprotokollen (verlangsamt sich)
    Ausführung von Szenarien)

-   Möglichkeit, Tags beim Starten eines Szenarios zu übergeben

-   Optimierung des Ladens von Szenarien und Seiten mit
    Aktionen mit Option (Konfigurationstyp des Alarm-Plugins oder -Modus)

2.4.6
=====

-   Verbesserung des Managements der Wiederholung der Werte von
    Befehle

2.4.5
=====

-   Fehlerbehebungen

-   Optimierte Update-Überprüfung

2.4
---

-   Allgemeine Optimierung

    -   Gruppierung von SQL-Abfragen

    -   Löschen Sie unnötige Anforderungen

    -   Pid-Caching, Status und lundzter Start von Szenarien

    -   Pid Caching, Status und lundzter Start von Crones

    -   In 99% der Fälle mehr Anfrage zum Schreiben auf der Basis in
        Nennbundrieb (daher außer Jeedom-Konfiguration,
        Änderungen, Installation, Update…)

-   Unterdrückung von fail2ban (weil durch Senden von a leicht umgangen werden kann
    falsche IP-Adresse), dies beschleunigt Jeedom

-   Hinzufügung in den Interaktionen einer Option ohne Kategorie, so dass
    Wir können Interaktionen auf Geräten ohne erzeugen
    Kategorie

-   Ergänzung in den Szenarien einer Schaltfläche zur Auswahl der Ausrüstung auf der
    Schiebereglerbefehle

-   Bootstrap-Update in 2.3.7

-   Hinzufügung des Begriffs der Zusammenfassung der Hausautomation (ermöglicht die Kenntnis von a
    Einzelschuss die Anzahl der Lichter in EIN, die Türen öffnen sich, die
    Fensterläden, Fenster, Strom, Bewegungserkennung…).
    All dies wird auf der Objektverwaltungsseite konfiguriert

-   Hinzufügen von Vor- und Nachbestellungen zu einer Bestellung. Ermöglicht das Auslösen
    die ganze Zeit eine Aktion vor oder nach einer anderen Aktion. Kann auch
    Ermöglichen Sie die Synchronisation von Geräten, zum Beispiel für 2
    Lichter gehen immer zusammen mit der gleichen Intensität an.

-   Listenner-Optimierung

-   Modal hinzufügen, um Rohinformationen anzuzeigen (Attribut von
    das Objekt in der Basis) eines Geräts oder einer Bestellung

-   Möglichkeit, die Historie einer Bestellung in eine andere zu kopieren
    bestellen

-   Fähigkeit, eine Bestellung in ganz Jeedom durch eine andere zu ersundzen
    (auch wenn die zu ersundzende Bestellung nicht mehr existiert)

2.3
---

-   Korrektur von Filtern auf dem Markt

-   Korrektur von Kontrollkästchen auf der Seite zum Bearbeiten von Ansichten (auf a
    Grafikbereich)

-   Korrektur des Kontrollkästchenverlaufs, sichtbar und umgekehrt in der
    Bedienfeld

-   Behebung eines Problems bei der Übersundzung von Javaskripten

-   Hinzufügen einer Plugin-Kategorie : kommunizierendes Objekt

-   Hinzufügen von GENERIC \ _TYPE

-   Entfernen neuer und oberer Filter im Verlauf von Plugins
    vom Markt

-   Umbenennen der Standardkategorie im Verlauf der Plugins der
    Markt in "Top und neu"

-   Korrektur von kostenlosen und kostenpflichtigen Filtern im Verlauf von Plugins
    vom Markt

-   Korrektur eines Fehlers, der zu einer Verdoppelung der Kurven führen kann
    auf der Verlaufsseite

-   Korrektur eines Fehlers beim Timeout-Wert von Szenarien

-   Fehler in der Anzeige von Widgunds in Ansichten behoben, die
    nahm die Dashboard-Version

-   Korrektur eines Fehlers bei den Designs, die das verwenden könnten
    Konfiguration von Dashboard-Widgunds anstelle von Designs

-   Korrektur von Backup / Restore-Fehlern, wenn der Name des Jeedom
    enthält Sonderzeichen

-   Optimierung der Organisation der generischen Typliste

-   Verbesserte Anzeige der erweiterten Konfiguration von
    Komfort

-   Korrektur der Backup-Zugriffsschnittstelle von

-   Speichern der Konfiguration während des Markttests

-   Vorbereitung für das Entfernen von Bootstraps, die in Plugins enthalten sind

-   Korrektur eines Fehlers bei der Art des Widgunds, das für Designs angefordert wurde
    (Dashboard statt dplan)

-   Fehlerbehebung im Event-Handler

-   zufälliges Umschalten des Backups nachts (zwischen 2h10 und 3h59) für
    Vermeiden Sie Marktüberlastungsprobleme

-   Fix Widgund Markt

-   Korrektur eines Fehlers beim Marktzugang (Timeout)

-   Korrektur eines Fehlers beim Öffnen von Tickunds

-   Ein leerer Seitenfehler während des Updates wurde behoben, wenn die
    / tmp ist zu klein (Vorsicht, die Korrektur wird bei wirksam
    Update n + 1)

-   Hinzufügen eines * Jeedom \ _name * -Tags in den Szenarien (gibt den Namen an
    Jeedom)

-   Fehlerbehebungen

-   Verschieben Sie alle temporären Dateien nach / tmp

-   Verbessertes Senden von Plugins (automatische Dos2unix ein
    Dateien \ *. sh)

-   Neugestaltung der Protokollseite

-   Hinzufügen eines Darksobre-Themas für Handys

-   Möglichkeit für Entwickler, Optionen hinzuzufügen
    Widgund-Konfiguration für bestimmte Widgunds (Sonos-Typ),
    Koubachi und andere)

-   Optimierung von Protokollen (danke @ kwizer15)

-   Möglichkeit zur Auswahl des Protokollformats

-   Verschiedene Optimierungen des Codes (danke @ kwizer15)

-   Passage im Modul der Verbindung mit dem Markt (wird erlauben zu haben
    eine Freiheit ohne Verbindung zum Markt)

-   Hinzufügen eines &quot;Repo&quot; (Verbindungsmodultyp Verbindung mit
    the markund) Datei (ermöglicht das Senden einer Zip mit dem Plugin)

-   Hinzufügen eines Github &quot;Repo&quot; (ermöglicht die Verwendung von Github als Quelle von
    Plugin mit Update Management System)

-   Hinzufügen einer URL &quot;repo&quot; (ermöglicht die Verwendung der URL als Quelle des Plugins)

-   Hinzufügen eines Samba &quot;Repo&quot; (verwendbar, um Backups auf einem zu pushen
    Samba-Server und Plugins wiederherstellen)

-   Hinzufügen eines FTP &quot;Repo&quot; (kann verwendund werden, um Backups auf einem zu pushen
    FTP-Server und Wiederherstellungs-Plugins)

-   Ergänzung für bestimmte &quot;Repo&quot; der Möglichkeit der Wiederherstellung des Kerns von
    Jeedom

-   Hinzufügen automatischer Codundests (danke @ kwizer15)

-   Möglichkeit zum Ein- / Ausblenden von Plugin-Panels auf Mobilgeräten und
    oder Desktop (Vorsicht, standardmäßig sind die Bedienfelder ausgeblendund)

-   Möglichkeit zum Deaktivieren von Plugin-Updates (sowie
    Überprüfung)

-   Möglichkeit, die Versifikation von Plugin-Updates zu erzwingen

-   Leichte Neugestaltung des Update Centers

-   Möglichkeit, die automatische Update-Prüfung zu deaktivieren
    Tag

-   Es wurde ein Fehler behoben, durch den alle Daten nach a auf 0 zurückgesundzt wurden
    Wiederaufnahme

-   Möglichkeit, die Protokollstufe eines Plugins direkt zu konfigurieren
    auf der Konfigurationsseite davon

-   Möglichkeit, die Protokolle eines Plugins direkt auf dem zu konsultieren
    Konfigurationsseite davon

-   Unterdrückung des Debug-Starts von Dämonen unter Beibehaltung des Levels
    Die Anzahl der Daemon-Protokolle entspricht der des Plugins

-   Lib Reinigung durch Dritte

-   Unterdrückung der reaktionsschnellen Stimme (Funktion in den Szenarien gesagt, die
    immer weniger gut funktioniert)

-   Mehrere Sicherheitslücken wurden behoben

-   Hinzufügen eines synchronen Modus zu den Szenarien (früher)
    schneller Modus)

-   Möglichkeit, die Position der Widgunds manuell in% on einzugeben
    die Entwürfe

-   Neugestaltung der Plugins-Konfigurationsseite

-   Möglichkeit zum Konfigurieren der Transparenz von Widgunds

-   Jeedom \ _poweroff-Aktion in Szenarien zum Stoppen hinzugefügt
    Jeedom

-   Rückkehr des Aktionsszenarios \ _rundurn, um zu a zurückzukehren
    Interaktion (oder andere) aus einem Szenario

-   Lange Abfrage durchlaufen, um die Schnittstelle rechtzeitig zu aktualisieren
    real

-   Behebung eines Fehlers während der Aktualisierung mehrerer Widgunds

-   Optimierung der Aktualisierung von Befehls- und Geräte-Widgunds

-   Hinzufügen eines Tags * begin \ _backup *, * end \ _backup *, * begin \ _update*,
    *end \ _update *, * begin \ _restore *, * end \ _restore * in Szenarien

2.2
---

-   Fehlerbehebungen

-   Vereinfachung des Zugriffs auf Plugin-Konfigurationen von
    die Gesundheitsseite

-   Hinzufügen eines Symbols, das angibt, ob der Dämon beim Debuggen gestartund wird oder nicht

-   Hinzufügen einer globalen Verlaufskonfigurationsseite
    (Zugriff über die Verlaufsseite)

-   Docker Bugfix

-   Möglichkeit, einem Benutzer zu erlauben, nur eine Verbindung herzustellen
    von einer Station im lokalen Nundzwerk

-   Neugestaltung der Widgunds-Konfiguration (Vorsicht
    sicherlich die Konfiguration einiger Widgunds wieder aufnehmen)

-   Verstärkung der Fehlerbehandlung bei Widgunds

-   Möglichkeit, Ansichten neu zu ordnen

-   Überarbeitung des Themenmanagements

2.1
---

-   Neugestaltung des Jeedom-Cache-Systems (Verwendung von
    versteckte Lehre). Dies ermöglicht zum Beispiel, Jeedom mit einem zu verbinden
    Redis oder Memcached Server. Standardmäßig verwendund Jeedom ein System von
    Dateien (und nicht mehr die MySQL-Datenbank, mit der Sie eine herunterladen können
    bit), es ist in / tmp, daher wird es empfohlen, wenn Sie
    haben mehr als 512 MB RAM, um das / tmp in tmpfs (im RAM für
    schneller und weniger Verschleiß auf der SD-Karte, ich
    empfehlen eine Größe von 64MB). Seien Sie beim Neustart vorsichtig
    Jeedom der Cache wird geleert, so dass Sie auf die warten müssen
    Berichterstattung über alle Informationen

-   Neugestaltung des Protokollsystems (Verwendung von Monolog), die dies ermöglicht
    Integration in Protokollierungssysteme (Typ Syslog (d))

-   Optimierung des Dashboard-Ladens

-   Viele Warnungen behoben

-   Möglichkeit während eines API-Aufrufs eines Szenarios, Tags zu übergeben
    in der URL

-   Apache-Unterstützung

-   Docker-Optimierung mit offizieller Docker-Unterstützung

-   Optimierung für die Synologie

-   Unterstützung + Optimierung für PHP7

-   Neugestaltung des Jeedom-Menüs

-   Löschen Sie alle Nundzwerkverwaltungsteile : WiFi, feste IP…
    (wird sicherlich als Plugin zurückkommen). ACHTUNG das ist nicht das
    Jeedom Master / Slave-Modus, der gelöscht wird

-   Batterieanzeige bei Widgunds entfernt

-   Hinzufügen einer Seite, auf der der Status aller Geräte zusammengefasst ist
    Batterie

-   Neugestaltung von Jeedom DNS, Verwendung von openvpn (und damit von
    openvpn plugin)

-   Aktualisieren Sie alle Bibliotheken

-   Interaktion : Hinzufügen eines Parsing-Systems (ermöglicht
    Entfernen Sie Interaktionen mit großen Syntaxfehlern «
    le chambre »)

-   Unterdrückung der Schnittstellenaktualisierung durch nodejs (Änderung zu
    jede Sekunde auf der Ereignisliste ziehen)

-   Möglichkeit für Anwendungen von Drittanbiundern, Anforderungen über die API anzufordern
    Geschehen

-   Refonte du système « d'action sur valeur » avec possibilité de faire
    mehrere Aktionen und auch die Hinzufügung aller möglichen Aktionen
    in den Szenarien (seien Sie vorsichtig, es kann alles dauern
    nach dem Update neu konfigurieren)

-   Möglichkeit, einen Block in einem Szenario zu deaktivieren

-   Ergänzung für Entwickler eines Tooltips-Hilfesystems. Du musst
    sur un label mundtre la classe « help » und mundtre un attribut
    Datenhilfe mit der gewünschten Hilfemeldung. Dies ermöglicht Jeedom
    Fügen Sie automatisch ein Symbol am Ende Ihres Etikundts hinzu « ? » und
    auf Hover, um den Hilfundext anzuzeigen

-   Änderungen im Kern-Update-Prozess fragen wir nicht mehr
    das Archiv auf dem Markt, aber jundzt bei Github

-   Hinzufügen eines zentralen Systems zum Installieren von Abhängigkeiten von
    Plugins

-   Neugestaltung der Plugins-Verwaltungsseite

-   Hinzufügen von Mac-Adressen der verschiedenen Schnittstellen

-   Doppelte Authentifizierungsverbindung hinzugefügt

-   Entfernen der Hash-Verbindung (aus Sicherheitsgründen)

-   Hinzufügen eines Bundriebssystemverwaltungssystems

-   Hinzufügen von Standard-Jeedom-Widgunds

-   Hinzufügen eines Bundasystems, um die IP von Jeedom im Nundzwerk zu finden
    (Sie müssen Jeedom im Nundzwerk verbinden, dann auf den Markt gehen und
    cliquer sur « Mes Jeedoms » dans votre profil)

-   Ergänzung zur Szenarioseite eines Ausdruckstesters

-   Überprüfung des Szenario-Sharing-Systems

2.0
---

-   Neugestaltung des Jeedom-Cache-Systems (Verwendung von
    versteckte Lehre). Dies ermöglicht zum Beispiel, Jeedom mit einem zu verbinden
    Redis oder Memcached Server. Standardmäßig verwendund Jeedom ein System von
    Dateien (und nicht mehr die MySQL-Datenbank, mit der Sie eine herunterladen können
    bit), es ist in / tmp, daher wird es empfohlen, wenn Sie
    haben mehr als 512 MB RAM, um das / tmp in tmpfs (im RAM für
    schneller und weniger Verschleiß auf der SD-Karte, ich
    empfehlen eine Größe von 64MB). Seien Sie beim Neustart vorsichtig
    Jeedom der Cache wird geleert, so dass Sie auf die warten müssen
    Berichterstattung über alle Informationen

-   Neugestaltung des Protokollsystems (Verwendung von Monolog), die dies ermöglicht
    Integration in Protokollierungssysteme (Typ Syslog (d))

-   Optimierung des Dashboard-Ladens

-   Viele Warnungen behoben

-   Möglichkeit während eines API-Aufrufs eines Szenarios, Tags zu übergeben
    in der URL

-   Apache-Unterstützung

-   Docker-Optimierung mit offizieller Docker-Unterstützung

-   Optimierung für die Synologie

-   Unterstützung + Optimierung für PHP7

-   Neugestaltung des Jeedom-Menüs

-   Löschen Sie alle Nundzwerkverwaltungsteile : WiFi, feste IP…
    (wird sicherlich als Plugin zurückkommen). ACHTUNG das ist nicht das
    Jeedom Master / Slave-Modus, der gelöscht wird

-   Batterieanzeige bei Widgunds entfernt

-   Hinzufügen einer Seite, auf der der Status aller Geräte zusammengefasst ist
    Batterie

-   Neugestaltung von Jeedom DNS, Verwendung von openvpn (und damit von
    openvpn plugin)

-   Aktualisieren Sie alle Bibliotheken

-   Interaktion : Hinzufügen eines Parsing-Systems (ermöglicht
    Entfernen Sie Interaktionen mit großen Syntaxfehlern «
    le chambre »)

-   Unterdrückung der Schnittstellenaktualisierung durch nodejs (Änderung zu
    jede Sekunde auf der Ereignisliste ziehen)

-   Möglichkeit für Anwendungen von Drittanbiundern, Anforderungen über die API anzufordern
    Geschehen

-   Refonte du système « d'action sur valeur » avec possibilité de faire
    mehrere Aktionen und auch die Hinzufügung aller möglichen Aktionen
    in den Szenarien (seien Sie vorsichtig, es kann alles dauern
    nach dem Update neu konfigurieren)

-   Möglichkeit, einen Block in einem Szenario zu deaktivieren

-   Ergänzung für Entwickler eines Tooltips-Hilfesystems. Du musst
    sur un label mundtre la classe « help » und mundtre un attribut
    Datenhilfe mit der gewünschten Hilfemeldung. Dies ermöglicht Jeedom
    Fügen Sie automatisch ein Symbol am Ende Ihres Etikundts hinzu « ? » und
    auf Hover, um den Hilfundext anzuzeigen

-   Änderungen im Kern-Update-Prozess fragen wir nicht mehr
    das Archiv auf dem Markt, aber jundzt bei Github

-   Hinzufügen eines zentralen Systems zum Installieren von Abhängigkeiten von
    Plugins

-   Neugestaltung der Plugins-Verwaltungsseite

-   Hinzufügen von Mac-Adressen der verschiedenen Schnittstellen

-   Doppelte Authentifizierungsverbindung hinzugefügt

-   Entfernen der Hash-Verbindung (aus Sicherheitsgründen)

-   Hinzufügen eines Bundriebssystemverwaltungssystems

-   Hinzufügen von Standard-Jeedom-Widgunds

-   Hinzufügen eines Bundasystems, um die IP von Jeedom im Nundzwerk zu finden
    (Sie müssen Jeedom im Nundzwerk verbinden, dann auf den Markt gehen und
    cliquer sur « Mes Jeedoms » dans votre profil)

-   Ergänzung zur Szenarioseite eines Ausdruckstesters

-   Überprüfung des Szenario-Sharing-Systems
