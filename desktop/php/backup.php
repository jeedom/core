<?php
if (!hasRight('backupview', true)) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<div id="backup">
    <div class="row row-overflow">
        <div class="col-sm-6">
            <legend>{{Sauvegardes}}</legend>
            <form class="form-horizontal">
                <fieldset>
                    <div class="form-group">
                        <label class="col-sm-4 col-xs-6 control-label">{{Sauvegardes}}</label>
                        <div class="col-sm-8 col-xs-6">
                            <a class="btn btn-success bt_backupJeedom"><i class="fa fa-refresh fa-spin" style="display : none;"></i> <i class="fa fa-floppy-o"></i> {{Lancer}}</a>
                            <?php if (config::byKey('backup::cloudUpload') == 1) {?>
                                <a class="btn btn-default bt_backupJeedom" data-noCloudBackup="1" ><i class="fa fa-refresh fa-spin" style="display : none;"></i> <i class="fa fa-floppy-o"></i> Lancer sans envoi sur le cloud</a>
                                <?php }
?>
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
                        <?php if (config::byKey('market::apikey') != '' || (config::byKey('market::username') != '' && config::byKey('market::password') != '')) {?>
                            <div class="form-group">
                                <label class="col-sm-4 col-xs-6 control-label">{{Envoyer les sauvegardes dans le cloud}}</label>
                                <div class="col-sm-4 col-xs-6">
                                    <input type="checkbox" class="configKey bootstrapSwitch" data-l1key="backup::cloudUpload" />
                                </div>
                            </div>
                            <?php }
?>
                        </fieldset>
                    </form>
                    <div class="form-actions" style="height: 20px;">
                        <a class="btn btn-success" id="bt_saveBackup"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
                    </div><br/><br/>
                    <legend>{{Sauvegardes locales}}</legend>
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
                                        <i class="fa fa-cloud-upload"></i> {{Envoyer}}<input id="bt_uploadBackup" type="file" name="file" data-url="core/ajax/jeedom.ajax.php?action=backupupload">
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
                    <?php if (config::byKey('market::apikey') != '' || (config::byKey('market::username') != '' && config::byKey('market::password') != '')) {
	?>
                       <legend>{{Sauvegardes cloud}}</legend>
                       <form class="form-horizontal">
                        <fieldset>
                            <?php
try {
		$listeCloudBackup = market::listeBackup();
	} catch (Exception $e) {
		echo '<div class="alert alert-danger">' . $e->getMessage() . '</div>';
	}
	?>
                          <div class="form-group">
                            <label class="col-sm-4 col-xs-6 control-label">{{Sauvegardes disponibles}}</label>
                            <div class="col-sm-6 col-xs-6">
                                <select class="form-control" id="sel_restoreCloudBackup">
                                    <?php
try {
		foreach ($listeCloudBackup as $key => $backup) {
			if (is_numeric($key)) {
				echo '<option>' . $backup . '</option>';
			}
		}
	} catch (Exception $e) {

	}
	?>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 col-xs-6 control-label">{{Restaurer la sauvegarde}}</label>
                        <div class="col-sm-4 col-xs-6">
                            <a class="btn btn-warning" id="bt_restoreCloudJeedom"><i class="fa fa-refresh fa-spin" style="display : none;"></i> <i class="fa fa-file"></i> {{Restaurer}}</a>
                        </div>
                    </div>
                </fieldset>
            </form>
            <?php }
?>
        </div>
        <div class="col-sm-6">
            <legend>{{Informations}}</legend>
            <pre id="pre_backupInfo" style="overflow: scroll;"></pre>
        </div>
    </div>
</div>


<?php include_file("desktop", "backup", "js");?>
