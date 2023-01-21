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

<table id="table_mod_insertEqLogicValue_valueEqLogicToMessage" class="table table-condensed table-bordered">
  <thead>
    <tr>
      <th style="width: 150px;">{{Objet}}</th>
      <th style="width: 150px;">{{Equipement}}</th>
    </tr>
  </thead>
  <tbody>
    <tr>
      <td class="mod_insertEqLogicValue_object">
        <select class='form-control'>
          <?php echo jeeObject::getUISelectList(); ?>
        </select>
      </td>
      <td class="mod_insertEqLogicValue_eqLogic">
        <select class="form-control"></select>
      </td>
    </tr>
  </tbody>
</table>

<script>
(function() {
  if (window.mod_insertEqLogic == undefined) {
    window.mod_insertEqLogic = function() {}
    mod_insertEqLogic.options = {}
    mod_insertEqLogic.options.eqLogic = {}
    mod_insertEqLogic.options.object = {}
  }

  mod_insertEqLogic.selectObject = document.querySelector('#table_mod_insertEqLogicValue_valueEqLogicToMessage td.mod_insertEqLogicValue_object > select')
  mod_insertEqLogic.selectEqlogic = document.querySelector('#table_mod_insertEqLogicValue_valueEqLogicToMessage td.mod_insertEqLogicValue_eqLogic > select')

  mod_insertEqLogic.selectObject.addEventListener('change', function(event) {
    mod_insertEqLogic.changeObjectEqLogic(event.target, mod_insertEqLogic.options)
  })

  mod_insertEqLogic.selectEqlogic.addEventListener('change', function(event) {
    mod_insertEqLogic.options.eqLogic = {id: event.target.value}
  })

  mod_insertEqLogic.setOptions = function(_options) {
    mod_insertEqLogic.options = _options
    var _select = document.getElementById('table_mod_insertEqLogicValue_valueEqLogicToMessage')?.querySelector('td.mod_insertEqLogicValue_object select')
    if (!isset(mod_insertEqLogic.options.eqLogic)) mod_insertEqLogic.options.eqLogic = {}
    if (!isset(mod_insertEqLogic.options.object)) mod_insertEqLogic.options.object = {}
    if (isset(mod_insertEqLogic.options.object.id)) {
      mod_insertEqLogic.selectObject.jeeValue(mod_insertEqLogic.options.object.id)
    }
    mod_insertEqLogic.changeObjectEqLogic(mod_insertEqLogic.selectObject)
  }

  mod_insertEqLogic.getValue = function() {
    let _selectObject = document.querySelector('#table_mod_insertEqLogicValue_valueEqLogicToMessage tbody tr').querySelector('.mod_insertEqLogicValue_object select')
    let _selectEq = document.querySelector('#table_mod_insertEqLogicValue_valueEqLogicToMessage tbody tr').querySelector('.mod_insertEqLogicValue_eqLogic select')
    if (_selectEq.selectedOptions.length == 0) {
      return ''
    }
    return '#[' + _selectObject.selectedOptions[0].text.trim() + '][' + _selectEq.selectedOptions[0].text.trim() + ']#'
  }

  mod_insertEqLogic.getId = function() {
    return document.querySelector('.mod_insertEqLogicValue_eqLogic select').value || null
  }

  mod_insertEqLogic.changeObjectEqLogic = function() {
    mod_insertEqLogic.options.object = {id: mod_insertEqLogic.selectObject.value}
    jeedom.object.getEqLogic({
      id: (mod_insertEqLogic.selectObject.value == '' ? -1 : mod_insertEqLogic.selectObject.value),
      orderByName: true,
      error: function(error) {
        jeedomUtils.showAlert({
          message: error.message,
          level: 'danger'
        })
      },
      success: function(eqLogics) {
        mod_insertEqLogic.selectEqlogic.empty()
        var selectEqLogic = ''
        for (var i in eqLogics) {
          if (init(mod_insertEqLogic.options.eqLogic.eqType_name, 'all') == 'all' || eqLogics[i].eqType_name == mod_insertEqLogic.options.eqLogic.eqType_name) {
            selectEqLogic += '<option value="' + eqLogics[i].id + '">' + eqLogics[i].name + '</option>'
          }
        }
        mod_insertEqLogic.selectEqlogic.insertAdjacentHTML('beforeend', selectEqLogic)
        if (isset(mod_insertEqLogic.options.eqLogic.id)) {
          mod_insertEqLogic.selectEqlogic.jeeValue(mod_insertEqLogic.options.eqLogic.id)
        }
      }
    })
  }
})()
</script>
