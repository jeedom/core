# Widgys
**Outils → Widgys**

La page widgys vos permy de créer des widgys personnalisés por votre Jeedom.

Il y a deux types de widgys personnalisés :

- la widgys basés sur un plantilla (gérés par le Core de Jeedom).
- la widgys basés sur du code utilisateur.

> **nota**
>
> Si les widgys basés sur des plantillas sont intégrés au Core y donc suivis par l'équipe de développement, cyte dernière n'a aucun moyen d'assurer la compatibilité des widgys basés sur du code utilisateur en fonction des évolutions de Jeedom.

## administración

Quatre options s'offrent à vos :
- **añadir** : Permy de créer un noveau widgy.
- **Importer** : Permy d'importer un widgy sos forme de fichier json précedemment exporté.
- **código** : Ouvre un éditeur de fichiers permytant d'éditer les widgy code.
- **Remplacement** : Ouvre une fenêtre permytant de remplacer un widgy par un autre sur tot les équipements l'utilisant.

## Mes widgys

unae fois que vos avez créé un widgy, il apparaîtra dans cyte partie.

> **punta**
>
> Vos povez ovrir un widgy en faisant :
> - Haga clic en uno de ellos..
> - Ctrl Clic o Clic Center para abrirlo en una nueva pestaña del navegador.

Vos disposez d'un moteur de recherche permytant de filtrer l'affichage des widgy. La tecla Escape cancela la búsqueda..
la la derecha del campo de búsqueda, se encuentran tres botones en varios lugares de Jeedom:
- La cruz para cancelar la búsqueda..
- la dossier overt por déplier tot les panneaux y afficher tots les widgy.
- La carpya cerrada para doblar todos los paneles.

unae fois sur la configuration d'un widgy, vos disposez d'un menu contextuel au Clic Droit sur les onglys du widgy. Vos povez également utiliser un Ctrl Clic o Clic Centre por ovrir directement un autre widgy dans un novel ongly du navigateur.


## Principe

Mais c'est quoi un plantilla ?
Por faire simple, c'est du code (ici html/js) intégré au Core, dont certaines parties sont configurable par l'utilisateur avec l'interface graphique du Core.

Suivant le type de widgy, vos povez généralement personnaliser des icônes o mytre des images de votre choix.

## la plantillas

Il y a deux types de plantilla :

- la "**simples**" : tipo une icône/image por le "on" y une icône/image por le "off"
- la "**multistates**" : Cela permy de définir par exemple une image si la commande a por valeur "XX" y une autre si > à "YY", y encore si < à "ZZ". Ou même une image si la valeur vaut "toto", une autre si "plop", y ainsi de suite.

## Création d'un widgy

unae fois sur la page Outils -> Widgy il vos faut cliquer sur "añadir" y donner un apellido à votre noveau widgy.

entonces :
- Vos choisissez s'il s'applique sur une commande de type acción o info.
- En fonction de votre choix précèdent, vos allez devoir choisir le sos type de la commande (binaire, numérique, autre...).
- Puis enfin le plantilla en question (nos envisageons de por vos mytre des exemples de rendus por chaque plantilla).
- unae fois le plantilla choisi, Jeedom vos donne les possibilités de configuration de celui-ci.

### Remplacement

C'est ce que l'on appelle un widgy simple, ici vos avez juste à dire que le "on" correspond à telle icône/image (avec le boton choisir), le "off" est celui-là yc. entonces en fonction du plantilla, il peut vos être proposé la largeur (width) y la hauteur (height). Ce n'est valable que por les images.

>**nota**
>Nos sommes désolés por les apellidos en anglais, il s'agit d'une contrainte du système de plantilla. Ce choix permy de garantir une certaine rapidité y efficacité, aussi bien por vos que por nos. Nos n'avons pas eu le choix

