# Widgets
**Outils → Widgets**

La page widgets vos permet de créer des widgets personnalisés por votre Jeedom.

Il y a deux types de widgets personnalisés :

- las widgets basés sur un plantilla (gérés par le Core de Jeedom).
- las widgets basés sur du code utilisateur.

> **nota**
>
> Si les widgets basés sur des plantillas sont intégrés au Core et donc suivis par l'équipe de développement, cette dernière n'a aucun moyen d'assurer la compatibilité des widgets basés sur du code utilisateur en fonction des évolutions de Jeedom.

## administración

Quatre options s'offrent à vos :
- **añadir** : Permet de créer un noveau widget.
- **Importer** : Permet d'importer un widget sos forme de fichier json précedemment exporté.
- **código** : Ouvre un éditeur de fichiers permettant d'éditer les widget code.
- **Remplacement** : Ouvre une fenêtre permettant de remplacer un widget par un autre sur tot les équipements l'utilisant.

## Mes widgets

unae fois que vos avez créé un widget, il apparaîtra dans cette partie.

> **punta**
>
> Vos povez ovrir un widget en faisant :
> - Haga clic en uno de ellos..
> - Ctrl Clic o Clic Center para abrirlo en una nueva pestaña del navegador.

Vos disposez d'un moteur de recherche permettant de filtrer l'affichage des widget. La tecla Escape cancela la búsqueda..
la la derecha del campo de búsqueda, se encuentran tres botones en varios lugares de Jeedom:
- La cruz para cancelar la búsqueda..
- la dossier overt por déplier tot les panneaux et afficher tots les widget.
- La carpeta cerrada para doblar todos los paneles.

unae fois sur la configuration d'un widget, vos disposez d'un menu contextuel au Clic Droit sur les onglets du widget. Vos povez également utiliser un Ctrl Clic o Clic Centre por ovrir directement un autre widget dans un novel onglet du navigateur.


## Principe

Mais c'est quoi un plantilla ?
Por faire simple, c'est du code (ici html/js) intégré au Core, dont certaines parties sont configurable par l'utilisateur avec l'interface graphique du Core.

Suivant le type de widget, vos povez généralement personnaliser des icônes o mettre des images de votre choix.

## las plantillas

Il y a deux types de plantilla :

- las "**simples**" : tipo une icône/image por le "on" et une icône/image por le "off"
- las "**multistates**" : Cela permet de définir par exemple une image si la commande a por valeur "XX" et une autre si > à "YY", et encore si < à "ZZ". Ou même une image si la valeur vaut "toto", une autre si "plop", et ainsi de suite.

## Création d'un widget

unae fois sur la page Outils -> Widget il vos faut cliquer sur "añadir" et donner un nom à votre noveau widget.

entonces :
- Vos choisissez s'il s'applique sur une commande de type acción o info.
- En fonction de votre choix précèdent, vos allez devoir choisir le sos type de la commande (binaire, numérique, autre...).
- Puis enfin le plantilla en question (nos envisageons de por vos mettre des exemples de rendus por chaque plantilla).
- unae fois le plantilla choisi, Jeedom vos donne les possibilités de configuration de celui-ci.

### Remplacement

C'est ce que l'on appelle un widget simple, ici vos avez juste à dire que le "on" correspond à telle icône/image (avec le boton choisir), le "off" est celui-là etc. entonces en fonction du plantilla, il peut vos être proposé la largeur (width) et la hauteur (height). Ce n'est valable que por les images.

>**nota**
>Nos sommes désolés por les noms en anglais, il s'agit d'une contrainte du système de plantilla. Ce choix permet de garantir une certaine rapidité et efficacité, aussi bien por vos que por nos. Nos n'avons pas eu le choix

>**TIPS**
>Por les utilisateurs avancés il est possible dans les valeurs de remplacement de mettre des tags et de spécifier leur valeur dans la configuration avancé de la commande, onglet affichage et "Paramètres optionnels widget". Por ejemploemple si dans width vos mettez comme valeur #width# (attention à bien mettre les # autor) au lieu d'un chiffre, dans "Paramètres optionnels widget" vos povez ajoter width (sans les #) et donner la valeur. Cela vos permet de changer la taille de l'image en fonction de la commande et donc vos évite de faire un widget différent par taille d'image que vos volez

### Test

C'est ce que l'on appelle la partie multistates, vos avez sovent comme por les widgets simples le choix de la "hauteur"/"largeur" por les images uniquement puis en dessos la partie test.

C'est assez simple. lau lieu de mettre une image por le "on" et/o por le "off" comme dans le cas précèdent, vos allez avant donner un test à faire. Si celui-ci est vrai alors le widget affichera l'icône/l'image en question.

las tests sont sos la forme : #value# == 1, #value# sera automatiquement remplacé par le système par la valeur actuelle de la commande. Vos povez aussi faire par exemple :

- #value# > 1
- #value# >= 1 && #value# <= 5
- #value# == 'toto'

>**nota**
>Il est important de noter les ' autor du texte à comparer si la valeur est un texte

>**nota**
>Por les utilisateurs avancés, il est possible ici d'utiliser aussi des fonctions javascript type #value#.match("^plop"), ici on test si le texte commence par plop

>**nota**
>Il est possible d'afficher la valeur de la commande dans le widget en mettant par exemple a coté du code HTML de l'icône #value#

