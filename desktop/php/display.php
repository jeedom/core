<?php
if (!hasRight('displayview', true)) {
    throw new Exception('{{401 - Accès non autorisé}}');
}

include_file('3rdparty', 'jquery.tree/themes/default/style.min', 'css');
include_file('3rdparty', 'jquery.tree/jstree.min', 'js');

sendVarToJS('cmd_widgetDashboard', cmd::availableWidget('dashboard'));
sendVarToJS('cmd_widgetMobile', cmd::availableWidget('mobile'));
?>


<ul class="nav nav-tabs">
    <li class="active"><a href="#tree" data-toggle="tab">{{Arbre domotique}}</a></li>
    <li><a href="#display_configuration" data-toggle="tab">{{Configuration de l'affichage}}</a></li>
</ul>

<div class="tab-content">
    <div class="tab-pane active" id="tree">
        <div class="row row-overflow">
            <div class="col-lg-2 col-md-3 col-sm-4" >
                <legend>{{Arbre des commandes}}</legend>
                <input id='in_treeSearch' class='form-control' placeholder="{{Rechercher}}" />
                <div id='div_tree'>
                    <ul id='ul_rootTree'>
                        <?php if (count(eqLogic::byObjectId(null)) > 0) { ?>
                            <li>
                                <a>{{Sans objet}}</a>
                                <ul>
                                    <?php
                                    foreach (eqLogic::byObjectId(null) as $eqLogic) {
                                        echo '<li>';
                                        echo '<a class="infoEqLogic" data-eqLogic_id="' . $eqLogic->getId() . '">' . $eqLogic->getName() . '</a>';
                                        echo '<ul>';
                                        foreach ($eqLogic->getCmd() as $cmd) {
                                            echo '<li>';
                                            echo '<a class="infoCmd" data-cmd_id="' . $cmd->getId() . '">' . $cmd->getName() . '</a>';
                                            echo '</li>';
                                        }
                                        echo '</ul>';
                                        echo '</li>';
                                    }
                                    ?>
                                </ul>
                            </li>
                        <?php } ?>
                        <?php
                        foreach (object::all() as $object) {
                            echo '<li>';
                            echo '<a class="infoObject" data-object_id="' . $object->getId() . '">' . $object->getName() . '</a>';
                            echo '<ul>';
                            foreach ($object->getEqLogic(false, false) as $eqLogic) {
                                echo '<li>';
                                echo '<a class="infoEqLogic" data-eqLogic_id="' . $eqLogic->getId() . '">' . $eqLogic->getName() . '</a>';
                                echo '<ul>';
                                foreach ($eqLogic->getCmd() as $cmd) {
                                    echo '<li>';
                                    echo '<a class="infoCmd" data-cmd_id="' . $cmd->getId() . '">' . $cmd->getName() . '</a>';
                                    echo '</li>';
                                }
                                echo '</ul>';
                                echo '</li>';
                            }
                            echo '</ul>';
                            echo '</li>';
                        }
                        ?>
                    </ul>
                </div>
            </div>
            <div class="col-lg-10 col-md-9 col-sm-8">
                <br/>
                <div id='div_displayInfo'></div>
            </div>
        </div>
    </div>


    <div class="tab-pane" id="display_configuration">
        <div class="panel-group" id="accordion">
            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_category">
                            {{Catégories}}
                        </a>
                    </h4>
                </div>
                <div id="collapse_category" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <form class="form-horizontal">
                            <fieldset>
                                <?php
                                foreach (jeedom::getConfiguration('eqLogic:category') as $key => $category) {
                                    echo '<legend>' . $category['name'] . '</legend>';
                                    echo '<div class="form-group">';
                                    echo '<label class="col-sm-3 control-label">{{Dashboard couleur de fond}}</label>';
                                    echo '<div class="col-sm-2">';
                                    echo '<input type="color" class="configKey form-control cursor" data-l1key="eqLogic:category:' . $key . ':color" value="' . $category['color'] . '" />';
                                    echo '</div>';
                                    echo '<div class="col-sm-1">';
                                    echo '<a class="btn btn-default bt_resetColor tooltips" data-l1key="eqLogic:category:' . $key . ':color" title="{{Remettre par défaut}}"><i class="fa fa-times"></i></a>';
                                    echo '</div>';
                                    echo '<label class="col-sm-3 control-label">{{Dashboard couleur commande}}</label>';
                                    echo '<div class="col-sm-2">';
                                    echo '<input type="color" class="configKey form-control cursor" data-l1key="eqLogic:category:' . $key . ':cmdColor" value="' . $category['cmdColor'] . '" />';
                                    echo '</div>';
                                    echo '<div class="col-sm-1">';
                                    echo '<a class="btn btn-default bt_resetColor tooltips" data-l1key="eqLogic:category:' . $key . ':cmdColor" title="{{Remettre par défaut}}"><i class="fa fa-times"></i></a>';
                                    echo '</div>';
                                    echo '</div>';
                                    echo '<div class="form-group">';
                                    echo '<label class="col-sm-3 control-label">{{Mobile couleur de fond}}</label>';
                                    echo '<div class="col-sm-2">';
                                    echo '<input type="color" class="configKey form-control cursor" data-l1key="eqLogic:category:' . $key . ':mcolor" value="' . $category['mcolor'] . '"/>';
                                    echo '</div>';
                                    echo '<div class="col-sm-1">';
                                    echo '<a class="btn btn-default bt_resetColor tooltips" data-l1key="eqLogic:category:' . $key . ':cmdColor" title="{{Remettre par défaut}}"><i class="fa fa-times"></i></a>';
                                    echo '</div>';
                                    echo '<label class="col-sm-3 control-label">{{Mobile couleur commande}}</label>';
                                    echo '<div class="col-sm-2">';
                                    echo '<input type="color" class="configKey form-control cursor" data-l1key="eqLogic:category:' . $key . ':mcmdColor" value="' . $category['mcmdColor'] . '" />';
                                    echo '</div>';
                                    echo '<div class="col-sm-1">';
                                    echo '<a class="btn btn-default bt_resetColor tooltips" data-l1key="eqLogic:category:' . $key . ':mcmdColor" title="{{Remettre par défaut}}"><i class="fa fa-times"></i></a>';
                                    echo '</div>';
                                    echo '</div>';
                                }
                                ?>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>

            <div class="panel panel-default">
                <div class="panel-heading">
                    <h4 class="panel-title">
                        <a data-toggle="collapse" data-parent="#accordion" href="#collapse_size">
                            {{Dimension}}
                        </a>
                    </h4>
                </div>
                <div id="collapse_size" class="panel-collapse collapse in">
                    <div class="panel-body">
                        <form class="form-horizontal">
                            <fieldset>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">{{Largeur pas widget (px)}}</label>
                                    <div class="col-sm-2">
                                        <input class="configKey form-control cursor" data-l1key="eqLogic::widget::stepWidth" value="<?php echo config::byKey('eqLogic::widget::stepWidth') ?>" />
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label class="col-sm-3 control-label">{{Hauteur pas widget (px)}}</label>
                                    <div class="col-sm-2">
                                        <input class="configKey form-control cursor" data-l1key="eqLogic::widget::stepHeight" value="<?php echo config::byKey('eqLogic::widget::stepHeight') ?>" />
                                    </div>
                                </div>
                            </fieldset>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        <div class="form-actions" style="height: 20px;">
            <a class="btn btn-success" id="bt_displayConfig"><i class="fa fa-check-circle"></i> {{Sauvegarder}}</a>
        </div>
    </div>
</div>

<?php include_file('desktop', 'display', 'js'); ?>