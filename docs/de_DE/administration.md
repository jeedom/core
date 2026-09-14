# Konfiguration
**Einstellungen → System → Konfiguration**

Auf dieser Seite befinden sich die meisten Konfigurationseinstellungen.
Obwohl es zahlreiche Einstellungen gibt, ist ein Großteil davon standardmäßig voreingestellt.


## Registerkarte „Allgemein“

Auf dieser Registerkarte finden Sie allgemeine Informationen zu Jeedom:

- **Name Ihres Jeedom**: Dient zur Identifizierung Ihres Jeedom, insbesondere im Market. Er kann in Szenarien wiederverwendet werden oder zur Identifizierung eines Backups dienen.
- **Sprache**: Die in Ihrem Jeedom verwendete Sprache.
- **System**: Art der Hardware, auf der das System installiert ist, auf dem Ihr Jeedom läuft.
- **Datum und Uhrzeit**: Auswahl Ihrer Zeitzone. Sie können auf **Zeitsynchronisierung erzwingen** klicken, um eine falsche Uhrzeit, die oben rechts angezeigt wird, zu korrigieren.
- **Optionaler Zeitserver**: Gibt an, welcher Zeitserver verwendet werden soll, wenn Sie auf **Zeitsynchronisierung erzwingen** klicken (nur für Experten).
- **Zeitüberprüfung ignorieren**: Weist Jeedom an, nicht zu überprüfen, ob die Uhrzeit zwischen Jeedom selbst und dem System, auf dem es läuft, übereinstimmt. Dies kann beispielsweise nützlich sein, wenn Sie Jeedom nicht mit dem Internet verbinden und die verwendete Hardware keine RTC-Batterie hat.
- **System**: Gibt die Art der Hardware an, auf der Jeedom installiert ist.
- **Installationsschlüssel**: Der Hardware-Schlüssel Ihres Jeedom im Market. Sollte Ihr Jeedom nicht in der Liste Ihrer Jeedom-Geräte im Market erscheinen, empfehlen wir, auf die Schaltfläche **Zurücksetzen** zu klicken.
- **Letztes bekanntes Datum**: Von Jeedom gespeichertes Datum, das nach einem Neustart für Systeme ohne RTC-Batterie verwendet wird.

Darunter befinden sich mehrere Einstellungen, die Informationen bündeln, die von den Plugins genutzt werden können, sodass diese nicht in jedem Plugin einzeln eingegeben werden müssen.

- Koordinaten: Breitengrad, Längengrad und Höhe Ihres Wohnsitzes bzw. Standorts.
- Adresse: Postanschrift Ihres Wohnsitzes / Standorts.
- Sonstiges: Fläche und Anzahl der Bewohner Ihres Wohnraums/Standorts.

## Registerkarte „Schnittstelle“

Auf dieser Registerkarte finden Sie die Einstellungen zur Anpassung der Anzeige.

### Themen

- **Heller und dunkler Desktop**: Hier können Sie zwischen einem hellen und einem dunklen Design für den Desktop wählen.
- **Mobile hell und dunkel**: wie zuvor bei der Mobile-Version.
- **Helles Design von / bis**: Hier können Sie einen Zeitbereich festlegen, in dem das zuvor ausgewählte helle Design verwendet wird. Dazu müssen Sie jedoch die Option **Design je nach Uhrzeit wechseln** aktivieren.
- **Lichtsensor**: Nur über die mobile Benutzeroberfläche verfügbar; erfordert die Aktivierung von *generic extra sensor* in Chrome auf der Seite chrome://flags.

### Dachziegel

- **Horizontal nicht**: Begrenzt die Breite der Kacheln alle x Pixel.
- **Vertikal nicht**: Legt die Höhe der Kacheln alle x Pixel fest.
- **Rand**: Vertikaler und horizontaler Abstand zwischen den Kacheln, in Pixeln.
- **Vertikale Zentrierung der Kacheln**: Zentriert den Inhalt der Kacheln vertikal.
- **Farbige Widget-Symbole**: Die Widget-Symbole werden je nach Status farblich hervorgehoben. Kann pro Szenario angepasst werden, *setColoredIcon* („Farbige Symbole“).
- **Farbige Kategorien**: Farbige Hervorhebung der Kacheltitel je nach Kategorie.
- **Mobil: Standardmäßig eine Spalte**: Anzeige der Kacheln in voller Breite auf Mobilgeräten


### Hintergrundbilder

- **Hintergrundbilder anzeigen**: Zeigt die Hintergrundbilder an, die auf den Seiten „Szenarien“, „Objekte“, „Interaktionen“ usw. zu finden sind.
- **Hintergrundunschärfe bei Objekten**: Ermöglicht die automatische Unschärfe der Hintergrundbilder von Objekten/Räumen.
- **Dashboard-Bild**: Hintergrundbild für die Dashboard-Seiten (abhängig von den Optionen des Objekts).
- **Bildanalyse**: Hintergrundbild für die Seiten des Menüs „Analyse“.
- **Bild „Extras“**: Hintergrundbild für die Seiten des Menüs „Extras“.
- **Deckkraft des „Light“-Themes**: Deckkraft der Hintergrundbilder im „Light“-Theme. Passen Sie diese je nach Helligkeit der Hintergrundbilder an, um eine bessere Lesbarkeit zu erzielen.
- **Deckkraft im Dark-Theme**: Deckkraft der Hintergrundbilder im Dark-Theme. Passen Sie diese je nach Helligkeit der Hintergrundbilder an, um eine bessere Lesbarkeit zu erzielen.

