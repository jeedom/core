<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
if (strtotime(config::byKey('update::lastCheck')) < (strtotime('now -120min'))) {
	try {
		update::checkAllUpdate();
	} catch (\Exception $e) {
		echo '<div class="alert alert-danger">{{Erreur sur la vérification des mises à jour :}}' . ' ' . $e->getMessage() . '</div>';
	}
}

$hardware = jeedom::getHardwareName();
$distrib = system::getDistrib();
$coreRemoteVersion = update::byLogicalId('jeedom')->getRemoteVersion();
$showUpdate = true;
$showUpgrade = false;
if ($coreRemoteVersion >= '4.2' && $distrib == 'debian') {
	$version = trim(strtolower(file_get_contents('/etc/debian_version')));
	if ($version < '10') {
		$system = strtoupper($hardware) . ' - ' . ucfirst($distrib) . ' ' . $version;
		$showUpdate = false;
		$alertLevel = 'alert alert-warning';
		if ($hardware == 'miniplus' || $hardware == 'Jeedomboard') {
			$messageAlert = '{{Votre système actuel fonctionnant correctement et n\'étant plus assez performant pour être en mesure de continuer à le faire dans les meilleures conditions à l\'avenir, nous vous invitons à ne plus mettre à jour le core de Jeedom dorénavant.}}';
		} else if ($hardware == 'smart') {
			$showUpgrade = true;
			$messageAlert = '{{Afin de pouvoir accéder aux futures mises à jour du core, veuillez mettre à niveau l\'environnement Linux de votre box Smart}}';
		} else {
			$messageAlert = '{{Afin de pouvoir accéder aux futures mises à jour du core, veuillez mettre à niveau l\'environnement Linux de votre box vers}}';
			$messageAlert .= ' <strong>Debian 10 Buster</strong>.<br><em>';
			if (config::byKey('doc::base_url', 'core') != '') {
				$messageAlert .= ' {{Il est conseillé de procéder à une nouvelle installation en Debian 10 Buster puis de restaurer votre dernière sauvegarde Jeedom plutôt que mettre directement à jour l\'OS en ligne de commande. Consulter}} <a href="' . config::byKey('doc::base_url', 'core') . '/fr_FR/installation/#Installation" target="_blank">{{la documentation d\'installation}}</a> {{pour plus d\'informations.}}' . '</em>';
			}
		}
		echo '<div class="col-xs-12 text-center ' . $alertLevel . '"><strong>' . $system . '</strong><br>' . $messageAlert . '</div>';
	}
}
$logUpdate = log::get('update', 0, -1);
if ((!isset($logUpdate[0])) || strpos($logUpdate[0], 'END UPDATE')) {
	sendVarToJS('jeephp2js.isUpdating', '0');
} else {
	sendVarToJS('jeephp2js.isUpdating', '1');
}
?>

