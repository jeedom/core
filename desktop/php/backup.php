<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$backupRepos = update::listBackupRepos();
$backupRepos['samba'] ??= update::repoById('samba');
?>

<div id="backup">
	<br />
	<div class="pull-right" style="display:inline-flex;">
		<a id="bt_saveBackup" class="btn btn-success pull-right"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a>
	</div>
	<br /><br />
	<div class="row">
		<div class="col-lg-6 col-sm-12">
			<div class="row">
				<div class="col-sm-12">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title"><i class="fas fa-folder-open"></i> {{Sauvegardes locales}}</h3>
						</div>
						<div class="panel-body">
							<form class="form-horizontal">
								<fieldset>
									<div class="form-group">
										<label class="col-sm-6 col-xs-6 control-label">{{Emplacement}}</label>
										<div class="col-sm-6 col-xs-6">
											<input type="text" class="configKey form-control" data-l1key="backup::path" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-6 col-xs-6 control-label">{{Rétention temporelle (jours)}}</label>
										<div class="col-sm-6 col-xs-6">
											<input type="text" class="configKey form-control" data-l1key="backup::keepDays" />
										</div>
									</div>
									<div class="form-group">
										<label class="col-sm-6 col-xs-6 control-label">{{Taille totale maximale (Mo)}}</label>
										<div class="col-sm-6 col-xs-6">
											<input type="text" class="configKey form-control" data-l1key="backup::maxSize" />
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-6 col-xs-6"></div>
										<div class="col-sm-6 col-xs-6">
											<a class="btn btn-success bt_backupJeedom" style="width:100%;"><i class="fas fa-sync fa-spin" style="display:none;"></i> <i class="fas fa-save"></i> {{Lancer une sauvegarde}}</a>
										</div>
									</div>

									<div class="form-group">
										<label class="col-xs-12"><i class="fas fa-tape"></i> {{Sauvegardes disponibles}}</label>
										<div class="col-xs-12">
											<select class="form-control" id="sel_restoreBackup"> </select>
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-6 col-xs-12">
											<a class="btn btn-danger" id="bt_removeBackup" style="width:100%;"><i class="far fa-trash-alt"></i> {{Supprimer la sauvegarde}}</a>
										</div>
										<div class="col-sm-6 col-xs-12">
											<a class="btn btn-warning" id="bt_restoreJeedom" style="width:100%;"><i class="fas fa-sync fa-spin" style="display:none;"></i> <i class="far fa-file"></i> {{Restaurer la sauvegarde}}</a>
										</div>
									</div>
									<div class="form-group">
										<div class="col-sm-6 col-xs-12">
											<?php if (substr(config::byKey('backup::path'), 0, 1) != '/') { ?>
												<a class="btn btn-success" id="bt_downloadBackup" style="width:100%;"><i class="fas fa-cloud-download-alt"></i> {{Télécharger la sauvegarde}}</a>
											<?php } ?>
										</div>
										<div class="col-sm-6 col-xs-12">
											<span class="btn btn-default btn-file" style="width:100%;">
												<i class="fas fa-cloud-upload-alt"></i> {{Ajouter une sauvegarde}}<input id="bt_uploadBackup" type="file" name="file" data-url="core/ajax/jeedom.ajax.php?action=backupupload">
											</span>
										</div>
									</div>
								</fieldset>
							</form>
						</div>
					</div>

					<?php foreach ($backupRepos as $id => $repo) {
						$samba = $id === 'samba';
						$showArchives = !$samba || repo_samba::backupEnabled();
						?>
						<div class="panel panel-primary">
							<div class="panel-heading">
								<h3 class="panel-title"><i class="fas <?= $id === 'market' ? 'fa-cloud' : 'fa-network-wired' ?>"></i> {{Sauvegardes}} <?= $repo['name'] ?></h3>
							</div>
							<div class="panel-body">
								<form class="form-horizontal repo">
									<fieldset>
										<div class="form-group">
											<label class="col-sm-6 col-xs-6" for="backupUpload_<?= $id ?>">{{Envoi automatique des sauvegardes}}</label>
											<div class="col-sm-6 col-xs-6">
												<input type="checkbox" id="backupUpload_<?= $id ?>" class="configKey" data-l1key="<?= $id ?>::cloudUpload" />
											</div>
										</div>
										<?php if ($samba) {
											foreach ($repo['configuration']['configuration'] as $key => $setting) {
												?>
												<div class="form-group">
													<label class="col-sm-6 col-xs-6 control-label" for="samba_<?= $key ?>"><?= $setting['name'] ?></label>
													<div class="col-sm-6 col-xs-6">
														<input type="<?= $setting['type'] === 'password' ? 'password' : 'text' ?>" id="samba_<?= $key ?>" class="configKey form-control" data-l1key="samba::<?= $key ?>" data-samba-key="<?= substr($key, strlen('backup::')) ?>" />
													</div>
												</div>
												<?php
											}
											?>
											<div class="form-group">
												<div class="col-sm-offset-6 col-sm-6 col-xs-12">
													<button type="button" class="btn btn-default bt_testSamba"><i class="fas fa-check"></i> {{Tester la connexion}}</button>
												</div>
											</div>
										<?php } ?>
										<?php if (!empty($repo['scope']['hasRetentionDay'])) { ?>
											<div class="form-group">
												<label class="col-sm-6 col-xs-6 control-label" for="backupRetention_<?= $id ?>">{{Rétention temporelle (jours)}}</label>
												<div class="col-sm-6 col-xs-6">
													<input type="number" min="0" id="backupRetention_<?= $id ?>" class="configKey form-control" data-l1key="<?= $id ?>::keepDays" />
												</div>
											</div>
										<?php } ?>
										<div class="repoArchives" data-repo="<?= $id ?>" <?= $showArchives ? '' : 'hidden' ?>>
											<div class="form-group">
												<label class="col-xs-12" for="repoArchive_<?= $id ?>"><i class="fas fa-tape"></i> {{Sauvegardes disponibles}}</label>
												<div class="col-xs-12">
													<select class="form-control sel_restoreCloudBackup" id="repoArchive_<?= $id ?>">
														<option>{{Chargement...}}</option>
													</select>
												</div>
											</div>
											<div class="form-group">
												<div class="col-sm-offset-6 col-sm-6 col-xs-12">
													<button type="button" class="btn btn-warning bt_restoreRepoBackup" data-repo="<?= $id ?>" style="width:100%;" disabled>
														<i class="fas fa-sync fa-spin" style="display:none;"></i> <i class="far fa-file"></i> {{Rapatrier la sauvegarde en local}}
													</button>
												</div>
											</div>
										</div>
									</fieldset>
								</form>
							</div>
						</div>
					<?php } ?>
				</div>
			</div>
		</div>
		<div class="col-lg-6 col-sm-12">
			<div class="row">
				<div class="col-sm-12 panel-backupinfo">
					<div class="panel panel-warning">
						<div class="panel-heading">
							<h3 class="panel-title"><i class="fas fa-info-circle"></i> {{Informations}}</h3>
						</div>
						<div class="panel-body">
							<pre id="pre_backupInfo"></pre>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php
include_file("desktop", "backup", "js");
?>
