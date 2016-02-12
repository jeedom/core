<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

?>
<div id="div_alertInteractTest"></div>
<form class="form-horizontal" onsubmit="return false;">
	<div class="input-group input-group-sm" style="width: 100%">
		<span class="input-group-addon" style="width: 100px">{{Demande}}</span>
		<input class="form-control" id="in_testInteractQuery">
		<span class="input-group-btn">
			<a class="btn btn-default" id="bt_executeInteractOk"><i class="fa fa-bolt"></i> {{Exécuter}}</a>
		</span>
	</div>
</form>

<legend>{{Résultat}}</legend>
<div id="div_interactTestResult"></div>

<script>

	$('#in_testInteractQuery').keypress(function(e) {
		if(e.which == 13) {
			$('#bt_executeInteractOk').trigger('click');
		}
	});

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
</script>