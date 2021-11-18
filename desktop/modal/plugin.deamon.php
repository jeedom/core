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
$plugin_id = init('plugin_id');
sendVarToJs('plugin_id', $plugin_id);
if (!class_exists($plugin_id)) {
  die();
}
$plugin = plugin::byId($plugin_id);
$deamon_info = $plugin->deamon_info();
if (count($deamon_info) == 0) {
  die();
}
$refresh = array();
?>
<table class="table table-bordered">
  <thead>
    <tr>
      <th>{{Nom}}</th>
      <th>{{Statut}}</th>
      <th>{{Configuration}}</th>
      <th>{{(Re)Démarrer}}</th>
      <th>{{Arrêter}}</th>
      <th>{{Gestion automatique}}</th>
      <th>{{Dernier lancement}}</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td>{{Local}}</td>
      <td class="deamonState">
        <?php
        $refresh[0] = 0;
        switch ($deamon_info['state']) {
          case 'ok':
            echo '<span class="label label-success">{{OK}}</span>';
            break;
          case 'nok':
            echo '<span class="label label-danger">{{NOK}}</span>';
            break;
          default:
            echo '<span class="label label-warning">' . $deamon_info['state'] . '</span>';
            break;
        }
        ?>
      </td>
      <td class="deamonLaunchable">
        <?php
        if (!isset($deamon_info['launchable_message'])) {
          $deamon_info['launchable_message'] = '';
        }
        if (!isset($deamon_info['auto'])) {
          $deamon_info['auto'] = 1;
        }
        switch ($deamon_info['launchable']) {
          case 'ok':
            echo '<span class="label label-success">{{OK}}</span>';
            break;
          case 'nok':
            echo '<span class="label label-danger">{{NOK}}</span> ' . $deamon_info['launchable_message'];
            break;
          default:
            echo '<span class="label label-warning">' . $deamon_info['launchable'] . '</span>';
            break;
        }
        ?>
      </td>
      <td>
        <a class="btn btn-success btn-sm bt_startDeamon" style="position:relative;top:-5px;"><i class="fas fa-play"></i></a>
      </td>
      <td>
        <a class="btn btn-danger btn-sm bt_stopDeamon" style="position:relative;top:-5px;"><i class="fas fa-stop"></i></a>
      </td>
      <td>
        <?php if ($deamon_info['auto'] == 1) {?>
          <a class="btn btn-danger btn-sm bt_changeAutoMode" data-mode="0" style="position:relative;top:-5px;"><i class="fas fa-times"></i> {{Désactiver}}</a>
          <?php } else {?>
            <a class="btn btn-success btn-sm bt_changeAutoMode" data-mode="1" style="position:relative;top:-5px;"><i class="fas fa-magic"></i> {{Activer}}</a>
            <?php }
        ?>
      </td>
      <td class="td_lastLaunchDeamon">
        <?php
        if (isset($deamon_info['last_launch'])) {
          echo $deamon_info['last_launch'];
        }
        ?>
      </td>
    </tr>
  </tbody>
</table>

<?php sendVarToJs('refresh_deamon_info', $refresh); ?>

