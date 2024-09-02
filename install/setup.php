<?php

require_once __DIR__ . '/../core/class/system.class.php';

function init(string $name, $default = null) {
    return $_GET[$name] ?? $_POST[$name] ?? $_REQUEST[$name] ?? $default;
}

function generateKey(): string
{
    $keyFilename = '/tmp/jeedom_tmp_key';
    if (file_exists($keyFilename)) {
        $key = file_get_contents($keyFilename);
        if (false !== $key) {
            return $key;
        }
    }

    $tmp_key = '';
    $chaine = "abcdefghijklmnpqrstuvwxy1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ";
    srand((double)microtime() * 1000000);

    $max = strlen($chaine);
    for ($i = 0; $i < 50; $i++) {
        $tmp_key .= $chaine[random_int(0, $max)];
    }
    file_put_contents($keyFilename, $tmp_key);

    return $tmp_key;
}

function sendNotFound(): void
{
    if (!headers_sent()) {
        header("Statut: 404 Page non trouvée");
        header('HTTP/1.0 404 Not Found');
    }
    $_SERVER['REDIRECT_STATUS'] = 404;
    echo "<h1>404 Non trouvé</h1>";
    echo "Cannot find requested page.";
}

function checkPhpVersion(array &$errorMessages): bool
{
    if (!version_compare(PHP_VERSION, '7', '<')) {
        return false;
    }

    $errorMessages[] = [
        'message' => 'Jeedom requires PHP 7.X or up (current : ' . PHP_VERSION . ')'
    ];

    return true;
}

function checkCrontab(string $rootPath, array &$errorMessages): bool
{
    $cronPath = '/etc/cron.d/jeedom';
    if (file_exists($cronPath)) {
        return false;
    }

    $currentUser = get_current_user();
    $jeeCronPath = $rootPath . '/core/php/jeeCron.php';
    $errorMessages[] = [
        'message' => 'Please add a crontab line for Jeedom (if Jeedom does not have sudo rights, this error is normal) :',
        'code' => <<<SHELL
sudo su -
echo "* * * * * {$currentUser} /usr/bin/php {$jeeCronPath} >> /dev/null" > {$cronPath}
SHELL,
];

    return false; // Non-blocking error
}

function checkNeededPackages(array &$errorMessages): bool
{
    $error = false;
    $needPackages = ['unzip', 'curl', 'ntp'];
    foreach ($needPackages as $needpackage) {
        if (shell_exec(system::get('cmd_check') . $needpackage . ' | wc -l') != 0) {
            continue;
        }

        $command = system::get('cmd_install') . $needpackage;
        $errorMessages[] = [
            'message' => 'Please install package ' . $needpackage . ' . Run SSH cmd : ',
            'code' => <<<SHELL
sudo su -
{$command}
SHELL
        ];
        $error = true;
    }

    return $error;
}

function checkMissingExtensions(array &$errorMessages): bool
{
    $missingExtensions = array_diff(['curl', 'json', 'pdo_mysql', 'gd'], get_loaded_extensions());
    if ([] === $missingExtensions) {
        return false;
    }

    foreach ($missingExtensions as $missingExtension) {
        $command = system::get('cmd_install') . ' php-' . $missingExtension;
        $errorMessages[] = [
                'message' => 'Jeedom requires PHP extension ' . $missingExtension . ' . Run SSH cmd : ',
                'code' => <<<SHELL
sudo su -
{$command}
systemctl reload php-fpm <strong>or</strong> systemctl reload apache2
SHELL
        ];
    }

    return true;
}

function checkExecutionTime(array &$errorMessages): bool
{
    $maxExecutionTime = ini_get('max_execution_time');
    if ($maxExecutionTime >= 600) {
        return false;
    }

    $errorMessages[] = [
        'message' => 'max_execution_time must be >= 600, edit ' . php_ini_loaded_file() . ' and change this value (current ' . $maxExecutionTime . ')'
    ];

    return true;
}

function checkUploadMaxFilesize(array &$errorMessages): bool
{
    $uploadMaxFilesize = ini_get('upload_max_filesize');
    if ($uploadMaxFilesize === '1G' || $uploadMaxFilesize === '1024M') {
        return false;
    }

    $errorMessages[] = [
        'message' => 'upload_max_filesize must be = 1G, edit ' . php_ini_loaded_file() . ' and change this value (current ' . $uploadMaxFilesize . ')',
    ];

    return true;
}

