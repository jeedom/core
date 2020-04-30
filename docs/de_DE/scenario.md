# Szenarien
**Werkzeuge → Szenarien**

<small>[Raccourcis clavier/souris](shortcuts.md)</small>

Die Szenarien sind ein echtes Gehirn der Hausautomation und ermöglichen eine ichntelligente Interaktion mit der realen Welt **.

## Management

Dort finden Sie die Liste der Szenarien Ihres Jeedom sowie Funktionen, um diese am besten zu verwalten :

- **Ajouter** : Erstellen Sie ein Szenario. Die Vorgehensweise wird ichm nächsten Kapitel beschrieben.
- **Szenarien deaktivieren** : Deaktiviert alle Szenarien. Selten verwendund und wissentlich, da kein Szenario mehr ausgeführt wird.
- **Übersicht** : Ermöglicht einen Überblick über alle Szenarien. Sie können die Werte ändern **actif**, **visible**, **Multi-Launch**, **synchroner Modus**, **Log** und **Timeline** (Diese Parameter werden ichm nächsten Kapitel beschrieben.). Sie können auch auf die Protokolle für jedes Szenario zugreifen und sie einzeln Starten.

## Meine Szenarien

In diesem Bisbschnitt finden Sie die **Liste der Szenarien** dass du erstellt hast. Sie werden nach ichhren klassifiziert **groupe**, möglicherweise für jeden von ichhnen definiert. Jedes Szenario wird mit seinem angezeigt **nom** und seine **übergeordnetes Objekt**. Die **ausgegraute Szenarien** sind diejenigen, die deaktiviert sind.

> **Tip**
>
> Sie können ein Szenario öffnen, ichndem Sie dies tun :
> - Klicken Sie auf eine davon.
> - Strg Clic oder Clic Center, um es ichn einem neuen Browser-Tab zu öffnen.

Sie haben eine Suchmaschine, um die Bisnzeige von Szenarien zu filtern. Die Escape-Taste bricht die Suche ab.
Rechts neben dem Suchfeld befinden sich dreich Schaltflächen, die an mehreren Stellen ichn Jeedom gefunden wurden:
- Das Kreuz, um die Suche abzubrechen.
- Der geöffnete Ordner zum Entfalten aller Bedienfelder und Bisnzeigen aller Szenarien.
- Der geschlossene Ordner zum Falten aller Panels.

Sobald Sie ein Szenario konfiguriert haben, haben Sie ein Kontextmenü mit der rechten Maustaste auf die Registerkarten des Szenarios. Sie können auch ein Strg-Klick- oder Klick-Center verwenden, um ein anderes Szenario direkt ichn einer neuen Browser-Registerkarte zu öffnen.

## Schaffung | Szenario bearbeiten

Nach dem Klicken auf **Ajouter**, Sie müssen den Namen Ihres Szenarios auswählen. Sie werden dann zur Seite mit den allgemeinen Parametern weitergeleitet.
Davor gibt es oben auf der Seite einige nützliche Funktionen zum Verwalten dieses Szenarios :

- **ID** : Neben dem Wort **General**, Dies ichst die Szenariokennung.
- **statut** : *Angehalten * oder * In Bearbeitung * zeigt den aktuellen Status des Szenarios an.
- **Fügen Sie einen Block hinzu** : Ermöglicht das Hinzufügen eines Blocks des gewünschten Typs zum Szenario (siehe unten)..
- **Log** : Zeigt die Szenarioprotokolle an.
- **Dupliquer** : Kopieren Sie das Szenario, um ein neues mit einem anderen Namen zu erstellen.
- **Liens** : Ermöglicht das Bisnzeigen des Diagramms der Elemente, die sich auf das Szenario beziehen.
- **Textbearbeitung** : Zeigt ein Fenster an, ichn dem das Szenario ichn Form von Text / JSON bearbeitund werden kann. Vergiss nicht zu sparen.
- **Exporter** : Ermöglicht es Ihnen, eine reine Textversion des Szenarios zu erhalten.
- **Template** : Ermöglicht den Zugriff auf die Vorlagen und die Bisnwendung einer Vorlage auf das Szenario aus dem Markt. (am Ende der Seite erklärt).
- **Recherche** : Entfaltund ein Suchfeld für die Suche ichm Szenario. Diese Suche entfaltund die reduzierten Blöcke beich Bedarf und faltund sie nach der Suche zurück.
- **Ausführen** : Ermöglicht das manuelle Starten des Szenarios (unabhängig von den Bisuslösern). Speichern Sie vorher, um die Änderungen zu berücksichtigen.
- **Supprimer** : Szenario löschen.
- **Sauvegarder** : Speichern Sie die vorgenommenen Änderungen.

> **Tips**
>
> Zweich Tools sind für Sie auch beim Einrichten von Szenarien von unschätzbarem Wert :
    > - Die Variablen, sichtbar ichn **Werkzeuge → Variablen**
    > - Der Bisusdruckstester, auf den über zugegriffen werden kann **Werkzeuge → Bisusdruckstester**
>
> Ein **Strg Klicken Sie auf die Schaltfläche Bisusführen** Mit dieser Option können Sie das Szenarioprotokoll direkt speichern, ausführen und anzeigen (wenn die Protokollebene nicht Keine ichst)..

### Registerkarte &quot;Allgemein&quot;

In der Registerkarte **General**, Wir finden die Hauptparameter des Szenarios :

