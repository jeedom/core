# Interactions
**Werkzeuge → Interaktionen**

Mit dem Interaktionssystem in Jeedom können Sie Aktionen über Text- oder Sprachbefehle ausführen.

Diese Bestellungen erhalten Sie von :

- SMS : Senden Sie eine SMS, um Befehle zu starten (Aktion) oder stellen Sie eine Frage (Info)).
- Katze : Telegramm, Slack usw.
- Vokal : diktieren Sie eine Phrase mit Siri, Google Now, SARAH usw. Befehle starten (Aktion) oder eine Frage stellen (Info).
- Http : Starten Sie eine HTTP-URL, die den Text enthält (z. Tasker, Slack), um Befehle zu starten (Aktion) oder eine Frage zu stellen (Info).

Der Wert von Interaktionen liegt in der vereinfachten Integration in andere Systeme wie Smartphones, Tablets, andere Hausautomationsboxen usw.

> **Spitze**
>
> Sie können eine Interaktion öffnen, indem Sie dies tun :
> - Klicken Sie auf eine davon.
> - Strg Clic oder Clic Center, um es in einem neuen Browser-Tab zu öffnen.

Sie haben eine Suchmaschine, um die Anzeige von Interaktionen zu filtern. Die Escape-Taste bricht die Suche ab.
Rechts neben dem Suchfeld befinden sich drei Schaltflächen, die an mehreren Stellen in Jeedom gefunden wurden:
- Das Kreuz, um die Suche abzubrechen.
- Der geöffnete Ordner zum Entfalten aller Bedienfelder und Anzeigen aller Interaktionen.
- Der geschlossene Ordner zum Falten aller Panels.

Sobald Sie eine Interaktion konfiguriert haben, haben Sie ein Kontextmenü mit dem rechten Klick auf die Registerkarten der Interaktion. Sie können auch eine Strg-Klick- oder Klick-Mitte verwenden, um eine andere Interaktion direkt in einer neuen Browser-Registerkarte zu öffnen.

## Interactions

Am oberen Rand der Seite befinden sich 3 Schaltflächen :

- **Hinzufügen** : Ermöglicht das Erstellen neuer Interaktionen.
- **Regenerat** : Recréer toutes les interactions (peut être très long &gt; 5mn).
- **Test** : Öffnen Sie einen Dialog, um einen Satz zu schreiben und zu testen.

> **Spitze**
>
> Wenn Sie eine Interaktion haben, die beispielsweise die Sätze für Lichter generiert, und Sie ein neues Lichtsteuermodul hinzufügen, müssen Sie entweder alle Interaktionen neu generieren oder zur betreffenden Interaktion gehen und sie erneut erstellen, um sie zu erstellen die Sätze dieses neuen Moduls.

## Principe

Das Prinzip der Schöpfung ist recht einfach : Wir werden einen generierenden Modellsatz definieren, der es Jeedom ermöglicht, einen oder mehrere Hunderte anderer Sätze zu erstellen, die mögliche Kombinationen des Modells darstellen.

Wir werden Antworten auf die gleiche Weise mit einem Modell definieren (dies ermöglicht Jeedom, mehrere Antworten auf eine einzelne Frage zu haben).

Wir können auch einen auszuführenden Befehl definieren, wenn beispielsweise die Interaktion nicht mit einer Aktion, sondern mit Informationen verknüpft ist oder wenn wir danach eine bestimmte Aktion ausführen möchten (es ist auch möglich, ein Szenario auszuführen, um es zu steuern mehrere Bestellungen…).

## Configuration

Die Konfigurationsseite besteht aus mehreren Registerkarten und Schaltflächen :

- **Sätze** : Zeigt die Anzahl der Sätze der Interaktion an (ein Klick darauf zeigt Sie).
- **Rekord** : Zeichnet die aktuelle Interaktion auf.
- **Entfernen** : Aktuelle Interaktion löschen.
- **Duplikat** : Dupliziere die aktuelle Interaktion.

### Registerkarte &quot;Allgemein&quot;

