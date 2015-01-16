<?php
if (!isConnect()) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
?>

<table id="table_addViewDataHidden" style="display: none;">
    <tbody></tbody>
</table>
<table class="table table-condensed table-bordered table-striped tablesorter" id="table_addViewData">
    <thead>
        <tr>
            <th style="width: 50px;">#</th>
            <th style="width: 150px;">{{Objet}}</th>
            <th style="width: 150px;">{{Nom}}</th>
            <th>{{Affichage}}</th>
        </tr>
    </thead>
    <tbody>
        <?php
        foreach (cmd::all() as $cmd) {
            $eqLogic = $cmd->getEqLogic();
            if (!is_object($eqLogic)) {
                continue;
            }
            if ($eqLogic->getIsVisible() == 1 && $cmd->getIsHistorized() == 1) {
                $object = $cmd->getEqLogic()->getObject();
                echo '<tr data-link_id="' . $cmd->getId() . '" data-type="graph" data-viewDataType="cmd">';
                echo '<td>';
                echo '<input type="checkbox" class="enable" />';
                echo '<input class="graphDataOption" data-l1key="link_id" value="' . $cmd->getId() . '" hidden/>';
                echo '</td>';
                echo '<td class="object_name">';
                 echo '<input class="graphDataOption" data-l1key="type" value="cmd" hidden/>';
                if (is_object($object)) {
                    echo $object->getName();
                }
                echo '</td>';
                echo '<td class="name">';
                echo '[' . $eqLogic->getName() . '][';
                echo $cmd->getName() . ']';
                echo '</td>';
                echo '<td class="display">';
                echo '<div class="option">';
                echo '<form class="form-inline">';
                echo '<div class="form-group">';
                echo '<label>Couleur :</label> <select class="graphDataOption form-control" data-l1key="configuration" data-l2key="graphColor" style="width : 110px;background-color:#4572A7;color:white;">';
                echo '<option value="#4572A7" style="background-color:#4572A7;color:white;">{{Bleu}}</option>';
                echo '<option value="#AA4643" style="background-color:#AA4643;color:white;">{{Rouge}}</option>';
                echo '<option value="#89A54E" style="background-color:#89A54E;color:white;">{{Vert}}</option>';
                echo '<option value="#80699B" style="background-color:#80699B;color:white;">{{Violet}}</option>';
                echo '<option value="#00FFFF" style="background-color:#00FFFF;color:white;">{{Bleu ciel}}</option>';
                echo '<option value="#DB843D" style="background-color:#DB843D;color:white;">{{Orange}}</option>';
                echo '<option value="#FFFF00" style="background-color:#FFFF00;color:white;">{{Jaune}}</option>';
                echo '<option value="#FE2E9A" style="background-color:#FE2E9A;color:white;">{{Rose}}</option>';
                echo '<option value="#000000" style="background-color:#000000;color:white;">{{Noir}}</option>';
                echo '<option value="#3D96AE" style="background-color:#3D96AE;color:white;">{{Vert/Bleu}}</option>';
                echo '</select> ';
                echo '</div> ';
                echo '<div class="form-group">';
                echo ' <label>Type :</label> <select class="graphDataOption form-control" data-l1key="configuration" data-l2key="graphType" style="width : 100px;">';
                echo '<option value="line">{{Ligne}}</option>';
                echo '<option value="area">{{Aire}}</option>';
                echo '<option value="column">{{Colonne}}</option>';
                echo '</select> ';
                echo '</div> ';
                echo '<div class="form-group">';
                echo '';
                echo ' <label>Escalier : <input type="checkbox" class="graphDataOption" data-l1key="configuration" data-l2key="graphStep">';
                echo '</label>';
                echo ' <label>Empiler : <input type="checkbox" class="graphDataOption" data-l1key="configuration" data-l2key="graphStack">';
                echo '</label>';
                echo ' <label>Variation : <input type="checkbox" class="graphDataOption" data-l1key="configuration" data-l2key="derive">';
                echo '</label>';
                echo ' <label>Echelle :</label> <select class="graphDataOption form-control" data-l1key="configuration" data-l2key="graphScale" style="width : 60px;">';
                echo '<option value="0">Droite</option>';
                echo '<option value="1">Gauche</option>';
                echo '</select>';

                echo '</div>';
                echo '</form>';
                echo '</div>';
                echo '</td>';
                echo '</tr>';
            }
        }
        ?>
    </tbody>
</table>

<script>
    initTableSorter();
</script>