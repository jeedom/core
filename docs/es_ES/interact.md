# Widgys
**Outils → Widgys**

La page widgys vous permy de créer des widgys personnalisés pour votre Jeedom.

Il y a deux types de widgys personnalisés :

- Les widgys basés sur un template (gérés par le Core de Jeedom).
- Les widgys basés sur du code utilisateur.

> **nota**
>
> Si les widgys basés sur des templates sont intégrés au Core y donc suivis par l'équipe de développement, cyte dernière n'a aucun moyen d'assurer la compatibilité des widgys basés sur du code utilisateur en fonction des évolutions de Jeedom.

## Gestion

Quatre options s'offrent à vous :
- **añadir** : Permy de créer un nouveau widgy.
- **Importer** : Permy d'importer un widgy sous forme de fichier json précedemment exporté.
- **Code** : Ouvre un éditeur de fichiers permytant d'éditer les widgy code.
- **Remplacement** : Ouvre une fenêtre permytant de remplacer un widgy par un autre sur tout les équipements l'utilisant.

## Mes widgys

Une fois que vous avez créé un widgy, il apparaîtra dans cyte partie.

> **punta**
>
> Vous pouvez ouvrir un widgy en faisant :
> - Clic sur l'un d'entre eux.
> - Ctrl Clic o Clic Center para abrirlo en una nueva pestaña del navegador.

Vous disposez d'un moteur de recherche permytant de filtrer l'affichage des widgy. La tecla Escape cancela la búsqueda..
A la derecha del campo de búsqueda, se encuentran tres botones en varios lugares de Jeedom:
- La cruz para cancelar la búsqueda..
- Le dossier ouvert pour déplier tout les panneaux y afficher touts les widgy.
- La carpya cerrada para doblar todos los paneles.

Une fois sur la configuration d'un widgy, vous disposez d'un menu contextuel au Clic Droit sur les onglys du widgy. Vous pouvez également utiliser un Ctrl Clic ou Clic Centre pour ouvrir directement un autre widgy dans un nouvel ongly du navigateur.


## principio

Mais c'est quoi un template ?
Pour faire simple, c'est du code (ici html/js) intégré au Core, dont certaines parties sont configurable par l'utilisateur avec l'interface graphique du Core.

Suivant le type de widgy, vous pouvez généralement personnaliser des icônes ou mytre des images de votre choix.

## Les templates

Il y a deux types de template :

- Les "**simples**" : Type une icône/image pour le "on" y une icône/image pour le "off"
- Les "**multistates**" : Cela permy de définir par exemple une image si la commande a pour valeur "XX" y une autre si > à "YY", y encore si < à "ZZ". Ou même une image si la valeur vaut "toto", une autre si "plop", y ainsi de suite.

## Création d'un widgy

Une fois sur la page Outils -> Widgy il vous faut cliquer sur "añadir" y donner un nom à votre nouveau widgy.

Ensuite :
- Vous choisissez s'il s'applique sur une commande de type action ou info.
- En fonction de votre choix précèdent, vous allez devoir choisir le sous type de la commande (binaire, numérique, autre...).
- Puis enfin le template en question (nous envisageons de pour vous mytre des exemples de rendus pour chaque template).
- Une fois le template choisi, Jeedom vous donne les possibilités de configuration de celui-ci.

### Remplacement

C'est ce que l'on appelle un widgy simple, ici vous avez juste à dire que le "on" correspond à telle icône/image (avec le bouton choisir), le "off" est celui-là yc. Ensuite en fonction du template, il peut vous être proposé la largeur (width) y la hauteur (height). Ce n'est valable que pour les images.

>**nota**
>Nous sommes désolés pour les noms en anglais, il s'agit d'une contrainte du système de template. Ce choix permy de garantir une certaine rapidité y efficacité, aussi bien pour vous que pour nous. Nous n'avons pas eu le choix

