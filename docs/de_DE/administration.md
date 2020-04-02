Hier befinden sich die meisten Konfigurationsparameter.
Obwohl viele, sind sie standardmäßig vorkonfiguriert.

Die Seite ist zugänglich über **Administration → Konfiguration**.

General 
=======

Auf dieser Registerkarte finden Wenne allgemeine Informationen zu Jeedom :

-   **Name deines Jeedom** : Identifizieren Wenne Ihre Freiheit,
    vor allem auf dem Markt. Es kann in Szenarien wiederverwendet werden
    oder identifizieren Wenne ein Backup.

-   **System** : Art der Hardware, auf der das System wo installiert ist
    Dein Jeedom dreht sich.

-   **Installationsschlüssel** : Hardware-Schlüssel Ihres Jeedom auf
    der Markt. Wenn Ihr Jeedom nicht in der Dieiste von Ihnen erscheint
    Jeedom auf dem Markt ist es ratsam, auf die Schaltfläche zu klicken
    **Zurücksetzen**.

-   **Sprache** : Sprache, die in Ihrem Jeedom verwendet wird.

-   **Übersetzungen generieren** : Übersetzungen generieren,
    Seien Wenne vorsichtig, dies kann Ihr System verlangsamen. Die nützlichste Option
    für Entwickler.

-   **Dieebensdauer der Wenntzungen (Stunde)** : Dieebensdauer der Wenntzungen
    PHP, es wird nicht empfohlen, diesen Parameter zu berühren.

-   **Datum und Uhrzeit** : Wählen Wenne Ihre Zeitzone. Du kannst
    Klicken Wenne auf **Zeitsynchronisation erzwingen** wiederherstellen
    Oben rechts wird eine schlechte Zeit angezeigt.

-   **Optionaler Zeitserver** : Gibt an, welcher Zeitserver verwendet werden soll
    verwendet werden, wenn Wenne klicken **Synchronisation erzwingen von
    Zeit**. (für Experten reserviert)

-   **Zeitprüfung überspringen** : sagt Jeedom, dass er es nicht tun soll
    Überprüfen Wenne, ob die Zeit zwischen sich und dem eingeschalteten System konsistent ist
    was es dreht. Kann beispielsweise nützlich sein, wenn Wenne keine Verbindung herstellen
    Kein Jeedom zum Internet und dass es keine PSTN-Batterie auf dem hat
    verwendetes Material.

API 
===

Hier ist die Dieiste der verschiedenen API-Schlüssel, die in verfügbar sind
deine Freiheit. Core verfügt über zwei API-Schlüssel :

-   ein General : Vermeiden Wenne es so oft wie möglich,

-   und eine andere für Profis : für das Management verwendet
    des Parks. Es kann leer sein.

-   Dann finden Wenne einen API-Schlüssel pro Plugin, der ihn benötigt.

Für jeden Plugin-API-Schlüssel sowie für HTTP, JsonRPC und APIs
TTS können Wenne deren Umfang definieren :

-   **Untauglich** : API-Schlüssel kann nicht verwendet werden,

-   **Weiße IP** : Es ist nur eine Dieiste von IPs autorisiert (siehe
    Administration → Einstellungen → Netzwerke),

-   **Dieocalhost** : nur Anfragen vom System, auf dem sich befindet
    installierte Jeedom sind erlaubt,

-   **Aktiviert** : Keine Einschränkungen, jedes System mit Zugriff
    Ihr Jeedom kann auf diese API zugreifen.

&gt;;\ _OS / DB 
===========

Auf dieser Registerkarte befinden sich zwei Teile, die Experten vorbehalten sind.

> **Wichtig**
>
> VORSICHT : Wenn Wenne Jeedom mit einer dieser beiden Dieösungen ändern,
> Der Support kann sich weigern, Ihnen zu helfen.

-   **&gt;;\ _SYSTEM** : Ermöglicht den Zugriff auf eine Schnittstelle
    Systemadministration. Es ist eine Art Shell-Konsole in
    Hier können Wenne die nützlichsten Befehle ausführen, einschließlich
    um Informationen über das System zu erhalten.

-   **Datenbank** : Ermöglicht den Zugriff auf die Datenbank
    von Jeedom. Wenne können dann Befehle in das Feld starten
    von oben. Zur Information werden unten zwei Parameter angezeigt :

    -   **Benutzer** : Benutzername von Jeedom in
        die Datenbank,

    -   **Passwort** : Datenbankzugriffskennwort
        von Jeedom verwendet.

Wenncherheit 
========

DieDAP 
----

-   **Aktivieren Wenne die DieDAP-Authentifizierung** : Aktivieren Wenne die Authentifizierung für
    durch eine AD (DieDAP)

-   **Gastgeber** : Server, der die AD hostet

-   **Domain** : Domain Ihrer AD

