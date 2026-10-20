<?php
// Standalone: php tests/compatibility/databaseCliTest.php (no live database needed).
require_once __DIR__ . '/../../core/class/DB.class.php';
function expectSame($expected, $actual, $message) {
    if ($expected !== $actual) {
        throw new RuntimeException($message . ': ' . var_export($actual, true));
    }
}
$originalPath = getenv('PATH');
$directory = sys_get_temp_dir() . '/jeedom-cli-' . bin2hex(random_bytes(8));
mkdir($directory, 0700);
try {
    putenv('PATH=' . $directory);
    $tools = array('client' => array('mariadb', 'mysql'), 'dump' => array('mariadb-dump', 'mysqldump'), 'check' => array('mariadb-check', 'mysqlcheck'));
    foreach ($tools as $tool => $names) {
        foreach (array_reverse($names) as $name) {
            file_put_contents($directory . '/' . $name, "#!/bin/sh\nprintf '%s\\n' \"\$@\"\n");
            chmod($directory . '/' . $name, 0700);
            expectSame(escapeshellarg($directory . '/' . $name), DB::getClientCommand($tool), 'Client selection for ' . $tool);
        }
    }
    global $CONFIG;
    $CONFIG['db'] = array('host' => 'localhost', 'port' => 3306, 'username' => "user with space", 'password' => "quo'te; \$(echo INJECTED) &", 'dbname' => 'test database');
    $output = array();
    exec(DB::getClientCommand() . ' ' . DB::getClientConnectionArguments(), $output, $rc);
    expectSame(0, $rc, 'Execute client');
    expectSame(array('--user=' . $CONFIG['db']['username'], '--password=' . $CONFIG['db']['password'], $CONFIG['db']['dbname']), $output, 'Shell argument boundaries');
    $CONFIG['db']['host'] = 'db.example';
    $CONFIG['db']['port'] = 3307;
    $output = array();
    exec(DB::getClientCommand() . ' ' . DB::getClientConnectionArguments(), $output, $rc);
    expectSame('--host=db.example', $output[0], 'Remote host');
    expectSame('--port=3307', $output[1], 'Remote port');
    $CONFIG['db']['unix_socket'] = '/tmp/socket with space';
    $output = array();
    exec(DB::getClientCommand() . ' ' . DB::getClientConnectionArguments(), $output, $rc);
    expectSame('--socket=/tmp/socket with space', $output[0], 'Unix socket');
    expectSame(4, count($output), 'Socket overrides host and port');
    try {
        DB::getClientCommand('client; false');
        throw new RuntimeException('Invalid tool accepted');
    } catch (InvalidArgumentException $expected) {
    }
    foreach (glob($directory . '/*') as $file) {
        unlink($file);
    }
    try {
        DB::getClientCommand('dump');
        throw new LogicException('Missing client accepted');
    } catch (RuntimeException $expected) {
        if ($expected instanceof LogicException) {
            throw $expected;
        }
    }
    echo "PASS: native clients, legacy fallback, quoting, TCP, socket, invalid and missing clients\n";
} finally {
    putenv('PATH=' . $originalPath);
    foreach (glob($directory . '/*') as $file) {
        unlink($file);
    }
    rmdir($directory);
}
