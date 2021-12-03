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
  throw new Exception('401 Unauthorized');
}
sendVarToJS([
  'log_display_name' => init('log', 'event'),
  'log_default_search' => init('search', '')
]);

if(init('log','event') == 'event'){
  if(log::getLogLevel('event') > 200){
    $alert = '<div class="alert alert-danger">{{Attention votre niveau de log (event) est inférieur à info, vous ne pouvez donc pas voir de temps réel.}}';
    $alert .= ' <a href="/index.php?v=d&p=administration#logtab" class="success">{{Configuration}}</a>';
    $alert .= '</div>';
    echo $alert;
  }
}
?>

<div class="input-group pull-right">
  <span class="input-group-btn" style="display: inline;">
    <span class="label-sm">{{Log brut}}</span>
    <input type="checkbox" id="brutlogcheck" autoswitch="1"/>
    <i id="brutlogicon" class="fas fa-exclamation-circle icon_orange"></i>
    <input class="input-sm roundedLeft" id="in_eventLogSearch" style="width : 200px;margin-left:5px;" placeholder="{{Rechercher}}" />
    <a class="btn btn-warning btn-sm" data-state="1" id="bt_eventLogStopStart"><i class="fas fa-pause"></i> {{Pause}}
    </a><a class="btn btn-success btn-sm" id="bt_logdisplaydownloadLog"><i class="fas fa-cloud-download-alt"></i> {{Télécharger}}
    </a><a class="btn btn-warning btn-sm" id="bt_logdisplayclearLog"><i class="fas fa-times"></i> {{Vider}}
    </a><a class="btn btn-danger roundedRight btn-sm" id="bt_logdisplayremoveLog"><i class="far fa-trash-alt"></i> {{Supprimer}}</a>
  </span>
</div>
<br/><br/>
<pre id='pre_eventlog' style='overflow: auto; height: calc(100% - 90px);width:100%;'></pre>

<script>
var $rawLogCheck = $('#brutlogcheck')
$rawLogCheck.on('click').on('click', function () {
  $rawLogCheck.attr('autoswitch', 0)

  var scroll = $('#pre_eventlog').scrollTop()
  jeedom.log.autoupdate({
    log: log_display_name,
    display: $('#pre_eventlog'),
    search: $('#in_eventLogSearch'),
    control: $('#bt_eventLogStopStart'),
    once: 1
  })
  $('#pre_eventlog').scrollTop(scroll)
})

jeedom.log.autoupdate({
  log: log_display_name,
  default_search: log_default_search,
  display: $('#pre_eventlog'),
  search: $('#in_eventLogSearch'),
  control: $('#bt_eventLogStopStart')
})

$("#bt_logdisplayclearLog").on('click', function(event) {
  jeedom.log.clear({
    log: log_display_name
  })
})

$("#bt_logdisplayremoveLog").on('click', function(event) {
  jeedom.log.remove({
    log: log_display_name
  })
})

$('#bt_logdisplaydownloadLog').click(function() {
  window.open('core/php/downloadFile.php?pathfile=log/' + log_display_name, "_blank", null)
})
</script>