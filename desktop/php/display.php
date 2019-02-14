<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
sendVarToJs('jeedomBackgroundImg', 'core/img/background/display.png');
$nbEqlogic = 0;
$nbCmd = 0;
$objects = jeeObject::all();
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
<br/>
<span class="pull-left">
	<a class="btn btn-default btn-sm" id="bt_removeHistory"><i class="fas fa-trash" aria-hidden="true"></i> {{Historique des suppressions}}</a>
</span>
<span class="pull-right">
	<input class="form-control pull-right" id="in_search" placeholder="{{Rechercher}}" style="width : 200px;"/>
	<label class="checkbox-inline"><input type="checkbox" id="cb_actifDisplay" checked />{{Inactif}}</label>
</span>
<center>
	<span class="label label-default" style="font-size : 1em;cursor : default;">{{Nombre d'objets :}} <?php echo count($objects) ?></span>
	<span class="label label-info" style="font-size : 1em;cursor : default;">{{Nombre d'équipements :}} <?php echo $nbEqlogic ?></span>
	<span class="label label-primary" style="font-size : 1em;cursor : default;">{{Nombre de commandes :}} <?php echo $nbCmd ?></span>
</center>
<br/>
<a class="btn btn-danger btn-sm" id="bt_removeEqlogic" style="display:none;"><i class="far fa-trash-alt"></i> {{Supprimer}}</a>
<a class="btn btn-success btn-sm bt_setIsVisible" data-value="1" style="display:none;"><i class="fas fa-eye"></i> {{Visible}}</a>
<a class="btn btn-warning btn-sm bt_setIsVisible" data-value="0" style="display:none;"><i class="fas fa-eye-slash"></i> {{Invisible}}</a>
<a class="btn btn-success btn-sm bt_setIsEnable" data-value="1" style="display:none;"><i class="fas fa-check"></i> {{Actif}}</a>
<a class="btn btn-warning btn-sm bt_setIsEnable" data-value="0" style="display:none;"><i class="fas fa-times"></i> {{Inactif}}</a>
<br/>
<br/>
<div class="row">
	<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 object" data-id="-1">
		<div style="margin-bottom: 1em; padding:0.2em 0.5em;">
		<legend style="cursor : default"><i class="far fa-circle"></i>  {{Aucun}} <i class="fas fa-chevron-down pull-right showEqLogic cursor" title="{{Voir les équipements}}"></i></legend>
		<ul class="eqLogicSortable">
			<?php
			foreach ($eqLogics[-1] as $eqLogic) {
				echo '<li class="alert alert-info eqLogic cursor" data-id="' . $eqLogic->getId() . '" data-enable="' . $eqLogic->getIsEnable() . '" data-name="' . $eqLogic->getName() . '" data-type="' . $eqLogic->getEqType_name() . '">';
				echo '<input type="checkbox" class="cb_selEqLogic" /> ';
				echo $eqLogic->getName() . ' ';
				echo '<i style="font-size:0.9em;">(' . $eqLogic->getEqType_name() . ')</i> ';
				if ($eqLogic->getIsEnable() != 1) {
					echo '<i class="fas fa-times" title="{{Non actif}}"></i> ';
				}
				if ($eqLogic->getIsVisible() != 1) {
					echo '<i class="fas fa-eye-slash" title="{{Non visible}}"></i> ';
				}
				echo '<i class="fas fa-chevron-right pull-right showCmd" title="{{Voir les commandes}}"></i> ';
				echo '<i class="fas fa-cog pull-right configureEqLogic" title="{{Configuration avancée}}"></i>';
				echo '<a href="' . $eqLogic->getLinkToConfiguration() . '" target="_blank" class="pull-right" title="{{Aller sur la configuration de l\'équipement}}"><i class="fas fa-external-link-alt"></i></a>';

				echo '<ul class="cmdSortable" style="display:none;" >';
				foreach ($cmds[$eqLogic->getId()] as $cmd) {
					echo '<li class="alert alert-warning cmd cursor" data-id="' . $cmd->getId() . '"  data-name="' . $cmd->getName() . '">' . $cmd->getName();
					echo '<i class="fas fa-cog pull-right configureCmd"></i>';
					echo '</li>';
				}
				echo '</ul>';
				echo '</li>';
			}
			?>
		</ul>
	</div>
	</div>
	<?php
	$i = 1;
	foreach ($objects as $object) {
		$defaultTextColor = ($object->getDisplay('tagColor') == '') ? 'black' : 'white';
		if ($i == 0) {
			echo '<div class="row">';
		}
		echo '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 object" data-id="' . $object->getId() . '">';
		echo '<div style="margin-bottom: 1em; padding:0.2em 0.5em; background-color: ' . $object->getDisplay('tagColor') . ';color: ' . $object->getDisplay('tagTextColor', $defaultTextColor) . '">';
		echo '<legend style="color : ' . $object->getDisplay('tagTextColor', $defaultTextColor) . ';cursor : default">' . $object->getDisplay('icon') . '  ' . $object->getName();
		echo '<i class="fas fa-chevron-down pull-right showEqLogic cursor" title="{{Voir les équipements}}"></i>';
		echo '<i style="position:relative;top : 3px;" class="fas fa-cog pull-right cursor configureObject" title="{{Configuration avancée}}"></i>';
		echo '<a style="position:relative;top : 3px;color:' . $object->getDisplay('tagTextColor', $defaultTextColor) . '" href="index.php?v=d&p=object&id=' . $object->getId() . '" target="_blank" class="pull-right" title="{{Aller sur la configuration de l\'objet}}"><i class="fas fa-external-link-alt"></i></a>';

		echo '</legend>';
		echo '<ul class="eqLogicSortable">';
		foreach ($eqLogics[$object->getId()] as $eqLogic) {
			echo '<li class="alert alert-info eqLogic cursor" data-id="' . $eqLogic->getId() . '" data-enable="' . $eqLogic->getIsEnable() . '" data-name="' . $eqLogic->getName() . '" data-type="' . $eqLogic->getEqType_name() . '">';
			echo '<input type="checkbox" class="cb_selEqLogic" /> ';
			echo $eqLogic->getName() . ' ';
			echo '<i style="font-size:0.9em;">(' . $eqLogic->getEqType_name() . ')</i> ';
			if ($eqLogic->getIsEnable() != 1) {
				echo '<i class="fas fa-times" title="{{Non actif}}"></i> ';
			}
			if ($eqLogic->getIsVisible() != 1) {
				echo '<i class="fas fa-eye-slash" title="{{Non visible}}"></i> ';
			}
			echo '<i class="fas fa-chevron-right pull-right showCmd" title="{{Voir les commandes}}"></i> ';
			echo '<i class="fas fa-cog pull-right configureEqLogic" title="{{Configuration avancée}}"></i>';
			echo '<a href="' . $eqLogic->getLinkToConfiguration() . '" target="_blank" class="pull-right" title="{{Aller sur la configuration de l\'équipement}}"><i class="fas fa-external-link-alt"></i></a>';
			echo '<ul class="cmdSortable" style="display:none;" >';
			foreach ($cmds[$eqLogic->getId()] as $cmd) {
				echo '<li class="alert alert-warning cmd cursor" data-id="' . $cmd->getId() . '"  data-name="' . $cmd->getName() . '">' . $cmd->getName();
				echo '<i class="fas fa-cog pull-right configureCmd" title="{{Configuration avancée}}"></i>';
				echo '</li>';
			}
			echo '</ul>';
			echo '</li>';
		}
		echo '</ul>';
		echo '</div>';
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