- **Name** : Interaktionsname (kann leer sein, der Name ersetzt den Anforderungstext in der Interaktionsliste).
- **Gruppe** : Interaktionsgruppe, dies ermöglicht es, sie zu organisieren (kann leer sein, wird daher in der Gruppe "keine" sein").
- **Aktiva** : Ermöglicht das Aktivieren oder Deaktivieren der Interaktion.
- **Anwendung** : Der generierende Modellsatz (erforderlich).
- **Synonym** : Ermöglicht das Definieren von Synonymen für die Namen der Befehle.
- **Antwort** : Die Antwort zu geben.
- **Warten Sie, bevor Sie antworten (s)** : Fügen Sie eine Verzögerung von X Sekunden hinzu, bevor Sie die Antwort generieren. So können Sie beispielsweise auf die Rückkehr eines Lampenstatus warten, bevor Sie beantwortet werden.
- **Binäre Konvertierung** : Konvertiert beispielsweise Binärwerte in Öffnen / Schließen (nur für Befehle vom Typ Binärinfo)).
- **Autorisierte Benutzer** : Beschränkt die Interaktion auf bestimmte Benutzer (Anmeldungen getrennt durch |).

### Registerkarte Filter

- **Beschränken Sie sich auf die Eingabe von Befehlen** : Ermöglicht die Verwendung nur der Arten von Aktionen, Informationen oder der beiden Arten.
- **Beschränken Sie sich auf Bestellungen mit Subtyp** : Beschränkt die Generierung auf einen oder mehrere Untertypen.
- **Beschränkung auf Bestellungen mit Einheit** : Ermöglicht es Ihnen, die Generierung auf eine oder mehrere Einheiten zu beschränken (Jeedom erstellt die Liste automatisch aus den in Ihren Bestellungen definierten Einheiten).
- **Beschränkung auf Bestellungen, die zum Objekt gehören** : Beschränkt die Generierung auf ein oder mehrere Objekte (Jeedom erstellt die Liste automatisch aus den von Ihnen erstellten Objekten).
- **Beschränken Sie sich auf das Plugin** : Ermöglicht es Ihnen, die Generierung auf ein oder mehrere Plugins zu beschränken (Jeedom erstellt die Liste automatisch aus installierten Plugins).
- **Beschränkung auf Kategorie** : Beschränkt die Generierung auf eine oder mehrere Kategorien.
- **Beschränkung auf Ausrüstung** : Beschränkt die Generierung auf ein einzelnes Gerät / Modul (Jeedom erstellt die Liste automatisch aus den Geräten / Modulen, über die Sie verfügen).

### Registerkarte &quot;Aktionen&quot;

Verwenden Sie diese Option, wenn Sie einen oder mehrere bestimmte Befehle als Ziel festlegen oder bestimmte Parameter übergeben möchten.

#### Exemples

> **Notiz**
>
> Die Screenshots können im Hinblick auf Entwicklungen unterschiedlich sein.

#### Einfache Interaktion

Der einfachste Weg, eine Interaktion zu konfigurieren, besteht darin, ihr ein starres Generatormodell zu geben, ohne dass eine Variation möglich ist. Diese Methode zielt sehr genau auf einen Auftrag oder ein Szenario ab.

Im folgenden Beispiel sehen wir im Feld &quot;Anfrage&quot; den genauen Satz, der zum Auslösen der Interaktion bereitgestellt werden soll. Hier, um die Wohnzimmer Deckenleuchte einzuschalten.

![interact004](../images/interact004.png)

In dieser Erfassung sehen wir die Konfiguration für eine Interaktion, die mit einer bestimmten Aktion verknüpft ist. Diese Aktion wird im Teil &quot;Aktion&quot; der Seite definiert.

Wir können uns sehr gut vorstellen, dasselbe mit mehreren Aktionen zu tun, um mehrere Lampen im Wohnzimmer anzuzünden, wie im folgenden Beispiel :

![interact005](../images/interact005.png)

In den beiden obigen Beispielen ist der Modellsatz identisch, aber die daraus resultierenden Aktionen ändern sich entsprechend der Konfiguration im Teil "Aktion". Daher können wir uns bereits mit einer einfachen Interaktion mit einem einzelnen Satz Aktionen vorstellen, die zwischen diesen kombiniert werden verschiedene Befehle und verschiedene Szenarien (wir können auch Szenarien im Aktionsteil von Interaktionen auslösen).

