<?php
global $JEEDOM_INTERNAL_CONFIG;
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$list = array();
foreach (eqLogic::all() as $eqLogic) {
	$battery_type = str_replace(array('(', ')'), array('', ''), $eqLogic->getConfiguration('battery_type', ''));
	if ($eqLogic->getStatus('battery', -2) != -2) {
		array_push($list, $eqLogic);
	}
}
usort($list, function ($a, $b) {
	return ($a->getStatus('battery') < $b->getStatus('battery')) ? -1 : (($a->getStatus('battery') > $b->getStatus('battery')) ? 1 : 0);
});
?>
<br/>
<ul class="nav nav-tabs reportModeHidden" role="tablist" id="ul_tabBatteryAlert">
	<li role="presentation" class="active batteries"><a href="#battery" aria-controls="battery" role="tab" data-toggle="tab"><i class="fas fa-battery-full"></i> {{Batteries}}</a></li>
	<li role="presentation" class="alerts"><a href="#alertEqlogic" aria-controls="alertEqlogic" role="tab" data-toggle="tab"><i class="fas fa-exclamation-triangle"></i> {{Modules en alerte}}</a></li>
	<li role="presentation"><a href="#actionCmd" aria-controls="actionCmd" role="tab" data-toggle="tab"><i class="fas fa-gears"></i> {{Actions définies}}</a></li>
	<li role="presentation"><a href="#alertCmd" aria-controls="actionCmd" role="tab" data-toggle="tab"><i class="fas fa-bell"></i> {{Alertes définies}}</a></li>
	<li role="presentation"><a href="#deadCmd" aria-controls="actionCmd" role="tab" data-toggle="tab"><i class="fab fa-snapchat-ghost"></i> {{Commandes orphelines}}</a></li>
</ul>

