<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

?>
<div id="div_alertInteractTest"></div>
<form class="form-horizontal">
	<div class="form-group">
		<label class="col-sm-2 control-label">Demande</label>
		<div class="col-sm-9">
			<input type="email" class="form-control" id="in_testInteractQuery">
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
</script>