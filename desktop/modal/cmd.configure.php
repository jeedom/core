    <?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$cmd = cmd::byId(init('cmd_id'));
if (!is_object($cmd)) {
	throw new Exception('Commande non trouvé : ' . init('cmd_id'));
}
$cmdInfo = jeedom::toHumanReadable(utils::o2a($cmd));
foreach (array('dashboard', 'mobile', 'dview', 'mview', 'dplan') as $value) {
	if (!isset($cmdInfo['html'][$value]) || $cmdInfo['html'][$value] == '') {
		$cmdInfo['html'][$value] = $cmd->getWidgetTemplateCode($value);
	}
}
sendVarToJS('cmdInfo', $cmdInfo);
$cmd_widgetDashboard = cmd::availableWidget('dashboard');
$cmd_widgetMobile = cmd::availableWidget('mobile');
?>
  <div style="display: none;" id="md_displayCmdConfigure"></div>


  <a class="btn btn-success btn-sm pull-right" id="bt_cmdConfigureSave"><i class="fa fa-check-circle"></i> {{Enregistrer}}</a>
  <a class="btn btn-default pull-right btn-sm" id="bt_cmdConfigureSaveOn"><i class="fa fa-plus-circle"></i> {{Appliquer à}}</a>

  <div role="tabpanel">

    <!-- Nav tabs -->
    <ul class="nav nav-tabs" role="tablist">
      <li role="presentation" class="active"><a href="#cmd_information" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-info-circle"></i> {{Informations}}</a></li>
      <li role="presentation"><a href="#cmd_configuration" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-wrench"></i> {{Configuration avancée}}</a></li>
      <?php if ($cmd->widgetPossibility('custom')) {
	?>
       <li role="presentation"><a href="#cmd_display" aria-controls="messages" role="tab" data-toggle="tab"><i class="fa fa-desktop"></i> {{Affichage avancé}}</a></li>
       <?php }
?>
       <?php if ($cmd->widgetPossibility('custom::htmlCode')) {
	?>
         <li role="presentation"><a href="#cmd_html" aria-controls="messages" role="tab" data-toggle="tab"><i class="fa fa-code-fork"></i> {{Code du widget}}</a></li>
         <?php }
?>
       </ul>


       <div class="tab-content" id="div_displayCmdConfigure">
        <div role="tabpanel" class="tab-pane active" id="cmd_information">
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
   <div role="tabpanel" class="tab-pane" id="cmd_configuration">
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
        <legend><i class="fa fa-building"></i> {{Type générique}}</legend>
        <div class="form-group">
          <label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">{{Valeur}}</label>
          <div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
            <select class="cmdAttr form-control" data-l1key="display" data-l2key="generic_type">
              <option value="">{{Aucun}}</option>
              <?php
