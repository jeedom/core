Hier befinden sich die meisten Konfigurationsparameter.
Obwohl viele, sind sie standardmäßig vorkonfiguriert.

Die Seite ist zugänglich über **Administration → Konfiguration**.

General 
=======

Auf dieser Registerkarte finden Sie allgemeine Informationen zu Jeedom :

-   **Name deines Jeedom** : Identifizieren Sie Ihre Freiheit,
    vor allem auf dem Markt. Es kann in Szenarien wiederverwendet werden
    oder identifizieren Sie ein Backup.

-   **System** : Art der Hardware, auf der das System wo installiert ist
    Dein Jeedom dreht sich.

-   **Installationsschlüssel** : Hardware-Schlüssel Ihres Jeedom auf
    der Markt. Wenn Ihr Jeedom nicht in der Liste von Ihnen erscheint
    Jeedom auf dem Markt ist es ratsam, auf die Schaltfläche zu klicken
    **Zurücksetzen**.

-   **Sprache** : Sprache, die in Ihrem Jeedom verwendet wird.

-   **Übersetzungen generieren** : Übersetzungen generieren,
    Seien Sie vorsichtig, dies kann Ihr System verlangsamen. Die nützlichste Option
    für Entwickler.

-   **Lebensdauer der Sitzungen (Stunde)** : Lebensdauer der Sitzungen
    PHP, es wird nicht empfohlen, diesen Parameter zu berühren.

-   **Datum und Uhrzeit** : Wählen Sie Ihre Zeitzone. Du kannst
    Klicken Sie auf **Zeitsynchronisation erzwingen** wiederherstellen
    Oben rechts wird eine schlechte Zeit angezeigt.

-   **Optionaler Zeitserver** : Gibt an, welcher Zeitserver verwendet werden soll
    verwendet werden, wenn Sie klicken **Synchronisation erzwingen von
    Zeit**. (Experten vorbehalten sein)

-   **Zeitprüfung überspringen** : sagt Jeedom, dass er es nicht tun soll
    Überprüfen Sie, ob die Zeit zwischen sich und dem eingeschalteten System konsistent ist
    was es dreht. Kann beispielsweise nützlich sein, wenn Sie keine Verbindung herstellen
    Kein Jeedom zum Internet und dass es keine PSTN-Batterie auf dem hat
    verwendetes Material.

API 
===

Hier ist die Liste der verschiedenen API-Schlüssel, die in verfügbar sind
deine Freiheit. Core verfügt über zwei API-Schlüssel :

-   ein General : Vermeiden Sie es so oft wie möglich,

-   und eine andere für Profis : für das Management verwendet
    des Parks. Es kann leer sein.

-   Dann finden Sie einen API-Schlüssel pro Plugin, der ihn benötigt.

Für jeden Plugin-API-Schlüssel sowie für HTTP, JsonRPC und APIs
TTS können Sie deren Umfang definieren :

-   **Untauglich** : API-Schlüssel kann nicht verwendet werden,

-   **Weiße IP** : Es ist nur eine Liste von IPs autorisiert (siehe
    Administration → Konfiguration → Netzwerke),

-   **Localhost** : nur Anfragen vom System, auf dem sich befindet
    installierte Jeedom sind erlaubt,

-   **Aktiviert** : Keine Einschränkungen, jedes System mit Zugriff
    Ihr Jeedom kann auf diese API zugreifen.

&gt;;\_OS / DB 
===========

Auf dieser Registerkarte befinden sich zwei Teile, die Experten vorbehalten sind.

> **Wichtig**
>
> VORSICHT : Wenn Sie Jeedom mit einer dieser beiden Lösungen ändern,
> Der Support kann sich weigern, Ihnen zu helfen.

-   **&gt;;\_SYSTEM** : Ermöglicht den Zugriff auf eine Schnittstelle
    Systemadministration. Es ist eine Art Shell-Konsole in
    Hier können Sie die nützlichsten Befehle ausführen, einschließlich
    um Informationen über das System zu erhalten.

-   **Datenbank** : Ermöglicht den Zugriff auf die Datenbank
    von Jeedom. Sie können dann Befehle in das Feld starten
    von oben. Zur Information werden unten zwei Parameter angezeigt :

    -   **Benutzer** : Benutzername von Jeedom in
        die Datenbank,

    -   **Passwort** : Datenbankzugriffskennwort
        von Jeedom verwendet.

Sicherheit 
========

LDAP 
----

-   **Aktivieren Sie die LDAP-Authentifizierung** : Aktivieren Sie die Authentifizierung für
    über ein AD (LDAP)

