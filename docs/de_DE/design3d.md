Cette page permet de créer une vue 3D de votre habitation qui pourra réagir en fonction de l'état des différentes informations de votre domotique.

Elle est accessible par Acceuil → Dashboard

> **Tipp**
>
> Über das Untermenü kann direkt zu einem 3D-Design gesprungen werden.

# Import von 3D-Modellen

> **IMPORTANT**
>
> Vous ne pouvez pas créer votre modèle 3D directement dans Jeedom, il faut le faire en passant par un logiciel tiers. Nous recommandons le très bon SweetHome3d (http://www.sweethome3d.com/fr/).

Une fois votre model 3D créé il faut l'exporter au format OBJ. Si vous utilisez SweetHome3d cela se fait à partir du menu "Vue 3D" puis "Exporter au format OBJ". Il faut ensuite prendre tous les fichiers générés et les mettre dans un fichier zip (il peut y avoir beaucoup de fichiers dû aux textures).

> **IMPORTANT**
>
> Les fichiers doivent etre à la racine du zip est pas dans un sous-dossier

> **ATTENTION**
>
> Un modèle 3D est assez imposant (cela peut représenter plusieurs centaine de Mo). Plus il est gros plus long sera le temps de rendu dans Jeedom.

Une fois votre modèle 3D exporté il faut dans Jeedom créer un nouveau design 3D. Pour cela il faut passer en mode édition en cliquant sur le petit crayon à droite, puis ensuite cliquer sur le +, donner un nom à ce nouveau design 3D puis valider.

Jeedom va automatiquement passer sur le nouveau design 3D, il faut repasser en mode édition et cliquer sur les petites roues crantées.

Sie können von diesem Bildschirm aus :

- Ändern Sie den Namen Ihres Designs
- Fügen Sie einen Zugriffscode hinzu
- Wählen Sie ein Symbol
- Importer votre modèle 3D

Cliquez sur le bouton "envoyer" au niveau de "Modèle 3D" et sélectionner votre fichier zip

> **ATTENTION**
>
> Jeedom autorise l'import d'un fichier de 150mo maximum !

> **ATTENTION**
>
> Il faut obligatoirement un fichier au format zip

> **Tip**
>
> Une fois l'import du fichier effectué (cela peut être assez long en fonction de la taille de celui-ci), il vous faut rafraichir la page pour voir le résultat (F5)


# Configuration des éléments

> **IMPORTANT**
>
> La configuration ne peut se faire qu'en mode édition

Pour configurer un élément sur le design 3D il vous faire un double clic sur l'élément que vous voulez configurer. Cela va amener une fenêtre où vous pourrez :

- Indiquer un type de lien (actuellement seul Equipement existe)
- Le lien vers l'élément en question. Ici vous ne pouvez pour le moment mettre qu'un lien vers un équipement. Cela permet lors du clic sur l'élément de faire apparaitre l'équipment
- La spécitificité, là il en existe plusieurs que l'on va voir juste après, cela permet de spécifier le type d'équipement et donc l'affichage d'information

## Beleuchtung

- Statut : Commande d'état de la lumiere peut être un binaire (0 ou 1), un numérique (de 0 à 100%) ou une couleur
- Puissance : puissance de l'ampoule (attention cela peut ne pas refléter la réalité)

## Text

- Texte : texte à afficher (vous pouvez y mettre des commandes, le texte sera automatiquement remis à jour sur changement de celle-ci)
- Textgröße
- Textfarbe
- Transparence du texte : de 0 (invisible) à 1 (visible)
- Hintergrundfarbe
- Transparence du fond : de 0 (invisible) à 1 (visible)
- Rahmenfarbe
- Transparence de la bordure : de 0 (invisible) à 1 (visible)
- Espacement au-dessus de l'objet : permet d'indiquer l'espacement du texte par rapport à l'élément

## Tür/Fenster

### Tür/Fenster

- Etat : état de la Porte/Fenêtre, 1 fermé et 0 ouvert
- Rotation
    - Activer : active la rotation de la Porte/Fenêtre lors de l'ouverture
    - Ouverture : le mieux est de tester pour que cela corresponde à votre Porte/Fenêtre
- Übersetzung
    - Activer : active la translation lors de l'ouverture (type Porte/Fenêtre coulissante)
    - Sens : sens dans lequel la Porte/Fenêtre doit bouger (vous avez haut/bas/droite/gauche)
    - Répéter : par défaut la Porte/Fenêtre bouge d'une fois sa dimension dans le sens donné mais vous pouvez augmenter cette valeur
- Masquer quand la Porte/Fenêtre est ouverte
    - Activer : Masque l'élément si la Porte/Fenêtre est ouverte
- Farbe
    - Couleur ouverte : si cocher l'élément prendra cette couleur si la Porte/Fenêtre est ouverte
    - Couleur fermée : si cocher l'élément prendra cette couleur si la Porte/Fenêtre est fermée

### Volet

- Etat : état du volet, 0 ouvert autre valeur fermé
- Masquer quand le volet est ouvert
    - Activer : masque l'élément si le volet est ouvert
- Farbe
    - Couleur fermé : si cocher l'élément prendra cette couleur si le volet est fermé

## Couleur conditionnelle

Permet de donner la couleur choisie à l'élément si la condition est valide. Vous pouvez mettre autant de couleurs/conditions que vous voulez.

> **Tip**
>
> Les conditions sont évaluées dans l'ordre, la première qui est vraie sera prise, les suivantes ne seront donc pas évaluées
