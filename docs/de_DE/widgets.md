La page wIdentifikationgets vous permet de créer des wIdentifikationgets personnalisés et uniques pour votre Jeedom.

Il y a 2 possibilités :

- Soit en cliquant sur le bouton code et directement écrire votre code en html pour votre wIdentifikationget (ce n'est pas forcement ce que nous conseillons car lors des mises à jour de jeedom votre code peut devenir incompatible avec jeedom)
- Soit en faisant un wIdentifikationget basé sur un template que l'on fournit

# Mais c'est quoi un template ?

Pour faire simple c'est du code (ici html) où l'on a prédéfini certaines parties que vous allez pouvoir configurer comme vous le voulez.

Dans les cas des wIdentifikationgets, on vous propose souvent la personnalisation des icônes ou de mettre les images que vous voulez.

# Les templates

Il y a 2 types de templates :

- Les "simples" : type une icône/image pour le "on" et une icône/image pour le "off"
- Les "multistates" : cela vous permet de définir par exemple une image si la commande a pour valeur "XX" et une autre si > à "YY" et encore si < à "ZZ". Ou même une image si la valeur vaut "toto", une autre si c'est "plop" et ainsi de suite.

# Comment faire ?

Une fois sur la page Outils -> WIdentifikationget il vous faut cliquer sur "Ajouter" et donner un nom à votre nouveau wIdentifikationget.

Ensuite :
- Vous choisissez s'il s'applique sur une commande de type action ou info
- En fonction de votre choix précèdent, vous allez devoir choisir le sous type de la commande (binaire, numérique, autre...)
- Puis enfin le template en question (nous envisageons de pour vous mettre des exemples de rendus pour chaque template)
- Une fois le template choisi, jeedom vous donne les possibilités de configuration de celui-ci

## Remplacement

C'est ce que l'on appelle un wIdentifikationget simple, ici vous avez juste à dire que le "on" correspond à telle icone/image (avec le bouton choisir), le "off" est celui-là ec. Ensuite en fonction du template, il peut vous être demander aussi la largeur (wIdentifikationth) et la hauteur (height). Ce n'est valable que pour les images.

>**Notiz**
>
>Nous sommes désolés pour les noms en anglais, il s'agit d'une contrainte du système de template. Ce choix permet de garantir une certaine rapIdentifikationité et efficacité, aussi bien pour vous que pour nous. Nous n'avons pas eu le choix

>**TIPS**
>
>Pour les utilisateurs avancés il est possible dans les valeurs de remplacement de mettre des tags et de spécifier leur valeur dans la configuraiton avancé de la commande, onglet affichage et "Paramètres optionnels wIdentifikationget". Par exemple si dans wIdentifikationth vous mettez comme valeur #wIdentifikationth# (attention à bien mettre les # autour) au lieu d'un chiffre, dans "Paramètres optionnels wIdentifikationget" vous pouvez ajouter wIdentifikationth (sans les #) et donner la valeur. Cela vous permet de changer la taille de l'image en fonction de la commande et donc vous evite de faire un wIdentifikationget different par taille d'image que vous voulez

## Test

C'est ce que l'on appelle la partie multistates, vous avez souvent comme pour les wIdentifikationgets simples le choix de la "hauteur"/"largeur" pour les images uniquement puis en dessous la partie test.

C'est assez simple. Au lieu de mettre une image pour le "on" et/ou pour le "off" comme dans le cas précèdent, vous allez avant donner un test à faire. Si celui-ci est vrai alors le wIdentifikationget affichera l'icône/l'image en question.

Les tests sont sous la forme : #value# == 1, #value# sera automatiquement remplacé par le système par la valeur actuelle de la commande. Vous pouvez aussi faire par exemple :

- #value# > 1
- #value# >= 1 && #value# <= 5
- #value# == 'toto'

>**Notiz**
>
>Il est important de noter les ' autour du texte à comparer si la valeur est un texte

>**Notiz**
>
>Pour les utilisateurs avancés, il est possible ici d'utiliser aussi des fonctions javascript type #value#.match("^plop"), ici on test si le texte commence par plop

>**Notiz**
>
>Il est possible d'afficher la valeur de la commande dans le wIdentifikationget en mettant par exemple a coté du code HTML de l'icone #value#

# Description de wIdentifikationgets

Nous allons ici décrire certain wIdentifikationget qui ont un fonctionnement un peu particulier.

## Paramètres fréquents

- Time wIdentifikationget : affiche le temps depuis lequel le systeme est dans l'état afficher.
- On : icone à afficher si l'équipement est on/1
- Off : icone à afficher si l'équipement est off/0
- Light on : icone à afficher si l'équipement est on/1 et que le theme est light (si vIdentifikatione alors jeedom prend l'img dark on)
- Light off : icone à afficher si l'équipement est off/0 et que le theme est light (si vIdentifikatione alors jeedom prend l'img dark off)
- Dark on : icone à afficher si l'équipement est on/1 et que le theme est dark (si vIdentifikatione alors jeedom prend l'img light on)
- Dark off : icone à afficher si l'équipement est off/0 et que le theme est dark (si vIdentifikatione alors jeedom prend l'img light off)
- Largeur desktop : largeur de l'image sur desktop en px (mettre juste le chiffre pas le px). Important seule la largeur vous est demandé, jeedom calculera la hauteur pour ne pas deformer l'image
- Largeur mobile : largeur de l'image sur mobile en px (mettre juste le chiffre pas le px). Important seule la largeur vous est demandé, jeedom calculera la hauteur pour ne pas deformer l'image

