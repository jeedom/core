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
      <a class='btn btn-success btn-xs pull-right cursor' style="color: white;" id='bt_saveConfigurePlan'><i class="fas fa-check"></i> {{Sauvegarder}}</a>
    </legend>
    <input type="text"  class="planAttr form-control" data-l1key="id" style="display: none;"/>
    <input type="text"  class="planAttr form-control" data-l1key="link_type" style="display: none;"/>
    <div class="form-group link_type link_eqLogic link_cmd link_scenario">
      <label class="col-lg-4 control-label">{{Taille du widget}}</label>
      <div class="col-lg-2">
       <input type="text" class="planAttr form-control" data-l1key="css" data-l2key="zoom"/>
     </div>
   </div>
   <div class="form-group link_type link_eqLogic link_cmd link_scenario link_graph link_text link_view link_plan link_image link_zone link_summary">
    <label class="col-lg-4 control-label">{{Profondeur}}</label>
    <div class="col-lg-2">
      <select class="form-control planAttr" data-l1key="css" data-l2key="z-index" >
        <option value="999">{{Niveau 0}}</option>
        <option value="1000" selected>{{Niveau 1}}</option>
        <option value="1001">{{Niveau 2}}</option>
        <option value="1002">{{Niveau 3}}</option>
      </select>
    </div>
  </div>
  <div class="form-group link_type link_eqLogic link_cmd link_scenario link_graph link_text link_view link_plan link_image link_zone link_summary">
    <label class="col-lg-4 control-label">{{Position X (%)}}</label>
    <div class="col-lg-2">
      <input type="text" class="planAttr form-control" data-l1key="position" data-l2key="left" />
    </div>
    <label class="col-lg-2 control-label">{{Position Y (%)}}</label>
    <div class="col-lg-2">
      <input type="text" class="planAttr form-control" data-l1key="position" data-l2key="top" />
    </div>
  </div>
  <div class="form-group link_type link_eqLogic link_cmd link_scenario link_graph link_text link_view link_plan link_image link_zone link_summary">
    <label class="col-lg-4 control-label">{{Largeur (px)}}</label>
    <div class="col-lg-2">
      <input type="text" class="planAttr form-control" data-l1key="display" data-l2key="width" />
    </div>
    <label class="col-lg-2 control-label">{{Hauteur (px)}}</label>
    <div class="col-lg-2">
      <input type="text" class="planAttr form-control" data-l1key="display" data-l2key="height" />
    </div>
  </div>
  <legend>{{Spécifique}}</legend>
  <div class="form-group link_type link_image">
    <label class="col-lg-4 control-label">{{Afficher}}</label>
    <div class="col-lg-3">
      <select class="form-control planAttr" data-l1key="configuration" data-l2key="display_mode">
        <option value="image">{{Image}}</option>
        <option value="camera">{{Caméra}}</option>
      </select>
    </div>
  </div>
  <div class="form-group link_type link_image display_mode display_mode_image">
    <label class="col-lg-4 control-label">{{Image}}</label>
    <div class="col-lg-8">
      <span class="btn btn-default btn-file">
        <i class="fas fa-cloud-upload-alt"></i> {{Envoyer}}<input  id="bt_uploadImagePlan" type="file" name="file" style="display: inline-block;">
      </span>
    </div>
  </div>
  <div class="form-group link_type link_image display_mode display_mode_camera" style="display:none;">
    <label class="col-lg-4 control-label">{{Caméra}}</label>
    <div class="col-lg-3">
      <div class="input-group">
        <input type="text" class="planAttr form-control" data-l1key="configuration" data-l2key="camera"/>
        <span class="input-group-btn">
         <a class="btn btn-default" id="bt_planConfigureSelectCamera"><i class="fas fa-list-alt"></i></a>
       </span>
     </div>
   </div>
 </div>

 <div class="form-group link_type link_graph">
  <label class="col-lg-4 control-label">{{Période}}</label>
  <div class="col-lg-2">
    <select class="planAttr form-control" data-l1key="display" data-l2key="dateRange">
      <option value="30 min">{{30min}}</option>
      <option value="1 day">{{Jour}}</option>
      <option value="7 days" selected>{{Semaine}}</option>
      <option value="1 month">{{Mois}}</option>
      <option value="1 year">{{Année}}</option>
      <option value="all">{{Tous}}</option>
    </select>
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
<div class="form-group link_type link_summary">
  <label class="col-lg-4 control-label">{{Lien}}</label>
  <div class="col-lg-2">
    <select class="form-control planAttr" data-l1key="link_id">
      <option value="-1">{{Aucun}}</option>
      <option value="0">{{Général}}</option>
      <?php
