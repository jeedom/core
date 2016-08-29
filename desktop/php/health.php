<?php
if (!hasRight('health', true)) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$starttime = getmicrotime();
?>
</style>
<legend style="cursor:default"><i class="icon divers-caduceus3" style="cursor:default"></i> {{Santé de Jeedom}}
		<i class="fa fa-dashboard pull-right cursor" id="bt_benchmarkJeedom"></i>
</legend>
<table class="table table-condensed table-bordered">
	<thead><tr><th style="width : 250px;cursor:default"></th><th style="width : 200px;cursor:default">{{Résultat}}</th><th style="cursor:default">{{Conseil}}</th></tr></thead>
	<tbody>
		<tr>
			<td style="font-weight : bold;cursor:default">{{Système à jour}}</td>
			<?php
$nbNeedUpdate = update::nbNeedUpdate();
if ($nbNeedUpdate > 0) {
	echo '<td class="alert alert-danger" style="cursor:default">' . $nbNeedUpdate . '</td>';
	echo '<td></td>';
} else {
	echo '<td class="alert alert-success" style="cursor:default">{{OK}}</td>';
	echo '<td></td>';
}
?>
		</tr>
		<tr>
			<td style="font-weight : bold;cursor:default">{{Cron actif}}</td>
			<?php
if (config::byKey('enableCron', 'core', 1, true) == 0) {
	echo '<td class="alert alert-danger" style="cursor:default">{{NOK}}</td>';
	echo '<td style="cursor:default">{{Erreur cron : les crons sont désactivés. Allez dans Administration -> Moteur de tâches pour les réactiver}}</td>';
} else {
	echo '<td class="alert alert-success" style="cursor:default">{{OK}}</td>';
	echo '<td></td>';
}
?>
		</tr>
		<tr>
			<td style="font-weight : bold;cursor:default">{{Scénario actif}}</td>
			<?php
if (config::byKey('enableScenario') == 0 && count(scenario::all()) > 0) {
	echo '<td class="alert alert-danger" style="cursor:default">{{NOK}}</td>';
	echo '<td style="cursor:default">{{Erreur scénario : tous les scénarios sont désactivés. Allez dans Outils -> Scénarios pour les réactiver}}</td>';
} else {
	echo '<td class="alert alert-success" style="cursor:default">{{OK}}</td>';
	echo '<td></td>';
}
?>
		</tr>
		<tr>
			<td style="font-weight : bold;cursor:default">{{Démarré}}</td>
			<?php
if (!jeedom::isStarted()) {
	echo '<td class="alert alert-danger" style="cursor:default">{{NOK}}</td>';
	echo '<td></td>';
} else {
	echo '<td class="alert alert-success">{{OK}}</td>';
	echo '<td></td>';
}
?>
		</tr>
		<tr>
			<td style="font-weight : bold;cursor:default">{{Date système}}</td>
			<?php
if (!jeedom::isDateOk()) {
	echo '<td class="alert alert-danger style="cursor:default"">' . date('Y-m-d H:i:s') . '</td>';
	echo '<td></td>';
} else {
	echo '<td class="alert alert-success" style="cursor:default">{{OK}}</td>';
	echo '<td></td>';
}
?>
		</tr>
		<tr>
			<td style="font-weight : bold;cursor:default">{{Authentification par défaut}}</td>
			<?php
if (user::hasDefaultIdentification() == 1) {
	echo '<td class="alert alert-danger" style="cursor:default">{{NOK}}</td>';
	echo '<td style="cursor:default">{{Attention vous avez toujours l\'utilisateur admin/admin de configuré, cela représente une grave faille de sécurité, aller <a href=\'index.php?v=d&p=user\'>ici</a> pour modifier le mot de passe de l\'utilisateur admin}}</td>';

} else {
	echo '<td class="alert alert-success" style="cursor:default">{{OK}}</td>';
	echo '<td></td>';
}
?>
		</tr>
		<tr>
			<td style="font-weight : bold;cursor:default">{{Droits sudo}}</td>
			<?php
