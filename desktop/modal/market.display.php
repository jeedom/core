<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

if (init('id') != '') {
	$market = market::byId(init('id'));
}
if (init('logicalId') != '' && init('type') != '') {
	$market = market::byLogicalIdAndType(init('logicalId'), init('type'));
}
if (!isset($market)) {
	throw new Exception('404 not found');
}
include_file('3rdparty', 'bootstrap.rating/bootstrap.rating', 'js');
include_file('3rdparty', 'slick/slick.min', 'js');
include_file('3rdparty', 'slick/slick', 'css');
include_file('3rdparty', 'slick/slick-theme', 'css');
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
$default_image = 'core/img/no_image.gif';
switch ($market->getType()) {
	case 'widget':
		$default_image = 'core/img/no-image-widget.png';
		break;
	case 'plugin':
		$default_image = 'core/img/no-image-plugin.png';
		break;
	case 'camera':
		$default_image = 'core/img/no-image-camera.png';
		break;
	case 'script':
		$default_image = 'core/img/no-image-script.png';
		break;
}
$urlPath = config::byKey('market::address') . '/' . $market->getImg('icon');
echo '<img src="' . $default_image . '" data-original="' . $urlPath . '"  class="lazy img-responsive" style="height : 200px;"/>';
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
if ($market->getCertification() == 'Conseillé') {
	echo '<span style="font-size: 1.5em;font-weight: bold;color:#707070;">Conseillé</span><br/>';
}
if ($market->getCertification() == 'Obsolète') {
	echo '<span style="font-size: 1.5em;font-weight: bold;color:#e74c3c;">Obsolète</span><br/>';
}
?>
   <span class="marketAttr" data-l1key="categorie" style="font-size: 1em;font-weight: bold;"></span>
   <br/><br/>
   <?php
if ($market->getPurchase() == 1) {
	if ($market->getStatus('stable') == 1) {
		echo ' <a class="btn btn-success bt_installFromMarket" data-version="stable" style="color : white;" data-market_logicalId="' . $market->getLogicalId() . '" data-market_id="' . $market->getId() . '" ><i class="fa fa-plus-circle"></i> {{Installer stable}}</a>';
	}
	if (config::byKey('market::allowBeta') == 1 || $market->getIsAuthor()) {
		if ($market->getStatus('stable') == 1) {
			echo ' <a class="btn btn-primary bt_installFromMarket" data-version="release" style="color : white;" data-market_logicalId="' . $market->getLogicalId() . '" data-market_id="' . $market->getId() . '" ><i class="fa fa-plus-circle"></i> {{Installer release}}</a>';
		}
		if ($market->getStatus('beta') == 1) {
			echo ' <a class="btn btn-warning bt_installFromMarket" data-version="beta" style="color : white;" data-market_logicalId="' . $market->getLogicalId() . '" data-market_id="' . $market->getId() . '" ><i class="fa fa-plus-circle"></i> {{Installer beta}}</a>';
		}
	}
} else if ($market->getPrivate() == 1) {
	echo '<div class="alert alert-info">{{Ce plugin est pour le moment privé. Vous devez attendre qu\'il devienne public ou avoir un code pour y accèder}}</div>';
} else {
	if (config::byKey('market::apikey') != '' || (config::byKey('market::username') != '' && config::byKey('market::password') != '')) {
		$purchase_info = market::getPurchaseInfo();
		if (isset($purchase_info['user_id']) && is_numeric($purchase_info['user_id'])) {

			?>
      <a class="btn btn-default" href='https://market.jeedom.fr/index.php?v=d&p=profils' target="_blank"><i class="fa fa-eur"></i> Code promo</a>
      <?php
echo '<a class="btn btn-default" target="_blank" href="' . config::byKey('market::address') . '/index.php?v=d&p=purchaseItem&user_id=' . $purchase_info['user_id'] . '&type=plugin&id=' . $market->getId() . '"><i class="fa fa-shopping-cart"></i> {{Acheter}}</a>';

		} else {
			echo '<div class="alert alert-info">{{Cet article est payant vous devez avoir un compte sur le market et avoir renseigné les identifiants market dans Jeedom pour pouvoir l\'acheter}}</div>';
		}
	} else {
		echo '<div class="alert alert-info">{{Cet article est payant vous devez avoir un compte sur le market et avoir renseigné les identifiants market dans Jeedom pour pouvoir l\'acheter}}</div>';
	}
}
if (is_object($update)) {
	?>
  <a class="btn btn-danger" style="color : white;" id="bt_removeFromMarket" data-market_id="<?php echo $market->getId(); ?>" ><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
  <?php }
?>
  <br/><br/>
  <?php
