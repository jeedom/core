Hier befinden sich die meisten Konfigurationsparameter..
Obwohl viele, ist eine Mehrheit standardmäßig vorkonfiguriert.

Die Seite ist über zugänglich  **Einstellungen → System → Konfiguration**.

General
=======

Auf dieser Registerkarte finden wenne allgemeine Informationen zu Jeedom :

-   **Name deines Jeedom** : Lass uns dein Jeedom identifizieren,
    vor allem auf dem Markt. Es kann in Szenarien wiederverwendet werden
    oder identifizieren wenne ein Backup.

-   **Sprache** : Sprache, die in Ihrem Jeedom verwendet wird.

-   **System** : Art der Hardware, auf der das System wo installiert ist
    Dein Jeedom dreht sich.

-   **Übersetzungen generieren** : Übersetzungen erstellen,
    Seien wenne vorsichtig, dies kann Ihr System verlangsamen. Die nützlichste Option
    für Entwickler.

-   **Datum und Uhrzeit** : Wählen wenne Ihre Zeitzone. Du kannst
    Klicken wenne auf **Zeitsynchronisation erzwingen** wiederherstellen
    Oben rechts wird eine schlechte Zeit angezeigt.

-   **Optionaler Zeitserver** : Gibt an, welcher Zeitserver verwendet werden soll
    verwendet werden, wenn wenne klicken **Zeitsynchronisation erzwingen**
    (für Experten reserviert).

-   **Zeitprüfung überspringen** : sagt Jeedom, dass er es nicht tun soll
    Überprüfen wenne, ob die Zeit zwischen sich und dem eingeschalteten System konsistent ist
    was es dreht. Kann zum Beispiel nützlich sein, wenn wenne keine Verbindung herstellen
    Kein Jeedom zum Internet und dass es keine PSTN-Batterie auf dem hat
    verwendetes Material.

-   **System** : Gibt den Hardwaretyp an, auf dem Jeedom installiert ist.   

-   **Installationsschlüssel** : Hardware-Schlüssel Ihres Jeedom auf
    der Markt. Wenn Ihr Jeedom nicht in der Liste von Ihnen erscheint
    Jeedom on the Markt, es ist ratsam, auf die Schaltfläche zu klicken
    **Zurücksetzen**.

-   **Letztes bekanntes Datum** : Aufnahmedatum von Jeedom, verwendet nach
    ein Neustart für Systeme ohne PSTN-Stack.

Schnittstelle
=========

Auf dieser Registerkarte finden wenne die Parameter für die Anpassung der Anzeige.

Themen
------

-   **Heller und dunkler Desktop** : Hier können wenne ein klares Thema auswählen
    und eine dunkle für den Desktop.

-   **Helles und dunkles Handy** : Gleich wie oben für die Mobile-Version.

-   **Klares Thema von / bis** : Hier können wenne einen Zeitraum definieren, in dem
    Das zuvor ausgewählte klare Thema wird verwendet. Aktivieren wenne jedoch die Option
    **Thema basierend auf der Zeit umschalten**.

-   **Helligkeitssensor**   : Nur mobile Schnittstelle, muss aktiviert werden
    generischer zusätzlicher Sensor in Chrom, Chrom Seite:// flags

-   **Hintergrundbilder ausblenden** : Wird verwendet, um die gefundenen Hintergrundbilder auszublenden
    in den Seiten Szenarien, Objekte, Interaktionen usw..

Fliesen
------

-   **Fliesen nicht horizontal** : Beschränkt die Breite der Kacheln alle x Pixel.

-   **Fliesen Nicht vertikal** : Beschränkt die Höhe der Kacheln alle x Pixel.

-   **Randfliesen** : Vertikaler und horizontaler Abstand zwischen Kacheln in Pixel.

Personalisierung
----------------

Netzwerke
=======

Es ist unbedingt erforderlich, diesen wichtigen Teil von korrekt zu konfigurieren
Jeedom sonst funktionieren viele Plugins möglicherweise nicht. es
Es ist möglich, auf zwei verschiedene Arten auf Jeedom zuzugreifen : die**Zugang
intern** (aus demselben lokalen Netzwerk wie Jeedom) und**Zugang
extern** (aus einem anderen Netzwerk, insbesondere aus dem Internet).

