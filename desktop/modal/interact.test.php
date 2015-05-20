<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

?>
<div id="div_alertInteractTest"></div>
<form class="form-horizontal">
	<div class="form-group">
		<label class="col-sm-2 control-label">Demande</label>
		<div class="col-sm-8">
			<input type="email" class="form-control" id="in_testInteractQuery">
		</div>
		<div class="col-sm-1">
			<a class="btn btn-success" id="bt_simulateInteractOk"><i class="fa fa-check"></i> {{Simuler}}</a>
		</div>
		<div class="col-sm-1">
			<a class="btn btn-warning" id="bt_executeInteractOk"><i class="fa fa-bolt"></i> {{Exécuter}}</a>
		</div>
	</div>
</form>

<legend>{{Résultat}}</legend>
<div id="div_interactTestResult"></div>

<script>
	$('#bt_executeInteractOk').on('click',function(){
		if($('#in_testInteractQuery').value() == ''){
			$('#div_alertInteractTest').showAlert({message: '{{La demande ne peut être vide}}', level: 'danger'});
			return;
		}
		jeedom.interact.execute({
			query: $('#in_testInteractQuery').value(),
			error: function (error) {
				$('#div_alertInteractTest').showAlert({message: error.message, level: 'danger'});
			},
			success: function (data) {
				$('#div_interactTestResult').empty();
				$('#div_interactTestResult').append('<div class="alert alert-info"><i class="fa fa-comment"></i> '+data+'</div>');
			}
		});
	});



	$('#bt_simulateInteractOk').on('click',function(){
		if($('#in_testInteractQuery').value() == ''){
			$('#div_alertInteractTest').showAlert({message: '{{La demande ne peut être vide}}', level: 'danger'});
			return;
		}
		jeedom.interact.simulate({
			query: $('#in_testInteractQuery').value(),
			error: function (error) {
				$('#div_alertInteractTest').showAlert({message: error.message, level: 'danger'});
			},
			success: function (data) {
				$('#div_interactTestResult').empty();
				if(data.interactQuery == null){
					$('#div_interactTestResult').append('<div class="alert alert-warning">{{Aucune correspondance trouvée}}</div>');
					return;
				}
				$('#div_interactTestResult').append('<div class="alert alert-info">{{J\'ai reconnu : }}<strong>'+data.interactQuery.query+'</strong></div>');
				if(data.interactQuery.link_type == 'cmd'){
					$('#div_interactTestResult').append('<div class="alert alert-warning">{{Je dois exécuter la/les commande(s) : }}<strong>'+data.cmd+'</strong></div>');
				}
				if(data.interactQuery.link_type == 'scenario'){
					$('#div_interactTestResult').append('<div class="alert alert-warning">{{Je dois}} <strong>'+data.action+'</strong> {{le scénario : }}<strong>'+data.scenario+'</strong></div>');
				}
				if(data.reply != ''){
					$('#div_interactTestResult').append('<div class="alert alert-success">{{Je vais répondre : }} <pre>'+data.reply+'</pre></div>');
				}
			}
		});
});
</script>