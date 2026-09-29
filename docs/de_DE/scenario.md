# Szenarien

**Extras → Szenarien**

<small>[Tastatur- und Maus-Shortcuts](shortcuts.md)</small>

Als echtes „Gehirn“ der Hausautomation ermöglichen Szenarien eine *intelligente* Interaktion mit der realen Welt.

## Verwaltung

Hier finden Sie eine Liste der Szenarien Ihres Jeedom sowie Funktionen, mit denen Sie diese optimal verwalten können:

- **Hinzufügen**: Ermöglicht das Erstellen eines Szenarios. Die Vorgehensweise wird im folgenden Kapitel beschrieben.
- **Szenarien deaktivieren**: Ermöglicht es, alle Szenarien zu deaktivieren. Wird nur selten und bewusst verwendet, da dann kein Szenario mehr ausgeführt wird.
- **Übersicht**: Bietet einen Überblick über alle Szenarien. Sie können die Werte für **aktiv**, **sichtbar**, **Mehrfachstart**, **synchroner Modus**, **Protokoll** und **Zeitleiste** ändern (diese Einstellungen werden im nächsten Kapitel beschrieben). Außerdem können Sie auf die Protokolle der einzelnen Szenarien zugreifen und diese einzeln starten.

## Meine Szenarien

In diesem Bereich finden Sie eine **Liste der Szenarien**, die Sie erstellt haben. Sie sind nach ihrer **Gruppe** sortiert, die gegebenenfalls für jedes einzelne Szenario definiert wurde. Jedes Szenario wird mit seinem **Namen** und seinem **übergeordneten Objekt** angezeigt. Die **ausgegrauten Szenarien** sind diejenigen, die deaktiviert sind.

> **Tipp**
>
> Sie können ein Szenario wie folgt öffnen:
>
> - Klicken Sie auf eines davon.
> - Strg-Klick oder mittlerer Mausklick, um die Seite in einem neuen Browser-Tab zu öffnen.

Ihnen steht eine Suchfunktion zur Verfügung, mit der Sie die Anzeige der Szenarien filtern können. Mit der Esc-Taste brechen Sie die Suche ab.
Rechts neben dem Suchfeld befinden sich drei Schaltflächen, die an mehreren Stellen in Jeedom zu finden sind:

- Das Kreuz zum Abbrechen der Suche.
- Der geöffnete Ordner, um alle Registerkarten aufzuklappen und alle Szenarien anzuzeigen.
- Der Ordner ist geschlossen, um alle Paneele einzuklappen.

Sobald Sie sich in der Konfiguration eines Szenarios befinden, steht Ihnen bei einem Rechtsklick auf die Registerkarten des Szenarios ein Kontextmenü zur Verfügung. Sie können auch Strg+Klick oder einen Klick mit der mittleren Maustaste verwenden, um ein anderes Szenario direkt in einem neuen Browser-Tab zu öffnen.

## Erstellen | Bearbeiten eines Szenarios

Nachdem Sie auf **Hinzufügen** geklickt haben, müssen Sie einen Namen für Ihr Szenario auswählen. Anschließend werden Sie zur Seite mit den allgemeinen Einstellungen weitergeleitet.
Zuvor finden Sie oben auf der Seite einige nützliche Funktionen zur Verwaltung dieses Szenarios:

- **ID**: Neben dem Wort **Allgemein** befindet sich die Kennung des Szenarios.
- **Status**: *Beendet* oder *In Bearbeitung* – gibt den aktuellen Status des Szenarios an.
- **Vorheriger/Nächster Zustand**: Ermöglicht es, eine Aktion rückgängig zu machen bzw. zu wiederholen.
- **Block hinzufügen**: Ermöglicht es, dem Szenario einen Block des gewünschten Typs hinzuzufügen (siehe unten).
- **Protokoll**: Hier können Sie die Protokolle des Szenarios anzeigen.
- **Duplizieren**: Ermöglicht es, das Szenario zu kopieren, um ein neues mit einem anderen Namen zu erstellen.
- **Links**: Ermöglicht die Anzeige der Grafik der Elemente, die mit dem Szenario verknüpft sind.
- **Textbearbeitung**: Öffnet ein Fenster, in dem Sie das Szenario im Text- oder JSON-Format bearbeiten können. Vergessen Sie nicht, die Änderungen zu speichern.
- **Exportieren**: Ermöglicht es, eine reine Textversion des Szenarios zu erhalten.
- **Vorlage**: Ermöglicht den Zugriff auf Vorlagen und das Anwenden einer Vorlage auf das Szenario über den Market (Erläuterung siehe unten auf der Seite).
- **Suche**: Öffnet ein Suchfeld, um das Szenario zu durchsuchen. Bei dieser Suche werden die ausgeblendeten Blöcke bei Bedarf einblendet und nach der Suche wieder ausgeblendet.
- **Ausführen**: Ermöglicht es, das Szenario manuell zu starten (unabhängig von den Auslösern). Speichern Sie es zuvor, damit die Änderungen berücksichtigt werden.
- **Löschen**: Das Szenario löschen.
- **Speichern**: Die vorgenommenen Änderungen speichern.

> **Tipps**
>
> Zwei Tools werden Ihnen bei der Einrichtung von Szenarien ebenfalls sehr hilfreich sein:
    > - Die Variablen, sichtbar unter **Extras → Variablen**
    > - Der Ausdrucksprüfer, erreichbar über **Extras → Ausdrucksprüfer**
>
> Mit einem **Strg-Klick auf die Schaltfläche „Ausführen“** können Sie das Szenario direkt speichern, ausführen und das Protokoll anzeigen (sofern die Protokollstufe nicht auf „Keine“ eingestellt ist).

## Registerkarte „Allgemein“

Auf der Registerkarte **Allgemein** finden sich die wichtigsten Einstellungen des Szenarios:

- **Name des Szenarios**: Der Name Ihres Szenarios.
- **Anzuzeigender Name**: Der Name, der zur Anzeige verwendet wird. Optional; wenn kein Name angegeben wird, wird der Name des Szenarios verwendet.
- **Gruppe**: Ermöglicht die Organisation der Szenarien, indem diese in Gruppen eingeordnet werden (sichtbar auf der Szenarienseite und in deren Kontextmenüs).
- **Aktiv**: Ermöglicht die Aktivierung des Szenarios. Ist diese Option nicht aktiviert, wird das Szenario von Jeedom nicht ausgeführt, unabhängig von der Art der Auslösung.
- **Sichtbar**: Ermöglicht es, das Szenario (Dashboard) sichtbar zu machen.
- **Übergeordnetes Objekt**: Zuordnung zu einem übergeordneten Objekt. Das Objekt ist dann je nach diesem übergeordneten Objekt sichtbar oder nicht.
- **Timeout in Sekunden (0 = unbegrenzt)**: Die maximal zulässige Ausführungsdauer für dieses Szenario. Nach Ablauf dieser Zeit wird die Ausführung des Szenarios unterbrochen.
- **Mehrfachstart**: Aktivieren Sie dieses Kontrollkästchen, wenn das Szenario mehrmals gleichzeitig gestartet werden soll.

>**WICHTIG**
>
>Der Multi-Start funktioniert sekundengenau, d. h., wenn Sie zwei Starts innerhalb derselben Sekunde ausführen, ohne das Kontrollkästchen zu aktivieren, wird das Szenario dennoch zweimal ausgeführt (obwohl dies nicht der Fall sein sollte). Ebenso kann es bei mehreren Starts innerhalb derselben Sekunde vorkommen, dass bei einigen Starts die Tags verloren gehen. Fazit: Mehrere Starts innerhalb derselben Sekunde müssen UNBEDINGT vermieden werden.

