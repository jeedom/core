<?php
if (!isConnect()) {
	throw new Exception(__('401 - Accès non autorisé', __FILE__));
}

sendVarToJS([
	'SEL_CATEGORY' => init('category', 'all'),
	'SEL_TAG' => init('tag', 'all'),
	'SEL_SUMMARY' => init('summary')
]);

//DisplayByObject or display by summaries:
$DisplayByObject = true;
if (init('summary') != '') {
	$DisplayByObject = false;
}

//Get higher object to show:
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

//Check for object found:
if ($DisplayByObject && !is_object($object)) {
	$object = jeeObject::rootObject();
	if (!is_object($object)) {
		$alert = '{{Aucun objet racine trouvé. Pour en créer un, allez dans Outils -> Objets}}.<br/>';
		if (config::byKey('doc::base_url', 'core') != '') {
			$alert .= '{{Documentation}} : <a href="' . config::byKey('doc::base_url', 'core') . '/fr_FR/concept/" class="cursor label alert-info" target="_blank">{{Concepts}}</a>';
			$alert .= ' | <a href="' . config::byKey('doc::base_url', 'core') . '/fr_FR/premiers-pas/" class="cursor label alert-info" target="_blank">{{Premiers pas}}</a>';
		}
		echo '<div class="alert alert-warning">' . $alert . '</div>';
		return;
	}
}

