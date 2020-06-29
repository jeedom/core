Auf der Seite Geräteanalyse können Sie viele Informationen anzeigen
in Bezug auf Geräte zentral :

-   den Zustand Ihrer Batterien

-   Module in Alarmbereitschaft

-   definierte Aktionen

-   definierte Warnungen

-   Orphan Orders

Die Registerkarte Batterien 
==================

Auf dieser Registerkarte sehen Sie die Liste Ihrer Module im Akkubetrieb,
ihre verbleibende Stufe (die Farbe der Kachel hängt von dieser Stufe ab), die
Art und Anzahl der in das Modul einzulegenden Batterien, Art der
Modul sowie das Datum, an dem die Informationen zum Batteriestand angezeigt werden
wurde aktualisiert. Sie können auch sehen, ob ein bestimmter Schwellenwert festgelegt wurde
Werkbank für das jeweilige Modul (dargestellt durch eine Hand)

> **Spitze**
>
> Die Alarm- / Warnschwellen für den Batteriestand sind
> global konfigurierbar in der Jeedom-Konfiguration
> (Administration → Registerkarte "Ausrüstung") oder nach Ausrüstung auf der Seite
> Erweiterte Konfiguration dieser auf der Registerkarte Warnungen.

Module auf der Registerkarte &quot;Warnung&quot; 
==========================

Auf dieser Registerkarte sehen Sie in Echtzeit die Module in Alarmbereitschaft. die
Es gibt verschiedene Arten von Warnungen :

-   Zeitüberschreitung (konfiguriert auf der Registerkarte "Definierte Warnungen"))

-   Batterie in Warnung oder in Gefahr

-   Befehl in Warnung oder Gefahr (konfigurierbar in den Parametern
    erweiterte Befehle)

Andere Arten von Warnungen finden Sie hier.
Jede Warnung wird durch die Farbe der Kachel (die Ebene) dargestellt
Warnung) und ein Logo oben links (die Art der Warnung)

> **Spitze**
>
> Hier werden alle Module in Alarmbereitschaft angezeigt, auch die in konfigurierten
> "nicht sichtbar". Es ist jedoch interessant festzustellen, dass wenn das Modul
> Ist &quot;sichtbar&quot;, wird die Warnung auch im Dashboard angezeigt (in
> das betreffende Objekt)

Die Registerkarte Definierte Aktionen 
=========================

Auf dieser Registerkarte können Sie die Aktionen anzeigen, die direkt auf a definiert sind
Befehl. In der Tat können wir verschiedene Aufträge erteilen und es
kann schwierig sein, sich an alle zu erinnern. Diese Registerkarte ist dafür da
und synthetisiert mehrere Dinge :

-   Aktionen auf Status (in erweiterten Parametern gefunden
    info Befehle und verwendet, um einen oder mehrere auszuführen
    Aktionen auf den Wert einer Bestellung - sofort oder danach
    eine Verzögerung)

-   Aktionsbestätigungen (konfigurierbar an derselben Stelle auf a
    Befehlsinfo und Zulassen einer Bestätigung für
    eine Aktion ausführen)

-   Bestätigungen mit Code (wie oben, jedoch mit
    Eingabe eines Codes)

-   Vor- und Nachaktionen (immer an derselben Stelle konfigurierbar
    einen Aktionsbefehl und das Ausführen eines oder mehrerer anderer
    Aktionen vor oder nach der betreffenden Aktion)

> **Spitze**
>
> In der Tabelle können Sie die Aktionen sehr textuell anzeigen
> definiert. Andere Arten definierter Aktionen können hinzugefügt werden.

Die Registerkarte Definierte Warnungen 
=========================

Auf dieser Registerkarte können Sie alle definierten Warnungen anzeigen
Finden Sie in einer Tabelle die folgenden Informationen, falls vorhanden :

-   Kommunikationsverzögerungswarnungen

-   spezifische Batterieschwellenwerte, die auf einem Gerät definiert sind

-   die verschiedenen Warn- und Warnbefehle

Die Registerkarte Orphan Orders 
=============================

Auf dieser Registerkarte können Sie auf einen Blick sehen, ob Sie welche haben
verwaiste Befehle, die durch Jeedom verwendet werden. Eine Bestellung
Orphan ist ein Befehl, der irgendwo verwendet wird, aber nicht mehr existiert.
Wir finden hier alle diese Befehle, wie zum Beispiel :

-   verwaiste Befehle, die im Hauptteil eines Szenarios verwendet werden

-   diejenigen, die zum Auslösen eines Szenarios verwendet werden

Und an vielen anderen Orten verwendet wie (nicht erschöpfend) :

-   Wechselwirkungen

-   Jeedom-Konfigurationen

-   in vor oder nach der Aktion einer Bestellung

-   in Aktion auf Auftragsstatus

-   in einigen Plugins

> **Spitze**
>
> Die Tabelle bietet eine sehr textuelle Ansicht der Befehle
> Waise. Ihr Zweck ist es, alle schnell identifizieren zu können
> &quot;Orphan&quot; -Befehle über alle Jeedom und Plugins. Es ist
> Einige Bereiche werden möglicherweise nicht analysiert, die Tabelle wird
> im Laufe der Zeit immer erschöpfender sein.