> **Spitze**
>
> Um ein Szenario hinzuzufügen, erstellen Sie eine neue Aktion, schreiben Sie &quot;Szenario&quot; ohne Akzent und drücken Sie die Tabulatortaste auf Ihrer Tastatur, um die Szenarioauswahl aufzurufen.

#### Interaktion mit mehreren Befehlen

Hier sehen wir das ganze Interesse und die ganze Kraft von Interaktionen. Mit einem Modellsatz können wir Sätze für eine ganze Gruppe von Befehlen generieren.

Wir werden das oben Gesagte fortsetzen, die Aktionen löschen, die wir hinzugefügt haben, und anstelle des festen Satzes in &quot;Anfrage&quot; die Tags verwenden **\.#commande\.#** und **\.#equipement\.#**. Jeedom wird diese Tags daher durch den Namen der Befehle und den Namen des Geräts ersetzen (wir können sehen, wie wichtig es ist, konsistente Befehls- / Gerätenamen zu haben).

![interact006](../images/interact006.png)

Wir können hier also sehen, dass Jeedom aus unserem Modell 152 Sätze generiert hat. Sie sind jedoch nicht sehr gut gebaut und wir haben von allem etwas.

Um Ordnung zu schaffen, verwenden wir die Filter (rechter Teil unserer Konfigurationsseite)). In diesem Beispiel möchten wir Sätze generieren, um das Licht einzuschalten. Wir können also den Info-Befehlstyp deaktivieren (wenn ich speichere, habe ich nur noch 95 Sätze), und dann können wir in den Untertypen nur "Standard" aktivieren, das der Aktionsschaltfläche entspricht ( es sind also nur noch 16 Sätze übrig).

![interact007](../images/interact007.png)

Es ist besser, aber wir können es noch natürlicher machen. Wenn ich das generierte Beispiel "Bei Eingabe" nehme, wäre es schön, diesen Satz in "Eintrag einschalten" oder "Eintrag einschalten" umwandeln zu können". Zu diesem Zweck hat Jeedom unter dem Anforderungsfeld ein Synonymfeld, mit dem wir den Namen der Befehle in unseren &quot;generierten&quot; Sätzen unterschiedlich benennen können. Hier ist es &quot;on&quot;, ich habe sogar &quot;on2&quot; &quot;in Modulen, die 2 Ausgänge steuern können.

In Synonymen geben wir daher den Namen des Befehls und die zu verwendenden Synonyme an :

![interact008](../images/interact008.png)

Wir können hier eine etwas neue Syntax für Synonyme sehen. Ein Befehlsname kann mehrere Synonyme haben, hier hat "Ein" das Synonym "Einschalten" und "Einschalten"". Die Syntax lautet daher "*Name der Bestellung*" ***=*** "*Synonym 1*"***,*** "*Synonym 2*" (Wir können so viele Synonyme setzen, wie wir wollen). Um dann Synonyme für einen anderen Befehlsnamen hinzuzufügen, fügen Sie einfach einen vertikalen Balken nach dem letzten Synonym hinzu "*|*" Danach können Sie den Befehl erneut benennen, der Synonyme für den ersten Teil usw. enthält.

Es ist schon besser, aber es fehlt immer noch für den Befehl &quot;on&quot; &quot;input&quot; das &quot;l&quot; und für andere das &quot;la&quot; oder &quot;le&quot; oder &quot;a&quot; usw. Wir könnten den Namen des Geräts ändern, um es hinzuzufügen, es wäre eine Lösung, andernfalls könnten wir die Variationen in der Anfrage verwenden. Dies besteht aus der Auflistung einer Reihe möglicher Wörter an einer Stelle im Satz. Jeedom generiert daher Sätze mit diesen Variationen.

![interact009](../images/interact009.png)

Wir haben jetzt etwas korrektere Sätze mit Sätzen, die nicht korrekt sind, für unser Beispiel "on" "". so finden wir &quot;Eintrag einschalten&quot;, &quot;Eintrag einschalten&quot;, &quot;Eintrag einschalten&quot;, &quot;Eintrag einschalten&quot; usw. Wir haben also alle möglichen Varianten mit dem, was wir zwischen "\ [\]" und diesem für jedes Synonym hinzugefügt haben, was schnell viele Sätze erzeugt (hier 168)).

