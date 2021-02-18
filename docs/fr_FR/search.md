# Recherche
**Menu Analyse → Recherche**

Jeedom propose un moteur de recherche interne.

![Recherche](./images/search_intro.gif)

Vous pouvez faire des recherches de différents types :

## Par Équipement

Sélectionnez un équipement avec l'icône à droite du champ.

Le moteur affichera dans les tableaux en dessous :

- Les **scénarios** utilisant cet équipement.
- Les **designs** affichant cet équipement.
- Les **vues** affichant cet équipement.
- Les **interactions** utilisant cet équipement.
- Les autres **équipement** utilisant cet équipement.
- Les **commandes** utilisant cet équipement.

## Par Commande

Sélectionnez une commande avec l'icône à droite du champ.

Le moteur affichera dans les tableaux en dessous :

- Les **scénarios** utilisant cette commande.
- Les **designs** affichant cette commande.
- Les **vues** affichant cette commande.
- Les **interactions** utilisant cette commande.
- Les **équipement** utilisant cette commande.
- Les autres **commandes** utilisant cette commande.

## Par Variable

Sélectionnez une variable dans la liste déroulante.

Le moteur affichera dans les tableaux en dessous :

- Les **scénarios** utilisant cette variable.
- Les **interactions** utilisant cette variable.
- Les **équipement** utilisant cette variable.
- Les **commandes** utilisant cette variable.

## Par Plugin

Sélectionnez un plugin dans la liste déroulante.

Le moteur affichera dans les tableaux en dessous :

- Les **scénarios** utilisant ce plugin.
- Les **designs** affichant ce plugin.
- Les **vues** affichant ce plugin.
- Les **interactions** utilisant ce plugin.
- Les **équipement** utilisant ce plugin.
- Les **commandes** utilisant ce plugin.

## Par Mot

Entrez une chaîne de caractères dans le champ de recherche. Valider avec *enter* ou avec le bouton *Rechercher*.

Le moteur affichera dans les tableaux en dessous :

- Les **scénarios** utilisant cette chaîne.
	Recherche dans les expressions, les commentaires, les blocs code.
- Les **interactions** utilisant cette chaîne.
	Recherche dans les champs *Demande*.
- Les **équipement** utilisant cette chaîne.
	Recherche dans les champs *name*, *logicalId*, *eqType*, *comment*, *tags*.
- Les **commandes** utilisant cette chaîne.
	Recherche dans les champs *name*, *logicalId*, *eqType*, *generic_type*, .
- Les **notes** utilisant cette chaîne.
	Recherche dans le texte des notes.

## Par ID

Entrez un nombre correspondant à un Id recherché dans le champ de recherche. Valider avec *enter* ou avec le bouton *Rechercher*.

Le moteur affichera dans les tableaux en dessous :

- Le **scénario** ayant cet Id.
- Le **design** ayant cet Id.
- La **vue** ayant cet Id.
- L'**interaction** ayant cet Id.
- L'**équipement** ayant cet Id.
- La **commande** ayant cet Id.
- La **note** ayant cet Id.

## Résultats

Pour chacun des types de résultat, il permet des actions:
- **scénarios** : Ouvrir le log du scénario, ou se rendre sur la page du scénario, avec la recherche active sur le terme recherché.
- **designs** : Afficher le design.
- **vues** : Afficher la vue.
- **interactions** : Ouvrir la page de configuration de l'interaction.
- **équipement** : Ouvrir la page de configuration de l'équipement.
- **commandes** : Ouvrir la configuration de la commande.
- **notes** : Ouvrir la Note.

Chacune de ces options ouvre un autre onglet de votre navigateur afin de ne pas perdre la recherche en cours.

