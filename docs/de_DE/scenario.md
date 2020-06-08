# Szenarien
**Werkzeuge → Szenarien**

<small>[Raccourcis clavier/.souris](shortcuts.md)</.small>

Die Szenarien sind ein echtes Gehirn der Hausautomation und ermöglichen es, auf eine Art und Weise mit der realen Welt zu interagieren *klug*.

## Gestion

Dort finden Sie die Liste der Szenarien Ihres Jeedom sowie Funktionen, um diese am besten zu verwalten :

- **Hinzufügen** : Erstellen Sie ein Szenario. Die Vorgehensweise wird im nächsten Kapitel beschrieben.
- **Szenarien deaktivieren** : Deaktiviert alle Szenarien. Selten verwendet und wissentlich, da kein Szenario mehr ausgeführt wird.
- **Übersicht** : Ermöglicht einen Überblick über alle Szenarien. Sie können die Werte ändern **Aktiva**, **sichtbar**, **Multi-Launch**, **synchroner Modus**, **Log** und **Zeitleiste** (Diese Parameter werden im folgenden Kapitel beschrieben). Sie können auch auf die Protokolle für jedes Szenario zugreifen und sie einzeln starten.

## Meine Szenarien

In diesem Abschnitt finden Sie die **Liste der Szenarien** dass du erstellt hast. Sie werden nach ihren klassifiziert **Gruppe**, möglicherweise für jeden von ihnen definiert. Jedes Szenario wird mit seinem angezeigt **Name** und seine **übergeordnetes Objekt**. Die **ausgegraute Szenarien** sind diejenigen, die deaktiviert sind.

> **Spitze**
>
> Sie können ein Szenario öffnen, indem Sie dies tun :
> - Klicken Sie auf eine davon.
> - Strg Clic oder Clic Center, um es in einem neuen Browser-Tab zu öffnen.

Sie haben eine Suchmaschine, um die Anzeige von Szenarien zu filtern. Die Escape-Taste bricht die Suche ab.
Rechts neben dem Suchfeld befinden sich drei Schaltflächen, die an mehreren Stellen in Jeedom gefunden wurden:
- Das Kreuz, um die Suche abzubrechen.
- Der geöffnete Ordner zum Entfalten aller Bedienfelder und Anzeigen aller Szenarien.
- Der geschlossene Ordner zum Falten aller Panels.

Sobald Sie ein Szenario konfiguriert haben, haben Sie ein Kontextmenü mit der rechten Maustaste auf die Registerkarten des Szenarios. Sie können auch ein Strg-Klick- oder Klick-Center verwenden, um ein anderes Szenario direkt in einer neuen Browser-Registerkarte zu öffnen.

## Schaffung | Szenario bearbeiten

Nach dem Klicken auf **Hinzufügen**, Sie müssen den Namen Ihres Szenarios auswählen. Sie werden dann zur Seite mit den allgemeinen Parametern weitergeleitet.
Davor gibt es oben auf der Seite einige nützliche Funktionen zum Verwalten dieses Szenarios :

- **Identifikation** : Neben dem Wort **General**, Dies ist die Szenariokennung.
- **Status** : *Verhaftet* oder *In Bearbeitung*, Es zeigt den aktuellen Status des Szenarios an.
- **Vorheriger / nächster Zustand** : Aktion abbrechen / wiederholen.
- **Block hinzufügen** : Ermöglicht das Hinzufügen eines Blocks des gewünschten Typs zum Szenario (siehe unten)).
- **Log** : Zeigt die Szenarioprotokolle an.
- **Duplikat** : Kopieren Sie das Szenario, um ein neues mit einem anderen Namen zu erstellen.
- **Verbindungen** : Ermöglicht das Anzeigen des Diagramms der Elemente, die sich auf das Szenario beziehen.
- **Textbearbeitung** : Zeigt ein Fenster an, in dem das Szenario in Form von Text / JSON bearbeitet werden kann. Vergiss nicht zu sparen.
- **Export** : Ermöglicht es Ihnen, eine reine Textversion des Szenarios zu erhalten.
- **Schablone** : Ermöglicht den Zugriff auf die Vorlagen und die Anwendung einer Vorlage auf das Szenario aus dem Markt. (am Ende der Seite erklärt).
- **Suche** : Entfaltet ein Suchfeld für die Suche im Szenario. Diese Suche entfaltet die reduzierten Blöcke bei Bedarf und faltet sie nach der Suche zurück.
- **Ausführen** : Ermöglicht das manuelle Starten des Szenarios (unabhängig von den Auslösern)). Speichern Sie vorher, um die Änderungen zu berücksichtigen.
- **Entfernen** : Szenario löschen.
- **Speichern** : Speichern Sie die vorgenommenen Änderungen.

> **Tipps**
>
> Zwei Tools sind für Sie auch beim Einrichten von Szenarien von unschätzbarem Wert :
    > - Die Variablen, sichtbar in **Werkzeuge → Variablen**
    > - Der Ausdruckstester, auf den über zugegriffen werden kann **Werkzeuge → Ausdruckstester**
>
> Ein **Strg Klicken Sie auf die Schaltfläche Ausführen** Mit dieser Option können Sie das Szenarioprotokoll direkt speichern, ausführen und anzeigen (wenn die Protokollebene nicht Keine ist).

## Registerkarte &quot;Allgemein&quot;

In der Registerkarte **General**, Wir finden die Hauptparameter des Szenarios :

