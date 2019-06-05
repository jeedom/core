<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<div id="div_alertExpressionTest"></div>



<form class="form-horizontal" onsubmit="return false;">
	<div class="input-group input-group-sm" style="width: 100%">
		<span class="input-group-addon roundedLeft" style="width: 100px"><i class="fas fa-random"></i>  {{Test}}</span>
		<input class="form-control input-sm" id="in_testExpression">
		<span class="input-group-btn">
			<a class="btn btn-default btn-sm cursor tooltips" id="bt_searchInfoCmd" title="{{Rechercher une commande}}"><i class="fas fa-list-alt"></i></a><a class="btn btn-default btn-sm cursor tooltips"  id="bt_searchScenario" title="{{Rechercher un scenario}}"><i class="fas fa-history"></i></a><a class="btn btn-default btn-sm cursor tooltips"  id="bt_searchEqLogic" title="{{Rechercher un équipement}}"><i class="fas fa-cube"></i></a><a class="btn btn-sm btn-default roundedRight" id="bt_executeExpressionOk"><i class="fas fa-bolt"></i> {{Exécuter}}</a>
		</span>
	</div>
</form>
</br>
<legend><i class="fas fa-sign-in-alt"></i> {{Résultat}}</legend>
<div id="div_expressionTestResult"></div>
<legend><i class="fas fa-history"></i> {{Historique}}</legend>
<ul id="ul_expressionHistory"></ul>
<script>



if ($('body').attr('data-page') == 'scenario' && $('#div_editScenario').is(':visible')) {
	$('.subElementIF .expressions input[data-l1key="expression"]').each(function() {
		expression = $(this).val().replace(/"/g, '\'')
		newLi = '<li class="cursor list-group-item list-group-item-success bt_expressionHistory" data-command="'+ expression +'"><a>' + expression + '</a></li>'
		$('#ul_expressionHistory').append(newLi)
	})
}

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

$('#ul_expressionHistory').off('click','.bt_expressionHistory').on('click','.bt_expressionHistory',function(){
	$('#in_testExpression').value($(this).attr('data-command'));
	$('#bt_executeExpressionOk').trigger('click');
});

$('#bt_executeExpressionOk').on('click',function(){
	if($('#in_testExpression').value() == ''){
		$('#div_alertExpressionTest').showAlert({message: '{{L\'expression de test ne peut être vide}}', level: 'danger'});
		return;
	}
	var expression = $('#in_testExpression').value();
	if($('.bt_expressionHistory[data-command="'+expression.replace(/"/g, '\\"')+'"]').html() == undefined){
		$('#ul_expressionHistory').prepend('<li class="cursor list-group-item list-group-item-success bt_expressionHistory"  data-command="'+expression.replace(/"/g, '\\"')+'"><a>'+expression+'</a></li>');
	}
	jeedom.scenario.testExpression({
		expression: expression,
		error: function (error) {
			$('#div_alertExpressionTest').showAlert({message: error.message, level: 'danger'});
		},
		success: function (data) {
			$('#div_expressionTestResult').empty();
			var html = '<ul><div class="alert alert-info">';
			if(data.correct == 'nok'){
				html += '<strong>{{Attention : il doit y avoir un souci, car le résultat est le même que l\'expression}}</strong><br\>';
			}
			html += '{{Je vais évaluer : }} <strong>'+data.evaluate+'</strong><br/>';
			html += '{{Résultat : }} <strong>'+data.result+'</strong>';
			html += '</div></ul>';
			$('#div_expressionTestResult').append(html);
		}
	});
});
</script>