if (jeedom::isCapable('sudo')) {
	echo '<td class="alert alert-success" style="cursor:default">{{OK}}</td>';
	echo '<td></td>';
} else {
	echo '<td class="alert alert-danger" style="cursor:default">{{NOK}}</td>';
	echo '<td style="cursor:default">Appliquer <a href="https://www.jeedom.com/doc/documentation/installation/fr_FR/doc-installation.html#_etape_4_définition_des_droits_root_à_jeedom" targe="_blank">cette étape</a> de l\'installation</td>';
}
?>
		</tr>
		<tr>
			<td style="font-weight : bold;cursor:default">{{Version Jeedom}}</td>
			<?php
echo '<td class="alert alert-success" style="cursor:default">' . jeedom::version() . '</td>';
echo '<td></td>';
?>
		</tr>
		<tr>
			<td style="font-weight : bold;cursor:default">{{Version PHP}}</td>
			<?php
if (version_compare(phpversion(), '5.5', '>=')) {
	echo '<td class="alert alert-success" style="cursor:default">' . phpversion() . '</td>';
	echo '<td></td>';
} else {
	echo '<td class="alert alert-warning" style="cursor:default">' . phpversion() . '</td>';
	echo '<td style="cursor:default">{{Si vous êtes en version 5.4.x on vous indiquera quand la version 5.5 sera obligatoire}}</td>';
}
?>
		</tr>
		<tr>
			<td style="font-weight : bold;cursor:default">{{Version OS}}</td>
			<?php
$uname = shell_exec('uname -a');
$version = '';
$version_ok = true;
if (strpos(strtolower($uname), 'debian') === false) {
	$version_ok = false;
}
if (!file_exists('/etc/debian_version')) {
	$version_ok = false;
} else {
	$version_ok = true;
	$version = trim(strtolower(file_get_contents('/etc/debian_version')));
	if (version_compare($version, '8', '<')) {
		if (strpos($version, 'jessie') === false && strpos($version, 'stretch')) {
			$version_ok = false;
		}
	}
}
if (strpos(strtolower($uname), 'ubuntu') !== false) {
	$version_ok = false;
}
if ($version_ok) {
	echo '<td class="alert alert-success" style="cursor:default">' . $uname . ' [' . $version . ']</td>';
	echo '<td></td>';
} else {
	echo '<td class="alert alert-danger" style="cursor:default">' . $uname . '</td>';
	echo '<td style="cursor:default">{{Vous n\'êtes pas sur un OS officiellement supporté par l\'équipe Jeedom (toute demande de support pourra donc être refusée). Les OS officiellement supporté sont Debian Jessie et Debian Strech (voir <a href="https://www.jeedom.com/doc/documentation/compatibility/fr_FR/doc-compatibility.html#_logiciel" target="_blank">ici</a>)}}</td>';
}
?>
		</tr>
		<tr>
			<td style="font-weight : bold;cursor:default">{{Version database}}</td>
			<?php
$version = DB::Prepare('select version()', array(), DB::FETCH_TYPE_ROW);
echo '<td class="alert alert-success" style="cursor:default">' . $version['version()'] . '</td>';
echo '<td></td>';

?>
		</tr>
		<tr>
			<td style="font-weight : bold;cursor:default">{{Espace disque libre}}</td>
			<?php
$value = jeedom::checkSpaceLeft();
if ($value > 10) {
	echo '<td class="alert alert-success" style="cursor:default">' . $value . ' %</td>';
	echo '<td></td>';
} else {
	echo '<td class="alert alert-danger" style="cursor:default">' . $value . ' %</td>';
	echo '<td></td>';
}
?>
		</tr>
		<tr>
			<td style="font-weight : bold;cursor:default">{{Configuration réseau interne}}</td>
			<?php
if (network::test('internal')) {
	echo '<td class="alert alert-success" style="cursor:default">{{OK}}</td>';
	echo '<td></td>';
} else {
	echo '<td class="alert alert-danger" style="cursor:default">{{NOK}}</td>';
	echo '<td style="cursor:default">{{Allez sur Administration -> Configuration puis configurez correctement la partie réseau}}</td>';
}
?>
		</tr>
		<tr>
			<td style="font-weight : bold;cursor:default">{{Configuration réseau externe}}</td>
			<?php
