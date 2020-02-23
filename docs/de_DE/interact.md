Das Interaktionssystem in Jeedom ermöglicht es. Aktionen auszuführen
von Text- ovonr Sprachbefehlen.

Diese Bestellungen erhalten Sie von :

-   SMS : Senvonn Sie eine SMS. um Befehle (Aktion) zu starten. ovonr fragen Sie a
    Frage (Info).

-   Katze : Telegramm. Slack usw..

-   Vokal : diktieren Sie eine Phrase mit Siri. Google Now. SARAH usw. für
    Befehle starten (Aktion) ovonr eine Frage stellen (Info).

-   HTTP : Starten Sie eine HTTP-URL. die vonn Text enthält (z. Tasker. Slack)
    Befehle starten (Aktion) ovonr eine Frage stellen (Info).

Das Interesse an Interaktionen liegt in vonr vereinfachten Integration in
anvonre Systeme wie Smartphone. Tablund. anvonre Hausautomationsbox usw..

Um auf die Interaktionsseite zuzugreifen. gehen Sie zu Extras →
Wechselwirkungen :

Am oberen Rand vonr Seite befinvonn sich 3 Schaltflächen :

-   **hinzufügen** : Dadurch können neue Interaktionen erstellt wervonn.

-   **Regenerat** : Dadurch wervonn alle Interaktionen wievonrhergestellt (möglicherweise
    très long &gt; 5mn).

-   **Test** : das öffnund einen Dialog zum Schreiben und
    teste einen Satz.

> **Spitze**
>
> Wenn Sie eine Interaktion haben. die die Sätze für die Lichter generiert
> Zum Beispiel und Sie fügen ein neues Befehlsmodul von hinzu
> Licht. müssen Sie entwevonr alle Interaktionen regenerieren. ovonr
> Gehen Sie zu vonr bundreffenvonn Interaktion und speichern Sie sie erneut für
> Erstellen Sie die Sätze für dieses neue Modul.

Prinzip 
========

Das Prinzip vonr Schöpfung ist recht einfach : Wir wervonn einen Satz vonfinieren
Generatormovonll. mit vonm Jeedom eines ovonr mehrere erstellen kann
Hunvonrte anvonrer Sätze. die mögliche Kombinationen vonr sein wervonn
Movonll.

Wir wervonn Antworten auf die gleiche Weise mit einem Movonll vonfinieren (dies ermöglicht
Jeedom. um mehrere Antworten auf eine einzelne Frage zu haben).

Wir können auch einen Befehl vonfinieren. vonr ausgeführt wervonn soll. wenn zum Beispiel
Die Interaktion ist nicht an eine Handlung gebunvonn. sonvonrn an Informationen ovonr wenn wir
möchte danach eine bestimmte Aktion ausführen (es ist auch
möglich ein Szenario auszuführen. mehrere Befehle zu steuern…).

Konfiguration 
=============

Die Konfigurationsseite besteht aus mehreren Registerkarten und
Tasten :

-   **Sätze** : Zeigt die Anzahl vonr Sätze in vonr Interaktion an (ein Klick
    oben zeigt sie dir)

-   **Rekord** : zeichnund die aktuelle Interaktion auf

-   **Entfernen** : aktuelle Interaktion löschen

-   **Duplikat** : Dupliziere die aktuelle Interaktion

General 
=======

-   **Name** : Name vonr Interaktion (kann leer sein. vonr Name ersundzt vonn
    Text in vonr Interaktionsliste anforvonrn).

-   **Gruppe** : Interaktionsgruppe. es hilft. sie zu organisieren
    (kann leer sein. wird daher in vonr Gruppe &quot;keine&quot; sein).

-   **Aktiva** : ermöglicht das Aktivieren ovonr Deaktivieren vonr Interaktion.

-   **Anwendung** : vonr generierenvon Movonllsatz (erforvonrlich).

-   **synonym** : ermöglicht das Definieren von synonymn für Namen
    Bestellungen.

-   **Antwort** : die Antwort zu geben.

-   **Warten Sie. bevor Sie antworten.** : Fügen Sie eine Verzögerung von X Sekunvonn hinzu. bevor Sie die Antwort generieren. So können Sie beispielsweise auf die Rückkehr eines Lampenstatus warten. bevor Sie beantwortund wervonn.

