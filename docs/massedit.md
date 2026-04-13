# Editeur en masse
**Réglages → Système → Configuration | OS/DB**

Cet outil permet d'éditer un grand nombre d'équipements, de commandes, d'objets, ou de scénarios. Il est totalement générique, et reprend automatiquement le schéma et la structure de la base de données de Jeedom. Il supporte ainsi les plugins et la configuration de leurs équipements.

> **Attention**
>
> Si cet outil s'avère assez simple d'utilisation, celui-ci s'adresse à des utilisateurs avancés. En effet, il est de fait très simple de changer n'importe quel paramètre sur des dizaines d'équipements ou centaines de commandes et donc de rendre inopérantes certaines fonctions, voir même le Core.

## Utilisation

La partie *Filtre* permet de sélectionner ce que vous souhaitez éditer, puis d'ajouter des filtres de sélection selon leurs paramètres. Un bouton de test permet, sans aucune modification, de vous montrer les items sélectionnés par les filtres renseignés.

La partie *Edition* permet de changer des paramètres sur ces items.

- **Colonne** : Paramètre.
- **Valeur** : La valeur du paramètre.
- **Valeur json** : La propriété du paramètre / valeur si celui-ci est de type json (clé->valeur).

### Exemples:

#### Renommer un groupe de scénarios

- Dans la partie *Filtre*, sélectionnez **Scénario**.
- Cliquez sur le bouton **+** pour ajouter un filtre.
- Dans ce filtre, sélectionnez la colonne *group*, et en valeur le nom du groupe à renommer.
- Cliquez sur le bouton *Test* pour afficher les scénarios de ce groupe.
- Dans la partie *Edition*, sélectionnez la colonne *group*, puis mettez le nom que vous souhaitez dans la valeur.
- Cliquez sur **Exécuter** en haut à droite.

#### Rendre invisible tous les équipements d'un objet/pièce:

- Dans la partie *Filtre*, sélectionnez **Equipement**.
- Cliquez sur le bouton **+** pour ajouter un filtre.
- Dans ce filtre, sélectionnez la colonne *object_id*, et en valeur l'id de l'objet en question (visible depuis Outils/Objets, Vue d'ensemble).
- Cliquez sur le bouton *Test* pour afficher les scénarios de ce groupe.
- Dans la partie *Edition*, sélectionnez la colonne *isvisible*, puis entrez la valeur 0.
- Cliquez sur **Exécuter** en haut à droite.