<?php
/* This file is part of Jeedom.
*
* Jeedom is free software: you can redistribute it and/or modify
* it under the terms of the GNU General Public License as published by
* the Free Software Foundation, either version 3 of the License, or
* (at your option) any later version.
*
* Jeedom is distributed in the hope that it will be useful,
* but WITHOUT ANY WARRANTY; without even the implied warranty of
* MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE. See the
* GNU General Public License for more details.
*
* You should have received a copy of the GNU General Public License
* along with Jeedom. If not, see <http://www.gnu.org/licenses/>.
*/

if (!isConnect('admin')) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
$hardware = jeedom::getHardwareName();
$goUpgrade = true;
if (system::getDistrib() != 'debian') {
  $system = strtoupper($hardware) . ' - OS non supporté.';
  $alertLevel = 'alert alert-danger';
  $messageAlert = '{{Impossible de vous renseigner sur la compatibilité de votre environnement.}}';
}
else {
	$version = trim(strtolower(file_get_contents('/etc/debian_version')));
  if ($version < '9') {
    $goUpgrade = false;
    $name = 'Jessie ou inférieur';
    $alertLevel = 'alert alert-danger';
    if ($hardware == 'smart') {
      $messageAlert = '{{Votre version de Debian est trop ancienne pour être en mesure d\'utiliser toutes les possibilités offertes par Jeedom dans les meilleures conditions. Veuillez mettre à niveau l\'environnement Linux de votre box Smart en cliquant sur le bouton}} <a class="btn btn-sm btn-danger roundedLeft" href="index.php?v=d&p=migrate"><i class="fab fa-linux"></i> {{Mettre à niveau}}</a>';
    }
    else {
      $messageAlert = '{{Votre version de Debian est trop ancienne pour être en mesure d\'utiliser toutes les possibilités offertes par Jeedom dans les meilleures conditions. Veuillez mettre à jour votre environnement vers Debian Buster avant de procéder à la migration V4.}}';
    }
  }
  else if ($version >= '9' && $version < '10') {
    $name = 'Stretch';
    $alertLevel = 'alert alert-warning';
    if ($hardware == 'smart') {
      $messageAlert = '{{La version de Debian officiellement supportée en V4 est Buster (10.X). Vous aurez accès à une procédure de mise à jour de l\'environnement de votre box Smart en V4.}}';
    }
    else {
    $messageAlert = '{{La version de Debian officiellement supportée en V4 est Buster (10.X). Pensez à mettre à jour votre environnement.}}';
  }
  }
  else if ($version >= '10' && $version < '11') {
    $name = 'Buster';
    $alertLevel = 'alert alert-success';
    $messageAlert = '{{Version de Debian officiellement supportée installée, vous pouvez continuer le processus de migration.}}';
  }
  $system = strtoupper($hardware) . ' - Debian en version '.$version.' ('.$name.')';
}
?>

<div class="row text-center">
  <p class="alert alert-info">{{Après plusieurs mois de développement et d'optimisations, la version 4 de Jeedom est désormais accessible à tous.}}<br> {{Avant d'effectuer la mise à niveau, nous allons découvrir quelques fonctionnalités proposées dans cette nouvelle version et vérifier la compatibilité de votre système et des plugins installés sur votre machine.}}</p>
  <div class="col-md-10 col-md-offset-1 <?= $alertLevel ?>"><strong><?= $system ?></strong><br/><?= $messageAlert ?></div>

  <?php if ($goUpgrade == true) { ?>
    <p>
      <a class="btn btn-warning" id="bt_goUpgradeV4" disabled="true" title="Veuillez lire toute la page avant de lancer la migration V4.">
        <i class="fas fa-level-up-alt"></i> {{Mettre à niveau V4}}
      </a>
</p>
<?php } ?>
</div>

