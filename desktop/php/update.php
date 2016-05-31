<?php
if (!hasRight('updateview', true)) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<br/>
<div class="row row-overflow">
    <div class="col-sm-8">
        <i class="fa fa-clock-o"></i>  {{Dernière vérification : }}<span class="label label-info" id="span_lastUpdateCheck" style="margin-bottom: 5px;font-size:1em;"></span>
        {{Dernière mise à jour du core : }}<span class="label label-info" id="span_lastCoreUpdate" style="margin-bottom: 5px;font-size:1em;"></span>
        <a class="btn btn-success pull-right" id="bt_saveUpdate" style="margin-top:5px;"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
        <a class="btn btn-info pull-right" id="bt_checkAllUpdate" style="margin-top:5px;"><i class="fa fa-refresh"></i> {{Vérifier les mises à jour}}</a>
        <div class="btn-group pull-right" style="margin-top:5px;">
            <a href="#" class="bt_updateAll btn btn-default"  data-level="0" data-mode=""><i class="fa fa-check"></i> {{Mettre à jour}}</a>
            <div class="btn-group">
                <button type="button" class="btn btn-default dropdown-toggle" data-toggle="dropdown">
                    <span class="caret"></span>
                </button>
                <ul class="dropdown-menu">
                    <li><a href="#" class="bt_updateAll expertModeVisible" data-level="0" data-mode="force"> <i class="fa fa-gavel"></i> {{Tout forcer}}</a></li>
                    <li><a href="#" class="bt_updateAll" data-level="1" data-mode=""><i class="fa fa-cube"></i> {{Plugins seulement}}</a></li>
                    <li><a href="#" class="bt_updateAll expertModeVisible" data-level="1" data-mode="force"><i class="fa fa-cube"></i> <i class="fa fa-gavel"></i> {{Plugins seulement forcés}}</a></li>
                    <li><a href="#" class="bt_updateAll" data-level="-1" data-mode=""><i class="fa fa-database"></i> {{Jeedom seulement}}</a></li>
                    <li><a href="#" class="bt_updateAll expertModeVisible" data-level="-1" data-mode="force"><i class="fa fa-database"></i> <i class="fa fa-gavel"></i> {{Jeedom seulement forcé}}</a></li>
                    <li><a href="#" class="expertModeVisible" id="bt_reapplyUpdate"><i class="fa fa-retweet"></i> {{Réappliquer une mise à jour}}</a></li>
                </ul>
            </div>
        </div>
        <br/><br/>
        <table class="table table-condensed table-bordered tablesorter" id="table_update" style="margin-top: 5px;">
            <thead>
                <tr>
                    <th data-sorter="false" style="width:50px;"></th>
                    <th>{{Nom}}</th>
                    <th>{{Version}}</th>
                    <th data-sorter="false" data-filter="false">{{Options}}</th>
                    <th data-sorter="false" data-filter="false">{{Actions}}</th>
                </tr>
            </thead>
            <tbody>
            </tbody>
        </table>
    </div>
    <div class="col-sm-4">
        <legend><i class="fa fa-info-circle"></i>  {{Informations :}}</legend>
        <pre id="pre_updateInfo"></pre>
    </div>
</div>

<div id="md_specifyUpdate">
 <form class="form-horizontal">
    <fieldset>
       <div class="form-group">
           <label class="col-xs-6 control-label">{{Mise à jour à réappliquer}}</label>
           <div class="col-xs-6">
            <select id="sel_updateVersion" class="form-control">
                <option value="">{{Aucune}}</option>
                <?php
$udpates = array();
foreach (update::listCoreUpdate() as $udpate) {
	$udpates[str_replace(array('.php', '.sql'), '', $udpate)] = str_replace(array('.php', '.sql'), '', $udpate);
}
usort($udpates, 'version_compare');
foreach ($udpates as $value) {
	echo '<option value="' . $value . '">' . $value . '</option>';
}
?>
           </select>
       </div>
   </div>
   <div class="form-group">
    <label class="col-xs-6 control-label">{{Mode forcé}}</label>
    <div class="col-xs-4">
        <input type="checkbox" id="cb_forceReapplyUpdate" checked />
    </div>
</div>
<div class="form-group">
    <label class="col-xs-6 control-label">{{Tout depuis cette version}}</label>
    <div class="col-xs-4">
        <input type="checkbox" id="cb_allFromThisUpdate" checked />
    </div>
</div>
</fieldset>
</form>
<a class="btn btn-success pull-right" style="color:white;" id="bt_reapplySpecifyUpdate"><i class="fa fa-check"></i> {{Valider}}</a>
</div>


</div>

<?php include_file('desktop', 'update', 'js');?>