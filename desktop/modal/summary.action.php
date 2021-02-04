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
if(init('object_id') == ''){
  $virtual = eqLogic::byLogicalId('summaryglobal', 'virtual');
}else{
  $virtual = eqLogic::byLogicalId('summary' . init('object_id'), 'virtual');
}
if(!is_object($virtual)){
  throw new Exception(__('L\'objet n\'existe pas : ', __FILE__) . init('object_id'));
}
$removeCmd = array();
foreach ($virtual->getCmd() as $cmd) {
  if($cmd->getType() == 'action' && $cmd->getConfiguration('summary::key') == init('summary')){
    continue;
  }
  $removeCmd[] = $cmd->getId();
}
echo '<div id="div_summaryAction">';
echo $virtual->toHtml('dashboard');
sendVarToJs('summary_cmd_to_remove',$removeCmd );
echo '</div>';
?>
<script>
setTimeout(function(){
  $('#div_summaryAction .widget-name').remove();
  $('#div_summaryAction .eqLogic-widget').width('100%')
  for(var i in summary_cmd_to_remove){
    $('#div_summaryAction .cmd[data-cmd_id='+summary_cmd_to_remove[i]+']').remove();
  }
  $('#md_modal').dialog({height: $('#div_summaryAction .eqLogic-widget').height() + 80})
}, 10);
</script>