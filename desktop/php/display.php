<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
global $JEEDOM_INTERNAL_CONFIG;
$nbEqlogic = 0;
$nbCmd = 0;
global $display_objects;
$display_objects = jeeObject::all(false, true);
global $display_eqlogics;
$display_eqlogics = array();
global $display_cmds;
$display_cmds = array();
$display_eqlogics[-1] = eqLogic::byObjectId(null, false);
foreach ($display_eqlogics[-1] as $eqLogic) {
	$display_cmds[$eqLogic->getId()] = $eqLogic->getCmd();
	$nbCmd += count($display_cmds[$eqLogic->getId()]);
}
$nbEqlogic += count($display_eqlogics[-1]);
foreach ($display_objects as $object) {
	$display_eqlogics[$object->getId()] = $object->getEqLogic(false, false);
	foreach ($display_eqlogics[$object->getId()] as $eqLogic) {
		$display_cmds[$eqLogic->getId()] = $eqLogic->getCmd();
		$nbCmd += count($display_cmds[$eqLogic->getId()]);
	}
	$nbEqlogic += count($display_eqlogics[$object->getId()]);
}
sendVarToJs('_nbCmd_', $nbCmd);
$remove_history = jeedom::getRemovehistory();
$plugin_enable = config::getPluginEnable();

function jeedom_displayObjectGroup($object=-1) {
	global $JEEDOM_INTERNAL_CONFIG;
	global $display_objects, $display_eqlogics, $display_cmds;
	
	if (!is_object($object) && $object == -1) {
		$_index = 'none';
		$numParents = 0;
		$objectId = -1;
		$objectName = '{{Aucun}}';
		$objecUseCustomColor = 0;
		$objectIcon = '<i class="far fa-circle"></i>';
	} else {
		if (!is_object($object)) return;
		$_index = $object->getId();
		$numParents = $object->getConfiguration('parentNumber');
		$objectId = $object->getId();
		$objectName = $object->getName();
		$objecUseCustomColor = $object->getConfiguration('useCustomColor');
		$objectIcon = $object->getDisplay('icon');
	}
	
	$div = '';
	//hierarchy decay:
	if ($numParents > 0) {
		$aStyle = ' style="margin-left:' . 30*$numParents . 'px;"';
	} else {
		$aStyle = ' style="margin-left:5px"';
	}
	$div .= '<div class="panel panel-default objectSortable" '.$aStyle.'">';
	$div .= '<div class="panel-heading" data-id="'.$objectId.'">';
	//custom colors panel-title:
	if ($objecUseCustomColor == 1) {
		$aStyle = 'style="color:'.$object->getDisplay('tagTextColor').'!important"';
		$div .= '<h3 class="panel-title" style="background-color:'.$object->getDisplay('tagColor').'; width:calc(100% - 100px);display: inline-block;">';
		$div .= '<a '.$aStyle.'class="accordion-toggle" data-toggle="collapse" data-parent="" aria-expanded="false" href="#config_'.$_index.'" style="color:'.$object->getDisplay('tagTextColor').'!important">'.$objectIcon.' '.$objectName;
	} else {
		$div .= '<h3 class="panel-title" style="width:calc(100% - 100px);display: inline-block;">';
		$div .= '<a class="accordion-toggle" data-toggle="collapse" data-parent="" aria-expanded="false" href="#config_'.$_index.'">'.$objectIcon.' '.$objectName;
	}
	$div .= '</a></h3>';
	//second panel-title trick for functions on the right:
	$div .= '<h3 class="panel-title" style="background-color:var(--defaultBkg-color); width:100px;display: inline;">';
	$div .= '<i class="fas fa-cog pull-right cursor configureObject" title="{{Configuration avancée}}"></i>';
	$div .= '<a href="/index.php?v=d&p=object&id='.$objectId.'" target="_blank" class="pull-right" title="{{Aller sur la configuration de l\'objet}}"><i class="fas fa-external-link-square-alt"></i></a>';
	$div .= '<i class="fas fa-square pull-right cursor objectUnselectEqlogics" title="{{Désélectionner les équipements}}"></i>';
	$div .= '<i class="fas fa-check-square pull-right cursor objectSelectEqlogics" title="{{Sélectionner les équipements}}"></i>';
	$div .= '</h3>';
	$div .= '</div>';
	
	//inner panel:
	$div .= '<div id="config_'.$_index.'" class="panel-collapse collapse">';
	$div .= '<div class="panel-body">';
	
	//eqLogics ul with cmds ul inside:
	$div .= '<ul class="eqLogicSortable">';
	foreach ($display_eqlogics[$objectId] as $eqLogic) {
		$translate_category = '';
		foreach ($JEEDOM_INTERNAL_CONFIG['eqLogic']['category'] as $key => $value) {
			if ($eqLogic->getCategory($key, 0) == 1) {
				$translate_category .= __($value['name'],__FILE__).',';
			}
		}
		$translate_category = trim($translate_category,',');
		$div .= '<li class="eqLogic cursor" data-id="'.$eqLogic->getId().'" data-translate-category="'.$translate_category.'" data-enable="'.$eqLogic->getIsEnable().'" data-name="'.$eqLogic->getName().'" data-type="'.$eqLogic->getEqType_name().'">';
		$div .= '<input type="checkbox" class="cb_selEqLogic" /> ';
		$div .= $eqLogic->getId(). ' | ' . $eqLogic->getEqType_name() .' | '.$eqLogic->getName();
		if ($eqLogic->getIsEnable() != 1) {
			$div .= '<i class="fas fa-times" title="{{Non actif}}"></i> ';
		}
		if ($eqLogic->getIsVisible() != 1) {
			$div .= '<i class="fas fa-eye-slash" title="{{Non visible}}"></i> ';
		}
		if(!isset($plugin_enable[$eqLogic->getEqType_name()]) || $plugin_enable[$eqLogic->getEqType_name()] == 1){
			$div .= '<i class="fas fa-cog pull-right configureEqLogic" title="{{Configuration avancée}}"></i>';
			$div .= '<a href="' . $eqLogic->getLinkToConfiguration() . '" target="_blank" class="pull-right" title="{{Aller sur la configuration de l\'équipement}}"><i class="fas fa-external-link-alt"></i></a>';
		}
		
		//cmds ul:
		$div .= '<ul class="cmdSortable" style="display:none;" >';
		foreach ($display_cmds[$eqLogic->getId()] as $cmd) {
			$div .= '<li class="alert alert-info cmd cursor" data-id="' . $cmd->getId() . '"  data-name="' . $cmd->getName() . '">' ;
			$div .= '<input type="checkbox" class="cb_selCmd"> ';
			$div .=  $cmd->getId().' | '.$cmd->getName();
			if ($cmd->getIsVisible() != 1) {
				$div .= ' <i class="fas fa-eye-slash" title="{{Non visible}}"></i> ';
			}
			if(!isset($plugin_enable[$eqLogic->getEqType_name()]) || $plugin_enable[$eqLogic->getEqType_name()] == 1){
				$div .= '<i class="fas fa-cog pull-right configureCmd" title="{{Configuration avancée}}"></i>';
			}
			$div .= '</li>';
		}
		$div .= '</ul>';
		$div .= '</li>';
	}
	$div .= '</ul>';
	$div .= '</div>';
	$div .= '</div>';
	$div .= '</div>';
	return $div;
}
?>