-   **Gastgeber** : Server, der die AD hostet

-   **Domain** : Domain Ihrer AD

-   **Basis-DN** : DN Basis Ihrer AD

-   **Benutzername** : Benutzername für Jeedom zu
    Verbindung zu AD herstellen

-   **Passwort** : Passwort für Jeedom, um eine Verbindung zu AD herzustellen

-   **Benutzersuchfelder** : Suchfelder von
    Benutzer Login. Normalerweise uid für LDAP, samaccountname für
    Windows AD

-   **Filter (optional)** : Filter auf dem AD (zum Verwalten
    Gruppen zum Beispiel)

-   **REMOTE\_USER zulassen** : Aktivieren Sie REMOTE\_USER (wird in SSO verwendet
    Zum Beispiel)

Einloggen 
---------

-   **Anzahl der tolerierten Fehler** : Legt die Anzahl der Versuche fest
    erlaubt vor dem Verbot der IP

-   **Maximale Zeit zwischen Ausfällen (in Sekunden)** : maximale Zeit
    so dass 2 Versuche als aufeinanderfolgend betrachtet werden

-   **Verbannungsdauer (in Sekunden), -1 für unendlich** : Zeit von
    IP-Verbot

-   **IP "weiß"** : Liste der IPs, die niemals gesperrt werden können

-   **Entfernen Sie gesperrte IPs** : Löschen Sie die Liste der IPs
    derzeit verboten

Die Liste der gesperrten IPs befindet sich am Ende dieser Seite. Sie finden dort
IP, Sperrdatum und Sperrenddatum
geplant.

Netzwerke 
=======

Es ist unbedingt erforderlich, diesen wichtigen Teil von korrekt zu konfigurieren
Jeedom, sonst funktionieren viele Plugins möglicherweise nicht. es
Es ist möglich, auf zwei verschiedene Arten auf Jeedom zuzugreifen : L'**Zugang
interne** (aus dem gleichen lokalen Netzwerk wie Jeedom) und l'**Zugang
externe** (aus einem anderen Netzwerk, insbesondere aus dem Internet).

> **Wichtig**
>
> Dieser Teil ist nur dazu da, Jeedom seine Umgebung zu erklären :
> Durch Ändern des Ports oder der IP auf dieser Registerkarte wird das nicht geändert
> Jeedom Port oder IP eigentlich. Dazu müssen Sie sich anmelden
> SSH und bearbeiten Sie die Datei / etc / network / interfaces für die IP und
> etc / apache2 / sites-available / default files und
> etc / apache2 / sites-available / default\_ssl (für HTTPS).In
> Wenn Ihr Jeedom falsch behandelt wird, wird das Jeedom-Team dies nicht tun
> kann zur Verantwortung gezogen werden und kann jede Anfrage für ablehnen
> Unterstützung.

-   **Interner Zugang** : Informationen für den Beitritt zu Jeedom von a
    gleiche Netzwerkausrüstung wie Jeedom (LAN)

    -   **OK / NOK** : Gibt an, ob die interne Netzwerkkonfiguration ist
        correcte

    -   **Protokoll** : das zu verwendende Protokoll, oft HTTP

    -   **URL oder IP-Adresse** : Jeedom IP eingeben

    -   **Hafen** : der Port der Jeedom-Weboberfläche, in der Regel 80.
        Bitte beachten Sie, dass durch Ändern des Ports hier nicht der tatsächliche Port von geändert wird
        Freiheit, die gleich bleiben wird

    -   **Ergänzen** : das Fragment einer zusätzlichen URL (Beispiel
        : / jeedom), um auf Jeedom zuzugreifen.

-   **Externer Zugang** : Informationen, um Jeedom von außen zu erreichen
    lokales Netzwerk. Nur auszufüllen, wenn Sie kein DNS verwenden
    Jeedom

    -   **OK / NOK** : Gibt an, ob die externe Netzwerkkonfiguration ist
        correcte

    -   **Protokoll** : Protokoll für den Zugang im Freien

    -   **URL oder IP-Adresse** : Externe IP, wenn es fest ist. andernfalls,
        Geben Sie die URL an, die auf die externe IP-Adresse Ihres Netzwerks verweist.

    -   **Ergänzen** : das Fragment einer zusätzlichen URL (Beispiel
        : / jeedom), um auf Jeedom zuzugreifen.

> **Spitze**
>
> Wenn Sie sich in HTTPS befinden, ist der Port 443 (standardmäßig) und in HTTP der
> Port ist 80 (Standard). HTTPS von außen verwenden,
> Ein Letsencrypt-Plugin ist jetzt auf dem Markt erhältlich.

