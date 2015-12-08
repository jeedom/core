<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin_id = init('plugin_id');
sendVarToJs('plugin_id', $plugin_id);
$dependancy_info = $plugin_id::dependancy_info();
$refresh = array();
?>
<form class="form-horizontal">
	<fieldset>
		<div class="form-group">
			<label class="col-lg-4 control-label">{{Status}}</label>
			<div class="col-lg-2 dependancyState" data-slave_id="0">
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
		echo '<span class="label label-primary" style="font-size:1em;">{{En cours}}</span>';
		break;
	default:
		echo '<span class="label label-danger" style="font-size:1em;">{{NOK}}</span>';
		break;
}
?>
			</div>
			<div class="col-lg-6">
				<a class="btn btn-warning launchInstallPluginDependancy" data-slave_id="0" style="position:relative;top:-5px;"><i class="fa fa-bicycle"></i> {{Relancer l'installation}}</a>
				<a class="btn btn-default showLogPluginDependancy" data-slave_id="0" style="position:relative;top:-5px;"><i class="fa fa-file-o"></i> {{Voir la log}}</a>
			</div>
		</div>

		<?php
foreach (jeeNetwork::byPlugin('openzwave') as $jeeNetwork) {
	$dependancy_info = $jeeNetwork->sendRawRequest('plugin::dependancyInfo', array('plugin_id' => $plugin_id));
	?>
			<div class="form-group">
				<label class="col-lg-4 control-label">{{Status}} <?php echo $jeeNetwork->getName(); ?></label>
				<div class="col-lg-2 dependancyState" data-slave_id="<?php echo $jeeNetwork->getId(); ?>">
					<?php
$refresh[$jeeNetwork->getId()] = 0;
	switch ($dependancy_info['state']) {
		case 'ok':
			echo '<span class="label label-success" style="font-size:1em;">{{OK}}</span>';
			break;
		case 'nok':
			echo '<span class="label label-danger" style="font-size:1em;">{{NOK}}</span>';
			break;
		case 'in_progress':
			$refresh[$jeeNetwork->getId()] = 1;
			echo '<span class="label label-primary" style="font-size:1em;">{{En cours}}</span>';
			break;
		default:
			echo '<span class="label label-danger" style="font-size:1em;">{{NOK}}</span>';
			break;
	}
	?>
				</div>
				<div class="col-lg-6">
					<a class="btn btn-warning launchInstallPluginDependancy" data-slave_id="<?php echo $jeeNetwork->getId(); ?>" style="position:relative;top:-5px;"><i class="fa fa-bicycle"></i> {{Relancer l'installation}}</a>
					<a class="btn btn-default showLogPluginDependancy" data-slave_id="<?php echo $jeeNetwork->getId(); ?>" style="position:relative;top:-5px;"><i class="fa fa-file-o"></i> {{Voir la log}}</a>
				</div>
			</div>
			<?php }
?>
		</fieldset>
	</form>
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
								$('.dependancyState[data-slave_id='+i+']').empty().append('<span class="label label-primary" style="font-size:1em;">{{En cours}}</span>');
								break;
								default:
								$('.dependancyState[data-slave_id='+i+']').empty().append('<span class="label label-danger" style="font-size:1em;">{{NOK}}</span>');
							}
						}
					});
}
}
setTimeout(refreshDependancyInfo, 5000);
}
refreshDependancyInfo();

$('.showLogPluginDependancy').on('click',function(){
	$('#md_modal').dialog({title: "{{Log des dépendances}}"});
	$('#md_modal').load('index.php?v=d&modal=plugin.dependancyLog&plugin_id='+plugin_id).dialog('open');
});

$('.launchInstallPluginDependancy').on('click',function(){
	jeedom.plugin.dependancyInstall({
		id : plugin_id,
		slave_id: i,
		error: function (error) {
			$('#div_alert').showAlert({message: error.message, level: 'danger'});
		},
		success: function (data) {
			$("#div_plugin_dependancy").load('index.php?v=d&modal=plugin.dependancy&plugin_id='+plugin_id);
		}
	});
});
</script>