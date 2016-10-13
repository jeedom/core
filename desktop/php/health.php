<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$starttime = getmicrotime();
?>
</style>
<legend style="cursor:default"><i class="icon divers-caduceus3" style="cursor:default"></i> {{Santé de Jeedom}}
		<i class="fa fa-dashboard pull-right cursor" id="bt_benchmarkJeedom"></i>
</legend>
<table class="table table-condensed table-bordered">
	<thead><tr><th style="width : 250px;cursor:default"></th><th style="width : 350px;cursor:default">{{Résultat}}</th><th style="cursor:default">{{Conseil}}</th></tr></thead>
	<tbody>
	<?php
foreach (jeedom::health() as $datas) {
	echo '<tr>';
	echo '<td style="font-weight : bold;cursor:default">';
	echo $datas['name'];
	echo '</td>';
	if ($datas['state']) {
		echo '<td class="alert alert-success" style="cursor:default">';
	} else {
		echo '<td class="alert alert-danger" style="cursor:default">';
	}
	echo $datas['result'];
	echo '</td>';
	echo '<td>';
	echo $datas['comment'];
	echo '</td>';
	echo '</tr>';
}
?>
		</tr>
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
			$daemonInfo = ' <i class="fa fa-university" style="cursor:default;color:grey;font-size:0.8em" title="Démon en mode automatique"></i>';
		} else {
			$daemonInfo = ' <i class="fa fa-university" style="cursor:default;color:#ff4c4c;font-size:0.8em" title="Démon en mode manuel"></i>';
		}
	}
	if (config::byKey('port', $plugin->getId()) != '') {
		$port = ' <i class="icon techno-fleches" style="cursor:default;color:grey;font-size:0.8em" title="Port configuré"></i><span style="cursor:default;color:grey;font-size:0.8em title="Port configuré"> ' . ucfirst(config::byKey('port', $plugin->getId())) . '</span>';
	}
	if (file_exists(dirname(plugin::getPathById($plugin_id)) . '/../desktop/modal/health.php')) {
		$hasSpecificHealth = 1;
		$hasSpecificHealthIcon = '  <i data-pluginname="' . $plugin->getName() . '" data-pluginid="' . $plugin->getId() . '" class="fa fa-medkit bt_healthSpecific" style="cursor:pointer;color:grey;font-size:0.8em" title="Santé spécifique"></i>';
	}
	if ($plugin->getHasDependency() == 1 || $plugin->getHasOwnDeamon() == 1 || method_exists($plugin->getId(), 'health') || $hasSpecificHealth == 1) {
		if ($count == 0) {
			$globalhtml .= '<div class="panel-group" id="accordionHealth">';
		}
		$count += 1;
		$globalhtml .= '<div class="panel panel-default">';
		$globalhtml .= ' <div class="panel-heading">
                <h3 class="panel-title">';
		if ($plugin->getHasDependency() == 1 || $plugin->getHasOwnDeamon() == 1 || method_exists($plugin->getId(), 'health')) {
			$html .= '<table class="table table-condensed table-bordered">';
			$html .= '<thead><tr><th style="width : 250px;cursor:default"></th><th style="width : 150px;cursor:default">{{Résultat}}</th><th style="cursor:default">{{Conseil}}</th></tr></thead>';
			$html .= '<tbody>';
		} else {
			$html .= '<span class="label label-primary" style="cursor:default"> {{Aucune santé spécifique}} </span>';
		}
	}
	try {
		if ($plugin->getHasDependency() == 1) {
			$dependancy_info = $plugin->dependancy_info();
			$html .= '<tr>';
			$html .= '<td style="font-weight : bold;cursor:default">';
			$html .= '{{Dépendance}}';
			$html .= '</td>';
			switch ($dependancy_info['state']) {
				case 'ok':
					$html .= '<td class="alert alert-success"  style="cursor:default">{{OK}}</td>';
					break;
				case 'nok':
					$html .= '<td class="alert alert-danger"  style="cursor:default">{{NOK}}</td>';
					$asNok += 1;
					break;
				case 'in_progress':
					$html .= '<td class="alert alert-info" style="cursor:default">{{En cours}}</td>';
					$asPending += 1;
					break;
				default:
					$html .= '<td class="alert alert-danger" style="cursor:default">{{NOK}}</td>';
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
			$html .= '<td style="font-weight : bold;cursor:default">';
			$html .= '{{Configuration démon}}';
			echo '</td>';
			switch ($deamon_info['launchable']) {
				case 'ok':
					$html .= '<td class="alert alert-success" style="cursor:default">{{OK}}</td>';
					break;
				case 'nok':
					if ($deamon_info['auto'] != 1) {
						$html .= '<td class="alert alert-success" style="cursor:default">{{Désactivé}}</td>';
					} else {
						$html .= '<td class="alert alert-danger" title="' . $deamon_info['launchable_message'] . '" style="cursor:default">{{NOK}}</td>';
						$asNok += 1;
					}
					break;
			}
			$html .= '<td style="cursor:default">';
			$html .= $deamon_info['launchable_message'];
			$html .= '</td>';
			$html .= '</tr>';
			$html .= '<tr>';
			$html .= '<td style="font-weight : bold;cursor:default">';
			$html .= '{{Statut démon}}';
			$html .= '</td>';
			switch ($deamon_info['state']) {
				case 'ok':
					$html .= '<td class="alert alert-success" style="cursor:default">';
					$html .= '{{OK}}</td>';
					break;
				case 'nok':
					if ($deamon_info['auto'] != 1) {
						$html .= '<td class="alert alert-success" style="cursor:default">{{Désactivé}}</td>';
					} else {
						$html .= '<td class="alert alert-danger" style="cursor:default">{{NOK}}</td>';
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
				$html .= '<td style="font-weight : bold;cursor:default">';
				$html .= $result['test'];
				$html .= '</td>';
				if ($result['state']) {
					$html .= '<td class="alert alert-success" style="cursor:default">';
				} else {
					$html .= '<td class="alert alert-danger" style="cursor:default">';
					$asNok += 1;
				}
				$html .= $result['result'];
				$html .= '</td>';
				$html .= '<td style="cursor:default">';
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
		$title = '<img class="img-responsive" style="width : 20px;display:inline-block;" src="' . $plugin->getPathImgIcon() . '" /> ';
		if ($asNok != 0) {
			$totalNok += 1;
			$errorMessage = '   <span class="label label-danger pull-right" style="cursor:default">' . $asNok . ' erreurs </span>';
		}
		if ($asPending != 0) {
			$totalPending += 1;
			$pendingMessage = '   <span class="label label-warning pull-right" style="cursor:default">' . $asPending . ' en cours </span>';
		}
		if ($asPending == 0 && $asNok == 0) {
			$errorMessage = '   <span class="label label-success pull-right" style="cursor:default">{{OK}}</span>';
		}

		$title .= '<a class="bt_configurationPlugin cursor" data-pluginid="' . $plugin->getId() . '">{{Santé }} ' . $plugin->getName() . '</a>' . $hasSpecificHealthIcon . $daemonInfo . $port . $errorMessage . $pendingMessage;
		$globalhtml .= '<a class="accordion-toggle pull-right" data-toggle="collapse" data-parent="#accordionHealth" href="#config_' . $plugin->getId() . '" style="text-decoration:none;"><i class="fa fa-arrows-v"></i>
                    </a>' . $title . '
                </h3>
            </div>';
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
			<td style="font-weight : bold;cursor:default">{{Plugins}}</td>';
	if ($totalNok == 0 && $totalPending == 0) {
		echo '<td class="alert alert-success" style="cursor:default">{{OK}}</td>';
		echo '<td></td>';
	} else if ($totalNok == 0 && $totalPending != 0) {
		echo '<td class="alert alert-warning" style="cursor:default">' . $totalPending . ' {{En cours}}</td>';
		echo '<td style="cursor:default">Vous pouvez voir les détails des plugins sur la partie basse de cette page</td>';
	} else if ($totalNok != 0) {
		$pending = '';
		if ($totalPending != 0) {
			$pending = ' {{et}} ' . $totalPending . ' {{En cours}}';
		}
		echo '<td class="alert alert-danger" style="cursor:default">' . $totalNok . ' {{NOK}}' . $pending . '</td>';
		echo '<td style="cursor:default">Vous pouvez voir les détails des plugins sur la partie basse de cette page</td>';
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
