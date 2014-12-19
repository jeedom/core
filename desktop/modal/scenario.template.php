<?php
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}
$scenario = scenario::byId(init('scenario_id'));
if (!is_object($scenario)) {
    throw new Exception('Scenario non trouvé : ' . init('scenario_id'));
}
sendVarToJS('scenario_template_id', init('scenario_id'));
?>
<div style="display: none;" id="md_scenarioTemplate"></div>
<div id='div_scenarioTemplate'>

    <form class="form-horizontal">
        <legend>{{Général}}</legend>
        <div class="form-group">
            <label class="col-xs-2 control-label">{{Market}}</label>
            <div class="col-xs-8">
                <a class='btn btn-success' id='bt_scenarioTemplateConvert'><i class="fa fa-code"></i> Convertir le scénario courant en un nouveau template</a>
            </div>
        </div>
        <div class="form-group">
            <label class="col-xs-2 control-label">{{Appliquer un template}}</label>
            <div class="col-xs-2">
                <select class='form-control' id='sel_scenarioTemplate'></select>
            </div>
            <div class="col-xs-8">
                <a class='btn btn-warning' id='bt_scenarioTemplateShare'><i class="fa fa-cloud-upload"></i> Partager</a>
                <a class='btn btn-danger' id='bt_scenarioTemplateRemove'><i class="fa fa-times"></i> Supprimer</a>
                <a class="btn btn-default" id="bt_scenarioTemplateDisplayMarket"><i class="fa fa-shopping-cart"></i> {{Market}}</a>
                <a class='btn btn-success' id='bt_scenarioTemplateConvertInTemplate'><i class="fa fa-code"></i> Convertir le scénario courant dans ce template</a>
            </div>
        </div>
        <div id='div_scenarioTemplateParametreConfiguration' style='display : none;'>
            <legend>{{Paramètres du scénario}}<a class='btn btn-warning btn-xs pull-right' id='bt_scenarioTemplateApply'><i class="fa fa-check-circle"></i> Appliquer</a></legend>
            <div id='div_scenarioTemplateParametreList'></div>
        </div>
    </form>
</div>


<script>
    function refreshScenarioTemplateList() {
        jeedom.scenario.getTemplate({
            error: function (error) {
                $('#md_scenarioTemplate').showAlert({message: error.message, level: 'danger'});
            },
            success: function (data) {
                $('#sel_scenarioTemplate').empty();
                var option = "<option value=''>Aucun</option>";
                for (var i in data) {
                    var name = data[i].split(".");
                    name = name[1];
                    option += "<option value='" + data[i] + "'>" + name.replace(".json", "") + "</option>";
                }
                $('#sel_scenarioTemplate').html(option);
                $('#sel_scenarioTemplate').trigger('change');
            }
        });
    }

    refreshScenarioTemplateList();

    $('#bt_scenarioTemplateDisplayMarket').on('click', function () {
        $('#md_modal').dialog({title: "{{Partager sur le market}}"});
        $('#md_modal').load('index.php?v=d&modal=market.list&type=scenario').dialog('open');
    });

    $('#bt_scenarioTemplateShare').on('click', function () {
        var logicalId = $('#sel_scenarioTemplate').value().replace(".json", "");
        if (logicalId == '') {
            $('#md_scenarioTemplate').showAlert({message: '{{Vous devez d\'abord sélectionner une configuration à partager}}', level: 'danger'});
            return;
        }
        $('#md_modal2').dialog({title: "{{Partager sur le market}}"});
        $('#md_modal2').load('index.php?v=d&modal=market.send&type=scenario&logicalId=' + encodeURI(logicalId) + '&name=' + encodeURI($('#sel_scenarioTemplate option:selected').text())).dialog('open');
    });


    $('#bt_scenarioTemplateConvertInTemplate').on('click', function () {
        jeedom.scenario.convertToTemplate({
            id: scenario_template_id,
            template: $('#sel_scenarioTemplate').value(),
            error: function (error) {
                $('#md_scenarioTemplate').showAlert({message: error.message, level: 'danger'});
            },
            success: function (data) {
                refreshScenarioTemplateList();
                $('#md_scenarioTemplate').showAlert({message: 'Création du template réussi', level: 'success'});
            }
        });
    });

    $('#bt_scenarioTemplateConvert').on('click', function () {
        jeedom.scenario.convertToTemplate({
            id: scenario_template_id,
            error: function (error) {
                $('#md_scenarioTemplate').showAlert({message: error.message, level: 'danger'});
            },
            success: function (data) {
                refreshScenarioTemplateList();
                $('#md_scenarioTemplate').showAlert({message: 'Création du template réussi', level: 'success'});
            }
        });
    });

    $('#bt_scenarioTemplateRemove').on('click', function () {
        jeedom.scenario.removeTemplate({
            template: $('#sel_scenarioTemplate').value(),
            error: function (error) {
                $('#md_scenarioTemplate').showAlert({message: error.message, level: 'danger'});
            },
            success: function (data) {
                refreshScenarioTemplateList();
                $('#md_scenarioTemplate').showAlert({message: 'Suppression du template réussi', level: 'success'});
            }
        });
    });

    $('#bt_scenarioTemplateApply').on('click', function () {
        bootbox.confirm('{{Etes-vous sûr de vouloir appliquer le template ? Cela écrasera votre scénario', function (result) {
            if (result) {
                var convert = $('.templateScenario').getValues('.templateScenarioAttr');
                jeedom.scenario.applyTemplate({
                    template: $('#sel_scenarioTemplate').value(),
                    id: scenario_template_id,
                    convert: json_encode(convert),
                    error: function (error) {
                        $('#md_scenarioTemplate').showAlert({message: error.message, level: 'danger'});
                    },
                    success: function (data) {
                        $('#md_scenarioTemplate').showAlert({message: 'Template appliqué avec succès', level: 'success'});
                    }
                });
            }
        });
    });

    $('#sel_scenarioTemplate').on('change', function () {
        if ($('#sel_scenarioTemplate').value() == '') {
            $('#div_scenarioTemplateParametreList').empty();
            $('#div_scenarioTemplateParametreConfiguration').hide();
        } else {
            jeedom.scenario.loadTemplateDiff({
                template: $('#sel_scenarioTemplate').value(),
                id: scenario_template_id,
                error: function (error) {
                    $('#md_scenarioTemplate').showAlert({message: error.message, level: 'danger'});
                },
                success: function (data) {
                    var html = '';
                    for (var i in data) {
                        html += '<div class="form-group templateScenario">';
                        html += '<label class="col-xs-4 control-label">' + data[i] + ' <i class="fa fa-arrow-right"></i></label>';
                        html += '<div class="col-xs-4">';
                        html += '<span class="templateScenarioAttr" data-l1key="begin" style="display : none;" >' + data[i] + '</span>';
                        html += '<input class="form-control templateScenarioAttr" data-l1key="end" />';
                        html += '</div>';
                        html += '<div class="col-xs-2">';
                        html += '<a class="btn btn-default cursor bt_scenarioTemplateSelectCmd"><i class="fa fa-list-alt"></i></a>';
                        html += '</div>';
                        html += '</div>';
                    }
                    $('#div_scenarioTemplateParametreList').empty().html(html);
                    $('#div_scenarioTemplateParametreConfiguration').show();
                }
            });
        }
    });

    $('#div_scenarioTemplate').delegate('.bt_scenarioTemplateSelectCmd', 'click', function () {
        var el = $(this);
        jeedom.cmd.getSelectModal({}, function (result) {
            el.closest('.templateScenario').find('.templateScenarioAttr[data-l1key=end]').value(result.human);
        });
    });
</script>