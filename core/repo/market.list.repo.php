<?php
  if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
  }

  $type = init('type', null);
  $categorie = init('categorie', null) === '' ? null : init('categorie', null);
  $name = init('name', null) === '' ? null : init('name', null);
  $author = init('author', null) === '' ? null : init('author', null);

  if ($name == 'false') {
    $name = null;
  }

  if ($author == null && $name === null && $categorie === null && init('certification', null) === null && init('cost', null) === null && $type == 'plugin') {
    $default = true;
    $markets = repo_market::byFilter(array(
      'status' => 'stable',
      'type' => 'plugin',
      'timeState' => 'popular',
    ));
    $markets2 = repo_market::byFilter(array(
      'status' => 'stable',
      'type' => 'plugin',
      'timeState' => 'newest',
    ));
    $markets = array_merge($markets, $markets2);
  } else {
    $default = false;
    $markets = repo_market::byFilter(
      array(
        'status' => null,
        'type' => $type,
        'categorie' => $categorie,
        'name' => $name,
        'author' => $author,
        'cost' => init('cost', null),
        'timeState' => init('timeState'),
        'certification' => init('certification', null)
      )
    );
  }

  function buildUrl($_key, $_value) {
    $url = 'index.php?v=d&modal=update.display&';
    foreach ($_GET as $key => $value) {
      if ($_key != $key) {
        $url .= $key . '=' . urlencode($value) . '&';
      }
    }
    if ($_key != '' && $_value != '') {
      $url .= $_key . '=' . urlencode($_value);
    }
    return $url;
  }
?>

