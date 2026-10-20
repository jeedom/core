<?php
// Initialisation du conteneur, hors du document root et sans modification du core.
declare(strict_types=1);

const HTML = '/var/www/html';
const CONFIG = HTML . '/core/config/common.config.php';
const INSTALLING = HTML . '/.jeedom-installing';

function connectDatabase(array $db, bool $wait = true): PDO {
    $deadline = time() + ($wait ? 60 : 0);
    do {
        try {
            $dsn = isset($db['socket'])
                ? 'mysql:unix_socket=' . $db['socket']
                : 'mysql:host=' . $db['host'] . ';port=' . $db['port'];
            if (!empty($db['dbname'])) {
                $dsn .= ';dbname=' . $db['dbname'];
            }
            return new PDO($dsn . ';charset=utf8mb4', $db['username'], $db['password'], [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                PDO::ATTR_TIMEOUT => 3,
            ]);
        } catch (PDOException $error) {
            // Une erreur d'identifiants ne se résout pas en attendant le serveur.
            if (in_array((int) ($error->errorInfo[1] ?? 0), [1044, 1045, 1698], true)) {
                throw new RuntimeException('Authentification MariaDB refusée : ' . $error->getMessage());
            }
            if (time() >= $deadline) {
                throw new RuntimeException('Connexion MariaDB impossible : ' . $error->getMessage());
            }
            sleep(1);
        }
    } while (true);
}

function configuration(): array {
    require CONFIG;
    $db = $GLOBALS['CONFIG']['db'] ?? null;
    if (!is_array($db) || array_diff(['host', 'port', 'dbname', 'username', 'password'], array_keys($db))) {
        throw new RuntimeException('Configuration DB Jeedom incomplète.');
    }
    return $db;
}

function environmentConfiguration(array $db): array {
    foreach (['DB_HOST' => 'host', 'DB_PORT' => 'port', 'DB_NAME' => 'dbname', 'DB_USER' => 'username'] as $variable => $key) {
        $value = getenv($variable);
        if ($value !== false) {
            if ($value === '') {
                throw new RuntimeException($variable . ' ne doit pas être vide.');
            }
            $db[$key] = $value;
        }
    }
    if (!ctype_digit((string) $db['port']) || (int) $db['port'] < 1 || (int) $db['port'] > 65535) {
        throw new RuntimeException('Port MariaDB invalide.');
    }
    $password = getenv('DB_PASSWORD');
    $passwordFile = getenv('DB_PASSWORD_FILE');
    if ($password !== false && $passwordFile !== false) {
        throw new RuntimeException('Choisir DB_PASSWORD ou DB_PASSWORD_FILE.');
    }
    if ($passwordFile !== false) {
        $contents = $passwordFile !== '' ? @file_get_contents($passwordFile) : false;
        if ($contents === false) {
            throw new RuntimeException('Secret DB_PASSWORD_FILE illisible.');
        }
        $password = rtrim($contents, "\r\n");
    }
    if ($password !== false) {
        if ($password === '') {
            throw new RuntimeException('Le mot de passe DB fourni ne doit pas être vide.');
        }
        $db['password'] = $password;
    }
    if (getenv('DB_HOST') !== false || getenv('DB_PORT') !== false) {
        unset($db['socket']);
    }
    return $db;
}

function tableCount(PDO $pdo): int {
    return (int) $pdo->query('SELECT COUNT(*) FROM information_schema.tables WHERE table_schema = DATABASE()')->fetchColumn();
}

function checkInstallation(PDO $pdo): void {
    $version = $pdo->query("SELECT value FROM config WHERE plugin='core' AND `key`='version'")->fetchColumn();
    if (!$version || (int) $pdo->query("SELECT COUNT(*) FROM user WHERE profils='admin'")->fetchColumn() < 1) {
        throw new RuntimeException('Installation Jeedom incomplète : version ou administrateur absent.');
    }
}

function run(array $command): void {
    $process = proc_open($command, [0 => STDIN, 1 => STDOUT, 2 => STDERR], $pipes);
    if (!is_resource($process) || proc_close($process) !== 0) {
        throw new RuntimeException('Échec de la commande : ' . $command[0]);
    }
}

function writeConfiguration(array $db): void {
    // Conserver les autres entrées de configuration lors d'une bascule de base.
    $config = $GLOBALS['CONFIG'] ?? [];
    $config['db'] = $db;
    $debug = defined('DEBUG') ? DEBUG : 0;
    $contents = "<?php\ndefine('DEBUG', " . var_export($debug, true) . ");\nglobal \$CONFIG;\n\$CONFIG = " . var_export($config, true) . ";\n";
    $tmp = CONFIG . '.tmp';
    if (file_put_contents($tmp, $contents) === false) {
        throw new RuntimeException('Impossible de préparer la configuration DB.');
    }
    chmod($tmp, 0640);
    chown($tmp, 'www-data');
    chgrp($tmp, 'www-data');
    if (!rename($tmp, CONFIG)) {
        throw new RuntimeException('Impossible de sauvegarder la configuration DB.');
    }
    $GLOBALS['CONFIG'] = $config;
}

