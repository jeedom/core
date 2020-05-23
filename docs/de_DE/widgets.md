# Widgets
**Extras → Widgets**

Auf der Widgets-Seite können Sie benutzerdefinierte Widgets für Ihr Jeedom erstellen.

Es gibt zwei Arten von benutzerdefinierten Widgets :

- Widgets basierend auf einer Vorlage (verwaltet vom Jeedom Core).
- Widgets basierend auf Benutzercode.

> **Notiz**
>
> Wenn vorlagenbasierte Widgets in den Core integriert und daher vom Entwicklungsteam überwacht werden, kann letzteres die Kompatibilität von Widgets basierend auf Benutzercode gemäß Jeedom-Entwicklungen nicht sicherstellen.

## Gestion

Sie haben vier Möglichkeiten :
- **Hinzufügen** : Ermöglicht das Erstellen eines neuen Widgets.
- **Import** : Ermöglicht das Importieren eines Widgets als zuvor exportierte JSON-Datei.
- **Code** : Öffnet einen Datei-Editor zum Bearbeiten von Code-Widgets.
- **Ersatz** : Öffnet ein Fenster, in dem Sie ein Widget auf allen Geräten, die es verwenden, durch ein anderes ersetzen können.

## Meine Widgets

Sobald Sie ein Widget erstellt haben, wird es in diesem Teil angezeigt.

> **Spitze**
>
> Sie können ein Widget öffnen, indem Sie dies tun :
> - Klicken Sie auf eine davon.
> - Strg Clic oder Clic Center, um es in einem neuen Browser-Tab zu öffnen.

Sie haben eine Suchmaschine, um die Anzeige von Widgets zu filtern. Die Escape-Taste bricht die Suche ab.
Rechts neben dem Suchfeld befinden sich drei Schaltflächen, die an mehreren Stellen in Jeedom gefunden wurden:

- Das Kreuz, um die Suche abzubrechen.
- Der geöffnete Ordner zum Entfalten aller Bedienfelder und Anzeigen aller Widgets.
- Der geschlossene Ordner zum Falten aller Panels.

Sobald Sie ein Widget konfiguriert haben, haben Sie ein Kontextmenü mit der rechten Maustaste auf die Registerkarten des Widgets. Sie können auch ein Strg-Klick- oder Clic-Center verwenden, um ein anderes Widget direkt in einer neuen Browser-Registerkarte zu öffnen.


## Principe

Aber was ist eine Vorlage ?
Einfach ausgedrückt ist es Code (hier html / js), der in den Core integriert ist. Einige Teile davon können vom Benutzer über die grafische Oberfläche des Core konfiguriert werden.

Abhängig von der Art des Widgets können Sie im Allgemeinen Symbole anpassen oder Bilder Ihrer Wahl einfügen.

## Die Vorlagen

Es gibt zwei Arten von Vorlagen :

- Die "**einfach**" : Geben Sie ein Symbol / Bild für das "Ein" und ein Symbol / Bild für das "Aus" ein"
- Die "**multistate**" : Auf diese Weise können Sie beispielsweise ein Bild definieren, wenn der Befehl auf "XX" gesetzt ist, und ein anderes, wenn> auf "YY" und erneut, wenn <auf "ZZ"". Oder sogar ein Bild, wenn der Wert &quot;toto&quot; ist, ein anderes, wenn &quot;plop&quot;, und so weiter.

## Widget erstellen

Klicken Sie auf der Seite Extras -&gt; Widget auf &quot;Hinzufügen&quot; und geben Sie Ihrem neuen Widget einen Namen.

Dann :
- Sie wählen, ob es sich um eine Aktion oder einen Auftragstyp handelt.
- Abhängig von Ihrer vorherigen Auswahl müssen Sie den Subtyp des Befehls auswählen (binär, digital, andere...).
- Dann endlich die fragliche Vorlage (wir planen, Beispiele für Renderings für jede Vorlage zu erstellen).
- Sobald die Vorlage ausgewählt wurde, bietet Ihnen jeedom die Möglichkeit, sie zu konfigurieren.