<div class="row row-overflow">
	<div class="hasfloatingbar col-xs-12">
		<div class="eqActions floatingbar" style="display:none;">
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
			<li role="presentation"><a href="#historytab" aria-controls="historytab" role="tab" data-toggle="tab"><i class="fas fa-trash"></i> {{Historique de suppression}}</a></li>
		</ul>
		
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="displaytab">
				<br/>
				<div>
					<div class="pull-left">
						<span class="label label-default">{{Nombre d'objets :}} <?php echo count($display_objects) ?></span>
						<span class="label label-info">{{Nombre d'équipements :}} <?php echo $nbEqlogic ?></span>
						<span class="label label-primary">{{Nombre de commandes :}} <?php echo $nbCmd ?></span>
						<span title="{{Afficher les éléments inactifs}}"><input type="checkbox" id="cb_actifDisplay" checked />{{Inactifs}}</span>
					</div>
					<a href="#" class="btn btn-sm btn-success pull-right bt_exportcsv" download="Jeedom_IDs.csv"><i class="fas fa-file-export"></i> {{Export CSV}}</a>
				</div>
				<br/><br/>
				<div>
					<div class="input-group" style="margin-bottom:5px;display: inline-table;">
						<input class="form-control roundedLeft" placeholder="{{Rechercher | nom | id | :not(nom}}" id="in_search"/>
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
					if (count($display_eqlogics[-1]) > 0) {
						echo jeedom_displayObjectGroup(-1);
					}
					
					foreach ($display_objects as $object) {
						echo jeedom_displayObjectGroup($object);
					}
					?>
				</div>
			</div>
			
			<div role="tabpanel" class="tab-pane" id="historytab">
				<div id="div_alertRemoveHistory"></div>
				<a class="btn btn-danger btn-sm floatingbar" id="bt_emptyRemoveHistory"><i class="fas fa-times"></i> {{Vider}}</a>
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
								switch ($remove['type']) {
									case 'cmd':
									$tr .= '<i class="fas fa-terminal"></i> '.$remove['type'];
									break;
									case 'eqLogic':
									$tr .= '<i class="fas fa-cog"></i> '.$remove['type'];
									break;
									case 'object':
									$tr .= '<i class="far fa-object-group"></i> '.$remove['type'];
									break;
									case 'scenario':
									$tr .= '<i class="fas fa-cogs"></i> '.$remove['type'];
									break;
									case 'plan':
									$tr .= '<i class="fas fa-paint-brush"></i> '.$remove['type'];
									break;
									case 'plan3d':
									$tr .= '<i class="fas fa-cubes"></i> '.$remove['type'];
									break;
									case 'view':
									$tr .= '<i class="far fa-image"></i> '.$remove['type'];
									break;
									case 'user':
									$tr .= '<i class="fas fa-users"></i> '.$remove['type'];
									break;
									default:
									$tr .= $remove['type'];
								}
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
	</div>
</div>

<?php include_file('desktop', 'display', 'js');?>
