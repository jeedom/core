Auf der WIdentifikationgets-Seite können Sie benutzerdefinierte und eindeutige WIdentifikationgets für Ihr Jeedom erstellen.

Es gibt 2 Möglichkeiten :

- Entweder durch Klicken auf die Code-Schaltfläche und direktes Schreiben Ihres HTML-Codes für Ihr WIdentifikationget (dies wird nicht unbedingt empfohlen, da Ihr Code während jeedom-Updates möglicherweise nicht mehr mit jeedom kompatibel ist).
- Entweder indem Sie ein WIdentifikationget basierend auf einer von uns bereitgestellten Vorlage erstellen

# Aber was ist eine Vorlage ?

Zur Vereinfachung ist es Code (hier HTML), in dem wir bestimmte Teile vordefiniert haben, die Sie nach Ihren Wünschen konfigurieren können.

Bei WIdentifikationgets empfehlen wir häufig, die Symbole anzupassen oder die gewünschten Bilder zu platzieren.

# Die Vorlagen

Es gibt zwei Arten von Vorlagen :

- Das "einfache" : Geben Sie ein Symbol / Bild für das "Ein" und ein Symbol / Bild für das "Aus" ein"
- Die "MultiZustands" : Auf diese Weise können Sie beispielsweise ein Bild definieren, wenn der Befehl den Wert "XX" und ein anderes if> bis "YY" und erneut if <bis "ZZ" hat". Oder sogar ein Bild, wenn der Wert &quot;toto&quot; ist, ein anderes, wenn es &quot;plop&quot; ist und so weiter.

# Wie es geht ?

Klicken Sie auf der Seite Extras -&gt; WIdentifikationget auf &quot;Hinzufügen&quot; und geben Sie Ihrem neuen WIdentifikationget einen Namen.

Dann :
- Sie wählen, ob es sich um eine Aktion oder einen Auftragstyp handelt
- Abhängig von Ihrer vorherigen Auswahl müssen Sie den Subtyp des Befehls auswählen (binär, digital, andere ...).
- Dann endlich die fragliche Vorlage (wir planen, Ihnen Beispiele für Renderings für jede Vorlage zu geben)
- Sobald die Vorlage ausgewählt wurde, bietet Ihnen jeedom die Möglichkeit, sie zu konfigurieren

## Ersatz

Dies ist, was wir ein einfaches WIdentifikationget nennen. Hier muss man nur sagen, dass das &quot;Ein&quot; einem solchen Symbol / Bild entspricht (mit der Schaltfläche auswählen), das &quot;Aus&quot; ist das ec. Abhängig von der Vorlage werden Sie dann möglicherweise auch nach der Breite und Höhe gefragt.. Dies gilt nur für Bilder.

>**Notiz**
>
>Wir entschuldigen uns für die Namen in Englisch, dies ist eine Einschränkung des Vorlagensystems. Diese Wahl garantiert eine gewisse Geschwindigkeit und Effizienz, sowohl für Sie als auch für uns.. Wir hatten keine Wahl

>**Tipps**
>
>Für fortgeschrittene Benutzer ist es möglich, in den Ersatzwerten Tags einzufügen und ihren Wert in der erweiterten Konfiguration des Befehls, der Registerkarte "Anzeige" und des WIdentifikationgets "Optionale Einstellungen" anzugeben". Wenn Sie beispielsweise in wIdentifikationth als Wert # wIdentifikationth # (achten Sie darauf, das # herum zu setzen) anstelle einer Zahl eingeben, können Sie in &quot;Optionale WIdentifikationget-Einstellungen&quot; wIdentifikationth (ohne #) hinzufügen und den Wert angeben. Auf diese Weise können Sie die Größe des Bildes abhängig von der Reihenfolge ändern und können daher nicht für jede gewünschte Bildgröße ein anderes WIdentifikationget erstellen.

## Test

Dies wird als mehrstufiger Teil bezeichnet. Bei einfachen WIdentifikationgets haben Sie häufig die Wahl zwischen &quot;Höhe&quot; / &quot;Breite&quot; für die Bilder nur dann unterhalb des Testteils.

Es ist ganz einfach. Anstatt wie im vorherigen Fall ein Bild für &quot;Ein&quot; und / oder &quot;Aus&quot; zu setzen, müssen Sie einen Test durchführen. Wenn dies zutrifft, zeigt das WIdentifikationget das betreffende Symbol / Bild an.

Die Tests sind in der Form : #Wert # == 1, # Wert # wird vom System automatisch durch den aktuellen Wert der Bestellung ersetzt. Sie können zum Beispiel auch tun :

