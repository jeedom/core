<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
sendVarToJs('jeedomBackgroundImg', 'core/img/background/object.png');
sendVarToJS('select_id', init('id', '-1'));
$allObject = jeeObject::buildTree(null, false);
?>
<div class="row row-overflow">
	<div id="div_resumeObjectList" class="col-xs-12" style="border-left: solid 1px #EEE; padding-left: 25px;">
		<legend><i class="fas fa-cog"></i>  {{Gestion}}</legend>
		<div class="objectListContainer">
			<div class="cursor" id="bt_addObject2" style=" height : 160px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
				<br/>
				<center>
					<i class="fas fa-plus-circle" style="font-size : 5em;color:#94ca02;margin-top:5px;"></i>
				</center>
				<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#94ca02"><center>Ajouter</center></span>
			</div>
			<div class="cursor bt_showObjectSummary" style="text-align: center;  height : 160px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >
				<br/>
				<center>
					<i class="fas fa-list" style="font-size : 5em;color:#337ab7;margin-top:5px;"></i>
				</center>
				<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;color:#337ab7">{{Vue d'ensemble}}</span>
			</div>
		</div>

		<legend><i class="fas fa-picture-o"></i>  {{Mes objets}}</legend>
		<input class="form-control" placeholder="{{Rechercher}}" style="margin-bottom:4px;" id="in_searchObject" />
		<div class="objectListContainer">
			<?php
			foreach ($allObject as $object) {
				echo '<div class="objectDisplayCard cursor w-icons" data-object_id="' . $object->getId() . '" data-object_name="' . $object->getName() . '" data-object_icon=\'' . $object->getDisplay('icon', '<i class="fas fa-lemon-o"></i>') . '\' style=" height : 160px;margin-bottom : 10px;padding : 5px;border-radius: 2px;width : 160px;margin-left : 10px;" >';
				echo "<center style='margin-top:10px;'>";
				echo str_replace('></i>', ' style="font-size : 5em;color:#767676;"></i>', $object->getDisplay('icon', '<i class="fas fa-lemon-o"></i>'));
				echo "</center>";
				echo '<span style="font-size : 1.1em;position:relative; top : 15px;word-break: break-all;white-space: pre-wrap;word-wrap: break-word;"><center class="name">' . $object->getName() . '</center></span><br/>';
				echo '<center style="font-size :0.7em">';
				echo $object->getHtmlSummary();
				echo "</center>";
				echo '</div>';
			}
			?>
		</div>
	</div>

	<div class="col-xs-12 object" style="display: none;" id="div_conf">
		<a class="btn btn-success pull-right" id="bt_saveObject"><i class="far fa-check-circle"></i> {{Sauvegarder}}</a>
		<a class="btn btn-danger pull-right" id="bt_removeObject"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
		<a class="btn btn-default pull-right" id="bt_graphObject"><i class="fas fa-object-group"></i> {{Liens}}</a>

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
							<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Position}}</label>
							<div class="col-xs-1">
								<input type="number" class="objectAttr form-control" data-l1key="position" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Visible}}</label>
							<div class="col-sm-1">
								<input class="objectAttr" type="checkbox" data-l1key="isVisible" checked/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Masquer sur le dashboard}}</label>
							<div class="col-sm-1">
								<input class="objectAttr" type="checkbox" data-l1key="configuration" data-l2key="hideOnDashboard"/>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Icône}}</label>
							<div class="col-xs-1">
								<a class="btn btn-default btn-sm" id="bt_chooseIcon"><i class="fas fa-flag"></i> {{Choisir}}</a>
							</div>
							<div class="col-xs-2">
								<div class="objectAttr" data-l1key="display" data-l2key="icon" style="font-size : 1.5em;"></div>
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Couleur du tag}}</label>
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
						<div class="form-group">
							<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Taille sur le dashboard (1 à 12)}}</label>
							<div class="col-xs-1">
								<input type="number" class="objectAttr form-control" data-l1key="display" data-l2key="dashboard::size" />
							</div>
						</div>
						<div class="form-group">
							<label class="col-lg-2 col-md-3 col-sm-4 col-xs-6 control-label">{{Image}}</label>
							<div class="col-lg-3 col-md-4 col-sm-5 col-xs-6">
								<span class="btn btn-default btn-file">
									<i class="fas fa-cloud-upload-alt"></i> {{Envoyer}}<input  id="bt_uploadImage" type="file" name="file" style="display: inline-block;">
								</span>
								<a class="btn btn-danger" id="bt_removeBackgroundImage"><i class="fas fa-trash"></i> {{Supprimer l'image}}</a>
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
											foreach (config::byKey('object:summary') as $key => $value) {
												echo '<th style="cursor:default;">' . $value['name'] . '</th>';
											}
											?>
										</tr>
									</thead>
									<?php
									echo '<tr>';
									echo '<td style="cursor:default;">';
									echo '{{Remonter dans le résumé global}}';
									echo '</td>';
									foreach (config::byKey('object:summary') as $key => $value) {
										echo '<td>';
										echo '<input type="checkbox" class="objectAttr" data-l1key="configuration" data-l2key="summary::global::' . $key . '" />';
										echo '</td>';
									}
									echo '</tr>';
									echo '<tr>';
									echo '<tr>';
									echo '<td style="cursor:default;">';
									echo '{{Masquer en desktop}}';
									echo '</td>';
									foreach (config::byKey('object:summary') as $key => $value) {
										echo '<td>';
										echo '<input type="checkbox" class="objectAttr" data-l1key="configuration" data-l2key="summary::hide::desktop::' . $key . '" />';
										echo '</td>';
									}
									echo '</tr>';
									echo '<tr>';
									echo '<tr>';
									echo '<td>';
									echo '{{Masquer en mobile}}';
									echo '</td>';
									foreach (config::byKey('object:summary') as $key => $value) {
										echo '<td>';
										echo '<input type="checkbox" class="objectAttr" data-l1key="configuration" data-l2key="summary::hide::mobile::' . $key . '" />';
										echo '</td>';
									}
									echo '</tr>';
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
									foreach (config::byKey('object:summary') as $key => $value) {
										echo '<li class="' . $active . '"><a href="#summarytab' . $key . '" role="tab" data-toggle="tab">' . $value['icon'] . ' ' . $value['name'] . '</i>  <span class="tabnumber summarytabnumber' . $key . '"</span></a></li>';
										$active = '';
									}
									?>
								</ul>
								<div class="tab-content">
									<?php
									$active = ' active';
									foreach (config::byKey('object:summary') as $key => $value) {
										echo '<div role="tabpanel" class="tab-pane type' . $key . $active . '" data-type="' . $key . '" id="summarytab' . $key . '">';
										echo '<a class="btn btn-sm btn-success pull-right addSummary" data-type="' . $key . '"><i class="fas fa-plus-circle"></i> {{Ajouter une commande}}</a>';
										echo '<br/>';
										echo '<div class="div_summary" data-type="' . $key . '"></div>';
										echo '</div>';
										$active = '';
									}
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
