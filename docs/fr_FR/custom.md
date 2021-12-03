# Personnalisation Avancée
**Réglages → Système → Personnalisation avancée**

Vous pouvez ici gérer des fonctions **javascript** et des règles **CSS** appliqués en desktop ou en mobile.

> **Attention**
>
> L'utilisation de règles CSS inappropriées peu casser l'affichage de votre Jeedom. Des fonctions js non correctement utilisées peuvent créer des dommages important à différents composants de votre installation. Pensez à générer et externaliser une sauvegarde avant d'utiliser ces fonctions.

Cette fonction utilise un mode particulier de l'éditeur de fichier du Core avec deux emplacements:

- desktop / custom : Peut contenir les deux fichiers **custom.js** et **custom.css** qui seront chargés par le Core en version Desktop.
- mobile / custom : Peut contenir les deux fichiers **custom.js** et **custom.css** qui seront chargés par le Core en version Mobile.

Dans la barre de menu de l'éditeur de fichier du Core, un bouton **Activé** ou **Désactivé** vous indique si le Core doit les charger ou non. Cette option est également disponible dans **Réglages → Système → Configuration** onglet Interface.

> **Remarque**
>
> Au lancement de cette page, l'arborescence est crée automatiquement, ainsi que les 4 fichiers avec un commentaire en 1ère ligne comprenant la version du Core qui les a crée.

## Ressources

[CSS: Cascading Style Sheets](https://developer.mozilla.org/en-US/docs/Web/CSS)

[JavaScript](https://developer.mozilla.org/en-US/docs/Web/JavaScript)

[Astuces pour la personnalisation de l'interface](https://kiboost.github.io/jeedom_docs/jeedomV4Tips/Interface/)

## En cas de problème

Injecter du JS et/ou du CSS peut rendre Jeedom inopérant.

Dans ce cas, deux solutions:

- Ouvrez un navigateur en mode rescue : `IP/index.php?rescue=1`
- Se connecter en SSH et supprimer les fichiers de customisation : `desktop/custom` et `mobile/custom`

