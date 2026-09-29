# Widgets

Ein Widget ist die grafische Darstellung eines Befehls auf dem Dashboard oder in der mobilen Version. Der Jeedom-Core weist automatisch ein Widget zu, je nach Typ *(Info oder Aktion)* und Untertyp *(Binär, Numerisch, Sonstiges, Schieberegler usw.)* des Befehls. Über die erweiterte Konfiguration des Befehls, Registerkarte „Anzeige“ → „**Widget**“, kann ein anderes Widget aus den verfügbaren Optionen ausgewählt werden.

## Standard-Widgets

Hier finden Sie die in den Jeedom Core integrierten Widgets, ihre Verwendungszwecke und ihre Anpassungsoptionen.

### Steuerungen

Die meisten Widgets verfügen über **optionale Parameter**, mit denen sich ihre Darstellung anpassen lässt, ohne ein benutzerdefiniertes Widget erstellen zu müssen: Farbe, Skalierung, Verhalten usw. Diese werden in den erweiterten Einstellungen des Befehls auf der Registerkarte „Anzeige“ → Abschnitt „**Optionale Widget-Parameter**“ in Form von Name-Wert-Paaren konfiguriert. Die verfügbaren Parameter variieren je nach ausgewähltem Widget und sind unten aufgeführt.

Der Parameter **`time`** (`duration`/`date`) ist allen Widgets *(außer HygroThermograph)* gemeinsam und zeigt jeweils die verstrichene Zeit oder das Datum der letzten Wertänderung an.

#### Info / Binär

| Widget | Beschreibung | Optionale Einstellungen |
|--------|-------------|----------------------|
| Rollladen | Visuelle Darstellung eines Rollladens mit Position in % | `color` |
| Warnung | Grünes Häkchen (EIN) / rote Warnung (AUS) | |
| Tür | Tür geschlossen: grün (EIN) / Tür offen: rot (AUS) | |
| Flood | Grüner, durchgestrichener Wassertropfen (EIN) / blauer Wassertropfen (AUS) | |
| Heizung | Rote Flamme (EIN) / Kreuz (AUS) | |
| Symbol | Grünes Häkchen (EIN) / rotes Kreuz (AUS) | |
| Licht | Glühbirne leuchtet gelb (EIN) / Glühbirne aus (AUS) | |
| Zeile | Grünes Häkchen (EIN) / rotes Kreuz (AUS), Inline-Anzeige mit dem Namen | |
| Schloss | Schloss geschlossen (ON) / rotes offenes Schloss (OFF) | |
| Anwesenheit | Grünes Häkchen (EIN) / rotes Bewegungssymbol (AUS) | |
| Steckdose | Symbol „Steckdose“ (EIN) / Kreuz (AUS) | |
| Fenster | Geschlossenes Fenster grün (EIN) / offenes Fenster rot (AUS) | |

#### Info / Digital

| Widget | Beschreibung | Optionale Einstellungen |
|--------|-------------|----------------------|
| Anzeigen | In einer farbigen Anzeige angezeigter Wert | `color`, `fontcolor` |
| Kompass | Kompass, der die Richtung in Grad anzeigt | `needle_color`, `ns_color`, `oe_color`, `scale` |
| Messgerät | Bogenförmiges Messgerät | `color` |
| Horizontal | Horizontaler Fortschrittsbalken | `color` |
| Hygrothermograph | Kombinierte Temperatur- und Feuchtigkeitsanzeige *(Widget mit mehreren Bedienelementen, ohne `time`)* | `scale` |
| Licht | Glühbirnen-Symbol (je nach Wert ein- oder ausgeschaltet) mit Wert und Einheit | |
| Zeile | Name, Wert und Einheit werden in einer einzigen Zeile angezeigt (kompaktes Format) | |
| Regen | Wasser- oder Niederschlagsmenge | `color`, `scale`, `showRange`, `animate` |
| Rollladen | Rollladen mit Positionsanzeige in % | `color`, `invert` |
| Kachel | Name, der als Überschrift über dem Wert und der Einheit angezeigt wird | |
| Vertikal | Vertikaler Fortschrittsbalken | `color` |
| HeatPiloteWire | 4-Stufen-Steuerleitung: Komfort, Frostschutz, Eco, Aus | |
| HeatPiloteWireQubino | Qubino-Steuerkabel: 6 Stufen: Komfort/Eco/Frostschutz/Aus | |

