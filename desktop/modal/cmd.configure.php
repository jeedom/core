<?php
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
$cmd = cmd::byId(init('cmd_id'));
if (!is_object($cmd)) {
    throw new Exception('Commande non trouvé : ' . init('cmd_id'));
}
sendVarToJS('cmdInfo', utils::o2a($cmd));
$cmd_widgetDashboard = cmd::availableWidget('dashboard');
$cmd_widgetMobile = cmd::availableWidget('mobile');
?>
<div style="display: none;" id="md_displayCmdConfigure"></div>
<div id='div_displayCmdConfigure'>
    <div class="row">
        <div class="col-sm-6" >
            <legend>{{Informations}}</legend>
            <div class="row">
                <div class="col-sm-6" >
                    <form class="form-horizontal">
                        <fieldset>
                            <div class="form-group">
                                <label class="col-xs-4 control-label">{{ID}}</label>
                                <div class="col-xs-4">
                                    <span class="cmdAttr label label-primary" data-l1key="id"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-xs-4 control-label">{{Logical ID}}</label>
                                <div class="col-xs-4">
                                    <span class="cmdAttr label label-primary" data-l1key="logicalId"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-xs-4 control-label">{{Nom}}</label>
                                <div class="col-xs-4">
                                    <span class="cmdAttr label label-primary" data-l1key="name"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-xs-4 control-label">{{Type}}</label>
                                <div class="col-xs-4">
                                    <span class="cmdAttr label label-primary" data-l1key="type"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-xs-4 control-label">{{Sous-type}}</label>
                                <div class="col-xs-4">
                                    <span class="cmdAttr label label-primary" data-l1key="subType"></span>
                                </div>
                            </div>
                        </fieldset>
                    </form>
                </div>
                <div class="col-sm-6" >
                    <form class="form-horizontal">
                        <fieldset>
                            <div class="form-group">
                                <label class="col-xs-4 control-label">{{Unité}}</label>
                                <div class="col-xs-4">
                                    <span class="cmdAttr label label-primary" data-l1key="unite"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-xs-4 control-label">{{Commande déclenchant une mise à jour}}</label>
                                <div class="col-xs-4">
                                    <span class="cmdAttr label label-primary" data-l1key="value"></span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-xs-4 control-label">{{Memcache}}</label>
                                <div class="col-xs-4">
                                    <span class="cmdAttr label label-primary tooltips" data-l1key="cache" data-l2key="enable" title="{{Actif}}"></span> 
                                    <span class="label label-default tooltips" title="{{Durée du cache}}"><span class="cmdAttr" data-l1key="cache" data-l2key="lifetime"></span> {{seconde(s)}}</span>
                                </div>
                            </div>

                            <div class="form-group">
                                <label class="col-xs-4 control-label">{{Evenement seulement}}</label>
                                <div class="col-xs-4">
                                    <span class="cmdAttr label label-primary" data-l1key="eventOnly"></span>
                                </div>
                            </div>

                        </fieldset>
                    </form>
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-2 control-label">{{URL direct}}</label>
                <div class="col-xs-10">
                    <?php
                    echo '<a href="'.$cmd->getDirectUrlAccess().'" target="_blank"><i class="fa fa-external-link"></i> URL</a>';
                    ?>
                </div>
            </div>
        </div>

        <div class="col-sm-6" >
            <legend>{{Utilisé par}}
                <a class="btn btn-success btn-xs pull-right" id="bt_cmdConfigureSave"><i class="fa fa-check-circle"></i> {{Enregistrer}}</a>
            </legend>
            <form class="form-horizontal">
                <fieldset id="fd_cmdUsedBy">
                    <?php
                    $usedBy = $cmd->getUsedBy();
                    ?>
                    <div class="form-group">
                        <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Equipement}}</label>
                        <div class="col-lg-10 col-md-9 col-sm-8 col-xs-6 ">
                            <?php
                            foreach ($usedBy['eqLogic'] as $usedByEqLogic) {
                                echo '<span class="label label-primary cursor"><a href="' . $usedByEqLogic->getLinkToConfiguration() . '" style="color : white;">' . $usedByEqLogic->getHumanName() . '</a></span><br/>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Commandes}}</label>
                        <div class="col-lg-10 col-md-9 col-sm-8 col-xs-6 ">
                            <?php
                            foreach ($usedBy['cmd'] as $usedByCmd) {
                                echo '<span class="label label-primary cursor"><a href="' . $usedByCmd->getEqLogic()->getLinkToConfiguration() . '" style="color : white;">' . $usedByCmd->getHumanName() . '</a></span><br/>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Scénario}}</label>
                        <div class="col-lg-10 col-md-9 col-sm-8 col-xs-6 ">
                            <?php
                            foreach ($usedBy['scenario'] as $usedByScneario) {
                                echo '<span class="label label-primary cursor"><a href="' . $usedByScneario->getLinkToConfiguration() . '" style="color : white;">' . $usedByScneario->getHumanName() . '</a></span><br/>';
                            }
                            ?>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Interaction}}</label>
                        <div class="col-lg-10 col-md-9 col-sm-8 col-xs-6 ">
                            <?php
                            foreach ($usedBy['interact'] as $usedByInteract) {
                                echo '<span class="label label-primary cursor"><a href="' . $usedByInteract->getLinkToConfiguration() . '" style="color : white;">' . $usedByInteract->getQuery() . '</a></span><br/>';
                            }
                            ?>
                        </div>
                    </div>
                </div>
            </fieldset>
        </form>
    </div>
    <div class='row'>
        <div class='col-lg-6 col-md-5 col-sm-4'>
            <legend>{{Configuration}}</legend>
            <form class="form-horizontal">
                <fieldset>
                    <table class="table table-bordered">
                        <thead>
                            <tr>
                                <th></th>
                                <th>Dashboard</th>
                                <th>Vue</th>
                                <th>Design</th>
                                <th>Mobile</th>
                            </tr>
                        </thead>
                        <tbody>
                            <tr>
                                <td>{{Widget}}</td>
                                <td colspan="3">
                                    <select class="form-control cmdAttr" data-l1key="template" data-l2key="dashboard">
                                        <?php
                                        foreach ($cmd_widgetDashboard[$cmd->getType()][$cmd->getSubType()] as $widget) {
                                            echo '<option>' . $widget['name'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </td>
                                <td>
                                    <select class="form-control cmdAttr" data-l1key="template" data-l2key="mobile">
                                        <?php
                                        foreach ($cmd_widgetMobile[$cmd->getType()][$cmd->getSubType()] as $widget) {
                                            echo '<option>' . $widget['name'] . '</option>';
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                            <tr>
                                <td>{{Ne pas afficher le nom}}</td>
                                <td><input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="doNotShowNameOnDashboard" /></td>
                                <td><input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="doNotShowNameOnView" /></td>
                                <td></td>
                                <td></td>
                            </tr>



                        </tbody>
                    </table>

                    <div class="form-group">
                        <label class="col-lg-3 col-md-3 col-sm-4 col-xs-6 control-label">{{Retour à la ligne forcé avant le widget}}</label>
                        <div class="col-xs-1">
                            <input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="forceReturnLineBefore" />
                        </div>
                        <label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{après le widget}}</label>
                        <div class="col-xs-1">
                            <input type="checkbox" class="cmdAttr" data-l1key="display" data-l2key="forceReturnLineAfter" />
                        </div>
                    </div>

                    <?php if ($cmd->getType() == 'info' && $cmd->getSubType() == 'numeric') { ?>
                    <div class="form-group">
                        <label class="col-lg-3 col-md-3 col-sm-4 col-xs-6 control-label">{{Formule de calcul (#value# pour la valeur)}}</label>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input class="cmdAttr form-control" data-l1key="configuration" data-l2key="calculValueOffset" />
                        </div>
                    </div>
                    <?php } ?>


                    <?php if ($cmd->getType() == 'action') { ?>
                    <div class="form-group">
                        <label class="col-lg-3 col-md-3 col-sm-4 col-xs-6 control-label">{{Code d'accès}}</label>
                        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
                            <input type="password" class="cmdAttr form-control" data-l1key="configuration" data-l2key="actionCodeAccess" />
                        </div>
                    </div>
                    <?php } ?>

                    <?php if ($cmd->getIsHistorized() == 1) { ?>
                    <div class="form-group">
                        <label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">{{Historiser}}</label>
                        <div class="col-xs-1">
                            <input type="checkbox" class="cmdAttr" data-l1key="isHistorized" />
                        </div>

                    </div>
                    <div class="form-group">

                        <label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">{{Mode de lissage}}</label>
                        <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                            <select class="form-control cmdAttr" data-l1key="configuration" data-l2key="historizeMode">
                                <option value="avg">Moyenne</option>
                                <option value="min">Minimum</option>
                                <option value="max">Maximum</option>
                                <option value="none">Aucun</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">{{Arrondi (chiffre après la virgule)}}</label>
                        <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
                            <input class="cmdAttr form-control" data-l1key="configuration" data-l2key="historizeRound" />
                        </div>
                    </div>
                    <?php } ?>
                </fieldset>
            </form>
        </div>
        <div class='col-lg-6 col-md-7 col-sm-8'>
            <legend>{{Paramètres optionnels widget}} <a class="btn btn-success btn-xs pull-right" id="bt_addWidgetParameters"><i class="fa fa-plus-circle"></i> Ajouter</a></legend>
            <table class="table table-bordered table-condensed" id="table_widgetParameters">
                <thead class="table table-bordered">
                    <tr>
                        <th>Nom</th>
                        <th>Valeur</th>
                        <th>Action</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    if ($cmd->getDisplay('parameters') != '') {
                        foreach ($cmd->getDisplay('parameters') as $key => $value) {
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
</div>

<script>
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

    $('#div_displayCmdConfigure').setValues(cmdInfo, '.cmdAttr');

    $('#bt_cmdConfigureSave').on('click', function () {
        var cmd = $('#div_displayCmdConfigure').getValues('.cmdAttr')[0];
        if (!isset(cmd.display)) {
            cmd.display = {};
        }
        if (!isset(cmd.display.parameters)) {
            cmd.display.parameters = {};
        }
        $('#table_widgetParameters tbody tr').each(function () {
            cmd.display.parameters[$(this).find('.key').value()] = $(this).find('.value').value();
        });
        jeedom.cmd.save({
            cmd: cmd,
            error: function (error) {
                $('#md_displayCmdConfigure').showAlert({message: error.message, level: 'danger'});
            },
            success: function () {
                $('#md_displayCmdConfigure').showAlert({message: '{{Enregistrement réussi}}', level: 'success'});
            }
        });
    });
</script>