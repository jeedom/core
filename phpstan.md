<p align="center">
  🇬🇧 <strong>English</strong> | 🇫🇷 <a href="phpstan.fr.md">Français</a>
</p>

# PHPStan Guide for Jeedom

## Local installation

Update the dependencies:
```bash
composer update --ignore-platform-reqs
```

## Configuration

The `phpstan.neon` file at the root of the project contains the following configuration:

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

Important notes:
- Analysis level: 1 / 10 (0 = minimum, 10 = maximum)
- The baseline allows ignoring existing errors
- Fixed errors are automatically removed from the detected errors

## Daily usage

### Running the analysis
```bash
vendor/bin/phpstan analyse --configuration phpstan.neon
```

### Common error types and solutions

1. **Variable might not be defined**:
```php
// Error
function myFunction() {
    if ($condition) {
        $variable = 'value';
    }
    echo $variable;  // Error: undefined variable if condition is false
}

// Solution
function myFunction() {
    $variable = null;  // Default initialization
    if ($condition) {
        $variable = 'value';
    }
    echo $variable;
}
```

2. **Method X not found in class Y**:
```php
// Error
$object->methodThatDoesNotExist();

// Solution
// Check whether the method exists in the class
// Or use an interface/abstract class to define the contract
```

3. **Cannot call method X on mixed**:
```php
// Error
$result = getData();  // getData() returns mixed
$result->method();    // Error: cannot call a method on mixed

// Solution
if (is_object($result)) {
    $result->method();
}
```

## Special cases

### Ignoring a specific error
If an error cannot be fixed or must be ignored, add a PHPStan comment:
```php
/** @phpstan-ignore-next-line */
$result = problematicCodeThatMustNotBeModified();
```

### Generating a new baseline
If many existing errors need to be ignored:
```bash
vendor/bin/phpstan analyse --configuration phpstan.neon --generate-baseline
```

## Continuous integration

### Code verification

The GitHub Actions workflow automatically checks the code on every push and pull request on the alpha branch. In case of failure:

1. Check the action logs to see the errors
2. Reproduce the analysis locally
3. Fix the errors or update the baseline if needed

### Automatic baseline update

An automatic process has been set up to keep the baseline up to date:

1. After each merge on alpha, the system checks whether any baseline errors can be removed
2. If errors have been fixed and can be removed from the baseline:
    - A new `update-phpstan-baseline` branch is created
    - A pull request is automatically opened
    - The PR contains only the update to the `phpstan-baseline.neon` file
3. This PR can be reviewed and merged like any other PR

👉 Note: There is no need to update the baseline manually, the automatic system takes care of it when errors are fixed.

## Best practices

- Run PHPStan locally before committing
- Fix errors rather than ignoring them when possible
- For new classes/methods, try not to introduce new errors
- Comment the code clearly when you have to ignore an error
- Let the automatic system handle the baseline update
- Review baseline update PRs to check that the removed errors were indeed fixed intentionally

---

Need more help? Check out the [official PHPStan documentation](https://phpstan.org/user-guide/getting-started).