#### Info / Sonstiges

| Widget | Beschreibung | Optionale Einstellungen |
|--------|-------------|----------------------|
| Ausweis | Textwert auf einem farbigen Ausweis | `color`, `fontcolor` |
| ButtonImage | Schaltfläche, die das Bild, dessen URL der Wert des Befehls ist, in einem Modalfenster öffnet | |
| Farbe | Zeigt die Farbe an, die einem Hexadezimalcode entspricht | `showValue` |
| Zeile | Name und Textwert werden in einer einzigen Zeile angezeigt (kompaktes Format) | |
| Multiline | Mehrzeiliger Textwert mit Bildlauf | `maxHeight`, `minHeight`, `backgroundColor` |
| Kachel | Name, der als Überschrift über dem Textwert angezeigt wird | |

#### Aktion / Farbe

| Widget | Beschreibung | Optionale Einstellungen |
|--------|-------------|----------------------|
| Standard | Umfassende Farbauswahl (Farbrad + Hex-Wert) | |
| Picker | Vereinfachte Farbauswahl | |

#### Aktion / Fehler

| Widget | Beschreibung | Optionale Einstellungen |
|--------|-------------|----------------------|
| Benachrichtigung | Glockensymbol, das den Status des zugehörigen Befehls anzeigt (rot = aktiv, grün mit Strich = inaktiv) | |
| BinaryDefault | Symbol, das den Status des zugehörigen Befehls anzeigt (grünes Häkchen = aktiv, rotes Kreuz = inaktiv) | |
| BinarySwitch | Kippschalter EIN/AUS | `color`, `color_switch` |
| BtnAlert | Schaltfläche mit Glockensymbol, das den Status des zugehörigen Befehls anzeigt | |
| Schaltfläche | Einfache Ausführungsschaltfläche | |
| Kreis | Gefüllter Kreis (EIN) / leerer Kreis (AUS) | |
| Lüfter | Lüfter (EIN) / Kreuz (AUS) | |
| Garage | Geschlossene Garage (grün) (EIN) / offene Garage (rot) (AUS) | |
| Licht | Glühbirne leuchtet gelb (EIN) / Glühbirne aus (AUS) | |
| Schloss | Schloss geschlossen (ON) / Schloss offen (orange, OFF) | |
| Steckdose | Symbol „Steckdose“ (EIN) / Kreuz (AUS) | |
| Sprinkle | Bewässerung: blau (EIN) / Kreuz (AUS) | |
| Umschalter | Schalter eingeschaltet (ON) / ausgeschaltet (OFF) | |
| ToggleLine | Schalter: ein (ON) / aus (OFF), Inline-Anzeige | |

#### Aktion / Schieberegler

| Widget | Beschreibung | Optionale Einstellungen |
|--------|-------------|----------------------|
| Schaltfläche | Schieberegler mit den Tasten „+“ und „−“ zur präzisen Einstellung | `step`, `width` |
| Rollladen | Spezieller Schieberegler für die Position eines Rollladens | `color`, `step`, `invert` |
| Schieberegler | Horizontaler Schieberegler | `color`, `step` |
| SliderVertical | Vertikaler Schieberegler | `color`, `step` |
| Wert | Direktes numerisches Eingabefeld | `color`, `step`, `noslider`, `width` |
| Licht | Glühbirne leuchtet gelb (EIN) / Glühbirne aus (AUS) | |

#### Aktion / Liste

| Widget | Beschreibung | Optionale Einstellungen |
|--------|-------------|----------------------|
| Auswählen | Dropdown-Liste mit vordefinierten Werten | |

#### Aktion / Nachricht

| Widget | Beschreibung | Optionale Einstellungen |
|--------|-------------|----------------------|
| Eingabe | Formular zum Absenden einer Nachricht mit Titel und Text | `title`, `title_placeholder`, `title_possibility_list`, `title_disable`, `message_placeholder`, `message`, `message_disable` |

### Ausstattung