> **Spitze**
>
> Um herauszufinden, ob Sie einen Wert im Feld festlegen müssen
> **Ergänzen**, Schau, wenn du dich bei Jeedom anmeldest
> Ihren Internetbrowser, wenn Sie / jeedom (oder andere) hinzufügen müssen
> Sache) nach der IP.

-   **Erweiterte Verwaltung** : Dieser Teil erscheint möglicherweise nicht in
    abhängig von der Kompatibilität mit Ihrer Hardware. Sie finden dort
    die Liste Ihrer Netzwerkschnittstellen. Sie können Jeedom sagen
    das Netzwerk nicht durch Klicken auf zu überwachen **Deaktivieren Sie die
    Netzwerkmanagement von Jeedom** (Überprüfen Sie, ob Jeedom nicht angeschlossen ist
    kein Netzwerk). Sie können den lokalen IP-Bereich auch im Formular 192.168.1 angeben.* (Nur in Docker-Installationen zu verwenden)

-   **Proxy-Markt** : ermöglicht den Fernzugriff auf Ihr Jeedom ohne zu haben
    benötigen ein DNS, eine feste IP oder um die Ports Ihrer Box zu öffnen
    Internet

    -   **Verwenden von Jeedom DNS** : aktiviert Jeedom DNS (Aufmerksamkeit
        Dies erfordert mindestens ein Service Pack)

    -   **DNS-Status** : DNS-HTTP-Status

    -   **Management** : Ermöglicht das Stoppen und Neustarten des DNS-Dienstes

> **Wichtig**
>
> Wenn Sie Jeedom DNS nicht zum Laufen bringen können, überprüfen Sie die
> Konfiguration der Firewall und des Kindersicherungsfilters Ihrer Internetbox
> (Auf Livebox benötigen Sie beispielsweise die Firewall in Medium).

Farben 
========

Die Kolorierung von Widgets erfolgt entsprechend der Kategorie bis
welche Ausrüstung gehört. Unter den Kategorien finden wir die
Heizung, Sicherheit, Energie, Licht, Automatisierung, Multimedia, Andere…

Für jede Kategorie können wir die Farben der Version unterscheiden
Desktop- und Mobile-Version. Wir können uns dann ändern :

-   die Hintergrundfarbe der Widgets,

-   Die Farbe des Befehls, wenn das Widget vom allmählichen Typ ist (z
    Beispiel Lichter, Fensterläden, Temperaturen).

Durch Klicken auf die Farbe wird ein Fenster geöffnet, in dem Sie Ihre auswählen können
Farbe. Das Kreuz neben der Farbe kehrt zum Parameter zurück
Standard.

Oben auf der Seite können Sie auch die Transparenz von konfigurieren
Widgets global (dies ist die Standardeinstellung. Er ist
dann möglich, diesen Wert Widget für Widget zu ändern). Um nicht
keine Transparenz setzen, 1 lassen.0 .

> **Spitze**
>
> Vergessen Sie nicht, nach jeder Änderung zu speichern.

Befehle 
=========

Viele Bestellungen können protokolliert werden. Also rein
Analyse → Verlauf erhalten Sie Diagramme, die ihre darstellen
verwenden. Auf dieser Registerkarte können Sie globale Parameter für festlegen
Bestellhistorie.

Historisch 
----------

-   **Widget-Statistiken anzeigen** : Anzeigen
    Widget-Statistiken. Das Widget muss sein
    kompatibel, was bei den meisten der Fall ist. Es ist auch notwendig, dass die
    Befehl entweder digital.

-   **Berechnungszeitraum für min, max, Durchschnitt (in Stunden)** : Zeit
    Statistikberechnung (standardmäßig 24 Stunden)). Es ist nicht möglich
    weniger als eine Stunde setzen.

-   **Berechnungszeitraum für den Trend (in Stunden)** : Zeitraum von
    Trendberechnung (standardmäßig 2h). Es ist nicht möglich
    weniger als eine Stunde setzen.

-   **Verzögerung vor der Archivierung (in Stunden)** : Zeigt die Verzögerung vor an
    Jeedom archiviert keine Daten (standardmäßig 24 Stunden)). Das heißt, die
    Historische Daten müssen länger als 24 Stunden archiviert werden
    (Zur Erinnerung: Die Archivierung wird entweder durchschnittlich oder maximal sein
    oder das Minimum der Daten über einen Zeitraum, der dem entspricht
    Paketgröße).

