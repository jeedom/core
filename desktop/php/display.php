<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
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
<div class="eqActions pull-right">
	<div class="input-group">
		<a class="btn btn-danger btn-sm roundedLeft" id="bt_removeEqlogic" style="display:none;"><i class="far fa-trash-alt"></i> {{Supprimer}}
		</a><a class="btn btn-success btn-sm bt_setIsVisible" data-value="1" style="display:none;"><i class="fas fa-eye"></i> {{Visible}}
		</a><a class="btn btn-warning btn-sm bt_setIsVisible" data-value="0" style="display:none;"><i class="fas fa-eye-slash"></i> {{Invisible}}
		</a><a class="btn btn-success btn-sm bt_setIsEnable" data-value="1" style="display:none;"><i class="fas fa-check"></i> {{Actif}}
		</a><a class="btn btn-warning btn-sm bt_setIsEnable roundedRight" data-value="0" style="display:none;"><i class="fas fa-times"></i> {{Inactif}}</a>
	</div>
</div>
<ul class="nav nav-tabs" role="tablist" id="ul_tabDisplay">
	<li role="presentation" class="active"><a href="#displaytab" aria-controls="displaytab" role="tab" data-toggle="tab"><i class="fas fa-th"></i> {{Résumé}}</a></li>
	<li role="presentation"><a href="#historytab" aria-controls="historytab" role="tab" data-toggle="tab"><i class="fas fa-trash"></i> {{Historique}}</a></li>
</ul>

