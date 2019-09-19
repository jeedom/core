<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
sendVarToJS('select_id', init('id', '-1'));
$allObject = jeeObject::all();
?>
<div class="row row-overflow">
	<div id="div_resumeObjectList" class="col-xs-12">
		<legend><i class="fas fa-cog"></i>  {{Gestion}}</legend>
		<div class="objectListContainer">
			<div class="cursor logoPrimary" id="bt_addObject2">
				<center>
					<i class="fas fa-plus-circle"></i>
				</center>
				<span class="txtColor"><center>{{Ajouter}}</center></span>
			</div>
			<div class="cursor bt_showObjectSummary logoSecondary" >
				<center>
					<i class="fas fa-list"></i>
				</center>
				<span class="txtColor">{{Vue d'ensemble}}</span>
			</div>
		</div>

		<legend><i class="fas fa-image"></i>  {{Mes objets}}</legend>
		<div class="input-group" style="margin-bottom:5px;">
			<input class="form-control roundedLeft" placeholder="{{Rechercher}}" id="in_searchObject"/>
			<div class="input-group-btn">
				<a id="bt_resetObjectSearch" class="btn roundedRight" style="width:30px"><i class="fas fa-times"></i> </a>
			</div>
		</div>
		<div class="panel">
			<div class="panel-body">
				<div class="objectListContainer">
					<?php
					$echo = '';
					foreach ($allObject as $object) {
						$echo .= '<div class="objectDisplayCard cursor" data-object_id="' . $object->getId() . '" data-object_name="' . $object->getName() . '" data-object_icon=\'' . $object->getDisplay('icon', '<i class="fas fa-lemon-o"></i>') . '\'>';
						$echo .= $object->getDisplay('icon', '<i class="fas fa-lemon-o"></i>');
						$echo .= "<br/>";
						$echo .= '<span class="name">' . $object->getName() . '</span><br/>';
						$echo .= $object->getHtmlSummary();
						$echo .= '</div>';
					}
					echo $echo;
					?>
				</div>
			</div>
		</div>
	</div>

	<div class="col-xs-12 object" style="display: none;" id="div_conf">
		<div class="input-group pull-right" style="display:inline-flex">
			<span class="input-group-btn">
				<a class="btn btn-sm roundedLeft" id="bt_graphObject"><i class="fas fa-object-group"></i> {{Liens}}
				</a><a class="btn btn-success btn-sm" id="bt_saveObject"><i class="far fa-check-circle"></i> {{Sauvegarder}}
				</a><a class="btn btn-danger btn-sm roundedRight" id="bt_removeObject"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
			</span>
		</div>

		<ul class="nav nav-tabs" role="tablist">
			<li role="presentation"><a class="cursor" aria-controls="home" role="tab" id="bt_returnToThumbnailDisplay"><i class="fas fa-arrow-circle-left"></i></a></li>
			<li role="presentation" class="active"><a href="#objecttab" aria-controls="home" role="tab" data-toggle="tab"><i class="fas fa-tachometer-alt"></i> {{Objet}}</a></li>
			<li role="presentation"><a href="#summarytab" aria-controls="profile" role="tab" data-toggle="tab"><i class="fas fa-list-alt"></i> {{Résumé}}</a></li>
		</ul>

		<div class="tab-content" style="height:calc(100% - 50px);overflow:auto;overflow-x: hidden;">
			<div role="tabpanel" class="tab-pane active" id="objecttab">
				<br/>
				<form class="form-horizontal">
					<fieldset>
						<div class="form-group">
							<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Nom de l'objet}}</label>
							<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
								<input class="form-control objectAttr" type="text" data-l1key="id" style="display : none;"/>
								<input class="form-control objectAttr" type="text" data-l1key="name" placeholder="Nom de l'objet"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Père}}</label>
							<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
								<select class="form-control objectAttr" data-l1key="father_id">
									<option value="">{{Aucun}}</option>
									<?php
									foreach ($allObject as $object) {
										echo '<option value="' . $object->getId() . '">' . $object->getName() . '</option>';
									}
									?>
								</select>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Visible}}
								<sup><i class="fas fa-question-circle tooltips" title="{{Rendre cet objet visible ou non.}}"></i></sup>
							</label>
							<div class="col-sm-1">
								<input class="objectAttr" type="checkbox" data-l1key="isVisible" checked/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Masquer sur le dashboard}}
								<sup><i class="fas fa-question-circle tooltips" title="{{Masquer cet objet uniquement sur le dashboard. Il restera visible, notemment dans la liste des objets.}}"></i></sup>
							</label>
							<div class="col-sm-1">
								<input class="objectAttr" type="checkbox" data-l1key="configuration" data-l2key="hideOnDashboard"/>
							</div>
						</div>
						<div class="form-group">
							<div class="tooltipWrapper" style="display:none">
								<span id="objectIconTip">
									{{Activer l'option 'Icônes widgets colorées' dans Interface si nécessaire.}}
									<br><a href="/index.php?v=d&p=administration#interfacetab">Configuration / Interface</a>
								</span>
							</div>
							<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Icône}}
								<sup><i class="fas fa-question-circle tooltips" data-tooltip-content="#objectIconTip"></i></sup>
							</label>
							<div class="col-xs-1">
								<a class="btn btn-default btn-sm" id="bt_chooseIcon"><i class="fas fa-flag"></i> {{Choisir}}</a>
							</div>
							<div class="col-xs-2">
								<div class="objectAttr" data-l1key="display" data-l2key="icon" style="font-size : 1.5em;"></div>
							</div>
						</div>
						<br>
						<div class="form-group">
							<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Couleurs personnalisées}}</label>
							<div class="col-sm-1">
								<input class="objectAttr" type="checkbox" data-l1key="configuration" data-l2key="useCustomColor"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Couleur du tag}}
								<sup><i class="fas fa-question-circle tooltips" title="{{Couleur de l’objet et des équipements qui lui sont rattachés.}}"></i></sup>
							</label>
							<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
								<input type="color" class="objectAttr form-control" data-l1key="display" data-l2key="tagColor" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Couleur du texte du tag}}</label>
							<div class="col-lg-2 col-md-2 col-sm-2 col-xs-2">
								<input type="color" class="objectAttr form-control" data-l1key="display" data-l2key="tagTextColor" />
							</div>
						</div>
						<br>
						<div class="form-group">
							<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Image}}</label>
							<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
								<span class="btn btn-default btn-file">
									<i class="fas fa-cloud-upload-alt"></i> {{Envoyer}}<input  id="bt_uploadImage" type="file" name="file" style="display: inline-block;">
								</span>
								<a class="btn btn-default" id="bt_libraryBackgroundImage"><i class="fas fa-photo-video"></i> {{Bibliotheque d'image}}</a>
								<a class="btn btn-danger" id="bt_removeBackgroundImage"><i class="fas fa-trash"></i> {{Supprimer l'image}}</a>
							</div>
						</div>
						<div class="form-group">
							<div class="col-lg-2 col-md-3 col-sm-4 col-xs-6"></div>
							<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6 objectImg">
								<img src="" width="200px" height="auto" />
							</div>
						</div>
					</fieldset>
				</form>
			</div>
			<div role="tabpanel" class="tab-pane" id="summarytab">
				<?php
				if (count(config::byKey('object:summary')) == 0) {
					echo '<div class="alert alert-danger">{{Vous n\'avez aucun résumé de créé. Allez dans l\'administration de}} ' . config::byKey('product_name') . ' {{-> Configuration -> onglet Résumés.}}</div>';
				} else {

					?>
					<form class="form-horizontal">
						<fieldset>
							<table class="table">
								<thead>
									<tr>
										<th></th>
										<?php
										$echo = '';
										foreach (config::byKey('object:summary') as $key => $value) {
											$echo .= '<th style="cursor:default;">' . $value['name'] . '</th>';
										}
										echo $echo;
										?>
									</tr>
								</thead>
								<?php
								$echo = '';
								$echo .= '<tr>';
								$echo .= '<td style="cursor:default;">';
								$echo .= '{{Remonter dans le résumé global}}';
								$echo .= '</td>';
								foreach (config::byKey('object:summary') as $key => $value) {
									$echo .= '<td>';
									$echo .= '<input type="checkbox" class="objectAttr" data-l1key="configuration" data-l2key="summary::global::' . $key . '" />';
									$echo .= '</td>';
								}
								$echo .= '<td><a class="btn btn-xs bt_checkAll" title="{{Tous}}"><i class="fas fa-square"></i></a> <a class="btn btn-xs bt_checkNone" title="{{Aucun}}"><i class="far fa-square"></i></a></td>';
								$echo .= '</tr>';

								$echo .= '<tr>';
								$echo .= '<td style="cursor:default;">';
								$echo .= '{{Masquer en desktop}}';
								$echo .= '</td>';
								foreach (config::byKey('object:summary') as $key => $value) {
									$echo .= '<td>';
									$echo .= '<input type="checkbox" class="objectAttr" data-l1key="configuration" data-l2key="summary::hide::desktop::' . $key . '" />';
									$echo .= '</td>';
								}
								$echo .= '<td><a class="btn btn-xs bt_checkAll" title="{{Tous}}"><i class="fas fa-square"></i></a> <a class="btn btn-xs bt_checkNone" title="{{Aucun}}"><i class="far fa-square"></i></a></td>';
								$echo .= '</tr>';

								$echo .= '<tr>';
								$echo .= '<td>';
								$echo .= '{{Masquer en mobile}}';
								$echo .= '</td>';
								foreach (config::byKey('object:summary') as $key => $value) {
									$echo .= '<td>';
									$echo .= '<input type="checkbox" class="objectAttr" data-l1key="configuration" data-l2key="summary::hide::mobile::' . $key . '" />';
									$echo .= '</td>';
								}
								$echo .= '<td><a class="btn btn-xs bt_checkAll" title="{{Tous}}"><i class="fas fa-square"></i></a> <a class="btn btn-xs bt_checkNone" title="{{Aucun}}"><i class="far fa-square"></i></a></td>';
								$echo .= '</tr>';

								echo $echo;
								?>
							</table>
						</fieldset>
					</form>
					<form class="form-horizontal">
						<fieldset>
							<legend style="cursor:default;"><i class="fas fa-tachometer-alt"></i>  {{Commandes}}</legend>
							<ul class="nav nav-tabs" role="tablist">
								<?php
								$active = 'active';
								$echo = '';
								foreach (config::byKey('object:summary') as $key => $value) {
									$echo .= '<li class="' . $active . '"><a href="#summarytab' . $key . '" role="tab" data-toggle="tab">' . $value['icon'] . ' ' . $value['name'] . '</i>  <span class="tabnumber summarytabnumber' . $key . '"</span></a></li>';
									$active = '';
								}
								echo $echo;
								?>
							</ul>
							<div class="tab-content">
								<?php
								$active = ' active';
								$echo = '';
								foreach (config::byKey('object:summary') as $key => $value) {
									$echo .=  '<div role="tabpanel" class="tab-pane type' . $key . $active . '" data-type="' . $key . '" id="summarytab' . $key . '">';
									$echo .=  '<a class="btn btn-sm btn-success pull-right addSummary" data-type="' . $key . '"><i class="fas fa-plus-circle"></i> {{Ajouter une commande}}</a>';
									$echo .=  '<br/>';
									$echo .=  '<div class="div_summary" data-type="' . $key . '"></div>';
									$echo .=  '</div>';
									$active = '';
								}
								echo $echo;
								?>
							</div>
						</fieldset>
					</form>
					<?php
				}
				?>
			</div>
		</div>
	</div>
</div>

<?php include_file("desktop", "object", "js");?>
