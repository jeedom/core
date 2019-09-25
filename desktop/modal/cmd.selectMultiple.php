<?php
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
<div>
  <a class="btn btn-default" id="bt_cmdConfigureSelectMultipleAlertToogle" data-state="0"><i class="far fa-check-circle"></i> {{Inverser}}</a>
  <a class="btn btn-success pull-right" id="bt_cmdConfigureSelectMultipleAlertApply"><i class="fas fa-check"></i> {{Valider}}</a>
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
      if(is_object($eqLogic)){
        $object = $eqLogic->getObject();
      }
      echo '<tr data-cmd_id="' . $listCmd->getId() . '">';
      echo '<td>';
      if (is_object($cmd) && $listCmd->getId() == $cmd->getId()) {
        echo '<input type="checkbox" class="selectMultipleApplyCmd" checked/>';
      } else {
        echo '<input type="checkbox" class="selectMultipleApplyCmd" />';
      }
      echo '</td>';
      echo '<td>';
      if(is_object($object)){
        echo $object->getName();
      }
      echo '</td>';
      echo '<td>';
      if(is_object($eqLogic)){
        echo $eqLogic->getName();
      }
      echo '</td>';
      echo '<td>';
      echo $listCmd->getName();
      echo '</td>';
      echo '</tr>';
    }
    ?>
  </tbody>
</table>
