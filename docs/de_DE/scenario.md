# Szenarien
**Werkzeuge → Szenarien**

<small>[Raccourcis clavier/souris](shortcuts.md)</small>

Die Szenarien sind ein echtes Gehirn der Hausautomation und ermöglichen eine intelligente Interaktion mit der realen Welt **.

## Gestion

Dort finden Sie die Liste der Szenarien Ihres Jeedom sowie Funktionen, um diese am besten zu verwalten :

- **Ajouter** : Erstellen Sie ein Szenario. Die Vorgehensweise wird im nächsten Kapitel beschrieben.
- **Szenarien deaktivieren** : Deaktiviert alle Szenarien. Selten verwendund und wissentlich, da kein Szenario mehr ausgeführt wird.
- **Übersicht** : Ermöglicht einen Überblick über alle Szenarien. Sie können die Werte ändern **actif**, **visible**, **Multi-Launch**, **synchroner Modus**, **Log** und **Timeline** (Diese Parameter werden im nächsten Kapitel beschrieben.). Sie können auch auf die Protokolle für jedes Szenario zugreifen und sie einzeln starten.

## Meine Szenarien

In diesem Abschnitt finden Sie die **Liste der Szenarien** dass du erstellt hast. Sie werden nach ihren klassifiziert **groupe**, möglicherweise für jeden von ihnen definiert. Jedes Szenario wird mit seinem angezeigt **nom** und seine **übergeordnetes Objekt**. Die **ausgegraute Szenarien** sind diejenigen, die deaktiviert sind.

> **Tip**
>
> Sie können ein Szenario öffnen, indem Sie dies tun :
> - Klicken Sie auf eine davon.
> - Strg Clic oder Clic Center, um es in einem neuen Browser-Tab zu öffnen.

Sie haben eine Suchmaschine, um die Anzeige von Szenarien zu filtern. Die Escape-Taste bricht die Suche ab.
Rechts neben dem Suchfeld befinden sich dreich Schaltflächen, die an mehreren Stellen in Jeedom gefunden wurden:
- Das Kreuz, um die Suche abzubrechen.
- Der geöffnete Ordner zum Entfalten aller Bedienfelder und Anzeigen aller Szenarien.
- Der geschlossene Ordner zum Falten aller Panels.

Sobald Sie ein Szenario konfiguriert haben, haben Sie ein Kontextmenü mit der rechten Maustaste auf die Registerkarten des Szenarios. Sie können auch ein Strg-Klick- oder Klick-Center verwenden, um ein anderes Szenario direkt in einer neuen Browser-Registerkarte zu öffnen.

## Schaffung | Szenario bearbeiten

Nach dem Klicken auf **Ajouter**, Sie müssen den Namen Ihres Szenarios auswählen. Sie werden dann zur Seite mit den allgemeinen Parametern weitergeleitet.
Davor gibt es oben auf der Seite einige nützliche Funktionen zum Verwalten dieses Szenarios :

- **ID** : Neben dem Wort **General**, Dies ist die Szenariokennung.
- **statut** : *Angehalten * oder * In Bearbeitung * zeigt den aktuellen Status des Szenarios an.
- **Block hinzufügen** : Ermöglicht das Hinzufügen eines Blocks des gewünschten Typs zum Szenario (siehe unten)..
- **Log** : Zeigt die Szenarioprotokolle an.
- **Dupliquer** : Kopieren Sie das Szenario, um ein neues mit einem anderen Namen zu erstellen.
- **Liens** : Ermöglicht das Anzeigen des Diagramms der Elemente, die sich auf das Szenario beziehen.
- **Textbearbeitung** : Zeigt ein Fenster an, in dem das Szenario in Form von Text / JSON bearbeitund werden kann. Vergiss nicht zu sparen.
- **Exporter** : Ermöglicht es Ihnen, eine reine Textversion des Szenarios zu erhalten.
- **Template** : Ermöglicht den Zugriff auf die Vorlagen und die Anwendung einer Vorlage auf das Szenario aus dem Markt. (am Ende der Seite erklärt).
- **Recherche** : Entfaltund ein Suchfeld für die Suche im Szenario. Diese Suche entfaltund die reduzierten Blöcke beich Bedarf und faltund sie nach der Suche zurück.
- **Ausführen** : Ermöglicht das manuelle Starten des Szenarios (unabhängig von den Auslösern). Speichern Sie vorher, um die Änderungen zu berücksichtigen.
- **Supprimer** : Szenario löschen.
- **Sauvegarder** : Speichern Sie die vorgenommenen Änderungen.

> **Tips**
>
> Zweich Tools sind für Sie auch beim Einrichten von Szenarien von unschätzbarem Wert :
    > - Die Variablen, sichtbar in **Werkzeuge → Variablen**
    > - Der Ausdruckstester, auf den über zugegriffen werden kann **Werkzeuge → Ausdruckstester**
>
> Ein **Strg Klicken Sie auf die Schaltfläche Ausführen** Mit dieser Option können Sie das Szenarioprotokoll direkt speichern, ausführen und anzeigen (wenn die Protokollebene nicht Keine ist)..

### Registerkarte &quot;Allgemein&quot;

In der Registerkarte **General**, Wir finden die Hauptparameter des Szenarios :

