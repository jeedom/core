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
			<th>{{Dernière installation}}</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>{{Local}}</td>
			<td class="dependancyState" data-slave_id="0">
				<?php
$refresh[0] = 1;
switch ($dependancy_info['state']) {
	case 'ok':
		echo '<span class="label label-success" style="font-size:1em;">{{OK}}</span>';
		break;
	case 'nok':
		echo '<span class="label label-danger" style="font-size:1em;">{{NOK}}</span>';
		break;
	case 'in_progress':
		echo '<span class="label label-primary" style="font-size:1em;"><i class="fa fa-spinner fa-spin"></i> {{Installation en cours}}';
		if (isset($dependancy_info['progression']) && $dependancy_info['progression'] !== '') {
			echo ' (' . $dependancy_info['progression'] . ' %)';
		}
		echo '</span>';
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
			<td class="td_lastLaunchDependancy" data-slave_id="0">
				<?php echo $deamon_info['last_launch'] ?>
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
$refresh[$jeeNetwork->getId()] = 1;
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
					echo '<span class="label label-primary" style="font-size:1em;"><i class="fa fa-spinner fa-spin"></i> {{Installation en cours}}';
					if (isset($dependancy_info['progression']) && $dependancy_info['progression'] !== '') {
						echo ' (' . $dependancy_info['progression'] . ' %)';
					}
					echo '</span>';
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
						<td class="td_lastLaunchDependancy" data-slave_id="<?php echo $jeeNetwork->getId(); ?>">
							<?php echo $deamon_info['last_launch'] ?>
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
		var nok = false;
		jeedom.plugin.getDependancyInfo({
			id : plugin_id,
			slave_id: json_encode(refresh_dependancy_info),
			success: function (datas) {
				for(var i in datas){
					var data = datas[i];
					switch(data.state) {
						case 'ok':
						$('.dependancyState[data-slave_id='+i+']').empty().append('<span class="label label-success" style="font-size:1em;">{{OK}}</span>');
						break;
						case 'nok':
						nok = true;
						$("#div_plugin_dependancy").closest('.panel').removeClass('panel-success panel-info').addClass('panel-danger');
						$('.dependancyState[data-slave_id='+i+']').empty().append('<span class="label label-danger" style="font-size:1em;">{{NOK}}</span>');
						break;
						case 'in_progress':
						nok = true;
						$("#div_plugin_dependancy").closest('.panel').removeClass('panel-success panel-danger').addClass('panel-info');
						refresh_dependancy_info[i] = 1;
						var html = '<span class="label label-primary" style="font-size:1em;"><i class="fa fa-spinner fa-spin"></i> {{Installation en cours}}';
						if(isset(data.progression) && data.progression !== ''){
							html += ' ('+data.progression+' %)';
						}
						html += '</span>';
						$('.dependancyState[data-slave_id='+i+']').empty().append(html);
						break;
						default:
						$('.dependancyState[data-slave_id='+i+']').empty().append('<span class="label label-warning" style="font-size:1em;">'+data.state+'</span>');
					}
					$('.td_lastLaunchDependancy[data-slave_id='+i+']').empty().append(data.last_launch);
					if(!nok){
						$("#div_plugin_dependancy").closest('.panel').removeClass('panel-danger panel-info').addClass('panel-success');
					}
				}
				setTimeout(refreshDependancyInfo, 5000);
			}
		});
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