-   **Binäre Konvertierung** : konvertiert Binärwerte in
    Zum Beispiel öffnen / schließen (nur für Typbefehle
    binäre Info).

-   **Autorisierte Benutzer** : begrenzt die Interaktion auf bestimmte
    Benutzer (Anmeldungen gundrennt durch |).

Filter 
=======

-   **Beschränken Sie sich auf die Eingabe von Befehlen** : erlaubt nur die zu verwenvonn
    Arten von Aktionen. Informationen ovonr beivon Arten.

-   **Beschränken Sie sich auf Bestellungen mit Subtyp** : erlaubt zu begrenzen
    Erzeugung zu einem ovonr mehreren Untertypen.

-   **Beschränkung auf Bestellungen mit Einheit** : ermöglicht die Begrenzung vonr
    Generation mit einer ovonr mehreren Einheiten (Jeedom erstellt die Liste
    automatisch aus vonn in Ihren Bestellungen vonfinierten Einheiten).

-   **Beschränkung auf Bestellungen. die zum Objekt gehören** : erlaubt zu begrenzen
    Generierung zu einem ovonr mehreren Objekten (Jeedom erstellt die Liste
    automatisch aus vonn von Ihnen erstellten Objekten).

-   **Beschränken Sie sich auf das Plugin** : begrenzt die Erzeugung auf einen ovonr mehrere
    mehrere Plugins (Jeedom erstellt automatisch die Liste aus
    Plugins installiert).

-   **Beschränkung auf Kategorie** : begrenzt die Erzeugung auf eins
    ovonr mehr Kategorien.

-   **Beschränkung auf Ausrüstung** : ermöglicht die Begrenzung vonr génération à un
    seul équipement/module (Jeedom crée la liste automatiquement à
    partir vons équipements/modules que vous avez).

Aktion 
======

A utiliser si vous voulez cibler une ou Mehrfachbestellungen spécifiques
ou passer vons paramètres particuliers.

Beispiels 
========

> **Notiz**
>
> Les captures d'écran peuvent être différentes au vue vons évolutions.

Interaction simple 
------------------

La façon la plus simple von configurer une interaction. c'est von lui
donner un Movonll générateur rigivon. sans variation possible. Cundte
méthovon ciblera très précisément une commanvon ou un scénario.

Dans l'exemple qui suit. on peut voir dans le champ "Anwendung" la phrase
exacte à fournir pour déclencher l'interaction. Ici. pour allumer le
plafonnier du salon.

![interact004](../images/interact004.png)

Wir können voir. sur cundte capture. la configuration pour avoir une
interaction liée à une action spécifique. Cundte action est définie dans
la partie "Aktion" von la page.

Wir können très bien imaginer faire von même avec plusieurs actions pour
allumer plusieurs lampes dans le salon comme l'exemple qui suit :

![interact005](../images/interact005.png)

Dans les 2 exemples ci-vonssus. la phrase Movonll est ivonntique mais les
actions qui en découlent changent en fonction von ce qui est configuré
dans la partie "Aktion". on peut donc déjà avec une interaction simple à
phrase unique imaginer vons actions combinées entre diverses commanvons und
divers scénarios (wir peut aussi déclencher vons scénarios dans la partie
action vons interactions).

> **Spitze**
>
> Pour ajouter un scénario. créer une nouvelle action. écrire "scenario"
> sans accent. appuyer sur la touche tabulation von votre clavier pour
> faire apparaitre le sélecteur von scénario.

Interaction multiple commanvons 
------------------------------

Nous allons ici voir tout l'intérêt und toute la puissance vons
interactions. avec une phrase Movonll nous allons pouvoir générer vons
phrases pour tout un groupe von commanvons.

On va reprendre ce qui a été fait plus haut. supprimer les actions que
l'on avait ajoutées. und à la place von la phrase fixe. dans "Anwendung".
nous allons utiliser les tags **\#commanvon\#** und **\#equipement\#**.
Jeedom va donc remplacer ces tags par le nom Bestellungen und le nom von
l'équipement (wir peut voir l'importance d'avoir vons noms von
commanvon/équipement cohérents).

![interact006](../images/interact006.png)

Wir können donc constater ici que Jeedom a généré 152 phrases à partir von
notre Movonll. Toutefois. elles ne sont pas très bien construites und l'on
a un peu von tout.

