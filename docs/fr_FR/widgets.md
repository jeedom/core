La page widgets vous permet de créer des widgets personnalisés et uniques pour votre Jeedom.

Il y a 2 possibilités :

- Soit en cliquant sur le bouton code et directement écrire votre code en html pour votre widget (ce n'est pas forcement ce que nous conseillons car lors des mises à jour de jeedom votre code peut devenir incompatible avec jeedom)
- Soit en faisant un widget basé sur un template que l'on fournit

# Mais c'est quoi un template ?

Pour faire simple c'est du code (ici html) où l'on a prédéfini certaines parties que vous allez pouvoir configurer comme vous le voulez.

Dans les cas des widgets, on vous propose souvent la personnalisation des icônes ou de mettre les images que vous voulez.

# Les templates

Il y a 2 types de templates :

- Les "simples" : type une icône/image pour le "on" et une icône/image pour le "off"
- Les "multistates" : cela vous permet de définir par exemple une image si la commande a pour valeur "XX" et une autre si > à "YY" et encore si < à "ZZ". Ou même une image si la valeur vaut "toto", une autre si c'est "plop" et ainsi de suite.

# Comment faire ?

Une fois sur la page Outils -> Widget il vous faut cliquer sur "Ajouter" et donner un nom à votre nouveau widget.

Ensuite :
- Vous choisissez s’il s'applique sur une commande de type action ou info
- En fonction de votre choix précèdent, vous allez devoir choisir le sous type de la commande (binaire, numérique, autre...)
- Puis enfin le template en question (nous envisageons de pour vous mettre des exemples de rendus pour chaque template)
- Une fois le template choisi, jeedom vous donne les possibilités de configuration de celui-ci

## Remplacement

C'est ce que l'on appelle un widget simple, ici vous avez juste à dire que le "on" correspond à telle icone/image (avec le bouton choisir), le "off" est celui-là ec. Ensuite en fonction du template, il peut vous être demander aussi la largeur (width) et la hauteur (height). Ce n’est valable que pour les images.

>**Note**
>
>Nous sommes désolés pour les noms en anglais, il s’agit d’une contrainte du système de template. Ce choix permet de garantir une certaine rapidité et efficacité, aussi bien pour vous que pour nous. Nous n'avons pas eu le choix

>**TIPS**
>
>Pour les utilisateurs avancés il est possible dans les valeurs de remplacement de mettre des tags et de spécifier leur valeur dans la configuraiton avancé de la commande, onglet affichage et "Paramètres optionnels widget". Par exemple si dans width vous mettez comme valeur #width# (attention à bien mettre les # autour) au lieu d'un chiffre, dans "Paramètres optionnels widget" vous pouvez ajouter width (sans les #) et donner la valeur. Cela vous permet de changer la taille de l'image en fonction de la commande et donc vous evite de faire un widget different par taille d'image que vous voulez

## Test

C'est ce que l'on appelle la partie multistates, vous avez souvent comme pour les widgets simples le choix de la "hauteur"/"largeur" pour les images uniquement puis en dessous la partie test.

C'est assez simple. Au lieu de mettre une image pour le "on" et/ou pour le "off" comme dans le cas précèdent, vous allez avant donner un test à faire. Si celui-ci est vrai alors le widget affichera l'icône/l'image en question.

Les tests sont sous la forme : #value# == 1, #value# sera automatiquement remplacé par le système par la valeur actuelle de la commande. Vous pouvez aussi faire par exemple :

- #value# > 1
- #value# >= 1 && #value# <= 5
- #value# == 'toto'

>**Note**
>
>Il est important de noter les ' autour du texte à comparer si la valeur est un texte

>**Note**
>
>Pour les utilisateurs avancés, il est possible ici d'utiliser aussi des fonctions javascript type #value#.match("^plop"), ici on test si le texte commence par plop

>**Note**
>
>Il est possible d'afficher la valeur de la commande dans le widget en mettant par exemple a coté du code HTML de l'icone #value#

# Description de widgets

Nous allons ici décrire certain widget qui ont un fonctionnement un peu particulier.

## Paramètres fréquents

- Time widget : affiche le temps depuis lequel le systeme est dans l'état afficher.
- On : icone à afficher si l'équipement est on/1
- Off : icone à afficher si l'équipement est off/0
- Light on : icone à afficher si l'équipement est on/1 et que le theme est light (si vide alors jeedom prend l'img dark on)
- Light off : icone à afficher si l'équipement est off/0 et que le theme est light (si vide alors jeedom prend l'img dark off)
- Dark on : icone à afficher si l'équipement est on/1 et que le theme est dark (si vide alors jeedom prend l'img light on)
- Dark off : icone à afficher si l'équipement est off/0 et que le theme est dark (si vide alors jeedom prend l'img light off)
- Largeur desktop : largeur de l'image sur desktop en px (mettre juste le chiffre pas le px). Important seule la largeur vous est demandé, jeedom calculera la hauteur pour ne pas deformer l'image
- Largeur mobile : largeur de l'image sur mobile en px (mettre juste le chiffre pas le px). Important seule la largeur vous est demandé, jeedom calculera la hauteur pour ne pas deformer l'image

## HygroThermographe

Ce widget est un peu particulier car c'est un widget multi-commande, c'est a dire qu'il assemble sur son affichage la valeur de plusieurs commande. Ici il prend les commandes de type temperature et humidité.

Pour le configurer c'est assez simple il faut affecter le widget a la commande température de votre équipement et à la commande humidité.

>**IMPORTANT**
>
>Il faut ABSOLUMENT que vos commandes aient les génériques type temperature sur la commande de temperature et humidité sur la commande humidité (cela se configure dans la configuration avancé de la commande onglet configuration).

Le widget a un paramètre optionnel : scale qui vous permet de changer sa taille, exemple en mettant scale à 0.5 il sera 2 fois plus petit

>**NOTE**
>
> Attention sur un design il ne faut surtout pas mettre une commande seul avec ce widget cela ne marchera pas vu que c'est un widget utilisant la valeur de plusieurs commande il faut absolument mettre le widget complet
