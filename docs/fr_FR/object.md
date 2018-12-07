Les **objets** permettent de définir l’arborescence de votre domotique.
Tous les équipements que vous créerez devront appartenir à un objet et
pourront ainsi être plus facilement repérables. On dit alors que l’objet
est le **parent** de l’équipement. La gestion des objets est accessible
à partir du menu **Outils → Objets**.

Pour laisser libre choix à la personnalisation, vous pouvez nommer ces
objets comme vous le voulez. Usuellement, on y définira les différentes
parties de sa maison, comme le nom des pièces (c’est d’ailleurs la
configuration recommandée).

Gestion 
=======

Deux options s’offrent à vous :

-   **Ajouter** : Permet de créer un nouvel objet.

-   **Vue d’ensemble** : Permet d’afficher la liste des objets créés
    ainsi que leur configuration.

Mes objets 
==========

Une fois que vous avez créé un objet, il apparaîtra dans cette partie.

Onglet Objet 
------------

En cliquant sur un objet, vous accédez à sa page de configuration. Quels
que soient les changements effectués, n’oubliez pas de sauvegarder à la
fin.

Voici donc les différentes caractéristiques pour configurer un objet :

-   **Nom de l’objet** : Le nom de votre objet.

-   **Père** : Indique le parent de l’objet courant, cela permet de
    définir une hiérarchie entre les objets. Par exemple : Le salon a
    pour parent l’appartement. Un objet ne peut avoir qu’un seul parent
    mais plusieurs objets peuvent avoir le même parent.

-   **Visible** : Cochez cette case pour rendre visible cet objet.

-   **Masquer sur le dashboard** : Cochez cette case pour masquer
    l’objet sur le dashboard. Il est tout de même conservé dans la
    liste, ce qui permet de l’afficher, mais uniquement de
    manière explicite.

-   **Icône** : Permet de choisir une icône pour votre objet.

-   **Couleur du tag** : Permet de choisir la couleur de l’objet et des
    équipements qui lui sont rattachés.

-   **Couleur du texte du tag** : Permet de choisir la couleur du texte
    de l’objet. Ce texte sera par dessus la **couleur du tag**. A vous
    de choisir une couleur pour rendre le texte lisible.

-   **Couleur du texte du résumé** : Permet de choisir la couleur des
    résultats du résumé de l’objet dans le dashboard.

-   **Taille sur le dashboard (1 à 12)** : Permet de définir la largeur
    de l’affichage de cet objet dans le dashboard. Par exemple : si vous
    mettez `6` à deux objets qui se suivent dans la liste, alors il
    seront côte à côte sur le dashboard. Si vous mettez `3` à quatre
    objets qui se suivent, ils seront également côte à côte.

> **Tip**
>
> Vous pouvez modifier l’ordre d’affichage des objets dans le dashboard.
> Dans le menu, à gauche de votre page, utilisez les flèches verticales
> en glisser/déposer pour leur donner une nouvelle place.

> **Tip**
>
> Vous pouvez voir un graphique représentant tous les éléments de Jeedom
> rattachés à cet objet en cliquant sur le bouton **Liens**, en haut à
> droite.

> **Tip**
>
> Quand un équipement est créé et qu’aucun parent n’a été défini, il
> aura comme parent : **Aucun** .

Onglet Résumé 
-------------

Les résumés sont des informations globales, affectées à un objet, qui
s’affichent notamment sur le dashboard à côté du nom de ce dernier.

### Tableau d’affichage 

Les colonnes représentent les résumés affectés à l’objet courant. Trois
lignes vous sont proposées :

-   **Remonter dans le résumé global** : Cochez la case si vous
    souhaitez que le résumé soit affiché dans la barre de menu
    de Jeedom.

-   **Masquer en desktop** : Cochez la case si vous ne souhaitez pas que
    le résumé s’affiche à côté du nom de l’objet sur le dashboard.

-   **Masquer en mobile** : Cochez la case si vous ne souhaitez pas que
    le résumé s’affiche quand vous l’affichez depuis un mobile.

### Commandes 

Chaque onglet représente un type de résumé défini dans la configuration
de Jeedom. Cliquez sur **Ajouter une commande** pour que celle-ci soit
prise en compte dans le résumé. Vous avez le choix de sélectionner la
commande de n’importe quel équipement de Jeedom, même s’il n’a pas pour
parent cet objet.

> **Tip**
>
> Si vous souhaitez ajouter un type de résumé ou pour configurer la
> méthode de calcul du résultat, l’unité, l’icône et le nom d’un résumé,
> vous devez aller dans la configuration générale de Jeedom :
> **Administration → Configuration → Onglet Résumés**.

Vue d’ensemble 
==============

La vue d’ensemble vous permet de visualiser l’ensemble des objets dans
Jeedom, ainsi que leur configuration :

-   **ID** : ID de l’objet.

-   **Objet** : Nom de l’objet.

-   **Père** : Nom de l’objet parent.

-   **Visible** : Visibilité de l’objet.

-   **Masqué** : Indique si l’objet est masqué sur le dashboard.

-   **Résumé Défini** : Indique le nombre de commandes par résumé. Ce
    qui est en bleu est pris en compte dans le résumé global.

-   **Résumé Dashboard Masqué** : Indique les résumés masqués sur
    le dashboard.

-   **Résumé Mobile Masqué** : Indique les résumés masqués sur
    le mobile.


