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
?>
<form class="form-inline" id="mod_insertCmdValue_valueEqLogicToMessage">
  <div class="form-group mod_insertCmdValue_object">
    <select class='form-control'>
      <?php echo jeeObject::getUISelectList(); ?>
    </select>
  </div>
  <div class="form-group mod_insertCmdValue_eqLogic">

  </div>
  <div class="form-group mod_insertCmdValue_cmd">

  </div>
</form>

<script>
  function mod_insertCmd() {}

  mod_insertCmd.options = {}
  mod_insertCmd.options.cmd = {}
  mod_insertCmd.options.eqLogic = {}
  mod_insertCmd.options.object = {}

  mod_insertCmd.setOptions = function(_options) {
    mod_insertCmd.options = _options
    var _selectObject = document.getElementById('mod_insertCmdValue_valueEqLogicToMessage').querySelector('div.mod_insertCmdValue_object select')
    if (!isset(mod_insertCmd.options.cmd)) {
      mod_insertCmd.options.cmd = {}
    }
    if (!isset(mod_insertCmd.options.eqLogic)) {
      mod_insertCmd.options.eqLogic = {}
    }
    if (!isset(mod_insertCmd.options.object)) {
      mod_insertCmd.options.object = {}
    }
    if (isset(mod_insertCmd.options.object.id)) {
      document.getElementById('mod_insertCmdValue_valueEqLogicToMessage').querySelector('div.mod_insertCmdValue_object select').value = mod_insertCmd.options.object.id
    }
    if (isset(mod_insertCmd.options.cmd.type)) {
      if (mod_insertCmd.options.cmd.type == "info") $('#thCmd').text("{{Commande info}}")
      if (mod_insertCmd.options.cmd.type == "action") $('#thCmd').text("{{Commande action}}")
    }

    mod_insertCmd.changeObjectCmd(_selectObject, mod_insertCmd.options)
    $('#mod_insertCmdValue_valueEqLogicToMessage').on({
      'change': function(event) {
        mod_insertCmd.changeObjectCmd(_selectObject, mod_insertCmd.options)
      }
    }, '.mod_insertCmdValue_object select')
  }

  mod_insertCmd.getValue = function() {
    var object_name = $('#mod_insertCmdValue_valueEqLogicToMessage .mod_insertCmdValue_object select option:selected').html().replace(/&nbsp;/g, '')
    var equipement_name = $('#mod_insertCmdValue_valueEqLogicToMessage .mod_insertCmdValue_eqLogic select option:selected').html()
    var cmd_name = $('#mod_insertCmdValue_valueEqLogicToMessage .mod_insertCmdValue_cmd select option:selected').html()
    if (cmd_name == undefined) {
      return ''
    }
    return '#[' + object_name + '][' + equipement_name + '][' + cmd_name + ']#'
  }

  mod_insertCmd.getCmdId = function() {
    return document.querySelector('#mod_insertCmdValue_valueEqLogicToMessage').querySelector('div.mod_insertCmdValue_cmd select').value
  }

  mod_insertCmd.getType = function() {
    return document.querySelector('#mod_insertCmdValue_valueEqLogicToMessage').querySelector('div.mod_insertCmdValue_cmd select').selectedOptions[0].getAttribute('data-type')
  }

  mod_insertCmd.getSubType = function() {
    return document.querySelector('#mod_insertCmdValue_valueEqLogicToMessage').querySelector('div.mod_insertCmdValue_cmd select').selectedOptions[0].getAttribute('data-subType')
  }

  mod_insertCmd.changeObjectCmd = function(_select, _options) {
    jeedom.object.getEqLogic({
      id: (_select.jeeValue() == '' ? -1 : _select.jeeValue()),
      orderByName: true,
      onlyHasCmds: _options.cmd,
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function(eqLogics) {
        _select.closest('form').querySelector('.mod_insertCmdValue_eqLogic').empty()
        let selectEqLogic = '<select class="form-control">'
        for (let i in eqLogics) {
          if (init(mod_insertCmd.options.eqLogic.eqType_name, 'all') == 'all' || eqLogics[i].eqType_name == mod_insertCmd.options.eqLogic.eqType_name) {
            selectEqLogic += '<option value="' + eqLogics[i].id + '">' + eqLogics[i].name + '</option>'
          }
        }
        selectEqLogic += '</select>'
        _select.closest('form').querySelector('.mod_insertCmdValue_eqLogic').insertAdjacentHTML('beforeend', selectEqLogic)
        _select.closest('form').querySelector('.mod_insertCmdValue_eqLogic select').addEventListener('change', function() {
          mod_insertCmd.changeEqLogic(this, mod_insertCmd.options)
        })
        if (isset(mod_insertCmd.options.object.id)) {
          _select.closest('form').querySelector('.mod_insertCmdValue_eqLogic select').jeeValue(mod_insertCmd.options.eqLogic.id)
        }
        mod_insertCmd.changeEqLogic(_select.closest('form').querySelector('.mod_insertCmdValue_eqLogic select'), mod_insertCmd.options)
      }
    })
  }

  mod_insertCmd.changeEqLogic = function(_select) {
    jeedom.eqLogic.buildSelectCmd({
      id: _select.jeeValue(),
      filter: mod_insertCmd.options.cmd,
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function(html) {
        try { //No tr on object without equipment
          _select.closest('form').querySelector('.mod_insertCmdValue_cmd').empty()
          var selectCmd = '<select class="form-control">'
          selectCmd += html
          selectCmd += '</select>'
          _select.closest('form').querySelector('.mod_insertCmdValue_cmd').insertAdjacentHTML('beforeend', selectCmd)
        } catch (error) {}
      }
    })
  }
</script>