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
<div class="alert alert-warning">{{Suite à l'installation du plugin ou à la mise à jour des dépendances veuillez attendre au moins 5min pour que le démon se relance automatiquement}}</div>
<table class="table table-bordered">
	<thead>
		<tr>
			<th>{{Nom}}</th>
			<th>{{Statut}}</th>
			<th>{{Configuration}}</th>
			<th>{{(Re)Démarrer}}</th>
			<th>{{Arrêter}}</th>
			<th>{{Debug}}</th>
			<th>{{Log}}</th>
			<th>{{Gestion automatique}}</th>
			<th>{{Dernier lancement}}</th>
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
		echo '<span class="label label-danger" style="font-size:1em;">{{NOK}}</span> ' . $deamon_info['launchable_message'];
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
				<?php if ($deamon_info['log'] != '') {?>
				<a class="btn btn-warning btn-sm bt_launchDebug" data-slave_id="0" style="position:relative;top:-5px;"><i class="fa fa-bug"></i></a>
				<?php }
?>
			</td>
			<td>
				<?php if ($deamon_info['log'] != '') {?>
				<a class="btn btn-default btn-sm bt_showDeamonLog" data-slave_id="0" style="position:relative;top:-5px;"><i class="fa fa-file-o"></i></a>
				<?php }
?>
			</td>
			<td>
				<?php if ($deamon_info['auto'] == 1) {?>
				<a class="btn btn-danger btn-sm bt_changeAutoMode" data-mode="0" data-slave_id="0" style="position:relative;top:-5px;"><i class="fa fa-times"></i> {{Désactiver}}</a>
				<?php } else {?>
				<a class="btn btn-success btn-sm bt_changeAutoMode" data-mode="1" data-slave_id="0" style="position:relative;top:-5px;"><i class="fa fa-magic"></i> {{Activer}}</a>
				<?php }
?>
			</td>
			<td class="td_lastLaunch" data-slave_id="0">
				<?php echo $deamon_info['last_launch'] ?>
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
					echo '<span class="label label-danger" style="font-size:1em;">{{NOK}}</span> ' . $deamon_info['launchable_message'];
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
							<?php if ($deamon_info['log'] != '') {?>
							<a class="btn btn-warning btn-sm bt_launchDebug" data-slave_id="<?php echo $jeeNetwork->getId(); ?>" style="position:relative;top:-5px;"><i class="fa fa-bug"></i></a>
							<?php }
			?>
						</td>
						<td>
							<?php if ($deamon_info['log'] != '') {?>
							<a class="btn btn-default btn-sm bt_showDeamonLog" data-slave_id="<?php echo $jeeNetwork->getId(); ?>" style="position:relative;top:-5px;"><i class="fa fa-file-o"></i></a>
							<?php }
			?>
						</td>
						<td>
							<?php if ($deamon_info['auto'] == 1) {?>
							<a class="btn btn-danger btn-sm bt_changeAutoMode" data-mode="0" data-slave_id="<?php echo $jeeNetwork->getId(); ?>" style="position:relative;top:-5px;"><i class="fa fa-times"></i> {{Désactiver}}</a>
							<?php } else {?>
							<a class="btn btn-success btn-sm bt_changeAutoMode" data-mode="1" data-slave_id="<?php echo $jeeNetwork->getId(); ?>" style="position:relative;top:-5px;"><i class="fa fa-magic"></i> {{Activer}}</a>
							<?php }
			?>
						</td>
						<td class="td_lastLaunch" data-slave_id="<?php echo $jeeNetwork->getId(); ?>">
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
sendVarToJs('refresh_deamon_info', $refresh);
?>
<script>
	var timeout_refreshDeamonInfo = null;
	function refreshDeamonInfo(){
		var in_progress = true;
		var nok = false;
		jeedom.plugin.getDeamonInfo({
			id : plugin_id,
			slave_id: json_encode(refresh_deamon_info),
			error: function (error) {
				$('#div_alert').showAlert({message: error.message, level: 'danger'});
			},
			success: function (datas) {
				for(var i in datas){
					var data = datas[i];
					switch(data.state) {
						case 'ok':
						$('.deamonState[data-slave_id='+i+']').empty().append('<span class="label label-success" style="font-size:1em;">{{OK}}</span>');
						break;
						case 'nok':
						if(data.auto == 1){
							nok = true;
						}
						$('.deamonState[data-slave_id='+i+']').empty().append('<span class="label label-danger" style="font-size:1em;">{{NOK}}</span>');
						break;
						default:
						$('.deamonState[data-slave_id='+i+']').empty().append('<span class="label label-warning" style="font-size:1em;">'+data.state+'</span>');
					}
					switch(data.launchable) {
						case 'ok':
						$('.bt_startDeamon').show();
						if(data.auto == 1){
							$('.bt_stopDeamon').show();
						}
						$('.bt_launchDebug').show();
						$('.deamonLaunchable[data-slave_id='+i+']').empty().append('<span class="label label-success" style="font-size:1em;">{{OK}}</span>');
						break;
						case 'nok':
						if(data.auto == 1){
							nok = true;
						}
						$('.bt_startDeamon').hide();
						$('.bt_stopDeamon').hide();
						$('.bt_launchDebug').hide();
						$('.deamonLaunchable[data-slave_id='+i+']').empty().append('<span class="label label-danger" style="font-size:1em;">{{NOK}}</span> '+data.launchable_message);
						break;
						default:
						$('.deamonLaunchable[data-slave_id='+i+']').empty().append('<span class="label label-warning" style="font-size:1em;">'+data.state+'</span>');
					}
					$('.td_lastLaunch[data-slave_id='+i+']').empty().append(data.last_launch);
					if(data.auto == 1){
						$('.bt_stopDeamon').hide();
						$('.bt_changeAutoMode[data-slave_id='+i+']').removeClass('btn-success').addClass('btn-danger');
						$('.bt_changeAutoMode[data-slave_id='+i+']').attr('data-mode',0);
						$('.bt_changeAutoMode[data-slave_id='+i+']').html('<i class="fa fa-times"></i> {{Désactiver}}');
					}else{
						if(data.launchable == 'ok'){
							$('.bt_stopDeamon').show();
						}
						$('.bt_changeAutoMode[data-slave_id='+i+']').removeClass('btn-danger').addClass('btn-success');
						$('.bt_changeAutoMode[data-slave_id='+i+']').attr('data-mode',1);
						$('.bt_changeAutoMode[data-slave_id='+i+']').html('<i class="fa fa-magic"></i> {{Activer}}');
					}
					if(!nok){
						$("#div_plugin_deamon").closest('.panel').removeClass('panel-danger').addClass('panel-success');
					}else{
						$("#div_plugin_deamon").closest('.panel').removeClass('panel-success').addClass('panel-danger');
					}
					if(data.launchable == 'ok' && data.state != 'ok' && data.auto == 1){
						clearTimeout(timeout_refreshDeamonInfo);
						jeedom.plugin.deamonStart({
							id : plugin_id,
							slave_id: i,
							global : false,
							error: function (error) {
								$('#div_alert').showAlert({message: error.message, level: 'danger'});
								timeout_refreshDeamonInfo = setTimeout(refreshDeamonInfo, 5000);
							},
							success :function(){
								timeout_refreshDeamonInfo = setTimeout(refreshDeamonInfo, 5000);
							}
						});
					}
				}
				if($("#div_plugin_deamon").is(':visible')){
					timeout_refreshDeamonInfo = setTimeout(refreshDeamonInfo, 5000);
				}
			}
		});
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
		}
	});
});

$('.bt_changeAutoMode').on('click',function(){
	var slave_id = $(this).attr('data-slave_id');
	var mode = $(this).attr('data-mode');
	jeedom.plugin.deamonChangeAutoMode({
		id : plugin_id,
		slave_id: slave_id,
		mode : mode,
		error: function (error) {
			$('#div_alert').showAlert({message: error.message, level: 'danger'});
		}
	});
});
</script>