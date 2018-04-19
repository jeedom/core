<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$user = user::byId(init('id'));

if (!is_object($user)) {
	throw new Exception(__('Impossible de trouver l\'utilisateur : ', __FILE__) . init('id'));
}
sendVarToJs('user_rights', utils::o2a($user));
if ($user->getProfils() != 'restrict') {
	echo '<div class="alert alert-danger">{{Attention : le compte utilisateur n\'a pas un profil "Utilisateur limité", aucune restriction mise ici ne pourra donc s\'appliquer}}</div>';
}
?>
<div style="display: none;" id="div_userRightAlert"></div>
<a class="btn btn-success pull-right" id="bt_usersRightsSave"><i class="far fa-check-circle"></i> {{Sauvegarder}}</a>
<ul class="nav nav-tabs" role="tablist">
	<li role="presentation" class="active"><a href="#tab_eqLogic" aria-controls="tab_eqLogic" role="tab" data-toggle="tab">{{Equipements}}</a></li>
	<li role="presentation"><a href="#tab_scenario" aria-controls="tab_scenario" role="tab" data-toggle="tab">{{Scénarios}}</a></li>
</ul>

<div class="tab-content" id="div_tasbUserRights">
	<span class="userAttr" data-l1key="id" style="display : none;"/>
	<div role="tabpanel" class="tab-pane active" id="tab_eqLogic">
		<table class='table table-condensed table-bordered tablesorter'>
			<thead>
				<tr>
					<th>{{Equipement}}</th>
					<th data-sorter="select-text">{{Droits}}</th>
				</tr>
			</thead>
			<tbody>
				<?php
foreach (eqLogic::all() as $eqLogic) {
	echo '<tr>';
	echo '<td>' . $eqLogic->getHumanName(true) . '</td>';
	echo '<td>';
	echo '<select class="form-control userAttr input-sm" data-l1key="rights" data-l2key="eqLogic' . $eqLogic->getId() . '">';
	echo '<option value="n">{{Aucun}}</option>';
	echo '<option value="r">{{Visualisation}}</option>';
	echo '<option value="rx">{{Visualisation et exécution}}</option>';
	echo '</select>';
	echo '</td>';
	echo '</tr>';
}
?>
			</tbody>
		</table>
	</div>
	<div role="tabpanel" class="tab-pane" id="tab_scenario">
		<table class='table table-condensed table-bordered tablesorter'>
			<thead>
				<tr>
					<th>{{Scénario}}</th>
					<th data-sorter="false" data-filter="false">{{Droits}}</th>
				</tr>
			</thead>
			<tbody>
				<?php
foreach (scenario::all() as $scenario) {
	echo '<tr>';
	echo '<td>' . $scenario->getHumanName(true, true, true) . '</td>';
	echo '<td>';
	echo '<select class="form-control userAttr input-sm" data-l1key="rights" data-l2key="scenario' . $scenario->getId() . '">';
	echo '<option value="n">{{Aucun}}</option>';
	echo '<option value="r">{{Visualisation}}</option>';
	echo '<option value="rx">{{Visualisation et exécution}}</option>';
	echo '</select>';
	echo '</td>';
	echo '</tr>';
}
?>
			</tbody>
		</table>
	</div>
</div>

<script>
	$('#div_tasbUserRights').setValues(user_rights, '.userAttr');
	initTableSorter();

	$("#bt_usersRightsSave").on('click',  function (event) {
		jeedom.user.save({
			users: $('#div_tasbUserRights').getValues('.userAttr'),
			error: function (error) {
				$('#div_userRightAlert').showAlert({message: error.message, level: 'danger'});
			},
			success: function () {
				$('#div_userRightAlert').showAlert({message: '{{Sauvegarde effectuée}}', level: 'success'});
				modifyWithoutSave = false;
			}
		});
	});
</script>
