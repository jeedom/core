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
	<div class="row row-overflow">
		<div class="col-sm-6">
			<legend><i class="fas fa-folder-open"></i>  {{Sauvegardes locales}}</legend>
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
					<hr class="hrPrimary">
				</fieldset>
			</form>
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
				echo '<legend>'.$icon.' {{Sauvegardes}} ' . $value['name'] . '</legend>';
				echo '<form class="form-horizontal repo">';
				echo '<fieldset>';
				echo '<div class="form-group">';
				echo '<label class="col-sm-6 col-xs-6">{{Envoi des sauvegardes}}</label>';
				echo '<div class="col-sm-4 col-xs-6">';
				echo '<input type="checkbox" class="configKey" data-l1key="' . $rkey . '::cloudUpload" />';
				echo '</div>';
				echo '</div>';
				echo '<div class="form-group">';
				echo '<label class="col-xs-12"><i class="fas fa-tape"></i> {{Sauvegardes disponibles}}</label>';
				echo '<div class="col-xs-12">';
				echo '<select class="form-control sel_restoreCloudBackup" data-repo="' . $rkey . '">';
				echo '<option>{{Chargement...}}</option>';
				echo '</select>';
				echo '<script>';
				echo 'REPO_LIST.push("' . $rkey . '");';
				echo '</script>';
				echo '</div>';
				echo '</div>';
				echo '<div class="form-group">';
				echo '<label class="col-sm-6 col-xs-12"></label>';
				echo '<div class="col-sm-6 col-xs-12">';
				echo '<a class="btn btn-warning bt_restoreRepoBackup" data-repo="' . $rkey . '" style="width:100%;"><i class="fas fa-sync fa-spin" style="display:none;"></i> <i class="far fa-file"></i> {{Restaurer la sauvegarde}}</a>';
				echo '</div>';
				echo '</div>';
				echo '<hr class="hrPrimary">';
				echo '</fieldset>';
				echo '</form>';
			}
			?>
		</div>
		<div class="col-sm-6">
			<legend><i class="fas fa-info-circle"></i>  {{Informations}}</legend>
			<pre id="pre_backupInfo" style="overflow: scroll;"></pre>
		</div>
	</div>
</div>

<?php include_file("desktop", "backup", "js");?>
