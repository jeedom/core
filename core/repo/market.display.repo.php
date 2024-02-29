<?php
if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}

if (init('id') != '') {
  $market = repo_market::byId(init('id'));
}
if (init('logicalId') != '' && init('type') != '') {
  $market = repo_market::byLogicalIdAndType(init('logicalId'), init('type'));
}
if (!isset($market)) {
  throw new Exception('404 not found');
}

include_file('3rdparty', 'fslightbox/fslightbox', 'js');

$market_array = utils::o2a($market);
$market_array['rating'] = $market->getRating();
$update = update::byLogicalId($market->getLogicalId());
sendVarToJS('market_display_info', $market_array);
?>

<div id="md_marketDisplayRepo" data-modalType="md_marketDisplayRepo">
  <div class='row row-overflow' style='padding-top: 10px; padding-bottom: 10px;'>
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
          case 'script':
            $default_image = 'core/img/no-image-script.png';
            break;
        }
        $urlPath = config::byKey('market::address') . '/' . $market->getImg('icon');
        echo '<img src="' . $urlPath . '" class="img-responsive" style="height : 200px;" onerror="this.onerror=null;this.src=&quot;core/img/no-image-plugin.png&quot;;"/>';
        ?>
      </center>
    </div>
    <div class='col-sm-8'>
      <input class="form-control marketAttr" data-l1key="id" style="display: none;">
      <span class="marketAttr" data-l1key="name" placeholder="{{Nom}}" style="font-size: 3em;font-weight: bold;"></span>
      <br />
      <span class="span_author cursor" style="font-size: 1.5em;font-weight: bold;color:#707070;" data-author="<?php echo $market->getAuthor(); ?>">{{Développé par}} <?php echo $market->getAuthor(); ?></span><br />
      <?php
      if ($market->getCertification() == 'Officiel') {
        echo '<span style="font-size : 1.5em;color:#707070">Officiel</span><br/>';
      }
      if ($market->getCertification() == 'Conseillé') {
        echo '<span style="font-size: 1.5em;font-weight: bold;color:#707070;">{{Conseillé}}</span><br/>';
      }
      if ($market->getCertification() == 'Legacy') {
        echo '<span style="font-size: 1.5em;font-weight: bold;color:#6b6b6b;">{{Legacy}}</span><br/>';
      }
      if ($market->getCertification() == 'Obsolète') {
        echo '<span style="font-size: 1.5em;font-weight: bold;color:#e74c3c;">{{Obsolète}}</span><br/>';
      }
      if ($market->getCertification() == 'Premium') {
        echo '<span style="font-size : 1.5em;color:#9b59b6">{{Premium}}</span><br/>';
      }
      if ($market->getCertification() == 'Partenaire') {
        echo '<span style="font-size : 1.5em;color:#2ecc71">{{Partenaire}}</span><br/>';
      }
      global $JEEDOM_INTERNAL_CONFIG;
      if (isset($JEEDOM_INTERNAL_CONFIG['plugin']['category'][$market->getCategorie()])) {
        echo '<span style="font-size: 1em;font-weight: bold;color:#707070;"><i class="fa ' . $JEEDOM_INTERNAL_CONFIG['plugin']['category'][$market->getCategorie()]['icon'] . '"></i> ' . $JEEDOM_INTERNAL_CONFIG['plugin']['category'][$market->getCategorie()]['name'] . '</span>';
        sendVarToJS('market_display_info_category', $JEEDOM_INTERNAL_CONFIG['plugin']['category'][$market->getCategorie()]['name']);
      } else {
        echo '<span style="font-size: 1em;font-weight: bold;color:#707070;">' . $market->getCategorie() . '</span>';
        sendVarToJS('market_display_info_category', $market->getCategorie());
      }
      ?>
      <br /><br />
      <?php
      if ($market->getPurchase() == 1) {
        $allowVersion = $market->getAllowVersion();
        foreach ($allowVersion as $branch) {
          if ($market->getStatus($branch) == 1) {
            echo ' <a class="btn btn-default bt_installFromMarket" data-version="' . $branch . '" data-market_logicalId="' . $market->getLogicalId() . '" data-market_id="' . $market->getId() . '" ><i class="fas fa-plus-circle"></i> {{Installer}} ' . $branch . '</a>';
          }
        }
      } else if ($market->getPrivate() == 1) {
        echo '<div class="alert alert-info">{{Ce plugin est pour le moment privé. Vous devez attendre qu\'il devienne public ou avoir un code pour y accéder}}</div>';
      } else {
        if (config::byKey('market::apikey') != '' || (config::byKey('market::username') != '' && config::byKey('market::password') != '')) {
          $purchase_info = repo_market::getPurchaseInfo();
          if (isset($purchase_info['user_id']) && is_numeric($purchase_info['user_id'])) {
      ?>
            <a class="btn btn-default" href='<?php echo config::byKey('market::address'); ?>/index.php?v=d&p=profils' target="_blank"><i class="fa fa-eur"></i> {{Code promo}}</a>
        <?php
            if ($market->getCertification() !== 'Premium') {
              echo '<a class="btn btn-default" target="_blank" href="' . config::byKey('market::address') . '/index.php?v=d&p=market_display&id=' . $market->getId() . '"><i class="fa fa-shopping-cart"></i> {{Acheter}}</a>';
            } else {
              echo '<a class="btn btn-default" target="_blank" href="mailto:supportpro@jeedom.com"><i class="fa fa-envelope"></i> {{Nous Contacter}}</a>';
            }
          } else {
            echo '<a class="btn btn-default" target="_blank" href="' . config::byKey('market::address') . '/index.php?v=d&p=market_display&id=' . $market->getId() . '"><i class="fa fa-shopping-cart"></i> {{Acheter}}</a>';
          }
        } else {
          echo '<a class="btn btn-default" target="_blank" href="' . config::byKey('market::address') . '/index.php?v=d&p=market_display&id=' . $market->getId() . '"><i class="fa fa-shopping-cart"></i> {{Acheter}}</a>';
        }
      }
      if (is_object($update)) {
        ?>
        <a class="btn btn-danger" style="color : white;" id="bt_removeFromMarket" data-market_id="<?php echo $market->getId(); ?>"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
      <?php }
      ?>
      <br /><br />
      <?php
      if ($market->getCertification() == 'Premium') {
        echo '<span data-l1key="rating" style="font-size: 1.5em;">{{Nous Contacter}}</span>';
      } else {
        if ($market->getCost() > 0) {
          if ($market->getCost() != $market->getRealCost()) {
            echo '<span data-l1key="rating" style="font-size: 1em;text-decoration:line-through;">' . number_format($market->getRealCost(), 2) . ' €</span> ';
          }
          echo '<span data-l1key="rating" style="font-size: 1.5em;">' . number_format($market->getCost(), 2) . ' € TTC</span>';
        } else {
          echo '<span data-l1key="rating" style="font-size: 1.5em;">{{Gratuit}}</span>';
        }
      }
      ?>
    </div>
  </div>
  <?php
  if ($market->getCertification() != 'Officiel' && $market->getCertification() != 'Premium' && $market->getCertification() != 'Legacy' && $market->getCertification() != 'Partenaire') {
    echo '<div class="alert alert-warning">{{Attention ce plugin n\'est pas un plugin officiel en cas de soucis avec celui-ci (direct ou indirect) toute demande de support peut être refusée}}</div>';
  }
  if ($market->getStatus('stable') == 0) {
    echo '<div class="alert alert-warning">{{Attention ce plugin n\'est disponible qu\'en beta, il peut donc avoir de nombreux bugs et vous perdrez toute possibilité de demande de support (quel que soit le plugin) en l\'installant}}</div>';
  }
  $compatibilityHardware = $market->getHardwareCompatibility();
  if (is_array($compatibilityHardware) && count($compatibilityHardware) > 0 && isset($compatibilityHardware[jeedom::getHardwareName()]) && $compatibilityHardware[jeedom::getHardwareName()] != 1) {
    echo '<div class="alert alert-danger">{{Attention ce plugin ne semble pas être compatible avec votre système}}</div>';
  }
  ?>

  <?php
  $mbState = config::byKey('mbState');
  if ($mbState == 0) {
    if (count($market->getImg('screenshot')) > 0) {
  ?>
      <section class="slider-wrapper">
        <button class="slide-arrow" id="slide-arrow-prev">&#8249;</button>
        <button class="slide-arrow" id="slide-arrow-next">&#8250;</button>
        <ul class="slides-container" id="slides-container">
          <?php
          foreach ($market->getImg('screenshot') as $screenshot) {
            $scrsht = '<li class="slide" >';
            $scrsht .= '<a class="cursor" data-type="image" data-fslightbox="gallery" href="' . config::byKey('market::address') . '/' . $screenshot . '" rel="group" >';
            $scrsht .= '<img src="' . config::byKey('market::address') . '/' . $screenshot . '"/>';
            $scrsht .= '</a>';
            $scrsht .= '</li>';
            echo $scrsht;
          }
          ?>
        </ul>
      </section>

    <?php }
    ?>

    <div class='row row-overflow'>
      <div class='col-sm-6'>
        <legend>{{Description}}
          <a class="btn btn-default btn-xs pull-right" target="_blank" href="<?php echo str_replace('#language#', config::byKey('language', 'core', 'fr_FR'), $market->getDoc()) ?>"><i class="fas fa-book"></i> {{Documentation}}</a>
          <a class="btn btn-default btn-xs pull-right" target="_blank" href="<?php echo str_replace('#language#', config::byKey('language', 'core', 'fr_FR'), $market->getChangelog()) ?>"><i class="fas fa-book"></i> {{Changelog}}</a>
          <br />
        </legend>
        <span class="marketAttr" data-l1key="description" style="word-wrap: break-word;white-space: -moz-pre-wrap;white-space: pre-wrap;"></span>
      </div>
      <div class='col-sm-6'>
        <legend>{{Compatibilité plateforme}}</legend>
        <?php
        if ($market->getHardwareCompatibility('v4') == 1) {
          echo '<img src="core/img/logo_market_v4.png" style="width:60px;height:60px;" />';
        }
        if ($market->getHardwareCompatibility('diy') == 1) {
          echo '<img src="core/img/logo_diy.png" style="width:60px;height:60px;" />';
        }
        if ($market->getHardwareCompatibility('rpi') == 1) {
          echo '<img src="core/img/logo_rpi12.png" style="width:60px;height:60px;" />';
        }
        if ($market->getHardwareCompatibility('docker') == 1) {
          echo '<img src="core/img/logo_docker.png" style="width:60px;height:60px;" />';
        }
        if ($market->getHardwareCompatibility('miniplus') == 1) {
          echo '<img src="core/img/logo_jeedomboard.png" style="width:60px;height:60px;" />';
        }
        ?>
      </div>
    </div>
    <br />
    <div class='row row-overflow'>
      <div class='col-sm-6'>
        <legend>Avis</legend>
        <div class='row'>
          <div class='col-sm-6'>
            <center>
              <span class="marketAttr" data-l1key="rating" style="font-size: 4em;"></span>/5
            </center>
          </div>
        </div>
      </div>
      <div class='col-sm-6'>
        <legend>{{Utilisation}}</legend>
        <span class="marketAttr" data-l1key="utilization" style="word-wrap: break-word;white-space: -moz-pre-wrap;white-space: pre-wrap;"></span>
      </div>
    </div>
    <br />

    <div class='row row-overflow'>
      <div class="col-sm-12">
        <legend>{{Informations complementaires}}</legend>

        <div class='col-sm-2'>
          <label class="control-label">{{Taille}}</label><br />
          <span><?php echo $market->getParameters('size'); ?></span>
        </div>
        <div class='col-sm-2'>
          <label class="control-label">{{Lien}}</label><br />
          <?php if ($market->getLink('video') != '' && $market->getLink('video') != 'null') { ?>
            <a class="btn btn-default btn-xs" target="_blank" href="<?php echo $market->getLink('video'); ?>"><i class="fas fa-youtube"></i> Video</a><br />
          <?php }
          ?>
          <?php if ($market->getLink('forum') != '' && $market->getLink('forum') != 'null') { ?>
            <a class="btn btn-default btn-xs" target="_blank" href="<?php echo $market->getLink('forum'); ?>"><i class="fas fa-users"></i> Forum</a><br />
          <?php }
          ?>
        </div>
        <div class='col-sm-2'>
          <label class="control-label">{{Installation}}</label>
          <span class="marketAttr"><?php echo $market->getNbInstall() ?></span>
        </div>

        <div class='col-sm-1'>
          <label class="control-label">{{Type}}</label><br />
          <span class="marketAttr" data-l1key="type"></span>
        </div>
        <div class='col-sm-2'>
          <label class="control-label">{{Langue disponible}}</label><br />
          <?php
          echo '<img src="core/img/langFlags/francais.png" width="30" />';
          if ($market->getLanguage('en_US') == 1) {
            echo '<img src="core/img/langFlags/anglais.png" width="30" />';
          }
          if ($market->getLanguage('de_DE') == 1) {
            echo '<img src="core/img/langFlags/allemand.png" width="30" />';
          }
          if ($market->getLanguage('es_ES') == 1) {
            echo '<img src="core/img/langFlags/espagnol.png" width="30" />';
          }
          if ($market->getLanguage('it_IT') == 1) {
            echo '<img src="core/img/langFlags/italien.png" width="30" />';
          }
          ?>
        </div>
        <div class='col-sm-3'>
          <label class="control-label">{{Dernière mise à jour le}}</label><br />
          <?php echo $market->getDatetime('stable') ?>
        </div>
      </div>
    </div>
  <?php } ?>
