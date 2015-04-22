<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$changelog = update::getAllUpdateChangelog();
if (count($changelog) == 0) {
	echo "<center><strong>{{Vous n'avez aucun plugin en attente de mise à jour}}</strong></center>";
} else {
	foreach (update::getAllUpdateChangelog() as $plugin_id => $changes) {
		echo '<legend>' . $plugin_id . '</legend>';
		foreach (array_reverse($changes) as $change) {
			echo '<strong>{{Version}} ' . $change['version'] . ' - ' . $change['date'] . '</strong><br/>';
			echo '<pre>';
			echo $change['change'];
			echo '</pre>';
			echo '<br/>';
		}
	}
}
?>