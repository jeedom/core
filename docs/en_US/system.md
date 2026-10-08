# System

This page is intended for advanced users and allows you to run SSH commands directly from Jeedom

## Add a command

You can add custom commands for this using the Jeedom editor (Configuration -> OS/DB -> File Editor); you'll need to create a file `systemCustomCmd.json` in `data`. The file must have the following format:
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