### Remplacement

Dies wird als einfaches Widget bezeichnet. Hier muss man nur sagen, dass das &quot;Ein&quot; einem solchen Symbol / Bild entspricht (mit der Schaltfläche auswählen), das &quot;Aus&quot; ist dieses usw. Abhängig von der Vorlage können Ihnen dann die Breite (Breite) und die Höhe (Höhe) angeboten werden). Dies gilt nur für Bilder.

>**Notiz**
>Wir entschuldigen uns für die Namen in Englisch, dies ist eine Einschränkung des Vorlagensystems. Diese Wahl garantiert eine gewisse Geschwindigkeit und Effizienz, sowohl für Sie als auch für uns. Wir hatten keine Wahl

>**Tipps**
>Für fortgeschrittene Benutzer ist es möglich, in den Ersatzwerten Tags zu setzen und ihren Wert in der erweiterten Konfiguration des Befehls, der Registerkartenanzeige und des Widgets "Optionale Parameter" anzugeben". Zum Beispiel, wenn Sie in der Breite als Wert eingeben #width# (Achten Sie darauf, die # autour) au lieu d'un chiffre, dans "Paramètres optionnels widget" vous pouvez ajouter width (sans les #) und geben Wert. Auf diese Weise können Sie die Größe des Bildes abhängig von der Reihenfolge ändern und können daher nicht für jede gewünschte Bildgröße ein anderes Widget erstellen

### Test

Dies wird als mehrstufiger Teil bezeichnet. Bei einfachen Widgets haben Sie häufig die Wahl zwischen &quot;Höhe&quot; / &quot;Breite&quot; für die Bilder nur dann unterhalb des Testteils.

Es ist ganz einfach. Anstatt wie im vorherigen Fall ein Bild für &quot;Ein&quot; und / oder &quot;Aus&quot; zu setzen, müssen Sie einen Test durchführen. Wenn dies zutrifft, zeigt das Widget das betreffende Symbol / Bild an.

Die Tests sind in der Form : #value# == 1, #value# wird vom System automatisch durch den aktuellen Wert der Bestellung ersetzt. Sie können zum Beispiel auch tun :

- #value# > 1
- #value# >= 1 && #value# <= 5
- #value# == 'toto'

>**Notiz**
>Es ist wichtig, das &#39;um den Text herum zu notieren, um zu vergleichen, ob der Wert ein Text ist

>**Notiz**
>Für fortgeschrittene Benutzer ist es hier auch möglich, Funktionen vom Typ Javascript zu verwenden #value#.match (&quot;^ plop&quot;), hier testen wir, ob der Text mit plop beginnt

>**Notiz**
>Es ist möglich, den Wert des Befehls im Widget anzuzeigen, indem Sie beispielsweise neben den HTML-Code des Symbols setzen #value#

## Beschreibung der Widgets

Wir werden hier einige Widgets beschreiben, die eine etwas bestimmte Funktion haben.

### Equipement

Die Geräte haben bestimmte Konfigurationsparameter :

- dashboard_class / mobile_class : Ermöglicht das Hinzufügen einer Klasse zur Ausrüstung. Zum Beispiel col2 für Geräte in der mobilen Version, mit der die Breite des Widgets verdoppelt werden kann

### Häufige Einstellungen

