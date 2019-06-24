<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
if(init('type') == 'plugin'){
	throw new Exception('{{Vous ne pouvez plus partager un plugin directement depuis Jeedom. Il faut le faire maintenant en synchronisation github depuis le market}}');
}
sendVarToJS('market_display_info', array(
	'logicalId' => init('logicalId'),
	'name' => init('name'),
));
sendVarToJS('market_type', init('type'));
try {
	if (init('logicalId') != '' && init('type') != '') {
		$market = repo_market::byLogicalIdAndType(init('logicalId'), init('type'));
	}
} catch (Exception $e) {
	$market = null;
}
if (is_object($market) && !$market->getIsAuthor()) {
	throw new Exception('{{Vous n\'êtes pas l\'auteur du plugin}}');
}

if (init('type') == 'plugin') {
	$plugin = plugin::byId(init('logicalId'));
	if (!is_object($plugin)) {
		throw new Exception('{{Le plugin :}} ' . init('logicalId') . ' {{est introuvable}}');
	}
	$plugin_info = utils::o2a($plugin);
	$plugin_info['logicalId'] = $plugin_info['id'];
	unset($plugin_info['id']);
	sendVarToJS('market_display_info', $plugin_info);
}
?>

<div style="display: none;width : 100%" id="div_alertMarketSend"></div>


<a class="btn btn-success pull-right" style="color : white;" id="bt_sendToMarket"><i class="fa fa-cloud-upload"></i> {{Envoyer}}</a>

