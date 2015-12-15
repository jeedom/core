<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin_id = init('plugin_id');
sendVarToJs('plugin_id', $plugin_id);
if (!class_exists($plugin_id)) {
	die();
}
if (!method_exists($plugin_id, 'dependancy_info')) {
	die();
}
$dependancy_info = $plugin_id::dependancy_info();
$refresh = array();
?>
<table class="table table-bordered">
	<thead>
		<tr>
			<th>{{Nom}}</th>
			<th>{{Statut}}</th>
			<th>{{Installation}}</th>
			<th>{{Log}}</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>{{Local}}</td>
			<td class="dependancyState" data-slave_id="0">
				<?php
$refresh[0] = 0;
switch ($dependancy_info['state']) {
	case 'ok':
		echo '<span class="label label-success" style="font-size:1em;">{{OK}}</span>';
		break;
	case 'nok':
		echo '<span class="label label-danger" style="font-size:1em;">{{NOK}}</span>';
		break;
	case 'in_progress':
		$refresh[0] = 1;
		echo '<span class="label label-primary" style="font-size:1em;">{{Installation en cours}}</span>';
		break;
	default:
		echo '<span class="label label-warning" style="font-size:1em;">' . $dependancy_info['state'] . '</span>';
		break;
}
?>
			</td>
			<td>
				<a class="btn btn-warning btn-sm launchInstallPluginDependancy" data-slave_id="0" style="position:relative;top:-5px;"><i class="fa fa-bicycle"></i> {{Relancer}}</a>
			</td>
			<td>
				<a class="btn btn-default btn-sm showLogPluginDependancy" data-slave_id="0" style="position:relative;top:-5px;"><i class="fa fa-file-o"></i> {{Voir la log}}</a>
			</td>
		</tr>

		<?php
if (config::byKey('jeeNetwork::mode') == 'master') {
	foreach (jeeNetwork::byPlugin($plugin_id) as $jeeNetwork) {
		try {
			$dependancy_info = $jeeNetwork->sendRawRequest('plugin::dependancyInfo', array('plugin_id' => $plugin_id));
			?>
					<tr>
						<td>
							<?php echo $jeeNetwork->getName(); ?>
						</td>
						<td class="dependancyState" data-slave_id="<?php echo $jeeNetwork->getId(); ?>">
							<?php
$refresh[$jeeNetwork->getId()] = 0;
			if (!isset($dependancy_info['state'])) {
				$dependancy_info['state'] = 'nok';
			}
			switch ($dependancy_info['state']) {
				case 'ok':
					echo '<span class="label label-success" style="font-size:1em;">{{OK}}</span>';
					break;
				case 'nok':
					echo '<span class="label label-danger" style="font-size:1em;">{{NOK}}</span>';
					break;
				case 'in_progress':
					$refresh[$jeeNetwork->getId()] = 1;
					echo '<span class="label label-primary" style="font-size:1em;">{{Installation en cours}}</span>';
					break;
				default:
					echo '<span class="label label-warning" style="font-size:1em;">' . $dependancy_info['state'] . '</span>';
					break;
			}
			?>
						</td>
						<td>
							<a class="btn btn-warning btn-sm launchInstallPluginDependancy" data-slave_id="<?php echo $jeeNetwork->getId(); ?>" style="position:relative;top:-5px;"><i class="fa fa-bicycle"></i> {{Relancer}}</a>
						</td>
						<td>
							<a class="btn btn-default btn-sm showLogPluginDependancy" data-slave_id="<?php echo $jeeNetwork->getId(); ?>" style="position:relative;top:-5px;"><i class="fa fa-file-o"></i> {{Voir la log}}</a>
						</td>
					</tr>
					<?php
} catch (Exception $e) {

		}
	}
}
?>
	</tbody>
</table>
<?php
sendVarToJs('refresh_dependancy_info', $refresh);
?>
<script>
	function refreshDependancyInfo(){
		for(var i in refresh_dependancy_info){
			if(refresh_dependancy_info[i] == 1){
				jeedom.plugin.getDependancyInfo({
					id : plugin_id,
					slave_id: i,
					error: function (error) {
						$('#div_alert').showAlert({message: error.message, level: 'danger'});
					},
					success: function (data) {
						refresh_dependancy_info[i] = 0;
						switch(data.state) {
							case 'ok':
							$('.dependancyState[data-slave_id='+i+']').empty().append('<span class="label label-success" style="font-size:1em;">{{OK}}</span>');
							break;
							case 'nok':
							$('.dependancyState[data-slave_id='+i+']').empty().append('<span class="label label-danger" style="font-size:1em;">{{NOK}}</span>');
							break;
							case 'in_progress':
							refresh_dependancy_info[i] = 1;
							$('.dependancyState[data-slave_id='+i+']').empty().append('<span class="label label-primary" style="font-size:1em;">{{Installation en cours}}</span>');
							break;
							default:
							$('.dependancyState[data-slave_id='+i+']').empty().append('<span class="label label-warning" style="font-size:1em;">'+data.state+'</span>');
						}
					}
				});
}
}
setTimeout(refreshDependancyInfo, 5000);
}
refreshDependancyInfo();

$('.showLogPluginDependancy').on('click',function(){
	var slave_id = $(this).attr('data-slave_id');
	$('#md_modal').dialog({title: "{{Log des dépendances}}"});
	$('#md_modal').load('index.php?v=d&modal=plugin.dependancyLog&plugin_id='+plugin_id+'&slave_id='+slave_id).dialog('open');
});

$('.launchInstallPluginDependancy').on('click',function(){
	var slave_id = $(this).attr('data-slave_id');
	jeedom.plugin.dependancyInstall({
		id : plugin_id,
		slave_id: slave_id,
		error: function (error) {
			$('#div_alert').showAlert({message: error.message, level: 'danger'});
		},
		success: function (data) {
			$("#div_plugin_dependancy").load('index.php?v=d&modal=plugin.dependancy&plugin_id='+plugin_id);
		}
	});
});
</script>