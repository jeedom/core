<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
if(strtotime(config::byKey('update::lastCheck')) < (strtotime('now -120min'))){
	try {
		update::checkAllUpdate();
	} catch (\Exception $e) {
		echo '<div class="alert alert-danger">{{Erreur sur la verification des mies à jour : }}'.$e->getMessage().'</div>';
	}
}
?>

<br/>
<i class="far fa-clock"></i> <span>{{Dernière vérification : }}</span>
<span class="label label-info" id="span_lastUpdateCheck"></span>
<div class="input-group pull-right" style="display:inline-flex">
	<span class="input-group-btn">
		<a href="#" class="btn btn-sm btn-warning roundedLeft" id="bt_updateJeedom"><i class="fas fa-check"></i> {{Mettre à jour}}</a><a class="btn btn-info btn-sm" id="bt_checkAllUpdate"><i class="fas fa-sync"></i> {{Vérifier les mises à jour}}</a><a class="btn btn-success btn-sm roundedRight" id="bt_saveUpdate"><i class="far fa-check-circle"></i> {{Sauvegarder}}</a>
	</span>
</div>
<br/><br/>

<ul class="nav nav-tabs" role="tablist">
	<li role="presentation" class="active"><a href="#coreplugin" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-archive"></i> {{Core et plugins}}</a></li>
	<li role="presentation"><a href="#log" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-info"></i> {{Informations}}</a></li>
</ul>

<div class="tab-content">
	<div role="tabpanel" class="tab-pane active" id="coreplugin">
		<div class="col-sm-12 progressbarContainer hidden" style="display: inline-flex;">
			<div class="progress" style="width:100%;height:22px;margin-top: 12px;">
				<div class="progress-bar progress-bar-striped" id="div_progressbar" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100" style="min-width: 2em;font-size:18px;">
					N/A
				</div>
			</div>
		</div>

		<table class="table table-condensed table-bordered tablesorter" id="table_update" style="margin-top: 5px;">
			<thead>
				<tr>
					<th data-sorter="false" style="width:50px;"></th>
					<th>{{Nom}}</th>
					<th data-sorter="shortDate">{{Version installée}}</th>
					<th data-sorter="shortDate">{{Dernière version}}</th>
					<th data-sorter="checkbox" data-filter="false">{{Options}}</th>
					<th data-sorter="false" data-filter="false">{{Actions}}</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
	<div role="tabpanel" class="tab-pane" id="log" style="overflow:auto;overflow-x: hidden">
		<legend><i class="fas fa-info-circle"></i>  {{Log :}}</legend>
		<div id="div_log">
			<pre id="pre_updateInfo"></pre>
		</div>
	</div>
</div>

<div id="md_specifyUpdate" style="overflow-x: hidden;">
	<form class="form-horizontal">
		<fieldset>
			<div class="alert alert-danger">
				{{Avant toute mise à jour, merci de consulter}} <a class="warning" style="color:var(--al-warning-color)!important" target="_blank" href="https://jeedom.github.io/core/fr_FR/noteVersion">{{la note de version}}</a> {{du core de Jeedom}}.
			</div>
			<div class="form-group">
				<div class="form-group">
					<label class="col-xs-6 control-label">{{Pré-update}}
						<sup><i class="fas fa-question-circle tooltips" title="{{Mettre d'abord le script d'update à jour.}}"></i></sup>
					</label>
					<div class="col-xs-4">
						<input type="checkbox" class="updateOption" data-l1key="preUpdate" />
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-6 control-label">{{Sauvegarder avant}}</label>
					<div class="col-xs-4">
						<input type="checkbox" class="updateOption" data-l1key="backup::before" checked />
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-6 control-label">{{Mettre à jour les plugins}}</label>
					<div class="col-xs-4">
						<input type="checkbox" class="updateOption" data-l1key="plugins" checked />
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-6 control-label">{{Mettre à jour le core}}</label>
					<div class="col-xs-4">
						<input type="checkbox" class="updateOption" data-l1key="core" checked />
					</div>
				</div>
				<div class="form-group">
					<label class="col-xs-6 control-label">{{Mode forcé}}
						<sup><i class="fas fa-question-circle tooltips" title="{{Continuer la mise à jour en cas d'erreur.}}"></i></sup>
					</label>
					<div class="col-xs-4">
						<input type="checkbox" class="updateOption" data-l1key="force" />
					</div>
				</div>
			</div>
			<div class="alert alert-danger">{{L'option suivante n'est à modifier que sur demande du support sinon il faut ABSOLUMENT qu'elle soit sur "Aucune"}}</div>
			<div class="form-group has-error">
				<label class="col-xs-6 control-label ">{{Mise à jour à réappliquer}}</label>
				<div class="col-xs-5">
					<select id="sel_updateVersion" class="form-control updateOption" data-l1key="update::reapply">
						<option value="">{{Aucune}}</option>
						<?php
						$updates = array();
						foreach (update::listCoreUpdate() as $udpate) {
							$updates[str_replace(array('.php', '.sql'), '', $udpate)] = str_replace(array('.php', '.sql'), '', $udpate);
						}
						usort($updates, 'version_compare');
						$updates = array_reverse($updates);
						foreach ($updates as $value) {
							echo '<option value="' . $value . '">' . $value . '</option>';
						}
						?>
					</select>
				</div>
			</div>
		</fieldset>
	</form>
	<a class="btn btn-success pull-right" style="color:white;" id="bt_doUpdate"><i class="fas fa-check"></i> {{Mettre à jour}}</a>
</div>

<?php include_file('desktop', 'update', 'js');?>