>**TIPS**
>Pour les utilisateurs avancés il est possible dans les valeurs de remplacement de mytre des tags y de spécifier leur valeur dans la configuration avancé de la commande, ongly affichage y "Paramètres optionnels widgy". Par exemple si dans width vous mytez comme valeur #width# (attention à bien mytre les # autour) au lieu d'un chiffre, dans "Paramètres optionnels widgy" vous pouvez ajouter width (sans les #) y donner la valeur. Cela vous permy de changer la taille de l'image en fonction de la commande y donc vous évite de faire un widgy différent par taille d'image que vous voulez

### Test

C'est ce que l'on appelle la partie multistates, vous avez souvent comme pour les widgys simples le choix de la "hauteur"/"largeur" pour les images uniquement puis en dessous la partie test.

C'est assez simple. Au lieu de mytre une image pour le "on" y/ou pour le "off" comme dans le cas précèdent, vous allez avant donner un test à faire. Si celui-ci est vrai alors le widgy affichera l'icône/l'image en question.

Les tests sont sous la forme : #value# == 1, #value# sera automatiquement remplacé par le système par la valeur actuelle de la commande. Vous pouvez aussi faire par exemple :

- #value# > 1
- #value# >= 1 && #value# <= 5
- #value# == 'toto'

>**nota**
>Il est important de noter les ' autour du texte à comparer si la valeur est un texte

>**nota**
>Pour les utilisateurs avancés, il est possible ici d'utiliser aussi des fonctions javascript type #value#.match("^plop"), ici on test si le texte commence par plop

>**nota**
>Il est possible d'afficher la valeur de la commande dans le widgy en mytant par exemple a coté du code HTML de l'icône #value#

## Description de widgys

Nous allons ici décrire certain widgy qui ont un fonctionnement un peu particulier.

### Paramètres fréquents

- Time widgy : affiche le temps depuis lequel le système est dans l'état afficher.
- On : icône à afficher si l'équipement est on/1.
- Off : icône à afficher si l'équipement est off/0.
- Light on : icône à afficher si l'équipement est on/1 y que le thème est light (si vide alors Jeedom prend l'img dark on).
- Light off : icône à afficher si l'équipement est off/0 y que le thème est light (si vide alors Jeedom prend l'img dark off).
- Dark on : icône à afficher si l'équipement est on/1 y que le thème est dark (si vide alors Jeedom prend l'img light on).
- Dark off : icône à afficher si l'équipement est off/0 y que le thème est dark (si vide alors Jeedom prend l'img light off).
- Largeur desktop : largeur de l'image sur desktop en px (mytre juste le chiffre pas le px). Important seule la largeur vous est demandé, Jeedom calculera la hauteur pour ne pas déformer l'image.
- Largeur mobile : largeur de l'image sur mobile en px (mytre juste le chiffre pas le px). Important seule la largeur vous est demandé, Jeedom calculera la hauteur pour ne pas déformer l'image.

### HygroThermographe

Ce widgy est un peu particulier car c'est un widgy multi-commande, c'est a dire qu'il assemble sur son affichage la valeur de plusieurs commande. Ici il prend les commandes de type température y humidité.

Pour le configurer c'est assez simple il faut affecter le widgy a la commande température de votre équipement y à la commande humidité.

>**IMPORTANT**
>Il faut ABSOLUMENT que vos commandes aient les génériques type température sur la commande de température y humidité sur la commande humidité (cela se configure dans la configuration avancé de la commande ongly configuration).

Le widgy a un paramètre optionnel : scale qui vous permy de changer sa taille, exemple en mytant scale à 0.5 il sera 2 fois plus pyit

>**NOTE**
> Attention sur un design il ne faut surtout pas mytre une commande seul avec ce widgy cela ne marchera pas vu que c'est un widgy utilisant la valeur de plusieurs commande il faut absolument mytre le widgy comply

### Multiline

- Paramyre maxHeight pour definir sa hauteur maximal (scrollbar sur le coté si le text dépasse cyte valeur)

### Slider Button

