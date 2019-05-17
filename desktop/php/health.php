<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$starttime = getmicrotime();
?>
<br/>
<div class="panel-group" id="accordionHealth">
	<div class="panel panel-default" style="border-left: 1px solid var(--logo-primary-color);border-color: var(--logo-primary-color)!important;">
		<div class="panel-heading">
			<h3 class="panel-title">
				<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionHealth" href="#health_jeedom">
					<i class="icon divers-caduceus3 success" style="font-size:22px;"></i> <span style="font-size:18px;">{{Santé de Jeedom}}</span></a>
				<i id="bt_benchmarkJeedom" class="fas fa-tachometer-alt pull-right cursor" title="Benchmark Jeedom"></i>
			</h3>
		</div>
		<div id="health_jeedom" class="panel-collapse collapse in" aria-expanded="true">
			<div class="panel-body"></div>
		</div>
	</div>

	<table id="jeedomTable" class="table table-condensed table-bordered">
		<thead><tr><th style="width : 250px;"></th><th style="width : 500px;">{{Résultat}}</th><th style="">{{Conseil}}</th></tr></thead>
		<tbody>
			<?php
				foreach (jeedom::health() as $datas) {
					echo '<tr>';
					echo '<td style="font-weight : bold;">';
					echo $datas['name'];
					echo '</td>';
					if ($datas['state'] === 2) {
						echo '<td class="alert alert-warning" style="">';
					} else if ($datas['state']) {
						echo '<td class="alert alert-success" style="">';
					} else {
						echo '<td class="alert alert-danger" style="">';
					}
					echo $datas['result'];
					echo '</td>';
					echo '<td>';
					echo $datas['comment'];
					echo '</td>';
					echo '</tr>';
				}
				echo '</tr>';
			?>

