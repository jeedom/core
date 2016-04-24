<?php
if (!hasRight('logview', true)) {
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
				<li class="nav-header">{{Logs}} </li>
				<li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
				<?php
foreach ($list_logfile as $file) {
	if ($file == $logfile) {
		echo '<li class="cursor li_log active" data-log="' . $file . '" ><a>' . $file . '</a></li>';
	} else {
		echo '<li class="cursor li_log" data-log="' . $file . '"><a>' . $file . '</a></li>';
	}
}
?>
			</ul>
		</div>
	</div>
	<div class="col-md-10 col-sm-9">
		<a class="btn btn-danger pull-right" id="bt_removeAllLog"><i class="fa fa-trash-o"></i> {{Supprimer tous les logs}}</a>
		<a class="btn btn-danger pull-right" id="bt_removeLog"><i class="fa fa-trash-o"></i> {{Supprimer}}</a>
		<a class="btn btn-warning pull-right" id="bt_clearLog"><i class="fa fa-times"></i> {{Vider}}</a>
		<a class="btn btn-success pull-right" id="bt_downloadLog"><i class="fa fa-cloud-download"></i> {{Télécharger}}</a>
		<a class="btn btn-warning pull-right" data-state="1" id="bt_globalLogStopStart"><i class="fa fa-pause"></i> {{Pause}}</a>
		<input class="form-control pull-right" id="in_globalLogSearch" style="width : 300px;" placeholder="{{Rechercher}}" />
		<br/><br/><br/>
		<pre id='pre_globallog' style='overflow: auto; height: calc(100% - 70px);with:100%;'></pre>
	</div>
</div>
<?php include_file('desktop', 'log', 'js');?>