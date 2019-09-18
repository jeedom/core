The **objects** allow to define the tree of your home automation.
All the equipment you create will belong to an object and
will be easier to locate. We then say that the object
is the **parent** of the equipment. Object management is accessible
from the menu **Tools → Objects**.

To give free choice to the customization, you can name these
objects as you want. Usually, we will define the different
parts of his house, like the names of the pieces (this is also the
recommended configuration).

Management
=======

Two options are available to you:

-   **Add**: Create a new object.

-   **Overview**: Displays the list of created objects
    as well as their configuration.

My objects
==========

Once you have created an object, it will appear in this part.

Object tab
------------

Clicking on an object will take you to its configuration page. What
whatever changes are made, do not forget to save to the
end.

Here are the different features to configure an object:

-   **Name of the object**: The name of your object.

-   **Father**: Indicates the parent of the current object, this allows you to
    define a hierarchy between the objects. For example: The show has
    for parent the apartment. An object can only have one parent
    but many objects can have the same parent.

-   **Visible**: Check this box to make this object visible.

-   **Hide on dashboard**: Check this box to hide
    the object on the dashboard. It is still preserved in the
    list, which allows to display it, but only
    explicit way.

-   **Icon**: Choose an icon for your object.

-   **Color of the tag**: Allows to choose the color of the object and the
    equipment attached to it.

-   **Tag text color**: Allows you to choose the color of the text
    of the object. This text will be over **the color of the tag**. To you
    to choose a color to make the text readable.

-   **Taille sur le dashboard (1 à 12)** : Permet de définir la largeur
    de l’affichage de cet objet dans le dashboard. Par exemple : si vous
    mettez `6` à deux objets qui se suivent dans la liste, alors il
    seront côte à côte sur le dashboard. Si vous mettez `3` à quatre
    objets qui se suivent, ils seront également côte à côte.

-   **Image** : Vous avez la possibilité de télécharger une image ou la
    supprimer. Au format jpeg cette image sera l'image de fond de l'objet
    quand vous l'afficherez sur le dashboard.

> **Tip**
>
> Vous pouvez modifier l’ordre d’affichage des objets dans le dashboard.
> Dans la vue d'ensemble, sélectionnez votre objet à la souris et 
> en glisser/déposer pour lui donner une nouvelle place.

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


