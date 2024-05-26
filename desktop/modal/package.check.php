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
$result = array();
$result['core'] = system::checkAndInstall(json_decode(file_get_contents(__DIR__ . '/../../install/packages.json'), true));
foreach ((plugin::listPlugin(true, false, false, true)) as $plugin) {
  if (file_exists(__DIR__ . '/../../plugins/' . $plugin . '/plugin_info/packages.json')) {
    $result[$plugin] = system::checkAndInstall(json_decode(file_get_contents(__DIR__ . '/../../plugins/' . $plugin . '/plugin_info/packages.json'), true), false, false, $plugin);
  }
}
$datas = array();
$canFix = false;
foreach ($result as $key => $packages) {
  foreach ($packages as $package => $info) {
    if (!isset($datas[$package])) {
      $datas[$package] = $info;
      $datas[$package]['needBy'] = array($key);
      if ($info['needUpdate']) {
        $canFix = true;
      }
    } else {
      if (isset($datas[$package]['level']) && $info['level'] < $datas[$package]['level']) {
        $datas[$package]['level'] = $info['level'];
      }
      if ($info['status'] != $datas[$package]['status'] && $info['status'] == 0) {
        $datas[$package]['status'] = $info['status'];
        $datas[$package]['fix'] = $info['fix'];
      }
      if ($info['remark'] != '') {
        $datas[$package]['remark'] .= '. ' . $info['remark'];
      }
      if ($info['needUpdate']) {
        $canFix = true;
      }
      $datas[$package]['needBy'][] = $key;
    }
  }
}
ksort($datas);
if (count(system::ps('dpkg ')) > 0 || count(system::ps('apt ')) > 0) {
  echo '<div class="alert alert-danger">{{Attention il y a déjà une installation de package en cours.Cliquez sur le bouton rafraichir jusqu\'a ce que ca soit fini}}</div>';
}
?>

<div id="md_packageCheck" data-modalType="md_packageCheck">
  <div class="input-group pull-right" style="display:inline-flex">
    <span class="input-group-btn">
      <a id="bt_refreshPackage" class="btn btn-sm btn-default roundedLeft"><i class="fas fa-sync"></i> {{Rafraichir}}</a>
      <?php echo '<a class="btn btn-sm btn-warning bt_correctPackage roundedRight' . (!$canFix ? ' disabled' : '') . '" data-package="all"><i class="fas fa-screwdriver"></i> {{Installer tous les packages du core}}</a>'; ?>
    </span>
  </div>
  <br /><br />
  <table id="table_packages" class="table table-condensed">
    <thead>
      <tr>
        <th>{{Package}}</th>
        <th>{{Type}}</th>
        <th>{{Status}}</th>
        <th>{{Obligatoire}}</th>
        <th>{{Voulu par}}</th>
        <th>{{Version}}</th>
        <th>{{Remarque}}</th>
        <th>{{Commande}}</th>
        <th>{{Action}}</th>
      </tr>
    </thead>
    <tbody>
      <?php
      foreach ($datas as $package => $info) {
        $_echo = '';
        $_echo .= '<tr>';
        $_echo .= '<td>';
        $_echo .= $info['name'];
        $_echo .= '</td>';
        $_echo .= '<td>';
        $_echo .= $info['type'];
        $_echo .= '</td>';

        if ($info['status'] == 1) {
          $_echo .= '<td class="alert-success">OK</td>';
        } elseif ($info['status'] == 2) {
          $_echo .= '<td class="alert-success">OK (' . $info['alternative_found'] . ')</td>';
        } elseif ($info['status'] == 3) {
          $_echo .= '<td class="alert-warning">{{Incompatible avec l\'OS}}</td>';
        } else {
          if ($info['needUpdate']) {
            $_echo .= '<td class="alert-warning">{{Mise à jour}}</td>';
          } else {
            $_echo .= '<td class="alert-danger">NOK</td>';
          }
        }

        $_echo .= '<td>';
        if ($info['optional'] == 0) {
          $_echo .= '<span class="label label-warning">{{oui}}</span>';
        } else {
          $_echo .= '<span class="label label-info">{{non}}</span>';
        }
        $_echo .= '</td>';

        $_echo .= '<td>';
        foreach ($info['needBy'] as $value) {
          $_echo .= '<span class="label label-primary" style="display:inline-block;">' . $value . '</span> ';
        }
        $_echo .= '</td>';

        $_echo .= '<td>';
        $_echo .= $info['version'];
        if ($info['needUpdate']) {
          $_echo .= '/' . $info['needVersion'];
        }
        $_echo .= '</td>';

        $_echo .= '<td>';
        $_echo .= $info['remark'];
        $_echo .= '</td>';

        $_echo .= '<td>';
        $_echo .= $info['fix'];
        $_echo .= '</td>';
        $_echo .= '<td>';
        if (!$info['status']) {
          $_echo .= '<a class="btn btn-xs btn-warning bt_correctPackage" data-package="' . $package . '" data-description="' . $info['name'] . ' (' . $package . ') "><i class="fas fa-wrench"></i> {{Corriger}}</a>';
        }
        $_echo .= '</td>';
        $_echo .= '</tr>';
        echo $_echo;
      }
      ?>
    </tbody>
  </table>
</div>

<script>
  document.getElementById('md_packageCheck').addEventListener('click', function(event) {
    var _target = null
    if (_target = event.target.closest('#bt_refreshPackage')) {
      jeeDialog.dialog({
        id: 'jee_modal',
        title: "{{Vérification des packages}}",
        contentUrl: 'index.php?v=d&modal=package.check'
      })
      return
    }

    if (_target = event.target.closest('#md_packageCheck .bt_correctPackage')) {
      if (_target.dataset.package == 'all') {
        var text = '{{Êtes-vous sûr de vouloir installer tous les packages non optionnels ?}}'
      } else {
        var text = '{{Êtes-vous sûr de vouloir installer le package}} ' + _target.dataset.description + ' ?'
      }
      jeeDialog.confirm(text, function(result) {
        if (result) {
          jeedom.systemCorrectPackage({
            package: _target.dataset.package,
            error: function(error) {
              jeedomUtils.showAlert({
                attachTo: jeeDialog.get('#md_packageCheck', 'dialog'),
                message: error.message,
                level: 'danger'
              })
            },
            success: function() {
              jeedomUtils.showAlert({
                attachTo: jeeDialog.get('#md_packageCheck', 'dialog'),
                message: '{{Installation lancée cela peut prendre plusieurs dizaines de minutes.}}',
                level: 'success'
              })
              jeeDialog.dialog({
                id: 'jee_modal2',
                title: "{{Vérification des packages}}",
                contentUrl: 'index.php?v=d&modal=log.display&log=packages'
              })
            }
          })
        }
      })
      return
    }
  })
</script>