foreach (jeedom::getConfiguration('cmd::generic_type') as $key => $value) {
	echo '<option value="' . $key . '">' . __($value['name'], 'common') . '</option>';
}
?>
           </select>
         </div>
       </div>

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
      <label class="col-lg-3 col-md-3 col-sm-4 col-xs-6 control-label">{{Action}}</label>
      <div class="col-lg-6 col-md-6 col-sm-6 col-xs-6">
        <a class="btn btn-success" id="bt_addActionCheckCmd"><i class="fa fa-plus-circle"></i> {{Ajouter}}</a>
      </div>
    </div>
    <div id="div_actionCheckCmd"></div>

    <script type="text/javascript">
      $("#div_actionCheckCmd").sortable({axis: "y", cursor: "move", items: ".actionCheckCmd", placeholder: "ui-state-highlight", tolerance: "intersect", forcePlaceholderSize: true});

      $('#bt_addActionCheckCmd').off('click').on('click',function(){
        addActionCheckCmd({}, 'actionCheckCmd','{{Action}}');
      });

      $("body").undelegate('.bt_removeAction', 'click').delegate('.bt_removeAction', 'click', function () {
        var type = $(this).attr('data-type');
        $(this).closest('.' + type).remove();
      });

      $("body").undelegate(".listCmd", 'click').delegate(".listCmd", 'click', function () {
        var type = $(this).attr('data-type');
        var el = $(this).closest('.' + type).find('.expressionAttr[data-l1key=cmd]');
        jeedom.cmd.getSelectModal({}, function (result) {
          el.value(result.human);
          jeedom.cmd.displayActionOption(el.value(), '', function (html) {
            el.closest('.' + type).find('.actionOptions').html(html);
          });
        });
      });

      $('body').undelegate(".cmdAction.expressionAttr[data-l1key=cmd]", 'focusout').delegate('.cmdAction.expressionAttr[data-l1key=cmd]', 'focusout', function (event) {
        var type = $(this).attr('data-type')
        var expression = $(this).closest('.' + type).getValues('.expressionAttr');
        var el = $(this);
        jeedom.cmd.displayActionOption($(this).value(), init(expression[0].options), function (html) {
          el.closest('.' + type).find('.actionOptions').html(html);
        })
      });

      function addActionCheckCmd(_action, _type, _name) {
        if (!isset(_action)) {
          _action = {};
        }
        if (!isset(_action.options)) {
          _action.options = {};
        }
        var div = '<div class="' + _type + '">';
        div += '<div class="form-group ">';
        div += '<div class="col-sm-5">';
        div += '<div class="input-group">';
        div += '<span class="input-group-btn">';
        div += '<a class="btn btn-default btn-sm bt_removeAction" data-type="' + _type + '"><i class="fa fa-minus-circle"></i></a>';
        div += '</span>';
        div += '<input class="expressionAttr form-control input-sm cmdAction" data-l1key="cmd" data-type="' + _type + '" />';
        div += '<span class="input-group-btn">';
        div += '<a class="btn btn-default btn-sm listCmd" data-type="' + _type + '"><i class="fa fa-list-alt"></i></a>';
        div += '</span>';
        div += '</div>';
        div += '</div>';
        div += '<div class="col-sm-7 actionOptions">';
        div += jeedom.cmd.displayActionOption(init(_action.cmd, ''), _action.options);
        div += '</div>';
        $('#div_' + _type).append(div);
        $('#div_' + _type + ' .' + _type + ':last').setValues(_action, '.expressionAttr');
      }
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
    <label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">{{Ignorer évènement si la valeur ne change pas}}</label>
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
<?php if ($cmd->widgetPossibility('custom::htmlCode')) {
	?>
  <div role="tabpanel" class="tab-pane" id="cmd_html">
    <br/>
    <div class="form-group">
      <label class="col-lg-3 col-md-3 col-sm-3 col-xs-6 control-label">{{Activer la personalisation du widget}}</label>
      <div class="col-xs-2">
        <input type="checkbox" class="cmdAttr bootstrapSwitch" data-l1key="html" data-l2key="enable" />
      </div>
      <div class="col-xs-3">
        <a class="btn btn-warning" id="bt_reinitHtmlCode"><i class="fa fa-times"></i> {{Reinitialiser la personalisation}}</a>
      </div>
    </div>
    <legend><i class="fa fa-code"></i> {{Code}}</legend>
    <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
      <div class="panel panel-default">
        <div class="panel-heading" role="tab" id="headingOne">
          <h4 class="panel-title">
            <a role="button" id="bt_codeDashboard" data-toggle="collapse" data-parent="#accordion" href="#collapseOne" aria-expanded="false" aria-controls="collapseOne">
             {{Dashboard}}
           </a>
         </h4>
       </div>
       <div id="collapseOne" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingOne">
        <div class="panel-body">
          <textarea class="cmdAttr" id="ta_codeDashboard" data-l1key="html" data-l2key="dashboard" style="width: 100%;height: 350px"></textarea>
        </div>
      </div>
    </div>
    <div class="panel panel-default">
      <div class="panel-heading" role="tab" id="headingFour">
        <h4 class="panel-title">
          <a class="collapsed" id="bt_codeDview" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFour" aria-expanded="false" aria-controls="collapseFour">
           {{Vue dashboard}}
         </a>
       </h4>
     </div>
     <div id="collapseFour" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
      <div class="panel-body">
        <textarea class="cmdAttr" id="ta_codeDview" data-l1key="html" data-l2key="dview" style="width: 100%;height: 350px"></textarea>
      </div>
    </div>
  </div>
  <div class="panel panel-default">
    <div class="panel-heading" role="tab" id="headingThree">
      <h4 class="panel-title">
        <a class="collapsed" id="bt_codeDplan" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseThree" aria-expanded="false" aria-controls="collapseThree">
         {{Design}}
       </a>
     </h4>
   </div>
   <div id="collapseThree" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
    <div class="panel-body">
      <textarea class="cmdAttr" id="ta_codeDplan" data-l1key="html" data-l2key="dplan" style="width: 100%;height: 350px"></textarea>
    </div>
  </div>
</div>
<div class="panel panel-default">
  <div class="panel-heading" role="tab" id="headingTwo">
    <h4 class="panel-title">
      <a class="collapsed" id="bt_codeMobile" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseTwo" aria-expanded="false" aria-controls="collapseTwo">
        {{Mobile}}
      </a>
    </h4>
  </div>
  <div id="collapseTwo" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingTwo">
    <div class="panel-body">
      <textarea class="cmdAttr" id="ta_codeMobile" data-l1key="html" data-l2key="mobile" style="width: 100%;height: 350px"></textarea>
    </div>
  </div>
</div>
<div class="panel panel-default">
  <div class="panel-heading" role="tab" id="headingFive">
    <h4 class="panel-title">
      <a class="collapsed" id="bt_codeMview" role="button" data-toggle="collapse" data-parent="#accordion" href="#collapseFive" aria-expanded="false" aria-controls="collapseFive">
       {{Vue mobile}}
     </a>
   </h4>
 </div>
 <div id="collapseFive" class="panel-collapse collapse" role="tabpanel" aria-labelledby="headingThree">
  <div class="panel-body">
    <textarea class="cmdAttr" id="ta_codeMview" data-l1key="html" data-l2key="mview" style="width: 100%;height: 350px"></textarea>
  </div>
</div>
</div>

</div>
</div>
<?php }
?>

