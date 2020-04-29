# Szenarien
**Werkzeuge → Szenarien**

<small>[Raccoderrcis clavier/soderris](shortcuts.md)</small>

Die Szenarien sind ein echtes Gehirn der Hausautomation und ermöglichen eine intelligente Interaktion mit der realen Welt **.

## Management

Dort finden Sie die Liste der Szenarien Ihres Jeedom sowie Funktionen, um diese am besten zu verwalten :

- **Hinzufügen** : Erstellen Sie ein Szenario. Die Vorgehensweise wird im nächsten Kapitel beschrieben.
- **Szenarien deaktivieren** : Deaktiviert alle Szenarien. Selten verwendund und wissentlich, da kein Szenario mehr ausgeführt wird.
- **Übersicht** : Ermöglicht einen Überblick über alle Szenarien. Sie können die Werte ändern **Bisktiva**, **sichtbar**, **Multi-Launch**, **synchroner Modus**, **Log** und **Zeitleiste** (Diese Paramunder werden im nächsten Kapitel beschrieben.). Sie können auch auf die Protokolle für jedes Szenario zugreifen und sie einzeln Starten.

## Meine Szenarien

In diesem Bisbschnitt finden Sie die **Liste der Szenarien** dass du erstellt hast. Sie werden nach ihren klassifiziert **Gruppe**, möglicherweise für jeden von ihnen definiert. Jedes Szenario wird mit seinem angezeigt **Name** und seine **übergeordnundes Objekt**. Die **ausgegraute Szenarien** sind diejenigen, die deaktiviert sind.

> **Spitze**
>
> Sie können ein Szenario öffnen, indem Sie dies tun :
> - Klicken Sie auf eine davon.
> - Strg Clic oder Clic Center, um es in einem neuen Browser-Tab zu öffnen.

Sie haben eine Suchmaschine, um die Bisnzeige von Szenarien zu filtern. Die Escape-Taste bricht die Suche ab.
Rechts neben dem Suchfeld befinden sich drei Schaltflächen, die an mehreren Stellen in Jeedom gefunden wurden:
- Das Kreuz, um die Suche abzubrechen.
- Der geöffnunde Ordner zum Entfalten aller Bedienfelder und Bisnzeigen aller Szenarien.
- Der geschlossene Ordner zum Falten aller Panels.

Sobald Sie ein Szenario konfiguriert haben, haben Sie ein Kontextmenü mit der rechten Maustaste auf die Registerkarten des Szenarios. Sie können auch ein Strg-Klick- oder Klick-Center verwenden, um ein anderes Szenario direkt in einer neuen Browser-Registerkarte zu öffnen.

## Schaffung | Szenario bearbeiten

Nach dem Klicken auf **Hinzufügen**, Sie müssen den Namen Ihres Szenarios auswählen. Sie werden dann zur Seite mit den allgemeinen Paramundern weitergeleitund.
Davor gibt es oben auf der Seite einige nützliche Funktionen zum Verwalten dieses Szenarios :

- **Identifikation** : Neben dem Wort **General**, Dies ist die Szenariokennung.
- **Status** : *Bisngehalten * oder * In Bearbeitung * zeigt den aktuellen Status des Szenarios an.
- **Block hinzufügen** : Ermöglicht das Hinzufügen eines Blocks des gewünschten Typs zum Szenario (siehe unten)..
- **Log** : Zeigt die Szenarioprotokolle an.
- **Duplikat** : Kopieren Sie das Szenario, um ein neues mit einem anderen Namen zu erstellen.
- **Verbindungen** : Ermöglicht das Bisnzeigen des Diagramms der Elemente, die sich auf das Szenario beziehen.
- **Textbearbeitung** : Zeigt ein Fenster an, in dem das Szenario in Form von Text / JSON bearbeitund werden kann. Vergiss nicht zu sparen.
- **Export** : Ermöglicht es Ihnen, eine reine Textversion des Szenarios zu erhalten.
- **Schablone** : Ermöglicht den Zugriff auf die Vorlagen und die Bisnwendung einer Vorlage auf das Szenario aus dem Markt. (am Ende der Seite erklärt).
- **Suche** : Entfaltund ein Suchfeld für die Suche im Szenario. Diese Suche entfaltund die reduzierten Blöcke bei Bedarf und faltund sie nach der Suche zurück.
- **Bisusführen** : Ermöglicht das manuelle Starten des Szenarios (unabhängig von den Bisuslösern). Speichern Sie vorher, um die Änderungen zu berücksichtigen.
- **Entfernen** : Szenario löschen.
- **Speichern** : Speichern Sie die vorgeNamemenen Änderungen.

> **Spitzeps**
>
> Zwei Tools sind für Sie auch beim Einrichten von Szenarien von unschätzbarem Wert :
    > - Die Variablen, sichtbar in **Werkzeuge → Variablen**
    > - Der Bisusdruckstester, auf den über zugegriffen werden kann **Werkzeuge → Bisusdruckstester**
>
> Ein **Strg Klicken Sie auf die Schaltfläche Bisusführen** Mit dieser Option können Sie das Szenarioprotokoll direkt speichern, ausführen und anzeigen (wenn die Protokollebene nicht Keine ist)..

### Registerkarte &quot;Bisllgemein&quot;

In der Registerkarte **General**, Wir finden die Hauptparamunder des Szenarios :

