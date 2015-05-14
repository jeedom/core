<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
sendVarToJS('market_display_info', array(
	'logicalId' => init('logicalId'),
	'name' => init('name'),
));
sendVarToJS('market_type', init('type'));
try {
	if (init('logicalId') != '' && init('type') != '') {
		$market = market::byLogicalIdAndType(init('logicalId'), init('type'));
	}
} catch (Exception $e) {
	$market = null;
}
if (is_object($market)) {
	if ($market->getApi_author() == '') {
		throw new Exception('{{Vous n\'êtes pas l\'auteur du plugin}}');
	}
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
            <div style="height: 130px;" class="priceChoose alert alert-success">
                <br/>
                <center><input type="radio" name="rb_price" class="rb_price free" data-value="" checked/> <h4 style="display: inline-block">Gratuit</h4></center>
            </div>
        </div>
        <div class="col-lg-3">
            <div style="height: 100px;" class="priceChoose">
                <center><input type="radio" name="rb_price" class="rb_price" data-value="2" /> <h4 style="display: inline-block">2 €</h4></center>
                <center>{{Sur ce prix seront 40 % seront reversés au développeur}}</center>
            </div>
        </div>
        <div class="col-lg-3">
            <div style="height: 100px;" class="priceChoose">
                <center><input type="radio" name="rb_price" class="rb_price" data-value="4" /> <h4 style="display: inline-block">4 €</h4></center>
                <center>{{Sur ce prix seront 60 % seront reversés au développeur}}</center>
            </div>
        </div>
        <div class="col-lg-3">
            <div style="height: 100px;" class="priceChoose">
                <center><input type="radio" name="rb_price" class="rb_price" data-value="custom" /> <h4 style="display: inline-block">Libre</h4> <input class="form-control marketAttr input-sm" data-l1key="cost" placeholder="Prix" style="display : inline-block; width : 80px;"> €</center>
                <center>{{Sur ce prix seront 65 % seront reversés au développeur (doit être supérieur ou égal à 5 €)}}</center>
            </div>
        </div>
    </div>
    <hr/>
    <?php }?>
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
                        <option>Alarme diverse</option>
                        <option>Audio/Vidéo</option>
                        <option>Automatisme</option>
                        <option>Autre</option>
                        <option>Energie</option>
                        <option>High tech</option>
                        <option>Humidité</option>
                        <option>Lumière</option>
                        <option>Météo</option>
                        <option>Ouverture</option>
                        <option>Présence</option>
                        <option>Température</option>
                        <option>Vie</option>
                    </select>
                    <select class="form-control plugin category" data-l1key="categorie" style="display: none;">
                        <option>Autre</option>
                        <option>Communication</option>
                        <option>Confort</option>
                        <option>Energie</option>
                        <option>Météo</option>
                        <option>Monitoring</option>
                        <option>Multimédia</option>
                        <option>Nature</option>
                        <option>Objets connectés</option>
                        <option>Organisation</option>
                        <option>passerelle domotique</option>
                        <option>Programmation</option>
                        <option>Protocole domotique</option>
                        <option>Santé</option>
                        <option>Sécurité</option>
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
               <label class="col-sm-2 control-label">Video</label>
               <div class="col-sm-9">
                <input class="form-control marketAttr" data-l1key="link" data-l2key="video">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Wiki</label>
            <div class="col-sm-9">
                <input class="form-control marketAttr" data-l1key="link" data-l2key="wiki">
            </div>
        </div>
        <div class="form-group">
            <label class="col-sm-2 control-label">Forum</label>
            <div class="col-sm-9">
                <input class="form-control marketAttr" data-l1key="link" data-l2key="forum">
            </div>
        </div>
        <div class="form-group">
            <div class="form-group">
                <label class="col-lg-2 control-label">{{Utilisation}}</label>
                <div class="col-lg-9">
                    <textarea class="form-control marketAttr" data-l1key="utilization" placeholder="{{Utilisation}}" style="height: 150px;"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-2 control-label">{{Langue}}</label>
                <div class="col-sm-10">
                    <label class="checkbox-inline">
                      <input type="checkbox" class="marketAttr" data-l1key="language" data-l2key="en_US"> {{English}}
                  </label>
                  <label class="checkbox-inline">
                      <input type="checkbox" class="marketAttr" data-l1key="language" data-l2key="de_DE"> {{Deutsch}}
                  </label>
                  <label class="checkbox-inline">
                      <input type="checkbox" class="marketAttr" data-l1key="language" data-l2key="sp_SP"> {{Español}}
                  </label>
                  <label class="checkbox-inline">
                      <input type="checkbox" class="marketAttr" data-l1key="language" data-l2key="ru_RU"> {{Pусский}}
                  </label>
                  <label class="checkbox-inline">
                      <input type="checkbox" class="marketAttr" data-l1key="language" data-l2key="id_ID"> {{Bahasa Indonesia}}
                  </label>
                  <label class="checkbox-inline">
                      <input type="checkbox" class="marketAttr" data-l1key="language" data-l2key="it_IT"> {{Italiano}}
                  </label>
              </div>
          </div>
      </div>
  </div>
