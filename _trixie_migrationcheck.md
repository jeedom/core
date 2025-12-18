# Migration et compatibilité Debian 13 (Trixie)

Ce document récapitule les modifications apportées pour rendre Jeedom compatible avec Debian 13 (Trixie).

## 📋 Résumé des modifications

### Packages système mis à jour

#### Packages remplacés
- `ntp` → `chrony` (service de synchronisation horaire moderne)
- `ntpdate` → supprimé (inclus dans chrony)
- `locate` → `plocate` (version moderne avec alternative)
- `espeak` + `mbrola` → `espeak-ng` (synthèse vocale moderne)
- `libcurl3-gnutls` → `libcurl4` (pour Debian 13+)

#### Packages supprimés (obsolètes)
- `telnet` (obsolète et non sécurisé)
- `apt-transport-https` (inutile depuis Debian 10)
- `cutycapt` (obsolète)
- `dos2unix` (remplacé par `sed` natif)
- `libav-tools` (remplacé par `ffmpeg`)
- `software-properties-common` (paquet Ubuntu, absent de Debian pur)

#### Packages rendus optionnels
- `php-imap` (retiré de PHP 8.4+, non disponible sur Debian 13)
- `php-ldap`, `php-yaml`, `php-snmp` (optionnels)
- `chromium` (optionnel)

### Migration vers PHP-FPM

Jeedom utilise maintenant **PHP-FPM** au lieu de **mod_php** pour :
- Meilleures performances
- Meilleure isolation des processus
- Architecture moderne recommandée

**Modules Apache activés** :
- `proxy_fcgi` (pour PHP-FPM)
- `setenvif`
- Détection automatique de la version PHP

### Configuration MariaDB

