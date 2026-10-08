# Erweiterte Anpassung
**Einstellungen → System → Erweiterte Anpassung**

Hier können Sie **JavaScript**-Funktionen und **CSS**-Regeln verwalten, die auf Desktop- oder Mobilgeräten angewendet werden.

> **Achtung**
>
> Die Verwendung ungeeigneter CSS-Regeln kann die Darstellung Ihres Jeedom beeinträchtigen. Unsachgemäß verwendete JavaScript-Funktionen können erhebliche Schäden an verschiedenen Komponenten Ihrer Anlage verursachen. Denken Sie daran, vor der Verwendung dieser Funktionen ein Backup zu erstellen und extern zu speichern.

Diese Funktion nutzt einen speziellen Modus des Core-Dateieditors mit zwei Speicherorten:

- Desktop / custom: Kann die beiden Dateien **custom.js** und **custom.css** enthalten, die vom Core in der Desktop-Version geladen werden.
- Mobil / Benutzerdefiniert: Kann die beiden Dateien **custom.js** und **custom.css** enthalten, die vom Core in der mobilen Version geladen werden.

In der Menüleiste des Core-Datei-Editors zeigt Ihnen eine Schaltfläche mit der Bezeichnung **Aktiviert** oder **Deaktiviert** an, ob der Core diese laden soll oder nicht. Diese Option ist auch unter **Einstellungen → System → Konfiguration** auf der Registerkarte „Benutzeroberfläche“ verfügbar.

> **Hinweis**
>
> Beim Aufruf dieser Seite wird die Verzeichnisstruktur automatisch erstellt, ebenso wie die vier Dateien, deren erste Zeile einen Kommentar enthält, der die Version des Core angibt, mit der sie erstellt wurden.

## Ressourcen

[CSS: Cascading Style Sheets](https://developer.mozilla.org/en-US/docs/Web/CSS)

[JavaScript](https://developer.mozilla.org/en-US/docs/Web/JavaScript)

[Tipps zur Anpassung der Benutzeroberfläche](https://kiboost.github.io/jeedom_docs/jeedomV4Tips/Interface/)

## Bei Problemen

Das Einfügen von JS und/oder CSS kann dazu führen, dass Jeedom nicht mehr funktioniert.

In diesem Fall gibt es zwei Lösungen:

- Öffnen Sie einen Browser im Rettungsmodus: `IP/index.php?rescue=1`
- Per SSH verbinden und die Anpassungsdateien löschen: `desktop/custom` und `mobile/custom`

## Beispiel für erweiterte Anpassung im CSS

Alle diese Beispiele müssen in die CSS-Datei eingefügt werden (vergessen Sie nicht, oben die erweiterte Anpassung zu aktivieren)

### Entfernen der Bildlaufleisten bei Widgets

```
.eqLogic-widget .cmds{
 overflow-x: hidden !important;
 overflow-y: hidden !important;
}
```

### Mindestbreite/-höhe der Widgets entfernen

Dadurch lassen sich kleinere Widgets erstellen (Breite [min-width], Höhe [min-height]), aber Vorsicht: Das kann die Darstellung weniger ansprechend machen.

```
div.cmd-widget .content,
div.cmd-widget .content-sm,
div.cmd-widget .content-lg,
div.cmd-widget .content-xs {
  min-width: unset !important;
  min-height: unset !important;
}
```

### Hinzufügen von Abständen zwischen den Objektnamen und den Geräten auf dem Dashboard

```
.div_object legend .objectDashLegend {
  margin-bottom: 5px;
}
```
