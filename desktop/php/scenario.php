<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

global $scenarios;
$scenarios = array();
$hasScenario = false;

//get all scenarios groups and all scenarios by groups:
$scenarioListGroup = scenario::listGroup();
if (is_array($scenarioListGroup)) {
	foreach ($scenarioListGroup as $group) {
		$scenarios[$group['group']] = scenario::all($group['group']);
	}
	$hasScenario = true;
}
//get all scenarios without group:
$scenarioNoGroup = scenario::all(null);
if (count($scenarioNoGroup) > 0) {
	$scenarios['{{Aucun}}'] = scenario::all(null);
	$hasScenario = true;
}

function jeedom_displayScenarioGroup($_group='', $_index=-1) {
	global $scenarios;
	$thisDiv = '';

	if ($_group == '') {
		$groupName = '{{Aucun}}';
		$href = '#config_none';
		$id = 'config_none';
	} else {
		$groupName = $_group;
		$href = '#config_'.$_index;
		$id = 'config_'.$_index;
	}
	$thisDiv .= '<div class="panel panel-default">';
	$thisDiv .= '<div class="panel-heading">';
	$thisDiv .= '<h3 class="panel-title">';
	$thisDiv .= '<a class="accordion-toggle" data-toggle="collapse" data-parent="" aria-expanded="false" href="'.$href.'">' . $groupName . ' - ';
	$c = count($scenarios[$groupName]);
	$thisDiv .= $c. ($c > 1 ? ' scénarios' : ' scénario').'</a>';
	$thisDiv .= '</h3>';
	$thisDiv .= '</div>';
	$thisDiv .= '<div id="'.$id.'" class="panel-collapse collapse">';
	$thisDiv .= '<div class="panel-body">';
	$thisDiv .= '<div class="scenarioListContainer">';
	foreach ($scenarios[$groupName] as $scenario) {
		$inactive = ($scenario->getIsActive()) ? '' : 'inactive';
		$thisDiv .= '<div class="scenarioDisplayCard cursor '.$inactive.'" data-scenario_id="' . $scenario->getId() . '">';
		if($scenario->getDisplay('icon') != ''){
			$thisDiv .= '<span>'.$scenario->getDisplay('icon').'</span>';
		}else{
			$thisDiv .= '<span><i class="icon noicon jeedom-clap_cinema"></i></span>';
		}
		$thisDiv .= "<br>";
		$thisDiv .= '<span class="name">' . $scenario->getHumanName(true, true, true, true) . '</span>';

		$thisDiv .= '<span class="hiddenAsCard displayTableRight">';
			$thisDiv .= '<span>'.$scenario->getLastLaunch().'</span>';
			$thisDiv .= '<a class="btn btn-default btn-xs bt_ViewLog"><i class="far fa-file"></i></a>';
		$thisDiv .= '</span>';

		$thisDiv .= '</div>';
	}
	$thisDiv .= '</div>';
	$thisDiv .= '</div>';
	$thisDiv .= '</div>';
	$thisDiv .= '</div>';
	return $thisDiv;
}

sendVarToJs('initSearch', init('search', 0));
?>

