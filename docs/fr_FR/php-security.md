# Atténuation temporaire des vulnérabilités PHP du 21 octobre 2026

Les CVE-2026-109618 et CVE-2026-109619 affectent PHP lui-même. Avec Apache
`mod_php`, une requête non authentifiée peut atteindre certains chemins
vulnérables avant l'exécution de Jeedom. L'authentification Jeedom ne suffit
donc pas à les bloquer.

**Les protections ci-dessous sont partielles. Seule l'installation d'un PHP
corrigé protège complètement contre ces deux vulnérabilités.** Les premières
versions corrigées sont PHP 8.2.35, 8.3.36, 8.4.27 et 8.5.12. Un fournisseur
peut aussi rétroporter les correctifs dans un paquet portant un autre numéro.

## Protections proposées par le core

Avec l'installation Apache standard de Jeedom :

- Le suivi de progression des uploads par les sessions PHP est désactivé avant
  l'analyse de la requête. Les uploads et les sessions d'authentification restent
  disponibles. Seuls les plugins utilisant explicitement
  `PHP_SESSION_UPLOAD_PROGRESS` perdent ce mécanisme de progression.
- La profondeur des variables d'entrée PHP est limitée à 32 niveaux, au lieu
  des 64 niveaux par défaut. Un formulaire de plugin dépassant cette profondeur
  doit être adapté ; les données JSON décodées par l'application ne sont pas
  couvertes par cette limite.
- Apache impose un plafond de 1 Gio sur le corps des requêtes, y compris avec
  `Transfer-Encoding: chunked`. Cette borne correspond au `post_max_size`
  déjà configuré par Jeedom. Elle ne limite pas le nombre d'en-têtes internes
  d'une partie multipart, ni la mémoire nécessaire à leur analyse.

Les deux modèles Apache (`apache_security` et `apache_security_unsecure`)
imposent les réglages PHP avec `php_admin_*`, pour qu'un `.htaccess` ou un
`ini_set()` ne puisse pas les réactiver. Le `.htaccess` du core applique aussi
ces réglages pour les installations `mod_php` qui n'ont pas encore actualisé
leur configuration Apache ; dans ce cas, un autre `.htaccess` peut les modifier.

Le plafond mémoire PHP et les limites PHP d'upload existantes restent inchangés.
**Le chemin d'exploitation par épuisement mémoire (CVE-2026-109618) reste
possible**, notamment avec des requêtes plus petites que la limite Apache.
La désactivation du suivi des uploads bloque les chemins de session identifiés
pour CVE-2026-109619, mais pas les autres états internes concernés.

## Activation et vérification

Le `.htaccess` est distribué avec la mise à jour du core. Une installation
utilisant `.htaccess_custom` doit y reporter ces protections. Les nouvelles
installations utilisent le modèle Apache actualisé. Pour une installation
existante, la vérification de cohérence actualise le modèle de sécurité standard
reconnu par Jeedom ; une configuration personnalisée ou le modèle sans CSP
bloquante doit être adapté par son administrateur.

Après actualisation des fichiers, vérifier la configuration puis effectuer un
**redémarrage complet** d'Apache pour remplacer tous les workers :

```bash
sudo apache2ctl configtest
sudo systemctl restart apache2
```

Le rechargement gracieux effectué par la vérification de cohérence ne remplace
pas ce redémarrage complet. Pour Docker, redémarrer ou recréer le conteneur
après mise à jour des fichiers utilisés. L'interruption des requêtes en cours
est à prévoir.

Vérifier les valeurs effectives dans le contexte **HTTP**, pas uniquement avec
`php -i` : la configuration du PHP CLI peut être différente. Tester la connexion,
un formulaire imbriqué (par exemple un scénario) et l'upload d'une sauvegarde.
Ne pas laisser de page `phpinfo()` publique pour ce contrôle.

Ces directives Apache ne configurent pas PHP-FPM, CGI ou un autre serveur.
Dans ces cas, désactiver `session.upload_progress.enabled` dans le `php.ini`
du service ou le pool FPM, limiter la profondeur des entrées et le corps des
requêtes au niveau du serveur, puis redémarrer tous les workers. Un `ini_set()`
dans Jeedom ou un `.user.ini` FPM arrive trop tard pour les chemins confirmés.

## Profil d'urgence plus strict

Si PHP ne peut pas encore être corrigé, réduire temporairement les corps de
requête à 32 Mio dans la configuration Apache du site :

```apache
<Location />
    LimitRequestBody 33554432
</Location>
```

Pour `mod_php`, ajouter dans le bloc `<Directory>` du site correspondant à la
version chargée (`mod_php.c` pour PHP 8, `mod_php7.c` pour PHP 7) :

```apache
php_admin_value post_max_size 32M
php_admin_value upload_max_filesize 31M
php_admin_flag session.upload_progress.enabled Off
php_admin_value max_input_nesting_level 32
```

Le bloc `<Location>` impose le plafond Apache après les réglages `.htaccess`.
Les sauvegardes ou archives dépassant 31 Mio ne pourront plus être envoyées
depuis le navigateur : les transférer autrement, par exemple par SSH.
Avec un reverse proxy, appliquer aussi sa limite au corps décodé des requêtes.

La recommandation PHP pour réduire le chemin OOM est d'imposer également :

```apache
php_admin_value memory_limit -1
```

**Ce réglage n'est pas activé automatiquement par Jeedom.** Sans limite mémoire
PHP, une requête peut épuiser la RAM de la box. Prévoir et tester une limite
mémoire au niveau du conteneur ou du cgroup et accepter le risque d'arrêt du
service avant de l'activer. Ne pas utiliser `RLimitMEM`, `RLIMIT_AS` ou
`RLIMIT_DATA` pour remplacer cette limite : ils peuvent déclencher le chemin
d'allocation vulnérable. Même avec ces mesures, une allocation système peut
échouer et les autres chemins de CVE-2026-109619 restent possibles.

Pour PHP 8.5, désactiver aussi `fatal_error_backtraces` dans le `php.ini` du
service, puis redémarrer tous les workers. Supprimer les réglages obsolètes ou
invalides appliqués par requête qui émettent des diagnostics avant
l'initialisation de PHP. Masquer les avertissements ne corrige pas le problème.

## Références

- [Avis officiel PHP](https://www.php.net/archive/2026.php#2026-10-21-1)
- [CVE-2026-109618 : corruption lors d'un épuisement mémoire](https://github.com/php/php-src/security/advisories/GHSA-w8hw-w9fx-j79w)
- [CVE-2026-109619 : état global périmé](https://github.com/php/php-src/security/advisories/GHSA-6wmf-4rv5-p3rp)
