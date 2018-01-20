<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

$planHeader = planHeader::byId(init('planHeader_id'));
if (!is_object($planHeader)) {
	throw new Exception('Impossible de trouver le plan');
}
sendVarToJS('id', $planHeader->getId());
sendVarToJS('planHeader', utils::o2a($planHeader));
?>
<div id="div_alertPlanHeaderConfigure"></div>

<div id="div_planHeaderConfigure">
    <form class="form-horizontal">
        <fieldset>
            <legend><i class="fa fa-cog"></i> {{Général}}<a class='btn btn-success btn-xs pull-right cursor' style="color: white;" id='bt_saveConfigurePlanHeader'><i class="fa fa-check"></i> {{Sauvegarder}}</a></legend>
            <input type="text"  class="planHeaderAttr form-control" data-l1key="id" style="display: none;"/>
            <div class="form-group">
                <label class="col-lg-4 control-label">{{Nom}}</label>
                <div class="col-lg-2">
                    <input class="planHeaderAttr form-control" data-l1key="name" />
                </div>
            </div>
             <div class="form-group">
                <label class="col-lg-4 control-label">{{Fond transparent}}</label>
                <div class="col-lg-2">
                    <input type="checkbox" class="planHeaderAttr" data-l1key="configuration" data-l2key="backgroundTransparent" />
                </div>
            </div>
             <div class="form-group">
                <label class="col-lg-4 control-label">{{Couleur de fond}}</label>
                <div class="col-lg-2">
                    <input type="color" class="planHeaderAttr form-control" data-l1key="configuration" data-l2key="backgroundColor" />
                </div>
            </div>
            <div class="form-group">
                <label class="col-lg-4 control-label">{{Code d'accès}}</label>
                <div class="col-lg-2">
                    <input type="password" class="planHeaderAttr form-control" data-l1key="configuration" data-l2key="accessCode" />
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
                <label class="col-lg-4 control-label">{{Image}}</label>
                <div class="col-lg-8">
                  <span class="btn btn-default btn-file">
                    <i class="fa fa-cloud-upload"></i> {{Envoyer}}<input  id="bt_uploadImage" type="file" name="file" style="display: inline-block;">
                </span>
                <a class="btn btn-danger" id="bt_removeBackgroundImage"><i class="fa fa-trash"></i> {{Supprimer l'image}}</a>
            </div>
        </div>
    </fieldset>
</form>
<form class="form-horizontal">
    <fieldset>
        <legend><i class="icon techno-fleches"></i> {{Tailles}}</legend>
        <div class="form-group">
            <label class="col-lg-4 control-label">{{Taille (LxH)}}</label>
            <div class="col-lg-4">
                <input class="form-control input-sm planHeaderAttr" data-l1key='configuration' data-l2key="desktopSizeX" style="width: 80px;display: inline-block;"/>
                x
                <input class="form-control input-sm planHeaderAttr" data-l1key='configuration' data-l2key='desktopSizeY' style="width: 80px;display: inline-block;"/>
            </div>
        </div>
    </fieldset>
</form>
</div>

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
        url: 'core/ajax/plan.ajax.php?action=uploadImage&id=' + planHeader_id+'&jeedom_token='+JEEDOM_AJAX_TOKEN,
        dataType: 'json',
        done: function (e, data) {
            if (data.result.state != 'ok') {
                $('#div_alertPlanHeaderConfigure').showAlert({message: data.result.result, level: 'danger'});
                return;
            }
            loadPage('index.php?v=d&p=plan&plan_id='+planHeader_id);
        }
    });

    $('#bt_removeBackgroundImage').on('click', function () {
      jeedom.plan.removeImageHeader({
        planHeader_id: planHeader_id,
        error: function (error) {
            $('#div_alertPlanHeaderConfigure').showAlert({message: error.message, level: 'danger'});
        },
        success: function () {
            $('#div_alertPlanHeaderConfigure').showAlert({message: '{{Image supprimée}}', level: 'success'});
        },
    });
  });

    $('#bt_saveConfigurePlanHeader').on('click', function () {
      jeedom.plan.saveHeader({
        planHeader: $('#div_planHeaderConfigure').getValues('.planHeaderAttr')[0],
        error: function (error) {
            $('#div_alertPlanHeaderConfigure').showAlert({message: error.message, level: 'danger'});
        },
        success: function () {
            $('#div_alertPlanHeaderConfigure').showAlert({message: '{{Design sauvegardé}}', level: 'success'});
            loadPage('index.php?v=d&p=plan&plan_id='+planHeader_id);
        },
    });
  });

    if (isset(id) && id != '') {
       $('#div_planHeaderConfigure').setValues(planHeader, '.planHeaderAttr');
   }
</script>