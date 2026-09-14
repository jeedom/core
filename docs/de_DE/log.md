# Protokolle
**Analyse → Protokolle**

Protokolle sind Logdateien, mit denen sich die Vorgänge im Hausautomationssystem nachverfolgen lassen. In den meisten Fällen dienen die Protokolle lediglich der Fehlerbehebung und der Problemlösung durch das Support-Team.

> **Tipp**
>
> Beim Öffnen der Seite wird der erste verfügbare Eintrag angezeigt.

Die Seite „Logs“ ist recht einfach aufgebaut:
Auf der linken Seite befindet sich eine Liste der verfügbaren Protokolle mit einem Suchfeld, um die Protokolle nach Namen zu filtern.
Oben rechts befinden sich 5 Schaltflächen:

- **Suchen**: Ermöglicht das Filtern der Anzeige des aktuellen Protokolls.
- **Pause/Fortsetzen**: Ermöglicht es, die Echtzeit-Aktualisierung des aktuellen Protokolls anzuhalten bzw. fortzusetzen.
- **Herunterladen**: Ermöglicht das Herunterladen des aktuellen Protokolls.
- **Leeren**: Ermöglicht das Leeren des aktuellen Protokolls.
- **Löschen**: Hiermit können Sie das aktuelle Protokoll löschen. Falls Jeedom es benötigt, wird es automatisch neu erstellt.
- **Alle Protokolle löschen**: Löscht alle vorhandenen Protokolle.

> **Tipp**
>
> Bitte beachten Sie, dass das http.error-Protokoll nicht gelöscht werden darf. Es ist unerlässlich; wenn Sie es löschen (beispielsweise über die Befehlszeile), wird es nicht automatisch neu erstellt, sondern Sie müssen das System neu starten.

## Echtzeit

Das „Event“-Protokoll ist etwas speziell. Damit es funktioniert, muss es zunächst auf die Stufe „Info“ oder „Debug“ eingestellt sein. Anschließend erfasst es alle Ereignisse oder Aktionen, die im Bereich der Hausautomation stattfinden. Um darauf zuzugreifen, muss man entweder die Protokollseite aufrufen oder zu „Analyse“ → „Echtzeit“ wechseln.

Sobald Sie darauf geklickt haben, öffnet sich ein Fenster, das in Echtzeit aktualisiert wird und Ihnen alle Ereignisse Ihres Hausautomationssystems anzeigt.

Oben rechts finden Sie ein Suchfeld (funktioniert nur, wenn Sie nicht in der Pause sind) und eine Schaltfläche zum Anhalten (nützlich, um beispielsweise etwas zu kopieren und einzufügen).
