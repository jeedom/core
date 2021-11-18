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

<table class="table table-bordered">
  <thead>
    <tr>
      <th>{{Nom}}</th>
      <th>{{Statut}}</th>
      <th>{{Installation}}</th>
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
      <td class="td_lastLaunchDependancy">
        <?php echo $dependancy_info['last_launch'] ?>
      </td>
    </tr>
  </tbody>
</table>

<script>
function refreshDependancyInfo() {
  var nok = false
  jeedom.plugin.getDependancyInfo({
    id : plugin_id,
    success: function (data) {
      switch(data.state) {
        case 'ok':
          $('.dependancyState').empty().append('<span class="label label-success">{{OK}}</span>')
          break
        case 'nok':
          nok = true
          $("#div_plugin_dependancy").closest('.panel').removeClass('panel-success panel-info').addClass('panel-danger')
          $('.dependancyState').empty().append('<span class="label label-danger">{{NOK}}</span>')
          break
        case 'in_progress':
          nok = true
          $("#div_plugin_dependancy").closest('.panel').removeClass('panel-success panel-danger').addClass('panel-info')
          var html = '<span class="label label-primary"><i class="fas fa-spinner fa-spin"></i> {{Installation en cours}}'
          if (isset(data.progression) && data.progression !== '') {
            html += ' - '+data.progression+' %'
          }
          if (isset(data.duration) && data.duration != -1) {
            html += ' - '+data.duration+' min'
          }
          html += '</span>'
          $('.dependancyState').empty().append(html)
          break
        default:
          $('.dependancyState').empty().append('<span class="label label-warning">'+data.state+'</span>')
      }
      $('.td_lastLaunchDependancy').empty().append(data.last_launch)
      if (!nok) {
        $("#div_plugin_dependancy").closest('.panel').removeClass('panel-danger panel-info').addClass('panel-success')
      }
      if (nok) {
        setTimeout(refreshDependancyInfo, 5000)
      }
    }
  })
}
refreshDependancyInfo()

$('.launchInstallPluginDependancy').on('click',function() {
  jeedom.plugin.dependancyInstall({
    id : plugin_id,
    error: function(error) {
      $.fn.showAlert({message: error.message, level: 'danger'})
    },
    success: function(data) {
      $("#div_plugin_dependancy").load('index.php?v=d&modal=plugin.dependancy&plugin_id='+plugin_id)
    }
  })
})
</script>