- Zeit-Widget : Zeigt die Zeit an, seit der sich das System im Anzeigezustand befindet.
- Ein : Symbol zur Anzeige, wenn das Gerät eingeschaltet ist / 1.
- Aus : Symbol zur Anzeige, wenn das Gerät ausgeschaltet ist / 0.
- Licht an : Symbol, das angezeigt wird, wenn das Gerät eingeschaltet ist / 1 und das Thema hell ist (wenn es leer ist, nimmt Jeedom das dunkle Bild an).
- Licht aus : Symbol zur Anzeige, wenn das Gerät ausgeschaltet ist / 0 und das Thema hell ist (wenn leer, schaltet Jeedom das Bild dunkel aus).
- Dunkel an : Symbol zur Anzeige, wenn das Gerät eingeschaltet ist / 1 und das Thema dunkel ist (wenn leer, schaltet Jeedom das Bild ein).
- Dunkel ab : Symbol zur Anzeige, wenn das Gerät ausgeschaltet ist / 0 und das Thema dunkel ist (wenn leer, schaltet Jeedom das Licht aus).
- Desktop-Breite : Breite des Bildes auf dem Desktop in px (geben Sie einfach die Zahl und nicht die px ein). Wichtig ist, dass nur die Breite angefordert wird. Jeedom berechnet die Höhe, um das Bild nicht zu verzerren.
- Bewegliche Breite : Breite des Bildes auf dem Handy in px (geben Sie einfach die Zahl und nicht die px ein). Wichtig ist, dass nur die Breite angefordert wird. Jeedom berechnet die Höhe, um das Bild nicht zu verzerren.

### HygroThermographe

Dieses Widget ist etwas Besonderes, da es sich um ein Widget mit mehreren Befehlen handelt, dh, es stellt auf seiner Anzeige den Wert mehrerer Befehle zusammen. Hier nimmt er Temperatur- und Feuchtigkeitsbefehle entgegen.

Um es zu konfigurieren, müssen Sie das Widget ganz einfach der Temperaturregelung Ihres Geräts und der Feuchtigkeitsregelung zuweisen.

>**WICHTIG**
>Es ist ABSOLUT erforderlich, dass Ihre Bestellungen die generische Temperatur für die Temperaturregelung und die Luftfeuchtigkeit für die Feuchtigkeitsregelung haben (dies wird in der erweiterten Konfiguration der Befehlsregisterkonfiguration konfiguriert).

##### Optionale Parameter)

- Skala : Ermöglicht das Ändern der Größe, indem Sie beispielsweise die Skalierung auf 0 setzen.5 es wird 2 mal kleiner sein.

>**Notiz**
> Achtung bei einem Entwurf Es ist wichtig, einen Befehl nicht alleine mit diesem Widget zu platzieren. Es funktioniert nicht, da es sich um ein Widget handelt, das den Wert mehrerer Befehle verwendet. Es ist unbedingt erforderlich, das vollständige Widget zu platzieren

### Multiline

##### Optionale Parameter)

- maxHeight : Ermöglicht das Definieren der maximalen Höhe (Bildlaufleiste an der Seite, wenn der Text diesen Wert überschreitet).

### Schieberegler

##### Optionale Parameter)

- Schritt : Ermöglicht das Anpassen des Aktionsschritts auf einer Schaltfläche (0).5 standardmäßig).

### Rain

##### Optionale Parameter)

- Skala : Ermöglicht das Ändern der Größe, indem Sie beispielsweise die Skalierung auf 0 setzen.5 es wird 2 mal kleiner sein.
- anzeigen Radius : Zeigt die Min / Max-Werte des Befehls an.


## Code-Widget

### Tags

Im Codemodus haben Sie Zugriff auf verschiedene Tags für Bestellungen. Hier ist eine Liste (nicht unbedingt vollständig)) :

