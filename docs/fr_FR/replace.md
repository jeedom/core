 # Remplacer

## Pourquoi un tel outil ?

![e99379df60ec9621d056709131876267787dec9f](https://github.com/jeedom/documentations/assets/38414898/7dcab025-acfb-44cb-8811-f63d2f5eca78)

Jeedom propose depuis la version 4.3.2 un nouvel outil <kbd>Remplacer</kbd> qui, en cas de problème ou de la nécessité de remplacer d’un équipement physique ou virtuel (un capteur de température, de présence, une commande de volume, un niveau d’eau,…), assurera la recopie de l’ensemble des commandes, informations, paramètres avancés et historique de cet équipement vers un nouvel équipement.<br>
Il se chargera également de remplacer l’ID de l’ancien équipement par le nouveau dans tous les scénarios, designs, virtuels, etc… qui y faisait référence.

En effet, si l’ancien équipement est supprimé, la référence à son numéro d'ID original sera définitivement effacée. Il faudra alors recréer toutes les commandes et les réintégrer dans l’ensemble des designs, widgets, etc… pour le nouveau module, et ce même si celui-ci est strictement de même type que l’original, voire le même mais avec un numéro d'ID différent.<br>
Aussi, avant toute suppression d’un équipement, Jeedom va prévenir des conséquences de cette suppression dans une fenêtre d’alerte :

![3082df630883423e63842193b1b9d164b55391b7](https://github.com/jeedom/documentations/assets/38414898/b1f9401c-6cc2-4c7b-9ce6-65bc9286b090)

Ici, la suppression de ce capteur de vibration va provoquer :

- La suppression des affichages définis dans le design 'Alarmes zones',
- La suppression des informations vibration, niveau de batterie, et date de la dernière communication, y compris en ce qui concerne les historiques,
- La suppression de l’équipement dans le scénario ‘Alarme détection intru’.

Et à partir du moment où cet équipement sera définitivement supprimé, il sera remplacé dans toutes ces entités par son ancien numéro d’ID, ou un champ vide à la place de son appellation d’origine :

![e76c8176183bab6d71d687fce567f113768fe85b_2_690x112](https://github.com/jeedom/documentations/assets/38414898/6b54d7b4-1de0-47e1-acd5-adfe7b6f0f61)
<br><br>

## Les opérations à effectuer au préalable avant d’utiliser cet outil

Même si l’outil <kbd>Remplacer</kbd> va vous proposer d'effectuer une sauvegarde de précaution avant, il est fortement conseillé d’en faire une avant de commencer cette procédure de remplacement.<br>
Gardez à l’esprit que cet outil est en effet vraiment puissant dans la mesure où il va aller effectuer les remplacements à tous les niveaux, y compris sur ceux auxquels vous n’aviez pas pensé ou tout simplement oublié. De plus, il n’existe pas de fonction *undo* pour annuler ou revenir en arrière.<br><br>

La phase suivante va être le renommage de l’ancien équipement. Pour ce faire, il suffit de lui changer son nom, en ajoutant le suffixe '**_old**' par exemple.

![f07b8c652fb3e9cf60fe7473e2d2979a5ea5503b_2_690x214](https://github.com/jeedom/documentations/assets/38414898/bd3963ea-8ca5-4c68-99aa-cd5598cab904)
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

![9b658a334fbdb62873261129ace3753094e67ee6_2_690x285](https://github.com/jeedom/documentations/assets/38414898/2965f6cc-40da-48d0-848c-97582de94729)
<br><br>

## L’utilisation de l’outil <kbd>Remplacer</kbd>

Ouvrir l’outil <kbd>Remplacer</kbd>, dans le menu <kbd>Outils</kbd>.

![e99379df60ec9621d056709131876267787dec9f](https://github.com/jeedom/documentations/assets/38414898/25b57bb9-f13c-4df4-b02c-2c7f1dca1db6)
<br>

Dans le champ *Objet*, sélectionner le ou les objets parents.

![b0a824e8f4817f0307e39aed26a2247a637c209f-1](https://github.com/jeedom/documentations/assets/38414898/544601ba-648f-46b9-a5e9-6222a4e8b752)
<br>

Dans les options, sélectionner le mode souhaité (*Remplacer* ou *Copier*) dans la liste déroulante, et suivant les besoins, les options suivantes (qui sont toutes décochées par défaut), soit au minimum :

- Copier la configuration de l’équipement source,
- Copier la configuration de la commande source.
<br>

![fbc07d32693cb32c659bd3cda978cbd12c53791c](https://github.com/jeedom/documentations/assets/38414898/b73ad64f-cace-4bb1-b61d-d77467c292b7)
<br>

Puis cliquer sur <kbd>Filtrer</kbd>

![e69caae400b7dac6063bac5e60c06757d0ec08b8](https://github.com/jeedom/documentations/assets/38414898/3f7df53c-61d0-4e5c-9517-382d8d09aad5)
<br>

Dans le champ *Remplacements*, toutes les entités relatives à l’objet parent apparaissent :

![310ce87669421ff3e72fa72bca522654ca41d486_2_690x130](https://github.com/jeedom/documentations/assets/38414898/161a9133-6c65-40e2-95e6-8e49ae48c10c)
<br>

Cocher l’équipement source (renommé en '**_old**'), c’est à dire celui à partir duquel on souhaite recopier les commandes, informations, historique…
Ici, l’équipement source sera donc : [Chambre ami][T°Chambre_old] (767 | z2m).<br>
Cliquer sur la ligne pour faire apparaître les différents champs rattachés.

![b652b8d76127f308459c2befdd5470b37133c6d6_2_690x90](https://github.com/jeedom/documentations/assets/38414898/21c69a07-42a5-4a63-8b7d-10af769cf0e8)
<br>

Dans la partie *Cible* à droite, dérouler la liste et sélectionner le nouvel équipement qui va le remplacer, c’est à dire [Chambre ami][T° chambre] dans notre exemple.

![be23ed8a466bd822aac66e31ee2af2c7a9c586e7](https://github.com/jeedom/documentations/assets/38414898/86365787-8cce-4793-8061-5953d2cdcd48)
<br>

Dans les listes déroulantes qui s’affichent ensuite à droite, les informations sont présentées sur fond bleu, les actions sur fond orange (ci-dessous un autre exemple sur un luminaire où il y a des actions et des infos).

![2117cf73f0c1726dac8907bad80fbf1d4b453d8f_2_690x182](https://github.com/jeedom/documentations/assets/38414898/c5582fa9-ab7e-4fcc-b1fa-6165dc0500ec)
<br>

Et s’il y a une correspondance directe (même nom en particulier), les différents paramètres seront définis automatiquement.

![fb5b9473af53ad8c7368ee06f7842bed61706883_2_690x203](https://github.com/jeedom/documentations/assets/38414898/b29665e7-8f24-46ef-a6f6-261289e3a792)
<br>

Ici, tout est automatiquement reconnu.
Sinon, le champ sera vide, et il faudra sélectionner manuellement dans la liste déroulante l’information/action correspondante si pertinente.

![5839e940665761b2de07ddfe86ef9599337cc82f_2_690x379](https://github.com/jeedom/documentations/assets/38414898/e91f5326-0c2e-41f4-a725-3862fbcfd527)
<br>

Cliquer sur <kbd>Remplacer</kbd>,

![69d71feae2b7a97afa93fe4ae748aaf27aa9cf32](https://github.com/jeedom/documentations/assets/38414898/4f6f1801-1a68-46ac-9a07-b42a657b6bae)
<br>

Valider le remplacement, en vérifiant qu’une sauvegarde ait bien été faite auparavant (attention, il n’y a pas de retour en arrière possible !).

![f956ebc278f049924f6410a2436048bda0083cdd-1](https://github.com/jeedom/documentations/assets/38414898/dfe82fe4-520a-49d3-9764-56d18d090692)
<br>

D’ailleurs, l’outil vous le proposera à cette étape. Mais en quittant cette fonction pour effectuer cette sauvegarde à ce moment, vous abandonnerez également tous les paramétrages déjà réalisés, d’où l’intérêt de faire cette sauvegarde dès le début de la procédure.<br><br>

Après avoir lancé la commande, à l'issue d'une brève attente un pop-up d’alerte va apparaître et va indiquer le bon déroulement de la procédure.<br><br>

## Les vérifications

Assurez-vous que le nouvel équipement ait bien été pris en compte dans les designs, scénarios, widgets, virtuels, plugs-in, etc… avec sa configuration (disposition, affichage, affectation des widgets,…), et (le cas échéant) l’historique associé.

![b4a11e717e2cbdd3b60e08ffdc390573db9a3356](https://github.com/jeedom/documentations/assets/38414898/82cd89df-6588-4e47-9e18-0bcbb5d1a2fd)
<br>

Pour bien vérifier qu’aucun problème supplémentaire n’ait été généré suite à ce remplacement, il est possible d’utiliser la fonction de détection des commandes orphelines.
Aller sur <kbd>Analyse</kbd>, <kbd>Equipements</kbd>, cliquer sur l’onglet *Commandes orphelines*.

![c44fa7da468e639e5ec6ec71a0eecfd2f3f71b71](https://github.com/jeedom/documentations/assets/38414898/ad4e36c9-411f-4525-8d37-50c619972bdf)
<br>

![39be66971e0d4294ae12d412b707aee996848f02](https://github.com/jeedom/documentations/assets/38414898/e5ea2db5-f713-4f16-ab79-b1ed77a5478b)
<br>

Si tout c’est bien passé, il ne doit y avoir aucune ligne présente dans ce compte rendu.
 
![9cfa83ec1c5479f31d09737ad008c594756a7bee_2_690x98](https://github.com/jeedom/documentations/assets/38414898/632f2fef-5f28-4cdc-8632-de67bd6e78c6)
<br>

Sinon, il faudra procéder à une analyse ligne par ligne pour chaque problème identifié pour y remédier.

![b9ba899f3cde1050351fa2b68cdc412572b811f6_2_690x102](https://github.com/jeedom/documentations/assets/38414898/0a3fe23c-b8b0-4b90-85a7-4abe3cbe431f)
<br>

Mais si les commandes orphelines ne sont pas prises en compte par l’outil <kbd>Remplacer</kbd>, il est quand même possible d’opérer à des remplacements avec cette fonction <kbd>Cette commande remplace l’ID</kbd> qu’on retrouve ici dans la fenêtre de configuration de la commande :

![12cccf29fe731ccbbd96cd7770f8e9ba9bcd0c06_2_690x376](https://github.com/jeedom/documentations/assets/38414898/ec1c83cc-b14f-472b-bcdb-3227da2c6520)
<br><br>

## Finalisation

Si tout est correct, l’ancien équipement (T°Chambre_old dans l’exemple) peut alors être supprimé définitivement. Plus aucune référence ne doit apparaître dans le pop-up d’avertissement lors de la suppression, excepté les commandes intrinsèques à cet équipement.

![3a5f69586df466f7902d46a8e3342731af668439_2_690x245](https://github.com/jeedom/documentations/assets/38414898/893d5244-2848-44aa-b5ab-f99e3755c3c0)
<br>

Ici, cet équipement n’est plus référencé que par son objet d’appartenance et ses propres commandes, ce qui est normal. On peut donc le supprimer sans regrets.<br><br>

## Conclusion

Cet outil est pratique, mais il est tout aussi dangereux s’il est mal utilisé de part son implication multi-bniveaux.<br>
Aussi, gardez bien à l'esprit ces fondamentaux :

- Effectuer systématiquement une sauvegarde de précaution, et ce avant même d'utiliser l'outil <kbd>Remplacer</kbd>,
- Il n’y a pas d’annulation ou de retour en arrière possible après l'exécution de cette commande,
- Et enfin, il est fortement conseillé de se familliariser à minima avec l'utilisation de cet outil.