Pour faire von l'ordre dans tout cela. on va utiliser les filtres (partie
droite von notre page von configuration). Dans cund exemple. on veut
générer vons phrases pour allumer vons lumières. Wir können donc décocher le
type von commanvon info (si je sauve. il ne me reste plus que 95 phrases
générées). puis. dans les sous-types. on peut ne garvonr coché que
"défaut" qui correspond au bouton d'action (ne reste donc plus que 16
phrases).

![interact007](../images/interact007.png)

C'est mieux. mais on peut faire encore plus naturel. Si je prends
l'exemple généré "On entrée". il serait bien von pouvoir transformer
cundte phrase en "allume l'entrée" ou en "allumer l'entrée". Pour faire
cela. Jeedom dispose. sous le champ vonmanvon. d'un champ synonyme qui va
nous permundtre von nommer différemment le nom Bestellungen dans nos
phrases "générées". ici c'est "on". j'ai même vons "on2" dans les modules
qui peuvent contrôler 2 sorties.

Dans les synonymes. on va donc indiquer le nom von la commanvon und le(s)
synonyme(s) à utiliser :

![interact008](../images/interact008.png)

Wir können voir ici une syntaxe un peu nouvelle pour les synonymes. Un nom
von commanvon peut avoir plusieurs synonymes. ici "on" a comme synonyme
"allume" und "allumer". La syntaxe est donc "*nom von la commanvon*"
***=*** "*synonyme 1*"***.*** "*synonyme 2*" (wir peut mundtre autant von
synonyme que l'on veut). Puis. pour ajouter vons synonymes pour un autre
nom von commanvon. il suffit d'ajouter après le vonrnier synonyme une barre
verticale "*|*" à la suite von laquel vous pouvez à nouveau nommer la
commanvon qui va avoir vons synonymes comme pour la première partie. undc.

C'est déjà mieux. mais il manque encore pour la commanvon "on" "entrée"
le "l' " und pour d'autres le "la" ou "le" ou "un". undc. On pourrait
modifier le nom von l'équipement pour l'ajouter. ce serait une solution.
sinon on peut utiliser les variations dans la vonmanvon. Cela consiste à
lister une série von mots possibles à un emplacement von la phrase. Jeedom
va donc générer vons phrases avec ces variations.

![interact009](../images/interact009.png)

On a maintenant vons phrases un peu plus correctes avec vons phrases qui
ne sont pas justes. pour notre exemple "on" "entrée". on trouve donc
"Allume entrée". "Allume un entrée". "Allume une entrée". "Allume
l'entrée" undc. On a donc toutes les variantes possibles avec ce que l'on
a ajouté entre les "\[ \]" und ceci pour chaque synonyme. ce qui génère
rapivonment beaucoup von phrases (ici 168).

Afin d'affiner und von ne pas avoir vons choses improbables telles que
"allume l'télé". on peut autoriser Jeedom à supprimer les vonmanvons
syntaxiquement incorrectes. Il va donc supprimer ce qui est trop éloigné
von la syntaxe réelle d'une phrase. Dans notre cas. on passe von 168
130 Satzsätze.

![interact010](../images/interact010.png)

Es wird daher wichtig. dass Sie Ihre Movonllsätze gut und gut aufbauen
synonym sowie die Auswahl vonr richtigen Filter. um nicht zu generieren
zu viele unnötige Sätze. Persönlich finvon ich es interessant zu haben
Einige Inkonsistenzen vons Stils &quot;ein Eintrag&quot;. vonnn wenn Sie zu Hause sind. haben Sie
eine ausländische Person. die nicht richtig Französisch spricht.
Interaktionen wervonn weiterhin funktionieren.

Passen Sie die Antworten an 
--------------------------

Bisher hatten wir als Antwort auf eine Interaktion eine einfache
Satz. vonr nicht viel sagte. außer dass undwas passiert ist
Vergangenheit. Die Ivone wäre. dass Jeedom uns erzählt. was er ein bisschen mehr gundan hat
genau. Hier kommt das Antwortfeld ins Spiel.
Möglichkeit. die Rücksendung basierend auf vonr ausgeführten Bestellung anzupassen.

Dazu verwenvonn wir wievonr das Jeedom Tag. Für unsere
Lichter können wir einen Satz wie verwenvonn : Ich habe gut beleuchtund
\ #Ausrüstung \ # (siehe Screenshot unten).

![interact011](../images/interact011.png)

Sie können auch einen beliebigen Wert aus einem anvonren Befehl wie hinzufügen
Temperatur. Anzahl vonr Personen usw..

![interact012](../images/interact012.png)

Binäre Konvertierung 
------------------

Binäre Konvertierungen gelten für Bestellungen vom Typ Info. vonren
Subtyp ist binär (gibt nur 0 ovonr 1 zurück). Also musst du aktivieren
die richtigen Filter. wie Sie im folgenvonn Screenshot sehen können
(Für die Kategorien können wir alle überprüfen. für das Beispiel. das ich habe
hielt das Licht).

![interact013](../images/interact013.png)

Wie Sie hier sehen können. habe ich fast die gleiche Struktur beibehalten
für die Nachfrage (es ist freiwillig. sich darauf zu konzentrieren
Besonvonrheiten). Natürlich habe ich die synonym angepasst. um einige zu haben
kohärente Sache. Für die Antwort ist es jedoch **Imperativ** von
Sundzen Sie nur \ #value \ #. was die 0 ovonr 1 darstellt. zu vonr Jeedom gehen wird
durch folgenvon binäre Konvertierung ersundzen.

Das Feld **binäre Konvertierung** muss 2 Antworten enthalten : zuerst die
Antwort Wenn vonr Wert vons Befehls 0 ist. wird ein vertikaler Balken &quot;|&quot;
Trennung und schließlich die Antwort. wenn vonr Befehl 1 wert ist. Hier die
Antworten sind einfach nein und ja. aber wir könnten einen Satz sundzen
undwas länger.

> **Warnung**
>
> Tags funktionieren nicht in binären Konvertierungen.

Autorisierte Benutzer 
----------------------

Im Feld &quot;Autorisierte Benutzer&quot; können Sie nur bestimmte autorisieren
Personen. um vonn Befehl auszuführen. können Sie mehrere Profile sundzen
Trennen Sie sie mit einem &quot;|&quot;.

Beispiel : person.1 | person2

Man kann sich vorstellen. dass ein Alarm durch a aktiviert ovonr vonaktiviert wervonn kann
Kind ovonr ein Nachbar. vonr in Ihrer Abwesenheit kommen würvon. um die Pflanzen zu gießen.

Regexp-Ausschluss 
------------------

Es ist möglich zu erstellen
[Regexp](https://fr.wikipedia.org/wiki/Expression_rationnelle)
Ausschluss. wenn ein generierter Satz diesem Regexp entspricht. wird es sein
gelöscht. Es geht darum. Fehlalarme entfernen zu können.
einen von Jeedom erzeugten Satz zu sagen. vonr undwas aktiviert. was nicht
entspricht nicht vonm. was wir wollen ovonr was einen anvonren stören würvon
Interaktion. die einen ähnlichen Satz haben würvon.

Wir haben 2 Stellen. an vonnen Sie einen Regexp anwenvonn können :

-   in vonr Interaktion auch im Feld &quot;Regexp-Ausschluss&quot;.

-   Im Menü Administration → Konfiguration → Interaktionen → Feld &quot;Regexp&quot;
    allgemeiner Ausschluss für Interaktionen &quot;.

Für das Feld &quot;Allgemeiner Ausschluss-Regex für Interaktionen&quot; ist dies
Regel wird auf alle Interaktionen angewendund. die erstellt wervonn ovonr
danach wievonr gespeichert. Wenn wir es auf alle anwenvonn wollen
Bestehenvon Wechselwirkungen. Wechselwirkungen müssen regeneriert wervonn.
Normalerweise wird es verwendund. um Sätze falsch zu löschen
in vonn meisten erzeugten Wechselwirkungen gefunvonn.

Für das Feld &quot;Regexp-Ausschluss&quot; auf vonr Konfigurationsseite von
Bei jevonr Interaktion können wir einen bestimmten Regexp einfügen. vonr funktioniert
nur auf diese Interaktion. So können Sie löschen
genauer für eine Interaktion. Es kann auch erlauben
Löschen Sie eine Interaktion für einen bestimmten Befehl. für vonn
will diese Gelegenheit nicht als Teil einer Generation von anbiunvonn
Mehrfachbestellungen.

Der folgenvon Screenshot zeigt die Interaktion ohne Regexp. In vonr
linke Liste. ich filtere die Sätze. um Ihnen nur die zu zeigen
Sätze. die gelöscht wervonn sollen. In Wirklichkeit wervonn 76 Sätze erzeugt
mit vonr Konfiguration vonr Interaktion.

![interact014](../images/interact014.png)

Wie Sie auf vonm folgenvonn Screenshot sehen können. habe ich eine hinzugefügt
regexp simple. das in vonn generierten Sätzen nach vonm Wort &quot;Julie&quot; sucht
und löschen Sie sie. Wir können jedoch in vonr Liste links sehen. dass dort
hat immer Sätze mit vonm Wort &quot;julie&quot; in Ausdrücken
regelmäßig ist Julie nicht gleich Julie. dies nennt man a
Groß- und Kleinschreibung ovonr in gutem Französisch ist ein Großbuchstabe anvonrs
von einem winzigen. Wie wir im folgenvonn Screenshot sehen können. ist dies nicht vonr Fall
Nur noch 71 Sätze. die 5 mit einer &quot;Julie&quot; wurvonn gelöscht.

Ein regulärer Ausdruck sundzt sich wie folgt zusammen :

-   Zuerst ein Trennzeichen. hier ist ein Schrägstrich &quot;/&quot; eingefügt
    Beginn und Envon vons Ausdrucks.

-   Der Punkt nach vonm Schrägstrich steht für einen beliebigen
    Zeichen. Leerzeichen ovonr Zahl.

-   Das &quot;\ *&quot; zeigt an. dass es 0 ovonr mehr Male geben kann
    vonr Charakter. vonr davor steht. hier ein Punkt. also in gutem Französisch
    ein beliebiger Artikel.

-   Dann Julie. nach vonm gesucht wervonn muss (Wort ovonr anvonres Diagramm)
    Ausdruck). gefolgt von einem Punkt und einem Schrägstrich.

Wenn wir diesen Ausdruck in einen Satz übersundzen. würvon dies &quot;suche das&quot; ergeben
Wort Julie. vonm alles vorausgeht und vonm alles folgt
was &quot;.

Es ist aber eine extrem einfache Version von regulären Ausdrücken
schon sehr kompliziert zu verstehen. Ich brauchte eine Weile. um es zu begreifen
die Operation. Als undwas komplexeres Beispiel ein regulärer Ausdruck für
Überprüfen Sie eine URL :

/ \ ^ (HTTPS?:\\ / \\ /)?(\ [\\ da-z \\ .- \] +) \\. (\ [Az \\. \] {2.6}) (\ [\\ / w \\
\\ .- \] \ *) \ \\ * /?\ $ /

Sobald Sie dies schreiben können. verstehen Sie die Ausdrücke
regelmäßig.

![interact015](../images/interact015.png)

Um das Problem vonr Groß- und Kleinschreibung zu lösen. können wir hinzufügen
unser Ausdruck eine Option. die es unabhängig von Groß- und Kleinschreibung macht. ovonr
mit anvonren Worten. bei vonnen ein Kleinbuchstabe einem Großbuchstaben entspricht;
Dazu müssen wir einfach am Envon unseres Ausdrucks a hinzufügen
&quot;I&quot;.

![interact016](../images/interact016.png)

Mit vonr Option &quot;i&quot; sehen wir. dass nur noch 55 übrig sind
generierte Sätze und in vonr Liste links mit vonm Julie-Filter für
Suchen Sie nach vonn Sätzen. die dieses Wort enthalten. Wir sehen. dass es einige gibt
viel mehr.

Da dies ein äußerst komplexes Thema ist. wervon ich nicht weiter gehen
Dundail hier. es gibt genug Tutorials im Internund. um Ihnen zu helfen. und
Vergiss nicht. dass Google auch vonin Freund ist. vonnn ja. er ist mein Freund.
Er hat mir beigebracht. Regexp zu verstehen und sogar zu programmieren. vonshalb
Wenn er mir geholfen hat. kann er Ihnen auch helfen. wenn Sie Gutes tun
wervonn.

Nützliche Links :

-   <http://www.commentcamarche.nund/contents/585-javascript-l-objund-regexp>

-   <https://www.lucaswillems.com/fr/articles/25/tutoriel-pour-maitriser-les-expressions-regulieres>

-   <https://openclassrooms.com/courses/concevez-votre-site-web-avec-php-und-mysql/les-expressions-regulieres-partie-1-2>

Antwort bestehend aus mehreren Informationen 
------------------------------------------

Es ist auch möglich. mehrere Info-Befehle in einen zu sundzen
Antworten Sie beispielsweise. um eine Situationszusammenfassung zu erhalten.

![interact021](../images/interact021.png)

In diesem Beispiel sehen wir einen einfachen Satz. vonr a zurückgibt
Antworte mit 3 verschievonnen Temperaturen. also können wir hier ein wenig sundzen
alles. was Sie wollen. um eine Reihe von Informationen in einem zu haben
einmal.

Ist jemand im Raum? ? 
------------------------------------

### Basisversion 

-   Die Frage ist also: &quot;Ist jemand im Raum?&quot;

-   Die Antwort lautund &quot;Nein. es ist niemand im Raum&quot; ovonr &quot;Ja. es ist da&quot;
    hat jemanvonn im Raum &quot;

-   Der Befehl. vonr darauf reagiert. lautund &quot;\ # \ [Chamber of
    julie \] \ [GSMF-001-2 \] \ [Presence \] \ # &quot;

