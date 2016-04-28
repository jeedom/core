<?php
if (!hasRight('scenarioview', true)) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

$scenarios = array();
$scenarios[-1] = scenario::all(null);
$scenarioListGroup = scenario::listGroup();
if (is_array($scenarioListGroup)) {
	foreach ($scenarioListGroup as $group) {
		$scenarios[$group['group']] = scenario::all($group['group']);
	}
}
?>

<div style="position : fixed;height:100%;width:15px;top:50px;left:0px;z-index:998;background-color:#f6f6f6;" class="div_smallSideBar" id="bt_displayScenarioList"><i class="fa fa-arrow-circle-o-right" style="color : #b6b6b6;"></i></div>

<div class="row row-overflow">
    <div class="col-lg-2 col-md-3 col-sm-4" id="div_listScenario" style="z-index:999">
        <div class="bs-sidebar nav nav-list bs-sidenav">
            <a class="btn btn-success pull-right form-control" id="bt_switchToExpertMode" href="index.php?v=d&p=scenario" style="text-shadow: none;"><i class="fa fa-toggle-off"></i> {{Interface simple}}</a>
            <center>
                <div class="col-xs-6">
                    <?php
if (config::byKey('enableScenario') == 0) {
	echo '<a class="btn btn-sm btn-success expertModeVisible" id="bt_changeAllScenarioState" data-state="1" style="margin-top : 3px;text-shadow: none;" ><i class="fa fa-check"></i> {{Act. scénarios}}</a>';
} else {
	echo '<a class="btn btn-sm btn-danger expertModeVisible" id="bt_changeAllScenarioState" data-state="0" style="margin-top : 3px;text-shadow: none;" ><i class="fa fa-times"></i> {{Désac. scénarios}}</a>';
}
?>
               </div>
               <div class="col-xs-6">
                <a class="btn btn-default btn-sm tooltips expertModeVisible" id="bt_displayScenarioVariable" title="{{Voir toutes les variables de scénario}}" style="margin-top : 3px;text-shadow: none"><i class="fa fa fa-eye"></i> {{Voir variables}}</a>
            </div>
        </center>
        <a class="btn btn-default" id="bt_addScenario" style="width : 100%;margin-top : 5px;margin-bottom: 5px;"><i class="fa fa-plus-circle cursor" ></i> {{Nouveau scénario}}</a>
        <input id='in_treeSearch' class='form-control' placeholder="{{Rechercher}}" />
        <div id="div_tree">
            <ul id="ul_scenario" >
                <li data-jstree='{"opened":true}'>
                    <?php if (count($scenarios[-1]) > 0) {
	?>
                       <a>Aucune</a>
                       <ul>
                        <?php
foreach ($scenarios[-1] as $scenario) {
		echo '<li data-jstree=\'{"opened":true,"icon":"' . $scenario->getIcon(true) . '"}\'>';
		echo ' <a class="li_scenario" id="scenario' . $scenario->getId() . '" data-type="' . $scenario->getType() . '" title="{{Scénario ID :}} ' . $scenario->getId() . '" data-scenario_id="' . $scenario->getId() . '" >' . $scenario->getHumanName(false, true) . '</a>';
		echo '</li>';
	}
	?>
                  </ul>
                  <?php }
?>
                  <?php
if (is_array($scenarioListGroup)) {
	foreach ($scenarioListGroup as $group) {
		if ($group['group'] != '' && count($scenarios[$group['group']]) > 0) {
			echo '<li data-jstree=\'{"opened":true}\'>';
			echo '<a>' . $group['group'] . '</a>';
			echo '<ul>';
			foreach ($scenarios[$group['group']] as $scenario) {
				echo '<li data-jstree=\'{"opened":true,"icon":"' . $scenario->getIcon(true) . '"}\'>';
				echo ' <a class="li_scenario" id="scenario' . $scenario->getId() . '" data-type="' . $scenario->getType() . '" title="{{Scénario ID :}} ' . $scenario->getId() . '" data-scenario_id="' . $scenario->getId() . '" >' . $scenario->getHumanName(false, true) . '</a>';
				echo '</li>';
			}
			echo '</ul>';
			echo '</li>';
		}
	}
}
?>
        </ul>
    </div>
