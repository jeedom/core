<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
echo '<script>';
echo 'REPO_LIST = []';
echo '</script>';
?>

<div id="backup">
	<br/>
	<div class="pull-right" style="display:inline-flex;">
		<a id="bt_saveBackup" class="btn btn-success pull-right"><i class="far fa-check-circle"></i> Sauvegarder</a>
	</div>
	<br/><br/>
	<div class="row">
		<div class="col-lg-6 col-sm-12">
			<div class="row">
				<div class="col-sm-12">
					<div class="panel panel-primary">
						<div class="panel-heading">
							<h3 class="panel-title"><i class="fas fa-folder-open"></i> {{Sauvegardes locales}}
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
												<a class="btn btn-success" id="bt_downloadBackup" style="width:100%;"><i class="fas fa-cloud-download-alt"></i> {{Télécharger la sauvegarde}}</a>
											</div>
											<div class="col-sm-6 col-xs-12">
												<span class="btn btn-default btn-file" style="width:100%;">
													<i class="fas fa-cloud-upload-alt"></i> {{Ajouter une sauvegarde}}<input id="bt_uploadBackup" type="file" name="file" data-url="core/ajax/jeedom.ajax.php?action=backupupload&jeedom_token=<?php echo ajax::getToken(); ?>">
												</span>
											</div>
										</div>
									</fieldset>
								</form>
							</div>
						</div>

						<?php
						foreach (update::listRepo() as $rkey => $value) {
							if ($value['scope']['backup'] == false) {
								continue;
							}
							if ($value['enable'] == 0) {
								continue;
							}
							$class = 'repo_' . $rkey;

							$icon = '<i class="fas fa-network-wired"></i>';
							if (strtolower($value['name']) == 'market') $icon = '<i class="fas fa-cloud"></i>';

							$div = '<div class="panel panel-primary">';
							$div .= '<div class="panel-heading">';
							$div .= '<h3 class="panel-title">'.$icon.' {{Sauvegardes}} ' . $value['name'];
							$div .= '</div>';
							$div .= '<div class="panel-body">';

							//$div .= '<legend>'.$icon.' {{Sauvegardes}} ' . $value['name'] . '</legend>';
							$div .= '<form class="form-horizontal repo">';
							$div .= '<fieldset>';
							$div .= '<div class="form-group">';
							$div .= '<label class="col-sm-6 col-xs-6">{{Envoi des sauvegardes}}</label>';
							$div .= '<div class="col-sm-4 col-xs-6">';
							$div .= '<input type="checkbox" class="configKey" data-l1key="' . $rkey . '::cloudUpload" />';
							$div .= '</div>';
							$div .= '</div>';
							$div .= '<div class="form-group">';
							$div .= '<label class="col-xs-12"><i class="fas fa-tape"></i> {{Sauvegardes disponibles}}</label>';
							$div .= '<div class="col-xs-12">';
							$div .= '<select class="form-control sel_restoreCloudBackup" data-repo="' . $rkey . '">';
							$div .= '<option>{{Chargement...}}</option>';
							$div .= '</select>';
							$div .= '<script>';
							$div .= 'REPO_LIST.push("' . $rkey . '");';
							$div .= '</script>';
							$div .= '</div>';
							$div .= '</div>';
							$div .= '<div class="form-group">';
							$div .= '<label class="col-sm-6 col-xs-12"></label>';
							$div .= '<div class="col-sm-6 col-xs-12">';
							$div .= '<a class="btn btn-warning bt_restoreRepoBackup" data-repo="' . $rkey . '" style="width:100%;"><i class="fas fa-sync fa-spin" style="display:none;"></i> <i class="far fa-file"></i> {{Rapatrier la sauvegarde}}</a>';
							$div .= '</div>';
							$div .= '</div>';
							$div .= '</fieldset>';
							$div .= '</form>';
							$div .= '</div>';
							$div .= '</div>';
							echo $div;
						}
						?>
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

	<?php include_file("desktop", "backup", "js");?>