- **Name des Szenarios** : Der Name Ihres Szenarios.
- **Name, der angezeigt werden soll** : Der Name, der für die Anzeige verwendund wird. Wenn nicht abgeschlossen, wird optional der Name des Szenarios verwendet.
- **Groupe** : Ermöglicht das Organisieren der Szenarien und deren Klassifizierung in Gruppen (sichtbar auf der Szenarioseite und in ihren Kontextmenüs)..
- **Actif** : Aktivieren Sie das Szenario. Wenn nicht aktiv, wird es von Jeedom unabhängig vom Triggermodus nicht ausgeführt.
- **Visible** : Ermöglicht es Ihnen, das Szenario sichtbar zu machen (Dashboard).
- **übergeordnetes Objekt** : Zuordnung zu einem übergeordneten Objekt. Es wird dann entsprechend diesem Elternteil sichtbar sein oder nicht.
- **Zeitüberschreitung in Sekunden (0 = unbegrenzt)** : Die maximal zulässige Ausführungszeit für dieses Szenario. Nach dieser Zeit wird die Ausführung des Szenarios unterbrochen.
- **Multi-Launch** : Aktivieren Sie dieses Kontrollkästchen, wenn das Szenario mehrmals gleichzeitig gestartund werden soll.
- **Synchroner Modus** : Starten Sie das Szenario im aktuellen Thread anstelle eines dedizierten Threads. Erhöht die Geschwindigkeit, mit der das Szenario gestartund wird, kann jedoch das System instabil machen.
- **Log** : Der für das Szenario gewünschte Protokolltyp. Sie können das Protokoll des Szenarios ausschneiden oder im Gegenteil unter Analyse → Echtzeit anzeigen.
- **Timeline** : Verfolgen Sie das Szenario in der Zeitleiste (siehe Verlaufsdokument)..
- **Symbol** : Ermöglicht die Auswahl eines Symbols für das Szenario anstelle des Standardsymbols.
- **Description** : Ermöglicht das Schreiben eines kleinen Textes zur Beschreibung Ihres Szenarios.
- **Szenariomodus** : Das Szenario kann programmiert, ausgelöst oder beides sein. Sie haben dann die Wahl, die Auslöser (maximal 15 Auslöser) und die Programmierung (en) anzugeben..

> **Tip**
>
> Bedingungen können jetzt im ausgelösten Modus eingegeben werden. Zum Beispiel : `# [Garage] [Garage öffnen] [Eröffnung] # == 1`
> Aufmerksamkeit : Sie können maximal 28 Trigger / Programmierungen für ein Szenario haben.

> **Tippmodus programmiert**
>
> Der geplante Modus verwendund die Syntax **Cron**. Sie können beispielsweise alle 20 Minuten ein Szenario mit `* / 20 * * * *` ausführen oder um 5 Uhr morgens mit `0 5 * * *` eine Vielzahl von Dingen für den Etikett erledigen. Die ? Rechts neben einem Programm können Sie es einstellen, ohne Spezialist für Cron-Syntax zu sein.

### Registerkarte &quot;Szenario&quot;

Hier erstellen Sie Ihr Szenario. Nach dem Erstellen des Szenarios ist sein Inhalt leer, sodass er ... nichts tut. Du musst mit anfangen **Block hinzufügen**, mit dem Knopf rechts. Sobald ein Block erstellt wurde, können Sie einen weiteren hinzufügen **bloc** oder a **action**.

Um die Blöcke im Szenario einfacher zu gestalten und nicht ständig neu anordnen zu müssen, wird nach dem Feld, in dem sich der Mauszeiger befindet, ein Block hinzugefügt.
*Wenn Sie beispielsweise zehn Blöcke haben und auf die IF-Bedingung des ersten Blocks klicken, wird der hinzugefügte Block nach dem Block auf derselben Ebene hinzugefügt. Wenn kein Feld aktiv ist, wird es am Ende des Szenarios hinzugefügt.*