<?php if ($cmd->widgetPossibility('custom')) {
	?>
  <div role="tabpanel" class="tab-pane" id="cmd_display">
    <br/>
    <legend><i class="fa fa-tint"></i> {{Widget}}</legend>
    <table class="table table-bordered table-condensed">
      <thead>
        <tr>
          <th></th>
                  <?php
foreach (jeedom::getConfiguration('eqLogic:displayType') as $key => $value) {
		echo '<th>{{' . $value['name'] . '}}</th>';
	}
	?>
        </tr>
      </thead>
      <tbody>
       <?php if ($cmd->widgetPossibility('custom::widget')) {
		?>
        <tr>
          <td>{{Widget}}</td>
          <td colspan="3">
           <?php if ($cmd->widgetPossibility('custom::widget::dashboard')) {
			?>
             <select class="form-control cmdAttr" data-l1key="template" data-l2key="dashboard">
              <?php
foreach ($cmd_widgetDashboard[$cmd->getType()][$cmd->getSubType()] as $widget) {
				echo '<option value="' . $widget['name'] . '">' . $widget['name'] . ' (' . $widget['location'] . ')</option>';
			}
			?>
            </select>
            <?php }
		?>
          </td>
          <td>
           <?php if ($cmd->widgetPossibility('custom::widget::mobile')) {
			?>
             <select class="form-control cmdAttr" data-l1key="template" data-l2key="mobile">
              <?php
foreach ($cmd_widgetMobile[$cmd->getType()][$cmd->getSubType()] as $widget) {
				echo '<option value="' . $widget['name'] . '">' . $widget['name'] . ' (' . $widget['location'] . ')</option>';
			}
			?>
            </select>
            <?php }
		?>
          </td>
        </tr>
        <?php }
	?>
        <?php if ($cmd->widgetPossibility('custom::visibility')) {
		?>
          <tr>
            <td>{{Visible}}</td>
 <?php
foreach (jeedom::getConfiguration('eqLogic:displayType') as $key => $value) {
			echo '<td>';
			if ($cmd->widgetPossibility('custom::visibility::' . $key)) {
				echo '<input type="checkbox" class="cmdAttr bootstrapSwitch" data-size="small" data-l1key="display" data-l2key="showOn' . $key . '" checked />';
			}
			echo '</td>';
		}
		?>
           </tr>
           <?php }
	?>
           <?php if ($cmd->widgetPossibility('custom::displayName')) {
		?>
            <tr>
              <td>{{Afficher le nom}}</td>
              <?php
foreach (jeedom::getConfiguration('eqLogic:displayType') as $key => $value) {
			echo '<td>';
			if ($cmd->widgetPossibility('custom::displayName::' . $key)) {
				echo '<input type="checkbox" class="cmdAttr bootstrapSwitch" data-size="small" data-l1key="display" data-l2key="showNameOn' . $key . '" checked />';
			}
			echo '</td>';
		}
		?>
            </tr>
            <?php }
	?>
            <?php if (config::byKey('displayStatsWidget') == 1 && $cmd->getSubType() != 'string' && $cmd->widgetPossibility('custom::displayStats')) {

		?>
              <tr>
                <td>{{Afficher les statistiques}}</td>
              <?php
foreach (jeedom::getConfiguration('eqLogic:displayType') as $key => $value) {
			echo '<td>';
			if ($cmd->widgetPossibility('custom::displayStats::' . $key)) {
				echo '<input type="checkbox" class="cmdAttr bootstrapSwitch" data-size="small" data-l1key="display" data-l2key="showStatsOn' . $key . '" checked />';
			}
			echo '</td>';
		}
		?>
              </tr>
              <?php
}
	?>
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
           <?php if ($cmd->widgetPossibility('custom::optionalParameters')) {
		?>
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
                 <?php
}
	?>
       </div>
       <?php }
