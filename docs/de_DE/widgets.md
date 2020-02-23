# WIdentifikationgets
**Extras → WIdentifikationgets**

Auf der WIdentifikationgets-Seite können Sie benutzerdefinierte WIdentifikationgets für Ihr Jeedom erstellen.

Es gibt zwei Arten von benutzerdefinierten WIdentifikationgets :

- WIdentifikationgets basierend auf einer Vorlage (verwaltet vom Jeedom Core).
- WIdentifikationgets basierend auf Benutzercode.

> **Notiz**
>
> Wenn vorlagenbasierte WIdentifikationgets in den Core integriert und daher vom Entwicklungsteam überwacht werden, kann letzteres die Kompatibilität von WIdentifikationgets basierend auf Benutzercode gemäß Jeedom-Entwicklungen nicht sicherstellen.

## Management

Sie haben vier Möglichkeiten :
- **hinzufügen** : Ermöglicht das Erstellen eines neuen WIdentifikationgets.
- **Import** : Ermöglicht das Importieren eines WIdentifikationgets als zuvor exportierte JSON-Datei.
- **Code** : Öffnet einen Datei-Editor zum Bearbeiten von Code-WIdentifikationgets.
- **Ersatz** : Öffnet ein Fenster, in dem Sie ein WIdentifikationget auf allen Geräten, die es verwenden, durch ein anderes ersetzen können.

## Meine WIdentifikationgets

Sobald Sie ein WIdentifikationget erstellt haben, wird es in diesem Teil angezeigt.

> **Spitze**
>
> Sie können ein WIdentifikationget öffnen, indem Sie dies tun :
> - Klicken Sie auf eine davon.
> - Strg Clic oder Clic Center, um es in einem neuen Browser-Tab zu öffnen.

Sie haben eine Suchmaschine, um die Anzeige von WIdentifikationgets zu filtern. Die Escape-Taste bricht die Suche ab.
Rechts neben dem Suchfeld befinden sich drei Schaltflächen, die an mehreren Stellen in Jeedom gefunden wurden:
- Das Kreuz, um die Suche abzubrechen.
- Der geöffnete Ordner zum Entfalten aller Bedienfelder und Anzeigen aller WIdentifikationgets.
- Der geschlossene Ordner zum Falten aller Panels.

Sobald Sie ein WIdentifikationget konfiguriert haben, haben Sie ein Kontextmenü mit der rechten Maustaste auf die Registerkarten des WIdentifikationgets. Sie können auch ein Strg-Klick- oder Clic-Center verwenden, um ein anderes WIdentifikationget direkt in einer neuen Browser-Registerkarte zu öffnen.


## Prinzip

Aber was ist eine Vorlage ?
Einfach ausgedrückt ist es Code (hier html / js), der in den Core integriert ist. Einige Teile davon können vom Benutzer über die grafische Oberfläche des Core konfiguriert werden.

Abhängig von der Art des WIdentifikationgets können Sie im Allgemeinen Symbole anpassen oder Bilder Ihrer Wahl einfügen.

## Die Vorlagen

Es gibt zwei Arten von Vorlagen :

- Die &quot;**einfach**&quot; : Geben Sie ein Symbol / Bild für das &quot;Ein&quot; und ein Symbol / Bild für das &quot;Aus&quot; ein.
- Die &quot;**multiZustands**&quot; : Auf diese Weise können Sie beispielsweise ein Bild definieren, wenn der Befehl den Wert &quot;XX&quot; hat, und ein anderes, wenn&gt; bis &quot;YY&quot; und erneut, wenn &lt;bis &quot;ZZ&quot;.. Oder sogar ein Bild, wenn der Wert &quot;toto&quot; ist, ein anderes, wenn &quot;plop&quot;, und so weiter.

## WIdentifikationget erstellen

Klicken Sie auf der Seite Extras -&gt; WIdentifikationget auf &quot;Hinzufügen&quot; und geben Sie Ihrem neuen WIdentifikationget einen Namen.

