<?php
if (!hasRight('dashboardview')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

if (init('object_id') == '') {
	$object = object::byId($_SESSION['user']->getOptions('defaultDashboardObject'));
} else {
	$object = object::byId(init('object_id'));
}
if (!is_object($object)) {
	$object = object::rootObject();
}
if (!is_object($object)) {
	throw new Exception('{{Aucun objet racine trouvé. Pour en créer un, allez dans Général -> Objet.<br/> Si vous ne savez pas quoi faire ou que c\'est la première fois que vous utilisez Jeedom n\'hésitez pas à consulter cette <a href="https://jeedom.fr/doc/documentation/premiers-pas/fr_FR/doc-premiers-pas.html" target="_blank">page</a> et celle la si vous avez un pack : <a href="https://jeedom.fr/start" target="_blank">page</a>}}');
}
$child_object = object::buildTree($object);
$parentNumber = array();
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
			<li class="nav-header">{{Liste objets}} </li>
			<li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
			<?php
$allObject = object::buildTree(null, true);
foreach ($allObject as $object_li) {
	$parentNumber[$object_li->getId()] = $object_li->parentNumber();
	$margin = 15 * $parentNumber[$object_li->getId()];
	if ($object_li->getId() == $object->getId()) {
		echo '<li class="cursor li_object active" ><a href="index.php?v=d&p=dashboard&object_id=' . $object_li->getId() . '&category=' . init('category', 'all') . '" style="position:relative;left:' . $margin . 'px;">' . $object_li->getHumanName(true) . '</a></li>';
	} else {
		echo '<li class="cursor li_object" ><a href="index.php?v=d&p=dashboard&object_id=' . $object_li->getId() . '&category=' . init('category', 'all') . '" style="position:relative;left:' . $margin . 'px;">' . $object_li->getHumanName(true) . '</a></li>';
	}
}
?>
		</ul>
	</div>
</div>
<?php
if ($_SESSION['user']->getOptions('displayScenarioByDefault') == 1) {
	if ($_SESSION['user']->getOptions('displayObjetByDefault') == 1) {
		echo '<div class="col-lg-8 col-md-7 col-sm-5" id="div_displayObject">';
	} else {
		echo '<div class="col-lg-10 col-md-9 col-sm-7" id="div_displayObject">';
	}
} else {
	if ($_SESSION['user']->getOptions('displayObjetByDefault') == 1) {
		echo '<div class="col-lg-10 col-md-9 col-sm-8" id="div_displayObject">';
	} else {
		echo '<div class="col-lg-12 col-md-12 col-sm-12" id="div_displayObject">';
	}
}
?>
<i class='fa fa-picture-o cursor tooltips pull-left' id='bt_displayObject' data-display='<?php echo $_SESSION['user']->getOptions('displayObjetByDefault')?>' title="{{Afficher/Masquer les objets}}"></i>
<i class='fa fa-cogs pull-right cursor tooltips' id='bt_displayScenario' data-display='<?php echo $_SESSION['user']->getOptions('displayScenarioByDefault')?>' title="{{Afficher/Masquer les scénarios}}"></i>
<?php if (init('category', 'all') == 'all') {?>
<i class="fa fa-pencil pull-right cursor" id="bt_editDashboardWidgetOrder" data-mode="0" style="margin-right : 10px;"></i>
<?php }
?>
<center>
	<?php
if (init('category', 'all') == 'all') {
	echo '<a href="index.php?v=d&p=dashboard&object_id=' . init('object_id') . '&category=all" class="btn btn-primary btn-sm categoryAction" style="margin-bottom: 5px;margin-right: 3px;">{{Tous}}</a>';
} else {
	echo '<a href="index.php?v=d&p=dashboard&object_id=' . init('object_id') . '&category=all" class="btn btn-default btn-sm categoryAction" style="margin-bottom: 5px;margin-right: 3px;">{{Tous}}</a>';
}
foreach (jeedom::getConfiguration('eqLogic:category') as $key => $value) {
	if (init('category', 'all') == $key) {
		echo '<a href="index.php?v=d&p=dashboard&object_id=' . init('object_id') . '&category=' . $key . '" class="btn btn-primary btn-sm categoryAction" data-l1key="' . $key . '" style="margin-bottom: 5px;margin-right: 3px;">{{' . $value['name'] . '}}</a>';
	} else {
		echo '<a href="index.php?v=d&p=dashboard&object_id=' . init('object_id') . '&category=' . $key . '" class="btn btn-default btn-sm categoryAction" data-l1key="' . $key . '" style="margin-bottom: 5px;margin-right: 3px;">{{' . $value['name'] . '}}</a>';
	}
}
if (init('category', 'all') == 'other') {
	echo '<a href="index.php?v=d&p=dashboard&object_id=' . init('object_id') . '&category=other" class="btn btn-primary btn-sm categoryAction" style="margin-bottom: 5px;margin-right: 3px;">{{Autre}}</a>';
} else {
	echo '<a href="index.php?v=d&p=dashboard&object_id=' . init('object_id') . '&category=other" class="btn btn-default btn-sm categoryAction" style="margin-bottom: 5px;margin-right: 3px;">{{Autre}}</a>';
}
?>
</center>
<?php include_file('desktop', 'dashboard', 'js');?>
<?php
echo '<div data-object_id="' . $object->getId() . '" class="div_object">';
echo '<legend style="margin-bottom : 0px;">' . $object->getDisplay('icon') . ' ' . $object->getName() . '</legend>';
echo '<div class="div_displayEquipement" id="div_ob' . $object->getId() . '" style="width: 100%;padding-top:3px;margin-bottom : 3px;">';
echo '<script>getObjectHtml(' . $object->getId() . ')</script>';
echo '</div>';
foreach ($child_object as $child) {
	echo '<div data-object_id="' . $child->getId() . '" style="margin-bottom : 3px;" class="div_object">';
	echo '<legend style="margin-bottom : 0px;">' . $child->getDisplay('icon') . ' ' . $child->getName() . '</legend>';
	echo '<div class="div_displayEquipement" id="div_ob' . $child->getId() . '" style="width: 100%;padding-top:3px;margin-bottom : 3px;">';
	echo '<script>getObjectHtml(' . $child->getId() . ')</script>';
	echo '</div>';
	echo '</div>';
}
echo '</div>';
?>
</div>
<?php
if ($_SESSION['user']->getOptions('displayScenarioByDefault') == 1) {
	echo '<div class="col-lg-2 col-md-2 col-sm-3" id="div_displayScenario">';
} else {
	echo '<div class="col-lg-2 col-md-2 col-sm-3" id="div_displayScenario" style="display:none;">';
}
?>
<legend><i class="fa fa-history"></i> {{Scénarios}}</legend>
<?php
if (init('object_id') == '') {
	foreach (scenario::byObjectId(null, false, true) as $scenario) {
		echo $scenario->toHtml('dashboard');
	}
}
foreach ($object->getScenario(false, true) as $scenario) {
	echo $scenario->toHtml('dashboard');
}
foreach ($child_object as $child) {
	foreach ($child->getScenario(false, true) as $scenario) {
		echo $scenario->toHtml('dashboard');
	}
}
?>
</div>
</div>
<style>
.scenario-widget{
	margin-top: 2px !important;
}
</style>