> **wichtig**
>
> Dieser Teil ist nur dazu da, Jeedom seine Umgebung zu erklären :
> Durch Ändern des Hafens oder der IP auf dieser Registerkarte wird das nicht geändert
> Jeedom Hafen oder IP eigentlich. Dazu müssen wenne sich anmelden
> SSH und bearbeiten wenne die Datei / etc / network / interfaces für die IP und
> etc / apache2 / sites-available / default files und
> etc / apache2 / sites-available / default \ _ssl (für HTTPS). In
> Wenn Ihr Jeedom falsch behandelt wird, wird das Jeedom-Team dies nicht tun
> kann zur Verantwortung gezogen werden und kann jede Anfrage für ablehnen
> Unterstützung.

-   **Interner Zugang** : Informationen für den Beitritt zu Jeedom von a
    gleiche Netzwerkausrüstung wie Jeedom (LAN)

    -   **OK / NOK** : Gibt an, ob die intern Netzwerkkonfiguration ist
        richtig

    -   **Protokoll** : das zu verwendende Protokoll, oft HTTP

    -   **URLs oder IP-Adresse** : Jeedom IP eingeben

    -   **Hafen** : der Hafen der Jeedom-Weboberfläche, in der Regel 80.
        Bitte beachten wenne, dass durch Ändern des Hafens hier nicht der tatsächliche Hafen von geändert wird
        Freiheit, die gleich bleiben wird

    -   **ergänzen** : das Fragment einer zusätzlichen URLs (Beispiel
        : / jeedom), um auf Jeedom zuzugreifen.

-   **Externer Zugang** : Informationen, um Jeedom von außen zu erreichen
    lokales Netzwerk. Nur auszufüllen, wenn wenne kein DNS verwenden
    Jeedom

    -   **OK / NOK** : Gibt an, ob die extern Netzwerkkonfiguration ist
        richtig

    -   **Protokoll** : Protokoll für den Zugang im Freien

    -   **URLs oder IP-Adresse** : Externe IP, wenn es fist ist. andernfalls
        Geben wenne die URLs an, die auf die extern IP-Adresse Ihres Netzwerks verweist.

    -   **ergänzen** : das Fragment einer zusätzlichen URLs (Beispiel
        : / jeedom), um auf Jeedom zuzugreifen.

-   **Proxy für den Markt** : Proxy-Aktivierung.

    - Aktivieren wenne das Kontrollkästchen Proxy aktivieren

    - **Proxy-Adresse** : Geben wenne die Proxy-Adresse ein.

    - **Proxy-Hafen** : Geben wenne den Proxy-Hafen ein.

    - **Login** : Geben wenne den Proxy-Login ein.

    - **Passwort** : Geben wenne das Passwort ein.

> **Spitze**
>
> Wenn wenne sich in HTTPS befinden, ist der Hafen 443 (standardmäßig) und in HTTP der
> Hafen ist 80 (Standard). Um HTTPS von außen zu verwenden,
> Ein Letsencrypt-Plugin ist jetzt auf dem Markt erhältlich.

> **Spitze**
>
> Um herauszufinden, ob wenne einen Wert im Feld fistlegen müssen
> **ergänzen**Schauen wenne, wenn wenne sich bei Jeedom anmelden
> Ihren Internetbrowser, wenn wenne / jeedom (oder andere) hinzufügen müssen
> Sache) nach der IP.

-   **Erweiterte Verwaltung** : Dieser Teil erscheint möglicherweise nicht in
    abhängig von der Kompatibilität mit Ihrer Hardware. wenne finden dort
    die Liste Ihrer Netzwerkschnittstellen. wenne können Jeedom sagen
    das Netzwerk nicht durch Klicken auf zu überwachen **Deaktivieren wenne die
    Netzwerkmanagement von Jeedom** (Überprüfen wenne, ob Jeedom nicht angeschlossen ist
    kein Netzwerk). wenne können den lokalen IP-Bereich auch im Formular 192.168.1 angeben.* (nur für Docker-Installationen zu verwenden)

