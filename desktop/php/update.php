<?php
if (!hasRight('updateview',true)) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
include_file('3rdparty', 'jquery.tablesorter/theme.bootstrap', 'css');
include_file('3rdparty', 'jquery.tablesorter/jquery.tablesorter.min', 'js');
include_file('3rdparty', 'jquery.tablesorter/jquery.tablesorter.widgets.min', 'js');
?>
<div>
    <?php
    if (config::byKey('jeedom::licence') < 5 || config::byKey('market::showPromotion') == 1) {
        echo market::getPromo();
    }
    ?>
</div><br/>
<div class="row">
    <div class="col-lg-8">
        <a class="btn btn-warning pull-right" id="bt_checkAllUpdate"><i class="fa fa-refresh"></i> Vérifier les objets et mises à jour</a> 
        <div class="btn-group pull-right">
            <a href="#" class="bt_updateAll btn btn-default"  data-level="0" data-mode=""><i class="fa fa-check"></i> {{Mettre à jour}}</a>
            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown"> 
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="#" class="bt_updateAll" data-level="0" data-mode="">{{Tout}}</a></li>
                    <li><a href="#" class="bt_updateAll expertModeVisible" data-level="0" data-mode="force">{{Tout forcer}}</a></li>
                    <li><a href="#" class="bt_updateAll" data-level="1" data-mode="">{{Plugins seulement}}</a></li>
                    <li><a href="#" class="bt_updateAll expertModeVisible" data-level="1" data-mode="force">{{Plugins seulement forcés}}</a></li>
                    <li><a href="#" class="bt_updateAll" data-level="-1" data-mode="">{{Jeedom seulement}}</a></li>
                    <li><a href="#" class="bt_updateAll expertModeVisible" data-level="-1" data-mode="force">{{Jeedom seulement forcé}}</a></li>
                </ul>
            </div>
        </div>
        <br/><br/>
        <table class="table table-condensed table-bordered tablesorter tablefixheader" id="table_update" style="margin-top: 5px;">
            <thead>
                <tr>
                    <th>{{Type}}</th>
                    <th>{{Nom}}</th>
                    <th>{{Version actuelle}}</th>
                    <th>{{Version disponible}}</th>
                    <th>{{Statut}}</th>
                    <th data-sorter="false" data-filter="false" style="width: 400px;">{{Actions}}</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div class="col-lg-4">
        <legend>{{Informations :}}</legend>
        <pre id="pre_updateInfo"></pre>
    </div>
</div>

<?php include_file('desktop', 'update', 'js'); ?>