- **Name des Szenarios** : Der Name Ihres Szenarios.
- **Name, der angezeigt werden soll** : Der Name, der für die Anzeige verwendet wird. Wenn nicht abgeschlossen, wird optional der Name des Szenarios verwendet.
- **Gruppe** : Ermöglicht das Organisieren der Szenarien, indem Sie sie in Gruppen einteilen (sichtbar auf der Szenarioseite und in ihren Kontextmenüs).
- **Aktiva** : Aktivieren Sie das Szenario. Wenn nicht aktiv, wird es von Jeedom unabhängig vom Triggermodus nicht ausgeführt.
- **Sichtbar** : Wird verwendet, um das Szenario sichtbar zu machen (Dashboard)).
- **übergeordnetes Objekt** : Zuordnung zu einem übergeordneten Objekt. Es wird dann entsprechend diesem Elternteil sichtbar sein oder nicht.
- **Zeitüberschreitung in Sekunden (0 = unbegrenzt)** : Die maximal zulässige Ausführungszeit für dieses Szenario. Nach dieser Zeit wird die Ausführung des Szenarios unterbrochen.
- **Multi-Launch** : Aktivieren Sie dieses Kontrollkästchen, wenn das Szenario mehrmals gleichzeitig gestartet werden soll.
>**Wichtig**
>
>Der Mehrfachstart funktioniert im Sekundentakt, dh wenn Sie zwei Starts in derselben Sekunde haben, ohne dass das Kontrollkästchen aktiviert ist, gibt es immer noch zwei Starts des Szenarios (wenn dies der Fall sein sollte)). In ähnlicher Weise ist es bei mehreren Starts in derselben Sekunde möglich, dass einige Starts die Tags verlieren. Fazit Sie MÜSSEN ABSOLUT mehrere Starts in denselben Sekunden vermeiden.
- **Synchroner Modus** : Starten Sie das Szenario im aktuellen Thread anstelle eines dedizierten Threads. Erhöht die Geschwindigkeit, mit der das Szenario gestartet wird, kann jedoch das System instabil machen.
- **Log** : Der für das Szenario gewünschte Protokolltyp. Sie können das Protokoll des Szenarios ausschneiden oder im Gegenteil unter Analyse → Echtzeit anzeigen.
- **Zeitleiste** : Behalten Sie das Szenario in der Zeitleiste im Auge (siehe Verlaufsdokument)).
- **Symbol** : Ermöglicht die Auswahl eines Symbols für das Szenario anstelle des Standardsymbols.
- **Beschreibung** : Ermöglicht das Schreiben eines kleinen Textes zur Beschreibung Ihres Szenarios.
- **Szenariomodus** : Das Szenario kann programmiert, ausgelöst oder beides sein. Sie haben dann die Wahl, die Auslöser (maximal 15 Auslöser) und die Programmierung (en) anzugeben).

> **Spitze**
>
> Bedingungen können jetzt im ausgelösten Modus eingegeben werden. Zum Beispiel : ``#[Garage][Open Garage][Ouverture]# == 1``
> Aufmerksamkeit : Sie können maximal 28 Trigger / Programmierungen für ein Szenario haben.

> **Tippmodus programmiert**
>
> Der geplante Modus verwendet die Syntax **Cron**. Beispielsweise können Sie mit alle 20 Minuten ein Szenario ausführen  ``*/.20 * * * *``, oder um 5 Uhr morgens, um eine Vielzahl von Dingen für den Tag mit zu erledigen ``0 5 * * *``. Die ? Rechts neben einem Programm können Sie es einstellen, ohne Spezialist für Cron-Syntax zu sein.

## Registerkarte &quot;Szenario&quot;

Hier erstellen Sie Ihr Szenario. Nach dem Erstellen des Szenarios ist sein Inhalt leer, sodass er ... nichts tut. Du musst mit anfangen **Block hinzufügen**, mit dem Knopf rechts. Sobald ein Block erstellt wurde, können Sie einen weiteren hinzufügen **Block** oder a **Aktion**.

Um die Blöcke im Szenario einfacher zu gestalten und nicht ständig neu anordnen zu müssen, wird nach dem Feld, in dem sich der Mauszeiger befindet, ein Block hinzugefügt.
*Wenn Sie beispielsweise zehn Blöcke haben und auf die IF-Bedingung des ersten Blocks klicken, wird der hinzugefügte Block nach dem Block auf derselben Ebene hinzugefügt. Wenn kein Feld aktiv ist, wird es am Ende des Szenarios hinzugefügt.*

> **Spitze**
>
> Bei Bedingungen und Aktionen ist es besser, einfache Anführungszeichen (') anstelle von doppelten zu bevorzugen (").

> **Spitze**
>
> Mit einer Strg-Umschalttaste Z oder einer Strg-Umschalttaste Y können Sie dies tun'**stornieren** oder eine Änderung wiederholen (Aktion hinzufügen, blockieren...).

## Blöcke

Hier sind die verschiedenen Arten von Blöcken verfügbar :

- **If / Then / Oder** : Ermöglicht das Ausführen von Aktionen unter bestimmten Bedingungen (wenn dies, dann das).
- **Aktion** : Ermöglicht das Starten einfacher Aktionen ohne Bedingungen.
- **Schleife** : Ermöglicht die wiederholte Ausführung von Aktionen von 1 bis zu einer definierten Zahl (oder sogar dem Wert eines Sensors oder einer Zufallszahl…)).
- **In** : Ermöglicht das Starten einer Aktion in X Minute (n) (0 ist ein möglicher Wert). Die Besonderheit ist, dass die Aktionen im Hintergrund gestartet werden, sodass sie den Rest des Szenarios nicht blockieren. Es ist also ein nicht blockierender Block.
- **Bis** : Ermöglicht es Jeedom, die Aktionen des Blocks zu einem bestimmten Zeitpunkt (in der Form hhmm) zu starten). Dieser Block ist nicht blockierend. Ex : 0030 für 00:30 oder 0146 für 1h46 und 1050 für 10h50.
- **CODE** : Ermöglicht das direkte Schreiben in PHP-Code (erfordert bestimmte Kenntnisse und kann riskant sein, ermöglicht Ihnen jedoch keine Einschränkungen).
- **Kommentar** : Ermöglicht das Hinzufügen von Kommentaren zu Ihrem Szenario.

Jeder Block hat seine Optionen, um besser damit umzugehen :

- Links :
    - Mit dem bidirektionalen Pfeil können Sie einen Block oder eine Aktion verschieben, um sie im Szenario neu anzuordnen.
    - Das Auge reduziert eine Blockade (*Zusammenbruch*) um seine visuelle Wirkung zu reduzieren. Strg Klicken Sie auf das Auge, um sie zu verkleinern oder alle anzuzeigen.
    - Mit dem Kontrollkästchen können Sie den Block vollständig deaktivieren, ohne ihn zu löschen. Es wird daher nicht ausgeführt.

- Rechts :
    - Mit dem Symbol Kopieren können Sie den Block kopieren, um eine Kopie an einer anderen Stelle zu erstellen. Strg Klicken Sie auf das Symbol, um den Block zu schneiden (kopieren und löschen).
    - Mit dem Symbol Einfügen können Sie eine Kopie des Blocks einfügen, der zuvor nach dem Block kopiert wurde, für den Sie diese Funktion verwenden.  Strg Klicken Sie auf das Symbol, um den Block durch den kopierten Block zu ersetzen.
    - Das Symbol - ermöglicht es Ihnen, den Block mit einer Bestätigungsanforderung zu löschen. Strg Klicken löscht den Block ohne Bestätigung.

