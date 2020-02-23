# WIdentifikationgets
**Outils → WIdentifikationgets**

La page wIdentifikationgets vous permet de créer des wIdentifikationgets personnalisés pour votre Jeedom.

Il y a deux types de wIdentifikationgets personnalisés :

- Les wIdentifikationgets basés sur un template (gérés par le Core de Jeedom).
- Les wIdentifikationgets basés sur du code utilisateur.

> **Notiz**
>
> Si les wIdentifikationgets basés sur des templates sont intégrés au Core et donc suivis par l'équipe de développement, cette dernière n'a aucun moyen d'assurer la compatibilité des wIdentifikationgets basés sur du code utilisateur en fonction des évolutions de Jeedom.

## Gestion

Quatre options s'offrent à vous :
- **Ajouter** : Permet de créer un nouveau wIdentifikationget.
- **Importer** : Permet d'importer un wIdentifikationget sous forme de fichier json précedemment exporté.
- **Code** : Ouvre un éditeur de fichiers permettant d'éditer les wIdentifikationget code.
- **Remplacement** : Ouvre une fenêtre permettant de remplacer un wIdentifikationget par un autre sur tout les équipements l'utilisant.

## Mes wIdentifikationgets

Une fois que vous avez créé un wIdentifikationget, il apparaîtra dans cette partie.

> **Tip**
>
> Vous pouvez ouvrir un wIdentifikationget en faisant :
> - Clic sur l'un d'entre eux.
> - Ctrl Clic ou Clic Centre pour l'ouvrir dans un nouvel onglet du navigateur.

Vous disposez d'un moteur de recherche permettant de filtrer l'affichage des wIdentifikationget. La touche Echap annule la recherche.
A droite du champ de recherche, trois boutons que l'on retrouve à plusieurs endroits de Jeedom:
- La croix pour annuler la recherche.
- Le dossier ouvert pour déplier tout les panneaux et afficher touts les wIdentifikationget.
- Le dossier fermé pour replier tout les panneaux.

Une fois sur la configuration d'un wIdentifikationget, vous disposez d'un menu contextuel au Clic Droit sur les onglets du wIdentifikationget. Vous pouvez également utiliser un Ctrl Clic ou Clic Centre pour ouvrir directement un autre wIdentifikationget dans un nouvel onglet du navigateur.


## Principe

Mais c'est quoi un template ?
Pour faire simple, c'est du code (ici html/js) intégré au Core, dont certaines parties sont configurable par l'utilisateur avec l'interface graphique du Core.

Suivant le type de wIdentifikationget, vous pouvez généralement personnaliser des icônes ou mettre des images de votre choix.

## Les templates

Il y a deux types de template :

- Les "**simples**" : Type une icône/image pour le "on" et une icône/image pour le "off"
- Les "**multistates**" : Cela permet de définir par exemple une image si la commande a pour valeur "XX" et une autre si > à "YY", et encore si < à "ZZ". Ou même une image si la valeur vaut "toto", une autre si "plop", et ainsi de suite.

## Création d'un wIdentifikationget

Une fois sur la page Outils -> WIdentifikationget il vous faut cliquer sur "Ajouter" et donner un nom à votre nouveau wIdentifikationget.

Ensuite :
- Vous choisissez s'il s'applique sur une commande de type Aktion ou info.
- En fonction de votre choix précèdent, vous allez devoir choisir le sous type de la commande (binaire, numérique, autre...).
- Puis enfin le template en question (nous envisageons de pour vous mettre des exemples de rendus pour chaque template).
- Une fois le template choisi, Jeedom vous donne les possibilités de configuration de celui-ci.

### Remplacement

C'est ce que l'on appelle un wIdentifikationget simple, ici vous avez juste à dire que le "on" correspond à telle icône/image (avec le bouton choisir), le "off" est celui-là etc. Ensuite en fonction du template, il peut vous être proposé la largeur (wIdentifikationth) et la hauteur (height). Ce n'est valable que pour les images.

>**Notiz**
>Nous sommes désolés pour les noms en anglais, il s'agit d'une contrainte du système de template. Ce choix permet de garantir une certaine rapIdentifikationité et efficacité, aussi bien pour vous que pour nous. Nous n'avons pas eu le choix

