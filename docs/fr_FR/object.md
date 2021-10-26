# Objets
**Outils → Objets**

Les **objets** permettent de définir l’arborescence de votre domotique.

Tous les équipements que vous créez doivent appartenir à un objet et sont ainsi plus facilement repérables. On dit alors que l’objet est le **parent** de l’équipement.

Pour laisser libre choix à la personnalisation, vous pouvez nommer ces objets comme vous le voulez. Usuellement, on y définira les différentes parties de sa maison, comme le nom des pièces (c’est d’ailleurs la configuration recommandée).

![Objets](./images/object_intro.gif)

## Gestion

Deux options s’offrent à vous :
- **Ajouter** : Permet de créer un nouvel objet.
- **Vue d’ensemble** : Permet d’afficher la liste des objets créés ainsi que leur configuration.

## Vue d’ensemble

La vue d’ensemble vous permet de visualiser l’ensemble des objets dans Jeedom, ainsi que leur configuration :

- **ID** : ID de l’objet.
- **Objet** : Nom de l’objet.
- **Père** : Nom de l’objet parent.
- **Visible** : Visibilité de l’objet.
- **Masqué** : Indique si l’objet est masqué sur le Dashboard.
- **Résumé Défini** : Indique le nombre de commandes par résumé. Ce qui est en bleu est pris en compte dans le résumé global.
- **Résumé Dashboard Masqué** : Indique les résumés masqués sur le Dashboard.
- **Résumé Mobile Masqué** : Indique les résumés masqués sur le mobile.

## Mes objets

Une fois que vous avez créé un objet, il apparaîtra dans cette partie.

> **Tip**
>
> Vous pouvez ouvrir un objet en faisant :
> - Clic sur l'un d'entre eux.
> - Ctrl Clic ou Clic Centre pour l'ouvrir dans un nouvel onglet du navigateur.

Vous disposez d'un moteur de recherche permettant de filtrer l'affichage des objets. La touche Echap annule la recherche.
A droite du champ de recherche, trois boutons que l'on retrouve à plusieurs endroits de Jeedom:

- La croix pour annuler la recherche.
- Le dossier ouvert pour déplier tout les panneaux et afficher touts les objets.
- Le dossier fermé pour replier tout les panneaux.

Une fois sur la configuration d'un objet, vous disposez d'un menu contextuel au Clic Droit sur les onglets de l'objet. Vous pouvez également utiliser un Ctrl Clic ou Clic Centre pour ouvrir directement un autre objet dans un nouvel onglet du navigateur.

## Onglet Objet

En cliquant sur un objet, vous accédez à sa page de configuration. Quels que soient les changements effectués, n’oubliez pas de sauvegarder vos modifications.

Voici donc les différentes caractéristiques pour configurer un objet :

#### Paramètres :

- **Nom de l’objet** : Le nom de votre objet.
- **Objet parent** : Indique le parent de l’objet courant, cela permet de définir une hiérarchie entre les objets. Par exemple : Le salon a pour parent l’appartement. Un objet ne peut avoir qu’un seul parent mais plusieurs objets peuvent avoir le même parent.
- **Visible** : Cochez cette case pour rendre visible cet objet.
- **Masquer sur le Dashboard** : Cochez cette case pour masquer l’objet sur le Dashboard. Il est tout de même conservé dans la liste, ce qui permet de l’afficher, mais uniquement de manière explicite.
- **Masquer sur la synthèse** : Cochez cette case pour masquer l’objet sur la synthèse'. Il est tout de même conservé dans la liste, ce qui permet de l’afficher, mais uniquement de manière explicite.
- **Action depuis la synthèse** : Vous pouvez ici indiquer une vue ou un design sur lequel aller quand vous cliquez sur l'objet depuis la Synthèse. *Defaut : Dashboard*.

#### Affichage :

- **Icône** : Permet de choisir une icône pour votre objet.
- **Couleurs personnalisées** : Active la prise en compte des deux paramètres de couleurs personnalisées en dessous.
- **Couleur du tag** : Permet de choisir la couleur de l’objet et des équipements qui lui sont rattachés.
- **Couleur du texte du tag** : Permet de choisir la couleur du texte de l’objet. Ce texte sera par dessus la **couleur du tag**. A vous de choisir une couleur pour rendre le texte lisible.
- **Seulement sur la synthèse** : Permet de mettre une image pour la Synthèse sans qu'elle soit utilisée comme image de fond, notamment sur la page *Dashboard* de cet objet.
- **Image** : Vous avez la possibilité de télécharger une image ou la supprimer. Au format jpeg cette image sera l'image de fond de l'objet quand vous l'afficherez sur le Dashboard. Elle sera aussi utilisée pour la vignette de la pièce sur la Synthèse.

> **Tip**
>
> Vous pouvez modifier l’ordre d’affichage des objets dans le Dashboard. Dans la vue d'ensemble (ou par le Résumé Domotique), sélectionnez votre objet avec la souris avec un glisser/déposer pour lui donner une nouvelle place.

> **Tip**
>
> Vous pouvez voir un graphique représentant tous les éléments de Jeedom rattachés à cet objet en cliquant sur le bouton **Liens**, en haut à droite.

> **Tip**
>
> Quand un équipement est créé et qu’aucun parent n’a été défini, il aura comme parent : **Aucun**.

## Onglets Résumés

[Voir documentation sur les résumés.](/fr_FR/concept/summary)