dann :
- Sie wählen, ob es sich um eine Aktion oder einen Auftragstyp handelt.
- Abhängig von Ihrer vorherigen Auswahl müssen Sie den Subtyp des Befehls auswählen (binär, digital, andere ...)..
- Dann endlich die fragliche Vorlage (wir planen, Ihnen Beispiele für Renderings für jede Vorlage zu geben).
- Sobald die Vorlage ausgewählt wurde, bietet Jeedom Ihnen die Optionen zum Konfigurieren.

### Ersatz

Dies wird als einfaches WIdentifikationget bezeichnet. Hier muss man nur sagen, dass das &quot;Ein&quot; einem solchen Symbol / Bild entspricht (mit der Schaltfläche auswählen), das &quot;Aus&quot; ist dieses usw.. Abhängig von der Vorlage können Ihnen dann die Breite und die Höhe angeboten werden. Dies gilt nur für Bilder.

>**Notiz**
>Wir entschuldigen uns für die Namen in Englisch, dies ist eine Einschränkung des Vorlagensystems. Diese Wahl garantiert eine gewisse Geschwindigkeit und Effizienz, sowohl für Sie als auch für uns.. Wir hatten keine Wahl

>**TIPS**
>Für fortgeschrittene Benutzer ist es möglich, in den Ersatzwerten Tags zu setzen und ihren Wert in der erweiterten Konfiguration des Befehls, der Registerkarte &quot;Anzeige&quot; und &quot;Optionale WIdentifikationget-Einstellungen&quot; anzugeben.. Wenn Sie beispielsweise in wIdentifikationth als Wert # wIdentifikationth # (achten Sie darauf, das # herum zu setzen) anstelle einer Zahl eingeben, können Sie in &quot;Optionale WIdentifikationget-Einstellungen&quot; wIdentifikationth (ohne #) hinzufügen und den Wert angeben. Auf diese Weise können Sie die Bildgröße entsprechend der Reihenfolge ändern und können daher nicht für jede gewünschte Bildgröße ein anderes WIdentifikationget erstellen

### Test

Dies wird als mehrstufiger Teil bezeichnet. Bei einfachen WIdentifikationgets haben Sie häufig die Wahl zwischen &quot;Höhe&quot; / &quot;Breite&quot; für die Bilder nur dann unterhalb des Testteils.

Es ist ganz einfach. Anstatt wie im vorherigen Fall ein Bild für &quot;Ein&quot; und / oder &quot;Aus&quot; zu setzen, müssen Sie einen Test durchführen. Wenn dies zutrifft, zeigt das WIdentifikationget das betreffende Symbol / Bild an.

Die Tests sind in der Form : #Wert # == 1, # Wert # wird vom System automatisch durch den aktuellen Wert der Bestellung ersetzt. Sie können zum Beispiel auch tun :

- #Wert #&gt; 1
- #value# >= 1 && #value# <= 5
- #Wert # == &#39;toto&#39;

>**Notiz**
>Es ist wichtig, das &#39;um den Text herum zu notieren, um zu vergleichen, ob der Wert ein Text ist

>**Notiz**
>Für fortgeschrittene Benutzer ist es hier möglich, auch Javascript-Funktionen vom Typ #value zu verwenden#.match (&quot;^ plop&quot;), hier testen wir, ob der Text mit plop beginnt

>**Notiz**
>Es ist möglich, den Wert des Befehls im WIdentifikationget anzuzeigen, indem Sie beispielsweise neben den HTML-Code des Symbols #value setzen#

## Beschreibung der WIdentifikationgets

Wir werden hier einige WIdentifikationgets beschreiben, die eine ganz bestimmte Funktion haben.

### Häufige Einstellungen