Die Geräte (oder Kacheln) verfügen über bestimmte Konfigurationsparameter, auf die über die erweiterte Konfiguration des Geräts zugegriffen werden kann, Registerkarte „Anzeige“ → „**Optionale Parameter auf der Kachel**“:

- **dashboard_class/mobile_class**: Ermöglicht das Hinzufügen einer Klasse zum Gerät. Zum Beispiel `col2` Bei Geräten in der mobilen Version wird dadurch die Breite des Widgets verdoppelt.

## Individuell angepasste Widgets

Auf der Seite „Widgets“, die über das Menü **Extras → Widgets** aufgerufen werden kann, können Sie benutzerdefinierte Widgets hinzufügen, zusätzlich zu den in Jeedom standardmäßig verfügbaren Widgets.

Es gibt zwei Arten von benutzerdefinierten Widgets:

- Die *Core*-Widgets basieren auf einer Vorlage. Diese Widgets werden vom Jeedom-Core verwaltet und somit vom Entwicklungsteam betreut. Ihre Kompatibilität mit zukünftigen Weiterentwicklungen von Jeedom ist gewährleistet.
- Widgets von *Drittanbietern*, die auf benutzereigenem Code basieren. Im Gegensatz zu den Core-Widgets hat das Jeedom-Entwicklerteam keine Kontrolle über den in diesen Widgets eingebetteten Code, sodass deren Kompatibilität mit zukünftigen Weiterentwicklungen nicht garantiert werden kann. Diese Widgets müssen daher vom Benutzer selbst gepflegt werden.

### Verwaltung

![Widgets](../images/widgets.png)

Sie haben vier Möglichkeiten:
- **Hinzufügen**: Ermöglicht das Hinzufügen eines *Core*-Widgets.
- **Importieren**: Ermöglicht das Importieren eines Widgets als zuvor exportierte JSON-Datei.
- **Code**: Ruft die Bearbeitungsseite für Widgets von *Drittanbietern* auf.
- **Ersetzen**: Öffnet ein Fenster, in dem Sie ein Widget auf allen Geräten, auf denen es verwendet wird, durch ein anderes ersetzen können.

### Meine Widgets

In diesem Abschnitt finden Sie alle von Ihnen erstellten Widgets, sortiert nach Typ.

![Meine Widgets](../images/widgets1.png)

>**INFORMATION**
>
>Sie können ein Widget wie folgt öffnen:
>- `Clic` auf einem davon.
>- `Ctrl+Clic` oder `Clic+Centre` um sie in einem neuen Browser-Tab zu öffnen.

Mit der Suchfunktion können Sie die Anzeige der Widgets nach verschiedenen Kriterien (Name, Typ, Untertyp usw.) filtern. Die Taste `Echap` bricht die Suche ab.

![Widgets suchen](../images/widgets2.png)

Rechts neben dem Suchfeld befinden sich drei Schaltflächen, die an mehreren Stellen in Jeedom zu finden sind:

- **Das Kreuz**, um die Suche abzubrechen.
- **Der Ordner ist geöffnet**, um alle Registerkarten zu entfalten und die Widgets anzuzeigen.
- **Der Ordner ist geschlossen**, um alle Fenster zu minimieren und die Widgets auszublenden.

Sobald man sich auf der Konfigurationsseite eines Widgets befindet, steht ein Kontextmenü zur Verfügung, das über `Clic Droit` auf den Registerkarten des Widgets. Sie können auch ein `Ctrl+Clic` oder `Clic+Centre` um ein anderes Widget direkt in einem neuen Browser-Tab zu öffnen.

### Erstellen eines Widgets

Sobald Sie sich auf der Seite **Tools → Widgets** befinden, klicken Sie auf die Schaltfläche „**Hinzufügen**“ und geben Sie Ihrem neuen Widget einen Namen.

Weiter:
- Sie wählen aus, ob es auf einen Befehl vom Typ **Aktion** oder **Info** angewendet wird.
- Je nach Ihrer vorherigen Auswahl müssen Sie nun **den Untertyp** des Befehls auswählen.
- Schließlich **die Vorlage** aus denjenigen, die entsprechend den vorherigen Auswahlen verfügbar sein werden.
- Sobald die Vorlage ausgewählt wurde, zeigt Jeedom darunter die Konfigurationsmöglichkeiten für diese Vorlage an.

