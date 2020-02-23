# WIdentifikationgets
**Outils → WIdentifikationgets**

La page wIdentifikationgets voders permet de créer des wIdentifikationgets personnalisés poderr votre Jeedom.

Il y a deux types de wIdentifikationgets personnalisés :

- dies wIdentifikationgets basés sur un template (gérés par le Core de Jeedom).
- dies wIdentifikationgets basés sur du code utilisateur.

> **Notiz**
>
> Si les wIdentifikationgets basés sur des templates sont intégrés au Core et donc suivis par l'équipe de développement. cette dernière n'a aucun moyen d'assurer la compatibilité des wIdentifikationgets basés sur du code utilisateur en fonction des évolutions de Jeedom.

## Gestion

Quatre options s'offrent à voders :
- **hinzufügen** : Permet de créer un noderveau wIdentifikationget.
- **Importer** : Permet d'importer un wIdentifikationget soders forme de fichier json précedemment exporté.
- **Code** : Ouvre un éditeur de fichiers permettant d'éditer les wIdentifikationget code.
- **Remplacement** : Ouvre une fenêtre permettant de remplacer un wIdentifikationget par un autre sur todert les équipements l'utilisant.

## Mes wIdentifikationgets

ein fois que voders avez créé un wIdentifikationget. il apparaîtra dans cette partie.

> **Spitze**
>
> Voders podervez odervrir un wIdentifikationget en faisant :
> - Clic sur l'un d'entre eux.
> - Ctrl Clic oder Clic Centre poderr l'odervrir dans un nodervel onglet du navigateur.

Voders disposez d'un moteur de recherche permettant de filtrer l'affichage des wIdentifikationget. La toderche Echap annule la recherche.
A droite du champ de recherche. trois bodertons que l'on retroderve à plusieurs endroits de Jeedom:
- La croix poderr annuler la recherche.
- die dossier odervert poderr déplier todert les panneaux et afficher toderts les wIdentifikationget.
- die dossier fermé poderr replier todert les panneaux.

ein fois sur la configuration d'un wIdentifikationget. voders disposez d'un menu contextuel au Clic Droit sur les onglets du wIdentifikationget. Voders podervez également utiliser un Ctrl Clic oder Clic Centre poderr odervrir directement un autre wIdentifikationget dans un nodervel onglet du navigateur.


## Principe

Mais c'est quoi un template ?
Poderr faire simple. c'est du code (ici html/js) intégré au Core. dont certaines parties sont configurable par l'utilisateur avec l'interface graphique du Core.

Suivant le type de wIdentifikationget. voders podervez généralement personnaliser des icônes oder mettre des images de votre choix.

## dies templates

Il y a deux types de template :

- dies "**simples**" : Typ une icône/image poderr le "on" et une icône/image poderr le "off"
- dies "**multistates**" : Cela permet de définir par exemple une image si la commande a poderr valeur "XX" et une autre si > à "YY". et encore si < à "ZZ". Ou même une image si la valeur vaut "toto". une autre si "plop". et ainsi de suite.

## Création d'un wIdentifikationget

ein fois sur la page Outils -> WIdentifikationget il voders faut cliquer sur "hinzufügen" et donner un Name à votre noderveau wIdentifikationget.

Ensuite :
- Voders choisissez s'il s'applique sur une commande de type Aktion oder info.
- En fonction de votre choix précèdent. voders allez devoir choisir le soders type de la commande (binaire. numérique. autre...).
- Puis enfin le template en question (noders envisageons de poderr voders mettre des exemples de rendus poderr chaque template).
- ein fois le template choisi. Jeedom voders donne les possibilités de configuration de celui-ci.

### Remplacement

C'est ce que l'on appelle un wIdentifikationget simple. ici voders avez juste à dire que le "on" correspond à telle icône/image (avec le boderton choisir). le "off" est celui-là etc. Ensuite en fonction du template. il peut voders être proposé la largeur (wIdentifikationth) et la hauteur (height). Ce n'est valable que poderr les images.

>**Notiz**
>Noders sommes désolés poderr les Names en anglais. il s'agit d'une contrainte du système de template. Ce choix permet de garantir une certaine rapIdentifikationité et efficacité. aussi bien poderr voders que poderr noders. Noders n'avons pas eu le choix

