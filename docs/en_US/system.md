# Systeme

Page reserved for advanced users, allows you to launch SSH commands directly from Jeedom

## Adding command

It is possible to add custom commands for this from the Jeedom editor (Configuration -> OS/DB -> File editor) you must create a `systemCustomCmd file.json` in `data`. The file must have the following form : 
```
[
   {
      "cmd":"my great order",
      "name":"name of my order"
   },
   {
      "cmd":"my super order 2",
      "name":"name of my order 2"
   }
]
```
