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
include_file('3rdparty', 'slick/slick.min', 'js');
include_file('3rdparty', 'slick/slick', 'css');
include_file('3rdparty', 'fancybox/jquery.fancybox', 'js');
include_file('3rdparty', 'fancybox/jquery.fancybox', 'css');


$market_array = utils::o2a($market);
$market_array['rating'] = $market->getRating();
$update = update::byLogicalId($market->getLogicalId());
sendVarToJS('market_display_info', $market_array);
?>


<div class='row' style='background-color: #e7e7e7; padding-top: 10px; padding-bottom: 10px;position: relative; top: -10px;'>
    <div class='col-sm-3'>
        <center>
            <?php
            $urlPath = config::byKey('market::address') . '/' . $market->getImg('icon');
            echo '<img src="core/img/no_image.gif" data-original="' . $urlPath . '"  class="lazy img-responsive" style="height : 200px;"/>';
            ?>
        </center>
    </div>
    <div class='col-sm-8'>
        <input class="form-control marketAttr" data-l1key="id" style="display: none;">
        <span class="marketAttr" data-l1key="name" placeholder="{{Nom}}" style="font-size: 3em;font-weight: bold;"></span>
        <br/>
        <?php
        if ($market->getCertification() == 'Officiel') {
            echo '<span style="font-size : 1.5em;color:#707070">Officiel</span><br/>';
        }
        if ($market->getCertification() == 'Recommandé') {
            echo '<span style="font-size: 1.5em;font-weight: bold;color:#707070;">Recommandé</span><br/>';
        }
        ?>
        <span class="marketAttr" data-l1key="categorie" style="font-size: 1em;font-weight: bold;"></span>
        <br/><br/>
        <?php
        if ($market->getPurchase() == 1) {
            if ($market->getStatus('stable') == 1) {
                echo ' <a class="btn btn-success bt_installFromMarket" data-version="stable" style="color : white;" data-market_logicalId="' . $market->getLogicalId() . '" data-market_id="' . $market->getId() . '" ><i class="fa fa-plus-circle"></i> {{Installer stable}}</a>';
            }
            if ($market->getStatus('beta') == 1) {
                echo ' <a class="btn btn-warning bt_installFromMarket" data-version="beta" style="color : white;" data-market_logicalId="' . $market->getLogicalId() . '" data-market_id="' . $market->getId() . '" ><i class="fa fa-plus-circle"></i> {{Installer beta}}</a>';
            }
        } else if (config::byKey('market::apikey') != '' || (config::byKey('market::username') != '' && config::byKey('market::password') != '')) {
            $purchase_info = market::getPurchaseInfo();
            if (count($purchase_info) == 3 && isset($purchase_info['user_id']) && is_numeric($purchase_info['user_id']) && isset($purchase_info['paypal::url']) && isset($purchase_info['paypal::marchandMail'])) {
                ?>
                <a class="btn btn-default" href='https://market.jeedom.fr/index.php?v=d&p=profils' target="_blank"><i class="fa fa-eur"></i> Code promo</a>
                <form action="<?php echo $purchase_info['paypal::url'] ?>/cgi-bin/webscr" method="post" style="display: inline-block;position: relative;top: 5px;" target="_blank" id='form_paypal'>
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
                    <input id='bt_paypalClick' alt="{{Effectuez vos paiements via PayPal : une solution rapide, gratuite et sécurisée}}" name="submit" src="https://www.paypal.com/fr_FR/FR/i/btn/btn_buynow_LG.gif" type="image" style="display: inline-block;position: relative;top: 5px;"/><img class="pull-right" src="https://www.paypal.com/fr_FR/i/scr/pixel.gif" border="0" alt="" width="1" height="1" style="display: inline-block;"/>
                </form>
                <?php
            } else {
                echo '<div class="alert alert-info">{{Cet article est payant vous devez avoir un compte sur le market et avoir renseigné les identifiants market dans Jeedom pour pouvoir l\'acheter}}</div>';
            }
        } else {
            echo '<div class="alert alert-info">{{Cet article est payant vous devez avoir un compte sur le market et avoir renseigné les identifiants market dans Jeedom pour pouvoir l\'acheter}}</div>';
        }
        if (is_object($update)) {
            ?>
            <a class="btn btn-danger" style="color : white;" id="bt_removeFromMarket" data-market_id="<?php echo $market->getId(); ?>" ><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
        <?php } ?>
        <br/><br/>
        <?php
        if ($market->getCost() > 0) {
            if ($market->getCost() != $market->getRealCost()) {
                echo '<span data-l1key="rating" style="font-size: 1em;text-decoration:line-through;">' . number_format($market->getRealCost(), 2) . ' €</span> ';
            }
            echo '<span data-l1key="rating" style="font-size: 1.5em;">' . number_format($market->getCost(), 2) . ' €</span> (TVA non applicable, article 293 B du CGI)';
        } else {
            echo '<span data-l1key="rating" style="font-size: 1.5em;">{{Gratuit}}</span>';
        }
        ?>
    </div>
</div>
<div style="display: none;width : 100%" id="div_alertMarketDisplay"></div>

<?php if (count($market->getImg('screenshot')) > 0) { ?>
    <div style='padding:25px;'>
        <div class="variable-width" style="height : 200px;">
            <?php
            foreach ($market->getImg('screenshot') as $screenshot) {
                echo '<div class="item" >';
                echo '<a class="fancybox cursor" href="' . config::byKey('market::address') . '/' . $screenshot . '" rel="group" >';
                echo '<img data-lazy="' . config::byKey('market::address') . '/' . $screenshot . '" style="height : 200px;" />';
                echo '</a>';
                echo '</div>';
            }
            ?>
        </div>
    </div>
<?php } ?>