> **Tip**
>
> In Bedingungen und Aktionen ist es besser, einfache Anführungszeichen (&#39;) anstelle von doppelten (&quot;) zu bevorzugen..

> **Tip**
>
> Mit einer Strg-Umschalttaste Z oder einer Strg-Umschalttaste Y können Sie dies tun'**annuler** oder eine Änderung wiederholen (Hinzufügung von Aktion, Block ...).

### Blöcke

Hier sind die verschiedenen Arten von Blöcken verfügbar :

- **If / Then / Oder** : Ermöglicht das Ausführen von bedingten Aktionen (wenn dies, dann das).
- **Action** : Ermöglicht das Starten einfacher Aktionen ohne Bedingungen.
- **Boucle** : Ermöglicht die wiederholte Ausführung von Aktionen von 1 bis zu einer definierten Zahl (oder sogar dem Wert eines Sensors oder einer Zufallszahl usw.).
- **Dans** : Ermöglicht das Starten einer Aktion in X Minute (n) (0 ist ein möglicher Wert). Die Besonderheit ist, dass die Aktionen im Hintergrund gestartund werden, sodass sie den Rest des Szenarios nicht blockieren. Es ist also ein nicht blockierender Block.
- **A** : Ermöglicht es Jeedom, die Aktionen des Blocks zu einem bestimmten Zeitpunkt zu starten (in der Form hhmm).. Dieser Block ist nicht blockierend. Ex : 0030 für 00:30 oder 0146 für 1h46 und 1050 für 10h50.
- **Code** : Ermöglicht das direkte Schreiben in PHP-CODE (erfordert bestimmte Kenntnisse und kann riskant sein, ermöglicht Ihnen jedoch keine Einschränkungen).
- **Commentaire** : Ermöglicht das Hinzufügen von Kommentaren zu Ihrem Szenario.

Jeder Block hat seine Optionen, um besser damit umzugehen :

- Links :
    - Mit dem bidirektionalen Pfeil können Sie einen Block oder eine Aktion verschieben, um sie im Szenario neu anzuordnen.
    - Das Auge reduziert einen Block (* Kollaps *), um seine visuelle Wirkung zu verringern. Strg Klicken Sie auf das Auge, um sie zu verkleinern oder alle anzuzeigen.
    - Mit dem Kontrollkästchen können Sie den Block vollständig deaktivieren, ohne ihn zu löschen. Es wird daher nicht ausgeführt.

- Rechts :
    - Mit dem Symbol Kopieren können Sie den Block kopieren, um eine Kopie an einer anderen Stelle zu erstellen. Strg Klicken Sie auf das Symbol, um den Block zu schneiden (kopieren und löschen).
    - Mit dem Symbol Einfügen können Sie eine Kopie des Blocks einfügen, der zuvor nach dem Block kopiert wurde, für den Sie diese Funktion verwenden..  Strg Klicken Sie auf das Symbol, um den Block durch den kopierten Block zu ersetzen.
    - Das Symbol - ermöglicht es Ihnen, den Block mit einer Bestätigungsanforderung zu löschen. Strg Klicken löscht den Block ohne Bestätigung.

#### Wenn / Dann / Sonst blockiert | Schleife | In | A

Für die Bedingungen versucht Jeedom, es möglich zu machen, sie so viel wie möglich in natürlicher Sprache zu schreiben und dabeich flexibel zu bleiben.
> Verwenden Sie [] NICHT in Bedingungstests, nur Klammern () sind möglich.

Rechts neben diesem Blocktyp stehen dreich Schaltflächen zur Auswahl eines zu testenden Elements zur Verfügung :

- **Finden Sie eine Bestellung** : Ermöglicht die Suche nach einer Bestellung in allen in Jeedom verfügbaren. Sobald die Bestellung gefunden wurde, öffnund Jeedom ein Fenster, in dem Sie gefragt werden, welchen Test Sie daran durchführen möchten. Wenn Sie möchten **Setzen Sie nichts**, Jeedom wird die Bestellung ohne Vergleich hinzufügen. Sie können auch wählen **et** oder **ou** Front **Ensuite** Kettenprüfungen an verschiedenen Geräten.
- **Suchen Sie ein Szenario** : Ermöglicht die Suche nach einem zu testenden Szenario.
- **Suche nach Ausrüstung** : Gleiches gilt für die Ausrüstung.

> **Note**
>
> Beich Blöcken vom Typ If / Then / Andernfalls können kreisförmige Pfeile links neben dem Bedingungsfeld die Wiederholung von Aktionen aktivieren oder nicht, wenn die Auswertung der Bedingung das gleiche Ergebnis wie beich der vorherigen Auswertung ergibt.

> **Tip**
>
> Es gibt eine Liste von Tags, die den Zugriff auf Variablen aus dem einen oder anderen Szenario oder nach Uhrzeit, Datum, Zufallszahl usw. ermöglichen. Siehe unten die Kapitel zu Befehlen und Tags.

Sobald die Bedingung erfüllt ist, müssen Sie die Schaltfläche &quot;Hinzufügen&quot; auf der linken Seite verwenden, um eine neue hinzuzufügen **bloc** oder a **action** im aktuellen Block.


#### Blockcode

Mit dem Code-Block können Sie PHP-CODE ausführen. Es ist daher sehr mächtig, erfordert aber gute Kenntnisse der PHP-Sprache.

##### Zugang zu Steuerungen (Sensoren und Aktoren):
-  `cmd::byString ($ string); ` : Gibt das entsprechende Befehlsobjekt zurück.
    -   `$string`: Link zur gewünschten Bestellung : `# [Objekt] [Bisusrüstung] [Befehl] #` (z : `# [Wohnung] [Bislarm] [Bisktiv] #`)
-  `cmd::byId ($ id); ` : Gibt das entsprechende Befehlsobjekt zurück.
    -  `$ id` : Bestellnummer.
-  $ cmd-> execCmd ($ options = null); ` : Führen Sie den Befehl aus und geben Sie das Ergebnis zurück.
    - `$ options` : Optionen für die Auftragsausführung (möglicherweise Plugin-spezifisch). Grundlegende Optionen (Befehlssubtyp) :
        -  Nachricht : `$ option = array ('title' => 'Nachrichtentitel,' Nachricht '=>' Meine Nachricht ');`
        -  Farbe : `$ option = array ('color' => 'Farbe in hexadecimal');`
        -  Schieber : `$ option = array ('slider' => 'gewünschter Wert von 0 bis 100');`

##### Zugriff auf das Protokoll :
-  `log::add ('Dateiname', 'Ebene', 'Nachricht'); `
    - Dateiname : Name der Protokolldatei.
    - Ebene : [Debug], [Info], [Fehler], [Ereignis].
    - Nachricht : Nachricht zum Schreiben in die Protokolle.

##### Zugriff auf das Szenario :
- $ Szenario-> getName (); ` : Gibt den Namen des aktuellen Szenarios zurück.
- $ Szenario-> getGroup (); ` : Gibt die Szenariogruppe zurück.
- $ Szenario-> getIsActive (); ` : Gibt den Status des Szenarios zurück.
- $ Szenario-> setIsActive ($ active); ` : Ermöglicht das Aktivieren oder Nicht-Aktivieren des Szenarios.
    - `$ active` : 1 aktiv, 0 nicht aktiv.
- `$ Szenario-> setOnGoing ($ onGoing);` : Sagen wir, ob das Szenario läuft oder nicht.
    - `$ onGoing => 1` : 1 in Bearbeitung, 0 gestoppt.
- `$ Szenario-> speichern ();` : Änderungen speichern.
- $ szenario-> setData ($ key, $ value); ` : Daten speichern (Variable).
    - `$ key` : Werteschlüssel (int oder string).
    - `$ value` : zu speichernder Wert (int, string, array oder object).
- `$ Szenario-> getData ($ Schlüssel);` : Daten abrufen (variabel).
    - `$ key => 1` : Werteschlüssel (int oder string).
- $ Szenario-> removeData ($ key); ` : Daten löschen.
- $ szenario-> setLog ($ message); ` : Schreiben Sie eine Nachricht in das Skriptprotokoll.
- $ Szenario-> persistLog (); ` : Erzwingen Sie das Schreiben des Protokolls (andernfalls wird es nur am Ende des Szenarios geschrieben). Seien Sie vorsichtig, dies kann das Szenario etwas verlangsamen.

> **Tip**
>
> Hinzufügen einer Suchfunktion im Codeblock : Suche : Strg + F dann Enter, Nächstes Ergebnis : Strg + G, Vorheriges Ergebnis : Strg + Umschalt + G.

#### Kommentarblock

Der Kommentarblock verhält sich anders, wenn er ausgeblendund ist. Die Schaltflächen auf der linken Seite sowie der Titel des Blocks verschwinden und werden beim Schweben wieder angezeigt. Ebenso wird die erste Zeile des Kommentars fett gedruckt.
Dadurch kann dieser Block als rein visuelle Trennung innerhalb des Szenarios verwendund werden.

### Aktionen

Zu Blöcken hinzugefügte Aktionen haben mehrere Optionen :

- Eine Kiste **aktiviert** damit dieser Befehl im Szenario berücksichtigt wird.
- Eine Kiste **parallel** so dass dieser Befehl parallel (gleichzeitig) mit den anderen ebenfalls ausgewählten Befehlen gestartund wird.
- Ein **vertikaler Doppelpfeil** um die Aktion zu verschieben. Einfach von dort ziehen und ablegen.
- Ein Knopf für **supprimer** die Aktion.
- Eine Schaltfläche für bestimmte Aktionen, beich der jedes Mal die Beschreibung (beim Bewegen des Mauszeigers) dieser Aktion angezeigt wird.
- Eine Schaltfläche zum Suchen nach einem Aktionsbefehl.

> **Tip**
>
> Abhängig vom ausgewählten Befehl werden verschiedene zusätzliche Felder angezeigt.

## Mögliche Substitutionen

### Auslöser

Es gibt bestimmte Auslöser (außer denen, die durch Befehle bereitgestellt werden). :

- #start# : Wird beim (erneuten) Start von Jeedom ausgelöst.
- #begin_backup# : Ereignis, das zu Beginn einer Sicherung gesendund wurde.
- #end_backup# : Ereignis, das am Ende einer Sicherung gesendund wird.
- #BEGIN_UPDATE# : Ereignis, das zu Beginn eines Updates gesendund wurde.
- #END_UPDATE# : Ereignis, das am Ende eines Updates gesendund wird.
- #begin_restore# : Ereignis zu Beginn einer Restaurierung gesendet.
- #end_restore# : Ereignis gesendund am Ende einer Restaurierung.
- #user_connect# : Benutzeranmeldung

Sie können auch ein Szenario auslösen, wenn eine Variable durch Putten aktualisiert wird : #Variable (Variablenname) # oder unter Verwendung der beschriebenen HTTP-API [hier](https://jeedom.github.io/core/fr_FR/api_http).

### Vergleichsoperatoren und Verknüpfungen zwischen Bedingungen

Sie können eines der folgenden Symbole für Vergleiche unter Bedingungen verwenden :

- == : Gleich.
- \.> : Streng größer als.
- \.>= : Größer oder gleich.
- < : Streng weniger als.
- <= : Kleiner als oder gleich.
- != : Anders als, ist nicht gleich.
- Streichhölzer : Enthält. Ex : `[Badezimmer] [Hydrometrie] [Bedingung] entspricht" / wund / "`.
- nicht (… passt…) : Enthält nicht. Ex :  `not ([Badezimmer] [Hydrometrie] [Zustand] stimmt mit" / wund / "überein)`.

Sie können jeden Vergleich mit den folgenden Operatoren kombinieren :

- &amp;&amp; / ET / und / AND / und : et,
- \.|| / OR / oder / OR / oder : ou,
- \.|^ / XOR / xor : oder exklusiv.

### Tags

Ein Etikett wird während der Ausführung des Szenarios durch seinen Wert ersetzt. Sie können die folgenden Tags verwenden :

> **Tip**
>
> Verwenden Sie die Funktion Date (), um die führenden Nullen anzuzeigen. Siehe [hier](http://php.net/manual/fr/function.date.php).

- #seconde# : Aktuelle Sekunde (ohne führende Nullen, z : 6 für 08:07:06).
- #hour# : Aktuelle Zeit im 24h Format (ohne führende Nullen). Ex : 8 für 08:07:06 oder 17 für 17:15.
- #hour12# : Aktuelle Zeit im 12-Stunden-Format (ohne führende Nullen). Ex : 8 für 08:07:06.
- #minute# : Aktuelle Minute (ohne führende Nullen). Ex : 7 für 08:07:06.
- #day# : Aktueller Etikett (ohne führende Nullen). Ex : 6 für 06/07/2017.
- #month# : Aktueller Monat (ohne führende Nullen). Ex : 7 für 06/07/2017.
- #year# : Laufendes Jahr.
- #time# : Aktuelle Stunde und Minute. Ex : 1715 für 17.15 Uhr.
- #timestamp# : Anzahl der Sekunden seit dem 1. Januar 1970.
- #date# : Etikett und Monat. Achtung, die erste Zahl ist der Monat. Ex : 1215 für den 15. Dezember.
- #week# : Wochennummer.
- #sday# : Name des Wochentags. Ex : Samstag.
- #nday# : Tagesnummer von 0 (Sonntag) bis 6 (Samstag).
- #smonth# : Name des Monats. Ex : Januar.
- #IP# : Jeedom&#39;s interne IP.
- #hostname# : Name der Jeedom-Maschine.
- #Trigger # (veraltet, besser Trigger () verwenden) : Möglicherweise der Name des Befehls, der das Szenario gestartund hat :
    - 'apich &#39;, wenn der Start von der API ausgelöst wurde,
    - 'Zeitplan &#39;, wenn es durch Programmierung gestartund wurde,
    - 'Benutzer &#39;, wenn es manuell gestartund wurde,
    - 'Start &#39;für einen Start, wenn Jeedom startet.
- #trigger_value # (veraltet, besser triggerValue () zu verwenden) : Für den Wert des Befehls, der das Szenario ausgelöst hat

Sie haben auch die folgenden zusätzlichen Tags, wenn Ihr Szenario durch eine Interaktion ausgelöst wurde :

- #query# : Interaktion, die das Szenario ausgelöst hat.
- #profil# : Profil des Benutzers, der das Szenario ausgelöst hat (kann leer sein).

> **Important**
>
> Wenn ein Szenario durch eine Interaktion ausgelöst wird, wird es notwendigerweise im schnellen Modus ausgeführt. Also im Interaktionsthread und nicht in einem separaten Thread.

### Berechnungsfunktionen

Für das Gerät stehen verschiedene Funktionen zur Verfügung :

- Durchschnitt (Reihenfolge, Zeitraum) und Durchschnitt zwischen (Reihenfolge, Start, Ende) : Geben Sie den Durchschnitt der Bestellung über den Zeitraum an=[Monat, Tag, Stunde, Minute] oder [PHP-Expression](http://php.net/manual/fr/datetime.formats.relative.php)) oder zwischen den 2 angeforderten Terminals (in der Form Ymd H.:i:s oder [PHP-Expression](http://php.net/manual/fr/datetime.formats.relative.php)).

- min (Reihenfolge, Periode) und minBetween (Reihenfolge, Start, Ende) : Geben Sie die Mindestbestellmenge über den Zeitraum (Zeitraum) an=[Monat, Tag, Stunde, Minute] oder [PHP-Expression](http://php.net/manual/fr/datetime.formats.relative.php)) oder zwischen den 2 angeforderten Terminals (in der Form Ymd H.:i:s oder [PHP-Expression](http://php.net/manual/fr/datetime.formats.relative.php)).

- max (Reihenfolge, Periode) und maxBetween (Reihenfolge, Start, Ende) : Geben Sie das Maximum der Bestellung über den Zeitraum (Zeitraum) an=[Monat, Tag, Stunde, Minute] oder [PHP-Expression](http://php.net/manual/fr/datetime.formats.relative.php)) oder zwischen den 2 angeforderten Terminals (in der Form Ymd H.:i:s oder [PHP-Expression](http://php.net/manual/fr/datetime.formats.relative.php)).

- Dauer (Reihenfolge, Wert, Periode) und Dauer zwischen (Reihenfolge, Wert, Start, Ende) : Geben Sie die Dauer in Minuten an, während der das Gerät über den Zeitraum (Zeitraum) den gewählten Wert hatte=[Monat, Tag, Stunde, Minute] oder [PHP-Expression](http://php.net/manual/fr/datetime.formats.relative.php)) oder zwischen den 2 angeforderten Terminals (in der Form Ymd H.:i:s oder [PHP-Expression](http://php.net/manual/fr/datetime.formats.relative.php)).

- Statistik (Reihenfolge, Berechnung, Zeitraum) und StatistikZwischen (Reihenfolge, Berechnung, Start, Ende) : Geben Sie das Ergebnis verschiedener statistischer Berechnungen (Summe, Anzahl, Standard, Varianz, Durchschnitt, Min, Max) über den Zeitraum (Zeitraum) an=[Monat, Tag, Stunde, Minute] oder [PHP-Expression](http://php.net/manual/fr/datetime.formats.relative.php)) oder zwischen den 2 angeforderten Terminals (in der Form Ymd H.:i:s oder [PHP-Expression](http://php.net/manual/fr/datetime.formats.relative.php)).

- Trend (Befehl, Zeitraum, threshold) : Gibt den Trend der Bestellung über den Zeitraum (Zeitraum) an=[Monat, Tag, Stunde, Minute] oder [PHP-Expression](http://php.net/manual/fr/datetime.formats.relative.php)).

- stateDuration (Kontrolle) : Gibt die Dauer in Sekunden seit der letzten Wertänderung an.
    -1 : Es gibt keine Geschichte oder keinen Wert in der Geschichte.
    -2 : Die Bestellung wird nicht protokolliert.

- lastChangeStateDuration (Sollwert) : Gibt die Dauer in Sekunden seit der letzten Zustandsänderung auf den im Parameter übergebenen Wert an.
    -1 : Es gibt keine Geschichte oder keinen Wert in der Geschichte.
    -2 Die Bestellung wird nicht protokolliert

- lastStateDuration (Sollwert) : Gibt die Dauer in Sekunden an, in der das Gerät zuletzt den gewählten Wert hatte.
    -1 : Es gibt keine Geschichte oder keinen Wert in der Geschichte.
    -2 : Die Bestellung wird nicht protokolliert.

- Alter (Kontrolle) : Gibt das Alter des Befehlswerts in Sekunden an (collecDate)
    -1 : Der Befehl existiert nicht oder ist nicht vom Typ info.

- stateChanges (Befehl,[Wert], Punkt) und stateChangesBetween (Befehl, [Wert], Start, Ende) : Geben Sie die Anzahl der Statusänderungen (auf einen bestimmten Wert, falls angegeben, oder insgesamt, wenn nicht) über den Zeitraum (Zeitraum = [Monat, Tag, Stunde, Minute] oder [PHP-Ausdruck) an](http://php.net/manual/fr/datetime.formats.relative.php)) oder zwischen den 2 angeforderten Terminals (in der Form Ymd H.:i:s oder [PHP-Expression](http://php.net/manual/fr/datetime.formats.relative.php)).

- lastBetween (command, Beginn, Ende) : Gibt den zuletzt für das Gerät zwischen den beiden angeforderten Terminals aufgezeichneten Wert an (in der Form Ymd H.:i:s oder [PHP-Expression](http://php.net/manual/fr/datetime.formats.relative.php)).

- Variable (Variable, Standard) : Ruft standardmäßig den Wert einer Variablen oder den gewünschten Wert ab.

- Szenario (Szenario) : Gibt den Status des Szenarios zurück.
    1 : In Bearbeitung,
    0 : Verhaftet,
    -1 : Untauglich,
    -2 : Das Szenario existiert nicht,
    -3 : Zustand ist nicht konsistent.
    Um den &quot;menschlichen&quot; Namen des Szenarios zu erhalten, können Sie die entsprechende Schaltfläche rechts neben der Szenariosuche verwenden.

- lastScenarioExecution (Szenario) : Gibt die Dauer in Sekunden seit dem letzten Start des Szenarios an.
    0 : Das Szenario existiert nicht

- collectDate (cmd,[Format]) : Gibt das Datum der letzten Daten für den im Parameter angegebenen Befehl zurück. Der zweite optionale Parameter ermöglicht die Angabe des Rückgabeformats (Details [hier]](http://php.net/manual/fr/function.date.php)).
    -1 : Der Befehl konnte nicht gefunden werden,
    -2 : Der Befehl ist nicht vom Typ info.

- valueDate (cmd,[Format]) : Gibt das Datum der letzten Daten für den im Parameter angegebenen Befehl zurück. Der zweite optionale Parameter ermöglicht die Angabe des Rückgabeformats (Details [hier]](http://php.net/manual/fr/function.date.php)).
    -1 : Der Befehl konnte nicht gefunden werden,
    -2 : Der Befehl ist nicht vom Typ info.

- eqEnable (Ausrüstung) : Gibt den Status des Geräts zurück.
    -2 : Das Gerät kann nicht gefunden werden,
    1 : Ausrüstung ist aktiv,
    0 : Das Gerät ist inaktiv.

- Wert (cmd) : Gibt den Wert einer Bestellung zurück, wenn er nicht automatisch von Jeedom angegeben wird (Groß- und Kleinschreibung, wenn der Name der Bestellung in einer Variablen gespeichert wird).

- Etikett (Montag [Standard]) : Wird verwendet, um den Wert eines Tags oder den Standardwert abzurufen, falls dieser nicht vorhanden ist.

- (Art, Kontrolle) : Wird verwendet, um den Namen der Bestellung, Ausrüstung oder des Objekts abzurufen. Typ : cmd, eqLogic oder Objekt.

- lastCommunication (Ausrüstung,[Format]) : Gibt das Datum der letzten Kommunikation für das als Parameter angegebene Gerät zurück. Mit dem zweiten optionalen Parameter können Sie das Rückgabeformat angeben (Details [hier]](http://php.net/manual/fr/function.date.php)). Eine Rückgabe von -1 bedeutet, dass das Gerät nicht gefunden werden kann.

- color_gradient (couleur_debut, couleur_fin, valuer_min, valeur_max, value) : Gibt eine Farbe zurück, die in Bezug auf den Wert im Bereich color_Start / color_end berechnund wurde. Der Wert muss zwischen min_value und max_value liegen.

Die Perioden und Intervalle dieser Funktionen können auch mit verwendund werden [PHP-Ausdrücke](http://php.net/manual/fr/datetime.formats.relative.php) wie zum Beispiel :

- Jetzt : jetzt.
- Heute : 00:00 heute (ermöglicht es beispielsweise, Ergebnisse für den Etikett zu erhalten, wenn zwischen &#39;Heute&#39; und &#39;Jetzt&#39;).
- Letzten Montag : letzten Montag um 00:00.
- Vor 5 Tagen : Vor 5 Tagen.
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
| max (Entscheidung, Periode)                 | Rückgabe 1 : Der Stecker war in der Zeit gut beleuchtund              |
| maxBetween (\.# [Badezimmer] [Hydrometrie] [Luftfeuchtigkeit] \.#, 2015-01-01 00:00:00,2015-01-15 00:00:00) | Gibt das Maximum der Bestellung zwischen dem 1. Januar 2015 und dem 15. Januar 2015 zurück                       |
| Dauer (Stecker, 1 Periode)          | Gibt 60 zurück : Der Stecker war in diesem Zeitraum 60 Minuten lang eingeschaltund (beich 1)                              |
| durationBetween (\.# [Salon] [Take] [State] \.#, 0, letzter Montag, jetzt)   | Gibt die Dauer in Minuten zurück, in der die Steckdose seit dem letzten Montag ausgeschaltund war.                |
| Statistiken (Fang, Anzahl, Zeitraum)    | Rückgabe 8 : In diesem Zeitraum gab es 8 Eskalationen               |
| Trend (Stecker, Periode 0.1)        | Gibt -1 zurück : Abwärtstrend    |
| stateDuration (Stecker)               | Gibt 600 zurück : Der Stecker befindund sich seit 600 Sekunden (10 Minuten) in seinem aktuellen Zustand.                             |
| lastChangeStateDuration (Fang, 0)   | Gibt 600 zurück : Die Steckdose ist vor 600 Sekunden (10 Minuten) zum letzten Mal ausgefallen (auf 0 geändert)     |
| lastChangeStateDuration (Fang, 1)   | Gibt 4200 zurück : Die Steckdose wurde vor 4200 Sekunden (1h10) zum letzten Mal eingeschaltund (auf 1 umschalten).                               |
| lastStateDuration (Fang, 0)         | Gibt 600 zurück : Die Steckdose war 600 Sekunden (10 Minuten) ausgeschaltet.     |
| lastStateDuration (Fang, 1)         | Gibt 3600 zurück : Die Steckdose wurde zuletzt für 3600 Sekunden (1 Stunde) eingeschaltet.           |
| stateChanges (Fang, Periode)        | Rückgabe 3 : Der Stecker hat während des Zeitraums dreimal den Zustand geändert            |
| stateChanges (Fang, 0, Periode)      | Rückgabe 2 : Die Steckdose ist während des Zeitraums zweimal erloschen (auf 0)                              |
| stateChanges (Fang, 1 Punkt)      | Rückgabe 1 : Der Stecker leuchtund während des Zeitraums einmal (auf 1 ändern)                              |
| lastBetween (\.# [Badezimmer] [Hydrometrie] [Luftfeuchtigkeit] \.#, gestern, heute) | Gibt die zuletzt gestern aufgezeichnete Temperatur zurück.                    |
| Variable (Plopp, 10)                  | Gibt den Wert der Variablen plop oder 10 zurück, wenn sie leer ist oder nicht existiert                         |
| Szenario (\.# [Badezimmer] [Licht] [Bisuto] \.#) | Gibt 1 in Bearbeitung zurück, 0, wenn gestoppt, und -1, wenn deaktiviert, -2, wenn das Szenario nicht existiert, und -3, wenn der Status nicht konsistent ist                         |
| lastScenarioExecution (\.# [Badezimmer] [Licht] [Bisuto] \.#)   | Gibt 300 zurück, wenn das Szenario vor 5 Minuten zum letzten Mal gestartund wurde                                  |
| collectDate (\.# [Badezimmer] [Hydrometrie] [Luftfeuchtigkeit] \.#)     | Rückgabe 2015-01-01 17:45:12          |
| valueDate (\.# [Badezimmer] [Hydrometrie] [Luftfeuchtigkeit] \.#) | Rückgabe 2015-01-01 17:50:12          |
| eqEnable (\.# [Nein] [Basilika] \.#)       | Gibt -2 zurück, wenn das Gerät nicht gefunden wird, 1, wenn das Gerät aktiv ist, und 0, wenn es inaktiv ist          |
| Etikett (Montag toto)                   | Gibt den Wert von "montag" zurück, falls vorhanden, andernfalls wird der Wert "toto" zurückgegeben"                               |
| Name (eqLogic, \.# [Badezimmer] [Hydrometrie] [Luftfeuchtigkeit] \.#)     | Gibt Hydrometrie zurück                  |


### Mathematische Funktionen

Eine generische Funktions-Toolbox kann auch zum Durchführen von Konvertierungen verwendund werden

oder Berechnungen :

- Rand (1.10) : Geben Sie eine Zufallszahl von 1 bis 10 an.
- `randText (text1; text2; text… ..)` : Ermöglicht es Ihnen, einen der Texte zufällig zurückzugeben (trennen Sie die Texte durch a;). Die Anzahl der Texte ist unbegrenzt.
- `randomColor (min, max)` : Gibt eine zufällige Farbe zwischen 2 Grenzen (0 =&gt; rot, 50 =&gt; grün, 100 =&gt; blau).
- `Trigger (Befehl)` : Ermöglicht es Ihnen, den Auslöser für das Szenario herauszufinden oder festzustellen, ob der als Parameter übergebene Befehl das Szenario ausgelöst hat.
- `triggerValue (Befehl)` : Wird verwendet, um den Wert des Szenario-Triggers herauszufinden.
- `round (Wert, [dezimal])` : Runden oben, [Dezimal] Anzahl der Dezimalstellen nach dem Dezimalpunkt.
- `ungerade (Wert)` : Lässt Sie wissen, ob eine Zahl ungerade ist oder nicht. Gibt 1 zurück, wenn sonst ungerade 0.
- `Median (Befehl1, Befehl2….BefehlN) ` : Gibt den Median der Werte zurück.
- `avg (Befehl1, Befehl2….BefehlN) `: Gibt den Durchschnitt der Werte zurück.
- `time_op (Zeit, Wert)` : Ermöglicht die pünktliche Ausführung von Operationen mit Zeit = Zeit (z : 1530) und Wert = Wert zum Addieren oder Subtrahieren in Minuten.
- `time_between (Zeit, Start, Ende)` : Wird verwendet, um zu testen, ob eine Zeit zwischen zweich Werten mit &quot;Zeit = time&quot; liegt (z : 1530), "Start = Zeit", "Ende = Zeit". Start- und Endwerte können sich über Mitternacht erstrecken.
- `time_diff (date1, date2 [, format, round])` : Wird verwendet, um den Unterschied zwischen zweich Daten zu ermitteln (die Daten müssen das Format JJJJ / MM / TT HH haben:MM:SS). Standardmäßig gibt die Methode die Differenz in Etikett (en) zurück.. Sie können es in Sekunden (s), Minuten (m), Stunden (h) fragen.. Beispiel in Sekunden `time_diff (2019-02-02 14:55:00.2019-02-25 14:55:00,s)`. Die Differenz wird in absoluten Zahlen zurückgegeben, es seich denn, Sie geben &quot;f&quot; an (sf, mf, hf, df).. Sie können auch `dhms` verwenden, das kein Beispiel` 7d 2h 5min 46s` zurückgibt. Der optionale runde Parameter wird nach dem Dezimalpunkt auf x Stellen gerundund (standardmäßig 2).. Ex: `time_diff (2020-02-21 20:55:28,2020-02-28 23:01:14, df, 4) `.
- `formatTime (Zeit)` : Ermöglicht das Formatieren der Rückgabe einer Zeichenfolge "# Zeit #".
- `Etage (Zeit / 60)` : Konvertiert Sekunden in Minuten oder Minuten in Stunden (Etage (Zeit / 3600) für Sekunden in Stunden).
- `convertDuration (Sekunden)` : Konvertiert Sekunden in d / h / min / s.

Und praktische Beispiele :


| Funktionsbeispiel                  | Zurückgegebenes Ergebnis                    |
|--------------------------------------|--------------------------------------|
| randText (es ist # [Wohnzimmer] [Bisuge] [Temperatur] #; Die Temperatur ist # [Wohnzimmer] [Bisuge] [Temperatur] #; Derzeit haben wir # [Wohnzimmer] [Bisuge] [Temperatur] #) | Die Funktion gibt beich jeder Ausführung zufällig einen dieser Texte zurück.                           |
| randomColor (40,60)                 | Gibt eine zufällige Farbe nahe Grün zurück.
| Auslöser (# [Badezimmer] [Hydrometrie] [Luftfeuchtigkeit] #)   | 1 wenn es gut ist \.# \. [Badezimmer \.] \. [Hydrometrie \.] \. [Luftfeuchtigkeit \.] \.#, was das Szenario ansonsten gestartund hat 0  |
| triggerValue (# [Badezimmer] [Hydrometrie] [Luftfeuchtigkeit] #) | 80, wenn die Hydrometrie von \.# \. [Badezimmer \.] \. [Hydrometrie \.] \. [Luftfeuchtigkeit \.] \.# 80% beträgt.                         |
| rund (# [Badezimmer] [Hydrometrie] [Luftfeuchtigkeit] # / 10) | Gibt 9 zurück, wenn der Feuchtigkeitsprozentsatz und 85                     |
| ungerade (3)                             | Rückgabe 1                            |
| Median (15,25,20)                   | Rückgabe 20
| avg (10,15,18)                      | Rückgabe 14.3                     |
| time_op (# Zeit #, -90)               | Wenn es 16:50 Uhr ist, kehren Sie zurück : 1 650-1 130 = 1520                          |
| FormatZeit (1650)                   | Rückgabe 16:50 Uhr                        |
| Stock (130/60)                      | Gibt 2 zurück (Minuten beich 130 s oder Stunden beich 130 m)                      |
| convertDuration (3600)              | Gibt 1h 0min 0s zurück                      |
| convertDuration (Dauer (# [Heizung] [Kesselmodul] [Zustand] #, 1, erster Etikett dieses Monats) * 60) | Gibt die Zündzeit in Tagen / Stunden / Minuten der Übergangszeit auf Zustand 1 des Moduls seit dem 1. Etikett des Monats zurück |


### Spezifische Bestellungen

Zusätzlich zu den Befehlen für die Hausautomation haben Sie Zugriff auf die folgenden Aktionen :

- **Pause** (Sleep) : Pause von x Sekunde (n).
- **variable** (Variabel) : Erstellung / Änderung einer Variablen oder des Werts einer Variablen.
- **Variable entfernen** (Delete_variable) : Ermöglicht das Löschen einer Variablen.
- **Szenario** (Szenario) : Ermöglicht die Steuerung von Szenarien. Mit dem Tag-Teil können Sie Tags an das Szenario senden, z : montag = 2 (Vorsicht, verwenden Sie nur Buchstaben von a bis z. Keine Großbuchstaben, keine Akzente und keine Sonderzeichen). Wir stellen das Etikett im Zielszenario mit der Tag-Funktion (montag) wieder her.. Mit dem Befehl &quot;Auf SI zurücksetzen&quot; kann der Status von &quot;SI&quot; zurückgesetzt werden (dieser Status wird verwendet, um die Aktionen eines &quot;SI&quot; nicht zu wiederholen, wenn Sie ihn zum zweiten Mal in Folge übergeben).
- **Stop** (Stop) : Stoppen Sie das Szenario.
- **Attendre** (WAIT) : Warten Sie, bis die Bedingung gültig ist (maximal 2 Stunden). Die Zeitüberschreitung erfolgt in Sekunden.
- **Gehe zum Design** (Gotodesign) : Ändern Sie das in allen Browsern angezeigte Design durch das angeforderte Design.
- **Fügen Sie ein Protokoll hinzu** (Log) : Ermöglicht das Hinzufügen einer Nachricht zu den Protokollen.
- **Nachricht erstellen** (Message) : Fügen Sie dem Nachrichtencenter eine Nachricht hinzu.
- **Geräte ausblenden / deaktivieren** (Ausstattung) : Ermöglicht das Ändern der Eigenschaften von sichtbaren / unsichtbaren, aktiven / inaktiven Geräten.
- **Stellen Sie eine Anfrage** (Ask) : Wird verwendet, um Jeedom anzuzeigen, dass dem Benutzer eine Frage gestellt werden soll. Die Antwort wird in einer Variablen gespeichert, dann müssen Sie nur noch ihren Wert testen.
    Momentan sind nur SMS-, Slack-, Telegramm- und Snips-Plugins sowie die mobile Anwendung kompatibel.
    Achtung, diese Funktion blockiert. Solange keine Antwort erfolgt oder das Zeitlimit nicht erreicht wird, wartund das Szenario.
- **Stoppen Sie Jeedom** (Jeedom_poweroff) : Bitten Sie Jeedom, herunterzufahren.
- **Geben Sie einen Text / Daten zurück** (Scenario_return) : Gibt beispielsweise einen Text oder einen Wert für eine Interaktion zurück.
- **Symbol** (Symbol) : Ermöglicht das Ändern des Darstellungssymbols des Szenarios.
- **Alerte** (Alert) : Zeigt eine kleine Warnmeldung in allen Browsern an, in denen eine Jeedom-Seite geöffnund ist. Sie können zusätzlich 4 Alarmstufen auswählen.
- **Pop-up** (Popup) : Ermöglicht die Anzeige eines Popups, das in allen Browsern, in denen eine Jeedom-Seite geöffnund ist, unbedingt überprüft werden muss.
- **Rapport** (Bericht) : Ermöglicht das Exportieren einer Ansicht im Format (PDF, PNG, JPEG oder SVG) und das Senden mit einem Befehl vom Typ Nachricht. Bitte beachten Sie, dass diese Funktionalität nicht funktioniert, wenn sich Ihr Internetzugang in nicht signiertem HTTPS befindet. Signiertes HTTP oder HTTPS ist erforderlich.
- **Programmierten IN / A-Block löschen** (Remove_inat) : Ermöglicht das Löschen der Programmierung aller IN- und A-Blöcke des Szenarios.
- **Ereignis** (Ereignis) : Ermöglicht das willkürliche Übertragen eines Werts in einen Befehl vom Typ Information.
- **Tag** (Tag) : Ermöglicht das Hinzufügen / Ändern eines Tags (das Etikett ist nur während der aktuellen Ausführung des Szenarios vorhanden, im Gegensatz zu den Variablen, die das Ende des Szenarios überleben)..
- **Färbung von Dashboard-Symbolen** (SetColoredIcon) : Ermöglicht das Aktivieren oder Nicht-Aktivieren der Farbgebung von Symbolen im Dashboard.

### Szenariovorlage

Mit dieser Funktion können Sie ein Szenario in eine Vorlage umwandeln, um es beispielsweise auf ein anderes Jeedom anzuwenden.

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
> Das Hinzufügen der PHP-Funktion ist fortgeschrittenen Benutzern vorbehalten. Der kleinste Fehler kann für Ihr Jeedom fatal sein.

#### Einrichten

Gehen Sie zur Jeedom-Konfiguration, dann zu OS / DB und starten Sie den Datei-Editor.

Gehen Sie in den Datenordner und dann in PHP und klicken Sie auf die Dateich user.function.class.php.

In dieser * Klasse * können Sie Ihre Funktionen hinzufügen. Sie finden ein Beispiel für eine Grundfunktion.

> **IMPORTANT**
>
> Wenn Sie Bedenken haben, können Sie jederzeit zur Originaldateich zurückkehren, indem Sie den Inhalt von user.function.class.sample kopieren.PHP in user.function.class.php