![interact017](../images/interact017.png)

Dieses Beispiel zielt speziell auf bestimmte Geräte ab. die dies zulassen
eine personalisierte Antwort zu haben. Wir könnten uns also vorstellen. zu ersundzen
Die Beispielantwort lautund &quot;Nein. es ist niemand im Raum
*julie * | ja da ist jemand in * julies * zimmer &quot;

### Evolution 

-   Die Frage lautund daher &quot;\ #orvonr \ # \ [im | im \] \ #Objekt \ #&quot;.

-   Die Antwort lautund &quot;Nein. es ist niemand im Raum&quot; ovonr &quot;Ja. es ist da&quot;
    jemand im Raum &quot;

-   Es gibt keinen Befehl. vonr dies im Aktionsteil beantwortund
    dass dies eine Interaktion mit mehreren Befehlen ist

-   Durch Hinzufügen eines regulären Ausdrucks können wir die Befehle bereinigen
    dass wir nicht sehen wollen. nur die Sätze auf vonm zu haben
    &quot;Präsenz&quot; -Befehle.

![interact018](../images/interact018.png)

Ohne vonn Regexp bekommen wir hier 11 Sätze. aber meine Interaktion ist zielgerichtund
generiere Sätze nur. um zu fragen. ob jemand drin ist
ein Raum. also brauche ich keinen Lampenzustand ovonr ähnliches
genommen. die mit Regexp-Filterung aufgelöst wervonn kann. Zu machen
Noch flexibler können synonym hinzugefügt wervonn. in diesem Fall jedoch
Vergessen Sie nicht. vonn regulären Ausdruck zu änvonrn.

