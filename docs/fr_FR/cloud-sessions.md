# Authentification Cloud des box

Le client REST Market du Core obtient une session Cloud propre à la box, valable au maximum 24 heures. Le Market transmet son empreinte SHA-256 au Cloud avant de retourner le jeton au Core. Le compte Cloud reste alimenté par la synchronisation existante.

Déployer Cloud [#38](https://github.com/jeedom/cloud-service/pull/38), sa table `session`, puis Market [#101](https://github.com/jeedom/market/pull/101) avant ce client. Une connexion REST Market est nécessaire. Le Core utilise le nouveau mode pour les sauvegardes WebDAV et le monitoring ; le Cloud continue d’accepter les anciens clients en parallèle.

## Utilisation dans un plugin

L’entrée publique chargeable automatiquement est :

```php
$token = repo_market::getCloudToken();
```

Cette méthode retourne le jeton existant s’il appartient au compte, au Market, au matériel et à la box courants, avec une marge d’expiration de 60 secondes. Sinon elle le demande au Market avec le Bearer REST de la box. Le jeton et son expiration sont stockés dans la configuration chiffrée `market::cloudSession`. Un verrou partagé entre les processus évite les demandes concurrentes. La déconnexion et une nouvelle connexion Market effacent la session Cloud locale.

Transmettre le jeton dans l’en-tête HTTP standard, avec vérification TLS et sans redirection :

```php
$http = new com_http(config::byKey('service::cloud::url') . '/service/monService');
$http->setNoSslCheck(false);
$http->setHeader(array('Authorization: Bearer ' . $token));
$http->setCURLOPT(array(CURLOPT_FOLLOWLOCATION => false));
$result = $http->exec(30, 1);
```

Après un HTTP **401**, appeler `repo_market::getCloudToken($tokenRefuse)` puis réessayer une seule fois. Si un autre processus a déjà renouvelé ce jeton, sa nouvelle valeur sera réutilisée. Un 403 ou une erreur de service ne doit pas provoquer de renouvellement. Aucun repli vers le mot de passe ou l’ancienne clé de compte n’est effectué. Ne pas afficher ni journaliser le jeton.

## Sauvegardes

Les opérations PROPFIND, MKCOL, PUT, GET et DELETE utilisent le Bearer. Les fichiers chiffrés sont transférés en flux par `com_http`, sans identifiant Market dans une commande `curl`/`wget`. Les erreurs HTTP sont contrôlées et les redirections refusées. Un 401 renouvelle le jeton et reprend le transfert une seule fois depuis le début.

Le mot de passe de sauvegarde reste nécessaire pour le chiffrement et le déchiffrement GPG. Les fichiers temporaires de chaque opération sont isolés dans un dossier privé puis supprimés. La liste et le nettoyage reconstruisent les URL à partir des noms du dossier configuré : les URL renvoyées par le serveur ne sont jamais suivies pour supprimer un fichier d’une autre box ou contacter un autre hôte.

## Vérifications

`php tests/market-cloud.php` exécute les contrôles de cache, expiration, reconnexion, renouvellement REST, concurrence entre processus, erreurs Cloud, WebDAV, transferts en flux et aller-retour GPG. La CI les exécute sous PHP 7.4 et 8.4.
