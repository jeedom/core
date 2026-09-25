# Sistema

Página reservada a usuarios avanzados, que permite ejecutar comandos SSH directamente desde Jeedom

## Añadir comando

Es posible añadir comandos personalizados para ello desde el editor de Jeedom (Configuración -> SO/BD -> Editor de archivos); para ello, hay que crear un archivo `systemCustomCmd.json` en `data`. El archivo debe tener el siguiente formato:
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