<?php
	$count = 0;
	$globalhtml = '';
	$totalNok = 0;
	$totalPending = 0;
	foreach (plugin::listPlugin(true) as $plugin) {
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
				$daemonInfo = ' <i class="fas fa-university pull-right" style="font-size:0.8em" title="{{Démon en mode automatique}}"></i>';
			} else {
				$daemonInfo = ' <i class="fas fa-university pull-right" style="color:#ff4c4c;font-size:0.8em" title="{{Démon en mode manuel}}"></i>';
			}
		}
		if (config::byKey('port', $plugin->getId()) != '') {
			$port = ' <i class="icon techno-fleches pull-right" style="font-size:0.8em" title="{{Port configuré}}"></i><span style="font-size:0.8em title="{{Port configuré}}" class="pull-right"> ' . ucfirst(config::byKey('port', $plugin->getId())) . '</span>';
		}
		if (file_exists(dirname(plugin::getPathById($plugin_id)) . '/../desktop/modal/health.php')) {
			$hasSpecificHealth = 1;
			$hasSpecificHealthIcon = '  <i data-pluginname="' . $plugin->getName() . '" data-pluginid="' . $plugin->getId() . '" class="fas fa-medkit bt_healthSpecific pull-right cursor" style="font-size:0.8em" title="Santé spécifique"></i>';
		}
		if ($plugin->getHasDependency() == 1 || $plugin->getHasOwnDeamon() == 1 || method_exists($plugin->getId(), 'health') || $hasSpecificHealth == 1) {
			$count += 1;
			$globalhtml .= '<div class="panel panel-default">';
			$globalhtml .= ' <div class="panel-heading">
				<h3 class="panel-title">';
			if ($plugin->getHasDependency() == 1 || $plugin->getHasOwnDeamon() == 1 || method_exists($plugin->getId(), 'health')) {
				$html .= '<table class="table table-condensed table-bordered">';
				$html .= '<thead><tr><th style="width : 250px;"></th><th style="width : 150px;">{{Résultat}}</th><th style="">{{Conseil}}</th></tr></thead>';
				$html .= '<tbody>';
			} else {
				$html .= '<span class="label label-primary" style="">{{Aucune santé spécifique}}</span>';
			}
		}
		try {
			if ($plugin->getHasDependency() == 1) {
				$dependancy_info = $plugin->dependancy_info();
				$html .= '<tr>';
				$html .= '<td style="font-weight : bold;">';
				$html .= '{{Dépendances}}';
				$html .= '</td>';
				switch ($dependancy_info['state']) {
					case 'ok':
						$html .= '<td class="alert alert-success"  style="">{{OK}}</td>';
						break;
					case 'nok':
						$html .= '<td class="alert alert-danger"  style="">{{NOK}}</td>';
						$asNok += 1;
						break;
					case 'in_progress':
						$html .= '<td class="alert alert-info" style="">{{En cours}}</td>';
						$asPending += 1;
						break;
					default:
						$html .= '<td class="alert alert-danger" style="">{{NOK}}</td>';
						$asNok += 1;
						break;
				}
				$html .= '<td>';
				$html .= '</td>';
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
				$html .= '<td style="font-weight : bold;">';
				$html .= '{{Configuration démon}}';
				echo '</td>';
				switch ($deamon_info['launchable']) {
					case 'ok':
						$html .= '<td class="alert alert-success" style="">{{OK}}</td>';
						break;
					case 'nok':
						if ($deamon_info['auto'] != 1) {
							$html .= '<td class="alert alert-success" style="">{{Désactivé}}</td>';
						} else {
							$html .= '<td class="alert alert-danger" title="' . $deamon_info['launchable_message'] . '" style="">{{NOK}}</td>';
							$asNok += 1;
						}
						break;
				}
				$html .= '<td style="">';
				$html .= $deamon_info['launchable_message'];
				$html .= '</td>';
				$html .= '</tr>';
				$html .= '<tr>';
				$html .= '<td style="font-weight : bold;">';
				$html .= '{{Statut démon}}';
				$html .= '</td>';
				switch ($deamon_info['state']) {
					case 'ok':
						$html .= '<td class="alert alert-success" style="">';
						$html .= '{{OK}}</td>';
						break;
					case 'nok':
						if ($deamon_info['auto'] != 1) {
							$html .= '<td class="alert alert-success" style="">{{Désactivé}}</td>';
						} else {
							$html .= '<td class="alert alert-danger" style="">{{NOK}}</td>';
							$asNok += 1;
						}
						break;
				}
				$html .= '<td>';
				$html .= '</td>';
				$html .= '</tr>';
			}
		} catch (Exception $e) {

		}

		try {
			if (method_exists($plugin->getId(), 'health')) {
				foreach ($plugin_id::health() as $result) {
					$html .= '<tr>';
					$html .= '<td style="font-weight : bold;">';
					$html .= $result['test'];
					$html .= '</td>';
					if ($result['state']) {
						$html .= '<td class="alert alert-success" style="">';
					} else {
						$html .= '<td class="alert alert-danger" style="">';
						$asNok += 1;
					}
					$html .= $result['result'];
					$html .= '</td>';
					$html .= '<td style="">';
					$html .= $result['advice'];
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
			$errorMessage = '';
			$pendingMessage = '';
			if ($asNok != 0) {
				$totalNok += 1;
				$errorMessage = '<span class="label label-danger pull-right" style="position:relative;top:-3px;">' . $asNok . ' {{erreurs}} </span>';
			}
			if ($asPending != 0) {
				$totalPending += 1;
				$pendingMessage = '<span class="label label-warning pull-right" style="position:relative;top:-3px;">' . $asPending . ' {{en cours}} </span>';
			}
			if ($asPending == 0 && $asNok == 0) {
				$errorMessage = '<span class="label label-success pull-right" style="position:relative;top:-3px;">{{OK}}</span>';
			}
			$globalhtml .= '<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionHealth" href="#config_' . $plugin->getId() . '">';
			$globalhtml .= '<img class="img-responsive" style="width : 20px;display:inline-block;" src="' . $plugin->getPathImgIcon() . '" /> ';
			$globalhtml .= '{{Santé }} ' . $plugin->getName() . '</a> ';
			$globalhtml .= $errorMessage . $pendingMessage;
			$globalhtml .= '<i class="fas fa-cogs bt_configurationPlugin cursor pull-right" title="{{Configuration du plugin}}" style=font-size:0.8em" data-pluginid="' . $plugin->getId() . '"></i> ' . $hasSpecificHealthIcon . $daemonInfo . $port;
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

	if ($globalhtml != '') {
		echo '<tr>
		<td style="font-weight : bold;">{{Plugins}}</td>';
		if ($totalNok == 0 && $totalPending == 0) {
			echo '<td class="alert alert-success" style="">{{OK}}</td>';
			echo '<td></td>';
		} else if ($totalNok == 0 && $totalPending != 0) {
			echo '<td class="alert alert-warning" style="">' . $totalPending . ' {{En cours}}</td>';
			echo '<td style="">Vous pouvez voir les détails des plugins sur la partie basse de cette page</td>';
		} else if ($totalNok != 0) {
			$pending = '';
			if ($totalPending != 0) {
				$pending = ' {{et}} ' . $totalPending . ' {{En cours}}';
			}
			echo '<td class="alert alert-danger" style="">' . $totalNok . ' {{NOK}}' . $pending . '</td>';
			echo '<td style="">Vous pouvez voir les détails des plugins sur la partie basse de cette page</td>';
		}
		echo '</tr>';
		echo '</tbody></table>';
		echo $globalhtml;
		echo '</div>';
	} else {
		echo '</tbody></table>';
	}

	?>

<?php include_file("desktop", "health", "js");?>
