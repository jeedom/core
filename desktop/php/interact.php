<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}

global $interacts;
$interacts = array();
$totalInteract = interactDef::all();
$interacts[__('Aucun', __FILE__)] = interactDef::all(null);
$interactListGroup = interactDef::listGroup();
if (is_array($interactListGroup)) {
	foreach ($interactListGroup as $group) {
		$interacts[$group['group']] = interactDef::all($group['group']);
	}
}
$optionMaxSize = 15;

function jeedom_displayInteractGroup($_group = '', $_index = -1) {
	global $interacts;
	$thisDiv = '';

	if ($_group == '') {
		$groupName = __('Aucun', __FILE__);
		$href = '#config_none';
		$id = 'config_none';
	} else {
		$groupName = $_group;
		$href = '#config_' . $_index;
		$id = 'config_' . $_index;
	}
	$thisDiv .= '<div class="panel panel-default">';
	$thisDiv .= '<div class="panel-heading">';
	$thisDiv .= '<h3 class="panel-title">';
	$thisDiv .= '<a class="accordion-toggle" data-toggle="collapse" data-parent="" aria-expanded="false" href="' . $href . '">' . $groupName . ' - ';
	$c = count($interacts[$groupName]);
	$thisDiv .= $c . ($c > 1 ? ' interactions' : ' interaction') . '</a>';
	$thisDiv .= '</h3>';
	$thisDiv .= '</div>';
	$thisDiv .= '<div id="' . $id . '" class="panel-collapse collapse">';
	$thisDiv .= '<div class="panel-body">';
	$thisDiv .= '<div class="interactListContainer">';
	foreach ($interacts[$groupName] as $interact) {
		$inactive = ($interact->getEnable()) ? '' : 'inactive';
		$thisDiv .= '<div class="interactDisplayCard cursor ' . $inactive . '" data-interact_id="' . $interact->getId() . '">';
		if ($interact->getDisplay('icon') != '') {
			$thisDiv .= $interact->getDisplay('icon');
		} else {
			$thisDiv .= '<i class="icon noicon far fa-comments"></i>';
		}
		$thisDiv .= "<br>";
		$thisDiv .= '<span class="name">' . $interact->getHumanName() . '</span>';
		$thisDiv .= '<span class="hiddenAsCard displayTableRight">' . $interact->getQuery();
		$thisDiv .= '</div>';
	}
	$thisDiv .= '</div>';
	$thisDiv .= '</div>';
	$thisDiv .= '</div>';
	$thisDiv .= '</div>';
	return $thisDiv;
}

?>