-   **Archiv nach Paket ab (in Stunden)** : Dieser Parameter gibt
    genau die Größe der Pakete (standardmäßig 1 Stunde). Es bedeutet durch
    Beispiel, dass Jeedom Perioden von 1 Stunde dauern wird, durchschnittlich und
    Speichern Sie den neu berechneten Wert, indem Sie den löschen
    gemittelte Werte.

-   **Niedrige Trendberechnungsschwelle** : Dieser Wert gibt die an
    Wert, von dem Jeedom anzeigt, dass der Trend in Richtung geht
    nach unten. Es muss negativ sein (Standard -0.1).

-   **Hohe Trendberechnungsschwelle** : Gleiches gilt für den Aufstieg.

-   **Standard-Grafikanzeigezeitraum** : Zeitraum, der ist
    Wird standardmäßig verwendet, wenn Sie den Verlauf anzeigen möchten
    einer Bestellung. Je kürzer der Zeitraum, desto schneller wird Jeedom
    um das angeforderte Diagramm anzuzeigen.

> **Notiz**
>
> Der erste Parameter **Widget-Statistiken anzeigen** est
> möglich, aber standardmäßig deaktiviert, da dies die
> Anzeigezeit des Dashboards. Wenn Sie diese Option zum Beispiel aktivieren
> Standardmäßig stützt sich Jeedom auf Daten der letzten 24 Stunden bis
> Berechnen Sie diese Statistiken. Die Trendberechnungsmethode basiert
> Berechnung der kleinsten Quadrate (siehe
> [hier](https://fr.wikipedia.org/wiki/M%C3%A9thode_des_moindres_carr%C3%A9s)
> für das Detail).

Druck 
----

**Globale Push-URL** : ermöglicht das Hinzufügen einer URL zum Aufrufen im Falle von
Bestellaktualisierung. Sie können die folgenden Tags verwenden :
**\.#value\.#** für den Bestellwert, **\.#cmd\._name\.#** für die
Befehlsname, **\.#cmd\._id\.#** für die eindeutige Kennung des
commande, **\.#humanname\.#** für den vollständigen Namen der Bestellung (z :
\.#\.[Salle de bain\.]\.[Hydrometrie\.]\.[Humidité\.]\.#), ``#eq_name#`für den Namen des Geräts

Abdeckung 
=====

Ermöglicht die Überwachung und Bearbeitung des Jeedom-Cache :

-   **Statistiken** : Anzahl der aktuell zwischengespeicherten Objekte

-   **Reinigen Sie die Abdeckung** : Erzwingen Sie das Löschen von Objekten, die dies nicht sind
    nützlicher. Jeedom macht das automatisch jede Nacht.

-   **Löschen Sie alle zwischengespeicherten Daten** : Leeren Sie den Deckel vollständig.
    Bitte beachten Sie, dass dies zu Datenverlust führen kann !

-   **Pause für lange Abfragen** : Wie oft
    Jeedom prüft, ob Ereignisse für Kunden ausstehen
    (Webinterface, mobile Anwendung…). Je kürzer diesmal, desto mehr
    Die Schnittstelle wird im Gegenzug schnell aktualisiert
    verbraucht mehr Ressourcen und kann daher Jeedom verlangsamen.

Wechselwirkungen 
============

Auf dieser Registerkarte können Sie globale Parameter für festlegen
Interaktionen, die Sie unter Extras → Interaktionen finden.

> **Spitze**
>
> Um das Interaktionsprotokoll zu aktivieren, wechseln Sie zur Registerkarte
> Administration → Konfiguration → Protokolle, dann kreuzen Sie an **Debuggen** in der Liste
> von unten. VORSICHT : Die Protokolle sind dann sehr ausführlich !

General 
-------

Hier haben Sie drei Parameter :

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

-   **Antworten Sie nicht, wenn die Interaktion nicht verstanden wird** : Standard
    Jeedom antwortet &quot;Ich habe es nicht verstanden&quot;, wenn keine Interaktion erfolgt
    stimmt nicht überein. Es ist möglich, diese Funktion für zu deaktivieren
    dass Jeedom nichts beantwortet. Aktivieren Sie das Kontrollkästchen zum Deaktivieren
    die Antwort.

-   **Allgemeine Ausschluss-Regex für Interaktionen** : lass uns
    Definieren Sie einen regulären Ausdruck, der, wenn er einer Interaktion entspricht,
    löscht diesen Satz automatisch aus der Generation (reserviert)
    an Experten). Weitere Informationen finden Sie in den Erläuterungen in der
    Kapitel **Regexp-Ausschluss** Dokumentation zu
    Wechselwirkungen.

