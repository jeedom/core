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
<div id='div_displayEqLogicConfigure'>
    <legend>{{Informations}}</legend>
    <div class="row">
        <div class="col-sm-4" >
            <form class="form-horizontal">
                <fieldset>
                    <div class="form-group">
                        <label class="col-sm-4 control-label">{{ID}}</label>
                        <div class="col-sm-4">
                            <span class="eqLogicAttr label label-primary" data-l1key="id"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">{{Nom}}</label>
                        <div class="col-sm-4">
                            <span class="eqLogicAttr label label-primary" data-l1key="name"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">{{Logical ID}}</label>
                        <div class="col-sm-4">
                            <span class="eqLogicAttr label label-primary" data-l1key="logicalId"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">{{Object ID}}</label>
                        <div class="col-sm-4">
                            <span class="eqLogicAttr label label-primary" data-l1key="object_id"></span>
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
                            <span class="eqLogicAttr label label-primary" data-l1key="eqType_name"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">{{Activer}}</label>
                        <div class="col-sm-4">
                            <span class="eqLogicAttr label label-primary" data-l1key="isEnable"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">{{Visible}}</label>
                        <div class="col-sm-4">
                            <span class="eqLogicAttr label label-primary" data-l1key="isVisible"></span>
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-4 control-label">{{Tentative échouée}}</label>
                        <div class="col-sm-4">
                            <span class="eqLogicAttr label label-primary" data-l1key="status" data-l2key="numberTryWithoutSuccess"></span>
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>
    </div>
    <div class='row'>
        <div class='col-lg-6 col-md-5 col-sm-4'>
            <legend>Configuration</legend>
            <form class="form-horizontal">
                <fieldset>
                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{Ordre}}</label>
                        <div class="col-sm-2">
                            <input type="number" class="eqLogicAttr form-control" data-l1key="order" />
                        </div>
                    </div>

                    <div class="form-group">
                        <label class="col-sm-3 control-label">{{Ne pas afficher le nom sur le dashboard}}</label>
                        <div class="col-sm-1">
                            <input type="checkbox" class="eqLogicAttr" data-l1key="display" data-l2key="doNotShowNameOnDashboard" />
                        </div>
                        <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{sur les vues}}</label>
                        <div class="col-sm-1">
                            <input type="checkbox" class="eqLogicAttr" data-l1key="display" data-l2key="doNotShowNameOnView" />
                        </div>
                    </div>
                </fieldset>
            </form>
        </div>

        <div class='col-lg-6 col-md-7 col-sm-8'>
            <legend>{{Paramètres optionnels sur la tuile}} <a class="btn btn-success btn-xs pull-right" id="bt_addWidgetParameters"><i class="fa fa-plus-circle"></i> Ajouter</a></legend>
            <table class="table table-bordered table-condensed" id="table_widgetParameters">
                <thead>
                    <tr>
                        <th>Nom</th>
                        <th>Valeur</th>
                        <th>Action</th>
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
    <a class="btn btn-success" id="saveEqLogic"><i class="fa fa-check-circle"></i> {{Enregistrer}}</a>
</div>
<script>
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

    $('#saveEqLogic').off().on('click', function () {
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
            }});
    });
</script>