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
$scenario = scenario::byId(init('scenario_id'));
if (!is_object($scenario)) {
  throw new Exception('Scénario non trouvé : ' . init('scenario_id'));
}
sendVarToJS('jeephp2js.md_scenarioTemplate_scId', init('scenario_id'));
?>

<div id="md_scenarioTemplate" data-modalType="md_scenarioTemplate">
  <div class="row row-overflow" id='div_scenarioTemplate'>
    <div class="col-lg-2 col-md-3 col-sm-5" id="div_listScenario" style="z-index:999">
      <div class="bs-sidebar nav nav-list bs-sidenav">
        <div class="form-group">
          <span class="btn btn-default btn-file" style="width:100%;">
            <i class="fas fa-file-download"></i> {{Charger un template}}<input id="bt_uploadScenarioTemplate" type="file" name="file" style="display: inline-block; width:100%;">
          </span>
        </div>
        <div class="form-group">
          <div class="input-group">
            <input class='form-control roundedLeft' id='in_newTemplateName' placeholder="{{Nom du template}}" />
            <span class="input-group-btn">
              <a class="btn btn-default roundedRight" id="bt_scenarioTemplateConvert" style="color:white;"><i class="fas fa-plus-circle"></i></a>
            </span>
          </div>
        </div>
        <legend>{{Template}}</legend>
        <ul id="ul_scenarioTemplateList" class="nav nav-list bs-sidenav"></ul>
      </div>
    </div>

    <div class="col-lg-10 col-md-9 col-sm-7" id="div_listScenarioTemplate" style="display:none;">
      <form class="form-horizontal">
        <legend><i class="fas fa-home"></i> {{Général}}</legend>
        <div class="form-group">
          <label class="col-xs-4 control-label">{{Gérer}}</label>
          <div class="col-xs-8">
            <a class="btn btn-sm btn-danger pull-right" id="bt_scenarioTemplateRemove"><i class="fas fa-times"></i> {{Supprimer}}</a>
            <a class="btn btn-sm btn-primary pull-right" id="bt_scenarioTemplateDownload"><i class="fas fa-cloud-download-alt"></i> {{Télécharger}}</a>
          </div>
        </div>
        <div id='div_scenarioTemplateParametreConfiguration' style='display:none;'>
          <legend><i class="fas fa-tools"></i> {{Paramètres du scénario}}<a class='btn btn-success btn-xs pull-right' id='bt_scenarioTemplateApply'><i class="far fa-check-circle"></i> {{Appliquer}}</a></legend>
          <div id='div_scenarioTemplateParametreList'></div>
        </div>
      </form>
    </div>
  </div>
</div>