if (network::test('external')) {
	echo '<td class="alert alert-success" style="cursor:default">{{OK}}</td>';
	echo '<td></td>';
} else {
	echo '<td class="alert alert-danger" style="cursor:default">{{NOK}}</td>';
	echo '<td style="cursor:default">{{Allez sur Administration -> Configuration puis configurez correctement la partie réseau}}</td>';
}
?>
		</tr>
	<tr>
			<td style="font-weight : bold;cursor:default">{{Persistance du cache}}</td>
			<?php
if (cache::isPersistOk()) {
	if (config::byKey('cache::engine') != 'FilesystemCache' && config::byKey('cache::engine') != 'PhpFileCache') {
		echo '<td class="alert alert-success" style="cursor:default">{{OK}}</td>';
	} else {
		$filename = dirname(__FILE__) . '/../../cache.tar.gz';
		echo '<td class="alert alert-success" style="cursor:default">{{OK}} (' . date('Y-m-d H:i:s', filemtime($filename)) . ')</td>';
	}
	echo '<td></td>';
} else {
	echo '<td class="alert alert-danger" style="cursor:default">{{NOK}}</td>';
	echo '<td style="cursor:default">{{Votre cache n\'est pas sauvegardé en cas de redemarrage certaines information peuvent être perdues. Essayez de lancer (à partir du moteur de têche) la tâche cache::persist}}</td>';
}
?>
		</tr>
<?php
if (config::byKey('jeeNetwork::mode') == 'master') {
	foreach (jeeNetwork::all() as $jeeNetwork) {
		echo '<tr>';
		echo '<td style="font-weight : bold;cursor:default">{{Version esclave}} ' . $jeeNetwork->getName() . '</td>';
		if (trim($jeeNetwork->getConfiguration('version')) == trim(jeedom::version())) {
			echo '<td class="alert alert-success" style="cursor:default">' . $jeeNetwork->getConfiguration('version') . ' </td>';
		} else {
			echo '<td class="alert alert-danger" style="cursor:default">' . $jeeNetwork->getConfiguration('version') . ' </td>';
		}
		echo '<td></td>';
		echo '</tr>';
	}
}
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
	$asNok = 0;
	$asPending = 0;
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
			if (config::byKey('jeeNetwork::mode') == 'master') {
				foreach (jeeNetwork::byPlugin($plugin_id) as $jeeNetwork) {
					$dependancyInfo = $jeeNetwork->sendRawRequest('plugin::dependancyInfo', array('plugin_id' => $plugin_id));
					$html .= '<tr>';
					$html .= '<td style="font-weight : bold;cursor:default">';
					$html .= '{{Dépendance}} ' . $jeeNetwork->getName();
					$html .= '</td>';
					switch ($dependancy_info['state']) {
						case 'ok':
							$html .= '<td class="alert alert-success" style="cursor:default">{{OK}}</td>';
							break;
						case 'nok':
							$html .= '<td class="alert alert-danger" style="cursor:default" >{{NOK}}</td>';
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
			}
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
			if (config::byKey('jeeNetwork::mode') == 'master') {
				foreach (jeeNetwork::byPlugin($plugin_id) as $jeeNetwork) {
					$deamon_info = $jeeNetwork->sendRawRequest('plugin::deamonInfo', array('plugin_id' => $plugin_id));
					$html .= '<tr>';
					$html .= '<td style="font-weight : bold;cursor:default">';
					$html .= '{{Configuration démon}} ' . $jeeNetwork->getName();
					$html .= '</td>';
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
					$html .= '<td>';
					$html .= '</td>';
					$html .= '</tr>';
					$html .= '<tr>';
					$html .= '<td style="font-weight : bold;cursor:default">';
					$html .= '{{Statut démon}} ' . $jeeNetwork->getName();
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
			}
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

		$title .= '<a class="bt_configurationPlugin cursor" data-pluginid="' . $plugin->getId() . '">{{Santé }} ' . $plugin->getName() . '</a>' . $hasSpecificHealthIcon . $errorMessage . $pendingMessage;
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
