# Geräteanalyse
**Analyse → Ausrüstung**

Auf der Seite Geräteanalyse können Sie viele Informationen zu Geräten zentral anzeigen :

- Den Zustand Ihrer Batterien
- Module in Alarmbereitschaft
- Definierte Aktionen
- Definierte Warnungen
- Orphan Orders

## Registerkarte &quot;Batterien&quot;


Auf dieser Registerkarte sehen Sie die Liste Ihrer Batteriemodule, deren verbleibenden Füllstand (die Farbe der Kachel hängt von diesem Füllstand ab), den Typ und die Anzahl der Batterien, die in das Modul eingelegt werden müssen, sowie den Modultyp dass das Datum, an dem die Informationen zum Batteriestand aktualisiert wurden. Sie können auch sehen, ob für das jeweilige Modul ein bestimmter Schwellenwert festgelegt wurde (dargestellt durch eine Hand))

> **Spitze**
>
> Die Alarm- / Warnschwellenwerte für die Batteriestände können global in der Jeedom-Konfiguration (Einstellungen → Systeme → Konfiguration) konfiguriert werden : Ausrüstung) oder nach Ausrüstung auf der Seite für die erweiterte Konfiguration auf der Registerkarte Warnungen.

## Module auf der Registerkarte &quot;Warnung&quot;

Auf dieser Registerkarte sehen Sie in Echtzeit die Module in Alarmbereitschaft. Die Warnungen können unterschiedlicher Art sein :

- Zeitüberschreitung (konfiguriert auf der Registerkarte "Definierte Warnungen")).
- Batterie in Warnung oder in Gefahr.
- Warn- oder Gefahrenbefehl (konfigurierbar in erweiterten Befehlsparametern).

Andere Arten von Warnungen finden Sie hier.
Jede Warnung wird durch die Farbe der Kachel (die Alarmstufe) und ein Logo oben links (die Alarmart) dargestellt).

> **Spitze**
>
> Hier werden alle Module in Alarmbereitschaft angezeigt, auch die unter "nicht sichtbar" konfigurierten". Es ist jedoch interessant festzustellen, dass, wenn das Modul "sichtbar" ist, die Warnung auch auf dem Dashboard (im betreffenden Objekt) sichtbar ist).

## Registerkarte &quot;Definierte Aktionen&quot;

Auf dieser Registerkarte können Sie die Aktionen anzeigen, die direkt in einem Auftrag definiert sind. In der Tat können wir verschiedene Befehle eingeben und es kann schwierig sein, sich an alle zu erinnern. Diese Registerkarte ist dafür da und fasst verschiedene Dinge zusammen :

- Aktionen zum Status (in den erweiterten Parametern von Info-Befehlen enthalten und die Ausführung einer oder mehrerer Aktionen für den Wert einer Bestellung - sofort oder nach einer Verzögerung).
- Bestätigungen von Aktionen (konfigurierbar an derselben Stelle in einem Info-Befehl und Ermöglichen des Anforderns einer Bestätigung zum Ausführen einer Aktion).
- Bestätigungen mit Code (wie oben, jedoch mit Eingabe eines Codes).
- Vor- und Nachaktionen (immer konfigurierbar an derselben Stelle in einem Aktionsbefehl und Ermöglichen der Ausführung einer oder mehrerer anderer Aktionen vor oder nach der betreffenden Aktion).

> **Spitze**
>
> Die Tabelle bietet eine sehr textuelle Ansicht der definierten Aktionen. Andere Arten definierter Aktionen können hinzugefügt werden.

## Registerkarte &quot;Definierte Warnungen&quot;

Auf dieser Registerkarte können Sie alle definierten Warnungen anzeigen. In einer Tabelle finden Sie die folgenden Informationen, falls vorhanden :

- Kommunikationsverzögerungswarnungen.
- Spezifische Batterieschwellenwerte, die auf einem Gerät definiert sind.
- Die verschiedenen Warn- und Warnbefehle.

## Registerkarte &quot;Orphan Orders&quot;

Auf dieser Registerkarte können Sie auf einen Blick sehen, ob Sie verwaiste Befehle haben, die über Jeedom verwendet werden. Ein Orphan-Befehl ist ein Befehl, der irgendwo verwendet wird, aber nicht mehr existiert. Wir finden hier alle diese Befehle, wie zum Beispiel :

- Verwaiste Befehle, die im Hauptteil eines Szenarios verwendet werden.
- diejenigen, die zum Auslösen eines Szenarios verwendet werden.

Und an vielen anderen Orten verwendet wie (nicht erschöpfend) :
- Wechselwirkungen.
- Jeedom-Konfigurationen.
- In vor oder nach der Aktion einer Bestellung.
- In Aktion auf Auftragsstatus.
- In einigen Plugins.

> **Spitze**
>
> Die Tabelle bietet eine sehr textuelle Ansicht der verwaisten Befehle. Ziel ist es, alle &quot;Orphan&quot; -Bestellungen über alle Jeedom und Plugins schnell identifizieren zu können. Es kann sein, dass einige Bereiche nicht analysiert werden und die Tabelle mit der Zeit immer umfassender wird.
