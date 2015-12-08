<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$plugin_id = init('plugin_id');
if (init('slave_id') == 0) {
	if (!method_exists($plugin_id, 'dependancy_info')) {
		die();
	}
	$dependancy_info = $plugin_id::dependancy_info();
} else {
	$jeeNetwork = jeeNetwork::byId(init('slave_id'));
	$dependancy_info = $jeeNetwork->sendRawRequest('plugin::dependancyInfo', array('plugin_id' => $plugin_id));
}
if (!isset($dependancy_info['log'])) {
	die();
}
sendVarToJs('logfile', $dependancy_info['log']);
sendVarToJs('slave_id', init('slave_id'));
?>

<div id='div_updatePluginDependancyAlert' style="display: none;"></div>

<a class="btn btn-warning pull-right" data-state="1" id="bt_pluginDependancyLogStopStart"><i class="fa fa-pause"></i> {{Pause}}</a>
<input class="form-control pull-right" id="in_pluginDependancyLogSearch" style="width : 300px;" placeholder="{{Rechercher}}" />
<br/><br/><br/>
<pre id='pre_pluginDependancyLogUpdate' style='overflow: auto; height: 90%;with:90%;'></pre>

<script>
	if(slave_id == 0){
		jeedom.log.autoupdate({
			log : logfile,
			display : $('#pre_pluginDependancyLogUpdate'),
			search : $('#in_pluginDependancyLogSearch'),
			control : $('#bt_pluginDependancyLogStopStart'),
		});
	}else{
		jeedom.log.autoupdate({
			log : logfile,
			slaveId :slave_id,
			display : $('#pre_pluginDependancyLogUpdate'),
			search : $('#in_pluginDependancyLogSearch'),
			control : $('#bt_pluginDependancyLogStopStart'),
		});
	}
</script>