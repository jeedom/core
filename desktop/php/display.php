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
sendVarToJs('_nbCmd_', $nbCmd);

if (file_exists(__DIR__ . '/../../data/remove_history.json')) {
	$remove_history = json_decode(file_get_contents(__DIR__ . '/../../data/remove_history.json'), true);
}
if (!is_array($remove_history)) {
	$remove_history = array();
}
?>
<br/>
<ul class="nav nav-tabs" role="tablist" id="ul_tabDisplay">
	<li role="presentation" class="active"><a href="#display" aria-controls="display" role="tab" data-toggle="tab"><i class="fas fa-th"></i> {{Résumé}}</a></li>
	<li role="presentation"><a href="#history" aria-controls="history" role="tab" data-toggle="tab"><i class="fas fa-trash"></i> {{Historique}}</a></li>
</ul>

<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
	<div role="tabpanel" class="tab-pane active" id="display">
		<br/>
		<div>
			<div class="pull-left">
				<span class="label label-default">{{Nombre d'objets :}} <?php echo count($objects) ?></span>
				<span class="label label-info">{{Nombre d'équipements :}} <?php echo $nbEqlogic ?></span>
				<span class="label label-primary">{{Nombre de commandes :}} <?php echo $nbCmd ?></span>
				<span title="{{Afficher les éléments inactifs}}"><label class="checkbox-inline"><input type="checkbox" id="cb_actifDisplay" checked />{{Inactifs}}</label></span>
			</div>

			<div class="pull-right">
				<div class="input-group">
					<a class="btn btn-danger btn-sm roundedLeft" id="bt_removeEqlogic" style="display:none;"><i class="far fa-trash-alt"></i> {{Supprimer}}
					</a><a class="btn btn-success btn-sm bt_setIsVisible" data-value="1" style="display:none;"><i class="fas fa-eye"></i> {{Visible}}
					</a><a class="btn btn-warning btn-sm bt_setIsVisible" data-value="0" style="display:none;"><i class="fas fa-eye-slash"></i> {{Invisible}}
					</a><a class="btn btn-success btn-sm bt_setIsEnable" data-value="1" style="display:none;"><i class="fas fa-check"></i> {{Actif}}
					</a><a class="btn btn-warning btn-sm bt_setIsEnable roundedRight" data-value="0" style="display:none;"><i class="fas fa-times"></i> {{Inactif}}</a>
				</div>
			</div>
		</div>
		<br/><br/>
		<div>
			<div class="input-group" style="margin-bottom:5px;display: inline-table;">
				<input class="form-control roundedLeft" placeholder="{{Rechercher}}" id="in_search"/>
				<div class="input-group-btn">
					<a id="bt_resetdisplaySearch" class="btn roundedRight" style="width:30px"><i class="fas fa-times"></i> </a>
				</div>
			</div>
		</div>

		<div>
			<div class="row packeryContainer">
			<?php
			if (count($eqLogics[-1]) > 0) {
				echo '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 object" data-id="-1">';
				echo '<div>';
				echo '<legend><i class="far fa-circle"></i>  {{Aucun}} <i class="fas fa-chevron-down pull-right showEqLogic cursor" title="{{Voir les équipements}}"></i></legend>';
				echo '<ul class="eqLogicSortable">';

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

				echo '</ul>';
				echo '</div>';
				echo '</div>';
			}

			foreach ($objects as $object) {
				echo '<div class="col-lg-4 col-md-4 col-sm-6 col-xs-12 object" data-id="' . $object->getId() . '">';
				if ($object->getConfiguration('useCustomColor') == 1) {
					echo '<div style="background-color: ' . $object->getDisplay('tagColor') . ';color: ' . $object->getDisplay('tagTextColor') . '">';
					echo '<legend style="color : ' . $object->getDisplay('tagTextColor') . '">' . $object->getDisplay('icon') . '  ' . $object->getName();
				} else {
					echo '<div class="labelObjectHuman">';
					echo '<legend class="labelObjectHuman">' . $object->getDisplay('icon') . '  ' . $object->getName();
				}
				echo '<i class="fas fa-chevron-down pull-right showEqLogic cursor" title="{{Voir les équipements}}"></i>';
				echo '<i class="fas fa-cog pull-right cursor configureObject" title="{{Configuration avancée}}"></i>';

				if ($object->getConfiguration('useCustomColor') == 1) {
					echo '<a style="color:' . $object->getDisplay('tagTextColor') . '" href="index.php?v=d&p=object&id=' . $object->getId() . '" target="_blank" class="pull-right" title="{{Aller sur la configuration de l\'objet}}"><i class="fas fa-external-link-alt"></i></a>';
				} else {
					echo '<a class="labelObjectHuman" href="index.php?v=d&p=object&id=' . $object->getId() . '" target="_blank" class="pull-right" title="{{Aller sur la configuration de l\'objet}}"><i class="fas fa-external-link-alt"></i></a>';
				}

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
			}
			?>
			</div>
		</div>
	</div>

	<div role="tabpanel" class="tab-pane" id="history">
		<br/>
		<div id="div_alertRemoveHistory"></div>
		<label class="label-sm"><i class="fas fa-trash"></i> {{Historique des suppressions}}</label>
		<a class="btn btn-danger btn-sm pull-right" id="bt_emptyRemoveHistory"><i class="fas fa-times"></i> {{Vider}}</a>
		<br/>
		<table class="table table-condensed table-bordered tablesorter" id="table_removeHistory">
			<thead>
				<tr>
					<th>{{Date}}</th>
					<th>{{Type}}</th>
					<th>{{ID}}</th>
					<th>{{Nom}}</th>
				</tr>
			</thead>
			<tbody>
				<?php
				if (count($remove_history) > 0) {
					foreach ($remove_history as $remove) {
						echo '<tr>';
						echo '<td>';
						echo $remove['date'];
						echo '</td>';
						echo '<td>';
						echo $remove['type'];
						echo '</td>';
						echo '<td>';
						echo $remove['id'];
						echo '</td>';
						echo '<td>';
						echo $remove['name'];
						echo '</td>';
						echo '</tr>';
					}
				}
				?>
			</tbody>
		</table>
	</div>

</div>

<?php include_file('desktop', 'display', 'js');?>