- #Wert #&gt; 1
- #value# >= 1 && #value# <= 5
- #Wert # == &#39;toto'

>**Notiz**
>
>Es ist wichtig, das &#39;um den Text herum zu notieren, um zu vergleichen, ob der Wert ein Text ist

>**Notiz**
>
>Für fortgeschrittene Benutzer ist es hier möglich, auch Javascript-Funktionen vom Typ #value zu verwenden#.match (&quot;^ plop&quot;), hier testen wir, ob der Text mit plop beginnt

>**Notiz**
>
>Es ist möglich, den Wert des Befehls im WIdentifikationget anzuzeigen, indem Sie beispielsweise neben den HTML-Code des Symbols #value setzen#

# Beschreibung der WIdentifikationgets

Wir werden hier einige WIdentifikationgets beschreiben, die eine ganz bestimmte Funktion haben.

## Häufige Einstellungen

- Zeit-WIdentifikationget : Zeigt die Zeit an, seit der sich das System im Anzeigezustand befindet.
- Ein : Symbol zur Anzeige, wenn das Gerät eingeschaltet ist / 1
- Aus : Symbol zur Anzeige, wenn das Gerät ausgeschaltet ist / 0
- Licht an : Symbol zur Anzeige, wenn das Gerät eingeschaltet ist / 1 und das Thema hell ist (wenn leer, nimmt jeedom das dunkle Bild an)
- Licht aus : Symbol zur Anzeige, wenn das Gerät ausgeschaltet ist / 0 und das Thema hell ist (wenn leer, nimmt jeedom das dunkle Bild aus)
- Dunkel an : Symbol zur Anzeige, wenn das Gerät eingeschaltet ist / 1 und das Thema dunkel ist (wenn leer, schaltet jeedom das Licht ein)
- Dunkel ab : Symbol zur Anzeige, wenn das Gerät ausgeschaltet ist / 0 und das Thema dunkel ist (wenn leer, schaltet jeedom das Licht aus)
- Desktop-Breite : Breite des Bildes auf dem Desktop in px (geben Sie einfach die Zahl und nicht die px ein). Wichtig, dass nur die Breite erforderlich ist. Jeedom berechnet die Höhe, um das Bild nicht zu verzerren
- Bewegliche Breite : Breite des Bildes auf dem Handy in px (geben Sie einfach die Zahl und nicht die px ein). Wichtig, dass nur die Breite erforderlich ist. Jeedom berechnet die Höhe, um das Bild nicht zu verzerren

## Hygrothermograph

Dieses WIdentifikationget ist etwas Besonderes, da es sich um ein WIdentifikationget mit mehreren Befehlen handelt, dh, es stellt auf seiner Anzeige den Wert mehrerer Befehle zusammen. Hier nimmt er Temperatur- und Feuchtigkeitsbefehle entgegen.

Um es zu konfigurieren, müssen Sie das WIdentifikationget ganz einfach der Temperaturregelung Ihres Geräts und der Feuchtigkeitsregelung zuweisen.

>**WICHTIG**
>
>Es ist ABSOLUT erforderlich, dass Ihre Bestellungen die generische Temperatur für die Temperatur- und Feuchtigkeitsregelung für die Feuchtigkeitsregelung haben (dies wird in der erweiterten Konfiguration der Konfiguration der Befehlsregisterkarte konfiguriert)..

Das WIdentifikationget verfügt über einen optionalen Parameter : Skala, mit der Sie die Größe ändern können, indem Sie beispielsweise die Skala auf 0 setzen.5 es wird 2 mal kleiner sein

>**Notiz**
>
> Aufmerksamkeit für ein Design Es ist besonders wichtig, mit diesem WIdentifikationget keine Bestellung aufzugeben, die nicht funktioniert, da es sich um ein WIdentifikationget handelt, das den Wert mehrerer Bestellungen verwendet. Es ist unbedingt erforderlich, das vollständige WIdentifikationget zu platzieren

## Schieberegler

- Schritt : Ermöglicht das Anpassen des Aktionsschritts auf einer Schaltfläche (0).5 standardmäßig)

## Kompass

- Nadel : Für die Anzeige im Kompassmodus auf 1 setzen

# Code-WIdentifikationget

## Tags

Im Codemodus haben Sie Zugriff auf verschiedene Tags für Bestellungen. Hier ist eine Liste (nicht unbedingt vollständig). :