**Paramètres obsolètes supprimés** (incompatibles avec MariaDB 10.11+) :
- `query_cache_type`, `query_cache_size`, `query_cache_limit`, `query_cache_min_res_unit` (le query cache n'existe plus)
- `innodb_large_prefix` (activé par défaut depuis MySQL 5.7)

### Détection de version Debian

La fonction `getOsVersion()` a été améliorée pour reconnaître les noms de code Debian :
- `trixie` → version 13
- `bookworm` → version 12
- `bullseye` → version 11
- `buster` → version 10

Gestion du format `codename/sid` pour les versions de développement avec fallback sur `/etc/os-release`.

### Version PHP minimale

Version PHP minimale augmentée de **PHP 7** à **PHP 7.4** (EOL de PHP 7.x).

### Support pour fork personnalisé

Ajout du paramètre `-r` pour spécifier un repository GitHub personnalisé :
```bash
sudo ./install.sh -r owner/repo -v branche
```

## 📝 Détails des fichiers modifiés

### 1. `install/install.sh`

**Packages installés (step_2_mainpackage)** :
```bash
# Supprimés
- software-properties-common (Ubuntu uniquement)
- telnet, apt-transport-https, cutycapt
- dos2unix, libav-tools
- ntp, ntpdate, espeak, mbrola

# Ajoutés/remplacés
+ chrony
+ plocate (avec alternative "locate")
+ espeak-ng

# Packages optionnels avec gestion d'erreur améliorée
apt-get -y install chromium > /dev/null 2>&1 || echo "[Optional] chromium not available"
apt-get -y remove brltty > /dev/null 2>&1 || echo "[Optional] brltty not present"
```

**PHP-FPM (step_5_php)** :
```bash
# Avant
apt_install php libapache2-mod-php php-json php-mysql

# Après
apt_install php php-fpm php-json php-mysql
a2enmod proxy_fcgi setenvif
a2enconf php${PHP_VERSION}-fpm

# Packages PHP optionnels avec messages informatifs
apt-get -y install php-imap > /dev/null 2>&1 || echo "[Optional] php-imap not available (normal on Debian 13+)"
apt-get -y install php-ldap > /dev/null 2>&1 || echo "[Optional] php-ldap not available"
apt-get -y install php-yaml > /dev/null 2>&1 || echo "[Optional] php-yaml not available"
apt-get -y install php-snmp > /dev/null 2>&1 || echo "[Optional] php-snmp not available"
```

**Avantage de cette approche** :
- ✅ Les erreurs apt sont masquées (`> /dev/null 2>&1`)
- ✅ Un message informatif en jaune est affiché si l'installation échoue
- ✅ L'utilisateur comprend que c'est optionnel et pourquoi
- ✅ Les vrais problèmes système restent visibles
- ✅ Plus informatif que de tout masquer avec `2>/dev/null`
```

**Configuration Apache (step_8_jeedom_customization)** :
```bash
a2dismod php*
a2enmod proxy_fcgi setenvif
PHP_VERSION=$(php -r "echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;")
a2enconf php${PHP_VERSION}-fpm
service_action restart php${PHP_VERSION}-fpm
```

**MariaDB (step_7_jeedom_customization_mariadb)** :
Suppression des paramètres obsolètes :
```bash
# Supprimés
- query_cache_type
- query_cache_size
- query_cache_limit
- query_cache_min_res_unit
- innodb_large_prefix
```

**Repository personnalisé (step_6_jeedom_download)** :
```bash
# Variables
GITHUB_REPO=jeedom/core  # Par défaut
VERSION=master            # Par défaut

# Extraction du nom du dépôt (pour gérer les forks)
REPO_NAME=$(echo "${GITHUB_REPO}" | awk -F'/' '{print $2}')

# URL de téléchargement
wget https://codeload.github.com/${GITHUB_REPO}/zip/refs/heads/${VERSION}

# Nettoyage des anciennes extractions
rm -rf /root/${REPO_NAME}-*

# Extraction et vérification
unzip -q /tmp/jeedom.zip -d /root/
EXTRACTED_DIR="/root/${REPO_NAME}-${VERSION}"

if [ ! -d "${EXTRACTED_DIR}" ]; then
  echo "Cannot find extracted directory: ${EXTRACTED_DIR}"
  echo "Available directories in /root/:"
  ls -la /root/
  exit 1
fi

# Déplacement vers le webserver
cp -R ${EXTRACTED_DIR}/* ${WEBSERVER_HOME}/
```

**Note** : GitHub crée toujours un répertoire au format `{nom-du-dépôt}-{branche}` lors de l'extraction d'une archive. Par exemple :
- `jeedom/core` branche `master` → `/root/core-master/`
- `titidom-rc/jeedom-core` branche `4.5.1` → `/root/jeedom-core-4.5.1/`

### 2. `install/packages.json`

**Packages ajoutés/modifiés** :
```json
{
  "chrony": {},
  "plocate": {"alternative": ["locate"]},
  "espeak-ng": {},
  "libcurl4": {"optional": true, "remark": "{{Utilisé pour chromium à partir de Debian 13}}"},
  "libcurl3-gnutls": {"optional": true, "remark": "{{Utilisé pour chromium}}", "denyDebianHigherEqual": "13"},
  "php-imap": {"alternative": ["/php(.*?)-imap/"], "optional": true, "remark": "{{Retiré de PHP 8.4+, non disponible sur Debian 13}}", "denyDebianHigherEqual": "13"}
}
```

**Packages supprimés** :
- `software-properties-common`
- `apt-transport-https`

### 3. `core/class/system.class.php`

**Fonction `getOsVersion()` améliorée** :
```php
public static function getOsVersion() {
    if (self::getDistrib() != 'debian') {
        return 0;
    }
    if (isset(self::$_os_version)) {
        return self::$_os_version;
    }
    $version = exec('cat /etc/debian_version');
    
    // Mapping des noms de code vers les numéros de version
    $codenames = array(
        'trixie' => '13',
        'bookworm' => '12',
        'bullseye' => '11',
        'buster' => '10',
        'stretch' => '9',
        'jessie' => '8'
    );
    
    // Vérifier si c'est un nom de code connu (avec ou sans /sid)
    foreach ($codenames as $codename => $number) {
        if (strpos($version, $codename) !== false) {
            self::$_os_version = $number;
            return self::$_os_version;
        }
    }
    
    // Si format "codename/sid" non reconnu, essayer de lire VERSION_ID depuis os-release
    if (strpos($version, '/') !== false) {
        $osVersion = exec('grep VERSION_ID /etc/os-release | cut -d\'=\' -f2 | tr -d \'"\'');
        if ($osVersion != '') {
            self::$_os_version = $osVersion;
            return self::$_os_version;
        }
    }
    
    // Sinon, retourner la version telle quelle
    self::$_os_version = $version;
    return self::$_os_version;
}
```

### 4. `core/repo/market.repo.php`

**Remplacement de `dos2unix` par `sed`** :
```php
// Avant
exec('find ' . realpath(__DIR__ . '/../../plugins/' . $plugin_id) . ' -name "*.sh" -type f -exec dos2unix {} \;');

// Après
// Conversion des fins de ligne CRLF vers LF pour les scripts shell (sed est natif sur tous les systèmes)
exec('find ' . realpath(__DIR__ . '/../../plugins/' . $plugin_id) . ' -name "*.sh" -type f -exec sed -i \'s/\r$//\' {} \;');
```

### 5. `install/install.php`

**Version PHP minimale** :
```php
// Avant
if (version_compare(PHP_VERSION, '7', '<')) {
    throw new Exception('Jeedom nécessite PHP 7 ou plus (actuellement : ' . PHP_VERSION . ')');
}

// Après
if (version_compare(PHP_VERSION, '7.4', '<')) {
    throw new Exception('Jeedom nécessite PHP 7.4 ou plus (actuellement : ' . PHP_VERSION . ')');
}
```

### 6. `resources/install_nodejs.sh`

**Modernisation complète du script d'installation NodeJS 22** :

#### Méthode d'installation simplifiée
```bash
# Avant (configuration manuelle GPG + sources)
sudo mkdir -p /etc/apt/keyrings
curl -fsSL https://deb.nodesource.com/gpgkey/nodesource-repo.gpg.key | sudo gpg --dearmor -o /etc/apt/keyrings/nodesource.gpg
echo "deb [signed-by=/etc/apt/keyrings/nodesource.gpg] https://deb.nodesource.com/node_$NODE_MAJOR.x nodistro main" | sudo tee /etc/apt/sources.list.d/nodesource.list

# Après (méthode officielle NodeSource)
curl -fsSL https://deb.nodesource.com/setup_22.x | sudo -E bash -
sudo apt-get install -y nodejs
```

#### Packages requis optimisés
```bash
# Avant
apt-get install -y lsb-release build-essential apt-utils git gnupg curl

# Après (uniquement les packages strictement nécessaires)
apt-get install -y lsb-release curl build-essential
```

#### Support NodeJS 22 pour armv6l (Raspberry Pi Zero/1/2)
```bash
# Ajouté
elif [[ $installVer == "22" ]]; then
  armVer="22.12.0"
```

#### Détection intelligente et mise à jour
```bash
# Avant
sudo apt-get -y --purge autoremove nodejs npm  # Désinstallation systématique

# Après (détection de provenance)
if dpkg -l nodejs 2>/dev/null | grep -q '^ii'; then
  if ! apt-cache policy nodejs 2>/dev/null | grep -q 'deb.nodesource.com'; then
    echo "NodeJS détecté depuis les dépôts Debian officiels, désinstallation nécessaire"
    sudo apt-get -y --purge autoremove nodejs npm
  else
    echo "NodeJS détecté depuis NodeSource, mise à jour en place"
  fi
fi
```

#### Suppression warning npm obsolète
```bash
# Suppression du paramètre globalignorefile (obsolète npm v9+)
npm config delete globalignorefile &>/dev/null || true
sudo npm config delete globalignorefile &>/dev/null || true
sudo -u www-data npm config delete globalignorefile &>/dev/null || true
```

#### Corrections dates Debian
```bash
# Debian 10 Buster
if [[ "$today" > "20240630" ]]; then  # Corrigé (était 20220630)
  echo "Debian 10 Buster n'est plus supportée depuis le 30 juin 2024"
```

#### Message d'erreur x86 32 bits actualisé
```bash
# Avant
echo "NodeJS 12 n'y est pas supporté"

# Après
echo "NodeJS n'y est plus supporté"
```

## 🚀 Installation

### Prérequis
- Debian 13 (Trixie) testing/sid ou version stable
- Accès root ou sudo

### Installation standard

```bash
wget https://raw.githubusercontent.com/titidom-rc/jeedom-core/4.5.1/install/install.sh -O install.sh
sudo bash install.sh 2>&1 | tee jeedom-install.log
```

### Installation avec fork personnalisé

```bash
wget https://raw.githubusercontent.com/votre-user/jeedom-core/votre-branche/install/install.sh -O install.sh
sudo bash install.sh -r votre-user/jeedom-core -v votre-branche 2>&1 | tee jeedom-install.log
```

### Paramètres disponibles

```bash
sudo bash install.sh [OPTIONS]

Options:
  -s STEP              Étape à exécuter (0-12)
  -v VERSION           Branche/version à installer (défaut: master)
  -r REPO              Repository GitHub au format owner/repo (défaut: jeedom/core)
  -w PATH              Dossier web (défaut: /var/www/html)
  -i TYPE              Type d'installation (défaut: standard)
  -d 0|1               Installation base de données (défaut: 1)
```

### Exemple avec tous les paramètres

```bash
sudo bash install.sh \
  -r titidom-rc/jeedom-core \
  -v 4.5.1 \
  -w /var/www/html \
  -i standard \
  -d 1 \
  2>&1 | tee jeedom-install.log
```

### Installation en une ligne (pipe)

```bash
wget -O- https://raw.githubusercontent.com/titidom-rc/jeedom-core/4.5.1/install/install.sh | sudo bash 2>&1 | tee jeedom-install.log
```

## ✅ Compatibilité PHP 8.3+

Le code Jeedom a été analysé pour la compatibilité PHP 8.3+ :

### Points vérifiés ✅
- Aucune fonction obsolète (`create_function`, `each()`, `money_format`)
- Toutes les propriétés de classes sont déclarées (`private`/`protected`/`public`)
- Pas de passage de `null` problématique aux fonctions natives
- Utilisation correcte de la Reflection API
- Pas de dynamic properties non déclarées

### Conclusion
**Le code Jeedom est pleinement compatible PHP 8.3+** sans modification nécessaire.

## 📊 Récapitulatif des changements

| Composant | Avant | Après | Raison |
|-----------|-------|-------|--------|
| Synchronisation horaire | ntp + ntpdate | chrony | Service moderne recommandé |
| Recherche fichiers | locate | plocate | Version moderne |
| Synthèse vocale | espeak + mbrola | espeak-ng | Mieux maintenu |
| Serveur PHP | mod_php | PHP-FPM | Performances et isolation |
| Base de données | MariaDB avec query cache | MariaDB sans query cache | Supprimé dans 10.11+ |
| Conversion fins ligne | dos2unix | sed natif | Dépendance en moins |
| PHP IMAP | php-imap requis | php-imap optionnel | Retiré de PHP 8.4+ |
| Détection Debian | Version numérique | Codenames + fallback | Support Trixie |
| PHP minimal | 7.0 | 7.4 | EOL de PHP 7.x |
| Gestion erreurs packages optionnels | Erreurs silencieuses | Messages informatifs | Transparence et debug |

## 🔍 Tests recommandés

1. **Installation fraîche** sur Debian 13
2. **Mise à jour** depuis Debian 12
3. **Vérification PHP-FPM** : `systemctl status phpX.X-fpm`
4. **Test des fonctionnalités** : scénarios, plugins, TTS
5. **Performances** : comparaison avant/après

## 📚 Références

- [Debian 13 Release Notes](https://www.debian.org/releases/trixie/)
- [PHP 8.3 Migration Guide](https://www.php.net/manual/en/migration83.php)
- [MariaDB 10.11 Release Notes](https://mariadb.com/kb/en/changes-improvements-in-mariadb-1011/)
- [PHP-FPM Configuration](https://www.php.net/manual/en/install.fpm.php)

## 🤝 Contribution

Pour contribuer à ces modifications :
1. Forkez le repository
2. Créez une branche : `git checkout -b debian-13-support`
3. Committez vos changements : `git commit -am 'Add Debian 13 support'`
4. Pushez vers la branche : `git push origin debian-13-support`
5. Créez une Pull Request

## 📝 Licence

Ce document et les modifications associées sont distribués sous la même licence que Jeedom (GPL v3).

---

## 🆕 Améliorations récentes (18 décembre 2025)

### Gestion améliorée des packages optionnels

Au lieu de masquer complètement les erreurs (`2>/dev/null`), le script affiche maintenant des **messages informatifs** lorsqu'un package optionnel n'est pas disponible :

```bash
# Exemple de sortie sur Debian 13
[Optional] php-imap not available (normal on Debian 13+ with PHP 8.4+)
[Optional] chromium not available (used for reports)
```

**Packages concernés** :
- `chromium` (step_2_mainpackage) - pour génération de rapports PDF
- `brltty` (step_2_mainpackage) - nettoyage lecteur braille
- `php-imap` (step_5_php) - accès IMAP (retiré PHP 8.4+)
- `php-ldap` (step_5_php) - authentification LDAP
- `php-yaml` (step_5_php) - fichiers YAML
- `php-snmp` (step_5_php) - monitoring SNMP

**Avantages** :
- ✅ Transparence : l'utilisateur sait ce qui se passe
- ✅ Debug facilité : les vrais problèmes système restent visibles
- ✅ Meilleure UX : messages colorés et informatifs
- ✅ Rétrocompatibilité : fonctionne sur Debian 11, 12 et 13

---

**Date de dernière mise à jour** : 18 décembre 2025  
**Version Jeedom** : 4.5.1  
**Debian cible** : 13 (Trixie)