<div class="row">
  <div style="display: none;" class="col-md-10 col-md-offset-1" id="div_alert_upgrade"></div>
  <div style="display: none;" class="col-md-10 col-md-offset-1" id="div_alert_upgrade2"></div>
  <h4 class="col-xs-12 text-center">{{Quoi de neuf en V4}} ?</h4>

  <div class="col-sm-6 col-md-4">
    <div class="panel panel-success" style="height:550px;">
      <div class="panel-heading">
        <h4 class="panel-title">{{3 nouveaux thèmes}}</h4>
      </div>
      <div class="panel-body">
        <div class="text-center">
          <h5>{{3 thèmes font leur apparition}} : <strong>Light, Dark ou Legacy.</strong></h5>
          <p>{{Optez pour un interface qui vous ressemble grâce aux 3 thèmes disponibles en V4. Il est possible de}} <strong>{{changer de thème automatiquement}}</strong> {{selon l'heure de la journée ou encore en fonction de la}} <strong>{{luminosité ambiante sur mobile}}</strong>.</p>
        </div>
      </div>
      <div class="panel-footer">
        <img class="img-responsive" src="core/img/v4Themes.png" alt="Thèmes Jeedom V4">
      </div>
    </div>
  </div>

  <div class="col-sm-6 col-md-4">
    <div class="panel panel-success" style="height:550px;">
      <div class="panel-heading">
        <h4 class="panel-title">{{Outil de création de widgets}}</h4>
      </div>
      <div class="panel-body">
        <div class="text-center">
          <h5>{{Pour toujours plus de}} <strong>{{simplicité et d'évolutivité.}}</strong></h5>
          <p>{{Le core Jeedom prend désormais en charge}} <strong>{{les widgets}}</strong> {{en mettant à disposition}} <strong>{{un nouvel outil}}</strong> {{alliant possibilités de personnalisations et facilité d'utilisation.}}</p>
          <a href="https://blog.jeedom.com/4368-les-widgets-en-v4/" target="blank" class="btn btn-success" role="button">{{En savoir plus}}</a>
          <p class="alert alert-warning" style="margin-top:20px;" >{{Le}} <strong>{{plugin Widgets}}</strong> {{devient par conséquent}} <strong>{{obsolète et doit être désinstallé après migration en V4}}</strong>.
            <br>
            {{Les widgets tiers utilisés en V3 sont susceptibles de ne plus fonctionner comme souhaité et peuvent nécessiter une intervention de votre part.}}</p>
          </div>
        </div>
        <div class="panel-footer">
          <img class="img-responsive" src="core/img/v4Widgets.png" alt="Widgets Jeedom V4">
        </div>
      </div>
    </div>

    <div class="col-sm-6 col-md-4">
      <div class="panel panel-success" style="height:550px;">
        <div class="panel-heading">
          <h4 class="panel-title">{{A venir en V4.1}}</h4>
        </div>
        <div class="panel-body">
          <div class="text-center">
            <h5>{{Une nouvelle page}} <strong>{{Synthèse.}}</strong></h5>
            <p>{{En V4.1, la page}} <strong>{{Synthèse}}</strong> {{vous propose une synthèse visuelle et centralisée de vos objets et de leurs résumés.}}</p>
            <a href="https://doc.jeedom.com/fr_FR/core/4.1/overview" target="blank" class="btn btn-success" role="button">{{En savoir plus}}</a>
          </div>
        </div>
        <div class="panel-footer">
          <img class="img-responsive" src="core/img/img_home.png" alt="Synthèse Jeedom V4.1">
        </div>
      </div>
    </div>
  </div>

  <div class="row" id="v4Compatibility_div">
    <h4 class="text-center">{{Compatibilité des plugins installés}}
      <img src="core/img/logo_v4Compatible.png" alt="" height=30px>
    </h4>
    <div class="col-md-10 col-md-offset-1 table-responsive">
      <table class="table table-condensed table-hover" id="plugins_upgrade">
        <thead>
          <tr>
            <th class="col-xs-2">{{Plugin}}</th>
            <th class="col-xs-1"></th>
            <th class="col-xs-2">{{Auteur}}</th>
            <th class="col-xs-2">{{Version}}</th>
            <th class="text-right col-xs-5">{{Compatibilité V4 confirmée}} ?</th>
          </tr>
        </thead>
        <tbody>
          <!-- populate with js -->
        </tbody>
      </table>
    </div>
  </div>

  <style>
  .certif-badge {
    color:white;
    padding:4px;
    border-radius:5px;
    font-size: 12px;
  }
</style>
<script type="text/javascript">

$('#table_update tbody tr').each(function() {
  let logicalId = $(this).data('logicalid');
  let version = $(this).find('span.label.label-warning').last().html();
  if (logicalId != 'jeedom') {
    $.ajax({
      type: 'POST',
      url: 'core/ajax/repo.ajax.php',
      data: {
        action: 'byLogicalId',
        repo: 'market',
        logicalId: logicalId,
        type: 'plugin',
      },
      dataType: 'json',
      global: false,
      error: function(request, status, error) {
        console.log(request, status, error);
      },
      success: function(data) {
        if (data.state != 'ok') {
          return
        }
        if (data.result['name'] == 'Widgets') {
          $('#plugins_upgrade > tbody').append('<tr class="warning"><td><a href="https://www.jeedom.com/market/index.php?v=d&p=market_display&id=widget" target="blank"><b>Widgets</b></a></td><td class="text-center">' + getBadgeCertification(data.result['certification']) + '</td><td>' + data.result['author'] + '</td><td class="text-capitalize">' + version + '</td><td class="text-right"> {{Obsolète en V4 - à désinstaller après migration}} <i class="icon_orange fas fa-trash-alt"></i></td></tr>');
        }
        else if (data.result['name'] == 'Timeline') {
          $('#plugins_upgrade > tbody').append('<tr id="timeline" class="danger toremove"><td><a href="https://www.jeedom.com/market/index.php?v=d&p=market_display&id=timeline" target="blank"><b>Timeline</b></a></td><td class="text-center"></td><td>' + data.result['author'] + '</td><td class="text-capitalize">' + version + '</td><td class="text-right"> {{Conflit timeline V4 - à désinstaller avant migration}} <i class="icon_red fas fa-trash-alt"></i></td></tr>');
        }
        else if (data.result['certification'] == 'Obsolète') {
          $('#plugins_upgrade > tbody').append('<tr id="' + logicalId + '" class="danger toremove"><td><a href="https://www.jeedom.com/market/index.php?v=d&p=market_display&id=' + logicalId + '" target="blank"><b>' + data.result['name'] + '</b></a></td><td class="text-center"><span style="background-color:#DC0D0B;" class="certif-badge">{{Obsolète}}</span></td><td>' + data.result['author'] + '</td><td class="text-capitalize">' + version + '</td><td class="text-right"> {{Plugin Obsolète - à désinstaller avant migration}} <i class="icon_red fas fa-trash-alt"></i></td></tr>');
        }
        else if (data.result['certification'] == 'Legacy') {
          $('#plugins_upgrade > tbody').append('<tr id="' + logicalId + '" class="info"><td><a href="https://www.jeedom.com/market/index.php?v=d&p=market_display&id=' + logicalId + '" target="blank"><b>' + data.result['name'] + '</b></a></td><td class="text-center"><span style="background-color:#6B6B6B;" class="certif-badge">Legacy</span></td><td>' + data.result['author'] + '</td><td class="text-capitalize">' + version + '</td><td class="text-right"> {{Plugin Legacy - fonctionnement non garanti}} <i class="icon_blue fas fa-question"></i></td></tr>');
        }
        else if (data.result['parameters']["github::stable::version"] == '') {
          $('#plugins_upgrade > tbody').append('<tr class="warning"><td><a href="https://www.jeedom.com/market/index.php?v=d&p=market_display&id=' + logicalId + '" target="blank"><b>' + data.result['name'] + '</b></a></td><td class="text-center"></td><td>' + data.result['author'] + '</td><td class="text-capitalize">' + version + '</td><td class="text-right">{{Ce plugin ne possède pas de version Stable}} <i class="icon_orange fas fa-question"></i></td></tr>');
        }
        else if (data.result['hardwareCompatibility']['v4'] == '1') {
          $('#plugins_upgrade > tbody').append('<tr class="success"><td><a href="https://www.jeedom.com/market/index.php?v=d&p=market_display&id=' + logicalId + '" target="blank"><b>' + data.result['name'] + '</b></a></td><td class="text-center">' + getBadgeCertification(data.result['certification']) + '</td><td>' + data.result['author'] + '</td><td class="text-capitalize">' + version + '</td><td class="text-right"><i class="icon_green fas fa-check"></i></td></tr>');
        }
        else {
          $('#plugins_upgrade > tbody').append('<tr class="danger"><td><a href="https://www.jeedom.com/market/index.php?v=d&p=market_display&id=' + logicalId + '" target="blank"><b>' + data.result['name'] + '</b></a></td><td class="text-center">' + getBadgeCertification(data.result['certification']) + '</td><td>' + data.result['author'] + '</td><td class="text-capitalize">' + version + '</td><td class="text-right"><i class="icon_red fas fa-times"></i></td></tr>');
        }
        countIncompatible();
        countToRemove();
      }
    })
  }
});

function countIncompatible() {
  let count_incompatible = $('#plugins_upgrade >tbody >tr >td .fa-times').length;
  if (count_incompatible == 0) {
    let count_plugins = $('#plugins_upgrade >tbody >tr').length;
    $('#div_alert_upgrade').showAlert({message: '<strong>{{Les}} ' + count_plugins + ' {{plugins installés sur votre box Jeedom ont tous confirmé leur compatibilité avec Jeedom V4.}}</strong> {{Tous vos plugins resteront pleinement fonctionnels après la migration vers Jeedom en version 4.}}<br>{{Consulter la section}} <a href="#"><strong>{{Compatibilité des plugins}}</strong></a> {{pour procéder à la mise à niveau V4.}}' , level: 'success'})
  } else if (count_incompatible == 1) {
    $('#div_alert_upgrade').showAlert({message: '<strong>{{1 seul plugin n\'a pas confirmé sa compatibilité avec Jeedom V4.}}</strong> {{Nous ne pouvons garantir le bon fonctionnement de ce plugin suite à la migration vers Jeedom en version 4.}}<br>{{Consulter la section}} <a href="#"><strong>{{Compatibilité des plugins}}</strong></a> {{avant de procéder à la mise à niveau V4.}}', level: 'warning'})
  } else if (count_incompatible > 1) {
    $('#div_alert_upgrade').showAlert({message: '<strong>'+ count_incompatible + ' {{plugins n\'ont pas confirmé leur compatibilité avec Jeedom V4.}}</strong> {{Nous ne pouvons garantir le bon fonctionnement de ces plugins suite à la migration vers Jeedom en version 4.}}<br>{{Consulter la section}} <a href="#"><strong>{{Compatibilité des plugins}}</strong></a> {{pour en apprendre davantage.}}', level: 'danger'})
  }
}

function countToRemove() {
  let count_toremove = $('#plugins_upgrade >tbody >tr >td i.icon_red.fas.fa-trash-alt').length;
  if (count_toremove == 1) {
    $('#div_alert_upgrade2').showAlert({message: ' <i class="icon_red fas fa-trash-alt"></i> <strong>{{1 plugin est obsolète et doit être désinstallé avant la migration.}}</strong><a id="bt_removePlugins" class="btn btn-danger btn-sm pull-right"><i class="fas fa-trash-alt"></i> {{Supprimer le plugin obsolète}}</a><br>{{Consulter la section}} <a href="#"><strong>{{Compatibilité des plugins}}</strong></a> {{pour en apprendre davantage.}}', level: 'danger'})
  }
  else if (count_toremove > 1) {
    $('#div_alert_upgrade2').showAlert({message: ' <i class="icon_red fas fa-trash-alt"></i> <strong>'+ count_toremove + ' {{plugins sont obsolètes et doivent être désinstallés avant la migration.}}</strong><a id="bt_removePlugins" class="btn btn-danger btn-sm pull-right"><i class="fas fa-trash-alt"></i> {{Supprimer les plugins obsolètes}}</a><br>{{Consulter la section}} <a href="#"><strong>{{Compatibilité des plugins}}</strong></a> {{pour en apprendre davantage.}}', level: 'danger'})
  }
}

function removePlugins() {
  let names = [];
  let ids_array = [];
  $('#plugins_upgrade >tbody >tr').each(function() {
    if ($(this).hasClass('toremove')) {
      names.push($(this).find('td > a > b').text());
      ids_array.push($(this).attr('id'));
    }
  })

  bootbox.confirm('{{Êtes-vous sûr de vouloir supprimer ce(s) plugin(s) obsolète(s) : }}' + names.join(', '),
  function(result){
    if (result) {
      for (let i in ids_array) {
        jeedom.update.remove({
          id: ids_array[i],
          error: function(error) {
            alert_div_plugin_configuration.showAlert({message: error.message, level: 'danger'})
          },
          success: function(data) {
          }
        })
      }
      $('#div_alert_upgrade2').hide();
      $('#plugins_upgrade >tbody >tr').each(function() {
        if ($(this).hasClass('toremove')) {
          $(this).remove();
        }
      })
    }
  })
}

function getBadgeCertification(certifLevel) {
  switch (certifLevel) {
    case 'Officiel':
    return '<span style="background-color:#87C206;" class="certif-badge">' + certifLevel + '</span>';
    break;
    case 'Partenaire':
    return '<span style="background-color:#33CB6A;" class="certif-badge">' + certifLevel + '</span>';
    break;
    case 'Conseillé':
    return '<span style="background-color:#755EED;" class="certif-badge">' + certifLevel + '</span>';
    break;
    case 'Premium':
    return '<span style="background-color:#9B5BB9;" class="certif-badge">' + certifLevel + '</span>';
    break;
    default:
    return '';
    break;
  }
}

$('#div_alert_upgrade, #div_alert_upgrade2').on('click', 'a', function() {
  $('#md_modal')[0].scrollTop = $('#md_modal')[0].scrollHeight;
});

$('#div_alert_upgrade2').on('click', '#bt_removePlugins', function() {
  removePlugins();
});

$('#bt_goUpgradeV4').off('click').on('click', function() {
  if($(this).attr('disabled') != 'disabled') {
    if ($('#plugins_upgrade >tbody >tr >td i.icon_red.fas.fa-trash-alt').length == 0) {
      bootbox.confirm({
        title: '{{Etes-vous certain de vouloir mettre à niveau Jeedom en V4 ?}}',
        message: '<div class="alert alert-success">{{Votre version actuelle de Jeedom ainsi que vos plugins seront sauvegardés avant migration. En cas de besoin il suffit de restaurer la dernière sauvegarde pour revenir en V3.}}</div>',
        callback: function(result){
          if (result) {
            jeedom.config.save({
              configuration: {'core::branch':'V4-stable'},
              error: function (error) {
                $('#div_alert').showAlert({message: error.message, level: 'danger'});
              },
              success: function () {
                $("#md_modal").dialog('close');
                jeedom.update.doAll({
                  options: {'preUpdate': '0', 'backup::before': '1', 'plugins': '1', 'core': '1', 'force': '0',' update::reapply': '0'},
                  error: function (error) {
                    $('#div_alert').showAlert({message: error.message, level: 'danger'});
                  },
                  success: function () {
                    $('a[data-toggle=tab][href="#log"]').click();
                    getJeedomLog(1, 'update');
                  }
                });
              }
            });
          }
        }
      })
    }
    else {
      bootbox.confirm({
        title: '{{Plugins incompatibles trouvés :}}',
        message: '{{Veuillez supprimer le(s) plugin(s) obsolète(s) avant de procéder à la migration.}}',
        buttons: {
          confirm: {
            label: '<i class="fas fa-trash-alt"></i> {{Supprimer le(s) plugin(s) obsolète(s)}}',
            className: 'btn-danger'
          }
        },
        callback: function(result){
          if (result) {
            removePlugins();
          }
        }
      })
    }
  }
});


if($('#md_modal').innerHeight() >= $('#md_modal')[0].scrollHeight) {
  $('#bt_goUpgradeV4').attr({'disabled': false,'title': '{{Cliquez ici pour effectuer la migration vers Jeedom V4.}}'});
}
$('#md_modal').on("scroll", function() {
  if($(this).scrollTop() + $(this).innerHeight() >= $(this)[0].scrollHeight) {
    $('#bt_goUpgradeV4').attr({'disabled': false,'title': '{{Cliquez ici pour effectuer la migration vers Jeedom V4.}}'});
  }
});

</script>
