    <?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$cmd = cmd::byId(init('cmd_id'));
if (!is_object($cmd)) {
	throw new Exception('Commande non trouvé : ' . init('cmd_id'));
}
sendVarToJS('cmdInfo', jeedom::toHumanReadable(utils::o2a($cmd)));
$cmd_widgetDashboard = cmd::availableWidget('dashboard');
$cmd_widgetMobile = cmd::availableWidget('mobile');
?>
<div style="display: none;" id="md_displayCmdConfigure"></div>


<a class="btn btn-success btn-sm pull-right" id="bt_cmdConfigureSave"><i class="fa fa-check-circle"></i> {{Enregistrer}}</a>
<a class="btn btn-default pull-right btn-sm" id="bt_cmdConfigureSaveOn"><i class="fa fa-plus-circle"></i> {{Appliquer à}}</a>

<div role="tabpanel">

  <!-- Nav tabs -->
  <ul class="nav nav-tabs" role="tablist">
    <li role="presentation" class="active"><a href="#information" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-info-circle"></i> {{Informations}}</a></li>
    <li role="presentation"><a href="#configuration" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-wrench"></i> {{Configuration avancée}}</a></li>
    <li role="presentation"><a href="#display" aria-controls="messages" role="tab" data-toggle="tab"><i class="fa fa-desktop"></i> {{Affichage avancé}}</a></li>
  </ul>


  <div class="tab-content" id="div_displayCmdConfigure">
    <div role="tabpanel" class="tab-pane active" id="information">
      <br/>
      <legend><i class="fa fa-list-alt"></i> {{Général}}</legend>
      <div class="row">
        <div class="col-sm-6" >
          <form class="form-horizontal">
            <fieldset>
              <div class="form-group">
                <label class="col-xs-4 control-label">{{ID}}</label>
                <div class="col-xs-4">
                  <span class="cmdAttr label label-primary" data-l1key="id" style="font-size : 1em;"></span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-xs-4 control-label">{{Logical ID}}</label>
                <div class="col-xs-4">
                  <span class="cmdAttr label label-primary" data-l1key="logicalId" style="font-size : 1em;"></span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-xs-4 control-label">{{Nom}}</label>
                <div class="col-xs-4">
                  <span class="cmdAttr label label-primary" data-l1key="name" style="font-size : 1em;"></span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-xs-4 control-label">{{Type}}</label>
                <div class="col-xs-4">
                  <span class="cmdAttr label label-primary" data-l1key="type" style="font-size : 1em;"></span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-xs-4 control-label">{{Sous-type}}</label>
                <div class="col-xs-4">
                  <span class="cmdAttr label label-primary" data-l1key="subType" style="font-size : 1em;"></span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-xs-4 control-label">{{URL directe}}</label>
                <div class="col-xs-8">
                  <?php
echo '<a href="' . $cmd->getDirectUrlAccess() . '" target="_blank"><i class="fa fa-external-link"></i> URL</a>';
?>
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
                  <span class="cmdAttr label label-primary" data-l1key="unite" style="font-size : 1em;"></span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-xs-4 control-label">{{Commande déclenchant une mise à jour}}</label>
                <div class="col-xs-4">
                  <span class="cmdAttr label label-primary" data-l1key="value" style="font-size : 1em;"></span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-xs-4 control-label">{{Cache}}</label>
                <div class="col-xs-4">
                  <span class="cmdAttr label label-primary tooltips" data-l1key="cache" data-l2key="enable" title="{{Actif}}" style="font-size : 1em;"></span>
                  <span class="label label-default tooltips" title="{{Durée du cache}}" style="font-size : 1em;"><span class="cmdAttr" data-l1key="cache" data-l2key="lifetime"></span> {{seconde(s)}}</span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-xs-4 control-label">{{Evènement seulement}}</label>
                <div class="col-xs-4">
                  <span class="cmdAttr label label-primary" data-l1key="eventOnly" style="font-size : 1em;"></span>
                </div>
              </div>

              <div class="form-group">
                <label class="col-xs-4 control-label"></label>
                <div class="col-xs-4">
                  <input type="checkbox" data-label-text="{{Visible}}" class="cmdAttr bootstrapSwitch" data-l1key="isVisible" />
                </div>
              </div>

            </fieldset>
          </form>
        </div>
      </div>

      <legend><i class="fa fa-search"></i> {{Utilisé par}}</legend>
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
 </fieldset>