-   **Proxy-Markt** : ermöglicht den Fernzugriff auf Ihr Jeedom ohne zu haben
    benötigen ein DNS, eine fiste IP oder um die Hafens Ihrer Box zu öffnen
    Internet

    -   **Verwenden von Jeedom DNS** : aktiviert Jeedom DNS (Aufmerksamkeit
        Dies erfordert mindistens ein Service Pack.

    -   **DNS-Status** : DNS-HTTP-Status

    -   **Management** : Ermöglicht das Stoppen und Neustarten des Jeedom-DNS-Dienstes

> **wichtig**
>
> Wenn wenne Jeedom DNS nicht zum Laufen bringen können, überprüfen wenne die
> Konfiguration der Firewall und des Kindersicherungsfilters Ihrer Internetbox
> (Auf Livebox benötigen wenne beispielsweise die Firewall auf mittlerer Ebene).
-   **Lebensdauer der wenntzungen (Stunde)** : Lebensdauer der wenntzungen
    PHP, es wird nicht empfohlen, diesen Parameter zu berühren.

logs
====

Timeline
--------

-   **Maximale Anzahl von Ereignissen** : Legt die maximale Anzahl von Ereignissen auf fist
    in der Zeitleiste anzeigen.

-   **Löschen wenne alle Ereignisse** : Leeren wenne die Zeitleiste von
    alle seine aufgezeichneten Ereignisse.

Beiträge
--------

-   **Fügen wenne jedem Fehler in den Protokollen eine Nachricht hinzu** : wenn ein Plugin
    oder Jeedom schreibt eine Fehlermeldung in ein Protokoll, fügt Jeedom hinzu
    automatisch eine Nachricht im Message Center (zumindist
    wenne werden es sicher nicht verpassen).

-   **Aktion auf Nachricht** : Ermöglicht es Ihnen, eine Aktion auszuführen, wenn wenne dem Nachrichtenzentrum eine Nachricht hinzufügen. wenne haben 2 Tags für diese Aktionen :
        - #Thema# : Nachricht in Frage
        - #Plugin# : Plugin, das die Nachricht ausgelöst hat

Benachrichtigungen
-------

-   **Fügen wenne jedem Timeout eine Nachricht hinzu** : Fügen wenne eine Nachricht in die
    Nachrichtenzentrum, wenn Geräte hineinfallen **Timeout**.

-   **Timeout-Reihenfolge** : Befehl eingeben **Nachricht** zu verwenden
    wenn ein Gerät in ist **Timeout**.

-   **Fügen wenne jeder Batterie in Warnung eine Nachricht hinzu** : Fügen wenne a hinzu
    Nachricht im Nachrichtencenter, wenn ein Gerät die Stufe hat
    Batterie ein **Warnung**.

-   **Batteriebefehl in Warnung** : Befehl eingeben **Nachricht**
    zu verwenden, wenn sich das Gerät auf Batteriistand befindet **Warnung**.

-   **Fügen wenne jeder gefährdeten Batterie eine Nachricht hinzu** : Fügen wenne a hinzu
    Nachricht im Nachrichtencenter, wenn ein Gerät auf seiner Ebene von
    Batterie ein **Gefahr**.

-   **Befehl zur Batterie in Gefahr** : Befehl eingeben **Nachricht** zu
    Verwenden wenne das Gerät, wenn der Batteriistand erreicht ist **Gefahr**.

-   **Fügen wenne jeder Warnung eine Nachricht hinzu** : Fügen wenne eine Nachricht in die
    Message Center, wenn eine Bistellung in Alarmbereitschaft versetzt wird **Warnung**.

-   **Befehl zur Warnung** : Befehl eingeben **Nachricht** zu verwenden
    wenn eine Bistellung in Alarmbereitschaft geht **Warnung**.

-   **Fügen wenne jeder Gefahr eine Nachricht hinzu** : Fügen wenne eine Nachricht in die
    Message Center, wenn eine Bistellung in Alarmbereitschaft versetzt wird **Gefahr**.

-   **Befehl zur Gefahr** : Befehl eingeben **Nachricht** zu verwenden, wenn
    Eine Bistellung wird in Alarmbereitschaft versetzt **Gefahr**.

logs
----

-   **Log Engine** : Ermöglicht das Ändern der Protokoll-Engine für, z
    Senden wenne sie beispielsweise an einen Syslog-Daemon (d)..

-   **Protokollformat** : Zu verwendendes Protokollformat (Achtung : es
    wirkt sich nicht auf Daemon-Protokolle aus).

-   **Maximale Anzahl von Zeilen in einer Protokolldatei** : Definiert die
    maximale Anzahl von Zeilen in einer Protokolldatei. Es wird empfohlen
    diesen Wert nicht zu berühren, weil ein zu großer Wert könnte
    Füllen wenne das Dateisystem und / oder machen wenne Jeedom unfähig
    um das Protokoll anzuzeigen.

-   **Standardprotokollstufe** : Wenn wenne &quot;Standard&quot; auswählen,
    Für die Ebene eines Protokolls in Jeedom ist dies diejenige, die sein wird
    dann verwendet.

Nachfolgend finden wenne eine Tabelle zur Feinverwaltung
logarithmische Ebene der wesentlichen Elemente von Jeedom sowie die von
Plugins.

Befehle
=========

Viele Bistellungen können protokolliert werden. Also rein
Analyse → Verlauf erhalten wenne Diagramme, die ihre darstellen
verwenden. Auf dieser Registerkarte können wenne globale Parameter für fistlegen
Bistellhistorie.

historisch
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
    Speichern wenne den neu berechneten Wert, indem wenne den löschen
    gemittelte Werte.

-   **Niedrige Trendberechnungsschwelle** : Dieser Wert gibt die an
    Wert, von dem Jeedom anzeigt, dass der Trend in Richtung geht
    nach unten. Es muss negativ sein (Standard -0.1).

-   **Hohe Trendberechnungsschwelle** : Gleiches gilt für den Aufstieg.

-   **Standard-Grafikanzeigezeitraum** : Zeitraum, der ist
    Wird standardmäßig verwendet, wenn wenne den Verlauf anzeigen möchten
    einer Bistellung. Je kürzer der Zeitraum, disto schneller wird Jeedom
    um das angeforderte Diagramm anzuzeigen.

> **Notiz**
>
> Der erste Parameter **Widget-Statistiken anzeigen** ist
> möglich, aber standardmäßig deaktiviert, da dies die
> Anzeigezeit des Dashboards. Wenn wenne diese Option zum Beispiel aktivieren
> Standardmäßig stützt sich Jeedom auf Daten der letzten 24 Stunden bis
> Berechnen wenne diese Statistiken. Die Trendberechnungsmethode basiert
> Berechnung der kleinsten Quadrate (siehe
> [ici](https://fr.wikipedia.org/wiki/M%C3%A9thode_des_moindres_carr%C3%A9s)
> für Details).

Druck
----

**Globale Druck-URLs** : ermöglicht das Hinzufügen einer URLs zum Aufrufen im Falle von
Bistellaktualisierung. wenne können die folgenden Tags verwenden :
**\ #Value \#** für den Wert der Bistellung, **\ #Cmd \ _name \#** für die
Befehlsname, **\ #Cmd \ _id \#** für die eindeutige Kennung des
Befehl, **\ #Humanname \#** für den vollständigen Namen der Bistellung (z :
\ # \ [Badezimmer \] \ [Hydrometrie \] \ [Luftfeuchtigkeit \] \ #), `# eq_name #` für den Namen des Geräts

Zusammenfassungen
=======

Objektzusammenfassungen hinzufügen. Diese Informationen werden angezeigt
ganz oben rechts in der Jeedom-Menüleiste oder neben dem
Objekte :

-   **Schlüssel** : Schlüssel zur Zusammenfassung, vor allem nicht zu berühren.

-   **Name** : Abstrakter Name.

-   **Berechnung** : Berechnungsmethode, kann vom Typ sein :

    -   **Summe** : summiere die verschiedenen Werte,

    -   **Durchschnitt** : mittelt die Werte,

    -   **Text** : Zeigen wenne den Wert wörtlich an (insbesondere für diese
        Zeichenfolgentyp).

-   **Symbol** : Zusammenfassungssymbol.

-   **Einheit** : Zusammenfassungseinheit.

-   **Zählmethode** : Wenn wenne dann Binärdaten zählen
    wenne müssen diesen Wert auf binär setzen, Beispiel, wenn wenne die zählen
    Anzahl der Lichter an, aber wenne haben nur den Wert von
    Dimmer (0 bis 100), dann müssen wenne binär setzen, wie dieses Jeedom
    Beachten wenne, dass die Lampe die Lampe ist, wenn der Wert größer als 1 ist
    ist an.

-   **Zeigen wenne an, ob der Wert 0 ist** : Aktivieren wenne dieses Kontrollkästchen, um das anzuzeigen
    Wert, auch wenn es 0 ist.

-   **Link zu einem virtuellen** : Erstellen wenne virtuelle Bistellungen
    mit Wert die der Zusammenfassung.

-   **Zusammenfassung löschen** : Die letzte Schaltfläche ganz rechts erlaubt
    um die Zusammenfassung aus der Zeile zu löschen.

Einrichtungen
===========

-   **Anzahl der Fehler vor Deaktivierung des Geräts** : Anzahl
    Kommunikationsfehler mit dem Gerät vor Deaktivierung von
    dieses (eine Nachricht warnt wenne, wenn dies passiert).

-   **Batterieschwellen** : Ermöglicht die Verwaltung globaler Alarmschwellenwerte
    auf die Batterien.

Verhältnis
========

Konfigurieren wenne die Erstellung und Verwaltung von Berichten

-   **Zeitüberschreitung nach Seitengenerierung (in ms)** : Bekanntmachung
    Warten nach dem Laden des Berichts, um das &quot;Foto&quot; aufzunehmen, um
    Ändern wenne dies beispielsweise, wenn Ihr Bericht unvollständig ist.

-   **Bereinigen wenne ältere Berichte von (Tagen)** : Definiert die
    Anzahl der Tage vor dem Löschen eines Berichts (Berichte dauern
    ein wenig Platz, also achten wenne darauf, nicht zu viel zu setzen
    Erhaltung).

Verbindungen
=====

Linkgrafiken konfigurieren. Diese Links ermöglichen es Ihnen
siehe in Form eines Diagramms die Beziehungen zwischen Objekten,
Ausrüstung, Gegenstände usw..

-   **Tiefe für Szenarien** : Wird verwendet, um zu definieren, wann
    Anzeigen eines Diagramms der Links eines Szenarios, die Nummer
    Maximale Anzahl der anzuzeigenden Elemente (je mehr Elemente, disto größer die
    je langsamer es zu generieren ist und je schwieriger es zu lesen ist).

-   **Tiefe für Objekte** : Gleiches gilt für Objekte.

-   **Tiefe für Ausrüstung** : Gleiches gilt für die Ausrüstung.

-   **Tiefe für Kontrollen** : Gleiches gilt für Bistellungen.

-   **Tiefe für Variablen** : Gleiches gilt für Variablen.

-   **Parameter des Prerenders** : Lasst uns auf das Layout einwirken
    des Graphen.

-   **Parameter rendern** : idem.

Wechselwirkungen
============

Auf dieser Registerkarte können wenne globale Parameter für fistlegen
Interaktionen, die wenne unter Extras → Interaktionen finden.

> **Spitze**
>
> Um das Interaktionsprotokoll zu aktivieren, wechseln wenne zur Registerkarte
> Administration → Konfiguration → Protokolle, dann kreuzen wenne an **debuggen** in der Liste
> von unten. Aufmerksamkeit : Die Protokolle sind dann sehr ausführlich !

General
-------

Hier haben wenne drei Parameter :

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

-   **Antworten wenne nicht, wenn die Interaktion nicht verstanden wird** : Standard
    Jeedom antwortet &quot;Ich habe es nicht verstanden&quot;, wenn keine Interaktion erfolgt
    stimmt nicht überein. Es ist möglich, diese Funktion für zu deaktivieren
    dass Jeedom nichts beantwortet. Aktivieren wenne das Kontrollkästchen zum Deaktivieren
    die Antwort.

-   **Allgemeine Ausschluss-Regex für Interaktionen** : lass uns
    Definieren wenne einen regulären Ausdruck, der, wenn er einer Interaktion entspricht,
    löscht diesen Satz automatisch aus der Generation (reserviert)
    an Experten). Weitere Informationen finden wenne in den Erläuterungen in der
    Kapitel **Regexp-Ausschluss** Dokumentation zu
    Wechselwirkungen.

Automatische Interaktion, Kontext &amp; Warnung
-----------------------------------------------------

-   die **automatische Interaktionen** Erlaube Jeedom, es zu versuchen
    eine Interaktionsanfrage verstehen, auch wenn es keine gibt
    von definiert. Er sucht dann nach einem Objekt- und / oder Gerätenamen
    und / oder um zu versuchen, so gut wie möglich zu antworten.

-   die **kontextuelle Interaktionen** erlauben wenne zu verketten
    Zum Beispiel mehrere Anfragen, ohne alles zu wiederholen :

    -   *Jeedom hält den Kontext :*

        -   *wenne* : Wie viel ist er im Raum ?

        -   *Jeedom* : Temperatur 25.2 ° C.

        -   *wenne* : und im Wohnzimmer ?

        -   *Jeedom* : Temperatur 27.2 ° C.

    -   *Stellen wenne zwei Fragen in einer :*

        -   *wenne* : Wie ist es im Schlafzimmer und im Wohnzimmer? ?

        -   *Jeedom* : Temperatur 23.6 ° C, Temperatur 27.2 ° C.

-   Typ Interaktionen **Lassen wenne uns wissen** lass uns fragen
    Jeedom, um wenne zu benachrichtigen, wenn eine Bistellung a überschreitet / absteigt oder a wert ist
    bistimmter Wert.

    -   *wenne* : Benachrichtigen wenne mich, wenn die Wohnzimmertemperatur 25 ° C überschreitet ?

    -   *Jeedom* : OK (* Sobald die Wohnzimmertemperatur 25 ° C überschreitet,
        Jeedom wird es dir nur einmal sagen *)

> **Notiz**
>
> Standardmäßig antwortet Jeedom Ihnen auf demselben Kanal wie wenne
> pflegte ihn zu bitten, wenne zu benachrichtigen. Wenn er keinen findet
> nicht, es wird dann der hier angegebene Standardbefehl verwendet
> Tab : **Standardrückgabebefehl**.

Hier sind die verschiedenen Optionen verfügbar :

-   **Aktivieren wenne automatische Interaktionen** : Zum Aktivieren aktivieren
    automatische Interaktionen.

-   **Aktivieren wenne kontextbezogene Antworten** : Zum Aktivieren aktivieren
    kontextuelle Interaktionen.

-   **Vorrangige kontextbezogene Antwort, wenn der Satz mit beginnt** : wenn
    Der Satz beginnt mit dem Wort, das wenne hier eingeben. Jeedom wird es tun
    Priorisieren wenne dann eine kontextbezogene Antwort (wenne können setzen
    mehrere Wörter getrennt durch **;** ).

-   **Schneiden wenne eine Interaktion in zwei Hälften, wenn sie enthält** : Gleiches für
    die Aufteilung einer Interaktion mit mehreren Fragen. wenne
    Geben wenne hier die Wörter an, die die verschiedenen Fragen trennen.

-   **Aktivieren wenne die Interaktionen &quot;Benachrichtigen&quot;** : Zum Aktivieren aktivieren
    Typ Interaktionen **Lassen wenne uns wissen**.

-   **Antwort &quot;Sag es mir&quot;, wenn der Satz mit beginnt** : Wenn die
    Satz beginnt mit diesen Wörtern, dann wird Jeedom versuchen, ein zu machen
    Typ Interaktion **Lassen wenne uns wissen** (wenne können mehrere setzen
    Wörter getrennt durch **;** ).

-   **Standardrückgabebefehl** : Standardrückgabebefehl
    für eine Typinteraktion **Lassen wenne uns wissen** (verwendet insbesondere
    wenn wenne den Alarm über die mobile Schnittstelle programmiert haben)

-   **Synonym für Objekte** : Liste der Synonyme für Objekte
    (zB : Erdgeschoss | Erdgeschoss | Keller | Erdgeschoss; Bad | Bad).

-   **Synonym für Ausrüstung** : Synonymliste für
    Ausrüstung.

-   **Synonym für Bistellungen** : Synonymliste für
    Bistellungen.

-   **Synonym für Abstracts** : Liste der Synonyme für Zusammenfassungen.

-   **Synonym für maximalen Schiebereglerbefehl** : Synonym für setzen a
    Befehl für den maximalen Schiebereglertyp (zB öffnet sich, um den Verschluss zu öffnen
    der Raum ⇒ 100% Raumverschluss).

-   **Synonym für minimalen Schiebereglerbefehl** : Synonym für setzen a
    Schieberegler-Befehl mindistens (zB schließt, um den Verschluss zu schließen
    der Raum ⇒ Raumkomponente bei 0%).

wenncherheit
========

LDAP
----

-   **Aktivieren wenne die LDAP-Authentifizierung** : Aktivieren wenne die Authentifizierung für
    durch eine AD (LDAP)

-   **Gastgeber** : Server, der die AD hostet

-   **Domain** : Domain Ihrer AD

-   **Basis-DN** : DN Basis Ihrer AD

-   **Benutzername** : Benutzername für Jeedom zu
    Verbindung zu AD herstellen

-   **Passwort** : Passwort für Jeedom, um eine Verbindung zu AD herzustellen

-   **Benutzersuchfelder** : Suchfelder von
    Benutzer Login. Normalerweise uid für LDAP, SamAccountName für
    Windows AD

-   **Filter (optional)** : Filter auf dem AD (zum Verwalten
    Gruppen zum Beispiel)

-   **REMOTE \ _USER zulassen** : Aktivieren wenne REMOTE \ _USER (wird in SSO verwendet
    zum Beispiel)

einloggen
---------

-   **Anzahl der tolerierten Fehler** : Legt die Anzahl der Versuche fist
    erlaubt vor dem Verbot der IP

-   **Maximale Zeit zwischen Ausfällen (in Sekunden)** : maximale Zeit
    so dass 2 Versuche als aufeinanderfolgend betrachtet werden

-   **Verbannungsdauer (in Sekunden), -1 für unendlich** : Zeit von
    IP-Verbot

-   **&quot;Weiße&quot; IP** : Liste der IPs, die niemals gesperrt werden können

-   **Entfernen wenne gesperrte IPs** : Löschen wenne die Liste der IPs
    derzeit verboten

Die Liste der gesperrten IPs befindet sich am Ende dieser Seite. wenne finden dort
IP, Sperrdatum und Sperrenddatum
geplant.

Update und Dateien
=======================

Jeedom Update
---------------------

-   **Quelle aktualisieren** : Wählen wenne die Quelle für die Aktualisierung der
    Jeedom Kern.

-   **Kernversion** : Kernversion zum Wiederherstellen.

-   **Automatisch nach Updates suchen** : Geben wenne an, ob
    wenne müssen automatisch suchen, wenn es neue Updates gibt
    (Achten wenne darauf, eine Überlastung des Marktes zu vermeiden
    Überprüfung kann sich ändern).

Einlagen
----------

Die Depots sind Speicher- (und Service-) Räume, um in der Lage zu sein
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
um dieses Repository zu verwenden. Aufmerksamkeit : Jede Supportanfrage kann sein
abgelehnt, wenn wenne eine andere Einzahlung als diese verwenden.

-   **Adresse** : Marktadresse (Https://www.jeedom.com/market)

-   **Benutzername** : Ihr Benutzername auf dem Markt.

-   **Passwort** : Ihr Marktpasswort.

-   **[Backup cloud] Name** : Name Ihres Cloud-Backups (die Aufmerksamkeit muss für jedes Jeedom eindeutig sein, bei dem die Gefahr eines Absturzes bisteht)

-   **[Backup cloud] Passwort** : Cloud-Backup-Passwort. WICHTIG wenne dürfen es nicht verlieren, es gibt keine Möglichkeit, es wiederherzustellen. Ohne sie können wenne Ihre Freiheit nicht mehr wiederherstellen.

-   **[Backup cloud] Fréquence backup full** : Häufigkeit der vollständigen Cloud-wenncherung. Eine vollständige wenncherung ist länger als eine inkrementelle (die nur die Unterschiede sendet).. Es wird empfohlen, 1 pro Monat zu tun

### Samba

Zahlen wenne ein, um automatisch ein Jeedom-Backup an zu senden
eine Samba-Aktie (z : NAS-Synologie).

-   **\ [Backup \] IP** : Samba Server IP.

-   **\ [Backup \] Benutzer** : Benutzername für die Anmeldung
    (anonyme Verbindungen sind nicht möglich). Es muss geben
    dass der Benutzer diee- und Schreibrechte an der hat
    Zielverzeichnis.

-   **\ [Backup \] Passwort** : Benutzerpasswort.

-   **\ [Backup \] Freigabe** : Art des Teilens (sei vorsichtig
    auf der Freigabeebene anhalten).

-   **\ [Backup \] Pfad** : Pfad beim Teilen (fistlegen
    relativ) muss es existieren.

> **Notiz**
>
> Wenn der Pfad zu Ihrem Samba-wenncherungsordner lautet :
> \\\\ 192.168.0.1 \\ Backups \\ Hausautomation \\ Jeedom Dann IP = 192.168.0.1
> , Sharing = //192.168.0.1 / Backups, Path = Home Automation / Jeedom

> **Notiz**
>
> Bei der Validierung der Samba-Freigabe, wie oben beschrieben,
> In diesem Abschnitt wird eine neue Form der wenncherung angezeigt
> Administration → Jeedom-Backups. Durch Aktivieren wird Jeedom fortfahren
> wenn es beim nächsten Backup automatisch gesendet wird. Ein Tist ist
> möglich durch manuelle wenncherung.

> **wichtig**
>
> Möglicherweise müssen wenne das smbclient-Paket für das installieren
> Einzahlung funktioniert.

> **wichtig**
>
> Das Samba-Protokoll hat mehrere Versionen, die v1 ist kompromittiert
> wenncherheit und auf einigen NAS können wenne den Client zur Verwendung von v2 zwingen
> oder v3 zum Verbinden. Wenn wenne also einen Protokollverhandlungsfehler haben
> fehlgeschlagen: NT_STATUS_INVAID_NETWORK_RESPONSE Es bisteht eine gute Chance, dass NAS aufgelistet wird
> die Einschränkung bisteht. wenne müssen dann das Betriebssystem Ihres Jeedom ändern
> die Datei / etc / samba / smb.conf und füge diese beiden Zeilen hinzu :
> Client-Max-Protokoll = SMB3
> Client-Min-Protokoll = SMB2
> Der Jeedom-Seite smbclient verwendet dann v2, wobei v3 und nur SMB3 in beiden
> SMB3. Es liegt also an Ihnen, sich an die Einschränkungen des NAS oder eines anderen Samba-Servers anzupassen

> **wichtig**
>
> Jeedom sollte der einzige sein, der in diesen Ordner schreibt, und er sollte leer sein
> standardmäßig (d. h. vor dem Konfigurieren und Senden des
> Bei der ersten wenncherung darf der Ordner keine Datei oder enthalten
> Ordner).

### URLs

-   **Jeedom-Kern-URLs**

-   **URLs der Jeedom-Kernversion**

Abdeckung
=====

Ermöglicht die Überwachung und Bearbeitung des Jeedom-Abdeckung :

-   **Statistiken** : Anzahl der aktuell zwischengespeicherten Objekte

-   **Reinigen wenne die Abdeckung** : Erzwingen wenne das Löschen von Objekten, die dies nicht sind
    nützlicher. Jeedom macht das automatisch jede Nacht.

-   **Löschen wenne alle zwischengespeicherten Daten** : Leeren wenne den Deckel vollständig.
    Bitte beachten wenne, dass dies zu Datenverlust führen kann !

-   **Leeren wenne den Widget-Abdeckung** : Leeren wenne den Abdeckung für Widgets

-   **Deaktivieren wenne den Widget-Abdeckung** : Aktivieren wenne das Kontrollkästchen zum Deaktivieren
    Das Widget deckt ab

-   **Pause für lange Abfragen** : Wie oft
    Jeedom prüft, ob Ereignisse für Kunden ausstehen
    (Weboberfläche, mobile Anwendung usw.). Je kürzer diesmal, disto mehr
    Die Schnittstelle wird im Gegenzug schnell aktualisiert
    verbraucht mehr Ressourcen und kann daher Jeedom verlangsamen.

API
===

Hier ist die Liste der verschiedenen API-Schlüssel, die in verfügbar sind
deine Freiheit. Core verfügt über zwei API-Schlüssel :

-   ein General : Vermeiden wenne es so weit wie möglich.

-   und eine andere für Profis : für das Management verwendet
    des Parks. Es kann leer sein.

-   Dann finden wenne einen API-Schlüssel pro Plugin, der ihn benötigt.

Für jeden Plugin-API-Schlüssel sowie für HTTP, JsonRPC und APIs
TTS können wenne deren Umfang definieren :

-   **untauglich** : Der API-Schlüssel kann nicht verwendet werden.

-   **Weiße IP** : Es ist nur eine Liste von IPs autorisiert (siehe
    Administration → Einstellungen → Netzwerke)

-   **localhost** : nur Anfragen vom System, auf dem sich befindet
    installierte Jeedom sind erlaubt,

-   **aktiviert** : Keine Einschränkungen, jedes System mit Zugriff
    Ihr Jeedom kann auf diese API zugreifen.

&gt;\ _OS / DB
===========

Auf dieser Registerkarte befinden sich zwei Teile, die Experten vorbehalten sind.

> **wichtig**
>
> VORSICHT : Wenn wenne Jeedom mit einer dieser beiden Lösungen ändern,
> Der Support kann sich weigern, Ihnen zu helfen.

-   **&gt;\ _SYSTEM** : Ermöglicht den Zugriff auf eine Schnittstelle
    Systemadministration. Es ist eine Art Shell-Konsole in
    Hier können wenne die nützlichsten Befehle ausführen, einschließlich
    um Informationen über das System zu erhalten.

-   **Datei-Editor** : Ermöglicht den Zugriff auf verschiedene Systemdateien
    und bearbeiten oder löschen oder erstellen wenne sie.

-   **Datenbank** : Administration / Start : Ermöglicht den Zugriff auf die Datenbank
    von Jeedom. wenne können dann Befehle in das Feld starten
    von oben.
    Überprüfen / Starten : Ermöglicht das Starten einer Überprüfung in der Datenbank
    Jeedom und korrigieren wenne gegebenenfalls Fehler

    Zur Information werden unten zwei Parameter angezeigt :

    -   **Benutzer** : Benutzername von Jeedom in
        die Datenbank,

    -   **Passwort** : Datenbankzugriffskennwort
        von Jeedom verwendet.
