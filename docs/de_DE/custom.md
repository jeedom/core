# Erweiterte Anpassung
**Einstellungen → System → Erweiterte Anpassung**

Hier können Sie Funktionen verwalten **Javascript** und Regeln **CSS** auf Desktop oder Handy angewendet.

> **Aufmerksamkeit**
>
> Die Verwendung unangemessener CSS-Regeln kann die Anzeige Ihres Jeedoms beeinträchtigen. Falsch verwendete js-Funktionen können zu erheblichen Schäden an verschiedenen Komponenten Ihrer Installation führen. Denken Sie daran, ein Backup zu erstellen und auszulagern, bevor Sie diese Funktionen verwenden.

Diese Funktion verwendet einen bestimmten Modus des Core-Dateieditors mit zwei Speicherorten:

- Desktop / Benutzerdefiniert : Kann beide Dateien enthalten **custom.js** Und **Benutzerdefinierte CSS** die vom Core in der Desktop-Version geladen wird.
- mobil / benutzerdefiniert : Kann beide Dateien enthalten **custom.js** Und **Benutzerdefinierte CSS** die vom Core in der mobilen Version geladen werden.

In der Menüleiste des Core-Dateieditors befindet sich eine Schaltfläche **Ermöglicht** Oder **Deaktiviert** sagt dir, ob der Core sie laden soll oder nicht. Diese Option ist auch verfügbar in **Einstellungen → System → Konfiguration** Registerkarte Schnittstelle.

> **Aufgefallen**
>
> Beim Aufruf dieser Seite wird automatisch die Baumstruktur erstellt, sowie die 4 Dateien mit einem Kommentar in der 1. Zeile inklusive der Version des Cores, der sie erstellt hat.

## Ressources

[CSS: Kaskadierende Stylesheets](https://developer.mozilla.org/en-US/docs/Web/CSS)

[Javascript](https://developer.mozilla.org/en-US/docs/Web/JavaScript)

[Tipps zum Anpassen der Benutzeroberfläche](https://kiboost.github.io/jeedom_docs/jeedomV4Tips/Interface/)

## Im Falle eines Problems

Das Injizieren von JS und / oder CSS kann dazu führen, dass Jeedom nicht mehr funktioniert.

In diesem Fall zwei Lösungen:

- Öffnen Sie einen Browser im Rettungsmodus : `IP / index.php?rescue=1`
- Stellen Sie eine Verbindung in SSH her und löschen Sie die Anpassungsdateien : `Desktop/Benutzerdefiniert` und`Mobil/Benutzerdefiniert`

## Beispiel für erweiterte Personalisierung in CSS

Alle diese Beispiele müssen in die CSS-Datei eingefügt werden (vergessen Sie nicht, oben die erweiterte Anpassung zu aktivieren))

### Bildlaufleisten in Widgets entfernen

„
.eqLogic-widget.cmds{
 overflow-x: versteckt !important;
 overflow-y: versteckt !important;
}
„

### Mindestbreite/-höhe von Widgets entfernen

Dies ermöglicht Ihnen kleinere Widgets (Breite [min-width], Höhe [min-height]), aber seien Sie vorsichtig, da dies die Anzeige weniger attraktiv machen kann

„
div.cmd-widget.content,
div.cmd-widget .content-sm,
div.cmd-widget .content-lg,
div.cmd-widget.content-xs {
  min-width: nicht gesetzt !important;
  min-height: nicht gesetzt !important;
}
„

### Zwischen den Namen von Objekten und Geräten im Dashboard wurde ein Abstand hinzugefügt 

„
.div_object-Legende .objectDashLegend {
  margin-bottom: 5px;
}
„
