# Widgets

Ein Widget ist die grafische Darstellung einer Bestellung. Jedes Widget ist spezifisch für den Typ und Subtyp des Befehls, auf den es angewendet werden muss, sowie für die Version, von der aus auf Jeedom zugegriffen wird *(Desktop oder Handy)*.

## Standard-Widgets

Bevor wir uns die Anpassung von Widgets ansehen, wollen wir uns die Möglichkeiten ansehen, die bestimmte Widgets bieten, die standardmäßig im Core Jeedom vorhanden sind.

### Équipements

Die Geräte (oder Kacheln) verfügen über bestimmte Konfigurationsparameter, auf die über die erweiterte Konfiguration des Geräts auf der Registerkarte "Anzeige" → zugegriffen werden kann "**Optionale Parameter auf der Kachel**".

##### Optionale Parameter)

- **dashboard_class / mobile_class** : Ermöglicht das Hinzufügen einer Klasse zur Ausrüstung. Zum Beispiel ermöglicht `col2` für Geräte in der mobilen Version die Verdoppelung der Breite des Widgets.

### HygroThermographe

Dieses Widget ist etwas Besonderes, da es sich um ein Widget mit mehreren Befehlen handelt, d. H. Es setzt den Wert mehrerer Befehle zusammen. Hier nimmt er Temperatur- und Feuchtigkeitsbefehle entgegen. Um es zu konfigurieren, müssen Sie das Widget den Temperatur- und Feuchtigkeitsreglern Ihrer Geräte zuweisen.

![WidgUnd HygroThermographe](./images/widgets3.png)

##### Optionale Parameter)

- **Skala** *(échelle)* : Ermöglicht das Ändern der Größe des Widgets durch Ausfüllen des Parameters **Skala** auf `0.5`, das Widget wird 2 mal kleiner sein.

>**WICHTIG**      
>Es ist ABSOLUT notwendig, dass die generischen Typen angegeben werden; `Temperatur` auf der Temperaturregelung und` Luftfeuchtigkeit` auf der Feuchtigkeitsregelung (dies wird in der erweiterten Konfiguration der Steuerung auf der Registerkarte Konfiguration konfiguriert).

>**NOTIZ**      
> Achtung bei einem Entwurf Es ist wichtig, einen Befehl nicht alleine mit diesem Widget zu platzieren. Es funktioniert nicht, da es sich um ein Widget handelt, das den Wert mehrerer Befehle verwendet. Es ist unbedingt erforderlich, das vollständige Widget zu platzieren

### Multiline

Dieses Widget wird verwendet, um den Inhalt eines info / other-Befehls in mehreren Zeilen anzuzeigen.

##### Optionale Parameter)

- **maximale Höhe** *(maximale Höhe)* : Ermöglicht das Festlegen der maximalen Höhe des Widgets (eines Aufzugs) *(scrollbar)* wird auf der Seite angezeigt, wenn der Text überschreitet).

### Schieberegler

Widget zur Aktions- / Cursorsteuerung mit Schaltfläche "**+**" und ein Knopf "**-**" Ermöglichen, präzise auf einen Wert einzuwirken.

##### Optionale Parameter)

- **Schritte** *(pas)* : Hier können Sie den Wertänderungsschritt festlegen *(Standardmäßig 0,5)*.

### Rain

Widget zur Anzeige des Wasserstandes.

![WidgUnd Rain](./images/widgets4.png)

##### Optionale Parameter)

- **Skala** *(échelle)* : Ermöglicht das Ändern der Größe des Widgets durch Ausfüllen des Parameters **Skala** auf `0.5`, das Widget wird 2 mal kleiner sein.
- **showRange** : Auf "1" setzen, um die Minimal- und Maximalwerte des Befehls anzuzeigen.
- **animieren** : Deaktivieren Sie die Animation des Widgets mit dem Wert "0".

### EIN / AUS-Symbol Umschalten

In Bezug auf Widgets für Switches *(ein / aus, ein / aus, öffnen / schließen usw...)*, Es kann als optisch ansprechender angesehen werden, nur ein Symbol anzuzeigen, das den Status des zu steuernden Geräts widerspiegelt.

Diese Möglichkeit kann sowohl mit Standard-Widgets als auch mit benutzerdefinierten Widgets verwendet werden.

Dazu müssen 2 Voraussetzungen berücksichtigt werden :

- DER **2 Aktions- / Fehlerbefehle** muss mit einer Bestellung verknüpft sein **info / binär** Hier wird der aktuelle Status des Geräts gespeichert.

>**Beispiel**      
>![ToggleLink-Widget](./images/widgets5.png)

>**Beratung**     
>Deaktivieren Sie *"Afficher"* des Befehls info / binary, der nicht angezeigt werden muss.

