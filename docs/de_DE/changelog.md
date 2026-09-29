# Änderungsprotokoll Jeedom V4.6

## 4.6.1

- Korrektur beim Bearbeiten eines Szenarios, dessen Timeout in der Datenbank *null* ist

## 4.6.0

### Neue Funktionen

- Hinzufügen eines „Solange“-Blocks in Szenarien ([Details](https://github.com/jeedom/core/pull/3234){:target="_blank"})
- Neue Methode zur Übersetzung der Benutzeroberfläche ([Details](https://github.com/jeedom/core/pull/3251){:target="_blank"})
- Zusammenführung der bisherigen Zeit-Widgets *(`timeXxxx`)* zu den Standard-Widgets mit Parameter `time` entsprechend ([Details](https://github.com/jeedom/core/pull/3332){:target="_blank"})
- [Erweitert] Hinzufügen eines `healthcheck` für Installationen unter Docker ([Details](https://github.com/jeedom/core/pull/2998){:target="_blank"})

### Korrekturen

- Korrektur der automatischen Aktualisierung der Diagramme ([Details](https://github.com/jeedom/core/pull/3178){:target="_blank"})
- Korrektur der mathematischen Funktion `randText` ([Details](https://github.com/jeedom/core/pull/3197){:target="_blank"})
- Verbesserung der Zuverlässigkeit bei der Verwendung spezifischer Aktionen außerhalb eines Szenarios ([Details](https://github.com/jeedom/core/pull/3228){:target="_blank"})
- Korrektur der Datumsbereichsauswahl *(Zoom)* mit Gruppierung im Verlauf ([Details](https://github.com/jeedom/core/pull/3242){:target="_blank"})
- Verbesserte Verwaltung der Reinigung von Plugin-Protokollen ([Details](https://github.com/jeedom/core/pull/3245){:target="_blank"})
- Korrektur der Tag-Übergabe bei der Ausführung eines Szenarios auf sich selbst ([Details](https://github.com/jeedom/core/pull/3255){:target="_blank"})
- Schutz vor Befehlsinjektionen in der TTS-API ([Details](https://github.com/jeedom/core/pull/3261){:target="_blank"})
- Schutz vor SQL-Injection in der View-Verwaltung ([Details](https://github.com/jeedom/core/pull/3267){:target="_blank"})
- Schutz vor SQL-Injektionen bei der Archivierung von Verlaufsdaten ([Details](https://github.com/jeedom/core/pull/3268){:target="_blank"})
- Korrektur der Sichtbarkeit des Felds „Timeline-Ordner“ in Szenarien ([Details](https://github.com/jeedom/core/pull/3305){:target="_blank"})
- Behebung eines Fehlers, der dazu führen konnte, dass die Protokolle der Szenarien zufällig geleert wurden ([Details](https://github.com/jeedom/core/pull/3316){:target="_blank"})
- Angleichung der maximalen Ausführungsdauer der Szenarioblöcke „Schleife“ und „Solange“ sowie der Aktionen „Warten“ und „Pause“ *(maximal 1 Stunde)* ([Details](https://github.com/jeedom/core/pull/3341){:target="_blank"})
- Entfernen ungerechtfertigter Warnungen des Ausdrucksprüfers ([Details](https://github.com/jeedom/core/pull/3349){:target="_blank"})
- Korrektur der Anzeige der Einheiten in der Befehlsliste ([Details](https://github.com/jeedom/core/pull/3362){:target="_blank"})
- Korrektur der Schaltflächen für den Zugriff auf das Changelog des Kerns im Update-Center ([Details](https://github.com/jeedom/core/pull/3368){:target="_blank"})
- [Erweitert] Behebung von Fehlern in der Proxy-Konfiguration ([Details](https://github.com/jeedom/core/pull/3238){:target="_blank"})
- [Erweitert] Korrektur der Updates über die API ([Details](https://github.com/jeedom/core/pull/3352){:target="_blank"})
- [Verschiedenes] Zahlreiche Optimierungen und Code-Korrekturen sowohl auf der Benutzeroberfläche (`Javascript`) sowie den Betrieb des Kerns (`PHP`)

### Dokumentationen

- Automatische Erstellung von Versionshinweisen im Zuge der Integrationen ([Details](https://github.com/jeedom/core/pull/3278){:target="_blank"})
- Aktualisierung der Dokumentation zu Szenarien mit dem Block „Solange“ und der maximalen Ausführungsdauer ([Details](https://github.com/jeedom/core/pull/3345){:target="_blank"})
- Die Dokumentation zu den Widgets wurde komplett überarbeitet und erweitert ([Details](https://github.com/jeedom/core/pull/3345){:target="_blank"})
- [Entwickler] Hinzufügen von PHPDoc in die Klassendateien ([Details](https://github.com/jeedom/core/pull/3365){:target="_blank"})

>**INFORMATION**
>
>Diese Version führt zudem eine neue Struktur in der Entwicklung von Jeedom ein, die nun auf drei Hauptzweigen basiert: `develop` *(kontinuierliche Integration)* → `release` *(nächste stabile Version)* → `master` *(stabil)*. Die alten Zweige `alpha`, `beta` und `V4-stable` werden in Kürze entfernt.\
>Dokumentationen [Jeedom-Beta-Test](https://doc.jeedom.com/contribute/de_DE/beta){:target="_blank"}, [Zur Dokumentation beitragen](https://doc.jeedom.com/contribute/de_DE/doc){:target="_blank"} und [Zum Kern oder zu den Plugins beitragen](https://doc.jeedom.com/contribute/de_DE/core){:target="_blank"} wurden entsprechend überarbeitet.
