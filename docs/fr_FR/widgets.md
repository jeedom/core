La page Widgets vous permet de créer des widgets personnalisés et uniques pour votre Jeedom.

Il y a 2 possibilités :

- soit en cliquant sur le bouton code et directement écrire votre code en html pour votre widget (ce n'est pas forcément ce que nous conseillons car lors des mises à jour de Jeedom votre code peut devenir incompatible avec Jeedom)
- soit en faisant un widget basé sur un template que nous fournissons et maintenons.

# Mais c'est quoi un template ?

Pour faire simple c'est du code (ici html) où l'on a prédéfini certaines parties que vous allez pouvoir configurer comme vous le voulez.

Dans les cas des widgets souvent on vous propose la personnalisation des icones ou de mettre les images que vous voulez.

# Les templates

Il y a 2 types de templates :

- ceux simples, type une icone/image pour le on et une icone/image pour le off
- les multistates : ils vous permettent de définir par exemple une image si la commande a pour valeur XX et une autre si > à YY et encore si < à ZZ. Ou même une image si la valeur vaut toto, une autre si c'est plop et ainsi de suite

# Comment faire ?

Une fois sur la page Outils -> Widgets cliquer sur "Ajouter" et donner un nom à votre nouveau widget.

Esnuite :
- Choisir si il s'applique sur une commande de type action ou info
- En fonction du choix précedent vous allez devoir choisir le sous-type de la commande (binaire, numérique, autre.)
- Puis enfin le template en question (Des exemples de rendu seront ajoutés pour chaque template)
- Une fois le template choisit Jeedom vous affiche les possibilités de configuration de celui-ci.

## Remplacement

C'est ce qu'on appel un widget simple, ici vous avez juste à dire que le "on" c'est telle icone/image (vous avez le bouton choisir), idem pour le "off". Ensuite en fonction du template il peut vous demander aussi la largeur (width) et la hauteur (height) pour les images uniquement.

>**Note**
>
>Nous sommes désolé pour les noms en anglais mais pour garder un système de template rapide et efficace aussi bien pour vous que pour nous, nous n'avons pas eu le choix.

## Test

C'est ce qu'on appelle la partie multistates, vous avez souvent comme pour les widegets simples, le choix de la hauteur / largeur pour les images uniquement et en dessous la partie test.

C'est assez simple au lieu de simplement mettre une image pour le on et/ou pour le off comme dans le cas précédent vous allez donner un test à faire si celui-ci est vrai alors le widget affichera l'icone/l'image en question.

Les tests sont sous la forme : #value# == 1, #value# sera automatiquement remplacé par le système par la valeur actuelle de la commande. Vous pouvez aussi faire par exemple :

- #value# > 1
- #value# >= 1 && #value# <= 5
- '#value#' == 'toto'

>**Note**
>
>Il est important de noter les ' autour de #value# et du texte a comparrer si la valeur est un texte

>**Note**
>
>Pour les utilisateurs avancés il est possible ici d'utiliser aussi des fonctions javascript type '#value#'.match("^plop"), ici on teste si le texte commence par plop