- Damit der Jeedom Core erkennen kann, welcher Befehl welcher Aktion entspricht, muss die folgende Benennung beachtet werden **2 Aktions- / Fehlerbefehle** :
`` ''
    'on':'on',
    'off':'off',
    'monter':'on',
    'descendre':'off',
    'ouvrir':'on',
    'ouvrirStop':'on',
    'ouvert':'on',
    'fermer':'off',
    'activer':'on',
    'desactiver':'off',
    'deaktivieren':'off',
    'lock':'on',
    'unlock':'off',
    'marche':'on',
    'arret':'off',
    'stoppen':'off',
    'stop':'off',
    'go':'on'
`` ''

>**Trick**      
>Solange der standardisierte Name lesbar bleibt, kann beispielsweise die Benennung angepasst werden *open_volet* Oder *shutter_close*, *walk_2* Und *stop_2*, usw.

## Benutzerdefinierte Widgets

Die Widgets-Seite, auf die über das Menü zugegriffen werden kann **Extras → Widgets**, Mit dieser Option können Sie zusätzlich zu den standardmäßig in Jeedom verfügbaren Widgets benutzerdefinierte Widgets hinzufügen.

Es gibt zwei Arten von benutzerdefinierten Widgets :

- Widgets *Kern* vorlagenbasiert. Diese Widgets werden vom Jeedom Core verwaltet und daher vom Entwicklungsteam überwacht. Ihre Kompatibilität ist mit zukünftigen Entwicklungen von Jeedom gewährleistet.
- Widgets *Dritte Seite* basierend auf Benutzercode. Im Gegensatz zu Core-Widgets hat das Jeedom-Entwicklungsteam keine Kontrolle über den in diese Widgets eingefügten Code. Die Kompatibilität mit zukünftigen Entwicklungen kann nicht garantiert werden. Diese Widgets müssen daher vom Benutzer gepflegt werden.

### Gestion

![Widgets](./images/widgets.png)

Sie haben vier Möglichkeiten :
- **Hinzufügen** : Ermöglicht das Hinzufügen eines Widgets *Kern*.
- **Import** : Ermöglicht das Importieren eines Widgets als zuvor exportierte JSON-Datei.
- **Code** : Rufen Sie die Widget-Bearbeitungsseite auf *Dritte Seite*.
- **Ersatz** : Öffnet ein Fenster, in dem Sie ein Widget auf allen Geräten, die es verwenden, durch ein anderes ersetzen können.

### Meine Widgets

In diesem Teil finden Sie alle Widgets, die Sie erstellt haben, nach Typ klassifiziert.

![Mes Widgets](./images/widgets1.png)

> **Trick**      
> Sie können ein Widget öffnen, indem Sie dies tun :
> - Klicken Sie auf einen von ihnen.
> - `Strg + Klick` oder` Klick + Mitte`, um es in einem neuen Browser-Tab zu öffnen.

Mit der Suchmaschine können Sie die Anzeige von Widgets nach verschiedenen Kriterien (Name, Typ, Subtyp usw.) filtern...). Die Esc-Taste bricht die Suche ab.

![Recherche Widgets](./images/widgets2.png)

Rechts neben dem Suchfeld drei Schaltflächen, die an mehreren Stellen in Jeedom zu finden sind:

- **Das Kreuz** um die Suche abzubrechen.
- **Die geöffnete Datei** um alle Bedienfelder zu entfalten und Widgets anzuzeigen.
- **Die geschlossene Datei** um alle Bedienfelder zu reduzieren und Widgets auszublenden.

Sobald Sie sich auf der Konfigurationsseite eines Widgets befinden, können Sie auf ein Kontextmenü zugreifen, indem Sie mit der rechten Maustaste auf die Registerkarten des Widgets klicken. Sie können auch Strg + Klick oder Klick + Mitte verwenden, um ein anderes Widget direkt in einem neuen Browser-Tab zu öffnen.

### Widget erstellen

Einmal auf der Seite **Extras → Widgets** Sie müssen auf die Schaltfläche klicken "**Hinzufügen**" und geben Sie Ihrem neuen Widget einen Namen.

Nachher :
- Sie wählen, ob es für eine Typreihenfolge gilt **Aktion** Oder **Information**.
- Abhängig von der vorherigen Auswahl müssen Sie **Wählen Sie den Subtyp** der Bestellung.
- Endlich **Die Vorlage** unter denen, die gemäß den vorherigen Auswahlmöglichkeiten verfügbar sein werden.
- Sobald die Vorlage ausgewählt wurde, zeigt Jeedom die Konfigurationsoptionen dafür unten an.

### Die Vorlagen

#### Definition einer Vorlage

