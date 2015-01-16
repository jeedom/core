<?php
if (!hasRight('scenarioview', true)) {
    throw new Exception('{{401 - Accès non autorisé}}');
}


$scenarios = array();
$scenarios[-1] = scenario::all(null);
foreach (scenario::listGroup() as $group) {
    $scenarios[$group['group']] = scenario::all($group['group']);
}
?>
<div class="row row-overflow">
    <div class="col-lg-2 col-md-3 col-sm-4" id="div_listScenario">
        <div class="bs-sidebar nav nav-list bs-sidenav" > 
            <a class="btn btn-warning form-control" id="bt_switchToExpertMode" href="index.php?v=d&p=scenarioAssist" style="text-shadow: none;"><i class="fa fa-toggle-on"></i> {{Interface avancée}}</a>
            <center>
                <?php
                if (config::byKey('enableScenario') == 0) {
                    echo '<a class="btn btn-sm btn-success expertModeVisible" id="bt_changeAllScenarioState" data-state="1" style="width : 48%;min-width : 127px;margin-top : 3px;text-shadow: none" ><i class="fa fa-check"></i> {{Act. scénarios}}</a>';
                } else {
                    echo '<a class="btn btn-sm btn-danger expertModeVisible" id="bt_changeAllScenarioState" data-state="0" style="width : 48%;min-width : 127px;margin-top : 3px;text-shadow: none" ><i class="fa fa-times"></i> {{Désac. scénarios}}</a>';
                }
                ?>
                <a class="btn btn-default btn-sm tooltips expertModeVisible" id="bt_displayScenarioVariable" title="{{Voir toutes les variables de scénario}}" style="width : 48%;min-width : 127px;margin-top : 3px;"><i class="fa fa fa-eye" style="font-size : 1.5em;"></i> {{Voir variables}}</a>
            </center>
            <a class="btn btn-default" id="bt_addScenario" style="width : 100%;margin-top : 5px;margin-bottom: 5px;"><i class="fa fa-plus-circle cursor" ></i> Nouveau scénario</a>

            <input id='in_treeSearch' class='form-control' placeholder="{{Rechercher}}" />
            <div id="div_tree">
                <ul id="ul_scenario" >  
                    <li data-jstree='{"opened":true}'>
                        <a>Aucune</a>
                        <ul>
                            <?php
                            foreach ($scenarios[-1] as $scenario) {
                                echo '<li data-jstree=\'{"opened":true,"icon":"' . $scenario->getIcon(true) . '"}\'>';
                                echo ' <a class="li_scenario" id="scenario' . $scenario->getId() . '" data-scenario_id="' . $scenario->getId() . '" >' . $scenario->getHumanName(false, true) . '</a>';
                                echo '</li>';
                            }
                            ?>
                        </ul>
                        <?php
                        foreach (scenario::listGroup() as $group) {
                            if ($group['group'] != '') {
                                echo '<li data-jstree=\'{"opened":true}\'>';
                                echo '<a>' . $group['group'] . '</a>';
                                echo '<ul>';
                                foreach ($scenarios[$group['group']] as $scenario) {
                                    echo '<li data-jstree=\'{"opened":true,"icon":"' . $scenario->getIcon(true) . '"}\'>';
                                    echo ' <a class="li_scenario" id="scenario' . $scenario->getId() . '" data-scenario_id="' . $scenario->getId() . '" >' . $scenario->getHumanName(false, true) . '</a>';
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
        <legend>{{Mes scenarios}}</legend>
        <?php
        if (count(scenario::all()) == 0) {
            echo "<br/><br/><br/><center><span style='color:#767676;font-size:1.2em;font-weight: bold;'>Vous n'avez encore aucun scénario, cliquez sur ajouter un scénario pour commencer</span></center>";
        } else {
            echo '<legend>Aucun</legend>';
            echo '<div class="scenarioListContainer">';
            foreach ($scenarios[-1] as $scenario) {
                echo '<div class="scenarioDisplayCard cursor" data-scenario_id="' . $scenario->getId() . '" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
                echo "<center>";
                echo '<i class="icon jeedom-clap_cinema" style="font-size : 4em;color:#767676;"></i>';
                echo "</center>";
                echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $scenario->getHumanName(true, true, true, true) . '</center></span>';
                echo '</div>';
            }
            echo '</div>';

            foreach (scenario::listGroup() as $group) {
                if ($group['group'] != '') {
                    echo '<legend>' . $group['group'] . '</legend>';
                    echo '<div class="scenarioListContainer">';
                    foreach ($scenarios[$group['group']] as $scenario) {
                        echo '<div class="scenarioDisplayCard cursor" data-scenario_id="' . $scenario->getId() . '" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
                        echo "<center>";
                        echo '<i class="icon jeedom-clap_cinema" style="font-size : 4em;color:#767676;"></i>';
                        echo "</center>";
                        echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $scenario->getHumanName(true, true, true, true) . '</center></span>';
                        echo '</div>';
                    }
                    echo '</div>';
                }
            }
            ?>
        <?php } ?>
    </div>

    <div id="div_editScenario" style="display: none; border-left: solid 1px #EEE; padding-left: 25px;">
        <legend style="height: 35px;"><i class="fa fa-arrow-circle-left cursor" id="bt_scenarioThumbnailDisplay"></i> {{Scénario}}
            <span class="expertModeVisible">(ID : <span class="scenarioAttr" data-l1key="id" ></span>)</span>
            <a class="btn btn-default btn-xs pull-right expertModeVisible" id="bt_copyScenario"><i class="fa fa-copy"></i> {{Dupliquer}}</a>
            <a class="btn btn-default btn-xs pull-right expertModeVisible" id="bt_logScenario"><i class="fa fa-file-text-o"></i> {{Log}}</a>
            <a class="btn btn-default btn-xs pull-right expertModeVisible" id="bt_exportScenario"><i class="fa fa fa-share"></i> {{Exporter}}</a>
            <a class="btn btn-danger btn-xs pull-right expertModeVisible" id="bt_stopScenario"><i class="fa fa-stop"></i> {{Arrêter}}</a>
            <a class="btn btn-default btn-xs pull-right expertModeVisible" id="bt_templateScenario"><i class="fa fa-cubes"></i> {{Template}}</a>
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
                            <label class="col-sm-6 col-xs-3 control-label">{{Actif}}</label>
                            <div class="col-sm-1 col-xs-1">
                                <input type="checkbox" class="scenarioAttr" data-l1key="isActive">
                            </div>
                            <label class="col-sm-3 col-xs-3 control-label">{{Visible}}</label>
                            <div class="col-sm-1 col-xs-1">
                                <input type="checkbox" class="scenarioAttr" data-l1key="isVisible">
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
                        <div class="col-sm-3 col-xs-6">
                            <select class="form-control scenarioAttr input-sm" data-l1key="mode">
                                <option value="provoke">{{Provoqué}}</option>
                                <option value="schedule">{{Programmé}}</option>
                                <option value="all">{{Les deux}}</option>
                            </select>
                        </div>
                        <div class="col-md-6">
                            <a class="btn btn-default btn-sm" id="bt_addTrigger"><i class="fa fa-plus-square"></i> {{Déclencheur}}</a>
                            <a class="btn btn-default btn-sm" id="bt_addSchedule"><i class="fa fa-plus-square"></i> {{Programmation}}</a>
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
                        <label class="col-xs-6 control-label">{{Exécuter le plus rapidement possible}}</label>
                        <div class="col-xs-1">
                            <input type="checkbox" class="scenarioAttr input-sm" data-l1key="configuration" data-l2key="speedPriority" titme="{{A ne surtout pas utiliser si vous avez des 'sleep' dans le scénario. Attention dans ce mode vous ne pouvez savoir si le scénario est en cours et aucun log ne sera écris par celui-ci}}">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-6 control-label" for="span_ongoing">{{Etat}}</label>
                        <div class="col-xs-6">
                            <div><span id="span_ongoing" class="label" style="position: relative; top: 4px;"></span></div>
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
                    </select>
                </center>
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