if ($market->getCost() > 0) {
	if ($market->getCost() != $market->getRealCost()) {
		echo '<span data-l1key="rating" style="font-size: 1em;text-decoration:line-through;">' . number_format($market->getRealCost(), 2) . ' €</span> ';
	}
	echo '<span data-l1key="rating" style="font-size: 1.5em;">' . number_format($market->getCost(), 2) . ' € TTC</span>';
} else {
	echo '<span data-l1key="rating" style="font-size: 1.5em;">{{Gratuit}}</span>';
}
?>
</div>
</div>
<?php
if ($market->getCertification() != 'Officiel') {
	echo '<div class="alert alert-warning">{{Attention ce plugin n\'est pas un plugin officiel en cas de soucis avec celui-ci (direct ou indirect) toute demande de support peut être refusée}}</div>';
}
$compatibilityHardware = $market->getHardwareCompatibility();
if (is_array($compatibilityHardware) && count($compatibilityHardware) > 0 && $compatibilityHardware[jeedom::getHardwareName()] != 1) {
	echo '<div class="alert alert-danger">{{Attention ce plugin ne semble pas être compatible avec votre système}}</div>';
}
?>
<div style="display: none;width : 100%" id="div_alertMarketDisplay"></div>

<?php if (count($market->getImg('screenshot')) > 0) {
	?>
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
  <?php }
?>

  <br/>
  <div class='row'>
    <div class='col-sm-6'>
      <legend>{{Description}}
        <a class="btn btn-default btn-xs pull-right" target="_blank" href="https://jeedom.com/doc/documentation/plugins/<?php echo $market->getLogicalId() . '/fr_FR/' . $market->getLogicalId() . '.html' ?>"><i class="fa fa-book"></i> {{Documentation}}</a><br/>
      </legend>
      <span class="marketAttr" data-l1key="description" style="word-wrap: break-word;white-space: -moz-pre-wrap;white-space: pre-wrap;" ></span>
      <br/><br/>
      <legend>{{Compatibilité plateforme}}</legend>
      <?php
if ($market->getHardwareCompatibility('DIY') == 1) {
	echo '<img src="core/img/logo_diy.png" style="width:60px;height:60px;" />';
}
if ($market->getHardwareCompatibility('RPI/RPI2') == 1) {
	echo '<img src="core/img/logo_rpi12.png" style="width:60px;height:60px;" />';
}
if ($market->getHardwareCompatibility('Docker') == 1) {
	echo '<img src="core/img/logo_docker.png" style="width:60px;height:60px;" />';
}
if ($market->getHardwareCompatibility('Jeedomboard') == 1) {
	echo '<img src="core/img/logo_jeedomboard.png" style="width:60px;height:60px;" />';
}
?>
   </div>
   <div class='col-sm-6'>
    <legend>Nouveautés <a class="btn btn-xs btn-default pull-right" id="bt_viewCompleteChangelog"><i class="fa fa-eye"></i> {{Tout voir}}</a></legend>
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
        <?php if (config::byKey('market::apikey') != '' || (config::byKey('market::username') != '' && config::byKey('market::password') != '')) {?>
          <div class="form-group">
            <label class="col-sm-4 control-label">{{Ma Note}}</label>
            <div class="col-sm-8">
              <span><input type="number" class="rating" id="in_myRating" data-max="5" data-empty-value="0" data-min="1" data-clearable="Effacer" value="<?php echo $market->getRating('user') ?>" /></span>
            </div>
          </div><br/>
          <?php }
?>
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
        <span><?php echo $market->getAuthor(); ?></span><br/>
        <label class="control-label">{{Dernière mise à jour par}}</label><br/>
        <span><?php echo $market->getUpdateBy(); ?></span>
      </div>
      <div class='col-sm-2'>
        <label class="control-label">{{Lien}}</label><br/>
        <?php if ($market->getLink('video') != '' && $market->getLink('video') != 'null') {?>
        <a class="btn btn-default btn-xs" target="_blank" href="<?php echo $market->getLink('video'); ?>"><i class="fa fa-youtube"></i> Video</a><br/>
        <?php }
?>
        <?php if ($market->getLink('forum') != '' && $market->getLink('forum') != 'null') {?>
        <a class="btn btn-default btn-xs" target="_blank" href="<?php echo $market->getLink('forum'); ?>"><i class="fa fa-users"></i> Forum</a><br/>
        <?php }