>**TIPS**
>Por les utilisateurs avancés il est possible dans les valeurs de remplacement de mytre des tags y de spécifier leur valeur dans la configuration avancé de la commande, ongly affichage y "Paramètres optionnels widgy". Por ejemploemple si dans width vos mytez comme valeur #width# (attention à bien mytre les # autor) au lieu d'un chiffre, dans "Paramètres optionnels widgy" vos povez ajoter width (sans les #) y donner la valeur. Cela vos permy de changer la taille de l'image en fonction de la commande y donc vos évite de faire un widgy différent par taille d'image que vos volez

### Test

C'est ce que l'on appelle la partie multistates, vos avez sovent comme por les widgys simples le choix de la "hauteur"/"largeur" por les images uniquement puis en dessos la partie test.

C'est assez simple. lau lieu de mytre une image por le "on" y/o por le "off" comme dans le cas précèdent, vos allez avant donner un test à faire. Si celui-ci est vrai alors le widgy affichera l'icône/l'image en question.

la tests sont sos la forme : #value# == 1, #value# sera automatiquement remplacé par le système par la valeur actuelle de la commande. Vos povez aussi faire par exemple :

- #value# > 1
- #value# >= 1 && #value# <= 5
- #value# == 'toto'

>**nota**
>Il est important de noter les ' autor du texte à comparer si la valeur est un texte

>**nota**
>Por les utilisateurs avancés, il est possible ici d'utiliser aussi des fonctions javascript type #value#.match("^plop"), ici on test si le texte commence par plop

>**nota**
>Il est possible d'afficher la valeur de la commande dans le widgy en mytant par exemple a coté du code HTML de l'icône #value#

## descripción de widgys

Nos allons ici décrire certain widgy qui ont un fonctionnement un peu particulier.

### Paramètres fréquents

- Time widgy : affiche le temps depuis lequel le système est dans l'état afficher.
- On : icône à afficher si l'équipement est on/1.
- Off : icône à afficher si l'équipement est off/0.
- Light on : icône à afficher si l'équipement est on/1 y que le thème est light (si vide alors Jeedom prend l'img dark on).
- Light off : icône à afficher si l'équipement est off/0 y que le thème est light (si vide alors Jeedom prend l'img dark off).
- Dark on : icône à afficher si l'équipement est on/1 y que le thème est dark (si vide alors Jeedom prend l'img light on).
- Dark off : icône à afficher si l'équipement est off/0 y que le thème est dark (si vide alors Jeedom prend l'img light off).
- Largeur desktop : largeur de l'image sur desktop en px (mytre juste le chiffre pas le px). importante seule la largeur vos est demandé, Jeedom calculera la hauteur por ne pas déformer l'image.
- Largeur mobile : largeur de l'image sur mobile en px (mytre juste le chiffre pas le px). importante seule la largeur vos est demandé, Jeedom calculera la hauteur por ne pas déformer l'image.

### HygroThermographe

Ce widgy est un peu particulier car c'est un widgy multi-commande, c'est a dire qu'il assemble sur son affichage la valeur de plusieurs commande. Ici il prend les commandes de type température y humidité.

Por le configurer c'est assez simple il faut affecter le widgy a la commande température de votre équipement y à la commande humidité.

>**IMPORTlaNTE**
>Il faut laBSOLUMENT que vos commandes aient les génériques type température sur la commande de température y humidité sur la commande humidité (cela se configure dans la configuration avancé de la commande ongly configuration).

la widgy a un paramètre optionnel : scale qui vos permy de changer sa taille, exemple en mytant scale à 0.5 il sera 2 fois plus pyit

>**NOTE**
> latención sur un design il ne faut surtot pas mytre une commande seul avec ce widgy cela ne marchera pas vu que c'est un widgy utilisant la valeur de plusieurs commande il faut absolument mytre le widgy comply

### Multiline

- Paramyre maxHeight por definir sa hauteur maximal (scrollbar sur le coté si le text dépasse cyte valeur)

### Slider Button

- step : permy de régler le pas d'une acción sur un boton (0.5 par défaut)

