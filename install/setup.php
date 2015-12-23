<?php
require_once dirname(__FILE__) . '/../core/php/core.inc.php';
if (!file_exists('/tmp/jeedom_tmp_key')) {
	$tmp_key = config::genKey();
	file_put_contents('/tmp/jeedom_tmp_key', $tmp_key);
} else {
	$tmp_key = file_get_contents('/tmp/jeedom_tmp_key');
}

if (init('log') == 1) {
	if ($tmp_key != init('key')) {
		if (!headers_sent()) {
			header("Status: 404 Not Found");
			header('HTTP/1.0 404 Not Found');
		}
		$_SERVER['REDIRECT_STATUS'] = 404;
		echo "<h1>404 Not Found</h1>";
		echo "The page that you have requested could not be found.";
		dei();
	}
	echo file_get_contents(dirname(__FILE__) . '/../log/jeedom_installation');
	die();
}
if (file_exists(dirname(__FILE__) . '/../core/config/common.config.php')) {
	if (!headers_sent()) {
		header("Status: 404 Not Found");
		header('HTTP/1.0 404 Not Found');
	}
	$_SERVER['REDIRECT_STATUS'] = 404;
	echo "<h1>404 Not Found</h1>";
	echo "The page that you have requested could not be found.";
	exit();
}
$needpackages = array('unzip', 'curl', 'sudo', 'ntp');
$needphpextensions = array('curl', 'json', 'mysql', 'gd');
$loadExtensions = get_loaded_extensions();
?>

<!DOCTYPE html>
<html>
<head>
	<title>Jeedom Installation</title>
	<script src="../../3rdparty/jquery/jquery.min.js"></script>
	<script src="../../3rdparty/bootstrap/bootstrap.min.js"></script>
	<link href="../../3rdparty/font-awesome/css/font-awesome.min.css" rel="stylesheet">
	<link href="../../3rdparty/bootstrap/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>
	<center>
		<img src="../../core/img/logo-jeedom-grand-nom-couleur-460x320.png" class="img-responsive" />
	</center>
	<?php
$error = false;
if (!jeedom::isCapable('sudo')) {
	$error = true;
	echo '<div class="alert alert-warning" style="margin:15px;">';
	echo '<center style="font-size:1.2em;">Jeedom has not sudo right please do in ssh : </center>';
	echo '<pre>';
	echo "sudo su -\n";
	echo 'echo "' . get_current_user() . ' ALL=(ALL) NOPASSWD: ALL" | (EDITOR="tee -a" visudo)';
	echo '</pre>';
	echo '</div>';
}
if (shell_exec('sudo crontab -l | grep jeeCron.php | wc -l') == 0) {
	$error = true;
	echo '<div class="alert alert-warning" style="margin:15px;">';
	echo '<center style="font-size:1.2em;">Please add crontab line for jeedom : </center>';
	echo '<pre>';
	echo "sudo su -\n";
	echo 'croncmd="su --shell=/bin/bash - ' . get_current_user() . ' -c \'/usr/bin/php ' . realpath(dirname(__FILE__) . '/../') . '/core/php/jeeCron.php\' >> /dev/null 2>&1' . "\n";
	echo 'cronjob="* * * * * $croncmd' . "\n";
	echo '( crontab -l | grep -v "$croncmd" ; echo "$cronjob" ) | crontab -' . "\n";
	echo '</pre>';
	echo '</div>';
}

