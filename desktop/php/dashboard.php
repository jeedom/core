<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
sendVarToJs('SEL_OBJECT_ID', init('object_id'));
sendVarToJs('SEL_CATEGORY', init('category', 'all'));
sendVarToJs('SEL_TAG', init('tag', 'all'));
sendVarToJs('SEL_SUMMARY', init('summary'));
if (init('object_id') == '') {
	$object = jeeObject::byId($_SESSION['user']->getOptions('defaultDashboardObject'));
} else {
	$object = jeeObject::byId(init('object_id'));
}
if (!is_object($object)) {
	$object = jeeObject::rootObject();
}
if (!is_object($object)) {
	throw new Exception('{{Aucun objet racine trouvé. Pour en créer un, allez dans Outils -> Objets.<br/> Si vous ne savez pas quoi faire ou que c\'est la première fois que vous utilisez Jeedom, n\'hésitez pas à consulter cette <a href="https://jeedom.github.io/documentation/premiers-pas/fr_FR/index" target="_blank">page</a> et celle-là si vous avez un pack : <a href="https://jeedom.com/start" target="_blank">page</a>}}');
}
$allObject = jeeObject::buildTree(null, true);
foreach ($allObject as $value) {
	if ($value->getId() == $object->getId()) {
		$child_object = $value->getChilds();
	}
}
sendVarToJs('rootObjectId', $object->getId());
include_file('desktop', 'dashboard', 'css');
?>
<div class="row row-overflow">
	<?php
	if ($_SESSION['user']->getOptions('displayObjetByDefault') == 1) {
		echo '<div class="col-lg-2 col-md-3 col-sm-4" id="div_displayObjectList">';
	} else {
		echo '<div class="col-lg-2 col-md-3 col-sm-4" style="display:none;" id="div_displayObjectList">';
	}
	?>
	<div class="bs-sidebar">
		<ul id="ul_object" class="nav nav-list bs-sidenav">
			<li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
			<?php
			foreach ($allObject as $object_li) {
				$margin = 5 * $object_li->getConfiguration('parentNumber');
				if ($object_li->getId() == $object->getId()) {
					echo '<li class="cursor li_object active" ><a data-object_id="' . $object_li->getId() . '" data-href="index.php?v=d&p=dashboard&object_id=' . $object_li->getId() . '&category=' . init('category', 'all') . '" style="padding: 2px 0px;"><span style="position:relative;left:' . $margin . 'px;font-size:0.85em;">' . $object_li->getHumanName(true, true) . '</span><span style="font-size : 0.65em;float:right;position:relative;top:7px;">' . $object_li->getHtmlSummary() . '</span></a></li>';
				} else {
					echo '<li class="cursor li_object" ><a data-object_id="' . $object_li->getId() . '" data-href="index.php?v=d&p=dashboard&object_id=' . $object_li->getId() . '&category=' . init('category', 'all') . '" style="padding: 2px 0px;"><span style="position:relative;left:' . $margin . 'px;font-size:0.85em;">' . $object_li->getHumanName(true, true) . '</span><span style="font-size : 0.65em;float:right;position:relative;top:7px;">' . $object_li->getHtmlSummary() . '</span></a></li>';
				}
			}
			?>
		</ul>
	</div>
</div>
<?php
if ($_SESSION['user']->getOptions('displayObjetByDefault') == 1) {
	echo '<div class="col-lg-10 col-md-9 col-sm-8" id="div_displayObject">';
} else {
	echo '<div class="col-lg-12 col-md-12 col-sm-12" id="div_displayObject">';
}
if (init('category', 'all') == 'all') {?>
	<a class="pull-right btn btn-default btn-sm" id="bt_editDashboardWidgetOrder" data-mode="0"><i class="fas fa-pencil-alt"></i></a>
<?php } ?>
<input class='form-control input-sm' id="in_searchWidget" style="width:calc(100% - 80px);display:inline-block;" placeholder="{{Rechercher}}"/>
<a class="pull-left btn btn-default btn-sm" id="bt_displayObject" data-display='<?php echo $_SESSION['user']->getOptions('displayObjetByDefault') ?>' title="{{Afficher/Masquer les objets}}"><i class='fa fa-picture-o'></i></a>
<?php include_file('desktop', 'dashboard', 'js'); ?>
<div class="row" >
	<?php
	if (init('object_id') != '') {
		echo '<div class="col-md-12">';
	} else {
		echo '<div class="col-md-' . $object->getDisplay('dashboard::size', 12) . '">';
	}
	echo '<div data-object_id="' . $object->getId() . '" data-father_id="' . $object->getFather_id() . '" class="div_object">';
	echo '<legend style="margin-bottom : 0px;"><a class="div_object" style="text-decoration:none" href="index.php?v=d&p=object&id=' . $object->getId() . '">' . $object->getDisplay('icon') . ' ' . $object->getName() . '</a><span style="font-size : 0.6em;margin-left:10px;">' . $object->getHtmlSummary() . '</span> <i class="fas fa-compress pull-right cursor bt_editDashboardWidgetAutoResize" id="edit_object_' . $object->getId() . '" data-mode="0" style="margin-right : 10px; display: none;"></i> </legend>';
	echo '<div class="div_displayEquipement" id="div_ob' . $object->getId() . '" style="width: 100%;padding-top:3px;margin-bottom : 3px;">';
	echo '<script>getObjectHtml(' . $object->getId() . ')</script>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
	foreach ($allObject as $value) {
		if ($value->getId() != $object->getId()) {
			continue;
		}
		foreach ($value->getChilds() as $child) {
			if ($child->getConfiguration('hideOnDashboard', 0) == 1) {
				continue;
			}
			echo '<div class="col-md-' . $child->getDisplay('dashboard::size', 12) . '">';
			echo '<div data-object_id="' . $child->getId() . '" data-father_id="' . $child->getFather_id() . '" style="margin-bottom : 3px;" class="div_object">';
			echo '<legend style="margin-bottom : 0px;"><a style="text-decoration:none" href="index.php?v=d&p=object&id=' . $child->getId() . '">' . $child->getDisplay('icon') . ' ' . $child->getName() . '</a><span style="font-size : 0.6em;margin-left:10px;">' . $child->getHtmlSummary() . '</span> <i class="fas fa-compress pull-right cursor bt_editDashboardWidgetAutoResize" id="edit_object_' . $child->getId() . '" data-mode="0" style="margin-right : 10px; display: none;"></i></legend>';
			echo '<div class="div_displayEquipement" id="div_ob' . $child->getId() . '" style="width: 100%;padding-top:3px;margin-bottom : 3px;">';
			echo '<script>getObjectHtml(' . $child->getId() . ')</script>';
			echo '</div>';
			echo '</div>';
			echo '</div>';
		}
	}
	?>
</div>
</div>
</div>
