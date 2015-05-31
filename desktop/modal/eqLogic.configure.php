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
<a class="btn btn-default pull-right btn-sm" id="bt_eqLogicConfigureSaveOn"><i class="fa fa-plus-circle"></i> {{Appliquer à}}</a>

<ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#information" aria-controls="home" role="tab" data-toggle="tab">{{Informations}}</a></li>
    <li role="presentation"><a href="#configuration" aria-controls="profile" role="tab" data-toggle="tab">{{Configuration avancée}}</a></li>
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
<div role="tabpanel" class="tab-pane" id="configuration">
    <br/>
    <form class="form-horizontal">
        <fieldset>
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
                <td><input type="checkbox" class="eqLogicAttr" data-l1key="display" data-l2key="doNotShowNameOnDashboard" /></td>
                <td><input type="checkbox" class="eqLogicAttr" data-l1key="display" data-l2key="doNotShowNameOnView" /></td>
                <td><input type="checkbox" class="eqLogicAttr" data-l1key="display" data-l2key="doNotShowNameOnMobile" /></td>
            </tr>
            <tr>
                <td>{{Ne pas afficher le nom de l'objet}}</td>
                <td></td>
                <td><input type="checkbox" class="eqLogicAttr" data-l1key="display" data-l2key="doNotShowObjectNameOnView" /></td>
                <td></td>
            </tr>
            <tr>
                <td>{{Ne pas afficher le niveau de batterie}}</td>
                <td><input type="checkbox" class="eqLogicAttr" data-l1key="display" data-l2key="doNotDisplayBatteryLevelOnDashboard" /></td>
                <td><input type="checkbox" class="eqLogicAttr" data-l1key="display" data-l2key="doNotDisplayBatteryLevelOnView" /></td>
                <td></td>
            </tr>
        </tbody>
    </table>

    <div class="form-group">
        <label class="col-sm-3 control-label">{{Ordre}}</label>
        <div class="col-sm-2">
            <input type="number" class="eqLogicAttr form-control" data-l1key="order" />
        </div>
    </div>
</fieldset>
</form>
</div>

<div role="tabpanel" class="tab-pane" id="display">
    <br/>
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


<div id="md_eqLogicConfigureSelectMultiple" title="{{Sélection multiple d'équipements}}">
    <div style="display: none;" id="md_eqLogicConfigureSelectMultipleAlert"></div>
    <div>
        <a class="btn btn-default" id="bt_eqLogicConfigureSelectMultipleAlertToogle" data-state="0"><i class="fa fa-check-circle-o"></i> {{Basculer}}</a>
        <a class="btn btn-success pull-right" id="bt_eqLogicConfigureSelectMultipleAlertApply" style="color : white;" ><i class="fa fa-check"></i> {{Valider}}</a>
    </div>
    <br/>
    <table class="table table-bordered table-condensed tablesorter" id="table_eqLogicConfigureSelectMultiple">
        <thead>
            <tr>
                <th></th>
                <th>{{Nom}}</th>
            </tr>
        </thead>
        <tbody>
            <?php
foreach (eqLogic::all() as $listEqLogic) {
	echo '<tr data-eqLogic_id="' . $listEqLogic->getId() . '" data-eqTypeName="' . $listEqLogic->getEqType_name() . '">';
	echo '<td>';
	if ($listEqLogic->getId() == $eqLogic->getId()) {
		echo '<input type="checkbox" class="selectMultipleApplyEqlogic" checked/>';
	} else {
		echo '<input type="checkbox" class="selectMultipleApplyEqlogic" />';
	}
	echo '</td>';
	echo '<td>';
	echo $listEqLogic->getHumanName(true);
	echo '</td>';
	echo '</tr>';
}
?>
      </tbody>
  </table>
</div>

<script>
 $("#md_eqLogicConfigureSelectMultiple").dialog({
    autoOpen: false,
    modal: true,
    height: (jQuery(window).height() - 150),
    width: ((jQuery(window).width() - 150) < 1200) ? (jQuery(window).width() - 50) : 1200,
    position: {my: 'center', at: 'center', of: window},
    open: function () {
        $("body").css({overflow: 'hidden'});
    },
    beforeClose: function (event, ui) {
        $("body").css({overflow: 'inherit'});
    }
});

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

 $('#bt_eqLogicConfigureSaveOn').on('click',function(){
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
    console.log(eqLogic);
    delete eqLogic.configuration.createtime;
    delete eqLogic.configuration.updatetime;
    eqLogic = {display : eqLogic.display,configuration : eqLogic.template };
    console.log(eqLogic);

    $('#md_eqLogicConfigureSelectMultiple').dialog('open');
    initTableSorter();

    $('#bt_eqLogicConfigureSelectMultipleAlertToogle').off().on('click', function () {
        var state = false;
        if ($(this).attr('data-state') == 0) {
            state = true;
            $(this).attr('data-state', 1);
            $(this).find('i').removeClass('fa-check-circle-o').addClass('fa-circle-o');
        } else {
            state = false;
            $(this).attr('data-state', 0);
            $(this).find('i').removeClass('fa-circle-o').addClass('fa-check-circle-o');
        }
        $('#table_eqLogicConfigureSelectMultiple tbody tr').each(function () {
            if ($(this).is(':visible')) {
                $(this).find('.selectMultipleApplyEqlogic').prop('checked', state);
            }
        });
    });

    $('#bt_eqLogicConfigureSelectMultipleAlertApply').off().on('click', function () {
      $('#table_eqLogicConfigureSelectMultiple tbody tr').each(function () {
        if ($(this).find('.selectMultipleApplyEqlogic').prop('checked')) {
            eqLogic.id = $(this).attr('data-eqLogic_id');
            jeedom.eqLogic.save({
                eqLogics: [eqLogic],
                type: $(this).attr('data-eqTypeName'),
                error: function (error) {
                    $('#md_eqLogicConfigureSelectMultipleAlert').showAlert({message: error.message, level: 'danger'});
                },
                success: function () {

                }
            });
        }
    });
      $('#md_eqLogicConfigureSelectMultipleAlert').showAlert({message: "{{Modification(s) appliquée(s) avec succès}}", level: 'success'});

  });

});
</script>