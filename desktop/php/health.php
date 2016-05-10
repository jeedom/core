<?php
if (!hasRight('health', true)) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<legend><i class="icon divers-caduceus3"></i>  {{Santé de Jeedom}}</legend>
<table class="table table-condensed table-bordered">
	<thead><tr><th style="width : 250px;"></th><th style="width : 200px;">{{Résultat}}</th><th>{{Conseil}}</th></tr></thead>
	<tbody>
		<tr>
			<td style="font-weight : bold;">{{Système à jour}}</td>
			<?php
$nbNeedUpdate = update::nbNeedUpdate();
if ($nbNeedUpdate > 0) {
	echo '<td class="alert alert-danger">' . $nbNeedUpdate . '</td>';
	echo '<td></td>';
} else {
	echo '<td class="alert alert-success">{{OK}}</td>';
	echo '<td></td>';
}
?>
		</tr>

		<tr>
			<td style="font-weight : bold;">{{Lancement des crons}}</td>
			<?php
if (!cron::ok()) {
	echo '<td class="alert alert-danger">{{NOK}}</td>';
	echo '<td></td>';
} else {
	echo '<td class="alert alert-success">{{OK}}</td>';
	echo '<td></td>';
}
?>
		</tr>

		<tr>
			<td style="font-weight : bold;">{{Cron actif}}</td>
			<?php
if (config::byKey('enableCron', 'core', 1, true) == 0) {
	echo '<td class="alert alert-danger">{{NOK}}</td>';
	echo '<td>{{Erreur cron : les crons sont désactivés. Allez dans Administration -> Moteur de tâches pour les réactiver}}</td>';
} else {
	echo '<td class="alert alert-success">{{OK}}</td>';
	echo '<td></td>';
}
?>
		</tr>

		<tr>
			<td style="font-weight : bold;">{{Scénario actif}}</td>
			<?php
if (config::byKey('enableScenario') == 0 && count(scenario::all()) > 0) {
	echo '<td class="alert alert-danger">{{NOK}}</td>';
	echo '<td>{{Erreur scénario : tous les scénarios sont désactivés. Allez dans Outils -> Scénarios pour les réactiver}}</td>';
} else {
	echo '<td class="alert alert-success">{{OK}}</td>';
	echo '<td></td>';
}
?>
		</tr>

		<tr>
			<td style="font-weight : bold;">{{Démarré}}</td>
			<?php
if (!jeedom::isStarted()) {
	echo '<td class="alert alert-danger">{{NOK}}</td>';
	echo '<td></td>';
} else {
	echo '<td class="alert alert-success">{{OK}}</td>';
	echo '<td></td>';
}
?>
		</tr>

		<tr>
			<td style="font-weight : bold;">{{Date système}}</td>
			<?php
if (!jeedom::isDateOk()) {
	echo '<td class="alert alert-danger">' . date('Y-m-d H:i:s') . '</td>';
	echo '<td></td>';
} else {
	echo '<td class="alert alert-success">{{OK}}</td>';
	echo '<td></td>';
}
?>
		</tr>

		<tr>
			<td style="font-weight : bold;">{{Authentification par défaut}}</td>
			<?php
if (user::hasDefaultIdentification() == 1) {
	echo '<td class="alert alert-danger">{{NOK}}</td>';
	echo '<td>{{Attention vous avez toujours l\'utilisateur admin/admin de configuré, cela représente une grave faille de sécurité, aller <a href=\'index.php?v=d&p=user\'>ici</a> pour modifier le mot de passe de l\'utilisateur admin}}</td>';

} else {
	echo '<td class="alert alert-success">{{OK}}</td>';
	echo '<td></td>';
}
?>
		</tr>

		<tr>
			<td style="font-weight : bold;">{{Droits sudo}}</td>
			<?php
