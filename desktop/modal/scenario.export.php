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
$scenario = scenario::byId(init('scenario_id'));
if (!is_object($scenario)) {
  throw new Exception('{{Scénario introuvable}}');
}
?>

<div id="md_scenarioExport" data-modalType="md_scenarioExport">
<a class="btn btn-success pull-right bt_downloadScenario"><i class="fa fa-download"></i> {{Télécharger}}</a>
<a class="btn btn-success pull-right bt_copyScenario"><i class="fa fa-copy"></i> {{Copier}}</a>
<br><br>
<?php
echo '<textarea id="scExport" style="height:calc(100% - 40px);width:100%">' . $scenario->export() . '</textarea>';
?>
</div>

<script>
document.querySelector('#md_scenarioExport .bt_downloadScenario').addEventListener('click', function(event) {
  var content = document.getElementById('scExport').textContent
  content = content.replace(/\n/g, "\r\n")

  dataStr = "data:text/json;charset=utf-8," + encodeURIComponent(content)
  downloadAnchorNode = document.createElement('a')
  downloadAnchorNode.setAttribute("href",     dataStr)
  downloadAnchorNode.setAttribute("target", "_blank")
  downloadAnchorNode.setAttribute("download", 'scenario.txt')
  document.body.appendChild(downloadAnchorNode)
  downloadAnchorNode.click()
  downloadAnchorNode.remove()
})

document.querySelector('#md_scenarioExport .bt_copyScenario').addEventListener('click', function(event) {
  document.getElementById('scExport').triggerEvent('select')
  document.execCommand("copy")
  document.getElementById('scExport').blur()
})
</script>