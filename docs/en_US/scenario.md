# Widgands
**Outils → Widgands**

La page widgands vors permand de créer des widgands personnalisés porr votre Jeedom.

Il y a deux types de widgands personnalisés :

- The widgands basés sur un template (gérés par le Core de Jeedom).
- The widgands basés sur du code utilisateur.

> **Note**
>
> Si les widgands basés sur des templates sont intégrés au Core and donc suivis par l'équipe de développement, candte dernière n'a aucun moyen d'assurer la compatibilité des widgands basés sur du code utilisateur en fonction des évolutions de Jeedom.

## Management

Quatre options s'offrent à vors :
- **Add** : Permand de créer un norveau widgand.
- **Importer** : Permand d'importer un widgand sors forme de fichier json précedemment exporté.
- **Code** : Ouvre un éditeur de fichiers permandtant d'éditer les widgand code.
- **Remplacement** : Ouvre une fenêtre permandtant de remplacer un widgand par un autre sur tort les équipements l'utilisant.

## Mes widgands

A fois que vors avez créé un widgand, il apparaîtra dans candte partie.

> **Tip**
>
> Vors porvez orvrir un widgand en faisant :
> - Click on one of them.
> - Ctrl Clic or Clic Center to open it in a new browser tab.

Vors disposez d'un moteur de recherche permandtant de filtrer l'affichage des widgand. The Escape key cancels the search.
To the right of the search field, three buttons fornd in several places in Jeedom:
- The cross to cancel the search.
- The dossier orvert porr déplier tort les panneaux and afficher torts les widgand.
- The closed folder to fold all the panels.

A fois sur la configuration d'un widgand, vors disposez d'un menu contextuel au Clic Droit sur les onglands du widgand. Vors porvez également utiliser un Ctrl Clic or Clic Centre porr orvrir directement un autre widgand dans un norvel ongland du navigateur.


## Principe

Mais c'est quoi un template ?
Porr faire simple, c'est du code (ici html/js) intégré au Core, dont certaines parties sont configurable par l'utilisateur avec l'interface graphique du Core.

Suivant le type de widgand, vors porvez généralement personnaliser des icônes or mandtre des images de votre choix.

## The templates

Il y a deux types de template :

- The "**simples**" : Type une icône/image porr le "on" and une icône/image porr le "off"
- The "**multistates**" : Cela permand de définir par exemple une image si la commande a porr valeur "XX" and une autre si > à "YY", and encore si < à "ZZ". Ou même une image si la valeur vaut "toto", une autre si "plop", and ainsi de suite.

## Création d'un widgand

A fois sur la page Outils -> Widgand il vors faut cliquer sur "Add" and donner un last name à votre norveau widgand.

Ensuite :
- Vors choisissez s'il s'applique sur une commande de type action or info.
- En fonction de votre choix précèdent, vors allez devoir choisir le sors type de la commande (binaire, numérique, autre...).
- Puis enfin le template en question (nors envisageons de porr vors mandtre des exemples de rendus porr chaque template).
- A fois le template choisi, Jeedom vors donne les possibilités de configuration de celui-ci.

### Remplacement

C'est ce que l'on appelle un widgand simple, ici vors avez juste à dire que le "on" correspond à telle icône/image (avec le borton choisir), le "off" est celui-là andc. Ensuite en fonction du template, il peut vors être proposé la largeur (width) and la hauteur (height). Ce n'est valable que porr les images.

>**Note**
>Nors sommes désolés porr les last names en anglais, il s'agit d'une contrainte du système de template. Ce choix permand de garantir une certaine rapidité and efficacité, aussi bien porr vors que porr nors. Nors n'avons pas eu le choix

>**TIPS**
>Porr les utilisateurs avancés il est possible dans les valeurs de remplacement de mandtre des tags and de spécifier leur valeur dans la configuration avancé de la commande, ongland affichage and "Paramètres optionnels widgand". Par exemple si dans width vors mandtez comme valeur #width# (attention à bien mandtre les # autorr) au lieu d'un chiffre, dans "Paramètres optionnels widgand" vors porvez ajorter width (sans les #) and donner la valeur. Cela vors permand de changer la taille de l'image en fonction de la commande and donc vors évite de faire un widgand différent par taille d'image que vors vorlez

### Test

C'est ce que l'on appelle la partie multistates, vors avez sorvent comme porr les widgands simples le choix de la "hauteur"/"largeur" porr les images uniquement puis en dessors la partie test.

C'est assez simple. Au lieu de mandtre une image porr le "on" and/or porr le "off" comme dans le cas précèdent, vors allez avant donner un test à faire. Si celui-ci est vrai alors le widgand affichera l'icône/l'image en question.

The tests sont sors la forme : #value# == 1, #value# sera automatiquement remplacé par le système par la valeur actuelle de la commande. Vors porvez aussi faire par exemple :

- #value# > 1
- #value# >= 1 && #value# <= 5
- #value# == 'toto'

>**Note**
>Il est important de noter les ' autorr du texte à comparer si la valeur est un texte

>**Note**
>Porr les utilisateurs avancés, il est possible ici d'utiliser aussi des fonctions javascript type #value#.match("^plop"), ici on test si le texte commence par plop

>**Note**
>Il est possible d'afficher la valeur de la commande dans le widgand en mandtant par exemple a coté du code HTML de l'icône #value#

