<?php
require_once __DIR__ . '/../core/class/system.class.php';

function init($_name, $_default = '') {
	if (isset($_GET[$_name])) {
		$cache[$_name] = $_GET[$_name];
		return $_GET[$_name];
	}

	if (isset($_POST[$_name])) {
		$cache[$_name] = $_POST[$_name];
		return $_POST[$_name];
	}

    return $_REQUEST[$_name] ?? $_default;
}

function getTmpKey()
{
    if (file_exists('/tmp/jeedom_tmp_key')) {
        return file_get_contents('/tmp/jeedom_tmp_key');
    }

    $tmp_key = '';
    $chaine = 'abcdefghijklmnpqrstuvwxy1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ';
    mt_srand((double) microtime() * 1000000);
    for ($i = 0; $i < 50; $i++) {
        $tmp_key .= $chaine[mt_rand() % strlen($chaine)];
    }
    file_put_contents('/tmp/jeedom_tmp_key', $tmp_key);

    return $tmp_key;
}

function getErrors() {
    $needpackages = ['unzip', 'curl', 'ntp'];
    $needphpextensions = ['curl', 'json', 'mysql', 'gd'];
    $loadExtensions = get_loaded_extensions();

    $errors = [];
    if (PHP_VERSION_ID < 70100) {
        $errors[] = ['danger', 'Jeedom nécessite PHP 7.1 ou plus (actuellement : ' . PHP_VERSION . ')'];
    }

    if (!file_exists('/etc/cron.d/jeedom')) {
        $errors[] = [
            'warning',
            'Veuillez ajouter une ligne crontab pour Jeedom (si Jeedom n\'a pas les droits sudo, cette erreur est normale) : ',
            'sudo su -'.PHP_EOL.'echo "* * * * * ' . get_current_user() . ' /usr/bin/php ' . dirname(__DIR__) . '/core/php/jeeCron.php >> /dev/null" > /etc/cron.d/jeedom'
        ];
    }

    $cmdInstall = trim(system::get('cmd_install'));
    foreach ($needpackages as $needpackage) {
        if (shell_exec(system::get('cmd_check') . $needpackage . ' | wc -l') == 0) {
            $errors[] = [
                'warning',
                'Jeedom nécessite le paquet ' . $needpackage . ' . Veuillez faire, en SSH : ',
                'sudo su -'.PHP_EOL. $cmdInstall . $needpackage
            ];
        }
    }

    foreach ($needphpextensions as $needphpextension) {
        if (in_array($needphpextension, $loadExtensions, true)) {
            break;
        }
        $errors[] = [
            'warning',
            'Jeedom nécessite l\'extension PHP ' . $needphpextension . ' . Veuillez faire, en SSH : ',
            'sudo su -'.PHP_EOL
            . $cmdInstall . ' php-' . $needphpextension . PHP_EOL
            . 'systemctl reload php-fpm <strong>or</strong> systemctl reload apache2',
        ];
    }

    $phpIniFile = php_ini_loaded_file();
    $maxExecutionTime = ini_get('max_execution_time');
    if ($maxExecutionTime < 600) {
        $errors[] = [
            'warning',
            'max_execution_time must be >= 600, edit ' . $phpIniFile . ' and change this value (current ' . $maxExecutionTime . ')',
        ];
    }

    $uploadMaxFilesize = ini_get('upload_max_filesize');
    if ($uploadMaxFilesize !== '1G' && $uploadMaxFilesize !== '1024M') {
        $errors[] = [
            'warning',
            'upload_max_filesize must be = 1G, edit ' . $phpIniFile . ' and change this value (current ' . $uploadMaxFilesize . ')',
        ];
    }

    $postMaxSize = ini_get('post_max_size');
    if ($postMaxSize !== '1G' && $postMaxSize !== '1024M') {
        $errors[] = [
            'warning',
            'post_max_size must be = 1G, edit ' . $phpIniFile . ' and change this value (current ' . $postMaxSize . ')',
        ];
    }

    return $errors;
}

function install()
{
    $tmp_key = getTmpKey();
    if (init('log') == 1) {
        if ($tmp_key != init('key')) {
            throw new Exception('No temporary key found.');
        }

        return file_get_contents(dirname(__DIR__) . '/log/jeedom_installation');
    }

    $configPath = dirname(__DIR__) . '/core/config';
    if (file_exists($configPath . '/common.config.php')) {
        throw new Exception('No common configuration found.');
    }

    $errors = getErrors();
    $templatePath = dirname(__DIR__) . '/templates';
    if (!empty($errors)) {
        return include $templatePath . '/install/setup_errors.phtml';
    }

    $config = true;

    if (init('hostname') != '' && init('username') != '' && init('password') != '') {
        try {
            $opt = [
                PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
            ];
            if (init('database') == '') {
                $_POST['database'] = 'jeedom';
            }
            $dsn = 'mysql:host=' . init('hostname') . ';port=' . init('port', '3306') . ';charset=utf8';
            $pdo = new PDO($dsn, init('username'), init('password'), $opt);
            $pdo->exec('CREATE DATABASE IF NOT EXISTS `' . init('database') . '`');
            $dsn .= ';dbname=' . init('database');
            $pdo = new PDO($dsn, init('username'), init('password'), $opt);
            $config = false;
            if (init('eraseDatabase') === 'on') {
                $pdo->exec('SET foreign_key_checks = 0');
                $tables = [];
                $result = $pdo->query('SHOW TABLES');
                while ($row = $result->fetch(PDO::FETCH_NUM)) {
                    $tables[] = $row[0];
                }

                foreach ($tables as $table) {
                    $pdo->exec("DROP TABLE `{$table}`");
                }
            }
        } catch (Exception $e) {
            $errors[] = [
                'danger',
                'Unable to connect to database',
                $e->getMessage()
            ];
        }
    }

    if ($config) {
        return include $templatePath . '/install/setup_config.phtml';
    }

    shell_exec('sudo chmod 775 -R ' . dirname(__DIR__) . '/*');
    shell_exec(
        'sudo chown ' . system::get('www-uid') . ':' . system::get('www-gid') . ' -R ' . dirname(__DIR__) . '/*'
    );

    if (!is_writable($configPath)) {
        $errors[] = [
            'danger',
            'Le dossier ' . $configPath . ' doit être accessible en écriture.'
        ];

        return include $templatePath . '/install/setup_errors.phtml';
    }
    $replace = [
        '#PASSWORD#' => init('password'),
        '#DBNAME#' => init('database'),
        '#USERNAME#' => init('username'),
        '#PORT#' => init('port'),
        '#HOST#' => init('hostname'),
    ];
    $config = str_replace(
        array_keys($replace),
        $replace,
        file_get_contents($configPath . '/common.config.sample.php')
    );
    file_put_contents($configPath . '/common.config.php', $config);
    shell_exec(
        'php ' . __DIR__ . '/install.php mode=force > ' . dirname(__DIR__) . '/log/jeedom_installation 2>&1 &'
    );

    return include $templatePath . '/install/setup_install.phtml';
}

try {
    echo install();
} catch (Exception $exception) {
    if (!headers_sent()) {
        header('Statut: 404 Page non trouvée');
        header('HTTP/1.0 404 Not Found');
    }
    $_SERVER['REDIRECT_STATUS'] = 404;
    echo '<h1>404 Non trouvé</h1>';
    echo 'La page que vous demandez ne peut être trouvée.';
}
