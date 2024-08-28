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

$plan3dHeader = plan3dHeader::byId(init('plan3dHeader_id'));
if (!is_object($plan3dHeader)) {
  throw new Exception('{{Impossible de trouver le design 3D}}');
}
sendVarToJS([
  'jeephp2js.md_plan3dHeaderConfigure_Id' => $plan3dHeader->getId(),
  'jeephp2js.md_plan3dHeaderConfigure_plan3dHeader' => utils::o2a($plan3dHeader)
]);
?>

<div id="md_plan3dHeaderConfigure" data-modaltype="md_plan3dHeaderConfigure">
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#main" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-cog"></i> {{Général}}</a></li>
    <li role="presentation"><a href="#components" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-cubes"></i> {{Composants}}</a></li>
    <div class="input-group pull-right" style="display:inline-flex;">
      <span class="input-group-btn">
        <a class='btn btn-success btn-sm roundedLeft' id='bt_saveConfigureplan3dHeader'><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a>
        <a class='btn btn-danger btn-sm roundedRight' id='bt_removeConfigureplan3dHeader'><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
      </span>
    </div>
  </ul>
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="main">
      <div id="div_plan3dHeaderConfigure">
        <form class="form-horizontal">
          <fieldset>
            <input type="text" class="plan3dHeaderAttr form-control" data-l1key="id" style="display: none;" />
            <div class="form-group">
              <label class="col-lg-4 control-label">{{Nom}}</label>
              <div class="col-lg-2">
                <input class="plan3dHeaderAttr form-control" data-l1key="name" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-4 control-label">{{Position}}</label>
              <div class="col-lg-2">
                <input type="number" class="plan3dHeaderAttr form-control" data-l1key="order" min="0" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-4 control-label">{{Code d'accès}}</label>
              <div class="col-lg-2">
                <input class="plan3dHeaderAttr form-control inputPassword" data-l1key="configuration" data-l2key="accessCode" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-4 control-label">{{Icône}}</label>
              <div class="col-lg-1">
                <a class="btn btn-default btn-sm" id="bt_chooseIcon"><i class="fas fa-flag"></i> {{Choisir}}</a>
              </div>
              <div class="col-lg-2">
                <div class="plan3dHeaderAttr" data-l1key="configuration" data-l2key="icon"></div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-4 control-label">{{Modèle 3D}}</label>
              <div class="col-lg-8">
                <span class="btn btn-default btn-file">
                  <i class="fas fa-cloud-upload-alt"></i> {{Envoyer}}<input id="bt_upload3dModel" type="file" name="file" style="display: inline-block;">
                </span>
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-4 control-label">{{Couleur du fond}}</label>
              <div class="col-lg-2">
                <input type="color" class="plan3dHeaderAttr form-control" data-l1key="configuration" data-l2key="backgroundColor" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-4 control-label">{{Puissance éclairage général (Défaut : 0.4)}}</label>
              <div class="col-lg-2">
                <input type="number" min="0" step="0.1" class="plan3dHeaderAttr form-control" data-l1key="configuration" data-l2key="globalLightPower" />
              </div>
            </div>
          </fieldset>
        </form>
      </div>
    </div>
    <div role="tabpanel" class="tab-pane" id="components">
      <form class="form-horizontal">
        <fieldset>
          <table class="table table-condensed">
            <thead>
              <tr>
                <th>{{ID}}</th>
                <th>{{Nom object}}</th>
                <th>{{Type}}</th>
                <th>{{ID du lien}}</th>
                <th>{{Action}}</th>
              </tr>
            </thead>
            <tbody>
              <?php
              foreach (($plan3dHeader->getPlan3d()) as $plan3d) {
                echo '<tr  class="plan" data-id="' . $plan3d->getId() . '">';
                echo '<td>';
                echo $plan3d->getId();
                echo '</td>';
                echo '<td>';
                echo $plan3d->getName();
                echo '</td>';
                echo '<td>';
                echo $plan3d->getLink_type();
                echo '</td>';
                echo '<td>';
                echo jeedom::toHumanReadable($plan3d->getLink_id());
                echo '</td>';
                echo '<td>';
                echo '<a class="btn btn-danger btn-xs bt_removePlan3dComposant pull-right"><i class="fas fa-trash"></i> {{Supprimer}}</a>';
                echo '<a class="btn btn-default btn-xs bt_configurePlan3dComposant pull-right"><i class="fas fa-cog"></i> {{Configuration}}</a>';
                echo '</td>';
                echo '</tr>';
              }
              ?>
            </tbody>
          </table>
        </fieldset>
      </form>
    </div>
  </div>
</div>

