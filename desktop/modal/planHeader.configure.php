<?php
if (!isConnect('admin')) {
    throw new Exception('{{401 - Accès non autorisé}}');
}

$planHeader = planHeader::byId(init('planHeader_id'));
if (!is_object($planHeader)) {
    throw new Exception('Impossible de trouver le plan');
}
sendVarToJS('id', $planHeader->getId())
?>
<div id="div_alertPlanHeaderConfigure"></div>
<a class='btn btn-success btn-xs pull-right cursor' style="color: white;" id='bt_saveConfigurePlanHeader'><i class="fa fa-check"></i> Sauvegarder</a>
<a class='btn btn-danger  btn-xs pull-right cursor' style="color: white;" id='bt_removeConfigurePlanHeader'><i class="fa fa-times"></i> Supprimer</a>
<form class="form-horizontal">
    <fieldset id="fd_planHeaderConfigure">
        <legend>{{Général}}</legend>
        <input type="text"  class="planHeaderAttr form-control" data-l1key="id" style="display: none;"/>
        <div class="form-group">
            <label class="col-lg-4 control-label">{{Nom}}</label>
            <div class="col-lg-2">
                <input class="planHeaderAttr form-control" data-l1key="name" />
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label">{{Disponible sur téléphone}}</label>
            <div class="col-lg-8">
                <input type="checkbox" class="planHeaderAttr" data-l1key="configuration" data-l2key="enableOnMobile"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label">{{Image}}</label>
            <div class="col-lg-8">
                <input  id="bt_uploadImage" type="file" name="file" style="display: inline-block;">
            </div>
        </div>
        <legend>{{Tailles}}</legend>
        <div class="form-group expertModeVisible">
            <label class="col-lg-4 control-label">{{Responsive mode (Attention toute les valeurs de taille sont ignorées)}}</label>
            <div class="col-lg-4">
                <input type="checkbox" class="planHeaderAttr" data-l1key='configuration' data-l2key="responsiveMode" /> 
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label">{{Taille (LxH)}}</label>
            <div class="col-lg-4">
                <input class="form-control input-sm planHeaderAttr" data-l1key='configuration' data-l2key="desktopSizeX" style="width: 80px;display: inline-block;"/> 
                x 
                <input class="form-control input-sm planHeaderAttr" data-l1key='configuration' data-l2key='desktopSizeY' style="width: 80px;display: inline-block;"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label">{{Proportion tablette (ex : 0.7)}}</label>
            <div class="col-lg-1">
                <input class="form-control input-sm planHeaderAttr" data-l1key='configuration' data-l2key="tabletteProportion" value="1"/>
            </div>
        </div>
        <div class="form-group">
            <label class="col-lg-4 control-label">{{Proportion téléphone (ex 0.5)}}</label>
            <div class="col-lg-1">
                <input class="form-control input-sm planHeaderAttr" data-l1key='configuration' data-l2key="mobileProportion" value="1"/>
            </div>
        </div>
    </fieldset>
</form>


<script>
    $('#bt_uploadImage').fileupload({
        replaceFileInput: false,
        url: 'core/ajax/plan.ajax.php?action=uploadImage&id=' + planHeader_id,
        dataType: 'json',
        done: function (e, data) {
            if (data.result.state != 'ok') {
                $('#div_alertPlanHeaderConfigure').showAlert({message: data.result.result, level: 'danger'});
                return;
            }
        }
    });

    $('#bt_saveConfigurePlanHeader').on('click', function () {
        save();
    });

    $('#bt_removeConfigurePlanHeader').on('click', function () {
        bootbox.confirm('Etes-vous sûr de vouloir supprimer cet object du design ?', function (result) {
            if (result) {
                remove();
            }
        });
    });

    if (isset(id) && id != '') {
        load(id);
    }

    function load(_id) {
        $.ajax({// fonction permettant de faire de l'ajax
            type: "POST", // methode de transmission des données au fichier php
            url: "core/ajax/plan.ajax.php", // url du fichier php
            data: {
                action: "getPlanHeader",
                id: _id
            },
            dataType: 'json',
            error: function (request, status, error) {
                handleAjaxError(request, status, error, $('#div_alertPlanHeaderConfigure'));
            },
            success: function (data) { // si l'appel a bien fonctionné
                if (data.state != 'ok') {
                    $('#div_alertPlanHeaderConfigure').showAlert({message: data.result, level: 'danger'});
                    return;
                }
                $('#fd_planHeaderConfigure').setValues(data.result, '.planHeaderAttr');
                $('.planHeaderAttr[data-l1key=configuration][data-l2key=preconfigureDevice]').off().on('change', function () {
                    $('.planHeaderAttr[data-l1key=configuration][data-l2key=sizeX]').value($(this).find('option:selected').attr('data-width'));
                    $('.planHeaderAttr[data-l1key=configuration][data-l2key=sizeY]').value($(this).find('option:selected').attr('data-height'));
                    $('.planHeaderAttr[data-l1key=configuration][data-l2key=maxSizeAllow]').value(1);
                    $('.planHeaderAttr[data-l1key=configuration][data-l2key=minSizeAllow]').value(1);
                });
            }
        });
    }


    function save() {
        jeedom.plan.saveHeader({
            planHeader: $('#fd_planHeaderConfigure').getValues('.planHeaderAttr')[0],
            error: function (error) {
                $('#div_alertPlanHeaderConfigure').showAlert({message: error.message, level: 'danger'});
            },
            success: function () {
                $('#div_alertPlanHeaderConfigure').showAlert({message: 'Design sauvegardé', level: 'success'});
                window.location.reload();
            },
        });
    }

    function remove() {
        $.ajax({// fonction permettant de faire de l'ajax
            type: "POST", // methode de transmission des données au fichier php
            url: "core/ajax/plan.ajax.php", // url du fichier php
            data: {
                action: "removePlanHeader",
                id: $(".planHeaderAttr[data-l1key=id]").value()
            },
            dataType: 'json',
            error: function (request, status, error) {
                handleAjaxError(request, status, error, $('#div_alertPlanHeaderConfigure'));
            },
            success: function (data) { // si l'appel a bien fonctionné
                if (data.state != 'ok') {
                    $('#div_alertPlanHeaderConfigure').showAlert({message: data.result, level: 'danger'});
                    return;
                }
                $('#div_alertPlanHeaderConfigure').showAlert({message: 'Design supprimé', level: 'success'});
                window.location.reload();
            }
        });
    }

</script>