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
  'summary_cmd_to_remove' => $removeCmd,
  'edqlogicHtml' => urlencode($virtual->toHtml('dashboard'))
]);
?>

<div id="div_summaryAction"></div>

<script>
  var $divSummaryAction = $('#div_summaryAction')

  //remove commands prior to DOM injection:
  var $eqLogic = $(decodeURIComponent(edqlogicHtml.replace(/\+/g, ' ')))
  for (var i in summary_cmd_to_remove) {
    $eqLogic.find('.cmd.cmd-widget[data-cmd_id='+summary_cmd_to_remove[i]+']').remove()
  }
  //eqLogic UI:
  $eqLogic.find('.widget-name').remove()
  $eqLogic.attr('data-eqLogic_id', -1)
  $eqLogic.find('.verticalAlign').removeClass('verticalAlign')

  $divSummaryAction.prepend($eqLogic)

  //modal:
  $('#md_modal').parent('.ui-dialog').addClass('summaryActionMain')
  $('#md_modal').dialog('open')

  //calcul commands width:
  $eqLogic = $divSummaryAction.find('.eqLogic-widget')
  var eqWidth = 0
  $eqLogic.find('.cmd-widget').each(function() {
    eqWidth += $(this).outerWidth(true) + 5
  })
  $eqLogic.css('width', eqWidth)

  //Set modal:
  $('#md_modal').dialog({
    height: $divSummaryAction.find('.eqLogic-widget').outerHeight(true) + 30,
    width: $divSummaryAction.find('.eqLogic-widget').outerWidth(true) + 10
  })

  var mouseY = jeedomUtils.mouseY - 20
  if (mouseY < 55) mouseY = 55
  $('#md_modal').parent('.ui-dialog').css({
    top: mouseY,
    left: jeedomUtils.mouseX - $('#md_modal').parent('.ui-dialog').width() / 2
  })

</script>