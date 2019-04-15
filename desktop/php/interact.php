<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
sendVarToJs('jeedomBackgroundImg', 'core/img/background/interact.png');
$interacts = array();
$totalInteract = interactDef::all();
$interacts[-1] = interactDef::all(null);
$interactListGroup = interactDef::listGroup();
if (is_array($interactListGroup)) {
	foreach ($interactListGroup as $group) {
		$interacts[$group['group']] = interactDef::all($group['group']);
	}
}
$optionMaxSize = 15;
?>
<div class="row row-overflow">
	<div id="interactThumbnailDisplay" class="col-xs-12">
		<legend><i class="fas fa-cog"></i> {{Gestion}}</legend>
		<div class="interactListContainer">
			<div class="cursor logoPrimary" id="bt_addInteract2">
				<center>
					<i class="fas fa-plus-circle"></i>
				</center>
				<span><center>{{Ajouter}}</center></span>
			</div>
			<div class="cursor logoSecondary" id="bt_regenerateInteract2">
				<center>
					<i class="fas fa-sync"></i>
				</center>
				<span><center>{{Regénérer}}</center></span>
			</div>
			<div class="cursor logoSecondary" id="bt_testInteract2">
				<center>
					<i class="fas fa-comment"></i>
				</center>
				<span ><center>{{Tester}}</center></span>
			</div>
		</div>
		
		<legend><i class="far fa-comments"></i> {{Mes interactions}}</legend>
		<?php
		if (count($totalInteract) == 0) {
			echo "<br/><br/><br/><center><span style='color:#767676;font-size:1.2em;font-weight: bold;'>Vous n'avez encore aucune interaction. Cliquez sur ajouter pour commencer.</span></center>";
		} else {
			echo '<div class="input-group" style="margin-bottom:5px;">';
			echo '<input class="form-control" placeholder="{{Rechercher}}" id="in_searchInteract" />';
			echo '<div class="input-group-btn">';
			echo '<a id="bt_resetInteractSearch" class="btn" style="width:30px"><i class="fas fa-times"></i> </a>';
			echo '</div>';
			echo '<div class="input-group-btn">';
			echo '<a class="btn" id="bt_openAll"><i class="fas fa-folder-open"></i></a>';
			echo '</div>';
			echo '<div class="input-group-btn">';
			echo '<a class="btn roundedRight" id="bt_closeAll"><i class="fas fa-folder"></i></a>';
			echo '</div>';
			echo '</div>';
			
			echo '<div class="panel-group" id="accordionInteract">';
			if (count($interacts[-1]) > 0) {
				echo '<div class="panel panel-default">';
				echo '<div class="panel-heading">';
				echo '<h3 class="panel-title">';
				echo '<a class="accordion-toggle" data-toggle="collapse" data-parent="" aria-expanded="false" href="#config_aucun">Aucun - ';
				$c = count($interacts[-1]);
				echo $c. ($c > 1 ? ' interactions' : ' interaction').'</a>';
				echo '</h3>';
				echo '</div>';
				echo '<div id="config_aucun" class="panel-collapse collapse">';
				echo '<div class="panel-body">';
				echo '<div class="interactListContainer">';
				foreach ($interacts[-1] as $interact) {
					$opacity = ($interact->getEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
					echo '<div class="interactDisplayCard cursor" data-interact_id="' . $interact->getId() . '" style="'.$opacity.'" >';
					if($interact->getDisplay('icon') != ''){
						echo '<span>'.$interact->getDisplay('icon').'</span>';
					}else{
						echo '<span><i class="icon noicon far fa-comments"></i></span>';
					}
					echo "<br>";
					echo '<span class="name">' . $interact->getHumanName(true, true, true, true) . '</span>';
					echo '</div>';
				}
				echo '</div>';
				echo '</div>';
				echo '</div>';
				echo '</div>';
			}
			$i = 0;
			foreach ($interactListGroup as $group) {
				if ($group['group'] != '') {
					echo '<div class="panel panel-default">';
					echo '<div class="panel-heading">';
					echo '<h3 class="panel-title">';
					echo '<a class="accordion-toggle" data-toggle="collapse" data-parent="" aria-expanded="false" href="#config_' . $i . '">' . $group['group'] . ' - ';
					$c = count($interacts[$group['group']]);
					echo $c. ($c > 1 ? ' interactions' : ' interaction').'</a>';
					echo '</h3>';
					echo '</div>';
					echo '<div id="config_' . $i . '" class="panel-collapse collapse">';
					echo '<div class="panel-body">';
					echo '<div class="interactListContainer">';
					foreach ($interacts[$group['group']] as $interact) {
						$opacity = ($interact->getEnable()) ? '' : jeedom::getConfiguration('eqLogic:style:noactive');
						echo '<div class="interactDisplayCard cursor" data-interact_id="' . $interact->getId() . '" style="'.$opacity.'" >';
						if($interact->getDisplay('icon') != ''){
							echo '<span>'.$interact->getDisplay('icon').'</span>';
						}else{
							echo '<span><i class="icon noicon far fa-comments"></i></span>';
						}
						echo "<br>";
						echo '<span class="name">' . $interact->getHumanName(true, true, true, true) . '</span>';
						echo '</div>';
					}
					echo '</div>';
					echo '</div>';
					echo '</div>';
					echo '</div>';
				}
				$i += 1;
			}
			echo '</div>';
		}
		?>
	</div>
	
	<div class="interact col-xs-12" style="display: none;" id="div_conf">
		<div class="input-group pull-right" style="display:inline-flex">
			<span class="input-group-btn">
				<a class="btn displayInteracQuery btn-sm roundedLeft"><i class="fas fa-eye"></i> {{Phrase(s)}} <span class="label label-success interactAttr" data-l1key="nbInteractQuery"></span>
				</a><a class="btn btn-sm" id="bt_duplicate"><i class="far fa-files-o"></i> {{Dupliquer}}
				</a><a class="btn btn-success btn-sm" id="bt_saveInteract"><i class="far fa-check-circle"></i> {{Sauvegarder}}
				</a><a class="btn btn-danger btn-sm roundedRight" id="bt_removeInteract"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
			</span>
		</div>
		
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation"><a class="cursor" aria-controls="home" role="tab" id="bt_interactThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
			<li role="presentation" class="active"><a href="#generaltab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Général}}</a></li>
			<li role="presentation"><a href="#filtertab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-filter"></i> {{Filtres}}</a></li>
			<li role="presentation"><a href="#actiontab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-cogs"></i> {{Actions}}</a></li>
		</ul>
	</ul>
	
	<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
		<div role="tabpanel" class="tab-pane active" id="generaltab">
			<form class="form-horizontal">
				<br/>
				<fieldset>
					<div class="form-group">
						<label class="col-sm-2 col-xs-2 control-label">{{Nom}}</label>
						<div class="col-sm-9 col-xs-9">
							<input class="form-control interactAttr" type="text" data-l1key="name" placeholder=""/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 col-xs-2 control-label">{{Groupe}}</label>
						<div class="col-sm-9 col-xs-9">
							<input class="form-control interactAttr" type="text" data-l1key="group" placeholder=""/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 col-xs-2 control-label">{{Actif}}</label>
						<div class="col-sm-9 col-xs-9">
							<input class="interactAttr" type="checkbox" data-l1key="enable" placeholder=""/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 col-xs-2 control-label">{{Icône}}</label>
						<div class="col-sm-1 col-xs-1">
							<a class="btn btn-default btn-sm" id="bt_chooseIcon"><i class="fas fa-flag"></i> {{Choisir}}</a>
						</div>
						<div class="col-sm-1 col-xs-1">
							<div class="interactAttr" data-l1key="display" data-l2key="icon" style="font-size : 1.5em;"></div>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 col-xs-2 control-label">{{Demande}}</label>
						<div class="col-sm-9 col-xs-9">
							<input class="form-control interactAttr" type="text" data-l1key="id" style="display : none;"/>
							<input class="form-control interactAttr" type="text" data-l1key="query" placeholder=""/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 col-xs-2 control-label">{{Regexp de correspondance obligatoire}}</label>
						<div class="col-sm-9 col-xs-9">
							<input class="form-control interactAttr" type="text" data-l1key="options" data-l2key="mustcontain"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 col-xs-2 control-label">{{Synonyme}}</label>
						<div class="col-sm-9 col-xs-9">
							<input class="form-control interactAttr" type="text" data-l1key="options" data-l2key="synonymes" placeholder="" title="{{Remplace les mots par leurs synonymes lors de la génération des commandes}}"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 col-xs-2 control-label">{{Réponse}}</label>
						<div class="col-sm-8 col-xs-8">
							<textarea class="form-control interactAttr ta_autosize" type="text" data-l1key="reply" placeholder=""></textarea>
						</div>
						<div class="col-sm-1">
							<a class="btn btn-default cursor listEquipementInfoReply input-sm" title="Rechercher une commande"><i class="fas fa-list-alt "></i></a>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 col-xs-2 control-label">{{Attendre avant de répondre (s)}}</label>
						<div class="col-sm-1 col-xs-9">
							<input type="number" class="form-control interactAttr" type="text" data-l1key="options" data-l2key="waitBeforeReply" placeholder="" title="{{Permet d'attendre le temps que l'état d'une lampe soit mise à jour par exemple}}"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 col-xs-2 control-label">{{Conversion binaire}}</label>
						<div class="col-sm-9 col-xs-9">
							<input class="form-control interactAttr" type="text" data-l1key="options" data-l2key="convertBinary" placeholder="" title="{{Convertir les commandes binaires}}"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 col-xs-2 control-label">{{Utilisateurs autorisés}}</label>
						<div class="col-sm-9 col-xs-9">
							<input class="form-control interactAttr" type="text" data-l1key="person" placeholder="" title="{{Liste des utilisateurs (identifiants) séparés par un |}}"/>
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 col-xs-2 control-label">{{Regexp d'exclusion}}</label>
						<div class="col-sm-9 col-xs-9">
							<input class="form-control interactAttr" type="text" data-l1key="options" data-l2key="exclude_regexp" placeholder="" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-xs-2 control-label">{{Autoriser Jeedom à supprimer les demandes syntaxiquement incorrectes}}</label>
						<div class="col-xs-3">
							<input type="checkbox" class="interactAttr" data-l1key="options" data-l2key="allowSyntaxCheck">
						</div>
					</div>
				</fieldset>
			</form>
		</div>
		
		<div role="tabpanel" class="tab-pane" id="filtertab">
			<br/>
			<legend><i class="fas fa-filter"></i> {{Filtrer par :}}</legend>
			<form class="form-horizontal" id="div_filtre">
				<fieldset>
					<div class="form-group">
						<div class="col-sm-12">
							<div class="col-sm-2">
								<label class="control-label"><i class="fas fa-filter"></i> {{Commandes de type}}</label><br/><br/>
								<?php
								$size = 0;
								$html = '';
								foreach (jeedom::getConfiguration('cmd:type') as $id => $type) {
									$html .= '<option selected="selected" class="interactAttr" data-l1key="filtres" data-l2key="type" data-l3key="'.$id.'">'.$type['name'].'</option>';
									$size += 1;
								}
								if ($size > $optionMaxSize) $size = $optionMaxSize;
								$html = '<select multiple="multiple" size="'.$size.'" class="custom-select" style="width:100%">'.$html;
								$html .= '</select>';
								echo $html;
								?>
							</div>
							<div class="col-sm-2">
								<label class="control-label"><i class="fas fa-filter"></i> {{Commandes de sous-type}}</label><br/><br/>
								<?php
								$size = 0;
								$html = '';
								foreach (jeedom::getConfiguration('cmd:type') as $type) {
									foreach ($type['subtype'] as $id => $subtype) {
										$html .= '<option selected="selected" class="interactAttr" data-l1key="filtres" data-l2key="subtype" data-l3key="'.$id.'">'.$subtype['name'].'</option>';
										$size += 1;
									}
								}
								if ($size > $optionMaxSize) $size = $optionMaxSize;
								$html = '<select multiple="multiple" size="'.$size.'" class="custom-select" style="width:100%">'.$html;
								$html .= '</select>';
								echo $html;
								?>
							</div>
							<div class="col-sm-2">
								<label class="control-label"><i class="fas fa-filter"></i> {{Commandes par unité}}</label><br/><br/>
								<?php
								$size = 1;
								$html = '<option selected="selected" class="interactAttr" data-l1key="filtres" data-l2key="unite" data-l3key="none">{{Sans unité}}</option>';
								foreach (cmd::allUnite() as $unite) {
									if (trim($unite['unite']) == '') {
										continue;
									}
									$html .= '<option selected="selected" class="interactAttr" data-l1key="filtres" data-l2key="unite" data-l3key="'.$unite['unite'].'">'.$unite['unite'].'</option>';
									$size += 1;
								}
								if ($size > $optionMaxSize) $size = $optionMaxSize;
								$html = '<select multiple="multiple" size="'.$size.'" class="custom-select" style="width:100%">'.$html;
								$html .= '</select>';
								echo $html;
								?>
							</div>
							<div class="col-sm-2">
								<label class="control-label"><i class="fas fa-filter"></i> {{Commandes des objets}}</label><br/><br/>
								<?php
								$size = 0;
								$html = '';
								foreach (jeeObject::all() as $object) {
									$html .= '<option selected="selected" class="interactAttr" data-l1key="filtres" data-l2key="object" data-l3key="'.$object->getId().'">'.$object->getName().'</option>';
									$size += 1;
								}
								if ($size > $optionMaxSize) $size = $optionMaxSize;
								$html = '<select multiple="multiple" size="'.$size.'" class="custom-select" style="width:100%">'.$html;
								$html .= '</select>';
								echo $html;
								?>
							</div>
							<div class="col-sm-2">
								<label class="control-label"><i class="fas fa-filter"></i> {{Plugins}}</label><br/><br/>
								<?php
								$size = 0;
								$html = '';
								foreach (eqLogic::allType() as $type) {
									$html .= '<option selected="selected" class="interactAttr" data-l1key="filtres" data-l2key="plugin" data-l3key="'.$type['type'].'">'.$type['type'].'</option>';
									$size += 1;
								}
								if ($size > $optionMaxSize) $size = $optionMaxSize;
								$html = '<select multiple="multiple" size="'.$size.'" class="custom-select" style="width:100%">'.$html;
								$html .= '</select>';
								echo $html;
								?>
							</div>
							<div class="col-sm-2">
								<label class="control-label"><i class="fas fa-filter"></i> {{Catégories}}</label><br/><br/>
								<?php
								$size = 1;
								$html = '<option selected="selected" class="interactAttr" data-l1key="filtres" data-l2key="category" data-l3key="noCategory">{{Sans catégorie}}</option>';
								foreach (jeedom::getConfiguration('eqLogic:category') as $id => $category) {
									$html .= '<option selected="selected" class="interactAttr" data-l1key="filtres" data-l2key="category" data-l3key="'.$id.'">'.$category['name'].'</option>';
									$size += 1;
								}
								if ($size > $optionMaxSize) $size = $optionMaxSize;
								$html = '<select multiple="multiple" size="'.$size.'" class="custom-select" style="width:100%">'.$html;
								$html .= '</select>';
								echo $html;
								?>
							</div>
						</div>
					</div>
					
					<div class="form-group">
						<div class="col-sm-12">
							<div class="col-sm-2">
								<label class="control-label"><i class="fas fa-filter"></i> {{Visibles}}</label><br/><br/>
								<?php
								$size = 0;
								$html = '';
								foreach (array('object' => 'Objets', 'eqlogic' => 'Equipements', 'cmd' => 'Commandes') as $id => $name) {
									$html .= '<option selected="selected" class="interactAttr" data-l1key="filtres" data-l2key="visible" data-l3key="'.$id.'">'.$name.'</option>';
									$size += 1;
								}
								if ($size > $optionMaxSize) $size = $optionMaxSize;
								$html = '<select multiple="multiple" size="'.$size.'" class="custom-select" style="width:100%">'.$html;
								$html .= '</select>';
								echo $html;
								?>
							</div>
							<div class="col-sm-4">
								<label class="control-label"><i class="fas fa-filter"></i> {{Equipement}}</label><br/><br/>
								<select class='interactAttr form-control' data-l1key='filtres' data-l2key='eqLogic_id' >
									<option value="all">{{Tous}}</option>
									<?php
									foreach (eqLogic::all() as $eqLogic) {
										echo '<option value="' . $eqLogic->getId() . '" >' . $eqLogic->getHumanName() . '</option>';
									}
									?>
								</select>
							</div>
						</div>
					</div>
				</fieldset>
			</form>
		</div>
		
		<div role="tabpanel" class="tab-pane" id="actiontab">
			<a class="btn btn-success btn-sm pull-right" id="bt_addAction" style="margin-top:5px;"><i class="fas fa-plus-circle"></i> {{Ajouter}}</a>
			<br/><br/>
			<form class="form-horizontal">
				<fieldset>
					
					<div id="div_action"></div>
				</fieldset>
			</form>
		</div>
	</div>
	
	<?php include_file('desktop', 'interact', 'js');?>
	