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
  
  
  <style>

  
  .marketDisplayContainer {  
      display:flex;
      flex-direction:column;
      align-items:center !important;
      width:100% !important;
      max-width:100% !important;
	}

  .headerPlugin {  
      display:flex;
      width:60%;
      max-width:60%;
	}

 
  .pluginBody{
      display:flex;
      flex-direction:column;
      align-items:center !important;
      width:60%;
      max-width:60%;
      background-color:white;
      border-radius:20px;

  }


.pluginBody:hover{
    box-shadow: 0 10px 20px rgba(0, 0, 0, 0.2), 0 10px 20px rgba(148, 202, 0, 0.5); 
    transition: all 0.3s ease;
}

  .descriptionSpan{
    padding:0 0px !important;
    text-align:start;
    width:60%;
    max-width:60%;

  }

  .divCompatibilityLogos{
    display:flex;
    width:100%;
    max-width:100%; 
    flex-direction:column;
    align-content:flex-start;
    
  }

.divInformationsElement{
  display:flex;
  width:60%;
  max-width:60%;
  justify-content:flex-start;
   margin-bottom:1%;

}


.labelInformationsElement{
  width:20%;
  max-width:25%;
  display:flex;
  justify-content:flex-start;
  margin-left:10%;


  
  
}

.spanInformationsElement{
  width:30%;
  max-width:30%;
  display:flex;
  justify-content:flex-start;
  margin-left:10%;

  
  
}

.legend-section{
  
    border-bottom: 1.5px solid #ccc;
    padding-bottom: 10px !important;
    margin-bottom: 10px;
  
}

  </style>

