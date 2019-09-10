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
