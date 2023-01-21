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

<div id="md_interactTest" data-modalType="md_interactTest">
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
</div>

<script>
(function() {// Self Isolation!
  document.getElementById('in_testInteractQuery')?.addEventListener('keypress', function(event) {
    if (event.which == 13) {
      document.getElementById('bt_executeInteractOk').triggerEvent('click')
    }
  })

  document.getElementById('bt_executeInteractOk').addEventListener('click',function() {
    if (document.getElementById('in_testInteractQuery').value == '') {
      jeedomUtils.showAlert({
        attachTo: jeeDialog.get('#md_interactTest', 'content'),
        message: '{{La demande ne peut être vide}}',
        level: 'danger'
      })
      return
    }
    jeedom.interact.execute({
      query: document.getElementById('in_testInteractQuery').value,
      error: function(error) {
        jeedomUtils.showAlert({
          attachTo: jeeDialog.get('#md_interactTest', 'content'),
          message: error.message,
          level: 'danger'
        })
      },
      success: function(data) {
        var divTest = document.getElementById('div_interactTestResult')
        divTest.empty()
        for (var i in data) {
          divTest.insertAdjacentHTML('beforeend', '<div class="alert alert-info"><i class="fas fa-comment"></i> ' + i + ' => ' + data[i] + '</div>')
        }
      }
    })
  })

})()
</script>