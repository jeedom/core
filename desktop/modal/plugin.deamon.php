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

<div id="md_pluginDaemon" data-modalType="md_pluginDaemon">
  <table class="table table-condensed">
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
          <a class="btn btn-success btn-xs bt_startDeamon"><i class="fas fa-play"></i></a>
        </td>
        <td>
          <a class="btn btn-danger btn-xs bt_stopDeamon"><i class="fas fa-stop"></i></a>
        </td>
        <td>
          <?php if ($deamon_info['auto'] == 1) { ?>
            <a class="btn btn-danger btn-xs bt_changeAutoMode" data-mode="0"><i class="fas fa-times"></i> {{Désactiver}}</a>
          <?php } else { ?>
            <a class="btn btn-success btn-xs bt_changeAutoMode" data-mode="1"><i class="fas fa-magic"></i> {{Activer}}</a>
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
</div>
<?php sendVarToJs('refresh_deamon_info', $refresh); ?>

<script>
if (!jeeFrontEnd.md_pluginDaemon) {
  jeeFrontEnd.md_pluginDaemon = {
    timeout_refreshDeamonInfo: null,
    init: function() {
      this.refreshDeamonInfo()
    },
    refreshDeamonInfo: function() {
      clearTimeout(jeeFrontEnd.md_pluginDaemon.timeout_refreshDeamonInfo)
      //No longer shown ?
      if (document.getElementById('md_pluginDaemon') == null) return
      var in_progress = true
      var nok = false
      jeedom.plugin.getDeamonInfo({
        id: plugin_id,
        success: function(data) {
          switch (data.state) {
            case 'ok':
              if (data.auto == 1) {
                document.querySelector('#md_pluginDaemon .bt_stopDeamon').seen()
              }
              document.querySelector('#md_pluginDaemon .deamonState').empty().insertAdjacentHTML('beforeend', '<span class="label label-success">{{OK}}</span>')
              break
            case 'nok':
              if (data.auto == 1) {
                nok = true
              }
              document.querySelector('#md_pluginDaemon .bt_stopDeamon').unseen()
              document.querySelector('#md_pluginDaemon .deamonState').empty().insertAdjacentHTML('beforeend', '<span class="label label-danger">{{NOK}}</span>')
              break;
            default:
              document.querySelector('#md_pluginDaemon .deamonState').empty().insertAdjacentHTML('beforeend', '<span class="label label-warning">' + data.state + '</span>')
          }
          switch (data.launchable) {
            case 'ok':
              document.querySelector('#md_pluginDaemon .bt_startDeamon').seen()
              if (data.auto == 1 && data.state == 'ok') {
                document.querySelector('.bt_stopDeamon').seen()
              }
              document.querySelector('#md_pluginDaemon .deamonLaunchable').empty().insertAdjacentHTML('beforeend', '<span class="label label-success">{{OK}}</span>')
              break
            case 'nok':
              if (data.auto == 1) {
                nok = true
              }
              document.querySelector('#md_pluginDaemon .bt_startDeamon').unseen()
              document.querySelector('#md_pluginDaemon .bt_stopDeamon').unseen()
              document.querySelector('#md_pluginDaemon .deamonLaunchable').empty().insertAdjacentHTML('beforeend', '<span class="label label-danger">{{NOK}}</span> ' + data.launchable_message)
              break
            default:
              document.querySelector('#md_pluginDaemon .deamonLaunchable').empty().insertAdjacentHTML('beforeend', '<span class="label label-warning">' + data.state + '</span>')
          }
          document.querySelector('#md_pluginDaemon .td_lastLaunchDeamon').empty().append(data.last_launch)
          if (data.auto == 1) {
            document.querySelector('#md_pluginDaemon .bt_stopDeamon').unseen()
            document.querySelector('#md_pluginDaemon .bt_changeAutoMode').removeClass('btn-success').addClass('btn-danger')
            document.querySelector('#md_pluginDaemon .bt_changeAutoMode').setAttribute('data-mode', 0)
            document.querySelector('#md_pluginDaemon .bt_changeAutoMode').innerHTML = '<i class="fas fa-times"></i> {{Désactiver}}'
          } else {
            if (data.launchable == 'ok' && data.state == 'ok') {
              document.querySelector('#md_pluginDaemon .bt_stopDeamon').seen()
            }
            document.querySelector('#md_pluginDaemon .bt_changeAutoMode').removeClass('btn-danger').addClass('btn-success')
            document.querySelector('#md_pluginDaemon .bt_changeAutoMode').setAttribute('data-mode', 1)
            document.querySelector('#md_pluginDaemon .bt_changeAutoMode').innerHTML = '<i class="fas fa-magic"></i> {{Activer}}'
          }
          if (!nok) {
            document.getElementById('div_plugin_deamon').closest('.panel').removeClass('panel-danger').addClass('panel-success')
          } else {
            document.getElementById('div_plugin_deamon').closest('.panel').removeClass('panel-success').addClass('panel-danger')
          }

          if (document.getElementById('div_plugin_deamon').isVisible()) {
            jeeFrontEnd.md_pluginDaemon.timeout_refreshDeamonInfo = setTimeout(jeeFrontEnd.md_pluginDaemon.refreshDeamonInfo, 5000)
          }
        }
      })
    },
  }
}
(function() {// Self Isolation!
  var jeeM = jeeFrontEnd.md_pluginDaemon
  jeeM.init()

  /*Events delegations
  */
  document.getElementById('md_pluginDaemon').addEventListener('click', function(event) {
    var _target = null
    if (_target = event.target.closest('.bt_startDeamon')) {
      clearTimeout(jeeFrontEnd.md_pluginDaemon.timeout_refreshDeamonInfo)
      jeeFrontEnd.plugin.savePluginConfig({
        relaunchDeamon: false,
        success: function() {
          jeedom.plugin.deamonStart({
            id: plugin_id,
            forceRestart: 1,
            error: function(error) {
              jeedomUtils.showAlert({
                attachTo: jeeDialog.get('#md_pluginDaemon', 'dialog'),
                message: error.message,
                level: 'danger'
              })
              jeeFrontEnd.md_pluginDaemon.refreshDeamonInfo()
              jeeFrontEnd.md_pluginDaemon.timeout_refreshDeamonInfo = setTimeout(jeeFrontEnd.md_pluginDaemon.refreshDeamonInfo, 5000)
            },
            success: function() {
              jeeFrontEnd.md_pluginDaemon.refreshDeamonInfo()
              jeeFrontEnd.md_pluginDaemon.timeout_refreshDeamonInfo = setTimeout(jeeFrontEnd.md_pluginDaemon.refreshDeamonInfo, 5000)
            }
          })
        }
      })
      return
    }

    if (_target = event.target.closest('.bt_stopDeamon')) {
      clearTimeout(jeeFrontEnd.md_pluginDaemon.timeout_refreshDeamonInfo)
      jeedom.plugin.deamonStop({
        id: plugin_id,
        error: function(error) {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_pluginDaemon', 'dialog'),
            message: error.message,
            level: 'danger'
          })
          jeeFrontEnd.md_pluginDaemon.refreshDeamonInfo()
          jeeFrontEnd.md_pluginDaemon.timeout_refreshDeamonInfo = setTimeout(jeeFrontEnd.md_pluginDaemon.refreshDeamonInfo, 5000)
        },
        success: function() {
          jeeFrontEnd.md_pluginDaemon.refreshDeamonInfo()
          jeeFrontEnd.md_pluginDaemon.timeout_refreshDeamonInfo = setTimeout(jeeFrontEnd.md_pluginDaemon.refreshDeamonInfo, 5000)
        }
      })
      return
    }

    if (_target = event.target.closest('.bt_changeAutoMode')) {
      clearTimeout(jeeFrontEnd.md_pluginDaemon.timeout_refreshDeamonInfo)
      var mode = _target.getAttribute('data-mode')
      jeedom.plugin.deamonChangeAutoMode({
        id: plugin_id,
        mode: mode,
        error: function(error) {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_pluginDaemon', 'dialog'),
            message: error.message,
            level: 'danger'
          })
          jeeFrontEnd.md_pluginDaemon.refreshDeamonInfo()
          jeeFrontEnd.md_pluginDaemon.timeout_refreshDeamonInfo = setTimeout(jeeFrontEnd.md_pluginDaemon.refreshDeamonInfo, 5000)
        },
        success: function() {
          jeeFrontEnd.md_pluginDaemon.refreshDeamonInfo()
          jeeFrontEnd.md_pluginDaemon.timeout_refreshDeamonInfo = setTimeout(jeeFrontEnd.md_pluginDaemon.refreshDeamonInfo, 5000)
        }
      })
      return
    }
  })

})()
</script>