<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
	<div role="tabpanel" class="tab-pane active" id="displaytab">
		<br/>
		<div>
			<div class="pull-left">
				<span class="label label-default">{{Nombre d'objets :}} <?php echo count($objects) ?></span>
				<span class="label label-info">{{Nombre d'équipements :}} <?php echo $nbEqlogic ?></span>
				<span class="label label-primary">{{Nombre de commandes :}} <?php echo $nbCmd ?></span>
				<span title="{{Afficher les éléments inactifs}}"><label class="checkbox-inline"><input type="checkbox" id="cb_actifDisplay" checked />{{Inactifs}}</label></span>
			</div>
          	<a href="#" class="btn btn-sm btn-success pull-right bt_exportcsv" download="Jeedom_IDs.csv"><i class="fas fa-file-export"></i> {{Export CSV}}</a>
		</div>
		<br/><br/>
		<div>
			<div class="input-group" style="margin-bottom:5px;display: inline-table;">
				<input class="form-control roundedLeft" placeholder="{{Rechercher}}" id="in_search"/>
				<div class="input-group-btn">
					<a id="bt_resetdisplaySearch" class="btn" style="width:30px"><i class="fas fa-times"></i>
					</a><a class="btn" id="bt_openAll"><i class="fas fa-folder-open"></i>
					</a><a class="btn roundedRight" id="bt_closeAll"><i class="fas fa-folder"></i></a>
				</div>
			</div>
		</div>
		
		<div class="panel-group" id="accordionObject">
			<?php
			//No parent objects:
			if (count($eqLogics[-1]) > 0) {
				$div = '';
				$div .= '<div class="panel panel-default objectSortable">';
				$div .= '<div class="panel-heading" data-id="-1">';
				$div .= '<h3 class="panel-title">';
				$div .= '<a class="accordion-toggle" data-toggle="collapse" data-parent="" aria-expanded="false" href="#config_none"><i class="far fa-circle"></i> {{Aucun}}';
				$div .= '</a>';
				$div .= '</div>';
				$div .= '<div id="config_none" class="panel-collapse collapse">';
				$div .= '<div class="panel-body">';
				
				$div .= '<ul class="eqLogicSortable">';
				foreach ($eqLogics[-1] as $eqLogic) {
					$div .= '<li class="eqLogic cursor" data-id="' . $eqLogic->getId() . '" data-enable="' . $eqLogic->getIsEnable() . '" data-name="' . $eqLogic->getName() . '" data-type="' . $eqLogic->getEqType_name() . '">';
					$div .= '<input type="checkbox" class="cb_selEqLogic" /> ';
					$div .= $eqLogic->getId(). ' | ' . $eqLogic->getEqType_name() .' | '.$eqLogic->getName();
					if ($eqLogic->getIsEnable() != 1) {
						$div .= '<i class="fas fa-times" title="{{Non actif}}"></i> ';
					}
					if ($eqLogic->getIsVisible() != 1) {
						$div .= '<i class="fas fa-eye-slash" title="{{Non visible}}"></i> ';
					}
					$div .= '<i class="fas fa-cog pull-right configureEqLogic" title="{{Configuration avancée}}"></i>';
					$div .= '<a href="' . $eqLogic->getLinkToConfiguration() . '" target="_blank" class="pull-right" title="{{Aller sur la configuration de l\'équipement}}"><i class="fas fa-external-link-alt"></i></a>';
					$div .= '<ul class="cmdSortable" style="display:none;" >';
					foreach ($cmds[$eqLogic->getId()] as $cmd) {
						$div .= '<li class="alert alert-info cmd cursor" data-id="' . $cmd->getId() . '"  data-name="' . $cmd->getName() . '">' ;
						$div .= '<input type="checkbox" class="cb_selCmd" /> ';
						$div .=  $cmd->getId().' | '.$cmd->getName();
						if ($cmd->getIsVisible() != 1) {
							$div .= ' <i class="fas fa-eye-slash" title="{{Non visible}}"></i> ';
						}
						$div .= '<i class="fas fa-cog pull-right configureCmd" title="{{Configuration avancée}}"></i>';
						$div .= '</li>';
					}
					$div .= '</ul>';
					$div .= '</li>';
				}
				$div .= '</ul>';
				$div .= '</div>';
				$div .= '</div>';
				$div .= '</div>';
				echo $div;
			}
			
			//one panel per parent:
			$i = 0;
			$div = '';
			foreach ($objects as $object) {
				$numParents = $object->getConfiguration('parentNumber');
				if ($numParents > 0) {
					$aStyle = ' style="margin-left:' . (10 + 10*$object->getConfiguration('parentNumber')) . 'px;"';
				} else {
					$aStyle = ' style=""';
				}
				$div .= '<div class="panel panel-default objectSortable">';
				$div .= '<div class="panel-heading" data-id="'.$object->getId().'">';
				if ($object->getConfiguration('useCustomColor') == 1) {
					$aStyle = str_replace('style="', 'style="color:'.$object->getDisplay('tagTextColor').'!important;', $aStyle);
					$div .= '<h3 class="panel-title" style="background-color:'.$object->getDisplay('tagColor').'; width:calc(100% - 55px);display: inline-block;">';
					$div .= '<a '.$aStyle.'class="accordion-toggle" data-toggle="collapse" data-parent="" aria-expanded="false" href="#config_'.$i.'" style="color:'.$object->getDisplay('tagTextColor').'!important">'.$object->getDisplay('icon').' '.$object->getName();
				} else {
					$div .= '<h3 class="panel-title" style="width:calc(100% - 55px);display: inline-block;">';
					$div .= '<a '.$aStyle.'class="accordion-toggle" data-toggle="collapse" data-parent="" aria-expanded="false" href="#config_'.$i.'">'.$object->getDisplay('icon').' '.$object->getName();
				}
				$div .= '</a></h3>';
				$div .= '<h3 class="panel-title" style="background-color:var(--defaultBkg-color); width:55px;display: inline;">';
				$div .= '<i class="fas fa-cog pull-right cursor configureObject" title="{{Configuration avancée}}"></i>';
				$div .= '<a href="/index.php?v=d&p=object&id=' . $object->getId() . '" target="_blank" class="pull-right" title="{{Aller sur la configuration de l\'équipement}}"><i class="fas fa-external-link-alt"></i></a></h3>';
				$div .= '</div>';
				
				$div .= '<div id="config_'.$i.'" class="panel-collapse collapse">';
				$div .= '<div class="panel-body">';
				
				$div .= '<ul class="eqLogicSortable">';
				foreach ($eqLogics[$object->getId()] as $eqLogic) {
					$div .= '<li class="eqLogic cursor" data-id="'.$eqLogic->getId().'" data-enable="'.$eqLogic->getIsEnable().'" data-name="'.$eqLogic->getName().'" data-type="'.$eqLogic->getEqType_name().'">';
					$div .= '<input type="checkbox" class="cb_selEqLogic" /> ';
					$div .= $eqLogic->getId(). ' | ' . $eqLogic->getEqType_name() .' | '.$eqLogic->getName();
					if ($eqLogic->getIsEnable() != 1) {
						$div .= '<i class="fas fa-times" title="{{Non actif}}"></i> ';
					}
					if ($eqLogic->getIsVisible() != 1) {
						$div .= '<i class="fas fa-eye-slash" title="{{Non visible}}"></i> ';
					}
					$div .= '<i class="fas fa-cog pull-right configureEqLogic" title="{{Configuration avancée}}"></i>';
					$div .= '<a href="' . $eqLogic->getLinkToConfiguration() . '" target="_blank" class="pull-right" title="{{Aller sur la configuration de l\'équipement}}"><i class="fas fa-external-link-alt"></i></a>';
					$div .= '<ul class="cmdSortable" style="display:none;" >';
					
					foreach ($cmds[$eqLogic->getId()] as $cmd) {
						$div .= '<li class="alert alert-info cmd cursor" data-id="' . $cmd->getId() . '"  data-name="' . $cmd->getName() . '">' ;
						$div .= '<input type="checkbox" class="cb_selCmd"> ';
						$div .=  $cmd->getId().' | '.$cmd->getName();
						if ($cmd->getIsVisible() != 1) {
							$div .= ' <i class="fas fa-eye-slash" title="{{Non visible}}"></i> ';
						}
						$div .= '<i class="fas fa-cog pull-right configureCmd" title="{{Configuration avancée}}"></i>';
						$div .= '</li>';
					}
					$div .= '</ul>';
					$div .= '</li>';
				}
				$i++;
				$div .= '</ul>';
				$div .= '</div>';
				$div .= '</div>';
				$div .= '</div>';
			}
			echo $div;
			$div = null;
			?>
		</div>
	</div>
	
	<div role="tabpanel" class="tab-pane" id="historytab">
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
						$tr = '<tr>';
						$tr .= '<td>';
						$tr .= $remove['date'];
						$tr .= '</td>';
						$tr .= '<td>';
						$tr .= $remove['type'];
						$tr .= '</td>';
						$tr .= '<td>';
						$tr .= $remove['id'];
						$tr .= '</td>';
						$tr .= '<td>';
						$tr .= $remove['name'];
						$tr .= '</td>';
						$tr .= '</tr>';
						echo $tr;
					}
				}
				?>
			</tbody>
		</table>
	</div>
	
</div>

<?php include_file('desktop', 'display', 'js');?>
