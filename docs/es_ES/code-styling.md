# Verificador de Estilo de Código K&R

Este sistema permite verificar el cumplimiento del estilo K&R en las modificaciones de código. Se puede utilizar tanto localmente como a través de GitHub Actions.

## Uso Local

### Verificación de Estilo

Para verificar cambios no confirmados:
```bash
.github/scripts/check-style.sh file1.php file2.php
```

Para verificar todos los archivos PHP modificados:
```bash
.github/scripts/check-style.sh $(git diff --name-only | grep ".php$")
```

### Corrección Automática

PHPCS permite la corrección automática de ciertos errores de estilo:

1. Descargue phpcbf (PHP Code Beautifier):
```bash
curl -OL https://squizlabs.github.io/PHP_CodeSniffer/phpcbf.phar
```

2. Ejecute la corrección en sus archivos:
```bash
php phpcbf.phar --standard=.github/phpcs/kr.xml file1.php file2.php
```

⚠️ **Importante:**
- Siempre haga una copia de seguridad o commit antes de usar la corrección automática
- Revise los cambios después de la corrección
- Algunos errores requieren corrección manual

### Instalación como Hook Pre-Commit

Para verificar automáticamente el estilo antes de cada commit:

1. Cree el archivo `.git/hooks/pre-commit`:
```bash
#!/bin/bash

# Obtener archivos PHP modificados
FILES=$(git diff --cached --name-only --diff-filter=ACM | grep ".php$" || true)

if [ -n "$FILES" ]; then
    .github/scripts/check-style.sh $FILES
    if [ $? -ne 0 ]; then
        echo "❌ Errores de estilo detectados. Por favor, corrija antes de confirmar."
        exit 1
    fi
fi
```

2. Hágalo ejecutable:
```bash
chmod +x .git/hooks/pre-commit
```

## Estilo de Código

El estilo K&R verificado incluye:
- Indentación de 4 espacios
- Llaves en la misma línea para funciones
- (otras reglas específicas del proyecto)

Para más detalles, consulte el archivo `.github/phpcs/kr.xml`.

## GitHub Actions

El workflow de GitHub verifica automáticamente el estilo de los archivos modificados en cada Pull Request. Solo verifica las líneas modificadas para evitar falsos positivos en el código existente.

## Problemas Comunes y Soluciones

### Mensaje de error "Not a git repository"
- Asegúrese de estar en el directorio del proyecto
- Verifique que el directorio sea un repositorio git

### Errores no corregidos por phpcbf
Algunos errores requieren corrección manual, particularmente:
- Organización de métodos en la clase
- Problemas de nomenclatura
- Estructura general del código

### Falsos Positivos
Si encuentra falsos positivos:
1. Verifique que está usando la última versión de los scripts
2. Asegúrese de que el archivo no esté excluido en `kr.xml`
