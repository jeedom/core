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

$view = view::byId(init('view_id'));
if (!is_object($view)) {
  throw new Exception('Impossible de trouver la vue');
}
sendVarToJS([
  'jeephp2js.md_viewConfigure_Id' => $view->getId(),
  'jeephp2js.md_viewConfigure_View' => utils::o2a($view)
]);

?>
<div id="md_viewConfigure" data-modalType="md_viewConfigure">
  <div id="div_viewConfigure">
    <form class="form-horizontal">
      <fieldset>
        <legend><i class="fas fa-cog"></i> {{Général}}<a class='btn btn-success btn-xs pull-right cursor' style="color: white;" id='bt_saveConfigureView'><i class="fas fa-check"></i> {{Sauvegarder}}</a></legend>
        <input type="text"  class="viewAttr form-control" data-l1key="id" style="display: none;"/>
        <div class="form-group">
          <label class="col-lg-4 control-label">{{Nom}}</label>
          <div class="col-lg-2">
            <input class="viewAttr form-control" data-l1key="name" />
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-4 control-label">{{Icône}}</label>
          <div class="col-lg-2">
            <div class="viewAttr" data-l1key="display" data-l2key="icon" ></div>
          </div>
          <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
            <a class="btn btn-default btn-sm" id="bt_chooseIcon"><i class="fas fa-flag"></i> {{Choisir}}</a>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-4 control-label">{{Image (marche uniquement avec le thème Jeedom)}}</label>
          <div class="col-lg-8">
            <span class="btn btn-default btn-file">
              <i class="fas fa-cloud-upload-alt"></i> {{Envoyer}}<input  id="bt_uploadImage" type="file" name="file" style="display: inline-block;">
            </span>
            <a class="btn btn-danger" id="bt_removeBackgroundImage"><i class="fas fa-trash"></i> {{Supprimer l'image}}</a>
          </div>
        </div>
        <div class="form-group">
          <label class="col-lg-4 control-label">{{Afficher le nom des objets sur les widgets}}</label>
          <div class="col-lg-2">
            <input type="checkbox" class="viewAttr form-control" data-l1key="configuration" data-l2key="displayObjectName" />
          </div>
        </div>
      </fieldset>
    </form>
  </div>
</div>

<script>
if (!jeeFrontEnd.md_viewConfigure) {
  jeeFrontEnd.md_viewConfigure = {
    init: function() {
      if (isset(jeephp2js.md_viewConfigure_Id) && jeephp2js.md_viewConfigure_Id != '') {
        document.getElementById('div_viewConfigure').setJeeValues(jeephp2js.md_viewConfigure_View, '.viewAttr')
      }

      this.setFileUpload()
    },
    setFileUpload: function() {
      new jeeFileUploader({
        fileInput: document.getElementById('bt_uploadImage'),
        replaceFileInput: false,
        url: 'core/ajax/view.ajax.php?action=uploadImage&id=' + jeephp2js.md_viewConfigure_View.id,
        dataType: 'json',
        done: function(e, data) {
          if (data.result.state != 'ok') {
            jeedomUtils.showAlert({
              attachTo: jeeDialog.get('#md_viewConfigure', 'dialog'),
              message: data.result.result,
              level: 'danger'
            })
            return
          }
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_viewConfigure', 'dialog'),
            message: '{{Image ajoutée}}',
            level: 'success'
          })
        }
      })
    },
    save: function() {
      var view = document.getElementById('div_viewConfigure').getJeeValues('.viewAttr')[0]
      jeedom.view.save({
        id : jeephp2js.md_viewConfigure_View.id,
        view: view,
        error: function(error) {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_viewConfigure', 'dialog'),
            message: error.message,
            level: 'danger'
           })
        },
        success: function() {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_viewConfigure', 'dialog'),
            message: '{{Vue sauvegardé}}',
            level: 'success'
          })
        },
      })
    },
  }
}
(function() {// Self Isolation!
  var jeeM = jeeFrontEnd.md_viewConfigure
  jeeM.init()

  /*Events delegations
  */
  document.getElementById('md_viewConfigure').addEventListener('click', function(event) {
    var _target = null
    if (_target = event.target.closest('#bt_chooseIcon')) {
      var _icon = document.querySelector('div.viewAttr[data-l2key="icon"] > i')
      if (_icon != null) {
        _icon = _icon.getAttribute('class')
      } else {
        _icon = false
      }
      jeedomUtils.chooseIcon(function(_icon) {
        document.querySelector('.viewAttr[data-l1key="display"][data-l2key="icon"]').innerHTML = _icon
      }, {icon: _icon})
      return
    }

    if (_target = event.target.closest('#bt_removeBackgroundImage')) {
      jeedom.view.removeImage({
        id: jeephp2js.md_viewConfigure_View.id,
        error: function(error) {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_viewConfigure', 'dialog'),
            message: error.message,
            level: 'danger'
           })
        },
        success: function() {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_viewConfigure', 'dialog'),
            message: '{{Image supprimée}}',
            level: 'success'
          })
        },
      })
      return
    }

    if (_target = event.target.closest('#bt_saveConfigureView')) {
      jeeFrontEnd.md_viewConfigure.save()
      return
    }
  })

  document.getElementById('md_viewConfigure').addEventListener('dblclick', function(event) {
    var _target = null
    if (_target = event.target.closest('.viewAttr[data-l1key="display"][data-l2key="icon"]')) {
      _target.innerHTML = ''
    }
  })

})()
</script>