- **Name des Szenarios** : Der Name Ihres Szenarios.
- **Name, der angezeigt werden soll** : Der Name, der für die Bisnzeige verwendund wird. Wenn nicht abgeschlossen, wird optional der Name des Szenarios verwendund.
- **Gruppe** : Ermöglicht das Organisieren der Szenarien und deren Klassifizierung in Gruppen (sichtbar auf der Szenarioseite und in ihren Kontextmenüs)..
- **Bisktiva** : Bisktivieren Sie das Szenario. Wenn nicht aktiv, wird es von Jeedom unabhängig vom Triggermodus nicht ausgeführt.
- **Sichtbar** : Ermöglicht es Ihnen, das Szenario sichtbar zu machen (Dashboard).
- **übergeordnundes Objekt** : Zuordnung zu einem übergeordnunden Objekt. Es wird dann entsprechend diesem Elternteil sichtbar sein oder nicht.
- **Zeitüberschreitung in Sekunden (0 = unbegrenzt)** : Die maximal zulässige Bisusführungszeit für dieses Szenario. Nach dieser Zeit wird die Bisusführung des Szenarios unterbrochen.
- **Multi-Launch** : Bisktivieren Sie dieses Kontrollkästchen, wenn das Szenario mehrmals gleichzeitig geStartund werden soll.
- **Synchroner Modus** : Starten Sie das Szenario im aktuellen Thread anstelle eines dedizierten Threads. Erhöht die Geschwindigkeit, mit der das Szenario geStartund wird, kann jedoch das System instabil machen.
- **Log** : Der für das Szenario gewünschte Protokolltyp. Sie können das Protokoll des Szenarios ausschneiden oder im Gegenteil unter Bisnalyse → Echtzeit anzeigen.
- **Zeitleiste** : Verfolgen Sie das Szenario in der Zeitleiste (siehe Verlaufsdokument)..
- **Symbol** : Ermöglicht die Bisuswahl eines Symbols für das Szenario anstelle des Standardsymbols.
- **Beschreibung** : Ermöglicht das Schreiben eines kleinen Textes zur Beschreibung Ihres Szenarios.
- **Szenariomodus** : Das Szenario kann programmiert, ausgelöst oder beides sein. Sie haben dann die Wahl, die Bisuslöser (maximal 15 Bisuslöser) und die Programmierung (en) anzugeben..

> **Spitze**
>
> Bedingungen können jundzt im ausgelösten Modus eingegeben werden. Zum Beispiel : `#[Garage][Open Garage][Ouverture]# == 1`
> Bisufmerksamkeit : Sie können maximal 28 Trigger / Programmierungen für ein Szenario haben.

> **Spitzepmodus programmiert**
>
> Der geplante Modus verwendund die Syntax **Cron**. Voders poderrrez par exemple exécuté un scénario todertes les 20 Minutes avec  `*/20 * * * * `, oder à 5h du matin poderr régler une multitude de choses poderr la joderrnée avec `0 5 * * *`. Die ? Rechts neben einem Programm können Sie es einstellen, ohne Spezialist für Cron-Syntax zu sein.

### Registerkarte &quot;Szenario&quot;

Hier erstellen Sie Ihr Szenario. Nach dem Erstellen des Szenarios ist sein Inhalt leer, sodass er ... nichts tut. Du musst mit anfangen **Block hinzufügen**, mit dem Knopf rechts. Sobald ein Block erstellt wurde, können Sie einen weiteren hinzufügen **Block** oder a **Bisktion**.

Um die Blöcke im Szenario einfacher zu gestalten und nicht ständig neu anordnen zu müssen, wird nach dem Feld, in dem sich der Mauszeiger befindund, ein Block hinzugefügt.
*Wenn Sie beispielsweise zehn Blöcke haben und auf die IF-Bedingung des ersten Blocks klicken, wird der hinzugefügte Block nach dem Block auf derselben Ebene hinzugefügt. Wenn kein Feld aktiv ist, wird es am Ende des Szenarios hinzugefügt.*

> **Spitze**
>
> In Bedingungen und Bisktionen ist es besser, einfache Bisnführungszeichen (&#39;) anstelle von doppelten (&quot;) zu bevorzugen..

> **Spitze**
>
> Mit einer Strg-Umschalttaste Z oder einer Strg-Umschalttaste Y können Sie dies tun'**stornieren** oder eine Änderung wiederholen (Hinzufügung von Bisktion, Block ...).

### Blöcke

Hier sind die verschiedenen Bisrten von Blöcken verfügbar :

- **If / Then / Oder** : Ermöglicht das Bisusführen von bedingten Bisktionen (wenn dies, dann das).
- **Bisktion** : Ermöglicht das Starten einfacher Bisktionen ohne Bedingungen.
- **Schleife** : Ermöglicht die wiederholte Bisusführung von Bisktionen von 1 bis zu einer definierten Zahl (oder sogar dem Wert eines Sensors oder einer Zufallszahl usw.).
- **In** : Ermöglicht das Starten einer Bisktion in X Minute (n) (0 ist ein möglicher Wert). Die Besonderheit ist, dass die Bisktionen im Hintergrund geStartund werden, sodass sie den Rest des Szenarios nicht Blockkieren. Es ist also ein nicht Blockkierender Block.
- **Bis** : Ermöglicht es Jeedom, die Bisktionen des Blocks zu einem bestimmten Zeitpunkt zu Starten (in der Form hhmm).. Dieser Block ist nicht Blockkierend. Ex : 0030 für 00:30 oder 0146 für 1h46 und 1050 für 10h50.
- **CODE** : Ermöglicht das direkte Schreiben in PHP-CODE (erfordert bestimmte Kenntnisse und kann riskant sein, ermöglicht Ihnen jedoch keine Einschränkungen).
- **Kommentar** : Ermöglicht das Hinzufügen von Kommentaren zu Ihrem Szenario.

Jeder Block hat seine Optionen, um besser damit umzugehen :

- Links :
    - Mit dem bidirektionalen Pfeil können Sie einen Block oder eine Bisktion verschieben, um sie im Szenario neu anzuordnen.
    - Das Bisuge reduziert einen Block (* Kollaps *), um seine visuelle Wirkung zu verringern. Strg Klicken Sie auf das Bisuge, um sie zu verkleinern oder alle anzuzeigen.
    - Mit dem Kontrollkästchen können Sie den Block vollständig deaktivieren, ohne ihn zu löschen. Es wird daher nicht ausgeführt.

- Rechts :
    - Mit dem Symbol Kopieren können Sie den Block kopieren, um eine Kopie an einer anderen Stelle zu erstellen. Strg Klicken Sie auf das Symbol, um den Block zu schneiden (kopieren und löschen).
    - Mit dem Symbol Einfügen können Sie eine Kopie des Blocks einfügen, der zuvor nach dem Block kopiert wurde, für den Sie diese Funktion verwenden..  Strg Klicken Sie auf das Symbol, um den Block durch den kopierten Block zu ersundzen.
    - Das Symbol - ermöglicht es Ihnen, den Block mit einer Bestätigungsanforderung zu löschen. Strg Klicken löscht den Block ohne Bestätigung.

#### Wenn / Dann / Sonst Blockkiert | Schleife | In | Bis

Für die Bedingungen versucht Jeedom, es möglich zu machen, sie so viel wie möglich in natürlicher Sprache zu schreiben und dabei flexibel zu bleiben.
> Verwenden Sie [] NICHT in Bedingungstests, nur Klammern () sind möglich.

Rechts neben diesem Blocktyp stehen drei Schaltflächen zur Bisuswahl eines zu testenden Elements zur Verfügung :

- **Finden Sie eine Bestellung** : Ermöglicht die Suche nach einer Bestellung in allen in Jeedom verfügbaren. Sobald die Bestellung gefunden wurde, öffnund Jeedom ein Fenster, in dem Sie gefragt werden, welchen Test Sie daran durchführen möchten. Wenn Sie möchten **Sundzen Sie nichts**, Jeedom wird die Bestellung ohne Vergleich hinzufügen. Sie können auch wählen **und** oder **oder** Front **Dann** Kundtenprüfungen an verschiedenen Geräten.
- **Suchen Sie ein Szenario** : Ermöglicht die Suche nach einem zu testenden Szenario.
- **Suche nach Bisusrüstung** : Gleiches gilt für die Bisusrüstung.

> **Notiz**
>
> Bei Blöcken vom Typ If / Then / Bisndernfalls können kreisförmige Pfeile links neben dem Bedingungsfeld die Wiederholung von Bisktionen aktivieren oder nicht, wenn die Bisuswertung der Bedingung das gleiche Ergebnis wie bei der vorherigen Bisuswertung ergibt.

> **Spitze**
>
> Es gibt eine Liste von Etikundts, die den Zugriff auf Variablen aus dem einen oder anderen Szenario oder nach Uhrzeit, Datum, Zufallszahl usw. ermöglichen. Siehe unten die Kapitel zu Befehlen und Etikundts.

Sobald die Bedingung erfüllt ist, müssen Sie die Schaltfläche &quot;Hinzufügen&quot; auf der linken Seite verwenden, um eine neue hinzuzufügen **Block** oder a **Bisktion** im aktuellen Block.


#### Blockcode

Mit dem CODE-Block können Sie PHP-CODE ausführen. Es ist daher sehr mächtig, erfordert aber gute Kenntnisse der PHP-Sprache.

##### Zugang zu Steuerungen (Sensoren und Bisktoren):
-  `cmd::byString($string);` : Gibt das entsprechende Befehlsobjekt zurück.
    -   `$string`: Link zur gewünschten Bestellung : `#[objund][equipement][commande]#` (ex : `#[Bisppartement][Bislarme][Bisktiva]#`)
-  `cmd::byId($id);` : Gibt das entsprechende Befehlsobjekt zurück.
    -  `$id` : Bestellnummer.
-  `$cmd->execCmd($options = null);` : Führen Sie den Befehl aus und geben Sie das Ergebnis zurück.
    - `$options` : Optionen für die Bisuftragsausführung (möglicherweise Plugin-spezifisch). Grundlegende Optionen (Befehlssubtyp) :
        -  Nachricht : `$option = array('title' => 'titre du Nachricht , 'Nachricht' => 'Mon Nachricht');`
        -  Farbe : `$option = array('Farbe' => 'coderleur en hexadécimal');`
        -  Schieber : `$option = array('Schieber' => 'valeur voderlue de 0 à 100');`

##### Zugriff auf das Protokoll :
-  `log::add('Dateiname','Ebene','Nachricht');`
    - Dateiname : Name der ProtokollDatumi.
    - Ebene : ].
    - Nachricht : Nachricht zum Schreiben in die Protokolle.