### Die Vorlagen

#### Definition einer Vorlage

Einfach ausgedrückt handelt es sich um Code (HTML/JS), der in den Core integriert ist und dessen einzelne Teile vom Benutzer über die grafische Benutzeroberfläche des Menüs **Widgets** konfiguriert werden können. Ausgehend von derselben Basis und unter Berücksichtigung der Elemente, die Sie in die Vorlage eingeben, generiert der Core einzigartige Widgets, die der von Ihnen gewünschten Darstellung entsprechen.

Je nach Art des Widgets können Sie in der Regel die Symbole anpassen, Bilder Ihrer Wahl einfügen und/oder HTML-Code einbinden.

Es gibt zwei Arten von Vorlagen:

- Die „**einfachen**“: zum Beispiel ein Symbol/Bild für „**EIN**“ und ein Symbol/Bild für „**AUS**“.
- „**Multistates**“: Damit lässt sich beispielsweise ein Bild festlegen, wenn der Befehl den Wert „**XX**“ hat, und ein anderes, wenn der Wert größer als „**YY**“ oder kleiner als „**ZZ**“ ist. Das funktioniert auch bei Textwerten: ein Bild, wenn der Wert „**toto**“ lautet, ein anderes, wenn er „**plop**“ lautet, und so weiter...

#### Austausch

Das nennt man eine einfache Vorlage. Hier müssen Sie lediglich festlegen, dass „**ON**“ einem bestimmten Symbol/Bild entspricht *(mithilfe der Schaltfläche „Auswählen“)*, „**OFF**“ einem anderen Symbol/Bild usw.

Die verfügbaren Core-Widgets vom Typ „Ersatz“ sind:

| Widget | Typen/Untertypen |
|--------|-----------------|
| Symbolvorlage | Info/Binär, Aktion/Standard, Aktion/Cursor |
| Iconline-Vorlage | Info/Binär, Aktion/Standard |
| Bildvorlage | Info/Binär, Aktion/Standard, Aktion/Schieberegler |

