<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
sendVarToJs('SEL_OBJECT_ID', init('object_id'));
sendVarToJs('SEL_CATEGORY', init('category', 'all'));
sendVarToJs('SEL_TAG', init('tag', 'all'));
sendVarToJs('SEL_SUMMARY', init('summary'));
if (init('object_id') == '') {
	if(init('summary') != ''){
		$object = jeeObject::rootObject();
	}else{
		$object = jeeObject::byId($_SESSION['user']->getOptions('defaultDashboardObject'));
	}
} else {
	$object = jeeObject::byId(init('object_id'));
}
if (!is_object($object)) {
	$object = jeeObject::rootObject();
}
if (!is_object($object)) {
	throw new Exception('{{Aucun objet racine trouvé. Pour en créer un, allez dans Outils -> Objets.<br/> Si vous ne savez pas quoi faire, n\'hésitez pas à consulter cette <a href="https://jeedom.github.io/documentation/premiers-pas/fr_FR/index" target="_blank">page</a> et celle-là si vous avez un pack : <a href="https://jeedom.com/start" target="_blank">page</a>}}');
}
$allObject = jeeObject::buildTree(null, true);
foreach ($allObject as $value) {
	if ($value->getId() == $object->getId()) {
		$child_object = $value->getChilds();
	}
}
sendVarToJs('rootObjectId', $object->getId());
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
			<li class="filter" style="margin-bottom: 5px;"><input class="filter form-control" placeholder="{{Rechercher}}" style="width: 100%"/></li>
			<?php
			foreach ($allObject as $object_li) {
				$margin = 5 * $object_li->getConfiguration('parentNumber');
				$liobject = '<li class="cursor li_object" ><a data-object_id="' . $object_li->getId() . '" data-href="index.php?v=d&p=dashboard&object_id=' . $object_li->getId() . '&category=' . init('category', 'all') . '" style="padding: 2px 0px;"><span style="position:relative;left:' . $margin . 'px;">' . $object_li->getHumanName(true, true) . '</span></a></li>';
				if ($object_li->getId() == $object->getId()) $liobject = str_replace('class="cursor li_object"', 'class="cursor li_object active"', $liobject);
				echo $liobject;
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
?>

<div id="dashTopBar" class="input-group">
	<div class="input-group-btn">
		<a id="bt_displayObject" class="btn roundedLeft" data-display='<?php echo $_SESSION['user']->getOptions('displayObjetByDefault') ?>' title="{{Afficher/Masquer les objets}}"><i class="far fa-image"></i></a><a id="bt_displaySummaries" class="btn" data-display="0" title="{{Afficher/Masquer les résumés}}"><i class="fas fa-poll-h"></i></a>
	</div>
	<input class="form-control" id="in_searchWidget" placeholder="Rechercher">
	<div class="input-group-btn">
		<a id="bt_resetDashboardSearch" class="btn"><i class="fas fa-times"></i></a>
	</div>
	<?php
	if (init('category', 'all') == 'all') {?>
		<div class="input-group-btn">
			<a id="bt_editDashboardWidgetOrder" data-mode="0" class="btn roundedRight" title="{{Édition du Dashboard}}"><i class="fas fa-pencil-alt"></i></a>
		</div>
	<?php } ?>
</div>

<?php include_file('desktop', 'dashboard', 'js'); ?>

<div class="row" >
	<?php
	$div =  '<div class="col-md-12">';
	$div .= '<div data-object_id="' . $object->getId() . '" data-father_id="' . $object->getFather_id() . '" class="div_object">';
	$div .= '<legend><a class="div_object" href="index.php?v=d&p=object&id=' . $object->getId() . '">' . $object->getDisplay('icon') . ' ' . ucfirst($object->getName()) . '</a><span>' . $object->getHtmlSummary() . '</span> <i class="fas fa-expand pull-right cursor bt_editDashboardWidgetAutoResize" id="edit_object_' . $object->getId() . '" data-mode="0" style="display: none;"></i> </legend>';
	$div .= '<div class="div_displayEquipement" id="div_ob' . $object->getId() . '">';
	$div .= '<script>getObjectHtml(' . $object->getId() . ')</script>';
	$div .= '</div>';
	$div .= '</div>';
	$div .= '</div>';
	echo $div;
	foreach ($allObject as $value) {
		if ($value->getId() != $object->getId()) {
			continue;
		}
		foreach ($value->getChilds() as $child) {
			if ($child->getConfiguration('hideOnDashboard', 0) == 1) {
				continue;
			}
			$div = '<div class="col-md-12">';
			$div .= '<div data-object_id="' . $child->getId() . '" data-father_id="' . $child->getFather_id() . '" class="div_object">';
			$div .= '<legend><a href="index.php?v=d&p=object&id=' . $child->getId() . '">' . $child->getDisplay('icon') . ' ' . $child->getName() . '</a><span>' . $child->getHtmlSummary() . '</span> <i class="fas fa-expand pull-right cursor bt_editDashboardWidgetAutoResize" id="edit_object_' . $child->getId() . '" data-mode="0" style="display: none;"></i></legend>';
			$div .= '<div class="div_displayEquipement" id="div_ob' . $child->getId() . '">';
			$div .= '<script>getObjectHtml(' . $child->getId() . ')</script>';
			$div .= '</div>';
			$div .= '</div>';
			$div .= '</div>';
			echo $div;
		}
	}
	?>
</div>
</div>
</div>
