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
				<li class="filter" style="margin-bottom: 5px;"><input class="filter form-control input-sm" placeholder="{{Rechercher}}" style="width: 100%" /></li>
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
					</a><a class="btn btn-default btn-sm" id="bt_copyView"><i class="fas fa-copy"></i> {{Copier}}
					</a><a class="btn btn-success btn-sm" id="bt_saveView"><i class="fas fa-check-circle"></i> {{Sauvegarder}}
					</a><a class="btn btn-danger btn-sm roundedRight" id="bt_removeView"><i class="fas fa-minus-circle"></i> {{Supprimer}}</a>
				</span>
			</div>
		</legend>

		<div id="div_viewZones" style="margin-top: 10px;"></div>
	</div>
</div>
<?php include_file('desktop', 'view_edit', 'js'); ?>