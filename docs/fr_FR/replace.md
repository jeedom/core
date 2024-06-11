 # Remplacer

## Pourquoi un tel outil ?

![1](../images/replace1.png)

Jeedom propose depuis la version 4.3.2 un nouvel outil <kbd>Remplacer</kbd> qui, en cas de problème ou de la nécessité de remplacer d’un équipement physique ou virtuel (un capteur de température, de présence, une commande de volume, un niveau d’eau,…), assurera la recopie de l’ensemble des commandes, informations, paramètres avancés et historique de cet équipement vers un nouvel équipement.<br>
Il se chargera également de remplacer l’ID de l’ancien équipement par le nouveau dans tous les scénarios, designs, virtuels, etc… qui y faisait référence.

En effet, si l’ancien équipement est supprimé, la référence à son numéro d'ID original sera définitivement effacée. Il faudra alors recréer toutes les commandes et les réintégrer dans l’ensemble des designs, widgets, etc… pour le nouveau module, et ce même si celui-ci est strictement de même type que l’original, voire le même mais avec un numéro d'ID différent.<br>
Aussi, avant toute suppression d’un équipement, Jeedom va prévenir des conséquences de cette suppression dans une fenêtre d’alerte :

![2](../images/replace2.png)

Ici, la suppression de ce capteur de vibration va provoquer :

- La suppression des affichages définis dans le design 'Alarmes zones',
- La suppression des informations vibration, niveau de batterie, et date de la dernière communication, y compris en ce qui concerne les historiques,
- La suppression de l’équipement dans le scénario ‘Alarme détection intru’.

Et à partir du moment où cet équipement sera définitivement supprimé, il sera remplacé dans toutes ces entités par son ancien numéro d’ID, ou un champ vide à la place de son appellation d’origine :

![3](../images/replace3.png)
<br><br>

## Les opérations à effectuer au préalable avant d’utiliser cet outil

Même si l’outil <kbd>Remplacer</kbd> va vous proposer d'effectuer une sauvegarde de précaution avant, il est fortement conseillé d’en faire une avant de commencer cette procédure de remplacement.<br>
Gardez à l’esprit que cet outil est en effet vraiment puissant dans la mesure où il va aller effectuer les remplacements à tous les niveaux, y compris sur ceux auxquels vous n’aviez pas pensé ou tout simplement oublié. De plus, il n’existe pas de fonction *undo* pour annuler ou revenir en arrière.<br><br>

La phase suivante va être le renommage de l’ancien équipement. Pour ce faire, il suffit de lui changer son nom, en ajoutant le suffixe '**_old**' par exemple.

![4](../images/replace4.png)
<br>

N’oubliez pas de sauvegarder.
<br>

Il faut ensuite effectuer l’inclusion du nouvel équipement s’il s’agit d’un équipement physique, ou la création du nouvel équipement virtuel, suivant la procédure standard propre à chaque plugin.
Cet équipement sera dénommé avec son nom définitif, puis l’objet parent et sa catégorie défini avant de l’activer. 
<br>
On obtient ainsi deux équipements :

- L’ancien équipement, qui n’existe peut-être plus physiquement, mais qui reste néanmoins référencé dans toutes les structures de Jeedom avec ses historiques,
- Et le nouvel équipement, sur lequel il s’agira de recopier les historiques et de le référencer à la place de l’ancien.
<br>

![5](../images/replace5.png)
<br><br>

## L’utilisation de l’outil <kbd>Remplacer</kbd>

Ouvrir l’outil <kbd>Remplacer</kbd>, dans le menu <kbd>Outils</kbd>.

![6](../images/replace6.png)
<br>

Dans le champ *Objet*, sélectionner le ou les objets parents.

![7](../images/replace7.png)
<br>

Dans les options, sélectionner le mode souhaité (*Remplacer* ou *Copier*) dans la liste déroulante, et suivant les besoins, les options suivantes (qui sont toutes décochées par défaut), soit au minimum :

- Copier la configuration de l’équipement source,
- Copier la configuration de la commande source.
<br>

![8](../images/replace8.png)
<br>

Puis cliquer sur <kbd>Filtrer</kbd>

![9](../images/replace9.png)
<br>

Dans le champ *Remplacements*, toutes les entités relatives à l’objet parent apparaissent :

![10](../images/replace10.png)
<br>

