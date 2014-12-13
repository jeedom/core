<?php
if (!hasRight('scenarioview', true)) {
    throw new Exception('{{401 - Accès non autorisé}}');
}

include_file('3rdparty', 'jquery.sew/jquery.sew', 'css');

include_file('3rdparty', 'jquery.tree/themes/default/style.min', 'css');
include_file('3rdparty', 'jquery.tree/jstree.min', 'js');
include_file('3rdparty', 'jquery.cron/jquery.cron.min', 'js');
include_file('3rdparty', 'jquery.cron/jquery.cron', 'css');
$scenarios = array();

$scenarios[-1] = scenario::all(null);
foreach (scenario::listGroup() as $group) {
    $scenarios[$group['group']] = scenario::all($group['group']);
}
?>

<div class="row row-overflow">
    <div class="col-lg-2 col-md-3 col-sm-4">
        <div class="bs-sidebar nav nav-list bs-sidenav"> 
            <a class="btn btn-default" id="bt_addScenario" style="width : 100%;margin-top : 5px;margin-bottom: 5px;"><i class="fa fa-plus-circle cursor" ></i> Nouveau scénario</a>
            <input id='in_treeSearch' class='form-control' placeholder="{{Rechercher}}" />
            <div id="div_tree">
                <ul id="ul_scenario" >  
                    <li data-jstree='{"opened":true}'>
                        <?php if (count($scenarios[-1]) > 0) { ?>
                            <a>Aucune</a>
                            <ul>
                                <?php
                                foreach ($scenarios[-1] as $scenario) {
                                    echo '<li data-jstree=\'{"opened":true,"icon":"' . $scenario->getIcon(true) . '"}\'>';
                                    echo ' <a class="li_scenario" id="scenario' . $scenario->getId() . '" data-type="' . $scenario->getType() . '" data-scenario_id="' . $scenario->getId() . '" >' . $scenario->getHumanName(false, true) . '</a>';
                                    echo '</li>';
                                }
                                ?>
                            </ul>
                        <?php } ?>
                        <?php
                        foreach (scenario::listGroup() as $group) {
                            if ($group['group'] != '' && count($scenarios[$group['group']]) > 0) {
                                echo '<li data-jstree=\'{"opened":true}\'>';
                                echo '<a>' . $group['group'] . '</a>';
                                echo '<ul>';
                                foreach ($scenarios[$group['group']] as $scenario) {
                                    echo '<li data-jstree=\'{"opened":true,"icon":"' . $scenario->getIcon(true) . '"}\'>';
                                    echo ' <a class="li_scenario" id="scenario' . $scenario->getId() . '" data-type="' . $scenario->getType() . '" data-scenario_id="' . $scenario->getId() . '" >' . $scenario->getHumanName(false, true) . '</a>';
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


    <div class="col-lg-10 col-md-9 col-sm-8" id="scenarioThumbnailDisplay" style="border-left: solid 1px #EEE; padding-left: 25px;">
        <legend>{{Mes scenarios}}
            <a class="btn btn-default btn-xs pull-right" href="index.php?v=d&p=scenario"><i class="fa fa-toggle-off"></i> {{Interface avancée}}</a>
        </legend>
        <?php
        if (count(scenario::all()) == 0) {
            echo "<br/><br/><br/><center><span style='color:#767676;font-size:1.2em;font-weight: bold;'>Vous n'avez encore aucun scénario, cliquez sur ajouter un scénario pour commencer</span></center>";
        } else {
            if (count($scenarios[-1]) > 0) {
                echo '<div class="scenarioListContainer">';
                foreach ($scenarios[-1] as $scenario) {
                    echo '<div class="scenarioDisplayCard cursor" data-scenario_id="' . $scenario->getId() . '" data-type="' . $scenario->getType() . '" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
                    echo "<center>";
                    echo '<i class="icon jeedom-clap_cinema" style="font-size : 4em;color:#767676;"></i>';
                    echo "</center>";
                    echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center>' . $scenario->getHumanName(true, true, true, true) . '</center></span>';
                    echo '</div>';
                }
                echo '</div>';
            }
            foreach (scenario::listGroup() as $group) {
                if ($group['group'] != '' && count($scenarios[$group['group']]) > 0) {
                    echo '<legend>' . $group['group'] . '</legend>';
                    echo '<div class="scenarioListContainer">';
                    foreach ($scenarios[$group['group']] as $scenario) {
                        echo '<div class="scenarioDisplayCard cursor" data-scenario_id="' . $scenario->getId() . '" data-type="' . $scenario->getType() . '" style="background-color : #ffffff; height : 200px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
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

    <div class="col-lg-10 col-md-9 col-sm-8" id="div_editScenario" style="display: none; border-left: solid 1px #EEE; padding-left: 25px;">
        <legend style="height: 35px;"><i class="fa fa-arrow-circle-left cursor" id="bt_scenarioThumbnailDisplay"></i> {{Scénario}}
            <a class="btn btn-default btn-xs pull-right" id="bt_logScenario"><i class="fa fa-file-text-o"></i> {{Log}}</a>
            <a class="btn btn-default btn-xs pull-right" id="bt_exportScenario"><i class="fa fa fa-share"></i> {{Exporter}}</a>
            <a class="btn btn-danger btn-xs pull-right" id="bt_stopScenario"><i class="fa fa-stop"></i> {{Arrêter}}</a>
            <a class="btn btn-default btn-xs pull-right" id="bt_switchToExpertMode" href="index.php?v=d&p=scenario"><i class="fa fa-toggle-off"></i> {{Interface avancée}}</a>
        </legend>
        <div class="row well" style="margin: 0px;margin-bottom: 15px;">
            <legend>1) Informations générale</legend>
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
                            <textarea class="form-control scenarioAttr" data-l1key="description" placeholder="Description" rows="3"></textarea>
                        </div>
                    </div>
                </form>
            </div>

            <div class="col-sm-6">
                <form class="form-horizontal">
                    <div class="form-group">
                        <label class="col-sm-3 col-xs-3 control-label">{{Actif}}</label>
                        <div class="col-sm-1 col-xs-1">
                            <input type="checkbox" class="scenarioAttr" data-l1key="isActive">
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label" for="span_lastCheck">{{Dernier lancement}}</label>
                        <div class="col-xs-3">
                            <div><span id="span_lastLaunch" class="label label-info" style="position: relative; top: 4px;"></span></div>
                        </div>
                    </div>
                    <div class="form-group">
                        <label class="col-xs-3 control-label" for="span_ongoing">{{Etat}}</label>
                        <div class="col-xs-3">
                            <div><span id="span_ongoing" class="label" style="position: relative; top: 4px;"></span></div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="row">
            <div class="col-sm-6">
                <div class="well">
                    <legend>2) Déclencheur</legend>
                    <form class="form-horizontal">
                        <div class="form-group">
                            <label class="col-xs-4 control-label" >{{Type}}</label>
                            <div class="col-xs-4">
                                <select class="form-control scenarioAttr input-sm" data-l1key="mode">
                                    <option value="schedule">Programmé</option>
                                    <option value="provoke">Déclenché</option>
                                </select>
                            </div>
                        </div>
                        <div class="form-group mode schedule">
                            <div class="col-xs-3"></div>
                            <div id='div_helpCronGenerate'></div><span class="scenarioAttr" data-l1key="schedule" id='span_helpCronGenerate' style="display: none;">* * * * *</span>
                        </div>
                        <div class="form-group mode provoke trigger">
                            <label class="col-xs-4 control-label" >{{Déclencheur}}</label>
                            <div class="form-group">
                                <div class="col-xs-6">
                                    <input class="scenarioAttr form-control" data-l1key="trigger" disabled>
                                </div>
                                <div class="col-xs-1">
                                    <a class="btn btn-default cursor" id="bt_selectTrigger"><i class="fa fa-list-alt"></i></a>
                                </div>
                            </div>
                        </div>
                        <hr/>
                        <div class="form-group">
                            <label class="col-xs-4 control-label" >{{Déclenchement conditioné}}</label>
                            <div class="col-xs-1">
                                <input type="checkbox" id="cb_conditionStart" />
                            </div>

                        </div>
                        <div class="form-group condition" style="display: none;">
                            <label class="col-xs-4 control-label" >{{Si}}</label>
                            <div class="col-xs-7">
                                <input class="form-control" id="in_cmdCondition" disabled>
                            </div>
                            <div class="col-xs-1">
                                <a class="btn btn-default cursor" id="bt_selectCondition"><i class="fa fa-list-alt"></i></a>
                            </div>
                        </div>
                        <div class="form-group condition" style="display: none;">
                            <label class="col-xs-4 control-label" >{{Est}}</label>
                            <div class='conditionOptions' data-type="info" data-subtype="numeric" style="display : none;">
                                <div class="col-xs-3">
                                    <select class="conditionAttr form-control" data-l1key="operator">
                                        <option value="=">égale</option>
                                        <option value=">">supérieur</option>
                                        <option value="<">inférieur</option>
                                        <option value="!=">différent</option>
                                    </select>
                                </div>
                                <div class="col-xs-3">
                                    <input type="number" class="conditionAttr form-control" data-l1key="operande" />
                                </div>
                            </div>
                            <div class='conditionOptions' data-type="info" data-subtype="string" style="display : none;">
                                <div class="col-xs-3">
                                    <select class="conditionAttr form-control" data-l1key="operator">
                                        <option value="=">égale</option>
                                        <option value="!=">différent</option>
                                        <option value="~">contient</option>
                                        <option value="!~">ne contient pas</option>
                                    </select>
                                </div>
                                <div class="col-xs-3">
                                    <input class="conditionAttr form-control" data-l1key="operande" />
                                </div>
                            </div>
                            <div class='conditionOptions' data-type="info" data-subtype="binary" style="display : none;">
                                <div class="col-xs-6">
                                    <input class="conditionAttr" data-l1key="operator" value="=" style="display : none;" />
                                    <select class="conditionAttr form-control" data-l1key="operande">
                                        <option value="1">Ouvert</option>
                                        <option value="0">Fermé</option>
                                        <option value="1">Allumé</option>
                                        <option value="0">Eteint</option>
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
                        <legend>3) Action
                            <a class="btn btn-xs pull-right btn-success" id="bt_addAction"><i class="fa fa-plus-circle"></i> Ajouter</a>
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