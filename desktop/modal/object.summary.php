<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<div id="div_alertObjectSummary"></div>
<table class="table table-bordered table-condensed" id="table_ObjectSummary">
	<thead>
		<tr>
			<th style="cursor:default">{{ID}}</th>
			<th style="cursor:default">{{Objet}}</th>
			<th style="cursor:default">{{Père}}</th>
			<th style="cursor:default">{{Visible}}</th>
			<th style="cursor:default">{{Masqué}}</th>
			<th style="cursor:default">{{Résumé Défini}} <sup style="cursor:default" title="Si grisé alors pas remonté en résumé global">?</th>
			<th style="cursor:default">{{Résumé Dashboard Masqué}}</th>
			<th style="cursor:default">{{Résumé Mobile Masqué}}</th>
		</tr>
	</thead>
	<tbody>
<?php
$allObject = object::buildTree(null, false);
foreach ($allObject as $object) {
	echo '<tr><td><span class="label label-info" style="font-size : 1em;">' . $object->getId() . '</span></td>';
	echo '<td><span style="font-size : 1.3em;">' . $object->getHumanName(true, true) . '</span></td>';
	$father = $object->getFather();
	if ($father) {
		echo '<td><span style="font-size : 1em;">' . $father->getHumanName(true, true) . '</span></td>';
	} else {
		echo '<td><span class="label label-info" style="font-size : 1em;"></span></td>';
	}
	if ($object->getIsVisible()) {
		echo '<td><span class="label label-success" style="font-size : 1em;" title="{{Oui}}"><i class="fa fa-check"></i></span></td>';
	} else {
		echo '<td><span class="label label-danger" style="font-size : 1em;" title="{{Non}}"><i class="fa fa-times"></i></span></td>';
	}
	if ($object->getConfiguration("hideOnDashboard", 0) == 1) {
		echo '<td><span class="label label-success" style="font-size : 1em;" title="{{Oui}}"><i class="fa fa-check"></i></span></td>';
	} else {
		echo '<td><span class="label label-danger" style="font-size : 1em;" title="{{Non}}"><i class="fa fa-times"></i></span></td>';
	}
	echo '<td>';
	foreach (config::byKey('object:summary') as $key => $value) {
		if (count($object->getConfiguration('summary')[$key]) > 0) {
			if ($object->getConfiguration('summary::global::' . $key) == 1) {
				echo '<a style="cursor:default;text-decoration:none;" title="' . $value['name'] . '">' . $value['icon'] . '<sup> ' . count($object->getConfiguration('summary')[$key]) . '</sup></a>  ';
			} else {
				echo '<a style="cursor:default;color:grey;text-decoration:none;" title="' . $value['name'] . '">' . $value['icon'] . '<sup> ' . count($object->getConfiguration('summary')[$key]) . '</sup></a>  ';
			}
		}
	}
	echo '</td>';
	echo '<td>';
	foreach (config::byKey('object:summary') as $key => $value) {
		if ($object->getConfiguration('summary::hide::desktop::' . $key) == 1) {
			echo '<a style="cursor:default;text-decoration:none;" title="' . $value['name'] . '">' . $value['icon'] . '</a>  ';
		}
	}
	echo '</td>';
	echo '<td>';
	foreach (config::byKey('object:summary') as $key => $value) {
		if ($object->getConfiguration('summary::hide::mobile::' . $key) == 1) {
			echo '<a style="cursor:default;text-decoration:none;" title="' . $value['name'] . '">' . $value['icon'] . '</a>  ';
		}
	}
	echo '</td>';
}
?>
	</tbody>
</table>