- #name# : Befehlsname
- #valueName# : Name des Bestellwerts und = #name# wenn es sich um einen Info-Befehl handelt
- #minValue# : Mindestwert, den der Befehl annehmen kann (wenn der Befehl vom Typ Schieberegler ist)
- #maxValue# : Maximalwert, der den Befehl annehmen kann (wenn der Befehl vom Typ Schieberegler ist)
- #hide_name# : leer oder ausgeblendet, wenn der Benutzer den Namen des Widgets ausblenden möchte, um es direkt in ein Klassen-Tag einzufügen
- #id# : Bestellnummer
- #state# : Wert des Befehls, leer für einen Aktionstypbefehl, wenn er nicht mit einem Statusbefehl verknüpft ist
- #uid# : eindeutige Kennung für diese Generation des Widgets (wenn es mehrmals den gleichen Befehl gibt, bei Entwürfen:  Nur diese Kennung ist wirklich eindeutig)
- #valueDate# : Datum des Bestellwertes
- #collectDate# : Datum der Auftragserfassung
- #alertLevel# : Alarmstufe (siehe [hier](https://github.com/Jeedom/core/blob/alpha/core/config/Jeedom.config.php#L67) für die Liste)
- #hide_history# : ob der Verlauf (max, min, Durchschnitt, Trend) ausgeblendet werden soll oder nicht. Wie für die #hide_name# Es ist leer oder versteckt und kann daher direkt in einer Klasse verwendet werden. WICHTIG Wenn dieses Tag nicht in Ihrem Widget gefunden wird, dann die Tags #minHistoryValue#, #averageHistoryValue#, #maxHistoryValue# und #tendance# wird nicht durch Jeedom ersetzt.
- #minHistoryValue# : Mindestwert über den Zeitraum (Zeitraum, den der Benutzer in der Jeedom-Konfiguration definiert hat)
- #averageHistoryValue# : Durchschnittswert über den Zeitraum (Zeitraum, der vom Benutzer in der Jeedom-Konfiguration definiert wurde)
- #maxHistoryValue# : Maximalwert über den Zeitraum (Zeitraum, der vom Benutzer in der Jeedom-Konfiguration definiert wurde)
- #tendance# : Trend über den Zeitraum (Zeitraum, den der Benutzer in der Konfiguration von Jeedom definiert hat). Achtung, der Trend ist direkt eine Klasse für Symbole : fas fa-Pfeil nach oben, fas fa-Pfeil nach unten oder fas fa-minus

### Werte aktualisieren

Wenn ein neuer Wert Jeedom auf der HTML-Seite angezeigt wird, wenn der Befehl vorhanden ist und in Jeedom.cmd.Update, wenn eine Update-Funktion vorhanden ist. Wenn ja, wird es mit einem einzelnen Argument aufgerufen, das ein Objekt im Formular ist :

`` ''
{display_value:'#state#',valueDate:'#valueDate#',collectDate:'#collectDate#',alertLevel:'#alertLevel#'}
`` ''

Hier ist ein einfaches Beispiel für Javascript-Code, den Sie in Ihr Widget einfügen können :

`` ''
<script>
    Jeedom.cmd.update ['#id#'] = Funktion (_Optionen){
      $('.cmd[data-cmd_id=#id#]').attr('title','Date de valeur : '+_options.valueDate+'<br/>Datum der Abholung : '+ _options.collectDate)
      $('.cmd[data-cmd_id=#id#] .state').empty().append(_options.display_value +' #unite#');;
    }
    Jeedom.cmd.update ['#id#']({display_value:'#state#',valueDate:'#valueDate#',collectDate:'#collectDate#',alertLevel:'#alertLevel#'});;
</script>
`` ''

Hier sind zwei wichtige Dinge :

`` ''
Jeedom.cmd.update ['#id#'] = Funktion (_Optionen){
  $('.cmd[data-cmd_id=#id#]').attr('title','Date de valeur : '+_options.valueDate+'<br/>Datum der Abholung : '+ _options.collectDate)
  $('.cmd[data-cmd_id=#id#] .state').empty().append(_options.display_value +' #unite#');;
}
`` ''
Die Funktion, die beim Aktualisieren des Widgets aufgerufen wird. Anschließend wird der HTML-Code der Widget-Vorlage aktualisiert.

`` ''
Jeedom.cmd.update ['#id#']({display_value:'#state#',valueDate:'#valueDate#',collectDate:'#collectDate#',alertLevel:'#alertLevel#'});;
`` ''
 Der Aufruf dieser Funktion zur Initialisierung des Widgets.

 Sie werden finden [hier](https://github.com/Jeedom/core/tree/V4-stable/core/template) Beispiele für Widgets (in Dashboard- und mobilen Ordnern)