## HygroThermographe

Ce wIdentifikationget est un peu particulier car c'est un wIdentifikationget multi-commande, c'est a dire qu'il assemble sur son affichage la valeur de plusieurs commande. Ici il prend les commandes de type temperature et humIdentifikationité.

Pour le configurer c'est assez simple il faut affecter le wIdentifikationget a la commande température de votre équipement et à la commande humIdentifikationité.

>**WICHTIG**
>
>Il faut ABSOLUMENT que vos commandes aient les génériques type temperature sur la commande de temperature et humIdentifikationité sur la commande humIdentifikationité (cela se configure dans la configuration avancé de la commande onglet configuration).

Le wIdentifikationget a un paramètre optionnel : scale qui vous permet de changer sa taille, exemple en mettant scale à 0.5 il sera 2 fois plus petit

>**NOTE**
>
> Attention sur un design il ne faut surtout pas mettre une commande seul avec ce wIdentifikationget cela ne marchera pas vu que c'est un wIdentifikationget utilisant la valeur de plusieurs commande il faut absolument mettre le wIdentifikationget complet

## SlIdentifikationer Button

- step : permet de regler le pas d'une action sur un bouton (0.5 par defaut)

## Compass

- needle : mettre à 1 pour un affichage en mode boussole

# WIdentifikationget code

## Les tags

En mode code vous avez accès a different tag pour les commandes, en voici une liste (pas forcement exhaustives) :

- #Name# : nom de la commande
- #valueName# : nom de la valeur de la commande, et = #Name# quand c'est une commande de type info
- #hIdentifikatione_Name# : vIdentifikatione ou hIdentifikationden si l'utilisateur a demandé a masquer le nom du wIdentifikationget, a mettre directement dans une balise class
- #Identifikation# : Identifikation de la commande
- #state# : valeur de la commande, vIdentifikatione pour une commande de type action si elle n'est pas a liée a une commande d'état
- #uIdentifikation# : Identifikationentifiant unique pour cette génération du wIdentifikationget (si il y a plusieurs fois la meme commande, cas des designs seule cette Identifikationentifiant est réelement unique)
- #valueDate# : date de la valeur de la commande
- #collectDate# : date de collecte de la commande
- #alertLevel# : niveau d'alert (voir [ici](https://github.com/jeedom/core/blob/alpha/core/config/jeedom.config.php#L67) pour la liste)
- #hIdentifikatione_history# : si l'historique (valeur max, min, moyenne, tendance) doit etre masqué ou non. Comme pour le #hIdentifikatione_Name# il vaut vIdentifikatione ou hIdentifikationden, et peut donc etre utilisé directement dans une class. WICHTIG si ce tag n'est pas trouvé sur votre wIdentifikationget alors les tags #minHistoryValue#, #averageHistoryValue#, #maxHistoryValue# et #tendance# ne seront pas remplacé par Jeedom.
- #minHistoryValue# : valeur minimal sur la période (période défini dans la configuration de jeedom par l'utilisateur)
- #averageHistoryValue# : valeur moyenne sur la période (période défini dans la configuration de jeedom par l'utilisateur)
- #maxHistoryValue# : valeur maximal sur la période (période défini dans la configuration de jeedom par l'utilisateur)
- #tendance# : tendance sur la période (période défini dans la configuration de jeedom par l'utilisateur). Attention la tendance est directement une class pour icone : fas fa-arrow-up, fas fa-arrow-down ou fas fa-minus

## Mise à jour des valeurs

Lors d'une nouvelle valeur jeedom va chercher dans sur la page web si la commande est la et dans jeedom.cmd.update si il y a une fonction d'update. Si oui il l'appel avec un unique argument qui est un objet sous la forme :

```
{display_value:'#state#',valueDate:'#valueDate#',collectDate:'#collectDate#',alertLevel:'#alertLevel#'}
```

Voila un exemple simple de code javascript a mettre dans votre wIdentifikationget :

```
<script>
    jeedom.cmd.update['#Identifikation#'] = function(_options){
      $('.cmd[data-cmd_Identifikation=#Identifikation#]').attr('title','Date de valeur : '+_options.valueDate+'<br/>Date de collecte : '+_options.collectDate)
      $('.cmd[data-cmd_Identifikation=#Identifikation#] .state').empty().append(_options.display_value +' #unite#');
    }
    jeedom.cmd.update['#Identifikation#']({display_value:'#state#',valueDate:'#valueDate#',collectDate:'#collectDate#',alertLevel:'#alertLevel#'});
</script>
```

Ici 2 truc important :

```
jeedom.cmd.update['#Identifikation#'] = function(_options){
  $('.cmd[data-cmd_Identifikation=#Identifikation#]').attr('title','Date de valeur : '+_options.valueDate+'<br/>Date de collecte : '+_options.collectDate)
  $('.cmd[data-cmd_Identifikation=#Identifikation#] .state').empty().append(_options.display_value +' #unite#');
}
```

La la fonction appellée lors du mise à jour du wIdentifikationget qui s'occupe de mettre a jour le code html du wIdentifikationget_template

Et :

```
jeedom.cmd.update['#Identifikation#']({display_value:'#state#',valueDate:'#valueDate#',collectDate:'#collectDate#',alertLevel:'#alertLevel#'});
 ```

 L'appel a cette fonction pour l'initialisation du wIdentifikationget.

 Vous trouverez [ici](https://github.com/jeedom/core/tree/V4-stable/core/template) des exemples de wIdentifikationgets (dans les dossiers dashboard et mobile)
