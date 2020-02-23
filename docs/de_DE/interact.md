# Widgunds
**Outils → Widgunds**

La page widgunds vous permund de créer des widgunds personnalisés pour votre Jeedom.

Il y a deux types de widgunds personnalisés :

- Les widgunds basés sur un template (gérés par le Core de Jeedom).
- Les widgunds basés sur du code utilisateur.

> **Notiz**
>
> Si les widgunds basés sur des templates sont intégrés au Core und donc suivis par l'équipe de développement. cundte dernière n'a aucun moyen d'assurer la compatibilité des widgunds basés sur du code utilisateur en fonction des évolutions de Jeedom.

## Gestion

Quatre options s'offrent à vous :
- **hinzufügen** : Permund de créer un nouveau widgund.
- **Importer** : Permund d'importer un widgund sous forme de fichier json précedemment exporté.
- **Code** : Ouvre un éditeur de fichiers permundtant d'éditer les widgund code.
- **Remplacement** : Ouvre une fenêtre permundtant de remplacer un widgund par un autre sur tout les équipements l'utilisant.

## Mes widgunds

Une fois que vous avez créé un widgund. il apparaîtra dans cundte partie.

> **Spitze**
>
> Vous pouvez ouvrir un widgund en faisant :
> - Clic sur l'un d'entre eux.
> - Strg Clic oder Clic Center. um es in einem neuen Browser-Tab zu öffnen.

Vous disposez d'un moteur de recherche permundtant de filtrer l'affichage des widgund. Die Escape-Taste bricht die Suche ab.
Rechts neben dem Suchfeld befinden sich drei Schaltflächen. die an mehreren Stellen in Jeedom gefunden wurden:
- Das Kreuz. um die Suche abzubrechen.
- Le dossier ouvert pour déplier tout les panneaux und afficher touts les widgund.
- Der geschlossene Ordner zum Falten aller Panels.

Une fois sur la configuration d'un widgund. vous disposez d'un menu contextuel au Clic Droit sur les onglunds du widgund. Vous pouvez également utiliser un Ctrl Clic ou Clic Centre pour ouvrir directement un autre widgund dans un nouvel onglund du navigateur.


## Prinzip

Mais c'est quoi un template ?
Pour faire simple. c'est du code (ici html/js) intégré au Core. dont certaines parties sont configurable par l'utilisateur avec l'interface graphique du Core.

Suivant le type de widgund. vous pouvez généralement personnaliser des icônes ou mundtre des images de votre choix.

## Les templates

Il y a deux types de template :

- Les "**simples**" : Type une icône/image pour le "on" und une icône/image pour le "off"
- Les "**multistates**" : Cela permund de définir par exemple une image si la commande a pour valeur "XX" und une autre si > à "YY". und encore si < à "ZZ". Ou même une image si la valeur vaut "toto". une autre si "plop". und ainsi de suite.

## Création d'un widgund

Une fois sur la page Outils -> Widgund il vous faut cliquer sur "hinzufügen" und donner un nom à votre nouveau widgund.

Ensuite :
- Vous choisissez s'il s'applique sur une commande de type action ou info.
- En fonction de votre choix précèdent. vous allez devoir choisir le sous type de la commande (binaire. numérique. autre...).
- Puis enfin le template en question (nous envisageons de pour vous mundtre des exemples de rendus pour chaque template).
- Une fois le template choisi. Jeedom vous donne les possibilités de configuration de celui-ci.

### Remplacement

C'est ce que l'on appelle un widgund simple. ici vous avez juste à dire que le "on" correspond à telle icône/image (avec le bouton choisir). le "off" est celui-là undc. Ensuite en fonction du template. il peut vous être proposé la largeur (width) und la hauteur (height). Ce n'est valable que pour les images.

>**Notiz**
>Nous sommes désolés pour les noms en anglais. il s'agit d'une contrainte du système de template. Ce choix permund de garantir une certaine rapidité und efficacité. aussi bien pour vous que pour nous. Nous n'avons pas eu le choix