</div>
</div>


<div id="scenarioThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">

 <div class="scenarioListContainer">
     <legend><i class="fa fa-cog"></i>  {{Gestion}}</legend>
     <div class="cursor" id="bt_addScenario2" style="background-color : #ffffff; height : 100px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
       <center>
        <i class="fa fa-plus-circle" style="font-size : 8em;color:#94ca02;"></i>
    </center>
    <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#94ca02"><center>{{Ajouter}}</center></span>
</div>
<?php if (config::byKey('enableScenario') == 0) {?>
    <div class="cursor expertModeVisible" id="bt_changeAllScenarioState2" data-state="1" style="background-color : #ffffff; height : 100px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
       <center>
           <i class="fa fa-check" style="font-size : 8em;color:#5cb85c;"></i>
       </center>
       <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#5cb85c"><center>{{Activer scénarios}}</center></span>
   </div>
   <?php } else {?>
   <div class="cursor expertModeVisible" id="bt_changeAllScenarioState2" data-state="0" style="background-color : #ffffff; height : 100px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
       <center>
           <i class="fa fa-times" style="font-size : 8em;color:#d9534f;"></i>
       </center>
       <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#d9534f"><center>{{Désactiver scénarios}}</center></span>
   </div>
   <?php }
?>

   <div class="cursor expertModeVisible" id="bt_displayScenarioVariable2" style="background-color : #ffffff; height : 100px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
       <center>
        <i class="fa fa-eye" style="font-size : 8em;color:#337ab7;"></i>
    </center>
    <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#337ab7"><center>{{Voir variables}}</center></span>
</div>

<div class="cursor expertModeVisible bt_showScenarioSummary" style="background-color : #ffffff; height : 100px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
 <center>
    <i class="fa fa-list" style="font-size : 8em;color:#337ab7;"></i>
</center>
<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#337ab7"><center>{{Vue d'ensemble}}</center></span>
</div>

<div class="cursor expertModeVisible bt_showExpressionTest" style="background-color : #ffffff; height : 100px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
   <center>
    <i class="fa fa-check" style="font-size : 8em;color:#337ab7;"></i>
</center>
<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#337ab7"><center>{{Testeur d'expression}}</center></span>
</div>
</div>

<legend><i class="icon jeedom-clap_cinema"></i>  {{Mes scénarios}}</legend>
<?php

