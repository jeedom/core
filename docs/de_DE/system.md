# Systeme

Die für fortgeschrittene Benutzer reservierte Seite ermöglicht es Ihnen, SSH-Befehle direkt von Jeedom aus zu starten

## Befehl hinzufügen

Es ist möglich, benutzerdefinierte Befehle hierfür über den Jeedom-Editor hinzuzufügen (Konfiguration -> OS/DB -> Dateieditor). Sie müssen eine „systemCustomCmd“-Datei erstellen.json` in `data`. Die Datei muss das folgende Format haben : 
„
[
   {
      "cmd":"meine tolle Bestellung",
      "name":"Name meiner Bestellung"
   },
   {
      "cmd":"meine Superbestellung 2",
      "name":"Name meiner Bestellung 2"
   }
]
„
