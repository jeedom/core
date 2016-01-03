<?php
if (!hasRight('logview', true)) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

$page = init('page', 1);
$logfile = init('logfile', 'http.error');
$list_logfile = array();
$dir = opendir('log/');
$logExist = false;
while ($file = readdir($dir)) {
	if ($file != '.' && $file != '..' && $file != '.htaccess' && !is_dir('log/' . $file)) {
		$list_logfile[] = $file;
		if ($logfile == $file) {
			$logExist = true;
		}
	}
}
natcasesort($list_logfile);
if ((!$logExist || $logfile == '') && count($list_logfile) > 0) {
	$logfile = $list_logfile[0];
}
if ($logfile == '') {
	throw new Exception('No log file');
}
?>

<a class="btn btn-danger pull-right" id="bt_removeAllLog"><i class="fa fa-trash-o"></i> {{Supprimer tous les logs}}</a>
<a class="btn btn-danger pull-right" id="bt_removeLog"><i class="fa fa-trash-o"></i> {{Supprimer}}</a>
<a class="btn btn-warning pull-right" id="bt_clearLog"><i class="fa fa-times"></i> {{Vider}}</a>
<a class="btn btn-success pull-right" id="bt_downloadLog"><i class="fa fa-cloud-download"></i> {{Télécharger}}</a>
<a class="btn btn-primary pull-right" id="bt_refreshLog"><i class="fa fa-refresh"></i> {{Rafraîchir}}</a>
<select id="sel_log" class="pull-left form-control" style="width: 200px;">
    <?php
foreach ($list_logfile as $file) {
	if ($file == $logfile) {
		echo '<option value="' . $file . '" selected>' . $file . '</option>';
	} else {
		echo '<option value="' . $file . '">' . $file . '</option>';
	}
}
?>
</select>
<br/><br/>
<?php
log::chunk($logfile);
?>
<div id="div_logDisplay" style="overflow: scroll;"><pre><?php
echo secureXSS(shell_exec('cat ' . dirname(__FILE__) . '/../../log/' . $logfile)); ?></pre></div>
<?php include_file('desktop', 'log', 'js');?>