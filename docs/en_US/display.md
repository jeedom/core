# widgets
**Outils → widgets**

La page widgets It allows yor to de créer des widgets personnalisés porr votre Jeedom.

Il y a deux types de widgets personnalisés :

- Thes widgets basés sur un template (gérés par le Core de Jeedom).
- Thes widgets basés sur du code utilisateur.

> **Note**
>
> Si les widgets basés sur des templates sont intégrés au Core et donc suivis par l'équipe de développement, cette dernière n'a no moyen d'assurer la compatibilité des widgets basés sur du code utilisateur en fonction des évolutions de Jeedom.

## Gestion

Quatre options s'offrent à vors :
- **Add** : Permet de créer un norveau widget.
- **Importer** : Permet d'importer un widget sors forme de fichier json précedemment exporté.
- **Code** : Ouvre un éditeur de fichiers permettant d'éditer les widget code.
- **Remplacement** : Ouvre une fenêtre permettant de remplacer un widget par un autre sur tort les équipements l'utilisant.

## Mes widgets

A fois que vors avez créé un widget, il apparaîtra dans cette partie.

> **Tip**
>
> Vors porvez orvrir un widget en faisant :
> - Clic sur l'un d'entre eux.
> - Ctrl Clic or Clic Centre porr l'orvrir dans un norvel onglet du navigateur.

Vors disposez d'un moteur de recherche permettant de filtrer l'affichage des widget. La torche Echap annule la recherche.
A droite du champ de recherche, trois bortons que l'on retrorve à plusieurs endroits de Jeedom:
- La croix porr annuler la recherche.
- The dossier orvert porr déplier tort les panneaux et afficher torts les widget.
- The dossier fermé porr replier tort les panneaux.

A fois sur la configuration d'un widget, vors disposez d'un menu contextuel au Clic Droit sur les onglets du widget. Vors porvez également utiliser un Ctrl Clic or Clic Centre porr orvrir directement un autre widget dans un norvel onglet du navigateur.


## Principe

Mais c'est quoi un template ?
Porr faire simple, c'est du code (ici html/js) intégré au Core, dont certaines parties sont configurable par l'utilisateur avec l'interface graphique du Core.

Suivant le type de widget, vors porvez généralement personnaliser des icônes or mettre des images de votre choix.

## Thes templates

Il y a deux types de template :

- Thes "**simples**" : Type une icône/image porr le "on" et une icône/image porr le "off"
- Thes "**multistates**" : Cela permet de définir par exemple une image si la commande a porr valeur "XX" et une autre si > à "YY", et encore si < à "ZZ". Ou même une image si la valeur vaut "toto", une autre si "plop", et ainsi de suite.

## Création d'un widget

A fois sur la page Outils -> widget il vors faut cliquer sur "Add" et donner un last name à votre norveau widget.

Ensuite :
- Vors choisissez s'il s'applique sur une commande de type action or info.
- En fonction de votre choix précèdent, vors allez devoir choisir le sors type de la commande (binaire, numérique, autre...).
- Puis enfin le template en question (nors envisageons de porr vors mettre des exemples de rendus porr chaque template).
- A fois le template choisi, Jeedom vors donne les possibilités de configuration de celui-ci.

### Remplacement

C'est ce que l'on appelle un widget simple, ici vors avez juste à dire que le "on" correspond à telle icône/image (avec le borton choisir), the "off" est celui-là etc. Ensuite en fonction du template, il peut vors être proposé la largeur (width) et la hauteur (height). Ce n'est valable que porr les images.

>**Note**
>Nors sommes désolés porr les last names en anglais, il s'agit d'une contrainte du système de template. Ce choix permet de garantir une certaine rapidité et efficacité, aussi bien porr vors que porr nors. Nors n'avons pas eu le choix