Automatische Interaktion, Kontext &amp; Warnung 
-----------------------------------------------------

-   Die **automatische Interaktionen** Erlaube Jeedom, es zu versuchen
    eine Interaktionsanfrage verstehen, auch wenn es keine gibt
    von definiert. Er sucht dann nach einem Objekt- und / oder Gerätenamen
    und / oder um zu versuchen, so gut wie möglich zu antworten.

-   Die **kontextuelle Interaktionen** erlauben Sie zu verketten
    Zum Beispiel mehrere Anfragen, ohne alles zu wiederholen :

    -   *Jeedom hält den Kontext :*

        -   *Sie* : Wie viel ist er im Raum ?

        -   *Jeedom* : Temperatur 25.2 ° C.

        -   *Sie* : und im Wohnzimmer ?

        -   *Jeedom* : Temperatur 27.2 ° C.

    -   *Stellen Sie zwei Fragen in einer :*

        -   *Sie* : Wie ist es im Schlafzimmer und im Wohnzimmer? ?

        -   *Jeedom* : Temperatur 23.6 ° C, Temperatur 27.2 ° C.

-   Typ Interaktionen **Lassen Sie uns wissen** lass uns fragen
    Jeedom, um Sie zu benachrichtigen, wenn eine Bestellung a überschreitet / absteigt oder a wert ist
    bestimmter Wert.

    -   *Sie* : Benachrichtigen Sie mich, wenn die Wohnzimmertemperatur 25 ° C überschreitet ?

    -   *Jeedom* : OK (* Sobald die Wohnzimmertemperatur 25 ° C überschreitet,
        Jeedom wird es dir nur einmal sagen*)

> **Notiz**
>
> Standardmäßig antwortet Jeedom Ihnen auf demselben Kanal wie Sie
> pflegte ihn zu bitten, Sie zu benachrichtigen. Wenn er keinen findet
> nicht, es wird dann der hier angegebene Standardbefehl verwendet
> Tab : **Standardrückgabebefehl**.

Hier sind die verschiedenen Optionen verfügbar :

-   **Aktivieren Sie automatische Interaktionen** : Zum Aktivieren aktivieren
    automatische Interaktionen.

-   **Aktivieren Sie kontextbezogene Antworten** : Zum Aktivieren aktivieren
    kontextuelle Interaktionen.

-   **Vorrangige kontextbezogene Antwort, wenn der Satz mit beginnt** : Si
    Der Satz beginnt mit dem Wort, das Sie hier eingeben. Jeedom wird es tun
    Priorisieren Sie dann eine kontextbezogene Antwort (Sie können setzen
    mehrere Wörter getrennt durch **;;** ).

-   **Schneiden Sie eine Interaktion in zwei Hälften, wenn sie enthält** : Gleiches für
    die Aufteilung einer Interaktion mit mehreren Fragen. Sie
    Geben Sie hier die Wörter an, die die verschiedenen Fragen trennen.

-   **Aktivieren Sie die Interaktionen "Benachrichtigen""** : Zum Aktivieren aktivieren
    Typ Interaktionen **Lassen Sie uns wissen**.

-   **Antwort &quot;Sag es mir&quot;, wenn der Satz mit beginnt** : Wenn die
    Satz beginnt mit diesen Wörtern, dann wird Jeedom versuchen, ein zu machen
    Typ Interaktion **Lassen Sie uns wissen** (Sie können mehrere setzen
    Wörter getrennt durch **;;** ).

-   **Standardrückgabebefehl** : Standardrückgabebefehl
    für eine Typinteraktion **Lassen Sie uns wissen** (insbesondere verwendet,
    wenn Sie den Alarm über die mobile Schnittstelle programmiert haben)

-   **Synonym für Objekte** : Liste der Synonyme für Objekte
    (ex : rdc|Erdgeschoss|unterirdisch|niedrig; sdb|Badezimmer).

-   **Synonym für Ausrüstung** : Synonymliste für
    Ausrüstung.

-   **Synonym für Bestellungen** : Synonymliste für
    Bestellungen.

-   **Synonym für Abstracts** : Liste der Synonyme für Zusammenfassungen.

-   **Synonym für maximalen Schiebereglerbefehl** : Synonym für setzen a
    Befehl für den maximalen Schiebereglertyp (ex öffnet sich, um den Verschluss zu öffnen
    der Raum ⇒ 100% Raumverschluss).

