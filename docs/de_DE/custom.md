# Erweiterte Anpassung
**Einstellungen → System → Erweiterte Anpassung**

Hier können Sie Funktionen verwalten **JavaScript** und Regeln **CSS** auf Desktop oder Handy angewendet.

> **Warnung**
>
> Die Verwendung unangemessener CSS-Regeln kann die Anzeige Ihres Jeedoms beeinträchtigen. Falsch verwendete js-Funktionen können zu erheblichen Schäden an verschiedenen Komponenten Ihrer Installation führen. Denken Sie daran, ein Backup zu erstellen und auszulagern, bevor Sie diese Funktionen verwenden.

Diese Funktion verwendet einen bestimmten Modus des Core-Dateieditors mit zwei Speicherorten:

- Desktop / Benutzerdefiniert : Kann beide Dateien enthalten **custom.js** und **Benutzerdefinierte CSS** die vom Core in der Desktop-Version geladen wird.
- mobil / benutzerdefiniert : Kann beide Dateien enthalten **custom.js** und **Benutzerdefinierte CSS** die vom Core in der mobilen Version geladen werden.

In der Menüleiste des Core-Dateieditors befindet sich eine Schaltfläche **Ermöglicht** Woher **Deaktiviert** sagt dir, ob der Core sie laden soll oder nicht. Diese Option ist auch verfügbar in **Einstellungen → System → Konfiguration** Registerkarte Schnittstelle.

> **Anmerkung**
>
> Beim Aufruf dieser Seite wird automatisch die Baumstruktur erstellt, sowie die 4 Dateien mit einem Kommentar in der 1. Zeile inklusive der Version des Cores, der sie erstellt hat.

## Ressources

[CSS: Cascading Style Sheets](https://developer.mozilla.org/en-US/docs/Web/CSS)

[JavaScript](https://developer.mozilla.org/en-US/docs/Web/JavaScript)

[Tipps zum Anpassen der Benutzeroberfläche](https://kiboost.github.io/jeedom_docs/jeedomV4Tips/Interface/)

## Im Falle eines Problems

Das Injizieren von JS und / oder CSS kann dazu führen, dass Jeedom nicht mehr funktioniert.

In diesem Fall zwei Lösungen:

- Öffnen Sie einen Browser im Rettungsmodus : `IP / index.php?rescue=1`
- Stellen Sie eine Verbindung in SSH her und löschen Sie die Anpassungsdateien : `Desktop/Benutzerdefiniert` und`Mobil/Benutzerdefiniert`