- **Synchroner Modus**: Startet das Szenario im aktuellen Thread statt in einem eigenen Thread. Dies beschleunigt den Start des Szenarios, kann das System jedoch instabil machen. Achten Sie darauf, auf keinen Fall komplexe Szenarien oder solche mit Pausen (Sleep) oder Wait-Befehlen im synchronen Modus auszuführen, da dies zu einem instabilen Verhalten von Jeedom führt und vom Support nicht abgedeckt werden kann.
- **Protokoll**: Die gewünschte Protokollart für das Szenario. Sie können die Protokollierung des Szenarios deaktivieren oder alternativ in „Analyse → Echtzeit“ anzeigen lassen.
- **Zeitleiste**: Ermöglicht es, den Verlauf des Szenarios in der Zeitleiste zu verfolgen (siehe Dokumentation „Verlauf“).
- **Symbol**: Hier können Sie anstelle des Standardsymbols ein eigenes Symbol für das Szenario auswählen.
- **Beschreibung**: Hier können Sie einen kurzen Text eingeben, um Ihr Szenario zu beschreiben.
- **Szenario-Modus**: Das Szenario kann programmiert, ausgelöst oder beides gleichzeitig werden. Anschließend haben Sie die Wahl, den/die Auslöser (maximal 15 Auslöser) und die Programmierung(en) anzugeben.

> **Tipp**
>
> Im ausgelösten Modus können nun Bedingungen eingegeben werden. Beispiel: ``#[Garage][Open Garage][Ouverture]# == 1``
> Achtung: Pro Szenario sind maximal 28 Auslöser/Zeitpläne zulässig.

> **Voreingestellter Tipp-Modus**
>
> Der programmierte Modus verwendet die **Cron**-Syntax. Sie können beispielsweise alle 20 Minuten ein Szenario ausführen mit  `*/20 * * * *`oder um 5 Uhr morgens, um eine Vielzahl von Dingen für den Tag zu regeln mit ``0 5 * * *``. Mit dem „?“ rechts neben einem Zeitplan können Sie diesen anpassen, ohne ein Experte für die Cron-Syntax sein zu müssen. Es ist auch möglich, eine Startzeit in folgender Form anzugeben: `Gi` (Uhrzeit ohne führende Null und ohne Minuten, Beispiel für `09h15` => `915` oder für `23h40` => `2340`). Diese Uhrzeit kann das Ergebnis einer Berechnung sein (unter Verwendung eines Befehls oder eines Tags), zum Beispiel: `#sunset# + 10` für einen Start 10 Minuten nach Sonnenuntergang. Beachten Sie, dass für einen Start 1 Stunde und 30 Minuten nach Sonnenuntergang folgende Einstellung erforderlich ist: `#sunset# + 130`. Bitte beachten Sie, dass Jeedom bei Verwendung einer anderen Syntax als der Cron-Syntax nicht in der Lage ist, Ihnen die Daten der vorherigen oder nächsten Ausführungen anzugeben.

## Registerkarte „Szenario“

Hier erstellen Sie Ihr Szenario. Nach der Erstellung des Szenarios ist dessen Inhalt leer, es wird also … nichts tun. Sie müssen zunächst mit der Schaltfläche auf der rechten Seite **einen Block hinzufügen**. Sobald ein Block erstellt wurde, können Sie ihm einen weiteren **Block** oder eine **Aktion** hinzufügen.

Um die Bedienung zu vereinfachen und zu vermeiden, dass die Blöcke im Szenario ständig neu angeordnet werden müssen, wird ein Block an der Stelle eingefügt, an der sich der Mauszeiger gerade befindet.
*Wenn Sie beispielsweise etwa zehn Blöcke haben und auf die „WENN“-Bedingung des ersten Blocks klicken, wird der hinzugefügte Block hinter diesem Block auf derselben Ebene eingefügt. Ist kein Feld aktiv, wird er am Ende des Szenarios eingefügt.*

