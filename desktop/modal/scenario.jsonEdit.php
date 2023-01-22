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

if (!isConnect()) {
  throw new Exception('{{401 - Accès non autorisé}}');
}
$scenario = scenario::byId(init('id'));
if (!is_object($scenario)) {
  throw new Exception(__('Aucun scénario ne correspondant à :', __FILE__) . ' ' . init('id'));
}
sendVarToJs('jeephp2js.md_scenarioJsonEdit_scId', init('id'));
include_file('3rdparty', 'codemirror/addon/selection/active-line', 'js');
include_file('3rdparty', 'codemirror/addon/search/search', 'js');
include_file('3rdparty', 'codemirror/addon/search/searchcursor', 'js');
include_file('3rdparty', 'codemirror/addon/dialog/dialog', 'js');
include_file('3rdparty', 'codemirror/addon/dialog/dialog', 'css');

include_file('3rdparty', 'codemirror/addon/fold/brace-fold', 'js');
include_file('3rdparty', 'codemirror/addon/fold/comment-fold', 'js');
include_file('3rdparty', 'codemirror/addon/fold/foldcode', 'js');
include_file('3rdparty', 'codemirror/addon/fold/indent-fold', 'js');
include_file('3rdparty', 'codemirror/addon/fold/foldgutter', 'js');
include_file('3rdparty', 'codemirror/addon/fold/foldgutter', 'css');
?>

<div id="md_scenarioJsonEdit" data-modalType="md_scenarioJsonEdit">
  <a class="btn btn-success btn-sm pull-right" id="bt_saveSummaryScenario"><i class="fas fa-check-circle"></i> {{Sauvegarder}}</a>
  <br/><br/>
  <textarea id="ta_scenarioJsonEdit_scenario">
    <?php
    $json = array();
    foreach (($scenario->getElement()) as $element) {
      $json[] = $element->getAjaxElement();
    }
    echo json_encode($json, JSON_PRETTY_PRINT);
    ?>
  </textarea>
</div>

<script>
if (!jeeFrontEnd.md_scenarioJsonEdit) {
  jeeFrontEnd.md_scenarioJsonEdit = {
    fileEditor: null,
    init: function() {
      this.fileEditor = CodeMirror.fromTextArea(document.getElementById("ta_scenarioJsonEdit_scenario"), {
      lineNumbers: true,
      mode: 'application/json',
      styleActiveLine: true,
      lineNumbers: true,
      lineWrapping: true,
      matchBrackets: true,
      autoRefresh: true,
      foldGutter: true,
      gutters: ["CodeMirror-linenumbers", "CodeMirror-foldgutter"]
    })
    this.fileEditor.setOption("extraKeys", {
      "Ctrl-Y": cm => CodeMirror.commands.foldAll(cm),
      "Ctrl-I": cm => CodeMirror.commands.unfoldAll(cm)
    })
    this.fileEditor.getWrapperElement().style.height = (document.getElementById('ta_scenarioJsonEdit_scenario').closest('.jeeDialog').style.height - 90) + 'px'
    this.fileEditor.refresh()
    },
  }
}

(function() {// Self Isolation!
  jeeFrontEnd.md_scenarioJsonEdit.init()

  document.getElementById('bt_saveSummaryScenario').addEventListener('click', function() {
    jeedomUtils.hideAlert()
    if (jeeFrontEnd.md_scenarioJsonEdit.fileEditor == undefined) {
      jeedomUtils.showAlert({
        attachTo: jeeDialog.get('#md_scenarioJsonEdit', 'content'),
        message: '{{Erreur editeur non défini}}',
        level: 'danger'
      })
      return
    }
    try {
      JSON.parse(jeeFrontEnd.md_scenarioJsonEdit.fileEditor.getValue())
    } catch(e) {
      jeedomUtils.showAlert({
        attachTo: jeeDialog.get('#md_scenarioJsonEdit', 'content'),
        message: '{{Champs json invalide}}',
        level: 'danger'
      })
      return
    }
    var scenario = {
      id : jeephp2js.md_scenarioJsonEdit_scId,
      elements : json_decode(jeeFrontEnd.md_scenarioJsonEdit.fileEditor.getValue())
    };
    jeedom.scenario.save({
      scenario: scenario,
      error: function(error) {
        jeedomUtils.showAlert({
          attachTo: jeeDialog.get('#md_scenarioJsonEdit', 'content'),
          message: error.message,
          level: 'danger'
        })
      },
      success: function(data) {
        jeedomUtils.showAlert({
          attachTo: jeeDialog.get('#md_scenarioJsonEdit', 'content'),
          message: '{{Sauvegarde réussie}}',
          level: 'success'
        })
        if (document.body.getAttribute('data-page') == 'scenario') {
          jeeFrontEnd.scenario.printScenario(jeephp2js.md_scenarioJsonEdit_scId)
        }
      }
    })
  })

})()
</script>