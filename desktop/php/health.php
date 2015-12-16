<?php
if (!hasRight('health', true)) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<legend>{{Santé de Jeedom}}</legend>
<table class="table table-condensed table-bordered">
	<thead><tr><th style="width : 250px;"></th><th style="width : 150px;">{{Résultat}}</th><th>{{Conseil}}</th></tr></thead>
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
	echo '<td>{{Erreur cron : les crons sont désactivés. Allez dans Général -> Administration -> Moteur de tâches pour les réactiver}}</td>';
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
	echo '<td>{{Erreur scénario : tous les scénarios sont désactivés. Allez dans Général -> Scénarios pour les réactiver}}</td>';
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
	echo '<td>Appliquer <a href="https://www.jeedom.fr/doc/documentation/installation/fr_FR/doc-installation.html#_etape_4_définition_des_droits_root_à_jeedom" targe="_blank">cette étape</a> de l\'installation</td>';
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
			<td style="font-weight : bold;">{{Version NodeJS}}</td>
			<?php
$version = str_replace('v', '', shell_exec('nodejs -v'));
if (version_compare($version, '0.10', '>=')) {
	echo '<td class="alert alert-success">' . $version . '</td>';
	echo '<td></td>';
} else {
	echo '<td class="alert alert-danger">' . $version . '</td>';
	echo '<td></td>';
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
			<td style="font-weight : bold;">{{Configuration réseaux interne}}</td>
			<?php
if (network::test('internal')) {
	echo '<td class="alert alert-success">{{OK}}</td>';
	echo '<td></td>';
} else {
	echo '<td class="alert alert-danger">{{NOK}}</td>';
	echo '<td>{{Allez sur Général -> Administration -> Configuration puis configurez correctement la partie réseaux}}</td>';
}
?>
		</tr>

		<tr>
			<td style="font-weight : bold;">{{Configuration réseaux externe}}</td>
			<?php
if (network::test('external')) {
	echo '<td class="alert alert-success">{{OK}}</td>';
	echo '<td></td>';
} else {
	echo '<td class="alert alert-danger">{{NOK}}</td>';
	echo '<td>{{Allez sur Général -> Administration -> Configuration puis configurez correctement la partie réseaux}}</td>';
}
?>
		</tr>
				<tr>
			<td style="font-weight : bold;">{{Commande info en non evènement seulement}}</td>
			<?php
$cmds = cmd::byTypeEventonly('info', 0);
if (count($cmds) == 0) {
	echo '<td class="alert alert-success">' . count($cmds) . ' </td>';
	echo '<td></td>';
} else {
	echo '<td class="alert alert-warning">' . count($cmds) . ' </td>';
	echo '<td>{{Les commandes info qui ne sont pas en évenement seulement ralentissent fortement l\'affichage de jeedom veuillez contacter les développeurs des plugins : }}';
	$plugins = array();
	foreach ($cmds as $cmd) {
		$plugins[$cmd->getEqType()] = $cmd->getEqType();
	}
	echo implode(',', $plugins);
	echo '</td>';
}
?>
		</tr>
<!--
		<tr>
			<td style="font-weight : bold;">{{Configuration nginx}}</td>
			<?php
if (exec('diff /etc/nginx/sites-available/default ' . dirname(__FILE__) . '/../../install/nginx_default | wc -l') == 0 || exec('diff /etc/nginx/sites-available/default ' . dirname(__FILE__) . '/../../install/nginx_default_without_jeedom | wc -l') == 0) {
	echo '<td class="alert alert-success">{{OK}}</td>';
	echo '<td></td>';
} else {
	echo '<td class="alert alert-danger">{{NOK}}</td>';
	echo '<td>{{Votre fichier de configuration nginx, n\'est pas à jour. Si vous l\'avez modifié cela est normal}}</td>';
}
?>
		</tr>
	-->
</tbody>
</table>

<?php
foreach (plugin::listPlugin(true) as $plugin) {
	$plugin_id = $plugin->getId();
	if ($plugin->getHasDependency() == 1 || $plugin->getHasOwnDeamon() == 1 || method_exists($plugin->getId(), 'health')) {
		echo '<legend>';
		if (file_exists(dirname(__FILE__) . '/../../' . $plugin->getPathImgIcon())) {
			echo '<img class="img-responsive" style="width : 20px;display:inline-block;" src="' . $plugin->getPathImgIcon() . '" /> ';
		} else {
			echo '<i class="' . $plugin->getIcon() . '"></i> ';
		}
		echo '{{Santé }} <a target="_blank" href="index.php?v=d&p=plugin&id=' . $plugin->getId() . '">' . $plugin->getName() . '</a></legend>';
		echo '<table class="table table-condensed table-bordered">';
		echo '<thead><tr><th style="width : 250px;"></th><th style="width : 150px;">{{Résultat}}</th><th>{{Conseil}}</th></tr></thead>';
		echo '<tbody>';
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
			$deamon_info = $plugin->deamon_info();
			echo '<tr>';
			echo '<td style="font-weight : bold;">';
			echo '{{Configuration démon}}';
			echo '</td>';
			switch ($deamon_info['launchable']) {
				case 'ok':
					echo '<td class="alert alert-success">{{OK}}</td>';
					break;
				case 'nok':
					echo '<td class="alert alert-danger">{{NOK}}</td>';
					break;
			}
			echo '<td>';
			echo $deamon_info['launchable_message'];
			echo '</td>';
			echo '</tr>';
			echo '<tr>';
			echo '<td style="font-weight : bold;">';
			echo '{{Status démon}}';
			echo '</td>';
			switch ($deamon_info['state']) {
				case 'ok':
					echo '<td class="alert alert-success">{{OK}}</td>';
					break;
				case 'nok':
					echo '<td class="alert alert-danger">{{NOK}}</td>';
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
							echo '<td class="alert alert-danger" title="' . $deamon_info['launchable_message'] . '">{{NOK}}</td>';
							break;
					}
					echo '<td>';
					echo '</td>';
					echo '</tr>';
					echo '<tr>';
					echo '<td style="font-weight : bold;">';
					echo '{{Status démon}} ' . $jeeNetwork->getName();
					echo '</td>';
					switch ($deamon_info['state']) {
						case 'ok':
							echo '<td class="alert alert-success">{{OK}}</td>';
							break;
						case 'nok':
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
