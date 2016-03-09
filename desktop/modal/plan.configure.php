<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
$plan = plan::byLinkTypeLinkIdPlanHedaerId(init('link_type'), init('link_id'), init('planHeader_id'));
if (!is_object($plan)) {
	throw new Exception('Impossible de trouver le design');
}
$link = $plan->getLink();
sendVarToJS('id', $plan->getId());
?>
<div id="div_alertPlanConfigure"></div>

<form class="form-horizontal">
    <fieldset id="fd_planConfigure">
        <legend>Général
            <a class='btn btn-success btn-xs pull-right cursor' style="color: white;" id='bt_saveConfigurePlan'><i class="fa fa-check"></i> {{Sauvegarder}}</a>
            <a class='btn btn-danger btn-xs pull-right cursor' style="color: white;" id='bt_removeConfigurePlan'><i class="fa fa-times"></i> {{Supprimer}}</a>
            <?php if ($plan->getLink_type() == 'eqLogic') {?>
            <a class='btn btn-default btn-xs pull-right cursor' id='bt_advanceEqLogicConfiguration' data-id='<?php echo $link->getId(); ?>'><i class="fa fa-cogs"></i> {{Configuration avancée de l'équipement}}</a>
            <?php }
?>
        </legend>
        <input type="text"  class="planAttr form-control" data-l1key="id" style="display: none;"/>
        <input type="text"  class="planAttr form-control" data-l1key="link_type" style="display: none;"/>
        <?php if ($plan->getLink_type() == 'eqLogic' || $plan->getLink_type() == 'scenario') {
	?>
           <div class="form-group">
            <label class="col-lg-4 control-label">{{Taille du widget}}</label>
            <div class="col-lg-2">
                <?php
if ($plan->getLink_type() == 'eqLogic') {
		echo '<input type="text" class="planAttr form-control" data-l1key="css" data-l2key="zoom" value="0.65"/>';
	}
	if ($plan->getLink_type() == 'scenario') {
		echo '<input type="text" class="planAttr form-control" data-l1key="css" data-l2key="zoom" value="1"/>';
	}
	?>
          </div>
      </div>

      <?php if ($plan->getLink_type() != 'eqLogic' || !is_object($link) || $link->widgetPossibility('custom')) {
		if ($link->widgetPossibility('custom::border-radius::plan')) {?>
    <div class="form-group">
        <label class="col-lg-4 control-label">{{Arrondir les angles (ne pas oublié de mettre %, ex 50%)}}</label>
        <div class="col-lg-2">
            <input class="form-control planAttr" data-l1key="css" data-l2key="border-radius" />
        </div>
    </div>
     <?php }
		?>
         <?php if ($link->widgetPossibility('custom::border::plan')) {?>
    <div class="form-group">
        <label class="col-lg-4 control-label">{{Bordure (attention syntax css, ex : solid 1px black)}}</label>
        <div class="col-lg-2">
            <input class="form-control planAttr" data-l1key="css" data-l2key="border" />
        </div>
    </div>
     <?php }
		?>
    <?php }
	?>
    <div class="form-group">
        <label class="col-lg-4 control-label">{{Profondeur}}</label>
        <div class="col-lg-2">
            <select class="form-control planAttr" data-l1key="css" data-l2key="z-index" >
                <option value="99">Niveau -1</option>
                <option value="1000" selected>Niveau 1</option>
                <option value="1001">Niveau 2</option>
                <option value="1002">Niveau 3</option>
            </select>
        </div>
    </div>
    <legend>Spécifique</legend>
    <?php
if ($plan->getLink_type() == 'eqLogic' && is_object($link)) {
		echo '<table class="table table-condensed">';
		echo '<thead>';
		echo '<tr>';
		echo '<th>{{Commande}}</th>';
		if ($link->widgetPossibility('changeWidget')) {
			echo '<th>{{Ne pas afficher la commande}}</th>';
		}
		echo '<th>{{Configuration avancée}}</th>';
		echo '</tr>';
		echo '</thead>';
		echo '<tbody>';
		foreach ($link->getCmd() as $cmd) {
			if ($cmd->getIsVisible() == 1) {
				echo '<tr>';
				echo '<td>' . $cmd->getHumanName() . '</td>';
				if ($link->widgetPossibility('changeWidget')) {
					echo '<td>';
					echo '<center><input type="checkbox" data-size="small" class="planAttr bootstrapSwitch" data-l1key="display" data-l2key="cmd" data-l3key="' . $cmd->getId() . '" /></center>';
					echo '</td>';
				}
				echo '<td>';
				echo '<a class="btn btn-default btn-xs pull-right cursor bt_advanceCmdConfiguration" data-id="' . $cmd->getId() . '"><i class="fa fa-cogs"></i></a>';
				echo '</td>';
				echo '</tr>';
			}
		}
		echo '</tbody>';
		echo '</table>';
	}

	if ($plan->getLink_type() == 'scenario') {
		echo '<div class="form-group">';
		echo '<label class="col-lg-6 control-label">{{Masquer les commandes}}</label>';
		echo '<div class="col-lg-1">';
		echo '<input type="checkbox" data-size="small" class="planAttr bootstrapSwitch" data-l1key="display" data-l2key="hideCmd" />';
		echo '</div>';
		echo '</div>';
	}
	?>
<?php } else if ($plan->getLink_type() == 'graph') {?>
<div class="form-group">
    <label class="col-lg-4 control-label">{{Période}}</label>
    <div class="col-lg-2">
        <select class="planAttr form-control" data-l1key="display" data-l2key="dateRange">
            <option value="30 min">{{30min}}</option>
            <option value="1 day">{{Jour}}</option>
            <option value="7 days" selected>{{Semaine}}</option>
            <option value="1 month">{{Mois}}</option>
            <option value="1 year">{{Années}}</option>
            <option value="all">{{Tous}}</option>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-4 control-label">{{Bordure (attention syntax css, ex : solid 1px black}}</label>
    <div class="col-lg-2">
        <input class="form-control planAttr" data-l1key="css" data-l2key="border" />
    </div>
</div>
<div class="form-group">
    <label class="col-lg-4 control-label">{{Profondeur}}</label>
    <div class="col-lg-2">
        <select class="form-control planAttr" data-l1key="css" data-l2key="z-index" >
            <option value="99">Niveau -1</option>
            <option value="1000" selected>Niveau 1</option>
            <option value="1001">Niveau 2</option>
            <option value="1002">Niveau 3</option>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-4 control-label">{{Afficher la légende}}</label>
    <div class="col-lg-2">
        <input type="checkbox" checked class="planAttr bootstrapSwitch" data-l1key="display" data-l2key="showLegend" >
    </div>
</div>
<div class="form-group">
    <label class="col-lg-4 control-label">{{Afficher le navigateur}}</label>
    <div class="col-lg-2">
        <input type="checkbox" checked class="planAttr bootstrapSwitch" data-l1key="display" data-l2key="showNavigator" >
    </div>
</div>
<div class="form-group">
    <label class="col-lg-4 control-label">{{Afficher le sélecteur de période}}</label>
    <div class="col-lg-2">
        <input type="checkbox" class="planAttr bootstrapSwitch" checked data-l1key="display" data-l2key="showTimeSelector" >
    </div>
</div>
<div class="form-group">
    <label class="col-lg-4 control-label">{{Afficher la barre de défilement}}</label>
    <div class="col-lg-2">
        <input type="checkbox" class="planAttr bootstrapSwitch" checked data-l1key="display" data-l2key="showScrollbar" >
    </div>
</div>
<div class="form-group">
    <label class="col-lg-4 control-label">{{Fond transparent}}</label>
    <div class="col-lg-2">
        <input type="checkbox" class="planAttr bootstrapSwitch" checked data-l1key="display" data-l2key="transparentBackground" >
    </div>
</div>
<?php } else if ($plan->getLink_type() == 'plan' || $plan->getLink_type() == 'view') {
	?>
    <div class="form-group">
        <label class="col-lg-4 control-label">{{Nom}}</label>
        <div class="col-lg-2">
            <input class="planAttr form-control" data-l1key="display" data-l2key="name" />
        </div>
    </div>
    <?php if ($plan->getLink_type() == 'view') {
		?>
      <div class="form-group">
        <label class="col-lg-4 control-label">{{Lien}}</label>
        <div class="col-lg-2">
            <select class="form-control planAttr" data-l1key="link_id">
                <?php
foreach (view::all() as $views) {
			echo '<option value="' . $views->getId() . '">' . $views->getName() . '</option>';
		}
		?>
         </select>
     </div>
 </div>
 <?php
}
	if ($plan->getLink_type() == 'plan') {
		?>
  <div class="form-group">
    <label class="col-lg-4 control-label">{{Lien}}</label>
    <div class="col-lg-2">
        <select class="form-control planAttr" data-l1key="link_id">
            <?php
foreach (planHeader::all() as $planHeader_select) {
			if ($planHeader_select->getId() != $plan->getPlanHeader_id()) {
				echo '<option value="' . $planHeader_select->getId() . '">' . $planHeader_select->getName() . '</option>';
			}
		}
		?>
    </select>
</div>
</div>
<div class="form-group">
    <label class="col-lg-4 control-label">{{Position X}}</label>
    <div class="col-lg-2">
        <input class="planAttr form-control" data-l1key="display" data-l2key="offsetX" />
    </div>
    <label class="col-lg-2 control-label">{{Position Y}}</label>
    <div class="col-lg-2">
        <input class="planAttr form-control" data-l1key="display" data-l2key="offsetY" />
    </div>
</div>
<?php }
	?>
<div class="form-group">
    <label class="col-lg-4 control-label">{{Icône}}</label>
    <div class="col-lg-2">
        <div class="planAttr" data-l1key="display" data-l2key="icon" ></div>
    </div>
    <div class="col-lg-2">
        <a class="btn btn-default btn-sm" id="bt_chooseIcon"><i class="fa fa-flag"></i> {{Choisir une icône}}</a>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-4 control-label">{{Couleur de fond}}</label>
    <div class="col-lg-2">
        <input type="color" class="planAttr form-control" data-l1key="css" data-l2key="background-color" />
    </div>
    <label class="col-lg-1 control-label">{{Transparent}}</label>
    <div class="col-lg-2">
        <input type="checkbox" class="planAttr bootstrapSwitch" data-l1key="display" data-l2key="background-transparent" />
    </div>
    <label class="col-lg-1 control-label">{{Défaut}}</label>
    <div class="col-lg-1">
        <input type="checkbox" class="planAttr bootstrapSwitch" data-l1key="display" data-l2key="background-defaut" checked />
    </div>
</div>
<div class="form-group">
    <label class="col-lg-4 control-label">{{Couleur du texte}}</label>
    <div class="col-lg-2">
        <input type="color" class="planAttr form-control" data-l1key="css" data-l2key="color" />
    </div>
    <label class="col-lg-1 control-label">{{Défaut}}</label>
    <div class="col-lg-1">
        <input type="checkbox" class="planAttr bootstrapSwitch" data-l1key="display" data-l2key="color-defaut" checked />
    </div>
</div>
<div class="form-group">
    <label class="col-lg-4 control-label">{{Arrondir les angles (ne pas oublié de mettre %, ex 50%)}}</label>
    <div class="col-lg-2">
        <input class="form-control planAttr" data-l1key="css" data-l2key="border-radius" />
    </div>
</div>
<div class="form-group">
    <label class="col-lg-4 control-label">{{Bordure (attention syntax css, ex : solid 1px black)}}</label>
    <div class="col-lg-2">
        <input class="form-control planAttr" data-l1key="css" data-l2key="border" />
    </div>
</div>
<div class="form-group">
    <label class="col-lg-4 control-label">{{Profondeur}}</label>
    <div class="col-lg-2">
        <select class="form-control planAttr" data-l1key="css" data-l2key="z-index" >
            <option value="99">Niveau -1</option>
            <option value="1000" selected>Niveau 1</option>
            <option value="1001">Niveau 2</option>
            <option value="1002">Niveau 3</option>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-4 control-label">{{Taille de la police (ex 50%, il faut bien mettre le signe %)}}</label>
    <div class="col-lg-2">
        <input class="planAttr form-control" data-l1key="css" data-l2key="font-size" />
    </div>
</div>
<div class="form-group">
    <label class="col-lg-4 control-label">{{Gras}}</label>
    <div class="col-lg-2">
        <select class="planAttr form-control" data-l1key="css" data-l2key="font-weight">
            <option value="bold">Gras</option>
            <option value="normal">Normal</option>
        </select>
    </div>
</div>
<?php } else if ($plan->getLink_type() == 'text') {?>
<div class="form-group">
    <label class="col-lg-4 control-label">{{Nom}}</label>
    <div class="col-lg-8">
        <textarea class="planAttr form-control" data-l1key="display" data-l2key="text" rows=10>Texte à insérer ici</textarea>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-4 control-label">{{Icône}}</label>
    <div class="col-lg-2">
        <div class="planAttr" data-l1key="display" data-l2key="icon" ></div>
    </div>
    <div class="col-lg-2">
        <a class="btn btn-default btn-sm" id="bt_chooseIcon"><i class="fa fa-flag"></i> {{Choisir une icône}}</a>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-4 control-label">{{Couleur de fond}}</label>
    <div class="col-lg-2">
        <input type="color" class="planAttr form-control" data-l1key="css" data-l2key="background-color" />
    </div>
    <label class="col-lg-1 control-label">{{Transparent}}</label>
    <div class="col-lg-1">
        <input type="checkbox" class="planAttr bootstrapSwitch" data-l1key="display" data-l2key="background-transparent" />
    </div>
    <label class="col-lg-1 control-label">{{Défaut}}</label>
    <div class="col-lg-1">
        <input type="checkbox" class="planAttr bootstrapSwitch" data-l1key="display" data-l2key="background-defaut" checked />
    </div>
</div>
<div class="form-group">
    <label class="col-lg-4 control-label">{{Couleur du texte}}</label>
    <div class="col-lg-2">
        <input type="color" class="planAttr form-control" data-l1key="css" data-l2key="color" />
    </div>
    <label class="col-lg-1 control-label">{{Défaut}}</label>
    <div class="col-lg-1">
        <input type="checkbox" class="planAttr bootstrapSwitch" data-l1key="display" data-l2key="color-defaut" checked />
    </div>
</div>
<div class="form-group">
    <label class="col-lg-4 control-label">{{Arrondir les angles (ne pas oublié de mettre %, ex 50%)}}</label>
    <div class="col-lg-2">
        <input class="form-control planAttr" data-l1key="css" data-l2key="border-radius" />
    </div>
</div>
<div class="form-group">
    <label class="col-lg-4 control-label">{{Bordure (attention syntaxe css, ex : solid 1px black)}}</label>
    <div class="col-lg-2">
        <input class="form-control planAttr" data-l1key="css" data-l2key="border" />
    </div>
</div>
<div class="form-group">
    <label class="col-lg-4 control-label">{{Taille de la police (ex 50%, il faut bien mettre le signe %)}}</label>
    <div class="col-lg-2">
        <input class="planAttr form-control" data-l1key="css" data-l2key="font-size" />
    </div>
</div>
<div class="form-group expertModeVisible">
    <label class="col-lg-4 control-label">{{Prendre en compte la taille predefinie}}</label>
    <div class="col-lg-4">
        <input type="checkbox" class="planHeaderAttr bootstrapSwitch" data-on-color="danger" data-off-color="success" data-off-text="Oui" data-on-text="Non" data-l1key='configuration' data-l2key="noPredefineSize" />
    </div>
</div>
<div class="form-group">
    <label class="col-lg-4 control-label">{{Profondeur}}</label>
    <div class="col-lg-2">
        <select class="form-control planAttr" data-l1key="css" data-l2key="z-index" >
            <option value="99">Niveau -1</option>
            <option value="1000" selected>Niveau 1</option>
            <option value="1001">Niveau 2</option>
            <option value="1002">Niveau 3</option>
        </select>
    </div>
</div>
<div class="form-group">
    <label class="col-lg-4 control-label">{{Gras}}</label>
    <div class="col-lg-2">
        <select class="planAttr form-control" data-l1key="css" data-l2key="font-weight">
            <option value="bold">Gras</option>
            <option value="normal">Normal</option>
        </select>
    </div>
</div>
<?php }
?>
</fieldset>
</form>


<script>
    initCheckBox();

    $('#bt_advanceEqLogicConfiguration').off('click').on('click', function () {
        $('#md_modal2').dialog({title: "{{Configuration de l'équipement}}"});
        $('#md_modal2').load('index.php?v=d&modal=eqLogic.configure&eqLogic_id=' + $(this).attr('data-id')).dialog('open');
    });

    $('.bt_advanceCmdConfiguration').off('click').on('click', function () {
        $('#md_modal2').dialog({title: "{{Configuration de la commande}}"});
        $('#md_modal2').load('index.php?v=d&modal=cmd.configure&cmd_id=' + $(this).attr('data-id')).dialog('open');
    });

    $('#fd_planConfigure').on('change switchChange.bootstrapSwitch','.planAttr[data-l1key=display][data-l2key=background-transparent]', function() {
        if($(this).value() == 1){
            $('.planAttr[data-l1key=display][data-l2key=background-defaut]').value(0);
        }
    });

    $('#fd_planConfigure').on('change','.planAttr[data-l1key=css][data-l2key=background-color]', function() {
     if($(this).value() != '#000000'){
        $('.planAttr[data-l1key=display][data-l2key=background-defaut]').value(0);
    }
});

    $('#fd_planConfigure').on('change switchChange.bootstrapSwitch','.planAttr[data-l1key=display][data-l2key=background-defaut]', function() {
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

    $('#bt_removeConfigurePlan').on('click', function () {
        bootbox.confirm('Etes-vous sûr de vouloir supprimer cet object du design ?', function (result) {
            if (result) {
                remove();
            }
        });
    });

    if (isset(id) && id != '') {
        load(id);
    }

    function load(_id) {
        $.ajax({// fonction permettant de faire de l'ajax
            type: "POST", // methode de transmission des données au fichier php
            url: "core/ajax/plan.ajax.php", // url du fichier php
            data: {
                action: "get",
                id: _id
            },
            dataType: 'json',
            error: function (request, status, error) {
                handleAjaxError(request, status, error, $('#div_alertPlanConfigure'));
            },
            success: function (data) { // si l'appel a bien fonctionné
            if (data.state != 'ok') {
                $('#div_alertPlanConfigure').showAlert({message: data.result, level: 'danger'});
                return;
            }
            $('#fd_planConfigure').setValues(data.result, '.planAttr');
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

    function remove() {
        $.ajax({// fonction permettant de faire de l'ajax
            type: "POST", // methode de transmission des données au fichier php
            url: "core/ajax/plan.ajax.php", // url du fichier php
            data: {
                action: "remove",
                id: $(".planAttr[data-l1key=id]").value()
            },
            dataType: 'json',
            error: function (request, status, error) {
                handleAjaxError(request, status, error, $('#div_alertPlanConfigure'));
            },
            success: function (data) { // si l'appel a bien fonctionné
            if (data.state != 'ok') {
                $('#div_alertPlanConfigure').showAlert({message: data.result, level: 'danger'});
                return;
            }
            $('#div_alertPlanConfigure').showAlert({message: 'Design supprimé', level: 'success'});
            displayPlan();
            $('#fd_planConfigure').closest("div.ui-dialog-content").dialog("close");
        }
    });
    }

</script>