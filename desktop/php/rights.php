<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

$pages = array(
	'dashboard' => array(
		'title' => 'Dashboard',
		'view' => array('title' => 'Voir'),
		// 'edit' => array('title' => 'Editer')
	),
	'display' => array(
		'title' => 'Affichage',
		'view' => array('title' => 'Voir'),
		//  'edit' => array('title' => 'Editer')
	),
	'health' => array(
		'title' => 'Santé',
		'view' => array('title' => 'Voir'),
		//  'edit' => array('title' => 'Editer')
	),
	'interact' => array(
		'title' => 'Interactions',
		'view' => array('title' => 'Voir'),
		//  'edit' => array('title' => 'Editer')
	),
	'log' => array(
		'title' => 'Log',
		'view' => array('title' => 'Voir'),
		// 'edit' => array('title' => 'Editer')
	),
	'message' => array(
		'title' => 'Message',
		'view' => array('title' => 'Voir'),
		// 'edit' => array('title' => 'Editer')
	),
	'object' => array(
		'title' => 'Objets',
		'view' => array('title' => 'Voir'),
		// 'edit' => array('title' => 'Editer')
	),
	'plan' => array(
		'title' => 'Designs',
		'view' => array('title' => 'Voir'),
		// 'edit' => array('title' => 'Editer')
	),
	'plugin' => array(
		'title' => 'Plugins',
		'view' => array('title' => 'Voir'),
		// 'edit' => array('title' => 'Editer')
	),
	'battery' => array(
		'title' => 'Batteries',
		'view' => array('title' => 'Voir'),
		// 'edit' => array('title' => 'Editer')
	),
	'scenario' => array(
		'title' => 'Scénarios',
		'view' => array('title' => 'Voir'),
		// 'edit' => array('title' => 'Editer')
	),
	'security' => array(
		'title' => 'Sécurité',
		'view' => array('title' => 'Voir'),
		//  'edit' => array('title' => 'Editer')
	),
	'sysinfo' => array(
		'title' => 'Système info',
		'view' => array('title' => 'Voir'),
		//  'edit' => array('title' => 'Editer')
	),
	'view' => array(
		'title' => 'Vues',
		'view' => array('title' => 'Voir'),
		//  'edit' => array('title' => 'Editer')
	),
	'report' => array(
		'title' => 'Rapport',
		'send' => array('title' => 'Envoyer'),
	),
);

include_file('3rdparty', 'jquery.tablesorter/theme.bootstrap', 'css');
include_file('3rdparty', 'jquery.tablesorter/jquery.tablesorter.min', 'js');
include_file('3rdparty', 'jquery.tablesorter/jquery.tablesorter.widgets.min', 'js');
?>
<div class="alert alert-info">
	<strong>{{Utilisateur (non admin uniquement) :}}</strong> <select class="form-control" id="sel_userId" style="display: inline-block; width: 200px;">
	<?php
foreach (user::all() as $user) {
	if ($user->getRights('admin')) {
		continue;
	}
	echo '<option value="' . $user->getId() . '">' . $user->getLogin() . '</option>';
}
?>
</select>
<a class="btn btn-success" id="bt_saveRights" style="margin-top:-2px;"><i class="fa fa-floppy-o"></i> Sauvegarder</a>
</div>
<!-- Nav tabs -->
<ul class="nav nav-tabs" role="tablist">
	<li role="presentation" class="active"><a href="#general" role="tab" data-toggle="tab">{{Général}}</a></li>
	<li role="presentation"><a href="#eqLogic" role="tab" data-toggle="tab">{{Plugins/Equipements}}</a></li>
	<li role="presentation"><a href="#scenario" role="tab" data-toggle="tab">{{Scénarios}}</a></li>
</ul>

<!-- Tab panes -->
<div class="tab-content">
	<div role="tabpanel" class="tab-pane active" id="general">
		<a class="btn btn-default toggleRight" data-state="1" data-type='view' style="margin-top : 5px;margin-bottom: 5px;"><i class="fa fa-check-circle-o"></i> {{Inverser}}</a>
		<table class="table table-bordered table-condensed tablesorter" >
			<thead>
				<tr>
					<td style="width: 150px;">{{Droits}}</td>
					<td style="width: 250px;">{{Nom}}</td>
					<td>{{Description}}</td>
				</tr>
			</thead>
			<tbody>
				<?php
