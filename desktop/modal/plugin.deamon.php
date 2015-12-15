<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin_id = init('plugin_id');
sendVarToJs('plugin_id', $plugin_id);
if (!class_exists($plugin_id)) {
	die();
}
$plugin = plugin::byId($plugin_id);
$deamon_info = $plugin->deamon_info();
if (count($deamon_info) == 0) {
	die();
}
$refresh = array();
?>
<table class="table table-bordered">
	<thead>
		<tr>
			<th>{{Nom}}</th>
			<th>{{Statut}}</th>
			<th>{{Configuration}}</th>
			<th>{{Démarrer}}</th>
			<th>{{Arrêter}}</th>
			<th>{{Debug}}</th>
			<th>{{Log}}</th>
		</tr>
	</thead>
	<tbody>
		<tr>
			<td>{{Local}}</td>
			<td class="deamonState" data-slave_id="0">
				<?php
$refresh[0] = 1;
switch ($deamon_info['state']) {
	case 'ok':
		echo '<span class="label label-success" style="font-size:1em;">{{OK}}</span>';
		break;
	case 'nok':
		echo '<span class="label label-danger" style="font-size:1em;">{{NOK}}</span>';
		break;
	default:
		echo '<span class="label label-warning" style="font-size:1em;">' . $deamon_info['state'] . '</span>';
		break;
}
?>
			</td>
			<td class="deamonLaunchable" data-slave_id="0">
				<?php
if (!isset($deamon_info['launchable_message'])) {
	$deamon_info['launchable_message'] = '';
}
switch ($deamon_info['launchable']) {
	case 'ok':
		echo '<span class="label label-success" style="font-size:1em;">{{OK}}</span>';
		break;
	case 'nok':
		echo '<span class="label label-danger" style="font-size:1em;" title="' . $deamon_info['launchable_message'] . '">{{NOK}}</span>';
		break;
	default:
		echo '<span class="label label-warning" style="font-size:1em;">' . $deamon_info['launchable'] . '</span>';
		break;
}
?>
			</td>

			<td>
				<a class="btn btn-success btn-sm bt_startDeamon" data-slave_id="0" style="position:relative;top:-5px;"><i class="fa fa-play"></i></a>
			</td>
			<td>
				<a class="btn btn-danger btn-sm bt_stopDeamon" data-slave_id="0" style="position:relative;top:-5px;"><i class="fa fa-stop"></i></a>
			</td>
			<td>
				<a class="btn btn-warning btn-sm bt_launchDebug" data-slave_id="0" style="position:relative;top:-5px;"><i class="fa fa-bug"></i></a>
			</td>
			<td>
				<a class="btn btn-default btn-sm bt_showDeamonLog" data-slave_id="0" style="position:relative;top:-5px;"><i class="fa fa-file-o"></i></a>
			</td>
		</tr>

		<?php
