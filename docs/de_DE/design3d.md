Cette page permet de creer une vue 3d de votre maison/appartement qui pourra réagir en fonction de l'état des différents information de votre domotique.

Elle est accessible par Acceuil → Dashboard

> **Tip**
>
> Il est possible d’aller directement sur un design 3D grâce au sous-menu.

# Importation du model 3d

> **IMPORTANT**
>
> Vous ne pouvez creer votre model 3d directement dans Jeedom, il faut le faire en passant par un logiciel tierce. Nous recommandons le très bon bon SweetHome3d

Une fois votre model 3d creer il faut l'exporter en OBJ. Si vous utilisez SweetHome3d cela se fait à partir du menu "Vue 3d" puis "Exporter au format OBJ". Il faut ensuite prendre tous les fichiers générer et les mettres dans un zip (il peut avoir beaucoup de fichier du aux textures)

> **ATTENTION**
>
> Un model 3d est assez imposant (ca peut aller à plusieurs 100mo). Plus il est gros plus long sera le temps de rendu dans Jeedom

Une fois votre model 3d exporter il faut dans Jeedom creer un nouveau design 3d. Pour cela il faut passer en mode édition en cliquant que le petit crayon à droit, puis ensuite cliquer sur le +, donner un nom à ce nouveau design 3D puis valider.

Jeedom va automatiquement passer sur le nouveau design 3d, il faut repasser en mode edition et cliquer sur les petite roues crantées.

Vous pouvez à partir de cette écran envoyer : 

- Changer le nom de votre design
- Ajouter un code d'accès
- Choisir une icone
- Importer votre model 3d

Cliquez sur le bouton "envoyer" au niveau de "Model 3D" et selectionner votre zip

> **ATTENTION**
>
> Jeedom autorise au maximum l'importe d'un fichier de 150mo

> **ATTENTION**
>
> Il faut obligatoirement un fichier zip

> **Tip**
>
> Une fois l'import du fichier fait (ca peut etre assez long en fonction de la taille de celui-ci), il vous faut rafraichir la page pour voir le résultat


# Configuration des éléments

> **IMPORTANT**
>
> La configuration ne peut se faire que en mode édition

Pour configurer un element sur le design 3d il vous faire un double clic sur l'élement que vous voulez configurer. Cela va amener une fenetre ou vous pourrez : 

- Indiquer un type de lien (actuellement seul Equipement existe)
- Le lien vers l'element en question. Ici vous ne pouvez pour le moment mettre qu'un lien vers un équipement. Cela permet lors du clic sur l'élément de faire apparaitre 
- La spécitificité, la il en existe plusieurs que l'on va voir juste après, ca permet spécifier le type d'équipement et donc l'affichage d'information

## Lumière

- Statut : Commande d'état de la lumiere peut etre un binaire (0 ou 1), un numérique (de 0 à 100%) ou une couleur
- Puissance : puissance de l'ampoule (attention cela peut ne pas refleter la réaliter)

## Texte

- Texte : texte à afficher (vous pouvez mettre des commandes dedans, le texte sera automatiquement remis à jour sur changement de celle-ci)
- Taille du texte
- Couleur du texte
- Transparence du texte : de 0 (invisible) à 1 (visible)
- Couleur de fond
- Transparence du fond : de 0 (invisible) à 1 (visible)
- Couleur de la bordure
- Transparence de la bordure : de 0 (invisible) à 1 (visible)
- Espacement au dessus de l'objet : permet d'indiquer l'espacement du texte par rapport à l'élément

## Porte/Fenêtre

### Porte/Fenêtre

- Etat : état de la Porte/Fenêtre, 1 fermé et 0 ouvert
- Rotation
    - Activer : active la rotation de la Porte/Fenêtre lors de l'ouverture
    - Ouverture : le mieux la c'est de tester pour que ca correspond au mieux à votre Porte/Fenêtre
- Translation
    - Activer : active la translation lors de l'ouverture (type Porte/Fenêtre coulissante)
    - Sens : sens dans lequel la Porte/Fenêtre doit bouger (vous avez haut/bas/droite/gauche)
    - Répéter : par defaut la Porte/Fenêtre bouge de 1 fois sa dimension dans le sens donner mais vous pouvez augmenter cette valeur
- Masquer quand la Porte/Fenêtre est ouverte    
    - Activer : Masque l'élément si la Porte/Fenêtre est ouverte
- Couleur
    - Couleur ouverte : si cocher l'élément prendra cette couleur si la Porte/Fenêtre est ouverte
    - Couleur fermée : si cocher l'élément prendra cette couleur si la Porte/Fenêtre est fermée

### Volet

- Etat : état du volet, 0 ouvert autre valeur fermé
- Masquer quand le volet est ouvert
    -  Activer : masque l'élément si le volet est ouvert
- Couleur
    - Couleur fermé : si cocher l'élément prendra cette couleur si le volet est fermée

## Couleur conditionnel

Permet de donner la couleur choisis à l'élement si la condition est valide. Vous pouvez mettre autant de couleur/condition que vous voulez. 

> **Tip**
>
> Les conditions sont évaluées dans l'ordre, la premiere qui est vrai sera prise, les suivantes ne seront donc pas évaluées