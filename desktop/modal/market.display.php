<?php
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}

if (init('id') != '') {
    $market = market::byId(init('id'));
}
if (init('logicalId') != '') {
    $market = market::byLogicalId(init('logicalId'));
}
if (!isset($market)) {
    throw new Exception('404 not found');
}
include_file('3rdparty', 'jquery.lazyload/jquery.lazyload', 'js');
include_file('3rdparty', 'bootstrap.rating/bootstrap.rating', 'js');

$market_array = utils::o2a($market);
$market_array['rating'] = $market->getRating();
$update = update::byLogicalId($market->getLogicalId());
sendVarToJS('market_display_info', $market_array);
if (is_object($update) && $update->getConfiguration('version', 'stable') == 'beta') {
    echo '<div class="alert alert-danger">{{Attention vous utilisez actuellement une version beta. Celle-ci peut ne pas être stable}}</div>';
}
if (is_object($update) && $update->getStatus() == 'update') {
    echo '<div class="alert alert-warning" id="div_pluginUpdate">{{Une mise à jour est disponible. Cliquez sur installer pour l\'effectuer}}</div>';
}
?>
<div style="display: none;width : 100%" id="div_alertMarketDisplay"></div>
<?php
if ($market->getPurchase() == 1) {
    if ($market->getStatus('stable') == 1) {
        echo '<a class="btn btn-success pull-right bt_installFromMarket" data-version="stable" style="color : white;" data-market_logicalId="' . $market->getLogicalId() . '" data-market_id="' . $market->getId() . '" ><i class="fa fa-plus-circle"></i> {{Installer stable}}</a>';
    }
    if ($market->getStatus('beta') == 1) {
        echo '<a class="btn btn-warning pull-right bt_installFromMarket" data-version="beta" style="color : white;" data-market_logicalId="' . $market->getLogicalId() . '" data-market_id="' . $market->getId() . '" ><i class="fa fa-plus-circle"></i> {{Installer beta}}</a>';
    }
} else if (config::byKey('market::apikey') != '' || (config::byKey('market::username') != '' && config::byKey('market::password') != '')) {
    $purchase_info = market::getPurchaseInfo();
    if (count($purchase_info) == 3 && isset($purchase_info['user_id']) && is_numeric($purchase_info['user_id']) && isset($purchase_info['paypal::url']) && isset($purchase_info['paypal::marchandMail'])) {
        ?>
        <a class="btn btn-default btn-xs pull-right" href='https://market.jeedom.fr/index.php?v=d&p=profils'><i class="fa fa-eur"></i> Code promo</a>
        <form action="<?php echo $purchase_info['paypal::url'] ?>/cgi-bin/webscr" method="post" style="display: inline-block;" class="pull-right" target="_blank" id='form_paypal'>
            <input type='hidden' name="amount" value="<?php echo $market->getCost() ?>" />
            <input name="currency_code" type="hidden" value="EUR" />
            <input name="shipping" type="hidden" value="0.00" />
            <input name="tax" type="hidden" value="0.00" />
            <input name="return" type="hidden" value="<?php echo config::byKey('market::address') . '/index.php?v=d&p=resultBuy&success=1' ?>" />
            <input name="cancel_return" type="hidden" value="<?php echo config::byKey('market::address') . '/index.php?v=d&p=resultBuy&success=0' ?>" />
            <input name="notify_url" type="hidden" value="<?php echo config::byKey('market::address') . '/index.php?v=d&p=registerBuy' ?>" />
            <input name="cmd" type="hidden" value="_xclick" />
            <input name="business" type="hidden" value="<?php echo $purchase_info['paypal::marchandMail'] ?>" />
            <input name="item_name" type="hidden" value="<?php echo '[' . $market->getType() . '] ' . $market->getLogicalId() ?>" />
            <input name="no_note" type="hidden" value="1" />
            <input name="lc" type="hidden" value="FR" />
            <input name="bn" type="hidden" value="PP-BuyNowBF" />
            <input name="custom" type="hidden" value="<?php echo $purchase_info['user_id'] . ':' . $market->getId() ?>" />
            <input class="pull-right" id='bt_paypalClick' alt="{{Effectuez vos paiements via PayPal : une solution rapide, gratuite et sécurisée}}" name="submit" src="https://www.paypal.com/fr_FR/FR/i/btn/btn_buynow_LG.gif" type="image" style="display: inline-block;"/><img class="pull-right" src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" border="0" alt="" width="1" height="1" style="display: inline-block;"/>
        </form>
        <?php
    } else {
        echo '<div class="alert alert-info">{{Cet article est payant vous devez avoir un compte sur le market et avoir renseigné la clef API de celui-ci dans Jeedom pour pouvoir l\'acheter}}</div>';
    }
} else {
    echo '<div class="alert alert-info">{{Cet article est payant vous devez avoir un compte sur le market et avoir renseigné la clef API de celui-ci dans Jeedom pour pouvoir l\'acheter}}</div>';
}
?>
<?php if (is_object($update)) { ?>
    <a class="btn btn-danger pull-right" style="color : white;" id="bt_removeFromMarket" data-market_id="<?php echo $market->getId(); ?>" ><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
<?php } ?>
<a class="btn btn-default pull-right" id="bt_viewComment"><i class="fa fa-comments-o"></i> {{Commentaires}}</a>
<br/><br/><br/>
<form class="form-horizontal" role="form">
    <div class="row">
        <div class="col-lg-6">
            <div class="form-group">
                <label class="col-lg-4 control-label">{{Nom}}</label>
                <div class="col-lg-8">
                    <input class="form-control marketAttr" data-l1key="id" style="display: none;">
                    <span class="label label-success marketAttr" data-l1key="name" placeholder="{{Nom}}" style="font-size: 1.6em;"></span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 control-label">{{Prix}}</label>
                <div class="col-lg-8">
                    <?php
                    if ($market->getCost() > 0) {
                        echo '<span class="label label-primary" data-l1key="rating" style="font-size: 1em;">' . number_format($market->getCost(), 2) . ' €</span> (TVA non applicable, article 293 B du CGI)';
                    } else {
                        echo '<span class="label label-primary" data-l1key="rating" style="font-size: 1em;">{{Gratuit}}</span>';
                    }
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Certification</label>
                <div class="col-sm-8">
                    <?php
                    if ($market->getCertification() == 'Officiel') {
                        echo '<span class="label label-success">Officiel</span>';
                    }
                    if ($market->getCertification() == 'Recommandé') {
                        echo '<span class="label label-primary">Recommandé</span>';
                    }
                    ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 control-label">{{Description}}</label>
                <div class="col-lg-8">
                    <pre class="marketAttr" data-l1key="description" style="word-wrap: break-word;white-space: -moz-pre-wrap;white-space: pre-wrap;" ></pre>
                </div>
            </div>

            <div class="form-group">
                <label class="col-lg-4 control-label">{{Note}}</label>
                <div class="col-lg-2">
                    <span class="label label-primary marketAttr" data-l1key="rating" style="font-size: 1.2em;"></span>
                </div>
                <?php if (config::byKey('market::apikey') != '' || (config::byKey('market::username') != '' && config::byKey('market::password') != '')) { ?>
                    <label class="col-lg-2 control-label">{{Ma Note}}</label>
                    <div class="col-lg-3">
                        <span><input type="number" class="rating" id="in_myRating" data-max="5" data-empty-value="0" data-min="1" data-clearable="Effacer" value="<?php echo $market->getRating('user') ?>" /></span>
                    </div>
                <?php } ?>
            </div>
            <div class="form-group">
                <label class="col-lg-4 control-label">{{Catégorie}}</label>
                <div class="col-lg-8">
                    <span class="label label-warning marketAttr" data-l1key="categorie" placeholder="{{Catégorie}}" style="font-size: 1em;"></span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Version</label>
                <div class="col-sm-8">
                    <?php
                    if ($market->getStatus('stable') == 1) {
                        echo '<span class="label label-success">';
                        echo 'Stable : ';
                        echo $market->getDatetime('stable');
                        echo '</span>';
                    }
                    if ($market->getStatus('beta') == 1) {
                        echo ' <span class="label label-warning">';
                        echo 'Beta : ';
                        echo $market->getDatetime('beta');
                        echo '</span>';
                    }
                    if (is_object($update) && $update->getConfiguration('version', 'stable') == 'beta' && $market->getStatus('stable') == 1) {
                        if (strtotime($market->getDatetime('stable')) >= strtotime($update->getLocalVersion())) {
                            echo '<br/><span class="label label-info">';
                            echo '{{Le retour à la version stable est possible}}';
                            echo '</span>';
                        } else {
                            echo '<br/><span class="label label-danger">';
                            echo '{{Le retour à la version stable est dangereux}}';
                            echo '</span>';
                        }
                    }
                    ?>
                </div>
            </div>

            <div class="form-group">
                <label class="col-lg-4 control-label">{{Changelog}}</label>
                <div class="col-lg-8">
                    <pre class="marketAttr" data-l1key="changelog" style="word-wrap: break-word;white-space: -moz-pre-wrap;white-space: pre-wrap;" ></pre>
                </div>
            </div>
            <div class="form-group">
                <label class="col-sm-4 control-label">Lien</label>
                <div class="col-sm-8">
                    <?php if ($market->getLink('wiki') != '' && $market->getLink('wiki') != 'null') { ?>
                        <a class="btn btn-default btn-xs" target="_blank" href="<?php echo $market->getLink('wiki'); ?>"><i class="fa fa-krw"></i> Wiki</a>
                    <?php } ?>
                    <?php if ($market->getLink('video') != '' && $market->getLink('video') != 'null') { ?>
                        <a class="btn btn-default btn-xs" target="_blank" href="<?php echo $market->getLink('video'); ?>"><i class="fa fa-youtube"></i> Video</a>
                    <?php } ?>
                    <?php if ($market->getLink('forum') != '' && $market->getLink('forum') != 'null') { ?>
                        <a class="btn btn-default btn-xs" target="_blank" href="<?php echo $market->getLink('forum'); ?>"><i class="fa fa-users"></i> Forum</a>
                    <?php } ?>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 control-label">{{Type}}</label>
                <div class="col-lg-8">
                    <select class="form-control marketAttr" data-l1key="type" disabled>
                        <option value="plugin">{{Plugin}}</option>
                        <option value="widget">{{Widget}}</option>
                        <option value="zwave_module">{{[Zwave] Configuration module}}</option>
                        <option value="enocean">{{[EnOcean] Configuration module}}</option>
                        <option value="rfxcom">{{[RfxCom] Configuration module}}</option>
                        <option value="script">{{Script}}</option>
                        <option value="camera">{{[Camera] Modèle}}</option>
                    </select>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 control-label">{{Auteur}}</label>
                <div class="col-lg-8">
                    <span><?php echo $market->getAuthor() ?></span>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 control-label">{{Utilisation}}</label>
                <div class="col-lg-8">
                    <pre class="marketAttr" data-l1key="utilization" style="word-wrap: break-word;white-space: -moz-pre-wrap;white-space: pre-wrap;" ></pre>
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 control-label">{{Nombre de téléchargements}}</label>
                <div class="col-lg-8">
                    <span class="marketAttr" data-l1key="downloaded"></span>
                </div>
            </div>
        </div> 
        <div class="col-lg-6">
            <div class="form-group">
                <div class="col-lg-12">
                    <center>
                        <?php
                        if ($market->getStatus('stable') == 1 && $market->getImg('stable')) {
                            $urlPath = config::byKey('market::address') . '/' . $market->getImg('stable');
                        } else {
                            if ($market->getImg('beta')) {
                                $urlPath = config::byKey('market::address') . '/' . $market->getImg('beta');
                            }
                        }
                        echo '<img src="core/img/no_image.gif" data-original="' . $urlPath . '"  class="lazy img-responsive img-thumbnail"/>';
                        ?>
                    </center>
                </div>
            </div>
        </div> 
    </div> 
