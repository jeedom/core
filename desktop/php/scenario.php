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
        <div class="bs-sidebar nav nav-list bs-sidenav" >
            <a class="btn btn-warning form-control" id="bt_switchToExpertMode" href="index.php?v=d&p=scenarioAssist" style="text-shadow: none;"><i class="fa fa-toggle-on"></i> {{Interface avancée}}</a>
            <center>
                <div class="col-xs-6">
                    <?php
if (config::byKey('enableScenario') == 0) {
	echo '<a class="btn btn-sm btn-success expertModeVisible" id="bt_changeAllScenarioState" data-state="1" style="width : 48%;min-width : 127px;margin-top : 3px;text-shadow: none;" ><i class="fa fa-check"></i> {{Act. scénarios}}</a>';
} else {
	echo '<a class="btn btn-sm btn-danger expertModeVisible" id="bt_changeAllScenarioState" data-state="0" style="width : 48%;min-width : 127px;margin-top : 3px;text-shadow: none;" ><i class="fa fa-times"></i> {{Désac. scénarios}}</a>';
}
?>
               </div>
               <div class="col-xs-6">
                <a class="btn btn-default btn-sm tooltips expertModeVisible" id="bt_displayScenarioVariable" title="{{Voir toutes les variables des scénarios}}" style="margin-top : 3px;text-shadow: none;"><i class="fa fa fa-eye"></i> {{Voir variables}}</a>
            </div>
        </center>
        <a class="btn btn-default" id="bt_addScenario" style="width : 100%;margin-top : 5px;margin-bottom: 5px;"><i class="fa fa-plus-circle cursor" ></i> {{Nouveau scénario}}</a>

        <input id='in_treeSearch' class='form-control' placeholder="{{Rechercher}}" />
        <div id="div_tree">
            <ul id="ul_scenario" >
                <li data-jstree='{"opened":true}'>
                    <a>Aucune</a>
                    <ul>
                        <?php
foreach ($scenarios[-1] as $scenario) {
	echo '<li data-jstree=\'{"opened":true,"icon":"' . $scenario->getIcon(true) . '"}\'>';
	echo ' <a class="li_scenario" id="scenario' . $scenario->getId() . '" data-scenario_id="' . $scenario->getId() . '" title="{{Scénario ID :}} ' . $scenario->getId() . '">' . $scenario->getHumanName(false, true) . '</a>';
	echo '</li>';
}
?>
                   </ul>
                   <?php
