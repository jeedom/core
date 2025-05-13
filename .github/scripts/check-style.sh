#!/bin/bash

# Script de vérification du style de code K&R
# Vérifie uniquement les lignes modifiées dans les fichiers fournis
# Peut être utilisé en local (utilise git diff HEAD) ou dans GitHub Actions (utilise les diffs pré-générés)

# Vérification des arguments
if [ "$#" -lt 1 ]; then
    echo "Usage: $0 <fichiers_php>"
    exit 1
fi

# Initialisation des variables
has_errors=0
work_dir=$(pwd)

# Télécharge PHPCS si nécessaire
if [ ! -f "phpcs.phar" ]; then
    echo "Téléchargement de phpcs.phar..."
    curl -OL https://squizlabs.github.io/PHP_CodeSniffer/phpcs.phar
fi

# Traite chaque fichier PHP fourni
for file in "$@"; do
    if [ ! -f "$file" ]; then
        echo "Le fichier $file n'existe pas"
        continue
    fi

    # Extraction des numéros de lignes modifiées
    echo "Analyse des modifications de $file..."

    # Utilise soit le fichier diff pré-généré (GitHub Actions)
    # soit git diff en local
    if [ -n "$DIFF_DIR" ] && [ -f "$DIFF_DIR/$(basename "$file").diff" ]; then
        DIFF_FILE="$DIFF_DIR/$(basename "$file").diff"
        cat "$DIFF_FILE"
    else
        git diff --unified=0 HEAD -- "$file"
    fi | grep -E "^@@.*\+" | grep -Eo "\+[0-9]+(,[0-9]+)?" | cut -c2- | while read -r line; do
        # Gestion des plages de lignes (format: début,longueur)
        if [[ $line == *","* ]]; then
            start=${line%,*}
            length=${line#*,}
            end=$((start + length - 1))
            seq $start $end
        else
            echo $line
        fi
    done | sort -u > "$file.lines"

    # Vérifie le style uniquement sur les lignes modifiées
    echo "Vérification du style de $file..."
    while IFS="," read -r filename line col level msg rest; do
        if [ -n "$line" ] && [ -f "$file.lines" ]; then
            # Si la ligne est dans la liste des lignes modifiées
            if grep -q "^$line$" "$file.lines"; then
                echo "$filename:$line:$col: [$level] $msg"
                has_errors=1
            fi
        fi
    done < <(php phpcs.phar --standard="$work_dir/.github/phpcs/kr.xml" --report=csv "$file" 2>/dev/null || true)

    # Nettoyage des fichiers temporaires
    rm -f "$file.lines"
done

# Retourne 1 si des erreurs ont été trouvées, 0 sinon
exit $has_errors