Um zu verfeinern und keine unwahrscheinlichen Dinge wie &quot;Schalten Sie den Fernseher ein&quot; zu haben, können wir Jeedom erlauben, syntaktisch falsche Anfragen zu löschen. Es wird daher gelöscht, was zu weit von der tatsächlichen Syntax eines Satzes entfernt ist. In unserem Fall gehen wir von 168 Sätzen auf 130 Sätze.

![interact010](../images/interact010.png)

Es ist daher wichtig, dass Sie Ihre Modellsätze und Synonyme gut erstellen und die richtigen Filter auswählen, um nicht zu viele unnötige Sätze zu generieren. Persönlich finde ich es interessant, einige Inkonsistenzen des Stils &quot;ein Eintrag&quot; zu haben, denn wenn Sie zu Hause eine ausländische Person haben, die nicht richtig Französisch spricht, funktionieren die Interaktionen immer noch.

### Passen Sie die Antworten an

Bis jetzt hatten wir als Antwort auf eine Interaktion einen einfachen Satz, der nicht viel sagte, außer dass etwas passiert ist. Die Idee wäre, dass Jeedom uns etwas genauer sagt, was er getan hat. Hier kommt das Antwortfeld ins Spiel, in dem wir die Rückgabe gemäß dem ausgeführten Befehl anpassen können.

Dazu verwenden wir wieder das Jeedom Tag. Für unsere Lichter können wir eine Phrase des Stils verwenden : Ich habe \ eingeschaltet#equipement\.# (siehe Screenshot unten).

![interact011](../images/interact011.png)

Sie können auch einen beliebigen Wert aus einem anderen Befehl hinzufügen, z. B. Temperatur, Anzahl der Personen usw.

![interact012](../images/interact012.png)

### Binäre Konvertierung

Binäre Konvertierungen gelten für Befehle vom Typ Info, deren Subtyp binär ist (gibt nur 0 oder 1 zurück). Sie müssen also die richtigen Filter aktivieren, wie wir auf dem Screenshot etwas unten sehen können (für die Kategorien können wir alle überprüfen, zum Beispiel habe ich nur Licht gehalten).

![interact013](../images/interact013.png)

Wie wir hier sehen können, habe ich für die Anfrage fast die gleiche Struktur beibehalten (es ist freiwillig, sich auf die Einzelheiten zu konzentrieren). Natürlich habe ich die Synonyme angepasst, um etwas Kohärentes zu haben. Für die Antwort ist es jedoch **Imperativ** nur \ setzen#valeur\.# Dies stellt die 0 oder 1 dar, die Jeedom durch die folgende binäre Konvertierung ersetzen wird.

Das Feld **Binäre Konvertierung** muss 2 Antworten enthalten : zuerst die Antwort, wenn der Wert des Befehls 0 wert ist, dann ein vertikaler Balken "|" Trennung und schließlich die Antwort, wenn der Befehl 1 wert ist. Hier sind die Antworten einfach nein und ja, aber wir könnten einen etwas längeren Satz setzen.

> **Warnung**
>
> Tags funktionieren nicht in binären Konvertierungen.

### Autorisierte Benutzer

Im Feld "Autorisierte Benutzer" können nur bestimmte Personen zur Ausführung des Befehls autorisiert werden. Sie können mehrere Profile erstellen, indem Sie sie durch a trennen "|".

Beispiel : personne1|personne2

Wir können uns vorstellen, dass ein Alarm von einem Kind oder einem Nachbarn aktiviert oder deaktiviert werden kann, der in Ihrer Abwesenheit kommen würde, um die Pflanzen zu gießen.

### Regexp-Ausschluss

