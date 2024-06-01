<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$count = 0;
$globalhtml = '';
$totalNok = 0;
$totalPending = 0;
foreach ((plugin::listPlugin(true)) as $plugin) {
	$plugin_id = $plugin->getId();
	$hasSpecificHealth = 0;
	$hasSpecificHealthIcon = '';
	$html = '';
	$daemonInfo = '';
	$port = '';
	$asNok = 0;
	$asPending = 0;
	if ($plugin->getHasOwnDeamon() == 1) {
		if ($plugin->deamon_info()['auto'] == 1) {
			$daemonInfo = ' <i class="fas fa-university" title="{{Démon en mode automatique}}"></i>';
		} else {
			$daemonInfo = ' <i class="fas fa-university" style="color:#ff4c4c;" title="{{Démon en mode manuel}}"></i>';
		}
	}
	if (config::byKey('port', $plugin->getId()) != '') {
		$port = ' <i class="icon techno-fleches" title="{{Port configuré}}"></i><span title="{{Port configuré}}"> ' . ucfirst(config::byKey('port', $plugin->getId())) . '</span>';
	}
	if (file_exists(dirname(plugin::getPathById($plugin_id)) . '/../desktop/modal/health.php')) {
		$hasSpecificHealth = 1;
		$hasSpecificHealthIcon = ' <i data-pluginname="' . $plugin->getName() . '" data-pluginid="' . $plugin->getId() . '" class="fas fa-medkit bt_healthSpecific cursor" title="{{Santé spécifique}}"></i>';
	}
	if ($plugin->getHasDependency() == 1 || $plugin->getHasOwnDeamon() == 1 || method_exists($plugin->getId(), 'health') || $hasSpecificHealth == 1) {
		$count += 1;
		$globalhtml .= '<div class="panel panel-default">';
		$globalhtml .= ' <div class="panel-heading">
		<h3 class="panel-title cursor">';
		if ($plugin->getHasDependency() == 1 || $plugin->getHasOwnDeamon() == 1 || method_exists($plugin->getId(), 'health')) {
			$html .= '<table class="table table-condensed">';
			$html .= '<tbody>';
		} else {
			$html .= ' <span class="label label-primary">{{Aucune santé spécifique}}</span>';
		}
	}
	try {
		if ($plugin->getHasDependency() == 1) {
			$dependancy_info = $plugin->dependancy_info();
			$html .= '<tr>';
			$html .= '<td>';
			$html .= '{{Dépendances}}';
			$html .= '</td>';
			switch ($dependancy_info['state']) {
				case 'ok':
				$html .= '<td class="alert alert-success" >{{OK}}</td>';
				break;
				case 'nok':
				$html .= '<td class="alert alert-danger" >{{NOK}}</td>';
				$asNok += 1;
				break;
				case 'in_progress':
				$html .= '<td class="alert alert-info">{{En cours}}</td>';
				$asPending += 1;
				break;
				default:
				$html .= '<td class="alert alert-danger">{{NOK}}</td>';
				$asNok += 1;
				break;
			}
			$html .= '</tr>';
		}
	} catch (Exception $e) {

	}
	try {
		if ($plugin->getHasOwnDeamon() == 1) {
			$alert = 'alert-danger';
			$deamon_info = $plugin->deamon_info();
			if ($deamon_info['auto'] != 1) {
				$alert = 'alert-success';
			}
			$html .= '<tr>';
			$html .= '<td>';
			$html .= '{{Configuration démon}}';
			if ($deamon_info['launchable_message'] != '') {
				$html .= ' <sup><i class="fas fa-question-circle tooltips" title="'.$deamon_info['launchable_message'].'"></i></sup>';
			}
			$html .= '</td>';
			switch ($deamon_info['launchable']) {
				case 'ok':
				$html .= '<td class="alert alert-success">{{OK}}</td>';
				break;
				case 'nok':
				if ($deamon_info['auto'] != 1) {
					$html .= '<td class="alert alert-success">{{Désactivé}}</td>';
				} else {
					$html .= '<td class="alert alert-danger" title="' . $deamon_info['launchable_message'] . '">{{NOK}}</td>';
					$asNok += 1;
				}
				break;
			}
			$html .= '</tr>';
			$html .= '<tr>';
			$html .= '<td>';
			$html .= '{{Statut démon}}';
			$html .= '</td>';
			switch ($deamon_info['state']) {
				case 'ok':
				$html .= '<td class="alert alert-success">';
				$html .= '{{OK}}</td>';
				break;
				case 'nok':
				if ($deamon_info['auto'] != 1) {
					$html .= '<td class="alert alert-success">{{Désactivé}}</td>';
				} else {
					$html .= '<td class="alert alert-danger">{{NOK}}</td>';
					$asNok += 1;
				}
				break;
			}
			$html .= '</tr>';
		}
	} catch (Exception $e) {

	}

	try {
		if (method_exists($plugin->getId(), 'health')) {
			foreach (($plugin_id::health()) as $result) {
				$html .= '<tr>';
				$html .= '<td>';
				$html .= $result['test'];
				if ($result['advice'] != '') {
					$html .= ' <sup><i class="fas fa-question-circle tooltips" title="'.$result['advice'].'"></i></sup>';
				}
				$html .= '</td>';
				if ($result['state']) {
					$html .= '<td class="alert alert-success">';
				} else {
					$html .= '<td class="alert alert-danger">';
					$asNok += 1;
				}
				$html .= $result['result'];
				$html .= '</td>';
				$html .= '</tr>';
			}
		}
	} catch (Exception $e) {

	}
	if ($plugin->getHasDependency() == 1 || $plugin->getHasOwnDeamon() == 1 || method_exists($plugin->getId(), 'health')) {
		$html .= '</tbody>';
		$html .= '</table>';
	}
	if ($html != '') {
		$globalhtml .= '<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionHealth" href="#config_' . $plugin->getId() . '">';
		$globalhtml .= '<img class="img-responsive" style="width : 20px;display:inline-block;" src="' . $plugin->getPathImgIcon() . '" /> ';
		$globalhtml .= '{{Santé}}' . ' ' . $plugin->getName() . '</a> ';

		$globalhtml .= '<div class="pull-right"><i class="fas fa-cogs bt_configurationPlugin cursor" title="{{Configuration du plugin}}" data-pluginid="' . $plugin->getId() . '"></i> ' . $hasSpecificHealthIcon . $daemonInfo . $port;
			$errorMessage = '';
			$pendingMessage = '';
			if ($asNok != 0) {
				$totalNok += 1;
				$errorMessage = ' <span class="label label-danger">' . $asNok . ' {{erreurs}} </span>';
			}
			if ($asPending != 0) {
				$totalPending += 1;
				$pendingMessage = ' <span class="label label-warning">' . $asPending . ' {{en cours}} </span>';
			}
			if ($asPending == 0 && $asNok == 0) {
				$errorMessage = ' <span class="label label-success">{{OK}}</span>';
			}
			$globalhtml .= $errorMessage . $pendingMessage;
		$globalhtml .= '</div>';

		$globalhtml .= '</h3>';
		$globalhtml .= '</div>';

		$globalhtml .= '<div id="config_' . $plugin->getId() . '" class="panel-collapse collapse">';
		$globalhtml .= '<div class="panel-body">';
		$globalhtml .= $html;
		$globalhtml .= '</div>';
		$globalhtml .= '</div>';
		$globalhtml .= '</div>';
	}
}
?>