<script>
var timeout_refreshDeamonInfo = null
function refreshDeamonInfo() {
  var in_progress = true
  var nok = false
  jeedom.plugin.getDeamonInfo({
    id : plugin_id,
    success: function(data) {
      switch(data.state) {
        case 'ok':
          if (data.auto == 1) {
            $('.bt_stopDeamon').show()
          }
          $('.deamonState').empty().append('<span class="label label-success">{{OK}}</span>')
          break
        case 'nok':
          if (data.auto == 1) {
            nok = true
          }
          $('.bt_stopDeamon').hide()
          $('.deamonState').empty().append('<span class="label label-danger">{{NOK}}</span>')
          break;
        default:
          $('.deamonState').empty().append('<span class="label label-warning">'+data.state+'</span>')
      }
      switch(data.launchable) {
        case 'ok':
          $('.bt_startDeamon').show()
          if (data.auto == 1 && data.state == 'ok') {
            $('.bt_stopDeamon').show()
          }
          $('.deamonLaunchable').empty().append('<span class="label label-success">{{OK}}</span>')
          break
        case 'nok':
          if (data.auto == 1) {
            nok = true
          }
          $('.bt_startDeamon').hide()
          $('.bt_stopDeamon').hide()
          $('.deamonLaunchable').empty().append('<span class="label label-danger">{{NOK}}</span> '+data.launchable_message)
          break
        default:
          $('.deamonLaunchable').empty().append('<span class="label label-warning">'+data.state+'</span>')
      }
      $('.td_lastLaunchDeamon').empty().append(data.last_launch)
      if (data.auto == 1) {
        $('.bt_stopDeamon').hide();
        $('.bt_changeAutoMode').removeClass('btn-success').addClass('btn-danger')
        $('.bt_changeAutoMode').attr('data-mode',0)
        $('.bt_changeAutoMode').html('<i class="fas fa-times"></i> {{Désactiver}}')
      } else {
        if (data.launchable == 'ok' && data.state == 'ok') {
          $('.bt_stopDeamon').show()
        }
        $('.bt_changeAutoMode').removeClass('btn-danger').addClass('btn-success')
        $('.bt_changeAutoMode').attr('data-mode',1)
        $('.bt_changeAutoMode').html('<i class="fas fa-magic"></i> {{Activer}}')
      }
      if (!nok) {
        $("#div_plugin_deamon").closest('.panel').removeClass('panel-danger').addClass('panel-success')
      } else {
        $("#div_plugin_deamon").closest('.panel').removeClass('panel-success').addClass('panel-danger')
      }

      if ($("#div_plugin_deamon").is(':visible')) {
        timeout_refreshDeamonInfo = setTimeout(refreshDeamonInfo, 5000)
      }
    }
  })
}
refreshDeamonInfo()

$('.bt_startDeamon').on('click', function() {
  clearTimeout(timeout_refreshDeamonInfo)
  savePluginConfig({
    relaunchDeamon : false,
    success : function() {
      jeedom.plugin.deamonStart({
        id : plugin_id,
        forceRestart: 1,
        error: function(error) {
          $.fn.showAlert({message: error.message, level: 'danger'})
          refreshDeamonInfo()
          timeout_refreshDeamonInfo = setTimeout(refreshDeamonInfo, 5000)
        },
        success:function() {
          refreshDeamonInfo()
          timeout_refreshDeamonInfo = setTimeout(refreshDeamonInfo, 5000)
        }
      })
    }
  })
})

$('.bt_stopDeamon').on('click', function() {
  clearTimeout(timeout_refreshDeamonInfo)
  jeedom.plugin.deamonStop({
    id : plugin_id,
    error: function(error) {
      $.fn.showAlert({message: error.message, level: 'danger'})
      refreshDeamonInfo()
      timeout_refreshDeamonInfo = setTimeout(refreshDeamonInfo, 5000)
    },
    success:function() {
      refreshDeamonInfo()
      timeout_refreshDeamonInfo = setTimeout(refreshDeamonInfo, 5000)
    }
  })
})

$('.bt_changeAutoMode').on('click', function() {
  clearTimeout(timeout_refreshDeamonInfo)
  var mode = $(this).attr('data-mode')
  jeedom.plugin.deamonChangeAutoMode({
    id : plugin_id,
    mode : mode,
    error: function(error) {
      $.fn.showAlert({message: error.message, level: 'danger'})
      refreshDeamonInfo()
      timeout_refreshDeamonInfo = setTimeout(refreshDeamonInfo, 5000)
    },
    success:function() {
      refreshDeamonInfo()
      timeout_refreshDeamonInfo = setTimeout(refreshDeamonInfo, 5000)
    }
  })
})
</script>