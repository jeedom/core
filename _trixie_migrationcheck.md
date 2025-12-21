# Migration et compatibilité Debian 13 (Trixie)

Ce document récapitule les modifications apportées pour rendre l'installation de Jeedom compatible avec Debian 13 (Trixie), tout en maintenant la rétrocompatibilité avec Debian 12 (Bookworm).

## 📋 Résumé des modifications

### Commandes APT → APT-GET

Pour améliorer la compatibilité et éviter les invites interactives lors des installations automatiques, toutes les commandes `apt` ont été remplacées par `apt-get` avec les options suivantes :

**Modifications principales** :

**Dans `install/install.sh`** :
- `apt update` → `apt-get update </dev/null`
- `apt upgrade` → `apt-get -y dist-upgrade </dev/null`
- `apt install` → `apt-get -o Dpkg::Options::="--force-confdef" -o Dpkg::Options::="--force-confold" -y install "$@" </dev/null`
- `apt -f install` → `apt-get -f install </dev/null`

**Dans `core/class/system.class.php`** :
- `apt update` → `apt-get update`
- `apt upgrade` → `DEBIAN_FRONTEND=noninteractive apt-get -o Dpkg::Options::="--force-confdef" -o Dpkg::Options::="--force-confold" -y upgrade`
- `apt install` → `DEBIAN_FRONTEND=noninteractive apt-get install -o Dpkg::Options::="--force-confdef" -o Dpkg::Options::="--force-confold" -y`
- `dpkg --configure -a` → `DEBIAN_FRONTEND=noninteractive dpkg --configure -a --force-confdef`

**Dans `desktop/php/system.php`** :
- `apt -f install` → `DEBIAN_FRONTEND=noninteractive apt-get -f install -y -o Dpkg::Options::='--force-confdef' -o Dpkg::Options::='--force-confold'`
- `dpkg --configure -a` → `DEBIAN_FRONTEND=noninteractive dpkg --configure -a --force-confdef --force-confold`

**Raisons du changement** :
- `apt-get` est plus stable pour les scripts automatisés (comportement prévisible)
- `DEBIAN_FRONTEND=noninteractive` évite toutes les invites interactives (utilisé dans `system.class.php` et `system.php`)
- `</dev/null` redirige stdin pour éviter les interactions dans `install.sh`
- `--force-confdef` : utilise la valeur par défaut pour les nouvelles options de configuration
- `--force-confold` : conserve les fichiers de configuration existants en cas de conflit (sauf dans `system.class.php` où seul `--force-confdef` est utilisé pour `dpkg --configure -a`)

**Fichiers modifiés** :
- `install/install.sh` : Toutes les commandes apt-get avec `</dev/null` et variables d'environnement globales
- `core/class/system.class.php` : Fonctions d'installation et mise à jour avec `DEBIAN_FRONTEND=noninteractive`
- `core/ajax/jeedom.ajax.php` : `apt-get update` simple
- `desktop/php/system.php` : Commandes système pour réparation/maintenance avec `at now`
- **`Dockerfile`** : Commandes apt-get avec `</dev/null` et variables d'environnement pour builds Docker
- **`install/OS_specific/Docker/init.sh`** : Support du repository GitHub custom

### Améliorations de l'interaction terminal (install.sh)

Pour éviter "l'effet escalier" dans le terminal lors de l'installation (lignes qui ne reviennent pas au début), plusieurs optimisations ont été apportées :

**Variables d'environnement globales** :
```bash
export DEBIAN_FRONTEND=noninteractive
export NEEDRESTART_MODE=l
```

- `DEBIAN_FRONTEND=noninteractive` : force apt/dpkg à ne pas interagir avec le terminal
- `NEEDRESTART_MODE=l` : mode "list" de needrestart - liste les services à redémarrer sans les redémarrer automatiquement (cohérent avec le message final "Reboot required")

**Redirection stdin depuis /dev/null** :
Toutes les commandes apt-get utilisent maintenant `</dev/null` pour empêcher les programmes d'interagir avec le terminal :

```bash
# Exemples
apt-get update </dev/null
apt-get -y dist-upgrade </dev/null
apt-get install packages </dev/null
```

**Avantages** :
- ✅ Empêche apt-get/dpkg de modifier les paramètres du terminal (conversion \r/\n)
- ✅ Élimine l'effet escalier (lignes décalées sans retour chariot)
- ✅ Affichage propre et lisible tout au long de l'installation
- ✅ Pas de redémarrages partiels pendant l'installation (reboot complet à la fin)