?>
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
      if(isset(cmdInfo.configuration.actionCheckCmd) && $.isArray(cmdInfo.configuration.actionCheckCmd) && cmdInfo.configuration.actionCheckCmd.length != null){
        for(var i in cmdInfo.configuration.actionCheckCmd){
          addActionCheckCmd(cmdInfo.configuration.actionCheckCmd[i], 'actionCheckCmd','{{Action}}');
        }
      }

      editorCodeDview = null;
      editorCodeDplan = null;
      editorCodeMobile = null;
      editorCodeMview = null;
      editorCodeDashboard = null;

      $('#bt_codeDashboard').one('click',function(){
        setTimeout(function () {
          editorCodeDashboard = CodeMirror.fromTextArea(document.getElementById("ta_codeDashboard"), {
            lineNumbers: true,
            mode: "text/javascript",
            matchBrackets: true,
            viewportMargin: Infinity
          });
        }, 1);
      });

      $('#bt_codeDview').one('click',function(){
        setTimeout(function () {
          editorCodeDview = CodeMirror.fromTextArea(document.getElementById("ta_codeDview"), {
            lineNumbers: true,
            mode: "text/javascript",
            matchBrackets: true,
            viewportMargin: Infinity
          });
        }, 1);
      });

      $('#bt_codeDplan').one('click',function(){
        setTimeout(function () {
          editorCodeDplan = CodeMirror.fromTextArea(document.getElementById("ta_codeDplan"), {
            lineNumbers: true,
            mode: "text/javascript",
            matchBrackets: true,
            viewportMargin: Infinity
          });
        }, 1);
      });

      $('#bt_codeMobile').one('click',function(){
        setTimeout(function () {
          editorCodeMobile = CodeMirror.fromTextArea(document.getElementById("ta_codeMobile"), {
            lineNumbers: true,
            mode: "text/javascript",
            matchBrackets: true,
            viewportMargin: Infinity
          });
        }, 1);
      });

      $('#bt_codeMview').one('click',function(){
        setTimeout(function () {
          editorCodeMview = CodeMirror.fromTextArea(document.getElementById("ta_codeMview"), {
            lineNumbers: true,
            mode: "text/javascript",
            matchBrackets: true,
            viewportMargin: Infinity
          });
        }, 1);
      });

      $('#bt_reinitHtmlCode').on('click',function(){
        $('#ta_codeDashboard').value('');
        $('#ta_codeDview').value('');
        $('#ta_codeDplan').value('');
        $('#ta_codeMobile').value('');
        $('#ta_codeMview').value('');
        if(editorCodeDashboard != null){
         editorCodeDashboard.setValue('');
       }
       if(editorCodeDview != null){
         editorCodeDview.setValue('');
       }
       if(editorCodeDplan != null){
         editorCodeDplan.setValue('');
       }
       if(editorCodeMobile != null){
        editorCodeMobile.setValue('');
      }
      if(editorCodeMview != null){
       editorCodeMview.setValue('');
     }
     $('#md_displayCmdConfigure').showAlert({message: '{{Opération faite, n\'oubliez pas de sauvegarder}}', level: 'success'});
   });


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
        cmd.configuration.actionCheckCmd = {};
        cmd.configuration.actionCheckCmd = $('#div_actionCheckCmd .actionCheckCmd').getValues('.expressionAttr');

        if(editorCodeDashboard != null){
          cmd.html.dashboard = editorCodeDashboard.getValue();
        }
        if(editorCodeDview != null){
          cmd.html.dview = editorCodeDview.getValue();
        }
        if(editorCodeDplan != null){
          cmd.html.dplan = editorCodeDplan.getValue();
        }
        if(editorCodeMobile != null){
          cmd.html.mobile = editorCodeMobile.getValue();
        }
        if(editorCodeMview != null){
          cmd.html.mview = editorCodeMview.getValue();
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
