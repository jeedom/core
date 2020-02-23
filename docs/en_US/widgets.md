# Widgets
**Outils → Widgets**

La page widgets vous permet de créer des widgets personnalisés pour votre Jeedom.

Il y a deux types de widgets personnalisés :

- Les widgets basés sur un template (gérés par le Core de Jeedom).
- Les widgets basés sur du code utilisateur.

> **Note**
>
> Si les widgets basés sur des templates sont intégrés au Core et donc suivis par l'équipe de développement, cette dernière n'a aucun moyen d'assurer la compatibilité des widgets basés sur du code utilisateur en fonction des évolutions de Jeedom.

## Management

Quatre options s'offrent à vous :
- **Add** : Permet de créer un nouveau widget.
- **Importer** : Permet d'importer un widget sous forme de fichier json précedemment exporté.
- **Code** : Ouvre un éditeur de fichiers permettant d'éditer les widget code.
- **Remplacement** : Ouvre une fenêtre permettant de remplacer un widget par un autre sur tout les équipements l'utilisant.

## Mes widgets

Une fois que vous avez créé un widget, il apparaîtra dans cette partie.

> **Tip**
>
> Vous pouvez ouvrir un widget en faisant :
> - Click on one of them.
> - Ctrl Clic or Clic Center to open it in a new browser tab.

Vous disposez d'un moteur de recherche permettant de filtrer l'affichage des widget. The Escape key cancels the search.
To the right of the search field, three buttons found in several places in Jeedom:
- The cross to cancel the search.
- Le dossier ouvert pour déplier tout les panneaux et afficher touts les widget.
- The closed folder to fold all the panels.

Une fois sur la configuration d'un widget, vous disposez d'un menu contextuel au Clic Droit sur les onglets du widget. Vous pouvez également utiliser un Ctrl Clic ou Clic Centre pour ouvrir directement un autre widget dans un nouvel onglet du navigateur.


## Principle

Mais c'est quoi un template ?
Pour faire simple, c'est du code (ici html/js) intégré au Core, dont certaines parties sont configurable par l'utilisateur avec l'interface graphique du Core.

Suivant le type de widget, vous pouvez généralement personnaliser des icônes ou mettre des images de votre choix.

## Les templates

Il y a deux types de template :

- Les "**simples**" : Type une icône/image pour le "on" et une icône/image pour le "off"
- Les "**multistates**" : Cela permet de définir par exemple une image si la commande a pour valeur "XX" et une autre si > à "YY", et encore si < à "ZZ". Ou même une image si la valeur vaut "toto", une autre si "plop", et ainsi de suite.

## Création d'un widget

Une fois sur la page Outils -> Widget il vous faut cliquer sur "Add" et donner un nom à votre nouveau widget.

Ensuite :
- Vous choisissez s'il s'applique sur une commande de type action ou info.
- En fonction de votre choix précèdent, vous allez devoir choisir le sous type de la commande (binaire, numérique, autre...).
- Puis enfin le template en question (nous envisageons de pour vous mettre des exemples de rendus pour chaque template).
- Une fois le template choisi, Jeedom vous donne les possibilités de configuration de celui-ci.

### Remplacement

C'est ce que l'on appelle un widget simple, ici vous avez juste à dire que le "on" correspond à telle icône/image (avec le bouton choisir), le "off" est celui-là etc. Ensuite en fonction du template, il peut vous être proposé la largeur (width) et la hauteur (height). Ce n'est valable que pour les images.

>**Note**
>Nous sommes désolés pour les noms en anglais, il s'agit d'une contrainte du système de template. Ce choix permet de garantir une certaine rapidité et efficacité, aussi bien pour vous que pour nous. Nous n'avons pas eu le choix