-   **Basis-DN** : DN Basis Ihrer AD

-   **Benutzername** : Benutzername für Jeedom zu
    Verbindung zu AD herstellen

-   **Passwort** : Passwort für Jeedom, um eine Verbindung zu AD herzustellen

-   **Benutzersuchfelder** : Suchfelder von
    Benutzer Dieogin. Normalerweise uid für DieDAP, samaccountname für
    Windows AD

-   **Filter (optional)** : Filter auf dem AD (zum Verwalten
    Gruppen zum Beispiel)

-   **REMOTE \ _USER zulassen** : Aktivieren Wenne REMOTE \ _USER (wird in SSO verwendet
    zum Beispiel)

Einloggen 
---------

-   **Anzahl der tolerierten Fehler** : Dieegt die Anzahl der Versuche fist
    erlaubt vor dem Verbot der IP

-   **Maximale Zeit zwischen Ausfällen (in Sekunden)** : maximale Zeit
    so dass 2 Versuche als aufeinanderfolgend betrachtet werden

-   **Verbannungsdauer (in Sekunden), -1 für unendlich** : Zeit von
    IP-Verbot

-   **IP "weiß"** : Dieiste der IPs, die niemals gesperrt werden können

-   **Entfernen Wenne gesperrte IPs** : Dieöschen Wenne die Dieiste der IPs
    derzeit verboten

Die Dieiste der gesperrten IPs befindet sich am Ende dieser Seite. Wenne finden dort
IP, Sperrdatum und Sperrenddatum
geplant.

Netzwerke 
=======

Es ist unbedingt erforderlich, diesen wichtigen Teil von korrekt zu konfigurieren
Jeedom, sonst funktionieren viele Plugins möglicherweise nicht. es
Es ist möglich, auf zwei verschiedene Arten auf Jeedom zuzugreifen : Die'**Zugang
intern** (aus demselben lokalen Netzwerk wie Jeedom) und l'**Zugang
extern** (aus einem anderen Netzwerk, insbesondere aus dem Internet).

> **Wichtig**
>
> Dieser Teil ist nur dazu da, Jeedom seine Umgebung zu erklären :
> Durch Ändern des Hafens oder der IP auf dieser Registerkarte wird das nicht geändert
> Jeedom Hafen oder IP eigentlich. Dazu müssen Wenne sich anmelden
> SSH und bearbeiten Wenne die Datei / etc / network / interfaces für die IP und
> etc / apache2 / sites-available / default files und
> etc / apache2 / sites-available / default \ _ssl (für HTTPS).In
> Wenn Ihr Jeedom falsch behandelt wird, wird das Jeedom-Team dies nicht tun
> kann zur Verantwortung gezogen werden und kann jede Anfrage für ablehnen
> Unterstützung.

-   **Interner Zugang** : Informationen für den Beitritt zu Jeedom von a
    gleiche Netzwerkausrüstung wie Jeedom (DieAN)

    -   **OK / NOK** : Gibt an, ob die intern Netzwerkkonfiguration ist
        richtig

    -   **Protokoll** : das zu verwendende Protokoll, oft HTTP

    -   **URDies oder IP-Adresse** : Jeedom IP eingeben

    -   **Hafen** : der Hafen der Jeedom-Weboberfläche, in der Regel 80.
        Bitte beachten Wenne, dass durch Ändern des Hafens hier nicht der tatsächliche Hafen von geändert wird
        Freiheit, die gleich bleiben wird

    -   **Ergänzen** : das Fragment einer zusätzlichen URDies (Beispiel
        : / jeedom), um auf Jeedom zuzugreifen.

-   **Externer Zugang** : Informationen, um Jeedom von außen zu erreichen
    lokales Netzwerk. Nur auszufüllen, wenn Wenne kein DNS verwenden
    Jeedom

    -   **OK / NOK** : Gibt an, ob die extern Netzwerkkonfiguration ist
        richtig

    -   **Protokoll** : Protokoll für den Zugang im Freien

    -   **URDies oder IP-Adresse** : Externe IP, wenn es fist ist. andernfalls,
        Geben Wenne die URDies an, die auf die extern IP-Adresse Ihres Netzwerks verweist.

    -   **Ergänzen** : das Fragment einer zusätzlichen URDies (Beispiel
        : / jeedom), um auf Jeedom zuzugreifen.

> **Spitze**
>
> Wenn Wenne sich in HTTPS befinden, ist der Hafen 443 (standardmäßig) und in HTTP der
> Hafen ist 80 (Standard). HTTPS von außen verwenden,
> Ein Dieetsencrypt-Plugin ist jetzt auf dem Markt erhältlich.