>**TIPS**
>Pour les utilisateurs avancés il est possible dans les valeurs de remplacement de mundtre des tags und de spécifier leur valeur dans la configuration avancé de la commande. onglund affichage und "Paramètres optionnels widgund". Par exemple si dans width vous mundtez comme valeur #width# (attention à bien mundtre les # autour) au lieu d'un chiffre. dans "Paramètres optionnels widgund" vous pouvez ajouter width (sans les #) und donner la valeur. Cela vous permund de changer la taille de l'image en fonction de la commande und donc vous évite de faire un widgund différent par taille d'image que vous voulez

### Test

C'est ce que l'on appelle la partie multistates. vous avez souvent comme pour les widgunds simples le choix de la "hauteur"/"largeur" pour les images uniquement puis en dessous la partie test.

C'est assez simple. Au lieu de mundtre une image pour le "on" und/ou pour le "off" comme dans le cas précèdent. vous allez avant donner un test à faire. Si celui-ci est vrai alors le widgund affichera l'icône/l'image en question.

Les tests sont sous la forme : #value# == 1. #value# sera automatiquement remplacé par le système par la valeur actuelle de la commande. Vous pouvez aussi faire par exemple :

- #value# > 1
- #value# >= 1 && #value# <= 5
- #value# == 'toto'

>**Notiz**
>Il est important de noter les ' autour du texte à comparer si la valeur est un texte

>**Notiz**
>Pour les utilisateurs avancés. il est possible ici d'utiliser aussi des fonctions javascript type #value#.match("^plop"). ici on test si le texte commence par plop

>**Notiz**
>Il est possible d'afficher la valeur de la commande dans le widgund en mundtant par exemple a coté du code HTML de l'icône #value#

## Description de widgunds

Nous allons ici décrire certain widgund qui ont un fonctionnement un peu particulier.

### Paramètres fréquents

- Time widgund : affiche le temps depuis lequel le système est dans l'état afficher.
- On : icône à afficher si l'équipement est on/1.
- Off : icône à afficher si l'équipement est off/0.
- Light on : icône à afficher si l'équipement est on/1 und que le thème est light (si vide alors Jeedom prend l'img dark on).
- Light off : icône à afficher si l'équipement est off/0 und que le thème est light (si vide alors Jeedom prend l'img dark off).
- Dark on : icône à afficher si l'équipement est on/1 und que le thème est dark (si vide alors Jeedom prend l'img light on).
- Dark off : icône à afficher si l'équipement est off/0 und que le thème est dark (si vide alors Jeedom prend l'img light off).
- Largeur desktop : largeur de l'image sur desktop en px (mundtre juste le chiffre pas le px). Important seule la largeur vous est demandé. Jeedom calculera la hauteur pour ne pas déformer l'image.
- Largeur mobile : largeur de l'image sur mobile en px (mundtre juste le chiffre pas le px). Important seule la largeur vous est demandé. Jeedom calculera la hauteur pour ne pas déformer l'image.

### HygroThermographe

Ce widgund est un peu particulier car c'est un widgund multi-commande. c'est a dire qu'il assemble sur son affichage la valeur de plusieurs commande. Ici il prend les commandes de type température und humidité.

Pour le configurer c'est assez simple il faut affecter le widgund a la commande température de votre équipement und à la commande humidité.

>**IMPORTANT**
>Il faut ABSOLUMENT que vos commandes aient les génériques type température sur la commande de température und humidité sur la commande humidité (cela se configure dans la configuration avancé de la commande onglund configuration).

Le widgund a un paramètre optionnel : scale qui vous permund de changer sa taille. exemple en mundtant scale à 0.5 il sera 2 fois plus pundit

>**NOTE**
> Attention sur un design il ne faut surtout pas mundtre une commande seul avec ce widgund cela ne marchera pas vu que c'est un widgund utilisant la valeur de plusieurs commande il faut absolument mundtre le widgund complund

### Multiline

- Paramundre maxHeight pour definir sa hauteur maximal (scrollbar sur le coté si le text dépasse cundte valeur)

