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

$planHeader = planHeader::byId(init('planHeader_id'));
if (!is_object($planHeader)) {
  throw new Exception('Impossible de trouver le plan');
}
sendVarToJS([
  'jeephp2js.md_planHeaderConfigure_Id' => $planHeader->getId(),
  'jeephp2js.md_planHeaderConfigure_planHeader' => utils::o2a($planHeader)
]);
?>

<div id="md_planHeaderConfigure" data-modalType="md_planHeaderConfigure">
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#main" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-cog"></i> {{Général}}</a></li>
    <li role="presentation"><a href="#components" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-cubes"></i> {{Composants}}</a></li>
    <a class='btn btn-success btn-sm pull-right cursor' id='bt_saveConfigurePlanHeader'><i class="fas fa-check"></i> {{Sauvegarder}}</a>
  </ul>
  <div class="tab-content">
    <div role="tabpanel" class="tab-pane active" id="main">
      <div id="div_planHeaderConfigure">
        <form class="form-horizontal">
          <fieldset>
            <legend><i class="fas fa-cog"></i> {{Général}}</legend>
            <input type="text"  class="planHeaderAttr form-control" data-l1key="id" style="display: none;"/>
            <div class="form-group">
              <label class="col-lg-4 control-label">{{Nom}}</label>
              <div class="col-lg-2">
                <input class="planHeaderAttr form-control" data-l1key="name" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-4 control-label">{{Position}}</label>
              <div class="col-lg-2">
                <input type="number" class="planHeaderAttr form-control" data-l1key="order" min="0" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-4 control-label">{{Fond transparent}}</label>
              <div class="col-lg-2">
                <input type="checkbox" class="planHeaderAttr" data-l1key="configuration" data-l2key="backgroundTransparent" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-4 control-label">{{Couleur de fond}}</label>
              <div class="col-lg-2">
                <input type="color" class="planHeaderAttr form-control" data-l1key="configuration" data-l2key="backgroundColor" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-4 control-label">{{Code d'accès}}</label>
              <div class="col-lg-2">
                <input class="planHeaderAttr form-control inputPassword" data-l1key="configuration" data-l2key="accessCode" />
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-4 control-label">{{Icône}}</label>
              <div class="col-lg-1">
                <a class="btn btn-default btn-sm" id="bt_chooseIcon"><i class="fas fa-flag"></i> {{Choisir}}</a>
              </div>
              <div class="col-lg-2">
                <div class="planHeaderAttr" data-l1key="configuration" data-l2key="icon" ></div>
              </div>
            </div>
            <div class="form-group">
              <label class="col-lg-4 control-label">{{Image}}</label>
              <div class="col-lg-8">
                <span class="btn btn-default btn-file btn-sm" style="position:absolute;">
                  <i class="fas fa-cloud-upload-alt"></i> {{Envoyer}}<input  id="bt_uploadImage" type="file" name="file" style="display: inline-block;">
                </span>
                <span class="objectImg">
                  <a class="btn btn-sm btn-danger" id="bt_removeBackgroundImage" style="position:absolute;bottom:0;"><i class="fas fa-trash"></i> {{Supprimer l'image}}</a>
                  <img src="" height="160px" style="min-height: 60px;"/>
                </span>
              </div>
            </div>
          </fieldset>
        </form>
        <form class="form-horizontal">
          <fieldset>
            <legend><i class="icon techno-fleches"></i> {{Tailles}}</legend>
            <div class="form-group">
              <label class="col-lg-4 control-label">{{Taille (LxH)}}</label>
              <div class="col-lg-4">
                <input class="form-control input-sm planHeaderAttr" data-l1key='configuration' data-l2key="desktopSizeX" style="width: 80px;display: inline-block;"/>
                x
                <input class="form-control input-sm planHeaderAttr" data-l1key='configuration' data-l2key='desktopSizeY' style="width: 80px;display: inline-block;"/>
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
                <th>{{Type}}</th>
                <th>{{ID du lien}}</th>
                <th>{{Lien}}</th>
                <th>{{Action}}</th>
              </tr>
            </thead>
            <tbody>
              <?php
              $tr = '';
              foreach (($planHeader->getPlan()) as $plan) {
                $tr .= '<tr  class="plan" data-id="'.$plan->getId().'">';
                $tr .= '<td>';
                $tr .= $plan->getId();
                $tr .= '</td>';
                $tr .= '<td>';
                $tr .= $plan->getLink_type();
                $tr .= '</td>';
                $tr .= '<td>';
                $tr .= $plan->getLink_id();
                $tr .= '</td>';
                $tr .= '<td>';
                if (in_array($plan->getLink_type(),array('text','summary','graph','plan','view','zone','image'))) {
                  $tr .= '<span class="label label-default">N/A</span>';
                } else {
                  $link = $plan->getLink();
                  if(is_object($link)){
                    $tr .= $link->getHumanName();
                  }else{
                    $tr .= '<span class="label label-danger">{{Lien mort ou absent}}</span>';
                  }
                }
                $tr .= '</td>';
                $tr .= '<td>';
                $tr .= '<a class="btn btn-danger btn-xs bt_removePlanComposant pull-right"><i class="fas fa-trash"></i> {{Supprimer}}</a> ';
                if (is_object($link)) {
                  $tr .= '<a class="btn btn-default btn-xs bt_configurePlanComposant pull-right"><i class="fas fa-cog"></i> {{Configuration}}</a>';
                }
                $tr .= '</td>';
                $tr .= '</tr>';
              }
              echo $tr;
              ?>
            </tbody>
          </table>
        </fieldset>
      </form>
    </div>
  </div>
</div>

<script>
if (!jeeFrontEnd.md_planHeaderConfigure) {
  jeeFrontEnd.md_planHeaderConfigure = {
    init: function() {
      if (isset(jeephp2js.md_planHeaderConfigure_Id) && jeephp2js.md_planHeaderConfigure_Id != '') {
        document.getElementById('div_planHeaderConfigure').setJeeValues(jeephp2js.md_planHeaderConfigure_planHeader, '.planHeaderAttr')
      }

      this.displayBackground(null, false)
      this.setUploadFile()
    },
    displayBackground: function(_path, _update) {
      var isObject = Object.prototype.toString.call(jeephp2js.md_planHeaderConfigure_planHeader.image) === '[object Object]'
      if (!isset(_path)) {
        if (isObject && jeephp2js.md_planHeaderConfigure_planHeader.image.sha512 != '') {
          var _path = '../../data/plan/planHeader' + jeephp2js.md_planHeaderConfigure_Id + '-' + jeephp2js.md_planHeaderConfigure_planHeader.image.sha512 + '.' + jeephp2js.md_planHeaderConfigure_planHeader.image.type
        } else {
          document.querySelector('#md_planHeaderConfigure .objectImg').unseen()
          return
        }
      } else {
        _path = '../../data/plan/planHeader' + _path.split('/planHeader')[1]
      }
      //Set dialog image:
      document.querySelector('#md_planHeaderConfigure .objectImg').seen().querySelector('img').setAttribute('src', _path)

      //Update or create design background image:
      if (isset(_update) && _update === true) {
        var planBckg = document.getElementById('planHeaderImage')
        if (!planBckg) {
          planBckg = document.createElement('div')
          planBckg.setAttribute('id', 'planHeaderImage')
          var parent = document.querySelector('div.div_backgroundPlan > div.div_displayObject')
          // console.log('parent:', parent)
          parent.insertBefore(planBckg, parent.querySelector('#div_grid'))

        }

        Object.assign(planBckg.style, {
          zIndex: 997,
          background: 'url(' + _path + ')',
          backgroundSize: jeephp2js.md_planHeaderConfigure_planHeader.configuration.desktopSizeX + 'px ' + jeephp2js.md_planHeaderConfigure_planHeader.configuration.desktopSizeY + 'px',
          width: jeephp2js.md_planHeaderConfigure_planHeader.configuration.desktopSizeX + 'px',
          height: jeephp2js.md_planHeaderConfigure_planHeader.configuration.desktopSizeY + 'px',
        })
      }
    },
    setUploadFile: function() {
      new jeeFileUploader({
        fileInput: document.getElementById('bt_uploadImage'),
        replaceFileInput: false,
        url: 'core/ajax/plan.ajax.php?action=uploadImage&id=' + jeephp2js.md_planHeaderConfigure_Id,
        dataType: 'json',
        done: function(e, data) {
          if (data.result.state != 'ok') {
            jeedomUtils.showAlert({
              attachTo: jeeDialog.get('#md_planHeaderConfigure', 'dialog'),
              message: data.result.result,
              level: 'danger'
            })
            return
          }
          jeeFrontEnd.md_planHeaderConfigure.displayBackground(data.result.result.filepath, true)
          //jeedomUtils.loadPage('index.php?v=d&p=plan&plan_id=' + jeephp2js.md_planHeaderConfigure_Id)
        }
      })
    },
  }
}
(function() {// Self Isolation!
  var jeeM = jeeFrontEnd.md_planHeaderConfigure
  jeeM.init()

  /*Events delegations
  */
  document.getElementById('md_planHeaderConfigure').addEventListener('click', function(event) {
    var _target = null
    if (_target = event.target.closest('.bt_removePlanComposant')) {
      var tr = _target.closest('tr')
      var dataPlanId = tr.getAttribute('data-id')
      jeedom.plan.remove({
        id: dataPlanId,
        error: function(error) {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_planHeaderConfigure', 'dialog'),
            message: error.message,
            level: 'danger'
          })
        },
        success: function() {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_planHeaderConfigure', 'dialog'),
            message: '{{Composant supprimé}}',
            level: 'success'
          })
          tr.remove()
          document.querySelector('div.div_backgroundPlan [data-plan_id="' + dataPlanId + '"]')?.remove()
        }
      })
      return
    }

    if (_target = event.target.closest('.bt_configurePlanComposant')) {
      jeeDialog.dialog({
        id: 'jee_modal2',
        title: "{{Configuration du composant}}",
        contentUrl: 'index.php?v=d&modal=plan.configure&id=' + _target.closest('tr').getAttribute('data-id')
      })
      return
    }

    if (_target = event.target.closest('#bt_chooseIcon')) {
      var _icon = document.querySelector('div.planHeaderAttr[data-l2key="icon"] > i')
      if (_icon != null) {
        _icon = _icon.getAttribute('class')
      } else {
        _icon = false
      }
      jeedomUtils.chooseIcon(function(_icon) {
        document.querySelector('.planHeaderAttr[data-l1key="configuration"][data-l2key="icon"]').innerHTML = _icon
      }, {icon: _icon})
      return
    }

    if (_target = event.target.closest('#bt_removeBackgroundImage')) {
      jeedom.plan.removeImageHeader({
        planHeader_id: jeephp2js.md_planHeaderConfigure_Id,
        error: function(error) {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_planHeaderConfigure', 'dialog'),
            message: error.message,
            level: 'danger'
          });
        },
        success: function() {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_planHeaderConfigure', 'dialog'),
            message: '{{Image supprimée}}',
            level: 'success'
          })
          document.getElementById('planHeaderImage')?.remove()
          document.querySelector('#md_planHeaderConfigure .objectImg').unseen()
        },
      })
      return
    }

    if (_target = event.target.closest('#bt_saveConfigurePlanHeader')) {
      jeedom.plan.saveHeader({
        planHeader: document.getElementById('div_planHeaderConfigure').getJeeValues('.planHeaderAttr')[0],
        error: function(error) {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_planHeaderConfigure', 'dialog'),
            message: error.message,
            level: 'danger'
          })
        },
        success: function() {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_planHeaderConfigure', 'dialog'),
            message: '{{Design sauvegardé}}',
            level: 'success'
          })
          jeedomUtils.loadPage('index.php?v=d&p=plan&plan_id=' + jeephp2js.md_planHeaderConfigure_Id)
        }
      })
      return
    }
  })

  document.getElementById('md_planHeaderConfigure').addEventListener('dblclick', function(event) {
    var _target = null
    if (_target = event.target.closest('.planHeaderAttr[data-l1key="configuration"][data-l2key="icon"]')) {
      _target.innerHTML = ''
      return
    }
  })

})()
</script>