> **Tipp**
>
> In Bedingungen und Aktionen sollten Sie lieber einfache Anführungszeichen (') anstelle von doppelten Anführungszeichen (") verwenden.

> **Tipp**
>
> Mit Strg+Umschalt+Z oder Strg+Umschalt+Y können Sie eine Änderung (Hinzufügen einer Aktion, eines Blocks usw.) **rückgängig machen** oder **wiederherstellen**.

## Die Bausteine

Hier sind die verschiedenen verfügbaren Blocktypen:

- **Wenn/Dann/Sonst**: Ermöglicht die Ausführung von Aktionen unter bestimmten Bedingungen (wenn dies, dann das).
- **Aktion**: Ermöglicht das Ausführen einfacher Aktionen ohne jegliche Bedingungen.
- **Schleife**: Ermöglicht die wiederholte Ausführung von Aktionen von 1 bis zu einer festgelegten Anzahl, einer zufälligen Zahl, dem Wert eines Sensors usw. *(maximale Dauer: 1 Stunde)*
- **In**: Ermöglicht es, eine Aktion in X Minuten auszulösen (0 ist ein möglicher Wert). Das Besondere daran ist, dass die Aktionen im Hintergrund ausgeführt werden und somit den weiteren Ablauf des Szenarios nicht blockieren. Es handelt sich also um einen nicht blockierenden Block.
- **A**: Ermöglicht es, Jeedom anzuweisen, die Aktionen des Blocks zu einer bestimmten Uhrzeit (im Format hhmm) auszuführen. Dieser Block ist nicht blockierend. Beispiel: 0030 für 00:30 Uhr, 0146 für 1:46 Uhr und 1050 für 10:50 Uhr.
- **Solange**: Ermöglicht die Ausführung von Aktionen, solange eine Bedingung erfüllt ist. *(maximale Dauer: 1 Stunde)*
- **Code**: Ermöglicht das direkte Schreiben in PHP-Code (erfordert gewisse Kenntnisse und kann risikobehaftet sein, bietet jedoch keinerlei Einschränkungen).
- **Kommentar**: Ermöglicht es, Kommentare zum eigenen Szenario hinzuzufügen.

Jeder Block verfügt über eigene Optionen, um ihn besser bedienen zu können:

- Links:
  - Mit dem Doppelpfeil können Sie einen Block oder eine Aktion verschieben, um sie im Szenario neu anzuordnen.
  - Mit dem Augensymbol können Sie einen Block (*collapse*) zusammenklappen, um dessen visuelle Wirkung zu verringern. Durch Strg-Klick auf das Augensymbol werden alle Blöcke entweder ausgeblendet oder eingeblendet.
  - Mit dem Kontrollkästchen können Sie den Block vollständig deaktivieren, ohne ihn zu löschen. Er wird daher nicht ausgeführt.

- Rechts:
  - Mit dem Symbol „Kopieren“ können Sie den Block kopieren, um an anderer Stelle eine Kopie davon zu erstellen. Durch Strg-Klick auf das Symbol wird der Block ausgeschnitten (zuerst kopieren, dann löschen).
  - Mit dem Symbol „Einfügen“ können Sie eine Kopie des zuvor kopierten Blocks hinter dem Block einfügen, auf dem Sie diese Funktion anwenden. Ein Strg-Klick auf das Symbol ersetzt den Block durch den kopierten Block.
  - Das Symbol – ermöglicht das Löschen des Blocks, wobei eine Bestätigung abgefragt wird. Mit Strg+Klick wird der Block ohne Bestätigung gelöscht.

### Wenn/Dann/Sonst-Blöcke | Schleife | In | A

Was die Bedingungen angeht, versucht Jeedom, diese so weit wie möglich in natürlicher Sprache zu formulieren und dabei dennoch flexibel zu bleiben.
> Verwenden Sie in Bedingungstests UNBEDINGT KEINE [ ], sondern ausschließlich Klammern ().

Auf der rechten Seite dieses Blocks befinden sich drei Schaltflächen zur Auswahl eines zu testenden Elements:

- **Befehl suchen**: Ermöglicht die Suche nach einem Befehl unter allen in Jeedom verfügbaren Befehlen. Sobald der Befehl gefunden wurde, öffnet Jeedom ein Fenster, in dem Sie gefragt werden, welchen Test Sie damit durchführen möchten. Wenn Sie **Nichts eingeben** wählen, fügt Jeedom den Befehl ohne Vergleich hinzu. Sie können außerdem vor **Anschließend** die Optionen **und** oder **oder** auswählen, um Tests an verschiedenen Geräten nacheinander durchzuführen.
- **Szenario suchen**: Ermöglicht die Suche nach einem zu testenden Szenario.
- **Gerät suchen**: Das Gleiche gilt für ein Gerät.

> **Hinweis**
>
> Bei Blöcken vom Typ „Wenn/Dann/Sonst“ ermöglichen kreisförmige Pfeile links neben dem Bedingungsfeld, die Wiederholung der Aktionen zu aktivieren oder zu deaktivieren, wenn die Auswertung der Bedingung dasselbe Ergebnis liefert wie bei der vorherigen Auswertung.
> „WENN Ausdruck != 0“ entspricht „WENN Ausdruck“ und „WENN Ausdruck == 0“ entspricht „WENN nicht Ausdruck“

> **Tipp**
>
> Es gibt eine Liste von Tags, über die Sie auf Variablen aus diesem oder einem anderen Szenario sowie auf Uhrzeit, Datum, eine Zufallszahl usw. zugreifen können. Weitere Informationen finden Sie in den Kapiteln zu Befehlen und Tags.

Sobald Sie die Bedingung eingegeben haben, müssen Sie die Schaltfläche „Hinzufügen“ auf der linken Seite verwenden, um einen neuen **Block** oder eine **Aktion** zum aktuellen Block hinzuzufügen.

### Code-Block

Mit dem Code-Block lässt sich PHP-Code ausführen. Er ist daher sehr leistungsstark, erfordert jedoch fundierte Kenntnisse der Programmiersprache PHP.

#### Zugriff auf die Steuerelemente (Sensoren und Aktoren)

- ``cmd::byString($string);`` : Gibt das entsprechende Objekt des Befehls zurück.
  - ``$string``: Link zum gewünschten Befehl: ``#[objet][equipement][commande]#`` (z. B.: ``#[Appartement][Alarme][Actif]#``)
- ``cmd::byId($id);`` : Gibt das entsprechende Objekt des Befehls zurück.
  - ``$id`` : ID des gewünschten Befehls.
- ``$cmd->execCmd($options = null);`` : Führt den Befehl aus und gibt das Ergebnis zurück.
  - ``$options`` : Optionen für die Ausführung des Befehls (kann pluginspezifisch sein). Grundlegende Optionen (Untertyp des Befehls):
    - ``message`` : ``$option = array('title' => 'titre du message , 'message' => 'Mon message');``
    - ``color`` : ``$option = array('color' => 'couleur en hexadécimal');``
    - ``slider`` : ``$option = array('slider' => 'valeur voulue de 0 à 100');``

#### Zugriff auf die Protokolle

- ``log::add('filename','level','message');``
  - ``filename`` : Name der Protokolldatei.
  - ``level`` : [Debug], [Info], [Fehler], [Ereignis].
  - ``message`` : In die Protokolle einzutragende Meldung.

#### Zugriff auf Szenarien

- ``$scenario->getName();`` : Gibt den Namen des aktuellen Szenarios zurück.
- ``$scenario->getGroup();`` : Gibt die Gruppe des Szenarios zurück.
- ``$scenario->getIsActive();`` : Gibt den Status des Szenarios zurück.
- ``$scenario->setIsActive($active);`` : Ermöglicht es, das Szenario zu aktivieren oder nicht.
  - ``$active`` : 1 aktiv, 0 inaktiv.
- ``$scenario->running();`` : Zeigt an, ob das Szenario gerade ausgeführt wird oder nicht (true / false).
- ``$scenario->save();`` : Speichert die Änderungen.
- ``$scenario->setData($key, $value);`` : Speichert einen Wert (eine Variable).
  - ``$key`` : Wert (int oder string).
  - ``$value`` : zu speichernder Wert (``int``, ``string``, ``array`` oder ``object``).
- ``$scenario->getData($key);`` : Ruft einen Wert (eine Variable) ab.
  - ``$key => 1`` : Wert (int oder string).
- ``$scenario->removeData($key);`` : Löscht einen Datensatz.
- ``$scenario->setLog($message);`` : Schreibt eine Meldung in das Protokoll des Szenarios.
- ``$scenario->persistLog();`` : Erzwingt die Protokollierung (ansonsten wird das Protokoll erst am Ende des Szenarios geschrieben). Achtung, dies kann das Szenario etwas verlangsamen.

> **Tipp**
>
> Hinzufügen einer Suchfunktion im Code-Block: Suchen: Strg + F, dann Enter; Nächstes Ergebnis: Strg + G; Vorheriges Ergebnis: Strg + Umschalt + G

[Szenarien: Kleine Programme unter Freunden](https://kiboost.github.io/jeedom_docs/jeedomV4Tips/CodesScenario/)

### Kommentarblock

Der Kommentarblock verhält sich anders, wenn er ausgeblendet ist. Seine Schaltflächen auf der linken Seite sowie der Titel des Blocks verschwinden und werden beim Darüberfahren mit der Maus wieder angezeigt. Ebenso wird die erste Zeile des Kommentars in Fettschrift angezeigt.
Dadurch kann dieser Block als rein visuelle Trennung innerhalb des Szenarios verwendet werden.

### Die Maßnahmen

Die in den Blöcken hinzugefügten Aktionen verfügen über verschiedene Optionen:

- Ein **aktiviertes** Kontrollkästchen, damit dieser Befehl im Szenario berücksichtigt wird.
- Ein Kontrollkästchen **„Parallel“**, damit dieser Befehl parallel (gleichzeitig) mit den anderen ebenfalls ausgewählten Befehlen ausgeführt wird.
- Ein **vertikaler Doppelpfeil**, um die Aktion zu verschieben. Ziehen Sie ihn einfach von dort per Drag & Drop an die gewünschte Stelle.
- Eine Schaltfläche zum **Löschen** der Aktion.
- Eine Schaltfläche für bestimmte Aktionen, wobei jeweils die Beschreibung dieser Aktion (beim Darüberfahren mit der Maus) angezeigt wird.
- Eine Schaltfläche zum Suchen eines Aktionsbefehls.

> **Tipp**
>
> Je nach ausgewähltem Befehl werden verschiedene zusätzliche Felder angezeigt.

## Mögliche Ersetzungen

### Auslöser

Es gibt spezielle Auslöser (neben denen, die von den Befehlen bereitgestellt werden):

- ``#start#`` : Wird beim (Neu-)Start von Jeedom ausgelöst.
- ``#begin_backup#`` : Ereignis, das zu Beginn einer Datensicherung gesendet wird.
- ``#end_backup#`` : Ereignis, das am Ende einer Datensicherung gesendet wird.
- ``#begin_update#`` : Ereignis, das zu Beginn eines Updates gesendet wird.
- ``#end_update#`` : Ereignis, das am Ende eines Updates gesendet wird.
- ``#begin_restore#`` : Ereignis, das zu Beginn einer Wiederherstellung gesendet wird.
- ``#end_restore#`` : Ereignis, das am Ende einer Wiederherstellung gesendet wird.
- ``#user_connect#`` : Anmeldung eines Benutzers, das Tag `#trigger_value#` enthält den Namen des Benutzers.
- ``#variable(nom_variable)#`` : Änderung des Wertes der Variablen „nom_variable“.
- ``#genericType(GENERIC, #[Object]#)#`` : Ändern eines Befehls vom Typ „Generic“ (GENERIC) im Objekt „Object“.
- ``#new_eqLogic#`` : Dieses Ereignis wird beim Anlegen eines neuen Geräts ausgelöst. Die Tags enthalten „id“ (ID des angelegten Geräts), „name“ (Name des angelegten Geräts) und „eqType“ (Typ/Plugin des angelegten Geräts).

Sie können ein Szenario auch über die beschriebene HTTP-API auslösen [hier](api_http.md).

### Vergleichsoperatoren und Verknüpfungen zwischen Bedingungen

Sie können jedes der folgenden Symbole für Vergleiche in den Bedingungen verwenden:

- ``==`` : Gleichbedeutend mit.
- ``>`` : Streng größer als.
- ``>=`` : Größer oder gleich.
- ``<`` : Streng kleiner als.
- ``<=`` : Kleiner oder gleich.
- ``!=`` : Ungleich, ist nicht gleich.
- ``matches`` : Enthält. Beispiel: ``[Salle de bain][Hydrometrie][etat] matches "/humide/"``.
- ``not(…​ matches …​)`` : Enthält keine. Beispiel:  ``not([Salle de bain][Hydrometrie][etat] matches "/humide/")``.

Sie können jeden Vergleich mit den folgenden Operatoren kombinieren:

Unabhängig davon, ob Sie Vergleiche zwischen verschiedenen Geräten oder innerhalb desselben Geräts anstellen, muss das Gerät immer angegeben werden.
``[Salle de bain][Hydrometrie][température] >= 18 && [Salle de bain][Hydrometrie][température] <= 22``

- ``&&`` : und. **Achtung**, die Verwendung von  : ``ET`` / ``et`` / ``AND`` / ``and`` wird nicht empfohlen; in manchen Fällen mag es zwar funktionieren, bei bestimmten PHP-Funktionen wird es jedoch nicht funktionieren.
- ``||`` : oder. **Achtung**, die Verwendung von  : ``OU`` / ``ou`` / ``OR`` / ``or`` wird nicht empfohlen; in manchen Fällen mag es zwar funktionieren, bei bestimmten PHP-Funktionen wird es jedoch nicht funktionieren.
- ``xor``  : oder exklusiv. **Achtung**, die Verwendung von  : ``XOR`` / ``^`` wird nicht empfohlen; in manchen Fällen mag es zwar funktionieren, bei bestimmten PHP-Funktionen wird es jedoch nicht funktionieren.

### Tags

Ein Tag wird bei der Ausführung des Szenarios durch seinen Wert ersetzt. Sie können die folgenden Tags verwenden:

> **Tipp**
>
> Um die führenden Nullen in der Anzeige zu erhalten, muss die Funktion Date() verwendet werden. Siehe [hier](https://www.php.net/manual/fr/datetime.format.php).

- ``#seconde#`` : Aktuelle Sekunde (ohne führende Nullen, z. B. 6 für 08:07:06).
- ``#hour#`` : Aktuelle Uhrzeit im 24-Stunden-Format (ohne führende Nullen). Beispiel: 8 für 08:07:06 oder 17 für 17:15.
- ``#hour12#`` : Aktuelle Uhrzeit im 12-Stunden-Format (ohne führende Nullen). Beispiel: 8 für 08:07:06.
- ``#minute#`` : Aktuelle Minute (ohne führende Nullen). Beispiel: 7 für 08:07:06.
- ``#day#`` : Aktueller Tag (ohne führende Nullen). Beispiel: 6 für den 06.07.2017.
- ``#month#`` : Aktueller Monat (ohne führende Nullen). Beispiel: 7 für den 06.07.2017.
- ``#year#`` : Laufendes Jahr.
- ``#time#`` : Aktuelle Stunde und Minute. Beispiel: 1715 für 17:15 Uhr.
- ``#timestamp#`` : Anzahl der Sekunden seit dem 1. Januar 1970.
- ``#date#`` : Tag und Monat. Achtung, die erste Zahl steht für den Monat. Beispiel: 1215 für den 15. Dezember.
- ``#week#`` : Ausgabe der Woche.
- ``#sday#`` : Name des Wochentags. Beispiel: Samstag.
- ``#nday#`` : Tagesnummer von 0 (Sonntag) bis 6 (Samstag).
- ``#smonth#`` : Name des Monats. Beispiel: Januar.
- ``#IP#`` : Interne IP-Adresse von Jeedom.
- ``#hostname#`` : Name des Jeedom-Geräts.
- ``#jeedomName#`` : Name des Jeedom.
- ``#trigger#`` : Möglicherweise:
  - ``api`` Wenn der Start über die API ausgelöst wurde,
  - ``TYPEcmd`` Wenn der Start durch einen Befehl ausgelöst wurde, wobei TYPE durch die ID des Plugins ersetzt wird (z. B. virtualCmd),
  - ``schedule`` wenn es durch eine Programmierung gestartet wurde,
  - ``user`` wenn es manuell gestartet wurde,
  - ``start`` zum Start von Jeedom.
- ``#trigger_id#`` : Wenn das Szenario durch einen Befehl ausgelöst wurde, enthält dieses Tag den Wert der ID des Befehls, der es ausgelöst hat. Beispiel: ``#trigger_id# == 19``
- ``#trigger_name#`` : Wenn ein Befehl das Szenario ausgelöst hat, enthält dieses Tag den Namen des Befehls (im Format [Objekt][Gerät][Befehl]). Beispiel: ``#trigger_name# == '[cuisine][lumiere][etat]'``
- ``#trigger_value#`` : Wenn das Szenario durch einen Befehl ausgelöst wurde, enthält dieses Tag den Wert des Befehls, der das Szenario ausgelöst hat. Tipp: Wenn Sie den aktuellen Wert des Befehls wissen möchten, der das Szenario ausgelöst hat (und nicht dessen Wert zum Zeitpunkt der Auslösung), können Sie Folgendes verwenden: ``##trigger_id##`` (doppelt #)
- ``#latitude#`` : Ermöglicht das Abrufen der in der Jeedom-Konfiguration hinterlegten Breitengradangaben
- ``#longitude#`` : Ermöglicht das Abrufen der in der Jeedom-Konfiguration hinterlegten Längengradangabe
- ``#altitude#`` : Ermöglicht das Abrufen der in der Jeedom-Konfiguration hinterlegten Höhenangabe
- ``#sunrise#`` : Ermöglicht das Abrufen der Sonnenaufgangszeit, sofern in der Jeedom-Konfiguration der Breiten- und Längengrad angegeben sind
- ``#sunset#`` : Ermöglicht es, die Zeit des Sonnenuntergangs abzurufen, sofern in der Jeedom-Konfiguration der Breiten- und Längengrad angegeben sind

Außerdem stehen Ihnen die folgenden Tags zur Verfügung, wenn Ihr Szenario durch eine Interaktion ausgelöst wurde:

- #query#: Interaktion, die das Szenario ausgelöst hat.
- #profil#: Profil des Benutzers, der das Szenario ausgelöst hat (kann leer sein).

> **Wichtig**
>
> Wenn ein Szenario durch eine Interaktion ausgelöst wird, wird es zwangsläufig im Schnellmodus ausgeführt. Das heißt, im Thread der Interaktion und nicht in einem separaten Thread.

### Rechenfunktionen

Für die Geräte stehen verschiedene Funktionen zur Verfügung:

- ``average(commande,période)`` & ``averageBetween(commande,start,end)`` : Gibt den Durchschnittswert der Bestellung über den Zeitraum an (period=[Monat, Tag, Stunde, Minute] oder [PHP-Ausdruck](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)) oder zwischen den beiden angegebenen Klemmen (in der Form ``Y-m-d H:i:s`` oder [PHP-Ausdruck](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)).

- ``averageTemporal(commande,période)`` & ``averageTemporalBetween(commande,start,end)`` : Gibt den Durchschnitt der Auftragswerte an, gewichtet nach ihrer Laufzeit im jeweiligen Zeitraum (period=[Monat, Tag, Stunde, Minute] oder [PHP-Ausdruck](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)) oder zwischen den beiden angegebenen Klemmen (in der Form ``Y-m-d H:i:s`` oder [PHP-Ausdruck](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)).

- ``min(commande,période)`` & ``minBetween(commande,start,end)`` : Gibt die Mindestanzahl der Befehle im angegebenen Zeitraum an (period=[Monat, Tag, Stunde, Minute] oder [PHP-Ausdruck](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)) oder zwischen den beiden angegebenen Klemmen (in der Form ``Y-m-d H:i:s`` oder [PHP-Ausdruck](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)).

- ``max(commande,période)`` & ``maxBetween(commande,start,end)`` : Gibt den maximalen Steuerwert für den Zeitraum an (period=[Monat, Tag, Stunde, Minute] oder [PHP-Ausdruck](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)) oder zwischen den beiden angegebenen Klemmen (in der Form ``Y-m-d H:i:s`` oder [PHP-Ausdruck](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)).

- ``duration(commande, valeur, période)`` & ``durationbetween(commande,valeur,start,end)`` : Gibt die Dauer in Minuten an, während der das Gerät den ausgewählten Wert im angegebenen Zeitraum hatte (period=[month,day,hour,min] oder [PHP-Ausdruck](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)) oder zwischen den beiden angegebenen Klemmen (in der Form ``Y-m-d H:i:s`` oder [PHP-Ausdruck](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)).

- ``statistics(commande,calcul,période)`` & ``statisticsBetween(commande,calcul,start,end)`` : Liefert das Ergebnis verschiedener statistischer Berechnungen (Summe, Anzahl, Standardabweichung, Varianz, Mittelwert, Minimum, Maximum) für den Zeitraum (period=[Monat, Tag, Stunde, Minute] oder [PHP-Ausdruck](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)) oder zwischen den beiden angegebenen Klemmen (in der Form ``Y-m-d H:i:s`` oder [PHP-Ausdruck](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)).

- ``tendance(commande,période,seuil)`` : Zeigt den Trend der Steuerung über den Zeitraum an (period=[Monat, Tag, Stunde, Minute] oder [PHP-Ausdruck](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)).

- ``stateDuration(commande)`` : Gibt die Zeit in Sekunden seit der letzten Wertänderung an.
-1: Es gibt keinen Verlauf oder der Wert ist im Verlauf nicht vorhanden.
-2: Der Befehl wird nicht protokolliert.

- ``lastChangeStateDuration(commande,valeur)`` : Gibt die Zeit in Sekunden seit der letzten Zustandsänderung des als Parameter übergebenen Werts an.
-1: Es gibt keinen Verlauf oder der Wert ist im Verlauf nicht vorhanden.
-2 Der Befehl wird nicht protokolliert

- ``lastStateDuration(commande,valeur)`` : Gibt die Dauer in Sekunden an, während der das Gerät zuletzt den ausgewählten Wert hatte.
-1: Es gibt keinen Verlauf oder der Wert ist im Verlauf nicht vorhanden.
-2: Der Befehl wird nicht protokolliert.

- ``age(commande)`` : Gibt das Alter des Befehlswerts in Sekunden an (``collecDate``)
-1: Der Befehl existiert nicht oder ist kein Info-Befehl.

- ``stateChanges(commande,[valeur], période)`` & ``stateChangesBetween(commande, [valeur], start, end)`` : Gibt die Anzahl der Zustandsänderungen an (auf einen bestimmten Wert, sofern angegeben, oder, falls nicht angegeben, im Vergleich zum aktuellen Wert) innerhalb des Zeitraums (period=[month,day,hour,min] oder [PHP-Ausdruck](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)) oder zwischen den beiden angegebenen Klemmen (in der Form ``Y-m-d H:i:s`` oder [PHP-Ausdruck](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)).

- ``lastBetween(commande,start,end)`` : Gibt den zuletzt gespeicherten Wert für das Gerät zwischen den beiden angeforderten Klemmen an (in der Form ``Y-m-d H:i:s`` oder [PHP-Ausdruck](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative)).

- ``variable(mavariable,valeur par défaut)`` : Ruft den Wert einer Variablen oder den gewünschten Standardwert ab.

- ``genericType(GENERIC, #[Object]#)`` : Ruft die Summe der Informationen vom generischen Typ GENERIC im Objekt „Object“ ab.

- ``scenario(scenario)`` : Gibt den Status des Szenarios zurück.
1: In Bearbeitung,
0: Ausgeschaltet,
-1: Deaktiviert,
-2: Das Szenario existiert nicht,
-3: Der Status ist nicht konsistent.
Um den „menschlichen“ Namen des Szenarios zu erhalten, können Sie die entsprechende Schaltfläche rechts neben der Szenariosuche verwenden.

- ``lastScenarioExecution(scenario)`` : Gibt die Zeit in Sekunden seit dem letzten Start des Szenarios an.
0: Das Szenario existiert nicht

- ``collectDate(cmd,[format])`` : Gibt das Datum der letzten Datenerfassung für den als Parameter übergebenen Befehl zurück; mit dem optionalen zweiten Parameter kann das Rückgabeformat festgelegt werden (Details [hier](https://www.php.net/manual/fr/datetime.format.php)).
-1: Der Befehl wurde nicht gefunden,
-2: Der Befehl ist kein Info-Befehl.

- ``valueDate(cmd,[format])`` : Gibt das Datum des letzten bekannten Werts für den als Parameter übergebenen Befehl zurück; mit dem optionalen zweiten Parameter kann das Rückgabeformat festgelegt werden (Details [hier](https://www.php.net/manual/fr/datetime.format.php)).
-1: Der Befehl wurde nicht gefunden,
-2: Der Befehl ist kein Info-Befehl.

- ``eqEnable(equipement)`` : Gibt den Status des Geräts zurück.
-2: Das Gerät ist nicht auffindbar,
1: Das Gerät ist aktiv,
0: Das Gerät ist inaktiv.

- ``value(cmd)`` : Gibt den Wert eines Befehls zurück, wenn dieser nicht automatisch von Jeedom bereitgestellt wird (z. B. beim Speichern des Befehlsnamens in einer Variablen)

- ``tag(montag,[defaut])`` : Ermöglicht das Abrufen des Werts eines Tags oder des Standardwerts, falls dieser nicht vorhanden ist.

- ``name(type,commande)`` : Ermöglicht das Abrufen des Namens des Befehls, des Geräts oder des Objekts. Typ: cmd, eqLogic oder object.

- ``lastCommunication(equipment,[format])`` : Gibt das Datum der letzten Datenübertragung für das als Parameter angegebene Gerät zurück. Mit dem optionalen zweiten Parameter kann das Rückgabeformat festgelegt werden (Details [hier](https://www.php.net/manual/fr/datetime.format.php)). Ein Rückgabewert von -1 bedeutet, dass das Gerät nicht gefunden werden kann. Das Datum der letzten Aktualisierung wird anhand des Befehlstyps „information“ und des Datums der Datenerfassung berechnet.

- ``color_gradient(couleur_debut,couleur_fin,valuer_min,valeur_max,valeur)`` : Gibt eine Farbe zurück, die anhand eines Werts im Bereich „Startfarbe/Endfarbe“ berechnet wird. Der Wert muss zwischen „Min-Wert“ und „Max-Wert“ liegen.

Die Zeiträume und Intervalle dieser Funktionen können auch mit [PHP-Ausdrücke](https://www.php.net/manual/fr/datetime.formats.php#datetime.formats.relative) wie zum Beispiel:

- ``Now`` : jetzt.
- ``Today`` : 00:00 heute (ermöglicht es beispielsweise, Ergebnisse des Tages abzurufen, wenn zwischen ``Today`` und ``Now``).
- ``Last Monday`` : letzten Montag um 00:00 Uhr.
- ``5 days ago`` : vor 5 Tagen.
- ``Yesterday noon`` : gestern Mittag.
- usw.

Hier sind einige praktische Beispiele, um die von diesen verschiedenen Funktionen zurückgegebenen Werte besser zu verstehen:

| Steckdose mit folgenden Werten: | 000 (für 10 Minuten) 11 (für 1 Stunde) 000 (für 10 Minuten)    |
|--------------------------------------|--------------------------------------|
| ``average(prise,période)``             | Gibt den Durchschnitt aus den Werten 0 und 1 zurück (kann  |
| | durch Polling beeinflusst werden) |
| ``averageBetween(#[Salle de bain][Hydrometrie][Humidité]#,2015-01-01 00:00:00,2015-01-15 00:00:00)`` | Gibt den Durchschnittswert der Bestellungen zwischen dem 1. Januar 2015 und dem 15. Januar 2015 zurück |
| ``min(prise,période)``                 | Gibt 0 zurück: Die Steckdose wurde innerhalb des Zeitraums erfolgreich ausgeschaltet |
| ``minBetween(#[Salle de bain][Hydrometrie][Humidité]#,2015-01-01 00:00:00,2015-01-15 00:00:00)`` | Zeigt den Mindestbestellwert zwischen dem 1. Januar 2015 und dem 15. Januar 2015 an |
| ``max(prise,période)``                 | Gibt 1 zurück: Die Steckdose wurde innerhalb des Zeitraums eingeschaltet |
| ``maxBetween(#[Salle de bain][Hydrometrie][Humidité]#,2015-01-01 00:00:00,2015-01-15 00:00:00)`` | Zeigt die höchste Bestellmenge zwischen dem 1. Januar 2015 und dem 15. Januar 2015 an |
| ``duration(prise,1,période)``          | Gibt 60 zurück: Die Steckdose war während 60 Minuten in diesem Zeitraum eingeschaltet (auf 1) |
| ``durationBetween(#[Salon][Prise][Etat]#,0,Last Monday,Now)``   | Gibt die Zeit in Minuten an, die die Steckdose seit dem vergangenen Montag ausgeschaltet war. |
| ``statistics(prise,count,période)``    | Ergebnis 8: In diesem Zeitraum gab es 8 Statusmeldungen |
| ``tendance(prise,période,0.1)``        | Gibt -1 zurück: Abwärtstrend    |
| ``stateDuration(prise)``               | Gibt 600 zurück: Die Steckdose befindet sich seit 600 Sekunden (10 Minuten) in ihrem aktuellen Zustand |
| ``lastChangeStateDuration(prise,0)``   | Gibt 600 zurück: Die Steckdose wurde zuletzt vor 600 Sekunden (10 Minuten) ausgeschaltet (Wechsel auf 0)     |
| ``lastChangeStateDuration(prise,1)``   | Gibt 4200 zurück: Die Steckdose wurde (Umschaltung auf 1) zuletzt vor 4200 Sekunden (1 Stunde und 10 Minuten) eingeschaltet |
| ``lastStateDuration(prise,0)``         | Gibt 600 zurück: Die Steckdose ist seit 600 Sekunden (10 Minuten) ausgeschaltet     |
| ``lastStateDuration(prise,1)``         | Gibt 3600 zurück: Die Steckdose wurde zuletzt vor 3600 Sekunden (1 Stunde) eingeschaltet |
| ``stateChanges(prise,période)``        | Rückgabewert 3: Die Steckdose hat ihren Zustand während des Zeitraums dreimal geändert (sofern es sich bei dem Befehl „info“ um einen binären Befehl handelt) |
| ``stateChanges(prise,0,période)``      | Rückmeldung 2: Die Steckdose wurde während des Zeitraums zweimal ausgeschaltet (Übergang auf 0) |
| ``stateChanges(prise,1,période)``      | Gibt 1 zurück: Die Steckdose wurde während des Zeitraums einmal eingeschaltet (Wechsel auf 1) |
| ``lastBetween(#[Salle de bain][Hydrometrie][Température]#,Yesterday,Today)`` | Gibt die zuletzt gestern aufgezeichnete Temperatur zurück. |
| ``variable(plop,10)``                  | Gibt den Wert der Variablen „plop“ zurück oder 10, wenn sie leer ist oder nicht existiert |
| ``scenario(#[Salle de bain][Lumière][Auto]#)`` | Gibt 1 zurück, wenn das Szenario läuft, 0, wenn es gestoppt ist, -1, wenn es deaktiviert ist, -2, wenn das Szenario nicht existiert, und -3, wenn der Status inkonsistent ist |
| ``lastScenarioExecution(#[Salle de bain][Lumière][Auto]#)``   | Gibt 300 zurück, wenn das Szenario zuletzt vor 5 Minuten gestartet wurde |
| ``collectDate(#[Salle de bain][Hydrometrie][Humidité]#)``     | Veröffentlicht am 14.02.2021 um 17:50:12 Uhr |
| ``valueDate(#[Salle de bain][Hydrometrie][Humidité]#)`` | Veröffentlicht am 14.02.2021 um 17:45:12 Uhr |
| ``eqEnable(#[Aucun][Basilique]#)``       | Gibt -2 zurück, wenn das Gerät nicht gefunden wird, 1, wenn das Gerät aktiv ist, und 0, wenn es inaktiv ist |
| ``tag(montag,toto)``                   | Gibt den Wert „montag“ zurück, sofern dieser vorhanden ist; andernfalls den Wert „toto“ |
| ``name(eqLogic,#[Salle de bain][Hydrometrie][Humidité]#)``     | Zurück zu Hydrometrie |

### Mathematische Funktionen

Ein Toolkit mit generischen Funktionen kann auch zur Durchführung von Umrechnungen oder Berechnungen verwendet werden:

- ``rand(1,10)`` : Gibt eine Zufallszahl zwischen 1 und 10 aus.
- ``randText(texte1;texte2;texte…​..)`` : Gibt einen der Texte nach dem Zufallsprinzip zurück (die Texte müssen durch ein Semikolon „;“ getrennt sein). Die Anzahl der Texte ist unbegrenzt.
- ``randomColor(min,max)`` : Gibt eine zufällige Farbe zwischen zwei Grenzwerten an (0 => rot, 50 => grün, 100 => blau).
- ``trigger(commande)`` : Ermöglicht es, den Auslöser des Szenarios zu ermitteln oder festzustellen, ob tatsächlich der als Parameter übergebene Befehl das Szenario ausgelöst hat. **=> Veraltet – es wird empfohlen, stattdessen das Tag #trigger# zu verwenden**
- ``triggerValue()`` : Zeigt den Wert des Auslösers des Szenarios an. **=> Veraltet – es empfiehlt sich, stattdessen das Tag #trigger_value# zu verwenden**
- ``round(valeur,[decimal])`` : Rundet nach oben, [decimal] Anzahl der Dezimalstellen nach dem Komma.
- ``odd(valeur)`` : Gibt an, ob eine Zahl ungerade ist oder nicht. Gibt 1 zurück, wenn die Zahl ungerade ist, andernfalls 0.
- ``median(commande1,commande2…​.commandeN)`` : Gibt den Median der Werte zurück.
- ``avg(commande1,commande2…​.commandeN)`` : Gibt den Durchschnittswert zurück.
- ``time_op(time,value)`` : Ermöglicht die Durchführung von Zeitoperationen mit „time=Zeit“ (z. B. 1530) und „value=Wert“, der in Minuten addiert oder subtrahiert werden soll.
- ``time_between(time,start,end)`` : Ermöglicht es, zu prüfen, ob eine Zeit zwischen zwei Werten liegt, mit ``time=temps`` (z. B.: 1530), ``start=temps``, ``end=temps``. Die Werte „start“ und „end“ können sich über Mitternacht erstrecken.
- ``time_diff(date1,date2[,format, round])`` : Ermöglicht es, die Differenz zwischen zwei Datumsangaben zu ermitteln (die Datumsangaben müssen im Format JJJJ/MM/TT HH:MM:SS vorliegen). Standardmäßig gibt die Methode die Differenz in Tagen zurück. Sie können die Ausgabe auch in Sekunden (s), Minuten (m) oder Stunden (h) anfordern. Beispiel in Sekunden ``time_diff(2019-02-02 14:55:00,2019-02-25 14:55:00,s)``. Die Differenz wird als Absolutwert zurückgegeben, es sei denn, Sie geben ``f`` (``sf``, ``mf``, ``hf``, ``df``). Sie können auch Folgendes verwenden: ``dhms`` die nicht zurückgegeben wird, Beispiel ``7j 2h 5min 46s``. Der optionale Parameter „round“ rundet auf x Stellen nach dem Komma (Standardwert: 2). Beispiel: ``time_diff(2020-02-21 20:55:28,2020-02-28 23:01:14,df, 4)``.
- ``formatTime(time)`` : Ermöglicht die Formatierung der Rückgabe eines Strings ``#time#``.
- ``floor(time/60)`` : Ermöglicht die Umrechnung von Sekunden in Minuten oder von Minuten in Stunden (``floor(time/3600)`` (Sekunden in Stunden umrechnen).
- ``convertDuration(secondes)`` : Ermöglicht die Umrechnung von Sekunden in Tage/Stunden/Minuten/Sekunden.

Und hier sind einige praktische Beispiele:

| Funktionsbeispiel | Zurückgegebenes Ergebnis |
|--------------------------------------|--------------------------------------|
| ``randText(il fait #[salon][oeil][température]#;La température est de #[salon][oeil][température]#;Actuellement on a #[salon][oeil][température]#)`` | Die Funktion gibt bei jeder Ausführung zufällig einen dieser Texte zurück. |
| ``randomColor(40,60)``                 | Gibt eine zufällige Farbe zurück, die dem Grün nahekommt. |
| ``round(#[Salle de bain][Hydrometrie][Humidité]# / 10)`` | Gibt 9 zurück, wenn die Luftfeuchtigkeit 85 % beträgt |
| ``odd(3)``                             | Gibt 1 zurück |
| ``median(15,25,20)``                   | Gibt 20 zurück
| ``avg(10,15,18)``                      | Version 14.3 |
| ``time_op(#time#, -90)``               | Wenn es 16:50 Uhr ist, gibt das Programm zurück: 1650 – 0130 = 1520 |
| ``formatTime(1650)``                   | Um 16:50 Uhr |
| ``floor(130/60)``                     | Gibt 2 zurück (Minuten, wenn 130 s, oder Stunden, wenn 130 m) |
| ``convertDuration(3600)``             | Dauer: 1 Std. 0 Min. 0 Sek. |
| ``convertDuration(duration(#[Chauffage][Module chaudière][Etat]#,1, first day of this month)*60)`` | Gibt die Einschaltdauer in Tagen/Stunden/Minuten an, die das Modul seit dem 1. Tag des Monats im Zustand 1 verbracht hat |

### Die verschiedenen Funktionen

- ``sun(elevation)`` : Gibt den Sonnenstand in Grad an (Achtung: Sie müssen Ihre geografischen Koordinaten in den Jeedom-Einstellungen angegeben haben)
- ``sun(azimuth)`` : Gibt den Azimut der Sonne in Grad an (Achtung: Sie müssen Ihre geografischen Koordinaten in den Jeedom-Einstellungen angegeben haben)

### Spezifische Befehle

Neben den Hausautomationsbefehlen stehen Ihnen folgende Aktionen zur Verfügung:

- **Pause** (Sleep): Pause von x Sekunden. *(maximale Dauer: 1 Stunde)*
- **Variable** (Variable): Anlegen/Ändern einer Variablen oder des Werts einer Variablen.
- **Variable löschen** (delete_variable): Ermöglicht das Löschen einer Variablen.
- **genericType(GENERIC, #[Object]#)**: Änderung eines Befehls (info oder event) oder einer Aktion (execCmd) durch einen generischen Typ in einem Objekt. Zum Beispiel: Alle Lichter im Wohnzimmer ausschalten.
- **Szenario** (scenario): Ermöglicht die Steuerung von Szenarien. Über den Bereich „Tags“ können Tags an das Szenario gesendet werden, z. B.: montag=2 (Achtung: Es dürfen nur Buchstaben von a bis z verwendet werden. Keine Großbuchstaben, keine Akzente und keine Sonderzeichen). Das Tag wird im Zielszenario mit der Funktion tag(montag) abgerufen.
  - Starten: Startet das Szenario in einem separaten Thread. Das gestartete Szenario wird unabhängig vom aufrufenden Szenario ausgeführt.
  - Starten (Synchronisieren): Startet das aufgerufene Szenario und hält das aufrufende Szenario an, bis das aufgerufene Szenario vollständig ausgeführt wurde.
  - Beenden: Beendet das Szenario.
  - Aktivieren: Aktiviert ein deaktiviertes Szenario.
  - Deaktivieren: Deaktiviert das Szenario. Es wird unabhängig von den Auslösern nicht mehr gestartet.
  - Zurücksetzen der SI: Ermöglicht das Zurücksetzen des Status der **SI**. Dieser Status wird verwendet, um die Wiederholung von Aktionen einer **SI** zu verhindern, wenn die Auswertung der Bedingung dasselbe Ergebnis wie die vorherige Auswertung liefert.
- **Stopp** (stop): Beendet das Szenario.
- **Warten** (wait): Wartet, bis die Bedingung erfüllt ist; das Timeout wird in Sekunden angegeben. *(maximale Dauer: 1 Stunde)*
- **Zum Design wechseln** (gotodesign): Ändert das in allen Browsern angezeigte Design auf das gewünschte Design.
- **Protokoll eintragen** (Protokoll): Ermöglicht das Hinzufügen einer Meldung zum Protokoll.
- **Nachricht erstellen** (Nachricht): Ermöglicht das Hinzufügen einer Nachricht im Nachrichtencenter.
- **Aktivieren/Deaktivieren, Gerät ausblenden/anzeigen** (Gerät): Ermöglicht es, die Eigenschaften eines Geräts (sichtbar/unsichtbar, aktiv/inaktiv) zu ändern.
- **Eine Anfrage stellen** (ask): Damit wird Jeedom angewiesen, dem Benutzer eine Frage zu stellen. Die Antwort wird in einer Variablen gespeichert; anschließend muss lediglich deren Wert überprüft werden.
Derzeit sind nur die Plugins für SMS, Slack, Telegram und Snips sowie die mobile App kompatibel.
Achtung, diese Funktion ist blockierend. Solange keine Antwort vorliegt oder das Timeout nicht erreicht ist, wartet das Szenario. Hinweis: Für eine freie Antwort fügen Sie * in die Liste der möglichen Antworten ein.
- **Jeedom herunterfahren** (jeedom_poweroff): Weist Jeedom an, sich herunterzufahren.
- **Einen Text/einen Wert zurückgeben** (scenario_return): Gibt beispielsweise bei einer Interaktion einen Text oder einen Wert zurück.
- **Symbol** (icon): Ermöglicht es, das Symbol für das Szenario zu ändern.
- **Alarm** (alert): Ermöglicht die Anzeige einer kurzen Alarmmeldung in allen Browsern, in denen eine Jeedom-Seite geöffnet ist. Außerdem können Sie zwischen 4 Alarmstufen wählen.
- **Pop-up** (Popup): Ermöglicht die Anzeige eines Pop-ups, das in allen Browsern, in denen eine Jeedom-Seite geöffnet ist, unbedingt bestätigt werden muss.
- **Bericht** (Report): Ermöglicht es, eine Ansicht im Format (PDF, PNG, JPEG oder SVG) zu exportieren und über einen Befehl vom Typ „Nachricht“ zu versenden. Achtung: Wenn Ihre Internetverbindung über unverschlüsseltes HTTPS läuft, funktioniert diese Funktion nicht. Es ist HTTP oder verschlüsseltes HTTPS erforderlich. Die „Verzögerung“ wird in Millisekunden (ms) angegeben.
- **DANS-/A-Blöcke aus einem Szenario löschen** (remove_inat): Ermöglicht das Löschen der Programmierung aller DANS- und A-Blöcke aus einem Szenario.
- **Ereignis** (event): Ermöglicht es, einen Wert beliebig in einen Befehl vom Typ „Information“ zu übertragen.
- **Tag** (Tag): Ermöglicht das Hinzufügen/Bearbeiten eines Tags (das Tag existiert nur während der laufenden Ausführung des Szenarios, im Gegensatz zu Variablen, die auch nach Beendigung des Szenarios bestehen bleiben).
- **Farbige Symbole im Dashboard** (setColoredIcon): Hiermit können Sie festlegen, ob die Symbole im Dashboard farbig angezeigt werden sollen oder nicht.
- **Thema wechseln** (changetheme): Ermöglicht es, das aktuelle Design der Benutzeroberfläche auf „Dark“ oder „Light“ umzustellen.
- **Verlauf exportieren** (exportHistory): Ermöglicht es, den Verlauf einer Bestellung als CSV-Datei zu exportieren (z. B. zum Versenden per E-Mail). Sie können mehrere Bestellungen angeben (getrennt durch &&). Die Auswahl des Zeitraums erfolgt in folgender Form:
  - "-1 Monat" => -1 Monat
  - "-1 Tag um Mitternacht" => -1 Tag um Mitternacht
  - "now" => jetzt
  - "monday this week midnight" => Montag dieser Woche um Mitternacht
  - „letzten Sonntag um 23:59 Uhr“ => am vergangenen Sonntag um 23:59 Uhr
  - "letzter Tag des Vormonats um 23:59 Uhr" => letzter Tag des Vormonats um 23:59 Uhr
  - „first day of january this year midnight“ => am ersten Tag des Januars um Mitternacht
  - ...

### Szenario-Vorlage

Mit dieser Funktion lässt sich ein Szenario in eine Vorlage umwandeln, um es beispielsweise auf ein anderes Jeedom-System anzuwenden.

Wenn Sie oben auf der Seite auf die Schaltfläche **Vorlage** klicken, öffnen Sie das Fenster zur Verwaltung der Vorlagen.

Von hier aus haben Sie folgende Möglichkeiten:

- Eine Vorlage an Jeedom senden (zuvor abgerufene JSON-Datei).
- Die Liste der im Market verfügbaren Szenarien aufrufen.
- Erstellen Sie eine Vorlage auf der Grundlage des aktuellen Szenarios (vergessen Sie nicht, einen Namen anzugeben).
- Die derzeit in Ihrem Jeedom vorhandenen Vorlagen anzeigen.

Wenn Sie auf eine Vorlage klicken, können Sie:

- **Teilen**: Die Vorlage im Market teilen.
- **Löschen**: Die Vorlage löschen.
- **Herunterladen**: Laden Sie die Vorlage als JSON-Datei herunter, um sie beispielsweise an ein anderes Jeedom-Gerät zu senden.

Darunter finden Sie den Bereich, in dem Sie Ihre Vorlage auf das aktuelle Szenario anwenden können.

Da sich die Befehle von einem Jeedom zum anderen oder von einer Anlage zur anderen unterscheiden können, fordert Jeedom Sie auf, die Zuordnung zwischen den bei der Erstellung der Vorlage vorhandenen Befehlen und den bei Ihnen vorhandenen Befehlen vorzunehmen. Sie müssen lediglich die Zuordnung der Befehle ausfüllen und anschließend auf „Übernehmen“ klicken.

## Hinzufügen einer PHP-Funktion

> **WICHTIG**
>
> Das Hinzufügen von PHP-Funktionen ist fortgeschrittenen Benutzern vorbehalten. Der kleinste Fehler kann fatale Folgen für Ihr Jeedom haben.

### Einrichtung

Gehen Sie in die Jeedom-Konfiguration, dann zu „OS/DB“ und starten Sie den Datei-Editor.

Gehen Sie in den Ordner „data“, dann in den Ordner „PHP“ und klicken Sie auf die Datei „user.function.class.php“.

In dieser *Klasse* können Sie Ihre Funktionen hinzufügen; dort finden Sie ein Beispiel für eine einfache Funktion.

> **WICHTIG**
>
> Sollten Sie ein Problem haben, können Sie jederzeit zur Originaldatei zurückkehren, indem Sie den Inhalt von ``user.function.class.sample.php`` in ``user.function.class.php``