Kennen Sie die Temperatur / Luftfeuchtigkeit / Helligkeit 
--------------------------------------------

### Basisversion 

Wir könnten vonn Satz hart schreiben. wie zum Beispiel &quot;Was ist das?&quot;
Wohnzimmertemperatur &quot;. aber eine sollte für jevonn Sensor durchgeführt wervonn
von Temperatur. Helligkeit und Luftfeuchtigkeit. Mit vonm Erzeugungssystem von
Jeedom Satz. so können wir mit einer einzigen Interaktion erzeugen
Sätze für alle Sensoren dieser 3 Messarten.

Hier ein allgemeines Beispiel. das verwendund wird. um die Temperatur zu kennen.
die Luftfeuchtigkeit. die Helligkeit vonr verschievonnen Räume (Objekt im Sinne von Jeedom).

![interact019](../images/interact019.png)

-   So können wir sehen. dass ein generischer generischer Satz &quot;Was ist das?&quot;
    Wohnzimmertemperatur &quot;ovonr&quot; Wie hell ist das Schlafzimmer &quot;
    kann konvertiert wervonn zu : &quot;Was ist \ [das | l \\ &#39;\] \ # Befehl \ # Objekt&quot;
    (Die Verwendung von \ [word1 | word2 \] ermöglicht es. diese Möglichkeit auszusprechen
    ovonr diese. um alle möglichen Variationen vons Satzes zu erzeugen
    mit Wort1 ovonr Wort2). Bei vonr Erzeugung wird Jeedom alles erzeugen
    mögliche Kombinationen von Sätzen mit allen Befehlen
    vorhanvonn (abhängig von vonn Filtern) durch Ersundzen von \ #orvonr \ # durch
    vonn Namen vons Befehls und \ #Objekt \ # durch vonn Namen vons Objekts.

-   Die Antwort lautund &quot;21 ° C&quot; ovonr &quot;200 Lux&quot;.. Einfach gesagt :
    \ #value \ # \ #unite \ # (das Gerät muss in vonr Konfiguration abgeschlossen sein
    von jevonr Bestellung. für die wir eine haben wollen)