Es ist möglich zu erstellen [Regexp](https://fr.wikipedia.org/wiki/Expression_rationnelle) Ausschluss: Wenn ein generierter Satz mit diesem regulären Ausdruck übereinstimmt, wird er gelöscht. Das Interesse besteht darin, falsch positive Ergebnisse entfernen zu können, dh einen von Jeedom erzeugten Satz, der etwas aktiviert, das nicht dem entspricht, was wir wollen, oder das eine andere Interaktion stören würde, die einen ähnlichen Satz hätte.

Wir haben 2 Stellen, an denen Sie einen Regexp anwenden können :
- In der Interaktion auch im Feld "Regexp-Ausschluss"".
- Im Menü Administration → Konfiguration → Interaktionen → Feld "Allgemeine Ausschluss-Regexp für Interaktionen"".

Für das Feld &quot;Allgemeiner Ausschluss-Regex für Interaktionen&quot; wird diese Regel auf alle Interaktionen angewendet, die später erstellt oder erneut gespeichert werden. Wenn wir es auf alle vorhandenen Interaktionen anwenden möchten, müssen wir die Interaktionen neu generieren. Im Allgemeinen wird es verwendet, um falsch gebildete Sätze zu löschen, die in den meisten generierten Interaktionen gefunden wurden.

Für das Feld &quot;Regexp-Ausschluss&quot; auf der Konfigurationsseite jeder Interaktion können Sie einen bestimmten Regexp einfügen, der nur für diese Interaktion gilt. Sie können daher für eine Interaktion genauer löschen. Es kann auch möglich sein, eine Interaktion für eine bestimmte Bestellung zu löschen, für die diese Möglichkeit im Rahmen einer Generierung mehrerer Bestellungen nicht angeboten werden soll.

Der folgende Screenshot zeigt die Interaktion ohne Regexp. In der Liste links filtere ich die Sätze, um nur die Sätze anzuzeigen, die gelöscht werden. In Wirklichkeit werden mit der Konfiguration der Interaktion 76 Sätze generiert.

![interact014](../images/interact014.png)

Wie Sie auf dem folgenden Screenshot sehen können, habe ich einen einfachen regulären Ausdruck hinzugefügt, der in den generierten Sätzen nach dem Wort &quot;Julie&quot; sucht und diese löscht. Wir können jedoch in der Liste auf der linken Seite sehen, dass es immer Sätze mit dem Wort &quot;julie&quot; gibt, in regulären Ausdrücken ist Julie nicht gleich julie, dies wird als Groß- / Kleinschreibung oder gut bezeichnet Französisch ein Großbuchstabe unterscheidet sich von einem Kleinbuchstaben. Wie wir im folgenden Screenshot sehen können, sind nur noch 71 Sätze übrig, die 5 mit einer &quot;Julie&quot; wurden gelöscht.

Ein regulärer Ausdruck setzt sich wie folgt zusammen :

- Erstens ein Trennzeichen, hier ein Schrägstrich &quot;/&quot; am Anfang und Ende des Ausdrucks.
- Der Punkt nach dem Schrägstrich steht für ein beliebiges Zeichen, Leerzeichen oder eine beliebige Zahl.
- Das &quot;\*&quot; gibt an, dass das Zeichen davor 0 oder mehr Mal sein kann, hier ein Punkt, also in gutem Französisch jedes Element.
- Dann Julie, nach dem gesucht werden muss (Wort oder anderes Ausdrucksmuster), gefolgt von einem Punkt und einem Schrägstrich.

Wenn wir diesen Ausdruck in einen Satz übersetzen, würde er "nach dem Wort Julie suchen, dem alles vorausgeht und dem alles folgt".

Es ist eine extrem einfache Version von regulären Ausdrücken, aber bereits sehr kompliziert zu verstehen. Ich habe eine Weile gebraucht, um zu verstehen, wie es funktioniert. Als etwas komplexeres Beispiel ein regulärer Ausdruck zum Überprüfen einer URL :

/\.^(https?:\\ / \\ /)?(\ [\\ da-z \\ .- \] +) \\. (\ [az \\. \] {2,6}) (\ [\\ / \\ w \\ .- \] \*)\.*\\ /?\ $ /

Sobald Sie dies schreiben können, verstehen Sie die regulären Ausdrücke.

![interact015](../images/interact015.png)

Um das Problem der Groß- und Kleinschreibung zu lösen, können wir unserem Ausdruck eine Option hinzufügen, die die Groß- und Kleinschreibung nicht berücksichtigt, oder mit anderen Worten, bei der ein Kleinbuchstabe einem Großbuchstaben entspricht. Dazu müssen wir am Ende unseres Ausdrucks einfach ein "i" hinzufügen".

![interact016](../images/interact016.png)

Mit der Option &quot;i&quot; sehen wir, dass nur noch 55 Sätze übrig sind, und in der Liste links mit dem Julie-Filter, um die Sätze zu finden, die dieses Wort enthalten, sehen wir, dass es einige gibt viel mehr.

Da dies ein äußerst komplexes Thema ist, werde ich hier nicht näher darauf eingehen. Es gibt genügend Tutorials im Internet, die Ihnen helfen, und vergessen Sie nicht, dass Google auch Ihr Freund ist, denn ja, es ist mein Thema Freund, er hat mir beigebracht, Regexp zu verstehen und sogar zu programmieren. Wenn er mir also geholfen hat, kann er Ihnen auch helfen, wenn Sie guten Willen hineinlegen.

Nützliche Links :

- <http://www.commentcamarche.net/contents/585-javascript-l-objet-regexp>
- <https://www.lucaswillems.com/fr/articles/25/tutoriel-pour-maitriser-les-expressions-regulieres>
- <https://openclassrooms.com/courses/concevez-votre-site-web-avec-php-et-mysql/les-expressions-regulieres-partie-1-2>

### Antwort bestehend aus mehreren Informationen

Es ist auch möglich, mehrere Info-Befehle in eine Antwort einzufügen, um beispielsweise eine Situationszusammenfassung zu erhalten.

![interact021](../images/interact021.png)

In diesem Beispiel sehen wir einen einfachen Satz, der eine Antwort mit 3 verschiedenen Temperaturen zurückgibt. Hier können wir also ein wenig setzen, was wir wollen, um eine Reihe von Informationen auf einmal zu haben.

### Ist jemand im Raum? ?

#### Basisversion

- Die Frage ist also: "Ist jemand im Raum?"
- Die Antwort lautet "Nein, es ist niemand im Raum" oder "Ja, es ist jemand im Raum""
- Der Befehl, der darauf reagiert, lautet "\#\.[Chambre de julie\.]\.[FGMS-001-2\.]\.[Présence\.]\.#"

![interact017](../images/interact017.png)

Dieses Beispiel zielt speziell auf bestimmte Geräte ab, die eine personalisierte Antwort ermöglichen. Wir könnten uns also vorstellen, die Antwort des Beispiels durch "Nein, es ist niemand im Raum" zu ersetzen *Julie*|Ja, da ist jemand im Raum *Julie*"

#### Evolution

- Die Frage ist also "\#commande\.# \ [in der |in der \] \#objet\.#"
- Die Antwort lautet "Nein, es ist niemand im Raum" oder "Ja, es ist jemand im Raum""
- Es gibt keinen Befehl, der darauf im Aktionsteil reagiert, da es sich um eine Interaktion mit mehreren Befehlen handelt
- Durch Hinzufügen eines regulären Ausdrucks können wir die Befehle bereinigen, die wir nicht sehen möchten, sodass wir nur die Sätze in den Befehlen "Präsenz" haben".

![interact018](../images/interact018.png)

Ohne den Regexp erhalten wir hier 11 Sätze, aber meine Interaktion zielt darauf ab, Sätze zu generieren, nur um zu fragen, ob sich jemand in einem Raum befindet, sodass ich keinen Lampenstatus oder ähnliches benötige Steckdosen, die mit Regexp-Filterung aufgelöst werden können. Um es noch flexibler zu machen, können Sie Synonyme hinzufügen. In diesem Fall sollten Sie jedoch nicht vergessen, den regulären Ausdruck zu ändern.

### Kennen Sie die Temperatur / Luftfeuchtigkeit / Helligkeit

#### Basisversion

Wir könnten den Satz hart schreiben, wie zum Beispiel &quot;Was ist die Temperatur des Wohnzimmers?&quot;, Aber es wäre notwendig, für jeden Sensor für Temperatur, Helligkeit und Feuchtigkeit einen zu erstellen. Mit dem Jeedom-Satzgenerierungssystem können wir daher Sätze für alle Sensoren dieser drei Messarten mit einer einzigen Interaktion erzeugen.

Hier ein allgemeines Beispiel, anhand dessen die Temperatur, Luftfeuchtigkeit und Helligkeit der verschiedenen Räume (Objekt im Sinne von Jeedom) ermittelt werden).