</form>

<div id="div_comments" title="{{Commentaires}}"></div>

<script>
    $('body').setValues(market_display_info, '.marketAttr');

    $('#bt_paypalClick').on('click', function () {
        $(this).hide();
    });

    $("img.lazy").lazyload({
        event: "sporty"
    });
    $("img.lazy").trigger("sporty");

    $("#div_comments").dialog({
        autoOpen: false,
        modal: true,
        height: (jQuery(window).height() - 300),
        width: 600,
        position: {my: 'center', at: 'center', of: window},
        open: function () {
            if ((jQuery(window).width() - 50) < 1500) {
                $('#md_modal').dialog({width: jQuery(window).width() - 50});
            }
        }
    });

    $('#bt_viewComment').on('click', function () {
        reloadMarketComment();
        $('#div_comments').dialog('open');
    });


    function reloadMarketComment() {
        $('#div_comments').load('index.php?v=d&modal=market.comment&id=' + $('.marketAttr[data-l1key=id]').value());
    }

    $('.bt_installFromMarket').on('click', function () {
        var id = $(this).attr('data-market_id');
        var logicalId = $(this).attr('data-market_logicalId');
        $.ajax({// fonction permettant de faire de l'ajax
            type: "POST", // methode de transmission des données au fichier php
            url: "core/ajax/market.ajax.php", // url du fichier php
            data: {
                action: "install",
                id: id,
                version: $(this).attr('data-version'),
            },
            dataType: 'json',
            error: function (request, status, error) {
                handleAjaxError(request, status, error);
            },
            success: function (data) { // si l'appel a bien fonctionné
                if (data.state != 'ok') {
                    $('#div_alertMarketDisplay').showAlert({message: data.result, level: 'danger'});
                    return;
                }
                var url = window.location.href;
                if (url.indexOf('p=plugin') > 0) {
                    window.location.href = 'index.php?v=d&p=plugin&id=' + logicalId;
                }
                $('#div_alertMarketDisplay').showAlert({message: '{{Objet installé avec succès}}', level: 'success'});
            }
        });
    });

    $('#bt_removeFromMarket').on('click', function () {
        var id = $(this).attr('data-market_id');
        $.ajax({// fonction permettant de faire de l'ajax
            type: "POST", // methode de transmission des données au fichier php
            url: "core/ajax/market.ajax.php", // url du fichier php
            data: {
                action: "remove",
                id: id
            },
            dataType: 'json',
            error: function (request, status, error) {
                handleAjaxError(request, status, error);
            },
            success: function (data) { // si l'appel a bien fonctionné
                if (data.state != 'ok') {
                    $('#div_alertMarketDisplay').showAlert({message: data.result, level: 'danger'});
                    return;
                }
                $.showLoading();
                window.location.reload();
            }
        });
    });

    $('#in_myRating').on('change', function () {
        var id = $('.marketAttr[data-l1key=id]').value();
        $.ajax({// fonction permettant de faire de l'ajax
            type: "POST", // methode de transmission des données au fichier php
            url: "core/ajax/market.ajax.php", // url du fichier php
            data: {
                action: "setRating",
                id: id,
                rating: $(this).val()
            },
            dataType: 'json',
            error: function (request, status, error) {
                handleAjaxError(request, status, error);
            },
            success: function (data) { // si l'appel a bien fonctionné
                if (data.state != 'ok') {
                    $('#div_alert').showAlert({message: data.result, level: 'danger'});
                    return;
                }
            }
        });
    });
</script>