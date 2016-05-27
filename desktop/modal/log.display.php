<?php
/* This file is part of Jeedom.
 *
 * Jeedom is free software: you can redistribute it and/or modify
 * it under the terms of the GNU General Public License as published by
 * the Free Software Foundation, either version 3 of the License, or
 * (at your option) any later version.
 *
 * Jeedom is distributed in the hope that it will be useful,
 * but WITHOUT ANY WARRANTY; without even the implied warranty of
 * MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
 * GNU General Public License for more details.
 *
 * You should have received a copy of the GNU General Public License
 * along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
 */

if (!isConnect('admin')) {
	throw new Exception('401 Unauthorized');
}
sendVarToJS('log_display_name', init('log', 'event'));
if (init('slave_id', '') == -1) {
	sendVarToJS('log_slave_id', '');
} else {
	sendVarToJS('log_slave_id', init('slave_id', ''));
}
?>
<a class="btn btn-danger pull-right" id="bt_logdisplayremoveLog"><i class="fa fa-trash-o"></i> {{Supprimer}}</a>
<a class="btn btn-warning pull-right" id="bt_logdisplayclearLog"><i class="fa fa-times"></i> {{Vider}}</a>
<a class="btn btn-success pull-right" id="bt_logdisplaydownloadLog"><i class="fa fa-cloud-download"></i> {{Télécharger}}</a>
<a class="btn btn-warning pull-right" data-state="1" id="bt_eventLogStopStart"><i class="fa fa-pause"></i> {{Pause}}</a>
<input class="form-control pull-right" id="in_eventLogSearch" style="width : 300px;" placeholder="{{Rechercher}}" />
<br/><br/><br/>
<pre id='pre_eventlog' style='overflow: auto; height: calc(100% - 65px);with:90%;'></pre>

<script>
	jeedom.log.autoupdate({
		log : log_display_name,
		slaveId : log_slave_id,
		display : $('#pre_eventlog'),
		search : $('#in_eventLogSearch'),
		control : $('#bt_eventLogStopStart'),
	});

	$("#bt_logdisplayclearLog").on('click', function(event) {
		jeedom.log.clear({
			log : log_display_name,
			slaveId : log_slave_id,
		});
	});

	$("#bt_logdisplayremoveLog").on('click', function(event) {
		jeedom.log.remove({
			log : log_display_name,
			slaveId : log_slave_id,
		});
	});
	if(log_slave_id == ''){
		$('#bt_logdisplaydownloadLog').click(function() {
			window.open('core/php/downloadFile.php?pathfile=log/' + log_display_name, "_blank", null);
		});
	}else{
		$('#bt_logdisplaydownloadLog').hide();
	}

</script>