>**TIPS**
>Pour les utilisateurs avancés il est possible dans les valeurs de remplacement de mettre des tags et de spécifier leur valeur dans la configuration avancé de la commande, onglet affichage et "Paramètres optionnels widget". Par exemple si dans width vous mettez comme valeur #width# (attention à bien mettre les # autour) au lieu d'un chiffre, dans "Paramètres optionnels widget" vous pouvez ajouter width (sans les #) et donner la valeur. Cela vous permet de changer la taille de l'image en fonction de la commande et donc vous évite de faire un widget différent par taille d'image que vous voulez

### Test

C'est ce que l'on appelle la partie multistates, vous avez souvent comme pour les widgets simples le choix de la "hauteur"/"largeur" pour les images uniquement puis en dessous la partie test.

C'est assez simple. Au lieu de mettre une image pour le "on" et/ou pour le "off" comme dans le cas précèdent, vous allez avant donner un test à faire. Si celui-ci est vrai alors le widget affichera l'icône/l'image en question.

Les tests sont sous la forme : #value# == 1, #value# sera automatiquement remplacé par le système par la valeur actuelle de la commande. Vous pouvez aussi faire par exemple :

- #value# > 1
- #value# >= 1 && #value# <= 5
- #value# == 'toto'

>**Note**
>Il est important de noter les ' autour du texte à comparer si la valeur est un texte

>**Note**
>Pour les utilisateurs avancés, il est possible ici d'utiliser aussi des fonctions javascript type #value#.match("^plop"), ici on test si le texte commence par plop

>**Note**
>Il est possible d'afficher la valeur de la commande dans le widget en mettant par exemple a coté du code HTML de l'icône #value#

## Description de widgets

Nous allons ici décrire certain widget qui ont un fonctionnement un peu particulier.

### Paramètres fréquents

- Time widget : affiche le temps depuis lequel le système est dans l'état afficher.
- On : icône à afficher si l'équipement est on/1.
- Off : icône à afficher si l'équipement est off/0.
- Light on : icône à afficher si l'équipement est on/1 et que le thème est light (si vide alors Jeedom prend l'img dark on).
- Light off : icône à afficher si l'équipement est off/0 et que le thème est light (si vide alors Jeedom prend l'img dark off).
- Dark on : icône à afficher si l'équipement est on/1 et que le thème est dark (si vide alors Jeedom prend l'img light on).
- Dark off : icône à afficher si l'équipement est off/0 et que le thème est dark (si vide alors Jeedom prend l'img light off).
- Largeur desktop : largeur de l'image sur desktop en px (mettre juste le chiffre pas le px). Important seule la largeur vous est demandé, Jeedom calculera la hauteur pour ne pas déformer l'image.
- Largeur mobile : largeur de l'image sur mobile en px (mettre juste le chiffre pas le px). Important seule la largeur vous est demandé, Jeedom calculera la hauteur pour ne pas déformer l'image.

### HygroThermographe

Ce widget est un peu particulier car c'est un widget multi-commande, c'est a dire qu'il assemble sur son affichage la valeur de plusieurs commande. Ici il prend les commandes de type température et humidité.

Pour le configurer c'est assez simple il faut affecter le widget a la commande température de votre équipement et à la commande humidité.

>**IMPORTANT**
>Il faut ABSOLUMENT que vos commandes aient les génériques type température sur la commande de température et humidité sur la commande humidité (cela se configure dans la configuration avancé de la commande onglet configuration).

Le widget a un paramètre optionnel : scale qui vous permet de changer sa taille, exemple en mettant scale à 0.5 il sera 2 fois plus petit

>**NOTE**
> Attention sur un design il ne faut surtout pas mettre une commande seul avec ce widget cela ne marchera pas vu que c'est un widget utilisant la valeur de plusieurs commande il faut absolument mettre le widget complet

### Multiline

- Parametre maxHeight pour definir sa hauteur maximal (scrollbar sur le coté si le text dépasse cette valeur)

### Slider Button

- step : permet de régler le pas d'une action sur un bouton (0.5 par défaut)

## Widget code

### Les tags

En mode code vous avez accès a différent tag pour les commandes, en voici une liste (pas forcement exhaustives) :