<div id="md_marketDisplayRepo" data-modalType="md_marketDisplayRepo" class="marketDisplayContainer">
  <div class='row row-overflow headerPlugin'>
        <div class="divLogoPlugin">
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
    <div class="divAuthor" style="margin-left:5%;">
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
              echo '<span id="spanCategory" style="font-size: 1em;font-weight: bold;color:#707070;">' . $market->getCategorie() . '</span>';
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
             echo '<div class="alert alert-warning" id="warningVersion" style="display:none;">{{Votre version actuelle du core ne permet pas d\'installer ce plugin}}</div>';
            } else if ($market->getPrivate() == 1) {
              echo '<div class="alert alert-info">{{Ce plugin est pour le moment privé. Vous devez attendre qu\'il devienne public ou avoir un code pour y accéder}}</div>';
            } else {
              if (config::byKey('market::apikey') != '' || (config::byKey('market::username') != '' && config::byKey('market::password') != '')) {
                $purchase_info = repo_market::getPurchaseInfo();
                if (isset($purchase_info['user_id']) && is_numeric($purchase_info['user_id'])) { ?>
                  <a class="btn btn-default" href='<?php echo config::byKey('market::address'); ?>/index.php?v=d&p=profils' target="_blank"><i class="fa fa-eur"></i> {{Code promo}}</a>
                <?php
                  if ($market->getCertification() !== 'Premium') {
                    echo '<a class="btn btn-default" target="_blank" href="' . config::byKey('market::address') . '/index.php?v=d&p=market_display&id=' . $market->getId() . '"><i class="fa fa-shopping-cart"></i> {{Acheter}}</a>';
                  } else {
                    echo '<a class="btn btn-default" target="_blank" href="mailto:supportpro@jeedom.com"><i class="fa fa-envelope"></i> {{Nous Contacter}}</a>';
                  }
                } else {
                  echo '<a class="btn btn-default buyButtons" target="_blank" href="' . config::byKey('market::address') . '/index.php?v=d&p=market_display&id=' . $market->getId() . '"><i class="fa fa-shopping-cart"></i> {{Acheter}}</a>';
                }
              } else {
                echo '<a class="btn btn-default buyButtons" target="_blank" href="' . config::byKey('market::address') . '/index.php?v=d&p=market_display&id=' . $market->getId() . '"><i class="fa fa-shopping-cart"></i> {{Acheter}}</a>';
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
                       if ($market->getPurchase() == 1 && isset($purchase_info['user_id']) && is_numeric($purchase_info['user_id'])) {
                        echo '<span data-l1key="rating" style="font-size: 1.5em;">{{Plugin deja acheté et/ou inclus dans votre service Pack}}</span>';
                      }else{
                          if ($market->getCost() != $market->getRealCost()) {
                            echo '<span data-l1key="rating" style="font-size: 1em;text-decoration:line-through;">' . number_format($market->getRealCost(), 2) . ' €</span> ';
                          }
                          echo '<span data-l1key="rating" style="font-size: 1.5em;">' . number_format($market->getCost(), 2) . ' € TTC</span>';
                      }        
                  } else {
                    echo '<span data-l1key="rating" style="font-size: 1.5em;">{{Gratuit}}</span>';
                  }
            }
            echo '<br/>';
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

  <div class="pluginBody">
    <div class='row row-overflow' style="width:100%;display:flex;flex-direction:column;">
      <div>
        <legend class="legend-section">{{Description}}
          <br />
        </legend>
             <br />
        <span class="marketAttr descriptionSpan" data-l1key="description"></span>
      </div>
      <div class="divCompatibilityLogos">
        <legend class="legend-section">{{Compatibilité plateforme}}</legend>
        <div style="display:flex;justify-content:space-evenly;">
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
    </div>
          
          
      <div style="display:flex;width:100%;max-width:100%;flex-direction:column">
        <legend class="legend-section">{{Informations}}</legend>

        <div class="divInformationsElement">
          <label class="labelInformationsElement">{{Taille}}</label><br />
          <span class="spanInformationsElement"><?php echo $market->getParameters('size'); ?></span>
            <a class="btn btn-default btn-xs pull-right" target="_blank" href="<?php echo str_replace('#language#', config::byKey('language', 'core', 'fr_FR'), $market->getDoc()) ?>"><i class="fas fa-book"></i> {{Documentation}}</a>
          <a class="btn btn-default btn-xs pull-right" target="_blank" href="<?php echo str_replace('#language#', config::byKey('language', 'core', 'fr_FR'), $market->getChangelog()) ?>"><i class="fas fa-book"></i> {{Changelog}}</a>
        </div>
    <div class="divInformationsElement">
      <?php if (($market->getLink('video') != '' && $market->getLink('video') != 'null') || ($market->getLink('forum') != '' && $market->getLink('forum') != 'null')) { ?>
        <label class="labelInformationsElement">{{Lien}}</label><br />
        <?php if ($market->getLink('video') != '' && $market->getLink('video') != 'null') { ?>
          <a class="btn btn-default btn-xs" target="_blank" href="<?php echo $market->getLink('video'); ?>"><i class="fas fa-youtube"></i> Video</a><br />
        <?php } ?>
        <?php if ($market->getLink('forum') != '' && $market->getLink('forum') != 'null') { ?>
          <a class="btn btn-default btn-xs" target="_blank" href="<?php echo $market->getLink('forum'); ?>"><i class="fas fa-users"></i> Forum</a><br />
        <?php } ?>
      <?php } ?>
    </div>
        <div class="divInformationsElement">
          <label class="labelInformationsElement">{{Installation}}</label>
          <span class="marketAttr spanInformationsElement"><?php echo $market->getNbInstall() ?></span>
        </div>

        <div class="divInformationsElement">
          <label class="labelInformationsElement">{{Type}}</label><br />
          <span class="marketAttr spanInformationsElement" data-l1key="type"></span>
        </div>
        <div class="divInformationsElement">
          <label class="labelInformationsElement">{{Langue disponible}}</label><br />
             <span class="spanInformationsElement">
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
            </span>
        </div>
        <div class="divInformationsElement">
          <label class="labelInformationsElement">{{Dernière mise à jour le}}</label><br />
          <?php echo $market->getDatetime('stable') ?>
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

   
  <?php } ?>
   
 </div> 
</div>

<script>
  (function() { // Self Isolation!

    jeedom.version({
        success: function(version) {
            if (market_display_info.parameters.minJeedomVersion >= version) {
                var installButtons = document.querySelectorAll('.bt_installFromMarket');
                installButtons.forEach(function(installButton) {
                    installButton.style.display = 'none';
                });
              var buyButtons = document.querySelectorAll('.buyButtons');
                     buyButtons.forEach(function(buyButton) {
                          buyButton.style.display = 'none';
                      });
     
              var warningDiv = document.getElementById('warningVersion'); 
              if (warningDiv) {
                  warningDiv.style.display = 'block';
              }
            }
        }
    });

     
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
      const descriptionSpan = modal.querySelector('.marketAttr[data-l1key="description"]');
      descriptionSpan.innerHTML = ''; 
      const descriptionContainer = document.createElement('div');
      descriptionContainer.style.padding = '0 20px';
      descriptionContainer.innerHTML = jeedomUtils.linkify(market_display_info.description);
      descriptionSpan.appendChild(descriptionContainer);
    }
    if (modal.querySelector('.marketAttr[data-l1key="utilization"]')) {
      modal.querySelector('.marketAttr[data-l1key="utilization"]').innerHTML = jeedomUtils.linkify(market_display_info.utilization);
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