foreach ($needpackages as $needpackage) {
	if (shell_exec(' dpkg --get-selections | grep -v deinstall | grep ' . $needpackage . ' | wc -l') == 0) {
		$error = true;
		echo '<div class="alert alert-warning" style="margin:15px;">';
		echo '<center style="font-size:1.2em;">Jeedom need ' . $needpackage . ' package, please do in ssh : </center>';
		echo '<pre>';
		echo "sudo su -\n";
		echo 'apt-get install -y ' . $needpackage;
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
	echo '<center style="font-size:1.2em;">Jeedom need ' . $needphpextension . ' php extension, please do in ssh : </center>';
	echo '<pre>';
	echo "sudo su -\n";
	echo 'apt-get install -y php5-' . $needphpextension . "\n";
	echo 'systemctl reload php5-fpm or systemctl reload apache2';
	echo '</pre>';
	echo '</div>';
}
if (ini_get('max_execution_time') < 300) {
	$error = true;
	echo '<div class="alert alert-warning" style="margin:15px;">';
	echo '<center style="font-size:1.2em;">max_execution_time must be >= 300, edit ' . php_ini_loaded_file() . ' and change this value (current ' . ini_get('max_execution_time') . ')</center>';
	echo '</div>';
}
if (ini_get('upload_max_filesize') != '1G') {
	$error = true;
	echo '<div class="alert alert-warning" style="margin:15px;">';
	echo '<center style="font-size:1.2em;">upload_max_filesize must be = 1G, edit ' . php_ini_loaded_file() . ' and change this value (current ' . ini_get('upload_max_filesize') . ')</center>';
	echo '</div>';
}
if (ini_get('post_max_size') != '1G') {
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
if (init('hostname') == '' && init('username') == '' && init('password') == '' && init('database') == '') {
	?>
		<form class="form-horizontal" action="setup.php" method="POST">
			<div class="form-group">
				<label class="col-sm-5 control-label">Database hostname</label>
				<div class="col-sm-2">
					<input type="text" class="form-control" id="hostname" name="hostname" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Database port</label>
				<div class="col-sm-2">
					<input type="text" class="form-control" id="port" name="port" value="3306" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Database username</label>
				<div class="col-sm-2">
					<input type="text" class="form-control" id="username" name="username" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Database password</label>
				<div class="col-sm-2">
					<input type="password" class="form-control" id="password" name="password" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-sm-5 control-label">Database name</label>
				<div class="col-sm-2">
					<input type="text" class="form-control" id="database" name="database" />
				</div>
			</div>
			<div class="form-group">
				<div class="col-sm-offset-5 col-sm-10">
					<input type="submit" class="btn btn-primary btn-lg" value="Proceed">
				</div>
			</div>
		</form>
		<?php } else {
	shell_exec('sudo chmod 775 -R ' . dirname(__FILE__) . '/../*');
	shell_exec('sudo chown www-data:www-data -R ' . dirname(__FILE__) . '/../*');
	if (!is_writable(dirname(__FILE__) . '/../core/config')) {
		echo '<div class="alert alert-danger" style="margin:15px;">';
		echo '<center style="font-size:1.2em;">Folder ' . dirname(__FILE__) . '/../core/config' . ' must be writable</center>';
		echo '</div>';
		echo '</body>';
		echo '</html>';
		die();
	}
	$replace = array(
		'#PASSWORD#' => init('password'),
		'#DBNAME#' => init('hostname'),
		'#USERNAME#' => init('username'),
		'#PORT#' => init('port'),
		'#HOST#' => init('hostname'),
	);
	$config = str_replace(array_keys($replace), $replace, file_get_contents(dirname(__FILE__) . '/../core/config/common.config.sample.php'));
	file_put_contents(dirname(__FILE__) . '/../core/config/common.config.php', $config);
	shell_exec('sudo echo "" ' . dirname(__FILE__) . '/../log/jeedom_installation 2>&1');
	shell_exec('sudo php ' . dirname(__FILE__) . '/install.php mode=force > ' . dirname(__FILE__) . '/../log/jeedom_installation 2>&1 &');
	echo '<div id="div_alertMessage" class="alert alert-warning" style="margin:15px;">';
	echo '<center style="font-size:1.2em;"><i class="fa fa-spinner fa-spin"></i> The installation jeedom is ongoing.</center>';
	echo '</div>';
	echo '<pre id="pre_installLog" style="margin:15px;"></pre>';
	?>
			<script>
				function loadLog(){
					$( "#pre_installLog" ).load( "setup.php?log=1&key=<?php echo $tmp_key ?>", function(data) {
						if(data.indexOf('[END UPDATE SUCCESS]') > 0){
							$('#div_alertMessage').removeClass('alert-warning').addClass('alert-success').html('<center style="font-size:1.2em;"><i class="fa fa-check"></i> Jeedom successfully install. Login is <strong>admin</strong>, password is <strong>admin</strong>. Click <a href="../index.php">here</a> for connection</center>');
							return;
						}
						if(data.indexOf('[END UPDATE ERROR]') > 0){
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