# Erweiterte Anpassung
**Einstellungen → System → Erweiterte Anpassung**

Auf dieser für Experten reservierten Seite können Sie Jeedom CSS- oder JS-Skripte hinzufügen, die auf jeder Seite ausgeführt werden.

Sie können Ihre eigenen JS-Funktionen hinzufügen und CSS-Klassen hinzufügen oder ändern.

Die beiden Teile JS und CSS unterscheiden sich je nach Desktop- oder Mobilanzeige.

## Ressources

[CSS: Cascading Style Sheets](https://developer.mozilla.org/en-US/docs/Web/CSS)

[JavaScript](https://developer.mozilla.org/en-US/docs/Web/JavaScript)

[Tipps zum Anpassen der Benutzeroberfläche](https://kiboost.github.io/jeedom_docs/jeedomV4Tips/Interface/)

## Im Falle eines Problems

Das Injizieren von JS und / oder CSS kann dazu führen, dass Jeedom nicht mehr funktioniert.

In diesem Fall zwei Lösungen:

- Öffnen Sie einen Browser im Rettungsmodus : `IP / index.php?rescue=1`
- Stellen Sie eine Verbindung in SSH her und löschen Sie die Anpassungsdateien : `desktop / custopn` und` mobile / custom`