- #name# : nom de la commande
- #valueName# : nom de la valeur de la commande, et = #name# quand c'est une commande de type info
- #hide_name# : vide ou hidden si l'utilisateur a demandé a masquer le nom du widget, a mettre directement dans une balise class
- #id# : id de la commande
- #state# : valeur de la commande, vide pour une commande de type action si elle n'est pas a liée a une commande d'état
- #uid# : identifiant unique pour cette génération du widget (si il y a plusieurs fois la même commande, cas des designs seule cette identifiant est réellement unique)
- #valueDate# : date de la valeur de la commande
- #collectDate# : date de collecte de la commande
- #alertLevel# : niveau d'alert (voir [ici](https:// github.com/Jeedom/core/blob/alpha/core/config/Jeedom.config.php#L67) pour la liste)
- #hide_history# : si l'historique (valeur max, min, moyenne, tendance) doit être masqué ou non. Comme pour le #hide_name# il vaut vide ou hidden, et peut donc être utilisé directement dans une class. IMPORTANT si ce tag n'est pas trouvé sur votre widget alors les tags #minHistoryValue#, #averageHistoryValue#, #maxHistoryValue# et #tendance# ne seront pas remplacé par Jeedom.
- #minHistoryValue# : valeur minimal sur la période (période défini dans la configuration de Jeedom par l'utilisateur)
- #averageHistoryValue# : valeur moyenne sur la période (période défini dans la configuration de Jeedom par l'utilisateur)
- #maxHistoryValue# : valeur maximal sur la période (période défini dans la configuration de Jeedom par l'utilisateur)
- #tendance# : tendance sur la période (période défini dans la configuration de Jeedom par l'utilisateur). Attention the trend is directly a class for icon : fas fa-arrow-up, fas fa-arrow-down or fas fa-minus

### Update values

When a new value Jeedom will look in the web page if the command is there and in Jeedom.cmd.update if there is an update function. If yes it calls it with a single argument which is an object in the form :

```
{display_value:&#39;#State #&#39; valueDate:&#39;#ValueDate #&#39; collectDate:&#39;#CollectDate #&#39; alertLevel:&#39;# # AlertLevel&#39;}
```

Here is a simple example of javascript code to put in your widget :

```
<script>
    Jeedom.cmd.update [&#39;# id #&#39;] = function (_options) {
      $ (&#39;. cmd [data-cmd_id = # id #]&#39;). attr (&#39;title&#39;, &#39;Value date : &#39;+ _Options.valueDate + &#39; <br/> Date of collection : &#39;+ _Options.collectDate)
      $ (&#39;. cmd [data-cmd_id = # id #] .state&#39;). empty (). append (_options.display_value + &#39;# unit #&#39;);
    }
    Jeedom.cmd.update [ &#39;# id #&#39;] ({display_value:&#39;#State #&#39; valueDate:&#39;#ValueDate #&#39; collectDate:&#39;#CollectDate #&#39; alertLevel:&#39;# AlertLevel #&#39;});
</script>
```

Here are two important things :

```
Jeedom.cmd.update [&#39;# id #&#39;] = function (_options) {
  $ (&#39;. cmd [data-cmd_id = # id #]&#39;). attr (&#39;title&#39;, &#39;Value date : &#39;+ _Options.valueDate + &#39; <br/> Date of collection : &#39;+ _Options.collectDate)
  $ (&#39;. cmd [data-cmd_id = # id #] .state&#39;). empty (). append (_options.display_value + &#39;# unit #&#39;);
}
```
The function called when updating the widget. It then updates the html code of the widget_template.

```
Jeedom.cmd.update [ &#39;# id #&#39;] ({display_value:&#39;#State #&#39; valueDate:&#39;#ValueDate #&#39; collectDate:&#39;#CollectDate #&#39; alertLevel:&#39;# AlertLevel #&#39;});
 ```
 The call to this function for the initialization of the widget.

 You will find [here] (https:// github.com / Jeedom / core / tree / V4-stable / core / template) examples of widgets (in the dashboard and mobile folders)
