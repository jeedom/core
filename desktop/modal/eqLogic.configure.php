<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$eqLogic = eqLogic::byId(init('eqLogic_id'));
if (!is_object($eqLogic)) {
	throw new Exception('EqLogic non trouvé : ' . init('eqLogic_id'));
}

sendVarToJS('eqLogicInfo', utils::o2a($eqLogic));
?>
<div style="display: none;" id="md_displayEqLogicConfigure"></div>

<a class="btn btn-danger pull-right btn-sm" id="bt_eqLogicConfigureRemove"><i class="fa fa-times"></i> {{Supprimer}}</a>
<a class="btn btn-success pull-right btn-sm" id="bt_eqLogicConfigureSave"><i class="fa fa-check-circle"></i> {{Enregistrer}}</a>

<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#information" aria-controls="home" role="tab" data-toggle="tab">{{Informations}}</a></li>
    <li role="presentation"><a href="#display" aria-controls="messages" role="tab" data-toggle="tab">{{Affichage avancé}}</a></li>
</ul>

<div class="tab-content" id="div_displayEqLogicConfigure">
   <div role="tabpanel" class="tab-pane active" id="information">
       <br/>
       <div class="row">
        <div class="col-sm-4" >
            <form class="form-horizontal">
                <fieldset>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">{{ID}}</label>
                        <div class="col-sm-4">
                            <span class="eqLogicAttr label label-primary" data-l1key="id" style="font-size : 1em;"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">{{Nom}}</label>
                        <div class="col-sm-4">
                            <span class="eqLogicAttr label label-primary" data-l1key="name" style="font-size : 1em;"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">{{ID logique}}</label>
                        <div class="col-sm-4">
                            <span class="eqLogicAttr label label-primary" data-l1key="logicalId" style="font-size : 1em;"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">{{ID de l'objet}}</label>
                        <div class="col-sm-4">
                            <span class="eqLogicAttr label label-primary" data-l1key="object_id" style="font-size : 1em;"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">{{Date de création}}</label>
                        <div class="col-sm-4">
                            <span class="eqLogicAttr label label-primary" data-l1key="configuration" data-l2key="createtime" style="font-size : 1em;"></span>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
        <div class="col-sm-4" >
            <form class="form-horizontal">
                <fieldset>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">{{Type}}</label>
                        <div class="col-sm-4">
                            <span class="eqLogicAttr label label-primary" data-l1key="eqType_name" style="font-size : 1em;"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">{{Activer}}</label>
                        <div class="col-sm-4">
                            <span class="eqLogicAttr label label-primary" data-l1key="isEnable" style="font-size : 1em;"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">{{Visible}}</label>
                        <div class="col-sm-4">
                            <span class="eqLogicAttr label label-primary" data-l1key="isVisible" style="font-size : 1em;"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">{{Tentative échouée}}</label>
                        <div class="col-sm-4">
                            <span class="label label-primary" style="font-size : 1em;"><?php echo $eqLogic->getStatus('numberTryWithoutSuccess', 0)?></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">{{Date de dernière communication}}</label>
                        <div class="col-sm-4">
                            <span class="label label-primary" style="font-size : 1em;"><?php echo $eqLogic->getStatus('lastCommunication')?></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">{{Dernière mise à jour}}</label>
                        <div class="col-sm-4">
                            <span class="eqLogicAttr label label-primary" data-l1key="configuration" data-l2key="updatetime" style="font-size : 1em;"></span>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
</div>

