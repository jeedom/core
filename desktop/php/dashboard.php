<?php
if (!isConnect()) {
	throw new Exception(__('401 - Accès non autorisé',__FILE__));
}

sendVarToJs('SEL_CATEGORY', init('category', 'all'));
sendVarToJs('SEL_TAG', init('tag', 'all'));
sendVarToJs('SEL_SUMMARY', init('summary'));

$DisplayByObject = true;
if (init('summary') != '') {
	$DisplayByObject = false;
}

if (init('object_id') == '') {
	if (init('summary') != '') {
		$object = jeeObject::rootObject();
	} else {
		$object = jeeObject::byId($_SESSION['user']->getOptions('defaultDashboardObject'));
	}
} else {
	sendVarToJs('SHOW_BY_SUMMARY', '');
	$object = jeeObject::byId(init('object_id'));
}

if ($DisplayByObject && !is_object($object)) {
	$object = jeeObject::rootObject();
		if (!is_object($object)) {
		$alert = '{{Aucun objet racine trouvé. Pour en créer un, allez dans Outils -> Objets}}.<br/>';
		$alert .= '{{Documentation}} : <a href="https://doc.jeedom.com/fr_FR/concept/" class="cursor label alert-info" target="_blank">{{Concepts}}</a>';
		$alert .= ' | <a href="https://doc.jeedom.com/fr_FR/premiers-pas/" class="cursor label alert-info" target="_blank">{{Premiers pas}}</a>';
		echo '<div class="alert alert-warning">'.$alert.'</div>';
		return;
	}
}

