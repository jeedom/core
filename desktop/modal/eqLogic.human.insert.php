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

<table class="table table-condensed table-bordered" id="table_mod_insertEqLogicValue_valueEqLogicToMessage">
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
       <td class="mod_insertEqLogicValue_eqLogic"></td>
     </tr>
   </tbody>
</table>
<script>
  function mod_insertEqLogic() {}

  mod_insertEqLogic.options = {}
  mod_insertEqLogic.options.eqLogic = {}
  mod_insertEqLogic.options.object = {}

  $('#table_mod_insertEqLogicValue_valueEqLogicToMessage').on({
    'change': function(event) {
      mod_insertEqLogic.changeObjectEqLogic($('#table_mod_insertEqLogicValue_valueEqLogicToMessage td.mod_insertEqLogicValue_object select'), mod_insertEqLogic.options)
    }
  }, 'td.mod_insertEqLogicValue_object select')

  mod_insertEqLogic.setOptions = function(_options) {
    mod_insertEqLogic.options = _options
    if (!isset(mod_insertEqLogic.options.eqLogic)) {
      mod_insertEqLogic.options.eqLogic = {}
    }
    if (!isset(mod_insertEqLogic.options.object)) {
      mod_insertEqLogic.options.object = {}
    }
    if (isset(mod_insertEqLogic.options.object.id)) {
      $('#table_mod_insertEqLogicValue_valueEqLogicToMessage td.mod_insertEqLogicValue_object select').value(mod_insertEqLogic.options.object.id)
    }
    mod_insertEqLogic.changeObjectEqLogic($('#table_mod_insertEqLogicValue_valueEqLogicToMessage td.mod_insertEqLogicValue_object select'), mod_insertEqLogic.options)
  }

  mod_insertEqLogic.getValue = function() {
    var object_name = $('#table_mod_insertEqLogicValue_valueEqLogicToMessage tbody tr:first .mod_insertEqLogicValue_object select option:selected').html().replace(/&nbsp;/g, '')
    var equipement_name = $('#table_mod_insertEqLogicValue_valueEqLogicToMessage tbody tr:first .mod_insertEqLogicValue_eqLogic select option:selected').html()
    if (equipement_name == undefined) {
      return ''
    }
    return '#[' + object_name + '][' + equipement_name + ']#'
  }

  mod_insertEqLogic.getId = function() {
    return $('.mod_insertEqLogicValue_eqLogic select').value()
  }

  mod_insertEqLogic.changeObjectEqLogic = function(_select) {
    jeedom.object.getEqLogic({
      id: (_select.value() == '' ? -1 : _select.value()),
      orderByName : true,
      error: function(error) {
        $.fn.showAlert({message: error.message, level: 'danger'})
      },
      success: function(eqLogics) {
        _select.closest('tr').find('.mod_insertEqLogicValue_eqLogic').empty()
        var selectEqLogic = '<select class="form-control">'
        for (var i in eqLogics) {
          if (init(mod_insertEqLogic.options.eqLogic.eqType_name, 'all') == 'all' || eqLogics[i].eqType_name == mod_insertEqLogic.options.eqLogic.eqType_name) {
          selectEqLogic += '<option value="' + eqLogics[i].id + '">' + eqLogics[i].name + '</option>'
        }
      }
      selectEqLogic += '</select>'
      _select.closest('tr').find('.mod_insertEqLogicValue_eqLogic').append(selectEqLogic)
    }
    })
  }

  mod_insertEqLogic.changeObjectEqLogic($('#table_mod_insertEqLogicValue_valueEqLogicToMessage td.mod_insertEqLogicValue_object select'), mod_insertEqLogic.options)
</script>