![interact019](../images/interact019.png)

- So können wir sehen, dass ein allgemeiner Satz wie &quot;Was ist die Temperatur im Wohnzimmer&quot; oder &quot;Was ist die Helligkeit des Schlafzimmers&quot; in umgewandelt werden kann : "was ist \ [the |l \\ '\] \#commande\.# Objekt "(die Verwendung von \ [word1 | Mit word2 \] können Sie diese oder jene Möglichkeit sagen, um alle möglichen Varianten des Satzes mit word1 oder word2 zu generieren). Beim Generieren generiert Jeedom alle möglichen Kombinationen von Sätzen mit allen vorhandenen Befehlen (abhängig von den Filtern), indem \ ersetzt wird#commande\.# mit dem Namen des Befehls und \#objet\.# durch den Namen des Objekts.
- Die Antwort lautet "21 ° C" oder "200 Lux"". Einfach gesagt : \.#valeur\.# \.#unite\.# (Die Einheit ist in der Konfiguration jedes Befehls zu vervollständigen, für den wir einen haben möchten)
- In diesem Beispiel wird daher ein Satz für alle digitalen Infotypbefehle generiert, die eine Einheit haben, sodass wir Einheiten im rechten Filter deaktivieren können, die auf den Typ beschränkt sind, der uns interessiert.

