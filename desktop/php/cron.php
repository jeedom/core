<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>

<br/>
<ul class="nav nav-tabs" role="tablist">
	<li role="presentation" class="active"><a href="#cron" role="tab" data-toggle="tab"><i class="fas fa-clock-o"></i> {{Cron}}</a></li>
	<li role="presentation"><a href="#listener" role="tab" data-toggle="tab"><i class="fas fa-assistive-listening-systems"></i> {{Listener}}</a></li>
	<li role="presentation"><a href="#deamon" role="tab" data-toggle="tab"><i class="fas fa-bug" ></i> {{Démon}}</a></li>
</ul>


<div class="tab-content">
	<div role="tabpanel" class="tab-pane active" id="cron">
		<a class="btn btn-success pull-right" id="bt_save" style="margin-top: 5px;"><i class="far fa-check-circle"></i> {{Enregistrer}}</a>
		<a class="btn btn-default pull-right" id="bt_addCron" style="margin-top: 5px;"><i class="fas fa-plus-circle"></i> {{Ajouter}}</a>
		<a class="btn btn-default pull-right" id="bt_refreshCron" style="margin-top: 5px;"><i class="fas fa-refresh"></i> {{Rafraîchir}}</a>
		<?php
		if (config::byKey('enableCron') == 0) {
			echo '<a class="btn btn-success pull-right" id="bt_changeCronState" data-state="1" style="margin-top: 5px;"><i class="fas fa-check"></i> {{Activer le système cron}}</a>';
		} else {
			echo '<a class="btn btn-danger pull-right" id="bt_changeCronState" data-state="0" style="margin-top: 5px;"><i class="fas fa-times"></i> {{Désactiver le système cron}}</a>';
		}
		?>
		<br/><br/><br/>
		<table id="table_cron" class="table table-bordered table-condensed" >
			<thead>
				<tr>
					<th class="id" style="width: 40px;">#</th>
					<th class="" style="width: 80px;" data-sorter="false" data-filter="false"></th>
					<th class="enable" style="width: 80px;">{{Actif}}</th>
					<th class="pid" style="width: 100px;">{{PID}}</th>
					<th class="deamons" style="width: 120px;">{{Démon}}</th>
					<th class="once" style="width: 80px;">{{Unique}}</th>
					<th class="class" style="width: 120px;">{{Classe}}</th>
					<th class="function" style="width: 120px;">{{Fonction}}</th>
					<th class="schedule" style="width: 170px;"><i class="fas fa-question-circle cursor bt_pageHelp" data-name='cronSyntaxe' style="position: relative; width: 10px;"></i> {{Programmation}}</th>
					<th class="timeout" style="width: 150px;">{{Timeout (min)}}</th>
					<th class="lastRun" style="width: 200px;">{{Dernier lancement}}</th>
					<th class="runtime" style="width: 200px;">{{Dernière durée}}</th>
					<th class="state" style="width: 80px;">{{Statut}}</th>
					<th class="action" style="width: 50px;" data-sorter="false" data-filter="false"></th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
	<div role="tabpanel" class="tab-pane" id="listener">
		<br/>
		<table id="table_listener" class="table table-bordered table-condensed" >
			<thead>
				<tr>
					<th style="width: 40px;">#</th>
					<th style="width: 80px;" data-sorter="false" data-filter="false"></th>
					<th>{{Event}}</th>
					<th style="width: 120px;">{{Classe}}</th>
					<th style="width: 120px;">{{Fonction}}</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
	
	<div role="tabpanel" class="tab-pane" id="deamon">
		<a class="btn btn-default pull-right" id="bt_refreshDeamon" style="margin-top: 5px;"><i class="fas fa-refresh"></i> {{Rafraîchir}}</a>
		<br/><br/><br/>
		<table id="table_deamon" class="table table-bordered table-condensed" >
			<thead>
				<tr>
					<th>{{Nom}}</th>
					<th>{{Status}}</th>
					<th>{{Date lancement}}</th>
					<th>{{Action}}</th>
				</tr>
			</thead>
			<tbody></tbody>
		</table>
	</div>
</div>
<?php include_file('desktop', 'cron', 'js');?>
