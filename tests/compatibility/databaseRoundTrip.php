<?php
// For a disposable Jeedom instance only. Called by databaseRoundTrip.sh.
if (getenv('JEEDOM_COMPATIBILITY_TEST') !== '1') {
    throw new RuntimeException('Set JEEDOM_COMPATIBILITY_TEST=1 on a disposable test instance.');
}
require_once __DIR__ . '/../../core/php/core.inc.php';
$action = $argv[1] ?? '';
$expected = "Debian 13 — été, apostrophe ' et caractères € 漢字";
$pdo = DB::getConnection();
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
switch ($action) {
    case 'seed':
        $pdo->exec('CREATE TABLE __jeedom_portage_test (id INT PRIMARY KEY, value TEXT) CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
        $pdo->prepare('INSERT INTO __jeedom_portage_test VALUES (1, ?)')->execute(array($expected));
        config::save('__portage_roundtrip', $expected);
        break;
    case 'mutate':
        $pdo->exec('DROP TABLE __jeedom_portage_test');
        config::save('__portage_roundtrip', 'changed after backup');
        break;
    case 'verify':
        if ($pdo->query('SELECT value FROM __jeedom_portage_test WHERE id=1')->fetchColumn() !== $expected
            || config::byKey('__portage_roundtrip') !== $expected) {
            throw new RuntimeException('Backup/restore did not preserve the table and configuration.');
        }
        echo "PASS: full Jeedom restore preserved database table, configuration and UTF-8 values\n";
        break;
    case 'cleanup':
        $pdo->exec('DROP TABLE __jeedom_portage_test');
        config::remove('__portage_roundtrip');
        break;
    default:
        throw new InvalidArgumentException('Expected seed, mutate, verify or cleanup.');
}