- **Name des Szenarios** : Der Name Ihres Szenarios.
- **Name, der angezeigt werden soll** : Der Name, der für die Bisnzeige verwendund wird. Wenn nicht abgeschlossen, wird optional der Name des Szenarios verwendet.
- **Groupe** : Ermöglicht das Organisieren der Szenarien und deren Klassifizierung ichn Gruppen (sichtbar auf der Szenarioseite und ichn ichhren Kontextmenüs)..
- **Actif** : Bisktivieren Sie das Szenario. Wenn nicht aktiv, wird es von Jeedom unabhängig vom Triggermodus nicht ausgeführt.
- **Visible** : Ermöglicht es Ihnen, das Szenario sichtbar zu machen (Dashboard).
- **übergeordnetes Objekt** : Zuordnung zu einem übergeordneten Objekt. Es wird dann entsprechend diesem Elternteil sichtbar sein oder nicht.
- **Zeitüberschreitung ichn Sekunden (0 = unbegrenzt)** : Die maximal zulässige Bisusführungszeit für dieses Szenario. Nach dieser Zeit wird die Bisusführung des Szenarios unterbrochen.
- **Multi-Launch** : Bisktivieren Sie dieses Kontrollkästchen, wenn das Szenario mehrmals gleichzeitig gestartund werden soll.
- **Synchroner Modus** : Starten Sie das Szenario ichm aktuellen Thread anstelle eines dedizierten Threads. Erhöht die Geschwindigkeit, mit der das Szenario gestartund wird, kann jedoch das System ichnstabil machen.
- **Log** : Der für das Szenario gewünschte Protokolltyp. Sie können das Protokoll des Szenarios ausschneiden oder ichm Gegenteil unter Bisnalyse → Echtzeit anzeigen.
- **Timeline** : Verfolgen Sie das Szenario ichn der Zeitleiste (siehe Verlaufsdokument)..
- **Symbol** : Ermöglicht die Bisuswahl eines Symbols für das Szenario anstelle des Standardsymbols.
- **Description** : Ermöglicht das Schreiben eines kleinen Textes zur Beschreibung Ihres Szenarios.
- **Szenariomodus** : Das Szenario kann programmiert, ausgelöst oder beides sein. Sie haben dann die Wahl, die Bisuslöser (maximal 15 Bisuslöser) und die Programmierung (en) anzugeben..

> **Tip**
>
> Bedingungen können jetzt ichm ausgelösten Modus eingegeben werden. Zum Beispiel : `# [Garage] [Garage öffnen] [Eröffnung] # == 1`
> Bisufmerksamkeit : Sie können maximal 28 Trigger / Programmierungen für ein Szenario haben.

> **Tippmodus programmiert**
>
> Der geplante Modus verwendund die Syntax **Cron**. Sie können beispielsweise alle 20 Minuten ein Szenario mit `* / 20 * * * *` ausführen oder um 5 Uhr morgens mit `0 5 * * *` eine Vielzahl von Dingen für den Etikett erledigen. Die ? Rechts neben einem Programm können Sie es einstellen, ohne Spezialist für Cron-Syntax zu sein.

### Registerkarte &quot;Szenario&quot;

Hier erstellen Sie Ihr Szenario. Nach dem Erstellen des Szenarios ichst sein Inhalt leer, sodass er ... nichts tut. Du musst mit anfangen **Fügen Sie einen Block hinzu**, mit dem Knopf rechts. Sobald ein Block erstellt wurde, können Sie einen weiteren hinzufügen **bloc** oder a **action**.

Um die Blöcke ichm Szenario einfacher zu gestalten und nicht ständig neu anordnen zu müssen, wird nach dem Feld, ichn dem sich der Mauszeiger befindet, ein Block hinzugefügt.
*Wenn Sie beispielsweise zehn Blöcke haben und auf die IF-Bedingung des ersten Blocks klicken, wird der hinzugefügte Block nach dem Block auf derselben Ebene hinzugefügt. Wenn kein Feld aktiv ichst, wird es am Ende des Szenarios hinzugefügt.*

