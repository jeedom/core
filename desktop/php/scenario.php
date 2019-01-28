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
.CodeMirror {
	border: 1px solid #eee;
	height: auto;
}
</style>

<div class="row row-overflow">
	<div id="scenarioThumbnailDisplay" class="col-xs-12" style="border-left: solid 1px #EEE; padding-left: 25px;">
		<div class="scenarioListContainer">
			<legend><i class="fas fa-cog"></i>  {{Gestion}}</legend>
			<div class="cursor" id="bt_addScenario2" style="text-align: center; height : 100px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 170px;margin-left : 10px;" >
				<i class="fas fa-plus-circle" style="font-size : 5em;color:#94ca02;"></i>
				<br>
				<span style="position:relative; top : 5px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#94ca02">{{Ajouter}}</span>
			</div>
			<?php if (config::byKey('enableScenario') == 0) {?>
				<div class="cursor" id="bt_changeAllScenarioState2" data-state="1" style="text-align: center; height : 100px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 170px;margin-left : 10px;" >
					<i class="fas fa-check" style="font-size : 5em;color:#5cb85c;"></i>
					<br>
					<span style="position:relative; top : 5px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#5cb85c">{{Activer scénarios}}</span>
				</div>
			<?php } else {?>
				<div class="cursor" id="bt_changeAllScenarioState2" data-state="0" style="text-align: center; height : 100px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 170px;margin-left : 10px;" >
					<i class="fas fa-times" style="font-size : 5em;color:#d9534f;"></i>
					<br>
					<span style="position:relative; top : 5px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#d9534f">{{Désactiver scénarios}}</span>
				</div>
			<?php } ?>
			<div class="cursor" id="bt_displayScenarioVariable2" style="text-align: center; height : 100px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 170px;margin-left : 10px;" >
				<i class="fas fa-eye" style="font-size : 5em;color:#337ab7;"></i>
				<br>
				<span style="position:relative; top : 5px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#337ab7">{{Voir variables}}</span>
			</div>
			<div class="cursor bt_showScenarioSummary" style="text-align: center; height : 100px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 170px;margin-left : 10px;" >
				<i class="fas fa-list" style="font-size : 5em;color:#337ab7;"></i>
				<br>
				<span style="position:relative; top : 5px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#337ab7">{{Vue d'ensemble}}</span>
			</div>
			<div class="cursor bt_showExpressionTest" style="text-align: center; height : 100px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 170px;margin-left : 10px;" >
				<i class="fas fa-check" style="font-size : 5em;color:#337ab7;"></i>
				<br>
				<span style="position:relative; top : 5px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#337ab7">{{Testeur d'expression}}</span>
			</div>
		</div>
		
		<legend><i class="icon jeedom-clap_cinema"></i>  {{Mes scénarios}}</legend>
		<?php
		if (count($totalScenario) == 0) {
			echo "<br/><br/><br/><center><span style='color:#767676;font-size:1.2em;font-weight: bold;'>Vous n'avez encore aucun scénario. Cliquez sur ajouter pour commencer</span></center>";
		} else {
			echo '<input class="form-control" placeholder="{{Rechercher}}" style="margin-bottom:4px;" id="in_searchScenario" />';
			echo '<div class="panel-group" id="accordionScenar">';
			if (count($scenarios[-1]) > 0) {
				echo '<div class="panel panel-default">';
				echo '<div class="panel-heading">';
				echo '<h3 class="panel-title">';
				echo 'Aucun - ' . count($scenarios[-1]) . ' scénario(s)';
				echo '</h3>';
				echo '</div>';
				echo '<div class="panel-body">';
				echo '<div class="scenarioListContainer">';
				foreach ($scenarios[-1] as $scenario) {
					$opacity = ($scenario->getIsActive()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
					echo '<div class="scenarioDisplayCard cursor" data-scenario_id="' . $scenario->getId() . '" style="text-align: center; min-height : 85px;margin-bottom : 5px;padding : 5px;border-radius: 2px;width : 130px;margin-left : 10px;' . $opacity . '" >';
					if($scenario->getDisplay('icon') != ''){
						echo '<span style="font-size:3em;"'.$scenario->getDisplay('icon').'</span>';
					}else{
						echo '<img src="core/img/scenario.png" height="45" width="40" />';
					}
					echo "<br>";
					echo '<span class="name" style="position:relative; word-break: break-all;white-space: pre-wrap;word-wrap: break-word;">' . $scenario->getHumanName(true, true, true, true) . '</span>';
					echo '</div>';
				}
				echo '</div>';
				echo '</div>';
				echo '</div>';
			}
			foreach ($scenarioListGroup as $group) {
				if ($group['group'] == '') {
					continue;
				}
				echo '<div class="panel panel-default">';
				echo '<div class="panel-heading">';
				echo '<h3 class="panel-title">';
				echo $group['group'] . ' - ' . count($scenarios[$group['group']]) . ' scénario(s)';
				echo '</h3>';
				echo '</div>';
				echo '<div class="panel-body">';
				echo '<div class="scenarioListContainer">';
				foreach ($scenarios[$group['group']] as $scenario) {
					$opacity = ($scenario->getIsActive()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
					echo '<div class="scenarioDisplayCard cursor" data-scenario_id="' . $scenario->getId() . '" style="text-align: center; min-height : 85px;margin-bottom : 5px;padding : 5px;border-radius: 2px;width : 130px;margin-left : 10px;' . $opacity . '" >';
					if($scenario->getDisplay('icon') != ''){
						echo '<span style="font-size:3em;"'.$scenario->getDisplay('icon').'</span>';
					}else{
						echo '<img src="core/img/scenario.png" height="45" width="40" />';
					}
					echo '<br/>';
					echo '<span class="name" style="position:relative;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;">' . $scenario->getHumanName(true, true, true, true) . '</span>';
					echo '</div>';
				}
				echo '</div>';
				echo '</div>';
				echo '</div>';
			}
			echo '</div>';
		}
		?>
	</div>
	
	<div id="div_editScenario" class="col-xs-12" style="display: none;" >
		<a class="btn btn-default btn-sm pull-right" id="bt_graphScenario" title="{{Liens}}"><i class="fas fa-object-group"></i></a>
		<a class="btn btn-default btn-sm pull-right" id="bt_copyScenario" title="{{Dupliquer}}"><i class="fas fa-copy"></i></a>
		<a class="btn btn-default btn-sm pull-right" id="bt_logScenario" title="{{Log}}"><i class="far fa-file-alt"></i></a>
		<a class="btn btn-default btn-sm pull-right" id="bt_exportScenario" title="{{Exporter}}"><i class="fas fa fa-share"></i></a>
		<a class="btn btn-danger btn-sm pull-right" id="bt_stopScenario"><i class="fas fa-stop"></i> {{Arrêter}}</a>
		<a class="btn btn-default btn-sm pull-right" id="bt_templateScenario" title="{{Template}}"><i class="fas fa-cubes"></i></a>
		<a class="btn btn-default btn-sm pull-right" id="bt_editJsonScenario" title="{{Edition texte}}"> <i class="far fa-edit"></i></a>
		<a class="btn btn-success btn-sm pull-right" id="bt_saveScenario2"><i class="far fa-check-circle"></i> {{Sauvegarder}}</a>
		<a class="btn btn-danger btn-sm pull-right" id="bt_delScenario2"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
		<a class="btn btn-warning btn-sm pull-right" id="bt_testScenario2" title='{{Veuillez sauvegarder avant de tester. Ceci peut ne pas aboutir.}}'><i class="fas fa-gamepad"></i> {{Exécuter}}</a>
		<a class="btn btn-primary btn-sm pull-right bt_showExpressionTest"><i class="fas fa-check"></i> {{Expression}}</a>
		<a class="btn btn-primary btn-sm pull-right" id="bt_displayScenarioVariable"><i class="fas fa-eye"></i> {{Variables}}</a>
		<a class="btn btn-default btn-sm pull-right bt_addScenarioElement"><i class="fas fa-plus-circle"></i> {{Ajouter bloc}}</a>
		<span id="span_ongoing" class="label pull-right" style="font-size : 1em;position:relative;top:5px;"></span>
		
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation"><a class="cursor" aria-controls="home" role="tab" id="bt_scenarioThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
			<li role="presentation" class="active"><a href="#generaltab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Général}} (ID : <span class="scenarioAttr" data-l1key="id" ></span>)</a></li>
			<li role="presentation"><a id="bt_scenarioTab" href="#scenariotab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-filter"></i> {{Scénario}}</a></li>
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
										foreach (jeeObject::all() as $object) {
											echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
										}
										?>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-xs-5 control-label">{{Timeout en secondes (0 = illimité)}}</label>
								<div class="col-xs-6">
									<input class="form-control scenarioAttr" data-l1key="timeout">
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
								<label class="col-xs-2 control-label">{{Timeline}}</label>
								<div class="col-xs-1">
									<input type="checkbox" class="scenarioAttr" data-l1key="configuration" data-l2key="timeline::enable" title="{{Les exécutions du scénario pourront être vues dans la timeline.}}">
								</div>
							</div>
							<div class="form-group">
								<label class="col-xs-5 control-label">{{Log}}</label>
								<div class="col-xs-6">
									<select class="scenarioAttr form-control" data-l1key="configuration" data-l2key="logmode">
										<option value="default">{{Défaut}}</option>
										<option value="none">{{Aucun}}</option>
										<option value="realtime">{{Temps réel}}</option>
									</select>
								</div>
							</div>
							<div class="form-group">
								<label class="col-xs-5 control-label">{{Icône}}</label>
								<div class="col-xs-3">
									<div class="scenarioAttr" data-l1key="display" data-l2key="icon" style="font-size : 1.5em;"></div>
								</div>
								<div class="col-xs-4">
									<a class="btn btn-default btn-sm" id="bt_chooseIcon"><i class="fas fa-flag"></i> {{Choisir}}</a>
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
										<a class="btn btn-default btn-sm" id="bt_addTrigger"><i class="fas fa-plus-square"></i> {{Déclencheur}}</a>
										<a class="btn btn-default btn-sm" id="bt_addSchedule"><i class="fas fa-plus-square"></i> {{Programmation}}</a>
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
include_file('desktop', 'scenario', 'js');
include_file('3rdparty', 'jquery.sew/jquery.caretposition', 'js');
include_file('3rdparty', 'jquery.sew/jquery.sew.min', 'js');
?>