function checkPostMaxSize(array &$errorMessages): bool
{
    $postMaxSize = ini_get('post_max_size');
    if ($postMaxSize === '1G' || $postMaxSize === '1024M') {
        return false;
    }

    $errorMessages[] = [
            'message' => 'post_max_size must be = 1G, edit ' . php_ini_loaded_file() . ' and change this value (current ' . $postMaxSize . ')',
    ];

    return true;
}

function checkErrors(string $rootPath, array &$errorMessages): bool
{
    $errors = checkPhpVersion($errorMessages);
    $errors = checkCrontab($rootPath, $errorMessages) || $errors;
    $errors = checkNeededPackages($errorMessages) || $errors;
    $errors = checkMissingExtensions($errorMessages) || $errors;
    $errors = checkExecutionTime($errorMessages) || $errors;
    $errors = checkUploadMaxFilesize($errorMessages) || $errors;
    $errors = checkPostMaxSize($errorMessages) || $errors;

    return $errors;
}

function initializeDatabase(array $databaseConfiguration, array &$errorMessages): bool
{
    $hostname = $databaseConfiguration['hostname'];
    $username = $databaseConfiguration['username'];
    $password = $databaseConfiguration['password'];
    $databaseName = $databaseConfiguration['databaseName'];
    $port = $databaseConfiguration['port'];
    if ($hostname === '' || $username === '' || $password === '' || $port === 0 || $databaseName === '') {
        return false;
    }

    $config = false;
    try {
        $opt = [
            PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        ];

        $dsn = sprintf('mysql:host=%s;port=%u;charset=%s', $hostname, $port, 'utf8');
        $pdo = new PDO($dsn, $username, $password, $opt);
        $pdo->query("CREATE DATABASE IF NOT EXISTS `{$databaseName}`");
        $dsn .= ";dbname=" . $databaseName;
        $pdo = new PDO($dsn, $username, $password, $opt);
        $config = true;
        if (init('eraseDatabase') === 'on') {
            $pdo->query('SET foreign_key_checks = 0');
            $tables = [];
            $result = $pdo->query('SHOW TABLES');
            while ($row = $result->fetch(PDO::FETCH_NUM)) {
                $tables[] = $row[0];
            }

            foreach ($tables as $table) {
                $pdo->query("DROP TABLE `{$table}`");
            }
        }
    } catch (Exception $e) {
        $errorMessages[] = [
            'level' => 'danger',
            'message' => 'Unable to connect to database',
            'code' => $e->getMessage(),
        ];
    }

    return $config;
}

function writeDatabaseConfiguration(array $databaseConfiguration, string $rootPath, array &$errorMessages): bool
{
    shell_exec('sudo chmod 775 -R ' . $rootPath . '/*');
    shell_exec('sudo chown ' . system::get('www-uid') . ':' . system::get('www-gid') . ' -R ' . $rootPath . '/*');
    if (!is_writable($rootPath . '/core/config')) {
        $errorMessages[] = [
            'level' => 'danger',
            'message' => 'Folder ' . $rootPath . '/core/config' . ' must have write access',
        ];

        return true;
    }

    $replace = [
        '#PASSWORD#' => $databaseConfiguration['password'],
        '#DBNAME#' => $databaseConfiguration['databaseName'],
        '#USERNAME#' => $databaseConfiguration['username'],
        '#PORT#' => $databaseConfiguration['port'],
        '#HOST#' => $databaseConfiguration['hostname'],
    ];
    $config = str_replace(array_keys($replace), $replace, file_get_contents($rootPath . '/core/config/common.config.sample.php'));
    file_put_contents($rootPath . '/core/config/common.config.php', $config);
    shell_exec('php ' . $rootPath . '/install/install.php mode=force > ' . $rootPath . '/log/jeedom_installation 2>&1 &');

    return false;
}


$tmp_key = generateKey();
$rootPath = dirname(__DIR__);

if (init('log') === '1') {
	if ($tmp_key !== init('key')) {
        return sendNotFound();
    }
	echo file_get_contents($rootPath . '/log/jeedom_installation');
	return;
}

if (file_exists($rootPath . '/core/config/common.config.php')) {
    return sendNotFound();
}

