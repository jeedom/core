# Geräteanalyse
**Analyse → Geräte**

Auf der Seite „Geräteanalyse“ können Sie zahlreiche Informationen zu den Geräten zentral einsehen:

- Der Ladezustand Ihrer Akkus
- Die Benachrichtigungsmodule
- Die definierten Aktionen
- Die definierten Benachrichtigungen
- Verwaise Befehle

## Registerkarte „Batterien“


Auf dieser Registerkarte sehen Sie eine Liste Ihrer batteriebetriebenen Module, deren verbleibenden Ladezustand (die Farbe der Kachel hängt vom Ladezustand ab), die Art und Anzahl der Batterien, die in das Modul eingelegt werden müssen, den Modultyp sowie das Datum, an dem die Informationen zum Ladezustand aktualisiert wurden. Außerdem können Sie sehen, ob für das jeweilige Modul ein bestimmter Schwellenwert festgelegt wurde (dargestellt durch eine Hand).

> **Tipp**
>
> Die Warnschwellenwerte für den Zustand der Batterie können global in der Jeedom-Konfiguration (Einstellungen → Systeme → Konfiguration: Geräte) oder gerätespezifisch auf der Seite „Erweiterte Konfiguration“ des jeweiligen Geräts auf der Registerkarte „Warnungen“ festgelegt werden.

## Registerkarte „Module mit Warnmeldungen“

Auf dieser Registerkarte sehen Sie in Echtzeit, welche Module einen Alarm auslösen. Es gibt verschiedene Arten von Alarmen:

- Timeout (konfiguriert auf der Registerkarte „Definierte Warnmeldungen“).
- Batterie im Warnmodus oder in Gefahr.
- Befehl im Warn- oder Gefahrenmodus (konfigurierbar in den erweiterten Befehlseinstellungen).

Möglicherweise werden hier auch andere Arten von Benachrichtigungen aufgeführt.
Jeder Alarm wird durch die Farbe der Kachel (Alarmstufe) und ein Logo oben links (Alarmtyp) dargestellt.

> **Tipp**
>
> Hier werden alle Module angezeigt, für die eine Warnmeldung vorliegt, auch diejenigen, die als „nicht sichtbar“ konfiguriert sind. Es ist jedoch zu beachten, dass, wenn das Modul als „sichtbar“ eingestellt ist, die Warnmeldung auch auf dem Dashboard (im entsprechenden Objekt) angezeigt wird.

## Registerkarte „Definierte Aktionen“

Auf dieser Registerkarte können Sie die direkt für einen Befehl definierten Aktionen anzeigen. Da man diese auf verschiedene Befehle anwenden kann, ist es manchmal schwierig, sich alle zu merken. Genau dafür ist diese Registerkarte da und fasst verschiedene Informationen zusammen:

- Zustandsabhängige Aktionen (die in den erweiterten Einstellungen der Befehle „Info“ zu finden sind und es ermöglichen, eine oder mehrere Aktionen auf den Wert eines Befehls anzuwenden – entweder sofort oder nach einer bestimmten Verzögerung).
- Aktionsbestätigungen (an derselben Stelle über einen Info-Befehl konfigurierbar, wodurch eine Bestätigung zur Ausführung einer Aktion angefordert werden kann).
- Bestätigungen mit Code (wie zuvor, jedoch mit Eingabe eines Codes).
- Vor- und Nachaktionen (die immer an derselben Stelle über einen Aktionsbefehl konfiguriert werden können und es ermöglichen, vor oder nach der betreffenden Aktion eine oder mehrere weitere Aktionen auszuführen).

> **Tipp**
>
> Die Tabelle bietet einen sehr anschaulichen Überblick über die definierten Aktionen. Weitere Arten von definierten Aktionen können hinzugefügt werden.

## Registerkarte „Definierte Benachrichtigungen“

Auf dieser Registerkarte können Sie alle definierten Benachrichtigungen einsehen. Dort finden Sie in einer Tabelle die folgenden Informationen, sofern vorhanden:

- Warnmeldungen bei Kommunikationsverzögerungen.
- Die für ein Gerät festgelegten spezifischen Schwellenwerte für den Zustand der Batterie.
- Die verschiedenen Gefahren- und Warnmeldungen der Steuerungen.

## Registerkarte „Verwaiste Befehle“

Auf dieser Registerkarte können Sie auf einen Blick erkennen, ob Sie in Jeedom verwaiste Befehle verwenden. Ein verwaistes Befehl ist ein Befehl, der an einer Stelle verwendet wird, aber nicht mehr existiert. Hier werden alle diese Befehle aufgelistet, wie zum Beispiel:

- Verwaiste Befehle, die im Hauptteil eines Szenarios verwendet werden.
- Diejenigen, die als Auslöser für ein Szenario verwendet werden.

Und an vielen anderen Orten eingesetzt, wie zum Beispiel (ohne Anspruch auf Vollständigkeit):
- Interaktionen.
- Die Konfigurationen von Jeedom.
- Als Vor- oder Nachmaßnahme eines Befehls.
- Aktion basierend auf dem Status einer Bestellung.
- In einigen Plugins.

Wenn die ID des verwaisten Befehls noch im Löschverlauf vorhanden ist (einsehbar unter „Analyse / Hausautomationsübersicht“), werden dessen früherer Name und das Löschdatum angezeigt.

> **Tipp**
>
> Die Tabelle bietet einen sehr übersichtlichen Überblick über die „verwaisten“ Befehle. Ihr Ziel ist es, alle „verwaisten“ Befehle in Jeedom und den Plugins schnell identifizieren zu können. Es kann sein, dass bestimmte Bereiche noch nicht analysiert wurden; die Tabelle wird jedoch mit der Zeit immer vollständiger werden.