if (config::byKey('jeeNetwork::mode') == 'master') {
	foreach (jeeNetwork::byPlugin($plugin_id) as $jeeNetwork) {
		try {
			$deamon_info = $jeeNetwork->sendRawRequest('plugin::deamonInfo', array('plugin_id' => $plugin_id));
			$refresh[$jeeNetwork->getId()] = 1;
			?>
					<tr>
						<td><?php echo $jeeNetwork->getName(); ?></td>
						<td class="deamonState" data-slave_id="<?php echo $jeeNetwork->getId(); ?>">
							<?php
if (!isset($deamon_info['state'])) {
				$deamon_info['state'] = 'nok';
			}
			switch ($deamon_info['state']) {
				case 'ok':
					echo '<span class="label label-success" style="font-size:1em;">{{OK}}</span>';
					break;
				case 'nok':
					echo '<span class="label label-danger" style="font-size:1em;">{{NOK}}</span>';
					break;
				default:
					echo '<span class="label label-warning" style="font-size:1em;">' . $deamon_info['state'] . '</span>';
					break;
			}
			?>
						</td>
						<td class="deamonLaunchable" data-slave_id="<?php echo $jeeNetwork->getId(); ?>">
							<?php
if (!isset($deamon_info['launchable'])) {
				$deamon_info['launchable'] = 'nok';
			}
			switch ($deamon_info['launchable']) {
				case 'ok':
					echo '<span class="label label-success" style="font-size:1em;">{{OK}}</span>';
					break;
				case 'nok':
					echo '<span class="label label-danger" style="font-size:1em;" title="' . $deamon_info['launchable_message'] . '">{{NOK}}</span>';
					break;
				default:
					echo '<span class="label label-warning" style="font-size:1em;">' . $deamon_info['launchable'] . '</span>';
					break;
			}
			?>
						</td>
						<td>
							<a class="btn btn-success btn-sm bt_startDeamon" data-slave_id="<?php echo $jeeNetwork->getId(); ?>" style="position:relative;top:-5px;"><i class="fa fa-play"></i></a>
						</td>
						<td>
							<a class="btn btn-danger btn-sm bt_stopDeamon" data-slave_id="<?php echo $jeeNetwork->getId(); ?>" style="position:relative;top:-5px;"><i class="fa fa-stop"></i></a>
						</td>
						<td>
							<a class="btn btn-warning btn-sm bt_launchDebug" data-slave_id="<?php echo $jeeNetwork->getId(); ?>" style="position:relative;top:-5px;"><i class="fa fa-bug"></i></a>
						</td>
						<td>
							<a class="btn btn-default btn-sm bt_showDeamonLog" data-slave_id="<?php echo $jeeNetwork->getId(); ?>" style="position:relative;top:-5px;"><i class="fa fa-file-o"></i></a>
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
sendVarToJs('refresh_deamon_info', $refresh);
?>
<script>
	function refreshDeamonInfo(){
		var relaunch = false;
		for(var i in refresh_deamon_info){
			relaunch = true;
			jeedom.plugin.getDeamonInfo({
				id : plugin_id,
				slave_id: i,
				error: function (error) {
					$('#div_alert').showAlert({message: error.message, level: 'danger'});
				},
				success: function (data) {
					switch(data.state) {
						case 'ok':
						$('.deamonState[data-slave_id='+i+']').empty().append('<span class="label label-success" style="font-size:1em;">{{OK}}</span>');
						break;
						case 'nok':
						$('.deamonState[data-slave_id='+i+']').empty().append('<span class="label label-danger" style="font-size:1em;">{{NOK}}</span>');
						break;
						default:
						$('.deamonState[data-slave_id='+i+']').empty().append('<span class="label label-warning" style="font-size:1em;">'+data.state+'</span>');
					}
					if (!isset(data.launchable_message)) {
						data.launchable_message = '';
					}
					switch(data.launchable) {
						case 'ok':
						$('.bt_startDeamon').show();
						$('.bt_stopDeamon').show();
						$('.bt_launchDebug').show();
						$('.deamonLaunchable[data-slave_id='+i+']').empty().append('<span class="label label-success" style="font-size:1em;">{{OK}}</span>');
						break;
						case 'nok':
						$('.bt_startDeamon').hide();
						$('.bt_stopDeamon').hide();
						$('.bt_launchDebug').hide();
						$('.deamonLaunchable[data-slave_id='+i+']').empty().append('<span class="label label-danger" style="font-size:1em;" title="'+data.launchable_message+'">{{NOK}}</span>');
						break;
						default:
						$('.deamonLaunchable[data-slave_id='+i+']').empty().append('<span class="label label-warning" style="font-size:1em;">'+data.state+'</span>');
					}
				}
			});
}
if(relaunch && $("#div_plugin_deamon").is(':visible')){
	setTimeout(refreshDeamonInfo, 5000);
}
}
refreshDeamonInfo();

$('.bt_showDeamonLog').on('click',function(){
	var slave_id = $(this).attr('data-slave_id');
	$('#md_modal').dialog({title: "{{Log du démon}}"});
	$('#md_modal').load('index.php?v=d&modal=plugin.deamonLog&plugin_id='+plugin_id+'&slave_id='+slave_id).dialog('open');
});

$('.bt_startDeamon').on('click',function(){
	var slave_id = $(this).attr('data-slave_id');
	savePluginConfig({
		relaunchDeamon : false,
		success : function(_slave_id){
			if(slave_id == _slave_id){
				jeedom.plugin.deamonStart({
					id : plugin_id,
					slave_id: slave_id,
					forceRestart: 1,
					error: function (error) {
						$('#div_alert').showAlert({message: error.message, level: 'danger'});
					},
					success: function (data) {
						$("#div_plugin_deamon").load('index.php?v=d&modal=plugin.deamon&plugin_id='+plugin_id);
					}
				});
			}
		}
	});
});

$('.bt_launchDebug').on('click',function(){
	var slave_id = $(this).attr('data-slave_id');
	savePluginConfig({
		relaunchDeamon : false,
		success : function(_slave_id){
			if(slave_id == _slave_id){
				jeedom.plugin.deamonStart({
					id : plugin_id,
					slave_id: slave_id,
					debug:1,
					forceRestart: 1,
					error: function (error) {
						$('#div_alert').showAlert({message: error.message, level: 'danger'});
					},
					success: function (data) {
						$("#div_plugin_deamon").load('index.php?v=d&modal=plugin.deamon&plugin_id='+plugin_id);
					}
				});
			}
		}
	});
});

$('.bt_stopDeamon').on('click',function(){
	var slave_id = $(this).attr('data-slave_id');
	jeedom.plugin.deamonStop({
		id : plugin_id,
		slave_id: slave_id,
		error: function (error) {
			$('#div_alert').showAlert({message: error.message, level: 'danger'});
		},
		success: function (data) {
			$("#div_plugin_deamon").load('index.php?v=d&modal=plugin.deamon&plugin_id='+plugin_id);
		}
	});
});
</script>