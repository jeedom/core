<?php
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
sendVarToJS('market_display_info', array(
    'logicalId' => init('logicalId'),
    'name' => init('name')
));
sendVarToJS('market_type', init('type'));
try {
    if (init('logicalId') != '') {
        $market = market::byLogicalId(init('logicalId'));
    }
} catch (Exception $e) {
    $market = null;
}
if (is_object($market)) {
    if ($market->getApi_author() == '') {
        throw new Exception('{{Vous n\'etes pas l\'autheur du plugin}}');
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
    <?php if (init('type') == 'plugin') { ?>
        <hr/>
        <div class="row">
            <div class="col-lg-3">
                <div style="height: 130px;" class="priceChoose alert alert-success">
                    <br/>
                    <center><input type="radio" name="rb_price" class="rb_price free" data-value="" checked/> <h4 style="display: inline-block">Gratuit</h4></center>
                </div>
            </div>
            <div class="col-lg-3">
                <div style="height: 130px;" class="priceChoose">
                    <center><input type="radio" name="rb_price" class="rb_price" data-value="1" /> <h4 style="display: inline-block">1€</h4></center>
                    <center>Sur ce prix seront prélevés 0,25€ de frais paypal puis 35% destinés à l'équipe du projet Jeedom</center>
                </div>
            </div>
            <div class="col-lg-3">
                <div style="height: 130px;" class="priceChoose">
                    <center><input type="radio" name="rb_price" class="rb_price" data-value="2" /> <h4 style="display: inline-block">2€</h4></center>
                    <center>Sur ce prix seront prélevés 0,25€ de frais paypal puis 30% destinés à l'équipe du projet Jeedom</center>
                </div>
            </div>
            <div class="col-lg-3">
                <div style="height: 130px;" class="priceChoose">
                    <center><input type="radio" name="rb_price" class="rb_price" data-value="custom" /> <h4 style="display: inline-block">Libre</h4> <input class="form-control marketAttr input-sm" data-l1key="cost" placeholder="Prix" style="display : inline-block; width : 80px;"> €</center>
                    <center>Sur ce prix seront prélevés 0,25€ de frais paypal puis 25% destinés à l'équipe du projet Jeedom (le prix doit etre >= 3€)</center>
                </div>
            </div>
        </div>
        <hr/>
    <?php } ?>
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
                        <option value="plugin" data-category='input'>{{Plugin}}</option>
                        <option value="widget" data-category='widget'>{{Widget}}</option>
                        <option value="zwave" data-category='input'>{{[Zwave] Configuration module}}</option>
                        <option value="enocean" data-category='input'>{{[EnOcean] Configuration module}}</option>
                        <option value="rfxcom" data-category='input'>{{[RfxCom] Configuration module}}</option>
                        <option value="script" data-category='input'>{{Script}}</option>
                        <option value="camera" data-category='input'>{{[Camera] Modèle}}</option>
                        <option value="SNMP" data-category='input'>{{[SNMP] Configuration}}</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 control-label">{{Catégorie}}</label>
                <div class="col-lg-6">
                    <input class="form-control input category marketAttr" data-l1key="categorie" >
                    <select class="form-control widget category" data-l1key="categorie" style="display: none;">
                        <option>Température</option>
                        <option>Lumière</option>
                        <option>Humidité</option>
                        <option>Consommation</option>
                        <option>Puissance</option>
                        <option>Ouverture</option>
                        <option>Présence</option>
                        <option>Alarme diverse</option>
                        <option>High tech</option>
                        <option>Automatisme</option>
                        <option>Audio/Vidéo</option>
                        <option>Météo</option>
                        <option>Nature</option>
                        <option>Vie</option>
                        <option>Autre</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 control-label">{{Description}}</label>
                <div class="col-lg-6">
                    <textarea class="form-control marketAttr" data-l1key="description" placeholder="{{Description}}" style="height: 150px;"></textarea>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Lien Video</label>
                <div class="col-sm-6">
                    <input class="form-control marketAttr" data-l1key="link" data-l2key="video">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Lien Wiki</label>
                <div class="col-sm-6">
                    <input class="form-control marketAttr" data-l1key="link" data-l2key="wiki">
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Lien Forum</label>
                <div class="col-sm-6">
                    <input class="form-control marketAttr" data-l1key="link" data-l2key="forum">
                </div>
            </div>
        </div> 
        <div class="col-lg-6">
            <div class="form-group">
                <div class="form-group">
                    <label class="col-lg-4 control-label">{{Utilisation}}</label>
                    <div class="col-lg-6">
                        <textarea class="form-control marketAttr" data-l1key="utilization" placeholder="{{Utilisation}}" style="height: 150px;"></textarea>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-lg-4 control-label">{{Changelog}}</label>
                    <div class="col-lg-6">
                        <textarea class="form-control marketAttr" data-l1key="changelog" placeholder="{{Changelog}}" style="height: 150px;"></textarea>
                    </div>
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

    if (market_display_info.realcost == '' || market_display_info.realcost == 0) {
        $('.rb_price.free').prop('checked', true);
        $('.marketAttr[data-l1key=cost]').value('');
        $('.rb_price.free').closest('.priceChoose').addClass('alert alert-success');
    } else if (market_display_info.realcost == 1) {
        $('.rb_price[data-value=1]').prop('checked', true);
        $('.marketAttr[data-l1key=cost]').value('');
        $('.rb_price[data-value=1]').closest('.priceChoose').addClass('alert alert-success');
        $('.rb_price.free').closest('.priceChoose').removeClass('alert alert-success');
    } else if (market_display_info.realcost == 2) {
        $('.rb_price[data-value=2]').prop('checked', true);
        $('.marketAttr[data-l1key=cost]').value('');
        $('.rb_price[data-value=2]').closest('.priceChoose').addClass('alert alert-success');
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
    });
</script>