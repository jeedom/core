<?php
if (!hasRight('cronview', true)) {
    throw new Exception('{{401 - Accès non autorisé}}');
}

?>

{{Processus cron Jeedom :}} <span class="label label-default"><span class="tooltips" id="span_jeecronMasterRuns" title="{{Nombre de processus Jeedom cron maître (doit toujours être inférieur à 2)}}"></span> | 
    <span id="span_jeecronRuns" class="tooltips" title="{{Nombre total de processus Jeedom en cours}}"></span> | 
    <span id="span_nbProcess" class="tooltips" title="{{Nombre total de processus en cours sur la machine}}"></span></span>
    <br/>
<span>{{Charge moyenne}} <span class="label label-default"><span id="span_loadAvg1" class="tooltips" title="{{Charge moyenne du système sur la dernière minute}}"></span> | 
        <span id="span_loadAvg5" class="tooltips" title="{{Charge moyenne du système sur les 5 dernières minutes}}"></span> | 
        <span id="span_loadAvg15" class="tooltips" title="{{Charge moyenne du système sur les 15 dernières minutes}}"></span></span></span>

    <a class="btn btn-success pull-right" id="bt_save" style="margin-top: 5px;"><i class="fa fa-check-circle"></i> {{Enregistrer}}</a>
<a class="btn btn-default pull-right" id="bt_addCron" style="margin-top: 5px;"><i class="fa fa-plus-circle"></i> {{Ajouter}}</a>
<a class="btn btn-default pull-right" id="bt_refreshCron" style="margin-top: 5px;"><i class="fa fa-refresh"></i> {{Rafraîchir}}</a>
<?php
if (config::byKey('enableCron') == 0) {
    echo '<a class="btn btn-success pull-right" id="bt_changeCronState" data-state="1" style="margin-top: 5px;"><i class="fa fa-check"></i> {{Activer le système cron}}</a>';
} else {
    echo '<a class="btn btn-danger pull-right" id="bt_changeCronState" data-state="0" style="margin-top: 5px;"><i class="fa fa-times"></i> {{Désactiver le système cron}}</a>';
}
?>
<br/><br/><br/>
<table id="table_cron" class="table table-bordered table-condensed tablesorter" >
    <thead>
        <tr>
            <th class="id" style="width: 40px;">#</th>
            <th class="" style="width: 50px;" data-sorter="false" data-filter="false"></th>
            <th class="enable" style="width: 80px;">{{Actif}}</th>
            <th class="server" style="width: 100px;">{{Serveur}}</th>
            <th class="pid" style="width: 100px;">{{PID}}</th>
            <th class="deamons" style="width: 80px;">{{Démon}}</th>
            <th class="once" style="width: 80px;">{{Unique}}</th>
            <th class="class" style="width: 120px;">{{Classe}}</th>
            <th class="function" style="width: 120px;">{{Fonction}}</th>
            <th class="schedule" style="width: 170px;"><i class="fa fa-question-circle cursor bt_pageHelp" data-name='cronSyntaxe' style="position: relative; width: 10px;"></i> {{Programmation}}</th>
            <th class="timeout" style="width: 150px;">{{Timeout (min)}}</th>
            <th class="lastRun" style="width: 200px;">{{Dernier lancement}}</th>
            <th class="state" style="width: 80px;">{{Statut}}</th>
            <th class="action" style="width: 50px;" data-sorter="false" data-filter="false"></th>
        </tr>
    </thead>
    <tbody> 
    </tbody>
</table>
<?php include_file('desktop', 'cron', 'js'); ?>