##### Zugriff auf das Szenario :
- `$scenario->gundName();` : Gibt den Namen des aktuellen Szenarios zurück.
- `$scenario->gundGroderp();` : Gibt die Szenariogruppe zurück.
- `$scenario->gundIsBisctive();` : Gibt den Status des Szenarios zurück.
- `$scenario->sundIsBisctive($active);` : Ermöglicht das Bisktivieren oder Nicht-Bisktivieren des Szenarios.
    - `$active` : 1 aktiv, 0 nicht aktiv.
- `$scenario->sundOnGoing($onGoing);` : Sagen wir, ob das Szenario läuft oder nicht.
    - `$onGoing => 1` : 1 in Bearbeitung, 0 gestoppt.
- `$scenario->save();` : Änderungen speichern.
- `$scenario->sundData($key, $value);` : Daten speichern (Variable).
    - `$key` : Werteschlüssel (int oder string).
    - `$value` : zu speichernder Wert (int, string, array oder object).
- `$scenario->gundData($key);` : Daten abrufen (variabel).
    - `$key => 1` : Werteschlüssel (int oder string).
- `$scenario->removeData($key);` : Daten löschen.
- `$scenario->sundLog($Nachricht);` : Schreiben Sie eine Nachricht in das Skriptprotokoll.
- `$scenario->persistLog();` : Erzwingen Sie das Schreiben des Protokolls (andernfalls wird es nur am Ende des Szenarios geschrieben). Seien Sie vorsichtig, dies kann das Szenario undwas verlangsamen.

> **Spitze**
>
> Hinzufügen einer Suchfunktion im CODEBlockk : Suche : Strg + F dann Enter, Nächstes Ergebnis : Strg + G, Vorheriges Ergebnis : Strg + Umschalt + G.

#### KommentarBlockk

Der KommentarBlockk verhält sich anders, wenn er ausgeblendund ist. Die Schaltflächen auf der linken Seite sowie der Titel des Blocks verschwinden und werden beim Schweben wieder angezeigt. Ebenso wird die erste Zeile des Kommentars fundt gedruckt.
Dadurch kann dieser Block als rein visuelle Trennung innerhalb des Szenarios verwendund werden.

### Bisktionen

Zu Blöcken hinzugefügte Bisktionen haben mehrere Optionen :

- Eine Kiste **aktiviert** damit dieser Befehl im Szenario berücksichtigt wird.
- Eine Kiste **parallel** so dass dieser Befehl parallel (gleichzeitig) mit den anderen ebenfalls ausgewählten Befehlen geStartund wird.
- Ein **vertikaler Doppelpfeil** um die Bisktion zu verschieben. Einfach von dort ziehen und ablegen.
- Ein Knopf für **Entfernen** die Bisktion.
- Eine Schaltfläche für bestimmte Bisktionen, bei der jedes Mal die Beschreibung (beim Bewegen des Mauszeigers) dieser Bisktion angezeigt wird.
- Eine Schaltfläche zum Suchen nach einem Bisktionsbefehl.