foreach ($scenarioListGroup as $group) {
	if ($group['group'] != '') {
		echo '<li data-jstree=\'{"opened":true}\'>';
		echo '<a>' . $group['group'] . '</a>';
		echo '<ul>';
		foreach ($scenarios[$group['group']] as $scenario) {
			echo '<li data-jstree=\'{"opened":true,"icon":"' . $scenario->getIcon(true) . '"}\'>';
			echo ' <a class="li_scenario" id="scenario' . $scenario->getId() . '" data-scenario_id="' . $scenario->getId() . '" title="{{Scénario ID :}} ' . $scenario->getId() . '">' . $scenario->getHumanName(false, true) . '</a>';
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

 <div id="scenarioThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">

     <div class="scenarioListContainer">
         <legend>{{Gestion}}</legend>
         <div class="cursor" id="bt_addScenario2" style="background-color : #ffffff; height : 100px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
           <center>
            <i class="fa fa-plus-circle" style="font-size : 4em;color:#94ca02;"></i>
        </center>
        <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#94ca02"><center>{{Ajouter}}</center></span>
    </div>
    <?php if (config::byKey('enableScenario') == 0) {?>
        <div class="cursor expertModeVisible" id="bt_changeAllScenarioState2" data-state="1" style="background-color : #ffffff; height : 100px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
           <center>
               <i class="fa fa-check" style="font-size : 4em;color:#5cb85c;"></i>
           </center>
           <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#5cb85c"><center>{{Activer scénarios}}</center></span>
       </div>
       <?php } else {?>
       <div class="cursor expertModeVisible" id="bt_changeAllScenarioState2" data-state="0" style="background-color : #ffffff; height : 100px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
           <center>
               <i class="fa fa-times" style="font-size : 4em;color:#d9534f;"></i>
           </center>
           <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#d9534f"><center>{{Désactiver scénarios}}</center></span>
       </div>
       <?php }
?>

       <div class="cursor expertModeVisible" id="bt_displayScenarioVariable2" style="background-color : #ffffff; height : 100px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
           <center>
            <i class="fa fa-eye" style="font-size : 4em;color:#337ab7;"></i>
        </center>
        <span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#337ab7"><center>{{Voir variables}}</center></span>
    </div>
</div>

<legend>{{Mes scénarios}}</legend>
<?php
if (count($scenarios) == 0) {
	echo "<br/><br/><br/><center><span style='color:#767676;font-size:1.2em;font-weight: bold;'>Vous n'avez encore aucun scénario. Cliquez sur ajouter un scénario pour commencer</span></center>";
} else {
	echo '<legend>Aucun</legend>';
	echo '<div class="scenarioListContainer">';
	foreach ($scenarios[-1] as $scenario) {
		$opacity = ($scenario->getIsActive()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
		echo '<div class="scenarioDisplayCard cursor" data-scenario_id="' . $scenario->getId() . '" style="background-color : #ffffff; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
		echo "<center>";
		echo '<img src="core/img/scenario.png" height="90" width="85" />';
		echo "</center>";
		echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $scenario->getHumanName(true, true, true, true) . '</center></span>';
		echo '</div>';
	}
	echo '</div>';

	foreach ($scenarioListGroup as $group) {
		if ($group['group'] != '') {
			echo '<legend>' . $group['group'] . '</legend>';
			echo '<div class="scenarioListContainer">';
			foreach ($scenarios[$group['group']] as $scenario) {
				$opacity = ($scenario->getIsActive()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
				echo '<div class="scenarioDisplayCard cursor" data-scenario_id="' . $scenario->getId() . '" style="background-color : #ffffff; height : 140px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;' . $opacity . '" >';
				echo "<center>";
				echo '<img src="core/img/scenario.png" height="90" width="85" />';
				echo "</center>";
				echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $scenario->getHumanName(true, true, true, true) . '</center></span>';
				echo '</div>';
			}
			echo '</div>';
		}
	}
}
?>
</div>

<div id="div_editScenario" style="display: none; border-left: solid 1px #EEE; padding-left: 25px;">
    <legend style="height: 35px;"><i class="fa fa-arrow-circle-left cursor" id="bt_scenarioThumbnailDisplay"></i> {{Scénario}}
        <span class="expertModeVisible">(ID : <span class="scenarioAttr" data-l1key="id" ></span>)</span>
        <a class="btn btn-default btn-xs pull-right expertModeVisible" id="bt_copyScenario"><i class="fa fa-copy"></i> {{Dupliquer}}</a>
        <a class="btn btn-default btn-xs pull-right expertModeVisible" id="bt_logScenario"><i class="fa fa-file-text-o"></i> {{Log}}</a>
        <a class="btn btn-default btn-xs pull-right expertModeVisible" id="bt_exportScenario"><i class="fa fa fa-share"></i> {{Exporter}}</a>
        <a class="btn btn-danger btn-xs pull-right expertModeVisible" id="bt_stopScenario"><i class="fa fa-stop"></i> {{Arrêter}}</a>
        <a class="btn btn-default btn-xs pull-right expertModeVisible" id="bt_templateScenario"><i class="fa fa-cubes"></i> {{Template}}</a>
        <a class="btn btn-success btn-xs pull-right" id="bt_saveScenario2"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
        <a class="btn btn-danger btn-xs pull-right" id="bt_delScenario2"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
        <a class="btn btn-warning btn-xs pull-right" id="bt_testScenario2" title='{{Veuillez sauvegarder avant de tester. Ceci peut ne pas aboutir.}}'><i class="fa fa-gamepad"></i> {{Executer}}</a>
    </legend>
    <div class="row">
        <div class="col-sm-4">
            <form class="form-horizontal">
                <fieldset>
                    <div class="form-group">
                        <label class="col-xs-6 control-label" >{{Nom du scénario}}</label>
                        <div class="col-xs-6">
                            <input class="form-control scenarioAttr input-sm" data-l1key="name" type="text" placeholder="{{Nom du scénario}}"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-6 control-label" >{{Nom à afficher}}</label>
                        <div class="col-xs-6">
                            <input class="form-control scenarioAttr input-sm tooltips" title="{{Ne rien mettre pour laisser le nom par défaut}}" data-l1key="display" data-l2key="name" type="text" placeholder="{{Nom à afficher}}"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-6 control-label" >{{Groupe}}</label>
                        <div class="col-xs-6">
                            <input class="form-control scenarioAttr input-sm" data-l1key="group" type="text" placeholder="{{Groupe du scénario}}"/>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-6 control-label"></label>
                        <div class="col-xs-6">
                            <input type="checkbox" class="scenarioAttr bootstrapSwitch" data-label-text="{{Actif}}" data-l1key="isActive">
                            <input type="checkbox" class="scenarioAttr bootstrapSwitch" data-label-text="{{Visible}}" data-l1key="isVisible">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-6 control-label" >{{Objet parent}}</label>
                        <div class="col-xs-6">
                            <select class="scenarioAttr form-control input-sm" data-l1key="object_id">
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
                    <label class="col-xs-6 control-label">{{Timeout secondes (0 = illimité)}}</label>
                    <div class="col-xs-6">
                        <input class="form-control scenarioAttr input-sm" data-l1key="timeout">
                    </div>
                </div>

            </fieldset>
        </form>
    </div>
    <div class="col-sm-5">
        <form class="form-horizontal">
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
    <div class="col-sm-3">
        <form class="form-horizontal">
            <div class="form-group">
                <div class="col-md-11">
                    <textarea class="form-control scenarioAttr" data-l1key="description" placeholder="Description"></textarea>
                </div>
            </div>
            <div class="form-group expertModeVisible">
                <label class="col-xs-6 control-label">{{Exécuter en avant plan}}</label>
                <div class="col-xs-1">
                    <input type="checkbox" class="scenarioAttr bootstrapSwitch" data-l1key="configuration" data-l2key="speedPriority" titme="{{A ne surtout pas utiliser si vous avez des 'sleep' dans le scénario. Attention dans ce mode vous ne pouvez savoir si le scénario est en cours.}}">
                </div>
            </div>
            <div class="form-group expertModeVisible">
                <label class="col-xs-6 control-label">{{Enchainer les commandes sans attendre}}</label>
                <div class="col-xs-1">
                    <input type="checkbox" class="scenarioAttr bootstrapSwitch" data-l1key="configuration" data-l2key="cmdNoWait" titme="{{Enchaine les commandes sans verification que la commande précedente c'est bien éxecutée (Attention il faut que le plugin le supporte)}}">
                </div>
            </div>
            <div class="form-group expertModeVisible">
                <label class="col-xs-6 control-label">{{Pas de log}}</label>
                <div class="col-xs-1">
                    <input type="checkbox" class="scenarioAttr bootstrapSwitch" data-l1key="configuration" data-l2key="noLog" titme="{{Le scénario n'écrit plus de log}}">
                </div>
            </div>
            <div class="form-group">
                <label class="col-xs-6 control-label" for="span_ongoing">{{Etat}}</label>
                <div class="col-xs-6">
                    <div><span id="span_ongoing" class="label" style="font-size : 1em;"></span></div>
                </div>
            </div>

        </form>
    </div>
</div>

<div id="div_scenarioElement" class="element"></div>

<div class="form-actions">
    <a class="btn btn-warning tooltips" id="bt_testScenario" title='{{Veuillez sauvegarder avant de tester. Ceci peut ne pas aboutir.}}'><i class="fa fa-gamepad"></i> Exécuter</a>
    <a class="btn btn-danger" id="bt_delScenario"><i class="fa fa-minus-circle"></i> {{Supprimer}}</a>
    <a class="btn btn-success" id="bt_saveScenario"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
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

<div class="modal fade" id="md_selectOtherAction">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <button class="close" data-dismiss="modal">×</button>
                <h3>{{Sélectionner le mot-clé}}</h3>
            </div>
            <div class="modal-body">
                <center>
                    <select id="sel_otherAction" class="form-control">
                        <option value="sleep">{{Pause}}</option>
                        <option value="variable">{{Variable}}</option>
                        <option value="scenario">{{Scénario}}</option>
                        <option value="stop">{{Stop}}</option>
                        <option value="say">{{Dit}}</option>
                        <option value="wait">{{Attendre}}</option>
                        <option value="return">{{Retourner}}</option>
                        <option value="gotodesign">{{Aller au design}}</option>
                        <option value="log">{{Ajouter un log}}</option>
                        <option value="message">{{Creer un message}}</option>
                        <option value="equipement">{{Activer/Desactiver Masquer/Afficher un équipement}}</option>
                        <option value="ask">{{Faire une demande}}</option>
                    </select>
                </center>
                <br/>
                <div class="alert alert-info sel_otherActionDescription sleep">
                    {{Pause de x seconde(s)}}
                </div>

                <div class="alert alert-info sel_otherActionDescription wait" style="display:none;">
                    {{Attend jusqu’à ce que la condition soit valide (maximum 2h)}}
                </div>

                <div class="alert alert-info sel_otherActionDescription variable" style="display:none;">
                    {{Création/modification d’une ou de la valeur d’une variable}}
                </div>

                <div class="alert alert-info sel_otherActionDescription scenario" style="display:none;">
                    {{Permet le contrôle des scénarios}}
                </div>

                <div class="alert alert-info sel_otherActionDescription stop" style="display:none;">
                   {{Arrête le scénario}}
               </div>

               <div class="alert alert-info sel_otherActionDescription say" style="display:none;">
                {{Permet de faire dire un texte à Jeedom (ne marche que si un onglet jeedom est ouvert dans le navigateur)}}
            </div>

            <div class="alert alert-info sel_otherActionDescription return" style="display:none;">
                {{Retourne un message à la fin du scénario. Cela ne sert que pour retourner un message spécifique suite à une intération. Attention à bien cocher la case "Exécuter le plus rapidement possible" pour que ça fonctionne.}}
            </div>

            <div class="alert alert-info sel_otherActionDescription gotodesign" style="display:none;">
                {{Change le design affiché sur tous les navigateurs qui affichent un design par le design demandé}}
            </div>

            <div class="alert alert-info sel_otherActionDescription log" style="display:none;">
               {{Permet de rajouter un message dans les logs}}
           </div>

           <div class="alert alert-info sel_otherActionDescription message" style="display:none;">
               {{Permet d'ajouter une message dans le centre de message}}
           </div>

           <div class="alert alert-info sel_otherActionDescription equipement" style="display:none;">
            {{Permet de modifier les prorietés visible/invisible actif/inactif d'un équipement}}
        </div>

        <div class="alert alert-info sel_otherActionDescription ask" style="display:none;">
            {{Action qui permet à Jeedom de faire une demande puis de stocker la réponde dans une variable. Cette action est bloquant et ne finie que : si jeedom reçoit une réponde ou si le timeout est atteind. Pour le moment cette action n'est compatible qu'avec le plugin SMS ou Slack.}}
        </div>

    </div>
    <div class="modal-footer">
        <a class="btn btn-default" data-dismiss="modal">{{Annuler}}</a>
        <a class="btn btn-primary" id="bt_selectOtherActionSave">{{Valider}}</a>
    </div>
</div>
</div>
</div>


<?php
include_file('desktop', 'scenario', 'js');
include_file('3rdparty', 'jquery.sew/jquery.caretposition', 'js');
include_file('3rdparty', 'jquery.sew/jquery.sew.min', 'js');
?>