<script>
if (!jeeFrontEnd.md_scenarioTemplate) {
  jeeFrontEnd.md_scenarioTemplate = {
    init: function() {
      this.refreshScenarioTemplateList()
      this.setFileUpload()
    },
    refreshScenarioTemplateList: function() {
      jeedom.scenario.getTemplate({
        error: function(error) {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_scenarioTemplate', 'dialog'),
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          let ulList = document.getElementById('ul_scenarioTemplateList')
          ulList.empty()
          li = ''
          for (var i in data) {
            li += "<li class='cursor li_scenarioTemplate' data-template='" + data[i] + "'><a>" + data[i].replace(".json", "") + "</a></li>"
          }
          ulList.insertAdjacentHTML('beforeend', li)
        }
      })
    },
    setFileUpload: function() {
      new jeeFileUploader({
        fileInput: document.getElementById('bt_uploadScenarioTemplate'),
        url: 'core/ajax/scenario.ajax.php?action=templateupload',
        add: function(event, options) {
          //Is already there ?
          var newTemplate
          for (var file of options.data.entries()) {
            newTemplate = file[1].name
          }
          var templateList = Array.from(document.getElementById('div_listScenario').querySelectorAll('li.li_scenarioTemplate')).map(t => t.getAttribute('data-template'))
          if (templateList.includes(newTemplate)) {
            jeeDialog.confirm({
              title: '<i class="fas fa-exclamation-circle icon_red"></i> {{Attention}}',
              message: '{{Voulez vous écraser le template existant}} : ' + newTemplate,
            },
            function(result) {
              if (result) {
                options.submit()
              }
            })
            return
          } else {
            options.submit()
          }
        },
        done: function(event, data) {
          if (data.result.state != 'ok') {
            jeedomUtils.showAlert({
              attachTo: jeeDialog.get('#md_scenarioTemplate', 'dialog'),
              message: data.result.result,
              level: 'danger'
            })
            return
          }
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_scenarioTemplate', 'dialog'),
            message: '{{Template ajouté avec succès}}',
            level: 'success'
          })
          jeeFrontEnd.md_scenarioTemplate.refreshScenarioTemplateList()
        }
      })
    },
  }
}
(function() {// Self Isolation!
  var jeeM = jeeFrontEnd.md_scenarioTemplate
  jeeM.init()

  /*Events delegations
  */
  document.getElementById('md_scenarioTemplate').addEventListener('click', function(event) {
    var _target = null
    if (_target = event.target.closest('#bt_scenarioTemplateConvert')) {
      jeedom.scenario.convertToTemplate({
        id: jeephp2js.md_scenarioTemplate_scId,
        template: document.getElementById('in_newTemplateName').value + '.json',
        error: function(error) {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_scenarioTemplate', 'dialog'),
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          jeeFrontEnd.md_scenarioTemplate.refreshScenarioTemplateList();
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_scenarioTemplate', 'dialog'),
            message: '{{Création du template réussie}}',
            level: 'success'
          })
        }
      })
      return
    }

    if (_target = event.target.closest('#bt_scenarioTemplateRemove')) {
      let selected = document.querySelector('#ul_scenarioTemplateList li.active')

      if (selected == null || selected.getAttribute('data-template') == undefined) {
        jeedomUtils.showAlert({
          attachTo: jeeDialog.get('#md_scenarioTemplate', 'dialog'),
          message: '{{Vous devez d\'abord sélectionner un template}}',
          level: 'danger'
        })
        return
      }

      jeeDialog.confirm('{{Êtes-vous sûr de vouloir supprimer ce template ?}} ' + selected.querySelector('a').textContent, function(result) {
        if (result) {
          let template = selected.getAttribute('data-template')
          jeedom.scenario.removeTemplate({
            template: template,
            error: function(error) {
              jeedomUtils.showAlert({
                attachTo: jeeDialog.get('#md_scenarioTemplate', 'dialog'),
                message: error.message,
                level: 'danger'
              })
            },
            success: function(data) {
              document.getElementById('div_listScenarioTemplate').unseen()
              document.getElementById('div_scenarioTemplateParametreConfiguration').empty()
              jeeFrontEnd.md_scenarioTemplate.refreshScenarioTemplateList()
              jeedomUtils.showAlert({
                attachTo: jeeDialog.get('#md_scenarioTemplate', 'dialog'),
                message: '{{Suppression du template réussie}}',
                level: 'success'
              })
            }
          })
          return
        }
      })
    }

    if (_target = event.target.closest('#bt_scenarioTemplateApply')) {
      jeeDialog.confirm('{{Êtes-vous sûr de vouloir appliquer ce template ? Cela écrasera votre scénario actuel.}}', function(result) {
        if (result) {
          var convert = document.querySelectorAll('.templateScenario').getJeeValues('.templateScenarioAttr')
          jeedom.scenario.applyTemplate({
            template: document.querySelector('#ul_scenarioTemplateList li.active').getAttribute('data-template'),
            id: jeephp2js.md_scenarioTemplate_scId,
            convert: json_encode(convert),
            error: function(error) {
              jeedomUtils.showAlert({
                attachTo: jeeDialog.get('#md_scenarioTemplate', 'dialog'),
                message: error.message,
                level: 'danger'
              })
            },
            success: function(data) {
              jeeFrontEnd.scenario.printScenario(jeephp2js.md_scenarioTemplate_scId)
              jeedomUtils.showAlert({
                attachTo: jeeDialog.get('#md_scenarioTemplate', 'dialog'),
                message: '{{Template appliqué avec succès.}}',
                level: 'success'
              })
            }
          })
        }
      })
      return
    }

    if (_target = event.target.closest('#ul_scenarioTemplateList .li_scenarioTemplate')) {
      document.getElementById('div_listScenarioTemplate').seen()
      document.querySelectorAll('#ul_scenarioTemplateList .li_scenarioTemplate').removeClass('active')
      _target.addClass('active')
      jeedom.scenario.loadTemplateDiff({
        template: _target.getAttribute('data-template'),
        id: jeephp2js.md_scenarioTemplate_scId,
        error: function(error) {
          jeedomUtils.showAlert({
            attachTo: jeeDialog.get('#md_scenarioTemplate', 'dialog'),
            message: error.message,
            level: 'danger'
          })
        },
        success: function(data) {
          var html = ''
          for (var i in data) {
            html += '<div class="form-group templateScenario">'
            html += '<label class="col-xs-4 control-label">' + i + ' <i class="fas fa-arrow-right"></i></label>'
            html += '<div class="col-xs-4">'
            html += '<span class="templateScenarioAttr" data-l1key="begin" style="display : none;" >' + i + '</span>'
            html += '<div class="input-group">'
            html += '<input class="form-control templateScenarioAttr roundedLeft" data-l1key="end" value="'+data[i]+'"/>'
            html += '<span class="input-group-btn">'
            html += '<a class="btn btn-default cursor bt_scenarioTemplateSelectCmd roundedRight"><i class="fas fa-list-alt"></i></a>'
            html += '</span>'
            html += '</div>'
            html += '</div>'
            html += '</div>'
          }
          document.getElementById('div_scenarioTemplateParametreList').empty().insertAdjacentHTML('beforeend', html)
          document.getElementById('div_scenarioTemplateParametreConfiguration').seen()
        }
      })
      return
    }

    if (_target = event.target.closest('#bt_scenarioTemplateDownload')) {
      if (document.querySelector('#ul_scenarioTemplateList li.active').getAttribute('data-template') == undefined) {
        jeedomUtils.showAlert({
          attachTo: jeeDialog.get('#md_scenarioTemplate', 'dialog'),
          message: '{{Vous devez d\'abord sélectionner un template}}',
          level: 'danger'
        })
        return
      }
      var path = 'data/scenario/' + document.querySelector('#ul_scenarioTemplateList li.active').getAttribute('data-template')
      window.open('core/php/downloadFile.php?pathfile=' + path, "_blank", null)
      return
    }

    if (_target = event.target.closest('#div_scenarioTemplate .bt_scenarioTemplateSelectCmd')) {
      jeedom.cmd.getSelectModal({}, function(result) {
        _target.closest('.templateScenario').querySelector('.templateScenarioAttr[data-l1key=end]').value = result.human
      })
      return
    }
  })

})()
</script>