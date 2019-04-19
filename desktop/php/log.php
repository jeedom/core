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
					$fsize = filesize('log/' . $file);
					
					if ($fsize < 2){
						$fsizelog = '';
					}else if ($fsize < 1024){
						$fsizelog = $fsize.' o';
					}else if ( $fsize < 1048576){
						$fsizelog = round($fsize / 1024,1) .' Ko';
					}else{
						$fsizelog = round($fsize / 1048576 ,1) .' Mo';
					}
					if($fsizelog != ''){
						$fsizelog = ' ('.$fsizelog.')';
					}
					$flag = '<i class="fa fa-check"></i>';
					if (shell_exec('grep -ci -E "\[:error\]|\[error\]" ' . __DIR__ . '/../../log/' . $file) != 0) {
						$flag = '<i class="fa fa-exclamation-triangle"></i>';
					} else if (shell_exec('grep -c -E "\[WARNING\]" ' . __DIR__ . '/../../log/' . $file) != 0) {
						$flag = '<i class="fa fa-exclamation-circle"></i>';
					}
					echo '<li class="cursor li_log ' .(($file == $logfile)?'active':'') .'" data-log="' . $file . '" ><a>' . $flag . ' ' . $file . $fsizelog . '</a></li>';
				}
				?>
			</ul>
		</div>
	</div>
	<div class="col-lg-10 col-md-9 col-sm-8">
		
		<div class="input-group pull-right" style="display:inline-flex">
			<span class="input-group-btn">
				<input style="width: 150px;" class="form-control roundedLeft" id="in_globalLogSearch" placeholder="{{Rechercher}}" />
				<a class="btn btn-warning" data-state="1" id="bt_globalLogStopStart"><i class="fas fa-pause"></i> {{Pause}}
				</a><a class="btn btn-success" id="bt_downloadLog"><i class="fas fa-cloud-download-alt"></i> {{Télécharger}}
				</a><a class="btn btn-warning" id="bt_clearLog"><i class="fas fa-times"></i> {{Vider}}
				</a><a class="btn btn-danger" id="bt_removeLog"><i class="far fa-trash-alt"></i> {{Supprimer}}
				</a><a class="btn btn-danger roundedRight" id="bt_removeAllLog"><i class="far fa-trash-alt"></i> {{Supprimer tous}}</a>
			</span>
		</div>
		<pre id='pre_globallog' style='overflow: auto; height: calc(100% - 50px);width:100%;margin-top: 5px;'></pre>
	</div>
</div>
<?php include_file('desktop', 'log', 'js');?>
