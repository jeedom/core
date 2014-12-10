<?php
if (!isConnect()) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
?>

<div class="row row-overflow">
    <div class="col-lg-3 col-md-4 col-sm-5 bs-sidebar">
        <ul id="ul_history" class="nav nav-list bs-sidenav">
            <li class="nav-header">{{Historique}}</li>
            <li class="filter"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" /></li>
            <?php
            foreach (cmd::allHistoryCmd() as $cmd) {
                echo '<li class="cursor li_history" data-cmd_id="' . $cmd->getId() . '"><a class="history"><i class="fa fa-trash-o remove"></i> <i class="fa fa-share export"></i> ' . $cmd->getHumanName(true) . '</a></li>';
            }
            ?>
        </ul>
    </div>

    <div class="col-lg-9 col-md-8 col-sm-7" style="border-left: solid 1px #EEE; padding-left: 25px;height: 600px;">
        <select class="form-control pull-right" id="sel_chartType" style="width: 200px;">
            <option value="line">{{Ligne}}</option>
            <option value="areaspline">{{Areaspline}}</option>
            <option value="column">{{Barre}}</option>
        </select>
        <span class="pull-right">Variation : <input type="checkbox" id="cb_derive" /></span>
        <span class="pull-right">Escalier : <input type="checkbox" id="cb_step" /></span>
        <div id="div_graph" style="margin-top: 50px;"></div>
    </div>
</div>

<?php include_file("desktop", "history", "js"); ?>
