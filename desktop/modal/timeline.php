<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
include_file('3rdparty', 'visjs/vis.min', 'css');
include_file('3rdparty', 'visjs/vis.min', 'js');
?>
<div id="div_timelineAlert"></div>

<div id="div_visualization"></div>

<script type="text/javascript">
	jeedom.getEvents({
		error: function (error) {
			$('#div_timelineAlert').showAlert({message: error.message, level: 'danger'});
		},
		success: function (data) {
			var names = ['action', 'info', 'scenario'];
			var groups = new vis.DataSet();
			for (var g = 0; g < 3; g++) {
				groups.add({id: names[g], content: names[g]});
			}
			data_item = [];
			id = 0;
			for(var i in data){
				item = {id : id,start : data[i].date,content : data[i].html,group : data[i].group};
				id++;
				data_item.push(item);
			}
			var items = new vis.DataSet(data_item);
			var container = document.getElementById('div_visualization');
			var options = {
				groupOrder:'content',
				verticalScroll: true,
				zoomKey: 'ctrlKey',
			};
			var timeline = new vis.Timeline(container);
			timeline.setOptions(options);
			timeline.setGroups(groups);
			timeline.setItems(items);
		}
	});
</script>