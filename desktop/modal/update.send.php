<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$repo = update::repoById(init('repo'));
if ($repo['enable'] == 0) {
	throw new Exception(__('Le repository est inactif : ', __FILE__) . init('repo'));
}
include_file('core', init('repo', 'market') . '.send', 'repo');
?>