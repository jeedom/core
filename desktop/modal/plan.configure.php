<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$plan = plan::byId(init('id'));
if (!is_object($plan)) {
	throw new Exception('Impossible de trouver le design');
}
$link = $plan->getLink();
sendVarToJS('id', $plan->getId());
?>
<div id="div_alertPlanConfigure"></div>

<form class="form-horizontal">
    <fieldset id="fd_planConfigure">
    <legend>{{Général}}
            <a class='btn btn-success btn-xs pull-right cursor' style="color: white;" id='bt_saveConfigurePlan'><i class="fa fa-check"></i> {{Sauvegarder}}</a>
        </legend>
        <input type="text"  class="planAttr form-control" data-l1key="id" style="display: none;"/>
        <input type="text"  class="planAttr form-control" data-l1key="link_type" style="display: none;"/>
        <div class="form-group link_type link_eqLogic link_cmd link_scenario">
            <label class="col-lg-4 control-label">{{Taille du widget}}</label>
            <div class="col-lg-2">
             <input type="text" class="planAttr form-control" data-l1key="css" data-l2key="zoom"/>
         </div>
     </div>
     <div class="form-group link_type link_eqLogic link_cmd link_scenario link_graph link_text link_view link_plan">
        <label class="col-lg-4 control-label">{{Profondeur}}</label>
        <div class="col-lg-2">
            <select class="form-control planAttr" data-l1key="css" data-l2key="z-index" >
                <option value="99">{{Niveau -1}}</option>
                <option value="1000" selected>{{Niveau 1}}</option>
                <option value="1001">{{Niveau 2}}</option>
                <option value="1002">{{Niveau 3}}</option>
            </select>
        </div>
    </div>
    <div class="form-group link_type link_eqLogic link_cmd link_scenario link_graph link_text link_view link_plan">
        <label class="col-lg-4 control-label">{{Position X (%)}}</label>
        <div class="col-lg-2">
            <input type="text" class="planAttr form-control" data-l1key="position" data-l2key="top" />
        </div>
        <label class="col-lg-2 control-label">{{Position Y (%)}}</label>
        <div class="col-lg-2">
            <input type="text" class="planAttr form-control" data-l1key="position" data-l2key="left" />
        </div>
    </div>
    <legend>{{Spécifique}}</legend>
    <div class="form-group link_type link_graph">
        <label class="col-lg-4 control-label">{{Période}}</label>
        <div class="col-lg-2">
            <select class="planAttr form-control" data-l1key="display" data-l2key="dateRange">
                <option value="30 min">{{30min}}</option>
                <option value="1 day">{{Jour}}</option>
                <option value="7 days" selected>{{Semaine}}</option>
                <option value="1 month">{{Mois}}</option>
                <option value="1 year">{{Années}}</option>
                <option value="all">{{Tous}}</option>
            </select>
        </div>
    </div>
    <div class="form-group link_type link_graph">
        <label class="col-lg-4 control-label">{{Bordure (attention syntax css, ex : solid 1px black}}</label>
        <div class="col-lg-2">
            <input class="form-control planAttr" data-l1key="css" data-l2key="border" />
        </div>
    </div>
    <div class="form-group link_type link_graph">
        <label class="col-lg-4 control-label">{{Afficher la légende}}</label>
        <div class="col-lg-2">
            <input type="checkbox" checked class="planAttr" data-l1key="display" data-l2key="showLegend" >
        </div>
    </div>
    <div class="form-group link_type link_graph">
        <label class="col-lg-4 control-label">{{Afficher le navigateur}}</label>
        <div class="col-lg-2">
            <input type="checkbox" checked class="planAttr" data-l1key="display" data-l2key="showNavigator" >
        </div>
    </div>
    <div class="form-group link_type link_graph">
        <label class="col-lg-4 control-label">{{Afficher le sélecteur de période}}</label>
        <div class="col-lg-2">
            <input type="checkbox" class="planAttr" checked data-l1key="display" data-l2key="showTimeSelector" >
        </div>
    </div>
    <div class="form-group link_type link_graph">
        <label class="col-lg-4 control-label">{{Afficher la barre de défilement}}</label>
        <div class="col-lg-2">
            <input type="checkbox" class="planAttr" checked data-l1key="display" data-l2key="showScrollbar" >
        </div>
    </div>
    <div class="form-group link_type link_graph">
        <label class="col-lg-4 control-label">{{Fond transparent}}</label>
        <div class="col-lg-2">
            <input type="checkbox" class="planAttr" checked data-l1key="display" data-l2key="transparentBackground" >
        </div>
    </div>
    <div class="form-group link_type link_plan link_view">
        <label class="col-lg-4 control-label">{{Nom}}</label>
        <div class="col-lg-2">
            <input class="planAttr form-control" data-l1key="display" data-l2key="name" />
        </div>
    </div>
    <div class="form-group link_type link_view">
        <label class="col-lg-4 control-label">{{Lien}}</label>
        <div class="col-lg-2">
            <select class="form-control planAttr" data-l1key="link_id">
                <?php
foreach (view::all() as $views) {
	echo '<option value="' . $views->getId() . '">' . $views->getName() . '</option>';
}
?>
           </select>
       </div>
   </div>
   <div class="form-group link_type link_plan">
    <label class="col-lg-4 control-label">{{Lien}}</label>
    <div class="col-lg-2">
        <select class="form-control planAttr" data-l1key="link_id">
            <?php
foreach (planHeader::all() as $planHeader_select) {
	if ($planHeader_select->getId() != $plan->getPlanHeader_id()) {
		echo '<option value="' . $planHeader_select->getId() . '">' . $planHeader_select->getName() . '</option>';
	}
}
?>
      </select>
  </div>
</div>
<div class="form-group link_type link_plan link_view link_text">
    <label class="col-lg-4 control-label">{{Icône}}</label>
    <div class="col-lg-2">
        <div class="planAttr" data-l1key="display" data-l2key="icon" ></div>
    </div>
    <div class="col-lg-2">
        <a class="btn btn-default btn-sm" id="bt_chooseIcon"><i class="fa fa-flag"></i> {{Choisir une icône}}</a>
    </div>
</div>
<div class="form-group link_type link_plan link_view link_text">
    <label class="col-lg-4 control-label">{{Couleur de fond}}</label>
    <div class="col-lg-2">
        <input type="color" class="planAttr form-control" data-l1key="css" data-l2key="background-color" />
    </div>
    <label class="col-lg-1 control-label">{{Transparent}}</label>
    <div class="col-lg-2">
        <input type="checkbox" class="planAttr" data-l1key="display" data-l2key="background-transparent" />
    </div>
    <label class="col-lg-1 control-label">{{Défaut}}</label>
    <div class="col-lg-1">
        <input type="checkbox" class="planAttr" data-l1key="display" data-l2key="background-defaut" checked />
    </div>
</div>
<div class="form-group link_type link_plan link_view link_text">
    <label class="col-lg-4 control-label">{{Couleur du texte}}</label>
    <div class="col-lg-2">
        <input type="color" class="planAttr form-control" data-l1key="css" data-l2key="color" />
    </div>
    <label class="col-lg-1 control-label">{{Défaut}}</label>
    <div class="col-lg-1">
        <input type="checkbox" class="planAttr" data-l1key="display" data-l2key="color-defaut" checked />
    </div>
</div>
<div class="form-group link_type link_plan link_view link_text">
    <label class="col-lg-4 control-label">{{Arrondir les angles (ne pas oublié de mettre %, ex 50%)}}</label>
    <div class="col-lg-2">
        <input class="form-control planAttr" data-l1key="css" data-l2key="border-radius" />
    </div>
</div>
<div class="form-group link_type link_plan link_view link_text">
    <label class="col-lg-4 control-label">{{Bordure (attention syntax css, ex : solid 1px black)}}</label>
    <div class="col-lg-2">
        <input class="form-control planAttr" data-l1key="css" data-l2key="border" />
    </div>
</div>
<div class="form-group link_type link_plan link_view link_text">
    <label class="col-lg-4 control-label">{{Taille de la police (ex 50%, il faut bien mettre le signe %)}}</label>
    <div class="col-lg-2">
        <input class="planAttr form-control" data-l1key="css" data-l2key="font-size" />
    </div>
</div>
<div class="form-group link_type link_plan link_view link_text">
    <label class="col-lg-4 control-label">{{Gras}}</label>
    <div class="col-lg-2">
        <select class="planAttr form-control" data-l1key="css" data-l2key="font-weight">
            <option value="bold">{{Gras}}</option>
            <option value="normal">{{Normal}}</option>
        </select>
    </div>
</div>
<div class="form-group link_type link_text">
    <label class="col-lg-4 control-label">{{Texte}}</label>
    <div class="col-lg-8">
        <textarea class="planAttr form-control" data-l1key="display" data-l2key="text" rows="10">Texte à insérer ici</textarea>
    </div>
</div>
</fieldset>
</form>
<script>
    $('#fd_planConfigure').on('change','.planAttr[data-l1key=display][data-l2key=background-transparent]', function() {
        if($(this).value() == 1){
            $('.planAttr[data-l1key=display][data-l2key=background-defaut]').value(0);
        }
    });

    $('#fd_planConfigure').on('change','.planAttr[data-l1key=css][data-l2key=background-color]', function() {
       if($(this).value() != '#000000'){
        $('.planAttr[data-l1key=display][data-l2key=background-defaut]').value(0);
    }
});

    $('#fd_planConfigure').on('change','.planAttr[data-l1key=display][data-l2key=background-defaut]', function() {
        if($(this).value() == 1){
            $('.planAttr[data-l1key=display][data-l2key=background-transparent]').value(0);
            $('.planAttr[data-l1key=css][data-l2key=background-color]').value('#000000');
        }
    });

    editor = [];

    $('#bt_chooseIcon').on('click', function () {
        chooseIcon(function (_icon) {
            $('.planAttr[data-l1key=display][data-l2key=icon]').empty().append(_icon);
        });
    });

    $('#bt_saveConfigurePlan').on('click', function () {
        save();
    });

    if (isset(id) && id != '') {
     $.ajax({
        type: "POST",
        url: "core/ajax/plan.ajax.php",
        data: {
            action: "get",
            id: id
        },
        dataType: 'json',
        error: function (request, status, error) {
            handleAjaxError(request, status, error, $('#div_alertPlanConfigure'));
        },
        success: function (data) {
            if (data.state != 'ok') {
                $('#div_alertPlanConfigure').showAlert({message: data.result, level: 'danger'});
                return;
            }
            $('.link_type:not(.link_'+data.result.link_type+')').remove()
            $('#fd_planConfigure').setValues(data.result, '.planAttr');
            if (data.result.link_type == 'text') {
                var code = $('.planAttr[data-l1key=display][data-l2key=text]');
                if (code.attr('id') == undefined) {
                    code.uniqueId();
                    var id = code.attr('id');
                    setTimeout(function () {
                        editor[id] = CodeMirror.fromTextArea(document.getElementById(id), {
                            lineNumbers: true,
                            mode: 'htmlmixed',
                            matchBrackets: true
                        });
                    }, 1);
                }
            }
        }
    });
 }

 function save() {
    var plans = $('#fd_planConfigure').getValues('.planAttr');
    if (plans[0].link_type == 'text') {
        var id = $('.planAttr[data-l1key=display][data-l2key=text]').attr('id');
        if (id != undefined && isset(editor[id])) {
            plans[0].display.text = editor[id].getValue();
        }
    }
    jeedom.plan.save({
        plans: plans,
        error: function (error) {
            $('#div_alertPlanConfigure').showAlert({message: error.message, level: 'danger'});
        },
        success: function () {
            $('#div_alertPlanConfigure').showAlert({message: 'Design sauvegardé', level: 'success'});
            displayPlan();
            $('#fd_planConfigure').closest("div.ui-dialog-content").dialog("close");
        },
    });
}
</script>