<br/><br/>
<form class="form-horizontal" role="form" id="form_sendToMarket">
	<?php if (init('type') == 'plugin') {?>
		<hr/>
		<div class="row">
			<div class="col-lg-3">
				<div class="priceChoose alert alert-success">
					<br/>
					<center><input type="radio" name="rb_price" class="rb_price free" data-value="" checked/> <h4 style="display: inline-block">{{Gratuit}}</h4></center>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="priceChoose">
					<center><input type="radio" name="rb_price" class="rb_price" data-value="2" /> <h4 style="display: inline-block">2 €</h4></center>
					<center>{{Sur ce prix 40 % seront reversés au développeur}}</center>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="priceChoose">
					<center><input type="radio" name="rb_price" class="rb_price" data-value="4" /> <h4 style="display: inline-block">4 €</h4></center>
					<center>{{Sur ce prix 60 % seront reversés au développeur}}</center>
				</div>
			</div>
			<div class="col-lg-3">
				<div class="priceChoose">
					<center><input type="radio" name="rb_price" class="rb_price" data-value="custom" /> <h4 style="display: inline-block">{{Libre}}</h4> <input class="form-control marketAttr input-sm" data-l1key="cost" placeholder="Prix" style="display : inline-block; width : 80px;"> €</center>
					<center>{{Sur ce prix 65 % seront reversés au développeur (doit être supérieur ou égal à 5 €)}}</center>
				</div>
			</div>
		</div>
		<hr/>
	<?php }
	?>
	<div class="alert alert-info">{{N'oubliez pas de rajouter une image à votre création en passant par le market.}}<span id="span_directLinkWidget"></span></div>
	<div class="row">
		<div class="col-lg-6">
			<div class="form-group">
				<label class="col-lg-4 control-label">{{ID}}</label>
				<div class="col-lg-6">
					<input class="form-control marketAttr" data-l1key="id" style="display: none;">
					<input class="form-control marketAttr" data-l1key="logicalId" placeholder="{{ID}}" disabled/>
				</div>
			</div>
			<div class="form-group">
				<label class="col-lg-4 control-label">{{Nom}}</label>
				<div class="col-lg-6">
					<input class="form-control marketAttr" data-l1key="name" placeholder="{{Nom}}" />
				</div>
			</div>
			<div class="form-group">
				<label class="col-lg-4 control-label">{{Type}}</label>
				<div class="col-lg-6">
					<select class="form-control marketAttr" data-l1key="type" disabled>
						<option value="plugin" data-category='plugin'>{{Plugin}}</option>
						<option value="widget" data-category='widget'>{{Widget}}</option>
						<option value="zwave" data-category='input'>{{[Z-Wave] Configuration module}}</option>
						<option value="script" data-category='input'>{{Script}}</option>
						<option value="scenario" data-category='input'>{{Scénario}}</option>
						<option value="camera" data-category='input'>{{[Caméra] Modèle}}</option>
						<option value="SNMP" data-category='input'>{{[SNMP] Configuration}}</option>
						<option value="mySensors" data-category='input'>{{[My Sensors] Noeud}}</option>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-lg-4 control-label">{{Catégorie}}</label>
				<div class="col-lg-6">
					<input class="form-control input category marketAttr" data-l1key="categorie" >
					<select class="form-control widget category" data-l1key="categorie" style="display: none;">
						<?php
						foreach (repo_market::distinctCategorie('widget') as $id => $category) {
							if (trim($category) != '' && is_numeric($id)) {
								echo '<option value="' . $category . '">';
								echo $category;
								echo '</option>';
							}
						}
						?>
					</select>
					<select class="form-control plugin category" data-l1key="categorie" style="display: none;">
						<?php
						global $JEEDOM_INTERNAL_CONFIG;
						foreach ($JEEDOM_INTERNAL_CONFIG['plugin']['category'] as $key => $value) {
							echo '<option value="' . $key . '"';
							echo (is_object($plugin) && $plugin->getCategory() == $key) ? 'selected >' : '>';
							echo $value['name'];
							echo '</option>';
						}
						?>
					</select>
				</div>
			</div>
			<div class="form-group">
				<label class="col-lg-4 control-label">{{Description}}</label>
				<div class="col-lg-6">
					<textarea class="form-control marketAttr" data-l1key="description" placeholder="{{Description}}" style="height: 150px;"></textarea>
				</div>
			</div>
		</div>
		<div class="col-lg-6">
			<div class="form-group">
				<label class="col-lg-2 control-label">{{Video}}</label>
				<div class="col-lg-9">
					<input class="form-control marketAttr" data-l1key="link" data-l2key="video">
				</div>
			</div>
			<div class="form-group">
				<label class="col-lg-2 control-label">{{Forum}}</label>
				<div class="col-lg-9">
					<input class="form-control marketAttr" data-l1key="link" data-l2key="forum">
				</div>
			</div>
			<div class="form-group">
				<label class="col-lg-2 control-label">{{Utilisation}}</label>
				<div class="col-lg-9">
					<textarea class="form-control marketAttr" data-l1key="utilization" placeholder="{{Utilisation}}" style="height: 150px;"></textarea>
				</div>
			</div>
			<div class="form-group">
				<label class="col-lg-2 control-label">{{Langue}}</label>
				<div class="col-lg-9">
					<label class="checkbox-inline">
						<input type="checkbox" class="marketAttr" data-l1key="language" data-l2key="en_US"> {{English}}
					</label>
					<label class="checkbox-inline">
						<input type="checkbox" class="marketAttr" data-l1key="language" data-l2key="de_DE"> {{Deutsch}}
					</label>
					<label class="checkbox-inline">
						<input type="checkbox" class="marketAttr" data-l1key="language" data-l2key="sp_SP"> {{Español}}
					</label>
				</div>
			</div>
			<div class="form-group">
				<label class="col-lg-2 control-label">{{Compatibilité matériel}}</label>
				<div class="col-lg-9">
					<label class="checkbox-inline">
						<input type="checkbox" class="marketAttr" data-l1key="hardwareCompatibility" data-l2key="Jeedomboard"> Jeedomboard (mini+)
					</label>
					<label class="checkbox-inline">
						<input type="checkbox" class="marketAttr" data-l1key="hardwareCompatibility" data-l2key="Smart"> Smart
					</label>
					<label class="checkbox-inline">
						<input type="checkbox" class="marketAttr" data-l1key="hardwareCompatibility" data-l2key="RPI/RPI2"> RPI/RPI2
					</label>
					<label class="checkbox-inline">
						<input type="checkbox" class="marketAttr" data-l1key="hardwareCompatibility" data-l2key="Docker"> Docker
					</label>
					<label class="checkbox-inline">
						<input type="checkbox" class="marketAttr" data-l1key="hardwareCompatibility" data-l2key="DIY"> DIY
					</label>
				</div>
			</div>
		</div>
	</div>
</form>
<?php
if (is_object($market)) {
	sendVarToJS('market_display_info', utils::o2a($market));
}
?>
<script>
$('.marketAttr[data-l1key=type]').on('change', function () {
	$('.category').hide().removeClass('marketAttr');
	$('.category.' + $('.marketAttr[data-l1key=type] option:selected').attr('data-category')).show().addClass('marketAttr');
	if ($(this).value() == 'plugin') {
		$('#div_marketPrice').show();
	} else {
		$('#div_marketPrice').hide();
	}
});

$('.rb_price').on('change', function () {
	$('.priceChoose').removeClass('alert alert-success');
	$(this).closest('.priceChoose').addClass('alert alert-success');
});

$('.priceChoose').on('click', function () {
	$(this).find('.rb_price').prop('checked', true);
	$('.priceChoose').removeClass('alert alert-success');
	$(this).addClass('alert alert-success');
});

$('body').setValues(market_display_info, '.marketAttr');
if(market_display_info.id != ''){
	$('#span_directLinkWidget').value('{{Ou en cliquant }}<a href="https://jeedom.com/market/index.php?v=d&p=addMarket&id='+market_display_info.id+'" target="_blank" >{{ici}}</a>');
}else{
	$('#span_directLinkWidget').value('');
}

if (market_display_info.realcost == '' || market_display_info.realcost == 0) {
	$('.rb_price.free').prop('checked', true);
	$('.marketAttr[data-l1key=cost]').value('');
	$('.rb_price.free').closest('.priceChoose').addClass('alert alert-success');
} else if (market_display_info.realcost == 2) {
	$('.rb_price[data-value=2]').prop('checked', true);
	$('.marketAttr[data-l1key=cost]').value('');
	$('.rb_price[data-value=2]').closest('.priceChoose').addClass('alert alert-success');
	$('.rb_price.free').closest('.priceChoose').removeClass('alert alert-success');
} else if (market_display_info.realcost == 4) {
	$('.rb_price[data-value=4]').prop('checked', true);
	$('.marketAttr[data-l1key=cost]').value('');
	$('.rb_price[data-value=4]').closest('.priceChoose').addClass('alert alert-success');
	$('.rb_price.free').closest('.priceChoose').removeClass('alert alert-success');
	$('.rb_price.free').closest('.priceChoose').removeClass('alert alert-success');
} else {
	$('.rb_price[data-value=custom]').prop('checked', true);
	$('.rb_price[data-value=custom]').closest('.priceChoose').addClass('alert alert-success');
	$('.rb_price.free').closest('.priceChoose').removeClass('alert alert-success');
	$('.marketAttr[data-l1key=cost]').value(market_display_info.realcost);
}

$('.marketAttr[data-l1key=type]').value(market_type);

$('#bt_sendToMarket').on('click', function () {
	var market = $('#form_sendToMarket').getValues('.marketAttr')[0];
	$('.rb_price').each(function () {
		if ($(this).is(":checked") && $(this).attr('data-value') != 'custom') {
			market.cost = parseInt($(this).attr('data-value'));
		}
	});
	
	if(market.id != ''){
		$.ajax({
			type: "POST",
			url: "core/ajax/repo.ajax.php",
			data: {
				action: "save",
				repo : 'market',
				market: json_encode(market),
			},
			dataType: 'json',
			error: function (request, status, error) {
				handleAjaxError(request, status, error, $('#div_alertMarketSend'));
			},
			success: function (data) {
				if (data.state != 'ok') {
					$('#div_alertMarketSend').showAlert({message: data.result, level: 'danger'});
					return;
				}
				if (market.id == undefined || market.id == '') {
					$.showLoading();
					window.location.reload();
				} else {
					$('#div_alertMarketSend').showAlert({message: '{{Votre objet a été envoyé avec succès sur le market}}', level: 'success'});
				}
				
			}
		});
	}else{
		$.ajax({
			type: "POST",
			url: "core/ajax/repo.ajax.php",
			data: {
				action: "save",
				repo : 'market',
				market: json_encode(market),
			},
			dataType: 'json',
			error: function (request, status, error) {
				handleAjaxError(request, status, error, $('#div_alertMarketSend'));
			},
			success: function (data) {
				if (data.state != 'ok') {
					$('#div_alertMarketSend').showAlert({message: data.result, level: 'danger'});
					return;
				}
				if (market.id == undefined || market.id == '') {
					$.showLoading();
					window.location.reload();
				} else {
					$('#div_alertMarketSend').showAlert({message: '{{Votre objet a été envoyé avec succès sur le market}}', level: 'success'});
				}
				
			}
		});
	}
});
</script>
