<?php
if (!isConnect()) {
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
	throw new Exception('{{Aucun objet racine trouvé. Pour en créer un, allez dans Outils -> Objets.<br/> Si vous ne savez pas quoi faire, n\'hésitez pas à consulter cette <a href="https://jeedom.github.io/documentation/premiers-pas/fr_FR/index" target="_blank">page</a> et celle-là si vous avez un pack : <a href="https://jeedom.com/start" target="_blank">page</a>}}');
}
$child_object = object::buildTree($object);
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
$allObject = object::buildTree(null, true);
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
<i class='fa fa-picture-o cursor pull-left' id='bt_displayObject' data-display='<?php echo $_SESSION['user']->getOptions('displayObjetByDefault') ?>' title="{{Afficher/Masquer les objets}}"></i>
<i class='fa fa-cogs pull-right cursor' id='bt_displayScenario' data-display='<?php echo $_SESSION['user']->getOptions('displayScenarioByDefault') ?>' title="{{Afficher/Masquer les scénarios}}"></i>
<?php if (init('category', 'all') == 'all') {?>
<i class="fa fa-pencil pull-right cursor" id="bt_editDashboardWidgetOrder" data-mode="0" style="margin-right : 10px;"></i>
<?php }
?>
<div style="text-align : center;">
	<?php
if (init('category', 'all') == 'all') {
	echo '<a href="index.php?v=d&p=dashboard&object_id=' . init('object_id') . '&category=all&summary=' . init('summary') . '" class="btn btn-primary btn-sm categoryAction" style="margin-bottom: 5px;margin-right: 3px;"><i class="fa fa-asterisk"></i> {{Tous}}</a>';
} else {
	echo '<a href="index.php?v=d&p=dashboard&object_id=' . init('object_id') . '&category=all&summary=' . init('summary') . '" class="btn btn-default btn-sm categoryAction" style="margin-bottom: 5px;margin-right: 3px;"><i class="fa fa-asterisk"></i> {{Tous}}</a>';
}
foreach (jeedom::getConfiguration('eqLogic:category', true) as $key => $value) {
	if (init('category', 'all') == $key) {
		echo '<a href="index.php?v=d&p=dashboard&object_id=' . init('object_id') . '&category=' . $key . '&summary=' . init('summary') . '" class="btn btn-primary btn-sm categoryAction" data-l1key="' . $key . '" style="margin-bottom: 5px;margin-right: 3px;"><i class="' . $value['icon'] . '"></i> {{' . $value['name'] . '}}</a>';
	} else {
		echo '<a href="index.php?v=d&p=dashboard&object_id=' . init('object_id') . '&category=' . $key . '&summary=' . init('summary') . '" class="btn btn-default btn-sm categoryAction" data-l1key="' . $key . '" style="margin-bottom: 5px;margin-right: 3px;"><i class="' . $value['icon'] . '"></i> {{' . $value['name'] . '}}</a>';
	}
}
?>
</div>
<?php include_file('desktop', 'dashboard', 'js');?>
<div class="row" >
<?php
if (init('object_id') != '') {
	echo '<div class="col-md-12">';
} else {
	echo '<div class="col-md-' . $object->getDisplay('dashboard::size', 12) . '">';
}
echo '<div data-object_id="' . $object->getId() . '" class="div_object">';
echo '<legend style="margin-bottom : 0px;"><a class="div_object" style="text-decoration:none" href="index.php?v=d&p=object&id=' . $object->getId() . '">' . $object->getDisplay('icon') . ' ' . $object->getName() . '</a><span style="font-size : 0.6em;margin-left:10px;">' . $object->getHtmlSummary() . '</span></legend>';
echo '<div class="div_displayEquipement" id="div_ob' . $object->getId() . '" style="width: 100%;padding-top:3px;margin-bottom : 3px;">';
echo '<script>getObjectHtml(' . $object->getId() . ')</script>';
echo '</div>';
echo '</div>';
echo '</div>';
foreach ($child_object as $child) {
	if ($child->getConfiguration('hideOnDashboard', 0) == 1) {
		continue;
	}
	echo '<div class="col-md-' . $child->getDisplay('dashboard::size', 12) . '">';
	echo '<div data-object_id="' . $child->getId() . '" style="margin-bottom : 3px;" class="div_object">';
	echo '<legend style="margin-bottom : 0px;"><a style="text-decoration:none" href="index.php?v=d&p=object&id=' . $child->getId() . '">' . $child->getDisplay('icon') . ' ' . $child->getName() . '</a><span style="font-size : 0.6em;margin-left:10px;">' . $child->getHtmlSummary() . '</span></legend>';
	echo '<div class="div_displayEquipement" id="div_ob' . $child->getId() . '" style="width: 100%;padding-top:3px;margin-bottom : 3px;">';
	echo '<script>getObjectHtml(' . $child->getId() . ')</script>';
	echo '</div>';
	echo '</div>';
	echo '</div>';
}

?>
</div>
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
foreach (scenario::all() as $scenario) {
	if ($scenario->getIsVisible() == 0) {
		continue;
	}
	echo $scenario->toHtml('dashboard');
}
?>
</div>
</div>
<style>
.scenario-widget{
	margin-top: 2px !important;
}
</style>