<div class="row row-overflow">
	<div id="scenarioThumbnailDisplay" class="col-xs-12">
		<legend><i class="fas fa-cog"></i>  {{Gestion}}</legend>
		<div class="scenarioListContainer <?php echo (jeedom::getThemeConfig()['theme_displayAsTable'] == 1) ? ' containerAsTable' : ''; ?>">
			<div class="cursor logoPrimary" id="bt_addScenario">
				<div class="center"><i class="fas fa-plus-circle"></i></div>
				<span class="txtColor">{{Ajouter}}</span>
			</div>
			<div class="cursor warning bt_clearAllLogs">
				<div class="center"><i class="far fa-trash-alt"></i></div>
				<span class="txtColor">{{Supprimer les logs}}</span>
			</div>
			<?php if (config::byKey('enableScenario') == 0) {?>
				<div class="cursor success" id="bt_changeAllScenarioState" data-state="1">
					<div class="center"><i class="fas fa-check"></i></div>
					<span class="txtColor">{{Activer scénarios}}</span>
				</div>
			<?php } else {?>
				<div class="cursor danger" id="bt_changeAllScenarioState" data-state="0">
					<div class="center"><i class="fas fa-times"></i></div>
					<span class="txtColor">{{Désactiver scénarios}}</span>
				</div>
			<?php } ?>
			<div class="cursor logoSecondary bt_showScenarioSummary">
				<div class="center"><i class="fas fa-list"></i></div>
				<span class="txtColor">{{Vue d'ensemble}}</span>
			</div>
		</div>

		<legend><i class="icon jeedom-clap_cinema"></i>  {{Mes scénarios}} <sub class="itemsNumber"></sub></legend>
		<?php
		if ($hasScenario == false) {
			echo "<br/><br/><br/><div class='center'><span style='color:#767676;font-size:1.2em;font-weight: bold;'>Vous n'avez encore aucun scénario. Cliquez sur ajouter pour commencer</span></div>";
		} else {
			$div = '<div class="input-group" style="margin-bottom:5px;">';
			$div .= '<input class="form-control roundedLeft" placeholder="{{Rechercher | nom | :not(nom}}" id="in_searchScenario"/>';
			$div .= '<div class="input-group-btn">';
			$div .= '<a id="bt_resetScenarioSearch" class="btn" style="width:30px"><i class="fas fa-times"></i></a>';
			$div .= '<a class="btn" id="bt_openAll"><i class="fas fa-folder-open"></i></a>';
			$div .= '<a class="btn" id="bt_closeAll"><i class="fas fa-folder"></i></a>';
			$div .= '<a class="btn roundedRight" id="bt_displayAsTable" data-card=".scenarioDisplayCard" data-container=".scenarioListContainer" data-state="0"><i class="fas fa-grip-lines"></i></a>';
			$div .= '</div>';
			$div .= '</div>';

			$div .= '<div class="panel-group" id="accordionScenario">';
			//No group first:
			if (isset($scenarios['{{Aucun}}']) && count($scenarios['{{Aucun}}']) > 0) {
				$div .= jeedom_displayScenarioGroup();
			}
			echo $div;

			//scenarios groups:
			$i = 0;
			$div = '';
			foreach ($scenarioListGroup as $group) {
				if ($group['group'] == '') {
					continue;
				}
				$div .= jeedom_displayScenarioGroup($group['group'], $i);
				$i += 1;
			}
			$div .= '</div>';
			echo $div;
		}
		?>
	</div>

	<div id="div_editScenario" class="hasfloatingbar col-xs-12" style="display: none;" >
        <div class="floatingbar">
          <div class="input-group">
              <span class="input-group-btn">
                  <span id="span_ongoing" class="label label-sm"></span>

                  <a id="bt_undo" class="disabled btn btn-sm roundedLeft" title="{{Etat précédent}} (Ctrl+Shift+Z)" style="margin:0"><i class="fas fa-undo"></i>
                  </a><a id="bt_redo" class="disabled btn btn-sm" title="{{Etat suivant}} (Ctrl+Shift+Y)" style="margin:0"><i class="fas fa-redo"></i></a>

                  <a class="btn btn-sm bt_addScenarioElement"><i class="fas fa-plus-circle"></i> <span class="hidden-768">{{Ajouter bloc}}</span>
                  </a><a class="btn btn-sm" id="bt_logScenario" title="{{Log (Ctrl+l)}}"><i class="far fa-file-alt"></i>
                  </a><a class="btn btn-sm" id="bt_copyScenario" title="{{Dupliquer}}"><i class="fas fa-copy"></i>
                  </a><a class="btn btn-sm" id="bt_graphScenario" title="{{Liens}}"><i class="fas fa-object-group"></i>
                  </a><a class="btn btn-sm" id="bt_editJsonScenario" title="{{Edition texte}}"> <i class="far fa-edit"></i>
                  </a><a class="btn btn-sm" id="bt_exportScenario" title="{{Exporter}}"><i class="fas fa-share"></i>
                  </a><a class="btn btn-sm" id="bt_templateScenario" title="{{Template}}"><i class="fas fa-cubes"></i></a>

                  <input class="input-sm" placeholder="{{Rechercher}}" id="in_searchInsideScenario" style="min-width: 120px;display:none;"/>
                  <a id="bt_resetInsideScenarioSearch" class="disabled btn btn-sm" data-state="0" style="width:30px" title="{{Rechercher}}"><i class="fas fa-search"></i></a>

                  <a class="btn btn-warning btn-sm" id="bt_runScenario" title='{{Veuillez sauvegarder avant de tester. Ceci peut ne pas aboutir.<br>Ctrl+click pour sauvegarder, executer et ouvrir le log}}'><i class="fas fa-gamepad"></i> <span class="hidden-768">{{Exécuter}}</span>
                  </a><a class="btn btn-danger btn-sm" id="bt_stopScenario"><i class="fas fa-stop"></i> {{Arrêter}}
                  </a><a class="btn btn-success btn-sm" id="bt_saveScenario"><i class="fas fa-check-circle"></i> <span class="hidden-768">{{Sauvegarder}}</span>
                  </a><a class="btn btn-danger btn-sm roundedRight" id="bt_delScenario"><i class="fas fa-minus-circle"></i> <span class="hidden-768">{{Supprimer}}</span></a>
              </span>
          </div>
        </div>
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation"><a class="cursor" aria-controls="home" role="tab" id="bt_scenarioThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
			<li role="presentation" class="active"><a id="bt_generalTab" href="#generaltab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Général}} (ID : <span class="scenarioAttr" data-l1key="id" ></span>)</a></li>
			<li role="presentation"><a id="bt_scenarioTab" href="#scenariotab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-filter"></i> {{Scénario}}</a></li>
		</ul>
		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="generaltab">
				<br/>
				<div class="row">
					<div class="col-sm-6">
						<form class="form-horizontal">
							<fieldset>
								<legend><i class="fas fa-users-cog"></i> {{Paramètres}}</legend>
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
									<label class="col-xs-5 control-label">{{Actif}}</label>
									<div class="col-xs-1">
										<input type="checkbox" class="scenarioAttr" data-l1key="isActive">
									</div>
								</div>
								<div class="form-group">
									<label class="col-xs-5 control-label">{{Visible}}</label>
									<div class="col-xs-1">
										<input type="checkbox" class="scenarioAttr" data-l1key="isVisible">
									</div>
								</div>
								<div class="form-group">
									<label class="col-xs-5 control-label" >{{Objet parent}}</label>
									<div class="col-xs-6">
										<select class="form-control scenarioAttr" data-l1key="object_id">
											<?php echo jeeObject::getUISelectList(); ?>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-xs-5 control-label">{{Log}}</label>
									<div class="col-xs-6">
										<select class="form-control scenarioAttr" data-l1key="configuration" data-l2key="logmode">
											<option value="default">{{Défaut}}</option>
											<option value="none">{{Aucun}}</option>
											<option value="realtime">{{Temps réel}}</option>
										</select>
									</div>
								</div>
								<div class="form-group">
									<label class="col-xs-5 control-label">{{Timeout}}
										<sup><i class="fas fa-question-circle" tooltip="{{Durée au delà de laquelle le scénario est coupé. 0 : pas de timeout.}}"></i></sup>
										<sub>s</sub>
									</label>
									<div class="col-xs-6">
										<input class="form-control scenarioAttr" data-l1key="timeout">
									</div>
								</div>
								<div class="form-group">
									<label class="col-xs-5 control-label">{{Multi-lancement}}
										<sup><i class="fas fa-question-circle" tooltip="{{Le scénario pourra s'éxécuter plusieurs fois en même temps.}}"></i></sup>
									</label>
									<div class="col-xs-1">
										<input type="checkbox" class="scenarioAttr" data-l1key="configuration" data-l2key="allowMultiInstance">
									</div>
									<label class="col-xs-3 control-label">{{Synchrone}}
										<sup><i class="fas fa-question-circle" tooltip="{{Le scénario est en mode synchrone. Attention, cela peut rendre le système instable.}}"></i></sup>
									</label>
									<div class="col-xs-1">
										<input type="checkbox" class="scenarioAttr" data-l1key="configuration" data-l2key="syncmode">
									</div>
								</div>
								<div class="form-group">
									<label class="col-xs-5 control-label">{{Timeline}}
										<sup><i class="fas fa-question-circle" tooltip="{{Les exécutions du scénario pourront être vues dans la timeline.}}"></i></sup>
									</label>
									<div class="col-xs-1">
										<input type="checkbox" class="scenarioAttr" data-l1key="configuration" data-l2key="timeline::enable">
									</div>
									<div class="col-xs-5">
										<input class="scenarioAttr" data-l1key="configuration" data-l2key="timeline::folder" placeholder="{{Dossier}}" style="width:100%;display:none;">
									</div>
								</div>
								<div class="form-group">
									<label class="col-xs-5 control-label">{{Dernier lancement}}</label>
									<div class="col-xs-3">
										<span class="label label-info scenarioAttr" data-l1key="lastLaunch"></span>
									</div>
								</div>
								<div class="form-group">
									<label class="col-xs-5 control-label">{{Icône}}</label>
									<div class="col-xs-3">
										<a class="btn btn-default btn-sm" id="bt_chooseIcon"><i class="fas fa-flag"></i> {{Choisir}}</a>
									</div>
									<div class="col-xs-3">
										<div class="scenarioAttr" data-l1key="display" data-l2key="icon" style="font-size : 1.5em;"></div>
									</div>
								</div>
							</fieldset>
						</form>
					</div>
					<div class="col-sm-6">
						<form class="form-horizontal">
							<fieldset>
								<legend><i class="fas fa-play-circle"></i> {{Déclenchement}}</legend>
								<div class="form-group">
									<label class="col-sm-3 col-xs-6 control-label" >{{Mode du scénario}}</label>
									<div class="col-sm-9 col-xs-6">
										<div class="input-group">
											<select class="form-control roundedLeft scenarioAttr" data-l1key="mode">
												<option value="provoke">{{Provoqué}}</option>
												<option value="schedule">{{Programmé}}</option>
												<option value="all">{{Les deux}}</option>
											</select>
											<span class="input-group-btn">
												<a class="btn btn-default" id="bt_addTrigger"><i class="fas fa-plus-square"></i> {{Déclencheur}}
												</a><a class="btn btn-default roundedRight" id="bt_addSchedule"><i class="fas fa-plus-square"></i> {{Programmation}}</a>
											</span>
										</div>
									</div>
									<label id="emptyModeWarning" class="warning col-xs-12" style="display: none;"><i class="warning fas fa-exclamation-circle"></i> {{Attention : aucun déclencheur paramétré !}}</label>
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
							</fieldset>
						</form>
						<hr class="hrPrimary">
						<legend><i class="fas fa-link"></i> {{Scénarios liés}}</legend>
						<div class="scenario_link"></div>
					</div>
				</div>

				<hr class="hrPrimary">
				<div class="row">
					<div class="col-sm-12">
						<div class="form-group">
							<div class="col-md-12">
								<span class="label" id="humanNameTag"></span>
							</div>
						</div>
					</div>
					<div class="col-sm-12">
						<div class="form-group">
							<div class="col-md-12">
								<textarea class="form-control scenarioAttr ta_autosize" data-l1key="description" placeholder="{{Description}}"></textarea>
							</div>
						</div>
					</div>
				</div>
			</div>


			<div role="tabpanel" class="tab-pane" id="scenariotab">
				<div id="div_scenarioElement" class="element"></div>
			</div>
		</div>

	</div>
</div>

<div class="modal fade" id="md_addElement">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button class="close" data-dismiss="modal">×</button>
				<h4>{{Ajouter un bloc}}</h4>
			</div>
			<div class="modal-body">
				<select id="in_addElementType" class="form-control">
					<option value="if">{{Si/Alors/Sinon}}</option>
					<option value="action">{{Action}}</option>
					<option value="for">{{Boucle}}</option>
					<option value="in">{{Dans}}</option>
					<option value="at">{{A}}</option>
					<option value="code">{{Code}}</option>
					<option value="comment">{{Commentaire}}</option>
				</select>
				<br/>
				<div class="alert alert-info addElementTypeDescription if">
					Permet de faire des conditions dans votre scénario. Par exemple : Si mon détecteur d’ouverture de porte se déclenche Alors allumer la lumière.
				</div>

				<div class="alert alert-info addElementTypeDescription action" style="display:none;">
					Permet de lancer une action, sur un de vos modules, scénarios ou autre. Par exemple : Passer votre sirène sur ON.
				</div>

				<div class="alert alert-info addElementTypeDescription for" style="display:none;">
					Une boucle permet de réaliser une action de façon répétée un certain nombre de fois. Par exemple : Permet de répéter une action de 1 à X, c’est-à-dire X fois.
				</div>

				<div class="alert alert-info addElementTypeDescription in" style="display:none;">
					Permet de faire une action dans X min. Par exemple : Dans 5 min, éteindre la lumière.
				</div>

				<div class="alert alert-info addElementTypeDescription at" style="display:none;">
					A un temps précis, cet élément permet de lancer une action. Par exemple : A 9h30, ouvrir les volets.
				</div>

				<div class="alert alert-info addElementTypeDescription code" style="display:none;">
					Cet élément permet de rajouter dans votre scénario de la programmation à l’aide d’un code, PHP/Shell, etc.
				</div>

				<div class="alert alert-info addElementTypeDescription comment" style="display:none;">
					Permet de commenter votre scénario.
				</div>

			</div>
			<div class="modal-footer">
				<a class="btn btn-danger" data-dismiss="modal"><i class="fas fa-minus-circle"></i> {{Annuler}}</a>
				<a class="btn btn-success" id="bt_addElementSave"><i class="fas fa-check-circle"></i> {{Ajouter}}</a>
			</div>
		</div>
	</div>
</div>

<?php
include_file('desktop', 'scenario', 'js');
include_file('3rdparty', 'codemirror/mode/php/php', 'js');
include_file('3rdparty', 'codemirror/addon/selection/active-line', 'js');
include_file('3rdparty', 'codemirror/addon/search/search', 'js');
include_file('3rdparty', 'codemirror/addon/search/searchcursor', 'js');
include_file('3rdparty', 'codemirror/addon/dialog/dialog', 'js');
include_file('3rdparty', 'codemirror/addon/dialog/dialog', 'css');

include_file('3rdparty', 'codemirror/addon/fold/brace-fold', 'js');
include_file('3rdparty', 'codemirror/addon/fold/comment-fold', 'js');
include_file('3rdparty', 'codemirror/addon/fold/foldcode', 'js');
include_file('3rdparty', 'codemirror/addon/fold/indent-fold', 'js');
include_file('3rdparty', 'codemirror/addon/fold/foldgutter', 'js');
include_file('3rdparty', 'codemirror/addon/fold/foldgutter', 'css');
?>