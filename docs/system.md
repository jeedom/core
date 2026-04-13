# Systeme

Page réservé aux utilisateurs avancés, permet de lancer des commandes SSH directement depuis Jeedom

## Ajout de commande

Il est possible d'ajouter des commandes personnalisée pour cela depuis l'éditeur Jeedom (Configuration -> OS/DB -> Editeur de fichier) il faut creer un fichier `systemCustomCmd.json` dans `data`. Le fichier doit avoir la forme suivante : 
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