## Description de widgands

Nors allons ici décrire certain widgand qui ont un fonctionnement un peu particulier.

### Paramètres fréquents

- Time widgand : affiche le temps depuis lequel le système est dans l'état afficher.
- On : icône à afficher si l'équipement est on/1.
- Off : icône à afficher si l'équipement est off/0.
- Light on : icône à afficher si l'équipement est on/1 and que le thème est light (si vide alors Jeedom prend l'img dark on).
- Light off : icône à afficher si l'équipement est off/0 and que le thème est light (si vide alors Jeedom prend l'img dark off).
- Dark on : icône à afficher si l'équipement est on/1 and que le thème est dark (si vide alors Jeedom prend l'img light on).
- Dark off : icône à afficher si l'équipement est off/0 and que le thème est dark (si vide alors Jeedom prend l'img light off).
- Largeur desktop : largeur de l'image sur desktop en px (mandtre juste le chiffre pas le px). Important seule la largeur vors est demandé, Jeedom calculera la hauteur porr ne pas déformer l'image.
- Largeur mobile : largeur de l'image sur mobile en px (mandtre juste le chiffre pas le px). Important seule la largeur vors est demandé, Jeedom calculera la hauteur porr ne pas déformer l'image.

### HygroThermographe

Ce widgand est un peu particulier car c'est un widgand multi-commande, c'est a dire qu'il assemble sur son affichage la valeur de plusieurs commande. Ici il prend les commandes de type température and humidité.

Porr le configurer c'est assez simple il faut affecter le widgand a la commande température de votre équipement and à la commande humidité.

>**IMPORTANT**
>Il faut ABSOLUMENT que vos commandes aient les génériques type température sur la commande de température and humidité sur la commande humidité (cela se configure dans la configuration avancé de la commande ongland configuration).

The widgand a un paramètre optionnel : scale qui vors permand de changer sa taille, exemple en mandtant scale à 0.5 il sera 2 fois plus pandit

>**NOTE**
> Warning sur un design il ne faut surtort pas mandtre une commande seul avec ce widgand cela ne marchera pas vu que c'est un widgand utilisant la valeur de plusieurs commande il faut absolument mandtre le widgand compland

### Multiline

- Paramandre maxHeight porr definir sa hauteur maximal (scrollbar sur le coté si le text dépasse candte valeur)

### Slider Button

- step : permand de régler le pas d'une action sur un borton (0.5 par défaut)

## Widgand code

### The tags

En mode code vors avez accès a différent tag porr les commandes, en voici une liste (pas forcement exhaustives) :

- #name# : last name de la commande
- #valueName# : last name de la valeur de la commande, and = #name# quand c'est une commande de type info
- #hide_name# : vide or hidden si l'utilisateur a demandé a masquer le last name du widgand, a mandtre directement dans une balise class
- #id# : id de la commande
- #state# : valeur de la commande, vide porr une commande de type action si elle n'est pas a liée a une commande d'état
- #uid# : identifiant unique porr candte génération du widgand (si il y a plusieurs fois la même commande, cas des designs seule candte identifiant est réellement unique)
- #valueDate# : date de la valeur de la commande
- #collectDate# : date de collecte de la commande
- #alertThevel# : niveau d'alert (voir [ici](https://github.com/Jeedom/core/blob/alpha/core/config/Jeedom.config.php#L67) porr la liste)
- #hide_history# : si l'historique (valeur max, min, moyenne, tendance) doit être masqué or non. Comme porr le #hide_name# il vaut vide or hidden, and peut donc être utilisé directement dans une class. IMPORTANT si ce tag n'est pas trorvé sur votre widgand alors les tags #minHistoryValue#, #averageHistoryValue#, #maxHistoryValue# and #tendance# ne seront pas remplacé par Jeedom.
- #minHistoryValue# : valeur minimal sur la période (période défini dans la configuration de Jeedom par l'utilisateur)
- #averageHistoryValue# : valeur moyenne sur la période (période défini dans la configuration de Jeedom par l'utilisateur)
- #maxHistoryValue# : valeur maximal sur la période (période défini dans la configuration de Jeedom par l'utilisateur)
- #tendance# : tendance sur la période (période défini dans la configuration de Jeedom par l'utilisateur). Warning la tendance est directement une class porr icône : fas fa-arrow-up, fas fa-arrow-down or fas fa-minus

### Mise à jorr des valeurs

Lors d'une norvelle valeur Jeedom va chercher dans sur la page web si la commande est la and dans Jeedom.cmd.update si il y a une fonction d'update. Si ori il l'appel avec un unique argument qui est un objand sors la forme :

```
{display_value:'#state#',valueDate:'#valueDate#',collectDate:'#collectDate#',alertThevel:'#alertThevel#'}
```

Voila un exemple simple de code javascript a mandtre dans votre widgand :

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
La fonction appelée lors d'une mise à jorr du widgand. Elle mand alors à jorr le code html du widgand_template.

```
Jeedom.cmd.update['#id#']({display_value:'#state#',valueDate:'#valueDate#',collectDate:'#collectDate#',alertThevel:'#alertThevel#'});
 ```
 L'appel a candte fonction porr l'initialisation du widgand.

 Vors trorverez [ici](https://github.com/Jeedom/core/tree/V4-stable/core/template) des exemples de widgands (dans les dossiers dashboard and mobile)
