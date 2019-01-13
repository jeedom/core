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
	if (isset($_REQUEST[$_name])) {
		return $_REQUEST[$_name];
	}
	return $_default;
}

if (!file_exists('/tmp/jeedom_tmp_key')) {
	$tmp_key = '';
	$chaine = "abcdefghijklmnpqrstuvwxy1234567890ABCDEFGHIJKLMNOPQRSTUVWXYZ";
	srand((double) microtime() * 1000000);
	for ($i = 0; $i < 50; $i++) {
		$tmp_key .= $chaine[rand() % strlen($chaine)];
	}
	file_put_contents('/tmp/jeedom_tmp_key', $tmp_key);
} else {
	$tmp_key = file_get_contents('/tmp/jeedom_tmp_key');
}

if (init('log') == 1) {
	if ($tmp_key != init('key')) {
		if (!headers_sent()) {
			header("Statut: 404 Page non trouvée");
			header('HTTP/1.0 404 Not Found');
		}
		$_SERVER['REDIRECT_STATUS'] = 404;
		echo "<h1>404 Non trouvé</h1>";
		echo "La page que vous demandez ne peut être trouvée.";
		dei();
	}
	echo file_get_contents(__DIR__ . '/../log/jeedom_installation');
	die();
}
if (file_exists(__DIR__ . '/../core/config/common.config.php')) {
	if (!headers_sent()) {
		header("Statut: 404 Page non trouvée");
		header('HTTP/1.0 404 Not Found');
	}
	$_SERVER['REDIRECT_STATUS'] = 404;
	echo "<h1>404 Non trouvé</h1>";
	echo "La page que vous demandez ne peut être trouvée.";
	exit();
}
$needpackages = array('unzip', 'curl', 'ntp');
$needphpextensions = array('curl', 'json', 'mysql', 'gd');
$loadExtensions = get_loaded_extensions();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Jeedom Installation</title>
	<script src="../3rdparty/jquery/jquery.min.js"></script>
	<script src="../3rdparty/bootstrap/bootstrap.min.js"></script>
	<link href="../3rdparty/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<link href="../3rdparty/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
	<center>
		<img src="../core/img/logo-jeedom-grand-nom-couleur-460x320.png" class="img-responsive" />
	</center>
	<?php
$error = false;
if (version_compare(PHP_VERSION, '5.6.0', '<')) {
	$error = true;
	echo '<div class="alert alert-danger" style="margin:15px;">';
	echo '<center style="font-size:1.2em;">Jeedom nécessite PHP 5.6 ou plus (actuellement : ' . PHP_VERSION . ')</center>';
	echo '</div>';
}
if (!file_exists('/etc/cron.d/jeedom')) {
	echo '<div class="alert alert-warning" style="margin:15px;">';
	echo '<center style="font-size:1.2em;">Veuillez ajouter une ligne crontab pour Jeedom (si Jeedom n\'a pas les droits sudo, cette erreur est normale) : </center>';
	echo '<pre>';
	echo "sudo su -\n";
	echo 'echo "* * * * * ' . get_current_user() . ' /usr/bin/php ' . dirname(__DIR__) . '/core/php/jeeCron.php >> /dev/null" > /etc/cron.d/jeedom';
	echo '</pre>';
	echo '</div>';
}

foreach ($needpackages as $needpackage) {
	if (shell_exec(system::get('cmd_check') . $needpackage . ' | wc -l') == 0) {
		$error = true;
		echo '<div class="alert alert-warning" style="margin:15px;">';
		echo '<center style="font-size:1.2em;">Jeedom nécessite le paquet ' . $needpackage . ' . Veuillez faire, en SSH : </center>';
		echo '<pre>';
		echo "sudo su -\n";
		echo system::get('cmd_install') . $needpackage;
		echo '</pre>';
		echo '</div>';
	}
}
foreach ($needphpextensions as $needphpextension) {
	foreach ($loadExtensions as $extension) {
		if ($extension == $needphpextension) {
			break 2;
		}
	}
	$error = true;
	echo '<div class="alert alert-warning" style="margin:15px;">';
	echo '<center style="font-size:1.2em;">Jeedom nécessite l\'extension PHP ' . $needphpextension . ' . Veuillez faire, en SSH : </center>';
	echo '<pre>';
	echo "sudo su -\n";
	echo system::get('cmd_install') . ' php5-' . $needphpextension . "\n";
	echo 'systemctl reload php5-fpm <strong>or</strong> systemctl reload apache2';
	echo '</pre>';
	echo '</div>';
}
if (ini_get('max_execution_time') < 600) {
	$error = true;
	echo '<div class="alert alert-warning" style="margin:15px;">';
	echo '<center style="font-size:1.2em;">max_execution_time must be >= 600, edit ' . php_ini_loaded_file() . ' and change this value (current ' . ini_get('max_execution_time') . ')</center>';
	echo '</div>';
}
if (ini_get('upload_max_filesize') != '1G' && ini_get('upload_max_filesize') != '1024M') {
	$error = true;
	echo '<div class="alert alert-warning" style="margin:15px;">';
	echo '<center style="font-size:1.2em;">upload_max_filesize must be = 1G, edit ' . php_ini_loaded_file() . ' and change this value (current ' . ini_get('upload_max_filesize') . ')</center>';
	echo '</div>';
}
if (ini_get('post_max_size') != '1G' && ini_get('post_max_size') != '1024M') {
	$error = true;
	echo '<div class="alert alert-warning" style="margin:15px;">';
	echo '<center style="font-size:1.2em;">post_max_size must be = 1G, edit ' . php_ini_loaded_file() . ' and change this value (current ' . ini_get('post_max_size') . ')</center>';
	echo '</div>';
}
if ($error) {
	echo '</body>';
	echo '</html>';
	die();
}
$config = true;
if (init('hostname') != '' && init('username') != '' && init('password') != '') {
	try {
		$opt = array(
			PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
		);
		if (init('database') == '') {
			$_POST['database'] = 'jeedom';
		}
		$dsn = "mysql:host=" . init('hostname') . ";port=" . init('port', '3306') . ";charset=utf8";
		$pdo = new PDO($dsn, init('username'), init('password'), $opt);
		$sql = $pdo->prepare("CREATE DATABASE IF NOT EXISTS `" . init('database') . "`");
		$sql->execute();
		$dsn .= ";dbname=" . init('database');
		$pdo = new PDO($dsn, init('username'), init('password'), $opt);
		$config = false;
		if (init('eraseDatabase') == 'on') {
			$sql = $pdo->prepare("SET foreign_key_checks = 0");
			$sql->execute();
			$tables = array();
			$result = $pdo->query("SHOW TABLES");
			while ($row = $result->fetch(PDO::FETCH_NUM)) {
				$tables[] = $row[0];
			}

			foreach ($tables as $table) {
				$sql = $pdo->prepare("DROP TABLE `$table`");
				$sql->execute();
			}
		}
	} catch (Exception $e) {
		echo '<div class="alert alert-danger" style="margin:15px;">';
		echo '<center style="font-size:1.2em;">Unable to connect to database</center>';
		echo '<pre>';
		echo $e->getMessage();
		echo '</pre>';
		echo '</div>';
	}
}

