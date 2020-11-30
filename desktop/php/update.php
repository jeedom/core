<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<br/>
<i class="fas fa-clock-o" style="cursor:default;"></i> <span style="cursor:default;">{{Dernière vérification : }}</span><span class="label label-info" id="span_lastUpdateCheck" style="margin-bottom: 5px;font-size:1em;cursor:default;"></span>
<a class="btn btn-success pull-right" id="bt_saveUpdate" style="margin-top:5px;"><i class="far fa-check-circle"></i> {{Sauvegarder}}</a>
<a class="btn btn-info pull-right" id="bt_checkAllUpdate" style="margin-top:5px;"><i class="fas fa-refresh"></i> {{Vérifier les mises à jour}}</a>
<?php if (jeedom::getHardwareName() != 'miniplus' && jeedom::getHardwareName() != 'Jeedomboard') { ?>
	<a class="btn btn-warning pull-right" id="bt_upgradeV4" style="margin-top:5px;"><i class="fas fa-level-up-alt"></i> {{Mettre à niveau V4}}</a>
<?php } ?>
<a href="#" class="btn btn-default pull-right" id="bt_updateJeedom" style="margin-top:5px;"><i class="fas fa-check"></i> {{Mettre à jour}}</a>
<br/><br/>

<ul class="nav nav-tabs" role="tablist">
	<li role="presentation" class="active"><a href="#coreplugin" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-archive"></i> {{Core et plugins}}</a></li>
	<li role="presentation"><a href="#other" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-feather"></i> {{Autre}}</a></li>
	<li role="presentation"><a href="#log" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-file"></i> {{Logs}}</a></li>
</ul>

<div class="tab-content">
	<div role="tabpanel" class="tab-pane active" id="coreplugin">
		<table class="table table-condensed table-bordered tablesorter" id="table_update" style="margin-top: 5px;">
			<thead>
				<tr>
					<th data-sorter="false" style="width:50px;"></th>
					<th data-sorter="false">{{Nom}}</th>
					<th data-sorter="false" data-filter="false">{{Version installée}}</th>
					<th data-sorter="false" data-filter="false">{{Dernière version}}</th>
					<th data-sorter="checkbox" data-filter="false">{{Options}}</th>
					<th data-sorter="false" data-filter="false">{{Actions}}</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
	<div role="tabpanel" class="tab-pane" id="other">
		<table class="table table-condensed table-bordered tablesorter" id="table_updateOther" style="margin-top: 5px;">
			<thead>
				<tr>
					<th data-sorter="false" style="width:50px;"></th>
					<th data-sorter="false">{{Nom}}</th>
					<th data-sorter="false" data-filter="false">{{Version installée}}</th>
					<th data-sorter="false" data-filter="false">{{Dernière version}}</th>
					<th data-sorter="checkbox" data-filter="false">{{Options}}</th>
					<th data-sorter="false" data-filter="false">{{Actions}}</th>
				</tr>
			</thead>
			<tbody>
			</tbody>
		</table>
	</div>
	<div role="tabpanel" class="tab-pane" id="log" style="overflow:auto;">
		<legend style="cursor:default;"><i class="fas fa-info-circle"></i>  {{Informations :}}</legend>
		<pre id="pre_updateInfo"></pre>
	</div>
</div>

<div id="md_specifyUpdate">
	<form class="form-horizontal">
		<fieldset>
			<div class="alert alert-warning">
				{{Avant toute mise à jour, merci de consulter le}} <span id="bt_changelogCore" class="label cursor alert-info">{{changelog}}</span> {{du Core}}.
			</div>
			<div class="form-group">
				<div class="form-group">
					<label class="col-xs-6 control-label">{{Pré-update (mettre à jour le script d'update avant)}}</label>
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
					<label class="col-xs-6 control-label">{{Mode forcé}}</label>
					<div class="col-xs-4">
						<input type="checkbox" class="updateOption" data-l1key="force" />
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
			</div>
		</fieldset>
	</form>
	<a class="btn btn-success pull-right" style="color:white;" id="bt_doUpdate"><i class="fas fa-check"></i> {{Mettre à jour}}</a>
</div>

<?php include_file('desktop', 'update', 'js');?>
