# Personnalisation Avancée
**Réglages → Système → Personnalisation avancée**

Cette page, réservée aux experts, permet d’ajouter à Jeedom des script CSS ou JS, qui seront exécutés sur chaque page.

Vous pouvez ainsi ajouter vos propres fonctions JS, et ajouter ou modifier des classes CSS.

Les deux parties, JS et CSS, sont différenciées selon l'affichage Desktop ou Mobile.

## Ressources

[CSS: Cascading Style Sheets](https://developer.mozilla.org/en-US/docs/Web/CSS)

[JavaScript](https://developer.mozilla.org/en-US/docs/Web/JavaScript)

[Astuces pour la personnalisation de l'interface](https://kiboost.github.io/jeedom_docs/jeedomV4Tips/Interface/)

## En cas de problème

Injecter du JS et/ou du CSS peut rendre Jeedom inopérant.

Dans ce cas, deux solutions:

- Ouvrez un navigateur en mode rescue : `IP/index.php?rescue=1`
- Se connecter en SSH et supprimer les fichiers de customisation : `desktop/custopn` et `mobile/custom`

