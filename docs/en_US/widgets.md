La page widgets vous permet de creer des widget personnalisé et unique pour votre Jeedom.

Il y a 2 possibilités :

- soit en cliquant sur le bouton code et directement écrire votre code en html pour votre widget (ce n'est pas forcement ce que nous conseillons car lors des mises à jour de jeedom votre code peut devenir incompatible avec jeedom)
- soit en faisant un widget basé sur un template que l'on fournis

# Mais c'est quoi un template ?

Pour faire simple c'est du code (ici html) ou l'on a prédefini certaine partie que vous allez pouvoir configurer comme vous le voulez.

Dans les cas des widgets souvent on vous propose la personnalisation des icones ou de mettre les images que vous voulez.

# Les templates

Il y a 2 types de templates :

- ceux simple, type une icone/image pour le on et une icone/image pour le off
- les multistates : cela vous permettent de definir par exemple une image si la commande a pour valeur XX et une autre si > à YY et encore si < à ZZ. Ou meme une image si la valeur vaut toto, une autre si c'est plop et ainsi de suivantes

# Comment faire ?

Une fois sur la page Outils -> Widget il vous faut cliquer sur "Ajouter" et donner un nom à votre nouveau widget.

Esnuite :
- Vous choisi si il s'applique sur une commande de type action ou info
- En fonction de votre choix précedent vous allez devoir choisir le sous type de la commande (binaire, numérique, autre...)
- Puis enfin le template en question (on va voir pour vous mettre des exemples de rendu pour chaque template)
- Une fois le template choisi jeedom vous donne les possibilité de configuration de celui-ci

## Remplacement

C'est ce qu'on appel un widget simple, ici vous avez juste a dire que le "on" c'est telle icone/image (vous avez le bouton choisir), le "off" c'est ca. Ensuite en fonction du template il peut vous demander aussi la largeur (width) et la hauteur (height) pour les images uniquement

>**Note**
>
>On est désolé pour les nom en anglais la mais pour garder un systeme de template rapide et efficace aussi bien pour vous que pour nous nous n'avons pas eu le choix

## Test

C'est ce qu'on appel la partie multistates, vous avez souvent comme pour les simples le choix de la hauteur largeur pour les images uniquement et en dessous la partie test.

C'est assez simple au lieu de simplement mettre une image pour le on et/ou pour le off comme dans le cas précedent vous allez avant donner un test a faire si celui-ci est vrai alors le widget affichera l'icone/l'image en question.

Les tests sont sous la forme : #value# == 1, #value# sera automatiquement remplacé par le systeme par la valeur actuelle de la commande. Vous pouvez aussi faire par exemple :

- #value# > 1
- #value# >= 1 && #value# <= 5
- '#value#' == 'toto'

>**Note**
>
>Il est important de noter les ' autour de #value# et du texte a comparé si la valeur est un texte

>**Note**
>
>Pour les utilisateurs avancés il est possible ici d'utiliser aussi des fonctions javascript type '#value#'.match("^plop"),ici on test si le texte commence par plop
