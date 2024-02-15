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
<table id="table_mod_insertCmdValue_valueEqLogicToMessage" class="table table-condensed">
  <thead>
    <tr>
      <th style="width: 150px;">{{Objet}}</th>
      <th style="width: 150px;">{{Equipement}}</th>
      <th id="thCmd" style="width: 150px;">{{Commande}}</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="mod_insertCmdValue_object">
        <select class='form-control'>
          <?php echo jeeObject::getUISelectList(); ?>
        </select>
      </td>
      <td class="mod_insertCmdValue_eqLogic"></td>
      <td class="mod_insertCmdValue_cmd"></td>
    </tr>
  </tbody>
</table>

<script>
(function() {// Self Isolation!
  if (window.mod_insertCmd == undefined) {
    window.mod_insertCmd = function() {}
    mod_insertCmd.options = {}
    mod_insertCmd.options.cmd = {}
    mod_insertCmd.options.eqLogic = {}
    mod_insertCmd.options.object = {}
  }else if(mod_insertCmd?.options?.object?.id){
    document.getElementById('table_mod_insertCmdValue_valueEqLogicToMessage').querySelector('td.mod_insertCmdValue_object select').jeeValue(mod_insertCmd.options.object.id)
  }

  mod_insertCmd.setOptions = function(_options) {
    mod_insertCmd.options = _options
    var _selectObject = document.getElementById('table_mod_insertCmdValue_valueEqLogicToMessage').querySelector('td.mod_insertCmdValue_object select')
    if (!isset(mod_insertCmd.options.cmd)) {
      mod_insertCmd.options.cmd = {}
    }
    if (!isset(mod_insertCmd.options.eqLogic)) {
      mod_insertCmd.options.eqLogic = {}
    }
    if (!isset(mod_insertCmd.options.object)) {
      mod_insertCmd.options.object = {}
    }
    if (mod_insertCmd?.options?.object?.id) {
      document.getElementById('table_mod_insertCmdValue_valueEqLogicToMessage').querySelector('td.mod_insertCmdValue_object select').value = mod_insertCmd.options.object.id
    }
    if (isset(mod_insertCmd.options.cmd.type)) {
      if (mod_insertCmd.options.cmd.type == "info") document.querySelector('#table_mod_insertCmdValue_valueEqLogicToMessage #thCmd').textContent = "{{Commande info}}"
      if (mod_insertCmd.options.cmd.type == "action") document.querySelector('#table_mod_insertCmdValue_valueEqLogicToMessage #thCmd').textContent = "{{Commande action}}"
    }

    mod_insertCmd.changeObjectCmd(_selectObject, mod_insertCmd.options)
    document.getElementById('table_mod_insertCmdValue_valueEqLogicToMessage').addEventListener('change', function(event) {
      if (event.target.matches('td.mod_insertCmdValue_object select')) {
        mod_insertCmd.changeObjectCmd(_selectObject, mod_insertCmd.options)
      }
    })
  }

  mod_insertCmd.getValue = function() {
    let object = document.querySelector('#table_mod_insertCmdValue_valueEqLogicToMessage .mod_insertCmdValue_object > select')?.selectedOptions
    let eqlogic = document.querySelector('#table_mod_insertCmdValue_valueEqLogicToMessage .mod_insertCmdValue_eqLogic > select')?.selectedOptions
    let cmd = document.querySelector('#table_mod_insertCmdValue_valueEqLogicToMessage .mod_insertCmdValue_cmd > select')?.selectedOptions
    if ([object, eqlogic, cmd].filter(hc => hc.length == 0).length > 0) {
      return ''
    }
    return '#[' + object[0].text.trim() + '][' + eqlogic[0].text + '][' + cmd[0].text + ']#'
  }

  mod_insertCmd.getCmdId = function() {
    return document.querySelector('#table_mod_insertCmdValue_valueEqLogicToMessage .mod_insertCmdValue_cmd > select').value || null
  }

  mod_insertCmd.getType = function() {
    let opt = document.querySelector('#table_mod_insertCmdValue_valueEqLogicToMessage .mod_insertCmdValue_cmd > select').selectedOptions
    return opt[0] ? opt[0].getAttribute('data-type') : null
  }

  mod_insertCmd.getSubType = function() {
    let opt = document.querySelector('#table_mod_insertCmdValue_valueEqLogicToMessage .mod_insertCmdValue_cmd > select').selectedOptions
    return opt[0] ? opt[0].getAttribute('data-subType') : null
  }

  mod_insertCmd.changeObjectCmd = function(_select, _options) {
    mod_insertCmd.options.object.id = (_select.jeeValue() == '' ? -1 : _select.jeeValue())
    jeedom.object.getEqLogic({
      id: (_select.jeeValue() == '' ? -1 : _select.jeeValue()),
      orderByName : true,
      onlyHasCmds : _options.cmd,
      error: function(error) {
        jeedomUtils.showAlert({message: error.message, level: 'danger'})
      },
      success: function(eqLogics) {
        _select.closest('tr').querySelector('.mod_insertCmdValue_eqLogic').empty()
        var selectEqLogic = '<select class="form-control">'
        for (var i in eqLogics) {
          if (init(mod_insertCmd.options.eqLogic.eqType_name, 'all') == 'all' || eqLogics[i].eqType_name == mod_insertCmd.options.eqLogic.eqType_name) {
            selectEqLogic += '<option value="' + eqLogics[i].id + '">' + eqLogics[i].name + '</option>'
          }
        }
        selectEqLogic += '</select>'
        _select.closest('tr').querySelector('.mod_insertCmdValue_eqLogic').insertAdjacentHTML('beforeend', selectEqLogic)
        if (mod_insertCmd?.options?.eqLogic?.id && Array.from(_select.closest('tr').querySelectorAll('.mod_insertCmdValue_eqLogic select option')).filter( o => o.value === mod_insertCmd.options.eqLogic.id ).length > 0) {
          _select.closest('tr').querySelector('.mod_insertCmdValue_eqLogic select').jeeValue(mod_insertCmd.options.eqLogic.id)
        }
        _select.closest('tr').querySelector('.mod_insertCmdValue_eqLogic select').addEventListener('change', function() {
          mod_insertCmd.changeEqLogic(this, mod_insertCmd.options)
        })
        mod_insertCmd.changeEqLogic(_select.closest('tr').querySelector('.mod_insertCmdValue_eqLogic select'), mod_insertCmd.options)
      }
    })
  }

  mod_insertCmd.changeEqLogic = function(_select, _options) {
    jeedom.eqLogic.buildSelectCmd({
      id: _select.jeeValue(),
      filter: mod_insertCmd.options.cmd,
      error: function(error) {
        jeedomUtils.showAlert({message: error.message, level: 'danger'})
      },
      success: function(html) {
        try { //No tr on object without equipment
          _select.closest('tr').querySelector('.mod_insertCmdValue_cmd').empty()
          var selectCmd = '<select class="form-control">'
          selectCmd += html
          selectCmd += '</select>'
          _select.closest('tr').querySelector('.mod_insertCmdValue_cmd').insertAdjacentHTML('beforeend', selectCmd)
        } catch (error) { }
      }
    })
  }
})()
</script>