if (jeedom::isCapable('sudo')) {
	echo '<td class="alert alert-success">{{OK}}</td>';
	echo '<td></td>';
} else {
	echo '<td class="alert alert-danger">{{NOK}}</td>';
	echo '<td>Appliquer <a href="https://www.jeedom.com/doc/documentation/installation/fr_FR/doc-installation.html#_etape_4_définition_des_droits_root_à_jeedom" targe="_blank">cette étape</a> de l\'installation</td>';
}
?>
		</tr>

		<tr>
			<td style="font-weight : bold;">{{Version Jeedom}}</td>
			<?php
echo '<td class="alert alert-success">' . jeedom::version() . '</td>';
echo '<td></td>';
?>
		</tr>

		<tr>
			<td style="font-weight : bold;">{{Version PHP}}</td>
			<?php
if (version_compare(phpversion(), '5.5', '>=')) {
	echo '<td class="alert alert-success">' . phpversion() . '</td>';
	echo '<td></td>';
} else {
	echo '<td class="alert alert-warning">' . phpversion() . '</td>';
	echo '<td>{{Si vous êtes en version 5.4.x on vous indiquera quand la version 5.5 sera obligatoire}}</td>';
}
?>
		</tr>
		<tr>
			<td style="font-weight : bold;">{{Espace disque libre}}</td>
			<?php
$value = jeedom::checkSpaceLeft();
if ($value > 10) {
	echo '<td class="alert alert-success">' . $value . ' %</td>';
	echo '<td></td>';
} else {
	echo '<td class="alert alert-danger">' . $value . ' %</td>';
	echo '<td></td>';
}
?>
		</tr>

		<tr>
			<td style="font-weight : bold;">{{Configuration réseau interne}}</td>
			<?php
if (network::test('internal')) {
	echo '<td class="alert alert-success">{{OK}}</td>';
	echo '<td></td>';
} else {
	echo '<td class="alert alert-danger">{{NOK}}</td>';
	echo '<td>{{Allez sur Administration -> Configuration puis configurez correctement la partie réseau}}</td>';
}
?>
		</tr>

		<tr>
			<td style="font-weight : bold;">{{Configuration réseau externe}}</td>
			<?php
if (network::test('external')) {
	echo '<td class="alert alert-success">{{OK}}</td>';
	echo '<td></td>';
} else {
	echo '<td class="alert alert-danger">{{NOK}}</td>';
	echo '<td>{{Allez sur Administration -> Configuration puis configurez correctement la partie réseau}}</td>';
}
?>
		</tr>
	<tr>
			<td style="font-weight : bold;">{{Persistance du cache}}</td>
			<?php
if (cache::isPersistOk()) {
	if (config::byKey('cache::engine') != 'FilesystemCache' && config::byKey('cache::engine') != 'PhpFileCache') {
		echo '<td class="alert alert-success">{{OK}}</td>';
	} else {
		$filename = dirname(__FILE__) . '/../../cache.tar.gz';
		echo '<td class="alert alert-success">{{OK}} (' . date('Y-m-d H:i:s', filemtime($filename)) . ')</td>';
	}
	echo '<td></td>';
} else {
	echo '<td class="alert alert-danger">{{NOK}}</td>';
	echo '<td>{{Votre cache n\'est pas sauvegardé en cas de redemarrage certaines information peuvent être perdues. Essayez de lancer (à partir du moteur de têche) la tâche cache::persist}}</td>';
}
?>
		</tr>



<?php
if (config::byKey('jeeNetwork::mode') == 'master') {
	foreach (jeeNetwork::all() as $jeeNetwork) {
		echo '<tr>';
		echo '<td style="font-weight : bold;">{{Version esclave}} ' . $jeeNetwork->getName() . '</td>';
		if (trim($jeeNetwork->getConfiguration('version')) == trim(jeedom::version())) {
			echo '<td class="alert alert-success">' . $jeeNetwork->getConfiguration('version') . ' </td>';
		} else {
			echo '<td class="alert alert-danger">' . $jeeNetwork->getConfiguration('version') . ' </td>';
		}
		echo '<td></td>';
		echo '</tr>';
	}
}
?>
	</tbody>
