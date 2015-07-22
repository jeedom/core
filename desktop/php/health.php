<?php
if (!hasRight('health', true)) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<legend>{{Santé de Jeedom}}</legend>
<table class="table table-condensed table-bordered">
	<thead><tr><th></th><th>{{Résultat}}</th><th>{{Conseil}}</th></tr></thead>
	<tbody>
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
			<td style="font-weight : bold;">{{En cours de démarrage}}</td>
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
			<td style="font-weight : bold;">{{Date système OK}}</td>
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
			<td style="font-weight : bold;">{{Droit sudo}}</td>
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
			<td style="font-weight : bold;">{{Version PHP}}</td>
			<?php
if (version_compare(phpversion(), '5.5', '>=')) {
	echo '<td class="alert alert-success">' . phpversion() . '</td>';
	echo '<td></td>';
} else {
	echo '<td class="alert alert-danger">' . phpversion() . '</td>';
	echo '<td></td>';
}
?>
		</tr>

		<tr>
			<td style="font-weight : bold;">{{Version NodeJS}}</td>
			<?php
$version = str_replace('v', '', shell_exec('nodejs -v'));
if (version_compare($version, '0.12', '>=')) {
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

	</tbody>
</table>