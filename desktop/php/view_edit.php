<?php
if (!isConnect('admin')) {
	throw new Exception('{{401 - Accès non autorisé}}');
}
?>

<div class="row row-overflow">
	<div class="col-lg-2 col-md-3 col-sm-4">
		<div class="bs-sidebar">
			<ul id="ul_view" class="nav nav-list bs-sidenav">
				<a id="bt_addView" class="btn btn-default" style="width : 100%;margin-top : 5px;margin-bottom: 5px;"><i class="fas fa-plus-circle"></i> {{Créer une vue}}</a>
				<li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%"/></li>
				<?php
				foreach ((view::all()) as $view) {
					$li = '';
					$li .=  '<li class="cursor li_view" data-view_id="' . $view->getId() . '">';
					$li .=  '<i class="fas fa-arrows-alt-v pull-left cursor"></i>';
					$li .=  '<a style="position:relative;left:15px;">' . trim($view->getDisplay('icon')) . ' ' . $view->getName() . '</a>';
					$li .=  '</li>';
					echo $li;
				}
				?>
			</ul>
		</div>
	</div>

	<div class="col-lg-10 col-md-9 col-sm-8" style="display: none;" id="div_view">
		<legend>
			{{Edition}}
			<div class="input-group pull-right" style="display:inline-flex">
				<span class="input-group-btn">
					<a class="btn btn-default btn-sm roundedLeft" id="bt_editView"><i class="fas fa-pencil-alt"></i> {{Configuration}}
					</a><a class="btn btn-default btn-sm" id="bt_addviewZone"><i class="fas fa-plus-circle"></i> {{Ajouter une zone}}
					</a><a class="btn btn-primary btn-sm" id="bt_viewResult"><i class="fas fa fa-eye"></i> {{Voir le résultat}}
					</a><a class="btn btn-success btn-sm" id="bt_saveView"><i class="fas fa-check-circle"></i> {{Sauvegarder}}
					</a><a class="btn btn-danger btn-sm roundedRight" id="bt_removeView"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
				</span>
			</div>
		</legend>

		<div id="div_viewZones" style="margin-top: 10px;"></div>
	</div>
</div>

<div class="modal fade" id="md_addEditviewZone">
	<div class="modal-dialog">
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
				<h4 class="modal-title">{{Ajouter/Editer viewZone}}</h4>
			</div>
			<div class="modal-body">
				<div class="alert alert-danger div_alert" style="display: none;" id="div_addEditviewZoneError"></div>
				<input id="in_addEditviewZoneEmplacement"  style="display : none;" />
				<form class="form-horizontal" onsubmit="return false;">
					<div class="form-group">
						<label class="col-sm-2 control-label">{{Nom}}</label>
						<div class="col-sm-10">
							<input id="in_addEditviewZoneName" class="form-control" placeholder="{{Nom}}" />
						</div>
					</div>
					<div class="form-group">
						<label class="col-sm-2 control-label">{{Type}}</label>
						<div class="col-sm-5">
							<select class="form-control" id="sel_addEditviewZoneType">
								<option value="widget">{{Equipement}}</option>
								<option value="graph">{{Graphique}}</option>
								<option value="table">{{Tableau}}</option>
							</select>
						</div>
					</div>
				</form>
			</div>
			<div class="modal-footer">
				<a class="btn btn-danger" data-dismiss="modal">{{Annuler}}</a>
				<a class="btn btn-success" id="bt_addEditviewZoneSave"><i class="fas fa-save"></i> {{Sauvegarder}}</a>
			</div>
		</div>
	</div>
</div>

<?php include_file('desktop', 'view_edit', 'js');?>