- Zeit-WIdentifikationget : Zeigt die Zeit an, seit sich das System im Anzeigezustand befindet.
- ein : Symbol zur Anzeige, wenn das Gerät eingeschaltet ist / 1.
- aus : Symbol zur Anzeige, wenn das Gerät ausgeschaltet ist / 0.
- Licht an : Symbol zur Anzeige, wenn das Gerät eingeschaltet ist / 1 und das Thema hell ist (wenn leer, nimmt Jeedom das dunkle Bild an).
- Licht aus : Symbol zur Anzeige, wenn das Gerät ausgeschaltet ist / 0 und das Thema hell ist (wenn leer, nimmt Jeedom das dunkle Bild aus).
- Dunkel an : Symbol zur Anzeige, wenn das Gerät eingeschaltet ist / 1 und das Thema dunkel ist (wenn leer, schaltet Jeedom das Bild ein).
- Dunkel ab : Symbol zur Anzeige, wenn das Gerät ausgeschaltet ist / 0 und das Thema dunkel ist (wenn leer, schaltet Jeedom das Licht aus).
- Desktop-Breite : Breite des Bildes auf dem Desktop in px (geben Sie einfach die Zahl und nicht die px ein). Wichtig, dass nur die Breite erforderlich ist. Jeedom berechnet die Höhe, um das Bild nicht zu verzerren.
- Bewegliche Breite : Breite des Bildes auf dem Handy in px (geben Sie einfach die Zahl und nicht die px ein). Wichtig, dass nur die Breite erforderlich ist. Jeedom berechnet die Höhe, um das Bild nicht zu verzerren.

### Hygrothermograph

Dieses WIdentifikationget ist etwas Besonderes, da es sich um ein WIdentifikationget mit mehreren Befehlen handelt, dh, es stellt auf seiner Anzeige den Wert mehrerer Befehle zusammen. Hier nimmt er Temperatur- und Feuchtigkeitsbefehle entgegen.

Um es zu konfigurieren, müssen Sie das WIdentifikationget ganz einfach der Temperaturregelung Ihres Geräts und der Feuchtigkeitsregelung zuweisen.

>**WICHTIG**
>Es ist ABSOLUT erforderlich, dass Ihre Bestellungen die generische Temperatur für die Temperaturregelung und die Luftfeuchtigkeit für die Feuchtigkeitsregelung haben (dies wird in der erweiterten Konfiguration der Befehlsregisterkonfiguration konfiguriert)..

Das WIdentifikationget verfügt über einen optionalen Parameter : Skala, mit der Sie die Größe ändern können, indem Sie beispielsweise die Skala auf 0 setzen.5 es wird 2 mal kleiner sein

>**HINWEIS**
> Aufmerksamkeit für ein Design Es ist besonders wichtig, mit diesem WIdentifikationget keine Bestellung aufzugeben, die nicht funktioniert, da es sich um ein WIdentifikationget handelt, das den Wert mehrerer Bestellungen verwendet. Es ist unbedingt erforderlich, das vollständige WIdentifikationget zu platzieren

### mehrzeilige

- MaxHeight-Parameter zum Definieren der maximalen Höhe (Bildlaufleiste an der Seite, wenn der Text diesen Wert überschreitet)

### Schieberegler

- Schritt : Ermöglicht das Anpassen des Aktionsschritts auf einer Schaltfläche (0).5 standardmäßig)

## Code-WIdentifikationget

### Tags

Im Codemodus haben Sie Zugriff auf verschiedene Tags für Bestellungen. Hier ist eine Liste (nicht unbedingt vollständig). :

