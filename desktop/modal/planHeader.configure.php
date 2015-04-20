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
            <label class="col-lg-4 control-label">{{Icône}}</label>
            <div class="col-lg-2">
                <div class="planHeaderAttr" data-l1key="configuration" data-l2key="icon" ></div>
            </div>
            <div class="col-lg-2 col-md-3 col-sm-4 col-xs-4">
                <a class="btn btn-default btn-sm" id="bt_chooseIcon"><i class="fa fa-flag"></i> {{Choisir}}</a>
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
        <div class="form-group expertModeVisible">
            <label class="col-lg-4 control-label">{{Ne pas afficher la fleche de retour lors de la mise en pleine écran}}</label>
            <div class="col-lg-4">
                <input type="checkbox" class="planHeaderAttr" data-l1key='configuration' data-l2key="noReturnFullScreen" />
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
    $('.planHeaderAttr[data-l1key=configuration][data-l2key=icon]').on('dblclick',function(){
        $('.planHeaderAttr[data-l1key=configuration][data-l2key=icon]').value('');
    });

    $('#bt_chooseIcon').on('click', function () {
        chooseIcon(function (_icon) {
            $('.planHeaderAttr[data-l1key=configuration][data-l2key=icon]').empty().append(_icon);
        });
    });

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
  });

    $('#bt_removeConfigurePlanHeader').on('click', function () {
        bootbox.confirm('Etes-vous sûr de vouloir supprimer cet object du design ?', function (result) {
            if (result) {
               jeedom.plan.removeHeader({
                id: $(".planHeaderAttr[data-l1key=id]").value(),
                error: function (error) {
                    $('#div_alertPlanHeaderConfigure').showAlert({message: error.message, level: 'danger'});
                },
                success: function () {
                   $('#div_alertPlanHeaderConfigure').showAlert({message: 'Design supprimé', level: 'success'});
                   window.location.reload();
               },
           });
           }
       });
    });

    if (isset(id) && id != '') {
     jeedom.plan.getHeader({
        id: id,
        error: function (error) {
            $('#div_alertPlanHeaderConfigure').showAlert({message: error.message, level: 'danger'});
        },
        success: function (planHeader) {
           $('#fd_planHeaderConfigure').setValues(planHeader, '.planHeaderAttr');
           $('.planHeaderAttr[data-l1key=configuration][data-l2key=preconfigureDevice]').off().on('change', function () {
            $('.planHeaderAttr[data-l1key=configuration][data-l2key=sizeX]').value($(this).find('option:selected').attr('data-width'));
            $('.planHeaderAttr[data-l1key=configuration][data-l2key=sizeY]').value($(this).find('option:selected').attr('data-height'));
            $('.planHeaderAttr[data-l1key=configuration][data-l2key=maxSizeAllow]').value(1);
            $('.planHeaderAttr[data-l1key=configuration][data-l2key=minSizeAllow]').value(1);
        });
       },
   });
 }
</script>