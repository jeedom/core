<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$date = array(
	'start' => date('Y-m-d', strtotime(config::byKey('history::defautShowPeriod') . ' ' . date('Y-m-d'))),
	'end' => date('Y-m-d'),
);
?>
<div class="row row-overflow">
	<div id="timelineBar" class="input-group">
		<input id="in_searchTimeline" class="form-control input-sm roundedLeft" placeholder="{{Rechercher | nom | :not(nom}}" style="flex: 6;flex-grow: 4;" />
		<a id="bt_resetTimelineSearch" class="btn input-sm form-control noCorner" style="width:30px"><i class="fas fa-times"></i></a>
		<select class="form-control input-sm noCorner" style="width:200px;" id="sel_timelineFolder">
			<?php
				$options = '<option value="main">{{Principal}}</option>';	
				$options .= '<optgroup label="{{Timelines}}">';
				foreach ((timeline::listFolder()) as $folder) {
					if ($folder == 'main') continue;
					$options .= '<option ' . (init('folder') == $folder ? 'selected' : '') . ' value="' . $folder . '">' . $folder . '</option>';
				}
				$options .= '</optgroup>';
				echo $options;
			?>
		</select>
		<a class="btn btn-success input-sm noCorner" id="bt_refreshTimeline"><i class="fas fa-sync"></i> {{Rafraîchir}}</a>
		<?php if (isConnect('admin')) { ?>
			<a type="text" class="btn btn-danger input-sm noCorner" id="bt_removeTimelineEvent"><i class="fas fa-trash"></i> {{Supprimer}}</a>
			<a id="bt_openCmdHistoryConfigure" class="btn btn-default input-sm noCorner roundedRight"><i class="fas fa-cogs"></i> {{Configuration}}</a>
		<?php } ?>
	</div>

	<div id="timelineContainer">
		<ul id="events">
		</ul>
		<div id="timelineBottom" class="panel" style="text-align: center; display: none;">
			<div class="panel-body">
				<a class="bt_loadMore btn btn-success input-sm noCorner" data-load="50"><i class="fas fa-plus-square"></i> 50</a>
				<a class="bt_loadMore btn btn-success input-sm noCorner" data-load="100"><i class="fas fa-plus-square"></i> 100</a>
				<a class="bt_loadMore btn btn-success input-sm noCorner" data-load="200"><i class="fas fa-plus-square"></i> 200</a>
			</div>
		</div>
	</div>
</div>

<?php
include_file("desktop", "timeline", "js");
?>