foreach ($pages as $kpage => $page) {
	echo '<tr>';
	echo '<td>';
	foreach ($page as $kright => $right) {
		if ($kright != 'title' && $kright != 'title') {
			echo '<span class="rights" style="margin-right:20px;">';
			echo '<input class="rightsAttr" data-l1key="id" style="display:none;" />';
			echo '<input class="rightsAttr" data-l1key="user_id" style="display:none;" />';
			echo '<input class="rightsAttr" data-l1key="entity" style="display:none;" value="' . $kpage . $kright . '" />';
			echo '<input type="checkbox" class="rightsAttr" data-type="' . $kright . '" data-l1key="right" checked />' . $right['title'];
			echo '</span>';
		}
	}
	echo '</td>';
	echo '<td>';
	echo $page['title'];
	echo '</td>';
	echo '<td>';
	echo '</td>';
	echo '</tr>';
}
?>
			</tbody>
		</table>
	</div>
	<div role="tabpanel" class="tab-pane" id="eqLogic">
		<a class="btn btn-default toggleRight" data-state="1" data-type='view' style="margin-top : 5px;margin-bottom: 5px;"><i class="fa fa-check-circle-o"></i> {{Inverser voir}}</a>
		<a class="btn btn-default toggleRight" data-state="1" data-type='action' style="margin-top : 5px;margin-bottom: 5px;"><i class="fa fa-check-circle-o"></i> {{Inverser action}}</a>
		<table class="table table-bordered table-condensed tablesorter" >
			<thead>
				<tr>
					<td style="width: 150px;">{{Droits}}</td>
					<td>{{Nom}}</td>
				</tr>
			</thead>
			<tbody>
				<?php
foreach (eqLogic::all() as $eqLogic) {
	echo '<tr>';
	echo '<td>';
	foreach (array( /* 'edit' => 'Editer', */'view' => 'Voir', 'action' => 'Action') as $kright => $right) {
		echo '<span class="rights" style="margin-right:20px;">';
		echo '<input class="rightsAttr" data-l1key="id" style="display:none;" />';
		echo '<input class="rightsAttr" data-l1key="user_id" style="display:none;" />';
		echo '<input class="rightsAttr" data-l1key="entity" style="display:none;" value="eqLogic' . $eqLogic->getId() . $kright . '" />';
		echo '<input type="checkbox" class="rightsAttr" data-l1key="right" data-type="' . $kright . '" checked />' . $right;
		echo '</span>';
	}
	echo '</td>';
	echo '<td>';
	echo $eqLogic->getHumanName();
	echo '</td>';
	echo '</tr>';
}
?>
			</tbody>
		</table>
	</div>
	<div role="tabpanel" class="tab-pane" id="scenario">
		<a class="btn btn-default toggleRight" data-state="1" data-type='view' style="margin-top : 5px;margin-bottom: 5px;"><i class="fa fa-check-circle-o"></i> {{Inverser voir}}</a>
		<a class="btn btn-default toggleRight" data-state="1" data-type='edit' style="margin-top : 5px;margin-bottom: 5px;"><i class="fa fa-check-circle-o"></i> {{Inverser editer}}</a>
		<a class="btn btn-default toggleRight" data-state="1" data-type='action' style="margin-top : 5px;margin-bottom: 5px;"><i class="fa fa-check-circle-o"></i> {{Inverser action}}</a>
		<table class="table table-bordered table-condensed tablesorter" >
			<thead>
				<tr>
					<td style="width: 150px;">{{Droits}}</td>
					<td>{{Nom}}</td>
				</tr>
			</thead>
			<tbody>
				<?php
foreach (scenario::all() as $scenario) {
	echo '<tr>';
	echo '<td>';
	foreach (array('view' => 'Voir', 'edit' => 'Editer', 'action' => 'Action') as $kright => $right) {
		echo '<span class="rights" style="margin-right:20px;">';
		echo '<input class="rightsAttr" data-l1key="id" style="display:none;" />';
		echo '<input class="rightsAttr" data-l1key="user_id" style="display:none;" />';
		echo '<input class="rightsAttr" data-l1key="entity" style="display:none;" value="scenario' . $scenario->getId() . $kright . '" />';
		echo '<input type="checkbox" class="rightsAttr" data-l1key="right" data-type="' . $kright . '" checked />' . $right;
		echo '</span>';
	}
	echo '</td>';
	echo '<td>';
	echo $scenario->getHumanName();
	echo '</td>';
	echo '</tr>';
}
?>
			</tbody>
		</table>
	</div>
</div>

<?php include_file("desktop", "rights", "js");?>