//Get all object in right order, coming from Dashboard or Synthesis, showing childs or not, or by summaries:
$objectTree = jeeObject::buildTree(null, true);
if ($DisplayByObject) {
	sendVarToJs('rootObjectId', $object->getId());
	if (init('childs', 1) == 1) {
		$allObject = $objectTree;
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

if (!$object->hasRight('r') && count($allObject) > 0) {
	$object = $allObject[0];
}

//cache object summaries to not duplicate calls:
global $summaryCache;
$summaryCache = [];
foreach ($objectTree as $_object) {
	$summaryCache[$_object->getId()] = $_object->getHtmlSummary();
}
?>

<div class="row row-overflow">
</div>
<div id="div_displayObject">
	<div id="dashTopBar" class="input-group">
		<div class="input-group-btn">
			<?php
			if (init('btover', 0) == 0) { ?>
				<a id="bt_overview" class="btn" data-state="0"><i class="icon jeedomapp-fleche-bas-line"></i></a>
			<?php } else { ?>
				<a id="bt_backOverview" href="index.php?v=d&p=overview" class="btn roundedLeft" title="{{Retour à la Synthèse}}"><i class="fas fa-arrow-circle-left"></i>
				</a><a id="bt_overview" class="btn clickable" data-state="0"><i class="icon jeedomapp-fleche-bas-line"></i></a>
			<?php } ?>
		</div>
		<input class="form-control" id="in_searchDashboard" placeholder="{{Rechercher | nom | :not(nom}}" autocomplete="off">
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
					if ($key == 'default') $key = '';
					echo '<li><a><input checked type="checkbox" class="catFilterKey" data-key="' . $value['name'] . '"/>&nbsp;<i class="' . $value['icon'] . '"></i> ' . $value['name'] . '</a></li>';
				}
				?>
				<li><a><input checked type="checkbox" class="catFilterKey" data-key="scenario" />&nbsp;<i class="fas fa-cogs"></i> {{Scénario}}</a></li>
			</ul>
		</div>
		<?php
		if (init('category', 'all') == 'all') { ?>
			<div class="input-group-btn">
				<a id="bt_editDashboardWidgetOrder" data-mode="0" class="btn enabled roundedRight" title="{{Édition du Dashboard}}"><i class="fas fa-pencil-alt"></i></a>
			</div>
		<?php } ?>
	</div>

	<?php
	//display previews:
	if (init('btover', 0) != 0) { //overview
		$divSummaries = '<div id="dashOverviewPrevSummaries">';
		$div = '<div id="dashOverviewPrev" class="overview" style="display:none;">';
		foreach ($objectTree as $_object) {
			if ($_object->getConfiguration('hideOnOverview') == 1) continue;
			$backUrl = $_object->getImgLink();
			if ($backUrl == '') {
				$backUrl = 'core/img/background/jeedom_abstract_04_light.jpg';
			}
			$div .= '<div class="objectPreview cursor shadowed fullCorner" style="background:url(' . $backUrl . ')" data-object_id="' . $_object->getId() . '">';
			$div .= '<div class="topPreview topCorner nocursor">';
			$div .= '<span class="name cursor">' . $_object->getDisplay('icon') . ' ' . $_object->getName() . '</span>';
			$div .= '</div>';
			$div .= '</div>';

			$divSummaries .= $summaryCache[$_object->getId()];
		}
		$div .= $divSummaries . '</div></div>';
		echo $div;
	} else { //dashboard
		$div = '<div id="dashOverviewPrev" class="dashboard" style="display:none;">';
		foreach ($objectTree as $_object) {
			$margin = 8 * $_object->getConfiguration('parentNumber');
			$dataHref = 'index.php?v=d&p=dashboard&object_id=' . $_object->getId();
			$div .= '<div class="cursor li_object"><a data-object_id="' . $_object->getId() . '" data-href="' . $dataHref . '">';
			$div .= '<span style="position:relative;left:' . $margin . 'px;">' . $_object->getHumanName(true, true) . '</a></span>';

			$div .= $summaryCache[$_object->getId()];
			$div .= '</div>';
		}
		$div .= '</div>';
		echo $div;
	}

	include_file('desktop', 'dashboard', 'js');

	function formatJeedomObjectDiv($object, $toSummary = false) {
		global $summaryCache;
		$objectId =  $object->getId();
		$divClass = 'div_object';
		if ($toSummary) $divClass .= ' hidden';
		$div =  '<div class="col-md-12">';
		$div .= '<div data-object_id="' . $objectId . '" data-father_id="' . $object->getFather_id() . '" class="' . $divClass . '">';
		$div .= '<legend><span class="objectDashLegend fullCorner">';
		if (init('childs', 1) == 0) {
			$div .= '<a href="index.php?v=d&p=dashboard&object_id=' . $objectId . '&childs=0&btover=1"><i class="icon jeedomapp-fleche-haut-line"></i></a>';
		} else {
			$div .= '<a href="index.php?v=d&p=dashboard&object_id=' . $objectId . '&childs=0"><i class="icon jeedomapp-fleche-haut-line"></i></a>';
		}
		$div .= '<a href="index.php?v=d&p=object&id=' . $objectId . '">' . $object->getDisplay('icon') . ' ' . ucfirst($object->getName()) . '</a>
		<span>' . $summaryCache[$objectId] . '</span>
		<i class="fas fa-compress pull-right cursor bt_editDashboardTilesAutoResizeDown" id="compressTiles_object_' . $objectId . '" title="{{Régler toutes les tuiles à la hauteur de la moins haute.}}" data-mode="0" style="display: none;"></i>
		<i class="fas fa-expand pull-right cursor bt_editDashboardTilesAutoResizeUp" id="expandTiles_object_' . $objectId . '" title="{{Régler toutes les tuiles à la hauteur de la plus haute.}}" data-mode="0" style="display: none;"></i>
		</span>
		</legend>';
		$div .= '<div class="div_displayEquipement" id="div_ob' . $objectId . '">';

		if ($toSummary) {
			$div .= '<script>getObjectHtmlFromSummary(' . $objectId . ')</script>';
		} else {
			$div .= '<script>getObjectHtml(' . $objectId . ')</script>';
		}
		$div .= '</div></div></div>';
		echo $div;
	}
	?>
	<div class="row">
		<?php
		if ($DisplayByObject) {
			//show root object and all its childs:
			if ($object->hasRight('r')) {
				formatJeedomObjectDiv($object);
			}
			foreach ($allObject as $thisObject) {
				if ($thisObject->getId() != $object->getId()) {
					continue;
				}
				foreach (($thisObject->getChilds()) as $child) {
					if ($child->getConfiguration('hideOnDashboard', 0) == 1 || !$child->hasRight('r')) {
						continue;
					}
					formatJeedomObjectDiv($child);
				}
			}
		} else {
			//show object(s) for summaries:
			foreach ($allObject as $object) {
				formatJeedomObjectDiv($object, true);
			}
		}

		?>
	</div>
</div>
</div>

<?php
include_file('desktop/common', 'ui', 'js');
?>