<div class="row row-overflow">
	<div id="interactThumbnailDisplay" class="col-xs-12">
		<legend><i class="fas fa-cog"></i> {{Gestion}}</legend>
		<div class="interactListContainer <?php echo (jeedom::getThemeConfig()['theme_displayAsTable'] == 1) ? ' containerAsTable' : ''; ?>">
			<div class="cursor logoPrimary" id="bt_addInteract2">
				<div class="center">
					<i class="fas fa-plus-circle"></i>
				</div>
				<span class="txtColor">{{Ajouter}}</span>
			</div>
			<div class="cursor logoSecondary" id="bt_regenerateInteract2">
				<div class="center">
					<i class="fas fa-sync"></i>
				</div>
				<span class="txtColor">{{Regénérer}}</span>
			</div>
			<div class="cursor logoSecondary" id="bt_testInteract2">
				<div class="center">
					<i class="fas fa-comment"></i>
				</div>
				<span class="txtColor">{{Tester}}</span>
			</div>
		</div>

		<legend><i class="far fa-comments"></i> {{Mes interactions}} <sub class="itemsNumber"></sub></legend>
		<?php
		if (count($totalInteract) == 0) {
			echo "<br/><br/><br/><div class='center'><span style='color:#767676;font-size:1.2em;font-weight: bold;'>{{Vous n'avez encore aucune interaction. Cliquez sur ajouter pour commencer.}}</span></div>";
		} else {
			$div = '<div class="input-group" style="margin-bottom:5px;">';
			$div .= '<input class="form-control" placeholder="{{Rechercher | nom | :not(nom}}" id="in_searchInteract" />';
			$div .= '<div class="input-group-btn">';
			$div .= '<a id="bt_resetInteractSearch" class="btn" style="width:30px"><i class="fas fa-times"></i> </a>';
			$div .= '</div>';
			$div .= '<div class="input-group-btn">';
			$div .= '<a class="btn" id="bt_openAll"><i class="fas fa-folder-open"></i></a>';
			$div .= '</div>';
			$div .= '<div class="input-group-btn">';
			$div .= '<a class="btn" id="bt_closeAll"><i class="fas fa-folder"></i></a>';
			$div .= '<a class="btn roundedRight" id="bt_displayAsTable" data-card=".interactDisplayCard" data-container=".interactListContainer" data-state="0"><i class="fas fa-grip-lines"></i></a>';
			$div .= '</div>';
			$div .= '</div>';

			$div .= '<div class="panel-group" id="accordionInteract">';
			//No group first:
			if (isset($interacts[__('Aucun', __FILE__)]) && count($interacts[__('Aucun', __FILE__)]) > 0) {
				$div .= jeedom_displayInteractGroup();
			}
			echo $div;

			//interacts groups:
			$i = 0;
			$div = '';
			foreach ($interactListGroup as $group) {
				if ($group['group'] == '') {
					continue;
				}
				$div .= jeedom_displayInteractGroup($group['group'], $i);
				$i += 1;
			}
			$div .= '</div>';
			echo $div;
		}
		?>
	</div>

	<div id="div_conf" class="interact hasfloatingbar col-xs-12" style="display: none;">
		<div class="floatingbar">
			<div class="input-group">
				<span class="input-group-btn">
					<a class="btn displayInteracQuery btn-sm roundedLeft"><i class="fas fa-eye"></i> {{Phrase(s)}} <span class="label label-success interactAttr" data-l1key="nbInteractQuery"></span>
					</a><a class="btn btn-sm" id="bt_duplicate"><i class="fas fa-copy"></i> {{Dupliquer}}
					</a><a class="btn btn-success btn-sm" id="bt_saveInteract"><i class="fas fa-check-circle"></i> {{Sauvegarder}}
					</a><a class="btn btn-danger btn-sm roundedRight" id="bt_removeInteract"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
				</span>
			</div>
		</div>
		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation"><a class="cursor" aria-controls="home" role="tab" id="bt_interactThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
			<li role="presentation" class="active"><a href="#generaltab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Général}}</a></li>
			<li role="presentation"><a href="#filtertab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-filter"></i> {{Filtres}}</a></li>
			<li role="presentation"><a href="#actiontab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-cogs"></i> {{Actions}}</a></li>
		</ul>

		<div class="tab-content">
			<div role="tabpanel" class="tab-pane active" id="generaltab">
				<form class="form-horizontal">
					<br />
					<fieldset>
						<div class="form-group">
							<label class="col-sm-2 col-xs-2 control-label">{{Nom}}</label>
							<div class="col-sm-9 col-xs-9">
								<input class="form-control interactAttr" type="text" data-l1key="name" placeholder="" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 col-xs-2 control-label">{{Groupe}}</label>
							<div class="col-sm-9 col-xs-9">
								<input class="form-control interactAttr" type="text" data-l1key="group" placeholder="{{Groupe de l'interaction}}" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 col-xs-2 control-label">{{Actif}}</label>
							<div class="col-sm-9 col-xs-9">
								<input class="interactAttr" type="checkbox" data-l1key="enable" placeholder="" />
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
								<input class="form-control interactAttr" type="text" data-l1key="id" style="display : none;" />
								<input class="form-control interactAttr" type="text" data-l1key="query" placeholder="" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 col-xs-2 control-label">{{Regexp de correspondance obligatoire}}</label>
							<div class="col-sm-9 col-xs-9">
								<input class="form-control interactAttr" type="text" data-l1key="options" data-l2key="mustcontain" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 col-xs-2 control-label">{{Synonyme}}</label>
							<div class="col-sm-9 col-xs-9">
								<input class="form-control interactAttr" type="text" data-l1key="options" data-l2key="synonymes" placeholder="" title="{{Remplace les mots par leurs synonymes lors de la génération des commandes}}" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 col-xs-2 control-label">{{Réponse}}</label>
							<div class="col-sm-8 col-xs-8">
								<textarea class="form-control interactAttr ta_autosize" type="text" data-l1key="reply" placeholder=""></textarea>
							</div>
							<div class="col-sm-1">
								<a class="btn btn-default btn-sm cursor listEquipementInfoReply" title="{{Rechercher une commande}}"><i class="fas fa-list-alt "></i></a>
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 col-xs-2 control-label">{{Attendre avant de répondre (s)}}</label>
							<div class="col-sm-1 col-xs-9">
								<input type="number" class="form-control interactAttr" type="text" data-l1key="options" data-l2key="waitBeforeReply" placeholder="" title="{{Permet d'attendre le temps que l'état d'une lampe soit mise à jour par exemple}}" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 col-xs-2 control-label">{{Conversion binaire}}</label>
							<div class="col-sm-9 col-xs-9">
								<input class="form-control interactAttr" type="text" data-l1key="options" data-l2key="convertBinary" placeholder="" title="{{Convertir les commandes binaires}}" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-sm-2 col-xs-2 control-label">{{Utilisateurs autorisés}}</label>
							<div class="col-sm-9 col-xs-9">
								<input class="form-control interactAttr" type="text" data-l1key="person" placeholder="" title="{{Liste des utilisateurs (identifiants) séparés par un |}}" />
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
						<div class="form-group">
							<label class="col-sm-2 col-xs-2 control-label">{{Commentaire}}</label>
							<div class="col-sm-8 col-xs-8">
								<textarea class="form-control interactAttr ta_autosize" type="text" data-l1key="comment" placeholder=""></textarea>
							</div>
						</div>
					</fieldset>
				</form>
			</div>

			<div role="tabpanel" class="tab-pane" id="filtertab">
				<br />
				<legend><i class="fas fa-filter"></i> {{Filtrer par :}}</legend>
				<form class="form-horizontal" id="div_filtre">
					<fieldset>
						<div class="form-group">
							<div class="col-sm-12">
								<div class="col-sm-2">
									<label class="control-label"><i class="fas fa-filter"></i> {{Commandes de type}}</label><br /><br />
									<?php
									$size = 0;
									$html = '';
									foreach ((jeedom::getConfiguration('cmd:type')) as $id => $type) {
										$html .= '<option selected="selected" class="interactAttr" data-l1key="filtres" data-l2key="type" data-l3key="' . $id . '">' . $type['name'] . '</option>';
										$size += 1;
									}
									if ($size > $optionMaxSize) $size = $optionMaxSize;
									$html = '<select multiple="true" size="' . $size . '" class="custom-select" style="width:100%">' . $html;
									$html .= '</select>';
									echo $html;
									?>
								</div>
								<div class="col-sm-2">
									<label class="control-label"><i class="fas fa-filter"></i> {{Commandes de sous-type}}</label><br /><br />
									<?php
									$size = 0;
									$html = '';
									foreach ((jeedom::getConfiguration('cmd:type')) as $type) {
										foreach ($type['subtype'] as $id => $subtype) {
											$html .= '<option selected="selected" class="interactAttr" data-l1key="filtres" data-l2key="subtype" data-l3key="' . $id . '">' . $subtype['name'] . '</option>';
											$size += 1;
										}
									}
									if ($size > $optionMaxSize) $size = $optionMaxSize;
									$html = '<select multiple="true" size="' . $size . '" class="custom-select" style="width:100%">' . $html;
									$html .= '</select>';
									echo $html;
									?>
								</div>
								<div class="col-sm-2">
									<label class="control-label"><i class="fas fa-filter"></i> {{Commandes par unité}}</label><br /><br />
									<?php
									$size = 1;
									$html = '<option selected="selected" class="interactAttr" data-l1key="filtres" data-l2key="unite" data-l3key="none">{{Sans unité}}</option>';
									foreach ((cmd::allUnite()) as $unite) {
										if (trim($unite['unite']) == '') {
											continue;
										}
										$html .= '<option selected="selected" class="interactAttr" data-l1key="filtres" data-l2key="unite" data-l3key="' . $unite['unite'] . '">' . $unite['unite'] . '</option>';
										$size += 1;
									}
									if ($size > $optionMaxSize) $size = $optionMaxSize;
									$html = '<select multiple="true" size="' . $size . '" class="custom-select" style="width:100%">' . $html;
									$html .= '</select>';
									echo $html;
									?>
								</div>
								<div class="col-sm-2">
									<label class="control-label"><i class="fas fa-filter"></i> {{Commandes des objets}}</label><br /><br />
									<?php
									$size = 0;
									$html = '';
									foreach ((jeeObject::all()) as $object) {
										$html .= '<option selected="selected" class="interactAttr" data-l1key="filtres" data-l2key="object" data-l3key="' . $object->getId() . '">' . $object->getName() . '</option>';
										$size += 1;
									}
									if ($size > $optionMaxSize) $size = $optionMaxSize;
									$html = '<select multiple="true" size="' . $size . '" class="custom-select" style="width:100%">' . $html;
									$html .= '</select>';
									echo $html;
									?>
								</div>
								<div class="col-sm-2">
									<label class="control-label"><i class="fas fa-filter"></i> {{Plugins}}</label><br /><br />
									<?php
									$size = 0;
									$html = '';
									foreach ((eqLogic::allType()) as $type) {
										$html .= '<option selected="selected" class="interactAttr" data-l1key="filtres" data-l2key="plugin" data-l3key="' . $type['type'] . '">' . $type['type'] . '</option>';
										$size += 1;
									}
									if ($size > $optionMaxSize) $size = $optionMaxSize;
									$html = '<select multiple="true" size="' . $size . '" class="custom-select" style="width:100%">' . $html;
									$html .= '</select>';
									echo $html;
									?>
								</div>
								<div class="col-sm-2">
									<label class="control-label"><i class="fas fa-filter"></i> {{Catégories}}</label><br /><br />
									<?php
									$size = 1;
									$html = '<option selected="selected" class="interactAttr" data-l1key="filtres" data-l2key="category" data-l3key="noCategory">{{Sans catégorie}}</option>';
									foreach ((jeedom::getConfiguration('eqLogic:category')) as $id => $category) {
										$html .= '<option selected="selected" class="interactAttr" data-l1key="filtres" data-l2key="category" data-l3key="' . $id . '">' . $category['name'] . '</option>';
										$size += 1;
									}
									if ($size > $optionMaxSize) $size = $optionMaxSize;
									$html = '<select multiple="true" size="' . $size . '" class="custom-select" style="width:100%">' . $html;
									$html .= '</select>';
									echo $html;
									?>
								</div>
							</div>
						</div>

						<div class="form-group">
							<div class="col-sm-12">
								<div class="col-sm-2">
									<label class="control-label"><i class="fas fa-filter"></i> {{Visibles}}</label><br /><br />
									<?php
									$size = 0;
									$html = '';
									foreach (array('object' => 'Objets', 'eqlogic' => 'Equipements', 'cmd' => 'Commandes') as $id => $name) {
										$html .= '<option selected="selected" class="interactAttr" data-l1key="filtres" data-l2key="visible" data-l3key="' . $id . '">' . $name . '</option>';
										$size += 1;
									}
									if ($size > $optionMaxSize) $size = $optionMaxSize;
									$html = '<select multiple="true" size="' . $size . '" class="custom-select" style="width:100%">' . $html;
									$html .= '</select>';
									echo $html;
									?>
								</div>
								<div class="col-sm-4">
									<label class="control-label"><i class="fas fa-filter"></i> {{Equipement}}</label><br /><br />
									<select class='interactAttr form-control' data-l1key='filtres' data-l2key='eqLogic_id'>
										<option value="all">{{Tous}}</option>
										<?php
										foreach ((eqLogic::all()) as $eqLogic) {
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
				<br /><br />
				<form class="form-horizontal">
					<fieldset>

						<div id="div_action"></div>
					</fieldset>
				</form>
			</div>
		</div>
	</div>
</div>

<?php include_file('desktop', 'interact', 'js'); ?>