>**TIPS**
>Pour les utilisateurs avancés il est possible dans les valeurs de remplacement de mettre des Tags et de spécifier leur valeur dans la configuration avancé de la commande, onglet affichage et "Paramètres optionnels wIdentifikationget". Par exemple si dans wIdentifikationth vous mettez comme valeur #wIdentifikationth# (attention à bien mettre les # autour) au lieu d'un chiffre, dans "Paramètres optionnels wIdentifikationget" vous pouvez ajouter wIdentifikationth (sans les #) et donner la valeur. Cela vous permet de changer la taille de l'image en fonction de la commande et donc vous évite de faire un wIdentifikationget différent par taille d'image que vous voulez

### Test

C'est ce que l'on appelle la partie multistates, vous avez souvent comme pour les wIdentifikationgets simples le choix de la "hauteur"/"largeur" pour les images uniquement puis en dessous la partie test.

C'est assez simple. Au lieu de mettre une image pour le "on" et/ou pour le "off" comme dans le cas précèdent, vous allez avant donner un test à faire. Si celui-ci est vrai alors le wIdentifikationget affichera l'icône/l'image en question.

Les tests sont sous la forme : #Wert# == 1, #Wert# sera automatiquement remplacé par le système par la valeur actuelle de la commande. Vous pouvez aussi faire par exemple :

- #Wert# > 1
- #Wert# >= 1 && #Wert# <= 5
- #Wert# == 'toto'

>**Notiz**
>Il est important de noter les ' autour du texte à comparer si la valeur est un texte

>**Notiz**
>Pour les utilisateurs avancés, il est possible ici d'utiliser aussi des fonctions javascript type #Wert#.match("^plop"), ici on test si le texte commence par plop

>**Notiz**
>Il est possible d'afficher la valeur de la commande dans le wIdentifikationget en mettant par exemple a coté du code HTML de l'icône #Wert#

## Description de wIdentifikationgets

Nous allons ici décrire certain wIdentifikationget qui ont un fonctionnement un peu particulier.

### Paramètres fréquents

- Time wIdentifikationget : affiche le temps depuis lequel le système est dans l'état afficher.
- On : icône à afficher si l'équipement est on/1.
- Off : icône à afficher si l'équipement est off/0.
- Light on : icône à afficher si l'équipement est on/1 et que le thème est light (si vIdentifikatione alors Jeedom prend l'img dark on).
- Light off : icône à afficher si l'équipement est off/0 et que le thème est light (si vIdentifikatione alors Jeedom prend l'img dark off).
- Dark on : icône à afficher si l'équipement est on/1 et que le thème est dark (si vIdentifikatione alors Jeedom prend l'img light on).
- Dark off : icône à afficher si l'équipement est off/0 et que le thème est dark (si vIdentifikatione alors Jeedom prend l'img light off).
- Largeur desktop : largeur de l'image sur desktop en px (mettre juste le chiffre pas le px). Important seule la largeur vous est demandé, Jeedom calculera la hauteur pour ne pas déformer l'image.
- Largeur mobile : largeur de l'image sur mobile en px (mettre juste le chiffre pas le px). Important seule la largeur vous est demandé, Jeedom calculera la hauteur pour ne pas déformer l'image.

### HygroThermographe

Ce wIdentifikationget est un peu particulier car c'est un wIdentifikationget multi-commande, c'est a dire qu'il assemble sur son affichage la valeur de plusieurs commande. Ici il prend les commandes de type température et humIdentifikationité.

Pour le configurer c'est assez simple il faut affecter le wIdentifikationget a la commande température de votre équipement et à la commande humIdentifikationité.

>**IMPORTANT**
>Il faut ABSOLUMENT que vos commandes aient les génériques type température sur la commande de température et humIdentifikationité sur la commande humIdentifikationité (cela se configure dans la configuration avancé de la commande onglet configuration).

Le wIdentifikationget a un paramètre optionnel : scale qui vous permet de changer sa taille, exemple en mettant scale à 0.5 il sera 2 fois plus petit

>**NOTE**
> Attention sur un design il ne faut surtout pas mettre une commande seul avec ce wIdentifikationget cela ne marchera pas vu que c'est un wIdentifikationget utilisant la valeur de plusieurs commande il faut absolument mettre le wIdentifikationget complet

### Multiline

- Parametre maxHeight pour definir sa hauteur maximal (scrollbar sur le coté si le text dépasse cette valeur)

### SlIdentifikationer Button

- step : permet de régler le pas d'une Aktion sur un bouton (0.5 par défaut)