<div role="tabpanel" class="tab-pane" id="display">
    <br/>
    <legend>{{Widget}}</legend>
    <table class="table table-bordered table-condensed">
        <thead>
            <tr>
                <th></th>
                <th>Dashboard et Design</th>
                <th>Vue</th>
                <th>Mobile</th>
            </tr>
        </thead>
        <tbody>
         <tr>
            <td>{{Ne pas afficher le nom}}</td>
            <td><input type="checkbox" data-size="small" class="eqLogicAttr bootstrapSwitch" data-l1key="display" data-l2key="doNotShowNameOnDashboard" /></td>
            <td><input type="checkbox" data-size="small" class="eqLogicAttr bootstrapSwitch" data-l1key="display" data-l2key="doNotShowNameOnView" /></td>
            <td><input type="checkbox" data-size="small" class="eqLogicAttr bootstrapSwitch" data-l1key="display" data-l2key="doNotShowNameOnMobile" /></td>
        </tr>
        <tr>
            <td>{{Ne pas afficher le nom de l'objet}}</td>
            <td></td>
            <td><input type="checkbox" data-size="small" class="eqLogicAttr bootstrapSwitch" data-l1key="display" data-l2key="doNotShowObjectNameOnView" /></td>
            <td></td>
        </tr>
        <tr>
            <td>{{Ne pas afficher le niveau de batterie}}</td>
            <td><input type="checkbox" data-size="small" class="eqLogicAttr bootstrapSwitch" data-l1key="display" data-l2key="doNotDisplayBatteryLevelOnDashboard" /></td>
            <td><input type="checkbox" data-size="small" class="eqLogicAttr bootstrapSwitch" data-l1key="display" data-l2key="doNotDisplayBatteryLevelOnView" /></td>
            <td></td>
        </tr>
    </tbody>
</table>
<legend>{{Paramètres optionnels sur la tuile}} <a class="btn btn-success btn-xs pull-right" id="bt_addWidgetParameters"><i class="fa fa-plus-circle"></i> Ajouter</a></legend>
<table class="table table-bordered table-condensed" id="table_widgetParameters">
    <thead>
        <tr>
            <th>{{Nom}}</th>
            <th>{{Valeur}}</th>
            <th>{{Action}}</th>
        </tr>
    </thead>
    <tbody>
        <?php
if ($eqLogic->getDisplay('parameters') != '') {
	foreach ($eqLogic->getDisplay('parameters') as $key => $value) {
		echo '<tr>';
		echo '<td>';
		echo '<input class="form-control key" value="' . $key . '" />';
		echo '</td>';
		echo '<td>';
		echo '<input class="form-control value" value="' . $value . '" />';
		echo '</td>';
		echo '<td>';
		echo '<a class="btn btn-danger btn-xs removeWidgetParameter"><i class="fa fa-times"></i> Supprimer</a>';
		echo '</td>';
		echo '</tr>';
	}
}
?>
  </tbody>
</table>
</div>
</div>


<script>
    initCheckBox();

    $('#div_displayEqLogicConfigure').setValues(eqLogicInfo, '.eqLogicAttr');
    $('#table_widgetParameters').delegate('.removeWidgetParameter', 'click', function () {
        $(this).closest('tr').remove();
    });

    $('#bt_addWidgetParameters').off().on('click', function () {
        var tr = '<tr>';
        tr += '<td>';
        tr += '<input class="form-control key" />';
        tr += '</td>';
        tr += '<td>';
        tr += '<input class="form-control value" />';
        tr += '</td>';
        tr += '<td>';
        tr += '<a class="btn btn-danger btn-xs removeWidgetParameter pull-right"><i class="fa fa-times"></i> Supprimer</a>';
        tr += '</td>';
        tr += '</tr>';
        $('#table_widgetParameters tbody').append(tr);
    });

    $('#bt_eqLogicConfigureSave').on('click', function () {
        var eqLogic = $('#div_displayEqLogicConfigure').getValues('.eqLogicAttr')[0];
        if (!isset(eqLogic.display)) {
            eqLogic.display = {};
        }
        if (!isset(eqLogic.display.parameters)) {
            eqLogic.display.parameters = {};
        }
        $('#table_widgetParameters tbody tr').each(function () {
            eqLogic.display.parameters[$(this).find('.key').value()] = $(this).find('.value').value();
        });
        jeedom.eqLogic.save({
            eqLogics: [eqLogic],
            type: eqLogic.eqType_name,
            error: function (error) {
                $('#md_displayEqLogicConfigure').showAlert({message: error.message, level: 'danger'});
            },
            success: function () {
                $('#md_displayEqLogicConfigure').showAlert({message: '{{Enregistrement réussi}}', level: 'success'});
            }
        });
    });

    $('#bt_eqLogicConfigureRemove').on('click',function(){
        bootbox.confirm('{{Etes-vous sûr de vouloir supprimer cet équipement ?}}', function (result) {
            if (result) {
                var eqLogic = $('#div_displayEqLogicConfigure').getValues('.eqLogicAttr')[0];
                jeedom.eqLogic.remove({
                    id : eqLogic.id,
                    type : eqLogic.eqType_name,
                    error: function (error) {
                        $('#md_displayEqLogicConfigure').showAlert({message: error.message, level: 'danger'});
                    },
                    success: function (data) {
                        $('#md_displayEqLogicConfigure').showAlert({message: '{{Suppression réalisée avec succès}}', level: 'success'});
                    }
                });
            }
        });
    });
</script>