### Nettoyage du cache APT

Pour optimiser l'espace disque après l'installation, la commande `apt-get clean` a été ajoutée à la fin du script :

```bash
apt-get clean > /dev/null 2>&1
```

**Effet** :
- Supprime les fichiers .deb téléchargés du cache APT (`/var/cache/apt/archives/`)
- Libère de l'espace disque (potentiellement plusieurs centaines de Mo)
- Redirection de la sortie pour éviter les messages inutiles

**Placement** : Juste avant `exit 0` à la fin du script d'installation

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
wget -o /dev/stdout --tries=3 --timeout=60 https://codeload.github.com/${GITHUB_REPO}/zip/refs/heads/${VERSION} -O /tmp/jeedom.zip

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
curl -fsSL https://deb.nodesource.com/setup_${NODE_MAJOR}.x | sudo -E bash -
sudo apt-get install -y nodejs </dev/null
```

#### Packages requis optimisés
```bash
# Avant
apt-get install -y lsb-release build-essential apt-utils git gnupg curl

# Après (uniquement les packages strictement nécessaires)
sudo apt-get install -y lsb-release curl build-essential </dev/null
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
  -v trixie \
  -w /var/www/html \
  -i standard \
  -d 1 \
  2>&1 | tee jeedom-install.log
```

### Installation en une ligne (pipe)

```bash
wget -O- https://raw.githubusercontent.com/titidom-rc/jeedom-core/trixie/install/install.sh | sudo bash 2>&1 | tee jeedom-install.log
```

## 🐳 Images Docker

Les modifications pour Debian 13 ont également été appliquées aux **images Docker** officielles.

### Support du repository GitHub personnalisé

Le **Dockerfile** supporte maintenant un repository GitHub custom via l'argument `GITHUB_REPO` :

```bash
# Build avec le repo par défaut (jeedom/core)
docker build -t jeedom/jeedom:latest .

# Build avec un repo et une branche custom
docker build \
  --build-arg GITHUB_REPO=votre-user/jeedom-core \
  --build-arg VERSION=trixie \
  -t jeedom/jeedom:custom .
```

### Tags Docker disponibles

Le workflow CI/CD GitHub Actions génère automatiquement les tags suivants :

| Branche GitHub | Tags Docker générés | Utilisation |
|----------------|---------------------|-------------|
| **master** | `latest`, `4.5` | Version stable production |
| **beta** | `beta` | Tests pré-release |
| **trixie** | `trixie` | Support Debian 13 |
| **autres** | `alpha` | Développement général |

**Pull de l'image Debian 13** :
```bash
docker pull jeedom/jeedom:trixie
docker run -d -p 80:80 -p 443:443 --name jeedom jeedom/jeedom:trixie
```

### Modifications dans Dockerfile

**Variables d'environnement** :
```dockerfile
ENV DEBIAN_FRONTEND=noninteractive
ENV NEEDRESTART_MODE=l
ENV GITHUB_REPO=jeedom/core
```

**Packages modernisés** (identiques à `install.sh`) :
- ✅ `chrony` (remplace ntp)
- ✅ `plocate` (remplace locate)
- ✅ `espeak-ng` (remplace espeak)
- ✅ `libcurl4` (remplace libcurl3-gnutls)
- ✅ `php-fpm` (remplace libapache2-mod-php)
- ✅ Packages optionnels : `chromium`, `php-imap`, `php-ldap`, `php-yaml`, `php-snmp`

**Commandes** :
```dockerfile
RUN apt-get update </dev/null 
RUN apt-get -o Dpkg::Options::="--force-confdef" -o Dpkg::Options::="--force-confold" -y install \
  chrony plocate espeak-ng libcurl4 php php-fpm ... </dev/null && \
  (apt-get -y install chromium </dev/null 2>&1 || echo "[Optional] chromium not available") && \
  (apt-get -y install php-imap </dev/null 2>&1 || echo "[Optional] php-imap not available")

# Correction des fins de ligne Windows (CRLF → LF)
COPY install/install.sh /tmp/
RUN sed -i 's/\r$//' /tmp/install.sh

