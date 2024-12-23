# K&R Code Style Checker

This system allows checking K&R style compliance on code modifications. It can be used both locally and through GitHub Actions.

## Local Usage

### Style Checking

To check uncommitted changes:
```bash
.github/scripts/check-style.sh file1.php file2.php
```

To check all modified PHP files:
```bash
.github/scripts/check-style.sh $(git diff --name-only | grep ".php$")
```

### Automatic Fixing

PHPCS allows automatic fixing of certain style errors:

1. Download phpcbf (PHP Code Beautifier):
```bash
curl -OL https://squizlabs.github.io/PHP_CodeSniffer/phpcbf.phar
```

2. Run the fix on your files:
```bash
php phpcbf.phar --standard=.github/phpcs/kr.xml file1.php file2.php
```

⚠️ **Important:**
- Always make a backup or commit before using automatic fixing
- Review changes after fixing
- Some errors require manual fixing

### Installing as Pre-Commit Hook

To automatically check style before each commit:

1. Create the `.git/hooks/pre-commit` file:
```bash
#!/bin/bash

# Get modified PHP files
FILES=$(git diff --cached --name-only --diff-filter=ACM | grep ".php$" || true)

if [ -n "$FILES" ]; then
    .github/scripts/check-style.sh $FILES
    if [ $? -ne 0 ]; then
        echo "❌ Style errors detected. Please fix before committing."
        exit 1
    fi
fi
```

2. Make it executable:
```bash
chmod +x .git/hooks/pre-commit
```

## Code Style

The checked K&R style includes:
- 4 spaces indentation
- Curly braces on the same line for functions
- (other project-specific rules)

For more details, check the `.github/phpcs/kr.xml` file.

## GitHub Actions

The GitHub workflow automatically checks the style of files modified in each Pull Request. It only checks modified lines to avoid false positives on existing code.

## Common Issues and Solutions

### "Not a git repository" error message
- Make sure you're in the project directory
- Verify that the directory is a git repository

### Errors not fixed by phpcbf
Some errors require manual fixing, particularly:
- Method organization in the class
- Naming issues
- Overall code structure

### False Positives
If you encounter false positives:
1. Check that you're using the latest version of the scripts
2. Ensure the file isn't excluded in `kr.xml`
