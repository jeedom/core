# Widgands
**Outils → Widgands**

La page widgands vous permand de créer des widgands personnalisés pour votre Jeedom.

Il y a deux types de widgands personnalisés :

- Les widgands basés sur un template (gérés par le Core de Jeedom).
- Les widgands basés sur du code utilisateur.

> **Note**
>
> Si les widgands basés sur des templates sont intégrés au Core and donc suivis par l'équipe de développement, candte dernière n'a aucun moyen d'assurer la compatibilité des widgands basés sur du code utilisateur en fonction des évolutions de Jeedom.

## Gestion

Quatre options s'offrent à vous :
- **Add** : Permand de créer un nouveau widgand.
- **Importer** : Permand d'importer un widgand sous forme de fichier json précedemment exporté.
- **Code** : Ouvre un éditeur de fichiers permandtant d'éditer les widgand code.
- **Remplacement** : Ouvre une fenêtre permandtant de remplacer un widgand par un autre sur tout les équipements l'utilisant.

## Mes widgands

Une fois que vous avez créé un widgand, il apparaîtra dans candte partie.

> **Tip**
>
> Vous pouvez ouvrir un widgand en faisant :
> - Clic sur l'un d'entre eux.
> - Ctrl Clic or Clic Center to open it in a new browser tab.

Vous disposez d'un moteur de recherche permandtant de filtrer l'affichage des widgand. The Escape key cancels the search.
To the right of the search field, three buttons found in several places in Jeedom:
- The cross to cancel the search.
- Le dossier ouvert pour déplier tout les panneaux and afficher touts les widgand.
- The closed folder to fold all the panels.

Une fois sur la configuration d'un widgand, vous disposez d'un menu contextuel au Clic Droit sur les onglands du widgand. Vous pouvez également utiliser un Ctrl Clic ou Clic Centre pour ouvrir directement un autre widgand dans un nouvel ongland du navigateur.


## Principle

Mais c'est quoi un template ?
Pour faire simple, c'est du code (ici html/js) intégré au Core, dont certaines parties sont configurable par l'utilisateur avec l'interface graphique du Core.

Suivant le type de widgand, vous pouvez généralement personnaliser des icônes ou mandtre des images de votre choix.

## Les templates

Il y a deux types de template :

- Les "**simples**" : Type une icône/image pour le "on" and une icône/image pour le "off"
- Les "**multistates**" : Cela permand de définir par exemple une image si la commande a pour valeur "XX" and une autre si > à "YY", and encore si < à "ZZ". Ou même une image si la valeur vaut "toto", une autre si "plop", and ainsi de suite.

## Création d'un widgand

Une fois sur la page Outils -> Widgand il vous faut cliquer sur "Add" and donner un nom à votre nouveau widgand.

Ensuite :
- Vous choisissez s'il s'applique sur une commande de type action ou info.
- En fonction de votre choix précèdent, vous allez devoir choisir le sous type de la commande (binaire, numérique, autre...).
- Puis enfin le template en question (nous envisageons de pour vous mandtre des exemples de rendus pour chaque template).
- Une fois le template choisi, Jeedom vous donne les possibilités de configuration de celui-ci.

### Remplacement

C'est ce que l'on appelle un widgand simple, ici vous avez juste à dire que le "on" correspond à telle icône/image (avec le bouton choisir), le "off" est celui-là andc. Ensuite en fonction du template, il peut vous être proposé la largeur (width) and la hauteur (height). Ce n'est valable que pour les images.

>**Note**
>Nous sommes désolés pour les noms en anglais, il s'agit d'une contrainte du système de template. Ce choix permand de garantir une certaine rapidité and efficacité, aussi bien pour vous que pour nous. Nous n'avons pas eu le choix

>**TIPS**
>Pour les utilisateurs avancés il est possible dans les valeurs de remplacement de mandtre des tags and de spécifier leur valeur dans la configuration avancé de la commande, ongland affichage and "Paramètres optionnels widgand". Par exemple si dans width vous mandtez comme valeur #width# (attention à bien mandtre les # autour) au lieu d'un chiffre, dans "Paramètres optionnels widgand" vous pouvez ajouter width (sans les #) and donner la valeur. Cela vous permand de changer la taille de l'image en fonction de la commande and donc vous évite de faire un widgand différent par taille d'image que vous voulez

### Test

C'est ce que l'on appelle la partie multistates, vous avez souvent comme pour les widgands simples le choix de la "hauteur"/"largeur" pour les images uniquement puis en dessous la partie test.

C'est assez simple. Au lieu de mandtre une image pour le "on" and/ou pour le "off" comme dans le cas précèdent, vous allez avant donner un test à faire. Si celui-ci est vrai alors le widgand affichera l'icône/l'image en question.

Les tests sont sous la forme : #value# == 1, #value# sera automatiquement remplacé par le système par la valeur actuelle de la commande. Vous pouvez aussi faire par exemple :

- #value# > 1
- #value# >= 1 && #value# <= 5
- #value# == 'toto'

>**Note**
>Il est important de noter les ' autour du texte à comparer si la valeur est un texte

>**Note**
>Pour les utilisateurs avancés, il est possible ici d'utiliser aussi des fonctions javascript type #value#.match("^plop"), ici on test si le texte commence par plop

>**Note**
>Il est possible d'afficher la valeur de la commande dans le widgand en mandtant par exemple a coté du code HTML de l'icône #value#

