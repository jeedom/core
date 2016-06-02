<?php
if (!hasRight('backupview', true)) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<div id="backup">
    <div class="row row-overflow">
        <div class="col-sm-6">
            <legend><i class="fa fa-floppy-o"></i>  {{Sauvegardes}}</legend>
            <form class="form-horizontal">
                <fieldset>
                    <div class="form-group">
                        <label class="col-sm-4 col-xs-6 control-label">{{Sauvegardes}}</label>
                        <div class="col-sm-8 col-xs-6">
                            <a class="btn btn-success bt_backupJeedom"><i class="fa fa-refresh fa-spin" style="display : none;"></i> <i class="fa fa-floppy-o"></i> {{Lancer}}</a>
                        </div>
                    </div>
                    <div class="form-group expertModeVisible">
                        <label class="col-sm-4 col-xs-6 control-label">{{Emplacement des sauvegardes}}</label>
                        <div class="col-sm-4 col-xs-6">
                            <input type="text" class="configKey form-control" data-l1key="backup::path" />
                        </div>
                    </div>
                    <div class="form-group expertModeVisible">
                        <label class="col-sm-4 col-xs-6 control-label">{{Nombre de jour(s) de mémorisation des sauvegardes}}</label>
                        <div class="col-sm-4 col-xs-6">
                            <input type="text" class="configKey form-control" data-l1key="backup::keepDays" />
                        </div>
                    </div>
                    <div class="form-group expertModeVisible">
                        <label class="col-sm-4 col-xs-6 control-label">{{Taille totale maximale des backups (Mo)}}</label>
                        <div class="col-sm-4 col-xs-6">
                            <input type="text" class="configKey form-control" data-l1key="backup::maxSize" />
                        </div>
                    </div>
                </fieldset>
            </form>
            <legend><i class="fa fa-folder-open"></i>  {{Sauvegardes locales}}</legend>
            <form class="form-horizontal">
                <fieldset>
                    <div class="form-group">
                        <label class="col-sm-4 col-xs-6 control-label">{{Sauvegardes disponibles}}</label>
                        <div class="col-sm-4 col-xs-6">
                            <select class="form-control" id="sel_restoreBackup"> </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 col-xs-6 control-label">{{Restaurer la sauvegarde}}</label>
                        <div class="col-sm-4 col-xs-6">
                            <a class="btn btn-warning" id="bt_restoreJeedom"><i class="fa fa-refresh fa-spin" style="display : none;"></i> <i class="fa fa-file"></i> {{Restaurer}}</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 col-xs-6 control-label">{{Supprimer la sauvegarde}}</label>
                        <div class="col-sm-4 col-xs-6">
                            <a class="btn btn-danger" id="bt_removeBackup"><i class="fa fa-trash-o"></i> {{Supprimer}}</a>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 col-xs-6 control-label">{{Envoyer une sauvegarde}}</label>
                        <div class="col-sm-8 col-xs-6">
                            <span class="btn btn-default btn-file">
                                <i class="fa fa-cloud-upload"></i> {{Envoyer}}<input id="bt_uploadBackup" type="file" name="file" data-url="core/ajax/jeedom.ajax.php?action=backupupload&jeedom_token=<?php echo ajax::getToken(); ?>">
                            </span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 col-xs-6 control-label">{{Télécharger la sauvegarde}}</label>
                        <div class="col-sm-4 col-xs-6">
                            <a class="btn btn-success" id="bt_downloadBackup"><i class="fa fa-cloud-download"></i> {{Télécharger}}</a>
                        </div>
                    </div>
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
	echo '<legend><i class="fa fa-cloud"></i> {{Sauvegardes}} ' . $value['name'] . '</legend>';
	echo '<form class="form-horizontal repo">';
	echo '<fieldset>';
	echo '<div class="form-group">';
	echo '<label class="col-sm-4 col-xs-6 control-label">{{Envoyer les sauvegardes dans le cloud}}</label>';
	echo '<div class="col-sm-4 col-xs-6">';
	echo '<input type="checkbox" class="configKey bootstrapSwitch" data-l1key="' . $rkey . '::cloudUpload" />';
	echo '</div>';
	echo '</div>';
	try {
		$listeCloudBackup = $class::listeBackup();
	} catch (Exception $e) {
		$listeCloudBackup = array();
		echo '<div class="alert alert-danger">' . $e->getMessage() . '</div>';
	}
	echo '<div class="form-group">';
	echo '<label class="col-sm-4 col-xs-6 control-label">{{Sauvegardes disponibles}}</label>';
	echo '<div class="col-sm-6 col-xs-6">';
	echo '<select class="form-control sel_restoreCloudBackup">';
	try {
		foreach ($listeCloudBackup as $key => $backup) {
			if (is_numeric($key)) {
				echo '<option>' . $backup . '</option>';
			}
		}
	} catch (Exception $e) {

	}
	echo '</select>';
	echo '</div>';
	echo '</div>';
	echo '<div class="form-group">';
	echo '<label class="col-sm-4 col-xs-6 control-label">{{Restaurer la sauvegarde}}</label>';
	echo '<div class="col-sm-4 col-xs-6">';
	echo '<a class="btn btn-warning bt_restoreRepoBackup" data-repo="' . $rkey . '"><i class="fa fa-refresh fa-spin" style="display : none;"></i> <i class="fa fa-file"></i> {{Restaurer}}</a>';
	echo '</div>';
	echo '</div>';
	echo '</fieldset>';
	echo '</form>';
}
?>

        <div class="form-actions" style="height: 20px;">
            <a class="btn btn-success" id="bt_saveBackup"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
        </div>
    </div>
    <div class="col-sm-6">
        <legend><i class="fa fa-info-circle"></i>  {{Informations}}</legend>
        <pre id="pre_backupInfo" style="overflow: scroll;"></pre>
    </div>
</div>
</div>


<?php include_file("desktop", "backup", "js");?>