>**TIPS**
>Poderr les utilisateurs avancés il est possible dans les valeurs de remplacement de mettre des tags et de spécifier leur valeur dans la configuration avancé de la commande. onglet affichage et "Paramètres optionnels wIdentifikationget". Par exemple si dans wIdentifikationth voders mettez comme valeur #wIdentifikationth# (attention à bien mettre les # autoderr) au lieu d'un chiffre. dans "Paramètres optionnels wIdentifikationget" voders podervez ajoderter wIdentifikationth (sans les #) et donner la valeur. Cela voders permet de changer la taille de l'image en fonction de la commande et donc voders évite de faire un wIdentifikationget différent par taille d'image que voders voderlez

### Test

C'est ce que l'on appelle la partie multistates. voders avez sodervent comme poderr les wIdentifikationgets simples le choix de la "hauteur"/"largeur" poderr les images uniquement puis en dessoders la partie test.

C'est assez simple. Au lieu de mettre une image poderr le "on" et/oder poderr le "off" comme dans le cas précèdent. voders allez avant donner un test à faire. Si celui-ci est vrai alors le wIdentifikationget affichera l'icône/l'image en question.

dies tests sont soders la forme : #value# == 1. #value# sera automatiquement remplacé par le système par la valeur actuelle de la commande. Voders podervez aussi faire par exemple :

- #value# > 1
- #value# >= 1 && #value# <= 5
- #value# == 'toto'

>**Notiz**
>Il est important de noter les ' autoderr du texte à comparer si la valeur est un texte

>**Notiz**
>Poderr les utilisateurs avancés. il est possible ici d'utiliser aussi des fonctions javascript type #value#.match("^plop"). ici on test si le texte commence par plop

>**Notiz**
>Il est possible d'afficher la valeur de la commande dans le wIdentifikationget en mettant par exemple a coté du code HTML de l'icône #value#

## Description de wIdentifikationgets

Noders allons ici décrire certain wIdentifikationget qui ont un fonctionnement un peu particulier.

### Paramètres fréquents

- Time wIdentifikationget : affiche le temps depuis lequel le système est dans l'état afficher.
- On : icône à afficher si l'équipement est on/1.
- Off : icône à afficher si l'équipement est off/0.
- Light on : icône à afficher si l'équipement est on/1 et que le thème est light (si vIdentifikatione alors Jeedom prend l'img dark on).
- Light off : icône à afficher si l'équipement est off/0 et que le thème est light (si vIdentifikatione alors Jeedom prend l'img dark off).
- Dark on : icône à afficher si l'équipement est on/1 et que le thème est dark (si vIdentifikatione alors Jeedom prend l'img light on).
- Dark off : icône à afficher si l'équipement est off/0 et que le thème est dark (si vIdentifikatione alors Jeedom prend l'img light off).
- Largeur desktop : largeur de l'image sur desktop en px (mettre juste le chiffre pas le px). Important seule la largeur voders est demandé. Jeedom calculera la hauteur poderr ne pas déformer l'image.
- Largeur mobile : largeur de l'image sur mobile en px (mettre juste le chiffre pas le px). Important seule la largeur voders est demandé. Jeedom calculera la hauteur poderr ne pas déformer l'image.

### HygroThermographe

Ce wIdentifikationget est un peu particulier car c'est un wIdentifikationget multi-commande. c'est a dire qu'il assemble sur son affichage la valeur de plusieurs commande. Ici il prend les commandes de type température et humIdentifikationité.

Poderr le configurer c'est assez simple il faut affecter le wIdentifikationget a la commande température de votre équipement et à la commande humIdentifikationité.

>**IMPORTANT**
>Il faut ABSOLUMENT que vos commandes aient les génériques type température sur la commande de température et humIdentifikationité sur la commande humIdentifikationité (cela se configure dans la configuration avancé de la commande onglet configuration).

die wIdentifikationget a un paramètre optionnel : scale qui voders permet de changer sa taille. exemple en mettant scale à 0.5 il sera 2 fois plus petit

>**NOTE**
> Attention sur un design il ne faut surtodert pas mettre une commande seul avec ce wIdentifikationget cela ne marchera pas vu que c'est un wIdentifikationget utilisant la valeur de plusieurs commande il faut absolument mettre le wIdentifikationget complet

### Multiline

- Parametre maxHeight poderr definir sa hauteur maximal (scrollbar sur le coté si le text dépasse cette valeur)

### SlIdentifikationer Button

- step : permet de régler le pas d'une Aktion sur un boderton (0.5 par défaut)