#### Evolution

Wir können daher dem Befehlsnamen Synonyme hinzufügen, um etwas Natürlicheres zu erhalten. Fügen Sie einen regulären Ausdruck hinzu, um die Befehle zu filtern, die nichts mit unserer Interaktion zu tun haben.

Wenn wir ein Synonym hinzufügen, sagen wir zu Jeedom, dass ein Befehl mit dem Namen &quot;X&quot; auch als &quot;Y&quot; bezeichnet werden kann. Wenn wir also in unserem Satz &quot;y einschalten&quot; haben, weiß Jeedom, dass es x einschaltet. Diese Methode ist sehr praktisch, um Befehlsnamen umzubenennen, die, wenn sie auf dem Bildschirm angezeigt werden, auf unnatürliche Weise, stimmlich oder in einem geschriebenen Satz wie "EIN" geschrieben werden". Ein so geschriebener Button ist völlig logisch, aber nicht im Kontext eines Satzes.

Wir können auch einen Regexp-Filter hinzufügen, um einige Befehle zu entfernen. Anhand des einfachen Beispiels sehen wir Sätze &quot;Batterie&quot; oder sogar &quot;Latenz&quot;, die nichts mit unserer Interaktionstemperatur / Luftfeuchtigkeit / Helligkeit zu tun haben.

![interact020](../images/interact020.png)

Wir können also einen regulären Ausdruck sehen :

**(batterie|latence|pression|vitesse|consommation)**

Auf diese Weise können Sie alle Befehle löschen, deren Satz eines dieser Wörter enthält

> **Notiz**
>
> Der reguläre Ausdruck hier ist eine vereinfachte Version zur einfachen Verwendung. Wir können daher entweder traditionelle Ausdrücke oder vereinfachte Ausdrücke wie in diesem Beispiel verwenden.

### Steuern Sie einen Dimmer oder einen Thermostat (Schieberegler))

#### Basisversion

Es ist möglich, eine Lampe als Prozentsatz (Dimmer) oder einen Thermostat mit den Wechselwirkungen zu steuern. Hier ist ein Beispiel, um den Dimmer einer Lampe mit Wechselwirkungen zu steuern :

![interact022](../images/interact022.png)

Wie wir sehen können, gibt es hier in der Anfrage das Tag **\.#consigne\.#** (Sie können eingeben, was Sie möchten), was in der Antriebssteuerung enthalten ist, um den gewünschten Wert anzuwenden. Dazu haben wir 3 Teile : \.* Anwendung : in dem wir ein Tag erstellen, das den Wert darstellt, der an die Interaktion gesendet wird. \* Antwort : Wir verwenden das Tag für die Antwort erneut, um sicherzustellen, dass Jeedom die Anfrage richtig verstanden hat. \* Aktion : Wir setzen eine Aktion auf die Lampe, die wir fahren möchten, und geben in dem Wert, den wir geben, unser Etikett an *Anleitung*.

