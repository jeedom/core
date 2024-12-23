# Vérification du style de code K&R

Ce système permet de vérifier le respect du style K&R sur les modifications de code. Il peut être utilisé à la fois en local et via GitHub Actions.

## Utilisation en local

### Vérification du style

Pour vérifier les modifications non committées :
```bash
.github/scripts/check-style.sh file1.php file2.php
```

Pour vérifier tous les fichiers PHP modifiés :
```bash
.github/scripts/check-style.sh $(git diff --name-only | grep ".php$")
```

### Correction automatique

PHPCS permet de corriger automatiquement certaines erreurs de style. Pour cela :

1. Téléchargez phpcbf (PHP Code Beautifier) :
```bash
curl -OL https://squizlabs.github.io/PHP_CodeSniffer/phpcbf.phar
```

2. Lancez la correction sur vos fichiers :
```bash
php phpcbf.phar --standard=.github/phpcs/kr.xml file1.php file2.php
```

⚠️ **Important :** 
- Faites toujours une sauvegarde ou un commit avant d'utiliser la correction automatique
- Vérifiez les modifications après correction
- Certaines erreurs nécessitent une correction manuelle

### Installation en tant que hook pre-commit

Pour vérifier automatiquement le style avant chaque commit :

1. Créez le fichier `.git/hooks/pre-commit` :
```bash
#!/bin/bash

# Récupère les fichiers PHP modifiés
FILES=$(git diff --cached --name-only --diff-filter=ACM | grep ".php$" || true)

if [ -n "$FILES" ]; then
.github/scripts/check-style.sh $FILES
    if [ $? -ne 0 ]; then
        echo "❌ Erreurs de style détectées. Corrigez-les avant de commiter."
        exit 1
    fi
fi
```

2. Rendez-le exécutable :
```bash
chmod +x .git/hooks/pre-commit
```

## Style de code

Le style K&R vérifié inclut :
- Indentation de 4 espaces
- Accolades sur la même ligne pour les fonctions
- (autres règles spécifiques au projet)

Pour plus de détails, consultez le fichier `.github/phpcs/kr.xml`.

## GitHub Actions

Le workflow GitHub vérifie automatiquement le style des fichiers modifiés dans chaque Pull Request. Il ne vérifie que les lignes modifiées pour éviter les faux positifs sur le code existant.

## Résolution des problèmes courants

### Message d'erreur "Not a git repository"
- Assurez-vous d'être dans le répertoire du projet
- Vérifiez que le répertoire est bien un dépôt git

### Erreurs non corrigées par phpcbf
Certaines erreurs nécessitent une correction manuelle, notamment :
- L'organisation des méthodes dans la classe
- Les problèmes de nommage
- La structure générale du code

### Faux positifs 
Si vous rencontrez des faux positifs :
1. Vérifiez que vous utilisez la dernière version des scripts
2. Assurez-vous que le fichier n'est pas exclu dans `kr.xml`
