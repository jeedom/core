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
$cmd = cmd::byId(init('cmd_id'));
if(is_object($cmd)){
  $listeCmds = cmd::byTypeSubType($cmd->getType(), $cmd->getSubType());
}else{
  $listeCmds = cmd::byTypeSubType(init('type'), init('subtype'));
}
if(!is_array($listeCmds) || count($listeCmds) == 0){
  throw new Exception(__('Aucune commande trouvées',__FILE__));
}
?>

<div style="display: none;" id="md_cmdConfigureSelectMultipleAlert"></div>
<div class="input-group pull-right">
  <a class="btn btn-default roundedLeft" id="bt_cmdConfigureSelectMultipleAlertToogle" data-state="0"><i class="fas fa-check-circle"></i> {{Inverser}}
  </a><a class="btn btn-success roundedRight" id="bt_cmdConfigureSelectMultipleAlertApply"><i class="fas fa-check"></i> {{Valider}}</a>
</div>
<br/>
<table class="table table-bordered table-condensed tablesorter" id="table_cmdConfigureSelectMultiple" style="width:100% !important;">
  <thead>
    <tr>
      <th data-sorter="false" data-filter="false"></th>
      <th>{{Objet}}</th>
      <th>{{Equipement}}</th>
      <th>{{Nom}}</th>
    </tr>
  </thead>
  <tbody>
    <?php
    foreach ($listeCmds as $listCmd) {
      $eqLogic = $listCmd->getEqLogic();
      $object = null;
      if (is_object($eqLogic)) {
        $object = $eqLogic->getObject();
      }
      $tr = '';
      $tr .= '<tr data-cmd_id="' . $listCmd->getId() . '">';
      $tr .= '<td>';
      if (is_object($cmd) && $listCmd->getId() == $cmd->getId()) {
        $tr .= '<input type="checkbox" class="selectMultipleApplyCmd" checked/>';
      } else {
        $tr .= '<input type="checkbox" class="selectMultipleApplyCmd" />';
      }
      $tr .= '</td>';
      $tr .= '<td>';
      if(is_object($object)){
        $tr .= $object->getName();
      }
      $tr .= '</td>';
      $tr .= '<td>';
      if(is_object($eqLogic)){
        $tr .= $eqLogic->getName();
      }
      $tr .= '</td>';
      $tr .= '<td>';
      $tr .= $listCmd->getName();
      $tr .= '</td>';
      $tr .= '</tr>';
      echo $tr;
    }
    ?>
  </tbody>
</table>