</div>

<script>
  (function() { // Self Isolation!

    //Slide screenshot:
    if (document.querySelector(".slide")) {
      document.getElementById("slide-arrow-next")?.addEventListener("click", (event) => {
        document.getElementById("slides-container").scrollLeft += document.querySelector(".slide").clientWidth
      })
      document.getElementById("slide-arrow-prev")?.addEventListener("click", (event) => {
        document.getElementById("slides-container").scrollLeft -= document.querySelector(".slide").clientWidth
      })
    }

    document.getElementById('md_marketDisplayRepo').setJeeValues(market_display_info, '.marketAttr')
    let modal = jeeDialog.get('#md_marketDisplayRepo', 'dialog')
    modal.querySelector('.title').textContent = 'Market - ' + market_display_info_category
    if (modal.querySelector('.marketAttr[data-l1key="description"]')) {
      modal.querySelector('.marketAttr[data-l1key="description"]').innerHTML = jeedomUtils.linkify(market_display_info.description)
    }
    if (modal.querySelector('.marketAttr[data-l1key="utilization"]')) {
      modal.querySelector('.marketAttr[data-l1key="utilization"]').innerHTML = jeedomUtils.linkify(market_display_info.utilization)
    }

    document.getElementById('md_marketDisplayRepo').addEventListener('click', function(event) {
      var _target = null
      if (_target = event.target.closest('#bt_paypalClick')) {
        _target.unseen()
        return
      }

      if (_target = event.target.closest('.bt_installFromMarket')) {
        var id = _target.getAttribute('data-market_id')
        var logicalId = _target.getAttribute('data-market_logicalId')
        jeedom.repo.install({
          id: id,
          repo: 'market',
          version: _target.getAttribute('data-version'),
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function(data) {
            if (market_display_info.type == 'plugin') {
              jeeDialog.confirm('{{Voulez-vous aller sur la page de configuration de votre nouveau plugin ?}}', function(result) {
                if (result) {
                  jeedomUtils.loadPage('index.php?v=d&p=plugin&id=' + logicalId)
                }
              })
            }
            if (typeof refreshListAfterMarketObjectInstall == 'function') {
              refreshListAfterMarketObjectInstall()
            }
            jeedomUtils.showAlert({
              message: '{{Plugin installé avec succès}}',
              level: 'success'
            })
          }
        })
        return
      }

      if (_target = event.target.closest('#bt_removeFromMarket')) {
        var id = _target.getAttribute('data-market_id')
        jeedom.repo.remove({
          id: id,
          repo: 'market',
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          },
          success: function(data) {
            domUtils.showLoading();
            window.location.reload();
          }
        })
        return
      }

      if (_target = event.target.closest('.span_author')) {
        jeeDialog.dialog({
          id: 'jee_modal',
          title: "{{Market}}",
          contentUrl: 'index.php?v=d&modal=update.list&type=plugin&repo=market&author=' + encodeURI(_target.getAttribute('data-author'))
        })
        return
      }
    })

    document.getElementById('md_marketDisplayRepo').addEventListener('change', function(event) {
      var _target = null
      if (_target = event.target.closest('#in_myRating')) {
        var id = document.querySelector('#md_marketDisplayRepo .marketAttr[data-l1key="id"]').jeeValue()
        jeedom.repo.setRating({
          id: id,
          repo: 'market',
          rating: _target.value,
          error: function(error) {
            jeedomUtils.showAlert({
              message: error.message,
              level: 'danger'
            })
          }
        })
        return
      }
    })

  })()
</script>