> **Notiz**
>
> Wir können jedes Tag verwenden, außer denjenigen, die bereits von Jeedom verwendet werden. Es können mehrere verwendet werden, um beispielsweise mehrere Befehle zu steuern. Beachten Sie auch, dass alle Tags an die von der Interaktion gestarteten Szenarien übergeben werden (es ist jedoch erforderlich, dass sich das Szenario unter "Im Vordergrund ausführen" befindet").

#### Evolution

Möglicherweise möchten wir alle Cursortypbefehle mit einer einzigen Interaktion steuern. Mit dem folgenden Beispiel können wir daher mehrere Laufwerke mit einer einzigen Interaktion steuern und daher eine Reihe von Sätzen generieren, um sie zu steuern.

![interact033](../images/interact033.png)

In dieser Interaktion haben wir keinen Befehl im Aktionsteil, wir lassen Jeedom aus Tags die Liste der Sätze generieren. Wir können das Tag sehen **\.#slider\.#**. Es ist unbedingt erforderlich, dieses Tag für Anweisungen in einem Mehrfachinteraktionsbefehl zu verwenden. Es ist möglicherweise nicht das letzte Wort des Satzes. In dem Beispiel sehen wir auch, dass wir in der Antwort ein Tag verwenden können, das nicht Teil der Anforderung ist. Die meisten in den Szenarien verfügbaren Tags sind auch in den Interaktionen verfügbar und können daher in einer Antwort verwendet werden.

Ergebnis der Interaktion :

![interact034](../images/interact034.png)

Wir können sehen, dass das Tag **\.#equipement\.#** Was in der Anfrage nicht verwendet wird, ist in der Antwort gut abgeschlossen.

### Steuern Sie die Farbe eines LED-Streifens

Es ist möglich, einen Farbbefehl durch die Interaktionen zu steuern, indem Jeedom beispielsweise aufgefordert wird, einen blauen LED-Streifen zu beleuchten. Dies ist die Interaktion zu tun :

![interact023](../images/interact023.png)

Bisher nichts Kompliziertes, Sie müssen jedoch die Farben in Jeedom konfiguriert haben, damit es funktioniert. Gehen Sie zum Menü → Konfiguration (oben rechts) und dann im Abschnitt "Konfiguration von Interaktionen"" :

![interact024](../images/interact024.png)

Wie wir auf dem Screenshot sehen können, ist keine Farbe konfiguriert, daher müssen Sie Farben mit dem &quot;+&quot; rechts hinzufügen. Der Name der Farbe ist der Name, den Sie an die Interaktion übergeben. Im rechten Teil (Spalte &quot;HTML-Code&quot;) können Sie durch Klicken auf die schwarze Farbe eine neue Farbe auswählen.

![interact025](../images/interact025.png)

Wir können so viele hinzufügen, wie wir wollen, wir können jeden Namen wie jeden setzen, so dass wir uns vorstellen können, dem Namen jedes Familienmitglieds eine Farbe zuzuweisen.

Nach der Konfiguration sagen Sie &quot;Beleuchten Sie den Baum in Grün&quot;. Jeedom sucht in der Anfrage nach einer Farbe und wendet sie auf die Bestellung an.
### Verwendung in Verbindung mit einem Szenario

#### Basisversion

Es ist möglich, eine Interaktion mit einem Szenario zu koppeln, um etwas komplexere Aktionen auszuführen als die Ausführung einer einfachen Aktion oder eine Informationsanforderung.

![interact026](../images/interact026.png)

Dieses Beispiel ermöglicht es daher, das Szenario zu starten, das im Aktionsteil verknüpft ist. Wir können natürlich mehrere haben.

### Programmieren einer Aktion mit Interaktionen

Interaktionen machen insbesondere viele Dinge. Sie können eine Aktion dynamisch programmieren. Beispiel : "Schaltet die Heizung um 22 Uhr für 14.50 Uhr ein". Nichts könnte einfacher sein, verwenden Sie einfach die Tags \#time\.# (wenn eine bestimmte Zeit definiert ist) oder \#duration\.# (für in X-Zeit, Beispiel in 1 Stunde) :

![interact23](../images/interact23.JPG)

> **Notiz**
>
> Sie werden in der Antwort das Tag \ bemerken#value\.# Dies enthält im Falle einer programmierten Interaktion die effektive Programmierzeit