</table>

<?php
foreach (plugin::listPlugin(true) as $plugin) {
	$plugin_id = $plugin->getId();
	$hasSpecificHealth = 0;
	$hasSpecificHealthIcon = '';
	if (file_exists(dirname(plugin::getPathById($plugin_id)) . '/../desktop/modal/health.php')) {
		$hasSpecificHealth = 1;
		$hasSpecificHealthIcon = '  <i data-pluginname="' . $plugin->getName() . '" data-pluginid="' . $plugin->getId() . '" class="fa fa-medkit bt_healthSpecific tooltips" style="cursor:pointer;color:grey;font-size:0.8em" title="Santé spécifique"></i>';
	}
	if ($plugin->getHasDependency() == 1 || $plugin->getHasOwnDeamon() == 1 || method_exists($plugin->getId(), 'health') || $hasSpecificHealth == 1) {
		echo '<legend>';
		if (file_exists(dirname(__FILE__) . '/../../' . $plugin->getPathImgIcon())) {
			echo '<img class="img-responsive" style="width : 20px;display:inline-block;" src="' . $plugin->getPathImgIcon() . '" /> ';
		} else {
			echo '<i class="' . $plugin->getIcon() . '"></i> ';
		}
		echo '{{Santé }} <a class="bt_configurationPlugin cursor" data-pluginid="' . $plugin->getId() . '">' . $plugin->getName() . '</a>' . $hasSpecificHealthIcon . '</legend>';
		if ($plugin->getHasDependency() == 1 || $plugin->getHasOwnDeamon() == 1 || method_exists($plugin->getId(), 'health')) {
			echo '<table class="table table-condensed table-bordered">';
			echo '<thead><tr><th style="width : 250px;"></th><th style="width : 150px;">{{Résultat}}</th><th>{{Conseil}}</th></tr></thead>';
			echo '<tbody>';
		}
	}
	try {
		if ($plugin->getHasDependency() == 1) {
			$dependancy_info = $plugin_id::dependancy_info();
			echo '<tr>';
			echo '<td style="font-weight : bold;">';
			echo '{{Dépendance}}';
			echo '</td>';
			switch ($dependancy_info['state']) {
				case 'ok':
					echo '<td class="alert alert-success">{{OK}}</td>';
					break;
				case 'nok':
					echo '<td class="alert alert-danger">{{NOK}}</td>';
					break;
				case 'in_progress':
					echo '<td class="alert alert-info">{{En cours}}</td>';
					break;
				default:
					echo '<td class="alert alert-danger">{{NOK}}</td>';
					break;
			}
			echo '<td>';
			echo '</td>';
			echo '</tr>';
			if (config::byKey('jeeNetwork::mode') == 'master') {
				foreach (jeeNetwork::byPlugin($plugin_id) as $jeeNetwork) {
					$dependancyInfo = $jeeNetwork->sendRawRequest('plugin::dependancyInfo', array('plugin_id' => $plugin_id));
					echo '<tr>';
					echo '<td style="font-weight : bold;">';
					echo '{{Dépendance}} ' . $jeeNetwork->getName();
					echo '</td>';
					switch ($dependancy_info['state']) {
						case 'ok':
							echo '<td class="alert alert-success">{{OK}}</td>';
							break;
						case 'nok':
							echo '<td class="alert alert-danger">{{NOK}}</td>';
							break;
						case 'in_progress':
							echo '<td class="alert alert-info">{{En cours}}</td>';
							break;
						default:
							echo '<td class="alert alert-danger">{{NOK}}</td>';
							break;
					}
					echo '<td>';
					echo '</td>';
					echo '</tr>';
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
			echo '<tr>';
			echo '<td style="font-weight : bold;">';
			echo '{{Configuration démon}}';
			echo '</td>';
			switch ($deamon_info['launchable']) {
				case 'ok':
					echo '<td class="alert alert-success">{{OK}}</td>';
					break;
				case 'nok':
					if ($deamon_info['auto'] != 1) {
						echo '<td class="alert alert-success">{{Désactivé}}</td>';
					} else {
						echo '<td class="alert alert-danger" title="' . $deamon_info['launchable_message'] . '">{{NOK}}</td>';
					}
					break;
			}
			echo '<td>';
			echo $deamon_info['launchable_message'];
			echo '</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td style="font-weight : bold;">';
			echo '{{Statut démon}}';
			echo '</td>';
			switch ($deamon_info['state']) {
				case 'ok':
					echo '<td class="alert alert-success">';
					if ($deamon_info['debug_mode']) {
						echo '<i class="fa fa-bug"></i> ';
					}
					echo '{{OK}}</td>';
					break;
				case 'nok':
					if ($deamon_info['auto'] != 1) {
						echo '<td class="alert alert-success">{{Désactivé}}</td>';
					} else {
						echo '<td class="alert alert-danger">{{NOK}}</td>';
					}
					break;
			}
			echo '<td>';
			echo '</td>';
			echo '</tr>';
			if (config::byKey('jeeNetwork::mode') == 'master') {
				foreach (jeeNetwork::byPlugin($plugin_id) as $jeeNetwork) {
					$deamon_info = $jeeNetwork->sendRawRequest('plugin::deamonInfo', array('plugin_id' => $plugin_id));
					echo '<tr>';
					echo '<td style="font-weight : bold;">';
					echo '{{Configuration démon}} ' . $jeeNetwork->getName();
					echo '</td>';
					switch ($deamon_info['launchable']) {
						case 'ok':
							echo '<td class="alert alert-success">{{OK}}</td>';
							break;
						case 'nok':
							if ($deamon_info['auto'] != 1) {
								echo '<td class="alert alert-success">{{Désactivé}}</td>';
							} else {
								echo '<td class="alert alert-danger" title="' . $deamon_info['launchable_message'] . '">{{NOK}}</td>';
							}
							break;
					}
					echo '<td>';
					echo '</td>';
					echo '</tr>';
					echo '<tr>';
					echo '<td style="font-weight : bold;">';
					echo '{{Statut démon}} ' . $jeeNetwork->getName();
					echo '</td>';
					switch ($deamon_info['state']) {
						case 'ok':
							echo '<td class="alert alert-success">';
							if ($deamon_info['debug_mode']) {
								echo '<i class="fa fa-bug"></i> ';
							}
							echo '{{OK}}</td>';
							break;
						case 'nok':
							if ($deamon_info['auto'] != 1) {
								echo '<td class="alert alert-success">{{Désactivé}}</td>';
							} else {
								echo '<td class="alert alert-danger">{{NOK}}</td>';
							}
							break;
					}
					echo '<td>';
					echo '</td>';
					echo '</tr>';
				}
			}
		}
	} catch (Exception $e) {

	}

	try {
		if (method_exists($plugin->getId(), 'health')) {

			foreach ($plugin_id::health() as $result) {
				echo '<tr>';
				echo '<td style="font-weight : bold;">';
				echo $result['test'];
				echo '</td>';
				if ($result['state']) {
					echo '<td class="alert alert-success">';
				} else {
					echo '<td class="alert alert-danger">';
				}
				echo $result['result'];
				echo '</td>';
				echo '<td>';
				echo $result['advice'];
				echo '</td>';
				echo '</tr>';
			}

		}
	} catch (Exception $e) {

	}
	if ($plugin->getHasDependency() == 1 || $plugin->getHasOwnDeamon() == 1 || method_exists($plugin->getId(), 'health')) {
		echo '</tbody>';
		echo '</table>';
	}
}
?>

<?php include_file("desktop", "health", "js");?>
