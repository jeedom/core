# Tests unitaires avec PHPUnit

## Installation

### Pré-requis

- PHP 7.4 ou supérieur
- MySQL/MariaDB configuré et fonctionnel
- Composer installé

### 1. Installer les dépendances

```bash
composer install
```

### 2. Configuration

Copier le fichier de configuration d'exemple et l'adapter :

```bash
cp core/config/common.config.sample.php core/config/common.config.php
```

Modifier le fichier `core/config/common.config.php` avec vos paramètres de base de données :

```php
<?php
define('DEBUG', 1);

global $CONFIG;
$CONFIG = [
    'db' => [
        'host' => '127.0.0.1',
        'port' => '3306',
        'dbname' => 'jeedom',          // Le bootstrap ajoutera '_test'
        'username' => 'root',
        'password' => 'root',
        'root_user' => 'my_root_user',               // À ajouter si besoin, par défaut 'root'
        'root_password' => 'my_root_password',       // À ajouter si besoin, par défaut pas de mot de passe
    ],
];
```

### 3. Configuration du cache (optionnel)

Pour éviter les problèmes de permissions avec le FileCache, créer le répertoire de cache :

```bash
sudo mkdir -p /tmp/jeedom/cache
sudo chmod 774 /tmp/jeedom
sudo chmod 774 /tmp/jeedom/cache
sudo chown -R $USER:$USER /tmp/jeedom
```

**Note :** Le fichier `tests/bootstrap.php` se charge automatiquement de :
- Ajouter le suffixe `_test` au nom de la base de données
- Créer/recréer la base de données de test
- Initialiser le schéma de base de données
- Créer un utilisateur admin par défaut

## Utilisation

### Exécuter tous les tests

```bash
# Exécuter tous les tests
vendor/bin/phpunit

# Ou avec plus de détails
vendor/bin/phpunit --coverage-text --colors=never
```

### Exécuter une suite de tests spécifique

```bash
# Exécuter seulement les tests legacy
vendor/bin/phpunit --testsuite "Legacy tests"
```

### Exécuter un test spécifique

```bash
# Exécuter un fichier de test particulier
vendor/bin/phpunit tests/configTest.php

# Exécuter une méthode de test spécifique
vendor/bin/phpunit tests/configTest.php::testMethod
```

### Exécuter les tests d'un dossier

```bash
# Tests des classes
vendor/bin/phpunit tests/class/

# Tests des utilitaires PHP
vendor/bin/phpunit tests/php/
```

## Structure des tests

Les tests sont organisés comme suit :

```
tests/
├── bootstrap.php          # Configuration d'amorçage des tests
├── cacheTest.php          # Tests du système de cache
├── configTest.php         # Tests de configuration
├── cronTest.php           # Tests des tâches cron
├── logTest.php            # Tests des logs
├── pluginTest.php         # Tests des plugins
├── scenarioExpressionTest.php # Tests des expressions de scénarios
├── userTest.php           # Tests des utilisateurs
├── class/                 # Tests des classes principales
│   ├── ajaxTest.php
│   └── scenarioTest.php
├── com/                   # Tests des communications
│   └── shellTest.php
└── php/                   # Tests des utilitaires PHP
    └── utilsTest.php
```

## Configuration PHPUnit

Le fichier `phpunit.xml.dist` contient la configuration des tests :

```xml
<phpunit bootstrap="./tests/bootstrap.php" colors="true">
    <testsuites>
        <testsuite name="Legacy tests">
            <directory suffix="Test.php">./tests</directory>
        </testsuite>
    </testsuites>
    
    <coverage processUncoveredFiles="true">
        <include>
            <directory suffix=".php">core/class</directory>
        </include>
    </coverage>
</phpunit>
```

## Conseils de développement

### Créer un nouveau test

1. Créer un fichier `*Test.php` dans le dossier approprié
2. Étendre la classe `PHPUnit\Framework\TestCase`
3. Nommer les méthodes de test avec le préfixe `test`

Exemple :

```php
<?php

use PHPUnit\Framework\TestCase;

class MonNouveauTest extends TestCase
{
    public function testMonTest()
    {
        $this->assertTrue(true);
    }
}
```

### Debugging

Pour déboguer vos tests :

```bash
# Afficher plus de détails
vendor/bin/phpunit --verbose

# Arrêter au premier échec
vendor/bin/phpunit --stop-on-failure
```

### Couverture de code

Pour générer un rapport de couverture de code :

```bash
# Rapport texte
vendor/bin/phpunit --coverage-text

# Rapport HTML (nécessite l'extension xdebug ou pcov)
vendor/bin/phpunit --coverage-html coverage/
```

## GitHub Actions

Les tests sont automatiquement exécutés via GitHub Actions sur :
- Push sur toutes les branches
- Pull requests vers `master`, `beta`, et `alpha`
- Matrice de tests : PHP 7.4 et 8.2

Le workflow installe les dépendances composer automatiquement, initialise la configuration et configure les répertoires de cache nécessaires.

## Informations techniques

### Bootstrap des tests

Le fichier `tests/bootstrap.php` effectue automatiquement :

1. **Configuration de la base de données** :
   - Ajout du suffixe `_test` au nom de la base
   - Suppression/recréation de la base de test
   - Attribution des privilèges

2. **Initialisation du schéma** :
   - Exécution du script `install/database.php`
   - Création des tables nécessaires

3. **Utilisateur par défaut** :
   - Création d'un utilisateur admin (login: `admin`, mot de passe: `admin`)

4. **Configuration système** :
   - Génération d'une clé API
   - Chargement des classes core

### Système de cache

Jeedom utilise un système de cache FileCache qui stocke les données dans `/tmp/jeedom/cache`. Si vous rencontrez des erreurs liées au cache, assurez-vous que :

- Le répertoire `/tmp/jeedom/cache` existe
- L'utilisateur a les permissions d'écriture (774 recommandé)
- Le répertoire parent `/tmp/jeedom` est accessible

### Résolution des problèmes courants

**Erreur de connexion à la base de données :**
- Vérifiez que MySQL/MariaDB est démarré
- Vérifiez les paramètres dans `core/config/common.config.php`
- Vérifiez que l’utilisateur du SGBD a les droits de création/suppression de base

**Erreur de cache :**
- Créez le répertoire de cache avec les bonnes permissions (voir section 4)

**Tests qui échouent :**
- Exécutez avec `--verbose` pour plus de détails
- Vérifiez les logs dans le répertoire `log/`