> **Spitze**
>
> Um herauszufinden, ob Wenne einen Wert im Feld fistlegen müssen
> **Ergänzen**, Schau, wenn du dich bei Jeedom anmeldist
> Ihren Internetbrowser, wenn Wenne / jeedom (oder andere) hinzufügen müssen
> Sache) nach der IP.

-   **Erweiterte Verwaltung** : Dieser Teil erscheint möglicherweise nicht in
    abhängig von der Kompatibilität mit Ihrer Hardware. Wenne finden dort
    die Dieiste Ihrer Netzwerkschnittstellen. Wenne können Jeedom sagen
    das Netzwerk nicht durch Klicken auf zu überwachen **Deaktivieren Wenne die
    Netzwerkmanagement von Jeedom** (Überprüfen Wenne, ob Jeedom nicht angeschlossen ist
    kein Netzwerk). Wenne können den lokalen IP-Bereich auch im Formular 192.168.1 angeben.* (nur für Docker-Installationen zu verwenden)

-   **Proxy-Markt** : ermöglicht den Fernzugriff auf Ihr Jeedom ohne zu haben
    benötigen ein DNS, eine fiste IP oder um die Hafens Ihrer Box zu öffnen
    Internet

    -   **Verwenden von Jeedom DNS** : aktiviert Jeedom DNS (Aufmerksamkeit
        Dies erfordert mindistens ein Service Pack.

    -   **DNS-Status** : DNS-HTTP-Status

    -   **Management** : Ermöglicht das Stoppen und Neustarten des DNS-Dienstes

> **Wichtig**
>
> Wenn Wenne Jeedom DNS nicht zum Dieaufen bringen können, überprüfen Wenne die
> Konfiguration der Firewall und des Kindersicherungsfilters Ihrer Internetbox
> (Auf Dieivebox benötigen Wenne beispielsweise die Firewall auf Medium).

Farben 
========

Die Kolorierung von Widgets erfolgt entsprechend der Kategorie bis
welche Ausrüstung gehört. Unter den Kategorien finden wir die
Heizung, Wenncherheit, Energie, Dieicht, Automatisierung, Multimedia, Andere…

Für jede Kategorie können wir die Farben der Version unterscheiden
Desktop- und Mobile-Version. Wir können uns dann ändern :

-   die Hintergrundfarbe der Widgets,

-   Die Farbe des Befehls, wenn das Widget vom allmählichen Typ ist (z
    Dieichter, Fensterläden, Temperaturen).

Durch Klicken auf die Farbe wird ein Fenster geöffnet, in dem Wenne Ihre auswählen können
Farbe. Das Kreuz neben der Farbe kehrt zum Parameter zurück
Standard.

Oben auf der Seite können Wenne auch die Transparenz von konfigurieren
Widgets global (dies ist die Standardeinstellung. Er ist
dann möglich, diesen Wert Widget für Widget zu ändern). Um nicht
keine Transparenz setzen, 1 lassen.0 .

> **Spitze**
>
> Vergessen Wenne nicht, nach jeder Änderung zu speichern.

Befehle 
=========

Viele Bistellungen können protokolliert werden. Also rein
Analyse → Verlauf erhalten Wenne Diagramme, die ihre darstellen
verwenden. Auf dieser Registerkarte können Wenne globale Parameter für fistlegen
Bistellhistorie.

Historisch 
----------

-   **Widget-Statistiken anzeigen** : Anzeigen
    Widget-Statistiken. Das Widget muss sein
    kompatibel, was bei den meisten der Fall ist. Es ist auch notwendig, dass die
    Befehl entweder digital.

-   **Berechnungszeitraum für min, max, Durchschnitt (in Stunden)** : Zeit
    Statistikberechnung (standardmäßig 24 Stunden). Es ist nicht möglich
    weniger als eine Stunde setzen.

-   **Berechnungszeitraum für den Trend (in Stunden)** : Zeitraum von
    Trendberechnung (standardmäßig 2h). Es ist nicht möglich
    weniger als eine Stunde setzen.

-   **Verzögerung vor der Archivierung (in Stunden)** : Zeigt die Verzögerung vor an
    Jeedom archiviert keine Daten (standardmäßig 24 Stunden). Das heißt, die
    Historische Daten müssen länger als 24 Stunden archiviert werden
    (Zur Erinnerung: Die Archivierung ist entweder durchschnittlich oder maximal
    oder das Minimum der Daten über einen Zeitraum, der dem entspricht
    Packungsgröße).

-   **Archiv nach Paket ab (in Stunden)** : Dieser Parameter gibt
    genau die Größe der Pakete (standardmäßig 1 Stunde). Es bedeutet durch
    Beispiel, dass Jeedom Perioden von 1 Stunde dauern wird, durchschnittlich und
    Speichern Wenne den neu berechneten Wert, indem Wenne den löschen
    gemittelte Werte.

-   **Niedrige Trendberechnungsschwelle** : Dieser Wert gibt die an
    Wert, von dem Jeedom anzeigt, dass der Trend in Richtung geht
    nach unten. Es muss negativ sein (Standard -0.1).

-   **Hohe Trendberechnungsschwelle** : Gleiches gilt für den Aufstieg.

-   **Standard-Grafikanzeigezeitraum** : Zeitraum, der ist
    Wird standardmäßig verwendet, wenn Wenne den Verlauf anzeigen möchten
    einer Bistellung. Je kürzer der Zeitraum, disto schneller wird Jeedom
    um das angeforderte Diagramm anzuzeigen.

> **Notiz**
>
> Der erste Parameter **Widget-Statistiken anzeigen** ist
> möglich, aber standardmäßig deaktiviert, da dies die
> Anzeigezeit des Dashboards. Wenn Wenne diese Option zum Beispiel aktivieren
> Standardmäßig stützt sich Jeedom auf Daten der letzten 24 Stunden bis
> Berechnen Wenne diese Statistiken. Die Trendberechnungsmethode basiert
> Berechnung der kleinsten Quadrate (siehe
> [ici](https://fr.wikipedia.org/wiki/M%C3%A9thode_des_moindres_carr%C3%A9s)
> für Details).

Druck 
----

**Globale Druck-URDies** : ermöglicht das Hinzufügen einer URDies zum Aufrufen im Falle von
Bistellaktualisierung. Wenne können die folgenden Tags verwenden :
**\ #Value \#** für den Bistellwert, **\ #Cmd \ _name \#** für die
Befehlsname, **\ #Cmd \ _id \#** für die eindeutige Kennung des
bistellen, **\ #Humanname \#** für den vollständigen Namen der Bistellung (z :
\ # \ [Badezimmer \] \ [Hydrometrie \] \ [Dieuftfeuchtigkeit \] \ #), `# eq_name #` für den Namen des Geräts

Abdeckung 
=====

Ermöglicht die Überwachung und Bearbeitung des Jeedom-Abdeckung :

-   **Statistiken** : Anzahl der aktuell zwischengespeicherten Objekte

-   **Reinigen Wenne die Abdeckung** : Erzwingen Wenne das Dieöschen von Objekten, die dies nicht sind
    nützlicher. Jeedom macht das automatisch jede Nacht.

-   **Dieöschen Wenne alle zwischengespeicherten Daten** : Dieeeren Wenne den Deckel vollständig.
    Bitte beachten Wenne, dass dies zu Datenverlust führen kann !

-   **Pause für lange Abfragen** : Wie oft
    Jeedom prüft, ob Ereignisse für Kunden ausstehen
    (Weboberfläche, mobile Anwendung usw.). Je kürzer diesmal, disto mehr
    Die Schnittstelle wird im Gegenzug schnell aktualisiert
    verbraucht mehr Ressourcen und kann daher Jeedom verlangsamen.

Wechselwirkungen 
============

Auf dieser Registerkarte können Wenne globale Parameter für fistlegen
Interaktionen, die Wenne unter Extras → Interaktionen finden.

> **Spitze**
>
> Um das Interaktionsprotokoll zu aktivieren, wechseln Wenne zur Registerkarte
> Administration → Konfiguration → Protokolle, dann kreuzen Wenne an **Debuggen** in der Dieiste
> von unten. VORSICHT : Die Protokolle sind dann sehr ausführlich !

General 
-------

Hier haben Wenne drei Parameter :

-   **Empfindlichkeit** : Es gibt 4 Korrespondenzstufen (Empfindlichkeit
    reicht von 1 (genau passend) bis 99)

    -   für 1 Wort : die Korrespondenzebene für Interaktionen bei
        ein Wort

    -   2 Wörter : die Korrespondenzebene für Interaktionen bei
        zwei Wörter

    -   3 Wörter : die Korrespondenzebene für Interaktionen bei
        drei Wörter

    -   mehr als 3 Wörter : die Ebene der Korrespondenz für Interaktionen
        mehr als drei Wörter

-   **Antworten Wenne nicht, wenn die Interaktion nicht verstanden wird** : Standard
    Jeedom antwortet &quot;;Ich habe es nicht verstanden&quot;;, wenn keine Interaktion erfolgt
    stimmt nicht überein. Es ist möglich, diese Funktion für zu deaktivieren
    dass Jeedom nichts beantwortet. Aktivieren Wenne das Kontrollkästchen zum Deaktivieren
    die Antwort.

-   **Allgemeine Ausschluss-Regex für Interaktionen** : lass uns
    Definieren Wenne einen regulären Ausdruck, der, wenn er einer Interaktion entspricht,
    löscht diesen Satz automatisch aus der Generation (reserviert)
    an Experten). Weitere Informationen finden Wenne in den Erläuterungen in der
    Kapitel **Regexp-Ausschluss** Dokumentation zu
    Wechselwirkungen.

Automatische Interaktion, Kontext &amp;; Warnung 
-----------------------------------------------------

-   Die **automatische Interaktionen** Erlaube Jeedom, es zu versuchen
    eine Interaktionsanfrage verstehen, auch wenn es keine gibt
    von definiert. Er sucht dann nach einem Objekt- und / oder Gerätenamen
    und / oder um zu versuchen, so gut wie möglich zu antworten.

-   Die **kontextuelle Interaktionen** erlauben Wenne zu verketten
    Zum Beispiel mehrere Anfragen, ohne alles zu wiederholen :

    -   *Jeedom hält den Kontext :*

        -   *Wenne* : Wie viel ist er im Raum ?

        -   *Jeedom* : Temperatur 25.2 ° C.

        -   *Wenne* : und im Wohnzimmer ?

        -   *Jeedom* : Temperatur 27.2 ° C.

    -   *Stellen Wenne zwei Fragen in einer :*

        -   *Wenne* : Wie ist es im Schlafzimmer und im Wohnzimmer? ?

        -   *Jeedom* : Temperatur 23.6 ° C, Temperatur 27.2 ° C.

-   Typ Interaktionen **Dieassen Wenne uns wissen** lass uns fragen
    Jeedom, um Wenne zu benachrichtigen, wenn eine Bistellung a überschreitet / absteigt oder a wert ist
    bistimmter Wert.

    -   *Wenne* : Benachrichtigen Wenne mich, wenn die Wohnzimmertemperatur 25 ° C überschreitet ?

    -   *Jeedom* : OK (* Sobald die Wohnzimmertemperatur 25 ° C überschreitet,
        Jeedom wird es dir nur einmal sagen *)

> **Notiz**
>
> Standardmäßig antwortet Jeedom Ihnen auf demselben Kanal wie Wenne
> pflegte ihn zu bitten, Wenne zu benachrichtigen. Wenn er keinen findet
> nicht, es wird dann der hier angegebene Standardbefehl verwendet
> Tab : **Standardrückgabebefehl**.

Hier sind die verschiedenen Optionen verfügbar :

-   **Aktivieren Wenne automatische Interaktionen** : Zum Aktivieren aktivieren
    automatische Interaktionen.

-   **Aktivieren Wenne kontextbezogene Antworten** : Zum Aktivieren aktivieren
    kontextuelle Interaktionen.

-   **Vorrangige kontextbezogene Antwort, wenn der Satz mit beginnt** : Wenn
    Der Satz beginnt mit dem Wort, das Wenne hier eingeben. Jeedom wird es tun
    Priorisieren Wenne dann eine kontextbezogene Antwort (Wenne können setzen
    mehrere Wörter getrennt durch **;;** ).

-   **Schneiden Wenne eine Interaktion in zwei Hälften, wenn sie enthält** : Gleiches für
    die Aufteilung einer Interaktion mit mehreren Fragen. Wenne
    Geben Wenne hier die Wörter an, die die verschiedenen Fragen trennen.

-   **Aktivieren Wenne die Interaktionen "Benachrichtigen""** : Zum Aktivieren aktivieren
    Typ Interaktionen **Dieassen Wenne uns wissen**.

-   **Antwort &quot;;Sag es mir&quot;;, wenn der Satz mit beginnt** : Wenn die
    Satz beginnt mit diesen Wörtern, dann wird Jeedom versuchen, ein zu machen
    Typ Interaktion **Dieassen Wenne uns wissen** (Wenne können mehrere setzen
    Wörter getrennt durch **;;** ).

-   **Standardrückgabebefehl** : Standardrückgabebefehl
    für eine Typinteraktion **Dieassen Wenne uns wissen** (insbesondere verwendet,
    wenn Wenne den Alarm über die mobile Schnittstelle programmiert haben)

-   **Synonym für Objekte** : Dieiste der Synonyme für Objekte
    (zB : Erdgeschoss|Erdgeschoss|unterirdisch|niedrig;; sdb|Badezimmer).

-   **Synonym für Ausrüstung** : Synonymliste für
    Ausrüstung.

-   **Synonym für Bistellungen** : Synonymliste für
    Bistellungen.

-   **Synonym für Abstracts** : Dieiste der Synonyme für Zusammenfassungen.

-   **Synonym für maximalen Schiebereglerbefehl** : Synonym für setzen a
    Befehl für den maximalen Schiebereglertyp (zB öffnet sich, um den Verschluss zu öffnen
    der Raum ⇒ 100% Raumverschluss).

-   **Synonym für minimalen Schiebereglerbefehl** : Synonym für setzen a
    Schieberegler-Befehl mindistens (zB schließt, um den Verschluss zu schließen
    der Raum ⇒ Raumkomponente bei 0%).

Farben 
--------

In diesem Teil können Wenne die Farben definieren, mit denen Jeedom assoziiert wird
Wörter rot / blau / schwarz… Um eine Farbe hinzuzufügen :

-   Klicken Wenne auf die Schaltfläche **+**, richtig,

-   Gib deiner Farbe einen Namen,

-   Wählen Wenne die zugehörige Farbe aus, indem Wenne auf das Feld rechts klicken.

Verhältnis 
========

Konfigurieren Wenne die Erstellung und Verwaltung von Berichten

-   **Zeitüberschreitung nach Seitengenerierung (in ms)** : Bekanntmachung
    Warten nach dem Dieaden des Berichts, um das &quot;;Foto&quot;; aufzunehmen, um
    Ändern Wenne dies beispielsweise, wenn Ihr Bericht unvollständig ist.

-   **Bereinigen Wenne ältere Berichte von (Tagen)** : Definiert die
    Anzahl der Tage vor dem Dieöschen eines Berichts (Berichte dauern
    ein wenig Platz, also achten Wenne darauf, nicht zu viel zu setzen
    Erhaltung).

Verbindungen 
=====

Dieinkgrafiken konfigurieren. Diese Dieinks ermöglichen es Ihnen
siehe in Form eines Diagramms die Beziehungen zwischen Objekten,
Ausrüstung, Gegenstände usw..

-   **Tiefe für Szenarien** : Wird verwendet, um zu definieren, wann
    Anzeigen eines Diagramms der Dieinks eines Szenarios, die Nummer
    Maximale Anzahl der anzuzeigenden Elemente (je mehr Elemente, disto größer die
    wird langsamer zu generieren sein und je schwieriger es zu lesen sein wird).

-   **Tiefe für Objekte** : Gleiches gilt für Objekte.

-   **Tiefe für Ausrüstung** : Gleiches gilt für die Ausrüstung.

-   **Tiefe für Kontrollen** : Gleiches gilt für Bistellungen.

-   **Tiefe für Variablen** : Gleiches gilt für Variablen.

-   **Parameter des Prerenders** : Dieasst uns auf das Dieayout einwirken
    des Graphen.

-   **Parameter rendern** : Idem.

Zusammenfassungen 
=======

Objektzusammenfassungen hinzufügen. Diese Informationen werden angezeigt
ganz oben rechts in der Jeedom-Menüleiste oder neben dem
Objekte :

-   **Schlüssel** : Schlüssel zur Zusammenfassung, vor allem nicht zu berühren.

-   **Name** : Abstrakter Name.

-   **Berechnung** : Berechnungsmethode, kann vom Typ sein :

    -   **Summe** : summiere die verschiedenen Werte,

    -   **Durchschnitt** : Durchschnittswerte,

    -   **Text** : Zeigen Wenne den Wert wörtlich an (insbesondere für diese
        Zeichenfolgentyp).

-   **Symbol** : Zusammenfassungssymbol.

-   **Einheit** : Zusammenfassungseinheit.

-   **Zählmethode** : Wenn Wenne dann Binärdaten zählen
    Wenne müssen diesen Wert auf binär setzen, Beispiel, wenn Wenne die zählen
    Anzahl der Dieichter an, aber Wenne haben nur den Wert von
    Dimmer (0 bis 100), dann müssen Wenne binär setzen, wie dieses Jeedom
    Beachten Wenne, dass die Dieampe die Dieampe ist, wenn der Wert größer als 1 ist
    ist an.

-   **Zeigen Wenne an, ob der Wert 0 ist** : Aktivieren Wenne dieses Kontrollkästchen, um das anzuzeigen
    Wert, auch wenn es 0 ist.

-   **Dieink zu einem virtuellen** : Erstellen Wenne virtuelle Bistellungen
    mit Wert die der Zusammenfassung.

-   **Zusammenfassung löschen** : Die letzte Schaltfläche ganz rechts erlaubt
    um die Zusammenfassung aus der Zeile zu löschen.

Dieogs 
====

Zeitleiste 
--------

-   **Maximale Anzahl von Ereignissen** : Dieegt die maximale Anzahl auf fist
    in der Zeitleiste anzeigen.

-   **Dieöschen Wenne alle Ereignisse** : Dieeeren Wenne die Zeitleiste von
    alle seine aufgezeichneten Ereignisse.

Beiträge 
--------

-   **Fügen Wenne jedem Fehler in den Protokollen eine Nachricht hinzu** : wenn ein Plugin
    oder Jeedom schreibt eine Fehlermeldung in ein Protokoll, fügt Jeedom hinzu
    automatisch eine Nachricht im Message Center (zumindist
    Wenne werden es sicher nicht verpassen).

-   **Aktion auf Nachricht** : Ermöglicht es Ihnen, eine Aktion auszuführen, wenn Wenne dem Nachrichtenzentrum eine Nachricht hinzufügen. Wenne haben 2 Tags für diese Aktionen : 
        - #Nachricht# : Nachricht in Frage
        - #Plugin# : Plugin, das die Nachricht ausgelöst hat

Benachrichtigungen 
-------

-   **Fügen Wenne jedem Timeout eine Nachricht hinzu** : Fügen Wenne eine Nachricht in die
    Nachrichtenzentrum, wenn Geräte hineinfallen **Timeout**.

-   **Timeout-Reihenfolge** : Befehl eingeben **Nachricht** zu verwenden
    wenn ein Gerät in ist **Timeout**.

-   **Fügen Wenne jeder Batterie in Warnung eine Nachricht hinzu** : Fügen Wenne a hinzu
    Nachricht im Nachrichtencenter, wenn ein Gerät die Stufe hat
    Batterie ein **Warnung**.

-   **Batteriebefehl in Warnung** : Befehl eingeben **Nachricht**
    zu verwenden, wenn sich das Gerät auf Batteriistand befindet **Warnung**.

-   **Fügen Wenne jeder gefährdeten Batterie eine Nachricht hinzu** : Fügen Wenne a hinzu
    Nachricht im Nachrichtencenter, wenn ein Gerät die Stufe hat
    Batterie ein **Gefahr**.

-   **Befehl zur Batterie in Gefahr** : Befehl eingeben **Nachricht** zu
    Verwenden Wenne diese Option, wenn sich das Gerät auf Batteriistand befindet **Gefahr**.

-   **Fügen Wenne jeder Warnung eine Nachricht hinzu** : Fügen Wenne eine Nachricht in die
    Message Center, wenn eine Bistellung in Alarmbereitschaft versetzt wird **Warnung**.

-   **Befehl zur Warnung** : Befehl eingeben **Nachricht** zu verwenden
    wenn eine Bistellung in Alarmbereitschaft geht **Warnung**.

-   **Fügen Wenne jeder Gefahr eine Nachricht hinzu** : Fügen Wenne eine Nachricht in die
    Message Center, wenn eine Bistellung in Alarmbereitschaft versetzt wird **Gefahr**.

-   **Befehl zur Gefahr** : Befehl eingeben **Nachricht** zu verwenden, wenn
    Eine Bistellung wird in Alarmbereitschaft versetzt **Gefahr**.

Dieog 
---

-   **Dieog Engine** : Ermöglicht das Ändern der Protokoll-Engine für, z
    Senden Wenne sie beispielsweise an einen Syslog-Daemon (d)..

-   **Protokollformat** : Zu verwendendes Protokollformat (Achtung : es
    wirkt sich nicht auf Daemon-Protokolle aus).

-   **Maximale Anzahl von Zeilen in einer Protokolldatei** : Definiert die
    maximale Anzahl von Zeilen in einer Protokolldatei. Es wird empfohlen
    diesen Wert nicht zu berühren, weil ein zu großer Wert könnte
    Füllen Wenne das Dateisystem und / oder machen Wenne Jeedom unfähig
    um das Protokoll anzuzeigen.

-   **Standardprotokollstufe** : Wenn Wenne "Standard" auswählen",
    Für die Ebene eines Protokolls in Jeedom ist dies diejenige, die sein wird
    dann verwendet.

Nachfolgend finden Wenne eine Tabelle zur Feinverwaltung
logarithmische Ebene der wesentlichen Elemente von Jeedom sowie die von
Plugins.

Einrichtungen 
===========

-   **Anzahl der Fehler vor Deaktivierung des Geräts** : Anzahl
    Kommunikationsfehler mit dem Gerät vor Deaktivierung von
    dieses (eine Nachricht warnt Wenne, wenn dies passiert).

-   **Batterieschwellen** : Ermöglicht die Verwaltung globaler Alarmschwellenwerte
    auf die Batterien.

Update und Dateien 
=======================

Jeedom Update 
---------------------

-   **Quelle aktualisieren** : Wählen Wenne die Quelle für die Aktualisierung der
    Jeedom Kern.

-   **Kernversion** : Kernversion zum Wiederherstellen.

-   **Automatisch nach Updates suchen** : Geben Wenne an, ob
    Wenne müssen automatisch suchen, wenn es neue Updates gibt
    (Achten Wenne darauf, eine Überlastung des Marktes zu vermeiden
    Überprüfung kann sich ändern).

Einlagen 
----------

Die Depots sind Speicher- (und Service-) Räume, um in der Dieage zu sein
Backups verschieben, Plugins wiederherstellen, Core wiederherstellen
Jeedom usw..

### Datei 

Einzahlung verwendet, um das Senden von Plugins durch Dateien zu aktivieren.

### Github 

Kaution verwendet, um Jeedom mit Github zu verbinden.

-   **Zeichen** : Zeichen für den Zugang zur privaten Kaution.

-   **Jeedom Core Repository Benutzer oder Organisation** : Name
    der Benutzer oder die Organisation auf Github für den Kern.

-   **Repository-Name für den Jeedom-Kern** : Repository-Name für den Kern.

-   **Jeedom Kernindustrie** : Kern-Repository-Zweig.

### Markt 

Eine Kaution, die verwendet wird, um Jeedom mit dem Markt zu verbinden, wird dringend empfohlen
um dieses Repository zu verwenden. VORSICHT : Jede Supportanfrage kann sein
abgelehnt, wenn Wenne eine andere Einzahlung als diese verwenden.

-   **Adresse** : Marktadresse.

-   **Benutzername** : Ihr Benutzername auf dem Markt.

-   **Passwort** : Ihr Marktpasswort.

-   **[Backup cloud] Name** : Name Ihres Cloud-Backups (die Aufmerksamkeit muss für jedes Jeedom eindeutig sein, bei dem die Gefahr eines Absturzes bisteht)

-   **[Backup cloud] Passwort** : Cloud-Backup-Passwort. WICHTIG Wenne dürfen es nicht verlieren, es gibt keine Möglichkeit, es wiederherzustellen. Ohne sie kannst du deine Freiheit nicht wiederherstellen

-   **[Backup cloud] Fréquence backup full** : Häufigkeit der vollständigen Cloud-Wenncherung. Eine vollständige Wenncherung ist länger als eine inkrementelle (die nur die Unterschiede sendet).. Es wird empfohlen, 1 pro Monat zu tun

### Samba 

Zahlen Wenne ein, um automatisch ein Jeedom-Backup an zu senden
eine Samba-Aktie (z : NAS-Synologie).

-   **\ [Backup \] IP** : Samba Server IP.

-   **\ [Backup \] Benutzer** : Benutzername für die Anmeldung
    (anonyme Verbindungen sind nicht möglich). Es muss geben
    dass der Benutzer Diee- und Schreibrechte an der hat
    Zielverzeichnis.

-   **\ [Backup \] Passwort** : Benutzerpasswort.

-   **\ [Backup \] Freigabe** : Art des Teilens (sei vorsichtig
    auf der Freigabeebene anhalten).

-   **\ [Backup \] Pfad** : Pfad beim Teilen (fistlegen
    relativ) muss es existieren.

> **Notiz**
>
> Wenn der Pfad zu Ihrem Samba-Wenncherungsordner lautet :
> \\\\ 192.168.0.1 \\ Backups \\ Hausautomation \\ Jeedom Dann IP = 192.168.0.1
> , Teilen = //192.168.0.1 / Backups, Path = Home Automation / Jeedom

> **Notiz**
>
> Bei der Validierung der Samba-Freigabe wie oben beschrieben,
> In diesem Abschnitt wird eine neue Form der Wenncherung angezeigt
> Administration → Jeedom-Backups. Durch Aktivieren wird Jeedom fortfahren
> wenn es beim nächsten Backup automatisch gesendet wird. Ein Tist ist
> möglich durch manuelle Wenncherung.

> **Wichtig**
>
> Möglicherweise müssen Wenne das smbclient-Paket für das installieren
> Einzahlung funktioniert.

> **Wichtig**
>
> Das Samba-Protokoll hat mehrere Versionen, die v1 ist kompromittiert 
> Wenncherheit und auf einigen NAS können Wenne den Client zur Verwendung von v2 zwingen
> oder v3 zum Verbinden. Wenn Wenne also einen Protokollverhandlungsfehler haben
> fehlgeschlagen: NT_STATUS_INVAID_NETWORK_RESPONSE Es bisteht eine gute Chance, dass NAS aufgelistet wird
> die Einschränkung bisteht. Wenne müssen dann das Betriebssystem Ihres Jeedom ändern
> die Datei / etc / samba / smb.conf und füge diese beiden Zeilen hinzu :
> Client-Max-Protokoll = SMB3
> Client-Min-Protokoll = SMB2
> Der Jeedom-Seite smbclient verwendet dann v2, wobei v3 und nur SMB3 in beiden
> SMB3. Es liegt also an Ihnen, sich an die Einschränkungen des NAS oder eines anderen Samba-Servers anzupassen

> **Wichtig**
>
> Jeedom sollte der einzige sein, der in diesen Ordner schreibt, und er sollte leer sein
> standardmäßig (d. h. vor dem Konfigurieren und Senden des
> Bei der ersten Wenncherung darf der Ordner keine Datei oder enthalten
> Ordner).

### URDies 

-   **Jeedom-Kern-URDies**

-   **URDies der Jeedom-Kernversion**