-   **Synonym für minimalen Schiebereglerbefehl** : Synonym für setzen a
    Schieberegler-Befehl mindestens (ex schließt, um den Verschluss zu schließen
    der Raum ⇒ Raumkomponente bei 0%).

Farben 
--------

In diesem Teil können Sie die Farben definieren, mit denen Jeedom assoziiert wird
Wörter rot / blau / schwarz… Um eine Farbe hinzuzufügen :

-   Klicken Sie auf die Schaltfläche **+**, richtig,

-   Gib deiner Farbe einen Namen,

-   Wählen Sie die zugehörige Farbe aus, indem Sie auf das Feld rechts klicken.

Verhältnis 
========

Konfigurieren Sie die Erstellung und Verwaltung von Berichten

-   **Zeitüberschreitung nach Seitengenerierung (in ms)** : Bekanntmachung
    Warten nach dem Laden des Berichts, um das &quot;Foto&quot; aufzunehmen, um
    Ändern Sie dies beispielsweise, wenn Ihr Bericht unvollständig ist.

-   **Bereinigen Sie ältere Berichte von (Tagen)** : Definiert die
    Anzahl der Tage vor dem Löschen eines Berichts (Berichte dauern
    ein wenig Platz, also achten Sie darauf, nicht zu viel zu setzen
    Erhaltung).

Verbindungen 
=====

Linkgrafiken konfigurieren. Diese Links ermöglichen es Ihnen
siehe in Form eines Diagramms die Beziehungen zwischen Objekten,
Ausrüstung, Gegenstände usw.

-   **Tiefe für Szenarien** : Wird verwendet, um zu definieren, wann
    Anzeigen eines Diagramms der Links eines Szenarios, die Nummer
    Maximale Anzahl der anzuzeigenden Elemente (je mehr Elemente, desto größer die
    Die Erstellung von Grafiken ist langsam und das Lesen umso schwieriger).

-   **Tiefe für Objekte** : Gleiches gilt für Objekte.

-   **Tiefe für Ausrüstung** : Gleiches gilt für die Ausrüstung.

-   **Tiefe für Kontrollen** : Gleiches gilt für Bestellungen.

-   **Tiefe für Variablen** : Gleiches gilt für Variablen.

-   **Parameter des Prerenders** : Lasst uns auf das Layout einwirken
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

    -   **Text** : Zeigen Sie den Wert wörtlich an (insbesondere für diese
        vom String-Typ).

-   **Symbol** : Zusammenfassungssymbol.

-   **Einheit** : Zusammenfassungseinheit.

-   **Zählmethode** : Wenn Sie dann Binärdaten zählen
    Sie müssen diesen Wert auf binär setzen, Beispiel, wenn Sie die zählen
    Anzahl der Lichter an, aber Sie haben nur den Wert von
    Dimmer (0 bis 100), dann müssen Sie binär setzen, wie dieses Jeedom
    Beachten Sie, dass die Lampe die Lampe ist, wenn der Wert größer als 1 ist
    ist an.

-   **Zeigen Sie an, ob der Wert 0 ist** : Aktivieren Sie dieses Kontrollkästchen, um das anzuzeigen
    Wert, auch wenn es 0 ist.

-   **Link zu einem virtuellen** : Erstellen Sie virtuelle Bestellungen
    mit Wert die der Zusammenfassung.

-   **Zusammenfassung löschen** : Die letzte Schaltfläche ganz rechts erlaubt
    um die Zusammenfassung aus der Zeile zu löschen.

Logs 
====

Zeitleiste 
--------

-   **Maximale Anzahl von Ereignissen** : Legt die maximale Anzahl auf fest
    in der Zeitleiste anzeigen.

-   **Löschen Sie alle Ereignisse** : Leeren Sie die Zeitleiste von
    alle seine aufgezeichneten Ereignisse.

Beiträge 
--------

-   **Fügen Sie jedem Fehler in den Protokollen eine Nachricht hinzu** : wenn ein Plugin
    oder Jeedom schreibt eine Fehlermeldung in ein Protokoll, fügt Jeedom hinzu
    automatisch eine Nachricht im Message Center (zumindest
    Sie werden es sicher nicht verpassen).

-   **Aktion auf Nachricht** : Ermöglicht es Ihnen, eine Aktion auszuführen, wenn Sie dem Nachrichtenzentrum eine Nachricht hinzufügen. Sie haben 2 Tags für diese Aktionen : 
        - #message# : Nachricht in Frage
        - #plugin# : Plugin, das die Nachricht ausgelöst hat

Benachrichtigungen 
-------