### Optionen

- **Tabellenansicht**: Zeigt die Seiten des Menüs „Extras“ und die unterstützten Plugins im Tabellenmodus an.
- **Position der Benachrichtigungen**: Position auf der Seite, an der die Benachrichtigungen angezeigt werden.
- **Anzeigedauer der Benachrichtigungen**: Dauer der Anzeige der Benachrichtigungen in Sekunden. 0, damit sie nicht automatisch ausgeblendet werden.

### Individuelle Anpassung

- **Aktivieren**: Aktiviert die Nutzung der unten aufgeführten Optionen.
- **Transparenz**: Zeigt die Kacheln des Dashboards und bestimmte Inhalte mit einer bestimmten Transparenz an. 1: vollständig undurchsichtig, 0: vollständig transparent.
- **Abgerundet**: Zeigt die Elemente der Benutzeroberfläche mit abgerundeten Ecken an. 0: keine Rundung, 1: maximale Rundung.
- **Schatten deaktivieren**: Deaktiviert die Schatten der Kacheln auf dem Dashboard, in den Menüs und bei bestimmten Elementen der Benutzeroberfläche.



## Registerkarte „Netzwerke“

Dieser wichtige Teil von Jeedom muss unbedingt korrekt konfiguriert werden, da sonst viele Plugins möglicherweise nicht funktionieren. Es gibt zwei Möglichkeiten, auf Jeedom zuzugreifen: den **internen Zugriff** (aus demselben lokalen Netzwerk wie Jeedom) und den **externen Zugriff** (aus einem anderen Netzwerk, insbesondere über das Internet).

> **Wichtig**
>
> Dieser Abschnitt dient lediglich dazu, Jeedom die Umgebung zu erklären:
> Eine Änderung des Ports oder der IP-Adresse in diesem Reiter hat keine Auswirkungen auf den tatsächlichen Port oder die IP-Adresse von Jeedom. Dazu müssen Sie sich per SSH verbinden und die Datei /etc/network/interfaces für die IP-Adresse sowie die Dateien etc/apache2/sites-available/default und etc/apache2/sites-available/default\_ssl (für HTTPS) bearbeiten.
> Bei unsachgemäßer Handhabung Ihres Jeedom-Geräts kann das Jeedom-Team jedoch nicht haftbar gemacht werden und sich berechtigt, Supportanfragen abzulehnen.

- **Interner Zugriff**: Informationen zum Zugriff auf Jeedom von einem Gerät aus, das sich im selben Netzwerk wie Jeedom befindet (LAN)
    - **OK/NOK**: Zeigt an, ob die interne Netzwerkkonfiguration korrekt ist.
    - **Protokoll**: Das zu verwendende Protokoll, häufig HTTP.
    - **URL- oder IP-Adresse**: Geben Sie hier die IP-Adresse von Jeedom ein.
    - **Port**: Der Port der Jeedom-Weboberfläche, in der Regel 80.
Achtung: Wenn Sie hier den Port ändern, ändert sich der tatsächliche Port von Jeedom nicht, dieser bleibt unverändert.
    - **Ergänzung**: Der zusätzliche URL-Teil (Beispiel: /Jeedom) für den Zugriff auf Jeedom.

- **Externer Zugriff**: Informationen zum Zugriff auf Jeedom von außerhalb des lokalen Netzwerks. Nur ausfüllen, wenn Sie nicht das Jeedom-DNS verwenden.
    - **OK/NOK**: Zeigt an, ob die externe Netzwerkkonfiguration korrekt ist.
    - **Protokoll**: Protokoll für den externen Zugriff.
    - **URL oder IP-Adresse**: Externe IP-Adresse, sofern es sich um eine feste Adresse handelt. Andernfalls geben Sie die URL an, die auf die externe IP-Adresse Ihres Netzwerks verweist.
    - **Ergänzung**: Der zusätzliche URL-Teil (Beispiel: /Jeedom) für den Zugriff auf Jeedom.

- **Proxy für Market**: Proxy aktivieren.
    - Aktivieren Sie das Kontrollkästchen „Proxy aktivieren“.
    - **Proxy-Adresse**: Geben Sie die Proxy-Adresse ein,
    - **Proxy-Port**: Geben Sie den Proxy-Port ein,
    - **Login**: Geben Sie den Proxy-Login ein,
    - **Passwort**: Geben Sie das Passwort ein.

> **Tipp**
>
> Wenn Sie HTTPS verwenden, ist der Port 443 (Standard), bei HTTP ist der Port 80 (Standard). Um HTTPS von außen zu nutzen, ist nun ein Let’s Encrypt-Plugin im Market verfügbar.

> **Tipp**
>
> Um herauszufinden, ob Sie im Feld **Ergänzung** einen Wert eingeben müssen, prüfen Sie beim Einloggen bei Jeedom in Ihrem Internetbrowser, ob Sie hinter der IP-Adresse /Jeedom (oder etwas anderes) hinzufügen müssen.