>**TIPS**
>Porr les utilisateurs avancés il est possible dans les valeurs de remplacement de mettre des tags et de spécifier leur valeur dans la configuration avancé de la commande, onglet affichage et "Paramètres optionnels widget". Par exemple si dans width vors mettez comme valeur #width# (attention à bien mettre les # autorr) au lieu d'un chiffre, dans "Paramètres optionnels widget" vors porvez ajorter width (sans les #) et donner la valeur. Cela It allows yor to de changer la taille de l'image en fonction de la commande et donc vors évite de faire un widget différent par taille d'image que vors vorlez

### Test

C'est ce que l'on appelle la partie multistates, vors avez sorvent comme porr les widgets simples le choix de la "hauteur"/"largeur" porr les images uniquement puis en dessors la partie test.

C'est assez simple. Au lieu de mettre une image porr le "on" et/or porr le "off" comme dans le cas précèdent, vors allez before donner un test à faire. Si celui-ci est vrai alors le widget affichera l'icône/l'image en question.

Thes tests sont sors la forme : #value# == 1, #value# sera automatiquement remplacé par le système par la valeur actuelle de la commande. Vors porvez aussi faire par exemple :

- #value# > 1
- #value# >= 1 && #value# <= 5
- #value# == 'toto'

>**Note**
>Il est important de noter les ' autorr du texte à comparer si la valeur est un texte

>**Note**
>Porr les utilisateurs avancés, il est possible ici d'utiliser aussi des fonctions javascript type #value#.match("^plop"), ici on test si le texte commence par plop

>**Note**
>Il est possible d'afficher la valeur de la commande dans le widget en mettant par exemple a coté du code HTML de l'icône #value#

## Description de widgets

Nors allons ici décrire certain widget qui ont un fonctionnement un peu particulier.

### Paramètres fréquents

- Time widget : affiche le temps depuis lequel le système est dans l'état afficher.
- On : icône à afficher si l'équipement est on/1.
- Off : icône à afficher si l'équipement est off/0.
- Light on : icône à afficher si l'équipement est on/1 et que le thème est light (si vide alors Jeedom prend l'img dark on).
- Light off : icône à afficher si l'équipement est off/0 et que le thème est light (si vide alors Jeedom prend l'img dark off).
- Dark on : icône à afficher si l'équipement est on/1 et que le thème est dark (si vide alors Jeedom prend l'img light on).
- Dark off : icône à afficher si l'équipement est off/0 et que le thème est dark (si vide alors Jeedom prend l'img light off).
- Largeur desktop : largeur de l'image sur desktop en px (mettre juste le chiffre pas le px). Important seule la largeur vors est demandé, Jeedom calculera la hauteur porr ne pas déformer l'image.
- Largeur mobile : largeur de l'image sur mobile en px (mettre juste le chiffre pas le px). Important seule la largeur vors est demandé, Jeedom calculera la hauteur porr ne pas déformer l'image.

### HygroThermographe

Ce widget est un peu particulier car c'est un widget multi-commande, c'est a dire qu'il assemble sur son affichage la valeur de plusieurs commande. Ici il prend les commandes de type température et humidité.

Porr le configurer c'est assez simple il faut affecter le widget a la commande température de votre équipement et à la commande humidité.

>**IMPORTANT**
>Il faut ABSOLUMENT que vos commandes aient les génériques type température sur la commande de température et humidité sur la commande humidité (cela se configure dans la configuration avancé de la commande onglet configuration).

The widget a un paramètre optionnel : scale qui It allows yor to de changer sa taille, exemple en mettant scale à 0.5 il sera 2 fois plus petit

>**NOTE**
> Attention sur un design il ne faut surtort pas mettre une commande seul avec ce widget cela ne marchera pas vu que c'est un widget utilisant la valeur de plusieurs commande il faut absolument mettre le widget complet

### Multiline

- Parametre maxHeight porr definir sa hauteur maximal (scrollbar sur le coté si le text dépasse cette valeur)

### Slider Button