### Wenn / Dann / Sonst blockiert | Schleife | In | A

Für die Bedingungen versucht Jeedom, es möglich zu machen, sie so viel wie möglich in natürlicher Sprache zu schreiben und dabei flexibel zu bleiben.
> Verwenden Sie [] NICHT in Bedingungstests, nur Klammern () sind möglich.

Rechts neben diesem Blocktyp stehen drei Schaltflächen zur Auswahl eines zu testenden Elements zur Verfügung :

- **Finden Sie eine Bestellung** : Ermöglicht die Suche nach einer Bestellung in allen in Jeedom verfügbaren. Sobald die Bestellung gefunden wurde, öffnet Jeedom ein Fenster, in dem Sie gefragt werden, welchen Test Sie daran durchführen möchten. Wenn Sie möchten **Setzen Sie nichts**, Jeedom wird die Bestellung ohne Vergleich hinzufügen. Sie können auch wählen **und** oder **oder** Front **Dann** Kettenprüfungen an verschiedenen Geräten.
- **Suchen Sie ein Szenario** : Ermöglicht die Suche nach einem zu testenden Szenario.
- **Suche nach Ausrüstung** : Gleiches gilt für die Ausrüstung.

> **Notiz**
>
> Bei Blöcken vom Typ If / Then / Andernfalls können kreisförmige Pfeile links neben dem Bedingungsfeld die Wiederholung von Aktionen aktivieren oder nicht, wenn die Auswertung der Bedingung das gleiche Ergebnis wie bei der vorherigen Auswertung ergibt.

> **Spitze**
>
> Es gibt eine Liste von Tags, die den Zugriff auf Variablen aus dem einen oder anderen Szenario oder nach Uhrzeit, Datum, Zufallszahl usw. ermöglichen. Siehe unten die Kapitel zu Befehlen und Tags.

Sobald die Bedingung erfüllt ist, müssen Sie die Schaltfläche &quot;Hinzufügen&quot; auf der linken Seite verwenden, um eine neue hinzuzufügen **Block** oder a **Aktion** im aktuellen Block.


### Blockcode

Mit dem Code-Block können Sie PHP-Code ausführen. Es ist daher sehr mächtig, erfordert aber gute Kenntnisse der PHP-Sprache.

#### Zugang zu Steuerungen (Sensoren und Aktoren))

-  ``cmd::byString($string);`` : Gibt das entsprechende Befehlsobjekt zurück.
    -   ``$string``: Link zur gewünschten Bestellung : ``#[objet][Gerät][commande]#`` (ex : ``#[Appartement][Alarme][Aktiva]#``)
-  ``cmd::byId($id);`` : Gibt das entsprechende Befehlsobjekt zurück.
    -  ``$id`` : Bestellnummer.
-  ``$cmd->execCmd($options = null);`` : Führen Sie den Befehl aus und geben Sie das Ergebnis zurück.
    - ``$options`` : Optionen für die Ausführung des Befehls (können spezifisch für das Plugin sein). Grundlegende Optionen (Befehlssubtyp) :
        -  ``message`` : ``$option = array('title' => 'titre du Nachricht , 'message' => 'Mon message');``
        -  ``color`` : ``$option = array('color' => 'couleur en hexadécimal');``
        -  ``slider`` : ``$option = array('slider' => 'valeur voulue de 0 à 100');``

#### Zugriff auf das Protokoll

-  ``log::add('filename','level','message');``
    - ``filename`` : Name der Protokolldatei.
    - ``level`` : [Debug], [Info], [Fehler], [Ereignis].
    - ``message`` : Nachricht zum Schreiben in die Protokolle.

#### Zugriff auf das Szenario

- ``$scenario->getName();`` : Gibt den Namen des aktuellen Szenarios zurück.
- ``$scenario->getGroup();`` : Gibt die Szenariogruppe zurück.
- ``$scenario->getIsActive();`` : Gibt den Status des Szenarios zurück.
- ``$scenario->setIsActive($active);`` : Ermöglicht das Aktivieren oder Nicht-Aktivieren des Szenarios.
    - ``$active`` : 1 aktiv, 0 nicht aktiv.
- ``$scenario->running();`` : Wird verwendet, um herauszufinden, ob das Szenario ausgeführt wird oder nicht (true / false)).
- ``$scenario->save();`` : Änderungen speichern.
- ``$scenario->setData($key, $value);`` : Speichern Sie eine Daten (Variable).
    - ``$key`` : Werteschlüssel (int oder string).
    - ``$value`` : Wert zu speichern (``int``, ``string``, ``array`` oder ``object``).
- ``$scenario->getData($key);`` : Daten abrufen (variabel).
    - ``$key => 1`` : Werteschlüssel (int oder string).
- ``$scenario->removeData($key);`` : Daten löschen.
- ``$scenario->setLog($message);`` : Schreiben Sie eine Nachricht in das Skriptprotokoll.
- ``$scenario->persistLog();`` : Erzwingen Sie das Schreiben des Protokolls (andernfalls wird es nur am Ende des Szenarios geschrieben). Seien Sie vorsichtig, dies kann das Szenario etwas verlangsamen.

> **Spitze**
>
> Hinzufügen einer Suchfunktion im Codeblock : Suche : Strg + F dann Enter, Nächstes Ergebnis : Strg + G, Vorheriges Ergebnis : Strg + Umschalt + G

[Szenarien : Kleine Codes mit Freunden](https:/./.kiboost.github.io/.jeedom_docs/.jeedomV4Tips/.CodesScenario/.)

### Kommentarblock

Der Kommentarblock verhält sich anders, wenn er ausgeblendet ist. Die Schaltflächen auf der linken Seite sowie der Titel des Blocks verschwinden und werden beim Schweben wieder angezeigt. Ebenso wird die erste Zeile des Kommentars fett gedruckt.
Dadurch kann dieser Block als rein visuelle Trennung innerhalb des Szenarios verwendet werden.

### Aktionen

Zu Blöcken hinzugefügte Aktionen haben mehrere Optionen :