<script>
if (!jeeFrontEnd.md_plan3dHeaderConfigure) {
  jeeFrontEnd.md_plan3dHeaderConfigure = {
    init: function(_cmdIds) {
      if (isset(jeephp2js.md_plan3dHeaderConfigure_Id) && jeephp2js.md_plan3dHeaderConfigure_Id != '') {
        document.getElementById('div_plan3dHeaderConfigure').setJeeValues(jeephp2js.md_plan3dHeaderConfigure_plan3dHeader, '.plan3dHeaderAttr')
      }
      this.setUpload3dModel()
    },
    setUpload3dModel: function() {
      new jeeFileUploader({
        fileInput: document.getElementById('bt_upload3dModel'),
        replaceFileInput: false,
        url: 'core/ajax/plan3d.ajax.php?action=uploadModel&id=' + jeephp2js.md_plan3dHeaderConfigure_Id,
        dataType: 'json',
        done: function(e, data) {
          if (data.result.state != 'ok') {
            jeedomUtils.showAlert({
              attachTo: jeeDialog.get('#md_plan3dHeaderConfigure', 'dialog'),
              message: data.result.result,
              level: 'danger'
            })
            return
          }
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_plan3dHeaderConfigure', 'dialog'),
            message: '{{Chargement réussi merci de recharger la page pour voir le résultat}}',
            level: 'success'
          })
          //jeedomUtils.loadPage('index.php?v=d&p=plan3d&plan_id=' + jeephp2js.md_plan3dHeaderConfigure_Id)
        }
      })
    },
  }
}

(function() { // Self Isolation!
  var jeeM = jeeFrontEnd.md_plan3dHeaderConfigure
  jeeM.init()

  /*Events delegations
  */
  document.getElementById('md_plan3dHeaderConfigure').addEventListener('click', function(event) {
    var _target = null
    if (_target = event.target.closest('.bt_removePlan3dComposant')) {
      jeeDialog.confirm('{{Êtes-vous sûr de vouloir supprimer ce composant ?}}', function(result) {
        if (result) {
          var tr = _target.closest('tr')
          var dataPlan3dId = tr.getAttribute('data-id')
          jeedom.plan3d.remove({
            id: dataPlan3dId,
            error: function(error) {
              jeedomUtils.showAlert({
                attachTo: jeeDialog.get('#md_plan3dHeaderConfigure', 'dialog'),
                message: error.message,
                level: 'danger'
              })
            },
            success: function() {
              jeedomUtils.showAlert({
                attachTo: jeeDialog.get('#md_plan3dHeaderConfigure', 'dialog'),
                message: '{{Composant supprimé}}',
                level: 'success'
              })
              tr.remove()
            }
          })
        }
      })
      return
    }

    if (_target = event.target.closest('.bt_configurePlan3dComposant')) {
      jeeDialog.dialog({
        id: 'jee_modal2',
        title: "{{Configuration du composant}}",
        contentUrl: 'index.php?v=d&modal=plan3d.configure&id=' + _target.closest('tr').getAttribute('data-id')
      })
      return
    }

    if (_target = event.target.closest('#bt_chooseIcon')) {
      var _icon = document.querySelector('div.plan3dHeaderAttr[data-l2key="icon"] > i')
      if (_icon != null) {
        _icon = _icon.getAttribute('class')
      } else {
        _icon = false
      }
      jeedomUtils.chooseIcon(function(_icon) {
        document.querySelector('.plan3dHeaderAttr[data-l1key="configuration"][data-l2key="icon"]').innerHTML = _icon
      }, {icon: _icon})
      return
    }

    if (_target = event.target.closest('#bt_saveConfigureplan3dHeader')) {
      jeedom.plan3d.saveHeader({
        plan3dHeader: document.getElementById('div_plan3dHeaderConfigure').getJeeValues('.plan3dHeaderAttr')[0],
        error: function(error) {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_plan3dHeaderConfigure', 'dialog'),
            message: error.message,
            level: 'danger'
          })
        },
        success: function() {
          window.location.reload()
        }
      })
    }

    if (_target = event.target.closest('#bt_removeConfigureplan3dHeader')) {
      jeeDialog.confirm('{{Êtes-vous sûr de vouloir supprimer ce plan 3d ?}}', function(result) {
        if (result) {
          jeedom.plan3d.removeHeader({
            id: document.getElementById('div_plan3dHeaderConfigure').getJeeValues('.plan3dHeaderAttr')[0].id,
            error: function(error) {
              jeedomUtils.showAlert({
                attachTo: jeeDialog.get('#md_plan3dHeaderConfigure', 'dialog'),
                message: error.message,
                level: 'danger'
              })
            },
            success: function() {
              window.location.reload()
            }
          })
        }
      })
    }
  })

  document.getElementById('md_plan3dHeaderConfigure').addEventListener('dblclick', function(event) {
    var _target = null
    if (_target = event.target.closest('.plan3dHeaderAttr[data-l1key=configuration][data-l2key=icon]')) {
      _target.innerHTML = ''
      return
    }
  })

})()
</script>