Einfach ausgedrückt handelt es sich um Code (HTML / JS), der in den Core integriert ist und von dem einige Teile vom Benutzer über die grafische Oberfläche des Menüs konfiguriert werden können **Widgets**. Aus derselben Datenbank und unter Berücksichtigung der Elemente, die Sie in die Vorlage eingeben, generiert der Core eindeutige Widgets, die der Anzeige entsprechen, die Sie erhalten möchten.

Abhängig von der Art des Widgets können Sie die Symbole im Allgemeinen anpassen, die Bilder Ihrer Wahl einfügen und / oder HTML-Code einbetten.

Es gibt zwei Arten von Vorlagen :

- DER "**einfach**" : als Symbol / Bild für die "**Wir**" und ein Symbol / Bild für die "**Aus**".
- DER "**mehrstufig**" : Dies ermöglicht es beispielsweise, ein Bild zu definieren, wenn der Befehl den Wert hat "**XX**" und ein anderer so größer als "**JJ**" oder wenn weniger als "**ZZ**". Funktioniert auch für Textwerte, ein Bild, wenn der Wert ist "**toto**", ein anderer wenn "**plumpsen**" Und so weiter...

#### Remplacement

Dies nennt man eine einfache Vorlage, hier muss man nur sagen, dass die "**Wir**" passt zu einem solchen Symbol / Bild *(mit der Auswahltaste)*, DER "**Aus**" zu solchen anderen Symbolen / Bildern usw...      

Die Kiste **Zeit-Widget**, Wenn verfügbar, wird die Dauer seit der letzten Statusänderung unter dem Widget angezeigt.

Bei Vorlagen mit Bildern können Sie die Breite des Widgets je nach Unterstützung in Pixel konfigurieren (**Desktop-Breite** & **Bewegliche Breite**). Je nach aktivem Thema Jeedom können auch verschiedene Bilder ausgewählt werden *(hell oder dunkel)*.

>**Trick**     
>Für fortgeschrittene Benutzer ist es möglich, Tags in die Ersatzwerte einzufügen und ihren Wert in der erweiterten Konfiguration des Befehls anzugeben.    
>Wenn zum Beispiel in **Desktop-Breite** Sie setzen als Wert `#largeur_desktop#` (**Achten Sie darauf, die** `#` **autour**) puis dans la configuratiWir avancée d'une commande, onglUnd affichage → "**Paramètres optionnels widget**" vous ajoutez DER paramètre `largeur_desktop` (**sans les** `#`) und gib ihm den Wert "**90**", Dieses benutzerdefinierte Widget für diesen Befehl ist 90 Pixel breit. Auf diese Weise können Sie die Größe des Widgets an jede Bestellung anpassen, ohne jedes Mal ein bestimmtes Widget erstellen zu müssen.

#### Test

Dies wird als mehrstufige Vorlagen bezeichnet *(mehrere Staaten)*. Anstatt ein Bild für die "**Wir** und / oder für die "**Aus** Wie im vorherigen Fall weisen Sie ein Symbol entsprechend der Validierung einer Bedingung zu *(test)*. Wenn dies zutrifft, zeigt das Widget das betreffende Symbol / Bild an.

Nach wie vor können je nach dem in Jeedom aktiven Thema und der Box unterschiedliche Bilder ausgewählt werden **Zeit-Widget** Zeigt die Dauer seit der letzten Zustandsänderung an.

Die Tests sind in der Form : `#value# == 1`,`#value#`wird automatisch durch den aktuellen Wert des Befehls ersetzt. Sie können zum Beispiel auch tun :

- `#value# > 1`
- `#value# >= 1 && #value# <= 5`
- `#value# == 'toto'`

>**NOTIZ**     
>Es ist wichtig, die Apostrophe zu zeigen (**'**) um den Text herum zu vergleichen, ob der Wert Text ist *(info / andere)*.

>**NOTIZ**     
>Für fortgeschrittene Benutzer ist es auch möglich, Javascript-Funktionen wie `zu verwenden#value#.match ("^ plop") `, hier testen wir, ob der Text mit` plop` beginnt.

>**NOTIZ**     
>Sie können den Wert des Befehls im Widget anzeigen, indem Sie `angeben#value#`im HTML-Code des Tests. Um das Gerät anzuzeigen, fügen Sie `hinzu#unite#`.

## Code-Widget

### Tags

Im Codemodus haben Sie Zugriff auf verschiedene Tags für Bestellungen. Hier ist eine Liste (nicht unbedingt vollständig)) :