function initializeDatabase(): void {
    if (is_file(CONFIG)) {
        throw new RuntimeException('Stockage MariaDB manquant pour la configuration existante : restaurer la base.');
    }
    $db = environmentConfiguration(['host' => 'localhost', 'port' => 3306, 'dbname' => 'jeedom',
                                   'username' => 'jeedom', 'password' => bin2hex(random_bytes(24))]);
    $name = '`' . str_replace('`', '``', $db['dbname']) . '`';
    $user = "'" . str_replace("'", "''", $db['username']) . "'@'localhost'";
    $password = "'" . str_replace("'", "''", $db['password']) . "'";
    // L'installateur MariaDB exécute ce SQL avant le démarrage du serveur.
    // Aucun accès administrateur PDO ni secret root n'est nécessaire.
    $sql = tempnam('/run/jeedom', 'mariadb-');
    if ($sql === false) {
        throw new RuntimeException('Impossible de préparer le stockage MariaDB.');
    }
    try {
        $contents = "SET SESSION sql_mode = 'NO_BACKSLASH_ESCAPES';\nFLUSH PRIVILEGES;\n"
            . "CREATE DATABASE $name CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\n"
            . "CREATE USER $user IDENTIFIED BY $password;\n"
            . "GRANT ALL PRIVILEGES ON $name.* TO $user;\n";
        if (file_put_contents($sql, $contents) === false || !chgrp($sql, 'mysql') || !chmod($sql, 0640)) {
            throw new RuntimeException('Impossible de préparer les identifiants MariaDB.');
        }
        run(['/usr/bin/mariadb-install-db', '--user=mysql', '--datadir=/var/lib/mysql', '--skip-test-db',
             '--auth-root-authentication-method=socket', '--extra-file=' . $sql]);
        touch(INSTALLING);
        writeConfiguration($db);
    } finally {
        unlink($sql);
    }
}

try {
    $mode = $argv[1] ?? '';
    if ($mode === 'initialize-database') {
        initializeDatabase();
        exit(0);
    }
    if ($mode === 'host') {
        $db = is_file(CONFIG) ? configuration() : ['host' => 'localhost', 'port' => 3306];
        echo environmentConfiguration($db)['host'];
        exit(0);
    }
    if ($mode === 'check') {
        checkInstallation(connectDatabase(configuration(), false));
        exit(0);
    }
    if ($mode !== 'bootstrap') {
        throw new RuntimeException('Mode attendu : bootstrap, check ou host.');
    }

    $saved = is_file(CONFIG) ? configuration() : null;
    $db = environmentConfiguration($saved ?? ['host' => 'localhost', 'port' => 3306,
                                            'dbname' => 'jeedom', 'username' => 'jeedom', 'password' => '']);
    if ($saved === null) {
        if (getenv('JEEDOM_INTERNAL_DB') === 'true') {
            throw new RuntimeException('Base Jeedom existante sans configuration : restaurer common.config.php. Aucune donnée modifiée.');
        } else {
            if ($db['password'] === '') {
                throw new RuntimeException('Mot de passe obligatoire pour une base externe.');
            }
            $pdo = connectDatabase($db);
            if (tableCount($pdo) > 0) {
                throw new RuntimeException('Base externe non vide sans configuration : restauration nécessaire.');
            }
            touch(INSTALLING);
        }
        writeConfiguration($db);
    } else {
        $pdo = connectDatabase($db);
        if (!is_file(INSTALLING)) {
            // Une destination vide ou invalide ne doit pas remplacer la configuration persistante.
            checkInstallation($pdo);
        }
        if ($db !== $saved) {
            writeConfiguration($db);
            echo "Configuration DB mise à jour depuis les paramètres du conteneur.\n";
        }
    }

    if (is_file(INSTALLING)) {
        echo "Initialisation applicative avec l'installateur officiel du core.\n";
        // Le core exclut sudo de son contrôle de concurrence, mais compterait runuser et PHP.
        run(['/usr/bin/sudo', '-n', '-u', 'www-data', '--', '/usr/bin/php', HTML . '/install/install.php', 'mode=force']);
        checkInstallation($pdo);
        // Les horloges sont gérées par l'hôte ; le core conserve ses fonctions normales.
        require HTML . '/core/php/console.php';
        require HTML . '/core/php/core.inc.php';
        config::save('hardware_name', 'docker');
        config::save('disable_ntp', 1);
        unlink(INSTALLING);
    } else {
        echo "Installation existante : code et données conservés.\n";
    }

    run(['/usr/bin/supervisorctl', '-c', '/etc/supervisor/jeedom.conf', 'start', 'runtime:*']);
    touch('/run/jeedom/cron-heartbeat');
    chown('/run/jeedom/cron-heartbeat', 'www-data');
    touch('/run/jeedom/ready');
    echo "Jeedom prêt.\n";
} catch (Throwable $error) {
    fwrite(STDERR, 'Initialisation Jeedom impossible : ' . $error->getMessage() . "\n");
    exit(1);
}