<div id="md_marketListRepo" data-modalType="md_marketListRepo">
  <div>
    <form class="form-inline" role="form" onsubmit="return false;">
      <?php if (init('type', 'plugin') == 'plugin') {?>
        <div class="input-group input-group-sm">
          <span class="input-group-btn">
            <a class="btn btn-default bt_pluginFilter roundedLeft <?php echo (init('cost') == 'free') ? 'btn-primary' : '' ?>" data-href="<?php echo buildUrl('cost', 'free'); ?>">{{Gratuit}}
            </a><a class="btn btn-default bt_pluginFilter <?php echo (init('cost') == 'paying') ? 'btn-primary' : '' ?>" data-href="<?php echo buildUrl('cost', 'paying'); ?>">{{Payant}}</a><a class="btn btn-default bt_pluginFilter roundedRight" data-href="<?php echo buildUrl('cost', ''); ?>"><i class="fas fa-times"></i></a>
          </span>
        </div>
      <?php }
      ?>
      <div class="input-group input-group-sm">
        <span class="input-group-btn">
          <a class="btn btn-default bt_pluginFilter roundedLeft <?php echo (init('certification') == 'Officiel') ? 'btn-primary' : '' ?>" data-href="<?php echo buildUrl('certification', 'Officiel'); ?>">{{Officiel}}
          </a><a class="btn btn-default bt_pluginFilter <?php echo (init('certification') == 'Conseillé') ? 'btn-primary' : '' ?>" data-href="<?php echo buildUrl('certification', 'Conseillé'); ?>">{{Conseillé}}
          </a><a class="btn btn-default bt_pluginFilter <?php echo (init('certification') == 'Premium') ? 'btn-primary' : '' ?>" data-href="<?php echo buildUrl('certification', 'Premium'); ?>">{{Premium}}
          </a><a class="btn btn-default bt_pluginFilter <?php echo (init('certification') == 'Partenaire') ? 'btn-primary' : '' ?>" data-href="<?php echo buildUrl('certification', 'Partenaire'); ?>">{{Partenaire}}
          </a><a class="btn btn-default bt_pluginFilter <?php echo (init('certification') == 'Legacy') ? 'btn-primary' : '' ?>" data-href="<?php echo buildUrl('certification', 'Legacy'); ?>">{{Legacy}}
          </a><a class="btn btn-default bt_pluginFilter roundedRight" data-href="<?php echo buildUrl('certification', ''); ?>"><i class="fas fa-times"></i></a>
        </span>
      </div>
      <div class="input-group">
        <span class="input-group-btn">
          <select class="form-control roundedLeft" id="sel_categorie" data-href='<?php echo buildUrl('categorie', ''); ?>'>
            <?php
            if (init('categorie') == '') {
              echo '<option value="" selected>{{Top et nouveautés}}</option>';
            } else {
              echo '<option value="">{{Top et nouveautés}}</option>';
            }

            if ($type !== null && $type != 'plugin') {
              $categories = array();
              foreach (repo_market::distinctCategorie($type) as $id => $category) {
                array_push($categories, array(trim($category), $id));
              }
              sort($categories);

              foreach ($categories as $id => $category) {
                if ($category != '' && is_numeric($id)) {
                  echo '<option value="' . $category . '"';
                  echo (init('categorie') == $category) ? 'selected >' : '>';
                  echo $category;
                  echo '</option>';
                }
              }
            } else {
              global $JEEDOM_INTERNAL_CONFIG;
              $categories = array();
              foreach ($JEEDOM_INTERNAL_CONFIG['plugin']['category'] as $key => $value) {
                array_push($categories, array($value['name'], $key));
              }
              sort($categories);

              foreach ($categories as $cat) {
                echo '<option value="' . $cat[1] . '"';
                echo (init('categorie') == $cat[1]) ? 'selected >' : '>';
                echo $cat[0];
                echo '</option>';
              }
            }
            ?>
          </select>
          <input class="form-control" data-href='<?php echo buildUrl('name', ''); ?>' placeholder="{{Rechercher}}" id="in_search" value="<?php echo $name ?>"/>
          <a class="btn btn-success roundedRight" id="bt_search" data-href='<?php echo buildUrl('name', ''); ?>'><i class="fas fa-search"></i></a>
        </span>
      </div>
      <?php
      if (config::byKey('market::username') != '') {
        echo '<span class="label label-info pull-right">' . config::byKey('market::username');
        try {
          repo_market::test();
          echo ' <i class="fas fa-check"></i>';
        } catch (Exception $e) {
          echo ' <i class="fas fa-times"></i>';
        }
        echo '</span>';
      }
      ?>
    </form>
  </div>

  <?php
  if ($name !== null && strpos($name, '$') !== false) {
    echo '<a class="btn btn-default" id="bt_returnMarketList" style="margin-top : 50px;" data-href=' . buildUrl('name', '') . '><i class="fas fa-arrow-circle-left"></i> {{Retour}}</a>';
  }
  ?>

  <div id="marketMainContainer">
    <?php
    $categorie = '';
    $first = true;
    $nCategory = 0;
    if ($default) {
      echo '<div class="pluginContainer">';
    }

    //marketDisplayCards
    foreach ($markets as $market) {
      $update = update::byLogicalId($market->getLogicalId());
      $category = $market->getCategorie();
      if ($category == '') {
        $category = '{{Aucune}}';
      }
      if ($categorie != $category) {
        $categorie = $category;
        if (!$default) {
          if (!$first) {
            echo '</div>';
          }
          if (isset($JEEDOM_INTERNAL_CONFIG['plugin']['category'][$categorie])) {
            echo '<legend data-category="' . $nCategory . '"><i class="fa ' . $JEEDOM_INTERNAL_CONFIG['plugin']['category'][$categorie]['icon'] . '"></i> ' . ucfirst($JEEDOM_INTERNAL_CONFIG['plugin']['category'][$categorie]['name']) . '</legend>';
          } else {
            echo '<legend data-category="' . $nCategory . '">' . ucfirst($categorie) . '</legend>';
          }
          echo '<div class="pluginContainer" data-category="' . $nCategory . '">';
        }
        $first = false;
        $nCategory++;
      }

      $install = 'notInstall';
      if (!is_object($update)) {
        $install = 'install';
      }


      $dispCard = '';
      $dispCard .= '<div class="marketDisplayCard market cursor ' . $install . '" data-market_id="' . $market->getId() . '" data-market_type="' . $market->getType() . '">';

      switch ($market->getCertification()) {
          case 'Officiel':
              $dispCard .= '<div class="headband"><img src="core/img/pluginBands/band_Officiel.png" /></div>';
              break;
          case 'Conseillé':
              $dispCard .= '<div class="headband"><img src="core/img/pluginBands/band_Conseille.png" /></div>';
              break;
          case 'Legacy':
              $dispCard .= '<div class="headband"><img src="core/img/pluginBands/band_Legacy.png" /></div>';
              break;
          case 'Obsolète':
              $dispCard .= '<div class="headband"><img src="core/img/pluginBands/band_Obsolete.png" /></div>';
              break;
          case 'Premium':
              $dispCard .= '<div class="headband"><img src="core/img/pluginBands/band_Premium.png" /></div>';
              break;
          case 'Partenaire':
              $dispCard .= '<div class="headband"><img src="core/img/pluginBands/band_Partenaire.png" /></div>';
              break;
      }

      if (is_object($update)) {
        $dispCard .= '<span class="installCheck"><i class="fas fa-check"></i></span>';
      }

      $dispCard .= "<br/><center>";
      $default_image = 'core/img/no-image-plugin.png';

      $urlPath = config::byKey('market::address') . '/' . $market->getImg('icon');
      $dispCard .= '<img class="pluginImg lazy" src="' . $default_image . '" data-src="' . $urlPath . '" onerror="this.onerror=null;this.src=&quot;core/img/no-image-plugin.png&quot;;" />';
      $dispCard .= "</center>";

      $dispCard .= '<span class="pluginName">' . $market->getName() . '</span>';

      $dispCard .= '<span class="pluginAuthor"><span style="font-size : 0.8em;">{{par}}</span> ' . $market->getAuthor() . '</span>';

      $note = $market->getRating();
      $dispCard .= '<span class="pluginRating">';
      for ($i = 1; $i < 6; $i++) {
        if ($i <= $note) {
          $dispCard .= '<i class="fa fa-star"></i>';
        } else {
          $dispCard .= '<i class="fa fa-star-o"></i>';
        }
      }
      $dispCard .= '</span>';
      if ($market->getCost() > 0) {
        $dispCard .= '<span class="pluginCost">';
        if ($market->getPurchase() == 1) {
          $dispCard .= ' <i class="fas fa-check-circle"></i>';
        } else  if ($market->getCertification() == 'Premium') {
          $dispCard .= '';
        } else {
          if ($market->getCost() != $market->getRealCost()) {
            $dispCard .= '<span style="text-decoration:line-through;">' . number_format($market->getRealCost(), 2) . ' €</span> ';
          }
          $dispCard .= number_format($market->getCost(), 2) . ' €';
        }
        $dispCard .= '</span>';
      } else {
        $dispCard .= '<span class="pluginNoCost">Gratuit</span>';
      }
      $dispCard .= '</div>';
      echo $dispCard;
    }
    if ($default) {
      echo '</div>';
    }
    ?>
  </div>
