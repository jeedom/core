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
$dependancy_info = $plugin->dependancy_info();
?>
<div id="md_pluginDependancy" data-modalType="md_pluginDependancy">
  <table class="table table-bordered">
    <thead>
      <tr>
        <th>{{Nom}}</th>
        <th>{{Statut}}</th>
        <th>{{Installation}}</th>
        <th>{{Gestion automatique}}</th>
        <th>{{Dernière installation}}</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>{{Local}}</td>
        <td class="dependancyState">
          <?php
          switch ($dependancy_info['state']) {
            case 'ok':
              echo '<span class="label label-success">{{OK}}</span>';
              break;
            case 'nok':
              echo '<span class="label label-danger">{{NOK}}</span>';
              break;
            case 'in_progress':
              echo '<span class="label label-primary"><i class="fas fa-spinner fa-spin"></i> {{Installation en cours}}';
              if (isset($dependancy_info['progression']) && $dependancy_info['progression'] !== '') {
                echo ' - ' . $dependancy_info['progression'] . ' %';
              }
              if (isset($dependancy_info['duration']) && $dependancy_info['duration'] != -1) {
                echo ' - ' . $dependancy_info['duration'] . ' min';
              }
              echo '</span>';
              break;
            default:
              echo '<span class="label label-warning">' . $dependancy_info['state'] . '</span>';
              break;
          }
          ?>
        </td>
        <td>
          <a class="btn btn-warning btn-sm launchInstallPluginDependancy" style="position:relative;top:-5px;"><i class="fas fa-bicycle"></i> {{Relancer}}</a>
        </td>
        <td>
          <?php if ($dependancy_info['auto'] == 1) { ?>
            <a class="btn btn-danger btn-sm bt_changeAutoModeDependancy" data-mode="0" style="position:relative;top:-5px;"><i class="fas fa-times"></i> {{Désactiver}}</a>
          <?php } else { ?>
            <a class="btn btn-success btn-sm bt_changeAutoModeDependancy" data-mode="1" style="position:relative;top:-5px;"><i class="fas fa-magic"></i> {{Activer}}</a>
          <?php }
          ?>
        </td>
        <td class="td_lastLaunchDependancy">
          <?php echo $dependancy_info['last_launch'] ?>
        </td>
      </tr>
    </tbody>
  </table>
</div>

<script>
if (!jeeFrontEnd.md_pluginDependancy) {
  jeeFrontEnd.md_pluginDependancy = {
    init: function() {
      this.refreshDependancyInfo()
    },
    refreshDependancyInfo: function() {
      var nok = false
      jeedom.plugin.getDependancyInfo({
        id: plugin_id,
        success: function(data) {
          switch (data.state) {
            case 'ok':
              document.querySelector('#div_plugin_dependancy .dependancyState').empty().insertAdjacentHTML('beforeend', '<span class="label label-success">{{OK}}</span>')
              break
            case 'nok':
              nok = true
              document.getElementById("div_plugin_dependancy").closest('.panel').removeClass('panel-success panel-info').addClass('panel-danger')
              document.querySelector('#div_plugin_dependancy .dependancyState').empty().insertAdjacentHTML('beforeend', '<span class="label label-danger">{{NOK}}</span>')
              break
            case 'in_progress':
              nok = true
              document.getElementById("div_plugin_dependancy").closest('.panel').removeClass('panel-success panel-danger').addClass('panel-info')
              var html = '<span class="label label-primary"><i class="fas fa-spinner fa-spin"></i> {{Installation en cours}}'
              if (isset(data.progression) && data.progression !== '') {
                html += ' - ' + data.progression + ' %'
              }
              if (isset(data.duration) && data.duration != -1) {
                html += ' - ' + data.duration + ' min'
              }
              html += '</span>'
              document.querySelector('#div_plugin_dependancy .dependancyState').empty().insertAdjacentHTML('beforeend', html)
              break
            default:
              document.querySelector('#div_plugin_dependancy .dependancyState').empty().insertAdjacentHTML('beforeend', '<span class="label label-warning">' + data.state + '</span>')
          }
          document.querySelector('.td_lastLaunchDependancy').empty().insertAdjacentHTML('beforeend', data.last_launch)
          let bt = document.querySelector('a.bt_changeAutoModeDependancy')
          if (data.auto == 1) {
            bt.removeClass('btn-success').addClass('btn-danger')
            bt.setAttribute('data-mode', 0)
            bt.innerHTML = '<i class="fas fa-times"></i> {{Désactiver}}'
          } else {
            bt.removeClass('btn-danger').addClass('btn-success')
            bt.setAttribute('data-mode', 1)
            bt.innerHTML = '<i class="fas fa-magic"></i> {{Activer}}'
          }
          if (!nok) {
            document.getElementById("div_plugin_dependancy").closest('.panel').removeClass('panel-danger panel-info').addClass('panel-success')
          }
          if (nok) {
            setTimeout(refreshDependancyInfo, 5000)
          }
        }
      })
    },
  }
}
(function() {// Self Isolation!
  var jeeM = jeeFrontEnd.md_pluginDependancy
  jeeM.init()

  /*Events delegations
  */
  document.getElementById('md_pluginDependancy').addEventListener('click', function(event) {
    var _target = null
    if (_target = event.target.closest('.launchInstallPluginDependancy')) {
      jeedom.plugin.dependancyInstall({
        id: plugin_id,
        error: function(error) {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_pluginDependancy', 'content'),
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          document.getElementById("div_plugin_dependancy").load('index.php?v=d&modal=plugin.dependancy&plugin_id=' + plugin_id)
        }
      })
      return
    }

    if (_target = event.target.closest('.bt_changeAutoModeDependancy')) {
      clearTimeout(timeout_refreshDeamonInfo)
      var mode = _target.getAttribute('data-mode')
      jeedom.plugin.dependancyChangeAutoMode({
        id: plugin_id,
        mode: mode,
        error: function(error) {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_pluginDependancy', 'content'),
            message: error.message,
            level: 'danger'
          })
          jeeFrontEnd.md_pluginDependancy.refreshDependancyInfo()
          timeout_refreshDeamonInfo = setTimeout(refreshDependancyInfo, 5000)
        },
        success: function() {
          jeeFrontEnd.md_pluginDependancy.refreshDependancyInfo()
          timeout_refreshDeamonInfo = setTimeout(refreshDependancyInfo, 5000)
        }
      })
      return
    }
  })

})()
</script>