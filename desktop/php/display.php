<?php
if (!hasRight('displayview', true)) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
sendVarToJS('cmd_widgetDashboard', cmd::availableWidget('dashboard'));
sendVarToJS('cmd_widgetMobile', cmd::availableWidget('mobile'));
?>
<style>
    .eqLogicSortable{
        list-style-type: none;
        min-height: 20px;
    }
    .eqLogicSortable li {
        margin: 0 2px 2px 2px;
        padding: 5px;
    }

    .cmdSortable{
        list-style-type: none;
        min-height: 20px;
    }
    .cmdSortable li {
        margin: 0 2px 2px 2px;
        padding: 5px;
    }
</style>

<ul class="nav nav-tabs">
    <li class="active"><a href="#display_order" data-toggle="tab">{{Ma domotique}}</a></li>
    <li><a href="#display_configuration" data-toggle="tab">{{Configuration de l'affichage}}</a></li>
</ul>

<div class="tab-pane active" id="display_order">
    <br/>
    <input class="form-control pull-right" id="in_search" placeholder="{{Rechercher}}" style="width : 200px;"/>
    <a class="btn btn-danger btn-sm" id="bt_removeEqlogic" style="display:none;"><i class="fa fa-trash-o"></i> {{Supprimer}}</a>
    <br/>
    <br/>
    <div class="row">
        <div class="col-sm-4 object" data-id="-1">
            <legend>{{Aucun}}</legend>
            <ul class="eqLogicSortable">
                <?php
foreach (eqLogic::byObjectId(null, false) as $eqLogic) {
	echo '<li class="alert alert-info eqLogic cursor" data-id="' . $eqLogic->getId() . '" data-name="' . $eqLogic->getName() . '">';
	echo '<input type="checkbox" class="cb_selEqLogic" /> ';
	echo $eqLogic->getName() . ' ';
	echo '<i style="font-size:0.9em;">(' . $eqLogic->getEqType_name() . ')</i>';
	echo '<i class="fa fa-chevron-right pull-right showCmd"></i> ';
	echo '<i class="fa fa-cog pull-right configureEqLogic"></i>';
	echo '<a href="' . $eqLogic->getLinkToConfiguration() . '" target="_blank" class="pull-right"><i class="fa fa-external-link"></i></a>';

	echo '<ul class="cmdSortable" style="display:none;" >';
	foreach ($eqLogic->getCmd() as $cmd) {
		echo '<li class="alert alert-warning cmd cursor" data-id="' . $cmd->getId() . '"  data-name="' . $cmd->getName() . '">' . $cmd->getName();
		echo '<i class="fa fa-cog pull-right configureCmd"></i>';
		echo '</li>';
	}
	echo '</ul>';
	echo '</li>';
}
?>
          </ul>
      </div>
      <?php
$i = 1;
foreach (object::all() as $object) {
	if ($i == 0) {
		echo '<div class="row">';
	}
	echo '<div class="col-sm-4 object" data-id="' . $object->getId() . '">';
	echo '<legend>' . $object->getName() . '<i class="fa fa-cog pull-right cursor configureObject"></i></legend>';
	echo '<ul class="eqLogicSortable">';
	foreach ($object->getEqLogic(false, false) as $eqLogic) {
		echo '<li class="alert alert-info eqLogic cursor" data-id="' . $eqLogic->getId() . '" data-name="' . $eqLogic->getName() . '">';
		echo '<input type="checkbox" class="cb_selEqLogic" /> ';
		echo $eqLogic->getName() . ' ';
		echo '<i style="font-size:0.9em;">(' . $eqLogic->getEqType_name() . ')</i>';
		echo '<i class="fa fa-chevron-right pull-right showCmd"></i> ';
		echo '<i class="fa fa-cog pull-right configureEqLogic"></i>';
		echo '<a href="' . $eqLogic->getLinkToConfiguration() . '" target="_blank" class="pull-right"><i class="fa fa-external-link"></i></a>';
		echo '<ul class="cmdSortable" style="display:none;" >';
		foreach ($eqLogic->getCmd() as $cmd) {
			echo '<li class="alert alert-warning cmd cursor" data-id="' . $cmd->getId() . '"  data-name="' . $cmd->getName() . '">' . $cmd->getName();
			echo '<i class="fa fa-cog pull-right configureCmd"></i>';
			echo '</li>';
		}
		echo '</ul>';
		echo '</li>';
	}
	echo '</ul>';
	echo '</div>';
	$i++;
	if ($i > 2) {
		$i = 0;
	}
	if ($i == 0) {
		echo '</div>';
	}
}
?>
</div>

<div class="tab-content">
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
            <div id="collapse_size" class="panel-collapse collapse">
                <div class="panel-body">
                    <form class="form-horizontal">
                        <fieldset>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{Largeur pas widget (px)}}</label>
                                <div class="col-sm-2">
                                    <input class="configKey form-control cursor" data-l1key="eqLogic::widget::stepWidth" value="<?php echo config::byKey('eqLogic::widget::stepWidth')?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{Hauteur pas widget (px)}}</label>
                                <div class="col-sm-2">
                                    <input class="configKey form-control cursor" data-l1key="eqLogic::widget::stepHeight" value="<?php echo config::byKey('eqLogic::widget::stepHeight')?>" />
                                </div>
                            </div>
                            <div class="form-group">
                                <label class="col-sm-3 control-label">{{Centrer verticalement les commandes sur la tuile}}</label>
                                <div class="col-sm-2">
                                    <?php
if (config::byKey('eqLogic::widget::verticalAlign') == 1) {
	echo '<input type="checkbox" class="configKey cursor bootstrapSwitch" data-l1key="eqLogic::widget::verticalAlign" checked />';
} else {
	echo '<input type="checkbox" class="configKey cursor bootstrapSwitch" data-l1key="eqLogic::widget::verticalAlign" />';
}
?>

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

<?php include_file('desktop', 'display', 'js');?>