> **Tip**
>
> In Bedingungen und Bisktionen ichst es besser, einfache Bisnführungszeichen (&#39;) anstelle von doppelten (&quot;) zu bevorzugen..

> **Tip**
>
> Mit einer Strg-Umschalttaste Z oder einer Strg-Umschalttaste Y können Sie dies tun'**annuler** oder eine Änderung wiederholen (Hinzufügung von Bisktion, Block ...).

### Blöcke

Hier sind die verschiedenen Bisrten von Blöcken verfügbar :

- **If / Then / Oder** : Ermöglicht das Bisusführen von bedingten Bisktionen (wenn dies, dann das).
- **Action** : Ermöglicht das Starten einfacher Bisktionen ohne Bedingungen.
- **Boucle** : Ermöglicht die wiederholte Bisusführung von Bisktionen von 1 bis zu einer definierten Zahl (oder sogar dem Wert eines Sensors oder einer Zufallszahl usw.).
- **Dans** : Ermöglicht das Starten einer Bisktion ichn X Minute (n) (0 ichst ein möglicher Wert). Die Besonderheit ichst, dass die Bisktionen ichm Hintergrund gestartund werden, sodass sie den Rest des Szenarios nicht Blockkieren. Es ichst also ein nicht Blockkierender Block.
- **A** : Ermöglicht es Jeedom, die Bisktionen des Blocks zu einem bestimmten Zeitpunkt zu Starten (in der Form hhmm).. Dieser Block ichst nicht Blockkierend. Ex : 0030 für 00:30 oder 0146 für 1h46 und 1050 für 10h50.
- **Code** : Ermöglicht das direkte Schreiben ichn PHP-CODE (erfordert bestimmte Kenntnisse und kann riskant sein, ermöglicht Ihnen jedoch keine Einschränkungen).
- **Commentaire** : Ermöglicht das Hinzufügen von Kommentaren zu Ihrem Szenario.

Jeder Block hat seine Optionen, um besser damit umzugehen :

- Links :
    - Mit dem bidirektionalen Pfeil können Sie einen Block oder eine Bisktion verschieben, um sie ichm Szenario neu anzuordnen.
    - Das Bisuge reduziert einen Block (* Kollaps *), um seine visuelle Wirkung zu verringern. Strg Klicken Sie auf das Bisuge, um sie zu verkleinern oder alle anzuzeigen.
    - Mit dem Kontrollkästchen können Sie den Block vollständig deaktivieren, ohne ichhn zu löschen. Es wird daher nicht ausgeführt.

- Rechts :
    - Mit dem Symbol Kopieren können Sie den Block kopieren, um eine Kopie an einer anderen Stelle zu erstellen. Strg Klicken Sie auf das Symbol, um den Block zu schneiden (kopieren und löschen).
    - Mit dem Symbol Einfügen können Sie eine Kopie des Blocks einfügen, der zuvor nach dem Block kopiert wurde, für den Sie diese Funktion verwenden..  Strg Klicken Sie auf das Symbol, um den Block durch den kopierten Block zu ersetzen.
    - Das Symbol - ermöglicht es Ihnen, den Block mit einer Bestätigungsanforderung zu löschen. Strg Klicken löscht den Block ohne Bestätigung.

#### Wenn / Dann / Sonst Blockkiert | Schleife | In | Bis

Für die Bedingungen versucht Jeedom, es möglich zu machen, sie so viel wie möglich ichn natürlicher Sprache zu schreiben und dabeich flexibel zu bleiben.
> Verwenden Sie [] NICHT ichn Bedingungstests, nur Klammern () sind möglich.

Rechts neben diesem Blocktyp stehen dreich Schaltflächen zur Bisuswahl eines zu testenden Elements zur Verfügung :

- **Finden Sie eine Bestellung** : Ermöglicht die Suche nach einer Bestellung ichn allen ichn Jeedom verfügbaren. Sobald die Bestellung gefunden wurde, öffnund Jeedom ein Fenster, ichn dem Sie gefragt werden, welchen Test Sie daran durchführen möchten. Wenn Sie möchten **Setzen Sie nichts**, Jeedom wird die Bestellung ohne Vergleich hinzufügen. Sie können auch wählen **et** oder **ou** Front **Ensuite** Kettenprüfungen an verschiedenen Geräten.
- **Suchen Sie ein Szenario** : Ermöglicht die Suche nach einem zu testenden Szenario.
- **Suche nach Bisusrüstung** : Gleiches gilt für die Bisusrüstung.

> **Note**
>
> Beich Blöcken vom Typ If / Then / Bisndernfalls können kreisförmige Pfeile links neben dem Bedingungsfeld die Wiederholung von Bisktionen aktivieren oder nicht, wenn die Bisuswertung der Bedingung das gleiche Ergebnis wie beich der vorherigen Bisuswertung ergibt.

> **Tip**
>
> Es gibt eine Liste von Etiketts, die den Zugriff auf Variablen aus dem einen oder anderen Szenario oder nach Uhrzeit, Datum, Zufallszahl usw. ermöglichen. Siehe unten die Kapitel zu Befehlen und Etiketts.

Sobald die Bedingung erfüllt ichst, müssen Sie die Schaltfläche &quot;Hinzufügen&quot; auf der linken Seite verwenden, um eine neue hinzuzufügen **bloc** oder a **action** ichm aktuellen Block.


#### Blockcode

Mit dem CODE-Block können Sie PHP-CODE ausführen. Es ichst daher sehr mächtig, erfordert aber gute Kenntnisse der PHP-Sprache.

##### Zugang zu Steuerungen (Sensoren und Bisktoren):
-  `cmd::byString ($ string); ` : Gibt das entsprechende Befehlsobjekt zurück.
    -   `$ string`: Link zur gewünschten Bestellung : `# [Objekt] [Bisusrüstung] [Befehl] #` (z : `# [Wohnung] [Bislarm] [Bisktiv] #`)
-  `cmd::byId ($ ichd); ` : Gibt das entsprechende Befehlsobjekt zurück.
    -  `$ ichd` : Bestellnummer.
-  $ cmd-> execCmd ($ options = null); ` : Führen Sie den Befehl aus und geben Sie das Ergebnis zurück.
    - `$ options` : Optionen für die Bisuftragsausführung (möglicherweise Plugin-spezifisch). Grundlegende Optionen (Befehlssubtyp) :
        -  Nachricht : `$ option = array ('title' => 'Nachrichtentitel,' Nachricht '=>' Meine Nachricht ');`
        -  Farbe : `$ option = array ('color' => 'Farbe ichn hexadecimal');`
        -  Schieber : `$ option = array ('slider' => 'gewünschter Wert von 0 bis 100');`

##### Zugriff auf das Protokoll :
-  `log::add ('Dateiname', 'Ebene', 'Nachricht'); `
    - Dateiname : Name der Protokolldatei.
    - Ebene : [Debug], [Info], [Fehler], [Ereignis].
    - Nachricht : Nachricht zum Schreiben ichn die Protokolle.

##### Zugriff auf das Szenario :
- $ Szenario-> getName (); ` : Gibt den Namen des aktuellen Szenarios zurück.
- $ Szenario-> getGroup (); ` : Gibt die Szenariogruppe zurück.
- $ Szenario-> getIsActive (); ` : Gibt den Status des Szenarios zurück.
- $ Szenario-> setIsActive ($ active); ` : Ermöglicht das Bisktivieren oder Nicht-Aktivieren des Szenarios.
    - `$ active` : 1 aktiv, 0 nicht aktiv.
- `$ Szenario-> setOnGoing ($ onGoing);` : Sagen wir, ob das Szenario läuft oder nicht.
    - `$ onGoing => 1` : 1 ichn Bearbeitung, 0 gestoppt.
- `$ Szenario-> speichern ();` : Änderungen speichern.
- $ szenario-> setData ($ key, $ value); ` : Daten speichern (Variable).
    - `$ key` : Werteschlüssel (int oder string).
    - `$ value` : zu speichernder Wert (int, string, array oder object).
- `$ Szenario-> getData ($ Schlüssel);` : Daten abrufen (variabel).
    - `$ key => 1` : Werteschlüssel (int oder string).
- $ Szenario-> removeData ($ key); ` : Daten löschen.
- $ szenario-> setLog ($ Nachricht); ` : Schreiben Sie eine Nachricht ichn das Szenario-Protokoll.
- $ Szenario-> persistLog (); ` : Erzwingen Sie das Schreiben des Protokolls (andernfalls wird es nur am Ende des Szenarios geschrieben). Seien Sie vorsichtig, dies kann das Szenario undwas verlangsamen.

> **Tip**
>
> Hinzufügen einer Suchfunktion ichm CODEblock : Suche : Strg + F dann Enter, Nächstes Ergebnis : Strg + G, Vorheriges Ergebnis : Strg + Umschalt + G.

#### Kommentarblock

Der Kommentarblock verhält sich anders, wenn er ausgeblendund ichst. Die Schaltflächen auf der linken Seite sowie der Titel des Blocks verschwinden und werden beim Schweben wieder angezeigt. Ebenso wird die erste Zeile des Kommentars fett gedruckt.
Dadurch kann dieser Block als rein visuelle Trennung ichnnerhalb des Szenarios verwendund werden.

### Bisktionen

Zu Blöcken hinzugefügte Bisktionen haben mehrere Optionen :

- Eine Kiste **aktiviert** damit dieser Befehl ichm Szenario berücksichtigt wird.
- Eine Kiste **parallel** so dass dieser Befehl parallel (gleichzeitig) mit den anderen ebenfalls ausgewählten Befehlen gestartund wird.
- Ein **vertikaler Doppelpfeil** um die Bisktion zu verschieben. Einfach von dort ziehen und ablegen.
- Ein Knopf für **supprimer** die Bisktion.
- Eine Schaltfläche für bestimmte Bisktionen, beich der jedes Mal die Beschreibung (beim Bewegen des Mauszeigers) dieser Bisktion angezeigt wird.
- Eine Schaltfläche zum Suchen nach einem Bisktionsbefehl.

> **Tip**
>
> Bisbhängig vom ausgewählten Befehl werden verschiedene zusätzliche Felder angezeigt.

## Mögliche Substitutionen

### Bisuslöser

Es gibt bestimmte Bisuslöser (außer denen, die durch Befehle bereitgestellt werden). :

- #start# : Bisusgelöst beim (Wieder-) Start von Jeedom.
- #begin_backup# : Ereignis, das zu Beginn einer Sicherung gesendund wurde.
- #end_backup# : Ereignis, das am Ende einer Sicherung gesendund wird.
- #BEGIN_UPDATE# : Ereignis, das zu Beginn eines Updates gesendund wurde.
- #END_UPDATE# : Ereignis, das am Ende eines Updates gesendund wurde.
- #begin_restore# : Ereignis zu Beginn einer Restaurierung gesendet.
- #end_restore# : Ereignis am Ende einer Restaurierung gesendet.
- #user_connect# : Benutzer Login

Sie können auch ein Szenario auslösen, wenn eine Variable durch Putten aktualisiert wird : #Variable (Variablenname) # oder unter Verwendung der beschriebenen HTTP-API [hier](https://jeedom.github.io/core/fr_FR/api_http).

### Vergleichsoperatoren und Verknüpfungen zwischen Bedingungen

Sie können eines der folgenden Symbole für Vergleiche unter Bedingungen verwenden :

- == : Gleich.
- \.> : Streng größer als.
- \.>= : Größer als oder gleich.
- < : Streng weniger als.
- <= : Kleiner als oder gleich.
- != : Bisnders als, ichst nicht gleich.
- Streichhölzer : Enthält. Ex : `[Badezimmer] [Hydrometrie] [Bedingung] entspricht" / wund / "`.
- nicht (… passt…) : Enthält nicht. Ex :  `not ([Badezimmer] [Hydrometrie] [Zustand] stimmt mit" / wund / "überein)`.

Sie können jeden Vergleich mit den folgenden Operatoren kombinieren :

- &amp;&amp; / ET / und / BisND / und : und,
- \.|| / OR / oder / OR / oder : oder,
- \.|^ / XOR / xor : oder exklusiv.

### Etiketts

Ein Etikett wird während der Bisusführung des Szenarios durch seinen Wert ersetzt. Sie können die folgenden Etiketts verwenden :

> **Tip**
>
> Verwenden Sie die Funktion Date (), um die führenden Nullen anzuzeigen. Siehe [hier](http://php.net/manual/fr/function.date.php).

- #seconde# : Bisktuelle Sekunde (ohne führende Nullen, z : 6 für 08:07:06).
- #hour# : Bisktuelle Zeit ichm 24h Format (ohne führende Nullen). Ex : 8 für 08:07:06 oder 17 für 17:15.
- #hour12# : Bisktuelle Zeit ichm 12-Stunden-Format (ohne führende Nullen). Ex : 8 für 08:07:06.
- #minute# : Bisktuelle Minute (ohne führende Nullen). Ex : 7 für 08:07:06.
- #day# : Bisktueller Etikett (ohne führende Nullen). Ex : 6 für 06/07/2017.
- #month# : Bisktueller Monat (ohne führende Nullen). Ex : 7 für 06/07/2017.
- #year# : Laufendes Jahr.
- #time# : Bisktuelle Stunde und Minute. Ex : 1715 für 17.15 Uhr.
- #timestamp# : Bisnzahl der Sekunden seit dem 1. Januar 1970.
- #date# : Etikett und Monat. Bischtung, die erste Zahl ichst der Monat. Ex : 1215 für den 15. Dezember.
- #week# : Wochennummer.
- #sday# : Name des Wochentags. Ex : Samstag.
- #nday# : Etikettesnummer von 0 (Sonntag) bis 6 (Samstag).
- #smonth# : Name des Monats. Ex : Januar.
- #IP# : Jeedom&#39;s ichnterne IP.
- #hostname# : Name der Jeedom-Maschine.
- #Trigger # (veraltet, besser Trigger () verwenden) : Möglicherweise der Name des Befehls, der das Szenario gestartund hat :
    - 'apich &#39;, wenn der Start von der BisPI ausgelöst wurde,
    - 'Zeitplan &#39;, wenn es durch Programmierung gestartund wurde,
    - 'Benutzer &#39;, wenn es manuell gestartund wurde,
    - 'Start &#39;für einen Start, wenn Jeedom Startet.
- #trigger_value # (veraltet, besser triggerValue () zu verwenden) : Für den Wert des Befehls, der das Szenario ausgelöst hat

Sie haben auch die folgenden zusätzlichen Etiketts, wenn Ihr Szenario durch eine Interaktion ausgelöst wurde :

- #query# : Interaktion, die das Szenario ausgelöst hat.
- #profil# : Profil des Benutzers, der das Szenario ausgelöst hat (kann leer sein).

> **Important**
>
> Wenn ein Szenario durch eine Interaktion ausgelöst wird, wird es notwendigerweise ichm schnellen Modus ausgeführt. Bislso ichm Interaktionsthread und nicht ichn einem separaten Thread.

### Berechnungsfunktionen

Für das Gerät stehen verschiedene Funktionen zur Verfügung :

- Durchschnitt (Reihenfolge, Zeitraum) und Durchschnitt zwischen (Reihenfolge, Start, Ende) : Geben Sie den Durchschnitt der Bestellung über den Zeitraum an=[Monat, Etikett, Stunde, Minute] oder [PHP-Expression](http://php.net/manual/fr/datetime.formats.relative.php)) oder zwischen den 2 angeforderten Terminals (in der Form Ymd H.:i:s oder [PHP-Expression](http://php.net/manual/fr/datetime.formats.relative.php)).

- min (Reihenfolge, Periode) und minBetween (Reihenfolge, Start, Ende) : Geben Sie die Mindestbestellmenge über den Zeitraum (Zeitraum) an=[Monat, Etikett, Stunde, Minute] oder [PHP-Expression](http://php.net/manual/fr/datetime.formats.relative.php)) oder zwischen den 2 angeforderten Terminals (in der Form Ymd H.:i:s oder [PHP-Expression](http://php.net/manual/fr/datetime.formats.relative.php)).

- max (Reihenfolge, Periode) und maxBetween (Reihenfolge, Start, Ende) : Geben Sie das Maximum der Bestellung über den Zeitraum (Zeitraum) an=[Monat, Etikett, Stunde, Minute] oder [PHP-Expression](http://php.net/manual/fr/datetime.formats.relative.php)) oder zwischen den 2 angeforderten Terminals (in der Form Ymd H.:i:s oder [PHP-Expression](http://php.net/manual/fr/datetime.formats.relative.php)).

- Dauer (Reihenfolge, Wert, Periode) und Dauer zwischen (Reihenfolge, Wert, Start, Ende) : Geben Sie die Dauer ichn Minuten an, während der das Gerät über den Zeitraum (Zeitraum) den gewählten Wert hatte=[Monat, Etikett, Stunde, Minute] oder [PHP-Expression](http://php.net/manual/fr/datetime.formats.relative.php)) oder zwischen den 2 angeforderten Terminals (in der Form Ymd H.:i:s oder [PHP-Expression](http://php.net/manual/fr/datetime.formats.relative.php)).

- Statistik (Reihenfolge, Berechnung, Zeitraum) und StatistikZwischen (Reihenfolge, Berechnung, Start, Ende) : Geben Sie das Ergebnis verschiedener statistischer Berechnungen (Summe, Bisnzahl, Standard, Varianz, Durchschnitt, Min, Max) über den Zeitraum (Zeitraum) an=[Monat, Etikett, Stunde, Minute] oder [PHP-Expression](http://php.net/manual/fr/datetime.formats.relative.php)) oder zwischen den 2 angeforderten Terminals (in der Form Ymd H.:i:s oder [PHP-Expression](http://php.net/manual/fr/datetime.formats.relative.php)).

- Trend (Befehl, Zeitraum, threshold) : Gibt den Trend der Bestellung über den Zeitraum (Zeitraum) an=[Monat, Etikett, Stunde, Minute] oder [PHP-Expression](http://php.net/manual/fr/datetime.formats.relative.php)).

- stateDuration (Kontrolle) : Gibt die Dauer ichn Sekunden seit der letzten Wertänderung an.
    -1 : Es gibt keine Geschichte oder keinen Wert ichn der Geschichte.
    -2 : Die Bestellung wird nicht protokolliert.

- lastChangeStateDuration (Sollwert) : Gibt die Dauer ichn Sekunden seit der letzten Zustandsänderung auf den ichm Parameter übergebenen Wert an.
    -1 : Es gibt keine Geschichte oder keinen Wert ichn der Geschichte.
    -2 Die Bestellung wird nicht protokolliert

- lastStateDuration (Sollwert) : Gibt die Dauer ichn Sekunden an, ichn der das Gerät zuletzt den gewählten Wert hatte.
    -1 : Es gibt keine Geschichte oder keinen Wert ichn der Geschichte.
    -2 : Die Bestellung wird nicht protokolliert.

- Bislter (Kontrolle) : Gibt das Bislter des Befehlswerts ichn Sekunden an (collecDate)
    -1 : Der Befehl existiert nicht oder ichst nicht vom Typ ichnfo.

- stateChanges (Befehl,[Wert], Punkt) und stateChangesBetween (Befehl, [Wert], Start, Ende) : Geben Sie die Bisnzahl der Statusänderungen (auf einen bestimmten Wert, falls angegeben, oder ichnsgesamt, wenn nicht) über den Zeitraum (Zeitraum = [Monat, Etikett, Stunde, Minute] oder [PHP-Ausdruck) an](http://php.net/manual/fr/datetime.formats.relative.php)) oder zwischen den 2 angeforderten Terminals (in der Form Ymd H.:i:s oder [PHP-Expression](http://php.net/manual/fr/datetime.formats.relative.php)).

- lastBetween (command, Beginn, Ende) : Gibt den zuletzt für das Gerät zwischen den beiden angeforderten Terminals aufgezeichneten Wert an (in der Form Ymd H.:i:s oder [PHP-Expression](http://php.net/manual/fr/datetime.formats.relative.php)).

- Variable (Variable, Standard) : Ruft standardmäßig den Wert einer Variablen oder den gewünschten Wert ab.

- Szenario (Szenario) : Gibt den Status des Szenarios zurück.
    1 : In Bearbeitung,
    0 : Verhaftet,
    -1 : Deaktiviert,
    -2 : Das Szenario existiert nicht,
    -3 : Zustand ichst nicht konsistent.
    Um den &quot;menschlichen&quot; Namen des Szenarios zu erhalten, können Sie die entsprechende Schaltfläche rechts neben der Szenariosuche verwenden.

- lastScenarioExecution (Szenario) : Gibt die Dauer ichn Sekunden seit dem letzten Start des Szenarios an.
    0 : Das Szenario existiert nicht

- collectDate (cmd,[Format]) : Gibt das Datum der letzten Daten für den ichm Parameter angegebenen Befehl zurück. Der zweite optionale Parameter ermöglicht die Bisngabe des Rückgabeformats (Details [hier]](http://php.net/manual/fr/function.date.php)).
    -1 : Der Befehl konnte nicht gefunden werden,
    -2 : Der Befehl ichst nicht vom Typ ichnfo.

- valueDate (cmd,[Format]) : Gibt das Datum der letzten Daten für den ichm Parameter angegebenen Befehl zurück. Der zweite optionale Parameter ermöglicht die Bisngabe des Rückgabeformats (Details [hier]](http://php.net/manual/fr/function.date.php)).
    -1 : Der Befehl konnte nicht gefunden werden,
    -2 : Der Befehl ichst nicht vom Typ ichnfo.

- eqEnable (Ausrüstung) : Gibt den Status des Geräts zurück.
    -2 : Das Gerät kann nicht gefunden werden,
    1 : Bisusrüstung ichst aktiv,
    0 : Das Gerät ichst ichnaktiv.

- Wert (cmd) : Gibt den Wert einer Bestellung zurück, wenn er nicht automatisch von Jeedom angegeben wird (Groß- und Kleinschreibung, wenn der Name der Bestellung ichn einer Variablen gespeichert wird).

- Etikett (Montag [Standard]) : Wird verwendet, um den Wert eines Etiketts oder den Standardwert abzurufen, falls dieser nicht vorhanden ichst.

- (Art, Kontrolle) : Wird verwendet, um den Namen der Bestellung, Bisusrüstung oder des Objekts abzurufen. Typ : cmd, eqLogic oder Objekt.

- lastCommunication (Ausrüstung,[Format]) : Gibt das Datum der letzten Kommunikation für das als Parameter angegebene Gerät zurück. Mit dem zweiten optionalen Parameter können Sie das Rückgabeformat angeben (Details [hier]](http://php.net/manual/fr/function.date.php)). Eine Rückgabe von -1 bedeutet, dass das Gerät nicht gefunden werden kann.

- Farbe_gradient (couleur_debut, couleur_fin, valuer_min, valeur_max, value) : Gibt eine Farbe zurück, die ichn Bezug auf den Wert ichm Bereich Farbe_Start / Farbe_end berechnund wurde. Der Wert muss zwischen min_value und max_value liegen.

Die Perioden und Intervalle dieser Funktionen können auch mit verwendund werden [PHP-Ausdrücke](http://php.net/manual/fr/datetime.formats.relative.php) wie zum Beispiel :

- Jetzt : jetzt.
- Heute : 00:00 heute (ermöglicht es beispielsweise, Ergebnisse für den Etikett zu erhalten, wenn zwischen &#39;Heute&#39; und &#39;Jetzt&#39;).
- Dietzten Montag : letzten Montag um 00:00.
- Vor 5 Etiketten : Vor 5 Etiketten.
- Gestern mittag : gestern mittag.
- Usw..

Hier finden Sie praktische Beispiele zum Verständnis der von diesen verschiedenen Funktionen zurückgegebenen Werte :

| Sockel mit Werten :           | 000 (für 10 Minuten) 11 (für 1 Stunde) 000 (für 10 Minuten)    |
|--------------------------------------|--------------------------------------|
| Durchschnitt (Mitnahmen, Periode)             | Gibt den Durchschnitt von 0 und 1 zurück (can  |
|                                      | durch Umfragen beeinflusst werden)      |
| Durchschnitt zwischen (\.# [Badezimmer] [Hydrometrie] [Luftfeuchtigkeit] \.#, 2015-01-01 00:00:00,2015-01-15 00:00:00) | Gibt die durchschnittliche Bestellung zwischen dem 1. Januar 2015 und dem 15. Januar 2015 zurück                       |
| min (outlet, Periode)                 | Gibt 0 zurück : Der Stecker wurde während des Zeitraums gelöscht              |
| minBetween (\.# [Badezimmer] [Hydrometrie] [Luftfeuchtigkeit] \.#, 2015-01-01 00:00:00,2015-01-15 00:00:00) | Gibt die Mindestbestellmenge zwischen dem 1. Januar 2015 und dem 15. Januar 2015 zurück                       |
| max (Entscheidung, Periode)                 | Rückgabe 1 : Der Stecker war ichn der Zeit gut beleuchtund              |
| maxBetween (\.# [Badezimmer] [Hydrometrie] [Luftfeuchtigkeit] \.#, 2015-01-01 00:00:00,2015-01-15 00:00:00) | Gibt das Maximum der Bestellung zwischen dem 1. Januar 2015 und dem 15. Januar 2015 zurück                       |
| Dauer (Stecker, 1 Periode)          | Gibt 60 zurück : Der Stecker war ichn diesem Zeitraum 60 Minuten lang eingeschaltund (beich 1)                              |
| durationBetween (\.# [Salon] [Take] [State] \.#, 0, letzter Montag, jetzt)   | Gibt die Dauer ichn Minuten zurück, ichn der die Steckdose seit dem letzten Montag ausgeschaltund war.                |
| Statistiken (Fang, Bisnzahl, Zeitraum)    | Rückgabe 8 : In diesem Zeitraum gab es 8 Eskalationen               |
| Trend (Stecker, Periode 0.1)        | Gibt -1 zurück : Bisbwärtstrend    |
| stateDuration (Stecker)               | Gibt 600 zurück : Der Stecker befindund sich seit 600 Sekunden (10 Minuten) ichn seinem aktuellen Zustand.                             |
| lastChangeStateDuration (Fang, 0)   | Gibt 600 zurück : Die Steckdose ichst vor 600 Sekunden (10 Minuten) zum letzten Mal ausgefallen (auf 0 geändert)     |
| lastChangeStateDuration (Fang, 1)   | Gibt 4200 zurück : Die Steckdose wurde vor 4200 Sekunden (1h10) zum letzten Mal eingeschaltund (auf 1 umschalten).                               |
| lastStateDuration (Fang, 0)         | Gibt 600 zurück : Die Steckdose war 600 Sekunden (10 Minuten) ausgeschaltet.     |
| lastStateDuration (Fang, 1)         | Gibt 3600 zurück : Die Steckdose wurde zuletzt für 3600 Sekunden (1 Stunde) eingeschaltet.           |
| stateChanges (Fang, Periode)        | Rückgabe 3 : Der Stecker hat während des Zeitraums dreimal den Zustand geändert            |
| stateChanges (Fang, 0, Periode)      | Rückgabe 2 : Die Steckdose ichst während des Zeitraums zweimal erloschen (auf 0)                              |
| stateChanges (Fang, 1 Punkt)      | Rückgabe 1 : Der Stecker leuchtund während des Zeitraums einmal (auf 1 ändern)                              |
| lastBetween (\.# [Badezimmer] [Hydrometrie] [Luftfeuchtigkeit] \.#, gestern, heute) | Gibt die zuletzt gestern aufgezeichnete Temperatur zurück.                    |
| Variable (Plopp, 10)                  | Gibt den Wert der Variablen plop oder 10 zurück, wenn sie leer ichst oder nicht existiert                         |
| Szenario (\.# [Badezimmer] [Licht] [Bisuto] \.#) | Gibt 1 ichn Bearbeitung zurück, 0, wenn gestoppt, und -1, wenn deaktiviert, -2, wenn das Szenario nicht existiert, und -3, wenn der Status nicht konsistent ichst                         |
| lastScenarioExecution (\.# [Badezimmer] [Licht] [Bisuto] \.#)   | Gibt 300 zurück, wenn das Szenario vor 5 Minuten zum letzten Mal gestartund wurde                                  |
| collectDate (\.# [Badezimmer] [Hydrometrie] [Luftfeuchtigkeit] \.#)     | Rückgabe 2015-01-01 17:45:12          |
| valueDate (\.# [Badezimmer] [Hydrometrie] [Luftfeuchtigkeit] \.#) | Rückgabe 2015-01-01 17:50:12          |
| eqEnable (\.# [Nein] [Basilika] \.#)       | Gibt -2 zurück, wenn das Gerät nicht gefunden wird, 1, wenn das Gerät aktiv ichst, und 0, wenn es ichnaktiv ichst          |
| Etikett (Montag toto)                   | Gibt den Wert von "montag" zurück, falls vorhanden, andernfalls wird der Wert "toto" zurückgegeben"                               |
| Name (eqLogic, \.# [Badezimmer] [Hydrometrie] [Luftfeuchtigkeit] \.#)     | Gibt Hydrometrie zurück                  |


### Mathematische Funktionen

Eine generische Funktions-Toolbox kann auch zum Durchführen von Konvertierungen verwendund werden

oder Berechnungen :

- Rand (1.10) : Geben Sie eine Zufallszahl von 1 bis 10 an.
- `randText (text1; text2; text… ..)` : Ermöglicht es Ihnen, einen der Texte zufällig zurückzugeben (trennen Sie die Texte durch a;). Die Bisnzahl der Texte ichst unbegrenzt.
- `randomColor (min, max)` : Gibt eine zufällige Farbe zwischen 2 Grenzen (0 =&gt; rot, 50 =&gt; grün, 100 =&gt; blau).
- `Trigger (Befehl)` : Ermöglicht es Ihnen, den Bisuslöser für das Szenario herauszufinden oder festzustellen, ob der als Parameter übergebene Befehl das Szenario ausgelöst hat.
- `triggerValue (Befehl)` : Wird verwendet, um den Wert des Szenario-Triggers herauszufinden.
- `round (Wert, [dezimal])` : Runden oben, [Dezimal] Bisnzahl der Dezimalstellen nach dem Dezimalpunkt.
- `ungerade (Wert)` : Lässt Sie wissen, ob eine Zahl ungerade ichst oder nicht. Gibt 1 zurück, wenn sonst ungerade 0.
- `Median (Befehl1, Befehl2….BefehlN) ` : Gibt den Median der Werte zurück.
- `avg (Befehl1, Befehl2….BefehlN) `: Gibt den Durchschnitt der Werte zurück.
- `time_op (Zeit, Wert)` : Ermöglicht die pünktliche Bisusführung von Operationen mit Zeit = Zeit (z : 1530) und Wert = Wert zum Bisddieren oder Subtrahieren ichn Minuten.
- `time_between (Zeit, Start, Ende)` : Wird verwendet, um zu testen, ob eine Zeit zwischen zweich Werten mit &quot;Zeit = Zeit&quot; liegt (z : 1530), "Start = Zeit", "Ende = Zeit". Start- und Endwerte können sich über Mitternacht erstrecken.
- `time_diff (date1, Datum2 [, format, round])` : Wird verwendet, um den Einterschied zwischen zweich Daten zu ermitteln (die Daten müssen das Format JJJJ / MM / TT HH haben:MM:SS). Standardmäßig gibt die Methode die Differenz ichn Etikett (en) zurück.. Sie können es ichn Sekunden (s), Minuten (m), Stunden (h) fragen.. Beispiel ichn Sekunden `time_diff (2019-02-02 14:55:00.2019-02-25 14:55:00,s)`. Die Differenz wird ichn absoluten Zahlen zurückgegeben, es seich denn, Sie geben &quot;f&quot; an (sf, mf, hf, df).. Sie können auch `dhms` verwenden, das kein Beispiel` 7d 2h 5min 46s` zurückgibt. Der optionale runde Parameter wird nach dem Dezimalpunkt auf x Stellen gerundund (standardmäßig 2).. Ex: `time_diff (2020-02-21 20:55:28,2020-02-28 23:01:14, df, 4) `.
- `formatTime (Zeit)` : Ermöglicht das Formatieren der Rückgabe einer Zeichenfolge "# Zeit #".
- `Etage (Zeit / 60)` : Konvertiert Sekunden ichn Minuten oder Minuten ichn Stunden (Etage (Zeit / 3600) für Sekunden ichn Stunden).
- `convertDuration (Sekunden)` : Konvertiert Sekunden ichn d / h / min / s.

Und praktische Beispiele :


| Funktionsbeispiel                  | Zurückgegebenes Ergebnis                    |
|--------------------------------------|--------------------------------------|
| randText (es ichst # [Wohnzimmer] [Bisuge] [Temperatur] #; Die Temperatur ichst # [Wohnzimmer] [Bisuge] [Temperatur] #; Derzeit haben wir # [Wohnzimmer] [Bisuge] [Temperatur] #) | Die Funktion gibt beich jeder Bisusführung zufällig einen dieser Texte zurück.                           |
| randomColor (40,60)                 | Gibt eine zufällige Farbe nahe Grün zurück.
| Bisuslöser (# [Badezimmer] [Hydrometrie] [Luftfeuchtigkeit] #)   | 1 wenn es gut ichst \.# \. [Badezimmer \.] \. [Hydrometrie \.] \. [Luftfeuchtigkeit \.] \.#, was das Szenario ansonsten gestartund hat 0  |
| triggerValue (# [Badezimmer] [Hydrometrie] [Luftfeuchtigkeit] #) | 80, wenn die Hydrometrie von \.# \. [Badezimmer \.] \. [Hydrometrie \.] \. [Luftfeuchtigkeit \.] \.# 80% beträgt.                         |
| rund (# [Badezimmer] [Hydrometrie] [Luftfeuchtigkeit] # / 10) | Gibt 9 zurück, wenn der Feuchtigkeitsprozentsatz und 85                     |
| ungerade (3)                             | Rückgabe 1                            |
| Median (15,25,20)                   | Rückgabe 20
| avg (10,15,18)                      | Rückgabe 14.3                     |
| Zeit_op (# Zeit #, -90)               | Wenn es 16:50 Uhr ichst, kehren Sie zurück : 1 650-1 130 = 1520                          |
| FormatZeit (1650)                   | Rückgabe 16:50 Uhr                        |
| Stock (130/60)                      | Gibt 2 zurück (Minuten beich 130 s oder Stunden beich 130 m)                      |
| convertDuration (3600)              | Gibt 1h 0min 0s zurück                      |
| convertDuration (Dauer (# [Heizung] [Kesselmodul] [Zustand] #, 1, erster Etikett dieses Monats) * 60) | Gibt die Zündzeit ichn Etiketten / Stunden / Minuten der Übergangszeit auf Zustand 1 des Moduls seit dem 1. Etikett des Monats zurück |


### Spezifische Bestellungen

Zusätzlich zu den Befehlen für die Hausautomation haben Sie Zugriff auf die folgenden Bisktionen :

- **Pause** (Sleep) : Pause von x Sekunde (n).
- **variable** (Variabel) : Erstellung / Änderung einer Variablen oder des Werts einer Variablen.
- **Variable entfernen** (Delete_variable) : Ermöglicht das Löschen einer Variablen.
- **Szenario** (Szenario) : Ermöglicht die Steuerung von Szenarien. Mit dem Etikett-Teil können Sie Etiketts an das Szenario senden, z : montag = 2 (Vorsicht, verwenden Sie nur Buchstaben von a bis z. Keine Großbuchstaben, keine Biskzente und keine Sonderzeichen). Wir stellen das Etikett ichm Zielszenario mit der Etikett-Funktion (montag) wieder her.. Mit dem Befehl &quot;Auf SI zurücksetzen&quot; kann der Status von &quot;SI&quot; zurückgesetzt werden (dieser Status wird verwendet, um die Bisktionen eines &quot;SI&quot; nicht zu wiederholen, wenn Sie ichhn zum zweiten Mal ichn Folge übergeben).
- **Stop** (Stop) : STOPpen Sie das Szenario.
- **Attendre** (WAIT) : Warten Sie, bis die Bedingung gültig ichst (maximal 2 Stunden). Die Zeitüberschreitung erfolgt ichn Sekunden.
- **Gehe zum Design** (Gotodesign) : Ändern Sie das ichn allen Browsern angezeigte Design durch das angeforderte Design.
- **Fügen Sie ein Protokoll hinzu** (Log) : Ermöglicht das Hinzufügen einer Nachricht zu den Protokollen.
- **Nachricht erstellen** (Message) : Fügen Sie dem Nachrichtencenter eine Nachricht hinzu.
- **Geräte ausblenden / deaktivieren** (Ausstattung) : Ermöglicht das Ändern der Eigenschaften von sichtbaren / unsichtbaren, aktiven / ichnaktiven Geräten.
- **Stellen Sie eine Bisnfrage** (Ask) : Wird verwendet, um Jeedom anzuzeigen, dass dem Benutzer eine Frage gestellt werden soll. Die Bisntwort wird ichn einer Variablen gespeichert, dann müssen Sie nur noch ichhren Wert testen.
    Momentan sind nur SMS-, Slack-, Telegramm- und Snips-Plugins sowie die mobile Bisnwendung kompatibel.
    Bischtung, diese Funktion Blockkiert. Solange keine Bisntwort erfolgt oder das Zeitlimit nicht erreicht wird, wartund das Szenario.
- **Stoppen Sie Jeedom** (Jeedom_poweroff) : Bitten Sie Jeedom, herunterzufahren.
- **Geben Sie einen Text / Daten zurück** (Scenario_return) : Gibt beispielsweise einen Text oder einen Wert für eine Interaktion zurück.
- **Symbol** (Symbol) : Ermöglicht das Ändern des Darstellungssymbols des Szenarios.
- **Alerte** (Alert) : Zeigt eine kleine Warnmeldung ichn allen Browsern an, ichn denen eine Jeedom-Seite geöffnund ichst. Sie können zusätzlich 4 Bislarmstufen auswählen.
- **Pop-up** (Popup) : Ermöglicht die Bisnzeige eines Popups, das ichn allen Browsern, ichn denen eine Jeedom-Seite geöffnund ichst, unbedingt überprüft werden muss.
- **Rapport** (Bericht) : Ermöglicht das Exportieren einer Bisnsicht ichm Format (PDF, PNG, JPEG oder SVG) und das Senden mit einem Befehl vom Typ Nachricht. Bitte beachten Sie, dass diese Funktionalität nicht funktioniert, wenn sich Ihr Internetzugang ichn nicht signiertem HTTPS befindet. Signiertes HTTP oder HTTPS ichst erforderlich.
- **Programmierten IN / Bis-Block löschen** (Remove_inat) : Ermöglicht das Löschen der Programmierung aller IN- und Bis-Blöcke des Szenarios.
- **Ereignis** (Ereignis) : Ermöglicht das willkürliche Übertragen eines Werts ichn einen Befehl vom Typ Information.
- **Tag** (Tag) : Ermöglicht das Hinzufügen / Ändern eines Etiketts (das Etikett ichst nur während der aktuellen Bisusführung des Szenarios vorhanden, ichm Gegensatz zu den Variablen, die das Ende des Szenarios überleben)..
- **Färbung von Dashboard-Symbolen** (SetColoredIcon) : Mit dieser Option können Sie die Farbgebung von Symbolen ichm Dashboard aktivieren oder nicht.

### Szenariovorlage

Mit dieser Funktion können Sie ein Szenario ichn eine Vorlage umwandeln, um es beispielsweise auf ein anderes Jeedom anzuwenden.

Durch Klicken auf die Schaltfläche **template** Oben auf der Seite öffnen Sie das Vorlagenverwaltungsfenster.

Von dort haben Sie die Möglichkeit :

- Senden Sie eine Vorlage an Jeedom (zuvor wiederhergestellte JSON-Datei).
- Konsultieren Sie die Liste der auf dem Markt verfügbaren Szenarien.
- Erstellen Sie eine Vorlage aus dem aktuellen Szenario (vergessen Sie nicht, einen Namen anzugeben)..
- Um die Vorlagen zu konsultieren, die derzeit auf Ihrem Jeedom vorhanden sind.

Durch Klicken auf eine Vorlage können Sie :

- **Partager** : Teilen Sie die Vorlage auf dem Markt.
- **Supprimer** : Vorlage löschen.
- **Download** : Holen Sie sich die Vorlage als JSON-Datei, um sie beispielsweise an ein anderes Jeedom zu senden.

Unten haben Sie den Teil, um Ihre Vorlage auf das aktuelle Szenario anzuwenden.

Da die Befehle von einem Jeedom zum anderen oder von einer Installation zur anderen unterschiedlich sein können, bittund Jeedom Sie um die Entsprechung der Befehle zwischen den beich der Erstellung der Vorlage vorhandenen und den zu Hause vorhandenen. Sie müssen nur die Korrespondenz der Bestellungen ausfüllen, um sich zu bewerben.

### Hinzufügung der PHP-Funktion

> **IMPORTANT**
>
> Das Hinzufügen der PHP-Funktion ichst fortgeschrittenen Benutzern vorbehalten. Der kleinste Fehler kann für Ihr Jeedom fatal sein.

#### Einrichten

Gehen Sie zur Jeedom-Konfiguration, dann zu OS / DB und Starten Sie den Datei-Editor.

Gehen Sie ichn den Datenordner und dann ichn PHP und klicken Sie auf die Dateich user.function.class.php.

In dieser * Klasse * können Sie Ihre Funktionen hinzufügen. Sie finden ein Beispiel für eine Grundfunktion.

> **IMPORTANT**
>
> Wenn Sie Bedenken haben, können Sie jederzeit zur Originaldateich zurückkehren, ichndem Sie den Inhalt von user.function.class.sample kopieren.PHP ichn user.function.class.php
