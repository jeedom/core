<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

$scenarios = array();
$totalScenario = scenario::all();
$scenarios[-1] = scenario::all(null);
$scenarioListGroup = scenario::listGroup();
if (is_array($scenarioListGroup)) {
	foreach ($scenarioListGroup as $group) {
		$scenarios[$group['group']] = scenario::all($group['group']);
	}
}
?>
<style>
.expressions .sortable-placeholder{
  background-color: #94CA02;
}
</style>
<div style="position : fixed;height:100%;width:15px;top:50px;left:0px;z-index:998;background-color:#f6f6f6;" class="div_smallSideBar" id="bt_displayScenarioList"><i class="fa fa-arrow-circle-o-right" style="color : #b6b6b6;"></i></div>

<div class="row row-overflow">
  <div class="col-xs-2" id="div_listScenario" style="z-index:999">
    <div class="bs-sidebar nav nav-list bs-sidenav" >
      <a class="btn btn-default" id="bt_addScenario" style="width : 100%;margin-top : 5px;margin-bottom: 5px;"><i class="fa fa-plus-circle cursor" ></i> {{Nouveau scénario}}</a>
      <input id='in_treeSearch' class='form-control' placeholder="{{Rechercher}}" />
      <div id="div_tree">
        <ul id="ul_scenario" >
          <?php if (count($scenarios[-1]) > 0) {
	?>
           <li data-jstree='{"opened":true}'>
            <?php
echo '<a>Aucun - ' . count($scenarios[-1]) . ' scénario(s)</a>';
	echo '<ul>';
	foreach ($scenarios[-1] as $scenario) {
		echo '<li data-jstree=\'{"opened":true,"icon":"' . $scenario->getIcon(true) . '"}\'>';
		echo ' <a class="li_scenario" id="scenario' . $scenario->getId() . '" data-scenario_id="' . $scenario->getId() . '" title="{{Scénario ID :}} ' . $scenario->getId() . ' ' . $scenario->getDescription() . '">' . $scenario->getHumanName(false, true) . '</a>';
		echo '</li>';
	}
	?>
          </ul>
          <?php
}
foreach ($scenarioListGroup as $group) {
	if ($group['group'] != '') {
		echo '<li data-jstree=\'{"opened":true}\'>';
		echo '<a>' . $group['group'] . ' - ' . count($scenarios[$group['group']]) . ' scénario(s)</a>';
		echo '<ul>';
		foreach ($scenarios[$group['group']] as $scenario) {
			echo '<li data-jstree=\'{"opened":true,"icon":"' . $scenario->getIcon(true) . '"}\'>';
			echo ' <a class="li_scenario" id="scenario' . $scenario->getId() . '" data-scenario_id="' . $scenario->getId() . '" title="{{Scénario ID :}} ' . $scenario->getId() . ' ' . $scenario->getDescription() . '">' . $scenario->getHumanName(false, true) . '</a>';
			echo '</li>';
		}
		echo '</ul>';
		echo '</li>';
	}
}
?>
   </ul>
 </div>
</div>
</div>

<div id="scenarioThumbnailDisplay" class="col-xs-10" style="border-left: solid 1px #EEE; padding-left: 25px;">
 <div class="scenarioListContainer">
   <legend><i class="fa fa-cog"></i>  {{Gestion}}</legend>
   <div class="cursor" id="bt_addScenario2" style="text-align: center; background-color : #ffffff; height : 130px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 170px;margin-left : 10px;" >
    <i class="fa fa-plus-circle" style="font-size : 6em;color:#94ca02;"></i>
    <br>
    <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#94ca02">{{Ajouter}}</span>
  </div>
  <?php if (config::byKey('enableScenario') == 0) {?>
    <div class="cursor expertModeVisible" id="bt_changeAllScenarioState2" data-state="1" style="text-align: center; background-color : #ffffff; height : 130px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 170px;margin-left : 10px;" >
     <i class="fa fa-check" style="font-size : 6em;color:#5cb85c;"></i>
     <br>
     <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#5cb85c">{{Activer scénarios}}</span>
   </div>
   <?php } else {?>
   <div class="cursor expertModeVisible" id="bt_changeAllScenarioState2" data-state="0" style="text-align: center; background-color : #ffffff; height : 130px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 170px;margin-left : 10px;" >
     <i class="fa fa-times" style="font-size : 6em;color:#d9534f;"></i>
     <br>
     <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#d9534f">{{Désactiver scénarios}}</span>
   </div>
   <?php }
