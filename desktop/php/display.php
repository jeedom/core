<?php
if (!hasRight('displayview', true)) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$nbEqlogic = 0;
$nbCmd = 0;
$objects = object::all();
$eqLogics = array();
$cmds = array();
$eqLogics[-1] = eqLogic::byObjectId(null, false);
foreach ($eqLogics[-1] as $eqLogic) {
	$cmds[$eqLogic->getId()] = $eqLogic->getCmd();
	$nbCmd += count($cmds[$eqLogic->getId()]);
}
$nbEqlogic += count($eqLogics[-1]);
foreach ($objects as $object) {
	$eqLogics[$object->getId()] = $object->getEqLogic(false, false);
	foreach ($eqLogics[$object->getId()] as $eqLogic) {
		$cmds[$eqLogic->getId()] = $eqLogic->getCmd();
		$nbCmd += count($cmds[$eqLogic->getId()]);
	}
	$nbEqlogic += count($eqLogics[$object->getId()]);
}
?>
<style>
	.eqLogicSortable{
		list-style-type: none;
		min-height: 20px;
		padding-left: 0px;
	}
	.eqLogicSortable li {
		margin: 0 2px 2px 2px;
		padding: 5px;
	}

	.cmdSortable{
		list-style-type: none;
		min-height: 20px;
		padding-left: 0px;
	}
	.cmdSortable li {
		margin: 0 2px 2px 2px;
		padding: 5px;
	}
</style>

<span class="pull-right">
	<input class="form-control pull-right" id="in_search" placeholder="{{Rechercher}}" style="width : 200px;"/>
	<input type="checkbox" class="bootstrapSwitch pull-right" id="cb_actifDisplay" data-on-text="{{Afficher}}" data-off-text="{{Masquer}}" data-label-text="{{Inactif}}" checked />
