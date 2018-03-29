<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<div id="div_alertJeeObjectSummary"></div>
<table class="table table-bordered table-condensed tablesorter" id="table_jeeObjectSummary">
	<thead>
		<tr>
			<th style="cursor:default">{{ID}}</th>
			<th style="cursor:default">{{Objet}}</th>
			<th style="cursor:default">{{Père}}</th>
			<th style="cursor:default" data-sorter="false" data-filter="false">{{Visible}}</th>
			<th style="cursor:default" data-sorter="false" data-filter="false">{{Masqué}}</th>
			<th style="cursor:default" data-sorter="false" data-filter="false">{{Résumé Défini}} <sup style="cursor:default" title="Si grisé, alors il n'est pas remonté en résumé global">?</th>
			<th style="cursor:default" data-sorter="false" data-filter="false">{{Résumé Dashboard Masqué}}</th>
			<th style="cursor:default" data-sorter="false" data-filter="false">{{Résumé Mobile Masqué}}</th>
		</tr>
	</thead>
	<tbody>
<?php
$alljeeObject = jeeObject::buildTree(null, false);
foreach ($alljeeObject as $jeeObject) {
	echo '<tr><td><span class="label label-info" style="font-size : 1em;">' . $jeeObject->getId() . '</span></td>';
	echo '<td><span style="font-size : 1.3em;">' . $jeeObject->getHumanName(true, true) . '</span></td>';
	$father = $jeeObject->getFather();
	if ($father) {
		echo '<td><span style="font-size : 1em;">' . $father->getHumanName(true, true) . '</span></td>';
	} else {
		echo '<td><span class="label label-info" style="font-size : 1em;"></span></td>';
	}
	if ($jeeObject->getIsVisible()) {
		echo '<td><span class="label label-success" style="font-size : 1em;" title="{{Oui}}"><i class="fa fa-check"></i></span></td>';
	} else {
		echo '<td><span class="label label-danger" style="font-size : 1em;" title="{{Non}}"><i class="fa fa-times"></i></span></td>';
	}
	if ($jeeObject->getConfiguration("hideOnDashboard", 0) == 1) {
		echo '<td><span class="label label-success" style="font-size : 1em;" title="{{Oui}}"><i class="fa fa-check"></i></span></td>';
	} else {
		echo '<td><span class="label label-danger" style="font-size : 1em;" title="{{Non}}"><i class="fa fa-times"></i></span></td>';
	}
	echo '<td>';
	foreach (config::byKey('jeeObject:summary') as $key => $value) {
		$title = '';
		$sumarries = $jeeObject->getConfiguration('summary');
		if (isset($sumarries[$key])) {
			foreach ($sumarries[$key] as $summary) {
				if (cmd::byId(str_replace('#', '', $summary['cmd']))) {
					$title .= '&#10;' . cmd::byId(str_replace('#', '', $summary['cmd']))->getHumanName();
				} else {
					$title .= '&#10;' . $summary['cmd'];
				}
			}
			if (count($sumarries[$key]) > 0) {
				if ($jeeObject->getConfiguration('summary::global::' . $key) == 1) {
					echo '<a style="cursor:default;text-decoration:none;" title="' . $value['name'] . $title . '">' . $value['icon'] . '<sup> ' . count($jeeObject->getConfiguration('summary')[$key]) . '</sup></a>  ';
				} else {
					echo '<a style="cursor:default;color:grey;text-decoration:none;" title="' . $value['name'] . $title . '">' . $value['icon'] . '<sup> ' . count($jeeObject->getConfiguration('summary')[$key]) . '</sup></a>  ';
				}
			}
		}
	}
	echo '</td>';
	echo '<td>';
	foreach (config::byKey('jeeObject:summary') as $key => $value) {
		if ($jeeObject->getConfiguration('summary::hide::desktop::' . $key) == 1) {
			echo '<a style="cursor:default;text-decoration:none;" title="' . $value['name'] . '">' . $value['icon'] . '</a>  ';
		}
	}
	echo '</td>';
	echo '<td>';
	foreach (config::byKey('jeeObject:summary') as $key => $value) {
		if ($jeeObject->getConfiguration('summary::hide::mobile::' . $key) == 1) {
			echo '<a style="cursor:default;text-decoration:none;" title="' . $value['name'] . '">' . $value['icon'] . '</a>  ';
		}
	}
	echo '</td>';
}
?>
	</tbody>
</table>

<script>
	initTableSorter();
</script>