- Eine Kiste **aktiviert** damit dieser Befehl im Szenario berücksichtigt wird.
- Eine Kiste **parallel** so dass dieser Befehl parallel (gleichzeitig) mit den anderen ebenfalls ausgewählten Befehlen gestartet wird.
- Ein **vertikaler Doppelpfeil** um die Aktion zu verschieben. Einfach von dort ziehen und ablegen.
- Ein Knopf für **Entfernen** die Aktion.
- Eine Schaltfläche für bestimmte Aktionen, bei der jedes Mal die Beschreibung (beim Bewegen des Mauszeigers) dieser Aktion angezeigt wird.
- Eine Schaltfläche zum Suchen nach einem Aktionsbefehl.

> **Spitze**
>
> Abhängig vom ausgewählten Befehl werden verschiedene zusätzliche Felder angezeigt.

## Mögliche Substitutionen

### Auslöser

Es gibt bestimmte Auslöser (außer denen, die durch Befehle bereitgestellt werden)) :

- ``#start#`` : Wird beim (erneuten) Start von Jeedom ausgelöst.
- ``#begin_backup#`` : Ereignis, das zu Beginn einer Sicherung gesendet wurde.
- ``#end_backup#`` : Ereignis, das am Ende einer Sicherung gesendet wird.
- ``#begin_update#`` : Ereignis, das zu Beginn eines Updates gesendet wurde.
- ``#end_update#`` : Ereignis, das am Ende eines Updates gesendet wird.
- ``#begin_restore#`` : Ereignis zu Beginn einer Restaurierung gesendet.
- ``#end_restore#`` : Ereignis gesendet am Ende einer Restaurierung.
- ``#user_connect#`` : Benutzeranmeldung

Sie können auch ein Szenario auslösen, wenn eine Variable durch Putten aktualisiert wird : #variable(nom_variable)# oder unter Verwendung der beschriebenen HTTP-API [hier](https:/./.doc.jeedom.com/de_DE/core/.4.1/.api_http).

### Vergleichsoperatoren und Verknüpfungen zwischen Bedingungen

Sie können eines der folgenden Symbole für Vergleiche unter Bedingungen verwenden :

- ``==`` : Gleich.
- ``>`` : Streng größer als.
- ``>=`` : Größer oder gleich.
- ``<`` : Streng weniger als.
- ``<=`` : Kleiner als oder gleich.
- ``!=`` : Anders als, ist nicht gleich.
- ``matches`` : Enthält. Ex : ``[Salle de bain][Hydrometrie][etat] matches "/.humide/."``.
- ``not ( …​ matches …​)`` : Enthält nicht. Ex :  ``not([Salle de bain][Hydrometrie][etat] matches "/.humide/.")``.

Sie können jeden Vergleich mit den folgenden Operatoren kombinieren :

- ``&&`` /. ``ET`` /. ``et`` /. ``AND`` /. ``and`` : et,
- ``||`` /. ``OU`` /. ``ou`` /. ``OR`` /. ``or`` : ou,
- ``^`` /. ``XOR`` /. ``xor`` : oder exklusiv.

### Tags

Ein Tag wird während der Ausführung des Szenarios durch seinen Wert ersetzt. Sie können die folgenden Tags verwenden :

> **Spitze**
>
> Verwenden Sie die Funktion Date (), um die führenden Nullen anzuzeigen. Siehe [hier](http:/./.php.net/.manual/.fr/.function.date.php).

- ``#seconde#`` : Aktuelle Sekunde (ohne führende Nullen, z : 6 für 08:07:06).
- ``#hour#`` : Aktuelle Zeit im 24h-Format (ohne führende Nullen). Ex : 8 für 08:07:06 oder 17 für 17:15.
- ``#hour12#`` : Aktuelle Zeit im 12-Stunden-Format (ohne führende Nullen). Ex : 8 für 08:07:06.
- ``#minute#`` : Aktuelle Minute (ohne führende Nullen). Ex : 7 für 08:07:06.
- ``#day#`` : Aktueller Tag (ohne führende Nullen). Ex : 6 für 06/07/2017.
- ``#month#`` : Aktueller Monat (ohne führende Nullen). Ex : 7 für 06/07/2017.
- ``#year#`` : Laufendes Jahr.
- ``#time#`` : Aktuelle Stunde und Minute. Ex : 1715 für 17.15 Uhr.
- ``#timestamp#`` : Anzahl der Sekunden seit dem 1. Januar 1970.
- ``#date#`` : Tag und Monat. Achtung, die erste Zahl ist der Monat. Ex : 1215 für den 15. Dezember.
- ``#week#`` : Wochennummer.
- ``#sday#`` : Name des Wochentags. Ex : Samstag.
- ``#nday#`` : Tagesnummer von 0 (Sonntag) bis 6 (Samstag)).
- ``#smonth#`` : Name des Monats. Ex : Januar.
- ``#IP#`` : Jeedom&#39;s interne IP.
- ``#hostname#`` : Name der Jeedom-Maschine.
- ``#trigger#`` (veraltet, besser zu bedienen ``trigger()``) : Möglicherweise der Name des Befehls, der das Szenario gestartet hat :
    - ``api`` wenn der Start von der API ausgelöst wurde,
    - ``schedule`` wenn es durch Programmierung gestartet wurde,
    - ``user`` wenn es manuell gestartet wurde,
    - ``start`` für einen Start beim Start von Jeedom.
- ``#trigger_value#`` (veraltet, besser TriggerValue zu verwenden()) : Für den Wert des Befehls, der das Szenario ausgelöst hat

Sie haben auch die folgenden zusätzlichen Tags, wenn Ihr Szenario durch eine Interaktion ausgelöst wurde :

- #query# : Interaktion, die das Szenario ausgelöst hat.
- #profil# : Profil des Benutzers, der das Szenario gestartet hat (kann leer sein).

> **Wichtig**
>
> Wenn ein Szenario durch eine Interaktion ausgelöst wird, wird es notwendigerweise im schnellen Modus ausgeführt. Also im Interaktionsthread und nicht in einem separaten Thread.

### Berechnungsfunktionen

Für das Gerät stehen verschiedene Funktionen zur Verfügung :

- ``average(commande,période)`` und ``averageBetween(commande,start,end)`` : Geben Sie den Durchschnitt der Bestellung über den Zeitraum an=[Monat, Tag, Stunde, Minute] oder [PHP-Expression](http:/./.php.net/.manual/.fr/.datetime.formats.relative.php)) oder zwischen den 2 erforderlichen Anschlüssen (in der Form Ymd H:i:s oder [PHP-Expression](http:/./.php.net/.manual/.fr/.datetime.formats.relative.php)).