# Utilisation de bash au lieu de sh (syntaxe bash dans install.sh)
RUN bash /tmp/install.sh -s 1 -r ${GITHUB_REPO} -v ${VERSION} -w ${WEBSERVER_HOME} -d ${DATABASE} -i docker
```

**Points importants** :
- ✅ Utilisation de `bash` au lieu de `sh` pour éviter les erreurs de syntaxe
- ✅ Conversion automatique des fins de ligne CRLF en LF avec `sed`
- ✅ Support complet du repository GitHub personnalisé

### Modifications dans init.sh

Le script d'initialisation Docker a été amélioré :

```bash
# Téléchargement depuis le repo personnalisé
wget https://raw.githubusercontent.com/${GITHUB_REPO}/${VERSION}/install/install.sh -O /root/install.sh

# Appel avec l'option -r
/root/install.sh -s 6 -r ${GITHUB_REPO} -v ${VERSION} -w ${WEBSERVER_HOME}

# Démarrage de PHP-FPM (essentiel pour que Jeedom fonctionne)
PHP_VERSION=$(php -r "echo PHP_MAJOR_VERSION.'.'.PHP_MINOR_VERSION;")
service php${PHP_VERSION}-fpm start

# Configuration Apache pour éviter les warnings
echo "ServerName localhost" >> /etc/apache2/apache2.conf
service apache2 start
```

**Améliorations** :
- ✅ Démarrage automatique de PHP-FPM (correction erreur 503)
- ✅ Configuration du ServerName Apache (supprime warning AH00558)
- ✅ Support du repository GitHub personnalisé

### Modifications dans init_workflow.sh

Ajout du tag `trixie` pour les builds CI/CD :

```bash
elif [[ "${GITHUB_REF_NAME}" == "trixie" ]]; then
  JEEDOM_TAGS="${REPO}/jeedom:trixie";
  GITHUB_BRANCH=${GITHUB_REF_NAME};
```

## ✅ Compatibilité PHP 8.3+

Le code Jeedom a été analysé pour la compatibilité PHP 8.3+ :

### Points vérifiés ✅
- Aucune fonction obsolète (`create_function`, `each()`, `money_format`)
- Toutes les propriétés de classes sont déclarées (`private`/`protected`/`public`)
- Pas de passage de `null` problématique aux fonctions natives
- Utilisation correcte de la Reflection API
- Pas de dynamic properties non déclarées

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
| **Docker GITHUB_REPO** | **Hardcodé jeedom/core** | **Variable ARG/ENV** | **Support forks** |
| **Docker tags** | **latest, beta, alpha** | **+ trixie** | **Tag spécifique Debian 13** |
| **Docker shell** | **sh** | **bash** | **Syntaxe bash dans install.sh** |
| **Docker CRLF** | **Non géré** | **Conversion automatique sed** | **Compatibilité Windows** |
| **Docker PHP-FPM** | **Non démarré** | **Démarrage automatique** | **Évite erreur 503** |

## 🔍 Tests recommandés

1. **Installation fraîche** sur Debian 13
2. **Montée de version Jeedom** (par exemple 4.4 → 4.5) depuis une ancienne version
3. **Vérification PHP-FPM** : `systemctl status phpX.X-fpm`
4. **Test des fonctionnalités** : scénarios, plugins, TTS
5. **Performances** : comparaison avant/après
6. **🐳 Docker Debian 13** :
   ```bash
   # Build avec Debian 13
   docker build --build-arg DEBIAN=trixie-slim --build-arg VERSION=trixie -t jeedom:trixie .
   
   # Test du container
   docker run -d -p 80:80 -p 443:443 --name jeedom-test jeedom:trixie
   
   # Vérification
   curl http://localhost
   docker logs jeedom-test
   ```

## 📚 Références

- [Debian 13 Release Notes](https://www.debian.org/releases/trixie/)
- [PHP 8.3 Migration Guide](https://www.php.net/manual/en/migration83.php)
- [MariaDB 10.11 Release Notes](https://mariadb.com/kb/en/changes-improvements-in-mariadb-1011/)
- [PHP-FPM Configuration](https://www.php.net/manual/en/install.fpm.php)
- [Docker Hub - Jeedom](https://hub.docker.com/r/jeedom/jeedom)

## 📝 Licence

Ce document et les modifications associées sont distribués sous la même licence que Jeedom (GPL v3).

---

**Date de dernière mise à jour** : 21 décembre 2025  
**Version Jeedom** : 4.5.2  
**Debian cible** : 13 (Trixie)  
**Compatibilité** : Debian 12 (Bookworm) et Debian 13 (Trixie)
