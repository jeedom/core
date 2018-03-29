<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
sendVarToJs('SEL_JEEOBJECT_ID', init('jeeObject_id'));
sendVarToJs('SEL_CATEGORY', init('category', 'all'));
sendVarToJs('SEL_TAG', init('tag', 'all'));
sendVarToJs('SEL_SUMMARY', init('summary'));
if (init('jeeObject_id') == '') {
	$jeeObject = jeeObject::byId($_SESSION['user']->getOptions('defaultDashboardObject'));
} else {
	$jeeObject = jeeObject::byId(init('jeeObject_id'));
}
if (!is_object($jeeObject)) {
	$jeeObject = jeeObject::root();
}
if (!is_object($jeeObject)) {
	throw new Exception('{{Aucun objet racine trouvé. Pour en créer un, allez dans Outils -> Objets.<br/> Si vous ne savez pas quoi faire ou que c\'est la première fois que vous utilisez Jeedom, n\'hésitez pas à consulter cette <a href="https://jeedom.github.io/documentation/premiers-pas/fr_FR/index" target="_blank">page</a> et celle-là si vous avez un pack : <a href="https://jeedom.com/start" target="_blank">page</a>}}');
}
$child_jeeObject = jeeObject::buildTree($jeeObject);
?>

<div class="row row-overflow">
	<?php
if ($_SESSION['user']->getOptions('displayObjetByDefault') == 1) {
	echo '<div class="col-lg-2 col-md-3 col-sm-4" id="div_displayJeeObjectList">';
} else {
	echo '<div class="col-lg-2 col-md-3 col-sm-4" style="display:none;" id="div_displayJeeObjectList">';
}
?>
	<div class="bs-sidebar">
		<ul id="ul_jeeObject" class="nav nav-list bs-sidenav">
			<li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
			<?php
$allJeeObject = jeeObject::buildTree(null, true);
foreach ($allJeeObject as $jeeObject_li) {
	$margin = 5 * $jeeObject_li->getConfiguration('parentNumber');
	if ($jeeObject_li->getId() == $jeeObject->getId()) {
		echo '<li class="cursor li_jeeObject active" ><a data-jeeObject_id="' . $jeeObject_li->getId() . '" data-href="index.php?v=d&p=dashboard&jeeObject_id=' . $jeeObject_li->getId() . '&category=' . init('category', 'all') . '" style="padding: 2px 0px;"><span style="position:relative;left:' . $margin . 'px;font-size:0.85em;">' . $jeeObject_li->getHumanName(true, true) . '</span><span style="font-size : 0.65em;float:right;position:relative;top:7px;">' . $jeeObject_li->getHtmlSummary() . '</span></a></li>';
	} else {
		echo '<li class="cursor li_jeeObject" ><a data-jeeObject_id="' . $jeeObject_li->getId() . '" data-href="index.php?v=d&p=dashboard&jeeObject_id=' . $jeeObject_li->getId() . '&category=' . init('category', 'all') . '" style="padding: 2px 0px;"><span style="position:relative;left:' . $margin . 'px;font-size:0.85em;">' . $jeeObject_li->getHumanName(true, true) . '</span><span style="font-size : 0.65em;float:right;position:relative;top:7px;">' . $jeeObject_li->getHtmlSummary() . '</span></a></li>';
	}
}
?>
		</ul>
	</div>
</div>
<?php
if ($_SESSION['user']->getOptions('displayScenarioByDefault') == 1) {
	if ($_SESSION['user']->getOptions('displayObjetByDefault') == 1) {
		echo '<div class="col-lg-8 col-md-7 col-sm-5" id="div_displayJeeObject">';
	} else {
		echo '<div class="col-lg-10 col-md-9 col-sm-7" id="div_displayJeeObject">';
	}
} else {
	if ($_SESSION['user']->getOptions('displayObjetByDefault') == 1) {
		echo '<div class="col-lg-10 col-md-9 col-sm-8" id="div_displayJeeObject">';
	} else {
		echo '<div class="col-lg-12 col-md-12 col-sm-12" id="div_displayJeeObject">';
	}
}
?>
<i class='fa fa-picture-o cursor pull-left' id='bt_displayJeeObject' data-display='<?php echo $_SESSION['user']->getOptions('displayObjetByDefault') ?>' title="{{Afficher/Masquer les objets}}"></i>
<i class='fa fa-cogs pull-right cursor' id='bt_displayScenario' data-display='<?php echo $_SESSION['user']->getOptions('displayScenarioByDefault') ?>' title="{{Afficher/Masquer les scénarios}}"></i>
<?php if (init('category', 'all') == 'all') {?>
<i class="fa fa-pencil pull-right cursor" id="bt_editDashboardWidgetOrder" data-mode="0" style="margin-right : 10px;"></i>
<?php }
?>
<div style="text-align : center;">
	<form class="form-inline">
		<div class="form-group">
			<label>{{Catégorie}}</label>
			<select id="sel_eqLogicCategory" class="form-control input-sm form-inline">
				<?php