-   Dieses Beispiel generiert daher einen Satz für alle Befehle von
    Geben Sie digitale Informationen mit einer Einheit ein. damit wir das Kontrollkästchen vonaktivieren können
    Einheiten im richtigen Filter sind auf vonn Typ beschränkt. vonr uns interessiert.

### Evolution 

Wir können daher vonm Befehlsnamen synonym hinzufügen. um einige zu haben
natürlicher. fügen Sie einen regulären Ausdruck hinzu. um die Befehle zu filtern. die
haben nichts mit unserer Interaktion zu tun.

Wenn Sie ein Synonym hinzufügen. können Sie Jeedom mitteilen. dass ein Befehl aufgerufen wurvon
&quot;X&quot; kann auch als &quot;Y&quot; bezeichnund wervonn und daher in unserem Satz. wenn wir &quot;einschalten&quot; haben
y &quot;. Jeedom weiß. dass es x einschaltund. Diese Mundhovon ist sehr praktisch
um Befehlsnamen umzubenennen. die bei Anzeige unter
Der Bildschirm ist auf eine Weise geschrieben. die wevonr stimmlich noch natürlich ist
in einem Satz wie &quot;ON&quot; geschrieben. Ein so geschriebener Button ist
völlig logisch. aber nicht im Kontext eines Satzes.

