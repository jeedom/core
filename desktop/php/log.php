<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

$page = init('page', 1);
$logfile = init('logfile');
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
?>
<div class="row row-overflow">
	<div class="col-lg-2 col-md-3 col-sm-4" id="div_displayLogList">
		<div class="bs-sidebar">
			<ul id="ul_object" class="nav nav-list bs-sidenav">
				<li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
				<?php
				foreach ($list_logfile as $file) {
					$hasErr = 0;
					$flag = '<i class="fa fa-check"></i>';
					if (shell_exec('grep -c -E "\[ERROR\]|\[error\]" ' . __DIR__ . '/../../log/' . $file) != 0) {
						$flag = '<i class="fa fa-exclamation-triangle"></i>';
					} else if (shell_exec('grep -c -E "\[WARNING\]" ' . __DIR__ . '/../../log/' . $file) != 0) {
						$flag = '<i class="fa fa-exclamation-circle"></i>';
					}
					if ($file == $logfile) {
						echo '<li class="cursor li_log active" data-log="' . $file . '" >' . $flag . '<a>' . $file . ' (' . round(filesize('log/' . $file) / 1024) . ' Ko)</a></li>';
					} else {
						echo '<li class="cursor li_log" data-log="' . $file . '">' . $flag . '<a>' . $file . ' (' . round(filesize('log/' . $file) / 1024) . ' Ko)</a></li>';
					}
				}
				?>
			</ul>
		</div>
	</div>
	<div class="col-lg-10 col-md-9 col-sm-8">
		<div class="row">
			<div class="col-lg-12">
				<div class="input-group pull-right" style="display:inline-flex">
					<input class="form-control roundedLeft input-sm" style="max-width:150px;" id="in_globalLogSearch" placeholder="{{Rechercher}}" />
					<span class="input-group-btn">
						<a class="btn btn-warning btn-sm" data-state="1" id="bt_globalLogStopStart"><i class="fas fa-pause"></i> {{Pause}}</a><a class="btn btn-success btn-sm" id="bt_downloadLog"><i class="fas fa-cloud-download-alt"></i> {{Télécharger}}</a><a class="btn btn-warning btn-sm" id="bt_clearLog"><i class="fas fa-times"></i> {{Vider}}</a><a class="btn btn-danger btn-sm" id="bt_removeLog"><i class="far fa-trash-alt"></i> {{Supprimer}}</a><a class="btn btn-danger btn-sm roundedRight" id="bt_removeAllLog"><i class="far fa-trash-alt"></i> {{Supprimer tous}}</a>
					</span>
				</div>
			</div>
		</div>
		<pre id='pre_globallog' style='overflow: auto; height: calc(100% - 50px);width:100%;margin-top: 5px;'></pre>
	</div>
</div>
<?php include_file('desktop', 'log', 'js');?>