<div class="row row-overflow">
	<div class="col-xs-12">
		<i class="far fa-clock"></i> <span>{{Dernière vérification :}} </span>
		<span class="label label-info" id="span_lastUpdateCheck"></span>
		<div class="input-group pull-right" style="display:inline-flex">
			<span class="input-group-btn">
				<a class="btn btn-info btn-sm roundedLeft" id="bt_checkAllUpdate"><i class="fas fa-sync"></i> {{Vérifier les mises à jour}}
				</a><a class="btn btn-success btn-sm" id="bt_saveUpdate"><i class="fas fa-check-circle"></i> {{Sauvegarder}}
				</a><?php if ($showUpdate == true) { ?><a href="#" class="btn btn-sm btn-warning roundedRight" id="bt_updateJeedom"><i class="fas fa-check"></i> {{Mettre à jour}}
					</a><?php } ?>
			</span>
		</div>
		<br /><br />
		<div id="progressbarContainer" class="col-sm-12 hidden">
			<div class="progress" style="width:100%;height:22px;margin-top: 12px;">
				<div class="progress-bar progress-bar-striped" id="div_progressbar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em;font-size:18px;">
					N/A
				</div>
			</div>
		</div>

		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation" class="active"><a data-target="#coreplugin" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-archive"></i> {{Core et plugins}}</a></li>
			<?php if (!in_array(jeedom::getHardwareName(), array('miniplus', 'smart', 'Atlas', 'Luna'))) { ?>
				<li role="presentation"><a data-target="#os" aria-controls="profile" role="tab" data-toggle="tab" class="bt_refreshOsPackageUpdate" data-forceRefresh="0"><i class="fas fa-box"></i> {{OS/Package}}</a></li>
			<?php } ?>
			<li role="presentation"><a data-target="#log" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-info"></i> {{Informations}}</a></li>
		</ul>

		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="coreplugin">
				<table class="ui-table-reflow table table-condensed dataTable" id="table_update">
					<thead>
						<tr>
							<th>{{Etat}}</th>
							<th>{{Nom}}</th>
							<th data-type="date" data-format="YYYY-MM-DD hh:mm:ss">{{Version installée}}</th>
							<th data-type="date" data-format="YYYY-MM-DD hh:mm:ss">{{Dernière version}}</th>
							<th data-type="date" data-format="YYYY-MM-DD hh:mm:ss">{{Mise à jour faite le}}</th>
							<th data-type="checkbox">{{Options}}</th>
							<th data-sortable="false">{{Actions}}</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>
			</div>
			<div role="tabpanel" class="tab-pane" id="log" style="overflow:auto;overflow-x: hidden">
				<legend><i class="fas fa-info-circle"></i> {{Log :}}</legend>
				<div id="div_log">
					<pre id="pre_updateInfo"></pre>
				</div>
			</div>


			<div role="tabpanel" class="tab-pane" id="os" style="overflow:auto;overflow-x: hidden">
				<div class="alert alert-info">{{IMPORTANT : Ne sont affichés ici que les packages Linux n’étant pas à jour. Une liste vide signifiant que votre système Linux est à jour.}}</div>

				<div class="input-group pull-right" style="display:inline-flex">
					<span class="input-group-btn">
						<a class="bt_refreshOsPackageUpdate btn btn-success roundedLeft" data-forceRefresh="1"><i class="fas fa-sync"></i> {{Mettre à jour la liste}}</a>
						<a class="bt_OsPackageUpdate btn btn-warning" data-type="apt"><i class="fas fa-sync"></i> {{Mettre à jour les packages OS}}</a>
						<a class="bt_OsPackageUpdate btn btn-warning" data-type="pip2"><i class="fas fa-sync"></i> {{Mettre à jour les packages Python2}}</a>
						<a class="bt_OsPackageUpdate btn btn-warning roundedRight" data-type="pip3"><i class="fas fa-sync"></i> {{Mettre à jour les packages Python3}}</a>
					</span>
				</div>

				<table class="ui-table-reflow table table-condensed" id="table_osUpdate">
					<thead>
						<tr>
							<th style="width:50px">{{Type}}</th>
							<th>{{Nom}}</th>
							<th>{{Version installée}}</th>
							<th>{{Dernière version}}</th>
							<th data-sorter="false" data-filter="false" style="width:120px">{{Actions}}</th>
						</tr>
					</thead>
					<tbody>
					</tbody>
				</table>

			</div>
		</div>

		<div id="md_specifyUpdate-template" class="hidden">
			<form class="form-horizontal">
				<fieldset>
					<div class="alert alert-warning">
						{{Avant toute mise à jour, merci de consulter le}} <span id="bt_changelogCore" class="bt_changelogCore label cursor alert-info">{{changelog}}</span> {{du Core}}.
					</div>

					<div class="form-group">
						<label>Core</label>
						<div class="form-group">
							<label class="col-xs-6 control-label">{{Pré-update}}
								<sup><i class="fas fa-question-circle" data-title="{{Mettre d'abord le script d'update à jour.}}"></i></sup>
							</label>
							<div class="col-xs-4">
								<input type="checkbox" class="updateOption" data-l1key="preUpdate" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-xs-6 control-label">{{Sauvegarder avant}}
								<sup><i class="fas fa-question-circle" data-title="{{Réalise une sauvegarde avant de lancer la mise à jour.}}"></i></sup>
							</label>
							<div class="col-xs-4">
								<input type="checkbox" class="updateOption" data-l1key="backup::before" checked />
							</div>
						</div>
						<div class="form-group">
							<label class="col-xs-6 control-label">{{Mettre à jour le Core}}</label>
							<div class="col-xs-4">
								<input type="checkbox" class="updateOption" data-l1key="core" checked />
							</div>
						</div>
						<div class="form-group">
							<label class="col-xs-6 control-label">{{Mode forcé}}
								<sup><i class="fas fa-question-circle" data-title="{{Continuer la mise à jour en cas d'erreur.}}"></i></sup>
							</label>
							<div class="col-xs-4">
								<input type="checkbox" class="updateOption" data-l1key="force" />
							</div>
						</div>
						<label>Plugins</label>
						<div class="form-group">
							<label class="col-xs-6 control-label">{{Mettre à jour les plugins}}</label>
							<div class="col-xs-4">
								<input type="checkbox" class="updateOption" data-l1key="plugins" checked />
							</div>
						</div>
					</div>
					<div class="alert alert-danger">{{L'option suivante n'est à utiliser que sur demande du support.}}</div>
					<div class="form-group">
						<label class="col-xs-6 control-label ">{{Script d'update à réappliquer}}</label>
						<div class="col-xs-5">
							<select id="sel_updateVersion" class="form-control input-sm updateOption" data-l1key="update::reapply">
								<option value="">{{Aucune}}</option>
									<?php
									$updates = array();
									foreach ((update::listCoreUpdate()) as $udpate) {
										$updates[str_replace(array('.php', '.sql'), '', $udpate)] = str_replace(array('.php', '.sql'), '', $udpate);
									}
									usort($updates, 'version_compare');
									$updates = array_reverse($updates);
									$options = '';
									foreach ($updates as $value) {
										$options .= '<option value="' . $value . '">' . $value . '</option>';
									}
									echo $options;
								?>
							</select>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
	</div>
</div>

<?php include_file('desktop', 'update', 'js'); ?>