- #Name# : BefehlsName
- #valueName# : Name des Befehlswertes und = # Name #, wenn es sich um einen Befehl vom Typ Info handelt
- #hIdentifikatione_Name# : leer oder ausgeblendet, wenn der Benutzer den Namen des WIdentifikationgets ausblenden möchte, um es direkt in ein Klassen-Tag einzufügen
- #Identifikation# : Bestellnummer
- #Zustand# : Wert des Befehls, leer für einen Aktionstypbefehl, wenn er nicht mit einem Statusbefehl verknüpft ist
- #uIdentifikation# : eindeutige Kennung für diese Generation des WIdentifikationgets (wenn es mehrmals denselben Befehl gibt, ist bei Entwürfen nur diese Kennung wirklich eindeutig)
- #valueDate# : Datum des Bestellwertes
- #collectDate# : Datum der Auftragserfassung
- #alertLevel# : Alarmstufe (siehe [hier] (https:// github.com/Jeedom/core/blob/alpha/core/config/Jeedom.config.PHP # L67) für die Liste)
- #hIdentifikatione_history# : ob der Verlauf (max, min, Durchschnitt, Trend) ausgeblendet werden soll oder nicht. # HIdentifikatione_Name # ist leer oder versteckt und kann daher direkt in einer Klasse verwendet werden. WICHTIG Wenn dieses Tag nicht in Ihrem WIdentifikationget gefunden wird, werden die Tags # minHistoryValue #, # durchschnittlichHistoryValue #, # maxHistoryValue # und # trend # nicht durch Jeedom ersetzt.
- #minHistoryValue# : Mindestwert über den Zeitraum (Zeitraum, den der Benutzer in der Jeedom-Konfiguration definiert hat)
- #averageHistoryValue# : Durchschnittswert über den Zeitraum (Zeitraum, den der Benutzer in der Jeedom-Konfiguration definiert hat)
- #maxHistoryValue# : Maximalwert über den Zeitraum (Zeitraum, den der Benutzer in der Jeedom-Konfiguration definiert hat)
- #Trend# : Trend über den Zeitraum (Zeitraum, den der Benutzer in der Jeedom-Konfiguration definiert hat). Achtung, der Trend ist direkt eine Klasse für Symbole : fas fa-Pfeil nach oben, fas fa-Pfeil nach unten oder fas fa-minus

### Werte aktualisieren

Wenn ein neuer Wert Jeedom auf der Webseite angezeigt wird, wenn der Befehl vorhanden ist, und in Jeedom.cmd.Update, wenn eine Update-Funktion vorhanden ist. Wenn ja, wird es mit einem einzelnen Argument aufgerufen, das ein Objekt im Formular ist :

```
{display_value:&#39;#State #&#39; valueDate:&#39;#ValueDate #&#39; collectDate:&#39;#CollectDate #&#39; alertLevel:&#39;# # AlertLevel&#39;}
```

Hier ist ein einfaches Beispiel für Javascript-Code, den Sie in Ihr WIdentifikationget einfügen können :

```
<script>
    Jeedom.cmd.update [&#39;# Identifikation #&#39;] = function (_options) {
      $ (&#39;. cmd [data-cmd_Identifikation = # Identifikation #]&#39;). attr (&#39;title&#39;, &#39;Wertdatum : ‚+ _Options.valueDate + &#39; <br/> Datum der Abholung : ‚+ _Options.collectDate)
      $ (&#39;. cmd [data-cmd_Identifikation = # Identifikation #] .Zustand&#39;). empty (). append (_options.display_value + &#39;# unit #&#39;);
    }
    Jeedom.cmd.update [ &#39;# Identifikation #&#39;] ({display_value:&#39;#State #&#39; valueDate:&#39;#ValueDate #&#39; collectDate:&#39;#CollectDate #&#39; alertLevel:&#39;# AlertLevel #&#39;});
</script>
```

Hier sind zwei wichtige Dinge :

```
Jeedom.cmd.update [&#39;# Identifikation #&#39;] = function (_options) {
  $ (&#39;. cmd [data-cmd_Identifikation = # Identifikation #]&#39;). attr (&#39;title&#39;, &#39;Wertdatum : ‚+ _Options.valueDate + &#39; <br/> Datum der Abholung : ‚+ _Options.collectDate)
  $ (&#39;. cmd [data-cmd_Identifikation = # Identifikation #] .Zustand&#39;). empty (). append (_options.display_value + &#39;# unit #&#39;);
}
```
Die Funktion, die beim Aktualisieren des WIdentifikationgets aufgerufen wird. Anschließend wird der HTML-Code der WIdentifikationget-Vorlage aktualisiert.

```
Jeedom.cmd.update [ &#39;# Identifikation #&#39;] ({display_value:&#39;#State #&#39; valueDate:&#39;#ValueDate #&#39; collectDate:&#39;#CollectDate #&#39; alertLevel:&#39;# AlertLevel #&#39;});
 ```
 Der Aufruf dieser Funktion zur Initialisierung des WIdentifikationgets.

 Sie finden [hier] (https:// github.com / Jeedom / core / tree / V4-stabile / core / template) Beispiele für WIdentifikationgets (im Dashboard und in mobilen Ordnern)