if (count($scenarios[-1]) > 0) {
	echo '<legend>Aucun</legend>';
	echo '<div class="scenarioListContainer">';
	foreach ($scenarios[-1] as $scenario) {
		$opacity = ($scenario->getIsActive()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
		echo '<div class="scenarioDisplayCard cursor" data-scenario_id="' . $scenario->getId() . '" data-type="' . $scenario->getType() . '" style="background-color : #ffffff; min-height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
		echo "<center>";
		echo '<img src="core/img/scenario.png" height="90" width="85" />';
		echo "</center>";
		echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $scenario->getHumanName(true, true, true, true) . '</center></span>';
		echo '</div>';
	}
	echo '</div>';
}
foreach ($scenarioListGroup as $group) {
	if ($group['group'] != '' && count($scenarios[$group['group']]) > 0) {
		echo '<legend>' . $group['group'] . '</legend>';
		echo '<div class="scenarioListContainer">';
		foreach ($scenarios[$group['group']] as $scenario) {
			$opacity = ($scenario->getIsActive()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
			echo '<div class="scenarioDisplayCard cursor" data-scenario_id="' . $scenario->getId() . '" data-type="' . $scenario->getType() . '" style="background-color : #ffffff; min-height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
			echo "<center>";
			echo '<img src="core/img/scenario.png" height="90" width="85" />';
			echo "</center>";
			echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $scenario->getHumanName(true, true, true, true) . '</center></span>';
			echo '</div>';
		}
		echo '</div>';
	}
}
?>
</div>

<div id="div_editScenario" style="display: none; border-left: solid 1px #EEE; padding-left: 25px;">
    <legend style="height: 35px;"><i class="fa fa-arrow-circle-left cursor" id="bt_scenarioThumbnailDisplay"></i> {{Scénario}}
        <a class="btn btn-default btn-xs pull-right expertModeVisible" id="bt_copyScenario"><i class="fa fa-copy"></i> {{Dupliquer}}</a>
        <a class="btn btn-default btn-xs pull-right expertModeVisible" id="bt_logScenario"><i class="fa fa-file-text-o"></i> {{Log}}</a>
        <a class="btn btn-default btn-xs pull-right expertModeVisible" id="bt_exportScenario"><i class="fa fa fa-share"></i> {{Exporter}}</a>
        <a class="btn btn-danger btn-xs pull-right expertModeVisible" id="bt_stopScenario"><i class="fa fa-stop"></i> {{Arrêter}}</a>
    </legend>

    <div class="row well" style="margin: 0px;margin-bottom: 15px;">
        <legend>1) Informations générales</legend>
        <div class="col-sm-6">
            <form class="form-horizontal">
                <div class="form-group">
                    <label class="col-xs-3 control-label" >{{Nom du scénario}}</label>
                    <div class="col-xs-6">
                        <input class="form-control scenarioAttr input-sm" data-l1key="id" type="text" style="display: none;"/>
                        <input class="form-control scenarioAttr input-sm" data-l1key="type" type="text" style="display: none;"/>
                        <input class="form-control scenarioAttr input-sm" data-l1key="name" type="text" placeholder="{{Nom du scénario}}"/>
                    </div>
                </div>
                <div class="form-group">
                    <div class="col-md-12">
                        <textarea class="form-control scenarioAttr" data-l1key="description" placeholder="Description" rows="5"></textarea>
                    </div>
                </div>
            </form>
        </div>

        <div class="col-sm-6">
            <form class="form-horizontal">
                <div class="form-group">
                    <label class="col-sm-4 col-xs-4 control-label"></label>
                    <div class="col-sm-8 col-xs-8">
                        <input type="checkbox" class="scenarioAttr bootstrapSwitch" data-label-text="{{Actif}}" data-l1key="isActive">
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-4 control-label" for="span_lastCheck">{{Dernier lancement}}</label>
                    <div class="col-xs-3">
                        <div><span id="span_lastLaunch" class="label label-info" style="font-size : 1em;"></span></div>
                    </div>
                </div>
                <div class="form-group">
                    <label class="col-xs-4 control-label" for="span_ongoing">{{Etat}}</label>
                    <div class="col-xs-3">
                        <div><span id="span_ongoing" class="label" style="font-size : 1em;"></span></div>
                    </div>
                </div>
            </form>
        </div>
    </div>

    <div class="row">
        <div class="col-sm-2"></div>
        <div class="col-sm-2">
            <center>
                <img src="core/img/si.svg" class="img-responsive" style="height: 50px;" />
            </center>
        </div>
        <div class="col-sm-1"></div>
        <div class="col-sm-2">
            <center><i class="fa fa-arrow-right fa-5x" style="color : #454449"></i></center>
        </div>
        <div class="col-sm-1"></div>
        <div class="col-sm-2">
            <center>
                <img src="core/img/alors.svg" class="img-responsive" style="height: 50px" />
            </center>
        </div>
    </div>
    <br/>
    <div class="row">
        <div class="col-sm-6">
            <div class="well">
                <legend>2) Condition d'exécution</legend>
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-xs-3 control-label" >{{Scénario}}</label>
                        <div class="col-xs-9">
                            <select class="form-control scenarioAttr input-sm" data-l1key="mode">
                                <option value="schedule">Programmé</option>
                                <option value="provoke">Déclenché</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group mode schedule">
                        <label class="col-xs-3 control-label" >{{A exécuter}}</label>
                        <div class="col-xs-9">
                            <select class="form-control scenarioAttr input-sm" id="sel_scheduleMode">
                                <option value="once">une seule fois</option>
                                <option value="repete">répététivement</option>
                            </select>
                        </div>
                    </div>
                    <div class="form-group mode schedule" id="div_scheduleConfig">

                    </div>
                    <div class="form-group mode provoke trigger">
                        <label class="col-xs-3 control-label" >{{Par}}</label>
                        <div class="col-xs-9">
                           <div class="input-group">
                            <input class="scenarioAttr form-control" data-l1key="trigger" disabled>
                            <span class="input-group-btn">
                                <a class="btn btn-default cursor" id="bt_selectTrigger"><i class="fa fa-list-alt"></i></a>
                            </span>
                        </div>
                    </div>
                </div>
                <hr/>
                <div class="form-group">
                    <label class="col-xs-3 control-label" >{{Condition optionnelle}}</label>
                    <div class="col-xs-2">
                        <input type="checkbox" class="bootstrapSwitch" id="cb_conditionStart" />
                    </div>
                </div>
                <div class="form-group condition" style="display: none;">
                    <label class="col-xs-3 control-label" >{{Si}}</label>
                    <div class="col-xs-7">
                        <input class="form-control" id="in_cmdCondition" disabled>
                    </div>
                    <div class="col-xs-2">
                        <a class="btn btn-default cursor" id="bt_selectCondition"><i class="fa fa-list-alt"></i></a>
                    </div>
                </div>
                <div class="form-group condition" style="display: none;">
                    <label class="col-xs-3 control-label" >{{Est}}</label>
                    <div class='conditionOptions' data-type="info" data-subtype="numeric" style="display : none;">
                        <div class="col-xs-4">
                            <select class="conditionAttr form-control" data-l1key="operator">
                                <option value="==">{{égal}}</option>
                                <option value=">">{{supérieur}}</option>
                                <option value="<">{{inférieur}}</option>
                                <option value="!=">{{différent}}</option>
                            </select>
                        </div>
                        <div class="col-xs-5">
                            <input type="number" class="conditionAttr form-control" data-l1key="operande" />
                        </div>
                    </div>
                    <div class='conditionOptions' data-type="info" data-subtype="string" style="display : none;">
                        <div class="col-xs-4">
                            <select class="conditionAttr form-control" data-l1key="operator">
                                <option value="==">{{égal}}</option>
                                <option value="!=">{{différent}}</option>
                            </select>
                        </div>
                        <div class="col-xs-5">
                            <input class="conditionAttr form-control" data-l1key="operande" />
                        </div>
                    </div>
                    <div class='conditionOptions' data-type="info" data-subtype="binary" style="display : none;">
                        <div class="col-xs-9">
                            <input class="conditionAttr" data-l1key="operator" value="==" style="display : none;" />
                            <select class="conditionAttr form-control" data-l1key="operande">
                                <option value="1">{{Ouvert}}</option>
                                <option value="0">{{Fermé}}</option>
                                <option value="1">{{Allumé}}</option>
                                <option value="0">{{Eteint}}</option>
                                <option value="1">{{Déclenché}}</option>
                                <option value="0">{{Au repos}}</option>
                            </select>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </div>
    <div class="col-sm-6">
        <div class="well">
            <form class="form-horizontal">
                <legend>3) Actions
                    <a class="btn btn-xs pull-right btn-success" id="bt_addAction"><i class="fa fa-plus-circle"></i> Ajouter une action</a>
                </legend>
                <div id="div_actionList"></div>
            </form>
        </div>
    </div>
</div>

<div class="form-actions">
    <a class="btn btn-warning tooltips" id="bt_testScenario" title='{{Veuillez sauvegarder avant de tester. Ceci peut ne pas aboutir.}}'><i class="fa fa-gamepad"></i> Exécuter</a>
    <a class="btn btn-danger" id="bt_delScenario"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
    <a class="btn btn-success" id="bt_saveScenario"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
</div>

</div>
</div>

<?php
include_file('desktop', 'scenarioAssist', 'js');
?>