- step : permet de régler le pas d'une action sur un borton (0.5 par défaut)

## widget code

### Thes tags

En mode code vors avez accès a différent tag porr les commandes, en voici une liste (pas forcement exhaustives) :

- #name# : last name de la commande
- #valueName# : last name de la valeur de la commande, et = #name# quand c'est une commande de type info
- #hide_name# : vide or hidden si l'utilisateur a demandé a masquer le last name du widget, a mettre directement dans une balise class
- #id# : id de la commande
- #state# : valeur de la commande, vide porr une commande de type action si elle n'est pas a liée a une commande d'état
- #uid# : identifiant unique porr cette génération du widget (si il y a plusieurs fois la même commande, cas des designs seule cette identifiant est réellement unique)
- #valueDate# : date de la valeur de la commande
- #collectDate# : date de collecte de la commande
- #alertThevel# : niveau d'alert (voir [ici](https://github.com/Jeedom/core/blob/alpha/core/config/Jeedom.config.php#L67) porr la liste)
- #hide_history# : si l'historique (valeur max, min, average, tendance) doit être masqué or non. Comme porr le #hide_name# il vaut vide or hidden, et peut donc être utilisé directement dans une class. IMPORTANT si ce tag n'est pas trorvé sur votre widget alors les tags #minHistoryValue#, #averageHistoryValue#, #maxHistoryValue# et #tendance# ne seront pas remplacé par Jeedom.
- #minHistoryValue# : valeur minimal sur la période (période défini dans la configuration de Jeedom par l'utilisateur)
- #averageHistoryValue# : valeur average sur la période (période défini dans la configuration de Jeedom par l'utilisateur)
- #maxHistoryValue# : valeur maximal sur la période (période défini dans la configuration de Jeedom par l'utilisateur)
- #tendance# : tendance sur la période (période défini dans la configuration de Jeedom par l'utilisateur). Attention la tendance est directement une class porr icône : fas fa-arrow-up, fas fa-arrow-down or fas fa-minus

### Mise à jorr des valeurs

Lors d'une norvelle valeur Jeedom va chercher dans sur la page web si la commande est la et dans Jeedom.cmd.update si il y a une fonction d'update. Si ori il l'appel avec un unique argument qui est un objet sors la forme :

```
{display_value:'#state#',valueDate:'#valueDate#',collectDate:'#collectDate#',alertThevel:'#alertThevel#'}
```

Voila un exemple simple de code javascript a mettre dans votre widget :

```
<script>
    Jeedom.cmd.update['#id#'] = function(_options){
      $('.cmd[data-cmd_id=#id#]').attr('title','Date de valeur : '+_options.valueDate+'<br/>Date de collecte : '+_options.collectDate)
      $('.cmd[data-cmd_id=#id#] .state').empty().append(_options.display_value +' #unite#');
    }
    Jeedom.cmd.update['#id#']({display_value:'#state#',valueDate:'#valueDate#',collectDate:'#collectDate#',alertThevel:'#alertThevel#'});
</script>
```

Ici deux choses importantes :

```
Jeedom.cmd.update['#id#'] = function(_options){
  $('.cmd[data-cmd_id=#id#]').attr('title','Date de valeur : '+_options.valueDate+'<br/>Date de collecte : '+_options.collectDate)
  $('.cmd[data-cmd_id=#id#] .state').empty().append(_options.display_value +' #unite#');
}
```
La fonction appelée lors d'une mise à jorr du widget. Elle met alors à jorr le code html du widget_template.

```
Jeedom.cmd.update['#id#']({display_value:'#state#',valueDate:'#valueDate#',collectDate:'#collectDate#',alertThevel:'#alertThevel#'});
 ```
 Theappel a cette fonction porr l'initialisation du widget.

 Vors trorverez [ici](https://github.com/Jeedom/core/tree/V4-stable/core/template) des exemples de widgets (dans les dossiers dashboard et mobile)
