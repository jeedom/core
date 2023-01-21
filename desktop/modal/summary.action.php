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
if (init('object_id') == '') {
  $virtual = eqLogic::byLogicalId('summaryglobal', 'virtual');
} else {
  $virtual = eqLogic::byLogicalId('summary' . init('object_id'), 'virtual');
}
if (!is_object($virtual)) {
  throw new Exception(__('L\'objet n\'existe pas :', __FILE__) . ' ' . init('object_id'));
}
$removeCmd = array();
foreach ($virtual->getCmd() as $cmd) {
  if ($cmd->getType() == 'action' && $cmd->getConfiguration('summary::key') == init('summary')) {
    continue;
  }
  $removeCmd[] = $cmd->getId();
}
sendVarToJS([
  'jeephp2js.md_summaryAction_cmdToRemove' => $removeCmd,
  'jeephp2js.md_summaryAction_eqHtml' => urlencode($virtual->toHtml('dashboard')),
  'jeephp2js.md_summaryAction_coords' => init('coords')
]);
?>

<div id="div_summaryAction" data-modalType="md_summaryAction"></div>

<script>
(function() {// Self Isolation!
  var divSummaryAction = jeeDialog.get('#md_summaryAction', 'content').querySelector('#div_summaryAction')

  //remove commands prior to DOM injection:
  var eqLogic = domUtils.parseHTML(decodeURIComponent(jeephp2js.md_summaryAction_eqHtml.replace(/\+/g, ' '))).childNodes[0]
  for (id of jeephp2js.md_summaryAction_cmdToRemove) {
    eqLogic.querySelector('div.cmd.cmd-widget[data-cmd_id="' + id + '"]')?.remove()
  }

  //eqLogic UI:
  eqLogic.querySelector('div.widget-name')?.remove()
  eqLogic.setAttribute('data-eqLogic_id', -1)
  eqLogic.querySelector('.verticalAlign')?.removeClass('verticalAlign')
  divSummaryAction.appendChild(eqLogic)

  //calcul commands width:
  var eqWidth = 0
  eqLogic.querySelectorAll('div.cmd-widget').forEach(widget => {
    eqWidth += widget.offsetWidth + 10
  })
  eqLogic.style.width = eqWidth + 'px'

  //Set modal:
  var modal = jeeDialog.get('#div_summaryAction', 'dialog')

  modal.style.width = eqLogic.offsetWidth + 10 + 'px'
  modal.style.height = eqLogic.offsetHeight + 20 + 'px'

  var coords = jeephp2js.md_summaryAction_coords.split('::')
  var mouseX = coords[0]
  var mouseY = coords[1] - 20
  if (mouseY < 55) mouseY = 55 //Prevent over menu
  modal.style.left = mouseX - (modal.offsetWidth / 2) + 'px'
  modal.style.top = mouseY + 'px'
})()
</script>