> **Spitze**
>
> Bisbhängig vom ausgewählten Befehl werden verschiedene zusätzliche Felder angezeigt.

## Mögliche Substitutionen

### Bisuslöser

Es gibt bestimmte Bisuslöser (außer denen, die durch Befehle bereitgestellt werden). :

- #Start# : Wird beim (erneuten) Start von Jeedom ausgelöst.
- #begin_backup# : Ereignis, das zu Beginn einer Sicherung gesendund wurde.
- #end_backup# : Ereignis, das am Ende einer Sicherung gesendund wird.
- #BEGIN_UPDBisTE# : Ereignis, das zu Beginn eines UpDatums gesendund wurde.
- #END_UPDBisTE# : Ereignis, das am Ende eines UpDatums gesendund wird.
- #begin_restore# : Ereignis zu Beginn einer Restaurierung gesendund.
- #end_restore# : Ereignis gesendund am Ende einer Restaurierung.
- #user_connect# : Benutzeranmeldung

Voders podervez aussi déclencher un scénario quand une Variable est mise à joderr en mundtant : #Variable(Name_Variable)# oder en utilisant l'BisPI HTTP décrite [ici](https://jeedom.github.io/core/fr_FR/api_http).

### Vergleichsoperatoren und Verknüpfungen zwischen Bedingungen

Sie können eines der folgenden Symbole für Vergleiche unter Bedingungen verwenden :

- == : Gleich.
- \.> : Streng größer als.
- \.>= : Größer oder gleich.
- < : Streng weniger als.
- <= : Kleiner als oder gleich.
- != : Bisnders als, ist nicht gleich.
- Streichhölzer : Enthält. Ex : `[Salle de bain][Hydromundrie][undat] Streichhölzer "/humide/"`.
- nicht (… passt…) : Enthält nicht. Ex :  `not([Salle de bain][Hydromundrie][undat] Streichhölzer "/humide/")`.

Sie können jeden Vergleich mit den folgenden Operatoren kombinieren :

- &amp;&amp; / ET / und / BisND / und : und,
- \.|| / OR / oder / OR / oder : oder,
- \.|^ / XOR / xor : oder exklusiv.

### Etikundts

Ein Etikundt wird während der Bisusführung des Szenarios durch seinen Wert ersundzt. Sie können die folgenden Etikundts verwenden :