$errorMessages = [];
$error = checkErrors($rootPath, $errorMessages);
$databaseConfiguration = [
    'hostname' => init('hostname', 'localhost'),
    'username' => init('username', 'root'),
    'password' => init('password', ''),
    'databaseName' => init('database', 'jeedom'),
    'port' => (int) (init('port', '3306')),
];
$databaseInitialized = initializeDatabase($databaseConfiguration, $errorMessages);
if ($databaseInitialized) {
    $error = writeDatabaseConfiguration($databaseConfiguration, $rootPath, $errorMessages) || $error;
}

?><!DOCTYPE html>
<html>
<head>
	<title>Jeedom Installation</title>
	<script src="/3rdparty/jquery/jquery.min.js"></script>
	<script src="/3rdparty/bootstrap/bootstrap.min.js"></script>
	<link href="/3rdparty/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<link href="/3rdparty/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
	<center>
		<img src="/core/img/logo-jeedom-grand-nom-couleur-460x320.png" class="img-responsive" />
	</center>
    <?php foreach ($errorMessages as $errorMessage): ?>
        <div class="alert alert-<?php echo $errorMessage['level'] ?? 'warning' ?>" style="margin:15px;">
            <center style="font-size:1.2em;"><?php echo $errorMessage['message']; ?></center>
            <?php if (isset($errorMessage['code'])): ?>
                <pre><?php echo $errorMessage['code']; ?></pre>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>

    <?php if (!$error): ?>
        <?php if (!$databaseInitialized) : ?>
		<form class="form-horizontal" action="setup.php" method="POST">
			<div class="form-group">
				<label class="col-sm-5 control-label">Database hostname</label>
				<div class="col-sm-2">
					<input type="text" class="form-control" id="hostname" name="hostname" value="<?php echo $databaseConfiguration['hostname'] ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Database port</label>
				<div class="col-sm-2">
					<input type="text" class="form-control" id="port" name="port" value="<?php echo $databaseConfiguration['port'] ?>"  />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Database username</label>
				<div class="col-sm-2">
					<input type="text" class="form-control" id="username" name="username" value="<?php echo $databaseConfiguration['username'] ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Database password</label>
				<div class="col-sm-2">
					<input type="password" class="form-control" id="password" name="password" value="<?php echo $databaseConfiguration['password'] ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Database name</label>
				<div class="col-sm-2">
					<input type="text" class="form-control" id="database" name="database" value="<?php echo $databaseConfiguration['databaseName'] ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Erase database</label>
				<div class="col-sm-2">
					<input type="checkbox" id="eraseDatabase" name="eraseDatabase" />
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-5 col-sm-10">
					<input type="submit" class="btn btn-primary btn-lg" value="Proceed">
				</div>
			</div>
		</form>
		<?php else : ?>
        <div id="div_alertMessage" class="alert alert-warning" style="margin:15px;">
            <center style="font-size:1.2em;"><i class="fa fa-spinner fa-spin"></i> The installation jeedom is ongoing.</center>
        </div>
    	<pre id="pre_installLog" style="margin:15px;"></pre>
			<script>
				function loadLog() {
					fetch("setup.php?log=1&key=<?php echo $tmp_key ?>").then((response) => response.text()).then(function(data) {
                        document.getElementById('pre_installLog').innerHTML = data
                        if (data.indexOf('[END INSTALL SUCCESS]') > 0) {
							document.getElementById('div_alertMessage').removeClass('alert-warning').addClass('alert-success')
							document.getElementById('div_alertMessage').innerHTML = '<center style="font-size:1.2em;"><i class="fas fa-check"></i> Jeedom successfully install. Login is <strong>admin</strong>, password is <strong>admin</strong>. Click <a href="../index.php">here</a> for connection</center>'
							return
						}
						if (data.indexOf('[END INSTALL ERROR]') > 0) {
							document.getElementById('div_alertMessage').removeClass('alert-warning').addClass('alert-danger')
							document.getElementById('div_alertMessage').innerHTML = '<center style="font-size:1.2em;"><i class="fas fa-times"></i> Error on installation, please read the log.</center>'
                            return;
						}
                        setTimeout(() => {
                            loadLog()
                        }, 1000)
					})
				}
				loadLog()
			</script>
        <?php endif; ?>
    <?php endif; ?>
	</body>
</html>
