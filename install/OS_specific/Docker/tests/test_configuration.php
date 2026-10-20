<?php
// Tests ciblés sans service ni base : lancés sur l'image avant sa publication.
declare(strict_types=1);

$root = sys_get_temp_dir() . '/jeedom-config-test-' . bin2hex(random_bytes(8));
mkdir($root . '/core/config', 0755, true);
$source = getenv('JEEDOM_DATABASE_SOURCE') ?: '/usr/local/lib/jeedom/database.php';
$definitions = explode("\ntry {\n    \$mode =", file_get_contents($source), 2)[0];
$definitions = str_replace("const HTML = '/var/www/html';", 'const HTML = ' . var_export($root, true) . ';', $definitions);
eval(substr($definitions, 5));

$variables = ['DB_HOST', 'DB_PORT', 'DB_NAME', 'DB_USER', 'DB_PASSWORD', 'DB_PASSWORD_FILE'];
$original = [];
foreach ($variables as $variable) {
    $original[$variable] = getenv($variable);
    putenv($variable);
}
$checks = 0;
function ensure(bool $condition, string $message): void {
    global $checks;
    if (!$condition) {
        throw new RuntimeException($message);
    }
    $checks++;
}
function rejected(array $db, string $message): void {
    try {
        environmentConfiguration($db);
    } catch (RuntimeException $error) {
        ensure(str_contains($error->getMessage(), $message), $error->getMessage());
        return;
    }
    throw new RuntimeException('Configuration invalide acceptée : ' . $message);
}

try {
    $saved = ['host' => 'localhost', 'port' => 3306, 'dbname' => 'jeedom',
              'username' => 'jeedom', 'password' => 'saved-password'];
    ensure(environmentConfiguration($saved) === $saved, 'Sans variable, conserver la configuration.');

    putenv('DB_HOST=mariadb.external');
    $db = environmentConfiguration($saved);
    ensure($db['host'] === 'mariadb.external', 'DB_HOST doit primer sur localhost.');
    ensure($db['password'] === $saved['password'] && $db['username'] === $saved['username'], 'Conserver les champs non fournis.');
    putenv('DB_PORT=3307');
    putenv('DB_NAME=migrated');
    putenv('DB_USER=migrated-user');
    putenv('DB_PASSWORD=new-password');
    $db = environmentConfiguration($saved + ['socket' => '/old/socket']);
    ensure($db === ['host' => 'mariadb.external', 'port' => '3307', 'dbname' => 'migrated',
                    'username' => 'migrated-user', 'password' => 'new-password'], 'Tous les champs fournis doivent primer.');

    putenv('DB_PASSWORD=0');
    ensure(environmentConfiguration($saved)['password'] === '0', 'Le mot de passe 0 est une chaîne valide.');
    putenv('DB_PASSWORD');
    file_put_contents($root . '/password', "secret-one\r\n");
    putenv('DB_PASSWORD_FILE=' . $root . '/password');
    ensure(environmentConfiguration($saved)['password'] === 'secret-one', 'Le secret fichier doit primer.');
    file_put_contents($root . '/password', "secret-two\n");
    ensure(environmentConfiguration($saved)['password'] === 'secret-two', 'Relire le secret à chaque résolution.');
    putenv('DB_PASSWORD=conflict');
    rejected($saved, 'Choisir DB_PASSWORD ou DB_PASSWORD_FILE');
    putenv('DB_PASSWORD');
    putenv('DB_PASSWORD_FILE=' . $root . '/absent');
    rejected($saved, 'illisible');
    putenv('DB_PASSWORD_FILE=' . $root . '/password');
    file_put_contents($root . '/password', "\n");
    rejected($saved, 'ne doit pas être vide');
    putenv('DB_PASSWORD_FILE');
    putenv('DB_PASSWORD=');
    rejected($saved, 'ne doit pas être vide');
    putenv('DB_PASSWORD');
    putenv('DB_PORT=invalid');
    rejected($saved, 'Port MariaDB invalide');
    putenv('DB_PORT=70000');
    rejected($saved, 'Port MariaDB invalide');
    putenv('DB_PORT');
    putenv('DB_HOST=');
    rejected($saved, 'DB_HOST ne doit pas être vide');
    putenv('DB_HOST=localhost');
    ensure(environmentConfiguration($db)['host'] === 'localhost', 'La sélection peut aussi revenir à une base interne.');

    $GLOBALS['CONFIG'] = ['db' => $saved, 'custom' => ['keep' => true]];
    define('DEBUG', 1);
    writeConfiguration($db);
    $contents = file_get_contents(CONFIG);
    ensure(str_contains($contents, "define('DEBUG', 1)"), 'Conserver DEBUG.');
    ensure(str_contains($contents, "'custom' =>"), 'Conserver les autres clés de CONFIG.');
    ensure(str_contains($contents, "'host' => 'mariadb.external'"), 'Persister la base résolue pour le core.');
    ensure($GLOBALS['CONFIG']['db'] === $db, 'Mettre aussi à jour la configuration du processus courant.');
    ensure((fileperms(CONFIG) & 0777) === 0640, 'La configuration doit rester privée.');
    ensure(!is_file(CONFIG . '.tmp'), 'Le remplacement atomique doit supprimer le temporaire.');
    echo "$checks vérifications de configuration DB réussies.\n";
} finally {
    foreach ($original as $variable => $value) {
        putenv($value === false ? $variable : $variable . '=' . $value);
    }
    foreach ([CONFIG, CONFIG . '.tmp', $root . '/password'] as $file) {
        if (is_file($file)) {
            unlink($file);
        }
    }
    rmdir($root . '/core/config');
    rmdir($root . '/core');
    rmdir($root);
}
