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

<form id="mod_insertCron" class="form-horizontal" style="overflow:hidden !important;">
  <div class="form-group mode schedule">
    <label class="col-xs-3 control-label" >{{A exécuter}}</label>
    <div class="col-xs-9">
      <select class="form-control" id="mod_cron_sel_scheduleMode">
        <option value="once">{{une seule fois}}</option>
        <option value="repete">{{récurrent}}</option>
      </select>
    </div>
  </div>
  <div id="mod_cron_div_scheduleConfig" class="mode schedule" style="margin-left: 30%;"></div>
</form>

<script>
(function() {// Self Isolation!
  var modal = jeeDialog.get('#mod_insertCron', 'dialog')
  modal.style.height = '420px'

  if (window.mod_insertCron == undefined) {
    window.mod_insertCron = function() {}
  }

  mod_insertCron.getValue = function() {
    return document.getElementById('mod_cron_span_cronResult').textContent
  }

  document.querySelector('#mod_insertCron #mod_cron_sel_scheduleMode').addEventListener('change', function(event) {
    let cronDiv = document.getElementById('mod_insertCron')
    let scheduleDiv = cronDiv.querySelector('#mod_cron_div_scheduleConfig')
    scheduleDiv.empty()
    if (event.target.value == 'once') {
      var html = '<div class="form-group">'
      html += '<label class="col-xs-3 control-label">{{En date du}}</label>'
      html += '<div class="col-xs-9">'
      html += '<input class="form-control in_datepicker" id="mod_cron_in_dateScenarioTrigger">'
      html += '</div>'
      html += '</div>'
      html += '<br><span id="mod_cron_span_cronResult" class="pull-right"></span>'
      scheduleDiv.insertAdjacentHTML('beforeend', html)
      jeedomUtils.datePickerInit('Y-m-d H:i:00')

      document.getElementById('mod_cron_in_dateScenarioTrigger').addEventListener('change', function(event) {
        if (event.target.value != '') {
          var date = new Date(Date.parse(event.target.value.replace(/-/g, "/")))
          var minute = (date.getMinutes() < 10 ? '0' : '') + date.getMinutes()
          var hour = (date.getHours() < 10 ? '0' : '') + date.getHours()
          var strdate = (date.getDate() < 10 ? '0' : '') + date.getDate()
          var month = ((date.getMonth() + 1) < 10 ? '0' : '') + (date.getMonth() + 1)
          var cron = minute + ' ' + hour + ' ' + strdate + ' ' + month + ' * ' + date.getFullYear()
          document.getElementById('mod_cron_span_cronResult').textContent = cron
        }
      })
    } else {
      var html = ''
      html += '<div id="mod_cron_div_cronGenerator"></div>'
      html += '<br><span id="mod_cron_span_cronResult"  class="pull-right">* * * * *</span>'
      scheduleDiv.insertAdjacentHTML('beforeend', html)

      var generator = document.getElementById('mod_cron_div_cronGenerator')
      generator.empty()
      new jeeCron(generator, {
        initial: '* * * * *',
        customValues: {
          "5 Minutes" : "*/5 * * * *",
          "10 Minutes" : "*/10 * * * *",
          "15 Minutes" : "*/15 * * * *",
          "20 Minutes" : "*/20 * * * *",
          "30 Minutes" : "*/30 * * * *",
        },
        onChange: function(_value) {
          document.getElementById('mod_cron_span_cronResult').textContent = this.value() || ''
        }
      })
      document.querySelector('#mod_cron_div_scheduleConfig select[name="cron-period"]').triggerEvent('change')
    }
  })

  document.querySelector('#mod_insertCron #mod_cron_sel_scheduleMode').triggerEvent('change')
})()
</script>
