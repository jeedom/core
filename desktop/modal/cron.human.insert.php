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

<form class="form-horizontal" style="overflow:hidden !important;">
  <div class="form-group mode schedule">
    <label class="col-xs-3 control-label" >{{A exécuter}}</label>
    <div class="col-xs-9">
      <select class="form-control" id="mod_cron_sel_scheduleMode">
        <option value="once">{{une seule fois}}</option>
        <option value="repete">{{récurrent}}</option>
      </select>
    </div>
  </div>
  <div class="mode schedule" id="mod_cron_div_scheduleConfig"></div>
</form>

<script>
$('#mod_cron_sel_scheduleMode').on('change', function() {
  $('#mod_cron_div_scheduleConfig').empty();
  if ($(this).value() == 'once') {
    var html = '<div class="form-group">'
    html += '<label class="col-xs-3 control-label">{{En date du}}</label>'
    html += '<div class="col-xs-9">'
    html += '<input class="form-control" id="mod_cron_in_dateScenarioTrigger">'
    html += '</div>'
    html += '</div>'
    html += '<span id="mod_cron_span_cronResult" style="display: none;"></span>'
    $('#mod_cron_div_scheduleConfig').append(html)
    $('#mod_cron_in_dateScenarioTrigger').datetimepicker({lang: 'fr',
    i18n: {
      fr: {
        months: [
          'Janvier', 'Février', 'Mars', 'Avril',
          'Mai', 'Juin', 'Juillet', 'Aout',
          'Septembre', 'Octobre', 'Novembre', 'Décembre',
        ],
        dayOfWeek: [
          "Di", "Lu", "Ma", "Me",
          "Je", "Ve", "Sa",
        ]
      }
    },
    format: 'Y-m-d H:i:00',
    step: 15
  })
  $('#mod_cron_in_dateScenarioTrigger').on('change', function() {
    if ($(this).value() != '') {
      var date = new Date(Date.parse($(this).value().replace(/-/g, "/")))
      var minute = (date.getMinutes() < 10 ? '0' : '') + date.getMinutes()
      var hour = (date.getHours() < 10 ? '0' : '') + date.getHours()
      var strdate = (date.getDate() < 10 ? '0' : '') + date.getDate()
      var month = ((date.getMonth() + 1) < 10 ? '0' : '') + (date.getMonth() + 1)
      var cron = minute + ' ' + hour + ' ' + strdate + ' ' + month + ' ' + date.getDay() + ' ' + date.getFullYear()
      $('#mod_cron_span_cronResult').value(cron)
    }
  })
} else {
  var html = ''
  html += '<div id="mod_cron_div_cronGenerator"></div>'
  html += '<span id="mod_cron_span_cronResult" style="display: none;">* * * * *</span>'
  $('#mod_cron_div_scheduleConfig').append(html)
  $('#mod_cron_div_cronGenerator').empty().cron({
    initial: '* * * * *',
    customValues: {
      "5 Minutes" : "*/5 * * * *",
      "10 Minutes" : "*/10 * * * *",
      "15 Minutes" : "*/15 * * * *",
      "20 Minutes" : "*/20 * * * *",
      "30 Minutes" : "*/30 * * * *",
    },
    onChange: function() {
      $('#mod_cron_span_cronResult').text($(this).cron("value"))
    }
  })
}
})

$('#mod_cron_sel_scheduleMode').trigger('change')

function mod_insertCron() {}

mod_insertCron.getValue = function() {
  return $('#mod_cron_span_cronResult').text()
}
</script>