Cocher l’équipement source (renommé en '**_old**'), c’est à dire celui à partir duquel on souhaite recopier les commandes, informations, historique…
Ici, l’équipement source sera donc : [Chambre ami][T°Chambre_old] (767 | z2m).<br>
Cliquer sur la ligne pour faire apparaître les différents champs rattachés.

![11](../images/replace11.png)
<br>

Dans la partie *Cible* à droite, dérouler la liste et sélectionner le nouvel équipement qui va le remplacer, c’est à dire [Chambre ami][T° chambre] dans notre exemple.

![12](../images/replace12.png)
<br>

Dans les listes déroulantes qui s’affichent ensuite à droite, les informations sont présentées sur fond bleu, les actions sur fond orange (ci-dessous un autre exemple sur un luminaire où il y a des actions et des infos).

![13](../images/replace13.png)
<br>

Et s’il y a une correspondance directe (même nom en particulier), les différents paramètres seront définis automatiquement.

![14](../images/replace14.png)
<br>

Ici, tout est automatiquement reconnu.
Sinon, le champ sera vide, et il faudra sélectionner manuellement dans la liste déroulante l’information/action correspondante si pertinente.

![15](../images/replace15.png)
<br>

Cliquer sur <kbd>Remplacer</kbd>,

![16](../images/replace16.png)
<br>

Valider le remplacement, en vérifiant qu’une sauvegarde ait bien été faite auparavant (attention, il n’y a pas de retour en arrière possible !).

![17](../images/replace17.png)
<br>

D’ailleurs, l’outil vous le proposera à cette étape. Mais en quittant cette fonction pour effectuer cette sauvegarde à ce moment, vous abandonnerez également tous les paramétrages déjà réalisés, d’où l’intérêt de faire cette sauvegarde dès le début de la procédure.<br><br>

Après avoir lancé la commande, à l'issue d'une brève attente un pop-up d’alerte va apparaître et va indiquer le bon déroulement de la procédure.<br><br>

## Les vérifications

Assurez-vous que le nouvel équipement ait bien été pris en compte dans les designs, scénarios, widgets, virtuels, plugs-in, etc… avec sa configuration (disposition, affichage, affectation des widgets,…), et (le cas échéant) l’historique associé.

![18](../images/replace18.png)
<br>

Pour bien vérifier qu’aucun problème supplémentaire n’ait été généré suite à ce remplacement, il est possible d’utiliser la fonction de détection des commandes orphelines.
Aller sur <kbd>Analyse</kbd>, <kbd>Equipements</kbd>, cliquer sur l’onglet *Commandes orphelines*.

![19](../images/replace19.png)
<br>

![20](../images/replace20.png)
<br>

Si tout c’est bien passé, il ne doit y avoir aucune ligne présente dans ce compte rendu.
 
![21](../images/replace21.png)
<br>

Sinon, il faudra procéder à une analyse ligne par ligne pour chaque problème identifié pour y remédier.

![22](../images/replace22.png)
<br>

Mais si les commandes orphelines ne sont pas prises en compte par l’outil <kbd>Remplacer</kbd>, il est quand même possible d’opérer à des remplacements avec cette fonction <kbd>Cette commande remplace l’ID</kbd> qu’on retrouve ici dans la fenêtre de configuration de la commande :

![23](../images/replace23.png)
<br><br>

## Finalisation

Si tout est correct, l’ancien équipement (T°Chambre_old dans l’exemple) peut alors être supprimé définitivement. Plus aucune référence ne doit apparaître dans le pop-up d’avertissement lors de la suppression, excepté les commandes intrinsèques à cet équipement.

![24](../images/replace24.png)
<br>

Ici, cet équipement n’est plus référencé que par son objet d’appartenance et ses propres commandes, ce qui est normal. On peut donc le supprimer sans regrets.<br><br>

## Conclusion

Cet outil est pratique, mais il est tout aussi dangereux s’il est mal utilisé de part son implication multi-niveaux.<br>
Aussi, gardez bien à l'esprit ces fondamentaux :

- Effectuer systématiquement une sauvegarde de précaution, et ce avant même d'utiliser l'outil <kbd>Remplacer</kbd>,
- Il n’y a pas d’annulation ou de retour en arrière possible après l'exécution de cette commande,
- Et enfin, il est fortement conseillé de se familliariser à minima avec l'utilisation de cet outil.