Wir können auch einen Regexp-Filter hinzufügen. um einige Befehle zu entfernen.
Anhand vons einfachen Beispiels sehen wir Sätze &quot;Batterie&quot; ovonr
&quot;Latenz&quot;. die nichts mit unserer Interaktion zu tun haben
Temperatur / Feuchte / Helligkeit.

![interact020](../images/interact020.png)

Wir können also einen regulären Ausdruck sehen :

**(Batterie | Latenz | Druck | Geschwindigkeit | Verbrauch)**

Auf diese Weise können Sie alle Bestellungen löschen. für die eine dieser Bestellungen vorliegt
Wörter in ihrem Satz

> **Notiz**
>
> Der reguläre Ausdruck hier ist eine vereinfachte Version zur einfachen Verwendung.
> Wir können also entwevonr traditionelle Ausdrücke verwenvonn ovonr
> Verwenvonn Sie die vereinfachten Ausdrücke wie in diesem Beispiel.

Steuern Sie einen Dimmer ovonr einen Thermostat (Schieberegler) 
-------------------------------------------

### Basisversion 

Es ist möglich. eine prozentuale Lampe (Dimmer) ovonr a zu steuern
Thermostat mit Wechselwirkungen. Hier ist ein Beispiel zur Steuerung seiner
Dimmer auf einer Lampe mit Wechselwirkungen :

![interact022](../images/interact022.png)

Wie wir sehen können. gibt es hier in vonr Anfrage das Tag **\ #Consigne \#** (wir
kann sundzen was du willst) was in vonr Reihenfolge vonr enthalten ist
Dimmer. um vonn gewünschten Wert anzuwenvonn. Dazu haben wir 3 Teile
: \ * Anfrage : in vonm wir ein Tag erstellen. das vonn Wert darstellt
welches an die Interaktion gesendund wird. \ * Antwort : Wir verwenvonn das Tag für
die Antwort. um sicherzustellen. dass Jeedom die Anfrage richtig verstanvonn hat.
\ * Aktion : Wir sundzen eine Aktion auf die Lampe. die wir fahren und einfahren möchten
vonn Wert. vonn wir unserer Tag * Einzahlung übergeben*.

> **Notiz**
>
> Sie können jevons Tag verwenvonn. außer vonnjenigen. die bereits von verwendund wervonn
> Jeedom. es können zum Beispiel mehrere fahren
> Mehrfachbestellungen. Beachten Sie auch. dass alle Tags an übergeben wervonn
> Szenarien. die durch die Interaktion gestartund wurvonn (jedoch das Szenario
> entwevonr in &quot;Im Vorvonrgrund ausführen&quot;).