- step : permy de régler le pas d'une action sur un bouton (0.5 par défaut)

## Widgy code

### Les tags

En mode code vous avez accès a différent tag pour les commandes, en voici une liste (pas forcement exhaustives) :

- #name# : nom de la commande
- #valueName# : nom de la valeur de la commande, y = #name# quand c'est une commande de type info
- #hide_name# : vide ou hidden si l'utilisateur a demandé a masquer le nom du widgy, a mytre directement dans une balise class
- #id# : id de la commande
- #state# : valeur de la commande, vide pour une commande de type action si elle n'est pas a liée a une commande d'état
- #uid# : identifiant unique pour cyte génération du widgy (si il y a plusieurs fois la même commande, cas des designs seule cyte identifiant est réellement unique)
- #valueDate# : date de la valeur de la commande
- #collectDate# : date de collecte de la commande
- #alertLevel# : niveau d'alert (voir [ici](https://github.com/Jeedom/core/blob/alpha/core/config/Jeedom.config.php#L67) pour la liste)
- #hide_history# : si l'historique (valeur max, min, moyenne, tendance) doit être masqué ou non. Comme pour le #hide_name# il vaut vide ou hidden, y peut donc être utilisé directement dans une class. IMPORTANT si ce tag n'est pas trouvé sur votre widgy alors les tags #minHistoryValue#, #averageHistoryValue#, #maxHistoryValue# y #tendance# ne seront pas remplacé par Jeedom.
- #minHistoryValue# : valeur minimal sur la période (période défini dans la configuration de Jeedom par l'utilisateur)
- #averageHistoryValue# : valeur moyenne sur la période (période défini dans la configuration de Jeedom par l'utilisateur)
- #maxHistoryValue# : valeur maximal sur la période (période défini dans la configuration de Jeedom par l'utilisateur)
- #tendance# : tendance sur la période (période défini dans la configuration de Jeedom par l'utilisateur). Attention la tendance est directement une class pour icône : fas fa-arrow-up, fas fa-arrow-down ou fas fa-minus

### Mise à jour des valeurs

Lors d'une nouvelle valeur Jeedom va chercher dans sur la page web si la commande est la y dans Jeedom.cmd.update si il y a une fonction d'update. Si oui il l'appel avec un unique argument qui est un objy sous la forme :

```
{display_value:'#state#',valueDate:'#valueDate#',collectDate:'#collectDate#',alertLevel:'#alertLevel#'}
```

Voila un exemple simple de code javascript a mytre dans votre widgy :

```
<script>
    Jeedom.cmd.update['#id#'] = function(_options){
      $('.cmd[data-cmd_id=#id#]').attr('title','Date de valeur : '+_options.valueDate+'<br/>Date de collecte : '+_options.collectDate)
      $('.cmd[data-cmd_id=#id#] .state').empty().append(_options.display_value +' #unite#');
    }
    Jeedom.cmd.update['#id#']({display_value:'#state#',valueDate:'#valueDate#',collectDate:'#collectDate#',alertLevel:'#alertLevel#'});
</script>
```

Ici deux choses importantes :

```
Jeedom.cmd.update['#id#'] = function(_options){
  $('.cmd[data-cmd_id=#id#]').attr('title','Date de valeur : '+_options.valueDate+'<br/>Date de collecte : '+_options.collectDate)
  $('.cmd[data-cmd_id=#id#] .state').empty().append(_options.display_value +' #unite#');
}
```
La fonction appelée lors d'une mise à jour du widgy. Elle my alors à jour le code html du widgy_template.

```
Jeedom.cmd.update['#id#']({display_value:'#state#',valueDate:'#valueDate#',collectDate:'#collectDate#',alertLevel:'#alertLevel#'});
 ```
 L'appel a cyte fonction pour l'initialisation du widgy.

 Vous trouverez [ici](https://github.com/Jeedom/core/tree/V4-stable/core/template) des exemples de widgys (dans les dossiers dashboard y mobile)