?>
      </div>
      <div class='col-sm-2'>
       <label class="control-label">{{Nombre d'installation}}</label><br/>
       <span class="marketAttr" data-l1key="nbInstall"></span><br/>
       <label class="control-label">{{Nombre de téléchargements}}</label><br/>
       <span class="marketAttr" data-l1key="downloaded"></span>
     </div>
     <div class='col-sm-1'>
      <label class="control-label">{{Type}}</label><br/>
      <span class="marketAttr" data-l1key="type"></span>
    </div>
    <div class='col-sm-2'>
      <label class="control-label">Langue disponible</label><br/>
      <?php
echo '<img src="core/img/francais.png" width="30" />';
if ($market->getLanguage('en_US') == 1) {
	echo '<img src="core/img/anglais.png" width="30" />';
}
if ($market->getLanguage('de_DE') == 1) {
	echo '<img src="core/img/allemand.png" width="30" />';
}
if ($market->getLanguage('sp_SP') == 1) {
	echo '<img src="core/img/espagnol.png" width="30" />';
}
if ($market->getLanguage('ru_RU') == 1) {
	echo '<img src="core/img/russe.png" width="30" />';
}
if ($market->getLanguage('id_ID') == 1) {
	echo '<img src="core/img/indonesien.png" width="30" />';
}
if ($market->getLanguage('it_IT') == 1) {
	echo '<img src="core/img/italien.png" width="30" />';
}
?>
   </div>
   <div class='col-sm-3'>
     <label class="control-label">{{Dernière mise à jour le}}</label><br/>
     <?php echo $market->getDatetime('stable') ?>
   </div>
 </div>

</div>

<div id="div_comments" title="{{Commentaires}}"></div>

<div id="div_changelog" title="{{Changelog}}"></div>

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

  $('.variable-width').slick({
    dots: true,
    speed: 300,
    accessibility: true,
    infinite: true,
    lazyLoad: 'ondemand',
    slidesToShow: 3,
    slidesToScroll: 1
  });

  $('body').setValues(market_display_info, '.marketAttr');
  if($.isArray(market_display_info.changelog)){
    var nb = 0;
    var html = '';
    for(var i in market_display_info.changelog.reverse()){
      html += '<strong>'+market_display_info.changelog[i].date+'</strong><br/>';
      html += linkify(market_display_info.changelog[i].change);
      html += '<br/><br/>';
      nb++;
      if(nb > 1){
        break;
      }
    }
    $('.marketAttr[data-l1key=changelog]').html(html);
    var html = '';
    for(var i in market_display_info.changelog.reverse()){
     html += '<strong>{{Version}} '+market_display_info.changelog[i].version+' - '+market_display_info.changelog[i].date+'</strong><br/>';
     html += linkify(market_display_info.changelog[i].change);
     html += '<br/><br/>';
   }
   $('#div_changelog').html(html);
 }
 $('.marketAttr[data-l1key=description]').html(linkify(market_display_info.description));
 $('.marketAttr[data-l1key=utilization]').html(linkify(market_display_info.utilization));

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

 $("#div_changelog").dialog({
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

 $("#bt_viewCompleteChangelog").on('click',function(){
  $('#div_changelog').dialog('open');
});


 $('.bt_installFromMarket').on('click', function () {
  var id = $(this).attr('data-market_id');
  var logicalId = $(this).attr('data-market_logicalId');
  jeedom.market.install({
    id: id,
    version: $(this).attr('data-version'),
    error: function (error) {
      $('#div_alertMarketDisplay').showAlert({message: error.message, level: 'danger'});
    },
 success: function (data) { // si l'appel a bien fonctionné
 if(market_display_info.type == 'plugin'){
   bootbox.confirm('{{Voulez vous aller sur la page de configuration de votre nouveau plugin ?}}', function (result) {
     if (result) {
      loadPage('index.php?v=d&p=plugin&id=' + logicalId);
    }
  });
 }
 if ( typeof refreshListAfterMarketObjectInstall == 'function'){
  refreshListAfterMarketObjectInstall()
}
$('#div_alertMarketDisplay').showAlert({message: '{{Objet installé avec succès}}', level: 'success'})
}
});

});

 $('#bt_removeFromMarket').on('click', function () {
  var id = $(this).attr('data-market_id');
  jeedom.market.remove({
    id: id,
    error: function (error) {
      $('#div_alertMarketDisplay').showAlert({message: error.message, level: 'danger'});
    },
 success: function (data) { // si l'appel a bien fonctionné
 $.showLoading();
 window.location.reload();
}
});
});

 $('#in_myRating').on('change', function () {
  var id = $('.marketAttr[data-l1key=id]').value();
  jeedom.market.setRating({
   id: id,
   rating: $(this).val(),
   error: function (error) {
    $('#div_alertMarketDisplay').showAlert({message: error.message, level: 'danger'});
  }
});
});
</script>