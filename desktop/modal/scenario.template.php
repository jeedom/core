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



<div class="row row-overflow" id='div_scenarioTemplate'>
 <div class="col-lg-2 col-md-3 col-sm-5" id="div_listScenario" style="z-index:999">
    <div class="bs-sidebar nav nav-list bs-sidenav" >
        <center>
            <span class="btn btn-default btn-file">
                <i class="fa fa-cloud-upload"></i> {{Envoyer un template}}<input class="expertModeVisible" id="bt_uploadScenarioTemplate" type="file" name="file" data-url="core/ajax/scenario.ajax.php?action=templateupload&jeedom_token=<?php echo ajax::getToken(); ?>" style="display : inline-block;">
            </span>
        </center>
        <br/>
        <center><a class="btn btn-default" id="bt_scenarioTemplateDisplayMarket" style="width : 91%"><i class="fa fa-shopping-cart"></i> {{Market}}</a></center><br/>
        <div class="form-group" style="position:relative;left : -5px;">
          <div class="col-xs-9">
              <input class='form-control' id='in_newTemplateName' placeholder="{{Nom du template}}" />
          </div>
          <div class="col-xs-2">
              <a class="btn btn-default" id="bt_scenarioTemplateConvert"><i class="fa fa-plus-circle cursor" ></i></a>
          </div>
      </div><br/><br/>
      <legend>{{Template}}</legend>
      <ul id="ul_scenarioTemplateList" class="nav nav-list bs-sidenav"></ul>
  </div>
</div>

<div class="col-lg-10 col-md-9 col-sm-7" id="div_listScenarioTemplate" style="border-left: solid 1px #EEE; padding-left: 25px;display : none;">
    <form class="form-horizontal">
        <legend>{{Général}}</legend>
        <div class="form-group">
            <label class="col-xs-2 control-label">{{Gérer}}</label>
            <div class="col-xs-6">
                <a class='btn btn-warning' id='bt_scenarioTemplateShare'><i class="fa fa-cloud-upload"></i> {{Partager}}</a>
                <a class='btn btn-danger' id='bt_scenarioTemplateRemove'><i class="fa fa-times"></i> {{Supprimer}}</a>
                <a class="btn btn-primary" id="bt_scenarioTemplateDownload"><i class="fa fa-cloud-download"></i> {{Télécharger}}</a>
            </div>
        </div>
        <div id='div_scenarioTemplateParametreConfiguration' style='display : none;'>
            <legend>{{Paramètres du scénario}}<a class='btn btn-warning btn-xs pull-right' id='bt_scenarioTemplateApply'><i class="fa fa-check-circle"></i> Appliquer</a></legend>
            <div id='div_scenarioTemplateParametreList'></div>
        </div>
    </form>
</div>
<div class="col-lg-10 col-md-9 col-sm-7" id="div_marketScenarioTemplate" style="border-left: solid 1px #EEE; padding-left: 25px;display : none;"></div>
</div>

<script>
    function refreshScenarioTemplateList() {
        jeedom.scenario.getTemplate({
            error: function (error) {
                $('#md_scenarioTemplate').showAlert({message: error.message, level: 'danger'});
            },
            success: function (data) {
                $('#ul_scenarioTemplateList').empty();
                li = '';
                for (var i in data) {
                    li += "<li class='cursor li_scenarioTemplate' data-template='" + data[i] + "'><a>" + data[i].replace(".json", "") + "</a></li>";
                }
                $('#ul_scenarioTemplateList').html(li);
            }
        });
    }

    function refreshListAfterMarketObjectInstall(){
         refreshScenarioTemplateList();
    }

    refreshScenarioTemplateList();

    $('#bt_scenarioTemplateDisplayMarket').on('click', function () {
     $('#div_listScenarioTemplate').hide();
     $('#div_marketScenarioTemplate').load('index.php?v=d&modal=market.list&type=scenario').show();
 });

    $('#bt_scenarioTemplateShare').on('click', function () {
        if($('#ul_scenarioTemplateList li.active').attr('data-template') == undefined){
            $('#md_scenarioTemplate').showAlert({message: 'Vous devez d\'abord selectionner un template', level: 'danger'});
            return;
        }
        var logicalId = $('#ul_scenarioTemplateList li.active').attr('data-template').replace(".json", "");
        $('#md_modal2').dialog({title: "{{Partager sur le market}}"});
        $('#md_modal2').load('index.php?v=d&modal=market.send&type=scenario&logicalId=' + encodeURI(logicalId) + '&name=' + encodeURI(logicalId)).dialog('open');
    });

    $('#bt_scenarioTemplateConvert').on('click', function () {
      jeedom.scenario.convertToTemplate({
        id: scenario_template_id,
        template: $('#in_newTemplateName').value()+'.json',
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
        if($('#ul_scenarioTemplateList li.active').attr('data-template') == undefined){
            $('#md_scenarioTemplate').showAlert({message: 'Vous devez d\'abord selectionner un template', level: 'danger'});
            return;
        }
        jeedom.scenario.removeTemplate({
            template: $('#ul_scenarioTemplateList li.active').attr('data-template'),
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
        bootbox.confirm('{{Etes-vous sûr de vouloir appliquer le template ? Cela écrasera votre scénario}}', function (result) {
            if (result) {
                var convert = $('.templateScenario').getValues('.templateScenarioAttr');
                jeedom.scenario.applyTemplate({
                    template:$('#ul_scenarioTemplateList li.active').attr('data-template'),
                    id: scenario_template_id,
                    convert: json_encode(convert),
                    error: function (error) {
                        $('#md_scenarioTemplate').showAlert({message: error.message, level: 'danger'});
                    },
                    success: function (data) {
                        $('#md_scenarioTemplate').showAlert({message: 'Template appliqué avec succès', level: 'success'});
                        $('.li_scenario[data-scenario_id='+scenario_template_id+']').click();
                    }
                });
            }
        });
    });

$('#ul_scenarioTemplateList').delegate('.li_scenarioTemplate','click', function () {
    $('#div_listScenarioTemplate').show();
    $('#div_marketScenarioTemplate').hide();
    $('#ul_scenarioTemplateList .li_scenarioTemplate').removeClass('active');
    $(this).addClass('active');
    jeedom.scenario.loadTemplateDiff({
        template: $(this).attr('data-template'),
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

});

$('#bt_scenarioTemplateDownload').on('click',function(){
    if($('#ul_scenarioTemplateList li.active').attr('data-template') == undefined){
        $('#md_scenarioTemplate').showAlert({message: 'Vous devez d\'abord selectionner un template', level: 'danger'});
        return;
    }
    window.open('core/php/downloadFile.php?pathfile=core/config/scenario/' + $('#ul_scenarioTemplateList li.active').attr('data-template'), "_blank", null);
});

$('#div_scenarioTemplate').delegate('.bt_scenarioTemplateSelectCmd', 'click', function () {
    var el = $(this);
    jeedom.cmd.getSelectModal({}, function (result) {
        el.closest('.templateScenario').find('.templateScenarioAttr[data-l1key=end]').value(result.human);
    });
});


$('#bt_uploadScenarioTemplate').fileupload({
    dataType: 'json',
    replaceFileInput: false,
    done: function (e, data) {
        if (data.result.state != 'ok') {
            $('#md_scenarioTemplate').showAlert({message: data.result.result, level: 'danger'});
            return;
        }
        $('#md_scenarioTemplate').showAlert({message: '{{Template ajouté avec succès.}}', level: 'success'});
        refreshScenarioTemplateList();
    }
});
</script>