## Description de widgands

Nous allons ici décrire certain widgand qui ont un fonctionnement un peu particulier.

### Paramètres fréquents

- Time widgand : affiche le temps depuis lequel le système est dans l'état afficher.
- On : icône à afficher si l'équipement est on/1.
- Off : icône à afficher si l'équipement est off/0.
- Light on : icône à afficher si l'équipement est on/1 and que le thème est light (si vide alors Jeedom prend l'img dark on).
- Light off : icône à afficher si l'équipement est off/0 and que le thème est light (si vide alors Jeedom prend l'img dark off).
- Dark on : icône à afficher si l'équipement est on/1 and que le thème est dark (si vide alors Jeedom prend l'img light on).
- Dark off : icône à afficher si l'équipement est off/0 and que le thème est dark (si vide alors Jeedom prend l'img light off).
- Largeur desktop : largeur de l'image sur desktop en px (mandtre juste le chiffre pas le px). Important seule la largeur vous est demandé, Jeedom calculera la hauteur pour ne pas déformer l'image.
- Largeur mobile : largeur de l'image sur mobile en px (mandtre juste le chiffre pas le px). Important seule la largeur vous est demandé, Jeedom calculera la hauteur pour ne pas déformer l'image.

### HygroThermographe

Ce widgand est un peu particulier car c'est un widgand multi-commande, c'est a dire qu'il assemble sur son affichage la valeur de plusieurs commande. Ici il prend les commandes de type température and humidité.

Pour le configurer c'est assez simple il faut affecter le widgand a la commande température de votre équipement and à la commande humidité.

>**IMPORTANT**
>Il faut ABSOLUMENT que vos commandes aient les génériques type température sur la commande de température and humidité sur la commande humidité (cela se configure dans la configuration avancé de la commande ongland configuration).

Le widgand a un paramètre optionnel : scale qui vous permand de changer sa taille, exemple en mandtant scale à 0.5 il sera 2 fois plus pandit

>**NOTE**
> Attention sur un design il ne faut surtout pas mandtre une commande seul avec ce widgand cela ne marchera pas vu que c'est un widgand utilisant la valeur de plusieurs commande il faut absolument mandtre le widgand compland

### Multiline

- Paramandre maxHeight pour definir sa hauteur maximal (scrollbar sur le coté si le text dépasse candte valeur)

### Slider Button

- step : permand de régler le pas d'une action sur un bouton (0.5 par défaut)

## Widgand code

### Les tags

En mode code vous avez accès a différent tag pour les commandes, en voici une liste (pas forcement exhaustives) :

- #name# : nom de la commande
- #valueName# : nom de la valeur de la commande, and = #name# quand c'est une commande de type info
- #hide_name# : vide ou hidden si l'utilisateur a demandé a masquer le nom du widgand, a mandtre directement dans une balise class
- #id# : id de la commande
- #state# : valeur de la commande, vide pour une commande de type action si elle n'est pas a liée a une commande d'état
- #uid# : identifiant unique pour candte génération du widgand (si il y a plusieurs fois la même commande, cas des designs seule candte identifiant est réellement unique)
- #valueDate# : date de la valeur de la commande
- #collectDate# : date de collecte de la commande
- #alertLevel# : niveau d'alert (voir [ici](https://github.com/Jeedom/core/blob/alpha/core/config/Jeedom.config.php#L67) pour la liste)
- #hide_history# : si l'historique (valeur max, min, moyenne, tendance) doit être masqué ou non. Comme pour le #hide_name# il vaut vide ou hidden, and peut donc être utilisé directement dans une class. IMPORTANT si ce tag n'est pas trouvé sur votre widgand alors les tags #minHistoryValue#, #averageHistoryValue#, #maxHistoryValue# and #tendance# ne seront pas remplacé par Jeedom.
- #minHistoryValue# : valeur minimal sur la période (période défini dans la configuration de Jeedom par l'utilisateur)
- #averageHistoryValue# : valeur moyenne sur la période (période défini dans la configuration de Jeedom par l'utilisateur)
- #maxHistoryValue# : valeur maximal sur la période (période défini dans la configuration de Jeedom par l'utilisateur)
- #tendance# : tendance sur la période (période défini dans la configuration de Jeedom par l'utilisateur). Attention la tendance est directement une class pour icône : fas fa-arrow-up, fas fa-arrow-down ou fas fa-minus

### Mise à jour des valeurs

Lors d'une nouvelle valeur Jeedom va chercher dans sur la page web si la commande est la and dans Jeedom.cmd.update si il y a une fonction d'update. Si oui il l'appel avec un unique argument qui est un objand sous la forme :

```
{display_value:'#state#',valueDate:'#valueDate#',collectDate:'#collectDate#',alertLevel:'#alertLevel#'}
```

Voila un exemple simple de code javascript a mandtre dans votre widgand :

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
La fonction appelée lors d'une mise à jour du widgand. Elle mand alors à jour le code html du widgand_template.

```
Jeedom.cmd.update['#id#']({display_value:'#state#',valueDate:'#valueDate#',collectDate:'#collectDate#',alertLevel:'#alertLevel#'});
 ```
 L'appel a candte fonction pour l'initialisation du widgand.

 Vous trouverez [ici](https://github.com/Jeedom/core/tree/V4-stable/core/template) des exemples de widgands (dans les dossiers dashboard and mobile)