- #Name# : BefehlsName
- #valueName# : Name des Befehlswertes und = # Name #, wenn es sich um einen Befehl vom Typ Info handelt
- #hIdentifikatione_Name# : leer oder ausgeblendet, wenn der Benutzer den Namen des WIdentifikationgets ausblenden möchte, um es direkt in ein Klassen-Tag einzufügen
- #Identifikation# : Bestellnummer
- #Zustand# : Wert des Befehls, leer für einen Aktionstypbefehl, wenn er nicht mit einem Statusbefehl verknüpft ist
- #uIdentifikation# : eindeutige Kennung für diese Generation des WIdentifikationgets (wenn es mehrmals denselben Befehl gibt, ist bei Entwürfen nur diese Kennung wirklich eindeutig)
- #valueDate# : Datum des Bestellwertes
- #collectDate# : Datum der Auftragserfassung
- #alertLevel# : Alarmstufe (siehe [hier] (https:// github.com/jeedom/core/blob/alpha/core/config/jeedom.config.PHP # L67) für die Liste)
- #hIdentifikatione_history# : ob der Verlauf (max, min, Durchschnitt, Trend) ausgeblendet werden soll oder nicht. # HIdentifikatione_Name # ist leer oder versteckt und kann daher direkt in einer Klasse verwendet werden. WICHTIG Wenn dieses Tag nicht in Ihrem WIdentifikationget gefunden wird, werden die Tags # minHistoryValue #, # durchschnittlichHistoryValue #, # maxHistoryValue # und # trend # nicht durch Jeedom ersetzt.
- #minHistoryValue# : Mindestwert über den Zeitraum (Zeitraum, der vom Benutzer in der Konfiguration von jeedom definiert wurde)
- #averageHistoryValue# : Durchschnittswert über den Zeitraum (Zeitraum, der in der Konfiguration von jeedom durch den Benutzer definiert wurde)
- #maxHistoryValue# : Maximalwert über den Zeitraum (Zeitraum, der vom Benutzer in der Konfiguration von jeedom definiert wurde)
- #Trend# : Trend über den Zeitraum (Zeitraum, den der Benutzer in der Konfiguration von jeedom definiert hat). Achtung, der Trend ist direkt eine Klasse für Symbole : fas fa-Pfeil nach oben, fas fa-Pfeil nach unten oder fas fa-minus

## Werte aktualisieren

Wenn ein neuer Wert jeedom auf der Webseite angezeigt wird, wenn der Befehl vorhanden ist, und in jeedom.cmd.Update, wenn eine Update-Funktion vorhanden ist. Wenn ja, wird es mit einem einzelnen Argument aufgerufen, das ein Objekt im Formular ist :

```
{display_value:'#Zustand # ‚valueDate:'#valueDate # ‚collectDate:'#collectDate # ‚alertLevel:'#alertLevel#'}
```

Hier ist ein einfaches Beispiel für Javascript-Code, den Sie in Ihr WIdentifikationget einfügen können :

```
<script>
    jeedom.cmd.update [&#39;# Identifikation #&#39;] = function (_options){
      $('.cmd[data-cmd_Identifikation=#Identifikation#]').attr('title','Date de valeur : '+_options.valueDate+'<br/>Date de collecte : '+_options.collectDate)
      $('.cmd[data-cmd_Identifikation=#Identifikation#] .Zustand').empty().append(_options.display_value +' #unite#');
    }
    jeedom.cmd.update [ &#39;# Identifikation #&#39;] ({display_value:'#Zustand # ‚valueDate:'#valueDate # ‚collectDate:'#collectDate # ‚alertLevel:'#alertLevel # ‚});
</script>
```

Hier 2 wichtige Sache :

```
jeedom.cmd.update [&#39;# Identifikation #&#39;] = function (_options){
  $('.cmd[data-cmd_Identifikation=#Identifikation#]').attr('title','Date de valeur : '+_options.valueDate+'<br/>Date de collecte : '+_options.collectDate)
  $('.cmd[data-cmd_Identifikation=#Identifikation#] .Zustand').empty().append(_options.display_value +' #unite#');
}
```

Die Funktion, die beim Aktualisieren des WIdentifikationgets aufgerufen wird und die Aktualisierung des HTML-Codes der WIdentifikationget-Vorlage übernimmt

Und :

```
jeedom.cmd.update [ &#39;# Identifikation #&#39;] ({display_value:'#Zustand # ‚valueDate:'#valueDate # ‚collectDate:'#collectDate # ‚alertLevel:'#alertLevel # ‚});
 ```

 Der Aufruf dieser Funktion zur Initialisierung des WIdentifikationgets.

 Sie finden [hier] (https:// github.com / jeedom / core / tree / V4-stabile / core / template) Beispiele für WIdentifikationgets (im Dashboard und in mobilen Ordnern)
