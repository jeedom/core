# Masseneditor
**Einstellungen → System → Konfiguration | Betriebssystem / DB**

Mit diesem Tool können Sie eine große Anzahl von Geräten, Befehlen, Objekten oder Szenarien bearbeiten. Es ist vollständig generisch und verwendet automatisch das Schema und die Struktur der Jeedom-Datenbank. Es unterstützt somit Plugins und die Konfiguration ihrer Geräte.

> **Warnung**
>
> Wenn dieses Tool recht einfach zu bedienen ist, ist es für fortgeschrittene Benutzer gedacht. Tatsächlich ist es sehr einfach, jeden Parameter auf Dutzenden von Geräten oder Hunderten von Befehlen zu ändern und daher bestimmte Funktionen außer Betrieb zu setzen, siehe sogar den Core.

## Utilisation

Das Teil *Gefiltert* ermöglicht es Ihnen, auszuwählen, was Sie bearbeiten möchten, und fügen Sie dann Auswahlfilter gemäß ihren Parametern hinzu. Eine Testschaltfläche ermöglicht es Ihnen, ohne Änderungen die Elemente anzuzeigen, die durch die eingegebenen Filter ausgewählt wurden.

Das Teil *Bearbeitung* ermöglicht es Ihnen, Parameter dieser Elemente zu ändern.

- **Säule** : Rahmen.
- **Wert** : Der Wert des Parameters.
- **Json-Wert** : Die Eigenschaft des Parameters / Werts, wenn er vom Typ json ist (Schlüssel-> Wert).

### Exemples:

#### Benennen Sie eine Szenariogruppe um

- Im Spiel *Gefiltert*, wählen **Szenario**.
- Klicken Sie auf die Schaltfläche **+** einen Filter hinzufügen.
- Wählen Sie in diesem Filter die Spalte *Gruppe*, und markieren Sie den Namen der Gruppe, die Sie umbenennen möchten.
- Klicken Sie auf die Schaltfläche *Prüfung* um die Szenarien dieser Gruppe anzuzeigen.
- Im Spiel *Bearbeitung*, Spalte auswählen *Gruppe*, dann geben Sie den gewünschten Namen in den Wert ein.
- Klicke auf **Ausführen** oben rechts.

#### Machen Sie die gesamte Ausrüstung eines Objekts / Raums unsichtbar:

- Im Spiel *Gefiltert*, wählen **Ausrüstung**.
- Klicken Sie auf die Schaltfläche **+** einen Filter hinzufügen.
- Wählen Sie in diesem Filter die Spalte *Objekt Identifikation*, und als Wert die ID des betreffenden Objekts (sichtbar unter Tools / Objects, Overview).
- Klicken Sie auf die Schaltfläche *Prüfung* um die Szenarien dieser Gruppe anzuzeigen.
- Im Spiel *Bearbeitung*, Spalte auswählen *ist sichtbar*, dann geben Sie den Wert 0 ein.
- Klicke auf **Ausführen** oben rechts.