<br/>
<div class="panel-group" id="accordionHealth">
	<div class="panel panel-default" style="border-left: 1px solid var(--logo-primary-color);border-color: var(--logo-primary-color)!important;">
		<div class="panel-heading">
			<h3 class="panel-title cursor">
				<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionHealth" href="#health_jeedom">
					<i class="icon divers-caduceus3 success" style="font-size:22px;"></i> <span style="font-size:18px;">{{Santé de}} <?php echo config::byKey('product_name') ?></span>
				</a>
				<i id="bt_benchmarkJeedom" class="fas fa-tachometer-alt pull-right cursor" title="{{Benchmark Jeedom}}"></i>
			</h3>
		</div>
		<div id="health_jeedom" class="panel-collapse collapse in" aria-expanded="true">
			<div class="panel-body">
				<table id="jeedomTable" class="table table-condensed">
					<tbody>
						<?php
							$count = 0;
							$echo = '';
							foreach ((jeedom::health()) as $datas) {
								if ($count % 2 == 0) $echo .= '<tr>';
								$echo .= '<td>';
								$echo .= $datas['name'];
								if ($datas['comment'] != '') {
									$echo .= ' <sup><i class="fas fa-question-circle tooltips" title="' . $datas['comment'] . '"></i></sup>';
								}
								$echo .= '</td>';
								if ($datas['state'] === 2) {
									$echo .= '<td class="alert-warning">';
								} else if ($datas['state']) {
									$echo .= '<td class="alert-success">';
								} else {
									$echo .= '<td class="alert-danger">';
								}
								$echo .= $datas['result'];
								$echo .= '</td>';
								if (++$count % 2 == 0 || $globalhtml == '') $echo .= '</tr>';
							}
							echo $echo;

							if ($globalhtml != '') {
								$echo = '';
								if ($count % 2 == 0) $echo .= '<tr>';
								$echo .= '<td>{{Plugins}} <sup><i class="fas fa-question-circle" title="{{Vous pouvez voir les détails des plugins sur la partie basse de cette page}}"></i></sup></td>';
								if ($totalNok == 0 && $totalPending == 0) {
									$echo .= '<td class="alert alert-success">{{OK}}</td>';
									$echo .= '<td></td>';
								} else if ($totalNok == 0 && $totalPending != 0) {
									$echo .= '<td class="alert alert-warning">' . $totalPending . ' {{En cours}}</td>';
								} else if ($totalNok != 0) {
									$pending = '';
									if ($totalPending != 0) {
										$pending = ' {{et}} ' . $totalPending . ' {{En cours}}';
									}
									$echo .= '<td class="alert alert-danger">' . $totalNok . ' {{NOK}}' . $pending . '</td>';
								}
								$echo .= '</tr>';
								echo $echo;
							}
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<div class="panel panel-default" style="border-left: 1px solid var(--logo-primary-color);border-color: var(--logo-primary-color)!important;">
		<div class="panel-heading">
			<h3 class="panel-title cursor">
				<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionHealth" href="#health_phpextension">
					<i class="fab fa-php" style="font-size:22px;"></i> <span style="font-size:18px;">{{Extensions php}}</span>
				</a>
			</h3>
		</div>
		<div id="health_phpextension" class="panel-collapse collapse" aria-expanded="true">
			<div class="panel-body">
				<table id="jeedomTable" class="table table-condensed">
					<tbody>
						<?php
						$count = 0;
						$tr = '';
						foreach ((get_loaded_extensions()) as $name) {
							if ($count % 10 == 0) $tr .= '<tr>';
							$tr .= '<td>';
							$tr .= $name;
							$tr .= '</td>';
							if (++$count % 10 == 0) $tr .= '</tr>';
						}
						if ($count % 10 != 0) $tr .= '</tr>';
						echo $tr;
						?>
					</tbody>
				</table>
			</div>
		</div>
	</div>
	<?php
	echo $globalhtml;
	?>
</div>

<?php	include_file("desktop", "health", "js");?>