- ``min(commande,période)`` und ``minBetween(commande,start,end)`` : Geben Sie die Mindestbestellmenge über den Zeitraum (Zeitraum) an=[Monat, Tag, Stunde, Minute] oder [PHP-Expression](http:/./.php.net/.manual/.fr/.datetime.formats.relative.php)) oder zwischen den 2 erforderlichen Anschlüssen (in der Form Ymd H:i:s oder [PHP-Expression](http:/./.php.net/.manual/.fr/.datetime.formats.relative.php)).

- ``max(commande,période)`` und ``maxBetween(commande,start,end)`` : Geben Sie das Maximum der Bestellung über den Zeitraum (Zeitraum) an=[Monat, Tag, Stunde, Minute] oder [PHP-Expression](http:/./.php.net/.manual/.fr/.datetime.formats.relative.php)) oder zwischen den 2 erforderlichen Anschlüssen (in der Form Ymd H:i:s oder [PHP-Expression](http:/./.php.net/.manual/.fr/.datetime.formats.relative.php)).

- ``duration(commande, valeur, période)`` und ``durationbetween(commande,valeur,start,end)`` : Geben Sie die Dauer in Minuten an, während der das Gerät über den Zeitraum (Zeitraum) den gewählten Wert hatte=[Monat, Tag, Stunde, Minute] oder [PHP-Expression](http:/./.php.net/.manual/.fr/.datetime.formats.relative.php)) oder zwischen den 2 erforderlichen Anschlüssen (in der Form Ymd H:i:s oder [PHP-Expression](http:/./.php.net/.manual/.fr/.datetime.formats.relative.php)).

- ``statistics(commande,calcul,période)`` und ``statisticsBetween(commande,calcul,start,end)`` : Geben Sie das Ergebnis verschiedener statistischer Berechnungen (Summe, Anzahl, Standard, Varianz, Durchschnitt, Min, Max) über den Zeitraum (Zeitraum) an=[Monat, Tag, Stunde, Minute] oder [PHP-Expression](http:/./.php.net/.manual/.fr/.datetime.formats.relative.php)) oder zwischen den 2 erforderlichen Anschlüssen (in der Form Ymd H:i:s oder [PHP-Expression](http:/./.php.net/.manual/.fr/.datetime.formats.relative.php)).

- ``tendance(commande,période,seuil)`` : Gibt den Trend der Bestellung über den Zeitraum (Zeitraum) an=[Monat, Tag, Stunde, Minute] oder [PHP-Expression](http:/./.php.net/.manual/.fr/.datetime.formats.relative.php)).

- ``stateDuration(commande)`` : Gibt die Dauer in Sekunden seit der letzten Wertänderung an.
    -1 : Es gibt keine Geschichte oder keinen Wert in der Geschichte.
    -2 : Die Bestellung wird nicht protokolliert.

- ``lastChangeStateDuration(commande,valeur)`` : Gibt die Dauer in Sekunden seit der letzten Zustandsänderung auf den im Parameter übergebenen Wert an.
    -1 : Es gibt keine Geschichte oder keinen Wert in der Geschichte.
    -2 Die Bestellung wird nicht protokolliert

- ``lastStateDuration(commande,valeur)`` : Gibt die Dauer in Sekunden an, in der das Gerät zuletzt den gewählten Wert hatte.
    -1 : Es gibt keine Geschichte oder keinen Wert in der Geschichte.
    -2 : Die Bestellung wird nicht protokolliert.

- ``age(commande)`` : Gibt das Alter des Befehlswerts in Sekunden an (``collecDate``)
    -1 : Der Befehl existiert nicht oder ist nicht vom Typ info.

- ``stateChanges(commande,[valeur], période)`` und ``stateChangesBetween(commande, [valeur], start, end)`` : Geben Sie die Anzahl der Statusänderungen (in Richtung eines bestimmten Werts, falls angegeben, oder insgesamt, falls nicht) über den Zeitraum (Zeitraum) an=[Monat, Tag, Stunde, Minute] oder [PHP-Expression](http:/./.php.net/.manual/.fr/.datetime.formats.relative.php)) oder zwischen den 2 erforderlichen Anschlüssen (in der Form Ymd H:i:s oder [PHP-Expression](http:/./.php.net/.manual/.fr/.datetime.formats.relative.php)).

- ``lastBetween(commande,start,end)`` : Gibt den zuletzt für das Gerät zwischen den beiden angeforderten Terminals aufgezeichneten Wert an (in der Form Ymd H:i:s oder [PHP-Expression](http:/./.php.net/.manual/.fr/.datetime.formats.relative.php)).

- ``variable(mavariable,valeur par défaut)`` : Ruft standardmäßig den Wert einer Variablen oder den gewünschten Wert ab.

- ``scenario(scenario)`` : Gibt den Status des Szenarios zurück.
    1 : In Bearbeitung,
    0 : Verhaftet,
    -1 : Untauglich,
    -2 : Das Szenario existiert nicht,
    -3 : Zustand ist nicht konsistent.
    Um den &quot;menschlichen&quot; Namen des Szenarios zu erhalten, können Sie die entsprechende Schaltfläche rechts neben der Szenariosuche verwenden.

- ``lastScenarioExecution(scenario)`` : Gibt die Dauer in Sekunden seit dem letzten Start des Szenarios an.
    0 : Das Szenario existiert nicht

- ``collectDate(cmd,[format])`` : Gibt das Datum der letzten Daten für den im Parameter angegebenen Befehl zurück. Der zweite optionale Parameter ermöglicht die Angabe des Rückgabeformats (Details) [hier](http:/./.php.net/.manual/.fr/.function.date.php)).
    -1 : Der Befehl konnte nicht gefunden werden,
    -2 : Der Befehl ist nicht vom Typ info.

- ``valueDate(cmd,[format])`` : Gibt das Datum der letzten Daten für den im Parameter angegebenen Befehl zurück. Der zweite optionale Parameter ermöglicht die Angabe des Rückgabeformats (Details) [hier](http:/./.php.net/.manual/.fr/.function.date.php)).
    -1 : Der Befehl konnte nicht gefunden werden,
    -2 : Der Befehl ist nicht vom Typ info.

- ``eqEnable(equipement)`` : Gibt den Status des Geräts zurück.
    -2 : Das Gerät kann nicht gefunden werden,
    1 : Ausrüstung ist aktiv,
    0 : Das Gerät ist inaktiv.

- ``value(cmd)`` : Gibt den Wert einer Bestellung zurück, wenn er nicht automatisch von Jeedom angegeben wird (Groß- und Kleinschreibung, wenn der Name der Bestellung in einer Variablen gespeichert wird)