<br/>
<div class='row'>
    <div class='col-sm-6'>
        <legend>Description</legend>
        <span class="marketAttr" data-l1key="description" style="word-wrap: break-word;white-space: -moz-pre-wrap;white-space: pre-wrap;" ></span>
    </div>
    <div class='col-sm-6'>
        <legend>Nouveautés</legend>
        <span class="marketAttr" data-l1key="changelog" style="word-wrap: break-word;white-space: -moz-pre-wrap;white-space: pre-wrap;" ></span>
    </div>
</div>
<br/>
<div class='row'>
    <div class='col-sm-6'>
        <legend>Avis</legend>
        <div class='row'>
            <div class='col-sm-6'>
                <center>
                    <span class="marketAttr" data-l1key="rating" style="font-size: 4em;"></span>/5
                </center>
            </div>
            <div class='col-sm-6'>
                <?php if (config::byKey('market::apikey') != '' || (config::byKey('market::username') != '' && config::byKey('market::password') != '')) { ?>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">{{Ma Note}}</label>
                        <div class="col-sm-8">
                            <span><input type="number" class="rating" id="in_myRating" data-max="5" data-empty-value="0" data-min="1" data-clearable="Effacer" value="<?php echo $market->getRating('user') ?>" /></span>
                        </div>
                    </div><br/>
                <?php } ?>
                <center>
                    <a class="btn btn-default" id="bt_viewComment"><i class="fa fa-comments-o"></i> {{Commentaires}}</a>
                </center>
            </div>
        </div>
    </div>
    <div class='col-sm-6'>
        <legend>Utilisation</legend>
        <span class="marketAttr" data-l1key="utilization" style="word-wrap: break-word;white-space: -moz-pre-wrap;white-space: pre-wrap;" ></span>
    </div>
</div>
<br/>
<legend>Informations complementaires</legend>
<div class="form-group">
    <div class='row'>
        <div class='col-sm-2'>
            <label class="control-label">{{Auteur}}</label><br/>
            <span><?php echo $market->getAuthor() ?></span>
        </div>
        <div class='col-sm-3'>
            <label class="control-label">{{Lien}}</label><br/>
            <?php if ($market->getLink('wiki') != '' && $market->getLink('wiki') != 'null') { ?>
                <a class="btn btn-default btn-xs" target="_blank" href="<?php echo $market->getLink('wiki'); ?>"><i class="fa fa-krw"></i> Wiki</a>
            <?php } ?>
            <?php if ($market->getLink('video') != '' && $market->getLink('video') != 'null') { ?>
                <a class="btn btn-default btn-xs" target="_blank" href="<?php echo $market->getLink('video'); ?>"><i class="fa fa-youtube"></i> Video</a>
            <?php } ?>
            <?php if ($market->getLink('forum') != '' && $market->getLink('forum') != 'null') { ?>
                <a class="btn btn-default btn-xs" target="_blank" href="<?php echo $market->getLink('forum'); ?>"><i class="fa fa-users"></i> Forum</a>
            <?php } ?>
            <?php if ($market->getLink('doc_fr_FR') != '' && $market->getLink('doc_fr_FR') != 'null') { ?>
                <a class="btn btn-default btn-xs" target="_blank" href="<?php echo $market->getLink('doc_fr_FR'); ?>"><i class="fa fa-book"></i> Doc FR</a>
            <?php } ?>
            <?php if ($market->getLink('doc_us_US') != '' && $market->getLink('doc_us_US') != 'null') { ?>
                <a class="btn btn-default btn-xs" target="_blank" href="<?php echo $market->getLink('doc_us_US'); ?>"><i class="fa fa-book"></i> Doc US</a>
            <?php } ?>
        </div>
        <div class='col-sm-2'>
            <label class="control-label">{{Nombre de téléchargements}}</label><br/>
            <span class="marketAttr" data-l1key="downloaded"></span>
        </div>
        <div class='col-sm-2'>
            <label class="control-label">{{Type}}</label><br/>
            <span class="marketAttr" data-l1key="type"></span>
        </div>
        <div class='col-sm-3'>
            <label class="control-label">{{Version}}</label><br/>
            <?php
            if ($market->getStatus('stable') == 1) {
                echo '<span class="label label-success">';
                echo 'Stable : ';
                echo $market->getDatetime('stable');
                echo '</span><br/>';
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

</div>

<div id="div_comments" title="{{Commentaires}}"></div>

<style>
    .slick-prev:before, .slick-next:before {
        color : #707070;
    }
</style>
<script>
    $("img.lazy").lazyload({
        event: "sporty"
    });
    $("img.lazy").trigger("sporty");
    
    $(document).unbind('click.fb-start');
    $(".fancybox").fancybox({
        autoHeight: true,
    });

    $('.variable-width').unslick().slick({
        dots: true,
        speed: 200,
        variableWidth: true,
        accessibility: true,
    });
    $('.variable-width').slickNext();
    setTimeout(function(){$('.variable-width').slickGoTo(0);}, 200);
    
    
    $('body').setValues(market_display_info, '.marketAttr');
    $('#bt_paypalClick').on('click', function () {
        $(this).hide();
    });


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