<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

global $JEEDOM_INTERNAL_CONFIG;
global $EQLOGICSALL;
$EQLOGICSALL = eqLogic::all();

global $GENRICSTYPES;

$types = config::getGenericTypes();
$GENRICSTYPES = $types['byType'];
$families = $types['byFamily'];

global $typeStringSep;
$typeStringSep = ' -> ';

sendVarToJS([
	'generics' => $GENRICSTYPES,
	'gen_families' => $families,
	'typeStringSep' => $typeStringSep
]);

function jeedom_displayGenFamily($_family, $_familyId='') {
	global $EQLOGICSALL, $GENRICSTYPES, $typeStringSep;

	if ($_family == -1) {
		$_index = '';
		$_family = '<i class="fas fa-puzzle-piece"></i> ' . '{{Equipements sans type}}';
	} else {
		$_index = $_familyId;
	}

	$div = '';
	$div .= '<div class="panel panel-default eqlogicSortable" data-id="'.$_index.'">';
	$div .= '<div class="panel-heading">';
	$div .= '<h3 class="panel-title">';
	$div .= '<a class="accordion-toggle" data-toggle="collapse" data-parent="" aria-expanded="false" href="#gen_'.$_index.'">'.$_family;
	$div .= '<span class="spanNumber"> (#num#)</span></a></h3>';
	$div .= '</div>';

	//inner panel:
	$div .= '<div id="gen_'.$_index.'" class="panel-collapse collapse">';
	$div .= '<div class="panel-body">';

	//eqLogics ul with cmds ul inside:
	$div .= '<ul class="eqLogicSortable">';
  	$numEqs = 0;
	foreach ($EQLOGICSALL as $eqLogic) {
		$eqGeneric = $eqLogic->getGenericType();
		if (is_null($eqGeneric) && $_index != '') continue;
		if (!is_null($eqGeneric) && $_index == '') continue;
		if ($eqGeneric != $_familyId && $_index != '') continue;

      	$numEqs++;
		$div .= '<li class="eqLogic cursor" data-id="'.$eqLogic->getId().'" data-objectId="' . $eqLogic->getObject_id() . '" data-changed="0" data-generic="' . $eqGeneric . '" data-enable="'.$eqLogic->getIsEnable().'" data-name="'.$eqLogic->getHumanName().'" data-type="'.$eqLogic->getEqType_name().'">';
		$div .= '<input type="checkbox" class="cb_selEqLogic" /> ';

		$object = $eqLogic->getObject();
		if (is_object($object)) {
			$objName = $object->getName();
		} else {
			$objName = '{{Aucun}}';
		}

		$div .= $eqLogic->getHumanName(). ' (' . $objName .' / '.$eqLogic->getEqType_name() . ')';
		if ($eqLogic->getIsEnable() != 1) {
			$div .= '<i class="fas fa-times" title="{{Non actif}}"></i> ';
		}
		if ($eqLogic->getIsVisible() != 1) {
			$div .= '<i class="fas fa-eye-slash" title="{{Non visible}}"></i> ';
		}

		$div .= '<a class="btn-xs warning bt_resetCmdsTypes pull-right"><i class="fas fa-times"></i> {{Reset types}}</a>';
		$div .= '<a class="btn-xs success hidden bt_queryCmdsTypes pull-right"><i class="fas fa-puzzle-piece"></i> {{Types auto}}</a>';


		//cmds ul:
		$div .= '<ul class="eqLogicCmds" style="display:none;" >';
		foreach (($eqLogic->getCmd()) as $cmd) {
			if ($cmd->getType() == 'info') {
				$typeClass = 'alert-info';
			} else {
				$typeClass = 'alert-warning';
			}

			$cmdGenericType = $cmd->getGeneric_type();
			$div .= '<li class="alert ' . $typeClass . ' cmd" data-id="' . $cmd->getId() . '" data-changed="0" data-name="' . $cmd->getName() . '" data-generic="' . $cmdGenericType . '" data-type="' . $cmd->getType() . '" data-subType="' . $cmd->getSubType() . '">' ;
			$div .=  $cmd->getId().' | '.$cmd->getName();
			if ($cmd->getIsVisible() != 1) {
				$div .= ' <i class="fas fa-eye-slash" title="{{Non visible}}"></i> ';
			}

			if ($cmdGenericType != '') {
				if (isset($GENRICSTYPES[$cmdGenericType]['family'], $GENRICSTYPES[$cmdGenericType]['name'])) {
					$cmdGeneric = $GENRICSTYPES[$cmdGenericType]['family'] . $typeStringSep . $GENRICSTYPES[$cmdGenericType]['name'];
				} else {
					 $cmdGeneric = $cmdGenericType . ' ({{Inconnu}})';
				}
			} else {
				$cmdGeneric = 'None';
			}
			$div .= '<span class="genericType">' . $cmdGeneric . '</span>';

			$div .= '</li>';
		}
		$div .= '</ul>';

		$div .= '</li>';
	}
	$div .= '</ul>';
  	$div = str_replace('#num#', strval($numEqs), $div);
	$div .= '</div>';
	$div .= '</div>';
	$div .= '</div>';
	return $div;
}
?>

<br/>
<div class="input-group" style="margin-bottom:5px;display: inline-table;">
	<input class="form-control roundedLeft" placeholder="{{Rechercher | nom | id}}" id="in_searchTypes"/>
	<div class="input-group-btn">
		<a id="bt_resetypeSearch" class="btn" style="width:30px"><i class="fas fa-times"></i>
		</a><a class="btn" id="bt_openAll"><i class="fas fa-folder-open"></i>
		</a><a class="btn" id="bt_closeAll"><i class="fas fa-folder"></i></a>
	</div>
	<div class="input-group-btn">
		<a class="btn btn-info" id="bt_listGenericTypes"><i class="fas fa-list-alt"></i> {{Liste}}</a>
		<a class="btn btn-success roundedRight" id="bt_saveGenericTypes"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a>
	</div>
</div>

<div class="panel-group" id="genericsContainer">

<div id="md_applyCmdsTypes" class="cleanableModal hidden" style="overflow-x: hidden;">
	<form class="form-horizontal">
		<fieldset>
			<div class="form-group maincontainer mediumText"></div>
		</fieldset>
	</form>
	<br>
	<a class="btn btn-warning pull-right" id="bt_applyCmdsTypes" style="top: 10px;position: absolute;right: 10px;"><i class="fas fa-check"></i> {{Appliquer}}</a>
</div>

<?php
	echo jeedom_displayGenFamily(-1);
	foreach ($families as $id => $name) {
		echo jeedom_displayGenFamily($name, $id);
	}

	//get eqLogics with generic not in Core:
	$unknown = [];
	foreach ($EQLOGICSALL as $eqLogic) {
		$eqGeneric = $eqLogic->getGenericType();
		if ($eqGeneric != '' && !in_array($eqGeneric, array_keys($families))) {
			array_push($unknown, $eqGeneric);
		}
	}
	$unknown = array_unique($unknown);
	if (count($unknown) > 0) {
		$div = '<div class="panel panel-default">';
		$div .= '<div class="panel-heading">';
		$div .= '<h3 class="panel-title" style="padding: 8px 15px;"><i class="fas fa-chevron-down"></i> {{Types inconnus}}</h3>';
		$div .= '</div></div>';
		echo $div;
		foreach (($unknown) as $family) {
			echo jeedom_displayGenFamily($family, trim($family));
		}
	}
?>

</div>

<?php include_file('desktop', 'types', 'js');?>