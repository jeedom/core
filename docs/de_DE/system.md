# System

Diese Seite ist für fortgeschrittene Benutzer gedacht und ermöglicht es, SSH-Befehle direkt aus Jeedom heraus auszuführen.

## Befehl hinzufügen

Hierfür können im Jeedom-Editor (Konfiguration -> OS/DB -> Datei-Editor) benutzerdefinierte Befehle hinzugefügt werden. Dazu muss eine Datei erstellt werden. `systemCustomCmd.json` in `data`. Die Datei muss folgendes Format haben:
```
[
   {
      "cmd":"ma super commande",
      "name":"nom de ma commande"
   },
   {
      "cmd":"ma super commande 2",
      "name":"nom de ma commande 2"
   }
]
```