- **#name#** : Befehlsname
- **#valueName#** : Name des Bestellwerts und = #name# wenn es sich um einen Info-Befehl handelt
- **#minValue#** : Mindestwert, den der Befehl annehmen kann (wenn der Befehl vom Typ Schieberegler ist)
- **#maxValue#** : Maximalwert, der den Befehl annehmen kann (wenn der Befehl vom Typ Schieberegler ist)
- **#hide_name#** : leer oder ausgeblendet, wenn der Benutzer den Namen des Widgets ausblenden möchte, um es direkt in ein Klassen-Tag einzufügen
- **#id#** : Bestellnummer
- **#state#** : Wert des Befehls, leer für einen Aktionstypbefehl, wenn er nicht mit einem Statusbefehl verknüpft ist
- **#uid#** : eindeutige Kennung für diese Generation des Widgets (wenn es mehrmals den gleichen Befehl gibt, bei Entwürfen:  Nur diese Kennung ist wirklich eindeutig)
- **#valueDate#** : Datum des Bestellwertes
- **#collectDate#** : Datum der Auftragserfassung
- **#alertLevel#** : Alarmstufe (siehe [Hier](https://github.com/Jeedom/core/blob/alpha/core/config/Jeedom.config.php#L67) für die Liste)
- **#hide_history#** : ob der Verlauf (max, min, Durchschnitt, Trend) ausgeblendet werden soll oder nicht. Wie für die #hide_name# Es ist leer oder versteckt und kann daher direkt in einer Klasse verwendet werden. WICHTIG Wenn dieses Tag nicht in Ihrem Widget gefunden wird, dann die Tags #minHistoryValue#, #averageHistoryValue#, #maxHistoryValue# Und #tendance# wird nicht durch Jeedom ersetzt.
- **#minHistoryValue#** : Mindestwert über den Zeitraum (Zeitraum, den der Benutzer in der Jeedom-Konfiguration definiert hat)
- **#averageHistoryValue#** : Durchschnittswert über den Zeitraum (Zeitraum, der vom Benutzer in der Jeedom-Konfiguration definiert wurde)
- **#maxHistoryValue#** : Maximalwert über den Zeitraum (Zeitraum, der vom Benutzer in der Jeedom-Konfiguration definiert wurde)
- **#tendance#** : Trend über den Zeitraum (Zeitraum, den der Benutzer in der Konfiguration von Jeedom definiert hat). Achtung, der Trend ist direkt eine Klasse für Symbole : fas fa-Pfeil nach oben, fas fa-Pfeil nach unten oder fas fa-minus

### Werte aktualisieren

Wenn ein neuer Wert Jeedom auf der Seite sucht, wenn der Befehl vorhanden ist, und in Jeedom.cmd.Update, wenn eine Update-Funktion vorhanden ist. Wenn ja, wird es mit einem einzelnen Argument aufgerufen, das ein Objekt im Formular ist :

`` ''
{Anzeigewert: '#state#', valueDate: '#valueDate#', collectDate: '#collectDate#', alertLevel: '#alertLevel#'}
`` ''

Hier ist ein einfaches Beispiel für Javascript-Code, den Sie in Ihr Widget einfügen können :

`` ''
<script>
    jeedom.cmd.addUpdateFunction('#id#', Funktion (_Optionen) {
      if (is_object(cmd = document.querySelector('.cmd[data-cmd_id="#id#"]'))) {
        cmd.setAttribute('title', '{{Valutadatum}}: ' + _Optionen.WertDatum + '<br>{{Datum der Abholung}}: ' + _options.collectDate)
        cmd.querySelector('.value').innerHTML = _options.display_value
        cmd.querySelector('.unit').innerHTML = _options.unit
      }
    }
    jeedom.cmd.refreshValue([{ cmd_id: '#id#', value: '#value#', Anzeigewert: '#state#', valueDate: '#valueDate#', collectDate: '#collectDate#', alertLevel: '#alertLevel#', unit: '#unite#' }])
</script>
`` ''

Hier sind zwei wichtige Dinge :

`` ''
jeedom.cmd.addUpdateFunction('#id#', Funktion (_Optionen) {
  if (is_object(cmd = document.querySelector('.cmd[data-cmd_id="#id#"]'))) {
    cmd.setAttribute('title', '{{Valutadatum}}: ' + _Optionen.WertDatum + '<br>{{Datum der Abholung}}: ' + _options.collectDate)
    cmd.querySelector('.value').innerHTML = _options.display_value
    cmd.querySelector('.unit').innerHTML = _options.unit
  }
}
`` ''
Die Funktion wird während einer Aktualisierung des Widgets aufgerufen. Anschließend wird der HTML-Code der Widget-Vorlage aktualisiert.

`` ''
jeedom.cmd.refreshValue([{ cmd_id: '#id#', value: '#value#', Anzeigewert: '#state#', valueDate: '#valueDate#', collectDate: '#collectDate#', alertLevel: '#alertLevel#', unit: '#unite#' }])
`` ''
 Der Aufruf dieser Funktion zur Initialisierung des Widgets.

### Exemples

 Sie werden finden [Hier](https://github.com/Jeedom/core/tree/master/core/template) Beispiele für Widgets (in Dashboard- und mobilen Ordnern)
