<?php
if (!isConnect()) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
if (config::byKey('market::address') == '') {
	throw new Exception('{{Aucune adresse pour le market n\'est renseignée}}');
}
if (config::byKey('market::apikey') == '' && config::byKey('market::username') == '') {
	throw new Exception('{{Aucun compte market n\'est renseigné. Veuillez vous enregistrer sur le market, puis renseignez vos identifiants dans}} ' . config::byKey('product_name') . ' {{avant d\'ouvrir un ticket}}');
}
?>
<div id='div_alertReportBug'></div>
<form class="form-horizontal" role="form" id="form_reportBug">
	<div class="panel panel-success">
		<div class="panel-heading"><h3 class="panel-title"><i class="fas fa-info"></i> {{Etape 1 : Information sur les tickets}}</h3></div>
		<div class="panel-body">
			{{Merci de vérifier avant toute ouverture de ticket :}}<br/>
			{{- que la question n'a pas déjà été posée sur le <a href='https://jeedom.com/forum'>forum</a>}}<br/>
			{{- que la catégorie est bien sélectionnée pour que votre ticket soit traité dans les plus courts délais}}<br/>
			{{- que la réponse n'est pas déjà dans la <a href='https://jeedom.github.io/documentation'>documentation</a>}}
		</div>
	</div>
	<div class="panel panel-danger">
		<div class="panel-heading"><h3 class="panel-title"><i class="fas fa-info"></i> {{Etape 2 : Choix du type de demande}}</h3></div>
		<div class="panel-body">
			<strong>{{Assistance technique}}</strong> : {{Rédigez votre question à l'attention de notre service Technique qui y répondra dans les meilleurs délais.}}<br/><br/>
			<strong>{{Rapport}}</strong> : {{Vous pouvez déclarer un bug qui sera publié sur notre Bug Tracker public (<strong>ATTENTION</strong> votre message sera public, il pourra être supprimé s'il ne s'agit pas d'un bug,  vous ne recevrez pas d'assistance technique suite à cette déclaration)}}<br/><br/>
			<strong>{{Demande d'amélioration}}</strong> : {{Vous pouvez envoyer des propositions d'amélioration qui seront publiées sur notre page publique dédiée et qui pourront être intégrées dans notre feuille de route.}}<br/><br/>
			<center>
				<a href="https://community.jeedom.com/tags/bug" target="_blank">{{Voir les bugs}}</a><br/>
				<a href="https://community.jeedom.com/tags/amelioration" target="_blank">{{Voir les propositions d'amélioration}}</a>
			</center>
		</div>
	</div>
	
	<div class="panel panel-primary">
		<div class="panel-heading"><h3 class="panel-title"><i class="fas fa-cogs"></i> {{Etape 3 : Catégorie et type de la demande}}</h3></div>
		<div class="panel-body">
			<div class="form-group">
				<label class="col-sm-2 control-label">{{Type}}</label>
				<div class="col-sm-4">
					<select class="form-control ticketAttr" data-l1key="type">
						<option value=''>{{Aucun}}</option>
						<option value='support'>{{Assistance technique}}</option>
						<option value='Bug'>{{Rapport}}</option>
						<option value='Amélioration'>{{Demande d'amélioration}}</option>
					</select>
				</div>
				<label class="col-sm-2 control-label">{{Catégorie}}</label>
				<div class="col-sm-4">
					<select class="form-control ticketAttr" data-l1key="category">
						<option value=''>{{Aucune}}</option>
						<option data-issue="" value="core" data-pagehelp="doc/<?php echo config::byKey('language', 'core', 'fr_FR'); ?>/depannage.html">{{Général}}</option>
						<option data-issue="" value="core" data-pagehelp="doc/<?php echo config::byKey('language', 'core', 'fr_FR'); ?>/scenario.html">{{Scénario}}</option>
						<?php
						foreach (plugin::listPlugin(true) as $plugin) {
							echo '<option data-issue="' . $plugin->getIssue() . '" value="plugin::' . $plugin->getId() . '" data-pagehelp="' . $plugin->getDocumentation() . '">Plugin ' . $plugin->getName() . '</option>';
						}
						?>
					</select>
				</div>
			</div>
		</div>
	</div>
	<div class="panel panel-primary" id="div_reportModalSendAction" style="display:none;">
		<div class="panel-heading"><h3 class="panel-title"><i class="fas fa-pencil-alt"></i> {{Etape 4 : Demande de support}}</h3></div>
		<div class="panel-body">
			<div class="form-group">
				<label class="col-sm-2 control-label">{{Titre}}</label>
				<div class="col-sm-7">
					<input class="form-control ticketAttr" data-l1key="title"/>
				</div>
			</div>
			
			<div class="form-group">
				<label class="col-sm-2 control-label">{{Message}}</label>
				<div class="col-sm-9">
					<textarea class="form-control messageAttr input-sm" data-l1key="message" rows="8" ></textarea>
					<input class="form-control ticketAttr" data-l1key="options" data-l2key="page" style="display: none;"/>
				</div>
			</div>
			<div class="form-actions" style="height: 20px;">
				<label style="margin-left: 140px;"><input type="checkbox" class="ticketAttr" data-l1key="openSupport" checked="checked" /> {{Ouvrir un accès au support}}</label>
				<a class="btn btn-success pull-right" id="bt_sendBugReport" style="color:white;"><i class="far fa-check-circle"></i> {{Envoyer}}</a>
			</div>
		</div>
	</div>
	
	<div class="panel panel-primary" id="div_reportModalPrivateIssue" style="display:none;">
		<div class="panel-heading"><h3 class="panel-title"><i class="fas fa-pencil-alt"></i> {{Etape 4 : Demande de support}}</h3></div>
		<div class="panel-body">
			<div class="form-group">
				<label class="col-sm-5 control-label">{{Ce plugin utilise un gestionnaire de demande de support}}</label>
				<div class="col-sm-2">
					<a class="btn btn-success" id="bt_reportBugIssueUrl" href="#" target="_blank" style="color:white;"><i class="far fa-check-circle"></i> {{Accéder}}</a>
				</div>
			</div>
		</div>
	</div>
</form>

<script>
$('.ticketAttr[data-l1key=options][data-l2key=page]').value(location.href);
if(getUrlVars('m') !== false){
	$('.ticketAttr[data-l1key=category]').value('plugin::'+getUrlVars('m'));
}

$('#bt_sendBugReport').on('click', function () {
	var ticket = $('#form_reportBug').getValues('.ticketAttr')[0];
	ticket.messages = $('#form_reportBug').getValues('.messageAttr');
	$.ajax({
		type: "POST",
		url: "core/ajax/repo.ajax.php",
		data: {
			action: "sendReportBug",
			repo : 'market',
			ticket: json_encode(ticket),
		},
		dataType: 'json',
		error: function (request, status, error) {
			handleAjaxError(request, status, error, $('#div_alertReportBug'));
		},
		success: function (data) {
			if (data.state != 'ok') {
				$('#div_alertReportBug').showAlert({message: data.result, level: 'danger'});
				return;
			}
			$('#form_reportBug').hide();
			$('#bt_sendBugReport').hide();
			$('#md_reportBug').animate({ scrollTop: 0 }, "slow");
			if(data.result != '' && data.result != null && data.result != 'ok'){
				$('#div_alertReportBug').showAlert({message: '{{Vous venez de déclarer un bug qui sera publié sur notre Bug Tracker public.<br/>Vous pouvez le suivre}} <a target="_blank" href="'+data.result+'">ici</a><br/><br/><strong>ATTENTION</strong> votre message sera public, il pourra être supprimé s\'il ne s\'agit pas d\'un bug, vous ne recevrez pas d\'assistance technique suite à cette déclaration)', level: 'success'});
			}else{
				$('#div_alertReportBug').showAlert({message: '{{Votre ticket a bien été ouvert. Un mail va vous être envoyé}}', level: 'success'});
			}
		}
	});
});

$('.ticketAttr[data-l1key=type],.ticketAttr[data-l1key=category]').on('change',function(){
	if($('.ticketAttr[data-l1key=type]').value() == 'Bug' || $('.ticketAttr[data-l1key=type]').value() == 'Amélioration'){
		$('#div_alertReportBug').showAlert({message: '{{ATTENTION cette demande sera public, il ne faut SURTOUT PAS mettre d\'informationd personnelles (mail, compte market, clef api...)}}', level: 'warning'});
	}else{
		$.hideAlert();
	}
	$('#div_reportModalPrivateIssue').hide();
	if($('.ticketAttr[data-l1key=type]').value() == '' || $('.ticketAttr[data-l1key=category]').value() == ''){
		$('#div_reportModalSendAction').hide();
	}else{
		if($('.ticketAttr[data-l1key=category] option:selected').attr('data-issue') == ''){
			$('#div_reportModalSendAction').show();
		}else{
			$('#div_reportModalPrivateIssue').show();
			$('#bt_reportBugIssueUrl').attr('href',$('.ticketAttr[data-l1key=category] option:selected').attr('data-issue'));
		}
	}
});
</script>