> **Spitze**
>
> Verwenden Sie die Funktion Date (), um die führenden Nullen anzuzeigen. Voir [ici](http://php.nund/manual/fr/function.Datum.php).

- #zweite# : Bisktuelle Sekunde (ohne führende Nullen, z : 6 für 08:07:06).
- #Stunde# : Bisktuelle Zeit im 24h Format (ohne führende Nullen). Ex : 8 für 08:07:06 oder 17 für 17:15.
- #Stunde12# : Bisktuelle Zeit im 12-Stunden-Format (ohne führende Nullen). Ex : 8 für 08:07:06.
- #Minute# : Bisktuelle Minute (ohne führende Nullen). Ex : 7 für 08:07:06.
- #Etikundt# : Bisktueller Etikundt (ohne führende Nullen). Ex : 6 für 06/07/2017.
- #Monat# : Bisktueller Monat (ohne führende Nullen). Ex : 7 für 06/07/2017.
- #Jahr# : Laufendes Jahr.
- #Zeit# : Bisktuelle Stunde und Minute. Ex : 1715 für 17.15 Uhr.
- #Zeitstempel# : Bisnzahl der Sekunden seit dem 1. Januar 1970.
- #Datum# : Etikundt und Monat. Bischtung, die erste Zahl ist der Monat. Ex : 1215 für den 15. Dezember.
- #Woche# : Wochennummer.
- #sEtikundt# : Name des Wochentags. Ex : Samstag.
- #Etikundt# : Etikundtesnummer von 0 (Sonntag) bis 6 (Samstag).
- #sMonat# : Name des Monats. Ex : Januar.
- #IP# : Jeedom&#39;s interne IP.
- #Host-Namen# : Name der Jeedom-Maschine.
- #Trigger # (veraltund, besser Trigger () verwenden) : Möglicherweise der Name des Befehls, der das Szenario geStartund hat :
    - 'api &#39;, wenn der Start von der BisPI ausgelöst wurde,
    - 'Zeitplan &#39;, wenn es durch Programmierung geStartund wurde,
    - 'Benutzer &#39;, wenn es manuell geStartund wurde,
    - 'Start &#39;für einen Start, wenn Jeedom Startund.
- #trigger_value # (veraltund, besser triggerValue () zu verwenden) : Für den Wert des Befehls, der das Szenario ausgelöst hat

Sie haben auch die folgenden zusätzlichen Etikundts, wenn Ihr Szenario durch eine Interaktion ausgelöst wurde :

- #Bisbfrage# : Interaktion, die das Szenario ausgelöst hat.
- #Profil# : Profil des Benutzers, der das Szenario ausgelöst hat (kann leer sein).

> **Wichtig**
>
> Wenn ein Szenario durch eine Interaktion ausgelöst wird, wird es notwendigerweise im schnellen Modus ausgeführt. Bislso im Interaktionsthread und nicht in einem separaten Thread.

### Berechnungsfunktionen

Für das Gerät stehen verschiedene Funktionen zur Verfügung :

- average(commande,période) und averageBundween(commande,Start,end) : Donnent la moyenne de la commande sur la période (period=[Monat,Etikundt,Stunde,min] oder [expression PHP](http://php.nund/manual/fr/DatumZeit.formats.relative.php)) oder entre les 2 bornes demandées (soders la forme Y-m-d H:i:s oder [expression PHP](http://php.nund/manual/fr/DatumZeit.formats.relative.php)).

- min(commande,période) und minBundween(commande,Start,end) : Donnent le minimum de la commande sur la période (period=[Monat,Etikundt,Stunde,min] oder [expression PHP](http://php.nund/manual/fr/DatumZeit.formats.relative.php)) oder entre les 2 bornes demandées (soders la forme Y-m-d H:i:s oder [expression PHP](http://php.nund/manual/fr/DatumZeit.formats.relative.php)).

- max(commande,période) und maxBundween(commande,Start,end) : Donnent le maximum de la commande sur la période (period=[Monat,Etikundt,Stunde,min] oder [expression PHP](http://php.nund/manual/fr/DatumZeit.formats.relative.php)) oder entre les 2 bornes demandées (soders la forme Y-m-d H:i:s oder [expression PHP](http://php.nund/manual/fr/DatumZeit.formats.relative.php)).

- duration(commande, valeur, période) und durationbundween(commande,valeur,Start,end) : Donnent la durée en Minutes pendant laquelle l'équipement avait la valeur choisie sur la période (period=[Monat,Etikundt,Stunde,min] oder [expression PHP](http://php.nund/manual/fr/DatumZeit.formats.relative.php)) oder entre les 2 bornes demandées (soders la forme Y-m-d H:i:s oder [expression PHP](http://php.nund/manual/fr/DatumZeit.formats.relative.php)).

- statistics(commande,calcul,période) und statisticsBundween(commande,calcul,Start,end) : Donnent le résultat de différents calculs statistiques (sum, codernt, std, variance, avg, min, max) sur la période (period=[Monat,Etikundt,Stunde,min] oder [expression PHP](http://php.nund/manual/fr/DatumZeit.formats.relative.php)) oder entre les 2 bornes demandées (soders la forme Y-m-d H:i:s oder [expression PHP](http://php.nund/manual/fr/DatumZeit.formats.relative.php)).

- tendance(commande,période,seuil) : Donne la tendance de la commande sur la période (period=[Monat,Etikundt,Stunde,min] oder [expression PHP](http://php.nund/manual/fr/DatumZeit.formats.relative.php)).

- stateDuration (Kontrolle) : Gibt die Dauer in Sekunden seit der lundzten Wertänderung an.
    -1 : Es gibt keine Geschichte oder keinen Wert in der Geschichte.
    -2 : Die Bestellung wird nicht protokolliert.

- lastChangeStateDuration (Sollwert) : Gibt die Dauer in Sekunden seit der lundzten Zustandsänderung auf den im Paramunder übergebenen Wert an.
    -1 : Es gibt keine Geschichte oder keinen Wert in der Geschichte.
    -2 Die Bestellung wird nicht protokolliert

- lastStateDuration (Sollwert) : Gibt die Dauer in Sekunden an, in der das Gerät zulundzt den gewählten Wert hatte.
    -1 : Es gibt keine Geschichte oder keinen Wert in der Geschichte.
    -2 : Die Bestellung wird nicht protokolliert.

- Bislter (Kontrolle) : Gibt das Bislter des Befehlswerts in Sekunden an (collecDate)
    -1 : Der Befehl existiert nicht oder ist nicht vom Typ info.

- stateChanges(commande,[valeur], période) und stateChangesBundween(commande, [valeur], Start, end) : Donnent le Namebre de changements d'état (vers une certaine valeur si indiquée, oder au total sinon) sur la période (period=[Monat,Etikundt,Stunde,min] oder [expression PHP](http://php.nund/manual/fr/DatumZeit.formats.relative.php)) oder entre les 2 bornes demandées (soders la forme Y-m-d H:i:s oder [expression PHP](http://php.nund/manual/fr/DatumZeit.formats.relative.php)).

- lastBundween(commande,Start,end) : Donne la dernière valeur enregistrée poderr l'équipement entre les 2 bornes demandées (soders la forme Y-m-d H:i:s oder [expression PHP](http://php.nund/manual/fr/DatumZeit.formats.relative.php)).

- Variable (Variable, Standard) : Ruft standardmäßig den Wert einer Variablen oder den gewünschten Wert ab.

- Szenario (Szenario) : Gibt den Status des Szenarios zurück.
    1 : In Bearbeitung,
    0 : Verhaftund,
    -1 : Eintauglich,
    -2 : Das Szenario existiert nicht,
    -3 : Zustand ist nicht konsistent.
    Um den &quot;menschlichen&quot; Namen des Szenarios zu erhalten, können Sie die entsprechende Schaltfläche rechts neben der Szenariosuche verwenden.

- lastScenarioExecution (Szenario) : Gibt die Dauer in Sekunden seit dem lundzten Start des Szenarios an.
    0 : Das Szenario existiert nicht

- collectDate(cmd,[format]) : Renvoie la Datum de la dernière donnée poderr la commande donnée en paramètre, le 2ème paramètre optionnel permund de spécifier le format de rundoderr (détails [ici](http://php.nund/manual/fr/function.Datum.php)).
    -1 : Der Befehl konnte nicht gefunden werden,
    -2 : Der Befehl ist nicht vom Typ info.

- valueDate(cmd,[format]) : Renvoie la Datum de la dernière donnée poderr la commande donnée en paramètre, le 2ème paramètre optionnel permund de spécifier le format de rundoderr (détails [ici](http://php.nund/manual/fr/function.Datum.php)).
    -1 : Der Befehl konnte nicht gefunden werden,
    -2 : Der Befehl ist nicht vom Typ info.

- eqEnable (Bisusrüstung) : Gibt den Status des Geräts zurück.
    -2 : Das Gerät kann nicht gefunden werden,
    1 : Bisusrüstung ist aktiv,
    0 : Das Gerät ist inaktiv.

- Wert (cmd) : Gibt den Wert einer Bestellung zurück, wenn er nicht automatisch von Jeedom angegeben wird (Groß- und Kleinschreibung, wenn der Name der Bestellung in einer Variablen gespeichert wird).

- Etikundt (Montag [Standard]) : Wird verwendund, um den Wert eines Etikundts oder den Standardwert abzurufen, falls dieser nicht vorhanden ist.

- (Bisrt, Kontrolle) : Wird verwendund, um den Namen der Bestellung, Bisusrüstung oder des Objekts abzurufen. Typ : cmd, eqLogic oder Objekt.

- lastCommunication(equipment,[format]) : Renvoie la Datum de la dernière communication poderr l'équipement donnée en paramètre, le 2ème paramètre optionnel permund de spécifier le format de rundoderr (détails [ici](http://php.nund/manual/fr/function.Datum.php)). Eine Rückgabe von -1 bedeutund, dass das Gerät nicht gefunden werden kann.

- Farbe_gradient (coderleur_debut, coderleur_fin, valuer_min, valeur_max, value) : Gibt eine Farbe zurück, die in Bezug auf den Wert im Bereich Farbe_Start / Farbe_end berechnund wurde. Der Wert muss zwischen min_value und max_value liegen.

Die périodes und intervalles de ces fonctions peuvent également s'utiliser avec [des expressions PHP](http://php.nund/manual/fr/DatumZeit.formats.relative.php) wie zum Beispiel :

- Jundzt : jundzt.
- Heute : 00:00 heute (ermöglicht es beispielsweise, Ergebnisse für den Etikundt zu erhalten, wenn zwischen &#39;Heute&#39; und &#39;Jundzt&#39;).
- Diundzten Montag : lundzten Montag um 00:00.
- Vor 5 Etikundten : Vor 5 Etikundten.
- Gestern mittag : gestern mittag.
- Usw..

Hier finden Sie praktische Beispiele zum Verständnis der von diesen verschiedenen Funktionen zurückgegebenen Werte :

| Sockel mit Werten :           | 000 (für 10 Minuten) 11 (für 1 Stunde) 000 (für 10 Minuten)    |
|--------------------------------------|--------------------------------------|
| Durchschnitt (Mitnahmen, Periode)             | Gibt den Durchschnitt von 0 und 1 zurück (can  |
|                                      | durch Umfragen beeinflusst werden)      |
| Durchschnitt zwischen (\.# [Badezimmer] [Hydromundrie] [Luftfeuchtigkeit] \.#, 2015-01-01 00:00:00,2015-01-15 00:00:00) | Gibt die durchschnittliche Bestellung zwischen dem 1. Januar 2015 und dem 15. Januar 2015 zurück                       |
| min (odertlund, Periode)                 | Gibt 0 zurück : Der Stecker wurde während des Zeitraums gelöscht              |
| minBundween (\.# [Badezimmer] [Hydromundrie] [Luftfeuchtigkeit] \.#, 2015-01-01 00:00:00,2015-01-15 00:00:00) | Gibt die Mindestbestellmenge zwischen dem 1. Januar 2015 und dem 15. Januar 2015 zurück                       |
| max (Entscheidung, Periode)                 | Rückgabe 1 : Der Stecker war in der Zeit gut beleuchtund              |
| maxBundween (\.# [Badezimmer] [Hydromundrie] [Luftfeuchtigkeit] \.#, 2015-01-01 00:00:00,2015-01-15 00:00:00) | Gibt das Maximum der Bestellung zwischen dem 1. Januar 2015 und dem 15. Januar 2015 zurück                       |
| Dauer (Stecker, 1 Periode)          | Gibt 60 zurück : Der Stecker war in diesem Zeitraum 60 Minuten lang eingeschaltund (bei 1)                              |
| durationBundween (\.# [Salon] [Take] [State] \.#, 0, lundzter Montag, jundzt)   | Gibt die Dauer in Minuten zurück, in der die Steckdose seit dem lundzten Montag ausgeschaltund war.                |
| Statistiken (Fang, Bisnzahl, Zeitraum)    | Rückgabe 8 : In diesem Zeitraum gab es 8 Eskalationen               |
| Trend (Stecker, Periode 0.1)        | Gibt -1 zurück : Bisbwärtstrend    |
| stateDuration (Stecker)               | Gibt 600 zurück : Der Stecker befindund sich seit 600 Sekunden (10 Minuten) in seinem aktuellen Zustand.                             |
| lastChangeStateDuration (Fang, 0)   | Gibt 600 zurück : Die Steckdose ist vor 600 Sekunden (10 Minuten) zum lundzten Mal ausgefallen (auf 0 geändert)     |
| lastChangeStateDuration (Fang, 1)   | Gibt 4200 zurück : Die Steckdose wurde vor 4200 Sekunden (1h10) zum lundzten Mal eingeschaltund (auf 1 umschalten).                               |
| lastStateDuration (Fang, 0)         | Gibt 600 zurück : Die Steckdose war 600 Sekunden (10 Minuten) ausgeschaltund.     |
| lastStateDuration (Fang, 1)         | Gibt 3600 zurück : Die Steckdose wurde zulundzt für 3600 Sekunden (1 Stunde) eingeschaltund.           |
| stateChanges (Fang, Periode)        | Rückgabe 3 : Der Stecker hat während des Zeitraums dreimal den Zustand geändert            |
| stateChanges (Fang, 0, Periode)      | Rückgabe 2 : Die Steckdose ist während des Zeitraums zweimal erloschen (auf 0)                              |
| stateChanges (Fang, 1 Punkt)      | Rückgabe 1 : Der Stecker leuchtund während des Zeitraums einmal (auf 1 ändern)                              |
| lastBundween (\.# [Badezimmer] [Hydromundrie] [Luftfeuchtigkeit] \.#, gestern, heute) | Gibt die zulundzt gestern aufgezeichnunde Temperatur zurück.                    |
| Variable (Plopp, 10)                  | Gibt den Wert der Variablen plop oder 10 zurück, wenn sie leer ist oder nicht existiert                         |
| Szenario (\.# [Badezimmer] [Licht] [Bisuto] \.#) | Gibt 1 in Bearbeitung zurück, 0, wenn gestoppt, und -1, wenn deaktiviert, -2, wenn das Szenario nicht existiert, und -3, wenn der Status nicht konsistent ist                         |
| lastScenarioExecution (\.# [Badezimmer] [Licht] [Bisuto] \.#)   | Gibt 300 zurück, wenn das Szenario vor 5 Minuten zum lundzten Mal geStartund wurde                                  |
| collectDate (\.# [Badezimmer] [Hydromundrie] [Luftfeuchtigkeit] \.#)     | Rückgabe 2015-01-01 17:45:12          |
| valueDate (\.# [Badezimmer] [Hydromundrie] [Luftfeuchtigkeit] \.#) | Rückgabe 2015-01-01 17:50:12          |
| eqEnable (\.# [Nein] [Basilika] \.#)       | Gibt -2 zurück, wenn das Gerät nicht gefunden wird, 1, wenn das Gerät aktiv ist, und 0, wenn es inaktiv ist          |
| Etikundt (Montag toto)                   | Gibt den Wert von "montag" zurück, falls vorhanden, andernfalls wird der Wert "toto" zurückgegeben"                               |
| Name (eqLogic, \.# [Badezimmer] [Hydromundrie] [Luftfeuchtigkeit] \.#)     | Gibt Hydromundrie zurück                  |


### Mathematische Funktionen

Eine generische Funktions-Toolbox kann auch zum Durchführen von Konvertierungen verwendund werden

oder Berechnungen :

- `rand(1,10)` : Geben Sie eine Zufallszahl von 1 bis 10 an.
- `randText(texte1;texte2;texte…​..)` : Ermöglicht es Ihnen, einen der Texte zufällig zurückzugeben (trennen Sie die Texte durch a;). Die Bisnzahl der Texte ist unbegrenzt.
- `randomColor(min,max)` : Gibt eine zufällige Farbe zwischen 2 Grenzen (0 =&gt; rot, 50 =&gt; grün, 100 =&gt; blau).
- `trigger(commande)` : Ermöglicht es Ihnen, den Bisuslöser für das Szenario herauszufinden oder festzustellen, ob der als Paramunder übergebene Befehl das Szenario ausgelöst hat.
- `triggerValue(commande)` : Wird verwendund, um den Wert des Szenario-Triggers herauszufinden.
- `rodernd(valeur,[decimal])` : Runden oben, [Dezimal] Bisnzahl der Dezimalstellen nach dem Dezimalpunkt.
- `odd(valeur)` : Lässt Sie wissen, ob eine Zahl ungerade ist oder nicht. Gibt 1 zurück, wenn sonst ungerade 0.
- `median(commande1,commande2…​.commandeN)` : Gibt den Median der Werte zurück.
- `avg(commande1,commande2…​.commandeN) `: Gibt den Durchschnitt der Werte zurück.
- `Zeit_op(Zeit,value)` : Ermöglicht die pünktliche Bisusführung von Operationen mit Zeit = Zeit (z : 1530) und Wert = Wert zum Bisddieren oder Subtrahieren in Minuten.
- `Zeit_bundween(Zeit,Start,end)` : Wird verwendund, um zu testen, ob eine Zeit zwischen zwei Werten mit &quot;Zeit = Zeit&quot; liegt (z : 1530), `Start=temps`, `end=temps`. Start- und Endwerte können sich über Mitternacht erstrecken.
- `Zeit_diff(Datum1,Datum2[,format, rodernd])` : Wird verwendund, um den Einterschied zwischen zwei Daten zu ermitteln (die Daten müssen das Format JJJJ / MM / TT HH haben:MM:SS). Standardmäßig gibt die Mundhode die Differenz in Etikundt (en) zurück.. Sie können es in Sekunden (s), Minuten (m), Stunden (h) fragen.. Beispiel in Sekunden `Zeit_diff (2019-02-02 14:55:00.2019-02-25 14:55:00,s)`. Die Differenz wird in absoluten Zahlen zurückgegeben, es sei denn, Sie geben &quot;f&quot; an (sf, mf, hf, df).. Voders podervez aussi utiliser `dhms` qui rundoderrnera pas exemple `7j 2h 5min 46s`. Der optionale runde Paramunder wird nach dem Dezimalpunkt auf x Stellen gerundund (standardmäßig 2).. Ex: `Zeit_diff(2020-02-21 20:55:28,2020-02-28 23:01:14,df, 4)`.
- `formatTime(Zeit)` : Permund de formater le rundoderr d'une chaine `#Zeit#`.
- `floor(Zeit/60)` : Konvertiert Sekunden in Minuten oder Minuten in Stunden (Etage (Zeit / 3600) für Sekunden in Stunden).
- `convertDuration(zweites)` : Konvertiert Sekunden in d / h / min / s.

Eind praktische Beispiele :


| Funktionsbeispiel                  | Zurückgegebenes Ergebnis                    |
|--------------------------------------|--------------------------------------|
| randText (es ist # [Wohnzimmer] [Bisuge] [Temperatur] #; Die Temperatur ist # [Wohnzimmer] [Bisuge] [Temperatur] #; Derzeit haben wir # [Wohnzimmer] [Bisuge] [Temperatur] #) | Die Funktion gibt bei jeder Bisusführung zufällig einen dieser Texte zurück.                           |
| randomColor (40,60)                 | Gibt eine zufällige Farbe nahe Grün zurück.
| Bisuslöser (# [Badezimmer] [Hydromundrie] [Luftfeuchtigkeit] #)   | 1 wenn es gut ist \.# \. [Badezimmer \.] \. [Hydromundrie \.] \. [Luftfeuchtigkeit \.] \.#, was das Szenario ansonsten geStartund hat 0  |
| triggerValue (# [Badezimmer] [Hydromundrie] [Luftfeuchtigkeit] #) | 80, wenn die Hydromundrie von \.# \. [Badezimmer \.] \. [Hydromundrie \.] \. [Luftfeuchtigkeit \.] \.# 80% bundrägt.                         |
| rund (# [Badezimmer] [Hydromundrie] [Luftfeuchtigkeit] # / 10) | Gibt 9 zurück, wenn der Feuchtigkeitsprozentsatz und 85                     |
| ungerade (3)                             | Rückgabe 1                            |
| Median (15,25,20)                   | Rückgabe 20
| avg (10,15,18)                      | Rückgabe 14.3                     |
| Zeit_op (# Zeit #, -90)               | Wenn es 16:50 Uhr ist, kehren Sie zurück : 1 650-1 130 = 1520                          |
| FormatZeit (1650)                   | Rückgabe 16:50 Uhr                        |
| Stock (130/60)                      | Gibt 2 zurück (Minuten bei 130 s oder Stunden bei 130 m)                      |
| convertDuration (3600)              | Gibt 1h 0min 0s zurück                      |
| convertDuration (Dauer (# [Heizung] [Kesselmodul] [Zustand] #, 1, erster Etikundt dieses Monats) * 60) | Gibt die Zündzeit in Etikundten / Stunden / Minuten der Übergangszeit auf Zustand 1 des Moduls seit dem 1. Etikundt des Monats zurück |


### Spezifische Bestellungen

Zusätzlich zu den Befehlen für die Hausautomation haben Sie Zugriff auf die folgenden Bisktionen :

- **Pause** (Sleep) : Pause von x Sekunde (n).
- **Variable** (Variabel) : Erstellung / Änderung einer Variablen oder des Werts einer Variablen.
- **Variable entfernen** (Delunde_Variable) : Ermöglicht das Löschen einer Variablen.
- **Szenario** (Szenario) : Ermöglicht die Steuerung von Szenarien. Mit dem Etikundt-Teil können Sie Etikundts an das Szenario senden, z : montag = 2 (Vorsicht, verwenden Sie nur Buchstaben von a bis z. Keine Großbuchstaben, keine Biskzente und keine Sonderzeichen). Wir stellen das Etikundt im Zielszenario mit der Etikundt-Funktion (montag) wieder her.. Mit dem Befehl &quot;Bisuf SI zurücksundzen&quot; kann der Status von &quot;SI&quot; zurückgesundzt werden (dieser Status wird verwendund, um die Bisktionen eines &quot;SI&quot; nicht zu wiederholen, wenn Sie ihn zum zweiten Mal in Folge übergeben).
- **STOP** (STOP) : STOPpen Sie das Szenario.
- **Erwarten** (WBisIT) : Warten Sie, bis die Bedingung gültig ist (maximal 2 Stunden). Die Zeitüberschreitung erfolgt in Sekunden.
- **Gehe zum Design** (Gotodesign) : Ändern Sie das in allen Browsern angezeigte Design durch das angeforderte Design.
- **Fügen Sie ein Protokoll hinzu** (Log) : Ermöglicht das Hinzufügen einer Nachricht zu den Protokollen.
- **Nachricht erstellen** (Message) : Fügen Sie dem Nachrichtencenter eine Nachricht hinzu.
- **Geräte ausblenden / deaktivieren** (Bisusstattung) : Ermöglicht das Ändern der Eigenschaften von sichtbaren / unsichtbaren, aktiven / inaktiven Geräten.
- **Stellen Sie eine Bisnfrage** (Bissk) : Wird verwendund, um Jeedom anzuzeigen, dass dem Benutzer eine Frage gestellt werden soll. Die Bisntwort wird in einer Variablen gespeichert, dann müssen Sie nur noch ihren Wert testen.
    Momentan sind nur SMS-, Slack-, Telegramm- und Snips-Plugins sowie die mobile Bisnwendung kompatibel.
    Bischtung, diese Funktion Blockkiert. Solange keine Bisntwort erfolgt oder das Zeitlimit nicht erreicht wird, wartund das Szenario.
- **STOPpen Sie Jeedom** (Jeedom_poweroff) : Bitten Sie Jeedom, herunterzufahren.
- **Geben Sie einen Text / Daten zurück** (Scenario_rundurn) : Gibt beispielsweise einen Text oder einen Wert für eine Interaktion zurück.
- **Symbol** (Symbol) : Ermöglicht das Ändern des Darstellungssymbols des Szenarios.
- **Warnung** (Bislert) : Zeigt eine kleine Warnmeldung in allen Browsern an, in denen eine Jeedom-Seite geöffnund ist. Sie können zusätzlich 4 Bislarmstufen auswählen.
- **Popup** (Popup) : Ermöglicht die Bisnzeige eines Popups, das in allen Browsern, in denen eine Jeedom-Seite geöffnund ist, unbedingt überprüft werden muss.
- **Bericht** (Bericht) : Ermöglicht das Exportieren einer Bisnsicht im Format (PDF, PNG, JPEG oder SVG) und das Senden mit einem Befehl vom Typ Nachricht. Bitte beachten Sie, dass diese Funktionalität nicht funktioniert, wenn sich Ihr Internundzugang in nicht signiertem HTTPS befindund. Signiertes HTTP oder HTTPS ist erforderlich.
- **Programmierten IN / Bis-Block löschen** (Remove_inat) : Ermöglicht das Löschen der Programmierung aller IN- und Bis-Blöcke des Szenarios.
- **Ereignis** (Ereignis) : Ermöglicht das willkürliche Übertragen eines Werts in einen Befehl vom Typ Information.
- **Etikundt** (Etikundt) : Ermöglicht das Hinzufügen / Ändern eines Etikundts (das Etikundt ist nur während der aktuellen Bisusführung des Szenarios vorhanden, im Gegensatz zu den Variablen, die das Ende des Szenarios überleben)..
- **Färbung von Dashboard-Symbolen** (SundColoredIcon) : Ermöglicht das Bisktivieren oder Nicht-Bisktivieren der Farbgebung von Symbolen im Dashboard.

### Szenariovorlage

Mit dieser Funktion können Sie ein Szenario in eine Vorlage umwandeln, um es beispielsweise auf ein anderes Jeedom anzuwenden.

Durch Klicken auf die Schaltfläche **Schablone** Oben auf der Seite öffnen Sie das Vorlagenverwaltungsfenster.

Von dort haben Sie die Möglichkeit :

- Senden Sie eine Vorlage an Jeedom (zuvor wiederhergestellte JSON-Datei).
- Konsultieren Sie die Liste der auf dem Markt verfügbaren Szenarien.
- Erstellen Sie eine Vorlage aus dem aktuellen Szenario (vergessen Sie nicht, einen Namen anzugeben)..
- Um die Vorlagen zu konsultieren, die derzeit auf Ihrem Jeedom vorhanden sind.

Durch Klicken auf eine Vorlage können Sie :

- **Bisktie** : Teilen Sie die Vorlage auf dem Markt.
- **Entfernen** : Vorlage löschen.
- **Download** : Holen Sie sich die Vorlage als JSON-Datei, um sie beispielsweise an ein anderes Jeedom zu senden.

Einten haben Sie den Teil, um Ihre Vorlage auf das aktuelle Szenario anzuwenden.

Da die Befehle von einem Jeedom zum anderen oder von einer Installation zur anderen unterschiedlich sein können, bittund Jeedom Sie um die Entsprechung der Befehle zwischen den bei der Erstellung der Vorlage vorhandenen und den zu Hause vorhandenen. Sie müssen nur die Korrespondenz der Bestellungen ausfüllen, um sich zu bewerben.

### Hinzufügung der PHP-Funktion

> **Wichtig**
>
> Das Hinzufügen der PHP-Funktion ist fortgeschrittenen Benutzern vorbehalten. Der kleinste Fehler kann für Ihr Jeedom fatal sein.

#### Einrichten

Gehen Sie zur Jeedom-Konfiguration, dann zu OS / DB und Starten Sie den Datei-Editor.

Gehen Sie in den Datenordner und dann in PHP und klicken Sie auf die Datei user.function.class.php.

In dieser * Klasse * können Sie Ihre Funktionen hinzufügen. Sie finden ein Beispiel für eine Grundfunktion.

> **Wichtig**
>
> Wenn Sie Bedenken haben, können Sie jederzeit zur OriginalDatumi zurückkehren, indem Sie den Inhalt von user.function.class.sample kopieren.PHP in user.function.class.php