Das Feld **Time Widget**, sofern verfügbar, entspricht dem Parameter `time: duration` (siehe [Steuerungen](#Commandes)).

Bei Vorlagen, die Bilder verwenden, können Sie die Breite des Widgets je nach Gerät in Pixel festlegen (**Desktop-Breite** & **Mobil-Breite**). Je nach aktivem Jeedom-Theme *(hell oder dunkel)* können auch unterschiedliche Bilder ausgewählt werden.

>**INFORMATION**
>
>Für fortgeschrittene Benutzer besteht die Möglichkeit, Tags in die Ersetzungswerte einzufügen und deren Wert in den erweiterten Einstellungen des Befehls festzulegen.
>Wenn Sie beispielsweise unter **Desktop-Breite** folgenden Wert eingeben `#largeur_desktop#` (**Achtung: Bitte unbedingt die** `#` **um**) und dann in den erweiterten Einstellungen eines Befehls unter dem Reiter „Anzeige“ → „**Optionale Widget-Einstellungen**“ fügen Sie den Parameter hinzu `largeur_desktop` (**ohne die** `#`) und weisen Sie ihm den Wert „**90**“ zu, dann hat dieses benutzerdefinierte Widget für diesen Befehl eine Breite von 90 Pixeln. So lässt sich die Größe des Widgets an jeden Befehl anpassen, ohne dass jedes Mal ein spezielles Widget erstellt werden muss.

#### Test

Dies werden als Multistate-Vorlagen *(mehrere Zustände)* bezeichnet. Anstatt wie im vorherigen Fall ein Bild für „**EIN**“ und/oder für „**AUS**“ einzufügen, weisen Sie ein Symbol zu, das von der Erfüllung einer Bedingung *(Test)* abhängt. Ist diese Bedingung wahr, zeigt das Widget das entsprechende Symbol bzw. Bild an.

Die folgenden Core-Widgets vom Typ „Multistate“ sind verfügbar:

| Widget | Typen/Untertypen |
|--------|-----------------|
| Vorlage für mehrere Bundesstaaten | Info/Digital, Info/Sonstiges |
| Vorlage „Multistateline“ | Info/Sonstiges |

Wie zuvor können je nach dem in Jeedom aktiven Thema verschiedene Bilder ausgewählt werden, und über das Kontrollkästchen **Zeit-Widget** lässt sich die Zeit seit der letzten Statusänderung anzeigen.

Die Tests haben folgende Form: `#value# == 1`, `#value#` wird automatisch durch den aktuellen Wert des Befehls ersetzt. Sie können beispielsweise auch Folgendes tun:

- `#value# > 1`
- `#value# >= 1 && #value# <= 5`
- `#value# == 'toto'`

>**WICHTIG**
>
>Es ist unbedingt erforderlich, den zu vergleichenden Text in Anführungszeichen (**'**) zu setzen, wenn es sich bei dem Wert um einen Text *(Info/Sonstiges)* handelt.

>**INFORMATION**
>
>Der Wert des Befehls kann im Widget angezeigt werden, indem man Folgendes angibt: `#value#` im HTML-Code des Tests. Um die Einheit anzuzeigen, fügen Sie Folgendes hinzu `#unite#`.\
>Für fortgeschrittene Nutzer besteht zudem die Möglichkeit, JavaScript-Funktionen wie beispielsweise `#value#.match("^plop")` um zu prüfen, ob der Text mit `plop`.

## Widget-Code

### Tags

Im Code-Modus haben Sie Zugriff auf verschiedene Tags für Befehle. Hier ist eine Liste (die nicht unbedingt vollständig ist):

- **#name#**: Name des Befehls
- **#valueName#**: Name des Befehlswerts, und = #name#, wenn es sich um einen Befehl vom Typ „Info“ handelt
- **#minValue#**: Mindestwert, den das Steuerelement annehmen kann (sofern es sich um einen Schieberegler handelt)
- **#maxValue#**: Maximalwert, den das Steuerelement annehmen kann (sofern es sich um einen Schieberegler handelt)
- **#hide_name#**: leer oder „hidden“, wenn der Benutzer angegeben hat, dass der Name des Widgets ausgeblendet werden soll; muss direkt in ein class-Tag eingefügt werden
- **#id#**: ID des Befehls
- **#state#**: Wert des Befehls; leer bei einem Aktionsbefehl, sofern dieser nicht mit einem Statusbefehl verknüpft ist
- **#uid#**: Eindeutige Kennung für diese Generierung des Widgets (falls derselbe Befehl mehrfach vorkommt, z. B. bei Designs: Nur diese Kennung ist tatsächlich eindeutig)
- **#valueDate#**: Datum des Bestellwerts
- **#collectDate#**: Abholtermin der Bestellung
- **#alertLevel#**: Alarmstufe (none, warning, danger)
- **#hide_history#**: Gibt an, ob der Verlauf (Maximalwert, Minimalwert, Durchschnittswert, Trend) ausgeblendet werden soll oder nicht. Wie bei #hide_name# hat es den Wert „leer“ oder „hidden“ und kann daher direkt in einer Klasse verwendet werden. WICHTIG: Wenn dieses Tag in Ihrem Widget nicht gefunden wird, werden die Tags #minHistoryValue#, #averageHistoryValue#, #maxHistoryValue# und #Trend# nicht von Jeedom ersetzt.
- **#minHistoryValue#**: Minimalwert im Zeitraum (Zeitraum vom Benutzer in der Jeedom-Konfiguration festgelegt)
- **#averageHistoryValue#**: Durchschnittswert über den Zeitraum (der Zeitraum wird vom Benutzer in der Jeedom-Konfiguration festgelegt)
- **#maxHistoryValue#**: Höchstwert im Zeitraum (der Zeitraum wird vom Benutzer in der Jeedom-Konfiguration festgelegt)
- **#Trend#**: Trend über den Zeitraum (der Zeitraum wird vom Benutzer in den Jeedom-Einstellungen festgelegt). Achtung: Der Trend ist direkt eine Klasse für ein Symbol: fas fa-arrow-up, fas fa-arrow-down oder fas fa-minus

### Werte aktualisieren

Bei einem neuen Wert sucht Jeedom auf der Seite nach dem Befehl und in jeedom.cmd.update nach einer Update-Funktion. Ist dies der Fall, ruft es diese mit einem einzigen Argument auf, das ein Objekt in folgender Form ist:

```
{display_value: '#state#', valueDate: '#valueDate#', collectDate: '#collectDate#', alertLevel: '#alertLevel#'}
```

Hier ist ein einfaches Beispiel für JavaScript-Code, den Sie in Ihr Widget einfügen können:

```
<script>
    jeedom.cmd.addUpdateFunction('#id#', function(_options) {
      if (is_object(cmd = document.querySelector('.cmd[data-cmd_id="#id#"]'))) {
        cmd.setAttribute('title', '{{Date de valeur}}: ' + _options.valueDate + '<br>{{Date de collecte}}: ' + _options.collectDate)
        cmd.querySelector('.value').innerHTML = _options.display_value
        cmd.querySelector('.unit').innerHTML = _options.unit
      }
    }
    jeedom.cmd.refreshValue([{ cmd_id: '#id#', value: '#value#', display_value: '#state#', valueDate: '#valueDate#', collectDate: '#collectDate#', alertLevel: '#alertLevel#', unit: '#unite#' }])
</script>
```

Hier sind zwei wichtige Punkte:

```
jeedom.cmd.addUpdateFunction('#id#', function(_options) {
  if (is_object(cmd = document.querySelector('.cmd[data-cmd_id="#id#"]'))) {
    cmd.setAttribute('title', '{{Date de valeur}}: ' + _options.valueDate + '<br>{{Date de collecte}}: ' + _options.collectDate)
    cmd.querySelector('.value').innerHTML = _options.display_value
    cmd.querySelector('.unit').innerHTML = _options.unit
  }
}
```
Die Funktion wird bei einer Aktualisierung des Widgets aufgerufen. Sie aktualisiert dann den HTML-Code der Widget-Vorlage.

```
jeedom.cmd.refreshValue([{ cmd_id: '#id#', value: '#value#', display_value: '#state#', valueDate: '#valueDate#', collectDate: '#collectDate#', alertLevel: '#alertLevel#', unit: '#unite#' }])
```
Der Aufruf dieser Funktion dient zur Initialisierung des Widgets.

### Beispiele

Hier finden Sie [hier](https://github.com/Jeedom/core/tree/master/core/template) Beispiele für Widgets (in den Ordnern „Dashboard“ und „Mobile“)

## Symbol-Umschalter EIN/AUS

Was die Widgets für Schalter *(Ein/Aus, Einschalten/Ausschalten, Öffnen/Schließen usw.)* betrifft, kann es optisch ansprechender sein, nur ein Symbol anzuzeigen, das den Status des zu steuernden Geräts widerspiegelt.

Diese Funktion kann sowohl mit den Standard-Widgets als auch mit benutzerdefinierten Widgets genutzt werden.

Dazu müssen zwei Voraussetzungen berücksichtigt werden:

- Die **2 Befehle „Aktion/Fehler“** müssen mit einem **Info-/Binärbefehl** verknüpft werden, der den aktuellen Status des Geräts speichert.

>**Beispiel**
>![ToggleLink-Widget](../images/widgets5.png)

>**Tipp**
>Deaktivieren Sie das Kontrollkästchen *„Anzeigen“* bei dem Info-/Binärbefehl, der nicht angezeigt werden soll.

- Damit der Jeedom-Core erkennen kann, welcher Befehl zu welcher Aktion gehört, muss bei den **2 Befehlen „action“ und „default“** unbedingt die folgende Namenskonvention eingehalten werden:
```
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
    'désactiver':'off',
    'lock':'on',
    'unlock':'off',
    'marche':'on',
    'arret':'off',
    'arrêt':'off',
    'stop':'off',
    'go':'on'
```

>**INFORMATION**
>
>Solange der standardisierte Name lesbar bleibt, ist es möglich, die Namensgebung anzupassen, zum Beispiel *Fensterladen_öffnen* oder *Fensterladen_schließen*, *Ein_2* und *Aus_2* usw.
