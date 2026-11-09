# Authentification Cloud des box

Chaque box utilise le même jeton d’accès REST pour le Market et le Cloud. Il est valable 24 heures. À la connexion et au renouvellement, le Market enregistre sa session puis tente de publier l’empreinte SHA-256 du jeton et son expiration au Cloud. Le jeton de renouvellement est réservé au Market. Le compte Cloud reste alimenté par la synchronisation existante.

Déployer Cloud [#38](https://github.com/jeedom/cloud-service/pull/38), sa table `session`, puis Market [#101](https://github.com/jeedom/market/pull/101) avant ce client. Une connexion REST Market est nécessaire. Le Core utilise le nouveau mode pour les sauvegardes WebDAV et le monitoring ; le Cloud continue d’accepter les anciens clients en parallèle.

## Utilisation dans un plugin

Utiliser la méthode publique de la classe chargée automatiquement.

```php
$token = repo_market::getCloudToken();
```

Cette méthode réutilise la session REST chiffrée `market::restSession` et son mécanisme de renouvellement. Un jeton encore valide est obtenu localement. À l’approche de son expiration, le renouvellement Market habituel fournit le prochain jeton.

Transmettre le jeton dans l’en-tête HTTP standard, avec vérification TLS et sans redirection.

```php
$http = new com_http(config::byKey('service::cloud::url') . '/service/monService');
$http->setNoSslCheck(false);
$http->setHeader(array('Authorization: Bearer ' . $token));
$http->setCURLOPT(array(CURLOPT_FOLLOWLOCATION => false));
$result = $http->exec(30, 1);
```

Si le Cloud est indisponible à l’émission, la box reçoit quand même son jeton et conserve l’accès au Market. Elle récupère l’accès Cloud au prochain renouvellement dont la publication réussit. Un HTTP 401 du Cloud est remonté à l’appelant. Garder le jeton hors des journaux et des réponses affichées.

## Sauvegardes

Les opérations PROPFIND, MKCOL, PUT, GET et DELETE utilisent le Bearer. Les fichiers chiffrés sont transférés en flux par `com_http`, sans identifiant Market dans une commande `curl`/`wget`. Les erreurs HTTP sont contrôlées et les redirections refusées.

Le mot de passe de sauvegarde reste nécessaire pour le chiffrement et le déchiffrement GPG. Les fichiers temporaires de chaque opération sont isolés dans un dossier privé puis supprimés. La liste et le nettoyage reconstruisent les URL à partir des noms du dossier configuré : les URL renvoyées par le serveur ne sont jamais suivies pour supprimer un fichier d’une autre box ou contacter un autre hôte.

## Vérifications

`php tests/market-cloud.php` exécute les contrôles de cache, expiration, reconnexion, renouvellement REST, concurrence entre processus, erreurs Cloud, WebDAV, transferts en flux et aller-retour GPG. La CI les exécute sous PHP 7.4 et 8.4.