if ($config) {
	?>
		<form class="form-horizontal" action="setup.php" method="POST">
			<div class="form-group">
				<label class="col-sm-5 control-label">Database hostname</label>
				<div class="col-sm-2">
					<input type="text" class="form-control" id="hostname" name="hostname" value="<?php echo init('hostname', 'localhost') ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Database port</label>
				<div class="col-sm-2">
					<input type="text" class="form-control" id="port" name="port" value="<?php echo init('port', '3306') ?>"  />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Database username</label>
				<div class="col-sm-2">
					<input type="text" class="form-control" id="username" name="username" value="<?php echo init('username', 'root') ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Database password</label>
				<div class="col-sm-2">
					<input type="password" class="form-control" id="password" name="password" value="<?php echo init('password') ?>" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Database name</label>
				<div class="col-sm-2">
					<input type="text" class="form-control" id="database" name="database" value="<?php echo init('database', 'jeedom') ?>" />
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
		<?php } else {
	shell_exec('sudo chmod 775 -R ' . __DIR__ . '/../*');
	shell_exec('sudo chown ' . system::get('www-uid') . ':' . system::get('www-gid') . ' -R ' . __DIR__ . '/../*');
	if (!is_writable(__DIR__ . '/../core/config')) {
		echo '<div class="alert alert-danger" style="margin:15px;">';
		echo '<center style="font-size:1.2em;">Le dossier ' . __DIR__ . '/../core/config' . ' doit être en écriture</center>';
		echo '</div>';
		echo '</body>';
		echo '</html>';
		die();
	}
	$replace = array(
		'#PASSWORD#' => init('password'),
		'#DBNAME#' => init('database'),
		'#USERNAME#' => init('username'),
		'#PORT#' => init('port'),
		'#HOST#' => init('hostname'),
	);
	$config = str_replace(array_keys($replace), $replace, file_get_contents(__DIR__ . '/../core/config/common.config.sample.php'));
	file_put_contents(__DIR__ . '/../core/config/common.config.php', $config);
	shell_exec('php ' . __DIR__ . '/install.php mode=force > ' . __DIR__ . '/../log/jeedom_installation 2>&1 &');
	echo '<div id="div_alertMessage" class="alert alert-warning" style="margin:15px;">';
	echo '<center style="font-size:1.2em;"><i class="fa fa-spinner fa-spin"></i> The installation jeedom is ongoing.</center>';
	echo '</div>';
	echo '<pre id="pre_installLog" style="margin:15px;"></pre>';
	?>
			<script>
				function loadLog(){
					$( "#pre_installLog" ).load( "setup.php?log=1&key=<?php echo $tmp_key ?>", function(data) {
						if(data.indexOf('[END INSTALL SUCCESS]') > 0){
							$('#div_alertMessage').removeClass('alert-warning').addClass('alert-success').html('<center style="font-size:1.2em;"><i class="fa fa-check"></i> Jeedom successfully install. Login is <strong>admin</strong>, password is <strong>admin</strong>. Click <a href="../index.php">here</a> for connection</center>');
							return;
						}
						if(data.indexOf('[END INSTALL ERROR]') > 0){
							$('#div_alertMessage').removeClass('alert-warning').addClass('alert-danger').html('<center style="font-size:1.2em;"><i class="fa fa-times"></i> Error on installation, please read the log.</center>');
							return;
						}
						setTimeout(function(){ loadLog(); }, 1000);
					});
				}
				loadLog();
			</script>
			<?php
}
?>
	</body>
	</html>