</div>

<script>

(function() { // Self Isolation!

  //Lazy loading:
  let options = {
    root: document.querySelector('#md_marketListRepo #marketMainContainer'),
    rootMargin: '0px',
    threshold: 1.0
  }

  let lazyImageObserver = new IntersectionObserver(function(entries, observer) {
    entries.forEach(function(entry) {
      if (entry.isIntersecting) {
        let lazyImage = entry.target
        lazyImage.src = lazyImage.dataset.src
        lazyImage.classList.remove('lazy')
        lazyImageObserver.unobserve(lazyImage)
      }
    })
  })

  let lazyImages = document.querySelectorAll('#md_marketListRepo div.marketDisplayCard img.lazy')
  lazyImages.forEach(function(lazyImage) {
    lazyImageObserver.observe(lazyImage)
  })

  document.getElementById('in_search').addEventListener('keypress', function (event) {
    if (event.which == 13) {
      let content = jeeDialog.get('#in_search', 'content')
      content.load(event.target.getAttribute('data-href') + '&name=' + encodeURI(event.target.value))
    }
  })

  document.getElementById('md_marketListRepo').addEventListener('click', function(event) {
    var _target = null
    if (_target = event.target.closest('.bt_pluginFilter')) {
      let content = jeeDialog.get('#in_search', 'content')
    content.load(_target.getAttribute('data-href'))
      return
    }

    var _target = null
    if (_target = event.target.closest('#bt_search')) {
      let content = jeeDialog.get('#in_search', 'content')
    content.load(_target.getAttribute('data-href') + '&name=' + encodeURI(document.getElementById('in_search').value))
      return
    }

    var _target = null
    if (_target = event.target.closest('#bt_returnMarketList')) {
    let content = jeeDialog.get('#in_search', 'content')
    content.load(_target.getAttribute('data-href'))
      return
    }

    var _target = null
    if (_target = event.target.closest('.marketMultiple')) {
    let content = jeeDialog.get('#in_search', 'content')
    content.load(_target.getAttribute('data-href') + '&name=' + encodeURI('.' + _target.getAttribute('data-market_name')))
      return
    }

    var _target = null
    if (_target = event.target.closest('.bt_installFilter')) {
    document.querySelectorAll('#md_marketListRepo .bt_installFilter').removeClass('btn-primary')
    document.querySelector('#md_marketListRepo .pluginContainer').seen()
    document.querySelectorAll('#md_marketListRepo .market').seen()
    var state = _target.getAttribute('data-state')
    if (state == '1') {
      _target.addClass('btn-primary')
      document.querySelectorAll('#md_marketListRepo .notInstall').unseen()
    }
    if (state == '-1') {
      _target.addClass('btn-primary')
      document.querySelectorAll('#md_marketListRepo .install').unseen()
    }
    document.querySelectorAll('#md_marketListRepo .pluginContainer').forEach(_container => {
      var hasVisible = false
      _container.querySelectorAll('.market').forEach(_mrkt => {
        if (_mrkt.isVisible()) {
          hasVisible = true
        }
      })
      if (hasVisible) {
        document.querySelector('legend[data-category=' + _container.getAttribute('data-category') + ']').seen()
      } else {
        _container.unseen()
        document.querySelector('legend[data-category=' + _container.getAttribute('data-category') + ']').unseen()
      }
    })
      return
    }

    var _target = null
    if (_target = event.target.closest('.market')) {
    jeeDialog.dialog({
          id: 'jee_modal2',
          title: "{{Market}}",
          contentUrl: 'index.php?v=d&modal=update.display&type=' + _target.getAttribute('data-market_type') + '&id=' + _target.getAttribute('data-market_id') + '&repo=market'
      })
      return
    }
  })

  document.getElementById('md_marketListRepo').addEventListener('change', function(event) {
    var _target = null
    if (_target = event.target.closest('#sel_categorie')) {
      let content = jeeDialog.get('#in_search', 'content')
    content.load(_target.getAttribute('data-href') + '&categorie=' + encodeURI(_target.value))
      return
    }
  })

})()
</script>
