<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>
<br/>
<div class="row row-overflow">
    <div class="col-sm-8">
        <i class="fa fa-clock-o" style="cursor:default;"></i> <span style="cursor:default;">{{Dernière vérification : }}</span><span class="label label-info" id="span_lastUpdateCheck" style="margin-bottom: 5px;font-size:1em;cursor:default;"></span>
        <a class="btn btn-success pull-right" id="bt_saveUpdate" style="margin-top:5px;"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
        <a class="btn btn-info pull-right" id="bt_checkAllUpdate" style="margin-top:5px;"><i class="fa fa-refresh"></i> {{Vérifier les mises à jour}}</a>
            <a href="#" class="btn btn-default pull-right" id="bt_updateJeedom" style="margin-top:5px;"><i class="fa fa-check"></i> {{Mettre à jour}}</a>
        <br/><br/>

        <ul class="nav nav-tabs" role="tablist">
            <li role="presentation" class="active"><a href="#coreplugin" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-archive"></i>  {{Core et plugins}}</a></li>
            <li role="presentation"><a href="#other" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-pencil-square-o"></i>  {{Autre}}</a></li>
        </ul>

        <div class="tab-content">
            <div role="tabpanel" class="tab-pane active" id="coreplugin">
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

            <div role="tabpanel" class="tab-pane" id="other">
                <table class="table table-condensed table-bordered tablesorter" id="table_updateOther" style="margin-top: 5px;">
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
        </div>

    </div>
    <div class="col-sm-4">
        <legend style="cursor:default;"><i class="fa fa-info-circle"></i>  {{Informations :}}</legend>
        <pre id="pre_updateInfo"></pre>
    </div>
</div>

<div id="md_specifyUpdate">
   <form class="form-horizontal">
    <fieldset>
     <div class="form-group">
         <div class="form-group">
            <label class="col-xs-6 control-label">{{Sauvegarder avant}}</label>
            <div class="col-xs-4">
                <input type="checkbox" class="updateOption" data-l1key="backup::defore" checked />
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-6 control-label">{{Si sauvegarde envoyer dans le cloud}}</label>
            <div class="col-xs-4">
                <input type="checkbox" class="updateOption" data-l1key="backup::cloudSend" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-6 control-label">{{Mettre à jour les plugins}}</label>
            <div class="col-xs-4">
                <input type="checkbox" class="updateOption" data-l1key="plugins" checked />
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-6 control-label">{{Mode forcé}}</label>
            <div class="col-xs-4">
                <input type="checkbox" class="updateOption" data-l1key="force" />
            </div>
        </div>
        <label class="col-xs-6 control-label">{{Mise à jour à réappliquer}}</label>
        <div class="col-xs-5">
            <select id="sel_updateVersion" class="form-control updateOption" data-l1key="update::reapply">
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
</fieldset>
</form>
<a class="btn btn-success" style="color:white;" id="bt_doUpdate"><i class="fa fa-check"></i> {{Mettre à jour}}</a>
</div>

<?php include_file('desktop', 'update', 'js');?>