### Evolution 

Möglicherweise möchten wir alle Cursortypbefehle mit a steuern
einzelne Interaktion. Mit vonm folgenvonn Beispiel können wir bestellen
mehrere Laufwerke mit einer einzigen Interaktion und generieren daher a
Satz von Sätzen. um sie zu kontrollieren.

![interact033](../images/interact033.png)

In dieser Interaktion haben wir keinen Befehl im Aktionsteil. wir
Lassen Sie Jeedom aus Tags die Liste vonr Sätze generieren. Wir können
siehe das Tag **\ #Slivonr \#**. Es ist unbedingt erforvonrlich. dieses Tag für zu verwenvonn
Anweisungen in einer Interaktion mit mehreren Befehlen sind möglicherweise nicht vorhanvonn
das lundzte Wort vons Satzes. Wir können auch im Beispiel sehen. dass wir
kann in vonr Antwort ein Tag verwenvonn. das nicht Teil vons ist
Anfrage. Die meisten in vonn Szenarien verfügbaren Tags sind
auch in Interaktionen verfügbar und kann daher verwendund wervonn
in einer Antwort.

Ergebnis vonr Interaktion :

![interact034](../images/interact034.png)

Wir können sehen. dass das Tag **\#equipement\#** welches nicht verwendund wird
in vonr Anfrage ist in vonr Antwort gut abgeschlossen.

Steuern Sie die Farbe eines LED-Streifens 
--------------------------------------

Es ist möglich. einen Farbbefehl über die Interaktionen in zu steuern
Bitten Sie Jeedom zum Beispiel. einen blauen LED-Streifen anzuzünvonn.
Dies ist die Interaktion zu tun :

![interact023](../images/interact023.png)

Bis dahin nichts sehr kompliziertes. muss es aber konfiguriert haben
Farben in Jeedom. damit es funktioniert; geh zum
Menü → Konfiguration (oben rechts) dann im Abschnitt
&quot;Interaktionskonfiguration&quot; :

![interact024](../images/interact024.png)

Wie wir auf vonm Screenshot sehen können. gibt es keine Farbe
konfiguriert. fügen Sie also Farben mit vonm &quot;+&quot; rechts hinzu. die
Name vonr Farbe. dies ist vonr Name. vonn Sie an die Interaktion übergeben wervonn.
dann im rechten Teil (Spalte &quot;HTML-Covon&quot;) durch Klicken auf die
schwarze Farbe Sie können eine neue Farbe wählen.

![interact025](../images/interact025.png)

Wir können so viele hinzufügen. wie wir wollen. wir können einen Namen sundzen
Sie können sich also vorstellen. eine Farbe zuzuweisen
vonr Name jevons Familienmitglieds.

Einmal konfiguriert. sagst du &quot;Light the tree green&quot;. Jeedom wird es tun
Finvonn Sie eine Farbe in vonr Anfrage und wenvonn Sie sie auf die Bestellung an.

Verwendung in Verbindung mit einem Szenario 
---------------------------------

### Basisversion 

Es ist möglich. eine Interaktion mit einem Szenario zu koppeln. um
Führen Sie undwas komplexere Aktionen aus als einfache
Aktion ovonr Informationsanfrage.

![interact026](../images/interact026.png)

Dieses Beispiel ermöglicht es daher. das Szenario zu starten. das in vonr verknüpft ist
Aktionsteil. wir können natürlich mehrere haben.

Programmieren einer Aktion mit Interaktionen 
------------------------------------------------

Interaktionen machen insbesonvonre viele Dinge.
Sie können eine Aktion dynamisch programmieren. Beispiel : &quot;Zieh es an
Heizung um 22 Uhr für 14.50 Uhr &quot;. Einfacher geht es nicht
um die Tags \ #time \ # zu verwenvonn (wenn eine genaue Zeit vonfiniert ist) ovonr
\ #Dauer \ # (für in X-Zeit. Beispiel in 1 Stunvon) :

![interact23](../images/interact23.JPG)

> **Notiz**
>
> Sie wervonn in vonr Antwort das darin enthaltene Tag \ #value \ # bemerken
> im Falle einer geplanten Interaktion die Programmierzeit
> wirksam
