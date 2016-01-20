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
    <li role="presentation" class="active"><a href="#information" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-info-circle"></i> {{Informations}}</a></li>
    <li role="presentation"><a href="#display" aria-controls="messages" role="tab" data-toggle="tab"><i class="fa fa-desktop"></i> {{Affichage avancé}}</a></li>
    <li role="presentation"><a href="#battery" aria-controls="messages" role="tab" data-toggle="tab"><i class="icon techno-charging"></i> {{Batterie}}</a></li>
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
                        <label class="col-sm-4 control-label"></label>
                        <div class="col-sm-8">
                           <input type="checkbox" class="eqLogicAttr bootstrapSwitch" data-label-text="{{Activer}}" data-l1key="isEnable" checked/>
                           <input type="checkbox" class="eqLogicAttr bootstrapSwitch" data-label-text="{{Visible}}" data-l1key="isVisible" checked/>
                       </div>
                   </div>

                   <div class="form-group">
                    <label class="col-sm-4 control-label">{{Type}}</label>
                    <div class="col-sm-4">
                        <span class="eqLogicAttr label label-primary" data-l1key="eqType_name" style="font-size : 1em;"></span>
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
    <legend><i class="fa fa-tint"></i> {{Widget}}</legend>
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
                <td>{{Visible}}</td>
                <td colspan="2"><input type="checkbox" class="eqLogicAttr bootstrapSwitch" data-on-color="danger" data-off-color="success" data-off-text="Oui" data-on-text="Non" data-size="small" data-l1key="display" data-l2key="hideOndashboard" /></td>
                <td><input type="checkbox" class="eqLogicAttr bootstrapSwitch" data-on-color="danger" data-off-color="success" data-off-text="Oui" data-on-text="Non" data-size="small" data-l1key="display" data-l2key="hideOnmobile" /></td>
            </tr>
            <tr>
                <td>{{Afficher le nom}}</td>
                <td><input type="checkbox" data-on-color="danger" data-off-color="success" data-off-text="Oui" data-on-text="Non" data-size="small" class="eqLogicAttr bootstrapSwitch" data-l1key="display" data-l2key="doNotShowNameOnDashboard" /></td>
                <td><input type="checkbox" data-on-color="danger" data-off-color="success" data-off-text="Oui" data-on-text="Non" data-size="small" class="eqLogicAttr bootstrapSwitch" data-l1key="display" data-l2key="doNotShowNameOnView" /></td>
                <td><input type="checkbox" data-on-color="danger" data-off-color="success" data-off-text="Oui" data-on-text="Non" data-size="small" class="eqLogicAttr bootstrapSwitch" data-l1key="display" data-l2key="doNotShowNameOnMobile" /></td>
            </tr>
            <tr>
                <td>{{Afficher le nom de l'objet}}</td>
                <td></td>
                <td><input type="checkbox" data-on-color="danger" data-off-color="success" data-off-text="Oui" data-on-text="Non" data-size="small" class="eqLogicAttr bootstrapSwitch" data-l1key="display" data-l2key="doNotShowObjectNameOnView" /></td>
                <td></td>
            </tr>
        </tbody>
    </table>
    <legend><i class="fa fa-pencil-square-o"></i> {{Paramètres optionnels sur la tuile}} <a class="btn btn-success btn-xs pull-right" id="bt_addWidgetParameters"><i class="fa fa-plus-circle"></i> Ajouter</a></legend>
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
<div role="tabpanel" class="tab-pane" id="battery">
 <br/>
 <legend><i class="fa fa-info-circle"></i> {{Informations}}</legend>
 <div class="alert alert-info" id="nobattery">
    {{Cet équipement ne possède pas de batterie/piles ou il n'a pas encore remonté sa valeur}}
</div>
<div id="hasbattery">
    <div class="row">
        <div class="col-sm-4" >
            <form class="form-horizontal">
                <fieldset>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">{{Type de batterie}}</label>
                        <div class="col-sm-4">
                            <span class="eqLogicAttr label label-primary" data-l1key="configuration"data-l2key="battery_type" style="font-size : 1em;"></span>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">{{Mis à jour le}}</label>
                        <div class="col-sm-4">
                            <span class="eqLogicAttr label label-primary" data-l1key="configuration"data-l2key="batteryStatusDatetime" style="font-size : 1em;"></span>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
        <div class="col-sm-4" >
            <form class="form-horizontal">
                <fieldset>
                   <div class="form-group">
                    <label class="col-sm-4 control-label">{{Niveau de batterie}}</label>
                    <div class="col-sm-4" id="batterylevel">
                        <span class="eqLogicAttr label label-primary" data-l1key="configuration" data-l2key="batteryStatus" style="font-size : 1em;"></span>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
</div>
<legend><i class="icon techno-fleches"></i> {{Seuils spécifiques}}</legend>
<div class="form-group">
    <label class="col-xs-2 eqLogicAttr label label-danger" style="font-size : 1.8em">{{Danger}}</label>
    <div class="col-xs-2">
    <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="battery_danger_threshold" />
  </input>
</div>
<label class="col-xs-2 label label-warning" style="font-size : 1.8em">{{Warning}}</label>
<div class="col-xs-2">
    <input class="eqLogicAttr form-control" data-l1key="configuration" data-l2key="battery_warning_threshold" />
</div>
<label class="col-xs-2 label label-success" style="font-size : 1.8em">{{Ok}}</label>
</div>
</div>
</div>
</div>


<script>
    initCheckBox();
    $(document).ready(function() {
      if(typeof $('.eqLogicAttr[data-l1key=configuration][data-l2key=batteryStatus]').value() != null) {
         console.log($('.eqLogicAttr[data-l1key=configuration][data-l2key=batteryStatus]').html());
         $( "#nobattery" ).show();
         $( "#hasbattery" ).hide();
     }else{
        $( "#nobattery" ).hide();
        $( "#hasbattery" ).show();
    }
});

    $('#div_displayEqLogicConfigure').setValues(eqLogicInfo, '.eqLogicAttr');
    $('#table_widgetParameters').delegate('.removeWidgetParameter', 'click', function () {
        $(this).closest('tr').remove();
    });
    if($('.eqLogicAttr[data-l1key=configuration][data-l2key=batteryStatus]').html() != '') {
      console.log($('.eqLogicAttr[data-l1key=configuration][data-l2key=batteryStatus]').html());
      $( "#nobattery" ).hide();
      $( "#hasbattery" ).show();
  }else{
    $( "#nobattery" ).show();
    $( "#hasbattery" ).hide();
}
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