# Systeme

Página reservada para usuarios avanzados, le permite ejecutar comandos SSH directamente desde Jeedom

## Agregar comando

Es posible agregar comandos personalizados para esto desde el editor Jeedom (Configuración -> OS/DB -> Editor de archivos). Debe crear un archivo `systemCustomCmd.json` en `datos`. El archivo debe tener la siguiente forma : 
```
[
   {
      "cmd":"mi gran orden",
      "name":"nombre de mi pedido"
   },
   {
      "cmd":"mi super pedido 2",
      "name":"nombre de mi pedido 2"
   }
]
```