- **Erweiterte Verwaltung**: Dieser Abschnitt wird möglicherweise nicht angezeigt, je nach Kompatibilität mit Ihrer Hardware.
Hier finden Sie eine Liste Ihrer Netzwerkschnittstellen. Sie können Jeedom anweisen, das Netzwerk nicht zu überwachen, indem Sie auf **„Netzwerkverwaltung durch Jeedom deaktivieren“** klicken (dieses Kontrollkästchen ist zu aktivieren, wenn Jeedom mit keinem Netzwerk verbunden ist). Außerdem können Sie hier den lokalen IP-Bereich im Format 192.168.1.* angeben (nur in Docker-Installationen zu verwenden).
- **Proxy Market**: Ermöglicht den Fernzugriff auf Ihr Jeedom, ohne dass Sie ein DNS, eine feste IP-Adresse oder das Öffnen der Ports Ihrer Internet-Router benötigen.
    - **Jeedom-DNS verwenden**: Aktiviert die Jeedom-DNS (Achtung: Hierfür ist mindestens ein Service Pack erforderlich).
    - **DNS-Status**: Status des HTTP-DNS.
    - **Verwaltung**: Ermöglicht das Beenden und Neustarten des Jeedom-DNS-Dienstes.

> **Wichtig**
>
> Wenn Sie das Jeedom-DNS nicht zum Laufen bringen können, überprüfen Sie die Einstellungen der Firewall und der Kindersicherung Ihrer Internet-Router (bei der Livebox muss beispielsweise die Firewall auf mittlerer Stufe eingestellt sein).
- **Sitzungsdauer (Stunden)**: Lebensdauer der PHP-Sitzungen; es wird davon abgeraten, diesen Parameter zu ändern.

## Registerkarte „Protokolle“

### Zeitleiste

- **Maximale Anzahl von Ereignissen**: Legt die maximale Anzahl der Ereignisse fest, die in der Zeitleiste angezeigt werden sollen.
- **Alle Ereignisse löschen**: Damit können Sie die Zeitleiste von allen gespeicherten Ereignissen bereinigen.

### Beiträge

- **Bei jedem Fehler in den Protokollen eine Meldung hinzufügen**: Wenn ein Plugin oder Jeedom eine Fehlermeldung in ein Protokoll schreibt, fügt Jeedom automatisch eine Meldung im Meldungscenter hinzu (so können Sie zumindest sicher sein, dass Sie sie nicht übersehen).
- **Aktion bei Nachricht**: Ermöglicht die Ausführung einer Aktion, wenn eine Nachricht im Nachrichtencenter hinzugefügt wird. Für diese Aktionen stehen Ihnen 2 Tags zur Verfügung:
        - #subject#: betreffende Nachricht.
        - #plugin#: Das Plugin, das die Meldung ausgelöst hat.

### Benachrichtigungen

- **Bei jedem Timeout eine Meldung hinzufügen**: Fügt eine Meldung im Meldungscenter hinzu, wenn ein Gerät in einen **Timeout** gerät.
- **Befehl bei Zeitüberschreitung**: Ein Befehl vom Typ **Nachricht**, der verwendet wird, wenn ein Gerät eine **Zeitüberschreitung** aufweist.
- **Eine Meldung zu jeder Batterie im Warnstatus hinzufügen**: Fügt eine Meldung im Meldungscenter hinzu, wenn der Akkustand eines Geräts im **Warnstatus** ist.
- **Batterie-Betrieb im Warnmodus**: Befehl vom Typ **Meldung**, der verwendet werden soll, wenn der Batteriestand eines Geräts im **Warnmodus** ist.
- **Bei jeder gefährdeten Batterie eine Meldung hinzufügen**: Fügt eine Meldung im Meldungscenter hinzu, wenn der Batteriestand eines Geräts **gefährdet** ist.
- **Batterie im kritischen Bereich**: Befehl vom Typ **Meldung**, der verwendet werden soll, wenn der Batteriestand eines Geräts im **kritischen Bereich** liegt.
- **Zu jeder Warnung eine Meldung hinzufügen**: Fügt eine Meldung im Meldungscenter hinzu, wenn ein Befehl den Alarmstatus **warning** erreicht.
- **Befehl bei „Warning“**: Ein Befehl vom Typ **message**, der verwendet wird, wenn ein Befehl in den Alarmzustand **warning** wechselt.
- **Zu jedem Gefahrenfall eine Meldung hinzufügen**: Fügt eine Meldung im Meldungscenter hinzu, wenn ein Befehl in den Alarmzustand **Gefahr** wechselt.
- **Befehl bei Gefahr**: Ein Befehl vom Typ **Nachricht**, der verwendet wird, wenn ein Befehl in den Alarmzustand **Gefahr** wechselt.

### Protokolle

- **Protokoll-Engine**: Ermöglicht es, die Protokoll-Engine zu ändern, um die Protokolle beispielsweise an einen syslog(d)-Daemon zu senden.
- **Protokollformat**: Zu verwendendes Protokollformat (Achtung: Dies hat keine Auswirkungen auf die Protokolle der Daemons).
- **Maximale Zeilenzahl in einer Protokolldatei**: Legt die maximale Zeilenzahl in einer Protokolldatei fest. Es wird empfohlen, diesen Wert nicht zu ändern, da ein zu hoher Wert das Dateisystem überfüllen und/oder dazu führen könnte, dass Jeedom das Protokoll nicht mehr anzeigen kann.
- **Standard-Protokollierungsstufe**: Wenn Sie in Jeedom für die Protokollierungsstufe „Standard“ auswählen, wird diese dann verwendet.

