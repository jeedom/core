<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
sendVarToJs('jeedomBackgroundImg', 'core/img/background/scenario.png');
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
<div class="row row-overflow">
	<div id="scenarioThumbnailDisplay" class="col-xs-12">
		<legend><i class="fas fa-cog"></i>  {{Gestion}}</legend>
		<div class="scenarioListContainer">
			<div class="cursor logoPrimary" id="bt_addScenario2">
				<center><i class="fas fa-plus-circle"></i></center>
				<span><center>{{Ajouter}}</center></span>
			</div>
			<?php if (config::byKey('enableScenario') == 0) {?>
				<div class="cursor success" id="bt_changeAllScenarioState2" data-state="1">
					<center><i class="fas fa-check"></i></center>
					<span><center>{{Activer scénarios}}</center></span>
				</div>
			<?php } else {?>
				<div class="cursor danger" id="bt_changeAllScenarioState2" data-state="0">
					<center><i class="fas fa-times"></i></center>
					<span><center>{{Désactiver scénarios}}</center></span>
				</div>
			<?php } ?>
			<div class="cursor logoSecondary" id="bt_displayScenarioVariable2">
				<center><i class="fas fa-eye"></i></center>
				<span><center>{{Voir variables}}</center></span>
			</div>
			<div class="cursor logoSecondary  bt_showScenarioSummary">
				<center><i class="fas fa-list"></i></center>
				<span><center>{{Vue d'ensemble}}</center></span>
			</div>
			<div class="cursor logoSecondary  bt_showExpressionTest">
				<center><i class="fas fa-check"></i></center>
				<span><center>{{Testeur d'expression}}</center></span>
			</div>
		</div>

		<legend><i class="icon jeedom-clap_cinema"></i>  {{Mes scénarios}}</legend>
		<?php
		if (count($totalScenario) == 0) {
			echo "<br/><br/><br/><center><span style='color:#767676;font-size:1.2em;font-weight: bold;'>Vous n'avez encore aucun scénario. Cliquez sur ajouter pour commencer</span></center>";
		} else {
			echo '<div class="input-group" style="margin-bottom:5px;">';
			echo '<input class="form-control roundedLeft" placeholder="{{Rechercher}}" id="in_searchScenario"/>';
			echo '<div class="input-group-btn">';
			echo '<a id="bt_resetScenarioSearch" class="btn" style="width:30px"><i class="fas fa-times"></i> </a>';
			echo '</div>';
			echo '<div class="input-group-btn">';
			echo '<a class="btn" id="bt_openAll"><i class="fas fa-folder-open"></i></a>';
			echo '</div>';
			echo '<div class="input-group-btn">';
			echo '<a class="btn roundedRight" id="bt_closeAll"><i class="fas fa-folder"></i></a>';
			echo '</div>';
			echo '</div>';

			echo '<div class="panel-group" id="accordionScenario">';
			if (count($scenarios[-1]) > 0) {
				echo '<div class="panel panel-default">';
				echo '<div class="panel-heading">';
				echo '<h3 class="panel-title">';
				echo '<a class="accordion-toggle" data-toggle="collapse" data-parent="" aria-expanded="false" href="#config_none">Aucun - ';
				$c = count($scenarios[-1]);
				echo $c. ($c > 1 ? ' scénarios' : ' scénario').'</a>';
				echo '</h3>';
				echo '</div>';
				echo '<div id="config_none" class="panel-collapse collapse">';
				echo '<div class="panel-body">';
				echo '<div class="scenarioListContainer">';
				foreach ($scenarios[-1] as $scenario) {
					$opacity = ($scenario->getIsActive()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
					echo '<div class="scenarioDisplayCard cursor" data-scenario_id="' . $scenario->getId() . '" style="' . $opacity . '" >';
					if($scenario->getDisplay('icon') != ''){
						echo '<span>'.$scenario->getDisplay('icon').'</span>';
					}else{
						echo '<span><i class="icon noicon jeedom-clap_cinema"></i></span>';
					}
					echo "<br>";
					echo '<span class="name">' . $scenario->getHumanName(true, true, true, true) . '</span>';
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
				echo '<a class="accordion-toggle" data-toggle="collapse" data-parent="" aria-expanded="false" href="#config_' . $i . '" style="text-decoration:none;">' . $group['group'] . ' - ';
				$c = count($scenarios[$group['group']]);
				echo $c. ($c > 1 ? ' scénarios' : ' scénario').'</a>';
				echo '</h3>';
				echo '</div>';
				echo '<div id="config_' . $i . '" class="panel-collapse collapse">';
				echo '<div class="panel-body">';
				echo '<div class="scenarioListContainer">';
				foreach ($scenarios[$group['group']] as $scenario) {
					$opacity = ($scenario->getIsActive()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
					echo '<div class="scenarioDisplayCard cursor" data-scenario_id="' . $scenario->getId() . '" style="' . $opacity . '" >';
					if($scenario->getDisplay('icon') != ''){
						echo '<span>'.$scenario->getDisplay('icon').'</span>';
					}else{
						echo '<span><i class="icon noicon jeedom-clap_cinema"></i></span>';
					}
					echo '<br/>';
					echo '<span class="name">' . $scenario->getHumanName(true, true, true, true) . '</span>';
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

	<div id="div_editScenario" class="col-xs-12" style="display: none;" >
		<div class="input-group pull-right" style="display:inline-flex">
			<span class="input-group-btn">
				<span id="span_ongoing" class="label" style="position:relative; margin-right:4px;"></span>
				<a class="btn btn-sm bt_addScenarioElement roundedLeft"><i class="fas fa-plus-circle"></i> {{Ajouter bloc}}
				</a><a class="btn btn-primary btn-sm" id="bt_displayScenarioVariable"><i class="fas fa-eye"></i> {{Variables}}
				</a><a class="btn btn-primary btn-sm bt_showExpressionTest"><i class="fas fa-check"></i> {{Expression}}
				</a><a class="btn btn-sm" id="bt_logScenario" title="{{Log}}"><i class="far fa-file-alt"></i>
				</a><a class="btn btn-sm" id="bt_copyScenario" title="{{Dupliquer}}"><i class="fas fa-copy"></i>
				</a><a class="btn btn-sm" id="bt_graphScenario" title="{{Liens}}"><i class="fas fa-object-group"></i>
				</a><a class="btn btn-sm" id="bt_editJsonScenario" title="{{Edition texte}}"> <i class="far fa-edit"></i>
				</a><a class="btn btn-sm" id="bt_exportScenario" title="{{Exporter}}"><i class="fas fas fa-share"></i>
				</a><a class="btn btn-sm" id="bt_templateScenario" title="{{Template}}"><i class="fas fa-cubes"></i>
				</a><a class="btn btn-warning btn-sm" id="bt_testScenario2" title='{{Veuillez sauvegarder avant de tester. Ceci peut ne pas aboutir.<br>Ctrl+click pour sauvegarder, executer et ouvrir le log}}'><i class="fas fa-gamepad"></i> {{Exécuter}}
				</a><a class="btn btn-danger btn-sm" id="bt_stopScenario"><i class="fas fa-stop"></i> {{Arrêter}}
				</a><a class="btn btn-success btn-sm" id="bt_saveScenario2"><i class="far fa-check-circle"></i> {{Sauvegarder}}
				</a><a class="btn btn-danger btn-sm roundedRight" id="bt_delScenario2"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
			</span>
		</div>
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation"><a class="cursor" aria-controls="home" role="tab" id="bt_scenarioThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
			<li role="presentation" class="active"><a href="#generaltab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Général}} (ID : <span class="scenarioAttr" data-l1key="id" ></span>)</a></li>
			<li role="presentation"><a id="bt_scenarioTab" href="#scenariotab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-filter"></i> {{Scénario}}</a></li>
		</ul>
		<div class="tab-content" style="height:calc(100% - 40px);overflow:auto;overflow-x: hidden;">
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
										{{Actif}}&nbsp;&nbsp;<input type="checkbox" class="scenarioAttr" data-l1key="isActive">
									</label>
									<label>
										{{Visible}}&nbsp;&nbsp;<input type="checkbox" class="scenarioAttr" data-l1key="isVisible">
									</label>
								</div>
								<div class="form-group">
									<label class="col-xs-5 control-label" >{{Objet parent}}</label>
									<div class="col-xs-6">
										<div class="dropdown dynDropdown">
											<button class="btn btn-default dropdown-toggle scenarioAttr" type="button" data-toggle="dropdown" data-l1key="object_id">
												<span class="caret"></span>
											</button>
											<ul class="dropdown-menu dropdown-menu-right scrollable-menu " style="max-height: 450px;">
												<li><a href="#" data-value="">{{Aucun}}</a></li>
												<?php
												foreach (jeeObject::all() as $object) {
													echo '<li><a href="#" data-value="' . $object->getId() . '">' . $object->getName() . '</a></li>';
												}
												?>
											</ul>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-xs-5 control-label">{{Timeout en secondes (0 = illimité)}}</label>
									<div class="col-xs-6">
										<input class="form-control scenarioAttr" data-l1key="timeout">
									</div>
								</div>
								<div class="form-group">
									<label class="col-xs-5 control-label">{{Log}}</label>
									<div class="col-xs-6">
										<div class="dropdown dynDropdown">
											<button class="btn btn-default dropdown-toggle scenarioAttr" type="button" data-toggle="dropdown" data-l1key="configuration" data-l2key="logmode">
												<span class="caret"></span>
											</button>
											<ul class="dropdown-menu dropdown-menu-right">
												<li><a href="#" data-value="default">{{Défaut}}</a></li>
												<li><a href="#" data-value="none">{{Aucun}}</a></li>
												<li><a href="#" data-value="realtime">{{Temps réel}}</a></li>
											</ul>
										</div>
									</div>
								</div>
								<div class="form-group">
									<label class="col-xs-5 control-label">{{Multi-lancement}}</label>
									<div class="col-xs-1">
										<input type="checkbox" class="scenarioAttr" data-l1key="configuration" data-l2key="allowMultiInstance" title="{{Le scénario pourra tourner plusieurs fois en même temps}}">
									</div>
									<label class="col-xs-2 control-label">{{Synchrone}}</label>
									<div class="col-xs-1">
										<input type="checkbox" class="scenarioAttr" data-l1key="configuration" data-l2key="syncmode" title="{{Le scénario est en mode synchrone. Attention, cela peut rendre le système instable}}">
									</div>
								</div>
								<div class="form-group">
									<label class="col-xs-5 control-label">{{Timeline}}</label>
									<div class="col-xs-1">
										<input type="checkbox" class="scenarioAttr" data-l1key="configuration" data-l2key="timeline::enable" title="{{Les exécutions du scénario pourront être vues dans la timeline.}}">
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
							<div class="form-group">
								<div class="col-md-12">
									<textarea class="form-control scenarioAttr ta_autosize" data-l1key="description" placeholder="Description"></textarea>
								</div>
							</div>
							<div class="form-group">
								<label class="col-sm-3 col-xs-6 control-label" >{{Mode du scénario}}</label>
								<div class="col-sm-9 col-xs-6">
									<div class="input-group">
										<div class="dropdown dynDropdown">
											<button class="btn btn-default dropdown-toggle roundedLeft scenarioAttr" type="button" data-toggle="dropdown" data-l1key="mode">
												<span class="caret"></span>
											</button>
											<ul class="dropdown-menu dropdown-menu-right">
												<li><a href="#" data-value="provoke">{{Provoqué}}</a></li>
												<li><a href="#" data-value="schedule">{{Programmé}}</a></li>
												<li><a href="#" data-value="all">{{Les deux}}</a></li>
											</ul>
										</div>
										<span class="input-group-btn">
											<a class="btn btn-default" id="bt_addTrigger"><i class="fas fa-plus-square"></i> {{Déclencheur}}
											</a><a class="btn btn-default roundedRight" id="bt_addSchedule"><i class="fas fa-plus-square"></i> {{Programmation}}</a>
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
				<div id="div_scenarioElement" class="element" style="padding-bottom: 20px;"></div>
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
				<a class="btn btn-danger" data-dismiss="modal"><i class="fas fa-minus-circle"></i> {{Annuler}}</a>
				<a class="btn btn-success" id="bt_copyScenarioSave"><i class="far fa-check-circle"></i> {{Enregistrer}}</a>
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
				<a class="btn btn-success" id="bt_addElementSave"><i class="far fa-check-circle"></i> {{Enregistrer}}</a>
			</div>
		</div>
	</div>
</div>

<?php
include_file('3rdparty', 'jquery.sew/jquery.caretposition', 'js');
include_file('3rdparty', 'jquery.sew/jquery.sew.min', 'js');
include_file('desktop', 'scenario', 'js');

//Core CodeMirror addons:
include_file('3rdparty', 'codemirror/addon/selection/active-line', 'js');
include_file('3rdparty', 'codemirror/addon/search/search', 'js');
include_file('3rdparty', 'codemirror/addon/search/searchcursor', 'js');
include_file('3rdparty', 'codemirror/addon/dialog/dialog', 'js');
include_file('3rdparty', 'codemirror/addon/dialog/dialog', 'css');
?>