-   **Fügen Sie jedem Timeout eine Nachricht hinzu** : Fügen Sie eine Nachricht in die
    Nachrichtenzentrum, wenn Geräte hineinfallen **Timeout**.

-   **Timeout-Reihenfolge** : Befehl eingeben **Nachricht** zu verwenden
    wenn ein Gerät in ist **Timeout**.

-   **Fügen Sie jeder Batterie in Warnung eine Nachricht hinzu** : Fügen Sie a hinzu
    Nachricht im Nachrichtencenter, wenn ein Gerät die Stufe hat
    Batterie ein **Warnung**.

-   **Batteriebefehl in Warnung** : Befehl eingeben **Nachricht**
    zu verwenden, wenn sich das Gerät auf Batteriestand befindet **Warnung**.

-   **Fügen Sie jeder gefährdeten Batterie eine Nachricht hinzu** : Fügen Sie a hinzu
    Nachricht im Nachrichtencenter, wenn ein Gerät die Stufe hat
    Batterie ein **Gefahr**.

-   **Befehl zur Batterie in Gefahr** : Befehl eingeben **Nachricht** zu
    Verwenden Sie diese Option, wenn sich das Gerät auf Batteriestand befindet **Gefahr**.

-   **Fügen Sie jeder Warnung eine Nachricht hinzu** : Fügen Sie eine Nachricht in die
    Message Center, wenn eine Bestellung in Alarmbereitschaft versetzt wird **Warnung**.

-   **Befehl zur Warnung** : Befehl eingeben **Nachricht** zu verwenden
    wenn eine Bestellung in Alarmbereitschaft geht **Warnung**.

-   **Fügen Sie jeder Gefahr eine Nachricht hinzu** : Fügen Sie eine Nachricht in die
    Message Center, wenn eine Bestellung in Alarmbereitschaft versetzt wird **Gefahr**.

-   **Befehl zur Gefahr** : Befehl eingeben **Nachricht** zu verwenden, wenn
    Eine Bestellung wird in Alarmbereitschaft versetzt **Gefahr**.

Log 
---

-   **Log Engine** : Ermöglicht das Ändern der Protokoll-Engine für, z
    Senden Sie sie beispielsweise an einen Syslog-Dämon (d).

-   **Protokollformat** : Zu verwendendes Protokollformat (Achtung : ça
    wirkt sich nicht auf Daemon-Protokolle aus).

-   **Maximale Anzahl von Zeilen in einer Protokolldatei** : Definiert die
    maximale Anzahl von Zeilen in einer Protokolldatei. Es wird empfohlen
    diesen Wert nicht zu berühren, weil ein zu großer Wert könnte
    Füllen Sie das Dateisystem und / oder machen Sie Jeedom unfähig
    um das Protokoll anzuzeigen.

-   **Standardprotokollstufe** : Wenn Sie "Standard" auswählen",
    Für die Ebene eines Protokolls in Jeedom ist dies diejenige, die sein wird
    dann verwendet.

Nachfolgend finden Sie eine Tabelle zur Feinverwaltung
logarithmische Ebene der wesentlichen Elemente von Jeedom sowie die von
plugins.

Einrichtungen 
===========

-   **Anzahl der Fehler vor Deaktivierung des Geräts** : Nombre
    Kommunikationsfehler mit dem Gerät vor Deaktivierung von
    dieses (eine Nachricht warnt Sie, wenn dies passiert).

-   **Batterieschwellen** : Ermöglicht die Verwaltung globaler Alarmschwellenwerte
    auf die Batterien.

Update und Dateien 
=======================

Jeedom Update 
---------------------

-   **Quelle aktualisieren** : Wählen Sie die Quelle für die Aktualisierung der
    Jeedom Kern.

-   **Kernversion** : Kernversion zum Wiederherstellen.

-   **Automatisch nach Updates suchen** : Geben Sie an, ob
    Sie müssen automatisch suchen, wenn es neue Updates gibt
    (Seien Sie vorsichtig, um eine Überlastung des Marktes zu vermeiden
    Überprüfung kann sich ändern).

Einlagen 
----------

Die Depots sind Speicher- (und Service-) Räume, um in der Lage zu sein
Backups verschieben, Plugins wiederherstellen, Core wiederherstellen
Jeedom usw.

### Datei 

Einzahlung verwendet, um das Senden von Plugins durch Dateien zu aktivieren.

### Github 

Kaution verwendet, um Jeedom mit Github zu verbinden.

-   **Zeichen** : Token für den Zugang zur privaten Kaution.

-   **Jeedom Core Repository Benutzer oder Organisation** : Nom
    der Benutzer oder die Organisation auf Github für den Kern.