if (init('category', 'all') == 'all') {
	echo '<option value="all" selected> {{Toute}}</option>';
} else {
	echo '<option value="all"> {{Toute}}</option>';
}
foreach (jeedom::getConfiguration('eqLogic:category', true) as $key => $value) {
	if (init('category', 'all') == $key) {
		echo '<option value="' . $key . '" selected> {{' . $value['name'] . '}}</option>';
	} else {
		echo '<option value="' . $key . '"> {{' . $value['name'] . '}}</option>';
	}
}
?>
			</select>
		</div>
		<label>{{Tags}}</label>
		<select id="sel_eqLogicTags" class="form-control input-sm form-inline">
			<?php
if (init('tag', 'all') == 'all') {
	echo '<option value="all" selected> {{Tous}}</option>';
} else {
	echo '<option value="all"> {{Tous}}</option>';
}
$knowTags = eqLogic::getAllTags();
foreach ($knowTags as $tag) {
	if (init('tag', 'all') == $tag) {
		echo '<option value="' . $tag . '" selected> ' . $tag . '</option>';
	} else {
		echo '<option value="' . $tag . '"> ' . $tag . '</option>';
	}
}
?>
		</select>
	</form>
</div>
<?php include_file('desktop', 'dashboard', 'js');?>
<div class="row" >
	<?php
if (init('jeeObject_id') != '') {
	echo '<div class="col-md-12">';
} else {
	echo '<div class="col-md-' . $jeeObject->getDisplay('dashboard::size', 12) . '">';
}
echo '<div data-jeeObject_id="' . $jeeObject->getId() . '" class="div_jeeOject">';
echo '<legend style="margin-bottom : 0px;"><a class="div_jeeobject" style="text-decoration:none" href="index.php?v=d&p=jeeObject&id=' . $jeeObject->getId() . '">' . $jeeObject->getDisplay('icon') . ' ' . $jeeObject->getName() . '</a><span style="font-size : 0.6em;margin-left:10px;">' . $jeeObject->getHtmlSummary() . '</span></legend>';
echo '<div class="div_displayEquipement" id="div_jeeOb' . $jeeObject->getId() . '" style="width: 100%;padding-top:3px;margin-bottom : 3px;">';
echo '<script>getJeeObjectHtml(' . $jeeObject->getId() . ')</script>';
echo '</div>';
echo '</div>';
echo '</div>';
foreach ($child_jeeObject as $child) {
	if ($child->getConfiguration('hideOnDashboard', 0) == 1) {
		continue;
	}
	echo '<div class="col-md-' . $child->getDisplay('dashboard::size', 12) . '">';
	echo '<div data-jeeObject_id="' . $child->getId() . '" style="margin-bottom : 3px;" class="div_jeeObject">';
	echo '<legend style="margin-bottom : 0px;"><a style="text-decoration:none" href="index.php?v=d&p=jeeObject&id=' . $child->getId() . '">' . $child->getDisplay('icon') . ' ' . $child->getName() . '</a><span style="font-size : 0.6em;margin-left:10px;">' . $child->getHtmlSummary() . '</span></legend>';
	echo '<div class="div_displayEquipement" id="div_jeeOb' . $child->getId() . '" style="width: 100%;padding-top:3px;margin-bottom : 3px;">';
	echo '<script>getJeeObjectHtml(' . $child->getId() . ')</script>';
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