?>

   <div class="cursor expertModeVisible" id="bt_displayScenarioVariable2" style="text-align: center; background-color : #ffffff; height : 130px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 170px;margin-left : 10px;" >
    <i class="fa fa-eye" style="font-size : 6em;color:#337ab7;"></i>
    <br>
    <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#337ab7">{{Voir variables}}</span>
  </div>

  <div class="cursor expertModeVisible bt_showScenarioSummary" style="text-align: center; background-color : #ffffff; height : 130px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 170px;margin-left : 10px;" >
    <i class="fa fa-list" style="font-size : 6em;color:#337ab7;"></i>
    <br>
    <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#337ab7">{{Vue d'ensemble}}</span>
  </div>

  <div class="cursor expertModeVisible bt_showExpressionTest" style="text-align: center; background-color : #ffffff; height : 130px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 170px;margin-left : 10px;" >
    <i class="fa fa-check" style="font-size : 6em;color:#337ab7;"></i>
    <br>
    <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#337ab7">{{Testeur d'expression}}</span>
  </div>
</div>

<legend><i class="icon jeedom-clap_cinema"></i>  {{Mes scénarios}}</legend>
<?php
if (count($totalScenario) == 0) {
	echo "<br/><br/><br/><center><span style='color:#767676;font-size:1.2em;font-weight: bold;'>Vous n'avez encore aucun scénario. Cliquez sur ajouter un scénario pour commencer</span></center>";
} else {
	echo "<center><span style='color:#767676;font-size:1.2em;font-weight: bold;'>Vous avez " . count($totalScenario) . " scénario(s) dans " . count($scenarioListGroup) . " groupe(s)</span></center>";
	echo '<div class="panel-group" id="accordionScenar">';
	if (count($scenarios[-1]) > 0) {
		echo '<div class="panel panel-default">';
		echo '<div class="panel-heading">';
		echo '<h3 class="panel-title">';
		echo '<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionScenar" href="#config_none" style="text-decoration:none;">Aucun - ' . count($scenarios[-1]) . ' scénario(s)</a>';
		echo '</h3>';
		echo '</div>';
		echo '<div id="config_none" class="panel-collapse collapse">';
		echo '<div class="panel-body">';
		echo '<div class="scenarioListContainer">';
		foreach ($scenarios[-1] as $scenario) {
			$opacity = ($scenario->getIsActive()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
			echo '<div class="scenarioDisplayCard cursor" data-scenario_id="' . $scenario->getId() . '" title="' . $scenario->getDescription() . '" style="text-align: center; background-color : #ffffff; min-height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
			echo '<img src="core/img/scenario.png" height="90" width="85" />';
			echo "<br>";
			echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;">' . $scenario->getHumanName(true, true, true, true) . '</span>';
			echo '</div>';
		}
		echo '</div>';
		echo '</div>';
		echo '</div>';
		echo '</div>';
	}
	$i = 0;
	foreach ($scenarioListGroup as $group) {
		if ($group['group'] == '') {
			continue;
		}
		echo '<div class="panel panel-default">';
		echo '<div class="panel-heading">';
		echo '<h3 class="panel-title">';
		echo '<a class="accordion-toggle" data-toggle="collapse" data-parent="#accordionScenar" href="#config_' . $i . '" style="text-decoration:none;">' . $group['group'] . ' - ' . count($scenarios[$group['group']]) . ' scénario(s)</a>';
		echo '</h3>';
		echo '</div>';
		echo '<div id="config_' . $i . '" class="panel-collapse collapse">';
		echo '<div class="panel-body">';
		echo '<div class="scenarioListContainer">';
		foreach ($scenarios[$group['group']] as $scenario) {
			$opacity = ($scenario->getIsActive()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
			echo '<div class="scenarioDisplayCard cursor" data-scenario_id="' . $scenario->getId() . '" title="' . $scenario->getDescription() . '" style="text-align: center; background-color : #ffffff; min-height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
			echo '<img src="core/img/scenario.png" height="90" width="85" />';
			echo "<br>";
			echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;">' . $scenario->getHumanName(true, true, true, true) . '</span>';
			echo '</div>';
		}
		echo '</div>';
		echo '</div>';
		echo '</div>';
		echo '</div>';
		$i += 1;
	}
	echo '</div>';
}
?>
</div>

<div id="div_editScenario" class="col-xs-10" style="border-left: solid 1px #EEE; padding-left: 25px;display: none;" >
 <a class="btn btn-default btn-sm pull-right" id="bt_graphScenario"><i class="fa fa-object-group"></i> {{Liens}}</a>
 <a class="btn btn-default btn-sm pull-right" id="bt_copyScenario"><i class="fa fa-copy"></i> {{Dupliquer}}</a>
 <a class="btn btn-default btn-sm pull-right" id="bt_logScenario"><i class="fa fa-file-text-o"></i> {{Log}}</a>
 <a class="btn btn-default btn-sm pull-right" id="bt_exportScenario"><i class="fa fa fa-share"></i> {{Exporter}}</a>
 <a class="btn btn-danger btn-sm pull-right" id="bt_stopScenario"><i class="fa fa-stop"></i> {{Arrêter}}</a>
 <a class="btn btn-default btn-sm pull-right" id="bt_templateScenario"><i class="fa fa-cubes"></i> {{Template}}</a>
 <a class="btn btn-success btn-sm pull-right" id="bt_saveScenario2"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
 <a class="btn btn-danger btn-sm pull-right" id="bt_delScenario2"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
 <a class="btn btn-warning btn-sm pull-right" id="bt_testScenario2" title='{{Veuillez sauvegarder avant de tester. Ceci peut ne pas aboutir.}}'><i class="fa fa-gamepad"></i> {{Executer}}</a>
 <a class="btn btn-primary btn-sm pull-right bt_showExpressionTest"><i class="fa fa-check"></i> {{Expression}}</a>
 <span id="span_ongoing" class="label pull-right" style="font-size : 1em;position:relative;top:5px;"></span>

 <ul class="nav nav-tabs" role="tablist">
   <li role="presentation"><a class="cursor" aria-controls="home" role="tab" id="bt_scenarioThumbnailDisplay"><i class="fa fa-arrow-circle-left"></i></a></li>
   <li role="presentation" class="active"><a href="#generaltab" aria-controls="home" role="tab" data-toggle="tab"><i class="fa fa-tachometer"></i> {{Général}} (ID : <span class="scenarioAttr" data-l1key="id" ></span>)</a></li>
   <li role="presentation"><a id="bt_scenarioTab" href="#scenariotab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fa fa-filter"></i> {{Scénario}}</a></li>
 </ul>
</ul>
<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
  <div role="tabpanel" class="tab-pane active" id="generaltab">
    <br/>
    <div class="row">
      <div class="col-sm-6">
        <form class="form-horizontal">
          <fieldset>
            <div class="form-group">
              <label class="col-xs-5 control-label" >{{Nom du scénario}}</label>
              <div class="col-xs-6">
                <input class="form-control scenarioAttr" data-l1key="name" type="text" placeholder="{{Nom du scénario}}"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-xs-5 control-label" >{{Nom à afficher}}</label>
              <div class="col-xs-6">
                <input class="form-control scenarioAttr" title="{{Ne rien mettre pour laisser le nom par défaut}}" data-l1key="display" data-l2key="name" type="text" placeholder="{{Nom à afficher}}"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-xs-5 control-label" >{{Groupe}}</label>
              <div class="col-xs-6">
                <input class="form-control scenarioAttr" data-l1key="group" type="text" placeholder="{{Groupe du scénario}}"/>
              </div>
            </div>
            <div class="form-group">
              <label class="col-xs-5 control-label"></label>
              <label>
                {{Actif}} <input type="checkbox" class="scenarioAttr" data-l1key="isActive">
              </label>
              <label>
               {{Visible}} <input type="checkbox" class="scenarioAttr" data-l1key="isVisible">
             </label>
           </div>
           <div class="form-group">
            <label class="col-xs-5 control-label" >{{Objet parent}}</label>
            <div class="col-xs-6">
              <select class="scenarioAttr form-control" data-l1key="object_id">
                <option value="">{{Aucun}}</option>
                <?php
foreach (object::all() as $object) {
	echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
}
?>
             </select>
           </div>
         </div>
         <div class="form-group expertModeVisible">
          <label class="col-xs-5 control-label">{{Timeout secondes (0 = illimité)}}</label>
          <div class="col-xs-6">
            <input class="form-control scenarioAttr" data-l1key="timeout">
          </div>
        </div>
        <div class="form-group expertModeVisible">
          <label class="col-xs-5 control-label">{{Multi lancement}}</label>
          <div class="col-xs-1">
            <input type="checkbox" class="scenarioAttr" data-l1key="configuration" data-l2key="allowMultiInstance" title="{{Le scénario pourra tourné plusieurs fois en même temps}}">
          </div>
           <label class="col-xs-4 control-label">{{Mode synchrone}}</label>
          <div class="col-xs-1">
            <input type="checkbox" class="scenarioAttr" data-l1key="configuration" data-l2key="syncmode" title="{{Le scénario est mode synchrone, attention peux rendre le système instable}}">
          </div>
        </div>
        <div class="form-group expertModeVisible">
          <label class="col-xs-5 control-label">{{Log}}</label>
          <div class="col-xs-6">
            <select class="scenarioAttr form-control" data-l1key="configuration" data-l2key="logmode">
              <option value="default">{{Défaut}}</option>
              <option value="none">{{Aucun}}</option>
              <option value="realtime">{{Temps réel}}</option>
            </select>
          </div>
        </div>
        <div class="form-group expertModeVisible">
          <label class="col-xs-5 control-label">{{Suivre dans la timeline}}</label>
          <div class="col-xs-1">
            <input type="checkbox" class="scenarioAttr" data-l1key="configuration" data-l2key="timeline::enable" title="{{Les executions du scénario pourront etre vu dans la timeline}}">
          </div>
        </div>
      </fieldset>
    </form>
  </div>
  <div class="col-sm-6">
    <form class="form-horizontal">
     <div class="form-group">
     <div class="col-md-12">
        <textarea class="form-control scenarioAttr ta_autosize" data-l1key="description" placeholder="Description"></textarea>
      </div>
    </div>
    <div class="form-group">
      <label class="col-sm-3 col-xs-6 control-label" >{{Mode du scénario}}</label>
      <div class="col-sm-9 col-xs-6">
        <div class="input-group">
          <select class="form-control scenarioAttr input-sm" data-l1key="mode">
            <option value="provoke">{{Provoqué}}</option>
            <option value="schedule">{{Programmé}}</option>
            <option value="all">{{Les deux}}</option>
          </select>
          <span class="input-group-btn">
            <a class="btn btn-default btn-sm" id="bt_addTrigger"><i class="fa fa-plus-square"></i> {{Déclencheur}}</a>
            <a class="btn btn-default btn-sm" id="bt_addSchedule"><i class="fa fa-plus-square"></i> {{Programmation}}</a>
          </span>
        </div>
      </div>
    </div>
    <div class="scheduleDisplay" style="display: none;">
      <div class="form-group">
        <label class="col-xs-3 control-label" >{{Précédent}}</label>
        <div class="col-xs-3" ><span class="scenarioAttr label label-primary" data-l1key="forecast" data-l2key="prevDate" data-l3key="date"></span></div>
        <label class="col-xs-3 control-label" >{{Prochain}}</label>
        <div class="col-xs-3"><span class="scenarioAttr label label-success" data-l1key="forecast" data-l2key="nextDate" data-l3key="date"></span></div>
      </div>
      <div class="scheduleMode"></div>
    </div>
    <div class="provokeMode provokeDisplay" style="display: none;">

    </div>
  </form>
</div>
</div>
</div>
<div role="tabpanel" class="tab-pane" id="scenariotab">
  <br/>
  <div id="div_scenarioElement" class="element" style="padding-bottom: 70px;"></div>
</div>
</div>

</div>
</div>

<div class="modal fade" id="md_copyScenario">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button class="close" data-dismiss="modal">×</button>
        <h3>{{Dupliquer le scénario}}</h3>
      </div>
      <div class="modal-body">
        <div style="display: none;" id="div_copyScenarioAlert"></div>
        <center>
          <input class="form-control" type="text"  id="in_copyScenarioName" size="16" placeholder="{{Nom du scénario}}"/><br/><br/>
        </center>
      </div>
      <div class="modal-footer">
        <a class="btn btn-danger" data-dismiss="modal"><i class="fa fa-minus-circle"></i> {{Annuler}}</a>
        <a class="btn btn-success" id="bt_copyScenarioSave"><i class="fa fa-check-circle"></i> {{Enregistrer}}</a>
      </div>
    </div>
  </div>
</div>

<div class="modal fade" id="md_addElement">
  <div class="modal-dialog">
    <div class="modal-content">
      <div class="modal-header">
        <button class="close" data-dismiss="modal">×</button>
        <h3>{{Ajouter élément}}</h3>
      </div>
      <div class="modal-body">
        <center>
          <select id="in_addElementType" class="form-control">
            <option value="if">{{Si/Alors/Sinon}}</option>
            <option value="action">{{Action}}</option>
            <option value="for">{{Boucle}}</option>
            <option value="in">{{Dans}}</option>
            <option value="at">{{A}}</option>
            <option value="code">{{Code}}</option>
            <option value="comment">{{Commentaire}}</option>
          </select>
        </center>
        <br/>
        <div class="alert alert-info addElementTypeDescription if">
          Permet de faire des conditions dans votre scénario. Par exemple : Si mon détecteur d’ouverture de porte se déclenche Alors allumer la lumière.
        </div>

        <div class="alert alert-info addElementTypeDescription action" style="display:none;">
          Permet de lancer une action, sur un de vos modules, scénarios ou autre. Par exemple : passer votre sirène sur ON.
        </div>

        <div class="alert alert-info addElementTypeDescription for" style="display:none;">
          Une boucle permet de réaliser une action de façon répétée un certain nombre de fois. Par exemple : permet de répéter une action de 1 à X, c’est-à-dire X fois.
        </div>

        <div class="alert alert-info addElementTypeDescription in" style="display:none;">
         Permet de faire une action dans X min. Par exemple : Dans 5 min éteindre la lumière.
       </div>

       <div class="alert alert-info addElementTypeDescription at" style="display:none;">
         A un temps précis, cet élément permet de lancer une action. Par exemple : A 9h30 ouvrir les volets.
       </div>

       <div class="alert alert-info addElementTypeDescription code" style="display:none;">
        Cet élément permet de rajouter dans votre scénario de la programmation à l’aide d’un code, PHP/Shell etc...
      </div>

      <div class="alert alert-info addElementTypeDescription comment" style="display:none;">
        Permet de commenter votre scénario.
      </div>

    </div>
    <div class="modal-footer">
      <a class="btn btn-danger" data-dismiss="modal"><i class="fa fa-minus-circle"></i> {{Annuler}}</a>
      <a class="btn btn-success" id="bt_addElementSave"><i class="fa fa-check-circle"></i> {{Enregistrer}}</a>
    </div>
  </div>
</div>
</div>

<?php
include_file('desktop', 'scenario', 'js');
include_file('3rdparty', 'jquery.sew/jquery.caretposition', 'js');
include_file('3rdparty', 'jquery.sew/jquery.sew.min', 'js');
?>