Nachstehend finden Sie eine Tabelle, mit der Sie die Protokollierungsstufe der wesentlichen Elemente von Jeedom sowie der Plugins detailliert verwalten können.

## Registerkarte „Zusammenfassungen“

[Siehe Dokumentation zu den Zusammenfassungen.](https://doc.jeedom.com/concept/de_DE/summary)

## Registerkarte „Ausstattung“

### Ausstattung

- **Anzahl der Fehlversuche vor der Deaktivierung des Geräts**: Anzahl der fehlgeschlagenen Kommunikationsversuche mit dem Gerät, bevor dieses deaktiviert wird (in diesem Fall erhalten Sie eine entsprechende Meldung).
- **Batteriegrenzwerte**: Ermöglicht die Verwaltung der allgemeinen Alarmschwellenwerte für Batterien.

Zahlreiche Befehle können protokolliert werden. Unter „Analyse → Verlauf“ werden Ihnen Grafiken angezeigt, die deren Verwendung veranschaulichen. Auf dieser Registerkarte können Sie allgemeine Einstellungen für die Protokollierung von Befehlen festlegen.

### Bestellverlauf

- **Statistiken zu Widgets anzeigen**: Ermöglicht die Anzeige von Statistiken zu Widgets. Das Widget muss kompatibel sein, was bei den meisten der Fall ist. Außerdem muss es sich um einen numerischen Befehl handeln.
- **Berechnungszeitraum für Min., Max. und Durchschnitt (in Stunden)**: Zeitraum für die Berechnung der Statistiken (standardmäßig 24 Stunden). Es ist nicht möglich, einen Zeitraum von weniger als einer Stunde festzulegen.
- **Berechnungszeitraum für den Trend (in Stunden)**: Zeitraum für die Berechnung der Trends (standardmäßig 2 Stunden). Es ist nicht möglich, einen Zeitraum von weniger als einer Stunde festzulegen.
- **Zeitraum bis zur Archivierung (in Stunden)**: Gibt den Zeitraum an, nach dem Jeedom Daten archiviert (standardmäßig 24 Stunden). Das bedeutet, dass historische Daten älter als 24 Stunden sein müssen, um archiviert zu werden (zur Erinnerung: Bei der Archivierung wird entweder ein Mittelwert gebildet oder der Höchst- bzw. Tiefstwert der Daten über einen Zeitraum, der der Größe der Datenpakete entspricht, herangezogen).
- **Nach Paketen von (in Stunden) archivieren**: Diese Einstellung gibt genau die Größe der Pakete an (standardmäßig 1 Stunde). Das bedeutet beispielsweise, dass Jeedom Zeiträume von 1 Stunde berücksichtigt, den Durchschnittswert berechnet und den neuen Wert speichert, wobei die gemittelten Werte gelöscht werden.
- **Schwellenwert für den Abwärtstrend**: Dieser Wert gibt an, ab welchem Wert Jeedom einen Abwärtstrend anzeigt. Er muss negativ sein (Standardwert: -0,1).
- **Schwellenwert für die Berechnung des Aufwärtstrends**: Das Gleiche gilt für den Aufwärtstrend.
- **Standardzeitraum für die Anzeige von Diagrammen**: Der Zeitraum, der standardmäßig verwendet wird, wenn Sie den Verlauf eines Befehls anzeigen möchten. Je kürzer der Zeitraum ist, desto schneller kann Jeedom das angeforderte Diagramm anzeigen.

> **Hinweis**
>
> Die erste Einstellung **Statistiken in den Widgets anzeigen** ist zwar verfügbar, standardmäßig jedoch deaktiviert, da sie die Ladezeit des Dashboards erheblich verlängert. Wenn Sie diese Option aktivieren, stützt sich Jeedom standardmäßig auf die Daten der letzten 24 Stunden, um diese Statistiken zu berechnen.
> Die Methode zur Trendberechnung basiert auf der Methode der kleinsten Quadrate (siehe [hier](https://fr.wikipedia.org/wiki/M%C3%A9thode_des_moindres_carr%C3%A9s) (für weitere Details).

### Push

- **Globale Push-URL**: Ermöglicht das Hinzufügen einer URL, die bei einer Aktualisierung eines Befehls aufgerufen werden soll. Sie können die folgenden Tags verwenden:
**\#value\#** für den Wert des Befehls, **\#cmd\_name\#** für den Namen des Befehls,
**\#cmd\_id\#** für die eindeutige Kennung des Befehls,
**\#humanname\#** für den vollständigen Namen des Befehls (z. B.: \#\[Badezimmer\]\[Hydrometrie\]\[Luftfeuchtigkeit\]\#),
**\#eq_name\#** als Name des Geräts

## Registerkarte „Berichte“

Ermöglicht die Konfiguration der Berichterstellung und -verwaltung

- **Wartezeit nach dem Erstellen der Seite (in ms)**: Wartezeit nach dem Laden des Berichts, um den „Screenshot“ zu erstellen; diese Zeit sollte beispielsweise geändert werden, wenn Ihr Bericht unvollständig ist.
- **Ältere Berichte nach (Tagen) löschen**: Legt fest, nach wie vielen Tagen ein Bericht gelöscht wird (Berichte nehmen etwas Speicherplatz in Anspruch, achten Sie daher darauf, die Aufbewahrungsdauer nicht zu lang einzustellen).

## Registerkarte „Links“

Ermöglicht die Konfiguration von Verknüpfungsdiagrammen. Diese Verknüpfungen ermöglichen es, die Beziehungen zwischen Objekten, Geräten usw. in Form eines Diagramms darzustellen.

- **Tiefe für Szenarien**: Ermöglicht es, bei der Anzeige eines Verknüpfungsdiagramms eines Szenarios die maximale Anzahl der anzuzeigenden Elemente festzulegen (je mehr Elemente vorhanden sind, desto langsamer wird das Diagramm generiert und desto schwieriger ist es zu lesen).
- **Tiefe für Objekte**: Das Gleiche gilt für Objekte.
- **Einbautiefe für Geräte**: Gleiches gilt für die Geräte.
- **Einbautiefe für die Bedienelemente**: Gleich wie bei den Bedienelementen.
- **Tiefe für Variablen**: Das Gleiche gilt für Variablen.
- **Prerender-Einstellung**: Ermöglicht die Anpassung des Layouts der Grafik.
- **Render-Einstellung**: Wie oben.

## Registerkarte „Interaktionen“

Auf dieser Registerkarte können Sie allgemeine Einstellungen für die Interaktionen festlegen, die Sie unter „Extras“ → „Interaktionen“ finden.

> **Tipp**
>
> Um die Protokollierung der Interaktionen zu aktivieren, gehen Sie auf die Registerkarte „Einstellungen“ → „System“ → „Konfiguration: Protokolle“ und aktivieren Sie dann **Debug** in der Liste unten. Achtung: Die Protokolle werden dann sehr ausführlich sein!

### Allgemeines

Hier stehen Ihnen drei Einstellungen zur Verfügung:

- **Empfindlichkeit**: Es gibt 4 Übereinstimmungsstufen (die Empfindlichkeit reicht von 1 (genaue Übereinstimmung) bis 99) für
    -   1 Wort: Die Übereinstimmungsstufe für Interaktionen mit einem einzigen Wort.
    -   2 Wörter: Die Übereinstimmungsstufe für Interaktionen mit zwei Wörtern.
    -   3 Wörter: Die Übereinstimmungsstufe für Interaktionen mit drei Wörtern.
    -   + aus 3 Wörtern: die Übereinstimmungsstufe für Interaktionen mit mehr als drei Wörtern.
- **Keine Antwort, wenn die Interaktion nicht verstanden wird**: Standardmäßig antwortet Jeedom mit „Ich habe das nicht verstanden“, wenn keine passende Interaktion gefunden wird. Diese Funktion kann deaktiviert werden, damit Jeedom keine Antwort gibt. Aktivieren Sie das Kontrollkästchen, um die Antwort zu deaktivieren.
- **Allgemeiner Ausschluss-Regex für Interaktionen**: Ermöglicht die Definition eines Regex-Ausdrucks, der, wenn er mit einer Interaktion übereinstimmt, diesen Satz automatisch aus der Generierung entfernt (nur für Experten). Weitere Informationen finden Sie in den Erläuterungen im Kapitel **Ausschluss-Regex** der Dokumentation zu Interaktionen.

### Automatische, kontextbezogene Interaktion und Benachrichtigung

-   Dank der **automatischen Interaktionen** kann Jeedom versuchen, eine Interaktionsanfrage zu verstehen, auch wenn keine definiert ist. Es sucht dann nach einem Objekt-, Geräte- und/oder Befehlsnamen, um die Anfrage bestmöglich zu beantworten.

-   Mit **kontextbezogenen Interaktionen** können Sie mehrere Anfragen aneinanderreihen, ohne alles wiederholen zu müssen, zum Beispiel:
    - *Jeedom unter Beibehaltung des Kontexts:*
        - *Du*: Wie hoch ist die Temperatur im Schlafzimmer?
        - *Jeedom*: Temperatur 25,2 °C
        - *Du*: Und im Wohnzimmer?
        - *Jeedom*: Temperatur 27,2 °C
    - *Zwei Fragen in einer stellen:*
        - *Sie*: Wie hoch ist die Temperatur im Schlafzimmer und im Wohnzimmer?
        - *Jeedom*: Temperatur 23,6 °C, Temperatur 27,2 °C
-   Mit Interaktionen vom Typ **Benachrichtige mich** können Sie Jeedom anweisen, Sie zu benachrichtigen, wenn ein Wert einen bestimmten Wert überschreitet, unterschreitet oder genau diesem entspricht.
    - *Du*: Sag mir Bescheid, wenn die Temperatur im Wohnzimmer 25 °C überschreitet.
    - *Jeedom*: OK (*Sobald die Temperatur im Wohnzimmer 25 °C überschreitet, wird Jeedom Sie einmalig darauf hinweisen*)

> **Hinweis**
>
> Standardmäßig antwortet Jeedom über denselben Kanal, über den Sie die Benachrichtigung angefordert haben. Wenn kein solcher Kanal gefunden wird, verwendet es den in diesem Reiter angegebenen Standardbefehl: **Standard-Rückmeldung**.

Hier sind also die verschiedenen verfügbaren Optionen:

- **Automatische Interaktionen aktivieren**: Setzen Sie ein Häkchen, um automatische Interaktionen zu aktivieren.
- **Kontextbezogene Antworten aktivieren**: Aktivieren Sie dieses Kontrollkästchen, um kontextbezogene Interaktionen zu aktivieren.
- **Kontextbezogene Antwort hat Vorrang, wenn der Satz mit** beginnt: Wenn der Satz mit dem Wort beginnt, das Sie hier eingeben, gibt Jeedom einer kontextbezogenen Antwort Vorrang (Sie können mehrere Wörter eingeben, indem Sie diese durch **;** trennen).
- **Eine Interaktion in zwei Teile aufteilen, wenn sie Folgendes enthält**: Das Gleiche gilt für die Aufteilung einer Interaktion, die mehrere Fragen enthält. Geben Sie hier die Wörter an, die die verschiedenen Fragen voneinander trennen.
- **Interaktionen vom Typ „Benachrichtige mich“ aktivieren**: Setzen Sie ein Häkchen, um Interaktionen vom Typ **„Benachrichtige mich“** zu aktivieren.
- **Antwort vom Typ „Benachrichtige mich“, wenn der Satz mit** beginnt: Wenn der Satz mit diesem/diesen Wort(en) beginnt, versucht Jeedom, eine Interaktion vom Typ **„Benachrichtige mich“** auszulösen (Sie können mehrere Wörter angeben, indem Sie diese durch **;** trennen).
- **Standard-Rückmeldung**: Standard-Rückmeldung für eine Interaktion vom Typ **Benachrichtige mich** (wird insbesondere verwendet, wenn Sie die Benachrichtigung über die mobile Schnittstelle programmiert haben)
- **Synonyme für Objekte**: Liste der Synonyme für Objekte (z. B.: Erdgeschoss|Erdgeschoss|Keller|Unten;Badezimmer|Badezimmer).
- **Synonyme für Geräte**: Liste der Synonyme für Geräte.
- **Synonyme für Befehle**: Liste der Synonyme für Befehle.
- **Synonyme für Zusammenfassungen**: Liste der Synonyme für Zusammenfassungen.
- **Synonym für „Schieberegler auf Maximum stellen“**: Synonym dafür, einen Schieberegler auf den maximalen Wert zu stellen (z. B. „öffnen“ bedeutet, den Rollladen des Schlafzimmers zu öffnen ⇒ Rollladen des Schlafzimmers auf 100 %).
- **Synonym für „Schieberegler auf Minimum stellen“**: Synonym dafür, einen Schieberegler auf den Mindestwert zu stellen (z. B. „Schließen“, um den Rollladen im Schlafzimmer zu schließen ⇒ Rollladen im Schlafzimmer auf 0 %).

## Registerkarte „Sicherheit“

### LDAP

- **LDAP-Authentifizierung aktivieren**: Aktiviert die Authentifizierung über ein AD (LDAP).
- **Host**: Server, auf dem die AD gehostet wird.
- **Domäne**: Domäne Ihrer AD.
- **DN-Basis**: DN-Basis Ihres AD.
- **Benutzername**: Benutzername, mit dem sich Jeedom bei der AD anmeldet.
- **Passwort**: Passwort, damit sich Jeedom mit dem AD verbinden kann.
- **Benutzersuchfelder**: Suchfelder für die Benutzeranmeldung. In der Regel „uid“ für LDAP, „SamAccountName“ für Windows AD.
- **Administratorfilter (optional)**: Administratorfilter im AD (z. B. für die Verwaltung von Gruppen)
- **Benutzerfilter (optional)**: Benutzerfilter im AD (z. B. zur Verwaltung von Gruppen)
- **Filter für eingeschränkte Benutzer (optional)**: Filter für eingeschränkte Benutzer im AD (z. B. zur Verwaltung von Gruppen)
- **REMOTE\_USER zulassen**: Aktiviert REMOTE\_USER (wird beispielsweise bei SSO verwendet).

### Anmelden

- **Zulässige Anzahl von Fehlversuchen**: Legt fest, wie viele aufeinanderfolgende Versuche zulässig sind, bevor die IP-Adresse gesperrt wird.
- **Maximale Zeitspanne zwischen Fehlversuchen (in Sekunden)**: Maximale Zeitspanne, innerhalb derer zwei Versuche als aufeinanderfolgend gelten
- **Dauer der Sperre (in Sekunden), -1 für unbegrenzt**: Dauer der IP-Sperre
- **„Weiße“ IP-Adressen**: Liste der IP-Adressen, die niemals gesperrt werden dürfen
- **Gesperrte IPs löschen**: Ermöglicht das Leeren der Liste der derzeit gesperrten IP-Adressen

Die Liste der gesperrten IP-Adressen finden Sie am Ende dieser Seite. Dort sind die IP-Adresse, das Datum der Sperrung und das geplante Ende der Sperrung aufgeführt.

## Registerkarte „Update/Market“

### Jeedom-Update

- **Update-Quelle**: Wählen Sie die Update-Quelle für den Jeedom-Core aus.
- **Core-Version**: Zu ladende Core-Version.
- **Automatisch nach Updates suchen**: Legt fest, ob automatisch nach neuen Updates gesucht werden soll (Achtung: Um eine Überlastung des Market zu vermeiden, kann sich der Zeitpunkt der Überprüfung ändern).

### The repositories

Repositories sind Speicher- (und Service-)Bereiche, über die man Backups verschieben, Plugins abrufen, den Jeedom-Core herunterladen usw. kann.

### Datei

Repository, das dazu dient, das Versenden von Plugins über Dateien zu aktivieren.

#### GitHub

Repository zur Verbindung von Jeedom mit GitHub.

- **Token**: Token für den Zugriff auf das private Repository.
- **Benutzer oder Organisation des Repositories für den Jeedom-Core**: Name des Benutzers oder der Organisation auf GitHub für den Core.
- **Name des Repositories für den Jeedom-Core**: Name des Repositories für den Core.
- **Zweig für den Jeedom-Kern**: Zweig des Repositorys für den Kern.

#### Markt

Repository zur Anbindung von Jeedom an den Market; die Verwendung dieses Repositorys wird dringend empfohlen. Achtung: Supportanfragen können abgelehnt werden, wenn Sie ein anderes Repository als dieses verwenden.

- **Adresse**: Adresse des Marktes. (https://market.jeedom.com).
- **Benutzername**: Ihr Benutzername im Market.
- **Passwort**: Ihr Passwort für den Market.
- **[Cloud-Backup] Name**: Name Ihres Cloud-Backups (Achtung: Der Name muss für jedes Jeedom-System eindeutig sein, da es sonst zu Überschreibungen kommen kann).
- **[Cloud-Backup] Passwort**: Passwort für das Cloud-Backup. WICHTIG: Sie dürfen es auf keinen Fall verlieren, da es keine Möglichkeit gibt, es wiederherzustellen. Ohne dieses Passwort können Sie Ihre Wiederherstellung von Jeedom nicht mehr durchführen.
- **[Cloud-Backup] Häufigkeit des vollständigen Backups**: Häufigkeit des vollständigen Cloud-Backups. Ein vollständiges Backup dauert länger als ein inkrementelles Backup (bei dem nur die Änderungen übertragen werden). Es wird empfohlen, einmal im Monat ein vollständiges Backup durchzuführen.

#### Samba

Repository, über das automatisch ein Jeedom-Backup an einen Samba-Freigabeordner (z. B. Synology NAS) gesendet werden kann.

- **\[Backup\] IP**: IP-Adresse des Samba-Servers.
- **\[Backup\] Benutzer**: Benutzername für die Anmeldung (anonyme Anmeldungen sind nicht möglich). Der Benutzer muss unbedingt über Lese- UND Schreibrechte für das Zielverzeichnis verfügen.
- **\[Backup\] Passwort**: Passwort des Benutzers (Achtung: Sonderzeichen sind nicht zulässig).
- **\[Backup\] Freigabe**: Pfad zur Freigabe (achten Sie darauf, dass Sie genau bei der Freigabe aufhören).
- **\[Backup\] Pfad**: Pfad innerhalb der Freigabe (als relativer Pfad anzugeben), dieser muss vorhanden sein.

> **Hinweis**
>
> Wenn der Pfad zu Ihrem Samba-Sicherungs-Ordner lautet:
> \\\\192.168.0.1\\Backups\\Hausautomation\\Jeedom Also IP = 192.168.0.1, Freigabe = //192.168.0.1/Backups, Pfad = Hausautomation/Jeedom

> **Hinweis**
>
> Bei der Bestätigung der Samba-Freigabe, wie zuvor beschrieben, erscheint im Bereich „Einstellungen → System → Backups“ von Jeedom eine neue Backup-Option. Wenn Sie diese aktivieren, sendet Jeedom das Backup automatisch bei der nächsten Sicherung. Ein Test ist möglich, indem Sie ein manuelles Backup durchführen.

> **Wichtig**
>
> Möglicherweise müssen Sie das Paket „smbclient“ installieren, damit das Repository funktioniert.

> **Wichtig**
>
> Das Samba-Protokoll gibt es in mehreren Versionen. Die Sicherheit von Version 1 ist beeinträchtigt, und bei einigen NAS-Geräten können Sie den Client dazu zwingen, für die Verbindung Version 2 oder 3 zu verwenden. Wenn Sie also die Fehlermeldung *protocol negotiation failed: NT_STATUS_INVALID_NETWORK_RESPONSE* erhalten, ist es sehr wahrscheinlich, dass auf der NAS-Seite diese Einschränkung aktiviert ist. In diesem Fall müssen Sie auf dem Betriebssystem Ihres Jeedom die Datei /etc/samba/smb.conf bearbeiten und folgende zwei Zeilen hinzufügen:
> Client-Max-Protokoll = SMB3
> Client-Min-Protokoll = SMB2
> Der smbclient auf der Jeedom-Seite verwendet dann entweder v2 oder v3; wenn Sie SMB3 auf beiden Seiten einstellen, wird ausschließlich SMB3 verwendet. Es liegt also an Ihnen, die Einstellungen entsprechend den Einschränkungen auf Seiten des NAS oder eines anderen Samba-Servers anzupassen.

> **Wichtig**
>
> Jeedom darf als einziges Programm in diesen Ordner schreiben, und dieser muss standardmäßig leer sein (das heißt, vor der Konfiguration und dem Senden der ersten Sicherung darf der Ordner keine Dateien oder Unterordner enthalten).

#### URL

- **Jeedom-Core-URL**
- **URL der Core-Version von Jeedom**

## Registerkarte „Cache“

Ermöglicht die Überwachung und Steuerung des Jeedom-Caches:

- **Cache-Engine**: Auswahl der Cache-Engine für Jeedom:
  - Dateisystem: Speicherung der Cache-Informationen in /tmp/jeedom/cache (also im RAM) im Dateimodus, verwendet eine Bibliothek eines Drittanbieters. Es wird in Kürze durch „Datei“ (Beta) ersetzt.
  - Datei (Beta): Speicherung der Cache-Informationen unter /tmp/jeedom/cache (also im Arbeitsspeicher) im Dateimodus. Die leistungsstärkste Option, wird jedoch alle 30 Minuten gesichert.
  - MySQL (Beta): Verwendung einer Cache-Tabelle in der Datenbank. Die leistungsschwächste Variante, wird jedoch in Echtzeit gesichert (kein Datenverlust möglich)
  - Redis (Beta): Nur für Experten geeignet, nutzt Redis zur Verwaltung des Caches (daher müssen Sie Redis und die PHP-Redis-Abhängigkeiten selbst installieren)
- **Cache leeren**: Erzwingt das Löschen von Objekten, die nicht mehr benötigt werden. Jeedom führt dies automatisch jede Nacht durch.
- **Alle zwischengespeicherten Daten löschen**: Leert den Cache vollständig.
Achtung, dabei können Daten verloren gehen!
- **Pausenzeit für Long Polling**: Häufigkeit, mit der Jeedom überprüft, ob für die Clients (Weboberfläche, mobile App…) Ereignisse ausstehen. Je kürzer diese Zeit ist, desto schneller wird die Oberfläche aktualisiert; im Gegenzug werden jedoch mehr Ressourcen verbraucht, was Jeedom verlangsamen kann.

>**WICHTIG**
>
> Jeder Wechsel des Cache-Motors führt zu einem Zurücksetzen desselben. Man muss daher abwarten, bis die Module die Informationen zurücksenden, um alle Daten wiederherzustellen.

## Registerkarte „API“

Hier finden Sie eine Liste der verschiedenen API-Schlüssel, die in Ihrem Jeedom verfügbar sind. Standardmäßig verfügt der Core über zwei API-Schlüssel:

- Allgemeiner Hinweis: Der Einsatz sollte so weit wie möglich vermieden werden,
- und eine weitere für Fachleute: Sie dient der Flottenverwaltung. Sie kann leer sein.
- Anschließend erhalten Sie bei Bedarf einen API-Schlüssel pro Plugin.

Für jeden Plugin-API-Schlüssel sowie für die APIs HTTP, JSON-RPC und TTS können Sie den Gültigkeitsbereich festlegen:

- **Deaktiviert**: Der API-Schlüssel kann nicht verwendet werden,
- **Whitelist**: Es ist nur eine Liste von IP-Adressen zulässig (siehe Einstellungen→System→Konfiguration: Sicherheit),
- **Localhost**: Es werden nur Anfragen zugelassen, die von dem System stammen, auf dem Jeedom installiert ist,
- **Aktiviert**: keine Einschränkungen, jedes System, das Zugriff auf Ihr Jeedom hat, kann auf diese API zugreifen.

Für jeden Plugin-API-Schlüssel können Sie den Zugriff auf die Core-Methoden (allgemein) sperren, um sie ausschließlich auf ihre eigene integrierte Methode zu beschränken (Achtung: Bestimmte Plugins wie „mobile“ oder „jeelink“ benötigen die Core-Methoden unbedingt).

## Registerkarte &gt;\_OS/DB

> **Wichtig**
>
> Diese Registerkarte ist Experten vorbehalten.
> Wenn Sie Jeedom mit einer dieser beiden Lösungen modifizieren, kann es sein, dass der Support Ihnen keine Hilfe leistet.

### Systemprüfungen

- **Allgemeine Überprüfung**: Ermöglicht das Ausführen eines Konsistenztests für Jeedom.
- **Wiederherstellung der Berechtigungen**: Ermöglicht es, die korrekten Berechtigungen für die Verzeichnisse und Dateien des Jeedom-Cores wiederherzustellen.
- **Überprüfung der Systempakete**: Ermöglicht die Überprüfung der installierten Pakete.
- **Datenbankprüfung**: Ermöglicht es, eine Prüfung der Jeedom-Datenbank durchzuführen und gegebenenfalls Fehler zu beheben.
- **Datenbankbereinigung**: Startet eine Überprüfung der Datenbank und löscht eventuell nicht mehr verwendete Einträge.


### System-Tools

- **Datei-Editor**: Ermöglicht den Zugriff auf verschiedene Dateien des Betriebssystems sowie deren Bearbeitung, Löschung oder Erstellung.
- **Systemverwaltung**: Ermöglicht den Zugriff auf eine Systemverwaltungsoberfläche. Es handelt sich um eine Art Shell-Konsole, in der Sie die nützlichsten Befehle ausführen können, insbesondere um Informationen über das System abzurufen.
- **Massenbearbeitung**: Tool zur Massenbearbeitung von Geräten, Befehlen, Objekten und Szenarien.
- **Datenbankverwaltung**: Ermöglicht den Zugriff auf die Jeedom-Datenbank. Im oberen Feld können Sie dann Befehle eingeben.
- **Benutzername / Passwort**: Benutzername und Passwort für den Zugriff auf die von Jeedom verwendete Datenbank.