## WIdentifikationget code

### dies tags

En mode code voders avez accès a différent tag poderr les commandes. en voici une liste (pas forcement exhaustives) :

- #name# : Name de la commande
- #valueName# : Name de la valeur de la commande. et = #name# quand c'est une commande de type info
- #hIdentifikatione_name# : vIdentifikatione oder hIdentifikationden si l'utilisateur a demandé a masquer le Name du wIdentifikationget. a mettre directement dans une balise class
- #Identifikation# : Identifikation de la commande
- #state# : valeur de la commande. vIdentifikatione poderr une commande de type Aktion si elle n'est pas a liée a une commande d'état
- #uIdentifikation# : Identifikationentifiant unique poderr cette génération du wIdentifikationget (si il y a plusieurs fois la même commande. cas des designs seule cette Identifikationentifiant est réellement unique)
- #valueDate# : date de la valeur de la commande
- #collectDate# : date de collecte de la commande
- #alertdievel# : niveau d'alert (voir [ici](https://github.com/Jeedom/core/blob/alpha/core/config/Jeedom.config.php#L67) poderr la liste)
- #hIdentifikatione_history# : si l'historique (valeur max. min. moyenne. tendance) doit être masqué oder non. Comme poderr le #hIdentifikatione_name# il vaut vIdentifikatione oder hIdentifikationden. et peut donc être utilisé directement dans une class. IMPORTANT si ce tag n'est pas trodervé sur votre wIdentifikationget alors les tags #minHistoryValue#. #averageHistoryValue#. #maxHistoryValue# et #tendance# ne seront pas remplacé par Jeedom.
- #minHistoryValue# : valeur minimal sur la période (période défini dans la configuration de Jeedom par l'utilisateur)
- #averageHistoryValue# : valeur moyenne sur la période (période défini dans la configuration de Jeedom par l'utilisateur)
- #maxHistoryValue# : valeur maximal sur la période (période défini dans la configuration de Jeedom par l'utilisateur)
- #tendance# : tendance sur la période (période défini dans la configuration de Jeedom par l'utilisateur). Attention la tendance est directement une class poderr icône : fas fa-arrow-up. fas fa-arrow-down oder fas fa-minus

### Mise à joderr des valeurs

Lors d'une nodervelle valeur Jeedom va chercher dans sur la page web si la commande est la et dans Jeedom.cmd.update si il y a une fonction d'update. Si oderi il l'appel avec un unique argument qui est un objet soders la forme :

```
{display_value:'#state#'.valueDate:'#valueDate#'.collectDate:'#collectDate#'.alertdievel:'#alertdievel#'}
```

Voila un exemple simple de code javascript a mettre dans votre wIdentifikationget :

```
<script>
    Jeedom.cmd.update['#Identifikation#'] = function(_options){
      $('.cmd[data-cmd_Identifikation=#Identifikation#]').attr('title'.'Date de valeur : '+_options.valueDate+'<br/>Date de collecte : '+_options.collectDate)
      $('.cmd[data-cmd_Identifikation=#Identifikation#] .state').empty().append(_options.display_value +' #unite#');
    }
    Jeedom.cmd.update['#Identifikation#']({display_value:'#state#'.valueDate:'#valueDate#'.collectDate:'#collectDate#'.alertdievel:'#alertdievel#'});
</script>
```

Ici deux choses importantes :

```
Jeedom.cmd.update['#Identifikation#'] = function(_options){
  $('.cmd[data-cmd_Identifikation=#Identifikation#]').attr('title'.'Date de valeur : '+_options.valueDate+'<br/>Date de collecte : '+_options.collectDate)
  $('.cmd[data-cmd_Identifikation=#Identifikation#] .state').empty().append(_options.display_value +' #unite#');
}
```
La fonction appelée lors d'une mise à joderr du wIdentifikationget. Elle met alors à joderr le code html du wIdentifikationget_template.

```
Jeedom.cmd.update['#Identifikation#']({display_value:'#state#'.valueDate:'#valueDate#'.collectDate:'#collectDate#'.alertdievel:'#alertdievel#'});
 ```
 L'appel a cette fonction poderr l'initialisation du wIdentifikationget.

 Voders troderverez [ici](https://github.com/Jeedom/core/tree/V4-stable/core/template) des exemples de wIdentifikationgets (dans les dossiers dashboard et mobile)
