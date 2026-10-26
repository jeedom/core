# Nouvelle image Docker Jeedom

Cette proposition reprend l'image `ghcr.io/jeedom/core` utilisée pour les tests
et le SaaS, jusqu'ici maintenue dans `jeedom/infra-semaphore/images/jeedom`.
Le Dockerfile, le runtime et leurs tests sont maintenant versionnés avec le core.
Le build et la publication restent exécutés par Semaphore.

Le workflow GitHub Actions `Docker Test Image` (`docker-test.yml`) est supprimé.
Les builds et tests de la nouvelle image sont exécutés par Semaphore.
Le Dockerfile historique à la racine, `init.sh`, Compose et le workflow historique
de publication sont conservés pendant la revue de cette proposition. Le nouveau
Dockerfile doit être sélectionné explicitement avec `-f`.
Cette PR ne modifie ni ne supprime les fichiers d'`infra-semaphore` et ne bascule
pas le job ou les instances SaaS.

## Organisation

| Fichier | Rôle |
| --- | --- |
| `Dockerfile` | Debian 13 slim, Apache/PHP 8.4, MariaDB, Node 22 et Composer 2 ; paquets APT lus depuis `install/packages.json`. |
| `Dockerfile.dockerignore` | Exclusions propres à ce Dockerfile, avec la racine du dépôt comme contexte. |
| `prepare.py` | Métadonnées du checkout local ; aucun téléchargement d'un second core. |
| `rootfs/` | Configuration des services, initialisation, santé et adaptateurs de services. |
| `tests/` | Tests de Supervisor, de la configuration SQL et du conteneur. |

## Construire et tester

Depuis la racine d'un checkout Git propre, après avoir commité les changements :

```bash
python3 install/OS_specific/Docker/prepare.py
docker build --pull --no-cache \
  -f install/OS_specific/Docker/Dockerfile -t jeedom-core:test .
python3 install/OS_specific/Docker/tests/test_runtime.py jeedom-core:test
```

`source.json`, généré et ignoré par Git, décrit la révision, la branche et la
version de ce checkout. Il est embarqué dans
`/usr/share/jeedom-image/source.json`. En checkout détaché, la branche vaut `HEAD`
et la révision reste le commit exact. La version vient de `core/config/version`.
Les métadonnées Git et les tests sont exclus du contexte de l'image par
`Dockerfile.dockerignore`.

Les tests Docker créent des conteneurs et volumes jetables : installation neuve,
cron, arrêt, recréation, persistance du code et des données, configuration SQL
invalide et arrêt après la perte d'un service critique. Un échec doit bloquer
la publication.

Tests ciblés sans moteur Docker :

```bash
python3 -m unittest discover -s install/OS_specific/Docker/tests -p 'test_*.py'
JEEDOM_DATABASE_SOURCE="$PWD/install/OS_specific/Docker/rootfs/usr/local/lib/jeedom/database.php" \
  php install/OS_specific/Docker/tests/test_configuration.php
```

## Raccordement ultérieur de Semaphore

Après validation et intégration sur la branche à construire, adapter uniquement
les champs suivants dans `config/builds/jeedom-core.yml` d'`infra-semaphore` :

```yaml
git_url: git@github.com:jeedom/core.git
git_branch: master
dockerfile: install/OS_specific/Docker/Dockerfile
build_context: .
keep_git_metadata: true
prepare_command: [python3, install/OS_specific/Docker/prepare.py]
image_version_file: core/config/version
image_test_command: [python3, install/OS_specific/Docker/tests/test_runtime.py]
```

`keep_git_metadata: true` est nécessaire : le playbook retire normalement `.git`
avant la préparation. Le fichier d'exclusion Docker empêche son embarquement.
Le registre, les identifiants GHCR, les tags de version et `latest`, les tests
avant publication et la planification mensuelle restent gérés par le modèle
Semaphore existant. Aucun workflow GitHub Actions ne publie cette nouvelle image.

L'exemple cible `master` une fois ces fichiers intégrés sur cette branche. Pour
évaluer la PR, sélectionner sa branche et une image ou des tags de test séparés ;
ne pas publier une version de développement sous les tags de production.
Les fichiers actuels d'`infra-semaphore` sont conservés jusqu'à la bascule validée.