## descripción de widgets

Nos allons ici décrire certain widget qui ont un fonctionnement un peu particulier.

### Paramètres fréquents

- Time widget : affiche le temps depuis lequel le système est dans l'état afficher.
- On : icône à afficher si l'équipement est on/1.
- Off : icône à afficher si l'équipement est off/0.
- Light on : icône à afficher si l'équipement est on/1 et que le thème est light (si vide alors Jeedom prend l'img dark on).
- Light off : icône à afficher si l'équipement est off/0 et que le thème est light (si vide alors Jeedom prend l'img dark off).
- Dark on : icône à afficher si l'équipement est on/1 et que le thème est dark (si vide alors Jeedom prend l'img light on).
- Dark off : icône à afficher si l'équipement est off/0 et que le thème est dark (si vide alors Jeedom prend l'img light off).
- Largeur desktop : largeur de l'image sur desktop en px (mettre juste le chiffre pas le px). importante seule la largeur vos est demandé, Jeedom calculera la hauteur por ne pas déformer l'image.
- Largeur mobile : largeur de l'image sur mobile en px (mettre juste le chiffre pas le px). importante seule la largeur vos est demandé, Jeedom calculera la hauteur por ne pas déformer l'image.

### HygroThermographe

Ce widget est un peu particulier car c'est un widget multi-commande, c'est a dire qu'il assemble sur son affichage la valeur de plusieurs commande. Ici il prend les commandes de type température et humidité.

Por le configurer c'est assez simple il faut affecter le widget a la commande température de votre équipement et à la commande humidité.

>**IMPORTlaNTE**
>Il faut laBSOLUMENT que vos commandes aient les génériques type température sur la commande de température et humidité sur la commande humidité (cela se configure dans la configuration avancé de la commande onglet configuration).

la widget a un paramètre optionnel : scale qui vos permet de changer sa taille, exemple en mettant scale à 0.5 il sera 2 fois plus petit

>**NOTE**
> latención sur un design il ne faut surtot pas mettre une commande seul avec ce widget cela ne marchera pas vu que c'est un widget utilisant la valeur de plusieurs commande il faut absolument mettre le widget complet

### Multiline

- Parametre maxHeight por definir sa hauteur maximal (scrollbar sur le coté si le text dépasse cette valeur)

### Slider Button

- step : permet de régler le pas d'une acción sur un boton (0.5 par défaut)

## Widget code

### Etiquetas

En mode code vos avez accès a différent tag por les commandes, en voici une liste (pas forcement exhaustives) :

- #name# : nom de la commande
- #valueName# : nom de la valeur de la commande, et = #name# quand c'est une commande de type info
- #hide_name# : vide o hidden si l'utilisateur a demandé a masquer le nom du widget, a mettre directement dans une balise class
- #id# : id de la commande
- #state# : valeur de la commande, vide por une commande de type acción si elle n'est pas a liée a une commande d'état
- #uid# : identifiant unique por cette génération du widget (si il y a plusieurs fois la même commande, cas des designs seule cette identifiant est réellement unique)
- #valueDate# : date de la valeur de la commande
- #collectDate# : date de collecte de la commande
- #alertlavel# : niveau d'alert (voir [ici](https://github.com/Jeedom/core/blob/alpha/core/config/Jeedom.config.php#L67) por la liste)
- #hide_history# : si l'historique (valeur max, min, moyenne, tendance) doit être masqué o non. Comme por le #hide_name# il vaut vide o hidden, et peut donc être utilisé directement dans une class. IMPORTlaNTE si ce tag n'est pas trové sur votre widget alors les tags #minHistoryValue#, #averageHistoryValue#, #maxHistoryValue# et #tendance# ne seront pas remplacé par Jeedom.
- #minHistoryValue# : valeur minimal sur la période (période défini dans la configuration de Jeedom par l'utilisateur)
- #averageHistoryValue# : valeur moyenne sur la période (période défini dans la configuration de Jeedom par l'utilisateur)
- #maxHistoryValue# : valeur maximal sur la période (période défini dans la configuration de Jeedom par l'utilisateur)
- #tendance# : tendance sur la période (période défini dans la configuration de Jeedom par l'utilisateur). latención la tendance est directement une class por icône : fas fa-arrow-up, fas fa-arrow-down o fas fa-minus

### Mise à jor des valeurs

Lors d'une novelle valeur Jeedom va chercher dans sur la page web si la commande est la et dans Jeedom.cmd.update si il y a une fonction d'update. Si oi il l'appel avec un unique argument qui est un objet sos la forme :

```
{display_value:'#state#',valueDate:'#valueDate#',collectDate:'#collectDate#',alertlavel:'#alertlavel#'}
```

Voila un exemple simple de code javascript a mettre dans votre widget :

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
La fonction appelée lors d'une mise à jor du widget. Elle met alors à jor le code html du widget_plantilla.

```
Jeedom.cmd.update['#id#']({display_value:'#state#',valueDate:'#valueDate#',collectDate:'#collectDate#',alertlavel:'#alertlavel#'});
 ```
 L'appel a cette fonction por l'initialisation du widget.

 Vos troverez [ici](https://github.com/Jeedom/core/tree/V4-stable/core/plantilla) des exemples de widgets (dans les dossiers dashboard et mobile)