<div class="tab-content">
	<div role="tabpanel" class="tab-pane active" id="battery">
		<br/>
		<div class="batteryListContainer">
			<?php
			foreach ($list as $eqLogic) {
				echo $eqLogic->batteryWidget();
			}
			?>
		</div>
	</div>
	<div role="tabpanel" class="tab-pane" id="alertEqlogic">
		<br/>
		<div class="alertListContainer">
			<?php
			$hasAlert = false;
			foreach (eqLogic::all() as $eqLogic) {
				if ($eqLogic->getAlert() == '') {
					continue;
				}
				$hasAlert = true;
				echo $eqLogic->toHtml('dashboard');
			}
			if (!$hasAlert) {
				echo '<br/><div class="alert alert-success">{{Aucun module en Alerte pour le moment}}</div>';
			}
			?>
		</div>
	</div>
	<div role="tabpanel" class="tab-pane" id="actionCmd">
		<br/>
		<div class="cmdListContainer">
			<table class="table table-condensed tablesorter" id="table_Action">
				<thead>
					<tr>
						<th>{{Equipement}}</th>
						<th>{{Commande}}</th>
						<th>{{Type}}</th>
						<th>{{Actions}}</th>
						<th></th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach (eqLogic::all() as $eqLogic) {
						foreach ($eqLogic->getCmd('info') as $cmd) {
							if (count($cmd->getConfiguration('actionCheckCmd', array())) > 0) {
								echo '<tr><td><a href="' . $eqLogic->getLinkToConfiguration() . '" style="text-decoration: none;">' . $eqLogic->getHumanName(true) . '</a></td><td>' . $cmd->getName() . ' (' . $cmd->getId() . ')</td><td>{{Action sur état}}</td>';
								echo '<td>Si ' . $cmd->getConfiguration('jeedomCheckCmdOperator') . ' ' . $cmd->getConfiguration('jeedomCheckCmdTest') . ' {{plus de}} ' . $cmd->getConfiguration('jeedomCheckCmdTime') . ' {{minutes alors}} : ';
								$actions = '';
								foreach ($cmd->getConfiguration('actionCheckCmd') as $actionCmd) {
									$actions .= scenarioExpression::humanAction($actionCmd) . '|';
								}
								echo trim($actions, '|');
								echo '</td>';
								echo '<td>';
								echo '<a class="btn btn-default btn-xs cmdAction pull-right" data-action="configure" data-cmd_id="' . $cmd->getId() . '"><i class="fas fa-cogs"></i></a>';
								echo '</td>';
								echo '</tr>';
							}
						}
						foreach ($eqLogic->getCmd('action') as $cmd) {
							if (count($cmd->getConfiguration('jeedomPreExecCmd', array())) > 0) {
								echo '<tr><td><a href="' . $eqLogic->getLinkToConfiguration() . '" style="text-decoration: none;">' . $eqLogic->getHumanName(true) . '</a></td><td>' . $cmd->getName() . ' (' . $cmd->getId() . ')</td><td>{{Pre exécution}}</td><td>';
								$actions = '';
								foreach ($cmd->getConfiguration('jeedomPreExecCmd') as $actionCmd) {
									$actions .= scenarioExpression::humanAction($actionCmd) . '|';
								}
								echo trim($actions, '|');
								echo '</td>';
								echo '<td>';
								echo '<a class="btn btn-default btn-xs cmdAction pull-right" data-action="configure" data-cmd_id="' . $cmd->getId() . '"><i class="fas fa-cogs"></i></a>';
								echo '</td>';
								echo '</tr>';
							}
							if (count($cmd->getConfiguration('jeedomPostExecCmd', array())) > 0) {
								echo '<tr><td><a href="' . $eqLogic->getLinkToConfiguration() . '" style="text-decoration: none;">' . $eqLogic->getHumanName(true) . '</a></td><td>' . $cmd->getName() . ' (' . $cmd->getId() . ')</td><td>{{Post exécution}}</td><td>';
								$actions = '';
								foreach ($cmd->getConfiguration('jeedomPostExecCmd') as $actionCmd) {
									$actions .= scenarioExpression::humanAction($actionCmd) . '|';
								}
								echo trim($actions, '|');
								echo '</td>';
								echo '<td>';
								echo '<a class="btn btn-default btn-xs cmdAction pull-right" data-action="configure" data-cmd_id="' . $cmd->getId() . '"><i class="fas fa-cogs"></i></a>';
								echo '</td>';
								echo '</tr>';
							}
							if ($cmd->getConfiguration('actionConfirm')) {
								$code = '';
								if ($cmd->getConfiguration('actionCodeAccess')) {
									$code = ' avec code';
								}
								echo '<tr><td><a href="' . $eqLogic->getLinkToConfiguration() . '" style="text-decoration: none;">' . $eqLogic->getHumanName(true) . '</a></td><td>' . $cmd->getName() . ' (' . $cmd->getId() . ')</td><td>{{Confirmation}}' . $code . '</td><td>';
								echo 'Confirmation de l\'action' . $code;
								echo '</td>';
								echo '<td>';
								echo '<a class="btn btn-default btn-xs cmdAction pull-right" data-action="configure" data-cmd_id="' . $cmd->getId() . '"><i class="fas fa-cogs"></i></a>';
								echo '</td>';
								echo '</tr>';
							}
							if ($cmd->getConfiguration('actionCodeAccess') && !$cmd->getConfiguration('actionConfirm')) {
								echo '<tr><td><a href="' . $eqLogic->getLinkToConfiguration() . '" style="text-decoration: none;">' . $eqLogic->getHumanName(true) . '</a></td><td>' . $cmd->getName() . ' (' . $cmd->getId() . ')</td><td>{{Confirmation}}' . $code . '</td><td>';
								echo 'Code de confirmation de l\'action';
								echo '</td>';
								echo '<td>';
								echo '<a class="btn btn-default btn-xs cmdAction pull-right" data-action="configure" data-cmd_id="' . $cmd->getId() . '"><i class="fas fa-cogs"></i></a>';
								echo '</td>';
								echo '</tr>';
							}
						}
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
	
	<div role="tabpanel" class="tab-pane" id="alertCmd">
		<br/>
		<div class="cmdListContainer">
			<table class="table table-condensed tablesorter" id="table_deadCmd">
				<thead>
					<tr>
						<th>{{Equipement}}</th>
						<th>{{Alertes}}</th>
						<th>{{Timeout}}</th>
						<th>{{Seuils batterie}}</th>
					</tr>
				</thead>
				<tbody>
					<?php
					foreach (eqLogic::all() as $eqLogic) {
						$hasSomeAlerts = 0;
						$listCmds = array();
						foreach ($eqLogic->getCmd('info') as $cmd) {
							foreach ($JEEDOM_INTERNAL_CONFIG['alerts'] as $level => $value) {
								if (!$value['check']) {
									continue;
								}
								if ($cmd->getAlert($level . 'if', '') != '') {
									$hasSomeAlerts += 1;
									if (!in_array($cmd, $listCmds)) {
										$listCmds[] = $cmd;
									}
								}
							}
						}
						if ($eqLogic->getConfiguration('battery_warning_threshold', '') != '') {
							$hasSomeAlerts += 1;
						}
						if ($eqLogic->getConfiguration('battery_danger_threshold', '') != '') {
							$hasSomeAlerts += 1;
						}
						if ($eqLogic->getTimeout('')) {
							$hasSomeAlerts += 1;
						}
						if ($hasSomeAlerts != 0) {
							echo '<tr><td><a href="' . $eqLogic->getLinkToConfiguration() . '" style="text-decoration: none;">' . $eqLogic->getHumanName(true) . '</a></td>';
							echo '<td>';
							foreach ($listCmds as $cmdalert) {
								foreach ($JEEDOM_INTERNAL_CONFIG['alerts'] as $level => $value) {
									if (!$value['check']) {
										continue;
									}
									if ($cmdalert->getAlert($level . 'if', '') != '') {
										$during = $cmdalert->getAlert($level . 'during', '') == '' ? ' effet immédiat' : ' pendant plus de ' . $cmdalert->getAlert($level . 'during', '') . ' minute(s)';
										echo ucfirst($level) . ' si ' . jeedom::toHumanReadable(str_replace('#value#', '<b>' . $cmdalert->getName() . '</b>', $cmdalert->getAlert($level . 'if', ''))) . $during . '</br>';
									}
								}
							}
							echo '</td>';
							echo '<td>';
							if ($eqLogic->getTimeout('') != '') {
								echo $eqLogic->getTimeout('') . ' minute(s)';
							}
							echo '</td>';
							echo '<td>';
							if ($eqLogic->getConfiguration('battery_danger_threshold', '') != '') {
								echo '<label class="col-xs-6 eqLogicAttr label label-danger" style="font-size : 0.8em">{{Danger}} ' . $eqLogic->getConfiguration('battery_danger_threshold', '') . ' % </label>';
							}
							if ($eqLogic->getConfiguration('battery_warning_threshold', '') != '') {
								echo '<label class="col-xs-6 label label-warning" style="font-size : 0.8em;">{{Warning}} ' . $eqLogic->getConfiguration('battery_warning_threshold', '') . ' % </label>';
							}
							echo '</td></tr>';
						}
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
	
	<div role="tabpanel" class="tab-pane" id="deadCmd">
		<br/>
		<div class="cmdListContainer">
			<table class="table table-condensed tablesorter" id="table_deadCmd">
				<thead>
					<tr>
						<th>{{Type}}</th>
						<th>{{Détail}}</th>
						<th>{{Commande}}</th>
						<th>{{Utilisation}}</th>
					</tr>
				</thead>
				<tbody>
					<?php
					
					foreach (jeedom::deadCmd() as $datas) {
						echo '<tr>';
						echo '<td>Core</td>';
						echo '<td>' . $datas['detail'] . '</td>';
						echo '<td>' . $datas['who'] . '</td>';
						echo '<td>' . $datas['help'] . '</td>';
						echo '</tr>';
					}
					foreach (cmd::deadCmd() as $datas) {
						echo '<tr>';
						echo '<td>Commande</td>';
						echo '<td>' . $datas['detail'] . '</td>';
						echo '<td>' . $datas['who'] . '</td>';
						echo '<td>' . $datas['help'] . '</td>';
						echo '</tr>';
					}
					foreach (jeeObject::deadCmd() as $datas) {
						echo '<tr>';
						echo '<td>Résumé</td>';
						echo '<td>' . $datas['detail'] . '</td>';
						echo '<td>' . $datas['who'] . '</td>';
						echo '<td>' . $datas['help'] . '</td>';
						echo '</tr>';
					}
					foreach (scenario::consystencyCheck(true) as $datas) {
						echo '<tr>';
						echo '<td>Scénario</td>';
						echo '<td>' . $datas['detail'] . '</td>';
						echo '<td>' . $datas['who'] . '</td>';
						echo '<td>' . $datas['help'] . '</td>';
						echo '</tr>';
					}
					foreach (interactDef::deadCmd() as $datas) {
						echo '<tr>';
						echo '<td>Interaction</td>';
						echo '<td>' . $datas['detail'] . '</td>';
						echo '<td>' . $datas['who'] . '</td>';
						echo '<td>' . $datas['help'] . '</td>';
						echo '</tr>';
					}
					#vues/designs
					foreach (plugin::listPlugin(true) as $plugin) {
						$plugin_id = $plugin->getId();
						if (method_exists($plugin_id, 'deadCmd')) {
							foreach ($plugin_id::deadCmd() as $datas) {
								echo '<tr>';
								echo '<td>Plugin ' . $plugin->getName() . '</td>';
								echo '<td>' . $datas['detail'] . '</td>';
								echo '<td>' . $datas['who'] . '</td>';
								echo '<td>' . $datas['help'] . '</td>';
								echo '</tr>';
							}
						}
					}
					?>
				</tbody>
			</table>
		</div>
	</div>
</div>

<?php include_file('desktop', 'eqAnalyse', 'js');?>