-   **Repository-Name für den Jeedom-Kern** : Repository-Name für den Kern.

-   **Jeedom Kernindustrie** : Kern-Repository-Zweig.

### Markt 

Eine Kaution, die verwendet wird, um Jeedom mit dem Markt zu verbinden, wird dringend empfohlen
um dieses Repository zu verwenden. VORSICHT : Jede Supportanfrage kann sein
abgelehnt, wenn Sie eine andere Einzahlung als diese verwenden.

-   **Adresse** : Marktadresse.

-   **Benutzername** : Ihr Benutzername auf dem Markt.

-   **Passwort** : Ihr Marktpasswort.

-   **[Backup Cloud] Name** : Name Ihres Cloud-Backups (die Aufmerksamkeit muss für jedes Jeedom eindeutig sein, wenn das Risiko besteht, dass es zwischen ihnen abstürzt)

-   **[Backup Cloud] Passwort** : Cloud-Backup-Passwort. WICHTIG Sie dürfen es nicht verlieren, es gibt keine Möglichkeit, es wiederherzustellen. Ohne sie kannst du deine Freiheit nicht wiederherstellen

-   **[Backup Cloud] Häufigkeit der vollständigen Sicherung** : Häufigkeit der vollständigen Cloud-Sicherung. Eine vollständige Sicherung ist länger als eine inkrementelle (die nur die Unterschiede sendet). Es wird empfohlen, 1 pro Monat zu tun

### Samba 

Zahlen Sie ein, um automatisch ein Jeedom-Backup an zu senden
eine Samba-Aktie (z : NAS-Synologie).

-   **\ [Backup \] IP** : Samba Server IP.

-   **\ [Backup \] Benutzer** : Benutzername für die Anmeldung
    (anonyme Verbindungen sind nicht möglich). Es muss geben
    dass der Benutzer Lese- und Schreibrechte an der hat
    Zielverzeichnis.

-   **\ [Backup \] Passwort** : Benutzerpasswort.

-   **\ [Backup \] Freigabe** : Art des Teilens (sei vorsichtig
    Halten Sie auf der Freigabeebene an).

-   **\ [Backup \] Pfad** : Pfad beim Teilen (festlegen
    relativ) muss es existieren.

> **Notiz**
>
> Wenn der Pfad zu Ihrem Samba-Sicherungsordner lautet :
> \\\\ 192.168.0.1 \\ Backups \\ Hausautomation \\ Jeedom Dann IP = 192.168.0.1
> , Teilen = //192.168.0.1 / Backups, Path = Home Automation / Jeedom

> **Notiz**
>
> Bei der Validierung der Samba-Freigabe wie oben beschrieben,
> In diesem Abschnitt wird eine neue Form der Sicherung angezeigt
> Administration → Jeedom-Backups. Durch Aktivieren wird Jeedom fortfahren
> wenn es beim nächsten Backup automatisch gesendet wird. Ein Test ist
> möglich durch manuelle Sicherung.

> **Wichtig**
>
> Möglicherweise müssen Sie das smbclient-Paket für das installieren
> Einzahlung funktioniert.

> **Wichtig**
>
> Das Samba-Protokoll hat mehrere Versionen, die v1 ist kompromittiert 
> Sicherheit und auf einigen NAS können Sie den Client zur Verwendung von v2 zwingen
> oder v3 zum Verbinden. Wenn Sie also einen Protokollverhandlungsfehler haben
> failed: NT_STATUS_INVAID_NETWORK_RESPONSE Es besteht eine gute Chance, dass NAS aufgelistet wird
> die Einschränkung besteht. Sie müssen dann das Betriebssystem Ihres Jeedom ändern
> die Datei / etc / samba / smb.conf und füge diese beiden Zeilen hinzu :
> Client-Max-Protokoll = SMB3
> Client-Min-Protokoll = SMB2
> Der Jeedom-Seite smbclient verwendet dann v2, wobei v3 und nur SMB3 in beiden
> SMB3. Es liegt also an Ihnen, sich an die Einschränkungen des NAS oder eines anderen Samba-Servers anzupassen

> **Wichtig**
>
> Jeedom sollte der einzige sein, der in diesen Ordner schreibt, und er sollte leer sein
> standardmäßig (d. h. vor dem Konfigurieren und Senden des
> Bei der ersten Sicherung darf der Ordner keine Datei oder enthalten
> dossier).

### URLs 

-   **Jeedom-Kern-URL**

-   **URL der Jeedom-Kernversion**