- ``tag(montag,[defaut])`` : Wird verwendet, um den Wert eines Tags oder den Standardwert abzurufen, falls dieser nicht vorhanden ist.

- ``name(type,commande)`` : Wird verwendet, um den Namen der Bestellung, Ausrüstung oder des Objekts abzurufen. Typ : cmd, eqLogic oder Objekt.

- ``lastCommunication(equipment,[format])`` : Gibt das Datum der letzten Kommunikation für das als Parameter angegebene Gerät zurück. Mit dem zweiten optionalen Parameter können Sie das Rückgabeformat (Details) angeben [hier](http:/./.php.net/.manual/.fr/.function.date.php)). Eine Rückgabe von -1 bedeutet, dass das Gerät nicht gefunden werden kann.

- ``color_gradient(couleur_debut,couleur_fin,valuer_min,valeur_max,valeur)`` : Gibt eine Farbe zurück, die in Bezug auf den Wert im Bereich color_start / color_end berechnet wurde. Der Wert muss zwischen min_value und max_value liegen.

Die Perioden und Intervalle dieser Funktionen können auch mit verwendet werden [PHP-Ausdrücke](http:/./.php.net/.manual/.fr/.datetime.formats.relative.php) wie zum Beispiel :

- ``Now`` : jetzt.
- ``Today`` : 00:00 heute (ermöglicht zum Beispiel, Ergebnisse für den Tag zu erhalten, wenn zwischen ``Today`` und ``Now``).
- ``Last Monday`` : letzten Montag um 00:00.
- ``5 days ago`` : Vor 5 Tagen.
- ``Yesterday noon`` : gestern mittag.
- Usw..

Hier finden Sie praktische Beispiele zum Verständnis der von diesen verschiedenen Funktionen zurückgegebenen Werte :

