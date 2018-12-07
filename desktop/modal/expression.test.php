<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<div id="div_alertExpressionTest"></div>



<form class="form-horizontal" onsubmit="return false;">
	<div class="input-group input-group-sm" style="width: 100%">
		<span class="input-group-addon" style="width: 100px"><i class="fa fa-random"></i>  {{Test}}</span>
		<input class="form-control" id="in_testExpression">
		<span class="input-group-btn">
			<a type="button" class="btn btn-default cursor tooltips" id="bt_searchInfoCmd" title="{{Rechercher une commande}}"><i class="fa fa-list-alt"></i></a>
			<a type="button" class="btn btn-default cursor tooltips"  id="bt_searchScenario" title="{{Rechercher un scenario}}"><i class="fa fa-history"></i></a>
			<a type="button" class="btn btn-default cursor tooltips"  id="bt_searchEqLogic" title="{{Rechercher d'un équipement}}"><i class="fa fa-cube"></i></a>
			<a class="btn btn-default" id="bt_executeExpressionOk"><i class="fa fa-bolt"></i> {{Exécuter}}</a>
		</span>
	</div>
</form>
</br>
<legend><i class="fa fa-sign-in"></i>  {{Résultat}}</legend>
<div id="div_expressionTestResult"></div>

<script>

	$('#in_testExpression').keypress(function(e) {
		if(e.which == 13) {
			$('#bt_executeExpressionOk').trigger('click');
		}
	});

	$('#bt_searchInfoCmd').on('click', function() {
		var el = $(this);
		jeedom.cmd.getSelectModal({cmd: {type: 'info'}}, function(result) {
			$('#in_testExpression').atCaret('insert', result.human);
		});
	});
	
	$('#bt_searchScenario').on('click', function() {
		var el = $(this);
		jeedom.scenario.getSelectModal({}, function(result) {
			$('#in_testExpression').atCaret('insert', result.human);
		});
	});

	$('#bt_searchEqLogic').on('click', function() {
		var el = $(this);
		jeedom.eqLogic.getSelectModal({}, function(result) {
			$('#in_testExpression').atCaret('insert', result.human);
		});
	});

	$('#bt_executeExpressionOk').on('click',function(){
		if($('#in_testExpression').value() == ''){
			$('#div_alertExpressionTest').showAlert({message: '{{L\'expression de test ne peut être vide}}', level: 'danger'});
			return;
		}
		jeedom.scenario.testExpression({
			expression: $('#in_testExpression').value(),
			error: function (error) {
				$('#div_alertExpressionTest').showAlert({message: error.message, level: 'danger'});
			},
			success: function (data) {
				$('#div_expressionTestResult').empty();
				var html = '<div class="alert alert-info">';
				if(data.correct == 'nok'){
					html += '<strong>{{Attention : il doit y avoir un souci, car le résultat est le même que l\'expression}}</strong><br\>';
				}
				html += '{{Je vais évaluer : }} <strong>'+data.evaluate+'</strong><br/>';
				html += '{{Résultat : }} <strong>'+data.result+'</strong>';
				html += '</div>';
				$('#div_expressionTestResult').append(html);
			}
		});
	});
</script>