if ($DisplayByObject) {
	sendVarToJs('rootObjectId', $object->getId());
	if (init('childs', 1) == 1) {
		$allObject = jeeObject::buildTree(null, true);
	} else {
		$allObject = array();
	}
} else {
	sendVarToJs('SHOW_BY_SUMMARY', init('summary'));
	if (init('object_id') == '') {
		$allObject = jeeObject::all(true);
		sendVarToJs('rootObjectId', 'undefined');
	} else {
		$allObject = [$object];
		sendVarToJs('rootObjectId', $object->getId());
	}
}

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
			if ($DisplayByObject) {
				foreach ($allObject as $object_li) {
					$margin = 5 * $object_li->getConfiguration('parentNumber');
					$liobject = '<li class="cursor li_object" ><a data-object_id="' . $object_li->getId() . '" data-href="index.php?v=d&p=dashboard&object_id=' . $object_li->getId() . '&category=' . init('category', 'all') . '" style="padding: 2px 0px;"><span style="position:relative;left:' . $margin . 'px;">' . $object_li->getHumanName(true, true) . '</span></a></li>';
					if ($object_li->getId() == $object->getId()) $liobject = str_replace('class="cursor li_object"', 'class="cursor li_object active"', $liobject);
					echo $liobject;
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
?>

<div id="dashTopBar" class="input-group">
	<div class="input-group-btn">
	<?php
		if (init('childs', 1) == 1) {?>
			<a id="bt_displayObject" class="btn roundedLeft" data-display='<?php echo $_SESSION['user']->getOptions('displayObjetByDefault') ?>' title="{{Afficher/Masquer les objets}}"><i class="far fa-image"></i>
		<?php } else { ?>
			<a id="bt_backOverview" href="index.php?v=d&p=overview" class="btn roundedLeft" title="{{Retour à la Synthèse}}"><i class="fas fa-arrow-circle-left"></i>&nbsp;<i class="fab fa-hubspot"></i>
		<?php } ?>
		</a>
	</div>
	<input class="form-control" id="in_searchWidget" placeholder="Rechercher">
	<div class="input-group-btn">
		<a id="bt_resetDashboardSearch" class="btn" title="{{Vider le champ de recherche}}"><i class="fas fa-times"></i>
		</a><button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown" title="{{Filtre par catégorie}}">
			<i class="fas fa-filter"></i></i>&nbsp;&nbsp;&nbsp;<span class="caret"></span>
		</button>
		<ul id="categoryfilter" class="dropdown-menu" role="menu" style="top:28px;left:-110px;">
			<li>
				<a id="catFilterAll"> {{Toutes}}</a>
				<a id="catFilterNone"> {{Aucune}}</a>
			</li>
			 <li class="divider"></li>
			<?php
				foreach ((jeedom::getConfiguration('eqLogic:category')) as $key => $value) {
					if ($key=='default') $key = '';
					echo '<li><a><input checked type="checkbox" class="catFilterKey" data-key="'.$key.'"/>&nbsp;<i class="'.$value['icon'].'"></i> '.$value['name'].'</a></li>';
				}
			?>
			<li><a><input checked type="checkbox" class="catFilterKey" data-key="scenario"/>&nbsp;<i class="fas fa-cogs"></i> {{Scenario}}</a></li>
		</ul>
	</div>
	<?php
	if (init('category', 'all') == 'all') {?>
		<div class="input-group-btn">
			<a id="bt_editDashboardWidgetOrder" data-mode="0" class="btn enabled roundedRight" title="{{Édition du Dashboard}}"><i class="fas fa-pencil-alt"></i></a>
		</div>
	<?php } ?>
</div>

<?php include_file('desktop', 'dashboard', 'js'); ?>
<div class="row" >
	<?php
	if ($DisplayByObject) {
		//show root object and all its childs:
		$div =  '<div class="col-md-12">';
		$div .= '<div data-object_id="' . $object->getId() . '" data-father_id="' . $object->getFather_id() . '" class="div_object">';
		$div .= '<legend><span class="objectDashLegend fullCorner"><a class="div_object" href="index.php?v=d&p=object&id=' . $object->getId() . '">' . $object->getDisplay('icon') . ' ' . ucfirst($object->getName()) . '</a><span>' . $object->getHtmlSummary() . '</span> <i class="fas fa-expand pull-right cursor bt_editDashboardWidgetAutoResize" id="edit_object_' . $object->getId() . '" title="{{Clic: hauteur max<br>CtrlClic: hauteur min}}" data-mode="0" style="display: none;"></i></span></legend>';
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
			foreach (($value->getChilds()) as $child) {
				if ($child->getConfiguration('hideOnDashboard', 0) == 1) {
					continue;
				}
				$div = '<div class="col-md-12">';
				$div .= '<div data-object_id="' . $child->getId() . '" data-father_id="' . $child->getFather_id() . '" class="div_object">';
				$div .= '<legend><span class="objectDashLegend fullCorner"><a href="index.php?v=d&p=object&id=' . $child->getId() . '">' . $child->getDisplay('icon') . ' ' . $child->getName() . '</a><span>' . $child->getHtmlSummary() . '</span> <i class="fas fa-expand pull-right cursor bt_editDashboardWidgetAutoResize" id="edit_object_' . $child->getId() . '" title="{{Clic: hauteur max<br>CtrlClic: hauteur min}}" data-mode="0" style="display: none;"></i></span></legend>';
				$div .= '<div class="div_displayEquipement" id="div_ob' . $child->getId() . '">';
				$div .= '<script>getObjectHtml(' . $child->getId() . ')</script>';
				$div .= '</div>';
				$div .= '</div>';
				$div .= '</div>';
				echo $div;
			}
		}
	} else {
		//show object(s) for summaries:
		foreach ($allObject as $object) {
			$div =  '<div class="col-md-12">';
			$div .= '<div data-object_id="' . $object->getId() . '" data-father_id="' . $object->getFather_id() . '" class="div_object hidden">';
			$div .= '<legend><span class="objectDashLegend fullCorner"><a class="div_object" href="index.php?v=d&p=object&id=' . $object->getId() . '">' . $object->getDisplay('icon') . ' ' . ucfirst($object->getName()) . '</a><span>' . $object->getHtmlSummary() . '</span> <i class="fas fa-expand pull-right cursor bt_editDashboardWidgetAutoResize" id="edit_object_' . $object->getId() . '" title="{{Clic: hauteur max<br>CtrlClic: hauteur min}}" data-mode="0" style="display: none;"></i></span></legend>';
			$div .= '<div class="div_displayEquipement" id="div_ob' . $object->getId() . '">';
			$div .= '<script>getObjectHtmlFromSummary(' . $object->getId() . ')</script>';
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

<?php
	include_file('desktop/common', 'ui', 'js');
?>