</div>
</form>

<div title="Qu'avez-vous changé ?" id="md_marketSendChangeChange">
    <form class="form-horizontal" role="form">
       <div class="form-group">
        <label class="col-sm-3 control-label">{{Version}}</label>
        <div class="col-sm-3">
            <input class="form-control" id="in_marketSendVersion">
        </div>
    </div>
    <div class="form-group">
        <label class="col-sm-3 control-label">{{Documentation seulement}}</label>
        <div class="col-sm-1">
           <input type="checkbox" id="cb_marketSendDocOnly" />
       </div>
   </div>
   <div class="form-group">
    <label class="col-sm-3 control-label">{{Changement}}</label>
    <div class="col-sm-9">
       <textarea class="form-control" id="ta_marketSendChange" placeholder="{{Changement}}" style="height: 150px;"></textarea>
   </div>

</div>
<a class="btn btn-success pull-right" id="bt_marketSendValideChange"><i class="fa fa-check"></i> {{Valider}}</a>
<a class="btn btn-default pull-right" id="bt_marketSendCancelChange"><i class="fa fa-times"></i> {{Annuler}}</a>
</form>
</div>

<?php
if (is_object($market)) {
	sendVarToJS('market_display_info', utils::o2a($market));
}
?>
<script>
  $("#md_marketSendChangeChange").dialog({
    autoOpen: false,
    modal: true,
    height: 400,
    width:700,
    position: {my: 'center', at: 'center', of: window},
    open: function () {
        $("body").css({overflow: 'hidden'});
    },
    beforeClose: function (event, ui) {
        $("body").css({overflow: 'inherit'});
    }
});



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
    $('#span_directLinkWidget').value('{{Ou en cliquant }}<a href="http://market.jeedom.fr/index.php?v=d&p=addMarket&id='+market_display_info.id+'" target="_blank" >{{ici}}</a>');
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
     $('#md_marketSendChangeChange').dialog('open');
     $('#in_marketSendVersion').value(market_display_info.version);
     $('#ta_marketSendChange').value('');
     $('#cb_marketSendDocOnly').value(0);
     $('#bt_marketSendCancelChange').off().on('click',function(){
        $('#md_marketSendChangeChange').dialog('close');
    });
     $('#bt_marketSendValideChange').off().on('click',function(){

        market.version = $('#in_marketSendVersion').value();
        market.change = $('#ta_marketSendChange').value();
        market.docOnly = $('#cb_marketSendDocOnly').value();


  $.ajax({// fonction permettant de faire de l'ajax
            type: "POST", // methode de transmission des données au fichier php
            url: "core/ajax/market.ajax.php", // url du fichier php
            data: {
                action: "save",
                market: json_encode(market),
            },
            dataType: 'json',
            error: function (request, status, error) {
                handleAjaxError(request, status, error, $('#div_alertMarketSend'));
            },
            success: function (data) { // si l'appel a bien fonctionné
            if (data.state != 'ok') {
                $('#div_alertMarketSend').showAlert({message: data.result, level: 'danger'});
                return;
            }
            if (market.id == undefined || market.id == '') {
                $.showLoading();
                window.location.reload();
            } else {
             $('#md_marketSendChangeChange').dialog('close');
             $('#div_alertMarketSend').showAlert({message: '{{Votre objet a été envoyé avec succès sur le market}}', level: 'success'});
         }

     }
 });
});

}else{
        $.ajax({// fonction permettant de faire de l'ajax
            type: "POST", // methode de transmission des données au fichier php
            url: "core/ajax/market.ajax.php", // url du fichier php
            data: {
                action: "save",
                market: json_encode(market),
            },
            dataType: 'json',
            error: function (request, status, error) {
                handleAjaxError(request, status, error, $('#div_alertMarketSend'));
            },
            success: function (data) { // si l'appel a bien fonctionné
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