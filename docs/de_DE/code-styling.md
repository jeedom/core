# K&R Code-Style-Überprüfung

Dieses System ermöglicht die Überprüfung der K&R-Stilkonformität bei Codeänderungen. Es kann sowohl lokal als auch über GitHub Actions verwendet werden.

## Lokale Verwendung

### Style-Überprüfung

Um nicht committete Änderungen zu überprüfen:
```bash
.github/scripts/check-style.sh file1.php file2.php
```

Um alle geänderten PHP-Dateien zu überprüfen:
```bash
.github/scripts/check-style.sh $(git diff --name-only | grep ".php$")
```

### Automatische Korrektur

PHPCS ermöglicht die automatische Korrektur bestimmter Stilfehler:

1. Laden Sie phpcbf (PHP Code Beautifier) herunter:
```bash
curl -OL https://squizlabs.github.io/PHP_CodeSniffer/phpcbf.phar
```

2. Führen Sie die Korrektur für Ihre Dateien aus:
```bash
php phpcbf.phar --standard=.github/phpcs/kr.xml file1.php file2.php
```

⚠️ **Wichtig:**
- Erstellen Sie immer ein Backup oder Commit vor der automatischen Korrektur
- Überprüfen Sie die Änderungen nach der Korrektur
- Einige Fehler erfordern manuelle Korrektur

### Installation als Pre-Commit Hook

Um den Stil vor jedem Commit automatisch zu überprüfen:

1. Erstellen Sie die Datei `.git/hooks/pre-commit`:
```bash
#!/bin/bash

# PHP-Dateien abrufen, die geändert wurden
FILES=$(git diff --cached --name-only --diff-filter=ACM | grep ".php$" || true)

if [ -n "$FILES" ]; then
    .github/scripts/check-style.sh $FILES
    if [ $? -ne 0 ]; then
        echo "❌ Stilfehler erkannt. Bitte vor dem Commit korrigieren."
        exit 1
    fi
fi
```

2. Machen Sie sie ausführbar:
```bash
chmod +x .git/hooks/pre-commit
```

## Code-Stil

Der überprüfte K&R-Stil umfasst:
- 4 Leerzeichen Einrückung
- Geschweifte Klammern in der gleichen Zeile für Funktionen
- (weitere projektspezifische Regeln)

Weitere Details finden Sie in der Datei `.github/phpcs/kr.xml`.

## GitHub Actions

Der GitHub-Workflow überprüft automatisch den Stil der in jedem Pull Request geänderten Dateien. Es werden nur die geänderten Zeilen überprüft, um False Positives im bestehenden Code zu vermeiden.

## Häufige Probleme und Lösungen

### Fehlermeldung "Not a git repository"
- Stellen Sie sicher, dass Sie sich im Projektverzeichnis befinden
- Überprüfen Sie, ob das Verzeichnis ein Git-Repository ist

### Fehler, die nicht von phpcbf korrigiert werden
Einige Fehler erfordern manuelle Korrektur, insbesondere:
- Die Organisation von Methoden in der Klasse
- Benennungsprobleme
- Die allgemeine Codestruktur

### False Positives
Bei False Positives:
1. Überprüfen Sie, ob Sie die neueste Version der Skripte verwenden
2. Stellen Sie sicher, dass die Datei nicht in `kr.xml` ausgeschlossen ist
