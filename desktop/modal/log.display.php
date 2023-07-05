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
  'jeephp2js.md_logDislay_Name' => init('log', 'event'),
  'jeephp2js.md_logDislay_defaultSearch' => init('search', '')
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

<div id="md_logDislay" data-modalType="md_logDislay">
  <div class="input-group pull-right">
    <span class="input-group-btn" style="display: inline;">
      <span class="label-sm">{{Log brut}}</span>
      <input type="checkbox" id="brutlogcheck" />
      <input class="input-sm roundedLeft" id="in_eventLogSearch" style="width : 200px;margin-left:5px;" placeholder="{{Rechercher}}" />
      </a><a id="bt_resetLogSearch" class="btn btn-sm"><i class="fas fa-times"></i>
      <a class="btn btn-warning btn-sm" data-state="1" id="bt_eventLogStopStart"><i class="fas fa-pause"></i> {{Pause}}
      </a><a class="btn btn-success btn-sm" id="bt_logdisplaydownloadLog"><i class="fas fa-cloud-download-alt"></i> {{Télécharger}}
      </a><a class="btn btn-warning btn-sm" id="bt_logdisplayclearLog"><i class="fas fa-times"></i> {{Vider}}
      </a><a class="btn btn-danger roundedRight btn-sm" id="bt_logdisplayremoveLog"><i class="far fa-trash-alt"></i> {{Supprimer}}</a>
    </span>
  </div>
  <br/><br/>
<pre id='pre_eventlog' style='overflow: auto; height: calc(100% - 110px); width:100%;'></pre>
</div>

<script>
(function() {// Self Isolation!
  document.getElementById('brutlogcheck').addEventListener('click', function(event) {
    jeedom.log.autoUpdateDelta({
      log: jeephp2js.md_logDislay_Name,
      display: document.getElementById('pre_eventlog'),
      search: document.getElementById('in_eventLogSearch'),
      control: document.getElementById('bt_eventLogStopStart')
    })
  })

  jeedom.log.autoUpdateDelta({
    log: jeephp2js.md_logDislay_Name,
    default_search: jeephp2js.md_logDislay_defaultSearch,
    display: document.getElementById('pre_eventlog'),
    search: document.getElementById('in_eventLogSearch'),
    control: document.getElementById('bt_eventLogStopStart')
  })

  document.getElementById('bt_resetLogSearch').addEventListener('click', function (event) {
    document.getElementById('in_eventLogSearch').jeeValue('')
  })

  document.getElementById("bt_logdisplayclearLog").addEventListener('click', function(event) {
    jeedom.log.clear({
      log: jeephp2js.md_logDislay_Name
    })
  })

  document.getElementById("bt_logdisplayremoveLog").addEventListener('click', function(event) {
    jeedom.log.remove({
      log: jeephp2js.md_logDislay_Name
    })
  })

  document.getElementById('bt_logdisplaydownloadLog').addEventListener('click', function(event) {
    if (document.getElementById('pre_eventlog').textContent == '') {
      jeeDialog.alert('{{Le log est vide.}}')
      return false
    }
    window.open('core/php/downloadFile.php?pathfile=log/' + jeephp2js.md_logDislay_Name, "_blank", null)
  })
})()
</script>