## WIdentifikationget code

### Les Tags

En mode code vous avez accès a différent tag pour les commandes, en voici une liste (pas forcement exhaustives) :

- #Name# : nom de la commande
- #WertName# : nom de la valeur de la commande, et = #Name# quand c'est une commande de type info
- #hIdentifikatione_Name# : vIdentifikatione ou hIdentifikationden si l'utilisateur a demandé a masquer le nom du wIdentifikationget, a mettre directement dans une balise class
- #Identifikation# : Identifikation de la commande
- #state# : valeur de la commande, vIdentifikatione pour une commande de type Aktion si elle n'est pas a liée a une commande d'état
- #uIdentifikation# : Identifikationentifiant unique pour cette génération du wIdentifikationget (si il y a plusieurs fois la même commande, cas des designs seule cette Identifikationentifiant est réellement unique)
- #WertDate# : date de la valeur de la commande
- #collectDate# : date de collecte de la commande
- #alertLevel# : niveau d'alert (voir [ici](https://github.com/Jeedom/core/blob/alpha/core/config/Jeedom.config.php#L67) pour la liste)
- #hIdentifikatione_history# : si l'historique (valeur max, min, moyenne, tendance) doit être masqué ou non. Comme pour le #hIdentifikatione_Name# il vaut vIdentifikatione ou hIdentifikationden, et peut donc être utilisé directement dans une class. IMPORTANT si ce tag n'est pas trouvé sur votre wIdentifikationget alors les Tags #minHistoryValue#, #averageHistoryValue#, #maxHistoryValue# et #tendance# ne seront pas remplacé par Jeedom.
- #minHistoryValue# : valeur minimal sur la période (période défini dans la configuration de Jeedom par l'utilisateur)
- #averageHistoryValue# : valeur moyenne sur la période (période défini dans la configuration de Jeedom par l'utilisateur)
- #maxHistoryValue# : valeur maximal sur la période (période défini dans la configuration de Jeedom par l'utilisateur)
- #tendance# : tendance sur la période (période défini dans la configuration de Jeedom par l'utilisateur). Attention la tendance est directement une class pour icône : fas fa-arrow-up, fas fa-arrow-down ou fas fa-minus

### Mise à jour des valeurs

Lors d'une nouvelle valeur Jeedom va chercher dans sur la page web si la commande est la et dans Jeedom.cmd.update si il y a une fonction d'update. Si oui il l'appel avec un unique argument qui est un objet sous la forme :

```
{display_Wert:'#state#',WertDate:'#WertDate#',collectDate:'#collectDate#',alertLevel:'#alertLevel#'}
```

Voila un exemple simple de code javascript a mettre dans votre wIdentifikationget :

```
<script>
    Jeedom.cmd.update['#Identifikation#'] = function(_options){
      $('.cmd[data-cmd_Identifikation=#Identifikation#]').attr('title','Date de valeur : '+_options.WertDate+'<br/>Date de collecte : '+_options.collectDate)
      $('.cmd[data-cmd_Identifikation=#Identifikation#] .state').empty().append(_options.display_Wert +' #unite#');
    }
    Jeedom.cmd.update['#Identifikation#']({display_Wert:'#state#',WertDate:'#WertDate#',collectDate:'#collectDate#',alertLevel:'#alertLevel#'});
</script>
```

Ici deux choses importantes :

```
Jeedom.cmd.update['#Identifikation#'] = function(_options){
  $('.cmd[data-cmd_Identifikation=#Identifikation#]').attr('title','Date de valeur : '+_options.WertDate+'<br/>Date de collecte : '+_options.collectDate)
  $('.cmd[data-cmd_Identifikation=#Identifikation#] .state').empty().append(_options.display_Wert +' #unite#');
}
```
La fonction appelée lors d'une mise à jour du wIdentifikationget. Elle met alors à jour le code html du wIdentifikationget_template.

```
Jeedom.cmd.update['#Identifikation#']({display_Wert:'#state#',WertDate:'#WertDate#',collectDate:'#collectDate#',alertLevel:'#alertLevel#'});
 ```
 L'appel a cette fonction pour l'initialisation du wIdentifikationget.

 Vous trouverez [ici](https://github.com/Jeedom/core/tree/V4-stable/core/template) des exemples de wIdentifikationgets (dans les dossiers dashboard et mobile)
