# Massen-Editor
**Einstellungen → System → Konfiguration | OS/DB**

Mit diesem Tool lassen sich zahlreiche Geräte, Befehle, Objekte oder Szenarien bearbeiten. Es ist vollkommen generisch und übernimmt automatisch das Schema und die Struktur der Jeedom-Datenbank. Somit unterstützt es die Plugins und die Konfiguration der dazugehörigen Geräte.

> **Achtung**
>
> Auch wenn sich dieses Tool als recht benutzerfreundlich erweist, richtet es sich doch an fortgeschrittene Nutzer. Tatsächlich ist es nämlich sehr einfach, beliebige Einstellungen an Dutzenden von Geräten oder Hunderten von Befehlen zu ändern und dadurch bestimmte Funktionen oder sogar den Core außer Betrieb zu setzen.

## Anwendung

Im Bereich *Filter* können Sie auswählen, was Sie bearbeiten möchten, und anschließend Auswahlfilter entsprechend deren Parametern hinzufügen. Über eine Testschaltfläche können Sie sich – ohne Änderungen vorzunehmen – die durch die eingegebenen Filter ausgewählten Elemente anzeigen lassen.

Im Bereich *Bearbeiten* können Sie die Einstellungen für diese Elemente ändern.

- **Spalte**: Parameter.
- **Wert**: Der Wert des Parameters.
- **JSON-Wert**: Die Eigenschaft des Parameters bzw. der Wert, wenn dieser vom Typ JSON ist (Schlüssel->Wert).

### Beispiele:

#### Eine Szenario-Gruppe umbenennen

- Wählen Sie im Bereich *Filter* die Option **Szenario** aus.
- Klicken Sie auf die Schaltfläche **+**, um einen Filter hinzuzufügen.
- Wählen Sie in diesem Filter die Spalte *group* aus und geben Sie als Wert den Namen der Gruppe ein, die umbenannt werden soll.
- Klicken Sie auf die Schaltfläche *Test*, um die Szenarien dieser Gruppe anzuzeigen.
- Wählen Sie im Bereich *Bearbeitung* die Spalte *Gruppe* aus und geben Sie dann den gewünschten Namen als Wert ein.
- Klicken Sie oben rechts auf **Ausführen**.

#### Alle Geräte eines Objekts/Raums unsichtbar machen:

- Wählen Sie im Bereich *Filter* die Option **Geräte** aus.
- Klicken Sie auf die Schaltfläche **+**, um einen Filter hinzuzufügen.
- Wählen Sie in diesem Filter die Spalte *object_id* aus und geben Sie als Wert die ID des betreffenden Objekts ein (zu finden unter „Extras/Objekte“, „Übersicht“).
- Klicken Sie auf die Schaltfläche *Test*, um die Szenarien dieser Gruppe anzuzeigen.
- Wählen Sie im Bereich *Bearbeitung* die Spalte *isvisible* aus und geben Sie dann den Wert 0 ein.
- Klicken Sie oben rechts auf **Ausführen**.
