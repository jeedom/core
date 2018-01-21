<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$plan3d = plan3d::byName3dHeaderId(init('name'), init('plan3dHeader_id'));
if (!is_object($plan3d)) {
	$plan3d = new plan3d();
	$plan3d->setName(init('name'));
	$plan3d->setPlan3dHeader_id(init('plan3dHeader_id'));
	$plan3d->save();
}
$link = $plan3d->getLink();
sendVarToJS('id', $plan3d->getId());
?>
<div id="div_alertPlan3dConfigure"></div>

<form class="form-horizontal">
	<fieldset id="fd_plan3dConfigure">
		<legend>{{Général}}
			<a class='btn btn-danger btn-xs pull-right cursor' style="color: white;" id='bt_removeConfigurePlan3d'><i class="fa fa-times"></i> {{Supprimer}}</a>
			<a class='btn btn-success btn-xs pull-right cursor' style="color: white;" id='bt_saveConfigurePlan3d'><i class="fa fa-check"></i> {{Sauvegarder}}</a>
		</legend>
		<input type="text"  class="plan3dAttr form-control" data-l1key="id" style="display: none;"/>
		<div class="form-group">
			<label class="col-lg-4 control-label">{{Nom}}</label>
			<div class="col-lg-3">
				<input type="text" class="plan3dAttr form-control" data-l1key="name" disabled="disabled" />
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-4 control-label">{{Type de lien}}</label>
			<div class="col-lg-3">
				<select class="plan3dAttr form-control" data-l1key="link_type" >
					<option value="eqLogic">{{Equipement}}</option>
				</select>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-4 control-label">{{Lien}}</label>
			<div class="col-lg-3">
				<input type="text" class="plan3dAttr form-control" data-l1key="link_id" />
			</div>
			<div class="col-lg-2">
				<a class="btn btn-default" id="bt_selEqLogic" title="{{Rechercher d\'un équipement}}"><i class="fa fa-list-alt"></i></a>
			</div>
		</div>
		<div class="form-group">
			<label class="col-lg-4 control-label">{{Spécificité}}</label>
			<div class="col-lg-3">
				<select class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget">
					<option value="">{{Aucun}}</option>
					<option value="light">{{Lumière}}</option>
					<option value="text">{{Texte}}</option>
					<option value="door">{{Porte/Fenêtre}}</option>
					<option value="conditionalColor">{{Couleur conditionnel}}</option>
				</select>
			</div>
		</div>
		<div class="form-group specificity specificity_light">
			<label class="col-lg-4 control-label">{{Statut}}</label>
			<div class="col-lg-3">
				<input type="text" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="cmd::state"/>
			</div>
			<div class="col-lg-2">
				<a class="btn btn-default" id="bt_selCmd" title="{{Rechercher d\'une commande}}"><i class="fa fa-list-alt"></i></a>
			</div>
		</div>
		<!---*********************************LIGHT************************************** -->
		<div class="form-group specificity specificity_light">
			<label class="col-lg-4 control-label">{{Puissance}}</label>
			<div class="col-lg-3">
				<select class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::light::power">
					<option value="0.5">20 lm (4W)</option>
					<option value="3">180 lm (25W)</option>
					<option value="6" selected="selected">400 lm (40W)</option>
					<option value="10">800 lm (60W)</option>
					<option value="15">1700 lm (100W)</option>
					<option value="45">3500 lm (300W)</option>
					<option value="150">110000 lm (1000W)</option>
				</select>
			</div>
		</div>

		<!---*********************************TEXT************************************** -->
		<div class="form-group specificity specificity_text">
			<label class="col-lg-4 control-label">{{Texte}}</label>
			<div class="col-lg-7">
				<textarea type="text" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::text::text"></textarea>
			</div>
			<div class="col-lg-1">
				<a class="btn btn-default" id="bt_addTextCommand" title="{{Rechercher d\'une commande}}"><i class="fa fa-list-alt"></i></a>
			</div>
		</div>
		<div class="form-group specificity specificity_text">
			<label class="col-lg-4 control-label">{{Taille du texte}}</label>
			<div class="col-lg-3">
				<input type="number" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::text::fontsize"/>
			</div>
		</div>
		<div class="form-group specificity specificity_text">
			<label class="col-lg-4 control-label">{{Couleur du texte}}</label>
			<div class="col-lg-3">
				<input type="color" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::text::textcolor"/>
			</div>
			<label class="col-lg-2 control-label">{{Transparence du texte}}</label>
			<div class="col-lg-1">
				<input type="number" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::text::texttransparency"/>
			</div>
		</div>
		<div class="form-group specificity specificity_text">
			<label class="col-lg-4 control-label">{{Couleur de fond}}</label>
			<div class="col-lg-3">
				<input type="color" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::text::backgroundcolor"/>
			</div>
			<label class="col-lg-2 control-label">{{Transparence fond}}</label>
			<div class="col-lg-1">
				<input type="number" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::text::backgroundtransparency"/>
			</div>
		</div>
		<div class="form-group specificity specificity_text">
			<label class="col-lg-4 control-label">{{Couleur de la bordure}}</label>
			<div class="col-lg-3">
				<input type="color" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::text::bordercolor"/>
			</div>
			<label class="col-lg-2 control-label">{{Transparence bordure}}</label>
			<div class="col-lg-1">
				<input type="number" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::text::bordertransparency"/>
			</div>
		</div>
		<div class="form-group specificity specificity_text">
			<label class="col-lg-4 control-label">{{Espacement au dessus de l'objet}}</label>
			<div class="col-lg-3">
				<input type="number" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::text::space::z"/>
			</div>
		</div>

		<!---*********************************DOOR************************************** -->
		<div class="form-group specificity specificity_door">
			<label class="col-lg-4 control-label">{{Fênetre/Porte}}</label>
			<div class="col-lg-3">
				<input type="text" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::door::window"/>
			</div>
			<div class="col-lg-2">
				<a class="btn btn-default" id="bt_selWindow" title="{{Rechercher d\'une commande}}"><i class="fa fa-list-alt"></i></a>
			</div>
		</div>
		<div class="form-group specificity specificity_door">
			<label class="col-lg-4 control-label">{{Couleur fênetre/porte ouverte}}</label>
			<div class="col-lg-3">
				<input type="color" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::door::windowopen"/>
			</div>
		</div>
		<div class="form-group specificity specificity_door">
			<label class="col-lg-4 control-label">{{Couleur fênetre/porte fermée}}</label>
			<div class="col-lg-3">
				<input type="color" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::door::windowclose"/>
			</div>
		</div>
		<div class="form-group specificity specificity_door">
			<label class="col-lg-4 control-label">{{Volet}}</label>
			<div class="col-lg-3">
				<input type="text" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::door::shutter"/>
			</div>
			<div class="col-lg-2">
				<a class="btn btn-default" id="bt_selShutter" title="{{Rechercher d\'une commande}}"><i class="fa fa-list-alt"></i></a>
			</div>
		</div>
		<div class="form-group specificity specificity_door">
			<label class="col-lg-4 control-label">{{Couleur volet fermé}}</label>
			<div class="col-lg-3">
				<input type="color" class="plan3dAttr form-control" data-l1key="configuration" data-l2key="3d::widget::door::shutterclose"/>
			</div>
		</div>

		<!---*********************************conditionalColor************************************** -->
		<div class="specificity specificity_conditionalColor">
			<legend>{{Condition}} <a class="btn btn-xs btn-success pull-right" id="bt_addCondition"><i class="fa fa-plus"></i> {{Ajouter}}</a></legend>
			<div id="div_conditionColor"></div>
		</div>
		<script>
			$('#bt_addCondition').on('click',function(){
				addConditionalColor({})
			});

			$('#fd_plan3dConfigure').off('click','.bt_removeConditionalColor').on('click','.bt_removeConditionalColor',  function (event) {
				$(this).closest('.conditionalColor').remove();
			});

			$('#fd_plan3dConfigure').off('click','.listCmdInfoConditionalColor').on('click','.listCmdInfoConditionalColor',  function (event) {
				var el = $(this).closest('.conditionalColor').find('.conditionalColorAttr[data-l1key=cmd]');
				jeedom.cmd.getSelectModal({cmd:{type:'info'}}, function (result) {
					el.atCaret('insert',result.human);
				});
			});

			function addConditionalColor(_conditionalColor) {
				if (!isset(_conditionalColor)) {
					_conditionalColor = {};
				}
				var div = '<div class="conditionalColor">';
				div += '<div class="form-group">';
				div += '<label class="col-sm-1 control-label">{{Condition}}</label>';
				div += '<div class="col-sm-9">';
				div += '<div class="input-group">';
				div += '<span class="input-group-btn">';
				div += '<a class="btn btn-default bt_removeConditionalColor btn-sm"><i class="fa fa-minus-circle"></i></a>';
				div += '</span>';
				div += '<input class="conditionalColorAttr form-control input-sm" data-l1key="cmd" />';
				div += '<span class="input-group-btn">';
				div += '<a class="btn btn-sm listCmdInfoConditionalColor btn-default"><i class="fa fa-list-alt"></i></a>';
				div += '</span>';
				div += '</div>';
				div += '</div>';
				div += '<label class="col-sm-1 control-label">{{Couleur}}</label>';
				div += '<div class="col-sm-1">';
				div += '<input type="color" class="conditionalColorAttr form-control input-sm" data-l1key="color" />';
				div += '</div>';
				div += '</div>';
				$('#div_conditionColor').append(div);
				$('#div_conditionColor .conditionalColor:last').setValues(_conditionalColor, '.conditionalColorAttr');
			}

			$("#div_conditionColor").sortable({axis: "y", cursor: "move", items: ".conditionalColor", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});
		</script>
	</fieldset>
</form>

<script>

	$('.plan3dAttr[data-l1key=configuration][data-l2key="3d::widget"]').on('change',function(){
		$('.specificity').hide();
		$('.specificity.specificity_'+$(this).value()).show();
	});

	$('.plan3dAttr[data-l1key=configuration][data-l2key="3d::widget"]').trigger('change');

	$('#fd_plan3dConfigure').off('click','#bt_selEqLogic').on('click','#bt_selEqLogic',  function (event) {
		jeedom.eqLogic.getSelectModal({}, function (result) {
			$('.plan3dAttr[data-l1key=link_id]').value(result.human);
		});
	});

	$('#fd_plan3dConfigure').off('click','#bt_selCmd').on('click','#bt_selCmd',  function (event) {
		jeedom.cmd.getSelectModal({cmd:{type:'info'}}, function (result) {
			$('.plan3dAttr[data-l1key=configuration][data-l2key="cmd::state"]').value(result.human);
		});
	});

	$('#fd_plan3dConfigure').off('click','#bt_selWindow').on('click','#bt_selWindow',  function (event) {
		jeedom.cmd.getSelectModal({cmd:{type:'info'}}, function (result) {
			$('.plan3dAttr[data-l1key=configuration][data-l2key="3d::widget::door::window"]').value(result.human);
		});
	});

	$('#fd_plan3dConfigure').off('click','#bt_selShutter').on('click','#bt_selShutter',  function (event) {
		jeedom.cmd.getSelectModal({cmd:{type:'info'}}, function (result) {
			$('.plan3dAttr[data-l1key=configuration][data-l2key="3d::widget::door::shutter"]').value(result.human);
		});
	});

	$('#fd_plan3dConfigure').off('click','#bt_addTextCommand').on('click','#bt_addTextCommand',  function (event) {
		jeedom.cmd.getSelectModal({cmd:{type:'info'}}, function (result) {
			$('.plan3dAttr[data-l1key=configuration][data-l2key="3d::widget::text::text"]').atCaret('insert',result.human);
		});
	});

	$('#bt_saveConfigurePlan3d').on('click', function () {
		var plan3ds = $('#fd_plan3dConfigure').getValues('.plan3dAttr');
		if(!isset(plan3ds[0].configuration)){
			plan3ds[0].configuration = {};
		}
		plan3ds[0].configuration['3d::widget::conditionalColor::condition'] = $('#div_conditionColor .conditionalColor').getValues('.conditionalColorAttr');
		jeedom.plan3d.save({
			plan3ds: plan3ds,
			error: function (error) {
				$('#div_alertPlan3dConfigure').showAlert({message: error.message, level: 'danger'});
			},
			success: function () {
				$('#fd_plan3dConfigure').closest("div.ui-dialog-content").dialog("close");
				if(typeof refresh3dObject == 'function'){
					refresh3dObject();
				}
			},
		});
	});

	$('#bt_removeConfigurePlan3d').on('click',function(){
		var plan3ds = $('#fd_plan3dConfigure').getValues('.plan3dAttr');
		if(!isset(plan3ds[0].configuration)){
			plan3ds[0].configuration = {};
		}
		jeedom.plan3d.remove({
			id: plan3ds[0].id,
			error: function (error) {
				$('#div_alertPlan3dConfigure').showAlert({message: error.message, level: 'danger'});
			},
			success: function () {
				$('#fd_plan3dConfigure').closest("div.ui-dialog-content").dialog("close");
				if(typeof refresh3dObject == 'function'){
					refresh3dObject();
				}
			},
		});
	});

	if (isset(id) && id != '') {
		$.ajax({
			type: "POST",
			url: "core/ajax/plan3d.ajax.php",
			data: {
				action: "get",
				id: id
			},
			dataType: 'json',
			error: function (request, status, error) {
				handleAjaxError(request, status, error, $('#div_alertPlan3dConfigure'));
			},
			success: function (data) {
				if (data.state != 'ok') {
					$('#div_alertPlan3dConfigure').showAlert({message: data.result, level: 'danger'});
					return;
				}
				$('#fd_plan3dConfigure').setValues(data.result, '.plan3dAttr');
				if (isset(data.result.configuration) && isset(data.result.configuration['3d::widget::conditionalColor::condition'])) {
					for (var i in data.result.configuration['3d::widget::conditionalColor::condition']) {
						addConditionalColor(data.result.configuration['3d::widget::conditionalColor::condition'][i]);
					}
				}
			}
		});
	}
</script>