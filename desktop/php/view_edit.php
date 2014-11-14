<?php
if (!hasRight('viewedit')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
include_file('3rdparty', 'jquery.tablesorter/theme.bootstrap', 'css');
include_file('3rdparty', 'jquery.tablesorter/jquery.tablesorter.min', 'js');
include_file('3rdparty', 'jquery.tablesorter/jquery.tablesorter.widgets.min', 'js');
?>

<style>
    .viewZone{
        padding: 9.5px;
        margin: 0 0px 5px;
        font-size: 13px;
        line-height: 20px;
        background-color: #f5f5f5;
        border: 1px solid #ccc;
        border: 1px solid rgba(0,0,0,0.15);
        -webkit-border-radius: 4px;
        -moz-border-radius: 4px;
        border-radius: 4px;
    }
</style>

<div class="row row-overflow">
    <div class="col-lg-2">
        <div class="bs-sidebar">
            <ul id="ul_view" class="nav nav-list bs-sidenav">
                <a id="bt_addView" class="btn btn-default" style="width : 100%;margin-top : 5px;margin-bottom: 5px;"><i class="fa fa-plus-circle"></i> {{Creer une vue}}</a>
                <li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
                <?php
                foreach (view::all() as $view) {
                    echo '<li class="cursor li_view" data-view_id="' . $view->getId() . '"><a>' . $view->getName() . '</a></li>';
                }
                ?>
            </ul>
        </div>
    </div>

    <div class="col-lg-10" style="display: none;" id="div_view">
        <legend style="height: 35px;">
            <a class="btn btn-default btn-xs" id="bt_editView"><i class="fa fa-pencil"></i> {{Renommer}}</a> {{Vue}}
            <a class="btn btn-success btn-xs pull-right" id="bt_viewResult"><i class="fa fa fa-eye"></i> {{Voir le resultat}}</a>
            <a class="btn btn-default btn-xs pull-right" id="bt_addviewZone"><i class="fa fa-plus-circle"></i> {{Ajouter une zone}}</a>
        </legend>

        <div id="div_viewZones" style="margin-top: 10px;"></div>


        <div class="form-actions" style="margin-top: 10px;">

            <a class="btn btn-danger" id="bt_removeView"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
            <a class="btn btn-success" id="bt_saveView"><i class="fa fa-check-circle"></i> {{Enregistrer}}</a>
        </div>
    </div>

</div>

<div class="modal fade" id="md_addEditviewZone">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
                <h4 class="modal-title">{{Ajouter/Editer viewZone}}</h4>
            </div>
            <div class="modal-body">
                <div class="alert alert-danger div_alert" style="display: none;" id="div_addEditviewZoneError"></div>
                <input id="in_addEditviewZoneEmplacement"  style="display : none;" />
                <form class="form-horizontal" onsubmit="return false;">
                    <div class="form-group">
                        <label class="col-lg-2 control-label">{{Nom}}</label>
                        <div class="col-lg-5">
                            <input id="in_addEditviewZoneName" class="form-control" placeholder="{{Nom}}" />
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 control-label">{{Type}}</label>
                        <div class="col-lg-5">
                            <select class="form-control" id="sel_addEditviewZoneType">
                                <option value="widget">{{Widget}}</option>
                                <option value="graph">{{Graphique}}</option>
                            </select>
                        </div>
                    </div>
                </form>
            </div>
            <div class="modal-footer">
                <a class="btn btn-danger" data-dismiss="modal">{{Annuler}}</a>
                <a class="btn btn-success" id="bt_addEditviewZoneSave"><i class="fa fa-save"></i> {{Enregistrer}}</a>
            </div>
        </div>
    </div>
</div>


<div id="md_addViewData" title="Ajouter widget/graph">
    <table id="table_addViewDataHidden" style="display: none;">
        <tbody></tbody>
    </table>
    <table class="table table-condensed table-bordered table-striped tablesorter" id="table_addViewData">
        <thead>
            <tr>
                <th style="width: 50px;">#</th>
                <th style="width: 150px;">{{Type}}</th>
                <th style="width: 150px;">{{Objet}}</th>
                <th style="width: 150px;">{{Nom}}</th>
                <th>Affichage</th>
            </tr>
        </thead>
        <tbody>
            <?php
            foreach (cmd::all() as $cmd) {
                $eqLogic = $cmd->getEqLogic();
                if (!is_object($eqLogic)) {
                    continue;
                }
                if ($cmd->getIsHistorized() == 1) {
                    $object = $cmd->getEqLogic()->getObject();
                    echo '<tr data-link_id="' . $cmd->getId() . '" data-type="graph" data-viewDataType="cmd">';
                    echo '<td>';
                    echo '<input type="checkbox" class="enable" />';
                    echo '<input class="viewDataOption" data-l1key="link_id" value="' . $cmd->getId() . '" hidden/>';
                    echo '</td>';
                    echo '<td class="type">';
                    echo 'Commande';
                    echo '<input class="viewDataOption" data-l1key="type" value="cmd" hidden/>';
                    echo '</td>';
                    echo '<td class="object_name">';
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
                    echo '<label>Couleur :</label> <select class="viewDataOption form-control" data-l1key="configuration" data-l2key="graphColor" style="width : 110px;background-color:#4572A7;color:white;">';
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
                    echo ' <label>Type :</label> <select class="viewDataOption form-control" data-l1key="configuration" data-l2key="graphType" style="width : 100px;">';
                    echo '<option value="line">{{Ligne}}</option>';
                    echo '<option value="area">{{Aire}}</option>';
                    echo '<option value="column">{{Colonne}}</option>';
                    echo '</select> ';
                    echo '</div> ';
                    echo '<div class="form-group">';
                    echo '';
                    echo ' <label>Escalier : <input type="checkbox" class="viewDataOption" data-l1key="configuration" data-l2key="graphStep">';
                    echo '</label>';
                    echo ' <label>Empiler : <input type="checkbox" class="viewDataOption" data-l1key="configuration" data-l2key="graphStack">';
                    echo '</label>';
                    echo ' <label>Variation : <input type="checkbox" class="viewDataOption" data-l1key="configuration" data-l2key="derive">';
                    echo '</label>';
                    echo ' <label>Echelle :</label> <select class="viewDataOption form-control" data-l1key="configuration" data-l2key="graphScale" style="width : 60px;">';
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

            foreach (eqLogic::all() as $eqLogic) {
                $object = $eqLogic->getObject();
                echo '<tr data-link_id="' . $eqLogic->getId() . '" data-type="widget" data-viewDataType="eqLogic">';
                echo '<td>';
                echo '<input type="checkbox" class="enable" />';
                echo '<input class="viewDataOption" data-l1key="type" value="eqLogic" hidden/>';
                echo '<input class="viewDataOption" data-l1key="link_id" value="' . $eqLogic->getId() . '" hidden/>';
                echo '</td>';
                echo '<td class="type">';
                echo 'Equipement';
                echo '</td>';
                echo '<td class="object_name">';
                if (is_object($object)) {
                    echo $object->getName();
                }
                echo '</td>';
                echo '<td class="name">';
                echo $eqLogic->getName();
                echo '</td>';
                echo '<td></td>';
                echo '</tr>';
            }
            foreach (scenario::all() as $scenario) {
                echo '<tr data-link_id="' . $scenario->getId() . '" data-type="widget" data-viewDataType="scenario">';
                echo '<td>';
                echo '<input type="checkbox" class="enable" />';
                echo '<input class="viewDataOption" data-l1key="type" value="scenario" hidden/>';
                echo '<input class="viewDataOption" data-l1key="link_id" value="' . $scenario->getId() . '" hidden/>';
                echo '</td>';
                echo '<td class="type">';
                echo 'Scénario';
                echo '</td>';
                echo '<td class="object_name">';
                $object = $scenario->getObject();
                if (is_object($object)) {
                    echo $object->getName();
                } else {
                    echo '{{Aucun}}';
                }
                echo '</td>';
                echo '<td class="name">';
                echo $scenario->getName();
                echo '</td>';
                echo '<td></td>';
                echo '</tr>';
            }
            ?>
        </tbody>
    </table>
</div>

<?php include_file('desktop', 'view_edit', 'js'); ?>