</form>
</div>
<div role="tabpanel" class="tab-pane" id="configuration">
  <br/>
  <form class="form-horizontal">
    <fieldset>

      <?php if ($cmd->getType() == 'info' && ($cmd->getSubType() == 'numeric' || $cmd->getSubType() == 'binary')) {
	?>
       <legend><i class="fa fa-table"></i> {{Calcul et arrondit}}</legend>
       <div class="form-group">
        <label class="col-lg-3 col-md-3 col-sm-4 col-xs-6 control-label">{{Formule de calcul (#value# pour la valeur)}}</label>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
          <input class="cmdAttr form-control" data-l1key="configuration" data-l2key="calculValueOffset" />
        </div>
      </div>
      <?php if ($cmd->getSubType() == 'numeric') {?>
      <div class="form-group">
        <label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">{{Arrondi (chiffre après la virgule)}}</label>
        <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
          <input class="cmdAttr form-control" data-l1key="configuration" data-l2key="historizeRound" />
        </div>
      </div>
      <?php }
	?>
      <?php }
?>
      <?php if ($cmd->getType() == 'action') {?>
      <legend><i class="fa fa-exclamation-triangle"></i> {{Restriction de l'action}}</legend>
      <div class="form-group">
        <label class="col-lg-3 col-md-3 col-sm-4 col-xs-6 control-label">{{Confirmer l'action}}</label>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
          <input type="checkbox" class="cmdAttr bootstrapSwitch" data-l1key="configuration" data-l2key="actionConfirm" />
        </div>
      </div>
      <div class="form-group">
        <label class="col-lg-3 col-md-3 col-sm-4 col-xs-6 control-label">{{Code d'accès}}</label>
        <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
          <input type="password" class="cmdAttr form-control" data-l1key="configuration" data-l2key="actionCodeAccess" autocomplete="off" />
        </div>
      </div>
      <?php }
?>
      <?php if ($cmd->getType() == 'info') {
	?>
       <legend><i class="fa fa-sign-out"></i> {{Action sur la valeur}}</legend>
       <div class="form-group">
        <label class="col-lg-3 col-md-3 col-sm-4 col-xs-6 control-label">{{Action sur valeur, si}}</label>
        <div class="col-lg-3 col-md-3 col-sm-3 col-xs-3">
          <select class="cmdAttr form-control" data-l1key="configuration" data-l2key="jeedomCheckCmdOperator" >
           <option value="==">{{égal}}</option>
           <option value=">">{{supérieur}}</option>
           <option value="<">{{inférieur}}</option>
           <option value="!=">{{différent}}</option>
         </select>
       </div>
       <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <input class="cmdAttr form-control" data-l1key="configuration" data-l2key="jeedomCheckCmdTest" />
      </div>
      <label class="col-lg-2 col-md-2 col-sm-2 col-xs-2 control-label">{{plus de (min)}}</label>
      <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
        <input type="number" class="cmdAttr form-control" data-l1key="configuration" data-l2key="jeedomCheckCmdTime" />
      </div>
    </div>
    <div class="form-group">
     <label class="col-lg-3 col-md-3 col-sm-4 col-xs-6 control-label">{{Action à faire}}</label>
     <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
      <select class="cmdAttr form-control" data-l1key="configuration" data-l2key="jeedomCheckCmdActionType" >
       <option value="cmd">{{Commande}}</option>
       <option value="scenario">{{Scénario}}</option>
     </select>
   </div>

   <div class="cmdCheckAction cmd">
    <div class="col-xs-6">
     <input class="cmdAttr form-control" data-l1key="configuration" data-l2key="jeedomCheckCmdCmdActionId" />
   </div>
   <div class="col-xs-1">
    <a class="btn btn-default btn-sm cursor" id="bt_checkCmdActionGetCmd"><i class="fa fa-list-alt"></i></a>
  </div>
</div>

<div class="cmdCheckAction scenario" style="display : none;">
 <div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
   <select class="cmdAttr form-control" data-l1key="configuration" data-l2key="jeedomCheckCmdScenarioActionMode" >
     <option value="start">{{Lancer}}</option>
     <option value="stop">{{Arrêter}}</option>
     <option value="activate">{{Activer}}</option>
     <option value="deactivate">{{Désactiver}}</option>
   </select>
 </div>
 <div class="col-lg-5 col-md-5 col-sm-5 col-xs-5">
   <select class="cmdAttr form-control" data-l1key="configuration" data-l2key="jeedomCheckCmdScenarioActionId" >
     <?php
foreach (scenario::all() as $scenario) {
		echo ' <option value="' . $scenario->getId() . '">' . $scenario->getHumanName() . '</option>';
	}

	?>
  </select>
</div>
</div>

</div>


<div class="cmdCheckAction cmd form-group">
  <label class="col-lg-3 col-md-3 col-sm-4 col-xs-6 control-label">{{Option}}</label>
  <div id="div_jeedomCheckCmdCmdOption" class="col-lg-9 col-md-9 col-sm-8 col-xs-6"></div>
</div>

<script>
  $('.cmdAttr[data-l2key=jeedomCheckCmdActionType]').on('change',function(){
    $('.cmdCheckAction').hide();
    $('.cmdCheckAction.'+$(this).value()).show();
  });


  $('#bt_checkCmdActionGetCmd').on('click', function() {
    jeedom.cmd.getSelectModal({cmd: {type: 'action'}}, function(result) {
      $('.cmdAttr[data-l2key=jeedomCheckCmdCmdActionId]').value(result.human);
      jeedom.cmd.displayActionOption($('.cmdAttr[data-l2key=jeedomCheckCmdCmdActionId]').value(), '', function (html) {
        $('#div_jeedomCheckCmdCmdOption').html(html);
      });
    });
  });

  $('.cmdAttr[data-l2key=jeedomCheckCmdCmdActionId]').on('change',function(){
   if(isset(cmdInfo.configuration.jeedomCheckCmdCmdActionOption)){
     jeedom.cmd.displayActionOption($('.cmdAttr[data-l2key=jeedomCheckCmdCmdActionId]').value(), cmdInfo.configuration.jeedomCheckCmdCmdActionOption, function (html) {
      $('#div_jeedomCheckCmdCmdOption').html(html);
    });
   }else{
    jeedom.cmd.displayActionOption($('.cmdAttr[data-l2key=jeedomCheckCmdCmdActionId]').value(), '', function (html) {
      $('#div_jeedomCheckCmdCmdOption').html(html);
    });
  }

});
</script>
<?php }
?>
<?php if ($cmd->getType() == 'info' && ($cmd->getSubType() == 'numeric' || $cmd->getSubType() == 'binary')) {?>
<legend><i class="fa fa-bar-chart-o"></i> {{Historique}}</legend>
<div class="form-group">
  <label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">{{Historiser}}</label>
  <div class="col-xs-1">
    <input type="checkbox" class="cmdAttr bootstrapSwitch" data-l1key="isHistorized" />
  </div>
</div>
<div class="form-group">
  <label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">{{Mode de lissage}}</label>
  <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
    <select class="form-control cmdAttr" data-l1key="configuration" data-l2key="historizeMode">
      <option value="avg">{{Moyenne}}</option>
      <option value="min">{{Minimum}}</option>
      <option value="max">{{Maximum}}</option>
      <option value="none">{{Aucun}}</option>
    </select>
  </div>
</div>

<div class="form-group">
  <label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">{{Purger l'historique si plus vieux de }}</label>
  <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
   <select class="form-control cmdAttr" data-l1key="configuration" data-l2key="historyPurge">
     <option value="">{{Jamais}}</option>
     <option value="-1 day">{{1 jour}}</option>
     <option value="-7 days">{{7 jours}}</option>
     <option value="-1 month">{{1 mois}}</option>
     <option value="-6 month">{{6 mois}}</option>
   </select>
 </div>
</div>
<?php }
?>
<?php if ($cmd->getType() == 'info') {?>
<legend><i class="fa fa-plus"></i> {{Autres}}</legend>
<div class="form-group">
  <label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">{{Ne pas répéter si la valeur ne change pas}}</label>
  <div class="col-xs-1">
    <input type="checkbox" class="cmdAttr bootstrapSwitch" data-l1key="configuration" data-l2key="doNotRepeatEvent" />
  </div>
</div>
<div class="form-group">
  <label class="col-lg-3 col-md-3 col-sm-4 col-xs-6 control-label">{{Push URL}}</label>
  <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
    <input class="cmdAttr form-control tooltips" data-l1key="configuration" data-l2key="jeedomPushUrl" title="{{Mettez ici l'URL à appeler lors d'une mise à jour de la valeur de la commande. Vous pouvez utiliser les tags suivants : #value# (valeur de la commande), #cmd_id# (id de la commande) et #cmd_name# (nom de la commande)}}"/>
  </div>
</div>
<?php }
?>
</fieldset>
</form>
</div>
<div role="tabpanel" class="tab-pane" id="display">
  <br/>
  <legend><i class="fa fa-tint"></i> {{Widget}}</legend>
  <table class="table table-bordered table-condensed">
    <thead>
      <tr>
        <th></th>
        <th>Dashboard et design</th>
        <th>Vue</th>
        <th>Mobile</th>
      </tr>
    </thead>
    <tbody>
      <tr>
        <td>{{Widget}}</td>
        <td colspan="2">
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
    <td>{{Visible}}</td>
    <td colspan="2"><input type="checkbox" class="cmdAttr bootstrapSwitch" data-on-color="danger" data-off-color="success" data-off-text="Oui" data-on-text="Non" data-size="small" data-l1key="display" data-l2key="hideOndashboard" /></td>
    <td><input type="checkbox" class="cmdAttr bootstrapSwitch" data-on-color="danger" data-off-color="success" data-off-text="Oui" data-on-text="Non" data-size="small" data-l1key="display" data-l2key="hideOnmobile" /></td>
   </tr>
   <tr>
    <td>{{Afficher le nom}}</td>
    <td><input type="checkbox" class="cmdAttr bootstrapSwitch" data-on-color="danger" data-off-color="success" data-off-text="Oui" data-on-text="Non" data-size="small" data-l1key="display" data-l2key="doNotShowNameOnDashboard" /></td>
    <td><input type="checkbox" class="cmdAttr bootstrapSwitch" data-on-color="danger" data-off-color="success" data-off-text="Oui" data-on-text="Non" data-size="small" data-l1key="display" data-l2key="doNotShowNameOnView" /></td>
    <td><input type="checkbox" class="cmdAttr bootstrapSwitch" data-on-color="danger" data-off-color="success" data-off-text="Oui" data-on-text="Non" data-size="small" data-l1key="display" data-l2key="doNotShowNameOnMobile" /></td>
  </tr>
  <tr>
    <td>{{Afficher les statistiques}}</td>
    <td><input type="checkbox" class="cmdAttr bootstrapSwitch" data-on-color="danger" data-off-color="success" data-off-text="Oui" data-on-text="Non" data-size="small" data-l1key="display" data-l2key="doNotShowStatOnDashboard" /></td>
    <td><input type="checkbox" class="cmdAttr bootstrapSwitch" data-on-color="danger" data-off-color="success" data-off-text="Oui" data-on-text="Non" data-size="small" data-l1key="display" data-l2key="doNotShowStatOnView" /></td>
    <td><input type="checkbox" class="cmdAttr bootstrapSwitch" data-on-color="danger" data-off-color="success" data-off-text="Oui" data-on-text="Non" data-size="small" data-l1key="display" data-l2key="doNotShowStatOnMobile" /></td>
  </tr>
</tbody>
</table>
<div class="form-group">
  <label class="col-lg-3 col-md-3 col-sm-4 col-xs-6 control-label">{{Retour à la ligne forcé avant le widget}}</label>
  <div class="col-xs-1">
    <input type="checkbox" class="cmdAttr bootstrapSwitch" data-size="small" data-l1key="display" data-l2key="forceReturnLineBefore" />
  </div>
  <label class="col-xs-2 control-label">{{après le widget}}</label>
  <div class="col-xs-1">
    <input type="checkbox" class="cmdAttr bootstrapSwitch" data-size="small" data-l1key="display" data-l2key="forceReturnLineAfter" />
  </div>
</div>
<br/><br/>
<legend><i class="fa fa-pencil-square-o"></i> {{Paramètres optionnels widget}} <a class="btn btn-success btn-xs pull-right" id="bt_addWidgetParameters"><i class="fa fa-plus-circle"></i> Ajouter</a></legend>
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


<div id="md_cmdConfigureSelectMultiple" title="{{Sélection multiple de commandes}}"></div>


<script>
  initTooltips();
  initCheckBox();


  $("#md_cmdConfigureSelectMultiple").dialog({
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
    var checkCmdParameter = $('#div_jeedomCheckCmdCmdOption').getValues('.expressionAttr')[0];
    if (isset(checkCmdParameter) && isset(checkCmdParameter.options)) {
      cmd.configuration.jeedomCheckCmdCmdActionOption = checkCmdParameter.options;
    }
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


  $('#bt_cmdConfigureSaveOn').on('click',function(){
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
    cmd = {display : cmd.display,template : cmd.template };
    var checkCmdParameter = $('#div_jeedomCheckCmdCmdOption').getValues('.expressionAttr')[0];
    if (isset(checkCmdParameter) && isset(checkCmdParameter.options)) {
      cmd.configuration.jeedomCheckCmdCmdActionOption = checkCmdParameter.options;
    }
    $('#md_cmdConfigureSelectMultiple').load('index.php?v=d&modal=cmd.selectMultiple&cmd_id='+cmdInfo.id, function() {
      initTableSorter();
      initCheckBox();
      $('#bt_cmdConfigureSelectMultipleAlertToogle').off().on('click', function () {
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
        $('#table_cmdConfigureSelectMultiple tbody tr').each(function () {
          if ($(this).is(':visible')) {
           $(this).find('.selectMultipleApplyCmd').bootstrapSwitch('destroy');
           $(this).find('.selectMultipleApplyCmd').prop('checked', state);
           $(this).find('.selectMultipleApplyCmd').bootstrapSwitch();
         }
       });
      });

      $('#bt_cmdConfigureSelectMultipleAlertApply').off().on('click', function () {
        $('#table_cmdConfigureSelectMultiple tbody tr').each(function () {
          if ($(this).find('.selectMultipleApplyCmd').prop('checked')) {
            cmd.id = $(this).attr('data-cmd_id');
            jeedom.cmd.save({
              cmd: cmd,
              error: function (error) {
                $('#md_cmdConfigureSelectMultipleAlert').showAlert({message: error.message, level: 'danger'});
              },
              success: function () {

              }
            });
          }
        });
        $('#md_cmdConfigureSelectMultipleAlert').showAlert({message: "{{Modification(s) appliquée(s) avec succès}}", level: 'success'});
      });
    }).dialog('open');
});
</script>