foreach (jeeObject::all() as $object) {
	echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
}
?>
   </select>
 </div>
</div>
<div class="form-group link_type link_view">
  <label class="col-lg-4 control-label">{{Lien}}</label>
  <div class="col-lg-2">
    <select class="form-control planAttr" data-l1key="link_id">
      <?php
foreach (view::all() as $view) {
	echo '<option value="' . $view->getId() . '">' . $view->getName() . '</option>';
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
foreach (planHeader::all() as $plan) {
	echo '<option value="' . $plan->getId() . '">' . $plan->getName() . '</option>';
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
    <a class="btn btn-default btn-sm" id="bt_chooseIcon"><i class="fas fa-flag"></i> {{Choisir une icône}}</a>
  </div>
</div>
<div class="form-group link_type link_plan link_view link_text link_summary">
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
<div class="form-group link_type link_plan link_view link_text link_summary">
  <label class="col-lg-4 control-label">{{Couleur du texte}}</label>
  <div class="col-lg-2">
    <input type="color" class="planAttr form-control" data-l1key="css" data-l2key="color" />
  </div>
  <label class="col-lg-1 control-label">{{Défaut}}</label>
  <div class="col-lg-1">
    <input type="checkbox" class="planAttr" data-l1key="display" data-l2key="color-defaut" checked />
  </div>
</div>
<div class="form-group link_type link_plan link_view link_text link_summary">
  <label class="col-lg-4 control-label">{{Arrondir les angles (ne pas oublié de mettre %. Ex : 50%)}}</label>
  <div class="col-lg-2">
    <input class="form-control planAttr" data-l1key="css" data-l2key="border-radius" />
  </div>
</div>
<div class="form-group link_type link_plan link_view link_text link_graph link_summary">
  <label class="col-lg-4 control-label">{{Bordure (attention syntaxe CSS. Ex : solid 1px black)}}</label>
  <div class="col-lg-2">
    <input class="form-control planAttr" data-l1key="css" data-l2key="border" />
  </div>
</div>
<div class="form-group link_type link_plan link_view link_text link_summary">
  <label class="col-lg-4 control-label">{{Taille de la police (il faut bien mettre le signe %. Ex : 50%)}}</label>
  <div class="col-lg-2">
    <input class="planAttr form-control" data-l1key="css" data-l2key="font-size" />
  </div>
</div>
<div class="form-group link_type link_plan link_view link_text">
  <label class="col-lg-4 control-label">{{Alignement du texte}}</label>
  <div class="col-lg-2">
    <select class="planAttr form-control" data-l1key="css" data-l2key="text-align">
      <option value="initial">{{Par defaut}}</option>
      <option value="left">{{Gauche}}</option>
      <option value="right">{{Droite}}</option>
      <option value="center">{{Centré}}</option>
    </select>
  </div>
</div>
<div class="form-group link_type link_plan link_view link_text link_summary">
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
<div class="link_type link_zone">
  <div class="form-group">
    <label class="col-lg-4 control-label">{{Type de zone}}</label>
    <div class="col-lg-2">
      <select class="planAttr form-control" data-l1key="configuration" data-l2key="zone_mode">
        <option value="simple">{{Macro simple}}</option>
        <option value="binary">{{Macro binaire}}</option>
        <option value="widget">{{Widget au survol}}</option>
      </select>
    </div>
  </div>


  <div class="zone_mode zone_simple">
    <legend>{{Action}}<a class="btn btn-success pull-right btn-xs bt_planConfigurationAction" data-type="other"><i class="fas fa-plus"></i></a></legend>
    <div id="div_planConfigureActionother"></div>
  </div>

  <div class="zone_mode zone_widget" style="display:none;">
    <div class="form-group">
      <label class="col-lg-4 control-label">{{Equipement}}</label>
      <div class="col-lg-3">
       <div class="input-group">
         <input type="text" class="planAttr form-control" data-l1key="configuration" data-l2key="eqLogic"/>
         <span class="input-group-btn">
           <a class="btn btn-default" id="bt_planConfigureAddEqLogic"><i class="fas fa-list-alt"></i></a>
         </div>
       </div>
     </div>
     <div class="form-group">
      <label class="col-lg-4 control-label">{{Afficher au survol}}</label>
      <div class="col-lg-2">
        <input type="checkbox" checked class="planAttr" data-l1key="configuration" data-l2key="showOnFly" >
      </div>
    </div>
    <div class="form-group">
      <label class="col-lg-4 control-label">{{Afficher sur un clic}}</label>
      <div class="col-lg-2">
        <input type="checkbox" checked class="planAttr" data-l1key="configuration" data-l2key="showOnClic" >
      </div>
    </div>
    <div class="form-group">
      <label class="col-lg-4 control-label">{{Position}}</label>
      <div class="col-lg-2">
        <select class="planAttr form-control" data-l1key="configuration" data-l2key="position" >
		<option value="">{{Défaut}}</option>
		<option value="bottom:0px;">{{Haut}}</option>
		<option value="right:0px;">{{Gauche}}</option>
		<option value="top:0px;">{{Bas}}</option>
		<option value="left:0px;">{{Droite}}</option>
		<option value="bottom:0px;right:0px;">{{Haut Gauche}}</option>
		<option value="bottom:0px;left:0px;">{{Haut Droite}}</option>
		<option value="top:0px;right:0px">{{Bas Gauche}}</option>
		<option value="top:0px;left:0px">{{Bas Droite}}</option>
	</select>
      </div>
    </div>
  </div>
  <div class="zone_mode zone_binary" style="display: none;">
    <div class="form-group">
      <label class="col-lg-4 control-label">{{Information binaire}}</label>
      <div class="col-lg-3">
       <input type="text" class="planAttr form-control" data-l1key="configuration" data-l2key="binary_info"/>
     </div>
     <div class="col-lg-3">
       <a class="btn btn-default" id="bt_planConfigureSelectBinary"><i class="fas fa-list-alt"></i></a>
     </div>
   </div>
   <legend>{{Action on}}<a class="btn btn-success pull-right btn-xs bt_planConfigurationAction" data-type="on"><i class="fas fa-plus"></i></a></legend>
   <div id="div_planConfigureActionon"></div>

   <legend>{{Action off}}<a class="btn btn-success pull-right btn-xs bt_planConfigurationAction" data-type="off"><i class="fas fa-plus"></i></a></legend>
   <div id="div_planConfigureActionoff"></div>
 </div>
</div>
</fieldset>
</form>
<script>
  $('.planAttr[data-l1key=configuration][data-l2key=zone_mode]').on('change',function(){
    $('.zone_mode').hide();
    $('.zone_mode.zone_'+$(this).value()).show();
  });

  $('.planAttr[data-l1key=configuration][data-l2key=display_mode]').on('change',function(){
    $('.display_mode').hide();
    $('.display_mode.display_mode_'+$(this).value()).show();
  });

  $('.bt_planConfigurationAction').on('click',function(){
    addActionPlanConfigure({},$(this).attr('data-type'));
  });

  $("body").delegate('.bt_removeAction', 'click', function () {
    $(this).closest('.' +  $(this).attr('data-type')).remove();
  });

  $("body").delegate(".listCmdAction", 'click', function () {
    var type = $(this).attr('data-type');
    var el = $(this).closest('.' + type).find('.expressionAttr[data-l1key=cmd]');
    jeedom.cmd.getSelectModal({cmd: {type: 'action'}}, function (result) {
      el.value(result.human);
      jeedom.cmd.displayActionOption(el.value(), '', function (html) {
        el.closest('.' + type).find('.actionOptions').html(html);
        taAutosize();
      });
    });
  });

  $('body').off('focusout','.expressionAttr[data-l1key=cmd]').on('focusout','.expressionAttr[data-l1key=cmd]',  function (event) {
   var type = $(this).attr('data-type');
   var el = $(this);
   jeedom.cmd.displayActionOption(el.value(), '', function (html) {
    el.closest('.' + type).find('.actionOptions').html(html);
    taAutosize();
  });
 });

  $('body').off('click','.bt_selectOtherActionExpression').on('click','.bt_selectOtherActionExpression',  function (event) {
    var expression = $(this).closest('.expression');
    jeedom.getSelectActionModal({scenario : true}, function (result) {
     expression.find('.expressionAttr[data-l1key=cmd]').value(result.human);
     jeedom.cmd.displayActionOption(expression.find('.expressionAttr[data-l1key=cmd]').value(), '', function (html) {
      expression.find('.actionOptions').html(html);
      taAutosize();
    });
   });
  });

  function addActionPlanConfigure(_action, _type) {
    if (!isset(_action)) {
      _action = {};
    }
    if (!isset(_action.options)) {
      _action.options = {};
    }
    var div = '<div class="expression ' + _type + '">';
    div += '<div class="form-group ">';
    div += '<label class="col-sm-1 control-label">{{Action}}</label>';
    div += '<div class="col-sm-4">';
    div += '<div class="input-group">';
    div += '<span class="input-group-btn">';
    div += '<a class="btn btn-default bt_removeAction btn-sm" data-type="' + _type + '"><i class="fas fa-minus-circle"></i></a>';
    div += '</span>';
    div += '<input class="expressionAttr form-control input-sm cmdAction" data-l1key="cmd" data-type="' + _type + '" />';
    div += '<span class="input-group-btn">';
    div += '<a class="btn btn-default btn-sm bt_selectOtherActionExpression" data-type="' + _type + '" title="{{Sélectionner un mot-clé}}"><i class="fas fa-tasks"></i></a>';
    div += '<a class="btn btn-default btn-sm listCmdAction" data-type="' + _type + '"><i class="fas fa-list-alt"></i></a>';
    div += '</span>';
    div += '</div>';
    div += '</div>';
    div += '<div class="col-sm-7 actionOptions">';
    div += jeedom.cmd.displayActionOption(init(_action.cmd, ''), _action.options);
    div += '</div>';
    div += '</div>';
    $('#div_planConfigureAction' + _type).append(div);
    $('#div_planConfigureAction' + _type + ' .' + _type + ':last').setValues(_action, '.expressionAttr');
    taAutosize();
  }


  $('#bt_planConfigureAddEqLogic').on('click', function() {
    var el = $(this);
    jeedom.eqLogic.getSelectModal({}, function(result) {
      el.parent().parent().find('.planAttr[data-l1key=configuration][data-l2key=eqLogic]').value(result.human);
    });
  });

  $('#bt_planConfigureSelectCamera').on('click', function() {
    var el = $(this);
    jeedom.eqLogic.getSelectModal({eqLogic: {eqType_name: 'camera'}}, function(result) {
      el.parent().parent().find('.planAttr[data-l1key=configuration][data-l2key=camera]').value(result.human);
    });
  });

  $('#bt_planConfigureSelectBinary').on('click', function() {
    var el = $(this);
    jeedom.cmd.getSelectModal({cmd: {type: 'info'}}, function(result) {
      el.parent().parent().find('.planAttr[data-l1key=configuration][data-l2key=binary_info]').value(result.human);
    });
  });
  $('#bt_uploadImagePlan').fileupload({
    replaceFileInput: false,
    url: 'core/ajax/plan.ajax.php?action=uploadImagePlan&id=' + id+'&jeedom_token='+JEEDOM_AJAX_TOKEN,
    dataType: 'json',
    done: function (e, data) {
      if (data.result.state != 'ok') {
        $('#div_alertPlanConfigure').showAlert({message: data.result.result, level: 'danger'});
        return;
      }
    }
  });

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
      if (isset(data.result.configuration.action_on)) {
        for (var i in data.result.configuration.action_on) {
          addActionPlanConfigure(data.result.configuration.action_on[i],'on');
        }
      }
      if (isset(data.result.configuration.action_off)) {
        for (var i in data.result.configuration.action_off) {
          addActionPlanConfigure(data.result.configuration.action_off[i],'off');
        }
      }
      if (isset(data.result.configuration.action_other)) {
        for (var i in data.result.configuration.action_other) {
          addActionPlanConfigure(data.result.configuration.action_other[i],'other');
        }
      }
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
  if(!isset(plans[0].configuration)){
    plans[0].configuration = {};
  }
  plans[0].configuration.action_on = $('#div_planConfigureActionon .on').getValues('.expressionAttr');
  plans[0].configuration.action_off = $('#div_planConfigureActionoff .off').getValues('.expressionAttr');
  plans[0].configuration.action_other = $('#div_planConfigureActionother .other').getValues('.expressionAttr');
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