</span>
<center>
	<span class="label label-default" style="font-size : 1em;">{{Nombre d'objet :}} <?php echo count($objects)?></span>
	<span class="label label-info" style="font-size : 1em;">{{Nombre d'équipement :}} <?php echo $nbEqlogic?></span>
	<span class="label label-primary" style="font-size : 1em;">{{Nombre de commande :}} <?php echo $nbCmd?></span>
</center>
<a class="btn btn-danger btn-sm" id="bt_removeEqlogic" style="display:none;"><i class="fa fa-trash-o"></i> {{Supprimer}}</a>
<a class="btn btn-success btn-sm bt_setIsVisible" data-value="1" style="display:none;"><i class="fa fa-eye"></i> {{Visible}}</a>
<a class="btn btn-warning btn-sm bt_setIsVisible" data-value="0" style="display:none;"><i class="fa fa-eye-slash"></i> {{Invisible}}</a>
<a class="btn btn-success btn-sm bt_setIsEnable" data-value="1" style="display:none;"><i class="fa fa-check"></i> {{Actif}}</a>
<a class="btn btn-warning btn-sm bt_setIsEnable" data-value="0" style="display:none;"><i class="fa fa-times"></i> {{Inactif}}</a>
<br/>
<br/>
<div class="row row-same-height">
	<div class="col-xs-4 object col-xs-height" data-id="-1">
		<legend><i class="fa fa-circle-o"></i>  {{Aucun}} <i class="fa fa-chevron-down pull-right showEqLogic tooltips cursor" title="{{Voir les équipements}}"></i></legend>
		<ul class="eqLogicSortable">
			<?php
foreach ($eqLogics[-1] as $eqLogic) {
	echo '<li class="alert alert-info eqLogic cursor" data-id="' . $eqLogic->getId() . '" data-enable="' . $eqLogic->getIsEnable() . '" data-name="' . $eqLogic->getName() . '" data-type="' . $eqLogic->getEqType_name() . '">';
	echo '<input type="checkbox" class="cb_selEqLogic" /> ';
	echo $eqLogic->getName() . ' ';
	echo '<i style="font-size:0.9em;">(' . $eqLogic->getEqType_name() . ')</i> ';
	if ($eqLogic->getIsEnable() != 1) {
		echo '<i class="fa fa-times tooltips" title="{{Non actif}}"></i> ';
	}
	if ($eqLogic->getIsVisible() != 1) {
		echo '<i class="fa fa-eye-slash tooltips" title="{{Non visible}}"></i> ';
	}
	echo '<i class="fa fa-chevron-right pull-right showCmd tooltips" title="{{Voir les commandes}}"></i> ';
	echo '<i class="fa fa-cog pull-right configureEqLogic tooltips" title="{{Configuration avancée}}"></i>';
	echo '<a href="' . $eqLogic->getLinkToConfiguration() . '" target="_blank" class="pull-right tooltips" title="{{Aller sur la configuration de l\'équipement}}"><i class="fa fa-external-link"></i></a>';

	echo '<ul class="cmdSortable" style="display:none;" >';
	foreach ($cmds[$eqLogic->getId()] as $cmd) {
		echo '<li class="alert alert-warning cmd cursor" data-id="' . $cmd->getId() . '"  data-name="' . $cmd->getName() . '">' . $cmd->getName();
		echo '<i class="fa fa-cog pull-right configureCmd"></i>';
		echo '</li>';
	}
	echo '</ul>';
	echo '</li>';
}
?>
		</ul>
	</div>
	<?php
$i = 1;
foreach ($objects as $object) {
	$defaultTextColor = ($object->getDisplay('tagColor') == '') ? 'black' : 'white';
	if ($i == 0) {
		echo '<div class="row row-same-height">';
	}
	echo '<div class="col-xs-4 object col-xs-height" data-id="' . $object->getId() . '" style="background-color : ' . $object->getDisplay('tagColor') . ';color : ' . $object->getDisplay('tagTextColor', $defaultTextColor) . '">';
	echo '<legend style="color : ' . $object->getDisplay('tagTextColor', $defaultTextColor) . '">' .$object->getDisplay('icon') . '  ' . $object->getName();
	echo '<i class="fa fa-chevron-down pull-right showEqLogic tooltips cursor" title="{{Voir les équipements}}"></i>';
	echo '<i style="position:relative;top : 3px;" class="fa fa-cog pull-right cursor configureObject tooltips" title="{{Configuration avancée}}"></i>';
	echo '<a style="position:relative;top : 3px;color:' . $object->getDisplay('tagTextColor', $defaultTextColor) . '" href="index.php?v=d&p=object&id=' . $object->getId() . '" target="_blank" class="pull-right tooltips" title="{{Aller sur la configuration de l\'objet}}"><i class="fa fa-external-link"></i></a>';

	echo '</legend>';
	echo '<ul class="eqLogicSortable">';
	foreach ($eqLogics[$object->getId()] as $eqLogic) {
		echo '<li class="alert alert-info eqLogic cursor" data-id="' . $eqLogic->getId() . '" data-enable="' . $eqLogic->getIsEnable() . '" data-name="' . $eqLogic->getName() . '" data-type="' . $eqLogic->getEqType_name() . '">';
		echo '<input type="checkbox" class="cb_selEqLogic" /> ';
		echo $eqLogic->getName() . ' ';
		echo '<i style="font-size:0.9em;">(' . $eqLogic->getEqType_name() . ')</i> ';
		if ($eqLogic->getIsEnable() != 1) {
			echo '<i class="fa fa-times tooltips" title="{{Non actif}}"></i> ';
		}
		if ($eqLogic->getIsVisible() != 1) {
			echo '<i class="fa fa-eye-slash tooltips" title="{{Non visible}}"></i> ';
		}
		echo '<i class="fa fa-chevron-right pull-right showCmd tooltips" title="{{Voir les commandes}}"></i> ';
		echo '<i class="fa fa-cog pull-right configureEqLogic tooltips" title="{{Configuration avancée}}"></i>';
		echo '<a href="' . $eqLogic->getLinkToConfiguration() . '" target="_blank" class="pull-right tooltips" title="{{Aller sur la configuration de l\'équipement}}"><i class="fa fa-external-link"></i></a>';
		echo '<ul class="cmdSortable" style="display:none;" >';
		foreach ($cmds[$eqLogic->getId()] as $cmd) {
			echo '<li class="alert alert-warning cmd cursor" data-id="' . $cmd->getId() . '"  data-name="' . $cmd->getName() . '">' . $cmd->getName();
			echo '<i class="fa fa-cog pull-right configureCmd tooltips" title="{{Configuration avancée}}"></i>';
			echo '</li>';
		}
		echo '</ul>';
		echo '</li>';
	}
	echo '</ul>';
	echo '</div>';
	$i++;
	if ($i > 2) {
		$i = 0;
	}
	if ($i == 0) {
		echo '</div>';
	}
}
if ($i != 0) {
	while ($i <= 2) {
		echo '<div class="col-xs-4 col-xs-height">';
		echo '</div>';
		$i++;
	}
	echo '</div>';
}
?>


	<?php include_file('desktop', 'display', 'js');?>