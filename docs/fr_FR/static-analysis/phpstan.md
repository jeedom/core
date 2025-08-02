# Guide PHPStan pour Jeedom

## Installation locale

1. Mettre à jour les dépendances :
```bash
composer update --ignore-platform-reqs
```

## Configuration

Le fichier `phpstan.neon` à la racine du projet contient la configuration suivante :

```yaml
parameters:
  level: 1
  paths:
    - core
    - desktop
    - install
    - mobile
  excludePaths:
    - vendor/*
  tmpDir: .phpstan.cache
  baseline: phpstan-baseline.neon
  reportUnmatchedIgnoredErrors: false

includes:
  - phpstan-baseline.neon
```

Notes importantes :
- Niveau d'analyse : 1 / 10 (0 = minimum, 10 = maximum)
- Le baseline permet d'ignorer les erreurs existantes
- Les erreurs corrigées sont automatiquement retirées des erreurs détectées

## Utilisation quotidienne

### Lancer l'analyse
```bash
vendor/bin/phpstan analyse --configuration phpstan.neon
```

### Types d'erreurs courantes et solutions

1. **Variable might not be defined** :
```php
// Erreur
function maFonction() {
    if($condition) {
        $variable = 'valeur';
    }
    echo $variable;  // Erreur : variable non définie si condition fausse
}

// Solution
function maFonction() {
    $variable = null;  // Initialisation par défaut
    if($condition) {
        $variable = 'valeur';
    }
    echo $variable;
}
```

2. **Method X not found in class Y** :
```php
// Erreur
$object->methodQuiNexistePas();

// Solution
// Vérifier si la méthode existe dans la classe
// Ou utiliser une interface/classe abstraite pour définir le contrat
```

3. **Cannot call method X on mixed** :
```php
// Erreur
$result = getData();  // getData() retourne mixed
$result->method();    // Erreur : impossible d'appeler une méthode sur mixed

// Solution
if (is_object($result)) {
    $result->method();
}
```

## Cas particuliers

### Ignorer une erreur spécifique
Si une erreur ne peut pas être corrigée ou doit être ignorée, ajoutez un commentaire PHPStan :
```php
/** @phpstan-ignore-next-line */
$resultat = codeProblematiqueQuiNeDoitPasEtreModifie();
```

### Générer un nouveau baseline
Si de nombreuses erreurs existantes doivent être ignorées :
```bash
php phpstan.phar analyse --configuration phpstan.neon --generate-baseline
```

## Intégration continue

### Vérification du code

Le workflow GitHub Actions vérifie automatiquement le code à chaque push et pull request sur la branche alpha. En cas d'échec :

1. Consultez les logs de l'action pour voir les erreurs
2. Reproduisez l'analyse en local
3. Corrigez les erreurs ou mettez à jour le baseline si nécessaire

### Mise à jour automatique du baseline

Un processus automatique a été mis en place pour maintenir le baseline à jour :

1. Après chaque merge sur alpha, le système vérifie si des erreurs du baseline peuvent être supprimées
2. Si des erreurs ont été corrigées et peuvent être retirées du baseline :
    - Une nouvelle branche `update-phpstan-baseline` est créée
    - Une Pull Request est automatiquement ouverte
    - La PR contient uniquement la mise à jour du fichier `phpstan-baseline.neon`
3. Cette PR peut être revue et mergée comme toute autre PR

👉 Note : Il n'est pas nécessaire de mettre à jour le baseline manuellement, le système automatique s'en charge lorsque des erreurs sont corrigées.

## Bonnes pratiques

- Exécutez PHPStan localement avant de commiter
- Corrigez les erreurs plutôt que de les ignorer quand c'est possible
- Pour les nouvelles classes/méthodes, essayez de ne pas générer de nouvelles erreurs
- Commentez le code clairement quand vous devez ignorer une erreur
- Laissez le système automatique gérer la mise à jour du baseline
- Revoyez les PRs de mise à jour du baseline pour vérifier que les erreurs retirées ont bien été corrigées intentionnellement

---

Besoin d'aide supplémentaire ? Consultez la [documentation officielle de PHPStan](https://phpstan.org/user-guide/getting-started).