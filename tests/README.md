# Tests Jeedom Core

Ce document décrit les différentes suites de tests disponibles dans Jeedom Core et comment les exécuter.

## Installation préalable

Avant de pouvoir exécuter les tests, vous devez installer PHPUnit. Il y a deux méthodes possibles :

### Installation locale (recommandée)
Cette méthode installe PHPUnit uniquement pour ce projet :
```bash
# Dans le répertoire du projet
composer require --dev phpunit/phpunit ^9.5

# Vérifiez l'installation
./vendor/bin/phpunit --version
```

### Installation globale
Si vous préférez installer PHPUnit globalement sur votre système :
```bash
# Via Composer
composer global require phpunit/phpunit ^9.5

# Vérifiez que le dossier bin de Composer est dans votre PATH
echo 'export PATH="$PATH:$HOME/.composer/vendor/bin"' >> ~/.bashrc
source ~/.bashrc

# Vérifiez l'installation
phpunit --version
```

Note : Assurez-vous d'utiliser PHP 7.4 ou supérieur pour la compatibilité avec PHPUnit 9.5.

## Prérequis base de données

⚠️ **Important : Dépendance à la base de données**

En raison de l'architecture actuelle du Core Jeedom, les tests nécessitent un accès à une base de données MySQL/MariaDB fonctionnelle. Cette dépendance s'applique à toutes les suites de tests (Legacy, Unitaires et BC).

### Configuration requise
- Une base de données MySQL/MariaDB
- Un utilisateur avec les droits appropriés

### Configuration pour les tests
Les paramètres de connexion à la base de données pour les tests peuvent être configurés dans le fichier `.env.test.local` à la racine du projet. Ce fichier vous permet de définir des paramètres spécifiques pour l'environnement de test, distincts de votre environnement de production.

La configuration se fait via la variable `DATABASE_DSN` qui suit le format suivant :
```env
DATABASE_DSN=mysql://root:root@localhost:3306/jeedom_test
```

Détail des composants de l'URL de connexion :
- `mysql://` : Protocole de connexion (obligatoire, seul MySQL est supporté)
- `root` : Nom d'utilisateur de la base de données
- `:root` : Mot de passe de l'utilisateur (après le `:`)
- `@localhost` : Hôte de la base de données
- `:3306` : Port de connexion MySQL (3306 est le port par défaut)
- `/jeedom_test` : Nom de la base de données

Exemple avec des valeurs différentes :
```env
DATABASE_DSN=mysql://jeedom_user:password123@database.local:3306/jeedom_testing
```

### Note technique
Cette dépendance à la base de données n'est pas idéale pour des tests unitaires, qui devraient idéalement être isolés et indépendants de toute ressource externe. C'est une contrainte héritée de l'architecture historique qui sera potentiellement revue dans le futur.

## Vue d'ensemble

Le projet contient trois types de tests distincts :
- Tests Legacy (anciens tests)
- Tests Unitaires (nouveaux tests)
- Tests BC (tests de compatibilité ascendante)

## Tests Legacy

### Description
Ces tests sont les tests historiques du projet. Bien qu'ils soient destinés à être progressivement remplacés, ils fournissent actuellement une couverture de code acceptable et restent donc maintenus.

### Exécution
```bash
php vendor/bin/phpunit tests/Legacy
```

### Structure
Les tests legacy se trouvent dans le dossier `tests/Legacy` et suivent l'ancienne structure de tests. Ils couvrent principalement :
- Les fonctionnalités core
- Les interactions avec la base de données
- Les scénarios
- Les interactions entre objets

## Tests Unitaires

### Description
Il s'agit des nouveaux tests unitaires, écrits selon les bonnes pratiques modernes. Ils sont plus atomiques, plus maintenables et plus fiables que les tests legacy.

### Caractéristiques
- Tests isolés et indépendants
- Un seul concept testé par méthode
- Utilisation des mocks et des stubs quand nécessaire
- Meilleure organisation du code de test

### Exécution
```bash
php vendor/bin/phpunit tests/Unit
```

### Structure
Les tests unitaires sont organisés dans le dossier `tests/Unit` et suivent la structure du code source :
```
tests/Unit/
├── Core/
│   ├── ConfigTest.php
│   ├── CronTest.php
│   └── ...
├── Mock/
└── ...
```

## Tests BC (Backward Compatibility)

### Description
Ces tests sont conçus pour détecter les ruptures de compatibilité ascendante (BC breaks) dans l'API PHP. Ils sont particulièrement importants car ils permettent d'assurer que les modifications du Core ne casseront pas les plugins existants.

### Ce qui est vérifié
- Signatures des méthodes
- Types de retour
- Paramètres (nombre, type, ordre)
- Classes et interfaces publiques
- Constantes

### Exécution
```bash
php vendor/bin/phpunit tests/BC
```

### En cas d'échec
Si ces tests échouent, cela signifie qu'une modification pourrait potentiellement casser la compatibilité avec les plugins existants. Il faut alors :
1. Examiner les changements proposés
2. Évaluer l'impact sur les plugins
3. Soit modifier le changement pour maintenir la compatibilité
4. Soit documenter le changement comme une rupture de compatibilité majeure

### Mise à jour des contraintes BC
Le fichier `/tests/BC/api_file` contient la liste des contraintes de compatibilité ascendante qui doivent être respectées. Pour mettre à jour ce fichier après des modifications de l'API :

```bash
# Placez-vous à la racine du projet
cd /chemin/vers/jeedom/core

# Générez une nouvelle liste des contraintes BC
php tests/BC/generateBCList.php
```

⚠️ Important :
- Générez ce fichier uniquement sur une version stable du code
- Vérifiez attentivement les différences avant de commiter les changements
- La génération doit se faire sur la branche principale (beta ou master) pour servir de référence

## Exécution complète des tests

Pour lancer tous les tests d'un coup :
```bash
php vendor/bin/phpunit
```

## Configuration

Les configurations des tests se trouvent dans le fichier `phpunit.xml.dist`

## Contribution

Lors de l'ajout de nouvelles fonctionnalités :
1. Ajoutez de nouveaux tests unitaires dans `tests/Unit`
2. Si nécessaire, ajoutez des tests BC dans `tests/BC`
3. Ne modifiez les tests legacy que si absolument nécessaire

## Bonnes pratiques

- Écrivez les nouveaux tests dans la suite de tests unitaires
- N'ajoutez pas de nouveaux tests dans la suite legacy
- Vérifiez toujours la compatibilité ascendante avec les tests BC
- Documenter les cas de test complexes
- Utiliser des noms de méthodes descriptifs