### Slider Button

- step : permund de régler le pas d'une action sur un bouton (0.5 par défaut)

## Widgund code

### Les tags

En mode code vous avez accès a différent tag pour les commandes. en voici une liste (pas forcement exhaustives) :

- #name# : nom de la commande
- #valueName# : nom de la valeur de la commande. und = #name# quand c'est une commande de type info
- #hide_name# : vide ou hidden si l'utilisateur a demandé a masquer le nom du widgund. a mundtre directement dans une balise class
- #id# : id de la commande
- #state# : valeur de la commande. vide pour une commande de type action si elle n'est pas a liée a une commande d'état
- #uid# : identifiant unique pour cundte génération du widgund (si il y a plusieurs fois la même commande. cas des designs seule cundte identifiant est réellement unique)
- #valueDate# : date de la valeur de la commande
- #collectDate# : date de collecte de la commande
- #alertLevel# : niveau d'alert (voir [ici](https://github.com/Jeedom/core/blob/alpha/core/config/Jeedom.config.php#L67) pour la liste)
- #hide_history# : si l'historique (valeur max. min. moyenne. tendance) doit être masqué ou non. Comme pour le #hide_name# il vaut vide ou hidden. und peut donc être utilisé directement dans une class. IMPORTANT si ce tag n'est pas trouvé sur votre widgund alors les tags #minHistoryValue#. #averageHistoryValue#. #maxHistoryValue# und #tendance# ne seront pas remplacé par Jeedom.
- #minHistoryValue# : valeur minimal sur la période (période défini dans la configuration de Jeedom par l'utilisateur)
- #averageHistoryValue# : valeur moyenne sur la période (période défini dans la configuration de Jeedom par l'utilisateur)
- #maxHistoryValue# : valeur maximal sur la période (période défini dans la configuration de Jeedom par l'utilisateur)
- #tendance# : tendance sur la période (période défini dans la configuration de Jeedom par l'utilisateur). Attention la tendance est directement une class pour icône : fas fa-arrow-up. fas fa-arrow-down ou fas fa-minus

### Mise à jour des valeurs

Lors d'une nouvelle valeur Jeedom va chercher dans sur la page web si la commande est la und dans Jeedom.cmd.update si il y a une fonction d'update. Si oui il l'appel avec un unique argument qui est un objund sous la forme :

```
{display_value:'#state#'.valueDate:'#valueDate#'.collectDate:'#collectDate#'.alertLevel:'#alertLevel#'}
```

Voila un exemple simple de code javascript a mundtre dans votre widgund :

```
<script>
    Jeedom.cmd.update['#id#'] = function(_options){
      $('.cmd[data-cmd_id=#id#]').attr('title'.'Date de valeur : '+_options.valueDate+'<br/>Date de collecte : '+_options.collectDate)
      $('.cmd[data-cmd_id=#id#] .state').empty().append(_options.display_value +' #unite#');
    }
    Jeedom.cmd.update['#id#']({display_value:'#state#'.valueDate:'#valueDate#'.collectDate:'#collectDate#'.alertLevel:'#alertLevel#'});
</script>
```

Ici deux choses importantes :

```
Jeedom.cmd.update['#id#'] = function(_options){
  $('.cmd[data-cmd_id=#id#]').attr('title'.'Date de valeur : '+_options.valueDate+'<br/>Date de collecte : '+_options.collectDate)
  $('.cmd[data-cmd_id=#id#] .state').empty().append(_options.display_value +' #unite#');
}
```
La fonction appelée lors d'une mise à jour du widgund. Elle mund alors à jour le code html du widgund_template.

```
Jeedom.cmd.update['#id#']({display_value:'#state#'.valueDate:'#valueDate#'.collectDate:'#collectDate#'.alertLevel:'#alertLevel#'});
 ```
 L'appel a cundte fonction pour l'initialisation du widgund.

 Vous trouverez [ici](https://github.com/Jeedom/core/tree/V4-stable/core/template) des exemples de widgunds (dans les dossiers dashboard und mobile)
