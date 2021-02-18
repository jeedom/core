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
?>

<div id="div_alertInteractTest"></div>
<form class="form-horizontal" onsubmit="return false;">
  <div class="input-group input-group-sm" style="width: 100%">
    <span class="input-group-addon" style="width: 100px">{{Demande}}</span>
    <input class="form-control roundedLeft" id="in_testInteractQuery">
    <span class="input-group-btn">
      <a class="btn btn-default roundedRight" id="bt_executeInteractOk"><i class="fas fa-bolt"></i> {{Exécuter}}</a>
    </span>
  </div>
</form>
<legend>{{Résultat}}</legend>
<div id="div_interactTestResult"></div>
<script>

$('#in_testInteractQuery').keypress(function(event) {
  if (event.which == 13) {
    $('#bt_executeInteractOk').trigger('click')
  }
})

$('#bt_executeInteractOk').on('click',function() {
  if ($('#in_testInteractQuery').value() == '') {
    $('#div_alertInteractTest').showAlert({message: '{{La demande ne peut être vide}}', level: 'danger'})
    return
  }
  jeedom.interact.execute({
    query: $('#in_testInteractQuery').value(),
    error: function(error) {
      $('#div_alertInteractTest').showAlert({message: error.message, level: 'danger'})
    },
    success: function(data) {
      $('#div_interactTestResult').empty()
      for (var i in data) {
        $('#div_interactTestResult').append('<div class="alert alert-info"><i class="fas fa-comment"></i> '+i+' => '+data[i]+'</div>')
      }

    }
  })
})
</script>