## Exécution et persistance

```bash
docker run -d --name jeedom-core-test --restart unless-stopped \
  --stop-timeout 90 -p 8080:80 -e TZ=Europe/Paris \
  --mount type=volume,src=jeedom-core-test-html,dst=/var/www/html \
  --mount type=volume,src=jeedom-core-test-db,dst=/var/lib/mysql \
  jeedom-core:test
docker logs -f jeedom-core-test
```

| Chemin | Rôle |
| --- | --- |
| `/usr/src/jeedom` | Code initial embarqué dans l'image. |
| `/var/www/html` | Volume persistant : code, configuration, plugins, sauvegardes et logs. |
| `/var/lib/mysql` | Volume persistant de la MariaDB intégrée. |
| `/run/jeedom`, `/tmp/jeedom` | État temporaire recréé au démarrage. |

Un HTML vide reçoit le code embarqué. Un HTML existant est conservé, y compris
les mises à jour faites depuis Jeedom : changer l'image actualise les outils
système, sans remplacer son code persistant. La configuration SQL et les données
existantes sont conservées. Une incohérence bloque le démarrage et nécessite une
restauration ; elle ne déclenche pas de réinitialisation destructive.

L'initialisation démarre comme root ; Apache/PHP utilise `www-data` et MariaDB
utilise `mysql`. Supervisor pilote MariaDB, le bootstrap, Apache, cron et atd.
La perte d'un service critique arrête le conteneur. Le contrôle de santé vérifie
SQL, HTTP et un heartbeat cron récent. Prévoir 90 secondes pour l'arrêt, soit
`terminationGracePeriodSeconds: 90` dans Kubernetes. HTTP écoute sur le port 80 ;
HTTPS est assuré par le reverse proxy.

Les dépendances de plugins installées hors des volumes sont perdues à la
recréation. Cette image conserve donc le périmètre test/SaaS de l'image source.
La migration du stockage d'une autre version MariaDB doit être testée séparément.

## Variables du conteneur

| Variable | Défaut et effet |
| --- | --- |
| `TZ` | `Europe/Paris` ; fuseau système et PHP. |
| `DB_HOST` | Configuration existante, sinon `localhost` pour la base intégrée. Toute autre valeur choisit une base externe. |
| `DB_PORT` | Configuration existante, sinon `3306`. |
| `DB_NAME` | Configuration existante, sinon `jeedom`. |
| `DB_USER` | Configuration existante, sinon `jeedom`. |
| `DB_PASSWORD` | Configuration existante ; aléatoire pour une base interne neuve. |
| `DB_PASSWORD_FILE` | Fichier de secret dans le conteneur, en alternative à `DB_PASSWORD`. |

Les variables `DB_*` explicitement fournies priment à chaque démarrage sur
`common.config.php` ; les autres champs sont conservés. Une valeur vide est
refusée. Une destination existante est vérifiée avant la mise à jour atomique de
la configuration. Les autres clés de configuration et `DEBUG` sont conservés.
Une base externe neuve doit exister, être vide et disposer de son compte SQL.
Changer les variables sur une base existante ne crée pas de compte et ne change
pas son mot de passe côté serveur. MariaDB intégrée écoute uniquement par socket.

## Dépendances embarquées

Le Dockerfile lit les noms de paquets de la section `apt` d'`install/packages.json`
du checkout construit, y compris les paquets marqués `optional`. Python 3 est
installé d'abord pour lire ce JSON. Le nom `libcurl4` est traduit en
`libcurl4t64` pour Debian 13.

Une liste complémentaire dans le Dockerfile fournit les dépendances absentes
du catalogue : Apache, Supervisor, cron, outils de l'installateur et
`mariadb-client-compat` pour les noms historiques MySQL, dont `mysqlcheck`.
Les paquets présents dans le JSON ne sont pas recopiés dans cette liste.
Un ajout au catalogue est ainsi installé au prochain build. Une erreur de lecture
du JSON ou d'installation APT bloque la construction, sans script de comparaison.

Aucun démon de synchronisation de l'horloge n'est lancé. Python 2 et PHP IMAP ne
sont pas fournis par cette image.