| Sockel mit Werten :           | 000 (für 10 Minuten) 11 (für 1 Stunde) 000 (für 10 Minuten))    |
|--------------------------------------|--------------------------------------|
| ``average(prise,période)``             | Gibt den Durchschnitt von 0 und 1 zurück (can  |
|                                      | durch Umfragen beeinflusst werden)      |
| ``averageBetween(#[Salle de bain][Hydrometrie][Humidité]#,2015-01-01 00:00:00,2015-01-15 00:00:00)`` | Gibt die durchschnittliche Bestellung zwischen dem 1. Januar 2015 und dem 15. Januar 2015 zurück                       |
| ``min(prise,période)``                 | Gibt 0 zurück : Der Stecker wurde während des Zeitraums gelöscht              |
| ``minBetween(#[Salle de bain][Hydrometrie][Humidité]#,2015-01-01 00:00:00,2015-01-15 00:00:00)`` | Gibt die Mindestbestellmenge zwischen dem 1. Januar 2015 und dem 15. Januar 2015 zurück                       |
| ``max(prise,période)``                 | Rückgabe 1 : Der Stecker war in der Zeit gut beleuchtet              |
| ``maxBetween(#[Salle de bain][Hydrometrie][Humidité]#,2015-01-01 00:00:00,2015-01-15 00:00:00)`` | Gibt das Maximum der Bestellung zwischen dem 1. Januar 2015 und dem 15. Januar 2015 zurück                       |
| ``duration(prise,1,période)``          | Gibt 60 zurück : Der Stecker war in diesem Zeitraum 60 Minuten lang eingeschaltet (bei 1)                              |
| ``durationBetween(#[Salon][Prise][Etat]#,0,Last Monday,Now)``   | Gibt die Dauer in Minuten zurück, in der die Steckdose seit dem letzten Montag ausgeschaltet war.                |
| ``statistics(prise,count,période)``    | Rückgabe 8 : In diesem Zeitraum gab es 8 Eskalationen               |
| ``tendance(prise,période,0.1)``        | Gibt -1 zurück : Abwärtstrend    |
| ``stateDuration(prise)``               | Gibt 600 zurück : Der Stecker befindet sich seit 600 Sekunden (10 Minuten) in seinem aktuellen Zustand)                             |
| ``lastChangeStateDuration(prise,0)``   | Gibt 600 zurück : Die Steckdose ging vor 600 Sekunden (10 Minuten) zum letzten Mal aus (auf 0 ändern))     |
| ``lastChangeStateDuration(prise,1)``   | Gibt 4200 zurück : Die Steckdose wurde vor 4200 Sekunden (1h10) zum letzten Mal eingeschaltet (auf 1 umgeschaltet))                               |
| ``lastStateDuration(prise,0)``         | Gibt 600 zurück : Die Steckdose war 600 Sekunden (10 Minuten) ausgeschaltet)     |
| ``lastStateDuration(prise,1)``         | Gibt 3600 zurück : Die Steckdose wurde zuletzt für 3600 Sekunden (1 Stunde) eingeschaltet)           |
| ``stateChanges(prise,période)``        | Rückgabe 3 : Der Stecker hat während des Zeitraums dreimal den Zustand geändert            |
| ``stateChanges(prise,0,période)``      | Rückgabe 2 : Die Steckdose ist während des Zeitraums zweimal erloschen (auf 0)                              |
| ``stateChanges(prise,1,période)``      | Rückgabe 1 : Der Stecker leuchtet während des Zeitraums einmal (auf 1 ändern)                              |
| ``lastBetween(#[Salle de bain][Hydrometrie][Humidité]#,Yesterday,Today)`` | Gibt die zuletzt gestern aufgezeichnete Temperatur zurück.                    |
| ``variable(plop,10)``                  | Gibt den Wert der Variablen plop oder 10 zurück, wenn sie leer ist oder nicht existiert                         |
| ``scenario(#[Salle de bain][Lumière][Auto]#)`` | Gibt 1 in Bearbeitung zurück, 0, wenn gestoppt, und -1, wenn deaktiviert, -2, wenn das Szenario nicht existiert, und -3, wenn der Status nicht konsistent ist                         |
| ``lastScenarioExecution(#[Salle de bain][Lumière][Auto]#)``   | Gibt 300 zurück, wenn das Szenario vor 5 Minuten zum letzten Mal gestartet wurde                                  |
| ``collectDate(#[Salle de bain][Hydrometrie][Humidité]#)``     | Rückgabe 2015-01-01 17:45:12          |
| ``valueDate(#[Salle de bain][Hydrometrie][Humidité]#)`` | Rückgabe 2015-01-01 17:50:12          |
| ``eqEnable(#[Aucun][Basilique]#)``       | Gibt -2 zurück, wenn das Gerät nicht gefunden wird, 1, wenn das Gerät aktiv ist, und 0, wenn es inaktiv ist          |
| ``tag(montag,toto)``                   | Gibt den Wert von "montag" zurück, falls vorhanden, andernfalls wird der Wert "toto" zurückgegeben"                               |
| ``name(eqLogic,#[Salle de bain][Hydrometrie][Humidité]#)``     | Gibt Hydrometrie zurück                  |


### Mathematische Funktionen

Eine generische Funktions-Toolbox kann auch zum Durchführen von Konvertierungen oder Berechnungen verwendet werden :

- ``rand(1,10)`` : Geben Sie eine Zufallszahl von 1 bis 10 an.
- ``randText(texte1;texte2;texte…​..)`` : Ermöglicht es Ihnen, einen der Texte zufällig zurückzugeben (trennen Sie die Texte durch einen; ). Die Anzahl der Texte ist unbegrenzt.
- ``randomColor(min,max)`` : Gibt eine zufällige Farbe zwischen 2 Grenzen (0 => Rot, 50 => Grün, 100 => Blau).
- ``trigger(commande)`` : Ermöglicht es Ihnen, den Auslöser für das Szenario herauszufinden oder festzustellen, ob der als Parameter übergebene Befehl das Szenario ausgelöst hat.
- ``triggerValue(commande)`` : Wird verwendet, um den Wert des Szenario-Triggers herauszufinden.
- ``round(valeur,[decimal])`` : Runden oben, [Dezimal] Anzahl der Dezimalstellen nach dem Dezimalpunkt.
- ``odd(valeur)`` : Lässt Sie wissen, ob eine Zahl ungerade ist oder nicht. Gibt 1 zurück, wenn sonst ungerade 0.
- ``median(commande1,commande2…​.commandeN)`` : Gibt den Median der Werte zurück.
- ``avg(commande1,commande2…​.commandeN)`` : Gibt den Durchschnitt der Werte zurück.
- ``time_op(time,value)`` : Ermöglicht die pünktliche Ausführung von Operationen mit time = time (z : 1530) und Wert = Wert zum Addieren oder Subtrahieren in Minuten.
- ``time_between(time,start,end)`` : Wird verwendet, um zu testen, ob eine Zeit zwischen zwei Werten mit liegt ``time=temps`` (ex : 1530), ``start=temps``, ``end=temps``. Start- und Endwerte können sich über Mitternacht erstrecken.
- ``time_diff(date1,date2[,format, round])`` : Wird verwendet, um den Unterschied zwischen zwei Daten zu ermitteln (die Daten müssen das Format JJJJ / MM / TT HH haben:MM:SS). Standardmäßig gibt die Methode die Differenz in Tag (e) zurück). Sie können es in Sekunden (s), Minuten (m), Stunden (h) fragen). Beispiel in Sekunden ``time_diff(2019-02-02 14:55:00,2019-02-25 14:55:00,s)``. Die Differenz wird in absoluten Zahlen zurückgegeben, sofern Sie nichts anderes angeben ``f`` (``sf``, ``mf``, ``hf``, ``df``). Sie können auch verwenden ``dhms`` wer wird nicht Beispiel zurückgeben ``7j 2h 5min 46s``. Der optionale runde Parameter wird nach dem Dezimalpunkt auf x Stellen gerundet (standardmäßig 2). Ex: ``time_diff(2020-02-21 20:55:28,2020-02-28 23:01:14,df, 4)``.
- ``formatTime(time)`` : Formatiert die Rückgabe einer Kette ``#time#``.
- ``floor(time/.60)`` : Konvertieren Sie Sekunden in Minuten oder Minuten in Stunden (``floor(time/.3600)`` für Sekunden bis Stunden).
- ``convertDuration(secondes)`` : Konvertiert Sekunden in d / h / min / s.

Und praktische Beispiele :


| Funktionsbeispiel                  | Zurückgegebenes Ergebnis                    |
|--------------------------------------|--------------------------------------|
| ``randText(il fait #[salon][oeil][température]#;La température est de #[salon][oeil][température]#;Actuellement on a #[salon][oeil][température]#)`` | Die Funktion gibt bei jeder Ausführung zufällig einen dieser Texte zurück.                           |
| ``randomColor(40,60)``                 | Gibt eine zufällige Farbe nahe Grün zurück.
| ``trigger(#[Salle de bain][Hydrometrie][Humidité]#)``   | 1 wenn es gut ist ``#[Salle de bain][Hydrometrie][Humidité]#`` Wer hat das Szenario sonst gestartet? 0  |
| ``triggerValue(#[Salle de bain][Hydrometrie][Humidité]#)`` | 80 wenn die Hydrometrie von ``#[Salle de bain][Hydrometrie][Humidité]#`` beträgt 80%.                         |
| ``round(#[Salle de bain][Hydrometrie][Humidité]# /. 10)`` | Gibt 9 zurück, wenn der Feuchtigkeitsprozentsatz und 85                     |
| ``odd(3)``                             | Rückgabe 1                            |
| ``median(15,25,20)``                   | Rückgabe 20
| ``avg(10,15,18)``                      | Rückgabe 14.3                     |
| ``time_op(#time#, -90)``               | Wenn es 16:50 Uhr ist, kehren Sie zurück : 1 650-1 130 = 1520                          |
| ``formatTime(1650)``                   | Rückgabe 16:50 Uhr                        |
| ``floor(130/.60)``                     | Gibt 2 zurück (Minuten bei 130 s oder Stunden bei 130 m)                      |
| ``convertDuration(3600)``             | Gibt 1h 0min 0s zurück                      |
| ``convertDuration(duration(#[Chauffage][Module chaudière][Etat]#,1, first day of this month)*60)`` | Gibt die Zündzeit in Tagen / Stunden / Minuten der Übergangszeit auf Zustand 1 des Moduls seit dem 1. Tag des Monats zurück |


### Spezifische Bestellungen

Zusätzlich zu den Befehlen für die Hausautomation haben Sie Zugriff auf die folgenden Aktionen :

- **Pause** (sleep) : Pause von x Sekunden (s).
- **Variable** (variable) : Erstellung / Änderung einer Variablen oder des Werts einer Variablen.
- **Variable entfernen** (delete_variable) : Ermöglicht das Löschen einer Variablen.
- **Szenario** (scenario) : Ermöglicht die Steuerung von Szenarien. Mit dem Tag-Teil können Sie Tags an das Szenario senden, z : montag = 2 (Vorsicht, verwenden Sie nur Buchstaben von a bis z. Keine Großbuchstaben, keine Akzente und keine Sonderzeichen). Wir stellen das Tag im Zielszenario mit der Tag-Funktion (montag) wieder her). Mit dem Befehl "Auf SI zurücksetzen" können Sie den Status von "SI" zurücksetzen (dieser Status wird verwendet, um die Aktionen eines "SI" nicht zu wiederholen, wenn Sie ihn zum zweiten Mal in Folge übergeben)
- **STOP** (stop) : Stoppen Sie das Szenario.
- **Erwarten** (wait) : Warten Sie, bis die Bedingung gültig ist (maximal 2 Stunden). Die Zeitüberschreitung erfolgt in Sekunden (s)).
- **Gehe zum Design** (gotodesign) : Ändern Sie das in allen Browsern angezeigte Design durch das angeforderte Design.
- **Fügen Sie ein Protokoll hinzu** (log) : Ermöglicht das Hinzufügen einer Nachricht zu den Protokollen.
- **Nachricht erstellen** (message) : Fügen Sie dem Nachrichtencenter eine Nachricht hinzu.
- **Geräte ausblenden / deaktivieren** (equipement) : Ermöglicht das Ändern der Eigenschaften von sichtbaren / unsichtbaren, aktiven / inaktiven Geräten.
- **Stellen Sie eine Anfrage** (ask) : Wird verwendet, um Jeedom anzuzeigen, dass dem Benutzer eine Frage gestellt werden soll. Die Antwort wird in einer Variablen gespeichert, dann müssen Sie nur noch ihren Wert testen.
    Momentan sind nur SMS-, Slack-, Telegramm- und Snips-Plugins sowie die mobile Anwendung kompatibel.
    Achtung, diese Funktion blockiert. Solange keine Antwort erfolgt oder das Zeitlimit nicht erreicht wird, wartet das Szenario.
- **Stoppen Sie Jeedom** (jeedom_poweroff) : Bitten Sie Jeedom, herunterzufahren.
- **Geben Sie einen Text / Daten zurück** (Szenario_return) : Gibt beispielsweise einen Text oder einen Wert für eine Interaktion zurück.
- **Symbol** (icon) : Ermöglicht das Ändern des Darstellungssymbols des Szenarios.
- **Warnung** (alert) : Zeigt eine kleine Warnmeldung in allen Browsern an, in denen eine Jeedom-Seite geöffnet ist. Sie können zusätzlich 4 Alarmstufen auswählen.
- **Popup** (popup) : Ermöglicht die Anzeige eines Popups, das in allen Browsern, in denen eine Jeedom-Seite geöffnet ist, unbedingt überprüft werden muss.
- **Bericht** (report) : Ermöglicht das Exportieren einer Ansicht im Format (PDF, PNG, JPEG oder SVG) und das Senden mit einem Befehl vom Typ Nachricht. Bitte beachten Sie, dass diese Funktionalität nicht funktioniert, wenn sich Ihr Internetzugang in nicht signiertem HTTPS befindet. Signiertes HTTP oder HTTPS ist erforderlich.
- **Programmierten IN / A-Block löschen** (remove_inat) : Ermöglicht das Löschen der Programmierung aller IN- und A-Blöcke des Szenarios.
- **Ereignis** (event) : Ermöglicht das willkürliche Übertragen eines Werts in einen Befehl vom Typ Information.
- **Etikett** (tag) : Ermöglicht das Hinzufügen / Ändern eines Tags (das Tag ist nur während der aktuellen Ausführung des Szenarios vorhanden, im Gegensatz zu den Variablen, die das Ende des Szenarios überleben).
- **Färbung von Dashboard-Symbolen** (setColoredIcon) : Ermöglicht das Aktivieren oder Nicht-Aktivieren der Farbgebung von Symbolen im Dashboard.

### Szenariovorlage

Mit dieser Funktion können Sie ein Szenario in eine Vorlage umwandeln, um es beispielsweise auf ein anderes Jeedom anzuwenden.

Durch Klicken auf die Schaltfläche **Schablone** Oben auf der Seite öffnen Sie das Vorlagenverwaltungsfenster.

Von dort haben Sie die Möglichkeit :

- Senden Sie eine Vorlage an Jeedom (zuvor abgerufene JSON-Datei).
- Konsultieren Sie die Liste der auf dem Markt verfügbaren Szenarien.
- Erstellen Sie eine Vorlage aus dem aktuellen Szenario (vergessen Sie nicht, einen Namen anzugeben).
- Um die Vorlagen zu konsultieren, die derzeit auf Ihrem Jeedom vorhanden sind.

Durch Klicken auf eine Vorlage können Sie :

- **Aktie** : Teilen Sie die Vorlage auf dem Markt.
- **Entfernen** : Vorlage löschen.
- **Download** : Holen Sie sich die Vorlage als JSON-Datei, um sie beispielsweise an ein anderes Jeedom zu senden.

Unten haben Sie den Teil, um Ihre Vorlage auf das aktuelle Szenario anzuwenden.

Da die Befehle von einem Jeedom zum anderen oder von einer Installation zur anderen unterschiedlich sein können, bittet Jeedom Sie um die Entsprechung der Befehle zwischen den bei der Erstellung der Vorlage vorhandenen und den zu Hause vorhandenen. Sie müssen nur die Korrespondenz der Bestellungen ausfüllen, um sich zu bewerben.

## Hinzufügung der PHP-Funktion

> **Wichtig**
>
> Das Hinzufügen der PHP-Funktion ist fortgeschrittenen Benutzern vorbehalten. Der kleinste Fehler kann für Ihr Jeedom fatal sein.

### Einrichten

Gehen Sie zur Jeedom-Konfiguration, dann zu OS / DB und starten Sie den Datei-Editor.

Gehen Sie in den Datenordner und dann in PHP und klicken Sie auf die Datei user.function.class.php.

Es ist in diesem *Klasse* Damit Sie Ihre Funktionen hinzufügen können, finden Sie dort ein Beispiel für eine Grundfunktion.

> **Wichtig**
>
> Wenn Sie Bedenken haben, können Sie jederzeit zur Originaldatei zurückkehren, indem Sie den Inhalt von kopieren ``user.function.class.sample.php`` In ``user.function.class.php``