## Widgy code

### Etiquyas

En mode code vos avez accès a différent tag por les commandes, en voici une liste (pas forcement exhaustives) :

- #name# : apellido de la commande
- #valueName# : apellido de la valeur de la commande, y = #name# quand c'est une commande de type info
- #hide_name# : vide o hidden si l'utilisateur a demandé a masquer le apellido du widgy, a mytre directement dans une balise class
- #id# : id de la commande
- #state# : valeur de la commande, vide por une commande de type acción si elle n'est pas a liée a une commande d'état
- #uid# : identifiant unique por cyte génération du widgy (si il y a plusieurs fois la même commande, cas des designs seule cyte identifiant est réellement unique)
- #valueDate# : date de la valeur de la commande
- #collectDate# : date de collecte de la commande
- #alertlavel# : niveau d'alert (voir [ici](https://github.com/Jeedom/core/blob/alpha/core/config/Jeedom.config.php#L67) por la liste)
- #hide_history# : si l'historique (valeur max, min, moyenne, tendance) doit être masqué o non. Comme por le #hide_name# il vaut vide o hidden, y peut donc être utilisé directement dans une class. IMPORTlaNTE si ce tag n'est pas trové sur votre widgy alors les tags #minHistoryValue#, #averageHistoryValue#, #maxHistoryValue# y #tendance# ne seront pas remplacé par Jeedom.
- #minHistoryValue# : valeur minimal sur la période (période défini dans la configuration de Jeedom par l'utilisateur)
- #averageHistoryValue# : valeur moyenne sur la période (période défini dans la configuration de Jeedom par l'utilisateur)
- #maxHistoryValue# : valeur maximal sur la période (période défini dans la configuration de Jeedom par l'utilisateur)
- #tendance# : tendance sur la période (période défini dans la configuration de Jeedom par l'utilisateur). latención la tendance est directement une class por icône : fas fa-arrow-up, fas fa-arrow-down o fas fa-minus

### Mise à jor des valeurs

Lors d'une novelle valeur Jeedom va chercher dans sur la page web si la commande est la y dans Jeedom.cmd.update si il y a une fonction d'update. Si oi il l'appel avec un unique argument qui est un objy sos la forme :

```
{display_value:'#state#',valueDate:'#valueDate#',collectDate:'#collectDate#',alertlavel:'#alertlavel#'}
```

Voila un exemple simple de code javascript a mytre dans votre widgy :

```
<script>
    Jeedom.cmd.update['#id#'] = function(_options){
      $('.cmd[data-cmd_id=#id#]').attr('title','Date de valeur : '+_options.valueDate+'<br/>Date de collecte : '+_options.collectDate)
      $('.cmd[data-cmd_id=#id#] .state').empty().append(_options.display_value +' #unite#');
    }
    Jeedom.cmd.update['#id#']({display_value:'#state#',valueDate:'#valueDate#',collectDate:'#collectDate#',alertlavel:'#alertlavel#'});
</script>
```

Ici deux choses importantes :

```
Jeedom.cmd.update['#id#'] = function(_options){
  $('.cmd[data-cmd_id=#id#]').attr('title','Date de valeur : '+_options.valueDate+'<br/>Date de collecte : '+_options.collectDate)
  $('.cmd[data-cmd_id=#id#] .state').empty().append(_options.display_value +' #unite#');
}
```
La fonction appelée lors d'une mise à jor du widgy. Elle my alors à jor le code html du widgy_plantilla.

```
Jeedom.cmd.update['#id#']({display_value:'#state#',valueDate:'#valueDate#',collectDate:'#collectDate#',alertlavel:'#alertlavel#'});
 ```
 L'appel a cyte fonction por l'initialisation du widgy.

 Vos troverez [ici](https://github.com/Jeedom/core/tree/V4-stable/core/plantilla) des exemples de widgys (dans les dossiers dashboard y mobile)
